# ZionLab

### Every lab session, accounted for.

ZionLab is the Computer Laboratory Digital Logbook for Lord of Zion Divine School (LZDS). It replaces the paper logbook with a simple LAN-accessible app for student check-in, computer station assignment, and administrator review.

## What it does

- Lets students check in without accounts; Student ID is optional.
- Records each student's name, grade, subject, reason for computer use, computer unit, and server timestamps.
- Lets students check out from the same registered computer and records session duration.
- Identifies a computer through an administrator-issued, one-time enrollment link and a server-validated browser credential. Students do not enter a PC number.
- Gives administrators a login-protected overview of occupied and available units, today's sessions, weekly usage, and recent activity.
- Supports session search, filters, CSV export, computer registration, credential rotation, and dropdown option management.
- Preserves historical sessions by blocking deletion of computers or dropdown options that are already in use.

## Screenshots / UI

The administrator overview is adapted from the [Bionis Dashboard by Watermelon UI](https://ui.watermelon.sh/dashboard/bionis-dashboard), with its sample health data replaced by live logbook data. The student check-in screen is a separate lightweight form.

## Technology

- Laravel 13 and PHP 8.5
- MySQL 8
- Blade for server-rendered pages
- React 19, Vite, Tailwind CSS 4, shadcn/ui, and Recharts for the administrator overview
- Asia/Manila timezone

## Getting started

### Requirements

PHP with the PDO MySQL extension, Composer, Node.js and npm, and MySQL. For the LZDS LAN deployment, Apache is configured to serve only the Laravel `public` directory.

### Install

From the project directory:

```powershell
composer install
npm install
Copy-Item .env.example .env
```

Edit `.env` locally with the MySQL database name, host, username, and a private password. Do not paste secrets into chat or commit them. For a **new installation only**, generate an application key:

```powershell
php artisan key:generate
```

Keep the generated `APP_KEY` stable after users or station browsers begin using the application. Then initialize the database, build the frontend assets, and create the administrator:

```powershell
php artisan migrate --force
php artisan db:seed --force
npm run build
php artisan logbook:create-admin "LZDS IT" it@lzds.local
```

The admin command prompts for a password privately and requires at least 12 characters. Configure Apache to use the project's `public` folder as its document root before serving the app.

## Workstation setup

1. Sign in to the admin area and register a computer using its PC Unit Number (for example `PC-01`).
2. Use the one-time enrollment link on that computer with the provided PowerShell script.
3. Confirm the student logbook displays the correct assigned unit, then test a check-in and check-out.

See [Laboratory Computer Registration](docs/Laboratory-Computer-Registration.md) for the full steps.

## Documentation

- [System Overview](docs/System-Overview.md)
- [Installation and Deployment](docs/Installation-and-Deployment.md)
- [Administrator Guide](docs/Administrator-Guide.md)
- [Student Usage Guide](docs/Student-Usage-Guide.md)
- [Computer Registration](docs/Laboratory-Computer-Registration.md)
- [Backup and Restore](docs/Backup-and-Restore.md)
- [Troubleshooting](docs/Troubleshooting.md)

## Security notes

- `.env`, `vendor`, `node_modules`, and generated build files are excluded from Git.
- Never commit database passwords, Laravel app keys, backups, or computer enrollment links.
- Use an application-specific MySQL account; do not connect the app as MySQL root.
- Keep the project root private. Apache should expose only `public`.
- Rotate a computer credential if its enrollment link or browser profile may have been exposed.

## Current scope

This initial version has a dashboard, session log, CSV export, computer management, and dropdown option management. Extended analytics reports, in-page administrator account management, and editing existing dropdown labels or computer names are not included yet.
