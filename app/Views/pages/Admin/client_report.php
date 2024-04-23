<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>
<?php if($renderFlag!=""){ ?>
	
<main id="main" class="main Nwprnt_d_none">
<div class="pagetitle">
      <h1>Client Report</h1>      
</div><!-- End Page Title -->
	<div class="frms-sec d-inline-block w-100 bg-white p-3">
        <form action="" method="post" id="clientReport">
        <div class="d-inline-block w-100">
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">From Date</label>
						<input type="text"  name="frmDt" id="frmDt" value="<?php if($option!="list"){ echo $frmDt;} ?>" class="form-control txtFrom" onblur="make_date(this)"/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">To Date</label>
						<input type="text" name="toDate" id="toDate"  value="<?php if($option!="list"){ echo $toDate;} ?>" class="form-control txtTo" onblur="make_date(this)"/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Report Pattern</label>
						<select class="form-select" name="menu_type">
							<option  value="">Select</option>
                            <option  value="W" <?php if($menu_type=='W'){ echo 'selected';}?>>With Attention & Address</option>
							<option  value="WO" <?php if($menu_type=='WO'){ echo 'selected';}?>>With Out Attention & Address</option>
						</select>
					</div>
					<input type="hidden" name="report" id="report" value=""/>									
				</div>
				<!-- <button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="submitSystemMenuForm();getExcel('')">Proceed</button> -->
				<a href="javascript:void(0)"  class="btn btn-primary cstmBtn mt-3" onclick="getExcel('')">Proceed</a>
				<a href="javascript:void(0)"  onclick="getExcel('Excel')" class="btn btn-primary cstmBtn mt-3">Excel</a>
				<a href="javascript:void(0)"  onclick="getExcel('Pdf')" class="btn btn-primary cstmBtn mt-3" >Pdf</a>
				<a href="javascript:void(0)"  onclick="getExcel('Print')" class="btn btn-primary cstmBtn mt-3" >Print</a> 
        </form>
</div>
</main>
<?php } else{?>
<script>
	document.getElementById('sidebar').style.display = "none";
	document.getElementById('burgerMenu').style.display = "none";
</script>
<?php } ?>
	<main id="main" class="main mt-0">
    <div class="col-md-12 mt-2 <?php if($option=="list"){ echo 'd-none';}?>">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="d-inline-block w-100 mt-2">
					
					<table class="table table-bordered tblePdngsml NwprntTbl">
						<tbody>
                        <?php if($option=="with"){ ?>
							<tr class="fs-14">
								<th>Client Name</th>
								<th>Client Code</th>
								<th>Address</th>
								<th>Attention</th>
							</tr>
                            <?php } else if($option=='without'){ ?>
                            <tr class="fs-14">
								<th>Client Name</th>
								<th>Client Code</th>
								<th>Created On</th>
								<th>Created By</th>
							</tr>
                            <?php } if($option=="with"){ //echo '<pre>'; print_r($data);die;
                                 foreach ($data as $key => $value) {?>
<tr>							
								<td class="w-260">
									<span><?php echo $value['client_name']; ?></span>
								</td>
								<td class="">
									<span><?php echo $value['client_code']; ?></span>
								</td>
								<td class=""><span><?php echo $value['address_line_1']; $value['address_line_2']; $value['address_line_3']; $value['address_line_4']; ?></span></td>
								<td class="">
									<span><?php echo $value['attention_name']; ?></span>
								</td>
							</tr>
                           <?php }}
                           else if($option=='without'){
						   foreach ($data as $key => $value) {?>
<tr>							
								<td class="w-260">
									<span><?php echo $value['client_name']; ?></span>
								</td>
								<td class="">
									<span><?php echo $value['client_code']; ?></span>
								</td>
								<td class=""><span><?php echo $value['prepared_on']; ?></span></td>
								<td class="">
									<span><?php echo $value['prepared_by']; ?></span>
								</td>
							</tr>

                          <?php } }?>
						</tbody>
					</table>
				</div>
				<!-- <div class="frms-sec-insde d-block float-start col-md-12">
					<button type="button" class="btn btn-primary cstmBtn btn-cncl">Close</button>
				</div> -->
			</div>
			
		  </div>
</main>
    

<?= $this->endSection() ?>