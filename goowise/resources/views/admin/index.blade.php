<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Goowise Advisory</title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="shortcut icon" href="{{ $app_url }}/admin_assets/logo.ico" type="image/x-icon">
<link rel="stylesheet" href="admin_assets/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="admin_assets/font-awesome/font-awesome.min.css">
<link rel="stylesheet" href="admin_assets/ionicons/ionicons.min.css">
<link rel="stylesheet" href="admin_assets/adminlte/AdminLTE.min.css">
<link rel="stylesheet" href="admin_assets/adminlte/skin-blue.min.css">
<link rel="stylesheet" href="admin_assets/morris-js/morris.css">
<link rel="stylesheet" href="admin_assets/jvectormap/jquery-jvectormap.css">
<link rel="stylesheet" href="admin_assets/bootstrap-datepicker/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="admin_assets/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="admin_assets/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="stylesheet" href="{{ $app_url }}/admin_assets/admin.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="app"></div>
<script src="admin_assets/jquery/jquery.min.js"></script>
<script src="admin_assets/jquery-ui/jquery-ui.min.js"></script>
<script>$.widget.bridge('uibutton', $.ui.button);</script>
<script src="admin_assets/bootstrap/bootstrap.min.js"></script>
<script src="admin_assets/raphael/raphael.min.js"></script>
<script src="admin_assets/morris-js/morris.min.js"></script>
<script src="admin_assets/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="admin_assets/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="admin_assets/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="admin_assets/jquery-knob/jquery.knob.min.js"></script>
<script src="admin_assets/moment/moment.min.js"></script>
<script src="admin_assets/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="admin_assets/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="admin_assets/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="admin_assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="admin_assets/fastclick/fastclick.js"></script>
<script src="admin_assets/adminlte/adminlte.min.js"></script>
<script>
var app_url = "{{ $app_url }}";
var api_token = "{{ $api_token }}";
var device_id = "{{ $device_id }}";
</script>
<script src="{{ $app_url }}/admin_assets/admin.js"></script>
</body>
</html>