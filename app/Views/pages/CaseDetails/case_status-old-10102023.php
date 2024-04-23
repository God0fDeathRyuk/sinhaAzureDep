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
    <?php endif; ?>
    <?php if (session()->getFlashdata('noted_message') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('noted_message') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('message_not_editable') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('message_not_editable') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<div class="pagetitle w-100 float-start border-bottom pb-1">
    <h1 class="col-md-8 float-start">Case Status <span class="badge rounded-pill bg-dark"><?= (isset($option)) ? ucfirst($option) : '' ?></span> </h1>
    <?php $session = session();
    $accessPermission=explode(",", $session->Accpermission);
    if (isset($option) && !isset($xQry) && isset($data)) { ?>
    <div class="col-md-4 float-end text-end mb-2">
        <a href="<?= $data['requested_url'] ?>" class="btn-bck btn btn-dark me-2">Back</a>
        <?php if ($option == 'add' || $option == 'edit'  || $option == 'copy' || $option == 'letter' || $option == 'open') { ?>
            <button type="button" class="btn-save btn btn-secondary" onclick="submitForm()">Save</button>
        <?php } else if($option == 'delete') { ?>
            <button type="button" class="btn-save btn btn-secondary" onclick="submitForm()">Delete</button>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- End Page Title -->

<section class="section dashboard d-inline-block w-100">
    <?php
    if (!isset($option) && !isset($xQry) && !isset($qry)) { ?>
        <form action="" method="get" id="caseStatus">
            <div class="row">
                <div class="inpt-grp col-md-4 pe-0">
                    <label class="d-block w-100 mb-2">Branch</label>
                    <select class="form-select cstm-inpt">
                        <?php foreach($data['branches'] as $branch) { ?>
                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="inpt-grp col-md-4 pe-0 position-relative">
                    <label class="d-block w-100 mb-2">Serial No</label>
                    <input type="text" class="form-control cstm-inpt" id="SerialNo" onchange="fetchData(this, 'serial_no', ['matterCode', 'matterDesc'], ['matter_code', 'matter_desc'], 'serial_no')" name="serial_no" required />
                    <i class="fa-solid fa-eye inpt-vw" onclick="showData('serial_no', '<?= $displayId['casesrl_help_id'] ?>', 'SerialNo', ['matterCode', 'matterDesc'], ['matter_code', 'matter_desc'], 'serial_no')" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <div class="inpt-grp col-md-4 pe-0"> 
                    <label class="d-block w-100 mb-2">Matter Code</label>
                    <input type="text" class="form-control cstm-inpt" id="matterCode" name="matter_code"  readonly disabled  />
                </div>
                <div class="inpt-grp col-md-12 pe-0 mt-3">
                    <label class="d-block w-100 mb-2">Matter Description</label>
                    <textarea name="" rows="1" class="form-control cstm-inpt" id="matterDesc" name="matter_desc" readonly disabled ></textarea>
                </div>
            </div>
            <div class="w-100 float-start text-start mt-4 top-btn-fld">
            <?php $accessPermission=explode(",", $session->Accpermission); 
                    foreach ($permdata as $value) { ?>
                    
                    <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2 <?= (in_array($value['id'], $accessPermission)) ? 'd-block float-start' : 'd-none'  ?>"  onclick="formOption('/sinhaco/case/status/', '<?php echo strtolower($value['permission_name']) ?>', 'caseStatus')"><?php echo $value['permission_name']; ?></button>
                    
                    <?php } ?>
                <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('/sinhaco/case/status/', 'letter', 'caseStatus')">Letter</button>
                <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('/sinhaco/case/status/', ' view', 'caseStatus')">View</button>
                <button type="reset" class="btn btn-primary cstmBtn mt-0" onclick="resetFields()">Reset</button>
                <button type="submit" class="btn btn-primary cstmBtn mt-0 d-none">Exit</button>
            </div>
        </form>

    <?php } else if(!isset($xQry) && !isset($qry)){ ?>

        <!-- form  -->
        <div class="form" >
            <div class="col-md-12">
                <form action="" method="post" id="caseStatusForm" name="caseStatusForm">
                    <div class="sec1 d-inline-block w-100 border mt-3">
                        <div class="inpt-sec-top d-inline-block w-100 p-3 border-bottom">
                            <div class="inpts col-md-3 float-start px-2 mb-2">
                                <label for="" class="d-block w-100 mb-2">Serial No</label>
                                <input type="text" name="serial_no" class="form-control" readonly style="background: #e9ecef;" value="<?= isset($caseHdrArray['serial_no']) ? $caseHdrArray['serial_no'] : '' ?>" <?= $permission ?>/>
                            </div>
                            <div class="inpts col-md-3 float-start px-2 mb-2">
                                <label for="" class="d-block w-100 mb-2">Activity Date</label>
                                <input type="text" name="activity_date" class="form-control" value="<?= isset($caseHdrArray['activity_date']) ? $caseHdrArray['activity_date'] : '' ?>" onChange="chkActivityDate(this)" <?= $redv ?> onBlur="make_date(this)"/>
                            </div>
                            <div class="inpts col-md-3 float-start px-2 mb-2">
                                <label for="" class="d-block w-100 mb-2">Branch</label>
                                <select class="form-select"  name="branch_code" id="branchCode" <?= $disview ?>>
                                <?php foreach($data['branches'] as $branch) { ?>
                                <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div class="inpts col-md-3 float-start px-2 mb-2">
                                <label for="" class="d-block w-100 mb-2">Prep By</label>
                                <input type="text" class="form-control" name="prepared_by" readonly  value="<?= isset($caseHdrArray['prepared_by']) ? $caseHdrArray['prepared_by'] : '' ?>" />
                            </div>
                            <div class="inpts col-md-3 float-start px-2 mb-2">
                                <label for="" class="d-block w-100 mb-2">Prep On</label>
                                <input type="text" class="form-control" name="prepared_on" readonly  value="<?= isset($caseHdrArray['prepared_on']) ? $caseHdrArray['prepared_on'] : '' ?>" />
                            </div>
                            <div class="inpts col-md-3 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Letter Date</label>
                                <?php if($option =='letter') { ?>
                                <input type="text" class="form-control" name="letter_date" value="<?php if($caseHdrArray['letter_date'] == '00-00-0000' && $option == 'letter') { echo date('d-m-Y'); } ?>" onChange="chkLetterDate(this); make_date(this)" <?= $redLetter ?>/>
                                <?php } else { ?>  
                                <input type="text" class="form-control" name="letter_date" value="<?php isset($caseHdrArray['letter_date']) ? $caseHdrArray['letter_date'] : '' ?>" onChange="chkLetterDate(this); make_date(this)" <?= $redLetter ?>/>
                                <?php } ?>
                            </div>
                            <?php if($option =='letter') { ?>
                                <div class="inpts col-md-3 float-start px-2 mb-2">
                                    <label for="" class="d-block w-100 mb-2">Logo</label>
                                    <select class="form-select" name="logo_print_ind">
                                        <option>Yes</option>
                                        <option>No</option>
                                    </select>
                                </div>
                            <?php } ?>
                            <?= ($option != 'add') ? '<a href="#" class="bdge">'. $caseHdrArray["status_desc"] .'</a>' : '' ?>
                        </div>
                        <div class="mn-inpt-sec d-inline-block w-100 p-3">
                            <div class="all-inpt d-inline-block w-100">
                                <div class="inpts col-md-2 float-start px-2 position-relative">
                                <!-- condition is applied in this field and used onDblClick="show_matter_history2()" function, for abhijit open lookup by history2 and for others history -->
                                    <label for="" class="d-block w-100 mb-2">Matter</label>
                                    <input type="text" class="form-control" name="matter_code" placeholder="search matter" oninput="this.value = this.value.toUpperCase();" id="matterCode" onchange="fetchData(this, 'matter_code', ['referenceDesc', 'clientCode', 'clientName', 'appearFor', 'judgeName', 'courtName', 'matterStatus'], ['reference_desc', 'client_code', 'client_name', 'appearing_for', 'judge_name', 'court', 'status_code'], 'matter_code'); showHistoryBtn(this, 'caseHistory');"  onfocusout="getMatterValue(this)" value="<?= isset($caseHdrArray['matter_code']) ? $caseHdrArray['matter_code'] : '' ?>" <?= $redv ?>/>
                                <?php if($option == 'add' || $option == 'edit') { ?>
                                    <i class="fa-solid fa-eye inpt-vw" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['referenceDesc', 'clientCode', 'clientName', 'appearFor', 'judgeName', 'courtName', 'matterStatus'], ['reference_desc', 'client_code', 'client_name', 'appearing_for', 'judge_name', 'court_name', 'status_code'], 'caseHistory')" data-toggle="modal" data-target="#lookup"></i>
                                <?php } ?>
                                <i class="fa-solid fa-clock inpt-vw histryIcn" id="caseHistory" onclick="showHistory('case/history/matter', 'branch_code=@branchCode&ason_date=<?= date('d-m-Y') ?>&matter_code=@matterCode&matter_desc=@matterDesc&client_code=@clientCode&desc_ind=Y')" title="Show Matter History" data-toggle="modal" data-target="#lookup"></i>
                                <!--  -->
                                </div>
                                <div class="inpts col-md-6 float-start px-2 position-relative mtop">
                                    <input type="text" class="form-control" name="mat_description" placeholder="search matter" readonly oninput="this.value = this.value.toUpperCase()" id="matterDesc"  value="<?= isset($caseHdrArray['full_matter_desc']) ? $caseHdrArray['full_matter_desc'] : '' ?>"/>
                                </div>
                                <div class="inpts col-md-4 float-start px-2">
                                    <label for="" class="d-block w-100 mb-2">Ref No.</label>
                                    <input type="text" class="form-control" name="reference_desc" id="referenceDesc" readonly  placeholder="Ref No" value="<?= isset($caseHdrArray['reference_desc']) ? $caseHdrArray['reference_desc'] : '' ?>" />
                                </div>
                            </div>
                            <div class="all-inpt d-inline-block w-100 mt-2">
                                <div class="inpts col-md-3 float-start px-2">
                                    <label for="" class="d-block w-100 mb-2">Client</label>
                                    <input type="text" class="form-control"  name="client_code"  id="clientCode" oninput="this.value = this.value.toUpperCase()" placeholder="Client" readonly  value="<?= isset($caseHdrArray['client_code']) ? $caseHdrArray['client_code'] : '' ?>" />
                                </div>
                                <div class="inpts col-md-9 float-start px-2 position-relative mtop">
                                    <input type="text" class="form-control" name="clint_name" id="clientName" oninput="this.value = this.value.toUpperCase()" placeholder="Client" readonly  value="<?= isset($caseHdrArray['client_name']) ? $caseHdrArray['client_name'] : '' ?>" />
                                </div>
                            </div>
                            <div class="all-inpt d-inline-block w-100 mt-2">
                                <div class="inpts col-md-3 float-start px-2">
                                    <label for="" class="d-block w-100 mb-2">Court</label>
                                    <input type="text" class="form-control" placeholder="Count" name="court_code" oninput="this.value = this.value.toUpperCase()" id="courtCode" readonly  value="<?= isset($caseHdrArray['court_code']) ? $caseHdrArray['court_code'] : '' ?>" />
                                </div>
                                <div class="inpts col-md-9 float-start px-2 position-relative mtop">
                                    <input type="text" class="form-control" placeholder="Court" name="court_name" oninput="this.value = this.value.toUpperCase()" id="courtName" readonly  value="<?= isset($caseHdrArray['code_desc']) ? $caseHdrArray['code_desc'] : '' ?>" />
                                </div>
                            </div>
                            <div class="all-inpt d-inline-block w-100 mt-2">
                                <div class="inpts col-md-12 float-start px-2">
                                    <textarea name="other_case_desc" id="" rows="2" class="form-control w-100" placeholder="Other case description" <?= $permission ?>><?= isset($caseHdrArray['other_case_desc']) ? $caseHdrArray['other_case_desc'] : '' ?></textarea>
                                </div>
                            </div>
                            <div class="all-inpt d-inline-block w-100 mt-2">
                                <div class="inpts col-md-4 float-start px-2">
                                    <label for="" class="d-block w-100 mb-2">Appear For</label>
                                    <input type="text" class="form-control" placeholder="Count" name="appear_for" id="appearFor" readonly  value="<?= isset($caseHdrArray['appear_for']) ? $caseHdrArray['appear_for'] : '' ?>" />
                                </div>
                                <div class="inpts col-md-4 float-start px-2">
                                    <label for="" class="d-block w-100 mb-2">Fixed For</label>
                                    <textarea name="prev_fixed_for" id="prevFixedFor" rows="2" class="form-control w-100" placeholder="Other case description" <?= $permission ?>><?= isset($caseHdrArray['prev_fixed_for']) ? $caseHdrArray['prev_fixed_for'] : '' ?></textarea> 
                                </div>
                                <div class="inpts col-md-4 float-start px-2">
                                    <label for="" class="d-block w-100 mb-2">Previous Remarks</label>
                                    <textarea name="last_remark" id="lastRemark" rows="2" class="form-control w-100" readonly  placeholder="Other case description" <?= $redv ?>>last remark</textarea>
                                </div>
                            </div>
                            <div class="all-inpt d-inline-block w-100">
                                <div class="inpts col-md-3 float-start px-2">
                                    <label for="" class="d-block w-100 mb-2">Last Date</label>
                                    <input type="text" class="form-control" placeholder="last Date" name="prev_date" id="prevDate" value="<?= isset($caseHdrArray['prev_date']) ? $caseHdrArray['prev_date'] : '' ?>" <?= $redv ?> onBlur="make_date(this)"/>
                                </div>
                                <div class="inpts col-md-3 float-start px-2">
                                    <label for="" class="d-block w-100 mb-2">Forwarding</label>
                                    <select name="forwarding_ind" id="" class="form-select" <?= $permission ?>>
                                    <option value="" <?php if ($option != 'add') if ($caseHdrArray['forwarding_ind'] != 'Y') echo 'selected'; ?>>No</option>
                                    <option value="Y" <?php if ($option != 'add') if ($caseHdrArray['forwarding_ind'] == 'Y') echo 'selected'; ?>>Yes</option>
                                    </select>
                                </div>
                                <div class="inpts col-md-6 float-start px-2 mtop rdobtnall">
                                    <div class="rdolst d-block float-start w-25 me-2">
                                        <input type="radio" id="bilable" name="billable_option" class="cstm-rdo float-start d-block me-1 mt-1" value='Y' <?= ($option != 'add') ? ($caseHdrArray['billable_option'] == 'Y') ? 'checked' : '' : 'checked' ?> <?= $disv ?> />
                                        <label for="bilable" class="d-block w-100">Bilable</label>
                                    </div>
                                    <div class="rdolst d-block float-start w-25 me-2">
                                        <input type="radio" id="prebilable" name="billable_option" class="cstm-rdo float-start d-block me-1 mt-1" value='P' <?php if ($option != 'add') if($caseHdrArray['billable_option'] == 'P'){ echo 'checked';}?> <?= $disv ?> />
                                        <label for="prebilable" class="d-block w-100">Pre-Bilable</label>
                                    </div>
                                    <div class="rdolst d-block float-start w-25 me-2">
                                        <input type="radio" id="nonbilable" name="billable_option" class="cstm-rdo float-start d-block me-1 mt-1" value='N' <?php if ($option != 'add') if($caseHdrArray['billable_option'] == 'N'){ echo 'checked';}?> <?= $disv ?> />
                                        <label for="nonbilable" class="d-block w-100">Non-Bilable</label>
                                    </div>                                
                                </div>
                                <div class="inpts col-md-3 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Instrument Stage</label>
                                    <select name="instrument_ind" id="" class="form-select" <?= $permission ?>>
                                        <option value="" <?php if ($option != 'add') if ($caseHdrArray['instrument_ind'] == 'N') echo 'selected'; ?>>N/A</option>
                                        <option value="I" <?php if ($option != 'add') if($caseHdrArray['instrument_ind'] == 'I') echo 'selected'; ?>>Issued</option>
                                    </select>
                                </div>
                                <div class="inpts col-md-3 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Letter Stage</label>
                                    <select name="stage_ind" id="" class="form-select" <?= $permission ?>>
                                    <option value="" <?php if ($option != 'add') if  ($caseHdrArray['instrument_ind'] == 'N') echo 'selected'; ?>>N/A</option>
                                    <option value="D" <?php if ($option != 'add') if  ($caseHdrArray['instrument_ind'] == 'D') echo 'selected'; ?>>Draft</option>
                                    <option value="F" <?php if ($option != 'add') if  ($caseHdrArray['instrument_ind'] == 'F') echo 'selected'; ?>>Final</option>
                                    <option value="U" <?php if ($option != 'add') if  ($caseHdrArray['instrument_ind'] == 'U') echo 'selected'; ?>>Follow-up</option>
                                    </select>
                                </div>
                                <div class="inpts col-md-3 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Instrument Type</label>
                                    <select name="instrument_code" id="" class="form-select" <?= $disv ?> required> 
                                    <option value="">----------Select----------</option>
                                    <?php foreach($caseHdrArray['instrument_status'] as $instrument_status) { ?> 
                                        <option value="<?php echo $instrument_status['code_code'];?>"  <?php if ($option != 'add') if ($caseHdrArray['instrument_code'] == $instrument_status['code_code']) { echo 'selected';} ?>><?php echo $instrument_status['code_desc'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="inpts col-md-3 float-start px-2 pe-0 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Inst No.</label>
                                    <input type="text" class="form-control" placeholder="Inst No" name="instrument_no" value="<?= ($option == 'add') ? '' : $caseHdrArray['instrument_no'] ?>" <?= $redv ?>/>
                                </div>
                                <div class="inpts col-md-3 float-start px-2 pe-0 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Issue Date</label>
                                    <input type="text" class="form-control" placeholder="Issue Date" name="instrument_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['instrument_date'] ?>" onChange="chkInstDate(this)" <?= $redv ?> onBlur="make_date(this)"/>
                                </div>
                                <div class="inpts col-md-5 float-start px-2 pe-0 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Judge</label>
                                    <input type="text" class="form-control" placeholder="Issue Date" name="judge_name" id="judgeName" value="<?= ($option == 'add') ? '' : $caseHdrArray['judge_name'] ?>" <?= $redv ?>/>
                                </div>
                                <div class="inpts col-md-4 float-start px-2 pe-0 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Next date</label>
                                    <input type="text" class="form-control" placeholder="Next date" name="next_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['next_date'] ?>" onchange="chkNextDate(this)" <?= $redv ?>/>
                                </div>
                                <div class="position-relative intrmde-dts d-inline-block col-md-12 border mt-5 py-2">   
                                    <p class="sectitl text-uppercase">Intermediate dates</p>                        
                                    <div class="inpts w-50 float-start px-2 pe-0 mt-3 position-relative">
                                        <label for="" class="d-block w-100 mb-2">Fixed For</label>
                                        <textarea name="next_fixed_for" id="" rows="2" class="form-control" placeholder="Fixed For" <?= $redv ?>><?= ($option == 'add') ? '' : $caseHdrArray['next_fixed_for'] ?></textarea>
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div class="inpts w-50 float-start px-2 mt-3">
                                        <label for="" class="d-block w-100 mb-2">Remarks</label>
                                        <textarea name="remarks" id="" rows="2" class="form-control" placeholder="Remarks" <?= $redv ?>><?= ($option == 'add') ? '' : $caseHdrArray['remarks'] ?></textarea>
                                    </div>
                                </div>
                                <div class="inpts w-100 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Particulars</label>
                                    <textarea name="letter_body_desc" id="editor" rows="2" class="form-control" placeholder="Particulars" <?= $redv ?>><?= ($option == 'add') ? '' : $caseHdrArray['header_desc'] ?></textarea>
                                </div>
                                <div class="inpts w-100 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Other Remarks</label>
                                    <textarea name="other_body_desc" id="" rows="2" class="form-control" placeholder="Other Remarks" <?= $redv ?>><?= ($option == 'add') ? '' : $caseHdrArray['other_body_desc'] ?></textarea>
                                </div>
                                <div class="inpts w-50 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Footer</label>
                                    <textarea name="footer_desc" id="" rows="1" class="form-control" placeholder="Footer" <?= $redv ?>><?= ($option == 'add') ? '' : $caseHdrArray['footer_desc'] ?></textarea>
                                </div>
                                <div class="inpts w-50 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">CC</label>
                                    <textarea name="cc_desc" id="" rows="1" class="form-control" placeholder="CC" <?= $redv ?>><?= ($option == 'add') ? '' : $caseHdrArray['cc_desc'] ?></textarea>
                                </div>
                                <div class="inpts col-md-4 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Matter Status</label>
                                    <select name="matter_status" id="matterStatus" class="form-select" <?= $disview ?>>
                                    <?php foreach($caseHdrArray['matter_status'] as $matter_status) { ?>   
                                    <option value="<?php echo $matter_status['status_code'];?>" <?php if ($option != 'add') if($matter_status['status_code'] ==  $caseHdrArray['matter_status_code']){ echo 'selected'; }?>><?php echo $matter_status['status_desc'];?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                                <div class="inpts col-md-4 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Status Date</label>
                                    <input type="text" class="form-control" placeholder="Status Date" name="status_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['status_date'] ?>" onBlur="chkStatusDate(this); make_date(this)" <?= $redv ?>/>
                                </div> 
                                <div class="inpts col-md-4 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Alert Date</label>
                                    <input type="date" class="form-control" placeholder="Alert Date" name="alert_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['alert_date'] ?>" <?= $redv ?> onBlur="make_date(this)"/>
                                </div>
                                <div class="inpts col-md-5 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Advocate</label>
                                    <textarea name="signatory" id="" rows="1" class="form-control" placeholder="Advocate" <?= $redv ?>><?= ($option == 'add') ? '' : $caseHdrArray['signatory'] ?></textarea>
                                </div>
                                <div class="inpts col-md-7 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Narration</label>
                                    <textarea name="alert_narration" id="" rows="1" class="form-control" placeholder="Narration" <?= $redv ?>><?= ($option == 'add') ? '' : $caseHdrArray['alert_narration'] ?></textarea>
                                </div>
                                <div class="d-inline-block w-100 px-2 mt-4">
                                    <div class="tbladd nobrdr-icn">
                                        <p class="d-inline-block w-100 px-3 py-2 text-uppercase fw-bold mb-2">Inpocket</p>
                                        <table class="table table-bordered cstmTbl mb-0 w-100">
                                            <tr>
                                                <th class="text-center">&nbsp;</th>
                                                <th class="w-12">Code</th>
                                                <th class="w-15">Name</th>
                                                <th>Acty</th>
                                                <th>Description</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        <tbody id="tbody">
                                            <?php 
                                            $k = 0; 
                                            for ($j=1; $j <= $count['row_inpocket']; $j++) {
                                                $k++;
                                                $activity_code             = isset($caseHdrArray['caseInpocketArray'][$j-1]['activity_code']) ? $caseHdrArray['caseInpocketArray'][$j-1]['activity_code'] : '';
                                                $activity_description      = isset($caseHdrArray['caseInpocketArray'][$j-1]['activity_desc']) ? $caseHdrArray['caseInpocketArray'][$j-1]['activity_desc'] : '';
                                            ?>
                                            <tr>
                                                <td style="width:20px;" id="Ctd<?= $k ?>" onClick="<?php if ($option == 'edit' || $option == "add") { ?>delRow_inpocket(this, <?= $k ?>)<?php } ?>"><input type="hidden" class="cstm-rdo" value="Y" name="del_inpoc<?= $k ?>" id="del_inpoc<?= $k ?>" checked <?= $disview ?> onClick="<?php if ($option == 'edit' || $option == "add") { ?>delRow_inpocket(this, <?= $k ?>)<?php } ?>"></td>
                                                <td><input type="text" class="w-100 form-control" name="asso_code<?php echo $k;?>" value="0000" readonly></td>
                                                <td><input type="text" class="w-100 form-control" name="asso_name<?php echo $k;?>" value="OWN" readonly></td>
                                                <td>
                                                <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                                                    <textarea class="w-100 resze form-control" rows="1" name="asso_acty<?php echo $k;?>" oninput="this.value = this.value.toUpperCase()" id="assoActy<?php echo $k;?>" onchange="fetchData(this, 'activity_code', ['assoDesc<?php echo $k;?>'], ['activity_desc'], 'activity_code')"><?= ($activity_code) ? $activity_code : '' ?></textarea> 
                                                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('activity_code', '<?= $displayId['activity_list_help_id'] ?>', 'assoActy<?= $k ?>', ['assoDesc<?php echo $k;?>'], ['activity_desc'], 'activity_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                                </div>
                                                </td>
                                                <td><textarea class="w-100 resze form-control" rows="1" class="w-100" name="asso_desc<?= $k ?>" id="assoDesc<?php echo $k;?>" readonly><?= $activity_description ?></textarea></td>
                                                <td class="text-center"><?php if(($option == 'add' || $option == 'edit') && $j == $count['row_inpocket']) {?><input type="button" name="Add_inpoc<?php echo $k;?>" value="+" title="Add Row" onClick="addInpocketRow(this,<?php echo $k;?>)"><?php }else if($option == 'add'){?><input type="button" name="Add_inpoc<?php echo $k;?>" value="+" title="Add Row" onClick="addInpocketRow(this,<?php echo $k;?>)"><?php } ?> 
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>   
                                        </table>
                                        <a href="javascript:void(0);" id="inpocketDelete" onclick="deleteRow('tbody', 'InpocketRowNo', 'inpocketDelete', 'addInpocketRow')" class="btn cstmBtn add text-white mt-3">Delete Row</a>
                                    </div>
                                </div>
        
                                <div class="d-inline-block w-100 px-2 mt-4 border-top pt-4">
                                    <div class="tbladd nobrdr-icn">
                                        <p class="d-inline-block w-100 px-3 py-2 text-uppercase fw-bold mb-2">Counsel</p>
                                        <table class="table table-bordered cstmTbl mb-0">
                                            <tr>
                                                <th class="text-center">&nbsp;</th>
                                                <th class="w-12">Cncl</th>
                                                <th class="w-15">Name</th>
                                                <th>Acty</th>
                                                <th>Description</th>
                                                <th>Pay?</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            <tbody id='tbody2'>
                                            <?php 
                                                $m = 0; $counsel_code = $counsel_name = ''; 
                                                for ($j=1; $j<=$count['row_counsel']; $j++)
                                                {
                                                    // echo '<pre>';print_r($caseHdrArray);die;
                // echo '<pre>';print_r($caseHdrArray['caseCounselArray']);die;

                                                $m++;
                                                $counsel_code          = isset($caseHdrArray['caseCounselArray'][$j-1]['counsel_code']) ? $caseHdrArray['caseCounselArray'][$j-1]['counsel_code'] : ''; 
                                                $counsel_name          = isset($caseHdrArray['caseCounselArray'][$j-1]['associate_name']) ? $caseHdrArray['caseCounselArray'][$j-1]['associate_name'] : ''; 
                                                $activity_code         = isset($caseHdrArray['caseCounselArray'][$j-1]['activity_code']) ? $caseHdrArray['caseCounselArray'][$j-1]['activity_code'] : ''; 
                                                $activity_description  = isset($caseHdrArray['caseCounselArray'][$j-1]['activity_desc']) ? $caseHdrArray['caseCounselArray'][$j-1]['activity_desc'] : ''; 
                                                $payable_ind           = isset($caseHdrArray['caseCounselArray'][$j-1]['payable_ind']) ? $caseHdrArray['caseCounselArray'][$j-1]['payable_ind'] : ''; 
                                            ?>
                                            <tr>
                                            <!-- disabled is putted dynamically -->
                                                <td id="Ctd2<?php echo $m; ?>" class="text-center" onClick="<?php if ($option == 'edit' || $option == "add") { ?>delRow_counsel(this, <?= $m ?>)<?php } ?>"><input type="hidden" class="cstm-rdo" value="Y" name="del_counsel<?php echo $m;?>" id="delCounsel<?php echo $m;?>" checked <?= $disview ?> onClick="<?php if ($option == 'edit' || $option == "add") { ?>delRow_counsel(this, <?= $m ?>)<?php } ?>"></td>
                                                <td>
                                                <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                                                    <input type="text" class="w-100 form-control" name="counsel_code<?php echo $m;?>" id="counselCode<?php echo $m;?>" value="<?php echo $counsel_code;?>" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'associate_code', ['counselName<?php echo $m;?>'], ['associate_name'], 'counsel_code')">
                                                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselCode<?php echo $m;?>', ['counselName<?php echo $m;?>'], ['associate_name'], 'counsel_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                                </div>
                                                </td>
                                                <td><input type="text" class="w-100 form-control" name="counsel_name<?php echo $m;?>" id="counselName<?php echo $m;?>" value="<?php echo $counsel_name;?>" readonly></td>
                                                <td>
                                                <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                                                    <textarea class="w-100 resze form-control" rows="1"  name="coun_acty<?php echo $m;?>" oninput="this.value = this.value.toUpperCase()" id="counActy<?php echo $m;?>" onchange="fetchData(this, 'activity_code', ['counDesc<?php echo $m;?>'], ['activity_desc'], 'activity_code')"><?php echo $activity_code;?></textarea> 
                                                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('activity_code', '<?= $displayId['activity_list_help_id'] ?>', 'counActy<?= $m;?>', ['counDesc<?php echo $m;?>'], ['activity_desc'], 'activity_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                                </div>
                                                </td>
                                                <td><textarea class="w-100 resze form-control" rows="1" class="w-100" name="coun_desc<?php echo $m;?>" id="counDesc<?php echo $m;?>" readonly> <?php echo $activity_description;?> </textarea></td>
                                                <td class="text-center"><input type="checkbox" class="cstm-rdo" name="pay<?php echo $m;?>" value="Y" <?php if($payable_ind == "Y") { echo "checked"; } ?> <?= $disview?> ></td>
                                                <td class="text-center">
                                                <?php if(($option=='add' || $option == 'edit') && $j == $count['row_counsel']){?><input type="button" name="Add_coun<?php echo $m;?>" value="+" title="Add Row" onClick="addCounselRow(this,<?php echo $m;?>)"><?php }else if($option == 'Add'){?><input type="hidden"   name="Add_coun<?php echo $m;?>" value="+" title="Add Row" onClick="addCounselRow(this,<?php echo $m;?>)"><?php } ?>
                                                    <!-- <i class="fa-solid fa-plus" title="ADD"></i> -->
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        </table>
                                        <a href="javascript:void(0);" id="counselDelete" onclick="deleteRow('tbody2', 'CounselRowNo', 'counselDelete', 'addCounselRow')" class="btn cstmBtn add text-white mt-3">Delete Row</a>
                                    </div>
                                </div>

                                <div class="inpts w-100 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Counsel</label>
                                    <input  type="hidden" name="counsel_code" id="counselCode" size="4"   maxlength="6"    value="<?= $counsel_code ?>"   onBlur="" tabindex="" readonly>
                                    <textarea name="counsel_name" id="counselName" rows="1" class="form-control" maxlength="80" readonly ><?= ($option == 'add') ? '' :  $counsel_name  ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $n = 0;
                    if($option == 'Add' || $option == 'Edit') { $row = $count['row_num']+1; } else { $row = $count['row_num']; } 
                    for ($j=1; $j<=$row; $j++)
                    {
                    $n++;
                    $date_activity      = isset($caseHdrArray['caseIntArray']) ? date_conv($caseHdrArray['caseIntArray'][$j-1]['activity_date']) : '';
                    $desc_activity      = isset($caseHdrArray['caseIntArray']) ? $caseHdrArray['caseIntArray'][$j-1]['activity_desc'] : '';
                    }
                    ?>

                    <input type="hidden" name="all_other_case_counter" value="<?= isset($caseHdrArray['all_other_case_counter']) ? $caseHdrArray['all_other_case_counter'] : $count['row_num'] ?>">
                    <input type="hidden" name="all_case" value="">
                    <input type="hidden" name="all_sub" value="">
                    <input type="hidden" name="other_ind" value="<?= isset($caseHdrArray['other_ind']) ? $caseHdrArray['other_ind'] : '' ?>">
                    <input type="hidden" name="std_serial_no">
                    <input type="hidden" name="on_of_case" size="2" readonly>
                                    
                    <?php $session = session(); ?>
                    <input type="hidden" name="status_code"  value="<?= isset($caseHdrArray['status_code']) ? $caseHdrArray['status_code'] : ''  ?>">
                    <input type="hidden" name="prepared_by"  value="<?= $session->userId  ?>">
                    <input type="hidden" name="prepared_on"  value="<?= date('d-m-Y') ?>">

                    <input type="hidden" name="updated_by"  value="<?= $session->userId ?>">
                    <input type="hidden" name="updated_on"  value="<?= date('d-m-Y') ?>">

                    <input type="hidden" name="arb_row_num"  value="<?= $count['row_num'] ?>">
                    <input type="hidden" name="sysdate"      value="<?= date('d-m-Y') ?>">
                    <input type="hidden" name="no_row_del"   value="0">
                    <input type="hidden" name="fld_name"     value="">
                    <input type="hidden" name="fld_no"       value="">
                    <input type="hidden" name="del_falg"     value="N">
                    <input type="hidden" name="letter_no"    value="<?= isset($caseHdrArray['letter_no']) ? $caseHdrArray['letter_no'] : '' ?>"> 
                    
                    <input type="hidden" name="current_date" value="<?= date('d-m-Y') ?>">
                    <input type="hidden" name="InpocketRowNo" id="InpocketRowNo" value="<?php echo $k;?>">
                    <input type="hidden" name="user_option" value="<?php echo $option;?>">
                    <input type="hidden" name="CounselRowNo" id="CounselRowNo" value="<?php echo $m;?>">
                    <input type="hidden" name="IntermediateRowNo" value="<?php echo $n;?>">
                    <input type="hidden" name="IntermediateFlag" value="<?php if($option == "copy") { echo "Y";} else{ echo "N";}?>">
                </form>
            </div>
        </div>
    
    <?php } else if(isset($xQry) && !isset($qry)) { ?>
        <form action="" method="get" name="caseStatusLetter" id="caseStatusLetter" onsubmit="setCheckValue(event)">
        <div class="pagetitle d-block float-start col-md-12">
            <h1>Intimation Letter </h1>      
        </div><!-- End Page Title -->
            
        <section class="section dashboard">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="frms-sec d-inline-block w-100 bg-white p-3">
                    <div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-4">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Matter <strong class="text-danger">*</strong></label>
                        <input type="text" class="form-control w-25 float-start" placeholder="Matter" name="matter_code" value="<?= $xQry['matter_code']?>" readonly required />
                        <textarea class="form-control w-72 float-start ms-3" placeholder="Matter Description" name="matter_desc" readonly><?= $xQry['matter_desc2']?></textarea>
                    </div>
                    <div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-4">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Other Slide <strong class="text-danger">*</strong></label>
                        <div class="d-inline-block w-100 bdrMn py-2 px-3">		
                            <table class="table mb-0">
                                <?php if($yCnt == 0) { ?>	
                                <tr> No Records Found!!!! </tr>
                                <?php } else { ?>			
                                <tr>
                                    <td class="border-0"> <?php foreach($yQry as $key => $yRow) { ?>
                                        <div class="col-md-3 float-start mb-2">
                                            <input type="checkbox" name="selind<?php echo $key+1?>" value="Y">
                                            <input class="display_text_item" type="hidden" name="row_no<?php echo $key+1?>" value="<?php echo $yRow['row_no'] ?>" readonly>
                                            <input type="text" class="border-0" readonly value="<?php echo $yRow['name'] ?>"/>
                                        </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                    
                </div>		
            </div>
            
            <div class="col-md-12 d-inline-block mb-3">
                <button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="formOption('/sinhaco/case/status/', 'print', 'caseStatusLetter')">Print</button>
                <button type="button" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Exit</button>
            </div>
            <input type="hidden"  name="serial_no"      value="<?php echo $params['serial_no']?>">
            <input type="hidden"  name="logo_print_ind" value="<?php echo $params['logo_print_ind']?>">
            <input type="hidden"  name="letter_date"    value="<?php echo $params['letter_date']?>">
            <input type="hidden"  name="user_option"    value="<?php echo $option;?>">
            <input type="hidden"  name="other_cnt" id="otherCnt" value="<?php echo isset($yCnt) ? $yCnt : ''?>">
            <input type="hidden"  name="other_str" id="otherStr" value="">
        </div>
        </section>
    <?php } else {?>
        <?php 
            $tot_char        = 90 ;
            $tot_no_of_lines = 55 ;
            $page_no = 1;
            $line_no = 0; 
            $page_flag = 'F';
        ?>

        <table width="681" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td colspan="2" class="text-center py-0"><img class="imlogoLtrpg" src="<?= base_url('public/assets/img/logo.jpg') ?>"/></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center py-0">
                    <p class="fw-bold"><?=  strtoupper($params['branch_addr1']) ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center py-0">
                    <p class="fw-bold"><?= strtoupper($params['branch_addr2']) ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center py-0 pb-3 emlSecTp">
                    <p class="fw-bold"><?= $params['branch_addr3'] ?></p>
                </td>
            </tr>
        </table>
        <table width="681" cellpadding="0" cellspacing="0" border="0">
        <tr>
        <td class="GroupDetail_band_portrait" width="70%" style="font-size:13px; font:Verdana; font-family:Verdana;">No :&nbsp;<?php echo $params['letter_no'];?></td>
        <td class="GroupDetail_band_portrait" width="23%" style="font-size:13px; font:Verdana; font-family:Verdana;" align="right">Date :&nbsp;<?php echo date_conv($params['letter_date']); ?>&nbsp;&nbsp;</td>
        <td class="GroupDetail_band_portrait" width="7%"  style="font-size:13px; font:Verdana; font-family:Verdana;" align="right"><?php if($page_flag == 'T') { echo '('.$page_no.')'; } ?></td>
        </tr>
        <tr><td class="GroupDetail_band_portrait" colspan="3">&nbsp;</td></tr>
        </table>
        <table width="681" cellpadding="0" cellspacing="0" border="0">
        <tr>
        <td class="GroupDetail_band_portrait_1" width="50%" valign="top">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr style="line-height:20px;"><td class="GroupDetail_band_portrait" style="font:Verdana; font-family:Verdana;">To</td></tr>
                <!-- Other Side -->
                <?php 
                // for($a=1; $a<=$other_cnt; $a++) 
                foreach($other_qry as $key => $other_row) 
                { 
                    $a = $key + 1;
                    // echo '<pre>'; echo "====="; print_r($other_row);die;
                // $other_row   = isset($other_qry) ? $other_qry : '' ; 
                $other_addr  = (($other_row['address_line_1'] != '') ? (', '.$other_row['address_line_2']) : '') ;
                $other_addr .= (($other_row['address_line_2'] != '') ? (', '.$other_row['address_line_2']) : '') ;
                $other_addr .= (($other_row['address_line_3'] != '') ? (', '.$other_row['address_line_3']) : '') ;
                $other_addr .= (($other_row['address_line_4'] != '') ? (', '.$other_row['address_line_4']) : '') ;
                $other_addr .= ($other_row['city'] != '' && $other_row['pin_code'] != '') ? (', '.$other_row['city'].', PIN - '.$other_row['pin_code']) : (($other_row['city'] != '' && $other_row['pin_code'] == '') ? (', '.$other_row['city']) : (($other_row['city'] == '' && $other_row['pin_code'] != '') ? (', PIN - '.$other_row['pin_code']) : '')) ;
                ?>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="vertical-align:text-top; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo $other_row['name'];?></td></tr>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="vertical-align:text-top; font-size:11px; font:Verdana; font-family:Verdana; padding-left:10px"><?php echo $other_addr;?></td></tr>
                <?php } ?> 
                <!-- Other Side -->
                <?php if ($other_cnt > 0) { ?>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="vertical-align:text-top; font-size:13px; font:Verdana; font-family:Verdana;">&nbsp;</td></tr>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="vertical-align:text-top; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo '<u>Our Client</u>'?></td></tr>
                <?php } ?>
                <?php if ($params['client_code'] == 'K00068') { ?>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="vertical-align:text-top; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo 'THE CHIEF LAW OFFICER'?></td></tr>
                <?php } ?>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="vertical-align:text-top; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo $params['client_name'];?></td></tr>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="vertical-align:text-top; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo $params['client_address'];?></td></tr>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="font-size:13px;">&nbsp;</td></tr>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="font-size:13px; font:Verdana; font-family:Verdana;">Kind Attn :&nbsp;<?php echo $params['attn_name'];?></td></tr>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait">&nbsp;</td></tr>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait" style="vertical-align:text-top; font-size:13px; font:Verdana; font-family:Verdana;"><?php if($params['designation']!='') echo 'Designation : '.$params['designation']; else echo ' ' ;?></td></tr>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait">&nbsp;</td></tr>
                <tr style="vertical-align:top;"><td class="GroupDetail_band_portrait">&nbsp;</td></tr>
            </table>
        </td>
        <td class="GroupDetail_band_portrait_1" width="50%" valign="top"> 
            <table width="100%" cellpadding="0" cellspacing="0" border="0" vspace="0">
                <tr>
                <td height="20" width="06%" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo 'Re : '?></td>
                <td height="20" width="94%" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo $params['matter_desc'];?></td>
                </tr>
                <tr>
                <td height="70">&nbsp;</td>
                <td height="70" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font-size:13px;"><?php echo strtoupper($params['other_cases']) ;?></td>
                </tr>
                <tr>
                <td height="15" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo 'Ref : ' ; ?></td>
                <td height="15" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><span class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo $params['reference_desc'];?></span></td>
                </tr>
                <tr>
                <td height="15" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo 'Sub : ' ; ?></td>
                <td height="75" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><span class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo $params['subject_desc'];?></span></td>
                </tr>
            </table>
            </td>
        </tr>
        </table>
        <table width="681" cellpadding="0" cellspacing="0" border="0">
        <tr><td class="GroupDetail_band_portrait" style="text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo $params['title'];?></td></tr>
        <tr><td class="GroupDetail_band_portrait">&nbsp;</td></tr>
        </table>
        <?php $l_no = 17+($other_cnt*2) ; $b_ln = $tot_no_of_lines - $l_no; ?>
        <table width="681" cellpadding="0" cellspacing="0" border="0">
        <?php 
        for($i=0;$i<$row_cnt;$i++)
        {
            if($l_no > $tot_no_of_lines)
            {
        ?>
            </table>
            <br class="pageEnd">
            <table width="681" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td colspan="2" class="text-center py-0"><img class="imlogoLtrpg" src="<?= base_url('assets/img/logo.jpg') ?>"/></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center py-0">
                        <p class="fw-bold"><?=  strtoupper($params['branch_addr1']) ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center py-0">
                        <p class="fw-bold"><?= strtoupper($params['branch_addr2']) ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center py-0 pb-3 emlSecTp">
                        <p class="fw-bold"><?= $params['branch_addr3'] ?></p>
                    </td>
                </tr>
            </table>
            <table width="681" cellpadding="0" cellspacing="0" border="0">
                <tr>
                <td class="GroupDetail_band_portrait" width="70%" style="font-size:13px; font:Verdana; font-family:Verdana;">No :&nbsp;<?php echo $params['letter_no'];?></td>
                <td class="GroupDetail_band_portrait" width="23%" style="font-size:13px; font:Verdana; font-family:Verdana;" align="right">Date :&nbsp;<?php echo date_conv($params['letter_date']); ?>&nbsp;&nbsp;</td>
                <td class="GroupDetail_band_portrait" width="7%"  style="font-size:13px; font:Verdana; font-family:Verdana;" align="right"><?php if($page_flag == 'T') { echo '('.$page_no.')'; } ?></td>
                </tr>
                <tr><td class="GroupDetail_band_portrait" colspan="3">&nbsp;</td></tr>
                <tr><td class="GroupDetail_band_portrait" colspan="3">&nbsp;</td></tr>
                <tr><td class="GroupDetail_band_portrait" colspan="3">&nbsp;</td></tr>
            </table>
            <table width="681" cellpadding="0" cellspacing="0" border="0">
        <?php 
            $l_no = 7 ; 
            } 
        ?>
            <!--<tr><td class="GroupDetail_band_portrait" style="text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo str_replace("<br />","",text_justify(trim(nl2br(stripslashes($hdr_array[$i]))),$tot_char));?></td></tr>-->
                <tr><td class="GroupDetail_band_portrait" style="text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo str_replace("<br />","",(trim(nl2br(stripslashes($hdr_array[$i])))));?></td></tr>
                <tr><td class="GroupDetail_band_portrait">&nbsp;</td>
        <?php 
            $l_no += 2 ; 
        } 
        ?>
        </table>
        <table width="681" cellpadding="0" cellspacing="0" border="0">
        <tr>
        <td width="227">&nbsp;</td>
        <td width="227">&nbsp;</td>
        <td width="227">&nbsp;</td>
        </tr>
        <tr>
        <td class="GroupDetail_band_portrait">&nbsp;</td>
        <td class="GroupDetail_band_portrait">&nbsp;</td>
        <td class="GroupDetail_band_portrait" align="center" style="text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;">&nbsp;Yours faithfully,</td>
        </tr>
        <tr>
        <td class="GroupDetail_band_portrait">&nbsp;</td>
        <td class="GroupDetail_band_portrait">&nbsp;</td>
        <td class="GroupDetail_band_portrait" align="center" style="text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;">&nbsp;for Sinha &amp; Company</td>
        </tr>
        <tr>
        <td class="GroupDetail_band_portrait">&nbsp;</td>
        <td class="GroupDetail_band_portrait">&nbsp;</td>
        <td class="GroupDetail_band_portrait" align="center" style="text-align:justify; vertical-align:bottom; height:65px; font-size:13px; font:Verdana; font-family:Verdana;">&nbsp;<?php echo $params['signatory'] ; ?>&nbsp;</td>
        </tr>
        <tr>
        <td class="GroupDetail_band_portrait">&nbsp;</td>
        <td class="GroupDetail_band_portrait">&nbsp;</td>
        <td class="GroupDetail_band_portrait" align="center" style="text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;">&nbsp;( Advocate )</td>
        </tr>
        </table>
        <table width="681" cellpadding="0" cellspacing="0" border="0">
        <tr><td class="GroupDetail_band_portrait">&nbsp;</td>
        <tr style="line-height:24px;"><td class="GroupDetail_band_portrait" style="text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php echo $params['footer_desc'];?></td></tr>
        </table>
        <table width="681" cellpadding="0" cellspacing="0" border="0">
        <tr><td class="GroupDetail_band_portrait">&nbsp;</td>
        <tr style="line-height:24px;"><td class="GroupDetail_band_portrait" style="text-align:justify; font-size:13px; font:Verdana; font-family:Verdana;"><?php if ($params['cc_desc']!='') echo 'CC: '.$params['cc_desc']; else echo '';?></td>
        </table>

    <?php } ?>
</section>

               

</main>
<!-- End #main -->

<script>
    function chkLetterDate(fld) {
        make_date(fld); 
        //
        if(fld.value != "") 
        { 
        var xCurDtYmd = document.caseStatusForm.current_date.value.substr(6,4)+document.caseStatusForm.current_date.value.substr(3,2)+document.caseStatusForm.current_date.value.substr(0,2) ;
        var xLetDtYmd = fld.value.substr(6,4)+fld.value.substr(3,2)+fld.value.substr(0,2) ;  
            
        if(xLetDtYmd > xCurDtYmd)
        {
            alert('Letter Date must be <= Current Date .......'); fld.value = ''; fld.focus(); return false ;
        }
        } 
    }

    function chkActivityDate(fld) {
        if(fld.value != "") 
        { 
            make_date(fld); 
            dateValid(fld,document.caseStatusForm.sysdate,'L',"Activity Date","Current Date")
        } else { alert('Activity Date : '); fld.focus(); return false; }
    }

    function chkStatusDate(fld) {
        if(fld.value != "") 
        { 
            make_date(fld); 
            dateValid(fld,document.caseStatusForm.sysdate,'L',"Status Date","Current Date")
        } 
    }

    function chkNextDate(fld) {
        var ind = 1;
        if(document.caseStatusForm.activity_date.value == "") { ind = 0; alert("Activity Date : "); fld.value = ""; document.caseStatusForm.activity_date.focus(); return false; } 
        if(fld.value != "" && ind == 1) 
        { 
            make_date(fld); 
            dateValid(fld,document.caseStatusForm.activity_date,'G',"Next Date","Activity Date")
        }  
    }

    function chkInstDate(fld) {
      var ind = 1;
	  if(document.caseStatusForm.activity_date.value == "") { ind = 0; error("Activity Date : ",1); fld.value = ""; document.caseStatusForm.activity_date.focus(); return false; } 
      if(fld.value != "" && ind == 1) 
      { 
         make_date(fld); 
         dateValid(fld,document.caseStatusForm.activity_date,'G',"Instrument Date","Activity Date")
      }
    }

    function getMatterValue(e) {
        console.log('abc');
		if(e.value != '') {
			fetch(`${baseURL}/api/matterDetails/${e.value}/notFound`)
			.then((response) => response.json())
			.then((data) => {
				console.log('============================> ');
				console.log(data);
				document.getElementById("courtCode").value=data.court_code;
				document.getElementById("matterDesc").value=data.mat_description;
				document.getElementById("courtName").value = data.court_name;
				document.getElementById("referenceDesc").value = data.reference_desc;
				document.getElementById("counselName").value = data.counsel_name;
				document.getElementById("counselCode").value = data.counsel_code;
				document.getElementById("prevFixedFor").value = data.prev_fixed_for;
				document.getElementById("prevDate").value = data.prev_date;
				document.getElementById("lastRemark").value = data.last_remark;
				document.getElementById("appearFor").value = data.appear_for;
			});
		}
    }

    function setCheckValue(e) {
		e.preventDefault();
		console.log(document.caseStatusLetter);
		
		var aCnt = 0  ;
	    var aStr = '' ;
	    for(a=1; a<=(document.getElementById("otherCnt").value*1); a++)
		{
            if(eval("document.caseStatusLetter.selind"+a+".checked == true"))
            {
                aCnt += 1;
                aStr += ((aCnt>1) ? ',' : '') + eval("document.caseStatusLetter.row_no"+a+".value") ;
            }
		}
        console.log(aStr);
        document.getElementById("otherStr").value = aStr;
		
		document.caseStatusLetter.submit();
	}

    function addInpocketRow(fld,n) { 
        var n = total_row = (document.caseStatusForm.InpocketRowNo.value)*1; let conditionFlag = 0;

        if(n != 0) {
			if(eval("document.caseStatusForm.asso_acty"+n+".value") == "" ) {
                Swal.fire({ text: 'Please Enter Associate Code !!' }).then((result) => { setTimeout(() => {eval("document.caseStatusForm.asso_acty"+n+".focus()")}, 500) });
				return false;
			}
			conditionFlag = (eval("document.caseStatusForm.asso_acty"+total_row+".value") != "" && eval("document.caseStatusForm.asso_desc"+total_row+".value") != "");
		}
         
        if(document.caseStatusForm.user_option.value == 'add' || document.caseStatusForm.user_option.value == 'edit') { 
            if(conditionFlag || total_row == 0) {
                n++; document.caseStatusForm.InpocketRowNo.value = n;

                if (total_row != 0) {
                    fld.disabled = true; fld.style.visibility = 'hidden'; 
                } else {
                    fld.setAttribute('onClick', `deleteRow('tbody', 'InpocketRowNo', 'inpocketDelete', 'addInpocketRow')`);
                    fld.innerText = "Delete Row";
                    let table = document.getElementById('tbody').innerHTML = '';
                }

                var text1  = `
                    <tr>
                        <td id="Ctd${n}" onClick="<?php if ($option == 'edit' || $option == "add") { ?>delRow_inpocket(this, ${n})<?php } ?>"><input type="hidden" value="Y" name="del_inpoc${n}" id="del_inpoc${n}" title="Delete Row" onClick="<?php if ($option == 'edit' || $option == "add") { ?>delRow_inpocket(this, ${n})<?php } ?>"></td>
                        <td><input class="w-100" type="text" name="asso_code${n}" value="0000" readonly></td>
                        <td><input class="w-100" type="text" name="asso_name${n}" value="OWN" readonly></td>
                        <td>
                            <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                                <input class="w-100" type="text" name="asso_acty${n}" id="assoActy${n}" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'activity_code', ['assoDesc${n}'], ['activity_desc'], 'activity_code')">
                                <i class="fa-solid fa-binoculars icn-vw" onclick="showData('activity_code', '<?= $displayId['activity_list_help_id'] ?>', 'assoActy${n}', ['assoDesc${n}'], ['activity_desc'], 'activity_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                        </td>
                        <td><input class="w-100" type="text" name="asso_desc${n}" id="assoDesc${n}" readonly></td>
                        <td class="text-center">&nbsp;<input type="button" name="Add_inpoc${n}" value="+" title="Add Row" onClick="addInpocketRow(this,${n})"> </td>
                    </tr>`;

                    let tbody = document.getElementById("tbody");
					let tr = tbody.insertRow(tbody.rows.length);
					tr.classList.add('fs-14'); tr.innerHTML = text1;

                eval(`document.caseStatusForm.asso_acty${n}.focus()`);
                eval(`document.caseStatusForm.asso_acty${n}.select()`);
            }
        }
    }
              
    function addCounselRow(fld,n) {
        var n = total_row = (document.caseStatusForm.CounselRowNo.value)*1; let conditionFlag = 0;
        //alert(total_row);
        if(n != 0) {
			if(eval("document.caseStatusForm.counsel_code"+n+".value") == "" ) {
                Swal.fire({ text: 'Please Enter Counsel Code  !!' }).then((result) => { setTimeout(() => {eval("document.caseStatusForm.counsel_code"+n+".focus()")}, 500) });
				return false;
			}
			conditionFlag = (eval("document.caseStatusForm.counsel_code"+total_row+".value") != "" && eval("document.caseStatusForm.counsel_name"+total_row+".value") != "" && eval("document.caseStatusForm.coun_acty"+total_row+".value") != "" && eval("document.caseStatusForm.coun_desc"+total_row+".value") != "" );
		}

        if(document.caseStatusForm.user_option.value == 'add' || document.caseStatusForm.user_option.value == 'edit' )
        { 
            if(conditionFlag || total_row == 0) {
                n++; document.caseStatusForm.CounselRowNo.value = n;

                if (total_row != 0) {
                    fld.disabled = true; fld.style.visibility = 'hidden'; 
                } else {
                    fld.setAttribute('onClick', `deleteRow('tbody2', 'CounselRowNo', 'counselDelete', 'addCounselRow')`);
                    fld.innerText = "Delete Row";
                    let table = document.getElementById('tbody2').innerHTML = '';
                }

                var text2  =  `
                <tr>
                    <td id="Ctd2${n}" onClick="<?php if ($option == 'edit' || $option == "add") { ?>delRow_counsel(this, ${n})<?php } ?>"><input type="hidden" value="Y" name="del_counsel${n}" id="delCounsel${n}" onClick="<?php if ($option == 'edit' || $option == "add") { ?>delRow_counsel(this, ${n})<?php } ?>"></td>
                    <td>
                        <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                            <input class="w-100" type="text" name="counsel_code${n}" id="counselCode${n}" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'associate_code', ['counselName${n}'], ['associate_name'], 'counsel_code')">
                            <i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselCode${n}', ['counselName${n}'], ['associate_name'], 'counsel_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                    </td>
                    <td><input class="w-100" type="text" name="counsel_name${n}" id="counselName${n}" readonly=true></td>
                    <td>
                        <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                            <input class="w-100" type="text" name="coun_acty${n}" id="counActy${n}" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'activity_code', ['counDesc${n}'], ['activity_desc'], 'activity_code')">
                            <i class="fa-solid fa-binoculars icn-vw" onclick="showData('activity_code', '<?= $displayId['activity_list_help_id'] ?>', 'counActy${n}', ['counDesc${n}'], ['activity_desc'], 'activity_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                    </td>
                    <td><input class="w-100" type="text" name="coun_desc${n}" id="counDesc${n}" readonly=true></td>
                    <td><input class="w-100" type="checkbox" name="pay${n}" value="Y"></td>
                    <td><input class="w-100" type="button" name="Add_coun${n}" value="+" title="Add Row" onClick="addCounselRow(this,${n})"></td>
                </tr>`;
                
                let tbody = document.getElementById("tbody2");
                let tr = tbody.insertRow(tbody.rows.length);
                tr.classList.add('fs-14'); tr.innerHTML = text2;

                eval(`document.caseStatusForm.counsel_code${n}.focus()`);
                eval(`document.caseStatusForm.counsel_code${n}.select()`);
            }
        }
             
    }

    function delRow_inpocket(e, n) {
		var row = document.getElementById("Ctd"+n);
		if(document.getElementById("del_inpoc"+n).value == 'Y') {
			$(e).parent('tr').addClass('rowSlcted');
			document.getElementById("del_inpoc"+n).value = 'N';
			document.getElementById("del_inpoc"+n).style.background='rgb(163 200 213)';
			document.getElementById("del_inpoc"+n).style.color='rgb(163 200 213)';
			row.style.background='rgb(163 200 213)';
		} else  {
			$(e).parent('tr').removeClass('rowSlcted');
			document.getElementById("del_inpoc"+n).value='Y';
			document.getElementById("del_inpoc"+n).style.background='#fff';
			document.getElementById("del_inpoc"+n).style.color='#fff';
			row.style.background='#fff';
		}
	}

    function delRow_counsel(e, n) {
		var row = document.getElementById("Ctd2"+n);
		if(document.getElementById("delCounsel"+n).value == 'Y') {
			$(e).parent('tr').addClass('rowSlcted');
			document.getElementById("delCounsel"+n).value = 'N';
			document.getElementById("delCounsel"+n).style.background='rgb(163 200 213)';
			document.getElementById("delCounsel"+n).style.color='rgb(163 200 213)';
			row.style.background='rgb(163 200 213)';
		} else  {
			$(e).parent('tr').removeClass('rowSlcted');
			document.getElementById("delCounsel"+n).value='Y';
			document.getElementById("delCounsel"+n).style.background='#fff';
			document.getElementById("delCounsel"+n).style.color='#fff';
			row.style.background='#fff';
		}
	}

    function deleteRow(id = '', rowCountId = '', actionBtn = '', callFunction = '') {
        var table = document.getElementById(id);
		var addBtn = table.lastElementChild.lastElementChild.innerHTML;
		var rows = table.querySelectorAll('.rowSlcted');

		if(rows.length > 0) {
			Swal.fire({
				title: 'Do you want to Delete ??',
				showCancelButton: true,
				confirmButtonText: 'Yes!! Delete',
			}).then((result) => {
				if (result.isConfirmed) {
					for (let row of rows) row.remove();

					var table = document.getElementById(id);
					let totalRows = table.children.length;
					if(totalRows > 0) table.lastElementChild.lastElementChild.innerHTML = addBtn;
					if(totalRows == 0) {
						let btnSpan = document.getElementById(actionBtn);
						btnSpan.setAttribute('onClick', callFunction + `(this, 1)`);
						btnSpan.innerText = "Add Row";
						table.innerHTML = '<td class="border fw-normal"></td> <td class="border fw-normal" colspan="8">  No Records Added Yet !! </td>';
					}
					let row_no = document.getElementById(rowCountId); row_no.value = parseInt(row_no.value) - rows.length;
				}
			})
		} else {
			Swal.fire('Select Atleast <b> One Row </b> to Perform Delete Operation !!')
		}
    }
</script>

<?= $this->endSection() ?>