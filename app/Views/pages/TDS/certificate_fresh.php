<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

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
		<h1>TDS Certificate-Payable (Fresh) </h1>
		</div>

		<form action="" method="post">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
                    <div class="frms-sec-insde d-block float-start col-md-4 pe-2 mb-4">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Certificate Date</label>
                        <input type="text" class="form-control float-start set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="tds_cert_date" value="<?= $data['ason_date'] ?>" />
			            <input type="hidden" name="curr_date" value="<?= $data['ason_date'] ?>">
                    </div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
						<select class="form-select cstm-inpt" name="branch_code" id="branchCode" onchange="cleanData('signByName')">
						<?php foreach($data['branches'] as $branch) { ?>
						<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
						<?php } ?>
						</select>
					</div>
                    <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Financial Year</label>
						<select class="form-select cstm-inpt" name="fin_year" id="finYear">
						<?php foreach($data['finyr_qry'] as $branch) { ?>
						<option value="<?= $branch['fin_year'] ?>"><?= $branch['fin_year'] ?></option>
        		<!-- <option value="<?php // echo $finyr_row[fin_year]?>" <?php // if($global_curr_finyear == $finyr_row[fin_year]) { echo 'selected' ; }?>><?php // echo $finyr_row[fin_year]?></option> -->
						<?php } ?>
						</select>
					</div>
					
					<div class="col-md-2 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Quarter No <strong class="text-danger">*</strong></label>
						<select class="form-select" name="quarter_no" id="quarterNo" onchange="myqtrno(this)" required>
                        <option value="">--Select--</option>
                        <option value="1">First</option>
                        <option value="2">Second</option>
                        <option value="3">Third</option>
                        <option value="4">Fourth</option>
						</select>
					</div>
					<div class="col-md-2 float-start px-2 mb-3 position-relative">
						<label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
						<input type="text" class="form-control" name="start_date" id="startDate" readonly/>
                        <!-- <i title="View" class="fa-solid fa-binoculars icn-vw"></i> -->
					</div>
					<div class="col-md-2 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
						<input type="text" class="form-control" name="end_date" id="endDate" readonly/>
					</div>
					<div class="col-md-2 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Return No</label>
						<input type="text" class="form-control" name="tds_return_no" id="tdsReturnNo"/>
					</div>

					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Payee Type <strong class="text-danger">*</strong></label>
						<select class="form-select" name="cert_status" id="certStatus" onchange="cleanData('payeeCode')" required>
                        <option value="">--Select--</option>
                        <option value="S">Supplier</option>
                        <option value="E">Employee</option>
                        <option value="C">Counsel</option>
                        <option value="A">Arbitrator</option>
                        <option value="T">Stenographer</option>
                        <option value="O">Others</option>
						</select>
					</div>
					<div class="col-md-4 float-start px-2 mb-3 position-relative">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Payee Code</label>
						<input type="text" class="form-control" name="payee_code" id="payeeCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeeName'], ['payee_payer_name'], 'payee_code', 'payee_payer_type=@certStatus')"/>
						<i class="fa-solid fa-binoculars icn-vw" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['payee_help_id'] ?>&payee_payer_type=@certStatus', 'payeeCode', ['payeeName'], ['payee_payer_name'], 'payee_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
					</div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Payee Name</label>
						<input type="text" class="form-control" name="payee_name" id="payeeName" readonly/>
					</div>

                    <div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Cert Date</label>
						<input type="text" class="form-control" name="tds_cert_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)"/>
						<input type="hidden" class="form-control" name="curr_date" value="<?= date('d-m-Y') ?>"/>
					</div>
                    <div class="col-md-4 float-start px-2 mb-3 position-relative">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Signed By</label>
						<input type="text" class="form-control" name="sign_by_name" id="signByName" oninput="this.value = this.value.toUpperCase()" readonly/>
						<input type="hidden" class="form-control" name="sign_by_row" id="signByRow" />
						<i title="View" class="fa-solid fa-binoculars icn-vw" onclick="showData('signatory_name', 'display_id=<?= $displayId['signed_help_id'] ?>&brch_code=@branchCode', 'signByName', ['signByRow', 'signByDesg'], ['row_no', 'signatory_desg'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
					</div>
                    <div class="col-md-4 float-start px-2 mb-3 position-relative">
						<label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
						<input type="text" class="form-control" name="sign_by_desg" id="signByDesg" readonly/>
					</div>
                    <div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">View / Print</label>
						<select class="form-select" name="view_print_ind">
                        <option value="V">View</option>
        		        <option value="P">Print</option>
						</select>
					</div>
			</div>
				<button type="button" class="btn btn-primary cstmBtn mt-3">Proceed</button>
				<button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
		</form>
	</main>

<?php } else { ?>
	<script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>

	<div class="tbl-sec d-inline-block w-100 p-3 position-relative">
			<div class="position-absolute btndv">
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a>
				<a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
			</div>
		<?php
			$maxline = 60 ;
			$rowcnt     = 1 ;
			$report_row = isset($tdscert_qry[$rowcnt-1]) ? $tdscert_qry[$rowcnt-1] : '' ;  
			$report_cnt = $params['tdscert_cnt'] ;
			while ($rowcnt <= $report_cnt)
			{
			$lineno  = 0 ;
			$pageno  = 0 ;
			$ttdsamt = 0 ; 
			$tscgamt = 0 ; 
			$tcesamt = 0 ; 
			$ttaxamt = 0 ;
			$rowno   = 0 ;
			$ppayee_type = $report_row['payee_type'] ;
			$ppayee_code = $report_row['payee_code'] ;
			$ppayee_name = $report_row['payee_name'] ;
			if ($report_row['payee_city'] != '') 
			{ 
				if($report_row['payee_pin']  != '') { $payee_citypin = $report_row['payee_city'].'-'.$report_row['payee_pin'] ; } else { $payee_citypin = $report_row['payee_city'] ; } 
			} 	 
			else
			{ 
				if($report_row['payee_pin']  != '') { $payee_citypin = 'PIN - '.$report_row['payee_pin'] ; } else { $payee_citypin = '' ; } 
			} 	 
			//
			while ($ppayee_type == $report_row['payee_type'] && $ppayee_code == $report_row['payee_code'] && $ppayee_name == $report_row['payee_name'] && $rowcnt <= $report_cnt)
			{
				if ($lineno == 0 || $lineno > $maxline)
				{
					if($lineno > $maxline)
					{ 
		?>
						</table>
						<BR CLASS="pageEnd">
		<?php
					}
					$pageno = $pageno + 1 ; 
		?>
					<table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td width="35%">&nbsp;</td>
						<td width="30%">&nbsp;</td>
						<td width="35%">&nbsp;</td>
					</tr>
					<tr>
						<td align="left">&nbsp;</td>
						<td align="center" class="report_label_text"><font size="2"><b>FORM NO. 16A</b></font></td>
						<td align="center"  class="report_label_text">TDS Certificate No :&nbsp;<?php echo $report_row['tds_cert_no']?>&nbsp;</td>
					</tr>
					<tr>
						<td align="left">&nbsp;</td>
						<td align="center" class="report_label_text"><font size="2"><b>[ See rule 31(1)(b) ]</b></font></td>
						<td align="right"  class="report_label_text">&nbsp;(<?php echo $pageno?>)&nbsp;</td>
					</tr>
					<tr>
						<td height="20" colspan="3" align="center" class="report_label_text"><b>CERTIFICATE OF DEDUCTION OF TAX AT SOURCE UNDER SECTION 203 OF THE INCOME -TAX ACT, 1961</b></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3" align="left" class="report_label_text" style="text-align:justify"><font size="2">
							For Interest on securities, dividends; interest other than 'interest on securities';
							winnings from lottery or crossword puzzle; winnings from horse race; payment to contractors 
							and sub-contractors; insurance commission; payment to non-resident sportsmen/sports 
							associations; payments in respect of deposits under National Savings Scheme; payments 
							on account of repurchase of units by Mutual Fund or Unit Trust of India; commission, 
							remuneration or prize on sale of lottery tickets; commission or brokerage; rent; fees 
							for professional or technical services; royalty and any sum under section 28(va); 
							income in respect of units; payment of compensation on acquisition of certain immovable 
							property; other sums under section 195; income in respect of units of non-residents 
							referred to in section 196A; income from units referred to in section 196B; income from 
							foreign currency bonds or shares of an Indian Company referred to in section 196C; income 
							of Foreign Institutional Investors from securities referred to in section 196D</font>
						</td>
					</tr>
					</table>
					<table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td class="report_detail_rtb"  width="36%" style="vertical-align:top">Name and Address of the person deducting tax</td>
						<td class="report_detail_rtb"  colspan="2" style="vertical-align:top">Acknowledgement Nos of all Quarterly Statements of TDS under sub-section(3) of section 200 as provided by TIN Facilitation Center or NSDL web-site</td>
						<td class="report_detail_tb"   width="35%" style="vertical-align:top">&nbsp;Name and address of the person to whom &nbsp;payment made or in whose account it is &nbsp;credited</td>
					</tr>
					<tr>
						<td height="20" class="report_detail_right" width="36%" style="vertical-align:top" align="left"  ><?php echo $params['company_name'] ?></td>
						<td height="20" class="report_detail_rb"    width="9%" style="vertical-align:top" align="center">&nbsp;<?php if($params['quarter_no'] =='1') { echo 'Q1' ;} ?></td>
						<td height="20" class="report_detail_rb"    width="20%" style="vertical-align:top" align="center">&nbsp;<?php if($params['quarter_no'] =='1') { echo $report_row['tds_return_no'] ;} ?></td>
						<td height="20" class="report_detail_none"  width="35%" style="vertical-align:top">&nbsp;<?php echo strtoupper($report_row['payee_name'])?></td>
					</tr>
					<tr>
						<td height="20" class="report_detail_right" width="36%" style="vertical-align:top" align="left"  >PARITOSH SINHA</td>
						<td height="20" class="report_detail_rb"    width="9%" style="vertical-align:top" align="center">&nbsp;<?php if($params['quarter_no']=='2') { echo 'Q2' ;} ?></td>
						<td height="20" class="report_detail_rb"    width="20%" style="vertical-align:top" align="center">&nbsp;<?php if($params['quarter_no']=='2') { echo $report_row['tds_return_no'] ;} ?></td>
						<td height="20" class="report_detail_none"  width="35%" style="vertical-align:top">&nbsp;<?php echo $report_row['payee_adr1']?></td>
					</tr>
					<tr>
						<td height="20" class="report_detail_right" width="36%" style="vertical-align:top" align="left"  ><?php echo $branch_addr1 ?></td>
						<td height="20" class="report_detail_rb"    width="9%" style="vertical-align:top" align="center">&nbsp;<?php if($params['quarter_no']=='3') { echo 'Q3' ;} ?></td>
						<td height="20" class="report_detail_rb"    width="20%" style="vertical-align:top" align="center">&nbsp;<?php if($params['quarter_no']=='3') { echo $report_row['tds_return_no'] ;} ?></td>
						<td height="20" class="report_detail_none"  width="35%" style="vertical-align:top">&nbsp;<?php echo $report_row['payee_adr2']?></td>
					</tr>
					<tr>
						<td height="20" class="report_detail_right" width="36%" style="vertical-align:top" align="left"  ><?php echo $branch_citypin ?></td>
						<td height="20" class="report_detail_rb"    width="9%" style="vertical-align:top" align="center">&nbsp;<?php if($params['quarter_no']=='4') { echo 'Q4' ;} ?></td>
						<td height="20" class="report_detail_rb"    width="20%" style="vertical-align:top" align="center">&nbsp;<?php if($params['quarter_no']=='4') { echo $report_row['tds_return_no'] ;} ?></td>
						<td height="20" class="report_detail_none"  width="35%" style="vertical-align:top">&nbsp;<?php echo $report_row['payee_adr3']?></td>
					</tr>
					<tr>
						<td height="20" class="report_detail_right" width="36%" style="vertical-align:top" align="left"  >&nbsp;</td>
						<td height="20" class="report_detail_rb"    width="9%" style="vertical-align:top" align="center">&nbsp;</td>
						<td height="20" class="report_detail_rb"    width="20%" style="vertical-align:top" align="center">&nbsp;</td>
						<td height="20" class="report_detail_none"  width="35%" style="vertical-align:top">&nbsp;<?php echo $report_row['payee_adr4']?></td>
					</tr>
					<tr>
						<td height="20" class="report_detail_right"  width="36%" style="vertical-align:top" align="left"  >&nbsp;</td>
						<td height="20" class="report_detail_rb"     width="9%" style="vertical-align:top" align="center">&nbsp;</td>
						<td height="20" class="report_detail_rb"     width="20%" style="vertical-align:top" align="center">&nbsp;</td>
						<td height="20" class="report_detail_none"   width="35%" style="vertical-align:top">&nbsp;<?php echo $payee_citypin?></td>
					</tr>
					<tr>
						<td height="20" class="report_detail_rb"     width="36%" style="vertical-align:top" align="left"  >&nbsp;</td>
						<td height="20" class="report_detail_rb"     width="9%" style="vertical-align:top" align="center">&nbsp;</td>
						<td height="20" class="report_detail_rb"     width="20%" style="vertical-align:top" align="center">&nbsp;</td>
						<td height="20" class="report_detail_bottom" width="35%" style="vertical-align:top">&nbsp;</td>
					</tr>
					<tr>
						<td height="20" class="report_detail_right"  width="36%" style="vertical-align:top" align="left"  >Tax Deduction A/c No of Deductor</td>
						<td height="20" class="report_detail_right"  colspan="2" style="vertical-align:top" align="center">Nature of Payment</td>
						<td height="20" class="report_detail_none"   width="35%" style="vertical-align:top">&nbsp;PAN No of the Payee</td>
					</tr>
					<tr>
						<td height="20" class="report_detail_right"  width="36%" style="vertical-align:top" align="left"  ><?php echo $params['branch_tanno'] ?></td>
						<td height="20" class="report_detail_right"  colspan="2" style="vertical-align:top" align="center"><?php echo $report_row['nature_of_service']?></td>
						<td height="20" class="report_detail_none"   width="35%" style="vertical-align:top">&nbsp;<?php echo $report_row['payee_panno'] ?></td>
					</tr>
					<tr>
						<td height="20" class="report_detail_rt"     width="36%" style="vertical-align:top" align="left"  >PAN No of the Deductor</td>
						<td height="20" class="report_detail_rt"     colspan="2" style="vertical-align:top" align="center">&nbsp;</td>
						<td height="20" class="report_detail_top"    width="35%" style="vertical-align:top">&nbsp;For the period</td>
					</tr>
					<tr>
						<td height="20" class="report_detail_rb"     width="36%" style="vertical-align:top" align="left"  ><?php echo $params['branch_panno'] ?></td>
						<td height="20" class="report_detail_rb"     colspan="2" style="vertical-align:top" align="center">&nbsp;</td>
						<td height="20" class="report_detail_bottom" width="35%" style="vertical-align:top">&nbsp;<?php echo $params['start_date'] ?> - <?php echo $params['end_date'] ?></td>
					</tr>
					</table>
					<table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td height="20" class="report_detail_text" colspan="11" align="center">DETAILS OF PAYAMENT TAX DEDUCTION AND DEPOSIT OF TAX INTO CENTRAL GOVERNMENT ACCOUNT</td>
					</tr>
					<tr>
						<td height="20" class="report_detail_text" colspan="11" align="center">(The Deductor is to provide transaction-wise details of tax deducted and deposited)</td>
					</tr>
					<tr>
						<td width="03%" align="left" class="report_detail_rt">Sr</td>
						<td width="11%" align="left" class="report_detail_rt">Amount</td>
						<td width="11%" align="left" class="report_detail_rt">Date of</td>
						<td width="11%" align="left" class="report_detail_rt">TDS</td>
						<td width="09%" align="left" class="report_detail_rt">Surcharge</td>
						<td width="09%" align="left" class="report_detail_rt">Education</td>
						<td width="11%" align="left" class="report_detail_rt">Total Tax</td>
						<td width="07%" align="left" class="report_detail_rt">Cheque/</td>
						<td width="10%" align="left" class="report_detail_rt">BSR Code</td>
						<td width="09%" align="left" class="report_detail_rt">Date on</td>
						<td width="09%" align="left" class="report_detail_top">Transfer</td>
					</tr>
					<tr>
						<td align="left" class="report_detail_right">No</td>
						<td align="left" class="report_detail_right">Paid /</td>
						<td align="left" class="report_detail_right">Payment/</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">Cess</td>
						<td align="left" class="report_detail_right">Deposited</td>
						<td align="left" class="report_detail_right">DD No</td>
						<td align="left" class="report_detail_right">Of Bank</td>
						<td align="left" class="report_detail_right">which Tax</td>
						<td align="left" class="report_detail_none" >Voucher/</td>
					</tr>
					<tr>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">Credited</td>
						<td align="left" class="report_detail_right">Credit</td>
						<td align="left" class="report_detail_right">(Rs)</td>
						<td align="left" class="report_detail_right">(Rs)</td>
						<td align="left" class="report_detail_right">(Rs)</td>
						<td align="left" class="report_detail_right">(Rs)</td>
						<td align="left" class="report_detail_right">(if any)</td>
						<td align="left" class="report_detail_right">Branch</td>
						<td align="left" class="report_detail_right">deposited</td>
						<td align="left" class="report_detail_none" >Challan</td>
					</tr>
					<tr>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">&nbsp;</td>
						<td align="left" class="report_detail_right">(dd/mm/yyyy)</td>
						<td align="left" class="report_detail_none" >Identification</td>
					</tr>
					<tr>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_rb">&nbsp;</td>
						<td align="left" class="report_detail_bottom">No</td>
					</tr>
		<?php 
					$lineno = 33 ; 
				}

				//-----
				$rowno    = $rowno + 1 ;
				$taxper   = $report_row['tax_percent'] ;
			//	 $tdsamt   = round(($report_row[gross_amount] * intval($taxper)/100)+0.49,0) ;
				$tdsamt   = round(($report_row['gross_amount'] * intval($taxper)/100)+0.00,0) ;		 
				$scgamt   = 0 ; 
				$cesamt   = $report_row['tax_amount'] - $tdsamt ;
		?>
					<tr>
						<td align="left"  class="report_detail_right"><?php echo $rowno ?></td>
		<!--            <td align="right" class="report_detail_right"><?php echo number_format($report_row['gross_amount']+$report_row['backlog_counsel_fee'],2,'.','') ?>&nbsp;</td>
		-->             <td align="right" class="report_detail_right"><?php echo number_format($report_row['gross_amount'],2,'.','') ?>&nbsp;</td>
						<td align="left"  class="report_detail_right"><?php echo date_conv($report_row['doc_date'],'-') ?></td>
						<td align="right" class="report_detail_right"><?php if ($tdsamt > 0) { echo number_format($tdsamt,2,'.','') ; } else { '&nbsp;' ; } ?>&nbsp;</td>
						<td align="right" class="report_detail_right"><?php if ($scgamt > 0) { echo number_format($scgamt,2,'.','') ; } else { '&nbsp;' ; } ?>&nbsp;</td>
						<td align="right" class="report_detail_right"><?php if ($cesamt > 0) { echo number_format($cesamt,2,'.','') ; } else { '&nbsp;' ; } ?>&nbsp;</td>
						<td align="right" class="report_detail_right"><?php echo $report_row['tax_amount'] ?>&nbsp;</td>
						<td align="left"  class="report_detail_right"><?php echo $report_row['tds_cheque_no'] ?>&nbsp;</td>
						<td align="center"  class="report_detail_right"><?php echo $report_row['tds_bank_bsr_code'] ?>&nbsp;</td>
						<td align="center"  class="report_detail_right"><?php echo date_conv($report_row['tds_deposit_date'],'-') ?>&nbsp;</td>
						<td align="left"  class="report_detail_none" ><?php echo $report_row['tds_challan_no'] ?></td>
					</tr>
		<?php
				$lineno  = $lineno  + 1 ; 
				$ttdsamt = $ttdsamt + $tdsamt ; 
				$tscgamt = $tscgamt + $scgamt ; 
				$tcesamt = $tcesamt + $cesamt ; 
				$ttaxamt = $ttaxamt + $report_row['tax_amount'] ; 
				//
				$report_row = ($rowcnt < $report_cnt) ? $tdscert_qry[$rowcnt] : $report_row;
				$rowcnt = $rowcnt + 1 ;
			}  
			//
			$actual_rs     = number_format($ttaxamt,2,'.','');
			$length        = strlen($actual_rs);
			$pos1          = $length - 3;
			$rs            = substr($actual_rs,0,$pos1);
			$ps            = substr($actual_rs,$pos1+1);
			$rs1_words     = int_to_words($rs);
			if ($ps > 0.00)
			{
				$ps_words  = 'and paise '.int_to_words($ps). ' only)';
			}
			else
			{
				$ps_words  = ' only)';
			}
			$rs_words = '(Rupees ' . $rs1_words.' '.$ps_words;
		?>
					<tr>
						<td align="right" class="report_detail_rtb" colspan="3"><b>TOTAL</b>&nbsp;</td>
						<td align="right" class="report_detail_rtb"><?php if ($ttdsamt > 0) { echo number_format($ttdsamt,2,'.','') ; } else { '&nbsp;' ; } ?>&nbsp;</td>
						<td align="right" class="report_detail_rtb"><?php if ($tscgamt > 0) { echo number_format($tscgamt,2,'.','') ; } else { '&nbsp;' ; } ?>&nbsp;</td>
						<td align="right" class="report_detail_rtb"><?php if ($tcesamt > 0) { echo number_format($tcesamt,2,'.','') ; } else { '&nbsp;' ; } ?>&nbsp;</td>
						<td align="right" class="report_detail_rtb"><?php if ($ttaxamt > 0) { echo number_format($ttaxamt,2,'.','') ; } else { '&nbsp;' ; } ?>&nbsp;</td>
						<td align="left"  class="report_detail_tb" colspan="4">&nbsp;</td>
					</tr>
					</table>
					<table width="750" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td width="10%">&nbsp;</td>
						<td width="35%">&nbsp;</td>
						<td width="10%">&nbsp;</td>
						<td width="55%">&nbsp;</td>
					</tr>
					<tr>
						<td height="20" colspan="4" class="report_label_text" style="text-align:justify">Certified that a sum of Rs.&nbsp;<?=$actual_rs?>&nbsp;<?=$rs_words ?>&nbsp;has been deducted at source and paid to the credit of the Central Government as per details given above.</td>
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
					<tr>
						<td colspan="3">&nbsp;</td>
						<td><hr size="1" noshade></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
						<td class="report_label_text" align="center">(Signature of the person responsible for deduction of tax)</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td class="report_label_text">Place</td>
						<td class="report_label_text">&nbsp;:&nbsp;KOLKATA</td>
						<td class="report_label_text">Full Name</td>
						<td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['sign_by_name'] ?></td>
					</tr> 
					<tr>
						<td class="report_label_text">Date</td>
						<td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['tds_cert_date'] ?></td>
						<td class="report_label_text">Designation</td>
						<td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['sign_by_desg'] ?></td>
					</tr> 
					</table>	
					<?php } ?>
	</div>
<?php } ?>
<!-- End #main -->


<?= $this->endSection() ?>