<?php
    //----- 
    $maxline    = 43 ;
    $tot_char   = 150 ;
    $lineno     = 0 ;
    $pageno     = 0 ;
    $rowcnt     = 1 ;
    $xsrl       = 0 ;

    // $report_row = $case_qry[0]; 
    $report_cnt = $case_cnt ;
    // while ($rowcnt <= $report_cnt)
    foreach ($case_qry as $report_row)
    {
      $hdr_desc      = str_replace("\n\n","\r\n\r\n",$report_row['header_desc']) . chr(13);
      $header_desc   = wordwrap($hdr_desc, $tot_char, "\n");
      $header_array  = explode("\n",$header_desc);
      $array_row     = count($header_array);
      if($lineno == 0 || $lineno >= $maxline)
      {
        if($lineno >= $maxline)
        { 
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
  <div class="ScrltblMnOthrsPop">
        <table class="table border-0" align="center" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="border-0 pb-0">    
              <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('Sinha and Co')?></b></td>
                </tr>
                <tr>
                  <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($report_desc)?> </u></b></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td class="report_label_text">&nbsp;Branch</td>
                  <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $branch_name?></b></td>
                  <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                  <td class="report_label_text">&nbsp;:&nbsp;<?= date('d-m-Y') ?></td>
                </tr>
                <tr>
                  <td class="report_label_text">&nbsp;As On</td>
                  <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $ason_date?></b></td>
                  <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                  <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                </tr>
                <tr>
                  <td class="report_label_text">&nbsp;Client</td>
                  <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($client_code != '%') { echo strtoupper($client_name) ; } else { echo 'ALL' ; } ?></b></td>
                  <td class="report_label_text">&nbsp;</td>
                  <td class="report_label_text">&nbsp;</td>
                </tr>
                <tr>
                  <td class="report_label_text">&nbsp;Matter</td>
                  <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($matter_code != '%') { echo '('.$matter_code.') - '.strtoupper($matter_desc) ; } else { echo 'ALL' ; } ?></b></td>
                  <td class="report_label_text">&nbsp;</td>
                  <td class="report_label_text">&nbsp;</td>
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
          <tr>
            <td colspan="4" class="grid_header">
              <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                <tr class="fs-14">
                  <th height="18" width="" align="right" class="py-3 px-2">Sl&nbsp;</th>
                  <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Rec Sl#</th>
                  <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Date</th>
                  <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Reference No/Court/Judge</th>
                  <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Filing Dt/Amt</th>
                  <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Prev Date/</th>
                  <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Next Date</th>
                  <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Fix For</th>
                  <th height="18" width="" align="left"  class="py-3 px-2">&nbsp;Remarks</th>
                </tr>
<?php
                $lineno = 10 ;
      }
      $xsrl = $xsrl + 1;
?>
      <tr style="vertical-align:top" class="fs-14">
        <td align="right" class="py-3 px-2"><b><?php echo $xsrl ?></b>&nbsp;</td>
        <td align="left"  class="py-3 px-2">{<?php echo $report_row['serial_no'];?>}&nbsp;</td> 
        <td align="left"  class="py-3 px-2"><?php echo date_conv($report_row['activity_date'],'-');?></td> 
        <td align="left"  class="py-3 px-2"><?php echo $report_row['reference_desc'];?></td>
        <td align="left"  class="py-3 px-2"><?php if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { echo date_conv($report_row['date_of_filing'],'-') ; } else { echo '&nbsp;' ; } ?></td> 
        <td align="left"  class="py-3 px-2"><?php echo date_conv($report_row['prev_date'],'-');?></td>
        <td align="left"  class="py-3 px-2"><?php echo date_conv($report_row['next_date'],'-');?></td>
        <td align="left"  class="py-3 px-2"><?php echo $report_row['next_fixed_for'];?>&nbsp;</td>
        <td align="left"  class="py-3 px-2" rowspan="3" style="vertical-align:top"><?php echo $report_row['remarks'];?></td>
      </tr>
      <tr class="fs-14">
        <td align="left"  class="py-3 px-2">&nbsp;</td> 
        <td align="left"  class="py-3 px-2">&nbsp;<?php if($report_row['bill_status'] == 'B') { echo '<b><font color="#CC0000">BILLED</font></b>';} else if($report_row['bill_status'] == 'A') { echo '<b><font color="#0000FF">DRAFT</font></b>';} else { echo '<b><font color="#FF0000">.</font></b>';} ?></td> 
        <td align="left"  class="py-3 px-2">&nbsp;</td>
        <td align="left"  class="py-3 px-2"><?php echo $report_row['court_name'];?></td>
        <td align="left"  class="py-3 px-2"><?php echo $report_row['stake_amount'];?></td> 
        <td align="left"  class="py-3 px-2">&nbsp;</td> 
        <td align="left"  class="py-3 px-2">&nbsp;</td> 
        <td align="left"  class="py-3 px-2">&nbsp;</td> 
      </tr>
      <tr class="fs-14">
        <td align="left"  class="py-3 px-2">&nbsp;</td> 
        <td align="left"  class="py-3 px-2" colspan="2" bgcolor="">&nbsp;<?php if($report_row['bill_status'] == 'B') { echo '<b><font color="#CC0000">'.$report_row['bill_no'].'</font></b>';} else if($report_row['bill_status'] == 'A') { echo '<b><font color="#0000FF">'.$report_row['ref_billinfo_serial_no'].'</font></b>';} else { echo '';} ?></td> 
        <td align="left"  class="py-3 px-2"><?php echo $report_row['judge_name'];?></td>
        <td align="left"  class="py-3 px-2">&nbsp;</td>
        <td align="left"  class="py-3 px-2">&nbsp;</td>
        <td align="left"  class="py-3 px-2">&nbsp;</td> 
        <td align="left"  class="py-3 px-2">&nbsp;</td> 
      </tr>
<?php 
      $lineno = $lineno + 3 ; 

      if($desc_ind == 'Y') 
      {  
?>
      <tr class="fs-14">
        <td align="right" class="py-3 px-2" style="text-align:center; font:Courier; font-family:Courier;" colspan="2"><i>Particulars</i></td> 
        <td align="left"  class="py-3 px-2" colspan="7"><?php echo $report_row['other_case_desc'];?>&nbsp;</td>
      </tr>
<?php
        $lineno = $lineno + 1 ; 
        for($i=0;$i<$array_row;$i++)
        {
          $header_desc = text_justify(trim(nl2br(stripslashes($header_array[$i]))),$tot_char);
          $header_desc = str_replace("<br />",'',$header_desc);
?>
          <tr class="fs-14">
            <td align="right" class="py-3 px-2" colspan="2">&nbsp;</td> 
            <td class="py-3 px-2" style="text-align:left; font:Arial; font-family:Arial;" colspan="7"><i><?php echo $header_desc;?></i></td>
          </tr>
<?php
          $lineno = $lineno + 1 ;
          if($lineno >= $maxline)
          {
?>
              </table>
            </td>
          </tr>
</table>
          </div>
          <BR CLASS="pageEnd"> 
<?php
          $pageno = $pageno + 1 ;
?>
<div class="ScrltblMnOthrsPop">
          <table class="table border-0 dd" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="border-0 pb-0">    
                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="report_label_text" colspan="4" align="center"><b><?php echo strtoupper('Sinha and Co.')?></b></td>
                  </tr>
                  <tr>
                    <td class="report_label_text" colspan="4" align="center"><b><u> <?php echo strtoupper($report_desc)?> </u></b></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="report_label_text">&nbsp;Branch</td>
                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $branch_name?></b></td>
                    <td class="report_label_text" align="right">Date&nbsp;&nbsp;</td>
                    <td class="report_label_text">&nbsp;:&nbsp;<?= date('d-m-Y') ?></td>
                  </tr>
                  <tr>
                    <td class="report_label_text">&nbsp;As On</td>
                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $ason_date?></b></td>
                    <td class="report_label_text" align="right">Page&nbsp;&nbsp;</td>
                    <td class="report_label_text">&nbsp;:&nbsp;<?php echo $pageno?></td>
                  </tr>
                  <tr>
                    <td class="report_label_text">&nbsp;Client</td>
                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($client_code != '%') { echo strtoupper($client_name) ; } else { echo 'ALL' ; } ?></b></td>
                    <td class="report_label_text">&nbsp;</td>
                    <td class="report_label_text">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="report_label_text">&nbsp;Matter</td>
                    <td class="report_label_text">&nbsp;:&nbsp;<b><?php if($matter_code != '%') { echo '('.$matter_code.') - '.strtoupper($matter_desc) ; } else { echo 'ALL' ; } ?></b></td>
                    <td class="report_label_text">&nbsp;</td>
                    <td class="report_label_text">&nbsp;</td>
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
              <td colspan="4" class="grid_header">
                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                  <tr class="fs-14">
                  <td height="18" width="03%" align="right" class="py-3 px-2">Sl&nbsp;</td>
                  <td height="18" width="08%" align="left"  class="report_detail_rtb">&nbsp;Rec Sl#</td>
                  <td height="18" width="08%" align="left"  class="report_detail_rtb">&nbsp;Date</td>
                  <td height="18" width="40%" align="left"  class="report_detail_rtb">&nbsp;Reference No/Court/Judge</td>
                  <td height="18" width="10%" align="left"  class="report_detail_rtb">&nbsp;Filing Dt/Amt</td>
                  <td height="18" width="08%" align="left"  class="report_detail_rtb">&nbsp;Prev Date/</td>
                  <td height="18" width="08%" align="left"  class="report_detail_rtb">&nbsp;Next Date</td>
                  <td height="18" width="23%" align="left"  class="report_detail_rtb">&nbsp;Fix For</td>
                  <td height="18" width="23%" align="left"  class="report_detail_rtb">&nbsp;Remarks</td>
                </tr>
<?php
            $lineno = 10 ;
          }
        }
      }  
?>
      <tr class="fs-14">
        <td colspan="78">&nbsp;</td>
      </tr>
<?php     
      $lineno = $lineno + 1 ; 
      if ($maxline - $lineno < 3) { $lineno = $maxline ; }  
    //   $report_row = mysql_fetch_array($case_qry);
      $rowcnt = $rowcnt + 1 ;
    }
?>
                </table>
  </div>
              </td>
            </tr>
          </table> 