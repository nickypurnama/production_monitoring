<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]--> 
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>Production Monitoring | Vivere Group</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<!-- BEGIN GLOBAL MANDATORY STYLES -->        
	<link href="<?=base_url()?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url()?>assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
	<link href="<?=base_url()?>assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url()?>assets/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url()?>assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url()?>assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="<?=base_url()?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<!-- END GLOBAL MANDATORY STYLES -->
	
	<!-- BEGIN PAGE LEVEL STYLES --> 
	<link href="<?=base_url()?>assets/css/pages/tasks.css" rel="stylesheet" type="text/css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/gritter/css/jquery.gritter.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/chosen-bootstrap/chosen/chosen.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/select2/select2_metro.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/clockface/css/clockface.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-datepicker/css/datepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-timepicker/compiled/timepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-colorpicker/css/colorpicker.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-datetimepicker/css/datetimepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/jquery-multi-select/css/multi-select-metro.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-modal/css/bootstrap-modal.css"  />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-metro.css"  />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/jquery-tags-input/jquery.tagsinput.css" />
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/data-tables/DT_bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/allert/jquery.alerts.css"  media="screen" />
	<link href="<?php echo base_url(); ?>assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
	<!-- END PAGE LEVEL STYLES -->
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.ico" />
	<!-- wizard -->
        <link rel="stylesheet" href="<?=base_url()?>assets/stepy/css/jquery.stepy.css" />
	<script src="<?=base_url()?>assets/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	<!--<script type="text/javascript" src="<?=base_url()?>assets/ckeditor/ckeditor.js"></script>-->
	<script>
		
		$(document).ready(function()
		{
			$("#spinner").show();
			$("#btn_sync").click(function(){
				$("#spinner").show();
			});
			$("#btn_loading").click(function(){
				$("#spinner").show();
			});
			
			$(".btn_loading").click(function(){
				$("#spinner").show();
			});
		});
		$(window).load(function() { $("#spinner").fadeOut("slow"); });
	</script>
	<style>
		#spinner {
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background: rgba( 255, 255, 255, .5 ) 
						url('<?php echo base_url()."assets/img/waiting.gif" ?>') 
						50% 50% 
						no-repeat;
		}
	</style>
</head>
<!-- END HEAD -->