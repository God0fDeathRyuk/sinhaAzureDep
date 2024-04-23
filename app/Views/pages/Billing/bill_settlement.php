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

<div class="pagetitle col-md-12 float-start pb-1">
<h1>Bill Settlement (Old Bills)</h1> 
</div>
<form action="" method="post" id="billSettlement" name="billSettlement" onsubmit="">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
					<select class="form-select" name="branch_code" <?= $permission ?>>
                    <?php foreach($data['branches'] as $branch) { ?>
                      <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-5 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Year/No <strong class="text-danger">*</strong></label>
					<select class="form-select w-48 float-start" name="fin_year" <?= $permission ?> required>
                        <?php foreach($finyr_qry as $finyr_row) { ?>
                        <option value="<?php echo $finyr_row['fin_year']?>" <?php if($data['branch_code']['branch_code'] == $finyr_row['fin_year']) { echo 'selected' ; }?>><?php echo $finyr_row['fin_year']?></option>
                        <?php } ?>
                    </select>
					<input class="form-control float-start w-54 ms-2" id="" type="text"   name="bill_no"        value="<?= ($option == 'show') ? $my_arr1['bill_no'] : '' ?>" <?= $permission ?> required>
					<input class="form-control float-start w-54 ms-2" id="" type="hidden" name="bill_serial_no" value="<?= ($option == 'show') ? $my_arr1['serial_no'] : '' ?>">
				</div>
				<?php if(!isset($my_arr1)) { ?>
				<button type="submit" class="btn btn-primary cstmBtn mt-4 float-start mb-3 mtp-30" onclick="formOption('/billing/settlement/', 'show', 'billSettlement')" <?= $permission ?>>Show</button>				
                <?php } ?>
</form>	
<?php if (isset($my_arr1)){ ?>
<form action="" method="post" id="billSettlement2" name="billSettlement2" onsubmit="" class="position-relative pt-5 d-inline-block w-100">
				<input type="text" class="btn btn-primary cstmBtn mt-4 float-start mb-3 ms-2 NwbdgeTop bdge" name="status_desc" value="<?= ($option == 'show') ? $params['stat_desc'] : '' ?>" readonly>
				<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
				<input type="hidden" name="bill_serial_no"	   value="<?= $params['bill_serial_no'];?>">
				<div class="d-inline-block w-100 mt-3">
					<div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Date</label>
						<input type="text" class="form-control" name="bill_date" value="<?php echo date_conv($my_arr1['bill_date'])?>" readonly/>
					</div>
					<div class="col-md-9 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Client</label>
						<input type="text" class="form-control" name="client_name" value="<?php echo $params['client_name']?>" readonly/>
					</div>
					<div class="col-md-6 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Address</label>
						<input type="text" class="form-control" name="address_line_1" placeholder="address_line_1" value="<?php echo $params['address_line_1']?>" readonly/>
					</div>
					<div class="col-md-6 float-start px-2 mb-3 mtop">
						<input type="text" class="form-control" name="address_line_2" placeholder="address_line_2"  value="<?php echo $params['address_line_2']?>" readonly/>
					</div>
					
					<div class="col-md-6 float-start px-2 mb-3">
						<input type="text" class="form-control" name="address_line_3" placeholder="address_line_3" value="<?php echo $params['address_line_3']?>" readonly/>
					</div>
					<div class="col-md-6 float-start px-2 mb-3">
						<input type="text" class="form-control" name="address_line_4" placeholder="address_line_4" value="<?php echo $params['address_line_4']?>" readonly/>
					</div>


					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Attention</label>
						<input type="text" class="form-control" name="attention_name"    value="<?php echo $params['attention_name']?>" readonly />
					</div>
					<div class="col-md-8 float-start px-2 mb-3 pe-0">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Matter</label>
						<input type="text" class="form-control w-25 float-start" name="matter_code" value="<?php echo $my_arr1['matter_code']?>" readonly/>
						<textarea rows="2" class="form-control w-72 ms-2 float-start" name="matter_desc" readonly><?php echo $params['matter_desc']?></textarea>
					</div> 
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Initial</label>
						<input type="text" class="form-control" name="initial_name"    value="<?php echo $params['initial_name']?>" readonly/>
					</div>
					<div class="col-md-8 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Cause</label>
						<textarea rows="2" class="form-control" name="bill_cause" readonly><?php echo $my_arr1['bill_cause']?></textarea>
					</div>
				</div>
				
				
				<div class="mntblsec d-inline-block w-100 mt-2">
					<table class="table table-bordered">
						<tbody>
							<tr class="fs-14">
								<th><span>&nbsp;</span></th>
								<th><span>Inpocket</span></th>
								<th><span>Outpocket</span></th>
								<th><span>Counsel</span></th>
								<th><span>Service Tax</span></th>
								<th><span>Total</span></th>
							</tr>
							<tr>
								<td><span>Billed</span></td>
								<td><input type="text" name="bill_amount_inpocket" class="form-control"  value="<?php echo $my_arr1['bill_amount_inpocket']?>"  readonly/></td>
								<td><input type="text" name="bill_amount_outpocket" class="form-control" value="<?php echo $my_arr1['bill_amount_outpocket']?>" readonly/></td>
								<td><input type="text" name="bill_amount_counsel" class="form-control"  value="<?php echo $my_arr1['bill_amount_counsel']?>"   readonly/></td>
								<td><input type="text" name="service_tax_amount" class="form-control"  value="<?php echo $params['service_tax_amount']?>"    readonly/></td>
								<td><input type="text" name="bill_amount_total" class="form-control"   value="<?php echo $params['bill_total_amount']?>"     readonly/></td>
							</tr>
							<tr>
								<td><span>Advanced</span></td>
								<td><input type="text" name="advance_amount_inpocket"  class="form-control" value="<?php echo $my_arr1['advance_amount_inpocket']?>"    	readonly/></td>
								<td><input type="text" name="advance_amount_outpocket" class="form-control" value="<?php echo $my_arr1['advance_amount_outpocket']?>"    	readonly/></td>
								<td><input type="text" name="advance_amount_counsel"  class="form-control" value="<?php echo $my_arr1['advance_amount_counsel']?>"    	readonly/></td>
								<td><input type="text" name="advance_amount_service_tax"  class="form-control"   value="<?php echo $params['advance_amount_service_tax']?>"    readonly/></td>
								<td><input type="text" name="advance_amount_total"  class="form-control" value="<?php echo $params['adv_total_amount']?>"    		readonly/></td>
							</tr>
							<tr>
								<td><span>Realised</span></td>
								<td><input type="text" name="realise_amount_inpocket" class="form-control" value="<?php echo $my_arr1['realise_amount_inpocket']?>"    	readonly/></td>
								<td><input type="text" name="realise_amount_outpocket" class="form-control" value="<?php echo $my_arr1['realise_amount_outpocket']?>"    	readonly/></td>
								<td><input type="text" name="realise_amount_counsel" class="form-control" value="<?php echo $my_arr1['realise_amount_counsel']?>"    	readonly/></td>
								<td><input type="text" name="realise_amount_service_tax" class="form-control" value="<?php echo $params['realise_amount_service_tax']?>"    readonly/></td>
								<td><input type="text" name="realise_amount_total" class="form-control" value="<?php echo $params['real_total_amount']?>"    		readonly/></td>
							</tr>
							<tr>
								<td><span>Deficit</span></td>
								<td><input type="text" name="deficit_amount_inpocket"  class="form-control" value="<?php echo $my_arr1['deficit_amount_inpocket']?>"    	readonly/></td>
								<td><input type="text" name="deficit_amount_outpocket" class="form-control" value="<?php echo $my_arr1['deficit_amount_outpocket']?>"    	readonly/></td>
								<td><input type="text" name="deficit_amount_counsel" class="form-control" value="<?php echo $my_arr1['deficit_amount_counsel']?>"    	readonly/></td>
								<td><input type="text" name="deficit_amount_service_tax" class="form-control" value="<?php echo $params['deficit_amount_service_tax']?>"    readonly/></td>
								<td><input type="text" name="deficit_amount_total" class="form-control" value="<?php echo $params['defc_total_amount']?>"    		readonly/></td>
							</tr>
							<tr>
								<td><span>Balance</span></td>
								<td><input type="text" name="balance_amount_inpocket" class="form-control" value="<?php echo $params['baln_amount_inpocket']?>"    	readonly/></td>
								<td><input type="text" name="balance_amount_outpocket" class="form-control" value="<?php echo $params['baln_amount_outpocket']?>"    	readonly/></td>
								<td><input type="text" name="balance_amount_counsel" class="form-control" value="<?php echo $params['baln_amount_counsel']?>"    		readonly/></td>
								<td><input type="text" name="balance_amount_service_tax" class="form-control" value="<?php echo $params['baln_amount_service_tax']?>"    	readonly/></td>
								<td><input type="text" name="balance_amount_total" class="form-control" value="<?php echo $params['baln_total_amount']?>"    		readonly/></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="mntblsec d-inline-block w-100 mt-2">
					<p class="d-inline-block w-100 my-3 bnd">Settlement Details</p>
					
					<table class="table table-bordered">
						<tbody>
							<tr class="fs-14">
								<td><span>Settlled</span></td>
								<td>
									<select class="form-select" name="backlog_ind" onChange="myBackLogInd()">
										<option value="">No</option>
										<option value="S">Yes</option>
									</select>
								</td>
								<td><span>Settlled On</span></td>
								<td>
									<input type="text" class="form-control" name="backlog_date" value="<?php if($my_arr1['backlog_date'] != '0000-00-00') { echo date_conv($my_arr1['backlog_date']); }else {echo  '';} ?>" onBlur="make_date(this)" disabled/>
								</td>
							</td>
							<tr class="fs-14">
								<td><span>Cheque No</span></td>
								<td>
									<input type="text" class="form-control" name="backlog_cheque_no" value="<?php echo $my_arr1['backlog_cheque_no']?>" disabled/>
								</td>
								<td><span>Cheque Date</span></td>
								<td>
									<input type="text" class="form-control" name="backlog_cheque_date" value="<?php if($my_arr1['backlog_cheque_date'] != '0000-00-00') { echo date_conv($my_arr1['backlog_cheque_date']); }else {echo  '';} ?>" onBlur="make_date(this)" disabled/>
								</td>
							</tr>
							<tr class="fs-14">
								<td><span>Bank Name</span></td>
								<td colspan="3">
									<input type="text" class="form-control" name="backlog_cheque_bank" value="<?php echo $my_arr1['backlog_cheque_bank']?>" disabled/>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="mntblsec d-inline-block w-100 mt-2">
					<table class="table table-bordered">
						<tbody>
							<tr class="fs-14">
								<th><span>&nbsp;</span></th>
								<th><span>Inpocket</span></th>
								<th><span>Outpocket</span></th>
								<th><span>Counsel</span></th>
								<th><span>Service Tax</span></th>
								<th><span>Total</span></th>
								<th><span>&nbsp;</span></th>
							</tr>
							<tr>
								<td><span>Realised</span></td>
								<td><input type="text" name="backlog_realise_amount_inpocket" class="form-control" value="<?php echo $my_arr1['backlog_realise_amount_inpocket']?>"     	readonly/></td>
								<td><input type="text" name="backlog_realise_amount_outpocket" class="form-control" value="<?php echo $my_arr1['backlog_realise_amount_outpocket']?>"     	readonly/></td>
								<td><input type="text" name="backlog_realise_amount_counsel" class="form-control" value="<?php echo $my_arr1['backlog_realise_amount_counsel']?>"     	readonly/></td>
								<td><input type="text" name="backlog_realise_amount_service_tax" class="form-control" value="<?php echo $my_arr1['backlog_realise_amount_service_tax']?>"    readonly/></td>
								<td><input type="text" name="backlog_realise_amount_total" class="form-control" value="<?php echo $params['backlog_real_total_amount']?>"     		readonly/></td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><span></span></td>
								<td><input type="text" name="old_backlog_realise_amount_inpocket" class="form-control" value="<?= isset($my_arr1['old_backlog_realise_amount_inpocket']) ? $my_arr1['old_backlog_realise_amount_inpocket'] : '' ?>"     	readonly/></td>
								<td><input type="text" name="old_backlog_realise_amount_outpocket" class="form-control" value="<?= isset($my_arr1['old_backlog_realise_amount_outpocket']) ? $my_arr1['old_backlog_realise_amount_outpocket'] : '' ?>"     	readonly/></td>
								<td><input type="text" name="old_backlog_realise_amount_counsel" class="form-control" value="<?= isset($my_arr1['old_backlog_realise_amount_counsel']) ? $my_arr1['old_backlog_realise_amount_counsel'] : '' ?>"     	readonly/></td>
								<td><input type="text" name="old_backlog_realise_amount_service_tax" class="form-control" value="<?= isset($my_arr1['old_backlog_realise_amount_service_tax']) ? $my_arr1['old_backlog_realise_amount_service_tax'] : '' ?>"    readonly/></td>
								<td><input type="text" name="old_backlog_realise_amount_total" class="form-control" value="<?= isset($my_arr1['old_backlog_realise_amount_total']) ? $my_arr1['old_backlog_realise_amount_total'] : '' ?>"     		readonly/></td>
								<td><input type="text" name="part_full_ind" class="form-control" value=""  readonly/></td>
							</tr>
						</tbody>
					</table>
				</div>			
				<input type="hidden" name="finsub" id="finsub" value="fsub">	
				<button type="submit" class="btn btn-primary cstmBtn mt-2">Confirm</button>
				<button type="reset" class="btn btn-primary cstmBtn mt-2 ms-2">Reset</button>				
				<a href="<?= base_url($params['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</a>
			</div>
</div>
</form>
<?php } ?>
</main>
<script>
	function myBackLogInd()
	 {
        if (document.billSettlement2.backlog_ind.value == 'S') 
		{
          document.billSettlement2.backlog_realise_amount_inpocket.value      = document.billSettlement2.balance_amount_inpocket.value ;
          document.billSettlement2.backlog_realise_amount_outpocket.value     = document.billSettlement2.balance_amount_outpocket.value ;
          document.billSettlement2.backlog_realise_amount_counsel.value       = document.billSettlement2.balance_amount_counsel.value ;
          document.billSettlement2.backlog_realise_amount_service_tax.value   = document.billSettlement2.backlog_realise_amount_service_tax.value ;
          document.billSettlement2.backlog_realise_amount_total.value         = document.billSettlement2.balance_amount_total.value ;


		  //
          document.billSettlement2.backlog_date.disabled        = false ;
          document.billSettlement2.backlog_cheque_no.disabled   = false ;
          document.billSettlement2.backlog_cheque_date.disabled = false ;
          document.billSettlement2.backlog_cheque_bank.disabled = false ;
          document.billSettlement2.backlog_date.focus(); 
		}
		else
		{
          document.billSettlement2.backlog_date.value                         = '' ;
          document.billSettlement2.backlog_cheque_no.value                    = '' ;
          document.billSettlement2.backlog_cheque_date.value                  = '' ;
          document.billSettlement2.backlog_cheque_bank.value                  = '' ;
          document.billSettlement2.backlog_realise_amount_inpocket.value      = '' ;
          document.billSettlement2.backlog_realise_amount_outpocket.value     = '' ;
          document.billSettlement2.backlog_realise_amount_counsel.value       = '' ;
          document.billSettlement2.backlog_realise_amount_service_tax.value   = '' ;
          document.billSettlement2.backlog_realise_amount_total.value         = '' ;
          //
          document.billSettlement2.backlog_date.disabled        = true ;
          document.billSettlement2.backlog_cheque_no.disabled   = true ;
          document.billSettlement2.backlog_cheque_date.disabled = true ;
          document.billSettlement2.backlog_cheque_bank.disabled = true ;
          document.billSettlement2.save_button.focus(); 
		}

     }
</script>
<?= $this->endSection() ?>