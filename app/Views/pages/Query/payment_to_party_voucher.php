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
      <h1>Payments Made To Party</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
    <form method="post" action="<?php echo base_url("/query/payment-to-party-voucher/") ?>" name="payment_to_party_frm" id="payment_to_party_frm" >
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-2 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
					<select class="form-select" name="branch_code" id="branch_code" required >
                    <?php foreach($data as $branch) { ?>
					<option value="<?= $branch['branch_code'] ?>" <?= ($option!='list')?($branch['branch_code'] == $branch['branch_code']['branch_code']) ? 'selected' : '':'' ?>><?= $branch['branch_name'] ?></option>
                    
					<?php } ?>
					</select>
					<?php foreach($data as $branch) { ?>
					<input type="hidden" name="branchName" id="branchName" value="<?= $branch['branch_name'] ?>">
					<?php } ?>
				</div>
				<div class="col-md-2 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Type <strong class="text-danger">*</strong></label>
					<select class="form-select w-100 float-start" name="voucher_type" id="voucher_type" onChange="myPayeeType()" required >
                    <option value="">--Select--</option>
                    <option value="PV">Payment</option>
        		    <option value="RV">Receipt</option>
            	    <option value="JV">Journal</option>
					</select>
				</div>
				<div class="col-md-2 float-start px-2 mb-1">
                <label class="d-inline-block w-100 mb-1 lbl-mn">Year</label>			
                <select class="form-select w-100 float-start" name="fin_year" id="fin_year" onChange="myPayeeType()" required >
                    <option value="">--Select--</option>
                    <?php foreach ($finyr_qry as $value){?>
                    <option value="<?= $value['fin_year']?>"><?= $value['fin_year'] ?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-5 float-start px-1 mb-1">		
                <label class="d-inline-block w-100 mb-1 lbl-mn">DB</label>		
                <select class="form-select w-100 float-start" name="daybook_code" id="daybook_code" onChange="myPayeeType()" required >
                    <option value="">--Select--</option>
                    <?php foreach ($daybook_qry as $value){?>
                    <option value="<?= $value['daybook_code']?>"><?= $value['daybook_desc'] ?></option>
                    <?php } ?>
					</select>
				</div>
                <div class="col-md-2 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">&nbsp;</label>
                    <input type="text" class="form-control w-100 float-start mt-27" name="voucher_no" id="voucher_no" required />
                    <input type="hidden" class="form-control w-100 float-start mt-27" name="serial_no" id="serial_no" required />
                </div>
				
				 
				<div class="tbl-sec d-inline-block w-100 bg-white p-2 mt-2 <?=($options!='pro')?'d-none':'d-block'?>">
					<table class="table border-0">
						<tbody>
							<tr class="fs-14">
								<th class="border">
									<span>Main</span>
								</th>
								<th class="border">
									<span>Sub </span>
								</th>
								<th class="border">
									<span>Client</span>
								</th>
								<th class="border">
									<span>Matter</span>
								</th>
								<th class="border w-350">
									<span>Narration</span>
								</th>
								<th class="border text-end">
									<span>Debit</span>
								</th>
								<th class="border text-end">
									<span>Credit</span>
								</th>
							</tr>
							
							<?php  $tdramt = 0; $tcramt = 0; foreach ($vchdtl_sql as $key => $value) {?>
							
							<tr class="fs-14 border-0">								
								<td class="border">
									<span><?= ($vchdtl_sql[0]!='')?$value['main_ac_code']:'' ?></span>
								</td>
								<td class="border">
									<span><?= ($vchdtl_sql[0]!='')?$value['sub_ac_code']:'' ?></span>
								</td>
								<td class="border">
									<span><?= ($vchdtl_sql[0]!='')?$value['client_code']:'' ?></span>
								</td>
								<td class="border">
									<span><?= ($vchdtl_sql[0]!='')?$value['matter_code']:'' ?></span>
								</td>
								<td class="border">
									<span><?= ($vchdtl_sql[0]!='')?$value['narration']:'' ?></span>
								</td>
								<td class="border text-end">
									<span><?php if(($vchdtl_sql[0]!='')?$value['dr_cr_ind']:'' == 'D') { echo $value['gross_amount'] ;} ?></span>
								</td>
								<td class="border text-end">
									<span><?php if(($vchdtl_sql[0]!='')?$value['dr_cr_ind']:'' == 'C') { echo $value['gross_amount'] ;} ?></span>
								</td>
							</tr>
							<?php   if( ($vchdtl_sql[0]!='')?$value['dr_cr_ind']:'' == 'D') { $tdramt = $tdramt + ($vchdtl_sql[0]!='')?$value['gross_amount'] :''; } else { $tcramt = $tcramt + ($vchdtl_sql[0]!='')?$value['gross_amount']:'' ;
							}} ?>
							<tr class="fs-14 border-0">								
								<td class="border text-end bgblue" colspan="5">
									<span>Total</span>
								</td>
								<td class="border bgblue text-end">
									<span><?php if($tdramt > 0) { echo number_format($tdramt,2,'.','') ; } ?></span>
								</td>
								<td class="border bgblue text-end">
									<span><?php if($tcramt > 0) { echo number_format($tcramt,2,'.','') ; } ?></span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php //echo '<pre>';print_r($vchhdr_sql);die;
				if($options!='send' && $vchhdr_sql!=null){
							
							?>
							<?php foreach ($vchhdr_sql as $key => $value) {?>
				<div class="col-md-8 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Party <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="payee_payer_name" id="payee_payer_name" value="<?php echo $value['payee_payer_name']?>" readonly/>
					<div class="d-block float-start mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">By <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control w-49 float-start" name="instrument_no" id="instrument_no" value="<?php echo $value['instrument_no']?>" readonly/>
						<input type="text" class="form-control w-49 float-start ms-2" name="instrument_date" id="instrument_date" value="<?php echo $value['instrument_dt']?>" readonly/>
						<input type="text" class="form-control w-100 float-start mt-2" name="tax_amount" id="tax_amount"     value="<?php echo $value['tax_amount']?>" readonly/>
					</div>
				</div>
				<div class="col-md-4 float-start px-2 mb-1">
					<div class="d-block w-100 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Gross <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="gross_amount" id="gross_amount"  value="<?php echo $value['gross_amount']?>" readonly/>						
					</div>
					<div class="d-block w-100 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Tax <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="tax_amount" id="tax_amount"  value="<?php echo $value['tax_amount']?>" readonly/>						
					</div>
					<div class="d-block w-100 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Net <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="net_amount" id="net_amount"    value="<?php echo $value['net_amount']?>" readonly/>						
					</div>
				</div>
				<?php }}
				if($options!='send' && $vchhdr_sql==null){  ?>
					<div class="col-md-8 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Party <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="payee_payer_name" id="payee_payer_name" value="" readonly/>
					<div class="d-block float-start mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">By <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control w-49 float-start" name="instrument_no" id="instrument_no" value="" readonly/>
						<input type="text" class="form-control w-49 float-start ms-2" name="instrument_date" id="instrument_date" value="" readonly/>
						<input type="text" class="form-control w-100 float-start mt-2" name="tax_amount" id="tax_amount"     value="" readonly/>
					</div>
				</div>
				<div class="col-md-4 float-start px-2 mb-1">
					<div class="d-block w-100 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Gross <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="gross_amount" id="gross_amount"  value="" readonly/>						
					</div>
					<div class="d-block w-100 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Tax <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="tax_amount" id="tax_amount"  value="" readonly/>						
					</div>
					<div class="d-block w-100 px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Net <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="net_amount" id="net_amount"    value="" readonly/>						
					</div>
				</div>
				<?php } ?>
				<div class="d-inline-block w-100 mt-2">
					<input type="hidden" name="options" id="options" value="pro" >
                    <input type="button" name="button" id="button" value="Proceed" class="btn btn-primary cstmBtn ms-2 float-start"  onClick="return checkPaymentToParty()" <?php if($options!='send'){ echo 'disabled';}else{ echo 'enable';} ?>>		
					<button type="button" class="btn btn-primary cstmBtn ms-2">Reset</button>				
					<button type="button" class="btn btn-primary cstmBtn btn-cncl ms-2">Exit</button>
				</div>
                
			</div>
			
		</div>
		
      </div>
    </form>
    </section>
	
</main><!-- End #main -->

<?= $this->endSection() ?>