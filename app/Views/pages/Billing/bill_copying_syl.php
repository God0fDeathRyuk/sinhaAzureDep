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
		</div>
	<?php endif; ?>
	<?php if (session()->getFlashdata('noted_number') !== NULL) : ?>
		<div id="alertMsg">
			<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
				<div> <b> <?= session()->getFlashdata('noted_number') ?> </b> </div>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		</div>
	<?php endif; ?>

	<div class="pagetitle col-md-12 float-start border-bottom pb-1">
		<h1>Bill Copying</h1>
	</div>
		<div class="myform">
			<form action="" method="get" id="billCopying" name="billCopying" onsubmit="billCopy(event)">
				<div class="frms-sec d-inline-block w-100 bg-white p-3 frmDv1">

					<div class="frms-secLft col-md-12 float-start ">
						<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-2 position-relative">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Serial#</label>
							<input type="text" class="form-control cstm-inpt" id="SerialNo" value="" onchange="fetchData(this, 'serial_no', ['billNo','billAmount','matterCode1'], [], 'serial_no')" onfocusout="getBillInfo(this)" name="serial_no" required />
							<i class="fa-solid fa-eye inpt-vw pe-2" onclick="showData('serial_no', '<?= $displayId['casesrl_help_id'] ?>', 'SerialNo', ['billNo','billAmount','matterCode1'], [], 'serial_no')" data-toggle="modal" data-target="#lookup"></i>
							<input type="hidden" name="court_fee_bill_ind" id="courtFeeBillInd" value="" />
						</div>
						<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-2">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Bill No</label>
							<input type="text" class="form-control" name="bill_no" id="billNo" value="" readonly />
						</div>
						<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-2">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Amount</label>
							<input type="text" class="form-control" name="bill_amount" id="billAmount" value="" readonly />
						</div>
						<div class="col-md-6 float-start px-2 position-relative mb-2">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Matter </label>
							<input type="text" class="form-control w-35 float-start" name="matter_code1" id="matterCode1" value="" readonly />
							<input type="text" class="form-control w-63 ms-2 float-start" name="matter_desc1" id="matterDesc1" value="" readonly />
						</div>
						<div class="col-md-6 float-start px-2 position-relative mb-2">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Client </label>
							<input type="text" class="form-control w-35 float-start" name="client_code1" id="clientCode1" value="" readonly />
							<input type="text" class="form-control w-63 ms-2 float-start" name="client_name1" id="clientName1" value="" readonly />
						</div>
						<div class="col-md-4 float-start px-2 mb-2">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Ref No</label>
							<input type="text" class="form-control" placeholder="Ref No" name="reference_desc" id="referenceDesc" value="" readonly />
						</div>
						<div class="col-md-4 float-start px-2 mb-2">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
							<textarea type="text" class="form-control" rows="2" name="subject_desc" id="subjectDesc" readonly></textarea>
						</div>
						<div class="col-md-4 float-start px-2 mb-3">
							<label class="d-inline-block w-100 mb-2 lbl-mn">Other Case(s)</label>
							<textarea type="text" class="form-control" rows="2" name="other_case_desc" id="otherCaseDesc" readonly></textarea>
						</div>
						<div class="d-inline-block w-100">
							<button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2 BilCpyprcdBtn" id="BilCpyprcdBtn" onclick="formOption('/billing/copying/', 'proceed', 'billCopying')">Proceed</button>
						</div>
					</div>
				</div>
			</form>

			<form action="" method="get" id="billCopying2" name="billCopying2" class="d-none gffg" onsubmit="billCopy(event)">
				<div class="frmDv1">
					<input type="hidden" name="row_counter1" id="row_counter1" value="">
					<input type="hidden" name="cur_date" id="cur_date" value="">

					<div class="d-inline-block w-100 mt-4 mb-2 bnd">
						<span class="d-block float-start w-75">Bill Details</span>
						<div class="d-block float-start w-25">
							<div class="d-block float-start me-3">
								<input type="radio" name="grant_bill" class="me-1" id="slctAll" value="A" onClick="selectAll_from()" />
								<label for="slctAll">Select All</label>
							</div>
							<div class="d-block float-start">
								<input type="radio" name="grant_bill" class="me-1" id="deslctAll" value="D" onClick="deSelectAll_from()" />
								<label for="deslctAll">De-Select All</label>
							</div>
						</div>
					</div>
					<div class="mntblsec mb-2 NwVrtScrl">
						<table class="table table-bordered tblhdClr">
							<thead>
								<tr>
									<!-- <th class="fntSml"><span>&nbsp;</span></th> -->
									<th class="fntSml"><span>Date</span></th>
									<th class="fntSml"><span>Cnsl</span></th>
									<th class="fntSml w-35"><span>Particulars</span></th>
									<th class="fntSml w-5"><span>I/O</span></th>
									<th class="fntSml"><span>Amount</span></th>
									<th class="fntSml text-center"><span>Copy?</span></th>
								</tr>
							</thead>

							<tbody id="1stThead">
								<tr>
									<td colspan="7" class="text-center"> No Records Found !! </td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- Table Size Small -->
					<div class="col-md-12 float-start mt-0 pt-3">
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
							<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
								<label class="d-inline-block w-100 mb-2 lbl-mn">New Client</label>
								<input type="text" class="form-control w-35 float-start" name="client_code" id="clientCode" value="" readonly>
								<input type="text" class="form-control w-63 float-start ms-2" name="client_name" id="clientName" value="" readonly>
							</div>
							<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
								<label class="d-inline-block w-100 mb-2 lbl-mn">Other Cases</label>
								<div id="div_other_cases" class="d-block float-start w-100">
									<!-- <input type="checkbox" class="d-block float-start me-2 cbxInpt"/>
										<input type="text" class="form-control d-block float-start w-94"/> -->
								</div>
							</div>
						</div>
						<input type="hidden" name="other_case_count" id="otherCaseCount" value="" readonly>
						<button type="submit" class="btn btn-primary cstmBtn mt-31" id="BilCpyprcdBtn2" onclick="formOption('/billing/copying/', 'proceed2', 'billCopying2')">Proceed</button>
					</div>
				</div>
			</form>

			<form action="" method="post" id="billCopying3" name="billCopying3" class="d-none">
				
				<div class="frmDv1">
					<input type="hidden" name="row_counter2" id="row_counter2" value="">
					<div class="d-inline-block w-100 mt-4 mb-2 bnd">
						<span class="d-block float-start w-75">Case/Expence Details:</span>
						<div class="d-block float-start w-25">
							<div class="d-block float-start me-4">
								<input type="radio" name="grant_case" value="A" id="slctAll2" onClick="selectAll_to()" />
								<label for="slctAll2">Select All</label>
							</div>
							<div class="d-block float-start">
								<input type="radio" name="grant_case" value="D" id="deslctAll2" onClick="deSelectAll_to()" />
								<label for="deslctAll2">De-Select All</label>
							</div>
						</div>
					</div>
					<div class="d-inline-block w-100">
						<table class="table table-bordered tblhdClr">
							<thead>
								<tr>
									<th class="fntSml d-none"> <span></span> </th>
									<th class="fntSml"> <span>Date</span> </th>
									<th class="fntSml" style="width:40%;"> <span>Particulars</span> </th>
									<th class="fntSml"> <span>Amount</span> </th>
									<th class="fntSml"> <span>Copy?</span> </th>
								</tr>
							</thead>

							<tbody id="2ndThead">
								<tr>
									<td colspan="7" class="text-center"> No Records Found !! </td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</form>

			<div id="submitBtn" class="d-none">
				<button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="formOption('/billing/copying/', 'confirm', 'billCopying3')">Confirm</button>
				<button type="reset" class="btn btn-primary cstmBtn mt-3 ms-2">Refresh</button>
				<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</button>
			</div>

		</div>
	</div>
	
</main>
<!-- End #main [blcpyMn] -->
<?= $this->endSection() ?>