<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?= view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">
    <div class="pagetitle d-inline-block w-100">
        <h1 class="col-md-11 float-start">Advance Adjustment JV (Client-Bill) [<?= ucfirst($user_option) ?>]</h1>
        <a href="<?= base_url(session()->last_selected_end_menu) ?>" class="btn btn-primary cstmBtn btn-cncl mt-2">Back</a>
    </div><!-- End Page Title -->

    <?php if(!isset($print)) { ?> 
        <section class="section dashboard">
            <div class="row">
                <form action="" name="f1" method="post">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            <div class="d-block float-start w-75">
                                <?php if($user_option != 'Add') { ?>	
                                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Serial#</label>
                                    <input type="text" class="form-control" name="voucher_serial_no" value="<?= $params['voucher_serial_no'] ?>" readonly>
                                </div>
                                <?php } ?>
                                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Branch  <strong class="text-danger">*</strong></label>
                                    <select class="form-select cstm-inpt" name="branch_code" onClick="pass_close()" onBlur="pass_close()">
                                        <?php foreach($data['branches'] as $branch) { ?>
                                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Year <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="fin_year" value="<?= $params['fin_year'] ?>" readonly />
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Date <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1 datepicker" name="voucher_serial_date" value="<?= $params['voucher_serial_date'] ?>" onBlur="make_date(this)" <?php if($selemode == 'Y') { echo 'readonly' ; }?> />
                                </div>
                                <div class="col-md-6 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Advance# <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-stat me-1" name="advance_serial_no" id="advanceSerialNo" value="<?= $params['advance_serial_no'] ?>" onBlur="checkCode('CheckAdvanceSerial')" readonly/>
                                    <?php if($selemode!='Y') { ?> <i class="fa-solid fa-binoculars icn-vw top40" id="payeeCodeLookup" onclick="showData('serial_no', '<?= $displayId['advance_help_id'] ?>', 'advanceSerialNo', [], [], '');" title="View" data-toggle="modal" data-target="#lookup"></i> <?php } ?>			
                                </div>
                                <div class="col-md-6 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Client</label>					
                                    <input class="form-control w-100 float-stat me-1" type="text" name="client_name" value="<?= $params['client_name'] ?>" readonly>
                                    <input class="form-control w-100 float-stat me-1" type="hidden" name="client_code" value="<?= $params['client_code'] ?>" readonly>
                                    <?php if($selemode!='Y') { ?> <i class="fa-solid fa-binoculars icn-vw top40" id="payeeCodeLookup" onclick="showData('associate_name', 'display_id=<?= $displayId['payee_name_code'] ?>&myPayeeType=@payeeTypeSlNo', 'payeeName', ['payeeCode'], ['associate_code'], '');" title="View" data-toggle="modal" data-target="#lookup"></i> <?php } ?>			
                                </div>
                                <div class="col-md-12 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Matter </label>				
                                    <input class="form-control w-50 float-stat me-1" type="text" name="matter_desc" value="<?= $params['matter_desc'] ?>" readonly>
                                    <input class="form-control w-50 float-end me-1" type="hidden" name="matter_code" value="<?= $params['matter_code'] ?>" readonly>
                                    <input type="hidden" size="06" maxlength="06" name="payee_code" id="payeeCode" value="<?= $params['payee_code'] ?>" readonly>
                                </div>
                                <?php if($user_option == 'Approve') { ?>
                                <div class="col-md-12 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Voucher Date</label>				
                                    <input class="form-control w-50 float-stat me-1 datepicker" type="text" name="voucher_date" value="<?= $params['voucher_date'] ?>" onBlur="make_date(this)">
                                    <input type="hidden" name="total_row_count" value="<?php echo $params['total_row_count'] ?>">
                                    <input type="hidden" name="current_date" value="<?php echo $params['global_dmydate'] ?>">
                                    <input type="hidden" name="finyr_start_date" value="<?php echo $params['finyr_start_date'] ?>">
                                    <input type="hidden" name="finyr_end_date" value="<?php echo $params['finyr_end_date'] ?>">
                                </div>
                                <?php } ?>
                                <div class="d-inline-block w-100 mt-3">			
                                    <input type="hidden" name="memdtl_cnt" value="<?= $memdtl_cnt ?>">
                                    <input type="hidden" name="user_option" value="<?= $user_option ?>">
                                    <input type="hidden" name="selemode" value="Y">
                                    <input type="hidden" name="mode" value="">

                                    <!-- <button type="submit" class="btn btn-primary cstmBtn ms-2">Proceed</button>				 -->
                                    <button type="button" onclick="document.f1.mode.value = 'myBillJVData'; confirmSubmit()" class="btn btn-primary cstmBtn ms-2">Confirm</button>				
                                    <button type="submit" class="btn btn-primary cstmBtn ms-2">Print</button>			
                                    <button type="reset" class="btn btn-primary cstmBtn ms-2">Reset</button>				
                                </div>
                            </div>
                            <div class="d-block float-start w-25 rgtSecAll">
                                <div class="col-md-12 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Advance <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="advance_amount" value="<?= number_format($params['advance_amount'], 2, '.', '') ?>" readonly />
                                </div>
                                <div class="col-md-12 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Settled <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="adjusted_amount" value="<?= number_format($params['adjusted_amount'], 2, '.', '') ?>" readonly />
                                </div>
                                <div class="col-md-12 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Balance <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="balance_amount" value="<?= number_format($params['balance_amount'], 2, '.', '')?>" readonly />
                                </div>
                                <div class="col-md-12 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Adjusted Now <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1 input-bg text-dark" name="now_adjusted_amount" value="<?php echo number_format($params['now_adjusted_amount'], 2, '.', '')?>" readonly />
                                </div>
                            </div>
                            <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                <div class="d-inline-block w-100 mt-2 ScrltblMn" id="listTable">					
                                    <table class="table table-bordered tblePdngsml">
                                        <thead>
                                            <tr class="fs-14">
                                                <?php if($user_option != 'Add') { ?> <th class="w-250"> Row No </th> <?php } ?>
                                                <th class="w-250"> Year </th>
                                                <th class="w-250"> Bill No. </th>
                                                <th class="w-250"> Matter </th>
                                                <th class="w-250"> I/P-Tax </th>
                                                <th class="w-250"> O/P-Tax </th>
                                                <th class="w-250"> Counsel-Tax </th>
                                                <th class="w-250"> I/P-Non Tax </th>
                                                <th class="w-250"> Reimbursment </th>
                                                <th class="w-250"> Counsel-Non Tax  </th>
                                                <th class="w-250"> Total S.Tax </th>
                                                <th class="w-250"> Part/Full </th>
                                                <?php if($user_option != 'Add') { ?> <th class="text-center"> Action </th> <?php } ?>                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php if($user_option != 'Add') { ?> 
                                                    <td class="w-150 position-relative"> 
                                                        <input type="text" class="form-control" name="rowoptn" value="<?= $params['rowoptn'] ?>" readonly> 
                                                    </td>						
                                                <?php } ?>

                                                <td class="w-150"> 
                                                    <select class="form-select" name="billyr" onChange="ena_dis('bill_year',this.value,'')">
                                                    <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                                        <?php foreach($finyr_qry as $row) { ?>
                                                            <option value="<?php echo $row['fin_year']; ?>"><?php echo $row['fin_year']; ?></option>
                                                        <?php } } ?>
                                                    </select>
                                                </td>						
                                                <td class="w-150 position-relative"> 
                                                    <input type="text" class="form-control" name="billno" value="<?= $params['billno'] ?>" onBlur="checkCode('CheckBillDetails')"> 
                                                </td>						
                                                <td class="w-150 position-relative">  
                                                    <input type="text" class="form-control" name="matr_code" id="matterCode" value="<?= $params['matr_code'] ?>"> 
                                                </td>						
                                                <td class="w-150">
                                                    <div class="d-block">
                                                        <input type="text" class="form-control" name="iposamt_stax" value="<?= isset($params['iposamt_stax']) ? ($params['iposamt_stax'] != '') ? $params['iposamt_stax'] : 0 : 0 ?>" readonly>
                                                    </div>
                                                    <div class="d-block pt-1">
                                                        <input type="text" class="form-control" name="ipcolamt_stax" value="<?= isset($params['ipcolamt_stax']) ? ($params['ipcolamt_stax'] != '') ? $params['ipcolamt_stax'] : 0 : 0 ?>" onBlur="format_number(this,2);">
                                                    </div>
                                                </td>						
                                                <td class="w-150">
                                                    <div class="d-block">
                                                        <input type="text" class="form-control" name="oposamt_stax" value="<?= isset($params['oposamt_stax']) ? ($params['oposamt_stax'] != '') ? $params['oposamt_stax'] : 0 : 0 ?>" readonly>
                                                    </div>										
                                                    <div class="d-block pt-1">
                                                        <input type="text" class="form-control" name="opcolamt_stax" value="<?= isset($params['opcolamt_stax']) ? ($params['opcolamt_stax'] != '') ? $params['opcolamt_stax'] : 0 : 0 ?>" onBlur="format_number(this,2);">
                                                    </div>										
                                                </td>
                                                <td class="w-150">  
                                                    <div class="d-block">
                                                        <input type="text" class="form-control" name="cnosamt_stax" value="<?= isset($params['cnosamt_stax']) ? ($params['cnosamt_stax'] != '') ? $params['cnosamt_stax'] : 0 : 0 ?>" readonly>
                                                    </div> 
                                                    <div class="d-block pt-1">
                                                        <input type="text" class="form-control" name="cncolamt_stax" value="<?= isset($params['cncolamt_stax']) ? ($params['cncolamt_stax'] != '') ? $params['cncolamt_stax'] : 0 : 0 ?>" onBlur="format_number(this,2);">
                                                    </div> 
                                                </td>
                                                <td class="w-150">  
                                                    <div class="d-block">
                                                        <input type="text" class="form-control" name="iposamt_ntax" value="<?= isset($params['iposamt_ntax']) ? ($params['iposamt_ntax'] != '') ? $params['iposamt_ntax'] : 0 : 0 ?>" readonly> 
                                                    </div>
                                                    <div class="d-block pt-1">
                                                        <input type="text" class="form-control" name="ipcolamt_ntax" value="<?= isset($params['ipcolamt_ntax']) ? ($params['ipcolamt_ntax'] != '') ? $params['ipcolamt_ntax'] : 0 : 0 ?>" onBlur="format_number(this,2);"> 
                                                    </div>
                                                </td>
                                                <td class="w-150">  
                                                    <div class="d-block">
                                                        <input type="text" class="form-control" name="oposamt_ntax" value="<?= isset($params['oposamt_ntax']) ? ($params['oposamt_ntax'] != '') ? $params['oposamt_ntax'] : 0 : 0 ?>" readonly> 
                                                    </div>
                                                    <div class="d-block pt-1">
                                                        <input type="text" class="form-control" name="opcolamt_ntax" value="<?= isset($params['opcolamt_ntax']) ? ($params['opcolamt_ntax'] != '') ? $params['opcolamt_ntax'] : 0 : 0 ?>" onBlur="format_number(this,2);"> 
                                                    </div>
                                                </td>
                                                <td class="w-150">
                                                    <div class="d-block">
                                                        <input type="text" class="form-control" name="cnosamt_ntax" value="<?= isset($params['cnosamt_ntax']) ? ($params['cnosamt_ntax'] != '') ? $params['cnosamt_ntax'] : 0 : 0 ?>" readonly> 
                                                    </div>  
                                                    <div class="d-block pt-1">
                                                        <input type="text" class="form-control" name="cncolamt_ntax" value="<?= isset($params['cncolamt_ntax']) ? ($params['cncolamt_ntax'] != '') ? $params['cncolamt_ntax'] : 0 : 0 ?>"  onBlur="format_number(this,2);"> 
                                                    </div>  
                                                </td>
                                                <td class="w-150">  
                                                    <div class="d-block">
                                                        <input type="text" class="form-control" name="stosamt" value="<?= isset($params['stosamt']) ? ($params['stosamt'] != '') ? $params['stosamt'] : 0 : 0 ?>" readonly> 
                                                    </div>
                                                    <div class="d-block pt-1">
                                                        <input type="text" class="form-control" name="stcolamt" value="<?= isset($params['stcolamt']) ? ($params['stcolamt'] != '') ? $params['stcolamt'] : 0 : 0 ?>"  onBlur="format_number(this,2);"> 
                                                    </div>
                                                </td>
                                                <td class="w-150">
                                                    <div class="d-block"> </div>
                                                    <div class="d-block pt-1">
                                                        <select class="form-select mt-37" name="pfind">
                                                            <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                                                <option value="F" <?php if($params['pfind']=='F') { echo 'selected' ; }?>>Full</option>
                                                                <option value="P" <?php if($params['pfind']=='P') { echo 'selected' ; }?>>Part</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="lineno"             value="<?php echo isset($params['lineno']) ? $params['lineno'] : '' ?>">
                                    <input type="hidden" name="voucher_row_no"     value="<?php echo isset($params['voucher_row_no']) ? $params['voucher_row_no'] : '' ?>">
                                    <input type="hidden" name="old_billyr"         value="<?php echo isset($params['old_billyr']) ? $params['old_billyr'] : '' ?>">
                                    <input type="hidden" name="old_billno"         value="<?php echo isset($params['old_billno']) ? $params['old_billno'] : '' ?>">
                                    <input type="hidden" name="old_matr_code"      value="<?php echo isset($params['old_matr_code']) ? $params['old_matr_code'] : '' ?>">
                                    <input type="hidden" name="old_ipcolamt_stax"  value="<?php echo isset($params['old_ipcolamt_stax']) ? $params['old_ipcolamt_stax'] : ''  ?>">
                                    <input type="hidden" name="old_opcolamt_stax"  value="<?php echo isset($params['old_opcolamt_stax']) ? $params['old_opcolamt_stax'] : ''  ?>">
                                    <input type="hidden" name="old_cncolamt_stax"  value="<?php echo isset($params['old_cncolamt_stax']) ? $params['old_cncolamt_stax'] : ''  ?>">
                                    <input type="hidden" name="old_ipcolamt_ntax"  value="<?php echo isset($params['old_ipcolamt_ntax']) ? $params['old_ipcolamt_ntax'] : ''  ?>">
                                    <input type="hidden" name="old_opcolamt_ntax"  value="<?php echo isset($params['old_opcolamt_ntax']) ? $params['old_opcolamt_ntax'] : ''  ?>">
                                    <input type="hidden" name="old_cncolamt_ntax"  value="<?php echo isset($params['old_cncolamt_ntax']) ? $params['old_cncolamt_ntax'] : ''  ?>">
                                    <input type="hidden" name="old_ipcolamt"       value="<?php echo isset($params['old_ipcolamt']) ? $params['old_ipcolamt'] : ''  ?>">
                                    <input type="hidden" name="old_opcolamt"       value="<?php echo isset($params['old_opcolamt']) ? $params['old_opcolamt'] : ''  ?>">
                                    <input type="hidden" name="old_cncolamt"       value="<?php echo isset($params['old_cncolamt']) ? $params['old_cncolamt'] : ''  ?>">
                                    <input type="hidden" name="old_stcolamt"       value="<?php echo isset($params['old_stcolamt']) ? $params['old_stcolamt'] : ''  ?>">
                                    <input type="hidden" name="old_ipdefamt_stax"  value="<?php echo isset($params['old_ipdefamt_stax']) ? $params['old_ipdefamt_stax'] : ''  ?>">
                                    <input type="hidden" name="old_opdefamt_stax"  value="<?php echo isset($params['old_opdefamt_stax']) ? $params['old_opdefamt_stax'] : ''  ?>">
                                    <input type="hidden" name="old_cndefamt_stax"  value="<?php echo isset($params['old_cndefamt_stax']) ? $params['old_cndefamt_stax'] : ''  ?>">
                                    <input type="hidden" name="old_ipdefamt_ntax"  value="<?php echo isset($params['old_ipdefamt_ntax']) ? $params['old_ipdefamt_ntax'] : ''  ?>">
                                    <input type="hidden" name="old_opdefamt_ntax"  value="<?php echo isset($params['old_opdefamt_ntax']) ? $params['old_opdefamt_ntax'] : ''  ?>">
                                    <input type="hidden" name="old_cndefamt_ntax"  value="<?php echo isset($params['old_cndefamt_ntax']) ? $params['old_cndefamt_ntax'] : ''  ?>">
                                    <input type="hidden" name="old_ipdefamt"       value="<?php echo isset($params['old_ipdefamt']) ? $params['old_ipdefamt'] : ''  ?>">
                                    <input type="hidden" name="old_opdefamt"       value="<?php echo isset($params['old_opdefamt']) ? $params['old_opdefamt'] : ''  ?>">
                                    <input type="hidden" name="old_cndefamt"       value="<?php echo isset($params['old_cndefamt']) ? $params['old_cndefamt'] : ''  ?>">
                                    <input type="hidden" name="old_stdefamt"       value="<?php echo isset($params['old_stdefamt']) ? $params['old_stdefamt'] : ''  ?>">
                                    <input type="hidden" name="old_pfind"          value="<?php echo isset($params['old_pfind']) ? $params['old_pfind'] : ''     ?>">  
                                </div>
                            <?php } ?>
                        </div>
                        <?php if($selemode == 'Y') { ?>
                            <div class="d-inline-block w-100 mt-2 tblscrlvtrcl ScrltblMn" id="listTable">					
                                <table class="table table-bordered tblePdngsml">
                                    <thead>
                                        <tr class="fs-14">
                                            <th class="">Row No.</th>
                                            <th class="w-250"> Year </th>
                                            <th class="w-250"> Bill No. </th>
                                            <th class="w-250"> Matter </th>
                                            <th class="w-250"> I/P-Tax </th>
                                            <th class="w-250"> O/P-Tax </th>
                                            <th class="w-250"> Counsel-Tax </th>
                                            <th class="w-250"> I/P-Non Tax </th>
                                            <th class="w-250"> Reimbursment </th>
                                            <th class="w-250"> Counsel-Non Tax  </th>
                                            <th class="w-250"> Total S.Tax </th>
                                            <th class="w-250"> Part/Full </th>
                                            <th class="text-center"> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        <?php foreach($vchdtl_qry as $i => $vchdtl) { 
                                            // if($vchdtl['ref_bill_year'] == '' && $vchdtl['ref_bill_no'] == '' && $vchdtl['matter_code'] == '') continue;
                                            ?>
                                        <tr>								
                                            <td class="w-150"> <input type="text" class="form-control" name="row_no<?= $i?>" value="<?= $vchdtl['row_no'] ?>" readonly> </td>				
                                            <td class="w-150"> <input type="text" class="form-control" name="bill_year<?= $i?>" value="<?= $vchdtl['ref_bill_year'] ?>" readonly> </td>				
                                            <td class="w-150 position-relative"> <input type="text" class="form-control" name="bill_no<?= $i?>" value="<?= $vchdtl['ref_bill_no'] ?>"> </td>						
                                            <td class="w-150 position-relative"> <input type="text" class="form-control" name="matter_code<?= $i?>" id="matterCode<?= $i ?>" value="<?= $vchdtl['matter_code'] ?>"> </td>						
                                            <td class="w-150">
                                                <div class="d-block pt-1">
                                                    <input type="text" class="form-control" name="ipcolamt_stax<?= $i ?>" value="<?= $vchdtl['realise_amount_inpocket_stax'] ?>"  readonly>
                                                </div>
                                                <div class="d-block pt-1">
                                                    <input type="text" class="form-control" name="ipdefamt_stax<?= $i ?>" value="<?= $vchdtl['deficit_amount_inpocket_stax'] ?>" readonly>
                                                </div>
                                            </td>						
                                            <td class="w-150">
                                                <div class="d-block">
                                                    <input type="text" class="form-control" name="opcolamt_stax<?= $i ?>" value="<?= $vchdtl['realise_amount_outpocket_stax'] ?>" readonly>
                                                </div>										
                                                <div class="d-block pt-1">
                                                    <input type="text" class="form-control" name="opdefamt_stax<?= $i ?>" value="<?= $vchdtl['deficit_amount_outpocket_stax'] ?>"  readonly>
                                                </div>										
                                            </td>
                                            <td class="w-150">  
                                                <div class="d-block">
                                                    <input type="text" class="form-control" name="cncolamt_stax<?= $i ?>" value="<?= $vchdtl['realise_amount_counsel_stax'] ?>" readonly>
                                                </div> 
                                                <div class="d-block pt-1">
                                                    <input type="text" class="form-control" name="cndefamt_stax<?= $i ?>" value="<?= $vchdtl['deficit_amount_counsel_stax'] ?>"  readonly>
                                                </div> 
                                            </td>
                                            <td class="w-150">  
                                                <div class="d-block">
                                                    <input type="text" class="form-control" name="ipcolamt_ntax<?= $i?>" value="<?= $vchdtl['realise_amount_inpocket_ntax'] ?>" readonly> 
                                                </div>
                                                <div class="d-block pt-1">
                                                    <input type="text" class="form-control" name="ipdefamt_ntax<?= $i?>" value="<?= $vchdtl['deficit_amount_inpocket_ntax'] ?>"  readonly> 
                                                </div>
                                            </td>
                                            <td class="w-150">  
                                                <div class="d-block">
                                                    <input type="text" class="form-control" name="opcolamt_ntax<?= $i?>" value="<?= $vchdtl['realise_amount_outpocket_ntax'] ?>" readonly> 
                                                </div>
                                                <div class="d-block pt-1">
                                                    <input type="text" class="form-control" name="opdefamt_ntax<?= $i?>" value="<?= $vchdtl['deficit_amount_outpocket_ntax'] ?>"  readonly> 
                                                </div>
                                            </td>
                                            <td class="w-150">
                                                <div class="d-block">
                                                    <input type="text" class="form-control" name="cncolamt_ntax<?= $i?>" value="<?= $vchdtl['realise_amount_counsel_ntax'] ?>" readonly> 
                                                </div>  
                                                <div class="d-block pt-1">
                                                    <input type="text" class="form-control" name="cndefamt_ntax<?= $i?>" value="<?= $vchdtl['deficit_amount_counsel_ntax'] ?>"   readonly> 
                                                </div>  
                                            </td>
                                            <td class="w-150">  
                                                <div class="d-block">
                                                    <input type="text" class="form-control" name="stcolamt<?= $i?>" value="<?= $vchdtl['realise_amount_service_tax'] ?>" readonly> 
                                                </div>
                                                <div class="d-block pt-1">
                                                    <input type="text" class="form-control" name="stdefamt<?= $i?>" value="<?= $vchdtl['deficit_amount_service_tax'] ?>"   readonly> 
                                                </div>
                                            </td>
                                            <td class="w-150">
                                                <div class="d-block pt-1">
                                                    <input type="text" class="form-control" name="pfind<?= $i?>" value="<?= $vchdtl['part_full_ind'] ?>" readonly> 
                                                </div>
                                            </td>
                                            <td class="text-center TbladdBtn wd100">
                                                <a href="javascript:void(0);" title="Edit" onClick="myRowEditDele(<?= $vchdtl['row_no'] ?>,'<?= $i ?>', 'Edit')"><i class="fa-solid fa-pen-to-square edit" aria-hidden="true"></i></a>
                                                <a href="javascript:void(0);" title="Delete" onClick="myRowEditDele(<?= $vchdtl['row_no'] ?>,'<?= $i ?>', 'Delete')"><i class="fa-solid fa-trash delt" aria-hidden="true"></i></a>
                                            </td>								
                                        </tr>
                                        <?php } if (!count($vchdtl_qry)) { ?>
                                        <tr>
                                            <td class="w-150" colspan="16"> No Records Found !!</td>						
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </section>
    <?php } else {
            $lines_per_page = 60; $page_no = 1; $line_count = 0;
            $entry_date            = date_conv(isset($hdr_row['entry_date']) ? $hdr_row['entry_date'] : '');
            $daybook_code          = isset($hdr_row['daybook_code']) ? $hdr_row['daybook_code'] : ''; 
            $payee_payer_name      = isset($hdr_row['payee_payer_name']) ? $hdr_row['payee_payer_name'] : ''; 
            $remarks               = isset($hdr_row['remarks']) ? $hdr_row['remarks'] : ''; 
            $hdr_gross_amount      = isset($hdr_row['gross_amount']) ? $hdr_row['gross_amount'] : 0; 
            $hdr_tax_amount        = isset($hdr_row['tax_amount']) ? $hdr_row['tax_amount'] : 0; 
            $hdr_net_amount        = isset($hdr_row['net_amount']) ? $hdr_row['net_amount'] : 0;
            $ref_ledger_serial_no  = isset($hdr_row['ref_ledger_serial_no']) ? $hdr_row['ref_ledger_serial_no'] : ''; 
            $ref_advance_serial_no = isset($hdr_row['ref_advance_serial_no']) ? $hdr_row['ref_advance_serial_no'] : ''; 
            $passed_on             = date_conv(isset($hdr_row['passed_on']) ? $hdr_row['passed_on'] : ''); 
            $hdr_client_code       = isset($hdr_row['client_code']) ? $hdr_row['client_code'] : ''; 
            $hdr_client_name       = getClientName($hdr_client_code); 
            $hdr_user              = strtoupper(isset($hdr_row['prepared_by']) ? $hdr_row['prepared_by'] : ''); 

            $line_count     = 10;

            $break_cnt = 'N';
            $cnt = 1 ;
            $ind = 0;
            $print_flag = 1;
            $cr_cnt     = 1;
            $dr_cr_txt  = '';
            $xsrl = 0;

            while($cnt <= $total_rows) {
                $dtl_row      = $res2[$cnt-1];
                $narration    = $dtl_row['narration'];
                $main_ac_code = $dtl_row['main_ac_code'];
                $sub_ac_code  = $dtl_row['sub_ac_code'];
                $matter_code  = $dtl_row['matter_code'];
                $client_code  = $dtl_row['client_code'];
                // $expense_code = $dtl_row['expense_code'];
                $gross_amount = $dtl_row['gross_amount'];
                $dr_cr_ind    = $dtl_row['dr_cr_ind'];
                
                if($dr_cr_ind == 'C' && $cr_cnt == 1) {  $print_flag = 1; $cr_cnt = 0; }
                if($print_flag == 0) { $txt = " style='line-height:2px;'"; } else { $txt = " style='line-height:24px;'"; $line_count = $line_count + 2;}
                if($dr_cr_ind == 'D' && $ind == 0){ $dr_cr_txt = 'Debit :'; $ind = 1; $print_flag = 0; } else if($dr_cr_ind == 'C' && $ind == 1){ $dr_cr_txt = 'To : '; $ind = 0; $print_flag = 0;} else { $dr_cr_txt = '';} 
            
                    if($line_count >= $lines_per_page) {
                        $line_count = 3; $page_no = $page_no + 1; $break_cnt  = 'Y';
                        ?> </table> <BR CLASS="pageEnd"> <?php

                    } else {
                        if($cnt == 1 || $break_cnt  == 'Y') {
                            $line_count = $line_count + 4; ?>
                            <table width="750" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="200" valign="top">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td height="30" colspan="4" class="GroupDetail_band_portrait"><span class="ReportTitle_portrait"><img src="./images/logo.jpg" width="83" height="86" border="0"></span></td>
                                        </tr>
                                        </table>
                                        <table width="100%" cellpadding="0" cellspacing="0" border="1">
                                            <tr>
                                                <td width="40%" height="30" class="GroupDetail_band_portrait">&nbsp;Srl.No</td>
                                                <td width="60%" height="30" class="ReportColumn_portrait">&nbsp;<?php echo $serial_no;?></td>
                                            </tr>
                                            <tr>
                                                <td height="30" class="GroupDetail_band_portrait">&nbsp;Date</td>
                                                <td height="30" class="ReportColumn_portrait">&nbsp;<?php echo $entry_date;?></td>
                                            </tr>
                                        </table>
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="vertical-align:bottom">
                                            <tr valign="bottom">
                                                <td class="GroupDetail_band_portrait">&nbsp;</td>
                                            </tr>
                                            <tr valign="bottom">
                                                <td class="GroupDetail_band_portrait" valign="bottom">&nbsp;<?php echo $type; ?></td>
                                            </tr>
                                        </table>    
                                    </td>
                                    <td width="350" valign="top">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td height="15" class="ReportTitle_portrait" align="center"><?= session()->user_qry['company_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <td height="15" class="GroupDetail_band_portrait" align="center"><?php echo '$global_company_address1'?></td>
                                        </tr>
                                        <tr>
                                            <td height="15" class="GroupDetail_band_portrait" align="center"><?php echo '$global_company_address2'?></td>
                                        </tr>
                                        <tr>
                                            <td height="15" class="GroupDetail_band_portrait" align="center"><?php echo '$global_company_address3'?></td>
                                        </tr>
                                        <tr>
                                            <td height="15" class="GroupDetail_band_portrait" align="center"><?php echo '$global_company_address4'?></td>
                                        </tr>
                                        </table>
                                        <table width="100%">
                                        <tr>
                                            <td class="ReportTitle_portrait" align="center" valign="top">JOURNAL VOUCHER </td>
                                        </tr>
                                        </table>    
                                    </td>
                                    <td width="200" valign="top">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="1">
                                        <tr>
                                            <td width="40%" height="30" class="GroupDetail_band_portrait">&nbsp;JV No</td>
                                            <td width="60%" height="30" class="ReportColumn_portrait">&nbsp;<?php echo $ref_ledger_serial_no; ?></td>
                                        </tr>
                                        <tr>
                                            <td height="30" class="GroupDetail_band_portrait">&nbsp;Date</td>
                                            <td height="30" class="ReportColumn_portrait">&nbsp;<?php echo $passed_on; ?></td>
                                        </tr>
                                        <tr>
                                            <td height="30" class="GroupDetail_band_portrait">&nbsp;Daybook</td>
                                            <td height="30" class="ReportColumn_portrait" align="left" style="font-size:15px;">&nbsp;<?php echo $daybook_code; ?></td>
                                        </tr>
                                        </table>
                                        <div align="right"><span style="font-size:15px;">Page :&nbsp;&nbsp;<?php echo $page_no; ?></span></div>
                                    </td>
                                </tr>
                            </table>
                            
                            <table width="750" cellpadding="0" cellspacing="0" border="1">
                                <tr>
                                    <td width="100%">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td width="25">&nbsp;</td>
                                            <td width="325" class="ReportColumn_portrait">&nbsp;Narration</td>
                                            <td width="50"  class="ReportColumn_portrait">&nbsp;Main</td>
                                            <td width="50"  class="ReportColumn_portrait">&nbsp;Sub</td>
                                            <td width="50"  class="ReportColumn_portrait">&nbsp;Matter</td>
                                            <td width="50"  class="ReportColumn_portrait">&nbsp;Client</td>
                                            <td width="100" class="ReportColumn_portrait" align="right">Debit&nbsp;</td>
                                            <td width="100" class="ReportColumn_portrait" align="right">Credit&nbsp;</td>
                                        </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                
                <!-- end of column heading -->
                <!-- detail rows -->
                <table width="750" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                    <td width="25"  class="cellheight_1">&nbsp;</td>
                    <td width="325" class="cellheight_1">&nbsp;</td>
                    <td width="50"  class="cellheight_1">&nbsp;</td>
                    <td width="50"  class="cellheight_1">&nbsp;</td>
                    <td width="50"  class="cellheight_1">&nbsp;</td>
                    <td width="50"  class="cellheight_1">&nbsp;</td>
                    <td width="100" class="cellheight_1">&nbsp;</td>
                    <td width="100" class="cellheight_1">&nbsp;</td>
                    </tr> 
                    <?php if($narration != '') { $xsrl++; ?>
                
                    <tr <?php echo $txt; ?>>
                    <td class="GroupDetail_band_portrait" colspan="2" align="left">&nbsp;<b><?php echo $dr_cr_txt;?></b></td>
                    <td class="GroupDetail_band_portrait" colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                    <td class="GroupDetail_band_portrait" align="right">[<?php echo $xsrl;?>]&nbsp;</td>
                    <td class="GroupDetail_band_portrait"  valign="top">&nbsp;<?php echo $narration;?></td>
                    <td class="GroupDetail_band_portrait">&nbsp;<?php echo $main_ac_code;?></td>
                    <td class="GroupDetail_band_portrait">&nbsp;<?php echo $sub_ac_code;?></td>
                    <td class="GroupDetail_band_portrait">&nbsp;<?php echo $matter_code;?></td>
                    <td class="GroupDetail_band_portrait">&nbsp;<?php echo $client_code;?></td>
                    <td class="GroupDetail_band_portrait" align="right"><?php if($dr_cr_ind == 'D') { echo number_format($gross_amount,2,'.',''); } else { echo '';}?>&nbsp;</td>
                    <td class="GroupDetail_band_portrait" align="right"><?php if($dr_cr_ind == 'C') { echo number_format($gross_amount,2,'.',''); } else { echo '';}?>&nbsp;</td>
                    </tr>
                    <?php }
                        $break_cnt  = 'N';
                        }
                    $cnt = $cnt+1 ;
                    $line_count = $line_count + 1;
            } ?>
            <tr>
            <td colspan="8">&nbsp;</td>
            </tr>	 
                
            </table>
            <!-- end of detail rows -->
            <!-- total band -->
            <table width="750" cellpadding="0" cellspacing="0" border="1">
                <tr height="50">
                <td width="500" class="ReportColumn_portrait" rowspan="3">&nbsp;<?php echo '(Rupees '.int_to_words($hdr_net_amount).' only)';?></td>
                <td width="75"  class="ReportColumn_portrait" align="right">Total&nbsp;</td>
                <td width="100" class="ReportColumn_portrait" align="right"><?php echo number_format($hdr_net_amount,2,'.','');?>&nbsp;</td>
                <td width="100" class="ReportColumn_portrait" align="right"><?php echo number_format($hdr_net_amount,2,'.','');?>&nbsp;</td>
                </tr>
            </table>
            <table width="750" cellpadding="0" cellspacing="0" border="0">
                <tr height="25">
                <td class="ReportColumn_portrait">&nbsp;</td>
                </tr>
                <tr >
                <td height="15" class="ReportTitle_portrait" >&nbsp;Client : <b><?php echo strtoupper($hdr_client_name) ?></b></td>
                </tr>
                <tr>
                <td class="ReportTitle_portrait" >&nbsp;Adv. Sl No.  : <b><?php echo strtoupper($ref_advance_serial_no) ?></b></td>
                </tr>  
            </table>
            <table width="750" cellpadding="0" cellspacing="0" border="0">
                <tr>
                <td width="250" height="48" class="GroupDetail_band_portrait" align="center" valign="bottom">____<u><?php echo $hdr_user?></u>_____</td>
                <td width="250" height="48" class="GroupDetail_band_portrait" align="center" valign="bottom">____________________</td>
                <td width="250" height="48" class="GroupDetail_band_portrait" align="center" valign="bottom">____________________</td>
                </tr>
                <tr>
                <td class="GroupDetail_band_portrait" align="center" valign="top">Prepared By</td>
                <td class="GroupDetail_band_portrait" align="center" valign="top">Checked By</td>
                <td class="GroupDetail_band_portrait" align="center" valign="top">Passed By</td>
                </tr>
            </table>
    <?php } ?>
</main>
<!-- End #main -->

<?php if(!isset($print)) { ?> 
<script>
    function checkCode(mode = '') {
        let advsrlno = document.f1.advance_serial_no.value;
        let queryString = '';

        if(advsrlno != '') {
            if (mode == 'CheckBillDetails') {
                document.f1.billno.value = document.f1.billno.value.toUpperCase();
                let clntcd = document.f1.client_code.value ;
                let matrcd = document.f1.matter_code.value ;
                let billyr = document.f1.billyr.value ;
                let billno = document.f1.billno.value ;
                queryString = 'client_code='+clntcd+'&matter_code='+matrcd+'&bill_year='+billyr+'&bill_no='+billno;
            } else if(mode == 'GetBillOs') {
                queryString = 'bill_year='+document.f1.billyr.value+'&bill_no='+document.f1.billno.value;
            }
    
            fetch(`/sinhaco/api/get_finance_details/${advsrlno}/${mode}?${queryString}`)
            .then((response) => response.json())
            .then((data) => {
                if(data.status) {
                    if(mode == 'CheckAdvanceSerial') {
                        console.log(data);
                        document.f1.client_code.value     = data.client_code; 
                        document.f1.client_name.value     = data.client_name; 
                        document.f1.matter_code.value     = data.matter_code; 
                        document.f1.matter_desc.value     = data.matter_desc; 
                        document.f1.advance_amount.value  = data.advance_amount;  format_number(document.f1.advance_amount,2);
                        document.f1.adjusted_amount.value = data.adjusted_amount;  format_number(document.f1.adjusted_amount,2); 
                        document.f1.balance_amount.value  = data.balance_amount;  format_number(document.f1.balance_amount,2); 
                        document.f1.billyr.focus();
    
                    } else if(mode == 'GetBillOs') {
                        // console.log(data);
                        document.f1.matr_code.value = data.matr_code;  
                        
                        document.f1.iposamt_stax.value = (data.iposamt_stax * 1) + (document.f1.ipcolamt_stax.value*1) + (document.f1.old_ipdefamt_stax.value*1) ;  format_number(document.f1.iposamt_stax,2) ;
                        document.f1.oposamt_stax.value = (data.oposamt_stax * 1) + (document.f1.opcolamt_stax.value*1) + (document.f1.old_opdefamt_stax.value*1) ;  format_number(document.f1.oposamt_stax,2) ;
                        document.f1.cnosamt_stax.value = (data.cnosamt_stax * 1) + (document.f1.cncolamt_stax.value*1) + (document.f1.old_cndefamt_stax.value*1) ;  format_number(document.f1.cnosamt_stax,2) ;
                        
                        document.f1.iposamt_ntax.value = (data.iposamt_ntax * 1) + (document.f1.ipcolamt_ntax.value*1) + (document.f1.old_ipdefamt_ntax.value*1) ;  format_number(document.f1.iposamt_ntax,2) ;
                        document.f1.oposamt_ntax.value = (data.oposamt_ntax * 1) + (document.f1.opcolamt_ntax.value*1) + (document.f1.old_opdefamt_ntax.value*1) ;  format_number(document.f1.oposamt_ntax,2) ;
                        document.f1.cnosamt_ntax.value = (data.cnosamt_ntax * 1) + (document.f1.cncolamt_ntax.value*1) + (document.f1.old_cndefamt_ntax.value*1) ;  format_number(document.f1.cnosamt_ntax,2) ;
                        document.f1.stosamt.value = (data.stosamt * 1) + (document.f1.stcolamt.value*1) + (document.f1.old_stdefamt.value*1) ;  format_number(document.f1.stosamt,2) ;  
                        document.f1.ipcolamt_stax.focus();
                    } else if(mode == 'CheckBillDetails') {
                        console.log(data);
                        document.f1.matr_code.value = data.matr_code ;  

                        document.f1.iposamt_stax.value   = data.iposamt_stax ;  format_number(document.f1.iposamt_stax,2) ;
                        document.f1.oposamt_stax.value   = data.oposamt_stax ;  format_number(document.f1.oposamt_stax,2) ;
                        document.f1.cnosamt_stax.value   = data.cnosamt_stax ;  format_number(document.f1.cnosamt_stax,2) ;

                        document.f1.iposamt_ntax.value   = data.iposamt_ntax ;  format_number(document.f1.iposamt_ntax,2) ;
                        document.f1.oposamt_ntax.value   = data.oposamt_ntax ;  format_number(document.f1.oposamt_ntax,2) ;
                        document.f1.cnosamt_ntax.value   = data.cnosamt_ntax ;  format_number(document.f1.cnosamt_ntax,2) ;

                        document.f1.ipcolamt_stax.value   = data.iposamt_stax ;  format_number(document.f1.ipcolamt_stax,2) ;
                        document.f1.opcolamt_stax.value   = data.oposamt_stax ;  format_number(document.f1.opcolamt_stax,2) ;
                        document.f1.cncolamt_stax.value   = data.cnosamt_stax ;  format_number(document.f1.cncolamt_stax,2) ;


                        document.f1.ipcolamt_ntax.value   = data.iposamt_ntax ;  format_number(document.f1.ipcolamt_ntax,2) ;
                        document.f1.opcolamt_ntax.value   = data.oposamt_ntax ;  format_number(document.f1.opcolamt_ntax,2) ;
                        document.f1.cncolamt_ntax.value   = data.cnosamt_ntax ;  format_number(document.f1.cncolamt_ntax,2) ;
                        document.f1.stosamt.value   = data.stosamt ;  format_number(document.f1.stosamt,2) ;  
                        document.f1.stcolamt.value   = data.stosamt ;  format_number(document.f1.stcolamt,2) ;  
                        document.f1.ipcolamt_stax.focus();
                    }
                } else {
                    console.log(data);
                    Swal.fire({ text: `${data.message}` }).then((result) => { setTimeout(() => {document.f1.advance_serial_no.focus()}, 500) });
                }
            });
        }
    }

    function myRowEditDele(param1, param2, param3) {
	    let rowno  = param1; 
	    let linno  = param2; 
		let actopt = param3;

		document.f1.rowoptn.value           = actopt ;
		document.f1.billyr.value            = eval("document.f1.bill_year"+linno+".value") ;
		document.f1.billno.value            = eval("document.f1.bill_no"+linno+".value") ;
		document.f1.matr_code.value         = eval("document.f1.matter_code"+linno+".value") ;
        document.f1.ipcolamt_stax.value     = eval("document.f1.ipcolamt_stax"+linno+".value") ;
        document.f1.opcolamt_stax.value     = eval("document.f1.opcolamt_stax"+linno+".value") ;
        document.f1.cncolamt_stax.value     = eval("document.f1.cncolamt_stax"+linno+".value") ;
        document.f1.ipcolamt_ntax.value     = eval("document.f1.ipcolamt_ntax"+linno+".value") ;
        document.f1.opcolamt_ntax.value     = eval("document.f1.opcolamt_ntax"+linno+".value") ;
        document.f1.cncolamt_ntax.value     = eval("document.f1.cncolamt_ntax"+linno+".value") ;
		document.f1.stcolamt.value          = eval("document.f1.stcolamt"+linno+".value") ;
		document.f1.pfind.value             = eval("document.f1.pfind"+linno+".value") ;
    
		document.f1.lineno.value              = linno ;
		document.f1.voucher_row_no.value      = rowno ;
		document.f1.old_billyr.value          = eval("document.f1.bill_year"+linno+".value") ;
		document.f1.old_billno.value          = eval("document.f1.bill_no"+linno+".value") ;
 		document.f1.old_matr_code.value       = eval("document.f1.matter_code"+linno+".value") ;

 		document.f1.old_ipcolamt_stax.value   = eval("document.f1.ipcolamt_stax"+linno+".value") ;
 		document.f1.old_opcolamt_stax.value   = eval("document.f1.opcolamt_stax"+linno+".value") ;
 		document.f1.old_cncolamt_stax.value   = eval("document.f1.cncolamt_stax"+linno+".value") ;
 		document.f1.old_ipcolamt_ntax.value   = eval("document.f1.ipcolamt_ntax"+linno+".value") ;
 		document.f1.old_opcolamt_ntax.value   = eval("document.f1.opcolamt_ntax"+linno+".value") ;
 		document.f1.old_cncolamt_ntax.value   = eval("document.f1.cncolamt_ntax"+linno+".value") ;

		document.f1.old_stcolamt.value        = eval("document.f1.stcolamt"+linno+".value") ;
		
		document.f1.old_ipdefamt_stax.value   = eval("document.f1.ipdefamt_stax"+linno+".value") ;
		document.f1.old_opdefamt_stax.value   = eval("document.f1.opdefamt_stax"+linno+".value") ;
		document.f1.old_cndefamt_stax.value   = eval("document.f1.cndefamt_stax"+linno+".value") ;
		
		
		document.f1.old_ipdefamt_ntax.value   = eval("document.f1.ipdefamt_ntax"+linno+".value") ;
		document.f1.old_opdefamt_ntax.value   = eval("document.f1.opdefamt_ntax"+linno+".value") ;
		document.f1.old_cndefamt_ntax.value   = eval("document.f1.cndefamt_ntax"+linno+".value") ;
		document.f1.old_stdefamt.value   = eval("document.f1.stdefamt"+linno+".value") ;
		document.f1.old_pfind.value      = eval("document.f1.pfind"+linno+".value") ;
        // console.log(eval("document.f1.matter_code"+linno+".value"));
        checkCode('GetBillOs');
	}

    function confirmSubmit() {
        var userOption    = document.f1.user_option.value;

        if(userOption == 'Add' || userOption == 'Edit') {
            var brchcd      = document.f1.branch_code.value ; 
            var finyr       = document.f1.fin_year.value ; 
            var vchsrldt    = document.f1.voucher_serial_date.value ; 
            var advsrlno    = document.f1.advance_serial_no.value ; 
            var clntcd      = document.f1.client_code.value ; 
            var matrcd      = document.f1.matter_code.value ; 
            var advamt      = document.f1.advance_amount.value ;
            var adjamt      = document.f1.adjusted_amount.value ;
            var balamt      = document.f1.balance_amount.value ; 
            var nowadjamt   = document.f1.now_adjusted_amount.value ; 
            // var rowoptn     = document.f1.rowoptn.value;
            var billyr      = document.f1.billyr.value;
            var billno      = document.f1.billno.value;
            var matrcd      = document.f1.matr_code.value;
    
            var iposamt_stax     = document.f1.iposamt_stax.value*1   ;
            var oposamt_stax     = document.f1.oposamt_stax.value*1   ;
            var cnosamt_stax     = document.f1.cnosamt_stax.value*1   ;
    
            var iposamt_ntax     = document.f1.iposamt_ntax.value*1   ;
            var oposamt_ntax     = document.f1.oposamt_ntax.value*1   ;
            var cnosamt_ntax     = document.f1.cnosamt_ntax.value*1   ;		 
            var stosamt          = document.f1.stosamt.value*1   ;
            
            var ipcolamt_stax    = document.f1.ipcolamt_stax.value*1  ;
            var opcolamt_stax    = document.f1.opcolamt_stax.value*1  ;
            var cncolamt_stax    = document.f1.cncolamt_stax.value*1  ;
            
            var ipcolamt_ntax    = document.f1.ipcolamt_ntax.value*1  ;
            var opcolamt_ntax    = document.f1.opcolamt_ntax.value*1  ;
            var cncolamt_ntax    = document.f1.cncolamt_ntax.value*1  ;
            var stcolamt    = document.f1.stcolamt.value*1  ;
            var pfind       = document.f1.pfind.value       ;
            
            var linno       = document.f1.lineno.value         ;
            var rowno       = document.f1.voucher_row_no.value ;
            var oldbillyr   = document.f1.old_billyr.value     ;
            var oldbillno   = document.f1.old_billno.value     ;
            var oldmatrcd   = document.f1.old_matr_code.value  ;

            var old_ipcolamt_stax = document.f1.old_ipcolamt_stax.value*1 ;
            var old_opcolamt_stax = document.f1.old_opcolamt_stax.value*1 ;
            var old_cncolamt_stax = document.f1.old_cncolamt_stax.value*1 ;
            
            var old_ipcolamt_ntax = document.f1.old_ipcolamt_ntax.value*1 ;
            var old_opcolamt_ntax = document.f1.old_opcolamt_ntax.value*1 ;
            var old_cncolamt_ntax = document.f1.old_cncolamt_ntax.value*1 ;
            var oldstcolamt = document.f1.old_stcolamt.value*1 ;
            
            var old_ipdefamt_stax = document.f1.old_ipdefamt_stax.value*1 ;
            var old_opdefamt_stax = document.f1.old_opdefamt_stax.value*1 ;
            var old_cndefamt_stax = document.f1.old_cndefamt_stax.value*1 ;
    
            var old_ipdefamt_ntax = document.f1.old_ipdefamt_ntax.value*1 ;
            var old_opdefamt_ntax = document.f1.old_opdefamt_ntax.value*1 ;
            var old_cndefamt_ntax = document.f1.old_cndefamt_ntax.value*1 ;
    
            var oldstdefamt = document.f1.old_stdefamt.value*1 ;
            var oldpfind    = document.f1.old_pfind.value      ;
        
            if (billyr == '') {
                alert('Enter Bill Year ........'); document.f1.billyr.focus() ; return false ;
            } else if (billno == '') {
                alert('Enter Bill Number ........'); document.f1.billno.focus() ; return false ;
    
            } else if ((ipcolamt_stax+opcolamt_stax+cncolamt_stax+ipcolamt_ntax+opcolamt_ntax+cncolamt_ntax+stcolamt) == 0) {
                alert('Enter either Inpocket or Outpocket or Counsel Amount ........'); document.f1.ipcolamt_stax.focus() ; return false ;
    
            } else if ((ipcolamt_stax+opcolamt_stax+cncolamt_stax+ipcolamt_ntax+opcolamt_ntax+cncolamt_ntax+stcolamt) > (iposamt_stax+oposamt_stax+cnosamt_stax+iposamt_ntax+oposamt_ntax+cnosamt_ntax+stosamt)) {
                alert('Total Collection Amount exceeds Total O/s Amount ........'); document.f1.ipcolamt_stax.focus() ; return false ;
    
            } else if (ipcolamt_stax > iposamt_stax) {
                alert('Collection Amount (Inpocket Tax) exceeds O/s Amount (Inpocket Tax) ........'); document.f1.ipcolamt_stax.focus() ; return false ; 
    
            } else if (opcolamt_stax > oposamt_stax) {
                alert('Collection Amount (Outpocket Tax) exceeds O/s Amount (Outpocket Tax) ........'); document.f1.opcolamt_stax.focus() ; return false ;
    
            } else if (cncolamt_stax > cnosamt_stax) {
                alert('Collection Amount (Counsel Tax) exceeds O/s Amount (Counsel Tax) ........'); document.f1.cncolamt_stax.focus() ; return false ;
    
            } else if (ipcolamt_ntax > iposamt_ntax) {
                alert('Collection Amount (Inpocket Non Tax) exceeds O/s Amount (Inpocket Non Tax) ........'); document.f1.ipcolamt_ntax.focus() ; return false ;
    
            } else if (opcolamt_ntax > oposamt_ntax) {
                alert('Collection Amount (Reimbursment) exceeds O/s Amount (Reimbursment) ........'); document.f1.opcolamt_ntax.focus() ; return false ;
    
            } else if (cncolamt_ntax > cnosamt_ntax) {
                alert('Collection Amount (Counsel Non Tax) exceeds O/s Amount (Counsel Non Tax) ........'); document.f1.cncolamt_ntax.focus() ; return false ;
    
            } else if (stcolamt > stosamt) {
                alert('Collection Amount (Service Tax) exceeds O/s Amount (Service Tax) ........'); document.f1.stcolamt.focus() ; return false ;
    
            } else if (pfind == '') {
                alert('Enter Part/Full Indicator ........'); document.f1.pfind.focus() ; return false ;
    
            } else {
                document.f1.submit();
            }
        } else {
            document.f1.submit();
        }
	}

</script>
<?php } ?>

<?= $this->endSection() ?>