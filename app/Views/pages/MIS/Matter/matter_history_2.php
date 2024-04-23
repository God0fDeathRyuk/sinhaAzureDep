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
        <h1>Matter History </h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="matterHistory2" name="matterHistory2" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">As on Date <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-100 float-start" name="ason_date" value="<?php echo date('d-m-Y') ?>" readonly>
                        </div>
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
                            <input type="text" class="form-control w-48 float-start datepicker" name="start_date" value="" onBlur="make_date(this)">
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-48 float-start datepicker" name="end_date" value="<?php echo date('d-m-Y')?>" onBlur="make_date(this)" required>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn pe-4">Matter Code <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control w-100 float-start pe-4" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc', 'clientCode', 'clientName'], ['matter_desc', 'client_code','client_name'], 'matter_code')" onfocusout="getMatterInfo(this)" name="matter_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc', 'clientCode', 'clientName'], ['matter_desc', 'client_code','client_name'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Desc </label>
                            <input type="text" class="form-control w-100 float-start" name="matter_desc" id="matterDesc" readonly>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn pe-4">Client Code</label>
                            <input type="text" class="form-control w-100 float-start pe-4" id="clientCode" name="client_code" readonly>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name </label>
                            <input type="text" class="form-control w-100 float-start" name="client_name" id="clientName" readonly>
                        </div>

                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn pe-4">Initial Code <strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control w-100 float-start" id="initialCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'initial_code', ['initialName'], ['initial_name'], 'initial_code')" name="initial_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('initial_code', '<?= $displayId['initial_help_id'] ?>', 'initialCode', ['initialName'], ['initial_name'], 'initial_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Initial Name </label>
                            <input type="text" class="form-control w-100 float-start" name="initial_name" id="initialName" readonly>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Options <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="desc_ind" required >
                                <option value="Y">With Particulars</option>
                                <option value="N">Without Particulars</option>
                            </select>
                        </div>				
                        <div class="col-md-2 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="output_type" required >
                                <option value="Report">View Report</option>
                                <option value="Pdf">Download PDF</option>
                                <option value="Excel">Download Excel</option>
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Show Date <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="report_type" required >
                                <option value="No" >No</option>				  
                                <option value="Yes">Yes</option>			
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
    <?php if($report_type_form == 'Yes') { ?>
        <?php
            $maxline    = 50 ;
            $tot_char   = 110 ;
            $lineno     = 0 ;
            $pageno     = 0 ;
            $rowcnt     = 1 ;
            $xsrl       = 0 ;

            $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; 
            $report_cnt = $params['case_cnt'] ;
            while ($rowcnt <= $report_cnt)
            {
            $hdr_desc      = str_replace("\n\n","\r\n\r\n",$report_row['header_desc']) . chr(13);
            $header_desc   = wordwrap($hdr_desc, $tot_char, "\n");
            $header_array  = explode("\n",$header_desc);
            $array_row     = count($header_array);
            if($lineno == 0 || $lineno >= $maxline)
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
                        <td colspan="11">    
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
                            <td class="report_label_text">&nbsp;As On</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
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
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['matter_code'] != '%') { echo '('.$params['matter_code'].') - '.strtoupper($params['matter_desc']) ; } else { echo 'ALL' ; } ?></b></td>
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
                        <th height="18" width="" align="right" class="py-3 px-2">Sl&nbsp;</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Recd No</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Date</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Reference No/Judge/Court</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Filing Dt/Amt</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Prev Date/</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Next Date</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Fix For</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Remarks</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Prepared On</th>
                        <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Prepared By</th>
                    </tr>
                            
            <?php
                            $lineno = 10 ;
                }
                $xsrl = $xsrl + 1;
            ?>
                <tr class="fs-14">
                    <td align="right" class="p-2"><b><?php echo $xsrl ?></b>&nbsp;</td>
                    <td align="left"  class="p-2"><?php echo $report_row['serial_no']?></td> 
                    <td align="left"  class="p-2"><?php echo date_conv($report_row['activity_date'],'-')?></td>
                    <td align="left"  class="p-2"><?php echo $report_row['reference_desc']?></td>
                    <td align="left"  class="p-2"><?php if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing'],'-') ; } else { echo '&nbsp;' ; } ?></td> 
                    <td align="left"  class="p-2"><?php echo date_conv($report_row['prev_date'],'-')?></td>
                    <td align="left"  class="p-2"><?php echo date_conv($report_row['next_date'],'-')?></td>
                    <td align="left"  class="p-2"><?php echo $report_row['next_fixed_for']?>&nbsp;</td>
                    <td align="left"  class="p-2"><?php echo $report_row['remarks']?></td>
                    <td align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['prepared_on'],'-')?></td>
                    <td align="left"  class="p-2">&nbsp;<?php echo $report_row['prepared_by']?></td>
                </tr>
                <tr class="fs-14">
                    <td align="left"  class="p-2">&nbsp;</td> 
                    <td align="left"  class="p-2">&nbsp;</td> 
                    <td align="left"  class="p-2">&nbsp;</td>
                    <td align="left"  class="p-2">&nbsp;<?php echo $report_row['judge_name']?></td>
                    <td align="left"  class="p-2"><?php echo $report_row['stake_amount']?></td> 
                    <td align="left"  class="p-2">&nbsp;</td> 
                    <td align="left"  class="p-2">&nbsp;</td> 
                    <td align="left"  class="p-2">&nbsp;</td> 
                </tr>
                <tr class="fs-14">
                    <td align="left"  class="p-2">&nbsp;</td> 
                    <td align="left"  class="p-2">&nbsp;</td> 
                    <td align="left"  class="p-2">&nbsp;</td>
                    <td align="left"  class="p-2"><?php echo $report_row['court_name']?></td>
                    <td align="left"  class="p-2">&nbsp;</td>
                    <td align="left"  class="p-2">&nbsp;</td>
                    <td align="left"  class="p-2">&nbsp;</td> 
                </tr>
            <?php 
                $lineno = $lineno + 3 ; 

                if($params['desc_ind'] == 'Y') 
                {  
            ?>
                <tr class="fs-14">
                    <td align="right" class="p-2" style="text-align:right; font:Courier; font-family:Courier; word-break:break-all; " colspan="2"><i>Particulars</i></td> 
                    <td align="left"  class="p-2" colspan="7" style="word-break:break-all;">&nbsp;<?php echo $report_row['other_case_desc'];?></td>
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
                        <td class="p-2" style="text-align:left; font:Arial; font-family:Arial;word-break:break-all;" colspan="5"><i><?php echo $header_desc;?></i></td>
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
                    <table width="950" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                        <td colspan="11">    
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
                                <td class="report_label_text">&nbsp;As On</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
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
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['matter_code'] != '%') { echo '('.$params['matter_code'].') - '.strtoupper($params['matter_desc']) ; } else { echo 'ALL' ; } ?></b></td>
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
                            <th height="18" align="right" class="py-3 px-2">Sl&nbsp;</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Recd No</th>
                            <td height="18" align="left"  class="py-3 px-2">&nbsp;Date</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Reference No/Judge/Court</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Filing Dt/Amt</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Prev Date/</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Next Date</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Fix For</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Remarks</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Prepared On</th>
                            <th height="18" align="left"  class="py-3 px-2">&nbsp;Prepared By</th>
                        </tr>
                            
            <?php
                        $lineno = 10 ;
                    }
                    }
                }  
            ?>
                <tr class="fs-14">
                    <td colspan="78">&nbsp;</td>
                </tr>
            <?php     
                $lineno = $lineno + 1 ; 
                if ($maxline - $lineno < 3) { $lineno = $maxline ; }  
                $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row; 
                $rowcnt = $rowcnt + 1 ;
                }
            ?>
                            </table>
                        </td>
                        </tr>
                    </table> 
    <?php } else if($report_type_form == 'No') { ?>
        <?php
            $maxline    = 43 ;
            $tot_char   = 150 ;
            $lineno     = 0 ;
            $pageno     = 0 ;
            $rowcnt     = 1 ;
            $xsrl       = 0 ;

            $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; 
            $report_cnt = $params['case_cnt'] ;
            while ($rowcnt <= $report_cnt)
            {
            $hdr_desc      = str_replace("\n\n","\r\n\r\n",$report_row['header_desc']) . chr(13);
            $header_desc   = wordwrap($hdr_desc, $tot_char, "\n");
            $header_array  = explode("\n",$header_desc);
            $array_row     = count($header_array);
            if($lineno == 0 || $lineno >= $maxline)
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
                        <td class="report_label_text">&nbsp;As On</td>
                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
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
                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['matter_code'] != '%') { echo '('.$params['matter_code'].') - '.strtoupper($params['matter_desc']) ; } else { echo 'ALL' ; } ?></b></td>
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
                    <th height="18" align="right" class="report_detail_all">Sl&nbsp;</th>
                    <th height="18" align="left"  class="report_detail_rtb">&nbsp;Rec Sl#</th>
                    <th height="18" align="left"  class="report_detail_rtb">&nbsp;Date</th>
                    <th height="18" align="left"  class="report_detail_rtb">&nbsp;Reference No/Court/Judge</th>
                    <th height="18" align="left"  class="report_detail_rtb">&nbsp;Filing Dt/Amt</th>
                    <th height="18" align="left"  class="report_detail_rtb">&nbsp;Prev Date/</th>
                    <th height="18" align="left"  class="report_detail_rtb">&nbsp;Next Date</th>
                    <th height="18" align="left"  class="report_detail_rtb">&nbsp;Fix For</th>
                    <th height="18" align="left"  class="report_detail_rtb">&nbsp;Remarks </th>
                </tr>
        <?php
                        $lineno = 10 ;
            }
            $xsrl = $xsrl + 1;
        ?>
            <tr style="vertical-align:top" class="fs-14">
                <td align="right" class="p-2"><b><?php echo $xsrl ?></b>&nbsp;</td>
                <td align="left"  class="p-2">{<?php echo $report_row['serial_no'];?>}&nbsp;</td> 
                <td align="left"  class="p-2"><?php echo date_conv($report_row['activity_date'],'-');?></td> 
                <td align="left"  class="p-2"><?php echo $report_row['reference_desc'];?></td>
                <td align="left"  class="p-2"><?php if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing'],'-') ; } else { echo '&nbsp;' ; } ?></td> 
                <td align="left"  class="p-2"><?php echo date_conv($report_row['prev_date'],'-');?></td>
                <td align="left"  class="p-2"><?php echo date_conv($report_row['next_date'],'-');?></td>
                <td align="left"  class="p-2"><?php echo $report_row['next_fixed_for'];?>&nbsp;</td>
                <td align="left"  class="p-2" rowspan="3" style="vertical-align:top"><?php echo $report_row['remarks'];?></td>
            </tr>
            <tr class="fs-14">
                <td align="left"  class="p-2">&nbsp;</td> 
                <td align="left"  class="p-2">&nbsp;<?php if($report_row['bill_status'] == 'B') { echo '<b><font color="#CC0000">BILLED</font></b>';} else if($report_row['bill_status'] == 'A') { echo '<b><font color="#0000FF">DRAFT</font></b>';} else { echo '<b><font color="#FF0000">.</font></b>';} ?></td> 
                <td align="left"  class="p-2">&nbsp;</td>
                <td align="left"  class="p-2"><?php echo $report_row['court_name'];?></td>
                <td align="left"  class="p-2"><?php echo $report_row['stake_amount'];?></td> 
                <td align="left"  class="p-2">&nbsp;</td> 
                <td align="left"  class="p-2">&nbsp;</td> 
                <td align="left"  class="p-2">&nbsp;</td> 
            </tr>
            <tr class="fs-14">
                <td align="left"  class="p-2">&nbsp;</td> 
                <td align="left"  class="p-2" colspan="2" bgcolor="">&nbsp;<?php if($report_row['bill_status'] == 'B') { echo '<b><font color="#CC0000">'.$report_row['bill_no'].'</font></b>';} else if($report_row['bill_status'] == 'A') { echo '<b><font color="#0000FF">'.$report_row['ref_billinfo_serial_no'].'</font></b>';} else { echo '';} ?></td> 
                <td align="left"  class="p-2"><?php echo $report_row['judge_name'];?></td>
                <td align="left"  class="p-2">&nbsp;</td>
                <td align="left"  class="p-2">&nbsp;</td>
                <td align="left"  class="p-2">&nbsp;</td> 
                <td align="left"  class="p-2">&nbsp;</td> 
            </tr>
        <?php 
            $lineno = $lineno + 3 ; 

            if($params['desc_ind'] == 'Y') 
            {  
        ?>
            <tr class="fs-14">
                <td align="right" class="p-2" style="text-align:center; font:Courier; font-family:Courier;" colspan="2"><i>Particulars</i></td> 
                <td align="left"  class="p-2" colspan="7" style="word-break:break-all;"><?php echo $report_row['other_case_desc'];?>&nbsp;</td>
            </tr>
        <?php
                $lineno = $lineno + 1 ; 
                for($i=0;$i<$array_row;$i++)
                {
                $header_desc = text_justify(trim(nl2br(stripslashes($header_array[$i]))),$tot_char);
                $header_desc = str_replace("<br />",'',$header_desc);
        ?>
                <tr class="fs-14">
                    <td align="right" class="p-2" colspan="2">&nbsp; </td> 
                    <td class="p-2" colspan="7" style="word-break:break-all;">
                        <p class="d-none w-75 float-start"><?php echo $header_desc;?></p>
                    </td>
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
                            <td class="report_label_text">&nbsp;As On</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
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
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['matter_code'] != '%') { echo '('.$params['matter_code'].') - '.strtoupper($params['matter_desc']) ; } else { echo 'ALL' ; } ?></b></td>
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
                        <th height="18" align="right" class="py-3 px-2">Sl&nbsp;</th>
                        <th height="18" align="left"  class="py-3 px-2">&nbsp;Rec Sl#</th>
                        <th height="18" align="left"  class="py-3 px-2">&nbsp;Date</th>
                        <th height="18" align="left"  class="py-3 px-2">&nbsp;Reference No/Court/Judge</th>
                        <th height="18" align="left"  class="py-3 px-2">&nbsp;Filing Dt/Amt</th>
                        <th height="18" align="left"  class="py-3 px-2">&nbsp;Prev Date/</th>
                        <th height="18" align="left"  class="py-3 px-2">&nbsp;Next Date</th>
                        <th height="18" align="left"  class="py-3 px-2">&nbsp;Fix For</th>
                        <th height="18" align="left"  class="py-3 px-2">&nbsp;Remarks</th>
                    </tr>
        <?php
                    $lineno = 10 ;
                }
                }
            }  
        ?>
            <tr class="fs-14">
                <td colspan="78">&nbsp;</td>
            </tr>
        <?php     
            $lineno = $lineno + 1 ; 
            if ($maxline - $lineno < 3) { $lineno = $maxline ; }  
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
        if (document.matterHistory2.matter_code.value == '' && document.matterHistory2.initial_code.value == '') {
            Swal.fire({ text: 'Please enter either Matter Code or Initial !!!' }).then((result) => { setTimeout(() => {document.matterHistory2.matter_code.focus()}, 500) });
            return false ;
        } else if (document.matterHistory2.initial_code.value == '' && document.matterHistory2.output_type.value == 'Excel') {
            Swal.fire({ text: 'Please enter Initial Code !!!' }).then((result) => { setTimeout(() => {document.matterHistory2.initial_code.focus()}, 500) });
            return false ;
        } else if (document.matterHistory2.matter_code.value == '' && (document.matterHistory2.output_type.value == 'Report' || document.matterHistory2.output_type.value == 'Pdf')) {
            Swal.fire({ text: 'Please enter either Matter Code or Output type Excel !!!' }).then((result) => { setTimeout(() => {document.matterHistory2.initial_code.focus()}, 500) });
            return false ;
        }
        document.matterHistory2.submit();
    }
</script>
<?= $this->endSection() ?>