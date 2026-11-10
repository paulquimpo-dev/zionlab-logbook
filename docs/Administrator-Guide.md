# Administrator User Guide

Sign in at `/admin/login`. Create or update the LZDS IT administrator locally with `php artisan logbook:create-admin "LZDS IT" it@lzds.local`. This asks for the password without echoing it. Administrator routes use Laravel authentication; there are no student accounts.

The dashboard summarizes registered computers, active sessions, completed sessions and today's sessions. Use Sessions to search name or Student ID, filter status/computer/date, and export the matching records as CSV. Use Computers to register a PC by its PC Unit Number, issue a one-time enrollment link, rotate its credential, activate/deactivate, or delete it. Use Dropdowns to add, activate/deactivate, or delete grade levels, subjects and reasons. Deletion is blocked whenever session history references that computer or option; deactivate it to keep the historical records.

The enrollment secret is shown only in the response immediately after registration or rotation. Open it on the intended computer and discard it after use. Rotation invalidates the old station cookie and changes any active session on that computer to INTERRUPTED. Review and record a resolution for incomplete sessions. Important session/computer operations are written to `audit_logs`.

Current limitations: labels can be deactivated but not renamed in the initial screens; administrator password changes and multiple role levels are not available in the UI. Use a secure local account recovery/update procedure before production deployment.
