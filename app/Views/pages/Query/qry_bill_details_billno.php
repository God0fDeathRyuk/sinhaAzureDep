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
<?php endif;?>
<div class="pagetitle">
      <h1>Bill Details (By Bill No)</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
			  <input type="hidden" id="display_id" name="display_id" value="<?php echo $_REQUEST['display_id']; ?>">
			  <input type="hidden" id="menu_id" name="menu_id" value="<?php echo $_REQUEST['menu_id'] ?>">
			  <input type="hidden" id="query_id" name="query_id" value="<?php echo $query_id ?>">

				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
					<select class="form-select" name="branch_code" id="branchCode"  required >
						<?php foreach ($data as $key => $value) {?>
						<option value="<?= $value['branch_code'] ?>"><?= $value['branch_name'] ?></option>
						<?php } ?>
					</select>
				</div>
				<?php  ?>
				<div class="col-md-5 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Bill Year/No <strong class="text-danger">*</strong></label>					
					<select class="form-select w48 float-start" name="fin_year" id="fin_year" required >
						<?php foreach ($fin_years as $key => $value) {?>
						<option value="<?= $value['fin_year'] ?>"><?= $value['fin_year'] ?></option>
						<?php } ?>
					</select>
					<span class="w-2 float-start mx-1 mt-2">/</span>
					<input type="text" class="form-control w48 float-start"  name="bill_no" id="bill_no" required>
				</div>
				<div class="col-md-4 float-start mt-20 position-relative">
				<input type="button" name="button" id="button" value="Show" class="btn btn-primary cstmBtn mt-4"  onClick="getBillDetails('branch_code=@branchCode','fin_year=@fin_year','bill_no=@bill_no')">		
					<button type="button" class="btn btn-primary mt-4 float-start mb-3 ms-2 tpbdgeSec w-50" name="status_desc" id="status_desc">&nbsp;</button>
				</div>
				<div class="d-block float-start w-100 mt-3">
					<div class="col-md-4 px-2 float-start mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Bill Date <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control datepicker" name="bill_date"  id="bill_date" />
					</div>
					<div class="col-md-8 px-2 float-start mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Client <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="client_name" id="client_name"  />
						<input type="hidden" name="client_code" id="client_code">
					</div>
					<div class="col-md-12 px-2 float-start mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Address <strong class="text-danger">*</strong></label>
						<textarea rows="2" class="form-control" name="address_line_1" id="address_line_1"></textarea>
					</div>
					<div class="col-md-4 px-2 float-start mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Attention <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="attention_name" id="attention_name"/>
					</div>
					<div class="col-md-8 px-2 float-start mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Matter <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control w-33 float-start" name="matter_code" id="matter_code"/>
						<textarea rows="2" class="form-control ms-2 w-65 float-start" name="matter_desc" id="matter_desc"></textarea>
					</div>
					<div class="col-md-4 px-2 float-start mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Initial <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="initial_name" id="initial_name"/>
					</div>
					<div class="col-md-8 px-2 float-start mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Cause <strong class="text-danger">*</strong></label>
						<textarea rows="2" class="form-control" name="bill_cause" id="bill_cause"></textarea>
					</div>
				</div>
				<div class="d-block float-start w-100 px-2 mt-2">
					<table class="table table-bordered tblePdngsml mt-3">
						<tr class="fs-14">						
							<th>&nbsp;</th>
							<th>Inpocket</th>
							<th>Outpocket</th>
							<th>Counsel</th>
							<th>Service Tax</th>
							<th>Total</th>
						</tr>
						<tr class="fs-14">
							<td>Billed</td>
							<td><input type="text" class="form-control" name="bill_amount_inpocket" id="bill_amount_inpocket"/></td>
							<td><input type="text" class="form-control" name="bill_amount_outpocket" id="bill_amount_outpocket"/></td>
							<td><input type="text" class="form-control" name="bill_amount_counsel" id="bill_amount_counsel"/></td>
							<td><input type="text" class="form-control" name="service_tax_amount" id="service_tax_amount"/></td>
							<td><input type="text" class="form-control" name="bill_amount_total" id="bill_amount_total"/></td>
						</tr>
						<tr class="fs-14">
							<td>Advance</td>
							<td><input type="text" class="form-control" name="advance_amount_inpocket" id="advance_amount_inpocket"/></td>
							<td><input type="text" class="form-control" name="advance_amount_outpocket" id="advance_amount_outpocket"/></td>
							<td><input type="text" class="form-control" name="advance_amount_counsel" id="advance_amount_counsel"/></td>
							<td><input type="text" class="form-control" name="advance_amount_service_tax" id="advance_amount_service_tax"/></td>
							<td><input type="text" class="form-control" name="advance_amount_total" id="advance_amount_total"/></td>
						</tr>
						<tr class="fs-14">
							<td>Realised</td>
							<td><input type="text" class="form-control" name="realise_amount_inpocket" id="realise_amount_inpocket"/></td>
							<td><input type="text" class="form-control" name="realise_amount_outpocket" id="realise_amount_outpocket"/></td>
							<td><input type="text" class="form-control" name="realise_amount_counsel" id="realise_amount_counsel"/></td>
							<td><input type="text" class="form-control" name="realise_amount_service_tax" id="realise_amount_service_tax"/></td>
							<td><input type="text" class="form-control" name="realise_amount_total" id="realise_amount_total"/></td>
						</tr>
						<tr class="fs-14">
							<td>Deficit</td>
							<td><input type="text" class="form-control" name="deficit_amount_inpocket" id="deficit_amount_inpocket"/></td>
							<td><input type="text" class="form-control" name="deficit_amount_outpocket" id="deficit_amount_outpocket"/></td>
							<td><input type="text" class="form-control" name="deficit_amount_counsel" id="deficit_amount_counsel"/></td>
							<td><input type="text" class="form-control" name="deficit_amount_service_tax" id="deficit_amount_service_tax"/></td>
							<td><input type="text" class="form-control" name="deficit_amount_total" id="deficit_amount_total"/></td>
						</tr>
						<tr class="fs-14">
							<td>Balance</td>
							<td><input type="text" class="form-control" name="balance_amount_inpocket" id="balance_amount_inpocket"/></td>
							<td><input type="text" class="form-control" name="balance_amount_outpocket" id="balance_amount_outpocket"/></td>
							<td><input type="text" class="form-control" name="balance_amount_counsel" id="balance_amount_counsel"/></td>
							<td><input type="text" class="form-control" name="balance_amount_service_tax" id="balance_amount_service_tax"/></td>
							<td><input type="text" class="form-control" name="balance_amount_total" id="balance_amount_total"/></td>
						</tr>
						<tr class="fs-14">
							<td colspan="6">
								<span><input type="button" class="btnnobackground" name="button" id="button" value="Click here to see Realisation(S)..."    onClick="sub_realisation('realisation')"/></span>
								<form  method="post" action="/sinhaco/query/qry-bill-details-bill-no-realisation" id="realisation" id="realisation" target="">
								<input type="hidden" class="form-control" name="displayid" id="displayid"/>
								<input type="hidden" class="form-control" name="menuid" id="menuid"/>
								<input type="hidden" class="form-control" name="queryId" id="queryId"/>
								<input type="hidden" class="form-control" name="branchcode" id="branchcode"/>
								<input type="hidden" class="form-control" name="finYear" id="finYear"/>
								<input type="hidden" class="form-control" name="billNo" id="billNo"/>
								</form>
							</td>
						</tr>
						<tr class="fs-14">
							<td colspan="6">&nbsp;</td>
						</tr>
						<tr class="fs-14">
							<td colspan="6" class="bgblue">
								<span>Double Click on Matter Code to see Bill details by matter</span>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-md-9 d-inline-block mt-1">
					<button type="button" class="btn btn-primary cstmBtn mt-2 ms-2">Reset</button>				
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Exit</button>
				</div>
			</div>
			
		</div>
		
      </div>
    </section>

  </main><!-- End #main -->
<?= $this->endSection() ?>