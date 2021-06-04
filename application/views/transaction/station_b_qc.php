<!-- BEGIN PAGE -->
<script type="text/javascript">

    function reject_approval(){

        var xproduction_order = $("#production_order").val();
        var xbatch = $("#batch").val();
        var xline_item = $("#line_item").val();
        var xreason = $("#reason").val();
        var xremark = $("#remark").val();

        if(xreason == ""){
            alert("Reason is required");
        }else{
            jConfirm("Reject QC?", "Vivere", function(r) {
                if(r == true){
                    $.ajax({
                    type: 'POST', 
                    url: "<?php echo site_url('transaction/reject_approval'); ?>",
                    data: {
                        production_order : xproduction_order,
                        batch : xbatch,
                        line_item : xline_item,
                        reason : xreason,
                        remark : xremark
                    },
                    success: function(msg){
                        <?php
                            $url = "transaction/station_b_qc/".$production_order."/".$batch."/".$type."/".$line_item."/1";
                        ?>
                        document.location='<?php echo site_url($url); ?>';
                    }
                    }); 
                }
            });
        }
    }

    function validateForm(){

        var xproduction_order = $("#production_order").val();
        var xbatch = $("#batch").val();
        var xtype = $("#type").val();
        var xline_item = $("#line_item").val();
        var xqty_confirm = $("#qty_confirm").val();
        var xqty_reject = $("#qty_reject").val();
        var xreason = $("#reason").val();
        var xremark = $("#remark").val();

        if(xqty_confirm == "" && xqty_reject == ""){
            alert("Qty Confirm or Qty Reject is required");
            return false;
        }else if(xqty_reject > 0 && xreason == ""){
            alert("Reason is required");
            return false;
        }else{
            return confirm('Submit QC?');
        }
    }

    function close_window() {
        close();
    }
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
                <!-- BEGIN PAGE CONTENT-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-tasks"></i> Approval Station B - QC</div>
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <?php
                            if($type == "Normal"){
                                if($approval == "-1"){ ?>
                                    <div class="alert alert-success">
                                        <strong>Success!</strong> Reject QC Berhasil
                                    </div>
                                    <div class="form-actions">
                                        <span class="pull-right">
                                            <a href="javascript:close_window();"><button id="btn_close" class="btn brown" onclick="">Close</button></a>
                                        </span>
                                    </div>
                                <?php }elseif($approval == "1"){ ?>
                                    <div class="alert alert-success">
                                        <strong>Success!</strong> Approve QC Berhasil
                                    </div>
                                    <div class="form-actions">
                                        <span class="pull-right">
                                            <a href="javascript:close_window();"><button id="btn_close" class="btn brown" onclick="">Close</button></a>
                                        </span>
                                    </div>
                                <?php }elseif($approval == "0"){ ?>
                                    <div class="alert alert-error">
                                        Status tidak di Station B - QC
                                    </div>
                                    <div class="form-actions">
                                        <span class="pull-right">
                                            <a href="javascript:close_window();"><button id="btn_close" class="btn brown" onclick="">Close</button></a>
                                        </span>
                                    </div>
                                <?php }elseif($approval == "-2"){ ?>
                                    <div class="alert alert-error">
                                        Panel belum di-scan di Station B - QC
                                    </div>
                                    <div class="form-actions">
                                        <span class="pull-right">
                                            <a href="javascript:close_window();"><button id="btn_close" class="btn brown" onclick="">Close</button></a>
                                        </span>
                                    </div>
                                <?php }else{ ?>
                                    <form action="<?php echo site_url('transaction/approve_qc'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                                    <label class="control-label">Reason</label>
                                                    <div class="controls">
                                                        <select id="reason" name="reason" class="span4 select2 select2_sample1">
                                                            <option></option>
                                                            <?php
                                                                foreach($reason as $row){ ?>
                                                                    <option value="<?php echo $row['value'] ?>"><?php echo $row['value'] ?></option>
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
                                                    <label class="control-label">Remark</label>
                                                    <div class="controls">
                                                        <input id="remark" class="span4" name="remark" type="text" maxlength="200">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <span class="pull-right">
                                                <input type="hidden" id="production_order" name="production_order" value="<?php echo $production_order ?>">
                                                <input type="hidden" id="batch" name="batch" value="<?php echo $batch ?>">
                                                <input type="hidden" id="type" name="type" value="<?php echo urldecode($type) ?>">
                                                <input type="hidden" id="line_item" name="line_item" value="<?php echo $line_item ?>">
                                                <button type="submit" class="btn green">Approve</button>
                                                <a href="javascript:reject_approval();"><input id="btn_reject" type="button" class="btn red" value="Reject"></a>
                                            </span>
                                        </div>
                                    </form>
                                <?php }
                            }else{
                                if($approval == "-1" || $approval == "1"){ ?>
                                    <?php
                                        $msg=$this->session->flashdata('msg');
                                        echo $msg;
                                    ?>
                                    <div class="form-actions">
                                        <span class="pull-right">
                                            <a href="javascript:close_window();"><button id="btn_close" class="btn brown" onclick="">Close</button></a>
                                        </span>
                                    </div>
                                <?php }elseif($approval == "0"){ ?>
                                    <div class="alert alert-error">
                                        Status tidak di Station B - QC
                                    </div>
                                    <?php
                                        $msg=$this->session->flashdata('msg');
                                        echo $msg;
                                    ?>
                                    <div class="form-actions">
                                        <span class="pull-right">
                                            <a href="javascript:close_window();"><button id="btn_close" class="btn brown" onclick="">Close</button></a>
                                        </span>
                                    </div>
                                <?php }elseif($approval == "-2"){ ?>
                                    <div class="alert alert-error">
                                        Panel belum di-scan di Station B - QC
                                    </div>
                                    <?php
                                        $msg=$this->session->flashdata('msg');
                                        echo $msg;
                                    ?>
                                    <div class="form-actions">
                                        <span class="pull-right">
                                            <a href="javascript:close_window();"><button id="btn_close" class="btn brown" onclick="">Close</button></a>
                                        </span>
                                    </div>
                                <?php }else{ ?>
                                    <?php
                                        $msg=$this->session->flashdata('msg');
                                        echo $msg;
                                    ?>
                                    <form action="<?php echo site_url('transaction/save_approval'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
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
                                                    <label class="control-label">Qty Confirm</label>
                                                    <div class="controls">
                                                        <input id="qty_confirm" name="qty_confirm" type="number" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="control-group">
                                                    <label class="control-label">Qty Reject</label>
                                                    <div class="controls">
                                                        <input id="qty_reject" name="qty_reject" type="number" min="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span12">
                                                 <div class="control-group">
                                                    <label class="control-label">Reason</label>
                                                    <div class="controls">
                                                        <select id="reason" name="reason" class="span4 select2 select2_sample1">
                                                            <option></option>
                                                            <?php
                                                                foreach($reason as $row){ ?>
                                                                    <option value="<?php echo $row['value'] ?>"><?php echo $row['value'] ?></option>
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
                                                    <label class="control-label">Remark</label>
                                                    <div class="controls">
                                                        <input id="remark" class="span4" name="remark" type="text" maxlength="200">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <span class="pull-right">
                                                <input type="hidden" id="production_order" name="production_order" value="<?php echo $production_order ?>">
                                                <input type="hidden" id="batch" name="batch" value="<?php echo $batch ?>">
                                                <input type="hidden" id="type" name="type" value="<?php echo urldecode($type) ?>">
                                                <input type="hidden" id="line_item" name="line_item" value="<?php echo $line_item ?>">
                                                <!-- <button type="submit" class="btn green">Approve</button>
                                                <a href="javascript:reject_approval();"><input id="btn_reject" type="button" class="btn red" value="Reject"></a> -->
                                                <button type="submit" class="btn brown"><i class="icon-ok"></i> Submit</button>
                                            </span>
                                        </div>
                                    </form> 
                                <?php }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <iframe style="height: 800px;" width="100%" src="<?php echo base_url() ?>qc_document/<?php echo $production_order ?>/<?php echo $production_order ?>_<?php echo $component_no ?>.pdf" title="pdf"></iframe>
            </div>
        </div>
    </div>
</div>