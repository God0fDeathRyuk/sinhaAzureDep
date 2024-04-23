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
<div class="pagetitle d-block float-start col-md-9">
      <h1>Standard Narration Master [<?php echo strtoupper($option) ?>]</h1>      
    </div><!-- End Page Title -->
	
	<div class="col-md-3 float-start text-end">
      <a href="javascript:void(0);" class="btn btn-primary cstmBtn mb-3">Active</a>      
    </div>
	<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-st-narration?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-st-narration?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-st-narration?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-3">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Serial No</label>
					<input type="text" class="form-control w-100" name="sl_no" id="sl_no" value="<?= ($option!='Add')?$data['serial_no']:'' ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Narration Type Code <strong class="text-danger">*</strong></label>
					<div class="position-relative d-inline-block smlBxLft float-start Inpt-Vw-icn w-100">
						<input type="text" class="form-control" name="narration_type" id="narrationType"
                                placeholder="Designation"
                                value="<?= ($option!='Add') ? $data['narration_type'] : '' ?>" onchange="fetchData(this, 'narration_type', ['narrationType', 'narrationName'], ['code_code', 'code_desc'], 'narration_type')"  required <?php echo $redokadd;?>/>
                            <i class="fa fa-binoculars icn-vw"
                                onclick="showData('code_code', '<?= '4084' ?>', 'narrationType', [ 'narrationType','narrationName'], ['code_code','code_desc'], 'narration_type')"
                                data-toggle="modal" data-target="#lookup"></i>
					</div>					
				</div>
				<div class="frms-sec-insde d-block float-start col-md-<?php if($option == 'Add'){echo '8'; }else{ echo '5'; }?> px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Narration Type Description</label>
					<textarea rows="3" class="form-control w-100" name="narration_name" id="narrationName" <?php echo $redokadd;?>><?= ($option!='Add')?$data['code_desc']:''  ?></textarea>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Standard Narration <strong class="text-danger">*</strong></label>
					<textarea rows="5" class="form-control w-100" required name="std_narration" id="std_narration" <?php echo $redokadd;?>><?= ($option!='Add')?$data['std_narration']:'' ?></textarea>
				</div>
				
			</div>		
		  </div>
		  <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<input type="hidden" name="finsub" id="finsub" value="fsub">
		<div class="col-md-12 d-inline-block">
			<button type="submit" class="btn btn-primary cstmBtn mt-3" <?php echo $disview;?>>Save</button>
			<?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
                        <?php } ?>  
			<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
		</div>
      </div>
    </section>
    <form>
</main><!-- End #main -->

<?= $this->endSection() ?>