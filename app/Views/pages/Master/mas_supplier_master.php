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
      <h1>Supplier Master [<?php echo strtoupper($option) ?>]</h1>
    </div><!-- End Page Title -->
	
	<div class="col-md-3 float-start text-end">
      <a href="javascript:void(0);" class="btn btn-primary cstmBtn mb-3">Active</a>      
    </div>
	<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-supplier-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-supplier-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-supplier-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-3">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-4 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Code </label>
					<input type="text" class="form-control" id="supplier_code" name="supplier_code" placeholder="Code" value="<?= ($option!='Add')?$data['supplier_code']:'' ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Name" required  value="<?= ($option!='Add')?$data['supplier_name']:'' ?>" <?php if ($option == 'Add') {?>  onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getsupNo')" <?php }?> onKeyUp="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-<?php if($option == 'Add'){echo '7'; }else{ echo '5'; }?> px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Address<strong class="text-danger">*</strong></label>
					<textarea rows="3" class="form-control" id="address_line_1" name="address_line_1" placeholder="Address" onKeyUp="javascript:(this.value=this.value.toUpperCase())" required <?php echo $redokadd;?>><?= ($option!='Add')?$data['address_line_1'].$data['address_line_2'].$data['address_line_3'].$data['address_line_4']:'' ?></textarea>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">City  <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" id="city" name="city" placeholder="city" required value="<?= ($option!='Add')?$data['city']:'' ?>" onKeyUp="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Pin </label>
					<input type="text" class="form-control" id="pin_code" name="pin_code"  placeholder="PIN Code" value="<?= ($option!='Add')?$data['pin_code']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">State <strong class="text-danger">*</strong></label>
					<select class="form-select" id="state_code" name="state_code" <?php echo $disview;?> required>
						<option value="">-- Select --</option>
                        <?php foreach ($data2 as $value) {?>
						<option value="<?= $value['state_code'] ?>" <?= ($option!='Add')?($data['state_code']==$value['state_code'])?'selected':'':'' ?>><?= $value['state_name'] ?></option>
                        <?php } ?>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Country  <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" id="country" name="country" placeholder="Country" required value="<?= ($option!='Add')?$data['country']:'' ?>" onKeyUp="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Service Nature</label>
					<input type="text" class="form-control" id="nature_of_service" name="nature_of_service" placeholder="Service Nature" value="<?= ($option!='Add')?$data['nature_of_service']:'' ?>" onKeyUp="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Credit Days</label>
					<input type="text" class="form-control" id="credit_days" name="credit_days" placeholder="Credit Days" value="<?= ($option!='Add')?$data['credit_days']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Status <strong class="text-danger">*</strong></label>
					<select class="form-select" name="status_code" id="status_code" required <?php echo $disview;?>>
                    <option value="">-- Select --</option>
						<option value="Active" <?= ($option!='Add')?($data['status_code']=='Active')?'selected':'':'' ?>>Active</option>
						<option value="Inactive"  <?= ($option!='Add')?($data['status_code']=='Inactive')?'selected':'':'' ?>>Inactive</option>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Contact Person</label>
					<input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="Contact Person" value="<?= ($option!='Add')?$data['contact_person']:'' ?>" onKeyUp="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Email# <strong class="text-danger">*</strong></label>
					<input type="email" class="form-control" id="email_id" name="email_id" placeholder="Email" value="<?= ($option!='Add')?$data['email_id']:'' ?>" required <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Phone#</label>
					<input type="tel" pattern="[7-9]{1}[0-9]{9}" placeholder="Phone" class="form-control" id="phone_no" name="phone_no"  value="<?= ($option!='Add')?$data['phone_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Fax#</label>
					<input type="text" class="form-control" id="fax_no" name="fax_no" placeholder="Fax" value="<?= ($option!='Add')?$data['fax_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Mobile#</label>
					<input type="tel" pattern="[7-9]{1}[0-9]{9}" placeholder="Mobile" class="form-control" id="mobile_no" name="mobile_no" value="<?= ($option!='Add')?$data['mobile_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Communication <strong class="text-danger">*</strong></label>
					<select class="form-select" name="default_comm_mode" id="default_comm_mode" required <?php echo $disview;?>>
						<option value="">--- Select ---</option>
                        <option value="E" <?= ($option!='Add')?($data['default_comm_mode']=='E')?'selected':'':'' ?>>Email</option>
                        <option value="F" <?= ($option!='Add')?($data['default_comm_mode']=='F')?'selected':'':'' ?>>FAX</option>
                        <option value="T" <?= ($option!='Add')?($data['default_comm_mode']=='T')?'selected':'':'' ?>>Telephone</option>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn text-uppercase">Pan#</label>
					<input type="text" class="form-control" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" id="pan_no" name="pan_no" placeholder="Pan" value="<?= ($option!='Add')?$data['pan_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn text-uppercase">Tan#</label>
					<input type="text" class="form-control" id="tan_no" name="tan_no" placeholder="Tan" value="<?= ($option!='Add')?$data['tan_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn text-uppercase">Tin#</label>
					<input type="text" class="form-control" id="tin_no" name="tin_no" placeholder="Tin" value="<?= ($option!='Add')?$data['tin_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn text-uppercase">Vat#</label>
					<input type="text" class="form-control" id="vat_no" name="vat_no" placeholder="Vat" value="<?= ($option!='Add')?$data['vat_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Serv. Tax Reg#</label>
					<input type="text" class="form-control" id="service_tax_regn_no" name="service_tax_regn_no" placeholder="Serv. Tax Reg" value="<?= ($option!='Add')?$data['service_tax_regn_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>				
			</div>
                        <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>" />
						<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
						<input type="hidden" name="finsub" id="finsub" value="fsub">
			<div class="col-md-12 d-inline-block">
				<button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="return supp_check()" <?php echo $disview;?>>Save</button>
				<?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
                        <?php } ?>
				<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
			</div>			
		  </div>
      </div>
    </section>
</form>
</main><!-- End #main -->

<?= $this->endSection() ?>