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
        <button type="button" class="btn btn-primary cstmBtn btn-cncl col-md-1 float-end">Exit</button>
    </div><!-- End Page Title -->

    <?php if(!isset($print)) { ?> 
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-2">
			<form action="" method="post" name="f1">
				<div class="frms-sec d-inline-block w-100 bg-white p-3 position-relative">
					<div class="inptSecBtn col-md-2 text-end">
						<input type="hidden" class="form-control" name="status_code" value="<?= $params['status_code'] ?>" readonly>
						<input type="text" class="cstmBtn w-75 text-center text-white" style="background-color:<?= $params['colour_s'] ?>" name="status_desc" value="<?= $params['status_desc'] ?>" readonly>
					</div>
					<?php if($user_option != 'Add') { ?>	
					<div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Serial#</label>
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
					<div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1 position-relative">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Client  <strong class="text-danger">*</strong></label>
						<input type="text" name="associate_code" id="associateCode" class="form-control w-33 float-start" value="<?= $params['associate_code'] ?>" <?= $tag_permissions ?>>
						<input type="text" name="associate_name" id="associateName" value="<?= $params['associate_name'] ?>" class="form-control ms-2 w-65 float-start" onBlur="javascript:(this.value=this.value.toUpperCase());" onChange="document.f1.associate_code.value='';" <?= $tag_permissions ?>>
						<input type="hidden" name="payee_payer_type" id="payeePayerType" value="<?= $params['payee_payer_type'] ?>" readonly>
						<input type="hidden" name="payment_type" id="paymentType" value="<?= $params['payment_type'] ?>" readonly>
						<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                            <i class="fa fa-binoculars icn-vw icn-vw2 lkupIcn" onclick="showData('employee_name', '<?= $displayId['asso_code'] ?>', 'associateName', ['associateCode'], ['employee_id'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
						<?php } ?>
					</div>
                    <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Received From</label>
						<input type="text" class="form-control" name="received_from" value="<?= $params['received_from'] ?>" <?= $tag_permissions ?>>
					</div>
                    <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">By</label>
                        <select class="form-select" name="instrument_type" onBlur="ena_dis('instrument_type',this.value,0)" <?= $tag_permissions ?>>
                        <?php if($user_option=='Add' || $user_option=='Edit') { ?>
                            <option value="C" <?php if($params['instrument_type']=='C') echo 'selected';?>>Cash</option>
                            <option value="D" <?php if($params['instrument_type']=='D') echo 'selected';?>>Draft</option>
                            <option value="Q" <?php if($params['instrument_type']=='Q') echo 'selected';?>>Cheque</option>
                        <?php } else { ?>
                            <option value="<?php echo $params['instrument_type'];?>"><?php if($params['instrument_type']=='C') echo 'Cash'; elseif($params['instrument_type']=='D') echo 'Draft';elseif($params['instrument_type']=='Q') echo 'Cheque';?></option>
                        <?php } ?>
                        </select>
					</div>
                    <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Cheque#</label>
						<input type="text" class="form-control" name="instrument_no" value="<?= $params['instrument_no'] ?>" <?= $tag_permissions ?>>
					</div>
                    <div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Date</label>
						<input type="text" class="form-control datepicker" name="instrument_dt" value="<?= $params['instrument_dt'] ?>" onBlur="make_date(this)" <?= $tag_permissions ?>>
					</div>
                    <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Bank</label>
						<input type="text" class="form-control" name="bank_name" value="<?= $params['bank_name'] ?>" <?= $tag_permissions ?>>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Remarks</label>
						<textarea rows="1" name="remarks" class="form-control" <?= $tag_permissions ?>><?= $params['remarks'] ?></textarea>
					</div>
					<span id="actionBtn1" class="mt-3 d-inline-block float-end">
						<?php if (ucfirst($user_option) == 'Edit' || ucfirst($user_option) == 'Add') { 
							if(count($dtl_data_arr)) { ?>
							<button type="button" onclick="deleteRow('tbody', 'row_count', 'actionBtn1', 'addNewRow')" class="btn btn-primary cstmBtn border border-white float-end mb-2">Delete Row</button> 
						<?php } else { ?>
							<button type="button" onclick="addNewRow(this, null)" class="btn btn-primary cstmBtn border border-white float-end">Add Row</button> 
						<?php } } ?>
					</span>
					
					<div class="d-inline-block w-100 mt-2 ScrltblMn">	
					   				
					<div class="d-inline-block w-100 mt-2 tblScrl">					
						<table class="table table-bordered tblePdngsml">
							<thead>
								<tr class="fs-14">
									<th class=""></th>
									<th class="w-250"> Year </th>
									<th class="w-250"> Bill No. </th>
									<th class="w-250"> Matter </th>
									<th class="w-250"> I/P-Tax </th>
									<th class="w-250"> O/P-Tax </th>
									<th class="w-250"> Counsel-Tax </th>
									<th class="w-250"> I/P-Non Tax </th>
									<th class="w-250"> Reimbursment </th>
									<th class="w-250"> Counsel-Non Tax  </th>
									<th class="w-250"> Total S.Tax </th>
									<th class="w-250"> Part/Full </th>
									<th class="text-center"> Action </th>
								</tr>
							</thead>
							<tbody id="tbody">
								<?php foreach($dtl_data_arr as $key => $dtl_data) { $i = $key+1; $tabindex = ($i * 100) + $i; ?>
								<tr>								
									<td class="wd50 text-center" id="Ctd2<?= $i?>" onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $i ?>)<?php }?>"> <input type="hidden" class="form-control" name="voucher_ok_ind<?= $i?>" value="Y" readonly onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $i;?>)<?php }?>">
									    <img src="<?= base_url('/public/assets/img/SelectRow.png')?>" class="slctRow" alt="Select">
									</td>						
									<td class="w-150"> 
                                        <select class="form-select" name="ref_bill_year<?= $i ?>" id="refBillYear<?= $i ?>" onChange="ena_dis('bill_year',this.value,'<?= $i ?>')" <?= $tag_permissions ?>>
                                        <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                            <?php foreach($years as $row) { ?>
                                                            <option value="<?php echo $row['fin_year']; ?>" <?php if($dtl_data['ref_bill_year'] == $row['fin_year']) echo 'selected';?>><?php echo $row['fin_year']; ?></option>
                                            <?php } } else {?>
                                                <option value="<?php echo $dtl_data['ref_bill_year']; ?>" ><?php echo $dtl_data['ref_bill_year']; ?></option>
                                        <?php } ?>
                                        </select>
									</td>						
									<td class="w-150 position-relative"> 
										<input type="text" class="form-control" name="ref_bill_no<?= $i?>" id="refBillNo" value="<?= $dtl_data['ref_bill_no'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>> 
                                        <?php if($user_option != 'Approve') { ?> 
                                        <i class="fa fa-binoculars icn-vw icn-vw2 lckpicn2" style="top:38px;" onclick="showData('matter_code', 'display_id=<?= $displayId['bill_code'] ?>&row_no=@row_count&ref_bill_no=@refBillYear<?= $i?>', 'refBillNo<?= $i ?>', [], [], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                        <?php } ?>
									</td>						
									<td class="w-150 position-relative">  
										<input type="text" class="form-control" name="matter_code<?= $i?>" id="matterCode<?= $i ?>" value="<?= $dtl_data['matter_code'] ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>> 
                                        <?php if($user_option != 'Approve') { ?> 
                                        <i class="fa fa-binoculars icn-vw icn-vw2 lckpicn2" style="top:38px;" onclick="showData('matter_code', '<?= $displayId['matter_code'] ?>', 'matterCode<?= $i ?>', [], [], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                        <?php } ?>
                                    </td>						
									<td class="w-150">
										<div class="d-block">
											<input type="text" class="form-control" name="bill_amount_inpocket_stax<?= $i ?>" value="<?= isset($dtl_data['bill_amount_inpocket_stax']) ? ($dtl_data['bill_amount_inpocket_stax'] != '') ? $dtl_data['bill_amount_inpocket_stax'] : 0 : 0 ?>" tabindex="<?= $tabindex ?>" readonly>
										</div>
										<div class="d-block pt-1">
											<input type="text" class="form-control" name="realise_amount_inpocket_stax<?= $i ?>" value="<?= isset($dtl_data['realise_amount_inpocket_stax']) ? ($dtl_data['realise_amount_inpocket_stax'] != '') ? $dtl_data['realise_amount_inpocket_stax'] : 0 : 0 ?>" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(<?php echo $i;?>)" tabindex="<?= $tabindex ?>">
										</div>
									</td>						
									<td class="w-150">
										<div class="d-block">
											<input type="text" class="form-control" name="bill_amount_outpocket_stax<?= $i ?>" value="<?= isset($dtl_data['bill_amount_outpocket_stax']) ? ($dtl_data['bill_amount_outpocket_stax'] != '') ? $dtl_data['bill_amount_outpocket_stax'] : 0 : 0 ?>" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?> readonly>
										</div>										
										<div class="d-block pt-1">
											<input type="text" class="form-control" name="realise_amount_outpocket_stax<?= $i ?>" value="<?= isset($dtl_data['realise_amount_outpocket_stax']) ? ($dtl_data['realise_amount_outpocket_stax'] != '') ? $dtl_data['realise_amount_outpocket_stax'] : 0 : 0 ?>" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(<?php echo $i;?>)" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
										</div>										
									</td>
                                    <td class="w-150">  
                                        <div class="d-block">
											<input type="text" class="form-control" name="bill_amount_counsel_stax<?= $i ?>" value="<?= isset($dtl_data['bill_amount_counsel_stax']) ? ($dtl_data['bill_amount_counsel_stax'] != '') ? $dtl_data['bill_amount_counsel_stax'] : 0 : 0 ?>" tabindex="<?= $tabindex ?>" readonly>
										</div> 
                                        <div class="d-block pt-1">
											<input type="text" class="form-control" name="realise_amount_counsel_stax<?= $i ?>" value="<?= isset($dtl_data['realise_amount_counsel_stax']) ? ($dtl_data['realise_amount_counsel_stax'] != '') ? $dtl_data['realise_amount_counsel_stax'] : 0 : 0 ?>" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(<?php echo $i;?>)" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>>
										</div> 
									</td>
                                    <td class="w-150">  
                                        <div class="d-block">
                                            <input type="text" class="form-control" name="bill_amount_inpocket_ntax<?= $i?>" value="<?= isset($dtl_data['bill_amount_inpocket_ntax']) ? ($dtl_data['bill_amount_inpocket_ntax'] != '') ? $dtl_data['bill_amount_inpocket_ntax'] : 0 : 0 ?>" tabindex="<?= $tabindex ?>" readonly> 
                                        </div>
                                        <div class="d-block pt-1">
                                            <input type="text" class="form-control" name="realise_amount_inpocket_ntax<?= $i?>" value="<?= isset($dtl_data['realise_amount_inpocket_ntax']) ? ($dtl_data['realise_amount_inpocket_ntax'] != '') ? $dtl_data['realise_amount_inpocket_ntax'] : 0 : 0 ?>" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(<?php echo $i;?>)" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>> 
                                        </div>
                                    </td>
                                    <td class="w-150">  
                                        <div class="d-block">
                                            <input type="text" class="form-control" name="bill_amount_outpocket_ntax<?= $i?>" value="<?= isset($dtl_data['bill_amount_outpocket_ntax']) ? ($dtl_data['bill_amount_outpocket_ntax'] != '') ? $dtl_data['bill_amount_outpocket_ntax'] : 0 : 0 ?>" tabindex="<?= $tabindex ?>" readonly> 
                                        </div>
                                        <div class="d-block pt-1">
                                            <input type="text" class="form-control" name="realise_amount_outpocket_ntax<?= $i?>" value="<?= isset($dtl_data['realise_amount_outpocket_ntax']) ? ($dtl_data['realise_amount_outpocket_ntax'] != '') ? $dtl_data['realise_amount_outpocket_ntax'] : 0 : 0 ?>" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(<?php echo $i;?>)" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>> 
                                        </div>
									</td>
                                    <td class="w-150">
                                        <div class="d-block">
                                            <input type="text" class="form-control" name="bill_amount_counsel_ntax<?= $i?>" value="<?= isset($dtl_data['bill_amount_counsel_ntax']) ? ($dtl_data['bill_amount_counsel_ntax'] != '') ? $dtl_data['bill_amount_counsel_ntax'] : 0 : 0 ?>" tabindex="<?= $tabindex ?>" readonly> 
                                        </div>  
                                        <div class="d-block pt-1">
                                            <input type="text" class="form-control" name="realise_amount_counsel_ntax<?= $i?>" value="<?= isset($dtl_data['realise_amount_counsel_ntax']) ? ($dtl_data['realise_amount_counsel_ntax'] != '') ? $dtl_data['realise_amount_counsel_ntax'] : 0 : 0 ?>"  onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(<?php echo $i;?>)" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>> 
                                        </div>  
									</td>
                                    <td class="w-150">  
                                        <div class="d-block">
                                            <input type="text" class="form-control" name="os_amount_service_tax<?= $i?>" value="<?= isset($dtl_data['os_amount_service_tax']) ? ($dtl_data['os_amount_service_tax'] != '') ? $dtl_data['os_amount_service_tax'] : 0 : 0 ?>" tabindex="<?= $tabindex ?>" readonly> 
                                        </div>
                                        <div class="d-block pt-1">
                                            <input type="text" class="form-control" name="realise_amount_service_tax<?= $i?>" value="<?= isset($dtl_data['realise_amount_service_tax']) ? ($dtl_data['realise_amount_service_tax'] != '') ? $dtl_data['realise_amount_service_tax'] : 0 : 0 ?>"  onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(<?php echo $i;?>)" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>> 
                                        </div>
									</td>
                                    <td class="w-150">
                                        <div class="d-block"> </div>
                                        <div class="d-block pt-1">
                                            <select class="form-select mt-37" name="part_full_ind<?php echo $i;?>" onKeyPress="return validnumbercheck(event)"   onChange="amount_calc(<?php echo $i;?>)" <?= $tag_permissions ?>>
                                                <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                                    <option value="P" <?php if($dtl_data['part_full_ind'] == 'P') echo 'selected';?>>Part</option>
                                                    <option value="F" <?php if($dtl_data['part_full_ind'] == 'F') echo 'selected';?>>Full</option>
                                                <?php } else {?>
                                                    <option value="<?php echo $dtl_data['part_full_ind'];?>"><?php if($dtl_data['part_full_ind']=='P') echo 'Part';else echo 'Full';?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
									</td>
									<td class="text-center TbladdBtn wd100">
									    <?php if(($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Post-Edit') && count($dtl_data_arr) == $i){?>
										<!-- <input type="button" title="Add Row">  -->
										<i class="fa-solid fa-plus" title="ADD" onclick="addNewRow(this, <?= $i?>)"></i>
										<?php } ?>
                                        <input type="hidden" name="main_ac_code<?= $i ?>"                   value="<?= isset($dtl_data['main_ac_code']) ? $dtl_data['main_ac_code'] : '' ?>">
                                        <input type="hidden" name="sub_ac_code<?= $i ?>"                    value="<?= isset($dtl_data['sub_ac_code']) ? $dtl_data['sub_ac_code'] : '' ?>">
                                        <input type="hidden" name="matter_name<?= $i ?>"                    value="<?= isset($dtl_data['matter_name']) ? $dtl_data['matter_name'] : '' ?>">
                                        <input type="hidden" name="client_code<?= $i ?>"                    value="<?= isset($dtl_data['client_code']) ? $dtl_data['client_code'] : '' ?>">
                                        <input type="hidden" name="initial_code<?= $i ?>"                   value="<?= isset($dtl_data['initial_code']) ? $dtl_data['initial_code'] : '' ?>">
                                        <input type="hidden" name="narration<?= $i ?>"                      value="<?= isset($dtl_data['narration']) ? $dtl_data['narration'] : '' ?>">
                                        <input type="hidden" name="total_amount<?= $i ?>"                   value="<?= isset($dtl_data['total_amount']) ? ($dtl_data['total_amount'] != '') ? $dtl_data['total_amount'] : 0 : 0 ?>">
                                        <input type="hidden" name="deficit_amount_inpocket_stax<?= $i ?>"   value="<?= isset($dtl_data['deficit_amount_inpocket_stax']) ? ($dtl_data['deficit_amount_inpocket_stax'] != '') ? $dtl_data['deficit_amount_inpocket_stax'] : 0 : '' ?>">
                                        <input type="hidden" name="deficit_amount_outpocket_stax<?= $i ?>"  value="<?= isset($dtl_data['deficit_amount_outpocket_stax']) ? ($dtl_data['deficit_amount_outpocket_stax'] != '') ? $dtl_data['deficit_amount_outpocket_stax'] : 0 : '' ?>">
                                        <input type="hidden" name="deficit_amount_counsel_stax<?= $i ?>"    value="<?= isset($dtl_data['deficit_amount_counsel_stax']) ? ($dtl_data['deficit_amount_counsel_stax'] != '') ? $dtl_data['deficit_amount_counsel_stax'] : 0 : '' ?>">
                                        <input type="hidden" name="deficit_amount_inpocket_ntax<?= $i ?>"   value="<?= isset($dtl_data['deficit_amount_inpocket_ntax']) ? ($dtl_data['deficit_amount_inpocket_ntax'] != '') ? $dtl_data['deficit_amount_inpocket_ntax'] : 0 : '' ?>">
                                        <input type="hidden" name="deficit_amount_outpocket_ntax<?= $i ?>"  value="<?= isset($dtl_data['deficit_amount_outpocket_ntax']) ? ($dtl_data['deficit_amount_outpocket_ntax'] != '') ? $dtl_data['deficit_amount_outpocket_ntax'] : 0 : '' ?>">
                                        <input type="hidden" name="deficit_amount_counsel_ntax<?= $i ?>"    value="<?= isset($dtl_data['deficit_amount_counsel_ntax']) ? ($dtl_data['deficit_amount_counsel_ntax'] != '') ? $dtl_data['deficit_amount_counsel_ntax'] : 0 : '' ?>">
                                        <input type="hidden" name="deficit_amount_service_tax<?= $i ?>"     value="<?= isset($dtl_data['deficit_amount_service_tax']) ? ($dtl_data['deficit_amount_service_tax'] != '') ? $dtl_data['deficit_amount_service_tax'] : 0 : '' ?>">
                                        <input type="hidden" name="old_ref_bill_year<?= $i ?>"              value="<?= isset($dtl_data['ref_bill_year']) ? $dtl_data['ref_bill_year'] : '' ?>">
                                        <input type="hidden" name="old_ref_bill_no<?= $i ?>"                value="<?= isset($dtl_data['ref_bill_no']) ? $dtl_data['ref_bill_no'] : '' ?>">
                                        <input type="hidden" name="old_realise_amount_inpocket_stax<?= $i ?>"    value="<?= isset($dtl_data['realise_amount_inpocket_stax']) ? ($dtl_data['realise_amount_inpocket_stax'] != '') ? $dtl_data['realise_amount_inpocket_stax'] : 0 : '' ?>">
                                        <input type="hidden" name="old_realise_amount_outpocket_stax<?= $i ?>"   value="<?= isset($dtl_data['realise_amount_outpocket_stax']) ? ($dtl_data['realise_amount_outpocket_stax'] != '') ? $dtl_data['realise_amount_outpocket_stax'] : 0 : '' ?>">
                                        <input type="hidden" name="old_realise_amount_counsel_stax<?= $i ?>"     value="<?= isset($dtl_data['realise_amount_counsel_stax']) ? ($dtl_data['realise_amount_counsel_stax'] != '') ? $dtl_data['realise_amount_counsel_stax'] : 0 : '' ?>">
                                        <input type="hidden" name="old_realise_amount_inpocket_ntax<?= $i ?>"    value="<?= isset($dtl_data['realise_amount_inpocket_ntax']) ? ($dtl_data['realise_amount_inpocket_ntax'] != '') ? $dtl_data['realise_amount_inpocket_ntax'] : 0 : '' ?>">
                                        <input type="hidden" name="old_realise_amount_outpocket_ntax<?= $i ?>"   value="<?= isset($dtl_data['realise_amount_outpocket_ntax']) ? ($dtl_data['realise_amount_outpocket_ntax'] != '') ? $dtl_data['realise_amount_outpocket_ntax'] : 0 : '' ?>">
                                        <input type="hidden" name="old_realise_amount_counsel_ntax<?= $i ?>"     value="<?= isset($dtl_data['realise_amount_counsel_ntax']) ? ($dtl_data['realise_amount_counsel_ntax'] != '') ? $dtl_data['realise_amount_counsel_ntax'] : 0 : '' ?>">
                                        <input type="hidden" name="old_realise_amount_service_tax<?= $i ?>"      value="<?= isset($dtl_data['realise_amount_service_tax']) ? ($dtl_data['realise_amount_service_tax'] != '') ? $dtl_data['realise_amount_service_tax'] : 0 : '' ?>">	
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
				</div>
				
				<?php if(($status_code == 'A' && $user_option == 'Approve') || ($user_option == 'View')) { ?>
                        <div class="col-md-6 float-start">
                            <div class="frms-sec-insde d-block float-start w-100 px-2 mb-1 position-relative">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Day Book  <strong class="text-danger">*</strong></label>
                                <input type="text" name="daybook_code" id="daybookCode" class="form-control w-33 float-start" value="<?= $params['daybook_code'] ?>" <?php if($user_option != 'Approve') echo "readonly";?>>
                                <input type="text" name="daybook_desc" id="daybookDesc" class="form-control w-65 float-start ms-2" value="<?= $params['daybook_desc'] ?>" readonly>
                                <input type="hidden" name="daybook_type" id="daybookType" value="<?= $params['daybook_type'] ?>" readonly>
                                <?php if($status_code == 'A' && $user_option == 'Approve' && $params['instrument_type'] == 'C') {?>
                                    <i class="fa fa-binoculars icn-vw icn-vw2 lkupIcn" onclick="showData('daybook_code', 'display_id=<?= $displayId['daybook_code']?>&daybook_type=CB', 'daybookCode', ['daybookDesc', 'daybookType'], ['daybook_desc', 'daybook_type'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                <?php }elseif ($status_code == 'A' && $user_option == 'Approve' && $params['instrument_type'] != 'C'){?>
                                    <i class="fa fa-binoculars icn-vw icn-vw2 lkupIcn" onclick="showData('daybook_code', '<?= $displayId['daybook_code'] ?>&daybook_type=BB', 'daybookCode', ['daybookDesc', 'daybookType'], ['daybook_desc', 'daybook_type'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                <?php }?>
                            </div>
                            <div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Voucher Date  <strong class="text-danger">*</strong></label>

                                <input type="text" class="form-control datepicker w-33" name="voucher_date"  value="<?php if ($user_option != 'Post-Edit') {echo date('d-m-Y');} if ($user_option == 'Post-Edit') {echo $params['final_voucher_date'];}?>"  tabindex="2001"  onChange="make_date(this); get_daybook();" onBlur="if(make_date(this)==true) {make_date(this); get_daybook();}" <?php if($user_option=='View') { echo 'readonly' ; } ?>>
                                <input type="hidden" name="current_date" value="<?= $params['global_sysdate'] ?>">
                                <input type="hidden" name="finyr_start_date" value="<?= $params['global_curr_finyr_fymddate'] ?>">
                                <input type="hidden" name="finyr_end_date" value="<?= $params['global_curr_finyr_lymddate'] ?>">
                            </div>
                        </div>
					<?php } ?>  
					<div class="col-md-6 float-end">          
						<div class="d-block w-33 float-end">
							<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Total</label>
								<input type="text" class="form-control text-end" name="gross_amount" value="<?= ($params['gross_amount'] != '') ? $params['gross_amount'] : 0.00 ?>" readonly>
                            </div>
							<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">S.Tax Amount</label>
								<input type="text" class="form-control text-end" name="service_tax_amount" value="<?= ($params['service_tax_amount'] != '') ? $params['service_tax_amount'] : 0.00 ?>" readonly>
                            </div>
							<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">TDS Amount</label>
								<input type="text" class="form-control text-end" name="tax_amount" value="<?= ($params['tax_amount'] != '') ? $params['tax_amount'] : 0.00 ?>" onBlur="format_number(this,2);amount_calc('');" onKeyPress="return validnumbercheck(event)" <?= $tag_permissions ?>>
							</div>
							<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
								<label class="d-inline-block w-100 mb-1 lbl-mn">Net Amount</label>
								<input type="text" class="form-control text-end" name="net_amount" value="<?= ($params['net_amount'] != '') ? $params['net_amount'] : 0.00 ?>" readonly>
							</div>
						</div>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-12 px-2 mt-10">
						<input type="hidden" name="row_count" value="<?= $params['row_count'] ?>" id="row_count" readonly>
						<input type="hidden" name="trans_type" value="<?= $params['trans_type'] ?>" readonly>
						<input type="hidden" name="user_option" value="<?= $user_option ?>">
						<input type="hidden" name="selemode" value="<?= $selemode ?>">
						<input type="hidden" name="bank_name" value="<?= $params['bank_name'] ?>">
                        <input type="hidden" name="tax_code" value="<?= $params['tax_code'] ?>">
                        <input type="hidden" name="tax_account_code" value="<?= $params['tax_account_code'] ?>">
                        <input type="hidden" name="tax_sub_account_code" value="<?= $params['tax_sub_account_code'] ?>">
                        <input type="hidden" name="service_tax_account_code" value="<?= $params['service_tax_account_code'] ?>">
                        
                        <?php if($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Delete' || $user_option == 'Print' || $user_option == 'Approve') { ?>
						    <button type="submit" class="btn btn-primary cstmBtn mt-2">Confirm</button>
                        <?php }?>
                        <a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn btn-primary cstmBtn btn-cncl mt-2">Back</a>
					</div>
			</form>
		  </div>
      </div>
    </section>
    <?php } else { echo view('pages/OtherExpenses/common_print_expenses'); } ?>
</main><!-- End #main -->

<?php if(!isset($print)) { ?> 
<script>
		
    function amount_calc(row_no) {
        var amount_check_ind = 'Y'
        if(row_no != '')
        {
            var ref_bill_year = eval("document.f1.ref_bill_year"+row_no+".value");
            var part_full_ind = eval("document.f1.part_full_ind"+row_no+".value");
            if(ref_bill_year != '' && ref_bill_year != 'ON AC' && ref_bill_year != 'ADVEXP' && ref_bill_year != 'UNKNOWN')
            {
                if(eval("document.f1.realise_amount_inpocket_stax"+row_no+".value*1 > document.f1.bill_amount_inpocket_stax"+row_no+".value*1"))
            {
                // showErrorMessage('Inpocket Amount : ',67);
                eval("document.f1.realise_amount_inpocket_stax"+row_no+".value=''");
                eval("document.f1.realise_amount_inpocket_stax"+row_no+".focus()");
                amount_check_ind = 'N';
                return false ;
            }
            else if(part_full_ind == 'P')
            {
            eval("document.f1.deficit_amount_inpocket_stax"+row_no+".value   = 0");
            eval("document.f1.deficit_amount_outpocket_stax"+row_no+".value  = 0");
            eval("document.f1.deficit_amount_counsel_stax"+row_no+".value    = 0");
            eval("document.f1.deficit_amount_inpocket_ntax"+row_no+".value   = 0");
            eval("document.f1.deficit_amount_outpocket_ntax"+row_no+".value  = 0");
            eval("document.f1.deficit_amount_counsel_ntax"+row_no+".value    = 0");
            eval("document.f1.deficit_amount_service_tax"+row_no+".value     = 0");
            }
            else if(part_full_ind == 'F')
            {
                eval("document.f1.deficit_amount_inpocket_stax"+row_no+".value     = (document.f1.bill_amount_inpocket_stax"+row_no+".value*1  - document.f1.realise_amount_inpocket_stax"+row_no+".value*1)"); 
                eval("document.f1.deficit_amount_outpocket_stax"+row_no+".value    = (document.f1.bill_amount_outpocket_stax"+row_no+".value*1 - document.f1.realise_amount_outpocket_stax"+row_no+".value*1)"); 
                eval("document.f1.deficit_amount_counsel_stax"+row_no+".value      = (document.f1.bill_amount_counsel_stax"+row_no+".value*1   - document.f1.realise_amount_counsel_stax"+row_no+".value*1)"); 
                eval("document.f1.deficit_amount_inpocket_ntax"+row_no+".value     = (document.f1.bill_amount_inpocket_ntax"+row_no+".value*1  - document.f1.realise_amount_inpocket_ntax"+row_no+".value*1)"); 
                eval("document.f1.deficit_amount_outpocket_ntax"+row_no+".value    = (document.f1.bill_amount_outpocket_ntax"+row_no+".value*1 - document.f1.realise_amount_outpocket_ntax"+row_no+".value*1)"); 
                eval("document.f1.deficit_amount_counsel_ntax"+row_no+".value      = (document.f1.bill_amount_counsel_ntax"+row_no+".value*1   - document.f1.realise_amount_counsel_ntax"+row_no+".value*1)"); 
                eval("document.f1.deficit_amount_service_tax"+row_no+".value       = (document.f1.os_amount_service_tax"+row_no+".value*1      - document.f1.realise_amount_service_tax"+row_no+".value*1)"); 
                }
            }
        }

        if(amount_check_ind == 'Y') 
        {
            var voucher_ok_ind = '';  
            var amt_inpocket_stax   = 0.00;
            var amt_outpocket_stax  = 0.00;
            var amt_counsel_stax    = 0.00;
            var amt_inpocket_ntax   = 0.00;
            var amt_outpocket_ntax  = 0.00;
            var amt_counsel_ntax    = 0.00;
            var amt_stax            = 0.00;
            var total_amount        = 0.00;

            var row_count    = document.f1.row_count.value * 1;
            var total_stax   = 0.00 ;
            var net_amount   = 0.00 ;
            var gross_amount = 0.00 ;
            var tax_amount   = document.f1.tax_amount.value*1;
            var this_amount  = 0.00 ;

            if(tax_amount<0)
            {
            // showErrorMessage('TDS Amount : ',8);
            document.f1.tax_amount.focus();
            document.f1.tax_amount.select();
            return false ;
            }

            for(i=1;i<=row_count;i++)
            {
            total_amount   = 0 ;

            voucher_ok_ind       = eval("document.f1.voucher_ok_ind"+i+".value");
            amt_inpocket_stax    = eval("document.f1.realise_amount_inpocket_stax"+i+".value*1");
            amt_outpocket_stax   = eval("document.f1.realise_amount_outpocket_stax"+i+".value*1");
            amt_counsel_stax     = eval("document.f1.realise_amount_counsel_stax"+i+".value*1");
            amt_inpocket_ntax    = eval("document.f1.realise_amount_inpocket_ntax"+i+".value*1");
            amt_outpocket_ntax   = eval("document.f1.realise_amount_outpocket_ntax"+i+".value*1");
            amt_counsel_ntax     = eval("document.f1.realise_amount_counsel_ntax"+i+".value*1");
            amt_stax             = eval("document.f1.realise_amount_service_tax"+i+".value*1");
            if(voucher_ok_ind=='Y')
            {
                if(amt_inpocket_stax < 0)
                {
                // showErrorMessage('Inpocket Tax Amount : ',8);
                eval("document.f1.realise_amount_inpocket_stax"+i+".value=''");
                eval("document.f1.realise_amount_inpocket_stax"+i+".focus()");
                }
                    
                else if(amt_outpocket_stax < 0)
                {
                // showErrorMessage('Outpocket Tax Amount : ',8);
                eval("document.f1.realise_amount_outpocket_stax"+i+".value=''");
                eval("document.f1.realise_amount_outpocket_stax"+i+".focus()");
                }
                
                else if(amt_counsel_stax < 0)
                {
                // showErrorMessage('Counsel Tax Amount : ',8);
                eval("document.f1.realise_amount_counsel_stax"+i+".value=''");
                eval("document.f1.realise_amount_counsel_stax"+i+".focus()");
                }

                else if(amt_inpocket_ntax < 0)
                {
                // showErrorMessage('Inpocket Non-Tax Amount : ',8);
                eval("document.f1.realise_amount_inpocket_ntax"+i+".value=''");
                eval("document.f1.realise_amount_inpocket_ntax"+i+".focus()");
                }
                
                else if(amt_outpocket_ntax < 0)
                {
                // showErrorMessage('Outpocket Non-Tax Amount : ',8);
                eval("document.f1.realise_amount_outpocket_ntax"+i+".value=''");
                eval("document.f1.realise_amount_outpocket_ntax"+i+".focus()");
                }
                
                else if(amt_counsel_ntax < 0)
                {
                // showErrorMessage('Counsel Non-Tax Amount : ',8);
                eval("document.f1.realise_amount_counsel_ntax"+i+".value=''");
                eval("document.f1.realise_amount_counsel_ntax"+i+".focus()");
                }
                else if(amt_stax < 0)
                {
                // showErrorMessage('Service Tax Amount : ',8);
                eval("document.f1.realise_amount_service_tax"+i+".value=''");
                eval("document.f1.realise_amount_service_tax"+i+".focus()");
                }
                else
                {
                total_amount = amt_inpocket_stax + amt_outpocket_stax + amt_counsel_stax + amt_inpocket_ntax + amt_outpocket_ntax + amt_counsel_ntax;
                gross_amount = gross_amount + amt_inpocket_stax + amt_outpocket_stax + amt_counsel_stax + amt_inpocket_ntax + amt_outpocket_ntax + amt_counsel_ntax + amt_stax;
                total_stax   = total_stax + amt_stax ;
                    
                eval("document.f1.total_amount"+i+".value = total_amount") ;
                }
            }
            }

            net_amount = gross_amount - tax_amount ;

            document.f1.gross_amount.value       = gross_amount ;
            document.f1.tax_amount.value         = tax_amount ;
            document.f1.net_amount.value         = net_amount ;
            document.f1.service_tax_amount.value = total_stax ;

            format_number(document.f1.gross_amount,2);
            format_number(document.f1.tax_amount,2);
            format_number(document.f1.net_amount,2);
            format_number(document.f1.service_tax_amount,2);
        }
    }

    function addNewRow(fld, row_no) {
		var n = (row_no == null) ? 0 : row_no;
        let tabindex = 0 ;
        let total_row = (document.f1.row_count.value)*1;
        let user_option = document.f1.user_option.value;

        if ((user_option == 'Add' || user_option == 'Edit')) {
            
            if((n) ? eval("document.f1.ref_bill_year"+n+".value") == "" : false) {
                eval("document.f1.ref_bill_year"+n+".focus()");

            } else {
                n++;
				if (total_row != 0) {
					fld.disabled = true; fld.style.visibility = 'hidden'; 
				} else {
					fld.setAttribute('onClick', `deleteRow('tbody', 'row_count', 'actionBtn1', 'addNewRow')`);
					fld.innerText = "Delete Row";
					let table = document.getElementById('tbody').innerHTML = '';
				}

                tabindex = (n*100) + n;
                document.f1.row_count.value = n; // var f_year_cnt = eval("document.f1.ref_bill_year"+total_row+".length")-1;

                var text  = `
                    <tr>								
                        <td class="w-150 text-center" id="Ctd2${n}" onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, ${n})<?php }?>"> <input type="hidden" class="form-control" name="voucher_ok_ind${n}" value="Y" readonly onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, ${n})<?php }?>">
                            <img src="<?= base_url('/public/assets/img/SelectRow.png')?>" class="slctRow" alt="Select">
                        </td>						
                        <td class="w-150"> 
                            <select class="form-select" name="ref_bill_year${n}" onChange="ena_dis('bill_year',this.value, ${n})" <?= $tag_permissions ?>>
                            <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                <option value="">-- Select --</option>
                                <option value="ON AC">ON AC</option>
                                <?php foreach($years as $row) { ?>
                                    <option value="<?php echo $row['fin_year']; ?>"><?php echo $row['fin_year']; ?></option>
                                <?php } } else { ?>
                                    <option value="" ></option>
                            <?php } ?>
                            </select>
                        </td>					
                        <td class="w-150 position-relative"> 
                            <input type="text" class="form-control" name="ref_bill_no${n}" id="refBillNo" value="" tabindex="${tabindex}" <?= $tag_permissions ?>> 
                            <?php if($user_option != 'Approve') { ?> 
                            <i class="fa fa-binoculars icn-vw icn-vw2 lkupIcn" style="top:18px;" onclick="showData('matter_code', 'display_id=<?= $displayId['bill_code'] ?>&row_no=@row_count&ref_bill_no=@refBillYear${n}', 'refBillNo${n}', [], [], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            <?php } ?>
                        </td>						
                        <td class="w-150 position-relative">  
                            <input type="text" class="form-control" name="matter_code${n}" id="matterCode${n}" value="" tabindex="${tabindex}" <?= $tag_permissions ?>> 
                            <?php if($user_option != 'Approve') { ?> 
                            <i class="fa fa-binoculars icn-vw icn-vw2" style="top:18px;" onclick="showData('matter_code', '<?= $displayId['matter_code'] ?>', 'matterCode${n}', [], [], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            <?php } ?>
                        </td>						
                        <td class="w-150">
                            <div class="d-block">
                                <input type="text" class="form-control" name="bill_amount_inpocket_stax${n}" tabindex="${tabindex}" readonly>
                            </div>
                            <div class="d-block pt-1">
                                <input type="text" class="form-control" name="realise_amount_inpocket_stax${n}" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(${n})" tabindex="${tabindex}">
                            </div>
                        </td>						
                        <td class="w-150">
                            <div class="d-block">
                                <input type="text" class="form-control" name="bill_amount_outpocket_stax${n}" tabindex="${tabindex}" <?= $tag_permissions ?> readonly>
                            </div>										
                            <div class="d-block pt-1">
                                <input type="text" class="form-control" name="realise_amount_outpocket_stax${n}" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(${n})" tabindex="${tabindex}" <?= $tag_permissions ?>>
                            </div>										
                        </td>
                        <td class="w-150">  
                            <div class="d-block">
                                <input type="text" class="form-control" name="bill_amount_counsel_stax${n}" tabindex="${tabindex}" readonly>
                            </div> 
                            <div class="d-block pt-1">
                                <input type="text" class="form-control" name="realise_amount_counsel_stax${n}" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(${n})" tabindex="${tabindex}" <?= $tag_permissions ?>>
                            </div> 
                        </td>
                        <td class="w-150">  
                            <div class="d-block">
                                <input type="text" class="form-control" name="bill_amount_inpocket_ntax${n}" tabindex="${tabindex}" readonly> 
                            </div>
                            <div class="d-block pt-1">
                                <input type="text" class="form-control" name="realise_amount_inpocket_ntax${n}" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(${n})" tabindex="${tabindex}" <?= $tag_permissions ?>> 
                            </div>
                        </td>
                        <td class="w-150">  
                            <div class="d-block">
                                <input type="text" class="form-control" name="bill_amount_outpocket_ntax${n}" tabindex="${tabindex}" readonly> 
                            </div>
                            <div class="d-block pt-1">
                                <input type="text" class="form-control" name="realise_amount_outpocket_ntax${n}" onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(${n})" tabindex="${tabindex}" <?= $tag_permissions ?>> 
                            </div>
                        </td>
                        <td class="w-150">
                            <div class="d-block">
                                <input type="text" class="form-control" name="bill_amount_counsel_ntax${n}" tabindex="${tabindex}" readonly> 
                            </div>  
                            <div class="d-block pt-1">
                                <input type="text" class="form-control" name="realise_amount_counsel_ntax${n}"  onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(${n})" tabindex="${tabindex}" <?= $tag_permissions ?>> 
                            </div>  
                        </td>
                        <td class="w-150">  
                            <div class="d-block">
                                <input type="text" class="form-control" name="os_amount_service_tax${n}" tabindex="${tabindex}" readonly> 
                            </div>
                            <div class="d-block pt-1">
                                <input type="text" class="form-control" name="realise_amount_service_tax${n}"  onKeyPress="return validnumbercheck(event)" onBlur="format_number(this,2); amount_calc(${n})" tabindex="${tabindex}" <?= $tag_permissions ?>> 
                            </div>
                        </td>
                        <td class="w-150">
                            <div class="d-block"> </div>
                            <div class="d-block pt-1">
                                <select class="form-select mt-37" name="part_full_ind${n}" onKeyPress="return validnumbercheck(event)"   onChange="amount_calc(${n})" <?= $tag_permissions ?>>
                                    <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                        <option value="P">Part</option>
                                        <option value="F">Full</option>
                                    <?php } else {?>
                                        <option value=""></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </td>
                        <td class="text-center TbladdBtn wd100">
                            <?php if($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Post-Edit' ){?>
                            <!-- <input type="button" title="Add Row">  -->
                            <i class="fa-solid fa-plus" title="ADD" onclick="addNewRow(this, ${n})"></i>
                            <?php } ?>
                            <input type="hidden" name="main_ac_code${n}">
                            <input type="hidden" name="sub_ac_code${n}">
                            <input type="hidden" name="matter_name${n}">
                            <input type="hidden" name="client_code${n}">
                            <input type="hidden" name="initial_code${n}">
                            <input type="hidden" name="narration${n}">
                            <input type="hidden" name="total_amount${n}">
                            <input type="hidden" name="deficit_amount_inpocket_stax${n}">
                            <input type="hidden" name="deficit_amount_outpocket_stax${n}">
                            <input type="hidden" name="deficit_amount_counsel_stax${n}">
                            <input type="hidden" name="deficit_amount_inpocket_ntax${n}">
                            <input type="hidden" name="deficit_amount_outpocket_ntax${n}">
                            <input type="hidden" name="deficit_amount_counsel_ntax${n}">
                            <input type="hidden" name="deficit_amount_service_tax${n}">
                            <input type="hidden" name="old_ref_bill_year${n}">
                            <input type="hidden" name="old_ref_bill_no${n}">
                            <input type="hidden" name="old_realise_amount_inpocket_stax${n}">
                            <input type="hidden" name="old_realise_amount_outpocket_stax${n}">
                            <input type="hidden" name="old_realise_amount_counsel_stax${n}">
                            <input type="hidden" name="old_realise_amount_inpocket_ntax${n}">
                            <input type="hidden" name="old_realise_amount_outpocket_ntax${n}">
                            <input type="hidden" name="old_realise_amount_counsel_ntax${n}">
                            <input type="hidden" name="old_realise_amount_service_tax${n}">	
                        </td>								
                    </tr>`;
                
				let tbody = document.getElementById("tbody");
				let tr = tbody.insertRow(tbody.rows.length);
				tr.classList.add('fs-14'); tr.innerHTML = text;

                eval("document.f1.ref_bill_year"+n+".focus()");
            }
        }    
    }

	function voucher_delRow(e, n) {
		var row = document.getElementById("Ctd2"+n);
		
		if(eval("document.f1.voucher_ok_ind"+n+".value=='Y'")) {
			$(e).parent('tr').addClass('rowSlcted');
			eval("document.f1.voucher_ok_ind"+n+".value='N'");
			eval("document.f1.voucher_ok_ind"+n+".style.background='#ff0000'");
			eval("document.f1.voucher_ok_ind"+n+".style.color='#ff0000'");
			row.style.background='rgb(163 200 213)';

		} else {
			$(e).parent('tr').removeClass('rowSlcted');
			eval("document.f1.voucher_ok_ind"+n+".value='Y'");
			eval("document.f1.voucher_ok_ind"+n+".style.background='#ECE8D7'");
			eval("document.f1.voucher_ok_ind"+n+".style.color='#ECE8D7'");
			row.style.background='#fff';
		}
        amount_calc('');
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