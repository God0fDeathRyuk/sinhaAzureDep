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
      <h1>Bill Details (By Bill No)</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">

				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
					<select class="form-select" name="branch_code" id="branchCode"  required >
						<?php foreach ($branch as $key => $value) {?>
						<option value="<?= $value['branch_code'] ?>" <?php if($branchCode==$value['branch_code']){ echo 'selected';} ?>><?= $value['branch_name'] ?></option>
						<?php } ?>
					</select>
				</div>
				<?php  ?>
				<div class="col-md-5 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Bill Year/No <strong class="text-danger">*</strong></label>					
					<select class="form-select w48 float-start" name="fin_year" id="fin_year" required >
						<?php  foreach ($fin_years as $key => $value) {?>
						<option value="<?= $value['fin_year'] ?>" <?= (($data!=null)?$data['ref_bill_year']:''==$value['fin_year'])?'selected':'' ?>><?= $value['fin_year'] ?></option>
						<?php }?>
					</select>
					<span class="w-2 float-start mx-1 mt-2">/</span>
					<input type="text" class="form-control w48 float-start"  name="bill_no" id="bill_no" required>
				</div>
				<div class="col-md-4 float-start mt-20 position-relative">
				<input type="button" name="button" id="button" value="Show" class="btn btn-primary cstmBtn mt-4"  onClick="getBillDetails()">		
					
				</div>
				
				<div class="d-block float-start w-100 px-2 mt-2 tblscrlvtrcllrg ScrltblMn">
					<table class="table border-0">
						<tr class="fs-14">						
							<th>&nbsp;Year</th>
							<th>&nbsp;Doc Dt</th>
							<th>&nbsp;Doc #</th>
							<th>&nbsp;Type</th>
							<th>&nbsp;DB</th>
							<th>&nbsp;From</th>
							<th>&nbsp;Instr No</th>
							<th>&nbsp;Instr Dt</th>
							<th>&nbsp;Instr Bank</th>
							<th>Gross&nbsp;</th>
							<th>Service Tax&nbsp;</th>
						</tr>
						<?php if($data!=null){
						 foreach ($data as $i => $value) { ?>
						<tr class="fs-14">
						<td><input class="form-control"  type="text"  name="fin_year<?php echo $i?>"      value="<?php echo $data['fin_year'] ?>" readonly></td>
                        <td><input class="form-control"  type="text"  name="doc_date<?php echo $i?>"      value="<?php echo date_conv($data['doc_date']) ?>" readonly></td>
                        <td><input class="form-control"  type="text"  name="doc_no<?php echo $i?>"        value="<?php echo $data['doc_no'] ?>" readonly></td>
                        <td><input class="form-control"  type="text"  name="doc_type<?php echo $i?>"      value="<?php echo $data['doc_type'] ?>" readonly></td>
                        <td><input class="form-control"  type="text"  name="daybook_code<?php echo $i?>"  value="<?php echo $data['daybook_code'] ?>" readonly></td>
                        <td><input class="form-control"  type="text"  name="received_from<?php echo $i?>" value="<?php echo $data['received_from']  ?>" readonly></td>
                        <td><input class="form-control"  type="text"  name="instrument_no<?php echo $i?>" value="<?php echo $data['instrument_no'] ?>" readonly></td>
                        <td><input class="form-control"  type="text"  name="instrument_dt<?php echo $i?>" value="<?php echo date_conv($data['instrument_dt']) ?>" readonly></td>
                        <td><input class="form-control"  type="text"  name="bank_name<?php echo $i?>"     value="<?php echo $data['bank_name']  ?>" readonly></td>
                        <td><input class="form-control"  type="text"  name="gross_amount<?php echo $i?>"  value="<?php echo $data['gross_amount']  ?>" readonly></td>
                        <td><input class="form-control" type="text"  name="service_tax_amount<?php echo $i?>"  value="<?php echo $data['service_tax_amount']  ?>" readonly></td>
						</tr>
						<?php }}else{?>
							<tr class="fs-14">
						<td colspan="10" class="text-center">No Data Found</td>
                        
						</tr>
						<?php } ?>
					</table>
				</div>
				<div class="col-md-9 d-inline-block mt-1 d-none">
					<button type="button" class="btn btn-primary cstmBtn mt-2 ms-2">Reset</button>				
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Exit</button>
				</div>
			</div>
			
		</div>
		
      </div>
    </section>

  </main><!-- End #main -->
<?= $this->endSection() ?>