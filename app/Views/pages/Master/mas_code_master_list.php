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

      <h1 class="col-md-8 float-start">Code Master </h1>	
	  </div>
    </div><!-- End Page Title -->

    <section class="section dashboard">

      <div class="row">

		<div class="col-md-12 mt-2">

			<div class="frms-sec d-inline-block w-100 bg-white p-3">

				<div class="d-inline-block w-100 btmDv" id="codeMasTab">

					<div class="col-md-12 float-start mb-3">

						<label class="d-inline-block w-100 mb-2 lbl-mn">Type <strong class="text-danger">*</strong></label>

						<input type="text" class="form-control w-24 float-start me-2" name="type_code" id="type_code" value="<?= ($option!='Add')?$data['type_code']:'' ?>" readonly/>

						<input type="text" class="form-control w-50 float-start" name="type_name" id="type_name" value="<?= ($option!='Add')?$data['type_desc']:'' ?>" readonly/>
						<form action="/sinhaco/master/mas-code-master-insert" method="post">
									<input type="hidden" name="type_code" id="type_code" value="<?= $_REQUEST['type_code'] ?>">
									<input type="hidden" name="option" id="option" value="Add">
									<input type="hidden" name="display_id" id="display_id" value="<?php echo $_REQUEST['display_id']; ?>">
									<input type="hidden" name="menu_id" id="menu_id" value="<?php echo $_REQUEST['menu_id']; ?>">
									<button type="submit" id="submit" class="btn btn-primary cstmBtn ms-3">ADD</button>
									<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2">Back</a>
								</form>

					</div>		

					

					<table class="table table-bordered">

						<tr class="fs-14">

							<th>&nbsp;</th>

							<th>&nbsp;</th>

							<th>Name</th>

							<th>Code</th>

						</tr>

                        <?php foreach ($data2 as $key => $value) {?>

						<tr class="fs-14">

							<td>
								<form action="/sinhaco/master/mas-code-master-insert" method="post">
									<input type="hidden" name="type_code" id="type_code" value="<?= $data['type_code'] ?>">
									<input type="hidden" name="type_desc" id="type_desc" value="<?= $data['type_desc'] ?>">
									<input type="hidden" name="code_code" id="code_code" value="<?= $value['code_code'] ?>">
									<input type="hidden" name="option" id="option" value="Edit">
									<input type="hidden" name="display_id" id="display_id" value="<?php echo $_REQUEST['display_id']; ?>">
									<input type="hidden" name="menu_id" id="menu_id" value="<?php echo $_REQUEST['menu_id']; ?>">
									<button type="submit" id="submit" class="fa-solid fa-pen-to-square edit"></button>
								</form>
							</td>

							<td>
								<form action="/sinhaco/master/mas-code-master-insert" method="post">
									<input type="hidden" name="type_code" id="type_code" value="<?= $data['type_code'] ?>">
									<input type="hidden" name="type_desc" id="type_desc" value="<?= $data['type_desc'] ?>">
									<input type="hidden" name="code_code" id="code_code" value="<?= $value['code_code'] ?>">
									<input type="hidden" name="option" id="option" value="View">
									<input type="hidden" name="display_id" id="display_id" value="<?php echo $_REQUEST['display_id']; ?>">
									<input type="hidden" name="menu_id" id="menu_id" value="<?php echo $_REQUEST['menu_id']; ?>">
									<button type="submit" id="submit" class="fa-solid fa-eye edit"></button>
								</form>
							<td>
							<form action="/sinhaco/master/mas-code-master-insert" method="post" class="d-inline">
									<input type="hidden" name="type_code" id="type_code" value="<?= $data['type_code'] ?>">
									<input type="hidden" name="type_desc" id="type_desc" value="<?= $data['type_desc'] ?>">
									<input type="hidden" name="code_code" id="code_code" value="<?= $value['code_code'] ?>">
									<input type="hidden" name="option" id="option" value="Delete">
									<input type="hidden" name="display_id" id="display_id" value="<?php echo $_REQUEST['display_id']; ?>">
									<input type="hidden" name="menu_id" id="menu_id" value="<?php echo $_REQUEST['menu_id']; ?>">
									<button type="submit" id="submit" class="fa-solid fa fa-trash edit"></button>
								</form>
							<td><?= strtoupper($value['code_desc']) ?></td>

							<td><?= strtoupper($value['code_code']) ?></td>

						</tr>

                        <?php } ?>

					</table>

					<div class="d-inline-block w-100">				

					<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>

					</div>

				</div>

			</div>

			

		</div>

		

      </div>

    </section>

</main><!-- End #main -->



<?= $this->endSection() ?>