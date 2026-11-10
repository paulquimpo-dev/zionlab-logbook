# Laboratory Computer Registration

Register each physical PC once while signed in as an administrator:

1. Open Admin → Computers, enter its assigned unit (for example `PC-01`) and a helpful computer name, then select Register.
2. Copy the one-time enrollment address from the confirmation immediately. It contains a secret and is not saved in readable form by the server.
3. On the target PC, sign in to Windows as an administrator. Copy `C:\LZDS\Deployment\ClientSetup\Enroll-LabComputer.ps1` to that PC (or run it from an approved admin share).
4. Open PowerShell and run:

```powershell
powershell -ExecutionPolicy Bypass -File .\Enroll-LabComputer.ps1 -EnrollmentUrl 'http://192.168.0.250/station/PASTE-ONE-TIME-SECRET'
```

The script opens the enrollment link in Edge, then creates a common desktop shortcut to `http://192.168.0.250/`. Verify the page shows the expected PC number and do a test check-in/out. Keep the Edge profile and cookie; clearing browser data requires rotation/re-enrollment. For a shared kiosk profile, lock down access to browser settings and the Windows account. Do not send station links to students or store them in shared documents.

This design uses an enrollment URL once because browsers cannot reliably reveal Windows hostnames to websites. The cookie is encrypted by Laravel and HttpOnly; the database stores a SHA-256 hash. A user with local access to the PC's browser profile could still use that station, so restrict those profiles. Rotate credentials after suspected misuse or PC reassignment.
