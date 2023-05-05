$(function() {
	$('#registration-form').submit(function(event) {
		event.preventDefault();
		$.ajax({
			url: 'register.php',
			method: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(response) {
				if (response.status == 'success') {
					alert('Registration successful!');
					window.location.href = 'login.html'; // Redirect to login page
				} else {
					alert('Registration failed: ' + response.message);
				}
			},
			error: function() {
				alert('Registration failed: Unknown error');
			}
		});
	});
});
