<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">
<div class="pagetitle w-100 float-start border-bottom pb-1">
    <h1 class="col-md-8 float-start">Case Status <span class="badge rounded-pill bg-dark"><?= (isset($option)) ? ucfirst($option) : '' ?></span> </h1>
    <?php
    if (isset($option)) { ?>
    <div class="col-md-4 float-end text-end mb-2">
        <a href="/sinhaco/case/status/" class="btn-bck btn btn-dark me-2">Back</a>
        <?php if ($option != 'view') { ?>
        <button type="button" class="btn-save btn btn-secondary" onclick="submitForm()">Save</button>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<!-- End Page Title -->

<section class="section dashboard d-inline-block w-100">
    <?php
    if (!isset($option)) { ?>
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
                <input type="text" class="form-control cstm-inpt" id="SerialNo" onchange="fetchData(this)" name="serial_no" required />
                <i class="fa-solid fa-eye inpt-vw" onclick="showData()" data-toggle="modal" data-target="#lookup"></i>
            </div>
            <div class="inpt-grp col-md-4 pe-0">
                <label class="d-block w-100 mb-2">Matter Code</label>
                <input type="text" class="form-control cstm-inpt" id="matterCode" readonly  />
            </div>
            <div class="inpt-grp col-md-12 pe-0 mt-3">
                <label class="d-block w-100 mb-2">Matter Description</label>
                <textarea name="" rows="1" class="form-control cstm-inpt" id="matterDesc" readonly ></textarea>
            </div>
        </div>
        <div class="w-100 float-start text-start mt-4 top-btn-fld">
            <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('add')">Add</button>
            <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('edit')">Edit</button>
            <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('view')">View</button>
            <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('delete')">Delete</button>
            <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('copy')">Copy</button>
            <button type="submit" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('letter')">Letter</button>
            <button type="reset" class="btn btn-primary cstmBtn mt-0" onclick="resetFields()">Reset</button>
            <button type="submit" class="btn btn-primary cstmBtn mt-0 d-none">Exit</button>
        </div>
    </form>

   <?php } else { ?>

    <!-- form  -->
    <div class="form" >
        <div class="col-md-12">
            <form action="" method="post" id="caseStatusForm">
                <div class="sec1 d-inline-block w-100 border mt-3">
                    <div class="inpt-sec-top d-inline-block w-100 p-3 border-bottom">
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Serial</label>
                            <input type="text" name="serial_no" class="form-control" readonly style="background: #e9ecef;" value="<?= ($option == 'add') ? '' : $caseHdrArray['serial_no'] ?>" <?= $permission ?> required />
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Activity Date</label>
                            <input type="text" name="activity_date" class="form-control" value="<?= ($option == 'add') ? '' : $caseHdrArray['activity_date'] ?>" <?= $permission ?>/>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Branch</label>
                            <select class="form-select"  name="branch_code" <?= $permission ?>>
                            <?php foreach($data['branches'] as $branch) { ?>
                            <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Prep By</label>
                            <input type="text" class="form-control" name="prepared_by" readonly  value="<?= ($option == 'add') ? '' : $caseHdrArray['prepared_by'] ?>" <?= $permission ?> <?= $permission ?>/>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mb-2">
                            <label for="" class="d-block w-100 mb-2">Prep On</label>
                            <input type="text" class="form-control" name="prepared_on" readonly  value="<?= ($option == 'add') ? '' : $caseHdrArray['prepared_on'] ?>" <?= $permission ?>/>
                        </div>
                        <div class="inpts col-md-3 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Letter Date</label>
                            <input type="text" class="form-control" name="letter_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['letter_date'] ?>" <?= $permission ?>/>
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
                        <button type="button" class="btn btn-primary text-uppercase border-0 btn-bdge float-end me-2">
                            ENTERED
                        </button>
                    </div>
                    <div class="mn-inpt-sec d-inline-block w-100 p-3">
                        <div class="all-inpt d-inline-block w-100">
                            <div class="inpts col-md-2 float-start px-2 position-relative">
                                <label for="" class="d-block w-100 mb-2">Matter</label>
                                <input type="text" class="form-control" name="matter_code" placeholder="search matter" value="<?= ($option == 'add') ? '' : $caseHdrArray['matter_code'] ?>" <?= $permission ?>/>
                                <i class="fa-solid fa-eye inpt-vw" onclick="showData()" data-toggle="modal" data-target="#lookup"></i>
                                <!-- <i class="fas fa-search"></i> -->
                            </div>
                            <div class="inpts col-md-6 float-start px-2 position-relative mtop">
                                <input type="text" class="form-control" name="mat_description" placeholder="search matter" readonly  value="<?= ($option == 'add') ? '' : $caseHdrArray['full_matter_desc'] ?>" <?= $permission ?>/>
                            </div>
                            <div class="inpts col-md-4 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Ref No.</label>
                                <input type="text" class="form-control" name="reference_desc" readonly  placeholder="Ref No" value="<?= ($option == 'add') ? '' : $caseHdrArray['reference_desc'] ?>" <?= $permission ?>/>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100 mt-2">
                            <div class="inpts col-md-3 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Client</label>
                                <input type="text" class="form-control"  name="client_code" placeholder="Client" readonly  value="<?= ($option == 'add') ? '' : $caseHdrArray['client_code'] ?>" <?= $permission ?>/>
                            </div>
                            <div class="inpts col-md-9 float-start px-2 position-relative mtop">
                                <input type="text" class="form-control" name="clint_name" placeholder="Client" readonly  value="<?= ($option == 'add') ? '' : $caseHdrArray['client_name'] ?>" <?= $permission ?>/>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100 mt-2">
                            <div class="inpts col-md-3 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Court</label>
                                <input type="text" class="form-control" placeholder="Count" name="court_code" readonly  value="<?= ($option == 'add') ? '' : $caseHdrArray['court_code'] ?>" <?= $permission ?>/>
                            </div>
                            <div class="inpts col-md-9 float-start px-2 position-relative mtop">
                                <input type="text" class="form-control" placeholder="Court" name="court_name" readonly  value="<?= ($option == 'add') ? '' : $caseHdrArray['code_desc'] ?>" <?= $permission ?>/>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100 mt-2">
                            <div class="inpts col-md-12 float-start px-2">
                                <textarea name="other_case_desc" id="" rows="2" class="form-control w-100" placeholder="Other case description" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['other_case_desc'] ?></textarea>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100 mt-2">
                            <div class="inpts col-md-4 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Appear For</label>
                                <input type="text" class="form-control" placeholder="Count"  name="appear_for" readonly  value="<?= ($option == 'add') ? '' : $caseHdrArray['appear_for'] ?>" <?= $permission ?>/>
                            </div>
                            <div class="inpts col-md-4 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Fixed For</label>
                                <textarea name="prev_fixed_for" id="" rows="2" class="form-control w-100" placeholder="Other case description" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['prev_fixed_for'] ?></textarea> 
                            </div>
                            <div class="inpts col-md-4 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Previous Remarks</label>
                                <textarea name="last_remark" id="" rows="2" class="form-control w-100" readonly  placeholder="Other case description" <?= $permission ?>>last remark</textarea>
                            </div>
                        </div>
                        <div class="all-inpt d-inline-block w-100">
                            <div class="inpts col-md-3 float-start px-2">
                                <label for="" class="d-block w-100 mb-2">Last Date</label>
                                <input type="text" class="form-control" placeholder="last Date" name="prev_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['prev_date'] ?>" <?= $permission ?>/>
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
                                    <input type="radio" id="bilable" name="billable_option" class="cstm-rdo float-start d-block me-1 mt-1" <?php if ($option != 'add') if($caseHdrArray['billable_option'] == 'Y'){ echo 'checked';}?> <?= $permission ?>/>
                                    <label for="bilable" class="d-block w-100">Bilable</label>
                                </div>
                                <div class="rdolst d-block float-start w-25 me-2">
                                    <input type="radio" id="prebilable" name="billable_option" class="cstm-rdo float-start d-block me-1 mt-1" <?php if ($option != 'add') if($caseHdrArray['billable_option'] == 'P'){ echo 'checked';}?> <?= $permission ?>/>
                                    <label for="prebilable" class="d-block w-100">Pre-Bilable</label>
                                </div>
                                <div class="rdolst d-block float-start w-25 me-2">
                                    <input type="radio" id="nonbilable" name="billable_option" class="cstm-rdo float-start d-block me-1 mt-1" <?php if ($option != 'add') if($caseHdrArray['billable_option'] == 'N'){ echo 'checked';}?> <?= $permission ?>/>
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
                                <select name="instrument_code" id="" class="form-select" <?= $permission ?>>
                                <?php foreach($caseHdrArray['instrument_status'] as $instrument_status) { ?> 
                                    <option value="<?php echo $instrument_status['code_code'];?>"  <?php if ($option != 'add') if ($caseHdrArray['instrument_code'] == $instrument_status['code_code']) { echo 'selected';} ?>><?php echo $instrument_status['code_desc'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="inpts col-md-3 float-start px-2 pe-0 mt-3">
                                <label for="" class="d-block w-100 mb-2">Inst No.</label>
                                <input type="text" class="form-control" placeholder="Inst No" name="instrument_no" value="<?= ($option == 'add') ? '' : $caseHdrArray['instrument_no'] ?>" <?= $permission ?>/>
                            </div>
                            <div class="inpts col-md-3 float-start px-2 pe-0 mt-3">
                                <label for="" class="d-block w-100 mb-2">Issue Date</label>
                                <input type="text" class="form-control" placeholder="Issue Date" name="instrument_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['instrument_date'] ?>" <?= $permission ?>/>
                            </div>
                            <div class="inpts col-md-5 float-start px-2 pe-0 mt-3">
                                <label for="" class="d-block w-100 mb-2">Judge</label>
                                <input type="text" class="form-control" placeholder="Issue Date" name="judge_name" value="<?= ($option == 'add') ? '' : $caseHdrArray['judge_name'] ?>" <?= $permission ?>/>
                            </div>
                            <div class="inpts col-md-4 float-start px-2 pe-0 mt-3">
                                <label for="" class="d-block w-100 mb-2">Next date</label>
                                <input type="text" class="form-control" placeholder="Next date" name="next_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['next_date'] ?>" <?= $permission ?>/>
                            </div>
                            <div class="position-relative intrmde-dts d-inline-block col-md-12 border mt-5 py-2">   
                                <p class="sectitl text-uppercase">Intermediate dates</p>                        
                                <div class="inpts w-50 float-start px-2 pe-0 mt-3 position-relative">
                                    <label for="" class="d-block w-100 mb-2">Fixed For</label>
                                    <textarea name="next_fixed_for" id="" rows="2" class="form-control" placeholder="Fixed For" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['next_fixed_for'] ?></textarea>
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="inpts w-50 float-start px-2 mt-3">
                                    <label for="" class="d-block w-100 mb-2">Remarks</label>
                                    <textarea name="remarks" id="" rows="2" class="form-control" placeholder="Remarks" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['remarks'] ?></textarea>
                                </div>
                            </div>
                            <div class="inpts w-100 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Particulars</label>
                                <textarea name="letter_body_desc" id="" rows="2" class="form-control" placeholder="Particulars" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['header_desc'] ?></textarea>
                            </div>
                            <div class="inpts w-100 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Other Remarks</label>
                                <textarea name="other_body_desc" id="" rows="2" class="form-control" placeholder="Other Remarks" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['other_body_desc'] ?></textarea>
                            </div>
                            <div class="inpts w-100 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Footer</label>
                                <textarea name="footer_desc" id="" rows="2" class="form-control" placeholder="Footer" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['footer_desc'] ?></textarea>
                            </div>
                            <div class="inpts w-100 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">CC</label>
                                <textarea name="cc_desc" id="" rows="2" class="form-control" placeholder="CC" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['cc_desc'] ?></textarea>
                            </div>
                            <div class="inpts col-md-4 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Matter Status</label>
                                <select name="matter_status" id="" class="form-select" <?= $permission ?>>
                                <?php foreach($caseHdrArray['matter_status'] as $matter_status) { ?>   
                                <option value="<?php echo $matter_status['status_code'];?>" <?php if ($option != 'add') if($matter_status['status_code'] ==  $caseHdrArray['matter_status_code']){ echo 'selected'; }?>><?php echo $matter_status['status_desc'];?></option>
                                <?php } ?>
                                </select>
                            </div>
                            <div class="inpts col-md-4 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Status Date</label>
                                <input type="text" class="form-control" placeholder="Status Date" name="status_date" readonly  value="<?= ($option == 'add') ? '' : $caseHdrArray['status_date'] ?>" <?= $permission ?>/>
                            </div> 
                            <div class="inpts col-md-4 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Alert Date</label>
                                <input type="date" class="form-control" placeholder="Alert Date" name="alert_date" value="<?= ($option == 'add') ? '' : $caseHdrArray['alert_date'] ?>" <?= $permission ?>/>
                            </div>
                            <div class="inpts col-md-5 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Advocate</label>
                                <textarea name="signatory" id="" rows="2" class="form-control" placeholder="Advocate" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['signatory'] ?></textarea>
                            </div>
                            <div class="inpts col-md-7 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Narration</label>
                                <textarea name="alert_narration" id="" rows="2" class="form-control" placeholder="Narration" <?= $permission ?>><?= ($option == 'add') ? '' : $caseHdrArray['alert_narration'] ?></textarea>
                            </div>
                            <div class="tblMn d-inline-block w-100 px-2 mt-4">
                                <div class="tbladd">
                                    <p class="d-inline-block w-100 px-3 py-2 text-uppercase fw-bold mb-2">Inpocket</p>
                                    <table class="table table-bordered cstmTbl mb-0">
                                        <tr>
                                            <th class="text-center">&nbsp;</th>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Acty</th>
                                            <th>Description</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><input type="checkbox" class="cstm-rdo"></td>
                                            <td>000</td>
                                            <td>OWN</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">
                                                <i class="fa-solid fa-trash" title="Delete"></i>
                                            </td>
                                        </tr>
                                    </table>
                                    <a href="javascript:void(0);" class="btn cstmBtn add text-white mt-3">Add</a>
                                </div>
                            </div>
    
                            <div class="tblMn d-inline-block w-100 px-2 mt-4 border-top pt-4">
                                <div class="tbladd">
                                    <p class="d-inline-block w-100 px-3 py-2 text-uppercase fw-bold mb-2">Counsel</p>
                                    <table class="table table-bordered cstmTbl mb-0">
                                        <tr>
                                            <th class="text-center">&nbsp;</th>
                                            <th>Cncl</th>
                                            <th>Name</th>
                                            <th>Acty</th>
                                            <th>Description</th>
                                            <th>Pay?</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><input type="checkbox" class="cstm-rdo"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center"><input type="checkbox" class="cstm-rdo"></td>
                                            <td class="text-center">
                                                <i class="fa-solid fa-trash" title="Delete"></i>
                                            </td>
                                        </tr>
                                    </table>
                                    <a href="javascript:void(0);" class="btn cstmBtn add text-white mt-3">Add</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <?php   } ?>
</section>

</main><!-- End #main -->
<script>
function showData (property = 'serial_no') {

    fetch("/api/lookup?display_id=4532")
    .then((response) => response.json())
    .then((data) => {
        var tr = "<tbody>", th = "<thead> <tr><th></th>";
        
        for (let heading of data.th) {
            th = th + `<th class="fw-normal"> ${heading} </th>`;
        }
        th = th + '</tr> </thead>';
        
        for (let obj of data.td) {
            let row = '', td = '';
            row = row + `<tr>
                <td class="text-center"><input type="radio" class="cstm-inpt-rdo" value="${obj[property]}" name="serial_no" onclick="setSerialNo(this); fetchData(this)" data-dismiss="modal"/></td>`;
            for (let dt in obj) {
                td = td + `<td> ${obj[dt]} </td>`;
            }
            row = row + td + '</tr>';
            tr = tr + row;
        }

        let lookupTable = th + tr + "</tbody>";
        document.getElementById('lookupTable').innerHTML = lookupTable;
    });
}

function setSerialNo(e) {
    document.getElementById('SerialNo').value = e.value;
    
}

function fetchData(e) {
    console.log(e.value);

    fetch(`/api/lookup/${e.value}/?display_id=4532`)
    .then((response) => response.json())
    .then((data) => {
        document.getElementById('matterCode').value = data[0].matter_code;
        document.getElementById('matterDesc').innerText = data[0].matter_desc;
        console.log(data);
    });
}

function formOption(option) {
    var form = document.getElementById("caseStatus");
    var SerialNo = document.getElementById("SerialNo");

    form.action = `/sinhaco/case/status/${option}`;

    if(option != 'add') SerialNo.required = true; 
    else SerialNo.required = false;
}

function resetFields() {
    textarea = document.getElementsByTagName('textarea');
    for(let input of textarea) input.innerHTML = '';
}

function submitForm() {
    document.getElementById('caseStatusForm').submit();
}
</script>
<?= $this->endSection() ?>