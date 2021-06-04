BEGIN PAGE -->
<script type="text/javascript">
function hapus(xid){
    jConfirm("Delete Data?", "Vivere", function(r) {
        if(r == true){
            $.ajax({
            type: 'POST', 
            url: "<?php echo site_url('master/user_role_delete'); ?>",
            data: {
                id : xid
            }, 
            cache: false,
            success: function(msg){
                <?php
                        $urldel="master/user_role";
                ?>
                document.location='<?php echo site_url($urldel); ?>';
            }
            }); 
        }
    });
}
</script>
<div class="page-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span5">
                <br>&nbsp;<br>&nbsp;
                <?php
                    $msg=$this->session->flashdata('msg');
                    echo $msg;
                ?>
                <!-- BEGIN PAGE CONTENT-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-tasks"></i> Add New Role</div>
                        
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('master/add_role'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                        <label class="control-label">NIK</label>
                                        <div class="controls">
                                            <select id="nik" name="nik" class="span10 select2 select2_sample1">
                                                <option value=""></option>
                                                <?php
                                                    foreach($nik_sso as $p){ ?>
                                                        <option value="<?php echo $p['NIK'] ?>"><?php echo $p['NIK'] ?> - <?php echo $p['NAMA'] ?></option>
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
                                        <label class="control-label">Role</label>
                                        <div class="controls">
                                             <select id="role" name="role" class="span10 select2 select2_sample1">
                                                <option></option>
                                                <?php
                                                    foreach($role as $row){ ?>
                                                        <option value="<?php echo $row['role_id'] ?>"><?php echo $row['role_name'] ?></option>
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
                                        <label class="control-label">Process</label>
                                        <div class="controls">
                                             <select id="process" name="process" class="span10 select2 select2_sample1">
                                                <option></option>
                                                <?php
                                                    foreach($process as $row){ ?>
                                                        <option value="<?php echo $row['production_process_id'] ?>"><?php echo $row['process'] ?></option>
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
                                        <label class="control-label">Plant</label>
                                        <div class="controls">
                                             <select id="plant" name="plant" class="span10 select2 select2_sample1">
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
                            <div class="form-actions">
                                <span class="pull-right">
                                    <button type="submit" class="btn brown"><i class="icon-ok"></i> Submit</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="span7">
                <br>&nbsp;<br>&nbsp;
                
                <!-- BEGIN PAGE CONTENT-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-tasks"></i> User Role</div>
                        
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <table class="table table-striped table-advance table-bottom table-hover" id="sample_3">
                            <thead>
                                <tr class="bg-brown">
                                    <th style='width:5%;'> No.</th>
                                    <th> NIK</th>
                                    <th> Name</th>
                                    <th> Role</th>
                                    <th> Process</th>
                                    <th> Plant</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($list)){
                                        $no=1;
                                        foreach($list as $row){
                                            $id=$row['user_role_id'];
                                            
                                            echo "<tr >";
                                            echo "<td  style='width:5%;'>".$no."</td>";
                                            echo "<td  style='width:15%;'>".$row['nik']."</td>";
                                            echo "<td class='hidden-480'>".$row['name']."</td>";
                                            echo "<td class='hidden-480'>".$row['role_name']."</td>";
                                            echo "<td class='hidden-480'>".$row['process_name']."</td>";
                                            echo "<td class='hidden-480'>".$row['plant']."</td>";
                                            echo "<td style='width:7%; text-align:center;'><a href='javascript:hapus(\"$id\")' class='btn mini red'>Delete</a></td>";
                                            echo "</tr>";
                                            $no++;
                                        }
                                    }
                                ?>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
    </div>
    <!-- END PAGE CONTAINER--> 
</div>
<!-- END PAGE -->    
</div>
<!-- END CONTAINER