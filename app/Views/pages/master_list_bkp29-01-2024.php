<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'crud']); ?>
<?php endif; ?>

<?php 
  $url = str_replace('/index.php', '', $_SERVER['REQUEST_URI']) . "?display_id={$params['displayId']}&menu_id={$params['menuId']}";
  $url .= isset($_REQUEST['query']) ? "&query={$_REQUEST['query']}" : '';
  $urlNext =  $url . "&pg=" . ($data['pg'] + 1); 
  $urlPrevious =  $url . "&pg=" . ($data['pg'] - 1);
  $firstPage =  $url . "&pg=1";
  $lastPage =  $url . "&pg={$data['totalPage']}";
  $formURL = str_replace('/index.php', '', $_SERVER['REQUEST_URI']);
  $tableCols = count($data["th"]) + 1;
  $totalRows = count($data["td"]);
?>

<?php if ($renderFlag) : ?> <main id="main" class="main"> <?php endif; ?>

<?php if (session()->getFlashdata('message') !== NULL) : ?>
  <div id="alertMsg">
    <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
      <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="pagetitle col-md-4 float-start">
  <h1 class="mb-1"> <?= ucwords($data["heading"]) ?> </h1>
  <?php if ($renderFlag) : ?>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <span class="float-start"> Page No. <?= $data['pg'] ?> of <?= $data['totalPage'] ?> </span> 
        <span class="pgeCunt"> Total <?= $data['totalRecords'] ?> Results Found  </span>
    </li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<div class="srchtop col-md-6 float-start mt-2">
  <form action="<?php echo $formURL ?>" method="get" class="d-block float-start w-52 ms-2">
    <div class="input-group">
      <div class="form-outline">
        <input type="hidden" name="display_id" value="<?= $params['displayId']?>" class="form-control d-none" />
        <input type="hidden" name="menu_id" value="<?= $params['menuId']?>" class="form-control d-none" />
        <input type="search" name="query" id="form1" class="form-control" value="<?= $data['query'] ?>" placeholder="search here.." />
      </div>
      <button type="submit" class="btn btn-primary btnCstm">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </form>
    <div class="pagntnSec d-block w-48 float-start mt-0">
        <select class="form-select w-40 float-end" onchange="changeRowPerPage(this)">
          <option value="20" <?= (session()->rowsPerPage == 20) ? 'selected' : '' ?>> 20 </option>
          <option value="50" <?= (session()->rowsPerPage == 50) ? 'selected' : '' ?>> 50 </option>
          <option value="100" <?= (session()->rowsPerPage == 100) ? 'selected' : '' ?>> 100 </option>
          <option value="All" <?= (session()->rowsPerPage == 'All') ? 'selected' : '' ?>> All </option>
        </select>  
        <span class="d-block float-end me-2 mt-2 gotoPge">Rows Per Page: </span>
    </div>
</div>
<div class="col-md-2 float-start text-end mt-2">
  
<?php if(count($data['adlink'])>1){
   foreach ($data['adlink'] as $key => $value) { 
    $ar=explode('?',$data['adlink'][1]); 
    $arbaseURl=$ar[0];
    $arTostring=$ar[1];
    $arTostring2="";
    $arTostring3="";
    $seprate1[]='';
    $seprate2[]='';
    $seprate3[]='';
    $arr2=explode('&',$arTostring);
    for($i=0;$i<count($arr2);$i++)
    {
      $test= explode('=',$arr2[$i]);
      if($test[0]!=''){
        $arTostring2=$arTostring2.$test[0].',';
      }
      if($i!=0){
        $arTostring3=$arTostring3.$test[1].',';
      }
    }
    
    $seprate1=explode(',',$arTostring2);
    $seprate2=explode(',',$arTostring3);
   }}
                            ?>
    <?php if(count($data['adlink'])>1){ ?>                      
<form action="<?= $arbaseURl ?>" method="post" class="d-inline">
<?php for($i=0;$i<count($seprate1);$i++){?>
<input type="hidden" name="<?php echo $seprate1[$i]; ?>" id="<?php echo $seprate1[$i]; ?>" value="<?php echo $seprate2[$i]; ?>">
<?php } ?>
<button type="submit" id="submit"  class="btn btn-primary cstmBtn mt-0 me-1">Add</button>
</form>
<?php } ?>
<form action="" method="post" target="_blank" class="d-inline">
    <input type="hidden" name="output_type" value="Pdf">
    <button type="submit" class="btn btn-primary cstmBtn mt-0 p-2 pdfDnld">
        <img src="<?= base_url('public/assets/img/pdf.png') ?>" title="Download List" class="" alt="Download List"/>
    </button>
</form>
<?php endif; ?>
</div>

<section class="section dashboard d-inline-block w-100">
  <div class="row">
    <div class="col-md-12 mt-2">
      <div class="tblMn tblMnNoscrl <?= (count($data["th"])+1 > 7) ? 'NwTbl_8_Scrl_Mdm' : 'NwTbl_7_no_Scrl' ?>">
        <table class="table table-bordered tblmn" cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                  <?php foreach ($data["th"] as $th) { ?>
                  <th scope="col" style="font-size:15px;padding:5px 10px;border:1px solid #ddd;vertical-align:middle;"> <?= ucwords(trim($th)) ?> </th>
                  <?php } ?>
                  <?php if ($renderFlag) : ?> <th scope="col">Action</th> <?php endif; ?>
              </tr>
            </thead>
            <tbody>
            <?php 
            if($totalRows != 0) {
            foreach ($data["td"] as $key => $row) { ?>
                <tr>
                <?php foreach ($row as $td) { 
                    ?>
                    <td style="border:1px solid #ddd;padding:8px;"> <?= $td ?> </td>
                    
                    <?php } ?>
                    <?php if ($renderFlag) : ?>
                    <td>
                      <?php
                        foreach ($data["actionLinks"][$key] as $option) { 
                           $ar=explode('?',$option['link']);
                             $arbaseURl=$ar[0];
                             $arTostring=$ar[1];
                             $arTostring2="";
                             $arTostring3="";
                             $seprate1[]='';
                            $seprate2[]='';
                            $seprate3[]='';
                           $t= str_replace(',', '', $arTostring);
                            if (preg_match('/\s&\s/', $t)){
                            $t = str_replace(' & ',' ', $t);
                         }
                         $result = preg_replace('/<[^>]*>/', '', $t);
                            //echo $result;die;
                            $arr2 = explode('&', $result);
                             for ($i = 0; $i < count($arr2); $i++) {
                              $test = explode('=', $arr2[$i]);
                          
                              if (isset($test[0]) && $test[0] != '') {
                                  $arTostring2 .= $test[0] . ',';
                              }
                          
                              if ($i != 0 && isset($test[1])) {
                                  $arTostring3 .= $test[1] . ',';
                              }
                          }
                          
                              $seprate1=explode(',',$arTostring2);
                              $seprate2=explode(',',$arTostring3);
                              // echo '<pre>'; print_r($seprate1);
                              // echo '<pre>'; print_r($seprate2); die;

                          ?> 
                          <form action="<?= $arbaseURl ?>" method="post" class="d-inline">
                            <?php for($i=0;$i<count($seprate1)-1;$i++){?>
                           <input type="hidden" name="<?php echo $seprate1[$i]; ?>" id="<?php echo $seprate1[$i]; ?>" value="<?php echo $seprate2[$i]; ?>">
                           <?php } ?>
                           <button type="submit" id="submit" title="<?php echo $seprate2[4]; ?>" class="<?= $option['icon'] ?> edit"></button>
                          </form>
                          <!-- <a href="<?= $arbaseURl ?>" class="me-1" title="<?= $option['desc'] ?>"><i class="<?= $option['icon'] ?> edit"></i></a> -->
                     <?php } ?>
                    </td>
                    <?php endif; ?>
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

<?php if ($renderFlag) : ?>
<div class="d-inline-block w-100 mb-1 mt-2">
  <div class="d-nline-block w-50 text-start float-start">
      <div class="pagntnSec d-nline-block w-100">
        <span class="d-block float-start me-2 mt-2 gotoPge">Enter Page No: </span>
        <form class="d-block float-start w-25" style="width:30% !important;" action="<?= $formURL ?>">
          <input type="hidden" name="display_id" value="<?= $params['displayId']?>" class="form-control rounded-0 d-none float-start w-65"/>
          <input type="hidden" name="menu_id" value="<?= $params['menuId'] ?>" class="form-control rounded-0 d-none float-start w-65"/>
          <input type="number" name="pg" min="1" max="<?= $data['totalPage'] ?>" class="form-control rounded-0 d-block float-start w-65" required />
          <button type="submit" class="btn btn-primary" style="margin-left: 10px;border-radius: 0;padding: 6px 8px;background-color:#055570;border:1px solid #055570;"> Go </button>
        </form>   
      </div>
  </div>
  <div class="d-nline-block w-50 text-end float-start">
    <div class="pagntnSec d-nline-block w-100">
        <a href="<?= $firstPage ?>"> <i class="fas fa-angle-double-left fnt14" title="First"></i> </a>
        <a href="<?= ($data['pg'] <= 1) ? '' : $urlPrevious ?>"> <i class="fas fa-angle-left fnt14 ms-2" title="Previous"></i> </a>
        <span class="mx-3">Page <b class="crntpg"> <?= $data['pg'] ?> </b> of <b> <?= $data['totalPage'] ?> </b></span>
        <a href="<?= ($data['pg'] >= $data['totalPage']) ? '' : $urlNext ?>"> <i class="fas fa-angle-right fnt14 me-2" title="Next"></i> </a>
        <a href="<?= $lastPage ?>"> <i class="fas fa-angle-double-right fnt14" title="Last"></i> </a>
      </div>
  </div>
</div>
</main>
<?php endif; ?>   

<?= $this->endSection() ?>