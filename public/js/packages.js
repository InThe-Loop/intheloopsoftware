$(function(){
	"use strict";

	$('.down').on('click', function() {
		$('.down, .up').toggleClass('up down');
		$('.package__all').slideToggle('slow');
	});

	$('.get-started').on('click', function() {
		let type = $(this).data('type');
		$('#type').val(type);
		$('.package').hide();
		$('.step-by-step').slideDown('slow');
		if (type === "starter") {
			$('#package-type').text('Starter');
			$('#package-once').text('5,000');
			$('#package-month').text('100');
			$('#package-weeks').text('1');
			$('#package-integrate').text('NO');
		}
		else if (type === "business") {
			$('#package-type').text('Business');
			$('#package-once').text('10,000');
			$('#package-month').text('140');
			$('#package-weeks').text('2');
			$('#package-integrate').text('NO');
		}
		else if (type === "elite") {
			$('#package-type').text('Elite');
			$('#package-once').text('20,000');
			$('#package-month').text('200');
			$('#package-weeks').text('4');
			$('#package-integrate').text('YES');
		}
	});

	$('.btn-cancel').on('click', function() {
		$('.package').slideUp();
		$('.step-by-step').hide();
	});

	$('.btn-next').on('click', function(e) {
		e.preventDefault();
		var currentStepNum = $('#packages-progress').data('current-step');
		var nextStepNum = (currentStepNum + 1);
		var currentStep = $('.step.step-' + currentStepNum);
		var nextStep = $('.step.step-' + nextStepNum);
		var progressBar = $('#packages-progress');
		$('.btn-cancel').hide();
		$('.btn-prev').removeClass('disabled');
		$('.btn-prev').show();
		let name = $('#name');
		let email = $('#email');
		let phone = $('#phone');
		let email_pattern = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		let phone_pattern = /^(0){1}[0-9]{9}$/;
		let requirements = $('#requirements');
		if (currentStepNum == 1) {
			let error = 0;
			name.bind('keyup', function() {
				name.removeClass('packages-error');
				$('#name-error').hide();
			});
			email.bind('keyup', function() {
				email.removeClass('packages-error');
				$('#email-error').hide();
			});
			phone.bind('keyup', function() {
				phone.removeClass('packages-error');
				$('#phone-error').hide();
			});
			if (name.val().length < 5) {
				name.addClass('packages-error');
				$('#name-error').show().text('This is probably too short!');
				error++;
			}
			if (!email_pattern.test(email.val())) {
				email.addClass('packages-error');
				$('#email-error').show().text('Please provide a valid email adress.');
				error++;
			}
			if (!phone_pattern.test(phone.val()) || phone.val().length === 0) {
				phone.addClass('packages-error');
				$('#phone-error').show().text('Please provide a valid phone number.');
				error++;
			}
			if(error) {
				return false;
			}
		}
		if (currentStepNum == 2) {
			requirements.bind('keyup', function() {
				requirements.removeClass('packages-error');
				$('#requirements-error').hide();
			});
			if (requirements.val().length < 100) {
				requirements.addClass('packages-error');
				$('#requirements-error').show().text('Please provide us more details so we can better understand your requirements.');
				return false;
			}
			// Create preview
			$('#package-email').text(email.val());
			let details = '<p><strong>Personal details</strong><br />Name: ' + name.val() + ' | Phone: ' + phone.val() + ' | Email: ' + email.val() + '</p>';
			let require = '<p><strong>Requirements</strong><br /> ' + requirements.val() + '</p>';
			$('#preview').html(details + require);
			$('.btn-next').hide();
			$('.btn-download').show();
			$('.btn-email').show();
		}
		$('#section'+currentStepNum).toggle();
		$('#section'+nextStepNum).toggle();
		// if(nextStepNum == 4) {
		// 	$(this).toggle();
		// 	$('.btn-prev, .btn-download, .btn-email').toggle();
		// }
		
		$('.packages-progress').removeClass('.step-' + currentStepNum).addClass('.step-' + (currentStepNum + 1));
		
		currentStep.removeClass('active').addClass('valid');
		currentStep.find('span').addClass('opaque');
		currentStep.find('.fa.fa-check').removeClass('opaque');
		
		nextStep.addClass('active');
		progressBar.removeAttr('class').addClass('step-' + nextStepNum).data('current-step', nextStepNum);
	});

	$('.btn-download, .btn-email').on('click',function() {
		if ($.trim($(this).text()) == "Download") {
			$('#send-email').val('no');
		}
		else {
			$('#send-email').val('yes');
		}
		var currentStepNum = $('#packages-progress').data('current-step');
		var nextStepNum = (currentStepNum + 1);
		var currentStep = $('.step.step-' + currentStepNum);
		var nextStep = $('.step.step-' + nextStepNum);
		var progressBar = $('#packages-progress');
		$('#section3').toggle();
		$('#section4').toggle();
		progressBar.removeClass('.step-3').addClass('.step-4');
		currentStep.removeClass('active').addClass('valid');
		currentStep.find('span').addClass('opaque');
		currentStep.find('.fa.fa-check').removeClass('opaque');
		nextStep.addClass('active');
		progressBar.removeAttr('class').addClass('step-4').data('current-step4');
		$('.btn').hide();

		$('#package-form').submit();
	});

	$('.btn-prev').on('click', function() {
		var currentStepNum = $('#packages-progress').data('current-step');
		var prevStepNum = (currentStepNum - 1);
		var currentStep = $('.step.step-' + currentStepNum);
		var prevStep = $('.step.step-' + prevStepNum);
		var progressBar = $('#packages-progress');
		$('.btn-next').removeClass('disabled');
		$('#section'+currentStepNum).toggle();
		$('#section'+prevStepNum).toggle();
		if(currentStepNum == 4){
			$('.btn-submit').toggle();
			$('.btn-next').toggle();
		}
		if(currentStepNum == 1) {
			$('.btn-next').show();
			$('.btn-download').hide();
			$('.btn-email').hide();
			return false;
		}
		if(prevStepNum == 1){
			$(this).addClass('disabled');
		}
		if(currentStepNum == 3) {
			$('.btn-next').show();
			$('.btn-download').hide();
			$('.btn-email').hide();
		}
		$('.packages-progress').removeClass('.step-' + currentStepNum).addClass('.step-' + (prevStepNum));
		
		currentStep.removeClass('active');
		prevStep.find('span').removeClass('opaque');
		prevStep.find('.fa.fa-check').addClass('opaque');
		
		prevStep.addClass('active').removeClass('valid');
		progressBar.removeAttr('class').addClass('step-' + prevStepNum).data('current-step', prevStepNum);
	});
});
