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
	ajax.url = app_url + '/advisor/initialize';
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

        var advisor = response.advisor;
        var last_visit = advisor.last_visit;
        if(last_visit == '')
        {
            dashboard_index();
            return;
        }

        last_visit = JSON.parse(last_visit);
        if(last_visit.page == 'dashboard_listing')
        {
            dashboard_index();
            return;
        }
        if(last_visit.page == 'client_listing')
        {
            client_index();
            return;
        }
        if(last_visit.page == 'profile_edit')
        {
            profile_edit();
            return;
        }
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
    html += '<a href="' + app_url + '/advisor" class="logo">';
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
    html += '<li><a href="#" onclick="dashboard_index()"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>';
    html += '<li><a href="#" onclick="client_index()"><i class="fa fa-users"></i> <span>Clients</span></a></li>';
    html += '<li><a href="#" onclick="profile_edit()"><i class="fa fa-user"></i> <span>My Profile</span></a></li>';
    html += '<li><a href="#" onclick="logout()"><i class="fa fa-power-off"></i> <span>Logout</span></a></li>';
    html += '</ul>';
    html += '</section>';
    html += '</aside>';

    // content
    html += '<div id="content" class="content-wrapper"></div>';

    // footer
    html += '<footer id="footer" class="main-footer">';
    html += '<div class="pull-right hidden-xs"><b>Version</b> 1.0</div>';
    html += '<strong>2019 <a href="' + app_url + '/advisor">Goowise Advisory</a></strong>';
    html += ' Mortgage and refinance your property';
    html += '</footer>';
    html += '</div>';

    $('#app').html(html);
    $('body').layout('fix');
}

function login_display()
{
    var html = '';
    html += '<div class="login-box">';
    html += '<div class="login-logo">';
    html += '<img src="' + app_url + '/advisor_assets/logo.jpg" style="width: 100%;">';
    html += '</div>';
    html += '<div class="login-box-body">';
    html += '<p class="login-box-msg">Advisor Login</p>';
    html += '<div class="form-group has-feedback">';
    html += '<input id="email" type="email" class="form-control" placeholder="Email" onkeyup="login_onkeyup(event, \'email\')">';
    html += '<span class="glyphicon glyphicon-envelope form-control-feedback"></span>';
    html += '</div>';
    html += '<div class="form-group has-feedback">';
    html += '<input id="password" type="password" class="form-control" placeholder="Password" onkeyup="login_onkeyup(event, \'password\')">';
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

function login_onkeyup(event, position)
{
    if(event.keyCode == 13)
    {
        if(position == 'email')
        {
            $('#password').focus();
            return;
        }

        login_submit();
    }
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
	ajax.url = app_url + '/advisor/login';
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
        
        var advisor = response.advisor;
        api_token = advisor.api_token;
        
        $('#result').html('<div class="text-green">' + message + '</div>');
        initialize_display();
        dashboard_index();
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
	ajax.url = app_url + '/advisor/logout';
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

function advisor_display()
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
    html += '<div class="box box-primary">';
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

function client_index()
{
    app_data = {};
    app_data.page = 1;
    app_data.sort = 'owner_name';
    app_data.direction = 'asc';
    app_data.filter_name = '';
    app_data.filter_nric = '';
    app_data.filter_mobile = '';
    app_data.filter_email = '';
    client_list();
}

function client_list()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.page = app_data.page;
    data.sort = app_data.sort;
    data.direction = app_data.direction;
    data.filter_name = app_data.filter_name;
    data.filter_nric = app_data.filter_nric;
    data.filter_mobile = app_data.filter_mobile;
    data.filter_email = app_data.filter_email;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/advisor/client/listing';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
        
        if(error == 99)
        {
            login_display();
            return;
        }

        var clients = response.clients;
        var html = '';

        // filter start
        html += '<section class="content-header">';
        html += '<h1>';
        html += 'Client Management';
        html += '<small>Listing of all clients</small>';
        html += '</h1>';
        html += '</section>';

        // filter clients
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Filters</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // filter_name
        html += '<div class="col-md-6">';
        html += '<div class="form-group">';
        html += '<label>Client Name</label>';
        html += '<input id="filter_name" type="text" class="form-control" value="' + app_data.filter_name + '">';
        html += '</div>';
        html += '</div>';

        // filter_nric
        html += '<div class="col-md-6">';
        html += '<div class="form-group">';
        html += '<label>Client NRIC</label>';
        html += '<input id="filter_nric" type="text" class="form-control" value="' + app_data.filter_nric + '">';
        html += '</div>';
        html += '</div>';

        // filter_mobile
        html += '<div class="col-md-6">';
        html += '<div class="form-group">';
        html += '<label>Client Mobile</label>';
        html += '<input id="filter_mobile" type="text" class="form-control" value="' + app_data.filter_mobile + '">';
        html += '</div>';
        html += '</div>';

        // filter_email
        html += '<div class="col-md-6">';
        html += '<div class="form-group">';
        html += '<label>Client Email</label>';
        html += '<input id="filter_email" type="text" class="form-control" value="' + app_data.filter_email + '">';
        html += '</div>';
        html += '</div>';

        // filter end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="client_filter()">Filter</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        // create clients
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="width15"></div>';
        html += '<div class="btn btn-success" onclick="client_create()">Create Client</div>';
        html += '</div>';
        html += '</div>';

        // list clients
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-xs-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header">';
        html += '<h3 class="box-title">client List</h3>';
        html += '</div>';
        html += '<div class="box-body table-responsive no-padding">';
        html += '<table class="table table-hover">';
        html += '<tr>';
        html += '<th role="button" onclick="client_sorting(\'owner_name\')">Name</th>';
        html += '<th role="button" onclick="client_sorting(\'owner_nric\')">NRIC</th>';
        html += '<th role="button" onclick="client_sorting(\'owner_mobile\')">Mobile</th>';
        html += '<th role="button" onclick="client_sorting(\'owner_email\')">Email</th>';
        html += '<th role="button" onclick="client_sorting(\'postal_code\')">Address</th>';
        html += '<th role="button" onclick="client_sorting(\'loan_amount\')">Loan Amount</th>';
        html += '<th>Actions</th>';
        html += '</tr>';
        for(i in clients)
        {
            var client = clients[i];

            html += '<tr>';
            html += '<td>' + client.owner_name + '<br>' + client.joint_name + '</td>';
            html += '<td>' + client.owner_nric + '<br>' + client.joint_nric + '</td>';
            html += '<td>' + client.owner_mobile + '<br>' + client.joint_mobile + '</td>';
            html += '<td>' + client.owner_email + '<br>' + client.joint_email + '</td>';
            html += '<td>Singapore ' + client.postal_code + '<br>' + client.property_address + '</td>';
            html += '<td>' + client.bank_name + '<br>' + client.loan_amount + '</td>';
            html += '<td>';
            html += '<div class="btn btn-primary" onclick="client_edit(\'' + client.id + '\')"><i class="fa fa-edit"></i></div>';
            html += '<div class="width5"></div>';
            html += '<div class="btn btn-danger" onclick="client_remove(\'' + client.id + '\')"><i class="fa fa-trash"></i></div>';
            html += '</td>';
            html += '</tr>';
        }
        html += '</table>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';
        $('#content').html(html);
	}
    $.ajax(ajax);
}

function client_filter()
{
    app_data.filter_name = $('#filter_name').val();
    app_data.filter_nric = $('#filter_nric').val();
    app_data.filter_email = $('#filter_email').val();
    app_data.filter_mobile = $('#filter_mobile').val();
    app_data.page = 1;
    client_list();
}

function client_paging(page)
{
    app_data.page = page;
    client_list();
}

function client_sorting(sort)
{
    if(sort == app_data.sort)
    {
        if(app_data.direction == 'asc')
        {
            app_data.direction = 'desc';
        }
        else
        {
            app_data.direction = 'asc';
        }
    }
    if(sort != app_data.sort)
    {
        app_data.sort = sort;
        app_data.direction = 'asc';
    }
    client_list();
}

function client_create()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/advisor/client/create';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
        var message = response.message;
        
        if(error == 99)
        {
            login_display();
            return;
        }
		
		if(error != 0)
		{
			$('#content').html(message);
			return;
		}
        
        var banks = response.banks;

        var html = '';

        // header
        html += '<section class="content-header">';
        html += '<h1>';
        html += 'Create Client';
        html += '<small>add a new client</small>';
        html += '</h1>';
        html += '</section>';

        // create client
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Create client</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // bank_id
        html += '<div class="form-group">';
        html += '<label>Loan Bank</label>';
        html += '<select id="bank_id" class="form-control select2" style="width: 100%;">';
        html += '<option value="">Select bank</option>';
        for(i in banks)
        {
            var bank = banks[i];
            html += '<option value="' + bank.id + '">' + bank.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // loan_amount
        html += '<div class="form-group">';
        html += '<label>Loan Amount</label>';
        html += '<input id="loan_amount" type="text" class="form-control">';
        html += '</div>';

        // owner_name
        html += '<div class="form-group">';
        html += '<label>Owner Name</label>';
        html += '<input id="owner_name" type="text" class="form-control">';
        html += '</div>';

        // owner_nric
        html += '<div class="form-group">';
        html += '<label>Owner NRIC</label>';
        html += '<input id="owner_nric" type="email" class="form-control">';
        html += '</div>';

        // owner_mobile
        html += '<div class="form-group">';
        html += '<label>Owner Mobile</label>';
        html += '<input id="owner_mobile" type="text" class="form-control">';
        html += '</div>';

        // owner_email
        html += '<div class="form-group">';
        html += '<label>Owner Email</label>';
        html += '<input id="owner_email" type="email" class="form-control">';
        html += '</div>';

        // joint_name
        html += '<div class="form-group">';
        html += '<label>Joint Name</label>';
        html += '<input id="joint_name" type="text" class="form-control">';
        html += '</div>';

        // joint_nric
        html += '<div class="form-group">';
        html += '<label>Joint NRIC</label>';
        html += '<input id="joint_nric" type="email" class="form-control">';
        html += '</div>';

        // joint_mobile
        html += '<div class="form-group">';
        html += '<label>Joint Mobile</label>';
        html += '<input id="joint_mobile" type="text" class="form-control">';
        html += '</div>';

        // joint_email
        html += '<div class="form-group">';
        html += '<label>Joint Email</label>';
        html += '<input id="joint_email" type="email" class="form-control">';
        html += '</div>';

        // property_address
        html += '<div class="form-group">';
        html += '<label>Property Address</label>';
        html += '<input id="property_address" type="text" class="form-control">';
        html += '</div>';

        // postal_code
        html += '<div class="form-group">';
        html += '<label>Postal Code</label>';
        html += '<input id="postal_code" type="text" class="form-control">';
        html += '</div>';

        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-success" onclick="client_add()">Create Client</button>';
        html += '</div>';
        html += '<div id="result"></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';
        $('#content').html(html);
        $('#advisor_id').select2();
        $('#bank_id').select2();
	}
    $.ajax(ajax);
}

function client_add()
{
    loading_show();
    $('#result').html('<span class="text-light-blue">Please wait...</span>');

    var data = {};
    data.api_token = api_token;
    data.bank_id = $('#bank_id').val();
    data.owner_name = $('#owner_name').val();
    data.owner_nric = $('#owner_nric').val();
    data.owner_mobile = $('#owner_mobile').val();
    data.owner_email = $('#owner_email').val();
    data.joint_name = $('#joint_name').val();
    data.joint_nric = $('#joint_nric').val();
    data.joint_mobile = $('#joint_mobile').val();
    data.joint_email = $('#joint_email').val();
    data.property_address = $('#property_address').val();
    data.postal_code = $('#postal_code').val();
    data.loan_amount = $('#loan_amount').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/advisor/client/add';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
		var message = response.message;
        
        if(error == 99)
        {
            login_display();
            return;
        }

        if(error != 0)
        {
            $('#result').html('<span class="text-red">' + message + '</span>');
            return;
        }
        $('#result').html('<span class="text-green">' + message + '</span>');
        client_list();
	}
    $.ajax(ajax);
}

function client_edit(client_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.client_id = client_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/advisor/client/edit';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
        var message = response.message;
        
        if(error == 99)
        {
            login_display();
            return;
        }
		
		if(error != 0)
		{
			$('#content').html(message);
			return;
		}
        
        var client = response.client;
        var banks = response.banks;
        var html = '';

        // start
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Edit client</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // id
        html += '<input id="client_id" type="hidden" value="' + client.id + '">';

        // bank_id
        html += '<div class="form-group">';
        html += '<label>Loan Bank</label>';
        html += '<select id="bank_id" class="form-control select2" style="width: 100%;">';
        html += '<option value="">Select bank</option>';
        for(i in banks)
        {
            var bank = banks[i];
            var html_select = '';
            if(bank.id == client.bank_id)
            {
                html_select = 'selected';
            }
            html += '<option value="' + bank.id + '" ' + html_select + '>' + bank.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // loan_amount
        html += '<div class="form-group">';
        html += '<label>Loan Amount</label>';
        html += '<input id="loan_amount" type="text" class="form-control" value="' + client.loan_amount + '">';
        html += '</div>';

        // owner_name
        html += '<div class="form-group">';
        html += '<label>Owner Name</label>';
        html += '<input id="owner_name" type="text" class="form-control" value="' + client.owner_name + '">';
        html += '</div>';

        // owner_nric
        html += '<div class="form-group">';
        html += '<label>Owner NRIC</label>';
        html += '<input id="owner_nric" type="email" class="form-control" value="' + client.owner_nric + '">';
        html += '</div>';

        // owner_mobile
        html += '<div class="form-group">';
        html += '<label>Owner Mobile</label>';
        html += '<input id="owner_mobile" type="text" class="form-control" value="' + client.owner_mobile + '">';
        html += '</div>';

        // owner_email
        html += '<div class="form-group">';
        html += '<label>Owner Email</label>';
        html += '<input id="owner_email" type="email" class="form-control" value="' + client.owner_email + '">';
        html += '</div>';

        // joint_name
        html += '<div class="form-group">';
        html += '<label>Joint Name</label>';
        html += '<input id="joint_name" type="text" class="form-control" value="' + client.joint_name + '">';
        html += '</div>';

        // joint_nric
        html += '<div class="form-group">';
        html += '<label>Joint NRIC</label>';
        html += '<input id="joint_nric" type="email" class="form-control" value="' + client.joint_nric + '">';
        html += '</div>';

        // joint_mobile
        html += '<div class="form-group">';
        html += '<label>Joint Mobile</label>';
        html += '<input id="joint_mobile" type="text" class="form-control" value="' + client.joint_mobile + '">';
        html += '</div>';

        // joint_email
        html += '<div class="form-group">';
        html += '<label>Joint Email</label>';
        html += '<input id="joint_email" type="email" class="form-control" value="' + client.joint_email + '">';
        html += '</div>';

        // property_address
        html += '<div class="form-group">';
        html += '<label>Property Address</label>';
        html += '<input id="property_address" type="text" class="form-control" value="' + client.property_address + '">';
        html += '</div>';

        // postal_code
        html += '<div class="form-group">';
        html += '<label>Postal Code</label>';
        html += '<input id="postal_code" type="text" class="form-control" value="' + client.postal_code + '">';
        html += '</div>';

        // end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="client_update()">Update client</button>';
        html += '</div>';
        html += '<div id="result"></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        $('#content').html(html);
        $('#advisor_id').select2();
        $('#bank_id').select2();
	}
    $.ajax(ajax);
}

function client_update()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.client_id = $('#client_id').val();
    data.bank_id = $('#bank_id').val();
    data.owner_name = $('#owner_name').val();
    data.owner_nric = $('#owner_nric').val();
    data.owner_mobile = $('#owner_mobile').val();
    data.owner_email = $('#owner_email').val();
    data.joint_name = $('#joint_name').val();
    data.joint_nric = $('#joint_nric').val();
    data.joint_mobile = $('#joint_mobile').val();
    data.joint_email = $('#joint_email').val();
    data.property_address = $('#property_address').val();
    data.postal_code = $('#postal_code').val();
    data.loan_amount = $('#loan_amount').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/advisor/client/update';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
        var message = response.message;
        
        if(error == 99)
        {
            login_display();
            return;
        }
		
		if(error == 1)
		{
			$('#result').html('<span class="text-red">' + message + '</span>');
			return;
		}
		
        $('#result').html('<span class="text-green">' + message + '</span>');
        client_list();
	}
    $.ajax(ajax);
}

function client_remove(client_id)
{
    var html = '';
    html += '<div class="box box-danger">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Click Confirm to Delete</h3>';
    html += '</div>';
    html += '<div class="box-body">';
    html += '<div class="btn btn-secondary" onclick="popup_hide()">Cancel</div>';
    html += '<div class="width5"></div>';
    html += '<div class="btn btn-danger" onclick="client_destroy(\'' + client_id + '\')">Confirm</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    popup_show(html);
}

function client_destroy(client_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.client_id = client_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/advisor/client/destroy';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
        var message = response.message;
        
        if(error == 99)
        {
            window.location.href = login_url;
            return;
        }
		
		if(error == 1)
		{
			$('#result').html('<span class="text-red">' + message + '</span>');
			return;
		}
		
        $('#result').html('<span class="text-green">' + message + '</span>');

        popup_hide();
        client_list();
	}
    $.ajax(ajax);
}

function dashboard_index()
{
    app_data = {};
    app_data.filter_bank_id = '';
    app_data.filter_package_id = '';
    dashboard_list();
}

function dashboard_filter()
{
    app_data.filter_bank_id = $('#filter_bank_id').val();
    app_data.filter_package_id = $('#filter_package_id').val();
    dashboard_list();
}

function dashboard_list()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.filter_bank_id = app_data.filter_bank_id;
    data.filter_package_id = app_data.filter_package_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/advisor/dashboard/listing';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
        
        if(error == 99)
        {
            login_display();
            return;
        }

        var dashboards = response.dashboards;
        var packages = response.packages;
        var banks = response.banks;
        var headlines = response.headlines;
        var html = '';

        // header
        html += '<section class="content-header">';

        // headlines
        for(var i in headlines)
        {
            var headline = headlines[i];
            html += '<div class="alert alert-success alert-dismissible">';
            html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            html += '<h4><i class="icon fa fa-check"></i> ' + headline.title + '</h4>';
            html += '<small>';
            html += headline.updated_at;
            html += '</small>';
            html += '<p>';
            html += headline.message;
            html += '</p>';
            html += '</div>';
        }
        
        html += '<h1>';
        html += 'Dashboard';
        html += '<small>Listing of all Bank Rates</small>';
        html += '</h1>';
        html += '</section>';

        // filter start
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Filters</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // filter bank_id
        html += '<div class="form-group">';
        html += '<label>Bank</label>';
        html += '<select id="filter_bank_id" class="form-control select2" style="width: 100%;">';
        html += '<option value="">All Banks</option>';
        for(i in banks)
        {
            var bank = banks[i];
            var html_selected = '';
            if(bank.id == app_data.filter_bank_id)
            {
                html_selected = 'selected';
            }
            html += '<option value="' + bank.id + '" ' + html_selected + '>' + bank.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // filter package_id
        html += '<div class="form-group">';
        html += '<label>Package</label>';
        html += '<select id="filter_package_id" class="form-control select2" style="width: 100%;">';
        html += '<option value="">All Packages</option>';
        for(i in packages)
        {
            var package = packages[i];
            var html_selected = '';
            if(package.id == app_data.filter_package_id)
            {
                html_selected = 'selected';
            }
            html += '<option value="' + package.id + '" ' + html_selected + '>' + package.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // filter end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="dashboard_filter()">Filter</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        // list dashboard
        for(i in dashboards)
        {
            var dashboard = dashboards[i];
            var bank_loans = dashboard.bank_loans;

            if(bank_loans.length == 0)
            {
                continue;
            }

            html += '<section class="content">';
            html += '<div class="row">';
            html += '<div class="col-xs-12">';
            html += '<div class="box box-primary">';
            html += '<div class="box-header">';
            html += '<h3 class="box-title">' + dashboard.name + '</h3>';
            html += '</div>';
            html += '<div class="box-body table-responsive no-padding">';
            html += '<table class="table table-hover">';
            html += '<tr>';
            html += '<th>Bank</th>';
            html += '<th>Minimum Loan</th>';
            html += '<th>Loan</th>';
            html += '<th>Lock Period</th>';
            html += '<th>Year</th>';
            html += '<th>Rate</th>';
            html += '<th>Interest Rate</th>';
            html += '</tr>';

            for(i in bank_loans)
            {
                var bank_loan = bank_loans[i];

                html += '<tr>';
                html += '<td>' + bank_loan.bank_name + '</td>';
                html += '<td>' + bank_loan.minimum_loan + '</td>';
                html += '<td>' + bank_loan.name + '</td>';
                html += '<td>' + bank_loan.lock_period + '</td>';

                var bank_rates = bank_loan.bank_rates;
                for(j in bank_rates)
                {
                    var bank_rate = bank_rates[j];

                    if(j != 0)
                    {
                        html += '<tr>';
                        html += '<td colspan="4"></td>';
                    }
                    html += '<td>' + bank_rate.year + '</td>';
                    html += '<td>' + bank_rate.formula + '</td>';
                    html += '<td>' + bank_rate.interest_rate + '</td>';
                    html += '</tr>';
                }

                if(bank_rates.length == 0)
                {
                    html += '<td></td>';
                    html += '<td></td>';
                    html += '<td></td>';
                    html += '</tr>';
                }
            }
            html += '</table>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</section>';
        }
        $('#content').html(html);
        
        var options = {};
        options.minimumResultsForSearch = -1;
        $('.select2').select2(options);
	}
    $.ajax(ajax);
}

function profile_edit(advisor_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.advisor_id = advisor_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/advisor/profile/edit';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
        var message = response.message;
        
        if(error == 99)
        {
            login_display();
            return;
        }
		
		if(error != 0)
		{
			$('#content').html(message);
			return;
		}
        
        var banks = response.banks;
        var advisor = response.advisor;
        var html = '';

        // start
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Edit advisor</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // bank_id
        html += '<div class="form-group">';
        html += '<label>Bank</label>';
        html += '<select id="bank_id" class="form-control select2" style="width: 100%;">';
        html += '<option value="">Select Bank</option>';
        for(i in banks)
        {
            var bank = banks[i];
            var html_select = '';
            if(bank.id == advisor.bank_id)
            {
                html_select = 'selected';
            }
            html += '<option value="' + bank.id + '" ' + html_select + '>' + bank.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // bank_account
        html += '<div class="form-group">';
        html += '<label>Bank Account No</label>';
        html += '<input id="bank_account" type="text" class="form-control" value="' + advisor.bank_account + '">';
        html += '</div>';

        // nric
        html += '<div class="form-group">';
        html += '<label>NRIC</label>';
        html += '<input id="nric" type="text" class="form-control" value="' + advisor.nric + '">';
        html += '</div>';

        // firstname
        html += '<div class="form-group">';
        html += '<label>First Name</label>';
        html += '<input id="firstname" type="text" class="form-control" value="' + advisor.firstname + '">';
        html += '</div>';

        // lastname
        html += '<div class="form-group">';
        html += '<label>Last Name</label>';
        html += '<input id="lastname" type="text" class="form-control" value="' + advisor.lastname + '">';
        html += '</div>';

        // mobile
        html += '<div class="form-group">';
        html += '<label>Mobile</label>';
        html += '<input id="mobile" type="text" class="form-control" value="' + advisor.mobile + '">';
        html += '</div>';

        // email
        html += '<div class="form-group">';
        html += '<label>Email</label>';
        html += '<input id="email" type="email" class="form-control" value="' + advisor.email + '">';
        html += '</div>';

        // password
        html += '<div class="form-group">';
        html += '<label>Password</label>';
        html += '<input id="password" type="password" class="form-control">';
        html += '</div>';

        // address
        html += '<div class="form-group">';
        html += '<label>Address</label>';
        html += '<input id="address" type="text" class="form-control" value="' + advisor.address + '">';
        html += '</div>';

        // postal_code
        html += '<div class="form-group">';
        html += '<label>Postal Code</label>';
        html += '<input id="postal_code" type="text" class="form-control" value="' + advisor.postal_code + '">';
        html += '</div>';

        // end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="profile_update()">Update Advisor</button>';
        html += '</div>';
        html += '<div id="result"></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        $('#content').html(html);
        $('#bank_id').select2();
	}
    $.ajax(ajax);
}

function profile_update()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_id = $('#bank_id').val();
    data.nric = $('#nric').val();
    data.firstname = $('#firstname').val();
    data.lastname = $('#lastname').val();
    data.mobile = $('#mobile').val();
    data.email = $('#email').val();
    data.password = $('#password').val();
    data.bank_account = $('#bank_account').val();
    data.address = $('#address').val();
    data.postal_code = $('#postal_code').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/advisor/profile/update';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
		var error = response.error;
        var message = response.message;
        
        if(error == 99)
        {
            login_display();
            return;
        }
		
		if(error == 1)
		{
			$('#result').html('<span class="text-red">' + message + '</span>');
			return;
		}
		
        $('#result').html('<span class="text-green">' + message + '</span>');
        dashboard_index();
	}
    $.ajax(ajax);
}