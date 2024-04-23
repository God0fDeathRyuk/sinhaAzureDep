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
  <h1 class="col-md-8 float-start">Client Name Entry</h1>
  <div class="col-md-4 float-end text-end mb-2">
        <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2">Back</a>
  </div>
</div><!-- End Page Title -->
<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/client-details-combine?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/client-details-combine?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/client-details-combine?user_option=Delete';}?>" method="post" id="clientDetailsCombileMaster">
<section class="section dashboard">
  <div class="row">
      <div class="col-md-12 mt-2">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Name<strong class="text-danger">*</strong></label>
                <input type="text" class="form-control" placeholder="Name" name="client_name" id="clientName" <?php if($option=='Add'){ ?> onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getTotNameCount')<?php }?>" value="<?= ($option!='Add') ? $data1['client_name'] : '' ?>" <?php echo $redokadd;?> required/>
                <input type="hidden" name="client_code" id="clientCode" value="<?= ($option!='Add') ? $_REQUEST['client_code'] : '' ?>" required/>
            </div>
                <div class="col-md-6 float-start px-2 mb-3 position-relative">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Group Name<strong class="text-danger">*</strong></label>
                   <input type="text" class="form-control" name="client_group_name" id="clientGroupName" placeholder="Group Name" onchange="fetchData(this, 'code_code', ['clientGroupId', 'clientGroupName'], ['code_code', 'code_desc'], 'client_group')" required  value="<?= ($option!='Add') ? $data1['code_desc'] : '' ?>" <?php echo $redokadd;?> required />
                    <input type="hidden" class="form-control" name="client_group_id" id="clientGroupId" placeholder="Councel Type" value="<?= ($option!='Add') ? $data1['client_group_code'] : '' ?>" />
                    <i class="fa fa-binoculars icn-vw" onclick="showData('code_code', '<?= '4071' ?>', 'clientGroupId', [ 'clientGroupId','clientGroupName'], ['code_code','code_desc'], 'client_group')"  data-toggle="modal" data-target="#lookup"></i>
                   
                </div>
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Credit limit</label>
                <div class="position-relative">
                <input type="text" class="form-control" placeholder="Credit Limit" name="credit_limit_amount" id="creditLimitAmount" value="<?= ($option!='Add')?$data1['credit_limit_amount'] : '' ?>" <?php echo $redokadd;?>/>
            </div>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile No </label>
                <div class="position-relative">
                <input type="tel"  pattern="[789][0-9]{9}" class="form-control w-63 float-start" placeholder="Mobile No" name="mobile_no" id="mobileNo" value="<?= ($option!='Add') ? $data1['mobile_no'] : '' ?>" <?php echo $redokadd;?>/>
                </div>
            </div>
            
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Referred by</label>
                <div class="position-relative">
                <input type="text" class="form-control w-63 float-start" placeholder="Referred by" name="referred_by" id="referredBy" value="<?= ($option!='Add') ? $data1['referred_by'] : '' ?>" <?php echo $redokadd;?>/>
                <input type="text" class="form-control w-35 ms-2 float-start" name="new_client" id="newClient" placeholder="New Client" value="<?= ($option!='Add') ? $data1['new_client'] : '' ?>" <?php echo $redokadd;?>/>
                <?php $session = session();
                $sessionName=$session->userId; ?>
                <input type="hidden" class="form-control" name="prepared_by" id="preparedBy" value="<?= ($option!='Add') ? $data1['prepared_by'] : $sessionName ?>"/>
                <input type="hidden" class="form-control" name="prepared_on" id="preparedOn" value="<?= ($option!='Add') ? $data1['prepared_on'] : date('Y-m-d') ?>"/>
                <input type="hidden" class="form-control" name="updated_by" id="updatedBy" value="<?= ($option!='Add') ? $sessionName : ''?>"/>
                <input type="hidden" class="form-control" name="updated_on" id="updatedOn" value="<?= ($option!='Add') ? date('Y-m-d') : '' ?>"/>
                </div>
            </div>	
        </div>
      </div>
  </div>
</section>
<div class="pagetitle mt-1">
  <h1>Client Address Entry</h1>
</div><!-- End Page Title -->
<section class="section dashboard">
  <div class="row">
  <input type="hidden" class="form-control" name="address_count" id="addressCount" value="<?= ($option!='Add')? count($data3): '1'?>"/>
  <input type="hidden" class="form-control" name="preaddress_count" id="preaddress_count" value="<?= ($option!='Add')? count($data3): '1'?>"/>
    <?php if($option=='Add'){ ?>
    <div class="accordion" id="accordionExample">
          <div class="accordion-item acrdnHedClr" id="addressSection">
            <?php $i=1; ?>
            <h2 class="accordion-header" id="heading<?= $i; ?>">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i; ?>" aria-expanded="true" aria-controls="collapse<?= $i; ?>">
                Address 
              </button>
            </h2>
            
            <div id="collapse<?= $i; ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?= $i; ?>" data-bs-parent="#accordionExample">
              <div class="accordion-body" >
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            
                            <div class="frms-sec-insde <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?> float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Addr Code</label>
                                <input type="text" class="form-control" name="address_code<?= $i; ?>" id="addressCode<?= $i; ?>" placeholder="Addr Code" value="" readonly/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Address <strong class="text-danger">*</strong></label>
                                <textarea type="text" class="form-control" rows="2" cols="" name="address_line_1<?= $i; ?>" id="addressLine_1<?= $i; ?>" required placeholder="Address" <?php echo $redokadd;?>></textarea>
                            </div>
                            <div class="col-md-<?php if($option == 'Add'){echo '3'; }else{ echo '4'; }?> float-start px-2 position-relative mb-1 hgt100">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">City / Town <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control w-100 float-start" name="city<?= $i; ?>" id="City<?= $i; ?>" required placeholder="City" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="col-md-4 float-start px-2 position-relative mb-1 <?php if($option == 'Add'){echo 'hgt100'; }?>">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Pin </label>
                                <input type="text" class="form-control w-100 float-start" name="pin_code<?= $i; ?>" id="pinCode<?= $i; ?>" placeholder="Pin" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="col-md-4 float-start px-2 position-relative mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">State<strong class="text-danger">*</strong> </label>
                                <select class="form-select" name="state_code<?= $i; ?>" id="stateCode<?= $i; ?>" required <?php echo $disview;?>>
                                    <option value="">-- None --</option>
                                    <?php foreach ($data2 as $value) {?>
                                    <option value="<?=$value['state_code']; ?>" ><?= $value['state_name'] ?></option>
                                    <?php } ?>
                                    
                                </select>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Country</label>
                                <input type="text" class="form-control" name="country<?= $i; ?>" id="Country<?= $i; ?>" placeholder="Country" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">PAN</label>
                                <input type="text" class="form-control" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" name="pan_no<?= $i; ?>" id="panNo<?= $i; ?>" placeholder="PAN" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">ISD#</label>
                                <input type="text" class="form-control" name="isd_code<?= $i; ?>" id="isdCode<?= $i; ?>" placeholder="ISD" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">STD#</label>
                                <input type="text" class="form-control" name="std_code<?= $i; ?>" id="stdCode<?= $i; ?>" placeholder="STD" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Phone#</label>
                                <input type="rel" pattern="[789][0-9]{9}" class="form-control" name="phone_no<?= $i; ?>" id="phoneNo<?= $i; ?>" placeholder="Phone" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">FAX#</label>
                                <input type="text" class="form-control" name="fax_no<?= $i; ?>" id="fax_no<?= $i; ?>" placeholder="FAX" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                                <input type="email" class="form-control" name="email_id<?= $i; ?>" id="emailId<?= $i; ?>" placeholder="Email" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Web Address</label>
                                <input type="text" class="form-control" name="web_id<?= $i; ?>" id="webId<?= $i; ?>" placeholder="Web Address" value="" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">GST</label>
                                <input type="text" class="form-control" name="client_gst<?= $i; ?>" id="clientGst<?= $i; ?>" placeholder="GST" value="" <?php echo $redokadd;?>/>
                            </div>  
                        </div>
                </div>
              </div>
            </div>
          </div>
          
      </div>
      <?php } ?>
      <?php if($option!='Add'){
      foreach($data3 as $key => $value) { $key++; ?>
      <div class="accordion" id="accordionExample">
          <div class="accordion-item acrdnHedClr" id="addressSection">
            <h2 class="accordion-header" id="heading<?= $key; ?>">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $key; ?>" aria-expanded="true" aria-controls="collapse<?= $key; ?>">
                Address
              </button>
            </h2>
            <div id="collapse<?= $key; ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?= $key; ?>" data-bs-parent="#accordionExample">
              <div class="accordion-body" >
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            
                            <div class="frms-sec-insde <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?> float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Addr Code</label>
                                <input type="text" class="form-control" name="address_code<?= $key; ?>" id="addressCode<?= $key; ?>" placeholder="Addr Code" value="<?= ($option!='Add') ? $value['address_code'] : '' ?>" readonly/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Address <strong class="text-danger">*</strong></label>
                                <textarea type="text" class="form-control" rows="2" cols="" name="address_line_1<?= $key; ?>" id="addressLine_1<?= $key; ?>" required placeholder="Address" <?php echo $redokadd;?>><?= ($option!='Add') ? $value['address_line_1'].$value['address_line_2'].$value['address_line_3'].$value['address_line_4'] : '' ?></textarea>
                            </div>
                            <div class="col-md-<?php if($option == 'Add'){echo '3'; }else{ echo '4'; }?> float-start px-2 position-relative mb-1 hgt100">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">City / Town <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control w-100 float-start" name="city<?= $key; ?>" id="City<?= $key; ?>" required placeholder="City" value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="col-md-4 float-start px-2 position-relative mb-1 <?php if($option == 'Add'){echo 'hgt100'; }?>">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Pin </label>
                                <input type="text" class="form-control w-100 float-start" name="pin_code<?= $key; ?>" id="pinCode<?= $key; ?>" placeholder="Pin" value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="col-md-4 float-start px-2 position-relative mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">State<strong class="text-danger">*</strong> </label>
                                <select class="form-select" name="state_code<?= $key; ?>" id="stateCode<?= $key; ?>" required <?php echo $disview;?>>
                                    <option value="">-- None --</option>
                                    <?php foreach ($data2 as $value2) {?>
                                    <option value="<?=$value2['state_code']; ?>" <?= ($option!='Add') ? ($value['state_code']==$value2['state_code'])?'selected' : '' : ''?>><?= $value2['state_name'] ?></option>
                                    <?php } ?>
                                    
                                </select>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Country</label>
                                <input type="text" class="form-control" name="country<?= $key; ?>" id="Country<?= $key; ?>" placeholder="Country" value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">PAN</label>
                                <input type="text" class="form-control" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" name="pan_no<?= $key; ?>" id="panNo<?= $key; ?>" placeholder="PAN" value="<?= ($option!='Add') ? $value['pan_no'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">ISD#</label>
                                <input type="text" class="form-control" name="isd_code<?= $key; ?>" id="isdCode<?= $key; ?>" placeholder="ISD" value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">STD#</label>
                                <input type="text" class="form-control" name="std_code<?= $key; ?>" id="stdCode<?= $key; ?>" placeholder="STD" value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Phone#</label>
                                <input type="rel" pattern="[789][0-9]{9}" class="form-control" name="phone_no<?= $key; ?>" id="phoneNo<?= $key; ?>" placeholder="Phone" value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">FAX#</label>
                                <input type="text" class="form-control" name="fax_no<?= $key; ?>" id="fax_no<?= $key; ?>" placeholder="FAX" value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                                <input type="email" class="form-control" name="email_id<?= $key; ?>" id="emailId<?= $key; ?>" placeholder="Email" value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Web Address</label>
                                <input type="text" class="form-control" name="web_id<?= $key; ?>" id="webId<?= $key; ?>" placeholder="Web Address" value="<?= ($option!='Add') ? $value['web_id'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">GST</label>
                                <input type="text" class="form-control" name="client_gst<?= $key; ?>" id="clientGst<?= $key; ?>" placeholder="GST" value="<?= ($option!='Add') ? $value['client_gst'] : '' ?>" <?php echo $redokadd;?>/>
                            </div>  
                        </div>
                        
                </div>
              </div>
            </div>
          </div>
          
          
      </div>
      <?php }} ?>
      
    </div>
    <button type="button" class="btn btn-primary cstmBtn ms-2 mt-3 float-end" onclick="addNewAddress()">Add New Address</button>
      
  </div>
</section>



<div class="pagetitle mt-1 d-inline-block w-100">
  <h1>Client Attention Entry</h1>
</div><!-- End Page Title -->
<input type="hidden" class="form-control" name="attentionCount" id="attentionCount" value="<?= ($option!='Add')? count($result2): '1'?>"/>
<input type="hidden" class="form-control" name="prevattentionCount" id="prevattentionCount" value="<?= ($option!='Add')? count($result2): '1'?>"/>
<?php if($option=='Add'){ ?>
<div class="" id="attentionSection">
<div class="accordion">
    <div class="accordion-item acrdnHedClr" >
        <h2 class="accordion-header" id="heading<?= $i; ?>">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapseS<?= $i; ?>" data-bs-target="#collapseS<?= $i; ?>" aria-expanded="false" aria-controls="collapseS<?= $i; ?>">Attention 
        </button>
        </h2>
        <div id="collapseS<?= $i; ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?= $i; ?>" data-bs-parent="#attentionSection">
        <div class="accordion-body">
        <div class="col-md-12 mt-2">
            <div class="frms-sec d-inline-block w-100 bg-white p-3">
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Code" name="attention_code<?= $i; ?>" id="attentionCode<?= $i; ?>"  value="<?= ($option!='Add') ? $data['attention_code'] : '' ?>" <?=  $redk; ?> readonly/>
                </div>
                <div class="col-md-8 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Name <b class="text-danger">*</b><i class="fw-bold ms-3">( ** Please use Mr or Mrs before name )</i></label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Name" required name="attention_name<?= $i; ?>"  id="attentionName<?= $i; ?>" value="<?= ($option!='Add') ? $data['attention_name'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Designatin </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Designation" name="designation<?= $i; ?>"  id="designation<?= $i; ?>" value="<?= ($option!='Add') ? $data['designation'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Abbr Name </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Abbr Name" name="short_name<?= $i; ?>"  id="shortName<?= $i; ?>" value="<?= ($option!='Add') ? $data['short_name'] : '' ?>"  <?= $disview; ?>/>
                </div>				
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Sex </label>
                    <select class="form-select" name="sex<?= $i; ?>" name="Sex<?= $i; ?>" <?= ($option=='View') ? $disv : '' ?>>
                        <option value="0">-- None --</option>
                        <option value="M" <?= ($option!='Add') ? ($data['sex']=="M" )?'selected': '' : '' ?>>Male</option>
                        <option value="F" <?= ($option!='Add') ? ($data['sex']=="F" )?'selected': '' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Title <strong class="text-danger">*</strong></label>
                    <select class="form-select" name="title<?= $i; ?>"  id="title<?= $i; ?>" <?= ($option=='View') ? $disv : '' ?> required >
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
                    <input type="tel" pattern="[7-9]{1}[0-9]{9}" title="[7-9]{1}[0-9]{9}"  class="form-control w-100 float-start" placeholder="Phone" name="phone_no<?= $i; ?>" id="phoneNo<?= $i; ?>" value="<?= ($option!='Add') ? $data['phone_no'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Fax# </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Fax" name="fax_no<?= $i; ?>" name="faxNo<?= $i; ?>" value="<?= ($option!='Add') ? $data['fax_no'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile# </label>
                    <input type="tel" pattern="[7-9]{1}[0-9]{9}" title="[7-9]{1}[0-9]{9}" class="form-control w-100 float-start" placeholder="Mobile" name="mobile_no<?= $i; ?>" id="mobileNo<?= $i; ?>" value="<?= ($option!='Add') ? $data['mobile_no'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                    <input type="email" class="form-control" placeholder="Email" name="email_id<?= $i; ?>" id="emailId<?= $i; ?>" value="<?= ($option!='Add') ? $data['email_id'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Other Email </label>
                    <input type="email" class="form-control w-100 float-start" placeholder="Other Email" name="email_id_other<?= $i; ?>"  id="emailIdOther<?= $i; ?>" value="<?= ($option!='Add') ? $data['email_id_other'] : '' ?>" <?= $disview; ?>/>
                </div>				
            </div>
        </div>
        
    </div>
</div>
        
    </div>  
</div>
</div>
<?php } ?>
<?php if($option!='Add'){
      foreach($result2 as $i => $value) { $i++; ?>
      
<div class="" id="attentionSection">
<div class="accordion">
    <div class="accordion-item acrdnHedClr" >
        <h2 class="accordion-header" id="heading<?= $i; ?>">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseS<?= $i; ?>" aria-expanded="false" aria-controls="colapseS<?= $i; ?>"> Attention 
        </button>
        </h2>
        <div id="collapseS<?= $i; ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?= $i; ?>" data-bs-parent="#accordionExample2">
        <div class="accordion-body">
        <div class="col-md-12 mt-2">
            <div class="frms-sec d-inline-block w-100 bg-white p-3">
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Code" name="attention_code<?= $i; ?>" id="attentionCode<?= $i; ?>"  value="<?= ($option!='Add') ? $value['attention_code'] : '' ?>" <?=  $redk; ?> readonly/>
                </div>
                <div class="col-md-8 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Name <b class="text-danger">*</b><i class="fw-bold ms-3">( ** Please use Mr or Mrs before name )</i></label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Name" required name="attention_name<?= $i; ?>"  id="attentionName<?= $i; ?>" value="<?= ($option!='Add') ? $value['attention_name'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Designatin </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Designation" name="designation<?= $i; ?>"  id="designation<?= $i; ?>" value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Abbr Name </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Abbr Name" name="short_name<?= $i; ?>"  id="shortName<?= $i; ?>" value="<?= ($option!='Add') ? $value['short_name'] : '' ?>"  <?= $disview; ?>/>
                </div>				
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Sex </label>
                    <select class="form-select" name="sex<?= $i; ?>" name="Sex<?= $i; ?>" <?= ($option=='View') ? $disv : '' ?>>
                        <option value="0">-- None --</option>
                        <option value="M" <?= ($option!='Add') ? ($value['sex']=="M" )?'selected': '' : '' ?>>Male</option>
                        <option value="F" <?= ($option!='Add') ? ($value['sex']=="F" )?'selected': '' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Title <strong class="text-danger">*</strong></label>
                    <select class="form-select" name="title<?= $i; ?>"  id="title<?= $i; ?>" <?= ($option=='View') ? $disv : '' ?> required >
                        <option value="">-- None --</option>
                        <option value="Mr."  <?= ($option!='Add') ?($value['title']=="Mr.")? 'selected': '': ''?>>Mr.</option>
                        <option value="Mrs." <?= ($option!='Add') ?($value['title']=="Mrs.")? 'selected': '': ''?>>Mrs.</option>
                        <option value="Ms."  <?= ($option!='Add') ?($value['title']=="Ms.")? 'selected': '': ''?>>Ms.</option>
                        <option value="Dr."  <?= ($option!='Add') ?($value['title']=="Dr.")? 'selected': '': ''?>>Dr.</option>
                        <option value="Prof."<?= ($option!='Add') ?($value['title']=="Prof.")? 'selected': '': ''?>>Prof.</option>
                        <option value="Rev." <?= ($option!='Add') ?($value['title']=="Rev.")? 'selected': '': ''?>>Rev.</option>
                        <option value="ORS"  <?= ($option!='Add') ?($value['title']=="ORS")? 'selected': '': ''?>>Other.</option>
                    </select>
                </div>
                
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Phone# </label> 
                    <input type="tel" pattern="[7-9]{1}[0-9]{9}" title="[7-9]{1}[0-9]{9}"  class="form-control w-100 float-start" placeholder="Phone" name="phone_no<?= $i; ?>" id="phoneNo<?= $i; ?>" value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Fax# </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Fax" name="fax_no<?= $i; ?>" name="faxNo<?= $i; ?>" value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile# </label>
                    <input type="tel" pattern="[7-9]{1}[0-9]{9}" title="[7-9]{1}[0-9]{9}" class="form-control w-100 float-start" placeholder="Mobile" name="mobile_no<?= $i; ?>" id="mobileNo<?= $i; ?>" value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                    <input type="email" class="form-control" placeholder="Email" name="email_id<?= $i; ?>" id="emailId<?= $i; ?>" value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?= $disview; ?>/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Other Email </label>
                    <input type="email" class="form-control w-100 float-start" placeholder="Other Email" name="email_id_other<?= $i; ?>"  id="emailIdOther<?= $i; ?>" value="<?= ($option!='Add') ? $value['email_id_other'] : '' ?>" <?= $disview; ?>/>
                </div>				
            </div>
        </div>
        
    </div>
</div>

<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<?php $session = session();
        $sessionName=$session->userId; ?>
        <input type="hidden" class="form-control" name="prepared_by" id="preparedBy" value="<?= ($option!='Add') ? $data1['prepared_by'] : $sessionName ?>"/>
        <input type="hidden" class="form-control" name="prepared_on" id="preparedOn" value="<?= ($option!='Add') ? $data1['prepared_on'] : date('Y-m-d') ?>"/>
        <input type="hidden" class="form-control" name="updated_by" id="updatedBy" value="<?= ($option!='Add') ? $sessionName : ''?>"/>
        <input type="hidden" class="form-control" name="updated_on" id="updatedOn" value="<?= ($option!='Add') ? date('Y-m-d') : '' ?>"/>
        <input type="hidden" name="finsub" id="finsub" value="fsub">
        
    </div>  
</div>
</div>
      <?php }} ?>
<div class="d-inline-block w-100 mt-3">
        <button type="submit" class="btn btn-primary cstmBtn ms-2" <?php echo $disview;?>>Save</button>
        <?php if($option=="Delete"){?>
                    <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn ms-2">Delete</button>
                    <?php } ?>
        <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2">Back</a>
        <button type="button" class="btn btn-primary cstmBtn ms-2 mt-0 float-end" onclick="addNewAttention()">Add New Attention</button>
    </div>  
</form>
</main><!-- End #main -->

<script>
    function addNewAddress() {
        let addressCount = document.getElementById('addressCount');
        addressCount.value = parseInt(addressCount.value) + 1;
        let key = addressCount.value;
        let addressSection = `
        <div class="accordion-item acrdnHedClr mt-2">
            <h2 class="accordion-header" id="heading${key}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${key}" aria-expanded="false" aria-controls="collapseTwo">
                Address 
              </button>
            </h2>
            <div id="collapse${key}" class="accordion-collapse collapse" aria-labelledby="heading${key}" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        
                        <div class="frms-sec-insde <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?> float-start col-md-3 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Addr Code</label>
                            <input type="text" class="form-control" name="address_code${key}" id="addressCode${key}" placeholder="Addr Code" value="" readonly/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Address <strong class="text-danger">*</strong></label>
                            <textarea type="text" class="form-control" rows="2" cols="" name="address_line_1${key}" id="addressLine_1${key}" required placeholder="Address" <?php echo $redokadd;?>></textarea>
                        </div>
                        <div class="col-md-<?php if($option == 'Add'){echo '3'; }else{ echo '4'; }?> float-start px-2 position-relative mb-1 hgt100">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">City / Town <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control w-100 float-start" name="city${key}" id="City${key}" required placeholder="City" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 position-relative mb-1 <?php if($option == 'Add'){echo 'hgt100'; }?>">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Pin </label>
                            <input type="text" class="form-control w-100 float-start" name="pin_code${key}" id="pinCode${key}" placeholder="Pin" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 position-relative mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">State<strong class="text-danger">*</strong> </label>
                            <select class="form-select" name="state_code${key}" id="stateCode${key}" required <?php echo $disview;?>>
                                <option value="">-- None --</option>
                                <?php foreach ($data2 as $value) {?>
                                <option value="<?=$value['state_code']; ?>"><?= $value['state_name'] ?></option>
                                <?php } ?>
                                
                            </select>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Country</label>
                            <input type="text" class="form-control" name="country${key}" id="Country${key}" placeholder="Country" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">PAN</label>
                            <input type="text" class="form-control" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" name="pan_no${key}" id="panNo${key}" placeholder="PAN" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">ISD#</label>
                            <input type="text" class="form-control" name="isd_code${key}" id="isdCode${key}" placeholder="ISD" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">STD#</label>
                            <input type="text" class="form-control" name="std_code${key}" id="stdCode${key}" placeholder="STD" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Phone#</label>
                            <input type="rel" pattern="[789][0-9]{9}" class="form-control" name="phone_no${key}" id="phoneNo${key}" placeholder="Phone" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">FAX#</label>
                            <input type="text" class="form-control" name="fax_no${key}" id="fax_no${key}" placeholder="FAX" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                            <input type="email" class="form-control" name="email_id${key}" id="emailId${key}" placeholder="Email" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Web Address</label>
                            <input type="text" class="form-control" name="web_id${key}" id="webId${key}" placeholder="Web Address" value="" <?php echo $redokadd;?>/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">GST</label>
                            <input type="text" class="form-control" name="client_gst${key}" id="clientGst${key}" placeholder="GST" value="" <?php echo $redokadd;?>/>
                        </div>  
                    </div>
                </div>
              </div>
            </div>
          </div> 
        `;
        $("#addressSection").append(addressSection);
       // console.log('hello');
    }

    function addNewAttention() {
        let attentionCount = document.getElementById('attentionCount');
        attentionCount.value = parseInt(attentionCount.value) + 1;
        let key = attentionCount.value;
        let attentionSection = `
        <div class="accordion mt-3">
        <div class="accordion-item acrdnHedClr">
        <h2 class="accordion-header" id="heading${key}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseS${key}" aria-expanded="false" aria-controls="collapseS${key}">
        Attention 
        </button>
        </h2>
        <div id="collapseS${key}" class="accordion-collapse collapse show" aria-labelledby="heading${key}" data-bs-parent="#accordionExample2">
        <div class="accordion-body">
        <div class="col-md-12 mt-2">
            <div class="frms-sec d-inline-block w-100 bg-white p-3">
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Code" name="attention_code${key}" id="attentionCode${key}"  value="" readonly/>
                </div>
                <div class="col-md-8 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Name <b class="text-danger">*</b><i class="fw-bold ms-3">( ** Please use Mr or Mrs before name )</i></label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Name" required name="attention_name${key}"  id="attentionName${key}" value="" />
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Designatin </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Designation" name="designation${key}"  id="designation${key}" value="" />
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Abbr Name </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Abbr Name" name="short_name${key}"  id="shortName${key}" value="" />
                </div>				
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Sex </label>
                    <select class="form-select" name="sex${key}" name="Sex${key}">
                        <option value="0">-- None --</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Title <strong class="text-danger">*</strong></label>
                    <select class="form-select" name="title${key}"  id="title${key}"  required >
                        <option value="">-- None --</option>
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Ms.">Ms.</option>
                        <option value="Dr.">Dr.</option>
                        <option value="Prof.">Prof.</option>
                        <option value="Rev.">Rev.</option>
                        <option value="ORS">Other.</option>
                    </select>
                </div>
                
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Phone# </label> 
                    <input type="tel" pattern="[7-9]{1}[0-9]{9}" title="[7-9]{1}[0-9]{9}"  class="form-control w-100 float-start" placeholder="Phone" name="phone_no${key}" id="phoneNo${key}" value=""/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Fax# </label>
                    <input type="text" class="form-control w-100 float-start" placeholder="Fax" name="fax_no${key}" name="faxNo${key}" value=""/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile# </label>
                    <input type="tel" pattern="[7-9]{1}[0-9]{9}" title="[7-9]{1}[0-9]{9}" class="form-control w-100 float-start" placeholder="Mobile" name="mobile_no${key}" id="mobileNo${key}" value=""/>
                </div>
                <div class="col-md-4 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                    <input type="email" class="form-control" placeholder="Email" name="email_id${key}" id="emailId${key}" value=""/>
                </div>
                <div class="col-md-4 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Other Email </label>
                    <input type="email" class="form-control w-100 float-start" placeholder="Other Email" name="email_id_other${key}"  id="emailIdOther${key}" value=""/>
                </div>				
            </div>
        </div>
        
    </div>
</div>
</div>
</div>
        `;
        $("#attentionSection").append(attentionSection);
       // console.log('hello');
    }
</script>

<?= $this->endSection() ?>