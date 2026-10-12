# OSCA Deployment — Notes, Lessons Learned & Server Reference

> Consolidated on 2026-10-12 from the actual deploy to `192.168.100.201`.
> This is the **real-world** companion to `DEPLOYMENT.md`. Read this before deploying.

---

## 1. Target Server Reference

| Item | Value |
|------|-------|
| Host | `192.168.100.201` (hostname `sithomeserver`) |
| SSH user | `sitadmin` (password auth; no key installed) |
| App path | `/var/www/osca` |
| Public URL | `https://osca.sititsolutions.com` |
| OS | Ubuntu, nginx `1.28.3`, PHP **8.5.4** (CLI + FPM), MySQL, Node v22 |
| nginx root | `/var/www/osca/public` |
| DB | MySQL, database `osca`, user `osca_user`, `DB_HOST=localhost` |

**App is NOT under git on the server** — there is no `.git` in `/var/www/osca`. Files are placed via SFTP/rsync from the dev machine. `git pull` on the server will fail.

### Other projects on the box
`/var/www/sithemr` (a second Laravel app) also exists. When scripting `find / -name artisan`, be specific about the path.

---

## 2. How We Actually Deploy (SFTP, not git)

Because there's no git checkout on the server, deploy changed files directly:

1. Determine changed files locally: `git diff --name-status <last_deployed_commit>..HEAD`
2. Back up the corresponding server files first (see §7).
3. SFTP each changed file to `/var/www/osca/<relpath>`.
4. Fix ownership: `sudo chown sitadmin:www-data <uploaded paths>`.
5. Install any new composer packages (see §4).
6. `php artisan migrate --force` if migrations were added.
7. Rebuild frontend assets (see §5 — **ownership gotcha**).
8. Rebuild caches.
9. Bring app out of maintenance mode (see §6 — **ownership gotcha**).
10. Smoke-test routes with `curl`.

> **Drift risk:** because the server isn't git-managed, it's easy to miss a file that
> changed but wasn't in the commit diff (this actually happened — see §8, the stale
> `Payroll.php`). After any deploy, `diff` the deployed file against local (md5) for the
> files a page depends on before declaring success.

---

## 3. Maintenance Mode — Use It

Always wrap deploys so users don't hit a half-updated app:

```bash
cd /var/www/osca && php artisan down --render=errors::503
# ... do work ...
cd /var/www/osca && php artisan up
```

**GOTCHA — taking it down again:** the maintenance lock file is
`storage/framework/down`. If you `chown` `storage` to `www-data` during the deploy,
then `php artisan up` run as `sitadmin` fails with
`unlink(storage/framework/down): Permission denied`. Fix:

```bash
sudo rm -f /var/www/osca/storage/framework/down
```

Order matters: bring the app UP *before* chowning storage to www-data, or plan to
`sudo rm` the down file. (We hit this — the site sat at 503 until we removed it.)

---

## 4. Composer — PHP 8.5.4 vs the Lock File (IMPORTANT)

The server runs **PHP 8.5.4**, but `composer.lock` was generated for `php ^8.2`, and
`lcobucci/clock 2.3.0` (a `tymon/jwt-auth` dependency) declares
`php ~8.1.0 || ~8.2.0`. Consequences:

- `composer require <pkg>` → **fails** ("requirements could not be resolved") because
  require triggers a full dependency re-resolve that trips the PHP-version constraint.
- `composer install` → also refuses ("lock file does not contain a compatible set of
  packages") for the same reason.
- **But the code already runs fine on 8.5.4** — the constraint is a packaging guard,
  not a real incompatibility.

**Workaround that works** — install from the lock, skipping ONLY the php-version gate
(extensions are still verified):

```bash
cd /var/www/osca
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader \
  --no-scripts --ignore-platform-req=php
composer dump-autoload -o
```

For a brand-new package not yet in the lock (we added `barryvdh/laravel-dompdf` and
`spatie/laravel-activitylog` this way): add it to `composer.json`/`composer.lock`
**locally** (where PHP is 8.2 and resolution works), upload `composer.json` +
`composer.lock`, then run the `composer install --ignore-platform-req=php` above.

> Do NOT try to "fix" this by upgrading lcobucci on the server — leave the locked JWT
> stack alone; it works. Only bypass the platform check.

---

## 5. Frontend Build — `public/build` Ownership Gotcha

`public/build/` ends up owned by `www-data` after a deploy (we chown it for the web
server). `npm run build` run as `sitadmin` then **fails**:

```
EACCES: permission denied, rmdir '/var/www/osca/public/build/assets'
```

`sitadmin` cannot `chown` the dir away from `www-data` without sudo. The working
sequence is:

```bash
sudo chown -R sitadmin:www-data /var/www/osca/public/build   # needs sudo
cd /var/www/osca && npm run build
sudo chown -R www-data:www-data /var/www/osca/public/build   # hand back to web server
```

If you skip the final chown-back, nginx/FPM may serve 403 on assets.

---

## 6. Caches & Stale Bootstrap Cache

### The "Laravel\Pail\PailServiceProvider not found" trap
After a composer install/downgrade that removes a package, the **cached**
`bootstrap/cache/packages.php` + `services.php` can still reference the old provider.
Every `php artisan` command then dies with
`Class "Laravel\Pail\PailServiceProvider" not found` — even `php artisan optimize:clear`.

**Fix (artisan is broken, so delete the files directly):**

```bash
rm -f /var/www/osca/bootstrap/cache/packages.php \
      /var/www/osca/bootstrap/cache/services.php \
      /var/www/osca/bootstrap/cache/config.php \
      /var/www/osca/bootstrap/cache/routes-*.php \
      /var/www/osca/bootstrap/cache/events.php
cd /var/www/osca && php artisan --version   # should work now
```

### Normal recache after a deploy
```bash
cd /var/www/osca
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If a Livewire page renders stale/malformed, `php artisan view:clear` specifically —
compiled views live in `storage/framework/views/`.

---

## 7. Backups Before Touching Anything

Always snapshot the server files you're about to overwrite:

```bash
BAK=/home/sitadmin/osca_backup_$(date +%Y%m%d_%H%M%S)
mkdir -p $BAK
cp -a /var/www/osca/app $BAK/
cp -a /var/www/osca/resources $BAK/
cp -a /var/www/osca/routes $BAK/
cp -a /var/www/osca/composer.json /var/www/osca/composer.lock $BAK/
cp -a /var/www/osca/vendor $BAK/          # large — only if composer will change
cd /var/www/osca && php artisan migrate:status > $BAK/migrate_status_before.txt
```

**Never touch `.env`.** It holds `APP_KEY`, `JWT_SECRET`, DB password and is
server-specific. Changing `APP_KEY` invalidates all sessions/encrypted cookies.

---

## 8. The Tailwind / CSS Activation Bug (subtle — cost us the most time)

**Symptom:** the "Create Cutoff" modal on `/payout-cutoff` rendered malformed — no
overlay dim, wrong width, no rounded corners / focus rings. No PHP error, no 500; the
files on the server matched local byte-for-byte.

**Root cause:** `resources/css/app.css` is a **hand-written custom design system**
(classes like `.modal-backdrop`, `.modal-box`, `.btn`, `.card`, `.data-table`,
`.form-input`, `.badge-active`). Tailwind v4 is installed and wired as a Vite plugin
(`vite.config.js`), but `app.css` **never had `@import "tailwindcss";`** — so Tailwind
was inert. Newer blades (payout-cutoff, payout-transaction-flow) were written with
**raw Tailwind utility classes** (`bg-slate-900`, `backdrop-blur-sm`, `max-w-md`,
`flex`, `gap-2`, `focus:ring-blue-500`) that were therefore **never generated**. CSS
was only ~19KB and contained none of them.

**Diagnosis:** grep the built CSS for the classes the modal uses:

```bash
CSS=$(ls -t /var/www/osca/public/build/assets/app-*.css | head -1)
for cls in bg-slate-900 backdrop-blur-sm max-w-md rounded-xl gap-2; do
  grep -q "$cls" "$CSS" && echo "$cls FOUND" || echo "$cls *** MISSING ***"
done
```

**Fix (two parts, both applied):**
1. Activated Tailwind v4 by adding `@import "tailwindcss";` at the very top of
   `resources/css/app.css` (imports must be first). Build output jumped 19KB → 59KB
   and all utilities generate. This also fixes `payout-transaction-flow.blade.php`.
2. Rewrote `payout-cutoff-maintenance.blade.php` to use the project's **real custom
   classes** (`.modal-backdrop > .modal-box > .modal-header/.modal-body/.modal-footer`,
   `.form-input`, `.badge-active/.badge-inactive`, `@error(...)` → `.text-danger`),
   matching the `user-maintenance` modal — so core structure doesn't depend on Tailwind.

**RULES going forward:**
- Before using a class in any blade, confirm it exists: either it's a custom class in
  `app.css`, or it's a Tailwind utility (which now generates — **run `npm run build`**
  after adding new utility classes, or they won't exist).
- Verify modal/utility classes are present in the *built* CSS, not the source.
- `focus:ring-blue-500` emits under a focus-visible variant in v4 — that's normal.

---

## 9. The Stale `Payroll.php` Bug (drift from no-git deploy)

**Symptom:** `/payroll` → 500 `Undefined variable $selected_barangay_id` in
`payroll.blade.php`. The blade references `$selected_barangay_id` /
`$selected_purok_id` / `$selected_purok_leader_id` for the print-route links and filters.

**Root cause:** the deployed `app/Livewire/Payroll.php` was an **older version** that
only declared `$fundings` and `$selected_funding_id` — it lacked the barangay/purok/
purok_leader filter properties and `getSelectedFundingProperty()`. The blade expected
them, so the view blew up. It wasn't in the commit diff I deployed from, so my file
list missed it — classic no-git-on-server drift.

**Fix:** uploaded the current local `Payroll.php` + `payroll.blade.php`, then
`php artisan view:clear`. Blade render smoke test passed (`RENDER_OK`), live route went
500 → 302.

**Takeaway:** after deploying, for the page in question, verify the deployed component's
public properties actually cover every variable the blade uses. A quick md5 compare of
local vs remote for the component + view catches this.

---

## 10. Diagnosing a 500 in a Livewire Blade (method that worked)

1. `curl -s -o /dev/null -w '%{http_code}' <url>` — 302 = login-gated (not an error),
   500 = real failure. **Authenticate** if needed; an unauthenticated curl only ever
   shows 302.
2. `tail -120 /var/www/osca/storage/logs/laravel.log` — the stack trace names the
   compiled view file and the exact error (e.g. `Undefined variable $x`).
3. Cross-check the blade variable against the component's `public $props` / `render()`
   data array.
4. Fix, upload, `php artisan view:clear`, re-curl.

**Rendering a Livewire view server-side to catch undefined vars without a browser:**

```php
<?php
require '/var/www/osca/vendor/autoload.php';
$app = require '/var/www/osca/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
view()->share('errors', new Illuminate\Support\ViewErrorBag); // so @error works
$html = view('livewire.payout-cutoff-maintenance', [
    'cutoffs' => \App\Models\PayoutCutoff::with('funding')->get(),
    'fundings' => \App\Models\Funding::where('is_active',true)->get(),
    'isModalOpen' => true, 'isEditMode' => false,
])->render();
echo "RENDER_OK len=".strlen($html)."\n";
```

Run with `php /tmp/render.php` from `/var/www/osca`.

---

## 11. New Packages & Migrations Added This Deploy

- **Packages:** `barryvdh/laravel-dompdf` (payroll PDF), `spatie/laravel-activitylog`
  (audit trail — `SeniorCitizen` model uses the `LogsActivity` trait).
- After installing activitylog, publish its config (migrations reference
  `config('activitylog.*')`):
  ```bash
  php artisan vendor:publish \
    --provider="Spatie\Activitylog\ActivitylogServiceProvider" \
    --tag="activitylog-config"
  ```
  Our own `activity_log` migrations already exist, so no need to publish those.
- **New tables (migrated successfully):**
  `payout_cutoffs`, `payout_transactions`, `senior_citizen_change_requests`,
  `activity_log`.

---

## 12. Verify-After-Deploy Checklist

```bash
# tables
cd /var/www/osca && php artisan migrate:status | tail -15   # expect 0 Pending
# routes
php artisan route:list | grep -iE 'payout|pending'
# caches clean
php artisan optimize:clear
# live smoke (302 to login = healthy & auth-gated; 500 = broken)
curl -s -o /dev/null -w '%{http_code}\n' https://osca.sititsolutions.com
curl -s -o /dev/null -w '%{http_code}\n' https://osca.sititsolutions.com/login   # 200
curl -s -o /dev/null -w '%{http_code}\n' https://osca.sititsolutions.com/payroll # 302
curl -s -o /dev/null -w '%{http_code}\n' https://osca.sititsolutions.com/payout-cutoff
# maintenance lock gone
ls /var/www/osca/storage/framework/down || echo "maintenance OFF (good)"
# CSS assets present + manifest current
ls -t /var/www/osca/public/build/assets/app-*.css | head -1
grep -o 'assets/app-[^"]*\.css' /var/www/osca/public/build/manifest.json
```

Manual click-through after deploy: login → Dashboard → Payroll (filters + 3 print
buttons) → Payout Cutoffs (open Create modal — **check it's styled**, see §8) →
Payout Transactions (record a payout, webcam/upload proof).

---

## 13. Quick "Oh No" Recovery

```bash
# site stuck at 503 (maintenance lock owned by www-data)
sudo rm -f /var/www/osca/storage/framework/down

# artisan totally broken (stale bootstrap cache)
rm -f /var/www/osca/bootstrap/cache/{packages,services,config,events}.php \
      /var/www/osca/bootstrap/cache/routes-*.php

# restore a file from the backup taken this deploy
cp -a /home/sitadmin/osca_backup_<TS>/<relpath> /var/www/osca/<relpath>

# roll back a migration (only if truly needed)
cd /var/www/osca && php artisan migrate:rollback --step=1
```
