<!DOCTYPE html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">    
<title>Goowise Advisory</title>
<link rel="shortcut icon" href="{{ $app_url }}/admin_assets/logo.ico" type="image/x-icon">
<link href="{{ $app_url }}/admin_assets/admin.css" rel="stylesheet">
</head>
<body>

<div id="popup_overlay" class="popup_overlay" onclick="popup_hide()"></div>
<div id="popup_content" class="popup_content"></div>
<div id="cropper_box" class="cropper_box">
<div id="cropper_container" class="cropper_container"></div>
</div>
<div id="cropper_footer" class="cropper_footer"></div>
<div id="loading_overlay" class="loading_overlay"></div>
<div id="loading_content" class="loading_content">
<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
</div>

<div id="app" class="app"></div>
</body>
<script>
var app_url = "{{ $app_url }}";
</script>
<script src="{{ $app_url }}/admin_assets/jquery-3.3.1.min.js"></script>
<script src="{{ $app_url }}/admin_assets/admin.js"></script>
</html>