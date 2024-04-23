<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>
<?php if(!isset($clientbal_qry)) { ?>
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>List of Balance (Client)</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="balanceClientwise" name="balanceClientwise" onsubmit="setValue(event)">
            <section class="section dashboard">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            <div class="col-md-3 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="branch_code" required >
                                    <?php foreach($data['branches'] as $branch) { ?>
                                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">As On <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="ason_date" value="<?= date('d-m-Y')?>" required />
                            </div>		
                            <div class="col-md-2 float-start px-2 mb-3 position-relative">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Client Code</label>					
                                <input type="text" class="form-control w-100 float-start" name="client_code" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06"/>
                                <i class="fa fa-binoculars icn-vw" aria-hidden="true" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-5 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>					
                                <input type="text" class="form-control w-100 float-start" name="client_name" id="clientName" readonly/>
                            </div>
                            <div class="col-md-3 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Report Type <strong class="text-danger">*</strong></label>
                                <select class="form-select w-100 float-start" name="report_type" required >
                                    <option value="D">Detail</option>
                                    <option value="S">Summary</option>
                                </select>
                            </div>
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                                <select class="form-select w-100 float-start" name="output_type" required >
                                    <option value="Report">View Report</option>
                                    <option value="Pdf" >Download PDF</option>
                                    <option value="Excel" >Download Excel</option>
                                </select>
                            </div>	
                            
                            <div class="d-inline-block w-100 mt-2">
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
                $maxline    = 40 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $gopbalamt  = 0; 
                $gtotdramt  = 0; 
                $gtotcramt  = 0; 
                $gclbalamt  = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($clientbal_qry[$rowcnt-1]) ? $clientbal_qry[$rowcnt-1] : '' ;  
                $report_cnt = $params['clientbal_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $mopbalamt = 0; 
                $mtotdramt = 0; 
                $mtotcramt = 0; 
                $mclbalamt = 0; 
                $pclntcd   = $report_row['client_code'] ;
                $pclntnm   = $report_row['client_name'] ;
                $pclntind  = 'Y';
                while ($pclntcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
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
                                    <td class="report_label_text">&nbsp;Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y') ?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;As On</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']; ; ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Client</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo strtoupper($pclntnm)?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
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
                            <th width="06%" align="left"  class="px-3 py-2">&nbsp;Matter</th>
                            <th width="40%" align="left"  class="px-3 py-2">&nbsp;Matter Description</th>
                            <th width="15%" align="right" class="px-3 py-2" colspan="2">&nbsp;Opening&nbsp;</th>
                            <th width="12%" align="right" class="px-3 py-2">&nbsp;Debit&nbsp;</th>
                            <th width="12%" align="right" class="px-3 py-2">&nbsp;Credit&nbsp;</th>
                            <th width="15%" align="right" class="report_detail_tb"  colspan="2">&nbsp;Closing&nbsp;</th>
                        </tr>
                                    
            <?php
                        $lineno = 8 ;
                        $pclntind = 'Y';
                    }
                    if($pclntind == 'Y')
                    {
            ?>
                                    <tr class="fs-14">
                                    <td height="5" align="left" class="p-2" colspan="4">&nbsp;</b></td> 
                                    </tr>
            <?php
                    $pclntind = 'N';
                    $lineno      = $lineno + 1;
                    }
            ?>
                                    <tr class="fs-14">
                                        <td height="18" align="left"  class="p-2" style="vertical-align:text-top"><?php echo $report_row['matter_code'] ?></td> 
                                        <td height="18" align="left"  class="p-2" style="vertical-align:text-top"><?php echo $report_row['matter_desc'] ?></td> 
                                        <td height="18" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['opbalamt'] != 0) { echo abs($report_row['opbalamt']); } ?>&nbsp;</td>
                                        <td height="18" align="left"  class="p-2" style="vertical-align:text-top"><?php if($report_row['opbalamt']  < 0) { echo 'CR'; } ?>&nbsp;</td>
                                        <td height="18" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['totdramt']  > 0) { echo $report_row['totdramt']; } ?>&nbsp;</td>
                                        <td height="18" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['totcramt']  > 0) { echo $report_row['totcramt']; } ?>&nbsp;</td>
                                        <td height="18" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['clbalamt'] != 0) { echo abs($report_row['clbalamt']); } ?>&nbsp;</td>
                                        <td height="18" align="left"  class="p-2" style="vertical-align:text-top"><?php if($report_row['clbalamt']  < 0) { echo 'CR'; } ?>&nbsp;</td>
                                    </tr>
            <?php     
                    $lineno = $lineno + 1;
                    $mopbalamt = $mopbalamt + $report_row['opbalamt'] ;                   
                    $mtotdramt = $mtotdramt + $report_row['totdramt'] ;                   
                    $mtotcramt = $mtotcramt + $report_row['totcramt'] ;                   
                    $mclbalamt = $mclbalamt + $report_row['clbalamt'] ;                   
                    //
                    $report_row = ($rowcnt < $report_cnt) ? $clientbal_qry[$rowcnt] : $report_row;  
                    $rowcnt = $rowcnt + 1 ;
                }  
            ?>                   
                                    <tr class="fs-14">
                                        <td height="20" align="right"  class="p-2" colspan="2" style="background-color:#eff3b1;"><b> Total</b></td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mopbalamt != 0) { echo number_format(abs($mopbalamt),2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="left"  class="p-2" style="background-color:#eff3b1;"><?php if($mopbalamt     < 0) { echo 'CR'; } ?>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mtotdramt  > 0) { echo number_format($mtotdramt,2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mtotcramt  > 0) { echo number_format($mtotcramt,2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mclbalamt != 0) { echo number_format(abs($mclbalamt),2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="left"  class="p-2" style="background-color:#eff3b1;"><?php if($mclbalamt     < 0) { echo 'CR'; } ?>&nbsp;</td>
                                    </tr>
            <?php
                $lineno = $lineno + 1;
                $gopbalamt = $gopbalamt + $mopbalamt ;                   
                $gtotdramt = $gtotdramt + $mtotdramt ;                   
                $gtotcramt = $gtotcramt + $mtotcramt ;                   
                $gclbalamt = $gclbalamt + $mclbalamt ;                   
                }
            ?>
                                    <tr class="fs-14">
                                        <td height="20" align="left"  class="p-2">&nbsp;</td>
                                        <td height="20" align="left"  class="p-2">&nbsp;</td>
                                        <td height="20" align="left"  class="p-2">&nbsp;</td>
                                        <td height="20" align="left"  class="p-2">&nbsp;</td>
                                        <td height="20" align="left"  class="p-2">&nbsp;</td>
                                        <td height="20" align="right" class="p-2">&nbsp;</td>
                                        <td height="20" align="left"  class="p-2">&nbsp;</td>
                                        <td height="20" align="right" class="p-2">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="20" align="left"   class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#bee9f7;"><b>GRAND TOTAL</b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if($gopbalamt != 0) { echo number_format(abs($gopbalamt),2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="left"   class="p-2" style="background-color:#bee9f7;"><?php if($gopbalamt     < 0) { echo 'CR'; } ?>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if($gtotdramt  > 0) { echo number_format($gtotdramt,2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if($gtotcramt  > 0) { echo number_format($gtotcramt,2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if($gclbalamt != 0) { echo number_format(abs($gclbalamt),2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="left"   class="p-2" style="background-color:#bee9f7;"><?php if($gclbalamt     < 0) { echo 'CR'; } ?>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
    </main>
<?php } ?>
<?= $this->endSection() ?>