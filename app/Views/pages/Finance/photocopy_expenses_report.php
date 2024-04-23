<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>
<?php if((!isset($bill_qry))) { ?>
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    
        <div class="pagetitle">
        <h1>Photocopy Expenses (Court/Client/Matter)</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="photocopyExpenses" name="photocopyExpenses" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">As On <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-100 float-start" name="ason_date" value="<?= date('d-m-Y')?>" onBlur="make_date(this)" readonly />
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
                            <input type="text" class="form-control w-45 float-start datepicker" name="start_date" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required>
                            <span class="w-2 float-start mx-1">--</span>
                            <input type="text" class="form-control w-45 float-start datepicker" name="end_date" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required>
                        </div>

                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Code </label>					
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" name="court_code"/>
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Name</label>					
                            <input class="form-control w-100 float-start" name="court_name" id="courtName" readonly>
                        </div>

                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Code </label>					
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code"/>
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Name</label>					
                            <input class="form-control w-100 float-start" name="client_name" id="clientName" readonly>
                        </div>
                        
                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Code </label>					
                            <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc'], ['matter_desc'], 'matter_code')" size="05" maxlength="06" name="matter_code"/>
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Description </label>					
                            <input class="form-control w-100 float-start" name="matter_desc" id="matterDesc"  readonly>
                        </div>
                        
                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Initial Code </label>					
                            <input type="text" class="form-control w-100 float-start" id="initialCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'initial_code', ['initialName'], ['initial_name'], 'initial_code')" size="05" maxlength="06" name="initial_code"/>
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('initial_code', '<?= $displayId['initial_help_id'] ?>', 'initialCode', ['initialName'], ['initial_name'], 'initial_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Initial Name</label>					
                            <input class="form-control w-100 float-start" name="initial_name" id="initialName" readonly>
                        </div>
                        
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Report Seq <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="report_seqn" required >
                                <option value="R">Court-wise</option>
                                <option value="M">Matter-wise</option>
                                <option value="C">Client-wise</option>
                                <option value="T">Court-wise/Client-wise</option>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Report Type <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="report_type" required >
                                <option value="D">Detail</option>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="output_type" required >
                                <option value="Report">View Report</option>
                                <option value="Pdf">Download PDF</option>
                                <option value="Excel" >Download Excel</option>
                            </select>
                        </div>
                        
                        <div class="col-md-9 d-inline-block mt-2">
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
				<?php if ($renderFlag) : ?>
						<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
            <?php
                $maxline = 35 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tbilamt = 0; 
                $tcolamt = 0; 
                $tdefamt = 0; 
                $tbalamt = 0; 
                $ltotamt  = 0;
                $ttotamt   = 0;
                $rowcnt     = 1 ;
                $report_row = isset($bill_qry[$rowcnt-1]) ? $bill_qry[$rowcnt-1] : '' ;   
                $report_cnt = $params['bill_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $cbilamt   = 0; 
                $ccolamt   = 0; 
                $cdefamt   = 0; 
                $cbalamt   = 0; 
                $pcourtind = 'Y' ;
                $pcourtcd  = isset($report_row['court_code']) ? $report_row['court_code'] : '' ;
                $pcourtnm  = isset($report_row['court_name']) ? $report_row['court_name'] : '' ;
                while($pcourtcd == '' && $rowcnt <= $report_cnt)
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
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Client</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['client_name']?></b></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y')?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Status As On</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr class="fs-14">
                            <th height="18" width="10%" align="left"  class="py-3 px-2">Doc No</th>
                            <th height="18" width="07%" align="left"  class="py-3 px-2">Doc Dt</th>
                            <th height="18" width="43%" align="left"  class="py-3 px-2">Client / Matter / Narration</th>
                            <th height="18" width="10%" align="right" class="py-3 px-2">Amount (Rs.)&nbsp;</th>
                        </tr>
                                    
            <?php
                        $lineno = 9 ;
                        $pcourtind = 'Y' ;
                    }

                    if ($pcourtind == 'Y') 
                    { 
            ?>
                                    <tr class="fs-14>
                                        <td height="22" align="left" class="p-2" colspan="7"><b><?php echo $pcourtnm?></b></td> 
                                    </tr>
            <?php
                    $lineno = $lineno + 1 ;
                    $pcourtind = 'N' ;
                    $sub_ac_code = $report_row['matter_code'] ;
                    $sub_ac_desc = $report_row['matter_desc'] ;

                    }
            ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2"><?php echo $report_row['doc_no']?></td> 
                                        <td align="left"  class="p-2"><?php echo date_conv($report_row['doc_date'])?></td> 
                                        <td align="left"  class="p-2"><b><?php echo strtoupper($report_row['client_name'])?></b></td>
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                        <td align="left" class="p-2" style="vertical-align:top"><?php if ($report_row['matter_code'] != '') {echo strtoupper($report_row['matter_desc']).'<b> ['.$report_row['matter_code'].']</b>';} else {echo '<b> '.'OFFICE'.' </b>';}?></td>
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                    </tr>

                                    <tr class="fs-14">
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                        <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                        <td align="left" class="p-2" style="vertical-align:top"><?php echo strtoupper($report_row['narration'])?></td>
                                        <td align="right" class="p-2"><?php if($report_row['net_amount'] > 0) { echo number_format($report_row['net_amount'],2,'.',''); }?>&nbsp;</td>
                                    </tr>

                                    <tr class="fs-14">
                                        <td height="10">&nbsp;</td>
                                    </tr>  
            <?php     
                    $lineno = $lineno + 3;
                    $ltotamt = $ltotamt + $report_row['net_amount'] ;
                    //
                    $report_row = ($rowcnt < $report_cnt) ? $bill_qry[$rowcnt] : $report_row; 
                    $rowcnt = $rowcnt + 1 ;
                }  
            ?>
                                    <tr class="fs-14">
                                        <td height="20" align="right"   class="p-2" colspan="3" style="background-color: #e2e6506e;"><b> Total</b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($ltotamt > 0) { echo number_format($ltotamt,2,'.','') ;}?></b>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="15" colspan="11">&nbsp;</td>
                                    </tr>
            <?php
                    $lineno  = $lineno + 2;
                    $ttotamt = $ttotamt + $ltotamt ;
                }
            ?>                    
                                    <tr class="fs-14">
                                        <td height="20" align="right" class="p-2" colspan="3" style="background-color: #91d6ec6e;"><b> Grand Total </b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.','') ;}?></b>&nbsp;</td>
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
        var asondt     = document.photocopyExpenses.ason_date.value ; 
        var asdtymd    = asondt.substr(6,4)+asondt.substr(3,2)+asondt.substr(0,2) ;

        if (document.photocopyExpenses.start_date.value.substring(6,10)+document.photocopyExpenses.start_date.value.substring(3,5)+document.photocopyExpenses.start_date.value.substring(0,2) > asdtymd) {
            Swal.fire({ text: 'Start Date must be less than equal to As On Date !!!' }).then((result) => { setTimeout(() => {document.photocopyExpenses.start_date.focus()}, 500) });
            return false;
        }
        else if (document.photocopyExpenses.end_date.value.substring(6,10)+document.photocopyExpenses.end_date.value.substring(3,5)+document.photocopyExpenses.end_date.value.substring(0,2) > asdtymd) {
            Swal.fire({ text: 'End Date must be less than or equal to As On Date !!!' }).then((result) => { setTimeout(() => {document.photocopyExpenses.end_date.focus()}, 500) });
            return false;
        }
        else if (document.photocopyExpenses.start_date.value.substring(6,10)+document.photocopyExpenses.start_date.value.substring(3,5)+document.photocopyExpenses.start_date.value.substring(0,2) > document.photocopyExpenses.end_date.value.substring(6,10)+document.photocopyExpenses.end_date.value.substring(3,5)+document.photocopyExpenses.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'End Date must be less than or equal to Start Date' }).then((result) => { setTimeout(() => {document.photocopyExpenses.end_date.focus()}, 500) });
            return false;
        }  
        
        document.photocopyExpenses.submit();
    }
</script>
<?= $this->endSection() ?>