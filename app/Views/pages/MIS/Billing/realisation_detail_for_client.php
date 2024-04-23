<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if(!isset($payreg_qry)) { ?>
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>Receipt Register</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="realisationDetailForClient" name="realisationDetailForClient" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-5 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-48 float-start datepicker" name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required />
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-48 float-start datepicker" name="end_date" value="<?= date('d-m-Y')?>" onBlur="make_date(this)" required />
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="payee_payer_type" id="payeePayerType" onchange="selectLookup(this); cleanData(this, 'payeePayerCode', '%&_', 'payeeCodeLookup')" required >
                                <option value="%">All</option>
                                <option value="C">Client</option>
                                <option value="O">Others</option>
                            </select>
                        </div>				
                        <div class="col-md-9 float-start px-0 mb-1" id="lookupBtn">   
                            <div class="col-md-4 float-start px-2 mb-1 position-relative">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Code <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control w-100 float-start" name="payee_payer_code" id="payeePayerCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeePayerName'], ['payee_payer_name'], 'payee_code_finance', 'payee_type=@payeePayerType')" readonly>
                                <i class="fa fa-binoculars icn-vw lkupIcn d-none" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['client_help_id'] ?>&payee_type=@payeePayerType', 'payeePayerCode', ['payeePayerName'], ['client_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                        
                            <div class="col-md-8 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Name <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control w-100 float-start" name="payee_payer_name" id="payeePayerName" readonly >
                            </div>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Report Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="report_type" required >
                                <option value="D">Detail</option>
                                <option value="S">Summary</option>
                            </select>
                        </div>		
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="output_type" tabindex="12" required>
                                <option value="Report">View Report</option>
                                <option value="Pdf">Download PDF</option>
                                <option value="Excel">Download Excel</option>
                            </select>
                        </div>	
                        <div class="col-md-8 float-start">
                            <button type="submit" class="btn btn-primary cstmBtn mt-28 ms-2">Proceed</button>				
                            <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-28 ms-2">Reset</button>
                        </div>
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
                $maxline = 40 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tgramt  = 0; 
                $ttxamt  = 0; 
                $tntamt  = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($payreg_qry[$rowcnt-1]) ? $payreg_qry[$rowcnt-1] : '' ;  
                $report_cnt = $params['payreg_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $psrlind = 'Y';
                $pgramt  = $report_row['gross_amount'] ;
                $ptxamt  = $report_row['tax_amount'] ;
                $pntamt  = $report_row['net_amount'] ;
                $pserial = $report_row['serial_no'] ;
                while($pserial == $report_row['serial_no'] && $rowcnt <= $report_cnt) 
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
                    <table width="950" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                            <td colspan="4">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
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
                                    <td class="report_label_text">&nbsp;Payee</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['payee_name'] != '%') { echo strtoupper($params['payee_name']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th align="left"  class="py-3 px-2">&nbsp;Payee</th>
                            <th align="right" class="py-3 px-2">Gross&nbsp;</th>
                            <th align="right" class="py-3 px-2">TDS&nbsp;</th>
                            <th align="right" class="py-3 px-2">Net&nbsp;</th>
                        </tr>
                                    
            <?php
                        $lineno = 7 ;
                    }
            ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php echo strtoupper($report_row['payee_payer_name'])?></td>
                                        <td align="right" class="p-2"  style="vertical-align:top">&nbsp;</td>
                                        <td align="right" class="p-2"  style="vertical-align:top">&nbsp;</td>
                                        <td align="right" class="p-2"  style="vertical-align:top">&nbsp;</td>
                                    </tr>
            <?php     
                    $psrlind = 'N' ;
                    $lineno  = $lineno + 1;
                    $report_row = ($rowcnt < $report_cnt) ? $payreg_qry[$rowcnt] : $report_row;  
                    $rowcnt = $rowcnt + 1 ;
                }
            ?>
                                    <tr class="fs-14">
                                        <td colspan="8">&nbsp;</td>
                                    </tr>    
                                    <tr class="fs-14">
                                        <td align="right"  class="p-2"  style="background-color: #e2e6506e;"><b> Total</b></td>
                                        <td colspan="left" class="p-2" align="right" style="background-color: #e2e6506e;"><b><?php if($pgramt>0) { echo number_format($pgramt,2,'.','');}?></b>&nbsp;</td>
                                        <td colspan="left" class="p-2" align="right" style="background-color: #e2e6506e;"><b><?php if($ptxamt>0) { echo number_format($ptxamt,2,'.','');}?></b>&nbsp;</td>
                                        <td colspan="left" class="p-2" align="right" style="background-color: #e2e6506e;"><b><?php if($pntamt>0) { echo number_format($pntamt,2,'.','');}?></b>&nbsp;</td>
                                    </tr>   
            <?php
                $lineno  = $lineno + 3;
                $tgramt  = $tgramt + $pgramt ;
                $ttxamt  = $ttxamt + $ptxamt ;
                $tntamt  = $tntamt + $pntamt ;
                }  
            ?>                   
                                    <tr class="fs-14">
                                        <td colspan="8">&nbsp;</td>
                                    </tr>    
                                    <tr class="fs-14">
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b> PERIOD TOTAL </b></td>
                                        <td height="left" colspan="1" class="p-2" align="right"  style="background-color: #91d6ec6e;"><b><?php if($tgramt>0) { echo number_format($tgramt,2,'.','');}?></b>&nbsp;</td>
                                        <td height="left" colspan="1" class="p-2" align="right"  style="background-color: #91d6ec6e;"><b><?php if($ttxamt>0) { echo number_format($ttxamt,2,'.','');}?></b>&nbsp;</td>
                                        <td height="left" colspan="1" class="p-2" align="right"  style="background-color: #91d6ec6e;"><b><?php if($tntamt>0) { echo number_format($tntamt,2,'.','');}?></b>&nbsp;</td>
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

        if (document.realisationDetailForClient.start_date.value.substring(6,10)+document.realisationDetailForClient.start_date.value.substring(3,5)+document.realisationDetailForClient.start_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.realisationDetailForClient.start_date.focus()}, 500) });
            return false;
        }
        else if (document.realisationDetailForClient.end_date.value.substring(6,10)+document.realisationDetailForClient.end_date.value.substring(3,5)+document.realisationDetailForClient.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.realisationDetailForClient.end_date.focus()}, 500) });
            return false;
        }
        else if (document.realisationDetailForClient.start_date.value.substring(6,10)+document.realisationDetailForClient.start_date.value.substring(3,5)+document.realisationDetailForClient.start_date.value.substring(0,2)>document.realisationDetailForClient.end_date.value.substring(6,10)+document.realisationDetailForClient.end_date.value.substring(3,5)+document.realisationDetailForClient.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'Period End Date must be greater than Period Start Date' }).then((result) => { setTimeout(() => {document.realisationDetailForClient.end_date.focus()}, 500) });
            return false;
        } else if(document.realisationDetailForClient.report_type.value == 'D') {
            Swal.fire({ text: 'No record(s) found !!!' }).then((result) => { setTimeout(() => {document.realisationDetailForClient.report_type.focus()}, 500) });
            return false;
        }
        
        document.realisationDetailForClient.submit();
    }
    
    function selectLookup(select) {

        let selectValue = select.value;
        let lookupDiv = document.getElementById("lookupBtn");
        let payeePayerCode = document.getElementById("payeePayerCode");

        if(selectValue == '%')
        {
            lookupDiv.innerHTML = `
                <div class="col-md-4 float-start px-2 mb-1 position-relative">
                    <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Code <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control w-100 float-start" name="payee_payer_code" id="payeePayerCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeePayerName'], ['payee_payer_name'], 'payee_code_finance', 'payee_type=@payeePayerType')" readonly>
                    <i class="fa fa-binoculars icn-vw lkupIcn d-none" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['client_help_id'] ?>&payee_type=@payeePayerType', 'payeePayerCode', ['payeePayerName'], ['client_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
            
                <div class="col-md-8 float-start px-2 mb-1">
                    <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Name <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control w-100 float-start" name="payee_payer_name" id="payeePayerName" readonly >
                </div>
            `;
        }
        else if(selectValue == 'C') { 

            payeePayerCode.focus(); 
            
            lookupDiv.innerHTML = `
                <div class="col-md-4 float-start px-2 mb-1 position-relative">
                    <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Code <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control w-100 float-start" name="payee_payer_code" id="payeePayerCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeePayerName'], ['payee_payer_name'], 'payee_code_finance', 'payee_type=@payeePayerType')">
                    <i class="fa fa-binoculars icn-vw lkupIcn d-none" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['client_help_id'] ?>&payee_type=@payeePayerType', 'payeePayerCode', ['payeePayerName'], ['client_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
            
                <div class="col-md-8 float-start px-2 mb-1">
                    <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Name <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control w-100 float-start" name="payee_payer_name" id="payeePayerName" readonly >
                </div>
            `;
        }
        else if (selectValue == 'O') {
            
            payeePayerCode.focus(); 

            lookupDiv.innerHTML = `
                <div class="col-md-4 float-start px-2 mb-1 position-relative">
                    <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Code <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control w-100 float-start" name="payee_payer_code" id="payeePayerCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeePayerName'], ['payee_payer_name'], 'payee_code_finance', 'payee_type=@payeePayerType')">
                    <i class="fa fa-binoculars icn-vw lkupIcn d-none" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['other_help_id'] ?>&payee_type=@payeePayerType', 'payeePayerCode', ['payeePayerName'], ['payee_payer_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
            
                <div class="col-md-8 float-start px-2 mb-1">
                    <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Name <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control w-100 float-start" name="payee_payer_name" id="payeePayerName" readonly >
                </div>
            `;
        }
    }
</script>
<?= $this->endSection() ?>