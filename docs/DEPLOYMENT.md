# OSCA Project Deployment Guide

## Prerequisites

### Server Environment
- PHP 8.2+ with required extensions: openssl, PDO, Mbstring, tokenizer, XML, Ctype, JSON, GMP
- MySQL 8.0+ client
- Composer
- Node.js 22+ with npm

### Access
- SSH access to target server
- sudo privileges or configured passwordless sudo (`SUDO_PASSWORD` env or NOPASSWD)
- Git repository access

---

## Deployment Steps

### 1. Clone Repository (if new server)

```bash
git clone <repo-url> /var/www/osca
cd /var/www/osca
```

Or pull latest changes:

```bash
cd /var/www/osca
git pull origin main
```

### 2. Install/Update Dependencies

```bash
# PHP dependencies
composer install --optimize-autoloader --no-dev

# Frontend assets
npm ci
npm run build
```

### 3. Environment Configuration

Create `.env` file:

```bash
cp .env.example .env
php artisan key:generate
```

Configure these values:

```env
APP_URL=https://osca.example.com
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=osca
DB_USERNAME=osca_user
DB_PASSWORD=secure_password

# JWT Secret
JWT_SECRET=your_jwt_secret_here

# Mail (for chat replies, contact form)
MAIL_MAILER=smtp
MAIL_HOST=mail.example.com
MAIL_PORT=587
MAIL_USERNAME=mail_user
MAIL_PASSWORD=mail_pass
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="OSCA Panel"
```

### 4. Database Setup

```bash
# Run migrations
php artisan migrate --force

# Seed initial data (if seeders exist)
php artisan db:seed --force
```

### 5. Storage Symlink

```bash
php artisan storage:link
```

### 6. Cache & Optimize

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Set Permissions

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache
```

### 8. Web Server Configuration

#### Nginx Example (`/etc/nginx/sites-available/osca`)

```nginx
server {
    listen 80;
    server_name osca.example.com;
    root /var/www/osca/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        proxy_pass unix:/var/run/php/php8.2-fpm.sock;
        proxy_http_version 1.1;
        proxy_set_header Connection "";
        proxy_cache_bypass $http_upgrade;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/osca /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl nginx reload
```

For HTTPS, add Let's Encrypt:

```bash
sudo certbot --nginx -d osca.example.com
```

### 9. Queue (Optional - If using queues)

```bash
php artisan queue:work --daemon
```

Or use supervisor:

```ini
[program:osca-queue]
command=php /var/www/osca/artisan queue:work --sleep=3 --tries=3 --timeout=90
autostart=true
autorestart=true
user=www-data
stdout-logfile=/var/log/osca-queue.log
```

### 10. Verify Deployment

- Visit `https://osca.example.com`
- Should redirect to `/login`
- Database data should load correctly
- Dashboard should populate with stats

---

## Post-Deployment Checklist

- [ ] `.env` file has correct database credentials
- [ ] Mailable config is correct (for user registration emails)
- [ ] Storage symlink is present (`public/storage` → `storage/app/public`)
- [ ] Frontend assets compiled (`public/build/` directory exists with manifest)
- [ ] Application cache cleared (`bootstrap/cache/config.php`, `routes.php`, `services.php`)
- [ ] Web server pointing to `/public` directory
- [ ] HTTPS configured and working
- [ ] Cron jobs set up for scheduled tasks (if any use Laravel queues/scheduling)

---

## Common Troubleshooting

### White Screen / 500 Error
```bash
php artisan config:clear
php artisan cache:clear
tail -f storage/logs/laravel.log
```

### Migrations Failing
Check `.env` DB credentials, ensure database exists and user has proper permissions.

### Queues Not Processing
```bash
php artisan queue:failed
php artisan queue:retry {job-id}
```

### Image Upload Issues
Ensure `storage/app/public/payout_proofs/` and `senior_citizens/` directories exist and have write permissions.