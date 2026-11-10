<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} · LZDS Computer Laboratory</title>
    @vite(['resources/css/app.css','resources/js/admin.jsx'])
</head>
<body>
    <div id="logbook-admin-root" data-page-title="{{ $title }}">
        <div id="logbook-admin-content">
            @if($errors->any())<div class="notice bad" role="alert">{{ $errors->first() }}</div>@endif
            @if(session('success'))<div class="notice good" role="status">{{ session('success') }}</div>@endif
            @if(session('enrollment_url'))<div class="notice good enrollment-notice" role="status"><strong>{{ session('created_pc') }} is registered.</strong><p>Open this one-time setup address on that computer now, then save it as the Edge home page.</p><div class="copy-row"><input readonly value="{{ session('enrollment_url') }}" onclick="this.select()"><strong>Copy this link now. It will not be shown again.</strong></div></div>@endif
        </div>
    </div>
</body>
</html>
