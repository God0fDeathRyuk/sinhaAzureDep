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
<div class="pagetitle <?php if($options!="Proceed"){echo 'd-block';}else{echo 'd-none';}?>">
      <h1>Query Details</h1>
    </div><!-- End Page Title -->
    <div class="pagetitle <?php if($options!="Proceed"){echo 'd-none';}else{echo 'd-block';}?>">
      <h1>Selection of Query  [Proceed]</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
    <form method="post" action="" name="query_frm" id="query_frm" class="<?php if($options!="Proceed"){echo 'd-block';}else{echo 'd-none';}?>" target="">
      <div class="row">
		  <div class="col-md-12">
			<div class="frms-sec d-inline-block w-100 bg-white p-3 pt-0 position-relative">
				<p class="d-inline-block w-100 bdge mb-2">Module : <span class="text-uppercase">Finance</span></p>
				<div class="frms-sec-insde d-block float-start col-md-4 px-0 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Query Name</label>
					<select class="form-select" name="query_id" id="query_id">
						<option value="">-- Select --</option>
                        <?php foreach ($data as $key => $value) {?>
						<option value="<?= ($option!="Proceed")?$value['query_id']:''?>"><?= ($option!="Proceed")?$value['query_name']:''?></option><?php } ?>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-12 px-0 mt-10">
                    <input type="hidden" name="options"id="options" value="send">
                    <input type="button" name="button" id="button" value="Proceed" class="btn btn-primary cstmBtn btncls mt-2"  onClick="return proc()">
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2">Back</button>
				</div>
			</div>
			
		  </div>
      </div>
    </form>
    </section>
    <section class="section dashboard <?php if($options!="Proceed"){echo 'd-none';}else{echo 'd-block';}?>">
      <div class="row">
      <div class="frms-sec d-inline-block w-100 bg-white p-3 pt-2">
		  <div class="col-md-6">
			  <div class="search">
				<label class="d-block w-100 mb-2">Search</label>
				<input class="form-control w-65 d-block float-start" type="search"/>
				<button class="btn btn-primary cstmBtn ms-2 d-block float-start">Search</button>
			  </div>
		  </div>
		  <div class="col-md-12 mt-0">
			<div class="tbl-sec scrltbl d-inline-block w-100 bg-white mt-3">				
				<table class="table border-0">
					<tr class="fs-14">
						<th class="border">
							<span>Client Name</span>
						</th>
						<th class="border">
							<span>Client Code</span>
						</th>
					</tr>
                    <?php foreach ($data2 as $key => $value) {?>
					<tr class="fs-14 border-0">
						<td class="border">
							<a href="javascript:void(0)"  onClick="getAddress(<?php echo $key?>,'<?php echo $value['client_code'];?>'),getAttention(<?php echo $key?>,'<?php echo $value['client_code'];?>')"  ><?= ($options!="list")?$value['client_name']:'' ?></a>
						</td>
						<td class="border">
                        <a href="javascript:void(0)"  onClick="getAddress(<?php echo $key?>,'<?php echo $value['client_code'];?>')" onClick="getAttention(<?php echo $key?>,'<?php echo $value['client_code'];?>')" ><?=  ($options!="list")?$value['client_code']:'' ?></a>
						</td>
					</tr>
                    <?php }?>
				</table>
			</div>
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
			<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</button>
		  </div>
      </div>
      </div>
    </section>

</main><!-- End #main -->

<?= $this->endSection() ?>