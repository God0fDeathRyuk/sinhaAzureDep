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
						<td><?= $branch_name ?></td>
					</tr>
					<tr class="fs-14">
						<td>Counsel</td>
						<td><?= $counsel_name ?></td>
					</tr>
					<tr class="fs-14">
						<td>As On</td>
						<td>
							<span class="d-block float-start me-1 mt-2"><?= $end_date ?></span>
							<button type="button" class="btn btn-primary cstmBtn btn-cncl ms-3">Back</button>
						</td>
					</tr>
				</table>
				<table class="table table-bordered tblePdngsml mt-3">
					<tr class="fs-14">
						<th class="">&nbsp;</th>
						<th>Counsel Name</th>
						<th>O/s Amt</th>
					</tr>
                    <?php  $tosamt = 0;  foreach ($data as $key => $value) {?>
					<tr class="fs-14">
                        <td class="text-center"><input type="radio" name="xx" id="xx" onClick="return myselepage('qry_councel_memo_frm',<?= $key ?>)"/></td>
						<td class="text-center"><span><?= $value['counsel_name']?></span></td>
						<td class="text-center"><span><?= $value['os_amount']?></span></td>
					</tr>
                    <?php $tosamt = $tosamt + $value['os_amount'] ;} ?>
					<tr class="fs-14">
						<td class="text-end bgblue" colspan="2">Total</td>
						<td class="text-end bgblue"><?= $tosamt; ?></td>
					</tr>					
				</table>  
                
			</div>
			
		  </div>
      </div>
    </section>
    <?php if ($options!=''){foreach ($data as $key => $value) {?>
		<form method="post" action="/sinhaco/query/councel-memo-view" name="qry_councel_memo_frm<?= $key ?>" id="qry_councel_memo_frm<?= $key ?>" >
			<input type="hidden" name="counsel_code" id="counsel_code" value="<?= $value['counsel_code'] ?>">
			<input type="hidden" name="counsel_name" id="counsel_name" value="<?= $value['counsel_name'] ?>">
		</form>
	<?php }}?>
</main><!-- End #main -->

<?= $this->endSection() ?>