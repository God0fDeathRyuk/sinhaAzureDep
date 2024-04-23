<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($payreg_qry))) { ?>
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>Payment Register (Expense Heade-wise)</h1>
        </div><!-- End Page Title -->
        <form action="" method="post" id="paymentSummary" name="paymentSummary" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-5 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-48 float-start" name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required>
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-48 float-start" name="end_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Type  <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="payee_payer_type" id="payeePayerType" onchange="cleanData(this, 'payeePayerCode', '%&_', 'payeeCodeLookup')" required >
                                <option value="%">All</option>
                                <option value="E">Employee</option>
                                <option value="S">Supplier</option>
                                <option value="C">Counsel</option>
                                <option value="A">Arbitrator</option>
                                <option value="T">Stenographer</option>
                                <option value="O">Others</option>
                            </select>
                        </div>				
                        
                        <div class="col-md-4 float-start px-2 mb-3 position-relative">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Code</label>
                            <input type="text" class="form-control w-100 float-start" name="payee_payer_code" id="payeePayerCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeePayerName'], ['payee_payer_name'], 'payee_code_finance', 'payee_type=@payeePayerType')">
                            <i class="fa-solid fa-binoculars icn-vw d-none" aria-hidden="true" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['payee_help_id'] ?>&payee_type=@payeePayerType', 'payeePayerCode', ['payeePayerName'], ['payee_payer_name'], 'payee_code_finance')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Name</label>
                            <input type="text" class="form-control w-100 float-start" name="payee_payer_name" id="payeePayerName" readonly>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Report Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="report_type" required >
                                <option value="S">Summary</option>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type</label>
                            <select class="form-select" name="output_type" tabindex="12" required>
                                <option value="Report">View Report</option>
                                <option value="Pdf" >Download PDF</option>
                                <option value="Excel" >Download Excel</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Proceed</button>				
                        <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Reset</button>
                    </div>
                    
                </div>
                
            </div>
            </section>
        </form>

    </main><!-- End #main -->
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
                $gtotamt = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($payreg_qry[$rowcnt-1]) ? $payreg_qry[$rowcnt-1] : '' ; 
                $report_cnt = $params['payreg_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $ppayeeind = 'Y';
                $ptotamt   = 0 ;
                $ppaytype  = $report_row['payee_payer_type'] ;
                $ppaycode  = $report_row['payee_payer_code'] ;
                $ppayname  = $report_row['payee_payer_name'] ;
                while($ppaytype == $report_row['payee_payer_type'] && $ppaycode == $report_row['payee_payer_code'] && $ppayname == $report_row['payee_payer_name'] && $rowcnt <= $report_cnt)
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
                    <table width="950" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                            <td colspan="4">    
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="08%">&nbsp;</td>
                                    <td width="72%">&nbsp;</td>
                                    <td width="08%">&nbsp;</td>
                                    <td width="12%">&nbsp;</td>
                                    </tr>
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
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['start_date'] . ' TO ' . $params['end_date'] ; ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Payee Type</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['payee_type_desc'] ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr><td colspan="4">&nbsp;</td></tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th height="20" width="40%" align="left"  class="py-3 px-2">&nbsp;<b>Payee Name</b></th>
                            <th height="20" width="04%" align="left"  class="py-3 px-2">&nbsp;<b>Code</b></th>
                            <th height="20" width="44%" align="left"  class="py-3 px-2">&nbsp;<b>Description</b></th>
                            <th height="20" width="12%" align="right" class="py-3 px-2"><b>Amount</b>&nbsp;</th>
                        </tr>
                                    
            <?php
                        $lineno = 8 ;
                    }
            ?>
                                    <tr class="fs-14">
                                        <td height="18" align="left"  class="p-2">&nbsp;<?php if($ppayeeind == 'Y') { echo $report_row['payee_payer_name'] ; } ?></td>
                                        <td height="18" align="left"  class="p-2" >&nbsp;<?php echo $report_row['main_ac_code']                                 ?></td>
                                        <td height="18" align="left"  class="p-2" >&nbsp;<?php echo strtoupper($report_row['main_ac_desc'])                     ?></td>
                                        <td height="18" align="right" class="p-2" ><?php echo number_format($report_row['expense_amount'],2,'.',',')            ?>&nbsp;</td>
                                    </tr>
            <?php     
                    $ppayeeind  = 'N';
                    $ptotamt   += $report_row['expense_amount'] ;
                    $lineno    += 1.5;
                    $report_row = ($rowcnt < $report_cnt) ? $payreg_qry[$rowcnt] : $report_row; 
                    $rowcnt    += 1 ;
                }
            ?>	  
                                    <tr class="fs-14">
                                        <td height="18" colspan="3" class="p-2" align="right" style="background-color: #91d6ec6e;"><b>PAYEE TOTAL :&nbsp;</b></td>
                                        <td height="18" colspan="1" class="p-2"  align="right" style="background-color: #91d6ec6e;"><b><?php if($ptotamt>0) { echo number_format($ptotamt,2,'.',','); }?></b>&nbsp;</td>
                                    </tr>
            <?php     
                $lineno  += 1.5;
                $gtotamt += $ptotamt ;
                }
            ?>                   
                                    <tr class="fs-14">
                                        <td height="20" colspan="3" class="p-2" align="right" style="background-color: #e2e6506e;"><b>TOTAL :&nbsp;</b></td>
                                        <td height="20" colspan="1" class="p-2"  align="right" style="background-color: #e2e6506e;"><b><?php if($gtotamt>0) { echo number_format($gtotamt,2,'.',','); }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
    </main>
<?php } ?>
<script>
    function setValue(e) {
        e.preventDefault();
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.paymentSummary.start_date.value.substring(6,10)+document.paymentSummary.start_date.value.substring(3,5)+document.paymentSummary.start_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.paymentSummary.start_date.focus()}, 500) });
            return false;
        }
        else if (document.paymentSummary.end_date.value.substring(6,10)+document.paymentSummary.end_date.value.substring(3,5)+document.paymentSummary.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.paymentSummary.end_date.focus()}, 500) });
            return false;
        }
        else if (document.paymentSummary.start_date.value.substring(6,10)+document.paymentSummary.start_date.value.substring(3,5)+document.paymentSummary.start_date.value.substring(0,2)>document.paymentSummary.end_date.value.substring(6,10)+document.paymentSummary.end_date.value.substring(3,5)+document.paymentSummary.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'Period End Date must be less than Period Start Date' }).then((result) => { setTimeout(() => {document.paymentSummary.end_date.focus()}, 500) });
            return false;
        } 
        else if (document.paymentSummary.payee_payer_type.value == '') {
            
            Swal.fire({ text: 'Please select Payee Type !!!!' }).then((result) => { setTimeout(() => {document.paymentSummary.payee_payer_type.focus()}, 500) });
            return false;
        } 
        document.paymentSummary.submit();
    }
</script>
<?= $this->endSection() ?>