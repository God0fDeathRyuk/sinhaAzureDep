<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if((!isset($params))) { ?> 
    <main id="main" class="main">
        
        <?php if (session()->getFlashdata('message') !== NULL) : ?>
            <div id="alertMsg">
                <div class="alert alert-info d-flex align-items-center alert-dismissible fade show mt-3" role="alert">
                <div> <b> <?= session()->getFlashdata('message') ?> </b> </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="pagetitle">
            <h1>Acknowledgement Slip</h1>
        </div><!-- End Page Title -->
        <form action="" method="post" id="acknowledgement_slip" name="acknowledgement_slip" onsubmit="setValue(event)">
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
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Date <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="received_date" value="<?= date('d-m-Y')?>" onBlur="make_date(this)"  required />
                                <input type="hidden" name="current_date" value="<?= date('d-m-Y')?>"/>
                            </div>		
                            <div class="col-md-4 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Received From <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="received_from_name" required />
                            </div>
                            <div class="col-md-4 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Received By <strong class="text-danger">*</strong></label>
                                <select class="form-select" name="instrument_type" onChange="myInstrumentType()" required >
                                    <option value="">--Select--</option>  
                                    <option value="C">Cash</option>
                                    <option value="Q">Cheque</option>
                                    <option value="D">Draft</option>
                                </select>
                            </div>
                            <div class="col-md-8 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Instrument No/Dt/Bank <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-25 float-start" name="instrument_no" required />
                                <input type="text" class="form-control w-25 float-start ms-2" name="instrument_dt" onBlur="make_date(this)" required />
                                <input type="text" class="form-control w46 float-start ms-2" name="instrument_bk" required />
                            </div>
                            <div class="col-md-4 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Received Amount <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="received_amount" required />
                            </div>
                            <div class="col-md-8 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">On Account Of <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="narration" required />
                            </div>
                            <div class="col-md-4 float-start px-2 mb-3">
                                <label class="d-inline-block w-100 mb-2 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                                <select class="form-select w-100 float-start" name="output_type" required >
                                    <option value="Report">View Report</option>
                                    <option value="Pdf" >Download PDF</option>
                                </select>
                            </div>	
                            <button type="submit" class="btn btn-primary cstmBtn mt-2 ms-2">Proceed</button>				
                            <button type="reset" class="btn btn-primary cstmBtn btn-cncl mt-2 ms-2">Reset</button>
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
            $rowcnt = 1 ;
            while ($rowcnt <= 2)
            {
        ?>
                <table width="750" align="center" border="0" cellspacing="0" cellpadding="0" class="bg-white">
                    <tr>
                        <td class="px-2">    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <td width="6%">&nbsp;</td>
                                <td width="12%">&nbsp;</td>
                                <td width="49%">&nbsp;</td>
                                <td width="33%">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="fw-bold text-dark" colspan="4" align="center" style="font-size:14px"><b><?php echo strtoupper('sinha and company')?></b></td>
                                </tr>
                                <tr>
                                <td class="fw-bold text-dark" colspan="4" align="center" style="font-size:14px"><b><?php echo strtoupper($params['branch_addr1'])?></b></td>
                                </tr>
                                <tr>
                                <td class="fw-bold text-dark" colspan="4" align="center" style="font-size:14px"><b><?php echo strtoupper($params['branch_addr2'])?></b></td>
                                </tr>
                                <tr>
                                <td class="fw-bold text-dark" colspan="4" align="center" style="font-size:14px"><b><?php echo $params['branch_addr3']?></b></td>
                                </tr>
                                <tr>
                                <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_label_text" colspan="3" style="font-size:14px" align="left">&nbsp;<b><u>ACKNOWLEDGEMENT SLIP</u></b></td>
                                <td class="report_label_text" colspan="1" style="font-size:12px" align="right">&nbsp;Date :&nbsp;<?php echo date('d-m-Y')?>&nbsp;&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="4" height="15"><hr size="1" color="#CCCCCC" noshade></td>
                                </tr>
                                <tr>
                                <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" colspan="2">&nbsp;Received From</td>
                                <td height="25" class="report_label_text" colspan="2">&nbsp;<b><?php echo $params['received_from'] ; ?></b></td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" colspan="2">&nbsp;Rupees</td>
                                <td height="25" class="report_label_text" colspan="2">&nbsp;<b><?php echo $params['received_amt_riw'] ; ?></b></td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" colspan="2">&nbsp;By</td>
                                <td height="25" class="report_label_text" colspan="2">&nbsp;<b><?php echo $params['instrument_desc'] ; ?><?php echo $params['instrument_details'] ; ?></b></td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" colspan="2">&nbsp;On account of</td>
                                <td height="25" class="report_label_text" colspan="2">&nbsp;<b><?php echo $params['narration'] ; ?></b></td>
                                </tr>
                                <tr>
                                <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" colspan="3" style="font-size:14px"><b>Rs.&nbsp;<?php echo number_format($params['received_amt'],2,'.','') ; ?></b></td>
                                <td height="25" class="report_label_text" colspan="1" align="center"><b>For&nbsp;<?php echo 'SINHA AND COMPANY' ; ?></td>
                                </tr>
                                <tr>
                                <td height="60" class="report_detail_all" >&nbsp;</td>
                                <td height="60" class="report_detail_none">&nbsp;</td>
                                <td height="60" class="report_detail_none">&nbsp;</td>
                                <td height="60" class="report_detail_none">&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_detail_none">&nbsp;</td>
                                <td class="report_detail_none">&nbsp;</td>
                                <td class="report_detail_none">&nbsp;</td>
                                <td style="border-top:1px solid #000;" align="center">&nbsp;(Cashier)</td>
                                </tr>
                                <tr>
                                <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="4" class="report_detail_none" align="center"><u>(THE RECEIPT IS VALID SUBJECT TO ENCASHMENT OF CHEQUE)</u></td>
                                </tr>
                                <tr>
                                <td height="30" colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="4" height="15"><hr size="2" color="#CCCCCC" noshade></td>
                                </tr>
                            </table>
                        </td>    
                    </tr>
                </table> 
        <?php
            $rowcnt = $rowcnt + 1 ;
            }
        ?>
    </main>
<?php } ?>
<script>
    function myInstrumentType() {
        var itype = document.acknowledgement_slip.instrument_type.value ;
        
        document.acknowledgement_slip.instrument_no.value  = '' ;
        document.acknowledgement_slip.instrument_dt.value  = '' ;
        document.acknowledgement_slip.instrument_bk.value  = '' ;

        if (itype == 'C') {
        document.acknowledgement_slip.instrument_no.disabled  = true ;
        document.acknowledgement_slip.instrument_dt.disabled  = true ;
        document.acknowledgement_slip.instrument_bk.disabled  = true ;
        document.acknowledgement_slip.received_amount.focus() ; 
        } else {
        document.acknowledgement_slip.instrument_no.disabled  = false ;
        document.acknowledgement_slip.instrument_dt.disabled  = false ;
        document.acknowledgement_slip.instrument_bk.disabled  = false ;
        document.acknowledgement_slip.instrument_no.focus() ; 
        }  
    }
    function setValue(e) {
        e.preventDefault();
        var pname    = document.acknowledgement_slip.received_from_name.value.replace('&','_|_').replace('&','_|_').replace('&','_|_').replace('&','_|_') ; 
            pname   = pname.replace("'",'-|-').replace("'",'-|-').replace("'",'-|-').replace("'",'-|-');
        var narr     = document.acknowledgement_slip.narration.value.replace('&','_|_').replace('&','_|_').replace('&','_|_').replace('&','_|_') ;
            narr     = narr.replace("'",'-|-').replace("'",'-|-').replace("'",'-|-').replace("'",'-|-');

        var today_date = '<?php echo substr(date('d-m-Y'),6,4).substr(date('d-m-Y'),3,2).substr(date('d-m-Y'),0,2);?>';

        if (document.acknowledgement_slip.received_date.value.substring(6,10)+document.acknowledgement_slip.received_date.value.substring(3,5)+document.acknowledgement_slip.received_date.value.substring(0,2) > today_date) {
            Swal.fire({ text: 'Received Date must be less than equal to Current Date !!!' }).then((result) => { setTimeout(() => {document.acknowledgement_slip.received_date.focus()}, 500) });
            return false;
        }
        else if (document.acknowledgement_slip.instrument_type.value != 'C' && document.acknowledgement_slip.instrument_no.value == '') {
            Swal.fire({ text: 'Please select Instrument No !!!' }).then((result) => { setTimeout(() => {document.acknowledgement_slip.instrument_no.focus()}, 500) });
            return false;
        }    
        else if (document.acknowledgement_slip.instrument_type.value != 'C' && document.acknowledgement_slip.instrument_dt.value == '')
        {
            Swal.fire({ text: 'Please select Instrument Date !!!' }).then((result) => { setTimeout(() => {document.acknowledgement_slip.instrument_dt.focus()}, 500) });
            return false;
        }    
        else if (document.acknowledgement_slip.instrument_type.value != 'C' && document.acknowledgement_slip.instrument_bk.value == '')
        {
            Swal.fire({ text: 'Please select Instrument Bank !!!' }).then((result) => { setTimeout(() => {document.acknowledgement_slip.instrument_bk.focus()}, 500) });
            return false;
        }    
        document.acknowledgement_slip.received_from_name.value = pname ;
        document.acknowledgement_slip.narration.value = narr ;
        document.acknowledgement_slip.submit();
    }
</script>
<?= $this->endSection() ?>
