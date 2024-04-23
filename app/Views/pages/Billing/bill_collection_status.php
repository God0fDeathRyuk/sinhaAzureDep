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
        <h1>Bill Collection Status</h1>       
    </div>
    <form action="" method="get" id="billCollectionStatus" name="billCollectionStatus">
  <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
  <div class="frms-sec d-inline-block w-100 bg-white p-3 pt-5 position-relative">
				<button type="button" class="btn btn-primary mt-3 float-start mb-3 ms-2 tpbdgeSec">Maintenance</button>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
					<select class="form-select cstm-inpt" name="branch_code" <?= $permission ?>>
                    <?php foreach($data['branches'] as $branch) { ?>
                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
                    </select>
				</div>
				<div class="col-md-4 float-start mb-3">
                    <span class="float-start mb-2 lbl-mn">Date From</span>
                    <input class="form-control float-start w-100 set-date datepicker" id="dateFrom" type="text" name="date_from" value="<?= ($option == 'proceed') ? date_conv($params['date_from']) : ''  ?>" onBlur="make_date(this)" <?= $permission ?>/>
                </div>
                <div class="col-md-4 float-start mb-4 pe-2">
                    <span class="float-start mb-2 lbl-mn">Date To</span>
                  <input class="form-control float-start w-100 ms-2 set-date datepicker withdate" id="dateTo" type="text" name="date_to" value="<?= ($option == 'proceed') ? date_conv($params['date_to']) : ''  ?>" onBlur="make_date(this)" <?= $permission ?>/>
                </div>
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">By</label>
					<select class="form-select w-100 float-start" name="client_matter" onchange="selectLookup(this)" <?= $permission ?> required>
                        <option value="">- Select -</option>
                        <option value="Client" <?php if($option == 'proceed') { if($params['client_matter'] == 'Client') { echo 'selected' ; }}  ?>>Client</option>
                        <option value="Matter" <?php if($option == 'proceed') { if($params['client_matter'] == 'Matter') { echo 'selected' ; }}?>>Matter</option>
					<select>
                </div>

                <div class="col-md-9 float-start mb-3">
                    <div id="lookupBtn">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                        <input type="text" class="form-control w-25 float-start ms-2" name="input_code" id="inputCode" value="<?= ($option == 'proceed') ? ($params['input_code']) : ''  ?>" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" required/>
                        <input type="text" class="form-control w-72 float-start ms-2" name="input_name" id="inputName" value="<?= ($option == 'proceed') ? ($params['input_name']) : ''  ?>" readonly/>
                    </div>
                </div>
                
				
				<button type="submit" class="btn btn-primary cstmBtn mt-4 float-start mb-3" onclick="formOption('/billing/collection-status/', 'proceed', 'billCollectionStatus')">Proceed</button>
				<button type="reset" class="btn btn-primary cstmBtn mt-4 float-start mb-3 ms-2">Reset</button>
</form>	
<?php if (isset($report)){ ?>
<form action="" method="post" id="" name="">			
				<div class="mntblsec d-inline-block w-100 mt-2 mb-3 ScrltblMn">
					<table class="table table-bordered">
						<tbody>
							<tr class="fs-14">
								<th><span>&nbsp;</span></th>
								<th><span>Bill Year</span></th>
								<th><span>Bill No.</span></th>
								<th><span>Bill Date</span></th>
								<th><span>Client</span></th>
								<th class="w-24"><span>Name</span></th>
								<th><span>Matter</span></th>
								<th class="w-24"><span>Description</span></th>
								<th><span>Amount</span></th>
								<th><span>Collectable</span></th>
							</tr>
                            <?php foreach($report as $key => $row ) { ?>
							<tr>
								<td><input type="hidden" name="voucher_ok_ind<?php echo $key+1?>"  value="Y" readonly/></td>
								<td><input type="text" name="ref_bill_year<?php echo $key+1?>"   value="<?= $row['fin_year'] ?>"        readonly/></td>
								<td><input type="text" name="ref_bill_no<?php echo $key+1?>"     value="<?= $row['bill_no'] ?>"         readonly/></td>
								<td><input type="text" name="ref_bill_date<?php echo $key+1?>"   value="<?= $row['bill_date'] ?>"       readonly/></td>
								<td><input type="text" name="client_code<?php echo $key+1?>"     value="<?= $row['client_code'] ?>"     readonly/></td>
								<td><input type="text" name="client_name<?php echo $key+1?>"     value="<?= $params['client_name'][$key] ?>"  readonly/></td>
								<td><input type="text" name="matter_code<?php echo $key+1?>"     value="<?= $row['matter_code'] ?>"     readonly/></td>
								<td><input type="text" name="matter_name<?php echo $key+1?>"     value="<?= $params['matter_name'][$key] ?>"  readonly/></td>
								<td><input type="text" name="realise_amount<?php echo $key+1?>"  value="<?= $row['realise_amount'] ?>"  readonly/></td>
								<td><select name="collectable_ind<?php echo $key+1?>" value="<?= $row['fin_year'] ?>">
                                
                                </select>
								<td><input type="hidden" name="bill_serial_no<?php echo $key+1?>"  value="<?= $row['serial_no'] ?>"/></td>
                                </td>
							</tr>
                            <?php } ?>
						</tbody>
					</table>
				</div>
				<button type="button" class="btn btn-primary cstmBtn mt-2">Confirm</button>
				<a href="<?= base_url($data['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
			
		</div>
  </form>
  <?php } ?>
</main>
<script>
    function selectLookup(select) {
        //alert('abc');
        let selectValue = select.value;
        let lookupDiv = document.getElementById("lookupBtn");
        let inputCode = document.getElementById("inputCode");
        let inputName = document.getElementById("inputName");

    
        if(selectValue == 'Client') {

            inputCode.readOnly = false;
            inputCode.value = ''; inputName.value = ''; inputCode.focus(); 
            
            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-25">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['inputName'], ['client_name'], 'client_code')" size="05" maxlength="06" />
                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'inputCode', ['inputName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <input type="text" class="form-control w-72 float-start ms-2 mtop" name="input_name" id="inputName" readonly/>
            `;

        }
        else if (selectValue == 'Matter') {
            
            inputCode.readOnly = false;
            inputCode.value = ''; inputName.value = ''; inputCode.focus(); 

            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-25">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'matter_code', ['inputName'], ['mat_des'], 'matter_code')" size="05" maxlength="06"/>
                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'inputCode', ['inputName'], ['mat_des'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <input type="text" class="form-control w-72 float-start ms-2 mtop" name="input_name" id="inputName" readonly/>
            `;
        }
        else { 

            inputCode.readOnly = true;
            inputCode.value = ''; inputName.value = '';

            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" readonly/>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
    }
</script>
<?= $this->endSection() ?>