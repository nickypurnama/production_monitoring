<!-- BEGIN PAGE -->
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
</head>
<!-- END HEAD -->
<script type="text/javascript">
</script>
<style type="text/css">
    body {
        text-align: center;
        background-color: #fff !important;
    }

    .item-block {
        padding: 15px 5px 15px 5px;
        border: 1px solid;
        margin-bottom: 20px;
        margin-left: 0px !important;
        margin-right: 25px;
    }

    .item-complete {
        border: 1px solid #9ccc66 !important;
        background-color: #9ccc66 !important;
    }

    .item-not-complete {
        border: 1px solid #f5493d !important;
        background-color: #f5493d !important;
    }

    .item-block p {
        margin-bottom: 0px;
        color: #fff !important;
        font-size: 20px;
    }

    p, td {
        font-weight: 700;
    }
</style>

<body>
<div class="page-content" style="margin-left: 0px;">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <p style="font-size: 24px; margin-top: 10px;"><?php echo $production_order ?></p>
            </div>
        </div>
        <div class="row-fluid">
            <?php
                if($total_module == $total_module_scan)
                    $class = "item-complete";
                else
                    $class = "item-not-complete";
                ?>

                <div class="span12 item-block <?php echo $class ?>">
                    <p>MODUL QTY: <?php echo $total_module_scan ?>/<?php echo $total_module ?></p>
                </div>

                <?php 

                if($total_panel == $total_panel_scan){ ?>
                    <div class="span12 item-block item-complete">
                        <p>PACK INTERNAL QTY: <?php echo $total_panel_scan ?>/<?php echo $total_panel ?></p>
                    </div>
                <?php } else { ?>
                    <div class="span12 item-block item-not-complete">
                        <p>PACK INTERNAL QTY: <?php echo $total_panel_scan ?>/<?php echo $total_panel ?></p>
                    </div>
                <?php }

                if($total_non_panel == $total_non_panel_scan){ ?>
                    <div class="span12 item-block item-complete">
                        <p>PACK EKSTERNAL QTY: <?php echo $total_non_panel_scan ?>/<?php echo $total_non_panel ?></p>
                    </div>
                <?php } else { ?>
                    <div class="span12 item-block item-not-complete">
                        <p>PACK EKSTERNAL QTY: <?php echo $total_non_panel_scan ?>/<?php echo $total_non_panel ?></p>
                    </div>
                <?php }
            ?>
        </div>
    </div>
</div>
<!-- BEGIN FOOTER -->
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
</body>
<!-- END BODY -->
</html>