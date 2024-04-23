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
      <h1>Selection of Query [Proceed]</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
      <div class="frms-sec d-inline-block w-100 bg-white p-3 pt-2">
		  <div class="col-md-6 float-start">
			  <div class="search">
				<input class="form-control w-48 d-block float-start me-1" type="search"/>
				<span class="d-block float-start mt-1">To</span>
				<input class="form-control w-48 d-block float-start ms-1" type="search"/>				
			  </div>
		  </div>
		  <div class="col-md-6 float-start">
			<button class="btn btn-primary cstmBtn ms-2 d-block float-start">Go</button>
		  </div>
		  <div class="col-md-12 mt-0">			
			<div class="tbl-sec scrlTbl d-inline-block w-100 bg-white mt-2">
			
				<table class="table border-0">
					<tr class="fs-14">
						<th class="border wd100">
							<span>Srl#</span>
						</th>
						<th class="border w-250">
							<span>Date</span>
						</th>
						<th class="border w-250">
							<span>Matter</span>
						</th>
						<th class="border w-250">
							<span>Client</span>
						</th>
						<th class="border w-250">
							<span>Judge</span>
						</th>
						<th class="border w-150">
							<span>App. For</span>
						</th>
						<th class="border w-150">
							<span>Fix For (Prev)</span>
						</th>
						<th class="border w-150">
							<span>Prev Date</span>
						</th>
						<th class="border w-150">
							<span>Nxt Date</span>
						</th>
						<th class="border w-150">
							<span>Fix For (Nxt)</span>
						</th>
					</tr>
                    <?php foreach ($res as $key => $value) {?>
					<tr class="fs-14 border-0">
						<td class="border">
							<span><input type="text" class="btnnobackground" name="serial_no" id="serial_no" value="<?= $value['serial_no'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"></span>
						</td>
						<td class="border">
							<span><input type="text" class="btnnobackground" name="activity_date" id="activity_date" value="<?= $value['activity_date'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"></span>
						</td>
						<td class="border">
							<span><input type="text" class="btnnobackground" name="matter_code" id="matter_code" value="<?= $value['matter_code'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"></span>
						</td>
						<td class="border">
							<span><input type="text" class="btnnobackground" name="client_code" id="client_code" value="<?= $value['client_code'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"></span>
						</td>
						<td class="border">
							<span><input type="text" class="btnnobackground" name="judge_name" id="judge_name" value="<?= $value['judge_name'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"></span>
						</td>
						<td class="border">
							<span><input type="text" class="btnnobackground" name="appear_for" id="appear_for" value="<?= $value['appear_for'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"></span>
						</td>
						<td class="border">
							<span><input type="text" class="btnnobackground" name="prev_fixed_for" id="prev_fixed_for" value="<?= $value['prev_fixed_for'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"> </span>
						</td>
						<td class="border">
							<span><input type="text" class="btnnobackground" name="prev_date" id="prev_date" value="<?= $value['prev_date'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"></span>
						</td>
						<td class="border">
							<span><input type="text" class="btnnobackground" name="next_date" id="next_date" value="<?= $value['next_date'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"></span>
						</td>
                        <td class="border">
							<span><input type="text" class="btnnobackground" name="next_fixed_for" id="next_fixed_for" value="<?= $value['next_fixed_for'] ?>" onClick="chkCaseDet(<?php echo $value['serial_no'];?>)"> </span>
						</td>
					</tr>
                    <?php } ?>
				</table>
			</div>
			<div class="col-md-3 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Billable Option</label>
				<a href="javascript:void(0);" class="btn btn-primary cstmBtn w-100 text-start py-1" name="status_desc" id="status_desc">Billable</a>
			</div>
			<div class="col-md-3 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Serial No</label>
				<input type="text" class="form-control w-100" name="serial_no_BP" id="serial_no_BP">
			</div>
			<div class="col-md-3 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Date</label>
				<input type="text" class="form-control w-100" name="activity_date_BP" id="activity_date_BP" >
			</div>
			<div class="col-md-3 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Matter</label>
				<input type="text" class="form-control w-100" name="matter_code_BP" id="matter_code_BP">
			</div>
			<div class="col-md-3 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Client</label>
				<input type="text" class="form-control w-100" name="client_code_BP"  id="client_code_BP" >
			</div>
			<div class="col-md-9 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Judge</label>
				<input type="text" class="form-control w-100" name="judge_name_BP" id="judge_name_BP">
			</div>
			<div class="col-md-6 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">App For</label>
				<input type="text" class="form-control w-100" name="appear_for_BP"  id="appear_for_BP" >
			</div>
			<div class="col-md-6 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Prev Date</label>
				<input type="text" class="form-control w48 float-start" name="prev_date_BP" id="prev_date_BP">
				<input type="text" class="form-control w48 ms-2  float-start"  name="prev_fixed_for_BP" id="prev_fixed_for_BP">
			</div>
			<div class="col-md-6 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Next Date</label>
				<input type="text" class="form-control w-33 float-start" name="next_date_BP" id="next_date_BP">
				<input type="text" class="form-control w-65 ms-2 float-start" name="next_fixed_for_BPss" id="next_fixed_for_BPss">
			</div>
			<div class="col-md-3 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Bill Srl</label>
				<input type="text" class="form-control w-100" name="bill_srl_BP" id="bill_srl_BP">
			</div>
			<div class="col-md-3 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Bill No</label>
				<input type="text" class="form-control w-100" name="ref_bill_srl_no_BP" id="ref_bill_srl_no_BP">
			</div>
			<div class="col-md-3 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Date</label>
				<input type="text" class="form-control w-100" name="letter_date_BP" id="letter_date_BP">
			</div>
			<div class="col-md-6 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">Letter No</label>
				<input type="text" class="form-control w-100" name="letter_no_BP" id="letter_no_BP" >
			</div>
			<div class="col-md-12 float-start px-2 mb-1">
				<label class="d-inline-block w-100 mb-1 lbl-mn">&nbsp;</label>
				<textarea rows="3" class="form-control w-100" name="header_desc_BP" id="header_desc_BP"></textarea>
			</div>
		  </div>
		  <div class="d-inline-block w-100">
				<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</button>
		    </div>
      </div>
      </div>
    </section>

  </main><!-- End #main -->

<?= $this->endSection() ?>