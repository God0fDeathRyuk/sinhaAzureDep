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
            <h1>Matter Ladger</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="matterLedger" name="matterLedger" onsubmit="setValue(event)">
            <section class="section dashboard">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <div class="frms-sec d-inline-block w-100 bg-white p-3">
                            <div class="col-md-3 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="branch_code" required >
                                    <?php foreach($data['branches'] as $branch) { ?>
                                        <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-5 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-45 float-start" name="date_from" value="<?= $data['curr_fyrsdt'] ?>" onBlur="make_date(this)" required>
                                <span class="w-2 float-start mx-1">---</span>
                                <input type="text" class="form-control w-45 float-start" name="date_to" value="<?= date('d-m-Y') ?>" onBlur="make_date(this)" required>
                            </div>			
                            <div class="col-md-4 float-start px-2 mb-1 position-relative">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Code <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="matterCode" onchange="fetchData(this, 'matter_code', ['matterDesc', 'clientName', 'clientCode'], ['matter_desc', 'client_name', 'client_code'], 'matter_code')" size="05" maxlength="06" name="matter_code" />
                                <i class="fa fa-binoculars icn-vw lkupIcn" aria-hidden="true" onclick="showData('matter_code', '<?= $displayId['matter_help_id'] ?>', 'matterCode', ['matterDesc', 'clientName', 'clientCode'], ['matter_desc', 'client_name', 'client_code'], 'matter_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-6 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Matter Description <strong class="text-danger">*</strong></label>					
                                <input class="form-control w-100 float-start" name="matter_desc" id="matterDesc" readonly>
                            </div>
                            <div class="col-md-6 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name <strong class="text-danger">*</strong></label>					
                                <input class="form-control w-100 float-start" type="text"   name="client_name" id="clientName" readonly/>
                                <input class="form-control w-100 float-start" type="hidden" name="client_code" id="clientCode" readonly >
                            </div>
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                                <select class="form-select w-100 float-start" name="output_type" required >
                                    <option value="Report">View Report</option>
                                    <option value="Pdf" >Download PDF</option>
                                    <option value="Excel" >Download Excel</option>
                                </select>
                            </div>
                            
                            <div class="col-md-9 d-inline-block mt-2">
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
                $lno = 0 ;
                $page = 0 ;
                $cnt = 1 ;
                $balance_print = 'Yes';
                $transrow = isset($sele_qry[$cnt-1]) ? $sele_qry[$cnt-1] : '' ; 
                $count = $params['count'];
                $grand_trans_dr = 0;
                $grand_trans_cr = 0;
                $grand_clo_balance = 0;
                $grand_op_balance = 0;
                while($cnt <= $count)
                {
                $m_ac_code   = $transrow['matter_code'] ;
                $ac_ind      = $transrow['sub_ac_ind'] ;
                $t_dr_amt    = 0 ;
                $t_cr_amt    = 0 ;
                $t_clo_amt   = 0 ;
                $balance_amt = 0.00 ;
                
                //loop to check the break of main account code
                $pmccind = 'Y';
                while($m_ac_code == $transrow['matter_code'] && $cnt <= $count)
                {
                $p_rec = $transrow['rec_code'] ;
                //loop to check the break of record code
                while($m_ac_code == $transrow['matter_code'] && $cnt <= $count &&  $p_rec == $transrow['rec_code'])
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
                        <table width="950" border="0" cellpadding="0" cellspacing="0" class="table border-0">
                        <tr>
                            <td colspan="21">			
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
                                        <td align="left"  width="05%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;Branch</td>
                                        <td align="left"  width="70%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo $params['branch_name']; ?></td>
                                        <td align="right" width="10%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">Date&nbsp;</td>
                                        <td align="left"  width="15%" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo date('d-m-Y'); ?></td>
                                    </tr>
                                    <tr>
                                        <td align="left"  style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;Period</td>
                                        <td align="left"  style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php if(!empty($params['date_from']) || !empty($params['fin_year'])) echo $params['date_from'].' to '.$params['date_to']; ?></td>
                                        <td align="right" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">Page&nbsp;</td>
                                        <td align="left"  style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">&nbsp;:&nbsp;<?php echo $page; ?></td>
                                    </tr>
                                    
                                </table>          
                                </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                       
                       <tr class="fs-14">
                            <th class="py-3 px-2" align="left">Br</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="left">DB</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="right">Type</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="left">Doc Date</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="left">Doc No</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="left">Instr.No.</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="left">Date</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="left">Narration</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="right">Debit</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="right">Credit</th>
                            <th class="py-3 px-2">&nbsp;</th>
                            <th class="py-3 px-2" align="right"><?php if($balance_print=='Yes'){?>Balance<?php }?>&nbsp;</th>
                        </tr>
                        
                <?php
                                $lno = 9 ;
                    }
                    if($pmccind == 'Y')
                    {
                        $lno          = $lno + 1 ;
                        $pmccind      = 'N' ;
                        $matter_code = $transrow['matter_code'] ;
                        $matter_desc = $transrow['matter_desc'] ;
                ?>	   	   
                        <tr class="fs-14">
                            <td class="p-2" align="left" style="vertical-align:top;" colspan="3"><?php echo $matter_code?></td>
                            <td class="p-2">&nbsp;</td>
                            <td class="p-2" colspan="11" align="left" style="vertical-align:top;"><?php echo $matter_desc?></td>
                        </tr>
                <?php
                    }
                    else
                    {
                        $matter_code = '' ;
                        $matter_desc = '' ;
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
                        <td class="p-2">&nbsp;</td>
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
                    </tr>	   
                <?php	   
                    $lno = $lno + 1 ;
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
                    $transrow = ($cnt < $count) ? $sele_qry[$cnt] : $transrow; 
                    $cnt = $cnt + 1 ; 
                    }
                    $t_clo_amt = $t_dr_amt - $t_cr_amt ;
                }
                        if($t_dr_amt != 0.00 || $t_cr_amt != 0.00)
                        {
                ?>
                        <tr class="fs-14">
                            <td colspan="14" class="p-2">&nbsp;</td>
                            <td colspan="7" class="p-2"><hr size="1"></td>
                        </tr>
                        <tr class="fs-14">
                            <td colspan="14" class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                            <td align="left" class="p-2" style="background-color: #e2e6506e;">Total</td>
                            <td style="background-color: #e2e6506e;">&nbsp;</td>
                <?php		   
                        if($t_dr_amt > 0 )
                        {
                ?>		   
                        <td class="p-2" align="right" style="vertical-align:top;background-color: #e2e6506e;"><?php echo number_format($t_dr_amt,2,'.','')?></td>
                <?php
                        }
                        if($t_dr_amt == 0.00)
                        {
                ?>
                        <td class="p-2" align="right" style="vertical-align:top;background-color: #e2e6506e;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>		   		   
                <?php
                        }
                ?>		   		   
                        <td class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                <?php		   
                        if($t_cr_amt > 0 )
                        {
                ?>
                        <td class="p-2" align="right" style="vertical-align:top;background-color: #e2e6506e;"><?php echo number_format($t_cr_amt,2,'.','')?></td>
                <?php		   
                        }
                        if($t_cr_amt = 0.00)
                        {
                ?>
                        <td class="p-2" align="right" style="vertical-align:top;background-color: #e2e6506e;">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <?php
                        }
                ?>		   		   		   
                        </tr>
                <?php	    
                        $lno = $lno + 2 ;
                        }
                ?>		
                    <tr class="fs-14">
                        <td class="p-2" colspan="14" style="background-color: #91d6ec6e;">&nbsp;</td>
                        <td class="p-2" align="left" style="background-color: #91d6ec6e;">Closing Balance</td>
                        <td class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                <?php	  
                    if($t_clo_amt > 0)
                    {
                ?>
                    <td class="p-2" style="background-color: #91d6ec6e;" align="right"><?php echo number_format($t_clo_amt,2,'.','')?></td>
                    <td class="p-2" style="background-color: #91d6ec6e;" colspan="2">&nbsp;</td>
                <?php	  
                    }
                    if($t_clo_amt < 0)
                    {
                ?>
                    <td class="p-2" style="background-color: #91d6ec6e;" colspan="2">&nbsp;</td>
                    <td class="p-2" style="background-color: #91d6ec6e;" align="right"><?php echo number_format(abs($t_clo_amt),2,'.','')?></td>
                <?php	  
                    }
                    if($t_clo_amt == 0.00)
                    {
                ?>
                    <td class="p-2" style="background-color: #91d6ec6e;" colspan="2">&nbsp;</td>
                    <td class="p-2" style="background-color: #91d6ec6e;" align="right">-&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <?php	  
                    }
                ?>	   
                    </tr>
                    <tr class="fs-14">
                        <td class="p-2" colspan="14">&nbsp;</td>
                        <td class="p-2" colspan="7"><hr size="1"></td>
                    </tr>
                <?php	
                $lno = $lno + 2 ;  
                }   
                ?>
                    <tr class="fs-14">
                        <td class="p-2" colspan="14" style="background-color: #91d6ec6e;">Grand Total</td>
                        <td class="p-2"  style="background-color: #91d6ec6e;">Opening</td>
                        <td class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                        <td class="p-2" align="right"  style="background-color: #91d6ec6e;"><?php if($grand_op_balance >= 0) echo number_format($grand_op_balance,2,'.','');?></td>
                        <td class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                        <td class="p-2" align="right"  style="background-color: #91d6ec6e;"><?php if($grand_op_balance <= 0) echo number_format(abs($grand_op_balance),2,'.','');?></td>
                    </tr>
                    <tr class="fs-14">
                        <td class="p-2" colspan="14" style="background-color: #91d6ec6e;">&nbsp;</td>
                        <td class="p-2" style="background-color: #91d6ec6e;">Transaction</td>
                        <td class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                        <td class="p-2" style="background-color: #91d6ec6e;" align="right"><?php if($grand_trans_dr != 0.00) echo number_format($grand_trans_dr,2,'.','');?></td>
                        <td class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                        <td class="p-2" style="background-color: #91d6ec6e;" align="right"><?php if($grand_trans_cr != 0.00) echo number_format($grand_trans_cr,2,'.','');?></td>
                    </tr>
                    <?php $grand_clo_balance = $grand_op_balance + $grand_trans_dr - $grand_trans_cr; ?>
                    <tr class="fs-14">
                        <td class="p-2" colspan="14" style="background-color: #91d6ec6e;">&nbsp;</td>
                        <td class="p-2"  style="background-color: #91d6ec6e;">Closing</td>
                        <td class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                        <td class="p-2" align="right"  style="background-color: #91d6ec6e;"><?php if($grand_clo_balance >= 0) echo number_format($grand_clo_balance,2,'.','');?></td>
                        <td class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                        <td class="p-2" align="right"  style="background-color: #91d6ec6e;"><?php if($grand_clo_balance <= 0) echo number_format(abs($grand_clo_balance),2,'.','');?></td>
                    </tr>
                    <tr class="fs-14"><td>&nbsp;</td></tr>
                </table>       	
    </main>
<?php } ?>

<script>
    function setValue(e) {
        e.preventDefault();
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.matterLedger.date_from.value.substring(6,10)+document.matterLedger.date_from.value.substring(3,5)+document.matterLedger.date_from.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period From Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.matterLedger.date_from.focus()}, 500) });
            return false;
        }
        else if (document.matterLedger.date_to.value.substring(6,10)+document.matterLedger.date_to.value.substring(3,5)+document.matterLedger.date_to.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period To Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.matterLedger.date_to.focus()}, 500) });
            return false;
        }
        else if (document.matterLedger.date_from.value.substring(6,10)+document.matterLedger.date_from.value.substring(3,5)+document.matterLedger.date_from.value.substring(0,2)>document.matterLedger.date_to.value.substring(6,10)+document.matterLedger.date_to.value.substring(3,5)+document.matterLedger.date_to.value.substring(0,2)) {
            Swal.fire({ text: 'Period To Date must be less than Period From Date' }).then((result) => { setTimeout(() => {document.matterLedger.date_to.focus()}, 500) });
            return false;
        } else if (document.matterLedger.matter_code.value == '') {
            Swal.fire({ text: 'Please select Matter Code !!!' }).then((result) => { setTimeout(() => {document.matterLedger.matter_code.focus()}, 500) });
            return false;
        }    
        
        document.matterLedger.submit();
    }
</script>

<?= $this->endSection() ?>