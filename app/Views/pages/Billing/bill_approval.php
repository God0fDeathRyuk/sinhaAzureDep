<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?> 

<main id="main" class="main">
<?php if (!isset($reports)) { ?> 

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
<?php if (session()->getFlashdata('valid_message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('valid_message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>

<div class="pagetitle col-md-12 float-start border-bottom pb-1">
  <h1>Bill Approval</h1>
</div>

<form action="" method="post" id="billApproval">
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
			<input type="text" class="form-control cstm-inpt" id="matterCode" name="matter_code" readonly   />
		</div>
		<div class="inpt-grp col-md-12 pe-0 mt-3">
			<label class="d-block w-100 mb-2">Matter Description</label>
			<textarea rows="1" class="form-control cstm-inpt" id="matterDesc" name="matter_desc" readonly ></textarea>
		</div>
		<div class="inpt-grp col-md-4 pe-0">
			<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
			<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="clientCode" name="client_code" size="05" maxlength="06" name="client_code" readonly />
		</div>
		<div class="inpt-grp col-md-8 pe-0">
			<label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
			<input type="text" class="form-control" id="clientName" oninput="this.value = this.value.toUpperCase()" name="client_name" readonly />
		</div>
	
		<div class="w-100 float-start text-start mt-4 top-btn-fld">
			<button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('/billing/approval/', 'approve', 'billApproval')"> Approve </button>
			<button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('/billing/approval/', 'view', 'billApproval')">View</button>
			<button type="reset" class="btn btn-primary cstmBtn mt-0" onclick="resetFields()">Reset</button>
			<button type="submit" class="btn btn-primary cstmBtn mt-0 d-none">Exit</button>
		</div>
	</div>
</form>

<?php } else { ?>
	<form action="" method="post">
	<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
	<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="d-inline-block w-100">
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Serial</label>
						<input type="text" class="form-control" name="serial_no" value="<?php echo $reports['serial_no']; ?>" readonly/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-6 ps-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Period</label>
						<span class="float-start mt-2">From</span>
						<input class="form-control float-start w-48 ms-2" id="" type="text" name="start_date" value="<?php echo date_conv($reports['start_date']); ?>" readonly>
						<span class="float-start mt-2 ms-2">-</span>
						<input class="form-control float-start w-42 ms-2" id="" type="text" name="end_date" value="<?php echo date_conv($reports['end_date']); ?>" readonly>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
						<select class="form-select"  name="branch_code_copy" <?= $permission ?>>
                            <?php foreach($data['branches'] as $branch) { ?>
                            	<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                            <?php } ?>
                        </select>
					</div>
					
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code</label>
					<input type="text" class="form-control w-35 float-start" name="matter_code" value="<?php echo $reports['matter_code'];?>" readonly/>
					<input type="text" class="form-control w-63 ms-2 float-start" name="matter_desc" value="<?php echo $params['matter_desc'];?>" readonly/>
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
					<input type="text" class="form-control w-35 float-start" name="client_code" value="<?php echo $reports['client_code'];?>" readonly/>
					<input type="text" class="form-control w-63 ms-2 float-start" name="client_name" value="<?php echo $params['client_name'];?>" readonly/>
				</div>
				<div class="col-md-12 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Other Case(s)</label>
					<textarea type="text" class="form-control" rows="2"  name="other_case_desc" readonly><?php echo $reports['other_case_desc']; ?></textarea>
				</div>
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
					<textarea type="text" class="form-control" rows="3" name="subject_desc" <?= $permission ?>><?php echo $reports['subject_desc'];?></textarea>
				</div>
				<div class="col-md-6 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Ref No</label>
					<textarea type="text" class="form-control" rows="3" name="reference_desc" <?= $permission ?>><?php echo $reports['reference_desc'];?></textarea>
				</div>		
				
				<a href="#" class="bdge mb-2"><?= $params['status_desc'] ?></a>
				<div class="mntblsec">
					<table class="table table-bordered overflow-auto tblhdClr">
						<tr>
							<th><span>&nbsp;</span></th>
							<th><span>With Tax</span></th>
							<th><span>W/O Tax</span></th>
							<th><span>Total</span></th>
							<th><span>Tax</span></th>
						</tr>
						<tr>
							<td><span>Inpocket</span></td>
							<td><input class="form-control" type="text" name="bill_amount_inpocket_stax" value="<?= $params['bill_amount_inpocket_stax']; ?>" readonly/></td>
							<td><input class="form-control" type="text" name="bill_amount_inpocket_ntax" value="<?= $params['bill_amount_inpocket_ntax']; ?>" readonly /></td>
							<td><input class="form-control" type="text" name="bill_amount_inpocket"      value="<?= $params['bill_amount_inpocket'];      ?>" readonly /></td>
							<td><input class="form-control" type="text" name="service_tax_inpocket"      value="<?= $params['service_tax_inpocket'];      ?>" readonly /></td>
						</tr>
						<tr>
							<td><span>Outpocket</span></td>
							<td><input class="form-control" type="text" name="bill_amount_outpocket_stax" value="<?= $params['bill_amount_outpocket_stax']; ?>" readonly/></td>
							<td><input class="form-control" type="text" name="bill_amount_outpocket_ntax" value="<?= $params['bill_amount_outpocket_ntax']; ?>" readonly/></td>
							<td><input class="form-control" type="text" name="bill_amount_outpocket"      value="<?= $params['bill_amount_outpocket'];      ?>" readonly/></td>
							<td><input class="form-control" type="text" name="service_tax_outpocket"      value="<?= $params['service_tax_outpocket'];      ?>" readonly/></td>
						</tr>
						<tr>
							<td><span>Councel</span></td>
							<td><input class="form-control" type="text" name="bill_amount_counsel_stax" value="<?= $params['bill_amount_counsel_stax']; ?>" readonly/></td>
							<td><input class="form-control" type="text" name="bill_amount_counsel_ntax" value="<?= $params['bill_amount_counsel_ntax']; ?>" readonly/></td>
							<td><input class="form-control" type="text" name="bill_amount_counsel"      value="<?= $params['bill_amount_counsel'];      ?>" readonly/></td>
							<td><input class="form-control" type="text" name="service_tax_counsel"      value="<?= $params['service_tax_counsel'];      ?>" readonly/></td>
						</tr>
						<tr>
							<td><span>Total</span></td>
							<td><input class="form-control" type="text" name="total_bill_amount_stax" value="<?= $params['total_bill_amount_stax']; ?>" readonly/></td>
							<td><input class="form-control" type="text" name="total_bill_amount_ntax" value="<?= $params['total_bill_amount_ntax']; ?>" readonly/></td>
							<td><input class="form-control" type="text" name="total_amount"           value="<?= $params['total_amount'];           ?>" readonly/></td>
							<td><input class="form-control" type="text" name="total_service_tax"      value="<?= $params['total_service_tax'];      ?>" readonly/></td>
						</tr>
					</table>
				</div>
				<div class="col-md-2 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Court Bill</label>
					<select class="form-select" name="court_fee_bill_ind" <?= $permission ?>>
						<option value="N" <?php if($reports['court_fee_bill_ind'] != 'Y') { echo 'selected' ; } ?>>No</option>
			  			<option value="Y" <?php if($reports['court_fee_bill_ind'] == 'Y') { echo 'selected' ; } ?>>Yes</option>
					</select>
				</div>
				
				<div class="frms-sec-insde d-block float-end col-md-3 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Net Amount</label>
					<input type="text" class="form-control" placeholder="" name="net_bill_amount" value="<?php echo $params['net_bill_amount'];?>" readonly/>
				</div>
				<div class="d-inline-block w-100 mt-2 mb-2 bnd">
					<span>Inpockets/Councel etc... </span>
				</div>
				<div class="d-inline-block w-100 mt-2 tblMn">
					<table class="table table-bordered tblhdClr">
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
							<th>
								<span>Tax</span>
							</th>
						</tr>
						<?php foreach($qry2 as $key => $row) { ?>
						<tr>
							<td>
								<input type="hidden" class="form-control" name="inp_ok_ind<?= $key+1 ?>" value="Y" readonly/>
								<input type="hidden" class="form-control" name="source_code<?= $key+1 ;?>" value="<?php echo $row['source_code']; ?>" readonly/>
								<input type="hidden" class="form-control" name="in_amt<?= $key+1 ;?>" value="<?php echo $row['amount']; ?>" readonly/>
							</td>
							<td>
								<input type="text" class="form-control" name="activity_date<?= $key+1 ?>" value="<?php echo date_conv($row['activity_date']);?>" readonly />
							</td>
							<td>
								<input type="text" class="form-control" name="counsel_code<?= $key+1 ?>" value="<?php echo $row['counsel_code'];?>" readonly/>
								<input type="hidden" class="form-control" name="counsel_name<?= $key+1 ?>" readonly/>

							</td>
							<td class="brkwrd optnNone">
								<textarea class="form-control ckeditor" name="activity_desc<?= $key+1 ?>" readonly><?php echo stripslashes($row['activity_desc']);?></textarea>
							</td>
							<td>
								<input class="form-control" type="text" name="io_ind<?= $key+1 ?>" value="<?php echo $row['io_ind'];?>" readonly/>
							</td>
							<td>
								<input class="form-control" type="text" name="billed_amount<?= $key+1 ;?>" value="<?php echo number_format($row['billed_amount'],2,'.','');?>" readonly/>
							</td>
							<td>
								<input class="form-control" type="text" name="servtax_amount<?= $key+1 ;?>" value="<?php echo number_format($row['service_tax_amount'],2,'.','');?>" readonly/>
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
			<?php if($option != 'view') { ?>
			<input type="hidden" name="finsub" id="finsub" value="fsub">
			<button type="submit" class="btn btn-primary cstmBtn mt-3">Confirm</button>
			<?php } ?>
			<a href="<?= base_url($data['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>

			<input type="hidden" name="row_no"	   value="<?= $params['qry_count'];?>">
			<input type="hidden" name="serial_no"  value="<?= $reports['serial_no'];?>">
			<input type="hidden" name="initial_code" value="<?= $params['initial_code'];?>">
			<input type="hidden" name="billing_addr_code" value="<?= $params['billing_addr_code'];?>">
            <input type="hidden" name="billing_attn_code" value="<?= $params['billing_attn_code'];?>">
            <input type="hidden" name="status_code" value="<?= $params['status_desc'];?>">

</form>
<?php } ?>
</main>
<!-- End #main -->
<?= $this->endSection() ?>