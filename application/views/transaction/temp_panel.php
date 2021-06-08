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

<div class="page-content" style="width: 180%;">
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
                        <div class="caption"><i class="icon-tasks"></i> Upload Data</div>
                        <div class="actions">
                            <!-- <a href="<?php echo site_url($urlAddItem); ?>" class="btn brown"><i class="icon-pencil"></i> Edit Header</a> -->
                        </div>
                    </div>
                    <div class="portlet-body table-bottom" style="">
                        <table class="table table-striped table-advance table-bottom table-hover" id="sample_3">
                            <thead>
                                <tr class="bg-brown">
                                    <th rowspan="2">Prod. Order</th>
                                    <th rowspan="2">Workshop</th>
                                    <th rowspan="2">No Box</th>
                                    <th rowspan="2">Kode Packing</th>
                                    <th rowspan="2">Kode Grouping</th>
                                    <th rowspan="2">Modul</th>
                                    <th rowspan="2">Batch</th>
                                    <th rowspan="2">Material</th>
                                    <th rowspan="2">Weight</th>
                                    <th rowspan="2">Produk</th>
                                    <th rowspan="2">No Komponen</th>
                                    <th rowspan="2">Nama Komponen</th>
                                    <th rowspan="2">Order Desc</th>
                                    <th colspan="3">Ukuran</th>
                                    <th rowspan="2">Produk PL</th>
                                    <th rowspan="2">LT</th>
                                    <th rowspan="2">QTY</th>
                                    <th rowspan="2">Qty Pack</th>
                                    <th rowspan="2">Pesan</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr class="bg-brown">
                                    <th>P</th>
                                    <th>L</th>
                                    <th>T</th>
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
                                                    <td style="width: 60px; color: red;"><?php echo $row['production_order'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['workshop'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['box_no'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['packing_code'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['grouping_code'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['module'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['batch'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['material'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['weight'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['product'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['component_no'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['component_name'] ?></td>
                                                    <td style="width: 60px; color: red;"><?php echo $row['order_description'] ?></td>
                                                    <td style="color: red;"><?php echo $row['length'] ?></td>
                                                    <td style="color: red;"><?php echo $row['width'] ?></td>
                                                    <td style="color: red;"><?php echo $row['height'] ?></td>
                                                    <td style="color: red;"><?php echo $row['code'] ?></td>
                                                    <td style="color: red;"><?php echo $row['floor'] ?></td>
                                                    <td style="color: red;"><?php echo $row['quantity'] ?></td>
                                                    <td style="color: red;"><?php echo $row['qty_pack'] ?></td>
                                                    <td style="color: red;"><?php echo $row['message'] ?></td>
                                                    <td><i class="icon-trash" data-toggle="modal" data-target="#deleteModal-<?php echo $id ?>" style="color: black; cursor: pointer;"></i></td>

                                                    <div class="modal fade" id="deleteModal-<?php echo $id ?>" role="dialog">
                                                        <div class="modal-dialog">
                                                            <form action="<?php echo site_url('transaction/delete_temp_panel'); ?>" method="post" style="margin-bottom: 0px;">
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
                                                    <td style="width: 60px;"><?php echo $row['production_order'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['workshop'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['box_no'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['packing_code'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['grouping_code'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['module'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['batch'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['material'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['weight'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['product'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['component_no'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['component_name'] ?></td>
                                                    <td style="width: 60px;"><?php echo $row['order_description'] ?></td>
                                                    <td><?php echo $row['length'] ?></td>
                                                    <td><?php echo $row['width'] ?></td>
                                                    <td><?php echo $row['height'] ?></td>
                                                    <td><?php echo $row['code'] ?></td>
                                                    <td><?php echo $row['floor'] ?></td>
                                                    <td><?php echo $row['quantity'] ?></td>
                                                    <td><?php echo $row['qty_pack'] ?></td>
                                                    <td><?php echo $row['message'] ?></td>
                                                    <td><i class="icon-trash" data-toggle="modal" data-target="#deleteModal-<?php echo $id ?>" style="color: black; cursor: pointer;"></i></td>

                                                    <div class="modal fade" id="deleteModal-<?php echo $id ?>" role="dialog">
                                                        <div class="modal-dialog">
                                                            <form action="<?php echo site_url('transaction/delete_temp_panel'); ?>" method="post" style="margin-bottom: 0px;">
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
                                <a href="<?php echo site_url('transaction/input_temp_panel'); ?>"><button class="btn brown"> Continue</button></a>
                            <?php }
                        ?>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
</div>