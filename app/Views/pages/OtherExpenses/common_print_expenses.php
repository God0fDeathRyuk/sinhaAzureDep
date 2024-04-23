<form action="<?= base_url($data['requested_url']) ?>" method="post">
    <input type="hidden" name="serial_no" value="<?= $serial_no ?>"/>
    <input type="hidden" name="voucher_serial_no" value="<?= $serial_no ?>"/>
    <input type="hidden" name="user_option" value="<?= $user_option ?>"/>
    <button class="text-decoration-none d-block float-end btn btn-dark me-5"> Back </button>
    <?php 
    foreach($params as $key => $param) { ?>
        <div class="tblDv">
            <table width="750" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto mrgLft21">
            <tr>
                <td width="200" valign="top">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                    <td height="30" colspan="4" class="GroupDetail_band_portrait"><span class="ReportTitle_portrait"><img src="<?= base_url('public/assets/img/logo.jpg') ?>" width="155" height="65" border="0"></span></td>
                    </tr>
                </table>
                <table width="100%" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000">
                <tr>
                    <td width="40%" height="30" class="GroupDetail_band_portrait  border-blk">&nbsp;Srl.No</td>
                    <td width="60%" height="30" class="ReportColumn_portrait  border-blk">&nbsp;<?php echo $param['serial_no'];?></td>
                    </tr>
                    <tr>
                    <td width="40%" height="30" class="GroupDetail_band_portrait  border-blk">&nbsp;Date</td>
                    <td width="60%" height="30" class="ReportColumn_portrait  border-blk">&nbsp;<?php echo $param['entry_date'];?></td>
                    </tr>
                </table>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                    <td height="35" valign="bottom" class="ReportTitle_portrait">&nbsp;Form No.AC - 3</td>
                    </tr>

                </table>
                </td>
                <td width="350" valign="top">
                <table width="100%" cellpadding="0" cellspacing="0" class="fntSml">
                    <tr>
                    <td width="74%" height="15" align="center" class="ReportTitle_portrait_company">SINHA AND COMPANY</td>
                    </tr>
                    <tr>
                    <td height="15" class="ReportTitle_portrait" align="center"><?php echo $param['branch_addr1']?></td>
                    </tr>
                    <tr>
                    <td height="15" class="ReportTitle_portrait" align="center"><?php echo $param['branch_addr2']?></td>
                    </tr>
                    <tr>
                    <td height="15" class="ReportTitle_portrait" align="center"><?php echo $param['branch_addr3']?></td>
                    </tr>
                    <tr>
                    <td height="15" class="ReportTitle_portrait" align="center"><?php echo $param['branch_addr4']?></td>
                    </tr>
                </table>
                <table width="100%">
                    <tr>
                    <td class="ReportTitle_portrait" align="center" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                    <td class="ReportTitle_portrait" align="center" valign="top"><b><u>Payment Voucher</u></b></td>
                    </tr>

                </table>
                </td>
                <td width="200" valign="top">
                <table width="100%" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000">
                    <tr>
                    <td width="40%" height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Voucher No</td>
                    <td width="60%" height="30" class="ReportColumn_portrait border-blk">&nbsp;</td>
                    </tr>
                    <tr>
                    <td height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Date</td>
                    <td height="30" class="ReportColumn_portrait border-blk">&nbsp;</td>
                    </tr>
                    <tr>
                    <td height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Daybook</td>
                    <td height="30" class="ReportColumn_portrait border-blk">&nbsp;&nbsp;<?php if($param['trans_type'] == 'CB') { echo $param['daybook_code'] ; } else { echo "&nbsp;" ; } ?></td>
                    </tr>
                    <tr>
                    <td height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Cheque No.</td>
                    <td height="30" class="ReportColumn_portrait border-blk">&nbsp;&nbsp;
                    <?php if($param['trans_type'] == 'CB') { echo $param['inst_no'] ; } else { echo "&nbsp;" ; } ?></td>
                    </tr>
                    <tr>
                    <td height="30" class="GroupDetail_band_portrait border-blk">&nbsp;Cheque Dt.</td>
                    <td height="30" class="ReportColumn_portrait border-blk">&nbsp;&nbsp;<?php if($param['trans_type'] == 'CB' && $param['daybook_code'] != '10') { echo $param['inst_dt'] ; } else { echo "&nbsp;" ; } ?></td>
                    </tr>
                    <tr>
                    <td height="30" colspan="2" class="GroupDetail_band_portrait border-blk">&nbsp;Trns Type - <?php echo $param['trans_type'];?>&nbsp; Party - <?php echo $param['payee']; ?>&nbsp; <?php echo $param['payment_type']; ?> </td>
                    </tr>
                </table>
            </tr>
            </table>            
            <!-- end of header part -->
            <!-- column heading -->
            <table width="750" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="m-0 m-auto">
                <tr>
                    <td width="100%">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                        <td width="" colspan="10" class="ReportColumn_portrait" align="center">&lt;============== Debit To =============&gt;</td>
                        </tr>
                        <tr>
                        <td width="25">&nbsp;</td>
                        <td width="275" class="ReportColumn_portrait">&nbsp;Narration</td>
                        <td width="35"  class="ReportColumn_portrait">&nbsp;</td>
                        <td width="50"  class="ReportColumn_portrait">&nbsp;Main</td>
                        <td width="50"  class="ReportColumn_portrait">&nbsp;Sub</td>
                        <td width="50"  class="ReportColumn_portrait">&nbsp;Matter</td>
                        <td width="50"  class="ReportColumn_portrait">&nbsp;Client</td>
                        <td width="50"  class="ReportColumn_portrait">&nbsp;Expn</td>
                        <td width="100" class="ReportColumn_portrait" align="right">Dr. Amt.&nbsp;(<img src="<?= base_url('public/assets/img/rupee.jpg') ?>"  height="8" border="0">)&nbsp;</td>
                        <td width="100" class="ReportColumn_portrait" align="right">Cr. Amt.&nbsp;(<img src="<?= base_url('public/assets/img/rupee.jpg') ?>" height="8" border="0">)&nbsp;</td>
                        </tr>
                    </table>
                    </td>
                </tr>
            </table>
            <!-- end of column heading -->
            <!-- detail rows -->
            <table width="750" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto">
                <tr>
                    <td width="25"  class="cellheight_1">&nbsp;</td>
                    <td width="275" class="cellheight_1">&nbsp;</td>
                    <td width="25"  class="cellheight_1">&nbsp;</td>
                    <td width="50"  class="cellheight_1">&nbsp;</td>
                    <td width="50"  class="cellheight_1">&nbsp;</td>
                    <td width="50"  class="cellheight_1">&nbsp;</td>
                    <td width="50"  class="cellheight_1">&nbsp;</td>
                    <td width="50"  class="cellheight_1">&nbsp;</td>
                    <td width="100" class="cellheight_1">&nbsp;</td>
                    <td width="100" class="cellheight_1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="GroupDetail_band_portrait" align="right">[<b><?php echo $param['cnt']?></b>]&nbsp;&nbsp;</td>
                    <td class="GroupDetail_band_portrait" rowspan="2" valign="top">&nbsp;<?php echo $param['narration']?></td>
                    <td >&nbsp;</td>
                    <td class="ReportColumn_portrait">&nbsp;<u><?php echo $param['main_ac_code']?></u></td>
                    <td class="GroupDetail_band_portrait">&nbsp;<u><?php echo $param['sub_ac_code']?></u></td>
                    <td class="GroupDetail_band_portrait">&nbsp;<u><?php echo $param['matter_code']?></u></td>
                    <td class="GroupDetail_band_portrait">&nbsp;<u><?php echo $param['client_code']?></u></td>
                    <td class="GroupDetail_band_portrait">&nbsp;<u><?php echo $param['expense_code']?></u></td>
                    <td class="GroupDetail_band_portrait" align="right"><?php if($param['dr_cr_ind'] == 'D' && $param['gross_amount']>0) echo number_format($param['gross_amount'],2,'.','');?>&nbsp;</td>
                    <td class="GroupDetail_band_portrait" align="right"><?php if($param['dr_cr_ind'] == 'C' && $param['gross_amount']>0) echo number_format($param['gross_amount'],2,'.','');?>&nbsp;</td>
                </tr>
                <tr>
                    <td class="GroupDetail_band_portrait" align="right" height="30">&nbsp;</td>
                    <!-- second line of narration-->
                    <td >&nbsp;</td>
                    <td class="ReportColumn_portrait" colspan="7" height="30" valign="top">&nbsp;<?php echo $param['main_ac_desc'] . $param['sub_ac_desc'];?></td>
                </tr>
            </table>
            <!-- end of detail rows -->
            <!-- total band -->
            <table width="750" cellpadding="0" cellspacing="0" border="1" bordercolor="#000000" class="m-0 m-auto">
                <tr height="50">
                    <td width="500" class="ReportColumn_portrait" rowspan="3">&nbsp;<?php echo $param['hdr_net_riw'] ;?></td>
                    <td width="75"  class="ReportColumn_portrait" align="right">Total&nbsp;<img src="<?= base_url('public/assets/img/rupee.jpg') ?>" height="8" border="0">&nbsp;</td>
                    <td width="100" class="ReportColumn_portrait" align="right"><?php echo number_format($param['hdr_net_amount'],2,'.','');?>&nbsp;</td>
                    <td width="100" class="ReportColumn_portrait" align="right">&nbsp;</td>
                </tr>
            </table>
            <!-- end of total band -->
            <!-- footer band -->
            <table width="750" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto">
                <tr>
                    <td width="100" height="30" class="cellheight_1">&nbsp;</td>
                    <td width="650" height="30" class="cellheight_1">&nbsp;</td>
                </tr>
                <tr>
                    <td height="30" class="GroupDetail_band_portrait">&nbsp;Pay To:</td>
                    <td height="30" class="GroupDetail_band_portrait" colspan="5">&nbsp;<b><?php echo $param['payee_payer_name'];?></b></td>
                </tr>
                <tr>
                    <td height="37" class="GroupDetail_band_portrait" valign="top">&nbsp;Remarks:</td>
                    <td height="37" class="GroupDetail_band_portrait" colspan="5" valign="top">&nbsp;<?php echo $param['remarks'];?> &nbsp;<strong><?php if($param['ref_advance_serial_no'] != '') echo 'ADV SL#: '.$param['ref_advance_serial_no'];?></strong></td>
                </tr>
                <tr>
                    <td colspan="6" class="cellheight_1"><hr size="1"></td>
                </tr>
            </table>
            <!-- end of footer band -->
            <!-- signature band -->
            <table width="750" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto">
                <tr>
                    <td width="225" height="40" class="GroupDetail_band_portrait" align="center" valign="bottom">____<u><?php echo $param['hdr_user']?></u>_____</td>
                    <td width="150" height="40" class="GroupDetail_band_portrait" align="center" valign="bottom">____________________</td>
                    <td width="150" height="40" class="GroupDetail_band_portrait" align="center" valign="bottom">____________________</td>
                    <td width="225" height="40" class="GroupDetail_band_portrait" align="center" valign="bottom">_________________________</td>
                </tr>
                <tr>
                    <td class="GroupDetail_band_portrait" align="center" valign="top">Prepared By</td>
                    <td class="GroupDetail_band_portrait" align="center" valign="top">Checked By</td>
                    <td class="GroupDetail_band_portrait" align="center" valign="top">Passed By</td>
                    <td class="GroupDetail_band_portrait" align="center" valign="top">Signature of the Payee</td>
                </tr>
            </table>
        </div>
    <?php } ?>
</form>