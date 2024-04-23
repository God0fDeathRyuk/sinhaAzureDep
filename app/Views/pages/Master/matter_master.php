<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<main id="main" class="main">
<?php $session = session();
     $sessionName=$session->userId;
  $accessPermission=explode(",", $session->Accpermission);
   if (session()->getFlashdata('message') !== NULL) : ?>
      

    <div id="alertMsg">
        <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b>
                    <?= session()->getFlashdata('message') ?>
                </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
<div class="pagetitle">
  <h1>Matter Entry</h1>
</div><!-- End Page Title -->
<form action="<?php if($option != 'Edit'){ echo "/sinhaco/master/matter-masteraddedit/Add";} else{ echo '/sinhaco/master/matter-masteraddedit/Edit';}?>" method="get" id="matterMaster">
<section class="section dashboard">
<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
  <div class="row">
      <div class="col-md-12 mt-2">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
            <div class="col-md-4 float-start px-2 mb-3 position-relative">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code</label>
                <div class="position-relative">
                <input type="text" class="form-control" name="matter_code" id="matterCode" placeholder="Matter Code" onchange="fetchData(this, 'matter_code', ['matterCode', 'matterDesc','clientName'], ['matter_code', 'matter_desc','client_name'],'matter_code')" required  value="" readonly/>
                    <i class="fa fa-binoculars icn-vw icn-vw2 d-none" id="matterBinocular" onclick="showData('matter_code', '<?= '4536' ?>', 'matterCode', [ 'matterCode','matterDesc','clientName'], ['matter_code','matter_desc','client_name'],'matter_code')"  data-toggle="modal" data-target="#lookup" style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
                </div>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Matter Desc</label>
                <div class="position-relative">
                <input type="text" class="form-control" placeholder="Matter Description" name="matter_desc" id="matterDesc" value="" <?= $redk; ?>/>
            </div>
            </div>
            <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Client Name </label>
                <div class="position-relative">
                <input type="text" class="form-control w-63 float-start" placeholder="Client Name" name="client_name" id="clientName" value="" <?= $redk; ?>/>
                <input type="hidden" class="form-control w-63 float-start" name="client_code" id="client_code" value=""/>
                </div>
            </div>
        </div>
        <input type="hidden" id="pageMode" name="pageMode" value="">
        <input type="hidden" id="closePage" name="closePage" value="">
        <?php $accessPermission=explode(",", $session->Accpermission); 
                    foreach ($permdata as $value) { 
                    $menuId=explode(",", $value['menu_id']);
          $count=sizeof($menuId);
          for($i=0;$i<$count;$i++){
        if($menuId[$i]==$_REQUEST['menu_id']){ 
          if($value['permission_name']=='Add'){?>
        <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2 <?= (in_array($value['id'], $accessPermission)) ? 'd-block float-start' : 'd-none'  ?>"  onclick="formOption('/master/matter-masteraddedit/', '<?php echo $value['permission_name'] ?>', 'matterMaster')" id="<?php echo $value['permission_name'] ?>"><?php echo $value['permission_name']; ?></button>
       <?php }else{ ?>
        <?php if($value['permission_name']!='Proceed'){?>
        <a class="btn btn-primary cstmBtn mt-0 me-2 <?= (in_array($value['id'], $accessPermission)) ? 'd-block float-start' : 'd-none'  ?>" <?php if($value['permission_name']!='Proceed'){ ?> onclick="buttonMode('<?php echo $value['permission_name']?>')" <?php }?> id="<?php echo $value['permission_name']?>"><?php echo $value['permission_name']; ?></a>
       <?php } 
       if($value['permission_name']=='Proceed'){?>
       <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2 <?= (in_array($value['id'], $accessPermission)) ? 'd-block float-start' : 'd-none'  ?>" id="<?php echo $value['permission_name'] ?>"><?php echo $value['permission_name']; ?></button>
        <?php }}} }}?>
        <a class="btn btn-primary cstmBtn mt-0 me-2" onclick="buttonMode('Reset')"  id="Reset">Reset</a>
      </div>
  </div>
</section>
</form>

</main><!-- End #main -->
<?= $this->endSection() ?>