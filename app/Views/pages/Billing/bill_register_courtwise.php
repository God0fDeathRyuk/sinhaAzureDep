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
		<h1>Bill Register (Court/Client/Matter/Initial) </h1>
		</div>

		<form action="" method="post">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
			<div class="frms-sec d-inline-block w-100 bg-white p-3">
                    <div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">As On</label>
						<input type="text" class="form-control float-start w-100 ms-0 set-date datepicker withdate" name="ason_date" readonly/>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-3 px-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Branch</label>
						<select class="form-select cstm-inpt" name="branch_code">
						<?php foreach($data['branches'] as $branch) { ?>
						<option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
						<?php } ?>
						</select>
					</div>
					<div class="frms-sec-insde d-block float-start col-md-6 ps-2 mb-4">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Period</label>
						<span class="float-start mt-2">From</span>
						<input type="text" class="form-control float-start w-40 ms-2 set-date datepicker" id="" placeholder="dd-mm-yyyy" name="start_date" onBlur="make_date(this)"/>
						<span class="float-start mt-2 ms-2">To</span>
						<input type="text" class="form-control float-start w-40 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="end_date" onBlur="make_date(this)"/>
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
						<label class="d-inline-block w-100 mb-2 lbl-mn">Attention Code</label>
						<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" id="attention_code" onchange="fetchData(this, 'attention_code', ['attentionName'], ['attention_name'], 'attention_code')" size="05" maxlength="06" name="attention_code" />
					    <i class="fa-solid fa-binoculars icn-vw" onclick="showData('attention_code', 'display_id=<?= $displayId['attn_help_id'] ?>&myclient_code=@clientCode', 'attention_code', ['attentionName'], ['attention_name'], 'attention_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
					</div>
					<div class="col-md-4 float-start px-2 mb-3">
						<label class="d-inline-block w-100 mb-2 lbl-mn">Attention Name</label>
						<input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" name="attention_name" id="attentionName" readonly/>
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
                        <option value="I">Court-wise</option>
                        <option value="C">Client-wise</option>
                        <option value="B">Bill-wise</option>
                        <option value="N">Initial wise</option>
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
		<div class="tbl-sec d-inline-block w-100 p-3 position-relative bg-white">
			<div class="position-absolute btndv">
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-success me-2">Download Excel</a> -->
				<!-- <a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a> -->
				<?php if ($renderFlag) : ?>
                    <a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
             
            <?php if ($params['report_type'] == 'D') { ?>
                <?php
                $maxline = 35 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tbilamt = 0; 
                $tcolamt = 0; 
                $tdefamt = 0; 
                $tbalamt = 0; 
                $lipamt  = 0; 
                $lopamt  = 0; 
                $lcnamt  = 0; 
                $lstamt  = 0; 
                $ltotamt = 0;
                $tipamt  = 0;  
                $topamt  = 0; 
                $tcnamt  = 0; 
                $tstamt  = 0; 
                $ttotamt = 0;
                //$index   = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                $report_cnt = $params['bill_cnt'] ;
                while ($rowcnt <= $report_cnt){
                $cbilamt   = 0; 
                $ccolamt   = 0; 
                $cdefamt   = 0; 
                $cbalamt   = 0; 
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
                <table class="table border-0" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="border-0 pb-0" colspan="11">    
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                
                                <tr><td class="report_label_text" colspan="4" align="center"><b>Sinha and Company</b></td></tr>
                                <tr><td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td></tr>
                                <tr><td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td></tr>
                                <tr>
                                    <td colspan="11">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">Client</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['client_name']?></b></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">Attention</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['attention_name'];?></b></td>
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
                                    <td class="report_label_text">Bill Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">Status As On</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['ason_date']?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                </tr>
                            </table>
                        </td>    
                    </tr>
                    <tr>
                        <td colspan="11">&nbsp;</td>
                    </tr>
                    <tr class="fs-14">
                        <th width="" align="left"  class="py-3 px-2">Bill No/Date</th>
                        <th width="" align="left"  class="py-3 px-2">Client / Attention / Matter</th>
                        <th width="" align="left"  class="py-3 px-2">Initial&nbsp;</th>
                        <th width="" align="right" class="py-3 px-2">Inpocket&nbsp;</th>
                        <th width="" align="right" class="py-3 px-2">Outpocket&nbsp;</th>
                        <th width="" align="right" class="py-3 px-2">Counsel&nbsp;</th>
                        <th width="" align="right" class="py-3 px-2">S Tax&nbsp;</th>
                        <th width="" align="right" class="py-3 px-2">Total&nbsp;</th>
                        <th width="" align="right" class="py-3 px-2">Realised&nbsp;</th>
                        <th width="" align="right" class="py-3 px-2">Deficit&nbsp;</th>
                        <th width="" align="right" class="py-3 px-2">O/s&nbsp;</th>
                    </tr>
                                
                <?php
                            $lineno = 10 ;
                            $pcourtind = 'Y' ;
                        }

                        if ($pcourtind == 'Y') 
                        { 
                ?>
                                <tr class="fs-14 border-0">
                                <td height="22" align="left" class="p-2" style="background-color: #e2e6506e;" colspan="11"><b><?php echo $pcourtnm?></b></td> 
                                </tr>
                <?php
                        $lineno = $lineno + 1 ;
                        $pcourtind = 'N' ;
                        }
                ?>
                                <tr class="fs-14 border-0">
                                <td align="left"  class="p-2" style="background-color: #6abdd9;"><?php echo $report_row['bill_number']?></td> 
                                <td align="left"  class="p-2" style="background-color: #6abdd9;"><?php echo strtoupper($report_row['client_name'])?></td>
                                <td align="left"  class="p-2" style="background-color: #6abdd9;"><?php echo strtoupper($report_row['initial_code'])?></td>
                                <td align="right" class="p-2" style="background-color: #6abdd9;"><?php if($report_row['ipamt']  > 0) { echo number_format($report_row['ipamt'], 2,'.',','); }?>&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #6abdd9;"><?php if($report_row['opamt']  > 0) { echo number_format($report_row['opamt'], 2,'.',','); }?>&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #6abdd9;"><?php if($report_row['cnamt']  > 0) { echo number_format($report_row['cnamt'], 2,'.',','); }?>&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #6abdd9;"><?php if($report_row['stamt']  > 0) { echo number_format($report_row['stamt'], 2,'.',','); }?>&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #6abdd9;"><?php if($report_row['totamt'] > 0) { echo number_format($report_row['totamt'],2,'.',','); }?>&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #6abdd9;"><?php if($report_row['realised_amount'] > 0) { echo number_format($report_row['realised_amount'],2,'.',','); }?>&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #6abdd9;"><?php if($report_row['deficit_amount']  > 0) { echo number_format($report_row['deficit_amount'] ,2,'.',','); }?>&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #6abdd9;"><?php if($report_row['balance_amount']  > 0) { echo number_format($report_row['balance_amount'] ,2,'.',','); }?>&nbsp;</td>
                                </tr>
                                <tr class="fs-14 border-0">
                                <td align="left" class="p-2"><?php echo date_conv($report_row['bill_date'])?></td> 
                                <td align="left" class="p-2" style="vertical-align:top"><?php echo strtoupper(get_attention_name($report_row['attention_code']) )?></td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                </tr>
                                <tr class="fs-14 border-0">
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td> 
                                <td align="left" class="p-2" style="vertical-align:top"><?php echo strtoupper($report_row['matter_desc'])?></td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top">&nbsp;</td>
                                </tr>
                                <tr class="fs-14 border-0"><td height="10">&nbsp;</td></tr>  
                <?php     
                        $lineno = $lineno + 4;
                        $lipamt  = $lipamt  + $report_row['ipamt'] ;
                        $lopamt  = $lopamt  + $report_row['opamt'] ;
                        $lcnamt  = $lcnamt  + $report_row['cnamt'] ;
                        $lstamt  = $lstamt  + $report_row['stamt'] ;
                        $ltotamt = $ltotamt + $report_row['totamt'] ;
                        // $ttotamt = 
                        $ccolamt = $ccolamt + $report_row['realised_amount'] ;
                        $cdefamt = $cdefamt + $report_row['deficit_amount'] ;
                        $cbalamt = $cbalamt + $report_row['balance_amount'] ;
                        //
                        $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                <tr><td height="15" colspan="11"><hr size="1" color="#CCCCCC" noshade></td></tr>
                                <tr>
                                <td height="" align="center" style="background-color:#80adec;"  class="report_detail_none" colspan="3"><b> Total</b>&nbsp;</td>
                                <td height="" align="right" style="background-color:#80adec;"  class="report_detail_none"><b><?php if($lipamt  > 0) { echo number_format($lipamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right" style="background-color:#80adec;"  class="report_detail_none"><b><?php if($lopamt  > 0) { echo number_format($lopamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right" style="background-color:#80adec;"  class="report_detail_none"><b><?php if($lcnamt  > 0) { echo number_format($lcnamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right" style="background-color:#80adec;"  class="report_detail_none"><b><?php if($lstamt  > 0) { echo number_format($lstamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right" style="background-color:#80adec;"  class="report_detail_none"><b><?php if($ltotamt > 0) { echo number_format($ltotamt,2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right" style="background-color:#80adec;"  class="report_detail_none"><b><?php if($ccolamt > 0) { echo number_format($ccolamt,2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right" style="background-color:#80adec;"  class="report_detail_none"><b><?php if($cdefamt > 0) { echo number_format($cdefamt,2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right" style="background-color:#80adec;"  class="report_detail_none"><b><?php if($cbalamt > 0) { echo number_format($cbalamt,2,'.',',') ;}?></b>&nbsp;</td>
                                </tr>
                                <tr><td height="15" colspan="11"><hr size="1" color="#CCCCCC" noshade></td></tr>
                <?php
                        $lineno  = $lineno + 2;
                        $tipamt  = $tipamt  + $lipamt ;
                        $topamt  = $topamt  + $lopamt ;
                        $tcnamt  = $tcnamt  + $lcnamt ;
                        $tstamt  = $tstamt  + $lstamt ;
                        $ttotamt = $ttotamt + $ltotamt ;
                        $tcolamt = $tcolamt + $ccolamt ;
                        $tdefamt = $tdefamt + $cdefamt ;
                        $tbalamt = $tbalamt + $cbalamt ;
                    }
                ?>                   
                                <tr><td height="15" colspan="11"><hr size="1" noshade></td></tr>
                                <tr>
                                <td height="" align="center" class="report_detail_none" colspan="3" style="background-color:#fbfcce;vertical-align:middle;"><b> Grand Total </b>&nbsp;</td>
                                <td height="" align="right"  class="report_detail_none pt-2" style="background-color:#fbfcce;vertical-align:middle;"><b><?php if($tipamt  > 0) { echo number_format($tipamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right"  class="report_detail_none" style="background-color:#fbfcce;"><b><?php if($topamt  > 0) { echo number_format($topamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right"  class="report_detail_none" style="background-color:#fbfcce;"><b><?php if($tcnamt  > 0) { echo number_format($tcnamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right"  class="report_detail_none" style="background-color:#fbfcce;"><b><?php if($tstamt  > 0) { echo number_format($tstamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right"  class="report_detail_none" style="background-color:#fbfcce;"><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right"  class="report_detail_none" style="background-color:#fbfcce;"><b><?php if($tcolamt > 0) { echo number_format($tcolamt,2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right"  class="report_detail_none" style="background-color:#fbfcce;"><b><?php if($tdefamt > 0) { echo number_format($tdefamt,2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="" align="right"  class="report_detail_none" style="background-color:#fbfcce;"><b><?php if($tbalamt > 0) { echo number_format($tbalamt,2,'.',',') ;}?></b>&nbsp;</td>
                                </tr>
                                <tr><td height="15" colspan="11"><hr size="1" noshade></td></tr>
                                <tr><td height="15" colspan="11">&nbsp;</td></tr>
                                <tr><td height="15" colspan="11">&nbsp;</td></tr>
                        </table>
                        </td>
                    </tr>
                </table>
                
          <?php } else if($params['report_type'] == 'S') { ?>
            <?php
            $maxline = 52 ;
            $lineno  = 0 ;
            $pageno  = 0 ;
            $tbilamt = 0; 
            $tcolamt = 0; 
            $tdefamt = 0; 
            $tbalamt = 0;
            $tipamt  = 0;  
            $topamt  = 0; 
            $tcnamt  = 0; 
            $tstamt  = 0; 
            $ttotamt = 0;
            $index = 0; 
            $report_cnt = $params['bill_cnt'] ;
            $rowcnt     = 1 ;
            foreach ($reports as $report_row){
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
                        <td class="border-0 pb-0" colspan="9">    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                               
                                <tr><td class="report_label_text" colspan="9" align="center"><b>Sinha and Company</b></td></tr>
                                <tr><td class="report_label_text" colspan="9" align="center"><b><u> <?php echo strtoupper($params['report_desc'])?> </u></b></td></tr>
                                <tr><td class="report_label_text" colspan="9" align="center"><b><u> <?php echo strtoupper($params['report_sub_desc'])?> </u></b></td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">Client</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['client_name'];?></b></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">Attention</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['attention_name'];?></b></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name'];?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date'];?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">Bill Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ;?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno;?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">Status As On</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['ason_date'];?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
                                </tr>
                            </table>
                        </td>    
                    </tr>
                    <tr class="fs-14">
                        <th height="" width="" align="left"  class="py-3 px-2">Court Name</th>
                        <th height="" width="" align="left" class="py-3 px-2">Inpocket&nbsp;</th>
                        <th height="" width="" align="left" class="py-3 px-2">Outpocket&nbsp;</th>
                        <th height="" width="" align="left" class="py-3 px-2">Counsel&nbsp;</th>
                        <th height="" width="" align="left" class="py-3 px-2">S Tax&nbsp;</th>
                        <th height="" width="" align="left" class="py-3 px-2">Total&nbsp;</th>
                        <th height="" width="" align="left" class="py-3 px-2">Realised&nbsp;</th>
                        <th height="" width="" align="left" class="py-3 px-2">Deficit&nbsp;</th>
                        <th height="" width="" align="left" class="py-3 px-2">O/s&nbsp;</th>
                    </tr>  
                                
            <?php
                        $lineno = 10 ;
                    }
                    
                    
                    
            ?>
                                <tr class="fs-14 border-0">
                                    <td height="16" align="left"  class="p-2"><?php echo strtoupper($report_row['court_name']);?></td>
                                    <td height="16" align="left" class="p-2" ><?php if($report_row['ipamt'] > 0) { echo number_format($report_row['ipamt'], 2,'.',','); }?>&nbsp;</td>
                                    <td height="16" align="left" class="p-2" ><?php if($report_row['opamt'] > 0) { echo number_format($report_row['opamt'], 2,'.',','); }?>&nbsp;</td>
                                    <td height="16" align="left" class="p-2" ><?php if($report_row['cnamt'] > 0) { echo number_format($report_row['cnamt'], 2,'.',','); }?>&nbsp;</td>
                                    <td height="16" align="left" class="p-2" ><?php if($report_row['stamt'] > 0) { echo number_format($report_row['stamt'], 2,'.',','); }?>&nbsp;</td>
                                    <td height="16" align="left" class="p-2" ><?php if($report_row['totamt'] > 0) { echo number_format($report_row['totamt'], 2,'.',','); }?>&nbsp;</td>
                                    <td height="16" align="left" class="p-2" ><?php if($report_row['realised_amount'] > 0) { echo number_format($report_row['realised_amount'],2,'.',','); }?>&nbsp;</td>
                                    <td height="16" align="left" class="p-2" ><?php if($report_row['deficit_amount']  > 0) { echo number_format($report_row['deficit_amount'] ,2,'.',','); }?>&nbsp;</td>
                                    <td height="16" align="left" class="p-2" ><?php if($report_row['balance_amount']  > 0) { echo number_format($report_row['balance_amount'] ,2,'.',','); }?>&nbsp;</td>
                                </tr>
            <?php     
                    $lineno = $lineno + 1;
                    $tipamt  = $tipamt  + $report_row['ipamt'] ;
                    $topamt  = $topamt  + $report_row['opamt'] ;
                    $tcnamt  = $tcnamt  + $report_row['cnamt'] ;
                    $tstamt  = $tstamt  + $report_row['stamt'] ;
                    $ttotamt = $ttotamt + $report_row['totamt'] ;
                    $tcolamt = $tcolamt + $report_row['realised_amount'] ;
                    $tdefamt = $tdefamt + $report_row['deficit_amount'] ;
                    $tbalamt = $tbalamt + $report_row['balance_amount'] ;
                    //
                    $rowcnt = $rowcnt + 1 ;
                }  
            ?>
                                <tr class="fs-14 border-0">
                                <td height="20" align="left" style="background-color: #e2e6506e;"  class="p-2"><b>Grand Total :</b>&nbsp;&nbsp;</td>
                                <td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" ><b><?php if($tipamt  > 0) { echo number_format($tipamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" ><b><?php if($topamt  > 0) { echo number_format($topamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" ><b><?php if($tcnamt  > 0) { echo number_format($tcnamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" ><b><?php if($tstamt  > 0) { echo number_format($tstamt, 2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" ><b><?php if($ttotamt > 0) { echo number_format($ttotamt,2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" ><b><?php if($tcolamt > 0) { echo number_format($tcolamt,2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" ><b><?php if($tdefamt > 0) { echo number_format($tdefamt,2,'.',',') ;}?></b>&nbsp;</td>
                                <td height="20" align="left" style="background-color: #e2e6506e;" class="p-2" ><b><?php if($tbalamt > 0) { echo number_format($tbalamt,2,'.',',') ;}?></b>&nbsp;</td>
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