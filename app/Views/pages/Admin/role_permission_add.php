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
<div class="pagetitle col-md-5 float-start">
  <h1>Add Permission </h1>
</div><!-- End Page Title -->
<form action="<?php if($option == 'list'){ echo "/sinhaco/admin/role-permission-add/add";} else{ echo '/sinhaco/admin/role-permission-add/edit';}?>" method="POST" id="queryDetailsAdd">

<div class="frms-sec d-inline-block w-100 bg-white p-3">
					<?php if($option!='edit'){ ?> 
                    <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Role</label>
						<select class="form-select" name="role_id">
                            <?php foreach ($data as $key => $value) {?>
							<option value="<?= $value['id'];?>"><?php echo $value['role_name']; ?></option>
                            <?php } ?>
						</select>
					</div>	
                    <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4 mltiSlct">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Permission</label>
						<select class="selectpicker" name="permission_name[]" multiple aria-label="Default select example" data-live-search="true" data-placeholder="Permission"


>
						<?php foreach ($data1 as $key => $value) {?>
							<option value="<?= $value['id'];?>" ><?php echo $value['permission_name']; ?></option>
                            <?php } ?>
						</select>
					</div>	
					<?php }if($option=='edit'){ foreach ($data2 as $key => $value2) { ?>
						<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Role</label>
						<select class="form-select" name="role_id">
                            <?php foreach ($data as $key => $value) {?>
							<option value="<?= $value['id'];?>" <?php if($value2['role']==$value['id']){ echo 'selected';} ?>><?php echo $value['role_name']; ?></option>
                            <?php } ?>
						</select>
					</div>	
                    <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4 mltiSlct">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Permission</label>
						<select class="selectpicker" name="permission_name[]" multiple aria-label="Default select example" data-live-search="true" data-placeholder="Permission"

								<?php $val=explode(",",  $value2['permission']);
									?>
>
                            <?php foreach ($data1 as $value1) {
								
								?>
							<option value="<?= $value1['id'];?>" <?= (in_array($value1['id'], $val)) ? 'selected' : '' ?>><?php echo $value1['permission_name']; ?></option>
                            <?php }  ?>
						</select>
						<input type="hidden" name="id"  value="<?= $value2['id']; ?>" >
					</div>
					
					<?php } }?>
    
</div>		
                <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2" >Confirm</button> 
			<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</button>
    </form>
    <div class="d-inline-block w-100 mt-4">
					<table class="table table-bordered tblhdClr">
						<tbody>
							<tr>									
								<th>
									<span>&nbsp;</span>
								</th>
								<th>
									<span class="fntSml">Role</span>
								</th>

								<th>
									<span class="fntSml">Permission</span>
								</th>
							</tr>
							
                          <?php  foreach ($data2 as $key => $value) { ?>
							
							<tr>
                                <td><a href="/sinhaco/admin/role-permission-add/edit?id=<?php echo $value['id']?>">Edit</a></span></td>
								<td>
									<span><?php echo $value['role_name'] ?></span>
								</td>
								<td>
									<span><?php 
									$val=explode(",",  $value['permission']);
									$count=sizeof($val);
									for($i=0;$i<$count;$i++)
										{
											$test=$val[$i];
											foreach ($data1 as $key => $value1) 
											{										
													if($value1['id']==$test)
													{
														echo $value1['permission_name'];echo ',';
													}
											}
										}
											?></span>
								</td>
								
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>	
</main>
<?= $this->endSection() ?>