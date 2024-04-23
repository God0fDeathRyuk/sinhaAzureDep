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
        <div class="pagetitle w-100 float-start border-bottom pb-1">
            <h1 class="col-md-8 float-start">Employee Master (<?php echo strtoupper($option) ?>)</h1>
            
        </div><!-- End Page Title -->
        <form action="<?php if($option == 'Add'){ echo "/sinhaco/master/mas-employee-master?user_option=Add";} if($option == 'Edit'){ echo '/sinhaco/master/mas-employee-master?user_option=Edit';}if($option == 'Delete'){ echo '/sinhaco/master/mas-employee-master?user_option=Delete';}?>" method="post" >
        <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-4 float-start px-2 mb-3 <?php if($option == 'Add'){echo 'd-none'; }else{ echo 'd-block'; }?>">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Employee Id </label>
                            <input type="text" class="form-control w-100 float-start" name="employee_id"
                                id="employee_id" onKeyUp="javascript:(this.value=this.value.toUpperCase());" value="<?= ($option!='Add')?$data['employee_id']:'' ?>"  readonly/>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Name <strong
                                    class="text-danger">*</strong></label>
                            <input type="text" class="form-control w-100 float-start" placeholder="Name" required name="employee_name"
                                id="employee_name" value="<?= ($option!='Add')?$data['employee_name']:'' ?>"
                                <?php if($option=='Add'){ ?>
                                onblur="duplicate_code_check(this.value,'<?php echo $option;?>','getTotEmpCount')<?php }?>"
                                onKeyUp="javascript:(this.value=this.value.toUpperCase());" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-5 float-start px-2 mb-3 h100">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Address <strong
                                    class="text-danger">*</strong></label>
                            <textarea rows="3" class="form-control w-100 float-start" required name="employee_address1"
                                id="employee_address1" 
                                onKeyUp="javascript:(this.value=this.value.toUpperCase());" <?php echo $redokadd;?>><?= ($option!='Add')?$data['employee_address1'].$data['employee_address2'].$data['employee_address3']:'' ?></textarea>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-3 h100">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Pin</label>
                            <input type="text" class="form-control w-100 float-start" placeholder="Pin" name="employee_pin"
                                id="employee_pin" value="<?= ($option!='Add')?$data['employee_pin']: '' ?>" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3 h100">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Phone</label>
                            <input type="text" class="form-control w-100 float-start" placeholder="Phone" name="employee_phone"
                                id="employee_phone" value="<?= ($option!='Add')?$data['employee_phone']:'' ?>" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3 mt-3 position-relative">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Department <strong
                                    class="text-danger">*</strong></label>
                            <input type="text" class="form-control" name="department_name" id="departmentName"
                                placeholder="Department Code"
                                onchange="fetchData(this, 'department_code', ['departmentCode', 'departmentName'], ['department_code', 'designation_name'], 'department')"
                                required value="<?= ($option!='Add') ? $data['department_name'] : '' ?>" <?php echo $redokadd;?> readonly/>
                            <input type="hidden" class="form-control" name="department_code" id="departmentCode"
                                placeholder="departmentCode"
                                value="<?= ($option!='Add') ? $data['department_code'] : '' ?>" />
                            <i class="fa fa-binoculars icn-vw"
                                onclick="showData('department_code', '<?= '4121' ?>', 'departmentCode', [ 'departmentCode','departmentName'], ['department_code','department_name'], 'department')"
                                data-toggle="modal" data-target="#lookup"></i>

                        </div>
                        <div class="col-md-4 float-start px-2 mb-3 mt-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Login Id</label>
                            <input type="text" class="form-control w-100 float-start" placeholder="Login Id" name="login_id" id="login_id"
                                value="<?= ($option!='Add')?$data['login_id']:'' ?>" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3 mt-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Employee</label>
                            <div class="d-inline-block w-100 rdoBdr">
                                <div class="d-block w-35 float-start mr-2">
                                    <input type="radio" value="Y" name="employee_ind" id="employee_ind" <?= ($option!='Add')?($data['employee_ind']=='Y')?'checked':'':'' ?> <?php echo $redokadd;?>>
                                      <label for="yes">Yes</label>
                                </div>
                                <div class="d-block w-35 float-start">
                                    <input type="radio" value="N" name="employee_ind" id="employee_ind" <?= ($option!='Add')?($data['employee_ind']=='N')?'checked':'':'' ?> <?php echo $redokadd;?>>
                                      <label for="no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 float-start px-2">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Salesman</label>
                            <div class="d-inline-block w-100 rdoBdr">
                                <div class="d-block w-35 float-start mr-2">
                                    <input type="radio" value="Y" name="salesman_ind" id="salesman_ind" <?= ($option!='Add')?($data['salesman_ind']=='Y')?'checked':'':'' ?> <?php echo $redokadd;?>>
                                      <label for="slsyes">Yes</label>
                                </div>
                                <div class="d-block w-35 float-start">
                                    <input type="radio" value="N" name="salesman_ind" id="salesman_ind" <?= ($option!='Add')?($data['salesman_ind']=='N')?'checked':'':'' ?> <?php echo $redokadd;?>>
                                      <label for="slsno">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">User Type <strong
                                    class="text-danger">*</strong></label>
                            <select class="form-select" name="user_type" id="user_type" required <?php echo $disview;?>>
                                <option value="">Select</option>
                                <option value="Administrator"
                                    <?= ($option!='Add')?($data['user_type'] == 'Administrator')?'selected': '':''?>>
                                    Administrator</option>
                                <option value="General"
                                    <?= ($option!='Add')?($data['user_type'] == 'General')?'selected': '':''?>>General
                                </option>
                                <option value="Supervisor"
                                    <?= ($option!='Add')?($data['user_type'] == 'Supervisor')?'selected': '':''?>>
                                    Supervisor</option>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Status <strong
                                    class="text-danger">*</strong></label>
                            <select class="form-select"  name="status_code" id="status_code" required <?php echo $disview;?>>
                                <option value="">Select</option>
                                <option value="A"
                                    <?= ($option!='Add')?($data['status_code'] == 'A')?'selected': '':''?>>Active
                                </option>
                                <option value="L"
                                    <?= ($option!='Add')?($data['status_code'] == 'L')?'selected': '':''?>>Left</option>
                                <option value="E"
                                    <?= ($option!='Add')?($data['status_code'] == 'E')?'selected': '':''?>>Expired
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Extra Time <strong
                                    class="text-danger">*</strong></label>
                            <select class="form-select"  name="atten_indicator" id="atten_indicator"
                                value="<?= ($option!='Add')?$data['atten_indicator']:'' ?>" required <?php echo $disview;?>>
                                <option value="">Select</option>
                                <option value="N"
                                    <?= ($option!='Add')?($data['atten_indicator'] == 'N')?'selected': '':''?>>No
                                </option>
                                <option value="Y"
                                    <?= ($option!='Add')?($data['atten_indicator'] == 'Y')?'selected': '':''?>>Yes
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Attendence Id</label>
                            <input type="text" class="form-control" name="attn_id" id="attn_id"placeholder="Attendence Id"
                                value="<?= ($option!='Add')?$data['attn_id']:'' ?>" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Initial <strong
                                    class="text-danger">*</strong></label>
                            <input type="text" class="form-control" required placeholder="Initial" name="employee_initial"
                                id="employee_initial" value="<?=($option!='Add')?$data['employee_initial']:'' ?>" onKeyUp="javascript:(this.value=this.value.toUpperCase());" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">City</label>
                            <input type="text" class="form-control" placeholder="City" name="employee_city" id="employee_city"
                                value="<?= ($option!='Add')?$data['employee_city']:'' ?>"  onKeyUp="javascript:(this.value=this.value.toUpperCase());" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Pan No</label>
                            <input type="text" class="form-control" placeholder="Pan No" name="employee_pan_no" name="employee_pan_no" maxlength="10" 
                                value="<?= ($option!='Add')?$data['employee_pan_no']:'' ?>"  onKeyUp="javascript:(this.value=this.value.toUpperCase());"<?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Mobile</label>
                            <input type="tel" placeholder="Mobile" pattern="[7-9]{1}[0-9]{9}" class="form-control" name="employee_mobile" id="employee_mobile"
                                value="<?= ($option!='Add')?$data['employee_mobile']: '' ?>" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3 position-relative">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Designation<strong
                                    class="text-danger">*</strong></label>
                            <input type="text" class="form-control" readonly name="designation_name" id="designationName"
                                placeholder="Designation"
                                onchange="fetchData(this, 'designation_code', ['designationCode', 'designationName'], ['designation_code', 'designation_name'], 'designation')"
                                 value="<?= ($option!='Add') ? $data['designation_name'] : '' ?>" <?php echo $redokadd;?> required/>
                            <input type="hidden" class="form-control" name="designation_code" id="designationCode"
                                placeholder="Designation"
                                value="<?= ($option!='Add') ? $data['designation_code'] : '' ?>" />
                            <i class="fa fa-binoculars icn-vw"
                                onclick="showData('designation_code', '<?= '4131' ?>', 'designationCode', [ 'designationCode','designationName'], ['designation_code','designation_name'], 'designation')"
                                data-toggle="modal" data-target="#lookup"></i>

                        </div>
                        <div class="col-md-4 float-start px-2 mb-3 d-none">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                value="<?= ($option!='Add')?$data['password']:'' ?>" />
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3 hgt70">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">User</label>
                            <div class="d-inline-block w-100 rdoBdr">
                                <div class="d-block w-35 float-start mr-2">
                                    <input type="radio" value="Y" id="user_ind" name="user_ind" <?= ($option!='Add')?($data['user_ind']=='Y')?'checked':'':'' ?> <?php echo $redokadd;?>> 
                                      <label for="usryes">Yes</label>
                                </div>
                                <div class="d-block w-35 float-start">
                                    <input type="radio" value="N" id="user_ind" name="user_ind" <?= ($option!='Add')?($data['user_ind']=='N')?'checked':'':'' ?> <?php echo $redokadd;?>>
                                      <label for="usrno">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3 position-relative hgt70">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Branch  <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control" readonly name="branch_name" id="branchName" placeholder="Branch" onchange="fetchData(this, 'branch_code', ['branchCode', 'branchName'], ['branch_code', 'branch_name'], 'branch')" required  value="<?= ($option!='Add') ? $data['branch_name'] : '' ?>" <?php echo $redokadd;?>/>
                    <input type="hidden" class="form-control" name="branch_code" id="branchCode" placeholder="Branch" value="<?= ($option!='Add') ? $data['branch_code'] : '' ?>" />
                    <i class="fa fa-binoculars icn-vw" onclick="showData('branch_code', '<?= '4024' ?>', 'branchCode', [ 'branchCode','branchName'], ['branch_code','branch_name'], 'branch')"  data-toggle="modal" data-target="#lookup"></i>
              
            </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Email</label>
                            <input type="text" class="form-control" placeholder="Email" name="email_id" id="email_id"
                                value="<?= ($option!='Add')?$data['email_id']: '' ?>" <?php echo $redokadd;?>/>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Gross Salary</label>
                            <input type="text" class="form-control" placeholder="Gross Salary" name="gross_salary" id="gross_salary"
                                value="<?= ($option!='Add')?$data['gross_salary']:'' ?>" <?php echo $redokadd;?>/>
                        </div>
<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
                        <input type="hidden" name="last_update_id" id="last_update_id" value="<?= ($option!='Add')?$sessionName:'' ?>" >
                        <input type="hidden" name="last_update_dt" id="last_update_dt" value="<?= ($option!='Add')?date('Y-m-d'):'' ?>" >
                        <input type="hidden" name="prepared_by" id="prepared_by" value="<?= ($option=='Add')?$sessionName:$data['prepared_by'] ?>" >
                        <input type="hidden" name="prepared_on" id="prepared_on" value="<?= ($option=='Add')?date('Y-m-d'):$data['prepared_on'] ?>" >
                        <div class="d-inline-block w-100 mt-3">
                        <input type="hidden" name="finsub" id="finsub" value="fsub">
            <button type="submit" class="btn btn-primary cstmBtn ms-2" onclick="return validation2()" <?php echo $disview;?>>Save</button>
            <?php if($option=="Delete"){?>
                        <button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn ms-2">Delete</button>
                        <?php } ?>                
            <a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2">Back</a>
            </div>
                    </div>
                    
                </div>
                
            </div>
        </section>
    </form>
</main><!-- End #main -->

<?= $this->endSection() ?>