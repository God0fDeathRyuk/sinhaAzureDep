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
		<h1 class="col-md-8 float-start">List of Case(s) [Billable Option-wise] </h1>
	</div>

	<form action="" method="post">
		<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
		<div class="frms-sec d-inline-block w-100 bg-white p-3">
			<div class="d-inline-block w-100">
				<div class="frms-sec-insde d-block float-start col-md-9 ps-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Period</label>
					<span class="float-start mt-2">From</span>
					<input class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" type="text" name="start_date" onBlur="make_date(this)"/>
					<span class="float-start mt-2 ms-2">To</span>
					<input class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" type="text" name="end_date" onBlur="make_date(this)"/>
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
				<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code</label>
				<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc'], ['matter_desc'], 'matter_code')" size="05" maxlength="06" name="matter_code"/>
				<i class="fa-solid fa-binoculars icn-vw" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Matter Description</label>
				<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="matterDesc" name="matter_desc" readonly/>
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
				<label class="d-inline-block w-100 mb-2 lbl-mn">Initial Code</label>
				<input type="text" class="form-control" id="initialCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'initial_code', ['initialName'], ['initial_name'], 'initial_code')" size="05" maxlength="06" name="initial_code"/>
				<i class="fa-solid fa-binoculars icn-vw" onclick="showData('initial_code', '<?= $displayId['initial_help_id'] ?>', 'initialCode', ['initialName'], ['initial_name'], 'initial_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Initial name</label>
				<input type="text" class="form-control" id="initialName" oninput="this.value = this.value.toUpperCase()" name="initial_name" readonly/>
			</div>
			<div class="col-md-3 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Option</label>
				<select class="form-select" name="billing_option">
				<option value="P" >Pre-Billable</option>
				<option value="N" >Non-Billable</option>
				</select>
			</div>
			<div class="col-md-3 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Report Seq</label>
				<select class="form-select" name="report_seq">
				<option value="1" >Activity Date-wise</option>
				<option value="2" >Client/Matter/Activity Date-wise</option>
				</select>
			</div>
			<div class="col-md-3 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Output Type</label>
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
</main>

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
					<table class="table border-0" cellspacing="0" cellpadding="0"> 
		<tr>
			<td colspan="<?= ($params['report_seq'] == '1') ? 10 : 7 ?>" class="text-center border-0" align="center">
				<span class="d-block w-100 text-uppercase fw-bold">Sinha and Company</span>
				<!-- <span class="d-block w-100 text-uppercase fw-bold">Cases to be appeared during a period [next date wise]</span> -->
			</td>
		</tr>
		<tr>
			<td colspan="<?= ($params['report_seq'] == '1') ? 10 : 7 ?>" class="text-center border-0" align="center">
			    <span class="d-block w-100 text-uppercase fw-bold"><b><u> <?= strtoupper($params['report_desc']) ?> </u></b></span>
		    </td>
		</tr>
		<tr>
			<td colspan="<?= ($params['report_seq'] == '1') ? 10 : 7 ?>" class="text-center border-0"  align="center">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="<?= ($params['report_seq'] == '1') ? 8 : 6 ?>" class="border-0">
				<p class="d-block w-100 text-uppercase">
					<span class="w-15 d-block float-start">Branch  </span><strong>: <?= $params['branch_name'] ?> </strong>
				</p>
				<p class="d-block w-100 text-uppercase">
					<span class="w-15 d-block float-start">Period </span><strong>: <?= $params['period_desc'] ?> </strong>
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
			<td colspan="<?= ($params['report_seq'] == '1') ? 2 : '' ?>" class="border-0">
				<p class="d-block w-100">
					<span>Date : <strong><?= $params['date'] ?></strong></span>
				</p>
				<p class="d-block w-100">
					<span>Page : <strong> <?= ($key / 6) + 1 ?></strong></span>
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="<?= ($params['report_seq'] == '1') ? 11 : 7 ?>" class="text-center border-0"  align="center">&nbsp;</td>
		</tr>
		<tr class="fs-14">
			<?php if ($params['report_seq'] == '1') { ?> 
				<th class="p-2"> <span>SL</span> </th>
				<th class="p-2"> <span>Case Srl</span> </th> 
				<th class="p-2"> <span>Date</span> </th> 
				<th class="p-2"> <span>Matter</span> </th> 
				<th class="p-2"> <span>Client/Matter Description</span> </th> 
				<th class="p-2"> <span>Court</span> </th> 
				<th class="p-2"> <span>Prev fixed for</span> </th> 
				<th class="p-2"> <span>Next fixed for</span> </th> 
				<th class="p-2"> <span>Bill Optn</span> </th> 
				<th class="p-2"> <span>Prepared By</span> </th>
			<?php } else { ?>
				<th class="p-2"> <span>SL</span> </th>
				<th class="p-2"> <span>RecSrl</span> </th> 
				<th class="p-2"> <span>Date</span> </th> 
				<th class="p-2"> <span>Matter</span> </th> 
				<th class="p-2"> <span>Matter Description</span> </th> 
				<th class="p-2"> <span>Court</span> </th> 
				<th class="p-2"> <span>Letter Ref</span> </th> 
			<?php } ?>
		</tr>

		<?php } ?> 
		<!-- table data  -->
		<?php if ($params['report_seq'] == '1') { ?>
		<tr class="fs-14 border-0">
			<td class="p-2"> <?= $key + 1 ?> </td>
			<td class="p-2"> <?= $report['serial_no'] ?> </td>
			<td class="p-2"> <?= date_conv($report['activity_date'],'-') ?> </td>
			<td class="p-2"> <?= $report['matter_code'] ?> </td>
			<td class="p-2"><b> <?= $report['client_name'] ?> </b> </td>
			<td class="p-2"><b> <?= $report['court_name'] ?> </b> </td>
			<td class="p-2"> <?= $report['prev_fixed_for'] ?> </td>
			<td class="p-2"> <?= $report['next_fixed_for'] ?> </td>
			<td class="p-2"> <?php if($report['billable_option'] == 'N') {echo 'Non Billable';} if($report['billable_option'] == 'P') {echo 'Pre-Billable';}?> </td>
			<td class="p-2"> <?= $report['prepared_by'] ?> </td>
		</tr>
		<tr class="fs-14 border-0">
			<td class="p-2" style="background-color:#fefee0;"> </td>
			<td class="p-2" style="background-color:#fefee0;"> </td>
			<td class="p-2" style="background-color:#fefee0;"> </td>
			<td class="p-2" style="background-color:#fefee0;"> </td>
			<td class="p-2 border" colspan="3" style="background-color:#fefee0;"> <?php echo 'Re: '. $report['matter_desc']?></td>
			<td class="p-2" style="background-color:#fefee0;"> </td>
			<td class="p-2" style="background-color:#fefee0;"> </td>
			<td class="p-2" style="background-color:#fefee0;"> </td>
		</tr>
		<?php } else { ?>	
			<tr class="fs-14 border-0">
			<td class="p-2"> <?= $key + 1 ?> </td>
			<td class="p-2"> <?= $report['serial_no'] ?> </td>
			<td class="p-2"> <?= date_conv($report['activity_date'],'-') ?> </td>
			<td class="p-2"> <?= $report['matter_code'] ?> </td>
			<td class="p-2" rowspan="2"><b> <?= $report['matter_desc'] ?> </b> </td>
			<td class="p-2" rowspan="2"><b> <?= $report['court_name'] ?> </b> </td>
			<td class="p-2"> <?= $report['letter_no'] ?> </td>
		</tr>
		<tr>
			<td class="p-2"> </td> 
			<td class="p-2"> </td> 
			<td class="p-2"> </td> 
			<td class="p-2"> </td> 
			<td class="p-2"> </td> 
		</tr>
		<?php } ?>     
		<?php
			if(($key+1) % 6 == 0 || (count($reports)-1) == $key) echo "</table>";
		} ?>	
	</main>
<?php } ?>
<!-- End #main -->
<?= $this->endSection() ?>