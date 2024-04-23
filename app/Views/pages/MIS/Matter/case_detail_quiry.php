<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($case_qry))) { ?>

    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>Case Detailed</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="caseDetailQuiry" name="caseDetailQuiry" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-48 float-start datepicker" name="start_date" value="<?php echo $data['curr_fyrsdt']?>" onBlur="make_date(this)">
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-48 float-start datepicker" name="end_date" value="<?php echo date('d-m-Y')?>" onBlur="make_date(this)" required>
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
                        
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Options <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="desc_ind" required >
                                <option value="N">Without Particulars</option>
                                <option value="Y">With Particulars</option>
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Seq <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="report_seq" required >
                                <option value="1">Activity Date-wise</option>
                                <option value="2">Matter/Activity Date-wise</option>
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
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Forwarding <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="forwarding_inp" required >
                                <option value="A">All</option>
                                <option value="Y">Yes</option>
                                <option value="N">No</option>
                            </select>
                        </div>				
                        
                        <button type="submit" class="btn btn-primary cstmBtn mt-3 ms-2">Proceed</button>				
                        <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
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
        <?php if($report_seq == '1') { ?>
            <?php
                $maxline    = 40 ;
                $tot_char   = 110 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $rowcnt     = 1 ;
                $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; 
                $report_cnt = $params['case_cnt'] ;
                $xsrl       = 0 ;
                while ($rowcnt <= $report_cnt)
                {
                    $xsrl          = $xsrl+1;
                    //
                    $hdr_desc      = $report_row['header_desc'] . chr(13);
                    $header_desc   = wordwrap($hdr_desc, $tot_char, "\n");
                    $header_array  = explode("\n",$header_desc);
                    $array_row     = count($header_array);
                    //
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
                    <table width="990" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                        <td colspan="6">    
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
                                <td class="report_label_text" align="right">Forwarding&nbsp;</td>
                                <td class="report_label_text">&nbsp;<b><?php echo $params['forwarding_ind']; ?></b></td>
                            </tr>
                            </table>
                        </td>    
                        </tr>
                        <tr class="fs-14">
                            <th width="03%" align="left"  class="py-3 px-2">&nbsp;Sl</th>
                            <th width="16%" align="left"  class="py-3 px-2">&nbsp;Dt/Mtr/F-Dt/Amt</th>
                            <th width="30%" align="left"  class="py-3 px-2">&nbsp;Client/Matter Description/Judge/Court/Reference</th>
                            <th width="19%" align="left"  class="py-3 px-2">&nbsp;Fix For (The Day)</th>
                            <th width="16%" align="left"  class="py-3 px-2">&nbsp;Next Dt/Fix For</th>
                            <th width="16%" align="left"  class="py-3 px-2">&nbsp;Prev Dt/Fix For</th>
                        </tr>
                <?php
                    $lineno = 10 ;
                    }	
                    $day_fixed_for = get_fixed_for($report_row['matter_code'],$report_row['activity_date'])	;
                ?> 
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2"><?php echo $xsrl ?></td> 
                        <td height="18" align="left"  class="p-2"><?php echo date_conv($report_row['activity_date'],'-')?></td> 
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['client_name']?></td>
                        <td height="18" align="left"  class="p-2"><?php echo $day_fixed_for?></td>
                        <td height="18" align="left"  class="p-2"><?php echo date_conv($report_row['next_date'],'-')?></td>
                        <td height="18" align="left"  class="p-2"><?php echo date_conv($report_row['prev_date'],'-')?></td>
                    </tr>
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2">&nbsp;</td> 
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['matter_code']?></td> 
                        <td height="18" align="left"  class="p-2" colspan="2"><?php echo $report_row['matter_desc']?></td>
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['next_fixed_for']?></td>
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['prev_fixed_for']?></td>
                    </tr>
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2">&nbsp;</td> 
                        <td height="18" align="left"  class="p-2"><?php if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing'],'-') ; } else { echo '&nbsp;' ; } ?></td> 
                        <td height="18" align="left"  class="p-2" colspan="2"><?php echo $report_row['judge_name']?></td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                    </tr>
                <?php 
                    $lineno = $lineno + 3 ; 
                    if($report_row['stake_amount'] != '' || $report_row['court_name'] != '') 
                    {  
                ?> 
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2">&nbsp;</td> 
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['stake_amount'] ?></td> 
                        <td height="18" align="left"  class="p-2" colspan="2"><?php echo $report_row['court_name']?></td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                    </tr>
                    <?php $lineno = $lineno + 1 ; } ?>
                    <?php if($report_row['reference_desc'] != '') {  ?> 
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2">&nbsp;</td> 
                        <td height="18" align="left"  class="p-2">{<?php echo $report_row['serial_no']?>}</td> 
                        <td height="18" align="left"  class="p-2" colspan="2"><?php echo $report_row['reference_desc']?></td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                    </tr>
                    <?php $lineno = $lineno + 1 ; } ?> 
                    <?php if($params['desc_ind'] == 'Y') {  ?>
                    <tr class="fs-14">
                        <td align="right" class="p-2" style="text-align:justify; font:Courier; font-family:Courier;" colspan="2"><i>Particulars</i>&nbsp;</td> 
                        <td align="left"  class="p-2" colspan="4">&nbsp;</td>
                    </tr>
                    <?php 
                    $lineno = $lineno + 1 ; 
                    for($i=0;$i<$array_row;$i++)
                    {
                        $header_desc = text_justify(trim(nl2br(stripslashes($header_array[$i]))),$tot_char);
                        $header_desc = str_replace("<br />",'',$header_desc);
                ?>
                        <tr class="fs-14">
                            <td align="right" class="p-2" colspan="2">&nbsp;</td> 
                            <td class="p-2" style="font:Courier; font-family:Courier;" colspan="4"><i><?php echo $header_desc;?></i></td>
                        </tr>
                <?php
                        $lineno = $lineno + 1 ;

                        if($lineno >= $maxline)
                        { 
                ?>
                                </table>
                        </td>
                            </tr>
                            </table>
                        <BR CLASS="pageEnd"> 
                <?php
                        $pageno = $pageno + 1 ;
                ?>
                        <table width="990" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                            <tr>
                            <td colspan="6">    
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
                                    <td class="report_label_text" align="right">Forwarding&nbsp;</td>
                                <td class="report_label_text">&nbsp;<b><?php echo $params['forwarding_ind']; ?></b></td> </tr>
                                </table>
                            </td>    
                            </tr>
                            <tr class="fs-14">
                                <th width="03%" align="left"  class="py-3 px-2">&nbsp;Sl</th>
                                <th width="10%" align="left"  class="py-3 px-2">&nbsp;Dt/Mtr/F-Dt/Amt</th>
                                <th width="36%" align="left"  class="py-3 px-2">&nbsp;Client/Matter Description/Judge/Court/Reference</th>
                                <th width="19%" align="left"  class="py-3 px-2">&nbsp;Fix For (The Day)</th>
                                <th width="16%" align="left"  class="py-3 px-2">&nbsp;Next Dt/Fix For</th>
                                <th width="16%" align="left"  class="py-3 px-2">&nbsp;Prev Dt/Fix For</th>
                            </tr>
                <?php
                        $lineno = 10 ;
                        }
                    }
                    } 
                ?>
                    <tr class="fs-14">
                        <td colspan="6">&nbsp;<hr noshade="noshade"></td>
                    </tr>
                <?php     
                    $lineno = $lineno + 1 ; 
                    if ($maxline - $lineno < 5) { $lineno = $maxline ; }  
                    $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row; 
                    $rowcnt = $rowcnt + 1 ;
                }
                ?>
                    </table>
                    </td>
                </tr>
                </table> 
        <?php } else if($report_seq == '2') { ?>
            <?php
                $maxline    = 40 ;
                $tot_char   = 110 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $rowcnt     = 1 ;
                $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; 
                $report_cnt = $params['case_cnt'] ;
                $xsrl       = 0 ;
                while ($rowcnt <= $report_cnt)
                {
                    $xsrl          = $xsrl+1;

                    $hdr_desc      = $report_row['header_desc'].chr(13);
                    $header_desc   = wordwrap($hdr_desc, $tot_char, "\n");
                    $header_array  = explode("\n",$header_desc);
                    $array_row     = count($header_array);

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
                    <table width="990" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                        <td colspan="6">    
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
                                <td class="report_label_text" align="right">Forwarding&nbsp;</td>
                                <td class="report_label_text">&nbsp;<b><?php echo $params['forwarding_ind']; ?></b></td>
                            </tr>
                            </table>
                        </td>    
                        </tr>
                        <tr>
                        <td colspan="4" class="grid_header">
                            <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                            <tr class="fs-14">
                                <th width="03%" align="left"  class="py-3 px-2">&nbsp;Sl</th>
                                <th width="16%" align="left"  class="py-3 px-2">&nbsp;Dt/Mtr/F-Dt/Amt</th>
                                <th width="30%" align="left"  class="py-3 px-2">&nbsp;Client/Matter Description/Judge/Court/Reference</th>
                                <th width="19%" align="left"  class="py-3 px-2">&nbsp;Fix For (The Day)</th>
                                <th width="16%" align="left"  class="py-3 px-2">&nbsp;Next Dt/Fix For</th>
                                <th width="16%" align="left"  class="py-3 px-2">&nbsp;Prev Dt/Fix For</th>
                            </tr>
                <?php
                    $lineno = 10 ;
                    }
                    //---	
                    $day_fixed_for = get_fixed_for($report_row['matter_code'],$report_row['activity_date'])	;
                ?>
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2"><?php echo $xsrl ?></td> 
                        <td height="18" align="left"  class="p-2"><?php echo date_conv($report_row['activity_date'],'-')?></td> 
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['client_name']?></td>
                        <td height="18" align="left"  class="p-2"><?php echo $day_fixed_for?></td>
                        <td height="18" align="left"  class="p-2"><?php echo date_conv($report_row['next_date'],'-')?></td>
                        <td height="18" align="left"  class="p-2"><?php echo date_conv($report_row['prev_date'],'-')?></td>
                    </tr>
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2">&nbsp;</td> 
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['matter_code']?></td> 
                        <td height="18" align="left"  class="p-2" colspan="2"><?php echo $report_row['matter_desc']?></td>
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['next_fixed_for']?></td>
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['prev_fixed_for']?></td>
                    </tr>
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2">&nbsp;</td> 
                        <td height="18" align="left"  class="p-2"><?php if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing'],'-') ; } else { echo '&nbsp;' ; } ?></td> 
                        <td height="18" align="left"  class="p-2" colspan="2"><?php echo $report_row['judge_name']?></td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                        <td height="18" align="left"  class="p-2"><?php echo isset($report_row['serial_no']) ? $report_row['serial_no'] : ''?></td>
                    </tr>
                <?php 
                    $lineno = $lineno + 3 ; 
                ?>
                <?php 
                    if($report_row['stake_amount'] != '') //|| $report_row['court_name'] != '') 
                    {  
                ?> 
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2">&nbsp;</td> 
                        <td height="18" align="left"  class="p-2"><?php echo $report_row['stake_amount'] ?></td> 
                        <td height="18" align="left"  class="p-2" colspan="2"><?php echo isset($report_row['court_name']) ? $report_row['court_name'] : ''?></td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                    </tr>
                <?php 
                    $lineno = $lineno + 1 ; 
                    } 
                ?>
                <?php 
                    if($report_row['reference_desc'] != '') 
                    {  
                ?> 
                    <tr class="fs-14">
                        <td height="18" align="left"  class="p-2">&nbsp;</td> 
                        <td height="18" align="left"  class="p-2">&nbsp;</td> 
                        <td height="18" align="left"  class="p-2" colspan="2"><?php echo $report_row['reference_desc']?></td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                        <td height="18" align="left"  class="p-2">&nbsp;</td>
                    </tr>
                <?php 
                    $lineno = $lineno + 1 ; 
                    } 
                ?>
                <?php 
                    if($params['desc_ind'] == 'Y') 
                    {  
                ?>
                    <tr class="fs-14">
                        <td align="right" class="report_detail_none" style="text-align:justify; font:Courier; font-family:Courier;" colspan="2"><i>Particulars</i>&nbsp;</td> 
                        <td align="left"  class="report_detail_none" colspan="4">&nbsp;</td>
                    </tr>
                <?php
                    $lineno = $lineno + 1 ; 
                    for($i=0;$i<$array_row;$i++)
                    {
                //        $header_desc = $header_array[$i];
                        $header_desc = text_justify(trim(nl2br(stripslashes($header_array[$i]))),$tot_char);
                        $header_desc = str_replace("<br />",'',$header_desc);
                ?>
                        <tr class="fs-14">
                            <td align="right" class="p-2" colspan="2">&nbsp;</td> 
                            <td class="p-2" style="text-align:justify; font:Courier; font-family:Courier;" colspan="4"><i><?php echo $header_desc;?></i></td>
                        </tr>
                <?php
                        $lineno = $lineno + 1 ;

                        if($lineno >= $maxline)
                        { 
                ?>
                                </table>
                                </td>
                            </tr>
                            </table>
                        <BR CLASS="pageEnd"> 
                <?php
                        $pageno = $pageno + 1 ;
                ?>
                        <table width="990" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                            <tr>
                            <td colspan="6">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="08%">&nbsp;</td>
                                    <td width="72%">&nbsp;</td>
                                    <td width="08%">&nbsp;</td>
                                    <td width="12%">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper($global_company_name)?></b></td>
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
                                    <td class="report_label_text">&nbsp;</td>
                                </tr>
                                </table>
                            </td>    
                            </tr>
                            <tr class="fs-14">
                                <th width="03%" align="left"  class="py-3 px-2">&nbsp;Sl</th>
                                <th width="16%" align="left"  class="py-3 px-2">&nbsp;Dt/Mtr/F-Dt/Amt</th>
                                <th width="30%" align="left"  class="py-3 px-2">&nbsp;Client/Matter Description/Judge/Court/Reference</th>
                                <th width="19%" align="left"  class="py-3 px-2">&nbsp;Fix For (The Day)</th>
                                <th width="16%" align="left"  class="py-3 px-2">&nbsp;Next Dt/Fix For</th>
                                <th width="16%" align="left"  class="py-3 px-2">&nbsp;Prev Dt/Fix For</th>
                            </tr>
                <?php
                        $lineno = 10 ;
                        }
                    }
                    } 
                ?>
                    <tr class="fs-14">
                        <td colspan="6">&nbsp;<hr noshade="noshade"></td>
                    </tr>
                <?php     
                    $lineno = $lineno + 1 ; 
                    if ($maxline - $lineno < 5) { $lineno = $maxline ; }  
                    $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row; 
                    $rowcnt = $rowcnt + 1 ;
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

        if (document.caseDetailQuiry.start_date.value.substring(6,10)+document.caseDetailQuiry.start_date.value.substring(3,5)+document.caseDetailQuiry.start_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.caseDetailQuiry.start_date.focus()}, 500) });
            return false;
        }
        else if (document.caseDetailQuiry.end_date.value.substring(6,10)+document.caseDetailQuiry.end_date.value.substring(3,5)+document.caseDetailQuiry.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.caseDetailQuiry.end_date.focus()}, 500) });
            return false;
        }
        else if (document.caseDetailQuiry.start_date.value.substring(6,10)+document.caseDetailQuiry.start_date.value.substring(3,5)+document.caseDetailQuiry.start_date.value.substring(0,2)>document.caseDetailQuiry.end_date.value.substring(6,10)+document.caseDetailQuiry.end_date.value.substring(3,5)+document.caseDetailQuiry.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'Period End Date must be greater than Period Start Date' }).then((result) => { setTimeout(() => {document.caseDetailQuiry.end_date.focus()}, 500) });
            return false;
        } 

        document.caseDetailQuiry.submit();
    }
</script>
<?= $this->endSection() ?>