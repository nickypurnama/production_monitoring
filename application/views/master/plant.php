<script type="text/javascript">
function hapus(xid){
    jConfirm("Delete Data?", "Vivere", function(r) {
        if(r == true){
            $.ajax({
            type: 'POST', 
            url: "<?php echo site_url('master/plant_delete'); ?>",
            data: {
                plant : xid
            }, 
            cache: false,
            success: function(msg){
                <?php
                        $urldel="master/plant";
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
                        <div class="caption"><i class="icon-tasks"></i> Add New Plant</div>
                        
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('master/add_plant'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                        <label class="control-label">Plant</label>
                                        <div class="controls">
                                            <input type="text" name="plant" class="span4" required="true">
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
                        <div class="caption"><i class="icon-tasks"></i> Plant</div>
                        
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <table class="table table-striped table-advance table-bottom table-hover" id="sample_3">
                            <thead>
                                <tr class="bg-brown">
				                    <th style='width:5%;'> No.</th>
                                    <th> Plant</th>
				                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($list)){
                                        $no=1;
                                        foreach($list as $row){
					                        $plant=$row['plant'];
                                            
                                            echo "<tr >";
					                        echo "<td  style='width:5%; text-align: center;'>".$no."</td>";
                                            echo "<td  style='width:15%; text-align: center;'>".$plant."</td>";
					                        echo "<td style='width:7%; text-align:center;'><a href='javascript:hapus(\"$plant\")' class='btn mini red'>Delete</a></td>";
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