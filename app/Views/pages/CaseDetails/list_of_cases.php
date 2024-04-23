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

	<div class="pagetitle w-100 float-start border-bottom pb-1">
		<h1 class="col-md-8 float-start">Cases As On Date </h1>
	</div>

	<form action="" method="post">
		<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
		<div class="frms-sec d-inline-block w-100 bg-white p-3"> 
			<div class="d-inline-block w-100">
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">As On Date</label>
					<input type="text" class="form-control float-start w-60 ms-0 set-date datepicker withdate" name="ason_date" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
					<select class="form-select cstm-inpt" name="branch_code">
					<?php foreach($data['branches'] as $branch) { ?>
					<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
					<?php } ?>
					</select>
				</div>
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
				<label class="d-inline-block w-100 mb-2 lbl-mn">Court Code</label>
				<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" size="05" maxlength="06" name="court_code"/>
				<i class="fa-solid fa-binoculars icn-vw" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Court name</label>
				<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="courtName" name="court_name" readonly/>
			</div>
			<div class="col-md-2 float-start px-2 position-relative mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Status Code</label>
				<input type="text" class="form-control" size="02" maxlength="02" id="statusCode" name="status_code" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'code_code', ['statusName'], ['code_desc'], 'status_code')"/>
				<i class="fa-solid fa-binoculars icn-vw" onclick="showData('code_code', '<?= $displayId['status_help_id'] ?>', 'statusCode', ['statusName'], ['code_desc'], 'status_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Status Description</label>
				<input type="text" class="form-control" name="status_name" id="statusName" oninput="this.value = this.value.toUpperCase()" readonly/>
			</div>
			<div class="col-md-3 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
				<select class="form-select" name="output_type" tabindex="12" required>
					<option value="">--Select--</option>
					<option value="Report">View Report</option>
					<option value="Pdf" >Download PDF</option>
					<option value="Excel" >Download Excel</option> 
				</select>
			</div>
		</div>
		<button type="submit" class="btn btn-primary cstmBtn mt-3">Proceed</button>
		<button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
	</form>
</main><!-- End #main -->
<?php } else { ?>
	<script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>
	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
			<div class="position-absolute btndv">
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a> -->
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
					<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
			<?php
			foreach ($reports as $key => $report) { 
				if($key % 6 == 0) { ?>
				
				<table class="table border-0" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="7" class="text-center border-0" align="center">
						<span class="d-block w-100 text-uppercase fw-bold">Sinha and Company</span>
						<!-- <span class="d-block w-100 text-uppercase fw-bold">Cases to be appeared during a period [next date wise]</span> -->
					</td>
				</tr>
				<tr>
					<td colspan="7" class="text-center border-0" align="center">
					    <span class="d-block w-100 text-uppercase fw-bold"><b><u> <?= strtoupper($params['report_desc']) ?> </u></b></span>
				    </td>
				</tr>
				<tr>
					<td colspan="7" class="text-center border-0"  align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5" class="border-0">
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">Branch  </span><strong>: <?= $params['branch_name'] ?> </strong>
						</p>
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">As On </span><strong>: <?= $params['ason_date'] ?> </strong>
						</p>
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">Client </span><strong>: <b><?php if($params['client_code'] != '%') { echo strtoupper($params['client_name']) ; } else { echo 'ALL' ; } ?></b></strong>
						</p>
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">Court </span><strong>: <b><?php if($params['court_code'] != '%') { echo strtoupper($params['court_name']) ; } else { echo 'ALL' ; } ?></b></strong>
						</p>
						<p class="d-block w-100 text-uppercase">
							<span class="w-15 d-block float-start">Status </span><strong>: <b><?php if($params['status_code'] != '%') { if(isset($params['status_desc'])){ echo strtoupper($params['status_desc']) ; } else{ echo '';}} else { echo 'ALL' ; } ?></b></strong>
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
				<tr>
					<td colspan="7" class="text-center border-0"  align="center">&nbsp;</td>
				</tr>
				<tr class="fs-14">
					<th class="p-2">
						<span>Court</span>
					</th>
					<th class="p-2">
						<span>Judge</span>
					</th>
					<th class="p-2">
						<span>Master</span>
					</th>
					<th class="p-2">
						<span>Case No/Matter Description</span>
					</th>
					<th class="p-2">
						<span>Filling Dt</span>
					</th>
					<th class="p-2">
						<span>Amount</span>
					</th>
					<th class="p-2">
						<span>Status</span>
					</th>
				</tr>
				<?php } ?> 
				<!-- table data  -->
				<tr class="fs-14 border-0">
					<td class="p-2 fw-bold" colspan="7"> <?= $report['client_name'] ?> </td>
				</tr>
				<tr class="fs-14 border-0">
					<td class="p-2"> <?= $report['court_name'] ?> </td>
					<td class="p-2"> <?= $report['judge_name'] ?> </td>
					<td class="p-2"> <?= $report['matter_code'] ?> </td>
					<td class="p-2"> <?= $report['matter_desc1'] ?> </td>
					<td class="p-2"> <?= date_conv($report['date_of_filing'],'-') ?> </td>
					<td class="p-2"> <?= $report['stake_amount'] ?> </td>
					<td class="p-2"> <?= $report['status_name'] ?> </td>
				</tr>
				<tr class="fs-14 border-0">
					<td class="p-2" style="background-color:#fefee0;"> </td>
					<td class="p-2" style="background-color:#fefee0;"> </td>
					<td class="p-2" style="background-color:#fefee0;"> </td>
					<td class="p-2" style="background-color:#fefee0;" colspan="4"> <?= $report['matter_desc2'] ?> </td>
				</tr>
				
				
				<tr class="">
					<td class="border-0 p-0" colspan="7"><span class="w-100 hrClr"></span></td>
				</tr>
			<?php
				if(($key+1) % 6 == 0 || (count($reports)-1) == $key) echo "</table>";
			} ?>	
	</main>
<?php } ?>
<!-- End #main -->

<?= $this->endSection() ?>