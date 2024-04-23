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
      <h1>Sub Ledger Master [<?php echo strtoupper($option) ?>]</h1>      
    </div><!-- End Page Title -->
	<div class="col-md-3 float-start text-end">
      <a href="javascript:void(0);" class="btn btn-primary cstmBtn mb-3">Active</a>      
    </div><form action="/sinhaco/master/mas-sub-account-master?user_option=Edit" method="post" >
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-3">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Main Account</label>
					<div class="position-relative d-inline-block smlBxLft float-start Inpt-Vw-icn">
						<input type="text" class="form-control w-100" name="main_ac_code" id="main_ac_code" value="<?= ($option!='Add')?$data1['main_ac_code']:'' ?>" readonly/>
					</div>					
					<input type="text" class="form-control w-50 float-start" name="ac_desc" id="ac_desc" value="<?= ($option!='Add')?$data1['main_ac_desc']:'' ?>" <?php echo $redokadd;?>/>
					
				</div>
			</div>		
		  </div>
		  
		  <div class="col-md-12 mt-3">
			  <div class="d-inline-block w-100 mt-2 mb-2 bnd">
				<span>Sub A/c Details</span>
			  </div>
              
			<div class="mb-2 vertScrl">
                <input type="hidden" name="count" id="count" value="<?= count($data) ?>">
                <input type="hidden" name="orcount" id="orcount" value="<?= count($data) ?>">
				<table class="table borderTd bg-white Scrltbl_lrg" id="subAcmasterTab">
					<tr class="fs-14">
						<th class="border">Code</th>
						<th class="border">Description</th>
						<th class="border">Status</th>
					</tr>
                    <?php $count=count($data);
                    foreach ($data as $key => $value) { $key++?>
					<tr id="rowId<?= $key ?>">
						<td class="w130">
							<input type="text" class="form-control w-100" name="sub_ac_code<?= $key ?>" id="sub_ac_code<?= $key ?>" value="<?= $value['sub_ac_code'] ?>" <?php echo $redokadd;?>/>
						</td>
						<td class="w250">
                            <input type="hidden" name="subCod<?= $key ?>" id="subCod<?= $key ?>">
							<textarea rows="2" class="form-control" name="sub_ac_desc<?= $key ?>" id="sub_ac_desc<?= $key ?>" <?php echo $redokadd;?>><?= $value['sub_ac_desc'] ?></textarea>
						</td>
						<td class="fs-14 border-0">
                        <select class="form-select" name="status_code<?= $key ?>" id="status_code<?= $key ?>" required <?php echo $disview;?>>
							<option>--- Select ---</option>
                            <option value="Active" <?= ($value['status_code']=="Active")? 'selected':'' ?> >Active</option>
                            <option value="Old" <?= ($value['status_code']=="Old")? 'selected':'' ?> >Old</option>
                    </select>
						</td>
					</tr>
                    <?php } ?>
				</table>
                    <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>

                <a class="btn btn-primary cstmBtn mt-3 ms-2 <?php if($option=='View'){ echo 'd-none';} ?>"
                                        onclick="related_matters('subAcMaster')">Add</a>
			</div>
			<div class="col-md-12 d-inline-block">
				<button type="submit" class="btn btn-primary cstmBtn mt-3" <?php echo $disview;?>>Save</button>
				<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
			</div>
		  </div>
      </div>
    </section>
</form>
</main><!-- End #main -->

<?= $this->endSection() ?>