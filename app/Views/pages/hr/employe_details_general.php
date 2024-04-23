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
<div class="pagetitle">
      <h1>Employee Details [ <?php echo  $user_option;?> ]</h1>
    </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
      <form method="post" action="" id="empDetailsGen" >
		<div class="col-md-12 mt-2">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="bdge d-block w-100 mt-2 mb-2">Personal</div>
				<div class="col-md-4 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Code</label>
                    <input type="text" class="form-control"  value="<?= ($user_option!="Add")?$data['employee_id']:'' ?>" readonly/>
					<input type="hidden" class="form-control" name="employee_id" id="employee_id" value="<?= $str; ?>" readonly/>
				</div>
				<div class="col-md-4 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Initial <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" required  name="employee_initial"  id="employee_initial"  value="<?= ($user_option!="Add")?$data['employee_initial']:'' ?>" style="text-transform:uppercase;" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-4 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Name <strong class="text-danger">*</strong></label>
					<input type="text" class="form-control" name="employee_name"  id="employee_name" value="<?= ($user_option!="Add")?$data['employee_name']:'' ?>" required  style="text-transform:uppercase;" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-12 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Address(Present) </label>
					<textarea rows="3" class="form-control" name="present_address_line_1" id="present_address_line_1"  style="text-transform:uppercase;" <?= ($user_option=='View')?$redokadd:'' ?>><?= ($user_option!="Add")?$data['present_address_line_1']. ''. $data['present_address_line_2']. '' . $data['present_address_line_3']. ''.$data['present_address_line_4']:'' ?></textarea>
				</div>
                <div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">City</label>
					<input type="text" class="form-control"  name="present_address_line_4" id="present_address_line_4" value="<?= ($user_option!="Add")?$data['present_address_line_4']:'' ?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Pincode</label>
					<input type="text" class="form-control"  name="present_pincode_no" id="present_pincode_no"  value="<?= ($user_option!="Add")?$data['present_pincode_no']:'' ?>" onBlur="format_field(this.id,1,'');" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Phone No</label>
					<input type="text" class="form-control" name="present_phone_no" id="present_phone_no" value="<?= ($user_option!="Add")?$data['present_phone_no']:'' ?>" onBlur="format_field(this.id,1,'');" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Address(Permanent) &nbsp;&nbsp;&nbsp;&nbsp;Same As Above&nbsp;&nbsp;<input type="checkbox" name="copy" id="copy" onClick="copyData()"></label>
					<textarea rows="3" class="form-control" name="permanent_address_line_1" id="permanent_address_line_1" style="text-transform:uppercase;" <?= ($user_option=='View')?$redokadd:'' ?>><?= ($user_option!="Add")?$data['permanent_address_line_1']:'' ?></textarea>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Email</label>
					<input type="mail" class="form-control"  name="email_id"  id="email_id" value="<?= ($user_option!="Add")?$data['email_id']:'' ?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Pincode</label>
					<input type="text" class="form-control"  name="permanent_pincode_no" id="permanent_pincode_no" value="<?= ($user_option!="Add")?$data['permanent_pincode_no']:'' ?>" onBlur="format_field(this.id,1,'');" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Mobile No</label>
					<input type="text" class="form-control"  name="mobile_no" id="mobile_no" value="<?= ($user_option!="Add")?$data['mobile_phone_no']:'' ?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Attn Id</label>
					<input type="text" class="form-control"  name="attn_id"  id="attn_id" value="<?= ($user_option!="Add")?$data['attn_id']:'' ?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Gender</label>
					<select class="form-select"  name="gender" id="gender" <?= ($user_option=='View')?$disview:'' ?> onChange="other_box()">
                        <option value="">--Select--</option>
						<option value="M" <?= ($user_option!="Add")?($data['gender_code']=='M')?'selected':'':'' ?>>Male</option>
						<option value="F" <?= ($user_option!="Add")?($data['gender_code']=='F')?'selected':'':'' ?>>Female</option>
						<option value="O" <?= ($user_option!="Add")?($data['gender_code']=='O')?'selected':'':'' ?>>Other</option>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1 <?= ($user_option!="Add")?'d-block':'d-none' ?>" id="otherDiv">
					<label class="d-inline-block w-100 mb-1 lbl-mn">&nbsp;</label>
					<input type="text" class="form-control <?= ($user_option!="Add")?'d-block':'d-none' ?>"  name="others"  id="others" value="<?= ($user_option!="Add")?$data['others']:'' ?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Marital Status</label>
					<select class="form-select" name="martial_status" id="martial_status" <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="">--Select--</option>
                    <?php foreach ($marstat_sql as $key => $value) {?>
                        <option value="<?php echo $value['code_code'];?>" <?=($user_option!="Add")?($data['marital_status_code']== $value['code_code'])?'selected':'':'' ?>><?php echo $value['code_desc'];?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Blood Gr</label>
					<select class="form-select" name="blood_group" id="blood_group" <?= ($user_option=='View')?$disview:'' ?>>
                        <option value="">--Select--</option>
                        <?php foreach ($bloodgrp_sql as $key => $value) {?>
                            <option value="<?php echo $value['code_code'];?>" <?= ($user_option!="Add")?($data['blood_group_code'] == $value['code_code'])?'selected':'':'' ?>><?php echo $value['code_desc'];?></option>
                        <?php } ?>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Religion</label>
					<select class="form-select" name="religion" id="religion" <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="">--Select--</option>
                        <?php foreach ($religion_sql as $key => $value) {?>
                            <option value="<?php echo $value['code_code'];?>" <?= ($user_option!="Add")?($data['religion_code'] == $value['code_code'])?'selected':'':'' ?>><?php echo $value['code_desc'];?></option>
                        <?php } ?>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Nationality</label>
					<select class="form-select" name="nationality" id="nationality" <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="">--Select--</option>
					<?php foreach ($nationality_sql as $key => $value) {?>
                        <option value="<?php echo $value['code_code'];?>" <?= ($user_option!="Add")?($data['nationality_code'] == $value['code_code'])?'selected':'':'' ?>><?php echo $value['code_desc'];?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-6 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Father/Spouse</label>
					<select class="form-select w-33 float-start" name="relation" id="relation" <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="">--Select--</option>
					<?php foreach ($relation_sql as $key => $value) {?>
                        <option value="<?php echo $value['code_code'];?>" <?= ($user_option!="Add")?($data['relationship_code'] == $value['code_code'])?'selected':'':'' ?>><?php echo $value['code_desc'];?></option>
                    <?php } ?>
					</select>
					<input type="text" class="form-control w-65 ms-1 float-start"/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Date of Birth</label>
					<input type="text" class="form-control datepicker" name="dob" id="dob" value="<?= ($user_option!="Add")?date('d-m-Y',strtotime($data['birth_date'])):'' ?>" onBlur="make_date(this),calc_retirement(this);" <?= ($user_option=='View')?$redokadd:'' ?>/>
                    <input class="accept_date_item" type="hidden" size="10" maxlength="10" name="current_date"     value="<?php //echo $global_dmydate?>">
                      <input class="accept_date_item" type="hidden" size="10" maxlength="10" name="anniversary_date" value="<?php //echo $anniversary_date?>">
				</div>

				<div class="bdge d-block w-100 mt-2 mb-2">Emergency Contact Details</div>
					<div class="col-md-3 float-start px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Emergency Contact Person <strong class="text-danger">*</strong></label>
						<input class="form-control" type="text" name="em_name" name="em_name" required value="<?= ($user_option!="Add")?($data['em_name']!='')?$data['em_name']:'':''?>">  
					</div>
					<div class="col-md-3 float-start px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Emergency Contact Person Relation <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="em_relation" name="em_relation" required   value="<?= ($user_option!="Add")?($data['em_relation']!='')?$data['em_relation']:'':''?>">  
					</div>
					<div class="col-md-3 float-start px-2 mb-1">
						<label class="d-inline-block w-100 mb-1 lbl-mn">Emergency Contact Number <strong class="text-danger">*</strong></label>
						<input type="text" class="form-control" name="em_number" name="em_number" required value="<?= ($user_option!="Add")?($data['em_number']!='')?$data['em_number']:'':''?>">  
					</div>
				</div>
				<!-- Official -->
				
				<div class="bdge d-block w-100 mt-2 mb-2">Official</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Status</label>
					<span class="bdge mt-0 py-2 w-100 text-center" style="height:35px;"><?= ($user_option!="Add")?$sql_data['status_desc']:'' ?></span>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
					<select class="form-select" name="branch_code" id="branch_code" required <?= ($user_option=='View')?$disview:'' ?>>
                    <?php foreach ($branch_sql as $key => $value) {?>
                        <option value="<?php echo $value['branch_code'];?>" <?=  ($user_option!="Add")?($data['branch_code'] == $value['branch_code'])? 'selected':'' :'' ?>><?php echo $value['branch_name']; ?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Department <strong class="text-danger">*</strong></label>
					<select class="form-select" name="department_code" id="department_code" required <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="">--Select--</option>
					<?php foreach ($department_sql as $key => $value) {?>
                        <option value="<?php echo $value['department_code'];?>" <?=  ($user_option!="Add")?($data['department_code'] == $value['department_code'])?'selected':'':'' ?>><?php echo $value['department_name'];?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Designation <strong class="text-danger">*</strong></label>
					<select class="form-select" name="designation_code" id="designation_code" required <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="">--Select--</option>
					<?php foreach ($designation_sql as $key => $value) {?>
                        <option value="<?php echo $value['designation_code'];?>" <?=  ($user_option!="Add")?($data['designation_code'] == $value['designation_code'])?'selected':'':'' ?>><?php echo $value['designation_name'];?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Joining Date</label>
					<input type="text" class="form-control datepicker" name="join_date"  id="join_date" onBlur="make_date(this);" value="<?= ($user_option!="Add")?($data['join_date']!='0000-00-00 00:00:00')?date('d-m-Y',strtotime($data['join_date'])):'':''?>" <?= ($user_option=='View')?$disview:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Confirmation Date</label>
					<input type="text" class="form-control datepicker"  name="conf_date" id="conf_date" onBlur="make_date(this);" value="<?= ($user_option!="Add")?($data['conf_date']!='0000-00-00 00:00:00')?date('d-m-Y',strtotime($data['conf_date'])):'':''?>" <?= ($user_option=='View')?$disview:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Retirement Date</label>
					<input type="text" class="form-control datepicker" name="retirement_date" id="retirement_date" onBlur="make_date(this);" value="<?= ($user_option!="Add")?($data['retirement_date']!='0000-00-00 00:00:00')?date('d-m-Y',strtotime($data['retirement_date'])):'':''?>" <?= ($user_option=='View')?$disview:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Reason</label>
					<textarea class="form-control" name="reason" id="reason"<?= ($user_option=='View')?$disview:'' ?>><?= ($user_option!="Add")?$data['reason']:''?></textarea>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Last Incr Date</label>
					<input type="text" class="form-control datepicker" name="last_incr_date" id="last_incr_date" onBlur="make_date(this);"  value="<?= ($user_option!="Add")?($data['last_incr_date']!='0000-00-00 00:00:00')?date('d-m-Y',strtotime($data['last_incr_date'])):'':''?>"<?= ($user_option=='View')?$disview:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Enrollment Date</label>
					<input type="text" class="form-control datepicker" name="enroll_date" id="enroll_date" onBlur="make_date(this);" value="<?= ($user_option!="Add")?($data['enroll_date']!='0000-00-00 00:00:00')?date('d-m-Y',strtotime($data['enroll_date'])):'':''?>" <?= ($user_option=='View')?$disview:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Enrollment No</label>
					<input type="text" class="form-control" name="enrollment_no" id="enrollment_no" value="<?= ($user_option!="Add")?($data['enrollment_no']!='')?$data['enrollment_no']:'':''?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Employee Type </label>
					<select class="form-select" name="employee_type_code" id="employee_type_code"  <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="">--Select--</option>
					<?php foreach ($employee_type as $key => $value) {?>
                        <option value="<?php echo $value['code_code'];?>" <?= ($user_option!="Add")?($data['employee_type_code'] == $value['code_code'])? 'selected':'':'' ?>><?php echo $value['code_desc'];?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">HOD <strong class="text-danger">*</strong></label>
					<select class="form-select" name="hod_name_code" id="hod_name_code" required <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="">--Select--</option>
					<?php foreach ($hod_name as $key => $value) {?>
                        <option value="<?php echo $value['initial_code'];?>" <?= ($user_option!="Add")?($data['hod_name_code']==$value['initial_code'])?'selected': '': '' ?>><?php echo $value['initial_name'];?></option>
                    <?php } ?>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">PF A/c No</label>
					<input type="text" class="form-control" name="pf_acc_no" id="pf_acc_no" value="<?= ($user_option!="Add")?($data['pf_acc_no']!='')?$data['pf_acc_no']:'':''?>" <?= ($user_option=='View')?$redokadd:'' ?> />
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Insurance No </label>
					<input type="text" class="form-control" name="esi_acc_no" id="esi_acc_no" value="<?= ($user_option!="Add")?($data['esi_acc_no']!='')?$data['esi_acc_no']:'':''?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">PF Eligibility</label>
					<select class="form-select" name="pf_elibility" id="pf_elibility" <?= ($user_option=='View')?$disview:'' ?>>
						<option>--- Select ---</option>
                        <option value="Y"  <?= ($user_option!="Add")?($data['pf_elibility']=='Y')?'selected':'':'' ?>>Yes</option>
                        <option value="N"  <?= ($user_option!="Add")?($data['pf_elibility']=='N')?'selected':'':'' ?>>No</option>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Medical Eligibility </label>
					<select class="form-select" name="esi_elibility" id="esi_elibility"  <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="Y" <?= ($user_option!="Add")?($data['esi_elibility']=='Y')?'selected':'':'' ?>>Yes</option>
                    <option value="N" <?= ($user_option!="Add")?($data['esi_elibility']=='N')?'selected':'':'' ?>>No</option>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">PTAX Eligibility </label>
					<select class="form-select" name="ptax_elibility" id="ptax_elibility"  <?= ($user_option=='View')?$disview:'' ?>>
                    <option value="Y" <?= ($user_option!="Add")?($data['ptax_elibility']=='Y')?'selected':'':'' ?>>Yes</option>
                    <option value="N" <?= ($user_option!="Add")?($data['ptax_elibility']=='N')?'selected':'':'' ?>>No</option>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Basic Rate </label>
					<input type="text" class="form-control" name="basic_rate" id="basic_rate" value="<?= ($user_option!="Add")?($data['basic_rate']!='')?$data['basic_rate']:'':''?>"  onBlur="chkNumber(this),calc_hra()";  <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">HRA Rate</label>
					<input type="text" class="form-control"  name="hra_rate" id="hra_rate" value="<?= ($user_option!="Add")?($data['hra_rate']!='')?$data['hra_rate']:'':''?>"  onBlur="chkNumber(this)"; <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Conv Allowance</label>
					<input type="text" class="form-control" name="conveyance_allowance_rate" value="<?= ($user_option!="Add")?($data['conveyance_allowance_rate']!='')?$data['conveyance_allowance_rate']:'':''?>" id="conveyance_allowance_rate"  onBlur="chkNumber(this)"; <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Special Allowance</label>
					<input type="text" class="form-control" name="special_allowance_rate" id="special_allowance_rate" value="<?= ($user_option!="Add")?($data['special_allowance_rate']!='')?$data['special_allowance_rate']:'':''?>" onBlur="chkNumber(this)"; <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Other Allowance</label>
					<input type="text" class="form-control" name="other_amount" id="other_amount" onBlur="chkNumber(this),match_gross()" value="<?= ($user_option!="Add")?($data['other_amount']!='')?$data['other_amount']:'':''?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Salary Type <strong class="text-danger">*</strong></label>
					<select class="form-select" name="salary_type_indicator" id="salary_type_indicator"  required <?= ($user_option=='View')?$disview:'' ?>>
						<option>--- Select ---</option>
                        <option value="S" <?= ($user_option!="Add")?($data['salary_type_indicator']=='S')?'selected':'':'' ?>>Salary</option>
                        <option value="W" <?= ($user_option!="Add")?($data['salary_type_indicator']=='W')?'selected':'':'' ?>>Wages</option>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Salary Payment</label>
					<select class="form-select" name="salary_pay_indicator" id="salary_pay_indicator" <?= ($user_option=='View')?$disview:'' ?>>
						<option>--- Select ---</option>
                        <option value="C" <?= ($user_option!="Add")?($data['salary_pay_indicator']=='C')?'selected':'':'' ?>>Cash</option>
                        <option value="Q" <?= ($user_option!="Add")?($data['salary_pay_indicator']=='Q')?'selected':'':'' ?>>Cheque</option>
                        <option value="B" <?= ($user_option!="Add")?($data['salary_pay_indicator']=='B')?'selected':'':'' ?>>Bank Advise</option>
					</select>
				</div>
				<input type="hidden"  id="user_option"  name="user_option"  value="<?= ($user_option!='list')?'Add':'Edit'?>">
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Bank A/c No</label>
					<input type="text" class="form-control" name="bank_account_no" id="bank_account_no" value="<?= ($user_option!="Add")?($data['bank_account_no']!='')?$data['bank_account_no']:'':''?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Bank Code <strong class="text-danger">*</strong></label>
					<select class="form-select"  name="bank_code"  id="bank_code" required  <?= ($user_option=='View')?$disview:'' ?>>
						<option>--- Select ---</option>
                        <?php foreach ($bank_code_sql as $key => $value) {?>
                        <option value="<?php echo $value['bank_code'];?>"  <?= ($user_option!="Add")?($data['bank_code']==$value['bank_code'])?'selected': '': '' ?>><?php echo $value['bank_name'];?></option>
                        <?php } ?>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Status Code <strong class="text-danger">*</strong></label>
					<select class="form-select" name="status_code" id="status_code" required <?= ($user_option=='View')?$disview:'' ?>>
						<option>--- Select ---</option>
                        <option value="A" <?= ($user_option!="Add")?($data['status_code']=='A')?'selected': '': '' ?>>Active</option>
                        <option value="L" <?= ($user_option!="Add")?($data['status_code']=='L')?'selected': '': '' ?>>Left</option>
                        <option value="E" <?= ($user_option!="Add")?($data['status_code']=='E')?'selected': '': '' ?>>Expired</option>
					</select>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Pan No</label>
					<input type="text" class="form-control" name="pan_no" id="pan_no" value="<?= ($user_option!="Add")?($data['pan_no']!='')?$data['pan_no']:'':''?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Aadhaar No</label>
					<input type="text" class="form-control" name="adhar_no" id="adhar_no" value="<?= ($user_option!="Add")?($data['adhar_no']!='')?$data['adhar_no']:'':''?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="col-md-3 float-start px-2 mb-1">
					<label class="d-inline-block w-100 mb-1 lbl-mn">Gross Salary</label>
					<input type="text" class="form-control" name="gross_salary"  id="gross_salary"onBlur="match_gross()" value="<?= ($user_option!="Add")?($data['gross_salary']!='')?$data['gross_salary']:'':''?>" <?= ($user_option=='View')?$redokadd:'' ?>/>
				</div>
				<div class="bdge d-block w-100 mt-2 mb-2">Leave Balences as on Date </div>
                <input type="hidden" name="count" id="count" value="<?php echo count($leave_sql); ?>"/>
				<table class="table table-bordered tblePdngsml">
					<tbody>
						<tr class="fs-14">
							<th>Leave Type</th>
							<th>Opening</th>
							<th>Earned</th>
							<th>Availed</th>
							<th>Closing</th>
						</tr>
                        <?php foreach ($leave_sql as $i => $value) { $i++;?>
						<tr>							
							<td class="text-uppercase w-350">
								<span><input type="text" class="btnnobackground2" name="leave_type_name<?php echo $i?>" id="leave_type_name<?php echo $i?>" value="<?php echo $value['leave_type_name']?>" <?= ($user_option=='View')?$redokadd:'' ?>/> </span>
                                <input type="hidden" class="form-control" name="leave_type_code<?php echo $i?>" id="leave_type_code<?php echo $i?>"  value="<?php echo $value['leave_type_code']?>"<?= ($user_option=='View')?$redokadd:'' ?> />
							</td>
							<td class="">
								<input type="text" class="form-control" name="leave_opening_days_no<?php echo $i?>" id="leave_opening_days_no<?php echo $i?>" onBlur="calc_leave_balance(<?php echo $i?>)" value="<?php echo $value['leave_opening_days_no']?>" size="3"  maxlength="4" <?= ($user_option=='View')?$redokadd:'' ?> />
							</td>
							<td class="">
								<input type="text" class="form-control" name="leave_earned_days_no<?php echo $i?>" id="leave_earned_days_no<?php echo $i?>" onBlur="calc_leave_balance(<?php echo $i?>)"  value="<?php echo $value['leave_earned_days_no']?>"  size="3"  maxlength="3" <?= ($user_option=='View')?$redokadd:'' ?>/>
							</td>
							<td class="">
								<input type="text" class="form-control" name="leave_availed_days_no<?php echo $i?>"  id="leave_availed_days_no<?php echo $i?>"  onBlur="calc_leave_balance(<?php echo $i?>)" value="<?php echo $value['leave_availed_days_no']?>"  size="3"  maxlength="3" <?= ($user_option=='View')?$redokadd:'' ?>/>
							</td>
                            
							<td class="">
                            <input type="text" class="form-control" name="leave_closing_days_no<?php echo $i?>"  id="leave_closing_days_no<?php echo $i?>"  value="<?php echo $value['leave_closing_days_no']?>"  size="3"  maxlength="3" <?= ($user_option=='View')?$redokadd:'' ?>/>
							</td>
						</tr>
                        <?php } ?>
					</tbody>
				</table>
				<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
                <input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/>
				<input type="hidden"  id="total_amount" name="total_amount" value="<?php// echo $total_amount;?>"> 
                <input type="hidden"  id="confirm" name="confirm" value="Submit"> 
                <input type="button" name="button" id="button" value="Confirm" class="btn btn-primary cstmBtn btncls mt-2"  onClick="data_check('empDetailsGen');return false;" <?= ($user_option=='View')?$disview:'' ?>>
				<a href="<?= $url ?>" class="btn btn-primary cstmBtn btn-cncl ms-2 mt-2">Close</a>
			</div>
			
		</div>
      </form>    
      </div>
    </section>

  </main><!-- End #main -->

<?= $this->endSection() ?>