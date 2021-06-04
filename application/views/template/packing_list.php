<style type="text/css">
	/*table {
		border: 1px solid;
	}*/

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
		border-right: 1px solid;
		padding: 3px;
		padding-bottom: 3px;
	}

	.border-3 {
		border-left: 1px solid;
	}

	.border-4 {
		border-right: 1px solid;
	}

	.border-5 {
		border-bottom: 1px solid;
	}

</style>
<?php
	$i = 1;
	foreach($result as $row){ 
		for ($s=0; $s <=$row['qty_pack']-1 ; $s++) {
			if($i%2 == 0)
				$break = "style='page-break-after: always;'";
			else
				$break = "style='margin-bottom: 150px;'";

			$i++;
			?>
			<div <?php echo $break ?>>
				<table style="border-spacing: 0px;">
					<tbody>
						<tr>
							<td colspan="10" style="text-align: right;">Terbitan 1</td>
						</tr>
						<tr>
							<td colspan="7" rowspan="3" class="border-3" style="font-size: 18px; font-weight: 700; border-top: 1px solid;">PACKING LIST</td>
							<td colspan="3" class="border-4" style="border-top: 1px solid;"></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align: center; border: 1px solid">Form: QMS-CKR-QAS-F.001.10</td>
						</tr>
						<tr>
							<td colspan="3" class="border-4" style="text-align: center;"><img src="<?php echo $logo ?>" style="width: 130px;"></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">BACTH NUMBER</td>
							<td colspan="3" class="border-1"><?php echo $batch ?></td>
							<td class="border-1" style="font-weight: 700;">IN TOTAL</td>
							<td colspan="2" class="border-1"><?php echo $in_total ?> PACKS</td>
							<td colspan="2" rowspan="3" class="border-2" style="text-align: center;"><img src="<?php echo $row['qr_code'] ?>" style="width: 75px;"></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">WBS NO</td>
							<td colspan="3" class="border-1"><?php echo $row['project_definition'] ?></td>
							<td class="border-1" style="font-weight: 700;">PACK NUMBER</td>
							<td colspan="2" class="border-1"><?php echo str_pad(($s+1), 4, "0", STR_PAD_LEFT) ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PROD. ORDER NO</td>
							<td colspan="3" class="border-1"><?php echo $row['production_order'] ?></td>
							<td class="border-1" style="font-weight: 700;">PACK CODE</td>
							<td colspan="2" class="border-1"><?php echo $row['packing_code'] ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PROJECT</td>
							<td colspan="3" class="border-1"><?php echo $row['project_description'] ?></td>
							<td rowspan="3" class="border-1" style="font-weight: 700;">FLOOR</td>
							<td colspan="2" rowspan="3" class="border-1"><?php echo $row['floor'] ?></td>
							<td rowspan="3" class="border-2" style="text-align: center; vertical-align: top;">QC Check</td>
							<td rowspan="3" class="border-2" style="text-align: center; vertical-align: top;">Tanggal</td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PRODUCT</td>
							<td colspan="3" class="border-1"><?php echo $row['code'] ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">FINISH COLOUR</td>
							<td colspan="3" class="border-1"><?php echo $row['order_description'] ?></td>
						</tr>
						<tr style="text-align: center; font-weight: 700;">
							<td rowspan="2" class="border-1 border-3">NO</td>
							<td rowspan="2" class="border-1">LABEL<br>NUMBER</td>
							<td rowspan="2" class="border-1" style="width: 100px;">GROUP<br>NAME</td>
							<td rowspan="2" class="border-1" style="width: 100px;">PANEL NAME</td>
							<td rowspan="2" class="border-1">QTY<br>TO CHECK</td>
							<td rowspan="2" class="border-1" style="width: 100px;">MATERIAL</td>
							<td colspan="3" class="border-1">SIZE</td>
							<td rowspan="2" class="border-2" style="width: 48px;">WEIGHT<br>(Kg)</td>
						</tr>
						<tr style="text-align: center; font-weight: 700;">
							<td class="border-1" style="width: 52px;">W</td>
							<td class="border-1" style="width: 52px;">D</td>
							<td class="border-1" style="width: 52px;">H</td>
						</tr>
						<?php
							$no = 1;
							$total_component = 0;
							$total_weight = 0;
							
							foreach($row['item'] as $row2){ 
								$total_component++;
								$total_weight += $row2['weight'];
								?>
								<tr>
									<td class="border-1 border-3" style="text-align: center;"><?php echo $no ?></td>
									<td class="border-1" style="text-align: center;"><?php echo $row2['component_no'] ?></td>
									<td class="border-1"><?php echo $row2['module'] ?></td>
									<td class="border-1"><?php echo $row2['component_name'] ?></td>
									<td class="border-1" style="text-align: center;">1</td>
									<td class="border-1"><?php echo $row2['material'] ?></td>
									<td class="border-1" style="text-align: center;"><?php echo str_replace(".", ",", $row2['width']) ?></td>
									<td class="border-1" style="text-align: center;"><?php echo str_replace(".", ",", $row2['length']) ?></td>
									<td class="border-1" style="text-align: center;"><?php echo str_replace(".", ",", $row2['height']) ?></td>
									<td class="border-2" style="text-align: center;"><?php echo str_replace(".", ",", $row2['weight']) ?></td>
								</tr>
							<?php 
							$no++;
							}
						?>
						<tr>
							<td colspan="4" class="border-1 border-3 border-5" style="text-align: right; font-weight: 700;">TOTAL COMPONENT</td>
							<td class="border-1 border-5" style="text-align: center;"><?php echo $total_component ?></td>
							<td colspan="4" class="border-1 border-5" style="text-align: right; font-weight: 700;">TOTAL WEIGHT</td>
							<td class="border-2 border-5" style="text-align: center;"><?php echo str_replace(".", ",", $total_weight) ?></td>
						</tr>
					</tbody>
				</table>
			</div>
	<?php
		}
	}

	foreach($non_panel as $row){ 
		for ($s=0; $s <=$row['qty_pack']-1 ; $s++) { 
			if($i%2 == 0)
				$break = "style='page-break-after: always;'";
			else
				$break = "style='margin-bottom: 150px;'";

			$i++;
			?>
			<div <?php echo $break ?>>
				<table style="border-spacing: 0px;">
					<tbody>
						<tr>
							<td colspan="10" style="text-align: right;">Terbitan 1</td>
						</tr>
						<tr>
							<td colspan="7" rowspan="3" class="border-3" style="font-size: 18px; font-weight: 700; border-top: 1px solid;">PACKING LIST</td>
							<td colspan="3" class="border-4" style="border-top: 1px solid;"></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align: center; border: 1px solid">Form: QMS-CKR-QAS-F.001.10</td>
						</tr>
						<tr>
							<td colspan="3" class="border-4" style="text-align: center;"><img src="<?php echo $logo ?>" style="width: 130px;"></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">BACTH NUMBER</td>
							<td colspan="3" class="border-1"><?php echo $batch ?></td>
							<td class="border-1" style="font-weight: 700;">IN TOTAL</td>
							<td colspan="2" class="border-1"><?php echo $in_total ?> PACKS</td>
							<td colspan="2" rowspan="3" class="border-2" style="text-align: center;"><img src="<?php echo $row['qr_code'] ?>" style="width: 75px;"></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">WBS NO</td>
							<td colspan="3" class="border-1"><?php echo $row['project_definition'] ?></td>
							<td class="border-1" style="font-weight: 700;">PACK NUMBER</td>
							<td colspan="2" class="border-1"><?php echo str_pad($s+1, 4, '0', STR_PAD_LEFT) ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PROD. ORDER NO</td>
							<td colspan="3" class="border-1"><?php echo $row['production_order'] ?></td>
							<td class="border-1" style="font-weight: 700;">PACK CODE</td>
							<td colspan="2" class="border-1"><?php echo $row['packing_code'] ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PROJECT</td>
							<td colspan="3" class="border-1"><?php echo $row['project_description'] ?></td>
							<td rowspan="3" class="border-1" style="font-weight: 700;">FLOOR</td>
							<td colspan="2" rowspan="3" class="border-1"><?php echo $row['floor'] ?></td>
							<td rowspan="3" class="border-2" style="text-align: center; vertical-align: top;">QC Check</td>
							<td rowspan="3" class="border-2" style="text-align: center; vertical-align: top;">Tanggal</td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PRODUCT</td>
							<td colspan="3" class="border-1"><?php echo $row['code'] ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">FINISH COLOUR</td>
							<td colspan="3" class="border-1"><?php echo $row['order_description'] ?></td>
						</tr>
						<tr style="text-align: center; font-weight: 700;">
							<td class="border-1 border-3">NO</td>
							<td class="border-1">SAP CODE</td>
							<td colspan="6" class="border-1" style="width: 470px;">DESCRIPTION</td>
							<td class="border-1">QTY<br>TO CHECK</td>
							<td class="border-2" style="width: 50px;">UNIT</td>
						</tr>
						<?php
							$no = 1;
							$total_component = 0;
							$total_weight = 0;
							
							$rows = sizeof($row['item']);
							foreach($row['item'] as $row2){ 
								$total_component += $row2['quantity'];
								$total_weight += $row2['weight'];

								if($rows == $no){ ?>
									<tr>
										<td class="border-1 border-3 border-5" style="text-align: center;"><?php echo $no ?></td>
										<td class="border-1 border-5" style="text-align: center;"><?php echo $row2['material'] ?></td>
										<td colspan="6" class="border-1 border-5"><?php echo $row2['description'] ?></td>
										<td class="border-1 border-5" style="text-align: center;"><?php echo $row2['quantity'] ?></td>
										<td class="border-2 border-5"><?php echo $row2['uom'] ?></td>
									</tr>
								<?php }else{ ?>
									<tr>
										<td class="border-1 border-3" style="text-align: center;"><?php echo $no ?></td>
										<td class="border-1" style="text-align: center;"><?php echo $row2['material'] ?></td>
										<td colspan="6" class="border-1"><?php echo $row2['description'] ?></td>
										<td class="border-1" style="text-align: center;"><?php echo $row2['quantity'] ?></td>
										<td class="border-2"><?php echo $row2['uom'] ?></td>
									</tr>
								<?php }
								?>
									
							<?php 
							$no++;
							}
						?>
					</tbody>
				</table>
			</div>
	<?php }
	}
?>
<?php
	if($i%2 == 0){ ?>
		<div style="page-break-after: always;">
		</div>
	<?php }
?>
<?php
	$i = 1;
	foreach($result as $row){ 
		for ($s=0; $s <= $row['qty_pack']-1 ; $s++) { 
			if($i%3 == 0)
				$break = "style='page-break-after: always;'";
			else
				$break = "style='margin-bottom: 50px;'";

			$i++;
			?>
			<div <?php echo $break ?>>
				<table style="border-spacing: 0px;">
					<tbody>
						<tr>
							<td colspan="10" style="text-align: right;">Terbitan 1</td>
						</tr>
						<tr>
							<td colspan="7" rowspan="3" class="border-3" style="font-size: 18px; font-weight: 700; border-top: 1px solid;">PACKING LIST</td>
							<td colspan="3" class="border-4" style="border-top: 1px solid;"></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align: center; border: 1px solid">Form: QMS-CKR-QAS-F.001.10</td>
						</tr>
						<tr>
							<td colspan="3" class="border-4" style="text-align: center;"><img src="<?php echo $logo ?>" style="width: 130px;"></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">BACTH NUMBER</td>
							<td colspan="3" class="border-1"><?php echo $batch ?></td>
							<td class="border-1" style="font-weight: 700;">IN TOTAL</td>
							<td colspan="2" class="border-1"><?php echo $in_total ?> PACKS</td>
							<td colspan="2" rowspan="3" class="border-2" style="text-align: center;"><img src="<?php echo $row['qr_code'] ?>" style="width: 75px;"></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">WBS NO</td>
							<td colspan="3" class="border-1"><?php echo $row['project_definition'] ?></td>
							<td class="border-1" style="font-weight: 700;">PACK NUMBER</td>
							<td colspan="2" class="border-1"><?php echo str_pad($s+1, 4, '0', STR_PAD_LEFT) ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PROD. ORDER NO</td>
							<td colspan="3" class="border-1" style="width: 250px;"><?php echo $row['production_order'] ?></td>
							<td class="border-1" style="font-weight: 700;">PACK CODE</td>
							<td colspan="2" class="border-1" style="width: 140px;"><?php echo $row['packing_code'] ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PROJECT</td>
							<td colspan="3" class="border-1"><?php echo $row['project_description'] ?></td>
							<td rowspan="3" class="border-1 border-5" style="font-weight: 700;">FLOOR</td>
							<td colspan="2" rowspan="3" class="border-1 border-5"><?php echo $row['floor'] ?></td>
							<td rowspan="3" class="border-2 border-5" style="text-align: center; vertical-align: top; width: 50px;">QC Check</td>
							<td rowspan="3" class="border-2 border-5" style="text-align: center; vertical-align: top; width: 50px;">Tanggal</td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PRODUCT</td>
							<td colspan="3" class="border-1"><?php echo $row['code'] ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3 border-5" style="font-weight: 700;">FINISH COLOUR</td>
							<td colspan="3" class="border-1 border-5"><?php echo $row['order_description'] ?></td>
						</tr>
					</tbody>
				</table>
			</div>
	<?php }
	}

	foreach($non_panel as $row){ 
		for ($s=0; $s <= $row['qty_pack']-1 ; $s++) { 
			if($i%3 == 0)
				$break = "style='page-break-after: always;'";
			else
				$break = "style='margin-bottom: 50px;'";

			$i++;
			?>
			<div <?php echo $break ?>>
				<table style="border-spacing: 0px;">
					<tbody>
						<tr>
							<td colspan="10" style="text-align: right;">Terbitan 1</td>
						</tr>
						<tr>
							<td colspan="7" rowspan="3" class="border-3" style="font-size: 18px; font-weight: 700; border-top: 1px solid;">PACKING LIST</td>
							<td colspan="3" class="border-4" style="border-top: 1px solid;"></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align: center; border: 1px solid">Form: QMS-CKR-QAS-F.001.10</td>
						</tr>
						<tr>
							<td colspan="3" class="border-4" style="text-align: center;"><img src="<?php echo $logo ?>" style="width: 130px;"></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">BACTH NUMBER</td>
							<td colspan="3" class="border-1"><?php echo $batch ?></td>
							<td class="border-1" style="font-weight: 700;">IN TOTAL</td>
							<td colspan="2" class="border-1"><?php echo $in_total ?> PACKS</td>
							<td colspan="2" rowspan="3" class="border-2" style="text-align: center;"><img src="<?php echo $row['qr_code'] ?>" style="width: 75px;"></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">WBS NO</td>
							<td colspan="3" class="border-1"><?php echo $row['project_definition'] ?></td>
							<td class="border-1" style="font-weight: 700;">PACK NUMBER</td>
							<td colspan="2" class="border-1"><?php echo str_pad($s+1, 4, '0', STR_PAD_LEFT) ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PROD. ORDER NO</td>
							<td colspan="3" class="border-1" style="width: 250px;"><?php echo $row['production_order'] ?></td>
							<td class="border-1" style="font-weight: 700;">PACK CODE</td>
							<td colspan="2" class="border-1" style="width: 140px;"><?php echo $row['packing_code'] ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PROJECT</td>
							<td colspan="3" class="border-1"><?php echo $row['project_description'] ?></td>
							<td rowspan="3" class="border-1 border-5" style="font-weight: 700;">FLOOR</td>
							<td colspan="2" rowspan="3" class="border-1 border-5"><?php echo $row['floor'] ?></td>
							<td rowspan="3" class="border-2 border-5" style="text-align: center; vertical-align: top; width: 50px;">QC Check</td>
							<td rowspan="3" class="border-2 border-5" style="text-align: center; vertical-align: top; width: 50px;">Tanggal</td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3" style="font-weight: 700;">PRODUCT</td>
							<td colspan="3" class="border-1"><?php echo $row['code'] ?></td>
						</tr>
						<tr>
							<td colspan="2" class="border-1 border-3 border-5" style="font-weight: 700;">FINISH COLOUR</td>
							<td colspan="3" class="border-1 border-5"><?php echo $row['order_description'] ?></td>
						</tr>
					</tbody>
				</table>
			</div>
	<?php }
	}
?>