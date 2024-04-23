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
						<td>Period</td>
						<td>
							<input type="text" class="form-control w-33 float-start datepicker" name="period_start_date" id="period_start_date" value="<?= $start_date ?>"/>
							<span class="d-block float-start mx-1 mt-1">-</span>
							<input type="text" class="form-control w-33 float-start datepicker" name="period_end_date" id="period_end_date" value="<?= $end_date ?>"/>
							<button type="button" class="btn btn-primary cstmBtn btn-cncl ms-3">Back</button>
						</td>
					</tr>
				</table>
				<table class="table table-bordered tblePdngsml mt-3">
					<tr class="fs-14">
						<th class="w-5 text-center">&nbsp;</th>
						<th>Counsel Name</th>
						<th class="text-end w-20">Credited Amt</th>
					</tr>
                    <?php $tcramt = 0;  foreach ($data as $key => $value) {?>
					<tr class="fs-14">
						<td class="text-center"><input type="radio" name="xx" id="xx" onClick="return myselepage('qry_councel_memo_frm',<?= $key ?>)" /></td>
						<td><?= $value['counsel_name'] ?></td>
						<td class="text-end"><?= $value['credited_amount'] ?></td>
					</tr>
                    <?php $tcramt = $tcramt + $value['credited_amount'] ;} ?>
					<tr class="fs-14">
						<td class="bgblue">&nbsp;</td>
						<td class="text-end bgblue">Total</td>
						<td class="text-end bgblue"><?php if($tcramt == 0.00) { echo '&nbsp;'; } else { echo number_format($tcramt,2,'.',''); } ?></td>
					</tr>					
				</table>
				<div class="d-block w-100 d-none    ">
					<span class="d-block float-start mt-1 me-2">Back To</span>
					<select type="text" class="form-select w-auto float-start">
						<option value="">------ select ------</option>
						<option value="">Option A</option>
						<option value="">Option B</option>
					</select>	
				</div>
			</div>
			
		  </div>
      </div>
    </section>
    <?php if ($options!=''){foreach ($data as $key => $value) {?>
		<form method="post" action="/sinhaco/query/councel-memo-credited-view" name="qry_councel_memo_frm<?= $key ?>" id="qry_councel_memo_frm<?= $key ?>" target="">
			<input type="hidden" name="counsel_code" id="counsel_code" value="<?= $value['counsel_code'] ?>">
			<input type="hidden" name="counsel_name" id="counsel_name" value="<?= $value['counsel_name'] ?>">
		</form>
	<?php }}?>
</main><!-- End #main -->

<?= $this->endSection() ?>