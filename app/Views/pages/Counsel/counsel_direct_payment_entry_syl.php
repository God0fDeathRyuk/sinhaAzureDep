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

<div class="pagetitle">
      <h1>Counsel Direct Payment Entry [<?= $user_option ?>]</h1>
    </div><!-- End Page Title -->
<form action="" method="post" name="f1">
    <section class="section dashboard">
      <div class="row">
		  <div class="col-md-12 mt-3">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Serial No</label>
					<input type="text" class="form-control" id="serialNo" name="serial_no" value="<?= ($user_option != 'Add') ? $hdr_row['serial_no'] : '' ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
					<select class="form-select cstm-inpt" name="branch_code" <?php echo $disv;?> >
                    <?php foreach($data['branches'] as $branch) { ?>
                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
                	</select>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Date</label>
					<input type="text" class="form-control" id="entryDate" name="entry_date" value="<?= ($user_option != 'Add') ? date_conv($hdr_row['entry_date']) : date('d-m-Y') ?>"  onBlur="chkEntryDate(this)" readonly/>
				</div>
				<hr/>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Counsel</label>
                    <div class="position-relative Inpt-Vw-icn">
						<?php if ($user_option == 'Add' || $user_option == 'Select') { ?>
							<input type="text" class="form-control smlBxLft float-start w-100" name="associate_code" oninput="this.value = this.value.toUpperCase()" id="counselResult" onchange="fetchData(this, 'associate_code', ['counselResultName'], ['associate_name'], 'get_counsel_result')" value="<?= ($user_option == 'Add') ? '' : $hdr_row['counsel_code'] ?>"/>
							<i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselResult', ['counselResultName'], ['associate_name'], 'get_counsel_result')" title="View" data-toggle="modal" data-target="#lookup"></i>
						<?php } else { ?>
							<input type="text" class="form-control smlBxLft float-start w-100" name="associate_code" value="<?= $hdr_row['counsel_code'] ?>" <?= ($user_option == 'View' || $user_option == 'Delete') ? 'readonly' : '' ?>/>
						<?php } ?>
					</div>
					
					<input type="text" class="form-control w-70 float-start" id="counselResultName" name="associate_name" value="<?= ($user_option == 'Add') ? '' : $hdr_row['associate_name'] ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Clerk</label>
					<div class="position-relative Inpt-Vw-icn">
					<?php if ($user_option == 'Add' || $user_option == 'Select') { ?>
                        <input type="text" class="form-control smlBxLft float-start w-100" name="clerk_code" id="clerkCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'associate_code', ['clerkName'], ['associate_name'], 'get_clerk_result')" value="<?= ($user_option == 'Add') ? '' : $hdr_row['clerk_code'] ?>" />
						<i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['clerk_help_id'] ?>', 'clerkCode', ['clerkName'], ['associate_name'], 'get_clerk_result')" title="View" data-toggle="modal" data-target="#lookup"></i>
					<?php } else { ?>
                        <input type="text" class="form-control smlBxLft float-start w-100" name="clerk_code" value="<?= $hdr_row['clerk_code'] ?>" readonly/>
					<?php } ?>
                    </div>
					<input type="text" class="form-control w-70 float-start" name="clerk_name" id="clerkName" value="<?= ($user_option == 'Add') ? '' : $hdr_row['clerk_name'] ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Peon</label>
					<div class="position-relative Inpt-Vw-icn">
					<?php if ($user_option == 'Add' || $user_option == 'Select') { ?>
                        <input type="text" class="form-control smlBxLft float-start w-100" name="peon_code" id="peonCode" value="<?= ($user_option == 'Add') ? '' : $hdr_row['peon_code'] ?>" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'associate_code', ['peonName'], ['associate_name'], 'get_peon_result')" />
						<i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['peon_help_id'] ?>', 'peonCode', ['peonName'], ['associate_name'], 'get_peon_result')" title="View" data-toggle="modal" data-target="#lookup"></i>
					<?php } else { ?>
                        <input type="text" class="form-control smlBxLft float-start w-100" name="clerk_code" value="<?= $hdr_row['peon_code'] ?>" readonly/>
					<?php } ?>
                    </div>
					<input type="text" class="form-control w-70 float-start" name="peon_name" id="peonName" value="" readonly/>
				</div>
			</div>		
		  </div>
		  <div class="col-md-12 mt-3">
			<div class="frms-sec d-inline-block w-100 bg-white p-3 actvFrmsec">
				<a href="javascript:void(0);" class="btn btn-primary cstmBtn mb-3 hovrnone"><?= $params['status_desc'] ?></a>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Fees - Counsel</label>
					<input type="text" class="form-control" id="ltrNO" name="counsel_fee" value="<?= ($user_option == 'Add') ? '' : $hdr_row['counsel_fee'] ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Fees - Clerk</label>
					<input type="text" class="form-control" id="ltrDte" name="clerk_fee" value="<?= ($user_option == 'Add') ? '' : $hdr_row['clerk_fee'] ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Fees - Peon</label>
					<input type="text" class="form-control" id="mdeSend" name="peon_fee" value="<?= ($user_option == 'Add') ? '' : $hdr_row['peon_fee'] ?>" readonly/>
				</div>
				
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Recd - Counsel</label>
					<input type="text" class="form-control" id="ltrNO" name="counsel_fee_recd" value="<?= ($user_option == 'Add') ? '' : $hdr_row['counsel_fee_recd'] ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Recd - Clerk</label>
					<input type="text" class="form-control" id="ltrDte" name="clerk_fee_recd" value="<?= ($user_option == 'Add') ? '' : $hdr_row['clerk_fee_recd'] ?>" readonly/>
				</div>
				<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
					<label class="d-inline-block w-100 mb-2 lbl-mn">Recd - Peon</label>
					<input type="text" class="form-control" id="mdeSend" name="peon_fee_recd" value="<?= ($user_option == 'Add') ? '' : $hdr_row['peon_fee_recd'] ?>" readonly/>
				</div>
			</div>		
		  </div>
		  <div class="col-md-12 mt-3">
			<span id="actionBtn1">
				<?php if (ucfirst($user_option) == 'Select' || ucfirst($user_option) == 'Add') { 
					if(count($reports) || ucfirst($user_option) == 'Add' || ucfirst($user_option) == 'Select') { ?>
					<button type="button" onclick="deleteRow('tbody', 'row_counter', 'actionBtn1', 'addNewRow')" class="btn btn-primary cstmBtn border border-white float-end">Delete Row</button> 
				<?php } else { ?>
					<button type="button" onclick="addNewRow(this, true, ['tbody', 'row_counter', 'actionBtn1', 'addNewRow(this, true)'])" class="btn btn-primary cstmBtn border border-white float-end">Add Row</button> 
				<?php } } ?>
			</span>
		 	 <div class="tblMn mb-2">
				<table class="table border-0">
					<tr class="fs-14">
						<th class="border w-8"></th>
						<th class="border w-8">Brief Date</th>
						<th class="border">Memo No.</th>
						<th class="border w-8">Memo Dt.</th>
						<th class="border">Matter.</th>
						<th class="border"><input type="hidden" value="Matter Desc" readonly="true"></th>
						<th class="border">Client</th>
						<th class="border">Initial</th>
						<th class="border">Counsel Fees</th>
						<th class="border">Clerkage</th>
						<th class="border">Peonage</th>
						<th class="border">Counsel Recv</th>
						<th class="border">Clerk Recv</th>
						<th class="border">Peon Recv</th>
						<th class="border">Insrument No.</th>
						<th class="border">Insrument Dt.</th>
						<th class="border text-center align-middle">Action</th>
					</tr>
					<tbody id="tbody">
						<?php
							$index = 0;
							$i = 0; $y = 6; $j = 0; $flag = false;
							if($user_option == 'Add' || $user_option == 'Select') { if($count == 0) { $j = 1; } else { $j = $count + 1; } } else { $j = $count; }
							for($k = 1; $k <= $j; $k++) {
								$flag = true; $row_1 = ($k <= $count) ? $reports[$k-1] : 0; $i++;
						?>
						<tr class="fs-14">
							<td id="Ctd<?= $i?>" class="border fw-normal" onClick="<?php if($user_option=='Add'||$user_option=='Select'){ ?>voucher_delRow(this, <?= $i ?>)<?php }?>">
								<input type="hidden" name="voucher_ok_ind<?= $i?>" value="Y" readonly="true" onClick="<?php if($user_option=='Add'||$user_option=='Select'){ ?>voucher_delRow(this, <?= $i ?>)<?php }?>">
							</td>
							<td class="border fw-normal"> <input type="text" name="brief_date<?= $i?>" value="<?= ($row_1) ? date_conv($row_1['brief_date']) : '' ?>"  onBlur="chkBriefDate(this,'<?= $i ?>')" <?= $redvc ?> ></td>
							<td class="border fw-normal"> <input type="text" name="memo_no<?= $i?>" value="<?= ($row_1) ? $row_1['memo_no'] : ''?>" <?= $redvc ?> ></td>
							<td class="border fw-normal"> <input type="text" name="memo_date<?= $i?>" value="<?= ($row_1) ? date_conv($row_1['memo_date']) : ''?>" onBlur="chkMemoDate(this,'<?= $i ?>')" <?= $redvc ?> ></td>
							<td class="border fw-normal position-relative">
								<input type="text" name="matter_code<?= $i?>" id="matterCode<?= $i ?>" value="<?= ($row_1) ? $row_1['matter_code'] : ''?>" 	oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'matter_code', ['clientCode<?= $i ?>', 'initialCode<?= $i ?>', 'matterDesc<?= $i ?>'], ['client_code', 'initial_code', 'mat_description'], 'matter_code')" <?= $redvc ?>>
								<?php if(($user_option == 'Add' || $user_option == 'Select')) { ?>    
									<i class="fa-solid fa-binoculars icn-vw" style="top:15px;" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode<?= $i ?>', ['clientCode<?= $i ?>', 'initialCode<?= $i ?>', 'matterDesc<?= $i ?>'], ['client_code', 'initial_code', 'mat_description'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
								<?php } ?>
							</td>
							<td class="border fw-normal"> <input type="hidden" name="mat_description<?= $i?>"  id="matterDesc<?= $i?>"  value="<?= ($row_1) ? stripslashes($row_1['matter_desc']) : ''?>"  readonly ></td>
							<td class="border fw-normal"> <input type="text" name="client_code<?= $i?>" id="clientCode<?= $i?>"  value="<?= ($row_1) ? $row_1['client_code'] : '' ?>"  readonly></td>
							<td class="border fw-normal"> <input type="text" name="initial_code<?= $i?>" id="initialCode<?= $i?>" value="<?= ($row_1) ? $row_1['initial_code'] : '' ?>"   readonly></td>
							<td class="border fw-normal"> <input type="text" name="counsel_fee<?= $i?>" value="<?= ($row_1) ? (($row_1['counsel_fee'] == '0') ? '' : $row_1['counsel_fee']) : ''?>" onBlur="chkCounselFee(this,'<?= $i ?>')" <?= $redvc ?>></td>
							<td class="border fw-normal"> <input type="text" name="clerk_fee<?= $i?>" value="<?= ($row_1) ? (($row_1['clerk_fee'] == '0') ? '' : $row_1['clerk_fee']) : '' ?>" 	onBlur="chkClerkFee(this,'<?= $i ?>')" <?= $redvc ?>></td>
							<td class="border fw-normal"> <input type="text" name="peon_fee<?= $i?>" value="<?= ($row_1) ? (($row_1['peon_fee'] == '0') ? '' : $row_1['peon_fee']) : '' ?>" 	onBlur="chkPeonFee(this,'<?= $i ?>')" <?= $redvc ?>></td>
							<td class="border fw-normal"> <input type="text" name="counsel_fee_recd<?= $i?>" value="<?= ($row_1) ? (($row_1['counsel_fee_recd'] == '0') ? '' : $row_1['counsel_fee_recd']) : ''?>" onBlur="chkCounselFeeRecd(this,'<?= $i ?>')" <?= $redkc ?>></td>
							<td class="border fw-normal"> <input type="text" name="clerk_fee_recd<?= $i?>" value="<?= ($row_1) ? (($row_1['clerk_fee_recd'] == '0') ? '' : $row_1['clerk_fee_recd']) : '' ?>" onBlur="chkClerkFeeRecd(this,'<?= $i ?>')" <?= $redkc ?>></td>
							<td class="border fw-normal"> <input type="text" name="peon_fee_recd<?= $i?>" value="<?= ($row_1) ? (($row_1['peon_fee_recd'] == '0') ? '' : $row_1['peon_fee_recd']) : '' ?>" onBlur="chkPeonFeeRecd(this,'<?= $i ?>')"  <?= $redkc ?>></td>
							<td class="border fw-normal"> <input type="text" name="instrument_no<?= $i?>" value="<?= ($row_1) ? $row_1['instrument_no'] : '' ?>" <?= $redkc ?>></td>
							<td class="border fw-normal"> <input type="text" name="instrument_date<?= $i?>" value="<?= ($row_1) ? date_conv($row_1['instrument_date']) : '' ?>" onBlur="chkInstrumentDate(this,'<?= $i ?>')" <?= $redkc ?>></td>
							<td class="border text-center">
								<?php if(($user_option == 'Add' || $user_option == 'Select') && ($i == $j)) { ?>    
									<input type="button" name="Add_row<?= $i ?>" value="+" title="Add Row" onClick="addNewRow(this, <?php echo $i;?>, <?php echo $y;?>)"  tabindex="<?php echo $y; ?>">
								<?php } ?>
							</td>
						</tr>
						
						<?php  $y += 14; } 
						if($k == 1) { ?>
							<td class="border fw-normal" colspan="17"> No Records Added Yet !! </td>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="col-md-12 d-inline-block">
			<?php if($user_option == 'Add' || $user_option == 'Select' || $user_option == 'Receive') { ?>	
				<button type="submit" class="btn btn-primary cstmBtn mt-3">Save</button>
			<?php } else if($user_option == 'Delete') { ?>
				<button type="submit" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Delete</button>
			<?php } ?>
				<a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn-bck btn btn-dark me-2">Back</a>
			</div>
		  </div>
      </div>
	  		<input type="hidden" name="cur_date" id="cur_date" value="<= date('d-m-Y') ?>"> 
			<input type="hidden" name="user_option"  value="<?php echo $user_option?>">
			<input type="hidden" name="row_counter" id="row_counter"  value="<?= $i ?>">
    </section>
</form>
    </main>
    <!-- End #main --> 

<script>
	//========================================================================================================================
	function addNewRow(fld,n,x) { 
		let total_row   = parseInt(document.f1.row_counter.value)*1;
		let user_option = document.f1.user_option.value;
		n = total_row;
		let m = n, flag = 1, conditionFlag = 0;
		let x1 = (x*1)+1, x2 = (x*1)+2, x3 = (x*1)+3, x4 = (x*1)+4, x5 = (x*1)+5, x6 = (x*1)+6, x7 = (x*1)+7, x8 = (x*1)+8, x9 = (x*1)+9, x10 = (x*1)+1, x11 = (x*1)+1, x12 = (x*1)+1, x13 = (x*1)+1, x14 = (x*1)+14 ; 

		if(n != 0) {
			if(eval("document.f1.brief_date"+n+".value") == "" ) {
				Swal.fire({ text: 'Please Enter Brief Date' }).then((result) => { setTimeout(() => {eval("document.f1.brief_date"+n+".focus()")}, 500) });
				flag = 0; return false;
			} else if(eval("document.f1.matter_code"+n+".value") == "" ) {
				Swal.fire({ text: 'Please Enter Matter' }).then((result) => { setTimeout(() => {eval("document.f1.matter_code"+n+".focus()")}, 500) });
				flag = 0; return false;
			} else if(eval("document.f1.counsel_fee"+n+".value") == "" ) {
				Swal.fire({ text: 'Please Enter Counsel Fees' }).then((result) => { setTimeout(() => {eval("document.f1.counsel_fee"+n+".focus()")}, 500) });
				flag = 0; return false;
			}
			conditionFlag = (eval("document.f1.brief_date"+total_row+".value") != "" && eval("document.f1.matter_code"+total_row+".value") != ""  && eval("document.f1.counsel_fee"+total_row+".value") != "");
		}

		if(document.f1.user_option.value == 'Add' || document.f1.user_option.value == 'Select') { 
			if(flag == 1) {
				if(conditionFlag || total_row == 0) {
					n++; x++; var text = "<tr>"; document.f1.row_counter.value = n; 

					if (total_row != 0) {
						fld.disabled = true; fld.style.visibility = 'hidden'; 
					} else {
						fld.setAttribute('onClick', `deleteRow('tbody', 'row_counter', 'actionBtn1', 'addNewRow')`);
						fld.innerText = "Delete Row";
						let table = document.getElementById('tbody').innerHTML = '';
					}
					
					if(user_option == 'Add' || user_option == 'Select') text += `<td id="Ctd${n}" onClick="voucher_delRow(this, ${n})" class="border fw-normal"align="center"><input type="hidden" name="voucher_ok_ind${n}" value="Y" readonly="true"></td>`;
					else text += `<td class="border fw-normal" align="center"><input type="hidden" name="voucher_ok_ind${n}" value="" readonly="true"></td>`;
					text += `
						<td class="border fw-normal"> <input type="text" name="brief_date${n}" value="${(total_row) ? document.f1['brief_date'+m].value : ''}" onBlur="chkBriefDate(this,${n})" tabindex="${x1}"></td> 
						<td class="border fw-normal"> <input type="text" name="memo_no${n}" tabindex="${x2}"></td> 
						<td class="border fw-normal"> <input type="text" name="memo_date${n}" value="${(total_row) ? document.f1['memo_date'+m].value : ''}" onBlur="chkMemoDate(this,${n})" tabindex="${x3}"></td> 
						<td class="border fw-normal position-relative"> 
							<input type="text" name="matter_code${n}" id="matterCode${n}" onBlur="chkMatterCode(this,${n})" onKeyUp="getMatterCode(this,${n})" tabindex="${x4}" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'matter_code', ['clientCode${n}', 'initialCode${n}', 'matterDesc${n}'], ['client_code', 'initial_code', 'mat_description'], 'matter_code')">
							<i class="fa-solid fa-binoculars icn-vw" style="top:15px;" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode${n}', ['clientCode${n}', 'initialCode${n}', 'matterDesc${n}'], ['client_code', 'initial_code', 'mat_description'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
						</td>
						<td class="border fw-normal"> <input type="hidden" name="mat_description${n}" id="matterDesc${n}" readonly="true" ></td> 
						<td class="border fw-normal"> <input type="text" name="client_code${n}" id="clientCode${n}" readonly="true" ></td> 
						<td class="border fw-normal"> <input type="text" name="initial_code${n}" id="initialCode${n}" readonly="true" ></td> 
						<td class="border fw-normal"> <input type="text" name="counsel_fee${n}" onBlur="chkCounselFee(this,${n})" tabindex="${x5}"></td> 
						<td class="border fw-normal"> <input type="text" name="clerk_fee${n}" onBlur="chkClerkFee(this,${n})" tabindex="${x6}"></td> 
						<td class="border fw-normal"> <input type="text" name="peon_fee${n}" onBlur="chkPeonFee(this,${n})" tabindex="${x7}"></td> 
						<td class="border fw-normal"> <input type="text" name="counsel_fee_recd${n}" onBlur="chkCounselFeeRecd(this,${n})" tabindex="${x8}" <?= $redkc ?> ></td> 
						<td class="border fw-normal"> <input type="text" name="clerk_fee_recd${n}" onBlur="chkClerkFeeRecd(this,${n})" tabindex="${x9}" <?= $redkc ?> ></td> 
						<td class="border fw-normal"> <input type="text" name="peon_fee_recd${n}" onBlur="chkPeonFeeRecd(this,${n})" tabindex="${x10}" <?= $redkc ?> ></td> 
						<td class="border fw-normal"> <input type="text" name="instrument_no${n}" tabindex="${x11}" <?= $redkc ?> ></td> 
						<td class="border fw-normal"> <input type="text" name="instrument_date${n}" value="${(total_row) ? document.f1['instrument_date'+m].value : ''}" onBlur="chkInstrumentDate(this,${n})" tabindex="${x12}" <?= $redkc ?> ></td> 
						<td class="border fw-normal"  align="center"><input type="button" name="Add_row${n}" value="+" title="Add Row" onClick="addNewRow(this,${n},${x})" tabindex="${x}"></td>  
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
						table.innerHTML = '<td class="border fw-normal"></td> <td class="border fw-normal" colspan="16">  No Records Added Yet !! </td>';
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
     calc_total_counsel();
	}

	function chkClerkFee(fld,n) {
		if(fld.value != ""){ validateNumber(fld, "Clerk Fee : ",2); }
		calc_total_clerk();
	}

	function chkPeonFee(fld,n) {
		if(fld.value != ""){ validateNumber(fld, "Peon Fee : ",2); }
		calc_total_peon();
	}

	function voucher_delRow(e, n) {
		var row = document.getElementById("Ctd"+n);
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

		calc_total_counsel_recd();
		calc_total_clerk_recd();
		calc_total_peon_recd();

	}
</script>
    <?= $this->endSection() ?>