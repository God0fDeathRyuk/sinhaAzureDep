<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($sele_qry))) { ?> 
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="pagetitle">
        <h1>Sub Ledger</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="subLedger" name="subLedger">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">				
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Date From <strong class="text-danger">*</strong> </label>					
                            <input type="text" class="form-control w-100 float-start datepicker" name="date_from" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required>
                        </div>
                        <div class="col-md-2 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Date To <strong class="text-danger">*</strong> </label>					
                            <input type="text" class="form-control w-100 float-start datepicker" name="date_to" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required>
                        </div>
                        <div class="col-md-2 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong> </label>
                            <select class="form-select" name="branch_code" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-5 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Main A/c <strong class="text-danger">*</strong> </label>					
                            <select class="form-select" name="main_ac" onchange="changeMainAcCode(this)" required >
                                <option value="All">All</option>
                                <?php foreach($sele_main_qry as $row) { ?>
                                    <option value="<?php echo $row['main_ac_code']?>" <?= ($main_ac == $row['main_ac_code']) ? 'selected' : '' ?>><?php echo $row['main_ac_desc'].' ['.$row['main_ac_code'].']';?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Sub A/c <strong class="text-danger">*</strong> </label>					
                            <select class="form-select" name="main_ac_sub_ac" required >
                                <option value="All">All</option>
                                <?php foreach($sele_sub_qry as $row1) { ?>
                                    <option value="<?php echo $row1['main_ac_code'].'|'.$row1['sub_ac_code']?>"><?php echo $row1['sub_ac_desc'].' ['.$row1['sub_ac_code'].']';?></option>
                                <?php } ?>
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
                        
                        <div class="col-md-3 float-start mt-20">
                            <button type="submit" class="btn btn-primary cstmBtn mt-2">Proceed</button>				
                            <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-1">Reset</button>
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
                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
                    <a href="<?= base_url($params['requested_url']) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
                <?php } else { ?> 
                    <button onclick="window.close()" class="text-decoration-none d-block float-start btn btn-dark">Close</button>
                <?php } } ?>
            <?php endif; ?>
        </div>

        <?php
            $lno = 0 ;
            $page = 0 ;
            $cnt = 1 ;
            $balance_print = 'Yes';
            $transrow = isset($sele_qry[$cnt-1]) ? $sele_qry[$cnt-1] : '' ;
            $count = $params['count'] ;
            while($cnt <= $count)
            {

            $dr_clo_bal = 0;
            $cr_clo_bal = 0;
            $gt_dr_amt = 0;
            $gt_cr_amt = 0;
            $dr_open_bal = 0;
            $cr_open_bal = 0;
            $t_open_bal = 0;
            $t_clo_bal = 0;
            $t_amt = 0;
            $opening_balance = 0 ;
            $t_dr_amt = 0;
            $t_cr_amt = 0;
            $pmaccode = $transrow['main_ac_code'] ;
            $psaccode = $transrow['sub_ac_code'] ;
            $pmacind = 'Y' ;
            $psacind = 'Y' ;

            while($pmaccode == $transrow['main_ac_code'] && $cnt <= $count)
            {
                $balance_amt = 0.00 ;
                $psaccode = $transrow['sub_ac_code'] ;
                $psubind = 'Y' ;
                while($psaccode == $transrow['sub_ac_code'] && $cnt <= $count)
                {
                $p_rec = $transrow['rec_code'] ;
                if($lno == 0 || $lno >= $params['lines_per_page'] || $pmacind == 'Y')
                {
                    $page = $page + 1 ;
                    if($lno != 0)
                    {
                    if($lno >= $params['lines_per_page'] || $pmacind == 'Y')
                    {
                        $pmacind = 'Y' ;
            ?>
                            </table>
                            </td>
                        </tr>
                        </table>	   	 		   
                        <BR CLASS="pageEnd">
            <?php			 
                    }
                    }
            ?>
                    <table width="950" border="0" cellpadding="0" cellspacing="0" class="table border-0">
                    <tr>
                        <td colspan="24">			
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                            <td colspan="3">
                            <table width="100%" cellspacing="0" cellpadding="0" valign="top">
                                <tr>
                                    <td colspan="4" align="center" style="font-family: Arial, Helvetica, sans-serif; font-weight : bold;font-size: 14px; color: #000000;"><?php echo 'SINHA AND COMPANY' ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="center">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="center" style="font-family: Arial, Helvetica, sans-serif; font-weight : bold;font-size: 14px; color: #000000;"><?php echo $params['display_heading']; ?></td>
                                </tr>
                                <tr>
                                    <td align="left"  width="05%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;Branch</td>
                                    <td align="left"  width="70%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo $params['branch_name']; ?></td>
                                    <td align="right" width="10%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">Date&nbsp;</td>
                                    <td align="left"  width="15%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo date('d-m-Y'); ?></td>
                                </tr>
                                <tr>
                                    <td align="left"  style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;Period</td>
                                    <td align="left"  style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo $params['period_desc']; ?></td>
                                    <td align="right" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">Page&nbsp;</td>
                                    <td align="left"  style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo $page; ?></td>
                                </tr>
                                <tr><td colspan="4"><hr width="100%" color="#000000" size="1"></td></tr>
                                </table>         
                            </td>
                            </tr>
            <?php	              
                            if($pmacind == 'Y')				  
                            {
                                $pmacind = 'N' ;
            ?>
                                <tr class="p-2">
                                    <td align="left" colspan="3"><font size="-1"><?php echo $transrow['main_ac_code']?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $transrow['main_ac_desc']?></font></td>
                                </tr>
            <?php
                            }
            ?>				   				  
                            </table>
                        </td>
                        </tr>
                        <tr class="fs-14">
                            <th width="" align="left" class="py-3 px-2">A/c</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="left" class="py-3 px-2">DB</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="right" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2" align="left">Doc Date</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2" align="left">Doc No</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2" align="left">Instr No</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2" align="left">Date</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2" align="left">Brn</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2" align="left">Narration</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2" align="right">Debit</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2" align="right">Credit</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" class="py-3 px-2" align="right"><?php if($balance_print=='Yes'){?>Balance<?php }?>&nbsp;</th>
                            <?php if ($renderFlag) : ?>
                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
                            <?php endif; ?>
                        </tr>
            <?php
                            $lno = 9 ;
                }
                if($psubind == 'Y' || $lno == 9)
                {
                    $psubind = 'N';
                    $sub_ac_code = $transrow['sub_ac_code'] ;
                    $sub_ac_desc = $transrow['sub_ac_desc'] ;
            ?>
                    <tr class="fs-14">
                        <td class="p-2" align="left" style="vertical-align:top;" colspan="3"><?php echo $sub_ac_code?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;" colspan="20"><?php echo $sub_ac_desc?></td>
                    </tr>
            <?php
                }
                else
                { 
                    $sub_ac_code = '' ;
                    $sub_ac_desc = '' ;
                }
            ?>	   	   
                    <tr class="fs-14">
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['daybook_code']?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['doc_type']?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo date_conv($transrow['doc_date'])?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['doc_no']?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['instrument_no']?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php if($transrow['instrument_dt']!='0000-00-00') echo date_conv($transrow['instrument_dt'])?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['branch_abbr_name']?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['narration']?></td>
                        <td class="p-2">&nbsp;</td>
            <?php
                    if($transrow['dr_cr_ind'] == 'D')
                    {
                    $t_dr_amt    = $t_dr_amt + $transrow['amount_dr'] ;
                    $gt_dr_amt   = $gt_dr_amt + $transrow['amount_dr'] ;		   
                    $balance_amt = $balance_amt + $transrow['amount_dr'] ;
            ?>
                        <td class="p-2" align="right" style="vertical-align:top;"><?php echo number_format($transrow['amount_dr'],2,'.','')?></td>		 		 
                        <td class="p-2" colspan="2">&nbsp;</td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;"><?php if($balance_print=='Yes') echo number_format($balance_amt,2,'.','');?></td>
            <?php  
                    }
                    if($transrow['dr_cr_ind'] == 'C')
                    {
                    $t_cr_amt    = $t_cr_amt + $transrow['amount_cr'] ;
                    $gt_cr_amt   = $gt_cr_amt + $transrow['amount_cr'];		  
                    $balance_amt = $balance_amt - $transrow['amount_cr'] ;
            ?>		 
                        <td class="p-2" colspan="2">&nbsp;</td>
                        <td class="p-2"  align="right" style="vertical-align:top;"><?php echo number_format($transrow['amount_cr'],2,'.','')?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;"><?php if($balance_print=='Yes') echo number_format($balance_amt,2,'.','');?></td>
            <?php
                    }
                    if($transrow['dr_cr_ind'] == 'S')
                    {
                    $balance_amt = $transrow['amount_dr'] ; 
                    if($transrow['amount_dr'] > 0 )
                    {
                        $t_dr_amt = $t_dr_amt + $transrow['amount_dr'] ; 
                        $dr_open_bal = $dr_open_bal + $transrow['amount_dr'];		  
            ?>		 
                        <td class="p-2"  align="right" style="vertical-align:top;"><?php echo number_format($transrow['amount_dr'],2,'.','')?></td>
                        <td class="p-2" colspan="2">&nbsp;</td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;"><?php if($balance_print=='Yes') echo number_format($balance_amt,2,'.','');?></td>
            <?php			 
                        }
                    if($transrow['amount_dr'] < 0 )
                    {
                        $t_cr_amt = $t_cr_amt + abs($transrow['amount_dr']) ;
                        $cr_open_bal = $cr_open_bal + $transrow['amount_dr'];		  
            ?>		 
                        <td class="p-2" colspan="2">&nbsp;</td>
                        <td class="p-2"  align="right" style="vertical-align:top;"><?php echo number_format(abs($transrow['amount_dr']),2,'.','')?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;"><?php if($balance_print=='Yes') echo number_format($balance_amt,2,'.','');?></td>
            <?php			 
                        }
                    if($transrow['amount_dr'] == 0.00 )
                    {
            ?>		 
                        <td class="p-2" colspan="2">&nbsp;</td>
                        <td class="p-2"  align="right" style="vertical-align:top;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;"><?php if($balance_print=='Yes') echo number_format($balance_amt,2,'.','');?></td>
            <?php			 
                        }	
                    }
            ?>		  	
                    <?php if($transrow['doc_no'] != '') { ?>
                        <?php if ($renderFlag) : ?>
                            <?php if(isset($showActionBtns)) { if($showActionBtns) { ?>   
                                <td height="20" align="left" class="p-2" >

                                    <form action="" method="post" target="_blank" name="actionForm<?= $cnt ?>">
                                        <input type="hidden" name="serial_no" value="<?= $transrow['serial_no'] ?>">
                                        <input type="hidden" name="output_type" value="">
                                        <button type="button" class="me-1 border-0 p-0" title="View Report" onclick="setOutputType('innerReport', <?= $cnt ?>)">
                                    <i class="fa-solid fa-eye edit"></i>
                                </button>
                                <button type="button" class="me-1 border-0 p-0" class="me-1" title="Download Excel" onclick="setOutputType('innerExcel', <?= $cnt ?>)">
                                    <i class="fa-solid fa-file-excel edit"></i>
                                </button>
                                <button type="button" class="me-1 border-0 p-0" class="me-1" title="Download PDF" onclick="setOutputType('innerPdf', <?= $cnt ?>)">
                                    <i class="fa-solid fa-file-pdf edit"></i>
                                </button>											
                                    </form>
                                    <script>
                                        function setOutputType(type, no) {
                                            document['actionForm'+no].output_type.value = type;
                                            document['actionForm'+no].submit();
                                        }
                                    </script>
                                </td>
                            <?php } } ?>
                        <?php endif; ?>
                    <?php } ?>
                </tr>	   
            <?php	   
                    $lno = $lno + 1 ;
                    $transrow = ($cnt < $count) ? $sele_qry[$cnt] : $transrow;  
                    $cnt = $cnt + 1 ;
                    $t_amt = $t_dr_amt - $t_cr_amt ;
                }			   		   
                $psaccode = $transrow['sub_ac_code'] ;
                $psacind = 'Y' ;
                $opening_balance = 0 ;

                if($t_dr_amt != 0 || $t_cr_amt != 0)
                {
            ?>
                <tr class="fs-14">
                    <td class="p-2" colspan="24" class="py-1">&nbsp;</td>
                </tr>
            <?php
                $lno = $lno + 1 ;
            ?>
                <tr class="fs-14">
                    <td class="p-2" colspan="16" style="background-color:#eff3b1;">&nbsp;</td>
                    <td class="p-2" align="left" style="background-color:#eff3b1;">Total</td>
                    <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <?php
                    if($t_dr_amt != 0)
                    {
            ?>
                    <td class="p-2" align="right" style="background-color:#eff3b1;"><?php echo number_format($t_dr_amt,2,'.','')?></td>
                    <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <?php
                    $t_dr_amt = 0 ;
                    }
                    else
                    {
            ?>
                    <td class="p-2" align="right" style="background-color:#eff3b1;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <?php
                    }
                    if($t_cr_amt != 0)
                    {
            ?>
                        <td class="p-2"  align="right" style="background-color:#eff3b1;"><?php echo number_format($t_cr_amt,2,'.','')?></td>
            <?php
                    }
                    else
                    {
            ?>
                        <td class="p-2"  align="right" style="background-color:#eff3b1;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <?php
                        }
            ?>
                <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                </tr>
            <?php
                $lno = $lno + 1 ;
                }
            ?>	  
            <tr class="fs-14">
                <td class="p-2" colspan="24" class="py-1">&nbsp;</td>
            </tr>
                <tr class="fs-14">
                    <td class="p-2" colspan="16" style="background-color:#bee9f7;">&nbsp;</td>
                    <td class="p-2" align="left" style="background-color:#bee9f7;">Closing Balance</td>
                    <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
            <?php
                    if($t_amt > 0)
                    {
                    $dr_clo_bal = $dr_clo_bal + $t_amt ;
            ?>
                    <td class="p-2" align="right" style="background-color:#bee9f7;"><?php echo number_format($t_amt,2,'.','')?></td>
                    <td class="p-2" colspan="2" style="background-color:#bee9f7;">&nbsp;</td>
            <?php
                    }
                    if($t_amt < 0)
                    {
                    $cr_clo_bal = $cr_clo_bal + $t_amt ;
            ?>
                    <td class="p-2" colspan="2" style="background-color:#bee9f7;">&nbsp;</td>
                    <td class="p-2" align="right" style="background-color:#bee9f7;"><?php echo number_format(abs($t_amt),2,'.','')?></td>
            <?php
                    }
                    if($t_amt == 0)
                    {
            ?>
                        <td class="p-2" colspan="2" style="background-color:#bee9f7;">&nbsp;</td>
                        <td class="p-2"  align="right" style="background-color:#bee9f7;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <?php
                    }
            ?>
                <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                </tr>
            <?php
                $lno = $lno + 1 ;
            ?>
                <tr class="fs-14">
                    <td class="p-2" colspan="24" class="py-1">&nbsp;</td>
                </tr>
            <?php
                $lno = $lno + 1 ;
                $t_dr_amt = 0;
                $t_cr_amt = 0;
                $t_amt = 0 ;
            }
                $pmaccode = $transrow['main_ac_code'] ;
                $pmacind = 'Y' ;
            ?>
            <tr class="fs-14">
                <td class="p-2" colspan="7" style="background-color:#eff3b1;"></td>
                <td class="p-2" colspan="9" align="right" style="background-color:#eff3b1;">Grand Total</td>
                <td class="p-2" align="left" style="background-color:#eff3b1;">Opening Balance</td>
                <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <?php
                if($dr_open_bal != 0)
                {
            ?>
                <td class="p-2" align="right" style="background-color:#eff3b1;"><?php echo number_format($dr_open_bal,2,'.','')?></td>
            <?php
                }
                else
                {
            ?>
                <td class="p-2" align="right" style="background-color:#eff3b1;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <?php
                }
            ?>
                <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <?php
                if($cr_open_bal != 0)
                {
            ?>
                <td class="p-2" align="right" style="background-color:#eff3b1;"><?php echo number_format(abs($cr_open_bal),2,'.','')?></td>
            <?php
                }
                else
                {
            ?>
                <td class="p-2" align="right" style="background-color:#eff3b1;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <?php
                }
            ?>
            <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            </tr>		
            <?php
            $lno = $lno + 1 ;
            ?>
            <tr class="fs-14">
                <td class="p-2" colspan="24">&nbsp;</td>
            </tr>
            <tr class="fs-14">
                <td class="p-2" colspan="16" style="background-color:#bee9f7;">&nbsp;</td>
                <td class="p-2" align="left" style="background-color:#bee9f7;">Transaction</td>
                <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
            <?php
                if($gt_dr_amt != 0)
                {
            ?>
                <td class="p-2" align="right" style="background-color:#bee9f7;"><?php echo number_format($gt_dr_amt,2,'.','')?></td>
            <?php
                }
                else
                {
            ?>
                <td class="p-2" align="right" style="background-color:#bee9f7;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <?php
                }
            ?>
                <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
            <?php
                if($gt_cr_amt != 0)
                {
            ?>
                <td class="p-2" align="right" style="background-color:#bee9f7;"><?php echo number_format($gt_cr_amt,2,'.','')?></td>
            <?php
                }
                else
                {
            ?>
                <td class="p-2" align="right" style="background-color:#bee9f7;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <?php
                }
            ?>
                <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
            </tr>		
            <?php
            $lno = $lno + 1 ;
            $closing_balance = $dr_clo_bal - abs($cr_clo_bal) ;
            ?>
            <tr class="fs-14">
                <td class="p-2" colspan="24">&nbsp;</td>
            </tr>
            <tr class="fs-14">
                <td class="p-2" colspan="16" style="background-color:#eff3b1;">&nbsp;</td>
                <td class="p-2" align="left" style="background-color:#eff3b1;">Closing Balance</td>
                <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <?php
                if($closing_balance > 0)
                {
            ?>
                <td class="p-2" align="right" style="background-color:#eff3b1;"><?php echo number_format($closing_balance,2,'.','')?></td>
            <?php
                }
                else
                {
            ?>
                <td class="p-2" align="right" style="background-color:#eff3b1;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <?php
                }
            ?>
                <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <?php
                if($closing_balance <= 0)
                {
            ?>
                <td class="p-2" align="right" style="background-color:#eff3b1;"><?php echo number_format(abs($closing_balance),2,'.','')?></td>
            <?php
                }
                else
                {
            ?>
                <td class="p-2" align="right" style="background-color:#eff3b1;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <?php
                }
            ?>
            <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
            </tr>
            <?php
            $lno = $lno + 2 ;

            }  
            ?>
                </table>
                </td>
            </tr>
            </table>
    </main>
<?php } ?>
<script>
    function changeMainAcCode(e) { 
        let main_ac = document.subLedger.main_ac.value;
        //$main_ac_sub_ac = document.subLedger.main_ac_sub_ac.value;
        let date_from = document.subLedger.date_from.value;
        let date_to = document.subLedger.date_to.value;
        let branch_code = document.subLedger.branch_code.value;

        let url = `${window.location.origin + window.location.pathname}?mode=Main&main_ac=${main_ac}&main_ac_sub_ac=&date_from=${date_from}&date_to=${date_to}&branch_code=${branch_code}&display_heading=Sub Ledger&acc_mode=Y`;
        console.log(url.toString());
        location.replace(url);
        // http://localhost/sinhaco/finance/sub-ledger?mode=Main&main_ac=3530&main_ac_sub_ac=&date_from=01-04-2023&date_to=16-01-2024&branch_code=B001&display_heading=Sub
    }
</script>
<?= $this->endSection() ?>