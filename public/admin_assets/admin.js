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
    data.name = $('#name').val();
    data.sku = $('#sku').val();
    data.description = $('#description').val();
    data.service_category_id = $('#service_category_id').val();
    data.logo = $('#logo').val();
    data.featured = $('#featured').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = url + '/api/admin/service/add';
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
			$('#result').html('<font color="FF0000">' + message + '</font>');
			return;
		}
		
        $('#result').html('<font color="008000">' + message + '</font>');
        list();
	}
    $.ajax(ajax);
    
    login_display();
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
    html += '<input type="text" id="email" class="login_textbox" placeholder="Email">';
    html += '<div class="height10"></div>';
    html += '<input type="password" id="password" class="login_textbox" placeholder="Password">';
    html += '<div class="height10"></div>';
    html += '<div class="login_button" onclick="login_submit()">Sign In</div>';
    html += '<div class="login_result"></div>';
    html += '</div>';
    html += '</div>';
    html += "</div>";
    $('#app').html(html);
}

function login_submit()
{
    loading_show();

    var data = {};
    data.api_token = api_token;
    data.name = $('#name').val();
    data.sku = $('#sku').val();
    data.description = $('#description').val();
    data.service_category_id = $('#service_category_id').val();
    data.logo = $('#logo').val();
    data.featured = $('#featured').val();
    data = JSON.stringify(data);

    var ajax = {};
	ajax.url = url + '/api/admin/service/add';
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
			$('#result').html('<font color="FF0000">' + message + '</font>');
			return;
		}
		
        $('#result').html('<font color="008000">' + message + '</font>');
        list();
	}
    $.ajax(ajax);
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