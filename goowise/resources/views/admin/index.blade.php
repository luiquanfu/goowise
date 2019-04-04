<!DOCTYPE html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Goowise Advisory</title>
<link rel="shortcut icon" href="{{ $app_url }}/admin_assets/logo.ico" type="image/x-icon">
<link href="{{ $app_url }}/admin_assets/admin.css" rel="stylesheet">
</head>
<body>
<div id="app" class="app"></div>
</body>
<script>
var app_url = "{{ $app_url }}";
var api_token = "{{ $api_token }}";
var device_id = "{{ $device_id }}";
</script>
<script src="{{ $app_url }}/admin_assets/jquery-3.3.1.min.js"></script>
<script src="{{ $app_url }}/admin_assets/admin.js"></script>
</html>