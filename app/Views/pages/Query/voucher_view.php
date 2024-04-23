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
      <h1>Voucher [View]</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="d-inline-block w-100 mt-2">
					<table class="table table-bordered tblePdngsml">
						<tr>
							<td class="bgBlue">
								<span>Serial No</span>
							</td>
							<td>
								<span><?= ($option=='list')?$data['ref_doc_serial_no']:''?></span>
							</td>
							<td class="bgBlue">
								<span>Voucher</span>
							</td>
							<td>
								<span><?= ($option=='list')?$data['doc_type'].'/'.  $data['doc_no'].'/'.  date_conv($data['doc_date'],'-').  '/Paid By'.' - '. $data['paid_by']:''?></span>
							</td>
						</tr>
						<tr>
							<td class="bgBlue">
								<span>Fin Year</span>
							</td>
							<td>
								<span><?= ($option=='list')?$data['fin_year']:''?></span>
							</td>
							<td class="bgBlue">
								<span>Payee</span>
							</td>
							<td>
								<span><?= ($option=='list')?$data['payee_payer_name']:''?></span>
							</td>
						</tr>
						<tr>
							<td class="bgBlue">
								<span>Branch</span>
							</td>
							<td>
								<span><?= ($option=='list')?$data['branch_name']:''?></span>
							</td>
							<td class="bgBlue">
								<span>Daybook Code</span>
							</td>
							<td>
								<span><?php if($option=='list'){if ($data['daybook_code'] != '10') {echo $data['instrument_no'];}?> &nbsp; <?php if ($data['daybook_code'] != '10' && $data['daybook_code'] != '40') {echo'Date:- '. date_conv($data['instrument_dt'],'-');}?> &nbsp; <?php if ($data['daybook_code'] != '10' && $data['bank_name'] != '' ) {echo'Bank - '. $data['bank_name'];}?>  <?php if ($data['daybook_code'] == '10') {echo $data['daybook_code'];}}?></span>
							</td>
						</tr>
					</table>
					<table class="table table-bordered tblePdngsml">
						<tbody>
							<tr class="fs-14">
								<th>Main</th>
								<th>Sub</th>
								<th>Matter</th>
								<th>Client</th>
								<th>Bill No</th>
								<th>Purpose</th>
								<th class="text-end">Debit</th>
								<th class="text-end">Credit</th>
							</tr>
                            <?php $tdtotal=0; $tctotal=0; foreach ($data1 as $key => $value) {?>
							<tr>							
								<td class="">
									<span><?= $value['main_ac_code'] ?></span>
								</td>
								<td class="">
									<span><?= $value['sub_ac_code'] ?></span>
								</td>
								<td class=""><span><?= $value['matter_code'] ?></span></td>
								<td class="">
									<span><?= $value['client_code'] ?> </span>
								</td>
								<td class="">
									<span><?= $value['bill_no'] ?> </span>
								</td>
								<td class="w-350">
									<span><?= $value['narration'] ?> </span>
								</td>
								<td class="wd100 text-end">
									<span><?php if($value['dr_cr_ind'] == 'D') {echo $value['gross_amount'];} else { echo '&nbsp;'; }?>&nbsp;</span>
								</td>
								<td class="wd100 text-end">
									<span><?php if($value['dr_cr_ind'] == 'C') {echo $value['gross_amount'];} else { echo '&nbsp;'; }?>&nbsp;</span>
								</td>
							</tr>
                            <?php if($value['dr_cr_ind'] == 'D') { $tdtotal = $tdtotal + $value['gross_amount'] ; } else { $tctotal = $tctotal + $value['gross_amount'] ; } } ?>
							<tr>							
								
								<td class="text-end bgBlue" colspan="6">
									<span>Total</span>
								</td>
								<td class="wd100 bgBlue text-end">
									<span><?php if($tdtotal != 0) {echo number_format(abs($tdtotal),2,'.','') ;} else {echo '&nbsp;';} ?></b>&nbsp;</span>
								</td>
								<td class="wd100 bgBlue text-end">
									<span><?php if($tctotal != 0) {echo number_format(abs($tctotal),2,'.','') ;} else {echo '&nbsp;';} ?></b>&nbsp;</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-12">
				<a class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2" href="javascript:window.open('','_self').close();">Back</a>
				</div>
			</div>
			
		  </div>
      </div>
    </section>
</main><!-- End #main -->

<?= $this->endSection() ?>