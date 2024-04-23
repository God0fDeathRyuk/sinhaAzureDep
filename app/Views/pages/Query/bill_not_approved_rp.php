<html>

<head>
    <link rel="icon" href="images/logo.ico">
    <link rel="shortcut icon" href="images/logo.ico">

    <title>Bill Register (Court/Client/Matter)</title>
    <link href="sbreportstyle.css" rel="stylesheet" type="text/css">
    <link href="sbstyle.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="common_function.js"></script>
    <style type="text/css">
    BR.pageEnd {
        page-break-after: always;
    }
    </style>
</head>

<body>
    <?php
    //----- 
    $maxline    = 65;
    $lineno     = 0 ;
    $pageno     = 0 ;
    $total      = 0;
    $gtotal     = 0;
    $rowcnt     = 1 ;
    $report_row = isset($bill_qry[$rowcnt-1]) ? $bill_qry[$rowcnt-1] : '' ;  
	$report_cnt = count($bill_qry) ;
    while ($rowcnt <= $report_cnt)
    {
      $total      = 0;
      $pclientind = 'Y' ;
      $pclientcd  = $report_row['client_code'];
      $pclientnm  = $report_row['client_name'];
      while ($pclientnm == $report_row['client_name'] && $rowcnt <= $report_cnt)
      {
         if ($lineno == 0 || $lineno > $maxline)
         {
           if($lineno > $maxline)
	       { 
?>
    </table>
    </td>
    </tr>
    </table>
    <BR CLASS="pageEnd">
    <?php          $ind = 1; 
	       }          
           $pageno = $pageno + 1 ;
?>
    <table width="950" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="08%">&nbsp;</td>
                        <td width="72%">&nbsp;</td>
                        <td width="08%">&nbsp;</td>
                        <td width="12%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="report_label_text" colspan="4" align="center">
                            <b><?php echo strtoupper('Sinha & Co')?></b></td>
                    </tr>
                    <tr>
                        <td class="report_label_text" colspan="4" align="center">
                            <b><?php echo strtoupper($report_desc).' '.date('d-m-Y').' (DATE-WISE/CLIENT-WISE)' ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="report_label_text">Client</td>
                        <td class="report_label_text">
                            &nbsp;:&nbsp;<b><?php if($report_row['client_code'] != '%') { echo strtoupper($report_row['client_name'].'['.$report_row['client_code'].']') ; } else { echo 'ALL' ; } ?></b>
                        </td>
                        <td class="report_label_text" align="right">&nbsp;&nbsp;</td>
                        <td class="report_label_text">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="report_label_text">Matter</td>
                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $report_row['matter_desc'] ?></b></td>
                        <td class="report_label_text" align="right" colspan="2">Page&nbsp;&nbsp;<?php echo $pageno?>
                        </td>
                    </tr>
                    <tr>
                        <td class="report_label_text">Court</td>
                        <td class="report_label_text">&nbsp;:&nbsp;<b><?php echo $report_row['court_desc']?></b></td>
                        <td class="report_label_text">&nbsp;</td>
                        <td class="report_label_text">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="grid_header">
                <table width="100%" border="0" align="left" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="10%" align="left" class="report_detail_tb">Bill Srl</td>
                        <td width="9%" align="left" class="report_detail_tb">Bill Date</td>
                        <td width="44%" align="left" class="report_detail_tb">Matter</td>
                        <td width="27%" align="left" class="report_detail_tb">&nbsp;Court&nbsp;</td>
                        <td width="10%" align="right" class="report_detail_tb">Amount&nbsp;</td>
                    </tr>
                    <?php
             $lineno     = 8 ;
             $pclientind = 'Y' ;
         } 
       
         if ($pclientind == 'Y') 
		 { 
?>
                    <tr>
                        <td align="left" class="report_detail_none" colspan="5">
                            <b><?php echo $report_row['client_name'];?></b></td>
                    </tr>
                    <?php
           $pclientind = 'N' ;
           $lineno = $lineno + 1 ;
         }
?>
                    <tr>
                        <td align="left" class="report_detail_none" style="vertical-align:top">
                            <?php echo $report_row['serial_no']?></td>
                        <td align="left" class="report_detail_none" style="vertical-align:top">
                            <?php echo date_conv($report_row['bill_date'])?></td>
                        <td align="left" class="report_detail_none" style="vertical-align:top" rowspan="2">
                            <b>[<?php echo strtoupper($report_row['matter_code']);?>]</b>&nbsp;<span
                                class="report_detail_none"
                                style="vertical-align:top"><?php echo strtoupper($report_row['matter_desc']);?></span>
                        </td>
                        <td align="left" class="report_detail_none" style="vertical-align:top">
                            &nbsp;<?php echo strtoupper($report_row['court_desc'])?>&nbsp;</td>
                        <td align="right" class="report_detail_none" style="vertical-align:top">
                            <?php echo number_format($report_row['bill_amount'],2,'.',''); ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" class="report_detail_none" style="vertical-align:top">&nbsp;</td>
                        <td align="left" class="report_detail_none" style="vertical-align:top">&nbsp;</td>
                        <td align="left" class="report_detail_none" style="vertical-align:top">&nbsp;</td>
                        <td align="right" class="report_detail_none" style="vertical-align:top">&nbsp;</td>
                    </tr>
                    <?php 
                    $total= $total+$report_row['bill_amount'];
         $lineno      = $lineno + 2;
         $report_row = ($rowcnt < $report_cnt) ? $bill_qry[$rowcnt] : $report_row; 
         $rowcnt      = $rowcnt + 1 ;
        }
       
?>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="report_detail_none" colspan="4">&nbsp;<b>** CLIENT TOTAL **</b></td>
                        <td class="report_detail_none" align="right">
                            <b><?php echo number_format($total,2,'.',''); ?></b>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <hr width="100%" color="#000000" size="1">
                        </td>
                    </tr>

                    <?php  
      $gtotal = $gtotal + $total ;
      $lineno = $lineno + 3;

}
?>

                    <tr>
                        <td align="center" class="report_detail_none" colspan="4"><b>*** GRAND TOTAL ***</b></td>
                        <td class="report_detail_none" align="right">
                            <b><?php echo number_format($gtotal,2,'.',''); ?></b>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <hr size="2" noshade>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>