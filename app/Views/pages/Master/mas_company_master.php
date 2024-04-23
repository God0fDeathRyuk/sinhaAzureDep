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
<div class="pagetitle">
      <h1>Company Master [<?php echo strtoupper($option)?>]</h1>
    </div><!-- End Page Title -->
    <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-company-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-company-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-company-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				
				<div class="frms-sec-insde <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?> float-start col-md-2 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn"> Code</label>
					<input type="text" class="form-control" name="company_code" id="company_code"  placeholder="Code" value="<?= ($option!='Add')?$data['company_code']: '' ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-<?php if($option == 'Add'){echo '4'; }else{ echo '5'; }?> px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="company_name" id="company_name" placeholder="Name"required value="<?= ($option!='Add')?$data['company_name']: '' ?>" <?php if($option=='Add'){ ?> onBlur="duplicate_code_check(this.value,'<?php echo $option;?>','getTotCompanyCount')<?php }?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-<?php if($option == 'Add'){echo '3'; }else{ echo '5'; }?> float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Abbr Name <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" name="company_abbr_name" id="company_abbr_name" placeholder="Name"required value="<?= ($option!='Add')?$data['company_abbr_name']: '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
                <div class="col-md-<?php if($option == 'Add'){echo '5'; }else{ echo '12'; }?> float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Address Name <strong class="text-danger">*</strong></label>
					<textarea rows="2" class="form-control w-100" name="address_line_1" id="address_line_1" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>><?= ($option!='Add')?$data['address_line_1'].' '.$data['address_line_2'].' '.$data['address_line_3'].' '.$data['address_line_4']: '' ?></textarea>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Contact Person </label>
					<input type="text" class="form-control"  name="contact_person" id="contact_person" placeholder="Contact Person" value="<?= ($option!='Add')?$data['contact_person']: '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Phone No </label>
					<input type="tel"  pattern="[789][0-9]{9}" title="[789][0-9]{9}" placeholder="Phone No"  class="form-control" name="phone_no" id="phone_no"  value="<?= ($option!='Add')?$data['phone_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">FAX No </label>
					<input type="text" class="form-control" name="fax_no" id="fax_no" placeholder="FAX No" value="<?= ($option!='Add')?$data['fax_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">E-mail ID </label>
					<input type="email" class="form-control" name="email_id" id="email_id" placeholder="Email" value="<?= ($option!='Add')?$data['email_id']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Website</label>
					<input type="url" class="form-control" name="website" id="website" placeholder="Website" value="<?= ($option!='Add')?$data['website']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">PAN No </label>
					<input type="text" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="[A-Z]{5}[0-9]{4}[A-Z]{1}" onkeyup="javascript:(this.value=this.value.toUpperCase())"  class="form-control" name="pan_no" id="pan_no" placeholder="Phone No" value="<?= ($option!='Add')?$data['pan_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">TAN No </label>
					<input type="text" class="form-control" name="tan_no" id="tan_no" placeholder="Pan No" value="<?= ($option!='Add')?$data['tan_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">TIN No </label>
					<input type="text" class="form-control" name="tin_no" id="tin_no" placeholder="Tin No" value="<?= ($option!='Add')?$data['tin_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Status <strong class="text-danger">*</strong></label>
					<select class="form-select" required name="status_code" id="status_code" <?php echo $disview;?>>
						<option value="">---- Select -----</option>
						<option <?=  ($option!='Add')? ($data['status_code']=='A') ? 'selected' : '' : ''?> value="A" >Active</option>
						<option <?=  ($option!='Add')? ($data['status_code']=='O') ? 'selected' : '' : ''?> value="O" >Inactive</option>
					</select>
				</div>
				<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
				<input type="hidden" name="finsub" id="finsub" value="fsub">
				<div class="d-inline-block w-100">
					<button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" <?php echo $disview;?>>Save</button>
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