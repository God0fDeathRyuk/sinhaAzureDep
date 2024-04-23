
+<?= $this->extend("layouts/master") ?>  

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
	<?php if (session()->getFlashdata('noted_number') !== NULL) : ?>
		<div id="alertMsg">
			<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
			<div> <b> <?= session()->getFlashdata('noted_number') ?> </b> </div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
    <?php endif; ?>

    <div class="pagetitle col-md-12 float-start border-bottom pb-1">
		<h1>Bill Generation (Matter)</h1> 
		</div>

	<form action="" method="post" id="billRegisterForm" name="billRegisterForm" onsubmit="setBlankValue(event)">
		<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

		<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="d-inline-block w-100">
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Now Bill Upto</label>
						<input type="text" class="form-control datepicker" placeholder="" name="bill_date_upto" value="<?= date('d-m-Y'); ?>" onblur="make_date(this)"/>
					</div>					
				</div>
				
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code <strong class="text-danger">*</strong></label>
					<div class="position-relative d-block float-start w-35">
						<input type="text" class="form-control w-100 float-start" name="matter_code" value="<?= ($option == 'proceed') ? $params['matter_code'] : ''  ?>" oninput="this.value = this.value.toUpperCase();" onfocusout="getMatterValue(this)" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc', 'clientCode', 'clientName'], ['matter_desc', 'client_code', 'client_name'], 'matter_code');" size="05" maxlength="06" <?= $permission ?> required/>
						<?php if($option != 'proceed'){ ?>
						<i class="fa-solid fa-binoculars icn-vw icnVwSmlCntnr" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc', 'clientCode', 'clientName'], ['matter_desc', 'client_code', 'client_name'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
						<?php } ?>
					</div>
					
					<input type="text" class="form-control w-63 ms-2 float-start" name="matter_desc" value="<?= ($option == 'proceed') ? $params['matter_desc'] : ''  ?>" oninput="this.value = this.value.toUpperCase()" id="matterDesc" readonly disable/>
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
					<input type="text" class="form-control w-35 float-start" name="client_code" id="clientCode" value="<?= ($option == 'proceed') ? $params['client_code'] : ''  ?>" oninput="this.value = this.value.toUpperCase()" readonly/>
                    <!-- <i class="fa-solid fa-binoculars icn-vw lft-140" title="View"></i> -->
					<input type="text" class="form-control w-63 ms-2 float-start" name="client_name" id="clientName" value="<?= ($option == 'proceed') ? $params['client_name'] : ''  ?>" oninput="this.value = this.value.toUpperCase()" readonly/>
				</div>
				<div class="col-md-12 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Other Case(s)</label>
					<textarea type="text" class="form-control" rows="3" name="other_case_desc" value="<?= ($option == 'proceed') ? $params['other_case_desc'] : ''  ?>" readonly></textarea>
                    <input type="hidden" name="othcase_cnt" value="" readonly>
                    <input type="hidden" name="othcase_dtl" value="" readonly>
                    <input type="hidden" name="all_sub" value="">
                    <input type="hidden" name="other_ind" value="N">
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">State</label>
					<input type="text" class="form-control" placeholder="" name="state_name" id="stateName" value="<?= ($option == 'proceed') ? $params['state_name'] : ''  ?>" readonly/>
				</div>
				
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
					<input type="text" class="form-control" placeholder="" name="subject_desc" id="subjectDesc" value="<?= ($option == 'proceed') ? $params['subject_desc'] : ''  ?>" readonly/>
				</div>
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Ref No</label>
					<input type="text" class="form-control" placeholder="" name="reference_desc" id="referenceDesc" value="<?= ($option == 'proceed') ? $params['reference_desc'] : ''  ?>" readonly/>
				</div>		
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Remarks</label>
					<input type="text" class="form-control" placeholder="" name="last_remark" id="lastRemark" value="<?= ($option == 'proceed') ? $params['last_remark'] : ''  ?>" readonly/>
				</div>
				<div class="col-md-2 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Court Fee Bill?</label>
					<select class="form-select" name="court_fee_bill_ind">
                        <option value="N">No</option>
                        <option value="Y">Yes</option>
					</select>
				</div>
				<?php if (!isset($report, $report1)){ ?>
					<button type="submit" class="btn btn-primary cstmBtn mt-31 ms-5" onclick="formOption('/billing/generation-matter/', 'proceed', 'billRegisterForm')">Proceed</button>
					
					<button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-31 ms-2">Reset</button>
				<?php } ?>

    		<!-- </form> -->
			<?php if (isset($report, $report1)){ ?>
			<!-- <form action="" method="post" id="" name=""> -->
				<input type="hidden" id="row_no_case" name="row_no_case" value="<?= $params['bill_cnt']?>" >
				<input type="hidden" id="row_no_other_exp" name="row_no_other_exp" value="<?= $params['bill_cnt1']?>" >
				<input type="hidden" id="client_code" name="client_code" value="<?= $params['client_code']?>" >
				<input type="hidden" id="court_fee_bill_ind" name="court_fee_bill_ind" value="<?= $params['court_fee_bill_ind']?>" >
				<input type="hidden" id="other_case_desc" name="other_case_desc" value="<?= $params['other_case_desc']?>" >
				<div class="d-inline-block w-100 mt-2 mb-2 bnd">
					Case Detail(s):
				</div>

                
				<div class="mntblsec">
					<table class="table table-bordered">
						<tr class="fs-14">
							<th><span>Date</span></th>
							<th><span>Details</span></th>
							<th><span>Next Date</span></th>
							<th><span>Fixed For</span></th> 
							<th><span>Gen?</span> <input type="checkbox" id="gen_chk_case" name="gen_chk_case" value="Y" onClick="checkAllData()"  <?php if($params['bill_cnt'] > 0) { echo 'checked'; }?>/></th>
						</tr>
                        <?php foreach($report as $key => $row) { ?> 
						<tr>
							<td style="width:150px;"><input type="text" class="form-control" name="activity_date<?php echo $key+1?>" id="activity_date<?php echo $key+1?>" value="<?= date_conv($row['activity_date']) ?>" readonly></td>
							<td class="optnNone"><textarea class="form-control ckeditor" name="datails<?php echo $key+1?>" id="datails<?php echo $key+1?>" readonly><?= stripslashes($row['header_desc']) ?></textarea></td>
							<td style="width:150px;"><input type="text" class="form-control" name="next_date<?php echo $key+1?>" id="next_date<?php echo $key+1?>" value="<?= date_conv($row['next_date']) ?>" readonly></td>
							<td><input type="text" class="form-control" name="next_fixed_for<?php echo $key+1?>" id="next_fixed_for<?php echo $key+1?>" value="<?= stripslashes($row['next_fixed_for']) ?>" readonly></td>
                            <td class="text-center pt-3">
								<input type="checkbox" class="" id="case_check<?php echo $key+1?>"  value="Y" name="case_check<?php echo $key+1?>" checked="checked" onClick="cal_data<?php echo $key+1?>">
								<input type="hidden" class="form-control" id="serial_no<?php echo $key+1?>" value="<?= $row['serial_no']?>" name="serial_no<?php echo $key+1?>"/>
								<input type="hidden" class="form-control" id="othcase_no<?php echo $key+1?>" value="<?= stripslashes($row['case_no'])?>" name="othcase_no<?php echo $key+1?>"/>
							</td>
                            

						</tr>
                        <?php } if(!count($report)) { ?> <tr> <td colspan="5" class="text-center">No Records Found !!</td> </tr> <?php } ?>
					</table>
				</div>
				<div class="d-inline-block w-100 mt-2 mb-2 bnd">
					<span class="text-white">Other Expence(s):</span>
				</div>
				<div class="d-inline-block w-100 mt-2">
					<table class="table table-bordered">
						<tr class="fs-14">
							<th>
								<span></span>
							</th>
							<th>
								<span>Date</span>
							</th>
							<th style="width:40%;">
								<span>Details</span>
							</th>
							<th>
								<span>Amount</span>
							</th>
							<th>
								<span>Gen?</span> <input type="checkbox" name="gen_chk_oe"  id="gen_chk_oe"  value="Y" onClick="checkAll_OE_Data()" <?php if($params['bill_cnt1'] > 0) { echo 'checked'; }?>/>
							</th>
						</tr>
                        <?php foreach($report1 as $key1 => $row_1) { ?> 
						<tr>
							<td>
							    <input type="hidden" class="form-control" id="expn_table<?php echo $key1+1?>" value="<?= $row_1['expn_table']?>" name="expn_table<?php echo $key1+1?>" readonly/> 
							</td>
							<td style="width:150px;">
								<input type="text" class="form-control" name="date<?php echo $key1+1?>" id="date<?php echo $key1+1?>" value="<?= $row_1['date'] ?>" readonly>
							</td>
							<td class="brkwrd">
								<input type="text" class="form-control" name="details<?php echo $key1+1?>" id="details<?php echo $key1+1?>" value="<?= $row_1['details']?>" readonly> 
							</td>
							<td>
								<input type="text" class="form-control" name="amount<?php echo $key1+1?>" id="amount<?php echo $key1+1?>" value="<?= number_format($row_1['amount'],2,'.','') ?>" readonly>
							</td>
							<td class="text-center pt-3">
								<input type="checkbox" class="" id="oe_check<?php echo $key1+1?>"  value="Y" name="oe_check<?php echo $key1+1?>" onClick="cal_oe_data<?php echo $key1+1?>" checked="checked">
							</td>
						</tr>
                        <?php } if(!count($report1)) { ?> <tr> <td colspan="5" class="text-center">No Records Found !!</td> </tr> <?php } ?>
					</table>
				</div>
			</div>
			<input type="hidden" name="finsub" id="finsub" value="fsub">
			<button type="submit" class="btn btn-primary cstmBtn mt-3" >Confirm</button>
			<!-- <button type="button" class="btn btn-primary cstmBtn mt-3 ms-2">Reset</button> -->
			<a href="<?= base_url($params['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
			<?php } ?>
		</form>
</main>

<script>
    function getMatterValue(e) {
		if(e.value != '') {
			fetch(`${baseURL}/api/matterDetails/${e.value}/notFound`)
			.then((response) => response.json())
			.then((data) => {
				console.log('============================> ');
				console.log(data);
				document.getElementById("stateName").value=data.state_name;
				document.getElementById("lastRemark").value = data.last_remark;
				document.getElementById("subjectDesc").value = data.subject_desc;
				document.getElementById("referenceDesc").value = data.reference_desc;
			});
		}
    }
	
	function cal_data(no) {
		//alert('abc');
		counter = document.getElementById('row_no_case').value;	
		var chk_indi = true;
		for(var i = 1; i <= counter; i++) {
			if(document.getElementById("case_check"+i).checked == false) {
				chk_indi=false;
				document.getElementById('gen_chk_case').checked = false;
			}
		}	
		if(chk_indi==true) {
			document.getElementById('gen_chk_case').checked=true;
		}
	}	
	
	function checkAllData() {
		counter = document.getElementById('row_no_case').value;
		if(document.getElementById('gen_chk_case').checked == true) {
			for(var i=1;i<=counter;i++) {
			document.getElementById("case_check"+i).checked = true;
			}
		}
		else {
			for(var i=1;i<=counter;i++) {
				document.getElementById("case_check"+i).checked = false;
			}
		}
	}

	function checkAll_OE_Data() {
		counter = document.getElementById('row_no_other_exp').value;
		if(document.getElementById('gen_chk_oe').checked == true) { 
			for(var i=1;i<=counter;i++) {
				document.getElementById("oe_check"+i).checked = true;
			}
		}
		else {
			for(var i=1;i<=counter;i++) {
				document.getElementById("oe_check"+i).checked = false;
			}
		}
	}

	function cal_oe_data(no) {
		counter = document.getElementById('row_no_other_exp').value;	
		var chk_indi = true;
		for(var i = 1; i <= counter; i++) {
			if(document.getElementById("oe_check"+i).checked == false) {
				chk_indi = false;
				document.getElementById('gen_chk_oe').checked = false;
			}
		}	
		if(chk_indi==true) {
			document.getElementById('gen_chk_oe').checked = true;
		}
	}	

	function setBlankValue(e) {
		e.preventDefault();
		console.log(document.billRegisterForm);
		
		document.billRegisterForm.othcase_cnt.value;
		document.billRegisterForm.othcase_dtl.value.replace('&','|and|');
		
		document.billRegisterForm.submit();
	}
</script>
<?= $this->endSection() ?>