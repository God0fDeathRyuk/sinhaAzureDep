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
        <h1>Matter(s) Opened </h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="matterOpenned" name="matterOpenned" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-48 float-start datepicker" name="start_date" value="<?php echo $data['curr_fyrsdt']?>" onBlur="make_date(this)" required>
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-48 float-start datepicker" name="end_date" value="<?php echo date('d-m-Y')?>" onBlur="make_date(this)" required>
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
                            <label class="d-inline-block w-100 mb-1 lbl-mn pe-4">Client Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" name="client_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name </label>
                            <input type="text" class="form-control w-100 float-start" id="clientName" name="client_name" readonly>
                        </div>

                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn pe-4">Court Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" name="court_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Name </label>
                            <input type="text" class="form-control w-100 float-start" id="courtName" name="court_name" readonly>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Case Type <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="case_type" required >
                                <option value="%">--All--</option>
                                <?php foreach($data['casetype_qry'] as $casetype_row) { ?>
                                    <option value="<?php echo $casetype_row['code_code']?>"><?php echo $casetype_row['code_desc']?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Seq <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="opened_by" required >
                                <option value="E">Date of Entry</option>
                                <option value="F">Date of Filing</option>
                            </select>
                        </div>				
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="output_type" required >
                                <option value="Report">View Report</option>
                                <option value="Pdf">Download PDF</option>
                                <option value="Excel">Download Excel</option> 
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Report Type <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="report_type" required>
                                <option value="D">Detail</option>
                                <option value="S">Summary</option>
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Report Seq <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="report_seqn" required >
                                <option value="I">Initial-wise</option>
                                <option value="T">Court-wise</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary cstmBtn mt-28 ms-2">Proceed</button>				
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
        <?php if($report_type_form == 'D' && $opened_by == 'E') { ?>
            <?php
                $maxline = 50 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $desc_ind  = '' ;
                $rowcnt     = 1 ;
                $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; 
                $report_cnt = $params['case_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $xsrl   = 0;
                $xbacnt   = 0;
                $xlacnt   = 0;
                $xacnt   = 0;
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
                    <table width="990" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                            <td colspan="7">    
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
                                    <td class="report_label_text">&nbsp;Court</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['court_code'] != '%') { echo strtoupper($params['court_name']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Case Type</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['case_type'] != '%') { echo $params['case_type_desc'] ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Total Matter </td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['case_cnt']?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th height="18" width="03%" align="right" class="py-3 px-2">Sl&nbsp;</th>
                            <th height="18" width="08%" align="left"  class="py-3 px-2">&nbsp;Date</th>
                            <th height="18" width="06%" align="left"  class="py-3 px-2">&nbsp;Matter</th>
                            <th height="18" width="35%" align="left"  class="py-3 px-2">&nbsp;Client/Matter Desc/Subject</th>
                            <th height="18" width="04%" align="left"  class="py-3 px-2">&nbsp;Intl</th>
                            <th height="18" width="20%" align="left"  class="py-3 px-2">&nbsp;Ref No</th>
                            <th height="18" width="24%" align="left"  class="py-3 px-2">&nbsp;Court/Judge/Filing Dt</th>
                        </tr>
                        
                <?php
                            $lineno = 11 ;
                        }
                ?>
                    <?php
                    if ($xbacnt > 0)
                    {
                        for ($i=($xlacnt+1); $i<$xacnt; $i++)
                        {
                    ?>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                        <td align="left"   class="p-2" style="text-align:justify;" colspan="3"><?php echo $xarray[$i]; ?></td>
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                    </tr>
                    <?php 
                        $lineno = $lineno + 1 ; 
                        } 
                        empty($xarray) ; 
                    ?>
                                    <tr class="fs-14">
                                        <td colspan="7"><hr size="1" noshade></td>
                                    </tr>
                    <?php
                        $lineno = $lineno + 1 ; 
                    } 
                            $xsrl = $xsrl + 1;

                    ?>
                                    <tr class="fs-14">
                                        <td align="right" class="p-2"><?php echo $xsrl ;?>&nbsp;</td> 
                                        <td align="left"  class="p-2"><?php if($report_row['prepared_on'] != '' && $report_row['prepared_on'] != '0000-00-00') { echo date_conv($report_row['prepared_on']);} else { echo '&nbsp;' ; };?></td> 
                                        <td align="left"  class="p-2"><?php echo $report_row['matter_code'];?></td>
                                        <td align="left"  class="p-2"><?php echo $report_row['client_name'];?></td>
                                        <td align="left"  class="p-2"><?php echo $report_row['initial_code'];?></td>
                                        <td align="left"  class="p-2"><?php echo stripslashes($report_row['reference_desc']);?></td>
                                        <td align="left"  class="p-2"><?php echo $report_row['court_name'];?></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2" colspan="2" rowspan="2" style="vertical-align:top"><?php echo $report_row['matter_desc2'];?>&nbsp;</td>
                                        <td align="left"  class="p-2"><?php echo $report_row['matter_desc1']?></td>
                                        <td align="left"  class="p-2"><?php echo $report_row['judge_name'];?></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2"><?php if($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing']); } else { echo '&nbsp;' ; };?></td> 
                                    </tr>
                                    <?php $lineno = $lineno + 3 ; ?>
                                    <?php if($desc_ind == 'Y') 
                                    {  
                                    $xlen   = strlen($report_row['subject_desc']); $xarray = str_split($report_row['subject_desc'],122); $xacnt = count($xarray) ; 
                                    $xbline = $maxline - $lineno ;   if ($xbline >= $xacnt) { $xbline = $xacnt ; } 
                                    $xbacnt = $xacnt - $xbline ; 
                                    for ($i=0; $i<$xbline; $i++)
                                    {
                                        $xlacnt = $i ; 
                                    ?>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                        <td align="center" class="p-2">&nbsp;</td> 
                                        <td align="center" class="p-2">&nbsp;</td> 
                                        <td align="left"   class="p-2" style="text-align:justify;" colspan="3"><i><?php echo $xarray[$i]; ?></i></td>
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                    </tr>
                                    <?php 
                                        $lineno = $lineno + 1 ; 
                                    } 
                                    } ?>
                                    <tr class="fs-14">
                                        <td colspan="7">&nbsp;</td>
                                    </tr>
                <?php     
                        $lineno = $lineno + 1 ; 
                        if ($maxline - $lineno < 3) { $lineno = $maxline ; }  
                        $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row; 
                        $rowcnt = $rowcnt + 1 ;
                    }  
                    if ($xbacnt == 0) 
                    {
                        empty($xarray); 
                ?>                   
                                        <tr class="fs-14">
                                            <td colspan="7"><hr size="1" noshade></td>
                                        </tr>
                <?php
                        $lineno = $lineno + 1 ;
                    }	 
                    }
                ?>
                            </table>
                            </td>
                        </tr>
                    </table>
        <?php } else if($report_type_form == 'D' && $opened_by == 'F') { ?>

            <?php
                $maxline = 50 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $desc_ind   ='' ;
                $rowcnt     = 1 ;
                $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ;  
                $report_cnt = $params['case_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $xsrl   = 0;
                $xbacnt   = 0;
                $xlacnt   = 0;
                $xacnt   = 0;
                $padate = $report_row['date_of_filing'] ;
                while ($padate == $report_row['date_of_filing'] && $rowcnt <= $report_cnt)
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
                    <table width="990" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                            <td colspan="7">    
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
                                    <td class="report_label_text">&nbsp;Court</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['court_code'] != '%') { echo strtoupper($params['court_name']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Case Type</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['case_type'] != '%') { echo $params['case_type_desc'] ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th height="18" width="03%" align="right" class="py-3 px-2">Sl&nbsp;</th>
                            <th height="18" width="08%" align="left"  class="py-3 px-2">&nbsp;Date</th>
                            <th height="18" width="06%" align="left"  class="py-3 px-2">&nbsp;Matter</th>
                            <th height="18" width="35%" align="left"  class="py-3 px-2">&nbsp;Client/Matter Desc/Subject</th>
                            <th height="18" width="04%" align="left"  class="py-3 px-2">&nbsp;Intl</th>
                            <th height="18" width="20%" align="left"  class="py-3 px-2">&nbsp;Ref No/Case No</th>
                            <th height="18" width="24%" align="left"  class="py-3 px-2">&nbsp;Court/Judge/Entry Dt</th>
                        </tr>  
            <?php
                        $lineno = 11 ;
                    }
                    $xsrl = $xsrl + 1;
            ?>
                    <?php
                    if ($xbacnt > 0)
                    {
                        for ($i=($xlacnt+1); $i<$xacnt; $i++)
                        {
                    ?>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                        <td align="left"   class="p-2" style="text-align:justify;" colspan="3"><?php echo $xarray[$i]; ?></td>
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                    </tr>
                    <?php 
                        $lineno = $lineno + 1 ; 
                        } 
                        empty($xarray) ; 
                    ?>
                                    <tr class="fs-14">
                                        <td colspan="7"><hr size="1" noshade></td>
                                    </tr>
                    <?php
                        $lineno = $lineno + 1 ; 
                    } 
                    ?>
                                    <tr class="fs-14">
                                        <td align="right" class="p-2"><?php echo $xsrl ?>&nbsp;</td> 
                                        <td align="left"  class="p-2"><?php if($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing']); } else { echo '&nbsp;' ; }?></td> 
                                        <td align="left"  class="p-2"><?php echo $report_row['matter_code']?></td>
                                        <td align="left"  class="p-2"><?php echo $report_row['client_name']?></td>
                                        <td align="left"  class="p-2"><?php echo $report_row['initial_code']?></td>
                                        <td align="left"  class="p-2"><?php echo $report_row['reference_desc']?></td>
                                        <td align="left"  class="p-2"><?php echo $report_row['court_name']?></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2" colspan="2" rowspan="2" style="vertical-align:top"><?php echo $report_row['matter_desc2']?>&nbsp;</td>
                                        <td align="left"  class="p-2"><?php echo $report_row['matter_desc1']?>&nbsp;</td>
                                        <td align="left"  class="p-2"><?php echo $report_row['judge_name']?></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2"><?php if($report_row['prepared_on'] != '' && $report_row['prepared_on'] != '0000-00-00') { echo date_conv($report_row['prepared_on']);} else { echo '&nbsp;' ; }?></td> 
                                    </tr>
                                    <?php $lineno = $lineno + 3 ; ?>
                                    <?php if($desc_ind == 'Y') 
                                    {  
                                    $xlen   = strlen($report_row['subject_desc']); $xarray = str_split($report_row['subject_desc'],122); $xacnt = count($xarray) ; 
                                    $xbline = $maxline - $lineno ;   if ($xbline >= $xacnt) { $xbline = $xacnt ; } 
                                    $xbacnt = $xacnt - $xbline ; 
                                    for ($i=0; $i<$xbline; $i++)
                                    {
                                        $xlacnt = $i ; 
                                    ?>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                        <td align="center" class="p-2">&nbsp;</td> 
                                        <td align="center" class="p-2">&nbsp;</td> 
                                        <td align="left"   class="p-2" style="text-align:justify;" colspan="3"><i><?php echo $xarray[$i]; ?></i></td>
                                        <td align="left"   class="p-2">&nbsp;</td> 
                                    </tr>
                                    <?php 
                                        $lineno = $lineno + 1 ; 
                                    } 
                                    } ?>
                                    <tr class="fs-14">
                                        <td colspan="7">&nbsp;</td>
                                    </tr>
            <?php     
                    $lineno = $lineno + 1 ; 
                    if ($maxline - $lineno < 3) { $lineno = $maxline ; }  
                    $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row;
                    $rowcnt = $rowcnt + 1 ;
                }  
                if ($xbacnt == 0) 
                {
                    empty($xarray); 
            ?>                   
                                    <tr class="fs-14">
                                        <td colspan="7"><hr size="1" noshade></td>
                                    </tr>
            <?php
                    $lineno = $lineno + 1 ;
                }	 
                }
            ?>
                            </table>
                            </td>
                        </tr>
                    </table> 
        <?php } else if($report_type_form == 'S') { ?>
            <table width="950" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                <tr>
                    <td>
                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="10%">&nbsp;</td>
                            <td width="70%">&nbsp;</td>
                            <td width="20%">&nbsp;</td>
                        </tr>
                        <tr><td class="report_label_text" colspan="3" align="center"><b><?php echo strtoupper('sinha and company')?></b></td></tr>
                        <tr><td class="report_label_text" colspan="3" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?></u></b></td></tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Branch</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                            <td class="report_label_text" align="right">&nbsp;&nbsp;Date&nbsp;:&nbsp;<?php echo date('d-m-Y')?></td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Period</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc']?></b></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Client</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['client_code'] != '%') { echo strtoupper($params['client_name']) ; } else { echo 'ALL' ; } ?></b></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Court</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['court_code'] != '%') { echo strtoupper($params['court_name']) ; } else { echo 'ALL' ; } ?></b></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Case Type</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['case_type'] != '%') { echo $params['case_type_desc'] ; } else { echo 'ALL' ; } ?></b></td>
                            <td class="report_label_text">&nbsp;</td>
                            <td class="report_label_text">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="report_label_text">&nbsp;Total</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['count_case_cnt']?></b></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="">
                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr class="fs-14">
                            <th height="18" width="90%" align="left"  class="p-2">&nbsp;<b><?php if($params['report_seqn'] == 'T') { echo 'Court' ; } else { echo 'Initial';} ?></b></th>
                            <th height="18" width="10%" align="right" class="p-2">&nbsp;<b>Count</b>&nbsp;</th>
                        </tr>
                        <?php $rowcnt = 1; $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; $report_cnt = $params['case_cnt'] ;  while ($rowcnt <= $report_cnt) { ?>
                        <tr class="fs-14">
                            <td align="left"  class="p-2">&nbsp;<?= ($params['report_seqn'] == 'T') ? $report_row['court_name'] : $report_row['initial_name']; ?></td>
                            <td align="right" class="p-2"><?php echo $report_row['tot_count']?>&nbsp;</td>
                        </tr>
                        <?php $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row; $rowcnt = $rowcnt + 1 ; }  ?>
                    </table>
                    </td>
                </tr>
                </table>
        <?php } ?>
    </main>
<?php } ?>

<?= $this->endSection() ?>