<?= $this->extend("layouts/master") ?>

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<main id="main" class="main <?php //if($option=='View'){echo 'm-0 m-auto';} ?>">
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
        <?php endif;  ?>
        <div class="pagetitle w-100 float-start border-bottom pb-1">
      <h1 class="col-md-8 float-start">Matter Master [<?php echo strtoupper($option) ?>]</h1>
      <!-- <div class="col-md-4 float-end text-end mb-2">
					
					<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2">Back</a>
	  </div> -->
    </div><!-- End Page Title -->
        <section class="section dashboard mtop90">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec-insde d-block float-start col-md-4 px-0 mb-3 ms-3 pt-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">&nbsp;</label>
                        <select class="form-select" name="tab_change" id="tabChange" onchange="changeSlide()"
                            onclick="changeTab()">
                            <option value="basic">Basic </option>
                            <option value="oSide" <?= ($option=='Add') ? 'disabled' : '' ?>>Other Side </option>
                            <option value="cncl(0)" <?= ($option=='Add') ? 'disabled' : '' ?>>Councel(0) </option>
                            <option value="Advisor(O)" <?= ($option=='Add') ? 'disabled' : '' ?>>Advisor(0) </option>
                            <option value="Advisoronrec(O)" <?= ($option=='Add') ? 'disabled' : '' ?>>Adv-on-Rec(0)
                            </option>
                            <option value="Intprty(O)" <?= ($option=='Add') ? 'disabled' : '' ?>>Int. Party(0) </option>
                            <option value="Advisor(C)" <?= ($option=='Add') ? 'disabled' : '' ?>>Advisor(C) </option>
                            <option value="Councel(C)" <?= ($option=='Add') ? 'disabled' : '' ?>>Councel(C) </option>
                            <option value="Advonrec(C)" <?= ($option=='Add') ? 'disabled' : '' ?>>Adv-on-Rec(C)
                            </option>
                            <option value="Int.Party(C)" <?= ($option=='Add') ? 'disabled' : '' ?>>Int.Party(C)</option>
                            <option value="Represent(C)" <?= ($option=='Add') ? 'disabled' : '' ?>>Represent(C)</option>
                            <option value="referedBy" <?= ($option=='Add') ? 'disabled' : '' ?>>Refered By</option>
                            <option value="Initials" <?= ($option=='Add') ? 'disabled' : '' ?>>Initials</option>
                            <option value="RelatedMatters" <?= ($option=='Add') ? 'disabled' : '' ?>>Related Matters
                            </option>
                            <option value="Org.Rec" <?= ($option=='Add') ? 'disabled' : '' ?>>Org.Rec</option>
                            <option value="bill_addr" <?= ($option=='Add') ? 'disabled' : '' ?>>Bill Addr</option>
                            <option value="Case_Nos" <?= ($option=='Add') ? 'disabled' : '' ?>>Case Nos</option>
                            <option value="Details" <?= ($option=='Add') ? 'disabled' : '' ?>>Details</option>
                            <option value="Cheques" <?= ($option=='Add') ? 'disabled' : '' ?>>Cheques</option>
                        </select>
                    </div>
                    <div class="frms-sec-insde d-block col-md-4 px-0 mb-3 float-start ms-4 pt-3">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">File No</label>
                        <input type="text" class="form-control" placeholder="File no" name=""
                            id="" value="<?= ($option!='Add') ? $_REQUEST['matter_code'] : '' ?>" readonly/>
                            <input type="hidden" name="" id="" value="<?= ($option=='Add') ? $lastIdtot['lastIdMax']+1 : '' ?>" >
                    </div>
                    <form
                        action="<?php if($option != 'Edit'){ echo "/sinhaco/master/matter-masteraddedit/Add";} else{ echo '/sinhaco/master/matter-masteraddedit/Edit';}?>"
                        method="post" id="matterMasterAddEdit" enctype="multipart/form-data">
<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
                        <input type="hidden" class="form-control" placeholder="File no" name="matter_code"
                            id="matterCode" value="<?= ($option!='Add') ? $_REQUEST['matter_code'] : '' ?>" readonly/>
                            <input type="hidden" name="lastId" id="lastId" value="<?= ($option=='Add') ? $lastIdtot['lastIdMax']+1 : '' ?>" >
                        <div class="frms-sec-insde d-block w-100 px-0 mb-3">
                            <div id="basic" class="frms-sec mt-2 bg-white">
                                <label class="col-md-3 float-end py-2 text-center text-white text-uppercase font-bold me-3"
                                        style="background-color:<?php echo $colour_s;?>; color:#FFFF33; text-align:center; font-weight:bold; font-size:16px; border:none;margin-top: 35px;"><?= ($option!='Add' ) ? isset($data23['status_desc'])? $data23['status_desc']: '&nbsp' : '&nbsp' ?>
                                        <input type="hidden" name="status_master" id="status_mater"
                                            value="<?= ($option!='Add') ? isset($data5['status_code'])?$data5['status_code']:'' : $data55['status_code'] ?>"></label>
                                <!-- End Page Title -->
                                <div class="frms-se d-inline-block w-100 bg-white p-3 pt-0">
                                    <div
                                        class="frms-sec-insde d-block float-start position-relative col-md-6 px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Opened By</label>
                                        <input type="text" class="form-control w-63 float-start" placeholder=""
                                            name="prepared_name" id="preparedName"
                                            value="<?= ($option!='Add') ? $data5['prepared_by'] : ''?>" readonly />
                                        <input type="text" class="form-control w-35 ms-1 float-start" placeholder=""
                                            name="prepared_on" id="preparedOn"
                                            value="<?= ($option!='Add') ? $data5['prepared_on'] : date('d-m-Y') ?>" readonly />
                                        <input type="hidden" class="form-control w-35 ms-1 float-start" placeholder=""
                                            name="prepared_by" id="preparedBy"
                                            value="<?= ($option!='Add') ? $data5['prepared_by'] : $sessionName ?>" readonly />
                                    </div>
                                    <div
                                        class="frms-sec-insde d-block float-start position-relative col-md-6 px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Last Update By</label>
                                        <input type="text" class="form-control w-63 float-start" placeholder=""
                                            name="last_update_name" id="lastUpdateName"
                                            value="<?= ($option!='Add') ? $data5['last_update_id'] : '' ?>" readonly />
                                        <input type="date" class="form-control w-35 ms-1 float-start" placeholder=""
                                            name="last_update_dt" id="lastUpdateDt"
                                            value="<?= ($option!='Add') ? $data5['last_update_dt'] : '' ?>" readonly />
                                        
                                    </div>
                                    <input type="hidden" class="form-control w-35 ms-1 float-start" placeholder=""
                                        name="status_code" id="statusCode" value="<?= ($option!='Add') ? $data5['status_code'] : $data55['status_code'] ?>"/>
                                    <div class="d-inline-block w-100 mb-0 pb-0">
                                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Initial<b
                                                    class="text-danger">*</b></label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" name="initial_name"
                                                    id="initialName" placeholder="Initial Name"
                                                    onChange="fetchData(this, 'initial_code', ['initialName', 'initialCode'], ['client_name', 'client_code'], 'initial_code')"
                                                    required
                                                    value="<?= ($option!='Add') ? isset($data25['initial_name'])? $data25['initial_name']: '' : '' ?>" <?php echo $redokadd ?>/>
                                                <input type="hidden" class="form-control" name="initial_code"
                                                    id="initialCode" required
                                                    value="<?= ($option!='Add') ? $data5['initial_code'] : '' ?>" />
                                                <i class="fa fa-binoculars icn-vw icn-vw2"
                                                    onClick="showData('initial_code', '4191', 'initialName', [ 'initialName','initialCode'], ['initial_name','initial_code'], 'initial_code')"
                                                    data-toggle="modal" data-target="#lookup"
                                                    style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">File Location <b
                                                    class="text-danger">*</b></label>
                                            <select class="form-select" required name="file_locn_code" id="fileLocnCode" <?php echo $disview ?>>
                                                <option value="">-- None --</option>
                                                <?php foreach ($data as $value) {?>
                                                <option
                                                    <?php  if($option!='Add') { if($data5['file_locn_code']== $value['code_code']){echo 'selected';} }?>
                                                    value="<?= $value['code_code'] ?>">
                                                    <?= $value['code_desc']; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Matter Type</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" name="matter_type_desc"
                                                    id="matterTypeDesc" placeholder="Matter Type"
                                                    onChange="fetchData(this, 'matter_type_code', ['matterTypeCode', 'matterTypeDesc', 'matterSubTypeDesc','matterSubTypeCode','matterSubSubTypeDesc'], ['matter_type_code', 'matter_type_desc','matter_sub_type_desc','matter_sub_type_code','matter_sub_sub_type_desc','matter_sub_sub_type_code'], 'matter_code_des')"
                                                    required value="<?= ($option!='Add') ? $matter_type_desc : '' ?>"
                                                    readonly />
                                                <input type="hidden" class="form-control" name="matter_type_code"
                                                    id="matterTypeCode" requiredF
                                                    value="<?= ($option!='Add') ? $matter_type_code : '' ?>" />
                                                <i class="fa fa-binoculars icn-vw icn-vw2"
                                                    onClick="showData('matter_type_code', '4201', 'matterTypeCode', [ 'matterTypeCode','matterTypeDesc','matterSubTypeDesc','matterSubTypeCode','matterSubSubTypeDesc','matterSubSubTypeCode'], ['matter_type_code','matter_type_desc','matter_sub_type_desc','matter_sub_type_code','matter_sub_sub_type_desc','matter_sub_sub_type_code'], 'matter_code_des','','1','matter_sub_type_code','matter_sub_sub_type_code')"
                                                    data-toggle="modal" data-target="#lookup"
                                                    style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Sub Matter</label>
                                        <textarea class="form-control" cols="3" placeholder="Sub Matter"
                                            name="matter_sub_type_desc"
                                            id="matterSubTypeDesc" <?php echo $redokadd ?>><?= ($option!='Add') ? $matter_sub_type_desc : '' ?></textarea>
                                        <input type="hidden" name="matter_sub_type_code" id="matterSubTypeCode"
                                            value="<?= ($option!='Add') ? $matter_sub_type_code : '' ?>">
                                    </div>
                                    <div class="col-md-6 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">S/Sub Matter</label>
                                        <textarea class="form-control" cols="3" placeholder="S/Sub Matter"
                                            name="matter_sub_sub_type_desc"
                                            id="matterSubSubTypeDesc" <?php echo $redokadd ?>><?= ($option!='Add') ? $matter_sub_sub_type_desc : '' ?></textarea>
                                        <input type="hidden" name="matter_sub_sub_type_code" id="matterSubSubTypeCode"
                                            value="<?= ($option!='Add') ? $matter_sub_sub_type_code : '' ?>">
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Court Name <b
                                                class="text-danger">*</b></label>
                                        <select class="form-select" required name="court_code" id="courtCode" <?php echo $disview ?>>
                                            <option value="">-- None --</option>
                                            <?php foreach ($data1 as  $value) {?>
                                            <option
                                                <?php if($option!='Add') { if($data5['court_code']== $value['code_code']){echo 'selected';} }?>
                                                value="<?= $value['code_code'] ?>">
                                                <?= $value['code_desc'] ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Judge Name</label>
                                        <input type="text" class="form-control" placeholder="Judge Name"
                                            name="judge_name" id="judgeName"
                                            value="<?= ($option!='Add') ? $data5['judge_name'] : '' ?>" <?php echo $redokadd ?>/>
                                    </div>
                                    <div
                                        class="frms-sec-insde d-block float-start position-relative col-md-4 px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Case Type</label>
                                        <select class="form-select w-100 float-start" name="case_type_code" id="caseTypeCode" onBlur="case_type_no_year(this,'<?php echo $option?>')" <?php echo $disview ?>>
                                            <option>-- None --</option>
                                            <?php foreach ($data2 as $value) {?>
                                            <option
                                                <?php if($option!='Add') { if($data5['case_type_code']== $value['code_code']){echo 'selected';} }  ?>
                                                value="<?= $value['code_code'] ?>">
                                                <?= $value['code_desc'] ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" size="2" maxlength="50" name="case_type_value"
                                            id="caseTypeValue"
                                            value="<?= ($option!='Add') ? $data5['case_type_code'] : '' ?>">
                                        
                                       
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Case No</label>
                                        <input type="text" class="form-control w-32 ms-1 float-start" placeholder="Case No"
                                            name="case_no" id="caseNo"
                                            onBlur="case_type_no_year(this,'<?php echo $option?>');check_case_number(this,'<?php echo $option?>')"
                                            value="<?= ($option!='Add') ? $data5['case_no'] : '' ?>" <?php echo $redokadd ?>/>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Case Year</label>
                                        <input type="text" class="form-control w-32 ms-1 float-start" placeholder="Case Year"
                                            name="case_year" id="caseYear"
                                            onBlur="case_type_no_year(this,'<?php echo $option?>');checkCaseNo()"
                                            value="<?= ($option!='Add') ? $data5['case_year'] : '' ?>" <?php echo $redokadd ?>/>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Case No</label>
                                        <input type="text" class="form-control" placeholder="Case No"
                                            name="matter_desc1" id="matterDesc1"
                                            value="<?= ($option!='Add') ? $data5['matter_desc1'] : '' ?>" <?php echo $redokadd ?> />
                                        <input type="hidden" name="old_matter_desc1" id="old_matter_desc1"
                                            value="<?= ($option!='Add') ? $data5['matter_desc1'] : '' ?>">
                                    </div>
                                    <div class="col-md-6 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Matter Description <b
                                                class="text-danger">*</b></label>
                                        <textarea class="form-control" cols="3" placeholder="Matter Description"
                                            name="matter_desc2" id="matterDesc2"
                                            required <?php echo $redokadd ?>><?= ($option!='Add') ? $data5['matter_desc2'] : '' ?></textarea>
                                    </div>
                                    <div class="col-md-6 float-start px-2 mb-1 h100">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Trust ID</label>
                                        <input type="text" class="form-control" placeholder="Trust ID" name="trust_name"
                                            id="trustName"
                                            value="<?= ($option!='Add') ? $data5['trust_name'] : '' ?>" <?php echo $redokadd ?>/>
                                    </div>
                                    <div class="col-md-6 float-start px-2 mb-1 position-relative">
                                        <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name<b
                                                class="text-danger">*</b></label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" name="client_name" id="clientName"
                                                placeholder="Client Name"
                                                onChange="fetchData(this, 'client_address', ['clientCode', 'clientName','clientGroupName'], ['client_code', 'client_name','code_desc'], 'client_group')"
                                                required value="<?= ($option!='Add') ? isset($data24['client_name'])? $data24['client_name'] : '' : '' ?>"
                                                <?php echo $redokadd ?> />
                                            <input type="hidden" class="form-control" name="client_code" id="clientCode"
                                                placeholder="Client Name"
                                                value="<?= ($option!='Add') ? $data5['client_code'] : '' ?>" />
                                            <i class="fa fa-binoculars icn-vw icn-vw2"
                                                onClick="showData('client_code', '<?= '4533' ?>', 'clientCode', [ 'clientCode','clientName','clientGroupName'], ['client_code','client_name','code_desc'], 'client_group')"
                                                data-toggle="modal" data-target="#lookup"
                                                style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6 float-start px-2 mb-2">
                                        <label class="d-inline-block w-100 mb-1 lbl-mn">Client Group <b
                                                class="text-danger">*</b></label>
                                        <input type="text" class="form-control" placeholder="Client Group" required
                                            name="client_group_name" id="clientGroupName"
                                            value="<?= ($option!='Add') ? isset($data26['code_desc'])? $data26['code_desc'] : '' : '' ?>" <?php echo $redokadd ?> />
                                       
                                        <input type="hidden" name="client_group_code" name="clientGroupCode"
                                            value="<?= ($option!='Add') ? $data24['client_group_code'] : '' ?>"
                                            readonly>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-1 lbl-mn">Appearing For<b
                                                class="text-danger">*</b></label>
                                        <select class="form-select w-100 float-start" name="appearing_for_code"
                                            id="appearingForCode" required <?php echo $disview ?>>
                                            <option value="">--None-- </option>
                                            <?php foreach ($data3 as $value) {?>
                                            <option
                                                <?php if($option!='Add') { if($data5['appearing_for_code']== $value['code_code']){echo 'selected';} } ?>
                                                value="<?= $value['code_code'] ?>">
                                                <?= $value['code_desc'] ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Filing Date</label>
                                        <input type="text" class="form-control datepicker" placeholder="Filing Date"
                                            name="date_of_filing" id="dateOfFiling"
                                            value="<?= ($option!='Add') ? ($data5['date_of_filing']!='0000-00-00')? date('d-m-Y', strtotime($data5['date_of_filing'])) :'' : '' ?>" onblur="make_date(this)" <?php echo $disview ?>/>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Appearing For No</label>
                                        <input type="text" class="form-control" placeholder="Appearing For No"
                                            name="appearing_for_no" id="appearingForNo"
                                            value="<?= ($option!='Add') ? $data5['appearing_for_no'] : '' ?>"  <?php echo $redokadd ?>/>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Requisition No</label>
                                        <input type="text" class="form-control" placeholder="Requisition No"
                                            name="requisition_no" id="requisitionNo"
                                            value="<?= ($option!='Add') ? $data5['requisition_no'] : '' ?>" <?php echo $redokadd ?>/>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Stake Amount</label>
                                        <input type="text" class="form-control" placeholder="Stake Amount"
                                            name="stake_amount" id="stakeAmount"
                                            value="<?= ($option!='Add') ? $data5['stake_amount'] : '' ?>" <?php echo $redokadd ?>/>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Notice No</label>
                                        <input type="text" class="form-control" placeholder="Notice No" name="notice_no"
                                            id="noticeNo" value="<?= ($option!='Add') ? $data5['notice_no'] : '' ?>" <?php echo $redokadd ?>/>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Notice Date</label>
                                        <input type="text" class="form-control datepicker" placeholder="Notice Date"
                                            name="notice_date" id="noticeDate"
                                            value="<?= ($option!='Add') ? ($data5['notice_date']!='0000-00-00')? date('d-m-Y', strtotime($data5['notice_date'])) :'' : '' ?>" onblur="make_date(this)" <?php echo $disview ?>/>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">To</label>
                                        <div class="d-block float-start w-48 bdr-rnd">
                                            <input type="hidden" name="apply_oppose_ind" id="applyOpposeInd"
                                                value="<?= ($option!='Add') ? $data5['apply_oppose_ind'] : '' ?>">
                                            <input type="radio" id="apply_opposeInd1" name="apply_oppose_ind1" value="O"
                                            <?= ($option!='Add')?($data5['apply_oppose_ind']=='O')? 'checked' :'' :'' ?> <?php echo $disview ?>>
                                            <label for="Opse">Oppose</label>
                                        </div>
                                        <div class="d-block float-start w-48 ms-3 bdr-rnd">
                                            <input type="radio" id="apply_opposeInd1" name="apply_oppose_ind1" value="A"
                                                <?= ($option!='Add')?($data5['apply_oppose_ind']=='A')? 'checked' :'' :'checked' ?> <?php echo $disview ?>>
                                            <label for="aply">Apply</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1 h75">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Billable</label>
                                        <div class="d-block float-start wdt-48 bdr-rnd">
                                            <input type="radio" name="billable_option1" id="billableOption1" value="Y"
                                            <?= ($option!='Add')?($data5['billable_option']=='Y')? 'checked' :'' :'checked' ?> <?php echo $disview ?>>
                                            <label for="ys">Yes</label>
                                        </div>
                                        <div class="d-block float-start wdt-48 ms-2 bdr-rnd">
                                            <input type="radio" name="billable_option1" id="billableOption1" value="N"
                                            <?= ($option!='Add')?($data5['billable_option']=='N')? 'checked' :'' :'' ?> <?php echo $disview ?>>
                                            <label for="no">No</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Ref. No</label>
                                        <input type="text" class="form-control" placeholder="Ref. No"
                                            name="reference_desc" id="referenceDesc"
                                            value="<?= ($option!='Add') ? $data5['reference_desc'] : '' ?>" <?php echo $redokadd ?>/>
                                    </div>
                                    <div class="col-md-4 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Product <b
                                                class="text-danger">*</b></label>
                                        <select class="form-select float-start" required name="reference_type_code"
                                            id="referenceTypeCode" <?php echo $disview ?>>
                                            <option>--None--</option>
                                            <?php foreach ($data4 as $value) {?>
                                            <option
                                                <?php if($option!='Add') { if($data5['reference_type_code']== $value['code_code']){echo 'selected';} }?>
                                                value="<?= $value['code_code'] ?>">
                                                <?= $value['code_desc'] ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-12 float-start px-2 mb-1">
                                        <label class="d-inline-block w-100 mb-2 lbl-mn">Subject</label>
                                        <textarea class="form-control" cols="3" placeholder="Subject"
                                            name="subject_desc"
                                            id="subjectDesc" <?php echo $redokadd ?>><?= ($option!='Add') ? $data5['subject_desc'] : '' ?></textarea>
                                    </div>
                                    <div class="bdr-top d-inline-block w-100 py-1">
                                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Addr Code [ Communication
                                                Address ]</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" name="corrp_addr_codeV"
                                                    id="corrpAddrCodeV" placeholder="Address Code"
                                                    onChange="fetchData(this, 'client_address', ['corrpAddrCode','corrpAddrCodeV', 'addressLine_1','addressLine_2','addressLine_3','City','pinCode','state_name','stdCode','Country','isdCode','phoneNo','faxNo','mobileNo','clientGst','billingAddrCode','bAddressLine1','bAddressLine2','bAddressLine3','bAddressLine4','bCity','bPinCode','bStateName','bIsdCode','bCountry','bStdCode','bPhoneNo','bFaxNo','bMobileNo','bEmailId'], ['corrp_addr_code','corrp_addr_code', 'address_line_1','address_line_2','address_line_3','city','pin_code','state_name','std_code','country','isd_code','phone_no','fax_no','mobile_no','client_gst','corrp_addr_code', 'address_line_1','address_line_2','address_line_3','city','pin_code','state_name','std_code','country','isd_code','phone_no','fax_no','mobile_no','client_gst'], 'corrp_addr_code')"
                                                    required
                                                    value="<?= ($option!='Add') ? $data5['corrp_addr_code'] : '' ?>"
                                                    <?php echo $redokadd ?>/>
                                                    <input type="hidden" class="form-control" name="corrp_addr_code"
                                                    id="corrpAddrCode" value="<?= ($option!='Add') ? $data5['corrp_addr_code'] : '' ?>"/>
                                                <i class="fa fa-binoculars icn-vw icn-vw2"
                                                    onClick="showData('corrp_addr_code', 'display_id=4074&client_code=@clientCode', 'corrpAddrCodeV',  ['corrpAddrCode','corrpAddrCodeV', 'addressLine_1','addressLine_2','addressLine_3','City','pinCode','stateName','stdCode','Country','isdCode','phoneNo','faxNo','mobileNo','clientGst','billingAddrCode','bAddressLine1','bAddressLine2','bAddressLine3','bAddressLine4','bCity','bPinCode','bStateName','bIsdCode','bCountry','bStdCode','bPhoneNo','bFaxNo','bMobileNo','bEmailId'], ['corrp_addr_code','corrp_addr_code', 'address_line_1','address_line_2','address_line_3','city','pin_code','state_name','std_code','country','isd_code','phone_no','fax_no','mobile_no','client_gst','corrp_addr_code', 'address_line_1','address_line_2','address_line_3','city','pin_code','state_name','std_code','country','isd_code','phone_no','fax_no','mobile_no','client_gst','corrp_addr_code', 'address_line_1','address_line_2','address_line_3','city','pin_code','state_name','std_code','country','isd_code','phone_no','fax_no','mobile_no','client_gst'], 'corrp_addr_code','','1','clientCode','')"
                                                    data-toggle="modal" data-target="#lookup"
                                                    style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Line#1 <b
                                                    class="text-danger">*</b></label>
                                            <input type="text" class="form-control" placeholder="Line"
                                                name="address_line_1" id="addressLine_1" required
                                                value="<?= ($option!='Add') ? $address_line_1 : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Line#2</label>
                                            <input type="text" class="form-control" placeholder="Line"
                                                name="address_line_2" id="addressLine_2"
                                                value="<?= ($option!='Add') ? $address_line_2 : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Line#3</label>
                                            <input type="text" class="form-control" placeholder="Line"
                                                name="address_line_3" id="addressLine_3"
                                                value="<?= ($option!='Add') ? $address_line_3 : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">City</label>
                                            <input type="text" class="form-control" placeholder="City" name="city"
                                                id="City" value="<?= ($option!='Add') ? $city : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Pin/Zip</label>
                                            <input type="text" class="form-control" placeholder="Pin/Zip"
                                                name="pin_code" id="pinCode"
                                                value="<?= ($option!='Add') ? $pin_code : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">State</label>
                                            <input type="text" class="form-control" placeholder="State"
                                                name="state_name" id="stateName"
                                                value="<?= ($option!='Add') ? $state_name : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">STD</label>
                                            <input type="text" class="form-control" placeholder="STD" name="std_code"
                                                id="stdCode" value="<?= ($option!='Add') ? $std_code : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Country</label>
                                            <input type="text" class="form-control" placeholder="Country" name="country"
                                                id="Country" value="<?= ($option!='Add') ? $country : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">ISD</label>
                                            <input type="text" class="form-control" placeholder="ISD" name="isd_code"
                                                id="isdCode" value="<?= ($option!='Add') ? $isd_code : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Phone</label>
                                            <input type="text" class="form-control" placeholder="Phone" name="phone_no"
                                                id="phoneNo" value="<?= ($option!='Add') ? $phone_no : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Fax</label>
                                            <input type="text" class="form-control" placeholder="Fax" name="fax_no"
                                                id="faxNo" value="<?= ($option!='Add') ? $fax_no : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile</label>
                                            <input type="text" class="form-control" placeholder="Mobile"
                                                name="mobile_no" id="mobileNo"
                                                value="<?= ($option!='Add') ? $mobile_no : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">E-Mail</label>
                                            <input type="email" class="form-control" placeholder="Email" name="email_id"
                                                id="emailId" value="<?= ($option!='Add') ? $email_id : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                        <div class="col-md-4 float-start px-2 mb-1">
                                            <label class="d-inline-block w-100 mb-2 lbl-mn">GST</label>
                                            <input type="text" class="form-control" placeholder="GST" name="client_gst"
                                                id="clientGst" value="<?= ($option!='Add') ? $client_gst : '' ?>" <?php echo $redokadd ?>/>
                                        </div>
                                    </div>
                                    <div class="bdr-top d-inline-block w-100 py-1">
                                        <div class="d-inline-block w-100 mb-1 pb-0">
                                            <div class="col-md-4 float-start px-2 mb-1 position-relative">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Attn Code [
                                                    Communication Attention ]</label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" name="corrp_attn_codeV"
                                                        id="corrpAttnCodeV" placeholder="Initial Name"
                                                        onChange="fetchData(this, 'corrp_attn_code', ['corrpAttnCode', 'attentionName', 'attentionName','Designation','Sex','billingAttnCode','billAttentionName','billDesignation','billSex','billAttnPhoneNo','billAttnFaxNo','billAttnMobileNo','billAttnEmailId'], ['corrp_attn_code', 'attention_name','designation','sex','corrp_attn_code', 'attention_name','designation','sex','phone_no','fax_no','mobile_no','email_id'], 'corrp_attn_code')"
                                                        required
                                                        value="<?= ($option!='Add') ? $data5['corrp_attn_code'] : '' ?>" <?php echo $redokadd ?>/>
                                                        <input type="hidden"  name="corrp_attn_code"  id="corrpAttnCode" value="<?= ($option!='Add') ? $data5['corrp_attn_code'] : '' ?>"/>
                                                    <i class="fa fa-binoculars icn-vw icn-vw2"
                                                        onClick="showData('corrp_attn_code', 'display_id=4075&client_code=@clientCode', 'corrpAttnCodeV', [ 'corrpAttnCode','attentionName','Designation','Sex','billingAttnCode','billAttentionName','billDesignation','billSex','billAttnPhoneNo','billAttnFaxNo','billAttnMobileNo','billAttnEmailId'], ['corrp_attn_code', 'attention_name','designation','sex','corrp_attn_code', 'attention_name','designation','sex','phone_no','fax_no','mobile_no','email_id'], 'corrp_attn_code','','1','<?= ($option=='Edit') ? $data5['client_code'] : 'clientCode'?>','')"
                                                        data-toggle="modal" data-target="#lookup"
                                                        style="display:<?php if($option=='View'){ echo $redv;} ?>"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-4 float-start px-2 mb-1">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Name <b
                                                        class="text-danger">*</b></label>
                                                <input type="text" class="form-control" placeholder="Name" required
                                                    name="attention_name" id="attentionName"
                                                    value="<?= ($option!='Add') ? $attention_name : '' ?>" <?php echo $redokadd ?>/>
                                            </div>
                                            <div class="col-md-4 float-start px-2 mb-1">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Designation</label>
                                                <input type="text" class="form-control" placeholder="Designation"
                                                    name="designation" id="Designation"
                                                    value="<?= ($option!='Add') ? $designation : '' ?>" <?php echo $redokadd ?>/>
                                            </div>
                                            <div class="col-md-3 float-start px-2 mb-1">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Gender</label>
                                                <input type="text" class="form-control" placeholder="Gender" name="sex"
                                                    id="Sex" value="<?= ($option!='Add') ? $sex : '' ?>" <?php echo $redokadd ?>/>
                                            </div>

                                            <div class="col-md-3 float-start px-2 mb-1">
                                                <label class="d-inline-block w-100 mb-1 lbl-mn">First Date</label>
                                                <input type="text" class="form-control datepicker" placeholder="First Date"
                                                    name="first_activity_date" id="firstActivityDate"
                                                    value="<?= ($option!='Add') ? ($data5['first_activity_date']!='0000-00-00')? date('d-m-Y', strtotime($data5['first_activity_date'])) :'': '' ?>" onblur="make_date(this)" <?php echo $disview ?>/>
                                            </div>
                                            <div class="col-md-3 float-start px-2 mb-1">
                                                <label class="d-inline-block w-100 mb-1 lbl-mn">Fixed For</label>
                                                <input type="text" class="form-control" placeholder="Fixed For"
                                                    name="first_fixed_for" id="firstFixedFor"
                                                    value="<?= ($option!='Add') ? $data5['first_fixed_for'] : '' ?>"<?php echo $disview ?> />
                                            </div>
                                            <div class="col-md-3 float-start px-2 mb-1">
                                                <label class="d-inline-block w-100 mb-1 lbl-mn">&nbsp; </label>
                                                <input type="text" class="form-control" name="new_matter" id="newMatter"
                                                    value="<?= ($option!='Add') ? $new_matter : '' ?>" readonly />
                                            </div>
                                            
                                        </div>
                                        <input type="hidden" class="form-control" name="attn_phone_no"
                                            id="attnPhoneNo" />
                                        <input type="hidden" class="form-control" name="attn_fax_no" id="attnFaxNo" />
                                        <input type="hidden" class="form-control" name="attn_mobile_no"
                                            id="attnMobileNo" />
                                        <input type="hidden" class="form-control" name="attn_email_id"
                                            id="attnEmailId" />
                                    </div>
                                </div>
                            </div>

                            <!-- 2nd Section Start-->
                            <div id="oSide" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1 class="col-md-12">Other Side</h1>
                                </div>
                                <!-- End Page Title -->
                                <div
                                    class="tbl-sec d-inline-block w-100 bg-white p-3 pt-2 position-relative mt-0 Scrltbl_lrg">
                                    <table class="table border-0" id="otherSideTable">
                                        <tbody>
                                            <tr class="fs-14">
                                                <th class="border cbxHead"><span>&nbsp;</span></th>
                                                <th class="border w-180"><span>Name</span></th>
                                                <th class="border w-180"><span>Address 1</span></th>
                                                <th class="border w-180"><span>Address 2</span></th>
                                                <th class="border w-180"><span>Address 3</span></th>
                                                <th class="border w-180"><span>Address 4</span></th>
                                                <th class="border w-180"><span>City</span></th>
                                                <th class="border w-180"><span>Pin Code</span></th>
                                                <th class="border w-180"><span>State Name</span></th>
                                                <th class="border w-180"><span>Country</span></th>
                                                <th class="border w-180"><span>ISD Code</span></th>
                                                <th class="border w-180"><span>STD Code</span></th>
                                                <th class="border w-180"><span>Phone</span></th>
                                                <th class="border w-180"><span>Mobile</span></th>
                                                <th class="border w-180"><span>Email Id</span></th>
                                                <th class="border w-180"><span>Fax</span></th>
                                                <th class="border w-180"><span>Company Name</span></th>
                                                <th class="border w-180"><span>Designation</span></th>
                                                <th class="border w-180"><span>Office Phone</span></th>
                                            </tr>
                                           
                                            <input type="hidden" name="other_side_count" id="other_side_count"
                                                value="<?php if($count>0){ echo $count+1;}else echo '1'; ?>" />
                                            <?php $key2=0;if(($option!='Add') ? $count : 1 >0)
											{
											
												foreach($data6 as $key=>  $value){ $key++;?>
                                                
                                            <tr class="fs-14 border-0">
                                                <td class="border cbxtd text-center"><span><input type="checkbox"
                                                            class="" name="other_side_ok_ind<?php echo $key?>"
                                                            id="other_side_ok_ind<?php echo $key?>" size="1" value="Y"
                                                            onclick="otherside_delRow('<?php echo $key;?>')"
                                                            checked <?php echo $disview ?>/></span><input type="hidden"
                                                        name="other_side_record_code<?php echo $key ?>" value="1"/>

                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_name<?php echo $key?>" id="otherSideName"
                                                            value="<?= ($option!='Add') ? $value['name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_address_line_1<?php echo $key?>"
                                                            id="other_sideAddressLine1"
                                                            value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_address_line_2<?php echo $key?>"
                                                            id="other_sideAddressLine2"
                                                            value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_address_line_3<?php echo $key?>"
                                                            id="other_sideAddressLine3"
                                                            value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_address_line_4<?php echo $key?>"
                                                            id="other_sideAddressLine4"
                                                            value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_city<?php echo $key?>" id="otherSideCity"
                                                            value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_pin_code<?php echo $key?>"
                                                            id="otherSidePinCode"
                                                            value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_state_name<?php echo $key?>"
                                                            id="otherSideStateName"
                                                            value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_country<?php echo $key?>"
                                                            id="otherSideCountry"
                                                            value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_isd_code<?php echo $key?>"
                                                            id="otherSideIsdCode"
                                                            value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_std_code<?php echo $key?>"
                                                            id="otherSideStdCode"
                                                            value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_phone_no<?php echo $key?>"
                                                            id="otherSidePhoneNo"
                                                            value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" onfocusout="validatePhoneNumber(this)" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_mobile_no<?php echo $key?>"
                                                            id="otherSideMobileNo"
                                                            value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" onfocusout="validatePhoneNumber(this)" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_email_id<?php echo $key?>"
                                                            id="otherSideEmailId"
                                                            value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_fax_no<?php echo $key?>"
                                                            id="otherSideFaxNo"
                                                            value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_company_name<?php echo $key?>"
                                                            id="otherSideCompanyName"
                                                            value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_designation<?php echo $key?>"
                                                            id="otherSideDesignation"
                                                            value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_office_tel<?php echo $key?>"
                                                            value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                            </tr>
                                            <?php $key2=$key2+$key; }
											}
												$key2=$count+1;
												?>
                                            <tr class="fs-14 border-0" id="rowId<?php echo $key2; ?>">
                                                <td class="border cbxtd text-center"><span><input type="checkbox"
                                                            class="" name="other_side_ok_ind<?php echo $key2?>"
                                                            id="other_side_ok_ind<?php echo $key2?>" size="1" value="Y"
                                                            onclick="otherside_delRow('<?php echo $key2;?>')"
                                                            checked <?php echo $disview ?>/></span><input type="hidden"
                                                        name="other_side_record_code<?php echo $key2 ?>" value="1"/>

                                                </td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_name<?php echo $key2?>"
                                                            id="otherSideName" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_address_line_1<?php echo $key2?>"
                                                            id="other_sideAddressLine1" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_address_line_2<?php echo $key2?>"
                                                            id="other_sideAddressLine2" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_address_line_3<?php echo $key2?>"
                                                            id="other_sideAddressLine3" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_address_line_4<?php echo $key2?>"
                                                            id="other_sideAddressLine4" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_city<?php echo $key2?>"
                                                            id="otherSideCity" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_pin_code<?php echo $key2?>"
                                                            id="otherSidePinCode" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_state_name<?php echo $key2?>"
                                                            id="otherSideStateName" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_country<?php echo $key2?>"
                                                            id="otherSideCountry" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_isd_code<?php echo $key2?>"
                                                            id="otherSideIsdCode" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_std_code<?php echo $key2?>"
                                                            id="otherSideStdCode" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_phone_no<?php echo $key2?>"
                                                            id="otherSidePhoneNo" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_mobile_no<?php echo $key2?>"
                                                            id="otherSideMobileNo" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_email_id<?php echo $key2?>"
                                                            id="otherSideEmailId" <?php echo $redokadd ?> /></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_fax_no<?php echo $key2?>"
                                                            id="otherSideFaxNo" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_company_name<?php echo $key2?>"
                                                            id="otherSideCompanyName" <?php echo $redokadd ?>/></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_designation<?php echo $key2?>"
                                                            id="otherSideDesignation" <?php echo $redokadd ?> /></span></td>
                                                <td class="border"><span><input type="text" class="form-control"
                                                            name="other_side_office_tel<?php echo $key2?>" /></span>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                        onclick="related_matters('otherSide')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                </div>
                            </div>
                            <!-- 2nd Section End -->
                            <!-- 3nd Section Start-->
                            <div id="cncl(0)" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Counsel(O)</h1>
                                </div>
                                <!-- End Page Title -->

                                        <div class="col-md-12 mt-0">
                                            <div
                                                class="tbl-sec d-inline-block w-100 bg-white p-3 pt-2 position-relative mt-0 Scrltbl_lrg">
                                                <table class="table border-0" id="counselTable">
                                                    <tbody>
                                                        <tr class="fs-14">
                                                            <td class="border cbxHead"><span>&nbsp;</span></td>
                                                            <th class="border w-350"> <span>Name</span> </th>
                                                            <th class="border w-350"> <span>Address </span> </th>
                                                            <th class="border w-350"> <span>Address 2</span> </th>
                                                            <th class="border w-350"> <span>Address 3</span> </th>
                                                            <th class="border w-350"> <span>Address 4</span> </th>
                                                            <th class="border w-180"> <span>City</span> </th>
                                                            <th class="border w-180"> <span>Pin Code</span> </th>
                                                            <th class="border w-180"> <span>State Name</span> </th>
                                                            <th class="border w-180"> <span>Country</span> </th>
                                                            <th class="border w-180"> <span>ISD Code</span> </th>
                                                            <th class="border w-180"> <span>STD Code</span> </th>
                                                            <th class="border w-180"> <span>Phone</span> </th>
                                                            <th class="border w-180"> <span>Mobile</span> </th>
                                                            <th class="border w-180"> <span>Email Id</span> </th>
                                                            <th class="border w-180"> <span>Fax</span> </th>
                                                            <th class="border w-350"> <span>Commpany Name</span> </th>
                                                            <th class="border w-180"> <span>Designation</span> </th>
                                                            <th class="border w-180"> <span>Office Phone</span> </th>
                                                        </tr>
                                                        <input type="hidden" name="counsel_other_side_count"
                                                            id="counsel_other_side_count"
                                                            value="<?php if($count2>0){ echo $count2+1;}else echo '1'; ?>" />
                                                        <?php $key2=0;if($count2>0)
                                                {
                                                    foreach($data7 as $key=>  $value){ $key++;?>
                                                        <tr class="fs-14 border-0" id="rodId<?php echo $key; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="counsel_other_side_ok_ind<?php echo $key?>"
                                                                        id="counsel_other_side_ok_ind<?php echo $key?>"
                                                                        size="1" value="Y"
                                                                        onclick="counsel_otherside_delRow('<?php echo $key;?>')" checked <?php echo $disview ?>/></span><input
                                                                    type="hidden"
                                                                    name="counsel_other_side_record_code<?php echo $key ?>"
                                                                    value="2" /></td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['name'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_address_line_1<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_address_line_2<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_address_line_3<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_address_line_4<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_city<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_pin_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_state_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_country<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_isd_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_std_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_phone_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_mobile_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_email_id<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_fax_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_company_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_designation<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_office_tel<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                        <?php $key2=$key2+$key; }
                                                }	$key2=$count2+1;
                                                    ?>
                                                        <tr class="fs-14 border-0" id="rowId<?php echo $key2; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="counsel_other_side_ok_ind<?php echo $key2?>"
                                                                        id="counsel_other_side_ok_ind<?php echo $key2?>"
                                                                        size="1" value="Y"
                                                                        onclick="counsel_otherside_delRow('<?php echo $key2;?>')"
                                                                        checked <?php echo $disview ?>/></span><input type="hidden"
                                                                    name="counsel_other_side_record_code<?php echo $key2 ?>"
                                                                    value="2" /></td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_address_line_1<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_address_line_2<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_address_line_3<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_address_line_4<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_city<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_pin_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_state_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_country<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_isd_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_std_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_phone_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_mobile_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_email_id<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_fax_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_company_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_designation<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="counsel_other_side_office_tel<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                                    onclick="related_matters('counsel')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                            </div>
                                        </div>
                            </div>
                            <!-- 3nd Section End -->
                            <!-- 4nd Section Start-->
                            <div id="Advisor(O)" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Advisor(O)</h1>
                                </div>
                                        <div class="col-md-12 mt-0">
                                            <div
                                                class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 Scrltbl_lrg">
                                                <table class="table border-0" id="advisorTable">
                                                    <tbody>
                                                        <tr class="fs-14">
                                                            <td class="border cbxHead"><span>&nbsp;</span></td>
                                                            <th class="border w-350"> <span>Name</span> </th>
                                                            <th class="border w-350"> <span>Address </span> </th>
                                                            <th class="border w-350"> <span>Address 2</span> </th>
                                                            <th class="border w-350"> <span>Address 3</span> </th>
                                                            <th class="border w-350"> <span>Address 4</span> </th>
                                                            <th class="border w-180"> <span>City</span> </th>
                                                            <th class="border w-180"> <span>Pin Code</span> </th>
                                                            <th class="border w-180"> <span>State Name</span> </th>
                                                            <th class="border w-180"> <span>Country</span> </th>
                                                            <th class="border w-180"> <span>ISD Code</span> </th>
                                                            <th class="border w-180"> <span>STD Code</span> </th>
                                                            <th class="border w-180"> <span>Phone</span> </th>
                                                            <th class="border w-180"> <span>Mobile</span> </th>
                                                            <th class="border w-180"> <span>Email Id</span> </th>
                                                            <th class="border w-180"> <span>Fax</span> </th>
                                                            <th class="border w-350"> <span>Commpany Name</span> </th>
                                                            <th class="border w-180"> <span>Designation</span> </th>
                                                            <th class="border w-180"> <span>Office Phone</span> </th>
                                                        </tr>
                                                        <input type="hidden" name="advidor_other_side_count"
                                                            id="advidor_other_side_count"
                                                            value="<?php if($count3>0){ echo $count3+1;}else echo '1'; ?>" />
                                                        <?php $key2=0;if($count3>0)
                                                {
                                                    foreach($data8 as $key=>  $value){ $key++;?>
                                                        <tr class="fs-14 border-0" id="rowId<?php echo $key; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="advisor_other_side_ok_ind<?php echo $key?>"
                                                                        id="advisor_other_side_ok_ind<?php echo $key?>"
                                                                        size="1" value="Y"
                                                                        onclick="advisor_otherside_delRow('<?php echo $key;?>')" checked <?php echo $disview ?>/></span><input
                                                                    type="hidden"
                                                                    name="advidor_other_side_record_code<?php echo $key ?>"
                                                                    value="3" /></td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="advisor_other_side_name<?php echo $key?>"
                                                                            value="<?= ($option!='Add') ? $value['name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_address_line_1<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_address_line_2<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_address_line_3<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_address_line_4<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_city<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_pin_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_state_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_country<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_isd_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_std_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_phone_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_mobile_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_email_id<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_fax_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_company_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_designation<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_office_tel<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                        <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$count3+1;
                                                    ?>
                                                        <tr class="fs-14 border-0" id="<?php echo $key2; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="advisor_other_side_ok_ind<?php echo $key2?>"
                                                                        id="advisor_other_side_ok_ind<?php echo $key2?>"
                                                                        size="1" value="Y"
                                                                        onclick="advisor_otherside_delRow('<?php echo $key2;?>')"
                                                                        checked <?php echo $disview ?>/></span><input type="hidden"
                                                                    name="advisor_other_side_record_code<?php echo $key2 ?>"
                                                                    value="3" /></td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_address_line_1<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_address_line_2<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_address_line_3<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_address_line_4<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_city<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_pin_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_state_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_country<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_isd_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_std_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_phone_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_mobile_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_email_id<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_fax_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_company_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_designation<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_other_side_office_tel<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                                    onclick="related_matters('advisor')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                            </div>
                                        </div>
                            </div>
                            <!-- 4nd Section End-->
                            <!-- 5nd Section Start-->
                            <div id="Advisoronrec(O)" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Adv-on-rec(O)</h1>
                                </div><!-- End Page Title -->
                                        <div class="col-md-12 mt-0">
                                            <div
                                                class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 Scrltbl_lrg">

                                                <table class="table border-0" id="advOnRecTable">
                                                    <tbody>
                                                        <tr class="fs-14">
                                                            <td class="border cbxHead"><span>&nbsp;</span></td>
                                                            <th class="border w-350"> <span>Name</span> </th>
                                                            <th class="border w-350"> <span>Address </span> </th>
                                                            <th class="border w-350"> <span>Address 2</span> </th>
                                                            <th class="border w-350"> <span>Address 3</span> </th>
                                                            <th class="border w-350"> <span>Address 4</span> </th>
                                                            <th class="border w-180"> <span>City</span> </th>
                                                            <th class="border w-180"> <span>Pin Code</span> </th>
                                                            <th class="border w-180"> <span>State Name</span> </th>
                                                            <th class="border w-180"> <span>Country</span> </th>
                                                            <th class="border w-180"> <span>ISD Code</span> </th>
                                                            <th class="border w-180"> <span>STD Code</span> </th>
                                                            <th class="border w-180"> <span>Phone</span> </th>
                                                            <th class="border w-180"> <span>Mobile</span> </th>
                                                            <th class="border w-180"> <span>Email Id</span> </th>
                                                            <th class="border w-180"> <span>Fax</span> </th>
                                                            <th class="border w-350"> <span>Commpany Name</span> </th>
                                                            <th class="border w-180"> <span>Designation</span> </th>
                                                            <th class="border w-180"> <span>Office Phone</span> </th>
                                                        </tr>
                                                        <input type="hidden" name="adv_on_rec_other_side_count"
                                                            id="adv_on_rec_other_side_count"
                                                            value="<?php if($count4>0){ echo $count4+1;}else echo '1'; ?>">
                                                        <?php $key2=0;if($count4>0)
                                                {
                                                
                                                    foreach($data9 as $key=>  $value){ $key++;?>
                                                        <tr class="fs-14 border-0" id="<?php echo $key; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="adv_on_rec_other_side_ok_ind<?php echo $key?>"
                                                                        id="adv_on_rec_other_side_ok_ind<?php echo $key?>"
                                                                        size="1" value="Y"
                                                                        onclick="adv_on_rec_otherside_delRow('<?php echo $key;?>')" checked <?php echo $disview ?>/></span><input
                                                                    type="hidden"
                                                                    name="adv_on_rec_other_side_record_code<?php echo $key ?>"
                                                                    value="4" /></td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="adv_on_rec_other_side_name<?php echo $key?>"
                                                                            value="<?= ($option!='Add') ? $value['name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_address_line_1<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_address_line_2<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_address_line_3<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_address_line_4<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_city<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_pin_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_state_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_country<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_isd_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_std_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_phone_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_mobile_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_email_id<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_fax_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_company_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_designation<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_office_tel<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                        <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$count4+1;
                                                    ?>
                                                        <tr class="fs-14 border-0" id="<?php echo $key2; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="adv_on_rec_other_side_ok_ind<?php echo $key2?>"
                                                                        id="adv_on_rec_other_side_ok_ind<?php echo $key2?>"
                                                                        size="1" value="Y"
                                                                        onclick="adv_on_rec_otherside_delRow('<?php echo $key2;?>')"
                                                                        checked <?php echo $disview ?>/></span><input type="hidden"
                                                                    name="adv_on_rec_other_side_record_code<?php echo $key2 ?>"
                                                                    value="4" /></td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="adv_on_rec_other_side_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_address_line_1<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_address_line_2<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_address_line_3<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_address_line_4<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_city<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_pin_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_state_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_country<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_isd_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_std_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_phone_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_mobile_no<?php echo $key2?>" <?php echo $redokadd ?> /></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_email_id<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_fax_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_company_name<?php echo $key2?>" <?php echo $redokadd ?> /></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_designation<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_other_side_office_tel<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                                    onclick="related_matters('advOnRecTable')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                            </div>
                                        </div>
                            </div>
                            <!-- 5nd Section End-->
                            <!-- 6nd Section Start-->
                            <div id="Intprty(O)" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Int. Party(O)</h1>
                                </div><!-- End Page Title -->
                                        <div class="col-md-12 mt-0">
                                            <div
                                                class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 Scrltbl_lrg">

                                                <table class="table border-0" id="intprtyTable">
                                                    <tbody>
                                                        <tr class="fs-14">
                                                            <td class="border cbxHead"><span>&nbsp;</span></td>
                                                            <th class="border w-350"> <span>Name</span> </th>
                                                            <th class="border w-350"> <span>Address </span> </th>
                                                            <th class="border w-350"> <span>Address 2</span> </th>
                                                            <th class="border w-350"> <span>Address 3</span> </th>
                                                            <th class="border w-350"> <span>Address 4</span> </th>
                                                            <th class="border w-180"> <span>City</span> </th>
                                                            <th class="border w-180"> <span>Pin Code</span> </th>
                                                            <th class="border w-180"> <span>State Name</span> </th>
                                                            <th class="border w-180"> <span>Country</span> </th>
                                                            <th class="border w-180"> <span>ISD Code</span> </th>
                                                            <th class="border w-180"> <span>STD Code</span> </th>
                                                            <th class="border w-180"> <span>Phone</span> </th>
                                                            <th class="border w-180"> <span>Mobile</span> </th>
                                                            <th class="border w-180"> <span>Email Id</span> </th>
                                                            <th class="border w-180"> <span>Fax</span> </th>
                                                            <th class="border w-350"> <span>Commpany Name</span> </th>
                                                            <th class="border w-180"> <span>Designation</span> </th>
                                                            <th class="border w-180"> <span>Office Phone</span> </th>
                                                        </tr>
                                                        <input type="hidden" name="int_party_othher_side_count"
                                                            id="int_party_othher_side_count"
                                                            value="<?php if($count5>0){ echo $count5+1;}else echo '1'; ?>">
                                                        <?php $key2=0;if($count5>0)
                                                {
                                                
                                                    foreach($data10 as $key=>  $value){ $key++;?>
                                                        <tr class="fs-14 border-0" id="rowId<?php echo $key; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="int_party_other_side_ok_ind<?php echo $key?>"
                                                                        id="int_party_other_side_ok_ind<?php echo $key?>"
                                                                        size="1" value="Y"
                                                                        onclick="int_party_otherside_delRow('<?php echo $key;?>')" checked <?php echo $disview ?>/></span><input
                                                                    type="hidden"
                                                                    name="int_party_other_side_record_code<?php echo $key ?>"
                                                                    value="5" /></td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="int_party_other_side_name<?php echo $key?>"
                                                                            value="<?= ($option!='Add') ? $value['name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_address_line_1<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_address_line_2<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_address_line_3<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_address_line_4<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_city<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_pin_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_state_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_country<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_isd_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_std_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_phone_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_mobile_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_email_id<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_fax_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_company_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_designation<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_office_tel<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                        <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$count5+1;
                                                    ?>
                                                        <tr class="fs-14 border-0" id="rowId<?php echo $key2;?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="int_party_other_side_ok_ind<?php echo $key2?>"
                                                                        id="int_party_other_side_ok_ind<?php echo $key2?>"
                                                                        size="1" value="Y"
                                                                        onclick="int_party_otherside_delRow('<?php echo $key2;?>')"
                                                                        checked <?php echo $disview ?> /></span><input type="hidden"
                                                                    name="int_party_other_side_record_code<?php echo $key2 ?>"
                                                                    value="5" /></td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="int_party_other_side_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_address_line_1<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_address_line_2<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_address_line_3<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_address_line_4<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_city<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_pin_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_state_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_country<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_isd_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_std_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_phone_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_mobile_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_email_id<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_fax_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_company_name<?php echo $key2?>" <?php echo $redokadd ?> /></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_designation<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_other_side_office_tel<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                                    onclick="related_matters('intprty')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                            </div>
                                        </div>
                            </div>
                            <!-- 6nd Section End-->
                            <!-- 7nd Section Start-->
                            <div id="Advisor(C)" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Advisor(C)</h1>
                                </div><!-- End Page Title -->
                                        <div class="col-md-12 mt-0">
                                            <div
                                                class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 Scrltbl_lrg">

                                                <table class="table border-0" id="advisorCTable">
                                                    <tbody>
                                                        <tr class="fs-14">
                                                            <td class="border cbxHead"><span>&nbsp;</span></td>
                                                            <th class="border w-350"> <span>Name</span> </th>
                                                            <th class="border w-350"> <span>Address </span> </th>
                                                            <th class="border w-350"> <span>Address 2</span> </th>
                                                            <th class="border w-350"> <span>Address 3</span> </th>
                                                            <th class="border w-350"> <span>Address 4</span> </th>
                                                            <th class="border w-180"> <span>City</span> </th>
                                                            <th class="border w-180"> <span>Pin Code</span> </th>
                                                            <th class="border w-180"> <span>State Name</span> </th>
                                                            <th class="border w-180"> <span>Country</span> </th>
                                                            <th class="border w-180"> <span>ISD Code</span> </th>
                                                            <th class="border w-180"> <span>STD Code</span> </th>
                                                            <th class="border w-180"> <span>Phone</span> </th>
                                                            <th class="border w-180"> <span>Mobile</span> </th>
                                                            <th class="border w-180"> <span>Email Id</span> </th>
                                                            <th class="border w-180"> <span>Fax</span> </th>
                                                            <th class="border w-350"> <span>Commpany Name</span> </th>
                                                            <th class="border w-180"> <span>Designation</span> </th>
                                                            <th class="border w-180"> <span>Office Phone</span> </th>
                                                        </tr>
                                                        <input type="hidden" name="advisor_client_count"
                                                            id="advisor_client_count"
                                                            value="<?php if($count6>0){ echo $count6+1;}else echo '1'; ?>">
                                                        <?php $key2=0;if($count6>0)
                                                {
                                                
                                                    foreach($data11 as $key=>  $value){ $key++;?>
                                                        <tr class="fs-14 border-0">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="advisor_client_ok_ind<?php echo $key?>"
                                                                        id="advisor_client_ok_ind<?php echo $key?>" size="1"
                                                                        value="Y"
                                                                        onclick="advisor_client_delRow('<?php echo $key;?>')"
                                                                        checked <?php echo $disview ?>/></span><input type="hidden"
                                                                    name="advisor_client_record_code<?php echo $key ?>"
                                                                    value="6" /></td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="advisor_client_name<?php echo $key?>"
                                                                            value="<?= ($option!='Add') ? $value['name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_address_line_1<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_address_line_2<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_address_line_3<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_address_line_4<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_city<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_pin_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_state_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_country<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_isd_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_std_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_phone_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_mobile_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_email_id<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_fax_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_company_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_designation<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_office_tel<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                        <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$count6+1;
                                                    ?>
                                                        <tr class="fs-14 border-0">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="advisor_client_ok_ind<?php echo $key2?>"
                                                                        id="advisor_client_ok_ind<?php echo $key2?>"
                                                                        size="1" value="Y"
                                                                        onclick="advisor_client_delRow('<?php echo $key2;?>')"
                                                                        checked <?php echo $disview ?> /></span><input type="hidden"
                                                                    name="advisor_client_record_code<?php echo $key2 ?>"
                                                                    value="6" /></td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="advisor_client_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_address_line_1<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_address_line_2<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_address_line_3<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_address_line_4<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_city<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_pin_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_state_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_country<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_isd_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_std_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_phone_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_mobile_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_email_id<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_fax_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_company_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_designation<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="advisor_client_office_tel<?php echo $key2?>" <?php echo $redokadd ?> /></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                                    onclick="related_matters('advisorC')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>

                                            </div>
                                        </div>
                            </div>
                            <!-- 7nd Section End-->
                            <!-- 8nd Section Start-->
                            <div id="Councel(C)" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Councel(C)</h1>
                                </div><!-- End Page Title -->
                                        <div class="col-md-12 mt-0 p-4">
                                            <input type="hidden" name="matter_counsel_count" value="<?php echo $count7; ?>">
                                            <select class="selectpicker w-50 border-slct" multiple aria-label="Default select example" data-live-search="true" data-placeholder="Associate" name="matter_counsel_code[]" <?php echo $disview ?>>
                                                <?php $iniCode="";$iniCd="";
                                                foreach ($data12 as  $value) 
                                                {
                                                    $iniCode.=",".$value['initial_code'];
                                                }
                                                $iniCd=explode(",",$iniCode);
                                                foreach ($data27 as $key=> $value) {$key++;?>
                                                <option value="<?php echo $value['associate_code']; ?>" <?= (in_array($value['associate_code'], $iniCd)) ? 'selected' : '' ?>><?= $value['associate_name']; ?>   [<?= $value['associate_code'] ?>] </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                            </div>
                            <!-- 8nd Section End-->
                            <!-- 9nd Section Start-->
                            <div id="Advonrec(C)" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Adv-on-rec(C)</h1>
                                </div><!-- End Page Title -->
                                        <div class="col-md-12 mt-0">
                                            <div
                                                class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 Scrltbl_lrg">

                                                <table class="table border-0" id="advOnReCTable">
                                                    <tbody>
                                                        <tr class="fs-14">
                                                            <th class="border cbxHead"><span>&nbsp;</span></th>
                                                            <th class="border w-350">
                                                                <span>Name</span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Address 1</span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Address 2</span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Address 3</span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Address 4</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>City</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Pin Code</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>State Name</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Country</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>ISD Code</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>STD Code</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Phone</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Mobile</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Email Id</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Fax</span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Commpany Name</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Designation</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Office Phone</span>
                                                            </th>
                                                        </tr>
                                                        <input type="hidden" name="adv_rec_client_count"
                                                            id="adv_rec_client_count"
                                                            value="<?php if($count8>0){ echo $count8+1;}else echo '1'; ?>">
                                                        <?php $key2=0;if($count8>0)
                                                {
                                                
                                                    foreach($data13 as $key=>  $value){ $key++;?>
                                                        <tr class="fs-14 border-0" id="rowId<?php echo $key; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="adv_on_rec_client_ok_ind<?php echo $key?>"
                                                                        id="adv_on_rec_client_ok_ind<?php echo $key?>"
                                                                        size="1" value="Y"
                                                                        onclick="adv_on_rec_client_delRow('<?php echo $key;?>')" checked <?php echo $disview ?>/></span><input
                                                                    type="hidden"
                                                                    name="adv_on_rec_client_record_code<?php echo $key ?>"
                                                                    value="8" /></td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="adv_on_rec_client_name<?php echo $key?>"
                                                                            value="<?= ($option!='Add') ? $value['name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_address_line_1<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_address_line_2<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_address_line_3<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_address_line_4<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_city<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_pin_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_state_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_country<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_isd_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_std_code<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_phone_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_mobile_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_email_id<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_fax_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_company_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_designation<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_office_tel<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                        <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$count8+1;
                                                    ?>
                                                        <tr class="fs-14 border-0" id="rowId<?php echo $key2; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="adv_on_rec_client_ok_ind<?php echo $key2?>"
                                                                        id="adv_on_rec_client_ok_ind<?php echo $key2?>"
                                                                        size="1" value="Y"
                                                                        onclick="adv_on_rec_client_delRow('<?php echo $key2;?>')"
                                                                        checked <?php echo $disview ?>/></span><input type="hidden"
                                                                    name="adv_on_rec_client_record_code<?php echo $key2 ?>"
                                                                    value="8" /></td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="adv_on_rec_client_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_address_line_1<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_address_line_2<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_address_line_3<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_address_line_4<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_city<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_pin_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_state_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_country<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_isd_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_std_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_phone_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_mobile_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_email_id<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_fax_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_company_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_designation<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="adv_on_rec_client_office_tel<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                                    onclick="related_matters('advOnRec')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                            </div>
                                        </div>

                            </div>
                            <!-- 9nd Section End-->
                            <!-- 10nd Section Start-->
                            <div id="Int.Party(C)" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Int.Party(C)</h1>
                                </div><!-- End Page Title -->
                                        <div class="col-md-12 mt-0">
                                            <div
                                                class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 Scrltbl_lrg">

                                                <table class="table border-0" id="intpartCTable">
                                                    <tbody>
                                                        <tr class="fs-14">
                                                            <th class="border cbxHead"><span>&nbsp;</span></th>
                                                            <th class="border w-350">
                                                                <span>Name</span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Address </span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Address 2</span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Address 3</span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Address 4</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>City</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Pin Code</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>State Name</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Country</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>ISD Code</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>STD Code</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Phone</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Mobile</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Email Id</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Fax</span>
                                                            </th>
                                                            <th class="border w-350">
                                                                <span>Commpany Name</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Designation</span>
                                                            </th>
                                                            <th class="border w-180">
                                                                <span>Office Phone</span>
                                                            </th>
                                                        </tr>
                                                        <input type="hidden" name="int_party_client_count"
                                                            id="int_party_client_count"
                                                            value="<?php if($count9>0){ echo $count9+1;}else echo '1'; ?>" />
                                                        <?php $key2=0;if($count9>0){
                                                    foreach($data14 as $key=>  $value){ $key++;?>
                                                        <tr class="fs-14 border-0" id="rowId<?php echo $key; ?>">
                                                            <td class="border cbxtd text-center"><span>
                                                                    <input type="checkbox"
                                                                        name="int_party_client_ok_ind<?php echo $key?>"
                                                                        id="int_party_client_ok_ind<?php echo $key2?>"
                                                                        size="1" value="Y"
                                                                        onclick="int_party_client_delRow('<?php echo $key;?>')" checked <?php echo $disview ?>/></span><input
                                                                    type="hidden"
                                                                    name="int_party_client_record_code<?php echo $key ?>"
                                                                    value="9" /></td>
                                                                    <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="int_party_client_name<?php echo $key?>" value="<?= ($option!='Add') ? $value['name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_address_line_1<?php echo $key?>" value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_address_line_2<?php echo $key?>" value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_address_line_3<?php echo $key?>" value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_address_line_4<?php echo $key?>" value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_city<?php echo $key?>" value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_pin_code<?php echo $key?>" value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_state_name<?php echo $key?>" value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_country<?php echo $key?>" value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_isd_code<?php echo $key?>" value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_std_code<?php echo $key?>" value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_phone_no<?php echo $key?>" value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_mobile_no<?php echo $key?>" value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_email_id<?php echo $key?>" value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_fax_no<?php echo $key?>" value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_company_name<?php echo $key?>" value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_designation<?php echo $key?>" value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_office_tel<?php echo $key?>" value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                        <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$count9+1;
                                                    ?>
                                                        <tr class="fs-14 border-0" id="rowID<?php echo $key2; ?>">
                                                            <td class="border cbxtd text-center"><span><input
                                                                        type="checkbox"
                                                                        name="int_party_client_ok_ind<?php echo $key2?>"
                                                                        id="int_party_client_ok_ind<?php echo $key2?>"
                                                                        size="1" value="Y"
                                                                        onclick="int_party_client_delRow('<?php echo $key2;?>')"
                                                                        checked <?php echo $disview ?>/></span><input type="hidden"
                                                                    name="int_party_client_record_code<?php echo $key2 ?>"
                                                                    value="9" />
                                                            </td>
                                                            <td class="border"><span><span><input type="text"
                                                                            class="form-control"
                                                                            name="int_party_client_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_address_line_1<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_address_line_2<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_address_line_3<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_address_line_4<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_city<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_pin_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_state_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_country<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_isd_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_std_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_phone_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_mobile_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_email_id<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_fax_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_company_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_designation<?php echo $key2?>"<?php echo $redokadd ?> /></span>
                                                            </td>
                                                            <td class="border"><span><input type="text" class="form-control"
                                                                        name="int_party_client_office_tel<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                                    onclick="related_matters('inPartyC')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                            </div>
                                        </div>
                            </div>
                            <!-- 10nd Section End-->
                            <div id="Represent(C)" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Represent(C)</h1>
                                </div><!-- End Page Title -->
                                <div class="col-md-12 mt-0">
                                    <div
                                        class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 Scrltbl_lrg">

                                        <table class="table border-0" id="representCTable">
                                            <tbody>
                                                <tr class="fs-14">
                                                    <th class="border cbxHead"><span>&nbsp;</span></th>
                                                    <th class="border w-350">
                                                        <span>Name</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Address 1</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Address 2</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Address 3</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Address 4</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>City</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Pin Code</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>State Name</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Country</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>ISD Code</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>STD Code</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Phone</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Mobile</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Email Id</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Fax</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Commpany Name</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Designation</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Office Phone</span>
                                                    </th>
                                                </tr>
                                                <input type="hidden" name="repr_by_client_count" id="repr_by_client_count"
                                                    value="<?php if($count10>0){ echo $count10+1;}else echo '1'; ?>" />
                                                <?php $key2=0;if($count10>0){
                                                    foreach($data15 as $key=>  $value){ $key++;?>
                                                <tr class="fs-14 border-0" id="rowID<?php echo $key; ?>">
                                                    <td class="border cbxtd text-center"><span>
                                                            <input type="checkbox"
                                                                name="repr_by_client_ok_ind<?php echo $key?>"
                                                                id="repr_by_client_ok_ind<?php echo $key?>" size="1"
                                                                value="Y"
                                                                onclick="repr_by_client_delRow('<?php echo $key;?>')" checked  <?php echo $disview ?>/></span><input
                                                            type="hidden"
                                                            name="repr_by_client_record_code<?php echo $key ?>"
                                                            value="10" /></td>
                                                            <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_name<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['name'] : '' ?>"  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_address_line_1<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>"  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_address_line_2<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>"  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_address_line_3<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>"  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_address_line_4<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>"  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_city<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['city'] : '' ?>"  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_pin_code<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_state_name<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_country<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_isd_code<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_std_code<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_phone_no<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_mobile_no<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_email_id<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['email_id'] : '' ?>"  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_fax_no<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_company_name<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_designation<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_office_tel<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                </tr>
                                                <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$count10+1;
                                                    ?>
                                                <tr class="fs-14 border-0" id="rowId<?php echo $key2; ?>">
                                                    <td class="border cbxtd text-center"><span><input type="checkbox"
                                                                name="repr_by_client_ok_ind<?php echo $key2?>"
                                                                id="repr_by_client_ok_ind<?php echo $key2?>" size="1"
                                                                value="Y"
                                                                onclick="repr_by_client_delRow('<?php echo $key2;?>')"
                                                                checked  <?php echo $disview ?>/></span><input type="hidden"
                                                            name="repr_by_client_record_code<?php echo $key2 ?>"
                                                            value="10" /></td>
                                                            <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_name<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_address_line_1<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_address_line_2<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_address_line_3<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_address_line_4<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_city<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_pin_code<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_state_name<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_country<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_isd_code<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_std_code<?php echo $key2?>"
                                                                value="" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_phone_no<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_mobile_no<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_email_id<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_fax_no<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_company_name<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_designation<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="repr_by_client_office_tel<?php echo $key2?>"
                                                                value=""  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                            onclick="related_matters('reprByClient')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                    </div>
                                </div>
                            </div>
                            <!-- 10nd Section End-->
                            <!-- 10nd Section End-->
                            <div id="referedBy" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Refered By</h1>
                                </div><!-- End Page Title -->
                                <div class="col-md-12 mt-0">
                                    <div
                                        class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 Scrltbl_lrg">

                                        <table class="table border-0" id="referedByTable">
                                            <tbody>
                                                <tr class="fs-14">
                                                    <th class="border cbxHead"><span>&nbsp;</span></th>
                                                    <th class="border w-350">
                                                        <span>Name</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Address 1</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Address 2</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Address 3</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Address 4</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>City</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Pin Code</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>State Name</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Country</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>ISD Code</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>STD Code</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Phone</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Mobile</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Email Id</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Fax</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Commpany Name</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Designation</span>
                                                    </th>
                                                    <th class="border w-180">
                                                        <span>Office Phone</span>
                                                    </th>
                                                </tr>
                                                <input name="ref_by_client_count" id="ref_by_client_count" type="hidden"
                                                    value="<?php if($count11>0){ echo $count11+1;}else echo '1'; ?>" />
                                                <?php $key2=0;if($count11>0){
                                                    foreach($data16 as $key=>  $value){ $key++;?>
                                                <tr class="fs-14 border-0" id="rowID<?php echo $key;?>">
                                                    <td class="border cbxtd text-center"><span>
                                                            <input type="checkbox"
                                                                name="ref_by_client_ok_ind<?php echo $key?>"
                                                                id="ref_by_client_ok_ind<?php echo $key?>" size="1"
                                                                value="Y"
                                                                onclick="ref_by_delRow('<?php echo $key;?>')" checked <?php echo $disview ?>/></span><input
                                                            type="hidden" name="ref_by_client_record_code<?php echo $key ?>"
                                                            value="13" /></td>
                                                            <td class="border"><span><span><input type="text" class="form-control"
                                                                    name="ref_by_client_name<?php echo $key?>"  value="<?= ($option!='Add') ? $value['name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_address_line_1<?php echo $key?>" value="<?= ($option!='Add') ? $value['address_line_1'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_address_line_2<?php echo $key?>" value="<?= ($option!='Add') ? $value['address_line_2'] : '' ?>"  <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_address_line_3<?php echo $key?>" value="<?= ($option!='Add') ? $value['address_line_3'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_address_line_4<?php echo $key?>" value="<?= ($option!='Add') ? $value['address_line_4'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_city<?php echo $key?>" value="<?= ($option!='Add') ? $value['city'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_pin_code<?php echo $key?>"  value="<?= ($option!='Add') ? $value['pin_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_state_name<?php echo $key?>"  value="<?= ($option!='Add') ? $value['state_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_country<?php echo $key?>" value="<?= ($option!='Add') ? $value['country'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_isd_code<?php echo $key?>" value="<?= ($option!='Add') ? $value['isd_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_std_code<?php echo $key?>" value="<?= ($option!='Add') ? $value['std_code'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_phone_no<?php echo $key?>" value="<?= ($option!='Add') ? $value['phone_no'] : '' ?>" <?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_mobile_no<?php echo $key?>" value="<?= ($option!='Add') ? $value['mobile_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_email_id<?php echo $key?>" value="<?= ($option!='Add') ? $value['email_id'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_fax_no<?php echo $key?>" value="<?= ($option!='Add') ? $value['fax_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_company_name<?php echo $key?>" value="<?= ($option!='Add') ? $value['company_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_designation<?php echo $key?>" value="<?= ($option!='Add') ? $value['designation'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_office_tel<?php echo $key?>" value="<?= ($option!='Add') ? $value['office_tel'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                </tr>
                                                <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$count11+1;
                                                    ?>
                                                <tr class="fs-14 border-0" id="rowId<?php echo $key2; ?>">
                                                    <td class="border cbxtd text-center"><span><input type="checkbox"
                                                                name="ref_by_client_ok_ind<?php echo $key2?>"
                                                                id="ref_by_client_ok_ind<?php echo $key2?>" size="1"
                                                                value="Y" onclick="ref_by_delRow('<?php echo $key2;?>')"
                                                                checked <?php echo $disview ?>/></span><input type="hidden"
                                                            name="ref_by_client_record_code<?php echo $key2 ?>" value="6" />
                                                    </td>
                                                    <td class="border"><span><span><input type="text" class="form-control"
                                                                    name="ref_by_client_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_address_line_1<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_address_line_2<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_address_line_3<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_address_line_4<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_city<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_pin_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_state_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_country<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_isd_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_std_code<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_phone_no<?php echo $key2?>"<?php echo $redokadd ?> /></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_mobile_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_email_id<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_fax_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_company_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_designation<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border"><span><input type="text" class="form-control"
                                                                name="ref_by_client_office_tel<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                            onclick="related_matters('refByClient')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                    </div>
                                </div>
                            </div>
                            <!-- 10nd Section End-->
                            <!-- 11nd Section Start-->
                            <div id="Initials" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Initials</h1>
                                </div><!-- End Page Title -->
                                <div class="col-md-12 mt-0">
                                    <div class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2">

                                        <table class="table border-0">
                                            <thead>
                                                <tr class="fs-14">
                                                    <th class="border">
                                                        <span>Initial Code</span>
                                                    </th>
                                                    <th class="border w-350">
                                                        <span>Name</span>
                                                    </th>
                                                    <th class="border cbxHead">
                                                        <span>Select</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <input type="hidden" class="form-control" name="matter_initial_count"
                                                    value="<?= count($data17) ?>" readonly />
                                                <?php foreach ($data17 as $key=> $value) { $key++;?>
                                                <tr class="fs-14 border-0">
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="matter_initial_code<?php echo $key?>"
                                                                value="<?= $value['initial_code'] ?>" readonly <?php echo $redokadd ?>/></span>

                                                    </td>
                                                    <td class="border text-uppercase">
                                                        <span><input type="text" class="form-control"
                                                                name="matter_initial_name<?php echo $key?>"
                                                                value="<?= $value['initial_name'] ?>" readonly <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border cbxtd text-center">
                                                        <span>
                                                            <input type="checkbox"
                                                                name="matter_initial_chk<?php echo $key?>" class="cbx"
                                                                <?php if(!empty($value['fileinfo_initial_code'])) { echo 'checked '; }  ?> <?php echo $disview ?>/>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                
                                            </tbody>
                                        </table>
                                            
                                    </div>
                                </div>
                            </div>
                            <!-- 11nd Section End-->
                            <!--12 Section Start-->
                            <div id="RelatedMatters" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Related Matters</h1>
                                </div><!-- End Page Title -->
                                <div class="col-md-12 mt-0">
                                    <div class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2">

                                        <table class="table border-0" id="relatedMatterTable">
                                            <tbody>
                                                <tr class="fs-14">
                                                    <th class="border cbxHead"><span>&nbsp;</span></th>
                                                    <th class="border">
                                                        <span>File No</span>
                                                    </th>
                                                    <th class="border">
                                                        <span>Matter Description</span>
                                                    </th>
                                                </tr>
                                                <input type="hidden" name="related_matter_count" id="related_matter_count"
                                                    value="<?php echo $count13+1; ?>" />
                                                <?php $key2=0;if($count13>0){
                                                    foreach($data18 as $key=>  $value){ $key++;?>
                                                <tr class="fs-14 border-0">
                                                    <td class="border cbxtd text-center"><span>
                                                            <input type="checkbox"
                                                                name="related_matter_ok_ind<?php echo $key?>"
                                                                id="related_matter_ok_ind<?php echo $key?>" size="1"
                                                                value="Y"
                                                                onclick="related_matter_delRow('<?php echo $key;?>')" checked <?php echo $disview ?>/></span>
                                                    </td>
                                                    <td class="border position-relative"><div class="position-relative"><span> <input type="text" class="form-control"
                                                                name="related_matter_code<?php echo $key?>"
                                                                id="relatedMatterCode<?php echo $key?>"
                                                                onclick="related_matter_delRow('<?php echo $key;?>')"
                                                                onchange="fetchData(this, 'matter_code', ['relatedMatterCode<?php echo $key?>', 'relatedMatterDescription<?php echo $key?>'], ['matter_code', 'mat_des'],'relatedmatter_code')" value="<?= ($option!='Add') ? $value['related_matter_code'] : '' ?>" <?php echo $redokadd ?>/>
                                                                <i class="fa fa-binoculars icn-vw icn-vw2"
                                                                id="matterBinocular"
                                                                onclick="showData('matter_code', '<?= '4204' ?>', 'relatedMatterCode<?php echo $key?>', [ 'relatedMatterCode<?php echo $key?>','relatedMatterDescription<?php echo $key?>'], ['matter_code','mat_des'],'relatedmatter_code');"
                                                                data-toggle="modal" data-target="#lookup" <?php if($option=='View'){?>style="display:<?php echo $redv ?>" <?php } ?>></i>
                                                        </span></div>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="related_matter_description<?php echo $key?>"
                                                                id="relatedMatterDescription<?php echo $key?>"
                                                                value="<?= $value['matterdesc'] ?>"
                                                                readonly <?php echo $redokadd ?>/></span>
                                                    </td>
                                                </tr>
                                                <?php  $key2=$key2+$key; }
                                                }
                                                    $key2=$key2+1;
                                                    ?>
                                                <tr class="fs-14 border-0" id="rowId<?php echo $key2 ?>">
                                                    <td class="border position-relative"><span>
                                                            <input type="checkbox" class="NwCbx_tbl"
                                                                name="related_matter_ok_ind<?php echo $key2?>"
                                                                id="related_matter_ok_ind<?php echo $key2?>" size="1"
                                                                value="Y"
                                                                onclick="related_matter_delRow('<?php echo $key2;?>')"
                                                                checked <?php echo $disview ?>/></span>
                                                    </td>
                                                    <td class="border position-relative"><div class="position-relative"><span>
                                                            <input type="text" class="form-control"
                                                                name="related_matter_code<?php echo $key2?>"
                                                                id="relatedMatterCode<?php echo $key2?>"
                                                                onclick="related_matter_delRow('<?php echo $key2;?>')"
                                                                onchange="fetchData(this, 'matter_code', ['relatedMatterCode<?php echo $key2?>', 'relatedMatterDescription<?php echo $key2?>'], ['matter_code', 'mat_des'],'relatedmatter_code')" <?php echo $redokadd ?>/>
                                                            <i class="fa fa-binoculars icn-vw icn-vw2"
                                                                id="matterBinocular"
                                                                onclick="showData('matter_code', '<?= '4204' ?>', 'relatedMatterCode<?php echo $key2?>', [ 'relatedMatterCode<?php echo $key2?>','relatedMatterDescription<?php echo $key2?>'], ['matter_code','mat_des'],'relatedmatter_code');"
                                                                data-toggle="modal" data-target="#lookup" <?php if($option=='View'){?>style="display:<?php echo $redv ?>" <?php } ?>></i>
                                                        </span></div>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="related_matter_description<?php echo $key2?>"
                                                                id="relatedMatterDescription<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                            onclick="related_matters('relatedMatter')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                    </div>
                                </div>
                            </div>
                            <!-- 12nd Section End-->
                            <!-- 13nd Section Start-->
                            <div id="Org.Rec" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Org.Rec</h1>
                                </div><!-- End Page Title -->
                                <div class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 ScrltblMn">

                                    <table class="table border-0" id="orgMatter">
                                        <tbody>
                                            <tr class="fs-14">
                                                <th class="border cbxHead"><span>&nbsp;</span></th>
                                                <th class="border">
                                                    <span>Record Desc</span>
                                                </th>
                                                <th class="border">
                                                    <span>Remarks</span>
                                                </th>
                                                <th class="border">
                                                    <span>File Location</span>
                                                </th>
                                                <th class="border">
                                                    <span>Received On </span>
                                                </th>
                                                <th class="border">
                                                    <span>Return On</span>
                                                </th>
                                            </tr>
                                            <input type="hidden" name="matter_org_count" id="matter_org_count"
                                                value="<?php if($count14>0){ echo $count14+1;}else echo '1'; ?>" />
                                            <?php $key2=0;if($count14>0){
                                                    foreach($data19 as $key=>  $value){ $key++;?>
                                            <tr class="fs-14 border-0" id="rowID">
                                                <td class="border cbxtd text-center"><span>
                                                        <input type="checkbox" name="matter_org_ok_ind<?php echo $key?>"
                                                            id="matter_org_ok_ind<?php echo $key?>" size="1" value="Y"
                                                            onclick="matter_org_delRow('<?php echo $key;?>')" checked <?php echo $disview ?> /></span><input
                                                        type="hidden" name="matter_org_record_code<?php echo $key ?>"
                                                        value="" /></td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control"
                                                            name="matter_org_record_desc<?php echo $key?>"
                                                            value="<?= ($option!='Add') ? $value['record_desc'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control"
                                                            name="matter_org_remarks<?php echo $key?>"
                                                            value="<?= ($option!='Add') ? $value['remarks'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control"
                                                            name="matter_org_filelocation<?php echo $key?>"
                                                            value="<?= ($option!='Add') ? $value['file_location'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control datepicker"
                                                            name="matter_org_receivedon<?php echo $key?>"
                                                            value="<?= ($option!='Add') ? date('d-m-Y',strtotime($value['receive_date'])) : '' ?>" onblur="make_date(this)" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control datepicker"
                                                            name="matter_org_returnon<?php echo $key?>"
                                                            value="<?= ($option!='Add') ? date('d-m-Y',strtotime($value['return_date'])) : '' ?>" onblur="make_date(this)" <?php echo $redokadd ?>/></span>
                                                </td>
                                            </tr>
                                            <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$key2+1;
                                                    ?>
                                            <tr class="fs-14 border-0">
                                                <td class="border cbxtd text-center"><span>
                                                        <input type="checkbox" name="matter_org_ok_ind<?php echo $key2?>"
                                                            id="matter_org_ok_ind<?php echo $key2?>" size="1" value="Y"
                                                            onclick="matter_org_delRow('<?php echo $key2;?>')"
                                                            checked <?php echo $disview ?>/></span><input type="hidden"
                                                        name="matter_org_record_code<?php echo $key2 ?>" value="" /></td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control"
                                                            name="matter_org_record_desc<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control"
                                                            name="matter_org_remarks<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control"
                                                            name="matter_org_filelocation<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control datepicker"
                                                            name="matter_org_receivedon<?php echo $key2?>" onblur="make_date(this)" <?php echo $redokadd ?>/></span>
                                                </td>
                                                <td class="border">
                                                    <span><input type="text" class="form-control datepicker"
                                                            name="matter_org_returnon<?php echo $key2?>" onblur="make_date(this)" <?php echo $redokadd ?>/></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                        onclick="related_matters('orgMatter')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                </div>
                            </div>
                            <!-- 13nd Section End-->
                            <!-- 14nd Section Start-->
                            <div id="Case_Nos" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Case Nos</h1>
                                </div><!-- End Page Title -->
                                <div class="col-md-12 mt-0">
                                    <div class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2">

                                        <table class="table border-0" id="caseNoTable">
                                            <tbody>
                                                <tr class="fs-14">
                                                    <th class="border">
                                                        <span>Case No</span>
                                                    </th>
                                                    <th class="border">
                                                        <span>Subject Description</span>
                                                    </th>
                                                </tr>
                                                <input type="hidden" name="case_no_count" id="case_no_count"
                                                    value="<?php if($count15>0){ echo $count15+1;}else echo '1'; ?>" />
                                                <?php $key2=0;if($count15>0){
                                                    foreach($data20 as $key=>  $value){ $key++;?>
                                                <tr class="fs-14 border-0" id="rowID<?php echo $key; ?>">
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="other_case_no<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['case_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="other_subject<?php echo $key?>"
                                                                value="<?= ($option!='Add') ? $value['subject_desc'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                </tr>
                                                <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$key2+1;
                                                    ?>
                                                <tr class="fs-14 border-0" id="rowId<?php echo $key2; ?>">
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="other_case_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                    <td class="border">
                                                        <span><input type="text" class="form-control"
                                                                name="other_subject<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                            onclick="related_matters('caseNo')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                    </div>
                                </div>
                            </div>
                            <!-- 14nd Section End-->
                            <!-- 15nd Section Start-->
                            <div id="Details" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Details</h1>
                                </div><!-- End Page Title -->
                                        <div class="col-md-12 mt-2">
                                            <textarea rows="5" class="form-control"
                                                name="case_details" <?php echo $redokadd ?>><?= ($option=="Edit") ? isset($data21['case_details'])?$data21['case_details'] :'' : '' ?></textarea>
                                        </div>
                            </div>
                            <!-- 15nd Section End-->
                            <!-- 16nd Section Start-->
                            <div id="Cheques" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Cheques</h1>
                                </div><!-- End Page Title -->
                                        <div class="col-md-12 mt-2">
                                            <div
                                                class="tbl-sec d-inline-block w-100 bg-white p-3 position-relative mt-2 ScrltblMn">

                                                <table class="table border-0" id="chequesTable">
                                                    <tbody>
                                                        <tr class="fs-14">
                                                            <th class="border">
                                                                <span>Cheque No</span>
                                                            </th>
                                                            <th class="border">
                                                                <span>Cheque Date</span>
                                                            </th>
                                                            <th class="border">
                                                                <span>Bank Name</span>
                                                            </th>
                                                            <th class="border">
                                                                <span>Amount </span>
                                                            </th>
                                                        </tr>
                                                        <input type="hidden" id="cheques_count" name="cheques_count"
                                                            value="<?php if($count17>0){ echo $count17+1;}else echo '1'; ?>" />
                                                        <?php $key2=0;if($count17>0){
                                                        foreach($data22 as $key=>  $value){ $key++;?>
                                                        <tr class="fs-14 border-0" id="rowId<?php echo $key; ?>">
                                                            <td class="border">
                                                                <span><input type="text" class="form-control"
                                                                        name="matter_instrument_no<?php echo $key?>" id="matter_instrument_no<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['instrument_no'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border">
                                                                <span><input type="text" class="form-control datepicker"
                                                                        name="matter_instrument_dt<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? date_conv($value['instrument_dt']) : '' ?>" onblur="make_date(this)" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border">
                                                                <span><input type="text" class="form-control"
                                                                        name="matter_bank_name<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['bank_name'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border">
                                                                <span><input type="text" class="form-control"
                                                                        name="matter_instr_amt<?php echo $key?>"
                                                                        value="<?= ($option!='Add') ? $value['instr_amt'] : '' ?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                        <?php $key2=$key2+$key; }
                                                }
                                                    $key2=$key2+1;
                                                    ?>
                                                        <tr class="fs-14 border-0" id="rowId<?php echo $key2; ?>">
                                                            <td class="border">
                                                                <span><input type="text" class="form-control"
                                                                        name="matter_instrument_no<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border">
                                                                <span><input type="text" class="form-control datepicker"
                                                                        name="matter_instrument_dt<?php echo $key2?>" onblur="make_date(this)" <?php echo $disview ?>/></span>
                                                            </td>
                                                            <td class="border">
                                                                <span><input type="text" class="form-control"
                                                                        name="matter_bank_name<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                            <td class="border">
                                                                <span><input type="text" class="form-control"
                                                                        name="matter_instr_amt<?php echo $key2?>" <?php echo $redokadd ?>/></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <a class="btn btn-primary cstmBtn mt-3 ms-2"
                                                    onclick="related_matters('cheques')" <?php if($option=='View'){?>style="pointer-events:<?php echo $redv ?>" <?php } ?>>Add</a>
                                            </div>
                                        </div>
                            </div>
                            <!-- 16nd Section End-->
                            <!-- 16nd Section Start-->
                            <div id="bill_addr" class="frms-sec mt-2 bg-white d-none">
                                <div class="pagetitle py-3 px-3 mb-0 d-inline-block w-100">
                                    <h1>Billing Address / Attention</h1>
                                </div><!-- End Page Title -->
                                        <div class="d-inline-block w-100">
                                            <div class="col-md-4 float-start px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Addr Code <i>[Billing
                                                        Address]</i></label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" name="billing_addr_code"
                                                        id="billingAddrCode"
                                                        onChange="fetchData(this, 'bill_addr_code', ['billingAddrCode', 'bAddressLine1', 'bAddressLine2','bAddressLine3','bCity','bPinCode','bStateName','bIsdCode','bCountry','bStdCode','bPhoneNo','bFaxNo','bMobileNo','bEmailId'], ['client_name','address_line_1','address_line_2','address_line_3','city','pin_code','state_name','country','isd_code','std_code','phone_no','fax_no','mobile_no','email_id','bill_addr_code')"
                                                        value="<?= ($option!='Add') ? $data5['billing_addr_code']: '' ?>" <?php echo $disview ?>/>
                                                    <i class="fa fa-binoculars icn-vw icn-vw2"
                                                        onClick="showData('bill_addr_code', 'display_id=4076&client_code=@clientCode', 'billingAddrCode', [ 'billingAddrCode','bAddressLine1','bAddressLine2','bAddressLine3','bCity','bPinCode','bStateName','bIsdCode','bCountry','bStdCode','bPhoneNo','bFaxNo','bMobileNo','bEmailId'], ['client_name','address_line_1','address_line_2','address_line_3','city','pin_code','state_name','country','isd_code','std_code','phone_no','fax_no','mobile_no','email_id','bill_addr_code'], 'bill_addr_code','','1','<?= ($option=='Edit') ? $data5['client_code'] : 'clientCode'?>','')"
                                                        data-toggle="modal" data-target="#lookup" <?php if($option=='View'){?>style="display:<?php echo $redv ?>" <?php } ?>></i>
                                                </div>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Line# 1</label>
                                                <input type="text" class="form-control" placeholder="Line# 1"
                                                    name="b_address_line_1" id="bAddressLine1"
                                                    value="<?= ($option!="Add") ? $address_line_1: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Line# 2</label>
                                                <input type="text" class="form-control" placeholder="Line# 2"
                                                    name="b_address_line_2" id="bAddressLine2"
                                                    value="<?= ($option!="Add") ? $address_line_2: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Line# 3</label>
                                                <input type="text" class="form-control" placeholder="Line# 2"
                                                    name="b_address_line_3" id="bAddressLine3"
                                                    value="<?= ($option!="Add") ? $address_line_3: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Line# 4</label>
                                                <input type="text" class="form-control" placeholder="Line# 4"
                                                    name="b_address_line_4" id="bAddressLine4"
                                                    value="<?= ($option!="Add") ? $address_line_4: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">City</label>
                                                <input type="text" class="form-control" placeholder="City" name="b_city"
                                                    id="bCity" value="<?= ($option!="Add") ? $city: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Pin</label>
                                                <input type="text" class="form-control" placeholder="Pin" name="b_pin_code"
                                                    id="bPinCode" value="<?= ($option!="Add") ? $pin_code: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">State</label>
                                                <input type="text" class="form-control" placeholder="State"
                                                    name="b_state_name" id="bStateName"
                                                    value="<?= ($option!="Add") ? $state_name: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">ISD</label>
                                                <input type="text" class="form-control" placeholder="ISD" name="b_isd_code"
                                                    id="bIsdCode" value="<?= ($option!="Add") ? $isd_code: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Country</label>
                                                <input type="text" class="form-control" placeholder="Country"
                                                    name="b_country" id="bCountry"
                                                    value="<?= ($option!="Add") ? $country: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">STD</label>
                                                <input type="text" class="form-control" placeholder="STD" name="b_std_code"
                                                    id="bStdCode" value="<?= ($option!="Add") ? $std_code: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Phones</label>
                                                <input type="text" class="form-control" placeholder="Phones"
                                                    name="b_phone_no" id="bPhoneNo"
                                                    value="<?= ($option!="Add") ? $phone_no: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Fax</label>
                                                <input type="text" class="form-control" placeholder="Fax" name="b_fax_no"
                                                    id="bFaxNo" value="<?= ($option!="Add") ? $fax_no: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile</label>
                                                <input type="text" class="form-control" placeholder="Mobile"
                                                    name="b_mobile_no" id="bMobileNo"
                                                    value="<?= ($option!="Add") ? $mobile_no: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                                                <input type="email" class="form-control" placeholder="Email"
                                                    name="b_email_id" id="bEmailId"
                                                    value="<?= ($option!="Add") ? $email_id: ''?>" <?php echo $redokadd ?>>
                                            </div>
                                        </div>
                                        <div class="d-inline-block w-100">
                                            <div class="d-inline-block w-100">
                                                <div class="col-md-4 float-start px-2 mb-3">
                                                    <label class="d-inline-block w-100 mb-2 lbl-mn">Attn Code
                                                        <i>[Billing Attention]</i></label>
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control" name="billing_attn_code"
                                                            id="billingAttnCode"
                                                            onChange="fetchData(this, 'bill_attn_code', ['billingAttnCode', 'billAttentionName', 'billDesignation','billSex','billAttnPhoneNo','billAttnFaxNo','billAttnMobileNo','billAttnEmailId'], ['bill_attn_code', 'attention_name','designation','sex','phone_no','fax_no','mobile_no','email_id'], 'bill_attn_code')"
                                                            value="<?= ($option!="Add") ? $data5['billing_attn_code'] : ''?>" <?php echo $disview ?>/>
                                                        <i class="fa fa-binoculars icn-vw icn-vw2"
                                                            onClick="showData('bill_attn_code', 'display_id=4077&client_code=@clientCode', 'billingAttnCode', ['billingAttnCode','billAttentionName','billDesignation','billSex','billAttnPhoneNo','billAttnFaxNo','billAttnMobileNo','billAttnEmailId'], ['bill_attn_code','attention_name','designation','sex','phone_no','fax_no','mobile_no','email_id'], 'bill_attn_code','','1','<?= ($option=='Edit') ? $data5['client_code'] : 'clientCode'?>','')"
                                                            data-toggle="modal" data-target="#lookup" <?php if($option=='View'){?>style="display:<?php echo $redv ?>" <?php } ?>></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Name</label>
                                                <input type="text" class="form-control" placeholder="Name"
                                                    name="bill_attention_name" id="billAttentionName"
                                                    value="<?= ($option!="Add") ? $attention_name : '' ?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Desig</label>
                                                <input type="text" class="form-control" placeholder="Desig"
                                                    name="bill_designation" id="billDesignation"
                                                    value="<?= ($option!="Add") ? $designation : '' ?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Gender</label>
                                                <input type="text" class="form-control" placeholder="Gender" name="bill_sex"
                                                    id="billSex" value="<?= ($option!="Add") ? $sex : '' ?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Phone</label>
                                                <input type="text" class="form-control" placeholder="Phone"
                                                    name="bill_attn_phone_no" id="billAttnPhoneNo"
                                                    value="<?= ($option!="Add") ? $attn_phone_no: '' ?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Fax</label>
                                                <input type="text" class="form-control" placeholder="Fax"
                                                    name="bill_attn_fax_no" id="billAttnFaxNo"
                                                    value="<?= ($option!="Add") ? $attn_fax_no: '' ?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile</label>
                                                <input type="text" class="form-control" placeholder="Mobile"
                                                    name="bill_attn_mobile_no" id="billAttnMobileNo"
                                                    value="<?= ($option!="Add") ? $attn_mobile_no: '' ?>" <?php echo $redokadd ?>>
                                            </div>
                                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-3">
                                                <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                                                <input type="email" class="form-control" placeholder="Email"
                                                    name="bill_attn_email_id" id="billAttnEmailId"
                                                    value="<?= ($option!="Add") ? $attn_email_id: '' ?>" <?php echo $redokadd ?>>
                                            </div>
                                        </div>
                            </div>
                            <!-- 16nd Section End-->
                        </div>
                        <?php if($closePage!=null){$pageClose=$closePage;}else{$pageClose=$_REQUEST['closePage'];} ?>
                        <?php  
                        if($closePage==''){?>
                        <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" <?= $disview; ?>>Save</button>
                        <a href="/sinhaco/master/matter-master?display_id=&menu_id=1015" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Back</a>
                       <?php } else{?>
                        <a class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2" href="javascript:window.open('','_self').close();">Back</a>
                       <?php } ?>
                        
                    </form>
                </div>
        </section>

</main>
<!-- End #main -->
<script>
    <?php  if($closePage==''){?>
        document.getElementById('sidebar').style.display = "block";
	document.getElementById('burgerMenu').style.display = "block";
  <?php   }
    else
    {  $closePage;?>
	document.getElementById('sidebar').style.display = "none";
	document.getElementById('burgerMenu').style.display = "none";
    <?php } ?>
</script>
<?= $this->endSection() ?>