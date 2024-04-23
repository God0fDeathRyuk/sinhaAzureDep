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
<div class="pagetitle">
      <h1>Photocopy Rate Master [<?php echo strtoupper($option) ?>] </h1>
    </div><!-- End Page Title -->
    <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-photocopy?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-photocopy?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-photocopy?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				
				<div class="d-inline-block w-100 btmDv">
					<div class="col-md-4 float-start mb-3 px-2 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Rate Code</label>
						<input type="text" class="form-control w-100 float-start me-2" name="rt_code" id="rt_code" value="<?= ($option!='Add')?$data['rate_code']: '' ?>" readonly/>
					</div>
					<div class="col-md-8 float-start mb-3 px-2 position-relative">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Agency Name</label>
						<input type="text" class="form-control" name="supplier_name" id="supplierName"
                                placeholder="Agency Name"
                                onchange="fetchData(this, 'supplier_code', ['supplierCode', 'supplierName'], ['supplier_code', 'supplier_name'], 'supplier_code')"
                                required value="<?= ($option!='Add') ? $data['supplier_name'] : '' ?>" readonly/>
                            <input type="hidden" class="form-control" name="supplier_code" id="supplierCode"
                                placeholder="Designation"
                                value="<?= ($option=='Edit') ? $data['supplier_code'] : '' ?>" />
                            <i class="fa fa-binoculars icn-vw"
                                onclick="showData('supplier_code', '<?= '4311' ?>', 'supplierCode', [ 'supplierCode','supplierName'], ['supplier_code','supplier_name'], 'supplier_code')"
                                data-toggle="modal" data-target="#lookup"></i>
					</div>	
                    <div class="col-md-4 float-start mb-3 px-2">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Page Size</label>
						<input type="text" class="form-control w-100 float-start me-2" placeholder="Page Size" name="page_size" id="page_size" value="<?= ($option!='Add')?$data['page_size']:'' ?>" <?php echo $redokadd;?>/>
					</div>					
					<div class="col-md-4 float-start mb-3 px-2">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Page Side <strong class="text-danger">*</strong></label>
						<select class="form-select" name="page_side" id="page_side" required <?php echo $disview;?>>
							<option value="">--- Select ---</option>
                            <option value="S" <?= ($option!='Add')?($data['page_side']=='S')? 'selected': '' :'' ?>>Single</option>
                	        <option value="B" <?= ($option!='Add')?($data['page_side']=='B')? 'selected': '' :'' ?>>Both</option>
						</select>
					</div>					
					<div class="col-md-4 float-start mb-3 px-2">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Rate <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control w-100 float-start me-2" placeholder="Rate" name="rate" id="rate" value="<?= ($option!='Add')?$data['rate']: '' ?>" required <?php echo $redokadd;?>/>
					</div>
					<div class="col-md-4 float-start mb-3 px-2">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Status <strong class="text-danger">*</strong></label>
						<select class="form-select" name="status_code" id="status_code" required <?php echo $disview;?>>
							<option value="">--- Select ---</option>
                            <option value="Active" <?= ($option!='Add')?($data['status_code']=='Active')?'selected': '' :'' ?>>Active</option>
                	        <option value="Old" <?= ($option!='Add')?($data['status_code']=='Old')?'selected': '' :'' ?>>Old</option>
						</select>
					</div>
<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<input type="hidden" name="finsub" id="finsub" value="fsub">
					<div class="d-inline-block w-100">
						<button type="submit" class="btn btn-primary cstmBtn mt-2" onclick="return photocopy_master_check()" <?php echo $disview;?>>Save</button>
						<?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-2 ms-2">Delete</button>
                        <?php } ?>  
						<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</a>
					</div>
				</div>
			</div>
			
		</div>
		
      </div>
    </section>
</form>
</main><!-- End #main -->

<?= $this->endSection() ?>