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
<div class="pagetitle w-100 float-start border-bottom pb-1">
      <h1 class="col-md-8 float-start">Designation Master (Add)</h1>
    </div><!-- End Page Title -->
    <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-designation-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-designation-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-designation-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="col-md-4 float-start px-2 mb-3 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Designation Code  <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100 float-start" name="designation_code" id="designation_code" value="<?= ($option!='Add')? $data['designation_code']: $data1['maxValue']+1 ?>"required readonly />
				</div>
				<div class="col-md-8 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Designation Name  <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100 float-start" placeholder="Designation Name" name="designation_name" id="designation_name" value="<?= ($option!='Add')?$data['designation_name']:'' ?>" onkeyup="javascript:(this.value=this.value.toUpperCase())" required <?php echo $redokadd;?>/>
				</div>
						<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<input type="hidden" name="finsub" id="finsub" value="fsub">
<div class="d-inline-block w-100 mt-3">
	  <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" onclick="data_check_designatiokn()" <?php echo $disview;?>>Save</button>				
	  <?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
                        <?php } ?>				
	  <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
	  </div>
			</div>
			
		</div>
		
      </div>
    </section>
</form>
</main><!-- End #main -->

<?= $this->endSection() ?>