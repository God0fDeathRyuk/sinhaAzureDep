<?= $this->extend("layouts/master") ?>

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<main id="main" class="main">
    <?php $session = session();
  $accessPermission=explode(",", $session->Accpermission);
   if (session()->getFlashdata('message') !== NULL) : ?>
    <div id="alertMsg">
        <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif;?>
        <div class="pagetitle">
            <h1>Time Sheet Upload</h1>
        </div><!-- End Page Title -->
        
        
            <section class="section dashboard">
                <div class="row">
                <form action="" method="post">
                <div class="row">
                    <div class="col-md-3">
                        <label class="d-block w-100">From Date</label>
                        <input type="text" name="frmDt" id="frmDt" class="form-control txtFrom" onblur="make_date(this)"/>
                    </div>
                    <div class="col-md-3">
                        <label class="d-block w-100">To Date</label>
                        <input type="text" name="toDt" id="toDt" class="form-control txtTo" onblur="make_date(this)"/>
                    </div>
                    <div class="srchtop col-md-4 float-start mt-20">
                            <div class="input-group">
                            <div class="form-outline">
                                <input type="hidden" name="option" id="option" value="search">
                                <input type="search" name="query" id="form1" class="form-control" value="" placeholder="search here.." />
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            </div>
                        
                    </div>
                </div>
                    </form>
                    <div class="col-md-12 mt-2">
                        <div class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 ">
                            <table class="table border-0" id="uploadTable">
                                <tbody>
                                    <tr class="fs-14">
                                        <th class="border">
                                            <span>Description</span>
                                        </th>
                                        <th class="border">
                                            <span>File Name</span>
                                        </th>
                                        <th class="border">
                                            <span>Uploaded On</span>
                                        </th>
                                        <th class="border">
                                            <span>Uploaded By</span>
                                        </th>
                                        <th class="border">
                                            <span>Action</span>
                                        </th>
                                    </tr>
                                    <?php foreach ($data as $key => $value) {?>
                                    <tr class="fs-14 border-0" id="rowId">
                                        <td class="border"><?= strtoupper($value['description']) ?></td>
                                        <td class="border"><?= $value['file_name_original'] ?></td>
                                        <td class="border"><?= date('d-m-Y',strtotime($value['uploaded_on'])) ?></td>
                                        <td class="border"><?= $value['uploaded_by'] ?></td>
                                        <td class="border"><a href="<?= base_url('download_timesheet').'?id='.$value['serial_no']; ?>" title="Click To Download">Download</a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
            </section>
        
</main><!-- End #main -->
<?= $this->endSection() ?>