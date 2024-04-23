<?= $this->extend("layouts/master") ?> 
<!-- ============================ Main Content ============================== -->
<?= $this->section("body-content") ?>
<script>
	document.getElementById('sidebar').style.display = "none";
	document.getElementById('burgerMenu').style.display = "none";
</script>
<div class="mtop90">
      <!-- header part -->
      <table width="950" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto">
        <tr>
          <td class="cellheight_1" width="050">&nbsp;</td>
          <td class="cellheight_1" width="125">&nbsp;</td>
          <td class="cellheight_1" width="400">&nbsp;</td>
          <td class="cellheight_1" width="075">&nbsp;</td>
          <td class="cellheight_1" width="050">&nbsp;</td>
          <td class="cellheight_1" width="050">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="center"> 
             <?php 
			    // include("main_letter_head_logo.php");
			 ?>
          </td>
		</tr>  
        <tr>
          <td class="GroupDetail_band_portrait">&nbsp;<?php if($prop_ind != 'Checked') { echo 'Bill No' ; } else { echo 'Ref No' ; }?></td>
          <td class="ReportColumn_portrait">&nbsp;<?php echo $bill_fin_year.'/'.$bill_no;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td> 
        </tr>
        <tr>
          <td class="GroupDetail_band_portrait">&nbsp;<?php if($prop_ind != 'Checked') { echo 'Bill Date' ; } else { echo 'Ref Date' ; }?></td>
          <td class="ReportColumn_portrait">&nbsp;<?php if(empty($final_bill_date)) echo $bill_date; else echo $final_bill_date;?></td>
          <td class="ReportTitle_portrait" align="center"><font size="+1"><b><?php if($dupl_ind=='Checked') {echo 'DUPLICATE ';} if($revd_ind=='Checked') {echo 'REVISED ';} if($prop_ind=='Checked') {echo 'PROPOSE / QUOTATION ';} if($copy_ind=='Checked') {echo 'COPY ';}?>BILL</b></font></td>
          <td>&nbsp;</td>
          <td class="GroupDetail_band_portrait" align="right" colspan="2">Page :&nbsp;<?php echo $page_no;?>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6"><hr size="1" noshade="noshade"></td>
        </tr>
      </table>
      <table width="950" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto">
        <tr>
          <!-- client detail -->
          <td width="375" height="180" style="vertical-align:text-top">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php if ($client_code == 'K00068') { ?>
        <tr>
		 <td height="15" class="GroupDetail_band_portrait"><?php echo 'THE CHIEF LAW OFFICER'?></td>
		</tr>
 <?php } ?>
 <?php if($trust_name != '') {?>
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $trust_name;?></td>
              </tr>
  <?php } ?>
 <?php if(!empty($client_name)) { ?> 
			  <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $client_name;?></td>
              </tr>
<?php } ?>
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $address_line_1;?></td>
              </tr>
              <?php if(!empty($address_line_2)) { ?>              
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $address_line_2;?></td>
              </tr>
              <?php } if(!empty($address_line_3)) { ?>              
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $address_line_3;?></td>
              </tr>
              <?php } if(!empty($address_line_4)) { ?>              
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $address_line_4;?></td>
              </tr>
              <?php } ?>              
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<?php echo $city .' '.$pin_code;?></td>
              </tr>
			  <?php if($pan_no != '') {?>
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<b><?php echo 'PAN : ' .' '.$pan_no;?></b></td>
              </tr>
			  <?php } ?>

			  <?php if($state_name != '') {?>
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<b><?php echo strtoupper(stripslashes($state_name));?></b></td>
              </tr>
			  <?php }  ?>
	<?php if($bill_row['b_d'] >= '2017-07-01' || date_conv($final_bill_date) >= '2017-07-01') {?>	
		  
			 <?php if($client_gst != '') {?>
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<b><?php echo 'STATE CODE : ' .' '.strtoupper(stripslashes($gst_zone_code));?></b></td>
              </tr>
			  <?php } ?>

			  <?php if($client_gst != '') {?>
              <tr>
                <td height="15" class="GroupDetail_band_portrait">&nbsp;<b><?php echo 'GSTIN : ' .' '.strtoupper(stripslashes($client_gst)) ;?></b></td>
              </tr>
			  <?php } ?>

  <?php } ?>
  
<?php if ($bill_attn_code != '373') { ?>

              <tr>
                <td valign="middle" height="90" class="GroupDetail_band_portrait">&nbsp;
                <p>&nbsp;<?php if ($bill_attn_code != '0') echo 'Attn. : '.$attention_name;?></p>
                <p>&nbsp;<?php if($designation!='') echo 'Designation : '.$designation; else echo' ';?></p></td>
              </tr>
<?php } ?>

            </table>
          </td>
          <!-- end of client detail -->

          <!-- ref and subj -->
          <td width="375">
            <table width="100%" cellpadding="0" cellspacing="0">
			<?php if($bill_fin_year == '2015-2016' && $bill_no == 'GR71') { ?>
              <tr>
                <td height="20" width="06%" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;">&nbsp;</td>
                <td height="20" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><b><?php echo 'In the High Court at Bombay';?> </b></td>
              </tr>
			<?php } ?>  
              <tr>
                <td height="20" width="06%" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo 'Re : ';?></td>
                <td height="20" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo $matter_name;?></td>
              </tr>
              <tr>
                <td height="70">&nbsp;</td>
                <td height="70" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo $other_case_desc;?></td>
              </tr>
              <tr>
                <td height="15" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php if($source_code=='C') { echo ' ' ; } else { echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' ; }?></td>
                <td height="15" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo $reference_desc;?></td>
              </tr>
              <tr>
                <td height="75" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify; width: 45px;display: block;">Sub :&nbsp;</td>
                <td height="75" class="GroupDetail_band_portrait" style="vertical-align:text-top; text-align:justify;"><?php echo strtoupper($subject_desc);?></td>
              </tr>
            </table>
          </td>
          <!-- end of ref and subj -->
        </tr>
      </table>
      <table width="950" class="m-0 m-auto" cellpadding="0" cellspacing="0" border="1" bordercolor="#666666" bordercolordark="#FFFFFF" bordercolorlight="#FFFFFF">
      <tr>
      <td>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="GroupDetail_band_portrait" width="106">&nbsp;<b>Date</b></td>
          <td class="GroupDetail_band_portrait" width="402">&nbsp;<b>Details</b></td>
              <td class="GroupDetail_band_portrait" width="142" align="right"><b>Professional Fees<b>(<img src="./images/rupee.jpg" height="8" border="0"><span  class="style1"></span>)</b><br>
              </b>&nbsp;</td>
              <td class="GroupDetail_band_portrait" width="111" align="right"><b>Reimbursement<b>(<img src="./images/rupee.jpg" height="8" border="0"><span class="style1"></span>)</b><br>
              </b>&nbsp;</td>
            
        </tr>
<?php
      $l_no = 25;
      $tot_inp_amount  = 0;
      $tot_out_amount  = 0;
      $tot_tax_amount  = 0;
      $tot_tot_amount  = 0;
      $tot_net_amount  = 0;
      $tot_srv_amount  = 0;
      $rowcnt = 1 ; 
     // $dtl_row = $sele_qry->fetchRow() ;
      $dtl_row = isset($sele_qry[$rowcnt-1]) ? $sele_qry[$rowcnt-1] : '' ; 
      while($rowcnt <= $selecnt_nos)
      {
        $sub_inp_amount  = 0;
        $sub_out_amount  = 0;
        $sub_srv_amount  = 0;
        $sub_tot_amount  = 0;
        $sub_net_amount  = 0;
        $ptaxind = 'Y';
        $pserv_tax_ind  = $dtl_row['service_tax_ind'];
		$pserv_tax_per  = $dtl_row['service_tax_percent'];
        if ($service_tax_amount >0)
          $pserv_tax_desc = $dtl_row['service_tax_desc'];
        else
          $pserv_tax_desc = '';

        while($pserv_tax_ind == $dtl_row['service_tax_ind'] && $rowcnt <= $selecnt_nos)
        {
         $activity_date     = $dtl_row['activity_date'];
         $activity_desc     = $dtl_row['activity_desc'];
         $io_ind            = $dtl_row['io_ind'];
         $source_code_dtl2  = $dtl_row['source_code'];
         $billed_amount     = $dtl_row['billed_amount'];
         $serv_tax_amount   = $dtl_row['service_tax_amount'];
         $pserv_bill_date   = $bill_row['bill_date'];


         if($l_no>$tot_no_of_lines)
         {
           $page_no = $page_no + 1;
           $l_no = 15;
?>
        </table>
        </td>
        </tr>
</table>
          <br class="pageEnd">
      <table width="950" cellpadding="0" cellspacing="0" border="0" class="m-0 m-auto">
        <tr>
          <td class="cellheight_1" width="075">&nbsp;</td>
          <td class="cellheight_1" width="100">&nbsp;</td>
          <td class="cellheight_1" width="400">&nbsp;</td>
          <td class="cellheight_1" width="075">&nbsp;</td>
          <td class="cellheight_1" width="050">&nbsp;</td>
          <td class="cellheight_1" width="050">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="6" align="center"> 
             <?php 
			
			 ?>
          </td>
		</tr>  
        <tr>
          <td class="GroupDetail_band_portrait">&nbsp;<?php if($prop_ind != 'Checked') { echo 'Bill No' ; } else { echo 'Ref No' ; }?></td>
          <td class="ReportColumn_portrait">&nbsp;<?php echo $bill_fin_year.'/'.$bill_no;?></td>
          <td>&nbsp; </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td> 
        </tr>
        <tr>
          <td class="GroupDetail_band_portrait">&nbsp;<?php if($prop_ind != 'Checked') { echo 'Bill Date' ; } else { echo 'Ref Date' ; }?></td>
          <td class="ReportColumn_portrait">&nbsp;<?php if(empty($final_bill_date)) echo $bill_date; else echo $final_bill_date;?></td>
          <td class="ReportTitle_portrait" align="center"><font size="+1"><b><?php if($dupl_ind=='Checked') {echo 'DUPLICATE ';} if($revd_ind=='Checked') {echo 'REVISED ';} if($prop_ind=='Checked') {echo 'PROPOSE / QUOTATION ';} ?>BILL</b></font></td>
          <td>&nbsp;</td>
          <td class="GroupDetail_band_portrait" align="right" colspan="2">Page :&nbsp;<?php echo $page_no;?>&nbsp;</td>
        </tr>
            <tr>
              <td colspan="6"><hr size="1" color="#666666" noshade="noshade"></td>
            </tr>
          </table>
          <table width="950" class="m-0 m-auto" cellpadding="0" cellspacing="0" border="1" bordercolor="#666666" bordercolordark="#FFFFFF" bordercolorlight="#FFFFFF">
          <tr>
          <td>
          <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr> 
              <td class="GroupDetail_band_portrait" width="129">&nbsp;<b>Date</b></td>
              <td class="GroupDetail_band_portrait" width="364">&nbsp;<b>Details</b></td>
              <td class="GroupDetail_band_portrait" width="142" align="right"><b>Professional Fees<b>(<img src="./images/rupee.jpg" height="8" border="0">)</b><br>
              </b>&nbsp;</td>
              <td class="GroupDetail_band_portrait" width="111" align="right"><b>Reimbursement<b>(<img src="./images/rupee.jpg" height="8" border="0">)</b><br>
              </b>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4"><hr color="#666666" noshade="noshade"></td>
            </tr>
<?php
         }
         if(!empty($activity_date))
         {
?>
           <tr>
             <td class="GroupDetail_band_portrait" colspan="4"><hr color="#666666" noshade="noshade"></td>
           </tr>
<?php
           $l_no = $l_no + 1 ;
         }
        $activity_desc = text_justify(trim(nl2br(stripslashes($activity_desc))),$tot_char);
        $activity_desc = str_replace("<br />",'',$activity_desc);
?>
        <?php if($ptaxind == 'Y') { ?>
        <tr>
          <td class="GroupDetail_band_portrait" colspan="4"><font size="2"><b><u><i><?php echo $pserv_tax_desc ;?></i></u></b></font></td>
        </tr>
        <tr>
          <td class="GroupDetail_band_portrait" colspan="4"><hr color="#666666"  noshade="noshade"></td>
        </tr>
        <?php $l_no += 2; $ptaxind = 'N' ; } ?>
        <tr>
          <td class="GroupDetail_band_portrait ps-2" style="vertical-align:middle; font:Courier; font-family:Courier;"><b><?php echo $activity_date ;?></b></td>
          <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;">&nbsp;<b><?php echo $activity_desc;?></b>&nbsp;</td>
		  <?php if ($billed_amount > 0) { ?>
          <td class="GroupDetail_band_portrait" style="vertical-align:middle; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php if(($io_ind == 'I' || $io_ind == 'O') && $source_code_dtl2 == 'C' && $billed_amount > 0) echo number_format($billed_amount,2,'.','');?></b>&nbsp;</td>
          <?php } ?>
		  <?php  if ($billed_amount == '0.00') { ?>
		  <td class="ReportColumn_portrait" style="  font:Courier; font-family:Courier; font-weight:bold; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="center"  ><b><?php if(($io_ind == 'I' || $io_ind == 'O') && $no_fee_bill_ind == 'Y' && $source_code_dtl2 == 'C' && $billed_amount == '0.00') echo '<strong>No Fee Charged</strong>';?></b>&nbsp;</td>
		  <?php } ?>
          <td class="GroupDetail_band_portrait" style="vertical-align:middle; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php if($io_ind == 'O' && $source_code_dtl2 != 'C' && $billed_amount > 0) echo number_format($billed_amount,2,'.','');?></b>&nbsp;</td>
        </tr>  
<?php
         $l_no = $l_no + 1 ;
         if($io_ind == 'O' && $source_code_dtl2 == 'M')
         {
           $sub_out_amount += $dtl_row['billed_amount'];
         }
         else
         {
           $sub_inp_amount += $dtl_row['billed_amount'];
         }

         $sub_tot_amount += $dtl_row['billed_amount'];
         $sub_srv_amount += $dtl_row['service_tax_amount'];
        //--------
		
		
		
		
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
       //  $dtl_row = $sele_qry->fetchRow() ;
         $dtl_row = isset($sele_qry[$rowcnt-1]) ? $sele_qry[$rowcnt-1] : '' ; 
         
         $rowcnt += 1;      
        }
        $sub_net_amount = $sub_tot_amount + $sub_srv_amount ;  



       // $serv_tot_amount = $s_tax + $cess_tax + $hecess_tax ;    
?>
         <tr>
           <td class="GroupDetail_band_portrait" colspan="4"><hr color="#666666" noshade="noshade"></td>
         </tr>
         <tr>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b>Total</b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php if($sub_tot_amount > 0) echo number_format($sub_tot_amount,2,'.','');?></b>&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>

         </tr>

<?php
          if($sub_srv_amount > 0)
{

?>
         <tr>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php echo $staxper?></b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php  echo number_format($s_tax,2,'.','');?></b>&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>
         </tr>
         <tr> 
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b>Education Cess 2% on ST</b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php  echo number_format($cess_tax,2,'.','');?></b>&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>
         </tr>
         <tr>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b>Secondary and Higher Secondary Education Cess 1% on ST</b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php  echo number_format($hecess_tax,2,'.','');?></b>&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>
         </tr>
         <tr>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier;">&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b>Sub Total(Round Off)</b>&nbsp;&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right"><b><?php if($sub_net_amount > 0) echo number_format(round($sub_net_amount,0),2,'.','');?></b>&nbsp;</td>
           <td class="GroupDetail_band_portrait" style="vertical-align:text-top; font:Courier; font-family:Courier; border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;" align="right">&nbsp;</td>
         </tr>
<?php
}
         $l_no = $l_no + 5 ;
         $tot_inp_amount  += $sub_inp_amount;
         $tot_out_amount  += $sub_out_amount;
         $tot_tot_amount  += $sub_tot_amount;
         $tot_srv_amount  += $sub_srv_amount;
         $tot_net_amount  += $sub_net_amount;
      }
?>
          <tr>
            <td class="GroupDetail_band_portrait" colspan="4"><hr color="#666666" noshade="noshade"></td>
          </tr>
      </table>
      </td>
      </tr>
      </table>
      <table width="950" class="m-0 m-auto" cellpadding="0" cellspacing="0" border="1" bordercolor="#666666" bordercolordark="#FFFFFF" bordercolorlight="#FFFFFF">
        <tr>
          <td width="100%">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td class="GroupDetail_band_portrait" width="425" valign="top">
                  <table width="100%" height="49" cellpadding="0" cellspacing="0">
                    <tr>
                      <td class="GroupDetail_band_portrait" height="22" style="vertical-align:text-top; font:Courier; font-family:Courier;"><b><?php echo '(Rupees '.int_to_words($tot_net_amount).' only)';?></b></td>
                    </tr>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="22" style="vertical-align:text-top; font:Courier; font-family:Courier; font-size:14px;"><b><?php if($direct_counsel_ind == 'Y') echo $direct_memo;?></b></td>
                    </tr>
					 <tr>
                      <td class="GroupDetail_band_portrait" height="18" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><span class="GroupDetail_band_portrait" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><font size="2"><b><?php echo '';?></b></font></span></td>
                    </tr>
					 <tr>
                      <td class="GroupDetail_band_portrait ps-2" height="18" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><span class="GroupDetail_band_portrait" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><font size="2"><b><?php echo $branch_pan_no;?></b></font></span></td>
                    </tr>
                  </table>

<?php
 //if ($service_tax_amount <= 0 && $bill_row['b_d'] > '2012-06-30' && $bill_row['b_d'] < '2017-07-01' || $final_bill_date < '2017-07-01')  {$service_dec = 'IN TERMS OF NOTIFICATION NO. 30/2012-ST, DATED JUNE 20, 2012 READ WITH CORRIGENDUM DATED JUNE 29, 2012, WITH EFFECT FROM JULY 1, 2012, SERVICE TAX AS APPLICABLE, IN RESPECT OF LEGAL SERVICES PROVIDED BY AN INDIVIDUAL ADVOCATE OR A FIRM OF ADVOCATES, IS PAYABLE BY THE SERVICE RECEIVER. HENCE SUCH TAX NOT INCLUDED IN THIS BILL.';} 

// if ($service_tax_amount <= 0 && $bill_row['b_d'] > '2012-06-30' && $bill_row['b_d'] < '2017-07-01' || $final_bill_date < '2017-07-01')  {$service_dec = 'IN TERMS OF NOTIFICATION NO. 30/2012-ST, DATED JUNE 20, 2012 READ WITH CORRIGENDUM DATED JUNE 29, 2012, WITH EFFECT FROM JULY 1, 2012, SERVICE TAX AS APPLICABLE, IN RESPECT OF LEGAL SERVICES PROVIDED BY AN INDIVIDUAL ADVOCATE OR A FIRM OF ADVOCATES, IS PAYABLE BY THE SERVICE RECEIVER. HENCE SUCH TAX NOT INCLUDED IN THIS BILL.';} 
 if ($service_tax_amount > 0 && $bill_row['b_d'] >= '2012-07-01') {$service_dec = '';}
?>
<!--                  <p><span class="GroupDetail_band_portrait" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><font size="2"><b><?php echo $branch_service_tax.'SD001';?></b></font></span></p>
-->               
                   <p><span class="GroupDetail_band_portrait ps-2" style="vertical-align:text-bottom; font:Arial; font-family:Arial;"><font size="2"><b><?php echo $service_nature;?></b></font></span></p>
                  <p><span class="GroupDetail_band_portrait ps-2 d-block" style="vertical-align:text-bottom;  font-size: text-align:justify; font:Arial; font-family:Arial; font-size:15px;"><b><?php echo $service_dec;?></b></font></span></p>                </td>
                <td class="GroupDetail_band_portrait ps-2" style="border-left-width: 1px; border-left-style: solid;  border-left-color: #666666;">
                  <table width="100%" cellpadding="0" cellspacing="0">

				    
<!--                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right">&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php if($tot_inp_amount>0) echo number_format($tot_inp_amount,2,'.','');?></b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php if($tot_out_amount>0) echo number_format($tot_out_amount,2,'.','');?></b>&nbsp;</td>
                    </tr>
-->                    
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
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><span class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;"><b><?php  echo number_format(round($tot_srv_amount,0),2,'.','');?></b></span>&nbsp;</td>
                    </tr>
                    <?php
                      }
                    ?>
                    <tr>
                      <td colspan="3"><hr size="1" noshade="noshade"></td>
                    </tr>
                    <tr>
                      <td colspan="3"><hr size="1" noshade="noshade"></td>
                    </tr>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Grand Total</b>&nbsp;</td>
                      <td width="100" height="15" align="right" class="style1 GroupDetail_band_portrait"><img src="./images/rupee.jpg" height="8" border="0"></td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php if($tot_net_amount>0) echo number_format(round($tot_net_amount,0),2,'.','');?></b>&nbsp;</td>
                    </tr>



                    <tr>
                      <td colspan="3" height="15"><hr size="3" color="#666666" noshade="noshade"></td>
                    </tr>
<?php
        $tot_taxable     = $bill_row['bill_amount_inpocket_stax'] + $bill_row['bill_amount_outpocket_stax'] + $bill_row['bill_amount_counsel_stax'] ;
        $tot_nontaxable  = $bill_row['bill_amount_inpocket_ntax'] + $bill_row['bill_amount_counsel_ntax'];
        $tot_reim        = $bill_row['bill_amount_outpocket_ntax'] ;
?>
        <?php
             if($tot_taxable>0)
        {
        ?>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Taxable Service</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php  echo number_format($tot_taxable,2,'.','');?></b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right">&nbsp;</td>
                    </tr>
                    <?php
                    }
                      if($tot_srv_amount>0)
                      {
                    ?>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Service Tax</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><span class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;"><b><?php  echo number_format(round($tot_srv_amount,0),2,'.','');?></b></span>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right">&nbsp;</td>
                    </tr>
                    <?php
                      }
                    ?>
          <?php
          if($tot_nontaxable>0)
          {
          ?>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Non Taxable</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php echo number_format(round($tot_nontaxable,0),2,'.','');?></b>&nbsp;</td>
                      <td width="100" height="15" align="right" class="style1 GroupDetail_band_portrait">&nbsp;</td>
                    </tr>
         <?php
           }
          ?>
         <?php
           if($tot_reim>0)
           {
         ?>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Reimbursement</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php echo number_format(round($tot_reim,0),2,'.','');?></b>&nbsp;</td>
                      <td width="100" height="15" align="right" class="style1 GroupDetail_band_portrait">&nbsp;</td>
                    </tr>
           <?php
           }
            ?>
          <?php
             if($tot_net_amount>0)
            {
          ?>
                    <tr>
                      <td class="GroupDetail_band_portrait" height="15" align="right"><b>Total</b>&nbsp;</td>
                      <td class="GroupDetail_band_portrait" style="font:Courier; font-family:Courier;" width="100" height="15" align="right"><b><?php echo number_format(round($tot_net_amount,0),2,'.','');?></b>&nbsp;</td>
                      <td width="100" height="15" align="right" class="style1 GroupDetail_band_portrait">&nbsp;</td>
                    </tr>
          <?php
               }
           ?>


                    <tr>
                      <td colspan="3" height="15"><hr size="3" color="#666666" noshade="noshade"></td>
                    </tr>
                    <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="15" align="center"><b>E.&.O.E.</b></td>
                    </tr>
                    <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="15" align="center"><b>for&nbsp;<?php echo 'Sinha&Co';?></b></td>
                    </tr>
                    <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="20" align="center">&nbsp;</td>
                    </tr>
<!--                    <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="20" align="center">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3" class="GroupDetail_band_portrait" height="20" align="center">&nbsp;</td>
                    </tr>
-->					 <tr>
                     <td colspan="3" class="GroupDetail_band_portrait" height="20" align="center"><font size="+1"><b><?php ?></b></font></td>
                    </tr>
                  </table>                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
<?php
//    }
    if($row_count > 1 && $i != $row_count)
    {
?>
      <BR CLASS="pageEnd">
<?php
    }   
?>
</div>
<?= $this->endSection() ?>
