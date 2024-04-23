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
<?php endif; ?>
<div class="pagetitle <?php if($option=='list' && $options==""){echo 'd-block';}else{echo 'd-none';}?>">
      <h1>Query Details</h1>
    </div><!-- End Page Title -->
    <div class="pagetitle <?php if($option=="list" && $options!=""){echo 'd-block';}else{echo 'd-none';}?>">
      <h1>Selection of Query  [Proceed]</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
    <form method="post" action="" name="query_frm" id="query_frm" class="<?php if($option=='list' && $options!=""){echo 'd-none';}else{echo 'd-block';}?>">
      <div class="row">
		  <div class="col-md-12">
			<div class="frms-sec d-inline-block w-100 bg-white p-3 pt-0 position-relative">
				<p class="d-inline-block w-100 bdge mb-2">Module : <span class="text-uppercase">Client-Matter</span></p>
				<div class="frms-sec-insde d-block float-start col-md-4 px-0 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Query Name</label>
					<select class="form-select" name="query_id" id="query_id">
						<option value="">-- Select --</option>
                        <?php foreach ($data as $key => $value) {?>
						<option value="<?= ($option=="list")?$value['query_id']:''?>"><?= ($option=="list")?$value['query_name']:''?></option><?php } ?>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-12 px-0 mt-10">
                    <input type="hidden" name="options"id="options" value="Proceed">
                    <input type="button" name="button" id="button" value="Proceed" class="btn btn-primary cstmBtn btncls mt-2"  onClick="return proc()">
				</div>
			</div>
			
		  </div>
      </div>
    </form>
    </section>
    <section class="section dashboard <?php if($option=="list" && $options!=""){echo 'd-block';}else{echo 'd-none';}?>">
      <div class="row">
      <div class="frms-sec d-inline-block w-100 bg-white p-3 pt-2">
	  <form method="post" action="" name="query_frm2" id="query_frm2" >
		  <div class="col-md-6">
			  <div class="search">
				<label class="d-block w-100 mb-2">Search</label>
				<input class="form-control w-65 d-block float-start" name="search_val" id="search_val" type="search"/>
				<input type="hidden" name="options"id="options" value="Search">
				<input type="hidden" name="query_id"id="query_id" value="<?php  ?>">
				<input type="button" class="btn btn-primary cstmBtn ms-2 d-block float-start" name="Searchbutton" id="Searchbutton" value="Search" onClick="return procSearch()">
			  </div>
		  </div>
	  </form>
		  <div class="col-md-12 mt-0">
			<div class="tbl-sec scrltbl d-inline-block w-100 bg-white mt-3">				
				<table class="table border-0 w-100">
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
			<div class="tbl-sec scrlTbl d-inline-block w-100 bg-white mt-2">
				<p class="d-inline-block w-100 mb-2">
					<span class="d-block float-start w-auto fw-bold">Address(s) of : </span>
					<span id="ClNm" class="d-block float-start fw-bold ms-2">EQUIFAX CREDIT INFORMATION SERVICES</span>
				</p>
				<table class="table border-0" id="addrTab" name="addrTab">
					<tr class="fs-14" >
						<th class="border wd100">
							<span>Code</span>
						</th>
						<th class="border w-250">
							<span>Address Line 1</span>
						</th>
						<th class="border w-250">
							<span>Address Line 2</span>
						</th>
						<th class="border w-250">
							<span>Address Line 3</span>
						</th>
						<th class="border w-250">
							<span>Address Line 4</span>
						</th>
						<th class="border w-150">
							<span>City</span>
						</th>
						<th class="border w-150">
							<span>Pin</span>
						</th>
						<th class="border w-150">
							<span>State</span>
						</th>
						<th class="border w-150">
							<span>Country</span>
						</th>
						<th class="border w-150">
							<span>Phone</span>
						</th>
						<th class="border w-150">
							<span>Fax</span>
						</th>
						<th class="border w-250">
							<span>Email</span>
						</th>
					</tr>
					
				</table>
			</div>
			
			<div class="tbl-sec scrlTblMd d-inline-block w-100 bg-white mt-2">
				<p class="d-inline-block w-100 mb-2">
					<span class="d-block float-start w-auto fw-bold">Address(s) of : </span>
					<span id="ClNm" class="d-block float-start fw-bold ms-2">UNIT 932, 3RD FLOOR, BUILDING NO. 9 </span>
				</p>
				<table class="table border-0" id="addrTab2">
					<tr class="fs-14">
						<th class="border w-250">
							<span>Attention Name</span>
						</th>
						<th class="border w-250">
							<span>Designation</span>
						</th>
						<th class="border w-150">
							<span>Phone</span>
						</th>
						
						<th class="border w-150">
							<span>Fax</span>
						</th>
						<th class="border w-150">
							<span>Mobile</span>
						</th>
						<th class="border w-250">
							<span>Email</span>
						</th>
					</tr>
					
				</table>
			</div>
			<!-- <button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</button> -->
		  </div>
      </div>
      </div>
    </section>

</main><!-- End #main -->

<?= $this->endSection() ?>