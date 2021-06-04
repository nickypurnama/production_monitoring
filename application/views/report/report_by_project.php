<!-- BEGIN PAGE -->
<script type="text/javascript">
    $(document).ready(function(){

        var div_search = document.getElementById("div_search");
        if(<?php echo $hide_header ?> == 1){
            div_search.style.display = "none";
        }

        $("#btn_toggle").click(function(){
            $("#div_search").toggle("slow");
        });

        $("#btn_batch").click(function(){
                
          var xbatch = $("#batch").val();

          if(xbatch == ""){
            alert("Batch is required");
          }else{
            $.ajax({ 
              type: 'POST', 
              url: "<?php echo site_url('ajax/get_production_order_by_batch'); ?>", 
              data: {
                batch : xbatch
              }, 
              success: function(msg) {
                var div_production_order = document.getElementById("div_production_order");
                div_production_order.style.display = "block";
                
                $("#production_order").html(msg);
              }
            });
          }
        });
    });
</script>
<style type="text/css">
    #sample_3 td, #table td, #table-2 td {
        text-align: center;
        width: 100px;
    }

    .border-1 {
        border-top: 1px solid;
        border-right: 1px solid;
    }

    .border-2 {
        border-left: 1px solid;
    }

    .border-3 {
        border-bottom: 1px solid;
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
                        <div class="caption"><i class="icon-tasks"></i> Report by Project</div>
                        <div class="actions">
                            <button id="btn_toggle" class="btn brown">Show/Hide</button>
                        </div>
                    </div>
                    <div id="div_search" class="portlet-body table-bottom" style="display: block;">
                        <form action="<?php echo site_url('report/report_by_project'); ?>" id="form_sample_1" class="form-horizontal" method="get" enctype="multipart/form-data">
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
                                        <label class="control-label">Project Definition</label>
                                        <div class="controls">
                                            <select id="project" name="project" class="span6 select2 select2_sample1">
                                                <option></option>
                                                <?php
                                                    foreach($list_project as $row){ 
                                                        if($project == $row['project_definition']){ ?>
                                                            <option value="<?php echo $row['project_definition'] ?>" selected="true"><?php echo $row['project_definition'] ?> - <?php echo $row['project_description'] ?></option>
                                                        <?php }else{ ?>
                                                            <option value="<?php echo $row['project_definition'] ?>"><?php echo $row['project_definition'] ?> - <?php echo $row['project_description'] ?></option>
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
        </div>
        <?php
            if($project <> ""){ ?>
                <div class="row-fluid">
                    <div class="span6">
                        <h4><b>Production Order Non Phyta</b></h4>
                        <br>
                        <table id="table">
                            <tbody>
                                <?php
                                    if(isset($project_data)){ 
                                        if(is_numeric( $project_data['total_qty'] ) && floor( $project_data['total_qty'] ) != $project_data['total_qty'])
                                            $total_qty = number_format($project_data['total_qty'], 3, ".", "");
                                        else
                                            $total_qty = $project_data['total_qty'];

                                        if(is_numeric( $project_data['ppic_qty'] ) && floor( $project_data['ppic_qty'] ) != $project_data['ppic_qty'])
                                            $ppic_qty = number_format($project_data['ppic_qty'], 3, ".", "");
                                        else
                                            $ppic_qty = $project_data['ppic_qty'];

                                        if(is_numeric( $project_data['pembahanan_qty'] ) && floor( $project_data['pembahanan_qty'] ) != $project_data['pembahanan_qty'])
                                            $pembahanan_qty = number_format($project_data['pembahanan_qty'], 3, ".", "");
                                        else
                                            $pembahanan_qty = $project_data['pembahanan_qty'];

                                        if(is_numeric( $project_data['perakitan_qty'] ) && floor( $project_data['perakitan_qty'] ) != $project_data['perakitan_qty'])
                                            $perakitan_qty = number_format($project_data['perakitan_qty'], 3, ".", "");
                                        else
                                            $perakitan_qty = $project_data['perakitan_qty'];

                                        if(is_numeric( $project_data['finishing_qty'] ) && floor( $project_data['finishing_qty'] ) != $project_data['finishing_qty'])
                                            $finishing_qty = number_format($project_data['finishing_qty'], 3, ".", "");
                                        else
                                            $finishing_qty = $project_data['finishing_qty'];

                                        if(is_numeric( $project_data['finish_good_qty'] ) && floor( $project_data['finish_good_qty'] ) != $project_data['finish_good_qty'])
                                            $finish_good_qty = number_format($project_data['finish_good_qty'], 3, ".", "");
                                        else
                                            $finish_good_qty = $project_data['finish_good_qty'];

                                        if(is_numeric( $project_data['pengiriman_qty'] ) && floor( $project_data['pengiriman_qty'] ) != $project_data['pengiriman_qty'])
                                            $pengiriman_qty = number_format($project_data['pengiriman_qty'], 3, ".", "");
                                        else
                                            $pengiriman_qty = $project_data['pengiriman_qty'];

                                        if(is_numeric( $project_data['install_qty'] ) && floor( $project_data['install_qty'] ) != $project_data['install_qty'])
                                            $install_qty = number_format($project_data['install_qty'], 3, ".", "");
                                        else
                                            $install_qty = $project_data['install_qty'];
                                        ?>
                                        <tr>
                                            <td class="border-1 border-2"><b>Project Def.</b></td>
                                            <td class="border-1"><b>Quantity</b></td>
                                            <td class="border-1"><b>PPIC</b></td>
                                            <td class="border-1"><b>Pembahanan</b></td>
                                            <td class="border-1"><b>Perakitan</b></td>
                                            <td class="border-1"><b>Finishing</b></td>
                                            <td class="border-1"><b>Finish Good</b></td>
                                            <td class="border-1"><b>Pengiriman</b></td>
                                            <td class="border-1"><b>Install</b></td>
                                        </tr>
                                        <tr>
                                            <td class="border-1 border-2 border-3"><?php echo $project ?></td>
                                            <td class="border-1 border-3"><?php echo $total_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $ppic_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $pembahanan_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $perakitan_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $finishing_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $finish_good_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $pengiriman_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $install_qty ?></td>
                                        </tr>
                                    <?php }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <div id="chart"></div>
                    </div>
                    <div class="span6">
                        <h4><b>Production Order Phyta</b></h4>
                        <br>
                        <table id="table-2">
                            <tbody>
                                <?php
                                    if(isset($project_panel_data)){ 
                                        if(is_numeric( $project_panel_data['total_panel_qty'] ) && floor( $project_panel_data['total_panel_qty'] ) != $project_panel_data['total_panel_qty'])
                                            $total_panel_qty = number_format($project_panel_data['total_panel_qty'], 3, ".", "");
                                        else
                                            $total_panel_qty = $project_panel_data['total_panel_qty'];

                                        if(is_numeric( $project_panel_data['station_a_qty'] ) && floor( $project_panel_data['station_a_qty'] ) != $project_panel_data['station_a_qty'])
                                            $station_a_qty = number_format($project_panel_data['station_a_qty'], 3, ".", "");
                                        else
                                            $station_a_qty = $project_panel_data['station_a_qty'];

                                        if(is_numeric( $project_panel_data['station_b_qty'] ) && floor( $project_panel_data['station_b_qty'] ) != $project_data['station_b_qty'])
                                            $station_b_qty = number_format($project_panel_data['station_b_qty'], 3, ".", "");
                                        else
                                            $station_b_qty = $project_panel_data['station_b_qty'];

                                        if(is_numeric( $project_panel_data['station_c_qty'] ) && floor( $project_panel_data['station_c_qty'] ) != $project_panel_data['station_c_qty'])
                                            $station_c_qty = number_format($project_panel_data['station_c_qty'], 3, ".", "");
                                        else
                                            $station_c_qty = $project_panel_data['station_c_qty'];

                                        if(is_numeric( $project_panel_data['station_d_qty'] ) && floor( $project_panel_data['station_d_qty'] ) != $project_panel_data['station_d_qty'])
                                            $station_d_qty = number_format($project_panel_data['station_d_qty'], 3, ".", "");
                                        else
                                            $station_d_qty = $project_panel_data['station_d_qty'];

                                        if(is_numeric( $project_panel_data['logistic_qty'] ) && floor( $project_panel_data['logistic_qty'] ) != $project_panel_data['logistic_qty'])
                                            $logistic_qty = number_format($project_panel_data['logistic_qty'], 3, ".", "");
                                        else
                                            $logistic_qty = $project_panel_data['logistic_qty'];

                                        if(is_numeric( $project_panel_data['install_qty'] ) && floor( $project_panel_data['install_qty'] ) != $project_panel_data['install_qty'])
                                            $install_qty = number_format($project_panel_data['install_qty'], 3, ".", "");
                                        else
                                            $install_qty = $project_panel_data['install_qty'];

                                        ?>
                                        <tr>
                                            <td class="border-1 border-2"><b>Project Def.</b></td>
                                            <td class="border-1"><b>Quantity</b></td>
                                            <td class="border-1"><b>Station A</b></td>
                                            <td class="border-1"><b>Station B</b></td>
                                            <td class="border-1"><b>Station C</b></td>
                                            <td class="border-1"><b>Station D</b></td>
                                            <td class="border-1"><b>Logistik</b></td>
                                            <td class="border-1"><b>Install</b></td>
                                        </tr>
                                        <tr>
                                            <td class="border-1 border-2 border-3"><?php echo $project ?></td>
                                            <td class="border-1 border-3"><?php echo $total_panel_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $station_a_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $station_b_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $station_c_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $station_d_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $logistic_qty ?></td>
                                            <td class="border-1 border-3"><?php echo $install_qty ?></td>
                                        </tr>
                                    <?php }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <div id="chart-2"></div>
                    </div>
                </div>
                <script src="<?php echo base_url(); ?>assets/plugins/highcharts/highcharts.js"></script>
                <script src="<?php echo base_url(); ?>assets/plugins/highcharts/highcharts-3d.js"></script>
                <script src="<?php echo base_url(); ?>assets/plugins/highcharts/exporting.js"></script>

                <script type="text/javascript">
                    $('#chart').highcharts({
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Production Order'
                        },
                        xAxis: {
                            categories: [
                                'PPIC',
                                'Pembahanan',
                                'Perakitan',
                                'Finishing',
                                'Finish Good',
                                'Pengiriman',
                                'Install'
                            ],
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            max: <?php echo $total_qty ?>,
                            title: {
                                text: 'Quantity'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y}</b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        //colors: ['#2f7ed8'],
                        series: [{
                            name: '<?php echo $project ?>',
                            data: [<?php echo $ppic_qty; ?>, <?php echo $pembahanan_qty; ?>, <?php echo $perakitan_qty; ?>, <?php echo $finishing_qty; ?>, <?php echo $finish_good_qty; ?>, <?php echo $pengiriman_qty; ?>, <?php echo $install_qty; ?>]

                        }]
                    });

                    $('#chart-2').highcharts({
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Production Order'
                        },
                        xAxis: {
                            categories: [
                                'Station A',
                                'Station B',
                                'Station C',
                                'Station D',
                                'Logistik',
                                'Install'
                            ],
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Quantity'
                            }
                        },
                        tooltip: {
                            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                '<td style="padding:0"><b>{point.y}</b></td></tr>',
                            footerFormat: '</table>',
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        //colors: ['#2f7ed8'],
                        series: [{
                            name: '<?php echo $project ?>',
                            data: [<?php echo $station_a_qty; ?>, <?php echo $station_b_qty; ?>, <?php echo $station_c_qty; ?>, <?php echo $station_d_qty; ?>, <?php echo $logistic_qty; ?>, <?php echo $install_qty; ?>]

                        }]
                    });
                </script>
            <?php }
        ?>
    </div>
</div>