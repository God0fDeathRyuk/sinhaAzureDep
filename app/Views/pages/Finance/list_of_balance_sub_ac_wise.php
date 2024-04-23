<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>
<?php if((!isset($subacbal_qry))) { ?> 
    <main id="main" class="main">
            
            <?php if (session()->getFlashdata('message') !== NULL) : ?>
                <div id="alertMsg">
                    <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                    <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="pagetitle">
                <h1>List of Balance (Sub A/c)</h1>
            </div><!-- End Page Title -->
            <form action="" method="post" id="courtwiseExpenses" name="courtwiseExpenses" onsubmit="setValue(event)">
                <section class="section dashboard">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <div class="frms-sec d-inline-block w-100 bg-white p-3">
                                <div class="col-md-4 float-start px-2 mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                                    <select class="form-select" name="branch_code" required >
                                        <?php foreach($data['branches'] as $branch) { ?>
                                            <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4 float-start px-2 mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn">As On <strong class="text-danger">*</strong></label>					
                                    <input type="text" class="form-control w-100 float-start" name="ason_date" value="<?= date('d-m-Y')?>" onBlur="make_date(this)" required />
                                </div>		
                                <div class="col-md-4 float-start px-2 mb-3 position-relative">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn">Main A/c Code</label>					
                                    <input type="text" class="form-control w-100 float-start" name="main_ac_code" id="mainAcCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'main_ac_code', ['mainAcDesc'], ['main_ac_desc'], 'sub_ledger_main_account')" />
                                    <i class="fa fa-binoculars icn-vw" aria-hidden="true" onclick="showData('main_ac_code', '<?= $displayId['mainac_help_id'] ?>', 'mainAcCode', ['mainAcDesc'], ['main_ac_desc'], 'sub_ledger_main_account')" title="View" data-toggle="modal" data-target="#lookup"></i>
                                </div>
                                <div class="col-md-8 float-start px-2 mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn">Main A/c Description</label>					
                                    <input class="form-control w-100 float-start" name="main_ac_desc" id="mainAcDesc" readonly>
                                </div>
                                <div class="col-md-4 float-start px-2 mb-3">
                                    <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
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

    </main>
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
                $maxline    = 60 ;
                $lineno     = 0 ;
                $pageno     = 0 ;
                $gopbalamt  = 0; 
                $gtotdramt  = 0; 
                $gtotcramt  = 0; 
                $gclbalamt  = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($subacbal_qry[$rowcnt-1]) ? $subacbal_qry[$rowcnt-1] : '' ; 
                //echo '<pre>';print_r($subacbal_qry);die;
                $report_cnt = $params['subacbal_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $mopbalamt = 0; 
                $mtotdramt = 0; 
                $mtotcramt = 0; 
                $mclbalamt = 0; 
                $pmactcd   = $report_row['main_ac_code'] ;
                $pmactnm   = $report_row['main_ac_desc'] ;
                $pmactind  = 'Y';
                while ($pmactcd == $report_row['main_ac_code'] && $rowcnt <= $report_cnt)
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
                            <td colspan="8">    
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
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo date('d-m-Y')?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;As On</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['ason_date']; ; ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Main A/c</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $pmactnm .' ['.$pmactcd.']'; ?></b></td>
                                    <td class="report_label_text" align="right">&nbsp;&nbsp;</td>
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
                            <th width="05%" align="left"  class="px-3 py-2">&nbsp;A/c</th>
                            <th width="41%" align="left"  class="px-3 py-2">&nbsp;Sub A/c Description</th>
                            <th width="12%" align="right" class="px-3 py-2" >&nbsp;Opening&nbsp;</th>
                            <th width="03%" align="right" class="px-3 py-2">&nbsp;</th>
                            <th width="12%" align="right" class="px-3 py-2">&nbsp;Debit&nbsp;</th>
                            <th width="12%" align="right" class="px-3 py-2">&nbsp;Credit&nbsp;</th>
                            <th width="12%" align="right" class="px-3 py-2" >&nbsp;Closing&nbsp;</th>
                            <th width="03%" align="right" class="px-3 py-2">&nbsp;</th>
                        </tr>
                                    
                <?php
                            $lineno = 8 ;
                            $pmactind = 'Y';
                        }
                        if($pmactind == 'Y')
                        {
                ?>
                                        <tr class="fs-14">
                                            <td height="10" align="left" class="p-2" colspan="4">&nbsp;</td> 
                                        </tr>
                <!--                        <tr>
                                        <td height="20" align="left" class="report_detail_none" colspan="4"><b><?php echo $pmactnm .' ['.$pmactcd.']'; ?></b></td> 
                                        </tr>
                --><?php
                            $pmactind = 'N';
                        $lineno      = $lineno + 1;
                        }

                ?>
                                    <tr class="fs-14">
                                        <td height="16" align="left"  class="p-2" style="vertical-align:text-top"><?php echo $report_row['sub_ac_code'] ?>&nbsp;&nbsp;</td> 
                                        <td height="16" align="left"  class="p-2" style="vertical-align:text-top"><?php echo $report_row['sub_ac_desc'] ?></td> 
                                        <td height="16" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['opbalamt'] != 0) { echo number_format(abs($report_row['opbalamt']),2,'.',''); } ?></td>
                                        <td height="16" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['opbalamt']  < 0) { echo 'CR'; } ?></td>
                                        <td height="16" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['totdramt']  > 0) { echo $report_row['totdramt']; } ?></td>
                                        <td height="16" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['totcramt']  > 0) { echo $report_row['totcramt']; } ?></td>
                                        <td height="16" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['clbalamt'] != 0) { echo number_format(abs($report_row['clbalamt']),2,'.',''); } ?></td>
                                        <td height="16" align="right" class="p-2" style="vertical-align:text-top"><?php if($report_row['clbalamt']  < 0) { echo 'CR'; } ?></td>
                                    </tr>
                <?php     

                        
                        $lineno = $lineno + 1;
                        $mopbalamt = $mopbalamt + $report_row['opbalamt'] ;                   
                        $mtotdramt = $mtotdramt + $report_row['totdramt'] ;                   
                        $mtotcramt = $mtotcramt + $report_row['totcramt'] ;                   
                        $mclbalamt = $mclbalamt + $report_row['clbalamt'] ;                   
                        //
                        $report_row = ($rowcnt < $report_cnt) ? $subacbal_qry[$rowcnt] : $report_row;   
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>                   
                                    <tr class="fs-14">
                                        <td height="20" align="right"  class="p-2" colspan="2" style="background-color:#eff3b1;"><b> Total</b></td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mopbalamt != 0) { echo number_format(abs($mopbalamt),2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mopbalamt  < 0) { echo 'CR' ; } ?></b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mtotdramt  > 0) { echo number_format($mtotdramt,2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mtotcramt  > 0) { echo number_format($mtotcramt,2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mclbalamt != 0) { echo number_format(abs($mclbalamt),2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#eff3b1;"><b><?php if($mclbalamt  < 0) { echo 'CR' ; } ?></b>&nbsp;</td>
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
                                        <td height="20" align="right" class="p-2">&nbsp;</td>
                                        <td height="20" align="left"  class="p-2">&nbsp;</td>
                                        <td height="20" align="right" class="p-2">&nbsp;</td>
                                    </tr>
                                    <tr class="fs-14">
                                        <td height="20" align="left"   class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                                        <td height="20" align="right" class="p-2" style="background-color:#bee9f7;"><b>GRAND TOTAL</b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if($gopbalamt != 0) { echo number_format(abs($gopbalamt),2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="left"   class="p-2" style="background-color:#bee9f7;"><b><?php if($gopbalamt  < 0) { echo 'CR' ; } ?></b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if($gtotdramt  > 0) { echo number_format($gtotdramt,2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if($gtotcramt  > 0) { echo number_format($gtotcramt,2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="right"  class="p-2" style="background-color:#bee9f7;"><b><?php if($gclbalamt != 0) { echo number_format(abs($gclbalamt),2,'.',''); } ?></b>&nbsp;</td>
                                        <td height="20" align="left"   class="p-2" style="background-color:#bee9f7;"><b><?php if($gclbalamt  < 0) { echo 'CR' ; } ?></b>&nbsp;</td>
                                    </tr>
                            </table>
                            </td>
                        </tr>
                    </table> 
    </main>
<?php } ?>
<?= $this->endSection() ?>