<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>


<main id="main" class="main">
<?php if (!isset($row)) { ?> 

<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>
<?php if (session()->getFlashdata('valid_message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('valid_message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>

<div class="pagetitle col-md-12 float-start border-bottom pb-1">
  <h1>Bill Cancellation (Draft)</h1>
</div>

<form action="" method="post" id="billCancellationDraft">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
	<div class="row">
		<div class="inpt-grp col-md-4 pe-0">
			<label class="d-block w-100 mb-2">Branch</label>
			<select class="form-select cstm-inpt" name="branch_code">
			<?php foreach($data['branches'] as $branch) { ?>
			<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
			<?php } ?>
			</select>
		</div>
		<div class="inpt-grp col-md-4 pe-0 position-relative">
			<label class="d-block w-100 mb-2">Serial No <strong class="text-danger">*</strong></label>
			<input type="text" class="form-control cstm-inpt" id="SerialNo" onchange="fetchData(this, 'serial_no', ['matterCode', 'matterDesc', 'clientCode', 'clientName'], ['matter_code', 'matter_desc', 'client_code', 'client_name'], 'bill_serial_code')" name="serial_no" required />
			<i class="fa-solid fa-eye inpt-vw" onclick="showData('serial_no', '<?= $displayId['billsrl_help_id'] ?>', 'SerialNo', ['matterCode', 'matterDesc', 'clientCode', 'clientName'], ['matter_code', 'matter_desc', 'client_code', 'client_name'], 'bill_serial_code')" data-toggle="modal" data-target="#lookup"></i>
		</div>
		<div class="inpt-grp col-md-4 pe-0">
			<label class="d-block w-100 mb-2">Matter Code</label>
			<input type="text" class="form-control cstm-inpt" id="matterCode" readonly disabled  />
		</div>
		<div class="inpt-grp col-md-12 pe-0 mt-3">
			<label class="d-block w-100 mb-2">Matter Description</label>
			<textarea name="" rows="1" class="form-control cstm-inpt" id="matterDesc" readonly disabled ></textarea>
		</div>
		<div class="inpt-grp col-md-4 pe-0">
			<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
			<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="clientCode" size="05" maxlength="06" name="client_code" readonly disabled/>
		</div>
		<div class="inpt-grp col-md-8 pe-0">
			<label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
			<input type="text" class="form-control" id="clientName" oninput="this.value = this.value.toUpperCase()"  name="client_name" readonly disabled/>
		</div>
	</div>
            <button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="formOption('/billing/cancellation-draft/', 'proceed', 'billCancellationDraft')">Proceed</button>
			<button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
</form>
<?php } else { ?>
	<form action="" method="post">
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
	<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="d-inline-block w-100">
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Serial</label>
						<input type="text" class="form-control" name="serial_no" value="<?php echo $row['serial_no']; ?>" readonly/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-6 ps-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Period</label>
						<span class="float-start mt-2">From</span>
						<input class="form-control float-start w-48 ms-2"  name="start_date" id="" value="<?php echo date_conv($row['start_date']); ?>" readonly>
						<span class="float-start mt-2 ms-1">-</span>
						<input class="form-control float-start w-42 ms-1"  name="end_date" id="" value="<?php echo date_conv($row['end_date']); ?>" readonly>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
						<select class="form-select"  name="branch_code" disabled>
                            <?php foreach($data['branches'] as $branch) { ?>
                            	<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                            <?php } ?>
                        </select>
					</div>
					
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code</label>
					<input type="text" class="form-control w-35 float-start" name="matter_code" value="<?= $row['matter_code']?>"  readonly/>
					<input type="text" class="form-control w-63 ms-2 float-start" name="matter_desc" value="<?= $row['matter_desc']?>" readonly/>
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
					<input type="text" class="form-control w-35 float-start" name="client_code" value="<?php echo $row['client_code'];?>" readonly/>
					<input type="text" class="form-control w-63 ms-2 float-start" name="client_name" value="<?php echo $row['client_name'];?>" readonly/>
				</div>
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
					<textarea type="text" class="form-control" rows="5" name="subject_desc" readonly><?php  echo $row['subject_desc']; ?></textarea>
				</div>
				<div class="col-md-6 float-start px-2 mb-3">
					<div class="d-block w-100 mb-3">	
						<label class="d-inline-block w-100 mb-2 lbl-mn">Ref No</label>
						<input type="text" class="form-control" rows="3" placeholder="Ref No" name="reference_desc" value="<?php echo $row['reference_desc'];?>" readonly>
					</div>
					<div class="frms-sec-insde d-block float-start w-100 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Counsel Caption</label>
						<select class="form-select" name="option">
							<option value="" selected="selected">---------- Select Counsel Caption ----------</option>
							<option value="1">Cancel Fully</option>
							<option value="2">Cancel and Leave for further Billing</option>
						</select>
					</div>
				</div>		
				
				<div class="d-block w-100 mt-3">
					<a href="#" class="bdge mb-3 d-block w-100"><?= $params['status_desc'] ?></a>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Inpocket</label>
						<input type="text" class="form-control" name="bill_amount_inpocket" id="billAmountInpocket" value="<?php echo $row['bill_amount_inpocket']; ?>" readonly/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Outpocket</label>
						<input type="text" class="form-control" name="bill_amount_outpocket" id="billAmountOutpocket" value="<?php echo $row['bill_amount_outpocket']; ?>" readonly/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Counsel</label>
						<input type="text" class="form-control"  name="bill_amount_counsel" name="billAmountCounsel" value="<?php echo $row['bill_amount_counsel']; ?>" readonly/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Total Amount</label>
						<input type="text" class="form-control"  name="total_amount" id="totalAmount" value="<?= $params['total_amount'] ?>" readonly/>
					</div>					
				</div>
				
				
				<div class="d-inline-block w-100 mt-2 mb-2 bnd">
					<span>Inpockets/Councel etc... </span>
				</div>
				<div class="d-inline-block w-100 mt-2">
					<table class="table table-bordered tblmn">
						<thead>
							<tr>
								<th>
									<span></span>
								</th>
								<th>
									<span>Date</span>
								</th>
								<th>
									<span>Cnsl</span>
								</th>
								<th style="width:40%;">
									<span>Particulars</span>
								</th>
								<th>
									<span>I/O</span>
								</th>
								<th>
									<span>Amount</span>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($qry2 as $key => $row) { ?>
							<tr>
								<td>
									<input type="hidden" name="inp_ok_ind<?php echo $key+1?>" value="Y" readonly/>
									<input type="hidden" name="source_code<?php echo $key+1;?>" value="<?=$row['source_code'] ?>" readonly/>
									<input type="hidden" name="in_amt<?php echo $key+1?>" value="<?= $row['amount'] ?>" readonly/>
								</td>
								<td>
									<input type="text" class="form-control" name="activity_date<?php echo $key+1?>" value="<?= date_conv($row['activity_date']) ?>"  readonly/>
								</td>
								<td>
									<input type="text" class="form-control" name="counsel_code<?php echo $key+1?>" value="<?= $row['counsel_code'] ?>" readonly/>
									<input type="hidden" name="counsel_name<?php echo $key+1;?>" readonly/>
								</td>
								<td class="brkwrd optnNone">
									<textarea type="text" class="form-control ckeditor" name="activity_desc<?php echo $key+1?>" readonly><?= stripslashes($row['activity_desc']) ?></textarea>
								</td>
								<td>
									<input type="text" class="form-control" name="io_ind<?php echo $key+1?>" value="<?= $row['io_ind'] ?>" readonly/>
								</td>
								<td>
									<input type="text" class="form-control" name="billed_amount<?php echo $key+1?>" value="<?= number_format($row['billed_amount'],2,'.','') ?>" readonly/>
								</td>
							</tr>						
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<input type="hidden" name="finsub" id="finsub" value="fsub">
			<button type="submit" class="btn btn-primary cstmBtn mt-3">Confirm</button>
			<a href="<?= base_url($data['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>

			<input type="hidden" name="row_no" value="<?php echo $params['qry_count'];?>">
</form>
<?php } ?>
</main>
<!-- <script>
	function total()
	{
	var total = 0;
	var inpocket   = document.getElementById("billAmountInpocket").value;
	var outpocket  = document.getElementById("billAmountOutpocket").value; 
	var counsel    = document.getElementById("billAmountCounsel").value; 

	total = inpocket + outpocket + counsel;
	total =  parseFloat(total).toFixed(2);
	document.getElementById("totalAmount").value = total;
	}
total();
</script> -->
<!-- End #main -->
<?= $this->endSection() ?>