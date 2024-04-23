<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if (!isset($unclr_credit_sql)) { ?>
<main id="main" class="main">
    <?php if (session()->getFlashdata('message') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
  
        <div class="pagetitle">
            <h1>Bank Reconciliation Statement</h1>
        </div><!-- End Page Title -->
    <form action="" method="post" name="bankStatement" id="bankStatement" onsubmit="setValue(event)">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">				
            
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-1">
                <label class="d-inline-block w-100 mb-1 lbl-mn">Year / Month <strong class="text-danger">*</strong></label>
                <select class="form-select w-35 float-start" name="fin_year" required>
                    <option value="">--Select--</option>
                    <?php foreach($finyr_qry as $finyr_row) { ?>
                        <option value="<?php echo $finyr_row['fin_year']?>" <?php if(session()->financialYear == $finyr_row['fin_year']) { echo 'selected' ; }?>><?php echo $finyr_row['fin_year']?></option>
                    <?php } ?>
                </select>
                <select class="form-select w-63 float-start ms-2" name="fin_month" required>
                    <option value="">--Select--</option>
                    <?php foreach($month_qry as $month_row) { ?>
                        <option value="<?php echo $month_row['month_no']?>" <?php if($curr_month == $month_row['month_no']) { echo 'selected' ; }?>><?php echo $month_row['month_descl']?></option>
                    <?php } ?>
                </select>
                <input type="hidden" size="08" maxlength="12" name="curr_yyyymm" value="<?php echo $curr_yyyymm?>">
                <input type="hidden" size="08" maxlength="12" name="curr_date"   value="<?php echo date('Y-m-d')?>">
            </div>
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-1">
                <label class="d-inline-block w-100 mb-1 lbl-mn">Bank <strong class="text-danger">*</strong></label>
                <select class="form-select" name="daybook_code" required>
                    <option value="">--Select--</option>
                    <?php foreach($daybook_qry as $daybook_row) { ?>
                        <option value="<?php echo $daybook_row['daybook_code']?>"><?php echo $daybook_row['daybook_desc'] . ' [DB '.$daybook_row['daybook_code'].']';?></option>
                    <?php } ?>		
                </select>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-1">
                <label class="d-inline-block w-100 mb-1 lbl-mn">Statement <strong class="text-danger">*</strong></label>
                <select class="form-select" name="statement_no" required>
                    <option value="">--Select--</option>
                    <option value="1">Bank Reconciliation Statement (with associated List)</option>
                    <option value="2">Bank Reconciliation Statement (w/o  associated List)</option>
                    <option value="3">Cheques Deposited but not yet Credited by Bank</option>
                    <option value="4">Cheques Issued but not yet Debited by Bank</option>
                    <option value="5">Other Transaction Debited/Credited by Bank but not in Book</option>
                </select>
            </div>
            <div class="col-md-3 float-start px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                <select class="form-select" name="output_type" tabindex="12" required>
                    <option value="">--Select--</option>
                    <option value="Report">View Report</option>
                    <option value="Pdf" >Download PDF</option>
                    <option value="Excel" >Download Excel</option>
                </select>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mt-10">
                <button type="submit" class="btn btn-primary cstmBtn mt-3">Proceed</button>
                <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
            </div>
        </div>
    </form>
</main>
<?php } else { ?>
    <script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>
    <main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
        <form action="" method="post"> 
            <div class="position-absolute btndv">
                <?php if ($renderFlag) : ?>
                    <a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
                <?php endif; ?>
            </div>  
            <?php 
            if ($params['statement_no'] == '1' || $params['statement_no'] == '2') { 
                $pageno = 1 ;
                ?>
                    <table width="750" align="center" class="table border-0" border="" cellspacing="0" cellpadding="0">
                        <tr>
                        <td class="border-0 pb-0" colspan="3">    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td height="22" class="report_label_text" colspan="4" align="center"><b>SINHA AND COMPANY </b></td>
                                </tr>
                                <tr>
                                    <td height="22" class="report_label_text" colspan="4" align="center"><b><u>[<?php echo strtoupper($params['report_desc'])?>]</u></b></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="22" class="report_label_text">&nbsp;Bank</td>
                                    <td height="22" class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['daybook_name']. ' [DB '.$params['daybook_code'].']';?></b></td>
                                    <td height="22" class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td height="22" class="report_label_text">&nbsp;:&nbsp;<?php echo $params['lstdate'];?></td>
                                </tr>
                                <tr>
                                    <td height="22" class="report_label_text">&nbsp;Period</td>
                                    <td height="22" class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc']?></b></td>
                                    <td height="22" class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td height="22" class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </table>
                        </td>    
                        </tr>
                                <tr class="fs-14">
                                    <th height="20" width="66%" align="left"  class="p-2">&nbsp;Particulars</th>
                                    <th height="20" width="17%" align="right" class="p-2">Credit&nbsp;</th>
                                    <th height="20" width="17%" align="right" class="p-2">Debit&nbsp;</th>
                                </tr>
                                <tr class="fs-14">
                                    <td height="22" align="left"  class="p-2">&nbsp;<b>Opening Balance as per Book</b></td> 
                                    <td height="22" align="right" class="p-2"><b><?php if($params['book_opbal_amt'] >= 0.00) echo number_format(abs($params['book_opbal_amt']),2,'.','') ; else echo '&nbsp;'; ?></b>&nbsp;</td>
                                    <td height="22" align="right" class="p-2" ><b><?php if($params['book_opbal_amt'] <  0.00) echo number_format(abs($params['book_opbal_amt']),2,'.','') ; else echo '&nbsp;'; ?></b>&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="right" class="p-2">&nbsp;</td>
                                    <td align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td height="22" align="left"  class="p-2">&nbsp;Add  : Receipts during the period</td> 
                                    <td height="22" align="right" class="p-2"><b><?php if($params['curr_credit_amt'] > 0.00) echo number_format($params['curr_credit_amt'],2,'.','') ; else echo '&nbsp;'; ?></b>&nbsp;</td>
                                    <td height="22" align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="right" class="p-2">&nbsp;</td>
                                    <td align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td height="22" align="left"  class="p-2">&nbsp;Less : Payments during the period</td> 
                                    <td height="22" align="right" class="p-2">&nbsp;</td>
                                    <td height="22" align="right" class="p-2" ><b><?php if($params['curr_debit_amt'] > 0.00) echo number_format($params['curr_debit_amt'],2,'.','') ; else echo '&nbsp;'; ?></b>&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="right" class="p-2">&nbsp;</td>
                                    <td align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td height="22" align="left"  class="p-2">&nbsp;<b>Closing Balance as per Book</b></td> 
                                    <td height="22" align="right" class="p-2"><b><?php if($params['book_clbal_amt'] > 0.00) echo number_format(abs($params['book_clbal_amt']),2,'.','') ; else echo '&nbsp;'; ?></b>&nbsp;</td>
                                    <td height="22" align="right" class="p-2" ><b><?php if($params['book_clbal_amt'] < 0.00) echo number_format(abs($params['book_clbal_amt']),2,'.','') ; else echo '&nbsp;'; ?></b>&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="right" class="p-2">&nbsp;</td>
                                    <td align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td height="22" align="left"  class="p-2">&nbsp;Add  : Cheques Issued not debited by Bank</td> 
                                    <td height="22" align="right" class="p-2"><?php if($params['unclr_debit_amt'] > 0.00) echo number_format($params['unclr_debit_amt'],2,'.','') ; else echo '&nbsp;'; ?>&nbsp;</td>
                                    <td height="22" align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="right" class="p-2">&nbsp;</td>
                                    <td align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td height="22" align="left"  class="p-2">&nbsp;Less  : Cheques Deposited not credited by Bank</td> 
                                    <td height="22" align="right" class="p-2">&nbsp;</td>
                                    <td height="22" align="right" class="p-2" ><?php if($params['unclr_credit_amt'] > 0.00) echo number_format($params['unclr_credit_amt'],2,'.','') ; else echo '&nbsp;'; ?>&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="right" class="p-2">&nbsp;</td>
                                    <td align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td height="22" align="left"  class="p-2">&nbsp;Add  : Amount Credited by Bank not shown in Book</td> 
                                    <td height="22" align="right" class="p-2"><?php if($params['bank_credit_amt'] > 0.00) echo number_format($params['bank_credit_amt'],2,'.','') ; else echo '&nbsp;'; ?>&nbsp;</td>
                                    <td height="22" align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="right" class="p-2">&nbsp;</td>
                                    <td align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td height="22" align="left"  class="p-2">&nbsp;Less : Amount Debited by Bank not shown in Book</td> 
                                    <td height="22" align="right" class="p-2">&nbsp;</td>
                                    <td height="22" align="right" class="p-2" ><?php if($params['bank_debit_amt'] > 0.00) echo number_format($params['bank_debit_amt'],2,'.','') ; else echo '&nbsp;'; ?>&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="right" class="p-2">&nbsp;</td>
                                    <td align="right" class="p-2" >&nbsp;</td>
                                </tr>
                                <tr class="fs-14">
                                    <td height="22" align="left"  class="p-2" >&nbsp;<b>Closing Balance as per Bank</b></td> 
                                    <td height="22" align="right" class="p-2" ><b><?php if($params['bank_clbal_amt'] > 0.00) echo number_format(abs($params['bank_clbal_amt']),2,'.','') ; else echo '&nbsp;'; ?></b>&nbsp;</td>
                                    <td height="22" align="right" class="p-2"><b><?php if($params['bank_clbal_amt'] < 0.00) echo number_format(abs($params['bank_clbal_amt']),2,'.','') ; else echo '&nbsp;'; ?></b>&nbsp;</td>
                                </tr>
                </table>
                <BR CLASS="pageEnd"> 
                <?php
                    }
                ?>
            <!---------------------  Uncleared Receipts ------------------- -->  
            <?php if ($params['statement_no'] == '1' || $params['statement_no'] == '3') { 
                //----- 
                $maxline = 60 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $totamt  = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($unclr_credit_sql[$rowcnt-1]) ? $unclr_credit_sql[$rowcnt-1] : '' ;   
                $report_cnt = $params['unclr_credit_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                    $pdocdt = $report_row['doc_date'] ;
                    while ($pdocdt == $report_row['doc_date'] && $rowcnt <= $report_cnt)
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
                        <table width="750" align="center" class="table border-0" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="border-0" colspan="5">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b>SINHA AND COMPANY</b></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b><u>[<?php echo strtoupper($params['report_desc'])?>]</u></b></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">&nbsp;Bank</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['daybook_name']?></b></td>
                                        <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['lstdate'];?></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">&nbsp;Period</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc']?></b></td>
                                        <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">&nbsp;Subject</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b>CHEQUES DEPOSITED NOT CREDITED BY BANK</b></td>
                                        <td class="report_label_text">&nbsp;</td>
                                        <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    </table>
                                </td>    
                            </tr>
                            <tr class="fs-14">
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Date</th>
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Chq#</th>
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Chq Dt</th>
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Received From</th>
                                <th width="" align="right" class="py-3 px-2">Amount&nbsp;</th>
                            </tr>
                <?php
                            $lineno = 8 ;
                            }
                ?>
                                    <tr class="fs-14">
                                        <td height="15" align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['doc_date'],'-')?></td> 
                                        <td height="15" align="left"  class="p-2">&nbsp;<?php echo $report_row['instrument_no']?></td>
                                        <td height="15" align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['instrument_dt'],'-')?></a></td>
                                        <td height="15" align="left"  class="p-2">&nbsp;<?php echo $report_row['payee_payer_name']?></td>
                                        <td height="15" align="right" class="p-2"><?php echo $report_row['amount'] ?>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="15" align="left"  class="p-2">&nbsp;</td> 
                                        <td height="15" align="left"  class="p-2" colspan="4">&nbsp;<?php echo $report_row['bank_name']?></td>
                                    </tr>
                <?php     
                            $lineno = $lineno + 2;
                            $totamt = $totamt + $report_row['amount'] ;                   
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $unclr_credit_sql[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                        }  
                    }
                ?>
                                    <tr class="fs-14">
                                        <td height="20" align="left"  class="p-2" style="background-color:#e2e6506e;">&nbsp;</td>
                                        <td height="20" align="left"  class="p-2" style="background-color:#e2e6506e;">&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#e2e6506e;">&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#e2e6506e;"><b>TOTAL</b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#e2e6506e;"><b><?php echo number_format($totamt,2,'.','') ?></b>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            </tr>
                        </table> 
                        <BR CLASS="pageEnd"> 
                <?php
                    }
                ?>	 
            <!---------------------  Uncleared Payments ------------------- -->  
            <?php if ($params['statement_no'] == '1' || $params['statement_no'] == '4') { 
                //----- 
                $maxline = 66 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $totamt  = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($unclr_debit_sql[$rowcnt-1]) ? $unclr_debit_sql[$rowcnt-1] : '' ; 
                $report_cnt = $params['unclr_debit_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                    $pdocdt = $report_row['doc_date'] ;
                    while ($pdocdt == $report_row['doc_date'] && $rowcnt <= $report_cnt)
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
                        <table width="750" align="center" class="table border-0" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="border-0" colspan="5">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b>SINHA AND COMPANY</b></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b><u>[<?php echo strtoupper($params['report_desc'])?>]</u></b></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">&nbsp;Bank</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['daybook_name']?></b></td>
                                        <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['lstdate'];?></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">&nbsp;Period</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc']?></b></td>
                                        <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">&nbsp;Subject</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b>CHEQUES ISSUED NOT DEBITED BY BANK</b></td>
                                        <td class="report_label_text">&nbsp;</td>
                                        <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    </table>
                                </td>    
                            </tr>
                            <tr class="fs-14">
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Date</th>
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Chq#</th>
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Chq Dt</th>
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Pay To</th>
                                <th width="" align="right" class="py-3 px-2">Amount&nbsp;</th>
                            </tr>
                <?php
                            $lineno = 8 ;
                            }
                ?>
                                        <tr class="fs-14">
                                            <td height="15" align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['doc_date'],'-')?></td> 
                                            <td height="15" align="left"  class="p-2">&nbsp;<?php echo $report_row['instrument_no']?></td>
                                            <td height="15" align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['instrument_dt'],'-')?></a></td>
                                            <td height="15" align="left"  class="p-2">&nbsp;<?php echo $report_row['payee_payer_name']?></td>
                                            <td height="15" align="right" class="p-2"><?php echo $report_row['amount'] ?>&nbsp;</td>
                                        </tr>
                <?php     
                            $lineno = $lineno + 1;
                            $totamt = $totamt + $report_row['amount'] ;                   
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $unclr_debit_sql[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                        }  
                    }
                ?>
                                    <tr class="fs-14">
                                        <td height="20" align="left"  class="p-2" style="background-color:#e2e6506e;">&nbsp;</td>
                                        <td height="20" align="left"  class="p-2" style="background-color:#e2e6506e;">&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#e2e6506e;">&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#e2e6506e;"><b>TOTAL</b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#e2e6506e;"><b><?php echo number_format($totamt,2,'.','') ?></b>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            </tr>
                        </table> 
                        <BR CLASS="pageEnd"> 
                <?php
                    }
                ?>	 
            <!---------------------  Other Transactions ------------------- -->  
            <?php if ($params['bank_drcr_cnt'] > 0 && ($params['statement_no'] == '1' || $params['statement_no'] == '5')) { 
                //----- 
                $maxline = 66 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tdramt  = 0; 
                $tcramt  = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($bank_drcr_sql[$rowcnt-1]) ? $bank_drcr_sql[$rowcnt-1] : '' ;
                $report_cnt = $params['bank_drcr_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                    $pdocdt = $report_row['trans_date'] ;
                    while ($pdocdt == $report_row['trans_date'] && $rowcnt <= $report_cnt)
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
                        <table width="750" align="center" class="table border-0" border="" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="border-0" colspan="4">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b>SINHA AND COMPANY</b></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b><u>[<?php echo strtoupper($params['report_desc'])?>]</u></b></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">&nbsp;Bank</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['daybook_name']?></b></td>
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
                                        <td class="report_label_text">&nbsp;Subject</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b>DEBITED/CREDIDTED BY BANK [NOT SHOWN IN BOOK]</b></td>
                                        <td class="report_label_text">&nbsp;</td>
                                        <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    </table>
                                </td>    
                            </tr>
                            <tr class="fs-14">
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Date</th>
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Narration</th>
                                <th width="" align="left"  class="py-3 px-2">&nbsp;Credit</th>
                                <th width="" align="right" class="py-3 px-2">Debit&nbsp;</th>
                            </tr>
                <?php
                            $lineno = 8 ;
                            }
                ?>
                                        <tr class="fs-14">
                                            <td height="15" align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['trans_date'],'-')?></td> 
                                            <td height="15" align="left"  class="p-2">&nbsp;<?php echo $report_row['narration']?></td>
                                            <td height="15" align="right" class="p-2"><?php if($report_row['dr_cr_ind']=='C') echo $report_row['amount'] ; else echo '&nbsp;' ; ?>&nbsp;</td>
                                            <td height="15" align="right" class="p-2" ><?php if($report_row['dr_cr_ind']=='D') echo $report_row['amount'] ; else echo '&nbsp;' ; ?>&nbsp;</td>
                                        </tr>
                <?php     
                            $lineno = $lineno + 1;
                            if ($report_row['dr_cr_ind'] == 'D') { $tdramt = $tdramt + $report_row['amount'] ; } else { $tcramt = $tcramt + $report_row['amount'] ; }                  
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $bank_drcr_sql[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                        }  
                    }
                ?>
                                    <tr class="fs-14">
                                        <td height="20" align="left"  class="p-2" style="background-color:#e2e6506e;">&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#e2e6506e;"><b>TOTAL</b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#e2e6506e;"><b><?php if($tcramt > 0.00) echo number_format($tcramt,2,'.','') ; else echo '&nbsp;' ; ?></b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#e2e6506e;"><b><?php if($tdramt > 0.00) echo number_format($tdramt,2,'.','') ; else echo '&nbsp;' ; ?></b>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            </tr>
                        </table> 
                <?php
                    }
                ?>	 
        </form>
    </main>
    <?php } ?>
<script>
    function setValue(e) {
        e.preventDefault();
        console.log(document.bankStatement);
        var cmm = document.bankStatement.fin_month.value ;
        var fyy = document.bankStatement.fin_year.value ;
        if (cmm <= '03') { var cyy = fyy.substr(5,4) ; } else { var cyy = fyy.substr(0,4) ; }
        var cym = cyy + cmm ;  

        if (cym > document.bankStatement.curr_yyyymm.value){
            Swal.fire({ html: 'Selected Year/Month must be <b> less than or equal to </b> Current Year/Month ...' }).then((result) => { setTimeout(() => {document.bankStatement.fin_year.focus()}, 500) });
            return false;
        }
        document.bankStatement.submit();
	}
</script><!-- End #main -->

<?= $this->endSection() ?>