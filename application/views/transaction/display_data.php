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
                        <div class="caption"><i class="icon-tasks"></i> Display Production Order</div>
                        <div class="actions">
                            <!-- <a href="<?php echo site_url($urlAddItem); ?>" class="btn brown"><i class="icon-pencil"></i> Edit Header</a> -->
                        </div>
                    </div>
                    <div class="portlet-body table-bottom" style="transform: scale(0.86); transform-origin: 0 0;-moz-transform: scale(0.82);">
                        <table class="table table-striped table-advance table-bottom table-hover" id="sample_3">
                            <thead>
                                <tr class="bg-brown">
				                    <th rowspan="2" style="width: 50px;">Line Item</th>
                                    <th rowspan="2" style="width: 50px;">No Komponen</th>
                                    <th rowspan="2" style="width: 50px;">Nama Komponen</th>
                                    <th rowspan="2" style="width: 60px;">PIC P</th>
                                    <th rowspan="2" style="width: 60px;">PIC W</th>
                                    <th rowspan="2" style="width: 60px;">PIC Install</th>
                                    <th rowspan="2">WBS</th>
                                    <th rowspan="2">Project Desc</th>
                                    <th rowspan="2">Sales Order</th>
                                    <th rowspan="2">Prod. Order</th>
                                    <th rowspan="2">Order Desc</th>
                                    <th colspan="3">Ukuran</th>
                                    <th colspan="2">Kode</th>
                                    <th rowspan="2">LT</th>
                                    <th rowspan="2">QTY</th>
                                    <th rowspan="2" style="width: 100px;">Tgl. Dist. ke Produksi</th>
                                    <th rowspan="2" style="width: 60px;">PIC</th>
                                    <th rowspan="2">Posisi</th>
                                    <th rowspan="2">Keterangan</th>
                                </tr>
                                <tr class="bg-brown">
                                    <th>P</th>
                                    <th>L</th>
                                    <th>T</th>
                                    <th>Kode</th>
                                    <th>Ket. Kode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($production_order)){
                                        $no=1;
                                        foreach($production_order as $row){ ?>
                    				        <tr>
                                                <td style="width: 50px;"><?php echo $row['line_item'] ?></td>
                                                <td style="width: 50px;"><?php echo $row['component_no'] ?></td>
                                                <td style="width: 50px;"><?php echo $row['component_name'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['pic_p_name'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['pic_w_name'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['pic_install_name'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['project_definition'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['project_description'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['sales_order'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['production_order'] ?></td>
                                                <td style="width: 60px;"><?php echo $row['order_description'] ?></td>
                                                <td><?php echo $row['length'] ?></td>
                                                <td><?php echo $row['width'] ?></td>
                                                <td><?php echo $row['height'] ?></td>
                                                <td><?php echo $row['code'] ?></td>
                                                <td><?php echo $row['code_information'] ?></td>
                                                <td><?php echo $row['floor'] ?></td>
                                                <td><?php echo $row['quantity'] ?></td>
                                                <td style="width: 100px;"><?php echo (isset($row['distribution_to_production_date']) ? date('d-m-Y', strtotime($row['distribution_to_production_date'])) : "") ?></td>
                                                <td style="width: 60px;"><?php echo $row['pic_name'] ?></td>
                                                <td><?php echo $row['process'] ?></td>
                                                <td><?php echo $row['information'] ?></td>
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