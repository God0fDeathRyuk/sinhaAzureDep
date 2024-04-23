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
<?php endif; //echo $option;die;?>
<div class="pagetitle">
      <h1>Matter Information</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
    <form method="post" action="" name="matter_infoFrm" id="matter_infoFrm">
      <div class="row">
		  <div class="col-md-12">
		  <div class="frms-sec d-inline-block w-100 bg-white p-3 pt-2">
			  <div class="search">
				<label class="d-block w-100 mb-2">Find By  <strong class="text-danger">*</strong></label>
					<select class="form-select w-35 me-2 d-block float-start" name="search_by" id="search_by" required>
					<option value="">--Select--</option>
					<option value="Client" <?= ($option!='list')?($search_by=='Client')?'selected':'':'' ?>>Client Name</option>
					<option value="Case" <?= ($option!='list')?($search_by=='Case')?'selected':'':''?>>Case No</option>
					<option value="Matter" <?= ($option!='list')?($search_by=='Matter')?'selected':'':'' ?>>Matter Desc</option>
					<option value="Ref" <?= ($option!='list')?($search_by=='Ref')?'selected':'':''?>>Reference No</option>
					<option value="Court" <?= ($option!='list')?($search_by=='Court')?'selected':'':''?>>Court Name</option>
					<option value="Judge" <?=($option!='list')?($search_by=='Judge')?'selected':'':'' ?>>Judge Name</option>
				</select>
				<input class="form-control w-63 d-block float-start" type="search" name="search_text" id="search_text" value="<?=($option!='list')?($search_text!='')?$search_text:'':'' ?>" />
				<div class="d-inline-block w-100 mt-3">
                <input type="hidden" name="option" id="option" value="<?php echo $option ?>">
				<input type="hidden" name="query_id" id="query_id" value="<?php echo $query_id ?>">
                <input type="button" name="button" id="button" value="Search" class="btn btn-primary cstmBtn btncls mt-2"  onClick="return search();">
                <input type="button" name="button" id="button" value="Reset" class="btn btn-primary cstmBtn btncls mt-2"  onClick="window.location.reload()">
                <a href="<?php echo base_url('query/print?search_by='.$search_by.'&search_text='.$search_text) ?>"  name="button" id="button"  class="btn btn-primary cstmBtn btncls mt-2" onClick="return printBtn('Print');" target="_blank">Print</a>
                <input type="button" name="button" id="button" value="Excel" class="btn btn-primary cstmBtn btncls mt-2" onClick="return printBtn('Excel');">
				</div>
			  </div>
		  </div>
		  <?php //echo '<pre>';print_r($data);die;?>
		  <div class="d-inline-block w-100 mt-3">
				<table class="table table-bordered tblePdngsml">
					<tbody>
						<tr class="fs-14">
							<th class="text-center">&nbsp;</th>
							<th>Client</th>
							<th>Matter</th>
							<th>Case No</th>
							<th>Matter Desc</th>
							<th>Ref No</th>
							<th>Court</th>
							<th>Judge</th>
						</tr>
						<?php  if($data2[0]!='') foreach ($data2 as $key => $value) {?>
						<tr>							
							<td class="text-center">
								<a href="<?php echo"/sinhaco/master/matter-masteraddedit/View?display_id=&menu_id=1015&matter_code=".$value['matter_code']."&matter_desc=".$value['matter_desc2']."&client_name=".$value['client_name']."&client_code=&pageMode=View&closePage=close"; ?>" target="_blank">View</a>
							</td>
							<td class="">
								<span><?= $value['client_name'] ?></span>
							</td>
							<td class=""><span><?= $value['matter_code'] ?></span></td>
							<td class="">
								<span><?= $value['matter_desc1'] ?></span>
							</td>
							<td class="">
								<span><?= $value['matter_desc2'] ?></span>
							</td>
							<td>
								<span><?= $value['reference_desc'] ?> </span>
							</td>
							<td>
								<span><?= $value['court_name'] ?> </span>
							</td>
							<td>
								<span><?= $value['judge_name'] ?></span>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		  </div>
      </div>
    </form>
    </section>
</main><!-- End #main -->

<?= $this->endSection() ?>