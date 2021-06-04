<!-- BEGIN PAGE -->
<script type="text/javascript">
    $(document).ready(function(){
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

        // setTimeout(function() {
        //   location.reload();
        // }, 10000);
    });
</script>
<style type="text/css">
    #sample_3 td{
        text-align: center;
    }

    .item-block {
        padding: 20px;
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
                        <div class="caption"><i class="icon-tasks"></i> Packing List</div>
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('transaction/export_packing_list'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                                        <select id="production_order" name="production_order" class="span4 select2 select2_sample1" required="true">
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
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Type</label>
                                                    <div class="controls">
                                                        <select id="type" name="type" class="span4 select2 select2_sample1" required="true">
                                                            <option></option>
                                                            <option value="Excel">Excel</option>
                                                            <option value="PDF">PDF</option>
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
    </div>
</div>