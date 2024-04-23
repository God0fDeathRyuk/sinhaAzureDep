<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if (!isset($tdscert_qry)) { ?> 
 
	<main id="main" class="main">

	<?php if (session()->getFlashdata('message') !== NULL) : ?>
	<div id="alertMsg">
		<div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
		<div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  	</div>
<?php endif; ?>

		<div class="pagetitle col-md-12 float-start border-bottom pb-1">
		<h1>TDS Certificate Status (Receivable)</h1>
		</div>

		<form action="" method="post">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
                    <div class="frms-sec-insde d-block float-start col-md-4 pe-2 mb-4">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">As On Date</label>
                        <input type="text" class="form-control float-start set-date" id="" placeholder="dd-mm-yyyy" name="ason_date" value="<?= $data['ason_date'] ?>" readonly />
                    </div>
					<div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
						<select class="form-select cstm-inpt" name="branch_code">
						<?php foreach($data['branches'] as $branch) { ?>
						<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
						<?php } ?>
						</select>
					</div>
                    <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Financial Year</label>
						<select class="form-select cstm-inpt" name="fin_year">
						<?php foreach($data['finyr_qry'] as $branch) { ?>
						<option value="<?= $branch['fin_year'] ?>"><?= $branch['fin_year'] ?></option>
        		<!-- <option value="<?php // echo $finyr_row[fin_year]?>" <?php // if($global_curr_finyear == $finyr_row[fin_year]) { echo 'selected' ; }?>><?php // echo $finyr_row[fin_year]?></option> -->
						<?php } ?>
						</select>
					</div>
					
                    <div class="col-md-4 float-start px-2 mb-3">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Type <strong class="text-danger">*</strong></label>
                        <select class="form-select" name="payee_type" id="payeeType" onchange="cleanData(this, 'payeeCode', '%&_', 'payeeCodeLookup')" required>
                        <option value="">--Select--</option>
                        <option value="%">All</option>
                        <option value="C">Counsel</option>
                        <option value="E">Employee</option>
                        <option value="S">Supplier</option>
                        <option value="O">Others</option>
                        </select>
                    </div>
                    <div class="col-md-4 float-start px-2 mb-3 position-relative">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Code</label>
                        <input type="text" class="form-control" name="payee_code" id="payeeCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeeName'], ['payee_payer_name'], 'payee_code', 'payee_payer_type=@payeeType')"/>
                        <i class="fa-solid fa-binoculars icn-vw d-none" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['payee_help_id'] ?>&payee_payer_type=@payeeType', 'payeeCode', ['payeeName'], ['payee_payer_name'], 'payee_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                    </div>
                    <div class="col-md-4 float-start px-2 mb-3">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Name</label>
                        <input type="text" class="form-control" name="payee_name" id="payeeName" readonly/>
                    </div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Certificate Status</label>
						<select class="form-select" name="cert_status">
                        <option value="A">All</option>
                        <option value="Y">Received</option>
                        <option value="N">Not Received</option>
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
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a> -->
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a>-->
				<?php if ($renderFlag) : ?>
				    <a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
                <?php endif; ?>
         </div>

    <?php
    $maxline = 50 ;
    $lineno  = 0 ;
    $pageno  = 0 ;
    $tgramt  = 0; 
    $ttxamt  = 0; 
    $rowcnt  = 1 ;
    $report_row = isset($tdscert_qry[$rowcnt-1]) ? $tdscert_qry[$rowcnt-1] : '' ;  
	$report_cnt = $params["tdscert_cnt"] ;
    while ($rowcnt <= $report_cnt)
    {
      $mgramt = 0; 
      $mtxamt = 0; 
      $ppayeecd = $report_row['payee_payer_code'] ;
      $ppayeenm = $report_row['payee_payer_name'] ;
    //echo "<pre>"; print_r($report_row['payee_payer_code']); die;
      while ($ppayeecd == $report_row['payee_payer_code'] && $rowcnt <= $report_cnt)
      {
     // echo "<pre>"; print_r($report_row['payee_payer_code']); die;
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
           <table class="table border-0" cellspacing="0" cellpadding="0">
               <tr>
                  <td class="border-0 pb-0" colspan="9">    
	                 <table width="100%" align="center" border="0">
                        <tr>
                            <td colspan="9" class="text-center border-0" align="center">
                                <span class="d-block w-100 text-uppercase fw-bold"><b>Sinha and Company</b></span>
                            </td>
  		                </tr>
                        <tr>
                            <td class="report_label_text" colspan="9" align="center"><span class="d-block w-100 text-uppercase fw-bold"><u><?php echo strtoupper($params['report_desc']);?></u></span></td>
                        </tr>
                        <tr>
                            <td colspan="9">&nbsp;</td>
                        </tr>
                        <tr>
		                   <td colspan="8" class="border-0 w-70">
                           		<p class="d-block w-100 text-uppercase">
                                   <span class="w-15 d-block float-start">Branch  </span><strong>: <?= $params['branch_name'];?> </strong>
                                </p>
                                <p class="d-block w-100 text-uppercase">
                                    <span class="w-15 d-block float-start">Date  </span><strong>: <?= $params["ason_date"]?> </strong>
                                </p>
                                <p class="d-block w-100 text-uppercase">
                                    <span class="w-15 d-block float-start">Year  </span><strong>: <?php echo $params["fin_year"]?> </strong>
                                </p>
                           </td>
		                   <td colspan="" class="border-0">
                                <p class="d-block w-100 text-uppercase">
                                    <span class="w-15 d-block float-start">Page  </span><strong>: <?php echo $pageno?> </strong>
                                </p>
                                <p class="d-block w-100 text-uppercase">
                                    <span class="w-15 d-block float-start">As On  </span><strong>: <?= $params["ason_date"] ; ?> </strong>
                                </p>
                                <p class="d-block w-100 text-uppercase">
                                    <span class="w-15 d-block float-start">Party  </span><strong>: <?php if($params["payee_code"] != '%') { echo strtoupper($params["payee_name"]) ; }  else { echo 'ALL' ; } ?> </strong>                                    
                                    [<b><?php echo $params["cert_type"] ; ?></b>]
                                </p>
                          </td>
		                </tr>
	                 </table>
                  </td>    
               </tr>
               <tr><td width="10%" align="left"  class="py-1">&nbsp;</td></tr>
               <tr class="fs-14">
                <th class="border px-3 py-2">Date</th>
                    <th class="border px-3 py-2">DB</th>
                    <th class="border px-3 py-2">Type</th>
                    <th class="border px-3 py-2">Doc#</th>
                    <th class="border px-3 py-2">Party</hd>
                    <th class="border px-3 py-2">Gross</th>
                    <th class="border px-3 py-2">TDS</th>
                    <th class="border px-3 py-2">Cert#</th>
                    <th class="border px-3 py-2">Cert Dt</th>
                </tr>
                       
        <?php
                    $lineno = 7 ;
                }
        ?>
                        <tr class="fs-14">
                           <td height="20" align="left"  class="report_detail_right px-2 py-2 align-text-top" style="vertical-align:top">&nbsp;<?php echo date_conv($report_row['doc_date'],'-')?></td> 
                           <td height="20" align="left"  class="report_detail_right px-2 py-2 align-text-top" style="vertical-align:top">&nbsp;<?php echo $report_row['daybook_code']?></td>
                           <td height="20" align="left"  class="report_detail_right px-2 py-2 align-text-top" style="vertical-align:top">&nbsp;<?php echo $report_row['doc_type']?></a></td>
                           <td height="20" align="left"  class="report_detail_right px-2 py-2 align-text-top" style="vertical-align:top">&nbsp;<?php echo $report_row['doc_no']?></td>
                           <td height="20" align="left"  class="report_detail_right px-2 py-2 align-text-top" style="vertical-align:top">&nbsp;<?php echo str_replace("\\","",$report_row['payee_payer_name'])?></td>
                           <td height="20" align="left"  class="report_detail_right px-2 py-2 align-text-top" style="vertical-align:top"><?php echo $report_row['gross_amount'] ?></td>
                           <td height="20" align="left"  class="report_detail_right px-2 py-2 align-text-top" style="vertical-align:top"><?php echo $report_row['tax_amount'] ?></td>
                           <td height="20" align="left"  class="report_detail_right px-2 py-2 align-text-top" style="vertical-align:top">&nbsp;<?php echo $report_row['tds_cert_no']?></td>
                           <td height="20" align="left"  class="report_detail_right px-2 py-2 align-text-top" style="vertical-align:top">&nbsp;<?php if($report_row['tds_cert_date'] != '' && $report_row['tds_cert_date'] != '0000-00-00') { echo date_conv($report_row['tds_cert_date'],'-') ; }?></td> 
                        </tr>
        <?php     
                $lineno = $lineno + 1;
                $mgramt = $mgramt + $report_row['gross_amount'] ;                   
                $mtxamt = $mtxamt + $report_row['tax_amount'] ;                   
                //
                $report_row = ($rowcnt < $report_cnt) ? $tdscert_qry[$rowcnt] : $report_row;
                $rowcnt = $rowcnt + 1 ;
            }  
            $tgramt = $tgramt + $mgramt ;                   
            $ttxamt = $ttxamt + $mtxamt ;                   
        ?>                   
                        <tr class="fs-14">
                           <td height="35" colspan="5" align="center" class="p-2" style="background-color: #e2e6506e;"><b>Total</b>&nbsp;</td>
                           <td height="35" align="left" class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format($mgramt,2,'.','') ?></b></td>
                           <td height="35" align="left" class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format($mtxamt,2,'.','') ?></b></td>
                           <td height="35" align="left"  class="p-2"  style="background-color: #e2e6506e;">&nbsp;</td>
                           <td height="35" align="left" class="p-2"  style="background-color: #e2e6506e;">&nbsp;</td>
                        </tr>
        <?php
            }
        ?>
                        <tr>
                           <td height="35" align="left"  class="report_detail_none border-0">&nbsp;</td>
                           <td height="35" align="left"  class="report_detail_none border-0">&nbsp;</td>
                           <td height="35" align="left"  class="report_detail_none border-0">&nbsp;</td>
                           <td height="35" align="right" class="report_detail_none border-0">&nbsp;</td>
                           <td height="35" align="right" class="report_detail_none border-0">&nbsp;</td>
                           <td height="35" align="right" class="report_detail_none border-0">&nbsp;</td>
                           <td height="35" align="right" class="report_detail_none border-0">&nbsp;</td>
                           <td height="35" align="right" class="report_detail_none border-0">&nbsp;</td>
                        </tr>
                        <tr>
                           <td height="35" align="center" colspan="5" class="p-2 border-0" style="background-color: #a1d1e4;"><b>GRAND TOTAL</b>&nbsp;</td>
                           <td height="35" align="left"  class="p-2 border-0" style="background-color: #a1d1e4;"><b><?php echo number_format($tgramt,2,'.','') ?></b></td>
                           <td height="35" align="left"  class="p-2 border-0" style="background-color: #a1d1e4;"><b><?php echo number_format($ttxamt,2,'.','') ?></b></td>
                           <td height="35" align="left"   class="p-2 border-0" style="background-color: #a1d1e4;">&nbsp;</td>
                           <td height="35" align="left"  class="p-2 border-0" style="background-color: #a1d1e4;">&nbsp;</td>
                        </tr>
                   </table>
                </td>
 	         </tr>
           </table> 
    </main>
<?php } ?>
<!-- End #main -->


<?= $this->endSection() ?>