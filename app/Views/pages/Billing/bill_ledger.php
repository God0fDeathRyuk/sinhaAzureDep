<?= $this->extend("layouts/master") ?> 

<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?> 
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if (!isset($report_qry)) { ?> 
 
<main id="main" class="main">

<?php if (session()->getFlashdata('message') !== NULL) : ?>
<div id="alertMsg">
    <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
    <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

    <div class="pagetitle col-md-12 float-start border-bottom pb-1">
    <h1>Bill Ledger </h1>
    </div>

    <form action="" method="post">
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
						<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker" id="" placeholder="dd-mm-yyyy" name="start_date"  value="<?= $curr_fyrsdt ;?>" onBlur="make_date(this)"/>
						<span class="float-start mt-2 ms-2">To</span>
						<input type="text" class="form-control float-start w-48 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="end_date" onBlur="make_date(this)"/>
					</div>
                <div class="col-md-3 float-start px-2 position-relative mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Client Code <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control cstm-inpt" id="clientCode" oninput="this.value = this.value.toUpperCase()" size="05" maxlength="06" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" name="client_code" required/>
                    <i title="View" class="fa-solid fa-binoculars icn-vw" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" data-toggle="modal" data-target="#lookup"></i>
                </div>
                <div class="col-md-6 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Client Name</label>
                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" name="client_name" id="clientName" readonly />
                </div>
                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type</label>
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
				<!--<a href="javascript:void(0);" class="text-decoration-none d-block float-start btn btn-primary me-2">Print</a>-->
				<?php if ($renderFlag) : ?>
                    <a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>
            <?php
                //----- 
                $maxline    = 60 ;
                //$index  = 0;
                $rowcnt     = 1 ;
                $report_row = isset($report_qry[$rowcnt-1]) ? $report_qry[$rowcnt-1] : '' ;  
                while ($rowcnt <= $params['report_cnt'])
                {
                    $lineno     = 0 ;
                    $pageno     = 0 ;
                    $c_opbal    = 0 ;
                    $c_tdramt   = 0 ; 
                    $c_tcramt   = 0 ;
                    $c_tdefamt  = 0 ;
                    $c_clbal    = 0 ;
                    $pclientind = 'Y' ;
                    $pclientcd  = $report_row['client_code'] ;
                    $pclientnm  = $report_row['client_name'] ;
                    while($pclientcd == $report_row['client_code'] && $rowcnt <= $params['report_cnt'])
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
                <table class="table border-0 px-2" style="background-color:#f6f9ff;" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="border-0 pb-0" colspan="8">    
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
                                    <td class="report_label_text">Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $params['date']?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">Client</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $params['client_name']?></b></td>
                                    <td class="report_label_text">&nbsp;</td>
                                    <td class="report_label_text">&nbsp;</td>
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
                    <tr class="fs-14">
                        <th width="10%" align="left"  class="py-3 px-2">Doc Dt</th>
                        <th width="10%" align="left"  class="py-3 px-2">Doc No</th>
                        <th width="07%" align="left"  class="py-3 px-2">Instr No</th>
                        <th width="07%" align="left"  class="py-3 px-2">Instr Dt</th>
                        <th width="36%" align="left"  class="py-3 px-2">Particulars</th>
                        <th width="10%" align="right" class="py-3 px-2">Debit&nbsp;</th>
                        <th width="10%" align="right" class="py-3 px-2">Credit&nbsp;</th>
                        <th width="10%" align="right" class="py-3 px-2">Deficit&nbsp;</th>
                    </tr>
                                
                <?php
                            $lineno     = 8 ;
                            $pclientind = 'Y' ;
                        }
                ?>
                        <?php if($report_row['ind'] == '1') { ?>
                        <tr class="fs-14 border-0">
                           <td align="left"  class="p-2"><?php echo date_conv($report_row["doc_date"])?></td> 
                           <td align="left"  class="p-2">&nbsp;</td> 
                           <td align="left"  class="p-2">&nbsp;</td> 
                           <td align="left"  class="p-2">&nbsp;</td> 
                           <td align="left" class="p-2"><b><?php echo $report_row['narration'] ?></b>&nbsp;</td>
                           <td align="left" class="p-2"><b><?php if($report_row['amount_dr']  >= 0) { echo number_format($report_row['amount_dr'], 2,'.',''); }?></b></td>
                           <td align="left" class="p-2"><b><?php if($report_row['amount_cr']  >  0) { echo number_format($report_row['amount_cr'], 2,'.',''); }?></b></td>
                           <td align="left" class="p-2">&nbsp;</td>
                        </tr>
						<?php $lineno = $lineno + 1; } else { ?>
                        <tr class="fs-14 border-0">
                           <td align="left"  class="p-2" style="vertical-align:top"><?php echo date_conv($report_row['doc_date'])?></td> 
                           <td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['doc_no'] ?></td> 
                           <td align="left"  class="p-2" style="vertical-align:top"><?php echo $report_row['instr_no'] ?></td> 
                           <td align="left"  class="p-2" style="vertical-align:top"><?php if($report_row['instr_dt'] != '' && $report_row['instr_dt'] != '0000-00-00') { echo date_conv($report_row['instr_dt']) ; }?></td> 
                           <td align="left"  class="p-2" style="vertical-align:top" rowspan="2"><?php echo strtoupper($report_row['narration'])?></td>
                           <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['amount_dr']  > 0) { echo number_format($report_row['amount_dr'], 2,'.',''); }?></td>
                           <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['amount_cr']  > 0) { echo number_format($report_row['amount_cr'], 2,'.',''); }?></td>
                           <td align="right" class="p-2" style="vertical-align:top"><?php if($report_row['amount_def'] > 0) { echo number_format($report_row['amount_def'],2,'.',''); }?></td>
                        </tr>
                        <tr class="fs-14 border-0">
                           <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                           <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                           <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                           <td align="left"  class="p-2" style="vertical-align:top">&nbsp;</td> 
                           <td align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                           <td align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                           <td align="right" class="p-2" style="vertical-align:top">&nbsp;</td>
                        </tr>
						<?php $lineno = $lineno + 2; } ?>
                <?php     
                        if ($report_row['ind'] == '1') 
                        {
                            $c_opbal = $report_row['amount_dr'] - $report_row['amount_cr'] ;   
                        }
                        else
                        {
                            if($report_row['amount_dr']  > 0) { $c_tdramt  += $report_row['amount_dr']  ; } 
                            if($report_row['amount_cr']  > 0) { $c_tcramt  += $report_row['amount_cr']  ; }
                            if($report_row['amount_def'] > 0) { $c_tdefamt += $report_row['amount_def'] ; }
                        }
                        //
                        $report_row = ($rowcnt < $params['report_cnt']) ? $report_qry[$rowcnt] : $report_row;
                        //$index++;
                        $rowcnt = $rowcnt + 1 ;
                    }  
                    //
                    $c_clbal = $c_opbal + $c_tdramt - $c_tcramt - $c_tdefamt ;
                ?>
                        <tr class="fs-14 border-0">
						   <td colspan="8">&nbsp;</td>
						</tr>
                        <tr class="fs-14 border-0">
                           <td align="left"  class="p-2" colspan="5"><b>Total</b>&nbsp;</td>
                           <td align="left"  class="p-2"><b><?php echo number_format($c_tdramt, 2,'.','') ;?></b></td>
                           <td align="left"  class="p-2"><b><?php echo number_format($c_tcramt, 2,'.','') ;?></b></td>
                           <td align="left"  class="p-2"><b><?php echo number_format($c_tdefamt,2,'.','') ;?></b></td>
                        </tr>
                        <tr class="fs-14 border-0">
                           <td align="left" style="background-color: #e2e6506e;" class="p-2" colspan="5"><b>Closing Balance</b>&nbsp;</td>
                           <td align="left" style="background-color: #e2e6506e;" class="p-2"><b><?php if($c_clbal >= 0) { echo number_format($c_clbal,2,'.','') ;}?></b></td>
                           <td align="left" style="background-color: #e2e6506e;" class="p-2"><b><?php if($c_clbal <  0) { echo number_format(abs($c_clbal),2,'.','') ;}?></b></td>
                           <td align="left" style="background-color: #e2e6506e;" class="p-2" colspan="5">&nbsp;</td>
                        </tr>
						<!-- <tr class="fs-14 border-0">
						   <td colspan="8"><hr size="1"  class="p-2" noshade></td>
						</tr> -->
                   </table>
                </td>
 	         </tr>
                </table> 
                    <BR CLASS="pageEnd"> 
                <?php
                    $lineno = $lineno + 3;
                    }
                ?>    

        </div>
<?php } ?>
<!-- End #main -->
<?= $this->endSection() ?>