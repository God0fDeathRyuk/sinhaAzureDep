<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>
<?php if((!isset($trial_qry))) { ?> 
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>Trial Balance</h1>
        </div><!-- End Page Title -->
        <form action="" method="post" id="acReport5110" name="acReport5110">
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
                                <input type="text" class="form-control w-45 float-start datepicker" name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required>
                                <span class="w-2 float-start mx-1">--</span>
                                <input type="text" class="form-control w-45 float-start datepicker" name="end_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Comparative <strong class="text-danger">*</strong></label>					
                                <select class="form-select" name="comparative_ind" required >
                                    <option value="N">No</option>
                                    <option value="Y">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Report Type <strong class="text-danger">*</strong></label>					
                                <select class="form-select" name="report_type" required >
                                    <option value="" >---Select---</option>
                                    <option value="G">A/c Group-wise</option>
                                    <option value="T">A/c Type-wise</option>
                                    <option value="C">A/c Code-wise</option>
                                </select>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Report Option <strong class="text-danger">*</strong></label>					
                                <select class="form-select" name="report_option" required >
                                    <option value="M">Moved</option>
                                    <option value="F">Full</option>
                                </select>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Report Output <strong class="text-danger">*</strong></label>					
                                <select class="form-select" name="report_otp" required >
                                    <option value="D">Detail</option>
                                    <option value="S">Summary</option>
                                    <option value="G">Segregated</option>
                                </select>
                            </div>
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                                <select class="form-select w-100 float-start" name="output_type" required >
                                    <option value="Report">View Report</option>
                                    <option value="Pdf" >Download PDF</option>
                                    <option value="Excel" >Download Excel</option>
                                </select>
                            </div>	

                            <div class="col-md-3 float-start mt-20">
                                <button type="submit" class="btn btn-primary cstmBtn mt-2">Proceed</button>				
                                <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-1">Reset</button>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </section>
        </form>

    </main><!-- End #main -->
<?php } else { ?>
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
    
        <?php if($params['comparative_ind'] == 'N') { ?>
            <?php if($params['report_otp'] == 'D') { ?>
                <?php
                    $maxline = 52 ;
                    $lineno  = 0 ;
                    $pageno  = 0 ;
                    $tbilamt = 0; 
                    $tcolamt = 0; 
                    $tbalamt = 0; 
                    $rowcnt     = 1 ;
                    $report_row = isset($trial_qry[$rowcnt-1]) ? $trial_qry[$rowcnt-1] : '' ;  
                    $report_cnt = $params['trial_cnt'] ;
                    while ($rowcnt <= $report_cnt)
                    {
                    
                    $bopdramt  = 0; 
                    $bopcramt  = 0; 
                    $bptdramt  = 0; 
                    $bptcramt  = 0; 
                    $bcldramt  = 0; 
                    $bclcramt  = 0; 
                    $topdramt  = 0;
                    $topcramt  = 0;
                    $tptdramt  = 0;
                    $tptcramt  = 0;
                    $tcldramt  = 0;
                    $tclcramt  = 0;
                    $plevelind = 'Y' ;
                    $plevelcd  = $report_row['level_code'] ;  
                    $plevelnm  = $report_row['level_name'] ;  
                    while($plevelcd == $report_row['level_code'] && $rowcnt <= $report_cnt)
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
                                <td colspan="9">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                        <td width="08%">&nbsp;</td>
                                        <td width="72%">&nbsp;</td>
                                        <td width="08%">&nbsp;</td>
                                        <td width="12%">&nbsp;</td>
                                        </tr>
                                        <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
                                        </tr>
                                        <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b><u><?php echo strtoupper($params['report_desc'])?></u></b></td>
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
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['end_date']?></td>
                                        </tr>
                                        <tr>
                                        <td class="report_label_text">&nbsp;Period</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc'] ?></b></td>
                                        <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                                <th height="18" align="left"  class="py-3 px-2">&nbsp;</th>
                                <th height="18" align="left"  class="py-3 px-2">&nbsp;Code</th>
                                <th height="18" align="left"  class="py-3 px-2">&nbsp;Description</th>
                                <th height="18" align="right" class="py-3 px-2">Opening&nbsp;</th>
                                <th height="18" align="left"  class="py-3 px-2">&nbsp;</th>
                                <th height="18" align="right" class="py-3 px-2">Debit&nbsp;</th>
                                <th height="18" align="right" class="py-3 px-2">Credit&nbsp;</th>
                                <th height="18" align="right" class="py-3 px-2">Closing&nbsp;</th>
                                <th height="18" align="left"  class="py-3 px-2">&nbsp;</th>
                            <?php if ($renderFlag) : ?>
                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
                            <?php endif; ?>
                            </tr>
                                        
                    <?php
                                $lineno = 8 ;
                                $plevelind = 'Y' ;
                            }
                    ?>
                                    <?php if($plevelind == 'Y' && $params['form_report_type'] != 'C') { ?>
                                        <tr class="fs-14">
                                            <td colspan="1" class="p-2"><b><?php echo '('.$plevelcd.')' ?></b></td>
                                            <td colspan="8" class="p-2"><b><?php echo '&nbsp;-- '.$plevelnm ?></b></td>
                                        </tr>
                                        <tr class="fs-14"><td colspan="9" class="report_detail_text">&nbsp;</td></tr>
                                        <?php $lineno = $lineno + 2; $plevelind = 'N' ; } ?>
                                        <tr class="fs-14">
                                            <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                                            <td align="left"  class="p-2" style="vertical-align:top">&nbsp;<?php echo $report_row['main_ac_code']?></td> 
                                            <td align="left"  class="p-2" style="vertical-align:top">&nbsp;<?php echo $report_row['main_ac_name']?></td> 
                                            <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['opbal'] != 0) {echo number_format(abs($report_row['opbal']),2,'.','') ;} else { echo '--';} ?>&nbsp;</td>
                                            <td align="left"  class="p-2" style="vertical-align:top"><?php if($report_row['opbal'] <  0) { echo 'CR' ; }?></td>
                                            <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['dramt'] != 0) {echo number_format(abs($report_row['dramt']),2,'.','') ;} else { echo '--';}?>&nbsp;</td>
                                            <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['cramt'] != 0) {echo number_format(abs($report_row['cramt']),2,'.','') ;} else { echo '--';} ?>&nbsp;</td>
                                            <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['clbal'] != 0) {echo number_format(abs($report_row['clbal']),2,'.','') ;} else { echo '--';} ?>&nbsp;</td>
                                            <td align="left"  class="p-2" style="vertical-align:top"><?php if($report_row['clbal'] <  0) { echo 'CR' ; }?></td>
                                        <?php if($report_row['main_ac_code'] != '') { ?>
                                            <?php if ($renderFlag) : ?>
                                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
                                                    <td height="20" align="left" class="p-2" >

                                                        <form action="<?= base_url('/finance/general-ledger?display_id=1&menu_id=150220') ?>" method="post" target="_blank" name="actionForm<?= $rowcnt ?>">
                                                            <input type="hidden" name="main_ac_code" value="<?= $report_row['main_ac_code'] ?>">
                                                            <input type="hidden" name="branch_code" value="<?= $params['branch_code'] ?>">
                                                            <input type="hidden" name="date_from" value="<?= $params['start_date'] ?>">
                                                            <input type="hidden" name="date_to" value="<?= $params['end_date'] ?>">
                                                            <input type="hidden" name="company_code" value="<?= $params['company_code'] ?>">
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
                            
                            $bopdramt += ($report_row['opbal'] >= 0) ? $report_row['opbal'] : 0 ; 
                            $bopcramt += ($report_row['opbal'] <  0) ? $report_row['opbal'] : 0 ; 
                            $bptdramt += ($report_row['dramt']) ;
                            $bptcramt += ($report_row['cramt']) ;
                            $bcldramt += ($report_row['clbal'] >= 0) ? $report_row['clbal'] : 0 ; 
                            $bclcramt += ($report_row['clbal'] <  0) ? $report_row['clbal'] : 0 ; 
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $trial_qry[$rowcnt] : $report_row;   
                            $rowcnt = $rowcnt + 1 ;
                        }  
                    ?>
                                    <?php if($params['form_report_type'] != 'C') { ?>                   
                                        <tr class="fs-14"><td colspan="9">&nbsp;</td></tr>
                                        <tr class="fs-14">
                                            <td align="right"   class="p-2" colspan="3" style="background-color: #e2e6506e;"><b> Total</b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format(abs($bopdramt),2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #e2e6506e;"><b><?php echo 'DR' ;  ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format($bptdramt,2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format($bptcramt,2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format(abs($bcldramt),2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #e2e6506e;"><b><?php echo 'DR' ;  ?></b></td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td align="left"   class="p-2" colspan="3">&nbsp;</td>
                                            <td align="right"  class="p-2"><b><?php echo number_format(abs($bopcramt),2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="left"   class="p-2"><b><?php echo 'CR' ;  ?></b></td>
                                            <td align="right"  class="p-2"><b>&nbsp;</td>
                                            <td align="right"  class="p-2"><b>&nbsp;</td>
                                            <td align="right"  class="p-2"><b><?php echo number_format(abs($bclcramt),2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="left"   class="p-2"><b><?php echo 'CR' ;  ?></b></td>
                                        </tr>
                                        <?php 
                                        } 
                                        $lineno = $lineno + 3; 
                                        $topdramt += $bopdramt ;
                                        $topcramt += $bopcramt ;
                                        $tptdramt += $bptdramt ;
                                        $tptcramt += $bptcramt ;
                                        $tcldramt += $bcldramt ;
                                        $tclcramt += $bclcramt ;

                                    if($maxline - $lineno < 3) { $lineno = $maxline + 1 ; }
                    }
                    ?>
                                        <tr class="fs-14"><td colspan="9">&nbsp;</td></tr>
                                        <tr class="fs-14">
                                            <td align="right" class="p-2" colspan="3"  style="background-color: #91d6ec6e;"><b> GRAND TOTAL </b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format(abs($topdramt),2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91d6ec6e;"><b><?php echo 'DR ' ;  ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format($tptdramt,2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format($tptcramt,2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format(abs($tcldramt),2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91d6ec6e;"><b><?php echo 'DR ' ;  ?></b></td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td align="left"   class="p-2" colspan="3">&nbsp;</td>
                                            <td align="right"  class="p-2"><b><?php echo number_format(abs($topcramt),2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="left"   class="p-2"><b><?php echo 'CR ' ;  ?></b></td>
                                            <td align="right"  class="p-2"><b>&nbsp;</td>
                                            <td align="right"  class="p-2"><b>&nbsp;</td>
                                            <td align="right"  class="p-2"><b><?php echo number_format(abs($tclcramt),2,'.','') ; ?></b>&nbsp;</td>
                                            <td align="left"   class="p-2"><b><?php echo 'CR ' ;  ?></b></td>
                                        </tr>
                                </table>
                                </td>
                            </tr>
                        </table> 

            <?php } else if($params['report_otp'] == 'G' && $params['form_report_type'] == 'G') { ?>
            <?php
                $maxline = 80 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tbilamt = 0; 
                $tcolamt = 0; 
                $tbalamt = 0; 
                $topdramt  = 0 ;
                $topcramt  = 0 ;
                $tptdramt  = 0 ;
                $tptcramt  = 0 ;
                $tcldramt  = 0 ;
                $tclcramt  = 0 ;
                $rowcnt     = 1 ;
                $report_row =isset($trial_qry[$rowcnt-1]) ? $trial_qry[$rowcnt-1] : '' ; 
                $report_cnt = $params['trial_cnt'] ;
                while ($rowcnt <= $report_cnt) {
                $bopdramt  = 0; 
                $bopcramt  = 0; 
                $bptdramt  = 0; 
                $bptcramt  = 0; 
                $bcldramt  = 0; 
                $bclcramt  = 0; 
                
                $gbopdramt  = 0;
                $gbptdramt  = 0;
                $gbptcramt  = 0;
                $gbcldramt  = 0;
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['level_code'] ;  
                $plevelnm  = $report_row['level_name'] ;  
                // $pvariablecd  = $report_row['variable_code'] ;
                
                while($plevelcd == $report_row['level_code'] && $report_row['variable_code'] && $rowcnt <= $report_cnt)
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
                    <table width="760" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                            <td colspan="10">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="08%">&nbsp;</td>
                                    <td width="72%">&nbsp;</td>
                                    <td width="08%">&nbsp;</td>
                                    <td width="12%">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text" colspan="4" align="center"><b><u><?php echo strtoupper($params['report_desc'])?></u></b></td>
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
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['end_date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                            <th height="18" width="04%" align="left"  class="py-3 px-2">&nbsp;</th>
                            <th height="18" width="04%" align="left"  class="py-3 px-2">&nbsp;</th>
                            <th height="18" width="04%" align="left"  class="py-3 px-2">&nbsp;Code</th>
                            <th height="18" width="38%" align="left"  class="py-3 px-2">&nbsp;Description</th>
                            <th height="18" width="12%" align="right" class="py-3 px-2">Opening&nbsp;</th>
                            <th height="18" width="03%" align="left"  class="py-3 px-2">&nbsp;</th>
                            <th height="18" width="12%" align="right" class="py-3 px-2">Debit&nbsp;</th>
                            <th height="18" width="12%" align="right" class="py-3 px-2">Credit&nbsp;</th>
                            <th height="18" width="12%" align="right" class="py-3 px-2">Closing&nbsp;</th>
                            <th height="18" width="03%" align="left"  class="py-3 px-2">&nbsp;</th>
                            <?php if ($renderFlag) : ?>
                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
                            <?php endif; ?>   
                        </tr>
                <?php
                            $lineno = 8 ;
                            $plevelind = 'Y' ;
                        }

                        if($plevelind == 'Y' && $params['form_report_type'] != 'C')
                        {
                ?>
                                    <tr class="fs-14">
                                        <td colspan="1" class="p-2"><b><?php echo '('.$plevelcd.'}' ?></b></td>
                                        <td colspan="9" class="p-2"><b><?php echo '&nbsp;-- '.$plevelnm ?></b></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td colspan="10" class="p-2">&nbsp;</td>
                                    </tr>

                                    
                <?php
                        $lineno = $lineno + 4;
                        $plevelind = 'N' ;
                        }
                ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2" style="vertical-align:top"><?php if($report_row['variable_code']=='F') {echo 'FIXED';} else if($report_row['variable_code']=='V') {echo 'VARIABLE' ;} else echo '';?>&nbsp;</td> 
                                        <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                                        <td align="left"  class="p-2" style="vertical-align:top">&nbsp;<?php echo $report_row['main_ac_code']?></td> 
                                        <td align="left"  class="p-2" style="vertical-align:top">&nbsp;<?php echo $report_row['main_ac_name']?></td> 
                                        <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['opbal'] != 0) {echo number_format(abs($report_row['opbal']),2,'.','') ;} else { echo '--';} ?>&nbsp;</td>
                                        <td align="left"  class="p-2" style="vertical-align:top"><?php if($report_row['opbal'] <  0) { echo 'CR' ; }?></td>
                                        <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['dramt'] != 0) {echo number_format(abs($report_row['dramt']),2,'.','') ;} else { echo '--';}?>&nbsp;</td>
                                        <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['cramt'] != 0) {echo number_format(abs($report_row['cramt']),2,'.','') ;} else { echo '--';} ?>&nbsp;</td>
                                        <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['clbal'] != 0) {echo number_format(abs($report_row['clbal']),2,'.','') ;} else { echo '--';} ?>&nbsp;</td>
                                        <td align="left"  class="p-2" style="vertical-align:top"><?php if($report_row['clbal'] <  0) { echo 'CR' ; }?></td>
                                        <?php if($report_row['main_ac_code'] != '') { ?>
                                            <?php if ($renderFlag) : ?>
                                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
                                                    <td height="20" align="left" class="p-2" >

                                                        <form action="<?= base_url('/finance/general-ledger?display_id=1&menu_id=150220') ?>" method="post" target="_blank" name="actionForm<?= $rowcnt ?>">
                                                            <input type="hidden" name="main_ac_code" value="<?= $report_row['main_ac_code'] ?>">
                                                            <input type="hidden" name="branch_code" value="<?= $params['branch_code'] ?>">
                                                            <input type="hidden" name="date_from" value="<?= $params['start_date'] ?>">
                                                            <input type="hidden" name="date_to" value="<?= $params['end_date'] ?>">
                                                            <input type="hidden" name="company_code" value="<?= $params['company_code'] ?>">
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
                                    </tr>
                <?php     
                        $lineno = $lineno + 1;
                        if($report_row['opbal'] >= 0) {$bopdramt = $bopdramt + $report_row['opbal'] ; } else {$bopcramt = $bopcramt + $report_row['opbal'] ; } 
                        if($report_row['clbal'] >= 0) {$bcldramt = $bcldramt + $report_row['clbal'] ; } else {$bclcramt = $bclcramt + $report_row['clbal'] ; } 
                        $bptdramt = $bptdramt + $report_row['dramt'] ;
                        $bptcramt = $bptcramt + $report_row['cramt'] ;
                        //
                        $report_row = ($rowcnt < $report_cnt) ? $trial_qry[$rowcnt] : $report_row;   
                        $rowcnt = $rowcnt + 1 ;
                    } 
                    
                    if($plevelcd == '015') 
                    
                    
                    {  
                    
                ?>	                         
                                    <tr class="fs-14">
                                        <td colspan="9">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="right"   class="p-2" colspan="4" style="background-color: #91d6ec6e;"><b> Group Total</b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format(abs($gbopdramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2" style="background-color: #91d6ec6e;"><b><?php echo 'DR' ;  ?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format($gbptdramt,2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format($gbptcramt,2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format(abs($gbcldramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2" style="background-color: #91d6ec6e;"><b><?php echo 'DR' ;  ?></b></td>
                                    </tr>

                <?php	  
                    }  
                    if($params['form_report_type'] != 'C')
                    {

                ?>                   
                                    <tr>
                                    <td colspan="9">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="right"   class="p-2" colspan="4" style="background-color: #e2e6506e;"><b> Total</b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format(abs($bopdramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2" style="background-color: #e2e6506e;"><b><?php echo 'DR' ;  ?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format($bptdramt,2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format($bptcramt,2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format(abs($bcldramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2" style="background-color: #e2e6506e;"><b><?php echo 'DR' ;  ?></b></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2" colspan="4">&nbsp;</td>
                                        <td align="right"  class="p-2"><b><?php echo number_format(abs($bopcramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2"><b><?php echo 'CR' ;  ?></b></td>
                                        <td align="right"  class="p-2"><b>&nbsp;</td>
                                        <td align="right"  class="p-2"><b>&nbsp;</td>
                                        <td align="right"  class="p-2"><b><?php echo number_format(abs($bclcramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2"><b><?php echo 'CR' ;  ?></b></td>
                                    </tr>
                <?php
                    }
                    $lineno = $lineno + 3;
                    $topdramt = $topdramt + $bopdramt ;
                    $topcramt = $topcramt + $bopcramt ;
                    $tptdramt = $tptdramt + $bptdramt ;
                    $tptcramt = $tptcramt + $bptcramt ;
                    $tcldramt = $tcldramt + $bcldramt ;
                    $tclcramt = $tclcramt + $bclcramt ;

                    if($maxline - $lineno < 3) { $lineno = $maxline + 1 ; }

                    }
                ?>
                                    <tr class="fs-14">
                                        <td colspan="9">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="center" class="p-2" colspan="4" style="background-color: #91d6ec6e;"><b> GRAND TOTAL </b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format(abs($topdramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2" style="background-color: #91d6ec6e;"><b><?php echo 'DR ' ;  ?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format($tptdramt,2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format($tptcramt,2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format(abs($tcldramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2" style="background-color: #91d6ec6e;"><b><?php echo 'DR ' ;  ?></b></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2" colspan="4">&nbsp;</td>
                                        <td align="right"  class="p-2"><b><?php echo number_format(abs($topcramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2"><b><?php echo 'CR ' ;  ?></b></td>
                                        <td align="right"  class="p-2"><b>&nbsp;</td>
                                        <td align="right"  class="p-2"><b>&nbsp;</td>
                                        <td align="right"  class="p-2"><b><?php echo number_format(abs($tclcramt),2,'.','') ; ?></b>&nbsp;</td>
                                        <td align="left"   class="p-2"><b><?php echo 'CR ' ;  ?></b></td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
            <?php } ?>
        <?php } else if($params['comparative_ind'] == 'Y') { ?>
            <?php
                $maxline   = 54 ;
                $lineno    = 0 ;
                $pageno    = 0 ;
                $topdramt  = 0; 
                $topcramt  = 0; 
                $tptdramt  = 0; 
                $tptcramt  = 0; 
                $tcldramt  = 0; 
                $tclcramt  = 0; 
                $tlcldramt = 0; 
                $tlclcramt = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($trial_qry[$rowcnt-1]) ? $trial_qry[$rowcnt-1] : '' ;   
                $report_cnt = $params['trial_cnt'] ;
                while ($rowcnt <= $report_cnt) {
                
                $bopdramt  = 0; 
                $bopcramt  = 0; 
                $bptdramt  = 0; 
                $bptcramt  = 0; 
                $bcldramt  = 0; 
                $bclcramt  = 0; 
                $blcldramt = 0; 
                $blclcramt = 0; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['level_code'] ;  
                $plevelnm  = $report_row['level_name'] ;  
                while($plevelcd == $report_row['level_code'] && $rowcnt <= $report_cnt)
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
                            <td colspan="12">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    
                                    <tr><td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td></tr>
                                    <tr><td class="report_label_text" colspan="4" align="center"><b><u><?php echo strtoupper($params['report_desc'])?></u></b></td></tr>
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
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['end_date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th height="18" width="03%" align="left"  class="p-2">&nbsp;Code&nbsp;<br>&nbsp;</th>
                            <th height="18" width="35%" align="left"  class="p-2">&nbsp;Description<br>&nbsp;</th>
                            <th height="18" width="11%" align="right" class="p-2" >Opening&nbsp;<br>(<b><?php echo $params['start_date']?></b>)&nbsp;</th>
                            <th height="18" width="02%" align="left"  class="p-2">&nbsp;<br>&nbsp;</th>
                            <th height="18" width="11%" align="right" class="p-2">Debit&nbsp;<br>(<b>this Period</b>)&nbsp;</th>
                            <th height="18" width="11%" align="right" class="p-2">Credit&nbsp;<br>(<b>this Period</b>)&nbsp;</th>
                            <th height="18" width="11%" align="right" class="p-2" >Closing&nbsp;<br>(<b><?php echo $params['end_date']?></b>)&nbsp;</th>
                            <th height="18" width="02%" align="left"  class="p-2">&nbsp;<br>&nbsp;</th>
                            <th height="18" width="01%" align="left"  class="p-2">&nbsp;<br>&nbsp;</th>
                            <th height="18" width="11%" align="right" class="p-2">Closing&nbsp;<br>(<b><?php echo date_conv($params['lend_date_ymd'])?></b>)&nbsp;</th>
                            <th height="18" width="02%" align="left"  class="p-2">&nbsp;<br>&nbsp;</th> 
                            <?php if ($renderFlag) : ?>
                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
                            <?php endif; ?>    
                        </tr>
                                    
            <?php
                        $lineno = 9 ;
                        $plevelind = 'Y' ;
                    }

                    if($plevelind == 'Y' && $params['form_report_type'] != 'C')
                    {
            ?>
                                    <tr class="fs-14"><td colspan="11" class="p-2">&nbsp;<b><?php echo '('.$plevelcd.')'.'&nbsp;-- '.$plevelnm ?></b></td></tr>
                                    <tr class="fs-14"><td colspan="11" class="p-2">&nbsp;</td></tr>
            <?php
                    $lineno = $lineno + 2;
                    $plevelind = 'N' ;
                    }
            ?>
                                    <tr class="fs-14">
                                        <td height="18" align="left"  class="p-2"     style="vertical-align:top">&nbsp;<?php echo $report_row['main_ac_code']?></td> 
                                        <td height="18" align="left"  class="p-2"      style="vertical-align:top">&nbsp;<font size="1"><?php echo $report_row['main_ac_name']?></font></td> 
                                        <td height="18" align="right" class="p-2"  style="vertical-align:top"><?php if($report_row['opbal'] != 0) {echo number_format(abs($report_row['opbal']),2,'.',',') ;} else { echo '-';} ?>&nbsp;</td>
                                        <td height="18" align="left"  class="p-2"      style="vertical-align:top"><?php if($report_row['opbal'] <  0) { echo 'CR' ; }?>&nbsp;</td>
                                        <td height="18" align="right" class="p-2"      style="vertical-align:top"><?php if($report_row['dramt'] != 0) {echo number_format(abs($report_row['dramt']),2,'.',',') ;} else { echo '-';}?>&nbsp;</td>
                                        <td height="18" align="right" class="p-2"      style="vertical-align:top"><?php if($report_row['cramt'] != 0) {echo number_format(abs($report_row['cramt']),2,'.',',') ;} else { echo '-';} ?>&nbsp;</td>
                                        <td height="18" align="right" class="p-2"  style="vertical-align:top"><?php if($report_row['clbal'] != 0) {echo number_format(abs($report_row['clbal']),2,'.',',') ;} else { echo '-';} ?>&nbsp;</td>
                                        <td height="18" align="left"  class="p-2"      style="vertical-align:top"><?php if($report_row['clbal'] <  0) { echo 'CR' ; }?>&nbsp;</td>
                                        <td height="18" align="left"  class="p-2"    style="vertical-align:top">&nbsp;</td>
                                        <td height="18" align="right" class="p-2"      style="vertical-align:top"><?php if($report_row['lastclbal'] != 0) {echo number_format(abs($report_row['lastclbal']),2,'.',',') ;} else { echo '-';} ?>&nbsp;</td>
                                        <td height="18" align="left"  class="p-2"      style="vertical-align:top"><?php if($report_row['lastclbal'] <  0) { echo 'CR' ; }?>&nbsp;</td>
                                        <?php if($report_row['main_ac_code'] != '') { ?>
                                            <?php if ($renderFlag) : ?>
                                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
                                                    <td height="20" align="left" class="p-2" >

                                                        <form action="<?= base_url('/finance/general-ledger?display_id=1&menu_id=150220') ?>" method="post" target="_blank" name="actionForm<?= $rowcnt ?>">
                                                            <input type="hidden" name="main_ac_code" value="<?= $report_row['main_ac_code'] ?>">
                                                            <input type="hidden" name="branch_code" value="<?= $params['branch_code'] ?>">
                                                            <input type="hidden" name="date_from" value="<?= $params['start_date'] ?>">
                                                            <input type="hidden" name="date_to" value="<?= $params['end_date'] ?>">
                                                            <input type="hidden" name="company_code" value="<?= $params['company_code'] ?>">
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
                    $lineno = $lineno + 1.5;
                    if($report_row['opbal']     >= 0) {$bopdramt  = $bopdramt  + $report_row['opbal']     ; } else {$bopcramt  = $bopcramt  + $report_row['opbal']     ; } 
                    if($report_row['clbal']     >= 0) {$bcldramt  = $bcldramt  + $report_row['clbal']     ; } else {$bclcramt  = $bclcramt  + $report_row['clbal']     ; } 
                    if($report_row['lastclbal'] >= 0) {$blcldramt = $blcldramt + $report_row['lastclbal'] ; } else {$blclcramt = $blclcramt + $report_row['lastclbal'] ; } 
                    $bptdramt = $bptdramt + $report_row['dramt'] ;
                    $bptcramt = $bptcramt + $report_row['cramt'] ;
                    //
                    $report_row = ($rowcnt < $report_cnt) ? $trial_qry[$rowcnt] : $report_row;  
                    $rowcnt = $rowcnt + 1 ;
                }  
                if($params['form_report_type'] != 'C')
                {
            ?>                   
                                    <!-- <tr><td colspan="11">&nbsp;</td></tr>-->
                                    <tr class="fs-14">
                                        <td height="18" align="right"  class="p-2" colspan="2" style="background-color: #e2e6506e;"><b> Total :</b>&nbsp;</td>
                                        <td height="18" align="right"  class="p-2"  style="background-color: #e2e6506e;"><b><?php echo number_format(abs($bopdramt),2,'.',',') ; ?></b>&nbsp;</td>
                                        <td height="18" align="left"   class="p-2" style="background-color: #e2e6506e;" ><b><?php echo 'DR' ;  ?></b></td>
                                        <td height="18" align="right"  class="p-2"  style="background-color: #e2e6506e;"><b><?php echo number_format($bptdramt,2,'.',',') ; ?></b>&nbsp;</td>
                                        <td height="18" align="right"  class="p-2"  style="background-color: #e2e6506e;"><b><?php echo number_format($bptcramt,2,'.',',') ; ?></b>&nbsp;</td>
                                        <td height="18" align="right"  class="p-2"  style="background-color: #e2e6506e;"><b><?php echo number_format(abs($bcldramt),2,'.',',') ; ?></b>&nbsp;</td>
                                        <td height="18" align="left"   class="p-2" style="background-color: #e2e6506e;"><b><?php echo 'DR' ;  ?></b></td>
                                        <td height="18" align="left"   class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                        <td height="18" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php echo number_format(abs($blcldramt),2,'.',',') ; ?></b>&nbsp;</td>
                                        <td height="18" align="left"   class="p-2" style="background-color: #e2e6506e;"><b><?php echo 'DR' ;  ?></b></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="18" align="left"   class="p-2" colspan="2">&nbsp;</td>
                                        <td height="18" align="right"  class="p-2"><b><?php echo number_format(abs($bopcramt),2,'.',',') ; ?></b>&nbsp;</td>
                                        <td height="18" align="left"   class="p-2"><b><?php echo 'CR' ;  ?></b></td>
                                        <td height="18" align="right"  class="p-2">&nbsp;</td>
                                        <td height="18" align="right"  class="p-2">&nbsp;</td>
                                        <td height="18" align="right"  class="p-2"><b><?php echo number_format(abs($bclcramt),2,'.',',') ; ?></b>&nbsp;</td>
                                        <td height="18" align="left"   class="p-2"><b><?php echo 'CR' ;  ?></b></td>
                                        <td height="18" align="left"   class="p-2">&nbsp;</td>
                                        <td height="18" align="right"  class="p-2"><b><?php echo number_format(abs($blclcramt),2,'.',',') ; ?></b>&nbsp;</td>
                                        <td height="18" align="left"   class="p-2"><b><?php echo 'CR' ;  ?></b></td>
                                    </tr>
            <?php
                }
                $lineno = $lineno + 3;
                $topdramt  = $topdramt  + $bopdramt ;
                $topcramt  = $topcramt  + $bopcramt ;
                $tptdramt  = $tptdramt  + $bptdramt ;
                $tptcramt  = $tptcramt  + $bptcramt ;
                $tcldramt  = $tcldramt  + $bcldramt ;
                $tclcramt  = $tclcramt  + $bclcramt ;
                $tlcldramt = $tlcldramt + $blcldramt ;
                $tlclcramt = $tlclcramt + $blclcramt ;

                if($maxline - $lineno < 3) { $lineno = $maxline + 1 ; }

                }
            ?>
                                    <tr class="fs-14">
                                    <td height="18" align="center" class="p-2" colspan="2">&nbsp;</td>
                                    <td height="18" align="right"  class="p-2" >&nbsp;</td>
                                    <td height="18" align="left"   class="p-2">&nbsp;</td>
                                    <td height="18" align="right"  class="p-2">&nbsp;</td>
                                    <td height="18" align="right"  class="p-2">&nbsp;</td>
                                    <td height="18" align="right"  class="p-2" >&nbsp;</td>
                                    <td height="18" align="left"   class="p-2">&nbsp;</td>
                                    <td height="18" align="left"   class="p-2" >&nbsp;</td>
                                    <td height="18" align="right"  class="p-2" >&nbsp;</td>
                                    <td height="18" align="left"   class="p-2">&nbsp;</td>
                                    </tr>
                                    
                                    <tr class="fs-14">
                                    <td height="18" align="right" class="p-2" colspan="2" style="background-color: #91d6ec6e;"><b> GRAND TOTAL :</b>&nbsp;</td>
                                    <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format(abs($topdramt),2,'.',',') ; ?></b>&nbsp;</td>
                                    <td height="18" align="left"   class="p-2" style="background-color: #91d6ec6e;"><b><?php echo 'DR ' ;  ?></b></td>
                                    <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format($tptdramt,2,'.',',') ; ?></b>&nbsp;</td>
                                    <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format($tptcramt,2,'.',',') ; ?></b>&nbsp;</td>
                                    <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format(abs($tcldramt),2,'.',',') ; ?></b>&nbsp;</td>
                                    <td height="18" align="left"   class="p-2" style="background-color: #91d6ec6e;"><b><?php echo 'DR ' ;  ?></b></td>
                                    <td height="18" align="left"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                    <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php echo number_format(abs($tlcldramt),2,'.',',') ; ?></b>&nbsp;</td>
                                    <td height="18" align="left"   class="p-2" style="background-color: #91d6ec6e;"><b><?php echo 'DR ' ;  ?></b></td>
                                    </tr>
                                    <tr class="fs-14">
                                    <td height="18" align="left"   class="p-2" colspan="2">&nbsp;</td>
                                    <td height="18" align="right"  class="p-2"><b><?php echo number_format(abs($topcramt),2,'.',',') ; ?></b>&nbsp;</td>
                                    <td height="18" align="left"   class="p-2"><b><?php echo 'CR ' ;  ?></b></td>
                                    <td height="18" align="right"  class="p-2">&nbsp;</td>
                                    <td height="18" align="right"  class="p-2">&nbsp;</td>
                                    <td height="18" align="right"  class="p-2"><b><?php echo number_format(abs($tclcramt),2,'.',',') ; ?></b>&nbsp;</td>
                                    <td height="18" align="left"   class="p-2"><b><?php echo 'CR ' ;  ?></b></td>
                                    <td height="18" align="left"   class="p-2">&nbsp;</td>
                                    <td height="18" align="right"  class="p-2"><b><?php echo number_format(abs($tlclcramt),2,'.',',') ; ?></b>&nbsp;</td>
                                    <td height="18" align="left"   class="p-2"><b><?php echo 'CR ' ;  ?></b></td>
                                    </tr>
                                    <tr><td colspan="11">&nbsp;</td></tr>
                                    <tr><td colspan="11">&nbsp;</td></tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
        <?php } ?>
    </main>
<?php } ?>
<?= $this->endSection() ?>