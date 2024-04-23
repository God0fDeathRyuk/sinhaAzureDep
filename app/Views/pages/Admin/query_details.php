<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<main id="main" class="main">
<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>
<div class="pagetitle col-md-5 float-start">
  <h1> Query Details </h1>
</div><!-- End Page Title -->

<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="d-inline-block w-100">
					<label class="d-block w-50 float-start mb-2 lbl-mn"><a href="javascript:void(0);"><u>List of Query(S)</u></a></label>
					<label class="d-block w-50 float-start mb-2 lbl-mn text-end"> <a href="/sinhaco/admin/query-details-add/add?display_id=&menu_id=9929" class="btn btn-primary cstmBtn mt-0"> Add </a></label>
				</div>
				
				<div class="mntblsec">
					<table class="table table-bordered tblhdClr">
						<tr>
							<th class="fntSml"><span></span></th>
							<th class="fntSml"><span></span></th>
							<th class="fntSml"><span>Description</span></th>
							<th class="fntSml"><span>Activity ID</span></th>
						</tr>
                        <?php 
                            foreach ($data as $key => $user) { ?>
						<tr>
							<td class="p-2"><span><a href="/sinhaco/admin/query-details-add/edit?query_id=<?php echo $user['query_id']?>">&nbsp;<b>Edit</b></a></span></td>
							<td class="p-2"><span><a class="detail_item" href="/sinhaco/admin/query-details-add/view?query_id=<?php echo $user['query_id']?>">&nbsp;<b>View</b></a></span></td>
							<td class="p-2"><span><?php echo $user['query_name'] ?></span></td>
							<td class="p-2"><span><?php echo $user['query_id'] ?></span></td>
						</tr>
                        <?php } ?>
					</table>
				</div>
			</div>
			<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</button>
</main>
<?= $this->endSection() ?>