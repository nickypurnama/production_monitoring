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
                        <div class="caption"><i class="icon-tasks"></i> Report</div>
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('report/report_result'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                            <select id="production_order1" name="production_order1" class="span2 select2 select2_sample1">
                                                <option></option>
                                                <?php
                                                    foreach($list_production_order as $row){ ?>
                                                        <option value="<?php echo $row['production_order'] ?>"><?php echo $row['production_order'] ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                            <select id="production_order2" name="production_order2" class="span2 select2 select2_sample1">
                                                <option></option>
                                                <?php
                                                    foreach($list_production_order as $row){ ?>
                                                        <option value="<?php echo $row['production_order'] ?>"><?php echo $row['production_order'] ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                            <select name="production_order3[]" class="span4 select2_sample1" multiple="multiple" placeholder="Multiple Production Order">
                                                <?php
                                                    foreach($list_production_order as $row){ ?>
                                                            <option  value="<?php echo $row['production_order'] ?>"><?php echo $row['production_order'] ?></option>
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Project Definition</label>
                                        <div class="controls">
                                            <select id="project_definition1" name="project_definition1" class="span2 select2 select2_sample1">
                                                <option></option>
                                                <?php
                                                    foreach($list_project as $row){ ?>
                                                        <option value="<?php echo $row['project_definition'] ?>"><?php echo $row['project_definition'] ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                            <select id="project_definition2" name="project_definition2" class="span2 select2 select2_sample1">
                                                <option></option>
                                                <?php
                                                    foreach($list_project as $row){ ?>
                                                        <option value="<?php echo $row['project_definition'] ?>"><?php echo $row['project_definition'] ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                            <select name="project_definition3[]" class="span4 select2_sample1" multiple="multiple" placeholder="Multiple Project Definition">
                                                <?php
                                                    foreach($list_project as $row){ ?>
                                                            <option  value="<?php echo $row['project_definition'] ?>"><?php echo $row['project_definition'] ?></option>
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Sales Order</label>
                                        <div class="controls">
                                            <select id="sales_order1" name="sales_order1" class="span2 select2 select2_sample1">
                                                <option></option>
                                                <?php
                                                    foreach($list_sales_order as $row){ ?>
                                                        <option value="<?php echo $row['sales_order'] ?>"><?php echo $row['sales_order'] ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                            <select id="sales_order2" name="sales_order2" class="span2 select2 select2_sample1">
                                                <option></option>
                                                <?php
                                                    foreach($list_sales_order as $row){ ?>
                                                        <option value="<?php echo $row['sales_order'] ?>"><?php echo $row['sales_order'] ?></option>
                                                    <?php }
                                                ?>
                                            </select>
                                            <select name="sales_order3[]" class="span4 select2_sample1" multiple="multiple" placeholder="Multiple Production Order">
                                                <?php
                                                    foreach($list_sales_order as $row){ ?>
                                                            <option  value="<?php echo $row['sales_order'] ?>"><?php echo $row['sales_order'] ?></option>
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Tanggal Basic</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl1" id="tgl1" type="text" readonly="true" />
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl2" id="tgl2" type="text" readonly="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Purchase Order</label>
                                        <div class="controls">
                                            <select name="po_vmk[]" class="span4 select2_sample1" multiple="multiple" placeholder="Multiple Purchase Order">
                                                <?php
                                                    foreach($list_po_vmk as $row){ ?>
                                                            <option  value="<?php echo $row['po_vmk'] ?>"><?php echo $row['po_vmk'] ?></option>
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">SO VMK</label>
                                        <div class="controls">
                                            <select name="so_vmk[]" class="span4 select2_sample1" multiple="multiple" placeholder="Multiple SO VMK">
                                                <?php
                                                    foreach($list_so_vmk as $row){ ?>
                                                            <option  value="<?php echo $row['so_vmk'] ?>"><?php echo $row['so_vmk'] ?></option>
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <span class="pull-right">
                                    <input type="hidden" id="plant" name="plant" value="<?=$plant?>" >
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