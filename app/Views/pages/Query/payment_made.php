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
    <form method="post" action="" name="payment_frm" id="payment_frm" >
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-3 float-start px-2 mb-1">
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
				<div class="col-md-4 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
					<input type="text" class="form-control w48 float-start me-1 datepicker" name="start_date" id="start_date" onblur="make_date(this)" required />
					<span class="float-start">--</span>
					<input type="text" class="form-control w-48 float-start ms-1 datepicker" name="end_date" id="end_date"  value="<?php echo date('d-m-Y'); ?>" onblur="make_date(this)" required />
                    <input class="display_text_mandatory" type="hidden" size="08" maxlength="10" name="current_date" id="current_date" value="<?php echo date('d-m-Y'); ?>">  
                </div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Type <strong class="text-danger">*</strong></label>
					<select class="form-select w-100 float-start" name="payee_payer_type" id="payee_payer_type" onChange="myPayeeType()" required >
                    <option value="">--Select--</option>
                    <option value="S">Supplier</option>
                    <option value="C">Counsel</option>
                    <option value="K">Clerk</option>
                    <option value="T">Stenographer</option>
                    <option value="A">Arbitrator</option>
                    <option value="O">Others</option>
					</select>
				</div>
				<div class="col-md-2 float-start px-2 mb-1">
                <label class="d-inline-block w-100 mb-1 lbl-mn">&nbsp;</label>			
					<input type="text" class="form-control w-100 float-start mt-27" name="payee_payer_code" id="payee_payer_code" required readonly/>
				</div>
				<div class="col-md-5 float-start px-1 mb-1 mt-2 position-relative">				
					<input type="text" class="form-control w-100 float-start" name="payee_payer_name" id="payee_payer_name" required readonly />
                    <i class="fa fa-binoculars icn-vw icn-vw2 d-none"
                                                    onClick="showData('corrp_addr_code', 'display_id=4403&payee_type=@payee_payer_type', 'payee_payer_code',  ['payee_payer_name','payee_payer_code'], ['payee_payer_name','payee_payer_code'], 'payee_payer_code','','1','payee_payer_code','')"
                                                    data-toggle="modal" data-target="#lookup"
                                                    style="display:<?php if($option=='view'){ echo $redv;} ?>" id="payee_payer_help" name="payee_payer_help"></i>
				</div>
				
				<div class="d-inline-block w-100 mt-2">
					<input type="hidden" name="options" id="options" value="pro" >
                    <input type="button" name="button" id="button" value="Proceed" class="btn btn-primary cstmBtn ms-2 float-start"  onClick="return paymentDataCheck()">		
					<input type="button" name="buttonprint" id="buttonprint" value="Print" class="btn btn-primary cstmBtn ms-2 <?= ($options!='')?'d-block float-start':'d-none' ?>"  onClick="return printpayment('datatable')">		
					<button type="button" class="btn btn-primary cstmBtn ms-2">Reset</button>				
					<button type="button" class="btn btn-primary cstmBtn btn-cncl ms-2">Exit</button>
				</div>
                <?php 
				if ($options!='send'){?>
                <div class="d-inline-block w-100 mt-3" id="datatable">
					<table class="table table-bordered tblePdngsml" >
						<tbody>
							<tr class="fs-14">
								<th class="text-center">View</th>
								<th>Year</th>
								<th>DB</th>
								<th>Doc Dt</th>
								<th>DOC #</th>
								<th>Instr No</th>
								<th>Instr Dt</th>
								<th>Instr Bank</th>
								<th class="text-end">Gross</th>
								<th class="text-end">Tax</th>
								<th class="text-end">Net</th>
							</tr>
							<?php $grosstotal=0;$nettotal=0;$gross=0;$net=0;
							 foreach ($data2 as $key => $value) {?>
							<tr>							
								<td class="text-center" id="view">
									<!-- <a href="/sinhaco/query/voucher-view?serial_no=<?php echo $value['serial_no']?>">*</a> -->
									<input type="radio" name="sl" id="sl" onClick="return proc_vou('qry_vou_frm',<?= $key ?>)"/>
									
								</td>
								<td class="">
									<span><?= $value['fin_year'] ?></span>
								</td>
								<td class=""><span><?= $value['daybook_code'] ?></span></td>
								<td class="">
									<span><?= $value['doc_date'] ?></span>
								</td>
								<td class="">
									<span><?= $value['doc_no'] ?></span>
								</td>
								<td>
									<span><?= $value['instrument_no']?> </span>
								</td>
								<td>
									<span><?= $value['instrument_dt'] ?> </span>
								</td>
								<td>
									<span><?= $value['daybook_name']?> </span>
								</td>
								<td class="text-end">
									<span><?= $value['gross_amount']?> </span>
								</td>
								<td class="text-end">
									<span> <?= $value['tax_amount']?></span>
								</td>
								<td class="text-end">
									<span><?= $value['net_amount'] ?></span>
								</td>
							</tr>
							<?php $gross=$gross+$value['gross_amount']; $net=$net+$value['net_amount'];
							}
							$total=$gross;
							$nettotal=$net;
							?>
							<tr>
								<td colspan="8" class="text-end"><span>Total</span></td>
								<td class="text-end"><span><?= number_format($total,2); ?></span></td>
								<td class="text-end"><span></span></td>
								<td class="text-end"><span><?= number_format($nettotal,2); ?></span></td>
							</tr>
						</tbody>
					</table>
				</div>
                <?php } ?>
			</div>
			
		</div>
		
      </div>
    </form>
    </section>
	<?php if ($options!=''){foreach ($data2 as $key => $value) {?>
		<form method="post" action="/sinhaco/query/payment-made" name="qry_vou_frm<?= $key ?>" id="qry_vou_frm<?= $key ?>" >
			<input type="hidden" name="serial_no" id="serial_no" value="<?= $value['serial_no'] ?>">
		</form>
	<?php }}?>
</main><!-- End #main -->

<?= $this->endSection() ?>