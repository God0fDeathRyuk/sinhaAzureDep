<?php
// echo "<pre>"; print_r($acknowledgement); die;
?>

<?= $this->extend("layouts/master") ?>
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

  <div class="pagetitle col-md-5 float-start">
    <h1> TDS Acknowledgement No (<?= $user_option ?>)</h1>
  </div>

  <section class="section dashboard d-inline-block w-100">
    <div class="row">
      <div class="col-md-12 mt-2">
        <form action="" method="post" name="tdsAcknowledgementForm" onsubmit="return tdsAcknowledgementSubmit()">
          <div class="frms-sec d-inline-block w-100 bg-white p-3">

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
              <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
              <?php if ($user_option == 'View') { ?> <select class="form-select cstm-inpt" name="branch_code" disabled="true">
              <?php } else { ?> <select class="form-select cstm-inpt" name="branch_code"> <?php } ?>
              <?php foreach ($data['branches'] as $branch) { ?>
                  <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
              <?php } ?> </select>
            </div>

            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
              <label class="d-inline-block w-100 mb-2 lbl-mn">Financial Year <strong class="text-danger">*</strong></label>
              <?php if ($user_option == 'View') { ?> <select class="form-select cstm-inpt" name="fin_year" disabled="true">
                  <option value="">--Select--</option>
              <?php } else { ?> <select class="form-select cstm-inpt" name="fin_year">
                    <option value="">--Select--</option>
              <?php } foreach ($acknowledgement['finyr_qry'] as $branch) { ?>
                  <option value="<?= $branch['fin_year'] ?>" <?= isset($acknowledgement['tdsretn_qry']) ? ($acknowledgement['tdsretn_qry']['fin_year'] == $branch['fin_year']) ? 'selected' : '' : '' ?>><?= $branch['fin_year'] ?></option>
              <?php } ?> </select>
            </div>

            <div class="col-md-8 float-start px-2 position-relative mb-3">
              <label class="d-inline-block w-100 mb-2 lbl-mn">Quarter No <strong class="text-danger">*</strong></label>
              <?php if ($user_option == 'View') { ?> <select class="form-select frm_Slct" name="quarter_no" disabled="true" onchange="calcQtrDate()">
              <?php } else { ?> <select class="form-select frm_Slct" name="quarter_no" onchange="calcQtrDate()">
              <?php } ?> <option value="">--Select--</option>
                <option value="1" <?= isset($acknowledgement['tdsretn_qry']) ? ($acknowledgement['tdsretn_qry']['quarter_no'] == '1') ? 'selected' : '' : '' ?>>First</option>
                <option value="2" <?= isset($acknowledgement['tdsretn_qry']) ? ($acknowledgement['tdsretn_qry']['quarter_no'] == '2') ? 'selected' : '' : '' ?>>Second</option>
                <option value="3" <?= isset($acknowledgement['tdsretn_qry']) ? ($acknowledgement['tdsretn_qry']['quarter_no'] == '3') ? 'selected' : '' : '' ?>>Third</option>
                <option value="4" <?= isset($acknowledgement['tdsretn_qry']) ? ($acknowledgement['tdsretn_qry']['quarter_no'] == '4') ? 'selected' : '' : '' ?>>Fourth</option>
              </select>
              <input type="text" class="form-control frm_inpt me-3 w-27" size="08" maxlength="10" name="start_date" value="<?= isset($acknowledgement['tdsretn_qry']) ? date_conv($acknowledgement['tdsretn_qry']['start_date']) : '' ?>" readonly>
              <div class="dsh">-</div>
              <input type="text" class="form-control frm_inpt ms-3 w-27" size="08" maxlength="10" name="end_date" value="<?= isset($acknowledgement['tdsretn_qry']) ? date_conv($acknowledgement['tdsretn_qry']['end_date']) : '' ?>" readonly>
            </div>

            <div class="col-md-4 float-start px-2 position-relative mb-3">
              <label class="d-inline-block w-100 mb-2 lbl-mn">Return No <strong class="text-danger">*</strong></label>
              <?php if ($user_option == 'View') { ?>
                <input type="text" class="form-control" name="tds_return_no" value="<?= isset($acknowledgement['tdsretn_qry']) ? $acknowledgement['tdsretn_qry']['tds_return_no'] : '' ?>" readonly>
              <?php } else { ?>
                <input type="text" class="form-control" name="tds_return_no" value="<?= isset($acknowledgement['tdsretn_qry']) ? $acknowledgement['tdsretn_qry']['tds_return_no'] : '' ?>">
              <?php } ?>
            </div>
          </div>
          <input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo $_REQUEST['display_id']; ?>" />
          <input type="hidden" class="form-control" name="menu_id" id="menu_id"  value="<?php echo $_REQUEST['menu_id']; ?>" />
          <input type="hidden" class="form-control" name="user_option" id="user_option"  value="<?php echo $_REQUEST['user_option']; ?>" />
          <input type="hidden" name="finsub" id="finsub" value="fsub">
          <?php if ($user_option != 'View') { ?> <button type="submit" class="btn btn-primary cstmBtn mt-3" <?php echo $disview; ?>>Confirm</button> <?php } ?>
          <?php if($user_option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn mt-3 ms-2">Delete</button>
                        <?php } ?> 
          <a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
        </form>
      </div>
    </div>
  </section>

</main>

<script>
  function tdsAcknowledgementSubmit() {
    if (document.tdsAcknowledgementForm.branch_code.value == '') {
      Swal.fire({ text: 'Please select Branch Code !!' }).then((result) => { setTimeout(() => {document.tdsAcknowledgementForm.branch_code.focus()}, 500) });
      return false;
    } else if (document.tdsAcknowledgementForm.fin_year.value == '') {
      Swal.fire({ text: 'Please select Finacial Year !!' }).then((result) => { setTimeout(() => {document.tdsAcknowledgementForm.fin_year.focus()}, 500) });
      return false;
    } else if (document.tdsAcknowledgementForm.quarter_no.value == '') {
      Swal.fire({ text: 'Please select Quarter Number !!' }).then((result) => { setTimeout(() => {document.tdsAcknowledgementForm.quarter_no.focus()}, 500) });
      return false;
    } else if (document.tdsAcknowledgementForm.tds_return_no.value == '') {
      Swal.fire({ text: 'Please Enter TDS Acknowledgement Number !!' }).then((result) => { setTimeout(() => {document.tdsAcknowledgementForm.tds_return_no.focus()}, 500) });
      return false;
    } else return true;
  }

  function calcQtrDate () {
    var fyear  = document.tdsAcknowledgementForm.fin_year.value ;
    if(fyear == '') {
			Swal.fire({ text: 'Please Enter Financial Year !!' }).then((result) => { setTimeout(() => {document.tdsAcknowledgementForm.fin_year.focus()}, 500) });
    } else {
      var qtrno  = document.tdsAcknowledgementForm.quarter_no.value ;
      var qtrsdt = '' ;
      var qtredt = '' ;
       
      if (qtrno == 1)       { qtrsdt = '01-04-' + fyear.substr(0,4) ;  qtredt = '30-06-' + fyear.substr(0,4) ; }
      else if (qtrno == 2)  { qtrsdt = '01-07-' + fyear.substr(0,4) ;  qtredt = '30-09-' + fyear.substr(0,4) ; }
      else if (qtrno == 3)  { qtrsdt = '01-10-' + fyear.substr(0,4) ;  qtredt = '31-12-' + fyear.substr(0,4) ; }
      else if (qtrno == 4)  { qtrsdt = '01-01-' + fyear.substr(5,4) ;  qtredt = '31-03-' + fyear.substr(5,4) ; }
      
      document.tdsAcknowledgementForm.start_date.value = qtrsdt ; document.tdsAcknowledgementForm.end_date.value = qtredt ; 
    }
  }
</script>

<?= $this->endSection() ?>