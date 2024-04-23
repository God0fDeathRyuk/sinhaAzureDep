<?= $this->extend("layouts/master") ?>  

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">

    <?php if (session()->getFlashdata('message') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success_message') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('success_message') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(ucfirst($user_option) == '' || ucfirst($user_option) == 'Search') { ?>
        <div class="pagetitle">
        <h1>Bank Reconciliation [Entry]</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="bankReconciliationEntry" name="bankReconciliationEntry" onsubmit="setValue(event)">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn"> Bank <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="db_code" onBlur="mydbfunc()" <?= ($selemode == 'Y') ? 'disabled' : ''?> required>
                                <option value="">--Select--</option>
                                <?php foreach($daybook_qry as $daybook_row) { ?>
                                    <option value="<?php echo $daybook_row['daybook_code']?>" <?= ($selemode == 'Y') ? ($params['db_code'] == $daybook_row['daybook_code']) ? 'selected' : '' : '' ?>><?php echo $daybook_row['daybook_desc'].' [DB '.$daybook_row['daybook_code'].']';?></option>
                                <?php } ?>	
                            </select>
                            <input type="hidden" size="03" maxlength="02" name="daybook_code" value="<?= ($selemode == 'Y') ? $params['daybook_code'] : '' ?>">
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Year <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="fin_year" <?= ($selemode == 'Y') ? 'disabled' : ''?> required>
                                <option value="">--Select--</option>
                                <?php foreach($finyr_qry as $finyr_row) { ?>
                                    <option value="<?php echo $finyr_row['fin_year']?>" <?php if($fin_year == $finyr_row['fin_year']) { echo 'selected' ; }?>><?php echo $finyr_row['fin_year']?></option>
                                <?php } ?>		
                            </select>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Month <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="month_no" id="month_no" <?= ($selemode == 'Y') ? 'disabled' : ''?> required>
                                <option value="">--Select--</option>
                                <?php foreach($month_qry as $month_row) { ?>
                                    <option value="<?php echo $month_row['month_no']?>" <?php if($month_no == $month_row['month_no']) { echo 'selected' ; }?>><?php echo $month_row['month_descl']?></option>
                                <?php } ?>	
                            </select>
                        <input type="hidden" size="06" maxlength="10" name="reco_yymm"   value="">
                        <input type="hidden" size="07" maxlength="10" name="current_date" id="current_date" value="<?php echo date('Y-m-d')?>">
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Type <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="rec_type" onBlur="myrectypefunc()" <?= ($selemode == 'Y') ? 'disabled' : ''?> required>
                                <option value="U" <?php if($rec_type == 'U') { echo 'selected' ; }?>>Un-Reconciled</option>
                                <option value="R" <?php if($rec_type == 'R') { echo 'selected' ; }?>>Reconciled</option>
                                <option value="A" <?php if($rec_type == 'A') { echo 'selected' ; }?>>All</option>
                            </select>
                        <input type="hidden" size="03" maxlength="01" name="trans_type" value="<?php echo $trans_type?>">
                        <input type="hidden" size="03" maxlength="01" name="selemode" value="Y">
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Last Reconciled</label>
                            <input type="text" class="form-control" placeholder="" name="last_recon_date" id="last_recon_date" value="<?= ($selemode == 'Y') ? $params['last_recon_date'] : ''?>" readonly/>
                        </div>
                        <?php if($selemode != 'Y') { ?>
                        <div class="frms-sec-insde d-block float-start col-md-4 px-2 mt-3">
                            <button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="formOption('/bank-reconciliation/entry/', 'search', 'bankReconciliationEntry')">Search</button>
                            <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
        <?php if($selemode == 'Y' && ucfirst($user_option) == 'Search') { ?>
            <form action="" method="post" id="bankReconciliationTable" name="bankReconciliationTable">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                <input type="hidden" size="03" maxlength="01" name="selemode" value="Y">
                <input type="hidden" name="db_code" value="<?= $params['db_code'] ?>">
                <input type="hidden" name="daybook_code" value="<?= $params['daybook_code'] ?>">
                <input type="hidden" name="reco_yymm" value="<?= $params['reco_yymm'] ?>">
                <input type="hidden" name="trans_type" value="<?= $params['trans_type'] ?>">
                <input type="hidden" name="recon_cnt" value="<?= $params['recon_cnt'] ?>">
                <p class="d-inline-block w-100 my-2 bnd">Transiction Details</p>
                <div class="d-inline-block w-100 NwTbl_lrg_Scrl tblscrlvtrcllrg">
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr class="fs-14">
                                <th class="wd100">Doc No</th>
                                <th class="w-150">Chq No</th>
                                <th class="w-150">Chq Date</th>
                                <th class="w-250">Paid To / Received From</th>
                                <th class="w-150">Deposit</th>
                                <th class="w-150">Withdrawal</th>
                                <th class="w-5">&nbsp;</th>
                                <th class="w-150">Clear Date</th>
                            </tr>
                        </thead>
                    <tbody>
                    <?php
                        $i = 0; $t = 11;
                        foreach($recon_qry as $recon_row)
                        {
                        $i++;
                        if ($recon_row['clear_date'] == '0000-00-00' || $recon_row['clear_date'] == '') 
                        {
                            $cleared_date = '' ; $cleared_ind = 'N' ; $cleared_desc = '' ; 
                        } 
                        else
                        {
                            $cleared_date = date_conv($recon_row['clear_date'],'/') ; $cleared_ind = 'Y' ; $cleared_desc = 'checked' ; 
                        } 
                        ?>
                        <tr> 
                        <td align="left">
                            <input class="form-control"   type="text" size="04"  maxlength="06" name="doc_no<?php echo $i?>"   value="<?php echo $recon_row['doc_no']?>"  readonly>
                        </td>
                        <td align="left">
                            <input class="form-control"   type="text" size="04"  maxlength="06" name="instrument_no<?php echo $i?>"   value="<?php echo $recon_row['instrument_no']?>"  readonly>
                        </td>
                        <td align="left">
                            <input class="form-control"   type="text" size="07"  maxlength="10" name="instrument_dt<?php echo $i?>" id="instrument_dt<?php echo $i?>" value="<?php echo date_conv($recon_row['instrument_dt'],'/')?>"  readonly>
                        </td>
                        <td align="left">
                            <input class="form-control"   type="text" size="59"  maxlength="50" name="payee_payer_name<?php echo $i?>"  value="<?php echo $recon_row['payee_payer_name']?>" readonly>
                        </td>
                        <td align="right">
                            <input class="form-control" type="text" size="09"  maxlength="12" name="debit_amt<?php echo $i?>"  value="<?php echo $recon_row['credit_amt']?>"  readonly>
                        </td>
                        <td align="right">
                            <input class="form-control" type="text" size="09"  maxlength="12" name="credit_amt<?php echo $i?>" value="<?php echo $recon_row['debit_amt']?>" readonly>
                        </td>
                        <td align="center" class="align-middle">
                            <input type="checkbox" class="tblcbx float-none" name="cleared_ind<?php echo $i?>" id="cleared_ind<?php echo $i?>" value='Y' onClick="myclearindfunc(<?php echo $i?>)" <?php echo $cleared_desc?>>
                        </td>
                        <td align="right">
                            <input class="form-control" type="text"   size="08"  maxlength="10" name="cleared_date<?php echo $i?>" id="cleared_date<?php echo $i?>" value="<?php echo $cleared_date?>" onBlur="mycleardatefunc(<?php echo $i?>)">
                            <input class="accept_text_item" type="hidden" size="10"  maxlength="13" name="serial_no<?php echo $i?>"   value="<?php echo $recon_row['serial_no']?>">
                        </td>
                        </tr>
                        <?php } ?>
                    </table>                    
                </div>
                <div class="frms-sec-insde d-block clearfix col-md-12">
                    <button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="formOption('/bank-reconciliation/entry/', 'print', 'bankReconciliationTable')">Print</button>
                    <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" onclick="formOption('/bank-reconciliation/entry/', 'confirm', 'bankReconciliationTable')">Confirm</button>
                    <a href="<?= $params['requested_url'] ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Cancel</a>
                </div>
            </form>
    <?php } } ?>
    <?php if($selemode == 'Y' && ucfirst($user_option) == 'Print') { ?>
        <form action="" method="post" id="bankReconciliationPrint" name="bankReconciliationPrint">
            <div class="position-absolute btndv">
                 <a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
            </div>
           
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            <?php
                $maxline = 40 ;
                $lineno  = 7 ;
                $pageno  = 1 ;
                $tcredit_amt  = 0;
                $tdebit_amt   = 0;
            ?> 
            <table width="950" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="8">    
                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                        
                        <tr>
                            <td class="report_label_text" colspan="4" align="center"><b> SINHA AND COMPANY </b></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Bank</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['daybook_name'] .' [DB '.$params['daybook_code'].']';?></b></td>
                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y')?></td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Year/Month</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['reco_yymm']?></b></td>
                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                    <th height="18" width=""  align="right" class="py-3 px-2">Sl&nbsp;</th>
                    <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Doc No</th>
                    <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Chq No</th>
                    <th height="18" width="" align="left"  class="py-3 px-2 w-12">&nbsp;Chq Date</th>
                    <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Paid To / Received From</th>
                    <th height="18" width=""  align="left"  class="py-3 px-2">&nbsp;Deposit</th>
                    <th height="18" width=""  align="left"  class="py-3 px-2">&nbsp;Withdrawal</th>
                    <th height="18" width="" align="left"  class="py-3 px-2 w-15">&nbsp;Clear Date</th>
                </tr>
                        

                <?php $xsrl = 0;  ?>
                <?php
                if ($lineno == 0 || $lineno > $maxline) {
                    if($lineno > $maxline) { 
                        $lineno = 0;
                        $pageno = $pageno + 1 ;
                ?>
                    </table>
                    </td>
                </tr>
            </table>
            <BR CLASS="pageEnd"> 

            <table width="950" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="8">    
                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="report_label_text" colspan="4" align="center"><b> SINHA AND COMPANY </b></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Bank</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['daybook_name'] .' [DB '.$params['daybook_code'].']';?></b></td>
                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y')?></td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Year/Month</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['reco_yymm']?></b></td>
                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                    <th height="18" width=""  align="right" class="PY-3 PX-2">Sl&nbsp;</th>
                    <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Doc No</th>
                    <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Chq No</th>
                    <th height="18" width="" align="left"  class="py-3 px-2 w-12">&nbsp;Chq Date</th>
                    <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Paid To / Received From</th>
                    <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Deposit</th>
                    <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Withdrawal</th>
                    <th height="18" width="" align="left"  class="py-3 px-2 w-15">&nbsp;Clear Date</th>
                </tr>
                

                    <?php } } ?> 
                
                    <tbody>
                        <?php
                        $i = 0; $t = 11;
                        foreach($recon_qry as $recon_row)
                        {
                            // echo '<pre>';print_r($recon_qry);die;
                            $i++;
                            if ($recon_row['clear_date'] == '0000-00-00' || $recon_row['clear_date'] == '') {
                                $cleared_date = 'Un-Reconciled' ; $cleared_ind = 'N' ; $cleared_desc = 'Un-Reconciled' ; 
                            } else {
                                $cleared_date = date_conv($recon_row['clear_date'],'/') ; $cleared_ind = 'Y' ; $cleared_desc = 'checked' ; 
                            } 

                            $xsrl++;
                        ?>
                        <?php if($recon_row['clear_date'] == '0000-00-00' || $recon_row['clear_date'] == '')
                            $a="#FF99CC";
                            else
                            $a= "#FFFFFF";?>
                        <tr class="fs-14">
                            <td height="32" align="right" class="p-2"><?php echo $xsrl ?>&nbsp;</td>
                            <td align="left"   class="p-2">&nbsp;<?php echo $recon_row['doc_no'];?></td>
                            <td align="center" class="p-2"><?php if ($recon_row['instrument_no'] == '') {echo ' -- ';} else {echo $recon_row['instrument_no'];}?></td>
                            <td align="right"  class="p-2"><?php echo date_conv($recon_row['instrument_dt'],'/');?></td> 
                            <td align="left"   class="p-2">&nbsp;<?php echo $recon_row['payee_payer_name'];?></td>
                            <td align="right"  class="p-2"><?php echo ($recon_row['credit_amt'] == '') ? '0.00' : $recon_row['credit_amt'];?></td>
                            <td align="right"  class="p-2"><?php echo ($recon_row['debit_amt'] == '') ? '0.00' : $recon_row['debit_amt'];?></td>
                            <td align="right"  class="p-2"><?php  echo $cleared_date;?></td>
                        </tr>

                        <?php
                            $lineno =  $lineno +1;
                            $tcredit_amt = (int)$tcredit_amt + (int)$recon_row['credit_amt'];
                            $tdebit_amt  = (int)$tdebit_amt + (int)$recon_row['debit_amt'];
                        } ?>
                        <tr class="fs-14">
                            <td align="right"  class="p-2" style="background-color:#cbe9f3;">&nbsp;</td>
                            <td height="18" colspan="4" align="center"  class="p-2" style="background-color:#cbe9f3;"><b> &nbsp;&nbsp;Grand Total&nbsp;&nbsp;</b></td>
                            <td align="right"  class="p-2" style="background-color:#cbe9f3;"><b><?php echo number_format($tcredit_amt,2,'.','');?></b></td>
                            <td align="right"  class="p-2" style="background-color:#cbe9f3;"><b><?php echo number_format($tdebit_amt,2,'.','');?></b></td>

                            <td align="right"  class="p-2" style="background-color:#cbe9f3;">&nbsp;</td>
                        </tr>
                    </tbody>
                    </table>
                    </td>
                </tr>
            </table>
        </form>
    <?php } ?>
</main><!-- End #main -->
<script>
    function mydbfunc() {
        document.bankReconciliationEntry.daybook_code.value = document.bankReconciliationEntry.db_code.value ;
    } 
    function myrectypefunc() {
        document.bankReconciliationEntry.trans_type.value = document.bankReconciliationEntry.rec_type.value ;
    }
    function setValue(e) {
        e.preventDefault();
        console.log(document.bankReconciliationEntry);
        var currdt   = document.bankReconciliationEntry.current_date.value ;
        var currym   = currdt.substr(0,4) + currdt.substr(5,2) ;
        
        var recomm   = document.bankReconciliationEntry.month_no.value ;
        var recoyr   = document.bankReconciliationEntry.fin_year.value ;
        if (recomm < '04') { var recoyy = recoyr.substr(5,4) ; } else { var recoyy = recoyr.substr(0,4) ; }
        var recoym   = recoyy + recomm  ;
        document.bankReconciliationEntry.reco_yymm.value = recoym ;
        // document.bankReconciliationEntry.branch_code.value = document.bankReconciliationEntry.db_code.value ;
        document.bankReconciliationEntry.trans_type.value = document.bankReconciliationEntry.rec_type.value ;

        if (recoym > currym){
            Swal.fire({ text: 'Year/Month must be less than or equal to Current Year/Month ...' }).then((result) => { setTimeout(() => {document.bankReconciliationEntry.fin_year.focus()}, 500) });
            return false;
        }
        document.bankReconciliationEntry.submit();
	}
    function myclearindfunc(posindex) {
        if (document.getElementById("cleared_ind"+posindex).checked == true) {
           document.getElementById("cleared_date"+posindex).value = document.getElementById("last_recon_date").value ;
        } else {
           document.getElementById("cleared_date"+posindex).value = ''  ;
        }
	} 
    function mycleardatefunc(posindex) {
		if (document.getElementById("cleared_date"+posindex).value != '') {
			make_date(document.getElementById("cleared_date"+posindex)); 
			//
			var clrdt = document.getElementById("cleared_date"+posindex).value ;  
			var clrdd  = clrdt.substr(0,2) ;
			var clrmm  = clrdt.substr(3,2) ;
			var clryy  = clrdt.substr(6,4) ;
			var clrymd = clryy + '-' + clrmm + '-' + clrdd ;
			//
			var chqdt  = document.getElementById("instrument_dt"+posindex).value ;
			var chqdd  = chqdt.substr(0,2) ;
			var chqmm  = chqdt.substr(3,2) ;
			var chqyy  = chqdt.substr(6,4) ;
			var chqymd = chqyy + '-' + chqmm + '-' + chqdd ;  
            console.log(chqymd);   
			//
			var lrcdt  = document.getElementById("last_recon_date").value ;
			var lrcdd  = lrcdt.substr(0,2) ;
			var lrcmm  = lrcdt.substr(3,2) ;
			var lrcyy  = lrcdt.substr(6,4) ;
			var lrcymd = lrcyy + '-' + lrcmm + '-' + lrcdd ;  
			//
			if (clrymd > document.getElementById("current_date").value) {
				Swal.fire({ text: 'Clear Date must be less than or equal to Current Date ...' }).then((result) => { setTimeout(() => {document.getElementById("cleared_ind"+posindex).focus()}, 500) });
			    document.getElementById("cleared_date"+posindex).value='' ;
				document.getElementById("cleared_ind"+posindex).checked = false ;
				return false;
			}	  
			else if (clrymd < chqymd) 
			{
				Swal.fire({ text: 'Clear Date must be greater than or equal to Cheque Date ...' }).then((result) => { setTimeout(() => {document.getElementById("cleared_ind"+posindex).focus()}, 500) });
				document.getElementById("cleared_date"+posindex).value='' ;
				document.getElementById("cleared_ind"+posindex).checked=false ;
				//document.getElementById("billsend_ind"+posindex).focus();
				return false;
			}	  
			else 
			{
				if (clrymd > lrcymd)
				{ //alert(clrymd);
				//alert(lrcymd);
				document.getElementById("last_recon_date").value = document.getElementById("cleared_date"+posindex).value ;
				}
			}	  
		}
	} 
</script>
<?= $this->endSection() ?>