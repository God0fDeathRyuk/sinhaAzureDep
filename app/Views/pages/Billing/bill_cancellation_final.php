<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
 
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">
<?php if (!isset($record)) { ?> 

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
  <h1>Bill Cancellation (Final)</h1>
</div>

<form action="" method="post" id="billCancellationFinal" name="billCancellationFinal">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
	<div class="row">
		<div class="inpt-grp col-md-4 pe-0">
			<label class="d-block w-100 mb-2">Branch <strong class="text-danger">*</strong></label>
			<select class="form-select cstm-inpt" name="branch_code" required>
			<?php foreach($data['branches'] as $branch) { ?>
			<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
			<?php } ?>
			</select>
		</div>
		<div class="inpt-grp col-md-8 pe-0 position-relative">
			<label class="d-block w-100 mb-2">Bill Yr/No <strong class="text-danger">*</strong></label>
            <input type="text" class="form-control cstm-inpt w-50 me-2 float-start" name="bill_year" id="billYear" value='' required /> 
			<input type="text" class="form-control cstm-inpt w48 float-start" id="billNo" name="bill_no" value='' onfocusout="myFinalBillSerial(this)" oninput="this.value = this.value.toUpperCase();" required />
			<!-- <i class="fa-solid fa-eye inpt-vw" onclick="showData('serial_no', '<?= $displayId['billsrl_help_id'] ?>', 'SerialNo', ['matterCode', 'matterDesc', 'clientCode', 'clientName'], ['matter_code', 'matter_desc', 'client_code', 'client_name'], 'bill_serial_code')" data-toggle="modal" data-target="#lookup"></i> -->
            <input type="hidden" class="form-control cstm-inpt" name="ref_bill_serial_no" id="refBillSerialNo" value='' />
            <input type="hidden" class="form-control cstm-inpt" name="serial_no"  id="serialNo" value='' />
            <input type="hidden" class="form-control cstm-inpt" name="status_code" id="statusCode" value='' />
		</div>
		<div class="inpt-grp col-md-4 pe-0 mt-1">
			<label class="d-block w-100 mb-2">Matter Code</label>
			<input type="text" class="form-control cstm-inpt" id="matterCode" name="matter_code" readonly disabled  />
		</div>
		<div class="inpt-grp col-md-8 pe-0 mt-1">
			<label class="d-block w-100 mb-2">Matter Description</label>
			<textarea name="" rows="1" class="form-control cstm-inpt" id="matterDesc"  name="matter_desc" readonly disabled ></textarea>
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
            <button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="formOption('/billing/cancellation-final/', 'proceed', 'billCancellationFinal')">Proceed</button>
			<button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
</form>

<?php } else { ?>
<form action="" method="post" id="billCancellationFinal2" name="billCancellationFinal2">
	<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Serial <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="serial_no" value="<?= $record['serial_no'] ?>" readonly required />
				</div>
				<div class="col-md-5 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill No</label>
					<input type="text" class="form-control w-48 float-start" name="bill_year"   value="<?= $params['bill_year'] ?>" readonly />
					<input class="form-control float-start w-54 ms-2" placeholder="" type="text"   name="bill_no" id="" value="<?= $params['bill_no'] ?>" readonly/>
					<input class="form-control float-start w-54 ms-2" placeholder="" type="hidden" name="ref_bill_serial_no" id="" value="<?= $record['ref_bill_serial_no'] ?>" readonly/>
				</div>
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch <strong class="text-danger">*</strong></label>
					<select class="form-select" name="branch_code" disabled required >
						<?php foreach($data['branches'] as $branch) { ?>
							<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
						<?php } ?>
					</select>
				</div>
				
				<div class="col-md-12 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter</label>
					<input type="text" class="form-control w-35 float-start" name="matter_code" value="<?= $record['matter_code']?>" readonly>
					<input type="text" class="form-control w-63 ms-2 float-start" name="matter_desc" value="<?= $record['matter_desc']?>" readonly>
				</div>
				<div class="col-md-12 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
					<input type="text" class="form-control w-35 float-start" 	  name="client_code" value="<?= $record['client_code'] ?>" readonly>
					<input type="text" class="form-control w-63 ms-2 float-start" name="client_name" value="<?= $record['client_name'] ?>" readonly>
				</div>
				
				<div class="col-md-6 float-start px-2 mb-3 h100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
					<textarea type="text" class="form-control w-100 float-start" rows="2" name="subject_desc" readonly><?= $record['subject_desc'] ?></textarea>
				</div>
				<div class="col-md-6 float-start px-2 mb-3 h100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Ref No</label>
					<input type="text" class="form-control" name="reference_desc" value="<?php echo $record['reference_desc'];?>" readonly/>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Option <strong class="text-danger">*</strong></label>
					<select class="form-select" name="option" required >
					<option value="" selected="selected">-------------------------- Select --------------------------</option>
					<option value="1">Cancel Fully</option>
					<option value="2">Cancel and Back To Draft Status</option>
					<option value="3">Cancel and Leave for further Billing</option>
					</select>
				</div>
				<div class="d-block w-100 mt-3">
					<a href="#" class="bdge mb-3 d-block w-100"><?= $params['status_desc'] ?></a>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Inpocket</label>
						<input type="text" class="form-control" name="bill_amount_inpocket" value="<?= $record['bill_amount_inpocket'] ?>" readonly/>
					</div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Outpocket</label>
						<input type="text" class="form-control" name="bill_amount_outpocket" value="<?= $record['bill_amount_outpocket'] ?>" readonly/>
					</div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Counsel</label>
						<input type="text" class="form-control" name="bill_amount_counsel" value="<?= $record['bill_amount_counsel'] ?>" readonly/>
					</div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Total Amount</label>
						<input type="text" class="form-control" name="total_amount" value="<?= $params['total_amount'] ?>" readonly/>
					</div>
				</div>
				<div class="mntblsec d-inline-block w-100 mt-2">
					<table class="table table-bordered">
						<tbody>
							<tr class="fs-14">
								<th><span>&nbsp;</span></th>
								<th><span>Date</span></th>
								<th><span>Counsel</span></th>
								<th><span>Particulars</span></th>
								<th><span>I/O</span></th>
								<th><span>Amount</span></th>
							</tr>
							<?php foreach($qry2 as $key => $row) { ?>
							<tr>
								<td id="Ctd<?= $key+1 ?>">
									<input type="hidden" name="inp_ok_ind<?= $key+1 ?>" value="Y" />
									<input type="hidden" name="source_code<?= $key+1 ?>" value="<?= $row['source_code'] ?>" />
									<input type="hidden" name="in_amt<?= $key+1 ?>" value="<?=$row['amount'] ?>" />
								</td>
								<td class="w-15" >
									<input class="form-control" type="text" name="activity_date<?= $key+1 ?>" value="<?= date_conv($row['activity_date']) ?>" readonly/>
								</td>
								<td>
									<input class="form-control" type="text" name="counsel_code<?= $key+1 ?>" value="<?= $row['counsel_code'] ?>" readonly/>
									<input class="form-control" type="hidden" name="counsel_name<?= $key+1 ?>" readonly/>
								</td>
								<td class="brkwrd optnNone">
									<textarea style="width:550px;" class="form-control ckeditor" name="activity_desc<?= $key+1 ?>" readonly> <?= stripslashes($row['activity_desc']) ?> </textarea>
								</td>
								<td>
									<input class="form-control" type="text" name="io_ind<?= $key+1 ?>" value="<?= $row['io_ind'] ?>" readonly/>
								</td>
								<td>
									<input class="form-control" type="text" name="billed_amount<?= $key+1 ?>" value="<?= number_format($row['billed_amount'],2,'.','') ?>" readonly/>
								</td>								
							</tr>
							<?php } ?>							
						</tbody>
					</table>
				</div>
				<input type="hidden" name="finsub" id="finsub" value="fsub">
				<button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Confirm</button>				
				<a href="<?= base_url($data['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</a>
			</div>

            <input type="hidden" name="row_no" value="<?php echo $params['qry_count'];?>">

</form>
<?php } ?>
</main>
<!-- End #main -->
<?= $this->endSection() ?>