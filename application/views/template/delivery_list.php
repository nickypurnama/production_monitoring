<style type="text/css">
	table {
		border: 1px solid;
	}

	td {
		font-size: 10px;
	}

	.border-1 {
		border-top: 1px solid;
		border-right: 1px solid;
		padding-top: 3px;
		padding-bottom: 3px;
	}

	.border-2 {
		border-top: 1px solid;
		padding: 3px;
		padding-bottom: 3px;
	}
</style>
<div style='page-break-after: always;'>
	<table style="border-spacing: 0px;">
		<tbody>
			<tr>
				<td colspan="5" style="font-size: 18px; font-weight: 700; border: 0px;">DELIVERY LIST</td>
				<td colspan="3" style="text-align: center; border: 0px;"><img src="<?php echo $logo ?>" style="width: 130px;"></td>
			</tr>
			<tr>
				<td colspan="2" class="border-1" style="font-weight: 700;">BACTH NUMBER</td>
				<td colspan="2" class="border-1"><?php echo $batch ?></td>
				<td colspan="4" rowspan="3" class="border-2" style="text-align: center;"><img src="<?php echo $qr_code ?>" style="width: 75px;"></td>
			</tr>
			<tr>
				<td colspan="2" class="border-1" style="font-weight: 700;">WBS NO</td>
				<td colspan="2" class="border-1"><?php echo $header['project_definition'] ?></td>
			</tr>
			<tr>
				<td colspan="2" class="border-1" style="font-weight: 700;">PROD. ORDER NO</td>
				<td colspan="2" class="border-1"><?php echo $header['production_order'] ?></td>
			</tr>
			<tr>
				<td colspan="2" class="border-1" style="font-weight: 700;">PROJECT</td>
				<td colspan="2" class="border-1"><?php echo $header['project_description'] ?></td>
				<td colspan="4" rowspan="3" class="border-2" style="font-size: 36px; font-weight: 700; text-align: center;">DL</td>
			</tr>
			<tr>
				<td colspan="2" class="border-1" style="font-weight: 700;">PRODUCT</td>
				<td colspan="2" class="border-1"><?php echo $code ?></td>
			</tr>
			<tr>
				<td colspan="2" class="border-1" style="font-weight: 700;">FINISH COLOUR</td>
				<td colspan="2" class="border-1"><?php echo $header['order_description'] ?></td>
			</tr>
			<tr style="text-align: center; font-weight: 700;">
				<td class="border-1">NO</td>
				<td class="border-1" style="width: 100px;">WORKSHOP</td>
				<td class="border-1" style="width: 100px;">PACK NO</td>
				<td class="border-1" style="width: 125px;">PACK CODE</td>
				<td class="border-1" style="width: 100px;">QTY PACK</td>
				<td colspan="3" class="border-2" style="width: 225px;">PRODUCT NAME</td>
			</tr>
			<?php
				$no = 1;
				$total_pack = 0;
				$total_qty_pack = 0;
				
				foreach($panel as $row){
					$workshop = $row['workshop'];

					if(($temp_box_no != $row['box_no'])|| ($temp_packing_code != $row['packing_code']) || ($temp_module != $row['module'])){ ?>
						<tr>
							<td class="border-1" style="text-align: center;"><?php echo $no ?></td>
							<?php
								if($workshop == $temp_workshop){ ?>
					                <td class="border-1"></td>
								<?php }else{ ?>
					                <td class="border-1" style="text-transform: uppercase;"><?php echo $workshop ?></td>
								<?php }
							?>
							
							<td class="border-1" style="text-align: center;"><?php echo $row['box_no'] ?></td>
							<td class="border-1"><?php echo $row['packing_code'] ?></td>
							<td class="border-1" style="text-align: center;"><?php echo $row['qty_pack'] ?></td>
							<td colspan="3" class="border-2"><?php echo $row['module'] ?></td>
						</tr>
						<?php 
						$total_pack += 1;
						$no++;
					}

					if($temp_packing_code != $row['packing_code'])
					{
						$total_qty_pack += $row['qty_pack'];
					}

					$temp_workshop = $workshop;
					$temp_box_no = $row['box_no'];
					$temp_packing_code = $row['packing_code'];
					$temp_module = $row['module'];
				}

				foreach($non_panel as $row){
					$workshop = $row['workshop'];

					if($workshop == "Accessories" || $workshop == "ACCESSORIES"){
						if(($temp_box_no != $row['box_no']) || ($temp_packing_code != $row['packing_code']) || ($temp_module != $row['workshop'])){ ?>
							<tr>
								<td class="border-1" style="text-align: center;"><?php echo $no ?></td>
								<?php
									if($workshop == $temp_workshop){ ?>
						                <td class="border-1"></td>
									<?php }else{ ?>
						                <td class="border-1" style="text-transform: uppercase;"><?php echo $workshop ?></td>
									<?php }
								?>
								
								<td class="border-1" style="text-align: center;"><?php echo $row['box_no'] ?></td>
								<td class="border-1"><?php echo $row['packing_code'] ?></td>
								<td class="border-1" style="text-align: center;"><?php echo $row['qty_pack'] ?></td>
								<td colspan="3" class="border-2" style="text-transform: uppercase;"><?php echo $row['workshop'] ?></td>
							</tr>
							<?php 
							$total_pack += 1;
							$no++;
						}

						$temp_workshop = $workshop;
						$temp_box_no = $row['box_no'];
						$temp_packing_code = $row['packing_code'];
						$temp_module = $row['workshop'];
					}else{
						if(($temp_box_no != $row['box_no']) || ($temp_packing_code != $row['packing_code']) || ($temp_module != $row['description'])){ ?>
							<tr>
								<td class="border-1" style="text-align: center;"><?php echo $no ?></td>
								<?php
									if($workshop == $temp_workshop){ ?>
						                <td class="border-1"></td>
									<?php }else{ ?>
						                <td class="border-1" style="text-transform: uppercase;"><?php echo $workshop ?></td>
									<?php }
								?>
								
								<td class="border-1" style="text-align: center;"><?php echo $row['box_no'] ?></td>
								<td class="border-1"><?php echo $row['packing_code'] ?></td>
								<td class="border-1" style="text-align: center;"><?php echo $row['qty_pack'] ?></td>
								<td colspan="3" class="border-2"><?php echo $row['description'] ?></td>
							</tr>
							<?php 
							$total_pack += 1;
							$no++;
						}

						$temp_workshop = $workshop;
						$temp_box_no = $row['box_no'];
						$temp_packing_code = $row['packing_code'];
						$temp_module = $row['description'];
					}

					if($temp_packing_code_qty_pack != $row['packing_code'])
					{
						$total_qty_pack += $row['qty_pack'];
					}
					
					$temp_packing_code_qty_pack = $row['packing_code'];
				}
			?>
			<tr>
				<td colspan="4" class="border-1" style="text-align: right; font-weight: 700;">TOTAL PACK</td>
				<td class="border-1" style="text-align: center;"><?php echo $total_qty_pack ?></td>
				<td colspan="3" class="border-2"></td>
			</tr>
		</tbody>
	</table>
</div>