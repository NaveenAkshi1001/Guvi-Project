$(document).ready(function() {
	$('#login-form').submit(function(e) {
		e.preventDefault();
		var email = $('#email').val();
		var password = $('#password').val();
		$.ajax({
			type: 'POST',
			url: 'login.php',
			data: {
				email: email,
				password: password
			},
			success: function(data) {
				if (data.status == 'success') {
					localStorage.setItem('token', data.token);
					window.location.href = 'dashboard.html';
				} else {
					$('#error-message').html(data.message);
				}
			}
		});
	});
});
