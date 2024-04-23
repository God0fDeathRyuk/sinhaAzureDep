<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($payreg_qry)) && (!isset($trandtl_qry))) { ?> 
    <main id="main" class="main">
    <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <div class="pagetitle">
    <h1>Payment Register</h1>
    </div><!-- End Page Title -->

    <form action="" method="post" id="paymentRegister" name="paymentRegister" onsubmit="setValue(event)">
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
                    <div class="col-md-5 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                        <input type="text" class="form-control w-48 float-start" name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)"required>
                        <span class="w-2 float-start mx-2">---</span>
                        <input type="text" class="form-control w-48 float-start" name="end_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required>
                        <input type="hidden" name="current_date" value="<?= date('d-m-Y') ?>">
                    </div>
                    <div class="col-md-3 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Type  <strong class="text-danger">*</strong></label>
                        <select class="form-select w-100 float-start" name="payee_payer_type" id="payeePayerType" onchange="cleanData(this, 'payeePayerCode', '%&_', 'payeeCodeLookup')" required >
                            <option value="">--Select--</option>
                            <option value="%">All</option>
                            <option value="E">Employee</option>
                            <option value="S">Supplier</option>
                            <option value="C">Counsel</option>
                            <option value="A">Arbitrator</option>
                            <option value="T">Stenographer</option> 
                            <option value="O">Others</option>
                        </select>
                    </div>				
                    
                    <div class="col-md-4 float-start px-2 mb-1 position-relative">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Code </label>
                        <input type="text" class="form-control w-100 float-start" name="payee_payer_code" id="payeePayerCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'payee_payer_code', ['payeePayerName'], ['payee_payer_name'], 'payee_code', 'payee_type=@payeePayerType')">
                        <i class="fa-solid fa-binoculars icn-vw d-none" id="payeeCodeLookup" onclick="showData('payee_payer_code', 'display_id=<?= $displayId['payee_help_id'] ?>&payee_type=@payeePayerType', 'payeePayerCode', ['payeePayerName'], ['payee_payer_name'], 'payee_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                    </div>
                    <div class="col-md-4 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Payee Name</label>
                        <input type="text" class="form-control w-100 float-start" name="payee_payer_name" id="payeePayerName" readonly>
                    </div>
                    <div class="col-md-4 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Report Type <strong class="text-danger">*</strong></label>
                        <select class="form-select w-100 float-start" name="report_type" required >
                            <option value="D">Detail</option>
                            <option value="S">Summary</option>
                        </select>
                    </div>
                    <div class="col-md-4 float-start px-2 mb-1">
                        <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                        <select class="form-select" name="output_type" tabindex="12" required>
                            <option value="Report">View Report</option>
                            <option value="Pdf">Download PDF</option>
                            <option value="Excel">Download Excel</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary cstmBtn mt-31 ms-2">Proceed</button>				
                    <button type="button" class="btn btn-primary cstmBtn btn-cncl  mt-31 ms-2">Cancel</button>
                </div>
                
            </div>
            
        </div>
        </section>
    </form>
    </main><!-- End #main -->
<?php } else if(!isset($trandtl_qry) && isset($payreg_qry)){ ?>
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
            <?php if($form_report_type == 'D') { ?>
                <?php
                    $maxline = 62 ;
                    $lineno  = 0 ;
                    $pageno  = 0 ;
                    $tgramt  = 0; 
                    $ttxamt  = 0; 
                    $tntamt  = 0; 
                    $rowcnt     = 1 ;
                    $report_row = isset($payreg_qry[$rowcnt-1]) ? $payreg_qry[$rowcnt-1] : '' ;
                    $report_cnt = $params['payreg_cnt'] ;
                    while ($rowcnt <= $report_cnt)
                    {
                    $psrlind = 'Y';
                    $pgramt  = $report_row['gross_amount'] ;
                    $ptxamt  = $report_row['tax_amount'] ;
                    $pntamt  = $report_row['net_amount'] ;
                    $pserial = $report_row['serial_no'] ;
                    while($pserial == $report_row['serial_no'] && $rowcnt <= $report_cnt) 
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
                        <table width="750" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                            <tr>
                                <td colspan="8">    
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
                                        <td class="report_label_text">&nbsp;Period</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['start_date'] . ' TO ' . $params['end_date'] ; ?></b></td>
                                        <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                        </tr>
                                        <tr>
                                        <td class="report_label_text">&nbsp;Payee</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['payee_code'] != '%') { echo strtoupper($params['payee_name']);} else { echo 'ALL' ;}  ?></b></td>
                                        <td class="report_label_text">&nbsp;</td>
                                        <td class="report_label_text">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>    
                            </tr>
                            <tr class="fs-14">
                                <th width="08%" align="left"  class="py-3 px-2">&nbsp;Date</th>
                                <th width="05%" align="left"  class="py-3 px-2">&nbsp;Doc#</th>
                                <th width="03%" align="left"  class="py-3 px-2">&nbsp;DB</th>
                                <th width="25%" align="left"  class="py-3 px-2">&nbsp;Payee</th>
                                <th width="32%" align="left"  class="py-3 px-2">Narration</th>
                                <th width="09%" align="right" class="py-3 px-2">Gross&nbsp;</th>
                                <th width="09%" align="right" class="py-3 px-2">TDS&nbsp;</th>
                                <th width="09%" align="right" class="py-3 px-2">Net&nbsp;</th>
                                <?php if ($renderFlag) : ?>
                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
                                <?php endif; ?>
                            </tr>
                                        
                    <?php
                                $lineno = 7 ;
                            }
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo date_conv($report_row['doc_date'],'-') ; }?></td> 
                                            <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo $report_row['doc_no'] ; } ?></td>
                                            <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo $report_row['daybook_code'] ; }?></td>
                                            <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo strtoupper($report_row['payee_payer_name']) ; }?></td>
                                            <td align="left"  class="p-2"  style="vertical-align:top"><?php echo strtoupper($report_row['narration'])?></td>
                                            <td align="right" class="p-2"  style="vertical-align:top"><?php echo $report_row['paid_amount'] ?>&nbsp;</td>
                                            <td align="right" class="p-2"  style="vertical-align:top">&nbsp;</td>
                                            <td align="right" class="p-2"  style="vertical-align:top">&nbsp;</td>
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
                            $psrlind = 'N' ;
                            $lineno  = $lineno + 1;
                            $report_row = ($rowcnt < $report_cnt) ? $payreg_qry[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                        }
                    ?>
                                        
                                        <tr class="fs-14 border-0">
                                            <td colspan="5" class="p-2" align="right" style="background-color: #fdfcc6;"><b>Total</b></td>
                                            <td colspan="1" class="p-2" align="right" style="background-color: #fdfcc6;"><b><?php if($pgramt>0) { echo number_format($pgramt,2,'.','');}?></b>&nbsp;</td>
                                            <td colspan="1" class="p-2" align="right" style="background-color: #fdfcc6;"><b><?php if($ptxamt>0) { echo number_format($ptxamt,2,'.','');}?></b>&nbsp;</td>
                                            <td colspan="1" class="p-2" align="right" style="background-color: #fdfcc6;"><b><?php if($pntamt>0) { echo number_format($pntamt,2,'.','');}?></b>&nbsp;</td>
                                        </tr> <tr class="fs-14 border-0">
                                            <td colspan="8">&nbsp;</td>
                                        </tr> 
                    <?php
                        $lineno  = $lineno + 3;
                        $tgramt  = $tgramt + $pgramt ;
                        $ttxamt  = $ttxamt + $ptxamt ;
                        $tntamt  = $tntamt + $pntamt ;
                        }  
                    ?>                       
                                            <tr class="fs-14 border-0">
                                            <td height="20" colspan="5" class="p-2" align="right"  style="background-color: #bee9f7;"><b> PERIOD TOTAL </b></td>
                                            <td height="20" colspan="1" class="p-2" align="right"  style="background-color: #bee9f7;"><b><?php if($tgramt>0) { echo number_format($tgramt,2,'.','');}?></b>&nbsp;</td>
                                            <td height="20" colspan="1" class="p-2" align="right"  style="background-color: #bee9f7;"><b><?php if($ttxamt>0) { echo number_format($ttxamt,2,'.','');}?></b>&nbsp;</td>
                                            <td height="20" colspan="1" class="p-2" align="right"  style="background-color: #bee9f7;"><b><?php if($tntamt>0) { echo number_format($tntamt,2,'.','');}?></b>&nbsp;</td>
                                            </tr>
                                    </table>
                                    </td>
                                </tr>
                            </table> 
            <?php } else if($form_report_type == 'S'){ ?>
                <?php
                    $maxline = 65 ;
                    $lineno  = 0 ;
                    $pageno  = 0 ;
                    $tgramt  = 0; 
                    $ttxamt  = 0; 
                    $tntamt  = 0; 
                    $tosamt  = 0;
                    $rowcnt     = 1 ;
                    $report_row = isset($payreg_qry[$rowcnt-1]) ? $payreg_qry[$rowcnt-1] : '' ; 
                    $report_cnt = $params['payreg_cnt'] ;
                    while ($rowcnt <= $report_cnt)
                    {
                    $psrlind = 'Y';
                    // $pgramt  = $report_row['paid_amount'] ;
                    // $ptxamt  = $report_row['tax_amount'] ;
                    // $pntamt  = $report_row['net_amount'] ;
                    $pserial = $report_row['serial_no'] ;
                    while($pserial == $report_row['serial_no'] && $rowcnt <= $report_cnt) 
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
                        <table width="750" align="center" border="1" cellspacing="0" cellpadding="0" class="table border-0">
                            <tr>
                            <td colspan="3">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="cheque_text" colspan="4" align="center"><b><?php echo strtoupper('Sinha And Company')?></b></td>
                                    </tr>
                                    <tr>
                                        <td class="cheque_text" colspan="4" align="center"><b><u><?php echo strtoupper($params['report_desc'])?></u></b></td>
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
                                        <td class="report_label_text">&nbsp;Payee</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo strtoupper($params['payee_type_name']) ; ?></b></td>
                                        <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">&nbsp;Period</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['start_date'] . ' TO ' . $params['end_date'] ; ?></b></td>
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
                                <th width="75%" align="left"   class="py-3 px-2">&nbsp;<b>Payee Name</b></th>
                                <th width="15%" align="right"  class="py-3 px-2"><b>Amount</b>&nbsp;</th>
                                <?php if ($renderFlag) : ?>
                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2">Actions&nbsp;</th> <?php } } ?>
                                <?php endif; ?>
                            </tr>
                    <?php
                            $lineno = 9 ;
                        }
                        //----------
                        $rowdesc  = $report_row['payee_payer_name'] ; 
                        $rosamt   = $report_row['paid_amount'] ; 
                        $tosamt   = $tosamt + $rosamt  ;// echo '<pre>';print_r($tosamt);die
                    ?>
                                    <tr class="fs-14 border-0">
                                        <td class="p-2" align="left">&nbsp;<?php echo strtoupper($report_row['payee_payer_name'])?></td>
                                        <td class="p-2" align="right"><?php if($rosamt == 0.00) echo '&nbsp;'; else  echo number_format($rosamt,2,'.','');?>&nbsp;</td>
                                        <?php if ($renderFlag) : ?>
                                        <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
                                        <td height="20" align="left" class="p-2" >

                                            <form action="" method="post" target="_blank" name="actionFormSummary<?= $rowcnt ?>">
                                                <input type="hidden" name="doc_type" value="<?= $report_row['doc_type'] ?>">
                                                <input type="hidden" name="serial_no" value="<?= $report_row['serial_no'] ?>">
                                                <input type="hidden" name="branch_code" value="<?= $params['branch_code'] ?>">
                                                <input type="hidden" name="start_date" value="<?= $params['start_date'] ?>">
                                                <input type="hidden" name="end_date" value="<?= $params['end_date'] ?>">
                                                <input type="hidden" name="payee_payer_type" value="<?= $params['payee_type'] ?>">
                                                <input type="hidden" name="payee_payer_code" value="<?= $report_row['payee_payer_code'] ?>">
                                                <input type="hidden" name="payee_payer_name" value="<?= $report_row['payee_payer_name'] ?>">
                                                <input type="hidden" name="show_summary_report" value="<?= ($show_summary_report) ? 'Y' : '' ?>">
                                                <input type="hidden" name="report_type" value="<?= ($show_summary_report) ? 'D' : $form_report_type ?>">
                                                <input type="hidden" name="output_type" value="">
                                                <button type="button" class="me-1 border-0 p-0" title="View Report" onclick="setOutputType('<?= ($show_summary_report) ? 'Report' : 'innerReport' ?>', <?= $rowcnt ?>)">
                                                    <i class="fa-solid fa-eye edit"></i>
                                                </button>
                                                <button type="button" class="me-1 border-0 p-0" class="me-1" title="Download Excel" onclick="setOutputType('Excel', <?= $rowcnt ?>)">
                                                    <i class="fa-solid fa-file-excel edit"></i>
                                                </button>
                                                <button type="button" class="me-1 border-0 p-0" class="me-1" title="Download PDF" onclick="setOutputType('Pdf', <?= $rowcnt ?>)">
                                                    <i class="fa-solid fa-file-pdf edit"></i>
                                                </button>									
                                            </form>
                                            <script>
                                                function setOutputType(type, no) {
                                                    document['actionFormSummary'+no].output_type.value = type;
                                                    document['actionFormSummary'+no].submit();
                                                }
                                            </script>
                                        </td>
                                        <?php } } ?>
                                        <?php endif; ?>
                                    
                                    </tr>
                    <?php     
                        $lineno = $lineno + 1;

                        $report_row = ($rowcnt < $report_cnt) ? $payreg_qry[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;
                        }
                    }  
                    ?>                   
                                <tr class="fs-14 border-0">
                                    <td class="p-2" align="center" style="background-color: #fdfcc6;"><b>TOTAL</b>&nbsp;</td>
                                    <td class="p-2"  align="right" style="background-color: #fdfcc6;"><b><?php if($tosamt == 0.00) echo '&nbsp;'; else  echo number_format($tosamt,2,'.','');?></b>&nbsp;</td>
                                    <td class="p-2" style="background-color: #fdfcc6;">&nbsp;</td>
                                </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
           <?php } ?>
    </main>
<?php }  else if(isset($trandtl_qry)) { ?>
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
            console.log(document.paymentRegister);
            var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

            if (document.paymentRegister.start_date.value.substring(6,10)+document.paymentRegister.start_date.value.substring(3,5)+document.paymentRegister.start_date.value.substring(0,2) > today_date) {
                Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.paymentRegister.start_date.focus()}, 500) });
                return false;
            }
            else if (document.paymentRegister.end_date.value.substring(6,10)+document.paymentRegister.end_date.value.substring(3,5)+document.paymentRegister.end_date.value.substring(0,2) > today_date) {
                Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.paymentRegister.end_date.focus()}, 500) });
                return false;
            }
            else if (document.paymentRegister.start_date.value.substring(6,10)+document.paymentRegister.start_date.value.substring(3,5)+document.paymentRegister.start_date.value.substring(0,2)>document.paymentRegister.end_date.value.substring(6,10)+document.paymentRegister.end_date.value.substring(3,5)+document.paymentRegister.end_date.value.substring(0,2)) {
                Swal.fire({ text: 'Period End Date must be less than Period Start Date' }).then((result) => { setTimeout(() => {document.paymentRegister.end_date.focus()}, 500) });
                return false;
            }
            
            document.paymentRegister.submit();
        }
</script>
<?= $this->endSection() ?>