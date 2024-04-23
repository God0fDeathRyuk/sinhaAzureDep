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

  <h1>Client Address Master [<?= strtoupper($option) ?>]</h1>

</div><!-- End Page Title -->

<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/client-address-addedit?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/client-address-addedit?user_option=Edit';} if($option == 'Delete'){ echo '/sinhaco/master/client-address-addedit?user_option=Delete';}?>" method="post" id="clientAddress">

<section class="section dashboard">

  <div class="row">

      <div class="col-md-12 mt-2">

        <div class="frms-sec d-inline-block w-100 bg-white p-3">

            

            <div class="frms-sec-insde <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?> float-start col-md-3 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">Addr Code <strong class="text-danger">*</strong></label>

                <input type="text" class="form-control" name="address_code" id="addressCode" placeholder="Addr Code" value="<?= ($option!='Add') ? $data1['address_code'] : '' ?>" required readonly/>

            </div>

            <div class="col-md-4 float-start px-2 mb-3 position-relative">

                <label class="d-inline-block w-100 mb-2 lbl-mn">Client Name <strong class="text-danger">*</strong></label>

                <input type="text" class="form-control" name="client_name" id="clientName" placeholder="Client Name" onchange="fetchData(this, 'client_address', ['clientCode', 'clientName'], ['client_code', 'client_name'], 'client_address')" required readonly value="<?= ($option!='Add') ? $data1['client_name'] : '' ?>" required <?php echo $redokadd;?>/>

                    <input type="hidden" class="form-control" name="client_code" id="clientCode" placeholder="Client Name" value="<?= ($option!='Add') ? $data1['client_code'] : '' ?>" />

                    <i class="fa fa-binoculars icn-vw" onclick="showData('client_code', '<?= '4072' ?>', 'clientCode', [ 'clientCode','clientName'], ['client_code','client_name'], 'client_address')"  data-toggle="modal" data-target="#lookup" ></i>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">Address<strong class="text-danger">*</strong></label>

                <textarea type="text" class="form-control" rows="3" required cols="" name="address_line_1" id="addressLine_1" placeholder="Address"  onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>><?= ($option!='Add') ? $data1['address_line_1'].$data1['address_line_2'].$data1['address_line_3'].$data1['address_line_4'] : '' ?></textarea>

            </div>

            <div class="col-md-4 float-start px-2 position-relative mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">City / Town <strong class="text-danger">*</strong></label>

                <input type="text" class="form-control w-100 float-start" required name="city" id="City" placeholder="City" value="<?= ($option!='Add') ? $data1['city'] : '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>

            </div>

            <div class="col-md-4 float-start px-2 position-relative mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">Pin </label>

                <input type="text" class="form-control w-100 ms-2 float-start" name="pin_code" id="pinCode" placeholder="Pin" value="<?= ($option!='Add') ? $data1['pin_code'] : '' ?>"<?php echo $redokadd;?>/>

            </div>

            <div class="col-md-4 float-start px-2 position-relative mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">State <strong class="text-danger">*</strong></label>

                <select class="form-select" name="state_code" id="stateCode" <?php echo $disview;?> required>

                    <option value="">-- None --</option>

                    <?php foreach ($data as $value) {?>

                    <option value="<?= $value['state_code'] ?>" <?= ($option!='Add')?($data1['state_code']==$value['state_code'])? 'selected': '' :'' ?>><?= $value['state_name'] ?></option>

                    <?php } ?>

                    

                </select>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">Country</label>

                <input type="text" class="form-control" name="country" id="Country" placeholder="Country" value="<?= ($option!='Add') ? $data1['country'] : '' ?>"  onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">PAN</label>

                <input type="text" class="form-control" max="10" name="pan_no" id="panNo" placeholder="PAN" value="<?= ($option!='Add') ? $data1['pan_no'] : '' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">ISD#</label>

                <input type="text" class="form-control" name="isd_code" id="isdCode" placeholder="ISD" value="<?= ($option!='Add') ? $data1['isd_code'] : '' ?>" <?php echo $redokadd;?>/>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">STD#</label>

                <input type="text" class="form-control" name="std_code" id="stdCode" placeholder="STD" value="<?= ($option!='Add') ? $data1['std_code'] : '' ?>" <?php echo $redokadd;?>/>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">Phone#</label>

                <input type="tel" pattern="[7-9]{1}[0-9]{9}" class="form-control" name="phone_no" id="phoneNo" placeholder="Phone" value="<?= ($option!='Add') ? $data1['phone_no'] : '' ?>" <?php echo $redokadd;?>/>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">FAX#</label>

                <input type="text" class="form-control" name="fax_no" id="fax_no" placeholder="FAX" value="<?= ($option!='Add') ? $data1['fax_no'] : '' ?>" <?php echo $redokadd;?>/>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile#</label>

                <input type="tel" pattern="[7-9]{1}[0-9]{9}" class="form-control" name="mobile_no" id="mobileNo" placeholder="Mobile" value="<?= ($option!='Add') ? $data1['mobile_no'] : '' ?>" <?php echo $redokadd;?>/>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>

                <input type="email" class="form-control" name="email_id" id="emailId" placeholder="Email" value="<?= ($option!='Add') ? $data1['email_id'] : '' ?>" <?php echo $redokadd;?>/>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">Web Address</label>

                <input type="text" class="form-control" name="web_id" id="webId" placeholder="Web Address" value="<?= ($option!='Add') ? $data1['web_id'] : '' ?>" <?php echo $redokadd;?>/>

            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                <label class="d-inline-block w-100 mb-2 lbl-mn">GST</label>

                <input type="text" class="form-control" name="client_gst" id="clientGst" placeholder="GST" value="<?= ($option!='Add') ? $data1['client_gst'] : '' ?>" <?php echo $redokadd;?>/>

            </div>

            <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>     

        </div>

        <?php $session = session();

                $sessionName=$session->userId; ?>

                <input type="hidden" class="form-control" name="prepared_by" id="preparedBy" value="<?= ($option!='Add') ? $data1['prepared_by'] : $sessionName ?>"/>

                <input type="hidden" class="form-control" name="prepared_on" id="preparedOn" value="<?= ($option!='Add') ? $data1['prepared_on'] : date('Y-m-d') ?>"/>

                <input type="hidden" class="form-control" name="updated_by" id="updatedBy" value="<?= ($option!='Add') ? $sessionName : ''?>"/>

                <input type="hidden" class="form-control" name="updated_on" id="updatedOn" value="<?= ($option!='Add') ? date('Y-m-d') : '' ?>"/>
                <input type="hidden" name="finsub" id="finsub" value="fsub">
        <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" id="btnSave" onClick="return goSave()" <?php echo $disview;?>><?php if($option=='Copy'){ echo 'Copy';}else{ echo 'Save';} ?></button>
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