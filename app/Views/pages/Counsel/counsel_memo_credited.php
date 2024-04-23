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
	<h1>Counsel Memo (Credited)</h1>
	</div>

	<form action="" method="post">
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
				<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker" name="start_date" placeholder="dd-mm-yyyy" onblur="make_date(this)" value="<?= $curr_fyrsdt ?>"/>
				<span class="float-start mt-2 ms-2">To</span>
				<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" name="end_date" onblur="make_date(this)" />
				<span class="eee"></span>
			</div>
			<div class="col-md-3 float-start px-2 position-relative mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Counsel Code</label>
				<input type="text" class="form-control" id="counselCode" oninput="this.value = this.value.toUpperCase()"  onchange="fetchData(this, 'associate_code', ['counselName'], ['associate_name'], 'counsel_code')" size="05" maxlength="06" name="counsel_code" />
				<i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselCode', ['counselName'], ['associate_name'], 'counsel_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
			</div>
			<div class="col-md-5 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Counsel Name</label>
				<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()"  id="counselName" name="counsel_name" readonly/>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
				<select class="form-select" name="output_type" tabindex="12" required>
    				<option value="">--Select--</option>
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
	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
			<div class="position-absolute btndv">
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a> -->
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
					<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
		<?php

		$tcramt  = 0; 
		$tstamt  = 0; 
		$ttcramt = 0;
		$maxline = 52 ;
		$lineno  = 0 ;
		$pageno  = 0 ;
		$tcfamt  = 0; 
		$tfjamt  = 0;
		$rowcnt  = 1 ;
		$report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
		$report_cnt = $params['memo_cnt'] ;
		while ($rowcnt <= $report_cnt)
		{
		$mcramt   = 0;
		$mstamt   = 0;
		$mtcramt  = 0; 
		$pcnslind = 'Y';
		$pcnslcd  = $report_row['counsel_code'] ;
		$pcnslnm  = $report_row['associate_name'] ;
		while ($pcnslcd == $report_row['counsel_code'] && $rowcnt <= $report_cnt)
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
			<table class="table border-0" align="center" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="8">    
						<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">							
							<tr>
								<td class="report_label_text" colspan="4" align="center"><b><u>Sinha and Company</u></b></td>
							</tr>
							<tr>
								<td class="report_label_text" colspan="4" align="center"><b><u> <?= strtoupper($params['report_desc']) ?> </u></b></td>
							</tr>
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
								<td class="report_label_text">&nbsp;Period</td>
								<td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['start_date']. ' - ' .  $params['end_date'] ; ?></b></td>
								<td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
								<td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
							</tr>
							<tr>
								<td class="report_label_text">&nbsp;</td>
								<td class="report_label_text">&nbsp;</td>
								<td class="report_label_text">&nbsp;</td>
								<td class="report_label_text">&nbsp;</td>
							</tr>
						</table>
					</td>    
				</tr>
				<tr class="fs-14">
					<th width="4%" align="left"  class="py-3 px-2">Srl#</th>
					<th width="12%" align="left" class="py-3 px-2">Memo No</th>
					<th width="10%" align="left"  class="py-3 px-2">Memo Dt</th>
					<th width="38%" align="left" class="py-3 px-2">Client/Matter</th>
					<th width="8%" align="left" class="py-3 px-2">Amount</th>
					<th width="8%" align="left" class="py-3 px-2">S Tax</th>
					<th width="9%" align="left" class="py-3 px-2">Total Amount</th>
					<th width="11%" align="left" class="py-3 px-2">JV No/Dt</th>
				</tr>
							
	<?php
				$lineno   = 8 ;
				$pcnslind = 'Y' ;
			}
			if($pcnslind == 'Y')
			{
	?>
							<tr class="fs-14 border-0">
								<td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" colspan="8"><b><?php echo strtoupper($pcnslnm)?></b></td> 
							</tr>
	<?php
			$lineno   = $lineno + 1;
			$pcnslind = 'N';
			}
	?>
							<tr class="fs-14 border-0">
								<td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['serial_no'];?></td> 
								<td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['memo_no'];?></td> 
								<td align="left"  class="p-2" style="vertical-align:top"><?php echo date_conv($report_row['memo_date']);?></td> 
								<td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['client_name'];?></td>
								<td align="left" class="p-2" style="vertical-align:top"><?php echo $report_row['counsel_amount'];?></td>
								<td align="left" class="p-2" style="vertical-align:top"><?php echo $report_row['stax_amount'];?></td>
								<td align="left" class="p-2" style="vertical-align:top"><?php echo $report_row['totcr_amount'];?></td>
								<td align="left" class="p-2" style="vertical-align:top">&nbsp;&nbsp;<?php echo $report_row['doc_no'];?></td>
							</tr>
							<tr class="fs-14 border-0">
								<td height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
								<td height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
								<td height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
								<td height="15" align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['matter_desc'];?></td>
								<td height="15" align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
								<td height="15" align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
								<td height="15" align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
								<td height="15" align="left" class="p-2" style="vertical-align:top"><?php echo date_conv($report_row['doc_date']);?></td>
							</tr>
		<?php     
				$lineno = $lineno + 2;
				$mcramt = $mcramt + $report_row['counsel_amount'] ; 
				$mstamt = $mstamt + $report_row['stax_amount'] ;                   
				$mtcramt = $mtcramt + $report_row['totcr_amount'] ;                   

				$report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
				$rowcnt = $rowcnt + 1 ;
			}  
		?>                   
							<tr class="fs-14 border-0">
								<td colspan="8" class="p-2">&nbsp;</td>
							</tr>
							<tr class="fs-14 border-0">
								<td height="18" align="center" style="background-color: #e2e6506e;"  class="p-2" colspan="4"><b>Total</b></td>
								<td height="18" align="left" style="background-color: #e2e6506e;" class="p-2"><b><?php echo number_format($mcramt,2,'.','') ?></b></td>
								<td height="18" align="left" style="background-color: #e2e6506e;" class="p-2"><b><?php echo number_format($mstamt,2,'.','') ?></b></td>
								<td height="18" align="left" style="background-color: #e2e6506e;" class="p-2"><b><?php echo number_format($mtcramt,2,'.','') ?></b></td>
								<td height="18" align="left" style="background-color: #e2e6506e;" class="p-2">&nbsp;</td>
							</tr>
							<tr class="fs-14 border-0">
								<td colspan="8" class="p-2">&nbsp;</td>
							</tr>
	<?php
	
		$lineno  = $lineno + 2;
		$tcramt  = $tcramt + $mcramt ; 
		$tstamt  = $tstamt + $mstamt ;                   
		$ttcramt = $ttcramt + $mtcramt ;                   
									
		}
	?>
							<tr class="fs-14 border-0">
								<td colspan="6"class="p-2">&nbsp;</td>
							</tr>
							<tr class="fs-14 border-0">
								<td height="18" align="center" style="background-color: #99cfe1;" class="p-2" colspan="4"><b> Grand Total </b></td>
								<td height="18" align="left" style="background-color: #99cfe1;"  class="p-2"><b><?php echo number_format($tcramt,2,'.','') ?></b></td>
								<td height="18" align="left" style="background-color: #99cfe1;"  class="p-2"><b><?php echo number_format($tstamt,2,'.','') ?></b></td>
								<td height="18" align="left"  style="background-color: #99cfe1;" class="p-2"><b><?php echo number_format($ttcramt,2,'.','') ?></b></td>
								<td height="18" align="left"  style="background-color: #99cfe1;" class="p-2">&nbsp;</td>
							</tr>
					</table>
					</td>
				</tr>
			</table> 
	</main>
	
<?php } ?>

<!-- End #main -->
<?= $this->endSection() ?>