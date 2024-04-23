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

			<?php if (session()->getFlashdata('message_not_editable') !== NULL) : ?>
			<div id="alertMsg">
				<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
					<div> <b> <?= session()->getFlashdata('message_not_editable') ?> </b> </div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php endif; ?>

			<div class="pagetitle col-md-12 float-start border-bottom pb-1">
				<h1>Bill Editing</h1>
			</div>

			<form action="" method="post" id="billEditing">
				<div class="row">
					<div class="inpt-grp col-md-4 pe-0">
						<label class="d-block w-100 mb-2">Branch</label>
						<select class="form-select cstm-inpt" name="branch_code">
							<?php foreach ($data['branches'] as $branch) { ?>
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
						<input type="text" class="form-control cstm-inpt" id="matterCode" name="matter_code" readonly />
					</div>
					<div class="inpt-grp col-md-12 pe-0 mt-3">
						<label class="d-block w-100 mb-2">Matter Description</label>
						<textarea rows="1" class="form-control cstm-inpt" id="matterDesc" name="matter_desc" readonly></textarea>
					</div>
					<div class="inpt-grp col-md-4 pe-0 mt-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
						<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="clientCode" size="05" maxlength="06" name="client_code" readonly />
					</div>
					<div class="inpt-grp col-md-8 pe-0 mt-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
						<input type="text" class="form-control" id="clientName" oninput="this.value = this.value.toUpperCase()" name="client_name" readonly />
					</div>
					<div class="w-100 float-start text-start mt-4 top-btn-fld">
						<button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('/billing/editing/', 'edit', 'billEditing')">Edit</button>
						<button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('/billing/editing/', 'view', 'billEditing')">View</button>
						<button type="reset" class="btn btn-primary cstmBtn mt-0" onclick="resetFields()">Reset</button>
						<button type="submit" class="btn btn-primary cstmBtn mt-0 d-none">Exit</button>
					</div>
				</div>

			</form>

		<?php } else { ?> 
			<form action="" name="billEditing" method="post">
				<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
				<div class="frms-sec d-inline-block w-100 bg-white p-3">
					<div class="d-inline-block w-100">
						<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Serial</label>
							<div class="position-relative">
							    <input type="text" class="form-control" name="serial_no" id="billSerialNo" value="<?php echo $reports['serial_no']; ?>" onchange="showHistoryBtn(this, 'draftBillHistory')" readonly />
							    <i class="fa-solid fa-clock inpt-vw histryIcn clckIcn" id="draftBillHistory" title="View Draft Bill" onclick="showHistory('billing/history/draft-bill', 'serial_no=@billSerialNo&row_count=1')" data-toggle="modal" data-target="#lookup"></i>
						    </div>
						</div>
						<div class="frms-sec-insde d-block float-start col-md-6 ps-2 mb-4">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Period</label>
							<span class="float-start mt-2">From</span>
							<input class="form-control float-start w-48 ms-2 set-date datepicker" id="start_date" type="text" name="start_date" value="<?php echo date_conv($reports['start_date']); ?>" readonly>
							<span class="float-start mt-2 ms-2">-</span>
							<input class="form-control float-start w-42 ms-2 set-date datepicker" id="end_date" type="text" name="end_date" value="<?php echo date_conv($reports['end_date']); ?>" readonly>
						</div>
						<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
							<select class="form-select" name="branch_code" <?= $permission ?>>
								<?php foreach ($data['branches'] as $branch) { ?>
									<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
								<?php } ?>
							</select>
						</div>

					</div>
					<div class="col-md-6 float-start px-2 position-relative mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code</label>
						<input type="text" class="form-control w-35 float-start" name="matter_code" value="<?php echo $reports['matter_code']; ?>" readonly />
						<input type="text" class="form-control w-63 ms-2 float-start" name="matter_desc" value="<?php echo $params['matter_desc']; ?>" readonly />
					</div>
					<div class="col-md-6 float-start px-2 position-relative mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
						<input type="text" class="form-control w-35 float-start" name="client_code" value="<?php echo $reports['client_code']; ?>" readonly />
						<input type="text" class="form-control w-63 ms-2 float-start" name="client_name" value="<?php echo $params['client_name']; ?>" readonly />
					</div>
					<div class="col-md-12 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Other Case(s)</label>
						<textarea type="text" class="form-control" rows="2" name="other_case_desc" readonly><?= $reports['other_case_desc'] ?></textarea>
					</div>
					<div class="col-md-6 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
						<textarea type="text" class="form-control" rows="3" name="subject_desc" <?= $permission ?>><?= strtoupper($reports['subject_desc']) ?></textarea>
					</div>
					<div class="col-md-6 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Ref No</label>
						<textarea type="text" class="form-control" rows="3" name="reference_desc" <?= $permission ?>><?= $reports['reference_desc'] ?></textarea>
					</div>

					<div class="col-md-2 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Direct Counsel?</label>
						<select class="form-select" name="direct_counsel_ind" <?= $permission ?>>
							<option value="N" <?php if ($reports['direct_counsel_ind'] != 'Y') {
													echo 'selected';
												} ?>>No</option>
							<option value="Y" <?php if ($reports['direct_counsel_ind'] == 'Y') {
													echo 'selected';
												} ?>>Yes</option>
						</select>
					</div>
					<a href="#" class="bdge"><?= $params['status_desc'] ?></a>
					<div class="mntblsec tblMn overflow-auto">
						<table class="table table-bordered tblhdClr w-100">
							<tr>
								<th><span>&nbsp;</span></th>
								<th><span>With Tax</span></th>
								<th><span>W/O Tax</span></th>
								<th><span>Total</span></th>
								<th><span>Tax</span></th>
							</tr>
							<tr>
								<td><span>Inpocket</span></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="bill_amount_inpocket_stax" id="bill_amount_inpocket_stax" value="<?= $params['bill_amount_inpocket_stax'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="bill_amount_inpocket_ntax" id="bill_amount_inpocket_ntax" value="<?= $params['bill_amount_inpocket_ntax'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="bill_amount_inpocket" id="bill_amount_inpocket" value="<?= $params['bill_amount_inpocket'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="service_tax_inpocket" id="service_tax_inpocket" value="<?= $params['service_tax_inpocket'] ?>" readonly /></td>
							</tr>
							<tr>
								<td><span>Outpocket</span></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="bill_amount_outpocket_stax" id="bill_amount_outpocket_stax" value="<?= $params['bill_amount_outpocket_stax'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="bill_amount_outpocket_ntax" id="bill_amount_outpocket_ntax" value="<?= $params['bill_amount_outpocket_ntax'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="bill_amount_outpocket" id="bill_amount_outpocket" value="<?= $params['bill_amount_outpocket'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="service_tax_outpocket" id="service_tax_outpocket" value="<?= $params['service_tax_outpocket'] ?>" readonly /></td>
							</tr>
							<tr>
								<td><span>Councel</span></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="bill_amount_counsel_stax" id="bill_amount_counsel_stax" value="<?= $params['bill_amount_counsel_stax'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="bill_amount_counsel_ntax" id="bill_amount_counsel_ntax" value="<?= $params['bill_amount_counsel_ntax'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="bill_amount_counsel" id="bill_amount_counsel" value="<?= $params['bill_amount_counsel'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="service_tax_counsel" id="service_tax_counsel" value="<?= $params['service_tax_counsel'] ?>" readonly /></td>
							</tr>
							<tr>
								<td><span>Total</span></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="total_bill_amount_stax" id="total_bill_amount_stax" value="<?= $params['total_bill_amount_stax'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="total_bill_amount_ntax" id="total_bill_amount_ntax" value="<?= $params['total_bill_amount_ntax'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="total_amount" id="total_amount" value="<?= $params['total_amount'] ?>" readonly /></td>
								<td style="width:20%;"><input class="w-100 form-control" type="text" name="total_service_tax" id="total_service_tax" value="<?= $params['total_service_tax'] ?>" readonly /></td>
							</tr>
						</table>
					</div>
					<div class="col-md-2 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Court Bill</label>
						<select class="form-select" name="court_fee_bill_ind" <?= $permission ?>>
							<option value="N" <?php if ($reports['court_fee_bill_ind'] != 'Y') {
													echo 'selected';
												} ?>>No</option>
							<option value="Y" <?php if ($reports['court_fee_bill_ind'] == 'Y') {
													echo 'selected';
												} ?>>Yes</option>
						</select>
					</div>
					<div class="col-md-2 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">No Free Bill</label>
						<select class="form-select" name="no_fee_bill_ind" <?= $permission ?>>
							<option value="N" <?php if ($reports['no_fee_bill_ind'] != 'Y') {
													echo 'selected';
												} ?>>No</option>
							<option value="Y" <?php if ($reports['no_fee_bill_ind'] == 'Y') {
													echo 'selected';
												} ?>>Yes</option>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-end col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Net Amount</label>
						<input type="text" class="form-control" name="net_bill_amount" id="net_bill_amount" value="<?= $params['net_bill_amount'] ?>" readonly />
					</div>
					<div class="d-inline-block w-100 mt-2 mb-2 bnd">
						<span class="d-block float-start pt-2">Inpockets/Councel etc... Please enter Councel Code for Councel fees instead of '0000'... </span>
						<span id="actionBtn1">
							<?php if (ucfirst($option) == 'Edit') { 
								if(count($qry2)) { ?>
								<button type="button" onclick="deleteRow('qry2Tbody', 'row_no1', 'actionBtn1', 'addRowInQry2')" class="btn btn-primary cstmBtn border border-white float-end">Delete Row</button> 
							<?php } else { ?>
								<button type="button" onclick="addRowInQry2(this, true, ['qry2Tbody', 'row_no1', 'actionBtn1', 'addRowInQry2(this, true)'])" class="btn btn-primary cstmBtn border border-white float-end">Add Row</button> 
							<?php } } ?>
						</span>
					</div>
					<div class="d-inline-block w-100 mt-2 scrlTblMd">
						<table class="table table-bordered tblhdClr">
							<tr>
								<th> <span></span> </th>
								<th> <span>Date</span> </th>
								<th> <span>CnslCod</span> </th>
								<th style="width:40%;"> <span>Particulars</span> </th>
								<th> <span>I/C</span> </th>
								<th> <span>Amount</span> </th>
								<th> <span>Tax?</span> </th>
								<th> <span>Prn?</span> </th>
								<th> <span>Seq</span> </th>
								<th> <span>Action</span> </th>
							</tr>
							<tbody id="qry2Tbody">
								<?php foreach ($qry2 as $key => $row) { ?>
									<tr>
										<td id="Ctd<?php echo $key + 1; ?>" onClick="<?php if ($option == 'edit') { ?>inpocket_delRow(this, <?php echo $key + 1; ?>)<?php } ?>">
											<input type="hidden" class="form-control" name="inp_ok_ind<?php echo $key + 1 ?>" id="inp_ok_ind<?php echo $key + 1 ?>" value="Y" onClick="<?php if ($option == 'edit') { ?>inpocket_delRow(<?php echo $key + 1; ?>)<?php } ?>" <?= $permission ?> />
											<input type="hidden" name="source_code<?php echo $key + 1; ?>" id="source_code<?php echo $key + 1; ?>" value="<?= $row['source_code'] ?>">
											<input type="hidden" name="in_amt<?php echo $key + 1; ?>" id="in_amt<?php echo $key + 1; ?>" value="<?= $row['amount'] ?>">
										</td>
										<td>
											<input style="width:120px;" class="form-control" type="text" name="activity_date<?php echo $key + 1 ?>" id="activity_date<?php echo $key + 1 ?>" value="<?= date_conv($row['activity_date']) ?>" onblur="<?php if ($option == 'edit') { ?>chkActivity(this)<?php } ?>" <?= $permission ?> />
										</td>
										<td>
											<input style="width:120px;" class="form-control" type="text" name="counsel_code<?php echo $key + 1 ?>" id="counsel_code<?php echo $key + 1 ?>" value="<?= $row['counsel_code'] ?>" <?= $permission ?> />
											<input type="hidden" name="counsel_name<?php echo $key + 1; ?>" id="counsel_name<?php echo $key + 1; ?>">
										</td>
										<td class="brkwrd optnNone">
											<textarea style="width:550px;" class="form-control ckeditor" name="activity_desc<?php echo $key + 1 ?>" id="activity_desc<?php echo $key + 1 ?>" <?= $permission ?>> <?= stripslashes($row['activity_desc']) ?> </textarea>
										</td>
										<td>
											<input style="width:75px;" class="form-control" type="text" name="io_ind<?php echo $key + 1 ?>" id="io_ind<?php echo $key + 1 ?>" value="<?= ($row['io_ind'] == 'O') ? $row['io_ind'] = 'C' : $row['io_ind'] ?>" onkeyup="<?php if ($option == 'edit') { ?>changeCase(this,<?php echo $key + 1; ?>);calc_service_tax(<?php echo $key + 1; ?>,'<?php echo $params['tax_per'] ?>','Grid1');<?php } ?>" <?= $permission ?> />
										</td>
										<td>
											<input style="width:75px;" class="form-control" type="text" name="billed_amount<?php echo $key + 1; ?>" id="billed_amount<?php echo $key + 1; ?>" onkeypress="return validnumbercheck(event);" onblur="<?php if ($option == 'edit') { ?>amtValidate(this,<?php echo $key + 1; ?>,'Grid1'); calc_service_tax('<?php echo $key + 1 ?>','<?php echo $params['tax_per'] ?>','Grid1')<?php } ?>" value="<?= number_format($row['billed_amount'], 2, '.', '') ?>" <?= $permission ?> />
										</td>
										<td>
											<input type="checkbox" class="" name="service_tax_ind<?php echo $key + 1; ?>" id="service_tax_ind<?php echo $key + 1; ?>" value="Y" <?php if ($row['service_tax_ind'] == 'Y') { echo 'checked'; } ?> onClick="calc_service_tax('<?php echo $key + 1 ?>','<?php echo $params['tax_per'] ?>','Grid1')" <?= $permission ?> />
											<input type="hidden" name="service_tax_percent<?php echo $key + 1; ?>" id="service_tax_percent<?php echo $key + 1; ?>" value="<?= $row['service_tax_percent'] ?>" />
											<input type="hidden" name="service_tax_amount<?php echo $key + 1; ?>" id="service_tax_amount<?php echo $key + 1; ?>" value="<?= $row['service_tax_amount'] ?>" />
										</td>
										<td>
											<input type="checkbox" class="" name="printer_ind<?php echo $key + 1; ?>" id="printer_ind<?php echo $key + 1; ?>" value="Y" <?php if ($row['printer_ind'] == 'Y') { echo 'checked'; } ?> onClick="myCheckPrintInd('<?php echo $key + 1 ?>','Grid1')" <?= $permission ?> />
										</td>
										<td>
											<input style="width:75px;" class="form-control" type="text" name="prn_seq_no<?php echo $key + 1; ?>" id="prn_seq_no<?php echo $key + 1; ?>" value="<?= $row['prn_seq_no'] ?>" <?= $permission ?> />
										</td>
										<td class="TbladdBtn">
											<?php if (ucfirst($option) == 'Edit' && count($qry2) == $key + 1) { ?> <input type="button" value="+" onclick="addRowInQry2(this)"> <?php } ?>
										</td>
									</tr>
								<?php } if(!count($qry2)) { ?> <tr> <td colspan="10" class="text-center">No Records Found !!</td> </tr> <?php } ?>
							</tbody>
						</table>
					</div>
					
					<div class="d-inline-block w-100 mt-4 mb-2 bnd">
						<span>Other Expence(s)...</span>
						<span id="actionBtn2">
							<?php if (ucfirst($option) == 'Edit') { 
								if(count($qry3)) { ?>
								<button type="button" onclick="deleteRow('qry3Tbody', 'row_no2', 'actionBtn2', 'addRowInQry3')" class="btn btn-primary cstmBtn border border-white float-end">Delete Row</button> 
							<?php } else { ?>
								<button type="button" onclick="addRowInQry3(this, true, ['qry3Tbody', 'row_no2', 'actionBtn2', 'addRowInQry3(this, true)'])" class="btn btn-primary cstmBtn border border-white float-end">Add Row</button> 
							<?php } } ?>
						</span>
					</div>
					<div class="d-inline-block w-100 mt-2 scrlTblMd">
						<table class="table table-bordered tblhdClr">
							<tr>
								<th> <span></span> </th>
								<th> <span>Date</span> </th>
								<th style="width:40%;"> <span>Particulars</span> </th>
								<th> <span>Amount</span> </th>
								<th> <span>Tax?</span> </th>
								<th> <span>Prn?</span> </th>
								<th> <span>Seq</span> </th>
								<th> <span>Add</span> </th>
							</tr>
							<tbody id="qry3Tbody">
								<?php foreach ($qry3 as $key_1 => $row_1) { ?>
									<tr>
										<td style="width:20px;" id="Ctd2<?php echo $key_1 + 1; ?>" onClick="<?php if ($option == 'edit') { ?>outpocket_delRow(this, <?php echo $key_1 + 1; ?>)<?php } ?>">
											<input type="hidden" name="out_ok_ind<?php echo $key_1 + 1; ?>" id="out_ok_ind<?php echo $key_1 + 1; ?>" value="Y" readonly onClick="<?php if ($option == 'edit') { ?>outpocket_delRow(this, <?php echo $key_1 + 1; ?>)<?php } ?>">
											<input type="hidden" name="out_source_code<?php echo $key_1 + 1; ?>" id="out_source_code<?php echo $key_1 + 1; ?>" value="<?= $row_1['source_code'] ?>">
											<input type="hidden" name="out_amt<?php echo $key_1 + 1; ?>" value="<?php echo $row_1['amount']; ?>">
										</td>
										<td style="width:115px;">
											<input class="w-100 form-control" name="date<?php echo $key_1 + 1 ?>" id="date<?php echo $key_1 + 1 ?>" value="<?= date_conv($row_1['activity_date']) ?>" onBlur="<?php if ($option == 'edit') { ?>chkActivity2(this,<?php echo $key_1 + 1 ?>)<?php } ?>" <?= $permission ?> />
										</td>
										<td class="brkwrd" style="width:120px;">
											<input class="form-control w-100" name="details<?php echo $key_1 + 1 ?>" id="details<?php echo $key_1 + 1 ?>" value="<?= stripslashes($row_1['activity_desc']) ?>" <?= $permission ?> />
										</td>
										<td style="width:85px;">
											<input class="w-100 text-left form-control" name="amount<?php echo $key_1 + 1; ?>" id="amount<?php echo $key_1 + 1; ?>" value="<?= number_format($row_1['billed_amount'], 2, '.', '') ?>" onBlur="<?php if ($option == 'edit') { ?>amtValidate(this,<?php echo $key_1 + 1; ?>,'Grid2'); calc_service_tax('<?php echo $key_1 + 1; ?>','<?php echo $params['tax_per'] ?>','Grid2')<?php } ?>" <?= $permission ?> />
										</td>
										<td style="width:75px;">
											<input class="w-100 text-left" type="checkbox" class="" name="tax_ind<?php echo $key_1 + 1; ?>" id="tax_ind<?php echo $key_1 + 1; ?>" value="Y" onClick="calc_service_tax('<?php echo $key_1 + 1; ?>','<?php echo $params['tax_per'] ?>','Grid2')" <?php if ($row_1['service_tax_ind'] == 'Y') { echo 'checked'; } ?> <?= $permission ?> />
											<input class="w-100 text-left" type="hidden" name="tax_percent<?php echo $key_1 + 1; ?>" id="tax_percent<?php echo $key_1 + 1; ?>" value="<?= $row_1['service_tax_percent'] ?>">
											<input class="w-100 text-left" type="hidden" name="tax_amount<?php echo $key_1 + 1; ?>" id="tax_amount<?php echo $key_1 + 1; ?>" value="<?= $row_1['service_tax_amount'] ?>">
										</td>
										<td style="width:75px;">
											<input class="w-100" type="checkbox" name="printer<?php echo $key_1 + 1 ?>" id="printer<?php echo $key_1 + 1 ?>" value="Y" onClick="myCheckPrintInd('<?php echo $key_1 + 1; ?>','Grid2')" <?php if ($row_1['printer_ind'] == 'Y') { echo 'checked'; } ?> <?= $permission ?> />
										</td>
										<td style="width:75px;">
											<input class="w-100 form-control" name="prn_seq<?php echo $key_1 + 1 ?>" id="prn_seq<?php echo $key_1 + 1 ?>" value="<?= $row_1['prn_seq_no'] ?>" <?= $permission ?> />
										</td>
										<td>
											<?php if (ucfirst($option) == 'Edit' && count($qry3) == $key_1 + 1) { ?> <input type="button" value="+" onclick="addRowInQry3(this)"> <?php } ?>
										</td>
									</tr>
								<?php } if(!count($qry3)) { ?> <tr> <td colspan="8" class="text-center">No Records Found !!</td> </tr> <?php } ?>
								
							</tbody>
						</table>
					</div>
				</div>
				<input type="hidden" name="finsub" id="finsub" value="fsub">
				<?php if ($option != 'view') { ?>
					<button type="submit" class="btn btn-primary cstmBtn mt-3">Confirm</button>
				<?php } ?>
				<a href="<?= base_url($params['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>

				<input type="hidden" name="row_no1" id="row_no1" value="<?php echo $params['qry_count2']; ?>">
				<input type="hidden" name="row_no2" id="row_no2" value="<?php echo $params['qry_count3']; ?>">
				<input type="hidden" name="tax_per" id="tax_per" size="<?php echo $params['tax_per']; ?>">
				<input type="hidden" name="serial_no" id="serial_no" value="<?php echo $reports['serial_no']; ?>">
			</form>

			<script>
				function addRowInQry2(e, flag = false, params) {
					let tbody = document.getElementById("qry2Tbody");
					let rowsCount = tbody.rows.length, key = rowsCount + 1;
					let tr = tbody.insertRow(rowsCount);
					let td = `
						<tr>
							<td id="Ctd${key}" onClick="<?php if ($option == 'edit') { ?>inpocket_delRow(this, ${key})<?php } ?>">
								<input type="hidden" class="form-control" name="inp_ok_ind${key}" id="inp_ok_ind${key}" value="Y" onClick="<?php if ($option == 'edit') { ?>inpocket_delRow(${key})<?php } ?>" />
								<input type="hidden" name="source_code${key}" id="source_code${key}" value="">
								<input type="hidden" name="in_amt${key}" id="in_amt${key}" value="">
							</td>
							<td>
								<input style="width:120px;" class="form-control" type="text" name="activity_date${key}" id="activity_date${key}" value="" onblur="<?php if ($option == 'edit') { ?>chkActivity(this,${key})<?php } ?>" />
							</td>
							<td>
								<input style="width:120px;" class="form-control" type="text" name="counsel_code${key}" id="counsel_code${key}" value="" />
								<input type="hidden" name="counsel_name${key}" id="counsel_name${key}">
							</td>
							<td class="brkwrd">
								<textarea style="width:550px;" class="form-control" name="activity_desc${key}" id="activity_desc${key}"> </textarea>
							</td>
							<td>
								<input style="width:75px;" class="form-control" type="text" name="io_ind${key}" id="io_ind${key}" value="" onkeyup="<?php if ($option == 'edit') { ?>changeCase(this, ${key});calc_service_tax(${key},'<?= $params['tax_per'] ?>','Grid1');<?php } ?>" />
							</td>
							<td>
								<input style="width:75px;" class="form-control" type="text" name="billed_amount${key}" id="billed_amount${key}" onkeypress="return validnumbercheck(event);" onblur="<?php if ($option == 'edit') { ?>amtValidate(this,${key},'Grid1'); calc_service_tax('${key}', '<?php echo $params['tax_per'] ?>', 'Grid1')<?php } ?>" value="0.00" />
							</td>
							<td>
								<input type="checkbox" class="" name="service_tax_ind${key}" id="service_tax_ind${key}" value="Y" onClick="calc_service_tax('${key}', '<?= $params['tax_per'] ?>', 'Grid1')" />
								<input type="hidden" name="service_tax_percent${key}" id="service_tax_percent${key}" value="" />
								<input type="hidden" name="service_tax_amount${key}" id="service_tax_amount${key}" value="" />
							</td>
							<td>
								<input type="checkbox" class="" name="printer_ind${key}" id="printer_ind${key}" value="Y" checked onClick="myCheckPrintInd('${key}','Grid1')" />
							</td>
							<td>
								<input style="width:75px;" class="form-control" type="text" name="prn_seq_no${key}" id="prn_seq_no${key}" value="" />
							</td>
							<td class="TbladdBtn">
								<input type="button" value="+" onclick="addRowInQry2(this)">
							</td>
						</tr>
					`;
					tr.classList.add('fs-14'); tr.innerHTML = td;
					let row_no = document.getElementById("row_no1"); row_no.value = parseInt(row_no.value) + 1;
					if(flag) {
						params = "'" + params.join("','") + "'";
						e.setAttribute('onClick', `deleteRow(${params})`);
						e.innerText = "Delete Row";
					} else e.remove();
				}

				function addRowInQry3(e, flag = false, params) {
					let tbody = document.getElementById("qry3Tbody");
					let rowsCount = tbody.rows.length, key = rowsCount + 1;
					let tr = tbody.insertRow(rowsCount);
					let td = `
						<tr>
							<td style="width:20px;" onClick="<?php if ($option == 'edit') { ?>outpocket_delRow(this, ${key})<?php } ?>">
								<input type="hidden" name="out_ok_ind${key}" id="out_ok_ind${key}" value="Y" readonly onClick="<?php if ($option == 'edit') { ?>outpocket_delRow(this, ${key})<?php } ?>">
								<input type="hidden" name="out_source_code${key}" id="out_source_code${key}" value="">
								<input type="hidden" name="out_amt${key}" value="">
							</td>
							<td style="width:115px;">
								<input class="w-100 form-control" name="date${key}" id="date${key}" value="" onBlur="<?php if ($option == 'edit') { ?>chkActivity2(this, ${key})<?php } ?>" />
							</td>
							<td class="brkwrd" style="width:120px;">
								<input class="form-control w-100" name="details${key}" id="details${key}" value="" />
							</td>
							<td style="width:85px;">
								<input class="w-100 text-left form-control" name="amount${key}" id="amount${key}" value="0.00" onBlur="<?php if ($option == 'edit') { ?>amtValidate(this, ${key}, 'Grid2'); calc_service_tax('${key}','<?php echo $params['tax_per'] ?>','Grid2')<?php } ?>" />
							</td>
							<td style="width:75px;">
								<input class="w-100 text-left" type="checkbox" class="" name="tax_ind${key}" id="tax_ind${key}" value="Y" onClick="calc_service_tax('${key}','<?php echo $params['tax_per'] ?>','Grid2')" />
								<input class="w-100 text-left" type="hidden" name="tax_percent${key}" id="tax_percent${key}" value="">
								<input class="w-100 text-left" type="hidden" name="tax_amount${key}" id="tax_amount${key}" value="">
							</td>
							<td style="width:75px;">
								<input class="w-100" type="checkbox" name="printer${key}" id="printer${key}" value="Y" onClick="myCheckPrintInd('${key}','Grid2')" checked />
							</td>
							<td style="width:75px;">
								<input class="w-100 form-control" name="prn_seq${key}" id="prn_seq${key}" value="" />
							</td>
							<td class="TbladdBtn w-5">
								<input type="button" value="+" onclick="addRowInQry3(this)">
							</td>
						</tr>
					`;
					tr.classList.add('fs-14'); tr.innerHTML = td;
					let row_no = document.getElementById("row_no2"); row_no.value = parseInt(row_no.value) + 1;
					if(flag) {
						params = "'" + params.join("','") + "'";
						e.setAttribute('onClick', `deleteRow(${params})`);
						e.innerText = "Delete Row";
					} else e.remove();
				}

				function deleteRow(id = '', rowCountId = '', actionBtn = '', callFunction = '') {
					var table = document.getElementById(id);
					var addBtn = table.lastElementChild.lastElementChild.innerHTML;
					var rows = table.querySelectorAll('.rowSlcted');

					if(rows.length > 0) {
						Swal.fire({
							title: 'Do you want to Delete ??',
							showCancelButton: true,
							confirmButtonText: 'Yes!! Delete',
						}).then((result) => {
							if (result.isConfirmed) {
								for (let row of rows) row.remove();
	
								var table = document.getElementById(id);
								let totalRows = table.children.length;
								if(totalRows > 0) table.lastElementChild.lastElementChild.innerHTML = addBtn;
								if(totalRows == 0) {
									let btnSpan = document.getElementById(actionBtn);
									btnSpan.firstElementChild.setAttribute('onClick', callFunction + `(this, true, ['${id}', '${rowCountId}', '${actionBtn}', '${callFunction}'])`);
									btnSpan.firstElementChild.innerText = "Add Row";
								}
								let row_no = document.getElementById(rowCountId); row_no.value = parseInt(row_no.value) - rows.length;
							}
						})
					} else {
						Swal.fire('Select Atleast <b> One Row </b> to Perform Delete Operation !!')
					}
				}
			</script>

		<?php } ?>
</main>
<!-- End #main -->
<?= $this->endSection() ?>