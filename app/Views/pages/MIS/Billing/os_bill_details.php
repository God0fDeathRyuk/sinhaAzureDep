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
        <h1>MIS: O/S Bill (Details)</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="osBillDetails" name="osBillDetails" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">As On Date<strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-100 float-start" name="ason_date"  value="<?= date('d-m-Y') ?>" readonly required />
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
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Report Seq <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="report_seq" onChange="myReportSeq()" required >
                                <option value="">--Select--</option>
                                <option value="G">Client Group-wise</option>
                                <option value="C">Client-wise</option>
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Reference Type</label>
                            <select class="form-select" name="reference_type" >
                                <option value="%">All</option>
                                <?php foreach($data['reftyp_qry'] as $reftyp_row) { ?>
                                    <option value="<?php echo $reftyp_row['code_code'];?>"><?php echo $reftyp_row['code_desc'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Group Code</label>					
                            <input type="text" class="form-control w-100" name="client_group_code" id="clientGroupCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'code_code', ['clientGroupName'], ['code_desc'], 'mis_client_group')" size="05" maxlength="06" readonly>
                            <i class="fa fa-binoculars icn-vw lkupIcn d-none" aria-hidden="true" id="clientGroupCodeLookup" onclick="showData('code_code', '<?= $displayId['group_help_id'] ?>', 'clientGroupCode', ['clientGroupName'], ['code_desc'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>

                        <div class="col-md-9 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Group Description </label>					
                            <input type="text" class="form-control w-100" name="client_group_name" id="clientGroupName" readonly/>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Code</label>					
                            <input type="text" class="form-control w-100" name="client_code" id="clientCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'code_code', ['clientName', 'clientGroupName', 'clientGroupCode'], ['client_name', 'code_desc', 'client_group_code'], 'mis_client')" size="05" maxlength="06" readonly>
                            <i class="fa fa-binoculars icn-vw lkupIcn d-none" aria-hidden="true" id="clientCodeLookup" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName', 'clientGroupName', 'clientGroupCode'], ['client_name', 'code_desc', 'client_group_code'], '')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name </label>					
                            <input type="text" class="form-control w-100" name="client_name" id="clientName" readonly/>
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
                        <div class="col-md-4 float-start px-2 mb-3">
                            <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="output_type" required >
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

	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
			<div class="position-absolute btndv">
				<?php if ($renderFlag) : ?>
					<a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
            <?php if($report_seq == 'C') { ?>
                <?php
                    $maxline      = 45 ;
                    $lineno       = 0 ;
                    $pageno       = 0 ;
                    $tbbal_amount = 0 ; 
                    $tabal_amount = 0 ; 
                    $tnbal_amount = 0 ;
                    $rowcnt       = 1 ;
                    $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ; 
                    $report_cnt   = $params['osbill_cnt'] ;
                    while ($rowcnt <= $report_cnt)
                    {
                    $cbbal_amount = 0 ; 
                    $cabal_amount = 0 ; 
                    $cnbal_amount = 0 ;
                    $pclientind   = 'Y' ;
                    $pclientcd    = $report_row['client_code'] ;
                    $pclientnm    = $report_row['client_name'] ;
                    while ($pclientcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
                    {
                        $plevel = $report_row['level_ind'];
                        while ($pclientcd == $report_row['client_code'] && $plevel == $report_row['level_ind'] && $rowcnt <= $report_cnt)
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
                            <table width="900" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                                <tr>
                                    <td colspan="8">    
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
                                        <td height="16" class="report_label_text">Client</td>
                                        <td height="16" class="report_label_text">&nbsp;:&nbsp;<b><?= ($params['client_code'] == '%') ? 'ALL' : $params['client_name']  ?></b></td>
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
                                    <th width="13%" align="left"   class="py-3 px-2">Bill No./Doc No</th>
                                    <th width="11%" align="center" class="py-3 px-2">Doc Dt</th>
                                    <th width="18%" align="left"   class="py-3 px-2">Description</th>
                                    <th width="14%" align="left"   class="py-3 px-2">&nbsp;</th>
                                    <th width="14%" align="left"   class="py-3 px-2">&nbsp;</th>
                                    <th width="10%" align="right"  class="py-3 px-2">Total</th>
                                    <th width="10%" align="right"  class="py-3 px-2">Settled</th>
                                    <th width="10%" align="right"  class="py-3 px-2">Balance</th>
                                </tr>
                                        
                    <?php
                                $lineno = 8 ;
                                }
                                if ($pclientind == 'Y')
                                {
                    ?>			   
                                            <tr>
                                            <td height="20" colspan="9" class="report_detail_none"><b><?php echo strtoupper($pclientnm)?></b></td>
                                            </tr> 
                    <?php
                                $lineno     = $lineno + 2 ; 
                                $pclientind = 'N' ;
                                }
                    ?>
                                        <tr class="fs-14">
                                            <td align="left"   class="p-2"><?php echo $report_row['doc_no'] ?></td> 
                                            <td align="center" class="p-2"><?php echo date_conv($report_row['doc_date'])?></td>
                                            <td align="left"   class="p-2" colspan="3" rowspan="2" style="vertical-align:top"><?php echo $report_row['matter_desc'] ?></td> 
                                            <td align="right"  class="p-2"><?php if($report_row['tot_amount']>0) {echo $report_row['tot_amount'];} else {echo '&nbsp;' ;}?></td>
                                            <td align="right"  class="p-2"><?php if($report_row['adj_amount']>0) {echo $report_row['adj_amount'];} else {echo '&nbsp;' ;}?></td>
                                            <td align="right"  class="p-2"><?php if($report_row['bal_amount']>0) {echo $report_row['bal_amount'];} else {echo '&nbsp;' ;}?></td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td align="left"   class="p-2">&nbsp;</td> 
                                        </tr>
                    <?php     
                                $lineno = $lineno + 2 ; 
                                if ($plevel=='1') { $cbbal_amount = $cbbal_amount + $report_row['bal_amount'] ; } else { $cabal_amount = $cabal_amount + $report_row['bal_amount'] ; }
                                $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;   
                                $rowcnt = $rowcnt + 1 ;
                            }  
                    ?>
                                            <tr class="fs-14">
                                                <td >&nbsp;</td>
                                                <td colspan="8"><hr size="1" color="#CCCCCC" noshade></td>
                                            </tr>
                    <?php
                            $lineno = $lineno + 1 ; 
                        }
                        $cnbal_amount = $cbbal_amount - $cabal_amount ;
                    ?>
                                        <tr class="fs-14">
                                            <td align="right"   class="p-2" colspan="2" style="background-color: #e2e6506e;"><b> Client Total</b></td> 
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b>O/s Bill :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #e2e6506e;">&nbsp;<b><?php echo number_format($cbbal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b>Unadjusted Adv :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #e2e6506e;">&nbsp;<b><?php echo number_format($cabal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b>Net O/s :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #e2e6506e;">&nbsp;<b><?php if($cnbal_amount>=0) {echo number_format($cnbal_amount,2,'.','');} else {echo '('.number_format(abs($cnbal_amount),2,'.','').')' ;}?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td colspan="9"><hr size="1" noshade></td>
                                        </tr>
                    <?php
                            $lineno = $lineno + 2 ; 
                            $tbbal_amount = $tbbal_amount + $cbbal_amount ;
                            $tabal_amount = $tabal_amount + $cabal_amount ;
                        }
                        $tnbal_amount = $tbbal_amount - $tabal_amount ;
                    ?>
                                        
                                        <tr class="fs-14">
                                            <td align="right"   class="p-2" colspan="2" style="background-color: #91d6ec6e;"><b> GRAND TOTAL </b></td> 
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b>O/s Bill :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;<b><?php echo number_format($tbbal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b>Unadjusted Adv :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;<b><?php echo number_format($tabal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b>Net O/s :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;<b><?php if($tnbal_amount>=0) {echo number_format($tnbal_amount,2,'.','');} else {echo '('.number_format(abs($tnbal_amount),2,'.','').')' ;}?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                        </tr>
                                </table>
                                </td>
                            </tr>
                        </table> 
            <?php } else if($report_seq == 'G') { ?>
                <?php
                    $maxline      = 45 ;
                    $lineno       = 0 ;
                    $pageno       = 0 ;
                    $tbbal_amount = 0 ; 
                    $tabal_amount = 0 ; 
                    $tnbal_amount = 0 ;
                    $rowcnt       = 1 ;
                    $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;  
                    $report_cnt   = $params['osbill_cnt'] ;
                    while ($rowcnt <= $report_cnt)
                    {
                    $gbbal_amount = 0 ; 
                    $gabal_amount = 0 ; 
                    $gnbal_amount = 0 ;
                    $pgroupcd     = $report_row['group_code'] ;
                    $pgroupnm     = $report_row['group_name'] ;
                    while ($pgroupcd == $report_row['group_code']  && $rowcnt <= $report_cnt)
                    {
                        $cbbal_amount = 0 ; 
                        $cabal_amount = 0 ; 
                        $cnbal_amount = 0 ;
                        $pclientind   = 'Y' ;
                        $pclientcd    = $report_row['client_code'] ;
                        $pclientnm    = $report_row['client_name'] ;
                        $preference   = $report_row['reference_name'] ;
                        $prefcode     = $report_row['reference_type'] ;
                        while ($pgroupcd == $report_row['group_code'] && $pclientcd == $report_row['client_code'] && $prefcode == $report_row['reference_type'] && $rowcnt <= $report_cnt)
                        {
                        $plevel = $report_row['level_ind'];
                        while ($pgroupcd == $report_row['group_code'] && $pclientcd == $report_row['client_code'] && $prefcode == $report_row['reference_type'] && $plevel == $report_row['level_ind'] && $rowcnt <= $report_cnt)
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
                            <table width="900" align="center" border="0" cellspacing="0" cellpadding="0" class="table border-0">
                                <tr>
                                    <td colspan="8">    
                                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                        <td width="08%">&nbsp;</td>
                                        <td width="72%">&nbsp;</td>
                                        <td width="08%">&nbsp;</td>
                                        <td width="12%">&nbsp;</td>
                                        </tr>
                                        <tr>
                                        <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('sinha and company');?></b></td>
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
                                        <td class="report_label_text">Branch</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name'];?></b></td>
                                        <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y') ;?></td>
                                        </tr>
                                        <tr>
                                        <td class="report_label_text">As On</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date'];?></b></td>
                                        <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno;?></td>
                                        </tr>
                                        <tr>
                                        <td class="report_label_text">Group</td>
                                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo strtoupper($pgroupnm);?></b></td>
                                        <td class="report_label_text">&nbsp;</td>
                                        <td class="report_label_text">&nbsp;</td>
                                        </tr>
                                        
                                    </table>
                                    </td>    
                                </tr>
                                <tr class="fs-14">
                                    <th width="17%" align="left"   class="py-3 px-2">Doc No</th>
                                    <th width="08%" align="center" class="py-3 px-2">Doc Dt</th>
                                    <th width="17%" align="left"   class="py-3 px-2">Description</th>
                                    <th width="14%" align="left"   class="py-3 px-2">&nbsp;</th>
                                    <th width="14%" align="left"   class="py-3 px-2">&nbsp;</th>
                                    <th width="10%" align="right"  class="py-3 px-2">Total</th>
                                    <th width="10%" align="right"  class="py-3 px-2">Settled</th>
                                    <th width="10%" align="right"  class="py-3 px-2">Balance</th>
                                </tr>
                                        
                <?php
                            $lineno = 8 ;
                            }
                            if ($pclientind == 'Y')
                            {
                ?>			   
                                <tr class="fs-14">
                                    <td height="20" colspan="9" class="p-2"><b><?php echo strtoupper($pclientnm);?></b></td>
                                </tr> 
                                <tr class="fs-14">
                                    <td height="20" colspan="9" class="p-2"><b><?php echo strtoupper($preference);?></b></td>
                                </tr> 						
                <?php
                            $lineno     = $lineno + 2 ; 
                            $pclientind = 'N' ;
                            }
                ?>
                                        <tr class="fs-14">
                                            <td align="left"   class="p-2"><?php echo $report_row['doc_no'] ;?></td> 
                                            <td align="center" class="p-2"><?php echo date_conv($report_row['doc_date']);?></td>
                                            <td align="left"   class="p-2" colspan="3" rowspan="2" style="vertical-align:top"><?php echo $report_row['matter_desc'] ;?></td> 
                                            <td align="right"  class="p-2"><?php if($report_row['tot_amount']>0) {echo $report_row['tot_amount'];} else {echo '&nbsp;' ;}?></td>
                                            <td align="right"  class="p-2"><?php if($report_row['adj_amount']>0) {echo $report_row['adj_amount'];} else {echo '&nbsp;' ;}?></td>
                                            <td align="right"  class="p-2"><?php if($report_row['bal_amount']>0) {echo $report_row['bal_amount'];} else {echo '&nbsp;' ;}?></td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td align="left"   class="p-2">&nbsp;</td> 
                                        </tr>
                <?php     
                            $lineno = $lineno + 2 ; 
                            if ($report_row['level_ind']=='1') { $cbbal_amount = $cbbal_amount + $report_row['bal_amount'] ; } else { $cabal_amount = $cabal_amount + $report_row['bal_amount'] ; }
                            $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;   
                            $rowcnt = $rowcnt + 1 ;
                        }  
                ?>
                                        <tr class="fs-14">
                                            <td >&nbsp;</td>
                                            <td colspan="8"><hr size="1" color="#CCCCCC" noshade></td>
                                        </tr>
                <?php
                            $lineno = $lineno + 1 ; 
                        }
                        $cnbal_amount = $cbbal_amount - $cabal_amount ;
                ?>
                                        <tr class="fs-14">
                                            <td align="right"   class="p-2" colspan="2" style="background-color: #e2e6506e;"><b> Client Total</b></td> 
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b>O/s Bill :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #e2e6506e;">&nbsp;<b><?php echo number_format($cbbal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b>Unadjusted Adv :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #e2e6506e;">&nbsp;<b><?php echo number_format($cabal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;"><b>Net O/s :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #e2e6506e;">&nbsp;<b><?php if($cnbal_amount>=0) {echo number_format($cnbal_amount,2,'.','');} else {echo '('.number_format(abs($cnbal_amount),2,'.','').')' ;}?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td colspan="9">&nbsp;</td>
                                        </tr>
                <?php
                        $lineno = $lineno + 2 ; 
                        $gbbal_amount = $gbbal_amount + $cbbal_amount ;
                        $gabal_amount = $gabal_amount + $cabal_amount ;
                    }
                    $gnbal_amount = $gbbal_amount - $gabal_amount ;
                ?>
                                        <tr class="fs-14">
                                            <td align="right"   class="p-2" colspan="2" style="background-color: #91d6ec6e;"><b>Group Total</b></td> 
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b>O/s Bill :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;<b><?php echo number_format($gbbal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b>Unadjusted Adv :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;<b><?php echo number_format($gabal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b>Net O/s :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;<b><?php if($gnbal_amount>=0) {echo number_format($gnbal_amount,2,'.','');} else {echo '('.number_format(abs($gnbal_amount),2,'.','').')' ;}?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                        </tr>
                                        <tr class="fs-14">
                                            <td colspan="9">&nbsp;</td>
                                        </tr>
                <?php
                        $lineno = $maxline ; 
                        $tbbal_amount = $tbbal_amount + $gbbal_amount ;
                        $tabal_amount = $tabal_amount + $gabal_amount ;
                    }
                    $tnbal_amount = $tbbal_amount - $tabal_amount ;
                ?>
                                        <tr class="fs-14">
                                            <td align="right"   class="p-2" colspan="2" style="background-color: #91ecdf6e;"><b> GRAND TOTAL </b></td> 
                                            <td align="right"  class="p-2" style="background-color: #91ecdf6e;"><b>O/s Bill :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91ecdf6e;">&nbsp;<b><?php echo number_format($tbbal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91ecdf6e;"><b>Unadjusted Adv :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91ecdf6e;">&nbsp;<b><?php echo number_format($tabal_amount,2,'.',''); ?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91ecdf6e;"><b>Net O/s :</b>&nbsp;</td>
                                            <td align="left"   class="p-2" style="background-color: #91ecdf6e;">&nbsp;<b><?php if($tnbal_amount>=0) {echo number_format($tnbal_amount,2,'.','');} else {echo '('.number_format(abs($tnbal_amount),2,'.','').')' ;}?></b></td>
                                            <td align="right"  class="p-2" style="background-color: #91ecdf6e;">&nbsp;</td>
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
        if (document.osBillDetails.report_seq.value == '') {

            document.osBillDetails.client_group_code.value = '' ; 
            document.osBillDetails.client_group_name.value = '' ; 
            document.osBillDetails.client_code.value       = '' ; 
            document.osBillDetails.client_name.value       = '' ; 
            document.osBillDetails.client_group_code.readOnly   = true ; 
            document.osBillDetails.client_code.readOnly         = true ; 
            document.getElementById("clientGroupCodeLookup").classList.add('d-none'); 
            document.getElementById("clientCodeLookup").classList.add('d-none'); 
            document.osBillDetails.report_seq.focus() ; 

        } else if (document.osBillDetails.report_seq.value == 'G') {

            document.osBillDetails.client_group_code.value      = '' ; 
            document.osBillDetails.client_group_name.value      = '' ; 
            document.osBillDetails.client_code.value            = '' ; 
            document.osBillDetails.client_name.value            = '' ; 
            document.osBillDetails.client_group_code.readOnly   = false ; 
            document.osBillDetails.client_code.readOnly         = true ; 
            document.getElementById("clientGroupCodeLookup").classList.remove('d-none'); 
            document.getElementById("clientCodeLookup").classList.add('d-none'); 
            document.osBillDetails.client_group_code.focus() ; 

        } else if (document.osBillDetails.report_seq.value == 'C') {
            
            document.osBillDetails.client_group_code.value      = '' ; 
            document.osBillDetails.client_group_name.value      = '' ; 
            document.osBillDetails.client_code.value            = '' ; 
            document.osBillDetails.client_name.value            = '' ; 
            document.osBillDetails.client_group_code.readOnly   = true ; 
            document.osBillDetails.client_code.readOnly         = false ; 
            document.getElementById("clientGroupCodeLookup").classList.add('d-none'); 
            document.getElementById("clientCodeLookup").classList.remove('d-none'); 
            document.osBillDetails.client_code.focus() ; 
        } 
	}
</script>
<?= $this->endSection() ?>