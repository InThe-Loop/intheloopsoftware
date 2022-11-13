/**
* Validate inputs
*/
$(function() {
	"use strict";
	
	// Validate form fields
	$(".contact-form").validate({
		// Specify validation rules
		rules: {
			email: {
				required: true,
				email: true
			},
			name: {
				required: true,
				minlength: 5,
			},
			phone: {
				phoneUK: true,
				minlength: 10,
			},
			subject: "required",
			message: {
				required: true,
				minlength: 15,
			}
		},
		// Specify validation error messages
		messages: {
			email: {
				required: "Please specify your email address so that we'd be able to reach you."
			},
			name: {
				required: "Please provide us with your full name for communication purposes.",
				minlength: $.validator.format("At least {0} characters are required for your name."),
			},
			phone: {
				phoneUK: "Please ensure this is a valid South African telephone number.",
				minlength: $.validator.format("You may not enter less than {0} digits!"),
			},
			subject: {
				required: "Please select the subject line of your email to us."
			},
			message: {
				required: "The message will form the body part of the email you send us.",
				minlength: $.validator.format("At least {0} characters are required for the message."),
			}
		}
	});
	
	let form = document.getElementById('contact-form');
	form.addEventListener('submit', function(event) {
		event.preventDefault();
		if ($(form).valid()) {
			form.querySelector('.loading').classList.remove('hide');
			form.querySelector('.send-message').classList.add('disabled');
			let formData = new FormData(form);
			email_form(form, "/sendmail", formData);
		}
	});

	function email_form(thisForm, action, formData) {
		fetch(action, {
			method: 'POST',
			body: formData,
			headers: {'X-Requested-With': 'XMLHttpRequest'}
		})
		.then(response => {
			return response.text();
		})
		.then(data => {
			thisForm.querySelector('.loading').classList.add('hide');
			thisForm.querySelector('.send-message').classList.remove('disabled');
			let response = $.parseJSON(data);
			if (response.code === 200 && response.status === "OK") {
				thisForm.querySelector('.message-sent').innerHTML = response.text;
				$('.hide-away').slideUp('slow');
				thisForm.reset();
			}
			else {
				thisForm.querySelector('.error-message').innerHTML = response.text;
				thisForm.querySelector('.error-message').classList.remove('hide');
			}
		})
		.catch((error) => {
			displayError(thisForm, error);
		});
	}

	function displayError(thisForm, error) {
		thisForm.querySelector('.loading').classList.add('hide');
		thisForm.querySelector('.send-message').classList.remove('disabled');
		thisForm.querySelector('.error-message').innerHTML = error;
		thisForm.querySelector('.error-message').classList.remove('hide');
	}
});
