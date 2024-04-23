<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">
<div class="pagetitle w-100 float-start border-bottom pb-1">
  <h1 class="col-md-8 float-start">Case Status </h1>
  <div class="col-md-4 float-end text-end mb-2">
    <a href="/sinhaco/case/status/" class="btn-bck btn btn-dark me-2">Back</a>
    <button type="button" class="btn-save btn btn-secondary">Save</button>
</div>
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
                    <option value="">Kolkata</option>
                </select>
            </div>
            <div class="inpt-grp col-md-4 pe-0 position-relative">
                <label class="d-block w-100 mb-2">Serial No</label>
                <input type="text" class="form-control cstm-inpt" id="SerialNo" onchange="fetchData(this)" name="serial_no" required />
                <i class="fa-solid fa-eye inpt-vw" onclick="showData()" data-toggle="modal" data-target="#lookup"></i>
            </div>
            <div class="inpt-grp col-md-4 pe-0">
                <label class="d-block w-100 mb-2">Matter Code</label>
                <input type="text" class="form-control cstm-inpt" id="matterCode" readonly disabled />
            </div>
            <div class="inpt-grp col-md-12 pe-0 mt-3">
                <label class="d-block w-100 mb-2">Matter Description</label>
                <textarea name="" rows="1" class="form-control cstm-inpt" id="matterDesc" readonly disabled></textarea>
            </div>
        </div>
        <div class="w-100 float-start text-start mt-4 top-btn-fld">
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('add')">Add</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('edit')">Edit</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('view')">View</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('delete')">Delete</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('copy')">Copy</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 me-2" onclick="formOption('letter')">Letter</button>
            <button type="reset" class="btn btn-primary cstmBtn mt-0" onclick="resetFields()">Reset</button>
            <button type="button" class="btn btn-primary cstmBtn mt-0 d-none">Exit</button>
        </div>
    </form>

   <?php } else { ?>

    <!-- form  -->
    <div class="form">
        <div class="col-md-12">
            <div class="sec1 d-inline-block w-100 border mt-3">
                <div class="inpt-sec-top d-inline-block w-100 p-3 border-bottom">
                    <div class="inpts col-md-3 float-start px-2 mb-2">
                        <label for="" class="d-block w-100 mb-2">Serial</label>
                        <input type="text" class="form-control" value="<?= $caseHdrArray['serial_no'] ?>"/>
                    </div>
                    <div class="inpts col-md-3 float-start px-2 mb-2">
                        <label for="" class="d-block w-100 mb-2">Activity Date</label>
                        <input type="text" class="form-control" value="<?= $caseHdrArray['activity_date'] ?>"/>
                    </div>
                    <div class="inpts col-md-3 float-start px-2 mb-2">
                        <label for="" class="d-block w-100 mb-2">Branch</label>
                        <select class="form-select">
                            <option>Kolkata</option>
                        </select>
                    </div>
                    <div class="inpts col-md-3 float-start px-2 mb-2">
                        <label for="" class="d-block w-100 mb-2">Prep By</label>
                        <input type="text" class="form-control" value="<?= $caseHdrArray['prepared_by'] ?>"/>
                    </div>
                    <div class="inpts col-md-3 float-start px-2 mb-2">
                        <label for="" class="d-block w-100 mb-2">Prep On</label>
                        <input type="text" class="form-control" value="<?= $caseHdrArray['prepared_on'] ?>"/>
                    </div>
                    <div class="inpts col-md-3 float-start px-2">
                        <label for="" class="d-block w-100 mb-2">Letter Date</label>
                        <input type="text" class="form-control" value="<?= $caseHdrArray['letter_date'] ?>"/>
                    </div>
                    <button type="button" class="btn btn-primary text-uppercase border-0 btn-bdge float-end me-2">
                        ENTERED
                    </button>
                </div>
                <div class="mn-inpt-sec d-inline-block w-100 p-3">
                    <div class="all-inpt d-inline-block w-100">
                        <div class="inpts col-md-2 float-start px-2 position-relative">
                            <label for="" class="d-block w-100 mb-2">Matter</label>
                            <input type="text" class="form-control" placeholder="search matter" value="<?= $caseHdrArray['matter_code'] ?>"/>
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="inpts col-md-6 float-start px-2 position-relative mtop">
                            <input type="text" class="form-control" placeholder="search matter" value="<?= $caseHdrArray['full_matter_desc'] ?>"/>
                        </div>
                        <div class="inpts col-md-4 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Ref No.</label>
                            <input type="text" class="form-control" placeholder="Ref No" value="<?= $caseHdrArray['reference_desc'] ?>"/>
                        </div>
                    </div>
                    <div class="all-inpt d-inline-block w-100 mt-2">
                        <div class="inpts col-md-3 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Client</label>
                            <input type="text" class="form-control" placeholder="Client" value="<?= $caseHdrArray['client_code'] ?>"/>
                        </div>
                        <div class="inpts col-md-9 float-start px-2 position-relative mtop">
                            <input type="text" class="form-control" placeholder="Client" value="<?= $caseHdrArray['client_name'] ?>"/>
                        </div>
                    </div>
                    <div class="all-inpt d-inline-block w-100 mt-2">
                        <div class="inpts col-md-3 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Count</label>
                            <input type="text" class="form-control" placeholder="Count" value="<?= $caseHdrArray['court_code'] ?>"/>
                        </div>
                        <div class="inpts col-md-9 float-start px-2 position-relative mtop">
                            <input type="text" class="form-control" placeholder="Count" value="<?= $caseHdrArray['code_desc'] ?>"/>
                        </div>
                    </div>
                    <div class="all-inpt d-inline-block w-100 mt-2">
                        <div class="inpts col-md-12 float-start px-2">
                            <textarea name="" id="" rows="2" class="form-control w-100" placeholder="Other case description"><?= $caseHdrArray['other_case_desc'] ?></textarea>
                        </div>
                    </div>
                    <div class="all-inpt d-inline-block w-100 mt-2">
                        <div class="inpts col-md-4 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Appear For</label>
                            <input type="text" class="form-control" placeholder="Count" value="<?= $caseHdrArray['appear_for'] ?>"/>
                        </div>
                        <div class="inpts col-md-4 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Fixed For</label>
                            <textarea name="" id="" rows="2" class="form-control w-100" placeholder="Other case description"><?= $caseHdrArray['prev_fixed_for'] ?></textarea>
                        </div>
                        <div class="inpts col-md-4 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Previous Remarks</label>
                            <textarea name="" id="" rows="2" class="form-control w-100" placeholder="Other case description">last remark</textarea>
                        </div>
                    </div>
                    <div class="all-inpt d-inline-block w-100">
                        <div class="inpts col-md-3 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Last Date</label>
                            <input type="text" class="form-control" placeholder="last Date" value="<?= $caseHdrArray['prev_date'] ?>"/>
                        </div>
                        <div class="inpts col-md-3 float-start px-2">
                            <label for="" class="d-block w-100 mb-2">Forwarding</label>
                            <select name="" id="" class="form-select">
                                <option value="">Yes</option>
                            </select>
                        </div>
                        <div class="inpts col-md-6 float-start px-2 mtop rdobtnall">
                            <div class="rdolst d-block float-start w-25 me-2">
                                <input type="radio" id="bilable" name="bill" class="cstm-rdo float-start d-block me-1 mt-1"/>
                                <label for="bilable" class="d-block w-100">Bilable</label>
                            </div>
                            <div class="rdolst d-block float-start w-25 me-2">
                                <input type="radio" id="prebilable" name="bill" class="cstm-rdo float-start d-block me-1 mt-1"/>
                                <label for="prebilable" class="d-block w-100">Pre-Bilable</label>
                            </div>
                            <div class="rdolst d-block float-start w-25 me-2">
                                <input type="radio" id="nonbilable" name="bill" class="cstm-rdo float-start d-block me-1 mt-1"/>
                                <label for="nonbilable" class="d-block w-100">Non-Bilable</label>
                            </div>                                
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Instrument Stage</label>
                            <select name="" id="" class="form-select">
                                <option value="">N/A</option>
                            </select>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Letter Stage</label>
                            <select name="" id="" class="form-select">
                                <option value="">N/A</option>
                            </select>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Instrument Type</label>
                            <select name="" id="" class="form-select">
                                <option value="">NBW</option>
                            </select>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 pe-0 mt-3">
                            <label for="" class="d-block w-100 mb-2">Inst No.</label>
                            <input type="text" class="form-control" placeholder="Inst No"/>
                        </div>
                        <div class="inpts col-md-3 float-start px-2 pe-0 mt-3">
                            <label for="" class="d-block w-100 mb-2">Issue Date</label>
                            <input type="text" class="form-control" placeholder="Issue Date"/>
                        </div>
                        <div class="inpts col-md-5 float-start px-2 pe-0 mt-3">
                            <label for="" class="d-block w-100 mb-2">Judge</label>
                            <input type="text" class="form-control" placeholder="Issue Date" value="<?= $caseHdrArray['judge_name'] ?>"/>
                        </div>
                        <div class="inpts col-md-4 float-start px-2 pe-0 mt-3">
                            <label for="" class="d-block w-100 mb-2">Next date</label>
                            <input type="text" class="form-control" placeholder="Next date" value="<?= $caseHdrArray['next_date'] ?>"/>
                        </div>
                        <div class="position-relative intrmde-dts d-inline-block col-md-12 border mt-5 py-2">   
                            <p class="sectitl text-uppercase">Intermediate dates</p>                        
                            <div class="inpts w-50 float-start px-2 pe-0 mt-3 position-relative">
                                <label for="" class="d-block w-100 mb-2">Fixed For</label>
                                <textarea name="" id="" rows="2" class="form-control" placeholder="Fixed For"><?= $caseHdrArray['next_fixed_for'] ?></textarea>
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="inpts w-50 float-start px-2 mt-3">
                                <label for="" class="d-block w-100 mb-2">Remarks</label>
                                <textarea name="" id="" rows="2" class="form-control" placeholder="Remarks"></textarea>
                            </div>
                        </div>
                        <div class="inpts w-100 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Particulars</label>
                            <textarea name="" id="" rows="2" class="form-control" placeholder="Particulars"><?= $caseHdrArray['header_desc'] ?></textarea>
                        </div>
                        <div class="inpts w-100 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Other Remarks</label>
                            <textarea name="" id="" rows="2" class="form-control" placeholder="Other Remarks"></textarea>
                        </div>
                        <div class="inpts w-100 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Footer</label>
                            <textarea name="" id="" rows="2" class="form-control" placeholder="Footer"></textarea>
                        </div>
                        <div class="inpts w-100 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">CC</label>
                            <textarea name="" id="" rows="2" class="form-control" placeholder="CC"></textarea>
                        </div>
                        <div class="inpts col-md-4 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Matter Status</label>
                            <input type="text" class="form-control" placeholder="Matter Status">
                        </div>
                        <div class="inpts col-md-4 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Status Date</label>
                            <input type="text" class="form-control" placeholder="Status Date">
                        </div> 
                        <div class="inpts col-md-4 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Alert Date</label>
                            <input type="date" class="form-control" placeholder="Alert Date">
                        </div>
                        <div class="inpts col-md-5 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Advocate</label>
                            <textarea name="" id="" rows="2" class="form-control" placeholder="Advocate"></textarea>
                        </div>
                        <div class="inpts col-md-7 float-start px-2 mt-3">
                            <label for="" class="d-block w-100 mb-2">Narration</label>
                            <textarea name="" id="" rows="2" class="form-control" placeholder="Narration"></textarea>
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
                                        <td>Indranil Banerjee</td>
                                        <td></td>
                                        <td>This is a dummey description for test.</td>
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
                                        <td>000</td>
                                        <td>Indranil Banerjee</td>
                                        <td></td>
                                        <td>This is a dummey description for test.</td>
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
        </div>
    </div>
    
    <?php   } ?>
</section>

</main><!-- End #main -->
<script>
function showData () {
    document.getElementById('dataSection').classList.remove("d-none");

    fetch("/api/lookup?display_id=4532")
    .then((response) => response.json())
    .then((data) => {
        var tr = "";
        for(let row of data) {
            tr = tr + `<tr>
                <td class="text-center"><input type="radio" class="cstm-inpt-rdo" value="${row.serial_no}" name="serial_no" onclick="setSerialNo(this); fetchData(this)" data-dismiss="modal"/></td>
                <td> ${row.serial_no} </td>
                <td> ${row.activity_date} </td>
                <td> ${row.matter_code} </td>
                <td> ${row.matter_desc1} </td>
                <td> ${row.matter_desc2} </td>
                <td> ${row.next_date} </td>
                <td> ${row.next_fixed_for} </td>
            </tr>`;
        }
        document.getElementById('tableBody').innerHTML = tr;
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
    form.action = `/sinhaco/case/status/${option}`;
    form.submit();
}

function resetFields() {
    textarea = document.getElementsByTagName('textarea');
    for(let input of textarea) input.innerHTML = '';
}
</script>
<?= $this->endSection() ?>