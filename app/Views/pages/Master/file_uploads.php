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
            <h1>File Upload</h1>
        </div><!-- End Page Title -->
        <form action="/sinhaco/master/file-uploads/edit" method="post" id="upload" enctype="multipart/form-data">
            <section class="section dashboard">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 ">
                            <input type="hidden" name="uploadCount" id="uploadCount"
                                value="<?= (count($data)>0)? count($data): '1' ?>">
                            <input type="hidden" name="emp_serial_no" id="emp_serial_no"
                                value="<?php echo $code;?>">
                            <table class="table border-0" id="uploadTable">
                                <tbody>
                                    <tr class="fs-14">
                                        <th class="border">
                                            <span></span>
                                        </th>
                                        <th class="border">
                                            <span>Description</span>
                                        </th>
                                        <th class="border">
                                            <span>File Name</span>
                                        </th>
                                    </tr>
                                    <?php $key2=0;if(count($data)>0){
                                        foreach ($data as $key => $value) { $key++;?>
                                    <tr class="fs-14 border-0" id="rowId<?= $key; ?>">
                                        <td class="border"> <input type="file" class="form-control"
                                                name="userfiles<?= $key; ?>" id="userfiles<?= $key; ?>">
                                                <input type="hidden" class="form-control"
                                                name="userfilesname<?= $key; ?>" id="userfilesname<?= $key; ?>" value="<?= $value['file_name_original'] ?>"></td>
                                        <td class="border"><textarea class="form-control" placeholder="Description"
                                                name="desc<? $key; ?>" id="desc<? $key; ?>"><?= $value['description'] ?></textarea> <input
                                                type="hidden" name="status_code" id="status_code" value="A"></td>
                                        <td class="border"><?= $value['file_name_original'] ?></td>
                                    </tr>
                                    <?php $key2=$key2+$key;} }else {  $key2=$key2+1;?>
                                    <tr class="fs-14 border-0" id="rowId<?= $key2; ?>">
                                        <td class="border"> <input type="file" class="form-control"
                                                name="userfiles<?= $key2; ?>" id="userfiles<?= $key2; ?>"></td>
                                        <td class="border"><textarea class="form-control" placeholder="Description"
                                                name="desc<?= $key2; ?>" id="desc<?= $key2; ?>"></textarea> <input
                                                type="hidden" name="status_code<?= $key2; ?>"
                                                id="status_code<?= $key2; ?>" value="A"></td>
                                    </tr>
<?php } ?>
                                </tbody>

                            </table>
                            <?php    $session = session();
                                     $sessionName=$session->userId;?>
                            <input type="hidden" class="form-control" name="uploaded_by" id="uploadedBy"
                                value="<?= $sessionName ?>" />
                            <input type="hidden" class="form-control" name="uploaded_on" id="uploadedOn"
                                value="<?= date('Y-m-d') ?>" />
                            <input type="hidden" class="form-control" name="uploaded_time" id="uploadedTime"
                                value="<?= date("h:i:s") ?>" />
                            <a class="btn btn-primary cstmBtn mt-3 ms-2" onclick="related_matters('addUpload')">Add</a>
                            <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2">Save</button>
                        </div>
                    </div>
            </section>
        </form>
</main><!-- End #main -->
<?= $this->endSection() ?>