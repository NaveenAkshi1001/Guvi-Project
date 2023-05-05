$(document).ready(function() {
	// Load user profile data on page load
	var token = localStorage.getItem('token');
	$.ajax({
		url: 'profile.php',
		type: 'POST',
		data: { token: token },
		success: function(data) {
			if (data.success) {
				var profileData = data.profile;
				$('#profile-data').html(
					'<p><strong>Name:</strong> ' + profileData.name + '</p>' +
					'<p><strong>Email:</strong> ' + profileData.email + '</p>' +
					'<p><strong>Age:</strong> ' + (profileData.age ? profileData.age : 'N/A') + '</p>' +
					'<p><strong>Date of Birth:</strong> ' + (profileData.dob ? profileData.dob : 'N/A') + '</p>' +
					'<p><strong>Contact:</strong> ' + (profileData.contact ? profileData.contact : 'N/A') + '</p>'
				);
				$('#profile-form #age').val(profileData.age);
				$('#profile-form #dob').val(profileData.dob);
				$('#profile-form #contact').val(profileData.contact);
			} else {
				alert('Failed to load profile data');
			}
		},
		error: function() {
			alert('Failed to connect to server');
		}

	// Submit updated profile data
    $('#profile-form').submit(function(event) {
		event.preventDefault();
		var age = $('#age').val();
		var dob = $('#dob').val();
		var contact = $('#contact').val();
		$.ajax({
			url: 'update_profile.php',
			type: 'POST',
			data: { token: token, age: age, dob: dob, contact: contact },
			success: function(data) {
				if (data.success) {
					alert('Profile updated successfully');
				} else {
					alert('Failed to update profile');
				}
			},
			error: function() {
				alert('Failed to connect to server');
		    }
	    });

	
