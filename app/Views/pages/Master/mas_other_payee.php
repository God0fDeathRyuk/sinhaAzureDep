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

<div class="pagetitle d-block float-start col-md-12">
      <h1>Other Master [<?php echo strtoupper($option) ?>]</h1>
    </div><!-- End Page Title -->
<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-other-payee?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-other-payee?user_option=Edit';} if($option == 'Delete'){ echo '/sinhaco/master/mas-other-payee?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-3">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-4 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Code </label>
					<input type="text" class="form-control"  placeholder="Code" name="other_payee_code" id="other_payee_code" value="<?= ($option!='Add')?$data['other_payee_code']:''  ?>" readonly />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" placeholder="Name" name="other_payee_name" id="other_payee_name" value="<?= ($option!='Add')?$data['other_payee_name']: '' ?>" required onkeyup="javascript:(this.value=this.value.toUpperCase())"  <?php if ($option == 'Add') {?>	onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getotherMasterNo')" <?php }?> <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-<?php if($option == 'Add'){echo '7'; }else{ echo '5'; }?> px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Address</label>
					<textarea rows="2" class="form-control"name="address_line_1" id="address_line_1" placeholder="Address" <?php echo $redokadd;?>><?= ($option!='Add')?$data['address_line_1'].$data['address_line_2'].$data['address_line_3'].$data['address_line_4']: '' ?></textarea>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">City</label>
					<input type="text" class="form-control" id="city" name="city" placeholder="City" value="<?= ($option!='Add')?$data['city']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Pin </label>
					<input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="Pin" value="<?= ($option!='Add')?$data['pin_code']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">State </label>
					<select class="form-select" name="state_code" id="state_code" <?php echo $disview;?> >
						<option>-- Select --</option>
                        <?php foreach ($data1 as  $value) {?>
						<option value="<?= $value['state_code'] ?>" <?= ($option!='Add')?($data['state_code']==$value['state_code'])? 'selected': '': '' ?>><?= $value['state_name']?></option>
                        <?php } ?>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Country</label>
					<input type="text" class="form-control" id="country" name="country" placeholder="Country" value="<?= ($option!='Add')?$data['country']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Phone No</label>
					<input type="tel" pattern="[7-9]{1}[0-9]{9}" placeholder="Phone No" class="form-control" id="phone_no" name="phone_no"  value="<?= ($option!='Add')?$data['phone_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Fax</label>
					<input type="text" class="form-control" id="fax_no" name="fax_no" placeholder="Fax" value="<?= ($option!='Add')?$data['fax_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Mobile No</label>
					<input type="tel" pattern="[7-9]{1}[0-9]{9}" placeholder="Mobile No" class="form-control" id="mobile_no" name="mobile_no"  value="<?= ($option!='Add')?$data['mobile_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
					<input type="email" class="form-control" id="email_id" name="email_id" placeholder="Email" value="<?= ($option!='Add')?$data['email_id']: '' ?>" <?php echo $redokadd;?>/>
				</div>				
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn text-uppercase">Pan</label>
					<input type="text" class="form-control" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" id="pan_no" name="pan_no" placeholder="Pan" value="<?= ($option!='Add')?$data['pan_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Status <strong class="text-danger">*</strong></label>
					<select class="form-select" name="status_code" id="status_code" required <?php echo $disview;?>>
                    <option value="A" <?= ($option!='Add')?($data['status_code'] == 'A')? 'selected': '' : ''?>>Active</option>
                    <option value="O" <?= ($option!='Add')?($data['status_code'] == 'O')? 'selected': '' : ''?>>Old</option>
					</select>
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
      </div>
    </section>
</form>

</main><!-- End #main -->
<?= $this->endSection() ?>