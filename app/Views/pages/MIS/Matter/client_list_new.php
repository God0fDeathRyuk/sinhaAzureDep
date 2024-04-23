<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($trandtl_qry))) { ?>
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>Client List</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="clientList" name="clientList" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-48 float-start datepicker" name="start_date" value="" onBlur="make_date(this)" required>
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-48 float-start datepicker" name="end_date" value="" onBlur="make_date(this)" required>
                        </div>		
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="output_type" required >
                                <option value="Report">View Report</option>
                                <option value="Pdf">Download PDF</option>
                                <option value="Excel">Download Excel</option>
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
            $maxline      = 50 ;
            $lineno       = 0 ;
            $pageno       = 0 ;
            $rowcnt       = 1 ;
            $report_row = isset($trandtl_qry[$rowcnt-1]) ? $trandtl_qry[$rowcnt-1] : '' ;
            $report_cnt   = $params['trandtl_cnt'] ;
            while ($rowcnt <= $report_cnt)
            {
            $pclientind = 'Y';
            $pclientcd  = $report_row['client_code'] ;
            $pclientnm  = $report_row['client_name'] ;
            while ($pclientcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
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
                    <table width="1200" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                    <tr>
                        <td colspan="6">    
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="20%">&nbsp;</td>
                                <td width="60%">&nbsp;</td>
                                <td width="20%">&nbsp;</td>
                            </tr>
                            <tr><td class="report_label_text" colspan="3" align="center"><b><?php echo strtoupper('sinha and company')?></b></td></tr>
                            <tr><td class="report_label_text" colspan="3" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td></tr>
                            <tr><td colspan="3">&nbsp;</td></tr>
                            <tr>
                                <td class="report_label_text" align="left">&nbsp;Date&nbsp;:&nbsp;<?php echo date('d-m-Y')?></td>
                                <td class="report_label_text" align="right">&nbsp;</td>
                                <td class="report_label_text" align="right">Page&nbsp;:&nbsp;<?php echo $pageno?>&nbsp;</td>
                            </tr>
                            
                            </table>
                        </td>    
                    </tr>
                    <tr class="fs-14">
                        <th align="left"  class="py-3 px-2">&nbsp;Date</th>
                        <th align="left"  class="py-3 px-2">&nbsp;Client</th>
                        <th align="left"  class="py-3 px-2">&nbsp;Case No</th>
                        <th align="left"  class="py-3 px-2">&nbsp;Matter</th>
                        <th align="left"  class="py-3 px-2">&nbsp;Initial</th>
                        <th align="left"  class="py-3 px-2">&nbsp;Court</th>
                    </tr>
        <?php
                    $lineno = 8 ;
                }
        ?>			      
                            <tr class="fs-14">
                                <td align="left"   class="p-2" style="vertical-align:top; padding-left:2px"><?php echo date_conv($report_row['prepared_on'])              ?>&nbsp;</td>
                                <td align="left"   class="p-2" style="vertical-align:top; padding-left:2px"><?php echo $report_row['client_name']                         ?>&nbsp;</td>
                                <td align="left"   class="p-2" style="vertical-align:top; padding-left:2px"><?php echo strtoupper($report_row['matter_desc1'])            ?>&nbsp;</td>
                                <td align="left"   class="p-2" style="vertical-align:top; padding-left:2px"><?php echo strtoupper($report_row['matter_desc2'])            ?>&nbsp;</td>
                                <td align="left"   class="p-2" style="vertical-align:top; padding-left:2px"><?php echo strtoupper($report_row['initial_code'])            ?>&nbsp;</td>
                                <td align="left"   class="p-2" style="vertical-align:top; padding-left:2px"><?php echo get_code_desc('001',$report_row['court_code']) ?>&nbsp;</td>
                            </tr>
        <?php     
                    $lineno    += 1 ; 
                    $report_row = ($rowcnt < $report_cnt) ? $trandtl_qry[$rowcnt] : $report_row; 
                    $rowcnt    += 1 ;
            }  
        ?>
                            
        <?php
            $lineno       += 1 ; 
            }
        ?>
                        </table>
                        </td>
                    </tr>
                </table> 
    </main>
<?php } ?>
<?= $this->endSection() ?>