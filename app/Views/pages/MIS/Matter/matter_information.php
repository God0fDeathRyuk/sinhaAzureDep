<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($matrinfo_qry))) { ?>
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>My Matter Information</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="matterInformation" name="matterInformation" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-48 float-start datepicker" name="start_date" value="<?php echo $data['curr_fyrsdt']?>" onBlur="make_date(this)">
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-48 float-start datepicker" name="end_date" value="<?php echo date('d-m-Y')?>" onBlur="make_date(this)"required>
                        </div>
                        
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>										
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" name="client_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name </label>
                            <input type="text" class="form-control w-100 float-start" id="clientName" name="client_name" readonly>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc'], ['matter_desc'], 'matter_code')" name="matter_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Desc </label>
                            <input type="text" class="form-control w-100 float-start" id="matterDesc" name="matter_desc" readonly>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" name="court_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Name </label>
                            <input type="text" class="form-control w-100 float-start" id="courtName" name="court_name" readonly>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Initial Code</label>
                            <input type="text" class="form-control w-100 float-start" id="initialCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'initial_code', ['initialName'], ['initial_name'], 'initial_code')" name="initial_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('initial_code', '<?= $displayId['initial_help_id'] ?>', 'initialCode', ['initialName'], ['initial_name'], 'initial_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Initial Name </label>
                            <input type="text" class="form-control w-100 float-start" name="initial_name" id="initialName" readonly>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Seq <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="opened_by" required>
                                <option value="E">Date of Entry</option>
                                <option value="F">Date of Filing</option>
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="output_type" required >
                                <option value="Report">View Report</option>
                                <option value="Pdf">Download PDF</option>
                                <option value="Excel">Download Excel</option>
                            </select>
                        </div>							
                        
                        <button type="submit" class="btn btn-primary cstmBtn mt-28 ms-2">Report</button>				
                        <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-28 ms-2">Reset</button>
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
                <a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
            <?php endif; ?>
        </div> 
        <?php if($opened_by == 'E') { ?> 
            <?php
                $maxline = 58 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $rowcnt     = 1 ;
                $report_row = isset($matrinfo_qry[$rowcnt-1]) ? $matrinfo_qry[$rowcnt-1] : '' ; 
                $report_cnt = $params['matrinfo_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $xsrl   = 0;
                $padate = $report_row['prepared_on'] ;
                while ($padate == $report_row['prepared_on'] && $rowcnt <= $report_cnt)
                {
                    if ($lineno == 0 || $lineno >= $maxline)
                    {
                    if($lineno >= $maxline)
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
                            <td colspan="7">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
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
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc']?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Client</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['client_code'] != '%') { echo strtoupper($params['client_name']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Matter</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['matter_code'] != '%') { echo strtoupper($params['matter_desc']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Court</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['court_code'] != '%') { echo strtoupper($params['court_name']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;<i>(By Entry Date)</i></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Initial</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['initial_code'] != '%') { echo strtoupper($params['initial_name']) ; } else { echo 'ALL' ; } ?></b></td>
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
                            <th height="18" width="08%" align="left"  class="py-3 px-2">&nbsp;Date</th>
                            <th height="18" width="05%" align="left"  class="py-3 px-2">&nbsp;Matter</th>
                            <th height="18" width="30%" align="left"  class="py-3 px-2">&nbsp;Client/Matter Desc/Court</th>
                            <th height="18" width="15%" align="left"  class="py-3 px-2">&nbsp;Notice No/Dt</th>
                            <th height="18" width="25%" align="left"  class="py-3 px-2">&nbsp;Appear For/Ref Type/Ref No</th>
                            <th height="18" width="05%" align="left"  class="py-3 px-2">&nbsp;Opp/App</th>
                            <th height="18" width="12%" align="left"  class="py-3 px-2">&nbsp;File Date/Amount</th>
                        </tr>
                        
            <?php
                        $lineno = 11 ;
                    }
            ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;<?php if($report_row['prepared_on'] != '' && $report_row['prepared_on'] != '0000-00-00') { echo date_conv($report_row['prepared_on'],'-') ; }?></td> 
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['matter_code']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['client_name']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['notice_no']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['appearing_for_name']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php if($report_row['apply_oppose_ind']=='A') { echo 'APPLY'; } else { echo 'OPPOSE' ; }?></td>
                                        <td align="right" class="p-2"><?php if($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing'],'-') ; } ?>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['initial_code']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['matter_desc1']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php if($report_row['notice_date'] != '' && $report_row['notice_date'] != '0000-00-00') { echo date_conv($report_row['notice_date'],'-') ; }?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['reference_type_name']?></td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="right" class="p-2"><?php echo $report_row['stake_amount']?>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2" style="word-break:break-all;">&nbsp;<?php echo $report_row['matter_desc2']?></td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2" style="word-break:break-all;">&nbsp;<?php echo $report_row['reference_desc']?></td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="right" class="p-2">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['court_name']?></td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="right" class="p-2">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td colspan="7"><hr size="1" noshade></td>
                                    </tr>
            <?php     
                    $lineno = $lineno + 7 ; 
                    $report_row = ($rowcnt < $report_cnt) ? $matrinfo_qry[$rowcnt] : $report_row; 
                    $rowcnt = $rowcnt + 1 ;
                }  
                }
            ?>
                            </table>
                            </td>
                        </tr>
                    </table> 
        <?php } else if($opened_by == 'F') { ?>
            <?php
                $maxline = 58 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $rowcnt     = 1 ;
                $report_row = isset($matrinfo_qry[$rowcnt-1]) ? $matrinfo_qry[$rowcnt-1] : '' ;  
                $report_cnt = $params['matrinfo_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $xsrl   = 0;
                $padate = $report_row['prepared_on'] ;
                while ($padate == $report_row['prepared_on'] && $rowcnt <= $report_cnt)
                {
                    if ($lineno == 0 || $lineno >= $maxline)
                    {
                    if($lineno >= $maxline)

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
                            <td colspan="7">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="">&nbsp;</td>
                                    <td width="">&nbsp;</td>
                                    <td width="">&nbsp;</td>
                                    <td width="">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
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
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc']?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Client</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['client_code'] != '%') { echo strtoupper($params['client_name']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Matter</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['matter_code'] != '%') { echo strtoupper($params['matter_desc']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Court</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['court_code'] != '%') { echo strtoupper($params['court_name']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;<i>(By Filing Date)</i></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Initial</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['initial_code'] != '%') { echo strtoupper($params['initial_name']) ; } else { echo 'ALL' ; } ?></b></td>
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
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Date</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Matter</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Client/Matter Desc/Court</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Notice No/Dt</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Appear For/Ref Type/Ref No</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Opp/App</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Entry Dt/Amount</th>
                        </tr>
                        
            <?php
                        $lineno = 11 ;
                    }
            ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;<?php if($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing'],'-') ; }?></td> 
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['matter_code']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['client_name']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['notice_no']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['appearing_for_name']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php if($report_row['apply_oppose_ind']=='A') { echo 'APPLY'; } else { echo 'OPPOSE' ; }?></td>
                                        <td align="right" class="p-2"><?php if($report_row['prepared_on'] != '' && $report_row['prepared_on'] != '0000-00-00') { echo date_conv($report_row['prepared_on'],'-') ; } ?>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['initial_code']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['matter_desc1']?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php if($report_row['notice_date'] != '' && $report_row['notice_date'] != '0000-00-00') { echo date_conv($report_row['notice_date'],'-') ; }?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['reference_type_name']?></td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="right" class="p-2"><?php echo $report_row['stake_amount']?>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['matter_desc2']?></td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['reference_desc']?></td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="right" class="p-2">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['court_name']?></td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="right" class="p-2">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td colspan="7"><hr size="1" noshade></td>
                                    </tr>
            <?php     
                    $lineno = $lineno + 7 ; 
                    $report_row = ($rowcnt < $report_cnt) ? $matrinfo_qry[$rowcnt] : $report_row;
                    $rowcnt = $rowcnt + 1 ;
                }  
                }
            ?>
                            </table>
                            </td>
                        </tr>
                    </table> 
        <?php } ?>
    </main>
<?php } ?>
<script>
    function setValue(e) {
        e.preventDefault();
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.matterInformation.start_date.value.substring(6,10)+document.matterInformation.start_date.value.substring(3,5)+document.matterInformation.start_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.matterInformation.start_date.focus()}, 500) });
            return false;
        }
        else if (document.matterInformation.end_date.value.substring(6,10)+document.matterInformation.end_date.value.substring(3,5)+document.matterInformation.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.matterInformation.end_date.focus()}, 500) });
            return false;
        }
        else if (document.matterInformation.start_date.value.substring(6,10)+document.matterInformation.start_date.value.substring(3,5)+document.matterInformation.start_date.value.substring(0,2)>document.matterInformation.end_date.value.substring(6,10)+document.matterInformation.end_date.value.substring(3,5)+document.matterInformation.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'Period End Date must be greater than Period Start Date' }).then((result) => { setTimeout(() => {document.matterInformation.end_date.focus()}, 500) });
            return false;
        } 

        document.matterInformation.submit();
    }
</script>
<?= $this->endSection() ?>