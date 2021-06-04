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
                        <div class="caption"><i class="icon-tasks"></i> Add Item</div>
                    </div>
                    <div class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('transaction/add_item_data'); ?>" id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
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
                                        <label class="control-label">Line Description</label>
                                        <div class="controls">
                                            <input type="text" name="line_description" class="span4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Purchase Order</label>
                                        <div class="controls">
                                            <input type="text" name="po_vmk" class="span4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">PIC P</label>
                                        <div class="controls">
                                           <select id="pic_p" name="pic_p" class="span4 select2 select2_sample1" required="true">
                                                <option></option>
                                            <?php
                                                foreach($karyawan as $row){ ?>
                                                    <option value="<?php echo $row['NIK'] ?> - <?php echo $row['NAMA'] ?>"><?php echo $row['NIK'] ?> - <?php echo $row['NAMA'] ?></option>
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
                                        <label class="control-label">PIC W</label>
                                        <div class="controls">
                                           <select id="pic_w" name="pic_w" class="span4 select2 select2_sample1" required="true">
                                                <option></option>
                                            <?php
                                                foreach($karyawan as $row){ ?>
                                                    <option value="<?php echo $row['NIK'] ?> - <?php echo $row['NAMA'] ?>"><?php echo $row['NIK'] ?> - <?php echo $row['NAMA'] ?></option>
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
                                        <label class="control-label">PIC Install</label>
                                        <div class="controls">
                                           <select id="pic_install" name="pic_install" class="span4 select2 select2_sample1" required="true">
                                                <option></option>
                                            <?php
                                                foreach($karyawan as $row){ ?>
                                                    <option value="<?php echo $row['NIK'] ?> - <?php echo $row['NAMA'] ?>"><?php echo $row['NIK'] ?> - <?php echo $row['NAMA'] ?></option>
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
                                        <label class="control-label">Ukuran</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span1" name="panjang" id="panjang" type="text" placeholder="P" required="true" />
                                            <input class="m-wrap m-ctrl-medium span1" name="lebar" id="lebar" type="text" placeholder="L" required="true" />
                                            <input class="m-wrap m-ctrl-medium span1" name="tinggi" id="tinggi" type="text" placeholder="T" required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Kode</label>
                                        <div class="controls">
                                            <input type="text" name="kode" class="span4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Keterangan Kode</label>
                                        <div class="controls">
                                            <input type="text" name="keterangan_kode" class="span4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Lantai</label>
                                        <div class="controls">
                                            <input type="text" name="lantai" class="span4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Quantity</label>
                                        <div class="controls">
                                            <input type="text" name="quantity" class="span4" required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Tanggal Dist. ke Produksi</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl1" id="tgl1" type="text" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">PIC</label>
                                        <div class="controls">
                                           <select id="pic" name="pic" class="span4 select2 select2_sample1" required="true">
                                                <option></option>
                                            <?php
                                                foreach($karyawan as $row){ ?>
                                                    <option value="<?php echo $row['NIK'] ?> - <?php echo $row['NAMA'] ?>"><?php echo $row['NIK'] ?> - <?php echo $row['NAMA'] ?></option>
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
                                        <label class="control-label">Posisi</label>
                                        <div class="controls">
                                            <select id="posisi" name="posisi" class="span4 select2 select2_sample1" required="true">
                                            <?php
                                                foreach($process as $row){
                                                    if($row['production_process_id'] == 1){ ?>
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
                                        <label class="control-label">Keterangan</label>
                                        <div class="controls">
                                            <input type="text" name="keterangan" class="span4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Detail Schedule</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl2" id="tgl2" type="text" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Target Tanggal PPIC</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl_ppic" id="tgl_ppic" type="text" required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Target Tanggal Pembahanan</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl_pembahanan" id="tgl_pembahanan" type="text" required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Target Tanggal Perakitan</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl_perakitan" id="tgl_perakitan" type="text" required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Subkon Perakitan</label>
                                        <div class="controls">
                                            <input type="text" name="subkon_perakitan" class="span4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Target Tanggal Finishing</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl_finishing" id="tgl_finishing" type="text" required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Subkon Finishing</label>
                                        <div class="controls">
                                            <input type="text" name="subkon_finishing" class="span4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Target Tanggal Finish Good</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl_finishgood" id="tgl_finishgood" type="text" required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label class="control-label">Target Tanggal Pengiriman</label>
                                        <div class="controls">
                                            <input class="m-wrap m-ctrl-medium span2" name="tgl_pengiriman" id="tgl_pengiriman" type="text" required="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="line_item" class="span4" value="<?php echo $line_item_no ?>">
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