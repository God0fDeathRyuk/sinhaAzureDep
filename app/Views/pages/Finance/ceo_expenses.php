<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>
<?php if((!isset($payreg_qry)) && (!isset($trandtl_qry)) ) { ?>
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>CEO Expences</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="ceoExpenses" name="ceoExpenses" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-5 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-45 float-start datepicker" name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required >
                            <span class="w-2 float-start mx-1">--</span>
                            <input type="text" class="form-control w-45 float-start datepicker" name="end_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Report Seq <strong class="text-danger">*</strong></label>					
                            <select class="form-select" name="report_seq" onChange="myReportSeq()" required >
                                <option value="">--Select--</option>
                                <option value="S">Name-wise Group-wise Summary</option>
                                <option value="C">Name-wise</option>
                            </select>
                        </div>
                        <div class="col-md-4 px-2 float-start mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Code</label>
                            <input type="text" class="form-control w-100 float-start" name="ceo_code" id="ceoCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'ceo_code', ['nameDesc'], ['name_desc'], 'ceo_code')" readonly>
                            <i class="fa fa-binoculars icn-vw lkupIcn d-none" aria-hidden="true" id="payee_help_code" onclick="showData('ceo_code', 'display_id=<?= $displayId['payee_help_id'] ?>', 'ceoCode', ['nameDesc'], ['name_desc'], 'ceo_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 px-2 float-start mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Name</label>
                            <input type="text" class="form-control w-100 float-start" name="name_desc" id="nameDesc" readonly>
                        </div>
                        <div class="col-md-4 px-2 float-start mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Exp Code</label>
                            <input type="text" class="form-control w-100 float-start" name="exps_code" id="expsCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'exps_code', ['expsDesc'], ['exps_desc'], 'exps_code')">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" id="exp_help_id" onclick="showData('exps_code', 'display_id=<?= $displayId['exp_help_id'] ?>', 'expsCode', ['expsDesc'], ['exps_desc'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 px-2 float-start mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Exp Name</label>
                            <input type="text" class="form-control w-100 float-start" name="exps_desc" id="expsDesc" readonly>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="output_type" required >
                                <option value="Report">View Report</option>
                                <option value="Pdf" >Download PDF</option>
                                <option value="Excel" >Download Excel</option>
                            </select>
                        </div>	

                        <div class="col-md-12 float-start mt-2">
                            <button type="submit" class="btn btn-primary cstmBtn mt-2">Proceed</button>				
                            <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-1">Reset</button>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            </section>
        </form>

    </main><!-- End #main -->
<?php } else if(!isset($trandtl_qry) && isset($payreg_qry)) { ?>
    <script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>

	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
        <div class="position-absolute btndv">
            <?php if ($renderFlag) : ?>
                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
                    <a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
                <?php } else { ?> 
                    <button onclick="window.close()" class="text-decoration-none d-block float-start btn btn-dark">Close</button>
                <?php } } ?>
            <?php endif; ?>
        </div>
        <?php if($report_seq != 'S') { ?>
            <?php
                $maxline = 35 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tgramt  = 0; 
                $ttxamt  = 0; 
                $tntamt  = 0;
                $pntamt  = 0; 
                $pgramt  = 0;
                $rowcnt     = 1 ;
                $report_row = isset($payreg_qry[$rowcnt-1]) ? $payreg_qry[$rowcnt-1] : ''  ; 
                $report_cnt = $params['payreg_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $psrlind = 'Y';
                $ptxamt  = $report_row['tax_amount'] ;
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
                    <table width="950" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                            <td colspan="8">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="08%">&nbsp;</td>
                                    <td width="72%">&nbsp;</td>
                                    <td width="08%">&nbsp;</td>
                                    <td width="12%">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinhaco and company')?></b></td>
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
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y') ?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc']; ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Payee</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if ($params['payee_name'] != '%') {echo strtoupper($params['payee_name']);} else {echo 'All'  ;}  ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th width="8%" align="left"  class="py-3 px-2">&nbsp;Date</th>
                            <th width="4%" align="left"  class="py-3 px-2">&nbsp;Doc#</th>
                            <th width="3%" align="left"  class="py-3 px-2">&nbsp;DB</th>
                            <th width="20%" align="left"  class="py-3 px-2">&nbsp;Payee</th>
                            <th width="20%" align="left"  class="py-3 px-2">&nbsp;Expenses</th>

                            <th width="27%" align="left"  class="py-3 px-2">Narration</th>
                            <th width="10%" align="right" class="py-3 px-2">Gross&nbsp;</th>
                            <th width="1%" align="right" class="py-3 px-2">&nbsp;</th>
                            <?php if ($renderFlag) : ?>
                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
                            <?php endif; ?>
                        </tr>
                <?php
                            $lineno = 7 ;
                        }
                ?>
                                        <tr class="fs-14">
                                            <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo date_conv($report_row['doc_date'],'-') ; }?></td> 
                                            <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php echo $report_row['doc_no']?></td>
                                            <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo $report_row['daybook_code'] ; }?></td>
                                            <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo strtoupper($report_row['name_desc']) ; }?></td>
                                            <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo strtoupper($report_row['exps_desc']) ; }?></td>
                                            <td align="left"  class="p-2"  style="vertical-align:top"><?php echo strtoupper($report_row['narration'])?></td>
                                            <td align="right" class="p-2"  style="vertical-align:top"><?php echo $report_row['paid_amount'] ?>&nbsp;</td>
                                            <td align="right" class="p-2"  style="vertical-align:top">&nbsp;</td>
                                            <?php if($report_row['doc_no'] != '') { ?>
                                                <?php if ($renderFlag) : ?>
                                                    <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
                                                        <td height="20" align="left" class="p-2" >

                                                            <form action="<?= base_url('/finance/general-ledger?display_id=1&menu_id=150220') ?>" method="post" target="_blank" name="actionForm<?= $rowcnt ?>">
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
                                            <?php } ?>
                                        </tr>
                <?php     
                        
                        $pgramt  = $pgramt + $report_row['paid_amount'] ;
                        $psrlind = 'N' ;
                        $lineno  = $lineno + 1;
                        $report_row = ($rowcnt < $report_cnt) ? $payreg_qry[$rowcnt] : $report_row; 
                        $rowcnt = $rowcnt + 1 ;
                    }
                        

                ?>
                                    <tr class="fs-14">
                                        <td colspan="8">&nbsp;</td>
                                    </tr>    
                                    <tr class="fs-14">
                                        <td colspan="6" class="p-2" align="left" ><b>** Total</b></td>
                                        <td colspan="1" class="p-2" align="right"><b><?php if($pgramt>0) { echo number_format($pgramt,2,'.','');}?></b>&nbsp;</td>
                                        <td colspan="1" class="p-2" align="right">&nbsp;</td>
                                        <td colspan="1" class="p-2" align="right">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td colspan="8"><hr size="1" color="#CCCCCC" noshade></td>
                                    </tr>    
                <?php
                    $lineno  = $lineno + 3;
                    $tgramt  = $tgramt + $pgramt ;
                    $ttxamt  = $ttxamt + $ptxamt ;
                    $tntamt  = $tntamt + $pntamt ;
                    }  
                ?>                   
                                    <tr class="fs-14">
                                        <td colspan="8">&nbsp;</td>
                                    </tr>    
                                    <tr class="fs-14">
                                        <td height="20" colspan="6" class="p-2" align="center" style="background-color: #e2e6506e;"><b> PERIOD TOTAL </b></td>
                                        <td height="20" colspan="1" class="p-2" align="right" style="background-color: #e2e6506e;"><b><?php if($tgramt>0) { echo number_format($tgramt,2,'.','');}?></b>&nbsp;</td>
                                        <td height="20" colspan="1" class="p-2" align="right" style="background-color: #e2e6506e;">&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
        <?php } else { ?>

            <?php
                $maxline = 65 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tgramt  = 0; 
                $ttxamt  = 0; 
                $tntamt  = 0; 
                $rosamt  = 0;
                $trosamt = 0;
                $totosamt = 0;
                $rowcnt     = 1 ;
                $report_row = isset($payreg_qry[$rowcnt-1]) ? $payreg_qry[$rowcnt-1] : '' ;   
                $report_cnt = $params['payreg_cnt'] ;
                
                while ($rowcnt <= $report_cnt)
                {
                $psrlind = 'Y';
                $pcourtnm = $report_row['name_desc'];;
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
                    <table width="700" align="center" border="1" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                        <td colspan="6">    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="11%" class="cheque_text">&nbsp;</td>
                                    <td width="69%" class="cheque_text">&nbsp;</td>
                                    <td width="8%" class="cheque_text">&nbsp;</td>
                                    <td width="12%" class="cheque_text">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="cheque_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
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
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y') ?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">&nbsp;Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc'] ; ?></b></td>
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
                            <th width="40%" align="left"  class="py-3 px-2" colspan="6">&nbsp;<b>Name</b></th>
                        </tr>
                        <tr class="fs-14">
                            <th width="40%" align="left"  class="py-3 px-2">&nbsp;</th>
                            <th width="35%" align="left"  class="py-3 px-2">&nbsp;<b>Expenses</b></th>
                            <th width="15%" align="right" class="py-3 px-2"><b>Gross</b>&nbsp;</th>
                            <th width="15%" align="right" class="py-3 px-2"><b>Net</b>&nbsp;</th>
                            <?php if ($renderFlag) : ?>
                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
                            <?php endif; ?>
                        </tr>
                            
            <?php
                    $lineno = 9 ;
                }
                //----------
                // $rowdesc   = $report_row['name_desc'] ;
                
                $rosamt    = $report_row['paid_amount'] ; 
                $trosamt   = $trosamt + $report_row['paid_amount'] ; 
                $totosamt  = $totosamt + $trosamt  ; 
            ?>
                            <tr class="fs-14">
                                <td class="p-2" align="left">&nbsp;<?php echo $pcourtnm?></td>
                                <td class="p-2" align="left">&nbsp;</td>
                                <td class="p-2" align="left">&nbsp;</td>
                                <td class="p-2" align="left">&nbsp;</td>
                            </tr>
                            <tr class="fs-14">
                                <td class="p-2" align="left">&nbsp;</td>
                                <td class="p-2" align="right"><?php echo $report_row['exps_desc'];?>&nbsp;</td>
                                <td class="p-2" align="right"><?php if($rosamt == 0.00) echo '&nbsp;'; else  echo number_format($rosamt,2,'.','');?>&nbsp;</td>
                                <td class="p-2" align="right"><?php if($trosamt == 0.00) echo '&nbsp;'; else  echo number_format($trosamt,2,'.','');?>&nbsp;</td>
                                <?php if($pcourtnm != '') { ?>
                                    <?php if ($renderFlag) : ?>
                                        <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
                                            <td height="20" align="left" class="p-2" >

                                                <form action="" method="post" target="_blank" name="actionForm<?= $rowcnt ?>">
                                                    <input type="hidden" name="ceo_code" value="<?= $report_row['ceo_code'] ?>">
                                                    <input type="hidden" name="branch_code" value="<?= $params['branch_code'] ?>">
                                                    <input type="hidden" name="start_date" value="<?= $params['start_date'] ?>">
                                                    <input type="hidden" name="end_date" value="<?= $params['end_date'] ?>">
                                                    <input type="hidden" name="name_desc" value="<?= $pcourtnm ?>">
                                                    <input type="hidden" name="exps_code" value="">
                                                    <input type="hidden" name="report_seq" value="">
                                                    <input type="hidden" name="output_type" value="">
                                                    <button type="button" class="me-1 border-0 p-0" title="View Report" onclick="setOutputType('Report', <?= $rowcnt ?>)">
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
                                                        document['actionForm'+no].output_type.value = type;
                                                        document['actionForm'+no].submit();
                                                    }
                                                </script>
                                            </td>
                                        <?php } } ?>
                                    <?php endif; ?>
                                <?php } ?>
                            </tr>
            <?php     
                $lineno = $lineno + 1;

                $report_row = ($rowcnt < $report_cnt) ? $payreg_qry[$rowcnt] : $report_row;    
                $rowcnt = $rowcnt + 1 ;
                }
            }  
            ?>                   
                            <tr class="fs-14">
                                <td class="p-2" align="right" style="background-color: #e2e6506e;"><b>TOTAL</b>&nbsp;</td>
                                <td class="p-2" align="center" style="background-color: #e2e6506e;">&nbsp;</td>
                                <td class="p-2" align="center" style="background-color: #e2e6506e;">&nbsp;</td>
                                <td class="p-2"  align="right" style="background-color: #e2e6506e;"><b><?php if($totosamt == 0.00) echo '&nbsp;'; else  echo number_format($totosamt,2,'.','');?></b>&nbsp;</td>
                                <td class="p-2" align="center" style="background-color: #e2e6506e;">&nbsp;</td>
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
        console.log(document.ceoExpenses);
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.ceoExpenses.start_date.value.substring(6,10)+document.ceoExpenses.start_date.value.substring(3,5)+document.ceoExpenses.start_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.ceoExpenses.start_date.focus()}, 500) });
            return false;
        }
        else if (document.ceoExpenses.end_date.value.substring(6,10)+document.ceoExpenses.end_date.value.substring(3,5)+document.ceoExpenses.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.ceoExpenses.end_date.focus()}, 500) });
            return false;
        }
        else if (document.ceoExpenses.start_date.value.substring(6,10)+document.ceoExpenses.start_date.value.substring(3,5)+document.ceoExpenses.start_date.value.substring(0,2)>document.ceoExpenses.end_date.value.substring(6,10)+document.ceoExpenses.end_date.value.substring(3,5)+document.ceoExpenses.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'Period End Date must be less than Period Start Date' }).then((result) => { setTimeout(() => {document.ceoExpenses.end_date.focus()}, 500) });
            return false;
        }
        
        document.ceoExpenses.submit();
    }

    function myReportSeq()
    {
        if (document.ceoExpenses.report_seq.value == '') 
        {
        document.ceoExpenses.ceo_code.value       = '' ; 
        document.ceoExpenses.name_desc.value      = '' ; 
        document.ceoExpenses.ceo_code.readOnly    = true ;
        document.getElementById("payee_help_code").classList.add('d-none'); 
        document.ceoExpenses.report_seq.focus() ; 
        } 
        else if (document.ceoExpenses.report_seq.value == 'S') 
        {
        document.ceoExpenses.ceo_code.value            = '' ; 
        document.ceoExpenses.name_desc.value            = '' ; 
        document.ceoExpenses.ceo_code.readOnly         = true ; 
        document.getElementById("payee_help_code").classList.add('d-none');
        } 
        
        else if (document.ceoExpenses.report_seq.value == 'C') 
        {
        document.ceoExpenses.ceo_code.value            = '' ; 
        document.ceoExpenses.name_desc.value            = '' ; 
        document.ceoExpenses.ceo_code.readOnly         = false ; 
        document.getElementById("payee_help_code").classList.remove("d-none");
        } 
	}

</script>
<?= $this->endSection() ?>