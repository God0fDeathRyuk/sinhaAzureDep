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

    <?php if(!isset($print) && !isset($voucher_print)) { ?> 
        <section class="section dashboard">
            <div class="row">
                <form action="" name="f1" method="post">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            <div class="d-block float-start w-100">
                                <?php if($user_option != 'Add') { ?>	
                                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Serial</label>
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
                                <div class="col-md-6 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Counsel Fee <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-stat me-1" name="counsel_fee" value="<?= number_format($params['counsel_fee'], 2, '.', '') ?>" readonly/>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Clerk Fee</label>					
                                    <input class="form-control w-100 float-stat me-1" type="text" name="clerk_fee" value="<?= number_format($params['clerk_fee'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Year <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1" name="fin_year" value="<?= $params['fin_year'] ?>" readonly />
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Entry Date <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start me-1 datepicker" name="voucher_serial_date" value="<?= $params['voucher_serial_date'] ?>" onBlur="make_date(this)" <?php if($selemode == 'Y') { echo 'readonly' ; }?> />
                                    <input type="hidden" name="current_date" value="<?= $params['global_dmydate'] ?>">
                                    <input type="hidden" name="finyr_start_date" value="<?= $params['finyr_start_date'] ?>">
                                    <input type="hidden" name="finyr_end_date" value="<?= $params['finyr_end_date'] ?>">
                                </div>
                                <?php if($user_option != 'Add' && $user_option != 'Edit') { ?>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Counsel (B'log)</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="counsel_fee_blog" value="<?= number_format($params['counsel_fee_blog'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Clerk (Pay)</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="clerk_fee_payable" value="<?= number_format($params['clerk_fee_payable'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Voucher Date</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="voucher_date" value="<?php if($user_option == 'Approve') { echo $params['voucher_date'] ; } ?>" onBlur="make_date(this)" <?php if($user_option != 'Approve') { echo 'disabled';}?>>
                                    <input type="hidden" name="total_row_count"    value="<?= $params['total_row_count'] ?>">
                                    <input type="hidden" name="current_date"       value="<?= $params['global_dmydate'] ?>">
                                    <input type="hidden" name="finyr_start_date"   value="<?= $params['finyr_start_date'] ?>">
                                    <input type="hidden" name="finyr_end_date"     value="<?= $params['finyr_end_date'] ?>">
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Clerk (Curr)</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="clerk_fee_curr" value="<?= number_format($params['clerk_fee_curr'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">TDS</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="tax_amount" value="<?= number_format($params['tax_amount'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Counsel PAN</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="pan_no_cnsl" value="<?= $params['pan_no_cnsl'] ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Clerk (B'log)</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="clerk_fee_blog" value="<?= number_format($params['clerk_fee_blog'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Clerk TDS</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="clerk_tax_amount" value="<?= $params['clerk_tax_amount'] ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Clerk PAN</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="pan_no_k" value="<?= $params['pan_no_k'] ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Peon (Curr)</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="peon_fee_curr" value="<?= number_format($params['peon_fee_curr'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Peon TDS</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="peon_tax_amount" value="<?= $params['peon_tax_amount'] ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Peon (B'log)</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="peon_fee_blog" value="<?= number_format($params['peon_fee_blog'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Peon (Pay)</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="peon_fee_payable" value="<?= number_format($params['peon_fee_payable'], 2, '.', '') ?>" readonly>
                                </div>
                                <?php } else { ?>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Service Tax</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="service_tax_fee" value="<?= number_format($params['service_tax_fee'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Peon Fee</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="peon_fee" value="<?= number_format($params['peon_fee'], 2, '.', '') ?>" readonly>
                                </div>
                                <div class="col-md-6 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Counsel</label>	
                                    <div class="position-relative d-block w-33 float-start">	                                    
                                        <input class="form-control w-100 float-start me-1" type="text" name="counsel_code" id="counselCode" value="<?= $params['counsel_code'] ?>" tabindex="3" <?= $redk ?>>
                                        <?php if($user_option == 'Add' && $selemode != 'Y') { ?>
                                            <i class="fa-solid fa-binoculars icn-vw icn-vw2" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselCode', ['counselName'], ['associate_name'], '');" title="View" data-toggle="modal" data-target="#lookup"></i>	
                                        <?php } ?>
                                    </div>
                                    <input class="form-control w-65 float-start ms-2" type="text" name="counsel_name" id="counselName" value="<?= $params['counsel_name'] ?>" readonly>
                                </div>
                                <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                    <label class="d-inline-block w-100 mb-1 lbl-mn">Total Fee</label>				
                                    <input class="form-control w-100 float-stat me-1" type="text" name="total_fee" value="<?= number_format($params['total_fee'], 2, '.', '') ?>" readonly>
                                </div>
                                <?php } ?>
                                <div class="d-inline-block w-100 mt-3">			
                                    <input type="hidden" name="user_option" value="<?= $user_option ?>">
                                    <input type="hidden" name="selemode" value="Y">
                                    <input type="hidden" name="memo_srlno">
                                    <input type="hidden" name="memo_cnt" value="<?= $memo_cnt ?>">
                                    <input type="hidden" name="mode" value="">
                                    
                                    <?php if($user_option == 'Add' && $selemode != 'Y') { ?>
                                    <button type="button" onclick="confirmProceed()" class="btn btn-primary cstmBtn ms-2">Proceed</button>	
                                    <?php } else { ?>			
                                    <button type="button" onclick="confirmSubmit()" class="btn btn-primary cstmBtn ms-2">Confirm</button>	
                                    <?php } ?>	
                                    <?php if($user_option == 'Add') { ?>
                                    <button type="reset" class="btn btn-primary cstmBtn ms-2">Reset</button>				
                                    <?php } ?>	
                                </div>
                            </div>
                        </div>
                        <?php if($selemode == 'Y') { ?>
                        <div class="d-inline-block w-100 mt-2 tblscrlvtrcllrg ScrltblMn" id="listTable">					
                            <table class="table table-bordered tblePdngsml">
                                <thead>
                                    <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                    <tr class="fs-14">
                                        <th class="w-250"> Srl#</th>
                                        <th class="w-250"> Date</th>
                                        <th class="w-250"> Memo No</th>
                                        <th class="w-250"> Memo Dt</th>
                                        <th class="w-250"> Counsel</th>
                                        <th class="w-250"> Clerk</th>
                                        <th class="w-250"> Peon</th>
                                        <th class="w-250"> Counsel Fee</th>
                                        <th class="w-250"> Clerk Fee</th>
                                        <th class="w-250"> Peon Fee</th>
                                        <th class="w-250"> Service Tax</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    <?php } else { ?>
                                    <tr class="fs-14">
                                        <th class="w-250">Main</th>
                                        <th class="w-250">Sub</th>
                                        <th class="w-250">Matter</th>
                                        <th class="w-250">Client</th>
                                        <th class="w-250">Narration</th>
                                        <th class="w-250">Debit</th>
                                        <th class="w-250">Credit</th>
                                        <th class="w-250"></th>
                                    </tr>
                                    <?php } ?>
                                </thead>
                                <tbody>
                                <?php if($user_option == 'Add' || $user_option == 'Edit') {
                                    foreach($memo_qry as $key => $memo_row) { $i = $key + 1; ?>
                                    <tr>
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="serial_no<?= $i ?>" value="<?= $memo_row['serial_no'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="entry_date<?= $i ?>" value="<?= $memo_row['entry_date'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="memo_no<?= $i ?>" value="<?= $memo_row['memo_no'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="memo_date<?= $i ?>" value="<?= $memo_row['memo_date'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="counsel_code<?= $i ?>" value="<?= $memo_row['counsel_code'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="clerk_code<?= $i ?>" value="<?= $memo_row['clerk_code'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="peon_code<?= $i ?>" value="<?= $memo_row['peon_code'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="counsel_fee<?= $i ?>" value="<?= $memo_row['counsel_fee'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="clerk_fee<?= $i ?>" value="<?= $memo_row['clerk_fee'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="peon_fee<?= $i ?>" value="<?= $memo_row['peon_fee'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative"> <input type="text" class="form-control" name="service_tax_fee<?= $i ?>" value="<?= $memo_row['service_tax_fee'] ?>" readonly> </td>						
                                        <td class="w-150 position-relative text-center"> <input type="checkbox" name="ok_ind<?= $i ?>" value="Y" <?= $memo_row['check_desc'] ?> onClick="calc_total(<?= $i ?>)"> </td>						
                                    </tr>
                                <?php } } else { 
                                    $tdramt = 0; $tcramt = 0; $j = 0; while ($j < $vchdtl_cnt ) { $vchdtl_row = $vchdtl_qry[$j]; ?>
                                    <tr> 
                                        <td class="w-150 position-relative" class=""><input class="form-control" type="text" name="main_ac_code<?= $j ?>"  value="<?= $vchdtl_row['main_ac_code'] ?>" readonly></td>
                                        <td class="w-150 position-relative" class=""><input class="form-control" type="text" name="sub_ac_code<?= $j ?>"   value="<?= $vchdtl_row['sub_ac_code'] ?>"  readonly></td>
                                        <td class="w-150 position-relative" class=""><input class="form-control" type="text" name="matter_code<?= $j ?>"   value="<?= $vchdtl_row['matter_code'] ?>"  readonly></td>
                                        <td class="w-150 position-relative" class=""><input class="form-control" type="text" name="client_code<?= $j ?>"   value="<?= $vchdtl_row['client_code'] ?>"  readonly></td>
                                        <td class="w-150 position-relative" class=""><input class="form-control" type="text" name="narration<?= $j ?>"     value="<?= $vchdtl_row['narration'] ?>"    readonly></td>
                                        <td class="w-150 position-relative" class=""><input class="form-control" type="text" name="debit_amount<?= $j ?>"  value="<?php if($vchdtl_row['dr_cr_ind'] == 'D') { echo $vchdtl_row['gross_amount']; } ?>" readonly></td>
                                        <td class="w-150 position-relative" class=""><input class="form-control" type="text" name="credit_amount<?= $j ?>" value="<?php if($vchdtl_row['dr_cr_ind'] == 'C') { echo $vchdtl_row['gross_amount']; } ?>" readonly></td>
                                        <td class="w-150 position-relative" class=""><input class="form-control" type="text" name="row_no<?= $j ?>"        value="<?= $vchdtl_row['row_no'] ?>" readonly></td>
                                    </tr> 
                                <?php $j++ ; if($vchdtl_row['dr_cr_ind'] == 'D') { $tdramt = $tdramt + $vchdtl_row['gross_amount']; } else { $tcramt = $tcramt + $vchdtl_row['gross_amount']; } } } ?>
                                <?php if($user_option != 'Add' && $user_option != 'Edit') { ?>
                                    <tr>
                                        <td colspan="5" class="align-middle">
                                            <label class="d-inline-block w-100 mb-1 lbl-mn text-end">Total</label>				
                                        </td>
                                        <td>
                                            <input class="form-control w-100 float-start me-1" type="text" name="tdramt"  value="<?php echo number_format($tdramt, 2, '.', '') ?>" readonly>                                        
                                        </td>
                                        <td>
                                            <input class="form-control w-100 float-start ms-1" type="text" name="tdramt"  value="<?php echo number_format($tcramt, 2, '.', '') ?>" readonly>                                       
                                        </td>
                                        <td>&nbsp;</td>
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
    <?php } else if(isset($voucher_print)) { 
            $maxline = 65 ;
            $pageno  = 0  ;
            $lineno  = 0  ;
            $rowcnt  = 1  ;
            $report_row = $params['vchdtl_qry'][0];
            $report_cnt = $params['vchdtl_cnt'];
            while ($rowcnt <= $report_cnt) {
                $pdrcrprn = 'Y'; $pdrcrind = $report_row['dr_cr_ind'];
                if($pdrcrind == 'C') { $pdrcrtxt = 'Credit :' ; } else { $pdrcrtxt = 'Debit :' ; }

                while($pdrcrind == $report_row['dr_cr_ind'] && $rowcnt <= $report_cnt) {
                    if($lineno == 0 || $lineno > $maxline) {
                    if($lineno > $maxline) {
                        ?>
                                        </table>   
                                    </td>
                                </tr>
                            </table>   
                        <br class="pageEnd">
                        <?php 
                    }           
                    $pageno = $pageno + 1; ?>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                            <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="px-2 bg-white">
                                <tr>
                                    <td width="8%" class="report_detail_all"  align="left" rowspan="2">&nbsp;Serial</td>
                                    <td width="12%" class="report_detail_rtb"  align="left" rowspan="2">&nbsp;<b><?php echo $params['voucher_serial_no'] ?></b></td>
                                    <td width="55%" class="report_detail_none" align="center">&nbsp;<b><?= session()->user_qry['company_name'] ?></b></td>
                                    <td width="8%" class="report_detail_all"  align="left" rowspan="2">&nbsp;JV No</td>
                                    <td width="17%" class="report_detail_rtb"  align="left" rowspan="2">&nbsp;</td>
                                </tr> 
                                <tr>
                                    <td class="report_detail_none" align="center">&nbsp;<?php echo $params['branch_addr1'] ?></td>
                                </tr> 
                                <tr>
                                    <td class="report_detail_all"  align="left" rowspan="2">&nbsp;Date</td>
                                    <td class="report_detail_rtb"  align="left" rowspan="2">&nbsp;<b><?php echo $params['voucher_serial_date'] ?></b></td>
                                    <td class="report_detail_none" align="center"><?php echo $params['branch_addr2'] ?></td>
                                    <td class="report_detail_all"  align="left" rowspan="2">&nbsp;JV Date</td>
                                    <td class="report_detail_rtb"  align="left" rowspan="2">&nbsp;</td>
                                </tr> 
                                <tr>
                                    <td class="report_detail_none" align="center"><?php echo $params['branch_addr3'] ?></td>
                                </tr> 
                                <tr>
                                    <td class="report_detail_none" align="left"   rowspan="2" colspan="2" style="vertical-align:bottom">&nbsp;<?php echo $params['voucher_type'] ?>&nbsp;</td>
                                    <td class="report_detail_none" align="center" rowspan=""><?php echo $params['branch_addr4'] ?></td>
                                    <td class="report_detail_all"  align="left"   rowspan="">&nbsp;Daybook</td>
                                    <td class="report_detail_rtb"  align="left"   rowspan="">&nbsp;&nbsp;<b><?php echo $params['daybook_code'] ?></b></td>
                                </tr>
                                
                                <tr>
                                    <td class="report_detail_none" align="center">&nbsp;<b>JOURNAL VOUCHER</b>&nbsp;</td>
                                </tr> 
                            </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="px-2 bg-white">
                                <tr>
                                    <td class="report_label_text" align="right" colspan="6">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                </tr>
                                <tr class="fs-14">
                                    <th class="px-3 py-2" align="left" >&nbsp;Narration</th>
                                    <th class="px-3 py-2" align="left" >&nbsp;Main</th>
                                    <th class="px-3 py-2" align="left" >&nbsp;Sub</th>
                                    <th class="px-3 py-2" align="left" >&nbsp;Client</th>
                                    <th class="px-3 py-2" align="left" >&nbsp;Matter</th>
                                    <th class="px-3 py-2" align="right">Debit&nbsp;</th>
                                    <th class="px-3 py-2" align="right">Credit&nbsp;</th>
                                </tr>
                    <?php
                    $lineno = 8;
                    }
                    ?>
                        <tr class="fs-14">
                            <td class="p-2" align="left" colspan="7"><b><?php if($pdrcrprn == 'Y') { echo $pdrcrtxt ; }?></b></td>
                        </tr> 						 
                        <tr class="fs-14">
                            <td class="p-2" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($report_row['narration'])?></td>
                            <td class="p-2" align="left" >&nbsp;<?php echo $report_row['main_ac_code']?></td>
                            <td class="p-2" align="left" >&nbsp;<?php echo $report_row['sub_ac_code']?></td>
                            <td class="p-2" align="left" >&nbsp;<?php echo $report_row['client_code']?></td>
                            <td class="p-2" align="left" >&nbsp;<?php echo $report_row['matter_code']?></td>
                            <td class="p-2" align="right"><?php if($report_row['dr_cr_ind']=='D') { echo $report_row['gross_amount'] ; }?>&nbsp;</td>
                            <td class="p-2" align="right"><?php if($report_row['dr_cr_ind']=='C') { echo $report_row['gross_amount'] ; }?>&nbsp;</td>
                        </tr>
                    <?php 
                        $pdrcrprn = 'N' ;
                        $lineno = $lineno + 1 ;
                        $report_row = isset($params['vchdtl_qry'][$rowcnt]) ? $params['vchdtl_qry'][$rowcnt] : $params['vchdtl_qry'][$rowcnt-1];
                        $report_cnt = $params['vchdtl_cnt'];
                        $rowcnt = $rowcnt + 1 ;
                    }
                    ?>
                    <tr class="fs-14">
                        <td class="p-2" align="right" colspan="7">&nbsp;</td>
                    </tr>
                    <?php
                    $lineno = $lineno + 1 ;
                } ?>
                <tr class="fs-14">
                    <td class="p-2" colspan="4" style="vertical-align:top"><b><?php echo '(Rupees '.int_to_words($params['hdr_gross_amount']).' only)';?></b></td>
                    <td class="p-2" align="center"><b>Total</b>&nbsp;</td>
                    <td class="p-2" align="right"><b><?php echo number_format($params['hdr_gross_amount'],2,'.','');?></b>&nbsp;</td>
                    <td class="p-2"  align="right"><b><?php echo number_format($params['hdr_gross_amount'],2,'.','');?></b>&nbsp;</td>
                </tr>
                <tr>
                    <td class="report_detail_bottom" align="right">&nbsp;</td>
                    <td class="report_detail_bottom" align="right">&nbsp;</td>
                    <td class="report_detail_rb" align="right">&nbsp;</td>
                </tr>
            </table>
            </td>
        </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="px-2 bg-white">
              <tr>
                 <td colspan="7">&nbsp;</td>
              </tr>
              <tr class="fs-14">
                <td width="141" class="px-3 py-2" align="center">Tax Deducted On :&nbsp;</td>
                <td width="101" class="px-2 py-2" align="right">&nbsp;Current :&nbsp;</td>
                <td width="93" class="px-2 py-2" align="right">&nbsp;&nbsp;<?php echo number_format(($params['counsel_fee_curr']),2,'.','');?>&nbsp;</td>
                <td width="126" class="px-2 py-2" align="right">&nbsp;Counsel Backlog :&nbsp;</td>
                <td width="93" class="px-2 py-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['cnsl_fee_backlog'],2,'.','');?>&nbsp;</td>
                <td width="87" class="px-2 py-2" align="right">&nbsp;</td>
                <td width="72" class="px-2 py-2" align="left" >&nbsp;</td>
                <td width="37" class="px-2 py-2" align="left" >&nbsp;</td>
              </tr>
              <tr class="fs-14">
                <td class="p-2" align="right">&nbsp;</td>
                <td class="p-2" align="right">Counsel :&nbsp;</td>
                <td class="p-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['counsel_fee_curr'],2,'.','');?>&nbsp;</td>
                <td class="p-2" align="right">Clerkage :&nbsp;</td>
                <td class="p-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['clerk_fee_curr'],2,'.','');?>&nbsp;</td>
                <td class="p-2" align="right">Service Tax :&nbsp;</td>
                <td class="p-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['serv_tax_fee_current'],2,'.','');?>&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
              </tr>
              <tr class="fs-14">
                <td class="p-2" align="right">&nbsp;</td>
                <td class="p-2" align="right">Counsel Tax :&nbsp;</td>
                <td class="p-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['tax_amt_current'],2,'.','');?>&nbsp;</td>
                <td width="126" class="p-2" align="right">&nbsp;Clerk Backlog :&nbsp;</td>
                <td width="93" class="p-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['clerk_fee_backlog'],2,'.','');?>&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
              </tr>
            <tr class="fs-14">
                <td class="p-2" align="right">&nbsp;</td>
                <td class="p-2" align="right">Clerk Tax :&nbsp;</td>
                <td class="p-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['tax_amt_clerk'],2,'.','');?>&nbsp;</td>
                <td class="p-2" align="right">Peonage :&nbsp;</td>
                <td class="p-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['peon_fee_curr'],2,'.','');?>&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
              </tr>
              <tr class="fs-14">
                <td class="p-2" align="right">&nbsp;</td>
                <td class="p-2" align="right">Peon Tax :&nbsp;</td>
                <td class="p-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['tax_amt_peon'],2,'.','');?>&nbsp;</td>
                <td width="126" class="p-2" align="right">&nbsp;Peon Backlog :&nbsp;</td>
                <td width="93" class="p-2" align="right">&nbsp;&nbsp;<?php echo number_format($params['peon_fee_backlog'],2,'.','');?>&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
                <td class="p-2" align="left">&nbsp;</td>
              </tr>

              <tr class="fs-14">
                <td class="p-2" align="left" colspan="8"><hr size="1" noshade></td>
              </tr>
            </table>
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="px-2 bg-white">
              <tr>
                <td width="250" height="48" class="report_detail_none" align="center" valign="bottom">____<u><?php echo $params['hdr_user']?></u>_____</td>
                <td width="250" height="48" class="report_detail_none" align="center" valign="bottom">____________________</td>
                <td width="250" height="48" class="report_detail_none" align="center" valign="bottom">____________________</td>
              </tr>
              <tr>
                <td class="report_detail_none" align="center" valign="top">Prepared By</td>
                <td class="report_detail_none" align="center" valign="top">Checked By</td>
                <td class="report_detail_none" align="center" valign="top">Passed By</td>
              </tr>
            </table>

    <?php } else { echo view('pages/OtherExpenses/common_print_expenses'); } ?>
</main><!-- End #main -->

<?php if(!isset($print) && !isset($voucher_print)) { ?> 
<script>
    function calc_total(rowno) {
        var cnslfee = document.f1.counsel_fee.value * 1;
        var clrkfee = document.f1.clerk_fee.value * 1;
        var peonfee = document.f1.peon_fee.value * 1;
        var staxfee = document.f1.service_tax_fee.value * 1;
        var totlfee = document.f1.total_fee.value * 1;

        if(eval("document.f1.ok_ind"+rowno+".checked") == true) {
            cnslfee = cnslfee + eval("document.f1.counsel_fee"+rowno+".value*1") ;
            clrkfee = clrkfee + eval("document.f1.clerk_fee"+rowno+".value*1") ;
            peonfee = peonfee + eval("document.f1.peon_fee"+rowno+".value*1") ;
            staxfee = staxfee + eval("document.f1.service_tax_fee"+rowno+".value*1") ;
            totlfee = totlfee + (eval("document.f1.counsel_fee"+rowno+".value*1") + eval("document.f1.clerk_fee"+rowno+".value*1") + eval("document.f1.peon_fee"+rowno+".value*1") + eval("document.f1.service_tax_fee"+rowno+".value*1")) ;
        } else {
            cnslfee = cnslfee - eval("document.f1.counsel_fee"+rowno+".value") ;
            clrkfee = clrkfee - eval("document.f1.clerk_fee"+rowno+".value") ;
            peonfee = peonfee - eval("document.f1.peon_fee"+rowno+".value") ;
            staxfee = staxfee - eval("document.f1.service_tax_fee"+rowno+".value*1") ;
            totlfee = totlfee - (eval("document.f1.counsel_fee"+rowno+".value*1") + eval("document.f1.clerk_fee"+rowno+".value*1") + eval("document.f1.peon_fee"+rowno+".value*1") + eval("document.f1.service_tax_fee"+rowno+".value*1")) ;
        }

        document.f1.counsel_fee.value      = cnslfee ; format_number(document.f1.counsel_fee,2);
        document.f1.clerk_fee.value        = clrkfee;  format_number(document.f1.clerk_fee,2);
        document.f1.peon_fee.value         = peonfee;  format_number(document.f1.peon_fee,2);
        document.f1.service_tax_fee.value  = staxfee;  format_number(document.f1.service_tax_fee,2);
        document.f1.total_fee.value        = totlfee;  format_number(document.f1.total_fee,2);
	}

    function confirmSubmit() {
        let userOption = document.f1.user_option.value;
        
        if(userOption == 'Add' || userOption == 'Edit') {
            var row = <?php echo $memo_cnt?>;
            var chk_ind = 0 ;
            var srlno   = '' ;
            var j = 0 ;
    
            for(var i=1; i<=row; i++) {
                if(eval("document.f1.ok_ind"+i+".checked") == true) {
                    chk_ind++;
                    j = j + 1 ;
                    if(j==1) {srlno = eval("document.f1.serial_no"+i+".value") ;} else {srlno = srlno+'|'+eval("document.f1.serial_no"+i+".value") ;}
                }
            }
    
            if(chk_ind == 0) {
                alert("No record selected ............");
                return false;
            } else {
                document.f1.memo_srlno.value = srlno;
                document.f1.submit();
            }
        } else {
            document.f1.submit();
        }
    }

    function confirmProceed() {
        document.f1.selemode.value = 'proceed';
        if(document.f1.branch_code.value == '') {
		   alert('Enter Branch Code .......') ;
		   document.f1.branch_code.focus() ;
		   return false;

		} else if(document.f1.voucher_serial_date.value == '') {
		   alert('Enter Voucher Serial Date .......') ;
		   document.f1.voucher_serial_date.focus() ;
		   return false;

		} else if(document.f1.counsel_code.value == '') {
		   alert('Enter Counsel .......') ;
		   document.f1.counsel_code_to.focus() ;
		   return false;

		} else {
           var vdt    = document.f1.voucher_serial_date.value ;
   	       var cdt    = document.f1.current_date.value ;
           var fsdt   = document.f1.finyr_start_date.value ;
           var fedt   = document.f1.finyr_end_date.value ;
 	       var vdate  = vdt.substr(6,4)  + vdt.substr(3,2)  + vdt.substr(0,2) ;  
	       var cdate  = cdt.substr(6,4)  + cdt.substr(3,2)  + cdt.substr(0,2) ;  
	       var fsdate = fsdt.substr(6,4) + fsdt.substr(3,2) + fsdt.substr(0,2) ; 
	       var fedate = fedt.substr(6,4) + fedt.substr(3,2) + fedt.substr(0,2) ; 
           
           if (vdate == '') {  
  	         alert('Enter Voucher Serial Date .......'); document.f1.voucher_serial_date.focus(); return false ; 
	       }
           else if (vdate > cdate) {
	         alert('Voucher Serial Date must be <= Current Date .......'); document.f1.voucher_serial_date.focus(); return false ; 
	       }
           else if (vdate < fsdate || vdate > fedate) {
             alert('Voucher Serial Date must be within the Financial Year .......'); document.f1.voucher_serial_date.focus(); return false ; 
	       }
		}
        document.f1.submit();
    }
</script>
<?php } ?>

<?= $this->endSection() ?>