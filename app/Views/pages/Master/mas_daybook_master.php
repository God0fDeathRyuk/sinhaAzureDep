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
      <h1 class="col-md-8 float-start">Daybook Master (<?php echo strtoupper($option) ?>)</h1>
    </div><!-- End Page Title -->
    <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-daybook-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-daybook-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-daybook-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-4 float-start px-2 mb-3 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn ">Day Book Code</label>
					<input type="text" class="form-control w-100 float-start" placeholder="Day Book Code" name="daybook_code" id="daybook_code" value="<?= ($option!='Add')?$data['daybook_code']: $data2['maxValue']+1 ?>" readonly/>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Day Book Desc  <strong class="text-danger">*</strong></label>
					<input type="text" placeholder="Day Book Desc" class="form-control w-100 float-start" required  name="daybook_desc" id="daybook_desc" value="<?= ($option!='Add')?$data['daybook_desc']: '' ?>" <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Day Book Type  <strong class="text-danger">*</strong></label>
					<select class="form-select w-100 float-start" required  name="daybook_type" id="daybook_type" onchange="check_account(this)" <?php echo $disview;?>>
						<option value="">--- Select ---</option>
                        <option value="CB" <?= ($option!='Add')?($data['daybook_type']=='CB')?'selected':'': ''?>>Cash</option>
                        <option value="BB" <?= ($option!='Add')?($data['daybook_type']=='BB')?'selected':'': ''?>>Bank</option>
                        <option value="JB" <?= ($option!='Add')?($data['daybook_type']=='JB')?'selected':'': ''?>>Journal</option>
                        <option value="SJ" <?= ($option!='Add')?($data['daybook_type']=='SJ')?'selected':'': ''?>>Sales</option>
                        <option value="PJ" <?= ($option!='Add')?($data['daybook_type']=='PJ')?'selected':'': ''?>>Purchase</option>
					</select>
				</div>
				
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch <strong class="text-danger">*</strong></label>
					<select class="form-select w-100 float-start" required  name="branch_code" id="branch_code" <?php echo $disview;?>>
						<option value="">--- Select ---</option>
                        <?php foreach ($data1 as $key => $value) { ?>
                            <option value="<?= $value['branch_code'] ?>" <?= ($option!='Add')?($data['branch_code']==$value['branch_code'])?'selected':'':'' ?>><?= $value['branch_name'] ?></option>
                        <?php } ?>
					</select>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bank A/C No</label>
					<input type="text" class="form-control w-100 float-start" placeholder="Bank A/C No" name="bank_account_no" id="bank_account_no" value="<?= ($option!='Add')?$data['bank_account_no']: '' ?>" <?php echo $redokadd;?>/>
				</div>
                <div class="col-md-4 float-start px-2 mb-3 position-relative">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Main Account #</label>
                <input type="text" class="form-control" name="ac_desc" id="acDesc" placeholder="Main Account " onchange="fetchData(this, 'main_ac_code', ['mainAcCode', 'acDesc'], ['main_ac_code', 'main_ac_desc'], 'main_ac_code')"   value="<?= ($option=='Edit') ? $data['main_ac_desc'] : '' ?>"  <?php echo $redokadd;?>/>
                    <input type="hidden" class="form-control" name="main_ac_code" id="mainAcCode" placeholder="Councel Type" value="<?= ($option!='Add')? $data['main_ac_code']:'' ?>" />
                    <i class="fa fa-binoculars icn-vw" onclick="showData('main_ac_code', '<?= '4004' ?>', 'mainAcCode', [ 'mainAcCode','acDesc'], ['main_ac_code','main_ac_desc'], 'main_ac_code')"  data-toggle="modal" data-target="#lookup"></i>
                </div>
                <div class="col-md-4 float-start px-2 mb-3 position-relative">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Sub Account #</label>
                <input type="text" class="form-control" name="sub_ac_desc" id="subAcDesc" placeholder="Sub Account" onchange="fetchData(this, 'sub_ac_code', ['mainAcCode', 'subAcCode'], ['sub_ac_code', 'sub_ac_desc'], 'sub_ac_code')"   value="<?= ($option=='Edit') ? '' : '' ?>"  <?php echo $redokadd;?>/>
                    <input type="hidden" class="form-control" name="sub_ac_code" id="subAcCode" placeholder="Councel Type" value="<?= ($option!='Add')? $data['sub_ac_code']:'' ?>" />
                    <i class="fa fa-binoculars icn-vw" onclick="showData('sub_ac_code', '<?= '4003' ?>', 'mainAcCode', [ 'mainAcCode','subAcCode'], ['sub_ac_code','sub_ac_desc'], 'sub_ac_code')"  data-toggle="modal" data-target="#lookup"></i>
                </div>
				
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">O/D Amount</label>
					<input type="text" class="form-control w-100 float-start" placeholder="O/D Amounts" name="overdraft_amount" id="overdraft_amount" value="<?= ($option!='Add')?$data['overdraft_amount']:'' ?>" <?php echo $redokadd;?>/>
				</div>
						<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<input type="hidden" name="finsub" id="finsub" value="fsub">
<div class="d-inline-block w-100 mt-3">
	  <button type="submit" class="btn btn-primary cstmBtn ms-2" onClick="return chk_and_submit()" <?php echo $disview;?>>Save</button>				
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