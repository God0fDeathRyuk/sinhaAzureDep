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

<div class="pagetitle">

      <h1>Code Master </h1>

    </div><!-- End Page Title -->

    <form action="/sinhaco/master/mas-code-master-list" method="post" >

    <section class="section dashboard">

      <div class="row">

		<div class="col-md-12 mt-2">

			<div class="frms-sec d-inline-block w-100 bg-white p-3">

				<div class="d-inline-block w-100 topDv" id="typeCodeTab">

					<div class="col-md-4 float-start px-2 mb-3 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">

						<label class="d-inline-block w-100 mb-2 lbl-mn">Type Code <strong class="text-danger">*</strong></label>

						<select class="form-select" name="type_code" id="type_code" >

							<option value="">--- Select ---</option>

                            <?php foreach ($data as $value) {?>

                            <option value="<?= $value['type_code'] ?>"><?= $value['type_desc'] ?></option>

                            <?php } ?>

						</select>

					</div>		

					
					<input type="hidden" class="form-control" name="display_id" id="display_id"
                            value="<?php echo $_REQUEST['display_id']; ?>" />
                        <input type="hidden" class="form-control" name="menu_id" id="menu_id"
                            value="<?php echo $_REQUEST['menu_id']; ?>" />
					<div class="d-inline-block w-100 mt-3">

                    

                    

						<button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Proceed</button>				

						<!-- <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</a> -->

					</div>

				</div>

			</div>

			

		</div>

		

      </div>

    </section>

</form>

</main><!-- End #main -->



<?= $this->endSection() ?>