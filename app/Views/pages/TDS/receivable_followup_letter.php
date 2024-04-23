<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if (!isset($tdscert_qry)) { ?>  
 
	<main id="main" class="main">

	<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
			<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    	</div>
  	</div>
<?php endif; ?>

		<div class="pagetitle col-md-12 float-start border-bottom pb-1">
		<h1>TDS Follow-up Letter (Receivable) </h1>
		</div>

		<form action="" method="post">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
						<select class="form-select cstm-inpt" name="branch_code">
						<?php foreach($data['branches'] as $branch) { ?>
						<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
						<?php } ?>
						</select>
					</div>
                    <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Financial Year</label>
						<select class="form-select cstm-inpt" name="fin_year">
						<?php foreach($data['finyr_qry'] as $branch) { ?>
						<option value="<?= $branch['fin_year'] ?>"><?= $branch['fin_year'] ?></option>
        		        <!-- <option value="<?php // echo $finyr_row[fin_year]?>" <?php // if($global_curr_finyear == $finyr_row[fin_year]) { echo 'selected' ; }?>><?php // echo $finyr_row[fin_year]?></option> -->
						<?php } ?>
						</select>
					</div>
					
					<div class="col-md-2 float-start px-2 mb-3 position-relative">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="payee_code" id="payeeCode" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'payee_payer_code', ['payeeName'], ['client_name'], 'client_code')" required />
			            <input type="hidden" name="payee_type"  value="<?= $data['payee_payer_type'] ?>">
                        <i title="View" class="fa-solid fa-binoculars icn-vw"  onclick="showData('payee_payer_code', 'display_id=<?= $displayId['client_help_id'] ?>&payee_payer_type=C', 'payeeCode', ['payeeName'], ['payee_payer_name'], 'client_code')" data-toggle="modal" data-target="#lookup"></i>
					</div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
						<input type="text" class="form-control" name="payee_name" id="payeeName" readonly/>
					</div>

                    <div class="col-md-4 float-start px-2 mb-3 position-relative">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client Attention <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control readonly" name="attn_code" id="attnCode" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'attention_code', ['attnName', 'addrCode', 'addrName'], ['attention_name', 'address_code', 'address_line_1'], 'attention_code')" required/>
                        <i title="View" class="fa-solid fa-binoculars icn-vw" onclick="showData('attention_code', 'display_id=<?= $displayId['attention_help_id'] ?>&payee_payer_code=@payeeCode', 'attnCode', ['attnName', 'addrCode', 'addrName'], ['attention_name', 'address_code', 'address_line_1'], 'attention_code')" data-toggle="modal" data-target="#lookup"></i>
					</div>
					<div class="col-md-8 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
						<input type="text" class="form-control" name="attn_name" id="attnName" readonly/>
					</div>
 
                    <div class="col-md-4 float-start px-2 mb-3 position-relative">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client Address</label>
						<input type="text" class="form-control" name="addr_code" id="addrCode" readonly/>
					</div> 
					<div class="col-md-8 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
						<input type="text" class="form-control" name="addr_name" id="addrName" readonly/>
					</div>
					<div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
						<select class="form-select" name="output_type" tabindex="12" required>
						<option value="">--Select--</option>
						<option value="Report">View Report</option>
						<option value="Pdf" >Download PDF</option>
						<!-- <option value="Excel" >Download Excel</option> -->
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

				$letter_no   = 'TD/'.str_pad(get_last_doc_no($params['fin_year'],$params['branch_code'],'TD','0','L'),5,'0',STR_PAD_LEFT) ;
				$letter_date = $params['date'] ;
				
				$maxline = 70 ;
				$lineno  = 0 ;
				$pageno  = 0 ;
				$tgramt  = 0; 
				$ttxamt  = 0; 
				$tntamt  = 0;
				$rowcnt  = 1 ;
				$report_row = isset($tdscert_qry[$rowcnt-1]) ? $tdscert_qry[$rowcnt-1] : '' ; 
				$report_cnt = $params['tdscert_cnt'] ;
				while ($rowcnt <= $report_cnt)
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
			<div class="w950 m-0 m-auto" style="width:950px;">
					<table class="table border-0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="border-0 pb-0" colspan="6">    
								<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
									
									<tr>
									<td class="report_label_text" colspan="4" align="center"><font size="+2"><b><?php echo strtoupper($params['comp_name'])?></b></font></td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr1']?></td>
									</tr>
									<?php $lineno = 2 ; ?>
									<?php if($params['branch_addr2'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr2']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['branch_citypin'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_citypin']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['branch_telfax'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_telfax']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['branch_email'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_email']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									</tr>
									<tr>
									<td class="report_label_text">Ref No</td>
									<td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $letter_no?></b></td>
									<td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
									<td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
									</tr>
									<tr>
									<td class="report_label_text">Date</td>
									<td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $letter_date?></b></td>
									<td class="report_label_text" align="right">&nbsp;</td>
									<td class="report_label_text">&nbsp;</td>
									</tr>
									<tr>
									<td colspan="4"><hr size="1" noshade></td>
									</tr>
									<?php $lineno = $lineno + 4 ;  ?> 
									<?php if($pageno == 1) 
									{ 
									?>
									<tr>
										<td class="report_label_text" colspan="4" align="left"><?php echo strtoupper($params['payee_name']) ?></td>
									</tr>
									<?php $lineno = $lineno + 1 ;   ?>
									<?php if($params['client_addr1'] != '') { ?> 
									<tr>
										<td class="report_label_text" colspan="4" align="left"><?php echo $params['client_addr1']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['client_addr2'] != '') { ?> 
									<tr>
										<td class="report_label_text" colspan="4" align="left"><?php echo $params['client_addr2']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['client_addr3'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="left"><?php echo $params['client_addr3']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['client_addr4'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="left"><?php echo $params['client_addr4']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['client_addr5'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="left"><?php echo $params['client_addr5']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<tr>
									<td class="report_label_text" colspan="4" align="left">&nbsp;</td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="left">Attn : <?php echo strtoupper($params['attn_name'])?></td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="left">&nbsp;</td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="center"><b><u>Re : NON RECEIPT OF TDS FORM NO 16A</u></b></td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="left">&nbsp;</td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="left">Dear Sirs,</td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="left">&nbsp;</td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="left">We do not appear to have received from you Form No 16A (Certification of Deduction of Tax at Source under Section 203 of the Income Tax Act, 1961) for the tax deducted by you while making following payments : </td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="left">&nbsp;</td>
									</tr>
									<?php $lineno = $lineno + 10 ; ?>
									<?php  
									} 
									?> 
								</table>
							</td>    
						</tr>
						<!-- <tr class="fs-14">
							<td colspan="4" class="grid_header">
								<table width="98%" border="0" align="left" cellspacing="0" cellpadding="0"> -->
									<tr class="fs-14">
										<th width="08%" align="left"  class="px-2 py-2">&nbsp;Chq No</th>
										<th width="15%" align="left"  class="px-2 py-2">&nbsp;Chq Dt</th>
										<th width="37%" align="left"  class="px-2 py-2">&nbsp;Bank</th>
										<th width="12%" align="right" class="px-2 py-2">Total&nbsp;</th>
										<th width="13%" align="right" class="px-2 py-2">TDS&nbsp;</th>
										<th width="13%" align="right" class="px-2 py-2">Net&nbsp;</th>
									</tr>
			<?php
						$lineno = $lineno + 1 ;
					}
			?>
									<tr class="fs-14 border-0">
									<td align="left"  class="p-2">&nbsp;<?php echo $report_row['instrument_no']?></td>
									<td align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['instrument_dt'],'-')?></td> 
									<td align="left"  class="p-2">&nbsp;<?php echo $report_row['bank_name']?></a></td>
									<td align="left" class="p-2"><?php echo $report_row['gross_amount'] ?>&nbsp;</td>
									<td align="left" class="p-2"><?php echo $report_row['tax_amount'] ?>&nbsp;</td>
									<td align="left" class="p-2"><?php echo $report_row['net_amount']?>&nbsp;</td>
									</tr>
			<?php     
					$lineno = $lineno + 1;
					$tgramt = $tgramt + $report_row['gross_amount'] ;                   
					$ttxamt = $ttxamt + $report_row['tax_amount'] ;                   
					$tntamt = $tntamt + $report_row['net_amount'] ;                   
					//
					$report_row = ($rowcnt < $report_cnt) ? $tdscert_qry[$rowcnt] : $report_row;
					$rowcnt = $rowcnt + 1 ;
				}  
			?>                   
									<tr>
									<td align="center" colspan="3" class="p-2"  style="background-color: #e2e6506e;"><b>TOTAL</b>&nbsp;</td>
									<td align="left" class="p-2"  style="background-color: #e2e6506e;"><b><?php echo number_format($tgramt,2,'.','') ?></b>&nbsp;</td>
									<td align="left" class="p-2"  style="background-color: #e2e6506e;"><b><?php echo number_format($ttxamt,2,'.','') ?></b>&nbsp;</td>
									<td align="left" class="p-2"  style="background-color: #e2e6506e;"><b><?php echo number_format($tntamt,2,'.','') ?></b>&nbsp;</td>
									</tr>
								<!-- </table> -->
							</td>
						</tr>
					<?php if (($maxline-$lineno) < 18) { ?>
					</table>
					</div> 
					<BR CLASS="pageEnd"> 
					<?php $pageno = $pageno + 1 ; ?>
					<table class="table border-0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="border-0 pb-0">    
								<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
									<tr>
									<td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper($params['comp_name'])?></b></td>
									</tr>
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr1']?></td>
									</tr>
									<?php $lineno = 2 ; ?>
									<?php if($params['branch_addr2'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr2']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['branch_addr3'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr3']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['branch_addr4'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr4']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['branch_addr5'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr5']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['branch_addr6'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr6']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<?php if($params['branch_addr7'] != '') { ?> 
									<tr>
									<td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr7']?></td>
									</tr>
									<?php $lineno = $lineno + 1 ; } ?>
									<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									</tr>
									<tr>
									<td class="report_label_text">&nbsp;Ref No</td>
									<td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $letter_no?></b></td>
									<td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
									<td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
									</tr>
									<tr>
									<td class="report_label_text">&nbsp;Date</td>
									<td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $letter_date?></b></td>
									<td class="report_label_text" align="right">&nbsp;</td>
									<td class="report_label_text">&nbsp;</td>
									</tr>
									<tr>
									<td colspan="4"><hr size="1" noshade></td>
									</tr>
									<tr>
									<td colspan="4">&nbsp;</td>
									</tr>
									<tr>
									<td colspan="4">&nbsp;</td>
									</tr>
									<tr>
									<td colspan="4">&nbsp;</td>
									</tr>
									<?php $lineno = $lineno + 7 ;  ?> 
								</table>
							</td>    
						</tr>
					<?php } ?>
						<tr>
							<td class="report_label_text border-0" colspan="4" align="left">&nbsp;</td>
						</tr>
						<tr>
							<td class=" border-0" colspan="4" align="left">Please expedite sending the same. </td>
						</tr>
						
						<tr>
							<td class=" border-0" colspan="4" align="left">Please note that pending TDS Certificates relate to the assessment year 2017-2018 can only be made use of, if received by 15th August, 2017 . If the said certificates are not sent within the said date, kindly make payment of the equivalent amount, so that the bills can be finally settled.</td>
						</tr>
						
						<tr>
							<td class=" border-0" colspan="4" align="left">&nbsp;Should you need any clarification please feel free to get in touch with us.</td>
						</tr>
						
						<tr>
							<td class=" border-0" colspan="4" align="left">&nbsp;Please note that our Income Tax Permanent A/c No is <b><?php echo $params['branch_panno'] ?></b></td>
						</tr>
						
						<tr>
							<td class=" border-0" colspan="4" align="left">&nbsp;Thanking you</td>
						</tr>
						
						<tr>
							<td class=" border-0" colspan="4" align="left">&nbsp;Yours faithfully</td>
						</tr>
						<tr>
							<td class=" border-0" colspan="4" align="left">&nbsp;for <?php echo $params['company_name']?></td>
						</tr>
						
						
						<tr>
							<td class=" border-0" colspan="4" align="left">&nbsp;_________________________</td>
						</tr>
						<tr>
							<td class=" border-0" colspan="4" align="left">&nbsp;Accountant</td>
						</tr>
						<?php $lineno = $lineno + 18 ; ?>
					</table>
	</main> 
<?php } ?>
<!-- End #main -->


<?= $this->endSection() ?>