<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if (!isset($ldg_qry)) { ?> 
 
<main id="main" class="main">
	<?php if (session()->getFlashdata('message') !== NULL) : ?>
		<div id="alertMsg">
			<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
			<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="pagetitle col-md-12 float-start border-bottom pb-1">
		<h1> List of Other Expenses </h1>
	</div>

	<form action="" method="post">
		<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
		<div class="frms-sec d-inline-block w-100 bg-white p-3">
			<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
				<select class="form-select cstm-inpt" name="branch_code">
				<?php foreach($data['branches'] as $branch) { ?>
				<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
				<?php } ?>
				</select>
			</div>
			<div class="frms-sec-insde d-block float-start col-md-8 ps-2 mb-4">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Period</label>
				<span class="float-start mt-2">From</span>
				<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker" value="<?= $data['curr_fyrsdt'] ?>" name="date_from" placeholder="dd-mm-yyyy" onblur="make_date(this)">
				<span class="float-start mt-2 ms-2">To</span>
				<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker" value="<?= date('d-m-Y') ?>" name="date_to" onblur="make_date(this)" >
				<span class="eee"></span>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Expense Type <strong class="text-danger">*</strong></label>
				<select class="form-select" name="expn_type" id="expenseType" onchange="cleanData(this, 'payeeCode', '%&_', 'payeeCodeLookup')" required>
					<option value="">--Select--</option>
					<option value="CM">Court Expense</option>
					<option value="PE">Photocopy Expense</option>
					<option value="CC">Courier Expense</option>
					<option value="AR">Arbitrator Expense</option>
					<option value="ST">Stenographer Expense</option>
				</select>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Payee Type <strong class="text-danger">*</strong></label>
				<select class="form-select" name="payee_type" id="payeeType" onchange="cleanData(this, 'payeeCode', '', 'payeeCodeLookup')" required>
					<option value="%">All</option>
					<option value="E">Employee</option>
					<option value="S">Supplier</option>
					<option value="A">Arbitrator</option>
					<option value="S">Stenographer</option>
				</select>
			</div>
			<div class="col-md-4 float-start px-2 mb-3 position-relative">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Payee Code</label>
				<input type="text" class="form-control" name="payee_code" id="payeeCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeeName'], ['payee_payer_name'], 'other_type', 'ref_doc_type=@expenseType&payee_type=@payeeType')"/>
				<i class="fa-solid fa-binoculars icn-vw d-none" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['payee_help_id'] ?>&ref_doc_type=@expenseType&payee_type=@payeeType', 'payeeCode', ['payeeName'], ['payee_payer_name'], 'other_type')" title="View" data-toggle="modal" data-target="#lookup"></i>
			</div>
			<div class="col-md-8 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Payee Name</label>
				<input type="text" class="form-control" name="payee_name" id="payeeName" readonly/>
			</div>
			<div class="col-md-4 float-start px-2 mb-3">
				<label class="d-inline-block w-100 mb-2 lbl-mn">Output Type</label>
				<select class="form-select" name="output_type" tabindex="12" required>
					<option value="">--Select--</option>
					<option value="Report">View Report</option>
					<option value="Pdf" >Download PDF</option>
					<option value="Excel" >Download Excel</option>
				</select>
			</div>
		</div>
			<button type="submit" class="btn btn-primary cstmBtn mt-3">Proceed</button>
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
					<?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
						<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
					<?php } else { ?> 
						<button onclick="window.close()" class="text-decoration-none d-block float-start btn btn-dark">Close</button>
					<?php } } ?>
				<?php endif; ?>
			</div>

			<?php
				//----- 
				$maxline = 55 ;
    $lineno  = 0 ;
    $pageno  = 0 ;
    $tgramt  = 0; 
    $ttxamt  = 0; 
    $tntamt  = 0; 
    $rowcnt     = 1 ;
    // $report_row = mysql_fetch_array($ldg_qry); 
	$report_row = isset($ldg_qry[$rowcnt-1]) ? $ldg_qry[$rowcnt-1] : '' ;  
	$report_cnt = $ldg_cnt ;
    while ($rowcnt <= $report_cnt)
    {
      $mgramt = 0; 
      $mtxamt = 0; 
      $mntamt = 0; 
      $pdocym = substr($report_row['doc_date'],0,4).substr($report_row['doc_date'],5,2) ;
      while ($pdocym == (substr($report_row['doc_date'],0,4).substr($report_row['doc_date'],5,2)) && $rowcnt <= $report_cnt)
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
           <table width="750" align="center" class="table border-0" cellspacing="0" cellpadding="0">
               <tr>
                  <td class="border-0" colspan="8">    
	                 <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
	   	                   <td class="border-0" colspan="4" align="center"><b><?php echo strtoupper('Sinha and Co')?></b></td>
  		                </tr>
                        <tr>
		                   <td class="border-0" colspan="4" align="center"><b><u><?php echo strtoupper($report_desc)?></u></b></td>
		                </tr>
                        <tr>
		                   <td>&nbsp;</td>
		                   <td>&nbsp;</td>
		                   <td>&nbsp;</td>
		                   <td>&nbsp;</td>
		                </tr>
                        <tr>
		                   <td class="report_label_text">&nbsp;Branch</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $branch_name?></b></td>
		                   <td class="report_label_text" align="right">&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;&nbsp;</td>
		                </tr>
<!--                        <tr>
		                   <td class="report_label_text">&nbsp;Year</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $fin_year?></b></td>
		                   <td class="report_label_text" align="right">&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;&nbsp;</td>
		                </tr>
-->                        <tr>
		                   <td class="report_label_text">&nbsp;Period</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo date_conv($date_from,'-') . ' TO ' . date_conv($date_to,'-') ; ?></b></td>
		                   <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y'); ?></td>
		                </tr>
                        <tr>
		                   <td class="report_label_text">&nbsp;Payee</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($payee_name != '%') { echo strtoupper($payee_name) ; } else { echo 'ALL' ; } ?></b></td>
		                   <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
		                </tr>                        
	                 </table>
                  </td>    
               </tr>
               <tr>
                   <td colspan="10">&nbsp;</td>
                </tr>
               <tr class="fs-14">
                   <th width="" align="left"  class="py-3 px-2">&nbsp;Date</th>
                   <th width="" align="left"  class="py-3 px-2">&nbsp;Doc#</th>
                   <th width="" align="left"  class="py-3 px-2">&nbsp;Type</th>
                   <th width="" align="left"  class="py-3 px-2">&nbsp;DB</th>
                   <th width="" align="left"  class="py-3 px-2">&nbsp;Payee</th>
                   <th width="" align="left" class="py-3 px-2">Gross&nbsp;</th>
                   <th width="" align="left" class="py-3 px-2">Tds&nbsp;</th>
                   <th width="" align="left" class="py-3 px-2">Net&nbsp;</th>
				   <?php if ($renderFlag) : ?>
					<?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
					<?php endif; ?>
                </tr>
<?php
             $lineno = 9 ;
         }
?>
                        <tr class="fs-14 border-0">
							<?php if ($renderFlag) : ?>
							<td height="20" align="left"  class="p-2 d-none">&nbsp;<input type="hidden" name="serial_no" value="<?php echo $report_row['serial_no']?>"></td>
							<?php endif; ?>
							<td height="20" align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['doc_date'],'-')?></td> 
                           <td height="20" align="left"  class="p-2">&nbsp;<?= isset($report_row['doc_no']) ? $report_row['doc_no'] : '' ?></td>
                           <td height="20" align="left"  class="p-2">&nbsp;<?= isset($report_row['doc_type']) ? $report_row['doc_type'] : '' ?></a></td>
                           <td height="20" align="left"  class="p-2">&nbsp;<?= isset($report_row['daybook_code']) ? $report_row['daybook_code'] : '' ?></td>
                           <td height="20" align="left"  class="p-2">&nbsp;<?php echo strtoupper($report_row['payee_payer_name'])?>&nbsp;</td>
                           <td height="20" align="right" class="p-2"><?= isset($report_row['gross_amount']) ? $report_row['gross_amount'] : '' ?>&nbsp;</td>
                           <td height="20" align="right" class="p-2" ><?= isset($report_row['tax_amount']) ? $report_row['tax_amount'] : '' ?>&nbsp;</td>
                           <td height="20" align="right" class="p-2" ><?php echo $report_row['net_amount'] ?>&nbsp;</td>
						   <?php if ($renderFlag) : ?>
						   <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
						   <td height="20" align="left" class="p-2" >
								<form action="" method="post" target="_blank" name="actionForm<?= $rowcnt ?>">
									<input type="hidden" name="date_from" value="<?= $date_from ?>">
									<input type="hidden" name="date_to" value="<?= $date_to ?>">
									<input type="hidden" name="branch_code" value="<?= $branch_code ?>">
									<input type="hidden" name="expn_type" value="<?= $expn_type ?>">
									<input type="hidden" name="payee_type" value="<?= $payee_type ?>">
									<input type="hidden" name="payee_name" value="<?= $payee_name ?>">
									<input type="hidden" name="serial_no" value="<?= $report_row['serial_no'] ?>">
									<input type="hidden" name="payee_code" value="<?=  $report_row['payee_payer_code'] ?>">
									<input type="hidden" name="fin_year" value="<?=  $fin_year ?>">
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
         $mgramt = $mgramt + isset($report_row['gross_amount']) ? $report_row['gross_amount'] : 0 ;                   
         $mtxamt = $mtxamt + isset($report_row['tax_amount']) ? $report_row['tax_amount'] : 0;                   
         $mntamt = $mntamt + $report_row['net_amount'] ;                   
         //
        //  $report_row = mysql_fetch_array($ldg_qry);
		 $report_row = ($rowcnt < $report_cnt) ? $ldg_qry[$rowcnt] : $report_row;
         $rowcnt = $rowcnt + 1 ;
      }  
      $tgramt = $tgramt + $mgramt ;                   
      $ttxamt = $ttxamt + $mtxamt ;                   
      $tntamt = $tntamt + $mntamt ;                   
?>                   
                        <tr class="fs-14">
                           <!-- <td height="20" align="left"  class="p-2"  style="background-color: #fdfcc6;">&nbsp;</td> -->
                           <td height="20" align="left"  class="p-2"  style="background-color: #fdfcc6;">&nbsp;</td>
                           <td height="20" align="left"  class="p-2"  style="background-color: #fdfcc6;">&nbsp;</td>
                           <td height="20" align="right" class="p-2"  style="background-color: #fdfcc6;">&nbsp;</td>
                           <td height="20" align="center" class="p-2" colspan="0" style="background-color: #fdfcc6;"><b>TOTAL</b>&nbsp;</td>
                           <td height="20" align="right" class="p-2" colspan="2" style="background-color: #fdfcc6;"><b><?php echo number_format($mgramt,2,'.','') ?></b>&nbsp;</td>
                           <td height="20" align="right" class="p-2"  style="background-color: #fdfcc6;"><b><?php echo number_format($mtxamt,2,'.','') ?></b>&nbsp;</td>
                           <td height="20" align="right" class="p-2"  style="background-color: #fdfcc6;"><b><?php echo number_format($mntamt,2,'.','') ?></b>&nbsp;</td>
						   <?php if ($renderFlag) : ?>  
						   <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   						   
						   		<td height="20" align="right" class="p-2"  style="background-color: #fdfcc6;">&nbsp;</td>
							<?php } } ?>
							<?php endif; ?>
						</tr>
<?php
    }
?>
                        <tr class="fs-14">
                           <td height="20" align="left"  class="" colspan="8">&nbsp;</td>
                        </tr>
                        <tr class="fs-14">
                           <!-- <td height="20" align="left"   class="p-2" style="background-color: #bee9f7;">&nbsp;</td> -->
                           <td height="20" align="left"   class="p-2" style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="left"   class="p-2" style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="right"  class="p-2" style="background-color: #bee9f7;">&nbsp;</td>
                           <td height="20" align="center" class="p-2" style="background-color: #bee9f7;" ><b>GRAND TOTAL</b>&nbsp;</td>
                           <td height="20" align="right"  class="p-2" style="background-color: #bee9f7;" colspan="2"><b><?php echo number_format($tgramt,2,'.','') ?></b>&nbsp;</td>
                           <td height="20" align="right"  class="p-2" style="background-color: #bee9f7;"><b><?php echo number_format($ttxamt,2,'.','') ?></b>&nbsp;</td>
                           <td height="20" align="right"  class="p-2" style="background-color: #bee9f7;"><b><?php echo number_format($tntamt,2,'.','') ?></b>&nbsp;</td>
						   <?php if ($renderFlag) : ?>    
						   <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   						   
						   		<td height="20" align="right" class="p-2"  style="background-color: #bee9f7;">&nbsp;</td>
							<?php } } ?>
							<?php endif; ?>
						</tr>
						<?php if($tgramt+$ttxamt+$tntamt == 0) { ?> 
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
                </td>
 	         </tr>
           </table> 
	</main>
<?php } ?>
<!-- End #main -->


<?= $this->endSection() ?>