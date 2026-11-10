<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LZDS · Computer Laboratory Dashboard</title>
    @vite(['resources/css/app.css','resources/js/admin.jsx'])
</head>
<body>
    <div id="logbook-admin-root"></div>
    <script id="logbook-dashboard-data" type="application/json">@json($dashboard)</script>
</body>
</html>
