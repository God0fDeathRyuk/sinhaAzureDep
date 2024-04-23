<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

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
		<h1>Bill Register (Bill/Client/Matter/Initial) </h1>
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
						<label class="d-inline-block w-100 mb-2 lbl-mn">Period</label>
						<span class="float-start mt-2">From</span>
						<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="start_date" onBlur="make_date(this)"/>
						<span class="float-start mt-2 ms-2">To</span>
						<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="end_date" onBlur="make_date(this)"/>
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
					<div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Bill For</label>
						<select class="form-select" name="billfor_ind">
						<option value="%">All</option>
						<option value="N">Others</option>
						<option value="Y" >Court Fee</option>
						</select>
					</div>
                    <div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Report Seq</label>
						<select class="form-select" name="report_seqn">
						<option value="B">Bill-wise</option>
                        <option value="C">Client-wise</option>
                        <option value="M">Matter-wise</option>
                        <option value="I">Initial-wise</option>
						</select>
					</div>
                    <div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Report Type</label>
						<select class="form-select" name="report_type">
						<option value="D">Detail</option>
				        <option value="S">Summary</option>
						</select>
					</div>
                    <div class="col-md-3 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
						<select class="form-select" name="output_type">
						<option value="Report">View Report</option>
						<option value="Pdf">Download PDF</option>
						<option value="Excel">Download Excel</option>
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
		<div class="tbl-sec d-inline-block w-100 p-3 position-relative bg-white" style="background-color:#f6f9ff !important;">
			<div class="position-absolute btndv">
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a> -->
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
                    <a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
                                
            <?php if ($params['report_type'] == 'D') { 
                if ($params['report_seqn'] == 'B') 
                {
                    $maxline    = 68 ;
                    $lineno     = 0 ;
                    $pageno     = 0 ;
                    $tipamt     = 0 ; 
                    $topamt     = 0 ; 
                    $tcnamt     = 0 ;
                    $tstamt     = 0 ; 
                    $ttotamt    = 0 ;
                    $index   = 0;   
                    $report_cnt = $params['bill_cnt'] ;
                    $rowcnt     = 1 ;
                    foreach ($reports as $report_row) {
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
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="px-2 bg-white">
                            <tr>
                                <td align="center" colspan="9">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="report_label_text" colspan="9" align="center"><b>Sinha and Company</td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="9" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="9" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Branch</td>
                                            <td class="report_label_text" style="width: 60%;">&nbsp;:&nbsp;<b><?= $params['branch_name'] ?></b></td>
                                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                            <td class="report_label_text" style="width: 15%;">&nbsp;:&nbsp;<?= $params['date'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Bill Period</td>
                                            <td class="report_label_text" style="width: 60%;">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
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
                            <tr class="fs-14">
                                <th width="" align="left"  class="px-3 py-2" style="padding-left:5px;padding-right:5px;">Bill No</th>
                                <th width="70" align="left"  class="px-3 py-2" style="padding-left:5px;padding-right:5px;">Bill Dt</th>
                                <th width="" align="left"  class="px-3 py-2" style="padding-left:5px;padding-right:5px;">Client/Matter</th>
                                <th width="" align="left"  class="px-3 py-2" style="padding-left:5px;padding-right:5px;">Intl</th>
                                <th width="" align="left" class="px-3 py-2" style="padding-left:5px;padding-right:5px;">Inpocket&nbsp;</th>
                                <th width="" align="left" class="px-3 py-2" style="padding-left:5px;padding-right:5px;">Outpocket&nbsp;</th>
                                <th width="" align="left" class="px-3 py-2" style="padding-left:5px;padding-right:5px;">Counsel&nbsp;</th>
                                <th width="30" align="left" class="px-3 py-2" style="padding-left:5px;padding-right:5px;">S Tax</th>
                                <th width="" align="left" class="px-3 py-2" style="padding-left:5px;padding-right:5px;">Total&nbsp;</th>
                            </tr>

                    <?php
                                $lineno = 9 ;
                            }
                    ?>
                                        <tr class="fs-14">
                                            <td align="left"  class="p-2"><?php echo $report_row['bill_number']?></td> 
                                            <td align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['bill_date'])?></td> 
                                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['client_name'])?></td>
                                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['initial_code'])?></td>
                                            <td align="left" class="p-2"><?php if($report_row['ipamt']  > 0) { echo number_format($report_row['ipamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['opamt']  > 0) { echo number_format($report_row['opamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['cnamt']  > 0) { echo number_format($report_row['cnamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['stamt']  > 0) { echo number_format($report_row['stamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['totamt'] > 0) { echo number_format($report_row['totamt'],2,'.',''); }?></td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left"  class="p-2" rowspan="2" style="vertical-align:top"><?php echo strtoupper($report_row['matter_desc'])?></td>
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                        </tr>
                    <?php     
                            $lineno  = $lineno  + 3;
                            $tipamt  = $tipamt  + $report_row['ipamt'] ;
                            $topamt  = $topamt  + $report_row['opamt'] ;
                            $tcnamt  = $tcnamt  + $report_row['cnamt'] ;
                            $tstamt  = $tstamt  + $report_row['stamt'] ;
                            $ttotamt = $ttotamt + $report_row['totamt'] ;

                            $rowcnt = $rowcnt + 1 ;
                        }
                    ?>                   
                                        <tr class="fs-14 border-0">
                                            <td height="10" colspan="9">&nbsp;</td>
                                        </tr>

                                        <tr class="fs-14 border-0">
                                            <td align="center" class="p-2" colspan="4" style="background-color: #e2e6506e;"><b> GRAND TOTAL </b>&nbsp;</td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($tipamt  > 0) { echo number_format($tipamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($topamt  > 0) { echo number_format($topamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($tcnamt  > 0) { echo number_format($tcnamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($tstamt  > 0) { echo number_format(round($tstamt, 0),2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.','') ;}?></b></td>
                                        </tr>

                                </table>
                                </td>
                            </tr>
                        </table> 
                <?php 
                }
                else if ($params['report_seqn'] == 'C') 
                {
                    $maxline    = 45 ;
                    $lineno     = 0 ;
                    $pageno     = 0 ;
                    $tipamt     = 0 ; 
                    $topamt     = 0 ; 
                    $tcnamt     = 0 ; 
                    $tstamt     = 0 ; 
                    $ttotamt    = 0 ; 
                    $index   = 0; 
                    $report_cnt = $params['bill_cnt'] ;
                    $rowcnt     = 1 ;
                    $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                    while ($rowcnt <= $report_cnt){
                    $lipamt    = 0 ; 
                    $lopamt    = 0 ; 
                    $lcnamt    = 0 ; 
                    $lstamt    = 0 ; 
                    $ltotamt   = 0 ; 
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
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td colspan="9" align="center">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="report_label_text" colspan="9" align="center"><b>Sinha and Company</b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="9" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?></u></b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="9" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc']);?> </u></b></td>
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
                                            <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Bill Period</td>
                                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ;?></b></td>
                                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno;?></td>
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
                                <td colspan="9" class="grid_header">
                                    <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                        <tr>
                                        <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                                        <tr class="fs-14 border-0">
                                            <th width="" align="left"  class="px-3 py-2">Bill No</th>
                                            <th width="" align="left"  class="report_detail_none px-3 py-2">Bill Dt</th>
                                            <th width="" align="left"  class="report_detail_none px-3 py-2">Client / Matter</th>
                                            <th width="" align="left"  class="report_detail_none px-3 py-2">Intl</th>
                                            <th width="" align="right" class="report_detail_none px-3 py-2">Inpocket&nbsp;</th>
                                            <th width="" align="right" class="report_detail_none px-3 py-2">Outpocket&nbsp;</th>
                                            <th width="" align="right" class="report_detail_none px-3 py-2">Counsel&nbsp;</th>
                                            <th width="" align="right" class="report_detail_none px-3 py-2">S Tax&nbsp;</th>
                                            <th width="" align="right" class="report_detail_none px-3 py-2">Total&nbsp;</th>
                                        </tr>
                                        <tr>
                                            <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                    <?php
                                $lineno = 9 ;
                                $plevelind = 'Y' ;
                            }

                            if ($plevelind == 'Y') 
                            { 
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="report_detail_none p-2" colspan="8"><b><?php echo $plevelnm;?></b></td> 
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="report_detail_none p-2" colspan="8">&nbsp;</td>
                                        </tr>
                    <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                            }
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left"  class="p-2"><?php echo $report_row['bill_number'];?></td> 
                                            <td align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['bill_date']);?></td> 
                                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['client_name']);?></td>
                                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['initial_code']);?></td>
                                            <td align="left" class="p-2"><?php if($report_row['ipamt']  > 0) { echo number_format($report_row['ipamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['opamt']  > 0) { echo number_format($report_row['opamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['cnamt']  > 0) { echo number_format($report_row['cnamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['stamt']  > 0) { echo number_format($report_row['stamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['totamt'] > 0) { echo number_format($report_row['totamt'],2,'.',''); }?></td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                            <td align="left" class="p-2" style="vertical-align:top" rowspan="2"><?php echo strtoupper($report_row['matter_desc']);?></td>
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                            <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                        </tr>
                    <?php     
                            $lineno  = $lineno  + 3;
                            $lipamt  = $lipamt  + $report_row['ipamt'] ;
                            $lopamt  = $lopamt  + $report_row['opamt'] ;
                            $lcnamt  = $lcnamt  + $report_row['cnamt'] ;
                            $lstamt  = $lstamt  + $report_row['stamt'] ;
                            $ltotamt = $ltotamt + $report_row['totamt'] ;
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                        }  
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td height="10" colspan="9">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left"   class="p-2" colspan="4" style="background-color: #e2e6506e;"><b> Total </b>&nbsp;</td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($lipamt  > 0) { echo number_format($lipamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($lopamt  > 0) { echo number_format($lopamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($lcnamt  > 0) { echo number_format($lcnamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($lstamt  > 0) { echo number_format($lstamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($ltotamt > 0) { echo number_format($ltotamt,2,'.','') ;}?></b></td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="1" color="#CCCCCC" noshade></td>
                                        </tr>

                    <?php
                            $lineno  = $lineno + 3;
                            $tipamt  = $tipamt  + $lipamt ;
                            $topamt  = $topamt  + $lopamt ;
                            $tcnamt  = $tcnamt  + $lcnamt ;
                            $tstamt  = $tstamt  + $lstamt ;
                            $ttotamt = $ttotamt + $ltotamt ;
                        }
                    ?>                   
                                        <tr class="fs-14 border-0">
                                            <td colspan="8">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td align="center" class="p-2" colspan="4" style="background-color: #e2e6506e;"><b> GRAND TOTAL </b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($tipamt  > 0) { echo number_format($tipamt, 2,'.','') ;}?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($topamt  > 0) { echo number_format($topamt, 2,'.','') ;}?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($tcnamt  > 0) { echo number_format($tcnamt, 2,'.','') ;}?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($tstamt  > 0) { echo number_format(round($tstamt, 0),2,'.','') ;}?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.','') ;}?></b></td>
                                        </tr>
                                        <tr>
                                            <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                                </table>
                                </td>
                            </tr>
                        </table> 
                <?php 
                }
                else if ($params['report_seqn'] == 'M') 
                {
                    $maxline    = 45 ;
                    $lineno     = 0 ;
                    $pageno     = 0 ;
                    $tipamt     = 0 ; 
                    $topamt     = 0 ; 
                    $tcnamt     = 0 ; 
                    $tstamt     = 0 ; 
                    $ttotamt    = 0 ; 
                    $index   = 0;
                    $report_cnt = $params['bill_cnt'] ;
                    $rowcnt     = 1 ;
                    $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                    while ($rowcnt <= $report_cnt){
                    $lipamt    = 0 ; 
                    $lopamt    = 0 ; 
                    $lcnamt    = 0 ; 
                    $lstamt    = 0 ; 
                    $ltotamt   = 0 ; 
                    $plevelind = 'Y' ;
                    $plevelcd  = $report_row['matter_code'] ;
                    $plevelnm  = '('.$report_row['matter_code'].') -- '.$report_row['matter_desc']  ;
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
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td colspan="9">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="report_label_text" colspan="9" align="center"><b>Sinha and Company</b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="9" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="9" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc']);?> </u></b></td>
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
                                            <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Bill Period</td>
                                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ;?></b></td>
                                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno;?></td>
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
                                <td colspan="9" class="grid_header">
                                    <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                                        <tr class="fs-14 border-0">
                                            <th width="" align="left"  class="px-3 py-2">Bill No</th>
                                            <th width="" align="left"  class="report_detail_none px-3 py-2">Bill Dt</th>
                                            <th width="" align="left"  class="report_detail_none px-3 py-2">Client</th>
                                            <th width="" align="left"  class="report_detail_none px-3 py-2">Intl</th>
                                            <th width="" align="left" class="report_detail_none px-3 py-2">Inpocket&nbsp;</th>
                                            <th width="" align="left" class="report_detail_none px-3 py-2">Outpocket&nbsp;</th>
                                            <th width="" align="left" class="report_detail_none px-3 py-2">Counsel&nbsp;</th>
                                            <th width="" align="left" class="report_detail_none px-3 py-2">S Tax&nbsp;</th>
                                            <th width="" align="left" class="report_detail_none px-3 py-2">Total&nbsp;</th>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                    <?php
                                $lineno = 9 ;
                                $plevelind = 'Y' ;
                            }

                            if ($plevelind == 'Y') 
                            { 
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="p-2" colspan="8"><b><?php echo $plevelnm;?></b></td> 
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="p-2" colspan="8">&nbsp;</td>
                                        </tr>
                    <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                            }
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left"  class="p-2"><?php echo $report_row['bill_number'];?></td> 
                                            <td align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['bill_date']);?></td> 
                                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['client_name']);?></td>
                                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['initial_code']);?></td>
                                            <td align="left" class="p-2"><?php if($report_row['ipamt']  > 0) { echo number_format($report_row['ipamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['opamt']  > 0) { echo number_format($report_row['opamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['cnamt']  > 0) { echo number_format($report_row['cnamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['stamt']  > 0) { echo number_format($report_row['stamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['totamt'] > 0) { echo number_format($report_row['totamt'],2,'.',''); }?></td>
                                        </tr>
                    <?php     
                            $lineno  = $lineno  + 1;
                            $lipamt  = $lipamt  + $report_row['ipamt'] ;
                            $lopamt  = $lopamt  + $report_row['opamt'] ;
                            $lcnamt  = $lcnamt  + $report_row['cnamt'] ;
                            $lstamt  = $lstamt  + $report_row['stamt'] ;
                            $ltotamt = $ltotamt + $report_row['totamt'] ;
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                        }  
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td height="10" colspan="9">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left"   class="p-2" colspan="4"><b>** Total</b>&nbsp;</td>
                                            <td align="left"  class="p-2"><b><?php if($lipamt  > 0) { echo number_format($lipamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($lopamt  > 0) { echo number_format($lopamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($lcnamt  > 0) { echo number_format($lcnamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($lstamt  > 0) { echo number_format($lstamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($ltotamt > 0) { echo number_format($ltotamt,2,'.','') ;}?></b></td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="1" color="#CCCCCC" noshade></td>
                                        </tr>

                    <?php
                            $lineno  = $lineno + 3;
                            $tipamt  = $tipamt  + $lipamt ;
                            $topamt  = $topamt  + $lopamt ;
                            $tcnamt  = $tcnamt  + $lcnamt ;
                            $tstamt  = $tstamt  + $lstamt ;
                            $ttotamt = $ttotamt + $ltotamt ;
                        }
                    ?>                   
                                        <tr class="fs-14 border-0">
                                            <td colspan="8">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                                        <tr class="fs-14 border-0">
                                            <td align="center" class="p-2" colspan="4"><b>*** GRAND TOTAL ***</b>&nbsp;</td>
                                            <td align="left"  class="p-2"><b><?php if($tipamt  > 0) { echo number_format($tipamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($topamt  > 0) { echo number_format($topamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($tcnamt  > 0) { echo number_format($tcnamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($tstamt  > 0) { echo number_format(round($tstamt, 0),2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.','') ;}?></b></td>
                                        </tr>

                                </table>
                                </td>
                            </tr>
                        </table> 
                <?php 
                }
                else if ($params['report_seqn'] == 'I') 
                {
                    $maxline    = 65 ;
                    $lineno     = 0 ;
                    $pageno     = 0 ;
                    $tipamt     = 0 ; 
                    $topamt     = 0 ; 
                    $tcnamt     = 0 ; 
                    $tstamt     = 0 ; 
                    $ttotamt    = 0 ; 
                    $index   = 0;
                    $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ;
                    $rowcnt     = 1 ;
                    while ($rowcnt <= $report_cnt){
                    $lipamt    = 0 ; 
                    $lopamt    = 0 ; 
                    $lcnamt    = 0 ; 
                    $lstamt    = 0 ; 
                    $ltotamt   = 0 ; 
                    $plevelind = 'Y' ;
                    $plevelcd  = $report_row['initial_code'] ;
                    $plevelnm  = '('.$report_row['initial_code'].') -- '.$report_row['initial_name']  ;
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
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td colspan="8">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="report_label_text" colspan="8" align="center"><b>Sinha and Company</b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="8" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="8" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc']);?> </u></b></td>
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
                                            <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Bill Period</td>
                                            <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ;?></b></td>
                                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno;?></td>
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
                                <td colspan="8" class="grid_header">
                                    <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                                        <tr class="fs-14 border-0">
                                            <th width="" align="left"  class="px-3 py-2">Bill No</th>
                                            <th width="" align="left"  class="report_detail_none">Bill Dt</th>
                                            <th width="" align="left"  class="report_detail_none">Client/Matter</th>
                                            <th width="" align="right" class="report_detail_none">Inpocket&nbsp;</th>
                                            <th width="" align="right" class="report_detail_none">Outpocket&nbsp;</th>
                                            <th width="" align="right" class="report_detail_none">Counsel&nbsp;</th>
                                            <th width="" align="right" class="report_detail_none">S Tax&nbsp;</th>
                                            <th width="" align="right" class="report_detail_none">Total&nbsp;</th>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                    <?php
                                $lineno = 9 ;
                                $plevelind = 'Y' ;
                            }

                            if ($plevelind == 'Y') 
                            { 
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="p-2" colspan="8"><b><?php echo $plevelnm;?></b></td> 
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left" class="p-2" colspan="8">&nbsp;</td>
                                        </tr>
                    <?php
                            $lineno = $lineno + 2 ;
                            $plevelind = 'N' ;
                            }
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left"  class="p-2"><?php echo $report_row['bill_number'];?></td> 
                                            <td align="left"  class="p-2">&nbsp;<?php echo date_conv($report_row['bill_date']);?></td> 
                                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['client_name']);?></td>
                                            <td align="left" class="p-2"><?php if($report_row['ipamt']  > 0) { echo number_format($report_row['ipamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['opamt']  > 0) { echo number_format($report_row['opamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['cnamt']  > 0) { echo number_format($report_row['cnamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['stamt']  > 0) { echo number_format($report_row['stamt'], 2,'.',''); }?></td>
                                            <td align="left" class="p-2"><?php if($report_row['totamt'] > 0) { echo number_format($report_row['totamt'],2,'.',''); }?></td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left"  class="p-2" rowspan="2" style="vertical-align:top"><?php echo strtoupper($report_row['matter_desc']);?></td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left"  class="p-2">&nbsp;</td> 
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                            <td align="left" class="p-2">&nbsp;</td>
                                        </tr>
                    <?php     
                            $lineno  = $lineno  + 3;
                            $lipamt  = $lipamt  + $report_row['ipamt'] ;
                            $lopamt  = $lopamt  + $report_row['opamt'] ;
                            $lcnamt  = $lcnamt  + $report_row['cnamt'] ;
                            $lstamt  = $lstamt  + $report_row['stamt'] ;
                            $ltotamt = $ltotamt + $report_row['totamt'] ;
                            //
                            $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                        }  
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td height="10" colspan="9">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="left"   class="p-2" colspan="3"><b>** Total</b>&nbsp;</td>
                                            <td align="left"  class="p-2"><b><?php if($lipamt  > 0) { echo number_format($lipamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($lopamt  > 0) { echo number_format($lopamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($lcnamt  > 0) { echo number_format($lcnamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($lstamt  > 0) { echo number_format($lstamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2"><b><?php if($ltotamt > 0) { echo number_format($ltotamt,2,'.','') ;}?></b></td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="1" color="#CCCCCC" noshade></td>
                                        </tr>

                    <?php
                            $lineno  = $lineno + 3;
                            $tipamt  = $tipamt  + $lipamt ;
                            $topamt  = $topamt  + $lopamt ;
                            $tcnamt  = $tcnamt  + $lcnamt ;
                            $tstamt  = $tstamt  + $lstamt ;
                            $ttotamt = $ttotamt + $ltotamt ;
                        }
                    ?>                   
                                        <tr class="fs-14 border-0">
                                            <td height="10" colspan="9">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                                        <tr class="fs-14 border-0">
                                            <td align="center" class="p-2" colspan="3" style="background-color: #e2e6506e;"><b> GRAND TOTAL </b>&nbsp;</td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($tipamt  > 0) { echo number_format($tipamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($topamt  > 0) { echo number_format($topamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($tcnamt  > 0) { echo number_format($tcnamt, 2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($tstamt  > 0) { echo number_format(round($tstamt, 0),2,'.','') ;}?></b></td>
                                            <td align="left"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.','') ;}?></b></td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td height="15" colspan="12"><hr size="2" noshade></td>
                                        </tr>

                                </table>
                                </td>
                            </tr>
                        </table> 

            <?php } 
           } else if($params['report_type'] == 'S') { 
                if ($params['report_seqn'] == 'C') 
                {
                    $maxline    = 52 ;
                    $lineno     = 0 ;
                    $pageno     = 0 ;
                    $tipamt     = 0 ; 
                    $topamt     = 0 ; 
                    $tcnamt     = 0 ; 
                    $tstamt     = 0 ; 
                    $ttotamt    = 0 ; 
                    $index = 0;
                    $report_row = $reports[$index]; 
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
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="px-2">
                            <tr>
                                <td colspan="7">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                        
                                        <tr>
                                            <td class="report_label_text" colspan="7" align="center"><b>Sinha and Company</b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="7" align="center"><b><u> <?= strtoupper($params['report_desc']) ?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="7" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Branch</td>
                                            <td class="report_label_text" style="width:60%;">&nbsp;:&nbsp;<b><?= $params['branch_name'] ?></b></td>
                                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                            <td class="report_label_text" style="width:15%;">&nbsp;:&nbsp;<?= $params['date'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Bill Period</td>
                                            <td class="report_label_text" style="width:60%;">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                            <td class="report_label_text" style="width:15%;">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                                <th width="" align="left"  class="report_detail_tb p-2">Code</th>
                                <th width="" align="left"  class="report_detail_tb p-2">Name</th>
                                <th width="" align="left" class="report_detail_tb p-2">Inpocket&nbsp;</th>
                                <th width="" align="left" class="report_detail_tb p-2">Outpocket&nbsp;</th>
                                <th width="" align="left" class="report_detail_tb p-2">Counsel&nbsp;</th>
                                <th width="" align="left" class="report_detail_tb p-2">S. Tax&nbsp;</th>
                                <th width="" align="left" class="report_detail_tb p-2">Total&nbsp;</th>
                            </tr>  
                    <?php
                                $lineno = 9 ;
                            }
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left"  class="report_detail_none p-2"><?php echo strtoupper($report_row['client_code'])?></td>
                                            <td align="left"  class="report_detail_none p-2"><?php echo strtoupper($report_row['client_name'])?></td>
                                            <td align="left" class="report_detail_none p-2"><?php if($report_row['ipamt']  > 0) { echo number_format($report_row['ipamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="left" class="report_detail_none p-2"><?php if($report_row['opamt']  > 0) { echo number_format($report_row['opamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="left" class="report_detail_none p-2"><?php if($report_row['cnamt']  > 0) { echo number_format($report_row['cnamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="left" class="report_detail_none p-2"><?php if($report_row['stamt']  > 0) { echo number_format($report_row['stamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="left" class="report_detail_none p-2"><?php if($report_row['totamt'] > 0) { echo number_format($report_row['totamt'],2,'.',''); }?>&nbsp;</td>
                                        </tr>
                    <?php     
                            $lineno  = $lineno  + 1;
                            $tipamt  = $tipamt  + $report_row['ipamt'] ;
                            $topamt  = $topamt  + $report_row['opamt'] ;
                            $tcnamt  = $tcnamt  + $report_row['cnamt'] ;
                            $tstamt  = $tstamt  + $report_row['stamt'] ;
                            $ttotamt = $ttotamt + $report_row['totamt'] ;
                            //
                            $rowcnt = $rowcnt + 1 ;
                        }
                    ?>                   
                                        <tr class="fs-14 border-0">
                                            <td colspan="6" class="border-0 p-1">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="center" class="report_detail_tb p-2" colspan="2" style="background-color: #e2e6506e;"><b> GRAND TOTAL </b>&nbsp;</td>
                                            <td align="left"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($tipamt  > 0) { echo number_format($tipamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="left"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($topamt  > 0) { echo number_format($topamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="left"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($tcnamt  > 0) { echo number_format($tcnamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="left"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($tstamt  > 0) { echo number_format($tstamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="left"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.','') ;}?></b>&nbsp;</td>
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
                    $tipamt     = 0 ; 
                    $topamt     = 0 ; 
                    $tcnamt     = 0 ; 
                    $tstamt     = 0 ; 
                    $ttotamt    = 0 ; 
                    $index = 0;
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
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="px-2">
                            <tr>
                                <td colspan="7">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                        
                                        <tr>
                                            <td class="report_label_text" colspan="7" align="center"><b>Sinha and Company</b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="7" align="center"><b><u> <?= strtoupper($params['report_desc']) ?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="7" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Branch</td>
                                            <td class="report_label_text" style="width:60%;">&nbsp;:&nbsp;<b><?= $params['branch_name'] ?></b></td>
                                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                            <td class="report_label_text" style="width:15%;">&nbsp;:&nbsp;<?= $params['date'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Bill Period</td>
                                            <td class="report_label_text" style="width:60%;">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                            <td class="report_label_text" style="width:15%;">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                            <tr class="fs-14 border-0">
                                <th width="" align="left"  class="py-3 px-2">Code</th>
                                <th width="" align="left"  class="py-3 px-2">Name</th>
                                <th width="" align="right" class="py-3 px-2">Inpocket&nbsp;</th>
                                <th width="" align="right" class="py-3 px-2">Outpocket&nbsp;</th>
                                <th width="" align="right" class="py-3 px-2">Counsel&nbsp;</th>
                                <th width="" align="right" class="py-3 px-2">S. Tax&nbsp;</th>
                                <th width="" align="right" class="py-3 px-2">Total&nbsp;</th>
                            </tr>
                                        
                    <?php
                                $lineno = 9 ;
                            }
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left"  class="report_detail_none p-2"><?php echo strtoupper($report_row['matter_code'])?></td>
                                            <td align="left"  class="report_detail_none p-2"><?php echo strtoupper($report_row['matter_desc'])?></td>
                                            <td align="right" class="report_detail_none p-2"><?php if($report_row['ipamt']  > 0) { echo number_format($report_row['ipamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="right" class="report_detail_none p-2"><?php if($report_row['opamt']  > 0) { echo number_format($report_row['opamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="right" class="report_detail_none p-2"><?php if($report_row['cnamt']  > 0) { echo number_format($report_row['cnamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="right" class="report_detail_none p-2"><?php if($report_row['stamt']  > 0) { echo number_format($report_row['stamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="right" class="report_detail_none p-2"><?php if($report_row['totamt'] > 0) { echo number_format($report_row['totamt'],2,'.',''); }?>&nbsp;</td>
                                        </tr>
                    <?php     
                            $lineno  = $lineno  + 1;
                            $tipamt  = $tipamt  + $report_row['ipamt'] ;
                            $topamt  = $topamt  + $report_row['opamt'] ;
                            $tcnamt  = $tcnamt  + $report_row['cnamt'] ;
                            $tstamt  = $tstamt  + $report_row['stamt'] ;
                            $ttotamt = $ttotamt + $report_row['totamt'] ;
                            //
                            $rowcnt = $rowcnt + 1 ;
                        }
                    ?>                   
                                        <tr class="fs-14 border-0">
                                            <td colspan="6"class="border-0 p-2">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="center" class="report_detail_tb p-2" colspan="2" style="background-color: #e2e6506e;"><b> GRAND TOTAL </b>&nbsp;</td>
                                            <td align="right"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($tipamt  > 0) { echo number_format($tipamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="right"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($topamt  > 0) { echo number_format($topamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="right"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($tcnamt  > 0) { echo number_format($tcnamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="right"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($tstamt  > 0) { echo number_format($tstamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="right"  class="report_detail_tb p-2" style="background-color: #e2e6506e;"><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.','') ;}?></b>&nbsp;</td>
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
                    $tipamt     = 0 ; 
                    $topamt     = 0 ; 
                    $tcnamt     = 0 ;
                    $tstamt     = 0 ; 
                    $ttotamt    = 0 ; 
                    $index = 0; 
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
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="px-2" style="background-color:#f6f9ff;">
                            <tr>
                                <td colspan="7">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="report_label_text" colspan="7" align="center"><b>Sinha and Company</b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="7" align="center"><b><u> <?= strtoupper($params['report_desc']) ?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text" colspan="7" align="center"><b><u> <?= strtoupper($params['report_sub_desc'])?> </u></b></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Branch</td>
                                            <td class="report_label_text" style="width:60%;">&nbsp;:&nbsp;<b><?= $params['branch_name'] ?></b></td>
                                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                            <td class="report_label_text" style="width:15%;">&nbsp;:&nbsp;<?= $params['date'] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="report_label_text">Bill Period</td>
                                            <td class="report_label_text" style="width:60%;">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                            <td class="report_label_text" style="width:15%;">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                            <tr class="fs-14 border-0">
                                <th width="" align="left"  class="px-3 py-2">Code</th>
                                <th width="" align="left"  class="px-3 py-2">Name</th>
                                <th width="" align="right" class="px-3 py-2">Inpocket&nbsp;</th>
                                <th width="" align="right" class="px-3 py-2">Outpocket&nbsp;</th>
                                <th width="" align="right" class="px-3 py-2">Counsel&nbsp;</th>
                                <th width="" align="right" class="px-3 py-2">S. Tax&nbsp;</th>
                                <th width="" align="right" class="px-3 py-2">Total&nbsp;</th>
                            </tr>
                    <?php
                                $lineno = 9 ;
                            }
                    ?>
                                        <tr class="fs-14 border-0">
                                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['initial_code'])?></td>
                                            <td align="left"  class="p-2"><?php echo strtoupper($report_row['initial_name'])?></td>
                                            <td align="right" class="p-2"><?php if($report_row['ipamt']  > 0) { echo number_format($report_row['ipamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="right" class="p-2"><?php if($report_row['opamt']  > 0) { echo number_format($report_row['opamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="right" class="p-2"><?php if($report_row['cnamt']  > 0) { echo number_format($report_row['cnamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="right" class="p-2"><?php if($report_row['stamt']  > 0) { echo number_format($report_row['stamt'], 2,'.',''); }?>&nbsp;</td>
                                            <td align="right" class="p-2"><?php if($report_row['totamt'] > 0) { echo number_format($report_row['totamt'],2,'.',''); }?>&nbsp;</td>
                                        </tr>
                    <?php     
                            $lineno  = $lineno  + 1;
                            $tipamt  = $tipamt  + $report_row['ipamt'] ;
                            $topamt  = $topamt  + $report_row['opamt'] ;
                            $tcnamt  = $tcnamt  + $report_row['cnamt'] ;
                            $tstamt  = $tstamt  + $report_row['stamt'] ;
                            $ttotamt = $ttotamt + $report_row['totamt'] ;
                            //
                            $rowcnt = $rowcnt + 1 ;
                        }
                    ?>                   
                                        <tr class="fs-14 border-0">
                                            <td colspan="6" class="p-2 border-0">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14 border-0">
                                            <td align="center" class="p-2" colspan="2" style="background-color:#fefee0;"><b> GRAND TOTAL </b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color:#fefee0;"><b><?php if($tipamt  > 0) { echo number_format($tipamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color:#fefee0;"><b><?php if($topamt  > 0) { echo number_format($topamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color:#fefee0;"><b><?php if($tcnamt  > 0) { echo number_format($tcnamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color:#fefee0;"><b><?php if($tstamt  > 0) { echo number_format($tstamt, 2,'.','') ;}?></b>&nbsp;</td>
                                            <td align="right"  class="p-2" style="background-color:#fefee0;"><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.','') ;}?></b>&nbsp;</td>
                                        </tr>
                                </table>
                                </td>
                            </tr>
                        </table> 
                <?php 
                }
            } ?>
        </div>    
<?php } ?>

<!-- End #main -->
<?= $this->endSection() ?>