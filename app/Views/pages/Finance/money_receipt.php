<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>

<?php if(!isset($params)) { ?>
    <main id="main" class="main">

        <div class="pagetitle">
        <h1>Money Receipt</h1>
        </div><!-- End Page Title -->

        <form action="" method="post" id="moneyReceipt" name="moneyReceipt" onsubmit="setValue(event)">
            <section class="section dashboard">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-inline-block w-100 bg-white p-3">
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Branch <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="branchCode" id="branchCode" required >
                                <?php foreach($data['branches'] as $branch) { ?>
                                    <option value="<?= $branch['branch_code'] ?>" <?= ($branch['branch_code'] == $data['branch_code']['branch_code']) ? 'selected' : '' ?>><?= $branch['branch_name'] ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="branch_code" value="">
                        </div>
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Year <strong class="text-danger">*</strong></label>
                            <select class="form-select" name="finYear" id="finYear" required >
                                <?php foreach($data['finyr_qry'] as $finyr_row) { ?>
                                    <option value="<?php echo $finyr_row['fin_year']?>" <?php if(session()->financialYear == $finyr_row['fin_year']) { echo 'selected' ; }?>><?php echo $finyr_row['fin_year']?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="fin_year" value="">
                        </div>				
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">DB <strong class="text-danger">*</strong></label>					
                            <select class="form-select" name="daybookCode" id="daybookCode" required >
                                <option value="">----Select----</option>
                                <?php foreach($data['dbook_qry'] as $dbook_row) { ?>
                                    <option value="<?php echo $dbook_row['daybook_code']?>"><?php echo $dbook_row['daybook_desc'] . ' [DB '.$dbook_row['daybook_code'].']';?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="daybook_code" value="">
                        </div>
                        <div class="col-md-8 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Voucher# <strong class="text-danger">*</strong></label>					
                            <input type="text" class="form-control w-100 float-start" name="voucher_no" id="voucherNo" onfocusout="myVoucherDetails(this)" required />
                        </div>
                        
                        
                        <div class="d-inline-block w-100 border-top pt-3 mt-2">
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Received Date <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="received_date" readonly />
                                <input type="hidden" name="ledger_serial_no" id="ledger_serial_no"/>
                                <input type="hidden" name="money_receipt_no" id="money_receipt_no"/>
                                <input type="hidden" name="money_receipt_date" id="money_receipt_date"/>
                            </div>
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Received From <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="received_from_name" readonly />
                            </div>
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Client Name <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="payee_payer_name" readonly />
                            </div>
                            <div class="col-md-12 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Received By <strong class="text-danger">*</strong></label>
                                <select class="form-select w-20 float-start" name="instrumentType" id="instrumentType" disabled >
                                    <option value="" ></option>
                                    <option value="C">Cash</option>
                                    <option value="Q">Cheque</option>
                                    <option value="D">Draft</option>
                                </select>
                                <input type="text" class="form-control w-20 float-start ms-2" name="instrument_no" readonly />
                                <input type="text" class="form-control w-20 float-start ms-2" name="instrument_dt" readonly />
                                <input type="text" class="form-control w-37 float-start ms-2" name="instrument_bk" readonly />						
                                <input type="hidden" name="instrument_type" readonly />						
                            </div>
                            
                            <div class="col-md-4 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">Received Amt <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="received_amt" readonly />
                            </div>
                            <div class="col-md-8 float-start px-2 mb-1">
                                <label class="d-inline-block w-100 mb-1 lbl-mn">On Account Of <strong class="text-danger">*</strong></label>					
                                <input type="text" class="form-control w-100 float-start" name="narration" id="narration" disabled required />
                            </div>
                        </div>		
                        <div class="col-md-4 float-start px-2 mb-1">
                            <label class="d-inline-block w-100 mb-1 lbl-mn">Output Type <strong class="text-danger">*</strong></label>
                            <select class="form-select w-100 float-start" name="output_type" required >
                                <option value="Report">View Report</option>
                                <option value="Pdf" >Download PDF</option>
                            </select>
                        </div>			
                        
                        <button type="submit" class="btn btn-primary cstmBtn mt-28 ms-2">Proceed</button>	
                        <a href="<?= base_url(session()->requested_end_menu_url) ?>" class="btn btn-primary cstmBtn mt-28 ms-2">Reset</a>			
                        <!-- <button type="reset" class="btn btn-primary cstmBtn mt-2 ms-2">Reset</button>			 -->
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
            while ($rowcnt <= 2) {
        ?>
                <table width="950" align="center" border="0" cellspacing="0" cellpadding="0" class="bg-white">
                    <tr>
                        <td class="px-2">    
                            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <td width="07%">&nbsp;</td>
                                <td width="06%">&nbsp;</td>
                                <td width="52%">&nbsp;</td>
                                <td width="15%">&nbsp;</td>
                                <td width="20%">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="fw-bold text-dark" colspan="5" align="center" style="font-size:14px"><b><?php echo strtoupper('sinha and company')?></b></td>
                                </tr>
                                <tr>
                                <td class="fw-bold text-dark" colspan="5" align="center" style="font-size:14px"><b><?php echo strtoupper($params['branch_addr1'])?></b></td>
                                </tr>
                                <tr>
                                <td class="fw-bold text-dark" colspan="5" align="center" style="font-size:14px"><b><?php echo strtoupper($params['branch_addr2'])?></b></td>
                                </tr>
                                <tr>
                                <td class="fw-bold text-dark" colspan="5" align="center" style="font-size:14px"><b><?php echo $params['branch_addr3']?></b></td>
                                </tr>
                                <tr>
                                <td colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="text-dark fw-bold" colspan="2" style="font-size:12px" align="left">&nbsp;No : <b><?php echo str_pad($params['money_receipt_no'],6,'0',STR_PAD_LEFT)?></b></td>
                                <td class="report_label_text" style="font-size:14px" align="center" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<b>RECEIPT</b></td>
                                <td class="text-dark fw-bold" colspan="1" style="font-size:12px" align="right">&nbsp;Date :&nbsp;<b><?php echo $params['money_receipt_date']?></b>&nbsp;&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="5" height="15"><hr size="1" color="#CCCCCC" noshade></td>
                                </tr>
                                <tr>
                                <td colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" style="width: 116px;display: table;" colspan="2">&nbsp;Received From</td>
                                <td height="25" class="report_label_text" colspan="3">&nbsp;<b><?php echo $params['received_from'] ; ?></b></td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" colspan="2" style="width: 116px;display: table;">&nbsp;Rupees</td>
                                <td height="25" class="report_label_text" colspan="3">&nbsp;<b><?php echo $params['received_amt_riw'] ; ?></b></td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" colspan="2" style="width: 116px;display: table;">&nbsp;By</td>
                                <td height="25" class="report_label_text" colspan="3">&nbsp;<b><?php echo $params['instrument_desc'] ; ?>&nbsp;&nbsp;&nbsp;<?php echo $params['instrument_details'] ; ?></b></td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" colspan="2" style="width: 116px;display: table;">&nbsp;On account of</td>
                                <td height="25" class="report_label_text" colspan="3">&nbsp;<b><?php echo $params['narration'] ; ?></b></td>
                                </tr>
                                <tr>
                                <td colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                <td height="25" class="report_label_text" colspan="3" style="font-size:14px"><b>Rs.&nbsp;<?php echo number_format($params['received_amount'],2,'.','') ; ?></b></td>
                                <td height="25" class="report_label_text" colspan="2" align="center"><b>For&nbsp;<?php echo 'SINHA AND COMPANY' ; ?></td>
                                </tr>
                                <tr>
                                <td height="60" class="report_detail_all" >&nbsp;</td>
                                <td height="60" class="report_detail_none">&nbsp;</td>
                                <td height="60" class="report_detail_none">&nbsp;</td>
                                <td height="60" class="report_detail_none">&nbsp;</td>
                                <td height="60" class="report_detail_none">&nbsp;</td>
                                </tr>
                                <tr>
                                <td class="report_detail_none">&nbsp;</td>
                                <td class="report_detail_none">&nbsp;</td>
                                <td class="report_detail_none">&nbsp;</td>
                                <td style="border-top:1px solid #000;" colspan="2" align="center">&nbsp;(Cashier)</td>
                                </tr>
                                <tr>
                                <td colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="5" class="report_detail_none" align="center"><u>(THE RECEIPT IS VALID SUBJECT TO ENCASHMENT OF CHEQUE)</u></td>
                                </tr>
                                <tr>
                                <td height="30" colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                <td colspan="5" height="15"><hr size="2" color="#CCCCCC" noshade></td>
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
    function myVoucherDetails(e) {
        if (e.value != '') {
            let branch_code = document.getElementById("branchCode").value;
            let fin_year = document.getElementById("finYear").value;
            let daybook_code = document.getElementById("daybookCode").value;
            
            fetch(`${baseURL}/api/VoucherDetails/${e.value}/${branch_code}/${fin_year}/${daybook_code}`)
                .then((response) => response.json())
                .then((data) => {
                    console.log('============================> ');
                    console.log(data);
                    let num_row = data.num_row;
                    if (num_row == null) {
                        e.value = '';
                        Swal.fire({
                            icon: 'info',
                            html: '<strong> Voucher Details not found ... </strong>'
                        })
                    } else {
                        document.moneyReceipt.received_date.value = data.received_date;
                        document.moneyReceipt.received_from_name.value = data.received_from_name;
                        document.moneyReceipt.payee_payer_name.value = data.payee_payer_name;
                        document.moneyReceipt.instrumentType.value = data.instrument_type;
                        document.moneyReceipt.instrument_no.value = data.instrument_no;
                        document.moneyReceipt.instrument_dt.value = data.instrument_dt;
                        document.moneyReceipt.instrument_bk.value = data.instrument_bk;
                        document.moneyReceipt.received_amt.value = data.received_amt;
                        document.moneyReceipt.ledger_serial_no.value = data.ledger_serial_no;
                        document.moneyReceipt.money_receipt_no.value = data.money_receipt_no;
                        document.moneyReceipt.money_receipt_date.value = data.money_receipt_date;

                        document.getElementById("branchCode").disabled = true;
                        document.moneyReceipt.branch_code.value = document.moneyReceipt.branchCode.value ;
                        document.getElementById("finYear").disabled = true ; 
                        document.moneyReceipt.fin_year.value = document.moneyReceipt.finYear.value ;
                        document.getElementById("daybookCode").disabled = true ;
                        document.moneyReceipt.daybook_code.value = document.moneyReceipt.daybookCode.value ;
                        document.moneyReceipt.instrument_type.value = document.moneyReceipt.instrumentType.value ;
                        document.getElementById("voucherNo").readOnly = true ; 
                        document.getElementById("narration").disabled    = false ;
                        document.moneyReceipt.narration.focus() ; 
                    }
                });
        } else {
            e.value = '';
            Swal.fire({
                icon: 'info',
                html: '<strong> No Record Found !! </strong>'
            })
        }
    }

    function setValue(e) {
        e.preventDefault();
        console.log(document.moneyReceipt);

        if (document.moneyReceipt.narration.value == '') {
            Swal.fire({ text: 'Please select On Account Of !!!' }).then((result) => { setTimeout(() => {document.moneyReceipt.narration.focus()}, 500) });
            return false;
        }

        var rcdnm = document.moneyReceipt.received_from_name.value.replace('&','_|_').replace('&','_|_').replace('&','_|_').replace('&','_|_') ;  
            rcdnm = rcdnm.replace("'",'-|-').replace("'",'-|-').replace("'",'-|-').replace("'",'-|-');
        var pname = document.moneyReceipt.payee_payer_name.value.replace('&','_|_').replace('&','_|_').replace('&','_|_').replace('&','_|_') ;    
            pname = pname.replace("'",'-|-').replace("'",'-|-').replace("'",'-|-').replace("'",'-|-');
        var narr  = document.moneyReceipt.narration.value.replace('&','_|_').replace('&','_|_').replace('&','_|_').replace('&','_|_').replace('&','_|_').replace('&','_|_') ;
            narr  = narr.replace("'",'-|-').replace("'",'-|-').replace("'",'-|-').replace("'",'-|-');

        document.moneyReceipt.received_from_name.value = rcdnm;
        document.moneyReceipt.payee_payer_name.value = pname;
        document.moneyReceipt.narration.value = narr;
        document.moneyReceipt.money_receipt_date.value = document.moneyReceipt.received_date.value ;

        document.moneyReceipt.submit();
    }
</script>
<?= $this->endSection() ?>