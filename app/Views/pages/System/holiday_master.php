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
  <h1 class="col-md-8 float-start">Holiday Master</h1>
</div><!-- End Page Title -->

<section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
            <form method="post" action="" name="procideHoliday" id="procideHoliday" >
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Year <strong class="text-danger">*</strong> </label>
					<select class="form-select mb-2" name="holiday_ind" id="holiday_ind" onChange="mybsplind()" <?php if(!empty($years)){ echo 'disabled';} ?>>
                        <option value=""> Select </option>
                    <?php foreach($fin_years as $finyr_row) { 
                        $yr=explode("-",$finyr_row['fin_year'])?>
                          <option value="<?php echo $yr[0];?>" <?php if($option!=''){ if($current_fin_year == $finyr_row['fin_year']) { echo 'selected'; } }?>><?php echo $finyr_row['fin_year'];?></option>
                          <?php } ?>
                        </select>
                        <input type="hidden" size="03" maxlength="02" name="calendar_year" id="calendar_year" value="<?= ($option!="")?$yr[0]:'' ?>"/>
                    <input type="hidden" size="03" maxlength="02" name="finsub" id="finsub" value="nsub">
                    <input type="hidden" name="option" id="option" value="View">
				</div>
                <div class="frms-sec-insde float-start col-md-12 px-2 mt-0 <?= ($option!="")?'d-none': 'd-block' ?>">
                    <input id="save_button" class="btn btn-primary cstmBtn mt-2" type="button" name="button" value="Proceed" onClick="return checkHolidaydata()">
					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Cancel</button>
				</div>
            </form>
            
            <form method="post" action="" name="SubmitHoliday" id="SubmitHoliday" >
                <div class="d-inline-block w-100 mt-2 <?= ($option!="")?'d-block': 'd-none' ?>">	
                <input type="hidden" name="tRowCount" id="tRowCount" value="<?php  if(!empty($years)){ echo count($years);}else{ echo '1';}?>" >		
                <input type="hidden" size="03" maxlength="02" name="calendar_year2" id="calendar_year2" value="<?= ($option!="")?$yr[0]:'' ?>"/>		
					<table class="table table-bordered tblePdngsml" id="holiday">
						<tbody><tr class="fs-14">
							<th>Date</th>
							<th>Day</th>
							<th>Description </th>
							<th>Action</th>
						</tr>
                        <?php 
                        $totRowcount=0;
                        if(!empty($years)){
                            foreach ($years as $key => $value){ $key=$key+1;
                            ?>
						<tr id="tabRow<?php echo $key ?>">							
							<td class="w-150 text-center">
								<input type="text" name="holiday_date<?php echo $key; ?>" id="holiday_date<?php echo $key; ?>" class="form-control datepicker" value="<?php if(isset($value['holiday_date'])) echo date_conv($value['holiday_date']);?>" >
							</td> 
							<td class="w-250"><input type="text" name="holiday_day<?php echo $key; ?>" id="holiday_day<?php echo $key; ?>" class="form-control" value="<?php if(isset($value['holiday_day'])) echo $value['holiday_day'];?>"></td>
							<td class="">
								<textarea name="holiday_desc<?php echo $key?>" id="holiday_desc<?php echo $key?>" class="form-control" rows="1"><?php if(isset($value['holiday_desc']))echo $value['holiday_desc'];?></textarea>
							</td>
                            <td class="text-center TbladdBtn wd100"><input class="<?= (count($years)==$key)? 'd-block' :'d-none'?> float-start me-2 text-center" type="button" name="Add_row<?= $key ?>" id="Add_row<?= $key ?>"  value="+" title="Add Row" onClick="addRow(<?php echo $key;?>, <?php echo $key;?>)"  tabindex="<?php echo $key; ?>"> 
                            &nbsp; <input class=" float-start text-center delt" type="button" name="Remove_row<?= $key ?>" id="Remove_row<?= $key ?>"  value="-" title="Remove Row" onClick="removeRow(<?php echo $key;?>)" ></td>
						</tr>
                        <?php }$totRowcount =count($years)+1; 
                    }?>
                    <?php    
                    if(empty($years)){ $key=1;?>
                    <tr>							
							<td class="w-150 text-center">
								<input type="text" name="holiday_date<?php echo $key; ?>" id="holiday_date<?php echo $key; ?>" class="form-control datepicker" value="<?php if(isset($value['holiday_date'])) echo date_conv($value['holiday_date']);?>" >
							</td> 
							<td class="w-250"><input type="text" name="holiday_day<?php echo $key; ?>" id="holiday_day<?php echo $key; ?>" class="form-control" value="<?php if(isset($value['holiday_day'])) echo $value['holiday_day'];?>"></td>
							<td class="">
								<textarea name="holiday_desc<?php echo $key?>" id="holiday_desc<?php echo $key?>" class="form-control" rows="1"><?php if(isset($value['holiday_desc']))echo $value['holiday_desc'];?></textarea>
							</td>
                            <td><input class="d-block" type="button" name="Add_row<?= $key ?>" id="Add_row<?= $key ?>"  value="+" title="Add Row" onClick="addRow(<?php echo $key;?>, <?php echo $key;?>)"  tabindex="<?php echo $key; ?>"></td>
						</tr>
                    <?php } ?>
						
					</tbody></table>
                    <input type="hidden" size="03" maxlength="02" name="finsub" id="finsub" value="fsub">
                    <input type="hidden" name="option" id="option" value="Edit">
                    <div class="frms-sec-insde d-block float-start col-md-12">
                        <input id="save_button" class="btn btn-primary cstmBtn mt-2" type="button" name="button" value="Save" onClick="return submitHolidaydata()">
    					<button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2">Cancel</button>
    				</div>
            </form>
				</div>
				
				
			</div>
			
		  </div>
      </div>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>