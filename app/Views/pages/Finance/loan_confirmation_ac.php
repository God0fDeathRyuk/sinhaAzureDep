<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>

<?php $renderFlag = isset($report_type) ? ($report_type == 'Pdf') ? false : true : true; ?>

<?php if ($renderFlag) : ?>
<?php echo view('partials/modelForm', ['model' => 'lookup']); ?>
<?php endif; ?>
<script>
    // document.getElementById('sidebar').style.display = "none";
    // document.getElementById('burgerMenu').style.display = "none";
    document.getElementById('header').classList.add = "d-none";
    document.getElementById('footer').classList.add = "d-none";
</script>
<?php if ($renderFlag) : ?>
<main id="main" class="main"style="margin-top:90px;">
<?php endif; ?>
    <form method="post" target="_blank">
        <?php if ($renderFlag) : ?>
        <div class="d-block text-end Nwprntbtn">    
            <button type="submit" class="btn btn-primary cstmBtn mt-0 p-2 pdfDnld" value="Pdf">
                <img src="<?= base_url('/public/assets/img/pdf.png') ?>" title="Download PDF" class="" alt="Download PDF">
            </button>
            <button type="button" onclick="window.print(); window.close();" class="btn btn-primary cstmBtn mt-0 p-2 pdfDnld">
                <img src="<?= base_url('/public/assets/img/prnt.png') ?>" title="Print" class="" alt="Print">
            </button>
            <!--<a href="<?= base_url(session()->requested_end_menu_url) ?>" class="btn btn-primary cstmBtn mt-0 p-2 pdfDnld bg-dark">-->
            <!--    <img src="<?= base_url('/public/assets/img/bck.png') ?>" title="Back" class="" alt="Back">-->
            <!--</a>-->
            <input type="hidden" name="output_type" value="Pdf" />
        </div>
        <?php endif; ?>
        <section class="section dashboard NwprntSec" id="printSection">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="frms-sec d-block w-100 bg-white p-3">
                        
                        <div class="tbl-sec d-block w-100 bg-white p-2 position-relative mt-2">
                            <table class="table border-0 pb-0">
                                <tr>
                                    <td class="border-0" style="width:50%;">To</td>
                                    <td class="border-0" style="width:50%;">From</td>
                                </tr>
                                <tr>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;font-weight:bold;" name="to" placeholder="Limit 212 Characters"><?= isset($params['to']) ? $params['to'] : '' ?></textarea>
                                    </td>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;margin-left:20px;font-weight:bold;" name="from"><?= isset($params['from']) ? $params['from'] : 'Paritosh Sinha' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;margin-left:20px;" name="extra_text1" placeholder="Limit 212 Characters"><?= isset($params['extra_text1']) ? $params['extra_text1'] : '' ?></textarea>
                                    </td>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;margin-left:20px;" name="extra_text2" placeholder="Limit 212 Characters"><?= isset($params['extra_text2']) ? $params['extra_text2'] : '5, Kiran Shankar Roy Road' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;margin-left:20px;" name="extra_text3" placeholder="Limit 212 Characters"><?= isset($params['extra_text3']) ? $params['extra_text3'] : '' ?></textarea>
                                    </td>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;margin-left:20px;" name="extra_text4" placeholder="Limit 212 Characters"><?= isset($params['extra_text4']) ? $params['extra_text4'] : "Kolkata - 700 001" ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;margin-left:20px;" name="extra_text5" placeholder="Limit 212 Characters"><?= isset($params['extra_text5']) ? $params['extra_text5'] : '' ?></textarea>
                                    </td>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;margin-left:20px;" name="extra_text6" placeholder="Limit 212 Characters"><?= isset($params['extra_text6']) ? $params['extra_text6'] : '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;margin-left:20px;" name="extra_text7" placeholder="Limit 212 Characters"><?= isset($params['extra_text7']) ? $params['extra_text7'] : '' ?></textarea>
                                    </td>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;margin-left:20px;" name="extra_text8" placeholder="Limit 212 Characters"><?= isset($params['extra_text8']) ? $params['extra_text8'] : '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <input type="text" class="form-control border-0" style="padding:0;" Value="Dear Sir/Madam," readonly/>
                                    </td>
                                    <td class="border-0" style="width:50%;padding-left:0;">
                                        <span style="display:block;float:left;margin-right:10px;">Date: </span>
                                        <input type="text" class="form-control border-0" style="padding:0;display: block;float: left;width: auto" placeholder="" name="date" value="<?= isset($params['date']) ? $params['date'] : '' ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 pt-0 pb-0" style="width:50%;padding-left:0;text-align:center;" colspan="2">
                                        <span style="font-weight:bold;">Sub : Confirmation of Accounts</span>
                                        <textarea rows="1" class="form-control border-0 m-0 m-auto" style="padding:0;width: auto"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 pt-0 pb-0" style="width:50%;padding-left:0;text-align:center;" colspan="2">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="border-0 pt-0 pb-0" style="width:50%;padding-left:0;text-align:center;" colspan="2">
                                        <textarea rows="1" class="form-control border-0" style="padding:0;" value="" readonly>Given below is the details of your Accounts as standing in my/our Books of Accounts for the above mentioned period.</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0" style="width:50%;padding-left:0;text-align:center;" colspan="2">
                                        <textarea rows="2" class="form-control border-0" style="padding:0;" readonly>Kindly return 3 copies stating your I.T. Permanent A/c. No., duly signed and sealed, in confirmation of the same. Please note that if no reply is received from you within a fortnight, it will be assumed that you have accepted the balance shown below :</textarea>
                                    </td>
                                </tr>
                            </table>
                            <table style="border:0">
                                <tr>
                                    <td style="border:1px solid #000;border-right:0;border-left:0;padding:5px 10px;">Date</td>
                                    <td style="border:1px solid #000;border-right:0;border-left:0;padding:5px 10px;">Particulars</td>
                                    <td style="border:1px solid #000;border-right:0;border-left:0;padding:5px 10px;">Debit Amount</td>
                                    <td style="border:1px solid #000;border-right:0;padding:5px 10px;">Date</td>
                                    <td style="border:1px solid #000;border-right:0;border-left:0;padding:5px 10px;">Particulars</td>
                                    <td style="border:1px solid #000;border-right:0;padding:5px 10px;">Credit Amount</td>
                                </tr>
                                <tr>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;" placeholder="Type here" name="textarea_date1"><?= isset($params['textarea_date1']) ? $params['textarea_date1'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;" placeholder="Type here" name="Particulars1"><?= isset($params['Particulars1']) ? $params['Particulars1'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;border-right:1px solid #000;padding:5px 10px;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;" placeholder="Type here" name="textarea_dramt1"><?= isset($params['textarea_dramt1']) ? $params['textarea_dramt1'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;" placeholder="Type here" name="textarea_date2"><?= isset($params['textarea_date2']) ? $params['textarea_date2'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;" placeholder="Type here" name="Particulars2"><?= isset($params['Particulars2']) ? $params['Particulars2'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="" class="form-control border-0" style="padding:0;height:20px;" placeholder="Type here" name="textarea_cramt1"><?= isset($params['textarea_cramt1']) ? $params['textarea_cramt1'] : '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="8" class="form-control border-0" style="padding:0;" placeholder="Type here" name="textarea_date3"><?= isset($params['textarea_date3']) ? $params['textarea_date3'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="8" class="form-control border-0" style="padding:0;" placeholder="Type here" name="Particulars3"><?= isset($params['Particulars3']) ? $params['Particulars3'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;border-right:1px solid #000;padding:5px 10px;">
                                        <textarea rows="8" class="form-control border-0" style="padding:0;border-bottom:1px solid #000 !important;border-radius:0;" placeholder="Type here" name="textarea_dramt2"><?= isset($params['textarea_dramt2']) ? $params['textarea_dramt2'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="8" class="form-control border-0" style="padding:0;" placeholder="Type here" name="textarea_date4"><?= isset($params['textarea_date4']) ? $params['textarea_date4'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="8" class="form-control border-0" style="padding:0;" placeholder="Type here" name="Particulars4"><?= isset($params['Particulars4']) ? $params['Particulars4'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="10" class="form-control border-0" style="padding:0;border-bottom:1px solid #000 !important;border-radius:0;" placeholder="Type here" name="textarea_cramt2"><?= isset($params['textarea_cramt2']) ? $params['textarea_cramt2'] : '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:0;padding:5px 10px;"></td>
                                    <td style="border:0;padding:5px 10px;"></td>
                                    <td style="border:0;border-right:1px solid #000;padding:5px 10px;">
                                        <textarea rows="1" class="form-control border-0" style="padding:0;border-bottom:1px solid #000 !important;border-radius:0;" placeholder="Type here" name="field1"><?= isset($params['field1']) ? $params['field1'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;"></td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="1" class="form-control border-0" style="padding:0;border-radius:0;" name="close_balance"><?= isset($params['close_balance']) ? $params['close_balance'] : 'Closing Balance' ?></textarea>
                                    </td>
                                    <td style="border:0;padding:5px 10px;">
                                        <textarea rows="1" class="form-control border-0" style="padding:0;border-bottom:1px solid #000 !important;border-radius:0;" placeholder="Type here" name="field2"><?= isset($params['field2']) ? $params['field2'] : '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:0;border-bottom:1px solid #000;padding:5px 10px;"></td>
                                    <td style="border:0;border-bottom:1px solid #000;padding:5px 10px;"></td>
                                    <td style="border:0;border-bottom:1px solid #000;border-right:1px solid #000;padding:5px 10px;">
                                        <textarea rows="1" class="form-control border-0" style="padding:0;border-bottom:1px solid #000 !important;border-radius:0;" placeholder="Type here" name="field3"><?= isset($params['field3']) ? $params['field3'] : '' ?></textarea>
                                    </td>
                                    <td style="border:0;border-bottom:1px solid #000;padding:5px 10px;"></td>
                                    <td style="border:0;border-bottom:1px solid #000;padding:5px 10px;"></td>
                                    <td style="border:0;border-bottom:1px solid #000;padding:5px 10px;">
                                        <textarea rows="1" class="form-control border-0" style="padding:0;border-bottom:1px solid #000 !important;border-radius:0;" placeholder="Type here" name="field4"><?= isset($params['field4']) ? $params['field4'] : '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:0;padding:5px 10px;" colspan="4">I/We hereby confirm the above</td>
                                    <td style="border:0;padding:5px 10px;" colspan="2">  Yours faithfully</td>
                                </tr>
                                <tr>
                                    <td style="border:0;padding:15px 10px 5px;" colspan="4"></td>
                                    <td style="border:0;padding:15px 10px 5px;" colspan="2">
                                        <textarea class="form-control border-0" style="padding:0;height:20px;" placeholder="Limit 212 Characters" name="field5"><?= isset($params['field5']) ? $params['field5'] : '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:0;padding:15px 10px 5px;" colspan="3">I.T. PAN NO.</td>
                                    <td style="border:0;padding:15px 10px 5px;" colspan="3">
                                        <span style="display:block;float:left;margin-right:15px;">OUR I.T. PAN NO.</span>
                                        <textarea class="form-control border-0" style="padding:0;height:20px;display:block;float:left;width:275px;" placeholder="Type here" name="field6"><?= isset($params['field6']) ? $params['field6'] : 'AMBPS7643A' ?></textarea>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </section>
    </form>
<?php if ($renderFlag) : ?>
</main><!-- End #main -->
<?php endif; ?>
<?= $this->endSection() ?>
