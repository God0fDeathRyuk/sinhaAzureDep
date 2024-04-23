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
                <form method="post" action="" id="searchClient">
                    <div class="col-md-8 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-1 lbl-mn">Client</label>
                        <input type="text" class="form-control w-65 float-start" name="client_searchval" id="client_searchval"  value="<?php isset($client_name)?$client_name:'' ?>" tabindex="1" onBlur="myClientName()" >
                        
                    </div>
                    
                    <div class="col-md-12 float-start mt-2 px-2">
                        <input type="button" name="button" id="button" value="Search" class="btn btn-primary cstmBtn btncls mt-2"  onClick="mySearch('searchClient')">		
                        <button type="button" class="btn btn-primary cstmBtn mt-2">Reset</button>			
                        <button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-1">Back</button>
                    </div>
                </form>
				<div class="d-block float-start w-100 px-2 mt-2">
					<table class="table table-bordered tblePdngsml mt-3">
						<tbody>
							<tr class="fs-14">						
								<th>&nbsp;</th>
								<th>Code</th>
								<th>Desc</th>
							</tr>
                            <?php foreach ($client_qry as $key => $value) {?>
							<tr class="fs-14">
								<td><input type="radio" name="recsel_ind" id="recsel_ind" value="Y" onClick="myRecSelect('<?php echo $key ?>')"></td>
								<td><?php echo strtoupper($value['client_code']) ?></td>
								<td class="text-uppercase"><?php echo strtoupper($value['client_name']) ?></td>
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
    <?php foreach ($client_qry as $key => $value) {?>
        <form method="post" action="/sinhaco/query/case-details-client-matter-view" id="clientSubmit<?php echo $key; ?>">
            <input type="hidden" name="client_code" id="client_code" value="<?php echo $value['client_code']; ?>" >
            <input type="hidden" name="client_name" id="client_name" value="<?php echo $value['client_name']; ?>" >
        </form>
        <?php } ?>
  </main><!-- End #main -->

<?= $this->endSection() ?>