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
        <h1>Bill Print</h1>      
    </div>
<form action="" method="post">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    <div class="frms-sec d-inline-block w-100 bg-white p-3">
        <div class="d-inline-block w-100">
            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-2">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
                <select class="form-select cstm-inpt" name="branch_code">
                <?php foreach($data['branches'] as $branch) { ?>
                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                <?php } ?>
                </select>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-2">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Bill For <strong class="text-danger">*</strong></label>
                <select class="form-select" name="client_matter" onchange="selectLookup(this)" required>
                    <option value="">- Select -</option>
                    <option value="Range">Range</option>
                    <option value="Client">Client</option>
                    <option value="Matter">Matter</option>
                    <option value="Slected">Selected</option>
                </select>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-7 ps-2 mb-2">
                <label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
                <span class="float-start mt-2">From</span>
                <input class="form-control w-42 float-start ms-2" id="rangeFrom" type="text" name="range_from" onBlur="myRangeTo()" disabled/>
                <span class="float-start mt-2 ms-2">To</span>
                <input class="form-control w-42 float-start ms-2" id="rangeTo" type="text" name="range_to" disabled/>
            </div>										
        </div>
        <div class="col-md-3 float-start px-2 ps-0 mb-2">
            <label class="d-inline-block w-100 mb-2 lbl-mn ps-2">Bill Date</label>
            <input type="text" placeholder="dd-mm-yyyy"  class="form-control float-start w-100 ms-2 set-date datepicker withdate" name="final_bill_date" onBlur="make_date(this)"/>
        </div>
        <div class="col-md-3 float-start px-2 position-relative mb-2">
            <label class="d-inline-block w-100 mb-2 lbl-mn">Format</label>
            <select class="form-select" name="bill_format" readonly>
                <option value="S">Service Tax</option>
            </select>
        </div>
        <div class="col-md-6 float-start px-2 mb-2" id="lookupBtn">            
            <div class="float-start position-relative mb-3 w-35">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" readonly/>
            </div>
            <input type="text" class="form-control ms-2 float-start mt-31 w-63" name="input_name" id="inputName" readonly/>
        </div>
        <div class="col-md-6 float-start px-2 mb-2" id="">            
            <div class="float-start position-relative mb-3 w-100">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Selected Range</label>
                <textarea rows="3" class="form-control w-100 float-start" name="input_range" id="inputRange" readonly="readonly" required ></textarea>
            </div>            
        </div>
        <div class="col-md-3 float-start px-2 mb-2" id="">            
            <div class="float-start position-relative mb-3 w-100">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Year</label>
                <input type="text" class="form-control w-100 float-start" name="input_year" id="inputYear" readonly/>
            </div>            
        </div>
        <div class="d-inline-block w-100 mt-2 ps-2">
            <button type="submit" class="btn btn-primary cstmBtn">Proceed</button>
            <button type="reset" class="btn btn-primary cstmBtn btn-cncl ms-2">Reset</button>
        </div>
        
    
</form>

<?php if (isset($reports)) { ?>
<input type="hidden" id="bill_count" value="<?= $params['bill_cnt']?>" >
                <div class="d-inline-block w-100 mt-4">
					<div class="d-inline-block w-100 scrlTblMd">
						<table class="table table-bordered tblhdClr">
							<tbody>
								<tr>
									<th>
										<span></span>
									</th>
									<th>
										<span class="fntSml">Bill No</span>
									</th>
									<th>
										<span class="fntSml">Bill Date</span>
									</th>
									<th>
										<span class="fntSml">Client</span>
									</th>
									<th>
										<span class="fntSml">Client Name</span>
									</th>
									<th>
										<span class="fntSml">Matter</span>
									</th>
									<th>
										<span class="fntSml">Matter Description</span>
									</th>
									<th>
										<span class="fntSml">Ind</span>
									</th>
								</tr>
                                <?php foreach($reports as $key => $row) { ?> 
                                  
								<tr>
                                    <td>
										<span></span>
									</td>
									<td>
										<span><?= $row['bill_number'] ?></span>
									</td>
									<td>
										<span><?= $row['bill_date'] ?></span>
									</td>
									<td>
										<span><?= $row['client_code'] ?></span>
									</td>
									<td style="width:15%;">
										<span><?= $params['client_name'] ?></span>
									</td>
									<td class="brkwrd" style="width:20%;">
										<span><?= $row['matter_code'] ?></span>
									</td>
									<td>
										<span><?= $params['matter_name'] ?></span>
									</td>
									<td>
                                        <input type="checkbox" class="" id="print_ind<?php echo $key+1?>"  value="Y" >
									</td>
                                    <td>
										<input type='hidden' value="<?= $row['serial_no'] ?>"/>
									</td>
								</tr>
                                <?php } ?>
							</tbody>
						</table>
					</div>

					<div class="d-block w-100 mt-1">
						<button type="button" class="btn btn-primary cstmBtn mt-3 float-start" disabled>Laser</button>
						<button type="button" class="btn btn-primary cstmBtn mt-3 float-start ms-2" disabled>Dot Matrix</button>
						<a href="<?= base_url($data['requested_url']) ?>" class="btn btn-primary cstmBtn mt-3 float-start ms-2">Back</a>
						
						<div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
							<input type="radio" id="select_all"  name="Select" onClick="myselect('S')" />
							<label for="slctAl" class="ms-2">Select All</label>
						</div>
						<div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
							<input type="radio" id="select_all" name="Select" onClick="myselect('D')"/>
							<label for="deslctAl" class="ms-2">De Select All</label>
						</div>
						<div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
							<input type="checkbox" id="dpltbl" name="Select"/>
							<label for="dpltbl" class="ms-2">Duplicate Bill</label>
						</div>
                        <div class="d-block float-start mt-3 cstmRdobtn mb-1 ms-2">
							<input type="checkbox" id="rvcdbl" name="Select"/>
							<label for="rvcdbl" class="ms-2">Revised Bill</label>
						</div>					
					</div>			
				</div>		
    
<?php } ?>
</main>
</div>
<script>
    
    function selectLookup(select) {

        let selectValue = select.value;
        let lookupDiv = document.getElementById("lookupBtn");
        let rangeFrom = document.getElementById("rangeFrom");
        let rangeTo = document.getElementById("rangeTo");
        let inputCode = document.getElementById("inputCode");
        let inputName = document.getElementById("inputName");
        let inputRange = document.getElementById("inputRange");
        let inputYear = document.getElementById("inputYear");

        if(selectValue == 'Range')
        {
            rangeFrom.disabled = false; rangeTo.disabled = false; rangeFrom.required = true; rangeTo.required = true; inputCode.readOnly = true; inputRange.required = false;
            rangeFrom.value = ''; rangeTo.value = ''; inputCode.value = ''; inputName.value = '';
            rangeFrom.focus() ; rangeFrom.select() ;

            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" readonly/>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
        else if(selectValue == 'Client') {

            rangeFrom.disabled = true; rangeTo.disabled = true; inputCode.readOnly = false; inputRange.required = false;
            rangeFrom.value = ''; rangeTo.value = ''; inputCode.value = ''; inputName.value = ''; inputCode.focus(); 
            
            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['inputName'], ['client_name'], 'client_code')" size="05" maxlength="06" />
                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'inputCode', ['inputName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
        else if (selectValue == 'Matter') {
            
            rangeFrom.disabled = true; rangeTo.disabled = true; inputCode.readOnly = false; inputRange.required = false;
            rangeFrom.value = ''; rangeTo.value = ''; inputCode.value = ''; inputName.value = ''; inputCode.focus(); 

            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'matter_code', ['inputName'], ['matter_desc'], 'matter_code')" size="05" maxlength="06"/>
                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'inputCode', ['inputName'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
        else if(selectValue == 'Slected'){

            rangeFrom.disabled = true; rangeTo.disabled = true; inputCode.readOnly = true; inputRange.readOnly = false; inputYear.readOnly = false;
            rangeFrom.value = ''; rangeTo.value = ''; inputCode.value = ''; inputName.value = ''; inputRange.required = true; inputRange.focus(); 

            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" readonly/>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
        else {

            rangeFrom.disabled = true; rangeTo.disabled = true; inputCode.readOnly = true; inputRange.required = false;
            rangeFrom.value = ''; rangeTo.value = ''; inputCode.value = ''; inputName.value = '';

            lookupDiv.innerHTML = `
                <div class="float-start px-2 position-relative mb-3 w-35">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
                    <input type="text" class="form-control w-100 float-start" name="input_code" id="inputCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" readonly/>
                </div>
                <input type="text" class="form-control ms-3 float-start mt-31 w-60" name="input_name" id="inputName" readonly/>
            `;
        }
    }

    function myselect(param) {
        var bill_count = document.getElementById("bill_count").value;
        //alert(bill_count);
         if (param == 'S') { var ind = true ; } else { var ind = false ; }
		 //
		 for (i=1; i<=bill_count; i++)
		 {
		  document.getElementById("print_ind"+i).checked = ind;
          // ("#print_ind"+i).prop('checked', true);
		 }
    }

    function myRangeTo() {
        
        if (document.getElementById("rangeTo").value == '') { 

            document.getElementById("rangeTo").value = document.getElementById("rangeFrom").value ; 
        }
    }
</script>
<?= $this->endSection() ?>