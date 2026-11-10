# Installation and Deployment Guide

## Existing server

Do not reinstall Apache, PHP, MySQL, Composer or Node. The app is at `C:\LZDS\Projects\lzds-pclab`; PHP modules `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `xml` and `ctype` were present. Composer installed Laravel 13 and `npm install` completed. React, Vite React, Tailwind 4, shadcn/ui and the Bionis dashboard registry files support the administrator overview. For a fresh checkout, run `composer install`, `npm install` and `npm run build` before serving.

## Secure database setup

Have the authorized MySQL administrator sign in locally using the existing root password (enter it only at MySQL's private prompt). Do not paste it into chat or save it in a script. Create an app-specific password locally and substitute that value in the SQL below in a private MySQL client:

```sql
CREATE DATABASE lzds_pclab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'lzds_pclab_app'@'127.0.0.1' IDENTIFIED BY '<a-long-random-password-created-locally>';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, REFERENCES ON lzds_pclab.* TO 'lzds_pclab_app'@'127.0.0.1';
FLUSH PRIVILEGES;
```

Set `DB_PASSWORD` in `C:\LZDS\Projects\lzds-pclab\.env` to that same secret. Restrict `.env` and `storage` write access to the Apache service identity and system administrators. Then run in PowerShell:

```powershell
Set-Location C:\LZDS\Projects\lzds-pclab
php artisan migrate --force
php artisan db:seed --force
php artisan logbook:create-admin "LZDS IT" it@lzds.local
php artisan optimize
```

The admin command asks for a password privately and requires at least 12 characters. Use a school-controlled administrator email. `APP_KEY` has already been generated; never regenerate it after sessions or station cookies are in use.

## Apache and runtime

The document root is set in the config file to `C:/LZDS/Projects/lzds-pclab/public`. The original configuration backup is `C:\Apache24\conf\httpd.conf.backup-lzds-20261110`. Apache2.4 is running, but this session could not restart it due to service-control access, so the active process may still use the prior configuration. Laravel requires `AllowOverride All` for the public `.htaccess`, `mod_rewrite`, and the preserved FastCGI handler:

```apache
SetHandler "proxy:fcgi://127.0.0.1:9000//./"
```

Validate before restarting:

```powershell
C:\Apache24\bin\httpd.exe -t
```

After validation, restart the existing Apache service from an elevated PowerShell session, using its verified service name. The expected name in the handover is `Apache2.4`, but it was not visible to this session; verify with `Get-Service *apache*` first. Do not expose MySQL port 3306 to the LAN. Permit TCP 80 only on the school LAN profile if the existing firewall blocks it.

## Acceptance checklist before launch

1. `http://192.168.0.250/` loads from the server and a lab PC.
2. Administrator login works; unauthenticated requests to `/admin` redirect to login.
3. Blank and populated Student ID check-ins both work; repeated names create separate sessions.
4. Correct station PC is shown and written in the session; invalid credentials and unregistered computers are rejected.
5. Checkout closes only the active session on that computer and calculates duration.
6. Filters, CSV export, backup scripts, and restore procedure are verified with sample data.
7. Reboot the host and repeat web and database checks.
