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
  <h1 class="col-md-8 float-start">Excel Files</h1>
</div><!-- End Page Title -->
<section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">	
				<ol>
					<?php foreach ($data as $key => $value) {?>
					<li><a href="<?= base_url('download_excel').'?id='.$value['id']; ?>" title="Click To Download"><?php echo $value['file_name']; ?></a></li>
					<?php } ?>
				</ol>
			</div>
			
		  </div>
      </div>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>