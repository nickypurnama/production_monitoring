/* [ ---- Gebo Admin Panel - wizard ---- ] */

	$(document).ready(function() {
		//* simple wizard
		gebo_wizard.simple();
		//* wizard with validation
		gebo_wizard.validation();
		//* add step numbers to titles
		gebo_wizard.steps_nb();
	});

	gebo_wizard = {
		simple: function(){
			$('#simple_wizard').stepy({
				titleClick	: true,
				nextLabel:      'Next <i class="icon-chevron-right icon-white"></i>',
				backLabel:      '<i class="icon-chevron-left"></i> Back'
			});
		},
		validation: function(){
			$('#validate_wizard').stepy({
				nextLabel:      'Next <i class="icon-chevron-right icon-white blue"></i>',
				backLabel:      '<i class="icon-chevron-left"></i> Back',
				block		: true,
				errorImage	: true,
				titleClick	: true,
				validate	: true
			});
			stepy_validation = $('#validate_wizard').validate({
				onfocusout: false,
				errorPlacement: function(error, element) {
					error.appendTo( element.closest("div.controls") );
				},
				highlight: function(element) {
					$(element).closest("div.control-group").addClass("error f_error");
					var thisStep = $(element).closest('form').prev('ul').find('.current-step');
					thisStep.addClass('error-image');
				},
				unhighlight: function(element) {
					$(element).closest("div.control-group").removeClass("error f_error");
					if(!$(element).closest('form').find('div.error').length) {
						var thisStep = $(element).closest('form').prev('ul').find('.current-step');
						thisStep.removeClass('error-image');
					};
				},
				rules: {
					'v_username'	: {
						required	: true,
						minlength	: 3
					},
					'v_email'		: 'email',
					'v_newsletter'	: 'required',
					'v_password'	: 'required',
					'v_city'		: 'required',
					'v_country'		: 'required',
					nama_lengkap : {
						required:  true,
						minlength	: 1
					},
					tgl_masuk : {
						required:  true
					},
					
					userfile : {
						required:  true
					},
					pt : {
						required:  true,
						minlength	: 1
					},
					divisi : {
						required:  true,
						minlength	: 1
					},
					lokasi : {
						required:  true,
						minlength	: 1
					},
					jabatan : {
						required:  true,
						minlength	: 1
					},
					level : {
						required:  true,
						minlength	: 1
					},
					grade : {
						required:  true,
						minlength	: 1
					},
					tgl_kkwt : {
						required:  true,
						minlength	: 1
					},
					tempat_lahir : {
						required:  true,
						minlength	: 1
					},
					tgl_lahir : {
						required:  true,
						minlength	: 1
					},
					//prod_store : {
					//	required:  true,
					//	minlength	: 1
					//},
					agama : {
						required:  true,
						minlength	: 1
					},
					hp : {
						required:  true,
						minlength	: 1
					},
					tlp : {
						required:  true,
						minlength	: 1
					},
					nama_kel : {
						required:  true,
						minlength	: 1
					},
					jk_kel : {
						required:  true,
						minlength	: 1
					},
					ktp : {
						required:  true,
						number :  true
					},
					alamat_kel : {
						required:  true,
						minlength	: 1
					},
					kota_kel : {
						required:  true,
						minlength	: 1
					},
					
					provinsi_kel : {
						required:  true,
						minlength	: 1
					},
					tlp_kel : {
						required:  true,
						minlength	: 1
					},
					hp_kel : {
						required:  true,
						minlength	: 1
					},
					hubungan_kel : {
						required:  true,
						minlength	: 1
					}
				}, messages: {
					'v_username'	: { required:  'Username field is required!' },
					'v_email'		: { email	:  'Invalid e-mail!' },
					'v_newsletter'	: { required:  'Newsletter field is required!' },
					'v_password'	: { required:  'Password field is requerid!' },
					'v_city'		: { required:  'City field is requerid!' },
					'v_country'		: { required:  'Country field is requerid!' },
					
				},
				ignore	: ':hidden'
			});
		},
		//* add numbers to step titles
		steps_nb: function(){
			$('.stepy-titles').each(function(){
				$(this).children('li').each(function(index){
					var myIndex = index + 1
					$(this).append('<span class="stepNb">'+myIndex+'</span>');
				})
			})
		}
	};