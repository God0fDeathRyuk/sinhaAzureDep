<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">
<div class="pagetitle col-md-12 float-start border-bottom pb-1">
  <h1>Case Alert </h1>
  <?php if(isset($msg)) { ?>
  <div id="alertMsg">
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </symbol>  
        </svg>
    <div class="alert alert-success d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
    <div>
        <?= "{$msg['msg']}  for <strong> Serial No. {$msg['id']} </strong>" ?>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php } ?>
</div>

</div>
<!-- End Page Title -->

<section class="section dashboard d-inline-block w-100">
    <form action="" method="post">
        <div class="row">
            <div class="inpt-grp col-md-3 pe-0 position-relative">
                <label class="d-block w-100 mb-2">Branch</label>
                <select class="form-select cstm-inpt">
                    <?php foreach($data['branches'] as $branch) { ?>
                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
                </select>
            </div> 
             <div class="inpt-grp col-md-3 pe-0 position-relative">
                <label class="d-block w-100 mb-2">Serial No <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control cstm-inpt" id="SerialNo" onchange="fetchData(this, 'serial_no', ['matterCode', 'matterDesc'], ['matter_code', 'matter_desc'], 'serial_no')" name="serial_no" required />
                <i class="fa-solid fa-eye inpt-vw" onclick="showData('serial_no', '<?= $displayId['casesrl_help_id'] ?>', 'SerialNo', ['matterCode', 'matterDesc'], ['matter_code', 'matter_desc'], 'serial_no')" data-toggle="modal" data-target="#lookup"></i>
            </div>
            <div class="inpt-grp col-md-3 pe-0">
                <label class="d-block w-100 mb-2">Case Alert</label>
                <select class="form-select cstm-inpt" name="alertType">
                    <option value="Yes">Display</option>
                    <option value="No">Close</option>
                </select>
            </div>
            <div class="inpt-grp col-md-3 pe-0">
                <label class="d-block w-100 mb-2">Matter Code</label>
                <input type="text" class="form-control cstm-inpt" id="matterCode" readonly disabled />
            </div>
            <div class="inpt-grp col-md-12 pe-0 mt-3">
                <label class="d-block w-100 mb-2">Matter Description</label>
                <textarea name="" rows="1" class="form-control cstm-inpt" id="matterDesc" readonly disabled></textarea>
            </div>
        </div>
        <div class="w-100 float-start text-start mt-4 top-btn-fld">
            <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2">Proceed</button>
            <!-- <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('edit')">Edit</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('view')">View</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('delete')">Delete</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('copy')">Copy</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('letter')">Letter</button> -->
            <button type="reset" class="btn btn-primary cstmBtn mt-0">Reset</button>
            <!-- <button type="button" class="btn btn-primary cstmBtn mt-0 d-none">Exit</button> -->
        </div>
    </form>
</section>

</main><!-- End #main -->

<?= $this->endSection() ?>