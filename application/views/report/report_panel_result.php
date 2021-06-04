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

<div class="page-content" style="width: 200%;">
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
                        <div class="caption"><i class="icon-tasks"></i> Report Result</div>
                    </div>
                    <div class="portlet-body" style="transform: scale(0.8); transform-origin: 0 0;-moz-transform: scale(0.76);">
                        <table class="table table-striped table-advance table-bottom table-hover" id="sample_3" style="width: 2100px !important;">
                            <thead>
                                <tr class="bg-brown">
                                    <th rowspan="2">Kode Project</th>
                                    <th rowspan="2">Project Desc.</th>
                                    <th rowspan="2">Sales Order</th>
                                    <th rowspan="2">Prod. Order</th>
                                    <th rowspan="2">SO VMK</th>
                                    <th rowspan="2">Workspace</th>
                                    <th rowspan="2">No Box</th>
                                    <th rowspan="2">Kode Packing</th>
                                    <th rowspan="2">Kode Grouping</th>
                                    <th rowspan="2">Modul</th>
                                    <th rowspan="2">Batch</th>
                                    <th rowspan="2">Material</th>
                                    <th rowspan="2">Deskripsi</th>
                                    <th rowspan="2">Weight</th>
                                    <th rowspan="2">No Komponen</th>
                                    <th rowspan="2">Nama Komponen</th>
                                    <th rowspan="2">Purchase Order</th>
                                    <th rowspan="2">Order Desc.</th>
                                    <th colspan="3">Ukuran</th>
                                    <th rowspan="2">Tanggal CRTD</th>
                                    <th rowspan="2">Tanggal REL</th>
                                    <th rowspan="2">Kode</th>
                                    <th rowspan="2">Keterangan Kode</th>
                                    <th rowspan="2">Lantai</th>
                                    <th rowspan="2">Qty</th>
                                    <th rowspan="2">Target Pengiriman</th>
                                    <th rowspan="2">Posisi</th>
                                    <th rowspan="2">Remark</th>
                                    <th colspan="23">Target-Realisasi Produksi</th>
                                    <th rowspan="2">Lead Time Process</th>
                                    <th rowspan="2"></th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr class="bg-brown">
                                    <th>P</th>
                                    <th>L</th>
                                    <th>T</th>
                                    <th>Target PPIC</th>
                                    <th>Realisasi PPIC</th>
                                    <th>Qty</th>
                                    <th>Target Pembahanan</th>
                                    <th>Realisasi Pembahanan</th>
                                    <th>Qty</th>
                                    <th>Mesin</th>
                                    <th>Target Perakitan</th>
                                    <th>Subkon Perakitan</th>
                                    <th>Realisasi Perakitan</th>
                                    <th>Qty</th>
                                    <th>Target Finishing</th>
                                    <th>Subkon Finishing</th>
                                    <th>Realisasi Finishing</th>
                                    <th>Qty</th>
                                    <th>Target Finish Good</th>
                                    <th>Realisasi Finish Good</th>
                                    <th>Qty</th>
                                    <th>Target Pengiriman</th>
                                    <th>Realisasi Pengiriman</th>
                                    <th>Qty</th>
                                    <th>Realisasi Install</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($production_order)){
                                        $no=1;
                                        foreach($production_order as $row){ 
                                            $id = $row['production_order_item_id']; 
                                            $created_date = date_create($row['created_date']);
                                            if($row['process_date'] <> ""){
                                                $process_date = date_create($row['process_date']);
                                                $diff = date_diff($created_date,$process_date);
                                                $lead_time_process = $diff->format("%a hari");
                                            } else {
                                                $lead_time_process = "-";
                                            }
                                            
                                            ?>
                                            <tr>
                                                <td style="width: 60px;"><?php echo $row['project_definition'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['project_description'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['sales_order'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['production_order'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['so_vmk'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['workshop'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['box_no'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['packing_code'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['grouping_code'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['module'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['batch'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['material'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['description'] ?></td>
                                                <td style="width: 60px;"><?php echo (isset($row['weight']) ? $row['weight']." KG" : "") ?></td>
                                                <td style="width: 60px;"><?php echo $row['component_no'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['component_name'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['po_vmk'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['order_description'] ?></td>
                                                <td><?php echo $row['length'] ?></td>
                                                <td><?php echo $row['width'] ?></td>
                                                <td><?php echo $row['height'] ?></td>
                                                <td style="width: 60px;"><?php echo (isset($row['created_date']) ? date('d-m-Y', strtotime($row['created_date'])) : "") ?></td>
                                                <td style="width: 60px;"><?php echo (isset($row['release_date']) ? date('d-m-Y', strtotime($row['release_date'])) : "") ?></td>
                                                <td><?php echo $row['code'] ?></td>
                                                <td><?php echo $row['code_information'] ?></td>
                                                <td><?php echo $row['floor'] ?></td>
                                                <td><?php echo $row['quantity'] ?> <?php echo $row['uom'] ?></td>
                                                <td style="width: 60px;"><?php echo (isset($row['date_target_pengiriman']) ? date('d-m-Y', strtotime($row['date_target_pengiriman'])) : "") ?></td>
                                                <td><?php echo $row['process'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['remark'] ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_ppic']) ? date('d-m-Y', strtotime($row['date_target_ppic'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['ppic']) ? date('d-m-Y', strtotime($row['ppic'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['ppic_qty']) ? $row['ppic_qty']." ".$row['uom'] : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_pembahanan']) ? date('d-m-Y', strtotime($row['date_target_pembahanan'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['pembahanan']) ? date('d-m-Y', strtotime($row['pembahanan'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['pembahanan_qty']) ? $row['pembahanan_qty']." ".$row['uom'] : "") ?></td>
                                                <td style="width: 100px;"><?php echo $row['machine'] ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_perakitan']) ? date('d-m-Y', strtotime($row['date_target_perakitan'])) : "") ?></td>
                                                <td><?php echo $row['subcont_perakitan'] ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['perakitan']) ? date('d-m-Y', strtotime($row['perakitan'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['perakitan_qty']) ? $row['perakitan_qty']." ".$row['uom'] : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_finishing']) ? date('d-m-Y', strtotime($row['date_target_finishing'])) : "") ?></td>
                                                <td><?php echo $row['subcont_finishing'] ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['finishing']) ? date('d-m-Y', strtotime($row['finishing'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['finishing_qty']) ? $row['finishing_qty']." ".$row['uom'] : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_finish_good']) ? date('d-m-Y', strtotime($row['date_target_finish_good'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['finish_good']) ? date('d-m-Y', strtotime($row['finish_good'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['finish_good_qty']) ? $row['finish_good_qty']." ".$row['uom'] : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['date_target_pengiriman']) ? date('d-m-Y', strtotime($row['date_target_pengiriman'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['pengiriman']) ? date('d-m-Y', strtotime($row['pengiriman'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['pengiriman_qty']) ? $row['pengiriman_qty']." ".$row['uom'] : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['install']) ? date('d-m-Y', strtotime($row['install'])) : "") ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['install_qty']) ? $row['install_qty']." ".$row['uom'] : "") ?></td>
                                                <td><?php echo $lead_time_process ?></td>
                                                <td><i class="icon-eye-open" data-toggle="modal" data-target="#viewModal-<?php echo $id ?>" style="color: black; cursor: pointer;"></i></td>
                                                <td><a class="btn mini brown" data-toggle="modal" data-target="#historyModal-<?php echo $id ?>">History</a></td>
                                                <div class="modal fade" id="viewModal-<?php echo $id ?>" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-brown">
                                                              <h3 class="modal-title">More Detail</h3>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row-fluid">
                                                                    <div class="span6">
                                                                        <p><b>PIC P</b></p>
                                                                        <p><?php echo $row['pic_p_name']; ?></p>
                                                                    </div>
                                                                    <div class="span6">
                                                                        <p><b>PIC W</b></p>
                                                                        <p><?php echo $row['pic_w_name']; ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row-fluid">
                                                                    <div class="span6">
                                                                        <p><b>PIC Install</b></p>
                                                                        <p><?php echo $row['pic_install_name'] ?></p>
                                                                    </div>
                                                                    <div class="span6">
                                                                        <p><b>PIC</b></p>
                                                                        <p><?php echo $row['pic_name'] ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row-fluid">
                                                                    <div class="span6">
                                                                        <p><b>Tgl. Dist. ke Produksi</b></p>
                                                                        <p><?php echo (isset($row['distribution_to_production_date']) ? date('d-m-Y', strtotime($row['distribution_to_production_date'])) : "") ?></p>
                                                                    </div>
                                                                    <div class="span6">
                                                                        <p><b>Detail Schedule</b></p>
                                                                        <p><?php echo (isset($row['detail_schedule']) ? date('d-m-Y', strtotime($row['detail_schedule'])) : "") ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row-fluid">
                                                                    <div class="span6">
                                                                        <p><b>Keterangan</b></p>
                                                                        <p><?php echo $row['information'] ?></p>
                                                                    </div>
                                                                </div>   
                                                            </div>
                                                            <div class="modal-footer">
                                                              <button class="btn brown" data-dismiss="modal" style="margin-top: 3px;">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="historyModal-<?php echo $id ?>" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                              <h3 class="modal-title">History</h3>
                                                            </div>
                                                            <div class="modal-body" style="height: 500px;">
                                                                <iframe style="height: 500px;" src="<?=base_url()?>report/history/<?php echo $id ?>" class="span5">
                                                                    <p>Your browser does not support iframes.</p>
                                                                </iframe>
                                                                
                                                            </div>
                                                            <div class="modal-footer">
                                                              <button class="btn btn-default" data-dismiss="modal" style="margin-top: 3px;">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="portlet-body">
                        <div class="">
                            <br/>
                            <form name="aaaa" method="post" action="<?php echo site_url('report/export_excel'); ?>">
                                <input type="hidden" id="production_order1" name="production_order1" value="<?=$production_order1?>" >
                                <input type="hidden" id="production_order2" name="production_order2" value="<?=$production_order2?>" >
                                <input type="hidden" id="production_order_str" name="production_order_str" value="<?=$production_order_str?>" >
                                <input type="hidden" id="project_definition1" name="project_definition1" value="<?=$project_definition1?>" >
                                <input type="hidden" id="project_definition2" name="project_definition2" value="<?=$project_definition2?>" >
                                <input type="hidden" id="project_definition_str" name="project_definition_str" value="<?=$project_definition_str?>" >
                                <input type="hidden" id="sales_order1" name="sales_order1" value="<?=$sales_order1?>" >
                                <input type="hidden" id="sales_order2" name="sales_order2" value="<?=$sales_order2?>" >
                                <input type="hidden" id="sales_order_str" name="sales_order_str" value="<?=$sales_order_str?>" >
                                <input type="hidden" id="basic_date_start" name="basic_date_start" value="<?=$basic_date_start?>" >
                                <input type="hidden" id="basic_date_end" name="basic_date_end" value="<?=$basic_date_end?>" >
                                <input type="hidden" id="po_vmk" name="po_vmk" value="<?=$po_vmk?>" >
                                <input type="hidden" id="so_vmk_str" name="so_vmk_str" value="<?=$so_vmk_str?>" >
                                <input type="hidden" id="plant" name="plant" value="<?=$plant?>" >
                                <button type="submit" class="btn brown"> Export Excel</button>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>