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
	<h1>Cases Appeared</h1>
	</div>

	<form action="" method="post">
		<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
		<div class="frms-sec d-inline-block w-100 bg-white p-3">
			<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
				<select class="form-select cstm-inpt" name="branch_code">
				<?php foreach($data['branches'] as $branch) { ?>
				<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
				<?php } ?>
				</select>
			</div>
			<div class="frms-sec-insde d-block float-start col-md-9 ps-2 mb-4">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Period</label>
				<span class="float-start mt-2">From</span>
				<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" name="start_date" placeholder="dd-mm-yyyy" onBlur="make_date(this)"/>
				<span class="float-start mt-2 ms-2">To</span>
				<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" name="end_date" onBlur="make_date(this)"/>
				<span class="eee"></span>
				
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
				<label class="d-inline-block w-100 mb-2 lbl-mn">Options</label>
				<select class="form-select" name="desc_ind" tabindex="10">
				<option value="N" >Without Particulars</option>
				<option value="Y" >With Particulars</option>
				</select>
			</div>
			<div class="col-md-3 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Options Seq</label>
				<select class="form-select" name="report_seq" tabindex="11">
				<option value="1" >Activity Date-wise</option>
				<option value="2" >Matter/Activity Date-wise</option>
				</select>
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
			<div class="col-md-3 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Forwarding</label>
				<select class="form-select" name="forward_inp" tabindex="13">
				<option value="A">All</option>
				<option value="Y">Yes</option>
				<option value="N">No</option>
				</select>
			</div>
			<input type="hidden"  name="selemode" value="Y">
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
<!--<table class="table border-0" cellpadding="0" cellspacing="0">			    <tbody><tr>-->
<!--    				<td colspan="6" class="text-center border-0" align="center">-->
<!--    					<span class="">Sinha and Company</span>-->
<!--    				</td>-->
<!--				</tr>-->
<!--				<tr>-->
<!--    				<td colspan="6" class="text-center border-0" align="center">-->
<!--    				    <span class=""><b><u> CASES APPEARED DURING A PERIOD [ACTIVITY DATE-WISE] </u></b></span>-->
    					<!-- <span class="d-block w-100 text-uppercase fw-bold">Cases to be appeared during a period [next date wise]</span> -->
<!--    				</td>-->
<!--			    </tr>-->
<!--			<tr>-->
<!--				<td colspan="3" class="border-0">-->
<!--					<p class="w-100 text-uppercase">-->
<!--						<span class="w-15 float-start">Branch  </span><strong>: KOLKATA </strong>-->
<!--					</p>-->
<!--					<p class="w-100 text-uppercase">-->
<!--						<span class="w-15  float-start">Period </span><strong>: 26-09-2023 - 26-10-2023 </strong>-->
<!--					</p>-->
<!--					<p class="w-100">-->
<!--						<span>Date : <strong>26-10-2023</strong></span>-->
<!--					</p>-->
<!--					<p class="w-100 text-uppercase">-->
<!--						<span class="w-15 float-start">Client </span><strong>: <b>ALL</b></strong>-->
<!--					</p>-->
<!--					<p class="w-100 text-uppercase">-->
<!--						<span class="w-15 float-start">Matter </span><strong>: <b>ALL</b></strong>-->
<!--					</p>-->
<!--				</td>-->
				
<!--				<td colspan="3" class="border-0">-->
<!--					<p class="w-100 text-uppercase">-->
<!--						<span class="w-15 float-start">Court </span><strong>: <b>ALL</b></strong>-->
<!--					</p>-->
<!--					<p class="w-100">-->
<!--						<span>Page : <strong> 1</strong></span>-->
<!--					</p>-->
<!--					<p class="w-100">-->
<!--						<span>Forwarding : <strong> All</strong></span>-->
<!--					</p>-->
<!--				</td>-->
<!--			</tr>-->
			
<!--			<tr><td width="10%" align="left" colspan="6" class="py-1">&nbsp;</td></tr>-->
			
<!--			<tr class="fs-14">-->
<!--				<th class=""> SL </th>-->
<!--				<th class=""> Dt/Mtr/F-Dt/Amt </th> -->
<!--				<th class=""> Client/Matter Description/Judge/Court/Reference </th> -->
<!--				<th class=""> Fix For (The Day) </th> -->
<!--				<th class=""> Next Dt/Fix For </th> -->
<!--				<th class=""> Prev Dt/Fix For </th> -->
<!--			</tr>-->

			 
			<!-- table data  -->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> 1 </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--				<td height="20" align="left" class="p-2"> FASTBOOK </td>-->
<!--				<td height="20" align="left" class="p-2"><b>  </b> </td>-->
<!--				<td height="20" align="left" class="p-2">  </td>-->
<!--				<td height="20" align="left" class="p-2">  </td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2">&nbsp;</td> -->
<!--				<td height="20" align="left" class="p-2">326665 </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> MISCELLANEOUS </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
				 
				  
<!--			</tr>-->
<!--						<tr>-->
<!--				<td height="20" align="left" class="p-2"> </td> -->
<!--				<td height="20" align="left" class="p-2">0.00</td> -->
<!--				<td height="20" align="left" class="p-2" colspan="2">BANKSHALL COURT, KOLKATA</td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--			</tr>-->
<!--												<tr class="">-->
<!--				<td class="border-0 p-0" height="20" align="left" colspan="7"><span class="w-100 hrClr"><hr></span></td>-->
<!--			</tr>-->
			 
			<!-- table data  -->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> 2 </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--				<td height="20" align="left" class="p-2"> FASTBOOK </td>-->
<!--				<td height="20" align="left" class="p-2"><b>  </b> </td>-->
<!--				<td height="20" align="left" class="p-2">  </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2">&nbsp;</td> -->
<!--				<td height="20" align="left" class="p-2">326665 </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> MISCELLANEOUS </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> test2</td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
				 
				  
<!--			</tr>-->
<!--						<tr>-->
<!--				<td height="20" align="left" class="p-2"> </td> -->
<!--				<td height="20" align="left" class="p-2">0.00</td> -->
<!--				<td height="20" align="left" class="p-2" colspan="2">BANKSHALL COURT, KOLKATA</td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--			</tr>-->
<!--												<tr class="">-->
<!--				<td class="border-0 p-0" height="20" align="left" colspan="7"><span class="w-100 hrClr"><hr></span></td>-->
<!--			</tr>-->
			 
			<!-- table data  -->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> 3 </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--				<td height="20" align="left" class="p-2"> FASTBOOK </td>-->
<!--				<td height="20" align="left" class="p-2"><b>  </b> </td>-->
<!--				<td height="20" align="left" class="p-2"> 10-10-2023 </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2">&nbsp;</td> -->
<!--				<td height="20" align="left" class="p-2">326665 </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> MISCELLANEOUS </td>-->
<!--				<td height="20" align="left" class="p-2"> anindita</td>-->
<!--				<td height="20" align="left" class="p-2"> anindita</td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
				 
				  
<!--			</tr>-->
<!--						<tr>-->
<!--				<td height="20" align="left" class="p-2"> </td> -->
<!--				<td height="20" align="left" class="p-2">0.00</td> -->
<!--				<td height="20" align="left" class="p-2" colspan="2">BANKSHALL COURT, KOLKATA</td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--			</tr>-->
<!--												<tr class="">-->
<!--				<td class="border-0 p-0" height="20" align="left" colspan="7"><span class="w-100 hrClr"><hr></span></td>-->
<!--			</tr>-->
			 
			<!-- table data  -->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> 4 </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--				<td height="20" align="left" class="p-2"> FASTBOOK </td>-->
<!--				<td height="20" align="left" class="p-2"><b>  </b> </td>-->
<!--				<td height="20" align="left" class="p-2">  </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2">&nbsp;</td> -->
<!--				<td height="20" align="left" class="p-2">326665 </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> MISCELLANEOUS </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> anindita</td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
				 
				  
<!--			</tr>-->
<!--						<tr>-->
<!--				<td height="20" align="left" class="p-2"> </td> -->
<!--				<td height="20" align="left" class="p-2">0.00</td> -->
<!--				<td height="20" align="left" class="p-2" colspan="2">BANKSHALL COURT, KOLKATA</td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--			</tr>-->
<!--												<tr class="">-->
<!--				<td class="border-0 p-0" height="20" align="left" colspan="7"><span class="w-100 hrClr"><hr></span></td>-->
<!--			</tr>-->
			 
			<!-- table data  -->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> 5 </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--				<td height="20" align="left" class="p-2"> FASTBOOK </td>-->
<!--				<td height="20" align="left" class="p-2"><b>  </b> </td>-->
<!--				<td height="20" align="left" class="p-2">  </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2">&nbsp;</td> -->
<!--				<td height="20" align="left" class="p-2">326665 </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> MISCELLANEOUS </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
				 
				  
<!--			</tr>-->
<!--						<tr>-->
<!--				<td height="20" align="left" class="p-2"> </td> -->
<!--				<td height="20" align="left" class="p-2">0.00</td> -->
<!--				<td height="20" align="left" class="p-2" colspan="2">BANKSHALL COURT, KOLKATA</td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--			</tr>-->
<!--												<tr class="">-->
<!--				<td class="border-0 p-0" height="20" align="left" colspan="7"><span class="w-100 hrClr"><hr></span></td>-->
<!--			</tr>-->
			 
			<!-- table data  -->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> 6 </td>-->
<!--				<td height="20" align="left" class="p-2"> 05-10-2023 </td>-->
<!--				<td height="20" align="left" class="p-2"> ICICI BANK LTD. </td>-->
<!--				<td height="20" align="left" class="p-2"><b>  </b> </td>-->
<!--				<td height="20" align="left" class="p-2">  </td>-->
<!--				<td height="20" align="left" class="p-2"> 10-02-2014 </td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2">&nbsp;</td> -->
<!--				<td height="20" align="left" class="p-2">123456 </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2">TS 2106 OF 2007 ICICI BANK LTD. V/S SUBHAS CHOWDHURY </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> DISMISSED FOR DEFAULT.</td>-->
<!--			</tr>-->
<!--			<tr class="fs-14 border-0">-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2">06-08-2007 </td>-->
<!--				<td height="20" align="left" class="p-2" colspan="2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
				 
				  
<!--			</tr>-->
<!--						<tr>-->
<!--				<td height="20" align="left" class="p-2"> </td> -->
<!--				<td height="20" align="left" class="p-2">282749.00</td> -->
<!--				<td height="20" align="left" class="p-2" colspan="2">CITY CIVIL COURT, KOLKATA</td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--				<td height="20" align="left" class="p-2"> </td>-->
<!--			</tr>-->
						 
<!--				<tr>-->
<!--					<td height="20" align="left" class="p-2"> </td> -->
					 
<!--					<td height="20" align="left" class="p-2">{2065555}</td> -->
<!--										<td height="20" align="left" class="p-2" colspan="2">LUCAL00003907394</td>-->
<!--					<td height="20" align="left" class="p-2"> </td>-->
<!--					<td height="20" align="left" class="p-2"> </td>-->
<!--				</tr>-->
<!--									<tr class="">-->
<!--				<td class="border-0 p-0" height="20" align="left" colspan="7"><span class="w-100 hrClr"><hr></span></td>-->
<!--			</tr>-->
<!--			</tbody></table>-->
			
			
			
			<?php
				foreach ($reports as $key => $report) {
					$report['serial_no'] = isset($report['serial_no']) ? $report['serial_no'] : '';
					$report['court_name'] = isset($report['court_name']) ? $report['court_name'] : '';
			
					$day_fixed_for = get_fixed_for($report['matter_code'], $report['activity_date']);

					if($key % 6 == 0) { ?>
						<table class="table border-0" cellpadding="0" cellspacing="0"> 
			    <tr>
    				<td colspan="6" class="text-center border-0" align="center">
    					<span class="">Sinha and Company</span>
    				</td>
				</tr>
				<tr>
    				<td colspan="6" class="text-center border-0" align="center">
    				    <span class=""><b><u> <?= strtoupper($params['report_desc']) ?> </u></b></span>
    					<!-- <span class="d-block w-100 text-uppercase fw-bold">Cases to be appeared during a period [next date wise]</span> -->
    				</td>
			    </tr>
			<tr>
				<td colspan="5" class="border-0">
					<p class="w-100 text-uppercase">
						<span class="w-15 float-start">Branch  </span><strong>: <?= $params['branch_name'] ?> </strong>
					</p>
					<p class="w-100 text-uppercase">
						<span class="w-15  float-start">Period </span><strong>: <?= $params['period_desc'] ?> </strong>
					</p>
					<p class="w-100">
						<span class="w-15  float-start">Date : </span><strong><?= $params['date'] ?></strong>
					</p>
					<p class="w-100 text-uppercase">
						<span class="w-15 float-start">Client </span><strong>: <b><?php if($params['client_code'] != '%') { echo strtoupper($params['client_name']) ; } else { echo 'ALL' ; } ?></b></strong>
					</p>
					<p class="w-100 text-uppercase">
						<span class="w-15 float-start">Matter </span><strong>: <b><?php if($params['matter_code'] != '%') { echo strtoupper($params['matter_desc']) ; } else { echo 'ALL' ; } ?></b></strong>
					</p>
				</td>
				
				<td colspan="2" class="border-0">
					<p class="d-block w-100 text-uppercase">
						<span class="">Court <strong>: <b><?php if($params['court_code'] != '%') { echo strtoupper($params['court_name']) ; } else { echo 'ALL' ; } ?></b></strong></span>
					</p>
					<p class="w-100">
						<span>Page : <strong> <?= ($key / 6) + 1 ?></strong></span>
					</p>
					<p class="w-100">
						<span>Forwarding : <strong> <?= ($params['forwarding_ind']); ?></strong></span>
					</p>
				</td>
			</tr>
			
			<tr><td width="10%" align="left" colspan="6"  class="py-1">&nbsp;</td></tr>
			
			<tr class="fs-14">
				<th class="p-2"> SL </th>
				<th class="p-2"> Dt/Mtr/F-Dt/Amt </th> 
				<th class="p-2"> Client/Matter Description/Judge/Court/Reference </th> 
				<th class="p-2"> Fix For (The Day) </th> 
				<th class="p-2"> Next Dt/Fix For </th> 
				<th class="p-2"> Prev Dt/Fix For </th> 
			</tr>

			<?php } ?> 
			<!-- table data  -->
			<tr class="fs-14 border-0">
				<td height="20" align="left"  class="p-2"> <?= $key + 1 ?> </td>
				<td height="20" align="left"  class="p-2"> <?= date_conv($report['activity_date'],'-') ?> </td>
				<td height="20" align="left"  class="p-2"> <?= $report['client_name'] ?> </td>
				<td height="20" align="left"  class="p-2"><b> <?= $day_fixed_for ?> </b> </td>
				<td height="20" align="left"  class="p-2"> <?= date_conv($report['next_date'],'-') ?> </td>
				<td height="20" align="left"  class="p-2"> <?= date_conv($report['prev_date'],'-') ?> </td>
			</tr>
			<tr class="fs-14 border-0">
				<td height="20" align="left"  class="p-2">&nbsp;</td> 
				<td height="20" align="left"  class="p-2"><?= $report['matter_code'] ?> </td>
				<td height="20" align="left"  class="p-2" colspan="2"><?= $report['matter_desc'] ?> </td>
				<td height="20" align="left"  class="p-2"> <?= strtoupper($report['next_fixed_for']) ?></td>
				<td height="20" align="left"  class="p-2"> <?= strtoupper($report['prev_fixed_for']) ?></td>
			</tr>
			<tr class="fs-14 border-0">
				<td height="20" align="left"  class="p-2"> </td>
				<td height="20" align="left"  class="p-2"><?php if ($report['date_of_filing'] != '' && $report['date_of_filing'] != '0000-00-00') { echo date_conv($report['date_of_filing'],'-') ; } else { echo '' ; } ?> </td>
				<td height="20" align="left"  class="p-2" colspan="2"> <?= $report['judge_name'] ?></td>
				<td height="20" align="left"  class="p-2"> </td>
				<?php if ($params['report_seq'] == '1') { ?> 
				<?php } else { ?>
				<td height="20" align="left"  class="p-2"><?= $report['serial_no'] ?> </td>
				<?php } ?>  
			</tr>
			<?php 
			if($report['stake_amount'] != '' || $report['court_name'] != '') { ?>
			<tr class="fs-14">
				<td height="20" align="left"  class="p-2" style="background-color:#fefee0;"> </td> 
				<td height="20" align="left"  class="p-2" style="background-color:#fefee0;"><?php echo $report['stake_amount'] ?></td> 
				<td height="20" align="left"  class="p-2" colspan="2" style="background-color:#fefee0;"><?php echo $report['court_name']?></td>
				<td height="20" align="left"  class="p-2" style="background-color:#fefee0;"> </td>
				<td height="20" align="left"  class="p-2" style="background-color:#fefee0;"> </td>
			</tr>
			<?php } ?>
			<?php if($report['reference_desc'] != '') {  ?> 
				<tr class="fs-14">
					<td height="20" align="left"  class="p-2"> </td> 
					<?php if ($params['report_seq'] == '1') { ?> 
					<td height="20" align="left"  class="p-2">{<?php echo $report['serial_no']?>}</td> 
					<?php } else { ?>
						<td height="20" align="left"  class="p-2"> </td> 
					<?php } ?>
					<td height="20" align="left"  class="p-2" colspan="2"><?php echo $report['reference_desc']?></td>
					<td height="20" align="left"  class="p-2"> </td>
					<td height="20" align="left"  class="p-2"> </td>
				</tr>
			<?php } ?>
			<?php if($params['desc_ind'] == 'Y') {  ?>
				<tr class="fs-14">
					<td height="20" align="left"  class="p-2" colspan="2"><i>Particulars</i> </td> 
					<td height="20" align="left"  class="p-2" colspan="4"> </td>
				</tr>
			<?php

				$tot_char   = 110 ;
				$hdr_desc      = $report['header_desc'].chr(13);
				$header_desc   = wordwrap($hdr_desc, $tot_char, "\n");
				$header_array  = explode("\n",$header_desc);
				$array_row     = count($header_array);

				for($i=0;$i<$array_row;$i++) {

					$header_desc = text_justify(trim(nl2br(stripslashes($header_array[$i]))),$tot_char);
					$header_desc = str_replace("<br />",'',$header_desc);
			?>
				<tr class="fs-14">
					<td height="20" align="left"  class="p-2" colspan="2"></td> 
					<td height="20" align="left"  class="p-2" colspan="4"><i><?php echo $header_desc;?></i></td>
				</tr>

        			<?php 
        	  	    } 
        		} ?>
			<tr class="fs-14">
				<td class="border-0 p-0" height="20" align="left" class="p-2" colspan="7"></span></td>
			</tr>
			<?php if(($key+1) % 6 == 0 || (count($reports)-1) == $key) echo "</table>";
			} ?>	
		</main>
	
<?php } ?>

<!-- End #main -->
<?= $this->endSection() ?>