<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<?php if (!isset($reports)) { ?> 
<main id="main" class="main">

<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>

<div class="pagetitle col-md-12 float-start border-bottom pb-1">
  <h1>Bill Cancellation (Final)</h1>
</div>

<form action="" method="post" id="billEditing">
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
	<div class="row">
		<div class="inpt-grp col-md-4 pe-0">
			<label class="d-block w-100 mb-2">Branch</label>
			<select class="form-select cstm-inpt" name="branch_code">
			<?php foreach($data['branches'] as $branch) { ?>
			<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
			<?php } ?>
			</select>
		</div>
		<div class="inpt-grp col-md-4 pe-0 position-relative">
			<label class="d-block w-100 mb-2">Bill Yr/No</label>
            <input type="text" class="form-control cstm-inpt" name="bill_year" id="billYear" value='' required /> 
			<input type="text" class="form-control cstm-inpt" id="SerialNo" name="bill_no" value='' onfocusout="myFinalBillSerial(this)" required />
			<!-- <i class="fa-solid fa-eye inpt-vw" onclick="showData('serial_no', '<?= $displayId['billsrl_help_id'] ?>', 'SerialNo', ['matterCode', 'matterDesc', 'clientCode', 'clientName'], ['matter_code', 'matter_desc', 'client_code', 'client_name'], 'bill_serial_code')" data-toggle="modal" data-target="#lookup"></i> -->
            <input type="hidden" class="form-control cstm-inpt" name="ref_bill_serial_no" value='' />
            <input type="hidden" class="form-control cstm-inpt" name="serial_no" value='' />
            <input type="hidden" class="form-control cstm-inpt" name="status_code" value='' />
		</div>
		<div class="inpt-grp col-md-4 pe-0">
			<label class="d-block w-100 mb-2">Matter Code</label>
			<input type="text" class="form-control cstm-inpt" id="matterCode" readonly disabled  />
		</div>
		<div class="inpt-grp col-md-12 pe-0 mt-3">
			<label class="d-block w-100 mb-2">Matter Description</label>
			<textarea name="" rows="1" class="form-control cstm-inpt" id="matterDesc" readonly disabled ></textarea>
		</div>
		<div class="inpt-grp col-md-4 pe-0">
			<label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
			<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="clientCode" size="05" maxlength="06" name="client_code" readonly disabled/>
		</div>
		<div class="inpt-grp col-md-8 pe-0">
			<label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
			<input type="text" class="form-control" id="clientName" oninput="this.value = this.value.toUpperCase()"  name="client_name" readonly disabled/>
		</div>
	</div>
            <button type="submit" class="btn btn-primary cstmBtn mt-3">Proced</button>
			<button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
</form>
</main>
<?php } else { ?>


<?php } ?>

<!-- End #main -->
<?= $this->endSection() ?>