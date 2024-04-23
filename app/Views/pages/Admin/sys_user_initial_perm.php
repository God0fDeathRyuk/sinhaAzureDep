<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<main id="main" class="main">
<div class="pagetitle">
      <h1>User-vs-Initial Details</h1>      
</div><!-- End Page Title -->

	
<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>
<section class="section dashboard d-block">
    <form action="/sinhaco/admin/sys-user-initial-perm/list" method="get">
      <div class="row <?php if($option=='edit' || $default_access_ind!=''){ echo 'd-none';}else{ echo 'd-block';}?>">
		  <div class="col-md-12 mt-1">
          <div class="d-inline-block w-100">
					<div class="col-md-4 float-start px-2 position-relative mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">User </label>
						<select class="form-select" name="default_access_ind">
                        <?php foreach ($data as $key => $value) 
                              {?>
                                <option value="<?php echo $value['user_id']?>" <?php if($value['user_id']==$default_access_ind){ echo 'selected';}  ?>><?= $value['user_name']?></option>
                        <?php } ?>
                        </select>
						<input type="hidden" name="menu_id" value="<?= $menu_id; ?>" >
					</div>	
					<button type="submit" class="btn btn-primary cstmBtn mt-31 ms-3">Proceed</button>	
                    <button type="button" class="btn btn-primary cstmBtn mt-31 ms-3">Back</button>									
				</div>
          </div>
      </div>
    </form>
</section>
<section class="section dashboard <?php if($option=='edit' || $default_access_ind!=''){ echo 'd-block';}else{ echo 'd-none';}?>">
<?php $initial_Name=''; foreach ($data2 as $key => $value2) 
                               { $initial_Name= $value2['user_name']; } ?>
      <div class="row">
		  <div class="col-md-12 mt-1">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="d-inline-block w-100">
					<div class="col-md-6 float-start px-2 position-relative mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">User</label>
						<input type="text" class="form-control w-35 float-start" name="user_id" value="<?= ($default_access_ind!='') ? $default_access_ind : $user_id ?>">
						
						<input type="text" class="form-control w-63 ms-2 float-start" name="user_name"  value="<?=$initial_Name; ?>">
						
					</div>
					<?php if($option!='edit'){ ?>	
					<a type="submit" class="btn btn-primary cstmBtn mt-31 ms-3" href="/sinhaco/admin/sys-user-initial-perm/edit?user_id=<?php echo ($default_access_ind!='') ? $default_access_ind : $user_id?>&user_name=<?php echo $initial_Name?>&menu_id=<?php echo $menu_id?>">&nbsp;<b>Add/Update</b></a>
					<?php } ?>
				</div>	
				<div class="<?php if($option!='edit'){ echo 'd-none';}else{ echo 'd-block';} ?> float-start w-25 mt-31 ms-4" id="sysmenuperm">
						<div class="float-start me-4">
							<input type="radio"  class="me-1" onClick="return grant_all();">
							<label for="grntAll">Grant All</label>
						</div>
						<div class="float-start">
							<input type="radio"  onClick="return revoke_all();" class="me-1"  >
							<label for="revcAll">Revoke All</label>
						</div>
					</div>							
			</div>
			<form action="/sinhaco/admin/sys-user-initial-perm/edit" method="post">
				<div class="d-inline-block w-100 mt-4">
					<table class="table table-bordered tblhdClr">
						<tbody>
							<tr>									
								<th>
									<span>Initial</span>
								</th>
								<th>
									<span class="fntSml">Initial Name</span>
								</th>
								<th class="text-center">
									<span class="fntSml">Allowed</span>
								</th>
							</tr>
                            <?php $count=0;foreach ($data1 as $key => $value1) { $count=$count+1;
								$initial_perm_value = strtoupper($value1['initial_perm']);
								if($initial_perm_value=='Y') {$initial_checked = 'checked';} else {$initial_checked = '' ; } 
								?>
							<tr>
								<td>
									<span><?= ($option=='edit' || $default_access_ind!='') ? $value1['initial_code'] : '' ?>
									<input type="hidden" name="userId" value="<?= $user_id?>">
									
									<input type="hidden" name="initial_code<?php echo $count;?>" value="<?= ($option=='edit' || $default_access_ind!='') ? $value1['initial_code'] : '' ?>">
									</span>
								</td>
								<td class="w-63">
									<span><?= ($option=='edit' || $default_access_ind!='') ? $value1['initial_name'] : '' ?>
									<input type="hidden" name="initial_name<?php echo $count;?>" value="<?= ($option=='edit' || $default_access_ind!='') ? $value1['initial_name'] : '' ?>">
								    </span>
								</td>
								<td style="width:5%;" class="text-center">
									<input type="checkbox" name="initial_perm<?php echo $count;?>" id="initial_perm<?php echo $count; ?>" value="<?= ($option!='list') ? $value1['initial_perm'] : '' ?>" <?php echo $initial_checked; if($option!='edit'){ echo' disabled';}?> onclick="cchange(<?php echo $count;?>)"/>
								</td>
							</tr>
                            <?php } ?>
						</tbody>
					</table>
				</div>					
				<button type="submit" class="btn btn-primary cstmBtn mt-1">Done</button>	
				<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-1">Back</button>	
				<input type="hidden"  name="total_row" id="total_row"  value="<?php echo $data3['cnt'];?>">	
			</form>
		  </div>
		
      </div>
    </section>

  </main><!-- End #main -->
<?= $this->endSection() ?>