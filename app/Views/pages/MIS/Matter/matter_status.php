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
        <h1>Matter Status</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="matterStatusLatest" name="matterStatusLatest" onsubmit="setValue(event)">
            <section class="section dashboard">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                            <div class="col-md-6 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-48 float-start datepicker" name="start_date" onBlur="make_date(this)">
                                <span class="w-2 float-start mx-2">---</span>
                                <input type="text" class="form-control w-48 float-start datepicker" name="end_date" value="<?php echo date('d-m-Y') ?>" onBlur="make_date(this)" required>
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
                                <input type="text" class="form-control w-100 float-start" id="clientName" oninput="this.value = this.value.toUpperCase()" name="client_name" readonly>
                            </div>
                            
                            <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Code</label>
                                <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc'], ['matter_desc'], 'matter_code')" name="matter_code">
                                <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-9 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Desc </label>
                                <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="matterDesc" name="matter_desc" readonly>
                            </div>
                            
                            <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Court Code</label>
                                <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" name="court_code">
                                <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-9 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Court Name </label>
                                <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="courtName" name="court_name" readonly>
                            </div>
                            
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                                <select class="form-select w-100 float-start" name="output_type" required >
                                    <option value="Excel" >Download Excel</option>
                                </select>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Column (Excel)</label>
                                <input type="text" class="form-control" size="2" maxlength="2" name="r_limit"  value="<?php echo '1';?>" tabindex="10"/>
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

        <?php
            $maxline = 40 ;
            $lineno  = 0 ;
            $pageno  = 0 ;
            $rowcnt     = 1 ;
            $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; 
            $report_cnt = $params['case_cnt'] ;
            while ($rowcnt <= $report_cnt)
            {
            $pcltcode = $report_row['client_code'] ;
            $pcltname = $report_row['client_name'] ;
            $pcltind  = 'Y' ;
            while ($pcltcode == $report_row['client_code'] && $rowcnt <= $report_cnt)
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
                <table width="990" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>    
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
                                <td class="report_label_text">&nbsp;</td>
                                <td class="report_label_text">&nbsp;</td>
                                </tr>
                            </table>
                        </td>    
                    </tr>
                    <tr>
                        <td colspan="4" class="grid_header">
                            <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                <tr>
                                <td width="08%" align="left"  class="report_detail_all">&nbsp;Mtr/Dt/Amt</td>
                                <td width="55%" align="left"  class="report_detail_rtb">&nbsp;Matter Description/Judge/Court/Ref</td>
                                <td width="09%" align="left"  class="report_detail_rtb">&nbsp;Last/Next</td>
                                <td width="30%" align="left"  class="report_detail_rtb">&nbsp;Fixed For</td>
                                </tr>
            <?php
                        $lineno = 10 ;
                    }
            ?>
                <?php if($pcltind == 'Y') { ?>
                                <tr>
                                <td height="20" colspan="6" class="report_detail_none" align="left"><b><?php echo strtoupper($pcltname) ?></b></td>
                            </tr>  
                <?php $lineno = $lineno + 1 ; $pcltind = 'N' ; } ?>
                                <tr>
                                <td align="left"  class="report_detail_none"><?php echo $report_row['matter_code']?></td> 
                                <td align="left"  class="report_detail_none"><?php echo $report_row['matter_desc']?></a></td>
                                <td align="left"  class="report_detail_none"><?php if ($report_row['activity_date'] != '' && $report_row['activity_date'] != '0000-00-00') { echo date_conv($report_row['activity_date']) ; } else { echo '&nbsp;' ; }?></td>
                                <td align="left"  class="report_detail_none" rowspan="2" style="vertical-align:top"><?php echo $report_row['prev_fixed_for'] ; ?></td>
                                </tr>
                                <tr>
                                <td align="left"  class="report_detail_none"><?php if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing']) ; } else { echo '&nbsp;' ; } ?></td> 
                                <td align="left"  class="report_detail_none"><?php echo $report_row['judge_name']?></td> 
                                <td align="left"  class="report_detail_none">&nbsp;</td> 
                                </tr>
                                <tr>
                                <td align="left"  class="report_detail_none"><?php echo $report_row['stake_amount']?>&nbsp;</td>
                                <td align="left"  class="report_detail_none"><?php echo $report_row['court_name']?></td> 
                                <td align="left"  class="report_detail_none"><?php if ($report_row['next_date']     != '' && $report_row['next_date']     != '0000-00-00') { echo date_conv($report_row['next_date'])     ; } else { echo '&nbsp;' ; }?></td> 
                                <td align="left"  class="report_detail_none" rowspan="2" style="vertical-align:top"><?php echo $report_row['next_fixed_for'] ; ?></td>
                                </tr>
                                <tr>
                                <td align="left"  class="report_detail_none">&nbsp;</td> 
                                <td align="left"  class="report_detail_none" style="vertical-align:top"><?php echo $report_row['reference_desc']?></td> 
                                <td align="right" class="report_detail_none">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="3">&nbsp;</td>
                                </tr>
                                <?php $lineno = $lineno + 5 ;  ?>
            <?php     
                    if ($maxline - $lineno < 4) { $lineno = $maxline ; }  
                    $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row; 
                    $rowcnt = $rowcnt + 1 ;
                }  
            ?>
                                    <tr>
                                    <td colspan="4"><hr size="1" noshade></td>
                                    </tr>
            <?php
                }
            ?>
                            </table>
                            </td>
                        </tr>
                    </table> 
    </main>
<?php } ?>

<?= $this->endSection() ?>