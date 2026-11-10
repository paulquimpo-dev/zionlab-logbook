# Troubleshooting

- **Unregistered PC:** Check the exact computer's Edge profile; enroll it using its one-time URL. If the URL has expired or was used on another profile, rotate the credential in Admin → Computers and enroll again.
- **Blank/500 page:** From the project directory run `php artisan optimize:clear`; inspect `storage\logs\laravel.log`. Keep `APP_DEBUG=false` on the LAN.
- **Database connection failure:** Check MySQL80 service, database and user grants, `DB_HOST=127.0.0.1`, and `.env`. Never expose MySQL port 3306 to the lab network.
- **Apache 403/404:** Confirm DocumentRoot is only `C:/LZDS/Projects/lzds-pclab/public`, `mod_rewrite` is enabled, `AllowOverride All` is set for that directory, and Apache can read `public` and write Laravel `storage` and `bootstrap/cache`.
- **Apache PHP errors:** Preserve the configured FastCGI endpoint `127.0.0.1:9000//./`; check the FastCGI scheduled task and Apache error log.
- **Student cannot check in:** Make sure required dropdown rows are active and the computer has no ACTIVE session. An administrator can mark an abandoned active session INTERRUPTED with a documented resolution.
- **Backup script fails:** Confirm `mysqldump.exe` exists at the documented MySQL path, enter the DB password at the prompt, and ensure backup folders have write access.
