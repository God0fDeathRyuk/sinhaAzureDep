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
        <h1>List of Advance (Adjusted)</h1>
        </div><!-- End Page Title -->
        <form action="" method="post" id="advanceAdjusted" name="advanceAdjusted" onsubmit="setValue(event)">
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
                                <input type="text" class="form-control w-45 float-start" name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required/>
                                <span class="w-2 float-start mx-1">---</span>
                                <input type="text" class="form-control w-45 float-start" name="end_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required />
                            </div>
                            <div class="col-md-3 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Party Type </label>
                                <select class="form-select w-100 float-start" name="payee_type_code" id="payeeTypeCode" onChange="mypayeetype(); cleanData(this, 'payeeCode', '%&_', 'payeeCodeLookup')" >
                                    <option value="" >--Select--</option>
                                    <option value="C">Client</option>
                                    <option value="E">Employee</option>
                                    <option value="S">Supplier</option>
                                    <option value="L">Counsel</option>
                                    <option value="A">Arbitrator</option>
                                    <option value="T">Stenographer</option>
                                </select>
                                <input type="hidden"  size="03" maxlength="01"  name="payee_type" id="payeeType"   value="%">
                                <input type="hidden"  size="03" maxlength="01"  name="advance_type" id="advanceType" value="%">
                                <input type="hidden"  size="03" maxlength="01"  name="payee_code2" id="payeeCode2" value="%">
                            </div>
                            <div class="col-md-4 float-start px-2 mb-3 position-relative">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Party Code</label>
                                <input type="text" class="form-control w-100 float-start" name="payee_code" id="payeeCode" onfocusout="document.getElementById('payeeCode2').value=this.value" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeeName'], ['payee_payer_name'], 'advance_payee', 'payee_type=@payeeType&advance_type=@advanceType')" >
                                <i class="fa fa-binoculars icn-vw d-none" aria-hidden="true" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['payee_help_id'] ?>&payee_type=@payeeType&advance_type=@advanceType', 'payeeCode', ['payeeName'], ['payee_payer_name'], 'advance_payee')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-8 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Party Name</label>
                                <input type="text" class="form-control w-100 float-start" name="payee_name" id="payeeName" readonly>
                            </div>
                            
                            <div class="col-md-6 float-start px-2 mb-3 position-relative">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Advance Srl#</label>
                                <input type="text" class="form-control w-100 float-start" name="advance_serial" id="advanceSerial" readonly>
                                <i class="fa fa-binoculars icn-vw" aria-hidden="true" onclick="showData('serial_no', 'display_id=<?= $displayId['advance_help_id'] ?>&payee_type=@payeeType&payee_code=@payeeCode2&advance_type=@advanceType', 'advanceSerial', ['payeeTypeCode', 'payeeCode', 'payeeName', 'payeeType', 'advanceType'], ['payee_type_code', 'payee_payer_code', 'payee_payer_name', 'payee_payer_type', 'advance_type'])" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>		
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                                <select class="form-select w-100 float-start" name="output_type" required >
                                    <option value="Report">View Report</option>
                                    <option value="Pdf" >Download PDF</option>
                                    <option value="Excel" >Download Excel</option>
                                </select>
                            </div>	
                            <div class="d-inline-block w-100">
                                <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Proceed</button>				
                                <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Reset</button>
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
                $maxline = 52 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tadvamt = 0; 
                $tadjamt = 0; 
                $tbalamt = 0 ;
                $rowcnt     = 1 ;
                $report_row = isset($trans_qry[$rowcnt-1]) ? $trans_qry[$rowcnt-1] : '' ;
                $report_cnt = $params['trans_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $padvamt = 0; 
                $padjamt = 0; 
                $pbalamt = 0 ;
                $psrlind = 'Y';
                $pserial = $report_row['serial_no'];
                $pdocno  = $report_row['doc_no'];
                $pdocdt  = $report_row['doc_date'];
                $ppaycd  = $report_row['payee_payer_code'];
                $ppaynm  = $report_row['payee_payer_name'];
                while ($pserial == $report_row['serial_no'] && $rowcnt <= $report_cnt)
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
                            <td colspan="6">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="12%">&nbsp;</td>
                                    <td width="68%">&nbsp;</td>
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
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th height="18" width="10%" align="left"  class="px-3 py-2">&nbsp;Date</th>
                            <th height="18" width="08%" align="left"  class="px-3 py-2">&nbsp;Doc#</th>
                            <th height="18" width="46%" align="left"  class="px-3 py-2">&nbsp;Narration</th>
                            <th height="18" width="12%" align="right" class="px-3 py-2">Advance&nbsp;</th>
                            <th height="18" width="12%" align="right" class="px-3 py-2">Adjusted&nbsp;</th>
                            <th height="18" width="12%" align="right" class="px-3 py-2">Balance&nbsp;</th>
                        </tr>
                                    
                <?php
                            $psrlind = 'Y';
                            $lineno = 9 ;
                        }
                    
                        if ($psrlind == 'Y') 
                        {
                ?>
                                        <tr class="fs-14">
                                            <td height="22" align="left"  class="report_detail_none" colspan="6"><b><?php echo strtoupper($ppaynm) ?>&nbsp;&nbsp;[ADVANCE # : <?php echo $pserial ?> ]</b></td> 
                                        </tr>
                <?php
                        $psrlind = 'N' ;
                        $lineno  = $lineno + 1 ; 
                    }

                    if ($report_row['ind'] == '1') 
                    {
                        $narration = '<b>Opening</b>' ;
                    }
                    else
                    {
                        $narration = 'Ref Doc # : '.$report_row['ref_doc_year'].'/'.$report_row['ref_doc_no'].'  Dated '.date_conv($report_row['ref_doc_date'],'-') ;
                    }

                ?>
                                        <tr class="fs-14">
                                            <td height="16" align="left"  class="px-2">&nbsp;<?php echo date_conv($report_row['adj_docdt'],'-')?></td> 
                                            <td height="16" align="left"  class="px-2">&nbsp;<?php echo $report_row['adj_docno']?></td>
                                            <td height="16" align="left"  class="px-2">&nbsp;<?php echo $narration?></a></td>
                                            <td height="16" align="right" class="px-2"><?php if ($report_row['opbamt'] > 0) { echo $report_row['opbamt'] ; } else { echo '&nbsp;' ; } ?>&nbsp;</td>
                                            <td height="16" align="right" class="px-2"><?php if ($report_row['adjamt'] > 0) { echo $report_row['adjamt'] ; } else { echo '&nbsp;' ; } ?>&nbsp;</td>
                                            <td height="16" align="right" class="px-2">&nbsp;</td>
                                        </tr>
                <?php     
                    $lineno = $lineno + 1;
                    $padvamt = $padvamt + $report_row['opbamt'] ;                   
                    $padjamt = $padjamt + $report_row['adjamt'] ;                   
                    //
                    $report_row = ($rowcnt < $report_cnt) ? $trans_qry[$rowcnt] : $report_row; 
                    $rowcnt = $rowcnt + 1 ;
                }  
                $pbalamt = $padvamt - $padjamt ;
                $tadvamt = $tadvamt + $padvamt ;                   
                $tadjamt = $tadjamt + $padjamt ;                   
                $tbalamt = $tbalamt + $pbalamt ;                   
                ?>
                                        <tr class="fs-14">
                                            <td height="20" align="right"   class="p-2" colspan="3" style="background-color:#eff3b1;"><b> Total</b>&nbsp;</td>
                                            <td height="20" align="right"  class="p-2" style="background-color:#eff3b1;"><b><?php if ($padvamt > 0) { echo number_format($padvamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
                                            <td height="20" align="right"  class="p-2" style="background-color:#eff3b1;"><b><?php if ($padjamt > 0) { echo number_format($padjamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
                                            <td height="20" align="right"  class="p-2" style="background-color:#eff3b1;"><b><?php if ($pbalamt > 0) { echo number_format($pbalamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
                                        </tr>
                <?php
                        $lineno = $lineno + 1;
                        if ($maxline - $lineno < 2) { $lineno = $maxline ; }
                    }
                ?>                   
                                    <tr class="fs-14">
                                        <td colspan="9">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="18" align="right" class="p-2" colspan="3" style="background-color:#bee9f7;">&nbsp;<b>GRAND TOTAL</b>&nbsp;</td>
                                        <td height="18" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if ($tadvamt > 0) { echo number_format($tadvamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
                                        <td height="18" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if ($tadjamt > 0) { echo number_format($tadjamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
                                        <td height="18" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if ($tbalamt > 0) { echo number_format($tbalamt,2,'.','') ; } else { echo '&nbsp;' ; } ?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
    </main>
<?php } ?>

<script>
    function mypayeetype() {
        if (document.advanceAdjusted.payee_type_code.value == 'C') { document.advanceAdjusted.advance_type.value = 'R' ; } else { document.advanceAdjusted.advance_type.value = 'P' ; }
        if (document.advanceAdjusted.payee_type_code.value == 'L') { document.advanceAdjusted.payee_type.value   = 'C' ; } else { document.advanceAdjusted.payee_type.value   = document.advanceAdjusted.payee_type_code.value ; }
        if (document.advanceAdjusted.payee_code.value == '') { document.advanceAdjusted.payee_code2.value   = '%' ; } else { document.advanceAdjusted.payee_code2.value   = document.advanceAdjusted.payee_code.value ; }
    }

    function setValue(e) {
        e.preventDefault();
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.advanceAdjusted.start_date.value.substring(6,10)+document.advanceAdjusted.start_date.value.substring(3,5)+document.advanceAdjusted.start_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.advanceAdjusted.start_date.focus()}, 500) });
            return false;
        }
        else if (document.advanceAdjusted.end_date.value.substring(6,10)+document.advanceAdjusted.end_date.value.substring(3,5)+document.advanceAdjusted.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.advanceAdjusted.end_date.focus()}, 500) });
            return false;
        }
        else if (document.advanceAdjusted.start_date.value.substring(6,10)+document.advanceAdjusted.start_date.value.substring(3,5)+document.advanceAdjusted.start_date.value.substring(0,2)>document.advanceAdjusted.end_date.value.substring(6,10)+document.advanceAdjusted.end_date.value.substring(3,5)+document.advanceAdjusted.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'Period End Date must be less than Period Start Date !!!' }).then((result) => { setTimeout(() => {document.advanceAdjusted.end_date.focus()}, 500) });
            return false;
        }
        else if (document.advanceAdjusted.payee_type.value == '%' && document.advanceAdjusted.advance_type.value  == '%') {
            Swal.fire({ text: 'Enter either Party Type or Advance Serial No !!!' }).then((result) => { setTimeout(() => {document.advanceAdjusted.payee_type_code.focus()}, 500) });
			return false ;
         }
        document.advanceAdjusted.submit();
    }
</script>
<?= $this->endSection() ?>