<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?= view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">
    <div class="pagetitle d-inline-block w-100">
        <h1 class="col-md-11 float-start">Courier Expenses [Edit]</h1>
        <a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn btn-primary cstmBtn btn-cncl mt-2">Back</a>
    </div><!-- End Page Title -->

    <?php if(!isset($print)) { ?> 
        <section class="section dashboard">
            <div class="row">
                <form action="" name="f1" method="post">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            <div class="d-block float-start w-75">
                                <?php if($user_option != 'Add') { ?>	
                                <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Serial#</label>
                                    <input type="text" class="form-control" name="voucher_serial_no" value="<?= $params['voucher_serial_no'] ?>" readonly>
                                </div>
                                <?php } ?>
                                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Branch  <strong class="text-danger">*</strong></label>
                                    <select class="form-select cstm-inpt" name="branch_code" onClick="pass_close()" onBlur="pass_close()">
                                        <?php foreach($data['branches'] as $branch) { ?>
                                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : $disk ?>><?= $branch['branch_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Year <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="fin_year" value="<?= $params['fin_year'] ?>" readonly />
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Date <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1 datepicker" name="voucher_serial_date" value="<?= $params['voucher_serial_date'] ?>" onBlur="make_date(this)" <?php if($selemode == 'Y') { echo 'readonly' ; }?> />
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Type <strong class="text-danger">*</strong></label>					
                                    <select class="form-select" name="payee_type_str" id="payeeType" onchange="cleanData(this, 'payeeCode', '', 'payeeCodeLookup'); document.getElementById('payeeTypeSlNo').value = this.value.slice(2, this.value.length); document.getElementById('payeeTypeCode').value = this.value.slice(0, 1);" tabindex="4">
                                        <option value=""  <?php if($params['payee_type_str'] == '' ) { echo 'selected'; } else {echo $disk;} ?>>-- Select --</option>
                                        <option value="C|001" <?php if($params['payee_type'] == 'C') { echo 'selected'; } else {echo $disk;} ?>>Counsel</option>
                                        <option value="K|002" <?php if($params['payee_type'] == 'K') { echo 'selected'; } else {echo $disk;} ?>>Clerk</option>
                                        <option value="A|003" <?php if($params['payee_type'] == 'A') { echo 'selected'; } else {echo $disk;} ?>>Arbitrator</option>
                                        <option value="T|004" <?php if($params['payee_type'] == 'T') { echo 'selected'; } else {echo $disk;} ?>>Stenographer</option>
                                    </select>
                                    <input type="hidden" id="payeeTypeSlNo">
                                    <input type="hidden" id="payeeTypeCode">
                                </div>
                                <div class="col-md-12 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Payee Name <strong class="text-danger">*</strong></label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="payee_name" id="payeeName" value="<?= $params['payee_name'] ?>" readonly>
                                    <input type="hidden" size="06" maxlength="06" name="payee_code" id="payeeCode" value="<?= $params['payee_code'] ?>" readonly>
                                    <?php if($selemode!='Y') { ?> <i class="fa-solid fa-binoculars icn-vw top40" id="payeeCodeLookup" onclick="showData('associate_name', 'display_id=<?= $displayId['payee_name_code'] ?>&myPayeeType=@payeeTypeSlNo', 'payeeName', ['payeeCode'], ['associate_code'], '');" title="View" data-toggle="modal" data-target="#lookup"></i> <?php } ?>			
                                </div>
                                <div class="col-md-6 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Advance# <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-stat me-1" name="advance_serial_no" id="payeePayerCode" value="<?= $params['advance_serial_no'] ?>" readonly/>
                                    <?php if($selemode!='Y') { ?> <i class="fa-solid fa-binoculars icn-vw top40" id="payeeCodeLookup" onclick="showData('payee_payer_name', 'display_id=<?= $displayId['advanced_code'] ?>&myPayeeType=@payeeTypeCode&myPayeeCode=@payeeCode', 'payeePayerCode', [], [], '');" title="View" data-toggle="modal" data-target="#lookup"></i> <?php } ?>			
                                </div>
                                <div class="col-md-6 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Voucher Date </label>			
                                    <input class="form-control w-100 float-stat me-1" type="text" name="voucher_date" value="<?= $params['voucher_date'] ?>" onBlur="make_date(this)" <?php if($user_option!='Approve') { echo 'disabled'; } ?>> 
                                    <input type="hidden" name="current_date" value="<?= date('d-m-Y') ?>">
                                    <input type="hidden" name="finyr_start_date" value="<?= $params['finyr_start_date'] ?>">
                                    <input type="hidden" name="finyr_end_date" value="<?= $params['finyr_end_date'] ?>">	
                                </div>
                                <div class="d-inline-block w-100 mt-3">			
                                    <input type="hidden" name="memdtl_cnt" value="<?= $memdtl_cnt ?>">
                                    <input type="hidden" name="user_option" value="<?= $user_option ?>">
                                    <input type="hidden" name="selemode" value="Y">
                                    <input type="hidden" name="action" value="Y">
                                    <?php if($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Delete' || $user_option == 'Print' || $user_option == 'Approve') { 
                                    if($selemode != 'Y' && $user_option == 'Add') { ?>
                                        <button type="button" onclick="document.f1.action.value = 'Proceed'; document.f1.submit();" class="btn btn-primary cstmBtn ms-2" <?php echo $disb1?>>Proceed</button>				
                                    <?php } else { ?>
                                        <button type="submit" class="btn btn-primary cstmBtn ms-2" <?php echo $disb3?>>Confirm</button>				
                                        <button type="submit" class="btn btn-primary cstmBtn ms-2" <?php echo $disb4?>>Print</button>			
                                        <button type="reset" class="btn btn-primary cstmBtn ms-2" <?php echo $disb2?>>Reset</button>
                                    <?php } } ?>
                                </div>
                            </div>
                            <div class="d-block float-start w-25 rgtSecAll">
                                <div class="col-md-12 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Advance <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="advance_amount" value="<?= number_format($params['advance_amount'], 2, '.', '') ?>" readonly />
                                </div>
                                <div class="col-md-12 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Adjusted <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="adjusted_amount" value="<?= number_format($params['adjusted_amount'], 2, '.', '') ?>" readonly />
                                </div>
                                <div class="col-md-12 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Balance <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="balance_amount" value="<?= number_format($params['balance_amount'], 2, '.', '')?>" readonly />
                                </div>
                                <div class="col-md-12 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Adjusted Now <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1 input-bg text-dark" name="now_adjusted_amount" value="<?php echo number_format($params['now_adjusted_amount'], 2, '.', '')?>" readonly />
                                    <input type="hidden" name="old_adjusted_amount" value="<?php echo number_format($params['now_adjusted_amount'], 2, '.', '')?>" readonly />
                                </div>
                            </div>
                            
                        </div>
                        <?php if($selemode == 'Y') { ?>
                            <div class="d-inline-block w-100 mt-2" id="listTable">					
                                <table class="table table-bordered tblePdngsml">
                                    <tr class="fs-14">
                                        <th>Serial</th>
                                        <th>Memo No</th>
                                        <th>Memo Date</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Adj?</th>
                                        <th>Adjust Now</th>
                                    </tr>
                                    <?php $t = 10; $j = 1; foreach($memdtl_qry as $memdtl_row) { ?>
                                    <tr> 
                                        <td align="right" ><input class="form-control" name="memo_serial_no<?php echo $j?>" value="<?php echo $memdtl_row['serial_no']?>"    readonly></td>
                                        <td align="left"  ><input class="form-control" name="memo_no<?php echo $j?>" value="<?php echo $memdtl_row['memo_no']?>"      readonly></td>
                                        <td align="left"  ><input class="form-control" name="memo_date<?php echo $j?>" value="<?php echo $memdtl_row['memo_date']?>"    readonly></td>
                                        <td align="right" ><input class="form-control" name="memo_amount<?php echo $j?>" value="<?php echo number_format($memdtl_row['memo_amount'],2,'.','')?>"  readonly></td>
                                        <td align="right" ><input class="form-control" name="paid_amount<?php echo $j?>" value="<?php echo number_format($memdtl_row['paid_amount'],2,'.','')?>"  readonly></td>
                                        <td align="right" ><input class="form-control" name="baln_amount<?php echo $j?>" value="<?php echo number_format($memdtl_row['baln_amount'],2,'.','')?>"  readonly></td>
                                        <td align="center"><input class="" type="checkbox" name="cadj_ind<?php echo $j?>" value="Y" tabindex="<?php echo ($t+1)?>" onClick="getAdjAmt(<?php echo $j?>); calc_total_adjamt()" <?php if($memdtl_row['cadj_amount']>0) {echo 'checked';}?> <?php echo $disv?>></td>
                                        <td align="right" >
                                            <input class="accept_number_item form-control"  type="text"   size="13" maxlength="12" name="cadj_amount<?php echo $j?>"     value="<?php if($memdtl_row['cadj_amount']>0) {echo number_format($memdtl_row['cadj_amount'],2,'.','');}?>" tabindex="<?php echo ($t+2)?>" onBlur="setAdjInd(<?php echo $j?>); calc_total_adjamt()"  <?php echo $redv?>>
                                            <input class="display_number_item" type="hidden" size="12" maxlength="12" name="old_cadj_amount<?php echo $j?>" value="<?php echo number_format($memdtl_row['cadj_amount'],2,'.','')?>" readonly>
                                        </td>
                                    </tr> 
                                    <?php $t+=2; $j++ ; 
                                    } if ($j == 1) { ?>
                                        <tr>
                                            <td colspan="8">No Records Found!!</td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </section>
    <?php } else { echo view('pages/OtherExpenses/common_print_expenses'); } ?>
</main><!-- End #main -->

<?php if(!isset($print)) { ?> 
<script>
    //--------
    function getAdjAmt(rowno)
	  {
        if (eval("document.f1.cadj_ind"+rowno+".checked==true")) { var adjamt = eval("document.f1.baln_amount"+rowno+".value") ; } else { var adjamt = '' ; }
		//  
		eval("document.f1.cadj_amount"+rowno+".value='"+adjamt+"'"); 
        //
		calc_total_adjamt();
      }

      //--------
      function setAdjInd(rowno)
	  {
        if (eval("document.f1.cadj_amount"+rowno+".value > 0")) { eval("document.f1.cadj_ind"+rowno+".checked = true") ; format_number(eval("document.f1.cadj_amount"+rowno),2) ;  } else { eval("document.f1.cadj_ind"+rowno+".checked = false") ; }
      }

      //--------
      function calc_total_adjamt()
	  {
         var memocnt  = document.f1.memdtl_cnt.value*1 ; 
		 var memotot  = 0 ;
		 for (i=1; i<=memocnt; i++)
		 {
           if (eval("document.f1.cadj_ind"+i+".checked==true") && eval("document.f1.cadj_amount"+i+".value > 0"))
		   {
               memotot += eval("document.f1.cadj_amount"+i+".value")*1 ;
           }
         }
         // 
		 document.f1.now_adjusted_amount.value = memotot ;  format_number(document.f1.now_adjusted_amount, 2) ; 
      }
</script>
<?php } ?>

<?= $this->endSection() ?>