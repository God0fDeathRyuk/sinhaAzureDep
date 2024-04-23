<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">
<div class="pagetitle col-md-12 float-start border-bottom pb-1">
  <h1>Case Status Open (Billed) </h1>
</div>
<!-- End Page -->

<section class="section dashboard d-inline-block w-100">
    <form action="" method="post">
        <div class="row">
            <div class="inpt-grp col-md-4 pe-0">
                <label class="d-block w-100 mb-2">Branch</label>
                <select class="form-select cstm-inpt">
                    <?php foreach($data['branches'] as $branch) { ?>
                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inpt-grp col-md-4 pe-0 position-relative">
                <label class="d-block w-100 mb-2">Serial No</label>
                <input type="text" class="form-control cstm-inpt" id="SerialNo" onchange="fetchData(this, ['matterCode', 'matterDesc'], ['matter_code', 'matter_desc'])" name="serial_no" />
                <i class="fa-solid fa-eye inpt-vw" onclick="showData('serial_no', '<?= $displayId['display_id'] ?>', 'SerialNo', ['matterCode', 'matterDesc'], ['matter_code', 'matter_desc'])" data-toggle="modal" data-target="#lookup"></i>
            </div>
            <div class="inpt-grp col-md-4 pe-0">
                <label class="d-block w-100 mb-2">Matter Code</label>
                <input type="text" class="form-control cstm-inpt" id="matterCode" readonly disabled />
            </div>
            <div class="inpt-grp col-md-12 pe-0 mt-3">
                <label class="d-block w-100 mb-2">Matter Description</label>
                <textarea name="" rows="1" class="form-control cstm-inpt" id="matterDesc" readonly disabled></textarea>
            </div>
        </div>
        <div class="w-100 float-start text-start mt-4 top-btn-fld">
            <!-- <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2">Proceed</button> -->
            <!-- <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('edit')">Edit</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('view')">View</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('delete')">Delete</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('copy')">Copy</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('letter')">Letter</button> -->
            <!-- <a href="/case/status/open/" class="btn btn-primary cstmBtn mt-0">Exit</a> -->
            <button type="reset" class="btn btn-primary cstmBtn mt-0 ">Exit</button>
        </div>
    </form>
</section>

</main>
<!-- End #main -->
<?= $this->endSection() ?>