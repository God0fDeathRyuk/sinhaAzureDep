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
  <h1 class="col-md-8 float-start">Change Password </h1>
</div><!-- End Page Title -->
<section class="section dashboard">
      <div class="row">
        <form method="post" action="" name="F1" id="F1" >
		  <div class="col-md-12 mt-1">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">				
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">User Name</label>
					<input type="text" name="employee_name" id="employee_name" value="<?=$data['user_name']?>"class="form-control"/>
                    <input type="hidden" name="user_id" id="user_id" value="<?=$data['user_id']?>"class="form-control"/>
				</div>
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Old Password <strong class="text-danger">*</strong></label>
					<input type="password"name="employee_password"  id="employee_password"  value="" class="form-control" onblur="check_password(this)" required/>
				</div>
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">New Password <strong class="text-danger">*</strong></label>
					<input type="password" class="form-control"  name="employee_new_password_1" id="employee_new_password_1" required onkeyup="passwordStrength(this.value)" readonly/>
				</div>
				<div class="col-md-3 float-start px-2 mb-3">
					<div  id="passwordDescription" class="d-inline-block w-100 mb-2 lbl-mn">Password Strength</div>
					<div id="passwordStrength" class="mb-3 mt-1 rounded w-auto h-auto strength0">&nbsp;</div>
				</div>
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Confirm Password <strong class="text-danger">*</strong></label>
					<input type="password" class="form-control"  name="employee_new_password_2" id="employee_new_password_2" required readonly/>
				</div>
                <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/> 
                <input type="hidden" name="finsub" id="finsub" value="fsub">
				<div class="col-md-9 float-start mtop fst-italic text-danger">** Use Alphanumeric Upper-lower combination and at least one special character</div>
				<div class="d-inline-block w-100">
					<input type="button" name="button" id="button" value="Confirm" class="btn btn-primary cstmBtn btncls mt-2"  onClick="return checkdata2()">
					<!-- <button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Exit</button> -->
				</div>
			</div>
			
		  </div>
        </from>
      </div>
    </section>

</main><!-- End #main -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>