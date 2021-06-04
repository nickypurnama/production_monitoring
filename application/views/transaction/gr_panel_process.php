<!-- BEGIN PAGE -->
<script type="text/javascript">
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
                        <div class="caption"><i class="icon-tasks"></i> GR Panel Process</div>
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('transaction/post_gr_panel'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                        <label class="control-label">Production Order</label>
                                        <div class="controls">
                                            <input type="text" name="production_order" class="span4" value="<?=$header['production_order']?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Plant</label>
                                        <div class="controls">
                                            <input type="text" name="plant" class="span4" value="<?=$header['plant']?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Material</label>
                                        <div class="controls">
                                            <input type="text" name="material" class="span4" value="<?=$header['material_description']?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Order Type</label>
                                        <div class="controls">
                                            <input type="text" name="order_type" class="span4" value="<?=$header['order_type']?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Project Definition</label>
                                        <div class="controls">
                                            <input type="text" name="project" class="span4" value="<?=$header['project_definition']?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Project Description</label>
                                        <div class="controls">
                                            <input type="text" name="project_description" class="span4" value="<?=$header['project_description']?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Quantity</label>
                                        <div class="controls">
                                            <input type="text" name="quantity_gr" class="span4" value="<?=$header['quantity']?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">SLOC</label>
                                        <div class="controls">
                                           <select id="sloc" name="sloc" class="span4 select2 select2_sample1" required="true">
                                                <option></option>
                                            <?php
                                                foreach($sloc as $row){ ?>
                                                    <option value="<?php echo $row['sloc'] ?>"><?php echo $row['sloc'] ?> - <?php echo $row['sloc_desctiption'] ?></option>
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
                                        <label class="control-label">GR Date</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl1" id="tgl1" type="text" required="true" autocomplete="off" value="<?php echo $date ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <span class="pull-right">
                                    <input type="hidden" name="material_code" value="<?=$header['material_code']?>">
                                    <button type="submit" class="btn brown"><i class="icon-ok"></i> Submit</button>
                                </span>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>