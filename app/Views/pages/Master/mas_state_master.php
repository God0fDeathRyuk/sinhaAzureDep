<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<main id="main" class="main">
<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>
<div class="pagetitle d-block float-start col-md-9">
      <h1>State Master [<?php echo strtoupper($option) ?>]</h1>      
    </div><!-- End Page Title -->
	
	<div class="col-md-3 float-start text-end">
      <a href="javascript:void(0);" class="btn btn-primary cstmBtn mb-3">Active</a>      
    </div>
	<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-state-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-state-master?user_option=Edit';} if($option == 'Delete'){ echo '/sinhaco/master/mas-state-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-3">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn">State Code</label>
					<input type="text" class="form-control w-100" placeholder="State Code" name="state_code" id="state_code" readonly value="<?= ($option!='Add')? $data['state_code']:'' ?>"/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">State Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100" placeholder="State Name" required  name="state_name" id="state_name" value="<?= ($option!='Add')?$data['state_name']: ''?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">GST State Code <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100" placeholder="GST State Code" required name="gst_zone_code" id="gst_zone_code"   value="<?= ($option!='Add')?$data['gst_zone_code']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Zone <strong class="text-danger">*</strong></label>
					<select class="form-select" name="zone_code" id="zone_code" <?php echo $disview;?>>
                        <option value="">Select</option>
                        <?php foreach ($data1 as  $value) {?>
						<option value="<?= $value['code_code']?>" <?= ($option!='Add')?($data['zone_code']==$value['code_code'])? 'selected': '' : '' ?>><?= $value['code_desc']?></option>
                        <?php } ?>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Country <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100" placeholder="Country" required  name="country" id="country" value="<?= ($option!='Add')?$data['country']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				
			</div>		
		  </div>
		  <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<input type="hidden" name="finsub" id="finsub" value="fsub">
		<div class="col-md-12 d-inline-block">
			<button type="submit" class="btn btn-primary cstmBtn mt-3" <?php echo $disview;?>>Save</button>
			<?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
                        <?php } ?>  
			<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
		</div>
      </div>
    </section>
</form>
</main><!-- End #main -->

<?= $this->endSection() ?>