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
<div class="pagetitle">
      <h1>List of Uploaded Files</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
	  <form action="" method="post">
                <div class="row">
                    <div class="col-md-3">
                        <label class="d-block w-100">From Date</label>
                        <input type="text" name="frmDt" id="frmDt" class="form-control txtFrom" onblur="make_date(this)"/>
                    </div>
                    <div class="col-md-3">
                        <label class="d-block w-100">To Date</label>
                        <input type="text" name="toDt" id="toDt" class="form-control txtTo" onblur="make_date(this)"/>
                    </div>
                    <div class="srchtop col-md-4 float-start mt-20">
                            <div class="input-group">
								<div class="form-outline">
                                <input type="hidden" name="option" id="option" value="search">
                                <input type="search" name="query" id="form1" class="form-control" value="" placeholder="search here.." />
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            </div>
                        
                    </div>
                </div>
                    </form>
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="d-inline-block w-100 mt-2">
					
					<table class="table table-bordered tblePdngsml">
						<tbody>
							<tr class="fs-14">
								<th>Description</th>
								<th>File Name</th>
								<th>Type</th>
								<th>Uploaded By</th>
								<th>Upload On</th>
								<th>Download</th>
							</tr>
                            <?php foreach ($data as $key => $value) {?>
							<tr>							
								<td class="w-260">
									<span><?php echo $value['description']; ?></span>
								</td>
								<td class="">
									<span><?php echo $value['file_name_original']; ?></span>
								</td>
								<td class=""><span><?php echo $value['file_type']; ?></span></td>
								<td class="">
									<span><?php echo $value['user_name']; ?></span>
								</td>
								<td class="">
									<span><?php echo date('d-m-Y',strtotime($value['uploaded_on'])); ?></span>
								</td>
								<td>
									<span><a href="<?= base_url('download_notice').'?id='.$value['serial_no']; ?>" title="Click To Download">Download</a> </span>
								</td>
							</tr>
                            <?php } ?>
						</tbody>
					</table>
				</div>
				<!-- <div class="frms-sec-insde d-block float-start col-md-12">
					<button type="button" class="btn btn-primary cstmBtn btn-cncl">Close</button>
				</div> -->
			</div>
			
		  </div>
      </div>
    </section>

</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>