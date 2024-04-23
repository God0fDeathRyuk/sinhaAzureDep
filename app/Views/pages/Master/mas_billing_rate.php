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
            <h1>Billing Rate Master [<?php echo strtoupper($option)?>]</h1>
        </div><!-- End Page Title -->
        <form
            action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-billing-rate?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-billing-rate?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-billing-rate?user_option=Delete';}?>"
            method="post">
            <section class="section dashboard">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-4 float-start px-2 mb-3 mt-3 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Serial No.</label>
                            <input type="text" class="form-control" name="sl_no" id="sl_no"
                                value="<?= ($option!='Add')?$data['serial_no']: '' ?>" readonly />
                        </div>
                            <div class="col-md-4 float-start px-2 mb-3 mt-3 position-relative">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Counsel <strong
                                        class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="counsel_name" id="counselName"
                                    placeholder="Counsel Name"
                                    onchange="fetchData(this, 'associate_code', ['counselCode', 'counselName'], ['associate_code', 'associate_name'], 'counsel_code')"
                                    required value="<?= ($option!='Add') ? $data['associate_name'] : '' ?>" readonly/>
                                <input type="hidden" class="form-control" name="counsel_code" id="counselCode"
                                    placeholder="Branch" value="<?= ($option!='Add') ? $data['counsel_code'] : '' ?>" />
                                <i class="fa fa-binoculars icn-vw"
                                    onclick="showData('associate_code', '<?= '4011' ?>', 'counselCode', [ 'counselCode','counselName'], ['associate_code','associate_name'], 'counsel_code')"
                                    data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-4 float-start px-2 mb-3 mt-3 position-relative">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Client <strong
                                        class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="client_name" id="clientName"
                                    placeholder="Client Name"
                                    onchange="fetchData(this, 'client_code', ['clientCode', 'clientName'], ['client_code', 'client_name'], 'client_code')"
                                    required value="<?= ($option!='Add') ? $data['client_name'] : '' ?>" readonly/>
                                <input type="hidden" class="form-control" name="client_code" id="clientCode"
                                    placeholder="Branch" value="<?= ($option!='Add') ? $data['client_code'] : '' ?>" />
                                <i class="fa fa-binoculars icn-vw"
                                    onclick="showData('client_code', '<?= '4072' ?>', 'clientCode', [ 'clientCode','clientName'], ['client_code','client_name'], 'client_code')"
                                    data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-4 float-start px-2 mb-3 mt-3 position-relative">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Matter <strong
                                        class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="matter_name" id="matterName"
                                    placeholder="Matter Name"
                                    onchange="fetchData(this, 'matter_code', ['matterCode', 'matterName'], ['matter_code', 'mat_desc'], 'matter_code')"
                                    required value="<?= ($option!='Add') ? $data['matter_desc1'].$data['matter_desc2'] : '' ?>" readonly/>
                                <input type="hidden" class="form-control" name="matter_code" id="matterCode"
                                    placeholder="Branch" value="<?= ($option!='Add') ? $data['matter_code'] : '' ?>" />
                                <i class="fa fa-binoculars icn-vw"
                                    onclick="showData('matter_code', '<?= '4202' ?>', 'matterCode', [ 'matterCode','matterName'], ['matter_code','mat_desc'], 'matter_code')"
                                    data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-4 float-start px-2 mb-3 mt-3 position-relative">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Activity <strong
                                        class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="act_name" id="actName"
                                    placeholder="Activity"
                                    onchange="fetchData(this, 'code_code', ['activityCode', 'actName'], ['code_code', 'code_desc'], 'activity_code')"
                                    required value="<?= ($option!='Add') ? $data['code_desc'] : '' ?>" readonly/>
                                <input type="hidden" class="form-control" name="activity_code" id="activityCode"
                                    placeholder="Branch" value="<?= ($option!='Add') ? $data['activity_code'] : '' ?>" />
                                <i class="fa fa-binoculars icn-vw"
                                    onclick="showData('code_code', '<?= '4083' ?>', 'activityCode', [ 'activityCode','actName'], ['code_code','code_desc'], 'activity_code')"
                                    data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-4 float-start px-2 mb-3 mt-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Rate</label>
                            <input type="text" class="form-control" placeholder="Rate" name="rate" id="rate"
                                value="<?= ($option!='Add')?$data['rate']: '' ?>" <?php echo $redokadd;?>/>
                        </div>
                        </div>
        <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
<input type="hidden" name="finsub" id="finsub" value="fsub">
                        <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" onclick="return billing_master_check()" <?php echo $disview;?>>Save</button>
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