<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">
    <div class="pagetitle d-inline-block w-100">
        <h1 class="col-md-11 float-start">Courier Expenses [Edit]</h1>
        <button type="button" class="btn btn-primary cstmBtn btn-cncl col-md-1 float-end">Exit</button>
    </div><!-- End Page Title -->

    <?php if(!isset($print)) { ?> 
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12 mt-2">
                <?php if($selemode == 'Y' || ucfirst($user_option) == 'Add') { ?> <form action="" name="outerCourierExpensesForm" onsubmit="ConfirmBtn(e)" method="post"> <?php } ?>
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <?php if($selemode != 'Y') { ?> <form action="" method="post"> <?php } ?>
                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-2">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Branch</label>
                                <select class="form-select cstm-inpt" name="branch_code">
                                <?php foreach($data['branches'] as $branch) { ?>
                                <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Agency <strong class="text-danger">*</strong></label>
                                <div class="position-relative">
                                    <input type="text" class="form-control w-100 float-start" name="supplier_name" id="supplierName" value="<?= $params['supplier_name'] ?>" readonly/>
                                    <input type="hidden" class="form-control w-100 float-start" name="supplier_code" id="supplierCode" value="<?= $params['supplier_code'] ?>" readonly/>
                                    <?php if($user_option=='Add') { ?>
                                        <i class="fa fa-binoculars position-absolute icn-vw icn-vw2" onclick="showData('supplier_code', '<?= $displayId['agency_help_id'] ?>', 'supplierCode', ['supplierName'], ['supplier_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Date <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control datepicker" name="exp_date" value="<?= $params['exp_date'] ?>" onBlur="chkActivityDate(this); myExpDate()" <?= $redv ?> />
                                <input type="hidden" class="form-control datepicker" name="current_date" value="<?= date('d-m-Y') ?>" />
                            </div>
                            <?php if($user_option == 'Approve') { ?>
                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Total <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="total_amount" value="<?= number_format($total_amount, 2, '.', '') ?>" readonly />
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Passed <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="passed_amount" value="<?= number_format($passed_amount, 2, '.', '') ?>" readonly />
                            </div>
                            <?php } ?>
                            <div class="frms-sec-insde d-block float-start col-md-12">
                                <input type="hidden" class="form-control" name="status_code" value="<?= $status_code ?>" readonly />
                                <input type="hidden" class="form-control" name="courier_cnt" value="<?= $courier_cnt ?>" readonly />
                                <input type="hidden" class="form-control" name="user_option" value="<?= $user_option ?>" readonly />
                                <input type="hidden" class="form-control" name="selemode" value="Y" readonly />
                                <input type="hidden" class="form-control" name="updt_ind" value="Y" readonly />
                                <input type="hidden" class="form-control" name="rec_no" value="0" readonly /> 
                                <input type="hidden" class="form-control" name="ref_voucher_serial_no" value="<?= $params['voucher_serial_no'] ?>" readonly /> 
                                <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2">Show Details</button>
                            </div>
                        <?php if($selemode != 'Y') { ?> </form> <?php } ?>
        
                        <div class="d-inline-block w-100 mt-2 <?= (ucfirst($user_option) == 'Add') ? 'd-none' : '' ?>" id="dataTable">					
                        <?php if($selemode == 'Y') { ?> 
                            <table class="table table-bordered tblePdngsml">
                                <tr class="fs-14">
                                    <th>Serial</th>
                                    <th>Date</th>
                                    <th>Time </th>
                                    <th>No </th>
                                    <th>Name </th>
                                    <th>A/C </th>
                                    <th>Matter </th>
                                    <th>Client </th>
                                    <th>Amount </th>
                                    <th class="text-center"> 
                                        <?php if($user_option == 'Approve' || $user_option == 'Generate') { ?> 
                                        <input type="checkbox" class="text-center" name="approve_all_ind" value="Y" onClick="approveChecked(this)" <?= ($user_option != 'Approve') ? 'checked' : '' ?> <?= ($user_option != 'Approve') ? 'disabled' : '' ?>> 
                                        <?php } else { ?> Actions <?php } ?>
                                    </th>
                                </tr>
                                <?php $total_amount = 0; foreach($courier_qry as $j => $courier_row) {?> 
                                    <tr> 
                                        <td class="w-150"><input type="text" class="form-control"  name="serial_no<?= $j?>" value="<?= $courier_row['serial_no'] ?>" readonly></td>
                                        <td class="w-250"><input type="text" class="form-control"  name="consignment_note_date<?= $j?>" value="<?= date_conv($courier_row['consignment_note_date']) ?>" readonly></td>
                                        <td class="w-150"><input type="text" class="form-control"  name="consignment_note_time<?= $j?>" value="<?= $courier_row['consignment_note_time'] ?>" readonly></td>
                                        <td class="w-150"><input type="text" class="form-control"  name="consignment_no<?= $j?>" value="<?= $courier_row['consignment_note_no'] ?>" readonly></td>
                                        <td class="w-250"><input type="text" class="form-control"  name="consignee_name<?= $j?>" value="<?= $courier_row['consignee_name'] ?>" readonly></td>
                                        <td class="w-150">
                                            <select class="form-select" name="exp_for<?= $j?>" disabled>
                                                <option value="C" <?php if($courier_row['exp_for']=='C') {echo 'selected';}?>>C</option>
                                                <option value="O" <?php if($courier_row['exp_for']=='O') {echo 'selected';}?>>O</option>			
                                            </select>    
                                        </td>
                                        <td class="w-150"><input type="text" class="form-control"  name="matter_code<?= $j?>" value="<?= $courier_row['matter_code'] ?>" readonly></td>
                                        <td class="w-150"><input type="text" class="form-control"  name="client_code<?= $j?>" value="<?= $courier_row['client_code'] ?>" readonly></td>
                                        <td class="w-150"><input type="text" class="form-control"  name="amount<?= $j?>" value="<?= $courier_row['amount'] ?>" readonly></td>
                                        <!-- <td class="w-150"><input type="text" class="form-control"  name="serial_no<?= $j?>" value="<?= $courier_row['serial_no'] ?>" readonly></td> -->
                                        <td class="w-80 text-center">
                                            <?php if($user_option == 'Approve' || $user_option == 'Generate') { ?> 
                                            <input type="checkbox" name="approve_ind_<?= $j ?>" value="Y" onClick="approveChecked(this,'<?= $j ?>')" <?php if($user_option != 'Approve') { echo 'checked'; } ?> <?php if($user_option != 'Approve') { echo 'disabled'; } ?>>
                                            <?php } else { ?>
                                            <a href="javascript:void(0);" title="Edit" onClick="getCourierExpensesData(<?= $courier_row['serial_no'] ?>, 'Edit')"><i class="fa-solid fa-pen-to-square edit" aria-hidden="true"></i></a>
                                            <a href="javascript:void(0);" title="Delete" onClick="getCourierExpensesData(<?= $courier_row['serial_no'] ?>, 'Delete')"><i class="fa-solid fa-trash delt" aria-hidden="true"></i></a>
                                            <?php } ?>
                                        </td>
                                    </tr> 
                                <?php $total_amount = $total_amount + $courier_row['amount']; } ?> 
                            </table>
                            <table>
                            <?php if($user_option == 'Generate') { ?>
                                <tr> <td height="12" colspan="11"><hr size="1" noshade></td> </tr>	  
                                <tr> 
                                    <td colspan="11">
                                        <table class="table_detail_color_new" width="97%" align="left" border="1" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="06%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="08%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="03%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="23%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="28%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="05%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="06%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="06%" align="right"  class="column_label_text_right">Gross&nbsp;</td>
                                                <td width="09%" align="right"  class="grid_col_detail"><input class="display_number_mandatory" type="text"    size="10" maxlength="12" name="gross_amount" value="<?php echo number_format($gross_amount,2,'.','')?>" readonly></td>
                                                <td width="03%" align="center" class="grid_col_detail">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td width="06%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="08%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="03%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="23%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="28%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="05%" align="right"  class="column_label_text_right">Tax&nbsp;</td>
                                                <td width="12%" align="left"   class="grid_col_detail" colspan="2">
                                                <select class="display_list_mandatory" name="tax_code" onChange="myTaxCode()" <?php if($user_option=='Print') { echo 'disabled';}?>>
                                                    <option value="">---Select--</option>
                                                    <?php foreach ($tax_qry as $tax_row) { ?>
                                                        <option value="<?= $tax_row['tax_code'] ?>" <?= ($tax_code==$tax_row['tax_code']) ? 'selected' : '' ?>><?= $tax_row['tax_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                </td>
                                                <td width="09%" align="right"  class="grid_col_detail"><input class="display_number_mandatory" type="text"    size="10" maxlength="12" name="tax_amount"  value="<?php echo $tax_amount?>"  readonly></td>
                                                <td width="03%" align="center" class="grid_col_detail">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td width="06%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="08%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="03%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="23%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="28%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="05%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="06%" align="left"   class="grid_col_detail">&nbsp;</td>
                                                <td width="06%" align="right"  class="column_label_text_right">Net&nbsp;</td>
                                                <td width="09%" align="right"  class="grid_col_detail"><input class="display_number_mandatory" type="text"    size="10" maxlength="12" name="net_amount" value="<?php echo $net_amount?>" readonly></td>
                                                <td width="03%" align="center" class="grid_col_detail">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <?php } ?> 
                            </table>
                        <?php } ?>						
                        </div>
                        <div id="showDetails" class="d-inline-block w-100 mt-2 <?= (ucfirst($user_option) != 'Add') ? 'd-none' : '' ?>">					
                            <div class="">
                                <input type="hidden" name="serial_no" class="form-control w-35 float-start"  />
                                <input type="hidden" name="rowoptn" class="form-control w-63 float-start ms-1"  />
                            </div>
                            <p class="col-md-12 bdge mb-3 mt-0">Serial No. <span class="badge rounded-pill bg-dark" id="slNo">...</span> <span class="badge rounded-pill bg-dark float-end" id="mode">...</span></p>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Date / Time <strong class="text-danger">*</strong></label>
                                <input type="text" name="notedate" placeholder="dd-mm-yyyy" class="form-control w-63 float-start datepicker" readonly />
                                <input type="text" name="notetime" placeholder="hh:mm:ss" class="form-control w-35 float-start ms-1"/>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">No <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="noteno"  />
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-1 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">A/c <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="expfor" >
                                    <option>C</option>
                                    <option>D</option>					
                                </select>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Matter <strong class="text-danger">*</strong></label>
                                <input type="text" name="matr_code" class="form-control w-35 float-start">
                                <div class="position-relative">
                                    <input type="text" name="matr_desc" class="form-control w-63 ms-2 float-start pr40">
                                    <i class="fa fa-binoculars position-absolute icn-vw icn-vw2" aria-hidden="true"></i>
                                </div>					
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Client <strong class="text-danger">*</strong></label>
                                <input type="text" name="clnt_code" class="form-control w-35 float-start">
                                <div class="position-relative">
                                    <input type="text" name="clnt_name" class="form-control w-63 ms-2 float-start">
                                    <i class="fa fa-binoculars position-absolute icn-vw icn-vw2" aria-hidden="true"></i>
                                </div>					
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Name <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="consgname"  />
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Post Type <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="posttype" >
                                    <option>C</option>
                                    <option>D</option>					
                                </select>									
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">City / Pin <strong class="text-danger">*</strong></label>
                                <input type="text" name="city" class="form-control w-35 float-start"  />
                                <input type="text" name="pincode" class="form-control w-63 float-start ms-1"  />					
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">State / Country <strong class="text-danger">*</strong></label>
                                <input type="text" name="state" class="form-control w-35 float-start"  />
                                <input type="text" name="country" class="form-control w-63 float-start ms-1"  />					
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Rate <strong class="text-danger">*</strong></label>
                                <div class="position-relative">
                                    <input type="text" name="ratecd" class="form-control w-100 float-start">
                                    <input type="hidden" name="exprate" class="form-control w-100 float-start">
                                    <i class="fa fa-binoculars position-absolute icn-vw icn-vw2" aria-hidden="true"></i>
                                </div>									
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Amount <strong class="text-danger">*</strong></label>
                                <input type="text" name="expamt" class="form-control"  />
                            </div> 
                            <div class="frms-sec-insde d-block float-start col-md-10 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Addr <strong class="text-danger">*</strong></label>
                                <textarea rows="1" class="form-control float-start w-33 me-1" name="consgadr1" ></textarea>
                                <textarea rows="1" class="form-control float-start w-33 me-1" name="consgadr2" ></textarea>
                                <textarea rows="1" class="form-control float-start w-33" name="consgadr3" ></textarea>
                            </div>
                            
                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Letter Ref <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="refletrno"  />
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Employee Id <strong class="text-danger">*</strong></label>
                                <input type="text" name="empid" class="form-control w-35 float-start">
                                <div class="position-relative">
                                    <input type="text" name="empname" class="form-control w-63 ms-2 float-start">
                                    <i class="fa fa-binoculars position-absolute icn-vw icn-vw2" aria-hidden="true"></i>
                                </div>					
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Remarks <strong class="text-danger">*</strong></label>
                                <input type="text" name="remks" class="form-control"  />
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-1">   
                                <?php if($selemode == 'Y' || ucfirst($user_option) == 'Add') { ?> <input type="hidden" name="edit_confirm" value="Add"> <?php } ?>
                                <button type="submit" class="btn btn-primary cstmBtn mt-3">Confirm</button>
                                <button type="button" class="btn btn-primary cstmBtn mt-3 ms-2" onclick="document.getElementById('showDetails').classList.add('d-none'); document.getElementById('dataTable').classList.remove('d-none');">Close Details</button>
                            </div>
                        </div>
                    </div>
                <?php if($selemode == 'Y' || ucfirst($user_option) == 'Add') { ?> </form> <?php } ?>
            </div>
        </div>
    </section>
    <?php } else { echo view('pages/OtherExpenses/common_print_expenses'); } ?>
</main><!-- End #main -->

<script>
    function getCourierExpensesData(serialNo = '', action = '') {
        fetch(`${baseURL}/api/getCourierExpensesById/${serialNo}`)
        .then((response) => response.json())
        .then((data) => {
                document.getElementById('showDetails').classList.remove('d-none'); document.getElementById('dataTable').classList.add('d-none');
                document.outerCourierExpensesForm.edit_confirm.value = action;

                document.outerCourierExpensesForm.serial_no.value = data.courier_expense.serial_no; document.getElementById('slNo').innerText = data.courier_expense.serial_no;
                document.outerCourierExpensesForm.rowoptn.value = action; document.getElementById('mode').innerText = action;
                document.outerCourierExpensesForm.notedate.value = data.courier_expense.consignment_note_date;
                document.outerCourierExpensesForm.notetime.value = data.courier_expense.consignment_note_time;
                document.outerCourierExpensesForm.noteno.value = data.courier_expense.consignment_note_no;
                document.outerCourierExpensesForm.expfor.value = data.courier_expense.exp_for;
                document.outerCourierExpensesForm.matr_code.value = data.courier_expense.matter_code;
                document.outerCourierExpensesForm.matr_desc.value = data.other_info.matter_desc;
                document.outerCourierExpensesForm.clnt_code.value = data.courier_expense.client_code;
                document.outerCourierExpensesForm.clnt_name.value = data.other_info.client_name;
                document.outerCourierExpensesForm.consgname.value = data.courier_expense.consignee_name;

                document.outerCourierExpensesForm.consgadr1.value = data.courier_expense.address_line_1;
                document.outerCourierExpensesForm.consgadr2.value = data.courier_expense.address_line_2;
                document.outerCourierExpensesForm.consgadr3.value = data.courier_expense.address_line_3;
                
                document.outerCourierExpensesForm.city.value = data.courier_expense.city;
                document.outerCourierExpensesForm.pincode.value = data.courier_expense.pin_code;

                document.outerCourierExpensesForm.state.value = data.courier_expense.city;
                document.outerCourierExpensesForm.country.value = data.courier_expense.country;
                
                document.outerCourierExpensesForm.posttype.value = data.courier_expense.letter_post_type;
                document.outerCourierExpensesForm.ratecd.value = data.courier_expense.rate_code;
                document.outerCourierExpensesForm.exprate.value = data.courier_expense.rate;

                document.outerCourierExpensesForm.expamt.value = data.courier_expense.amount;
                document.outerCourierExpensesForm.refletrno.value = data.courier_expense.ref_letter_no;
                document.outerCourierExpensesForm.empid.value = data.courier_expense.employee_id;
                document.outerCourierExpensesForm.empname.value = data.other_info.employee_name;
                document.outerCourierExpensesForm.remks.value = data.courier_expense.remarks;
			});
    }

    function ConfirmBtn(e) {
        e.preventdefault();
        let brchcd = document.outerCourierExpensesForm.branch_code.value, suppcd = document.outerCourierExpensesForm.supplier_code.value; 
        let suppnm = document.outerCourierExpensesForm.supplier_name.value, expdate = document.outerCourierExpensesForm.exp_date.value; 
        let totamt = document.outerCourierExpensesForm.total_amount.value, serial_no = document.outerCourierExpensesForm.serial_no.value;
        let rowoptn = document.outerCourierExpensesForm.rowoptn.value, notedate = document.outerCourierExpensesForm.notedate.value; 
        let notetime = document.outerCourierExpensesForm.notetime.value, noteno = document.outerCourierExpensesForm.noteno.value; 
        let expfor = document.outerCourierExpensesForm.expfor.value, matrcd = document.outerCourierExpensesForm.matr_code.value; 
        let matrnm = document.outerCourierExpensesForm.matr_desc.value, clntcd = document.outerCourierExpensesForm.clnt_code.value; 
        let clntnm = document.outerCourierExpensesForm.clnt_name.value, consgname = document.outerCourierExpensesForm.consgname.value; 
        let consgadr1 = document.outerCourierExpensesForm.consgadr1.value, consgadr2 = document.outerCourierExpensesForm.consgadr2.value; 
        let consgadr3 = document.outerCourierExpensesForm.consgadr3.value, city = document.outerCourierExpensesForm.city.value; 
        let pincode = document.outerCourierExpensesForm.pincode.value, state = document.outerCourierExpensesForm.state.value; 
        let country = document.outerCourierExpensesForm.country.value, posttype = document.outerCourierExpensesForm.posttype.value; 
        let ratecd = document.outerCourierExpensesForm.ratecd.value, exprate = document.outerCourierExpensesForm.exprate.value;
        let expamt = document.outerCourierExpensesForm.expamt.value, refletrno = document.outerCourierExpensesForm.refletrno.value;
        let empid = document.outerCourierExpensesForm.empid.value, empname = document.outerCourierExpensesForm.empname.value;
        let remks = document.outerCourierExpensesForm.remks.value;

        if (suppcd == '') {
            alert('Enter Agency Code ........'); document.outerCourierExpensesForm.supplier_code.focus(); return false;
        } else if (expdate == '') {
            alert('Enter Expense Date ........'); document.outerCourierExpensesForm.exp_date.focus(); return false;
        } else if (notedate == '') {
            alert('Enter Consignment Note Date ........'); document.outerCourierExpensesForm.notedate.focus(); return false;
        } else if (noteno == '') {
            alert('Enter Consignment Note No ........'); document.outerCourierExpensesForm.noteno.focus(); return false;
        } else if (expfor == '') {
            alert('Enter A/c ........'); document.outerCourierExpensesForm.expfor.focus(); return false;
        } else if (expfor == 'C' && matrcd == '') {
            alert('Enter Matter Code ........'); document.outerCourierExpensesForm.matr_code.focus(); return false;
        } else if (consgname == '') {
            alert('Enter Consignee Name ........'); document.outerCourierExpensesForm.consgname.focus(); return false;
        } else if (consgadr1 == '') {
            alert('Enter Consignee Address Line 1 ........'); document.outerCourierExpensesForm.consgadr1.focus(); return false;
        } else if (posttype == '') {
            alert('Enter Post Type........'); document.outerCourierExpensesForm.posttype.focus(); return false;
        } else if (expamt == '' || expamt <= 0) {
            alert('Enter Expense Amount ........'); document.outerCourierExpensesForm.expamt.focus(); return false;
        } else if (empid == '') {
            alert('Enter Employee ID ........'); document.outerCourierExpensesForm.empid.focus(); return false;
        } else {
            e.submit();
        }
	}

    function approveChecked(e, param = null) { 
        // console.log(document.outerCourierExpensesForm.["approve_ind_0"].checked);
        if(param == null) {
            if (e.checked == true) {
                document.outerCourierExpensesForm.passed_amount.value = document.outerCourierExpensesForm.total_amount.value ;
                let xcnt = document.outerCourierExpensesForm.courier_cnt.value ;
                
                for (i=0; i < xcnt; i++) document.outerCourierExpensesForm["approve_ind_"+i].checked = true;  
            } else {
                document.outerCourierExpensesForm.passed_amount.value = 0 ;
                format_number(document.outerCourierExpensesForm.passed_amount,2) ;
                let xcnt = document.outerCourierExpensesForm.courier_cnt.value ;
                
                for (i=0; i < xcnt; i++) document.outerCourierExpensesForm["approve_ind_"+i].checked = false;
            }
        } else {
            let i = param;  
            let amt = document.outerCourierExpensesForm["amount"+i].value * 1;
            let pamt = document.outerCourierExpensesForm.passed_amount.value * 1;
            
            if (e.checked == true) pamt = pamt + amt; 
            else pamt = pamt - amt; 
            
            document.outerCourierExpensesForm.passed_amount.value = pamt;
            format_number(document.outerCourierExpensesForm.passed_amount, 2);
        }
        document.outerCourierExpensesForm.updt_ind.value = (document.f1.approve_ind_0.checked == true) ? 'Y' : 'N';
    }

    function myTaxCode() {
         if(document.f1.tax_code != '')
		 {
           getData('trn_xerox_expense_ajax.php','tax_code='+document.f1.tax_code.value,'','myTaxCode','') ;
         }		     	  
	  } 
</script>

<?= $this->endSection() ?>