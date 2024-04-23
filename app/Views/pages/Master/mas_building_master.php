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
            <h1>Company Building Master [<?php echo strtoupper($option) ?>]</h1>
        </div><!-- End Page Title -->
        <form
            action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-building-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-building-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-building-master?user_option=Delete';}?>"
            method="post">
            <section class="section dashboard">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn"> Floor <strong
                                        class="text-danger">*</strong></label>
                                <input type="hidden" name="building_code" id="building_code"
                                    value="<?= ($option!='Add') ? $data['building_code'] : '' ?>">
                                <input type="text" class="form-control" placeholder="Floor" name="floor" id="floor"
                                    value="<?= ($option!='Add')?$data['floor']: '' ?>" required
                                    <?php echo $redokadd;?> />
                            </div>
                            <div class="d-inline-block w-100">
                                <div class="col-md-6 float-start px-2 position-relative mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn">Address1 <strong
                                            class="text-danger">*</strong></label>
                                    <textarea rows="4" class="form-control w-100" placeholder="Address1"
                                        name="address_line_1" id="address_line_1"
                                        onkeyup="javascript:(this.value=this.value.toUpperCase())"
                                        <?php echo $redokadd;?>><?= ($option!='Add') ?$data['address_line_1']: '' ?></textarea>
                                </div>
                                <div class="col-md-6 float-start px-2 position-relative mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn">Address2</label>
                                    <textarea rows="4" class="form-control w-100" placeholder="Address2"
                                        name="address_line_2" id="address_line_2"
                                        onkeyup="javascript:(this.value=this.value.toUpperCase())"
                                        <?php echo $redokadd;?>><?= ($option!='Add') ?$data['address_line_2']: '' ?></textarea>
                                </div>
                                <div class="col-md-6 float-start px-2 position-relative mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn">Address3</label>
                                    <textarea rows="4" class="form-control w-100" placeholder="Address3"
                                        name="address_line_3" id="address_line_3"
                                        onkeyup="javascript:(this.value=this.value.toUpperCase())"
                                        <?php echo $redokadd;?>><?= ($option!='Add') ?$data['address_line_3']: '' ?></textarea>
                                </div>
                                <div class="col-md-6 float-start px-2 position-relative mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn">Address4</label>
                                    <textarea rows="4" class="form-control w-100" placeholder="Address4"
                                        name="address_line_4" id="address_line_4"
                                        onkeyup="javascript:(this.value=this.value.toUpperCase())"
                                        <?php echo $redokadd;?>><?= ($option!='Add') ?$data['address_line_4']: '' ?></textarea>
                                </div>
                                <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn"> City <strong
                                            class="text-danger">*</strong></label>
                                    <input type="text" class="form-control" placeholder="City" name="city" id="city"
                                        value="<?= ($option!='Add')?$data['city']: '' ?>" required
                                        onkeyup="javascript:(this.value=this.value.toUpperCase())"
                                        <?php echo $redokadd;?> />
                                </div>
                                <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn"> Pin </label>
                                    <input type="text" class="form-control" placeholder="Pin" name="pin_code"
                                        id="pin_code" value="<?= ($option!='Add')?$data['pin_code']: '' ?>"
                                        <?php echo $redokadd;?> />
                                    <input type="hidden" name="status_date" id="status_date"
                                        value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="display_id" id="display_id"
                                value="<?php echo  $_REQUEST['display_id']; ?>" />
                            <input type="hidden" class="form-control" name="menu_id" id="menu_id"
                                value="<?php echo  $_REQUEST['menu_id']; ?>" />
                            <input type="hidden" name="finsub" id="finsub" value="fsub">
                            <div class="d-inline-block w-100">
                                <button type="submit" id="submit_buildingbtn" class="btn btn-primary cstmBtn mt-3 ms-2"
                                    onClick="return building_master_check()" <?php echo $disview;?>>Save</button>
                                <?php if($option=="Delete"){?>
                                <button type="submit" onclick="return confirm('Are you sure you want to delete.')"
                                    class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
                                <?php } ?>
                                <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
                            </div>

                        </div>

                    </div>
                </div>
            </section>
        </form>
</main><!-- End #main -->
<?= $this->endSection() ?>