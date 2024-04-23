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
        <h1>Receipt Register</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="receiptRegister" name="receiptRegister" onsubmit="setValue(event)">
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
                            <input type="hidden" name="current_date" value="<?= date('d-m-Y') ?>">
                        </div>
                        <div class="col-md-3 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="payee_payer_type" id="payeePayerType" onchange="cleanData(this, 'payeePayerCode', '%&_', 'payeeCodeLookup')" required >
                                <option value="">--Select--</option>
                                <option value="C">Client</option>
                            </select>
                        </div>				
                        
                        <div class="col-md-4 float-start px-2 mb-3 position-relative">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Code <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control w-100 float-start" name="payee_payer_code" id="payeePayerCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeePayerName'], ['payee_payer_name'], 'payee_code_finance', 'payee_type=@payeePayerType')" >
                            <i class="fa-solid fa-binoculars icn-vw d-none" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['client_help_id'] ?>&payee_type=@payeePayerType', 'payeePayerCode', ['payeePayerName'], ['client_name'], 'payee_code_finance')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Name <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control w-100 float-start" name="payee_payer_name" id="payeePayerName" readonly >
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Report Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="report_type" required >
                                <option value="S">Summary</option>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Report Seq <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="report_seqn" required >
                                <option value="C">Client-wise</option>
                                <option value="M">Court-wise</option>
                                <option value="I">Initial-wise</option>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="output_type" required >
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
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
					<?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
						<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
					<?php } else { ?> 
						<button onclick="window.close()" class="text-decoration-none d-block float-start btn btn-dark">Close</button>
					<?php } } ?>
				<?php endif; ?>
			</div>
            <?php if($form_report_type == 'S') { ?>
                <?php
                    $maxline = 60 ;
                    $lineno  = 0 ;
                    $pageno  = 0 ;
                    $tgramt  = 0; 
                    $ttxamt  = 0; 
                    $tntamt  = 0; 
                    $tosamt  = 0;
                    $rowcnt     = 1 ;
                    $report_row = isset($payreg_qry[$rowcnt-1]) ? $payreg_qry[$rowcnt-1] : '' ;
                    $report_cnt = $params['payreg_cnt'] ;
                    while ($rowcnt <= $report_cnt)
                    {
                    $psrlind = 'Y';
                    // $pgramt  = $report_row['gross_amount'] ;
                    // $ptxamt  = $report_row['tax_amount'] ;
                    // $pntamt  = $report_row['net_amount'] ;
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
                        <table width="750" align="center" border="1" cellspacing="0" cellpadding="0" class="table border-0">
                            <tr>
                            <td colspan="2">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="cheque_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
                                    </tr>
                                    <tr>
                                        <td class="cheque_text" colspan="4" align="center"><b><u><?php echo strtoupper($params['report_desc'])?></u></b></td>
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
                                        <td class="report_label_text">&nbsp;Payee</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['payee_code'] != '%') { echo strtoupper($params['payee_name']);} else { echo 'ALL' ;} ?></b></td>
                                        <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">&nbsp;Period</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['start_date'] . ' TO ' . $params['end_date'] ; ?></b></td>
                                        <td class="report_label_text">&nbsp;</td>
                                        <td class="report_label_text">&nbsp;</td>
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
                                <th width="83%" align="left"  class="py-3 px-2">&nbsp;<b>Payee Name</b></th>
                                <th width="17%" align="right" class="py-3 px-2"><b>Amount</b>&nbsp;</th>
                            </tr>
                    <?php
                            $lineno = 9 ;
                        }
                        //----------
                        $rowdesc  = $report_row['payee_payer_name'] ; 
                        $rosamt   = $report_row['paid_amount'] ; 
                        $tosamt   = $tosamt + $rosamt  ; 
                    ?>
                                <tr class="fs-14 border-0">
                                    <td class="p-2"     align="left">&nbsp;<?php echo strtoupper($report_row['payee_payer_name'])?></a></td>
                                    <td class="p-2" align="right"><?php if($rosamt == 0.00) echo '&nbsp;'; else  echo number_format($rosamt,2,'.','');?></td>
                                </tr>
                    <?php     
                        $lineno = $lineno + 1;

                        $report_row = ($rowcnt < $report_cnt) ? $payreg_qry[$rowcnt] : $report_row; 
                        $rowcnt = $rowcnt + 1 ;
                        }
                    }  
                    ?>                   
                                <tr class="fs-14 border-0">
                                    <td class="p-2" align="right" colspan="0" style="background-color: #fdfcc6;"><b>TOTAL</b>&nbsp;</td>
                                    <td width="17%"  align="right" class="p-2" style="background-color: #fdfcc6;"><b><?php if($tosamt == 0.00) echo '&nbsp;'; else  echo number_format($tosamt,2,'.','');?></b></td>
                                </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
            <?php } else { ?>
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
                        <table width="990" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>    
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
                            <tr>
                                <td colspan="4" class="grid_header">
                                    <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                        <tr bgcolor="#CCCCCC">
                                        <td width="08%" align="left"  class="report_detail_tb">&nbsp;Date</td>
                                        <td width="05%" align="left"  class="report_detail_tb">&nbsp;Doc#</td>
                                        <td width="03%" align="left"  class="report_detail_tb">&nbsp;DB</td>
                                        <td width="25%" align="left"  class="report_detail_tb">&nbsp;Payee</td>
                                        <td width="32%" align="left"  class="report_detail_tb">&nbsp;Narration</td>
                                        <td width="09%" align="right" class="report_detail_tb">Gross&nbsp;</td>
                                        <td width="09%" align="right" class="report_detail_tb">TDS&nbsp;</td>
                                        <td width="09%" align="right" class="report_detail_tb">Net&nbsp;</td>
                                        </tr>
                <?php
                            $lineno = 7 ;
                        }
                ?>
                                        <tr>
                                        <td align="left"  class="report_detail_none"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo date_conv($report_row['doc_date'],'-') ; }?></td> 
                                        <td align="left"  class="report_detail_none"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo $report_row['doc_no'] ; }?></td>
                                        <td align="left"  class="report_detail_none"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo $report_row['daybook_code'] ; }?></td>
                                        <td align="left"  class="report_detail_none"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo strtoupper($report_row['payee_payer_name']) ; }?></td>
                                        <td align="justify"  class="report_detail_none"  style="vertical-align:top">&nbsp;<?php echo strtoupper($report_row['narration'])?></td>
                                        <td align="right" class="report_detail_none"  style="vertical-align:top"><?php echo $report_row['paid_amount'] ?>&nbsp;</td>
                                        <td align="right" class="report_detail_none"  style="vertical-align:top">&nbsp;</td>
                                        <td align="right" class="report_detail_none"  style="vertical-align:top">&nbsp;</td>
                                        </tr>
                <?php     
                        $psrlind = 'N' ;
                        $lineno  = $lineno + 1;
                        $report_row = ($rowcnt < $report_cnt) ? $payreg_qry[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;
                    }
                ?>
                                        <tr>
                                        <td colspan="8">&nbsp;</td>
                                        </tr>    
                                        <tr>
                                        <td colspan="5" class="report_detail_none" align="left" ><b>** Total</b></td>
                                        <td colspan="1" class="report_detail_none" align="right"><b><?php if($pgramt>0) { echo number_format($pgramt,2,'.','');}?></b>&nbsp;</td>
                                        <td colspan="1" class="report_detail_none" align="right"><b><?php if($ptxamt>0) { echo number_format($ptxamt,2,'.','');}?></b>&nbsp;</td>
                                        <td colspan="1" class="report_detail_none" align="right"><b><?php if($pntamt>0) { echo number_format($pntamt,2,'.','');}?></b>&nbsp;</td>
                                        </tr>
                                        <tr>
                                        <td colspan="8"><hr size="1" color="#CCCCCC" noshade></td>
                                        </tr>    
                <?php
                    $lineno  = $lineno + 3;
                    $tgramt  = $tgramt + $pgramt ;
                    $ttxamt  = $ttxamt + $ptxamt ;
                    $tntamt  = $tntamt + $pntamt ;
                    }  
                ?>                   
                                        <tr>
                                        <td colspan="8">&nbsp;</td>
                                        </tr>    
                                        <tr>
                                        <td height="20" colspan="5" class="report_detail_bottom" align="center"><b>*** PERIOD TOTAL ***</b></td>
                                        <td height="20" colspan="1" class="report_detail_bottom" align="right" ><b><?php if($tgramt>0) { echo number_format($tgramt,2,'.','');}?></b>&nbsp;</td>
                                        <td height="20" colspan="1" class="report_detail_bottom" align="right" ><b><?php if($ttxamt>0) { echo number_format($ttxamt,2,'.','');}?></b>&nbsp;</td>
                                        <td height="20" colspan="1" class="report_detail_bottom" align="right" ><b><?php if($tntamt>0) { echo number_format($tntamt,2,'.','');}?></b>&nbsp;</td>
                                        </tr>
                                </table>
                                </td>
                            </tr>
                        </table> 
            <?php } ?>
    </main>
<?php } ?>

<script>
    function setValue(e) {
        e.preventDefault();
        console.log(document.receiptRegister);
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.receiptRegister.start_date.value.substring(6,10)+document.receiptRegister.start_date.value.substring(3,5)+document.receiptRegister.start_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.receiptRegister.start_date.focus()}, 500) });
            return false;
        }
        else if (document.receiptRegister.end_date.value.substring(6,10)+document.receiptRegister.end_date.value.substring(3,5)+document.receiptRegister.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.receiptRegister.end_date.focus()}, 500) });
            return false;
        }
        else if (document.receiptRegister.start_date.value.substring(6,10)+document.receiptRegister.start_date.value.substring(3,5)+document.receiptRegister.start_date.value.substring(0,2)>document.receiptRegister.end_date.value.substring(6,10)+document.receiptRegister.end_date.value.substring(3,5)+document.receiptRegister.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'Period End Date must be less than Period Start Date' }).then((result) => { setTimeout(() => {document.receiptRegister.end_date.focus()}, 500) });
            return false;
        }
        else if (document.receiptRegister.report_type.value == 'S' && document.receiptRegister.payee_payer_type.value != 'C' ) 
         {
            Swal.fire({ text: 'Please select Payee Type- Client for Summary !!!' }).then((result) => { setTimeout(() => {document.receiptRegister.payee_payer_type.focus()}, 500) });
			return false ;
         }
         else if (document.receiptRegister.report_type.value != 'S' && document.receiptRegister.output_type.value == 'Excel' ) 
         {
            Swal.fire({ text: 'Sorry !!! Please Select Report Type Summary for Excel Output !!!' }).then((result) => { setTimeout(() => {document.receiptRegister.report_type.focus()}, 500) });
			return false ;
         } else if (document.receiptRegister.report_seqn.value == 'I' && document.receiptRegister.payee_payer_code.value == '' ) 
         {
            Swal.fire({ text: 'Please select Payee Code !!!' }).then((result) => { setTimeout(() => {document.receiptRegister.payee_payer_code.focus()}, 500) });
			return false ;
         }
        document.receiptRegister.submit();
    }
</script>
<?= $this->endSection() ?>