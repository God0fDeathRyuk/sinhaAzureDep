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
      <h1>Consultant [Add]</h1>
    </div><!-- End Page Title -->
    <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-consultant-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-consultant-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-consultant-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn"> Code</label>
					<input type="text" class="form-control" placeholder="Code" name="consultant_code" id="consultant_code" value="<?= ($option!='Add')?$data['consultant_code']:'' ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-8 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" placeholder="Name" name="consultant_name" id="consultant_name" required value="<?= ($option!='Add')?$data['consultant_name']:'' ?>" onkeyup="(this.value=this.value.toUpperCase())" <?php if ($option == 'Add') {?>	onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getConsuNo')" <?php }?> <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-12 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Address  <strong class="text-danger">*</strong></label>
					<textarea rows="3" class="form-control w-100 me-2 float-start" placeholder="Address" name="address_line_1" id="address_line_1" required onkeyup="(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>><?= ($option!='Add')?$data['address_line_1'].$data['address_line_2'].$data['address_line_3'].$data['address_line_4']:'' ?></textarea>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">City</label>					
					<input type="text" class="form-control w-100 float-start" placeholder="City" name="city" id="city" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['city']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Pin</label>					
					<input type="text" class="form-control w-100 float-start" placeholder="Pin" name="pin_code" id="pin_code" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['pin_code']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">State <strong class="text-danger">*</strong></label>
					<select class="form-select"  name="state_code" id="state_code" required <?php echo $disview;?>>
						<option value="">---- Select -----</option>
						<?php foreach ($data1 as  $value) {?>
						<option value="<?= $value['state_code'] ?>" <?= ($option!='Add')?($data['state_code']==$value['state_code'])? 'selected': '': '' ?>><?= $value['state_name']?></option>
                        <?php } ?>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Country <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100 float-start" placeholder="Country"  name="country" id="country" required  onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['country']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Service Nature</label>
					<input type="text" class="form-control w-100 float-start" placeholder="Service Nature" name="nature_of_service" id="nature_of_service" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['nature_of_service']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Credit Days</label>
					<input type="text" class="form-control w-100 float-start" placeholder="Credit Day" name="credit_days" id="credit_days" onkeyup="(this.value=this.value.toUpperCase())"  value="<?= ($option!='Add')?$data['credit_days']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Status <strong class="text-danger">*</strong></label>
					<select class="form-select" name="status_code" id="status_code" required <?php echo $disview;?>>
						<option value="">---- Select -----</option>
                        <option value="Active" <?= ($option!='Add')?($data['status_code'] == 'Active')?'selected':'' :''?>>Active</option>
                        <option value="Old"  <?= ($option!='Add')?($data['status_code'] == 'Old')?'selected':'' :''?>>Old</option>
					</select>
				</div>
				
				
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Contact Person</label>
					<input type="text" class="form-control" placeholder="Contact Person"  name="contact_person" id="contact_person" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['contact_person']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">E-mail #</label>
					<input type="email" class="form-control" placeholder="Email" name="email_id" id="email_id" value="<?= ($option!='Add')?$data['email_id']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Phone # </label>
					<input type="tel" pattern="[789][0-9]{9}" class="form-control" placeholder="Phone No" name="phone_no" id="phone_no" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['phone_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">FAX # </label>
					<input type="text" class="form-control" placeholder="FAX No"  name="fax_no" id="fax_no" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['fax_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Mobile # </label>
					<input type="tel" pattern="[789][0-9]{9}" class="form-control" placeholder="Mobile No" name="mobile_no" id="mobile_no" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['mobile_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>				
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Communication<strong class="text-danger">*</strong></label>
					<select class="form-select" name="default_comm_mode" id="default_comm_mode" required <?php echo $disview;?>>
						<option value="">---- Select -----</option>
                        <option value="E" <?= ($option!='Add')?($data['default_comm_mode'] == 'E')?'selected':'':'' ?>>Email</option>
                        <option value="F" <?= ($option!='Add')?($data['default_comm_mode'] == 'F')?'selected':'':'' ?>>FAX</option>
                        <option value="T" <?= ($option!='Add')?($data['default_comm_mode'] == 'T')?'selected':'':'' ?>>Telephone</option>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">PAN # </label>
					<input type="text" class="form-control" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" placeholder="PAN No" name="pan_no" id="pan_no" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['pan_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">TAN # </label>
					<input type="text" class="form-control" placeholder="TAN No" name="tan_no" id="tan_no" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['tan_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">TIN # </label>
					<input type="text" class="form-control" placeholder="TIN No" name="tin_no" id="tin_no" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['tin_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">VAT # </label>
					<input type="text" class="form-control" placeholder="VAT No" name="vat_no" id="vat_no" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['vat_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>	
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Serv. Tax Regn # </label>
					<input type="text" class="form-control" placeholder="Serv. Tax Regn No" name="service_tax_regn_no" id="service_tax_regn_no" onkeyup="(this.value=this.value.toUpperCase())" value="<?= ($option!='Add')?$data['service_tax_regn_no']:'' ?>" <?php echo $redokadd;?>/>
				</div>
                        <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
				<div class="d-inline-block w-100">
					<button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" <?php echo $disview;?>>Save</button>
					<input type="hidden" name="finsub" id="finsub" value="fsub">
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
