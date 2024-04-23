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
      <h1>Case Details(Clint/Matterwise)</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-8 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Client</label>
					<input type="text" class="form-control w-65 float-start" value="<?= isset($client_name)?$client_name:'' ?>" readonly>
					<input type="text" class="form-control w-33 ms-2 float-start" value="<?= isset($client_code)?$client_code:'' ?>" readonly>
				</div>
				<div class="col-md-4 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter</label>
					<input type="text" class="form-control w-100" name="matter_searchval" id="matter_searchval">
				</div>
				
				<div class="col-md-12 float-start mt-2 px-2">
					<button type="button" class="btn btn-primary cstmBtn mt-2">Search</button>			
					<button type="button" class="btn btn-primary cstmBtn mt-2">Reset</button>			
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-1">Back</button>
				</div>
				<div class="d-block float-start w-100 px-2 mt-2">
					<table class="table table-bordered tblePdngsml mt-3">
						<tbody>
							<tr class="fs-14">						
								<th>&nbsp;</th>
								<th>Code</th>
								<th>Desc</th>
							</tr>
							<?php foreach ($matter_sql as $key => $value) {?>
							<tr class="fs-14">
								<td><input type="radio" name="recsel_ind" id="recsel_ind" value="Y" onClick="myMatterSelect('<?php echo $key?>')"></td>
								<td><?php echo strtoupper($value['matter_code']) ?></td>
								<td class="text-uppercase"><?php echo strtoupper($value['matter_desc']) ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="col-md-4 float-start px-2 mb-1 d-none">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Back To</label>
					<select class="form-select w-100">
						<option>---- select ----</option>
					</select>
				</div>
			</div>
			
		</div>
		
      </div>
    </section>
	<?php foreach ($matter_sql as $key => $value) {?>
        <form method="post" action="/sinhaco/query/case-details-matter-view" id="matterSubmit<?php echo $key; ?>">
            <input type="hidden" name="matter_code" id="matter_code" value="<?php echo $value['matter_code']; ?>" >
            <input type="hidden" name="matter_desc" id="matter_desc" value="<?php echo $value['matter_desc']; ?>" >
			<input type="hidden" name="client_code" id="client_code" value="<?php echo $client_code; ?>" >
			<input type="hidden" name="client_name" id="client_name" value="<?php echo $client_name; ?>" >
        </form>
        <?php } ?>
  </main><!-- End #main -->

<?= $this->endSection() ?>