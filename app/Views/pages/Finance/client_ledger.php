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
        <h1>Client Ladger</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="clientLedger" name="clientLedger" onsubmit="setValue(event)">
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
                            <div class="col-md-3 float-start px-2 mb-1 position-relative">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Client Code <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" oninput="this.value = this.value.toUpperCase()" id="clientCode" onchange="fetchData(this, 'client_code', ['clientName'], ['client_name'], 'client_code')" size="05" maxlength="06" name="client_code"/>
                                <i class="fa fa-binoculars icn-vw" aria-hidden="true" onclick="showData('client_code', '<?= $displayId['client_help_id'] ?>', 'clientCode', ['clientName'], ['client_name'], 'client_code')" title="View" data-toggle="modal" data-target="#lookup"></i>
                            </div>
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name</label>					
                                <input type="text" class="form-control w-100 float-start" name="client_name" id="clientName" readonly/>
                            </div>				
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                                <select class="form-select w-100 float-start" name="output_type" required >
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
                $lno = 0 ;
                $page = 0 ;
                $cnt = 1 ;
                $transrow = isset($sele_qry[$cnt-1]) ? $sele_qry[$cnt-1] : '' ;
                $count = $params['count'];
                $lno = 0 ;
                $page = 0 ;
                $prev_rec_ind = '';
                $prev_dr_amt  = 0.00;
                $prev_cr_amt  = 0.00;
                $close_blnc   = 0.00;
                while($cnt <= $count)
                {
                if($lno == 0 || $lno > $params['lines_per_page'])
                {
                    $page = $page + 1 ;
                    if($lno > $params['lines_per_page'])
                    {
                ?>
                        </table>
                        </td>
                    </tr>
                    </table>
                    <BR CLASS="pageEnd"> 
                <?php
                    }
                ?>
                <table width="950" border="0" cellpadding="0" cellspacing="0" class="table border-0">
                    <tr>
                    <td colspan="13">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                            <table width="100%" border="0" cellpadding="1" cellspacing="0">
                                <tr>
                                <td>
                                <table width="100%" cellspacing="0" cellpadding="0" valign="top">
                                    <tr>
                                        <td colspan="13" align="center" style="font-family: Arial, Helvetica, sans-serif; font-weight : bold;font-size: 14px; color: #000000;"><?php echo 'SINHA AND COMPANY'; ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="13" align="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="13" align="center" style="font-family: Arial, Helvetica, sans-serif; font-weight : bold;font-size: 14px; color: #000000;"><?php echo $params['display_heading']; ?></td>
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
                                    <tr><td colspan="13">&nbsp;</td></tr>
                                </table>         
                                </td>
                                </tr>
                            </table>          
                            </td>
                        </tr>
                        </table>
                    </tr>
                    <tr class="fs-14">
                        <th width="" align="left" class="px-2 py-3">Doc Date</th>
                        <th width="" class="px-2 py-3">&nbsp;</th>
                        <th width="" class="px-2 py-3" align="left">Doc No</th>
                        <th width="" class="px-2 py-3">&nbsp;</th>
                        <th width="" class="px-2 py-3" align="left">Instr.No.</th>
                        <th width="" class="px-2 py-3">&nbsp;</th>
                        <th width="" class="px-2 py-3" align="left">Date</th>
                        <th width="" class="px-2 py-3">&nbsp;</th>
                        <th width="" class="px-2 py-3" align="left">Narration</th>
                        <th width="" class="px-2 py-3">&nbsp;</th>
                        <th width="" class="px-2 py-3" align="right">Debit</th>
                        <th width="" class="px-2 py-3">&nbsp;</th>
                        <th width="" class="px-2 py-3" align="right">Credit</th>
                    </tr>
                    <tr class="fs-14">
                        <td colspan="13" class="p-2"><b><?php echo $params['client_name'] ;?></b></td>
                    </tr>
                <?php
                    $lno = $lno + 11 ;
                }
                if($transrow['rec_ind'] != $prev_rec_ind)
                {
                    if($prev_rec_ind > 0)
                    {
                ?>
                            <tr class="fs-14">
                                <td colspan="13" class="p-2"><hr size="1"></td>
                            </tr>
                            <tr class="fs-14">
                                <td align="right" class="p-2" style="vertical-align:top;" colspan="9" style="background-color: #e2e6506e;">Total</td>
                                <td class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                <td align="right" class="p-2" style="vertical-align:top;" style="background-color: #e2e6506e;"><?php echo number_format($prev_dr_amt,2,'.','');?></td>
                                <td class="p-2" style="background-color: #e2e6506e;">&nbsp;</td>
                                <td align="right" class="p-2" style="vertical-align:top;" style="background-color: #e2e6506e;"><?php echo number_format($prev_cr_amt,2,'.','');?></td>
                            </tr>
                            <tr class="fs-14">
                                <td colspan="13" class="p-2"><hr size="1"></td>
                            </tr>
                <?php
                    $lno = $lno + 3 ;
                    }
                    $prev_rec_ind = $transrow['rec_ind'] ;
                    $prev_dr_amt  = $transrow['amount_dr'];
                    $prev_cr_amt  = $transrow['amount_cr'];
                }
                else
                {
                    $prev_dr_amt  = $prev_dr_amt + $transrow['amount_dr'];
                    $prev_cr_amt  = $prev_cr_amt + $transrow['amount_cr'];
                }
                ?>
                            <tr class="fs-14">
                                <td align="left" style="vertical-align:top;" class="p-2"><?php if($transrow['doc_date']!='00-00-0000') echo $transrow['doc_date']; else echo '&nbsp;';?></td>
                                <td>&nbsp;</td>
                                <td align="left" style="vertical-align:top;" class="p-2"><?php echo $transrow['doc_no']?></td>
                                <td>&nbsp;</td>
                                <td align="left" style="vertical-align:top;" class="p-2"><?php echo $transrow['instrument_no']?></td>
                                <td>&nbsp;</td>
                                <td align="left" style="vertical-align:top;" class="p-2"><?php if($transrow['instrument_dt'] != '00-00-0000') echo $transrow['instrument_dt']; echo '&nbsp;';?></td>
                                <td>&nbsp;</td>
                                <td align="left" style="vertical-align:top;" class="p-2"><?php echo $transrow['narration']?></td>
                                <td>&nbsp;</td>
                            <?php
                            if($transrow['rec_ind'] == '0')
                            {
                            ?>
                                <td align="right" style="vertical-align:top;" class="p-2"><?php if($transrow['amount_dr'] > 0) echo number_format($transrow['amount_dr'],2,'.','');?></td>
                                <td class="p-2">&nbsp;</td>
                                <td align="right" style="vertical-align:top;" class="p-2"><?php if($transrow['amount_dr'] < 0) echo number_format(abs($transrow['amount_dr']),2,'.','');?></td>
                            <?php
                            }
                            else
                            {
                            ?>
                                <td align="right" style="vertical-align:top;" class="p-2"><?php echo number_format($transrow['amount_dr'],2,'.','');?></td>
                                <td class="p-2">&nbsp;</td>
                                <td align="right" style="vertical-align:top;" class="p-2"><?php echo number_format($transrow['amount_cr'],2,'.','');?></td>
                            <?php
                            }
                            ?>
                            </tr>
                            <?php
                            $close_blnc = $close_blnc + $transrow['amount_dr'] - $transrow['amount_cr'] ;
                            $transrow = ($cnt < $count) ? $sele_qry[$cnt] : $transrow;  
                            $lno = $lno+1;
                            $cnt = $cnt + 1;
                }
                ?>
                            <tr class="fs-14">
                                <td colspan="13" class="p-2"><hr size="1"></td>
                            </tr>
                            <tr class="fs-14">
                                <td align="right"  class="p-2" colspan="9" style="background-color: #e2e6506e;">Total</td>
                                <td style="background-color: #e2e6506e;">&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #e2e6506e;"><?php echo number_format($prev_dr_amt,2,'.','');?></td>
                                <td style="background-color: #e2e6506e;">&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #e2e6506e;"><?php echo number_format($prev_cr_amt,2,'.','');?></td>
                            </tr>
                            <tr class="fs-14">
                                <td colspan="13" class="p-2">&nbsp;</td>
                            </tr>
                            <tr class="fs-14">
                                <td align="right" class="p-2" colspan="9" style="background-color: #91d6ec6e;">Closing</td>
                                <td class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #91d6ec6e;"><?php if($close_blnc > 0) echo number_format($close_blnc,2,'.','');?></td>
                                <td class="p-2" style="background-color: #91d6ec6e;">&nbsp;</td>
                                <td align="right" class="p-2" style="background-color: #91d6ec6e;"><?php if($close_blnc < 0) echo number_format(abs($close_blnc),2,'.','');?></td>
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
        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.clientLedger.date_from.value.substring(6,10)+document.clientLedger.date_from.value.substring(3,5)+document.clientLedger.date_from.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period From Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.clientLedger.date_from.focus()}, 500) });
            return false;
        }
        else if (document.clientLedger.date_to.value.substring(6,10)+document.clientLedger.date_to.value.substring(3,5)+document.clientLedger.date_to.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Period To Date must be less than or equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.clientLedger.date_to.focus()}, 500) });
            return false;
        }
        else if (document.clientLedger.date_from.value.substring(6,10)+document.clientLedger.date_from.value.substring(3,5)+document.clientLedger.date_from.value.substring(0,2)>document.clientLedger.date_to.value.substring(6,10)+document.clientLedger.date_to.value.substring(3,5)+document.clientLedger.date_to.value.substring(0,2)) {
            Swal.fire({ text: 'Period To Date must be less than Period From Date' }).then((result) => { setTimeout(() => {document.clientLedger.date_to.focus()}, 500) });
            return false;
        } else if (document.clientLedger.client_code.value == '') {
            Swal.fire({ text: 'Please select Client Code !!!' }).then((result) => { setTimeout(() => {document.clientLedger.client_code.focus()}, 500) });
            return false;
        }    
        
        document.clientLedger.submit();
    }
</script>
<?= $this->endSection() ?>