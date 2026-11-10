# Backup and Restore Guide

Run the app backup from an administrator PowerShell:

```powershell
powershell -ExecutionPolicy Bypass -File C:\LZDS\Deployment\PowerShell\Backup-LzdsApplication.ps1
```

Run the database backup:

```powershell
powershell -ExecutionPolicy Bypass -File C:\LZDS\Deployment\PowerShell\Backup-LzdsDatabase.ps1
```

MySQL prompts locally for the app user's password. Backups are timestamped in `C:\LZDS\Backups` and `C:\LZDS\DatabaseBackups`; each script prints a SHA-256 checksum. Store a second protected copy offline. Avoid putting passwords in scripts or command arguments.

To restore, first stop Apache and preserve the current application and database. Expand the chosen app ZIP back to `C:\LZDS\Projects\lzds-pclab`. Open the MySQL client with `mysql --user=lzds_pclab_app --password lzds_pclab`; enter the password at its private prompt, then run `SOURCE C:/LZDS/DatabaseBackups/your-backup.sql;` and `exit`. Confirm `.env` has the correct app key and DB settings, run `php artisan optimize:clear`, validate Apache config, restart services and verify a login/session. Test restore using a separate staging database before relying on backups.
