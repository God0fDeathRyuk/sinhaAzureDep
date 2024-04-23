<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<main id="main" class="main">
<div class="pagetitle col-md-5 float-start">
  <h1> Query Details Add </h1>
</div><!-- End Page Title -->
<form action="<?php if($option == 'list'){ echo "/sinhaco/admin/query-details-add/add";} else{ echo '/sinhaco/admin/query-details-add/edit';}?>" method="get" id="queryDetailsAdd">
<div class="frms-sec d-inline-block w-100 bg-white p-3">
					<div class="inptSec col-md-4 float-start pe-2 mb-3">
						<label class="d-block w-100 mb-2 fw-bold">Query Id</label>
						<input type="text" name="query_id" class="form-control w-100 me-2 float-start" value="<?= ($option != 'add') ? $data['query_id'] :  ''?>"/>
					</div>
					<div class="inptSec col-md-4 float-start pe-2 mb-3">
						<label class="d-block w-100 mb-2 fw-bold">Query Name</label>
						<input type="text" name="query_name" class="form-control w-100 me-2 float-start" value="<?= ($option != 'add') ? $data['query_name'] :  ''?>"/>
					</div>
					<div class="inptSec col-md-4 float-start pe-2 mb-3">
						<label class="d-block w-100 mb-2 fw-bold">Query Module</label>
						<select class="form-select float-start" name="query_module_code"  value="<?= ($option != 'add') ? $data['query_module_code'] :  ''?>">
                        <?php  
                             foreach ($data1 as $key => $user) { 
				        ?>
							 <option value="<?php  echo $user['code_code'];?>" <?php if ($option == 'edit') if($user['code_code'] == $data['query_module_code']){ echo "selected";}?>> <?php echo $user['code_desc']?></option>
                        <?php } ?>
						</select>
					</div>
					<div class="inptSec col-md-4 float-start pe-2 mb-3">
						<label class="d-block w-100 mb-2 fw-bold">Programme</label>
						<input type="text" name="query_program_name" class="form-control w-100 me-2 float-start" value="<?= ($option != 'add') ? $data['query_program_name'] :  ''?>"/>
					</div>
					<div class="inptSec col-md-4 float-start pe-2 mb-3">
						<label class="d-block w-100 mb-2 fw-bold">Status</label>
						<select class="form-select float-start" name="status_code" >
                        <option value="A" <?php if ($option != 'add') if($data['status_code'] == "A") echo "selected";?>>Active</option>
                        <option value="C" <?php if ($option != 'add') if($data['status_code'] == "C") echo "selected";?>>Closed</option>
						</select>
					</div>
				</div>
                <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2" >Confirm</button> 
			<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</button>
    </form>
</main>
<?= $this->endSection() ?>