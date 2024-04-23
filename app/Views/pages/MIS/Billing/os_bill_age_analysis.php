<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($osbill_qry))) { ?> 
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>MIS: Age Analysis</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="osBillAgeAnalysis" name="osBillAgeAnalysis" onsubmit="setValue(event)">
            <section class="section dashboard">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">As On Date<strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start datepicker" name="ason_date"  value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required />
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="branch_code" required >
                                    <?php foreach($data['branches'] as $branch) { ?>
                                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Bill Status <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="collectable_ind" required >
                                    <option value="%">All</option>
                                    <option value="C">Collectable</option>
                                    <option value="D">Doubtful</option>
                                    <option value="B">Bad</option>
                                </select>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Report Seq <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="report_seq" onChange="myReportSeq()" required >
                                    <option value="">--Select--</option>
                                    <option value="G">Client Group-wise</option>
                                    <option value="C">Client-wise</option>
                                </select>
                            </div>				
                            <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Group Code</label>					
                                <input type="text" class="form-control w-100" name="client_group_code" id="clientGroupCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'code_code', ['clientGroupName'], ['code_desc'], 'mis_client_group')" size="05" maxlength="06" readonly>
                                <i class="fa fa-binoculars icn-vw lkupIcn d-none" aria-hidden="true" id="clientGroupCodeLookup" onclick="showData('code_code', '<?= $displayId['group_help_id'] ?>', 'clientGroupCode', ['clientGroupName'], ['code_desc'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>

                            <div class="col-md-9 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Group Description </label>					
                                <input type="text" class="form-control w-100" name="client_group_name" id="clientGroupName" readonly/>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Client Code</label>					
                                <input type="text" class="form-control w-100" name="client_code" id="clientCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'code_code', ['clientName', 'clientGroupName', 'clientGroupCode'], ['client_name', 'code_desc', 'client_group_code'], 'mis_client')" size="05" maxlength="06" readonly>
                                <i class="fa fa-binoculars icn-vw lkupIcn d-none" aria-hidden="true" id="clientCodeLookup" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName', 'clientGroupName', 'clientGroupCode'], ['client_name', 'code_desc', 'client_group_code'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-9 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name </label>					
                                <input type="text" class="form-control w-100" name="client_name" id="clientName" readonly/>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="output_type" required >
                                    <option value="Report">View Report</option>
                                    <option value="Pdf" >Download PDF</option>
                                    <option value="Excel" >Download Excel</option>
                                </select>
                            </div>
                            
                            
                            <div class="col-md-9 d-inline-block mt-20">
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

	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important; background-color:#fff;"' : '' ?>>
			<div class="position-absolute btndv">
				<?php if ($renderFlag) : ?>
					<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
            <?php if($report_seq == 'C') { ?>
                <?php
                    $maxline      = 52 ;
                    $lineno       = 0 ;
                    $pageno       = 0 ;
                    $ttot_amt1    = 0 ;
                    $ttot_amt2    = 0 ;
                    $ttot_amt3    = 0 ;
                    $ttot_amt4    = 0 ;
                    $ttot_amt5    = 0 ;
                    $ttot_tamt    = 0 ;
                    $ttot_uamt    = 0 ;
                    $ttot_namt    = 0 ;
                    $rowcnt       = 1 ;
                    $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;  
                    $report_cnt   = $params['osbill_cnt'] ;
                    while ($rowcnt <= $report_cnt)
                    {
                        $ctot_amt1     = 0 ;
                        $ctot_amt2     = 0 ;
                        $ctot_amt3     = 0 ;
                        $ctot_amt4     = 0 ;
                        $ctot_amt5     = 0 ;
                        $ctot_tamt     = 0 ;
                        $ctot_uamt     = 0 ;
                        $ctot_namt     = 0 ;
                        $pclientcd     = $report_row['client_code'] ;
                        $pclientnm     = $report_row['client_name'] ;
                        while ($pclientcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
                        {
                        if     ($report_row['no_of_days'] >  180) { $ctot_amt5 = $ctot_amt5 + $report_row['bal_amount'] ; } 
                        else if($report_row['no_of_days'] >  120) { $ctot_amt4 = $ctot_amt4 + $report_row['bal_amount'] ; } 
                        else if($report_row['no_of_days'] >   60) { $ctot_amt3 = $ctot_amt3 + $report_row['bal_amount'] ; } 
                        else if($report_row['no_of_days'] >   30) { $ctot_amt2 = $ctot_amt2 + $report_row['bal_amount'] ; } 
                        else if($report_row['no_of_days'] <=  30) { $ctot_amt1 = $ctot_amt1 + $report_row['bal_amount'] ; } 
                        //
                        $ctot_tamt = $ctot_tamt + $report_row['bal_amount'] ;
                        $ctot_uamt = $ctot_uamt + $report_row['uaj_amount'] ;
                        $ctot_namt = $ctot_namt + $report_row['net_amount'] ;
                        //
                        $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row; 
                        $rowcnt = $rowcnt + 1 ;
                        }
                        //
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
                                <td colspan="9">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="08%">&nbsp;</td>
                                        <td width="72%">&nbsp;</td>
                                        <td width="08%">&nbsp;</td>
                                        <td width="12%">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="18" class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
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
                                        <td height="16" class="report_label_text">Branch</td>
                                        <td height="16" class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                                        <td height="16" class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y') ?></td>
                                    </tr>
                                    <tr>
                                        <td height="16" class="report_label_text">As On</td>
                                        <td height="16" class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
                                        <td height="16" class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                        <td height="16" class="report_label_text">&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;</td>
                                    </tr>
                                    </table>
                                </td>    
                            </tr>
                            <tr class="fs-14">
                                <td align="left"   class="report_detail_top">&nbsp;</td>
                                <td align="center" class="p-2 fw-bold" colspan="5" style="background-color: #beefff;"> Days </td>
                                <td align="right"  class="report_detail_top">&nbsp;</td>
                                <td align="right"  class="report_detail_top">&nbsp;</td>
                                <td align="right"  class="report_detail_top">&nbsp;</td>
                            </tr>
                            <tr class="fs-14">
                                <th width="28%" align="left"   class="py-3 px-2">Client Name</th>
                                <th width="09%" align="right"  class="py-3 px-2">0 - 30</th>
                                <th width="09%" align="right"  class="py-3 px-2">31 - 60</th>
                                <th width="09%" align="right"  class="py-3 px-2">61 - 120</th>
                                <th width="08%" align="right"  class="py-3 px-2">121 - 180</th>
                                <th width="09%" align="right"  class="py-3 px-2">> 180</th>
                                <th width="09%" align="right"  class="py-3 px-2">Total</th>
                                <th width="09%" align="right"  class="py-3 px-2">Unadj Adv</th>
                                <th width="09%" align="right"  class="py-3 px-2">Net O/s</th>
                            </tr>
                                    
                    <?php
                                $lineno = 8 ;
                            }
                    ?>
                                    <tr class="fs-14">
                                        <td height="15" align="left"   class="p-2"><?php echo strtoupper($pclientnm) ?></td>
                                        <td height="15" align="right"  class="p-2"><?php if($ctot_amt1 >0 )  {echo number_format($ctot_amt1,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td height="15" align="right"  class="p-2"><?php if($ctot_amt2 >0 )  {echo number_format($ctot_amt2,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td height="15" align="right"  class="p-2"><?php if($ctot_amt3 >0 )  {echo number_format($ctot_amt3,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td height="15" align="right"  class="p-2"><?php if($ctot_amt4 >0 )  {echo number_format($ctot_amt4,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td height="15" align="right"  class="p-2"><?php if($ctot_amt5 >0 )  {echo number_format($ctot_amt5,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td height="15" align="right"  class="p-2"><?php if($ctot_tamt >0 )  {echo number_format($ctot_tamt,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td height="15" align="right"  class="p-2"><?php if($ctot_uamt >0 )  {echo number_format($ctot_uamt,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td height="15" align="right"  class="p-2"><?php if($ctot_namt >=0)  {echo number_format($ctot_namt,2,'.','');}  else {echo '('.number_format(abs($ctot_namt),2,'.','').')' ;}?></td>
                                    </tr>
                    <?php     
                                $lineno = $lineno + 1 ; 
                                $ttot_amt1 = $ttot_amt1 + $ctot_amt1 ;
                                $ttot_amt2 = $ttot_amt2 + $ctot_amt2 ;
                                $ttot_amt3 = $ttot_amt3 + $ctot_amt3 ;
                                $ttot_amt4 = $ttot_amt4 + $ctot_amt4 ;
                                $ttot_amt5 = $ttot_amt5 + $ctot_amt5 ;
                                $ttot_tamt = $ttot_tamt + $ctot_tamt ;
                                $ttot_uamt = $ttot_uamt + $ctot_uamt ;
                                $ttot_namt = $ttot_namt + $ctot_namt ;
                        }
                    ?>
                                    <tr class="fs-14">
                                        <td height="15" colspan="9"><hr size="2" noshade></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="18" align="center" class="p-2" style="background-color: #91d6ec6e;"><b> Grand Total </b></td> 
                                        <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt1 >0 )  {echo number_format($ttot_amt1,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt2 >0 )  {echo number_format($ttot_amt2,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt3 >0 )  {echo number_format($ttot_amt3,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt4 >0 )  {echo number_format($ttot_amt4,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt5 >0 )  {echo number_format($ttot_amt5,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_tamt >0 )  {echo number_format($ttot_tamt,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_uamt >0 )  {echo number_format($ttot_uamt,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="18" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_namt >=0)  {echo number_format($ttot_namt,2,'.','');}  else {echo '('.number_format(abs($ttot_namt),2,'.','').')' ;}?></b></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="15" colspan="9"><hr size="2" noshade></td>
                                    </tr>
                                </table>
                                </td>
                            </tr>
                        </table> 
            <?php } else if($report_seq == 'G') { ?>
                <?php
                    $maxline      = 38 ;
                    $lineno       = 0 ;
                    $pageno       = 0 ;
                    $ttot_amt1    = 0 ;
                    $ttot_amt2    = 0 ;
                    $ttot_amt3    = 0 ;
                    $ttot_amt4    = 0 ;
                    $ttot_amt5    = 0 ;
                    $ttot_tamt    = 0 ;
                    $ttot_uamt    = 0 ;
                    $ttot_namt    = 0 ;
                    $rowcnt       = 1 ;
                    $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;  
                    $report_cnt   = $params['osbill_cnt'] ;
                    while ($rowcnt <= $report_cnt)
                    {
                    $gtot_amt1    = 0 ;
                    $gtot_amt2    = 0 ;
                    $gtot_amt3    = 0 ;
                    $gtot_amt4    = 0 ;
                    $gtot_amt5    = 0 ;
                    $gtot_tamt    = 0 ;
                    $gtot_uamt    = 0 ;
                    $gtot_namt    = 0 ;
                    $pgroupind    = 'Y';
                    $pgroupcd     = $report_row['group_code'] ;
                    $pgroupnm     = $report_row['group_name'] ;
                    while ($pgroupcd == $report_row['group_code'] && $rowcnt <= $report_cnt)
                    {
                        $ctot_amt1     = 0 ;
                        $ctot_amt2     = 0 ;
                        $ctot_amt3     = 0 ;
                        $ctot_amt4     = 0 ;
                        $ctot_amt5     = 0 ;
                        $ctot_tamt     = 0 ;
                        $ctot_uamt     = 0 ;
                        $ctot_namt     = 0 ;
                        $pclientcd     = $report_row['client_code'] ;
                        $pclientnm     = $report_row['client_name'] ;
                        while ($pgroupcd == $report_row['group_code'] && $pclientcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
                        {
                        if     ($report_row['no_of_days'] >  180) { $ctot_amt5 = $ctot_amt5 + $report_row['bal_amount'] ; } 
                        else if($report_row['no_of_days'] >  120) { $ctot_amt4 = $ctot_amt4 + $report_row['bal_amount'] ; } 
                        else if($report_row['no_of_days'] >   60) { $ctot_amt3 = $ctot_amt3 + $report_row['bal_amount'] ; } 
                        else if($report_row['no_of_days'] >   30) { $ctot_amt2 = $ctot_amt2 + $report_row['bal_amount'] ; } 
                        else if($report_row['no_of_days'] <=  30) { $ctot_amt1 = $ctot_amt1 + $report_row['bal_amount'] ; } 
                        
                        $ctot_tamt = $ctot_tamt + $report_row['bal_amount'] ;
                        $ctot_uamt = $ctot_uamt + $report_row['uaj_amount'] ;
                        $ctot_namt = $ctot_namt + $report_row['net_amount'] ;
                        
                        $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;  
                        $rowcnt = $rowcnt + 1 ;
                        }
                        
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
                                <td colspan="9">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="08%">&nbsp;</td>
                                        <td width="72%">&nbsp;</td>
                                        <td width="08%">&nbsp;</td>
                                        <td width="12%">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td height="18" class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company')?></b></td>
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
                                        <td height="16" class="report_label_text">Branch</td>
                                        <td height="16" class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                                        <td height="16" class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y') ?></td>
                                    </tr>
                                    <tr>
                                        <td height="16" class="report_label_text">As On</td>
                                        <td height="16" class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
                                        <td height="16" class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                        <td height="16" class="report_label_text">&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;</td>
                                        <td height="16" class="report_label_text">&nbsp;</td>
                                    </tr>
                                    </table>
                                </td>    
                            </tr>
                            <tr class="fs-14">
                                <td align="left"   class="p-2">&nbsp;</td>
                                <td align="center" class="p-2 fw-bold" colspan="5" style="background-color: #beefff;"> Days </td>
                                <td align="right"  class="p-2">&nbsp;</td>
                                <td align="right"  class="p-2">&nbsp;</td>
                                <td align="right"  class="p-2">&nbsp;</td>
                            </tr>
                            <tr class="fs-14">
                                <th width="28%" align="left"   class="p-2">Client Name</th>
                                <th width="09%" align="right"  class="p-2">0 - 30</th>
                                <th width="09%" align="right"  class="p-2">31 - 60</th>
                                <th width="09%" align="right"  class="p-2">61 - 120</th>
                                <th width="08%" align="right"  class="p-2">121 - 180</th>
                                <th width="09%" align="right"  class="p-2">> 180</th>
                                <th width="09%" align="right"  class="p-2">Total</th>
                                <th width="09%" align="right"  class="p-2">Unadj Adv</th>
                                <th width="09%" align="right"  class="p-2">Net O/s</th>
                            </tr>
                                    
                <?php
                            $lineno    = 8 ;
                            $pgroupind = 'Y' ;
                        }
                        if ($pgroupind == 'Y')
                        {
                ?>			   
                                    <tr class="fs-14">
                                        <td height="22" colspan="9" class="report_detail_none"><b><?php echo strtoupper($pgroupnm)?></b></td>
                                    </tr> 
                <?php
                            $lineno    = $lineno + 1 ; 
                            $pgroupind = 'N' ;
                        }
                ?>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2"><?php echo strtoupper($pclientnm) ?></td>
                                        <td align="right"  class="p-2"><?php if($ctot_amt1 >0 )  {echo number_format($ctot_amt1,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td align="right"  class="p-2"><?php if($ctot_amt2 >0 )  {echo number_format($ctot_amt2,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td align="right"  class="p-2"><?php if($ctot_amt3 >0 )  {echo number_format($ctot_amt3,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td align="right"  class="p-2"><?php if($ctot_amt4 >0 )  {echo number_format($ctot_amt4,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td align="right"  class="p-2"><?php if($ctot_amt5 >0 )  {echo number_format($ctot_amt5,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td align="right"  class="p-2"><?php if($ctot_tamt >0 )  {echo number_format($ctot_tamt,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td align="right"  class="p-2"><?php if($ctot_uamt >0 )  {echo number_format($ctot_uamt,2,'.','');}  else {echo '&nbsp;' ;}?></td>
                                        <td align="right"  class="p-2"><?php if($ctot_namt >=0)  {echo number_format($ctot_namt,2,'.','');}  else {echo '('.number_format(abs($ctot_namt),2,'.','').')' ;}?></td>
                                    </tr>
                <?php     
                        $lineno = $lineno + 1 ; 
                        $gtot_amt1 = $gtot_amt1 + $ctot_amt1 ;
                        $gtot_amt2 = $gtot_amt2 + $ctot_amt2 ;
                        $gtot_amt3 = $gtot_amt3 + $ctot_amt3 ;
                        $gtot_amt4 = $gtot_amt4 + $ctot_amt4 ;
                        $gtot_amt5 = $gtot_amt5 + $ctot_amt5 ;
                        $gtot_tamt = $gtot_tamt + $ctot_tamt ;
                        $gtot_uamt = $gtot_uamt + $ctot_uamt ;
                        $gtot_namt = $gtot_namt + $ctot_namt ;
                    }  
                ?>
                                    <tr class="fs-14">
                                        <td height="14" colspan="9">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="22" align="right"  class="p-2" style="background-color: #e2e6506e;"><b> Group Total</b></td> 
                                        <td height="22" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtot_amt1 >0 )  {echo number_format($gtot_amt1,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="22" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtot_amt2 >0 )  {echo number_format($gtot_amt2,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="22" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtot_amt3 >0 )  {echo number_format($gtot_amt3,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="22" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtot_amt4 >0 )  {echo number_format($gtot_amt4,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="22" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtot_amt5 >0 )  {echo number_format($gtot_amt5,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="22" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtot_tamt >0 )  {echo number_format($gtot_tamt,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="22" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtot_uamt >0 )  {echo number_format($gtot_uamt,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td height="22" align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtot_namt >=0)  {echo number_format($gtot_namt,2,'.','');}  else {echo '('.number_format(abs($gtot_namt),2,'.','').')' ;}?></b></td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="15" colspan="9"></td>
                                    </tr>
                <?php
                    $lineno = $lineno + 3 ; 
                    $ttot_amt1 = $ttot_amt1 + $gtot_amt1 ;
                    $ttot_amt2 = $ttot_amt2 + $gtot_amt2 ;
                    $ttot_amt3 = $ttot_amt3 + $gtot_amt3 ;
                    $ttot_amt4 = $ttot_amt4 + $gtot_amt4 ;
                    $ttot_amt5 = $ttot_amt5 + $gtot_amt5 ;
                    $ttot_tamt = $ttot_tamt + $gtot_tamt ;
                    $ttot_uamt = $ttot_uamt + $gtot_uamt ;
                    $ttot_namt = $ttot_namt + $gtot_namt ;
                    }
                ?>
                                    <tr class="fs-14">
                                        <td height="18" align="right" class="p-2" style="background-color: #91d6ec6e;"><b> Grand Total </b></td> 
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt1 >0 )  {echo number_format($ttot_amt1,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt2 >0 )  {echo number_format($ttot_amt2,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt3 >0 )  {echo number_format($ttot_amt3,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt4 >0 )  {echo number_format($ttot_amt4,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_amt5 >0 )  {echo number_format($ttot_amt5,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_tamt >0 )  {echo number_format($ttot_tamt,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_uamt >0 )  {echo number_format($ttot_uamt,2,'.','');}  else {echo '&nbsp;' ;}?></b></td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttot_namt >=0)  {echo number_format($ttot_namt,2,'.','');}  else {echo '('.number_format(abs($ttot_namt),2,'.','').')' ;}?></b></td>
                                    </tr>
                                </table>
                                </td>
                            </tr>
                        </table> 
            <?php } ?>
    </main>
<?php } ?>

<script>
    function myReportSeq() {
        if (document.osBillAgeAnalysis.report_seq.value == '') {
            document.osBillAgeAnalysis.client_group_code.value = '' ; 
            document.osBillAgeAnalysis.client_group_name.value = '' ; 
            document.osBillAgeAnalysis.client_code.value       = '' ; 
            document.osBillAgeAnalysis.client_name.value       = '' ; 
            document.osBillAgeAnalysis.client_group_code.readOnly   = true ; 
            document.osBillAgeAnalysis.client_code.readOnly         = true ; 
            document.getElementById("clientGroupCodeLookup").classList.add('d-none'); 
            document.getElementById("clientCodeLookup").classList.add('d-none'); 
            document.osBillAgeAnalysis.report_seq.focus() ; 
        } else if (document.osBillAgeAnalysis.report_seq.value == 'G') {
            document.osBillAgeAnalysis.client_group_code.value      = '' ; 
            document.osBillAgeAnalysis.client_group_name.value      = '' ; 
            document.osBillAgeAnalysis.client_code.value            = '' ; 
            document.osBillAgeAnalysis.client_name.value            = '' ; 
            document.osBillAgeAnalysis.client_group_code.readOnly   = false ; 
            document.osBillAgeAnalysis.client_code.readOnly         = true ; 
            document.getElementById("clientGroupCodeLookup").classList.remove('d-none'); 
            document.getElementById("clientCodeLookup").classList.add('d-none'); 
            document.osBillAgeAnalysis.client_group_code.focus() ; 
        } else if (document.osBillAgeAnalysis.report_seq.value == 'C') {
            document.osBillAgeAnalysis.client_group_code.value      = '' ; 
            document.osBillAgeAnalysis.client_group_name.value      = '' ; 
            document.osBillAgeAnalysis.client_code.value            = '' ; 
            document.osBillAgeAnalysis.client_name.value            = '' ; 
            document.osBillAgeAnalysis.client_group_code.readOnly   = true ; 
            document.osBillAgeAnalysis.client_code.readOnly         = false ; 
            document.getElementById("clientGroupCodeLookup").classList.add('d-none'); 
            document.getElementById("clientCodeLookup").classList.remove('d-none'); 
            document.osBillAgeAnalysis.client_code.focus() ; 
        } 
	}

    function setValue(e) {
        e.preventDefault();

        if (document.osBillAgeAnalysis.output_type.value == "Excel" && document.osBillAgeAnalysis.report_seq.value == 'G') {
            Swal.fire({ text: 'Please Select Client-wise Report Seq !!!' }).then((result) => { setTimeout(() => {document.osBillAgeAnalysis.report_seq.focus()}, 500) });
            return false;
        }
        
        document.osBillAgeAnalysis.submit();
    }
</script>
<?= $this->endSection() ?>