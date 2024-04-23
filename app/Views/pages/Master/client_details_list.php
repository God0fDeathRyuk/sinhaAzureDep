<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'crud']); ?>

<?php $session = session();
    $accessPermission=explode(",", $session->Accpermission);
  
  $url = str_replace('/index.php', '', $_SERVER['PHP_SELF']) . "?display_id={$params['displayId']}&menu_id={$params['menuId']}";
  $urlNext =  $url . "&pg=" . ($data['pg'] + 1); 
  $urlPrevious =  $url . "&pg=" . ($data['pg'] + 1);
  $firstPage =  $url . "&pg=1";
  $lastPage =  $url . "&pg={$data['totalPage']}";
  $formURL = str_replace('/index.php', '', $_SERVER['PHP_SELF']);
  $tableCols = count($data["th"]) + 1;
  $totalRows = count($data["td"]);

  // echo "<pre>"; print_r($data); die;

?>

<main id="main" class="main">

<div class="pagetitle col-md-5 float-start">
  <h1> <?= ucwords($data["heading"]) ?> </h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><span> Page No. <?= $data['pg'] ?> </span> </li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<div class="srchtop col-md-4 float-start mt-2">
  <form action="<?= $formURL ?>" method="get">
    <div class="input-group">
      <div class="form-outline">
        <input type="hidden" name="display_id" value="<?= $params['displayId']?>" class="form-control d-none" />
        <input type="hidden" name="menu_id" value="<?= $params['menuId']?>" class="form-control d-none" />
        <input type="search" name="query" id="form1" class="form-control" value="<?= $data['query'] ?>" placeholder="search here.." />
      </div>
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </form>
</div>
<div class="w-100 float-start text-start mt-4 top-btn-fld">
 <?php   $accessPermission=explode(",", $session->Accpermission); 
                      foreach ($permdata as $key => $value) { 
                        $menuId=explode(",", $value['menu_id']);
                        $permission_name=$value['permission_name'];
                        $count=sizeof($menuId);
                      if($permission_name=='Add'){ ?>
                      <a href="/sinhaco/master/client-details-combine/<?php echo strtolower($value['permission_name']) ?>?display_id=<?php echo $params['displayId']; ?>&menu_id=<?php echo $params['menuId']; ?>" class="btn btn-primary cstmBtn mt-0 me-2" title="<?= $value['permission_name'] ?>" <?= (in_array($_REQUEST['menu_id'], $data["detail_linkparam"])) ? 'd-block float-start' : 'd-none'  ?>><?php echo $permission_name; ?></a>

<?php } ?>
<?php
 if($permission_name=='List'){ ?>
  <!-- <a href="/master/client-master/<?php echo strtolower($value['permission_name']) ?>?display_id=<?php echo $params['displayId']; ?>&menu_id=<?php echo $params['menuId']; ?>" class="btn btn-primary cstmBtn mt-0" title="LIST" ><?php echo $value['permission_name']; ?></a>
 -->
 <a href="" class="btn btn-primary cstmBtn mt-0" title="LIST" ><?php echo $value['permission_name']; ?></a>
<?php }
}?>
</div>
<section class="section dashboard d-inline-block w-100">
  <div class="row">
    <div class="col-md-12 mt-2">
      <div class="tblMn tblMnNoscrl ">
        <table class="table table-bordered tblmn">
            <thead>
              <tr>
                  <?php foreach ($data["th"] as $th) { ?>
                  <th scope="col"> <?= ucwords(trim($th)) ?> </th>
                  <?php } ?>
                  <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php 
            if($totalRows != 0) {
            foreach ($data["td"] as $key => $row) { ?>
                <tr>
                <?php foreach ($row as $td) { 
                    ?>
                    <td> <?= $td ?> </td>
                    
                    <?php } ?>
                    <td>
                    <?php 
                  //  echo '<pre>';print_r($data["actionLinks"]); echo '</pre>';die;
                    $accessPermission=explode(",", $session->Accpermission); 
                      foreach ($permdata as $key => $value) { 
                        $menuId=explode(",", $value['menu_id']);
                        $count=sizeof($menuId);
                        for($i=0;$i<$count;$i++){
                      if($menuId[$i]==$_REQUEST['menu_id']){ 
                        $permission_name=$value['permission_name'];
                      if($permission_name!='Add' && $permission_name!='List'){ ?>
                        <a href="/sinhaco/master/client-details-combine/<?php echo strtolower($value['permission_name']) ?>?display_id=<?php echo $params['displayId']; ?>&menu_id=<?php echo $params['menuId']; ?>&uid=<?php echo $row['client_code']; ?>" class="me-1" title="<?= $value['permission_name'] ?>" <?= (in_array($_REQUEST['menu_id'], $data["detail_linkparam"])) ? 'd-block float-start' : 'd-none'  ?>><i class="<?= $value['icon'] ?> edit" ></i></a>
                     <?php } }}}
                        ?>
                    </td>
                </tr>
                <?php }
            } else { ?>
            	<td colspan= <?= $tableCols ?> class="text-center"> No Records Found !! </td>
            <?php } ?>
              </tbody>
        </table>
      </div>    
    </div>
  </div>
</section>

<div class="d-inline-block w-100 mb-1">
  <div class="d-nline-block w-50 text-start float-start">
      <div class="pagntnSec d-nline-block w-100">
        <span class="d-block float-start me-2 mt-2 gotoPge">Enter Page No: </span>
        <form class="d-block float-start w-25" style="width:30% !important;" action="<?= $formURL ?>">
          <input type="hidden" name="display_id" value="<?= $params['displayId']?>" class="form-control rounded-0 d-none float-start w-65"/>
          <input type="hidden" name="menu_id" value="<?= $params['menuId'] ?>" class="form-control rounded-0 d-none float-start w-65"/>
          <input type="number" name="pg" min="1" max="<?= $data['totalPage'] ?>" class="form-control rounded-0 d-block float-start w-65" required />
          <button type="submit" class="btn btn-primary" style="margin-left: 10px;border-radius: 0;padding: 6px 8px;"> Go </button>
        </form>   
      </div>
  </div>
  <div class="d-nline-block w-50 text-end float-start">
    <div class="pagntnSec d-nline-block w-100">
        <a href="<?= $firstPage ?>"> <i class="fas fa-angle-double-left fnt14" title="First"></i> </a>
        <a href="<?= $urlPrevious ?>"> <i class="fas fa-angle-left fnt14 ms-2" title="Previous"></i> </a>
        <span class="mx-3">Page <b class="crntpg"> <?= $data['pg'] ?> </b> of <b> <?= $data['totalPage'] ?> </b></span>
        <a href="<?= $urlNext ?>"> <i class="fas fa-angle-right fnt14 me-2" title="Next"></i> </a>
        <a href="<?= $lastPage ?>"> <i class="fas fa-angle-double-right fnt14" title="Last"></i> </a>
      </div>
  </div>
</div>
                    
         

</main>

<?= $this->endSection() ?>