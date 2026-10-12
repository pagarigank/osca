# Deployment Verification Script

Run these commands on the deployment server after pulling the latest code:

```bash
# 1. Update code
cd /var/www/osca
git pull origin main

# 2. Update dependencies
composer install --optimize-autoloader --no-dev
npm ci
npm run build

# 3. Run new migrations (payout tables + change requests + activity log)
php artisan migrate --force

# 4. Cache and optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Fix storage permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache

# 6. Verify database tables exist
php artisan tinker --execute="
    Schema::hasTable('payout_cutoffs') && print('payout_cutoffs: OK\n');
    Schema::hasTable('payout_transactions') && print('payout_transactions: OK\n');
    Schema::hasTable('senior_citizen_change_requests') && print('senior_citizen_change_requests: OK\n');
    Schema::hasTable('activity_log') && print('activity_log: OK\n');
"

# 7. Test application
curl -I https://your-server-url
```

## Expected New Database Tables

1. `payout_cutoffs` - Payout period tracking
2. `payout_transactions` - Payout disbursement records
3. `senior_citizen_change_requests` - Encoder change request tracking
4. `activity_log` - System audit trail (spatie/laravel-activitylog)

## Post-Deployment Checks

- [ ] Dashboard loads without errors
- [ ] Payroll module: funding selection, filters, print buttons work
- [ ] Payout Cutoff Maintenance: create/edit/delete works
- [ ] Payout Transaction Flow: record payouts, upload proofs work
- [ ] ID card printing: front + back renders correctly
- [ ] Claim stubs: batch and single printing work
- [ ] QR verification: scanning stubs goes to verification page