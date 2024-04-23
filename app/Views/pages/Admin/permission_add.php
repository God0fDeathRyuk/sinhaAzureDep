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
<form action="<?php if($option == 'list'){ echo "/sinhaco/admin/permission-add/add";} else{ echo '/sinhaco/admin/permission-add/edit';}?>" method="post" id="queryDetailsAdd">

<div class="frms-sec d-inline-block w-100 bg-white p-3">
  
					<div class="inptSec col-md-4 float-start pe-2 mb-3">
						<label class="d-block w-100 mb-2 fw-bold">Permission</label>
						<input type="text" name="permission_name" class="form-control w-100 me-2 float-start" value="<?= ($option=='edit') ? $data2['permission_name'] : '' ?>"/>
                        <input type="hidden" name="id" class="form-control w-100 me-2 float-start" value="<?= ($option=='edit') ? $data2['id'] : '' ?>"/>
					</div>
					<div class="float-start me-2 ms-2 mt-31">
						<input type="radio"  class="me-1" name="permission_on" id="permission_on_button"  onClick="displayPage()" value="0" <?php if($option=='edit'){ echo ($data2['permission_on']=='0') ? 'checked' : '' ;} ?>>
						<label for="grntAll">Button</label>
					</div>
					<div class="float-start mt-31 me-2" id="permission_on_menu">
						<input type="radio" class="me-1"  name="permission_on"  value="1" <?php if($option=='edit'){ echo ($data2['permission_on']=='1') ? 'checked' : '' ;} ?>>
						<label for="revcAll">Menu</label>
					</div>
					<div class="inptSec col-md-3 float-start pe-2 mb-3 <?php if($option=='edit'){ echo ($data2['permission_on']=='0') ? 'd-block' : 'd-none' ;} ?>" id="page_menu">
					<label class="d-block w-100 mb-2 fw-bold">&nbsp;</label>
					<?php $val=explode(",",   ($option=='edit') ? $data2['menu_id'] : '');
									?>
						<select class="selectpicker"  name="page_name[]" multiple aria-label="Default select example" data-live-search="true" data-placeholder="Select Pages">
						<?php foreach ($data3 as $key => $value) {
						?>
							<option value="<?= ($option!="") ? $value['menu_id'] : ''?>" <?= (in_array($value['menu_id'], $val)) ? 'selected' : '' ?>><?php echo $value['menu_desc']; ?></option>
                        <?php } ?>
						</select>
					</div>
					<div class="inptSec col-md-3 float-start pe-2 mb-3">
						<label class="d-block w-100 mb-2 fw-bold">Icon</label>
						<input type="text" name="icon" class="form-control w-100 me-2 float-start" value="<?= ($option=='edit') ? $data2['icon'] : '' ?>"/>
                       
					</div>
    
</div>

		
                <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2" >Confirm</button> 
				<?php if($option=='edit'){ ?>
				<a href="/sinhaco/admin/permission-add/" class="btn btn-primary mt-2 ms-2 btn-dark">Back</a>
				<?php } ?>
    </form>
    <div class="d-inline-block w-100 mt-4">
					<table class="table table-bordered tblhdClr">
						<tbody>
							<tr>									
								<th>
									<span>&nbsp;</span>
								</th>
								<th>
									<span class="fntSml">Description</span>
								</th>
								<th>
									<span class="fntSml">Menu</span>
								</th>
								<th>
									<span class="fntSml">Icon</span>
								</th>
							</tr>
                           <?php if($option=='list'){ foreach ($data as $key => $value) {?>
							<tr>
                                <td><a href="/sinhaco/admin/permission-add/edit?id=<?= $value['id']; ?>">Edit</a></span></td>
								<td>
									<span><?= $value['permission_name']; ?></span>
								</td>
								<td>
									<span><?php 
									$val=explode(",",  $value['menu_id']);
									$count=sizeof($val);
									for($i=0;$i<$count;$i++)
										{
											$test=$val[$i];
											foreach ($data4 as $key => $value4) 
											{										
													if($value4['menu_id']==$test)
													{
														//echo $value4['menu_id']; echo '-'; echo $test;
														echo $value4['menu_desc'];echo ',';
													}
											}
										}
											?></span>
								</td>
								<td><?php echo $value['icon']; ?></td>
								
							</tr>
							<?php } }
							else{
								?>
							<tr>
								
                                <td><a href="/admin/permission-add/edit?id=<?= $data2['id']; ?>">Edit</a></span></td>
								<td>
									<span><?= $data2['permission_name']; ?></span>
								</td>
								<td>
								<?php $val=explode(",",  $data2['menu_id']);
								//print_r($val);
								foreach ($data3 as $key => $value) { ?>
								
									<span><?php 
								echo  (in_array($value['menu_id'], $val)) ? $value['menu_desc'].',' : '' 
											?></span>
											<?php } 
								?>
								</td>
								
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>	
</main>
<?= $this->endSection() ?>