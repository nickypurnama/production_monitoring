<!-- BEGIN PAGE -->
<script type="text/javascript">
    
$(function () {
    $('#production-chart').highcharts({
        title: {
            text: 'Production Order Item per Process'
        },

        yAxis: {
            title: {
                text: 'Total Item'
            }
        },

        xAxis: {
            categories: ['PPIC','Pembahanan', 'Perakitan', 'Finishing', 'Finish Good'],
        },

        series: [
            <?php
                $time_latest = 0;
                foreach($chart_data as $row){ 
                    $value = $row['ppic'].','.$row['pembahanan'].','.$row['perakitan'].','.$row['finishing'].','.$row['finish_good'];
                    $time = date('d-m-Y H:i:s', strtotime($row['updated_at']));

                    if($time_latest == 0)
                        $time_latest = $time;
                    else{
                        if($time > $time_latest)
                            $time_latest = $time;
                    }
                    
                    ?>
                    {
                        name: <?php echo $row['plant'] ?>,
                        data: [<?php echo $value ?>]
                    },
                <?php }
            ?>
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
});
</script>

<script src="<?php echo base_url(); ?>assets/plugins/highcharts/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/highcharts/highcharts-3d.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/highcharts/exporting.js"></script>
<div class="page-content">
    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">
        <div class="row-fluid" style="margin-top: 10%">
        	<div class="span12">
        		<div id="production-chart"></div>
                <p>Last Update: <?php echo $time_latest ?></p>
        	</div>
		</div>
	</div>
</div>
<!-- END CONTAINER -->
	