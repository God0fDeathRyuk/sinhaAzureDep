<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php 
  isset($_REQUEST['option'])?$user_option = $_REQUEST['option']:$user_option = 'Add';
if($user_option == 'Edit')
  {
    $sele_stmt = "select * from system_menu where serial_no = '$serial_no' order by menu_id ";
    $sele_row  = mysql_fetch_array(mysql_query($sele_stmt,$link));
    $menu_head                  = $sele_row['menu_head'];
    $t_menu_id                  = $sele_row['menu_id'];
    $menu_type                  = $sele_row['menu_type'];
    $menu_desc                  = $sele_row['menu_desc'];
    $menu_prog                  = $sele_row['menu_prog'];
    $params                     = $sele_row['params'];
    $program_screen_header_name = $sele_row['program_screen_header_name'];
    $prog_help_id               = $sele_row['program_help_id'];
    $default_access_ind         = $sele_row['default_access_ind'];
    $program_screen_ref_no      = $sele_row['program_screen_ref_no'];
    $status_code                = $sele_row['status_code'];
  }
  else
  {
    $menu_head                  = '';
    $t_menu_id                  = '';
    $menu_type                  = '';
    $menu_desc                  = '';
    $menu_prog                  = '';
    $params                     = '';
    $program_screen_header_name = '';
    $prog_help_id               = '';
    $default_access_ind         = '';
    $program_screen_ref_no      = '';
    $status_code                = '';
  } 
  ?>
<main id="main" class="main">
	
<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>

<div class="pagetitle">
      <h1>Menu Details</h1>      
    </div><!-- End Page Title -->
	<div class="frms-sec d-inline-block w-100 bg-white p-3">
	<form action="<?php if($option == 'list'){ echo "/sinhaco/admin/system-menu/add";} else{ echo '/sinhaco/admin/system-menu/edit';}?>" method="get" id="systemMenu">
				<div class="d-inline-block w-100">
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Menu Head</label>
						<input type="text"  name="menu_head" value="<?= ($option == 'edit') ? $data1['menu_head'] :  ''?>" class="form-control"/>
						<input type="hidden"  name="serial_no" value="<?= ($option == 'edit')? $_REQUEST['serial_no'] : '' ?>" class="form-control"/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Menu Id</label>
						<input type="text" name="t_menu_id" value="<?= ($option == 'edit') ? $data1['t_menu_id'] :  ''?>" class="form-control"/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Menu Description</label>
						<input type="text" name="menu_desc" value="<?= ($option == 'edit') ? $data1['menu_desc'] :  ''?>" class="form-control"/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Menu Type</label>
						<select class="form-select" name="menu_type">
							<option <?php if ($option == 'edit') if($data1['menu_type'] == "Menu") echo "selected";?> value="M">Menu</option>
							<option <?php if ($option == 'edit') if($data1['menu_type'] == "Program") echo "selected";?> value="P">Program</option>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Programe help</label>
						<input type="text" name="program_help_id" value="<?= ($option == 'edit') ? $data1['prog_help_id'] :  ''?>" class="form-control"/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Menu Programe</label>
						<input type="text" name="menu_prog" value="<?= ($option == 'edit') ? $data1['menu_prog'] :  ''?>" class="form-control"/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Section Header</label>
						<input type="text" name="program_screen_header_name" value="<?= ($option == 'edit') ? $data1['program_screen_header_name'] :  ''?>" class="form-control"/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Parameters</label>
						<input type="text" name="params" value="<?= ($option == 'edit') ? $data1['params'] :  ''?>" class="form-control"/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Access</label>
						<select class="form-select" name="default_access_ind">
							<option <?php if ($option == 'edit') if($data1['default_access_ind'] == "Y") echo "selected";?> value="Y">Yes</option>
							<option <?php if ($option == 'edit') if($data1['default_access_ind'] == "N") echo "selected";?> value="N">No</option>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Status</label>
						<select class="form-select" name="status_code">
							<option <?php if ($option == 'edit') if($data1['status_code'] == "Active") echo "selected";?> value="Active">Active</option>
							<option <?php if ($option == 'edit') if($data1['status_code'] == "Closed") echo "selected";?> value="Closed">Closed</option>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Screen Ref#</label>
						<input type="text" name="program_screen_ref_no" value="<?= ($option == 'edit') ? $data1['program_screen_ref_no'] : '' ?>" class="form-control"/>
					</div>
														
				</div>
				<button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="submitSystemMenuForm()">Save</button> 
				<button type="submit" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back </button>
   </form>
				
				
				<div class="d-inline-block w-100 tblMnNoscrl  mt-4">
					<table class="table table-bordered tblhdClr">
						<tbody>
							<tr>									
								<th>
									<?php   isset($_REQUEST['menu_id'])?$my_menuid = $_REQUEST['menu_id']:$my_menuid = ''; ?>
									<span class="fntSml"> <a href="/sinhaco/admin/system-menu/add"><font color="white">&nbsp;<b>Add</b></font></a></span>
								</th>
								<th>
									<span class="fntSml"><a href="/sinhaco/admin/system-menu/list?index_order=<?php echo 'menu_head';?>&menu_id=<?php echo $my_menuid;?>"><font color="white">&nbsp;<b>Head</b></font></a></span>
								</th>
								<th>
									<span class="fntSml"><a href="/sinhaco/admin/system-menu/list?index_order=<?php echo 'menu_desc';?>&menu_id=<?php echo $my_menuid;?>"><font color="white">&nbsp;<b>Description</b></font></a></span>
								</th>
								<th>
									<span class="fntSml"><a href="/sinhaco/admin/system-menu/list?index_order=<?php echo 'menu_id';?>&menu_id=<?php echo $my_menuid;?>"><font color="white">&nbsp;<b>ID</b></font></a></span>
								</th>
								<th>
									<span class="fntSml"><a href="/sinhaco/admin/system-menu/list?index_order=<?php echo 'menu_prog';?>&menu_id=<?php echo $my_menuid;?>"><font color="white">&nbsp;<b>Program</b></font></a></span>
								</th>
								<th>
									<span class="fntSml"><a href="/sinhaco/admin/system-menu/list?index_order=<?php echo 'menu_type';?>&menu_id=<?php echo $my_menuid;?>"><font color="white">&nbsp;<b>Type</b></font></a></span>
								</th>
								<th>
									<span><a href="/sinhaco/admin/system-menu/list?index_order=<?php echo 'params';?>&menu_id=<?php echo $my_menuid;?>"><font color="white">&nbsp;<b>Params</b></font></a></span>
								</th>
							</tr>
                            <?php  
							if($option=='edit'){
				?>
							<tr>
								<td>
								<a href="/sinhaco/admin/system-menu/edit?serial_no=<?php echo $data1['serial_no'];?>&my_menuid=<?php echo $data1['t_menu_id'];?>"><b>Edit</b></a>
								</td>
								<td>
									<span><?= $data1['menu_head'] ?></span>
								</td>
								<td>
									<span><?= $data1['menu_desc'] ?></span>
								</td>
								<td>
									<span><?= $data1['t_menu_id'] ?></span>
								</td>
								<td class="brkwrd" style="width:30%;">
									<span><?= $data1['menu_prog'] ?></span>
								</td>
								<td>
									<span><?= $data1['menu_type'] ?></span>
								</td>
								<td style="width:10%;">
									<span><?= $data1['params'] ?></span>
								</td>
							</tr>
                            <?php }
           ?>
		   <?php  
							if($option=='edit'){
            foreach ($data as $key => $user) { 
				?>
							<tr>
								<td>
								<a href="/sinhaco/admin/system-menu/edit?serial_no=<?php echo $user['serial_no'];?>&my_menuid=<?php echo $user['t_menu_id'];?>"><b>Edit</b></a>
								</td>
								<td>
									<span><?= $user['menu_head'] ?></span>
								</td>
								<td>
									<span><?= $user['menu_desc'] ?></span>
								</td>
								<td>
									<span><?= $user['t_menu_id'] ?></span>
								</td>
								<td class="brkwrd" style="width:30%;">
									<span><?= $user['menu_prog'] ?></span>
								</td>
								<td>
									<span><?= $user['menu_type'] ?></span>
								</td>
								<td style="width:10%;">
									<span><?= $user['params'] ?></span>
								</td>
							</tr>
                            <?php }}
           ?>
		   <?php  
							if($option!='edit'){
            foreach ($data as $key => $user) { 
				?>
							<tr>
								<td>
								<a href="/sinhaco/admin/system-menu/edit?serial_no=<?php echo $user['serial_no'];?>&my_menuid=<?php echo $user['t_menu_id'];?>"><b>Edit</b></a>
								</td>
								<td>
									<span><?= $user['menu_head'] ?></span>
								</td>
								<td>
									<span><?= $user['menu_desc'] ?></span>
								</td>
								<td>
									<span><?= $user['t_menu_id'] ?></span>
								</td>
								<td class="brkwrd" style="width:30%;">
									<span><?= $user['menu_prog'] ?></span>
								</td>
								<td>
									<span><?= $user['menu_type'] ?></span>
								</td>
								<td style="width:10%;">
									<span><?= $user['params'] ?></span>
								</td>
							</tr>
                            <?php }}
           ?>
            	
						</tbody>
					</table>
				</div>					
				<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Close</button>		
		  </div>
</main>
<?= $this->endSection() ?>