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
      <h1>Bill Details (By Matter)</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
        <form action="/sinhaco/query/qry-bill-details-matter" method="post" id="qryBillDetailsMatter" target="">
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">				
				<div class="float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
					<select class="form-select" name="branch_code" id="branchCode"  required >
						<?php foreach ($branch as $key => $value) {?>
						<option value="<?= $value['branch_code'] ?>" ><?= $value['branch_name'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-5 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
					<input type="text" class="form-control w-45 float-start datepicker" name="start_date"  id="start_date" required value="<?= ($option!='list')?$start_date:'' ?>"  onblur="make_date(this)">
					<span class="w-2 float-start mx-1">--</span>
					<input type="text" class="form-control w-45 float-start datepicker" name="end_date" id="end_date" required  value="<?= ($option!='list')?$end_date:date('d-m-Y') ?>">
				</div>
				
				<div class="float-start col-md-3 px-2 mb-1 position-relative">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter Code <strong class="text-danger">*</strong></label>
					<input type="text" name="matter_code" id="matterCode" class="form-control"  required readonly />
					<i class="fa fa-binoculars icn-vw" id="matterBinocular" onclick="showData('matter_code', '<?= '4219' ?>', 'matterCode', [ 'matterCode','matterDesc','clientName','clientCode'], ['matter_code','matter_desc','client_name','client_code'],'matter_code')"  data-toggle="modal" data-target="#lookup" style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
				</div>
				<div class="float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Matter Description</label>
					<input type="text" class="form-control" name="matter_desc" id="matterDesc"  readonly/>
				</div>
				<div class="float-start col-md-8 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Client Name</label>
					<input type="text" class="form-control" name="client_name" id="clientName"   readonly/>
                    <input type="hidden" class="form-control" name="client_code" id="clientCode"/>
				</div>	
				<div class="float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Bill Status <strong class="text-danger">*</strong></label>
					<select tabindex="6" class="form-select" name="bill_status" id="bill_status">
                        <option value='%'>All</option>
                        <option value='B'>Approved</option>
                        <option value='A'>Un-Approved</option>
                        <option value='X'>Cancelled</option>
				    </select> 
				</div>				
			</div>
            <input type="hidden" name="option" id="option" value="pro"/>
            <input type="button" name="button" id="button" value="Proceed" class="btn btn-primary cstmBtn mt-3 ms-2"  onClick="getBillDetailsMatter('qryBillDetailsMatter')">		
			<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</button>
		  </div>
         </form>
      </div>
    </section>

  </main><!-- End #main -->

<?= $this->endSection() ?>