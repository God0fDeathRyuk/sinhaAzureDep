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
            <h1>MIS: O/S Bill (Summary)</h1>
        </div><!-- End Page Title -->
        <form action="" method="post" id="osBillSummaryOld" name="osBillSummaryOld" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">As On Date <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control" name="ason_date" value="<?= date('d-m-Y') ?>" required />
                        </div>
                        <div class="col-md-5 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Report Seq <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="report_seq" onChange="myReportSeq()" required >
                                <option value="">--Select--</option>
                                <option value="G">Client Group-wise</option>
                                <option value="C">Client-wise</option>
                            </select>
                        </div>							
                        
                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Group Code</label>
                            <input type="text" class="form-control w-100 float-start" name="client_group_code" id="clientGroupCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'code_code', ['clientGroupName'], ['code_desc'], 'mis_client_group')" readonly>
                            <i class="fa fa-binoculars icn-vw lkupIcn d-none" aria-hidden="true" id="clientGroupCodeLookup" onclick="showData('code_code', '<?= $displayId['group_help_id'] ?>', 'clientGroupCode', ['clientGroupName'], ['code_desc'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Group Desc </label>
                            <input type="text" class="form-control w-100 float-start" name="client_group_name" id="clientGroupName" readonly>
                        </div>
                        
                        <div class="col-md-4 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Code </label>
                            <input type="text" class="form-control w-100 float-start" name="client_code" id="clientCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'code_code', ['clientName', 'clientGroupName', 'clientGroupCode'], ['client_name', 'code_desc', 'client_group_code'], 'mis_client')" readonly>
                            <i class="fa fa-binoculars icn-vw lkupIcn d-none" aria-hidden="true" id="clientCodeLookup" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName', 'clientGroupName', 'clientGroupCode'], ['client_name', 'code_desc', 'client_group_code'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-8 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name</label>
                            <input type="text" class="form-control w-100 float-start" name="client_name" id="clientName" readonly>
                        </div>
                        
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Bill Status <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="collectable_ind" required>
                                <option value="%">All</option>
                                <option value="C">Collectable</option>
                                <option value="D">Doubtful</option>
                                <option value="B">Bad</option>
                            </select>
                        </div>
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">O/s Order <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="os_order" required>
                                <option value="A">O/s Ascending</option>
                                <option value="D">O/s Descending</option>
                                <option value="C">Client Name</option>
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
            <?php if($report_seq == 'C') { ?>
                <?php
                    $maxline      = 75 ;
                    $lineno       = 0 ;
                    $pageno       = 0 ;
                    $tbtot_amount = 0 ; 
                    $tbadj_amount = 0 ; 
                    $ttbal_amount = 0 ;
                    $ttuaj_amount = 0 ;
                    $ttnet_amount = 0 ;
                    $tbbal_amount = 0;
                    $tbuaj_amount  = 0;
                    $tbnet_amount  = 0;
                    $rowcnt       = 1 ;
                    $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;
                    $report_cnt   = $params['osbill_cnt'] ;
                    while ($rowcnt <= $report_cnt)
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
                                <td colspan="6">    
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
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">Branch</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                                        <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">As On</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
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
                                <th align="left"   class="py-3 px-2">&nbsp;<b>Client Name</b></th>
                                <th align="right"  class="py-3 px-2"><b>Total</b>&nbsp;</th>
                                <th align="right"  class="py-3 px-2"><b>Settled</b>&nbsp;</th>
                                <th align="right"  class="py-3 px-2"><b>Balance</b>&nbsp;</th>
                                <th align="right"  class="py-3 px-2"><b>Unadjusted</b>&nbsp;</th>
                                <th align="right"  class="py-3 px-2"><b>Net</b>&nbsp;</th>
                            </tr>
                <?php
                            $lineno    = 8 ;
                            $pgroupind = 'Y' ;
                        }
                ?>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2">&nbsp;<?php echo strtoupper($report_row['client_name']) ?></td>
                                        <td align="right"  class="p-2" ><?php if($report_row['tot_amount']>0)  {echo number_format($report_row['tot_amount'],2,'.','');}  else {echo '&nbsp;' ;}?>&nbsp;</td>
                                        <td align="right"  class="p-2" ><?php if($report_row['adj_amount']>0)  {echo number_format($report_row['adj_amount'],2,'.','');}  else {echo '&nbsp;' ;}?>&nbsp;</td>
                                        <td align="right"  class="p-2" ><?php if($report_row['bal_amount']>0)  {echo number_format($report_row['bal_amount'],2,'.','');}  else {echo '&nbsp;' ;}?>&nbsp;</td>
                                        <td align="right"  class="p-2" ><?php if($report_row['uaj_amount']>0)  {echo number_format($report_row['uaj_amount'],2,'.','');}  else {echo '&nbsp;' ;}?>&nbsp;</td>
                                        <td align="right"  class="p-2" ><?php if($report_row['net_amount']>=0) {echo number_format($report_row['net_amount'],2,'.','').'&nbsp;';}  else {echo '('.number_format(abs($report_row['net_amount']),2,'.','').')' ;}?>&nbsp;</td>
                                    </tr>
                <?php     
                            $lineno = $lineno + 1 ; 
                            $tbtot_amount = $tbtot_amount + $report_row['tot_amount'] ;
                            $tbadj_amount = $tbadj_amount + $report_row['adj_amount'] ;
                            $tbbal_amount = $tbbal_amount + $report_row['bal_amount'] ;
                            $tbuaj_amount = $tbuaj_amount + $report_row['uaj_amount'] ;
                            $tbnet_amount = $tbnet_amount + $report_row['net_amount'] ;
                            $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;   
                            $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                    <tr class="fs-14">
                                        <td height="20" align="right" class="p-2" style="background-color: #91d6ec6e;"><b> Grand Total </b></td> 
                                        <td height="20" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tbtot_amount>0)  {echo number_format($tbtot_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tbadj_amount>0)  {echo number_format($tbadj_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tbbal_amount>0)  {echo number_format($tbbal_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tbuaj_amount>0)  {echo number_format($tbuaj_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tbnet_amount>=0) {echo number_format($tbnet_amount,2,'.','');}  else {echo '('.number_format(abs($tbnet_amount),2,'.','').')' ;}?></b>&nbsp;</td>
                                    </tr>
                                </table>
                                </td>
                            </tr>
                        </table> 
            <?php } else if($report_seq == 'G') { ?>
                <?php
                    $maxline      = 90 ;
                    $lineno       = 0 ;
                    $pageno       = 0 ;
                    $tbtot_amount = 0 ; 
                    $tbadj_amount = 0 ; 
                    $tbbal_amount = 0 ;
                    $ttuaj_amount = 0 ;
                    $ttnet_amount = 0 ;
                    $rowcnt       = 1 ;
                    $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;
                    $report_cnt   = $params['osbill_cnt'] ;
                    while ($rowcnt <= $report_cnt)
                    {
                    $gbtot_amount = 0 ; 
                    $gbadj_amount = 0 ; 
                    $gbbal_amount = 0 ;
                    $gtuaj_amount = 0 ;
                    $gtnet_amount = 0 ;
                    $pgroupind    = 'Y';
                    $pgroupcd     = $report_row['group_code'] ;
                    $pgroupnm     = $report_row['group_name'] ;
                    while ($pgroupcd == $report_row['group_code'] && $rowcnt <= $report_cnt)
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
                            <table width="750" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td colspan="6">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
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
                                        <td class="report_label_text">Branch</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                                        <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y')?></td>
                                    </tr>
                                    <tr>
                                        <td class="report_label_text">As On</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']?></b></td>
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
                                <th align="left"   class="py-3 px-2">&nbsp;Client Name</th>
                                <th align="right"  class="py-3 px-2">Total&nbsp;</th>
                                <th align="right"  class="py-3 px-2">Settled&nbsp;</th>
                                <th align="right"  class="py-3 px-2">Balance&nbsp;</th>
                                <th align="right"  class="py-3 px-2">Unadjusted&nbsp;</th>
                                <th align="right"  class="py-3 px-2">Net&nbsp;</th>
                            </tr>
                            
                <?php
                            $lineno    = 8 ;
                            $pgroupind = 'Y' ;
                        }
                        if ($pgroupind == 'Y')
                        {
                ?>			   
                                    <tr class="fs-14">
                                        <td height="20" colspan="6" class="p-2" style="word-break:break-all;"><b><?php echo strtoupper($pgroupnm)?></b></td>
                                    </tr> 
                <?php
                            $lineno    = $lineno + 2 ; 
                            $pgroupind = 'N' ;
                        }
                ?>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2" style="word-break:break-all;"><?php echo strtoupper($report_row['client_name']) ?></td>
                                        <td align="right"  class="p-2" style="word-break:break-all;"><?php if($report_row['tot_amount']>0)  {echo number_format($report_row['tot_amount'],2,'.','');}  else {echo '&nbsp;' ;}?>&nbsp;</td>
                                        <td align="right"  class="p-2" style="word-break:break-all;"><?php if($report_row['adj_amount']>0)  {echo number_format($report_row['adj_amount'],2,'.','');}  else {echo '&nbsp;' ;}?>&nbsp;</td>
                                        <td align="right"  class="p-2" style="word-break:break-all;"><?php if($report_row['bal_amount']>0)  {echo number_format($report_row['bal_amount'],2,'.','');}  else {echo '&nbsp;' ;}?>&nbsp;</td>
                                        <td align="right"  class="p-2" style="word-break:break-all;"><?php if($report_row['uaj_amount']>0)  {echo number_format($report_row['uaj_amount'],2,'.','');}  else {echo '&nbsp;' ;}?>&nbsp;</td>
                                        <td align="right"  class="p-2" style="word-break:break-all;"><?php if($report_row['net_amount']>=0) {echo number_format($report_row['net_amount'],2,'.','').'&nbsp;';}  else {echo '('.number_format(abs($report_row['net_amount']),2,'.','').')' ;}?>&nbsp;</td>
                                    </tr>
                <?php     
                            $lineno = $lineno + 1 ; 
                            $gbtot_amount = $gbtot_amount + $report_row['tot_amount'] ;
                            $gbadj_amount = $gbadj_amount + $report_row['adj_amount'] ;
                            $gbbal_amount = $gbbal_amount + $report_row['bal_amount'] ;
                            $gtuaj_amount = $gtuaj_amount + $report_row['uaj_amount'] ;
                            $gtnet_amount = $gtnet_amount + $report_row['net_amount'] ;
                            $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;  
                            $rowcnt = $rowcnt + 1 ;
                    }  
                ?>
                                    <tr class="fs-14">
                                        <td colspan="6">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left"   class="p-2" style="background-color: #e2e6506e;"><b> Group Total</b></td> 
                                        <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gbtot_amount>0)  {echo number_format($gbtot_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gbadj_amount>0)  {echo number_format($gbadj_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gbbal_amount>0)  {echo number_format($gbbal_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtuaj_amount>0)  {echo number_format($gtuaj_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b><?php if($gtnet_amount>=0) {echo number_format($gtnet_amount,2,'.','').'&nbsp;';}  else {echo '('.number_format(abs($gtnet_amount),2,'.','').')' ;}?></b>&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td colspan="6">&nbsp;</td>
                                    </tr>
                <?php
                    $lineno = $lineno + 3 ; 
                    $tbtot_amount = $tbtot_amount + $gbtot_amount ;
                    $tbadj_amount = $tbadj_amount + $gbadj_amount ;
                    $tbbal_amount = $tbbal_amount + $gbbal_amount ;
                    $ttuaj_amount = $ttuaj_amount + $gtuaj_amount ;
                    $ttnet_amount = $ttnet_amount + $gtnet_amount ;
                    }
                ?>
                                    <tr class="fs-14">
                                        <td align="center" class="p-2" style="background-color: #91d6ec6e;"><b> Grand Total </b></td> 
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tbtot_amount>0)  {echo number_format($tbtot_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tbadj_amount>0)  {echo number_format($tbadj_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tbbal_amount>0)  {echo number_format($tbbal_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttuaj_amount>0)  {echo number_format($ttuaj_amount,2,'.','');}  else {echo '&nbsp;' ;}?></b>&nbsp;</td>
                                        <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttnet_amount>=0) {echo number_format($ttnet_amount,2,'.','').'&nbsp;';}  else {echo '('.number_format(abs($ttnet_amount),2,'.','').')' ;}?></b>&nbsp;</td>
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
        if (document.osBillSummaryOld.report_seq.value == '') {

            document.osBillSummaryOld.client_group_code.value = '' ; 
            document.osBillSummaryOld.client_group_name.value = '' ; 
            document.osBillSummaryOld.client_code.value       = '' ; 
            document.osBillSummaryOld.client_name.value       = '' ; 
            document.osBillSummaryOld.client_group_code.readOnly   = true ; 
            document.osBillSummaryOld.client_code.readOnly         = true ; 
            document.getElementById("clientGroupCodeLookup").classList.add('d-none'); 
            document.getElementById("clientCodeLookup").classList.add('d-none'); 
            document.osBillSummaryOld.report_seq.focus() ; 

        } else if (document.osBillSummaryOld.report_seq.value == 'G') {

            document.osBillSummaryOld.client_group_code.value      = '' ; 
            document.osBillSummaryOld.client_group_name.value      = '' ; 
            document.osBillSummaryOld.client_code.value            = '' ; 
            document.osBillSummaryOld.client_name.value            = '' ; 
            document.osBillSummaryOld.client_group_code.readOnly   = false ; 
            document.osBillSummaryOld.client_code.readOnly         = true ; 
            document.getElementById("clientGroupCodeLookup").classList.remove('d-none'); 
            document.getElementById("clientCodeLookup").classList.add('d-none'); 
            document.osBillSummaryOld.client_group_code.focus() ; 

        } else if (document.osBillSummaryOld.report_seq.value == 'C') {
            
            document.osBillSummaryOld.client_group_code.value      = '' ; 
            document.osBillSummaryOld.client_group_name.value      = '' ; 
            document.osBillSummaryOld.client_code.value            = '' ; 
            document.osBillSummaryOld.client_name.value            = '' ; 
            document.osBillSummaryOld.client_group_code.readOnly   = true ; 
            document.osBillSummaryOld.client_code.readOnly         = false ; 
            document.getElementById("clientGroupCodeLookup").classList.add('d-none'); 
            document.getElementById("clientCodeLookup").classList.remove('d-none'); 
            document.osBillSummaryOld.client_code.focus() ; 
        } 
	}
</script>
<?= $this->endSection() ?>