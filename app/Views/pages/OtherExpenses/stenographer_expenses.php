<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?> 

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<main id="main" class="main">

    <?php if (session()->getFlashdata('show_message') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('show_message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                    <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
    <?php if(isset($row_num)) { ?>
        <div class="pagetitle">
            <h1>Stenographer Expenses [<?= $user_option ?>]</h1>
        </div><!-- End Page Title -->
        <form action="" method="post" name="stenographerExpenses" id="stenographerExpenses">
            <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                        
                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                    <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                    <select class="form-select float-start" name="branch_code" onBlur="changeFocus()" <?php echo $params['disv'];?> required>
                        <?php foreach($data['branches'] as $branch) { ?>
                            <option value="<?= $branch['branch_code'] ?>" <?php if($user_option == 'Add') { if($branch['branch_code'] == $data['branch_code']['branch_code']){ echo 'selected'; }} else { if($branch['branch_code'] == $branch_code){ echo 'selected'; } } ?>><?= strtoupper($branch['branch_name']) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="frms-sec-insde d-block float-start col-md-6 px-2 mb-1">
                    <label class="d-inline-block w-100 mb-1 lbl-mn">Code <strong class="text-danger">*</strong></label>					
                    <div class="position-relative w-35 float-start">
                        <input type="text" class="form-control w-100 float-start" name="associate_code" value="<?= ($user_option == 'Add') ? '' : $params['associate_code'] ?>" oninput="this.value = this.value.toUpperCase()" id="associateCode" onchange="fetchData(this, 'associate_code', ['associateName'], ['associate_name'], 'stenographer_code')" <?php echo $params['redokadd'];?>/>
                        <?php if($user_option == 'Add') { ?>
                            <i class="fa fa-binoculars position-absolute icn-vw icn-vw2" aria-hidden="true" onclick="showData('associate_code', '<?= $displayId['stenographer_help_id'] ?>', 'associateCode', ['associateName'], ['associate_name'], 'stenographer_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        <?php } ?>
                    </div>
                    <input type="text" class="form-control w-63 float-start ms-2" name="associate_name" id="associateName" value="<?= ($user_option == 'Add') ? '' : $params['associate_name'] ?>" readonly/>
                </div>
                <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                    <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                        <label class="d-inline-block w-100 mb-1 lbl-mn">Total Amount</label>
                        <input type="text" class="form-control" name="total_value" readonly />
                    </div>
                <?php } ?>
                <div class="col-md-12 mt-3">
                    <span id="actionBtn1">
                        <?php if (ucfirst($user_option) == 'Add' || ucfirst($user_option) == 'Edit') { 
                            if(count($stenoArray) || ucfirst($user_option) == 'Add' || ucfirst($user_option) == 'Edit') { ?>
                            <button type="button" onclick="deleteRow('tbody', 'row_counter', 'actionBtn1', 'addNewRow')" class="btn btn-primary cstmBtn border border-white float-end mb-2">Delete Row</button> 
                        <?php } else { ?>
                            <button type="button" onclick="addNewRow(this, true, ['tbody', 'row_counter', 'actionBtn1', 'addNewRow(this, true)'])" class="btn btn-primary cstmBtn border border-white float-end">Add Row</button> 
                        <?php } } ?>
                    </span>
                    <div class="d-inline-block w-100 mt-2 ScrltblMn">					
                        <table class="table table-bordered tblePdngsml">
                            <tr class="fs-14">
                                <?php if($user_option == 'Add' || $user_option == 'Edit') { ?> <th></th> <?php } ?>
                                <th>Memo Dt</th>
                                <th>Memo No</th>
                                <th>Matter </th>
                                <th>Matter Description </th>
                                <th>Client </th>
                                <th>Narration </th>
                                <?php if($user_option != 'Generate') { ?>
                                    <th>Amount </th>
                                <?php } ?>
                                <?php if($user_option != 'Add' && $user_option != 'Edit') { ?>
                                    <th>Passed Amount </th>
                                <?php } ?>
                                <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                    <th class="text-center">Action</th>
                                <?php }  ?>  
                                <?php if($user_option == 'Approve') { ?>
                                    <th><input type="checkbox" name="generateChkbox" style="border-bottom-style:none;" onClick="selectAll(this)"></th>
                                <?php } ?>
                            </tr>
<tbody id="tbody">
                                <?php

                                    $tabno = $tabno1 = $i = 0; $flag = false; $gross_amount = 0; $saved_serial_no = '';
                                    if($user_option != 'Add') { $row_num = $row_num['totalRow'] - 1; } 
                                    
                                    for ($j=1; $j <= $row_num + 1; $j++) {
                                        $flag = true; $i++;
                                        $memo_date       = ($stenoArray) ? date_conv($stenoArray[$j-1]['memo_date']) : '';
                                        $memo_no         = ($stenoArray) ? $stenoArray[$j-1]['memo_no'] : '';
                                        $matter_code     = ($stenoArray) ? $stenoArray[$j-1]['matter_code'] : '';
                                        $matter_desc1    = ($stenoArray) ? $stenoArray[$j-1]['matter_desc1'] : '';
                                        $matter_desc2    = ($stenoArray) ? $stenoArray[$j-1]['matter_desc2'] : '';
                                        $mat_description = trim($matter_desc1.' '.$matter_desc2);                                                                          
                                        $client_code     = ($stenoArray) ? $stenoArray[$j-1]['client_code'] : '';
                                        $description     = ($stenoArray) ? $stenoArray[$j-1]['description'] : '';
                                        $amount          = ($stenoArray) ? $stenoArray[$j-1]['amount'] : 0;
                                        $passed_amount   = ($stenoArray) ? $stenoArray[$j-1]['passed_amount'] : '';
                                        $serial_no       = ($stenoArray) ? $stenoArray[$j-1]['serial_no'] : ''; $saved_serial_no .= $serial_no . ',';
                                        $prepared_by     = ($stenoArray) ? $stenoArray[$j-1]['prepared_by'] : '';
                                        $prepared_on     = ($stenoArray) ? $stenoArray[$j-1]['prepared_on'] : '';
                                        $passed_by       = ($stenoArray) ? $stenoArray[$j-1]['passed_by'] : '';
                                        $passed_dt       = ($stenoArray) ? $stenoArray[$j-1]['passed_dt'] : '';
                                        $initial_code    = ($stenoArray) ? $stenoArray[$j-1]['initial_code'] : '';
                                        $gross_amount += $amount;
                                        
                                        if($passed_amount == 0.00) { $passed_amount = NULL;}
                                ?>   
                                    <tr id="row_id<?php echo $j ?>">
                                        <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                        <td id="row_id<?= $j?>" class="border fw-normal align-middle text-center wd100" onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $j ?>)<?php }?>">
                                            <input type="hidden" name="voucher_ok_ind<?= $j?>" value="Y" readonly="true" onClick="<?php if($user_option=='Add'||$user_option=='Edit'){ ?>voucher_delRow(this, <?= $j ?>)<?php }?>">
                                            <img src="<?= base_url('public/assets/img/SelectRow.png') ?>" class="slctRow" alt="Select"/>
                                        </td>	
                                        <?php } ?>
                                        <td class="w-150 text-center">
                                            <input type="text" class="form-control" name="memo_date<?php echo $j?>" value="<?= (isset($memo_date)) ? $memo_date : ''?>" onBlur="chkMemoDate_steno(this,'<?php echo $j;?>')" <?php echo $params['redv'];?> required/>
                                                                
                                            <input type="hidden" name="serial_no<?php echo $j?>"   value="<?php  if(isset($serial_no)) echo $serial_no; ?>" >
                                            <input type="hidden" name="prepared_name<?php echo $j?>"  value="<?php  if(isset($prepared_by)) echo $prepared_by; ?>" >
                                            <input type="hidden" name="prepared_dt<?php echo $j?>"  value="<?php  if(isset($prepared_on)) echo $prepared_on; ?>" >
                                            <input type="hidden" name="approve_by<?php echo $j?>"  value="<?php  if(isset($passed_by)) echo $passed_by; ?>" >
                                            <input type="hidden" name="approve_on<?php echo $j?>"  value="<?php  if(isset($passed_dt)) echo $passed_dt; ?>" >
                                            <input type="hidden" name="initial_code<?php echo $j?>" size="8"  value="<?php  if(isset($initial_code)) echo $initial_code; ?>" >
                                            <input type="hidden" name="del_falg<?php echo $j?>" size="1" value="N">
                                        </td>
                                        <td class="w-150"><input type="text" class="form-control" name="memo_no<?php echo $j?>" value="<?= (isset($memo_no)) ? $memo_no : ''?>" onBlur="chkMemoNo(this,'<?php echo $j;?>')" <?php echo $params['redv'];?> required/></td>
                                        <td class="w-250">
                                            <div class="position-relative">
                                                <input type="text" class="form-control w-100 float-start" name="matter_code<?php echo $j?>" id="matterCode<?php echo $j?>"  value="<?= (isset($matter_code)) ? $matter_code : ''?>"	oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'matter_code', ['clientCode<?= $j ?>', 'matDescription<?= $j ?>'], ['client_code', 'matter_desc'], 'matter_code')" <?php echo $params['redv'];?> required>
                                                <i class="fa fa-binoculars position-absolute icn-vw icn-vw2" aria-hidden="true" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode<?= $j ?>', ['clientCode<?= $j ?>', 'matDescription<?= $j ?>'], ['client_code', 'mat_description'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                            </div>
                                        </td>
                                        <td class="w-350"><input type="text" class="form-control" name="mat_description<?php echo $j?>" id="matDescription<?php echo $j?>" value="<?= (isset($mat_description)) ? $mat_description : '' ?>" readonly/></td>							
                                        <td class="w-150"><input type="text" class="form-control" name="client_code<?php echo $j?>" id="clientCode<?php echo $j?>" value="<?= (isset($client_code)) ? $client_code : ''?>" readonly/></td>
                                        <td class="w-150"><input type="text" class="form-control" name="description<?php echo $j?>" value="<?= (isset($description)) ? $description : '' ?>" oninput="this.value = this.value.toUpperCase()" onblur="chkNarration(this,'<?php echo $j;?>')" <?php echo $params['redv'];?> required/></td>
                                        <?php if($user_option != 'Generate') { ?>
                                            <td class="w-250"><input type="text" class="form-control" name="amount<?php echo $j?>" value="<?= (isset($amount)) ? $amount : '' ?>" onBlur="chkAmount(this,'<?php echo $j;?>')" <?php echo $params['redv'];?> required/></td>
                                        <?php } ?>
                                        <?php if($user_option != 'Add' && $user_option != 'Edit') { ?>
                                                <td class="w-250"><input type="text" class="form-control" name="passed_amount<?php echo $j?>" value="<?= (isset($passed_amount)) ? $passed_amount : ''?>" onBlur="chkPassedAmount(this,'<?php echo $j;?>')" <?php echo $params['redk'];?> required/></td>
                                        <?php } ?>
                                        <?php if($user_option == 'Add' || $user_option == 'Edit') { ?>
                                            <td class="border text-center wd100 TbladdBtn">
                                                <?php if(($user_option == 'Add' || $user_option == 'Edit' && ($i == $row_num + 1))) { ?>    
                                                    <input type="button" name="addRowBtn<?= $j ?>" value="+" title="Add Row" onClick="addNewRow(this, <?php echo $j;?>)" >
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                        <?php if($user_option == 'Approve') { ?>
                                            <td>
                                                <input type="checkbox" name="passValueChk<?php echo $j;?>" style="border-bottom-style:none" onClick="copyValue(this,'<?php echo $j;?>')" <?php if($passed_amount != NULL) { echo 'checked';}?>>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php 
                                $tabno = $tabno + 2 ;
                                $tabno1 = $tabno1 + 2 ; 
                                } if($j == 1) { ?>
                                    <td class="border fw-normal" colspan="17"> No Records Added Yet !! </td>
                                <?php } ?> 
                            </tbody>
                        </table>
                        <span class="d-none"> <input type="hidden" name="saved_serial_no" value="<?= $saved_serial_no ?>"> </span>
                    </div>
                    <?php if($user_option == 'Generate') { ?>
                        <div class="col-md-6 float-start">
                            <div class="frms-sec-insde d-block w-100 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Tax</label>
                                <select class="form-select w-63 float-start" name="tax_percent" onBlur="getTaxAmount()" onChange="getTaxAmount()">
                                    <option value=""></option>
                                <?php foreach($tax_data as $row) { ?>
                                    <option value="<?= $row['tax_code'].'|$|'.$row['tax_percent'] ?>" <?php if($row['tax_percent'] == $data['branch_code']['branch_code']) { echo 'selected'; } ?>><?php echo strtoupper($row['tax_name'])." - [".round($row['tax_percent'],2)."%]";?></option>
                                <?php } ?>
                                </select>
                                <input type="text" class="form-control ms-1 float-start w-35" name="tax_rate" value="0.00" readonly/>
                                <input type="hidden" name="tax_code" size="2">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($user_option == 'Approve') { ?>
                        <div class="col-md-2 float-start">
                            <div class="frms-sec-insde d-block w-100 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Total</label>
                                <input type="text" class="form-control w-100" name="total_value" value="0.00" readonly/>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($user_option == 'Generate') { ?>
                        <div class="col-md-2 float-start">
                            <div class="frms-sec-insde d-block w-100 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Total</label>
                                <input type="text" class="form-control w-100" name="gross_amount" value="<?= $gross_amount ?>" readonly/>
                                <input type="hidden" name="total_value">
                            </div>
                        </div>
                        <div class="col-md-2 float-start">
                            <div class="frms-sec-insde d-block w-100 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">%</label>
                                <input type="text" class="form-control w-100" name="tax_amount" value="0.00" readonly/>
                            </div>
                        </div>
                        <div class="col-md-2 float-start">
                            <div class="frms-sec-insde d-block w-100 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Net</label>
                                <input type="text" class="form-control w-100" name="net_amount" value="<?= $gross_amount ?>" readonly/>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="frms-sec-insde d-block float-start <?= ($user_option == 'Approve') ? 'mt-18' : '' ?>">
                        <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Save</button>
                        <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Reset</button>
                        <a href="<?= base_url(session()->last_selected_end_menu) ?>"  class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Back</a>
                    </div>
                </div>
            </div>
                <input type="hidden" name="user_option" value="<?php echo $user_option?>">
                <input type="hidden" name="option" value="<?php echo $user_option?>">
                <input type="hidden" name="row_counter" id="row_counter"  value="<?= $i ?>">
                <input type="hidden" name="selemode" id="selemode"  value="Y">
                <input type="hidden" name="sysdate" id="sysdate" value="<?= date('d-m-Y')?>">
                <input type="hidden" name="branch_code_copy" value="<?php echo $branch_code; ?>"> 
                <input type="hidden" name="arb_row_num" value="<?php echo $row_num+1; ?>">
                <input type="hidden" name="prepared_by" value="<?php echo session()->userId;  ?>">
                <input type="hidden" name="prepared_on" value="<?php echo date_conv(date('d-m-Y'));?>">	

        </form>
    <?php } else if(!isset($row_num)) { ?>
        <form action="" method="post" id="">
            <a href="<?= base_url($data['requested_url']) ?>" class="text-decoration-none d-block float-end btn btn-dark me-5">Back</a>
            <?php 
            foreach($params as $key => $param) { ?>
            <div class="tblDv">
                <table width="750" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto mrgLft21">
                <tr>
                    <td width="200" valign="top">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                        <td height="30" colspan="4" class="GroupDetail_band_portrait"><span class="ReportTitle_portrait"><img src="<?= base_url('public/assets/img/logo.jpg') ?>" width="155" height="65" border="0"></span></td>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000">
                    <tr>
                        <td width="40%" height="30" class="GroupDetail_band_portrait  border-blk">&nbsp;Srl.No</td>
                        <td width="60%" height="30" class="ReportColumn_portrait  border-blk">&nbsp;<?php echo $param['serial_no'];?></td>
                        </tr>
                        <tr>
                        <td width="40%" height="30" class="GroupDetail_band_portrait  border-blk">&nbsp;Date</td>
                        <td width="60%" height="30" class="ReportColumn_portrait  border-blk">&nbsp;<?php echo $param['entry_date'];?></td>
                        </tr>
                    </table>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                        <td height="35" valign="bottom" class="ReportTitle_portrait">&nbsp;Form No.AC - 3</td>
                        </tr>

                    </table>
                    </td>
                    <td width="350" valign="top">
                    <table width="100%" cellpadding="0" cellspacing="0" class="fntSml">
                        <tr>
                        <td width="74%" height="15" align="center" class="ReportTitle_portrait_company">SINHA AND COMPANY</td>
                        </tr>
                        <tr>
                        <td height="15" class="ReportTitle_portrait" align="center"><?php echo $param['branch_addr1']?></td>
                        </tr>
                        <tr>
                        <td height="15" class="ReportTitle_portrait" align="center"><?php echo $param['branch_addr2']?></td>
                        </tr>
                        <tr>
                        <td height="15" class="ReportTitle_portrait" align="center"><?php echo $param['branch_addr3']?></td>
                        </tr>
                        <tr>
                        <td height="15" class="ReportTitle_portrait" align="center"><?php echo $param['branch_addr4']?></td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                        <td class="ReportTitle_portrait" align="center" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                        <td class="ReportTitle_portrait" align="center" valign="top"><b><u>Payment Voucher</u></b></td>
                        </tr>

                    </table>
                    </td>
                    <td width="200" valign="top">
                    <table width="100%" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000">
                        <tr>
                        <td width="40%" height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Voucher No</td>
                        <td width="60%" height="30" class="ReportColumn_portrait border-blk">&nbsp;</td>
                        </tr>
                        <tr>
                        <td height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Date</td>
                        <td height="30" class="ReportColumn_portrait border-blk">&nbsp;</td>
                        </tr>
                        <tr>
                        <td height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Daybook</td>
                        <td height="30" class="ReportColumn_portrait border-blk">&nbsp;&nbsp;<?php if($param['trans_type'] == 'CB') { echo $param['daybook_code'] ; } else { echo "&nbsp;" ; } ?></td>
                        </tr>
                        <tr>
                        <td height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Cheque No.</td>
                        <td height="30" class="ReportColumn_portrait border-blk">&nbsp;&nbsp;
                        <?php if($param['trans_type'] == 'CB') { echo $param['inst_no'] ; } else { echo "&nbsp;" ; } ?></td>
                        </tr>
                        <tr>
                        <td height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Cheque Dt.</td>
                        <td height="30" class="ReportColumn_portrait border-blk">&nbsp;&nbsp;<?php if($param['trans_type'] == 'CB' && $param['daybook_code'] != '10') { echo $param['inst_dt'] ; } else { echo "&nbsp;" ; } ?></td>
                        </tr>
                        <tr>
                        <td height="30" colspan="2" class="GroupDetail_band_portrait border-blk">&nbsp;Trns Type - <?php echo $param['trans_type'];?>&nbsp; Party - <?php echo $param['payee']; ?>&nbsp; <?php echo $param['payment_type']; ?> </td>
                        </tr>
                    </table>
                </tr>
                </table>            
            <!-- end of header part -->
            <!-- column heading -->
            <table width="750" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="m-0 m-auto">
            <tr>
                <td width="100%">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                    <td width="" colspan="10" class="ReportColumn_portrait" align="center">&lt;============== Debit To =============&gt;</td>
                    </tr>
                    <tr>
                    <td width="25">&nbsp;</td>
                    <td width="275" class="ReportColumn_portrait">&nbsp;Narration</td>
                    <td width="35"  class="ReportColumn_portrait">&nbsp;</td>
                    <td width="50"  class="ReportColumn_portrait">&nbsp;Main</td>
                    <td width="50"  class="ReportColumn_portrait">&nbsp;Sub</td>
                    <td width="50"  class="ReportColumn_portrait">&nbsp;Matter</td>
                    <td width="50"  class="ReportColumn_portrait">&nbsp;Client</td>
                    <td width="50"  class="ReportColumn_portrait">&nbsp;Expn</td>
                    <td width="100" class="ReportColumn_portrait" align="right">Dr. Amt.&nbsp;(<img src="<?= base_url('public/assets/img/rupee.jpg') ?>"  height="8" border="0">)&nbsp;</td>
                    <td width="100" class="ReportColumn_portrait" align="right">Cr. Amt.&nbsp;(<img src="<?= base_url('public/assets/img/rupee.jpg') ?>" height="8" border="0">)&nbsp;</td>
                    </tr>
                </table>
                </td>
            </tr>
            </table>
            <!-- end of column heading -->
            <!-- detail rows -->
            <table width="750" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto">
            <tr>
                <td width="25"  class="cellheight_1">&nbsp;</td>
                <td width="275" class="cellheight_1">&nbsp;</td>
                <td width="25"  class="cellheight_1">&nbsp;</td>
                <td width="50"  class="cellheight_1">&nbsp;</td>
                <td width="50"  class="cellheight_1">&nbsp;</td>
                <td width="50"  class="cellheight_1">&nbsp;</td>
                <td width="50"  class="cellheight_1">&nbsp;</td>
                <td width="50"  class="cellheight_1">&nbsp;</td>
                <td width="100" class="cellheight_1">&nbsp;</td>
                <td width="100" class="cellheight_1">&nbsp;</td>
            </tr>
            <tr>
            <td class="GroupDetail_band_portrait" align="right">[<b><?php echo $param['cnt']?></b>]&nbsp;&nbsp;</td>
            <td class="GroupDetail_band_portrait" rowspan="2" valign="top">&nbsp;<?php echo $param['narration']?></td>
            <td >&nbsp;</td>
            <td class="ReportColumn_portrait">&nbsp;<u><?php echo $param['main_ac_code']?></u></td>
            <td class="GroupDetail_band_portrait">&nbsp;<u><?php echo $param['sub_ac_code']?></u></td>
            <td class="GroupDetail_band_portrait">&nbsp;<u><?php echo $param['matter_code']?></u></td>
            <td class="GroupDetail_band_portrait">&nbsp;<u><?php echo $param['client_code']?></u></td>
            <td class="GroupDetail_band_portrait">&nbsp;<u><?php echo $param['expense_code']?></u></td>
            <td class="GroupDetail_band_portrait" align="right"><?php if($param['dr_cr_ind'] == 'D' && $param['gross_amount']>0) echo number_format($param['gross_amount'],2,'.','');?>&nbsp;</td>
            <td class="GroupDetail_band_portrait" align="right"><?php if($param['dr_cr_ind'] == 'C' && $param['gross_amount']>0) echo number_format($param['gross_amount'],2,'.','');?>&nbsp;</td>
            </tr>
            <tr>
                <td class="GroupDetail_band_portrait" align="right" height="30">&nbsp;</td>
                <!-- second line of narration-->
                <td >&nbsp;</td>
                <td class="ReportColumn_portrait" colspan="7" height="30" valign="top">&nbsp;<?php echo $param['main_ac_desc'] . $param['sub_ac_desc'];?></td>
            </tr>
            </table>
            <!-- end of detail rows -->
            <!-- total band -->
            <table width="750" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="m-0 m-auto">
            <tr height="50">
                <td width="500" class="ReportColumn_portrait" rowspan="3">&nbsp;<?php echo $param['hdr_net_riw'] ;?></td>
                <td width="75"  class="ReportColumn_portrait" align="right">Total&nbsp;<img src="<?= base_url('public/assets/img/rupee.jpg') ?>" height="8" border="0">&nbsp;</td>
                <td width="100" class="ReportColumn_portrait" align="right"><?php echo number_format($param['hdr_net_amount'],2,'.','');?>&nbsp;</td>
                <td width="100" class="ReportColumn_portrait" align="right">&nbsp;</td>
            </tr>
            </table>
            <!-- end of total band -->
            <!-- footer band -->
            <table width="750" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto">
            <tr>
                <td width="100" height="30" class="cellheight_1">&nbsp;</td>
                <td width="650" height="30" class="cellheight_1">&nbsp;</td>
            </tr>
            <tr>
                <td height="30" class="GroupDetail_band_portrait">&nbsp;Pay To:</td>
                <td height="30" class="GroupDetail_band_portrait" colspan="5">&nbsp;<b><?php echo $param['payee_payer_name'];?></b></td>
            </tr>
            <tr>
                <td height="37" class="GroupDetail_band_portrait" valign="top">&nbsp;Remarks:</td>
                <td height="37" class="GroupDetail_band_portrait" colspan="5" valign="top">&nbsp;<?php echo $param['remarks'];?> &nbsp;<strong><?php if($param['ref_advance_serial_no'] != '') echo 'ADV SL#: '.$param['ref_advance_serial_no'];?></strong></td>
            </tr>

            <tr>
                <td colspan="6" class="cellheight_1"><hr size="1"></td>
            </tr>
            </table>
            <!-- end of footer band -->
            <!-- signature band -->
            <table width="750" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto">
            <tr>
                <td width="225" height="40" class="GroupDetail_band_portrait" align="center" valign="bottom">____<u><?php echo $param['hdr_user']?></u>_____</td>
                <td width="150" height="40" class="GroupDetail_band_portrait" align="center" valign="bottom">____________________</td>
                <td width="150" height="40" class="GroupDetail_band_portrait" align="center" valign="bottom">____________________</td>
                <td width="225" height="40" class="GroupDetail_band_portrait" align="center" valign="bottom">_________________________</td>
            </tr>
            <tr>
                <td class="GroupDetail_band_portrait" align="center" valign="top">Prepared By</td>
                <td class="GroupDetail_band_portrait" align="center" valign="top">Checked By</td>
                <td class="GroupDetail_band_portrait" align="center" valign="top">Passed By</td>
                <td class="GroupDetail_band_portrait" align="center" valign="top">Signature of the Payee</td>
            </tr>
            </table>
            </div>
            <?php } ?>
        </form>
    <?php } ?>
</main>
<script>
    function addNewRow(fld,n) {
        var total_row = parseInt(document.stenographerExpenses.row_counter.value)*1;
        let user_option = document.stenographerExpenses.user_option.value;
        n = total_row;
        let m = n, flag = 1, conditionFlag = 0;
        if(n != 0) {
            if (eval("document.stenographerExpenses.memo_date"+total_row+".value") == "") {
                Swal.fire({ text: 'Please Enter Memo Date' }).then((result) => { setTimeout(() => {eval("document.stenographerExpenses.memo_date"+n+".focus()")}, 500) });
                flag = 0; return false;
            } else if (eval("document.stenographerExpenses.memo_no"+total_row+".value") == "") {
                Swal.fire({ text: 'Please Enter Memo No' }).then((result) => { setTimeout(() => {eval("document.stenographerExpenses.memo_no"+n+".focus()")}, 500) });
                flag = 0; return false;
            } else if (eval("document.stenographerExpenses.matter_code"+total_row+".value") == "") {
                Swal.fire({ text: 'Please Enter Matter Code' }).then((result) => { setTimeout(() => {eval("document.stenographerExpenses.matter_code"+n+".focus()")}, 500) });
                flag = 0; return false;
            } else if (eval("document.stenographerExpenses.description"+total_row+".value") == ""){
                Swal.fire({ text: 'Please Enter Narration' }).then((result) => { setTimeout(() => {eval("document.stenographerExpenses.description"+n+".focus()")}, 500) });
                flag = 0; return false;
            } else if (eval("document.stenographerExpenses.amount"+total_row+".value") == "") {
                Swal.fire({ text: 'Please Enter Amount' }).then((result) => { setTimeout(() => {eval("document.stenographerExpenses.amount"+n+".focus()")}, 500) });
                flag = 0; return false;
            }
			conditionFlag = (eval("document.stenographerExpenses.memo_date"+total_row+".value") != "" && eval("document.stenographerExpenses.matter_code"+total_row+".value") != ""  && eval("document.stenographerExpenses.description"+total_row+".value") != ""  && eval("document.stenographerExpenses.description"+total_row+".amount") != "");
        }

        if (n == total_row) 
        {  
            calc_total();

            if(document.stenographerExpenses.user_option.value == 'Add' || document.stenographerExpenses.user_option.value == 'Edit')
            { 
                if(flag == 1) {
                    if(conditionFlag || total_row == 0) {
                        n++; var text = "<tr>"; document.stenographerExpenses.row_counter.value = n; 

                        if (total_row != 0) {
                            fld.disabled = true; fld.style.visibility = 'hidden'; 
                        } else {
                            fld.setAttribute('onClick', `deleteRow('tbody', 'row_counter', 'actionBtn1', 'addNewRow')`);
                            fld.innerText = "Delete Row";
                            let table = document.getElementById('tbody').innerHTML = '';
                        }
                        if(user_option == 'Add' || user_option == 'Edit') text += `<td id="row_id${n}" onClick="voucher_delRow(this, ${n})" class="border fw-normal"align="center"><input type="hidden" name="voucher_ok_ind${n}" value="Y" readonly="true"><img src="<?= base_url('public/assets/img/SelectRow.png') ?>" class="slctRow" alt="Select"/></td>`;
                        else text += `<td class="border fw-normal" align="center"><input type="hidden" name="voucher_ok_ind${n}" value="" readonly="true"><img src="<?= base_url('public/assets/img/SelectRow.png') ?>" class="slctRow" alt="Select"/></td>`;
                        

                        text += `
                            <td class="border fw-normal"> 
                                <input type="text" class="form-control datepicker" name="memo_date${n}" onBlur="chkMemoDate_steno(this,${n})">
                                                                                                                            
                                <input type="hidden" name="serial_no${n}"   value="" >
                                <input type="hidden" name="prepared_name${n}"  value="" >
                                <input type="hidden" name="prepared_dt${n}"  value="" >
                                <input type="hidden" name="approve_by${n}"  value="" >
                                <input type="hidden" name="approve_on${n}"  value="" >
                                <input type="hidden" name="initial_code${n}" size="8"  value="" >
                                <input type="hidden" name="del_falg${n}" size="1" value="N">
                            </td> 
                            <td class="border fw-normal"> <input type="text" class="form-control" name="memo_no${n}" onBlur="chkMemoNo(this,${n})"></td> 
                            <td class="border fw-normal position-relative"> 
                                <input type="text" class="form-control" name="matter_code${n}" id="matterCode${n}" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'matter_code', ['clientCode${n}', 'matterDesc${n}'], ['client_code', 'matter_desc'], 'matter_code')">
                                <i class="fa-solid fa-binoculars icn-vw" style="top:20px;" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode${n}', ['clientCode${n}', 'matterDesc${n}'], ['client_code', 'mat_description'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </td>
                            <td class="border fw-normal"> <input type="text" class="form-control" name="mat_description${n}" id="matterDesc${n}" readonly="true" ></td> 
                            <td class="border fw-normal"> <input type="text" class="form-control" name="client_code${n}" id="clientCode${n}" readonly="true" ></td> 
                            <td class="border fw-normal"> <input type="text" class="form-control" name="description${n}" oninput="this.value = this.value.toUpperCase()" onBlur="chkNarration(this,${n})"></td> 
                            <td class="border fw-normal"> <input type="text" class="form-control" name="amount${n}" onBlur="chkAmount(this,${n})"></td> 
                            <td class="border fw-normal" align="center"><input type="button" name="addRowBtn${n}" value="+" title="Add Row" onClick="addNewRow(this,${n})"></td>  
                        </tr>`;


                        let tbody = document.getElementById("tbody");
                        let tr = tbody.insertRow(tbody.rows.length);
                        tr.classList.add('fs-14'); tr.innerHTML = text;

                        // let desc_value = eval(`document.stenographerExpenses.description${total_row}.value`);
                        // console.log(desc_value);
                        
                        eval(`document.stenographerExpenses.memo_date${n}.focus()`);
                        eval(`document.stenographerExpenses.memo_date${n}.select()`);
                        // eval(`document.stenographerExpenses.description${n}.value`) = desc_value;

                    }
                }
            }
        }      
    }
    
    document.stenographerExpenses.total_value.value = <?= $gross_amount ?>;
    if(document.stenographerExpenses.user_option.value == 'Add' || document.stenographerExpenses.user_option.value == 'Edit') {
        var total_row = (document.stenographerExpenses.arb_row_num.value)*1;
        
        document.stenographerExpenses.generateChkbox.disabled   = true;
        document.stenographerExpenses.generateChkbox.style.visibility = 'hidden';
        
        for (i=1; i <= total_row; i++)
        {
            eval("document.stenographerExpenses.passValueChk"+i+".disabled = true");
            eval("document.stenographerExpenses.passValueChk"+i+".style.visibility = 'hidden'");
        }

    }
    if(document.stenographerExpenses.user_option.value == 'Approve')
    {
        var total_row = (document.stenographerExpenses.arb_row_num.value)*1;
        var cnt = 0;	
        for (i=1; i <= total_row; i++)
        {  
            if(eval("document.stenographerExpenses.passValueChk"+i+".checked") == true) { cnt = cnt + 1; } else { cnt = cnt - 1; }
        }
        if(cnt == total_row) { document.stenographerExpenses.generateChkbox.checked = true; } else { document.stenographerExpenses.generateChkbox.checked = false; }
    }

    if(document.stenographerExpenses.user_option.value == 'Generate')
    {
        //console.log('abc');
        var total_row = (document.stenographerExpenses.arb_row_num.value)*1;
        var cnt = 0;	

        document.stenographerExpenses.generateChkbox.disabled   = true;
        document.stenographerExpenses.generateChkbox.style.visibility = 'hidden';
        for (i=1; i <= total_row; i++)
        {
            eval("document.stenographerExpenses.passValueChk"+i+".disabled = true");
            eval("document.stenographerExpenses.passValueChk"+i+".style.visibility = 'hidden'");
        }
        
    }
    calc_total() ;

    function generateValueCal() {

        var totval = 0 ;
        var totPassVal = 0;
        for (j = 1; j <= document.stenographerExpenses.arb_row_num.value; j++)
        {
            if(eval("document.stenographerExpenses.passValueChk"+j+".checked") == true)
            { 
                if(document.stenographerExpenses.user_option.value == "Generate")
                    totval = totval + eval('document.stenographerExpenses.passed_amount'+j+'.value*1') ;
                else if(document.stenographerExpenses.user_option.value == "Approve")
                    totval = totval + eval('document.stenographerExpenses.amount'+j+'.value*1') ;
            }
            totPassVal = totPassVal + eval('document.stenographerExpenses.passed_amount'+j+'.value*1') ;
        }
        
        if(document.stenographerExpenses.user_option.value == "Generate")
        {
            document.stenographerExpenses.total_value.value = totPassVal ;
            format_number(document.stenographerExpenses.total_value,2) ;
            document.stenographerExpenses.gross_amount.value = totval;
            format_number(document.stenographerExpenses.gross_amount,2) ;
        }
        else
        {
            document.stenographerExpenses.total_value.value = totval ;
            format_number(document.stenographerExpenses.total_value,2) ;
        }
    }

    function calc_tax() {
        var gross_amt                = document.stenographerExpenses.gross_amount.value*1;
        var tax_percent              = 0.00
        var tax_rate                 = 0.00;
        var tax_code                 = "";
        var tax                      = "";

        if(document.stenographerExpenses.tax_percent.value != "") 
        { 
            tax         = document.stenographerExpenses.tax_percent.value;
            var text    = tax.split("|$|");
            tax_code    = text[0]; 
            tax_percent = text[1]*1;
        }
        tax_rate                     = Math.round(gross_amt * (tax_percent/100));
        document.stenographerExpenses.tax_rate.value   = tax_percent;
        format_number(document.stenographerExpenses.tax_rate,2);
        document.stenographerExpenses.tax_code.value   = tax_code;
        document.stenographerExpenses.tax_amount.value = tax_rate;
        format_number(document.stenographerExpenses.tax_amount,2);
    }
  
    function calc_netAmount() {
        var gross_amt  = document.stenographerExpenses.gross_amount.value*1;
        var tax_amount = document.stenographerExpenses.tax_amount.value*1;
        var net_amount = 0.00;

        net_amount     = gross_amt - tax_amount;
        document.stenographerExpenses.net_amount.value = net_amount;
        format_number(document.stenographerExpenses.net_amount,2) ;
    }

    function getTaxAmount() {
        calc_tax();
        calc_netAmount();
    }


    function selectAll(fld) {

        var total_row = (document.stenographerExpenses.arb_row_num.value)*1;
        var option    = document.stenographerExpenses.option.value;
        if(option == "Generate")
        {
            if(fld.checked == true)
            {
                for (i=1; i <= total_row; i++)
                {
                    eval("document.stenographerExpenses.passValueChk"+i+".checked = true");
                }
                generateValueCal();
                    if(document.stenographerExpenses.user_option.value == 'Generate')
                {
                    calc_tax();
                    calc_netAmount();
                }
            }
            else
            {  
            for (i=1; i <= total_row; i++)
            {
                eval("document.stenographerExpenses.passValueChk"+i+".checked = false");
            }
            generateValueCal();
            if(document.stenographerExpenses.user_option.value == 'Generate')
            {
                calc_tax();
                calc_netAmount();
            }
            }
        } 
        
        if(option == "Approve")
        { 
            if(fld.checked == true)
            {
            for (i=1; i <= total_row; i++)
            {
                eval("document.stenographerExpenses.passValueChk"+i+".checked = true");
                var amt       = eval("document.stenographerExpenses.amount"+i+".value");
                var passValue = eval("document.stenographerExpenses.passed_amount"+i+".value");
                if(passValue == "")
                {
                    eval("document.stenographerExpenses.passed_amount"+i+".value ='"+amt+"'");
                }
            }
            generateValueCal();
            }
            else
            {  
            for (i=1; i <= total_row; i++)
            { 
                var passValue = eval("document.stenographerExpenses.passed_amount"+i+".value");
                var blank     = ""; 
                if(passValue != "")
                { 
                    eval("document.stenographerExpenses.passed_amount"+i+".value ='"+blank+"'"); 
                }
                eval("document.stenographerExpenses.passValueChk"+i+".checked = false");
            }
            generateValueCal();
            }
        }
    }

    function chkMemoDate_steno(fld,n) {
        // console.log('abc');
        // if(document.stenographerExpenses.user_option.value == 'Edit') {
        //     if(fld.value == "") { 
		// 		Swal.fire({ text: 'Please Enter Memo Date' }).then((result) => { setTimeout(() => {fld.focus()}, 500) });
        //         return false; }
        // }
        if(fld.value != "") { 
            make_date(fld);
            dateValid(fld,document.getElementById("sysdate"),'L',"Memo Date","Current Date")
        }
    }

    // function chkMemoNo(fld,n) {
    //     if(document.stenographerExpenses.user_option.value == 'Edit')
    //     {
    //         if(fld.value == "") {
    //             Swal.fire({ text: 'Please Enter Memo No' }).then((result) => { setTimeout(() => {fld.focus()}, 500) });
    //             return false; }
    //     }
    // }

    // function chkNarration(fld,n) {
    //     if(document.stenographerExpenses.user_option.value == 'Edit')
    //     {
    //         if(fld.value == "") { 
    //             Swal.fire({ text: 'Please Enter Narration' }).then((result) => { setTimeout(() => {fld.focus()}, 500) });
    //             return false; }
    //     }
    // }

    function chkAmount(fld,n) {
        //console.log(document.stenographerExpenses.arb_row_num.value);
        // if(document.stenographerExpenses.user_option.value == 'Edit')
        // {
        //     if(fld.value == "") { 
        //         Swal.fire({ text: 'Please Enter Amount' }).then((result) => { setTimeout(() => {fld.focus()}, 500) });
        //         return false; }
        // }
        if(fld.value != ""){ validateNumber(fld, "Amount : ",2); }
        calc_total();
    }

    function calc_total() { 
        var totval = 0 ;
	    var total_row = document.stenographerExpenses.row_counter.value;

        if(document.stenographerExpenses.user_option.value == 'Approve')
        {
            passValueCal();
        }  
        else if(document.stenographerExpenses.user_option.value == 'Generate')
        {
            generateValueCal();
            calc_tax();
            calc_netAmount();
        }
        else
        {

            for (i = 1; i <= total_row; i++) {
                voucher_ok_ind = eval("document.stenographerExpenses.voucher_ok_ind" + i + ".value");
                //console.log(voucher_ok_ind);
                if (voucher_ok_ind == 'Y') {
                    totval = totval + eval("document.stenographerExpenses.amount" + i + ".value") * 1;
                }
            }
            totval = parseFloat(totval).toFixed(2);
            document.stenographerExpenses.total_value.value = totval;
            format_number(document.stenographerExpenses.total_value, 2) ;

        //     for (i=1; i<=document.stenographerExpenses.arb_row_num.value; i++)
        //     { 
        //         totval = totval + eval('document.stenographerExpenses.amount'+i+'.value*1') ;
        //     }
        // document.stenographerExpenses.total_value.value = totval ;
        // //console.log(document.stenographerExpenses.total_value.value);
        // format_number(document.stenographerExpenses.total_value, 2) ;

        }  

    } 

    function copyValue(fld,n) {  
        if(document.stenographerExpenses.user_option.value == 'Approve') {
            var total_row = (document.stenographerExpenses.arb_row_num.value)*1;
            var cnt = 0;
            var ind = 1;	
            for (i=1; i <= total_row; i++) {
                if(eval("document.stenographerExpenses.passValueChk"+i+".checked") == true) { cnt = cnt + 1; } 
                else { ind = 0; }
            }

            var passedVal = eval("document.stenographerExpenses.passed_amount"+n+".value");
            var amount    = eval("document.stenographerExpenses.amount"+n+".value");
            //if(passedVal == "") { fld.checked = false; } else { fld.checked = true; }
            if(fld.checked == true) { eval("document.stenographerExpenses.passed_amount"+n+".value = amount"); passValueCal(); } else { eval("document.stenographerExpenses.passed_amount"+n+".value = ''"); passValueCal(); }
            if(cnt == total_row) { document.stenographerExpenses.generateChkbox.checked = true; } else { document.stenographerExpenses.generateChkbox.checked = false; }
        }

        if(document.stenographerExpenses.user_option.value == 'Generate')
        {
            // console.log('abc');
            var total_row = (document.stenographerExpenses.arb_row_num.value)*1;
            var cnt = 0;
            var ind = 1;	
            for (i=1; i <= total_row; i++)
            {
            if(eval("document.stenographerExpenses.passValueChk"+i+".checked") == true) { cnt = cnt + 1; } 
            else { ind = 0; }
            }
            if (ind == 1){ generateValueCal();  calc_tax(); calc_netAmount(); } else if(ind==0) { generateValueCal();  calc_tax(); calc_netAmount(); }
        //alert('total row : ' + total_row + ' cnt : ' + cnt);
        if(cnt == total_row) { document.stenographerExpenses.generateChkbox.checked = true; } else { document.stenographerExpenses.generateChkbox.checked = false; }
        }

    }

    function chkPassedAmount(fld,n) {
        if(document.stenographerExpenses.user_option.value == 'Approve')
        {
            if(fld.value == "") { eval("document.stenographerExpenses.passValueChk"+n+".checked = false"); passValueCal(); } 
            else { eval("document.stenographerExpenses.passValueChk"+n+".checked = true"); format_number(fld,2); passValueCal(); }
        }
    }
    
    function passValueCal() {
        var totval = 0 ;
        for (i=1; i<=document.stenographerExpenses.arb_row_num.value; i++)
        {
            if(eval('document.stenographerExpenses.passed_amount'+i+'.value') != "")
            { 
                totval = totval + eval('document.stenographerExpenses.passed_amount'+i+'.value*1') ;
            }
        }
        document.stenographerExpenses.total_value.value = totval ;
        format_number(document.stenographerExpenses.total_value,2) ;
    }

    function voucher_delRow(e, n) {
		var row = document.getElementById("row_id"+n);
		if(eval("document.stenographerExpenses.voucher_ok_ind"+n+".value=='Y'"))
		{
			$(e).parent('tr').addClass('rowSlcted');
			eval("document.stenographerExpenses.voucher_ok_ind"+n+".value='N'");
			eval("document.stenographerExpenses.voucher_ok_ind"+n+".style.background='#ff0000'");
			eval("document.stenographerExpenses.voucher_ok_ind"+n+".style.color='#ff0000'");
			row.style.background='rgb(163 200 213)';
		}
		else
		{
			$(e).parent('tr').removeClass('rowSlcted');
			eval("document.stenographerExpenses.voucher_ok_ind"+n+".value='Y'");
			eval("document.stenographerExpenses.voucher_ok_ind"+n+".style.background='#ECE8D7'");
			eval("document.stenographerExpenses.voucher_ok_ind"+n+".style.color='#ECE8D7'");
			row.style.background='#fff';

		}
		calc_total();
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
</script>
<?= $this->endSection() ?>