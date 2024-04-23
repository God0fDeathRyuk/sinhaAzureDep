<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?= view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">
    <div class="pagetitle d-inline-block w-100">
        <h1 class="col-md-11 float-start">Receipt-Others <span class="badge rounded-pill bg-dark"><?= ucfirst($user_option) ?></span></h1>
        <button type="button" class="btn btn-primary cstmBtn btn-cncl col-md-1 float-end">Exit</button>
    </div><!-- End Page Title -->

    <?php if(!isset($print)) { ?> 
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-2">
			<form action="" method="post" name="paymentForm">
				<div class="frms-sec d-inline-block w-100 bg-white p-3 position-relative">
					<div class="inptSecBtn col-md-2 text-end">
						<input type="hidden" class="form-control" name="status_code" value="<?= $params['status_code'] ?>" readonly>
						<input type="text" class="cstmBtn w-75 text-center text-white" style="background-color:<?= $params['colour_s'] ?>" name="status_desc" value="<?= $params['status_desc'] ?>" readonly>
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
					<div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1 position-relative">
						<label class="d-inline-block w-100 mb-1 lbl-mn">From  <strong class="text-danger">*</strong></label>
						<select class="form-select w-33 float-start me-1" name="payee_payer_type" onChange="document.paymentForm.associate_name.value=''; document.paymentForm.associate_code.value='';" <?= $tag_permissions ?>>
						<?php if($user_option=='Add' || $user_option=='Edit') { ?>
							<option value="E" <?php if($params['payee_payer_type']=='E') echo 'selected';?>>Employee</option>
							<option value="S" <?php if($params['payee_payer_type']=='S') echo 'selected';?>>Supplier</option>
							<option value="C" <?php if($params['payee_payer_type']=='C') echo 'selected';?>>Counsel</option>
							<option value="K" <?php if($params['payee_payer_type']=='K') echo 'selected';?>>Clerk</option>
							<option value="T" <?php if($params['payee_payer_type']=='T') echo 'selected';?>>Stenographer</option>
							<option value="A" <?php if($params['payee_payer_type']=='A') echo 'selected';?>>Arbitrator</option>
							<option value="O" <?php if($params['payee_payer_type']=='O') echo 'selected';?>>Others</option>
						<?php } else { ?>
							<?php if($params['payee_payer_type'] == "E"){?><option value="E">Employee</option><?php }?>
							<?php if($params['payee_payer_type'] == "S"){?><option value="S">Supplier</option><?php }?>
							<?php if($params['payee_payer_type'] == "C"){?><option value="C">Counsel</option><?php }?>
							<?php if($params['payee_payer_type'] == "K"){?><option value="K">Clerk</option><?php }?>
							<?php if($params['payee_payer_type'] == "T"){?><option value="T">Stenographer</option><?php }?>
							<?php if($params['payee_payer_type'] == "A"){?><option value="A">Arbitrator</option><?php }?>
							<?php if($params['payee_payer_type'] == "O"){?><option value="O">Others</option><?php }?>
						<?php } ?>
						</select>
						<input type="text" name="associate_name" id="associateName" value="<?= $params['associate_name'] ?>" class="form-control w-65 float-start pe-5" onBlur="javascript:(this.value=this.value.toUpperCase());" onChange="document.paymentForm.associate_code.value='';" readonly>
						<input type="hidden" name="associate_code" id="associateCode" value="<?= $params['associate_code'] ?>">
						<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                            <i class="fa fa-binoculars icn-vw icn-vw2" style="top: 38px;right: 25px;" onclick="showData('employee_name', '<?= $displayId['asso_code'] ?>', 'associateName', ['associateCode'], ['employee_id'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
						<?php } ?>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">By  <strong class="text-danger">*</strong></label>
						<input type="hidden" name="payment_type">

						<select class="form-select" name="instrument_type" onchange="ena_dis('instrument_type',this.value)" <?= $tag_permissions ?>>
						<?php if($user_option=='Add' || $user_option=='Edit') { ?>
							<option value="C" <?php if($params['instrument_type']=='C') echo 'selected';?>>Cash</option>
                            <option value="D" <?php if($params['instrument_type']=='D') echo 'selected';?>>Draft</option>
                            <option value="Q" <?php if($params['instrument_type']=='Q') echo 'selected';?>>Cheque</option>
						<?php } else { 
							if($params['instrument_type'] == "C"){?><option value="C">Cash</option><?php }
                            if($params['instrument_type'] == "D"){?><option value="D">Draft</option><?php }
                            if($params['instrument_type'] == "Q"){?><option value="Q">Cheque</option><?php } ?>
						<?php } ?>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Cheque#</label>
						<input type="text" name="instrument_no" value="<?= $params['instrument_no'] ?>" class="form-control" <?= ($params['instrument_type']=='C') ? 'readonly' : '' ?> <?= $tag_permissions ?>>
					</div>			
					<div class="frms-sec-insde d-block float-start col-md-2 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Date</label>
						<input type="text" name="instrument_dt" value="<?= $params['instrument_dt'] ?>" class="form-control" <?= ($params['instrument_type']=='C') ? 'readonly' : '' ?> <?= $tag_permissions ?>>
					</div>			
					<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Bank</label>
						<input type="text" name="bank_name" value="<?= $params['bank_name'] ?>" class="form-control" <?= ($params['instrument_type']=='C') ? 'readonly' : '' ?> <?= $tag_permissions ?>>
					</div>			
					<div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Remarks</label>
						<textarea rows="1" name="remarks" class="form-control" <?= $tag_permissions ?>><?= $params['remarks'] ?></textarea>
					</div>			
					<div class="d-inline-block w-100 mt-2 <?= (count($dtl_data_arr)) ? 'tblscrlvtrcllrg' : '' ?>">	
					<span id="actionBtn1">
						<?php if (ucfirst($user_option) == 'Edit' || ucfirst($user_option) == 'Add') { 
							if(isset($dtl_data_arr)) { ?>
							<button type="button" onclick="deleteRow('tbody', 'row_count', 'actionBtn1', 'addNewRow')" class="btn btn-primary cstmBtn border border-white float-end mb-2">Delete Row</button> 
						<?php } else { ?>
							<button type="button" onclick="addNewRow(this, null)" class="btn btn-primary cstmBtn border border-white float-end">Add Row</button> 
						<?php } } ?>
					</span>   				
					<div class="d-inline-block w-100 mt-2 <?= (count($dtl_data_arr)) ? 'tblscrlvtrcllrg' : '' ?>">					
						<table class="table table-bordered tblePdngsml">
							<thead>
								<tr class="fs-14">
									<th class="wd50"></th>
									<th class="w-250">Main</th>
									<th class="w-250">Sub</th>
									<th class="w-250">Matter</th>
									<th class="w-250">Client</th>
									<th class="w-250">Intl</th>
									<th class="w-500">Narration</th>
									<th class="text-center w-150">Amount</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="tbody">
								<?php foreach($dtl_data_arr as $key => $dtl_data) { $i = $key+1; $tabindex = ($i * 100) + $i; ?>
								<tr>								
									<td class="text-center" id="Ctd2<?= $i?>" onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $i;?>)<?php }?>"> <input type="hidden" class="form-control" name="voucher_ok_ind<?= $i?>" value="Y" readonly onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $i;?>)<?php }?>">
									    <img src="<?= base_url('/public/assets/img/SelectRow.png')?>" class="slctRow" alt="Select">
									</td>						
									<td class=""> 
										<div class="position-relative">
											<input class="form-control" name="main_ac_code<?= $i?>" id="mainAcCode<?= $i ?>" value="<?= $dtl_data['main_ac_code'];?>" onChange="value_changed('main_ac_code',<?php echo $i;?>)" tabindex="<?= $tabindex;?>" <?= $tag_permissions ?>> 
											<input type="hidden" class="form-control" name="ac_desc<?= $i?>" value="<?= $dtl_data['ac_desc'];?>"> 
											<input type="hidden" class="form-control" name="sub_ac_ind<?= $i?>" value="<?= $dtl_data['sub_ac_ind'];?>">
											<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                                <i class="fa fa-binoculars icn-vw icn-vw2" onclick="showData('main_ac_code', '<?= $displayId['main_ac_code'] ?>', 'mainAcCode<?= $i ?>', [], [], '')" title="View"  data-toggle="modal" data-target="#lookup"></i>
                    						<?php } ?>
										</div>
									</td>						
									<td class=""> 
										<div class="position-relative">
											<input type="text" class="form-control" name="sub_ac_code<?= $i?>" id="subAcCode<?= $i ?>" value="<?= $dtl_data['sub_ac_code'] ?>" tabindex="<?= $tabindex ?>" onChange="value_changed('sub_ac_code',<?php echo $i;?>)" <?= $tag_permissions ?>> 
											<input type="hidden" class="form-control" name="sub_ac_desc<?= $i ?>" id="subAcDesc<?= $i ?>" value="<?= $dtl_data['sub_ac_desc'] ?>">
											<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                                <i class="fa fa-binoculars icn-vw icn-vw2" onclick="showData('sub_ac_code', 'display_id=<?= $displayId['sub_ac_code'] ?>&main_ac_code=@mainAcCode<?= $i ?>', 'subAcCode<?= $i ?>', ['subAcDesc<?= $i ?>'], ['sub_ac_desc'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                    						<?php } ?>
										</div>
									</td>						
									<td class="">  
										<div class="position-relative">
											<input type="text" class="form-control" name="matter_code<?= $i?>" id="matterCode<?= $i?>" value="<?= $dtl_data['matter_code'] ?>" tabindex="<?= $tabindex ?>" onblur="fetchData(this, 'matter_code', ['matterCode<?= $i?>', 'matterName<?= $i?>', 'clientCode<?= $i ?>', 'intlCode<?= $i ?>'], ['matter_code', 'matter_name', 'client_code', 'initial_code'], 'matter_code')" onChange="value_changed('matter_code',<?php echo $i;?>)" <?= $tag_permissions ?>> 
											<input type="hidden" class="form-control" name="matter_name<?= $i ?>" id="matterName<?= $i ?>" value="<?= $dtl_data['matter_name'] ?>">
											<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                                <i class="fa fa-binoculars icn-vw icn-vw2" onclick="showData('matter_code', '<?= $displayId['matter_code'] ?>', 'matterCode<?= $i?>', ['matterName<?= $i ?>'], ['matter_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                    						<?php } ?>
										</div>
									</td>						
									<td class="">
										<div class="d-block">
											<input type="text" class="form-control" name="client_code<?= $i ?>" id="clientCode<?= $i ?>" value="<?= $dtl_data['client_code'] ?>" tabindex="<?= $tabindex ?>" readonly>
										</div>
									</td>						
									<td class="">
										<div class="d-block">
											<input type="text" class="form-control" name="intl_code<?= $i ?>" id="intlCode<?= $i ?>" value="<?= $dtl_data['intl_code'] ?>" tabindex="<?= $tabindex ?>" readonly>
										</div>
									</td>
									<td class="w-350">	
										<textarea class="form-control" name="narration<?= $i ?>" oninput="this.value = this.value.toUpperCase()" tabindex="<?= $tabindex ?>" <?= $tag_permissions ?>><?= $dtl_data['narration'] ?></textarea>	
									</td>								
									<td class="text-center">
										<input type="text" class="form-control" name="total_amount<?= $i ?>" value="<?= $dtl_data['total_amount'] ?>" onBlur="format_number(this,2);amount_calc('<?= $params['global_curr_finyear'] ?>');" <?= $tag_permissions;?>>
									</td>
									<td class="text-center TbladdBtn wd100">
										<?php if(($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Post-Edit') && count($dtl_data_arr) == $i){?>
											<i class="fa-solid fa-plus" title="ADD" onclick="addNewRow(this, <?= $i?>)"></i>
										<?php } ?>
									</td>								
								</tr>
								<?php } if (!count($dtl_data_arr)) { ?>
									<tr>
										<td class="w-150 text-center" colspan="16"> No Records Found !!</td>						
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				
				<?php if(($status_code == 'A' && $user_option == 'Approve') || ($user_option == 'View')) { ?>

				<div class="col-md-6 float-start">
    				<div class="frms-sec-insde d-block float-start w-33 px-2 mb-1">
    					<label class="d-inline-block w-100 mb-1 lbl-mn">Voucher Date  <strong class="text-danger">*</strong></label>
    
    					<input type="text" class="form-control datepicker" name="voucher_date"  value="<?php if ($user_option != 'Post-Edit') {echo date('d-m-Y');} if ($user_option == 'Post-Edit') {echo $params['final_voucher_date'];}?>"  tabindex="2001"  onChange="make_date(this); get_daybook();" onBlur="if(make_date(this)==true) {make_date(this); get_daybook();}" <?php if($user_option=='View') { echo 'readonly' ; } ?>>
    					<input type="hidden" name="current_date" value="<?= $params['global_sysdate'] ?>">
    					<input type="hidden" name="finyr_start_date" value="<?= $params['global_curr_finyr_fymddate'] ?>">
    					<input type="hidden" name="finyr_end_date" value="<?= $params['global_curr_finyr_lymddate'] ?>">
    				</div>
    				<div class="frms-sec-insde d-block float-start w-65 px-2 mb-1">
    					<label class="d-inline-block w-100 mb-1 lbl-mn">Day Book  <strong class="text-danger">*</strong></label>
    					<input type="type" class="form-control float-start w-33" name="daybook_code" value="<?= $params['daybook_code'] ?>" readonly>
    					<input type="type" class="form-control float-start w-65 ms-1" name="daybook_desc" value="<?= $params['daybook_desc'] ?>" readonly>
    					<input type="hidden" name="daybook_type" value="<?= $params['daybook_type'] ?>" readonly>
    				</div>
			    </div>
            <?php } ?>
				<div class="d-block float-end w-33">
						<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-1 position-relative">
							<label class="d-inline-block w-100 mb-1 lbl-mn">Tax A/c</label>
							<input type="text" class="form-control text-start" name="tax_account_code" id="taxAccountCode" value="<?= $params['tax_account_code'] ?>" readonly>
							<input type="hidden" name="tax_account_desc" id="taxAccountDesc" value="<?= $params['tax_account_desc'] ?>">
							<input type="hidden" class="form-control w-33 ms-1 float-start" name="tax_sub_account_code" value="<?= $params['tax_sub_account_code'] ?>">
							<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                <i class="fa fa-binoculars icn-vw icn-vw2" style="top: 38px;right: 25px;" onclick="showData('main_ac_code', '<?= $displayId['tax_code'] ?>', 'taxAccountCode', ['taxAccountDesc'], ['main_ac_desc'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
    						<?php } ?>
						</div>
						<div class="col-md-6 float-start">          
							<div class="d-block w-100 float-start">
								<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
									<label class="d-inline-block w-100 mb-1 lbl-mn">Gross</label>
									<input type="text" class="form-control text-end" name="gross_amount" value="<?= ($params['gross_amount'] != '') ? $params['gross_amount'] : 0 ?>" readonly>	
								</div>
								<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
									<label class="d-inline-block w-100 mb-1 lbl-mn">TDS</label>
									<input type="text" class="form-control text-end" name="tax_amount" value="<?= $params['tax_amount']?>" onBlur="format_number(this,2); amount_calc();" <?= ($user_option == 'Add' || $user_option == 'Edit') ? '' : 'readonly' ?>>
        							<input type="hidden" name="tax_code" value="<?= $params['tax_code'] ?>">
        							<input type="hidden" name="tax_percent" value="<?= $params['tax_percent'] ?>">
								</div>
								<div class="frms-sec-insde d-block float-start w-100 px-2 mb-1">
									<label class="d-inline-block w-100 mb-1 lbl-mn">Net</label>
									<input type="text" class="form-control text-end" name="net_amount" value="<?= $params['net_amount'] ?>" readonly>
								</div>
							</div>
						</div>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-12 px-2 mt-10">
						<input type="hidden" name="row_count" value="<?= $params['row_count'] ?>" id="row_count" readonly>
						<input type="hidden" name="trans_type" value="<?= $params['trans_type'] ?>" readonly>
						<input type="hidden" name="user_option" value="<?= $user_option ?>">
                        <input type="hidden" name="selemode" value="<?= $selemode ?>">

                        <button type="submit" class="btn btn-primary cstmBtn mt-2">Confirm</button>
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
    function amount_calc(param1) {
      var voucher_ok_ind = '';  
    
      var row_count    = document.paymentForm.row_count.value * 1;
      var net_amount   = 0.00 ;
      var gross_amount = 0.00 ;
      var tax_amount   = document.paymentForm.tax_amount.value*1;
      var this_amount  = 0.00 ;
    
      if(tax_amount<0)
      {
        showErrorMessage('TDS Amount : ',8);
        document.paymentForm.tax_amount.focus();
        document.paymentForm.tax_amount.select();
        return false ;
      }
    
      for(i=1;i<=row_count;i++)
      {
        voucher_ok_ind = eval("document.paymentForm.voucher_ok_ind"+i+".value");
        this_amount = eval("document.paymentForm.total_amount"+i+".value*1");
        if(voucher_ok_ind=='Y')
        {
          if(this_amount < 0)
          {
            showErrorMessage('Amount : ',8);
            eval("document.paymentForm.total_amount"+i+".value=''");
          }
          else
          {
            gross_amount = gross_amount + this_amount ;
          }
        }
      }
    
      document.paymentForm.gross_amount.value = gross_amount ;
      //  mytaxcalc(param1);
      document.paymentForm.tax_amount.value   = tax_amount ;
      net_amount = gross_amount - tax_amount ;
      document.paymentForm.net_amount.value   = net_amount ;
    
      format_number(document.paymentForm.gross_amount,2);
      format_number(document.paymentForm.tax_amount,2);
      format_number(document.paymentForm.net_amount,2);
    }
      
	function value_changed(input_value,row_no) {
      if(input_value == 'main_ac_code')
      {
        eval("document.paymentForm.ac_desc"+row_no+".value=''");
        eval("document.paymentForm.sub_ac_ind"+row_no+".value=''");
        eval("document.paymentForm.sub_ac_code"+row_no+".value=''");
        eval("document.paymentForm.sub_ac_desc"+row_no+".value=''");
      }
      else if(input_value == 'sub_ac_code')
      {
        eval("document.paymentForm.sub_ac_desc"+row_no+".value=''");
      }
      
        else if(input_value == 'matter_code')
      {
        eval("document.paymentForm.matter_name"+row_no+".value=''");
        eval("document.paymentForm.client_code"+row_no+".value=''");
        eval("document.paymentForm.intl_code"+row_no+".value=''");
      }
      else if(input_value == 'expense_code')
      {
        eval("document.paymentForm.expense_desc"+row_no+".value=''");
      }
    
      
    }
		
	function ena_dis(input_str,input_val) {
      if(input_str == 'instrument_type')
      {
        if(input_val == 'C')
        {
          document.paymentForm.remarks.focus();
          document.paymentForm.remarks.select();
          document.paymentForm.instrument_no.value    = '';
          document.paymentForm.instrument_dt.value    = '';
          document.paymentForm.bank_name.value        = '';
          document.paymentForm.instrument_no.readOnly = true;
          document.paymentForm.instrument_dt.readOnly = true;
          document.paymentForm.bank_name.readOnly     = true;
        }
        else
        {
          document.paymentForm.instrument_no.readOnly = false;
          document.paymentForm.instrument_dt.readOnly = false;
          document.paymentForm.bank_name.readOnly     = false;
          document.paymentForm.instrument_no.focus();
          document.paymentForm.instrument_no.select();
        }
      }
    }
    
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
					<td class=""> 
						<div class="position-relative">
							<input class="form-control" name="main_ac_code${n}" id="mainAcCode${n}" onChange="value_changed('main_ac_code',${n})" tabindex="<?= $tabindex;?>" <?= $tag_permissions ?>> 
							<input type="hidden" class="form-control" name="ac_desc${n}"> 
							<input type="hidden" class="form-control" name="sub_ac_ind${n}">
							<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
								<i class="fa fa-binoculars icn-vw icn-vw2" onclick="showData('main_ac_code', '<?= $displayId['main_ac_code'] ?>', 'mainAcCode${n}', [], [], '')" title="View"  data-toggle="modal" data-target="#lookup"></i>
							<?php } ?>
						</div>
					</td>						
					<td class=""> 
						<div class="position-relative">
							<input type="text" class="form-control" name="sub_ac_code${n}" id="subAcCode${n}" tabindex="${tabindex}" onChange="value_changed('sub_ac_code',${n})" <?= $tag_permissions ?>> 
							<input type="hidden" class="form-control" name="sub_ac_desc${n}" id="subAcDesc${n}">
							<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
								<i class="fa fa-binoculars icn-vw icn-vw2" onclick="showData('sub_ac_code', 'display_id=<?= $displayId['sub_ac_code'] ?>&main_ac_code=@mainAcCode${n}', 'subAcCode${n}', ['subAcDesc${n}'], ['sub_ac_desc'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
							<?php } ?>
						</div>
					</td>						
					<td class="">  
						<div class="position-relative">
							<input type="text" class="form-control" name="matter_code${n}" id="matterCode${n}" tabindex="${tabindex}" onblur="fetchData(this, 'matter_code', ['matterCode${n}', 'matterName${n}', 'clientCode${n}', 'intlCode${n}'], ['matter_code', 'matter_name', 'client_code', 'initial_code'], 'matter_code')" onChange="value_changed('matter_code',<?php echo $i;?>)" <?= $tag_permissions ?>> 
							<input type="hidden" class="form-control" name="matter_name${n}" id="matterName${n}">
							<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
								<i class="fa fa-binoculars icn-vw icn-vw2" onclick="showData('matter_code', '<?= $displayId['matter_code'] ?>', 'matterCode${n}', ['matterName${n}'], ['matter_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
							<?php } ?>
						</div>
					</td>						
					<td class="">
						<div class="d-block">
							<input type="text" class="form-control" name="client_code${n}" id="clientCode${n}" tabindex="${tabindex}" readonly>
						</div>
					</td>						
					<td class="">
						<div class="d-block">
							<input type="text" class="form-control" name="intl_code${n}" id="intlCode${n}" tabindex="${tabindex}" readonly>
						</div>
					</td>
					<td class="w-350">	
						<textarea class="form-control" name="narration${n}" oninput="this.value = this.value.toUpperCase()" tabindex="${tabindex}" <?= $tag_permissions ?>></textarea>	
					</td>								
					<td class="text-center">
						<input type="text" class="form-control" name="total_amount${n}" onBlur="format_number(this,2);amount_calc('<?= $params['global_curr_finyear'] ?>');" <?= $tag_permissions;?>>
					</td>
					<td class="text-center TbladdBtn wd100">
						<?php if($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Post-Edit' ){?>
							<i class="fa-solid fa-plus" title="ADD" onclick="addNewRow(this, ${n})"></i>
						<?php } ?>
					</td>								
				</tr>
				`;

				let tbody = document.getElementById("tbody");
				let tr = tbody.insertRow(tbody.rows.length);
				tr.classList.add('fs-14'); tr.innerHTML = text;

				eval(`document.paymentForm.main_ac_code${n}.focus()`);
				eval(`document.paymentForm.main_ac_code${n}.select()`);
			} else {
		console.log('hello');

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
		amount_calc();
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