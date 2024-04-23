<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>
<?php if((!isset($sele_ac_qry))) { ?> 
    <main id="main" class="main">

        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
        <h1>Selected ac report (5110)</h1>
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
                $lno  = 0 ;
                $page = 0 ;
                $cnt  = 1 ;
                $tot_amt = 0.00;
                $lines_per_page = 65;
                $tot_dramt  = $tot_cramt = 0 ;

                $count = $params['sele_ac_count'] ;
                $transrow = isset($sele_ac_qry[$cnt-1]) ? $sele_ac_qry[$cnt-1] : '' ;
                while($cnt <= $count) {
                if($lno == 0 || $lno >= $lines_per_page)
                {
                    $page = $page + 1 ;
                    if($lno >= $lines_per_page)
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
                        <td colspan="3">			
                        <table width="100%" border="0" cellpadding="1" cellspacing="0">
                            <tr>
                            <td width="">
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
                        <th width="" align="left" class="py-3 px-2">Code</th>
                        <th width="" align="left" class="py-3 px-2">Name</th>
                        <th width="" align="right" class="py-3 px-2">&nbsp;Amount</th>
                    </tr>
                <?php
                    $lno = 9 ;
                }

                
                    $net_amount = abs($transrow['cr_amount']) - abs($transrow['dr_amount']) ;
                    $net_amount = abs($net_amount);
                    $clos_amt = $transrow['cr_amount'] - $transrow['dr_amount'] ;
                
                ?>		

                <?php if ($net_amount >0) {	?>	 
                            <tr class="fs-14">
                                <td align="left" class="p-2" style="vertical-align:top;"><?php echo $transrow['c_code'];?>&nbsp;</td>
                                <td align="left" class="p-2" style="vertical-align:top;"><?php echo $transrow['c_name'];?></td>
                                <td align="right" class="p-2" style="vertical-align:top;"><?php echo number_format($clos_amt,2,'.','');?>&nbsp;<?php if($clos_amt<0) echo 'DR'; else echo 'CR';?> </td>
                            </tr>
                <?php 
                }
                    $tot_amt = $tot_amt + $net_amount;
                    
                    $tot_dramt = $tot_dramt + $transrow['dr_amount'];
                    $tot_cramt = $tot_cramt + $transrow['cr_amount'];
                    $tot_netamt =  $tot_cramt - $tot_dramt;


                $lno = $lno + 1 ;
                $transrow = ($cnt < $count) ? $sele_ac_qry[$cnt] : $transrow; 
                $cnt = $cnt + 1 ;
                }

                    if($lines_per_page - $lno < 3) { $lno = $lines_per_page + 1 ; }

                ?>
                <tr class="fs-14">
                    <td colspan="2" align="right" class="p-2" style="background-color:#eff3b1;"><strong>Total</strong></td>
                    <td width="" valign="top" class="p-2" colspan="2" align="right" style="background-color:#eff3b1;">&nbsp;<b><?php echo number_format($tot_netamt,2,'.','');?>&nbsp;<?php if($tot_netamt<0) echo 'DR'; else echo 'CR';?> </b></td>

                <tr>
                </table>       	
    </main>
<?php } ?>

<?= $this->endSection() ?>
