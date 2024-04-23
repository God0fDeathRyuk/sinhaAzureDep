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
    <h1>Bill Follow-up Letter (Billing Address) </h1>
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
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Billing Period</label>
                    <span class="float-start mt-2">From</span>
                    <input type="text" class="form-control float-start w-40 ms-2 set-date datepicker" id="" placeholder="dd-mm-yyyy" name="start_date" onBlur="make_date(this)" />
                    <span class="float-start mt-2 ms-2">To</span>
                    <input type="text" class="form-control float-start w-40 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="end_date" onBlur="make_date(this)"/>
                </div>
                <div class="col-md-2 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code" />
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
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
                    <a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>

        <?php 
                $maxline = 55;
                //$index   = 0;
                $rowcnt     = 1 ;
                $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                // echo'<pre>';print_r($report_row);die; 
                
                $report_cnt = count($c);
                $report_cnt = $params['pendbill_cnt'];
               // echo count($c); echo '=='; echo $report_cnt;die;
                while ($rowcnt <= $report_cnt)
                {
                $lineno  = 0;
                $pageno  = 0;
                $tblamt  = 0;
                $trlamt  = 0;
                $tosamt  = 0;
                $pintlcd = $report_row['initial_code'];
                $pclntcd = $report_row['client_code'];
                $pclntnm = $report_row['client_name'];
                $paddrcd = $report_row['address_code'];
                $paddrl1 = $report_row['address_line_1'];
                $paddrl2 = $report_row['address_line_2'];
                $paddrl3 = $report_row['address_line_3'];
                $paddrl4 = $report_row['address_line_4'];
                $paddrct = $report_row['city'];
                $paddrpn = $report_row['pin_code'];
                if ($paddrct != '') 
                {
                    if($paddrpn != '') { $paddrl5 = $paddrct.' - '.$paddrpn ; } else { $paddrl5 = $paddrct ; }
                }
                else
                {
                    if($paddrpn != '') { $paddrl5 = 'PIN - '.$paddrpn ; } else { $paddrl5 = '' ; }
                }
            $pattncd = $report_row['attention_code'];
            $pattnsx = $report_row['sex'];   if($pattnsx == 'F') {$pattnind = 'Ms ' ; $pattnxx = 'Madam,' ; } else  {$pattnind = 'Mr ' ; $pattnxx = 'Sir,' ; }
            $pattnnm = $pattnind . $report_row['attention_name'] ;
            $pbilcnt = $params['pendbill_cnt'];
            // echo $pbilcnt; die; //$report_row[bill_count] ;
            $letter_ref_no  = strtoupper($pintlcd).':'.strtoupper($pclntcd).':'.$paddrcd.':'.$pattncd ;
            $letter_ref_dt  = date('d-m-Y');
            //
            while($pattncd == $report_row['attention_code'] && $rowcnt <= $report_cnt)
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
                                <td colspan="4" class="text-center py-0" align="center"><img class="imlogoLtrpg" src="<?= base_url('public/assets/img/logo.jpg') ?>" alt="SinhaCo"/></td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr1']?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr2']?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr3']?></td>
                            </tr>
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text">&nbsp;Ref No</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $letter_ref_no?></b></td>
                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text">&nbsp;Date</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $letter_ref_dt?></b></td>
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
                            <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo strtoupper($pclntnm) ?></td>
                            </tr>
                            <?php $lineno = $lineno + 1 ;   ?>
                            <?php if($paddrl1 != '') { ?> 
                            <tr>
                            <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $paddrl1?></td>
                            </tr>
                            <?php $lineno = $lineno + 1 ; } ?>
                            <?php if($paddrl2 != '') { ?> 
                            <tr>
                            <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $paddrl2?></td>
                            </tr>
                            <?php $lineno = $lineno + 1 ; } ?>
                            <?php if($paddrl3 != '') { ?> 
                            <tr>
                            <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $paddrl3?></td>
                            </tr>
                            <?php $lineno = $lineno + 1 ; } ?>
                            <?php if($paddrl4 != '') { ?> 
                            <tr>
                            <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $paddrl4?></td>
                            </tr>
                            <?php $lineno = $lineno + 1 ; } ?>
                            <?php if($paddrl5 != '') { ?> 
                            <tr>
                            <td class="report_label_text" colspan="4" align="left">&nbsp;<?php echo $paddrl5?></td>
                            </tr>
                            <?php $lineno = $lineno + 1 ; } ?>
                            <tr>
                            <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text ltrtbltd" colspan="1" align="left">&nbsp;Attn</td>
                            <td class="report_label_text ltrtbltd" colspan="3" align="left">&nbsp;:&nbsp;<?php echo strtoupper($pattnnm)?></td>
                            </tr>
                            
                            <tr>
                            <td class="report_label_text ltrtbltd" colspan="1" align="left">&nbsp;Re</td>
                            <td class="report_label_text ltrtbltd" colspan="3" align="left">&nbsp;:&nbsp;<b><u>Bill Follow-up</u></b></td>
                            </tr>
                            <tr>
                            <td class="report_label_text ptop15" colspan="4" align="left">&nbsp;Dear <?php echo $pattnxx?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text ptop15" colspan="4" align="left">&nbsp;The undernoted bill(s) raised bearing initials <?php echo strtoupper($pintlcd)?> <?php if($pbilcnt>1) { echo ' were'; } else { echo ' was';  }?> outstanding as on <?php echo $params['ason_date'] ?></td>
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
               <tr class="fs-14">
                   <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Bill No</th>
                   <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Bill Dt</th>
                   <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Cause</th>
                   <th height="18" width="" align="right" class="py-3 px-2">Bill Amt&nbsp;</th>
                </tr>
               
            <?php
                        $lineno = $lineno + 1 ;
                    }
            ?>
                        <tr>
                           <td height="20" align="left"  class="p-2"    style="vertical-align:top">&nbsp;<?php echo $report_row['bill_number']?></td>
                           <td height="20" align="left"  class="p-2" style="vertical-align:top">&nbsp;<?php if($report_row['bill_date'] != '' && $report_row['bill_date'] != '0000-00-00') { echo date_conv($report_row['bill_date']); }?></td> 
                           <td height="20" align="left"  class="p-2" style="vertical-align:top">&nbsp;<?php echo $report_row['bill_cause']?></td>
                           <td height="20" align="right" class="p-2" style="vertical-align:top"><?php if($report_row['billamount'] > 0) { echo number_format($report_row['billamount'],2,'.','') ; } ?>&nbsp;</td>
                        </tr>
            <?php     
                    $lineno = $lineno + 1;
                    $tblamt = $tblamt + $report_row['billamount'] ;
                    $trlamt = $trlamt + $report_row['realamount'] ;
                    //
                    
                    $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                    $rowcnt = $rowcnt + 1 ;
                }
                $tosamt = $tblamt - $trlamt ;
            ?>
                        <!-- <tr>
                           <td class="report_detail_lr"    style="vertical-align:bottom"><hr size="1" noshade></td>
                           <td class="report_detail_right" style="vertical-align:bottom"><hr size="1" noshade></td>
                           <td class="report_detail_right" style="vertical-align:bottom"><hr size="1" noshade></td>
                           <td class="report_detail_right" style="vertical-align:bottom"><hr size="1" noshade></td>
                        </tr> -->
                        <tr>
                           <td height="17" align="right" class="p-2">&nbsp;</td>
                           <td height="17" align="right" class="p-2">&nbsp;</td>
                           <td height="17" align="right" class="p-2"><b>Total</b>&nbsp;</td>
                           <td height="17" align="right" class="p-2"><b><?php echo number_format($tblamt,2,'.','') ; ?></b>&nbsp;</td>
                        </tr>
                     </table>
            <table class="ltrTbl" cellspacing="0" cellpadding="0">
            <?php $lineno = $lineno + 2; ?>
            <?php if (($maxline-$lineno) < 16) { $pageno = $pageno + 1 ; ?> 
            </table>
            <BR CLASS="pageEnd"> 
            <table width="750" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>    
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                            <td width="">&nbsp;</td>
                            <td width="">&nbsp;</td>
                            <td width="">&nbsp;</td>
                            <td width="">&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><font size="+2"><b>Sinha and Companyssss</b></font></td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr1']?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr2']?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><?php echo $params['branch_addr3']?></td>
                            </tr>
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text">&nbsp;Ref No</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $letter_ref_no?></b></td>
                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text">&nbsp;Date</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $letter_ref_dt?></b></td>
                            <td class="report_label_text" align="right">&nbsp;</td>
                            </tr>
                            
                            <?php $lineno = $lineno + 12 ;  ?> 
                        </table>
                    </td>    
                </tr>
                <?php } ?>
               <tr>
 	              <td class="report_label_text" colspan="4" align="left">&nbsp;We would be grateful if these bill<?php if($pbilcnt>1) { echo 's are'; } else { echo ' is';  }?> looked into and settled at an early date.</td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text ptop25" colspan="4" align="left">&nbsp;With regards,</td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text ptop15" colspan="4" align="left">&nbsp;Yours Sincerely</td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text" colspan="4" align="left">&nbsp;for Sinha and Company</td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text ptop25" colspan="4" align="left">&nbsp;Please note that our Income Tax Permanent A/c No is <b><?php echo $params['branch_panno'] ; ?></b></td>
  		       </tr>
               <tr>
	   	          <td class="report_label_text" colspan="4" align="left">&nbsp;</td>
  		       </tr>
               <tr>
	   	          <td height="20" class="report_label_text" colspan="4" align="left">&nbsp;<i>[ If any of the bills mentioned in the statement has already been paid by you, you can ignore the request for payment in respect of the said paid bill and send us the particulars of payment/receipts to enable us to reconcile our records ]</i></td>
  		       </tr>
               <?php $lineno = $lineno + 16 ; ?>
            </table> 
            <p class="tblbrkend">&nbsp;</p>
            <BR CLASS="pageEnd">  
        <?php
            }  
        ?>                   

        </div>
<?php } ?>
<!-- End #main -->
<?= $this->endSection() ?>