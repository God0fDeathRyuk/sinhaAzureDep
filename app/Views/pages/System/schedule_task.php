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
  <h1 class="col-md-8 float-start">Schedule Task</h1>
</div><!-- End Page Title -->
<section class="section dashboard">
<form method="post" action="" name="scheduleTask" id="scheduleTask" >
      <div class="row">
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">				
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
				<input type="hidden" name="task_id" id="task_id" value="<?= ($option!='Add')? $data2['task_id']: '' ?>">
					<label class="d-inline-block w-100 mb-1 lbl-mn">User Name  <strong class="text-danger">*</strong></label>
					<select class="form-select"  name="user_id"  id="user_id" required <?=$disview ?>>
						<option value="">-- Select --</option>
						<?php  foreach ($data as $key => $value) {?>
						<option value="<?=  $value['user_id'];?>"  <?= ($option!='Add')?($data2['user_id'] == $value['user_id'])? 'selected' :'':'' ?>><?=  $value['user_name'];?></option>
						<?php } ?>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Start Date  <strong class="text-danger">*</strong></label>
					<input type="text" name="start_date" id="start_date" value="<?= ($option!='Add')? $data2['start_date']:'' ?>" class="form-control datepicker" required <?= $redokadd; ?> >
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Task Description  <strong class="text-danger">*</strong></label>
					<textarea rows="2" name="task_desc" id="task_desc"   class="form-control" required <?= $redokadd; ?>><?= ($option!='Add')? $data2['task_desc']:'' ?></textarea>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Task Frequency  <strong class="text-danger">*</strong></label>
					<?php $seprate1=explode(" ",($option!='Add')?$data2['task_freq']: ''); ?>
					<input type="text" class="form-control w-65 float-start"  name="task_freq" id="task_freq" value="<?= ($option!='Add')? $seprate1[0]:''?>" required <?= $redokadd; ?>>
					<select class="form-select w-33 float-start ms-2" name="task_day" id="task_day" <?=$disview ?>>
						<option value="day" <?= ($option!='Add')? ($seprate1[1]=='day')? 'selected': '':'' ?>>Day</option>
						<option value="month" <?= ($option!='Add')? ($seprate1[1]=='month')? 'selected': '':'' ?>>Month</option>
					</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Advance Notice period  <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="adv_notice" id="adv_notice" value="<?= ($option!='Add')? $data2['advance_notice_period']:''?>" required <?= $redokadd; ?>>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Status  <strong class="text-danger">*</strong></label>
					<select class="form-select"  name="status" id="status" <?=$disview ?>>
						<option value="Active" <?= ($option!='Add')? ($data2['status']=='Active')? 'selected': '':'' ?>>Active</option>
						<option value="Old" <?= ($option!='Add')? ($data2['status']=='Old')? 'selected': '':'' ?>>Inactive</option>
					</select>
				</div>
				
				<div class="frms-sec-insde d-block float-start col-md-12 px-2 mt-10">
				<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/> 
                <input type="hidden" name="finsub" id="finsub" value="fsub">
				<input type="hidden" name="user_option" id="user_option" value="<?php echo $_REQUEST['user_option']; ?>">
				<input id="save_button" class="btn btn-primary cstmBtn mt-2" type="button" name="button" value="Save"  onClick="return valid()">
				<?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btndanger cstmBtn mt-2" >Delete</button>
                        <?php } ?>
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2">Cancel</button>
				</div>
			</div>
			
		  </div>
      </div>
</form>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>