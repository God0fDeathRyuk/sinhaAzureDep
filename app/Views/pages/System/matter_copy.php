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
<div class="pagetitle w-100 float-start border-bottom pb-1">
  <h1 class="col-md-8 float-start">Matter Coping</h1>
</div><!-- End Page Title -->
<section class="section dashboard">
<form method="post" action="" name="matterCopy" id="matterCopy" >
      <div class="row">
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				
				<div class="col-md-3 float-start px-2 position-relative mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter Code <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control"  name="matter_code"  id="matterCode" onChange="fetchData(this, 'matter_code', [ 'matterCode','clientName','matterDesc1','matterDesc2'], ['matter_code','client_name','matter_desc1','matter_desc2'], 'matter_code')"  onBlur="mymattercheck(this.value)" required/>
					<i class="fa-solid fa-binoculars icn-vw" id="clientBinocular" onclick="showData('matter_code', '<?= '4211' ?>', 'matterCode', [ 'matterCode','clientName','matterDesc1','matterDesc2'], ['matter_code','client_name','matter_desc1','matter_desc2'],'matter_code')"  data-toggle="modal" data-target="#lookup" style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Case No <strong class="text-danger">*</strong></label>
					<input type="text" name="matter_desc1" id="matterDesc1" class="form-control" placeholder="Case No" required />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter Description <strong class="text-danger">*</strong></label>
					<input type="text" name="matter_desc2" id="matterDesc2" class="form-control" placeholder="Matter Description" required />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Client Name <strong class="text-danger">*</strong></label>
					<input type="text" name="client_name" id="clientName" class="form-control" placeholder="Client Name" required />
					<input type="hidden" name="client_code" id="client_code" required />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Initial Name <strong class="text-danger">*</strong></label>
					<input type="text" name="initial_name" id="initial_name" class="form-control" placeholder="Initial Name" required />
					<input type="hidden" name="initial_code" id="initial_code" required />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Court Name <strong class="text-danger">*</strong></label>
					<input type="text" name="court_name" id="court_name" class="form-control" placeholder="Court Name" required />
					<input type="hidden" name="court_code" id="court_code" required />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Judge Name <strong class="text-danger">*</strong></label>
					<input type="text" name="judge_name" id="judge_name" class="form-control" placeholder="Judge Name" required />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Reference No <strong class="text-danger">*</strong></label>
					<input type="text" name="reference_desc" id="reference_desc" class="form-control" placeholder="Reference No" required />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Present Status <strong class="text-danger">*</strong></label>
					<input type="text" name="status_desc" id="status_desc" class="form-control" placeholder="Present Status" required />
					<input type="hidden" name="status_code" id="status_code" required />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-8 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Subject <strong class="text-danger">*</strong></label>
					<textarea rows="2" name="subject_desc"  id="subject_desc"  class="form-control" placeholder="Subject" required ></textarea>
				</div>
				
				<div class="frms-sec-insde d-block float-start col-md-12 px-2 mt-10">
				<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/> 
                <input type="hidden" name="finsub" id="finsub" value="fsub">
				<input type="hidden" name="option" id="option" value="Edit">
				<input id="save_button" class="btn btn-primary cstmBtn mt-2" type="button" name="button" value="Confirm" onClick="return checkMatterCopydata()">
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Exit</button>
				</div>
			</div>
			
		  </div>
      </div>
</form>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>