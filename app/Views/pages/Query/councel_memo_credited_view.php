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
      <h1>Counsel Memo (Credit)</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12">
			<div class="frms-sec d-inline-block w-100 bg-white p-3 pt-0 position-relative">
				<table class="table table-bordered tblePdngsml mt-3">
					<tr class="fs-14">
						<td>Branch</td>
						<td>Kolkata</td>
					</tr>
					<tr class="fs-14">
						<td>Counsel</td>
						<td><?= $counsel_name ?></td>
					</tr>
					<tr class="fs-14">
						<td>Period</td>
						<td>
							<span class="d-block float-start me-1 mt-2"><?= $defaultdate ?></span>
							<span class="d-block float-start mx-1 mt-2">-</span>
							<span class="d-block float-start me-2 mt-2"><?= $end_date_ymd ?></span>
							<button type="button" class="btn btn-primary cstmBtn btn-cncl ms-3">Back</button>
						</td>
					</tr>
				</table>
				<table class="table table-bordered tblePdngsml mt-3">
					<tr class="fs-14">
						<th class="">Memo No</th>
						<th>Memo Dt</th>
						<th>Acty Dt</th>
						<th>Matter</th>
						<th>Client</th>
						<th>Narration</th>
						<th class="text-end w-20">Credit Amt</th>
					</tr>
                    <?php $tcramt = 0; foreach ($data as $key => $value) {?>
					<tr class="fs-14">
						<td class="text-center"><span><?= $value['memo_no'] ?></span></td>
						<td class="text-center"><span><?= $value['memo_date'] ?></span></td>
						<td class="text-center"><span><?php if($value['brief_date'] != '' && $value['brief_date'] != '0000-00-00') { echo date_conv($value['brief_date']); } ?></span></td>
						<td class="text-center"><span><?= $value['matter_code'] ?></span></td>
						<td class="text-center"><span><?= $value['client_code'] ?></span></td>
						<td class="text-center"><span><?= $value['narration'] ?></span></td>
						<td class="text-end"><span><?= $value['credited_amount'] ?></span></td>
					</tr>
                    <?php $tcramt = $tcramt + $value['credited_amount'] ; ; } ?>
					<tr class="fs-14">
						<td class="text-end bgblue" colspan="6">Total</td>
						<td class="text-end bgblue"><?php if($tcramt == 0.00) { echo '&nbsp;'; } else { echo number_format($tcramt,2,'.',''); } ?></td>
					</tr>					
				</table>
			</div>
			
		  </div>
      </div>
    </section>

  </main><!-- End #main -->
<?= $this->endSection() ?>