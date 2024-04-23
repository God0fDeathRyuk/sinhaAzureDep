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

    <div class="pagetitle col-md-12 float-start border-bottom pb-1">
    <h1>O/s Bill (Summary) </h1>
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
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Billing Period</label>
                    <span class="float-start mt-2">From</span>
                    <input type="text" class="form-control float-start w-40 ms-2 set-date datepicker" id="" placeholder="dd-mm-yyyy" name="start_date" value="<?= $curr_fyrsdt ?>" onBlur="make_date(this)"/>
                    <span class="float-start mt-2 ms-2">To</span>
                    <input type="text" class="form-control float-start w-40 ms-2 set-date datepicker withdate" id="" placeholder="dd-mm-yyyy" name="end_date" onBlur="make_date(this)"/>
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
					<i class="fa-solid fa-binoculars icn-vw" onclick="showData('matter_code', 'display_id=<?= $displayId['matter_help_id'] ?>&client_code=@clientCode', 'matterCode', ['matterDesc'], ['mat_des'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
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
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Reference Type</label>
                    <select class="form-select" name="reference_type">
                    <option value="%">All</option>
                    <?php foreach($reftyp_qry as $reftyp_row) { ?>
                    <option value="<?php echo $reftyp_row['code_code'];?>"><?php echo $reftyp_row['code_desc'];?></option>
                    <?php } ?>
                    </select>
                </div> 
                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Bill Status</label>
                    <select class="form-select" name="collectable_ind">
                    <option value="%">All</option>
                    <option value="C">Collectable</option>
                    <option value="D">Doubtful</option>
                    <option value="B">Bad</option>
                    </select>
                </div>
                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">Report Seq</label>
                    <select class="form-select" name="report_seqn">
                    <option value="C">Client wise</option>
                    <option value="M">Matter wise</option>
                    <option value="I">Initial wise</option>
                    </select>
                </div>
                <div class="col-md-3 float-start px-2 mb-3">
                    <label class="d-inline-block w-100 mb-2 lbl-mn">O/s Order</label>
                    <select class="form-select" name="os_order">
                    <option value="A">O/s Ascending</option>
                    <option value="D">O/s Descending</option>
                    <option value="N">Normal</option>
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
				<?php if ($renderFlag) : ?>
                    <a href="<?= $params['requested_url'] ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
				<?php endif; ?>
			</div>

        <?php if($params['report_seqn'] == 'C'){ 
                $maxline = 75 ;
                $lineno  = 0 ;
                $pageno  = 0 ;
                $tbilamt = 0; 
                $tcolamt = 0; 
                $tbalamt = 0; 
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
                <table class="table border-0 px-2" align="center" style="background-color:#f6f9ff;" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="border-0 pb-0" colspan="5">    
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
                                    <td class="report_label_text">&nbsp;Branch</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
                                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">&nbsp;Period</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
                                </tr>
                                <tr>
                                    <td class="report_label_text">&nbsp;Reference</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['reference_desc'] ?></b></td>
                                    <td class="report_label_text" align="right">Seqn&nbsp;&nbsp;</td>
                                    <td class="report_label_text">&nbsp;:&nbsp;<b>[ Client ]</b></td>
                                </tr>
                            </table>
                        </td>    
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr class="fs-14">
                        <th height="" width="" align="left"  class="py-3 px-2">&nbsp;<b>Code</b></th>
                        <th height="" width="" align="left"  class="py-3 px-2">&nbsp;<b>Name</b></th>
                        <th height="" width="" align="right" class="py-3 px-2"><b>Billed</b>&nbsp;</th>
                        <th height="" width="" align="right" class="py-3 px-2"><b>Realised</b>&nbsp;</th>
                        <th height="" width="" align="right" class="py-3 px-2"><b>Balance</b>&nbsp;</th>
                    </tr>
                <?php
                            $lineno = 9 ;
                        }
                ?>
                                <tr class="fs-14 border-0">
                                    <td align="left"  class="p-2">&nbsp;<?php echo $report_row['client_code']?></td> 
                                    <td align="left"  class="p-2" >&nbsp;<?php echo strtoupper($report_row['client_name'])?></td>
                                    <td align="right" class="p-2" ><?php if($report_row['billed_amount']   > 0) { echo number_format($report_row['billed_amount'],  2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="p-2" ><?php if($report_row['realised_amount'] > 0) { echo number_format($report_row['realised_amount'],2,'.',''); }?>&nbsp;</td>
                                    <td align="right" class="p-2" ><?php if($report_row['balance_amount']  > 0) { echo number_format($report_row['balance_amount'], 2,'.',''); }?>&nbsp;</td>
                                </tr>
                <?php     
                        $lineno  = $lineno + 1;
                        $tbilamt = $tbilamt + $report_row['billed_amount']   ;
                        $tcolamt = $tcolamt + $report_row['realised_amount'] ;
                        $tbalamt = $tbalamt + $report_row['balance_amount']  ;
                        //
                        $rowcnt = $rowcnt + 1 ;
                    }  
                ?>                   
                                <tr class="fs-14 border-0">
                                    <td height="" align="center" style="background-color: #e2e6506e;" class="p-2" colspan="2"><b>GRAND TOTAL</b>&nbsp;</td>
                                    <td height="" align="right" style="background-color: #e2e6506e;"  class="p-2" ><b><?php if($tbilamt > 0) { echo number_format($tbilamt,2,'.','') ;}?></b>&nbsp;</td>
                                    <td height="" align="right" style="background-color: #e2e6506e;"  class="p-2" ><b><?php if($tcolamt > 0) { echo number_format($tcolamt,2,'.','') ;}?></b>&nbsp;</td>
                                    <td height="" align="right" style="background-color: #e2e6506e;"  class="p-2"><b><?php if($tbalamt > 0) { echo number_format($tbalamt,2,'.','') ;}?></b>&nbsp;</td>
                                </tr>
                        </table>
                        </td>
                    </tr>
                </table>
        <?php } ?> 
        <?php if($params['report_seqn'] == 'M'){    
            $maxline = 38 ;
            $lineno  = 0 ;
            $pageno  = 0 ;
            $tbilamt = 0; 
            $tcolamt = 0; 
            $tbalamt = 0;  
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
           <table class="table border-0 px-2" align="center" border="0" cellspacing="0" cellpadding="0" style="background-color:#f6f9ff;">
               <tr>
                  <td class="border-0 pb-0" colspan="5">    
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
		                   <td class="report_label_text">&nbsp;Branch</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
		                   <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
		                </tr>
                        <tr>
		                   <td class="report_label_text">&nbsp;Period</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
		                   <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
		                </tr>
                        <tr>
		                   <td class="report_label_text">&nbsp;Reference</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['reference_desc'] ?></b></td>
		                   <td class="report_label_text" align="right">Seqn&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b>[ Matter ]</b></td>
		                </tr>
	                 </table>
                  </td>    
               </tr>
               <tr><td>&nbsp;</td></tr>
               <tr class="fs-14">
                   <th height="" width="" align="left"  class="py-3 px-2">&nbsp;<b>Matter</b></th>
                   <th height="" width="" align="left"  class="py-3 px-2">&nbsp;<b>Matter Desc</b></th>
                   <th height="" width="" align="right" class="py-3 px-2"><b>Billed</b>&nbsp;</th>
                   <th height="" width="" align="right" class="py-3 px-2"><b>Realised</b>&nbsp;</th>
                   <th height="" width="" align="right" class="py-3 px-2"><b>Balance</b>&nbsp;</th>
                </tr>
            <?php
                        $lineno = 9 ;
                    }
            ?>
                        <tr class="fs-14 border-0">
                           <td align="left"  class="p-2" style="vertical-align:top">&nbsp;<?php echo $report_row['matter_code']?></td> 
                           <td align="left"  class="p-2"  style="vertical-align:top; padding-left:2px"><font size="1"><?php echo strtoupper($report_row['matter_desc'])?></font></td>
                           <td align="right" class="p-2"  style="vertical-align:top"><?php if($report_row['billed_amount']   > 0) { echo number_format($report_row['billed_amount'],  2,'.',''); } ?>&nbsp;</td>
                           <td align="right" class="p-2"  style="vertical-align:top"><?php if($report_row['realised_amount'] > 0) { echo number_format($report_row['realised_amount'],2,'.',''); } ?>&nbsp;</td>
                           <td align="right" class="p-2"  style="vertical-align:top"><?php if($report_row['balance_amount']  > 0) { echo number_format($report_row['balance_amount'], 2,'.',''); } ?>&nbsp;</td>
                        </tr>
            <?php     
                    $lineno = $lineno + 1;
                    $tbilamt = $tbilamt + $report_row['billed_amount']   ;
                    $tcolamt = $tcolamt + $report_row['realised_amount'] ;
                    $tbalamt = $tbalamt + $report_row['balance_amount']  ;
                    
                    $rowcnt = $rowcnt + 1 ;
                }  
            ?>                   
                        <tr class="fs-14 border-0">
                           <td align="center" style="background-color: #e2e6506e;" class="p-2" colspan="2"><b>GRAND TOTAL</b>&nbsp;</td>
                           <td align="right" style="background-color: #e2e6506e;" class="p-2"><b><?php if($tbilamt > 0) { echo number_format($tbilamt,2,'.','') ;}?></b>&nbsp;</td>
                           <td align="right" style="background-color: #e2e6506e;" class="p-2"><b><?php if($tcolamt > 0) { echo number_format($tcolamt,2,'.','') ;}?></b>&nbsp;</td>
                           <td align="right" style="background-color: #e2e6506e;" class="p-2"><b><?php if($tbalamt > 0) { echo number_format($tbalamt,2,'.','') ;}?></b>&nbsp;</td>
                        </tr>
                   </table>
                </td>
 	         </tr>
           </table> 
        <?php } ?>
        <?php if($params['report_seqn'] == 'I'){
            $maxline = 50 ;
            $lineno  = 0 ;
            $pageno  = 0 ;
            $tbilamt = 0; 
            $tcolamt = 0; 
            $tbalamt = 0;  
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
           <table class="table border-0 px-2" align="center" border="0" cellspacing="0" cellpadding="0" style="background-color:#f6f9ff;">
               <tr>
                  <td class="border-0 pb-0" colspan="5">     
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
		                   <td class="report_label_text">&nbsp;Branch</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['branch_name']?></b></td>
		                   <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<?= $params['date']?></td>
		                </tr>
                        <tr>
		                   <td class="report_label_text">&nbsp;Period</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['period_desc'] ?></b></td>
		                   <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<?= $pageno?></td>
		                </tr>
                        <tr>
		                   <td class="report_label_text">&nbsp;Reference</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b><?= $params['reference_desc'] ?></b></td>
		                   <td class="report_label_text" align="right">Seqn&nbsp;&nbsp;</td>
		                   <td class="report_label_text">&nbsp;:&nbsp;<b>[ Client ]</b></td>
		                </tr>
	                 </table>
                  </td>    
               </tr>
               <tr><td>&nbsp;</td></tr>
               <tr class="fs-14">
                   <th height="18" width="06%" align="left"  class="py-3 px-2">&nbsp;<b>Code</b></th>
                   <th height="18" width="55%" align="left"  class="py-3 px-2">&nbsp;<b>Name</b></th>
                   <th height="18" width="13%" align="right" class="py-3 px-2"><b>Billed</b>&nbsp;</th>
                   <th height="18" width="13%" align="right" class="py-3 px-2"><b>Realised</b>&nbsp;</th>
                   <th height="18" width="13%" align="right" class="py-3 px-2"><b>Balance</b>&nbsp;</th>
                </tr>
               
            <?php
                        $lineno = 9 ;
                    }
            ?>
                        <tr class="fs-14 border-0">
                           <td align="left"  class="p-2">&nbsp;<?php echo $report_row['initial_code']?></td> 
                           <td align="left"  class="p-2">&nbsp;<?php echo strtoupper($report_row['initial_name'])?></td>
                           <td align="right" class="p-2"><?php if($report_row['billed_amount']   > 0) { echo number_format($report_row['billed_amount'],  2,'.',''); }?>&nbsp;</td>
                           <td align="right" class="p-2"><?php if($report_row['realised_amount'] > 0) { echo number_format($report_row['realised_amount'],2,'.',''); }?>&nbsp;</td>
                           <td align="right" class="p-2"><?php if($report_row['balance_amount']  > 0) { echo number_format($report_row['balance_amount'], 2,'.',''); }?>&nbsp;</td>
                        </tr>
            <?php     
                    $lineno  = $lineno + 1;
                    $tbilamt = $tbilamt + $report_row['billed_amount']   ;
                    $tcolamt = $tcolamt + $report_row['realised_amount'] ;
                    $tbalamt = $tbalamt + $report_row['balance_amount']  ;
                    
                    $rowcnt = $rowcnt + 1 ;
                }  
            ?>                   
                        <tr class="fs-14 border-0">
                           <td align="center" style="background-color: #e2e6506e;" class="p-2" colspan="2"><b> GRAND TOTAL </b>&nbsp;</td>
                           <td align="right" style="background-color: #e2e6506e;" class="p-2"><b><?php if($tbilamt > 0) { echo number_format($tbilamt,2,'.','') ;}?></b>&nbsp;</td>
                           <td align="right" style="background-color: #e2e6506e;" class="p-2"><b><?php if($tcolamt > 0) { echo number_format($tcolamt,2,'.','') ;}?></b>&nbsp;</td>
                           <td align="right" style="background-color: #e2e6506e;" class="p-2"><b><?php if($tbalamt > 0) { echo number_format($tbalamt,2,'.','') ;}?></b>&nbsp;</td>
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