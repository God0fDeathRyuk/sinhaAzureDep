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
  <h1>Client Entry</h1>
</div><!-- End Page Title -->
<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/client-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/client-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/client-master?user_option=Delete';}?>" method="post" id="clientMaster">
<section class="section dashboard">
  <div class="row">
      <div class="col-md-12 mt-2">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Name</label>
                <input type="text" class="form-control" placeholder="Name" name="client_name" id="clientName"<?php if($option=='Add'){ ?> onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getTotNameCount')<?php }?>" value="<?= ($option!='Add') ? $data['client_name'] : '' ?>" <?php echo $redokadd;?> onkeyup="javascript:(this.value=this.value.toUpperCase())"/>
                <input type="hidden" name="client_code" id="clientCode" value="<?= ($option!='Add') ? $data['client_code'] : '' ?>" value="" required/>
            </div>
            <div class="col-md-4 float-start px-2 mb-3 position-relative">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Group Name</label>
                <input type="text" class="form-control" name="client_group_name" id="clientGroupName" placeholder="Councel Type" onchange="fetchData(this, 'code_code', ['clientGroupId', 'clientGroupName'], ['code_code', 'code_desc'], 'client_group')" required  value="<?= ($option!='Add') ? $data['code_desc'] : '' ?>" readonly/>
                    <input type="hidden" class="form-control" name="client_group_id" id="clientGroupId" placeholder="Councel Type" value="<?= ($option!='Add') ? $data['client_group_code'] : '' ?>" />
                    <i class="fa fa-binoculars icn-vw" onclick="showData('code_code', '<?= '4071' ?>', 'clientGroupId', [ 'clientGroupId','clientGroupName'], ['code_code','code_desc'], 'client_group')"  data-toggle="modal" data-target="#lookup" style="display:<?php if($option=='view'){ echo $redv;} ?>"></i>
               
            </div>
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Credit limit</label>
                <div class="position-relative">
                <input type="text" class="form-control" placeholder="Credit Limit" name="credit_limit_amount" id="creditLimitAmount" value="<?= ($option!='Add')?$data['credit_limit_amount'] : '' ?>" <?php echo $redokadd;?>/>
            </div>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile No </label>
                <div class="position-relative">
                <input type="text" class="form-control w-63 float-start" placeholder="Mobile No" name="mobile_no" id="mobileNo" value="<?= ($option!='Add') ? $data['mobile_no'] : '' ?>" <?php echo $redokadd;?>/>
                </div>
            </div>
            
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Referred by</label>
                <div class="position-relative">
                <input type="text" class="form-control w-63 float-start" placeholder="Referred by" name="referred_by" id="referredBy" value="<?= ($option!='Add') ? $data['referred_by'] : '' ?>" <?php echo $redokadd;?>/>
                <input type="text" class="form-control" name="new_client" id="newClient" placeholder="New Client" value="<?= ($option!='Add') ? $data['new_client'] : '' ?>" <?php echo $redokadd;?>/>
                <?php $session = session();
                $sessionName=$session->userId; ?>
                <input type="hidden" class="form-control" name="prepared_by" id="preparedBy" value="<?= ($option=='Edit') ? $data['prepared_by'] : $sessionName ?>"/>
                <input type="hidden" class="form-control" name="prepared_on" id="preparedOn" value="<?= ($option=='Edit') ? $data['prepared_on'] : date('Y-m-d') ?>"/>
                <input type="hidden" class="form-control" name="updated_by" id="updatedBy" value="<?= ($option=='Edit') ? $sessionName : ''?>"/>
                <input type="hidden" class="form-control" name="updated_on" id="updatedOn" value="<?= ($option=='Edit') ? date('Y-m-d') : '' ?>"/>
                <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
                <input type="hidden" name="finsub" id="finsub" value="fsub">    
            </div>
            </div>	
        </div>
        <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2"  onClick="validation()" <?php echo $disview;?>>Save</button>
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