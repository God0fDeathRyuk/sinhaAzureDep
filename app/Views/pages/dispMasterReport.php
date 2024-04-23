
<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'crud']); ?>
<?php endif; ?>
<?php if ($renderFlag) : ?> 
  <main id="main" class="main"> <?php endif; ?>
  <section class="section dashboard">
  <form action="" method="post">
                <div class="row">
				<div class="pagetitle w-100 float-start border-bottom pb-1">
					<h1 class="col-md-8 float-start">Case Status <span class="badge rounded-pill bg-dark"></span> </h1>
					<div class="srchtop col-md-4 float-start mb-2">
						<label class="d-block float-start mt-1 lbl-mn w-15 fw-bold">Year </label>
                            <div class="input-group d-block float-start w-75">
                            <div class="form-outline float-start">
                                <input type="hidden" name="option" id="option" value="search">
                                <select class="form-select" name="year" id='date-dropdown'>
									<option value="">-Select-</option>
								</select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            </div>
                        
                    </div>					
				</div>
                    
                </div>
                    </form>
      <div class="row">
		  <div class="col-md-12 mt-2">
			<div class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-0">
				<table class="table border-0">
					<tbody>
						<tr>
							<td colspan="7" class="text-center border-0">
								<span class="d-block w-100 text-uppercase fw-bold">Sinha & Co Holiday List - <?= ($year!='')? $year:$yr ?></span>
							</td>
						</tr>
						<tr class="fs-14">
							<th class="border w-250">
								<span>Description </span>
							</th>
							<th class="border">
								<span>Day</span>
							</th>
							<th class="border">
								<span>Date</span>
							</th>
							<th class="border">
								<span>Month</span>
							</th>
							<th class="border">
								<span>Calendar year</span>
							</th>
						</tr>
                        <?php  if($years!=null){foreach ($years as $key => $value) {?>
						<tr class="fs-14 border-0">
							<td class="border">
								<span><?php if(!empty($value['holiday_desc'])){ echo $value['holiday_desc'];} ?></span>
							</td>
							<td class="border">
                                <span><?php if (!empty($value['holiday_day'])) {   echo $value['holiday_day'];} ?></span>
                                </td>
                                <td class="border">
                                    <span><?php if (!empty($value['holiday_day'])) {   $date = new DateTime($value['holiday_day']);  echo $date->format('jS'); } ?></span>
							</td>
							<td class="border">
                            <span><?php if (!empty($value['holiday_date'])) { $date = new DateTime($value['holiday_date']); echo $date->format('F');} ?></span>
							</td>
							<td class="border">
                            <span><?php if (!empty($value['calendar_year'])) {   echo $value['calendar_year'];   } ?></span>
							</td>
						</tr>
                        <?php }}
						else{ ?>
						<tr class="fs-14 border-0">
							<td class="border text-center" colspan="5">
								<span>No Data Found</span>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>			
		  </div>
      </div>
    </section>

  </main> 
  <script>
	let dateDropdown = document.getElementById('date-dropdown');

let currentYear = new Date().getFullYear();
let earliestYear = 1970;

while (currentYear >= earliestYear) {
  let dateOption = document.createElement('option');
  dateOption.text = currentYear;
  dateOption.value = currentYear;
  dateDropdown.add(dateOption);
  currentYear -= 1;
}
</script>
<?= $this->endSection() ?>