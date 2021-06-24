<?php
    $urlAddItem = "transaction/add_item/".$production_order;
    $urlAddPanel = "transaction/add_panel/".$production_order;
    $urlAddNonPanel = "transaction/add_non_panel/".$production_order;
    $urlEditItem = "transaction/edit_header/".$production_order;
    $urlSync = "transaction/sync_header/".$production_order;
?>
<!-- BEGIN PAGE -->
<script type="text/javascript">
</script>
<style type="text/css">
    #sample_3 td, #sample_2 td{
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
                        <div class="caption"><i class="icon-tasks"></i> Production Order</div>
                        <div class="actions">
                            <a href="<?php echo site_url($urlEditItem); ?>" class="btn green"><i class="icon-pencil"></i> Edit Header</a>
                            <a href="<?php echo site_url($urlSync); ?>" class="btn blue"><i class="icon-refresh"></i> Sync SAP</a>
                            <a class="btn red" data-toggle="modal" data-target="#deleteModal"><i class="icon-trash"></i> Delete Header</a>
                            <div class="modal fade" id="deleteModal" role="dialog">
                                <div class="modal-dialog">
                                    <form action="<?php echo site_url('transaction/delete_header'); ?>" method="post" style="margin-bottom: 0px;">
                                        <div class="modal-content">
                                            <div class="modal-header bg-brown">
                                              <h3 class="modal-title">Delete Header</h3>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure want to delete header?</b></p>
                                                <input type="hidden" name="production_order" value="<?php echo $production_order ?>">
                                            </div>
                                            <div class="modal-footer">
                                              <button type="submit" class="btn brown" style="margin-top: 3px;">Delete</button>
                                              <button class="btn btn-default" data-dismiss="modal" style="margin-top: 3px;">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="span4">
                            <table>
                                <tr>
                                    <td>WBS</td>
                                    <td>:</td>
                                    <td><?=$project_definition?></td>
                                </tr>
                                <tr>
                                    <td>Project Description</td>
                                    <td>:</td>
                                    <td><?=$project_description?></td>
                                </tr>
                                <tr>
                                    <td>Production Order</td>
                                    <td>:</td>
                                    <td><?=$production_order?></td>
                                </tr>
                                <tr>
                                    <td>Order Description</td>
                                    <td>:</td>
                                    <td><?=$order_description?></td>
                                </tr>
                            </table>
                            <br>
                        </div>
                        <div class="span4">
                            <table>
                                <tr>
                                    <td>Total Quantity</td>
                                    <td>:</td>
                                    <td><?=$quantity." ".$uom?></td>
                                </tr>
                                <tr>
                                    <td>Material Code</td>
                                    <td>:</td>
                                    <td><?=$material_code?></td>
                                </tr>
                                <tr>
                                    <td>Material Description</td>
                                    <td>:</td>
                                    <td><?=$material_description?></td>
                                </tr>
                            </table>
                            <br>
                        </div>
                        <div class="span4">
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                        </div>
                    </div>
                    <div class="pull-right" style="margin-bottom: 10px;">
                        <a href="<?php echo site_url($urlAddItem); ?>" class="btn brown"><i class="icon-plus"></i> Add Item</a>
                        <a href="<?php echo site_url($urlAddPanel); ?>" class="btn brown"><i class="icon-plus"></i> Add Panel</a>
                    </div>
                    
                    <div class="portlet-body" style="display: block;">
                        <table class="table table-striped table-advance table-bottom table-hover" id="sample_3">
                            <thead>
                                <tr class="bg-brown">
                                    <th rowspan="2" style="width: 50px;">Line Item</th>
                                    <th rowspan="2" style="width: 50px;">Produk</th>
                                    <th rowspan="2" style="width: 50px;">No Komponen</th>
                                    <th rowspan="2" style="width: 50px;">Nama Komponen</th>
                                    <th rowspan="2">Line Description</th>
                                    <th colspan="3">Ukuran</th>
                                    <th colspan="2">Kode</th>
                                    <th rowspan="2">LT</th>
                                    <th rowspan="2">QTY</th>
                                    <th rowspan="2">QTY Pack</th>
                                    <th rowspan="2">Keterangan</th>
                                    <th rowspan="2">Posisi</th>
                                    <th colspan="8">Target Produksi</th>
                                    <th rowspan="2"></th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr class="bg-brown">
                                    <th>P</th>
                                    <th>L</th>
                                    <th>T</th>
                                    <th>Kode</th>
                                    <th>Ket. Kode</th>
                                    <th>PPIC</th>
                                    <th>Pembahanan</th>
                                    <th>Perakitan</th>
                                    <th>Subkon Perakitan</th>
                                    <th>Finishing</th>
                                    <th>Subkon Finishing</th>
                                    <th>Finish Good</th>
                                    <th>Pengiriman</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($line_item)){
                                        $no=1;
                                        foreach($line_item as $row){ 
                                            $id = $row['production_order_item_id'];
                                            $urlEditItem = "transaction/edit_item/".$production_order."/".$row['line_item'];
                                            ?>
                                            <tr>
                                                <td style="width: 50px;"><?php echo $row['line_item'] ?></td>
                                                <td style="width: 50px;"><?php echo $product ?></td>
                                                <td style="width: 50px;"><?php echo $row['component_no'] ?></td>
                                                <td style="width: 50px;"><?php echo $row['component_name'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['line_description'] ?></td>
                                                <td><?php echo $row['length'] ?></td>
                                                <td><?php echo $row['width'] ?></td>
                                                <td><?php echo $row['height'] ?></td>
                                                <td><?php echo $row['code'] ?></td>
                                                <td><?php echo $row['code_information'] ?></td>
                                                <td><?php echo $row['floor'] ?></td>
                                                <td><?php echo $row['quantity'] ?></td>
                                                <td><?php echo $row['qty_pack'] ?></td>
                                                <td><?php echo $row['information'] ?></td>
                                                <td><?php echo $row['process'] ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_ppic']) ? date('d-m-Y', strtotime($row['date_target_ppic'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_pembahanan']) ? date('d-m-Y', strtotime($row['date_target_pembahanan'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_perakitan']) ? date('d-m-Y', strtotime($row['date_target_perakitan'])) : "") ?></td>
                                                <td><?php echo $row['subcont_perakitan'] ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_finishing']) ? date('d-m-Y', strtotime($row['date_target_finishing'])) : "") ?></td>
                                                <td><?php echo $row['subcont_finishing'] ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_finish_good']) ? date('d-m-Y', strtotime($row['date_target_finish_good'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_pengiriman']) ? date('d-m-Y', strtotime($row['date_target_pengiriman'])) : "") ?></td>
                                                <td><a class="btn mini green" href="<?php echo site_url($urlEditItem); ?>">Edit</a></td>
                                                <td><a class="btn mini red" data-toggle="modal" data-target="#deleteModal-<?php echo $id ?>">Delete</a></td>

                                                <div class="modal fade" id="deleteModal-<?php echo $id ?>" role="dialog">
                                                    <div class="modal-dialog">
                                                        <form action="<?php echo site_url('transaction/delete_item'); ?>" method="post" style="margin-bottom: 0px;">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-brown">
                                                                  <h3 class="modal-title">Delete Item</h3>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure want to delete item?</b></p>
                                                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                                                    <input type="hidden" name="production_order" value="<?php echo $production_order ?>">
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
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            
            <div class="span12">
                <!-- BEGIN PAGE CONTENT-->
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption"><i class="icon-tasks"></i> Non Panel</div>
                    </div>
                    <div class="pull-right" style="margin-bottom: 10px;">
                        <a href="<?php echo site_url($urlAddNonPanel); ?>" class="btn brown"><i class="icon-plus"></i> Add Non Panel</a>
                    </div>
                    
                    <div class="portlet-body" style="display: block;">
                        <table class="table table-striped table-advance table-bottom table-hover" id="sample_2">
                            <thead>
                                <tr class="bg-brown">
                                    <th>Line Item</th>
                                    <th>Workshop</th>
                                    <th>No Box</th>
                                    <th>Kode Packing</th>
                                    <th>Kode Grouping</th>
                                    <th>Batch</th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>QTY</th>
                                    <th>QTY PACK</th>
                                    <th>UOM</th>
                                    <th>Posisi</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($non_panel)){
                                        $no=1;
                                        foreach($non_panel as $row){ 
                                            $id = $row['production_order_item_id'];
                                            $urlEditItem = "transaction/edit_non_panel/".$production_order."/".$row['line_item'];
                                            ?>
                                            <tr>
                                                <td><?php echo $row['line_item'] ?></td>
                                                <td><?php echo $row['workshop'] ?></td>
                                                <td><?php echo $row['box_no'] ?></td>
                                                <td><?php echo $row['packing_code'] ?></td>
                                                <td><?php echo $row['grouping_code'] ?></td>
                                                <td><?php echo $row['batch'] ?></td>
                                                <td><?php echo $row['material'] ?></td>
                                                <td><?php echo $row['description'] ?></td>
                                                <td><?php echo $row['quantity'] ?></td>
                                                <td><?php echo $row['qty_pack'] ?></td>
                                                <td><?php echo $row['uom'] ?></td>
                                                <td><?php echo $row['process'] ?></td>
                                                <td><a class="btn mini green" href="<?php echo site_url($urlEditItem); ?>">Edit</a></td>
                                                <td><a class="btn mini red" data-toggle="modal" data-target="#deleteModal-<?php echo $id ?>">Delete</a></td>

                                                <div class="modal fade" id="deleteModal-<?php echo $id ?>" role="dialog">
                                                    <div class="modal-dialog">
                                                        <form action="<?php echo site_url('transaction/delete_item'); ?>" method="post" style="margin-bottom: 0px;">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-brown">
                                                                  <h3 class="modal-title">Delete Item</h3>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure want to delete item?</b></p>
                                                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                                                    <input type="hidden" name="production_order" value="<?php echo $production_order ?>">
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
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>