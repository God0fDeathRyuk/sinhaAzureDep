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
      <h1>Expense [<?php echo strtoupper($option) ?>]</h1>      
    </div><!-- End Page Title -->
	
	<div class="col-md-3 float-start text-end">
      <a href="javascript:void(0);" class="btn btn-primary cstmBtn mb-3">Active</a>      
    </div>
	<form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-expense-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-expense-master?user_option=Edit';} if($option == 'Delete'){ echo '/sinhaco/master/mas-expense-master?user_option=Delete';}?>" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-3">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>
					<input type="text" class="form-control w-100" placeholder="Code" name="expense_code" id="expense_code" value="<?= ($option!='Add')?$data['expense_code']:'' ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Description <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control w-100" placeholder="Description"  name="expense_description" id="expense_description" value="<?= ($option!='Add')?$data['expense_desc']:'' ?>" required onkeyup="javascript:(this.value=this.value.toUpperCase())"  <?php if ($option == 'Add') {?>	onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getExpNo')" <?php }?> <?php echo $redokadd;?>/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Type <strong class="text-danger">*</strong></label>
					<select class="form-select" required name="expense_type" id="expense_type" <?php echo $disview;?>>
						<option value="">-- Select --</option>
                        <option value="C" <?= ($option!='Add')?($data['expense_type']=="C")? 'Selected': '':'' ?>>Client</option>
					<option value="A" <?= ($option!='Add')?($data['expense_type']=="A")? 'Selected': '':'' ?>>Advance</option>
					</select>
				</div>				
			</div>		
		  </div>
		  <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<input type="hidden" name="finsub" id="finsub" value="fsub">
		<div class="col-md-12 d-inline-block">
			<button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="return expense_master_check()" <?php echo $disview;?>>Save</button>
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