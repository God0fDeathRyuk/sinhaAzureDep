<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?= view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">
    <div class="pagetitle d-inline-block w-100">
        <h1 class="col-md-11 float-start">Payment Voucher <span class="badge rounded-pill bg-dark"><?= ucfirst($user_option) ?></span></h1>
        <a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn btn-primary cstmBtn btn-cncl col-md-1 float-end">Exit</a>
    </div><!-- End Page Title -->

    <?php if(!isset($print)) { ?> 
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-2">
			<form action="" method="post" name="paymentForm">
				<div class="frms-sec d-inline-block w-100 bg-white p-3 position-relative">
					<div class="inptSecBtn col-md-2 text-end">
						<input type="hidden" class="form-control" name="status_code" value="<?= $params['status_code'] ?>" readonly>
						<input type="text" class="cstmBtn w-75 text-center text-white NwhvrNone" style="background-color:<?= $params['colour_s'] ?>" name="status_desc" value="<?= $params['status_desc'] ?>" readonly>
					</div>
					<?php if($user_option != 'Add') { ?>	
					<div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Serial# </label>
						<input type="text" class="form-control" name="serial_no" value="<?= $params['serial_no'] ?>" readonly>
						<input type="hidden" class="form-control" name="link_jv_serial_no" value="<?= $params['link_jv_serial_no'] ?>" readonly>
					</div>
					<?php } ?>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Branch  <strong class="text-danger">*</strong></label>
						<select class="form-select cstm-inpt" name="branch_code" onClick="pass_close()" onBlur="pass_close()" <?= $tag_permissions ?>>
							<?php foreach($data['branches'] as $branch) { ?>
							<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Date  <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control datepicker" name="entry_date" value="<?= $params['entry_date'] ?>" onBlur="make_date(this)" <?= $tag_permissions ?>>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-7 px-2 mb-1 position-relative">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Payee  <strong class="text-danger">*</strong></label>
						<select class="form-select w-33 float-start me-1" name="payee_payer_type" onChange="document.paymentForm.associate_name.value=''; document.paymentForm.associate_code.value='';" <?= $tag_permissions ?>>
						<?php if($user_option=='Add' || $user_option=='Edit' ||  $user_option == 'Post-Edit' ) { ?>
							<option value="E" <?php if($params['payee_payer_type']=='E') echo 'selected';?>>Employee</option>
							<option value="S" <?php if($params['payee_payer_type']=='S') echo 'selected';?>>Supplier</option>
							<option value="U" <?php if($params['payee_payer_type']=='U') echo 'selected';?>>Consultant</option>
							<option value="C" <?php if($params['payee_payer_type']=='C') echo 'selected';?>>Counsel</option>
							<option value="K" <?php if($params['payee_payer_type']=='K') echo 'selected';?>>Clerk</option>
							<option value="P" <?php if($params['payee_payer_type']=='P') echo 'selected';?>>Peon</option>
							<option value="T" <?php if($params['payee_payer_type']=='T') echo 'selected';?>>Stenographer</option>
							<option value="A" <?php if($params['payee_payer_type']=='A') echo 'selected';?>>Arbitrator</option>
							<option value="O" <?php if($params['payee_payer_type']=='O') echo 'selected';?>>Others</option>
						<?php } else { ?>
							<?php if($params['payee_payer_type'] == "E"){?><option value="E">Employee</option><?php }?>
							<?php if($params['payee_payer_type'] == "S"){?><option value="S">Supplier</option><?php }?>
							<?php if($params['payee_payer_type'] == "U"){?><option value="U">Consultant</option><?php }?>
							<?php if($params['payee_payer_type'] == "C"){?><option value="C">Counsel</option><?php }?>
							<?php if($params['payee_payer_type'] == "K"){?><option value="K">Clerk</option><?php }?>
							<?php if($params['payee_payer_type'] == "P"){?><option value="P">Peon</option><?php }?>
							<?php if($params['payee_payer_type'] == "T"){?><option value="T">Stenographer</option><?php }?>
							<?php if($params['payee_payer_type'] == "A"){?><option value="A">Arbitrator</option><?php }?>
							<?php if($params['payee_payer_type'] == "O"){?><option value="O">Others</option><?php }?>
						<?php } ?>
						</select>
						<input type="text" name="associate_name" id="associateName" value="<?= $params['associate_name'] ?>" class="form-control w-65 float-start" onBlur="javascript:(this.value=this.value.toUpperCase());" onChange="document.paymentForm.associate_code.value='';" readonly>
						<input type="hidden" name="associate_code" id="associateCode" value="<?= $params['associate_code'] ?>">
						<?php if($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Post-Edit') { ?>
                            <i class="fa fa-binoculars icn-vw" style="right:30px;top:40px;" onclick="showData('employee_name', '<?= $displayId['asso_code'] ?>', 'associateName', ['associateCode'], ['employee_id'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
						<?php } ?>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Type  <strong class="text-danger">*</strong></label>
						<select class="form-select" name="payment_type" <?= $tag_permissions ?>>
						<?php if($user_option=='Add' || $user_option=='Edit' ||  $user_option == 'Post-Edit') { ?>
							<option value="N" <?php if($params['payment_type']=='N') echo 'selected';?>>Normal</option>
							<option value="A" <?php if($params['payment_type']=='A') echo 'selected';?>>Advance</option>
						<?php } else { 
							if($params['payment_type'] == "N"){?><option value="N">Normal</option><?php }
							if($params['payment_type'] == "A"){?><option value="A">Advance</option><?php } ?>
						<?php } ?>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-9 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Remarks</label>
						<textarea rows="1" name="remarks" class="form-control" <?= $tag_permissions ?>><?= $params['remarks'] ?></textarea>
					</div>			
					<div class="d-inline-block w-100 mt-2 NwTbl_8_Scrl_Mdm">	
					<span id="actionBtn1">
						<?php if (ucfirst($user_option) == 'Edit' || ucfirst($user_option) == 'Add') { 
							if(count($dtl_data_arr)) { ?>
							<button type="button" onclick="deleteRow('tbody', 'row_count', 'actionBtn1', 'addNewRow')" class="btn btn-primary cstmBtn border border-white float-end mb-2">Delete Row</button> 
						<?php } else { ?>
							<button type="button" onclick="addNewRow(this, null)" class="btn btn-primary cstmBtn border border-white float-end">Add Row</button> 
						<?php } } ?>
					</span>   				
					<div class="d-inline-block w-100 mt-2 tblscrlvtrcllrg">					
						<table class="table table-bordered tblePdngsml">
							<thead>
								<tr class="fs-14">
									<th class=""></th>
									<th class="w-250">Main A/c</th>
									<th class="w-250">Sub A/c</th>
									<th class="w-250">Matter Code</th>
									<th class="w-250">Client Code</th>
									<th class="w-250">Intl Code</th>
									<th class="w-250">Expn Code</th>
									<th class="w-250">Bill#</th>
									<th class="w-250">Bill Dt</th>
									<th class="w-250">Basic</th>
									<th class="w-250">Tax</th>
									<th class="w-250">Cess</th>
									<th class="w-250">Hec</th>
									<th class="w-250">Service Tax</th>
									<th class="text-center wd100">Bill ?</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="tbody">
								<?php foreach($dtl_data_arr as $key => $dtl_data) { $i = $key+1; $tabindex = ($i * 100) + $i; ?>
								<tr>								
									<td class="w-150 text-center"id="Ctd2<?= $i?>" onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $i;?>)<?php }?>"> <input type="hidden" class="form-control" name="voucher_ok_ind<?= $i?>" value="Y" readonly onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $i;?>)<?php }?>"> 
									    <img src="<?= base_url('/public/assets/img/SelectRow.png')?>" class="slctRow" alt="Select">
									</td>						
									<td class="w-150 position-relative"> 
										<input class="form-control" name="main_ac_code<?= $i?>" id="mainAcCode<?= $i?>" value="<?= $dtl_data['main_ac_code'];?>" onchange="fetchData(this, 'main_ac_code', [ 'subAcInd<?= $i?>','acDesc<?= $i?>'], ['sub_ac_ind','main_ac_desc'], 'main_ac_code')" tabindex="<?= $tabindex;?>" <?= $tag_permissions ?>> 
										<input type="hidden" class="form-control" name="ac_desc<?= $i?>" id="acDesc<?= $i?>" value="<?= $dtl_data['ac_desc'];?>"> 
										<input type="hidden" class="form-control" name="sub_ac_ind<?= $i?>" id="subAcInd<?= $i?>" value="<?= $dtl_data['sub_ac_ind'];?>"> 
										<i class="fa fa-binoculars icn-vw" onclick="showData('main_ac_code', '<?= '4004' ?>', 'mainAcCode<?= $i?>', ['subAcInd<?= $i?>','acDesc<?= $i?>'], ['sub_ac_ind','main_ac_desc'], 'main_ac_code')"  data-toggle="modal" data-target="#lookup"></i>
									</td>						
									<td class="w-150 position-relative"> 
										<input type="text" class="form-control" name="sub_ac_code<?= $i?>" value="<?= $dtl_data['sub_ac_code'] ?>" id="subAcCode<?= $i?>" onchange="fetchData(this, 'sub_ac_code', ['subAcDesc<?= $i ?>'], ['sub_ac_desc'], 'sub_ac_code')" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>> 
										<input type="hidden" class="form-control" name="sub_ac_desc<?= $i ?>" id="subAcDesc<?= $i ?>" value="<?= $dtl_data['sub_ac_desc'] ?>">
										<i class="fa fa-binoculars icn-vw" onclick="showData('sub_ac_code', '<?= '4003' ?>', 'subAcCode<?= $i?>', ['subAcDesc<?= $i ?>'], ['sub_ac_desc'], 'sub_ac_code')"  data-toggle="modal" data-target="#lookup"></i>
									</td>							
									<td class="w-150">  
										<input type="text" class="form-control" name="matter_code<?= $i?>" value="<?= $dtl_data['matter_code'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>> 
										<input type="hidden" class="form-control" name="matter_name<?= $i ?>" value="<?= $dtl_data['matter_name'] ?>">
									</td>						
									<td class="w-150">
										<div class="d-block">
											<input type="text" class="form-control" name="client_code<?= $i ?>" value="<?= $dtl_data['client_code'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
										</div>
									</td>						
									<td class="w-150">
										<div class="d-block">
											<input type="text" class="form-control" name="intl_code<?= $i ?>" value="<?= $dtl_data['intl_code'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
										</div>
										<div class="d-block">
											<span>CEO Code</span>
											<input type="text" class="form-control" name="ceo_code<?= $i ?>" value="<?= $dtl_data['ceo_code'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
											<input type="hidden" class="form-control" name="name_desc<?= $i ?>" value="<?= isset($dtl_data['name_desc']) ? $dtl_data['name_desc'] : '' ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
										</div>
									</td>
									<td class="w-150 text-left">
										<input type="text" class="form-control" name="expense_code<?= $i ?>" value="<?= $dtl_data['expense_code'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
										<input type="hidden" class="form-control" name="expense_desc<?= $i ?>" value="<?= $dtl_data['expense_desc'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
										<div class="d-block">
											<span>EXPS Code</span>
											<input type="text" class="form-control" name="exps_code<?= $i ?>" value="<?= $dtl_data['exps_code'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
											<input type="hidden" class="form-control" name="exps_desc<?= $i ?>" value="<?= isset($dtl_data['exps_desc']) ? $dtl_data['exps_desc'] : '' ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
										</div>
									</td>
									<td class="w-150" colspan="3">
										<table class="w-auto">
											<tbody>
												<tr>
													<td class="w-150">
														<div class="d-block">
															<input class="form-control" name="bill_no<?= $i ?>" value="<?= $dtl_data['bill_no'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
														</div>
													</td>
													<td class="w-150">
														<div class="d-block">
															<span></span>
															<input class="form-control" name="bill_date<?= $i ?>" value="<?= $dtl_data['bill_date'] ?>" tabindex="<?= $tabindex ?>" onBlur="make_date(this)" <?= $tag_permissions ?>>
														</div>
													</td>
													<td class="w-150">
														<div class="d-block">
															<span></span>
															<input class="form-control" name="basic_amount<?= $i ?>" value="<?= ($dtl_data['basic_amount']) ? $dtl_data['basic_amount'] : 0 ?>" tabindex="<?= $tabindex ?>" onBlur="fin_calc_basic_amount(<?= $i?>);"  <?= $tag_permissions ?>>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<div class="d-block">
															<span>Narration</span>
															<textarea class="form-control" name="narration<?= $i ?>" oninput="this.value = this.value.toUpperCase()" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>><?= $dtl_data['narration'] ?></textarea>
														</div>
													</td>
												</tr>
											</tbody>
										</table>									
									</td>
									<td class="w-150 text-left">
										<div class="d-block">
											<span>Tax %</span>
											<input class="form-control"  name="new_tax_percent<?= $i?>" value="<?= ($dtl_data['new_tax_percent'] != '') ? $dtl_data['new_tax_percent'] : 0 ?>" tabindex="<?= $tabindex;?>" onBlur="fin_calc_newtax_percent(<?= $i?>);" <?= $tag_permissions ?>>
										</div>
										<div class="d-block">
											<span>Amount</span>
											<input class="form-control" name="new_tax_amount<?= $i?>" value="<?= ($dtl_data['new_tax_amount'] != '') ? $dtl_data['new_tax_amount'] : 0 ?>" tabindex="<?= $tabindex;?>" onBlur="fin_calc_newtax_amount(<?= $i?>)" <?= $tag_permissions ?>>
										</div>
									</td>						
									<td class="wd100">
										<div class="d-block">
											<span>Cess %</span>
											<input class="form-control" name="new_tax_cess_percent<?= $i?>" value="<?= ($dtl_data['new_tax_cess_percent']) ? $dtl_data['new_tax_cess_percent'] : 0 ?>" tabindex="<?= $tabindex;?>" onBlur="fin_calc_newcess_percent(<?= $i?>);" <?= $tag_permissions ?>>
										</div>
										<div class="d-block">
											<span>Amount</span>
											<input class="form-control" name="new_tax_cess_amount<?= $i?>" value="<?= ($dtl_data['new_tax_cess_amount']) ? $dtl_data['new_tax_cess_amount'] : 0 ?>" tabindex="<?= $tabindex;?>" onBlur="fin_calc_newcess_amount(<?= $i?>);" <?= $tag_permissions ?>>
										</div>
									</td>								
									<td class="wd100">
										<div class="d-block">
											<span>Hec %</span>
											<input class="form-control" name="new_tax_hecess_percent<?= $i?>" value="<?= ($dtl_data['new_tax_hecess_percent']) ? $dtl_data['new_tax_hecess_percent'] : 0 ?>" tabindex="<?= $tabindex;?>" onBlur="fin_calc_newhecess_percent(<?= $i?>);" <?= $tag_permissions ?>>
										</div>
										<div class="d-block">
											<span>Amount</span>
											<input class="form-control" name="new_tax_hecess_amount<?= $i?>" value="<?= ($dtl_data['new_tax_hecess_amount']) ? $dtl_data['new_tax_hecess_amount'] : 0 ?>" tabindex="<?= $tabindex;?>" onBlur="fin_calc_newhecess_amount(<?= $i?>);" <?= $tag_permissions ?>>
										</div>
									</td>								
									<td class="w-150">
									<div class="d-block">
											<span>Service Tax</span>
											<input class="form-control"  name="new_tax_total_amount<?= $i?>" value="<?= ($dtl_data['new_tax_total_amount']) ? $dtl_data['new_tax_total_amount'] : 0 ?>" readonly>
										</div>
										<div class="d-block">
											<span>Gross Amount</span>
											<input class="form-control" name="total_amount<?= $i?>" value="<?= ($dtl_data['total_amount']) ? $dtl_data['total_amount'] : 0 ?>" onBlur="format_number(this,2); fin_amount_calc();" tabindex="<?= $tabindex;?>" <?= $tag_permissions;?>>
										</div>
									</td>								
									<td class="text-center"><input type="checkbox" class="cbx" name="billable_ind<?= $i?>" value="Y" <?php if($user_option=='Add') {echo 'checked';} else {echo $params['billable_ind_desc'];} ?> <?= $tag_permissions ?>></td>
									<td class="text-center TbladdBtn wd100">
									<?php if(($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Post-Edit') && count($dtl_data_arr) == $i){?>
										<!-- <input type="button" title="Add Row">  -->
										<i class="fa-solid fa-plus" title="ADD" onclick="addNewRow(this, <?= $i?>)"></i>
										<?php } ?>

									</td>								
								</tr>
								<?php } if (!count($dtl_data_arr)) { ?>
									<tr>
										<td class="w-150" colspan="16"> No Records Found !!</td>						
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					
					<?php if(($status_code == 'B' && $user_option == 'Payment' ||  $user_option == 'Post-Edit') || ($user_option == 'View')){ ?>

					<div class="col-md-6 float-start">
						<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
							<label class="d-inline-block w-100 mb-1 lbl-mn">Voucher Date  <strong class="text-danger">*</strong></label>

							<input type="text" class="form-control datepicker" name="voucher_date"  value="<?php if ($user_option != 'Post-Edit') {echo date('d-m-Y');} if ($user_option == 'Post-Edit') {echo $params['final_voucher_date'];}?>"  tabindex="2001"  onChange="make_date(this); get_daybook();" onBlur="if(make_date(this)==true) {make_date(this); get_daybook();}" <?php if($user_option=='View') { echo 'readonly' ; } ?>>
							<input type="hidden" name="current_date" value="<?= $params['global_sysdate'] ?>">
							<input type="hidden" name="finyr_start_date" value="<?= $params['global_curr_finyr_fymddate'] ?>">
							<input type="hidden" name="finyr_end_date" value="<?= $params['global_curr_finyr_lymddate'] ?>">
						</div>
						<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
							<label class="d-inline-block w-100 mb-1 lbl-mn">Day Book  <strong class="text-danger">*</strong></label>
							<select class="form-select" name="daybook_code" required <?= $tag_permissions ?>>
								<option value="">---Select---</option>
								<?php foreach($params['daybook_qry'] as $daybook_row) { ?>
								<option value="<?= $daybook_row['daybook_code'] ?>" <?php if($params['daybook_code'] == $daybook_row['daybook_code']) {echo 'selected';}?>><strong><?= $daybook_row['daybook_desc'].' - '.' ['.$daybook_row['daybook_code'].']'.' ';?></strong></option>
								<?php } ?>
							</select>
							<input type="hidden" name="daybook_type" value="<?= $params['daybook_type'] ?>" readonly>
             				<input type="hidden" name="ok_ind" readonly> 
						</div>
						<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
							<label class="d-inline-block w-100 mb-1 lbl-mn">Day Balence</label>
							<input type="text"   class="form-control" name="daybook_balance_amount" value="<?= isset($daybook_balance_amount) ? $daybook_balance_amount : 0 ?>" readonly>
						</div> 
						<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
							<label class="d-inline-block w-100 mb-1 lbl-mn">Insufficient Balence On</label>
							<input type="text" class="form-control" name="check_date" readonly>  
						</div>
						<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
							<label class="d-inline-block w-100 mb-1 lbl-mn">Cheque # / Dt</label>
							<input type="text" class="form-control w-49 float-start me-1" name="instrument_no" value="<?= $params['instrument_no'] ?>" <?= $tag_permissions;?> tabindex="2003">
            				<input type="text" class="form-control w-49 float-start" name="instrument_dt" value="<?= $params['instrument_dt'] ?>" onBlur="make_date(this)" <?= $tag_permissions;?> tabindex="2004">
						</div>
					</div>
					<?php } ?>   
					<div class="col-md-6 float-start">
						<?php if ($params['global_userid'] == 'abhijit') { ?> 
							<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Prepared By</label>
								<input type="text" class="form-control" name="pan_no" value="<?= $params['prepared_by'] ?>" readonly>
							</div>
							<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Prepared On</label>
								<input type="text" class="form-control" name="pan_no" value="<?= $params['prepared_on'] ?>" readonly>
							</div>
  							<?php if ($user_option == 'Post-Edit') { ?>

							<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Paid By</label>
								<input type="text" class="form-control" name="pan_no" value="<?= $params['paid_by'] ?>" readonly>
							</div>
							<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Paid On</label>
								<input type="text" class="form-control" name="pan_no" value="<?= $params['paid_on'] ?>" readonly>
							</div>							
						<?php } } ?>
						<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
							<label class="d-inline-block w-100 mb-1 lbl-mn">PAN</label>
							<input type="text" class="form-control" name="pan_no" value="<?= $params['pan_no'] ?>" readonly>
						</div>
						<?php if ($user_option == 'Chq-Print') { ?> 
							<div class="frms-sec-insde d-block float-start w-49 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">A/c Payee</label>
								<input type="checkbox" class="w25 h25 mt-1" name="dupl_ind" value="<?= $params['pan_no'] ?>" readonly>
							</div>
						<?php } ?>
					</div>
					<div class="col-md-6 float-start">          
						<div class="frms-sec-insde d-block float-start w-65 px-2 mb-1">
							<label class="d-inline-block w-100 mb-1 lbl-mn">TDS % <strong class="text-danger">*</strong></label>

							<select name="tax_select" class="form-select w-65 float-start" onChange="fin_amount_calc();" <?= $tag_permissions ?> tabindex="1001">
								<?php if($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Post-Edit' ) { ?>
									<option value="">---Select---</option>
								<?php }?>
								<?php foreach($res as $row) { ?>
									<option value="<?= $row['tax_code'].'|'.$row['tax_percent'].'|'.$row['tax_account_code'].'|'.$row['tax_sub_account_code'];?>" <?php if($row['tax_code'] == $params['tax_code']){ echo 'selected'; }?> ><?php echo strtoupper($row['tax_name']);?></option>
								<?php } ?>
              				</select>
							<input type="text" class="form-control w-33 ms-1 float-start" name="tax_sub_account_code" value="<?= $params['tax_sub_account_code'] ?>" onBlur="javascript:(this.value=this.value.toUpperCase());" <?= $tag_permissions ?>>
							<input type="hidden" name="tax_code" value="<?= $params['tax_code'] ?>">
							<input type="hidden" name="tax_account_code" value="<?= $params['tax_account_code'] ?>">
							<input type="hidden" name="tax_percent" value="<?= $params['tax_percent'] ?>"> 
						</div>
						<div class="d-block w-33 float-start">
							<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Total</label>
								<input type="text" class="form-control text-end" name="gross_amount" value="<?= ($params['gross_amount'] != '') ? $params['gross_amount'] : 0 ?>" readonly>
								<input type="hidden" name="total_basic_amount" value="<?= ($params['total_basic_amount'] != '') ? $params['total_basic_amount'] : 0 ?>" readonly>
								<input type="hidden" name="total_newtax_amount" value="<?= ($params['total_newtax_amount'] != '') ? $params['total_newtax_amount'] : 0 ?>" readonly>
								<input type="hidden" name="total_newcess_amount" value="<?= ($params['total_newcess_amount'] != '') ? $params['total_newcess_amount'] : 0 ?>" readonly>
								<input type="hidden" name="total_newhecess_amount" value="<?= ($params['total_newhecess_amount'] != '') ? $params['total_newhecess_amount'] : 0 ?>" readonly>	
							</div>
							<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Amount</label>
								<input type="text" class="form-control text-end" name="tax_amount" value="<?= $params['tax_amount']?>" readonly>
							</div>
							<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Net</label>
								<input type="text" class="form-control text-end" name="net_amount" value="<?= $params['net_amount'] ?>" readonly>
							</div>
						</div>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-12 px-2 mt-10">
						<input type="hidden" name="row_count" value="<?= $params['row_count'] ?>" id="row_count" readonly>
						<input type="hidden" name="trans_type" value="<?= $params['trans_type'] ?>" readonly>
						<input type="hidden" name="user_option" value="<?= $user_option ?>">
						<input type="hidden" name="selemode" value="<?= $selemode ?>">
						<input type="hidden" name="bank_name" value="<?= $params['bank_name'] ?>">

                        <?php if($user_option != 'View') { ?>
						<button type="submit" class="btn btn-primary cstmBtn mt-2"><?= ($user_option != 'Print' && $user_option != 'Chq-Print') ? 'Confirm' : 'Print' ?></button>
						<?php } ?>
						<a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn btn-primary cstmBtn btn-cncl mt-2">Back</a>
					</div>
				</div>
			</form>
		  </div>
      </div>
    </section>
    <?php } else { echo view('pages/OtherExpenses/common_print_expenses'); } ?>
</main><!-- End #main -->

<?php if(!isset($print)) { ?> 
<script>
		
	function fin_calc_basic_amount(rno) {
		var basamt = eval("document.paymentForm.basic_amount"+rno+".value") * 1 ;
		if (basamt > 0) { format_number(eval("document.paymentForm.basic_amount"+rno),2); }
		
		fin_calc_newtax_percent(rno);
		}

		//-------------------------------------------------
		function fin_calc_newtax_percent(rno) {
		var basamt  = eval("document.paymentForm.basic_amount"+rno+".value") * 1 ;
		var ntaxper = eval("document.paymentForm.new_tax_percent"+rno+".value") * 1 ;

		if (ntaxper > 0) {
			var ntaxamt = (basamt*ntaxper/100) ; eval("document.paymentForm.new_tax_amount"+rno+".value = '"+ntaxamt+"'");  format_number(eval("document.paymentForm.new_tax_amount"+rno),2); 
		} else {
			var ntaxamt = ''                   ; eval("document.paymentForm.new_tax_amount"+rno+".value = '"+ntaxamt+"'"); 
		}

		fin_calc_newcess_percent(rno) ;
		}

		//-------------------------------------------------
		function fin_calc_newtax_amount(rno) {
		var ntaxamt = eval("document.paymentForm.new_tax_amount"+rno+".value") * 1 ;
		if (ntaxamt > 0) { format_number(eval("document.paymentForm.new_tax_amount"+rno),2); }

		fin_calc_newcess_percent(rno) ;
		}

		//-------------------------------------------------
		function fin_calc_newcess_percent(rno) {
		var ntaxamt  = eval("document.paymentForm.new_tax_amount"+rno+".value") * 1 ;
		var ncessper = eval("document.paymentForm.new_tax_cess_percent"+rno+".value") * 1 ;

		if (ncessper > 0) {
			var ncessamt = (ntaxamt*ncessper/100) ; eval("document.paymentForm.new_tax_cess_amount"+rno+".value = '"+ncessamt+"'");  format_number(eval("document.paymentForm.new_tax_cess_amount"+rno),2); 
		} else {
			var ncessamt = ''; eval("document.paymentForm.new_tax_cess_amount"+rno+".value = '"+ncessamt+"'"); 
		}

		fin_calc_newhecess_percent(rno) ;
		}

		//-------------------------------------------------
		function fin_calc_newcess_amount(rno) {
		var ncessamt = eval("document.paymentForm.new_tax_cess_amount"+rno+".value") * 1 ;
		if (ncessamt > 0) { format_number(eval("document.paymentForm.new_tax_cess_amount"+rno),2); }

		fin_calc_newhecess_percent(rno) ;
		}

		//-------------------------------------------------
		function fin_calc_newhecess_percent(rno) {
		var ntaxamt  = eval("document.paymentForm.new_tax_amount"+rno+".value") * 1 ;
		var nhcesper = eval("document.paymentForm.new_tax_hecess_percent"+rno+".value") * 1 ;

		//--- H.E.Cess
		if (nhcesper > 0) {
			var nhcesamt = (ntaxamt*nhcesper/100) ; eval("document.paymentForm.new_tax_hecess_amount"+rno+".value = '"+nhcesamt+"'");  format_number(eval("document.paymentForm.new_tax_hecess_amount"+rno),2); 
		} else {
			var nhcesamt = ''; eval("document.paymentForm.new_tax_hecess_amount"+rno+".value = '"+nhcesamt+"'"); 
		}
		
		fin_calc_row_gross(rno) ;
		}

		//-------------------------------------------------
		function fin_calc_newhecess_amount(rno) {
		var nhcessamt = eval("document.paymentForm.new_tax_hecess_amount"+rno+".value") * 1 ;
		if (nhcessamt > 0) { format_number(eval("document.paymentForm.new_tax_hecess_amount"+rno),2); }

		fin_calc_row_gross(rno) ;
		}

		//-------------------------------------------------
		function fin_calc_row_gross(rno) {
		var basamt  = eval("document.paymentForm.basic_amount"+rno+".value") * 1 ;
		var ntaxamt = eval("document.paymentForm.new_tax_amount"+rno+".value") * 1 ;
		var ncesamt = eval("document.paymentForm.new_tax_cess_amount"+rno+".value") * 1 ;
		var nhceamt = eval("document.paymentForm.new_tax_hecess_amount"+rno+".value") * 1 ;

		var rsvamt  = Math.round(ntaxamt + ncesamt + nhceamt) ;
		var rgramt  = basamt + rsvamt ;
		if (rsvamt > 0) { eval("document.paymentForm.new_tax_total_amount"+rno+".value = '"+rsvamt+"'");  format_number(eval("document.paymentForm.new_tax_total_amount"+rno),2); }
		if (rgramt > 0) { eval("document.paymentForm.total_amount"+rno+".value = '"+rgramt+"'");          format_number(eval("document.paymentForm.total_amount"+rno),2); }
		
		fin_amount_calc();
	}

	function fin_amount_calc() {
		var tax_select = document.paymentForm.tax_select.value;
		var voucher_ok_ind = '';  

		if(tax_select=='') {
			document.paymentForm.tax_code.value             = '';
			document.paymentForm.tax_percent.value          = '';
			document.paymentForm.tax_account_code.value     = '';
			document.paymentForm.tax_sub_account_code.value = '';
		} else {
			var tax_array    = tax_select.split('|');
			document.paymentForm.tax_code.value             = tax_array[0];
			document.paymentForm.tax_percent.value          = tax_array[1];
			document.paymentForm.tax_account_code.value     = tax_array[2];
			document.paymentForm.tax_sub_account_code.value = tax_array[3];
		}
		var row_count         = document.paymentForm.row_count.value * 1;
		var basic_amount      = 0.00 ;
		var svtax_amount      = 0.00 ;
		var svces_amount      = 0.00 ;
		var svhce_amount      = 0.00 ;
		var gross_amount      = 0.00 ;
		var net_amount        = 0.00 ;
		var tax_percent       = document.paymentForm.tax_percent.value*1 ;
		var tax_amount        = 0.00 ;
		var this_amount       = 0.00 ;

		if(tax_percent<0) {
			showErrorMessage('TDS Percent : ',8);
			document.paymentForm.tax_percent.focus();
			document.paymentForm.tax_percent.select();
			return false ;
		}
		let count = 1;
		for(let i=1; count <= row_count; i++) {
			try {
				voucher_ok_ind = eval("document.paymentForm.voucher_ok_ind"+i+".value");
				this_amount = eval("document.paymentForm.total_amount"+i+".value*1");
				
				if(voucher_ok_ind=='Y') {
					if(this_amount < 0) {
						eval("document.paymentForm.total_amount"+i+".value=''");
					} else {
						gross_amount = gross_amount + this_amount ;
						basic_amount += eval("document.paymentForm.basic_amount"+i+".value")*1 ;
						svtax_amount += eval("document.paymentForm.new_tax_amount"+i+".value")*1 ;
						svces_amount += eval("document.paymentForm.new_tax_cess_amount"+i+".value")*1 ;
						svhce_amount += eval("document.paymentForm.new_tax_hecess_amount"+i+".value")*1 ;
					}
				}
				count++;
			} catch (error) {}
		}
		tax_amount = Math.round(gross_amount * tax_percent / 100) ;
		net_amount = gross_amount - tax_amount ;

		document.paymentForm.total_basic_amount.value = basic_amount ;       format_number(document.paymentForm.total_basic_amount,2);
		document.paymentForm.total_newtax_amount.value = svtax_amount ;       format_number(document.paymentForm.total_newtax_amount,2);
		document.paymentForm.total_newcess_amount.value = svces_amount ;       format_number(document.paymentForm.total_newcess_amount,2);
		document.paymentForm.total_newhecess_amount.value = svhce_amount ;       format_number(document.paymentForm.total_newhecess_amount,2);
		document.paymentForm.gross_amount.value = gross_amount ;       format_number(document.paymentForm.gross_amount,2);
		document.paymentForm.tax_amount.value = tax_amount ;         format_number(document.paymentForm.tax_amount,2);
		document.paymentForm.net_amount.value = net_amount ;         format_number(document.paymentForm.net_amount,2);
	}

	function addNewRow(fld, row_no) {
		var n = (row_no == null) ? 0 : row_no;
		var tabindex    = 0 ;
		var total_row   = (document.paymentForm.row_count.value)*1;
		var user_option = document.paymentForm.user_option.value;

		if ((user_option == 'Add' || user_option == 'Edit')) {
			if((row_no) ? eval("document.paymentForm.ac_desc"+n+".value") != "" : true) {

				n++; //var prev_narration = eval("document.paymentForm.narration"+n+".value"); 
				if (total_row != 0) {
					fld.disabled = true; fld.style.visibility = 'hidden'; 
				} else {
					fld.setAttribute('onClick', `deleteRow('tbody', 'row_count', 'actionBtn1', 'addNewRow')`);
					fld.innerText = "Delete Row";
					let table = document.getElementById('tbody').innerHTML = '';
				}
				
				tabindex = (n*100) + n ;
				document.paymentForm.row_count.value = total_row+1;
				var text = "<tr>";

				if(user_option == 'Add' || user_option == 'Edit') {
					text += `<td id="Ctd2${n}" class="w-150 text-center" onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, ${n})<?php }?>"> <input type="hidden" class="form-control" name="voucher_ok_ind${n}" value="Y" readonly onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, ${n})<?php }?>">
					    <img src="<?= base_url('/public/assets/img/SelectRow.png')?>" class="slctRow" alt="Select">
					</td>`;
					
				} else {
					text += `<td id="Ctd2${n}" class="w-150 text-center"><input class="form-control" type="hidden" name="voucher_ok_ind"${n}" value="" readonly>
					    <img src="<?= base_url('/public/assets/img/SelectRow.png')?>" class="slctRow" alt="Select">
					</td>`;
				}

				text += `
					<tr>	
						<td class="w-150 position-relative"> 
							<input class="form-control" name="main_ac_code${n}" id="mainAcCode${n}" onchange="fetchData(this, 'main_ac_code', [ 'subAcInd${n}','acDesc${n}'], ['sub_ac_ind','main_ac_desc'], 'main_ac_code')" tabindex="${tabindex}" <?= $tag_permissions ?>> 
							<input type="hidden" class="form-control" name="ac_desc${n}" id="acDesc${n}"> 
							<input type="hidden" class="form-control" name="sub_ac_ind${n}" id="subAcInd${n}"> 
							<i class="fa fa-binoculars icn-vw" onclick="showData('main_ac_code', '<?= '4004' ?>', 'mainAcCode${n}', ['subAcInd${n}','acDesc${n}'], ['sub_ac_ind','main_ac_desc'], 'main_ac_code')"  data-toggle="modal" data-target="#lookup"></i>
						</td>						
						<td class="w-150 position-relative"> 
							<input type="text" class="form-control" name="sub_ac_code${n}" id="subAcCode${n}" onchange="fetchData(this, 'sub_ac_code', ['subAcDesc${n}'], ['sub_ac_desc'], 'sub_ac_code')" tabindex="${tabindex}" <?= $tag_permissions ?>> 
							<input type="hidden" class="form-control" name="sub_ac_desc${n}" id="subAcDesc${n}">
							<i class="fa fa-binoculars icn-vw" onclick="showData('sub_ac_code', '<?= '4003' ?>', 'subAcCode${n}', ['subAcDesc${n}'], ['sub_ac_desc'], 'sub_ac_code')"  data-toggle="modal" data-target="#lookup"></i>
						</td>						
						<td class="w-150">  
							<input type="text" class="form-control" name="matter_code${n}" value="" tabindex="${tabindex}"> 
							<input type="hidden" class="form-control" name="matter_name${n}" value="">
						</td>						
						<td class="w-150">
							<div class="d-block">
								<input type="text" class="form-control" name="client_code${n}" value="" tabindex="${tabindex}">
							</div>
						</td>						
						<td class="w-150">
							<div class="d-block">
								<input type="text" class="form-control" name="intl_code${n}" value="" tabindex="${tabindex}">
							</div>
							<div class="d-block">
								<span>Code</span>
								<input type="text" class="form-control" name="ceo_code${n}" value="" tabindex="${tabindex}">
								<input type="hidden" class="form-control" name="name_desc${n}" value="" tabindex="${tabindex}">
							</div>
						</td>
						<td class="w-150 text-left">
							<input type="text" class="form-control" name="expense_code${n}" value="" tabindex="${tabindex}">
							<input type="hidden" class="form-control" name="expense_desc${n}" value="" tabindex="${tabindex}">
							<div class="d-block">
								<span>Code</span>
								<input type="text" class="form-control" name="exps_code${n}" value="" tabindex="${tabindex}">
								<input type="hidden" class="form-control" name="exps_desc${n}" value="" tabindex="${tabindex}">
							</div>
						</td>
						<td class="w-150" colspan="3">
							<table class="w-auto">
								<tbody>
									<tr>
										<td class="w-150">
											<div class="d-block">
												<input class="form-control" name="bill_no${n}" value="" tabindex="${tabindex}">
											</div>
										</td>
										<td class="w-150">
											<div class="d-block">
												<span></span>
												<input class="form-control" name="bill_date${n}" value="" tabindex="${tabindex}" onBlur="make_date(this)">
											</div>
										</td>
										<td class="w-150">
											<div class="d-block">
												<span></span>
												<input class="form-control" name="basic_amount${n}" value="" tabindex="${tabindex}" onBlur="fin_calc_basic_amount(${n});" >
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<div class="d-block">
												<span>Narration</span>
												<textarea class="form-control" name="narration${n}" oninput="this.value = this.value.toUpperCase()" tabindex="${tabindex}"></textarea>
											</div>
										</td>
									</tr>
								</tbody>
							</table>									
						</td>
						<td class="w-150 text-left">
							<div class="d-block">
								<span>Tax %</span>
								<input class="form-control"  name="new_tax_percent${n}" value="0.00" tabindex="${tabindex}" onBlur="fin_calc_newtax_percent(${n});">
							</div>
							<div class="d-block">
								<span>Amount</span>
								<input class="form-control" name="new_tax_amount${n}" value="0.00" tabindex="${tabindex}" onBlur="fin_calc_newtax_amount(${n})">
							</div>
						</td>						
						<td class="wd100">
							<div class="d-block">
								<span>Cess %</span>
								<input class="form-control" name="new_tax_cess_percent${n}" value="0.00" tabindex="${tabindex}" onBlur="fin_calc_newcess_percent(${n});">
							</div>
							<div class="d-block">
								<span>Amount</span>
								<input class="form-control" name="new_tax_cess_amount${n}" value="0.00" tabindex="${tabindex}" onBlur="fin_calc_newcess_amount(${n});">
							</div>
						</td>								
						<td class="wd100">
							<div class="d-block">
								<span>Hec %</span>
								<input class="form-control" name="new_tax_hecess_percent${n}" value="0.00" tabindex="${tabindex}" onBlur="fin_calc_newhecess_percent(${n});">
							</div>
							<div class="d-block">
								<span>Amount</span>
								<input class="form-control" name="new_tax_hecess_amount${n}" value="0.00" tabindex="${tabindex}" onBlur="fin_calc_newhecess_amount(${n});">
							</div>
						</td>								
						<td class="w-150">
						<div class="d-block">
								<span>Service Tax</span>
								<input class="form-control"  name="new_tax_total_amount${n}" value="0.00" readonly>
							</div>
							<div class="d-block">
								<span>Gross Amount</span>
								<input class="form-control" name="total_amount${n}" value="0.00" onBlur="format_number(this,2); fin_amount_calc();" tabindex="${tabindex}">
							</div>
						</td>								
						<td class="text-center"><input type="checkbox" class="cbx" name="billable_ind${n}" value="Y" <?php if($user_option=='Add') {echo 'checked';} else {echo $params['billable_ind_desc'];} ?>></td>
						<td class="text-center TbladdBtn wd100">
							<i class="fa-solid fa-plus" title="ADD" onclick="addNewRow(this, ${n})"></i>
						</td>								
					</tr> 
				`;

				let tbody = document.getElementById("tbody");
				let tr = tbody.insertRow(tbody.rows.length);
				tr.classList.add('fs-14'); tr.innerHTML = text;

				eval(`document.paymentForm.main_ac_code${n}.focus()`);
				eval(`document.paymentForm.main_ac_code${n}.select()`);
			} else {
                alert('Please enter Main A/c code !!');
                document.paymentForm[`main_ac_code${n}`].value = '';
                eval("document.paymentForm.main_ac_code"+n+".focus()");
			}
		}    
	}

	function voucher_delRow(e, n) {
		var row = document.getElementById("Ctd2"+n);
		
		if(eval("document.paymentForm.voucher_ok_ind"+n+".value=='Y'")) {
			$(e).parent('tr').addClass('rowSlcted');
			eval("document.paymentForm.voucher_ok_ind"+n+".value='N'");
			eval("document.paymentForm.voucher_ok_ind"+n+".style.background='#ff0000'");
			eval("document.paymentForm.voucher_ok_ind"+n+".style.color='#ff0000'");
			row.style.background='rgb(163 200 213)';

		} else {
			$(e).parent('tr').removeClass('rowSlcted');
			eval("document.paymentForm.voucher_ok_ind"+n+".value='Y'");
			eval("document.paymentForm.voucher_ok_ind"+n+".style.background='#ECE8D7'");
			eval("document.paymentForm.voucher_ok_ind"+n+".style.color='#ECE8D7'");
			row.style.background='#fff';
		}
		fin_amount_calc();
	}

	function deleteRow(id = '', rowCountId = '', actionBtn = '', callFunction = '') {
		var table = document.getElementById(id);
		var addBtn = table.lastElementChild.lastElementChild.innerHTML;
		var rows = table.querySelectorAll('.rowSlcted');

		if(rows.length > 0) {
			Swal.fire({
				title: 'Do you want to Delete ??',
				showCancelButton: true,
				confirmButtonText: 'Yes!! Delete',
			}).then((result) => {
				if (result.isConfirmed) {
					for (let row of rows) row.remove();

					var table = document.getElementById(id);
					let totalRows = table.children.length;
					let row_no = document.getElementById(rowCountId); row_no.value = parseInt(row_no.value) - rows.length;

					if(totalRows > 0) table.lastElementChild.lastElementChild.firstElementChild.style.visibility = 'visible';
					
					if(totalRows == 0) {
						let btnSpan = document.getElementById(actionBtn);
						btnSpan.firstElementChild.setAttribute('onclick', callFunction + `(this, null)`);
						btnSpan.firstElementChild.innerText = "Add Row";
						table.innerHTML = '<td class="w250" colspan="16"> No Records Added Yet !! </td>';
					}
				}
			})
		} else {
			Swal.fire('Select Atleast <b> One Row </b> to Perform Delete Operation !!')
		}
	}

</script>
<?php } ?>

<?= $this->endSection() ?>