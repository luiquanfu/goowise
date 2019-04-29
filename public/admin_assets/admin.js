initialize();

function popup_show(html)
{
    $('#popup_content').html(html);
    $('#popup_overlay').show();
    $('#popup_content').show();
}

function popup_hide()
{
    $('#popup_overlay').hide();
    $('#popup_content').hide();
    $('#popup_content').html('');
}

function loading_show()
{
    $('#loading_overlay').show();
    $('#loading_content').show();
}

function loading_hide()
{
    $('#loading_overlay').hide();
    $('#loading_content').hide();
}

function cropper_show(html_container, html_footer)
{
    $('#cropper_container').html(html_container);
    $('#cropper_footer').html(html_footer);
    $('#cropper_box').show();
    $('#cropper_footer').show();
}

function cropper_hide()
{
    $('#cropper_box').hide();
    $('#cropper_footer').hide();
}

function initialize()
{
    var data = {};
    data.api_token = api_token;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/initialize';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
		var error = response.error;
        
        if(error != 0)
        {
            login_display();
            return;
        }

        initialize_display();
        admin_display();
	}
    $.ajax(ajax);
}

function initialize_display()
{
    var html = '';

    // generic div
    html += '<div id="popup_overlay" class="popup_overlay" onclick="popup_hide()"></div>';
    html += '<div id="popup_content" class="popup_content"></div>';
    html += '<div id="cropper_box" class="cropper_box">';
    html += '<div id="cropper_container" class="cropper_container"></div>';
    html += '</div>';
    html += '<div id="cropper_footer" class="cropper_footer"></div>';
    html += '<div id="loading_overlay" class="loading_overlay"></div>';
    html += '<div id="loading_content" class="loading_content">';
    html += '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>';
    html += '</div>';

    html += '<div class="wrapper">';

    // top header
    html += '<header id="header" class="main-header">';
    html += '<a href="' + app_url + '/admin" class="logo">';
    html += '<span class="logo-mini"><b>G</b>OO</span>';
    html += '<span class="logo-lg"><b>Goo</b>Wise</span>';
    html += '</a>';
    html += '<nav class="navbar navbar-static-top">';
    html += '<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">';
    html += '<span class="sr-only">Toggle navigation</span>';
    html += '</a>';
    html += '</nav>';
    html += '</header>';

    // sidebar
    html += '<aside class="main-sidebar">';
    html += '<section class="sidebar">';
    html += '<ul class="sidebar-menu" data-widget="tree">';
    html += '<li><a href="#" onclick="admin_display()"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>';
    html += '<li><a href="#" onclick="bankrate_list()"><i class="fa fa-th"></i> <span>Update Bank Rates</span></a></li>';
    html += '<li><a href="#" onclick="logout()"><i class="fa fa-power-off"></i> <span>Logout</span></a></li>';
    html += '<li><a href="#" onclick="testing()"><i class="fa fa-fire"></i> <span>Testing</span></a></li>';
    html += '</ul>';
    html += '</section>';
    html += '</aside>';

    // content
    html += '<div id="content" class="content-wrapper"></div>';

    // footer
    html += '<footer id="footer" class="main-footer">';
    html += '<div class="pull-right hidden-xs"><b>Version</b> 1.0</div>';
    html += '<strong>2019 <a href="' + app_url + '/admin">Goowise Advisory</a></strong>';
    html += ' Mortgage and refinance your property';
    html += '</footer>';
    html += '</div>';

    $('#app').html(html);

    var body_height = $(window).height();
    var header_height = $('#header').outerHeight();
    var footer_height = $('#footer').outerHeight();
    var content_height = $('#content').outerHeight();
    var minimum_height = body_height - header_height - footer_height;
    if(content_height < minimum_height) $('#content').height(minimum_height);
}

function login_display()
{
    var html = '';
    html += '<div class="login-box">';
    html += '<div class="login-logo">';
    html += '<img src="' + app_url + '/admin_assets/logo.jpg" style="width: 100%;">';
    html += '</div>';
    html += '<div class="login-box-body">';
    html += '<p class="login-box-msg">Admin Login</p>';
    html += '<div class="form-group has-feedback">';
    html += '<input id="email" type="email" class="form-control" placeholder="Email">';
    html += '<span class="glyphicon glyphicon-envelope form-control-feedback"></span>';
    html += '</div>';
    html += '<div class="form-group has-feedback">';
    html += '<input id="password" type="password" class="form-control" placeholder="Password">';
    html += '<span class="glyphicon glyphicon-lock form-control-feedback"></span>';
    html += '</div>';
    html += '<div class="row">';
    html += '<div class="col-xs-8">';
    html += '</div>';
    html += '<div class="col-xs-4">';
    html += '<div class="btn btn-primary btn-block btn-flat" onclick="login_submit()">Sign In</div>';
    html += '</div>';
    html += '</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';

    $('#app').html(html);
}

function login_submit()
{
    $('#result').html('<div class="text-light-blue">Please wait...</div>');
    loading_show();

    var data = {};
    data.email = $('#email').val();
    data.password = $('#password').val();
    data.device_id = device_id;
    data.device_type = 'website';
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/login';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
        var message = response.message;

		if(error == 1)
		{
			$('#result').html('<div class="text-red">' + message + '</div>');
			return;
		}
		
        $('#result').html('<div class="text-green">' + message + '</div>');
        initialize_display();
        admin_display();
	}
    $.ajax(ajax);
}

function logout()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/logout';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
        login_display();
	}
    $.ajax(ajax);
}

function admin_display()
{
    var html = '';

    // header
    html += '<section class="content-header">';
    html += '<h1>';
    html += 'Dashboard';
    html += '<small>summary of all bank rates</small>';
    html += '</h1>';
    html += '</section>';

    // content
    html += '<section class="content">';
    html += '<div class="row">';
    html += '<div class="col-xs-12">';
    html += '<div class="box">';
    html += '<div class="box-header">';
    html += '<h3 class="box-title">Responsive Hover Table</h3>';
    html += '';
    html += '<div class="box-tools">';
    html += '<div class="input-group input-group-sm" style="width: 150px;">';
    html += '<input type="text" name="table_search" class="form-control pull-right" placeholder="Search">';
    html += '';
    html += '<div class="input-group-btn">';
    html += '<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '<div class="box-body table-responsive no-padding">';
    html += '<table class="table table-hover">';
    html += '<tr>';
    html += '<th>ID</th>';
    html += '<th>User</th>';
    html += '<th>Date</th>';
    html += '<th>Status</th>';
    html += '<th>Reason</th>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>183</td>';
    html += '<td>John Doe</td>';
    html += '<td>11-7-2014</td>';
    html += '<td><span class="label label-success">Approved</span></td>';
    html += '<td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>219</td>';
    html += '<td>Alexander Pierce</td>';
    html += '<td>11-7-2014</td>';
    html += '<td><span class="label label-warning">Pending</span></td>';
    html += '<td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>657</td>';
    html += '<td>Bob Doe</td>';
    html += '<td>11-7-2014</td>';
    html += '<td><span class="label label-primary">Approved</span></td>';
    html += '<td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>175</td>';
    html += '<td>Mike Doe</td>';
    html += '<td>11-7-2014</td>';
    html += '<td><span class="label label-danger">Denied</span></td>';
    html += '<td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>';
    html += '</tr>';
    html += '</table>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</section>';
    $('#content').html(html);
}

function bankrate_list()
{
    var html = '';

    // header
    html += '<section class="content-header">';
    html += '<h1>';
    html += 'Dashboard';
    html += '<small>summary of all bank rates</small>';
    html += '</h1>';
    html += '</section>';

    // content
    html += '<section class="content">';
    html += '<div class="row">';
    html += '<div class="col-md-12">';
    html += '<div class="box box-primary">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Quick Example</h3>';
    html += '</div>';
    html += '<div class="box-body">';
    html += '<div class="form-group">';
    html += '<label for="exampleInputEmail1">Email address</label>';
    html += '<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">';
    html += '</div>';
    html += '<div class="form-group">';
    html += '<label for="exampleInputPassword1">Password</label>';
    html += '<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">';
    html += '</div>';
    html += '<div class="form-group">';
    html += '<label for="exampleInputFile">File input</label>';
    html += '<input type="file" id="exampleInputFile">';
    html += '<p class="help-block">Example block-level help text here.</p>';
    html += '</div>';
    html += '<div class="checkbox">';
    html += '<label>';
    html += '<input type="checkbox"> Check me out';
    html += '</label>';
    html += '</div>';
    html += '</div>';
    html += '<div class="box-footer">';
    html += '<button type="submit" class="btn btn-primary">Submit</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</section>';
    $('#content').html(html);
}

function bankrate_create()
{

}