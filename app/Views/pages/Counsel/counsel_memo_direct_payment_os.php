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
   <h1>Counsel Memo Direct Payment (O/s)</h1>
   </div>

   <form action="" method="post">
         <div class="frms-sec d-inline-block w-100 bg-white p-3">
            <div class="frms-sec-insde d-block float-start col-md-9 ps-2 mb-4">
               <label class="d-inline-block w-100 mb-2 lbl-mn">Period</label>
               <span class="float-start mt-2">From</span>
               <input type="text" class="form-control float-start w-48 ms-2 set-date datepicker" value="<?= $fst_dt_yr?>" name="start_date" placeholder="dd-mm-yyyy" onblur="make_date(this)" />
               <span class="float-start mt-2 ms-2">To</span>
               <input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" name="end_date" onblur="make_date(this)" />
               <span class="eee"></span>
            </div>
                  <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
               <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
               <select class="form-select cstm-inpt" name="branch_code">
                     <?php foreach($data['branches'] as $branch) { ?>
                     <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                     <?php } ?>
                  </select>
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
               <label class="d-inline-block w-100 mb-2 lbl-mn">Report Seq</label>
               <select class="form-select" name="report_seqn">
               <option value="R" >Counsel-wise</option>
               <option value="C" >Client-wise</option>
               </select>
            </div>
                  <div class="col-md-3 float-start px-2 mb-3">
               <label class="d-inline-block w-100 mb-2 lbl-mn">Report Type</label>
               <select class="form-select" name="report_type">
               <option value="D" >Detail</option>
               <option value="S" >Summary</option>
               </select>
            </div>
            <div class="col-md-3 float-start px-2 mb-3">
               <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
               <select class="form-select" name="output_type" tabindex="12" required>
                    <option value="">--Select--</option>
                    <option value="Report">View Report</option>
                    <option value="Pdf" >Download PDF</option>
                    <option value="Excel" >Download Excel</option> 
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
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a> -->
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
               <a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>

         <?php if ($params['report_type'] == 'D') { 
            $maxline    = 35;
            $lineno     = 0;
            $pageno     = 0;
            $tosamt     = 0;
            $gtamount   = 0;
            $tstamt     = 0;
            $ttotosamt  = 0;
            $rowcnt     = 1;
            //$index = 0;
            $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
            $report_cnt = $params['memo_cnt'];
            while ($rowcnt <= $report_cnt)
            {
               $mosamt      = 0; 
               $mstamt      = 0;
               $tamount     = 0;
               $mtotosamt   = 0;
               $pcounselcd  = $report_row['counsel_code'] ;
               $pcounselnm  = $report_row['counsel_name'] ;
               $pcounselind = 'Y';
               while ($pcounselcd == $report_row['counsel_code'] && $rowcnt <= $report_cnt)
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
                           <td colspan="6" class=" border-0">    
                           <table class="table border-0">
                                 <tr>
                                    <td colspan="6" class="text-center border-0" align="center">
                                       <span class="d-block w-100 text-uppercase fw-bold">Sinha and Company</span>                                       
                                       <!-- <span class="d-block w-100 text-uppercase fw-bold">Cases to be appeared during a period [next date wise]</span> -->
                                    </td>
                                 </tr>
                                 <tr>
                                    <td colspan="6" class="text-center border-0" align="center">
                                       <span class="d-block w-100 text-uppercase fw-bold"><u> <?= strtoupper($params['report_desc']) ?> </u></span>
                                    </td>
                                 </tr>
                                 <tr colspan="6">
                                    <td class=" border-0">&nbsp;</td>
                                 </tr>
                              <tr>
                                 <td colspan="5" class="border-0">
                                    <p class="d-block w-100 text-uppercase">
                                       <span class="w-15 d-block float-start">Branch  </span><strong>: <?= $params['branch_name'] ?> </strong>
                                    </p>
                                    <p class="d-block w-100 text-uppercase">
                                       <span class="w-15 d-block float-start">Period  </span><strong>: <?= $params['period_desc'] ?> </strong>
                                    </p>
                                    <p class="d-block w-100 text-uppercase">
                                       <span class="w-15 d-block float-start">Counsel  </span><strong>: <?= $params['counsel_desc'] ?> </strong>
                                    </p>
                                    <p class="d-block w-100 text-uppercase">
                                       <span class="w-15 d-block float-start">Initial  </span><strong>: <?= $params['initial_desc'] ?> </strong>
                                    </p>
                                 </td>
                                 <td colspan="2" class="border-0">
                                    <p class="d-block w-100">
                                       <span>Date : <strong><?= $params['date'] ?> </strong></span>
                                    </p>
                                    <p class="d-block w-100">
                                       <span>Page : <strong><?php echo $pageno?> </strong></span>
                                    </p>
                                 </td>
                              </tr>
                           </table>
                           </td>    
                        </tr>       
                        
                        <tr class="fs-14">
                           <th class="border px-3 py-2">Memo Sl</th>
                           <th class="border px-3 py-2">Memo No/Dt</th>
                           <th class="border px-3 py-2">Client/Matter</th>
                           <th class="border px-3 py-2">Initial</th>
                           <th class="border px-3 py-2">Amount</th>
                           <th class="border px-3 py-2">Os Amount</th>
<!--                           <td width="10%" align="right" class="report_detail_rtb">&nbsp;S Tax&nbsp;</td>
                     <td width="10%" align="right" class="report_detail_rtb">&nbsp;Total Amount&nbsp;</td>
         -->
                        </tr>
                                 
         <?php
                     $lineno = 8 ;
                     $pcounselind = 'Y';
                  }
                  if($pcounselind == 'Y')
                  {
         ?>
                  <tr class="fs-14 border-0">
                     <td height="20" align="left" class="report_detail_none px-2 py-2"  style="background-color: #e2e6506e;" colspan="6"><b><?php echo $pcounselnm?></b></td> 
                  </tr>
         <?php
                  $pcounselind = 'N';
                  $lineno      = $lineno + 1; 
                  }

                  $client_qry   = $clients[$rowcnt-1];
                  $client_name  = $client_qry['client_name'];
                  //
                  $matter_qry   = $clients[$rowcnt-1];
                  $matter_desc1 = $matter_qry['matter_desc1'];
                  $matter_desc2 = $matter_qry['matter_desc2'];
                  $matter_desc = ($matter_desc1 != '') ? $matter_desc1 . ' : ' . $matter_desc2 : $matter_desc1 ; 
               //
         ?>
                                 <tr class="fs-14 border-0">
                                    <td align="left"  class="report_detail_none px-2 py-2 align-text-top"><?php echo $report_row['serial_no'] ?></td> 
                                    <td align="left"  class="report_detail_none px-2 py-2 align-text-top"><?php echo $report_row['memo_no'] ?></td> 
                                    <td align="left"  class="report_detail_none px-2 py-2 align-text-top"><b><?php echo $client_name ;?></b></td>
                                    <td align="center" class="report_detail_none px-2 py-2 align-text-top"><?php echo $report_row['initial_code'] ?>&nbsp;</td>
                                    <td align="right" class="report_detail_none px-2 py-2 align-text-top"><?php echo $report_row['amount']?>&nbsp;</td>
                                    <td align="right" class="report_detail_none px-2 py-2 align-text-top" ><?php echo $report_row['os_amount']?>&nbsp;</td>
                                 </tr>
                                 <tr class="fs-14 border-0">
                                    <td align="left"  class="report_detail_none px-2 py-2 align-text-top">&nbsp;</td> 
                                    <td align="left"  class="report_detail_none px-2 py-2 align-text-top"><?php echo date_conv($report_row['memo_date']) ?></td> 
                                    <td align="left"  class="report_detail_none px-2 py-2 align-text-top"><?php echo 'Matter: ['.$report_row['matter_code'].'] '.'-'.'Re.: '. $matter_desc.']'; ?></td>
                                    <td align="right" class="report_detail_none px-2 py-2 align-text-top" colspan="3">&nbsp;</td>
                                 </tr>
                                 <tr class="fs-14 border-0">
                                    <td align="left"  class="report_detail_none px-2 py-2 align-text-top" colspan="6">&nbsp;</td>
                                 </tr>
                           
                        
         <?php     
                  $lineno    = $lineno + 3;
                  $mosamt    = $mosamt + $report_row['os_amount'] ;
                  $tamount   = $tamount + $report_row['amount'] ;

                  $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                  $rowcnt = $rowcnt + 1 ;
               }  
         ?>                   
                                 <tr class="fs-14 border-0">
                                    <td height="20" align="right" colspan="4" style="background-color: #99cfe1;" class="p-2"><b>TOTAL</b>&nbsp;</td> 
                                    <td height="20" align="right"  style="background-color: #99cfe1;" class="p-2"><b><?php echo number_format($tamount,2,'.','') ?></b>&nbsp;</td>
                                    <td height="20" align="right"  style="background-color: #99cfe1;" class="p-2" colspan="3"><b><?php echo number_format($mosamt,2,'.','') ?></b>&nbsp;</td>
                                 </tr>
         <?php
               $lineno    = $lineno + 1;
               $tosamt    = $tosamt + $mosamt ;  
            $tstamt    = $tstamt + $mstamt ;
               $gtamount  = $gtamount + $tamount ;
               $ttotosamt = $ttotosamt + $mtotosamt ;                   
                        
            }
         ?>
                                 <tr class="fs-14 border-0">
                                    <td height="20" align="left"  class="report_detail_none">&nbsp;</td>
                                    <td height="20" align="left"  class="report_detail_none">&nbsp;</td>
                                    <td height="20" align="left"  class="report_detail_none">&nbsp;</td>
                                    <td height="20" align="right" class="report_detail_none">&nbsp;</td>
                                    <td height="20" align="right" class="report_detail_none">&nbsp;</td>
                                 </tr>
                                 <tr class="fs-14 border-0">
                                    <td height="20" align="center" colspan="4"  style="background-color: #99cfe1;" class="p-2"><b>GRAND TOTAL</b>&nbsp;</td>
                                    <td height="20" align="right" style="background-color: #99cfe1;"  class="p-2"><b><?php echo number_format($gtamount,2,'.','') ?></b>&nbsp;</td>
                                    <td height="20" align="right" style="background-color: #99cfe1;"  class="p-2"><b><?php echo number_format($tosamt,2,'.','') ?></b>&nbsp;</td>
                                 </tr>
                           </table>
                        </td>
                     </tr>
            </table> 

            <?php } 
            else if($params['report_type'] == 'S') { 
            $tos_counsel=0; $tos_clerk=0;
            $maxline = 65 ;
            $lineno  = 0 ;
            $pageno  = 0 ;
            $rowcnt  = 1 ;
            $tosamt  = 0;
            //$index = 0;
            $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ;
            //$report_row = $reports[$index]; 
            $report_cnt = $params['memo_cnt'] ;
            
            while ($rowcnt <= $report_cnt)
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
                  <table class="w-100" style="width:100%;" align="center" cellspacing="0" cellpadding="0">
                     <tr>
                        <td colspan="4">    
                        <table class="table border-0">
                              <tr>
                                 <td colspan="5" class="text-center border-0" align="center">
                                    <span class="d-block w-100 text-uppercase fw-bold">Sinha and Company</span>
                                    <!-- <span class="d-block w-100 text-uppercase fw-bold">Cases to be appeared during a period [next date wise]</span> -->
                                 </td>
                              </tr>
                              <tr>
                                 <td colspan="5" class="text-center border-0" align="center">
                                    <span class="d-block w-100 text-uppercase fw-bold text-decoration-underline"><u> <?= strtoupper($params['report_desc']) ?> </u></span>
                                 </td>
                              </tr>
                              <tr>
                                 <td class="report_label_text border-0" colspan="4">
                                    <p class="d-block w-100 text-uppercase">
                                       <span class="w-15 d-block float-start">Branch :  </span><strong> <?= $params['branch_name'] ?></strong>
                                    </p>
                                    <p class="d-block w-100 text-uppercase">
                                       <span class="w-15 d-block float-start">Counsel : </span><strong> <?php if($params['counsel_name'] == '') {echo 'ALL';} else {echo $params['counsel_name'];}?> </strong>
                                    </p>
                                    <p class="d-block w-100 text-uppercase">
                                       <span class="w-15 d-block float-start">Period : </span><strong> <?= $params['period_desc'] ?></strong>
                                    </p>
                                 </td>
                                 <td class="report_label_text float-end border-0" align="left" colspan="">
                                    <div class="rgtclmn d-block float-end w-100">
                                       <p class="d-block w-100 float-start text-uppercase">
                                          <span class="w-auto d-block float-start">Date :  </span><strong> <?= $params['date'] ?></strong>
                                       </p>
                                       <p class="d-block w-100 float-start text-uppercase">
                                          <span class="w-auto d-block float-start">Page : </span><strong> <?php echo $pageno?></strong>
                                       </p> 
                                    </div>
                                 </td>
                              </tr>
                        </table>
                        </td>    
                     </tr>
               <?php if($params['report_seqn'] == 'C')   {	?>     
               <tr class="fs-14 border-0">
                   <th width="" class="p-2 text-center"><b>Client Name</b></th>
                   <th width="" class="p-2 text-center"><b>O/s Counsel</b></th>
                   <th width="" class="p-2 text-center"><b>O/s Clerk</b></th>
                   <th width="" class="p-2 text-center"><b>O/s Total</b></th>
                </tr>
                        
            <?php } ?>

         <?php if($params['report_seqn'] == 'R')   {	?>     

                     <tr class="fs-14">
                          <th class="border px-2 py-2 text-center"><b>Counsel Name</b></th>
                          <th class="border px-2 py-2 text-center"><b>O/s Counsel</b></th>
                          <th class="border px-2 py-2 text-center"><b>O/s Clerk</b></th>
                          <th class="border px-2 py-2 text-center"><b>O/s Total</b></th>
                       </tr>
                           
               <?php } ?>

         <?php

                  $lineno = 9 ;
               }
               //----------
               $rowdesc       =  $report_row['counsel_name'] ;
               $os_counsel    =  $report_row['os_counsel'] ;
               $os_clerk      =  $report_row['os_clerk'] ;
               $rosamt        =  $report_row['os_amount'] ;
               $client_name   =  $report_row['client_name'] ;
               $tos_counsel   =  $tos_counsel + $report_row['os_counsel'] ;
               $tos_clerk     =  $tos_clerk + $report_row['os_clerk'] ;
               $tosamt        =  $tosamt + $rosamt  ; 
         ?>


         <?php if($params['report_seqn'] == 'C' && $os_counsel >0)   {	?>     

                           <tr class="fs-14 border-0">
                              <td class="p-2 border" align="left"><span>
                                 <?php echo $client_name ?>
                              </span></td>
                              <td class="p-2 border" align="right"><span><?php if($os_counsel == 0.00) echo ''; else  echo number_format($os_counsel,2,'.','');?></span></td>
                              <td class="p-2 border" align="right"><span><?php if($os_clerk == 0.00) echo ''; else  echo number_format($os_clerk,2,'.','');?></span></td>
                              <td class="p-2 border" align="right"><span><?php if($rosamt == 0.00) echo ''; else  echo number_format($rosamt,2,'.','');?></span></td>
                           </tr>
               <?php } ?>
            
         <?php if($params['report_seqn'] == 'R' && $os_counsel >0)   {	?>     
                     <tr>
                              <td class="p-2 border px-2 py-2" align="left"><span><?php echo $rowdesc ?></span></td>
                              <td class="p-2 border px-2 py-2" align="right"><span><?php if($os_counsel == 0.00) echo ''; else  echo number_format($os_counsel,2,'.','');?></span></td>
                              <td class="p-2 border px-2 py-2" align="right"><span><?php if($os_clerk == 0.00) echo ''; else  echo number_format($os_clerk,2,'.','');?></span></td>
                              <td class="p-2 border px-2 py-2" align="right"><span><?php if($rosamt == 0.00) echo ''; else  echo number_format($rosamt,2,'.','');?></span></td>
                           </tr>
               <?php } ?>
                        
                        
                        
         <?php     
               $lineno = $lineno + 1;

               $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
               $rowcnt = $rowcnt + 1 ;
            }  
         ?>                   
                           <tr class="fs-14">
                              <td class="p-2" align="center" style="background-color:#f7f6d8;"><b>TOTAL</b>&nbsp;</td>
                              <td class="p-2"  align="right" style="background-color:#f7f6d8;"><b><?php if($tos_counsel == 0.00) echo '&nbsp;'; else  echo number_format($tos_counsel,2,'.','');?></b>&nbsp;</td>
                              <td class="p-2"  align="right" style="background-color:#f7f6d8;"><b><?php if($tos_clerk == 0.00) echo '&nbsp;'; else  echo number_format($tos_clerk,2,'.','');?></b>&nbsp;</td>
                              <td class="p-2"  align="right" style="background-color:#f7f6d8;"><b><?php if($tosamt == 0.00) echo '&nbsp;'; else  echo number_format($tosamt,2,'.','');?></b>&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
            </table>

            
            
         <?php } ?>
   </main>
<?php } ?>

<!-- End #main -->
<?= $this->endSection() ?>