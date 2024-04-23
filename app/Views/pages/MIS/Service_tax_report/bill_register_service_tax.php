<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?= view('partials/modelForm', ['model' => 'lookup']); ?>
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

    <div class="pagetitle d-inline-block w-100">
        <h1 class="col-md-11 float-start">Bill Register Service Tax</h1>
    </div>
    
    <section class="section dashboard">
        <div class="row">
            <form action="" method="post" name="f1">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branch_code" required>
                                <?php foreach($branch_qry as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch_code == $branch['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                            <input class="display_date_mandatory" type="hidden" name="current_date" value="<?= $current_date ?>">
                        </div>
                        <div class="col-md-6 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Period <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-45 float-start datepicker" name="billing_start_date" value="<?= $billing_start_date ?>" onBlur="make_date(this)">
                            <span class="w-2 float-start mx-2">---</span>
                            <input type="text" class="form-control w-45 float-start datepicker" name="billing_end_date" value="<?= $billing_end_date ?>" onBlur="make_date(this)">
                        </div>					
                        
                        <div class="col-md-3 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="output_type" required>
                                <option value="Report">View Report</option>
                                <option value="Pdf" >Download PDF</option>
                                <option value="Excel" >Download Excel</option> 
                            </select>
                        </div>			
                        <input type="hidden"  name="user_option" value="<?= $user_option ?>">  
                        <button type="button" onclick="validateProceed()" class="btn btn-primary cstmBtn mt-3 ms-2">Proceed</button>				
                        <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-3 ms-2">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>
<?php } else { ?>
    <script>
        document.getElementById('sidebar').style.display = "none";
        document.getElementById('burgerMenu').style.display = "none";
    </script>
    <main id="main" class="main <?= ($renderFlag) ? 'm0auto mtop90 ' : '' ?>" <?= (!$renderFlag) ? 'style="margin-top: 0px !important;"' : '' ?>>
        <div class="position-absolute btndv">
            <?php if ($renderFlag) : ?>
                <a href="<?= base_url($requested_url) ?>" class="text-decoration-none d-block float-start btn btn-dark">Back</a>
            <?php endif; ?>
        </div> 
        <?php
        $maxline    = 75 ;
        $lineno     = 0 ;
        $pageno     = 0 ;
        $tbillamt   = 0 ; 
        $trealamt   = 0 ; 
        $tbalnamt   = 0 ; 
        $tdefcamt   = 0 ; 
        $totstaxamt = $tnontaxamt = $ttaxamt = $tstaxamt = $tcessamt = $thecessamt = 0 ;

        $report_row = $bill_qry[0]; 
        $report_cnt = $bill_cnt ;
        $rowcnt     = 1 ;
        $s_tax_percent = $s_tax = $cess_tax = $hecess_tax = $totsrv_tax_amount = 0;
        
        while ($rowcnt <= $report_cnt) {
            if ($lineno == 0 || $lineno > $maxline) {
            if($lineno > $maxline) { 
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
                    <td colspan="12">    
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                            <td width="15%">&nbsp;</td>
                            <td width="65%">&nbsp;</td>
                            <td width="8%">&nbsp;</td>
                            <td width="12%">&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper(session()->company_name)?></b></td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($report_desc)?> </u></b></td>
                            </tr>
                            <tr>
                            <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper(isset($report_sub_desc) ? $report_sub_desc : '')?> </u></b></td>
                            </tr>
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Branch</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $branch_name?></b></td>
                            <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo $global_dmydate?></td>
                            </tr>
                            <tr>
                            <td class="report_label_text">Bill Raised For The Period : </td>
                            <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $period_desc ?></b></td>
                            <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                            <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
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
                    <th width="49"  align="left"    class="py-3 px-2">Bill No</th>
                    <th width="77"  align="center"  class="py-3 px-2">Bill Dt&nbsp;</th>
                    <th width="306" align="left"    class="py-3 px-2">Client Name</th>
                    <th width="112" align="right"   class="py-3 px-2">Non Taxable Service&nbsp;</th>
                    <th width="107" align="right"   class="py-3 px-2">Taxable Service&nbsp;</th>
                    <th width="75" align="right"   class="py-3 px-2">S Tax %&nbsp;</th>
                    <th width="87"  align="right"   class="py-3 px-2">Service Tax&nbsp;</th>
                    <th width="70"  align="right"   class="py-3 px-2">Edu. Cess&nbsp;</th>
                    <th width="75" align="right"   class="py-3 px-2">H.S. Edu. Cess&nbsp;</th>
                    <th width="100"  align="right"   class="py-3 px-2">Total S. Tax&nbsp;</th>
                    <th width="92"  align="right"   class="py-3 px-2">Re-Imbursement&nbsp;</th>
                    <th width="130"  align="right"   class="py-3 px-2">Bill Toatal&nbsp;</th>
                </tr>
                            
                    <?php $lineno = 9 ; }

                        if ($report_row['bill_year'] == '2010-2011') {
                            $s_tax_percent = '10.30%';
                            $s_tax = ($report_row['taxable_total'])*(10/100); 
                            $cess_tax = $s_tax*2/100;
                            $hecess_tax = $s_tax*1/100;
                            $totsrv_tax_amount = $s_tax + $cess_tax + $hecess_tax;
                        }

                        if ($report_row['bill_year'] == '2011-2012') {
                                $s_tax_percent = '10.30%';
                                $s_tax = ($report_row['taxable_total'])*(10/100); 
                                $cess_tax = $s_tax*2/100;
                                $hecess_tax = $s_tax*1/100;
                                $totsrv_tax_amount = $s_tax + $cess_tax + $hecess_tax;
                        }

                        if ($report_row['bill_year'] == '2012-2013') {
                            $s_tax_percent = '12.36%';
                            $s_tax = ($report_row['taxable_total'])*(12/100); 
                            $cess_tax = $s_tax*2/100;
                            $hecess_tax = $s_tax*1/100;
                            $totsrv_tax_amount = $s_tax + $cess_tax + $hecess_tax;
                        }
                    ?>
                    <tr class="fs-14">
                        <td align="left"   class="p-2"><?php echo $report_row['billno']?></td> 
                        <td align="left"   class="p-2"><?php echo date_conv($report_row['bill_date'])?>&nbsp;</td> 
                        <td align="left"   class="p-2"><?php echo strtoupper($report_row['client_name'])?></td>
                        <td align="right"  class="p-2"><?php echo number_format($report_row['non_taxable_total'],2,'.','');?></td>
                        <td align="right"  class="p-2"><?php echo number_format($report_row['taxable_total'],2,'.','');?></td>
                        <td align="right"  class="p-2"><?php echo $s_tax_percent;?></td>
                        <td align="right"  class="p-2"><?php echo number_format($s_tax,2,'.','');?></td>
                        <td align="right"  class="p-2"><?php echo number_format($cess_tax,2,'.','');?></td>
                        <td align="right"  class="p-2"><?php echo number_format($hecess_tax,2,'.','');?></td>
                        <td align="right"  class="p-2"><?php echo number_format($totsrv_tax_amount,2,'.','');?>&nbsp;</td>
                        <td align="right"  class="p-2"><?php echo number_format($report_row['non_taxable_total'],2,'.','');?></td>
                        <td align="right"  class="p-2"><?php echo number_format($report_row['billed_amount'],2,'.','');?></td>   
                    </tr>
                    <?php     
                        $lineno   = $lineno  + 2;
                        
                        $tnontaxamt  = $tnontaxamt + $report_row['non_taxable_total'];
                        $ttaxamt     = $ttaxamt + $report_row['taxable_total'];
                        $tstaxamt    = $tstaxamt + $s_tax;
                        $tcessamt    = $tcessamt + $cess_tax;
                        $thecessamt  = $thecessamt + $hecess_tax;
                        $totstaxamt  = $totstaxamt + $s_tax + $cess_tax + $hecess_tax;
                        $tbillamt    = $tbillamt + $report_row['billed_amount'];

                        $report_row = ($rowcnt < $bill_cnt) ? $bill_qry[$rowcnt] : [];
                        $rowcnt = $rowcnt + 1 ;
        } ?>                   
                        <tr class="fs-14">
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr class="fs-14">
                            <td align="right" class="p-2" colspan="3" style="background-color: #91d6ec6e;"><b> GRAND TOTAL </b>&nbsp;</td>
                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tnontaxamt > 0) { echo number_format($tnontaxamt,2,'.','') ;}?></b></td>
                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($ttaxamt > 0) { echo number_format($ttaxamt,2,'.','') ;}?></b></td>
                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tstaxamt > 0) { echo number_format($tstaxamt,2,'.','') ;}?></b></td>
                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tcessamt > 0) { echo number_format($tcessamt,2,'.','') ;}?></b></td>
                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($thecessamt > 0) { echo number_format($thecessamt,2,'.','') ;}?></b></td>
                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($totstaxamt > 0) { echo number_format($totstaxamt,2,'.','') ;}?></b>&nbsp;</td>
                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tnontaxamt > 0) { echo number_format($tnontaxamt,2,'.','') ;}?></b></td>
                            <td align="right"  class="p-2" style="background-color: #91d6ec6e;"><b><?php if($tbillamt > 0) { echo number_format($tbillamt,2,'.','') ;}?></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> 
    </main>
<?php } ?>

<script>
    function validateProceed() {
   	     var bcode     = document.f1.branch_code.value ;
         var curdt     = document.f1.current_date.value ; 
         var bpsdt     = document.f1.billing_start_date.value ; 
         var bpedt     = document.f1.billing_end_date.value ; 
         var outp_type = document.f1.output_type.value ;
         var bpsdtymd  = bpsdt.substr(6,4)+bpsdt.substr(3,2)+bpsdt.substr(0,2) ;
         var bpedtymd  = bpedt.substr(6,4)+bpedt.substr(3,2)+bpedt.substr(0,2) ;
         var curdtymd  = curdt.substr(6,4)+curdt.substr(3,2)+curdt.substr(0,2) ;

        if (document.f1.branch_code.value == '') {
            alert('Please enter Branch ........');
            document.f1.branch_code.focus() ;
            return false;

        } else if (document.f1.billing_end_date.value == '') {
            alert('Please enter Billing End Date ........');
            document.f1.billing_end_date.focus() ;
            return false;

        } else if (bpsdtymd > curdtymd) {
            alert('Billing Start Date must be <= Current Date ........');
            document.f1.billing_start_date.focus() ;
            return false;

        } else if (bpedtymd > curdtymd) {
            alert('Billing End Date must be <= Current Date ........');
            document.f1.billing_end_date.focus() ;
            return false;

        } else if (bpedtymd < bpsdtymd) {
            alert('Billing End Date must be >= Billing Start Date ........');
            document.f1.billing_start_date.focus() ;
            return false;
        } else {
            document.f1.submit();
        }
    }
</script>
<?= $this->endSection() ?>