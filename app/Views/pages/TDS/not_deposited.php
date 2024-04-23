<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if (!isset($tds_qry)) { ?> 
 
<main id="main" class="main">
	<?php if (session()->getFlashdata('message') !== NULL) : ?>
		<div id="alertMsg">
			<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
			<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="pagetitle col-md-12 float-start border-bottom pb-1">
		<h1>TDS Not Deposited </h1>
	</div>

	<form action="" method="post">
		<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
		<div class="frms-sec d-inline-block w-100 bg-white p-3">
			<div class="frms-sec-insde d-block float-start col-md-4 pe-2 mb-4">
				<label class="d-inline-block w-100 mb-2 lbl-mn">As On Date</label>
				<input type="text" class="form-control float-start set-date" id="" placeholder="dd-mm-yyyy" name="ason_date" value="<?= $data['ason_date'] ?>" readonly />
			</div>
			<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
				<select class="form-select cstm-inpt" name="branch_code">
				<?php foreach($data['branches'] as $branch) { ?>
				<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
				<?php } ?>
				</select>
			</div>
			<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Financial Year</label>
				<select class="form-select cstm-inpt" name="fin_year">
				<?php foreach($data['finyr_qry'] as $branch) { ?>
					<option value="<?= $branch['fin_year'] ?>"> <?= $branch['fin_year'] ?> </option>
					<!-- <option value="<?php // echo $finyr_row[fin_year]?>" <?php // if($global_curr_finyear == $finyr_row[fin_year]) { echo 'selected' ; }?>><?php // echo $finyr_row[fin_year]?></option> -->
				<?php } ?>
				</select>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Payee Type <strong class="text-danger">*</strong></label>
				<select class="form-select" name="payee_type" id="payeeType" onchange="cleanData(this, 'payeeCode', '%&_', 'payeeCodeLookup')" required>
				<option value="">--Select--</option>
					<option value="%">All</option>
					<option value="C">Counsel</option>
					<option value="E">Employee</option>
					<option value="S">Supplier</option>
					<option value="O">Others</option>
				</select>
			</div>
			<div class="col-md-4 float-start px-2 mb-3 position-relative">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Payee Code</label>
				<input type="text" class="form-control" name="payee_code" id="payeeCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeeName'], ['payee_payer_name'], 'payee_code', 'payee_payer_type=@payeeType')"/>
				<i class="fa-solid fa-binoculars icn-vw d-none" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['payee_help_id'] ?>&payee_payer_type=@payeeType', 'payeeCode', ['payeeName'], ['payee_payer_name'], 'payee_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Payee Name</label>
				<input type="text" class="form-control" name="payee_name" id="payeeName" readonly/>
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
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
					<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>

			<?php
				//----- 
				$maxline = 45 ;
				$lineno  = 0 ;
				$pageno  = 0 ;
				$tgramt  = 0; 
				$ttxamt  = 0; 
				$rowcnt     = 1 ;
				$report_row = isset($tds_qry[$rowcnt-1]) ? $tds_qry[$rowcnt-1] : '' ;  
				$report_cnt = $params['tds_cnt'] ;
				while ($rowcnt <= $report_cnt)
				{
				$mgramt = 0; 
				$mtxamt = 0; 
				$pdocym = substr($report_row['doc_date'],0,4).substr($report_row['doc_date'],5,2) ;
				while ($pdocym == (substr($report_row['doc_date'],0,4).substr($report_row['doc_date'],5,2)) && $rowcnt <= $report_cnt)
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
							<td class="border-0 pb-0" colspan="7">    
								<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
									<tr>
									    <td class="report_label_text" colspan="7" align="center"><b>Sinha and Company</b></td>
									</tr>
									<tr>
									    <td class="report_label_text" colspan="7" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td>
									</tr>
									<tr>
    									<td>&nbsp;</td>
    									<td>&nbsp;</td>
    									<td>&nbsp;</td>
    									<td>&nbsp;</td>
									</tr>
									<tr>
    									<td class="report_label_text">&nbsp;Branch</td>
    									<td class="report_label_text" colspan="3">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
    									<td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
    									<td class="report_label_text fw-bold">&nbsp;:&nbsp;<?php echo $params['ason_date']?></td>
									</tr>
									<tr>
    									<td class="report_label_text">&nbsp;Year</td>
    									<td class="report_label_text" colspan="3">&nbsp;:&nbsp;<b><?php echo $params['fin_year']?></b></td>
    									<td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
    									<td class="report_label_text fw-bold">&nbsp;:&nbsp;<?php echo $pageno?></td>
									</tr>
									<tr>
    									<td class="report_label_text">&nbsp;As On</td>
    									<td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date'] ; ?></b></td>
    									<td class="report_label_text">&nbsp;</td>
    									<td class="report_label_text">&nbsp;</td>
									</tr>
									<tr>
    									<td class="report_label_text">&nbsp;Payee</td>
    									<td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['payee_type'] != '%') { echo strtoupper($params['payee_name']) ; } else { echo 'ALL' ; } ?></b></td>
    									<td class="report_label_text">&nbsp;</td>
    									<td class="report_label_text">&nbsp;</td>
									</tr>
								</table>
							</td>    
						</tr>
						<tr><td width="10%" align="left"  class="py-1">&nbsp;</td></tr>
						<tr class="fs-14">
							<th width="10%" align="left"  class="py-3 px-2">&nbsp;Date</th>
							<th width="07%" align="left"  class="py-3 px-2">&nbsp;Doc#</th>
							<th width="03%" align="left"  class="py-3 px-2">&nbsp;Type</th>
							<th width="03%" align="left"  class="py-3 px-2">&nbsp;DB</th>
							<th width="47%" align="left"  class="py-3 px-2">&nbsp;Payee</th>
							<th width="15%" align="left" class="py-3 px-2">Gross&nbsp;</th>
							<th width="15%" align="left" class="py-3 px-2">TDS&nbsp;</th>
						</tr>
						
			<?php
						$lineno = 8 ;
					}
			?>
									<tr class="fs-14 border-0">
										<td height="20" align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['doc_date'],'-')?></td> 
										<td height="20" align="left"  class="p-2">&nbsp;<?php echo $report_row['doc_no']?></td>
										<td height="20" align="left"  class="p-2">&nbsp;<?php echo $report_row['doc_type']?></a></td>
										<td height="20" align="left"  class="p-2">&nbsp;<?php echo $report_row['daybook_code']?></td>
										<td height="20" align="left"  class="p-2">&nbsp;<?php echo $report_row['payee_payer_name'].' ['.$report_row['payee_payer_code'].']';?></td>
										<td height="20" align="left" class="p-2"><?php echo $report_row['gross_amount'] ?>&nbsp;</td>
										<td height="20" align="left" class="p-2"><?php echo $report_row['tax_amount'] ?>&nbsp;</td>
									</tr>
			<?php     
					$lineno = $lineno + 1;
					$mgramt = $mgramt + $report_row['gross_amount'] ;                   
					$mtxamt = $mtxamt + $report_row['tax_amount'] ;                   
					//
					$report_row = ($rowcnt < $report_cnt) ? $tds_qry[$rowcnt] : $report_row;
					$rowcnt = $rowcnt + 1 ;
				}  
				$tgramt = $tgramt + $mgramt ;                   
				$ttxamt = $ttxamt + $mtxamt ;                   
			?>                   
									<tr class="fs-14 border-0">
									<td height="20" align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
									<td height="20" align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
									<td height="20" align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
									<td height="20" align="right" class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
									<td height="20" align="right" class="p-2" style="background-color: #e2e6506e;"><b>TOTAL</b>&nbsp;</td>
									<td height="20" align="right" class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format($mgramt,2,'.','') ?></b>&nbsp;</td>
									<td height="20" align="right" class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format($mtxamt,2,'.','') ?></b>&nbsp;</td>
									</tr>
			<?php
				$lineno = $lineno + 1;
				}
			?>
									<tr class="fs-14 border-0">
										<td height="20" align="left"  colspan="7" class="p-2">&nbsp;</td>
									</tr>
									<tr class="fs-14 border-0">
										<td height="20" colspan="5" align="center" style="background-color: #a1d1e4;" class="p-2"><b>GRAND TOTAL</b>&nbsp;</td>
										<td height="20" align="right" style="background-color: #a1d1e4;" class="p-2"><b><?php echo number_format($tgramt,2,'.','') ?></b>&nbsp;</td>
										<td height="20" align="right" style="background-color: #a1d1e4;" class="p-2"><b><?php echo number_format($ttxamt,2,'.','') ?></b>&nbsp;</td>
									</tr>
							</table>
							</td>
						</tr>
					</table> 
	</main>
<?php } ?>
<!-- End #main -->


<?= $this->endSection() ?>