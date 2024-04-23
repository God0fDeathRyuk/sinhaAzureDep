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

  <h1>Account head master [<?php echo strtoupper($option); ?>]</h1>      

</div><!-- End Page Title -->

<section class="section dashboard">

<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-account-master-pl?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-account-master-pl?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-account-master-pl?user_option=Delete';}?>" method="post" id="accountHeadMaster">

  <div class="row">

      <div class="col-md-12 mt-1">

        <div class="frms-sec d-inline-block w-100 bg-white p-3">

            <div class="d-inline-block w-100">

                <div class="frms-sec-insde float-start col-md-3 px-2 mb-4 <?= ($option == 'Add')? 'd-none': 'd-block' ?>">

                    <label class="d-inline-block w-100 mb-2 lbl-mn">A/C Code</label>

                    <input type="text" name="main_ac_code"  id="mainAcCode"  class="form-control" <?php if ($option == 'Add') {?>  onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getDuplMainAcc')" <?php }?>  value="<?= ($option!='Add') ? $data['main_ac_code'] : '' ?>" readonly/>

                </div>

                <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-4">

                    <label class="d-inline-block w-100 mb-2 lbl-mn">A/C Name<strong class="text-danger">*</strong></label>

                    <input type="text" name="main_ac_desc" id="mainAcDesc" class="form-control" value="<?= ($option!='Add') ? $data['main_ac_desc'] : '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?> required />

                </div>

                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">

                    <label class="d-inline-block w-100 mb-2 lbl-mn">A/C Type <strong class="text-danger">*</strong></label>

                    <select class="form-select" required name="account_type_code" id="accountTypeCode" value="<?= ($option!='Add') ? $data['account_type_code'] : '' ?>" <?php echo $disview;?>>
                        <option value="">Select</option>
                        <option value="A" <?= ($option!='Add')?($data['account_type_code']=='A') ? 'selected' : '' :'' ?>>Asset</option>

                        <option value="L" <?= ($option!='Add')?($data['account_type_code']=="L")?'selected': '' :'' ?>>Liability</option>

                        <option value="I" <?= ($option!='Add')?($data['account_type_code']=="I")? 'selected': '' :'' ?>>Income</option>

                        <option value="E" <?= ($option!='Add')?($data['account_type_code']=="E") ?'selected': '' : ''?>>Expenditure</option>

                    </select>

                </div>

                <div class="col-md-6 float-start px-2 mb-3 position-relative">

                    <label class="d-inline-block w-100 mb-2 lbl-mn">A/C Group<strong class="text-danger">*</strong></label>

                    <input type="text" class="form-control" name="act_group_desc" id="actGroupDesc" onchange="fetchData(this, 'act_group_code', ['actGroupDesc', 'actGroupCode'], ['code_desc', 'code_code'], 'act_group_code')" required  value="<?= ($option!='Add') ? $data['code_desc'] : '' ?>" readonly required/>

                        <input type="hidden" class="form-control" name="act_group_code" id="actGroupCode" placeholder="A/C Group"  value="<?= ($option!='Add') ? $data['account_group_code'] : '' ?>"  />

                        <i class="fa fa-binoculars icn-vw" onclick="showData('code_code', '<?= '4001' ?>', 'actGroupCode', [ 'actGroupDesc','actGroupCode'], ['code_desc','code_code'], 'act_group_code')"  data-toggle="modal" data-target="#lookup" ></i>

                   

                </div>

                <!-- <div class="col-md-6 float-start px-2 mb-3 position-relative d-none">

                    <label class="d-inline-block w-100 mb-2 lbl-mn">BS/PL Link</label>

                    <div class="position-relative">

                    <input type="text" class="form-control" name="bspl_desc" id="bsplDesc" onchange="fetchData(this, 'bspl_desc', ['bsplDesc', 'bsplCode'], ['client_name', 'client_code'], 'bspl_desc')"   />

                        <input type="hidden" class="form-control" name="bspl_code" id="bsplCode" placeholder="BS/PL Link"  />

                        <i class="fa fa-binoculars position-absolute icn-vwdml" onclick="showData('client_code', '<?= '4549' ?>', 'bsplCode', [ 'bsplDesc','bsplCode'], ['client_name','client_code'], 'bspl_desc')"  data-toggle="modal" data-target="#lookup" ></i>

                    </div>

                </div>

                

                <div class="col-md-6 float-start px-2 mb-3 position-relative d-none">

                    <label class="d-inline-block w-100 mb-2 lbl-mn">BS/PL Advance Link</label>

                    <div class="position-relative">

                    <input type="text" class="form-control" name="bspl_desc_adv"  id="bsplDescAdv" onchange="fetchData(this, 'bspl_desc', ['bsplDescAdv', 'bsplCodeAdv'], ['client_name', 'client_code'], 'bspl_desc')"   />

                        <input type="hidden" class="form-control" name="bspl_code_adv" id="bsplCodeAdv" placeholder="BS/PL Advance Link"  />

                        <i class="fa fa-binoculars position-absolute icn-vwdml" onclick="showData('client_code', '<?= '4550' ?>', 'bsplCode', [ 'bsplDescAdv','bsplCodeAdv'], ['client_name','client_code'], 'bspl_desc')"  data-toggle="modal" data-target="#lookup" ></i>

                    </div>

                </div> -->

                <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-4">

                    <label class="d-inline-block w-100 mb-2 lbl-mn">S/L Ind<strong class="text-danger">*</strong></label>

                    <select class="form-select" name="sub_ac_ind" id="subAcInd" value="<?= ($option!='Add') ? $data['sub_ac_ind'] : '' ?>" <?php echo $disview;?> required>
                        <option value="">Select</option>
                        <option value="Y" <?=  ($option!='Add')?($data['sub_ac_ind']=="Y")? 'selected': '' : '' ?>>Yes</option>

                        <option value="N" <?=  ($option!='Add')?($data['sub_ac_ind']=="N")? 'selected': '' : ''?>>No</option>

                    </select>

                </div>

                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">

                    <label class="d-inline-block w-100 mb-2 lbl-mn">Status<strong class="text-danger">*</strong></label>

                    <select class="form-select" name="status_code" id="statusCode" value="<?= ($option!='Add') ? $data['statusCode'] : '' ?>" <?php echo $disview;?> required>
                        <option value="">Select</option>
                        <option value="Active" <?= ($option!='Add')?($data['statusCode']=="Active")? 'selected': '' : ''?>> Active</option>

                        <option value="Old" <?= ($option!='Add')?($data['statusCode']=="Old") ? 'selected': '' : '' ?>>Inactive</option>

                    </select>

                    <input type="hidden" name="status_date" id="statusDate" value="<?= ($option!='Add') ? $data['status_date'] : date('Y-m-d') ?>" class="form-control" <?php echo $redokadd;?>/>

                </div>

                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">

                    <label class="d-inline-block w-100 mb-2 lbl-mn">Segregation <strong class="text-danger">*</strong></label>

                    <select class="form-select" name="segregated_ind" id="segregatedInd" value="<?= ($option!='Add') ? $data['segregated_ind'] : '' ?>" <?php echo $disview;?> required>
                        <option value="">Select</option>
                        <option value="V" <?= ($option!='Add')?($data['segregated_ind']=="V") ? 'selected': '':'' ?>>N/A</option>

                        <option value="F" <?= ($option!='Add')? ($data['segregated_ind']=="F") ? 'selected': '': '' ?>>N/B</option>

                    </select>

                </div>														

            </div>
            <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
		<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
            <?php $session = session();

                $sessionName=$session->userId;?>

            <input type="hidden" name="update_id"  value="<?= ($option!='Add') ? $sessionName  : '' ?>"> 

		    <input type="hidden" name="update_dt" value="<?= ($option!='Add') ? date('Y-m-d') : '' ?>"> 

            <input type="hidden" class="form-control" name="opening_date" id="opening_date" value="<?= ($option!='Add') ? $data['opening_date'] : date('Y-m-d') ?>"/>
          
                <input type="hidden" name="finsub" id="finsub" value="fsub">
            <button type="submit" id="submit" class="btn btn-primary cstmBtn mt-2" onClick="return account_master_check();" <?php echo $disview;?>>Save</button>
            <?php if($option=="Delete"){?>
                <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-2 ms-2">Delete</button>
            <?php } ?>
            <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</a>

      </div>

  </div>

</form>

</section>



</main><!-- End #main -->



<?= $this->endSection() ?>