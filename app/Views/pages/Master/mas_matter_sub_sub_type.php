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

      <h1 class="col-md-8 float-start">Sub-Sub Matter Type Master [Select]</h1>
      <div class="col-md-4 float-end text-end mb-2">
					
					<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2">Back</a>
	  </div>
    </div><!-- End Page Title -->

    <form  action="/sinhaco/master/mas-matter-sub-sub-type?user_option=Edit" method="post">

    <section class="section dashboard">

      <div class="row">

		<div class="col-md-3 mt-2">

			<div class="search">

				<label class="d-block w-100 mb-2">Matter Type</label>

				<input class="form-control w-100 d-block float-start" type="search" name="matter_type_code" id="matter_type_code" value="<?= ($option!='Edit')?$data['matter_type_code']:'' ?>" readonly <?php echo $redokadd;?>/>

			</div>

		</div>

		<div class="col-md-9 mt-2">

			<div class="search">

				<label class="d-block w-100 mb-2">Matter Description</label>

				<input class="form-control w-100 d-block float-start" type="search" name="" id="" value="<?= ($option!='Edit')?$data['matter_type_desc']:'' ?>" readonly <?php echo $redokadd;?>/>

			</div>

		</div>

		<div class="col-md-3 mt-3">

			<div class="search">

				<label class="d-block w-100 mb-2">Sub Matter Type</label>

				<input class="form-control w-100 d-block float-start" type="search" name="matter_sub_type_code" id="matter_sub_type_code" value="<?= ($option!='Edit')?$data['matter_sub_type_code']:'' ?>" readonly <?php echo $redokadd;?>/>

			</div>

		</div>

		<div class="col-md-9 mt-3">

			<div class="search">

				<label class="d-block w-100 mb-2">Sub Matter Description</label>

				<input class="form-control w-100 d-block float-start" type="search" name="" id="" value="<?= ($option!='Edit')?$data['matter_sub_type_desc']:'' ?>" readonly <?php echo $redokadd;?>/>

			</div>

		</div>

        <div class="col-md-12 mt-0">

                <div class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2">



                    <table class="table border-0" id="matterSubSubTypeDes">

                        <thead>

                            <tr class="fs-14">

                                <th class="border">

                                    <span>Sub-Sub matter Type Description</span>

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <input type="hidden" name="org_matter_sub_sub_type_count" id="org_matter_sub_sub_type_count"

                                value="<?php echo count($data1); ?>">

                            <input type="hidden" name="matter_sub_sub_type_count" id="matter_sub_sub_type_count"

                                value="<?php echo count($data1)+1; ?>">

                            <?php foreach ($data1 as $key=> $value) { $key++;?>

                            <tr class="fs-14 border-0" id="rowID<?php echo $key;?>">

                                <td class="border">

                                    <span><input type="text" class="form-control"

                                            name="matter_sub_sub_type_desc<?php echo $key; ?>"

                                            value="<?= $value['matter_sub_sub_type_desc'];?>" <?php echo $redokadd;?>/></span>

                                    <input type="hidden" class="form-control"

                                        name="matter_sub_sub_type_code<?php echo $key; ?>"

                                        value="<?= $value['matter_sub_sub_type_code'];?>" readonly />

                                </td>

                            </tr>

                            <?php } ?>

                            <tr class="fs-14 border-0" id="rowID<?php echo count($data1)+1;?>">

                                <td class="border">

                                    <span><input type="text" class="form-control"

                                            name="matter_sub_sub_type_desc<?php echo count($data1)+1;?>" value="" <?php echo $redokadd;?>/></span>

                                    <input type="hidden" class="form-control"

                                        name="matter_sub_sub_type_code<?php echo count($data1)+1;?>" value="" readonly <?php echo $redokadd;?>/>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>
                <input type="hidden" name="finsub" id="finsub" value="fsub">
                <div class="d-inline-block w-100 bg-white p-3">

                    <a class="btn btn-primary cstmBtn mt-3 ms-2 <?php if($option=='View'){ echo 'd-none';} ?>" onclick="related_matters('matterSubSubTypeAdd')">Add</a>

                    <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" <?php echo $disview;?>>Save</button>

                    <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>

                </div>

      </div>

    </section>

    </form>

</main><!-- End #main -->



<?= $this->endSection() ?>