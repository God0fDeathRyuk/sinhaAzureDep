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
<h1> Bill Summary Correction  </h1> 
</div>
<form action="" method="post" id="billSummaryCorrection" name="billSummaryCorrection">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
					<select class="form-select" name="branch_code" <?= $permission ?>>
                    <?php foreach($data['branches'] as $branch) { ?>
                      <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-5 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Year/No <strong class="text-danger">*</strong></label>
					<select class="form-select w-48 float-start" name="bill_year" required <?= $permission ?>>
                    <?php foreach($finyr_qry as $finyr_row) { ?>
                        <option value="<?php echo $finyr_row['bill_year']?>" <?php if($data['branch_code']['branch_code'] == $finyr_row['bill_year']) { echo 'selected' ; }?>><?php echo $finyr_row['bill_year']?></option>
                    <?php } ?>
					</select>
					<input class="form-control float-start w-54 ms-2" id="bill_no"        type="text"   name="bill_no"        value="<?= ($option == 'show') ? $params['bill_no'] : ''  ?>" oninput="this.value = this.value.toUpperCase();" required <?= $permission ?>>
					<input class="form-control float-start w-54 ms-2" id="bill_serial_no" type="hidden" name="bill_serial_no" value="<?= ($option == 'show') ? $my_arr1['serial_no'] : ''  ?>">
				</div>
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bill Date</label>
					<input class="form-control float-start w-100" id="bill_date" name="bill_date" type="text"  value="<?= ($option == 'show') ? date_conv($params['bill_date']) : ''  ?>" onBlur="make_date(this)" <?= (isset($my_arr1)) ? '' : 'readonly' ?>>
				</div>

				<div class="col-md-12 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter/Client/Intl</label>
					<input type="text" class="form-control w-35 float-start"      type="text" name="matter_code"  value="<?= ($option == 'show') ? $params['matter_code'] : ''  ?>"  readonly>
					<input type="text" class="form-control w-35 ms-2 float-start" type="text" name="client_code"  value="<?= ($option == 'show') ? $params['client_code'] : ''  ?>"  readonly>
					<input type="text" class="form-control w-28 ms-2 float-start" type="text" name="initial_code" value="<?= ($option == 'show') ? $params['initial_code'] : '' ?>" readonly>
				</div>
				<div class="col-md-4 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Client</label>
					<input type="text" class="form-control w-100 float-start" name="client_name" value="<?= ($option == 'show') ? $params['client_name'] : '' ?>" readonly>
				</div>
				<div class="col-md-8 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter</label>
					<textarea type="text" class="form-control w-100 float-start" rows="2" name="matter_desc" readonly><?= ($option == 'show') ? $params['matter_desc'] : '' ?></textarea>
				</div>
				<button type="submit" class="btn btn-primary cstmBtn mt-2 float-start mb-3" onclick="formOption('/billing/summary-correction/', 'show', 'billSummaryCorrection')" <?= $permission ?>>Show</button>
</form>
<?php if (isset($my_arr1)){ ?>
<form action="" method="post" id="billSummaryCorrection2" name="billSummaryCorrection2" >
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
								<td><input type="text" name="bill_amount_inpocket" class="form-control" value="<?= isset($my_arr1['bill_amount_inpocket']) ? $my_arr1['bill_amount_inpocket'] : 0 ?>"    onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="bill_amount_outpocket" class="form-control" value="<?= isset($my_arr1['bill_amount_outpocket']) ? $my_arr1['bill_amount_outpocket'] : 0 ?>"  onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="bill_amount_counsel" class="form-control" value="<?= isset($my_arr1['bill_amount_counsel']) ? $my_arr1['bill_amount_counsel'] : 0 ?>"      onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="bill_amount_service_tax" class="form-control" value="<?= isset($my_arr1['service_tax_amount']) ? $my_arr1['service_tax_amount'] : 0 ?>" 	   onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="bill_amount_total"  class="form-control" value="" readonly /></td>
							</tr>
							<tr>
								<td><span>Collected</span></td>
								<td><input type="text" name="realise_amount_inpocket" class="form-control" value="<?= isset($my_arr1['realise_amount_inpocket']) ? $my_arr1['realise_amount_inpocket'] : 0  ?>"     	onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="realise_amount_outpocket" class="form-control" value="<?= isset($my_arr1['realise_amount_outpocket']) ? $my_arr1['realise_amount_outpocket'] : 0 ?>"    	onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="realise_amount_counsel" class="form-control"  value="<?= isset($my_arr1['realise_amount_counsel']) ? $my_arr1['realise_amount_counsel'] : 0 ?>"      	onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="realise_amount_service_tax" class="form-control" value="<?= isset($my_arr1['realise_amount_service_tax']) ? $my_arr1['realise_amount_service_tax'] : 0 ?>"  onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="realise_amount_total"  class="form-control"  value="" readonly/></td>
							</tr>
							<tr>
								<td><span>Adjusted</span></td>
								<td><input type="text" name="adjusted_amount_inpocket" class="form-control" value="<?= isset($my_arr1['advance_amount_inpocket']) ? $my_arr1['advance_amount_inpocket'] : 0 ?>"     	onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="adjusted_amount_outpocket" class="form-control" value="<?= isset($my_arr1['advance_amount_outpocket']) ? $my_arr1['advance_amount_outpocket'] : 0 ?>"    	onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="adjusted_amount_counsel" class="form-control" value="<?= isset($my_arr1['advance_amount_counsel']) ? $my_arr1['advance_amount_counsel'] : 0 ?>"      	onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="adjusted_amount_service_tax" class="form-control" value="<?= isset($my_arr1['advance_amount_service_tax']) ? $my_arr1['advance_amount_service_tax'] : 0 ?>" onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="adjusted_amount_total" class="form-control" value="" readonly /></td>
							</tr>
							<tr>
								<td><span>Deficit</span></td>
								<td><input type="text" name="deficit_amount_inpocket" class="form-control" value="<?= isset($my_arr1['deficit_amount_inpocket']) ? $my_arr1['deficit_amount_inpocket'] : 0 ?>"        onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="deficit_amount_outpocket" class="form-control" value="<?= isset($my_arr1['deficit_amount_outpocket']) ? $my_arr1['deficit_amount_outpocket'] : 0 ?>"      onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="deficit_amount_counsel" class="form-control" value="<?= isset($my_arr1['deficit_amount_counsel']) ? $my_arr1['deficit_amount_counsel'] : 0 ?>"         	onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="deficit_amount_service_tax" class="form-control" value="<?= isset($my_arr1['deficit_amount_service_tax']) ? $my_arr1['deficit_amount_service_tax'] : 0 ?>"  onBlur="setFormat(this,2); calc_total()" /></td>
								<td><input type="text" name="deficit_amount_total" class="form-control" value="" readonly /></td>
							</tr>
							<tr>
								<td><span>Balance</span></td>
								<td><input type="text" name="balance_amount_inpocket"  class="form-control" value="" readonly /></td>
								<td><input type="text" name="balance_amount_outpocket" class="form-control" value="" readonly /></td>
								<td><input type="text" name="balance_amount_counsel" class="form-control" value="" readonly /></td>
								<td><input type="text" name="balance_amount_service_tax" class="form-control"  value="" readonly /></td>
								<td><input type="text" name="balance_amount_total" class="form-control" value="" readonly /></td>
							</tr>
							<tr>
								<td><span>Booked</span></td>
								<td><input type="text" name="booked_amount_inpocket" class="form-control" value="<?= isset($my_arr1['booked_amount_inpocket']) ? $my_arr1['booked_amount_inpocket'] : 0  ?>"    	readonly /></td>
								<td><input type="text" name="booked_amount_outpocket" class="form-control" value="<?= isset($my_arr1['booked_amount_outpocket']) ? $my_arr1['booked_amount_outpocket'] : 0  ?>"   	readonly /></td>
								<td><input type="text" name="booked_amount_counsel" class="form-control" value="<?= isset($my_arr1['booked_amount_counsel']) ? $my_arr1['booked_amount_counsel'] : 0  ?>"     	readonly /></td>
								<td><input type="text" name="booked_amount_service_tax" class="form-control" value="<?= isset($my_arr1['booked_amount_service_tax']) ? $my_arr1['booked_amount_service_tax'] : 0 ?>" readonly /></td>
								<td><input type="text" name="booked_amount_total" class="form-control" value="" readonly /></td>
							</tr>
							<tr>
								<td><span>Receivable</span></td>
								<td><input type="text" name="receivable_amount_inpocket" class="form-control" value="" readonly/></td>
								<td><input type="text" name="receivable_amount_outpocket" class="form-control" value="" readonly/></td>
								<td><input type="text" name="receivable_amount_counsel" class="form-control" value="" readonly/></td>
								<td><input type="text" name="receivable_amount_service_tax" class="form-control" value="" readonly/></td>
								<td><input type="text" name="receivable_amount_total" class="form-control" value="" readonly/></td>
							</tr>
							<tr>
								<td><span>Status</span></td>
								<td colspan="5">
                                    <input type="text" name="status_desc" class="form-control" value="" readonly/>
                                    <input type="hidden" name="status_code" class="form-control" value="" readonly/>
                                </td>
							</tr>
						</tbody>
					</table>
				</div>
				<input type="hidden" name="finsub" id="finsub" value="fsub">
				<button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Confirm</button>				
				<a href="<?= base_url($params['requested_url']) ?>" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</a>
			</div>
		<input type="hidden" name="bill_serial_no" id="bill_serial_no" value="<?= $my_arr1['serial_no']?>">
		<input type="hidden" name="bill_date" id="bill_date" value="<?= $params['bill_date'] ?>">

</form>
<?php } ?>
</main>
<script>
function calc_total() {
	console.log('abc');
	var xIptBillAmt = document.billSummaryCorrection2.bill_amount_inpocket.value;
	var xOptBillAmt = document.billSummaryCorrection2.bill_amount_outpocket.value
	//alert(xOptBillAmt);
	var xCnsBillAmt = document.billSummaryCorrection2.bill_amount_counsel.value;
	var xSvtBillAmt = document.billSummaryCorrection2.bill_amount_service_tax.value;
	var xTotBillAmt = (parseFloat(xIptBillAmt) + parseFloat(xOptBillAmt) + parseFloat(xCnsBillAmt) + parseFloat(xSvtBillAmt));
	
	//alert(xTotBillAmt);
	//
	var xIptRealAmt = document.billSummaryCorrection2.realise_amount_inpocket.value;
	var xOptRealAmt = document.billSummaryCorrection2.realise_amount_outpocket.value;
	var xCnsRealAmt = document.billSummaryCorrection2.realise_amount_counsel.value;
	var xSvtRealAmt = document.billSummaryCorrection2.realise_amount_service_tax.value;
	var xTotRealAmt = (parseFloat(xIptRealAmt) + parseFloat(xOptRealAmt) + parseFloat(xCnsRealAmt) + parseFloat(xSvtRealAmt));
	//
	var xIptAdjAmt = document.billSummaryCorrection2.adjusted_amount_inpocket.value;
	var xOptAdjAmt = document.billSummaryCorrection2.adjusted_amount_outpocket.value;
	var xCnsAdjAmt = document.billSummaryCorrection2.adjusted_amount_counsel.value;
	var xSvtAdjAmt = document.billSummaryCorrection2.adjusted_amount_service_tax.value;
	var xTotAdjAmt = (parseFloat(xIptAdjAmt) + parseFloat(xOptAdjAmt) + parseFloat(xCnsAdjAmt) + parseFloat(xSvtAdjAmt));
	//
	var xIptDefAmt = document.billSummaryCorrection2.deficit_amount_inpocket.value;
	var xOptDefAmt = document.billSummaryCorrection2.deficit_amount_outpocket.value;
	var xCnsDefAmt = document.billSummaryCorrection2.deficit_amount_counsel.value;
	var xSvtDefAmt = document.billSummaryCorrection2.deficit_amount_service_tax.value;
	var xTotDefAmt = (parseFloat(xIptDefAmt) + parseFloat(xOptDefAmt) + parseFloat(xCnsDefAmt) + parseFloat(xSvtDefAmt));
	//
	var xIptBalAmt = parseFloat(xIptBillAmt - xIptRealAmt - xIptAdjAmt - xIptDefAmt);
	var xOptBalAmt = parseFloat(xOptBillAmt - xOptRealAmt - xOptAdjAmt - xOptDefAmt);
	var xCnsBalAmt = parseFloat(xCnsBillAmt - xCnsRealAmt - xCnsAdjAmt - xCnsDefAmt);
	var xSvtBalAmt = parseFloat(xSvtBillAmt - xSvtRealAmt - xSvtAdjAmt - xSvtDefAmt);
	var xTotBalAmt = parseFloat(xTotBillAmt - xTotRealAmt - xTotAdjAmt - xTotDefAmt);
	//
	var xIptBkdAmt = document.billSummaryCorrection2.booked_amount_inpocket.value;
	var xOptBkdAmt = document.billSummaryCorrection2.booked_amount_outpocket.value;
	var xCnsBkdAmt = document.billSummaryCorrection2.booked_amount_counsel.value;
	var xSvtBkdAmt = document.billSummaryCorrection2.booked_amount_service_tax.value;
	var xTotBkdAmt = (parseFloat(xIptBkdAmt) + parseFloat(xOptBkdAmt) + parseFloat(xCnsBkdAmt) + parseFloat(xSvtBkdAmt));
	//
	var xIptRcvAmt = parseFloat(xIptBalAmt - xIptBkdAmt);
	var xOptRcvAmt = parseFloat(xOptBalAmt - xOptBkdAmt);
	var xCnsRcvAmt = parseFloat(xCnsBalAmt - xCnsBkdAmt);
	var xSvtRcvAmt = parseFloat(xSvtBalAmt - xSvtBkdAmt);
	var xTotRcvAmt = parseFloat(xTotBalAmt - xTotBkdAmt);
	//

	document.billSummaryCorrection2.bill_amount_total.value = xTotBillAmt; setFormat(document.billSummaryCorrection2.bill_amount_total, 2);
	document.billSummaryCorrection2.realise_amount_total.value = xTotRealAmt; setFormat(document.billSummaryCorrection2.realise_amount_total, 2);
	document.billSummaryCorrection2.adjusted_amount_total.value = xTotAdjAmt; setFormat(document.billSummaryCorrection2.adjusted_amount_total, 2);
	document.billSummaryCorrection2.deficit_amount_total.value = xTotDefAmt; setFormat(document.billSummaryCorrection2.deficit_amount_total, 2);
	//
	document.billSummaryCorrection2.balance_amount_inpocket.value = xIptBalAmt; setFormat(document.billSummaryCorrection2.balance_amount_inpocket, 2);
	document.billSummaryCorrection2.balance_amount_outpocket.value = xOptBalAmt; setFormat(document.billSummaryCorrection2.balance_amount_outpocket, 2);
	document.billSummaryCorrection2.balance_amount_counsel.value = xCnsBalAmt; setFormat(document.billSummaryCorrection2.balance_amount_counsel, 2);
	document.billSummaryCorrection2.balance_amount_service_tax.value = xSvtBalAmt; setFormat(document.billSummaryCorrection2.balance_amount_service_tax, 2);
	document.billSummaryCorrection2.balance_amount_total.value = xTotBalAmt; setFormat(document.billSummaryCorrection2.balance_amount_total, 2);
	//
	document.billSummaryCorrection2.receivable_amount_inpocket.value = xIptRcvAmt; setFormat(document.billSummaryCorrection2.receivable_amount_inpocket, 2);
	document.billSummaryCorrection2.receivable_amount_outpocket.value = xOptRcvAmt; setFormat(document.billSummaryCorrection2.receivable_amount_outpocket, 2);
	document.billSummaryCorrection2.receivable_amount_counsel.value = xCnsRcvAmt; setFormat(document.billSummaryCorrection2.receivable_amount_counsel, 2);
	document.billSummaryCorrection2.receivable_amount_service_tax.value = xSvtRcvAmt; setFormat(document.billSummaryCorrection2.receivable_amount_service_tax, 2);
	document.billSummaryCorrection2.receivable_amount_total.value = xTotRcvAmt; setFormat(document.billSummaryCorrection2.receivable_amount_total, 2);
	//
	if (document.billSummaryCorrection2.balance_amount_total.value > 0) { document.billSummaryCorrection2.status_code.value = 'O'; document.billSummaryCorrection2.status_desc.value = 'Outstanding'; } else { document.billSummaryCorrection2.status_code.value = 'S'; document.billSummaryCorrection2.status_desc.value = 'Settled'; }
} 
document.billSummaryCorrection2.bill_amount_inpocket.focus(); 
setTimeout(() => {document.billSummaryCorrection2.status_desc.focus()}, 500);
</script>
<?= $this->endSection() ?>