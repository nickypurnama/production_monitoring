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
                        <div class="caption"><i class="icon-tasks"></i> Edit Item</div>
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('transaction/edit_item_data'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                            <input type="text" name="production_order" class="span4" value="<?=$production_order?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Line Item</label>
                                        <div class="controls">
                                            <input type="text" name="line_item" class="span4" value="<?php echo $item['line_item'] ?>" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Line Description</label>
                                        <div class="controls">
                                            <input type="text" name="line_description" class="span4" value="<?php echo $item['line_description'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Workshop</label>
                                        <div class="controls">
                                            <input type="text" name="workshop" class="span4" value="<?php echo $item['workshop'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">No Box</label>
                                        <div class="controls">
                                            <input type="text" name="box_no" class="span4" value="<?php echo $item['box_no'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Kode Packing</label>
                                        <div class="controls">
                                            <input type="text" name="packing_code" class="span4" value="<?php echo $item['packing_code'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Kode Grouping</label>
                                        <div class="controls">
                                            <input type="text" name="grouping_code" class="span4" value="<?php echo $item['grouping_code'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Modul</label>
                                        <div class="controls">
                                            <input type="text" name="module" class="span4" value="<?php echo $item['module'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Batch</label>
                                        <div class="controls">
                                            <input type="text" name="batch" class="span4" value="<?php echo $item['batch'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Material</label>
                                        <div class="controls">
                                            <input type="text" name="material" class="span4" value="<?php echo $item['material'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Weight</label>
                                        <div class="controls">
                                            <input type="text" name="weight" class="span4" value="<?php echo $item['weight'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Produk</label>
                                        <div class="controls">
                                            <input type="text" name="product" class="span4" value="<?php echo $product ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">No Komponen</label>
                                        <div class="controls">
                                            <input type="text" name="component_no" class="span4" value="<?php echo $item['component_no'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Nama Komponen</label>
                                        <div class="controls">
                                            <input type="text" name="component_name" class="span4" value="<?php echo $item['component_name'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Purchase Order</label>
                                        <div class="controls">
                                            <input type="text" name="po_vmk" class="span4" value="<?php echo $item['po_vmk'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Ukuran</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span1" name="panjang" id="panjang" type="text" placeholder="P" value="<?php echo $item['length'] ?>" required="true" />
                                            <input class="m-wrap m-ctrl-medium span1" name="lebar" id="lebar" type="text" placeholder="L" value="<?php echo $item['width'] ?>" required="true" />
                                            <input class="m-wrap m-ctrl-medium span1" name="tinggi" id="tinggi" type="text" placeholder="T" value="<?php echo $item['height'] ?>" required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Kode</label>
                                        <div class="controls">
                                            <input type="text" name="kode" class="span4" value="<?php echo $item['code'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Lantai</label>
                                        <div class="controls">
                                            <input type="text" name="lantai" class="span4" value="<?php echo $item['floor'] ?>" required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Quantity</label>
                                        <div class="controls">
                                            <input type="text" name="quantity" class="span4" value="<?php echo $item['quantity'] ?>" required="true" readonly="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Qty Pack</label>
                                        <div class="controls">
                                            <input type="number" name="qty_pack" class="span4" value="<?php echo $item['qty_pack'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Posisi</label>
                                        <div class="controls">
                                            <select id="posisi" name="posisi" class="span4 select2 select2_sample1" required="true">
                                                <option></option>
                                                <?php
                                                    foreach($process as $row){ 
                                                        if($row['production_process_id'] == $item['position']){ ?>
                                                            <option value="<?php echo $row['production_process_id'] ?>" selected><?php echo $row['process'] ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $row['production_process_id'] ?>"><?php echo $row['process'] ?></option>
                                                        <?php }    
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
                                        <label class="control-label">Detail Schedule</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl2" id="tgl2" type="text" value="<?php echo (isset($item['detail_schedule']) ? date('d-m-Y', strtotime($item['detail_schedule'])) : "") ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="quantity_old" class="span4" value="<?php echo $item['quantity'] ?>">
                            <input type="hidden" name="posisi_old" class="span4" value="<?php echo $item['position'] ?>">
                            <input type="hidden" name="production_order_item_id" class="span4" value="<?php echo $item['production_order_item_id'] ?>">
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