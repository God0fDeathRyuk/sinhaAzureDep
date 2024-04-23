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
      <h1>Code Master </h1>
    </div><!-- End Page Title -->
    <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-code-master-insert?option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-code-master-insert?option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-code-master-insert?option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				
				<div class="d-inline-block w-100 btmDv">
					<div class="col-md-8 float-start mb-3 ">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Type</label>
						<input type="text" class="form-control w-24 float-start me-2" name="type_code" id="type_code" value="<?= ($option!='list')?$data['type_code']: '' ?>" readonly <?php echo $redokadd;?>/>
						<input type="text" class="form-control w-75 float-start" name="type_name" id="type_name" value="<?= ($option!='list')?$data['type_desc']: '' ?>" readonly <?php echo $redokadd;?>/>
					</div>
					<div class="col-md-6 float-start mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Code <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control w-50 float-start me-2" name="code_code" id="code_code" value="<?= ($option!='Add')?$data2['code_code']:'' ?>"  onKeyUp="javascript:(this.value=this.value.toUpperCase());" required <?php echo $redokadd;?>/>
					<input type="hidden" name="code_code_hi" id="code_code_hi" value="<?= ($option!='Add')?$data2['code_code']:'' ?>"/>
                    </div>						
					<div class="col-md-8 float-start mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Desc <strong class="text-danger">*</strong></label>
						<textarea rows="3" class="form-control w-100" required name="code_desc" id="code_desc" onKeyUp="javascript:(this.value=this.value.toUpperCase());" <?php echo $redokadd;?>><?= ($option!='Add')?$data2['code_desc']:'' ?></textarea>
					</div>
					<input type="hidden" name="display_id" id="display_id" value="<?php echo $_REQUEST['display_id']; ?>">
					<input type="hidden" name="menu_id" id="menu_id" value="<?php echo $_REQUEST['menu_id']; ?>">
					<input type="hidden" name="finsub" id="finsub" value="fsub">
					<div class="d-inline-block w-100">
						<button type="submit" class="btn btn-primary cstmBtn mt-2" onclick="return checkdataCode()" <?php echo $disview;?>>Save</button>
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