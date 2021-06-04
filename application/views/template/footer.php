<!-- BEGIN FOOTER -->
<div class="footer">
		<div class="footer-inner">
			2016 &copy; IS Department.
		</div>
		<div class="footer-tools">
			<span class="go-top">
			<i class="icon-angle-up"></i>
			</span>
		</div>
	</div>
	<!-- END FOOTER -->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
	<!-- BEGIN CORE PLUGINS -->   
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="<?=base_url()?>assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>      
	<script src="<?=base_url()?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
	<!--[if lt IE 9] -->
	<script src="<?=base_url()?>assets/plugins/excanvas.min.js"></script>
	<script src="<?=base_url()?>assets/plugins/respond.min.js"></script>  
	<![endif]-->   
	<script src="<?=base_url()?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>  
	<script src="<?=base_url()?>assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
	<!-- END CORE PLUGINS -->
	
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/select2/select2.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/clockface/js/clockface.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-daterangepicker/date.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script> 
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>  
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>   
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery.input-ip-address-control-1.0.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>   
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-modal/js/bootstrap-modal.js"  ></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"  ></script> 
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery.pwstrength.bootstrap/src/pwstrength.js"  ></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/bootstrap-switch/static/js/bootstrap-switch.js"  ></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery-tags-input/jquery.tagsinput.min.js"  ></script>
	
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery.pulsate.min.js" ></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/gritter/js/jquery.gritter.js" ></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery-validation/dist/jquery.validate.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/jquery-validation/dist/additional-methods.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/data-tables/jquery.dataTables.js"></script>
	<script src="<?=base_url()?>assets/plugins/data-tables/dataTables.fnGetHiddenNodes.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/plugins/data-tables/DT_bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/allert/jquery.alerts.js" ></script>
	<!-- END PAGE LEVEL PLUGINS -->
	
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script type="text/javascript" src="<?=base_url()?>assets/scripts/app.js" ></script>
	<script type="text/javascript" src="<?=base_url()?>assets/scripts/tasks.js" ></script>
	<script type="text/javascript" src="<?=base_url()?>assets/scripts/form-validation.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/scripts/table-managed.js"></script>
	<script src="<?=base_url()?>assets/plugins/fancybox/source/jquery.fancybox.pack.js"></script>   
	<!-- begin wizard -->
	<script src="<?=base_url()?>assets/stepy/js/jquery.stepy.js"></script>
	<script src="<?=base_url()?>assets/stepy/gebo_wizard.js"></script>
	<script src="<?=base_url()?>assets/scripts/form-components.js"></script>
	
	<!-- end wizard -->
	<!-- END PAGE LEVEL SCRIPTS -->  
	<script>
		jQuery(document).ready(function() {    
		   App.init(); // initlayout and core plugins
		   FormComponents.init();
		   FormValidation.init();
		   TableManaged.init();
			$('#tgl1').datepicker({
				calendarWeeks: true,
				format: "dd-mm-yyyy",
				weekStart: 1,
				autoclose: true
			});
			$('#tgl2').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true
			});
			$('#tgl_ppic').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true
			});
			$('#tgl_pembahanan').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true
			});
			$('#tgl_perakitan').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true
			});
			$('#tgl_finishgood').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true
			});
			$('#tgl_finishing').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true
			});
			$('#tgl_pengiriman').datepicker({
				format: "dd-mm-yyyy",
				autoclose: true
			});
			
			var oTable = $('#sample_3').dataTable();
			$('#form_sample_1').submit(function(){
				$(oTable.fnGetHiddenNodes()).find('input:checked').appendTo(this).hide();
			 });
		});
		
	</script>
	 <script type="text/javascript">
		//CKEDITOR.replace( 'isi',
		//	{
		//		fullPage : true
		//	});

	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>