<?php $role = $this->session->userdata('role'); ?>
<!-- BEGIN BODY -->
<body class="page-header-fixed page-full-width">
<div id="spinner"></div>
	<!-- BEGIN HEADER -->   
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
		<div class="container-fluid">
		<!-- BEGIN LOGO -->
		<a class="brand" href="<?php echo site_url('site/index'); ?>">
			<img src="<?php echo base_url(); ?>assets/img/production_monitoring.png" alt="logo" style="margin-left: 5%; margin-top: 5%; width: 85%;" />
		</a>
		<!-- END LOGO -->
		<!-- BEGIN HORIZANTAL MENU -->
		<div class="navbar hor-menu hidden-phone hidden-tablet">
			<div class="navbar-inner">
				<ul class="nav">
				    <li class="visible-phone visible-tablet">
						<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
						<form class="sidebar-search">
						    <div class="input-box">
							<a href="javascript:;" class="remove"></a>
							<input type="text" placeholder="Search..." />            
							<input type="button" class="submit" value=" " />
						    </div>
						</form>
						<!-- END RESPONSIVE QUICK SEARCH FORM -->
				    </li>
				    <li>
						<a id="" href="<?php echo site_url('site/index'); ?>" class="btn_loading">
						Home
						</a>
				    </li>
				    <?php
				    	if($this->session->userdata('role_prodmon') == '1' || $this->session->userdata('role_prodmon') == '5' || $this->session->userdata('role_prodmon') == '6'){
				    	?>
						    <li>
								<a data-hover="dropdown" class="dropdown-toggle" href="javascript:;">
								<span class="selected"></span>
								Transaction
								<span class="arrow"></span>     
								</a>	
								<ul class="dropdown-menu">
									<li>
										<a href="<?php echo site_url('transaction/upload'); ?>">
										Upload Data</a>
								    </li>
								    <li>
										<a href="<?php echo site_url('transaction/upload_panel'); ?>">
										Upload Data Panel</a>
								    </li>

								    <li>
										<a href="<?php echo site_url('transaction/upload_non_panel'); ?>">
										Upload Data Non Panel</a>
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
									<li>
										<a href="<?php echo site_url('transaction/close_production_order'); ?>">
										Close Production Order</a>
								    </li>
								</ul>    
								<b class="caret-out"></b>                        
						    </li>
						<?php }
					?>
					<?php
				    	if($this->session->userdata('role_prodmon') == '1' || ($this->session->userdata('plant_prodmon') == "1600" && $this->session->userdata('role_prodmon') == '2') || ($this->session->userdata('plant_prodmon') == "1200" && $this->session->userdata('role_prodmon') == '2') || $this->session->userdata('role_prodmon') == '5' || $this->session->userdata('role_prodmon') == '6'){
				    	?>
						    <li>
								<a data-hover="dropdown" class="dropdown-toggle" href="javascript:;">
								<span class="selected"></span>
								Station
								<span class="arrow"></span>     
								</a>	
								<ul class="dropdown-menu">
									<li>
										<a href="<?php echo site_url('transaction/station_a'); ?>">
										Station A - Panel Counting</a>
								    </li>
								    <li>
										<a href="<?php echo site_url('transaction/station_b'); ?>">
										Station B - QC</a>
								    </li>
								    <li>
										<a href="<?php echo site_url('transaction/station_c'); ?>">
										Station C - Grouping & Packing</a>
								    </li>
								    <li>
										<a href="<?php echo site_url('transaction/station_d'); ?>">
										Station D - Delivery</a>
								    </li>
								    <li>
										<a href="<?php echo site_url('transaction/gr_panel'); ?>">
										GR Panel</a>
								    </li>
								</ul>    
								<b class="caret-out"></b>                        
						    </li>
						    <li>
								<a data-hover="dropdown" class="dropdown-toggle" href="javascript:;">
								<span class="selected"></span>
								Form
								<span class="arrow"></span>     
								</a>	
								<ul class="dropdown-menu">
									<li>
										<a href="<?php echo site_url('transaction/packing_list'); ?>">
										Packing List</a>
								    </li>
								    <li>
										<a href="<?php echo site_url('transaction/delivery_list'); ?>">
										Delivery List</a>
								    </li>
								</ul>    
								<b class="caret-out"></b>                        
						    </li>
						<?php }
					?>
				    <?php
				    	if($this->session->userdata('role_prodmon') == '1' || $this->session->userdata('role_prodmon') == '2' || $this->session->userdata('role_prodmon') == '4' || $this->session->userdata('role_prodmon') == '5' || $this->session->userdata('role_prodmon') == '6' || $this->session->userdata('role_prodmon') == '8' || $this->session->userdata('role_prodmon') == '9'){
				    	?>
						    <li>
								<a data-hover="dropdown" class="dropdown-toggle" href="javascript:;">
								<span class="selected"></span>
								Report
								<span class="arrow"></span>     
								</a>	
								<ul class="dropdown-menu">
									<?php
								    	if($this->session->userdata('role_prodmon') == '6'){ ?>
										    <li>
												<a href="<?php echo site_url('report/report_by_project'); ?>">
												Report by Project </a>
										    </li>
										<?php }
									?>
									<li>
										<a href="<?php echo site_url('report/report_plant'); ?>">
										Production Order</a>
								    </li>
								    <?php
								    	if($this->session->userdata('role_prodmon') == '5' || $this->session->userdata('role_prodmon') == '6' || $this->session->userdata('role_prodmon') == '8'){ ?>
										    <li>
												<a href="<?php echo site_url('report/panel_logistic'); ?>">
												Panel Logistic </a>
										    </li>
										<?php }
									?>
								</ul>    
								<b class="caret-out"></b>                        
						    </li>
						<?php }
					?>
				    <?php
				    	if($this->session->userdata('role_prodmon') == '5' || $this->session->userdata('role_prodmon') == '6'){
				    	?>
						    <li>
								<a data-hover="dropdown" class="dropdown-toggle" href="javascript:;">
								<span class="selected"></span>
								Master
								<span class="arrow"></span>     
								</a>	
								<ul class="dropdown-menu">
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
								</ul>    
								<b class="caret-out"></b>                        
						    </li>
						<?php }
					?>
				</ul>
			</div>
		</div>
		<!-- END HORIZANTAL MENU -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
			<img src="<?php echo base_url(); ?>assets/img/menu-toggler.png" alt="" />
		</a>          
		<!-- END RESPONSIVE MENU TOGGLER -->            
		<!-- BEGIN TOP NAVIGATION MENU -->              

		<ul class="nav pull-right">
		<!-- BEGIN USER LOGIN DROPDOWN -->
			<li class="dropdown user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" src="<?php echo base_url(); ?>assets/img/user.png" />
					<span class="username"><?php echo $this->session->userdata('nik_prodmon'); ?></span>
					<i class="icon-angle-down"></i>
				</a>
				<ul class="dropdown-menu">
					<!-- <li><a href="<?php echo site_url('master/manage'); ?>"><i class="icon-user"></i> Manage Account</a></li> -->
					<li><a href="<?php echo site_url('site/logout'); ?>"><i class="icon-key"></i> Log Out</a></li>
				</ul>
			</li>
		<!-- END USER LOGIN DROPDOWN -->
		</ul>
		<!-- END TOP NAVIGATION MENU --> 
		</div>
	</div>
	<!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->