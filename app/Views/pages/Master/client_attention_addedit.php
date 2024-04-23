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
  <h1 class="col-md-8 float-start">Client Attention Master [<?= strtoupper($option) ?>]</h1>
</div><!-- End Page Title -->
<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/client-attention-addedit?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/client-attention-addedit?user_option=Edit';} if($option == 'Delete'){ echo '/sinhaco/master/client-attention-addedit?user_option=Delete';}?>" method="post" id="clientAttention">
<section class="section dashboard">
  <div class="row">
      <div class="col-md-12 mt-2">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
            <div class="d-inline-block w-100 mb-3 pb-2 border-bottom">
                <div class="col-md-6 float-start px-2 mb-3 position-relative">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Client Name <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control" name="client_name" id="clientName" placeholder="Client Name" onchange="fetchData(this, 'client_attention', ['clientName', 'clientCode', 'Address','addressCode'], ['client_name', 'client_code','address','address_code'], 'client_attention')" required readonly  value="<?= ($option!='Add') ? $data['client_name'] : '' ?>" />
                    <input type="hidden" class="form-control" name="client_code" id="clientCode" placeholder="Councel Type" value="<?= ($option!='Add') ? $data['client_code'] : '' ?>" />
                    <i class="fa fa-binoculars icn-vw" onclick="showData('client_code', '<?= '4073' ?>', 'clientName', [ 'clientName','clientCode','Address','addressCode'], ['client_name','client_code','address','address_code'], 'client_attention')"  data-toggle="modal" data-target="#lookup" style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
                
            </div>
                <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Address</label>
                    <textarea type="text" class="form-control" rows="3" cols="" placeholder="Address" name="address" id="Address" <?= $disview; ?>><?= ($option!='Add') ? $data['address_line_1'].$data['address_line_2'].$data['address_line_3'].$data['address_line_4'] : '' ?></textarea>
                    <input type="hidden" name="uid" id="uid" value="<?= ($option!='Add') ? $data['attention_code'] : '' ?>"/>
                    <input type="hidden" class="form-control" name="address_code" id="addressCode" value="<?= ($option!='Add') ? $data['address_code'] : '' ?>" />
                </div>
            </div>
            
            <div class="col-md-4 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Code </label>
                <input type="text" class="form-control w-100 float-start" placeholder="Code" name="attention_code" id="attentionCode"  value="<?= ($option!='Add') ? $data['attention_code'] : '' ?>" <?=  $redk; ?> readonly/>
            </div>
            <div class="col-md-8 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Name <b class="text-danger">*</b><i class="fw-bold ms-3">( ** Please use Mr or Mrs before name )</i></label>
                <input type="text" class="form-control w-100 float-start" placeholder="Name" required name="attention_name"  id="attentionName" value="<?= ($option!='Add') ? $data['attention_name'] : '' ?>" <?= $disview; ?>/>
            </div>
            <div class="col-md-4 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Designatin </label>
                <input type="text" class="form-control w-100 float-start" placeholder="Designation" name="designation"  id="designation" value="<?= ($option!='Add') ? $data['designation'] : '' ?>" <?= $disview; ?>/>
            </div>
            <div class="col-md-4 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Abbr Name </label>
                <input type="text" class="form-control w-100 float-start" placeholder="Abbr Name" name="short_name"  id="shortName" value="<?= ($option!='Add') ? $data['short_name'] : '' ?>"  <?= $disview; ?>/>
            </div>				
            <div class="col-md-4 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Sex </label>
                <select class="form-select" name="sex" name="Sex" <?= ($option=='View') ? $disv : '' ?>>
                    <option value="0">-- None --</option>
                    <option value="M" <?= ($option!='Add') ? ($data['sex']=="M" )?'selected': '' : '' ?>>Male</option>
                    <option value="F" <?= ($option!='Add') ? ($data['sex']=="F" )?'selected': '' : '' ?>>Female</option>
                </select>
            </div>
            <div class="col-md-4 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Title <strong class="text-danger">*</strong></label>
                <select class="form-select" name="title"  id="title" <?= ($option=='View') ? $disv : '' ?> required >
                    <option value="">-- None --</option>
                    <option value="Mr."  <?= ($option!='Add') ?($data['title']=="Mr.")? 'selected': '': ''?>>Mr.</option>
                    <option value="Mrs." <?= ($option!='Add') ?($data['title']=="Mrs.")? 'selected': '': ''?>>Mrs.</option>
                    <option value="Ms."  <?= ($option!='Add') ?($data['title']=="Ms.")? 'selected': '': ''?>>Ms.</option>
                    <option value="Dr."  <?= ($option!='Add') ?($data['title']=="Dr.")? 'selected': '': ''?>>Dr.</option>
                    <option value="Prof."<?= ($option!='Add') ?($data['title']=="Prof.")? 'selected': '': ''?>>Prof.</option>
                    <option value="Rev." <?= ($option!='Add') ?($data['title']=="Rev.")? 'selected': '': ''?>>Rev.</option>
                    <option value="ORS"  <?= ($option!='Add') ?($data['title']=="ORS")? 'selected': '': ''?>>Other.</option>
                </select>
            </div>
            
            <div class="col-md-4 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Phone# </label> 
                <input type="tel" pattern="[7-9]{1}[0-9]{9}" title="[7-9]{1}[0-9]{9}"  class="form-control w-100 float-start" placeholder="Phone" name="phone_no" id="phoneNo" value="<?= ($option!='Add') ? $data['phone_no'] : '' ?>" <?= $disview; ?>/>
            </div>
            <div class="col-md-4 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Fax# </label>
                <input type="text" class="form-control w-100 float-start" placeholder="Fax" name="fax_no" name="faxNo" value="<?= ($option!='Add') ? $data['fax_no'] : '' ?>" <?= $disview; ?>/>
            </div>
            <div class="col-md-4 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile# </label>
                <input type="tel" pattern="[7-9]{1}[0-9]{9}" title="[7-9]{1}[0-9]{9}" class="form-control w-100 float-start" placeholder="Mobile" name="mobile_no" id="mobileNo" value="<?= ($option!='Add') ? $data['mobile_no'] : '' ?>" <?= $disview; ?>/>
            </div>
            <div class="col-md-4 float-start px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                <input type="email" class="form-control" placeholder="Email" name="email_id" id="emailId" value="<?= ($option!='Add') ? $data['email_id'] : '' ?>" <?= $disview; ?>/>
            </div>
            <div class="col-md-4 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Other Email </label>
                <input type="email" class="form-control w-100 float-start" placeholder="Other Email" name="email_id_other"  id="emailIdOther" value="<?= ($option!='Add') ? $data['email_id_other'] : '' ?>" <?= $disview; ?>/>
            </div>				
        </div>
<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
        <?php $session = session();
                $sessionName=$session->userId; ?>
                <input type="hidden" class="form-control" name="prepared_by" id="preparedBy" value="<?= ($option=='Edd') ? $data['prepared_by'] : $sessionName ?>"/>
                <input type="hidden" class="form-control" name="prepared_on" id="preparedOn" value="<?= ($option=='Edd') ? $data['prepared_on'] : date('Y-m-d') ?>"/>
                <input type="hidden" class="form-control" name="updated_by" id="updatedBy" value="<?= ($option=='Edd') ? $sessionName : ''?>"/>
                <input type="hidden" class="form-control" name="updated_on" id="updatedOn" value="<?= ($option=='Edd') ? date('Y-m-d') : '' ?>"/>
                <div class="d-inline-block w-100 mt-3">
                <input type="hidden" name="finsub" id="finsub" value="fsub">
  <button type="submit" class="btn btn-primary cstmBtn ms-2" id="btnSave" onClick="return attgoSave()" <?= $disview; ?>>Save</button>
  <?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn ms-2">Delete</button>
                        <?php } ?>       
  <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2">Back</a>
  </div>
                
      </div>
  </div>
</section>
</form>

</main><!-- End #main -->

<?= $this->endSection() ?>