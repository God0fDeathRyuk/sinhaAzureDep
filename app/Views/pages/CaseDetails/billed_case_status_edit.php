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
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('message_not_editable') !== NULL) : ?>
    <div id="alertMsg">
        <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('message_not_editable') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>
<div class="pagetitle col-md-12 float-start border-bottom pb-1">
  <h1>Case Status Edit (Billed) </h1>
<?php if (isset($option)) { ?>
    <div class="col-md-4 float-end text-end mb-2">
        <a href="<?= base_url($data['requested_url']) ?>" class="btn-bck btn btn-dark me-2">Back</a>
        <button type="button" class="btn-save btn btn-secondary" onclick="submitForm('caseStatusForm', 'save')">Save</button>
    </div>
    <?php } ?> 
</div>
<!-- End Page -->

<section class="section dashboard d-inline-block w-100">
<?php if (!isset($option)) { ?>
        <form action="" method="post" id="caseStatusEdit">
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
                    <label class="d-block w-100 mb-2">Serial No <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control cstm-inpt" id="SerialNo" onchange="fetchData(this, 'serial_no', ['matterCode', 'matterDesc'], ['matter_code', 'matter_desc'], 'serial_no')" name="serial_no" required />
                    <i class="fa-solid fa-eye inpt-vw" onclick="showData('serial_no', '<?= $displayId['casesrl_help_id'] ?>', 'SerialNo', ['matterCode', 'matterDesc'], ['matter_code', 'matter_desc'], 'serial_no')" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <div class="inpt-grp col-md-4 pe-0">
                    <label class="d-block w-100 mb-2">Matter Code</label>
                    <input type="text" class="form-control cstm-inpt" id="matterCode" name="matter_code" readonly disabled />
                </div>
                <div class="inpt-grp col-md-12 pe-0 mt-3">
                    <label class="d-block w-100 mb-2">Matter Description</label>
                    <textarea name="" rows="1" class="form-control cstm-inpt" id="matterDesc" name="matter_desc" readonly disabled></textarea>
                </div>
            </div>
            <div class="w-100 float-start text-start mt-4 top-btn-fld">
                <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('/case/billed-case-status-edit/', 'post edit', 'caseStatusEdit')">Post Edit</button>
                <button type="reset" class="btn btn-primary cstmBtn mt-0">Reset</button>
                <!--<a href="/sinhaco/case/status/open/" class="btn btn-primary cstmBtn mt-0">Exit</a>-->
                  
            </div>
        </form>
<?php } else { ?>

    <div class="form" >
        <div class="col-md-12">
            <form action="" method="post" id="caseStatusForm" name="caseStatusForm" >
                <div class="sec1 d-inline-block w-100 border mt-3">
                    <div class="inpt-sec-top d-inline-block w-100 p-3 border-bottom">
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Serial</label>
                            <input type="text" name="serial_no" class="form-control" readonly style="background: #e9ecef;" value="<?= ($option == 'add') ? '' : $caseHdrArray['serial_no'] ?>" />
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Activity Date</label>
                            <input type="text" name="activity_date" class="form-control" value="<?= ($option == 'add') ? '' : date_conv($caseHdrArray['activity_date']) ?>"  onchange="chkActivityDate(this)" onBlur="make_date(this)"/>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Branch</label>
                            <select class="form-select"  name="branch_code" id="branchCode">
                            <?php foreach($data['branches'] as $branch) { ?>
                            <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Prep By</label>
                            <input type="text" class="form-control" name="prepared_by"   value="<?= ($option == 'add') ? '' : $caseHdrArray['prepared_by'] ?>" readonly/>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Prep On</label>
                            <input type="text" class="form-control" name="prepared_on"   value="<?= ($option == 'add') ? '' : date_conv($caseHdrArray['prepared_on']) ?>" readonly/>
                        </div>
                        <div class="inpts col-md-3 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Letter Date</label>
                            <input type="text" class="form-control" name="letter_date" value="<?= ($option == 'add') ? '' : date_conv($caseHdrArray['letter_date']) ?>" onChange="chkLetterDate(this); make_date(this)"/>
                        </div>
                        <a href="#" class="bdge"><?= $caseHdrArray['status_desc'] ?></a>
                    </div>
                    <div class="mn-inpt-sec d-inline-block w-100 p-3">
                        <div class="all-inpt d-inline-block w-100">
                            <div class="inpts col-md-2 float-start px-2 position-relative">
                            <!-- condition is applied in this field and used onDblClick="show_matter_history2()" function, for abhijit open lookup by history2 and for others history -->
                                <label for="" class="d-block w-100 mb-2">Matter</label>
                                <input type="text" class="form-control" name="matter_code" placeholder="search matter" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['referenceDesc', 'clientCode', 'clientName', 'appearFor', 'judgeName', 'courtName', 'matterStatus'], ['reference_desc', 'client_code', 'client_name', 'appearing_for', 'judge_name', 'court', 'status_code'], 'matter_code')"  onfocusout="getMatterValue(this)" value="<?= ($option == 'add') ? '' : $caseHdrArray['matter_code'] ?>" readonly/>
                                <i class="fa-solid fa-clock inpt-vw histryIcn" id="caseHistory" onclick="showHistory('/case/history/matter', 'branch_code=@branchCode&ason_date=<?= date('d-m-Y') ?>&matter_code=@matterCode&matter_desc=@matterDesc&client_code=@clientCode&desc_ind=Y')" title="Show Matter History" data-toggle="modal" data-target="#lookup"></i>
                            <!--  -->
                            </div>
                            <div class="inpts col-md-6 float-start px-2 position-relative mtop">
                                <input type="text" class="form-control" name="mat_description" placeholder="search matter"  oninput="this.value = this.value.toUpperCase()" id="matterDesc"  value="<?= ($option == 'add') ? '' : $caseHdrArray['full_matter_desc'] ?>" readonly/>
                            </div>
                            <div class="inpts col-md-4 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Ref No.</label>
                                <input type="text" class="form-control" name="reference_desc" id="referenceDesc"   placeholder="Ref No" value="<?= ($option == 'add') ? '' : $caseHdrArray['reference_desc'] ?>" readonly/>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100 mt-2">
                            <div class="inpts col-md-3 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Client</label>
                                <input type="text" class="form-control"  name="client_code"  id="clientCode" oninput="this.value = this.value.toUpperCase()" placeholder="Client"   value="<?= ($option == 'add') ? '' : $caseHdrArray['client_code'] ?>" readonly/>
                            </div>
                            <div class="inpts col-md-9 float-start px-2 position-relative mtop">
                                <input type="text" class="form-control" name="clint_name" id="clientName" oninput="this.value = this.value.toUpperCase()" placeholder="Client"   value="<?= ($option == 'add') ? '' : $caseHdrArray['client_name'] ?>" readonly/>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100 mt-2">
                            <div class="inpts col-md-3 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Court</label>
                                <input type="text" class="form-control" placeholder="Count" name="court_code" oninput="this.value = this.value.toUpperCase()" id="courtCode"   value="<?= ($option == 'add') ? '' : $caseHdrArray['court_code'] ?>" readonly/>
                            </div>
                            <div class="inpts col-md-9 float-start px-2 position-relative mtop">
                                <input type="text" class="form-control" placeholder="Court" name="court_name" oninput="this.value = this.value.toUpperCase()" id="courtName"   value="<?= ($option == 'add') ? '' : $caseHdrArray['code_desc'] ?>" readonly/>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100 mt-2">
                            <div class="inpts col-md-12 float-start px-2">
                                <textarea name="other_case_desc" id="" rows="2" class="form-control w-100" placeholder="Other case description" readonly><?= ($option == 'add') ? '' : $caseHdrArray['other_case_desc'] ?></textarea>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100 mt-2">
                            <div class="inpts col-md-4 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Appear For</label>
                                <input type="text" class="form-control" placeholder="Count" name="appear_for" id="appearFor"   value="<?= ($option == 'add') ? '' : $caseHdrArray['appear_for'] ?>" readonly/>
                            </div>
                            <div class="inpts col-md-4 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Fixed For</label>
                                <textarea name="prev_fixed_for" id="prevFixedFor" rows="2" class="form-control w-100" placeholder="Other case description"><?= ($option == 'add') ? '' : $caseHdrArray['prev_fixed_for'] ?></textarea> 
                            </div>
                            <div class="inpts col-md-4 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Previous Remarks</label>
                                <textarea name="last_remark" id="lastRemark" rows="2" class="form-control w-100"   placeholder="Other case description" readonly ></textarea>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100">
                            <div class="inpts col-md-3 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Last Date</label>
                                <input type="text" class="form-control" placeholder="last Date" name="prev_date" id="prevDate" value="<?= ($option == 'add') ? '' : date_conv($caseHdrArray['prev_date']) ?>" onBlur="make_date(this)"/>
                            </div>
                            <div class="inpts col-md-3 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Forwarding</label>
                                <select name="forwarding_ind" id="" class="form-select">
                                <option value="" <?php if ($option != 'add') if ($caseHdrArray['forwarding_ind'] != 'Y') echo 'selected'; ?>>No</option>
                                <option value="Y" <?php if ($option != 'add') if ($caseHdrArray['forwarding_ind'] == 'Y') echo 'selected'; ?>>Yes</option>
                                </select>
                            </div>
                            <div class="inpts col-md-6 float-start px-2 mtop rdobtnall">
                                <div class="rdolst d-block float-start w-25 me-2">
                                    <input type="radio" id="bilable" name="billable_option" class="cstm-rdo float-start d-block me-1 mt-1" value='Y' <?= ($option != 'add') ? ($caseHdrArray['billable_option'] == 'Y') ? 'checked' : '' : 'checked' ?> disabled />
                                    <label for="bilable" class="d-block w-100">Bilable</label>
                                </div>
                                <div class="rdolst d-block float-start w-25 me-2">
                                    <input type="radio" id="prebilable" name="billable_option" class="cstm-rdo float-start d-block me-1 mt-1" value='P' <?php if ($option != 'add') if($caseHdrArray['billable_option'] == 'P'){ echo 'checked';}?> disabled />
                                    <label for="prebilable" class="d-block w-100">Pre-Bilable</label>
                                </div>
                                <div class="rdolst d-block float-start w-25 me-2">
                                    <input type="radio" id="nonbilable" name="billable_option" class="cstm-rdo float-start d-block me-1 mt-1" value='N' <?php if ($option != 'add') if($caseHdrArray['billable_option'] == 'N'){ echo 'checked';}?> disabled />
                                    <label for="nonbilable" class="d-block w-100">Non-Bilable</label>
                                </div>                                
                            </div>
                            
                            <div class="inpts col-md-5 float-start px-2 pe-0 mt-3">
                                <label for="" class="d-block w-100 mb-2">Judge</label>
                                <input type="text" class="form-control" placeholder="Issue Date" name="judge_name" name="judgeName" value="<?= ($option == 'add') ? '' : $caseHdrArray['judge_name'] ?>" />
                            </div>
                            <div class="inpts col-md-4 float-start px-2 pe-0 mt-3">
                                <label for="" class="d-block w-100 mb-2">Next date</label>
                                <input type="text" class="form-control" placeholder="Next date" name="next_date" value="<?= ($option == 'add') ? '' : date_conv($caseHdrArray['next_date']) ?>" onchange="chkNextDate(this)" onBlur="make_date(this)"/>
                            </div>
                            <div class="position-relative intrmde-dts d-inline-block col-md-12 border mt-5 py-2">   
                                <p class="sectitl text-uppercase">Intermediate dates</p>                        
                                <div class="inpts w-50 float-start px-2 pe-0 mt-3 position-relative">
                                    <label for="" class="d-block w-100 mb-2">Fixed For</label>
                                    <textarea name="next_fixed_for" id="" rows="2" class="form-control" placeholder="Fixed For"><?= ($option == 'add') ? '' : $caseHdrArray['next_fixed_for'] ?></textarea>
                                    
                                </div>
                                <div class="inpts w-50 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Remarks</label>
                                    <textarea name="remarks" id="" rows="2" class="form-control" placeholder="Remarks" ><?= ($option == 'add') ? '' : $caseHdrArray['remarks'] ?></textarea>
                                </div>
                            </div>
                            <div class="inpts w-100 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Particulars</label>
                                <textarea name="letter_body_desc" id="editor" rows="2"  class="form-control" placeholder="Particulars" ><?= ($option == 'add') ? '' : $caseHdrArray['header_desc'] ?></textarea>
                            </div>
                            <div class="inpts w-100 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Footer</label>
                                <textarea name="footer_desc" id="" rows="2" class="form-control" placeholder="Footer"><?= ($option == 'add') ? '' : $caseHdrArray['footer_desc'] ?></textarea>
                            </div>
                            <div class="inpts w-100 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">CC</label>
                                <textarea name="cc_desc" id="" rows="2" class="form-control" placeholder="CC"><?= ($option == 'add') ? '' : $caseHdrArray['cc_desc'] ?></textarea>
                            </div>
                            <div class="inpts col-md-4 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Matter Status</label>
                                <select name="matter_status" id="matterStatus" class="form-select">
                                <?php foreach($caseHdrArray['matter_status'] as $matter_status) { ?>   
                                <option value="<?php echo $matter_status['status_code'];?>" <?php if ($option != 'add') if($matter_status['status_code'] ==  $caseHdrArray['matter_status_code']){ echo 'selected'; }?>><?php echo $matter_status['status_desc'];?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div class="inpts col-md-4 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Status Date</label>
                                <input type="text" class="form-control" placeholder="Status Date" name="status_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['status_date'] ?>" onBlur="chkStatusDate(this); make_date(this)"/>
                            </div> 
                            <div class="inpts col-md-4 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Alert Date</label>
                                <input type="date" class="form-control" placeholder="Alert Date" name="alert_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['alert_date'] ?>"/>
                            </div>
                            <div class="inpts col-md-5 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Advocate</label>
                                <textarea name="signatory" id="" rows="2" class="form-control" placeholder="Advocate"><?= ($option == 'add') ? '' : $caseHdrArray['signatory'] ?></textarea>
                            </div>
                            <div class="inpts col-md-7 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Narration</label>
                                <textarea name="alert_narration" id="" rows="2" class="form-control" placeholder="Narration"><?= ($option == 'add') ? '' : $caseHdrArray['alert_narration'] ?></textarea>
                            </div>
                            <div class="d-inline-block w-100 px-2 mt-4">
                                    <div class="tbladd nobrdr-icn">
                                        <p class="d-inline-block w-100 px-3 py-2 text-uppercase fw-bold mb-2">Inpocket</p>
                                        <table class="table table-bordered cstmTbl mb-0 w-100">
                                            <tr>
                                                <th class="text-center">&nbsp;</th>
                                                <th class="w-12">Code</th>
                                                <th class="w-35">Name</th>
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
                                                <td style="width:20px;" id="Ctd<?= $k ?>" onClick="<?php if ($option == 'post edit') { ?>delRow_inpocket(this, <?= $k ?>)<?php } ?>"><input type="hidden" class="cstm-rdo" value="Y" name="del_inpoc<?= $k ?>" id="del_inpoc<?= $k ?>" checked onClick="<?php if ($option == 'post edit') { ?>delRow_inpocket(this, <?= $k ?>)<?php } ?>"></td>
                                                <td><input type="text" class="w-100 form-control" name="asso_code<?php echo $k;?>" value="0000" readonly></td>
                                                <td><input type="text" class="w-100 form-control" name="asso_name<?php echo $k;?>" value="OWN" readonly></td>
                                                <td>
                                                <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                                                    <textarea class="w-100 resze form-control" rows="1" name="asso_acty<?php echo $k;?>" oninput="this.value = this.value.toUpperCase()" id="assoActy<?php echo $k;?>" onchange="fetchData(this, 'activity_code', ['assoDesc<?php echo $k;?>'], ['activity_desc'], 'activity_code')"><?= ($activity_code) ? $activity_code : '' ?></textarea> 
                                                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('activity_code', '<?= $displayId['activity_list_help_id'] ?>', 'assoActy<?= $k ?>', ['assoDesc<?php echo $k;?>'], ['activity_desc'], 'activity_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                                </div>
                                                </td>
                                                <td><textarea class="w-100 resze form-control" rows="1" class="w-100" name="asso_desc<?= $k ?>" id="assoDesc<?php echo $k;?>" readonly><?= $activity_description ?></textarea></td>
                                                <td class="text-center"><?php if($j == $count['row_inpocket']) {?><input type="button" name="Add_inpoc<?php echo $k;?>" value="+" title="Add Row" onClick="addInpocketRow(this,<?php echo $k;?>)"><?php }else if($option == 'add'){?><input type="button" name="Add_inpoc<?php echo $k;?>" value="+" title="Add Row" onClick="addInpocketRow(this,<?php echo $k;?>)"><?php } ?> 
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
                                                <th class="w-35">Name</th>
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
                                                <td id="Ctd2<?php echo $m; ?>" class="text-center" onClick="<?php if ($option == 'post edit') { ?>delRow_counsel(this, <?= $m ?>)<?php } ?>"><input type="hidden" class="cstm-rdo" value="Y" name="del_counsel<?php echo $m;?>" id="delCounsel<?php echo $m;?>" checked onClick="<?php if ($option == 'post edit') { ?>delRow_counsel(this, <?= $m ?>)<?php } ?>"></td>
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
                                                <td class="text-center"><input type="checkbox" class="cstm-rdo" name="pay<?php echo $m;?>" value="Y" <?php if($payable_ind == "Y") { echo "checked"; } ?>></td>
                                                <td class="text-center">
                                                <?php if($j == $count['row_counsel']){?><input type="button" name="Add_coun<?php echo $m;?>" value="+" title="Add Row" onClick="addCounselRow(this,<?php echo $m;?>)"><?php }else if($option == 'Add'){?><input type="hidden"   name="Add_coun<?php echo $m;?>" value="+" title="Add Row" onClick="addCounselRow(this,<?php echo $m;?>)"><?php } ?>
                                                    <!-- <i class="fa-solid fa-plus" title="ADD"></i> -->
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        </table>
                                        <a href="javascript:void(0);" id="counselDelete" onclick="deleteRow('tbody2', 'CounselRowNo', 'counselDelete', 'addCounselRow')" class="btn cstmBtn add text-white mt-3">Delete Row</a>
                                    </div>
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

                <input type="hidden" name="InpocketRowNo" id="InpocketRowNo" value="<?php echo $k;?>">
                <input type="hidden" name="user_option" value="<?php echo $option;?>">
                <input type="hidden" name="CounselRowNo" id="CounselRowNo" value="<?php echo $m;?>">
                <input type="hidden" name="IntermediateRowNo" value="<?php echo $n;?>">
                <input type="hidden" name="IntermediateFlag" value="<?php if($option == "copy") { echo "Y";} else{ echo "N";}?>">
                <input type="hidden" name="finsub" id="finsub" value="fsub">
            </form>
<?php } ?>
</section>

</main><!-- End #main -->
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
            Swal.fire({ text: 'Letter Date must be <= Current Date .......' }).then((result) => { setTimeout(() => { fld.focus()}, 500) }); fld.value = ''; return false ;
        }
        } 
    }

    function chkActivityDate(fld) {
        if(fld.value != "") 
        { 
            make_date(fld); 
            dateValid(fld,document.caseStatusForm.sysdate,'L',"Activity Date","Current Date")
        } else { Swal.fire({ text: 'Activity Date : '}).then((result) => { setTimeout(() => { fld.focus()}, 500) }); return false; }
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
        if(document.caseStatusForm.activity_date.value == "") { ind = 0; Swal.fire({ text: "Activity Date : " }).then((result) => { setTimeout(() => { document.caseStatusForm.activity_date.focus()}, 500) }); fld.value = ""; return false; } 
        if(fld.value != "" && ind == 1) 
        { 
            make_date(fld); 
            dateValid(fld,document.caseStatusForm.activity_date,'G',"Next Date","Activity Date")
        }  
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
         
        if(document.caseStatusForm.user_option.value == 'post edit') { 
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
                        <td id="Ctd${n}" onClick="<?php if ($option == 'post edit') { ?>delRow_inpocket(this, ${n})<?php } ?>"><input type="hidden" value="Y" name="del_inpoc${n}" id="del_inpoc${n}" title="Delete Row" onClick="<?php if ($option == 'post edit') { ?>delRow_inpocket(this, ${n})<?php } ?>"></td>
                        <td><input class="w-100 form-control" type="text" name="asso_code${n}" value="0000" readonly></td>
                        <td><input class="w-100 form-control" type="text" name="asso_name${n}" value="OWN" readonly></td>
                        <td>
                            <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                                <input class="w-100 form-control" type="text" name="asso_acty${n}" id="assoActy${n}" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'activity_code', ['assoDesc${n}'], ['activity_desc'], 'activity_code')">
                                <i class="fa-solid fa-binoculars icn-vw" onclick="showData('activity_code', '<?= $displayId['activity_list_help_id'] ?>', 'assoActy${n}', ['assoDesc${n}'], ['activity_desc'], 'activity_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                        </td>
                        <td><input class="w-100 form-control" type="text" name="asso_desc${n}" id="assoDesc${n}" readonly></td>
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

        if(document.caseStatusForm.user_option.value == 'post edit')
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
                    <td id="Ctd2${n}" onClick="<?php if ($option == 'post edit') { ?>delRow_counsel(this, ${n})<?php } ?>"><input type="hidden" value="Y" name="del_counsel${n}" id="delCounsel${n}" onClick="<?php if ($option == 'post edit') { ?>delRow_counsel(this, ${n})<?php } ?>"></td>
                    <td>
                        <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                            <input class="w-100 form-control" type="text" name="counsel_code${n}" id="counselCode${n}" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'associate_code', ['counselName${n}'], ['associate_name'], 'counsel_code')">
                            <i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselCode${n}', ['counselName${n}'], ['associate_name'], 'counsel_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                    </td>
                    <td><input class="w-100 form-control" type="text" name="counsel_name${n}" id="counselName${n}" readonly=true></td>
                    <td>
                        <div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
                            <input class="w-100 form-control" type="text" name="coun_acty${n}" id="counActy${n}" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'activity_code', ['counDesc${n}'], ['activity_desc'], 'activity_code')">
                            <i class="fa-solid fa-binoculars icn-vw" onclick="showData('activity_code', '<?= $displayId['activity_list_help_id'] ?>', 'counActy${n}', ['counDesc${n}'], ['activity_desc'], 'activity_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                    </td>
                    <td><input class="w-100 form-control" type="text" name="coun_desc${n}" id="counDesc${n}" readonly=true></td>
                    <td><input class="w-100" type="checkbox" name="pay${n}" value="Y"></td>
                    <td align="center"><input class="w-auto" type="button" name="Add_coun${n}" value="+" title="Add Row" onClick="addCounselRow(this,${n})"></td>
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