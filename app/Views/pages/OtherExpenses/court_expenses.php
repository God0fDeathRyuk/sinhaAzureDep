<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">
	<?php if (session()->getFlashdata('message') !== NULL) : ?>
		<div id="alertMsg">
			<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
			<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

  <div class="pagetitle col-md-12 float-start border-bottom pb-1"> <h1>Photocopy Expenses (<?= ucfirst($user_option) ?>)</h1> </div>
  <?php if(!isset($print)) { ?> 
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12 mt-2">
                <form action="" name="courtExpForm" method="post" onsubmit="return myConfirm()">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                        <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch</label>
                            <select class="form-select cstm-inpt" name="branch_code">
                            <?php foreach($data['branches'] as $branch) { ?>
                            <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Code <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control w-75 float-start" name="employee_name" id="employeeName" value="<?= $params['employee_name']?>" readonly/>
                            <input type="text" class="form-control w-22 ms-2 float-start" style="top:20px;" name="employee_id" id="employeeId" value="<?= $params['employee_id']?>" readonly/>
                            <?php if($user_option=='Add') { ?>
                              <i class="fa-solid fa-binoculars icn-vw" style="top:38px;" id="payeeCodeLookup" onclick="showData('employee_id', '<?= $displayId['agency_help_id'] ?>', 'employeeId', ['employeeName'], ['employee_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            <?php } ?>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Expn Date <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" name="exp_date" value="<?php echo $exp_date ?>" onBlur="make_date(this); myExpDate()" <?php echo $redv?> />
                            <input type="hidden" class="form-control datepicker" placeholder="dd-mm-yyyy" name="memo_date" value="<?php echo $exp_date ?>" onBlur="make_date(this); myExpDate()" <?php echo $redv?> />
                            <input class="accept_date_mandatory" type="hidden" size="08" maxlength="10" name="current_date" value="<?= date('d-m-Y') ?>">			 </td>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Total</label>
                            <input class="form-control" type="text" name="total_amount" value="<?= number_format($total_amount,2,'.','') ?>" readonly></td>
                            <input class="form-control" type="hidden" name="status_code"  value="<?= $status_code ?>">    
                            <input class="form-control" type="hidden" name="user_option"  value="<?= $user_option ?>">    
                        </div>
        
                        <div class="d-inline-block w-100 mt-2">					
                            <table class="table table-bordered tblePdngsml mb-1">
                                <tr class="fs-14">
                                    <th>Date</th>
                                    <th>A/c</th>
                                    <th>Matter</th>
                                    <th>Client</th>
                                    <th>Narration</th>
                                    <th>Narr</th>
                                    <th>Amount</th>
                                </tr>
                                <tr>							
                                    <td class="d-none">
                                        <input class="form-control" type="hidden" name="srlno" value="<?= isset($params_opt['srlno']) ? $params_opt['srlno'] : '' ?>">
                                        <input class="form-control" type="hidden" name="rowoptn" value="<?= isset($params_opt['rowoptn']) ? $params_opt['rowoptn'] : '' ?>" readonly>
                                    </td>
                                    <td class="w-15"><input class="form-control" type="text" name="expdate" value="<?= isset($params_opt['expdate']) ? $params_opt['expdate'] : '' ?>" onBlur="make_date(this)" readonly></td>
                                    <td class="w-10">
                                        <select class="form-select" name="expfor" onChange="ExpFor()">
                                            <option value="C">C</option>
                                        </select>
                                    </td>
                                    <td class="w-15 position-relative">
                                      <input class="form-control" type="text" name="matr_code" value="<?= isset($params_opt['matr_code']) ? $params_opt['matr_code'] : '' ?>" onKeyPress="return validnumbercheck(event)" id="matterCode" onfocusout="fetchData(this, 'matter_code', ['clientCode'], ['client_code'], 'matter_code');">
                                      <i class="fa-solid fa-eye inpt-vw" style="top:20px;right:18px;" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['clientCode'], ['client_code'], '')" data-toggle="modal" data-target="#lookup"></i>
                                    </td>
                                    <td class="w-15"><input class="form-control" type="text" name="clnt_code" id="clientCode"  value="<?= isset($params_opt['clnt_code']) ? $params_opt['clnt_code'] : '' ?>"></td>
                                    <td class="w-350">
                                        <select class="form-select" name="narr">
                                            <option value="" <?= (isset($params_opt['narr']) ? $params_opt['narr'] : '' == "") ? 'selected' : '' ?>></option>
                                            <?php foreach($sele_qry as $row) { ?> 
                                                <option value="<?= $row['code_desc'] ?>" <?= (isset($params_opt['narr']) ? $params_opt['narr'] : '' == $row['code_desc']) ? 'selected' : '' ?>><?= $row['code_desc'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td class="w-10"><input class="form-control" type="text" name="narr2" value="<?= isset($params_opt['narr2']) ? $params_opt['narr2'] : '' ?>" onBlur="javascript:(this.value=this.value.toUpperCase());"></td>
                                    <td class="w-10"><input class="form-control" type="text" name="expamt" value="<?= isset($params_opt['expamt']) ? $params_opt['expamt'] : '' ?>" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2)"></td>
                                </tr>
                            </table>
                        </div>
                        <?php if($selemode == 'Y') { ?>
                        <div class="d-inline-block w-100 mt-2" id="listTable">					
                            <table class="table table-bordered tblePdngsml mb-1">
                                <tr class="fs-14">
                                    <th>Date</th>
                                    <th>A/c</th>
                                    <th>Matter</th>
                                    <th>Client</th>
                                    <th>Narration</th>
                                    <th>Narr</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                <?php $total_amount = 0; foreach($xerox_qry as $key => $xerox_row) {
                                        $j = $key + 1;
                                        // echo "<pre>"; print_r($xerox_row);
                                        $matter_desc1    = isset($xerox_row['matter_desc1']) ? $xerox_row['matter_desc1'] : '';
                                        $matter_desc2    = isset($xerox_row['matter_desc2']) ? $xerox_row['matter_desc2'] : '';
                                        $mat_description = trim($matter_desc1.' '.$matter_desc2);        
                                        $passed_amount   = ($xerox_row['passed_amount'] == 0.00) ? NULL : $xerox_row['passed_amount'];
                                  ?>
                                <tr>							
                                    <td class="w-15">
                                      <input class="form-control" type="text" name="exp_date<?= $j ?>" value="<?= date_conv($xerox_row['exp_date']) ?>" onBlur="make_date(this)" readonly>
                                      <input class="form-control" type="hidden" name="memo_date<?= $j ?>" value="<?= date_conv($xerox_row['exp_date']) ?>" onBlur="make_date(this)" readonly>
                                    </td>
                                    <td class="wd100">
                                        <select class="form-select" name="exp_for<?= $j ?>" disabled>
                                            <option value="C">C</option>
                                        </select>
                                    </td>
                                    <td class="w-250">
                                      <input class="form-control" type="text" name="matter_code<?= $j ?>" value="<?= $xerox_row['matter_code'] ?>" onKeyPress="return validnumbercheck(event)" readonly>
                                      <input class="form-control" type="hidden" name="mat_description<?= $j ?>" value="<?= $mat_description ?>" onKeyPress="return validnumbercheck(event)" readonly>
                                    </td>
                                    <td class="w-250"><input class="form-control" type="text" name="client_code<?= $j ?>" name="client_code<?= $j ?>" value="<?= $xerox_row['client_code'] ?>" readonly></td>
                                    <td class="w-350"><input class="form-control" type="text" name="description<?= $j ?>" value="<?= $xerox_row['description'] ?>" onBlur="javascript:(this.value=this.value.toUpperCase());" readonly></td>
                                    <td class="w-350"><input class="form-control" type="text" name="description2<?= $j ?>" value="<?= $xerox_row['description2'] ?>" onBlur="javascript:(this.value=this.value.toUpperCase());" readonly></td>
                                    <td class="w-150"><input class="form-control" type="text" name="amount<?= $j ?>" value="<?= $xerox_row['amount'] ?>" readonly></td>
                                    <td class="w-150 d-none"><input class="form-control" type="text" name="serial_no<?= $j ?>" value="<?= $xerox_row['serial_no'] ?>" readonly></td>
                                    <?php if($user_option != 'Generate') { ?>
                                    <td class="wd100 text-center">
                                        <a href="javascript:void(0);" title="Edit" onClick="myRowEditDele(<?= $xerox_row['serial_no'] ?>,'<?= $j ?>', 'Edit')"><i class="fa-solid fa-pen-to-square edit" aria-hidden="true"></i></a>
                                        <a href="javascript:void(0);" title="Delete" onClick="myRowEditDele(<?= $xerox_row['serial_no'] ?>,'<?= $j ?>', 'Delete')"><i class="fa-solid fa-trash delt" aria-hidden="true"></i></a>
                                    </td>
                                    <?php } ?>
                                    <td class="d-none">
                                      <input type="hidden" name="prepared_name<?= $j ?>" value="<?php  if(isset($prepared_by)) echo $prepared_by; ?>" >
                                      <input type="hidden" name="prepared_dt<?= $j ?>" value="<?php  if(isset($prepared_on)) echo $prepared_on; ?>" >
                                      <input type="hidden" name="approve_by<?= $j ?>" value="<?php  if(isset($passed_by)) echo $passed_by; ?>" >
                                      <input type="hidden" name="approve_on<?= $j ?>" value="<?php  if(isset($passed_dt)) echo $passed_dt; ?>" >
                                      <input type="hidden" name="initial_code<?= $j ?>" size="8"  value="<?php  if(isset($initial_code)) echo $initial_code; ?>" >
                                      <input type="hidden" name="del_falg<?= $j ?>" size="1" value="N">
                                      <input type="hidden" name="passed_amount<?= $j ?>" value="<?php  if(isset($passed_amount)) echo $passed_amount;?>" onBlur="chkPassedAmount(this,'<?php echo $j;?>')" >
                                    </td>
                                </tr>
                                <?php $total_amount = $total_amount + $xerox_row['amount'] ; } ?> 
                            </table>
                        </div>
                        <?php } ?>
                        <div class="d-block w-100">
                        <?php if($user_option == "Generate") { ?>
                        
                            <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
            					<label class="d-inline-block w-100 mb-1 lbl-mn">Total </label>
            					<input type="text" class="form-control" name="gross_amount" value="<?= $total_amount ?>" readonly>
            				</div>
            				<div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
            					<label class="d-inline-block w-100 mb-1 lbl-mn">% </label>
            					<input type="text" class="form-control" name="tax_amount" readonly>
            				</div>
            				<div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
            					<label class="d-inline-block w-100 mb-1 lbl-mn">Tax </label>
            					<select class="form-select w-40 float-start" name="tax_percent" onBlur="getTaxAmount()" onChange="getTaxAmount()">
                                    <option value=""></option>
                                    <?php foreach($res as $row) { ?>
                                      <option value="<?php echo $row['tax_code'].'|$|'.$row['tax_percent'];?>" title="<?php echo $row['tax_name'];?>" <?php if($row['tax_percent'] == $params['branch_code']){ echo 'selected'; }?> ><?php echo strtoupper($row['tax_name'])." - [".round($row['tax_percent'],2)."%]";?></option>
                                    <?php } ?>
                                  </select>
            					   <input class="form-control float-start w-58 ms-2" name="tax_rate" style="width:58%;" readonly>
                                  <input type="hidden" name="tax_code" size="2">
            				</div>
            				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
            					<label class="d-inline-block w-100 mb-1 lbl-mn">Net <strong class="text-danger">*</strong></label>
            					<input type="text" class="form-control" name="net_amount" readonly>
            				</div>
            				
                        
                            <!--<table class="table_style_detail" width="100%" border="0" cellspacing="0" cellpadding="0">-->
                            <!--  <tr><td colspan="5"><hr size="1"></td></tr>-->
                            <!--  <tr>-->
                            <!--    <td class="label_style_detail" width="529" align="right">&nbsp;</td>-->
                            <!--    <td class="label_style_detail" width="260" align="right"><b>Total</b>&nbsp;</td>-->
                            <!--    <td class="label_style_detail" width="10" align="right">&nbsp;</td>-->
                            <!--    <td width="105" align="right"><input type="text" class="display_int_style" name="gross_amount" value="<?= $total_amount ?>" style="width:88px"  maxlength="9" readonly></td>-->
                            <!--    <td width="76">&nbsp;</td>-->
                            <!--  </tr>-->
                            <!--  <tr>-->
                            <!--    <td class="label_style_detail" align="right"><b>Tax</b>&nbsp;</td>-->
                            <!--    <td class="label_style_detail" align="left">-->
                            <!--      &nbsp;-->
                                  
                            <!--    </td>-->
                            <!--    <td class="label_style_detail" align="right"><b>%</b></td>-->
                            <!--    <td align="right"></td>-->
                            <!--    <td>&nbsp;</td>-->
                            <!--  </tr>-->
                            <!--  <tr>-->
                            <!--    <td class="label_style_detail" align="right">&nbsp;</td>-->
                            <!--    <td class="label_style_detail" align="right"><b>Net</b>&nbsp;</td>-->
                            <!--    <td class="label_style_detail" align="left">&nbsp;</td>-->
                            <!--    <td align="right"></td>-->
                            <!--    <td>&nbsp;</td>-->
                            <!--  </tr>-->
                            <!--</table> -->
                        <?php } else { ?>			
                          <label class="d-inline-block w-100 mb-1 lbl-mn">PAN No</label>
                          <input type="text" class="form-control" name="pan_no" value="<?= $pan_no ?>" readonly/>
                        <?php } ?>			
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-12">
                            <input type="hidden" name="selemode" value="Y">
                            <input type="hidden" name="branch_code_copy" value="<?= $params['branch_code'] ?>">
                            <input type="hidden" name="arb_row_num" value="<?= isset($row_num['totalRow']) ? $row_num['totalRow'] : 0 ?>">
                            <input type="hidden" name="ref_voucher_serial_no" value="<?= $params['ref_voucher_serial_no'] ?>">

                            <button type="submit" class="btn btn-primary cstmBtn mt-3">Show Details</button>
                            <?php if ($user_option == 'Add') { ?> <button type="reset" class="btn btn-primary cstmBtn mt-3">Reset</button> <?php } ?>
                            <button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Exit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php } else { echo view('pages/OtherExpenses/common_print_expenses'); } ?>
</main><!-- End #main -->

<script>
    function myRowEditDele(param1, param2, param3) {
	    let srlno = param1, linno = param2, srlop = param3;

        document.courtExpForm.srlno.value     = srlno ;
		document.courtExpForm.rowoptn.value   = srlop ;
		document.courtExpForm.expdate.value   = document.courtExpForm['exp_date'+linno].value;
		document.courtExpForm.expfor.value    = document.courtExpForm['exp_for'+linno].value;
		document.courtExpForm.matr_code.value = document.courtExpForm['matter_code'+linno].value;
		document.courtExpForm.clnt_code.value = document.courtExpForm['client_code'+linno].value;
		document.courtExpForm.narr.value      = document.courtExpForm['description'+linno].value;
		document.courtExpForm.narr2.value      = document.courtExpForm['description2'+linno].value;
		document.courtExpForm.expamt.value    = document.courtExpForm['amount'+linno].value;

        if(srlop == 'Edit') {document.courtExpForm.expdate.disabled = false;} else {document.courtExpForm.expdate.disabled = true;}
        // document.getElementById('listTable').classList.add('d-none');
	}

    // function myConfirm() {
    //     let brchcd   = document.courtExpForm.branch_code.value ; 
    //     let emplcd   = document.courtExpForm.employee_id.value ; 
    //     let emplnm   = document.courtExpForm.employee_name.value ; 
	// 	let panno    = document.courtExpForm.pan_no.value ;
    //     let expdate  = document.courtExpForm.exp_date.value ; 
	// 	let totamt   = document.courtExpForm.total_amount.value ;
	// 	let srlno    = document.courtExpForm.srlno.value ;
	// 	let rowoptn  = document.courtExpForm.rowoptn.value ;
    //     let expdt    = document.courtExpForm.expdate.value  ; 
    //     let expfor   = document.courtExpForm.expfor.value  ; 
    //     let matrcd   = document.courtExpForm.matr_code.value  ; 
    //     let clntcd   = document.courtExpForm.clnt_code.value  ; 
	// 	let narr     = document.courtExpForm.narr.value ;
	// 	let narr2     = document.courtExpForm.narr2.value ;
	// 	let expamt   = document.courtExpForm.expamt.value;

    //     if (emplcd == '') {
    //         alert('Enter Employee Code ........'); document.courtExpForm.employee_id.focus() ; return false ;
    //     } else if (expdate == '') {
    //         alert('Enter Expense Date ........'); document.courtExpForm.exp_date.focus() ; return false ;
    //     } if (expdt == '') {
    //         alert('Enter Expense Date ........'); document.courtExpForm.expdate.focus() ; return false ;
    //     } else if (expfor == '') {
    //         alert('Enter A/c ........'); document.courtExpForm.expfor.focus() ; return false ;
    //     } else if (expfor == 'C' && matrcd == '') {
    //         alert('Enter Matter Code ........'); document.courtExpForm.matr_code.focus() ; return false ;
    //     } else if (narr == '') {
    //         alert('Enter Narration ........'); document.courtExpForm.narr.focus() ; return false ;
    //     } else if (expamt == '' || expamt <= 0) {
    //         alert('Enter Expense Amount ........'); document.courtExpForm.expamt.focus() ; return false ;
    //     } else {
    //         return true;
    //         // getData('trn_court_expense_ajax.php','branch_code='+brchcd+'&employee_id='+emplcd+'&pan_no='+panno+'&exp_date='+expdate+'&serial_no='+srlno+'&rowoptn='+rowoptn+'&exp_for='+expfor+'&matter_code='+matrcd+'&client_code='+clntcd+'&description='+narr+'&description2='+narr2+'&amount='+expamt,'','myXeroxData','') ;
    //     }
	// }

  function ExpFor() {
    if (document.courtExpForm.expfor.value == 'C') {
      document.courtExpForm.matr_code.disabled = false;
      document.courtExpForm.matr_code.focus();
    } else {
      document.courtExpForm.matr_code.disabled = true;
      document.courtExpForm.narr.focus();
      document.courtExpForm.matr_code.value = '';
      document.courtExpForm.clnt_code.value = '';
    }
  }

  function getTaxAmount() {
    calc_tax();
    calc_netAmount();
  }

  function calc_tax() {
    let gross_amt = document.courtExpForm.gross_amount.value*1;
    let tax_percent = 0.00
    let tax_rate = 0.00;
    let tax_code = "";
    let tax = "";

    if(document.courtExpForm.tax_percent.value != "") { 
        tax = document.courtExpForm.tax_percent.value;
        let text = tax.split("|$|");
        tax_code = text[0]; 
        tax_percent = text[1]*1;
    }
    tax_rate = Math.round(gross_amt * (tax_percent/100));
    document.courtExpForm.tax_rate.value = tax_percent;
    format_number(document.courtExpForm.tax_rate, 2);
    document.courtExpForm.tax_code.value = tax_code;
    document.courtExpForm.tax_amount.value = tax_rate;
    format_number(document.courtExpForm.tax_amount, 2);
  }

  function calc_netAmount() {
    let gross_amt = document.courtExpForm.gross_amount.value*1;
    let tax_amount = document.courtExpForm.tax_amount.value*1;
    let net_amount = 0.00;

    net_amount = gross_amt - tax_amount;
    document.courtExpForm.net_amount.value = net_amount;
    format_number(document.courtExpForm.net_amount,2) ;
  }
</script>

<?= $this->endSection() ?>