<?php
    $urlAddItem = "transaction/add_item/".$production_order;
?>
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
                        <div class="caption"><i class="icon-tasks"></i> Upload Data Non Panel</div>
                        <div class="actions">
                            <!-- <a href="<?php echo site_url($urlAddItem); ?>" class="btn brown"><i class="icon-pencil"></i> Edit Header</a> -->
                        </div>
                    </div>
                    <div class="portlet-body table-bottom" style="">
                        <table class="table table-striped table-advance table-bottom table-hover" id="sample_3">
                            <thead>
                                <tr class="bg-brown">
                                    <th>Prod. Order</th>
                                    <th>Workshop</th>
                                    <th>No Box</th>
                                    <th>Kode Packing</th>
                                    <th>Kode Grouping</th>
                                    <th>Batch</th>
                                    <th>Material</th>
                                    <th>Deskripsi</th>
                                    <th>QTY</th>
                                    <th>UOM</th>
                                    <th>Qty Pack</th>
                                    <th>Pesan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($production_order)){
                                        $no=1;
                                        $error = 0;
                                        foreach($production_order as $row){ 
                                            $id = $row['temp_upload_id'];
                                            if($row['message'] <> "OK"){ 
                                                $error++;
                                                ?>
                                                <tr>
                                                    <td style="color: red;"><?php echo $row['production_order'] ?></td>
                                                    <td style="color: red;"><?php echo $row['workshop'] ?></td>
                                                    <td style="color: red;"><?php echo $row['box_no'] ?></td>
                                                    <td style="color: red;"><?php echo $row['packing_code'] ?></td>
                                                    <td style="color: red;"><?php echo $row['grouping_code'] ?></td>
                                                    <td style="color: red;"><?php echo $row['batch'] ?></td>
                                                    <td style="color: red;"><?php echo $row['material'] ?></td>
                                                    <td style="width: 200px; color: red;"><?php echo $row['description'] ?></td>
                                                    <td style="color: red;"><?php echo $row['quantity'] ?></td>
                                                    <td style="color: red;"><?php echo $row['uom'] ?></td>
                                                    <td style="color: red;"><?php echo $row['qty_pack'] ?></td>
                                                    <td style="color: red;"><?php echo $row['message'] ?></td>
                                                    <td><i class="icon-trash" data-toggle="modal" data-target="#deleteModal-<?php echo $id ?>" style="color: black; cursor: pointer;"></i></td>

                                                    <div class="modal fade" id="deleteModal-<?php echo $id ?>" role="dialog">
                                                        <div class="modal-dialog">
                                                            <form action="<?php echo site_url('transaction/delete_temp_non_panel'); ?>" method="post" style="margin-bottom: 0px;">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-brown">
                                                                      <h3 class="modal-title">Delete Item</h3>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Are you sure want to delete item?</b></p>
                                                                        <input type="hidden" name="id" value="<?php echo $id ?>">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                      <button type="submit" class="btn brown" style="margin-top: 3px;">Delete</button>
                                                                      <button class="btn btn-default" data-dismiss="modal" style="margin-top: 3px;">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <td style=""><?php echo $row['production_order'] ?></td>
                                                    <td style=""><?php echo $row['workshop'] ?></td>
                                                    <td style=""><?php echo $row['box_no'] ?></td>
                                                    <td style=""><?php echo $row['packing_code'] ?></td>
                                                    <td style=""><?php echo $row['grouping_code'] ?></td>
                                                    <td style=""><?php echo $row['batch'] ?></td>
                                                    <td style=""><?php echo $row['material'] ?></td>
                                                    <td style="width: 200px;"><?php echo $row['description'] ?></td>
                                                    <td style=""><?php echo $row['quantity'] ?></td>
                                                    <td style=""><?php echo $row['uom'] ?></td>
                                                    <td style="color: red;"><?php echo $row['qty_pack'] ?></td>
                                                    <td style=""><?php echo $row['message'] ?></td>
                                                    <td><i class="icon-trash" data-toggle="modal" data-target="#deleteModal-<?php echo $id ?>" style="color: black; cursor: pointer;"></i></td>

                                                    <div class="modal fade" id="deleteModal-<?php echo $id ?>" role="dialog">
                                                        <div class="modal-dialog">
                                                            <form action="<?php echo site_url('transaction/delete_temp_non_panel'); ?>" method="post" style="margin-bottom: 0px;">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-brown">
                                                                      <h3 class="modal-title">Delete Item</h3>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Are you sure want to delete item?</b></p>
                                                                        <input type="hidden" name="id" value="<?php echo $id ?>">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                      <button type="submit" class="btn brown" style="margin-top: 3px;">Delete</button>
                                                                      <button class="btn btn-default" data-dismiss="modal" style="margin-top: 3px;">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </tr>
                                            <?php }

                                            ?>
                    				        
                                        <?php }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pull-right">
                        <br/>
                        <?php
                            if($error == 0){ ?>
                                <a href="<?php echo site_url('transaction/input_temp_non_panel'); ?>"><button class="btn brown"> Continue</button></a>
                            <?php }
                        ?>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
</div>