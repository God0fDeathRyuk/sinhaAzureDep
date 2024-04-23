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
        <h1>List of Advance (Un-Adjusted)</h1>
        </div><!-- End Page Title -->
        <form action="" method="post" id="advanceUnadjusted" name="advanceUnadjusted">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">As On <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control float-start"  name="end_date" value="<?= date('d-m-Y') ?>" readonly/>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>				
                        <div class="col-md-3 float-start px-1 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Party Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="payee_type_code" id="payeeTypeCode" onChange="mypayeetype(); cleanData(this, 'payeeCode', '%&_', 'payeeCodeLookup')" required >
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
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Party Code </label>
                            <input type="text" class="form-control w-100 float-start" name="payee_code" id="payeeCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeeName'], ['payee_payer_name'], 'advance_payee', 'payee_type=@payeeType&advance_type=@advanceType')" readonly>
                            <i class="fa fa-binoculars icn-vw d-none" aria-hidden="true" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['payee_help_id'] ?>&payee_type=@payeeType&advance_type=@advanceType', 'payeeCode', ['payeeName'], ['payee_payer_name'], 'advance_payee')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-6 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Party Name</label>
                            <input type="text" class="form-control w-100 float-start" name="payee_name" id="payeeName" readonly>
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
                $maxline = 39 ;
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
                $padvamt   = 0; 
                $padjamt   = 0; 
                $pbalamt   = 0 ;
                $ppartyind = 'Y';
                $ppartycd  = $report_row['payee_payer_code'];
                $ppartynm  = $report_row['payee_payer_name'];
                while ($ppartycd == $report_row['payee_payer_code'] && $rowcnt <= $report_cnt)
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
                            <td colspan="10">    
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
                                    <td class="report_label_text">&nbsp;As On</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['end_date']?></b></td>
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
                            <th height="18" width="05%" align="left"  class="px-3 py-2">&nbsp;Adv#&nbsp;</th>
                            <th height="18" width="10%" align="left"  class="px-3 py-2">&nbsp;Date</th>
                            <th height="18" width="05%" align="left"  class="px-3 py-2">&nbsp;DB</th>
                            <th height="18" width="05%" align="left"  class="px-3 py-2">&nbsp;Typ</th>
                            <th height="18" width="10%" align="left"  class="px-3 py-2">&nbsp;Doc#</th>
                            <th height="18" width="10%" align="left"  class="px-3 py-2">&nbsp;Chq#</th>
                            <th height="18" width="10%" align="left"  class="px-3 py-2">&nbsp;Chq Dt</th>
                            <th height="18" width="15%" align="right" class="px-3 py-2">Advance&nbsp;</th>
                            <th height="18" width="15%" align="right" class="px-3 py-2">Adjusted&nbsp;</th>
                            <th height="18" width="15%" align="right" class="report_detail_tb" >Balance&nbsp;</th>
                        </tr>
                                    
                <?php
                            $ppartyind = 'Y';
                            $lineno = 9 ;
                        }
                    
                        if ($ppartyind == 'Y') 
                        {
                ?>
                                        <tr class="fs-14">
                                            <td height="22" align="left"  class="p-2" colspan="10">&nbsp;<b><?php echo strtoupper($ppartynm) .' ['.$ppartycd.']'; ?></b></td> 
                                        </tr>
                <?php
                            $ppartyind = 'N' ;
                            $lineno  = $lineno + 1 ; 
                        }
                ?>
                                    <tr class="fs-14">
                                        <td height="16" align="right" class="p-2">&nbsp;<?php echo $report_row['serial_no']?></td> 
                                        <td height="16" align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['doc_date'],'-')?></td>
                                        <td height="16" align="left"  class="p-2">&nbsp;<?php echo $report_row['daybook_code']?></a></td>
                                        <td height="16" align="left"  class="p-2">&nbsp;<?php echo $report_row['doc_type']?></a></td>
                                        <td height="16" align="left"  class="p-2">&nbsp;<?php echo $report_row['doc_no']?></a></td>
                                        <td height="16" align="left"  class="p-2">&nbsp;<?php echo $report_row['instrument_no']?></a></td>
                                        <td height="16" align="left"  class="p-2">&nbsp;<?php if($report_row['instrument_dt'] != '' && $report_row['instrument_dt'] != '0000-00-00') { echo date_conv($report_row['instrument_dt'],'-') ; } ?></td>
                                        <td height="16" align="right" class="p-2"><?php if ($report_row['gross_amount']      > 0) { echo $report_row['gross_amount']      ; } else { echo '&nbsp;' ; } ?>&nbsp;</td>
                                        <td height="16" align="right" class="p-2"><?php if ($report_row['adjusted_amount'] > 0) { echo $report_row['adjusted_amount'] ; } else { echo '&nbsp;' ; } ?>&nbsp;</td>
                                        <td height="16" align="right" class="p-2"><?php if ($report_row['balance_amount']  > 0) { echo $report_row['balance_amount']  ; } else { echo '&nbsp;' ; } ?>&nbsp;</td>
                                    </tr>
                <?php     
                        $lineno = $lineno + 1;
                        $padvamt = $padvamt + $report_row['gross_amount'] ;                   
                        $padjamt = $padjamt + $report_row['adjusted_amount'] ;                   
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
                                        <td height="20" align="right"   class="p-2" colspan="7" style="background-color:#eff3b1;"><b> Total</b>&nbsp;</td>
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
                                        <td colspan="10">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="18" align="right" class="p-2" colspan="7" style="background-color:#bee9f7;">&nbsp;<b>GRAND TOTAL</b>&nbsp;</td>
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
        if (document.advanceUnadjusted.payee_type_code.value == 'C') { document.advanceUnadjusted.advance_type.value = 'R' ; } else { document.advanceUnadjusted.advance_type.value = 'P' ; }
        if (document.advanceUnadjusted.payee_type_code.value == 'L') { document.advanceUnadjusted.payee_type.value   = 'C' ; } else { document.advanceUnadjusted.payee_type.value   = document.advanceUnadjusted.payee_type_code.value ; }
        if (document.advanceUnadjusted.payee_type.value != '%') { document.advanceUnadjusted.payee_code.readOnly  = false ; }
        
    }

</script>
<?= $this->endSection() ?>