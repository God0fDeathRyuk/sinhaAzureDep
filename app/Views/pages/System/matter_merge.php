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
  <h1 class="col-md-8 float-start">Matter Merging</h1>
</div><!-- End Page Title -->
<section class="section dashboard">
<form method="post" action="" name="matterMerge" id="matterMerge" >
      <div class="row">
		  <div class="col-md-12 mt-1">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">				
				<div class="col-md-3 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Financial Year</label>
					<select class="form-select"  name="fin_year"  id="fin_year">
					<?php foreach($fin_years as $finyr_row) { ?>
                          <option value="<?php echo $finyr_row['fin_year'];?>" <?php if($current_fin_year == $finyr_row['fin_year']) { echo 'selected'; } ?>><?php echo $finyr_row['fin_year'];?></option>
                         <?php } ?>
					</select>
				</div>
				<div class="d-inline-block w-100">
					<div class="col-md-2 float-start px-2 position-relative mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Matter - From</label>
                        <input type="text" class="form-control" name="old_matter_code" id="oldMatterCode" placeholder="Matter Code" onchange="fetchData(this, 'old_matter_code', ['oldMatterCode', 'oldClientName','oldClientCode','oldMatterDesc'], ['matter_code', 'client_name','client_code','matter_desc'],'matter_code')" required  value="" readonly/>
                    <i class="fa-solid fa-binoculars icn-vw" id="matterBinocular" onclick="showData('matter_code', '<?= '4207' ?>', 'oldMatterCode', [ 'oldMatterCode','oldClientName','oldClientCode','oldMatterDesc'], ['matter_code','client_name','client_code','matter_desc'],'matter_code')"  data-toggle="modal" data-target="#lookup" style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
					</div>
					
					<div class="col-md-10 float-start px-2 mb-1">
						<label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
						<input type="text" name="old_matter_desc" id="oldMatterDesc" class="form-control mb-1" required />
                        <input type="hidden" name="old_client_code" id="oldClientCode">
						<input type="text" name="old_client_name" id="oldClientName" class="form-control" required />
                        <input type="hidden" name="old_client_code" id="oldClientCode">
					</div>
				</div>
				<hr/>
				<div class="col-md-2 float-start px-2 position-relative mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Matter - To</label>
					<input type="text" class="form-control" name="new_matter_code" id="newMatterCode" placeholder="Matter Code" onchange="fetchData(this, 'old_matter_code', ['newMatterCode', 'newMatterDesc','newClientCode','newClientName'], ['matter_code', 'matter_desc','client_code','client_name'],'matter_code')" required  value="" readonly/>
                    <i class="fa-solid fa-binoculars icn-vw" id="newmatterBinocular" onclick="showData('matter_code', '<?= '4208' ?>', 'newMatterCode', [ 'newMatterCode','newMatterDesc','newClientCode','newClientName'], ['matter_code','matter_desc','client_code','client_name'],'matter_code')"  data-toggle="modal" data-target="#lookup" style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
				</div>
				
				<div class="col-md-10 float-start px-2 mb-3">
					<label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
					<input type="text" name="new_matter_desc" id="newMatterDesc" class="form-control mb-1" required />
						<input type="text" name="new_client_name" id="newClientName" class="form-control" required />
                        <input type="hidden" name="new_client_code" id="newClientCode">
				</div>
				<div class="d-inline-block w-100">
				<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/> 
                <input type="hidden" name="finsub" id="finsub" value="fsub">
				<input type="hidden" name="option" id="option" value="merge">
				<input id="save_button" class="btn btn-primary cstmBtn mt-2" type="button" name="button" value="Confirm" onClick="return checkMatterMergedata()">
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Exit</button>
				</div>
			</div>
			
		  </div>
      </div>
</form>
    </section>

<?= $this->endSection() ?>