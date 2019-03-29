function register()
{
	$('#register_result').html('<font color="0000FF">Please wait for a moment</font>');
	
	var name = $('#register_name').val();
	var email = $('#register_email').val();
	var mobile = $('#register_mobile').val();
	var reason = $('#register_reason').val();
	var google = $('#g-recaptcha-response').val();
	
	var data = new FormData();
	data.append('name', name);
	data.append('email', email);
	data.append('mobile', mobile);
	data.append('reason', reason);
	data.append('google', google);

	var ajax = {}
	ajax.url = app_url + '/website/register';
	ajax.data = data;
	ajax.type = 'post';
	ajax.contentType = false;
	ajax.processData = false;
	ajax.success = function(response)
	{
		var error = response.error;
		var message = response.message;
		
		if(error == 1)
		{
			$('#register_result').html('<font color="FF0000">' + message + '</font>');
			return;
		}
		
		$('#register_result').html('<font color="008000">' + message + '</font>');
		$('#register_button').hide();
	}
	$.ajax(ajax);
}