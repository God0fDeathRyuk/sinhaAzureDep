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
        <?php if (session()->getFlashdata('valid_message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('valid_message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

		<div class="pagetitle col-md-12 float-start border-bottom pb-1">
		<h1>Bill Realisation (Bill/Client/Matter/Initial/Court) </h1>
		</div>

		<form action="" method="post">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
						<select class="form-select cstm-inpt" name="branch_code">
						<?php foreach($data['branches'] as $branch) { ?>
						<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
						<?php } ?>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-9 ps-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Realisation Period</label>
						<span class="float-start mt-2">From</span>
						<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker" id="" placeholder="dd-mm-yyyy" name="realisation_start_date" />
						<span class="float-start mt-2 ms-2">To</span>
						<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="realisation_end_date" />
					</div>
                    <div class="frms-sec-insde d-block float-start col-md-9 ps-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Billing Period</label>
						<span class="float-start mt-2">From</span>
						<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker" id="" placeholder="dd-mm-yyyy" name="billing_start_date" />
						<span class="float-start mt-2 ms-2">To</span>
						<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="billing_end_date" />
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
						<label class="d-inline-block w-100 mb-2 lbl-mn">Realisation Details</label>
						<select class="form-select" name="info_by">
						<option value="B">By Bill</option>
				        <option value="C">By Cheque</option>
						</select>
					</div>
                    <div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Report Seq</label>
						<select class="form-select" name="report_seqn">
                        <option value="B">Doc-wise</option>
                        <option value="C">Client-wise</option>
                        <option value="M">Matter-wise</option>
                        <option value="I">Initial-wise</option>
                        <option value="T">Court-wise</option>
						</select>
					</div>
                    <div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Report Type</label>
						<select class="form-select" name="report_type">
                        <option value="S">Summary</option>
						<option value="D">Detail</option>
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
				<button type="submit" class="btn btn-primary cstmBtn mt-3">Proced</button>
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
        <?php if(($params['info_by'] == 'B') && ($params['report_type'] == 'D')){
            if ($params['report_seqn'] == 'B') 
            {
                $maxline    = 45 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $tbillamt   = 0 ; 
                $trealamt   = 0 ; 
                $tbalnamt   = 0 ; 
                $tdefcamt   = 0 ; 
                $index   = 0; 
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
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Realise Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                </table>
                            </td>    
                        </tr>
                        <tr>
                            <td colspan="4" class="grid_header">
                                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                    <tr class="fs-14">
                                    <th width="130" align="left"  class="py-3 px-2">Bill No</th>
                                    <th width="120" align="left"  class="py-3 px-2">Bill Dt</th>
                                    <th width="550" align="left"  class="py-3 px-2">Client/Matter</th>
                                    <th width="025" align="left"  class="py-3 px-2">Intl</th>
                                    <th width="100" align="right" class="py-3 px-2">Realised&nbsp;</th>
                                    <th width="100" align="right" class="py-3 px-2">Deficit&nbsp;</th>
                                    </tr>
                <?php
                            $lineno = 9 ;
                        }
                        //-----
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                                    <tr class="fs-14 border-0">
                                    <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo $report_row['bill_number']?></td> 
                                    <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo date_conv($report_row['bill_date'])?></td> 
                                    <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo strtoupper($report_row['client_name'])?></td>
                                    <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo strtoupper($report_row['initial_code'])?></td>
                                    <td align="right" class="p-2" style="background-color: #e2e6506e;"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="p-2" style="background-color: #e2e6506e;"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14 border-0">
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="left"  class="p-2"><?php echo strtoupper($report_row['matter_desc'])?></td>
                                    <td align="left"  class="p-2">&nbsp;</td> 
                                    <td align="right" class="p-2">&nbsp;</td>
                                    <td align="right" class="p-2">&nbsp;</td>
                                    </tr>
                <?php     
                        $lineno   = $lineno  + 2;
                        $trealamt = $trealamt + $realamt ;
                        $tdefcamt = $tdefcamt + $defcamt ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }
                ?>                   
                                    <tr>
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14 border-0">
                                    <td align="center" style="background-color: #e2e6506e;" class="p-2" colspan="4"><b>GRAND TOTAL</b>&nbsp;</td>
                                    <td align="right"  style="background-color: #e2e6506e;" class="p-2"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.','') ;}?></b>&nbsp;</td>
                                    <td align="right" style="background-color: #e2e6506e;"  class="p-2"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.','') ;}?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'C') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $tbillamt   = 0 ; 
                $trealamt   = 0 ; 
                $tbalnamt   = 0 ; 
                $tdefcamt   = 0 ; 
                $index   = 0; 
                $report_cnt = $params['bill_cnt'] ; 
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
                {
                $lbillamt  = 0 ; 
                $lrealamt  = 0 ; 
                $lbalnamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['client_code'] ;
                $plevelnm  = '('.$report_row['client_code'].') -- '.$report_row['client_name'] ;
                while($plevelcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
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
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Realise Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                        <tr>
                            <td colspan="4" class="grid_header">
                                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="100" align="left"  class="report_detail_tb">Bill No</td>
                                    <td width="075" align="left"  class="report_detail_tb">Bill Dt</td>
                                    <td width="550" align="left"  class="report_detail_tb">Matter</td>
                                    <td width="025" align="left"  class="report_detail_tb">Intl</td>
                                    <td width="100" align="right" class="report_detail_tb">Realised&nbsp;</td>
                                    <td width="100" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                                    </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                                    <tr>
                                    <td align="left" class="report_detail_none" colspan="6"><b><?php echo $plevelnm?></b></td> 
                                    </tr>
                                    <tr>
                                    <td align="left" class="report_detail_none" colspan="6">&nbsp;</td>
                                    </tr>
                <?php
                        $lineno = $lineno + 2 ;
                        $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                                    <tr>
                                    <td align="left"  class="report_detail_none"><?php echo $report_row['bill_number']?></td> 
                                    <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['bill_date'])?></td> 
                                    <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['matter_desc'])?></td>
                                    <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['initial_code'])?></td>
                                    <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                    </tr>
                <?php     
                        $lineno   = $lineno  + 1;
                        $lrealamt = $lrealamt + $realamt ;
                        $ldefcamt = $ldefcamt + $defcamt ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                    <tr>
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="left"  class="report_detail_bottom" colspan="4"><b>** Total</b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                <?php
                        $lineno   = $lineno + 2;
                        $trealamt = $trealamt + $lrealamt ;
                        $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                                    <tr>
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="center" class="report_detail_bottom" colspan="4"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'M') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $tbillamt   = 0 ; 
                $trealamt   = 0 ; 
                $tbalnamt   = 0 ; 
                $tdefcamt   = 0 ; 
                $index   = 0; 
                $report_cnt = $params['bill_cnt'] ; 
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
                {
                $lbillamt  = 0 ; 
                $lrealamt  = 0 ; 
                $lbalnamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['matter_code'] ;
                $plevelnm  = '('.$report_row['matter_code'].') -- '.$report_row['matter_desc'] ;
                while($plevelcd == $report_row['matter_code'] && $rowcnt <= $report_cnt)
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
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Realise Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                        <tr>
                            <td colspan="4" class="grid_header">
                                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="100" align="left"  class="report_detail_tb">Bill No</td>
                                    <td width="075" align="left"  class="report_detail_tb">Bill Dt</td>
                                    <td width="550" align="left"  class="report_detail_tb">Client</td>
                                    <td width="025" align="left"  class="report_detail_tb">Intl</td>
                                    <td width="100" align="right" class="report_detail_tb">Realised&nbsp;</td>
                                    <td width="100" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                                    </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                                    <tr>
                                    <td align="left" class="report_detail_none" colspan="6"><b><?php echo $plevelnm?></b></td> 
                                    </tr>
                                    <tr>
                                    <td align="left" class="report_detail_none" colspan="6">&nbsp;</td>
                                    </tr>
                <?php
                        $lineno = $lineno + 2 ;
                        $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                                    <tr>
                                    <td align="left"  class="report_detail_none"><?php echo $report_row['bill_number']?></td> 
                                    <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['bill_date'])?></td> 
                                    <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['client_name'])?></td>
                                    <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['initial_code'])?></td>
                                    <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                    </tr>
                <?php     
                        $lineno   = $lineno  + 1;
                        $lrealamt = $lrealamt + $realamt ;
                        $ldefcamt = $ldefcamt + $defcamt ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                    <tr>
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="left"  class="report_detail_bottom" colspan="4"><b>** Total</b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                <?php
                        $lineno   = $lineno + 2;
                        $tbillamt = $tbillamt + $lbillamt ;
                        $trealamt = $trealamt + $lrealamt ;
                        $tbalnamt = $tbalnamt + $lbalnamt ;
                        $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                                    <tr>
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="center" class="report_detail_bottom" colspan="4"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'I') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $tbillamt   = 0 ; 
                $trealamt   = 0 ; 
                $tbalnamt   = 0 ; 
                $tdefcamt   = 0 ; 
                $index   = 0; 
                $report_cnt = $params['bill_cnt'] ; 
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
                {
                $lbillamt  = 0 ; 
                $lrealamt  = 0 ; 
                $lbalnamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['initial_code'] ;
                $plevelnm  = '('.$report_row['initial_code'].') -- '.$report_row['initial_name'] ;
                while($plevelcd == $report_row['initial_code'] && $rowcnt <= $report_cnt)
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
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Realise Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                        <tr>
                            <td colspan="4" class="grid_header">
                                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="100" align="left"  class="report_detail_tb">Bill No</td>
                                    <td width="075" align="left"  class="report_detail_tb">Bill Dt</td>
                                    <td width="575" align="left"  class="report_detail_tb">Client/Matter</td>
                                    <td width="100" align="right" class="report_detail_tb">Realised&nbsp;</td>
                                    <td width="100" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                                    </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                                    <tr>
                                    <td align="left" class="report_detail_none" colspan="6"><b><?php echo $plevelnm?></b></td> 
                                    </tr>
                                    <tr>
                                    <td align="left" class="report_detail_none" colspan="6">&nbsp;</td>
                                    </tr>
                <?php
                        $lineno = $lineno + 2 ;
                        $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                                    <tr>
                                    <td align="left"  class="report_detail_none"><?php echo $report_row['bill_number']?></td> 
                                    <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['bill_date'])?></td> 
                                    <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['client_name'])?></td>
                                    <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="left"  class="report_detail_none">&nbsp;</td> 
                                    <td align="left"  class="report_detail_none">&nbsp;</td> 
                                    <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['matter_desc'])?></td>
                                    <td align="right" class="report_detail_none">&nbsp;</td>
                                    <td align="right" class="report_detail_none">&nbsp;</td>
                                    <td align="right" class="report_detail_none">&nbsp;</td>
                                    <td align="right" class="report_detail_none">&nbsp;</td>
                                    </tr>
                <?php     
                        $lineno   = $lineno  + 3;
                        $lrealamt = $lrealamt + $realamt ;
                        $ldefcamt = $ldefcamt + $defcamt ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                    <tr>
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="left"  class="report_detail_bottom" colspan="3"><b>** Total</b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                <?php
                        $lineno   = $lineno + 2;
                        $trealamt = $trealamt + $lrealamt ;
                        $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                                    <tr>
                                    <td colspan="8">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="center" class="report_detail_bottom" colspan="3"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                    <td align="right"  class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right"  class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'T') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $tbillamt   = 0 ; 
                $trealamt   = 0 ; 
                $tbalnamt   = 0 ; 
                $tdefcamt   = 0 ; 
                $index   = 0; 
                $report_cnt = $params['bill_cnt'] ; 
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
                {
                $lbillamt  = 0 ; 
                $lrealamt  = 0 ; 
                $lbalnamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['court_code'] ;
                $plevelnm  = '('.$report_row['court_code'].') -- '.$report_row['court_name'] ;
                while($plevelcd == $report_row['court_code'] && $rowcnt <= $report_cnt)
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
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Realise Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                        <tr>
                            <td colspan="4" class="grid_header">
                                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                    <tr>
                                    <td width="100" align="left"  class="report_detail_tb">Bill No</td>
                                    <td width="075" align="left"  class="report_detail_tb">Bill Dt</td>
                                    <td width="575" align="left"  class="report_detail_tb">Client/Matter</td>
                                    <td width="100" align="right" class="report_detail_tb">Realised&nbsp;</td>
                                    <td width="100" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                                    </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                                    <tr>
                                    <td align="left" class="report_detail_none" colspan="6"><b><?php echo $plevelnm?></b></td> 
                                    </tr>
                                    <tr>
                                    <td align="left" class="report_detail_none" colspan="6">&nbsp;</td>
                                    </tr>
                <?php
                        $lineno = $lineno + 2 ;
                        $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                                    <tr>
                                    <td align="left"  class="report_detail_none"><?php echo $report_row['bill_number']?></td> 
                                    <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['bill_date'])?></td> 
                                    <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['client_name'])?></td>
                                    <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="left"  class="report_detail_none">&nbsp;</td> 
                                    <td align="left"  class="report_detail_none">&nbsp;</td> 
                                    <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['matter_desc'])?></td>
                                    <td align="right" class="report_detail_none">&nbsp;</td>
                                    <td align="right" class="report_detail_none">&nbsp;</td>
                                    <td align="right" class="report_detail_none">&nbsp;</td>
                                    <td align="right" class="report_detail_none">&nbsp;</td>
                                    </tr>
                <?php     
                        $lineno   = $lineno  + 3;
                        $lrealamt = $lrealamt + $realamt ;
                        $ldefcamt = $ldefcamt + $defcamt ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                    <tr>
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="left"  class="report_detail_bottom" colspan="3"><b>** Total</b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                <?php
                        $lineno   = $lineno + 2;
                        $trealamt = $trealamt + $lrealamt ;
                        $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                                    <tr>
                                    <td colspan="8">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="center" class="report_detail_bottom" colspan="3"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                    <td align="right"  class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right"  class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
            <?php 
            }
            ?>                   
        <?php } ?>

        <?php if(($params['info_by'] == 'B') && ($params['report_type'] == 'S')){
            if ($params['report_seqn'] == 'C') 
            {
                
                //-------------------
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ;  
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
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Realise Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                    <tr>
                        <td colspan="4" class="grid_header">
                            <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                <tr class="fs-14">
                                <th width="06%" align="left" class="py-3 px-2">Code</th>
                                <th width="74%" align="left" class="py-3 px-2">Name</th>
                                <th width="10%" align="left" class="py-3 px-2">Realised&nbsp;</th>
                                <th width="10%" align="left" class="py-3 px-2">Deficit&nbsp;</th>
                                </tr>
                    <?php
                                $lineno = 9 ;
                            }
                            $realamt = $report_row['realamt'];  
                            $defcamt = $report_row['defcamt'];  
                    ?>
                                <tr>
                                <td align="left"  class="report_detail_none"><?php echo $report_row['client_code']?></td> 
                                <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['client_name'])?></td>
                                <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                </tr>
                    <?php     
                            $lineno   = $lineno  + 1;
                            $trealamt = $trealamt + $realamt ;
                            $tdefcamt = $tdefcamt + $defcamt ;
                            //
                            $rowcnt = $rowcnt + 1 ;
                        }
                    ?>                   
                                <tr>
                                <td colspan="8">&nbsp;</td>
                                </tr>
                                <tr>
                                <td align="center" class="report_detail_tb" colspan="2"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                <td align="right"  class="report_detail_tb"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                <td align="right"  class="report_detail_tb"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                </tr>
                        </table>
                        </td>
                    </tr>
                </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'M') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ; 
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
                <table width="750" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <td width="10%">&nbsp;</td>
                                <td width="70%">&nbsp;</td>
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
                                <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Branch</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Realise Period</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                <tr>
                    <td colspan="4" class="grid_header">
                        <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                            <tr>
                            <td width="06%" align="left"  class="report_detail_tb">Code</td>
                            <td width="74%" align="left"  class="report_detail_tb">Name</td>
                            <td width="10%" align="right" class="report_detail_tb">Realised&nbsp;</td>
                            <td width="10%" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                            </tr>
                <?php
                            $lineno = 9 ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                            <tr>
                            <td align="left"  class="report_detail_none"><?php echo $report_row['matter_code']?></td> 
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['matter_desc'])?></td>
                            <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                            <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="left"  class="report_detail_none">&nbsp;</td> 
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['client_name'])?></td>
                            <td align="right" class="report_detail_none">&nbsp;</td>
                            <td align="right" class="report_detail_none">&nbsp;</td>
                            </tr>
                <?php     
                        $lineno   = $lineno  + 2;
                        $trealamt = $trealamt + $realamt ;
                        $tdefcamt = $tdefcamt + $defcamt ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }
                ?>                   
                            <tr>
                            <td colspan="8">&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="center" class="report_detail_tb" colspan="2"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                            <td align="right"  class="report_detail_tb"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right"  class="report_detail_tb"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                    </table>
                    </td>
                </tr>
                </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'I') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ; 
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
                <table width="750" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <td width="10%">&nbsp;</td>
                                <td width="70%">&nbsp;</td>
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
                                <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Branch</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Realise Period</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                <tr>
                    <td colspan="4" class="grid_header">
                        <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                            <tr>
                            <td width="06%" align="left"  class="report_detail_tb">Code</td>
                            <td width="74%" align="left"  class="report_detail_tb">Name</td>
                            <td width="10%" align="right" class="report_detail_tb">Realised&nbsp;</td>
                            <td width="10%" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                            </tr>
                <?php
                            $lineno = 9 ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                            <tr>
                            <td align="left"  class="report_detail_none"><?php echo $report_row['initial_code']?></td> 
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['initial_name'])?></td>
                            <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                            <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                            </tr>
                <?php     
                        $lineno   = $lineno  + 1;
                        $trealamt = $trealamt + $realamt ;
                        $tdefcamt = $tdefcamt + $defcamt ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }
                ?>                   
                            <tr>
                            <td colspan="8">&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="center" class="report_detail_tb" colspan="2"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                            <td align="right"  class="report_detail_tb"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right"  class="report_detail_tb"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                    </table>
                    </td>
                </tr>
                </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'T') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ; 
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
                <table width="750" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <td width="10%">&nbsp;</td>
                                <td width="70%">&nbsp;</td>
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
                                <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Branch</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Realise Period</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                    <tr>
                        <td colspan="4" class="grid_header">
                            <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                <tr>
                                <td width="06%" align="left"  class="report_detail_tb">Code</td>
                                <td width="74%" align="left"  class="report_detail_tb">Name</td>
                                <td width="10%" align="right" class="report_detail_tb">Realised&nbsp;</td>
                                <td width="10%" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                                </tr>
                    <?php
                                $lineno = 9 ;
                            }
                            $realamt = $report_row['realamt'];  
                            $defcamt = $report_row['defcamt'];  
                    ?>
                                <tr>
                                <td align="left"  class="report_detail_none"><?php echo $report_row['court_code']?></td> 
                                <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['court_name'])?></td>
                                <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                </tr>
                    <?php     
                            $lineno   = $lineno  + 1;
                            $trealamt = $trealamt + $realamt ;
                            $tdefcamt = $tdefcamt + $defcamt ;
                            //
                            $rowcnt = $rowcnt + 1 ;
                        }
                    ?>                   
                                <tr>
                                <td colspan="8">&nbsp;</td>
                                </tr>
                                <tr>
                                <td align="center" class="report_detail_tb" colspan="2"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                <td align="right"  class="report_detail_tb"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                <td align="right"  class="report_detail_tb"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                </tr>
                        </table>
                    </td>
                </tr>
                </table> 
            <?php }  ?>
        <?php }  ?>

        <?php if(($params['info_by'] == 'C') && ($params['report_type'] == 'D')){
            if ($params['report_seqn'] == 'B') 
            {
                $maxline    = 40 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ;
                $report_cnt = $params['bill_cnt'] ;
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
                {
                $srealamt  = 0 ; 
                $sdefcamt  = 0 ; 
                $slevelind = 'Y' ;
                $slevelcd1 = $report_row['instrument_no'] ;
                $slevelcd2 = $report_row['instrument_dt'] ;
                $slevelcd3 = $report_row['bank_name'] ;
                $slevelnm  = '';
                while($slevelcd1 == $report_row['instrument_no'] && $slevelcd2 == $report_row['instrument_dt'] && $slevelcd3 == $report_row['bank_name'] && $rowcnt <= $report_cnt)
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
                                <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Branch</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Realise Period</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno ?></td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                            </table>
                    </td>    
                </tr>
                <tr>
                    <td colspan="4" class="grid_header">
                        <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                            <tr class="fs-14">
                            <th width="100" align="left"  class="py-3 px-2">Chq#</th>
                            <th width="100" align="left"  class="py-3 px-2">Chq Dt</th>
                            <th width="160" align="left"  class="py-3 px-2">Bill No</th>
                            <th width="100" align="left"  class="py-3 px-2">Bill Dt</th>
                            <th width="400" align="left"  class="py-3 px-2">Client/Matter</th>
                            <th width="50" align="left"  class="py-3 px-2">Intl</th>
                            <th width="100" align="left"  class="py-3 px-2">Rl. Date&nbsp;</th>
                            <th width="120" align="left" class="py-3 px-2">Realised&nbsp;</th>
                            <th width="075" align="left" class="py-3 px-2">Deficit&nbsp;</th>
                            </tr>
                <?php
                            $lineno = 9 ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                            <tr class="fs-14 border-0">
                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php if($slevelind=='Y') { echo $report_row['instrument_no'] ; } else { echo '&nbsp;' ; } ?></td> 
                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php if($slevelind=='Y') { echo date_conv($report_row['instrument_dt']); } else { echo '&nbsp;' ; }?></td> 
                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo $report_row['bill_number']?></td> 
                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo date_conv($report_row['bill_date'])?></td> 
                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo strtoupper($report_row['client_name'])?></td>
                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo strtoupper($report_row['initial_code'])?></td>
                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><?php echo date_conv($report_row['doc_date'])?></td> 
                            <td align="right" class="p-2" style="background-color: #e2e6506e;"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                            <td align="right" class="p-2" style="background-color: #e2e6506e;"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                            </tr>
                            <tr class="fs-14 border-0">
                            <td align="left"  class="p-2">&nbsp;</td> 
                            <td align="left"  class="p-2">&nbsp;</td> 
                            <td align="left"  class="p-2">&nbsp;</td> 
                            <td align="left"  class="p-2">&nbsp;</td> 
                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['matter_desc'])?></td>
                            <td align="left"  class="p-2">&nbsp;</td>
                            <td align="left"  class="p-2">&nbsp;</td>
                            <td align="right" class="p-2">&nbsp;</td>
                            <td align="right" class="p-2">&nbsp;</td>
                            </tr>
                <?php     
                        $slevelind = 'N';
                        $lineno    = $lineno  + 2;
                        $srealamt  = $srealamt + $realamt ;
                        $sdefcamt  = $sdefcamt + $defcamt ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                            <tr>
                            <td colspan="9">&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="left"  class="report_detail_bottom"><?php echo $slevelcd1 ; ?></td> 
                            <td align="left"  class="report_detail_bottom"><?php echo date_conv($slevelcd2); ?></td> 
                            <td align="left"  class="report_detail_bottom" colspan="2"><?php echo $slevelcd3 ; ?></td> 
                            <td align="left"  class="report_detail_bottom" colspan="3"><b>** Total</b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($srealamt > 0) { echo number_format($srealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($sdefcamt > 0) { echo number_format($sdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                <?php
                        $lineno   = $lineno + 2;
                        $trealamt = $trealamt + $srealamt ;
                        $tdefcamt = $tdefcamt + $sdefcamt ;
                    }
                ?>                   
                            <tr>
                            <td colspan="9">&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="center" class="report_detail_bottom" colspan="7"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                    </table>
                    </td>
                </tr>
            </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'C') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $tbillamt   = 0 ; 
                $trealamt   = 0 ; 
                $tbalnamt   = 0 ; 
                $tdefcamt   = 0 ;
                $report_cnt = $params['bill_cnt'] ;
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
                {
                $lrealamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['client_code'] ;
                $plevelnm  = '('.$report_row['client_code'].') -- '.$report_row['client_name'] ;
                while($plevelcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
                {
                    $srealamt  = 0 ; 
                    $sdefcamt  = 0 ; 
                    $slevelind = 'Y' ;
                    $slevelcd1 = $report_row['instrument_no'] ;
                    $slevelcd2 = $report_row['instrument_dt'] ;
                    $slevelcd3 = $report_row['bank_name'] ;
                    $slevelnm  = '';
                    while($plevelcd == $report_row['client_code'] && $slevelcd1 == $report_row['instrument_no'] && $slevelcd2 == $report_row['instrument_dt'] && $slevelcd3 == $report_row['bank_name'] && $rowcnt <= $report_cnt)
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
                            <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                            </tr>
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Branch</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Realise Period</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
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
                <tr>
                    <td colspan="4" class="grid_header">
                        <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                            <tr class="fs-14">
                            <th width="120" align="left"  class="py-3 px-2">Chq#</th>
                            <th width="100" align="left"  class="py-3 px-2">Chq Dt</th>
                            <th width="130" align="left"  class="py-3 px-2">Bill No</th>
                            <th width="100" align="left"  class="py-3 px-2">Bill Dt</th>
                            <th width="400" align="left"  class="py-3 px-2">Matter</th>
                            <th width="50" align="left"  class="py-3 px-2">Intl</th>
                            <th width="120" align="left"  class="py-3 px-2">Rl. Date&nbsp;</th>
                            <th width="075" align="right" class="py-3 px-2">Realised&nbsp;</th>
                            <th width="075" align="right" class="py-3 px-2">Deficit&nbsp;</th>
                            </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                            <tr>
                            <td align="left" class="p-2" style="background-color: #e2e6506e;" colspan="9"><b><?php echo $plevelnm?></b></td> 
                            </tr>
                            <!-- <tr>
                            <td align="left" class="report_detail_none" colspan="9">&nbsp;</td>
                            </tr> -->
                <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                            <tr class="fs-14 border-0">
                            <td align="left"  class="p-2" ><?php echo $report_row['instrument_no'] ?></td> 
                            <td align="left"  class="p-2" ><?php echo date_conv($report_row['instrument_dt'])?></td> 
                            <td align="left"  class="p-2" ><?php echo $report_row['bill_number']?></td> 
                            <td align="left"  class="p-2" ><?php echo date_conv($report_row['bill_date'])?></td> 
                            <td align="left"  class="p-2" ><?php echo strtoupper($report_row['matter_desc'])?></td>
                            <td align="left"  class="p-2" ><?php echo strtoupper($report_row['initial_code'])?></td>
                            <td align="left"  class="p-2" ><?php echo date_conv($report_row['doc_date'])?></td> 
                            <td align="right" class="p-2" ><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                            <td align="right" class="p-2" ><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                            </tr>
                <?php     
                            $slevelind = 'N' ;
                            $lineno    = $lineno  + 1;
                            $srealamt  = $srealamt + $realamt ;
                            $sdefcamt  = $sdefcamt + $defcamt ;
                            //

                            $rowcnt = $rowcnt + 1 ;
                        }  
                ?>
                        <tr>
                            <td align="left"  class="report_detail_none"><?php echo $slevelcd1 ; ?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($slevelcd2); ?></td> 
                            <td align="left"  class="report_detail_none" colspan="2"><?php echo $slevelcd3 ; ?></td> 
                            <td align="right" class="report_detail_none" colspan="3"><b>** Instrument Total</b>&nbsp;</td>
                            <td align="right" class="report_detail_none"><b><?php if($srealamt > 0) { echo number_format($srealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_none"><b><?php if($sdefcamt > 0) { echo number_format($sdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                            <tr>
                            <td colspan="8">&nbsp;</td>
                            </tr>
                <?php
                        $lineno    = $lineno  + 2;
                        $lrealamt  = $lrealamt + $srealamt ;
                        $ldefcamt  = $ldefcamt + $sdefcamt ;
                    }  
                ?>

                            <tr>
                            <td align="left"  class="report_detail_bottom" colspan="7"><b>** Total</b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                <?php
                    $lineno   = $lineno + 1;
                    $trealamt = $trealamt + $lrealamt ;
                    $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                            <tr>
                            <td colspan="8">&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="center" class="report_detail_bottom" colspan="7"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                    </table>
                    </td>
                </tr>
                </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'M') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $tbillamt   = 0 ; 
                $trealamt   = 0 ; 
                $tbalnamt   = 0 ; 
                $tdefcamt   = 0 ;
                $report_cnt = $params['bill_cnt'] ;
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
                {
                $lrealamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['matter_code'] ;
                $plevelnm  = '('.$report_row['matter_code'].') -- '.$report_row['matter_desc'] ;
                while($plevelcd == $report_row['matter_code'] && $rowcnt <= $report_cnt)
                {
                $srealamt  = 0 ; 
                $sdefcamt  = 0 ; 
                $slevelind = 'Y' ;
                $slevelcd1 = $report_row['instrument_no'] ;
                $slevelcd2 = $report_row['instrument_dt'] ;
                $slevelcd3 = $report_row['bank_name'] ;
                $slevelnm  = '';
                while($plevelcd == $report_row['matter_code'] && $slevelcd1 == $report_row['instrument_no'] && $slevelcd2 == $report_row['instrument_dt'] && $slevelcd3 == $report_row['bank_name'] && $rowcnt <= $report_cnt)
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
                            <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                            </tr>
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Branch</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Realise Period</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
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
                <tr>
                    <td colspan="4" class="grid_header">
                        <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                            <tr>
                            <td width="050" align="left"  class="report_detail_tb">Chq#</td>
                            <td width="075" align="left"  class="report_detail_tb">Chq Dt</td>
                            <td width="100" align="left"  class="report_detail_tb">Bill No</td>
                            <td width="075" align="left"  class="report_detail_tb">Bill Dt</td>
                            <td width="400" align="left"  class="report_detail_tb">Client</td>
                            <td width="025" align="left"  class="report_detail_tb">Intl</td>
                            <td width="075" align="left"  class="report_detail_tb">Rl. Date</td>
                            <td width="075" align="right" class="report_detail_tb">Realised&nbsp;</td>
                            <td width="075" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                            </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                            <tr>
                            <td align="left" class="report_detail_none" colspan="9"><b><?php echo $plevelnm?></b></td> 
                            </tr>
                            <tr>
                            <td align="left" class="report_detail_none" colspan="9">&nbsp;</td>
                            </tr>
                <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                            <tr>
                            <td align="left"  class="report_detail_none"><?php echo $report_row['instrument_no']?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['instrument_dt'])?></td> 
                            <td align="left"  class="report_detail_none"><?php echo $report_row['bill_number']?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['bill_date'])?></td> 
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['client_name'])?></td>
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['initial_code'])?></td>
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['doc_date'])?></td> 
                            <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                            <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                            </tr>
                <?php     
                            $slevelind = 'N' ;
                            $lineno    = $lineno  + 1;
                            $srealamt  = $srealamt + $realamt ;
                            $sdefcamt  = $sdefcamt + $defcamt ;
                            //

                            $rowcnt = $rowcnt + 1 ;
                        }  
                ?>
                            <tr>
                            <td align="left"  class="report_detail_none"><?php echo $slevelcd1?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($slevelcd2)?></td> 
                            <td align="left"  class="report_detail_none" colspan="2"><?php echo $slevelcd3?></td> 
                            <td align="right" class="report_detail_none" colspan="3"><b>** Instrument Total</b>&nbsp;</td>
                            <td align="right" class="report_detail_none"><b><?php if($srealamt > 0) { echo number_format($srealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_none"><b><?php if($sdefcamt > 0) { echo number_format($sdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                            <tr>
                            <td colspan="9">&nbsp;</td>
                            </tr>
                <?php
                        $lineno    = $lineno  + 2;
                        $lrealamt  = $lrealamt + $srealamt ;
                        $ldefcamt  = $ldefcamt + $sdefcamt ;
                    }  
                ?>

                            <tr>
                            <td align="left"  class="report_detail_bottom" colspan="7"><b>** Total</b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                <?php
                    $lineno   = $lineno + 1;
                    $trealamt = $trealamt + $lrealamt ;
                    $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                            <tr>
                            <td colspan="8">&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="center" class="report_detail_bottom" colspan="7"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                            <td align="right"  class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right"  class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                    </table>
                    </td>
                </tr>
                </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'I') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $tbillamt   = 0 ; 
                $trealamt   = 0 ; 
                $tbalnamt   = 0 ; 
                $tdefcamt   = 0 ;
                $report_cnt = $params['bill_cnt'] ;
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
                {
                $lrealamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['initial_code'] ;
                $plevelnm  = '('.$report_row['initial_code'].') -- '.$report_row['initial_name'] ;
                while($plevelcd == $report_row['initial_code'] && $rowcnt <= $report_cnt)
                {
                    $srealamt  = 0 ; 
                    $sdefcamt  = 0 ; 
                    $slevelind = 'Y' ;
                    $slevelcd1 = $report_row['instrument_no'] ;
                    $slevelcd2 = $report_row['instrument_dt'] ;
                    $slevelcd3 = $report_row['bank_name'] ;
                    $slevelnm  = '';
                    while($plevelcd == $report_row['initial_code'] && $slevelcd1 == $report_row['instrument_no'] && $slevelcd2 == $report_row['instrument_dt'] && $slevelcd3 == $report_row['bank_name'] && $rowcnt <= $report_cnt)
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
                            <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                            </tr>
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Branch</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Realise Period</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
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
                <tr>
                    <td colspan="4" class="grid_header">
                        <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                            <tr>
                            <td width="050" align="left"  class="report_detail_tb">Chq#</td>
                            <td width="075" align="left"  class="report_detail_tb">Chq Dt</td>
                            <td width="100" align="left"  class="report_detail_tb">Bill No</td>
                            <td width="075" align="left"  class="report_detail_tb">Bill Dt</td>
                            <td width="400" align="left"  class="report_detail_tb">Matter</td>
                            <td width="025" align="left"  class="report_detail_tb">Intl</td>
                            <td width="075" align="left"  class="report_detail_tb">Rl. Date</td>
                            <td width="075" align="right" class="report_detail_tb">Realised&nbsp;</td>
                            <td width="075" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                            </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                            <tr>
                            <td align="left" class="report_detail_none" colspan="9"><b><?php echo $plevelnm?></b></td> 
                            </tr>
                            <tr>
                            <td align="left" class="report_detail_none" colspan="9">&nbsp;</td>
                            </tr>
                <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                            <tr>
                            <td align="left"  class="report_detail_none"><?php echo $report_row['instrument_no'] ?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['instrument_dt'])?></td> 
                            <td align="left"  class="report_detail_none"><?php echo $report_row['bill_number']?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['bill_date'])?></td> 
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['matter_desc'])?></td>
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['initial_code'])?></td>
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['doc_date'])?></td> 
                            <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                            <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                            </tr>
                <?php     
                            $slevelind = 'N' ;
                            $lineno    = $lineno  + 1;
                            $srealamt  = $srealamt + $realamt ;
                            $sdefcamt  = $sdefcamt + $defcamt ;
                            //

                            $rowcnt = $rowcnt + 1 ;
                        }  
                ?>
                        <tr>
                            <td align="left"  class="report_detail_none"><?php echo $slevelcd1 ; ?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($slevelcd2); ?></td> 
                            <td align="left"  class="report_detail_none" colspan="2"><?php echo $slevelcd3 ; ?></td> 
                            <td align="right" class="report_detail_none" colspan="3"><b>** Instrument Total</b>&nbsp;</td>
                            <td align="right" class="report_detail_none"><b><?php if($srealamt > 0) { echo number_format($srealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_none"><b><?php if($sdefcamt > 0) { echo number_format($sdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                            <tr>
                            <td colspan="9">&nbsp;</td>
                            </tr>
                <?php
                        $lineno    = $lineno  + 2;
                        $lrealamt  = $lrealamt + $srealamt ;
                        $ldefcamt  = $ldefcamt + $sdefcamt ;
                    }  
                ?>

                            <tr>
                            <td align="left"  class="report_detail_bottom" colspan="7"><b>** Total</b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                <?php
                    $lineno   = $lineno + 1;
                    $trealamt = $trealamt + $lrealamt ;
                    $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                            <tr>
                            <td colspan="9">&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="center" class="report_detail_bottom" colspan="7"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                    </table>
                    </td>
                </tr>
                </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'T') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $tbillamt   = 0 ; 
                $trealamt   = 0 ; 
                $tbalnamt   = 0 ; 
                $tdefcamt   = 0 ;
                $report_cnt = $params['bill_cnt'] ;
                $rowcnt     = 1 ;
                foreach ($reports as $report_row)
                {
                $lrealamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['court_code'] ;
                $plevelnm  = '('.$report_row['court_code'].') -- '.$report_row['court_name'] ;
                while($plevelcd == $report_row['court_code'] && $rowcnt <= $report_cnt)
                {
                    $srealamt  = 0 ; 
                    $sdefcamt  = 0 ; 
                    $slevelind = 'Y' ;
                    $slevelcd1 = $report_row['instrument_no'] ;
                    $slevelcd2 = $report_row['instrument_dt'] ;
                    $slevelcd3 = $report_row['bank_name'] ;
                    $slevelnm  = '';
                    while($plevelcd == $report_row['court_code'] && $slevelcd1 == $report_row['instrument_no'] && $slevelcd2 == $report_row['instrument_dt'] && $slevelcd3 == $report_row['bank_name'] && $rowcnt <= $report_cnt)
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
                            <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                            </tr>
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Branch</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Realise Period</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
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
                <tr>
                    <td colspan="4" class="grid_header">
                        <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                            <tr>
                            <td width="050" align="left"  class="report_detail_tb">Chq#</td>
                            <td width="075" align="left"  class="report_detail_tb">Chq Dt</td>
                            <td width="100" align="left"  class="report_detail_tb">Bill No</td>
                            <td width="075" align="left"  class="report_detail_tb">Bill Dt</td>
                            <td width="400" align="left"  class="report_detail_tb">Matter</td>
                            <td width="025" align="left"  class="report_detail_tb">Intl</td>
                            <td width="075" align="left"  class="report_detail_tb">Rl. Date</td>
                            <td width="075" align="right" class="report_detail_tb">Realised&nbsp;</td>
                            <td width="075" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                            </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                            <tr>
                            <td align="left" class="report_detail_none" colspan="9"><b><?php echo $plevelnm?></b></td> 
                            </tr>
                            <tr>
                            <td align="left" class="report_detail_none" colspan="9">&nbsp;</td>
                            </tr>
                <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                            <tr>
                            <td align="left"  class="report_detail_none"><?php echo $report_row['instrument_no'] ?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['instrument_dt'])?></td> 
                            <td align="left"  class="report_detail_none"><?php echo $report_row['bill_number']?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['bill_date'])?></td> 
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['matter_desc'])?></td>
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['initial_code'])?></td>
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['doc_date'])?></td> 
                            <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                            <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                            </tr>
                <?php     
                            $slevelind = 'N' ;
                            $lineno    = $lineno  + 1;
                            $srealamt  = $srealamt + $realamt ;
                            $sdefcamt  = $sdefcamt + $defcamt ;
                            //

                            $rowcnt = $rowcnt + 1 ;
                        }  
                ?>
                        <tr>
                            <td align="left"  class="report_detail_none"><?php echo $slevelcd1 ; ?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($slevelcd2); ?></td> 
                            <td align="left"  class="report_detail_none" colspan="2"><?php echo $slevelcd3 ; ?></td> 
                            <td align="right" class="report_detail_none" colspan="3"><b>** Instrument Total</b>&nbsp;</td>
                            <td align="right" class="report_detail_none"><b><?php if($srealamt > 0) { echo number_format($srealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_none"><b><?php if($sdefcamt > 0) { echo number_format($sdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                            <tr>
                            <td colspan="9">&nbsp;</td>
                            </tr>
                <?php
                        $lineno    = $lineno  + 2;
                        $lrealamt  = $lrealamt + $srealamt ;
                        $ldefcamt  = $ldefcamt + $sdefcamt ;
                    }  
                ?>

                            <tr>
                            <td align="left"  class="report_detail_bottom" colspan="7"><b>** Total</b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                <?php
                    $lineno   = $lineno + 1;
                    $trealamt = $trealamt + $lrealamt ;
                    $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                            <tr>
                            <td colspan="8">&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="center" class="report_detail_bottom" colspan="7"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right" class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                    </table>
                    </td>
                </tr>
                </table> 
            <?php 
            }
            ?>
        <?php } ?>               

        <?php if(($params['info_by'] == 'C') && ($params['report_type'] == 'S')){
            if ($params['report_seqn'] == 'B') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ;  
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
                <table  class="table border-0" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <td width="10%">&nbsp;</td>
                                <td width="70%">&nbsp;</td>
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
                                <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Branch</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                </tr>
                                <tr>
                                <td class="report_label_text">Realise Period</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                <tr>
                    <td colspan="4" class="grid_header">
                        <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                            <tr>
                            <td width="07%" align="left"  class="report_detail_tb">Chq No</td>
                            <td width="09%" align="left"  class="report_detail_tb">Chq Dt</td>
                            <td width="32%" align="left"  class="report_detail_tb">Bank Name</td>
                            <td width="34%" align="left"  class="report_detail_tb">Received From</td>
                            <td width="09%" align="right" class="report_detail_tb">Realised&nbsp;</td>
                            <td width="09%" align="right" class="report_detail_tb">Deficit&nbsp;</td>
                            </tr>
                <?php
                            $lineno = 9 ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                            <tr>
                            <td align="left"  class="report_detail_none"><?php echo $report_row['instrument_no'] ?></td> 
                            <td align="left"  class="report_detail_none"><?php echo date_conv($report_row['instrument_dt'])?></td> 
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['bank_name'])?></td> 
                            <td align="left"  class="report_detail_none"><?php echo strtoupper($report_row['received_from'])?></td>
                            <td align="right" class="report_detail_none"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                            <td align="right" class="report_detail_none"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                            </tr>
                <?php     
                        $lineno    = $lineno  + 1;
                        $trealamt  = $srealamt + $realamt ;
                        $tdefcamt  = $sdefcamt + $defcamt ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }
                ?>                   
                            <tr>
                            <td colspan="8">&nbsp;</td>
                            </tr>
                            <tr>
                            <td align="center" class="report_detail_tb" colspan="4"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                            <td align="right"  class="report_detail_tb"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                            <td align="right"  class="report_detail_tb"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                            </tr>
                    </table>
                    </td>
                </tr>
                </table> 
            <?php 
            }
            else if ($params['report_seqn']== 'C') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ; 
                $report_cnt = $params['bill_cnt'] ;
                $rowcnt     = 1 ;
                $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                while ($rowcnt <= $report_cnt)
                {
                $lrealamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['client_code'] ;
                $plevelnm  = '('.$report_row['client_code'].') -- '.$report_row['client_name'] ;
                while($plevelcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
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
                                    <td width="10%">&nbsp;</td>
                                    <td width="70%">&nbsp;</td>
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
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Realise Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                        <tr>
                            <td colspan="4" class="grid_header">
                                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                    <tr class="fs-14">
                                        <th width="07%" align="left"  class="py-3 px-2">Chq No</th>
                                        <th width="09%" align="left"  class="py-3 px-2">Chq Dt</th>
                                        <th width="32%" align="left"  class="py-3 px-2">Bank Name</th>
                                        <th width="34%" align="left"  class="py-3 px-2">Received From</th>
                                        <th width="09%" align="right" class="py-3 px-2">Realised&nbsp;</th>
                                        <th width="09%" align="right" class="py-3 px-2">Deficit&nbsp;</th>
                                    </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="p-2" colspan="8"><b><?php echo $plevelnm?></b></td> 
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="p-2" colspan="8">&nbsp;</td>
                                        </tr>
                <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                                    <tr class="fs-14 border-0">
                                    <td align="left"  class="p-2"><?php echo $report_row['instrument_no'] ?></td> 
                                    <td align="left"  class="p-2"><?php echo date_conv($report_row['instrument_dt'])?></td> 
                                    <td align="left"  class="p-2"><?php echo strtoupper($report_row['bank_name'])?></td> 
                                    <td align="left"  class="p-2"><?php echo strtoupper($report_row['received_from'])?></td>
                                    <td align="right" class="p-2"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="p-2"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                    </tr>
                <?php     
                            $slevelind = 'N' ;
                            $lineno    = $lineno  + 1;
                            $lrealamt  = $lrealamt + $realamt ;
                            $ldefcamt  = $ldefcamt + $defcamt ;
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="report_detail_none" colspan="6">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="center"  class="p-2" colspan="4" style="background-color:#f0f1a4;"><b>** Total</b>&nbsp;</td>
                                            <td align="right" class="p-2" style="background-color:#f0f1a4;"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                                            <td align="right" class="p-2" style="background-color:#f0f1a4;"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                                        </tr>
                <?php
                    $lineno   = $lineno + 2;
                    $trealamt = $trealamt + $lrealamt ;
                    $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                                    <tr>
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="center" class="report_detail_bottom" colspan="4"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                        </table> 
                
            <?php 
            }
            else if ($params['report_seqn'] == 'M') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ; 
                $rowcnt     = 1 ;
                $report_row =isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                $report_cnt = $params['bill_cnt'];
                while ($rowcnt <= $report_cnt)
                {
                $lrealamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['matter_code'] ;
                $plevelnm  = '('.$report_row['matter_code'].') -- '.$report_row['matter_desc'] ;
                while($plevelcd == $report_row['matter_code'] && $rowcnt <= $report_cnt)
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
                                    <td width="10%">&nbsp;</td>
                                    <td width="70%">&nbsp;</td>
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
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name'] ?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Realise Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                        <tr>
                            <td colspan="4" class="grid_header">
                                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                    <tr class="fs-14">
                                        <th width="07%" align="left"  class="py-3 px-2">Chq No</th>
                                        <th width="09%" align="left"  class="py-3 px-2">Chq Dt</th>
                                        <th width="32%" align="left"  class="py-3 px-2">Bank Name</th>
                                        <th width="34%" align="left"  class="py-3 px-2">Received From</th>
                                        <th width="09%" align="right" class="py-3 px-2">Realised&nbsp;</th>
                                        <th width="09%" align="right" class="py-3 px-2">Deficit&nbsp;</th>
                                    </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                                        <tr class="fs-14">
                                        <td align="left" class="py-3 px-2" colspan="8"><b><?php echo $plevelnm?></b></td> 
                                        </tr>
                                        <tr class="fs-14">
                                        <td align="left" class="py-3 px-2" colspan="8">&nbsp;</td>
                                        </tr>
                <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                                    <tr class="fs-14">
                                    <td align="left"  class="py-3 px-2"><?php echo $report_row['instrument_no'] ?></td> 
                                    <td align="left"  class="py-3 px-2"><?php echo date_conv($report_row['instrument_dt'])?></td> 
                                    <td align="left"  class="py-3 px-2"><?php echo strtoupper($report_row['bank_name'])?></td> 
                                    <td align="left"  class="py-3 px-2"><?php echo strtoupper($report_row['received_from'])?></td>
                                    <td align="right" class="py-3 px-2"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="py-3 px-2"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                    </tr>
                <?php     
                            $slevelind = 'N' ;
                            $lineno    = $lineno  + 1;
                            $lrealamt  = $lrealamt + $realamt ;
                            $ldefcamt  = $ldefcamt + $defcamt ;
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                        <tr class="fs-14">
                                            <td align="left" class="p-2" colspan="6">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td align="center"  class="p-2" style="background-color:#f0f1a4;" colspan="4"><b>** Total</b>&nbsp;</td>
                                            <td align="right" class="p-2" style="background-color:#f0f1a4;"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                                            <td align="right" class="p-2" style="background-color:#f0f1a4;"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                                        </tr>
                <?php
                    $lineno   = $lineno + 2;
                    $trealamt = $trealamt + $lrealamt ;
                    $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                                    <tr>
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td align="center" class="report_detail_bottom" colspan="4"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right" class="report_detail_bottom"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                        </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'I') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ; 
                $rowcnt     = 1 ;
                $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                $report_cnt = $params['bill_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $lrealamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['initial_code'] ;
                $plevelnm  = '('.$report_row['initial_code'].') -- '.$report_row['initial_name'] ;
                while($plevelcd == $report_row['initial_code'] && $rowcnt <= $report_cnt)
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
                                        <td width="10%">&nbsp;</td>
                                        <td width="70%">&nbsp;</td>
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
                                        <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                        </tr>
                                        <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                        <td class="report_label_text">Branch</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name'] ?></b></td>
                                        <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                        </tr>
                                        <tr>
                                        <td class="report_label_text">Realise Period</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc'] ?></b></td>
                                        <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                            <tr>
                                <td colspan="4" class="grid_header">
                                    <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                        <tr  class="fs-14">
                                            <th width="07%" align="left"  class="py-3 px-2">Chq No</th>
                                            <th width="09%" align="left"  class="py-3 px-2">Chq Dt</th>
                                            <th width="32%" align="left"  class="py-3 px-2">Bank Name</th>
                                            <th width="34%" align="left"  class="py-3 px-2">Received From</th>
                                            <th width="09%" align="right" class="py-3 px-2">Realised&nbsp;</th>
                                            <th width="09%" align="right" class="py-3 px-2">Deficit&nbsp;</th>
                                        </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                                        <tr class="fs-14">
                                        <td align="left" class="py-3 px-2" colspan="8"><b><?php echo $plevelnm?></b></td> 
                                        </tr>
                                        <tr class="fs-14">
                                        <td align="left" class="py-3 px-2" colspan="8">&nbsp;</td>
                                        </tr>
                <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                                        <tr class="fs-14">
                                            <td align="left"  class="py-3 px-2"><?php echo $report_row['instrument_no'] ?></td> 
                                            <td align="left"  class="py-3 px-2"><?php echo date_conv($report_row['instrument_dt'])?></td> 
                                            <td align="left"  class="py-3 px-2"><?php echo strtoupper($report_row['bank_name'])?></td> 
                                            <td align="left"  class="py-3 px-2"><?php echo strtoupper($report_row['received_from'])?></td>
                                            <td align="right" class="py-3 px-2"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                            <td align="right" class="py-3 px-2"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                        </tr>
                <?php     
                            $slevelind = 'N' ;
                            $lineno    = $lineno  + 1;
                            $lrealamt  = $lrealamt + $realamt ;
                            $ldefcamt  = $ldefcamt + $defcamt ;
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                        <tr class="fs-14">
                                            <td align="left" class="py-3 px-2" colspan="6">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td align="center"  class="py-3 px-2" colspan="4"  style="background-color:#f0f1a4;"><b>** Total</b>&nbsp;</td>
                                            <td align="right" class="py-3 px-2"  style="background-color:#f0f1a4;"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                                            <td align="right" class="py-3 px-2"  style="background-color:#f0f1a4;"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                                        </tr>
                <?php
                    $lineno   = $lineno + 2;
                    $trealamt = $trealamt + $lrealamt ;
                    $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                                    <tr class="fs-14">
                                        <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="center" class="py-3 px-2" colspan="4"  style="background-color:#05557038;"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                        <td align="right" class="py-3 px-2"  style="background-color:#05557038;"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                        <td align="right" class="py-3 px-2"  style="background-color:#05557038;"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                        </table> 
            <?php 
            }
            else if ($params['report_seqn'] == 'T') 
            {
                $maxline    = 52 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $trealamt   = 0 ; 
                $tdefcamt   = 0 ; 
                $rowcnt     = 1 ;
                $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                $report_cnt = $params['bill_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $lrealamt  = 0 ; 
                $ldefcamt  = 0 ; 
                $plevelind = 'Y' ;
                $plevelcd  = $report_row['court_code'] ;
                $plevelnm  = '('.$report_row['court_code'].') -- '.$report_row['court_name'] ;
                while($plevelcd == $report_row['court_code'] && $rowcnt <= $report_cnt)
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
                                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                    </tr>
                                    <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name'] ?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Realise Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                        <tr>
                            <td colspan="4" class="grid_header">
                                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                    <tr class="fs-14">
                                        <th width="07%" align="left"  class="py-3 px-2">Chq No</th>
                                        <th width="09%" align="left"  class="py-3 px-2">Chq Dt</th>
                                        <th width="32%" align="left"  class="py-3 px-2">Bank Name</th>
                                        <th width="34%" align="left"  class="py-3 px-2">Received From</th>
                                        <th width="09%" align="right" class="py-3 px-2">Realised&nbsp;</th>
                                        <th width="09%" align="right" class="py-3 px-2">Deficit&nbsp;</th>
                                    </tr>
                <?php
                            $lineno = 9 ;
                            $plevelind = 'Y' ;
                        }

                        if ($plevelind == 'Y') 
                        { 
                ?>
                                        <tr  class="fs-14">
                                            <td align="left" class="py-3 px-2" colspan="8"><b><?php echo $plevelnm?></b></td> 
                                        </tr>
                                        <tr  class="fs-14">
                                            <td align="left" class="py-3 px-2" colspan="8">&nbsp;</td>
                                        </tr>
                <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                        }
                        $realamt = $report_row['realamt'];  
                        $defcamt = $report_row['defcamt'];  
                ?>
                                    <tr class="fs-14">
                                    <td align="left"  class="py-3 px-2"><?php echo $report_row['instrument_no'] ?></td> 
                                    <td align="left"  class="py-3 px-2"><?php echo date_conv($report_row['instrument_dt'])?></td> 
                                    <td align="left"  class="py-3 px-2"><?php echo strtoupper($report_row['bank_name'])?></td> 
                                    <td align="left"  class="py-3 px-2"><?php echo strtoupper($report_row['received_from'])?></td>
                                    <td align="right" class="py-3 px-2"><?php if($realamt > 0) { echo number_format($realamt,2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="py-3 px-2"><?php if($defcamt > 0) { echo number_format($defcamt,2,'.',''); }?>&nbsp;</td>
                                    </tr>
                <?php     
                            $slevelind = 'N' ;
                            $lineno    = $lineno  + 1;
                            $lrealamt  = $lrealamt + $realamt ;
                            $ldefcamt  = $ldefcamt + $defcamt ;
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                        <tr  class="fs-14">
                                        <td align="left" class="py-3 px-2" colspan="6">&nbsp;</td>
                                        </tr>
                                        <tr  class="fs-14">
                                        <td align="center"  class="py-3 px-2" colspan="4" style="background-color:#f0f1a4;"><b>** Total</b>&nbsp;</td>
                                        <td align="right" class="py-3 px-2" style="background-color:#f0f1a4;"><b><?php if($lrealamt > 0) { echo number_format($lrealamt,2,'.',''); }?></b>&nbsp;</td>
                                        <td align="right" class="py-3 px-2" style="background-color:#f0f1a4;"><b><?php if($ldefcamt > 0) { echo number_format($ldefcamt,2,'.',''); }?></b>&nbsp;</td>
                                        </tr>
                <?php
                    $lineno   = $lineno + 2;
                    $trealamt = $trealamt + $lrealamt ;
                    $tdefcamt = $tdefcamt + $ldefcamt ;
                    }
                ?>                   
                                    <tr class="fs-14">
                                    <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                    <td align="center" class="py-3 px-2" colspan="4" style="background-color:#05557038;"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                    <td align="right" class="py-3 px-2" style="background-color:#05557038;"><b><?php if($trealamt > 0) { echo number_format($trealamt,2,'.',''); }?></b>&nbsp;</td>
                                    <td align="right" class="py-3 px-2" style="background-color:#05557038;"><b><?php if($tdefcamt > 0) { echo number_format($tdefcamt,2,'.',''); }?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                        </table> 
            <?php 
            }
            ?>    
        <?php } ?>  
        
        
        </div>
<?php } ?>
        
<!-- End #main -->
<?= $this->endSection() ?>