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
    <h1>Bill Follow-up Letter (Specific Address) </h1>
    </div>

    <form action="" method="post">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">As On</label>
                    <input type="text" class="form-control float-start w-100 ms-0 set-date datepicker withdate" name="ason_date" readonly/>
                </div>
                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
                    <select class="form-select cstm-inpt" name="branch_code">
                    <?php foreach($data['branches'] as $branch) { ?>
                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="frms-sec-insde d-block float-start col-md-6 ps-2 mb-4">
                    <label class="d-inline-block w-100 mb-2 lbl-mn"> Period</label>
                    <span class="float-start mt-2">From</span>
                    <input type="text" class="form-control float-start w-40 ms-2 set-date datepicker" id="" placeholder="dd-mm-yyyy" name="start_date" onBlur="make_date(this)"/>
                    <span class="float-start mt-2 ms-2">To</span>
                    <input type="text" class="form-control float-start w-40 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="end_date" onBlur="make_date(this)"/>
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
                <div class="col-md-6 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Option</label>
                    <select class="form-select" name="unadjadv_ind">
				    <option value="N">W/o  Unadjusted Advance</option>
                    <option value="Y">With Unadjusted Advance</option>
                    </select>
                </div>
                <div class="col-md-12 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Attention</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" size="70" maxlength="50" name="attention_name" id="attentionName" required/>
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
		<div class="tbl-sec d-inline-block w-100 p-3 position-relative ltrTblSec">
			<div class="position-absolute btndv">
				<!--<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>-->
				<!--<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a>-->
				<?php if ($renderFlag) : ?>
                    <a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>

            <?php
                //----- 
                $maxline = 55;
                $lineno  = 0;
                $pageno  = 0;
                $tblamt  = 0;
                $trlamt  = 0;
                $tosamt  = 0;
                $osamt   = 0;
                $report_cnt = $params['bill_count'] ;
                $rowcnt     = 1 ;
                $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ;  
                while ($rowcnt <= $report_cnt)
                {
                $plevel = $report_row['level_ind'];
                while($plevel == $report_row['level_ind'] && $rowcnt <= $report_cnt)
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
                <table class="ltrTbl" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="4">    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr style="line-height:2px">
                                    <td width="">&nbsp;</td>
                                    <td width="">&nbsp;</td>
                                    <td width="">&nbsp;</td>
                                    <td width="">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td> 	  	  
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">&nbsp;Ref No</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['letter_ref_no']?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">&nbsp;Date</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['letter_ref_dt']?></b></td>
                                    <td class="report_label_text" align="right">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="4"><hr size="1" noshade></td>
                                </tr>
                                <?php $lineno = 15 ;  ?> 
                                <?php if($pageno == 1) 
                                { 
                                ?>
                                <tr>
                                    <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo strtoupper($params['client_name']) ?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ;   ?>
                                <?php if($params['address_line_1'] != '') { ?> 
                                <tr>
                                    <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $params['address_line_1']?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <?php if($params['address_line_2'] != '') { ?> 
                                <tr>
                                    <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $params['address_line_2']?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <?php if($params['address_line_3'] != '') { ?> 
                                <tr>
                                    <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $params['address_line_3']?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <?php if($params['address_line_4'] != '') { ?> 
                                <tr>
                                <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $params['address_line_4']?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <?php if($params['address_line_5'] != '') { ?> 
                                <tr>
                                <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $params['address_line_5']?></td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; } ?>
                                <tr>
                                    <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="report_label_text" colspan="1" align="left">&nbsp;Attn</td>
                                    <td class="report_label_text" colspan="3" align="left">&nbsp;:&nbsp;<?php echo strtoupper($params['attn_name'])?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <?php if($params['designation']!='') { ?>
                                <tr>
                                    <td class="report_label_text" colspan="1" align="left">&nbsp;<?php if($params['designation']!='') echo 'Designation ';?></td>
                                    <td class="report_label_text" colspan="3" align="left">&nbsp;:&nbsp;<?php echo $params['designation'] ;?></td>
                                </tr>
                                <?php } ?>
                                
                                <tr>
                                    <td class="report_label_text" colspan="1" align="left">&nbsp;Re</td>
                                    <td class="report_label_text" colspan="3" align="left">&nbsp;:&nbsp;<b><u>Bill Follow-up</u></b></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="report_label_text" colspan="4" align="left">&nbsp;Dear Sir/Madam,</td>
                                </tr>
                                <tr>
                                    <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="20" class="report_label_text" colspan="4" align="left">&nbsp;The undernoted bill<?php if($params['bill_count']>1) { echo 's are'; } else { echo ' is';  }?> outstanding as on <?php echo $params['ason_date'] ?></td>
                                </tr>
                                <?php $lineno = $lineno + 8 ;  ?>
                                <?php  
                                } 
                                ?> 
                                <tr>
                                <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
                                </tr>
                                <?php $lineno = $lineno + 1 ; ?>
                            </table>
                  </td>    
               </tr>
               <tr>
		          <td colspan="4" class="grid_header">
	                 <table  class="table table-bordered tblmn" cellspacing="0" cellpadding="0">
                        <thead>   
                            <tr>
                                <th width="" align="left"  class="py-3 px-2" style="font-size:14px;">&nbsp;Bill No/Dt</th>
                                <th width="" align="left"  class="py-3 px-2" style="font-size:14px;">&nbsp;Matter</th>
                                <th width="" align="right" class="py-3 px-2" style="font-size:14px;">Billed&nbsp;</th>
                                <th width="" align="right" class="py-3 px-2" style="font-size:14px;">Realised&nbsp;</th>
                                <th width="" align="right" class="py-3 px-2" style="font-size:14px;">O/s Amount&nbsp;</th>
                            </tr>
                        </thead>
                <?php
                            $lineno = $lineno + 1 ;
                        }

                $osamt = $report_row['billamt'] - $report_row['realamt'] ;

                if ($osamt>0) {

                ?>
                        <tr>
                           <td align="left"  class="report_detail_none" style="vertical-align:top"><?= $report_row['bill_number']?></td>
                           <td align="left"  class="report_detail_none" style="vertical-align:top" rowspan="2"><?=  $report_row['matter_desc']?></td>
                           <td align="right" class="report_detail_none" style="vertical-align:top"><?php if($report_row['billamt'] > 0) { echo number_format($report_row['billamt'],2,'.','') ; } ?>&nbsp;</td>
                           <td align="right" class="report_detail_none" style="vertical-align:top"><?php if($report_row['realamt'] > 0) { echo number_format($report_row['realamt'],2,'.','') ; } ?>&nbsp;</td>
                           <td align="right" class="report_detail_none" style="vertical-align:top"><?php if($osamt > 0) { echo number_format($osamt,2,'.','') ; } ?>&nbsp;</td>
                        </tr>
                        <tr>
                           <td height="20" align="left"  class="report_detail_none" style="vertical-align:top"><?php if($report_row['bill_date'] != '' && $report_row['bill_date'] != '0000-00-00') { echo date_conv($report_row['bill_date']); }?></td> 
                           <td height="20" align="left"  class="report_detail_none" style="vertical-align:top">&nbsp;</td>
                           <td height="20" align="right" class="report_detail_none" style="vertical-align:top">&nbsp;</td>
                           <td height="20" align="right" class="report_detail_none" style="vertical-align:top">&nbsp;</td>
                        </tr>
                <?php 
                }    
                        $lineno = $lineno + 2;
                        $tblamt = $tblamt + $report_row['billamt'] ;
                        $trlamt = $trlamt + $report_row['realamt'] ;
                        //
                        $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;
                    }
                ?>
                        <tr>
                           <td class="report_detail_none" colspan="6">&nbsp;</td>
                        </tr>
                <?php
                        $lineno = $lineno + 1;
                    }  
                    $tosamt = $tblamt - $trlamt ;
                ?>                   
                        <tr>
                           <td height="17" align="right" class="report_detail_tb">&nbsp;</td>
                           <td height="17" align="right" class="report_detail_tb"><b>Total</b>&nbsp;</td>
                           <td height="17" align="right" class="report_detail_tb"><b><?php echo number_format($tblamt,2,'.','') ; ?></b>&nbsp;</td>
                           <td height="17" align="right" class="report_detail_tb"><b><?php echo number_format($trlamt,2,'.','') ; ?></b>&nbsp;</td>
                           <td height="17" align="right" class="report_detail_tb"><b><?php echo number_format($tosamt,2,'.','') ; ?></b>&nbsp;</td>
                        </tr>
                     </table>
                  </td>
 	           </tr>
                <?php $lineno = $lineno + 1; ?>
                <?php if (($maxline-$lineno) < 16) { $pageno = $pageno + 1 ; ?> 
                </table> 
                <BR CLASS="pageEnd"> 
                <table width="750" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr style="line-height:2px">
                                <td width="08%">&nbsp;</td>
                                <td width="72%">&nbsp;</td>
                                <td width="08%">&nbsp;</td>
                                <td width="12%">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="4">
                                   
                                </td> 	  	  
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text">&nbsp;Ref No</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['letter_ref_no']?></b></td>
                                <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">&nbsp;Date</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['letter_ref_dt']?></b></td>
                                <td class="report_label_text" align="right">&nbsp;</td>
                                <td class="report_label_text">&nbsp;</td>
                                </tr>
                                <tr><td colspan="4"><hr size="1" noshade></td></tr>
                                <tr><td colspan="4">&nbsp;</td></tr>
                                <tr><td colspan="4">&nbsp;</td></tr>
                                <tr><td colspan="4">&nbsp;</td></tr>
                                <?php $lineno = $lineno + 18 ;  ?> 
                            </table>
                  </td>    
               </tr>
               <?php } ?>
               <tr>
 	              <td colspan="4" align="left"><textarea style="width:650px; height:50px; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-style: normal; line-height: normal;  font-weight: normal;  font-variant: normal; text-transform: none; overflow:scroll; text-decoration: none; vertical-align: middle ; color: #000000 ;  overflow:hidden; border:#FFFFFF;" class="report_label_text"  name="extra_text" placeholder="Limit 212 characters"  ></textarea> &nbsp;</td>
  		       </tr>
               <tr>
 	              <td height="20" class="report_label_text" colspan="4" align="left">&nbsp;We would be grateful if these bill<?php if($params['bill_count']>1) { echo 's are'; } else { echo ' is';  }?> looked into and settled at an early date.</td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text" colspan="4" align="left">&nbsp;With regards,</td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text" colspan="4" align="left">&nbsp;Yours Sincerely</td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text" colspan="4" align="left">&nbsp;for Sinha and Company</td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>
               <tr>
 	              <td colspan="4" align="left"><textarea style="width:250px; height: font-family: Verdana, Arial, Helvetica, sans-serif; overflow:scroll; overflow:hidden; font-size: 11px; font-style: normal; line-height: normal;  font-weight: normal;  font-variant: normal; text-transform: none; text-decoration: none; vertical-align: middle ; color: #000000 ; border:#FFFFFF;" class="report_label_text"  name="encl_text"></textarea> </td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text" colspan="4" align="left">&nbsp;Please note that our Income Tax Permanent A/c No is <b><?php echo $params['branch_panno'] ; ?></b></td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;Kindly provide Bill No. while making payment by Cheque/Demand Draft/NEFT/RTGS.</td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>

             <tr>
	   	          <td height="20" class="report_label_text" colspan="4" align="justify">&nbsp;
	   	            <p><i>[ If any of the bill(s), mentioned in the statement has already been paid by you, please send us the particulars of payment/receipts to enable us to reconcile our records. You can ignore the request for payment in respect of the said paid bill(s).] 
                </i></td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>

               <?php $lineno = $lineno + 16 ; ?>
             </table> 
        </div>
<?php } ?>
<!-- End #main -->
<?= $this->endSection() ?>