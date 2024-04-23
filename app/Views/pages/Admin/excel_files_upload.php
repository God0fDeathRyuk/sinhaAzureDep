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
  <h1 class="col-md-8 float-start">Excel File Upload</h1>
</div><!-- End Page Title -->
<section class="section dashboard">
<form method="post" action="" name="fileSubmit" id="fileSubmit"  enctype="multipart/form-data">
	
      <div class="row">
		  <div class="col-md-12 mt-1">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">	
				<div class="d-inline-block w-100">					
					<div class="col-md-6	 float-start px-2 mb-1">
						<label class="d-inline-block w-100 mb-2 lbl-mn">File Name</label>
						<input type="text" name="file_name" id="file_name" class="form-control mb-1" placeholder="Enter File Name To Display" required />
					</div>
				</div>
				<div class="col-md-6 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Choose File</label>
					<input type="file" class="form-control" name="userfiles" id="userfiles">
				</div>
				<div class="d-inline-block w-100">
				<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/> 
                <input type="hidden" name="finsub" id="finsub" value="fsub">
				<input type="hidden" name="option" id="option" value="merge">
				<input id="save_button" class="btn btn-primary cstmBtn mt-2" type="button" name="button" value="Submit" onClick="return checkFileUpload()">
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