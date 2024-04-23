<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if (!isset($reports)) { ?> 
<main id="main" class="main">
    <?php if (session()->getFlashdata('message') !== NULL) : ?>
        <div id="alertMsg">
            <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
            <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="pagetitle col-md-12 float-start border-bottom pb-1">
    <h1> Direct Memo Follow-up Letter (Specific Address) </h1>
    </div>

    <form action="" method="post">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
            <div class="d-inline-block w-100">
                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">As On </label>
                    <input type="text" class="form-control float-start w-100 ms-0" name="ason_date"  value="<?= date('d-m-Y') ?>" readonly/>
                </div>
                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
                    <select class="form-select cstm-inpt" name="branch_code">
                    <?php foreach($data['branches'] as $branch) { ?>
                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="frms-sec-insde d-block float-start col-md-6 ps-2 mb-4">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Period</label>
                    <span class="float-start mt-2">From</span>
                    <input type="text" class="form-control float-start w-40 ms-2 set-date datepicker" id="" name="start_date" placeholder="dd-mm-yyyy" onblur="make_date(this)" />
                    <span class="float-start mt-2 ms-2">To</span>
                    <input type="text" class="form-control float-start w-40 ms-2 set-date datepicker withdate" id="" name="end_date" onblur="make_date(this)" />
                    <span class="eee"></span>
                </div>
            </div>
            <div class="col-md-2 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Client Code <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code" required/>
                <i class="fa-solid fa-binoculars icn-vw" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
            </div>
            <div class="col-md-4 float-start px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
                <input type="text" class="form-control" id="clientName" oninput="this.value = this.value.toUpperCase()"  name="client_name" readonly/>
            </div>
            <div class="col-md-2 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Initial Code</label>
                <input type="text" class="form-control" id="initialCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'initial_code', ['initialName'], ['initial_name'], 'initial_code')" size="05" maxlength="06" name="initial_code"/>
                <i class="fa-solid fa-binoculars icn-vw" onclick="showData('initial_code', '<?= $displayId['initial_help_id'] ?>', 'initialCode', ['initialName'], ['initial_name'], 'initial_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
            </div>
            <div class="col-md-4 float-start px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Initial name</label>
                <input type="text" class="form-control" id="initialName" oninput="this.value = this.value.toUpperCase()" name="initial_name" readonly/>
            </div>
            <div class="col-md-2 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Counsel Code</label>
                <input type="text" class="form-control" id="counselCode" oninput="this.value = this.value.toUpperCase()"  onchange="fetchData(this, 'associate_code', ['counselName'], ['associate_name'], 'counsel_code')" size="05" maxlength="06" name="counsel_code" />
                <i class="fa-solid fa-binoculars icn-vw" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselCode', ['counselName'], ['associate_name'], 'counsel_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
            </div>
            <div class="col-md-4 float-start px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Counsel Name</label>
                <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()"  id="counselName" name="counsel_name" readonly/>
            </div>
            
            <div class="col-md-12 float-start px-2 position-relative mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Attention  <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control readonly" oninput="this.value = this.value.toUpperCase()" size="70" maxlength="50" name="attention_name" id="attentionName" required/>
                <input type="hidden" class="form-control" oninput="this.value = this.value.toUpperCase()" size="05" maxlength="06" name="attention_code" id="attentionCode" />
                <i class="fa-solid fa-binoculars icn-vw" onclick="showData('attention_code', 'display_id=<?= $displayId['adratn_help_id'] ?>&myclient_code=@clientCode', 'attentionCode', ['attentionName', 'addressLine1', 'addressLine2', 'addressLine3', 'addressLine4', 'addressLine5'], ['attention_name', 'address_line_1', 'address_line_2', 'address_line_3', 'address_line_4', 'address_line_5'], 'attention_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
            </div>
            <div class="col-md-12 float-start px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Address</label>
                <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_1" id="addressLine1" oninput="this.value = this.value.toUpperCase()" readonly />
                <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_2" id="addressLine2" oninput="this.value = this.value.toUpperCase()" readonly />
                <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_3" id="addressLine3" oninput="this.value = this.value.toUpperCase()" readonly />
                <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_4" id="addressLine4" oninput="this.value = this.value.toUpperCase()" readonly />
                <input type="text" class="form-control mb-2" size="70" maxlength="50" name="address_line_5" id="addressLine5" oninput="this.value = this.value.toUpperCase()" readonly />
            </div>
            <div class="col-md-3 float-start px-2 mb-3">
                <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                <select class="form-select" name="output_type" tabindex="12" required>
                <option value="">--Select--</option>
                <option value="Report">View Report</option>
                <option value="Pdf" >Download PDF</option>
                <!-- <option value="Excel" >Download Excel</option> -->
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary cstmBtn mt-3">Proced</button>
        <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
    </form>
</main>
<?php } else { ?>
        <script>
			document.getElementById('sidebar').style.display = "none";
			document.getElementById('burgerMenu').style.display = "none";
		</script>
	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
			<div class="position-absolute btndv">
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
                    <a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
                <?php endif; ?>
				
			</div>

            <?php
            //----- 
            $maxline = 50;
            $lineno  = 0;
            $pageno  = 0;
            $tblamt  = 0;
            $trlamt  = 0;
            $tosamt  = 0;
            $osamt   = 0;
            $tcnamt  = 0; 
            $tclamt  = 0;
            //$index   = 0;
            $rowcnt  = 1 ;
            $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
            $report_cnt = isset($params['pendbill_cnt']) ? $params['pendbill_cnt'] : '';
            while ($rowcnt <= $report_cnt)
            {
            $pcounselcd  = $report_row['counsel_code'] ;
            $pcounselnm  = $report_row['counsel_name'] ;
            while($pcounselcd == $report_row['counsel_code'] && $rowcnt <= $report_cnt)
            {
                if ($lineno == 0 || $lineno > $maxline)
                {
                if($lineno > $maxline)
                { 
            ?>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <BR CLASS="pageEnd"> 
        <?php
                }
                $pageno = $pageno + 1 ;
        ?>
                <table class="table border-0" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="5">    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><font face="Times New Roman, Times, serif"><b><u>Sinha and Company</u></b></font></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><font face="Times New Roman, Times, serif" size="5"><b><?php echo 'Advocates';?></b></font></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><?php echo $params['branch_addr1'];?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><?php echo $params['branch_addr2'];?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><?php echo $params['branch_addr3']?></td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new">&nbsp;Ref No</td>
                                <td class="report_label_text_new">&nbsp;:&nbsp;<b><?php echo $params['letter_ref_no']?></b></td>
                                <td class="report_label_text_new" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text_new">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new">&nbsp;Date</td>
                                <td class="report_label_text_new">&nbsp;:&nbsp;<b><?php echo $params['letter_ref_dt']?></b></td>
                                <td class="report_label_text_new" align="right">&nbsp;</td>
                                <td class="report_label_text_new">&nbsp;</td>
                                </tr>
                                <tr class="">
                                    <td colspan="5" class="border-0"><hr size="1" noshade></td>
                                </tr>
                                <?php $lineno = 8 ;  ?> 
                                <?php if($pageno == 1) 
                                { 
                                ?>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;<?php echo strtoupper($params['client_name']) ;?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ;   ?>
                                <?php if($params['address_line_1'] != '') { ?> 
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;<?php echo strtoupper($params['address_line_1']);?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <?php if($params['address_line_2'] != '') { ?> 
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;<?php echo strtoupper($params['address_line_2']) ;?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <?php if($params['address_line_3'] != '') { ?> 
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;<?php echo strtoupper($params['address_line_3']) ;?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <?php if($params['address_line_4'] != '') { ?> 
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;<?php echo strtoupper($params['address_line_4']) ;?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <?php if($params['address_line_5'] != '') { ?> 
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;<?php echo strtoupper($params['address_line_5']) ;?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="5" align="center"><b><u>Kind Attn&nbsp;:&nbsp;<?php echo strtoupper($params['attn_name'])?></u></td>
                                </tr>
                                <?php if($params['designation']!='') { ?>
                                <tr>
                                <td class="report_label_text_new" colspan="5" align="center"><?php if($params['designation']!='') echo 'Designation ';?>&nbsp;:&nbsp;<?php echo $params['designation'] ;?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="5" align="center"><b><u>Re&nbsp;:&nbsp;Direct Counsel Payment</u></b></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;Dear Sir/Madam,</td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="20" class="report_label_text_new" colspan="4" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We are sending herewith original Memo<?php if($params['bill_count']>1) { echo 's'; } else { echo '';  }?> raised by Learned Counsel(s) and clerk(s) for rendering service as specified therein. </td>
                                </tr>
                                <?php $lineno = $lineno + 8 ;  ?>
                                <?php  
                                } 
                                ?> 
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; ?>
                            </table>
                        </td>    
                    </tr>
                    <tr class="fs-14">
                        <th height="18" width="18%" align="left"  class="py-3 px-2">&nbsp;Memo No/Dt</th>
                        <th height="18" width="39%" align="left"  class="py-3 px-2">&nbsp;Counsel</th>
                        <th height="18" width="15%" align="right" class="py-3 px-2">Counsel Fee (Rs.)&nbsp;</th>
                        <th height="18" width="13%" align="right" class="py-3 px-2">Clerk Fee (Rs.)&nbsp;</th>
                        <th height="18" width="15%" align="right" class="py-3 px-2">O/s Amount (Rs.)&nbsp;</th>
                    </tr>
        <?php
                    $lineno = $lineno + 1 ;
                    
                }

        $osamt = $report_row['counsel_fee'] + $report_row['clerk_fee'] ;

        //if (($report_row['counsel_fee'] - $report_row['clerk_fee']) > 0) 
        {

        ?>
                                <tr class="fs-14">
                                <td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['memo_no']?></td>
                                <td height="20" align="left" class="p-2" colspan="5">&nbsp;</td> 
                                </tr>
                                <tr class="fs-14">
                                <td height="20" align="left"  class="p-2" style="vertical-align:top"><?php if($report_row['memo_date'] != '' && $report_row['memo_date'] != '0000-00-00') { echo date_conv($report_row['memo_date']); }?></td> 
                                <td height="20" align="left"  class="p-2" style="vertical-align:top"><b><?php echo $pcounselnm?></b></td> 
        <!--                           <td height="20" align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['matter_desc']?></td> 
        -->                           <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['counsel_fee'] > 0) { echo number_format($report_row['counsel_fee'],2,'.','') ; } ?>&nbsp;</td>
                                <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['clerk_fee'] > 0) { echo number_format($report_row['clerk_fee'],2,'.','') ; } ?>&nbsp;</td>
                                <td align="right" class="p-2" style="vertical-align:top"><?php if($osamt > 0) { echo number_format($osamt,2,'.','') ; } ?>&nbsp;</td>
                                </tr>
                                <!-- <tr class="fs-14">
                                <td colspan="5"><hr size="1" noshade></td>
                                </tr> -->
        <?php 
        }    
                $lineno = $lineno + 2;
                $tblamt = $tblamt + $osamt ;
                $tcnamt = $tcnamt + $report_row['counsel_fee'] ;
                $tclamt = $tclamt + $report_row['clerk_fee'] ;
                $trlamt = $trlamt + $report_row['realamt'] ;
                
                //
                $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
			    $rowcnt = $rowcnt + 1 ;
            }
        ?>
        <?php
                $lineno = $lineno + 1;
            }  
            $tosamt = $tblamt - $trlamt ;
        ?>                   
                                <tr class="fs-14">
                                <td height="17" align="right" class="p-2">&nbsp;</td>
                                <td height="17" align="right" class="p-2"><b>Total</b>&nbsp;</td>
                                <td height="17" align="right" class="p-2"><b><?php echo number_format($tcnamt,2,'.','') ; ?></b>&nbsp;</td>
                                <td height="17" align="right" class="p-2"><b><?php echo number_format($tclamt,2,'.','') ; ?></b>&nbsp;</td>
                                <td height="17" align="right" class="p-2"><b><?php echo number_format($tosamt,2,'.','') ; ?></b>&nbsp;</td>
                                </tr>
                            </table>
                    <!--    </td>-->
                    <!--</tr>-->
                <table>
                <?php $lineno = $lineno + 1; ?>
                <?php if (($maxline-$lineno) < 16) { $pageno = $pageno + 1 ; ?> 
                </table> 
                
                
                
                
                <BR CLASS="pageEnd"> 
                <table class="table border-0" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="5">    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                               
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><font face="Times New Roman, Times, serif" size="5.5"><b><u>Sinha and Company</u></b></font></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><font face="Times New Roman, Times, serif" size="5"><b><?php echo 'Advocates';?></b></font></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><?php echo $params['branch_addr1']?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><?php echo $params['branch_addr2']?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new" colspan="4" align="center"><?php echo $params['branch_addr3']?></td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new">&nbsp;Ref No</td>
                                <td class="report_label_text_new">&nbsp;:&nbsp;<b><?php echo $params['letter_ref_no']?></b></td>
                                <td class="report_label_text_new" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text_new">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text_new">&nbsp;Date</td>
                                <td class="report_label_text_new">&nbsp;:&nbsp;<b><?php echo $params['letter_ref_dt']?></b></td>
                                <td class="report_label_text_new" align="right">&nbsp;</td>
                                <td class="report_label_text_new">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="4"><hr size="1" noshade></td>
                                </tr>
                                <tr>
                                <td colspan="4">&nbsp;</td>
                                </tr>
                                <?php $lineno = $lineno + 12 ;  ?> 
                            </table>
                        </td>    
                    </tr>
                    <?php } ?>
                    <tr>
                        <td height="20" class="report_label_text_new" colspan="4" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;You are requested to promptly settle the Memo<?php if($params['bill_count']>1) { echo 's'; } else { echo ''; }?> and issue cheque(s) in the name of Learned Counsel(s) and clerk(s) separately as per the memorandum. </td>
                    </tr>
                    <tr>
                        <td height="10" class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="20" class="report_label_text_new" colspan="4" align="justify">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please quote the Memo/Bill number and date, when sending the payment to us or enclose a photocopy of the Memo/Bill. If you directly forward the cheque(s) to the Learned Counsel(s), please send us the particulars of payment to enable us to up date our records. </td>
                    </tr>
                    <tr>
                        <td height="10" class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="20" class="report_label_text_new" colspan="4" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thanking and assuring you of our best services at all times.</td>
                    </tr>
                    <tr>
                        <td class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="20" class="report_label_text_new" colspan="4" align="right">Yours faithfully,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="20" class="report_label_text_new" colspan="4" align="right">for <span>Sinha and Company</span>&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="report_label_text_new" colspan="4" align="left">&nbsp;Enclo: as above</td>
                    </tr>
                    <tr>
                        <td class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="report_label_text_new" colspan="4" align="left">&nbsp;</td>
                    </tr>

                    <?php $lineno = $lineno + 16 ; ?>
                </table>   
    </main>
<?php } ?>
<!-- End #main -->
<?= $this->endSection() ?>