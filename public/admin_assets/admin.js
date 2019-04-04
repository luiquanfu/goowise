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
    initialize_display();

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
        var message = response.message;
        
        if(error != 0)
        {
            login_display();
            return;
        }

        admin_display();
	}
    $.ajax(ajax);
}

function initialize_display()
{
    var html = '';
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

    $('#app').html(html);
}

function login_display()
{
    var html = '';
    html += '<div class="login_component">';
    html += '<img src="' + app_url + '/admin_assets/logo.jpg" class="login_image">';
    html += '<div class="height20"></div>';
    html += '<div class="login_background">';
    html += '<div class="login_box">';
    html += 'Admin Login';
    html += '<div class="height10"></div>';
    html += '<input type="text" id="email" class="login_textbox" autocomplete="off" placeholder="Email">';
    html += '<div class="height10"></div>';
    html += '<input type="password" id="password" class="login_textbox" placeholder="Password">';
    html += '<div class="height10"></div>';
    html += '<div class="login_button" onclick="login_submit()">Sign In</div>';
    html += '<div class="height10"></div>';
    html += '<div id="result" class="login_result"></div>';
    html += '</div>';
    html += '</div>';
    html += "</div>";
    $('#app').html(html);
}

function login_submit()
{
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
			$('#result').html('<font color="FF0000">' + message + '</font>');
			return;
		}
		
        $('#result').html('<font color="008000">' + message + '</font>');
	}
    $.ajax(ajax);
}

function admin_display()
{
    var html = '';
    html += '<nav class="navbar">';
    html += '<div class="navbar_header">';
    html += '<img src="' + app_url + '/admin_assets/logo.jpg" height="40">';
    html += '<div id="mobile-nav" onclick="navigation()">Menu</div>';
    html += '</div>';
    html += '<div class="collapse navbar-collapse">';
    html += '<ul class="nav navbar-nav navbar-right">';


    // navbar ending
    html += '</ul>';
    html += '</div>';
    html += '</nav>';
    $('#app').html(html);
}

function bankrate_list()
{
    var html = '';
    html += '<h1>bank rate</h1>';
    $('#app').html(html);
}

function bankrate_create()
{
}