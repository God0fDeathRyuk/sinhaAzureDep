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
    <h1>Excel Cause List</h1>
    </div><!-- End Page Title -->

    <form action="" method="post" name="f1">
        <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-48 float-start datepicker" name="start_date" value="<?= $start_date ?>" onBlur="make_date(this)">
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-48 float-start datepicker" name="end_date" value="<?= $end_date ?>" onBlur="make_date(this)" required>
                        </div>
                        
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>										
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" name="client_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name </label>
                            <input type="text" class="form-control w-100 float-start" id="clientName" oninput="this.value = this.value.toUpperCase()" name="client_name" readonly>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc'], ['matter_desc'], 'matter_code')" name="matter_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Desc </label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="matterDesc" name="matter_desc" readonly>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" name="court_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Name </label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="courtName" name="court_name" readonly>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Initial Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="initialCode" onchange="fetchData(this, 'initial_code', ['initialName'], ['initial_name'], 'initial_code')" name="initial_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('initial_code', '<?= $displayId['initial_help_id'] ?>', 'initialCode', ['initialName'], ['initial_name'], 'initial_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Initial Name </label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="initialName" name="initial_name" readonly>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="output_type" required >
                                <option value="Excel" >Download Excel</option>
                            </select>
                        </div>						

                        <button type="button" onclick="validateDataSubmit()" class="btn btn-primary cstmBtn mt-28 ms-2">Report</button>				
                        <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-28 ms-2">Reset</button>
                    </div>
                </div>
            </div>
        </section>
    </form>

</main><!-- End #main -->

<script>
    function validateDataSubmit() {
        let bcode      = document.f1.branch_code.value ;
        let psdt       = document.f1.start_date.value ; 
        let pedt       = document.f1.end_date.value ; 
        let clnt_code  = document.f1.client_code.value ; 
        let clnt_name  = document.f1.client_name.value.replace('&','_|_').replace('&','_|_').replace('&','_|_').replace('&','_|_') ; 
        clnt_name  = clnt_name.replace("'",'-|-').replace("'",'-|-').replace("'",'-|-').replace("'",'-|-');
        let matr_code  = document.f1.matter_code.value ; 
        let matr_name  = document.f1.matter_desc.value ; 
        let intl_code  = document.f1.initial_code.value ; 
        let intl_name  = document.f1.initial_name.value ; 
        let cort_code  = document.f1.court_code.value ; 
        let cort_name  = document.f1.court_name.value ; 
        let outp_type  = document.f1.output_type.value ;
        let psdtymd    = psdt.substr(6,4)+psdt.substr(3,2)+psdt.substr(0,2) ;  
        let pedtymd    = pedt.substr(6,4)+pedt.substr(3,2)+pedt.substr(0,2) ;  
         
        if (document.f1.start_date.value == '') {
            alert('Please enter Start Date ........');
            document.f1.start_date.focus() ;
            return false ;
        } else if (document.f1.client_code.value == '') {
            alert('Please select Client ........');
            document.f1.client_code.focus() ;
            return false ;
        } else if (document.f1.court_code.value == '') {
            alert('Please select Court ........');
            document.f1.court_code.focus() ;
            return false ;
        } else if (document.f1.end_date.value == '') {
            alert('Please enter End Date ........');
            document.f1.end_date.focus() ;
            return false ;
        } else if (pedtymd < psdtymd) {
            alert('End Date must be >= Start Date ........');
            document.f1.start_date.focus() ;
            return false ;
        } else {
            document.f1.submit();
        }
    }
</script>
<?= $this->endSection() ?>