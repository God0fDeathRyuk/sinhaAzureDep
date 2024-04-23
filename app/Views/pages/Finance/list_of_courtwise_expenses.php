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
            <h1>List of Court-wise Expenses</h1>
        </div><!-- End Page Title -->
        <form action="" method="post" id="courtwiseExpenses" name="courtwiseExpenses" onsubmit="setValue(event)">
            <section class="section dashboard">
                <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-5 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-7 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-45 float-start"  name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required/>
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-45 float-start" name="end_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required />
                        </div>
                        
                        <div class="col-md-4 float-start px-2 mb-3 position-relative">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Court Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" size="05" maxlength="06" name="court_code">
                            <i class="fa fa-binoculars icn-vw" aria-hidden="true" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Court Name</label>
                            <input type="text" class="form-control w-100 float-start" name="court_name" id="courtName" readonly >
                        </div>
                        
                        <div class="col-md-4 float-start px-2 mb-3 position-relative">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code">
                            <i class="fa fa-binoculars icn-vw" aria-hidden="true" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
                            <input type="text" class="form-control w-100 float-start" name="client_name" id="clientName" readonly>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3 position-relative">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code</label>
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc'], ['matter_desc'], 'matter_code')" size="05" maxlength="06" name="matter_code">
                            <i class="fa fa-binoculars icn-vw" aria-hidden="true" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Matter Desc</label>
                            <input type="text" class="form-control w-100 float-start" name="matter_desc" id="matterDesc" readonly>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Report Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="report_type" required >
                                <option value="D">Detail</option>
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
                        <div class="d-inline-block w-100">
                            <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Proceed</button>				
                            <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Reset</button>
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
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a>
				<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
						<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
            <?php
                $maxline = 95 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $texpamt = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($trandtl_qry[$rowcnt-1]) ? $trandtl_qry[$rowcnt-1] : '' ; 
                $report_cnt = $params['trandtl_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $cexpamt   = 0; 
                $pcourtind = 'Y' ;
                $pcourtcd  = $report_row['court_code'] ;
                $pcourtnm  = $report_row['court_name'] ;
                while($pcourtcd == $report_row['court_code'] && $rowcnt <= $report_cnt)
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
                            <td colspan="4">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="20%">&nbsp;</td>
                                    <td width="60%">&nbsp;</td>
                                    <td width="20%">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td class="report_label_text" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td class="report_label_text" align="center"><b><u> <?php echo strtoupper($params['report_desc1'])?> </u></b></td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text" align="left"  >&nbsp;Date : <?php echo date('d-m-Y') ?></td>
                                    <td class="report_label_text" align="center"><b><?php echo strtoupper($params['report_desc2'])?></b></td>
                                    <td class="report_label_text" align="right" >&nbsp;Page : <?php echo $pageno ?></td>
                                    </tr>
                                    <tr><td colspan="3"><hr size="1"></td></tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th width="50%" align="left"  class="px-2 py-3">&nbsp;Client / Matter</th>
                            <th width="08%" align="left"  class="px-2 py-3">&nbsp;Date</th>
                            <th width="33%" align="left"  class="px-2 py-3">Narration&nbsp;</th>
                            <th width="09%" align="right" class="px-2 py-3">Amount&nbsp;</th>
                        </tr>
                                    
            <?php
                        $lineno = 6 ;
                        $pcourtind = 'Y' ;
                    }
            ?>
                                <?php if ($pcourtind == 'Y') { ?> 
                                    <tr class="fs-14">
                                        <td height="22" align="left" class="p-2" colspan="4">&nbsp;<b><i><?php echo $pcourtnm?></i></b></td> 
                                    </tr>
                                    <?php $lineno += 2 ; $pcourtind = 'N' ; } ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2" colspan="1">&nbsp;<?php echo strtoupper($report_row['client_name']) ?></td>
                                        <td align="left"  class="p-2" colspan="1">&nbsp;<?php echo date_conv($report_row['exp_date']) ?></td> 
                                        <td align="left"  class="p-2" colspan="1">&nbsp;<?php echo strtoupper($report_row['narration']) ?></td>
                                        <td align="right" class="p-2" colspan="1">&nbsp;<?php echo number_format($report_row['exp_amount'] ,2,'.','') ?>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2" colspan="4">&nbsp;&nbsp;&nbsp;<i><?php echo strtoupper($report_row['matter_desc'])?></i></td>
                                    </tr>
            <?php     
                    $lineno  += 2 ;
                    $cexpamt += $report_row['exp_amount'] ;
                    $report_row = ($rowcnt < $report_cnt) ? $trandtl_qry[$rowcnt] : $report_row;  
                    $rowcnt = $rowcnt + 1 ;
                }  
            ?>
                                    
                                    <tr class="fs-14">
                                        <td align="right"  class="p-2" colspan="3" style="background-color:#eff3b1;"><b> Court Total</b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color:#eff3b1;"><b><?php if($cexpamt > 0) { echo number_format($cexpamt,2,'.','') ;}?></b>&nbsp;</td>
                                    </tr>
            <?php
                    $lineno  += 3;
                    $texpamt += $cexpamt ;
                }
            ?>                   <tr class="fs-14"><td colspan="4" class="p-1">&nbsp;</td></tr>
                                    <tr class="fs-14">
                                        <td align="right" class="p-2" colspan="3" style="background-color:#bee9f7;"><b> PERIOD TOTAL </b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if($texpamt > 0) { echo number_format($texpamt,2,'.','') ;}?></b>&nbsp;</td>
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
        console.log(document.courtwiseExpenses);
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.courtwiseExpenses.start_date.value.substring(6,10)+document.courtwiseExpenses.start_date.value.substring(3,5)+document.courtwiseExpenses.start_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period Start Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.courtwiseExpenses.start_date.focus()}, 500) });
            return false;
        }
        else if (document.courtwiseExpenses.end_date.value.substring(6,10)+document.courtwiseExpenses.end_date.value.substring(3,5)+document.courtwiseExpenses.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period End Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.courtwiseExpenses.end_date.focus()}, 500) });
            return false;
        }
        else if (document.courtwiseExpenses.start_date.value.substring(6,10)+document.courtwiseExpenses.start_date.value.substring(3,5)+document.courtwiseExpenses.start_date.value.substring(0,2)>document.courtwiseExpenses.end_date.value.substring(6,10)+document.courtwiseExpenses.end_date.value.substring(3,5)+document.courtwiseExpenses.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'Period End Date must be less than Period Start Date' }).then((result) => { setTimeout(() => {document.courtwiseExpenses.end_date.focus()}, 500) });
            return false;
        }
        
        document.courtwiseExpenses.submit();
    }
</script>
<?= $this->endSection() ?>
