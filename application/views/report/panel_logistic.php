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
    });
</script>
<style type="text/css">
    #sample_3 td{
        text-align: center;
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
                        <div class="caption"><i class="icon-tasks"></i> Report Panel Logistic</div>
                        <div class="actions">
                            <button id="btn_toggle" class="btn brown">Show/Hide</button>
                        </div>
                    </div>
                    <div id="div_search" class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('report/panel_logistic'); ?>" id="form_sample_1" class="form-horizontal" method="get" enctype="multipart/form-data">
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
                <div class="row-fluid">
                    <table class="table table-striped table-advance table-bottom table-hover" id="sample_3">
                        <thead>
                            <tr class="bg-brown">
                                <th>No</th>
                                <th>Production Order</th>
                                <th>Group Name</th>
                                <th>Pack Internal</th>
                                <th>Pack Eksternal</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Reject Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($packing_code)){
                                    $no=1;
                                    $error = 0;
                                    foreach($packing_code as $row){ ?>
                                        <tr>
                                            <td><?php echo $no ?></td>
                                            <td><?php echo $row['production_order'] ?></td>
                                            <td><?php echo $row['module'] ?></td>

                                            <?php
                                                if($row['is_panel'] == "1"){ ?>
                                                    <td><?php echo $row['packing_code'] ?></td>
                                                    <td></td>
                                                <?php }else{ ?>
                                                    <td></td>
                                                    <td><?php echo $row['packing_code'] ?></td>
                                                <?php }

                                                if($row['reject_reason'] == ""){ ?>
                                                    <td>Approved</td>
                                                    <td></td>
                                                    <td></td>
                                                <?php }else{ ?>
                                                    <td>Rejected</td>
                                                    <td><?php echo $row['remark'] ?></td>
                                                    <td><a href="<?php echo base_url() ?>/assets/img/panel-logistic/<?php echo $row['reject_reason'] ?>" target="_blank"><button class="btn brown mini">View</button></a></td>
                                                <?php }
                                            ?>  
                                        </tr>
                                        <?php 
                                        $no++;
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row-fluid">
                    <form name="aaaa" method="post" action="<?php echo site_url('report/export_panel_logistic'); ?>">
                        <input type="hidden" id="production_order" name="production_order" value="<?=$production_order?>" >
                        <button type="submit" class="btn brown">Export</button>
                    </form>
                </div>
            <?php }
        ?>
    </div>
</div>