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
<div class="pagetitle w-100 float-start border-bottom pb-1">
      <h1 class="col-md-8 float-start">Initial Master [<?php echo strtoupper($option) ?>]</h1>
    </div><!-- End Page Title -->
    <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-initial-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-initial-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-initial-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
        <?php $v=($option!='Add')?'':$data1['maxValue'] ;
        $v++;?>
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Initial <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100 float-start" required name="initial_code" id="initial_code" value="<?= ($option!='Add')?$data1['initial_code']: $v ?>" readonly/>
				</div>
				<div class="col-md-8 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Name  <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100 float-start" placeholder="Name" required  name="initial_name" id="initial_name" value="<?= ($option!='Add')?$data1['initial_name']:'' ?>"  onkeyup="javascript:(this.value=this.value.toUpperCase());" <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-5 float-start px-2 mb-3 h100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Address  <strong class="text-danger">*</strong></label>
					<textarea rows="3" class="form-control w-100 float-start" placeholder="Address" required name="address_line_1" id="address_line_1"  onkeyup="javascript:(this.value=this.value.toUpperCase());" <?php echo $redokadd;?>><?= ($option!='Add')?$data1['address_line_1']:'' ?></textarea>
				</div>
				<div class="col-md-4 float-start px-2 mb-3 h100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">City</label>
					<input type="text" class="form-control w-100 float-start" placeholder="City" name="city" id="city" value="<?= ($option!='Add')?$data1['city']: '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase());" <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-3 h100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Pin</label>
					<input type="text" class="form-control w-100 float-start" placeholder="Pin" name="pin_code" id="pin_code" value="<?= ($option!='Add')?$data1['pin_code']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				
				<div class="col-md-4 float-start px-2 mt-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Mobile</label>
					<input type="text" class="form-control w-100 float-start" placeholder="Mobile" name="mobile_no" id="mobile_no" value="<?= ($option!='Add')?$data1['mobile_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-4 float-start px-2 mt-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Date of Joining</label>
					<input type="text" class="form-control w-100 float-start datepicker" name="dt_of_join" id="dt_of_join" value="<?= ($option!='Add')?$data1['dt_of_join']:'' ?>" onblur="make_date(this)" <?php echo $redokadd;?>/>
				</div>				
				
				<div class="col-md-4 float-start px-2 mt-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Status <strong class="text-danger">*</strong></label>
					<select class="form-select" required name="status_code" id="status_code" <?php echo $disview;?>>
					<option value="">Select</option>
                        <?php foreach ($data as $value) {?>
						<option value="<?= $value['status_code'] ?>" <?= ($option!='Add')?($data1['status_code']==$value['status_code'])?'selected':'':'' ?>><?= $value['status_code'] ?></option>
                        <?php }?>
					</select>
				</div>				
				<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<input type="hidden" name="finsub" id="finsub" value="fsub">
<div class="d-inline-block w-100 mt-3">
<button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" onclick="data_check_initial()" <?php echo $disview;?>>Save</button>				
<?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
                        <?php } ?>
<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
</div>
			</div>
			
		</div>
		
      </div>
    </section>
    </form>
</main><!-- End #main -->

<?= $this->endSection() ?>