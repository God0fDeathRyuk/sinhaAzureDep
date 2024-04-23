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
	<h1>Counsel Memo Os </h1>
	</div>

	<form action="" method="post">
		<div class="frms-sec d-inline-block w-100 bg-white p-3">
			<div class="d-inline-block w-100">
			<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">As On </label>
					<input type="text" class="form-control float-start w-100 ms-0 set-date datepicker withdate" name="ason_date"  value=""readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
					<select class="form-select cstm-inpt" name="branch_code">
					<?php foreach($data['branches'] as $branch) { ?>
					<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-md-2 float-start px-2 position-relative mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Counsel Code</label>
				<input type="text" class="form-control" id="counselCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'associate_code', ['counselName'], ['associate_name'], 'counsel_code')" size="05" maxlength="06" name="counsel_code" />
				<i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselCode', ['counselName'], ['associate_name'], 'counsel_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Counsel Name</label>
				<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()"  id="counselName" name="counsel_name" readonly/>
			</div>
			<div class="col-md-3 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Initial</label>
				<select class="form-select" name="initial_code">
				<option value="%">--All--</option>
					<?php foreach($initial_qry as $initial_row) { ?>
						<option value="<?php echo $initial_row['initial_code'] ?>"><?php echo $initial_row['initial_name']?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-3 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
				<select class="form-select" name="output_type" tabindex="12" required>
    				<option value="">--Select--</option>
    				<option value="Report">View Report</option>
    				<option value="Pdf" >Download PDF</option>
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
	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
			<div class="position-absolute btndv">
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
					<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>	
			</div>

		<?php
		//----- 
		$maxline    = 40 ;
		$lineno     = 0 ;
		$pageno     = 0 ;
		$tosamt     = 0 ;
		$tstamt     = 0 ;
		$ttotosamt  = 0 ;
		$rowcnt     = 1 ;
		$report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
		$report_cnt = $params['memo_cnt'] ; 
		while ($rowcnt <= $report_cnt)
		{
		$mosamt      = 0; 
		$mstamt      = 0 ;
		$mtotosamt   = 0 ;
		$pcounselcd  = $report_row['counsel_code'] ;
		$pcounselnm  = $report_row['counsel_name'] ;
		$pcounselind = 'Y';
		while ($pcounselcd == $report_row['counsel_code'] && $rowcnt <= $report_cnt)
		{
			if ($lineno == 0 || $lineno > $maxline)
			{
			if($lineno > $maxline)
			{ 
		?>
								</table>
							</td>
						</tr>
					</table>
					<BR CLASS="pageEnd"> 
		<?php
				}
				$pageno = $pageno + 1 ;
		?>
			<table class="table border-0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="7">    
						<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
							<tr><td class="report_label_text" colspan="4" align="center"><b><u>Sinha and Company</u></b></td></tr>
							<tr><td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td></tr>
							<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							</tr>
							<tr>
							<td class="report_label_text">&nbsp;Branch</td>
							<td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name'] ?></b></td>
							<td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
							<td class="report_label_text">&nbsp;:&nbsp;<?= $params['date'] ?></td>
							</tr>
							<tr>
							<td class="report_label_text">&nbsp;As On</td>
							<td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['ason_date'] ?></td>
							<td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
							<td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
							</tr>
							<tr>
							<td class="report_label_text">&nbsp;Counsel</td>
							<td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['counsel_desc'] ?></b></td>
							<td class="report_label_text">&nbsp;</td>
							<td class="report_label_text">&nbsp;</td>
							</tr>
							<tr>
							<td class="report_label_text">&nbsp;Initial</td>
							<td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['initial_desc'] ?></b></td>
							<td class="report_label_text">&nbsp;</td>
							<td class="report_label_text">&nbsp;</td>
							</tr>
						</table>
					</td>    
				</tr>
				<tr class=""><td>&nbsp;</td></tr>
				<tr class="fs-14">
					<th width="10%" align="left"  class="py-3 px-2">&nbsp;<b>Memo Sl</b></th>
					<th width="15%" align="left"  class="py-3 px-2">&nbsp;<b>Memo No/Dt</b></th>
					<th width="10%" align="left"  class="py-3 px-2">&nbsp;<b>Initial</b></th>
					<th width="30%" align="left"  class="py-3 px-2">&nbsp;<b>Client/Matter/Narration</b></th>
					<th width="10%" align="left" class="py-3 px-2">&nbsp;<b>Amount</b>t&nbsp;</th>
					<th width="12%" align="left" class="py-3 px-2">&nbsp;<b>S/Tax</b>&nbsp;</th>
					<th width="13%" align="left" class="py-3 px-2">&nbsp;<b>Total</b>&nbsp;</th>
				</tr>
				
		<?php
					$lineno = 9 ;
					$pcounselind = 'Y';
				}
				if($pcounselind == 'Y')
				{
		?>
								<tr class="fs-14 border-0">
									<td height="20" style="background-color: #e2e6506e;" class="p-2" align="left"  colspan="7"><b><u><?php echo $pcounselnm?></u></b></td> 
								</tr>
		<?php
			$pcounselind = 'N';
			$lineno      = $lineno + 1;
			}

			$client_qry   = $clients[$rowcnt-1]; 
			$client_name  = $client_qry['client_name'] ; 
		//
			$matter_qry   = $clients[$rowcnt-1];
			$matter_desc1 = $matter_qry['matter_desc1'] ; 
			$matter_desc2 = $matter_qry['matter_desc2'] ; 
			$matter_desc = ($matter_desc1 != '') ? $matter_desc1 . ' : ' . $matter_desc2 : $matter_desc1 ; 
			//
		?>
							<tr class="fs-14 border-0">
								<td align="left"  class="p-2" style="vertical-align:text-top"><?php echo $report_row['serial_no'] ?></td> 
								<td align="left"  class="p-2" style="vertical-align:text-top"><?php echo $report_row['memo_no'] ?></td> 
								<td align="left"  class="p-2" style="vertical-align:text-top"><?php echo $report_row['initial_code'] ?></td> 
								<td align="left"  class="p-2" style="vertical-align:text-top"><b><?php echo $client_name ?></b></td>
								<td align="left" class="p-2" style="vertical-align:text-top"><?php if($report_row['os_amount']    != 0) {echo number_format($report_row['os_amount'],   2,'.',',');}?>&nbsp;</td>
								<td align="left" class="p-2" style="vertical-align:text-top"><?php if($report_row['stax_amount']  != 0) {echo number_format($report_row['stax_amount'], 2,'.',',');}?>&nbsp;</td>
								<td align="left" class="p-2" style="vertical-align:text-top"><?php if($report_row['totos_amount'] != 0) {echo number_format($report_row['totos_amount'],2,'.',',');}?>&nbsp;</td>

							</tr>
							<tr class="fs-14 border-0">
								<td align="left"  class="p-2" style="vertical-align:text-top">&nbsp;</td> 
								<td align="left"  class="p-2" style="vertical-align:text-top"><?php echo date_conv($report_row['memo_date']) ?></td> 
								<td align="left"  class="p-2" style="vertical-align:text-top">&nbsp;</td> 
								<td align="left"  class="p-2" style="vertical-align:text-top"><u><?php echo 'Matter: ['.$report_row['matter_code'].'] '.'-'.'Re.: '. $matter_desc.']'; ?></u></td>
								<td align="left" class="p-2" style="vertical-align:text-top" colspan="3">&nbsp;</td>
							</tr>
							<tr class="fs-14 border-0">
								<td align="left"  class="p-2" style="vertical-align:text-top">&nbsp;</td> 
								<td align="left"  class="p-2" style="vertical-align:text-top">&nbsp;</td> 
								<td align="left"  class="p-2" style="vertical-align:text-top">&nbsp;</td> 
								<td align="left"  class="p-2" style="vertical-align:text-top">&nbsp;&nbsp;&nbsp;<i><?php echo $report_row['narration'] ?></i></td>
								<td align="left" class="p-2" style="vertical-align:text-top" colspan="3">&nbsp;</td>
							</tr>
		<?php     
				$lineno    = $lineno    + 3;
				$mosamt    = $mosamt    + $report_row['os_amount'] ;
				$mstamt    = $mstamt    + $report_row['stax_amount'] ;                   
				$mtotosamt = $mtotosamt + $report_row['totos_amount'] ;     

				$report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
				$rowcnt = $rowcnt + 1 ;
			}  
		?>                   
							<tr class="fs-14 border-0">
								<td height="20" colspan="4" style="background-color: #e2e6506e;" align="right" class="p-2"><b>TOTAL</b>&nbsp;</td>
								<td height="20" align="left" style="background-color: #e2e6506e;" class="p-2"><b><?php if($mosamt    != 0) {echo number_format($mosamt,   2,'.',',');} ?></b>&nbsp;</td>
								<td height="20" align="left" style="background-color: #e2e6506e;" class="p-2"><b><?php if($mstamt    != 0) {echo number_format($mstamt,   2,'.',',');} ?></b>&nbsp;</td>
								<td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" colspan="3"><b><?php if($mtotosamt != 0) {echo number_format($mtotosamt,2,'.',',');} ?></b>&nbsp;</td>

							</tr>
							<tr class=""><td>&nbsp;</td></tr>
		<?php
			$lineno    = $lineno    + 1;
			$tosamt    = $tosamt    + $mosamt ;  
			$tstamt    = $tstamt    + $mstamt ;                   
			$ttotosamt = $ttotosamt + $mtotosamt ;                   
						
			}
		?>
							<tr class="fs-14 border-0">
								<td height="20" colspan="4" style="background-color: #99cfe1;" align="center" class="p-2"><b>GRAND TOTAL</b>&nbsp;</td>
								<td height="20" align="left" style="background-color: #99cfe1;" class="p-2"><b><?php if($tosamt    != 0) {echo number_format($tosamt,   2,'.',',');} ?></b>&nbsp;</td>
								<td height="20" align="left" style="background-color: #99cfe1;" class="p-2"><b><?php if($tstamt    != 0) {echo number_format($tstamt,   2,'.',',');} ?></b>&nbsp;</td>
								<td height="20" align="left" style="background-color: #99cfe1;" class="p-2"  colspan="3"><b><?php if($ttotosamt != 0) {echo number_format($ttotosamt,2,'.',',');} ?></b>&nbsp;</td>
							</tr>
					</table>
					</td>
				</tr>
			</table> 
    </main>
<?php } ?>
<!-- End #main -->
<?= $this->endSection() ?>