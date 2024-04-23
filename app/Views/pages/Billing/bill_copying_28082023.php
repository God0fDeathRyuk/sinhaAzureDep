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

<div class="pagetitle col-md-12 float-start border-bottom pb-1">
  <h1>Bill Copying</h1>
</div>
<form action="" method="get" id="billCopying" name="billCopying" onsubmit="">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

<div class="frms-sec d-inline-block w-100 bg-white p-3">
				
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3 position-relative">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Serial#</label>
                    <input type="text" class="form-control cstm-inpt" id="SerialNo" value="" onchange="fetchData(this, 'serial_no', ['billNo','billAmount','matterCode1'], [], 'serial_no')" onfocusout="getMatterValue(this)" name="serial_no" required />
                    <i class="fa-solid fa-eye inpt-vw pe-2" onclick="showData('serial_no', '<?= $displayId['casesrl_help_id'] ?>', 'SerialNo', ['billNo','billAmount','matterCode1'], [], 'serial_no')" data-toggle="modal" data-target="#lookup"></i>
					<input type="hidden" name="court_fee_bill_ind" id="courtFeeBillInd" value=""/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill No</label>
					<input type="text" class="form-control" name="bill_no" id="billNo" value="" readonly />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Amount</label>
					<input type="text" class="form-control" name="bill_amount" id="billAmount" value="" readonly/>
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter </label>
					<input type="text" class="form-control w-35 float-start" name="matter_code1" id="matterCode1" value="" readonly/>
					<input type="text" class="form-control w-63 ms-2 float-start" name="matter_desc1" id="matterDesc1" value="" readonly/>
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client </label>
					<input type="text" class="form-control w-35 float-start" name="client_code1" id="clientCode1" value="" readonly/>
					<input type="text" class="form-control w-63 ms-2 float-start" name="client_name1" id="clientName1" value="" readonly/>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Ref No</label>
					<input type="text" class="form-control" placeholder="Ref No"  name="reference_desc" id="referenceDesc" value="" readonly/>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
					<textarea type="text" class="form-control" rows="3" name="subject_desc" id="subjectDesc" readonly></textarea>
				</div>	
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Other Case(s)</label>
					<textarea type="text" class="form-control" rows="3" name="other_case_desc" id="otherCaseDesc" readonly></textarea>
				</div>
				
				<button type="submit" class="btn btn-primary cstmBtn mt-31" onclick="formOption('/billing/copying/', 'proceed', 'billCopying')">Proceed</button>
</form>
<?php if (isset($res)){ ?>
<form action="" method="post" id="billCopying2" name="billCopying2">
<input type="hidden" name="row_counter1" id="row_counter1" value="<?php echo $count;?>">
<input type="hidden" name="cur_date" id="cur_date" value="<?php echo $cur_date;?>">

				<div class="d-inline-block w-100 mt-4 mb-2 bnd">
					<span class="d-block float-start w-75">Bill Details</span>
					<div class="d-block float-start w-25">
						<div class="d-block float-start me-4">
							<input type="radio" name="grant_bill" class="me-1" id="slctAll" value="A" onClick="selectAll_from()" <?php if($count > 0) { echo 'checked'; }?>/>
							<label for="slctAll">Select All</label>
						</div>
						<div class="d-block float-start">
							<input type="radio" name="grant_bill" class="me-1" id="deslctAll" value="D" onClick="deSelectAll_from()"/>
							<label for="deslctAll">De-Select All</label>
						</div>
					</div>
				</div>
				<div class="mntblsec mb-5">
					<table class="table table-bordered tblhdClr">
						<tr>
							<th class="fntSml"><span>&nbsp;</span></th>
							<th class="fntSml"><span>Date</span></th>
							<th class="fntSml"><span>Cnsl</span></th>
							<th class="fntSml"><span>Particulars</span></th>
							<th class="fntSml"><span>I/O</span></th>
							<th class="fntSml"><span>Amount</span></th>
							<th class="fntSml"><span>Copy?</span></th>
						</tr>
						<?php foreach($res as $key => $row) { ?> 
						<tr>
							<td id="Ctd<?php echo $key+1?>">
								<input type="hidden" 	 name="inp_ok_ind<?php echo $key+1?>" 	 value="" readonly/>
								<input type="hidden" name="source_code<?php echo $key+1?>" 	 value="<?= $row['source_code'] ?>"/>
								<input type="hidden" name="activity_type<?php echo $key+1?>" value="<?= $row['activity_type'] ?>"/>
								<input type="hidden" name="printer_ind<?php echo $key+1?>" 	 value="<?= $row['printer_ind'] ?>"/>
								<input type="hidden" name="prn_seq_no<?php echo $key+1?>" 	 value="<?= $row['prn_seq_no'] ?>"/>
							</td>
							<td><input type="text" 	name="activity_date<?php echo $key+1?>" value="<?= date_conv($row['activity_date']) ?>" readonly /></td>
							<td><input type="text" 	name="counsel_code<?php echo $key+1?>" 	value="<?= $row['counsel_code']?>" readonly /></td>
							<td ><textarea  name="activity_desc<?php echo $key+1?>"  readonly> <?= stripslashes($row['activity_desc']) ?> </textarea></td>
							<td><input type="text" 	name="io_ind<?php echo $key+1?>" value="<?= $row['io_ind'] ?>"  readonly /></td>
							<td><input type="text" 	name="billed_amount<?php echo $key+1?>" value="<?= $row['billed_amount'] ?>" readonly /></td>
							<td><input type="checkbox" name="copy_ind<?php echo $key+1?>"  id="copy_ind<?php echo $key+1?>" value="Y" checked/></td>
						</tr>
						<?php } ?>
					</table>
				</div>				
				
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">New Matter</label>
					<input type="text" class="form-control" name="matter_code" oninput="this.value = this.value.toUpperCase();" onfocusout="getMatterValue(this)" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc', 'clientCode', 'clientName'], ['matter_desc', 'client_code', 'client_name'], 'matter_code');"/>
					<i class="fa-solid fa-binoculars" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc', 'clientCode', 'clientName'], ['matter_desc', 'client_code', 'client_name'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Date Upto</label>
					<input type="text" class="form-control" name="bill_date_upto" id="billDateUpto" onBlur="chkActivity(this)" value="" />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-3">
					<textarea class="form-control" rows="3" name="matter_desc" oninput="this.value = this.value.toUpperCase()" id="matterDesc" readonly ></textarea>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">New Client</label>
					<input type="text" class="form-control" name="client_code" id="clientCode" value="" readonly>
           			<input type="text" class="form-control" name="client_name" id="clientName" value="" readonly>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Other Cases</label>
					<div id="div_other_cases" style="height:75px; width:375px; border:1px solid; border-color:#000000;"></div>
				</div>	
				<input type="hidden" name="other_case_count" readonly>			
				<button type="button" class="btn btn-primary cstmBtn mt-31" >Proceed</button>
	
				<div class="d-inline-block w-100 mt-4 mb-2 bnd">
					<span class="d-block float-start w-75">Case/Expence Details:</span>
					<div class="d-block float-start w-25">
						<div class="d-block float-start me-4">
							<input type="radio" name="expslct" class="me-1" id="expslctAll"/>
							<label for="expslctAll">Select All</label>
						</div>
						<div class="d-block float-start">
							<input type="radio" name="expslct" class="me-1" id="expdeslctAll"/>
							<label for="expdeslctAll">De-Select All</label>
						</div>
					</div>
				</div>				
				<div class="d-inline-block w-100">
					<table class="table table-bordered tblhdClr">
						<tr>
							<th class="fntSml">
								<span></span>
							</th>
							<th class="fntSml">
								<span>Date</span>
							</th>
							<th class="fntSml" style="width:40%;">
								<span>Particulars</span>
							</th>
							<th class="fntSml">
								<span>Amount</span>
							</th>
							<th class="fntSml">
								<span>Copy?</span> 
							</th>
						</tr>
						<tr>
							<td>
								<span></span>
							</td>
							<td>
								<span>06-06-2023</span>
							</td>
							<td class="brkwrd">
								<span>Lorem ipsum is a dummey text. Lorem ipsum is a dummey text. Lorem ipsum is a dummey text.</span>
							</td>
							<td>
								<span>0000</span>
							</td>
							<td class="w-2 text-center" style="width:2%;">
								<input type="checkbox"/>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<button type="button" class="btn btn-primary cstmBtn mt-3" disabled>Confirm</button>
			<button type="button" class="btn btn-primary cstmBtn mt-3 ms-2">Refresh</button>
			<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</button>

</form>
<?php } ?>
</main>
<!-- End #main -->
<script>
    
	

	function selectAll_to()
	{
		var rowNo = document.f1.row_counter2.value*1;
		for(var i=1; i<=rowNo; i++)
		{
			eval("document.f1.new_copy_ind_i"+i+".checked=true");
		}
	}

	function deSelectAll_to()
	{
		var rowNo = document.f1.row_counter2.value*1;
		for(var i=1; i<=rowNo; i++)
		{
			//if(document.f1.grant_bill[0].checked == true;
			eval("document.f1.new_copy_ind_i"+i+".checked=false");
		}
	}
</script>
<?= $this->endSection() ?>