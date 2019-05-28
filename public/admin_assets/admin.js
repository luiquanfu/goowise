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

        var admin = response.admin;
        var last_visit = admin.last_visit;
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
        if(last_visit.page == 'package_listing')
        {
            package_index();
            return;
        }
        if(last_visit.page == 'rate_listing')
        {
            rate_index();
            return;
        }
        if(last_visit.page == 'building_type_listing')
        {
            building_type_index();
            return;
        }
        if(last_visit.page == 'bank_listing')
        {
            bank_index();
            return;
        }
        if(last_visit.page == 'bank_loan_listing')
        {
            bank_loan_index();
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
    html += '<li><a href="#" onclick="dashboard_index()"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>';
    html += '<li><a href="#" onclick="package_index()"><i class="fa fa-building-o"></i> <span>Package</span></a></li>';
    html += '<li><a href="#" onclick="rate_index()"><i class="fa fa-object-group"></i> <span>Rate</span></a></li>';
    html += '<li><a href="#" onclick="building_type_index()"><i class="fa fa-building"></i> <span>Building Type</span></a></li>';
    html += '<li><a href="#" onclick="bank_index()"><i class="fa fa-bank"></i> <span>Bank</span></a></li>';
    html += '<li><a href="#" onclick="bank_loan_index()"><i class="fa fa-th"></i> <span>Bank Loans</span></a></li>';
    html += '<li><a href="#" onclick="testing()"><i class="fa fa-fire"></i> <span>Testing</span></a></li>';
    html += '<li><a href="#" onclick="logout()"><i class="fa fa-power-off"></i> <span>Logout</span></a></li>';
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
    $('body').layout('fix');
}

function testing()
{
    $('#content').html('testing');
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
        
        var admin = response.admin;
        api_token = admin.api_token;
        
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
	ajax.url = app_url + '/admin/dashboard/listing';
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
        var html = '';

        // header
        html += '<section class="content-header">';
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

function package_index()
{
    app_data = {};
    app_data.page = 1;
    app_data.sort = 'name';
    app_data.direction = 'asc';
    app_data.filter_name = '';
    package_list();
}

function package_list()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.page = app_data.page;
    data.sort = app_data.sort;
    data.direction = app_data.direction;
    data.filter_name = app_data.filter_name;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/package/listing';
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

        var packages = response.packages;
        var total_pages = response.total_pages;
        var current_page = response.current_page;
        var html = '';

        // header
        html += '<section class="content-header">';
        html += '<h1>';
        html += 'Package Management';
        html += '<small>Listing of all Packages</small>';
        html += '</h1>';
        html += '</section>';

        // filter packages
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Filters</h3>';
        html += '</div>';
        html += '<div class="box-body">';
        html += '<div class="form-group">';
        html += '<label>Package Name</label>';
        html += '<input id="filter_name" type="text" class="form-control" value="' + app_data.filter_name + '">';
        html += '</div>';
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="package_filter()">Filter</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        // create packages
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="width15"></div>';
        html += '<div class="btn btn-success" onclick="package_create()">Create Package</div>';
        html += '</div>';
        html += '</div>';

        // list packages
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-xs-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header">';
        html += '<h3 class="box-title">Package List</h3>';
        html += '</div>';
        html += '<div class="box-body table-responsive no-padding">';
        html += '<table class="table table-hover">';
        html += '<tr>';
        html += '<th role="button" onclick="package_sorting(\'name\')">Name</th>';
        html += '<th>Actions</th>';
        html += '</tr>';
        for(i in packages)
        {
            var package = packages[i];

            html += '<tr>';
            html += '<td>' + package.name + '</td>';
            html += '<td>';
            html += '<div class="btn btn-primary" onclick="package_edit(\'' + package.id + '\')"><i class="fa fa-edit"></i></div>';
            html += '<div class="width5"></div>';
            html += '<div class="btn btn-danger" onclick="package_remove(\'' + package.id + '\')"><i class="fa fa-trash"></i></div>';
            html += '</td>';
            html += '</tr>';
        }
        html += '</table>';
        html += '</div>';
        html += '<div class="box-footer clearfix">';
        html += '<ul class="pagination pagination-sm no-margin pull-right">';
        for(var i = 1; i <= total_pages; i++)
        {
            var html_page = '<a href="#" onclick="package_paging(' + i + ')">' + i + '</a>';
            if(i == current_page)
            {
                html_page = '<li><span>' + i + '</span></li>';
            }
            html += '<li>' + html_page + '</li>';
        }
        html += '</ul>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';
        $('#content').html(html);
	}
    $.ajax(ajax);
}

function package_filter()
{
    app_data.filter_name = $('#filter_name').val();
    app_data.page = 1;
    package_list();
}

function package_paging(page)
{
    app_data.page = page;
    package_list();
}

function package_sorting(sort)
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
    package_list();
}

function package_create()
{
    var html = '';

    // start
    html += '<section class="content">';
    html += '<div class="row">';
    html += '<div class="col-md-12">';
    html += '<div class="box box-primary">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Add Package</h3>';
    html += '</div>';
    html += '<div class="box-body">';

    // name
    html += '<div class="form-group">';
    html += '<label>Package Name</label>';
    html += '<input id="name" type="text" class="form-control">';
    html += '</div>';

    // end
    html += '</div>';
    html += '<div class="box-footer">';
    html += '<div class="btn btn-success" onclick="package_add()">Add Package</button>';
    html += '</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</section>';

    $('#content').html(html);
}

function package_add()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.name = $('#name').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/package/add';
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
        package_list();
	}
    $.ajax(ajax);
}

function package_edit(package_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.package_id = package_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/package/edit';
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
		
        var package = response.package;
        var html = '';

        // start
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Edit Package</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // id
        html += '<input id="package_id" type="hidden" value="' + package.id + '">';

        // name
        html += '<div class="form-group">';
        html += '<label>Package Name</label>';
        html += '<input id="name" type="text" class="form-control" value="' + package.name + '">';
        html += '</div>';

        // end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="package_update()">Update Package</button>';
        html += '</div>';
        html += '<div id="result"></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        $('#content').html(html);
	}
    $.ajax(ajax);
}

function package_update()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.package_id = $('#package_id').val();
    data.name = $('#name').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/package/update';
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
        package_list();
	}
    $.ajax(ajax);
}

function package_remove(package_id)
{
    var html = '';
    html += '<div class="box box-danger">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Click Confirm to Delete</h3>';
    html += '</div>';
    html += '<div class="box-body">';
    html += '<div class="btn btn-secondary" onclick="popup_hide()">Cancel</div>';
    html += '<div class="width5"></div>';
    html += '<div class="btn btn-danger" onclick="package_destroy(\'' + package_id + '\')">Confirm</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    popup_show(html);
}

function package_destroy(package_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.package_id = package_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/package/destroy';
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
        package_list();
	}
    $.ajax(ajax);
}

function rate_index()
{
    app_data = {};
    app_data.page = 1;
    app_data.sort = 'name';
    app_data.direction = 'asc';
    app_data.filter_name = '';
    rate_list();
}

function rate_list()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.page = app_data.page;
    data.sort = app_data.sort;
    data.direction = app_data.direction;
    data.filter_name = app_data.filter_name;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/rate/listing';
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

        var rates = response.rates;
        var total_pages = response.total_pages;
        var current_page = response.current_page;
        var html = '';

        // header
        html += '<section class="content-header">';
        html += '<h1>';
        html += 'Rate Management';
        html += '<small>Listing of all Rates</small>';
        html += '</h1>';
        html += '</section>';

        // filter rates
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Filters</h3>';
        html += '</div>';
        html += '<div class="box-body">';
        html += '<div class="form-group">';
        html += '<label>Rate Name</label>';
        html += '<input id="filter_name" type="text" class="form-control" value="' + app_data.filter_name + '">';
        html += '</div>';
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="rate_filter()">Filter</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        // create rates
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="width15"></div>';
        html += '<div class="btn btn-success" onclick="rate_create()">Create Rate</div>';
        html += '</div>';
        html += '</div>';

        // list rates
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-xs-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header">';
        html += '<h3 class="box-title">Rate List</h3>';
        html += '</div>';
        html += '<div class="box-body table-responsive no-padding">';
        html += '<table class="table table-hover">';
        html += '<tr>';
        html += '<th role="button" onclick="rate_sorting(\'name\')">Name</th>';
        html += '<th role="button" onclick="rate_sorting(\'interest\')">Interest</th>';
        html += '<th>Actions</th>';
        html += '</tr>';
        for(i in rates)
        {
            var rate = rates[i];

            html += '<tr>';
            html += '<td>' + rate.name + '</td>';
            html += '<td>' + rate.interest + '</td>';
            html += '<td>';
            html += '<div class="btn btn-primary" onclick="rate_edit(\'' + rate.id + '\')"><i class="fa fa-edit"></i></div>';
            html += '<div class="width5"></div>';
            html += '<div class="btn btn-danger" onclick="rate_remove(\'' + rate.id + '\')"><i class="fa fa-trash"></i></div>';
            html += '</td>';
            html += '</tr>';
        }
        html += '</table>';
        html += '</div>';
        html += '<div class="box-footer clearfix">';
        html += '<ul class="pagination pagination-sm no-margin pull-right">';
        for(var i = 1; i <= total_pages; i++)
        {
            html += '<li onclick="rate_paging(' + i + ')"><a href="#">' + i + '</a></li>';
        }
        html += '</ul>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';
        $('#content').html(html);
	}
    $.ajax(ajax);
}

function rate_filter()
{
    app_data.filter_name = $('#filter_name').val();
    app_data.page = 1;
    rate_list();
}

function rate_paging(page)
{
    app_data.page = page;
    rate_list();
}

function rate_sorting(sort)
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
    rate_list();
}

function rate_create()
{
    var html = '';

    // start
    html += '<section class="content">';
    html += '<div class="row">';
    html += '<div class="col-md-12">';
    html += '<div class="box box-primary">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Add Rate</h3>';
    html += '</div>';
    html += '<div class="box-body">';

    // name
    html += '<div class="form-group">';
    html += '<label>Rate Name</label>';
    html += '<input id="name" type="text" class="form-control">';
    html += '</div>';

    // interest
    html += '<div class="form-group">';
    html += '<label>Interest</label>';
    html += '<input id="interest" type="text" class="form-control">';
    html += '</div>';

    // end
    html += '</div>';
    html += '<div class="box-footer">';
    html += '<div class="btn btn-success" onclick="rate_add()">Add Rate</button>';
    html += '</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</section>';

    $('#content').html(html);
}

function rate_add()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_id = $('#bank_id').val();
    data.name = $('#name').val();
    data.interest = $('#interest').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/rate/add';
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
        rate_list();
	}
    $.ajax(ajax);
}

function rate_edit(rate_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.rate_id = rate_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/rate/edit';
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
        var rate = response.rate;
        var html = '';

        // start
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Edit Rate</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // id
        html += '<input id="rate_id" type="hidden" value="' + rate.id + '">';

        // name
        html += '<div class="form-group">';
        html += '<label>Rate Name</label>';
        html += '<input id="name" type="text" class="form-control" value="' + rate.name + '">';
        html += '</div>';

        // interest
        html += '<div class="form-group">';
        html += '<label>Interest</label>';
        html += '<input id="interest" type="text" class="form-control" value="' + rate.interest + '">';
        html += '</div>';

        // end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="rate_update()">Update Rate</button>';
        html += '</div>';
        html += '<div id="result"></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        $('#content').html(html);
	}
    $.ajax(ajax);
}

function rate_update()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.rate_id = $('#rate_id').val();
    data.name = $('#name').val();
    data.interest = $('#interest').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/rate/update';
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
        rate_list();
	}
    $.ajax(ajax);
}

function rate_remove(rate_id)
{
    var html = '';
    html += '<div class="box box-danger">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Click Confirm to Delete</h3>';
    html += '</div>';
    html += '<div class="box-body">';
    html += '<div class="btn btn-secondary" onclick="popup_hide()">Cancel</div>';
    html += '<div class="width5"></div>';
    html += '<div class="btn btn-danger" onclick="rate_destroy(\'' + rate_id + '\')">Confirm</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    popup_show(html);
}

function rate_destroy(rate_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.rate_id = rate_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/rate/destroy';
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
        rate_list();
	}
    $.ajax(ajax);
}

function building_type_index()
{
    app_data = {};
    app_data.page = 1;
    app_data.sort = 'name';
    app_data.direction = 'asc';
    app_data.filter_name = '';
    building_type_list();
}

function building_type_list()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.page = app_data.page;
    data.sort = app_data.sort;
    data.direction = app_data.direction;
    data.filter_name = app_data.filter_name;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/building_type/listing';
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

        var building_types = response.building_types;
        var total_pages = response.total_pages;
        var current_page = response.current_page;
        var html = '';

        // header
        html += '<section class="content-header">';
        html += '<h1>';
        html += 'Building Type Management';
        html += '<small>Listing of all Building Types</small>';
        html += '</h1>';
        html += '</section>';

        // filter building_types
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Filters</h3>';
        html += '</div>';
        html += '<div class="box-body">';
        html += '<div class="form-group">';
        html += '<label>Building Type Name</label>';
        html += '<input id="filter_name" type="text" class="form-control" value="' + app_data.filter_name + '">';
        html += '</div>';
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="building_type_filter()">Filter</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        // create building_types
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="width15"></div>';
        html += '<div class="btn btn-success" onclick="building_type_create()">Create Building Type</div>';
        html += '</div>';
        html += '</div>';

        // list building_types
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-xs-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header">';
        html += '<h3 class="box-title">Building Type List</h3>';
        html += '</div>';
        html += '<div class="box-body table-responsive no-padding">';
        html += '<table class="table table-hover">';
        html += '<tr>';
        html += '<th role="button" onclick="building_type_sorting(\'name\')">Name</th>';
        html += '<th>Actions</th>';
        html += '</tr>';
        for(i in building_types)
        {
            var building_type = building_types[i];

            html += '<tr>';
            html += '<td>' + building_type.name + '</td>';
            html += '<td>';
            html += '<div class="btn btn-primary" onclick="building_type_edit(\'' + building_type.id + '\')"><i class="fa fa-edit"></i></div>';
            html += '<div class="width5"></div>';
            html += '<div class="btn btn-danger" onclick="building_type_remove(\'' + building_type.id + '\')"><i class="fa fa-trash"></i></div>';
            html += '</td>';
            html += '</tr>';
        }
        html += '</table>';
        html += '</div>';
        html += '<div class="box-footer clearfix">';
        html += '<ul class="pagination pagination-sm no-margin pull-right">';
        for(var i = 1; i <= total_pages; i++)
        {
            var html_page = '<a href="#" onclick="building_type_paging(' + i + ')">' + i + '</a>';
            if(i == current_page)
            {
                html_page = '<li><span>' + i + '</span></li>';
            }
            html += '<li>' + html_page + '</li>';
        }
        html += '</ul>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';
        $('#content').html(html);
	}
    $.ajax(ajax);
}

function building_type_filter()
{
    app_data.filter_name = $('#filter_name').val();
    app_data.page = 1;
    building_type_list();
}

function building_type_paging(page)
{
    app_data.page = page;
    building_type_list();
}

function building_type_sorting(sort)
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
    building_type_list();
}

function building_type_create()
{
    var html = '';

    // start
    html += '<section class="content">';
    html += '<div class="row">';
    html += '<div class="col-md-12">';
    html += '<div class="box box-primary">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Add Building Type</h3>';
    html += '</div>';
    html += '<div class="box-body">';

    // name
    html += '<div class="form-group">';
    html += '<label>Building Type Name</label>';
    html += '<input id="name" type="text" class="form-control">';
    html += '</div>';

    // end
    html += '</div>';
    html += '<div class="box-footer">';
    html += '<div class="btn btn-success" onclick="building_type_add()">Add Building Type</button>';
    html += '</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</section>';

    $('#content').html(html);
}

function building_type_add()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.name = $('#name').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/building_type/add';
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
        building_type_list();
	}
    $.ajax(ajax);
}

function building_type_edit(building_type_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.building_type_id = building_type_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/building_type/edit';
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
		
        var building_type = response.building_type;
        var html = '';

        // start
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Edit Building Type</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // id
        html += '<input id="building_type_id" type="hidden" value="' + building_type.id + '">';

        // name
        html += '<div class="form-group">';
        html += '<label>Building Type Name</label>';
        html += '<input id="name" type="text" class="form-control" value="' + building_type.name + '">';
        html += '</div>';

        // end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="building_type_update()">Update Building Type</button>';
        html += '</div>';
        html += '<div id="result"></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        $('#content').html(html);
	}
    $.ajax(ajax);
}

function building_type_update()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.building_type_id = $('#building_type_id').val();
    data.name = $('#name').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/building_type/update';
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
        building_type_list();
	}
    $.ajax(ajax);
}

function building_type_remove(building_type_id)
{
    var html = '';
    html += '<div class="box box-danger">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Click Confirm to Delete</h3>';
    html += '</div>';
    html += '<div class="box-body">';
    html += '<div class="btn btn-secondary" onclick="popup_hide()">Cancel</div>';
    html += '<div class="width5"></div>';
    html += '<div class="btn btn-danger" onclick="building_type_destroy(\'' + building_type_id + '\')">Confirm</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    popup_show(html);
}

function building_type_destroy(building_type_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.building_type_id = building_type_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/building_type/destroy';
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
        building_type_list();
	}
    $.ajax(ajax);
}

function bank_index()
{
    app_data = {};
    app_data.page = 1;
    app_data.sort = 'name';
    app_data.direction = 'asc';
    app_data.filter_name = '';
    bank_list();
}

function bank_list()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.page = app_data.page;
    data.sort = app_data.sort;
    data.direction = app_data.direction;
    data.filter_name = app_data.filter_name;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank/listing';
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

        var banks = response.banks;
        var html = '';

        // header
        html += '<section class="content-header">';
        html += '<h1>';
        html += 'Bank Management';
        html += '<small>Listing of all banks</small>';
        html += '</h1>';
        html += '</section>';

        // filter banks
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Filters</h3>';
        html += '</div>';
        html += '<div class="box-body">';
        html += '<div class="form-group">';
        html += '<label>Bank Name</label>';
        html += '<input id="filter_name" type="text" class="form-control" value="' + app_data.filter_name + '">';
        html += '</div>';
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="bank_filter()">Filter</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        // create banks
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="width15"></div>';
        html += '<div class="btn btn-success" onclick="bank_create()">Create Bank</div>';
        html += '</div>';
        html += '</div>';

        // list banks
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-xs-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header">';
        html += '<h3 class="box-title">Bank List</h3>';
        html += '</div>';
        html += '<div class="box-body table-responsive no-padding">';
        html += '<table class="table table-hover">';
        html += '<tr>';
        html += '<th role="button" onclick="bank_sorting(\'name\')">Name</th>';
        html += '<th>Actions</th>';
        html += '</tr>';
        for(i in banks)
        {
            var bank = banks[i];

            html += '<tr>';
            html += '<td>' + bank.name + '</td>';
            html += '<td>';
            html += '<div class="btn btn-primary" onclick="bank_edit(\'' + bank.id + '\')"><i class="fa fa-edit"></i></div>';
            html += '<div class="width5"></div>';
            html += '<div class="btn btn-danger" onclick="bank_remove(\'' + bank.id + '\')"><i class="fa fa-trash"></i></div>';
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

function bank_filter()
{
    app_data.name = $('#filter_name').val();
    app_data.page = 1;
    bank_list();
}

function bank_paging(page)
{
    app_data.page = page;
    bank_list();
}

function bank_sorting(sort)
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
    bank_list();
}

function bank_create()
{
    var html = '';

    // header
    html += '<section class="content-header">';
    html += '<h1>';
    html += 'Create Bank';
    html += '<small>add a new bank</small>';
    html += '</h1>';
    html += '</section>';

    // create bank
    html += '<section class="content">';
    html += '<div class="row">';
    html += '<div class="col-md-12">';
    html += '<div class="box box-primary">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Create Bank</h3>';
    html += '</div>';
    html += '<div class="box-body">';
    html += '<div class="form-group">';
    html += '<label>Bank Name</label>';
    html += '<input id="name" type="text" class="form-control">';
    html += '</div>';
    html += '</div>';
    html += '<div class="box-footer">';
    html += '<div class="btn btn-success" onclick="bank_add()">Create Bank</button>';
    html += '</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</section>';
    $('#content').html(html);
}

function bank_add()
{
    loading_show();
    $('#result').html('<span class="text-light-blue">Please wait...</span>');

    var data = {};
    data.api_token = api_token;
    data.name = $('#name').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank/add';
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
        bank_list();
	}
    $.ajax(ajax);
}

function bank_edit(bank_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_id = bank_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank/edit';
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
		
        var bank = response.bank;
        var html = '';

        // start
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Edit Bank</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // id
        html += '<input id="bank_id" type="hidden" value="' + bank.id + '">';

        // name
        html += '<div class="form-group">';
        html += '<label>Bank Name</label>';
        html += '<input id="name" type="text" class="form-control" value="' + bank.name + '">';
        html += '</div>';

        // end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="bank_update()">Update Bank</button>';
        html += '</div>';
        html += '<div id="result"></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        $('#content').html(html);
	}
    $.ajax(ajax);
}

function bank_update()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_id = $('#bank_id').val();
    data.name = $('#name').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank/update';
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
        bank_list();
	}
    $.ajax(ajax);
}

function bank_remove(bank_id)
{
    var html = '';
    // html += '<div class="card">';
    // html += '<div class="card-header">';
    // html += '<h4 class="card-title">Click Confirm to Delete</h4>';
    // html += '</div>';
    // html += '<div class="card-body">';
    // html += '<p class="card-text"></p>';
    // html += '<div class="btn btn-secondary" onclick="popup_hide()">Cancel</div>';
    // html += '<div class="width5"></div>';
    // html += '<div class="btn btn-danger" onclick="destroy(\'' + bank_id + '\')">Confirm</div>';
    // html += '<div id="result"></div>';
    // html += '</div>';
    // html += '</div>';

    html += '<div class="box box-danger">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Click Confirm to Delete</h3>';
    html += '</div>';
    html += '<div class="box-body">';
    html += '<div class="btn btn-secondary" onclick="popup_hide()">Cancel</div>';
    html += '<div class="width5"></div>';
    html += '<div class="btn btn-danger" onclick="bank_destroy(\'' + bank_id + '\')">Confirm</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    popup_show(html);
}

function bank_destroy(bank_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_id = bank_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank/destroy';
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
        bank_list();
	}
    $.ajax(ajax);
}

function bank_index()
{
    app_data = {};
    app_data.page = 1;
    app_data.sort = 'name';
    app_data.direction = 'asc';
    app_data.filter_name = '';
    bank_list();
}

function bank_loan_index()
{
    app_data = {};
    app_data.page = 1;
    app_data.sort = 'name';
    app_data.direction = 'asc';
    app_data.filter_name = '';
    var calculates = [];
    calculates.push('add');
    calculates.push('subtract');
    app_data.calculates = calculates;
    bank_loan_list();
}

function bank_loan_list()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.page = app_data.page;
    data.sort = app_data.sort;
    data.direction = app_data.direction;
    data.filter_name = app_data.filter_name;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank_loan/listing';
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

        var bank_loans = response.bank_loans;
        var total_pages = response.total_pages;
        var current_page = response.current_page;
        var html = '';

        // header
        html += '<section class="content-header">';
        html += '<h1>';
        html += 'Bank Loan Management';
        html += '<small>Listing of all bank loans</small>';
        html += '</h1>';
        html += '</section>';

        // filter bank_loans
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Filters</h3>';
        html += '</div>';
        html += '<div class="box-body">';
        html += '<div class="form-group">';
        html += '<label>Bank Loan Name</label>';
        html += '<input id="filter_name" type="text" class="form-control" value="' + app_data.filter_name + '">';
        html += '</div>';
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="bank_loan_filter()">Filter</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        // create bank_loans
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="width15"></div>';
        html += '<div class="btn btn-success" onclick="bank_loan_create()">Create Bank Loan</div>';
        html += '</div>';
        html += '</div>';

        // list bank_loans
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-xs-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header">';
        html += '<h3 class="box-title">Bank Loan List</h3>';
        html += '</div>';
        html += '<div class="box-body table-responsive no-padding">';
        html += '<table class="table table-hover">';
        html += '<tr>';
        html += '<th>Bank</th>';
        html += '<th>Package</th>';
        html += '<th role="button" onclick="bank_loan_sorting(\'name\')">Name</th>';
        html += '<th role="button" onclick="bank_loan_sorting(\'lock_period\')">Lock Period</th>';
        html += '<th>Actions</th>';
        html += '</tr>';
        for(i in bank_loans)
        {
            var bank_loan = bank_loans[i];

            html += '<tr>';
            html += '<td>' + bank_loan.bank_name + '</td>';
            html += '<td>' + bank_loan.package_name + '</td>';
            html += '<td>' + bank_loan.name + '</td>';
            html += '<td>' + bank_loan.lock_period + ' years</td>';
            html += '<td>';
            html += '<div class="btn btn-primary" onclick="bank_loan_edit(\'' + bank_loan.id + '\')"><i class="fa fa-edit"></i></div>';
            html += '<div class="width5"></div>';
            html += '<div class="btn btn-danger" onclick="bank_loan_remove(\'' + bank_loan.id + '\')"><i class="fa fa-trash"></i></div>';
            html += '</td>';
            html += '</tr>';
        }
        html += '</table>';
        html += '</div>';
        html += '<div class="box-footer clearfix">';
        html += '<ul class="pagination pagination-sm no-margin pull-right">';
        for(var i = 1; i <= total_pages; i++)
        {
            var html_page = '<a href="#" onclick="bank_loan_paging(' + i + ')">' + i + '</a>';
            if(i == current_page)
            {
                html_page = '<li><span>' + i + '</span></li>';
            }
            html += '<li>' + html_page + '</li>';
        }
        html += '</ul>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';
        $('#content').html(html);
	}
    $.ajax(ajax);
}

function bank_loan_filter()
{
    app_data.filter_name = $('#filter_name').val();
    app_data.page = 1;
    bank_loan_list();
}

function bank_loan_paging(page)
{
    app_data.page = page;
    bank_loan_list();
}

function bank_loan_sorting(sort)
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
    bank_loan_list();
}

function bank_loan_create()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank_loan/create';
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
        var rates = response.rates;
        var packages = response.packages;
        var building_types = response.building_types;
        var html = '';

        app_data.rates = rates;

        // start
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Add Bank Loan</h3>';
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
            html += '<option value="' + bank.id + '">' + bank.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // package_id
        html += '<div class="form-group">';
        html += '<label>Package</label>';
        html += '<select id="package_id" class="form-control select2" style="width: 100%;">';
        html += '<option value="">Select Package</option>';
        for(i in packages)
        {
            var package = packages[i];
            html += '<option value="' + package.id + '">' + package.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // name
        html += '<div class="form-group">';
        html += '<label>Bank Loan Name</label>';
        html += '<input id="name" type="text" class="form-control">';
        html += '</div>';

        // lock_period
        html += '<div class="form-group">';
        html += '<label>Lock Period</label>';
        html += '<input id="lock_period" type="text" class="form-control">';
        html += '</div>';

        // minimum_loan
        html += '<div class="form-group">';
        html += '<label>Minimum Loan</label>';
        html += '<input id="minimum_loan" type="text" class="form-control">';
        html += '</div>';

        // bank_loan_buildings
        html += '<div class="form-group">';
        html += '<label>Building Types</label>';
        html += '<select id="building_types" class="form-control select2" multiple="multiple" style="width: 100%;">';
        for(i in building_types)
        {
            var building_type = building_types[i];
            html += '<option value="' + building_type.id + '">' + building_type.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // bank_rates
        app_data.new_bank_rate_ids = [];
        app_data.edit_bank_rate_ids = [];
        html += '<div class="box box-success">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Bank Rates</h3>';
        html += '</div>';
        html += '<div class="box-body table-responsive no-padding">';
        html += '<table id="table_bank_rates" class="table table-bordered">';
        html += '<tr>';
        html += '<th>Year</th>';
        html += '<th>Rate</th>';
        html += '<th>Calculate</th>';
        html += '<th>Interest</th>';
        html += '<th>Action</th>';
        html += '</tr>';
        html += '</table>';
        html += '</div>';
        html += '</div>';
        html += '<div class="btn btn-success pull-right" onclick="bank_rate_create()">Add Bank Rate</div>';

        // end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-success" onclick="bank_loan_add()">Add Bank Loan</button>';
        html += '</div>';
        html += '<div id="result"></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        $('#content').html(html);
        $('.select2').select2();
	}
    $.ajax(ajax);
}

function bank_loan_add()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_id = $('#bank_id').val();
    data.package_id = $('#package_id').val();
    data.name = $('#name').val();
    data.lock_period = $('#lock_period').val();
    data.minimum_loan = $('#minimum_loan').val();
    var building_types = [];
	$.each($('#building_types option:selected'), function()
	{
        var building_type = {};
        building_type.id = $(this).val();
		building_types.push(building_type);
	});
    data.building_types = building_types;
    var new_bank_rates = [];
    for(i in app_data.new_bank_rate_ids)
    {
        var id = app_data.new_bank_rate_ids[i];
        var bank_rate = {};
        bank_rate.id = id;
        bank_rate.year = $('#new_bank_rate_year_' + id).val();
        bank_rate.rate_id = $('#new_bank_rate_rate_id_' + id).val();
        bank_rate.calculate = $('#new_bank_rate_calculate_' + id).val();
        bank_rate.interest = $('#new_bank_rate_interest_' + id).val();
        new_bank_rates.push(bank_rate);
    }
    data.new_bank_rates = new_bank_rates;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank_loan/add';
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
        bank_loan_list();
	}
    $.ajax(ajax);
}

function bank_loan_edit(bank_loan_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_loan_id = bank_loan_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank_loan/edit';
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
		
        var packages = response.packages;
        var rates = response.rates;
        var banks = response.banks;
        var building_types = response.building_types;
        var bank_loan = response.bank_loan;
        var bank_loan_buildings = response.bank_loan_buildings;
        var bank_rates = response.bank_rates;
        var html = '';

        app_data.rates = rates;

        // start
        html += '<section class="content">';
        html += '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<div class="box box-primary">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Edit Bank Loan</h3>';
        html += '</div>';
        html += '<div class="box-body">';

        // id
        html += '<input id="bank_loan_id" type="hidden" value="' + bank_loan.id + '">';

        // bank_id
        html += '<div class="form-group">';
        html += '<label>Bank</label>';
        html += '<select id="bank_id" class="form-control select2" style="width: 100%;">';
        html += '<option value="">Select Bank</option>';
        for(i in banks)
        {
            var bank = banks[i];
            var html_selected = '';
            if(bank.id == bank_loan.bank_id)
            {
                html_selected = 'selected';
            }
            html += '<option value="' + bank.id + '" ' + html_selected + '>' + bank.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // package_id
        html += '<div class="form-group">';
        html += '<label>Package</label>';
        html += '<select id="package_id" class="form-control select2" style="width: 100%;">';
        html += '<option value="">Select Package</option>';
        for(i in packages)
        {
            var package = packages[i];
            var html_selected = '';
            if(package.id == bank_loan.package_id)
            {
                html_selected = 'selected';
            }
            html += '<option value="' + package.id + '" ' + html_selected + '>' + package.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // name
        html += '<div class="form-group">';
        html += '<label>Bank Loan Name</label>';
        html += '<input id="name" type="text" class="form-control" value="' + bank_loan.name + '">';
        html += '</div>';

        // lock_period
        html += '<div class="form-group">';
        html += '<label>Lock Period</label>';
        html += '<input id="lock_period" type="text" class="form-control" value="' + bank_loan.lock_period + '">';
        html += '</div>';

        // minimum_loan
        html += '<div class="form-group">';
        html += '<label>Minimum Loan</label>';
        html += '<input id="minimum_loan" type="text" class="form-control" value="' + bank_loan.minimum_loan + '">';
        html += '</div>';

        // bank_loan_buildings
        html += '<div class="form-group">';
        html += '<label>Building Types</label>';
        html += '<select id="building_types" class="form-control select2" multiple="multiple" style="width: 100%;">';
        for(i in building_types)
        {
            var building_type = building_types[i];
            var html_selected = '';
            for(j in bank_loan_buildings)
            {
                var bank_loan_building = bank_loan_buildings[j];
                if(bank_loan_building.building_type_id == building_type.id)
                {
                    html_selected = 'selected';
                }
            }
            html += '<option value="' + building_type.id + '" ' + html_selected + '>' + building_type.name + '</option>';
        }
        html += '</select>';
        html += '</div>';

        // bank_rates
        app_data.new_bank_rate_ids = [];
        app_data.edit_bank_rate_ids = [];
        html += '<div class="box box-success">';
        html += '<div class="box-header with-border">';
        html += '<h3 class="box-title">Bank Rates</h3>';
        html += '</div>';
        html += '<div class="box-body table-responsive">';
        html += '<table id="table_bank_rates" class="table table-bordered no-padding">';
        html += '<tr>';
        html += '<th>Year</th>';
        html += '<th>Rate</th>';
        html += '<th>Calculate</th>';
        html += '<th>Interest</th>';
        html += '<th>Action</th>';
        html += '</tr>';
        for(i in bank_rates)
        {
            var bank_rate = bank_rates[i];
            app_data.edit_bank_rate_ids.push(bank_rate.id);

            html += '<tr id="edit_bank_rate_tr_' + bank_rate.id + '">';
            html += '<td><input id="edit_bank_rate_year_' + bank_rate.id + '" type="text" class="form-control" value="' + bank_rate.year + '"></td>';
            html += '<td>';
            html += '<select id="edit_bank_rate_rate_id_' + bank_rate.id + '" class="form-control select2" style="width: 100%;">';
            html += '<option value="">NA</option>';
            for(i in rates)
            {
                var rate = rates[i];
                var html_selected = '';
                if(rate.id == bank_rate.rate_id)
                {
                    html_selected = 'selected';
                }
                html += '<option value="' + rate.id + '" ' + html_selected + '>' + rate.name + '</option>';
            }
            html += '</select>';
            html += '</td>';
            html += '<td>';
            html += '<select id="edit_bank_rate_calculate_' + bank_rate.id + '" class="form-control select2" style="width: 100%;">';
            var calculates = app_data.calculates;
            for(i in calculates)
            {
                var calculate = calculates[i];
                var html_selected = '';
                if(calculate == bank_rate.calculate)
                {
                    html_selected = 'selected';
                }
                html += '<option value="' + calculate + '" ' + html_selected + '>' + calculate + '</option>';
            }
            html += '</select>';
            html += '</td>';
            html += '<td><input id="edit_bank_rate_interest_' + bank_rate.id + '"type="text"class="form-control" value="' + bank_rate.interest + '"></td>';
            html += '<td>';
            html += '<div class="btn btn-danger" onclick="bank_rate_destroy(\'' + bank_rate.id + '\', \'edit\')">';
            html += '<i class="fa fa-trash-o"></i>';
            html += '</div>';
            html += '</td>';
            html += '</tr>';
        }
        html += '</table>';
        html += '</div>';
        html += '</div>';
        html += '<div class="btn btn-success pull-right" onclick="bank_rate_create()">Add Bank Rate</div>';

        // end
        html += '</div>';
        html += '<div class="box-footer">';
        html += '<div class="btn btn-primary" onclick="bank_loan_update()">Update Bank Loan</button>';
        html += '</div>';
        html += '<div id="result"></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</section>';

        $('#content').html(html);

        var options = {};
        options.minimumResultsForSearch = -1;
        $('.select2').select2(options);
	}
    $.ajax(ajax);
}

function bank_loan_update()
{
    $('#result').html('<span class="text-light-blue">Please wait...</span>');
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_loan_id = $('#bank_loan_id').val();
    data.bank_id = $('#bank_id').val();
    data.package_id = $('#package_id').val();
    data.name = $('#name').val();
    data.lock_period = $('#lock_period').val();
    data.minimum_loan = $('#minimum_loan').val();
    var building_types = [];
	$.each($('#building_types option:selected'), function()
	{
        var building_type = {};
        building_type.id = $(this).val();
		building_types.push(building_type);
	});
    data.building_types = building_types;
    var edit_bank_rates = [];
    for(i in app_data.edit_bank_rate_ids)
    {
        var id = app_data.edit_bank_rate_ids[i];
        var bank_rate = {};
        bank_rate.id = id;
        bank_rate.year = $('#edit_bank_rate_year_' + id).val();
        bank_rate.rate_id = $('#edit_bank_rate_rate_id_' + id).val();
        bank_rate.calculate = $('#edit_bank_rate_calculate_' + id).val();
        bank_rate.interest = $('#edit_bank_rate_interest_' + id).val();
        edit_bank_rates.push(bank_rate);
    }
    data.edit_bank_rates = edit_bank_rates;
    var new_bank_rates = [];
    for(i in app_data.new_bank_rate_ids)
    {
        var id = app_data.new_bank_rate_ids[i];
        var bank_rate = {};
        bank_rate.id = id;
        bank_rate.year = $('#new_bank_rate_year_' + id).val();
        bank_rate.rate_id = $('#new_bank_rate_rate_id_' + id).val();
        bank_rate.calculate = $('#new_bank_rate_calculate_' + id).val();
        bank_rate.interest = $('#new_bank_rate_interest_' + id).val();
        new_bank_rates.push(bank_rate);
    }
    data.new_bank_rates = new_bank_rates;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank_loan/update';
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
        bank_loan_list();
	}
    $.ajax(ajax);
}

function bank_loan_remove(bank_loan_id)
{
    var html = '';
    html += '<div class="box box-danger">';
    html += '<div class="box-header with-border">';
    html += '<h3 class="box-title">Click Confirm to Delete</h3>';
    html += '</div>';
    html += '<div class="box-body">';
    html += '<div class="btn btn-secondary" onclick="popup_hide()">Cancel</div>';
    html += '<div class="width5"></div>';
    html += '<div class="btn btn-danger" onclick="bank_loan_destroy(\'' + bank_loan_id + '\')">Confirm</div>';
    html += '<div id="result"></div>';
    html += '</div>';
    html += '</div>';
    popup_show(html);
}

function bank_loan_destroy(bank_loan_id)
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_loan_id = bank_loan_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank_loan/destroy';
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
        bank_loan_list();
	}
    $.ajax(ajax);
}

function bank_rate_create()
{
    // set current_year
    var current_year = 0;
    for(i in app_data.edit_bank_rate_ids)
    {
        var id = app_data.edit_bank_rate_ids[i];
        current_year = $('#edit_bank_rate_year_' + id).val();
    }
    for(i in app_data.new_bank_rate_ids)
    {
        var id = app_data.new_bank_rate_ids[i];
        current_year = $('#new_bank_rate_year_' + id).val();
    }
    current_year++;

    // create new bank_rate
    var bank_rate_id = 0;
    for(i in app_data.new_bank_rate_ids)
    {
        bank_rate_id = app_data.new_bank_rate_ids[i];
    }
    bank_rate_id++;
    app_data.new_bank_rate_ids.push(bank_rate_id);

    var html = '';
    html += '<tr id="new_bank_rate_tr_' + bank_rate_id + '">';
    html += '<td><input id="new_bank_rate_year_' + bank_rate_id + '" type="text" class="form-control" value="' + current_year + '"></td>';
    html += '<td>';
    html += '<select id="new_bank_rate_rate_id_' + bank_rate_id + '" class="form-control select2" style="width: 100%;">';
    html += '<option value="">NA</option>';
    for(i in app_data.rates)
    {
        var rate = app_data.rates[i];
        html += '<option value="' + rate.id + '">' + rate.name + '</option>';
    }
    html += '</select>';
    html += '</td>';
    html += '<td>';
    html += '<select id="new_bank_rate_calculate_' + bank_rate_id + '" class="form-control select2" style="width: 100%;">';
    var calculates = app_data.calculates;
    for(i in calculates)
    {
        var calculate = calculates[i];
        html += '<option value="' + calculate + '">' + calculate + '</option>';
    }
    html += '</select>';
    html += '</td>';
    html += '<td><input id="new_bank_rate_interest_' + bank_rate_id + '"type="text"class="form-control" value="0"></td>';

    // action
    html += '<td>';
    html += '<div class="btn btn-danger" onclick="bank_rate_destroy(\'' + bank_rate_id + '\', \'new\')">';
    html += '<i class="fa fa-trash-o"></i>';
    html += '</div>';
    html += '</td>';

    html += '</tr>';
    $('#table_bank_rates').append(html);
    
    var options = {};
    options.minimumResultsForSearch = -1;
    $('.select2').select2(options);
}

function bank_rate_destroy(bank_rate_id, mode)
{
    $('#' + mode + '_bank_rate_tr_' + bank_rate_id).remove();

    if(mode == 'new')
    {
        for(i in app_data.new_bank_rate_ids)
        {
            var new_bank_rate_id = app_data.new_bank_rate_ids[i];
            if(new_bank_rate_id == bank_rate_id)
            {
                app_data.new_bank_rate_ids.splice(i, 1);
            }
        }
    }

    if(mode == 'edit')
    {
        for(i in app_data.edit_bank_rate_ids)
        {
            var edit_bank_rate_id = app_data.edit_bank_rate_ids[i];
            if(edit_bank_rate_id == bank_rate_id)
            {
                app_data.edit_bank_rate_ids.splice(i, 1);
            }
        }
    }

    if(mode == 'new')
    {
        return;
    }

    loading_show();

    var data = {};
    data.api_token = api_token;
    data.bank_rate_id = bank_rate_id;
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = app_url + '/admin/bank_rate/destroy';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = 'application/json; charset=utf-8';
	ajax.processData = false;
	ajax.success = function(response)
	{
        loading_hide();
	}
    $.ajax(ajax);
}