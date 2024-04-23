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
        <h1>Bill Status Summary</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="billingCountSummary" name="billingCountSummary" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">				
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
                            <input type="text" class="form-control w-45 float-start datepicker" name="start_date" onBlur="make_date(this)">
                            <span class="w-2 float-start mx-1">--</span>
                            <input type="text" class="form-control w-45 float-start datepicker" name="end_date" value="<?= date('d-m-Y')?>" onBlur="make_date(this)" required>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Options <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="report_for" required >
                                <option value="A">All</option>
                                <option value="B">Billed</option>
                                <option value="N">Not-Billed</option>
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Code</label>					
                            <input type="text" class="form-control w-100" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code" >
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>
                        <div class="col-md-9 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name </label>					
                            <input type="text" class="form-control w-100" id="clientName" name="client_name" readonly/>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Code</label>					
                            <input type="text" class="form-control w-100" oninput="this.value = this.value.toUpperCase()" id="courtCode" onchange="fetchData(this, 'code_code', ['courtName'], ['code_desc'], 'court_code')" size="05" maxlength="06" name="court_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('code_code', '<?= $displayId['court_help_id'] ?>', 'courtCode', ['courtName'], ['code_desc'], 'court_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>

                        <div class="col-md-9 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Court Name </label>					
                            <input type="text" class="form-control w-100" id="courtName" name="court_name" readonly/>
                        </div>
                        
                        <div class="col-md-3 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Initial Code</label>					
                            <input type="text" class="form-control w-100" id="initialCode" oninput="this.value = this.value.toUpperCase()" onchange="fetchData(this, 'initial_code', ['initialName'], ['initial_name'], 'initial_code')" size="05" maxlength="06" name="initial_code">
                            <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('initial_code', '<?= $displayId['initial_help_id'] ?>', 'initialCode', ['initialName'], ['initial_name'], 'initial_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                        </div>

                        <div class="col-md-9 float-start px-2 mb-1 position-relative">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Initial Name </label>					
                            <input type="text" class="form-control w-100" id="initialName" name="initial_name" readonly/>
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
                $gtbilnos   = 0 ;
                $gtbilamt   = 0 ;
                $gtcolamt   = 0 ;
                $rowcnt     = 1 ;
                $report_row = isset($bill_qry[$rowcnt-1]) ? $bill_qry[$rowcnt-1] : '' ;  
                $report_cnt = $params['bill_cnt'] ;
                while ($rowcnt <= $report_cnt)
                {
                $pcourtind = 'Y' ;
                $pcourtcd  = $report_row['court_code'] ;
                $pcourtnm  = $report_row['court_name'] ;
                $ctbilnos  = 0 ;
                $ctbilamt  = 0 ;
                $ctcolamt  = 0 ;
                while ($pcourtcd == $report_row['court_code'] && $rowcnt <= $report_cnt)
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
                            <td colspan="13">    
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
                                    <td class="report_label_text">&nbsp;Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc']?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                    </tr>
                                    <tr>
                                    <td class="report_label_text">&nbsp;Client</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($params['client_code'] != '%') { echo strtoupper($params['client_name']) ; } else { echo 'ALL' ; } ?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text" align="right">&nbsp;<b><?php echo $params['report_for_desc']?></b>&nbsp;</td>
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
                            <th width="05%" align="left"  class="py-3 px-2">&nbsp;Matter</th>
                            <th width="01%">&nbsp;</th>
                            <th width="40%" align="left"  class="py-3 px-2">&nbsp;Matter Description</th>
                            <th width="01%">&nbsp;</th>
                            <th width="05%" align="right" class="py-3 px-2">&nbsp;Initial&nbsp;</th>
                            <th width="01%">&nbsp;</th>
                            <th width="05%" align="right" class="py-3 px-2">&nbsp;No(s)&nbsp;</th>
                            <th width="01%">&nbsp;</th>
                            <th width="12%" align="right" class="py-3 px-2">&nbsp;Billed&nbsp;</th>
                            <th width="01%">&nbsp;</th>
                            <th width="12%" align="right" class="py-3 px-2">&nbsp;Realised&nbsp;</th>
                            <th width="01%">&nbsp;</th>
                            <th width="15%" align="right" class="py-3 px-2">&nbsp;Last Bill&nbsp;</th>
                        </tr>
            <?php
                        $lineno = 9 ;
                        $pcourtind = 'Y' ; 
                    }
            ?>
                                    <?php if($pcourtind == 'Y') { ?> 
                                    <tr class="fs-14">
                                        <td align="left" class="p-2" colspan="13"><b>COURT : <?php echo $pcourtnm ?></b></td> 
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="left" class="p-2" colspan="13">&nbsp;</td> 
                                    </tr>
                                    <?php $pcourtind = 'N' ; $lineno = $lineno + 2 ; } ?>
                                    <tr class="fs-14">
                                        <td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['matter_code'] ?></td> 
                                        <td>&nbsp;</td>
                                        <td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['matter_desc']?></td>
                                        <td>&nbsp;</td>
                                        <td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['initial_code']?></td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['billnos'] > 0) { echo $report_row['billnos'] ; }?>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['billamt'] > 0) { echo $report_row['billamt'] ; }?>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['realamt'] > 0) { echo $report_row['realamt'] ; }?>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td align="right" class="p-2" style="vertical-align:top"><?php echo date_conv($report_row['billdate']) ; ?>&nbsp;</td>
                                    </tr>
            <?php     
                    $lineno     = $lineno + 1 ; 
                    $ctbilnos   = $ctbilnos + $report_row['billnos'] ;
                    $ctbilamt   = $ctbilamt + $report_row['billamt'] ;
                    $ctcolamt   = $ctcolamt + $report_row['realamt'] ;
                    $report_row = ($rowcnt < $report_cnt) ? $bill_qry[$rowcnt] : $report_row;  
                    $rowcnt = $rowcnt + 1 ;
                }  
            ?>
                                    <tr class="fs-14">
                                        <td align="right"  class="p-2" colspan="3" style="background-color: #e2e6506e;"><b>Total :</b></td> 
                                        <td align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                        <td align="right" class="p-2" style="background-color: #e2e6506e;">&nbsp;<b><?php if($ctbilnos > 0) { echo $ctbilnos ; } ?></b>&nbsp;</td>
                                        <td align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                        <td align="right" class="p-2" style="background-color: #e2e6506e;">&nbsp;<b><?php if($ctbilamt > 0) { echo number_format($ctbilamt,2,'.','') ; } ?></b>&nbsp;</td>
                                        <td align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                        <td align="right" class="p-2" style="background-color: #e2e6506e;">&nbsp;<b><?php if($ctcolamt > 0) { echo number_format($ctcolamt,2,'.','') ; } ?></b>&nbsp;</td>
                                        <td align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                        <td align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                        <td align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                        <td align="left"  class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                    </tr>
            <?php
                    $lineno     = $lineno + 1 ; 
                    $gtbilnos   = $gtbilnos + $ctbilnos ;
                    $gtbilamt   = $gtbilamt + $ctbilamt ;
                    $gtcolamt   = $gtcolamt + $ctcolamt ;
                }
            ?>
                                    <tr class="fs-14">
                                        <td colspan="5">&nbsp;</td> 
                                    </tr>
                                    <tr class="fs-14">
                                        <td align="right"  class="p-2" colspan="3" style="background-color: #91d6ec6e;">&nbsp;<b>GRAND TOTAL</b></td> 
                                        <td align="left"    class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                        <td align="right"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;<b><?php if($gtbilnos > 0) { echo $gtbilnos ; } ?></b>&nbsp;</td>
                                        <td align="left"    class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                        <td align="right"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;<b><?php if($gtbilamt > 0) { echo number_format($gtbilamt,2,'.','') ; } ?></b>&nbsp;</td>
                                        <td align="left"    class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                        <td align="right"   class="p-2" style="background-color: #91d6ec6e;">&nbsp;<b><?php if($gtcolamt > 0) { echo number_format($gtcolamt,2,'.','') ; } ?></b>&nbsp;</td>
                                        <td align="left"    class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                        <td align="left"    class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                        <td align="left"    class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                        <td align="left"    class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
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
        console.log(document.billingCountSummary);
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.billingCountSummary.start_date.value.substring(6,10)+document.billingCountSummary.start_date.value.substring(3,5)+document.billingCountSummary.start_date.value.substring(0,2) > document.billingCountSummary.end_date.value.substring(6,10)+document.billingCountSummary.end_date.value.substring(3,5)+document.billingCountSummary.end_date.value.substring(0,2)) {
            Swal.fire({ text: 'End Date must be greater than or equal to Start Date' }).then((result) => { setTimeout(() => {document.billingCountSummary.end_date.focus()}, 500) });
            return false;
        } else if (document.billingCountSummary.end_date.value.substring(6,10)+document.billingCountSummary.end_date.value.substring(3,5)+document.billingCountSummary.end_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'End Date must be less than or equal to Today !!!' }).then((result) => { setTimeout(() => {document.billingCountSummary.end_date.focus()}, 500) });
            return false;
        }
        
        document.billingCountSummary.submit();
    }
</script>
<?= $this->endSection() ?>