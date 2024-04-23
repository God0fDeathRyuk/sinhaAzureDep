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
		</div>
	<?php endif; ?>

	<div class="pagetitle">
      <h1><?= $params['disp_heading'] . " [" . ucfirst($user_option) . "]" ?></h1>
    </div>

	<form action="" method="post" name="tdsDepositForm" onsubmit="return tdsDepositSubmit(event)">
		<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
					<select class="form-select cstm-inpt" name="branch_code" <?= isset($tdscert['tdscert_qry']) ? 'readonly' : '' ?>>
					<?php foreach($data['branches'] as $branch) { ?>
					<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
					<?php } ?>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Deposite Date <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control <?= isset($tdscert['tdscert_qry']) ? '' : 'datepicker' ?>" placeholder="dd-mm-yyyy" name="tds_deposit_date" value="<?= $params['tds_deposit_date'] ?>" id="depdate" onBlur="make_date(this)" <?= isset($tdscert['tdscert_qry']) ? 'readonly' : '' ?>/>
					<input type="hidden" class="form-control" name="current_date" value="<?= date('d-m-Y') ?>" id="depdate"/>
					<input type="hidden" class="form-control" name="selemode" value="Y"/>
					<input type="hidden" class="form-control" name="user_option" value="<?= $user_option ?>"/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Deposite Bank <strong class="text-danger">*</strong></label>
					<div class="position-relative Inpt-Vw-icn w-100">					
						<input type="text" class="form-control" name="bank_name" id="bankName" value="<?php echo $params['bank_name'] ?>" readonly/>
						<input type="hidden" class="form-control" name="bank_code" id="bankCode" value="<?php echo $params['bank_code'] ?>" readonly/>
						<?php if(!isset($tdscert['tdscert_qry'])) { ?>
						<i class="fa-solid fa-binoculars icn-vw posTopRgt11" onclick="showData('bank_code', '<?= $displayId['bank_help_id'] ?>', 'bankCode', ['bankName'], ['bank_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
					    <?php } ?>
					</div>
				</div>
				
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Challan No <strong class="text-danger">*</strong></label> 
					<input type="text" class="form-control" name="tds_challan_no" value="<?php echo $params['tds_challan_no'] ?>" <?= isset($tdscert['tdscert_qry']) ? 'readonly' : '' ?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Cheque # <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="tds_cheque_no"  value="<?php echo $params['tds_cheque_no'] ?>" <?= isset($tdscert['tdscert_qry']) ? 'readonly' : '' ?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Cheque Date <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control <?= isset($tdscert['tdscert_qry']) ? '' : 'datepicker' ?>" placeholder="dd-mm-yyyy" name="tds_cheque_date" value="<?= $params['tds_cheque_date'] ?>" onBlur="make_date(this)" <?= isset($tdscert['tdscert_qry']) ? 'readonly' : '' ?>/>					
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Cheque Bank <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="tds_cheque_bank"  value="<?php echo $params['tds_cheque_bank'] ?>" <?= isset($tdscert['tdscert_qry']) ? 'readonly' : '' ?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Total</label>
					<input type="text" class="form-control" name="total_value"  value="0.00" readonly/>
				</div>
				<?php if (isset($tdscert)) { ?> 
					<div class="tbl-shw-hde">
						<div class="tbl-sec d-inline-block mt-0 w-100 bg-white p-2 position-relative">
							<table class="table border-0">
								<tr class="fs-14">
									<th class="border"> <span>DB</span> </th>
									<th class="border"> <span>Doc Dt</span> </th>
									<th class="border"> <span>Doc #</span> </th>
									<th class="border"> <span>Narration</span> </th>
									<th class="border"> <span>Gross</span> </th>
									<th class="border"> <span>TDS</span> </th>
									<th class="border"> <span>dep </span> </th>
								</tr>
								<?php 
									$tot_deposit_amount = 0;
									foreach ($tdscert['tdscert_qry'] as $key => $tdscert_row) {
										if ($tdscert_row['tds_deposit_ind'] == 'Y') { 
											$checked_ind = 'checked'; 
											$tot_deposit_amount = $tot_deposit_amount + $tdscert_row['tax_amount']; 
										} else $checked_ind = ''; 	
								?> 
								<tr class="fs-14">
									<td class="border"> <input type="text" class="form-control" name="daybook_code<?= $key + 1 ?>" value="<?= $tdscert_row['daybook_code'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="doc_date<?= $key + 1 ?>" value="<?= date_conv($tdscert_row['doc_date']) ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="doc_no<?= $key + 1 ?>" value="<?= $tdscert_row['doc_no'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="narration<?= $key + 1 ?>" value="<?= $tdscert_row['narration'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="gross_amount<?= $key + 1 ?>" value="<?= $tdscert_row['gross_amount'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="tax_amount<?= $key + 1 ?>" value="<?= $tdscert_row['tax_amount'] ?>" readonly/> </td>
									<td class="border text-center"> <input type="checkbox" onclick="calcTotal(this, <?= $key + 1 ?>)" name="depind<?= $key + 1 ?>" value="Y" <?= $checked_ind ?> <?= (ucfirst($user_option) == 'View') ? 'disabled' : '' ?> style="width: 22px; height: 22px; margin-top: 7px;"/> </td>
									<input type="hidden" class="form-control" name="serial_no<?= $key + 1 ?>" value="<?= $tdscert_row['serial_no'] ?>" readonly/>
								</tr>
								<?php } if(!count($tdscert['tdscert_qry'])) { ?> <tr class="fs-14"> <td class="border" colspan="10"> No Records Found !! </td> </tr> <?php } ?>
									<input type="hidden" class="form-control" name="total_deposit_amount" value="<?= $tot_deposit_amount ?>" readonly/>
									<input type="hidden" class="form-control" name="tdscert_cnt" value="<?= $tdscert['tdscert_cnt'] ?>" readonly/>
							</table>
						</div>  
					</div>
				<?php } ?>
		</div>
		<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
		<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
		<?php $return_url = '';
			if(isset($tdscert) && ucfirst($user_option) != 'View') { 
				$return_url = session()->requested_end_menu_url; 
				if(count($tdscert['tdscert_qry'])) { ?>
				<input type="hidden" name="finsub" id="finsub" value="fsub">
				<button type="submit" class="btn btn-primary cstmBtn mt-3" <?php echo $disview;?>> Save </button>
		<?php } } if (!isset($tdscert)) { 
				$return_url = session()->last_selected_end_menu; ?>
				<input type="hidden" name="finsub" id="finsub" value="nsub">
				<button type="submit" class="btn btn-primary cstmBtn mt-3" <?php echo $disview;?>> Proceed </button>
		<?php } ?>
		<?php if($user_option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
                        <?php } ?> 
		<a href="<?= base_url(($return_url != '') ? $return_url : session()->requested_end_menu_url) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
	</form>
          
</main>

<script>
	document.tdsDepositForm.total_value.value = document.tdsDepositForm.total_deposit_amount.value;
	
	// TDS transaction js
	function tdsDepositSubmit(event) {
		let bcode     = document.tdsDepositForm.branch_code.value ;
		let depdt     = document.tdsDepositForm.tds_deposit_date.value ; 
		let depbk     = document.tdsDepositForm.bank_name.value ; 
		let chlno     = document.tdsDepositForm.tds_challan_no.value ;
		let chqno     = document.tdsDepositForm.tds_cheque_no.value ; 
		let chqdt     = document.tdsDepositForm.tds_cheque_date.value ; 
		let chqbk     = document.tdsDepositForm.tds_cheque_bank.value ; 
		let curdt     = document.tdsDepositForm.current_date.value ; 
		let depdtymd  = depdt.substr(6,4)+depdt.substr(3,2)+depdt.substr(0,2) ;
		let chqdtymd  = chqdt.substr(6,4)+chqdt.substr(3,2)+chqdt.substr(0,2) ;
		let cdateymd  = curdt.substr(6,4)+curdt.substr(3,2)+curdt.substr(0,2) ;
 
		if (document.tdsDepositForm.branch_code.value == '') {
			Swal.fire({ text: 'Please Enter Branch Code !!' }).then((result) => { setTimeout(() => {document.tdsDepositForm.branch_code.focus()}, 500) });
			return false;
		} else if (document.tdsDepositForm.tds_deposit_date.value == '') {
			Swal.fire({ text: 'Please enter Deposit Date!!'}).then((result) => { setTimeout(() => {document.tdsDepositForm.tds_deposit_date.focus()}, 500) });
			return false;
		} else if (depdtymd > cdateymd) {
			Swal.fire({ html: 'Deposit Date must be <b> Less Then </b> Current Date!!'}).then((result) => { setTimeout(() => {document.tdsDepositForm.tds_deposit_date.focus()}, 500) });
			return false;
		} else if (document.tdsDepositForm.bank_name.value == '') {
			Swal.fire({ text: 'Please enter Deposit Bank Name!!'}).then((result) => { setTimeout(() => {document.tdsDepositForm.bank_name.focus()}, 500) });
			return false;
		} else if (document.tdsDepositForm.tds_challan_no.value == '') {
			Swal.fire({ text: 'Please enter Deposit Challan No!!'}).then((result) => { setTimeout(() => {document.tdsDepositForm.tds_challan_no.focus()}, 500) });
			return false;
		} else if (document.tdsDepositForm.tds_cheque_date.value != '' && document.tdsDepositForm.tds_cheque_no.value == '') {
			Swal.fire({ text: 'Please enter Deposit Cheque No!!'}).then((result) => { setTimeout(() => {document.tdsDepositForm.tds_challan_no.focus()}, 500) });
			return false;
		} else if (document.tdsDepositForm.tds_cheque_date.value != '' && chqdtymd >= cdateymd) {
			Swal.fire({ html: 'Cheque Date must be <b> Less Then </b> Current Date!!'}).then((result) => { setTimeout(() => {document.tdsDepositForm.tds_cheque_date.focus()}, 500) });
			return false;
		} else if (document.tdsDepositForm.tds_cheque_date.value != '' && chqdtymd >  depdtymd) {
			Swal.fire({ html: 'Deposit Date must be <b> Less Then </b> Deposit Date!!'}).then((result) => { setTimeout(() => {document.tdsDepositForm.tds_cheque_date.focus()}, 500) });
			return false;
		} else if (document.tdsDepositForm.tds_cheque_date.value != '' && document.tdsDepositForm.tds_cheque_bank.value == '') {
			Swal.fire({ text: 'Please enter Cheque Bank Name!!'}).then((result) => { setTimeout(() => {document.tdsDepositForm.tds_cheque_bank.focus()}, 500) });
			return false;
		} else return true;
    }

	function calcTotal(tag, index = 0) {
        var tot_amount = document.tdsDepositForm.total_value.value * 1;
        var tds_amount = document.tdsDepositForm['tax_amount'+index].value * 1;

        if(tag.checked == true) tot_amount = tot_amount + tds_amount;
		else tot_amount = tot_amount - tds_amount;

        document.tdsDepositForm.total_value.value = tot_amount;
        format_number(document.tdsDepositForm.total_value, 2);
    }
</script>

<?= $this->endSection() ?>