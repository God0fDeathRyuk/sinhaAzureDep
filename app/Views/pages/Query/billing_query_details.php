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
<div class="pagetitle <?php if($options!="Proceed"){echo 'd-block';}else{echo 'd-none';}?>">
      <h1>Query Details</h1>
    </div><!-- End Page Title -->
    <div class="pagetitle <?php if($options!="Proceed"){echo 'd-none';}else{echo 'd-block';}?>">
      <h1>Selection of Query  [Proceed]</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
    <form method="post" action="" name="query_frm" id="query_frm" class="<?php if($options!="Proceed"){echo 'd-block';}else{echo 'd-none';}?>">
      <div class="row">
		  <div class="col-md-12">
			<div class="frms-sec d-inline-block w-100 bg-white p-3 pt-0 position-relative">
				<p class="d-inline-block w-100 bdge mb-2">Module : <span class="text-uppercase">Billing</span></p>
				<div class="frms-sec-insde d-block float-start col-md-4 px-0 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Query Name</label>
					<select class="form-select" name="query_id" id="query_id">
						<option value="">-- Select --</option>
                        <?php foreach ($data as $key => $value) {?>
						<option value="<?= ($option!="Proceed")?$value['query_id']:''?>"><?= ($option!="Proceed")?$value['query_name']:''?></option><?php } ?>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-12 px-0 mt-10">
                    <input type="hidden" name="options"id="options" value="send">
                    <input type="button" name="button" id="button" value="Proceed" class="btn btn-primary cstmBtn btncls mt-2"  onClick="return proc()">
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2">Back</button>
				</div>
			</div>
			
		  </div>
      </div>
    </form>
    </section>
    <section class="section dashboard <?php if($options!="Proceed"){echo 'd-none';}else{echo 'd-block';}?>">
      <div class="row">
      <div class="frms-sec d-inline-block w-100 bg-white p-3 pt-2">
		  <div class="col-md-6">
			  <div class="search">
				<label class="d-block w-100 mb-2">Search</label>
				<input class="form-control w-65 d-block float-start" type="search"/>
				<button class="btn btn-primary cstmBtn ms-2 d-block float-start">Search</button>
			  </div>
		  </div>
		  <div class="col-md-12 mt-0">
			<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</button>
		  </div>
      </div>
      </div>
    </section>

</main><!-- End #main -->

<?= $this->endSection() ?>