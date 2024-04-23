<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($ldg_qry)) && (!isset($trandtl_qry))) { ?> 
<main id="main" class="main">
	<?php if (session()->getFlashdata('message') !== NULL) : ?>
		<div id="alertMsg">
			<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
			<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>
    <div class="pagetitle">
      <h1>Daybook</h1>
    </div><!-- End Page Title -->

    <form action="" method="post" id="daybook" name="daybook" onsubmit="setValue(event)">
        <section class="section dashboard">
        <div class="row">
            <div class="col-md-12 mt-2">
                <div class="frms-sec d-inline-block w-100 bg-white p-3">
                    <div class="col-md-4 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                        <select class="form-select" name="branch_code" required >
                            <?php foreach($data['branches'] as $branch) { ?>
                            <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Financial Year <strong class="text-danger">*</strong></label>
                        <select class="form-select w-100 float-start" name="fin_year" required >
                            <?php foreach($data['finyr_qry'] as $finyr_row) { ?>
                            <option value="<?php echo $finyr_row['fin_year']?>" <?php if(session()->financialYear == $finyr_row['fin_year']) { echo 'selected' ; }?>><?php echo $finyr_row['fin_year']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="col-md-5 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                        <input type="text" class="form-control w-48 float-start" name="date_from" onBlur="make_date(this)"  value="<?= $data['curr_fyrsdt'] ?>" required>
                        <span class="w-2 float-start mx-2">---</span>
                        <input type="text" class="form-control w-48 float-start" name="date_to"  onBlur="make_date(this)"  value="<?= date('d-m-Y') ?>" required>
                    </div>
                    <div class="col-md-4 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Day Book <strong class="text-danger">*</strong></label>
                        <select class="form-select w-100 float-start" name="daybook_code" required >
                            <option value="">--Select--</option>
                            <?php foreach($data['daybook_qry'] as $daybook_row) { ?>
                            <option value="<?php echo $daybook_row['daybook_code']?>"><?php echo $daybook_row['daybook_desc'] . ' [DB '.$daybook_row['daybook_code'].']';?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type</label>
                        <select class="form-select" name="output_type" tabindex="12" required>
                            <option value="">--Select--</option>
                            <option value="Report">View Report</option>
                            <option value="Pdf" >Download PDF</option>
                            <option value="Excel" >Download Excel</option>
                        </select>
                    </div>
                    <input type="hidden" name="daybook_desc" value="">
                    <div class="d-inline-block w-100">
                        <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Proceed</button>				
                        <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Reset</button>
                    </div>
                </div>
                
            </div>
            
        </div>
        </section>
    </form>

</main>
<?php } else if(!isset($trandtl_qry) && (isset($ldg_qry))){ ?>
	<script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>

	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
			<div class="position-absolute btndv">
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
					<?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
						<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
					<?php } else { ?> 
						<button onclick="window.close()" class="text-decoration-none d-block float-start btn btn-dark">Close</button>
					<?php } } ?>
				<?php endif; ?>
			</div>
            <?php
                $maxline = 99 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $gdopbal = $params['cdopbal'] ;
                $gtdramt = 0; 
                $gtcramt = 0; 
                $rowcnt  = 1 ;
                $report_row = isset($ldg_qry[$rowcnt-1]) ? $ldg_qry[$rowcnt-1] : '' ;   
                $report_cnt = $ldg_cnt ;
                while ($rowcnt <= $report_cnt)
                {
                $dtdramt = 0; 
                $dtcramt = 0; 
                $pdocdt = substr($report_row['doc_date'],0,4).substr($report_row['doc_date'],5,2).substr($report_row['doc_date'],8,2) ;
                $opbalance_ind = 'Y';
                while ($pdocdt == (substr($report_row['doc_date'],0,4).substr($report_row['doc_date'],5,2).substr($report_row['doc_date'],8,2)) && $rowcnt <= $report_cnt)
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
                    <table width="950" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                            <td class="border-0" colspan="8">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('Sinha and Company')?></b></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y')?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Year</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['fin_year']?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo date_conv($params['date_from'],'-') . ' to ' . date_conv($params['date_to'],'-') ; ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Daybook</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['daybook_desc'] ; ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th align="left"  class="py-3 px-2">&nbsp;Doc Date</th>
                            <th align="left"  class="py-3 px-2">&nbsp;Doc#</th>
                            <th align="left"  class="py-3 px-2">&nbsp;Instr#</th>
                            <th align="left"  class="py-3 px-2">&nbsp;Instr Dt</th>
                            <th align="left"  class="py-3 px-2">&nbsp;Narration</th>
                            <th align="right" class="py-3 px-2">Receipt&nbsp;</th>
                            <th align="right" class="py-3 px-2" >Payment&nbsp;</th>
                            <!-- <th width="12%" align="right" class="py-3 px-2" >Action&nbsp;</th> -->
                        <?php if ($renderFlag) : ?>
                        <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
                        <?php endif; ?>
                        </tr>
                                    
                <?php
                            $lineno = 8 ;
                        }
                        if($opbalance_ind == 'Y')
                        {
                ?>
                        <tr class="fs-14 border-0">
                           <td align="left"  class="p-2">&nbsp;</td> 
                           <td align="left"  class="p-2">&nbsp;</td> 
                           <td align="left"  class="p-2">&nbsp;</td> 
                           <td align="left"  class="p-2">&nbsp;</td> 
                           <td align="right" class="p-2"><b>Opening Balance</b>&nbsp;</td>
                           <td align="right" class="p-2"><b><?php if($params['cdopbal'] > 0) echo number_format(abs($params['cdopbal']),2,'.','') ; ?></b>&nbsp;</td>
                           <td align="right" class="p-2"><b><?php if($params['cdopbal'] < 0) echo number_format(abs($params['cdopbal']),2,'.','') ; ?></b>&nbsp;</td>
                            
                        </tr>
                <?php
                        $opbalance_ind = 'N';
                        $lineno = $lineno + 1;
                        }
                ?>
                        <tr class="fs-14 border-0">
                           <td align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['doc_date'],'-')?></td> 
                           <td align="left"  class="p-2" style="vertical-align:top;"> <?php echo $report_row['doc_no']?> </td>
                           <td align="left"  class="p-2">&nbsp;<?php echo $report_row['instrument_no']?></td>
                           <td align="left"  class="p-2">&nbsp;<?php if($report_row['doc_date'] != '' && $report_row['doc_date'] != '0000-00-00') { echo date_conv($report_row['doc_date']) ; } ?>&nbsp;</td> 
                           <td align="left"  class="p-2">&nbsp;<?php echo strtoupper($report_row['narration'])?></td>
                           <td align="right" class="p-2"><?php if($report_row['dr_cr_ind']=='D' && $report_row['net_amount']>0) echo $report_row['net_amount'] ?>&nbsp;</td>
                           <td align="right" class="p-2" ><?php if($report_row['dr_cr_ind']=='C' && $report_row['net_amount']>0) echo $report_row['net_amount'] ?>&nbsp;</td>
                           <?php if ($renderFlag) : ?>
						   <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
						   <td height="20" align="left" class="p-2" >

								<form action="" method="post" target="_blank" name="actionForm<?= $rowcnt ?>">
				 					<input type="hidden" name="doc_type" value="<?= $report_row['doc_type'] ?>">
									<input type="hidden" name="serial_no" value="<?= $report_row['serial_no'] ?>">
									<input type="hidden" name="output_type" value="">
									<button type="button" class="me-1 border-0 p-0" title="View Report" onclick="setOutputType('innerReport', <?= $rowcnt ?>)">
										<i class="fa-solid fa-eye edit"></i>
						 			</button>
									<button type="button" class="me-1 border-0 p-0" class="me-1" title="Download Excel" onclick="setOutputType('innerExcel', <?= $rowcnt ?>)">
										<i class="fa-solid fa-file-excel edit"></i>
						 			</button>
									<button type="button" class="me-1 border-0 p-0" class="me-1" title="Download PDF" onclick="setOutputType('innerPdf', <?= $rowcnt ?>)">
										<i class="fa-solid fa-file-pdf edit"></i>
									</button>									
								</form>
								<script>
									function setOutputType(type, no) {
										document['actionForm'+no].output_type.value = type;
										document['actionForm'+no].submit();
									}
								</script>
							</td>
							<?php } } ?>
							<?php endif; ?>
                        </tr>
                <?php     
                        $lineno = $lineno + 1;
                        if($report_row['dr_cr_ind'] == 'D')
                        $dtdramt = $dtdramt + $report_row['net_amount'] ;                   
                        else
                        $dtcramt = $dtcramt + $report_row['net_amount'] ;                   
                        //
                        $report_row = ($rowcnt < $report_cnt) ? $ldg_qry[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;
                    }  
                    //-------- Day Break
                    $cdclbal = $params['cdopbal'] + $dtdramt - $dtcramt ;
                    $gtdramt = $gtdramt + $dtdramt ;                   
                    $gtcramt = $gtcramt + $dtcramt ;
                    $params['cdopbal'] = $cdclbal ;
                    
                ?>                   
                        <tr class="fs-14 border-0">
                           <td align="left"  class="p-2">&nbsp;</td>
                           <td align="left"  class="p-2">&nbsp;</td>
                           <td align="left"  class="p-2">&nbsp;</td>
                           <td align="left"  class="p-2">&nbsp;</td>
                           <td align="right" class="p-2"><b>Total</b>&nbsp;</td>
                           <td align="right" class="p-2"><b><?php if($dtdramt > 0) echo number_format($dtdramt,2,'.',''); ?></b>&nbsp;</td>
                           <td align="right" class="p-2"><b><?php if($dtcramt > 0) echo number_format($dtcramt,2,'.',''); ?></b>&nbsp;</td>
                           
                        </tr>
                        <tr>
                           <td align="left"  class="p-2 border-0" style="background-color: #fdfcc6;">&nbsp;</td>
                           <td align="left"  class="p-2 border-0" style="background-color: #fdfcc6;">&nbsp;</td>
                           <td align="left"  class="p-2 border-0" style="background-color: #fdfcc6;">&nbsp;</td>
                           <td align="left"  class="p-2 border-0" style="background-color: #fdfcc6;">&nbsp;</td>
                           <td align="right" class="p-2 border-0" style="background-color: #fdfcc6;"><b>Closing Balance</b>&nbsp;</td>
                           <td align="right" class="p-2 border-0" style="background-color: #fdfcc6;"><b><?php if($cdclbal > 0) echo number_format($cdclbal,2,'.',''); ?></b>&nbsp;</td>
                           <td align="right" class="p-2 border-0" style="background-color: #fdfcc6;"><b><?php if($cdclbal < 0) echo number_format(abs($cdclbal),2,'.',''); ?></b>&nbsp;</td>
                           
                        </tr>
                <?php
                    $lineno = $lineno + 2;
                    }
                    $gdclbal = $gdopbal + $gtdramt - $gtcramt ;
                ?>
                        <tr>
                           <td align="left"  class="p-2" colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                           <td align="center" class="p-2" colspan="7" style="background-color: #bee9f7;"><font size="2"><b>*** PERIOD CONTROL ***</b></font>&nbsp;</td>
                        </tr>
                        <tr>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="right" class="p-2"style="background-color: #bee9f7;"><b>Period Opening Balance</b>&nbsp;</td>
                           <td height="20" align="right" class="p-2"style="background-color: #bee9f7;"><b><?php if($gdopbal > 0) echo number_format(abs($gdopbal),2,'.',''); ?></b>&nbsp;</td>
                           <td height="20" align="right" class="p-2"style="background-color: #bee9f7;"><b><?php if($gdopbal < 0) echo number_format(abs($gdopbal),2,'.',''); ?></b>&nbsp;</td>
                        </tr>
                        <tr>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="right" class="p-2"style="background-color: #bee9f7;"><b>Period Total</b>&nbsp;</td>
                           <td height="20" align="right" class="p-2"style="background-color: #bee9f7;"><b><?php if($gtdramt > 0) echo number_format(abs($gtdramt),2,'.',''); ?></b>&nbsp;</td>
                           <td height="20" align="right" class="p-2"style="background-color: #bee9f7;"><b><?php if($gtcramt > 0) echo number_format(abs($gtcramt),2,'.',''); ?></b>&nbsp;</td>
                        </tr>
                        <tr>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="right" class="p-2"style="background-color: #bee9f7;"><b>Period Closing Balance</b>&nbsp;</td>
                           <td height="20" align="right" class="p-2"style="background-color: #bee9f7;"><b><?php if($gdclbal > 0) echo number_format(abs($gdclbal),2,'.',''); ?></b>&nbsp;</td>
                           <td height="20" align="right" class="p-2"style="background-color: #bee9f7;"><b><?php if($gdclbal < 0) echo number_format(abs($gdclbal),2,'.',''); ?></b>&nbsp;</td>
                        </tr>
                   </table>
                </td>
 	         </tr>
        </table> 
    </main>
<?php } else if(isset($trandtl_qry)) { ?>
    <script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>

	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
        <div class="pagetitle">
        <h1>Voucher [View]</h1>
        </div><!-- End Page Title -->

        <section class="section dashboard">
        <div class="row">
            <div class="col-md-12 mt-2">
                <div class="frms-sec d-inline-block w-100 bg-white p-3">
                    <div class="d-inline-block w-100 mt-2">
                        <table class="table table-bordered tblePdngsml">
                            <tr>
                                <td class="bgBlue">
                                    <span>Serial No</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['ref_doc_serial_no']?></b></span>
                                </td>
                                <td class="bgBlue">
                                    <span>Voucher</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['doc_type']?> / <?php echo $tranhdr_row['doc_no']?> / <?php echo date_conv($tranhdr_row['doc_date'],'-')?> <?php echo '/Paid By'.' - '. $tranhdr_row['paid_by'];?> </b></span>
                                </td>
                                <?php if(session()->userId == 'abhijit' ) { ?>
                                    <td><b> <?php echo 'Prepared On'.' - '. date_conv($vchrhdr_row['prepared_on'],'-')?> <?php echo '/Prepared By'.' - '. $vchrhdr_row['prepared_by'];?> </b></font> </td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td class="bgBlue">
                                    <span>Fin Year</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['fin_year']?></b></span>
                                </td>
                                <td class="bgBlue">
                                    <span>Payee</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['payee_payer_name']?>&nbsp;&nbsp;<?php if ($tranhdr_row['payee_payer_name'] != '') {?>[&nbsp;<?php echo $tranhdr_row['payee_payer_type']?>&nbsp;]<?php }?></b></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="bgBlue">
                                    <span>Branch</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['branch_name']?></b></span>
                                </td>
                                <td class="bgBlue">
                                    <span><?php if ($tranhdr_row['daybook_code'] != '10') {echo 'Instrument';} else {echo 'Daybook Code';}?></span>
                                </td>
                                <td>
                                    <span><b><?php if ($tranhdr_row['daybook_code'] != '10') {echo $tranhdr_row['instrument_no'];}?> &nbsp; <?php if ($tranhdr_row['daybook_code'] != '10' && $tranhdr_row['daybook_code'] != '40') {echo'Date:- '. date_conv($tranhdr_row['instrument_dt'],'-');}?> &nbsp; <?php if ($tranhdr_row['daybook_code'] != '10' && $tranhdr_row['bank_name'] != '' ) {echo'Bank - '. $tranhdr_row['bank_name'];}?>  <?php if ($tranhdr_row['daybook_code'] == '10') {echo $tranhdr_row['daybook_code'];}?></b></span>
                                </td>
                                <td class="bgBlue">
                                    <span><?php if ($tranhdr_row['daybook_code'] == '10') {echo '';} else {echo 'Daybook Code';}?></span>
                                </td>
                                <td>
                                    <span><b><?php if ($tranhdr_row['daybook_code'] != '10') {echo $tranhdr_row['daybook_code'];} else {echo '';}?> </b></span>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered tblePdngsml">
                            <tbody>
                                <tr class="fs-14">
                                    <th>Main</th>
                                    <th>Sub</th>
                                    <th>Matter</th>
                                    <th>Client</th>
                                    <th>Bill No</th>
                                    <th>Purpose</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Credit</th>
                                </tr>
                                <?php $tdtotal=0; $tctotal=0; foreach($trandtl_qry as $trandtl_row) { ?>
                                    <tr>							
                                        <td class="">
                                            <span><?php echo $trandtl_row['main_ac_code']?></span>
                                        </td>
                                        <td class="">
                                            <span><?php echo $trandtl_row['sub_ac_code'] ?></span>
                                        </td>
                                        <td class=""><span><?php echo $trandtl_row['matter_code'] ?></span></td>
                                        <td class="">
                                            <span><?php echo $trandtl_row['client_code'] ?> </span>
                                        </td>
                                        <td class="">
                                            <span><?php echo $trandtl_row['bill_no']?></span>
                                        </td>
                                        <td class="w-350">
                                            <span><?php echo $trandtl_row['narration']?></span>
                                        </td>
                                        <td class="wd100 text-end">
                                            <span><?php if($trandtl_row['dr_cr_ind'] == 'D') {echo $trandtl_row['gross_amount'];} else { echo '&nbsp;'; }?></span>
                                        </td>
                                        <td class="wd100 text-end">
                                            <span><?php if($trandtl_row['dr_cr_ind'] == 'C') {echo $trandtl_row['gross_amount'];} else { echo '&nbsp;'; }?></span>
                                        </td>
                                    </tr>
                                <?php if($trandtl_row['dr_cr_ind'] == 'D') { $tdtotal = $tdtotal + $trandtl_row['gross_amount'] ; } else { $tctotal = $tctotal + $trandtl_row['gross_amount'] ; }  } ?> 
                                <tr>							
                                    
                                    <td class="text-end bgBlue" colspan="6">
                                        <span>Total</span>
                                    </td>
                                    <td class="wd100 bgBlue text-end">
                                        <span><b><?php if($tdtotal != 0) {echo number_format(abs($tdtotal),2,'.','') ;} else {echo '&nbsp;';} ?></b></span>
                                    </td>
                                    <td class="wd100 bgBlue text-end">
                                        <span><b><?php if($tctotal != 0) {echo number_format(abs($tctotal),2,'.','') ;} else {echo '&nbsp;';} ?></b></span>
                                    </td>
                                </tr>
                            </tbody>
                            <?php if($tdtotal + $tctotal == 0) { ?> 
                            <tr>
                                <td> <span> The Tab will Close Automatically in <b id="backTimer">05 Seconds</b>  !!</span> </td>
                            </tr> <script> 
                            let counter=5;
                            function countdown(counter) {
                                if(counter>0) {
                                    counter--; setTimeout(function(){countdown(counter)},1000);
                                    document.getElementById('backTimer').innerText = '0' + counter + ' Seconds';
                                }
                            } countdown(counter);
                            setTimeout(() => { window.close(); }, 1000*counter); </script> <?php } ?>
                        </table>
                    </div>
                    <?php if ($renderFlag) : ?>
                    <div class="frms-sec-insde d-block float-start col-md-12">
                        <button onclick="window.close()" class="text-decoration-none d-block float-start btn btn-dark">Close</button>
                    </div>
				    <?php endif; ?>
                </div>
                
            </div>
        </div>
        </section>

  </main><!-- End #main -->
<?php } ?>
<script>
    function setValue(e) {
            e.preventDefault();
            console.log(document.daybook);
            var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

            if (document.daybook.date_from.value.substring(6,10)+document.daybook.date_from.value.substring(3,5)+document.daybook.date_from.value.substring(0,2) > today_date) {
                Swal.fire({ text: 'Date From must be less than equal to Today !!!' }).then((result) => { setTimeout(() => {document.daybook.date_from.focus()}, 500) });
                return false;
            }
            else if (document.daybook.date_to.value.substring(6,10)+document.daybook.date_to.value.substring(3,5)+document.daybook.date_to.value.substring(0,2) > today_date) {
                Swal.fire({ text: 'Date To must be less than or equal to Today !!!' }).then((result) => { setTimeout(() => {document.daybook.date_to.focus()}, 500) });
                return false;
            }
            else if (document.daybook.date_from.value.substring(6,10)+document.daybook.date_from.value.substring(3,5)+document.daybook.date_from.value.substring(0,2)>document.daybook.date_to.value.substring(6,10)+document.daybook.date_to.value.substring(3,5)+document.daybook.date_to.value.substring(0,2)) {
                Swal.fire({ text: 'Date To must be greater than Date From' }).then((result) => { setTimeout(() => {document.daybook.date_to.focus()}, 500) });
                return false;
            }
            else {
                var optn_indx = document.daybook.daybook_code.options.selectedIndex;
                var dbdesc    = document.daybook.daybook_code.options[optn_indx].text ; 
            }
            document.daybook.daybook_desc.value = dbdesc ;
            document.daybook.submit();
        }
</script>
<?= $this->endSection() ?>