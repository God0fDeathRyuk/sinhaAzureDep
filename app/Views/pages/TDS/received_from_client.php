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
      <h1><?= $params['disp_heading'] ?></h1>
    </div>

	<form action="" method="post" name="tdsReceivedForm" onsubmit="return tdsReceivedSubmit(event)">
		<div class="frms-sec d-inline-block w-100 bg-white p-3">
			<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
				<select class="form-select cstm-inpt" name="branch_code">
				<?php foreach($data['branches'] as $branch) { ?>
				<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
				<?php } ?>
				</select>
			</div>
			<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Financial Year</label>
				  <select class="form-select cstm-inpt" name="fin_year" <?= isset($tdscert['tdscert_qry']) ? 'readonly' : '' ?>>
					  <?php foreach($params['finyr_qry'] as $branch) { ?>
						<option value="<?= $branch['fin_year'] ?>" <?php if(session()->financialYear == $branch['fin_year']) { echo 'selected' ; }?> ><?= $branch['fin_year'] ?></option>
					 <?php } ?>
				  </select>
			</div>
					<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Client Name <strong class="text-danger">*</strong></label>
				<div class="position-relative Inpt-Vw-icn w-100">					
					<input type="text" class="form-control" name="client_name" id="clientName" value="<?= $params['client_name'] ?>" readonly/>
					<input type="hidden" class="form-control" name="client_code" id="clientCode" value="<?= $params['client_code'] ?>" readonly/>
					<i class="fa-solid fa-binoculars icn-vw posTopRgt11" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
				</div>
			</div>
			<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Certificate Number <strong class="text-danger">*</strong></label> 
				<input type="text" class="form-control" name="tds_cert_no" placeholder="e.g. WDBYCUA" value="<?= $params['tds_cert_no']?>" <?= isset($tdscert['tdscert_qry']) ? 'readonly' : '' ?>/>
				<!-- Hidden fields -->
				<input type="hidden" class="form-control" name="selemode" value="Y"/>
				<input type="hidden" class="form-control" name="user_option" value="<?= $user_option ?>"/>
			</div>
			<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Certificate Date <strong class="text-danger">*</strong></label>
				<input type="text" class="form-control datepicker" placeholder="dd-mm-yyyy" name="tds_cert_date"  value="<?= $params['tds_cert_date'] ?>" onBlur="make_date(this)" <?= isset($tdscert['tdscert_qry']) ? 'readonly' : '' ?>/>
				<input type="hidden" class="form-control" name="current_date"  value="<?= date('d-m-Y')?>"/>
			</div>
			<?php if (isset($tdscert)) { ?> 
				<div class="tbl-shw-hde">
					<div class="tbl-sec tblMn <?= (count($tdscert['tdscert_qry'])) ? 'tblscrlvtrcl' : '' ?> d-inline-block mt-0 w-100 bg-white p-2 position-relative">
						<table class="table border-0">
							<tr class="fs-14">
								<th class="border"> <span>DB</span> </th>
								<th class="border"> <span>Doc Dt</span> </th>
								<th class="border"> <span>Doc #</span> </th>
								<th class="border"> <span>Instr # </span> </th>
								<th class="border"> <span>Instr Dt</span> </th>
								<th class="border"> <span>Bank</span> </th>
								<th class="border"> <span>Gross </span> </th>
								<th class="border"> <span>TDS </span> </th>
								<th class="border"> <span>Net </span> </th>
								<th class="border"> <span>Recd </span> </th>
							</tr>
							<?php 
									$tot_deposit_amount = 0;
									foreach ($tdscert['tdscert_qry'] as $key => $tdscert_row) {
										if ($tdscert_row['tds_cert_no'] != '') { 
											$checked_ind = 'checked'; 
											$tot_deposit_amount = $tot_deposit_amount + $tdscert_row['tax_amount']; 
										} else $checked_ind = ''; 	
								?> 
								<tr class="fs-14">
									<td class="border"> <input type="text" class="form-control" name="daybook_code<?= $key + 1 ?>" value="<?= $tdscert_row['daybook_code'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="doc_date<?= $key + 1 ?>" value="<?= date_conv($tdscert_row['doc_date']) ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="doc_no<?= $key + 1 ?>" value="<?= $tdscert_row['doc_no'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="instrument_no<?= $key + 1 ?>" value="<?= $tdscert_row['instrument_no'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="instrument_dt<?= $key + 1 ?>" value="<?= $tdscert_row['instrument_no'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="bank_name<?= $key + 1 ?>" value="<?= $tdscert_row['bank_name'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="gross_amount<?= $key + 1 ?>" value="<?= $tdscert_row['gross_amount'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="tax_amount<?= $key + 1 ?>" value="<?= $tdscert_row['tax_amount'] ?>" readonly/> </td>
									<td class="border"> <input type="text" class="form-control" name="net_amount<?= $key + 1 ?>" value="<?= $tdscert_row['net_amount'] ?>" readonly/> </td>
									<td class="border text-center"> <input type="checkbox" name="depind<?= $key + 1 ?>" value="Y" <?= $checked_ind ?> <?= (ucfirst($user_option) == 'View') ? 'disabled' : '' ?> style="width: 22px; height: 22px; margin-top: 7px;"/> </td>
									<input type="hidden" class="form-control" name="serial_no<?= $key + 1 ?>" value="<?= $tdscert_row['serial_no'] ?>" readonly/>
								</tr>
							<?php } if(!count($tdscert['tdscert_qry'])) { ?> <tr class="fs-14"> <td class="border" colspan="10"> No Records Found !! </td> </tr> <?php } ?>
							<input type="hidden" class="form-control" name="tdscert_cnt" value="<?= $tdscert['tdscert_cnt'] ?>" readonly/>
						</table>
					</div>  
				</div>
			<?php } ?>
		</div>
		<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo $_REQUEST['display_id']; ?>" />
        <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo $_REQUEST['menu_id']; ?>" />
		<input type="hidden" name="user_option" id="user_option" value="<?php echo $_REQUEST['user_option']; ?>">
		<?php $return_url = '';
			if(isset($tdscert) && ucfirst($user_option) != 'View') { 
				$return_url = session()->requested_end_menu_url; 
				if(count($tdscert['tdscert_qry'])) { ?>
					<input type="hidden" name="finsub" id="finsub" value="fsub">
				<button type="submit" class="btn btn-primary cstmBtn mt-3"> Save </button>
		<?php } } if (!isset($tdscert)) { 
				$return_url = session()->last_selected_end_menu; ?>
				<button type="submit" class="btn btn-primary cstmBtn mt-3"> Proceed </button>
		<?php } ?>
		<a href="<?= base_url(($return_url != '') ? $return_url : session()->requested_end_menu_url) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
	</form>
</main>

<script>
	// TDS transaction js
	function tdsReceivedSubmit(event) {
		let bcode     = document.tdsReceivedForm.branch_code.value ;
		let finyr     = document.tdsReceivedForm.fin_year.value ; 
		let clntcd    = document.tdsReceivedForm.client_code.value ; 
		let clntnm    = document.tdsReceivedForm.client_name.value ;
		let certno    = document.tdsReceivedForm.tds_cert_no.value ; 
		let certdt    = document.tdsReceivedForm.tds_cert_date.value ; 
		let curdt     = document.tdsReceivedForm.current_date.value ; 
		let crtdtymd  = certdt.substr(6,4)+certdt.substr(3,2)+certdt.substr(0,2) ;
		let cdateymd  = curdt.substr(6,4)+curdt.substr(3,2)+curdt.substr(0,2) ;

		if (document.tdsReceivedForm.branch_code.value == '') {
			Swal.fire({ text: 'Please select Branch Code!!' }).then((result) => { setTimeout(() => {document.tdsReceivedForm.branch_code.focus()}, 500) });
			return false;
		} else if (document.tdsReceivedForm.fin_year.value == '') {
			Swal.fire({ text: 'Please enter Financial Year!!' }).then((result) => { setTimeout(() => {document.tdsReceivedForm.fin_year.focus()}, 500) });
			return false;
		} else if (document.tdsReceivedForm.client_code.value == '') {
			Swal.fire({ text: 'Please Choose Client Name!!' }).then((result) => { setTimeout(() => {document.tdsReceivedForm.client_name.focus()}, 500) });
			return false;
		} else if (document.tdsReceivedForm.tds_cert_no.value == '') {
			Swal.fire({ text: 'Please enter Certificate Number!!' }).then((result) => { setTimeout(() => {document.tdsReceivedForm.tds_cert_no.focus()}, 500) });
			return false;
		} else if (document.tdsReceivedForm.tds_cert_date.value != '' && document.tdsReceivedForm.tds_cert_no.value == '') {
			Swal.fire({ text: 'Please enter Deposit Certificate Number!!' }).then((result) => { setTimeout(() => {document.tdsReceivedForm.tds_cert_no.focus()}, 500) });
			return false;
		} else if (document.tdsReceivedForm.tds_cert_date.value == '' && document.tdsReceivedForm.tds_cert_no.value != '') {
			Swal.fire({ text: 'Please enter Deposit Certificate Date!!' }).then((result) => { setTimeout(() => {document.tdsReceivedForm.tds_cert_date.focus()}, 500) });
			return false;
		} else if (document.tdsReceivedForm.tds_cert_date.value != '' && crtdtymd >= cdateymd) {
			Swal.fire({ html: 'Certificate Date must be <b> Less Then </b> Current Date!!' }).then((result) => { setTimeout(() => {document.tdsReceivedForm.tds_cert_date.focus()}, 500) });
			return false;
		} else return true;
    }
</script>
<?= $this->endSection() ?>