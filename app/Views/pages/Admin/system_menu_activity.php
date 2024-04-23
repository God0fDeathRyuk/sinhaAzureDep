<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<main id="main" class="main">
	
<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>
    <div class="pagetitle">
      <h1>Menu Activity</h1>      
    </div><!-- End Page Title -->
<div class="frms-sec d-inline-block w-100 bg-white p-3">
<form action="<?php if($option == 'list'){ echo "/sinhaco/admin/system-menu-activity/add";} else{ echo '/sinhaco/admin/system-menu-activity/edit';}?>" method="get" id="systemMenuActivity">
				<div class="d-inline-block w-100">
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Menu ID</label>
						<select class="form-select" name="menu_id">
                            <?php foreach($data1 as $key => $system_menu){ ?>
							<option value="<?= $system_menu['menu_id'];?>" <?php if ($option == 'edit') if($system_menu['menu_id'] == $data3['t_menu_id']){ echo "selected";}?>><?php echo $system_menu['menu_desc'];?> </option>
                            <?php } ?>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Activity</label>
						<input type="text" class="form-control w-100 float-start" name="activity_id" value="<?= ($option=='edit')? $data3['activity_id'] : ''?>" readonly/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Description</label>
						<input type="text" class="form-control w-100 float-start" name="activity_desc" value="<?= ($option=='edit')? $data3['activity_desc'] : ''?>"/>
					</div>
					<button type="submit" class="btn btn-primary cstmBtn mt-2 mb-3 ms-2">Save</button>
				</div>
				
				<div class="mntblsec">
					<table class="table table-bordered tblhdClr">
						<tr>
							<th class="fntSml"><span>Add</span></th>
							<th class="fntSml"><span>Menu ID</span></th>
							<th class="fntSml"><span>Menu Description</span></th>
							<th class="fntSml"><span>Activity ID</span></th>
							<th class="fntSml"><span>Activity Desc</span></th>
						</tr>
                        <?PHP foreach ($data as $key => $user) {?>
						<tr>
							<td class="p-2"><span> <a href="/sinhaco/admin/system-menu-activity/edit?activity_id=<?php echo $user['activity_id']?>&my_menuid=<?php echo $menu_id;?>">Edit</a></span></td>
							<td class="p-2"><span><?php echo $user['t_menu_id']?></span></td>
							<td class="p-2"><span><?php echo $user['menu_desc']?></span></td>
							<td class="p-2"><span><?php echo $user['activity_id']?></span></td>
							<td class="p-2"><span><?php echo $user['activity_desc']?></span></td>
						</tr>
                        <?php } ?>
					</table>
				</div>
			</div>
</main>
<?= $this->endSection() ?>