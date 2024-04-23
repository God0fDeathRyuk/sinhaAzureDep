<?= $this->extend("layouts/master") ?>



<!-- ============================ Main Content ============================== -->

<?= $this->section("body-content") ?>



<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">

    <?php if (session()->getFlashdata('message') !== NULL): ?>

        <div id="alertMsg">

            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">

                <div> <b>
                        <?= session()->getFlashdata('message') ?>
                    </b> </div>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

            </div>

        <?php endif; ?>
        <div class="pagetitle">

            <h1>Associate master [
                <?php echo strtoupper($option); ?>]
            </h1>

        </div><!-- End Page Title -->
        <form
            action="<?php if ($option == 'Add') {
                echo "/sinhaco/master/associate-list-add?user_option=Add";
            } if ($option == 'Edit') {
                echo '/sinhaco/master/associate-list-add?user_option=Edit';
            }
            if ($option == 'Delete') {
                echo '/sinhaco/master/associate-list-add?user_option=Delete';
            } ?>"
            method="post" id="associateListAdd">

            <section class="section dashboard">

                <div class="row">

                    <div class="col-md-12 mt-2">

                        <div class="frms-sec d-inline-block w-100 bg-white p-3">

                            <div class="frms-sec-insde float-start col-md-2 px-2 mb-3 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Code</label>

                                <input type="text" class="form-control" placeholder="Code" name="associate_code"
                                    id="associateCode" value="<?= ($option != 'Add') ? $data1['associate_code'] : '' ?>"
                                    readonly />

                                <input type="hidden" name="maxCode" id="maxCode" value="">

                            </div>

                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Name<strong class="text-danger">*</strong></label>

                                <input type="text" class="form-control" placeholder="Name" name="associate_name"
                                    id="associateName" required
                                    value="<?= ($option != 'Add') ? $data1['associate_name'] : '' ?>" <?php if ($option == 'Add') { ?>
                                        onblur="duplicate_code_check(this.value,'<?php echo $option; ?>','getAcode')<?php } ?>"
                                    onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?> required/>

                            </div>

                            <div class="frms-sec-insde d-block float-start col-md-<?php if($option == 'Add'){echo '8'; }else{ echo '6'; }?> px-2 mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Address<strong class="text-danger">*</strong></label>

                                <textarea type="text" class="form-control" rows="3" cols="" placeholder="Address"
                                    name="address_line_1" id="addressLine_1" required
                                    onkeyup="javascript:(this.value=this.value.toUpperCase())" <?php echo $redokadd;?>><?= ($option != 'Add') ? $data1['address_line_1'] : '' ?></textarea>

                            </div>

                            <div class="col-md-5 float-start px-2 position-relative mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">City / Pin <strong class="text-danger">*</strong></label>

                                <input type="text" class="form-control w-63 float-start" placeholder="City" name="city"
                                    id="City" value="<?= ($option != 'Add') ? $data1['city'] : '' ?>" required <?php echo $redokadd;?>/>

                                <input type="text" class="form-control w-35 ms-2 float-start" placeholder="Pin"
                                    name="pin_code" id="pinCode"
                                    value="<?= ($option != 'Add') ? $data1['pin_code'] : '' ?>" <?php echo $redokadd;?> required/>

                            </div>



                            <div class="col-md-3 float-start px-2 position-relative mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Associate Type <strong class="text-danger">*</strong></label>

                                <select class="form-select" name="associate_type" id="associateType"
                                    onChange="chkAssociateVal()" required <?php echo $disview;?>>

                                    <option value="">-- None --</option>

                                    <?php foreach ($data as $associate) { ?>

                                        <option value="<?= ($option != '') ? $associate['code_code'] : '' ?>"
                                            <?= ($option != 'Add') ? ($data1['associate_type'] == $associate['code_code']) ? 'selected' : '' : '' ?>>
                                            <?= $associate['code_desc'] ?>
                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="col-md-4 float-start px-2 mb-3 position-relative">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Councel Type<strong class="text-danger">*</strong></label>

                                <input type="text" class="form-control" name="counsel_type_desc" id="counselType_desc"
                                    placeholder="Councel Type"
                                    onchange="fetchData(this, 'counsel_type', ['counselType_desc', 'counseType'], ['code_desc', 'code_code'], 'counsel_type')"
                                    required value="<?= ($option != 'Add') ? $data1['code_desc'] : '' ?>" <?php echo $redokadd;?>/>

                                <input type="hidden" class="form-control" name="counsel_type" id="counseType"
                                    placeholder="Councel Type"
                                    value="<?= ($option != 'Add') ? $data1['counsel_type'] : '' ?>" />

                                <i class="fa fa-binoculars icn-vw"
                                    onclick="showData('code_code', '<?= '4082' ?>', 'counseType', [ 'counselType_desc','counseType'], ['code_desc','code_code'], 'counsel_type')"
                                    data-toggle="modal" data-target="#lookup"></i>



                            </div>

                            <div class="col-md-4 float-start px-2 mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Clerk Name<strong class="text-danger">*</strong></label>

                                <select class="form-select" name="link_associate_code" id="LinkAssociateCode" <?php echo $disview;?>>

                                    <option value="">-- None --</option>



                                </select>

                                <input type="hidden" name="old_link_associate_code" value="" readonly>

                            </div>

                            <div class="col-md-4 float-start px-2 mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Bearer Fee</label>

                                <input type="number" class="form-control" name="bearer_fee" id="bearerFee"
                                    placeholder="Bearer Fee" value="<?= ($option != 'Add') ? $data1['bearer_fee'] : '' ?>"
                                    onBlur="chkNumber(this)" <?php echo $redokadd;?>/>

                            </div>

                            <div class="col-md-4 float-start px-2 mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Phone#</label>

                                <input type="tel"  pattern="[789][0-9]{9}" title="[789][0-9]{9}" class="form-control" name="phone_no" id="phoneNo" placeholder="Phone"
                                    value="<?= ($option != 'Add') ? $data1['phone_no'] : '' ?>" <?php echo $redokadd;?>/>

                            </div>

                            <div class="col-md-4 float-start px-2 mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile#</label>

                                <input type="tel"  pattern="[789][0-9]{9}" title="[789][0-9]{9}" class="form-control" name="mobile_no" id="mobileNo"
                                    placeholder="Mobile" value="<?= ($option != 'Add') ? $data1['mobile_no'] : '' ?>" <?php echo $redokadd;?>/>

                            </div>

                            <div class="col-md-4 float-start px-2 mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">PAN#</label>

                                <input type="text" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="[A-Z]{5}[0-9]{4}[A-Z]{1}" class="form-control" name="pan_no" id="panNo" placeholder="PAN"
                                    value="<?= ($option != 'Add') ? $data1['pan_no'] : '' ?>" <?php echo $redokadd;?>/>

                            </div>

                            <div class="col-md-4 float-start px-2 mb-3">

                                <label class="d-inline-block w-100 mb-2 lbl-mn">Status<strong class="text-danger">*</strong></label>

                                <select class="form-select" name="status_code" id="statusCode" <?php echo $disview;?> required>
                                    <option value="">Select</option>
                                    <option value="Active" <?= ($option != 'Add') ? ($data1['statusCode'] == 'Active') ? 'selected' : '' : '' ?>>Active</option>
                                    <option value="Old" <?= ($option != 'Add') ? ($data1['statusCode'] == 'Old') ? 'selected' : '' : '' ?>>Old</option>

                                </select>

                            </div>



                        </div>
                        <input type="hidden" class="form-control" name="display_id" id="display_id"
                            value="<?php echo $_REQUEST['display_id']; ?>" />
                        <input type="hidden" class="form-control" name="menu_id" id="menu_id"
                            value="<?php echo $_REQUEST['menu_id']; ?>" />

                        <?php $session = session();

                        $sessionName = $session->userId; ?>

                        <input type="hidden" name="last_update_id" value="<?= ($option != 'Add') ? $sessionName : '' ?>">

                        <input type="hidden" name="last_update_dt" value="<?= ($option != 'Add') ? date('Y-m-d') : '' ?>">

                        <input type="hidden" name="prepared_by"
                            value="<?= ($option == 'Add') ? $sessionName : $data1['prepared_by'] ?>">

                        <input type="hidden" name="prepared_on"
                            value="<?= ($option == 'Add') ? date('Y-m-d') : $data1['prepared_on'] ?>">
                        <input type="hidden" name="finsub" id="finsub" value="fsub">
                        <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" <?php echo $disview;?>>Save</button>
                        <?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
                        <?php } ?>                       
                        <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>

                    </div>

                </div>

            </section>

        </form>

</main><!-- End #main -->



<?= $this->endSection() ?>