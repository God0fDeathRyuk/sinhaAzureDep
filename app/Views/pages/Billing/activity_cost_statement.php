<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<main id="main" class="main">
<?php if(!isset($actycost_qry)) { ?>
<?php if (session()->getFlashdata('message') !== NULL) : ?>
<div id="alertMsg">
    <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
    <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

    <div class="pagetitle col-md-12 float-start border-bottom pb-1">
    <h1>Activity Cost Statement </h1>
    </div>
    <form action="" method="post" id="activityCostProceed">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
                    <select class="form-select cstm-inpt" name="branch_code">
                    <?php foreach($data['branches'] as $branch) { ?>
                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
                    </select>
                </div>

                <div class="frms-sec-insde d-block float-start col-md-9 ps-2 mb-4">
                    <label class="d-inline-block w-100 mb-2 lbl-mn"> Period</label>
                    <span class="float-start mt-2">From</span>
                    <input type="text" class="form-control float-start w-48 ms-2 set-date datepicker" id="" placeholder="dd-mm-yyyy" name="start_date" onBlur="make_date(this)" value="<?= isset($reports) ? $params['start_date'] : ''?>"/>
                    <span class="float-start mt-2 ms-2">To</span>
                    <input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="end_date" onBlur="make_date(this)" value="<?= isset($reports) ? $params['end_date'] : ''?>"/>
                </div>

                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Unadj Adv?</label>
                    <select class="form-select" name="unadj_adv_ind">
                        <option value="N" <?= isset($reports) ? ($params['unadj_adv_ind'] == 'N') ? 'selected' : '' : '' ?>>No</option>
                        <option value="Y" <?= isset($reports) ? ($params['unadj_adv_ind'] == 'Y') ? 'selected' : '' : ''?>>Yes</option>
                    </select>
                </div>

                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Print Type</label>
                    <select class="form-select" name="print_type">
                        <option value="1" <?= isset($reports) ? ($params['print_type'] == '1') ? 'selected' : '' : ''?>>Srl No/Bill Date/Bill No</option>
                        <option value="2" <?= isset($reports) ? ($params['print_type'] == '2') ? 'selected' : '' : ''?>>Bill Date/Bill No</option>
                        <option value="3" <?= isset($reports) ? ($params['print_type'] == '3') ? 'selected' : '' : ''?>>Srl No</option> 
                    </select>
                </div>

                <div class="col-md-5 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Client <strong class="text-danger">*</strong></label>
                    <input type="hidden" class="form-control cstm-inpt" id="clientCode" oninput="this.value = this.value.toUpperCase()" size="05" maxlength="06" onchange="fetchData(this, ['clientName'], ['client_name'], 'client_code')" name="client_code" />
                    <input type="text" class="form-control readonly" oninput="this.value = this.value.toUpperCase()" name="client_name" id="clientName" value="<?= isset($reports) ? $params['client_name'] : ''?>" required />
                    <i title="View" class="fa-solid fa-binoculars icn-vw" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" data-toggle="modal" data-target="#lookup"></i>

                </div>
                <!-- <div class="col-md-4 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" name="client_name" id="clientName" readonly />
                </div> -->
                
                <div class="col-md-12 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Attention <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" size="70" maxlength="50" name="attention_name" id="attentionName" value="<?= isset($reports) ? $params['attention_name'] : ''?>" required/>
                    <input type="hidden" class="form-control" oninput="this.value = this.value.toUpperCase()" size="05" maxlength="06" name="attention_code" id="attentionCode" />
                    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('attention_code', 'display_id=<?= $displayId['adratn_help_id'] ?>&myclient_code=@clientCode', 'attentionCode', ['attentionName', 'addressLine1', 'addressLine2', 'addressLine3', 'addressLine4', 'addressLine5'], ['attention_name', 'address_line_1', 'address_line_2', 'address_line_3', 'address_line_4', 'address_line_5'], 'attention_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <div class="col-md-12 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Address</label>
                    <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_1" id="addressLine1" oninput="this.value = this.value.toUpperCase()" value="<?= isset($reports) ? $params['address_line_1'] : ''?>" readonly />
                    <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_2" id="addressLine2" oninput="this.value = this.value.toUpperCase()" value="<?= isset($reports) ? $params['address_line_2'] : ''?>" readonly />
                    <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_3" id="addressLine3" oninput="this.value = this.value.toUpperCase()" value="<?= isset($reports) ? $params['address_line_3'] : ''?>" readonly />
                    <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_4" id="addressLine4" oninput="this.value = this.value.toUpperCase()" value="<?= isset($reports) ? $params['address_line_4'] : ''?>" readonly />
                    <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_5" id="addressLine5" oninput="this.value = this.value.toUpperCase()" value="<?= isset($reports) ? $params['address_line_5'] : ''?>" readonly />
                </div>
        </div>
        <?php if(!isset($reports)) { ?>
             <button type="submit" class="btn btn-primary cstmBtn mt-3" onclick="formOption('/billing/activity-cost-statement/', 'proceed', 'activityCostProceed')">Proceed</button>
             <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
        <?php } ?>
    </form>
    <form action="" method="post" id="activityCost">
        <?php if (isset($reports)) { ?> 
            <div class="d-inline-block w-100 mt-4">
                        <div class="d-inline-block w-100 scrlTblMd tblscrlvtrcllrg">
                            <input type="hidden" id="bill_count" name="bill_cnt" value="<?= $params['bill_cnt']?>" >
                            <input type="hidden" id="branch_code" name="branch_code" value="<?= $params['branch_code']?>" >
                            <input type="hidden" id="client_code" name="client_code" value="<?= $params['client_code']?>" >
                            <input type="hidden" id="start_date" name="start_date" value="<?= $params['start_date']?>" >
                            <input type="hidden" id="end_date" name="end_date" value="<?= $params['end_date']?>" >
                            <input type="hidden" id="attention_code" name="attention_code" value="<?= $params['attention_code']?>" >
                            <input type="hidden" id="attention_name" name="attention_name" value="<?= $params['attention_name']?>" >
                            <input type="hidden" id="address_line_1" name="address_line_1" value="<?= $params['address_line_1']?>" >
                            <input type="hidden" id="address_line_2" name="address_line_2" value="<?= $params['address_line_2']?>" >
                            <input type="hidden" id="address_line_3" name="address_line_3" value="<?= $params['address_line_3']?>" >
                            <input type="hidden" id="address_line_4" name="address_line_4" value="<?= $params['address_line_4']?>" >
                            <input type="hidden" id="address_line_5" name="address_line_5" value="<?= $params['address_line_5']?>" >
                            <input type="hidden" id="print_type" name="print_type" value="<?= $params['print_type']?>" >
                        
                            <table class="table table-bordered tblhdClr">
                                <tbody>
                                    <tr>
                                        <th>
                                            <span class="fntSml">Bill No</span>
                                        </th>
                                        <th>
                                            <span class="fntSml">Bill Date</span>
                                        </th>
                                        <th>
                                            <span class="fntSml">Matter</span>
                                        </th>
                                        <th>
                                            <span class="fntSml">Cause</span>
                                        </th>
                                        <th>
                                            <span class="fntSml">Amount</span>
                                        </th>
                                        <th>
                                            <span class="fntSml">Prn</span>
                                        </th>
                                        
                                    </tr>
                                    <?php foreach($reports as $key => $row) { ?> 
                                    
                                    <tr>
                                        
                                        <td>
                                            <input type="text" name="bill_number<?php echo $key+1?>" value="<?php echo $row['bill_number'] ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="bill_date<?php echo $key+1?>"  onBlur="make_date(this)"  value="<?php echo date_conv($row['bill_date']) ?>" >
                                        </td>
                                        <td>
                                            <input type="text" name="matter_code<?php echo $key+1?>" value="<?php echo $row['matter_code'] ?>" readonly>
                                        </td>
                                        <td style="width:15%;">
                                            <input type="text" name="bill_cause<?php echo $key+1?>" value="<?php echo stripslashes($row['bill_cause']) ?>" readonly>
                                        </td>
                                        <td class="brkwrd" style="width:20%;">
                                            <input type="text" name="bill_amount<?php echo $key+1?>" value="<?php echo number_format($row['bill_amount'],2,'.','') ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="" id="print_ind<?php echo $key+1?>" name="print_ind<?php echo $key+1?>" value="Y" checked>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-block w-100 mt-1"> 
                            
                            <div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
                                <input type="radio" id="select_all"  name="Select" onClick="myselect('S')" checked/>
                                <label for="slctAl" class="ms-2">Select All</label>
                            </div>
                            <div class="d-block float-start mt-3 ms-2 cstmRdobtn mb-1">
                                <input type="radio" id="select_all" name="Select" onClick="myselect('D')"/>
                                <label for="deslctAl" class="ms-2">De Select All</label>
                            </div>
                            <div>	
                            <input type="hidden" name="finsub" id="finsub" value="fsub">
                            <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2" onclick="formOption('/billing/activity-cost-statement/', 'print', 'activityCost')">Print</button>
                            </div>

                        </div>			
                    </div>	
        <?php } ?>
    </form>

<?php } ?>

<?php if (isset($actycost_qry)) { ?>
    <a href="<?= base_url($data['requested_url']) ?>" class="btn btn-primary cstmBtn mt-3 float-start ms-2">Back</a>
    <?php
        $maxline = 60 ;
        $lineno  = 0 ;
        $pageno  = 0 ;
        $rowcnt     = 1 ;
        $report_row = isset($actycost_qry[$rowcnt-1]) ? $actycost_qry[$rowcnt-1] : '' ;  
        $report_cnt = $actycost_cnt ;
        while ($rowcnt <= $report_cnt)
        {
        $plevelhdr = 'Y' ;
        $plevelamt = 0 ;
        $pleveladj = 0 ;
        $plevelbal = 0 ;
        $plevelind = $report_row['level_ind'] ;
        while ($plevelind == $report_row['level_ind'] && $rowcnt <= $report_cnt)
        {
            if ($lineno == 0 || $lineno >= $maxline)
            {
            $plevelhdr = 'Y' ;
            if($lineno >= $maxline)
            { ?>
                            </table>
                        </td>
                    </tr>
                </table>
                <BR CLASS="pageEnd"> 
            <?php } $pageno = $pageno + 1 ; ?>
           <table width="750" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>    
	                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="cellheight_1" width="75">&nbsp;</td>
                                <td class="cellheight_1" width="143">&nbsp;</td>
                                <td class="cellheight_1" width="357">&nbsp;</td>
                                <td class="cellheight_1" width="121">&nbsp;</td>
                                <td class="cellheight_1" width="50">&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="cellheight_1" width="" colspan="6">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                                <tr style="line-height:2px">
                                    <td width="120">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td width="120">&nbsp;</td>
                                </tr>                                   
                                <tr style="line-height:80px">
                                    <td valign="top">
                                    <td valign="top">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tbody><tr><td align="center"><img src="<?= base_url('public/assets/img/logo.jpg') ?>" width="155" height="65" border="0"></td></tr>
                                    </tbody></table>
                                    </td>
                                    <?php if($params['x25thLogoInd'] == 'Y') { ?>
                                    <td valign="top">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr><td align="center"><img src="<?= base_url('public/assets/img/logo.jpg') ?>" width="155" height="65" border="0">&nbsp;</td></tr>
                                        </table>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b><?php echo strtoupper($params['branch_addr1']);?></b></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b><?php echo strtoupper($params['branch_addr2']);?></b></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b><?php echo $params['branch_addr3'];?></b></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr><td colspan="3"><hr size="3" noshade="noshade"></td></tr>
                            </tbody>
                        </table>
                    </td>	  	  
				</tr>
                        <tr>
		                   <td height="19">&nbsp;</td>
		                   <td height="19">&nbsp;</td>
		                   <td height="19">&nbsp;</td>
		                   <td height="19">&nbsp;</td>
		                   <td height="19">&nbsp;</td>
		                </tr>
                        <tr>
		                   <td height="" class="report_detail_bottom">&nbsp;Ref</td>
		                   <td height="" class="report_detail_bottom" style="width:35%;" valign="bottom" align="center"><span class="d-block float-start">: FA/</span>
		                   <textarea class="d-block float-start" style="width:auto; height:20px; font-family:Verdana, Arial, Helvetica, sans-serif; font-weight:bold;  font-size:11.8px;   vertical-align:bottom; color: #000000 ;  overflow:scroll; overflow:hidden;resize: none; border:none;background-color
		                   :transparent;" ><?php echo substr(date('Y-m-d'),0,4);?>/</textarea></td> 
		                   <td height="" class="report_detail_bottom" valign="bottom" align="center"><textarea style="width:150; height:20px; font-family:Verdana, Arial, Helvetica, sans-serif;  font-size:11.8px;   vertical-align:bottom; color: #000000 ;  overflow:scroll; overflow:hidden; border:none;background-color:transparent;resize: none;" ><?php echo $params['date'];?></textarea></td> 
		                   <td height="" class="report_detail_bottom">&nbsp;</td>
		                   <td height="" class="report_detail_bottom" align="right" >(<?php echo $pageno;?>)&nbsp;</td>
		                </tr>
                        <tr><td  height="15" colspan="5">&nbsp;</td></tr>
						<?php $lineno = 9 ; ?> 
                        <?php if($pageno == 1) { ?>
                        <tr><td  height="15" class="report_label_text" colspan="5">To, </td></tr>
                        <?php if ($params['client_code'] == 'C00003') { ?>
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;<?php echo 'THE CHAIRMAN-CUM-MANAGING DIRECTOR';?></td></tr>
                        <?php } ?>
                        <?php if ($params['client_code'] == 'K00068') { ?>
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;<?php echo 'THE CHIEF LAW OFFICER';?></td></tr>
						<?php } ?>
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;<?php echo $params['client_name']    ?></td></tr>
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;<?php echo $params['address_line_1'] ?></td></tr>
                        <?php if(!empty($params['address_line_2'])) { ?>              
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;<?php echo $params['address_line_2'] ;?></td></tr>
                        <?php } if(!empty($params['address_line_3'])) { ?>              
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;<?php echo $params['address_line_3'] ;?></td></tr>
                        <?php } if(!empty($params['address_line_4'])) { ?>              
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;<?php echo $params['address_line_4'] ;?></td></tr>
                        <?php } ?>              
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;<?php echo $params['address_line_5'] ;?></td></tr>
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;</td></tr>
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;</td></tr>
                        <?php if ($params['attention_code'] != '373') { ?>
                        <tr>
		                   <td  height="15" class="report_label_text">Attn</td>
		                   <td  height="15" class="report_label_text" colspan="4">&nbsp;:&nbsp;<b><?php echo $params['attention_name'];?></b></td>
		                </tr>
                        <?php } ?>
                        <tr><td  height="15" class="report_label_text" colspan="5">&nbsp;</td></tr>
                        <tr>
		                   <td  height="15" class="report_label_text">Re </td>
		                   <td  height="15" class="report_label_text" colspan="4">&nbsp;:&nbsp;<b>ACTIVITY COST STATEMENT</b></td>
		                </tr>
                        <tr><td  height="15" colspan="5">&nbsp;</td></tr>
                        <tr><td  height="15" class="report_label_text" colspan="5">Dear <?php echo $params['attn_desc'] ;?></td></tr>
                        <tr><td  height="15" colspan="5">&nbsp;</td></tr>
                        <tr><td  height="15" class="report_label_text" colspan="5">We are sending herewith our Activity Cost Statement in respect of the matter and upto the period as specified therein.</td></tr>
                        <tr><td  height="15" colspan="5">&nbsp;</td></tr>
						<?php $lineno = $lineno + 23 ; ?>
                        <?php } ?>
	                 </table>
                  </td>    
               </tr>
               <tr>
		          <td  height="15" colspan="4" class="grid_header">
	                 <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
            <?php
                }
            ?>
            <?php if($plevelhdr == 'Y') { ?>
            <tr>
                <td height="15" colspan="11" class="report_label_text"><b><?php if($plevelind == '1') { echo 'Bill(s)'; } else { echo 'Unadjusted Advance(s)' ; } ?></b></td>
            </tr>   
            <tr class="fs-14">
                <?php if($params['print_type'] != 2) { ?>
                    <th  height="15" width="02%" align="left"  class="px-2 py-2"><?php if($plevelind == '1') { echo 'Srl' ; } else { echo '' ; }?>&nbsp;</th>
                <?php } ?>
                <th  height="15" width="01%">&nbsp;</th>
                <?php if($params['print_type'] != '3') { ?>
                <th  height="15" width="14%" align="left"  class="px-2 py-2"><?php if($plevelind == '1' && $params['print_type'] == '1' || $params['print_type'] == '2') { echo 'Bill No'   ; } else if($plevelind == '1' && $params['print_type'] == '3') { echo '' ; } else { echo 'Chq No'   ; }?>&nbsp;</th>
                <?php } ?>
                <th  height="15" width="01%">&nbsp;</th>
                <?php if($params['print_type'] != '3') { ?>
                <th  height="15" width="15%" align="left"  class="px-2 py-2"><?php if($plevelind == '1' && $params['print_type'] == '1' || $params['print_type'] == '2') { echo 'Bill Date' ; } else if($plevelind == '1' && $params['print_type'] == '3') { echo '' ; } else { echo 'Chq Date' ; }?>&nbsp;</th>
                <?php } ?>
                <th  height="15" width="01%">&nbsp;</th>
                <th  height="15" width="38%" align="left"  class="px-2 py-2"><?php if($plevelind == '1') { echo 'Particulars'     ; } else { echo 'Bank'     ; }?>&nbsp;</th>
                <th  height="15" width="01%">&nbsp;</th>
                <th  height="15" width="10%" align="right" class="px-2 py-2"><?php if($plevelind == '1') { echo 'Amount'    ; } else { echo 'Amount'   ; }?>&nbsp;</th>
                <th  height="15" width="01%">&nbsp;</th>
                <th  height="15" width="10%" align="right" class="px-2 py-2"><?php if($plevelind == '1') { echo '&nbsp;'    ; } else { echo 'Adjusted' ; }?>&nbsp;</th>
                <th  height="15" width="01%">&nbsp;</th>
                <th  height="15" width="10%" align="right" class="px-2 py-2"><?php if($plevelind == '1') { echo '&nbsp;'    ; } else { echo 'Balance'  ; }?>&nbsp;</th>
            </tr> 
            <?php $plevelhdr = 'N' ; $lineno = $lineno + 2 ; ?>
            <?php } ?>
            <tr class="fs-14">
                <?php if($params['print_type'] != 2) { ?>
                    <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php if($plevelind == '1') { echo $rowcnt ; }?>&nbsp;</td> 
                <?php } ?>
                <td  height="15">&nbsp;</td>
                <?php if($params['print_type'] != '3') { ?>
                    <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php if($plevelind == '1' && $params['print_type'] == '1' || $params['print_type'] == '2') { echo $report_row['doc_no'] ; }?>&nbsp;</td> 
                <?php } ?>
                <td  height="15">&nbsp;</td>
                <?php if($params['print_type'] != '3') { ?>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php if($plevelind == '1' && $params['print_type'] == '1' || $params['print_type'] == '2') { echo date_conv($report_row['doc_date'],'-');}?>&nbsp;</td> 
                <?php } ?>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php echo substr(str_replace('|','&',$report_row['doc_narr']),0,45)?>&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top"><?php echo $report_row['doc_amount']?>&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top"><?php if($plevelind == '2' && $report_row['adj_amount']>0) { echo $report_row['adj_amount'] ; } ?>&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top"><?php if($plevelind == '2' && $report_row['bal_amount']>0) { echo $report_row['bal_amount'] ; } ?>&nbsp;</td>
            </tr>
            <?php $lineno = $lineno + 1 ; ?>
            <?php if((strlen($report_row['doc_narr']) > 45) && $params['print_type'] == 1) {?>
            <tr class="fs-14">
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php echo substr(str_replace('|','&',$report_row['doc_narr']),45,45);?></td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
            </tr>
            <?php $lineno    = $lineno + 1 ;} else if((strlen($report_row['doc_narr']) > 45) && $params['print_type'] == 2) { ?>
            <tr class="fs-14">
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php echo substr(str_replace('|','&',$report_row['doc_narr']),45,45);?></td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
            </tr>
            <?php $lineno    = $lineno + 1 ; } else if((strlen($report_row['doc_narr']) > 45) && $params['print_type'] == 3) {?>
            <tr class="fs-14">
                <td  height="15" align="left"  class="report_detail_none" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php echo substr(str_replace('|','&',$report_row['doc_narr']),45,45);?></td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
            </tr>
            <?php $lineno    = $lineno + 1 ; } ?>
            <?php if((strlen($report_row['doc_narr']) > 90) && $params['print_type'] == 1 ) {?>
            <tr class="fs-14">
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php echo substr(str_replace('|','&',$report_row['doc_narr']),90,45);?></td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
            </tr>
            <?php $lineno    = $lineno + 1 ;} else if((strlen($report_row['doc_narr']) > 90) && $params['print_type'] == 2) {?>
            <tr class="fs-14">
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php echo substr(str_replace('|','&',$report_row['doc_narr']),90,45);?></td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
            </tr>
            <?php $lineno    = $lineno + 1 ; } else if((strlen($report_row['doc_narr']) > 90) && $params['print_type'] == 3) {?>
            <tr class="fs-14">
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                <td  height="15">&nbsp;</td>
                <td  height="15" align="left"  class="p-2" style="vertical-align:top"><?php echo substr(str_replace('|','&',$report_row['doc_narr']),90,45);?></td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                <td  height="15">&nbsp;</td>
                <td  height="15" align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
            </tr>
            <?php $lineno    = $lineno + 1 ; } ?>
            <?php     
                $plevelamt = $plevelamt + $report_row['doc_amount'] ;
                $pleveladj = $pleveladj + $report_row['adj_amount'] ;
                $plevelbal = $plevelbal + $report_row['bal_amount'] ;
                $report_row = isset($actycost_qry[$rowcnt]) ? $actycost_qry[$rowcnt] : $report_row; 
                $rowcnt = $rowcnt + 1 ;
            }  
            ?>
            <tr class="fs-14">
                <td height="15" align="left"  class="p-2" style="vertical-align:bottom;background-color:#f2f1ce;" colspan="7"><b>Total</b></td> 
                <td height="15" align="left"  class="p-2" style="background-color:#f2f1ce;">&nbsp;</td>
                <td height="15" align="right" class="p-2" style="vertical-align:bottom;background-color:#f2f1ce;"><b><?php echo number_format($plevelamt,2,'.','');?></b>&nbsp;</td>
                <td height="15" align="left"  class="p-2" style="background-color:#f2f1ce;">&nbsp;</td>
                <td height="15" align="right" class="p-2" style="vertical-align:bottom;background-color:#f2f1ce;"><b><?php if($pleveladj>0) { echo number_format($pleveladj,2,'.','') ; }?></b>&nbsp;</td>
                <td height="15" align="left"  class="p-2" style="background-color:#f2f1ce;">&nbsp;</td>
                <td height="15" align="right" class="p-2" style="vertical-align:bottom;background-color:#f2f1ce;"><b><?php if($plevelbal>0) { echo number_format($plevelbal,2,'.','') ; }?></b>&nbsp;</td>
            </tr>
            <?php
                $lineno = $lineno + 1 ; 
            }
            ?>
                     </table>
                  </td>
               </tr>
           </table>
    <?php
    //echo $lineno;die;
        if ($maxline - $lineno < 18) 
        {   
            $pageno = $pageno + 1 ;
            ?>
           <BR CLASS="pageEnd"> 
           <table width="750" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                            <td>    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="cellheight_1" width="75">&nbsp;</td>
                                        <td class="cellheight_1" width="143">&nbsp;</td>
                                        <td class="cellheight_1" width="357">&nbsp;</td>
                                        <td class="cellheight_1" width="121">&nbsp;</td>
                                        <td class="cellheight_1" width="50">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="cellheight_1" width="" colspan="6">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr style="line-height:2px">
                                            <td width="120">&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td width="120">&nbsp;</td>
                                        </tr>                                   
                                        <tr style="line-height:80px">
                                            <td valign="top">
                                            <td valign="top">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                <tbody><tr><td align="center"><img src="<?= base_url('public/assets/img/logo.jpg') ?>" width="155" height="65" border="0"></td></tr>
                                            </tbody></table>
                                            </td>
                                            <?php if($params['x25thLogoInd'] == 'Y') { ?>
                                            <td valign="top">
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                <tr><td align="center"><img src="<?= base_url('public/assets/img/logo.jpg') ?>" width="155" height="65" border="0">&nbsp;</td></tr>
                                                </table>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b><?php echo strtoupper($params['branch_addr1']);?></b></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b><?php echo strtoupper($params['branch_addr2']);?></b></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="GroupDetail_band_portrait" style="font-size:12px;" align="center"><b><?php echo $params['branch_addr3'];?></b></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr><td colspan="3"><hr size="3" noshade="noshade"></td></tr>
                                    </tbody>
                                </table>
                            </td>	  	  
                        </tr>
						<tr>
						  <td height="15" class="ReportTitle_portrait" colspan="5">&nbsp;</td>
						</tr>
                        <tr>
		                   <td  height="15" class="report_label_text">&nbsp;Ref : </td>
		                   <td  height="15" class="report_detail_bottom" valign="bottom" align="center"><span class="d-block float-start">: FA</span>/<textarea   style="width:100; height:20px; font-family:Verdana, Arial, Helvetica, sans-serif; font-weight:bold;  font-size:11.8px;   vertical-align:bottom; color: #000000 ;  overflow:scroll; overflow:hidden; border:0;background-color
		                   :transparent;resize:none;" ><?php echo substr(date('Y-m-d'),0,4);?>/</textarea></td> 
		                   <td  height="15" class="report_detail_bottom" valign="bottom" align="center"><textarea   style="width:150; height:20px; font-family:Verdana, Arial, Helvetica, sans-serif;  font-size:11.8px;   vertical-align:bottom; color: #000000 ;  overflow:scroll; overflow:hidden; border:0;resize:none;background-color:transparent;" ><?php echo $params['date'];?></textarea></td> 
		                   <td  height="15" class="report_detail_bottom" align="right" >&nbsp;</td>
		                   <td  height="15" class="report_detail_bottom" align="right" >(<?php echo $pageno;?>)&nbsp;</td>
		                </tr>
                        <tr><td  height="15" colspan="5"><hr size="1" noshade></td></tr>
                        <tr><td  height="15" colspan="5">&nbsp;</td></tr>
						<?php $lineno = 13 ; ?>
	                 </table>
                  </td>    
               </tr>
           </table>
    <?php } ?>
           <table width="750" align="center" border="0" cellspacing="0" cellpadding="0">
               <tr>
		          <td>
	                 <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                         <tr>
						    <td  height="15" width="50%">&nbsp;</td>
						    <td  height="15" width="50%">&nbsp;</td>
                         </tr>
                       <tr>
                           <td  height="15" colspan="2" class="report_label_text" style="text-align:justify">
                             You are requested to go through the activities and expenses mentioned in the bill.
                             Should you require any clarification or have any query with regard thereto, kindly 
                             get in touch with our  Mr. Prabir Hazra at 8584070981 ( E-mail: prabirhazra.sinhaco@gmail.com ), within 2 (two) 
                             weeks from date, failing which, the Invoice(s) shall be treated as final. If there be any advance 
                             in respect of the aforesaid Invoice(s), please indicate the same with particulars at the time of payment 
                             to enable us to adjust the same. 
                             In the event the Invoice(s) are not received by you within next few days,you are requested to contact
                             our Mr. Prabir Hazra to enable us to do the needful.
                           </td>
                         </tr>
                         <tr><td  height="15" colspan="2">&nbsp;</td></tr>
                         <tr><td  height="15" colspan="2" class="report_label_text">We request payment of our bills within four weeks from date.</td></tr>
                         <tr><td  height="15" colspan="2">&nbsp;</td></tr>
                         <tr><td  height="15" class="report_label_text" colspan="2">Please note that our I.T. Permanent A/c No is <b><?php echo $params['branch_pan_no'];?></b></td></tr>
	                     	 
						 <?php if($params['bill_dt'] <= '2017-06-30') { ?>
                         <tr><td  height="15" class="report_label_text" colspan="2">Service Tax Regn. No is <b><?php echo $params['service_tax_no'];?></b></td></tr>
						 <?php } ?>
                         <tr><td  height="15" class="report_label_text" colspan="2">Nature of Service : <b><?php echo $params['nature_of_serv'];?></b></td></tr>
                         <tr><td  height="15" colspan="2"><b>Please draw the cheque in favour of "SINHA & CO". </b>Please do mention Bill number on the reverse of the cheque.</td></tr>
                         <tr><td  height="15" colspan="2">&nbsp;</td></tr>
                         <tr><td  height="15" colspan="2">&nbsp;</td></tr>
                         <tr>
						    <td  height="15">&nbsp;</td>
						    <td  height="15" class="report_label_text" align="center" colspan="2">Yours faithfully</td>
                         </tr>
                         <tr>
						    <td  height="15">&nbsp;</td>
						    <td  height="15" class="report_label_text" align="center" colspan="2">for SINHA AND COMPANY </td>
                         </tr>
                         <tr><td  height="15" colspan="3">&nbsp;</td></tr>
                         <tr><td  height="15" colspan="3">&nbsp;</td></tr>
                         <tr>
						    <td  height="15" class="report_label_text">Encl : as above</td>
						    <td  height="15" colspan="2">&nbsp;</td>
                         </tr>
                   </table>
                </td>
 	         </tr>
           </table> 
<?php } ?>
</main>
<script>
 function myselect(param)
	  {
        var bill_count = document.getElementById("bill_count").value;
         if (param == 'S') { var ind = true ; } else { var ind = false ; }
		 //
		 for (i=1; i<=bill_count; i++)
		 {
		  document.getElementById("print_ind"+i).checked=ind;
          // ("#print_ind"+i).prop('checked', true);
		 }
      }

</script>
<!-- End #main -->
<?= $this->endSection() ?>