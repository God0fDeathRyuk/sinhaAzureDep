<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>

<?php if (!isset($reports)) { ?> 
  
<main id="main" class="main">

<?php if (session()->getFlashdata('message') !== NULL) : ?>
<div id="alertMsg">
    <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
    <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

    <div class="pagetitle col-md-12 float-start border-bottom pb-1">
    <h1>O/s Bill (Details) </h1>
    </div>

    <form action="" method="post">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">As On</label>
                    <input type="text" class="form-control float-start w-60 ms-0 set-date datepicker withdate" name="ason_date" readonly/>
                </div>
                <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
                    <select class="form-select cstm-inpt" name="branch_code">
                    <?php foreach($data['branches'] as $branch) { ?>
                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="frms-sec-insde d-block float-start col-md-9 ps-2 mb-4">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Billing Period</label>
                    <span class="float-start mt-2">From</span>
                    <input type="text" class="form-control float-start w-48 ms-2 set-date datepicker" id="" placeholder="dd-mm-yyyy" name="start_date" value="<?= $curr_fyrsdt ?>" onBlur="make_date(this)"/>
                    <span class="float-start mt-2 ms-2">To</span>
                    <input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="end_date" onBlur="make_date(this)" />
                </div>
                <div class="col-md-2 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code" />
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>

                </div>
                <div class="col-md-4 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
                    <input type="text" class="form-control" id="clientName" oninput="this.value = this.value.toUpperCase()"  name="client_name" readonly/>
                </div>
                <div class="col-md-2 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Matter Code</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc'], ['matter_desc'], 'matter_code')" size="05" maxlength="06" name="matter_code"/>
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc'], ['matter_desc'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <div class="col-md-4 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Matter Desc</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="matterDesc" name="matter_desc" readonly/>
                </div>
                <div class="col-md-2 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Initial Code</label>
                    <input type="text" class="form-control" id="initialCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'initial_code', ['initialName'], ['initial_name'], 'initial_code')" size="05" maxlength="06" name="initial_code"/>
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('initial_code', '<?= $displayId['initial_help_id'] ?>', 'initialCode', ['initialName'], ['initial_name'], 'initial_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <div class="col-md-4 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Initial name</label>
                    <input type="text" class="form-control" id="initialName" oninput="this.value = this.value.toUpperCase()" name="initial_name" readonly/>
                </div>
                <div class="col-md-2 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Court Code</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" size="05" maxlength="06" name="court_code"/>
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <div class="col-md-4 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Court Name</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="courtName" name="court_name" readonly/>
                </div>
                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Reference Type</label>
                    <select class="form-select" name="reference_type">
                    <option value="%">All</option>
                    <?php foreach($reftyp_qry as $reftyp_row) { ?>
                    <option value="<?php echo $reftyp_row['code_code'];?>"><?php echo $reftyp_row['code_desc'];?></option>
                    <?php } ?>
                    </select>
                </div>
                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Bill Status</label>
                    <select class="form-select" name="collectable_ind">
                    <option value="%">All</option>
                    <option value="C">Collectable</option>
                    <option value="D">Doubtful</option>
                    <option value="B">Bad</option>
                    </select>
                </div>
                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Report Seq</label>
                    <select class="form-select" name="report_seqn">
                    <option value="B">Bill No wise</option>
                    <option value="C">Client wise</option>
                    <option value="M">Matter wise</option>
                    <option value="I">Initial wise</option>
                    </select>
                </div>
                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type</label>
                    <select class="form-select" name="output_type">
                    <option value="Report">Report</option>
                    <option value="Excel">Excel</option>
                    </select>
                </div>
        </div>
             <button type="submit" class="btn btn-primary cstmBtn mt-3">Proceed</button>
             <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
     </form>
</main>
<?php } else { ?>
    <script>
			document.getElementById('sidebar').style.display = "none";
			document.getElementById('burgerMenu').style.display = "none";
		</script>
		<div class="tbl-sec d-inline-block w-100 p-3 position-relative bg-white">
			<div class="position-absolute btndv">
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a> -->
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
			</div>


            <?php if($params['report_seqn'] == 'B'){
                $maxline = 80 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tbilamt = 0; 
                $tcolamt = 0; 
                $tbalamt = 0; 
                $report_cnt = $params['bill_cnt'] ;
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
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
                    <table class="table border-0" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="border-0 pb-0">    
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b>Sinha and Company</b></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_desc']);?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name'];?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date'];?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ;?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno;?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Reference</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['reference_desc'] ;?></b></td>
                                    <td class="report_label_text" align="right">Seqn&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b>[ Bill No ]</b></td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr>
                            <td colspan="4" class="grid_header">
                                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                    <tr class="fs-14">
                                    <th width="12%" align="left"  class="py-3 px-2">Bill No / Date</th>
                                    <th width="34%" align="left"  class="py-3 px-2">Client / Matter</th>
                                    <th width="6%" align="left"  class="py-3 px-2">Intl</th>
                                    <th width="7%" align="right" class="py-3 px-2">IP&nbsp;</th>
                                    <th width="7%" align="right" class="py-3 px-2">OP&nbsp;</th>
                                    <th width="8%" align="right" class="py-3 px-2">COUN&nbsp;</th>
                                    <th width="8%" align="right" class="py-3 px-2">Billed&nbsp;</th>
                                    <th width="8%" align="right" class="py-3 px-2">Realised&nbsp;</th>
                                    <th width="10%" align="right" class="py-3 px-2">Balance&nbsp;</th>
                                    </tr>
                <?php
                            $lineno = 8 ;
                        }
                ?>
                                    <tr class="fs-14 border-0">
                                    <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo $report_row['bill_number'];?>&nbsp;</td> 
                                    <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo strtoupper($report_row['client_name']);?>&nbsp;</td>
                                    <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo strtoupper($report_row['initial_code']);?>&nbsp;</td>
                                    <td align="left" class="p-2" style="background-color: #e2e6506e;"><?php if($report_row['bill_amount_inpocket'] > 0) { echo number_format($report_row['bill_amount_inpocket'],2,'.',''); } ?>&nbsp;</td>
                                    <td align="left" class="p-2" style="background-color: #e2e6506e;"><?php if($report_row['bill_amount_outpocket'] > 0) { echo number_format($report_row['bill_amount_outpocket'],2,'.',''); } ?>&nbsp;</td>
                                    <td align="left" class="p-2" style="background-color: #e2e6506e;"><?php if($report_row['bill_amount_counsel'] > 0) { echo number_format($report_row['bill_amount_counsel'],2,'.',''); } ?>&nbsp;</td>
                                    <td align="left" class="p-2" style="background-color: #e2e6506e;"><?php if($report_row['billed_amount'] > 0) { echo number_format($report_row['billed_amount'],2,'.',''); } ?>&nbsp;</td>
                                    <td align="left" class="p-2" style="background-color: #e2e6506e;"><?php if($report_row['realised_amount'] > 0) { echo number_format($report_row['realised_amount'],2,'.',''); }?>&nbsp;</td>
                                    <td align="left" class="p-2" style="background-color: #e2e6506e;"><?php if($report_row['billed_amount']-$report_row['realised_amount'] > 0) { echo number_format(($report_row['billed_amount']-$report_row['realised_amount']),2,'.',''); }?>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14 border-0">
                                    <td align="left" class="p-2" style="vertical-align:top"><?php echo date_conv($report_row['bill_date']);?>&nbsp;</td> 
                                    <td align="left" class="p-2" style="vertical-align:top" colspan="2"><?php echo strtoupper($report_row['matter_desc']);?>&nbsp;</td>
                                    <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                    <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                    <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td height="10">&nbsp;</td>
                                    </tr>  
                <?php     
                        $lineno = $lineno + 3;
                        $tbilamt = $tbilamt + $report_row['billed_amount'] ;
                        $tcolamt = $tcolamt + $report_row['realised_amount'] ;
                        $tbalamt = $tbalamt + $report_row['billed_amount'] - $report_row['realised_amount'] ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>                   
                                    <tr class="fs-14 border-0">
                                    <td height="20" align="left" style="background-color: #e2e6506e;"  class="p-2">&nbsp;</td>
                                    <td height="20" align="right" style="background-color: #e2e6506e;" class="p-2"><b>GRAND TOTAL</b>&nbsp;</td>
                                    <td height="20" align="left" style="background-color: #e2e6506e;"  class="p-2">&nbsp;</td>
                                    <td height="20" align="left" style="background-color: #e2e6506e;"  class="p-2">&nbsp;</td>
                                    <td height="20" align="left" style="background-color: #e2e6506e;"  class="p-2">&nbsp;</td>
                                    <td height="20" align="left" style="background-color: #e2e6506e;"  class="p-2">&nbsp;</td>
                                    <td height="20" align="right" style="background-color: #e2e6506e;" class="p-2"><b><?php if($tbilamt > 0) { echo number_format($tbilamt,2,'.','') ;}?></b>&nbsp;</td>
                                    <td height="20" align="right" style="background-color: #e2e6506e;" class="p-2"><b><?php if($tcolamt > 0) { echo number_format($tcolamt,2,'.','') ;}?></b>&nbsp;</td>
                                    <td height="20" align="right" style="background-color: #e2e6506e;" class="p-2"><b><?php if($tbalamt > 0) { echo number_format($tbalamt,2,'.','') ;}?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
            <?php } ?>
            <?php if($params['report_seqn'] == 'C'){ 
                $maxline = 52 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tbilamt = 0; 
                $tcolamt = 0; 
                $tbalamt = 0;  
                $rowcnt     = 1 ;
                $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                $report_cnt = $params['bill_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                
                $bbilamt  = 0; 
                $bcolamt  = 0; 
                $bbalamt  = 0; 
                $pclntind = 'Y' ;
                $pclntcd  = $report_row['client_code'] ;  
                $pclntnm  = $report_row['client_name'] ;  
                while($pclntcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
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
                <table class="table border-0" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="border-0 pb-0">    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                
                                <tr>
                                <td class="report_label_text" colspan="4" align="center"><b>Sinha and Company</b></td>
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
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">&nbsp;Period</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">&nbsp;Reference</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['reference_desc'] ?></b></td>
                                <td class="report_label_text" align="right">Seqn&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b>[ Client ]</b></td>
                                </tr>
                            </table>
                        </td>    
                    </tr>
                    <tr>
                        <td colspan="4" class="grid_header">
                            <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                <tr class="fs-14">
                                <th width="10%" align="left"  class="py-3 px-2">Bill No</th>
                                <th width="08%" align="left"  class="py-3 px-2">Bill Dt</th>
                                <th width="47%" align="left"  class="py-3 px-2">Matter</th>
                                <th width="05%" align="left"  class="py-3 px-2">Initial</th>
                                <th width="10%" align="right" class="py-3 px-2">Billed&nbsp;</th>
                                <th width="10%" align="right" class="py-3 px-2">Realised&nbsp;</th>
                                <th width="10%" align="right" class="py-3 px-2">Balance&nbsp;</th>
                                </tr>
                <?php
                            $lineno = 8 ;
                            $pclntind = 'Y' ;
                        }

                        if($pclntind == 'Y')
                        {
                ?>
                                        <tr class="fs-14 border-0">
                                        <td height="20" colspan="7" class="report_detail_text dd px-2 py-2 align-text-top" style="background-color: #e2e6506e;"><b><?php echo $pclntnm?></b></td>
                                        </tr>
                <?php
                        $lineno = $lineno + 1;
                        $pclntind = 'N' ;
                        }
                ?>
                                <tr class="fs-14 border-0">
                                <td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['bill_number']?></td> 
                                <td align="left"  class="p-2" style="vertical-align:top"><?php echo date_conv($report_row['bill_date'])?></td> 
                                <td align="left"  class="p-2" style="vertical-align:top"><?php echo strtoupper($report_row['matter_desc'])?></td>
                                <td align="left"  class="p-2" style="vertical-align:middle"><?php echo strtoupper($report_row['initial_code'])?></td>
                                <td align="left" class="p-2"><?php if($report_row['billed_amount'] > 0) { echo number_format($report_row['billed_amount'],2,'.',''); } ?>&nbsp;</td>
                                <td align="left" class="p-2"><?php if($report_row['realised_amount'] > 0) { echo number_format($report_row['realised_amount'],2,'.',''); }?>&nbsp;</td>
                                <td align="left" class="p-2"><?php if($report_row['billed_amount']-$report_row['realised_amount'] > 0) { echo number_format(($report_row['billed_amount']-$report_row['realised_amount']),2,'.',''); }?>&nbsp;</td>
                                </tr>
                <?php     
                        $lineno = $lineno + 1;
                        $bbilamt = $bbilamt + $report_row['billed_amount'] ;
                        $bcolamt = $bcolamt + $report_row['realised_amount'] ;
                        $bbalamt = $bbalamt + $report_row['billed_amount'] - $report_row['realised_amount'] ;
                        //
                        $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>                   
                                <tr class="fs-14 border-0">
                                <td colspan="7">&nbsp;</td>
                                </tr>
                                <tr class="fs-14 border-0">
                                <td align="left"   class="p-2" colspan="4"><b>** Total</b>&nbsp;</td>
                                <td align="right"  class="p-2"><b><?php if($bbilamt > 0) { echo number_format($bbilamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td align="right"  class="p-2"><b><?php if($bcolamt > 0) { echo number_format($bcolamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td align="right"  class="p-2"><b><?php if($bbalamt > 0) { echo number_format($bbalamt,2,'.','') ;}?></b>&nbsp;</td>
                                </tr>
                <?php
                        $lineno = $lineno + 3;
                        $tbilamt = $tbilamt + $bbilamt ;
                        $tcolamt = $tcolamt + $bcolamt ;
                        $tbalamt = $tbalamt + $bbalamt ;
                    }
                ?>
                                <tr class="fs-14 border-0">
                                <td colspan="7">&nbsp;</td>
                                </tr>
                                <tr class="fs-14 border-0">
                                <td align="center" class="p-2" colspan="4"><b>GRAND TOTAL</b>&nbsp;</td>
                                <td align="right"  class="p-2"><b><?php if($tbilamt > 0) { echo number_format($tbilamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td align="right"  class="p-2"><b><?php if($tcolamt > 0) { echo number_format($tcolamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td align="right"  class="p-2"><b><?php if($tbalamt > 0) { echo number_format($tbalamt,2,'.','') ;}?></b>&nbsp;</td>
                                </tr>
                        </table>
                        </td>
                    </tr>
                </table> 

            <?php } ?>
            <?php if($params['report_seqn'] == 'M'){ 
                $maxline = 52 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tbilamt = 0; 
                $tcolamt = 0; 
                $tbalamt = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                $report_cnt = $params['bill_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
      
                $bbilamt  = 0; 
                $bcolamt  = 0; 
                $bbalamt  = 0; 
                $pmatrind = 'Y' ;
                $pmatrcd  = $report_row['matter_code'] ;  
                $pmatrnm  = $report_row['matter_desc'] ;  
                while($pmatrcd == $report_row['matter_code'] && $rowcnt <= $report_cnt)
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
                <table width="950" align="center" border="0" cellspacing="0" cellpadding="0">
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
                                <td class="report_label_text" colspan="4" align="center"><b>Sinha and Company</b></td>
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
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">&nbsp;Period</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">&nbsp;Reference</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['reference_desc'] ?></b></td>
                                <td class="report_label_text" align="right">Seqn&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b>[ Matter ]</b></td>
                                </tr>
                            </table>
                        </td>    
                    </tr>
                    <tr>
                        <td colspan="4" class="grid_header">
                            <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                <tr>
                                <td height="18" width="10%" align="left"  class="report_detail_tb">Bill No</td>
                                <td height="18" width="08%" align="left"  class="report_detail_tb">Bill Dt</td>
                                <td height="18" width="47%" align="left"  class="report_detail_tb">Client</td>
                                <td height="18" width="05%" align="left"  class="report_detail_tb">Intl</td>
                                <td height="18" width="10%" align="right" class="report_detail_tb">Billed&nbsp;</td>
                                <td height="18" width="10%" align="right" class="report_detail_tb">Realised&nbsp;</td>
                                <td height="18" width="10%" align="right" class="report_detail_tb">Balance&nbsp;</td>
                                </tr>
                <?php
                            $lineno = 8 ;
                            $pmatrind = 'Y' ;
                        }

                        if($pmatrind == 'Y')
                        {
                ?>
                                <tr>
                                <td height="20" colspan="6" class="report_detail_text"><b><?php echo $pmatrnm?></b></td>
                                </tr>
                <?php
                        $lineno = $lineno + 1;
                        $pmatrind = 'N' ;
                        }
                ?>
                                <tr>
                                <td align="left"  class="report_detail_none" style="vertical-align:top"><?php echo $report_row['bill_number']?></td> 
                                <td align="left"  class="report_detail_none" style="vertical-align:top"><?php echo date_conv($report_row['bill_date'])?></td> 
                                <td align="left"  class="report_detail_none" style="vertical-align:top"><?php echo strtoupper($report_row['client_name'])?></td>
                                <td align="left"  class="report_detail_none" style="vertical-align:top"><?php echo strtoupper($report_row['initial_code'])?></td>
                                <td align="right" class="report_detail_none"><?php if($report_row['billed_amount'] > 0) { echo number_format($report_row['billed_amount'],2,'.',''); } ?>&nbsp;</td>
                                <td align="right" class="report_detail_none"><?php if($report_row['realised_amount'] > 0) { echo number_format($report_row['realised_amount'],2,'.',''); }?>&nbsp;</td>
                                <td align="right" class="report_detail_none"><?php if($report_row['billed_amount']-$report_row['realised_amount'] > 0) { echo number_format(($report_row['billed_amount']-$report_row['realised_amount']),2,'.',''); }?>&nbsp;</td>
                                </tr>
                <?php     
                        $lineno = $lineno + 1;
                        $bbilamt = $bbilamt + $report_row['billed_amount'] ;
                        $bcolamt = $bcolamt + $report_row['realised_amount'] ;
                        $bbalamt = $bbalamt + $report_row['billed_amount'] - $report_row['realised_amount'] ;
                        //
                        $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>                   
                                <tr>
                                <td colspan="7">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="20" align="left"   class="report_detail_bottom" colspan="4"><b>** Total</b>&nbsp;</td>
                                <td height="20" align="right"  class="report_detail_bottom"><b><?php if($bbilamt > 0) { echo number_format($bbilamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td height="20" align="right"  class="report_detail_bottom"><b><?php if($bcolamt > 0) { echo number_format($bcolamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td height="20" align="right"  class="report_detail_bottom"><b><?php if($bbalamt > 0) { echo number_format($bbalamt,2,'.','') ;}?></b>&nbsp;</td>
                                </tr>
                <?php
                        $lineno = $lineno + 3;
                        $tbilamt = $tbilamt + $bbilamt ;
                        $tcolamt = $tcolamt + $bcolamt ;
                        $tbalamt = $tbalamt + $bbalamt ;
                    }
                ?>
                                <tr>
                                <td colspan="7">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="20" align="center" class="report_detail_bottom" colspan="4"><b>* * *  GRAND TOTAL * * *</b>&nbsp;</td>
                                <td height="20" align="right"  class="report_detail_bottom"><b><?php if($tbilamt > 0) { echo number_format($tbilamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td height="20" align="right"  class="report_detail_bottom"><b><?php if($tcolamt > 0) { echo number_format($tcolamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td height="20" align="right"  class="report_detail_bottom"><b><?php if($tbalamt > 0) { echo number_format($tbalamt,2,'.','') ;}?></b>&nbsp;</td>
                                </tr>
                        </table>
                        </td>
                    </tr>
                </table> 
            <?php } ?> 
            <?php if($params['report_seqn'] == 'I'){
                $maxline = 52 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tbilamt = 0; 
                $tcolamt = 0; 
                $tbalamt = 0; 
                $rowcnt  = 1 ;
                $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                //$report_row = mysql_fetch_array($reports); 
                $report_cnt = $params['bill_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                
                $bbilamt  = 0; 
                $bcolamt  = 0; 
                $bbalamt  = 0; 
                $pintlind = 'Y' ;
                $pintlcd  = $report_row['initial_code'] ;  
                $pintlnm  = $report_row['initial_name'] ;  
                while($pintlcd == $report_row['initial_code'] && $rowcnt <= $report_cnt)
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
                <table class="w-100" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>    
                            <table class="table border-0" cellspacing="0" cellpadding="0">
                                <tr>
                                <tr>
                                <td class="text-center border-0" colspan="4" align="center"><b>Sinha and Company</b></td>
                                </tr>
                                <tr>
                                <td class="border-0" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td>
                                </tr>
                                <tr>
                                <td class="border-0 py-0">&nbsp;Branch</td>
                                <td class="border-0 py-0">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                <td class="border-0 py-0" align="right">Date&nbsp;&nbsp;</td>
                                <td class="border-0 py-0">&nbsp;:&nbsp;<?= $params['date']?></td>
                                </tr>
                                <tr>
                                <td class="border-0 py-0">&nbsp;Period</td>
                                <td class="border-0 py-0">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                <td class="border-0 py-0" align="right">Page&nbsp;&nbsp;</td>
                                <td class="border-0 py-0">&nbsp;:&nbsp;<?= $pageno ?></td>
                                </tr>
                                <tr>
                                <td class="border-0 py-0">&nbsp;Reference</td>
                                <td class="border-0 py-0">&nbsp;:&nbsp;<b><?php echo $params['reference_desc'] ?></b></td>
                                <td class="border-0 py-0" align="right">Seqn&nbsp;&nbsp;</td>
                                <td class="border-0 py-0">&nbsp;:&nbsp;<b>[ Initial ]</b></td>
                                </tr>
                            </table>
                        </td>    
                    </tr>
                    <tr class="fs-14">
                        <td colspan="4" class="grid_header">
                            <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                <tr class="fs-14">
                                <th height="18" width="10%" align="left"  class="px-3 py-2">Bill No</th>
                                <th height="18" width="08%" align="left"  class="px-3 py-2">Bill Dt</th>
                                <th height="18" width="52%" align="left"  class="px-3 py-2">Client / Matter</th>
                                <th height="18" width="10%" align="right" class="px-3 py-2">Billed&nbsp;</th>
                                <th height="18" width="10%" align="right" class="px-3 py-2">Realised&nbsp;</th>
                                <th height="18" width="10%" align="right" class="px-3 py-2">Balance&nbsp;</th>
                                </tr>
                <?php
                            $lineno = 8 ;
                            $pintlind = 'Y' ;
                        }

                        if($pintlind == 'Y')
                        {
                ?>
                                        <tr>
                                        <td height="20" colspan="6" class="report_detail_text dd px-2 py-2 align-text-top" style="background-color: #e2e6506e;"><b><?php echo strtoupper($pintlnm) ?></b></td>
                                        </tr>
                <?php
                        $lineno = $lineno + 1;
                        $pintlind = 'N' ;
                        }
                ?>
                                <tr class="fs-14">
                                <td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['bill_number']?></td> 
                                <td align="left"  class="p-2" style="vertical-align:top"><?php echo date_conv($report_row['bill_date'])?></td> 
                                <td align="left"  class="p-2" style="vertical-align:top"><?php echo strtoupper($report_row['client_name'])?></td>
                                <td align="right" class="p-2"><?php if($report_row['billed_amount'] > 0) { echo number_format($report_row['billed_amount'],2,'.',''); } ?>&nbsp;</td>
                                <td align="right" class="p-2"><?php if($report_row['realised_amount'] > 0) { echo number_format($report_row['realised_amount'],2,'.',''); }?>&nbsp;</td>
                                <td align="right" class="p-2"><?php if($report_row['billed_amount']-$report_row['realised_amount'] > 0) { echo number_format(($report_row['billed_amount']-$report_row['realised_amount']),2,'.',''); }?>&nbsp;</td>
                                </tr>
                                <tr class="fs-14 border-0">
                                <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                                <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left"  class="p-2" style="vertical-align:top"><?php echo strtoupper($report_row['matter_desc'])?></td>
                                <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="10">&nbsp;</td>
                                </tr>  
                <?php     
                        $lineno = $lineno + 3;
                        $bbilamt = $bbilamt + $report_row['billed_amount'] ;
                        $bcolamt = $bcolamt + $report_row['realised_amount'] ;
                        $bbalamt = $bbalamt + $report_row['billed_amount'] - $report_row['realised_amount'] ;
                        //
                        $report_row = ($rowcnt < $params['bill_cnt']) ? $reports[$rowcnt] : $report_row; 
                        $rowcnt += 1;   
                    }  
                ?>                   
                                <tr class="fs-14 border-0">
                                <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr class="fs-14 border-0">
                                <td height="20" align="right"  class="p-2" colspan="3" style="background-color: #e2e6506e;"><b> Total</b>&nbsp;</td>
                                <td height="20" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($bbilamt > 0) { echo number_format($bbilamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td height="20" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($bcolamt > 0) { echo number_format($bcolamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td height="20" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($bbalamt > 0) { echo number_format($bbalamt,2,'.','') ;}?></b>&nbsp;</td>
                                </tr>
                <?php
                        $lineno = $lineno + 3;
                        $tbilamt = $tbilamt + $bbilamt ;
                        $tcolamt = $tcolamt + $bcolamt ;
                        $tbalamt = $tbalamt + $bbalamt ;
                    }
                ?>
                                <tr>
                                <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="20" align="center" class="py-2" colspan="3" style="background-color: #94c0cf;"><b> GRAND TOTAL </b>&nbsp;</td>
                                <td height="20" align="right"  class="py-2" style="background-color: #94c0cf;"><b><?php if($tbilamt > 0) { echo number_format($tbilamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td height="20" align="right"  class="py-2" style="background-color: #94c0cf;"><b><?php if($tcolamt > 0) { echo number_format($tcolamt,2,'.','') ;}?></b>&nbsp;</td>
                                <td height="20" align="right"  class="py-2" style="background-color: #94c0cf;"><b><?php if($tbalamt > 0) { echo number_format($tbalamt,2,'.','') ;}?></b>&nbsp;</td>
                                </tr>
                        </table>
                        </td>
                    </tr>
                </table> 
            <?php } ?>   
        </div>
<?php } ?>
<!-- End #main -->
<?= $this->endSection() ?>