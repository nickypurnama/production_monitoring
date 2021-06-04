
	<!-- BEGIN CONTAINER -->   
	<div class="page-container row-fluid">
		<!-- BEGIN EMPTY PAGE SIDEBAR -->
		<div class="page-sidebar nav-collapse collapse visible-phone visible-tablet">
			<ul class="page-sidebar-menu">
				<!-- <li class="visible-phone visible-tablet">
					<form class="sidebar-search">
						<div class="input-box">
							<a href="javascript:;" class="remove"></a>
							<input type="text" placeholder="Search..." />            
							<input type="button" class="submit" value=" " />
						</div>
					</form>
				</li> -->
				<li>
					<a id="" href="<?php echo site_url('site/index'); ?>" class="btn_loading">
					Home
					</a>
			    </li>
			    <?php
			    	if($this->session->userdata('role_prodmon') == '1' || $this->session->userdata('role_prodmon') == '5' || $this->session->userdata('role_prodmon') == '6'){
			    	?>
						<li>
							<a href="<?php echo site_url('transaction/upload'); ?>">
							Upload Data</a>
					    </li>
					    <li>
							<a href="<?php echo site_url('transaction/input'); ?>">
							Input Data</a>
					    </li>
					    <li>
							<a href="<?php echo site_url('transaction/change'); ?>">
							Change Data</a>
					    </li>
					    <li>
							<a href="<?php echo site_url('transaction/display'); ?>">
							Display Data</a>
					    </li>
					<?php }
				?>
			    <?php
			    	if($this->session->userdata('role_prodmon') == '1' || $this->session->userdata('role_prodmon') == '2' || $this->session->userdata('role_prodmon') == '4' || $this->session->userdata('role_prodmon') == '5' || $this->session->userdata('role_prodmon') == '6' || $this->session->userdata('role_prodmon') == '8' || $this->session->userdata('role_prodmon') == '9'){
			    	?>
					    <li>
							<a id="" href="<?php echo site_url('report/report_plant'); ?>" class="btn_loading">
							Report
							</a>
					    </li>
					<?php }
				?>
			    <?php
			    	if($this->session->userdata('role_prodmon') == '5' || $this->session->userdata('role_prodmon') == '6'){
			    	?>
						<li>
							<a href="<?php echo site_url('master/user_role'); ?>">
							User Role</a>
					    </li>
					    <?php
				    		if($this->session->userdata('role_prodmon') == '6'){
				    		?>
							    <li>
									<a href="<?php echo site_url('master/plant'); ?>">
									Plant</a>
							    </li>
						    <?php }
						?>
					<?php }
				?>
						 	  
			</ul>
		</div>
		<!-- END EMPTY PAGE SIDEBAR -->
		