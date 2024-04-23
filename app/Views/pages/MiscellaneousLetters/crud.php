<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<main id="main" class="main">

<?php if (session()->getFlashdata('message') !== NULL) : ?>
    <div id="alertMsg">
        <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

	<div class="pagetitle w-100 float-start pb-1">
		<h1 class="col-md-8 float-start"> Miscellaneous Letter to Party [<?= ucfirst($user_option) ?>] </h1> 
	</div>

	<form action="" method="post">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Latter No</label>
                <input type="text" class="form-control" id="ltrNO" name="letter_no" value="<?= ($user_option == "Copy") ? '' : $letter['letter_no'] ?>" readonly/>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Latter Date</label>
                <input type="text" class="form-control datepicker" id="ltrDte" placeholder="dd-mm-yyyy" name="letter_date" value="<?= ($user_option == "Add") ? date('d-m-Y') : $letter['letter_date'] ?>" <?= $redv ?> onBlur="chkActivityDate(this)"/>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Mode of Sending</label>
                <input type="text" class="form-control" id="mdeSend" name="send_mode" value="<?= $letter['send_mode'] ?>" <?= $redv ?>/>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Remark</label>
                <textarea class="form-control" name="remarks" <?= $redv ?>><?= $letter['remarks'] ?></textarea>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Address <strong class="text-danger">*</strong></label>
                <textarea class="form-control" name="letter_address" <?= $redv ?> required><?= $letter['letter_address'] ?></textarea>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Ref</label>
                <textarea class="form-control" name="letter_desc_ref" <?= $redv ?>><?= $letter['letter_desc_ref'] ?></textarea>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Re <strong class="text-danger">*</strong></label>
                <textarea class="form-control"  name="letter_desc" <?= $redv ?> required><?= $letter['letter_desc'] ?></textarea>
            </div>				
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Our Client</label>
                <textarea class="form-control"  name="letter_client" <?= $redv ?>><?= $letter['letter_client'] ?></textarea>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Your Client</label>
                <textarea class="form-control" name="your_client" <?= $redv ?>><?= $letter['your_client'] ?></textarea>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-1">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
                <textarea class="form-control" rows="1" name="letter_desc_sub" <?= $redv ?>><?= $letter['letter_desc_sub'] ?></textarea>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-2">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Body of the letter</label>
                <textarea id="editor" name="letter_body" <?= $redv ?> required><?= $letter['letter_body'] ?></textarea>					
            </div>
            <input type="hidden" name="user_option" id="user_option" value="<?= $_REQUEST['user_option']; ?>">
            <input type="hidden" name="serial_no" id="serial_no" value="<?php if($_REQUEST['user_option']!=='Add'){ echo $_REQUEST['serial_no'];} ?>">
            <input type="hidden" name="finsub" id="finsub" value="fsub">
            <div class="col-md-12 d-inline-block">
            <?php  if($user_option != 'View') { ?>
				<button type="submit" class="btn btn-primary cstmBtn mt-3">Save</button>
            <?php } ?>
				<a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
            </div>
        </div>
    </form>
</main>
<?= $this->endSection() ?>