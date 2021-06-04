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
                        <div class="caption"><i class="icon-tasks"></i> Report Plant</div>
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('report/index'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="alert alert-error hide">
                                <button class="close" data-dismiss="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-success hide">
                                <button class="close" data-dismiss="alert"></button>
                                Your form validation is successful!
                            </div>
                            <div class="row-fluid">
                                <div class="span4">
                                     <div class="control-group">
                                        <label class="control-label">Plant</label>
                                        <div class="controls">
                                             <select id="plant" name="plant" class="span10 select2 select2_sample1" required="true">
                                                <?php
                                                    if(!empty($user_plant)){ ?>
                                                        <option value="<?php echo $user_plant ?>" selected><?php echo $user_plant ?></option>
                                                    <?php } else { ?>
                                                        <option></option>
                                                        <?php
                                                            foreach($plant as $row){ ?>
                                                                <option value="<?php echo $row['plant'] ?>"><?php echo $row['plant'] ?></option>
                                                        <?php }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span4">
                                     <div class="control-group">
                                        <label class="control-label">Type</label>
                                        <div class="controls">
                                             <select id="type" name="type" class="span10 select2 select2_sample1" required="true">
                                                <option></option>
                                                <option value="web">Web</option>
                                                <option value="excel">Excel</option>
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
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>