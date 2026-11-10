# LZDS Computer Laboratory Digital Logbook

## Current implementation status — 10 November 2026

The Laravel 13 application is implemented under `C:\LZDS\Projects\lzds-pclab`. It provides anonymous student check-in and check-out, server recorded timestamps, optional student IDs, per-computer station credentials, authenticated administrator routes, searchable sessions, CSV export, seed dropdown choices, and basic computer administration. The administrator overview uses the Watermelon Bionis dashboard shell adapted to live logbook metrics; the student check-in page remains a lightweight Blade form.

The server uses PHP 8.5.12, MySQL 8.0, Apache 2.4 and Asia/Manila time. The intended address is `http://192.168.0.250/`. Apache configuration was backed up to `C:\Apache24\conf\httpd.conf.backup-lzds-20261110`, its document root was changed to the app's `public` folder, and `httpd.exe -t` passed. The Apache2.4 service is running, but the restart attempt failed because this session could not open the service. The new config therefore has not been loaded by the running service. Database provisioning, elevated service restart, LAN browser checks, administrator creation and station enrollment remain pending local setup.

The server stores only SHA-256 hashes of station credentials. Per-computer enrollment links exchange their one-time secret for a new persistent credential in an encrypted HttpOnly browser cookie; the setup secret is consumed and then redirects to the logbook. A PC's enrollment must be repeated after browser profile reset or credential rotation.

## Data model

Tables include `users`, `computers`, `grade_levels`, `subjects`, `usage_reasons`, `laboratory_sessions`, `audit_logs`, `application_settings`, plus Laravel's cache, queue and session tables. `student_id` is nullable and non-unique. Session statuses are `ACTIVE`, `COMPLETED`, and `INTERRUPTED`.

The document root must remain `C:\LZDS\Projects\lzds-pclab\public`. Never map Apache to the project root. MySQL should listen only on the host and must not be opened to student PCs.

## Project layout

- Application: `C:\LZDS\Projects\lzds-pclab`
- Maintenance scripts: `C:\LZDS\Deployment\PowerShell`
- Client enrollment script: `C:\LZDS\Deployment\ClientSetup\Enroll-LabComputer.ps1`
- Application backups: `C:\LZDS\Backups`
- Database backups: `C:\LZDS\DatabaseBackups`

## Known scope limits

The initial application provides filters for name/ID, status, computer and date, with CSV export. Full weekly/monthly analytics, editing existing dropdown labels/computer names, detailed admin user management, a scheduled interrupted-session workflow, print-specific reports, and LAN/boot verification are not complete. Do not treat the system as production-ready until the deployment checklist in the Installation Guide is completed.
