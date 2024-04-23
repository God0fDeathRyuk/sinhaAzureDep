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
                <form method="post" id="caseDetailsClientMatter">
				<div class="col-md-8 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Client</label>
					<input type="text" class="form-control w-100" value="<?= isset($client_name)?$client_name:'' ?>" readonly>
				</div>
				<div class="col-md-4 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter</label>
					<input type="text" class="form-control w-100" value="<?= isset($matter_desc)?$matter_desc:'' ?>" readonly>
				</div>
				<div class="col-md-5 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
					<input type="text" class="form-control w-45 float-start datepicker" name="start_date" id="start_date">
					<span class="w-2 float-start mx-1">--</span>
					<input type="text" class="form-control w-45 float-start datepicker" name="end_date" id="end_date" value="<?php echo date('d-m-Y')?>" required="">
				</div>
				<div class="col-md-7 float-start mt-20 px-2">
                    <input type="hidden" name="op" id="op" value="search">
                    <input type="button" name="button" id="button" value="Search" class="btn btn-primary cstmBtn btncls mt-2"  onClick="caseDetailsClientMatterSearch('caseDetailsClientMatter')">			
					<button type="button" class="btn btn-primary cstmBtn mt-2">Reset</button>			
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-1">Back</button>
				</div>
                </form>
				<div class="d-block float-start w-100 px-2 mt-2">
					<table class="table table-bordered tblePdngsml mt-3">
						<tbody>
							<tr class="fs-14">						
								<th>&nbsp;</th>
								<th>Srl#</th>
								<th>Date</th>
								<th>Client</th>
								<th>Matter</th>
								<th>Judge</th>
							</tr>
                            <?php 
                                foreach ($case_qry as $key => $value) {?>
							<tr class="fs-14">
								<td><input type="radio" name="recsel_ind" id="recsel_ind" value="Y" onClick="myRecSelectall(<?php echo $value['serial_no']?>)"/></td>
								<td><?php echo $value['serial_no'] ?>&nbsp;</td>
								<td>&nbsp;<?php echo date_conv($value['activity_date'],'-') ?></td>
								<td>&nbsp;<?php echo $value['client_code'] ?></td>
								<td>&nbsp;<?php echo $value['matter_code'] ?></td>
								<td class="text-uppercase">&nbsp;<?php echo $value['judge_name']  ?></td>
							</tr>
                            <?php }?>
						</tbody>
					</table>
				</div>
				
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Serial</label>
					<input type="text" class="form-control w-100" name="serial_no"  id="serial_no" >
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Date</label>
					<input type="text" class="form-control w-100" name="activity_date" id="activity_date">
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter</label>
					<input type="text" class="form-control w-33 float-start" name="matter_code" id="matter_code">
					<input type="text" class="form-control w-65 ms-2 float-start" name="matter_desc"  id="matter_desc">
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Client</label>
					<input type="text" class="form-control w-33 float-start" name="client_code" id="client_code">
					<input type="text" class="form-control w-65 ms-2 float-start" name="client_name" id="client_name">
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Judge</label>
					<input type="text" class="form-control w-100" name="judge_name" id="judge_name">
				</div>
				<div class="col-md-4 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Apply For</label>
					<input type="text" class="form-control w-100" name="appear_for"  id="appear_for" >
				</div>
				<div class="col-md-8 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Previous</label>
					<input type="text" class="form-control w-33 float-start" name="prev_date" id="prev_date" >
					<input type="text" class="form-control w-65 ms-2 float-start" name="prev_fixed_for" id="prev_fixed_for">
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Next</label>
					<input type="text" class="form-control w-33 float-start" name="next_date" id="next_date">
					<input type="text" class="form-control w-65 ms-2 float-start" id="next_fixed_for" name="next_fixed_for">
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Remarks</label>
					<input type="text" class="form-control w-100" name="remarks" id="remarks">
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Draft Bill#</label>
					<input type="text" class="form-control w-100" name="ref_billinfo_serial_no" id="ref_billinfo_serial_no">
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Final Bill#</label>
					<input type="text" class="form-control w48 float-start" name="final_bill_no" id="final_bill_no">
					<input type="text" class="form-control w48 ms-2 float-start" name="final_bill_date" id="final_bill_date">
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Latter Ref</label>
					<input type="text" class="form-control w48 float-start" name="letter_no"  id="letter_no" >
					<input type="text" class="form-control w48 ms-2 float-start" name="letter_date" id="letter_date">
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Perticulars</label>
					<textarea rows="3" class="form-control w-100" name="header_desc" id="header_desc"></textarea>
				</div>
				<div class="col-md-3 float-start px-2 mb-1 hgt120">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Status</label>
					<input type="text" name="status_desc" id="status_desc"class="btn btn-primary cstmBtn mb-3 w-100 text-start">
				</div>
				<div class="col-md-3 float-start px-2 mb-1 hgt120">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Billable Option</label>
					<input type="text" name="billable_desc" class="btn btn-primary cstmBtn mb-3 w-100 text-start">
				</div>	
			</div>
			
		</div>
		
      </div>
    </section>

  </main><!-- End #main -->

<?= $this->endSection() ?>