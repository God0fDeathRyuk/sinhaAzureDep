<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($trans_qry))) { ?> 
	<main id="main" class="main">
			
		<?php if (session()->getFlashdata('message') !== NULL) : ?>
			<div id="alertMsg">
				<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
				<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif; ?>

		<div class="pagetitle">
			<h1>List of Advance (Received)</h1>
		</div><!-- End Page Title -->

        <form action="" method="post" id="advancesReceived" name="advancesReceived" onsubmit="setValue(event)">
			<section class="section dashboard">
				<div class="row">
					<div class="col-md-12 mt-2">
						<div class="frms-sec d-inline-block w-100 bg-white p-3">
							<div class="col-md-3 float-start px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
								<select class="form-select" name="branch_code" required >
									<?php foreach($data['branches'] as $branch) { ?>
										<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
									<?php } ?>
								</select>
							</div>	
							<div class="col-md-6 float-start px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
								<input type="text" class="form-control w-45 float-start" name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required>
								<span class="w-2 float-start mx-2">---</span>
								<input type="text" class="form-control w-45 float-start" name="end_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required>
							</div>
							<div class="col-md-3 float-start px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Party Type <strong class="text-danger">*</strong></label>
								<select class="form-select w-100 float-start" name="payee_type" id="payeeType" required >
									<option value="C">Client</option>
								</select>
							</div>
							<div class="col-md-4 float-start px-2 mb-1 position-relative">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Party Code</label>					
								<input type="text" class="form-control w-100 float-start" name="payee_code" id="payeeCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeeName'], ['payee_payer_name'], 'advance_payee', 'advance_type=R&payee_type=@payeeType')" />
								<i class="fa fa-binoculars icn-vw" aria-hidden="true" id="payeeCodeLookup" onclick="showData('client_code', 'display_id=<?= $displayId['payee_help_id'] ?>&advance_type=R&payee_type=@payeeType', 'payeeCode', ['payeeName'], ['client_name'], 'advance_payee')" title="View" data-toggle="modal" data-target="#lookup"></i>
							</div>
							<div class="col-md-8 float-start px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Party Name</label>					
								<input type="text" class="form-control w-100 float-start" name="payee_name" id="payeeName" readonly/>
							</div>
							<div class="col-md-4 float-start px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
								<select class="form-select w-100 float-start" name="output_type" required >
									<option value="Report">View Report</option>
									<option value="Pdf" >Download PDF</option>
									<option value="Excel" >Download Excel</option>
								</select>
							</div>	
							<input type='hidden' name="advance_type" value="<?= $data['advance_type'] ?>">
							
							<div class="d-inline-block w-100 mt-2">
								<button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Proceed</button>				
								<button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Reset</button>
							</div>
						</div>
						
					</div>
					
				</div>
			</section>
		</form>
	</main>
<?php } else { ?>    

	<script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>

	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
			<div class="position-absolute btndv">
				<?php if ($renderFlag) : ?>
					<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
			<?php
				$maxline = 52 ;
				$lineno  = 0 ;
				$pageno  = 0 ;
				$tgrsamt = 0; 
				$ttaxamt = 0; 
				$tnetamt = 0 ;
				$rowcnt     = 1 ;
				$report_row = isset($trans_qry[$rowcnt-1]) ? $trans_qry[$rowcnt-1] : '' ;
				$report_cnt = $params['trans_cnt'] ;
				while ($rowcnt <= $report_cnt)
				{
				$pgrsamt = 0; 
				$ptaxamt = 0; 
				$pnetamt = 0 ;
				$ppayind = 'Y';
				$ppaycd  = $report_row['payee_payer_code'];
				$ppaynm  = $report_row['payee_payer_name'];
				while ($ppaycd == $report_row['payee_payer_code'] && $rowcnt <= $report_cnt)
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
					<table width="750" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
						<tr>
							<td colspan="9">    
								<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
									<tr>
									<td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td>
									</tr>
									<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									</tr>
									<tr>
									<td class="report_label_text">&nbsp;Branch</td>
									<td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
									<td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
									<td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y')?></td>
									</tr>
									<tr>
									<td class="report_label_text">&nbsp;Period</td>
									<td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc']?></b></td>
									<td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
									<td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
									</tr>
									<tr>
									<td class="report_label_text">&nbsp;Party Type</td>
									<td class="report_label_text">&nbsp;:&nbsp;<b><?php echo strtoupper($params['payee_type'])?></b></td>
									<td class="report_label_text">&nbsp;</td>
									<td class="report_label_text">&nbsp;</td>
									</tr>
									<tr>
									<td height="20" class="report_label_text">&nbsp;Party Name</td>
									<td height="20" class="report_label_text">&nbsp;:&nbsp;<b><?php echo strtoupper($params['payee_name'])?></b></td>
									<td height="20" class="report_label_text">&nbsp;</td>
									<td height="20" class="report_label_text">&nbsp;</td>
									</tr>
								</table>
							</td>    
						</tr>
						<tr class="fs-14">
							<th height="18" width="10%" align="left"  class="py-3 px-2">&nbsp;Date</th>
							<th height="18" width="04%" align="left"  class="py-3 px-2">&nbsp;DB</th>
							<th height="18" width="04%" align="left"  class="py-3 px-2">&nbsp;Typ</th>
							<th height="18" width="10%" align="left"  class="py-3 px-2">&nbsp;Doc#</th>
							<th height="18" width="10%" align="left"  class="py-3 px-2">&nbsp;Chq No</th>
							<th height="18" width="10%" align="left"  class="py-3 px-2">&nbsp;Chq Date</th>
							<th height="18" width="15%" align="right" class="py-3 px-2">Gross&nbsp;</th>
							<th height="18" width="15%" align="right" class="py-3 px-2">TDS&nbsp;</th>
							<th height="18" width="15%" align="right" class="py-3 px-2">Net&nbsp;</th>
						</tr>
									
			<?php
						$ppayind = 'Y';
						$lineno = 8 ; 
					}
				
					if ($ppayind == 'Y') 
					{
			?>
									<tr class="fs-14 border-0">
										<td height="20" align="left"  class="p-2" colspan="9">&nbsp;<b><?php echo strtoupper($ppaynm) ?></b></td> 
									</tr>
			<?php
						$ppayind = 'N' ;
						$lineno  = $lineno + 1 ; 
					}
			?>
									<tr class="fs-14 border-0">
										<td height="16" align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['doc_date'],'-')?></td> 
										<td height="16" align="left"  class="p-2">&nbsp;<?php echo $report_row['daybook_code']?></td>
										<td height="16" align="left"  class="p-2">&nbsp;<?php echo $report_row['doc_type']?></a></td>
										<td height="16" align="left"  class="p-2">&nbsp;<?php echo $report_row['doc_no']?></td>
										<td height="16" align="left"  class="p-2">&nbsp;<?php echo $report_row['instrument_no']?></td>
										<td height="16" align="left"  class="p-2">&nbsp;<?php if ($report_row['instrument_dt'] != '' && $report_row['instrument_dt'] != '0000-00-00') { echo date_conv($report_row['instrument_dt'],'-') ; } else { echo '&nbsp;' ; } ?></td> 
										<td height="16" align="right" class="p-2"><?php if ($report_row['gross_amount'] > 0) { echo $report_row['gross_amount'] ; } else { echo '&nbsp;' ; } ?>&nbsp;</td>
										<td height="16" align="right" class="p-2"><?php if ($report_row['tax_amount']   > 0) { echo $report_row['tax_amount']   ; } else { echo '&nbsp;' ; } ?>&nbsp;</td>
										<td height="16" align="right" class="p-2"><?php if ($report_row['net_amount']   > 0) { echo $report_row['net_amount']   ; } else { echo '&nbsp;' ; } ?>&nbsp;</td>
									</tr>
			<?php     
					$lineno = $lineno + 1;
					$pgrsamt = $pgrsamt + $report_row['gross_amount'] ;                   
					$ptaxamt = $ptaxamt + $report_row['tax_amount'] ;                   
					$pnetamt = $pnetamt + $report_row['net_amount'] ;                   
					//
					$report_row = ($rowcnt < $report_cnt) ? $trans_qry[$rowcnt] : $report_row;  
					$rowcnt = $rowcnt + 1 ;
				}  
				$tgrsamt = $tgrsamt + $pgrsamt ;                   
				$ttaxamt = $ttaxamt + $ptaxamt ;                   
				$tnetamt = $tnetamt + $pnetamt ;                   
			?>
									<tr class="fs-14 border-0">
										<td height="20" align="right"   class="p-2" colspan="6" style="background-color: #bee9f7;"><b> Total</b>&nbsp;</td>
										<td height="20" align="right"  class="p-2" style="background-color: #bee9f7;"><b><?php if ($pgrsamt > 0) { echo number_format($pgrsamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
										<td height="20" align="right"  class="p-2" style="background-color: #bee9f7;"><b><?php if ($ptaxamt > 0) { echo number_format($ptaxamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
										<td height="20" align="right"  class="p-2" style="background-color: #bee9f7;"><b><?php if ($pnetamt > 0) { echo number_format($pnetamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
									</tr>
			<?php
					$lineno = $lineno + 1;
					if ($maxline - $lineno < 2) { $lineno = $maxline ; }
				}
			?>                   
									<tr class="fs-14 border-0">
										<td colspan="9">&nbsp;</td>
									</tr>
									<tr class="fs-14 border-0">
										<td height="18" align="right" class="p-2 border-0" colspan="6" style="background-color: #fdfcc6;">&nbsp;<b>GRAND TOTAL</b>&nbsp;</td>
										<td height="18" align="right"  class="p-2 border-0" style="background-color: #fdfcc6;"><b><?php if ($tgrsamt > 0) { echo number_format($tgrsamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
										<td height="18" align="right"  class="p-2 border-0" style="background-color: #fdfcc6;"><b><?php if ($ttaxamt > 0) { echo number_format($ttaxamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
										<td height="18" align="right"  class="report_detail_tb" style="background-color: #fdfcc6;"><b><?php if ($tnetamt > 0) { echo number_format($tnetamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
									</tr>
							</table>
							</td>
						</tr>
					</table> 
	</main>
<?php } ?>
<?= $this->endSection() ?>