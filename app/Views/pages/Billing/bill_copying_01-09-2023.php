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

			<div class="frms-secLft col-md-6 float-start <?php if (isset($res)) { }else{ echo 'blcpyMn'; } ?>">
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-2 position-relative w48">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Serial#</label>
					<input type="text" class="form-control cstm-inpt" id="SerialNo" value="" onchange="fetchData(this, 'serial_no', ['billNo','billAmount','matterCode1'], [], 'serial_no')" onfocusout="getBillInfo(this)" name="serial_no" required />
					<i class="fa-solid fa-eye inpt-vw pe-2" onclick="showData('serial_no', '<?= $displayId['casesrl_help_id'] ?>', 'SerialNo', ['billNo','billAmount','matterCode1'], [], 'serial_no')" data-toggle="modal" data-target="#lookup"></i>
					<input type="hidden" name="court_fee_bill_ind" id="courtFeeBillInd" value="" />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-2 w48">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill No</label>
					<input type="text" class="form-control" name="bill_no" id="billNo" value="" readonly />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-2 w-100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Amount</label>
					<input type="text" class="form-control" name="bill_amount" id="billAmount" value="" readonly />
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-2 w-100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter </label>
					<input type="text" class="form-control w-35 float-start" name="matter_code1" id="matterCode1" value="" readonly />
					<input type="text" class="form-control w-63 ms-2 float-start" name="matter_desc1" id="matterDesc1" value="" readonly />
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-2 w-100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client </label>
					<input type="text" class="form-control w-35 float-start" name="client_code1" id="clientCode1" value="" readonly />
					<input type="text" class="form-control w-63 ms-2 float-start" name="client_name1" id="clientName1" value="" readonly />
				</div>
				<div class="col-md-4 float-start px-2 mb-2 w-100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Ref No</label>
					<input type="text" class="form-control" placeholder="Ref No" name="reference_desc" id="referenceDesc" value="" readonly />
				</div>
				<div class="col-md-4 float-start px-2 mb-2 w-100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
					<textarea type="text" class="form-control" rows="2" name="subject_desc" id="subjectDesc" readonly></textarea>
				</div>
				<div class="col-md-4 float-start px-2 mb-3 w-100">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Other Case(s)</label>
					<textarea type="text" class="form-control" rows="2" name="other_case_desc" id="otherCaseDesc" readonly></textarea>
				</div>
				<div class="d-inline-block w-100">
					<button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2 BilCpyprcdBtn" onclick="formOption('/billing/copying/', 'proceed', 'billCopying')">Proceed</button>
				</div>
			</form>
		</div>
		<?php if (isset($res)) { // FORM - 2 ?>
			
				<form action="" method="get" id="billCopying2" name="billCopying2">
				<div class="col-md-6 float-start">
					<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
					<input type="hidden" name="row_counter1" id="row_counter1" value="<?php echo $count; ?>">
					<input type="hidden" name="cur_date" id="cur_date" value="<?php echo $cur_date; ?>">

					<div class="d-inline-block w-100 mt-4 mb-2 bnd">
						<span class="d-block float-start w-25">Bill Details</span>
						<div class="d-block float-start w-75">
							<div class="d-block float-start me-4">
								<input type="radio" name="grant_bill" class="me-1" id="slctAll" value="A" onClick="selectAll_from()" <?php if ($count > 0) { echo 'checked';} ?> />
								<label for="slctAll">Select All</label>
							</div>
							<div class="d-block float-start">
								<input type="radio" name="grant_bill" class="me-1" id="deslctAll" value="D" onClick="deSelectAll_from()" />
								<label for="deslctAll">De-Select All</label>
							</div>
						</div>
					</div>
					<div class="mntblsec mb-5 scrlTblMd NwVrtScrl">
						<table class="table table-bordered tblhdClr">
							<tr>
								<th class="fntSml"><span>&nbsp;</span></th>
								<th class="fntSml"><span>Date</span></th>
								<th class="fntSml"><span>Cnsl</span></th>
								<th class="fntSml w-35"><span>Particulars</span></th>
								<th class="fntSml w-5"><span>I/O</span></th>
								<th class="fntSml"><span>Amount</span></th>
								<th class="fntSml text-center"><span>Copy?</span></th>
							</tr>
							<?php foreach ($res as $key => $row) { ?>
								<tr>
									<td id="Ctd<?php echo $key + 1 ?>">
										<input type="hidden" name="inp_ok_ind<?php echo $key + 1 ?>" value="" readonly />
										<input type="hidden" name="source_code<?php echo $key + 1 ?>" value="<?= $row['source_code'] ?>" />
										<input type="hidden" name="activity_type<?php echo $key + 1 ?>" value="<?= $row['activity_type'] ?>" />
										<input type="hidden" name="printer_ind<?php echo $key + 1 ?>" value="<?= $row['printer_ind'] ?>" />
										<input type="hidden" name="prn_seq_no<?php echo $key + 1 ?>" value="<?= $row['prn_seq_no'] ?>" />
									</td>
									<td><input type="text" class="form-control" name="activity_date<?php echo $key + 1 ?>" value="<?= date_conv($row['activity_date']) ?>" readonly /></td>
									<td><input type="text" class="form-control" name="counsel_code<?php echo $key + 1 ?>" value="<?= $row['counsel_code'] ?>" readonly /></td>
									<td><textarea class="form-control" name="activity_desc<?php echo $key + 1 ?>" readonly> <?= stripslashes($row['activity_desc']) ?> </textarea></td>
									<td class="text-center"><input type="text" class="form-control text-center" name="io_ind<?php echo $key + 1 ?>" value="<?= $row['io_ind'] ?>" readonly /></td>
									<td><input type="text" class="form-control" name="billed_amount<?php echo $key + 1 ?>" value="<?= $row['billed_amount'] ?>" readonly /></td>
									<td class="text-center"><input type="checkbox" class="cbxInpt" name="copy_ind<?php echo $key + 1 ?>" id="copy_ind<?php echo $key + 1 ?>" value="Y" checked /></td>
								</tr>
							<?php } ?>
						</table>
					</div>
				</div>
				
				<!-- Table Size Small -->
				<div class="col-md-12 float-start mt-4 border-top pt-3  <?php if (isset($res)) { }else{ echo 'blcpyMn'; } ?>">
				<div class="d-inline-block w-100">
					<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3 position-relative">
						<label class="d-inline-block w-100 mb-2 lbl-mn">New Matter</label>
						<input type="text" class="form-control" name="matter_code" oninput="this.value = this.value.toUpperCase();" onfocusout="getMatterInfo(this)" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc', 'clientCode', 'clientName'], ['matter_desc', 'client_code', 'client_name'], 'matter_code');" />
						<i class="fa-solid fa-binoculars icn-vw" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc', 'clientCode', 'clientName'], ['matter_desc', 'client_code', 'client_name'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Date Upto</label>
						<input type="text" class="form-control" name="bill_date_upto" id="billDateUpto" onBlur="chkActivity(this)" value="" />
					</div>
					<div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-3">
						<textarea class="form-control" rows="3" name="matter_desc" oninput="this.value = this.value.toUpperCase()" id="matterDesc" readonly></textarea>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3 w100">
						<label class="d-inline-block w-100 mb-2 lbl-mn">New Client</label>
						<input type="text" class="form-control w-35 float-start" name="client_code" id="clientCode" value="" readonly>
						<input type="text" class="form-control w-63 float-start ms-2" name="client_name" id="clientName" value="" readonly>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3 w100">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Other Cases</label>
						<div id="div_other_cases" class="d-block float-start w-100">
							<!-- <input type="checkbox" class="d-block float-start me-2 cbxInpt"/>
							<input type="text" class="form-control d-block float-start w-94"/> -->
						</div>
					</div>
				</div>
				<input type="hidden" name="other_case_count" id="otherCaseCount" value="" readonly>
				<button type="submit" class="btn btn-primary cstmBtn mt-31" onclick="formOption('/billing/copying/', 'proceed2', 'billCopying2')">Proceed</button>
				</form>
			</div>
		<?php } ?>



		<?php if (isset($res2)) { ?>
			<form action="" method="get" id="billCopying3" name="billCopying3">
				<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
				<input type="hidden" name="row_counter2" id="row_counter2" value="<?php echo $count2; ?>">
				<div class="d-inline-block w-100 mt-4 mb-2 bnd">
					<span class="d-block float-start w-75">Case/Expence Details:</span>
					<div class="d-block float-start w-25">
						<div class="d-block float-start me-4">
							<input type="radio" name="grant_case" value="A" onClick="selectAll_to()" <?php if ($count2 > 0) { echo 'checked';} ?> />
							<label for="expslctAll">Select All</label>
						</div>
						<div class="d-block float-start">
							<input type="radio" name="grant_case" value="D" onClick="deSelectAll_to()" />
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
						<?php foreach ($res2 as $key2 => $row2) { ?>
							<tr>
								<td>
									<input type="hidden" name="inp_ok_ind_i<?= $key2 + 1 ?>" value="" readonly />
									<input type="hidden" name="srl_no_1<?= $key2 + 1 ?>" value="<?= $row2['serial_no'] ?>" readonly />
								</td>
								<td>
									<input type="text" name="activity_date_1<?= $key2 + 1 ?>" value="<?= $row2['date'] ?>" readonly />
								</td>
								<td class="brkwrd">
									<textarea name="activity_desc_1<?= $key2 + 1 ?>" readonly><?= stripslashes($row2['details']) ?></textarea>
								</td>
								<td>
									<input type="text" name="bill_amount<?= $key2 + 1 ?>" value="<?= number_format($row2['amount'], '2', '.', '') ?>" readonly />
								</td>
								<td class="w-2 text-center" style="width:2%;">
									<input type="checkbox" name="new_copy_ind_i<?= $key2 + 1 ?>" id="new_copy_ind_i<?= $key2 + 1 ?>" checked />
								</td>
							</tr>
						<?php } ?>
					</table>
				</div>
		</div>
		<button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="formOption('/billing/copying/', 'confirm', 'billCopying3')">Confirm</button>
		<button type="reset" class="btn btn-primary cstmBtn mt-3 ms-2">Refresh</button>
		<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</button>

	<?php } ?>
	</form>
</main>
<!-- End #main -->
<?= $this->endSection() ?>