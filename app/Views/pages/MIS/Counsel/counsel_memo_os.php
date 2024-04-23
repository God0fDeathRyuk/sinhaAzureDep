<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>
<?php if((!isset($memo_qry))) { ?>
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>Counsel Memo (O/S)</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="counselMemoCredited" name="counselMemoCredited" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">As On <strong class="text-danger">*</strong></label>	
                            <input type="text" class="form-control w-100 float-start"  name="ason_date" value="<?php echo date('d-m-Y')?>" readonly required>
                        </div>
                        
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>										
                        
                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Counsel Code</label>
                            <input type="text" class="form-control w-100 float-start" name="counsel_code" id="counselCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'associate_code', ['counselName'], ['associate_name'], 'counsel_code')">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('associate_code', '<?= $displayId['counsel_help_id'] ?>', 'counselCode', ['counselName'], ['associate_name'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Counsel Name </label>
                            <input type="text" class="form-control w-100 float-start" name="counsel_name" id="counselName" readonly>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="output_type" required >
                                <option value="Report">View Report</option>
                                <option value="Pdf" >Download PDF</option>
                                <option value="Excel" >Download Excel</option>
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
        <?php
            $maxline = 60 ;
            $lineno  = 0 ;
            $pageno  = 0 ;
            $xgamt   = 0 ;
            $rowcnt  = 1 ;
            $report_row = isset($memo_qry[$rowcnt-1]) ? $memo_qry[$rowcnt-1] : '' ; 
            $report_cnt = $params['memo_cnt'] ;
            while ($rowcnt <= $report_cnt)
            {
            $xsrl   = 0 ;
            $xcamt  = 0 ;
            $pccode = $report_row['counsel_code'] ;
            while ($pccode == $report_row['counsel_code'] && $rowcnt <= $report_cnt)
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
                            <td colspan="4" class="border-0">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="10%">&nbsp;</td>
                                    <td width="67%">&nbsp;</td>
                                    <td width="08%">&nbsp;</td>
                                    <td width="15%">&nbsp;</td>
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
                                    <td class="report_label_text">&nbsp;Counsel</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['counsel_code'] != '%') { echo strtoupper($params['counsel_name']) ; } else { echo 'ALL' ; } ?></b></td>
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
                            <th height="18" width="08%" align="left"  class="py-3 px-2">&nbsp;Code</th>
                            <th height="18" width="65%" align="left"  class="py-3 px-2">&nbsp;Name</th>
                            <th height="18" width="10%" align="left"  class="py-3 px-2">&nbsp;Yr/Mth</th>
                            <th height="18" width="17%" align="right" class="py-3 px-2">Amount&nbsp;</th>
                        </tr>
            <?php
                        $lineno = 10 ;
                    }
            ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;<?php if($xsrl==0) { echo $report_row['counsel_code'] ; } else { echo '&nbsp;' ; } ?></td> 
                                        <td align="left"  class="p-2">&nbsp;<?php if($xsrl==0) { echo $report_row['counsel_name'] ; } else { echo '&nbsp;' ; } ?></td>
                                        <td align="left"  class="p-2">&nbsp;<?php echo $report_row['yyyy_mm']?></td>
                                        <td align="right" class="p-2"><?php echo $report_row['counsel_fee']?>&nbsp;</td>
                                    </tr>
            <?php     
                    $xcamt  = $xcamt + $report_row['counsel_fee'] ;
                    $xsrl   = $xsrl + 1 ;
                    $lineno = $lineno + 1 ; 
                    if ($maxline - $lineno < 3) { $lineno = $maxline ; }  
                    $report_row = ($rowcnt < $report_cnt) ? $memo_qry[$rowcnt] : $report_row;   
                    $rowcnt = $rowcnt + 1 ;
                }  
            ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="right" class="p-2">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td> 
                                        <td align="right" class="p-2" colspan="2" style="background-color: #e2e6506e;"><b> Counsel Total</b>&nbsp;</td>
                                        <td align="right" class="p-2" style="background-color: #e2e6506e;"><?php echo number_format($xcamt,2,'.','')?>&nbsp;</td>
                                    </tr>
                                    
            <?php     
                    $xgamt  = $xgamt + $xcamt ;
                    $lineno = $lineno + 3 ; 
                    if ($maxline - $lineno < 3) { $lineno = $maxline ; }  
                }  
            ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2">&nbsp;</td> 
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="left"  class="p-2">&nbsp;</td>
                                        <td align="right" class="p-2">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td> 
                                        <td align="right" class="p-2" colspan="2" style="background-color: #91d6ec6e;"><b>  GRAND TOTAL  </b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><?php echo number_format($xgamt,2,'.','')?>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table> 
                    <BR CLASS="pageEnd">
    </main>
<?php } ?>

<?= $this->endSection() ?>