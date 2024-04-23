<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>
<?php if((!isset($sele_qry)) && (!isset($trandtl_qry))) { ?> 
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>General Ledger </h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="acReport5110" name="acReport5110">
            <section class="section dashboard">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Branch</label>
                                <select class="form-select" name="branch_code" required >
                                    <?php foreach($data['branches'] as $branch) { ?>
                                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>				
                            <div class="col-md-2 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Date From</label>					
                                <input type="text" class="form-control float-start datepicker" name="date_from" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required/>
                            </div>
                            <div class="col-md-2 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Date To</label>					
                                <input type="text" class="form-control float-start datepicker" name="date_to" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required/>
                            </div>
                                            
                            <div class="col-md-5 float-start px-1 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Main A/c </label>
                                <select class="form-select w-100 float-start" name="main_ac_code" required>
                                    <option value="%">All</option>
                                    <?php foreach($data['main_ac'] as $main_ac) { ?>
                                        <option value="<?php echo $main_ac['main_ac_code']?>"><?php echo $main_ac['main_ac_desc'].' ['.$main_ac['main_ac_code'].']';?></option>
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
                            <input type="hidden" name="lines_per_page"  value="<?= $data['lines_per_page'] ?>">
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
<?php } else if(!isset($trandtl_qry) && (isset($sele_qry))) { ?>
    <script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>

	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important; background-color:#fff!important;"' : '' ?>>
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
                $grand_trans_dr = 0;
                $grand_trans_cr = 0;
                $grand_clo_balance = 0;
                $grand_op_balance = 0;
                while($cnt <= $count)
                {
                $m_ac_code   = $transrow['main_ac_code'] ;
                $ac_ind      = $transrow['sub_ac_ind'] ;
                $t_dr_amt    = 0 ;
                $t_cr_amt    = 0 ;
                $t_clo_amt   = 0 ;
                $balance_amt = 0.00 ;
                
                //loop to check the break of main account code
                $pmccind = 'Y';
                while($m_ac_code == $transrow['main_ac_code'] && $cnt <= $count)
                {
                $p_rec = $transrow['rec_code'] ;
                //loop to check the break of record code
                while($m_ac_code == $transrow['main_ac_code'] && $cnt <= $count &&  $p_rec == $transrow['rec_code'])
                {
                    $ac_ind = $transrow['sub_ac_ind'];	
                    //page break 
                    if($lno == 0 || $lno >= $params['lines_per_page'])
                    {
                    $page = $page + 1 ;
                    if($lno >= $params['lines_per_page'])
                    {
                ?>
                                </table>
                            </td>
                            </tr>
                        </table>	   	 		   
                        <BR CLASS="pageEnd">
                <?php			 
                        $pmccind = 'Y' ;
                    }
                ?>

                        <table width="900" border="0" cellpadding="0" cellspacing="0" class="table border-0">
                        <tr>
                            <td colspan="22">			
                            <table width="100%" border="0" cellpadding="1" cellspacing="0">
                                <tr>
                                <td colspan="3">
                                <table width="100%" cellspacing="0" cellpadding="0" valign="top">
                                    <tr>
                                        <td colspan="4" align="center" style="font-family: Arial, Helvetica, sans-serif; font-weight : bold;font-size: 14px; color: #000000;"><?php echo 'SINHA AND COMPANY'; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="center" style="font-family: Arial, Helvetica, sans-serif; font-weight : bold;font-size: 14px; color: #000000;"><?php echo $params['display_heading']; ?></td>
                                    </tr>
                                    <tr>
                                        <td align="left"  width="" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;Branch</td>
                                        <td align="left"  width="" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo $params['branch_name']; ?></td>
                                        <td align="right" width="" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">Date&nbsp;</td>
                                        <td align="left"  width="" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo date('d-m-Y'); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="left"  style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;Period</td>
                                        <td align="left"  style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php if(!empty($params['date_from']) || !empty($params['fin_year'])) echo $params['date_from'].' to '.$params['date_to']; ?></td>
                                        <td align="right" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">Page&nbsp;</td>
                                        <td align="left"  style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo $page; ?></td>
                                    </tr>
                                    <tr><td colspan="4"><hr width="100%" color="#000000" size="1"></td></tr>
                                </table>         
                                </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                        <tr class="fs-14">
                            <th width="" align="left" class="py-3 px-2">A/c</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="left" class="py-3 px-2">DB</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="right" class="py-3 px-2">Type</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="left" class="py-3 px-2">Doc Date</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="left" class="py-3 px-2">Doc No</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="left" class="py-3 px-2">Instr.No.</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="left" class="py-3 px-2">Date</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="left" class="py-3 px-2">Narration</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="right" class="py-3 px-2">Debit</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="right" class="py-3 px-2">Credit</th>
                            <th width="" class="py-3 px-2">&nbsp;</th>
                            <th width="" align="right" class="py-3 px-2"><?php if($balance_print=='Yes'){?>Balance<?php }?>&nbsp;</th>

                            <?php if ($renderFlag) : ?>
                                <?php if(isset($showActionBtns)) { if($showActionBtns) { ?> <th width="" align="left" class="py-3 px-2 w-10">Actions&nbsp;</th> <?php } } ?>
                            <?php endif; ?>
                        </tr>
                <?php
                                $lno = 9 ;
                    }
                    if($pmccind == 'Y')
                    {
                        $lno          = $lno + 1 ;
                        $pmccind      = 'N' ;
                        $main_ac_code = $transrow['main_ac_code'] ;
                        $main_ac_desc = $transrow['main_ac_desc'] ;
                ?>	   	   
                        <tr class="fs-14">
                        <td class="p-2" align="left" style="vertical-align:top;" colspan="3"><?php echo $main_ac_code?></td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" colspan="20" align="left" style="vertical-align:top;"><?php echo $main_ac_desc?></td>
                        </tr>
                <?php
                    }
                    else
                    {
                        $main_ac_code = '' ;
                        $main_ac_desc = '' ;
                    }
                ?>				 
                        <tr class="fs-14">
                            <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['branch_abbr_name']?></td>
                            <td class="p-2">&nbsp;</td>
                            <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['daybook_code']?></td>
                            <td class="p-2">&nbsp;</td>
                            <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['doc_type']?></td>
                            <td class="p-2">&nbsp;</td>
                            <td class="p-2" align="left" nowrap style="vertical-align:top;"><?php echo date_conv($transrow['doc_date'])?></td>
                            <td class="p-2">&nbsp;</td>
                            <td class="p-2" align="left" style="vertical-align:top;"><?php echo $transrow['doc_no']?></td>
                            <td class="p-2">&nbsp;</td>
                            <td class="p-2" align="left" nowrap style="vertical-align:top;"><?php echo $transrow['instrument_no']?></td>
                            <td class="p-2">&nbsp;</td>
                            <td class="p-2" align="left" nowrap style="vertical-align:top;"><?php echo date_conv($transrow['instrument_dt'])?></td>
                            <td class="p-2">&nbsp;</td>
                <?php
                        if($transrow['rec_code'] == '03') 
                        {
                ?>
                        <td class="p-2" align="left"  style="vertical-align:top;"><?php echo substr($transrow['narration'],0,50)?></td>
                
                <?php       }
                        else if($transrow['rec_code'] == '04')
                        {
                ?>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo substr($transrow['narration'],0,50)?></td>
                <?php       }
                        else
                        {
                ?>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo substr($transrow['narration'],0,50)?></td>
                <?php
                        }
                ?>
                        <td>&nbsp;</td>
                <?php

                        if($transrow['dr_cr_ind'] == 'D')
                        {
                        $t_dr_amt       = $t_dr_amt + $transrow['amount_dr'] ;
                        $grand_trans_dr = $grand_trans_dr + $transrow['amount_dr'] ;		   
                        $balance_amt    = $balance_amt + $transrow['amount_dr'] ;
                ?>
                            
                        <td class="p-2" align="right" style="vertical-align:top;"><?php echo number_format($transrow['amount_dr'],2,'.','')?></td>		 		 
                        <td class="p-2" colspan="2">&nbsp;</td>
                        <td class="p-2">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;"><?php if($balance_print=='Yes') echo number_format($balance_amt,2,'.','');?></td>
                <?php  
                        }
                        if($transrow['dr_cr_ind'] == 'C')
                        {
                        $t_cr_amt       = $t_cr_amt + $transrow['amount_cr'] ;
                        $grand_trans_cr = $grand_trans_cr + $transrow['amount_cr'];		  
                        $balance_amt    = $balance_amt - $transrow['amount_cr'] ;
                ?>		 
                        <td class="p-2" colspan="2">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align:top;"><?php echo number_format($transrow['amount_cr'],2,'.','')?></td>
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
                            $grand_op_balance = $grand_op_balance + $transrow['amount_dr'];		  
                ?>		 
                            <td class="p-2" align="right" style="vertical-align:top;"><?php echo number_format($transrow['amount_dr'],2,'.','')?></td>
                            <td class="p-2" colspan="2">&nbsp;</td>
                            <td class="p-2">&nbsp;</td>
                            <td class="p-2" align="right" style="vertical-align: top;"><?php if($balance_print=='Yes') echo number_format($balance_amt,2,'.','');?></td>
                <?php			 
                            }
                        if($transrow['amount_dr'] < 0 )
                        {
                            $t_cr_amt = $t_cr_amt + abs($transrow['amount_dr']) ;
                            $grand_op_balance = $grand_op_balance + $transrow['amount_dr'];		  
                ?>		 
                            <td class="p-2" colspan="2">&nbsp;</td>
                            <td class="p-2" align="right" style="vertical-align:top;"><?php echo number_format(abs($transrow['amount_dr']),2,'.','')?></td>
                            <td class="p-2">&nbsp;</td>
                            <td class="p-2" align="right" style="vertical-align: top;"><?php if($balance_print=='Yes') echo number_format($balance_amt,2,'.','');?></td>
                            
                <?php			 
                            }
                        if($transrow['amount_dr'] == 0.00 )
                        {
                ?>		 
                            <td class="p-2" colspan="2">&nbsp;</td>
                            <td class="p-2" align="right" style="vertical-align:top;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
                    if(strlen($transrow['narration'])>51)
                    {
                ?>
                    <tr class="fs-14">
                        <td class="p-2" colspan="14">&nbsp;</td>
                        <td class="p-2" align="left" style="vertical-align:top;"><?php echo substr($transrow['narration'],50)?></td>
                    </tr>	   
                <?php
                    $lno = $lno + 1 ;
                    }
                //	 $gt_tot_dr_amt = $gt_tot_dr_amt + $t_dr_amt;
                // 	 $gt_tot_cr_amt = $gt_tot_cr_amt + $t_cr_amt;
                    $lno = $lno + 1 ;
                    $transrow = ($cnt < $count) ? $sele_qry[$cnt] : $transrow;  
                    $cnt = $cnt + 1 ; 
                    }
                    $t_clo_amt = $t_dr_amt - $t_cr_amt ;
                }
                        if($t_dr_amt != 0.00 || $t_cr_amt != 0.00)
                        {
                ?>
                        
                        <tr class="fs-14">
                            <td class="p-2" colspan="14"  style="background-color:#eff3b1;">&nbsp;</td>
                            <td class="p-2" align="left" style="background-color:#eff3b1;">Total</td>
                            <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                <?php		   
                        if($t_dr_amt > 0 )
                        {
                ?>		   
                        <td class="p-2" align="right" style="vertical-align:top;background-color:#eff3b1;"><?php echo number_format($t_dr_amt,2,'.','')?></td>
                <?php
                        }
                        if($t_dr_amt == 0.00)
                        {
                ?>
                        <td class="p-2" align="right" style="vertical-align:top;background-color:#eff3b1;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>		   		   
                <?php
                        }
                ?>		   		   
                        <td class="p-2" style="background-color:#eff3b1;background-color:#eff3b1;">&nbsp;</td>
                <?php		   
                        if($t_cr_amt > 0 )
                        {
                ?>
                        <td class="p-2" align="right" style="vertical-align:top;background-color:#eff3b1;"><?php echo number_format($t_cr_amt,2,'.','')?></td>
                <?php		   
                        }
                        if($t_cr_amt = 0.00)
                        {
                ?>
                        <td class="p-2" align="right" style="vertical-align:top;background-color:#eff3b1;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
                <tr class="fs-14"><td class="py-1">&nbsp;</td></tr>
                    <tr class="fs-14">
                        <td class="p-2" colspan="14" style="background-color:#bee9f7;">&nbsp;</td>
                        <td class="p-2" align="left" style="background-color:#bee9f7;">Closing Balance</td>
                        <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                <?php	  
                    if($t_clo_amt > 0)
                    {
                ?>
                    <td align="right" style="background-color:#bee9f7;"><?php echo number_format($t_clo_amt,2,'.','')?></td>
                    <td colspan="2" style="background-color:#bee9f7;">&nbsp;</td>
                <?php	  
                    }
                    if($t_clo_amt < 0)
                    {
                ?>
                    <td colspan="2" style="background-color:#bee9f7;">&nbsp;</td>
                    <td align="right" style="background-color:#bee9f7;"><?php echo number_format(abs($t_clo_amt),2,'.','')?></td>
                <?php	  
                    }
                    if($t_clo_amt == 0.00)
                    {
                ?>
                    <td colspan="2" style="background-color:#bee9f7;">&nbsp;</td>
                    <td align="right" style="background-color:#bee9f7;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    
                <?php	  
                    }
                ?>	 
                    <td style="background-color:#bee9f7;">&nbsp;</td>
                    <td style="background-color:#bee9f7;">&nbsp;</td>  
                    </tr>
                    <tr class="fs-14">
                        <td colspan="22" class="py-1">&nbsp;</td>
                    </tr>
                        
                <?php	
                $lno = $lno + 2 ;  
                }   
                ?>
                    <tr class="fs-14">
                        <td class="p-2" colspan="14" style="background-color:#eff3b1;" align="center">Grand Total</td>
                        <td class="p-2" style="vertical-align: top;background-color:#eff3b1;">Opening</td>
                        <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;background-color:#eff3b1;"><?php if($grand_op_balance >= 0) echo number_format($grand_op_balance,2,'.','');?></td>
                        <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;background-color:#eff3b1;"><?php if($grand_op_balance <= 0) echo number_format(abs($grand_op_balance),2,'.','');?></td>
                        <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                        <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                    </tr>
                    <tr class="fs-14">
                        <td colspan="22" class="py-1">&nbsp;</td>
                    </tr>
                    <tr class="fs-14">
                        <td class="p-2" colspan="14" style="background-color:#bee9f7;">&nbsp;</td>
                        <td class="p-2" style="vertical-align: top;background-color:#bee9f7;">Transaction</td>
                        <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;background-color:#bee9f7;"><?php if($grand_trans_dr != 0.00) echo number_format($grand_trans_dr,2,'.','');?></td>
                        <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;background-color:#bee9f7;"><?php if($grand_trans_cr != 0.00) echo number_format($grand_trans_cr,2,'.','');?></td>
                        <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                        <td class="p-2" style="background-color:#bee9f7;">&nbsp;</td>
                    </tr>
                    <tr class="fs-14">
                        <td colspan="22" class="py-1">&nbsp;</td>
                    </tr>
                    <?php $grand_clo_balance = $grand_op_balance + $grand_trans_dr - $grand_trans_cr; ?>
                    <tr class="fs-14">
                        <td colspan="14" class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                        <td class="p-2" style="vertical-align: top;background-color:#eff3b1;">Closing</td>
                        <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;background-color:#eff3b1;"><?php if($grand_clo_balance >= 0) echo number_format($grand_clo_balance,2,'.','');?></td>
                        <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                        <td class="p-2" align="right" style="vertical-align: top;background-color:#eff3b1;"><?php if($grand_clo_balance <= 0) echo number_format(abs($grand_clo_balance),2,'.','');?></td>
                        <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                        <td class="p-2" style="background-color:#eff3b1;">&nbsp;</td>
                    </tr>
                </table>       	 	
    </main>
<?php } else if(isset($trandtl_qry)) { ?>
    <script>
		document.getElementById('sidebar').style.display = "none";
		document.getElementById('burgerMenu').style.display = "none";
	</script>

	<main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
        <div class="pagetitle">
        <h1>Voucher [View]</h1>
        </div><!-- End Page Title -->

        <section class="section dashboard">
        <div class="row">
            <div class="col-md-12 mt-2">
                <div class="frms-sec d-inline-block w-100 bg-white p-3">
                    <div class="d-inline-block w-100 mt-2">
                        <table class="table table-bordered tblePdngsml">
                            <tr>
                                <td class="bgBlue">
                                    <span>Serial No</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['ref_doc_serial_no']?></b></span>
                                </td>
                                <td class="bgBlue">
                                    <span>Voucher</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['doc_type']?> / <?php echo $tranhdr_row['doc_no']?> / <?php echo date_conv($tranhdr_row['doc_date'],'-')?> <?php echo '/Paid By'.' - '. $tranhdr_row['paid_by'];?> </b></span>
                                </td>
                                <?php if(session()->userId == 'abhijit' ) { ?>
                                    <td><b> <?php echo 'Prepared On'.' - '. date_conv($vchrhdr_row['prepared_on'],'-')?> <?php echo '/Prepared By'.' - '. $vchrhdr_row['prepared_by'];?> </b></font> </td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <td class="bgBlue">
                                    <span>Fin Year</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['fin_year']?></b></span>
                                </td>
                                <td class="bgBlue">
                                    <span>Payee</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['payee_payer_name']?>&nbsp;&nbsp;<?php if ($tranhdr_row['payee_payer_name'] != '') {?>[&nbsp;<?php echo $tranhdr_row['payee_payer_type']?>&nbsp;]<?php }?></b></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="bgBlue">
                                    <span>Branch</span>
                                </td>
                                <td>
                                    <span><b><?php echo $tranhdr_row['branch_name']?></b></span>
                                </td>
                                <td class="bgBlue">
                                    <span><?php if ($tranhdr_row['daybook_code'] != '10') {echo 'Instrument';} else {echo 'Daybook Code';}?></span>
                                </td>
                                <td>
                                    <span><b><?php if ($tranhdr_row['daybook_code'] != '10') {echo $tranhdr_row['instrument_no'];}?> &nbsp; <?php if ($tranhdr_row['daybook_code'] != '10' && $tranhdr_row['daybook_code'] != '40') {echo'Date:- '. date_conv($tranhdr_row['instrument_dt'],'-');}?> &nbsp; <?php if ($tranhdr_row['daybook_code'] != '10' && $tranhdr_row['bank_name'] != '' ) {echo'Bank - '. $tranhdr_row['bank_name'];}?>  <?php if ($tranhdr_row['daybook_code'] == '10') {echo $tranhdr_row['daybook_code'];}?></b></span>
                                </td>
                                <td class="bgBlue">
                                    <span><?php if ($tranhdr_row['daybook_code'] == '10') {echo '';} else {echo 'Daybook Code';}?></span>
                                </td>
                                <td>
                                    <span><b><?php if ($tranhdr_row['daybook_code'] != '10') {echo $tranhdr_row['daybook_code'];} else {echo '';}?> </b></span>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered tblePdngsml">
                            <tbody>
                                <tr class="fs-14">
                                    <th>Main</th>
                                    <th>Sub</th>
                                    <th>Matter</th>
                                    <th>Client</th>
                                    <th>Bill No</th>
                                    <th>Purpose</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Credit</th>
                                </tr>
                                <?php $tdtotal=0; $tctotal=0; foreach($trandtl_qry as $trandtl_row) { ?>
                                    <tr>							
                                        <td class="">
                                            <span><?php echo $trandtl_row['main_ac_code']?></span>
                                        </td>
                                        <td class="">
                                            <span><?php echo $trandtl_row['sub_ac_code'] ?></span>
                                        </td>
                                        <td class=""><span><?php echo $trandtl_row['matter_code'] ?></span></td>
                                        <td class="">
                                            <span><?php echo $trandtl_row['client_code'] ?> </span>
                                        </td>
                                        <td class="">
                                            <span><?php echo $trandtl_row['bill_no']?></span>
                                        </td>
                                        <td class="w-350">
                                            <span><?php echo $trandtl_row['narration']?></span>
                                        </td>
                                        <td class="wd100 text-end">
                                            <span><?php if($trandtl_row['dr_cr_ind'] == 'D') {echo $trandtl_row['gross_amount'];} else { echo '&nbsp;'; }?></span>
                                        </td>
                                        <td class="wd100 text-end">
                                            <span><?php if($trandtl_row['dr_cr_ind'] == 'C') {echo $trandtl_row['gross_amount'];} else { echo '&nbsp;'; }?></span>
                                        </td>
                                    </tr>
                                <?php if($trandtl_row['dr_cr_ind'] == 'D') { $tdtotal = $tdtotal + $trandtl_row['gross_amount'] ; } else { $tctotal = $tctotal + $trandtl_row['gross_amount'] ; }  } ?> 
                                <tr>							
                                    
                                    <td class="text-end bgBlue" colspan="6">
                                        <span>Total</span>
                                    </td>
                                    <td class="wd100 bgBlue text-end">
                                        <span><b><?php if($tdtotal != 0) {echo number_format(abs($tdtotal),2,'.','') ;} else {echo '&nbsp;';} ?></b></span>
                                    </td>
                                    <td class="wd100 bgBlue text-end">
                                        <span><b><?php if($tctotal != 0) {echo number_format(abs($tctotal),2,'.','') ;} else {echo '&nbsp;';} ?></b></span>
                                    </td>
                                </tr>
                            </tbody>
                            <?php if($tdtotal + $tctotal == 0) { ?> 
                            <tr>
                                <td> <span> The Tab will Close Automatically in <b id="backTimer">05 Seconds</b>  !!</span> </td>
                            </tr> <script> 
                            let counter=5;
                            function countdown(counter) {
                                if(counter>0) {
                                    counter--; setTimeout(function(){countdown(counter)},1000);
                                    document.getElementById('backTimer').innerText = '0' + counter + ' Seconds';
                                }
                            } countdown(counter);
                            setTimeout(() => { window.close(); }, 1000*counter); </script> <?php } ?>
                        </table>
                    </div>
                    <?php if ($renderFlag) : ?>
                    <div class="frms-sec-insde d-block float-start col-md-12">
                        <button onclick="window.close()" class="text-decoration-none d-block float-start btn btn-dark">Close</button>
                    </div>
				    <?php endif; ?>
                </div>
                
            </div>
        </div>
        </section>

  </main><!-- End #main -->
<?php } ?>
<?= $this->endSection() ?>