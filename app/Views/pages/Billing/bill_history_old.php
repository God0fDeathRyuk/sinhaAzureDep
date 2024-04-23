<?php $page_no = 1 ;?>
<table width="700"  cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="cellheight_1" width="075">&nbsp;</td>
          <td class="cellheight_1" width="075">&nbsp;</td>
          <td class="cellheight_1" width="350">&nbsp;</td>
          <td class="cellheight_1" width="100">&nbsp;</td>
          <td class="cellheight_1" width="050">&nbsp;</td>
          <td class="cellheight_1" width="050">&nbsp;</td>
        </tr>
        <tr>
          <td height="15" class="ReportTitle_portrait" colspan="6" align="center">Sinha and Company</td>
        </tr>
        <tr>
          <td class="GroupDetail_band_portrait">&nbsp;Serial No.</td>
          <td class="ReportColumn_portrait">&nbsp;<?= $hdr_row['serial_no']?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="GroupDetail_band_portrait">&nbsp;Date</td>
          <td class="GroupDetail_band_portrait">&nbsp;<?= $hdr_row['bill_date'] ?></td>
          <td class="ReportTitle_portrait" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($hdr_row['status_code'] == 'X') {echo 'Cancelled Draft Bill';} else {echo 'Draft Bill' ;}?></td>
          <td>&nbsp;</td>
          <td class="GroupDetail_band_portrait" align="right">Page :&nbsp;</td>
          <td class="ReportColumn_portrait" align="right"><?= $page_no;?>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6"><hr size="1" color="#CCCCCC" noshade="noshade"></td>
        </tr>
      </table>
      <table width="700" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <!-- client detail -->
          <td width="350" height="180" valign="top">
            <table width="100%"  cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td height="15" class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;">&nbsp;<?= $bill_data['client_name']?></td>
              </tr>
              <tr>
                <td height="15" class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;">&nbsp;<?= $cadr_row['address_line_1']?></td>
              </tr>
              <?php if(!empty($cadr_row['address_line_2'])) { ?>              
              <tr>
                <td height="15" class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;">&nbsp;<?= $cadr_row['address_line_2']?></td>
              </tr>
              <?php } if(!empty($cadr_row['address_line_3'])) { ?>              
              <tr>
                <td height="15" class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;">&nbsp;<?= $cadr_row['address_line_3']?></td>
              </tr>
              <?php } if(!empty($cadr_row['address_line_4'])) { ?>              
              <tr>
                <td height="15" class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;">&nbsp;<?= $cadr_row['address_line_4']?></td>
              </tr>
              <?php } ?>              
              <tr>
                <td height="15" class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;">&nbsp;<?= $cadr_row['city'] .' '.$cadr_row['pin_code']?></td>
              </tr>
              <tr>
                <td height="90" class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;">&nbsp;<?= 'Attn. : '.$bill_data['attention_name']?></td>
              </tr>
            </table>
          </td>
          <!-- end of client detail -->

          <!-- ref and subj -->
          <td width="350">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td height="20" width="06%" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font:Courier; font-family:Courier;"><?= 'Re : '?></td>
                <td height="20" width="94%" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font:Courier; font-family:Courier;"><?= $bill_data['matter_name']?></td>
              </tr>
              <tr>
			    <td>&nbsp;</td>
                <td height="70" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font:Courier; font-family:Courier;"><?= '     ' . $bill_data['other_case_desc']?></td>
              </tr>
              <tr>
                <td height="15" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font:Courier; font-family:Courier;"><?php if($hdr_row['source_code'] == 'C') { echo ' ' ; } else { echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' ; }?></td>
                <td height="15" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font:Courier; font-family:Courier;"><?= stripslashes($hdr_row['reference_desc'])?></td>
              </tr>
              <tr>
			    <td class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font:Courier; font-family:Courier;">Sub :&nbsp;</td>
                <td height="75" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; font:Courier; font-family:Courier;"><?php echo stripslashes($hdr_row['subject_desc'])?></td>
              </tr>
            </table>
          </td>
          <!-- end of ref and subj -->
        </tr>
      </table>
      <table width="700" cellpadding="0" cellspacing="0" border="1" bordercolor="#eeeeee" bordercolordark="#FFFFFF" bordercolorlight="#FFFFFF">
      <tr>
      <td>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="GroupDetail_band_portrait" width="100">&nbsp;&nbsp;&nbsp;<b>Date</b></td>
          <td class="GroupDetail_band_portrait" width="400">&nbsp;&nbsp;&nbsp;&nbsp;<b>Details</b></td>
          <td class="GroupDetail_band_portrait" width="100" align="right"><b>In-pocket (<img src="./images/rupee.jpg" height="8" border="0">)</b>&nbsp;</td>
          <td class="GroupDetail_band_portrait" align="right"><b>Out-pocket (<img src="./images/rupee.jpg" height="8" border="0">)</b>&nbsp;</td>
        </tr>
<?php
      $l_no = 16;
      $tot_inp_amount  = 0;
      $tot_out_amount  = 0;
      $tot_tax_amount  = 0;
      $tot_tot_amount  = 0;
      $tot_net_amount  = 0;
      $tot_srv_amount  = 0;
      $rowcnt = 1 ; 
      $index   = 0;
      $dtl_row = isset($sele_qry[$index]) ? $sele_qry[$index] : null ;
      while($rowcnt <= $bill_data['selecnt_nos'])
      {
        $sub_inp_amount  = 0;
        $sub_out_amount  = 0;
        $sub_srv_amount  = 0;
        $sub_tot_amount  = 0;
        $sub_net_amount  = 0;
        $ptaxind = 'Y';
        $pserv_tax_ind  = $dtl_row['service_tax_ind'];
		$pserv_tax_per  = $dtl_row['service_tax_percent'];
        if ($hdr_row['service_tax_amount'] >0)
        $pserv_tax_desc = $dtl_row['service_tax_desc'];
        else $pserv_tax_desc = '';
        while($pserv_tax_ind == $dtl_row['service_tax_ind'] && $rowcnt <= $bill_data['selecnt_nos'])
        {
         $activity_date     = $dtl_row['activity_date'];
         $activity_desc     = $dtl_row['activity_desc'];
         $io_ind            = $dtl_row['io_ind'];
         $billed_amount     = $dtl_row['billed_amount'];
         $serv_tax_amount   = $dtl_row['service_tax_amount'];
         if($l_no>$bill_data['tot_no_of_lines'])
         {
          $page_no = $page_no + 1;
          $l_no = 6;
?>
          </table>
          </td>
          </tr>
          </table>
          <br class="pageEnd">
          <table width="700" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td class="cellheight_1" width="075">&nbsp;</td>
              <td class="cellheight_1" width="075">&nbsp;</td>
              <td class="cellheight_1" width="350">&nbsp;</td>
              <td class="cellheight_1" width="100">&nbsp;</td>
              <td class="cellheight_1" width="050">&nbsp;</td>
              <td class="cellheight_1" width="050">&nbsp;</td>
            <tr>
              <td height="15" class="ReportTitle_portrait" colspan="6" align="center">Sinha and Company</td>
            </tr>
            <tr>
              <td class="GroupDetail_band_portrait">&nbsp;Serial No.</td>
              <td class="ReportColumn_portrait">&nbsp;<?= $hdr_row['serial_no'] ?></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="GroupDetail_band_portrait">&nbsp;Date</td>
              <td class="ReportColumn_portrait">&nbsp;<?= $hdr_row['bill_date'] ?></td>
              <td class="ReportTitle_portrait" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($hdr_row['status_code'] == 'X') {echo 'Cancelled Draft Bill';} else {echo 'Draft Bill' ;}?></td>
              <td>&nbsp;</td>
              <td class="GroupDetail_band_portrait" align="right">Page :&nbsp;</td>
              <td class="ReportColumn_portrait" align="right"><?php echo $page_no;?>&nbsp;</td>
            </tr>
          </table>
          <table width="700" cellpadding="0" cellspacing="0" border="1" bordercolor="#eeeeee" bordercolordark="#FFFFFF" bordercolorlight="#FFFFFF">
          <tr>
          <td>
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td class="GroupDetail_band_portrait" width="100">&nbsp;&nbsp;&nbsp;<b>Date</b></td>
              <td class="GroupDetail_band_portrait" width="400">&nbsp;&nbsp;&nbsp;&nbsp;<b>Details</b></td>
              <td class="GroupDetail_band_portrait" width="100" align="right"><b>In-pocket (<img src="./images/rupee.jpg" height="8" border="0">)</b>&nbsp;</td>
              <td class="GroupDetail_band_portrait" align="right"><b>Out-pocket (<img src="./images/rupee.jpg" height="8" border="0">)</b>&nbsp;</td>
            </tr>
            <tr>
              <td class="GroupDetail_band_portrait" colspan="4"><hr color="#CCCCCC" noshade="noshade"></td>
            </tr>
<?php
         }
         if(!empty($activity_date))
         {
?>
            <tr>
              <td class="GroupDetail_band_portrait" colspan="4"><hr color="#CCCCCC" noshade="noshade"></td>
            </tr>
<?php
           $l_no = $l_no + 1 ;
         }
         $activity_desc = text_justify(trim(nl2br(stripslashes($activity_desc))),$bill_data['tot_char']);
         $activity_desc = str_replace("<br />",'',$activity_desc);
?>
            <?php if($ptaxind == 'Y') { ?>
            <tr>
              <td class="GroupDetail_band_portrait" colspan="4"><font size="2"><b><u><i><?php echo $pserv_tax_desc ?></i></u></b></font></td>
            </tr>
           <tr>
              <td class="GroupDetail_band_portrait" colspan="4"><hr color="#CCCCCC" noshade="noshade"></td>
            </tr>
            <?php $l_no += 2; $ptaxind = 'N' ; } ?>
         <tr>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;<b><?php echo $activity_date;?></b></td>
           <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;">&nbsp;&nbsp;<b><?php echo $activity_desc;?></b></td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php if($io_ind == 'I' && $billed_amount > 0) echo number_format($billed_amount,2,'.','');?></b>&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php if($io_ind == 'O' && $billed_amount > 0) echo number_format($billed_amount,2,'.','');?></b>&nbsp;</td>
         </tr>
<?php 
         $l_no = $l_no + 1 ;
         if($io_ind == 'O')
         {
           $sub_out_amount += $dtl_row['billed_amount'];
         }
         else
         {
           $sub_inp_amount += $dtl_row['billed_amount'];
         }

         $sub_tot_amount += $dtl_row['billed_amount'];
         $sub_srv_amount += $dtl_row['service_tax_amount'];
          //----
		  
 
 
 if ($pserv_tax_per == '10.300')
      { $s_tax = $sub_tot_amount*10/100;
        $cess_tax = $s_tax*2/100;
        $hecess_tax = $s_tax*1/100;
		}
		
 if ($pserv_tax_per == '12.360')
      { $s_tax = $sub_tot_amount*12/100;
        $cess_tax = $s_tax*2/100;
        $hecess_tax = $s_tax*1/100;

		}
 if($pserv_tax_per == '10.300') { $staxper = 'Service Tax 10%';}  if($pserv_tax_per == '12.360') { $staxper = 'Service Tax 12%';}

		  
		  
         //------   
            $dtl_row = isset($sele_qry[$index]) ? $sele_qry[$index] : null ;
            $index++;
            $rowcnt += 1;      
        //  $dtl_row = $sele_qry->fetchRow() ;
        //  $rowcnt += 1;      
        }
        $sub_net_amount = $sub_tot_amount + $sub_srv_amount ;       
?>
         <tr>
           <td class="GroupDetail_band_portrait" colspan="4"><hr color="#CCCCCC" noshade="noshade"></td>
         </tr>
         <!--
         <tr>
           <td class="GroupDetail_band_portrait" >&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;">&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php if($sub_inp_amount > 0) echo number_format($sub_inp_amount,2,'.','');?></b>&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php if($sub_out_amount > 0) echo number_format($sub_out_amount,2,'.','');?></b>&nbsp;</td>
         </tr>
         --->
         <tr>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b>Total</b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php if($sub_tot_amount > 0) echo number_format($sub_tot_amount,2,'.','');?></b>&nbsp;</td>
         </tr>
<?php
if($sub_srv_amount > 0)
{
 
?>
         <tr>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php echo $staxper?></b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php  echo number_format($s_tax,2,'.','');?></b>&nbsp;</td>
         </tr>
         <tr> 
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b>Education Cess 2% on ST</b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php  echo number_format($cess_tax,2,'.','');?></b>&nbsp;</td>
         </tr>
         <tr>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b>Secondary and Higher Secondary Education Cess 1% on ST</b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php  echo number_format($hecess_tax,2,'.','');?></b>&nbsp;</td>
         </tr>
         <tr>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b>Sub Total(Round Off)</b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;" align="right"><b><?php if($sub_net_amount > 0) echo number_format(round($sub_net_amount,0),2,'.','');?></b>&nbsp;</td>
         </tr>
<?php
    }     $l_no = $l_no + 5 ;
         $tot_inp_amount  += $sub_inp_amount;
         $tot_out_amount  += $sub_out_amount;
         $tot_tot_amount  += $sub_tot_amount;
         $tot_srv_amount  += $sub_srv_amount;
         $tot_net_amount  += $sub_net_amount;
      }
?>
          <tr>
            <td class="GroupDetail_band_portrait" colspan="4"><hr color="#CCCCCC" noshade="noshade"></td>
          </tr>
      </table>
      </td>
      </tr>
      </table>
      <table width="700" cellpadding="0" cellspacing="0" border="1" bordercolor="#eeeeee" bordercolordark="#FFFFFF" bordercolorlight="#FFFFFF">
        <tr>
          <td width="100%">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td class="GroupDetail_band_portrait" width="394" valign="top">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td class="GroupDetail_band_portrait" height="60" style="vertical-align:text-top; font:Courier; font-family:Courier;"><b><?php echo '(Rupees '.int_to_words($tot_net_amount).' only)';?></b></td>
                    </tr>
                  </table>
                </td>
                <td width="300" class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #CCCCCC;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right">&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php if($tot_inp_amount>0) echo number_format($tot_inp_amount,2,'.','');?></b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php if($tot_out_amount>0) echo number_format($tot_out_amount,2,'.','');?></b>&nbsp;</td>
                    </tr>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Total</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right">&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php if($tot_tot_amount>0) echo number_format($tot_tot_amount,2,'.','');?></b>&nbsp;</td>
                    </tr>
<?php
           if($tot_srv_amount>0)
{
?>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Total Service Tax</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right">&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php echo number_format(round($tot_srv_amount,0),2,'.','');?></b>&nbsp;</td>
                    </tr>
<?php
}
?>
                    <tr>
                      <td colspan="3"><hr size="1" noshade="noshade"></td>
                    </tr>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Grand Total</b>&nbsp;</td>
                      <td width="100" height="15" align="right" class="style1 GroupDetail_band_portrait"><img src="./images/rupee.jpg" height="8" border="0">&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php if($tot_net_amount>0) echo number_format(round($tot_net_amount,0),2,'.','');?></b>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3" height="15"><hr size="3" color="#CCCCCC" noshade="noshade"></td>
                    </tr>
                    <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="15" align="center"><b>E.&.O.E.</b></td>
                    </tr>
                    <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="15" align="center"><b>for&nbsp;Sinha and Company</b></td>
                    </tr>
                    <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="20" align="center">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="20" align="center">&nbsp;</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
