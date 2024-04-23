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
  <h1 class="col-md-8 float-start">Matter Status Change</h1>
</div><!-- End Page Title -->
<section class="section dashboard">
    <form  action="" method="post" name="matterStatusChange" id="matterStatusChange" >
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
					<input type="text" class="form-control" name="matter_desc1" id="matterDesc1"  placeholder="Case No" required readonly />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter Description <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control datepicker" name="matter_desc2" id="matterDesc2" placeholder="Matter Description" required readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Client Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="client_name" id="clientName" placeholder="Client Name" required readonly/>
                    <input type="hidden" name="client_code" id="client_code" >
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Initial Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="initial_name" id="initial_name" placeholder="Initial Name" required readonly/>
                    <input type="hidden" name="initial_code" id="initial_code" >
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Court Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control"  name="court_name"  id="court_name" placeholder="Court Name" required readonly/>
                    <input type="hidden" name="court_code" id="court_code">
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Judge Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="judge_name" id="judge_name" placeholder="Judge Name" required readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Reference No <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="reference_desc" id="reference_desc" placeholder="Reference No" required readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Present Status <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="status_desc" id="status_desc" placeholder="Present Status" required readonly/>
                    <input  type="hidden" name="status_code" id="status_code">
                    <input  type="hidden"  name="old_status_code" id="old_status_code">
				</div>
				<div class="frms-sec-insde d-block float-start col-md-8 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Subject <strong class="text-danger">*</strong></label>
					<textarea rows="2" class="form-control" name="subject_desc" id="subject_desc" placeholder="Subject" required readonly ></textarea>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">New Status <strong class="text-danger">*</strong></label>
					<select class="form-select" name="new_status_code" id="new_status_code" required  >
						<?php foreach ($data as $key => $value) {?>
							<option value="<?= isset($value['code_code'])?$value['code_code']:'' ?>" ><?= isset($value['code_code'])?$value['code_desc']:'' ?></option>
							<?php } ?>
					</select>
				</div>
                <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/> 
                <input type="hidden" name="finsub" id="finsub" value="fsub">
				<input type="hidden" name="option" id="option" value="Edit">
				<div class="frms-sec-insde d-block float-start col-md-12 px-2 mt-10">
                <input id="save_button" class="btn btn-primary cstmBtn mt-2" type="button" name="button" value="Confirm" onClick="return checkMatterStatusChangedata()">
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