<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if (!isset($reports)) { ?> 
 
	<main id="main" class="main">

	<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
    <?php endif; ?>

		<div class="pagetitle col-md-12 float-start border-bottom pb-1">
		<h1>Bill Send (Court/Client/Matter/Initial) </h1>
		</div>

		<form action="" method="post">
		<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
                    <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">As On</label>
						<input type="text" class="form-control float-start w-100 ms-0 set-date datepicker withdate" name="ason_date" readonly/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
						<select class="form-select cstm-inpt" name="branch_code">
						<?php foreach($data['branches'] as $branch) { ?>
						<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
						<?php } ?>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-6 ps-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Period</label>
						<span class="float-start mt-2">From</span>
						<input type="text" class="form-control float-start w-40 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="start_date" onBlur="make_date(this)"/>
						<span class="float-start mt-2 ms-2">To</span>
						<input type="text" class="form-control float-start w-40 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="end_date" onBlur="make_date(this)"/>
					</div>
                    <div class="col-md-2 float-start px-2 position-relative mb-3">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Court Code</label>
                        <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" size="05" maxlength="06" name="court_code"/>
						<i class="fa-solid fa-binoculars icn-vw" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
				    </div>
                    <div class="col-md-4 float-start px-2 mb-3">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Court Name</label>
                        <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="courtName" name="court_name" readonly/>
                    </div>
					<div class="col-md-2 float-start px-2 position-relative mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
						<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code" />
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>

					</div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
						<input type="text" class="form-control" id="clientName" oninput="this.value = this.value.toUpperCase()"  name="client_name" readonly/>
					</div>
					<div class="col-md-2 float-start px-2 position-relative mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code</label>
						<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc'], ['matter_desc'], 'matter_code')" size="05" maxlength="06" name="matter_code"/>
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
					</div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Desc</label>
						<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="matterDesc" name="matter_desc" readonly/>
					</div>
					<div class="col-md-2 float-start px-2 position-relative mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Initial Code</label>
						<input type="text" class="form-control" id="initialCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'initial_code', ['initialName'], ['initial_name'], 'initial_code')" size="05" maxlength="06" name="initial_code"/>
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('initial_code', '<?= $displayId['initial_help_id'] ?>', 'initialCode', ['initialName'], ['initial_name'], 'initial_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
					</div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Initial name</label>
						<input type="text" class="form-control" id="initialName" oninput="this.value = this.value.toUpperCase()" name="initial_name" readonly/>
					</div>
					<div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Bill For</label>
						<select class="form-select" name="billfor_ind">
                            <option value="%">All</option>
                            <option value="N">Others</option>
                            <option value="Y" >Court Fee</option>
						</select>
					</div>
                    <div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Report Seq</label>
						<select class="form-select" name="report_seqn">
                            <option value="I">Court-wise</option>
                            <option value="C">Client-wise</option>
                            <option value="B">Bill-wise</option>
                            <option value="N">Initial wise</option>
						</select>
					</div>
                    <div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Report Type</label>
						<select class="form-select" name="report_type">
                        <option value="D">Detail</option>
						</select>
					</div>
                    <div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
						<select class="form-select" name="output_type">
						    <option value="Report">View Report</option>
							<option value="Pdf">Download PDF</option>
							<option value="Excel">Download Excel</option>
						</select>
					</div>
			</div>
				<button type="submit" class="btn btn-primary cstmBtn mt-3">Proced</button>
				<button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
		</form>
	</main>

<?php } else { ?>
    <script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>

	<div class="tbl-sec d-inline-block w-100 p-3 position-relative bg-white" style="background-color:#fff !important;">
			<div class="position-absolute btndv">
				<!--<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>-->
				<!--<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a>-->
				<?php if ($renderFlag) : ?>
                    <a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>

			<?php
			foreach ($reports as $key => $report) { 
				if($key % 6 == 0) {
					echo '<table class="table border-0">'; ?> 
				<tr>
					<td colspan="9" class="text-center border-0" align="center">
						<span class="d-block w-100 text-uppercase fw-bold">Sinha and Company</span>
					</td>
				</tr>
				<tr>
					<td colspan="9" class="text-center border-0" align="center">
						<span class="d-block w-100 text-uppercase fw-bold"><b><u> Bill Send Report </u></b></span>
					</td>
				</tr>
				<tr>
					<td colspan="8" class="border-0">
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">Branch  </span><strong>: <?= $params['branch_name'] ?> </strong>
						</p>
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">Client </span><strong>: <b><?php if($params['client_code'] != '%') { echo strtoupper($params['client_name']) ; } else { echo 'ALL' ; } ?></b></strong>
						</p>
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">Matter </span><strong>: <b><?php if($params['matter_code'] != '%') { echo strtoupper($params['matter_desc']) ; } else { echo 'ALL' ; } ?></b></strong>
						</p>
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">Court </span><strong>: <b><?php if($params['court_code'] != '%') { echo strtoupper($params['court_name']) ; } else { echo 'ALL' ; } ?></b></strong>
						</p>
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">Initial </span><strong>: <b><?php if($params['initial_code'] != '%') { echo strtoupper($params['initial_name']) ; } else { echo 'ALL' ; } ?></b></strong>
						</p>
					</td>
					<td colspan="2" class="border-0">
						<p class="d-block w-100">
							<span>Date : <strong><?= $params['date'] ?></strong></span>
						</p>
						<p class="d-block w-100">
							<span>Page : <strong> <?= ($key / 6) + 1 ?></strong></span>
						</p>
					</td>
				</tr>
				<tr class="fs-14">
					<th class="border py-3 px-2">
						<span>Sl#</span>
					</th>
					<th class="border py-3 px-2">
						<span>Bill No</span>
					</th>
					<th class="border py-3 px-2">
						<span>Bill Dt</span>
					</th>
					<th class="border py-3 px-2">
						<span>Initial</span>
					</th>
					<th class="border py-3 px-2">
						<span>Client</span>
					</th>
					<th class="border py-3 px-2">
						<span>Matter</span>
					</th>
					<th class="border py-3 px-2">
						<span>Case</span>
					</th>
                    <th class="border py-3 px-2">
						<span>Desc</span>
					</th>
                    <th class="border py-3 px-2" style="width:150px;">
						<span>Total</span>
					</th>
                    <th class="border py-3 px-2">
						<span>Send On</span>
					</th>
				</tr>

				<?php } ?> 
				<!-- table data  -->
				<tr class="fs-14 border-0">
					<td class="border p-2"> <?= $key + 1 ?> </td>
					<td class="border p-2"> <?= $report['bill_number'] ?> </td>
					<td class="border p-2"> <?= date_conv($report['bill_date'],'-') ?> </td>
					<td class="border p-2"> <?= strtoupper($report['initial_code']) ?> </td>
					<td class="border p-2"> <?= strtoupper($report['client_name']) ?> </td>
					<td class="border p-2"> <?= strtoupper($report['matter_code']) ?> </td>
					<td class="border p-2"> <?php if($report['matter_desc1'] == '') {echo '-';} else {echo strtoupper($report['matter_desc1']);}?> </td>
					<td class="border p-2"> <?= strtoupper($report['matter_desc2']) ?> </td>
					<td class="border p-2"> <?php if($report['totamt']  > 0) { echo currency_format($report['totamt'],2,'.',''); }?> </td>
					<td class="border p-2">  </td>
				 </tr>
				
				<!-- <tr class="">
					<td class="border-0 p-0" colspan="7"><span class="w-100 hrClr"><hr/></span></td>
				</tr> -->
			<?php
				if((($key + 1) % 6 == 0) || (count($reports)-1) == $key) echo "</table>";
				
			} ?>	
		</div>

<?php } ?>
<!-- End #main -->
<?= $this->endSection() ?>