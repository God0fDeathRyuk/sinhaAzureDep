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

	<div class="pagetitle col-md-12 float-start border-bottom pb-1"> <h1>Photocopy Expenses</h1> </div>

    <?php if(!isset($print)) { ?> 
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12 mt-2">
                <form action="" name="photocopyExpForm" method="post">
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
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Agency <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control w-75 float-start" name="supplier_name" id="payeeName" value="<?= $params['supplier_name']?>" readonly/>
                            <input type="text" class="form-control w-20 ms-2 float-start" name="supplier_code" id="payeeCode" value="<?= $params['supplier_code']?>" readonly/>
                            <!-- <input type="text" class="form-control w-23 ms-2 me-0 float-start"/>	 -->
                            <?php if($user_option=='Add') { ?>
                              <i class="fa-solid fa-binoculars icn-vw" style="top:39px; right:28px;" id="payeeCodeLookup" onclick="showData('supplier_code', '<?= $displayId['agency_help_id'] ?>', 'payeeCode', ['payeeName'], ['supplier_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            <?php } ?>
                        </div>
                        <?php if($user_option == 'Print') { ?>
                          <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                              <label class="d-inline-block w-100 mb-1 lbl-mn">Serial# <strong class="text-danger">*</strong></label>
                              <input type="text" class="form-control" name="ref_voucher_serial_no"  value="<?= $params['voucher_serial_no']; ?>" readonly />
                          </div>
                        <?php } ?>
                        <?php if($user_option == 'Approve') { ?>
                          <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Total</label>
                            <input class="form-control" type="text" name="total_amount" value="<?= number_format($total_amount, 2, '.', '')?>" readonly></td>
                          </div>
                          <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Passed</label>
                            <input class="form-control" type="text" name="passed_amount" value="<?= number_format($passed_amount, 2, '.', '')?>" readonly></td>
                          </div>
                        <?php } ?>
                        <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Date <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" name="exp_date" value="<?= $exp_date ?>" onBlur="make_date(this); myExpDate()" <?= $redv?> />
                            <input class="accept_date_mandatory" type="hidden" size="08" maxlength="10" name="current_date" value="<?= date('d-m-Y') ?>">
                            <input class="form-control" type="hidden" name="status_code"  value="<?= $status_code ?>">
                            <input class="form-control" type="hidden" name="xerox_cnt"  value="<?= $xerox_cnt ?>">
                            <input class="form-control" type="hidden" name="user_option"  value="<?= $user_option ?>">
                        </div>
        
                        <div class="d-inline-block w-100 mt-2 ScrltblMn">					
                            <table class="table table-bordered tblePdngsml">
                                <tr class="fs-14">
                                    <th class="d-none">Serial</th>
                                    <th>Date</th>
                                    <th>A/c</th>
                                    <th>Matter</th>
                                    <th>Client</th>
                                    <th>Narration</th>
                                    <th>Pgs</th>
                                    <th>Cpy</th>
                                    <th>Rate Cd</th>
                                    <th>PgSz</th>
                                    <th>Side</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th><input type="checkbox" class="tblcbx float-none d-inline-block"></th>
                                </tr>
                                <tr>							
                                    <td class="wd100 d-none">
                                        <input class="form-control" type="hidden" name="srlno" value="<?= isset($params_opt['srlno']) ? $params_opt['srlno'] : '' ?>">
                                        <input class="form-control" type="hidden" name="rowoptn" value="<?= isset($params_opt['rowoptn']) ? $params_opt['rowoptn'] : '' ?>" readonly>
                                    </td>
                                    <td class="w-150"><input class="form-control" type="text" name="expdate" value="<?= isset($params_opt['expdate']) ? ($params_opt['expdate'] == '') ? $exp_date : '' : '' ?>" onBlur="make_date(this)" disabled></td>
                                    <td class="w-85">
                                        <select class="form-select" name="expfor" onChange="myExpFor()">
                                            <option value="C">C</option>
                                            <option value="O">O</option>
                                        </select>
                                    </td>
                                    <td class="w-10 position-relative">
                                      <input class="form-control" type="text" name="matr_code" value="<?= isset($params_opt['matr_code']) ? $params_opt['matr_code'] : '' ?>" onKeyPress="return validnumbercheck(event)" id="matterCode" onfocusout="fetchData(this, 'matter_code', ['clientCode'], ['client_code'], 'matter_code');">
                                      <i class="fa-solid fa-eye inpt-vw" style="top:20px; right:15px;" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['clientCode'], ['client_code'], '')" data-toggle="modal" data-target="#lookup"></i>
                                    </td>
                                    <td class="w-10"><input class="form-control" type="text" name="clnt_code" id="clientCode"  value="<?= isset($params_opt['clnt_code']) ? $params_opt['clnt_code'] : '' ?>"></td>
                                    <td class="w-350"><input class="form-control" type="text" name="narr" onBlur="javascript:(this.value=this.value.toUpperCase());" value="<?= isset($params_opt['narr']) ? $params_opt['narr'] : '' ?>"></td>
                                    <td class="w-150"><input class="form-control" type="text" name="pages" value="<?= isset($params_opt['pages']) ? $params_opt['pages'] : '' ?>" onBlur="calc_amount()"></td>
                                    <td class="w-150"><input class="form-control" type="text" name="copies" value="<?= isset($params_opt['copies']) ? $params_opt['copies'] : '' ?>" onBlur="calc_amount()"></td>
                                    <td class="w-150"><input class="form-control" type="text" name="ratecd" value="<?= isset($params_opt['ratecd']) ? $params_opt['ratecd'] : '' ?>" onBlur="myCheckCode('Rate'); calc_amount()" onKeyUp="myCodeHelp('Rate')"></td>
                                    <td class="w-150"><input class="form-control" type="text" name="pgsize" value="<?= isset($params_opt['pgsize']) ? $params_opt['pgsize'] : '' ?>"></td>
                                    <td class="w-85">
                                        <select class="form-select" name="pgside">
                                            <option value="S">S</option>
                                            <option value="B">B</option>
                                        </select>
                                    </td>
                                    <td class="w-150"><input class="form-control" type="text" name="exprate" value="<?= isset($params_opt['exprate']) ? $params_opt['exprate'] : '' ?>" onBlur="format_number(this,2); calc_amount()"></td>
                                    <td class="w-150"><input class="form-control" type="text" name="expamt" value="<?= isset($params_opt['expamt']) ? $params_opt['expamt'] : '' ?>" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2)"></td>
                                    <td class="text-center align-middle"><input type="checkbox" class="tblcbx float-none d-inline-block" checked></td>
                                </tr>
                            </table>
                        </div>
                        <?php if($selemode == 'Y') { ?>
                        <div class="d-inline-block w-100 mt-2 ScrltblMn" id="listTable">					
                            <table class="table table-bordered tblePdngsml">
                                <tr class="fs-14">
                                    <th class="d-none">Serial</th>
                                    <th>Date</th>
                                    <th>A/c</th>
                                    <th>Matter</th>
                                    <th>Client</th>
                                    <th>Narration</th>
                                    <th>Pgs</th>
                                    <th>Cpy</th>
                                    <th>Rate Cd</th>
                                    <th>PgSz</th>
                                    <th>Side</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Serial No</th>
                                    <th>Action</th>
                                </tr>
                                <?php $total_amount = 0; foreach($xerox_qry as $j => $xerox_row) { ?>
                                <tr>							
                                    <td class="w-150"><input class="form-control" type="text" name="exp_date<?= $j ?>" value="<?= date_conv($xerox_row['exp_date']) ?>" onBlur="make_date(this)" readonly></td>
                                    <td class="w-85">
                                        <select class="form-select" name="exp_for<?= $j ?>" disabled>
                                            <option value="C" <?php if($xerox_row['exp_for']=='C') {echo 'selected';}?>>C</option>
                                            <option value="O" <?php if($xerox_row['exp_for']=='O') {echo 'selected';}?>>O</option>
                                        </select>
                                    </td>
                                    <td class="w-150"><input class="form-control" type="text" name="matter_code<?= $j ?>" value="<?= $xerox_row['matter_code'] ?>" onKeyPress="return validnumbercheck(event)" readonly></td>
                                    <td class="w-150"><input class="form-control" type="text" name="client_code<?= $j ?>" name="client_code<?= $j ?>" value="<?= $xerox_row['client_code'] ?>" readonly></td>
                                    <td class="w-350"><input class="form-control" type="text" name="description<?= $j ?>" value="<?= $xerox_row['description'] ?>" onBlur="javascript:(this.value=this.value.toUpperCase());" readonly></td>
                                    <td class="w-150"><input class="form-control" type="text" name="page_no<?= $j ?>" value="<?= $xerox_row['page_no'] ?>" readonly></td>
                                    <td class="w-150"><input class="form-control" type="text" name="copy_no<?= $j ?>" value="<?= $xerox_row['copy_no'] ?>" readonly></td>
                                    <td class="w-150"><input class="form-control" type="text" name="rate_code<?= $j ?>" value="<?= $xerox_row['rate_code'] ?>" readonly></td>
                                    <td class="w-150"><input class="form-control" type="text" name="page_size<?= $j ?>" value="<?= $xerox_row['page_size'] ?>" readonly></td>
                                    <td class="w-85">
                                        <select class="form-select" name="page_side<?= $j ?>" disabled>
                                            <option value="S" <?php if($xerox_row['page_side']=='S') {echo 'selected';}?>>S</option>
                                            <option value="B" <?php if($xerox_row['page_side']=='B') {echo 'selected';}?>>B</option>
                                        </select>
                                    </td>
                                    <td class="w-150"><input class="form-control" type="text" name="rate<?= $j ?>" value="<?= $xerox_row['rate'] ?>" readonly></td>
                                    <td class="w-150"><input class="form-control" type="text" name="amount<?= $j ?>" value="<?= $xerox_row['amount'] ?>" readonly></td>
                                    <td class="w-150"><input class="form-control" type="text" name="serial_no<?= $j ?>" value="<?= $xerox_row['serial_no'] ?>" readonly></td>
                                    <td class="wd100 text-center">
                                        <a href="javascript:void(0);" title="Edit" onClick="myRowEditDele(<?= $xerox_row['serial_no'] ?>,'<?= $j ?>', 'Edit')"><i class="fa-solid fa-pen-to-square edit" aria-hidden="true"></i></a>
                                        <a href="javascript:void(0);" title="Delete" onClick="myRowEditDele(<?= $xerox_row['serial_no'] ?>,'<?= $j ?>', 'Delete')"><i class="fa-solid fa-trash delt" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <?php $total_amount = $total_amount + $xerox_row['amount'] ; } ?> 
                            </table>
                        </div>
                        <?php } ?>
                        <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">PAN No</label>
                            <input type="text" class="form-control" name="pan_no" value="<?= $pan_no ?>" readonly/>
                        </div>
                        <div class="frms-sec-insde d-block float-start col-md-12">
                            <input type="hidden" name="selemode" value="Y">
                            <button type="submit" class="btn btn-primary cstmBtn mt-3">Show Details</button>
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

        document.photocopyExpForm.srlno.value     = srlno ;
		document.photocopyExpForm.rowoptn.value   = srlop ;
		document.photocopyExpForm.expdate.value   = document.photocopyExpForm['exp_date'+linno].value;
		document.photocopyExpForm.expfor.value    = document.photocopyExpForm['exp_for'+linno].value;
		document.photocopyExpForm.matr_code.value = document.photocopyExpForm['matter_code'+linno].value;
		document.photocopyExpForm.clnt_code.value = document.photocopyExpForm['client_code'+linno].value;
		document.photocopyExpForm.narr.value      = document.photocopyExpForm['description'+linno].value;
		document.photocopyExpForm.ratecd.value    = document.photocopyExpForm['rate_code'+linno].value;
		document.photocopyExpForm.pages.value     = document.photocopyExpForm['page_no'+linno].value;
		document.photocopyExpForm.copies.value    = document.photocopyExpForm['copy_no'+linno].value;
		document.photocopyExpForm.ratecd.value    = document.photocopyExpForm['rate_code'+linno].value;
		document.photocopyExpForm.pgsize.value    = document.photocopyExpForm['page_size'+linno].value;
		document.photocopyExpForm.pgside.value    = document.photocopyExpForm['page_side'+linno].value;
		document.photocopyExpForm.exprate.value   = document.photocopyExpForm['rate'+linno].value;
		document.photocopyExpForm.expamt.value    = document.photocopyExpForm['amount'+linno].value;

        if(srlop == 'Edit') {document.photocopyExpForm.expdate.disabled = false;} else {document.photocopyExpForm.expdate.disabled = true;}
        // document.getElementById('listTable').classList.add('d-none');
	  }

      function myConfirm() {
        let brchcd   = document.f1.branch_code.value ; 
        let suppcd   = document.f1.supplier_code.value ; 
        let suppnm   = document.f1.supplier_name.value ; 
		let panno    = document.f1.pan_no.value ;
        let expdate  = document.f1.exp_date.value ; 
		let totamt   = document.f1.total_amount.value ;
		let srlno    = document.f1.srlno.value ;
		let rowoptn  = document.f1.rowoptn.value ;
        let expdt    = document.f1.expdate.value  ; 
        let expfor   = document.f1.expfor.value  ; 
        let matrcd   = document.f1.matr_code.value  ; 
        let clntcd   = document.f1.clnt_code.value  ; 
		let narr     = document.f1.narr.value ;
		let pageno   = document.f1.pages.value;
		let copyno   = document.f1.copies.value;
		let ratecd   = document.f1.ratecd.value;
		let pgsize   = document.f1.pgsize.value;
		let pgside   = document.f1.pgside.value;
		let exprate  = document.f1.exprate.value;
		let expamt   = document.f1.expamt.value;

        if (suppcd == '') {
            alert('Enter Agency Code ........'); document.f1.supplier_code.focus() ; return false ;
        } else if (expdate == '') {
            alert('Enter Expense Date ........'); document.f1.exp_date.focus() ; return false ;
        } if (expdt == '') {
            alert('Enter Expense Date ........'); document.f1.expdate.focus() ; return false ;
        } else if (expfor == '') {
            alert('Enter A/c ........'); document.f1.expfor.focus() ; return false ;
        } else if (expfor == 'C' && matrcd == '') {
            alert('Enter Matter Code ........'); document.f1.matr_code.focus() ; return false ;
        } else if (narr == '') {
            alert('Enter Narration ........'); document.f1.narr.focus() ; return false ;
        } else if (pageno == '' || pageno == 0) {
            alert('Enter No of Pages ........'); document.f1.pages.focus() ; return false ;
        } else if (pgsize == '') {
            alert('Enter Page Size ........'); document.f1.pgsize.focus() ; return false ;
        } else if (exprate == '' || exprate == 0) {
            alert('Enter Expense Rate ........'); document.f1.exprate.focus() ; return false ;
        } else if (expamt == '' || expamt <= 0) {
            alert('Enter Expense Amount ........'); document.f1.expamt.focus() ; return false ;
        } else {
            getData('trn_xerox_expense_ajax.php','branch_code='+brchcd+'&supplier_code='+suppcd+'&pan_no='+panno+'&exp_date='+expdate+'&serial_no='+srlno+'&rowoptn='+rowoptn+'&exp_for='+expfor+'&matter_code='+matrcd+'&client_code='+clntcd+'&description='+narr+'&page_no='+pageno+'&copy_no='+copyno+'&rate_code='+ratecd+'&page_size='+pgsize+'&page_side='+pgside+'&rate='+exprate+'&amount='+expamt,'','myXeroxData','') ;
        }
	  }
</script>

<?= $this->endSection() ?>