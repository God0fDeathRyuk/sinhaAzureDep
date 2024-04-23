<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?= view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">

<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
			<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	</div>
<?php endif; ?>

<form name="f1" method="post" action="">

<div class="pagetitle d-block float-start col-md-9">
    <h1>Counsel Memo Entry <span class="badge rounded-pill bg-dark"><?= (isset($user_option)) ? ucfirst($user_option) : '' ?></span></h1>      
</div><!-- End Page Title -->
<div class="col-md-3 float-start text-end">
    <a href="javascript:void(0);" class="btn btn-primary cstmBtn mb-3"> <?= strtoupper($status_desc) ?>
	<input class="" type="hidden" name="status_code" value="<?= strtoupper($status_desc) ?>" readonly>
	</a>      
</div>
<section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-3">
<div class="frms-sec d-inline-block w-100 bg-white p-3">
    <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
        <label class="d-inline-block w-100 mb-2 lbl-mn">Serial No</label>
        <input type="text" class="form-control" id="srlNO" name="serial_no" value="<?= isset($hdr_row['serial_no']) ? $hdr_row['serial_no'] : '' ?>" readonly/>
    </div>
    
    <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
        <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
        <select class="form-select" name="branch_code">
        <?php foreach($data['branches'] as $branch) { ?>
            <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
        <?php } ?>
        </select>
    </div>
    <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
        <label class="d-inline-block w-100 mb-2 lbl-mn">Date</label>
        <input type="text" class="form-control set-date datepicker readonly" id="dte" name="entry_date" value="<?= ($user_option == 'Add') ? date('d-m-Y') : date_conv($hdr_row['entry_date']) ?>" required/>
    </div>
    <hr/>
    
    <div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-4">
        <label class="d-inline-block w-100 mb-2 lbl-mn">Councel <strong class="text-danger">*</strong></label>
        <div class="position-relative d-inline-block smlBxLft float-start Inpt-Vw-icn">
            <input type="text" class="form-control smlBxLft float-start w-100" name="associate_code" oninput="this.value = this.value.toUpperCase()" id="counselResult" onchange="fetchData(this, 'associate_code', ['counselResultName'], ['associate_name'], 'get_counsel_result')" value="<?= ($user_option == 'Add') ? '' : $hdr_row['counsel_code'] ?>" required/>
			<?php if ($user_option == 'Add' || $user_option == 'Edit') { ?>
				<i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselResult', ['counselResultName'], ['associate_name'], 'get_counsel_result')" title="View" data-toggle="modal" data-target="#lookup"></i>
			<?php } ?>
        </div>					
        <input type="text" class="form-control w-50 float-start" id="counselResultName" name="associate_name" value="<?= ($user_option == 'Add') ? '' : $hdr_row['associate_name'] ?>" readonly/>
        <input type="text" class="form-control w-20 float-start ms-2" id="counselPan" name="counsel_pan" value="<?= ($user_option == 'Add') ? '' : $hdr_row['counsel_pan'] ?>" readonly/>
    </div>
    <div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-4">
        <label class="d-inline-block w-100 mb-2 lbl-mn">Clerk</label>
        <div class="position-relative d-inline-block smlBxLft float-start Inpt-Vw-icn">
            <input type="text" class="form-control smlBxLft float-start w-100" name="clerk_code" id="clerkCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'associate_code', ['clerkName'], ['associate_name'], 'get_clerk_result')" value="<?= ($user_option == 'Add') ? '' : $hdr_row['clerk_code'] ?>" />
			<?php if ($user_option == 'Add' || $user_option == 'Edit') { ?>
				<i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['clerk_help_id'] ?>', 'clerkCode', ['clerkName'], ['associate_name'], 'get_clerk_result')" title="View" data-toggle="modal" data-target="#lookup"></i>
			<?php } ?>
        </div>			
        <input type="text" class="form-control w-50 float-start" name="clerk_name" id="clerkName" value="<?= ($user_option == 'Add') ? '' : $hdr_row['clerk_name'] ?>" readonly/>
        <input type="text" class="form-control w-20 float-start ms-2" name="clerk_pan" id="clerkPan" value="<?= ($user_option == 'Add') ? '' : $hdr_row['clerk_pan'] ?>" readonly/>
    </div>
    <div class="frms-sec-insde d-block float-start col-md-12 px-2 mb-4">
        <label class="d-inline-block w-100 mb-2 lbl-mn">Peon</label>
        <div class="position-relative d-inline-block smlBxLft float-start Inpt-Vw-icn">
            <input type="text" class="form-control smlBxLft float-start w-100" name="peon_code" id="peonCode" value="<?= ($user_option == 'Add') ? '' : $hdr_row['peon_code'] ?>" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'associate_code', ['peonName'], ['associate_name'], 'get_peon_result')" />
			<?php if ($user_option == 'Add' || $user_option == 'Edit') { ?>
				<i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['peon_help_id'] ?>', 'peonCode', ['peonName'], ['associate_name'], 'get_peon_result')" title="View" data-toggle="modal" data-target="#lookup"></i>
			<?php } ?>
        </div>					
        <input type="text" class="form-control w-50 float-start" name="peon_name" id="peonName" value="<?= ($user_option == 'Add') ? '' : $hdr_row['peon_name'] ?>" readonly/>
        <input type="text" class="form-control w-20 float-start ms-2" name="peon_pan" id="peonP readonlyan" value="<?= ($user_option == 'Add') ? '' : $hdr_row['peon_pan'] ?>" readonly/>
    </div>
    <div class="frms-sec-insde d-block float-start col-md-8 px-2 mb-4">
        <label class="d-inline-block w-100 mb-2 lbl-mn">Memo No / Dt <strong class="text-danger">*</strong></label>
        <input type="text" name="memo_no" class="form-control w-75 float-start" value="<?= ($user_option == 'Add') ? '' : $hdr_row['memo_no'] ?>" <?= $redv ?> required/>
        <input type="text" name="memo_date" placeholder="dd-mm-yyyy" class="form-control float-start w-24 ms-2 set-date datepicker" value="<?= ($user_option == 'Add') ? '' : date_conv($hdr_row['memo_date']) ?>" <?= $redv ?>  onBlur="make_date(this)" required/>
    </div>
</div>		
</div>
<div class="col-md-12 mt-3">
    <div class="frms-sec d-inline-block w-100 bg-white p-3 mt-2 actvFrmsec">
        <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
            <label class="d-inline-block w-100 mb-2 lbl-mn">Counsel Fee</label>
            <input type="text" class="form-control" id="ltrNO" name="counsel_fee" value="<?= ($user_option == 'Add') ? '' : $hdr_row['counsel_fee'] ?>" readonly/>
        </div>
        <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
            <label class="d-inline-block w-100 mb-2 lbl-mn">Clerk Fee</label>
            <input type="text" class="form-control" id="ltrDte" name="clerk_fee" value="<?= ($user_option == 'Add') ? '' : $hdr_row['clerk_fee'] ?>" readonly/>
        </div>
        <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
            <label class="d-inline-block w-100 mb-2 lbl-mn">Peon Fee</label>
            <input type="text" class="form-control" id="mdeSend" name="peon_fee" value="<?= ($user_option == 'Add') ? '' : $hdr_row['peon_fee'] ?>" readonly/>
        </div>
        
        <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
            <label class="d-inline-block w-100 mb-2 lbl-mn">Service Tax</label>
            <input type="text" class="form-control" id="ltrNO" name="service_tax_fee" value="<?= ($user_option == 'Add') ? '' : $hdr_row['service_tax_fee'] ?>" readonly/>
        </div>
    </div>		
</div>
<div class="col-md-12 mt-3">
<span id="actionBtn1">
	<?php if (ucfirst($user_option) == 'Edit' || ucfirst($user_option) == 'Add') { 
		if(count($response) || ucfirst($user_option) == 'Add' || ucfirst($user_option) == 'Edit') { ?>
		<button type="button" onclick="deleteRow('tbody', 'row_counter', 'actionBtn1', 'addNewRow')" class="btn btn-primary cstmBtn border border-white float-end mb-2">Delete Row</button> 
	<?php } else { ?>
		<button type="button" onclick="addNewRow(this, null, 21)" class="btn btn-primary cstmBtn border border-white float-end">Add Row</button> 
	<?php } } ?>
</span>   
	<div class="scrlTbl mb-2 tblscrlvtrclExtlrg">
        <table class="table borderTd bg-white" id="memoEntryTable">
            <thead>
				<tr class="fs-14">
					<th class="border">&nbsp;</th>
					<th class="border">Brief Date</th>
					<th class="border">Matter</th>
					<th class="border">Client</th>
					<th class="border">Initial</th>
					<th class="border">Narration</th>
					<th class="border">Counsel</th>
					<th class="border">Clerk</th>
					<th class="border">Peon</th>
					<th class="border">Tax%</th>
					<th class="border">Cess%</th>
					<th class="border">HeCess%</th>
					<th class="border">Total Tax</th>
					<th class="border">Gross</th>
					<th class="border text-center align-middle">Action</th>
				</tr>
            </thead>
            <tbody id="tbody">
                <?php
                $i = 0; $y = 10; $j = 0; 
                if($user_option == 'Add' || $user_option == 'Edit') { if($count == 0) { $j = 1; } else { $j = $count + 1; } } else { $j = $count; }
                for($k = 1; $k <= $j; $k++) {
                    $row_1 = ($k <= $count) ? $response[$k-1] : 0;
                    $i++;
                    $gross_amount = number_format((($row_1) ? $row_1['counsel_fee'] : 0) + (($row_1) ? $row_1['clerk_fee'] : 0)  + (($row_1) ? $row_1['peon_fee'] : 0)  + (($row_1) ? $row_1['new_tax_total_amount'] : 0) , 2, '.', '') ;
					?>
					<tr>
						<td id="Ctd2<?= $i?>" class="border fw-normal" onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $i ?>)<?php }?>">
							<input type="hidden" name="voucher_ok_ind<?= $i ?>" value="Y" onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $i ?>)<?php }?>">
                            <img src="<?= base_url('public/assets/img/SelectRow.png') ?>" class="slctRow" alt="Select"/>
						</td>
						<td class="w250">
							<input type="text" class="form-control datepicker" name="brief_date<?= $i ?>" value="<?= date_conv(($row_1) ? $row_1['brief_date'] : '' );?>" maxlength="10" tabindex="<?= ($y+1); ?>" onBlur="chkBriefDate(this,'<?= $i ?>')" <?= $redv; ?>>
						</td>
						<td class="w550">
							<div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
								<span class="d-block w-100"> Code</span>
								<input type="text" class="form-control" name="matter_code<?= $i ?>" value="<?= ($row_1) ? $row_1['matter_code'] : '' ;?>" maxlength="06" tabindex="<?= ($y+2); ?>" id="matterCode<?= $i ?>" onchange="fetchData(this, 'matter_code', ['clientCode<?= $i ?>', 'initialCode<?= $i ?>', 'matterDesc<?= $i ?>'], ['client_code', 'initial_code', 'matter_desc'], 'matter_code')"  <?= $redv; ?>>
								<?php if(($user_option == 'Add' || $user_option == 'Edit')) { ?>
									<i class="fa-solid fa-binoculars icn-vw" style="top:35px;" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode<?= $i ?>', ['clientCode<?= $i ?>', 'initialCode<?= $i ?>', 'matterDesc<?= $i ?>'], ['client_code', 'initial_code', 'mat_description'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
								<?php } ?>
							</div>
							<div class="d-inline-block w-100 mt-2">
								<span class="d-block w-100">Matter Desc</span>
								<textarea rows="2" cols="10" class="form-control" name="mat_description<?= $i ?>" id="matterDesc<?= $i ?>" readonly><?= stripslashes(($row_1) ? $row_1['matter_desc'] : '' );?></textarea>
							</div>
						</td>
						<td class="w250">
								<input type="text" class="form-control" name="client_code<?= $i ?>" value="<?= ($row_1) ? $row_1['client_code'] : '' ?>" id="clientCode<?= $i ?>"  maxlength="06" readonly>
						</td>
						<td class="w250">
							<input type="text" class="form-control" name="initial_code<?= $i ?>" value="<?= ($row_1) ? $row_1['initial_code'] : '' ?>" id="initialCode<?= $i ?>" maxlength="06" readonly/>
						</td>
						<td class="w550">
							<textarea rows="2" class="form-control" name="narration<?= $i ?>" tabindex="<?= ($y+3)  ?>" <?= $redv; ?>><?= ($row_1) ? $row_1['narration'] : '' ?></textarea>
						</td>
						<td class="w250">
							<input type="text" class="form-control" name="counsel_fee<?= $i ?>" value="<?= ($row_1) ? $row_1['counsel_fee'] : '' ?>" maxlength="12" tabindex="<?= ($y+4); ?>" onBlur="chkCounselFee(this,'<?= $i ?>')" <?= $redv; ?>/>
						</td>
						<td class="w250">
							<input type="text" class="form-control" name="clerk_fee<?= $i ?>" value="<?= ($row_1) ? $row_1['clerk_fee'] : '' ?>"  maxlength="12" tabindex="<?= ($y+5); ?>" onBlur="chkClerkFee(this,'<?= $i ?>')" <?= $redv; ?>/>
						</td>
						<td class="w250">
							<input class="form-control" type="text" name="peon_fee<?= $i ?>" value="<?= ($row_1) ? $row_1['peon_fee'] : '' ?>" tabindex="<?= ($y+6) ?>" onBlur="chkPeonFee(this,'<?= $i ?>')" <?= $redv ?>>
						</td>
						<td class="w250">
							<div class="d-inline-block w-100">
								<label class="d-inline-block w-100 mb-2 lbl-mn">Tax %</label>
								<input type="text" class="form-control" name="new_tax_percent<?= $i ?>"  value="<?= ($row_1) ? $row_1['new_tax_percent'] : '' ?>"   maxlength="12" tabindex="<?= ($y+7); ?>" onBlur="calc_newtax_percent(<?= $i ?>);" <?= $redv; ?>/>
							</div>
							<div class="d-inline-block w-100 mt-2">
								<label class="d-inline-block w-100 mb-2 lbl-mn">Tax Amount</label>
								<input type="text" class="form-control"name="new_tax_amount<?= $i ?>" value="<?= ($row_1) ? $row_1['new_tax_amount'] : '' ?>" onBlur="calc_newtax_amount(<?= $i ?>)"/>
							</div>
						</td>
						<td class="w250">
							<div class="d-inline-block w-100">
								<label class="d-inline-block w-100 mb-2 lbl-mn">Cess %</label>
								<input type="text" class="form-control" name="new_tax_cess_percent<?= $i ?>" value="<?= ($row_1) ? $row_1['new_tax_cess_percent'] : '' ?>" maxlength="12" tabindex="<?= ($y+8); ?>" onBlur="calc_newcess_percent(<?= $i ?>);"   <?= $redv; ?>/>
							</div>
							<div class="d-inline-block w-100 mt-2">
								<label class="d-inline-block w-100 mb-2 lbl-mn">Cess Amount</label>
								<input type="text" class="form-control" name="new_tax_cess_amount<?= $i ?>" value="<?= ($row_1) ? $row_1['new_tax_cess_amount'] : '' ?>" onBlur="calc_newcess_amount(<?= $i ?>);"/>
							</div>
						</td>
						<td class="w250">
							<div class="d-inline-block w-100">
								<label class="d-inline-block w-100 mb-2 lbl-mn">HECess %</label>
								<input type="text" class="form-control" name="new_tax_hecess_percent<?= $i ?>"  value="<?= ($row_1) ? $row_1['new_tax_hecess_percent'] : '' ?>"  maxlength="12" tabindex="<?= ($y+9);?>" onBlur="calc_newhecess_percent(<?= $i ?>);" <?= $redv; ?>/>
							</div>
							<div class="d-inline-block w-100 mt-2">
								<label class="d-inline-block w-100 mb-2 lbl-mn">HECess Amount</label>
								<input type="text" class="form-control" name="new_tax_hecess_amount<?= $i ?>" value="<?= ($row_1) ? $row_1['new_tax_hecess_amount'] : '' ?>" onBlur="calc_newhecess_amount(<?= $i ?>);" />
							</div>
						</td>
						<td class="w250">
							<div class="d-inline-block w-100">
								<input type="text" class="form-control" name="new_tax_total_amount<?= $i ?>" value="<?= ($row_1) ? $row_1['new_tax_total_amount'] : '' ?>" onBlur="calc_row_total(<?= $i ?>);" readonly/>
								<input type="hidden" class="form-control" name="new_tax_total_percent<?= $i ?>" value="<?= ($row_1) ? $row_1['new_tax_total_percent'] : '' ?>" readonly/>
							</div>
						</td>
						<td class="w250">
							<input type="text" class="form-control" name="gross_amount<?= $i ?>" value="<?= $gross_amount ?>" maxlength="12" readonly/>
						</td>
						<td class="border text-center TbladdBtn">
							<?php if(($user_option == 'Add' || $user_option == 'Edit') && ($i == $j)) { ?>    
								<input type="button" name="Add_row<?= $i ?>" value="+" title="Add Row" onClick="addNewRow(this,<?= $i ?>,<?= ($y+11) ?>)" tabindex="<?= ($y+11); ?>">
							<?php } ?>
						</td>
					</tr>
                <?php $y += 13; } 
				if($k == 1) { ?>
					<td class="w250" colspan="15"> No Records Added Yet !! </td>
				<?php } ?>
            <div id="tbl-container-bothScrollBar5"> </div>
            </tbody>
        </table>
    </div>
	<input type="hidden" class="form-control" name="display_id" id="display_id" value="<?php echo  $_REQUEST['display_id']; ?>"/>
	<input type="hidden" class="form-control" name="menu_id" id="menu_id" value="<?php echo  $_REQUEST['menu_id']; ?>"/> 
	<input type="hidden" name="finsub" id="finsub" value="fsub">
    <div class="col-md-12 d-inline-block">
		<?php if($user_option == 'Add' || $user_option == 'Edit') { ?>	
			<button type="submit" class="btn btn-primary cstmBtn mt-3">Save</button>
		<?php } else if($user_option == 'Delete') { ?>
			<button type="submit" onclick="return confirm('Are you sure you want to delete.')" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Delete</button>
		<?php } ?>
			<a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn-bck btn btn-dark ms-2 mt-3">Back</a>
    </div>	
</div>
</div>
</div>
</div>
<input type="hidden" name="display_code" value="<?= $displayId['matter_help_id'] ?>">
<input type="hidden" name="row_counter" id="row_counter"  value="<?= $i ?>">
<input type="hidden" name="user_option"  value="<?= $user_option?>">
</form>
</main>
<!-- End #main -->

<script>
    function addNewRow(fld, n = null, x) { 
		let total_row   = parseInt(document.f1.row_counter.value)*1;
		let user_option = document.f1.user_option.value;
		n = total_row;
		let m = n, flag = 1, conditionFlag = 0;
		let x1 = (x*1)+1, x2 = (x*1)+2, x3 = (x*1)+3, x4 = (x*1)+4, x5 = (x*1)+5, x6 = (x*1)+6, x7 = (x*1)+7, x8 = (x*1)+8, x9 = (x*1)+9, x10 = (x*1)+1, x11 = (x*1)+1, x12 = (x*1)+1, x13 = (x*1)+1, x14 = (x*1)+14 ; 

		if(n != 0) {
			if(eval("document.f1.brief_date"+n+".value") == "" ) {
				Swal.fire({ text: 'Please Enter Brief Date!!' }).then((result) => { setTimeout(() => {eval("document.f1.brief_date"+n+".focus()")}, 500) });
				flag = 0; return false;
			} else if(eval("document.f1.matter_code"+n+".value") == "" ) {
				Swal.fire({ text: 'Please Enter Matter!!' }).then((result) => { setTimeout(() => {eval("document.f1.matter_code"+n+".focus()")}, 500) });
				flag = 0; return false;
			} else if(eval("document.f1.narration"+n+".value") == "" ) {
				Swal.fire({ text: 'Please Enter Narration!!' }).then((result) => { setTimeout(() => {eval("document.f1.narration"+n+".focus()")}, 500) });
				flag = 0; return false;
			} else if(eval("document.f1.counsel_fee"+n+".value") == "" ) {
				Swal.fire({ text: 'Please Enter Counsel Fees!!' }).then((result) => { setTimeout(() => {eval("document.f1.counsel_fee"+n+".focus()")}, 500) });
				flag = 0; return false;
			}
			conditionFlag = (eval("document.f1.brief_date"+total_row+".value") != "" && eval("document.f1.matter_code"+total_row+".value") != "" && eval("document.f1.narration"+total_row+".value") != "" && eval("document.f1.counsel_fee"+total_row+".value") != "");
		}

		if(document.f1.user_option.value == 'Add' || document.f1.user_option.value == 'Edit') { 
			if(flag == 1) {
				if(conditionFlag || total_row == 0) {
					n++; x++;
					if (total_row != 0) {
						fld.disabled = true; fld.style.visibility = 'hidden'; 
					} else {
						fld.setAttribute('onClick', `deleteRow('tbody', 'row_counter', 'actionBtn1', 'addNewRow')`);
						fld.innerText = "Delete Row";
						let table = document.getElementById('tbody').innerHTML = '';
					}
					
					document.f1.row_counter.value = n;
					var text   = "<tr>"; 
						
					if(user_option == 'Add' || user_option == 'Edit') 
							text += `<td id="Ctd2${n}" onClick="voucher_delRow(this, ${n})"><input type="hidden" name="voucher_ok_ind${n}" value="Y" readonly="true"><img src="<?= base_url('public/assets/img/SelectRow.png') ?>" class="slctRow" alt="Select"/></td>`;
					else text += `<td><input type="hidden" name="voucher_ok_ind${n}" readonly="true"><img src="<?= base_url('public/assets/img/SelectRow.png') ?>" class="slctRow" alt="Select"/></td>`;
					
					text +=` 
							<td class="w250"><input class="form-control" type="text"  name="brief_date${n}" value="${(total_row) ? document.f1['brief_date'+m].value : ''}" maxlength="10"  tabindex="${x1}" onBlur="chkBriefDate(this, ${n})"></td>

							<td class="w550">
								<div class="d-inline-block w-100 position-relative Inpt-Vw-icn">
									<span class="d-block w-100"> Code</span>
									<input class="form-control" type="text" name="matter_code${n}" id="matterCode${n}" tabindex="${x2}" onBlur="chkMatterCode(this, ${n})" onKeyUp="getMatterCode(this, ${n})"  onchange="fetchData(this, 'matter_code', ['clientCode${n}', 'initialCode${n}', 'matterDesc${n}'], ['client_code', 'initial_code', 'matter_desc'], 'matter_code')" >
									
									<i class="fa-solid fa-binoculars icn-vw" style="top:35px;" onclick="showData('matter_code', '${(total_row) ? document.f1.display_code.value : ''}', 'matterCode${n}', ['clientCode${n}', 'initialCode${n}', 'matterDesc${n}'], ['client_code', 'initial_code', 'mat_description'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
									</div>
								<div class="d-inline-block w-100 mt-2">
									<span class="d-block w-100">Matter Desc</span>
									<textarea rows="2" cols="10" class="form-control" name="mat_description${n}" id="matterDesc${n}" readonly></textarea>
								</div>
							</td>

							<td class="w250" ><input class="form-control" type="text"  name="client_code${n}"  id="clientCode${n}"  readonly></td>
							
							<td class="w250"><input class="form-control" type="text" name="initial_code${n}"  id="initialCode${n}"  readonly></td>
							
							<td class="w550"> <textarea class="form-control" type="text"  name="narration${n}" tabindex="${x3}" ></textarea> </td>
							
							<td class="w250"><input class="form-control" type="text" name="counsel_fee${n}" tabindex="${x4}" onBlur="chkCounselFee(this, ${n})" ></td>
							
							<td class="w250"><input class="form-control" type="text" name="clerk_fee${n}" tabindex="${x5}" onBlur="chkClerkFee(this, ${n})" ></td>

							<td class="w250"><input class="form-control" type="text" name="peon_fee${n}" tabindex="${x6}" onBlur="chkPeonFee(this, ${n})" ></td>
							
							<td class="w250">
								<div class="d-inline-block w-100">
									<label class="d-inline-block w-100 mb-2 lbl-mn">Tax %</label>
									<input class="form-control" type="text"  name="new_tax_percent${n}"   tabindex="${x7}" onBlur="calc_newtax_percent(${n});">
								</div>
								<div class="d-inline-block w-100 mt-2">
									<label class="d-inline-block w-100 mb-2 lbl-mn">Tax Amount</label>
									<input class="form-control" type="text" name="new_tax_amount${n}" tabindex="${x8}" onBlur="calc_newtax_amount(${n});">
								</div>
							</td>
							<td class="w250" >
							<div class="d-inline-block w-100">
								<label class="d-inline-block w-100 mb-2 lbl-mn">Cess %</label>
								<input type="text" class="form-control" name="new_tax_cess_percent${n}" maxlength="12" tabindex="${x9}" onBlur="calc_newcess_percent(${n});" <?= $redv; ?>/>
							</div>
							<div class="d-inline-block w-100 mt-2">
								<label class="d-inline-block w-100 mb-2 lbl-mn">Cess Amount</label>
								<input type="text" class="form-control" name="new_tax_cess_amount${n}" onBlur="calc_newcess_amount(${n});"/>
							</div>
							</td> 
							
							<td class="w250">
							<div class="d-inline-block w-100">
								<label class="d-inline-block w-100 mb-2 lbl-mn">HECess %</label>
								<input type="text" class="form-control" name="new_tax_hecess_percent${n}" maxlength="12" tabindex="${x10}" onBlur="calc_newhecess_percent(${n});" <?= $redv; ?>/>
							</div>
							<div class="d-inline-block w-100 mt-2">
								<label class="d-inline-block w-100 mb-2 lbl-mn">HECess Amount</label>
								<input type="text" class="form-control" name="new_tax_hecess_amount${n}" onBlur="calc_newhecess_amount(${n});" />
							</div>
							</td>
							<td class="w250">
							<div class="d-inline-block w-100">
								<input type="text" class="form-control" name="new_tax_total_amount${n}" readonly/>
								<input type="hidden" class="form-control" name="new_tax_total_percent${n}" readonly/>
							</div>
							</td>
							<td class="w250" ><input class="form-control" type="text"  name="gross_amount${n}" readonly ></td>
							<td class="border text-center TbladdBtn"><input type="button" name="Add_row${n}" value="+" title="Add Row" onClick="addNewRow(this, ${n}, ${x13})" tabindex="${x13}"></td> 
						</tr>`;
					
					let tbody = document.getElementById("tbody");
					let tr = tbody.insertRow(tbody.rows.length);
					tr.classList.add('fs-14'); tr.innerHTML = text;

					eval(`document.f1.brief_date${n}.focus()`);
					eval(`document.f1.brief_date${n}.select()`);
				}
			}
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
						btnSpan.firstElementChild.setAttribute('onClick', callFunction + `(this, null, 21)`);
						btnSpan.firstElementChild.innerText = "Add Row";
						table.innerHTML = '<td class="w250" colspan="15"> No Records Added Yet !! </td>';
					}
					let row_no = document.getElementById(rowCountId); row_no.value = parseInt(row_no.value) - rows.length;
				}
			})
		} else {
			Swal.fire('Select Atleast <b> One Row </b> to Perform Delete Operation !!')
		}
	}

	function chkCounselFee(fld,n) {
		if(fld.value != ""){ validateNumber(fld, "Counsel Fee : ",2); }
		calc_newtax_percent(n) ; 
		calc_total_counsel();
	}

	//=========================================================================================================================   
	function chkClerkFee(fld,n) {
		if(fld.value != ""){ validateNumber(fld, "Clerk Fee : ",2); }
		calc_newtax_percent(n) ; 
		calc_total_clerk();
	}

	//=================================================================================================================
	//=========================================================================================================================   
	function chkPeonFee(fld,n) {
		if(fld.value != ""){ validateNumber(fld, "Peon Fee : ",2); }
		calc_newtax_percent(n) ; 
		calc_total_peon();
	}

	function voucher_delRow(e, n) {
		var row = document.getElementById("Ctd2"+n);
		if(eval("document.f1.voucher_ok_ind"+n+".value=='Y'"))
		{
			$(e).parent('tr').addClass('rowSlcted');
			eval("document.f1.voucher_ok_ind"+n+".value='N'");
			eval("document.f1.voucher_ok_ind"+n+".style.background='#ff0000'");
			eval("document.f1.voucher_ok_ind"+n+".style.color='#ff0000'");
			row.style.background='rgb(163 200 213)';
		}
		else
		{
			$(e).parent('tr').removeClass('rowSlcted');
			eval("document.f1.voucher_ok_ind"+n+".value='Y'");
			eval("document.f1.voucher_ok_ind"+n+".style.background='#ECE8D7'");
			eval("document.f1.voucher_ok_ind"+n+".style.color='#ECE8D7'");
			row.style.background='#fff';

		}
		calc_total_counsel();
		calc_total_clerk();
		calc_total_peon();

	}

	function calc_newtax_percent(rno) {
		var basamt  = eval("document.f1.counsel_fee"+rno+".value") * 1 ;
		var ntaxper = eval("document.f1.new_tax_percent"+rno+".value") * 1 ;
		
		if (ntaxper > 0) 
		{
			var ntaxamt = (basamt*ntaxper/100) ; eval("document.f1.new_tax_amount"+rno+".value = '"+ntaxamt+"'");  format_number(eval("document.f1.new_tax_amount"+rno),2); 
		}
		else 
		{
			var ntaxamt = '' ; eval("document.f1.new_tax_amount"+rno+".value = '"+ntaxamt+"'"); 
		}

		calc_newcess_percent(rno) ;
	}
</script>

<?= $this->endSection() ?>