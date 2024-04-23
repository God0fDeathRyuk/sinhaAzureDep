<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($jnlreg_qry))) { ?> 
    <main id="main" class="main">
        
        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>Journal Register</h1>
        </div><!-- End Page Title -->

            <form action="" method="post" id="journalRegister" name="journalRegister" onsubmit="setValue(event)">
                <section class="section dashboard">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3 position-relative">
                        
                            <div class="frms-sec-insde d-block float-start col-md-4 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Branch  <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="branch_code" required >
                                    <?php foreach($data['branches'] as $branch) { ?>
                                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-5 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control w-48 float-start" name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)"required>
                                <span class="w-2 float-start mx-2">---</span>
                                <input type="text" class="form-control w-48 float-start" name="end_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required>
                                <input type="hidden" name="current_date" value="<?= date('d-m-Y') ?>">
                            </div>
                            <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Journal Type  <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="journal_type" required>
                                    <option value="">---Select---</option>
                                    <option value="AJ">Adjustment</option>
                                    <option value="BJ">Bill</option>
                                    <option value="MJ">Memo</option>
                                    <option value="PJ">P/L Transfer</option>
                                    <option value="TJ">Advance Transfer</option>
                                </select>
                            </div>
                            <div class="col-md-4 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                                <select class="form-select w-100 float-start" name="output_type" required >
                                    <option value="Report">View Report</option>
                                    <option value="Pdf" >Download PDF</option>
                                    <option value="Excel" >Download Excel</option>
                                </select>
                            </div>	
                            <div class="frms-sec-insde d-block float-start col-md-12 px-2 mt-10">
                                <button type="submit" class="btn btn-primary cstmBtn mt-2">Proceed</button>
                                <button type="button" class="btn btn-primary cstmBtn btn-cncl mt-2">Cancel</button>
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
						<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
            <?php
                $maxline = 40 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tdramt  = 0; 
                $tcramt  = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($jnlreg_qry[$rowcnt-1]) ? $jnlreg_qry[$rowcnt-1] : '' ; 
                $report_cnt = $params['jnlreg_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $sdramt  = 0; 
                $scramt  = 0; 
                $psrlind = 'Y';
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
                    <table width="990" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                        <tr>
                            <td colspan="10">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
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
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['start_date'] . ' TO ' . $params['end_date'] ; ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Journal</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo strtoupper($params['jvtype_name']) ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th width="08%" align="left"  class="py-3 px-2">&nbsp;Date</th>
                            <th width="05%" align="left"  class="py-3 px-2">&nbsp;Doc#</th>
                            <th width="03%" align="left"  class="py-3 px-2">&nbsp;Main</th>
                            <th width="03%" align="left"  class="py-3 px-2">&nbsp;Sub</th>
                            <th width="05%" align="left"  class="py-3 px-2">&nbsp;Client</th>
                            <th width="05%" align="left"  class="py-3 px-2">&nbsp;Matter</th>
                            <th width="03%" align="left"  class="py-3 px-2">&nbsp;Intl</th>
                            <th width="46%" align="left"  class="py-3 px-2">&nbsp;Narration</th>
                            <th width="10%" align="right" class="py-3 px-2">Debit&nbsp;</th>
                            <th width="10%" align="right" class="py-3 px-2">Credit&nbsp;</th>
                        </tr>
                                    
                <?php
                            $lineno = 7 ;
                        }
                ?>
                                    <tr class="fs-14 border-0">
                                        <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo date_conv($report_row['doc_date'],'-') ; }?></td> 
                                        <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php if($psrlind=='Y') { echo $report_row['doc_no'] ; }?></td>
                                        <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php echo $report_row['main_ac_code'] ; ?></td>
                                        <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php echo strtoupper($report_row['sub_ac_code'])  ; ?></td>
                                        <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php echo strtoupper($report_row['client_code'])  ; ?></td>
                                        <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php echo $report_row['matter_code']  ; ?></td>
                                        <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php echo strtoupper($report_row['initial_code']) ; ?></td>
                                        <td align="left"  class="p-2"  style="vertical-align:top">&nbsp;<?php echo strtoupper($report_row['narration'])?></td>
                                        <td align="right" class="p-2"  style="vertical-align:top"><?php if($report_row['dr_cr_ind'] == 'D') { echo $report_row['gross_amount'] ; }?>&nbsp;</td>
                                        <td align="right" class="p-2"  style="vertical-align:top"><?php if($report_row['dr_cr_ind'] == 'C') { echo $report_row['gross_amount'] ; }?>&nbsp;</td>
                                    </tr>
                <?php     
                        $psrlind = 'N' ;
                        $lineno  = $lineno + 1;
                        if ($report_row['dr_cr_ind'] == 'D') {$sdramt  = $sdramt + $report_row['gross_amount'] ; }                  
                        if ($report_row['dr_cr_ind'] == 'C') {$scramt  = $scramt + $report_row['gross_amount'] ; }                  
                        //
                        $report_row = ($rowcnt < $report_cnt) ? $jnlreg_qry[$rowcnt] : $report_row; 
                        $rowcnt = $rowcnt + 1 ;
                    }
                ?>
                                    <tr class="fs-14 border-0">
                                        <td height="25" colspan="8" align="right"  class="p-2" style="background-color: #fdfcc6;">&nbsp;<b> Total</b></td>
                                        <td height="25" align="right" class="p-2" style="background-color: #fdfcc6;"><b><?php if($sdramt > 0) { echo number_format($sdramt,2,'.','') ; }?></b>&nbsp;</td>
                                        <td height="25" align="right" class="p-2" style="background-color: #fdfcc6;"><b><?php if($sdramt > 0) { echo number_format($sdramt,2,'.','') ; }?></b>&nbsp;</td>
                                    </tr>
                <?php
                    $lineno  = $lineno + 1;
                    $tdramt  = $tdramt + $sdramt ;
                    $tcramt  = $tcramt + $scramt ;
                    }  
                ?>                   
                                    <tr class="fs-14 border-0">
                                        <td colspan="8">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14 border-0">
                                        <td height="25" align="right" class="p-2" colspan="8" style="background-color: #fdfcc6;">&nbsp;<b> PERIOD TOTAL </b></td>
                                        <td height="25" align="right"  class="p-2" style="background-color: #fdfcc6;"><b><?php if($tdramt > 0) { echo number_format($tdramt,2,'.','') ; }?></b>&nbsp;</td>
                                        <td height="25" align="right"  class="p-2" style="background-color: #fdfcc6;"><b><?php if($tdramt > 0) { echo number_format($tdramt,2,'.','') ; }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
    </main>
<?php } ?>
<script>
    function setValue(e) {
        e.preventDefault();
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.journalRegister.start_date.value.substring(6,10)+document.journalRegister.start_date.value.substring(3,5)+document.journalRegister.start_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.journalRegister.start_date.focus()}, 500) });
            return false;
        }
        else if (document.journalRegister.end_date.value.substring(6,10)+document.journalRegister.end_date.value.substring(3,5)+document.journalRegister.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.journalRegister.end_date.focus()}, 500) });
            return false;
        }
        else if (document.journalRegister.start_date.value.substring(6,10)+document.journalRegister.start_date.value.substring(3,5)+document.journalRegister.start_date.value.substring(0,2)>document.journalRegister.end_date.value.substring(6,10)+document.journalRegister.end_date.value.substring(3,5)+document.journalRegister.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'Period End Date must be less than Period Start Date' }).then((result) => { setTimeout(() => {document.journalRegister.end_date.focus()}, 500) });
            return false;
        }
        
        document.journalRegister.submit();
    }
</script>
<?= $this->endSection() ?>