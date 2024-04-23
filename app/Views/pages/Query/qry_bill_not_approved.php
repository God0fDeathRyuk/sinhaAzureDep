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
      <h1>Bill Generated But Not Yet Approved</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
      <form action="/sinhaco/query/bill-not-approved-rp" method="post" id="qryBillNotApproved" target="">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">As On <strong class="text-danger">*</strong></label>
					<input class="form-control w-100" placeholder="DD-MM-YYYY" type="text" name="ason_date"  id="ason_date" value="<?php echo date('d-m-Y');?>" readonly>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
					<select class="form-select" name="branch_code" id="branch_code" required>
					<?php foreach ($branch as $key => $value) {?>
						<option value="<?= $value['branch_code'] ?>" ><?= $value['branch_name'] ?></option>
					<?php } ?>
					</select>
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Court Code</label>
					<div class="position-relative float-start w-33">
						<input type="text" class="form-control float-start"  name="court_code" id="courtCode"/>
						<i class="fa-solid fa-binoculars icn-vw icn-vw2" title="View" id="matterBinocular" onclick="showData('code_code', '<?= '4221' ?>', 'courtCode', [ 'courtCode','courtName'], ['code_code','code_desc'],'code_code')"  data-toggle="modal" data-target="#lookup"></i>
					</div>
					<input type="text" class="form-control float-start w-65 ms-2" name="court_name" id="courtName"/>				
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Client Code</label>
					<div class="position-relative float-start w-33">
						<input type="text" class="form-control float-start" name="client_code"  id="clientCode" />
						<i class="fa-solid fa-binoculars icn-vw icn-vw2" title="View" id="matterBinocular" onclick="showData('client_code', '<?= '4072' ?>', 'clientCode', [ 'clientName','clientCode'], ['client_name','client_code'],'client_code')"  data-toggle="modal" data-target="#lookup"></i>
					</div>
					<input type="text" class="form-control float-start w-65 ms-2" name="client_name"  id="clientName" />				
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter Code</label>
					<div class="position-relative float-start w-33">
						<input type="text" class="form-control float-start" name="matter_code" id="matterCode"/>
						<i class="fa-solid fa-binoculars icn-vw icn-vw2" title="View" id="matterBinocular" onclick="showData('matter_code', '<?= '4220' ?>', 'matterCode', [ 'matterCode','matterDesc'], ['matter_code','matter_desc'],'matter_code')"  data-toggle="modal" data-target="#lookup"></i>
					</div>
					<input type="text" class="form-control float-start w-65 ms-2" name="matter_desc" id="matterDesc" />				
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                    <select class="form-select" name="output_type" id="output_type" tabindex="8">
                        <option value="Report">Report</option>
                        <option value="Excel" >Excel</option>
                    </select>
				</div>
				<input type="button" name="button" id="button" value="Proceed" class="btn btn-primary cstmBtn mt-4 ms-2"  onClick="getBillDetailsNotApp('qryBillNotApproved')">
				<button type="button" class="btn btn-primary cstmBtn mt-4 ms-2">Refresh</button>				
				<button type="button" class="btn btn-primary cstmBtn mt-4 ms-2 btn-cncl">Exit</button>
			</div>
			
		</div>
      </form>		
      </div>
    </section>

  </main><!-- End #main -->
<?= $this->endSection() ?>