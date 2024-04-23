<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">

    <?php if (session()->getFlashdata('message') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="pagetitle">
      <h1>Bill Realisation Summary</h1>
    </div><!-- End Page Title -->

    <form action="" method="post" id="billingRealisationCountSummary" name="billingRealisationCountSummary" onsubmit="setValue(event)">
        <section class="section dashboard">
        <div class="row">
            <div class="col-md-12 mt-2">
                <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                    <div class="col-md-4 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                        <select class="form-select" name="branch_code" required >
                            <?php foreach($data['branches'] as $branch) { ?>
                                <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-5 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                        <input type="text" class="form-control w-45 float-start datepicker" name="start_date" onBlur="make_date(this)">
                        <span class="w-2 float-start mx-1">--</span>
                        <input type="text" class="form-control w-45 float-start datepicker" name="end_date" value="<?= date('d-m-Y')?>" onBlur="make_date(this)" required>
                    </div>
                    <div class="d-inline-block w-100">
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Code  <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-100" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name </label>					
                            <input type="text" class="form-control w-100" id="clientName" name="client_name" readonly/>
                        </div>
                        
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Code</label>					
                            <input type="text" class="form-control w-100" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" size="05" maxlength="06" name="court_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>

                        <div class="col-md-9 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Name </label>					
                            <input type="text" class="form-control w-100" id="courtName" name="court_name" readonly/>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="output_type" required >
                                <option value="Excel">Download Excel</option>
                            </select>
                        </div>
                        
                        <div class="col-md-9 d-inline-block mt-20">
                            <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Proceed</button>				
                            <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Reset</button>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        </section>
    </form>

</main><!-- End #main -->

<script>
    function setValue(e) {
        e.preventDefault();
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.billingRealisationCountSummary.start_date.value.substring(6,10)+document.billingRealisationCountSummary.start_date.value.substring(3,5)+document.billingRealisationCountSummary.start_date.value.substring(0,2) > document.billingRealisationCountSummary.end_date.value.substring(6,10)+document.billingRealisationCountSummary.end_date.value.substring(3,5)+document.billingRealisationCountSummary.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'End Date must be greater than or equal to Start Date' }).then((result) => { setTimeout(() => {document.billingRealisationCountSummary.end_date.focus()}, 500) });
            return false;
        } else if (document.billingRealisationCountSummary.end_date.value.substring(6,10)+document.billingRealisationCountSummary.end_date.value.substring(3,5)+document.billingRealisationCountSummary.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'End Date must be less than or equal to Today !!!' }).then((result) => { setTimeout(() => {document.billingRealisationCountSummary.end_date.focus()}, 500) });
            return false;
        }
        
        document.billingRealisationCountSummary.submit();
    }
</script>
<?= $this->endSection() ?>