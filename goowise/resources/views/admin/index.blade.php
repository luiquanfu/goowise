<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Goowise Advisory</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="shortcut icon" href="{{ $app_url }}/admin_assets/logo.ico" type="image/x-icon">

<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="admin_assets/bootstrap/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="admin_assets/font-awesome/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="admin_assets/ionicons/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="admin_assets/adminlte/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="admin_assets/adminlte/_all-skins.min.css">
<!-- Morris chart -->
<link rel="stylesheet" href="admin_assets/morris-js/morris.css">
<!-- jvectormap -->
<link rel="stylesheet" href="admin_assets/jvectormap/jquery-jvectormap.css">
<!-- Date Picker -->
<link rel="stylesheet" href="admin_assets/bootstrap-datepicker/bootstrap-datepicker.min.css">
<!-- Daterange picker -->
<link rel="stylesheet" href="admin_assets/bootstrap-daterangepicker/daterangepicker.css">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="admin_assets/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

<link rel="stylesheet" href="{{ $app_url }}/admin_assets/admin.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">

<!-- generic div -->
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

<div class="wrapper">

<!-- top header -->
<header class="main-header">
<a href="index2.html" class="logo">
<span class="logo-mini"><b>G</b>OO</span>
<span class="logo-lg"><b>Goo</b>Wise</span>
</a>
<nav class="navbar navbar-static-top">
<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
<span class="sr-only">Toggle navigation</span>
</a>
</nav>
</header>

<!-- side bar -->
<aside class="main-sidebar">
<section class="sidebar">
<ul class="sidebar-menu" data-widget="tree">
<li><a href="#" onclick="dashboard()"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
<li><a href="#" onclick="bankrate_index()"><i class="fa fa-th"></i> <span>Update Bank Rates</span></a></li>
</ul>
</section>
</aside>

<!-- content -->
<div id="content" class="content-wrapper"></div>

<!-- footer bar -->
<footer class="main-footer">
<div class="pull-right hidden-xs"><b>Version</b> 1.0</div>
<strong>2019 <a href="{{ url('') }}">Goowise Advisory</a></strong>
Mortgage and refinance your property
</footer>


</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="admin_assets/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="admin_assets/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="admin_assets/bootstrap/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="admin_assets/raphael/raphael.min.js"></script>
<script src="admin_assets/morris-js/morris.min.js"></script>
<!-- Sparkline -->
<script src="admin_assets/jquery-sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="admin_assets/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="admin_assets/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="admin_assets/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="admin_assets/moment/moment.min.js"></script>
<script src="admin_assets/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="admin_assets/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="admin_assets/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="admin_assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="admin_assets/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="admin_assets/adminlte/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="admin_assets/adminlte/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="admin_assets/adminlte/demo.js"></script>

<script>
var app_url = "{{ $app_url }}";
var api_token = "{{ $api_token }}";
var device_id = "{{ $device_id }}";
</script>
<script src="{{ $app_url }}/admin_assets/admin.js"></script>

</body>
</html>