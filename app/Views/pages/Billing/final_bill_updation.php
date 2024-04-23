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

    <div class="pagetitle col-md-12 float-start pb-1">
		<h1>Final Bill Updation (Client/Address/Attention)</h1> 
		</div>
<form action="" method="post" id="finalBillUpdation" name="finalBillUpdation" onsubmit="setFocus(event)">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-4 float-start px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
                    <select class="form-select" name="branch_code">
                        <?php foreach($data['branches'] as $branch) { ?>
                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                        <?php } ?>
                    </select>
				</div>
				<div class="col-md-8 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Year/No <strong class="text-danger">*</strong></label>
					<select class="form-select w-25 float-start"  name="bill_year">
						<?php foreach($billyr_qry as $billyr_row) { ?> 
						<option value="<?php echo $billyr_row['fin_year']?>" <?php if($branch_code == $billyr_row['fin_year']) { echo 'selected'; }?>><?php echo $billyr_row['fin_year']?></option>  
						<?php } ?>
					</select>
					<input class="form-control float-start w-72 ms-3" type="text" name="bill_no" value="<?= ($option == 'show') ? $params['billno'] : ''  ?>" oninput="this.value = this.value.toUpperCase();" required>
					<input type="hidden" name="bill_serial_no" value="<?= ($option == 'show') ? $params['xSerialNo'] : ''  ?>">
					<input type="hidden" name="status_code"    value="">
				</div>
				<?php if($option != 'show') {?>
				<button type="submit" class="btn btn-primary cstmBtn mt-2 float-start mb-3" onclick="formOption('/billing/final-bill-updation/', 'show', 'finalBillUpdation')">Show</button>
				<?php } ?>
</form>
<?php if (isset($params)){ ?>
<form action="" method="post" id="finalBillUpdation2" name="finalBillUpdation2" onsubmit="">
		<input type="hidden" name="bill_serial_no" value="<?= $params['xSerialNo'] ?>">

				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter</label>
					<input type="text" class="form-control w-35 float-start" name="matter_code"  value="<?= ($option == 'show') ? $params['xMatterCode'] : ''  ?>" readonly>
					<input type="text" class="form-control w-63 ms-2 float-start" name="matter_desc"  value="<?= ($option == 'show') ? $params['xMatterDesc'] : ''  ?>" readonly>
				</div>
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client</label>
					<input type="text" class="form-control w-35 float-start" name="client_code"  value="<?= ($option == 'show') ? $params['xClientCode'] : ''  ?>" readonly>
					<input type="text" class="form-control w-63 ms-2 float-start" name="client_name"  value="<?= ($option == 'show') ? $params['xClientName'] : ''  ?>" readonly>
				</div>
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Address</label>
					<input type="text" class="form-control w-35 float-start" name="address_code"  value="<?= ($option == 'show') ? $params['xAddrCode'] : ''  ?>" readonly>
					<input type="text" class="form-control w-63 ms-2 float-start" name="address_desc"  value="<?= ($option == 'show') ? $params['xAddrDesc'] : ''  ?>" readonly>
				</div>
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Attention</label>
					<input type="text" class="form-control w-35 float-start" name="attention_code"  value="<?= ($option == 'show') ? $params['xAttnCode'] : ''  ?>" readonly>
					<input type="text" class="form-control w-63 ms-2 float-start" name="attention_name"  value="<?= ($option == 'show') ? $params['xAttnName'] : ''  ?>" readonly>
				</div>
				<div class="d-inline-block w-100 mb-3">
					<hr/>
					<div class="col-md-4 float-none px-2 mb-3 pb-2">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Want To Change</label>
						<select class="form-select" name="change_ind" onChange="setNewDetails()" required>
							<option value=""  >-Select-</option>  
							<option value="AD">Address</option>  
							<option value="AT">Attention</option>  
						</select>
					</div>
					<hr/>
				</div>
				<div class="col-md-12 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">New Client</label>
					<input type="text" class="form-control w-25 float-start" type="text" name="new_client_code" id="newClientCode"  value="" readonly>
					<input type="text" class="form-control w-72 ms-2 float-start" type="text" name="new_client_name" name="newClientName"  value="" readonly/>
				</div>
				<div class="col-md-12 float-start px-2 mb-3" id="lookUpBtn">
					<label class="d-inline-block w-100 mb-2 lbl-mn">New Address</label>
					<div class="position-relative d-block float-start w-25 Inpt-Vw-icn">
						<input type="text" class="form-control w-100 float-start readonly" type="text" name="new_address_code" id="newAddressCode"  value="" required>
						<i class="fa-solid fa-binoculars icn-vw" id="NewAddressLookup" onclick="showData('address_code', 'display_id=<?= $displayId['client_help_id'] ?>&myClientCode=@newClientCode&myAddressCode=@newAddressCode|%', 'newAddressCode', ['newAddressDesc', 'newAttentionCode', 'newAttentionDesc'], ['address', 'attention_code', 'attention_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>						
					</div>
					<input type="text" class="form-control w-72 ms-2 float-start" type="text"   size="83" maxlength="50" name="new_address_desc" id="newAddressDesc"  value="" readonly/>
				</div>
				<div class="col-md-12 float-start px-2 mb-3" id="lookUpBtn2">
					<label class="d-inline-block w-100 mb-2 lbl-mn">New Attention</label>
					<div class="position-relative d-block float-start w-25 Inpt-Vw-icn">
						<input type="text" class="form-control w-100 float-start readonly" type="text"   size="05" maxlength="06" name="new_attention_code" id="newAttentionCode"  value="" required>
						<i class="fa-solid fa-binoculars icn-vw" id="NewAttentionLookup" onclick="showData('attention_code', 'display_id=<?= $displayId['client_help_id'] ?>&myClientCode=@newClientCode&myAddressCode=@newAddressCode|%', 'newAttentionCode', ['newAttentionDesc'], ['attention_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
					</div>
					<input type="text" class="form-control w-72 ms-2 float-start" type="text"   size="83" maxlength="50" name="new_attention_name" id="newAttentionDesc" value="" readonly/>
				</div>
				<input type="hidden" name="finsub" id="finsub" value="fsub">
				<div class="d-inline-block w-100 mb-3 px-2">
					<button type="submit" class="btn btn-primary cstmBtn mt-3">Confirm</button>
					<button type="reset" class="btn btn-primary cstmBtn mt-3 ms-2">Reset</button>
					<a href="<?= base_url($data['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
				</div>
</div>
</form>
<?php } ?>
</main>
<script>
	function setNewDetails()
      {
        
         if(document.finalBillUpdation2.change_ind.value == 'AD')
        {
          document.finalBillUpdation2.new_client_code.value    = document.finalBillUpdation2.client_code.value  ; 
          document.finalBillUpdation2.new_client_name.value    = document.finalBillUpdation2.client_name.value  ; 
          document.finalBillUpdation2.new_address_code.value   = ''  ; 
          document.finalBillUpdation2.new_address_desc.value   = ''  ; 
          document.finalBillUpdation2.new_attention_code.value = ''  ; 
          document.finalBillUpdation2.new_attention_name.value = ''  ; 
          //document.finalBillUpdation2.help_1.style.visibility = 'hidden'  ; 
          //document.finalBillUpdation2.help_2.style.visibility = 'visible'  ; 
          //document.finalBillUpdation2.help_3.style.visibility = 'hidden'  ; 
		  
        }
        else if(document.finalBillUpdation2.change_ind.value == 'AT')
        {
          document.finalBillUpdation2.new_client_code.value    = document.finalBillUpdation2.client_code.value  ; 
          document.finalBillUpdation2.new_client_name.value    = document.finalBillUpdation2.client_name.value  ; 
          document.finalBillUpdation2.new_address_code.value   = document.finalBillUpdation2.address_code.value  ; 
          document.finalBillUpdation2.new_address_desc.value   = document.finalBillUpdation2.address_desc.value  ; 
          document.finalBillUpdation2.new_attention_code.value = ''  ; 
          document.finalBillUpdation2.new_attention_name.value = ''  ; 
          //document.finalBillUpdation2.help_1.style.visibility = 'hidden'  ; 
          //document.finalBillUpdation2.help_2.style.visibility = 'hidden'  ; 
          //document.finalBillUpdation2.help_3.style.visibility = 'visible'  ; 
        }

        //document.finalBillUpdation2.conf_button.disabled = false ;
        //document.finalBillUpdation2.rese_button.disabled = false ;
      }
</script>
<?= $this->endSection() ?>