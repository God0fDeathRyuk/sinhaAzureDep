<?= $this->extend("layouts/master") ?>



<!-- ============================ Main Content ============================== -->

<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>





<main id="main" class="main">	

	

    <div class="pagetitle">

      <h1>Matter Type Master</h1>

    </div><!-- End Page Title -->



    <section class="section dashboard">

    <div id="Initials" class="frms-sec mt-2 bg-white">

                            <div class="pagetitle py-3 px-3 mb-0 pb-0">

                            </div><!-- End Page Title -->

                        <form  action="/sinhaco/master/mas-matter-type/Edit" method="post">

                            <div class="col-md-12 mt-0">

                                <div class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2">



                                    <table class="table border-0" id="matterTypeDes">

                                        <thead>

                                            <tr class="fs-14">

                                                <th class="border">

                                                    <span>Matter Type Description</span>

                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                        <input type="hidden" name="org_matter_type_count" id="org_matter_type_count" value="<?php echo count($data); ?>">

                                            <input type="hidden" name="matter_type_count" id="matter_type_count" value="<?php echo count($data)+1; ?>">

                                        <?php foreach ($data as $key=> $value) { $key++;?>

                                            <tr class="fs-14 border-0" id="rowID<?php echo $key;?>">

                                                <td class="border">

                                                    <span><input type="text" class="form-control"

                                                            name="matter_type_desc<?php echo $key; ?>"

                                                            value="<?= $value['matter_type_desc'];?>"  /></span>

                                                            <input type="hidden" class="form-control"

                                                            name="matter_type_code<?php echo $key; ?>"

                                                            value="<?= $value['matter_type_code'];?>" readonly />

                                                </td>

                                            </tr>

                                            <?php } ?>

                                            <tr class="fs-14 border-0" id="rowID<?php echo count($data)+1;?>">

                                                <td class="border">

                                                    <span><input type="text" class="form-control"

                                                            name="matter_type_desc<?php echo count($data)+1; ?>"

                                                            value=""  /></span>

                                                            <input type="hidden" class="form-control"

                                                            name="matter_type_code<?php echo count($data)+1; ?>"

                                                            value="" readonly />

                                                            

                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                                <div class="d-inline-block w-100 bg-white p-3">

                                <a class="btn btn-primary cstmBtn mt-3 ms-2"

                                onclick="related_matters('matterTypeAdd')">Add</a>

                                        <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2">Save</button>

                                        <!-- <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a> -->

                                </div>

                            </div>

                         </form>

                        </div>

    </section>



  </main><!-- End #main -->



<?= $this->endSection() ?>