<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?= view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<main id="main" class="main">
    <?php if (session()->getFlashdata('message') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
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
                            <div class="d-block float-start w-100">
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
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Entry Date <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1 datepicker" name="voucher_serial_date" value="<?= $params['voucher_serial_date'] ?>" onBlur="make_date(this)" <?php if($selemode == 'Y') { echo 'readonly' ; }?> />
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Total Debit <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="total_debit_amount" value="<?= number_format($params['total_debit_amount'], 2, '.', '')?>" readonly />
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Total Credit <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1 input-bg text-dark" name="total_credit_amount" value="<?php echo number_format($params['total_credit_amount'], 2, '.', '')?>" readonly />
                                    <input type="hidden" name="total_row_count" value="<?= $params['total_row_count'] ?>">
                                </div>
                            </div>
                            <div class="d-inline-block w-100 mt-3">			
                                <input type="hidden" name="user_option" value="<?= $user_option ?>">
                                <input type="hidden" name="selemode" value="Y">
                                <input type="hidden" name="mode" value="">
                                <?php if($user_option == 'Add' || $user_option == 'Edit' || $user_option == 'Delete' || $user_option == 'Print' || $user_option == 'Approve') { ?>
                                <button type="button" onclick="confirmSubmit()" class="btn btn-primary cstmBtn ms-2">Confirm</button>	
                                <?php } ?>
                                <?php if($user_option == 'Add') { ?>
                                    <button type="reset" class="btn btn-primary cstmBtn ms-2">Reset</button>				
                                <?php } ?>	
                            </div>

                            <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                <div class="d-inline-block w-100 mt-2" id="listTable">					
                                    <table class="table table-bordered tblePdngsml">
                                        <thead>
                                            <tr class="fs-14">
                                                <th class="w-250"> Row No </th>
                                                <th class="w-250"> Main </th>
                                                <th class="w-250"> Sub </th>
                                                <th class="w-250"> Matter </th>
                                                <th class="w-250"> Client </th>
                                                <th class="w-250"> Narration </th>
                                                <th class="w-250"> Debit </th>
                                                <th class="w-250"> Credit </th>
                                                <th class="text-center"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="w-150 position-relative">
                                                    <input type="text" class="form-control" name="rowno" value="<?= $params['rowno'] ?>" readonly>
                                                </td>
                                                <td class="w-150 position-relative">
                                                    <input type="text" class="form-control" name="mainac_code" id="mainacCode" value="<?= $params['mainac_code'] ?>" onfocusout="checkCode(this, 'CheckMainAc')">
                                                    <i class="fa-solid fa-binoculars icn-vw icn-vw2" onclick="showData('main_ac_code', '<?= $displayId['mainac_help_id'] ?>', 'mainacCode', [], [], '');" title="View" data-toggle="modal" data-target="#lookup"></i>	
                                                </td>
                                                <td class="w-150 position-relative">
                                                    <input type="text" class="form-control" name="subac_code" id="subacCode" value="<?= $params['subac_code'] ?>" onfocusout="checkCode(this, 'CheckSubAc')">
                                                    <i class="fa-solid fa-binoculars icn-vw icn-vw2" onclick="showData('sub_ac_code', '<?= $displayId['subac_help_id'] ?>', 'subacCode', [], [], '');" title="View" data-toggle="modal" data-target="#lookup"></i>	
                                                </td>
                                                <td class="w-150 position-relative">
                                                    <input type="text" class="form-control" name="matr_code" id="matrCode" onKeyPress="return validnumbercheck(event)"  value="<?= $params['matr_code']   ?>" onfocusout="checkCode(this, 'CheckMatter')">
                                                    <i class="fa-solid fa-binoculars icn-vw icn-vw2" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matrCode', [], [], '');" title="View" data-toggle="modal" data-target="#lookup"></i>	
                                                </td>
                                                <td class="w-150 position-relative">
                                                    <input type="text" class="form-control" name="clnt_code" value="<?= $params['clnt_code'] ?>" onBlur="javascript:(this.value=this.value.toUpperCase());" >
                                                </td>
                                                <td class="w-150 position-relative">
                                                    <input type="text" class="form-control" name="narr" value="<?= $params['narr'] ?>" onBlur="javascript:(this.value=this.value.toUpperCase());" >
                                                </td>
                                                <td class="w-150 position-relative">
                                                    <input type="text" class="form-control" onKeyPress="return validnumbercheck(event)" name="dramt" value="<?= $params['dramt'] ?>" onBlur="format_number(this,2)">
                                                </td>
                                                <td class="w-150 position-relative">
                                                    <input type="text" class="form-control" onKeyPress="return validnumbercheck(event)" name="cramt" value="<?= $params['cramt'] ?>" onBlur="format_number(this,2)">
                                                </td>
                                                <td class="w-150 position-relative"> 
                                                    <input type="text" class="form-control text-center" name="rowoptn" value="<?= $params['rowoptn'] ?>" readonly> 
                                                </td>			
                                            </tr>
                                        </tbody>
                                    </table>
						            <input type="hidden" name="subac_ind" value="<?= $params['subac_ind'] ?>">
                                </div>
                            <?php } ?>
                            
                            <?php if($selemode == 'Y') { ?>
                            <div class="d-inline-block w-100 mt-2 tblscrlvtrcl" id="listTable">					
                                <table class="table table-bordered tblePdngsml">
                                    <thead>
                                        <tr class="fs-14">
                                            <?php if($user_option != 'Add') { ?> <th class="w-250"> Row No </th> <?php } ?>
                                            <th class="w-250"> Main </th>
                                            <th class="w-250"> Sub </th>
                                            <th class="w-250"> Matter </th>
                                            <th class="w-250"> Client </th>
                                            <th class="w-250"> Narration </th>
                                            <th class="w-250"> Debit </th>
                                            <th class="w-250"> Credit </th>
                                            <?php if($user_option != 'Add') { ?> <th class="text-center"> Action </th> <?php } ?>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $tdramt = 0; $tcramt = 0; $j = 0; while ($j < $vchdtl_cnt ) { $vchdtl_row = $vchdtl_qry[$j] ; ?>
                                        <tr> 
                                            <td class="w-150 position-relative" align="left"> <input class="form-control" type="text" name="row_no<?= $j ?>" value="<?= $vchdtl_row['row_no'] ?>" readonly> </td>
                                            <td class="w-150 position-relative" align="left"> <input class="form-control" type="text" name="main_ac_code<?= $j ?>" value="<?= $vchdtl_row['main_ac_code'] ?>" readonly> </td>
                                            <td class="w-150 position-relative" align="left"> <input class="form-control" type="text" name="sub_ac_code<?= $j ?>" value="<?= $vchdtl_row['sub_ac_code'] ?>" readonly> </td>
                                            <td class="w-150 position-relative" align="left"> <input class="form-control" type="text" onKeyPress="return validnumbercheck(event)" name="matter_code<?= $j ?>" value="<?= $vchdtl_row['matter_code'] ?>" readonly> </td>
                                            <td class="w-150 position-relative" align="left"> <input class="form-control" type="text" name="client_code<?= $j ?>" value="<?= $vchdtl_row['client_code'] ?>" readonly> </td>
                                            <td class="w-150 position-relative" align="left"> <input class="form-control" type="text" name="narration<?= $j ?>" value="<?= $vchdtl_row['narration'] ?>" onBlur="javascript:(this.value=this.value.toUpperCase());" readonly> </td>
                                            <td class="w-150 position-relative" align="right"> <input class="form-control" type="text" onKeyPress="return validnumbercheck(event)" name="debit_amount<?= $j ?>" value="<?php if($vchdtl_row['dr_cr_ind'] == 'D') { echo $vchdtl_row['gross_amount'] ;} ?>" readonly> </td>
                                            <td class="w-150 position-relative" align="right"> <input class="form-control" type="text" onKeyPress="return validnumbercheck(event)" name="credit_amount<?= $j ?>" value="<?php if($vchdtl_row['dr_cr_ind'] == 'C') { echo $vchdtl_row['gross_amount'] ;} ?>" readonly> </td>
                                            <td class="w-150 position-relative" align="center"> 
                                                <a href="javascript:void(0);" title="Edit" onClick="rowEditDelete(<?= $vchdtl_row['row_no'] ?>,'<?= $j ?>', 'Edit')"><i class="fa-solid fa-pen-to-square edit" aria-hidden="true"></i></a>
                                                <a href="javascript:void(0);" title="Delete" onClick="rowEditDelete(<?= $vchdtl_row['row_no'] ?>,'<?= $j ?>', 'Delete')"><i class="fa-solid fa-trash delt" aria-hidden="true"></i></a>
                                            </td>
                                        </tr> 
                                        <?php $j++ ; if($vchdtl_row['dr_cr_ind'] == 'D') { $tdramt = $tdramt + $vchdtl_row['gross_amount'] ; } else { $tcramt = $tcramt + $vchdtl_row['gross_amount'] ; } } ?> 
				                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        </div>
                        
                    </div>
                </form>
            </div>
        </section>
    <?php } else { ?>
        <table width="750" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="200" valign="top">
                <table width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr>
                    <td width="40%" height="30" class="GroupDetail_band_portrait">&nbsp;Srl.No</td>
                    <td width="60%" height="30" class="ReportColumn_portrait">&nbsp;<?php echo $params['serial_no']; ?></td>
                    </tr>
                    <tr>
                    <td height="30" class="GroupDetail_band_portrait">&nbsp;Date</td>
                    <td height="30" class="ReportColumn_portrait">&nbsp;<?php echo $params['entry_date'];?></td>
                    </tr>
                </table>
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="vertical-align:bottom">
                    <tr valign="bottom">
                    <td class="GroupDetail_band_portrait">&nbsp;</td>
                    </tr>
                    <tr valign="bottom">
                    <td class="GroupDetail_band_portrait" valign="bottom">&nbsp;<?php echo $params['type']; ?></td>
                    </tr>
                </table>
                </td>
                <td width="350" valign="top">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                    <td height="15" class="ReportTitle_portrait" align="center"><?php echo session()->user_qry['company_name'] ?></td>
                    </tr>
                    <tr>
                    <td height="15" class="GroupDetail_band_portrait" align="center"><?php echo 'global_company_address1'; ?></td>
                    </tr>
                    <tr>
                    <td height="15" class="GroupDetail_band_portrait" align="center"><?php echo 'global_company_address2'; ?></td>
                    </tr>
                    <tr>
                    <td height="15" class="GroupDetail_band_portrait" align="center"><?php echo 'global_company_address3'; ?></td>
                    </tr>
                    <tr>
                    <td height="15" class="GroupDetail_band_portrait" align="center"><?php echo 'global_company_address4'; ?></td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                    <td class="ReportTitle_portrait" align="center" valign="top">JOURNAL VOUCHER</td>
                    </tr>
                </table>
                </td>
                <td width="200" valign="top">
                <table width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr>
                    <td width="40%" height="30" class="GroupDetail_band_portrait">&nbsp;JV No</td>
                    <td width="60%" height="30" class="ReportColumn_portrait">&nbsp;<?php echo $params['ref_ledger_serial_no']; ?></td>
                    </tr>
                    <tr>
                    <td height="30" class="GroupDetail_band_portrait">&nbsp;Date</td>
                    <td height="30" class="ReportColumn_portrait">&nbsp;<?php echo $params['passed_on']; ?></td>
                    </tr>
                    <tr>
                    <td height="30" class="GroupDetail_band_portrait">&nbsp;Daybook</td>
                    <td height="30" class="ReportColumn_portrait" align="left" style="font-size:15px;">&nbsp;<?php echo $params['daybook_code']; ?></td>
                    </tr>
                </table>
                </td>
            </tr>
            <tr><td colspan="3"><hr size="1" color="#000000"></td></tr>
        </table>
        <table width="750" cellpadding="0" cellspacing="0" border="0">
            <?php 
            $lines_per_page = 65; $page_no = 1; $line_count = 0;
            $line_count = 11; $break_cnt = 'N'; $cnt = 1; $ind = 0; $print_flag = 1; $cr_cnt = 1; $dr_cr_txt = '';
            
            while($cnt <= $total_rows) {
                    $dtl_row = $params['data'][$cnt-1];
                    $narration    = $dtl_row['narration'];
                    $main_ac_code = $dtl_row['main_ac_code'];
                    $sub_ac_code  = $dtl_row['sub_ac_code'];
                    $matter_code  = $dtl_row['matter_code'];
                    $client_code  = $dtl_row['client_code'];
                    $expense_code = $dtl_row['expense_code'];
                    $gross_amount = $dtl_row['gross_amount'];
                    $dr_cr_ind    = $dtl_row['dr_cr_ind'];

                    if($dr_cr_ind == 'C' && $cr_cnt == 1) {  $params['data'][$cnt-1]['print_flag'] = $print_flag = 1; $params['data'][$cnt-1]['cr_cnt'] = $cr_cnt = 0; }
                    if($print_flag == 0) { $txt = " style='line-height:2px;'"; } else { $txt = " style='line-height:24px;'"; $line_count = $line_count + 2;}
                    if($dr_cr_ind == 'D' && $ind == 0){ $params['data'][$cnt-1]['dr_cr_txt'] = $dr_cr_txt = 'Debit :'; $ind = 1; $print_flag = 0; } else if($params['data'][$cnt-1]['dr_cr_ind'] = $dr_cr_ind == 'C' && $ind == 1){ $params['data'][$cnt-1]['dr_cr_txt'] = $dr_cr_txt = 'To : '; $ind = 0; $print_flag = 0;} else { $params['data'][$cnt-1]['dr_cr_txt'] = $dr_cr_txt = '';} 

                    if($line_count > $lines_per_page) {
                        $line_count = 30; $page_no = $page_no + 1; $break_cnt  = 'Y';

                    } else { ?>                  
                        <tr>
                            <td width="25"  class="cellheight_1">&nbsp;</td>
                            <td width="375" class="cellheight_1">&nbsp;</td>
                            <td width="50"  class="cellheight_1">&nbsp;</td>
                            <td width="50"  class="cellheight_1">&nbsp;</td>
                            <td width="50"  class="cellheight_1">&nbsp;</td>
                            <td width="50"  class="cellheight_1">&nbsp;</td>
                            <td width="075" class="cellheight_1">&nbsp;</td>
                            <td width="075" class="cellheight_1">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="GroupDetail_band_portrait" colspan="2" align="left">&nbsp;<b><?php echo $dr_cr_txt;?></b></td>
                            <td class="GroupDetail_band_portrait" colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="GroupDetail_band_portrait" align="right" height="30" valign="bottom">[<?php echo $cnt;?>]&nbsp;&nbsp;</td>
                            <td class="GroupDetail_band_portrait"  valign="bottom"><?php echo $narration;?></td>
                            <td class="GroupDetail_band_portrait" align="right" valign="bottom"><?php echo $main_ac_code;?>&nbsp;</td>
                            <td class="GroupDetail_band_portrait" align="left" valign="bottom">&nbsp;<?php echo $sub_ac_code;?></td>
                            <td class="GroupDetail_band_portrait" valign="bottom">&nbsp;<?php echo $matter_code;?></td>
                            <td class="GroupDetail_band_portrait" valign="bottom">&nbsp;<?php echo $client_code;?></td>
                            <td class="GroupDetail_band_portrait" align="right" valign="bottom"><?php if($dr_cr_ind == 'D') { echo number_format($gross_amount,2,'.',''); } else { echo '';}?>&nbsp;</td>
                            <td class="GroupDetail_band_portrait" align="right" valign="bottom"><?php if($dr_cr_ind == 'C') { echo number_format($gross_amount,2,'.',''); } else { echo '';}?>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="GroupDetail_band_portrait" colspan="3" align="right" valign="top" height="30"><?php echo isset($params['main_ac_desc']) ? $params['main_ac_desc'] : '';?>&nbsp;</td>
                            <td class="GroupDetail_band_portrait" colspan="5" align="left"  valign="top">&nbsp;<?php echo isset($params['sub_ac_desc']) ? $params['sub_ac_desc'] : '';?></td>
                        </tr>
                    <?php $break_cnt  = 'N';  
                                      
                                    }
                                    $cnt = $cnt+1; $line_count = $line_count + 1;
                    ?>
            <?php } ?>
        </table>
        <table width="750" cellpadding="0" cellspacing="0" border="1">
            <tr height="30">
                <td width="500" class="ReportColumn_portrait" rowspan="3">&nbsp;<?php echo $params['hdr_net_riw'];?></td>
                <td width="75"  class="ReportColumn_portrait" align="right">Total&nbsp;</td>
                <td width="100" class="ReportColumn_portrait" align="right"><?php echo number_format(($params['hdr_net_amount'] != '') ? $params['hdr_net_amount'] : 0.00,2,'.','');?>&nbsp;</td>
                <td width="100" class="ReportColumn_portrait" align="right"><?php echo number_format(($params['hdr_net_amount'] != '') ? $params['hdr_net_amount'] : 0.00,2,'.','');?>&nbsp;</td>
            </tr>
        </table>
        <table width="750" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="250" height="48" class="GroupDetail_band_portrait" align="center" valign="bottom">_________________________</td>
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
    function checkCode(e, mode = '') {
        var mainac = e.value;
        let queryString = '';

        if(mainac != '') {
            if (mode == 'CheckMainAc') {
                    queryString = 'main_ac_code='+mainac;

            } else if(mode == 'CheckSubAc') {
                let subac  = document.f1.subac_code.value ;

                if (mainac != '' && subac != '') {
                    queryString = 'main_ac_code='+mainac+'&sub_ac_code='+subac;
                }
            } else if(mode == 'CheckMatter') {
                let matrcd = document.f1.matr_code.value ;

		        if (matrcd != '') {
                    queryString = 'matter_code='+matrcd;
                }
            }
    
            fetch(`/sinhaco/api/get_finance_details/${mainac}/${mode}?${queryString}`)
            .then((response) => response.json())
            .then((data) => {
                if(data.status) {
                    if (mode == 'CheckMainAc') {    
                        let mact_ind   = data.status;  
                        let mact_name  = data.main_ac_desc; 
                        let sact_ind   = data.sub_ac_ind;

                        if (!mact_ind) { 
                            Swal.fire({ text: `${mact_name}` }).then((result) => { if (result.isConfirmed) setTimeout(() => {document.f1.mainac_code.focus()}, 500) });
                            return false; 
                        } else { 
                            document.f1.subac_ind.value = sact_ind ; 
                            if (sact_ind == 'Y') { document.f1.subac_code.disabled = false ; document.f1.subac_code.focus(); } else { document.f1.subac_code.value = '' ; document.f1.subac_code.disabled = true ; document.f1.matr_code.focus(); }
                        }

                    } else if (mode == 'CheckSubAc') {    
                        let sact_ind   = data.status;  
                        let sact_name  = data.sub_ac_desc; 

                        if (!sact_ind) { 
                            Swal.fire({ text: `${sact_name}` }).then((result) => { if (result.isConfirmed) setTimeout(() => {document.f1.subac_code.focus()}, 500) });
                            return false ;
                        } else { 
                            document.f1.matr_code.focus() ; 
                        }
                    } else if (mode == 'CheckMatter') {  
                        let matr_ind   = data.status;  
                        let matr_name  = data.matter_desc; 
                        let clnt_code  = data.client_code; 
                        let clnt_name  = data.client_name; 

                        if (!matr_ind) { 
                            Swal.fire({ text: `${matr_name}` }).then((result) => { if (result.isConfirmed) setTimeout(() => {document.f1.matr_code.focus()}, 500) });
                            return false ; 

                        } else { 
                            document.f1.clnt_code.value = clnt_code ;
                            document.f1.narr.focus() ; 
                        }
                    }
                } else {
                    Swal.fire({ text: `${data.message}` }).then((result) => { if (result.isConfirmed) setTimeout(() => {e.focus()}, 500) });
                }
            });
        }
    }

    function rowEditDelete(param1, param2, option) {
        let rowno = param1; 
        let linno = param2; 

        document.f1.rowno.value       = rowno ;
        document.f1.rowoptn.value     = option; 
        document.f1.mainac_code.value = eval("document.f1.main_ac_code"+linno+".value") ;
        document.f1.subac_code.value  = eval("document.f1.sub_ac_code"+linno+".value") ;
        document.f1.matr_code.value   = eval("document.f1.matter_code"+linno+".value") ;
        document.f1.clnt_code.value   = eval("document.f1.client_code"+linno+".value") ;
        document.f1.narr.value        = eval("document.f1.narration"+linno+".value") ;
        document.f1.dramt.value       = eval("document.f1.debit_amount"+linno+".value") ;
        document.f1.cramt.value       = eval("document.f1.credit_amount"+linno+".value") ;
        document.f1.mainac_code.focus();
    }

    function confirmSubmit() {
        var userOption    = document.f1.user_option.value;

        if(userOption == 'Add' || userOption == 'Edit') {
            var brchcd   = document.f1.branch_code.value ; 
            var finyr    = document.f1.fin_year.value ; 
            var serialdt = document.f1.voucher_serial_date.value ; 
            var totdramt = document.f1.total_debit_amount.value ;
            var totcramt = document.f1.total_credit_amount.value ;
            // var rowno    = document.f1.rowno.value ;
            // var rowoptn  = document.f1.rowoptn.value ;
            var subacind = document.f1.subac_ind.value ;
            var mainac   = document.f1.mainac_code.value  ; 
            var subac    = document.f1.subac_code.value  ; 
            var matrcd   = document.f1.matr_code.value  ; 
            var clntcd   = document.f1.clnt_code.value  ; 
            var narr     = document.f1.narr.value ;
            var dramt    = document.f1.dramt.value ;
            var cramt    = document.f1.cramt.value ;

            if (mainac == '') {
                Swal.fire({ text: `Enter Main A/c Code!!` }).then((result) => { if (result.isConfirmed) setTimeout(() => {document.f1.mainac_code.focus()}, 500) });
                return false ;

            } else if (subacind == 'Y' && subac == '') {
                Swal.fire({ text: `Enter Sub A/c Code!!` }).then((result) => { if (result.isConfirmed) setTimeout(() => {document.f1.subac_code.focus()}, 500) });
                return false ;

            } else if (narr == '') {
                Swal.fire({ text: `Enter Narration!!` }).then((result) => { if (result.isConfirmed) setTimeout(() => {document.f1.narr.focus()}, 500) });
                return false ;

            } else if ((dramt > 0 && cramt > 0) || (dramt == '' && cramt == '') || (dramt == 0 && cramt == 0)) {
                Swal.fire({ text: `Enter either Debit Amount or Credit Amount!!` }).then((result) => { if (result.isConfirmed) setTimeout(() => {document.f1.dramt.focus()}, 500) });
                return false ;

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