<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#123858">
    <title>Administrator sign in · LZDS Computer Laboratory</title>
    @vite('resources/js/login.jsx')
</head>
<body>
    <div id="auth-01-root"></div>
    <script id="auth-01-props" type="application/json">@json(['csrfToken'=>csrf_token(),'emailValue'=>old('email'),'error'=>$errors->first('email')])</script>
</body>
</html>
