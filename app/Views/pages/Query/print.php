<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<main id="main" class="main m-0 m-auto">
    <section class="section dashboard mtop90" >
      <div class="row">
		  <div class="col-md-12">
		  <div class="d-inline-block w-100 mt-3">
				<table class="table table-bordered tblePdngsml">
					<tbody>
						<tr class="fs-14">
							<th>Client</th>
							<th>Matter</th>
							<th>Case No</th>
							<th>Matter Desc</th>
							<th>Ref No</th>
							<th>Court</th>
							<th>Judge</th>
						</tr>
						<?php foreach ($data as $key => $value) {?>
						<tr>
							<td class="">
								<span><?= $value['client_name'] ?></span>
							</td>
							<td class=""><span><?= $value['matter_code'] ?></span></td>
							<td class="">
								<span><?= $value['matter_desc1'] ?></span>
							</td>
							<td class="">
								<span><?= $value['matter_desc2'] ?></span>
							</td>
							<td>
								<span><?= $value['reference_desc'] ?> </span>
							</td>
							<td>
								<span><?= $value['court_name'] ?> </span>
							</td>
							<td>
								<span><?= $value['judge_name'] ?></span>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		  </div>
      </div>
    </section>
</main><!-- End #main -->
<script>
    // Define a function to print the page
    function printPage() {
      window.print(); // This will trigger the browser's print dialog
    }

    // Attach the printPage function to the window.onload event
    window.onload = printPage;
    document.getElementById('sidebar').style.display = "none";
	document.getElementById('burgerMenu').style.display = "none";   
    document.getElementById("header").classList.add("d-none");
    document.getElementById("footer").classList.add("d-none");
</script>
<?= $this->endSection() ?>