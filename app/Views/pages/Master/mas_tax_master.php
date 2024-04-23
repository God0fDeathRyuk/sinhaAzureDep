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
      <h1>Tax Master [<?php echo strtoupper($option) ?>]</h1>      
    </div><!-- End Page Title -->
	
	<div class="col-md-3 float-start text-end">
      <a href="javascript:void(0);" class="btn btn-primary cstmBtn mb-3">Active</a>      
    </div>
	<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-tax-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-tax-master?user_option=Edit';} if($option == 'Delete'){ echo '/sinhaco/master/mas-tax-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-3"><input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="frms-sec-insde d-block float-start col-md-2 px-2 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Tax Code</label>
					<input type="text" class="form-control w-100" placeholder="State Code" name="tax_code" id="tax_code" value="<?= ($option!='Add')?$data['tax_code']:'' ?>" readonly <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Tax Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100" placeholder="State Name" name="tax_name" id="tax_name" required value="<?= ($option!='Add')?$data['tax_name']:'' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())"  <?php if ($option == 'Add') {?>	onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getTaxNo')" <?php }?> <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-2 px-2">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Tax Type Code <strong class="text-danger">*</strong></label>
					<select class="form-select" required name="tax_type_code" id="tax_type_code" <?php echo $disview;?>>
						<option>Select</option>
						<option value="C" <?= ($option!='Add')?($data['tax_type_code'] == 'C')?'selected':'':''?>>CST</option>
						<option value="V" <?= ($option!='Add')?($data['tax_type_code'] == 'V')?'selected':'':''?>>Vat</option>
						<option value="S" <?= ($option!='Add')?($data['tax_type_code'] == 'S')?'selected':'':''?>>Sales Tax</option>
						<option value="T" <?= ($option!='Add')?($data['tax_type_code'] == 'T')?'selected':'':''?>>TDS</option>
						<option value="G" <?= ($option!='Add')?($data['tax_type_code'] == 'G')?'selected':'':''?>>GST</option>
					</select>
				</div>				
				<div class="col-md-<?php if($option == 'Add'){echo '7'; }else{ echo '5'; }?> float-start mb-1 px-2">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Tax A/C Code <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-24 float-start me-2" required  name="tax_account_code" id="taxAccountCode" value="<?= ($option!='Add')?$data['tax_account_code']:'' ?>" onchange="fetchData(this, 'tax_account_code', ['taxAccountCode', 'taxAccountDesc'], ['main_ac_code', 'main_ac_desc'], 'tax_account_code')" <?php echo $redokadd;?>/>
					<div class="position-relative d-block float-start w-75">						
						<input type="text" class="form-control w-100 float-start me-2" required  name="tax_account_desc" id="taxAccountDesc"  value="<?= ($option!='Add')?$data['main_ac_desc']:'' ?>" readonly/>
						<i class="fa fa-binoculars icn-vw icn-vw2"
                                onclick="showData('main_ac_code', '<?= '4005' ?>', 'taxAccountCode', [ 'taxAccountCode','taxAccountDesc'], ['main_ac_code','main_ac_desc'], 'tax_account_code')"
                                data-toggle="modal" data-target="#lookup"></i>
					</div>
				</div>
				<div class="col-md-6 float-start mb-3 px-2">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Sub /C Code <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-24 float-start me-2"  name="sub_ac_code" id="subAcCode" value="<?= ($option!='Add')?$data['tax_sub_account_code']:'' ?>" onchange="fetchData(this, 'sub_ac_code', ['subAcCode', 'subAcDesc','mainAcCode'], ['sub_ac_code', 'sub_ac_desc','main_ac_code'], 'sub_ac_code')" <?php echo $redokadd;?>/>
					<div class="position-relative d-block float-start w-75">						
						<input type="text" class="form-control w-100 float-start me-2"  name="sub_ac_desc" id="subAcDesc" value="<?= ($option!='Add')?$data['sub_ac_desc']:'' ?>" readonly/>
						<input type="hidden" name="main_ac_code" id="mainAcCode" value="<?= ($option!='Add')?$data['main_ac_code']:'' ?>">
						<i class="fa fa-binoculars icn-vw icn-vw2"
                                onclick="showData('sub_ac_code', '<?= '4003' ?>', 'subAcCode', [ 'subAcCode','subAcDesc','mainAcCode'], ['sub_ac_code','sub_ac_desc','main_ac_code'], 'sub_ac_code')"
                                data-toggle="modal" data-target="#lookup"></i>
					</div>
				</div>
				
			</div>		
		  </div>
          <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
		  <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
		  <input type="hidden" name="finsub" id="finsub" value="fsub">
		<div class="col-md-12 d-inline-block">
			<button type="submit" class="btn btn-primary cstmBtn mt-3"  onclick="tax_master_validataion()" <?php echo $disview;?>>Save</button>
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