<!-- BEGIN PAGE -->
<script type="text/javascript">
    $(document).ready(function(){

        var div_search = document.getElementById("div_search");
        if(<?php echo $hide_header ?> == 1){
            div_search.style.display = "none";
        }
        
        $("#btn_toggle").click(function(){
            $("#div_search").toggle("slow");
        });
        
        $("#btn_batch").click(function(){
                
          var xbatch = $("#batch").val();

          if(xbatch == ""){
            alert("Batch is required");
          }else{
            $.ajax({ 
              type: 'POST', 
              url: "<?php echo site_url('ajax/get_production_order_by_batch'); ?>", 
              data: {
                batch : xbatch
              }, 
              success: function(msg) {
                var div_production_order = document.getElementById("div_production_order");
                div_production_order.style.display = "block";
                
                $("#production_order").html(msg);
              }
            });
          }
        });

        $('#qrcode-text').keyup(function(){
            var xtype = $("#type").val();

            if((this.value.includes('xxl') || this.value.includes('xml')) && xtype == "Normal"){
                $('#qrcode-submit').click();
            }
        });

        $('#modal-scan').on('shown.bs.modal', function() {
            $('#qrcode-text').trigger('focus');
        });

        function reset() {
          document.getElementById("form-scan").reset();
        }
        // setTimeout(function() {
        //   location.reload();
        // }, 5000);
    });
</script>
<style type="text/css">
    #sample_3 td{
        text-align: center;
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
    }

    .header-item {
        font-size: 16px;
    }

    .header-item-detail {
        font-size: 30px;
    }

    .header-right {
        border: 1px solid;
        text-align: center;
    }

    .header-right .header-item {
        border-bottom: 1px solid;
    }

</style>

<div class="page-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <div class="row-fluid">
            
            <div class="span12">
                <br>&nbsp;<br>&nbsp;
                <?php
                    $msg=$this->session->flashdata('msg');
                    echo $msg;
                ?>
                <!-- BEGIN PAGE CONTENT-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-tasks"></i> Station C - Grouping & Packing</div>
                        <div class="actions">
                            <button id="btn_toggle" class="btn brown">Show/Hide</button>
                        </div>
                    </div>
                    <div id="div_search" class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('transaction/station_c'); ?>" id="form_sample_1" class="form-horizontal" method="get" enctype="multipart/form-data">
                            <div class="alert alert-error hide">
                                <button class="close" data-dismiss="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-success hide">
                                <button class="close" data-dismiss="alert"></button>
                                Your form validation is successful!
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Batch</label>
                                        <div class="controls">
                                            <input type="text" id="batch" name="batch" class="span4" value="<?php echo $batch ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Type</label>
                                        <div class="controls">
                                            <select id="type" name="type" class="span4 select2 select2_sample1" required="true">
                                                <option></option>
                                                <?php
                                                    if($type == "Mass Pro"){ ?>
                                                        <option value="Mass Pro" selected="true">Mass Pro</option>
                                                        <option value="Normal">Normal</option>
                                                    <?php }elseif($type == "Normal"){ ?>
                                                        <option value="Mass Pro">Mass Pro</option>
                                                        <option value="Normal" selected="true">Normal</option>
                                                    <?php }else{ ?>
                                                        <option value="Mass Pro">Mass Pro</option>
                                                        <option value="Normal">Normal</option>
                                                    <?php }
                                                ?>      
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <div class="controls">
                                            <input type="button" id="btn_batch" class="btn brown" value="Search">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                if($production_order <> ""){ ?>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="control-group">
                                                <label class="control-label">Production Order</label>
                                                <div class="controls">
                                                    <select id="production_order" name="production_order" class="span4 select2 select2_sample1">
                                                        <option></option>
                                                        <?php
                                                            foreach($list_production_order as $row){ 
                                                                if($row['production_order'] == $production_order){ ?>
                                                                    <option value="<?php echo $row['production_order'] ?>" selected="true"><?php echo $row['production_order'] ?></option>
                                                                <?php }else{ ?>
                                                                    <option value="<?php echo $row['production_order'] ?>"><?php echo $row['production_order'] ?></option>
                                                                <?php }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <span class="pull-right">
                                            <button type="submit" class="btn brown"><i class="icon-ok"></i> Submit</button>
                                        </span>
                                    </div>
                                <?php }else{ ?>
                                    <div id="div_production_order" style="display: none;">
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Production Order</label>
                                                    <div class="controls">
                                                        <select id="production_order" name="production_order" class="span4 select2 select2_sample1">
                                                            <option></option>
                                                            <?php
                                                                foreach($list_production_order as $row){ ?>
                                                                    <option value="<?php echo $row['production_order'] ?>"><?php echo $row['production_order'] ?></option>
                                                                <?php }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <span class="pull-right">
                                                <button type="submit" class="btn brown"><i class="icon-ok"></i> Submit</button>
                                            </span>
                                        </div>
                                    </div>
                                <?php }
                            ?>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
        <?php
            if($production_order <> ""){ ?>
                <div class="row-fluid" style="margin-bottom: 10px;">
                    <form id="form-scan" action="<?php echo site_url('transaction/scan_panel'); ?>" method="post" class="form-horizontal">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label">QR Code</label>
                                    <div class="controls">
                                        <input id="qrcode-text" name="qrcode-text" type="text" required="true" autocomplete="off" autofocus="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            if($type <> "Normal"){ ?>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="control-group">
                                            <label class="control-label">Qty Panel</label>
                                            <div class="controls">
                                                <input id="qty_panel" name="qty_panel" type="number" required="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        ?>
                        <div class="form-actions">
                            <span class="pull-right">
                                <input type="hidden" name="batch" value="<?php echo $batch ?>">
                                <input type="hidden" name="type" value="<?php echo $type ?>">
                                <input type="hidden" name="production_order" value="<?php echo $production_order ?>">
                                <input type="hidden" name="position" value="25">
                                <input type="hidden" name="position_new" value="26">
                                <button type="submit" id="qrcode-submit" class="btn brown"><i class="icon-ok"></i> Submit</button>
                                <button class="btn btn-default" onclick="reset()" style="margin-top: 3px;">Reset</button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <p class="header-item">BATCH: <b><?php echo $batch ?></b></p>
                        <p class="header-item">PROD. ORDER: <b><?php echo $production_order ?></b></p>
                    </div>
                    <div class="span2 header-right">
                        <p class="header-item">KODE GROUPING:</p>
                        <p class="header-item-detail"><b><?php echo $grouping_code ?></b></p>
                    </div>
                    <div class="span2 header-right">
                        <p class="header-item">KOMPONEN:</p>
                        <p class="header-item-detail"><b><?php echo $total_component_scan ?>/<?php echo $total_component ?></b></p>
                    </div>
                    <div class="span2 header-right">
                        <p class="header-item">MODUL:</p>
                        <p class="header-item-detail"><b><?php echo $total_module_scan ?>/<?php echo $total_module ?></b></p>
                    </div>
                </div>
                <br>
                <div id="panel" class="row-fluid">
                    <?php
                        $no = 1;
                        foreach($module as $row){ 
                            if($row['total_scan'] == $row['total_panel'])
                                $class = "item-complete";
                            else
                                $class = "item-not-complete";

                            ?>
                            <a data-toggle="modal" data-target="#modal-<?php echo $no ?>">
                                <div class="span2 item-block <?php echo $class ?>">
                                    <p>MODUL: <?php echo $row['module'] ?><br>PART QTY: <?php echo $row['total_packing_code_scan'] ?>/<?php echo $row['total_packing_code'] ?></p>
                                </div>
                            </a>
                            <div class="panel-modal modal fade" id="modal-<?php echo $no ?>" role="dialog" style="display: none; left: 25%;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                          <h3 class="modal-title">Panel</h3>
                                        </div>
                                        <div class="modal-body" style="height: 500px;">
                                            <iframe style="height: 500px;" src="<?=base_url()?>transaction/station_c_panel/<?php echo $production_order ?>/<?php echo $row['module'] ?>" class="span12">
                                                <p>Your browser does not support iframes.</p>
                                            </iframe>
                                            
                                        </div>
                                        <div class="modal-footer">
                                          <button class="btn btn-default" data-dismiss="modal" style="margin-top: 3px;">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        $no++;
                        }
                    ?>
                </div>
                <div class="row-fluid">
                    <form name="aaaa" method="post" action="<?php echo site_url('transaction/export_station'); ?>">
                        <input type="hidden" id="batch" name="batch" value="<?=$batch?>" >
                        <input type="hidden" id="production_order" name="production_order" value="<?=$production_order?>" >
                        <input type="hidden" id="position" name="position" value="25" >
                        <button type="submit" class="btn brown">Export</button>
                    </form>
                </div>
            <?php }
        ?>
    </div>
</div>