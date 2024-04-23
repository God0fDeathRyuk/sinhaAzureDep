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
      <h1>Bank Master [<?php echo strtoupper($option)?>]</h1>
    </div><!-- End Page Title -->
    <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-bank-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-bank-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-bank-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				
				<div class="frms-sec-insde float-start col-md-3 px-2 mb-3 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bank Code</label>
					<input type="text" class="form-control" name="bank_code" id="bank_code" value="<?= ($option!='Add')?$data['bank_code']:'' ?>" placeholder="Bank Code" readonly />
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Bank Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="bank_name" id="bank_name" value="<?= ($option!='Add') ? $data['bank_name']: '' ?>" placeholder="Bank Name"required <?php if($option=='Add'){ ?> onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getTotBankCount')<?php }?>" onKeyUp="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>/>
				</div>
				<div class="col-md-3 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">BSR Code  <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100 float-start" name="bsr_code" id="bsr_code" value="<?= ($option!='Add') ? $data['bsr_code']: ''  ?>" placeholder="BSR Code" onKeyUp="javascript:(this.value=this.value.toUpperCase())" required <?php echo $redokadd;?>/>
				</div>
				
			</div>
                    <input type="hidden" name="display_id" id="display_id" value="<?php echo $_REQUEST['display_id']?>">
                    <input type="hidden" name="menu_id" id="menu_id" value="<?php echo $_REQUEST['menu_id']?>">
					<input type="hidden" name="finsub" id="finsub" value="fsub">
			<button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" id="save_button" onClick="return bank_master_check()" <?php echo $disview;?>>Save</button>
			<?php if($option=="Delete"){?>
<button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
<?php } ?>
			<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
		  </div>
      </div>
    </section>
</form>
</main><!-- End #main -->

<?= $this->endSection() ?>