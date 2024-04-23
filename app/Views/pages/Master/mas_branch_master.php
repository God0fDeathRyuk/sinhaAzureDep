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
      <h1 class="col-md-8 float-start">Company Branch Master [<?php echo strtoupper($option); ?>]</h1>
	  <div class="col-md-4 float-end text-end mb-2">
					
					<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2">Back</a>
	  </div>
    </div><!-- End Page Title -->
    <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-branch-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-branch-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-branch-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
	  
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
			
				<div class="frms-sec-insde <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?> float-start col-md-3 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn"> Code</label>
					<input type="text" class="form-control" placeholder="Code" name="branch_code" id="branch_code" value="<?= ($option!='Add')? $data['branch_code']: '' ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" placeholder="Name"required  name="branch_name" id="branch_name" value="<?= ($option!='Add')? $data['branch_name']: '' ?>" <?php if($option=='Add'){ ?> onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getTotBranchCount')<?php }?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-<?php if($option == 'Add'){echo '6'; }else{ echo '3'; }?> float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Abbriviation  <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100 me-2 float-start" name="branch_abbr_name" id="branch_abbr_name" placeholder="Abbr Name" required value="<?= ($option!='Add')? $data['branch_abbr_name']: '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-<?php if($option == 'Add'){echo '6'; }else{ echo '7'; }?> float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Address  <strong class="text-danger">*</strong></label>
					<textarea rows="3" class="form-control w-100 me-2 float-start" name="address_line_1" id="address_line_1" placeholder="Address" required onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>> <?= ($option!='Add')? $data['address_line_1']: '' ?></textarea>
				</div>
				<div class="col-md-<?php if($option == 'Add'){echo '6'; }else{ echo '5'; }?> float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">City/Pin</label>					
					<input type="text" class="form-control w48 me-3 float-start"  name="city" id="city" placeholder="City" value="<?= ($option!='Add')? $data['city']: '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
					<input type="text" class="form-control w48 float-start" name="pin_code" id="pin_code" placeholder="Pin Code" value="<?= ($option!='Add')? $data['pin_code']: '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="d-inline-block w-100">
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">State <strong class="text-danger">*</strong></label>
						<select class="form-select" required name="state_code" id="state_code" <?php echo $disview;?>>
							<option value="">---- Select -----</option>
                            <?php foreach ($data1 as $value) {?>
							<option value="<?= $value['state_code']; ?>" <?= ($option!='Add')?($data['state_code']==$value['state_code'])? 'selected': '': '' ?>><?= $value['state_name'] ?></option>
						<?php }?>
                        </select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Website</label>
						<input type="text" class="form-control" placeholder="Website" name="website" id="website" value="<?= ($option!='Add')? $data['website']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Phone # </label>
						<input type="tel"  pattern="[789][0-9]{9}" title="[789][0-9]{9}" placeholder="Phone" class="form-control"  name="phone_no" id="phone_no" value="<?= ($option!='Add')? $data['phone_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">FAX # </label>
						<input type="text" class="form-control" placeholder="FAX No"  name="fax_no" value="<?= ($option!='Add')? $data['fax_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Mobile # </label>
						<input type="tel"  pattern="[789][0-9]{9}" title="[789][0-9]{9}" placeholder="Mobile"  class="form-control" placeholder="Mobile No" name="mobile_no" id="mobile_no" value="<?= ($option!='Add')? $data['mobile_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">E-mail ID </label>
						<input type="email" class="form-control" placeholder="Email" name="email_id" id="email_id" value="<?= ($option!='Add')? $data['email_id']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Contact Person<strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" placeholder="Contact Person" required  name="contact_person" id="contact_person" value="<?= ($option!='Add')? $data['contact_person']: '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Company <strong class="text-danger">*</strong> </label>
						<select class="form-select" required name="company_code" id="company_code" onchange="duplicate_code_check(this.value,'<?php echo $option;?>','getCompanyDetails')" <?php echo $disview;?>>
							<option value="">---- Select -----</option>
                            <?php foreach ($data2 as $value) {?>
							<option value="<?= $value['company_code']; ?>" <?= ($option!='Add')?($data['company_code']==$value['company_code'])?'selected': '' : '' ?>><?= $value['company_name'] ?></option>
						<?php }?>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">PAN # </label>
						<input type="text" class="form-control" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" placeholder="Pan No" name="pan_no" id="pan_no" value="<?= ($option!='Add')? $data['pan_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">TAN # </label>
						<input type="text" class="form-control" placeholder="TAN No" name="tan_no" id="tan_no" value="<?= ($option!='Add')? $data['tan_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">CST # </label>
						<input type="text" class="form-control" placeholder="CST No" name="cst_no" id="cst_no" value="<?= ($option!='Add')? $data['cst_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">LST # </label>
						<input type="text" class="form-control" placeholder="LST No" name="lst_no" id="lst_no" value="<?= ($option!='Add')? $data['lst_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">VAT # </label>
						<input type="text" class="form-control" placeholder="VAT No" name="vat_no" id="vat_no" value="<?= ($option!='Add')? $data['vat_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">TIN No </label>
						<input type="text" class="form-control" placeholder="TIN No" name="tin_no" id="tin_no" value="<?= ($option!='Add')? $data['tin_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">TDS Circle No <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" placeholder="TDS No"  name="tds_circle_no" id="tds_circle_no" value="<?= ($option!='Add')? $data['tds_circle_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Excise Regn No </label>
						<input type="text" class="form-control" placeholder="Excise Regn No" name="excise_registration_no" id="excise_registration_no" value="<?= ($option!='Add')? $data['excise_registration_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Excise Regn Dt </label>
						<input type="text" class="form-control set-date datepicker"  placeholder="Excise Regn Date" name="excise_registration_date" id="excise_registration_date" value="<?= ($option!='Add')? $data['excise_registration_date']: '' ?>" onblur="make_date(this)" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Excise Regn Type </label>
						<select class="form-select"  name="excise_registration_type" id="excise_registration_type" <?php echo $disview;?>>
							<option value="">---- Select -----</option>
							<option value="M" <?= ($option!='Add')?($data['excise_registration_type']=='M')? 'selected': '' :'' ?>>M</option>
							<option value="N" <?= ($option!='Add')?($data['excise_registration_type']=='N')? 'selected': '' :'' ?>>N</option>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Serv Tax Regn No </label>
						<input type="text" class="form-control" placeholder="Serv Tax Regn No" name="service_tax_regn_no" id="service_tax_regn_no"  value="<?= ($option!='Add')? $data['service_tax_regn_no']: '' ?>" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Serv Tax Regn Dt </label>
						<input type="text" class="form-control set-date datepicker"  placeholder="Serv Tax Regn Date" name="service_tax_regn_date" id="service_tax_regn_date" value="<?= ($option!='Add')? $data['service_tax_regn_date']: '' ?>" onblur="make_date(this)" <?php echo $redokadd;?>/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Default Access <strong class="text-danger">*</strong></label>
						<select class="form-select" required name="default_access_ind" id="default_access_ind" <?php echo $disview;?>>
							<option value="">---- Select -----</option>
							<option value="Y" <?= ($option!='Add')?($data['default_access_ind']=='Y')? 'selected': '' :'' ?>>Yes</option>
							<option value="N" <?= ($option!='Add')?($data['default_access_ind']=='N')? 'selected': '' :'' ?>>No</option>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Status <strong class="text-danger">*</strong></label>
						<select class="form-select" required name="status_code" id="status_code" <?php echo $disview;?>>
							<option value="">---- Select -----</option>
							<option value="Active" <?= ($option!='Add')?($data['status_code']=='Active')? 'selected': '' :'' ?>>Active</option>
							<option value="Inactive" <?= ($option!='Add')?($data['status_code']=='Inactive')? 'selected': '' :'' ?>>Inactive</option>
						</select>
					</div>
				</div>
				<a class="btn btn-primary float-end cstmBtn ms-2 <?php if($option == 'View'){echo 'd-none'; }?>"
                                        onclick="addSigRow()" <?php echo $disview;?>>Add</a>
				<div class="d-inline-block w-100 mt-2 mb-2 bnd">
					<span>Signatory Details </span>
				</div>
				
				<div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-3">
                    <input type="hidden" name="sigRowCount" id="sigRowCount" value="<?php echo count($data3)?>">
					
                    <table class="table table-bordered w-100" id="sigTab">
                        <tr>
                            <td>&nbsp;</td>
                            <td>Name</td>
                            <td>Designation</td>   
                        </tr>
                        <?php foreach ($data3 as $key => $value) {$key++;?>
                        <tr id="rowId<?= $key ?>">
                            <td><?= $key ?></td>
                            <td><input type="text" class="form-control float-start w-90" placeholder="Name" name="signatory_name<?php echo $key; ?>" id="signatory_name<?php echo $key; ?>" value="<?= $value['signatory_name'] ?>" <?php echo $redokadd;?>/></td>
                            <td><input type="text" class="form-control float-start w-100" placeholder="Designation" name="signatory_desg<?php echo $key; ?>" id="signatory_desg<?php echo $key; ?>" value="<?= $value['signatory_desg']?>" <?php echo $redokadd;?>/></td>                            
                        </tr>
                        <?php }?>
                    </table>
                        <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<input type="hidden" name="finsub" id="finsub" value="fsub">              
<div class="d-inline-block w-100 mt-3">
	<input type="hidden" name="finsub" id="finsub" value="fsub">
					<button type="submit" id="submit_btn" class="btn btn-primary cstmBtn ms-2" onClick="return checkdata()" <?php echo $disview;?>>Save</button>
					<?php if($option=="Delete"){?>
<button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn ms-2">Delete</button>
<?php } ?>
					<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2">Back</a>
	  </div>
				
			</div>
			
		  </div>
      </div>
    </section>
                        </form>
</main><!-- End #main -->

<?= $this->endSection() ?>