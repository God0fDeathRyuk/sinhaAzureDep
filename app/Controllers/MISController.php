<?php

namespace App\Controllers; 

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MISController extends BaseController
{
    public function __construct() {   
        $db = $this->db = db_connect();
        $temp_db = $this->temp_db = db_connect('temp');
    }

    /************************************ BILLING ******************************************/

    public function os_bill_summary() {
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $ason_date       = isset($_REQUEST['ason_date'])    ?$_REQUEST['ason_date']       : NULL ;  $ason_date_ymd = date_conv($ason_date);
                $branch_code     = isset($_REQUEST['branch_code'])  ?$_REQUEST['branch_code']     : NULL ;
                $group_code      = isset($_REQUEST['client_group_code'])   ?$_REQUEST['client_group_code']      : NULL ;  if($group_code  == '') { $group_code  = '%' ; }
                // echo '<pre>';print_r($group_code);die;
                $client_code     = isset($_REQUEST['client_code'])  ?$_REQUEST['client_code']     : NULL ;  if($client_code == '') { $client_code = '%' ; }
                $initial_code    = isset($_REQUEST['initial_code']) ?$_REQUEST['initial_code']    : NULL ;   
                $collectable_ind = isset($_REQUEST['initial_code']) ?$_REQUEST['collectable_ind'] : NULL ;   
                $report_seq      = isset($_REQUEST['initial_code']) ?$_REQUEST['report_seq']      : NULL  ;
                $os_order        = isset($_REQUEST['initial_code']) ?$_REQUEST['os_order']        : NULL ;   
                $group_name      = ($group_code  != '%') ? get_code_desc('022',$group_code)  : 'ALL' ;   
                $client_name     = ($client_code != '%') ? get_client_name($client_code)     : 'ALL' ;
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;   

                $osbill_sql = $report_desc = '';
                switch($report_seq) {
                    case 'C' : 
                        if($os_order=='D') { $report_desc = 'LIST OF CLIENT-WISE O/S BILL(S) [DESCENDING]'  ; $order_by_clause = 'order by 8 desc' ; } 
                        if($os_order=='A') { $report_desc = 'LIST OF CLIENT-WISE O/S BILL(S) [ASSCENDING]'  ; $order_by_clause = 'order by 8'      ; }
                        if($os_order=='C') { $report_desc = 'LIST OF CLIENT-WISE O/S BILL(S) [CLIENT-WISE]' ; $order_by_clause = 'order by 2'      ; }
        
                        $osbill_sql = "select x.client_code, x.client_name, x.initial_code, sum(x.tot_amount) tot_amount, sum(x.adj_amount) adj_amount, sum(x.bal_amount) bal_amount, sum(x.uaj_amount) uaj_amount, sum(x.net_amount) net_amount 
                            from (select a.client_code,b.client_name,a.initial_code 
                            ,sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) tot_amount 
                            ,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) adj_amount 
                            ,sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) bal_amount,0 uaj_amount
                            ,sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) net_amount
                            from bill_detail a, client_master b  
                            where a.branch_code like '$branch_code'
                            and a.bill_date <= '$ason_date_ymd'
                            and a.client_code like '$client_code'
                            and a.initial_code like '$initial_code'
                            and a.collectable_ind like '$collectable_ind'
                            and a.client_code = b.client_code
                            and b.client_group_code like '$group_code'
                            and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
                            ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                            (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                            (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                            and (a.cancel_ind is NULL or a.cancel_ind = '' or a.cancel_ind = 'N')
                            group by a.client_code, b.client_name, a.initial_code
                            union all
                            select a.payee_payer_code,a.payee_payer_name,d.initial_code,0 tot_amount,0 adj_amount,0 bal_amount
                            ,sum(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) uaj_amount
                            ,sum(0-(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0))) net_amount
                            from advance_details a, client_master b, ledger_trans_hdr c, fileinfo_header d
                            where a.branch_code like '$branch_code'
                            and a.client_code like '$client_code'
                            and a.matter_code = d.matter_code
                            and d.initial_code like '$initial_code'
                            and a.client_code =  b.client_code
                            and b.client_group_code like '$group_code' 
                            and a.ref_ledger_serial_no = c.serial_no
                            and c.doc_date <= '$ason_date_ymd' 
                            and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0
                            group by a.payee_payer_code, a.payee_payer_name, d.initial_code
                            ) x 
                            group by x.client_code,x.client_name, x.initial_code ".$order_by_clause ; 
                    break;
                    case 'G' :
                        if($os_order=='D') { $report_desc = 'LIST OF CLIENT GROUP-WISE O/S BILL(S) [DESCENDING]'  ; $order_by_clause = 'order by 2,10 desc' ; } 
                        if($os_order=='A') { $report_desc = 'LIST OF CLIENT GROUP-WISE O/S BILL(S) [ASSCENDING]'  ; $order_by_clause = 'order by 2,10'      ; }
                        if($os_order=='C') { $report_desc = 'LIST OF CLIENT GROUP-WISE O/S BILL(S) [CLIENT-WISE]' ; $order_by_clause = 'order by 2,4'       ; }

                        $osbill_sql = "select x.client_group_code, y.code_desc, x.client_code, x.client_name, x.initial_code, sum(x.tot_amount) tot_amount, sum(x.adj_amount) adj_amount, sum(x.bal_amount) bal_amount, sum(x.uaj_amount) uaj_amount, sum(x.net_amount) net_amount 
                            from (select b.client_group_code,a.client_code,b.client_name,a.initial_code
                            ,sum(ifnull(a.bill_amount_inpocket,0)    + ifnull(a.bill_amount_outpocket,0)    + ifnull(a.bill_amount_counsel,0)    + ifnull(a.service_tax_amount,0)) tot_amount
                            ,sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) adj_amount
                            ,sum((ifnull(a.bill_amount_inpocket,0)   + ifnull(a.bill_amount_outpocket,0)    + ifnull(a.bill_amount_counsel,0)    + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) bal_amount
                            ,0 uaj_amount
                            ,sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) net_amount
                            from bill_detail a, client_master b  
                            where a.bill_date <= '$ason_date_ymd'
                            and a.client_code like '$client_code'
                            and a.initial_code like '$initial_code'
                            and a.collectable_ind like '$collectable_ind'
                            and a.client_code = b.client_code
                            and b.client_group_code like '$group_code'
                            and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel, 0) + ifnull(a.service_tax_amount,0)) -
                            ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                            (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                            (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                            and (a.cancel_ind is NULL or a.cancel_ind = '' or a.cancel_ind = 'N')
                            group by b.client_group_code, a.client_code, b.client_name,a.initial_code
                            union all
                            select b.client_group_code,a.payee_payer_code,a.payee_payer_name,d.initial_code,0 tot_amount,0 adj_amount,0 bal_amount
                            ,sum(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) uaj_amount,sum(0-(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0))) net_amount
                            from advance_details a, client_master b, ledger_trans_hdr c, fileinfo_header d
                            where a.client_code like '$client_code'
                            and a.matter_code = d.matter_code
                            and d.initial_code like '$initial_code'
                            and a.client_code =  b.client_code
                            and b.client_group_code like '$group_code' 
                            and a.ref_ledger_serial_no = c.serial_no
                            and c.doc_date <= '$ason_date_ymd' 
                            and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0
                            group by b.client_group_code, a.payee_payer_code, a.payee_payer_name,d.initial_code
                            ) x, code_master y
                            where x.client_group_code = y.code_code 
                            and y.type_code = '022'	 
                            group by x.client_group_code,y.code_desc,x.client_code,x.client_name,x.initial_code ".$order_by_clause  ; 
                                    
                    break;
                }
                
                $osbill_qry = $this->db->query($osbill_sql)->getResultArray();
                $osbill_cnt = count($osbill_qry);

                if(empty($osbill_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "osbill_cnt" => $osbill_cnt,
                    "report_desc" => $report_desc,
                    "branch_name" => $branch_name,
                    "ason_date" => $ason_date,
                    "requested_url" => $requested_url,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/MIS/Billing/os_bill_summary", compact("osbill_qry", "params", 'report_type', 'report_seq'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render(); 
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else if($output_type == 'Excel') {
                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    if($report_seq == 'C') {
                        $fileName = 'MIS-Bill-Summary-Clientwise-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Client', 'Initial', 'Total', 'Settled', 'Balance', 'Unadjusted', 'Net'];
                        $column = 'A'; $rows++;

                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value

                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;

                        $tbtot_amount = 0 ; 
                        $tbadj_amount = 0 ; 
                        $tbbal_amount  = 0;
                        $tbuaj_amount  = 0;
                        $tbnet_amount  = 0;
                        $ttbal_amount = 0 ;
                        $ttuaj_amount = 0 ;
                        $ttnet_amount = 0 ;
                        $rowcnt       = 1 ;
                        $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;  
                        $report_cnt   = $params['osbill_cnt'] ;
                        while ($rowcnt <= $report_cnt) {
                            $sheet->setCellValue('A' . $rows, strtoupper($report_row['client_name']));
                            $sheet->setCellValue('B' . $rows, strtoupper($report_row['initial_code']));
                            $sheet->setCellValue('C' . $rows, ($report_row['tot_amount'] > 0) ? number_format($report_row['tot_amount'],2,'.','') : '');
                            $sheet->setCellValue('D' . $rows, ($report_row['adj_amount']>0) ? number_format($report_row['adj_amount'],2,'.','') : '');
                            $sheet->setCellValue('E' . $rows, ($report_row['bal_amount']>0) ? number_format($report_row['bal_amount'],2,'.','') : '');
                            $sheet->setCellValue('F' . $rows, ($report_row['uaj_amount']>0) ? number_format($report_row['uaj_amount'],2,'.','') : '');
                            $sheet->setCellValue('G' . $rows, ($report_row['net_amount']>=0) ? number_format($report_row['net_amount'],2,'.','') : '('.number_format(abs($report_row['net_amount']),2,'.','').')');
                            
                            $tbtot_amount += $report_row['tot_amount'] ;
                            $tbadj_amount += $report_row['adj_amount'] ;
                            $tbbal_amount += $report_row['bal_amount'] ;
                            $tbuaj_amount += $report_row['uaj_amount'] ;
                            $tbnet_amount += $report_row['net_amount'] ;
                            
                            $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;    
                            $rowcnt = $rowcnt + 1 ;

                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        }  
                        $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                        $sheet->setCellValue('C' . $rows, ($tbtot_amount > 0) ? number_format($tbtot_amount,2,'.','') : ''); 
                        $sheet->setCellValue('D' . $rows, ($tbadj_amount > 0) ? number_format($tbadj_amount,2,'.','') : ''); 
                        $sheet->setCellValue('E' . $rows, ($tbbal_amount > 0) ? number_format($tbbal_amount,2,'.','') : ''); 
                        $sheet->setCellValue('F' . $rows, ($tbuaj_amount > 0) ? number_format($tbuaj_amount,2,'.','') : ''); 
                        $sheet->setCellValue('G' . $rows, ($tbnet_amount >= 0) ? number_format($tbnet_amount,2,'.','') : '('.number_format(abs($tbnet_amount),2,'.','').')'); 

                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                        $sheet->mergeCells('A' . $rows . ':B' . $rows);
                        $rows++;
                    } else if($report_seq == 'G') {

                        $fileName = 'MIS-Bill-Summary-Client-Groupwise-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Client Name', 'Initial', 'Total', 'Settled', 'Balance', 'Unadjusted', 'Net'];
                        $column = 'A'; $rows++;

                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value

                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;

                        $tbtot_amount = 0 ; 
                        $tbadj_amount = 0 ; 
                        $tbbal_amount = 0 ;
                        $ttuaj_amount = 0 ;
                        $ttnet_amount = 0 ;
                        $rowcnt       = 1 ;
                        $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;
                        $report_cnt   = $params['osbill_cnt'] ;
                        while ($rowcnt <= $report_cnt) {
                            $gbtot_amount = 0 ; 
                            $gbadj_amount = 0 ; 
                            $gbbal_amount = 0 ;
                            $gtuaj_amount = 0 ;
                            $gtnet_amount = 0 ;
                            $pgroupind    = 'Y';
                            $pgroupcd     = $report_row['client_group_code'] ;
                            $pgroupnm     = $report_row['code_desc'] ;
                            while ($pgroupcd == $report_row['client_group_code'] && $rowcnt <= $report_cnt) {
                                if ($pgroupind == 'Y') { 
                                    $sheet->setCellValue('A' . $rows, strtoupper($pgroupnm));

                                    $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                    $style->getFont()->setBold(true);
                                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                    $sheet->mergeCells('A' . $rows . ':G' . $rows);
                                    $rows++;
                                }
                                $sheet->setCellValue('A' . $rows, strtoupper($report_row['client_name']));
                                $sheet->setCellValue('B' . $rows, strtoupper($report_row['initial_code']));
                                $sheet->setCellValue('C' . $rows, ($report_row['tot_amount'] > 0) ? number_format($report_row['tot_amount'],2,'.','') : '');
                                $sheet->setCellValue('D' . $rows, ($report_row['adj_amount']>0) ? number_format($report_row['adj_amount'],2,'.','') : '');
                                $sheet->setCellValue('E' . $rows, ($report_row['bal_amount']>0) ? number_format($report_row['bal_amount'],2,'.','') : '');
                                $sheet->setCellValue('F' . $rows, ($report_row['uaj_amount']>0) ? number_format($report_row['uaj_amount'],2,'.','') : '');
                                $sheet->setCellValue('G' . $rows, ($report_row['net_amount']>=0) ? number_format($report_row['net_amount'],2,'.','') : '('.number_format(abs($report_row['net_amount']),2,'.','').')');
                                
                                $pgroupind = 'N' ; 
                                $gbtot_amount += $report_row['tot_amount'] ;
                                $gbadj_amount += $report_row['adj_amount'] ;
                                $gbbal_amount += $report_row['bal_amount'] ;
                                $gtuaj_amount += $report_row['uaj_amount'] ;
                                $gtnet_amount += $report_row['net_amount'] ;
                                
                                $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;   
                                $rowcnt = $rowcnt + 1 ;
                        
                                $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                $rows++;
                            }  
                            $sheet->setCellValue('A' . $rows, 'GROUP TOTAL'); 
                            $sheet->setCellValue('C' . $rows, ($gbtot_amount > 0) ? number_format($gbtot_amount,2,'.','') : ''); 
                            $sheet->setCellValue('D' . $rows, ($gbadj_amount > 0) ? number_format($gbadj_amount,2,'.','') : ''); 
                            $sheet->setCellValue('E' . $rows, ($gbbal_amount > 0) ? number_format($gbbal_amount,2,'.','') : ''); 
                            $sheet->setCellValue('F' . $rows, ($gtuaj_amount > 0) ? number_format($gtuaj_amount,2,'.','') : ''); 
                            $sheet->setCellValue('G' . $rows, ($gtnet_amount >= 0) ? number_format($gtnet_amount,2,'.','') : '('.number_format(abs($gtnet_amount),2,'.','').')'); 

                            $tbtot_amount += $gbtot_amount ;
                            $tbadj_amount += $gbadj_amount ;
                            $tbbal_amount += $gbbal_amount ;
                            $ttuaj_amount += $gtuaj_amount ;
                            $ttnet_amount += $gtnet_amount ;

                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                            $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8efe3');
                            $sheet->mergeCells('A' . $rows . ':B' . $rows);
                            $rows++;
                        }
                        $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                        $sheet->setCellValue('C' . $rows, ($tbtot_amount > 0) ? number_format($tbtot_amount,2,'.','') : ''); 
                        $sheet->setCellValue('D' . $rows, ($tbadj_amount > 0) ? number_format($tbadj_amount,2,'.','') : ''); 
                        $sheet->setCellValue('E' . $rows, ($tbbal_amount > 0) ? number_format($tbbal_amount,2,'.','') : ''); 
                        $sheet->setCellValue('F' . $rows, ($ttuaj_amount > 0) ? number_format($ttuaj_amount,2,'.','') : ''); 
                        $sheet->setCellValue('G' . $rows, ($ttnet_amount >= 0) ? number_format($ttnet_amount,2,'.','') : '('.number_format(abs($ttnet_amount),2,'.','').')'); 
                            
                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                        $sheet->mergeCells('A' . $rows . ':B' . $rows);
                        $rows++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                } else return view("pages/MIS/Billing/os_bill_summary", compact("osbill_qry", "params", 'report_seq'));
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4533', 'group_help_id' => '4071'] ;
            $data['collectable_ind']   = 'C';
            $data['initial_qry'] = $this->db->query("select * from initial_master order by initial_name ")->getResultArray();
            $data['ason_date'] = date('d-m-Y');
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Billing/os_bill_summary", compact("data", "displayId"));
        }
    }

    public function os_bill_details() {
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {
              
                $ason_date       = $_REQUEST['ason_date'] ;    $ason_date_ymd = date_conv($ason_date);
                $branch_code     = $_REQUEST['branch_code'] ;
                $report_seq      = $_REQUEST['report_seq'] ;
                $group_code      = $_REQUEST['client_group_code'] ;   if($group_code  == '') { $group_code  = '%' ; }
                $group_name      = $_REQUEST['client_group_name'] ;   
                $client_code     = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name     = $_REQUEST['client_name'] ; 
                $client_name   = str_replace('_|_','&', $client_name) ;
                $client_name   = str_replace('-|-',"'", $client_name) ;  
                $collectable_ind = $_REQUEST['collectable_ind'] ;   
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];
                $user_id        = session()->userId ;
                $curr_time      = $logdt_qry['current_time'];
                $curr_date      = $logdt_qry['current_dmydate'];
                $curr_day       = substr($curr_date,0,2) ;
                $curr_month     = substr($curr_date,3,2) ; 
                $curr_year      = substr($curr_date,6,4) ;
                $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);

                $osbill_sql = $report_desc = $old_matter_desc = $reference_type = '';
                switch($report_seq) {
                    case 'C' : 
                        $report_desc     = 'LIST OF CLIENT-WISE O/S BILL(S)' ;
                        $old_matter_desc = 'ALL MATTERS UPTO 30/09/2003' ;

                        $myosbilltbl = $temp_id.'_mis_osbill_table';
                        $this->temp_db->query("drop table IF EXISTS $myosbilltbl");
                        $this->temp_db->query("create table $myosbilltbl(branch_code varchar(4), group_code varchar(3), client_code varchar(6), client_name varchar(50), level_ind varchar(1), doc_no varchar(25), doc_date date, matter_code varchar(6), matter_desc varchar(200), tot_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2))") ;
                      
                        $osbill_sql = "insert into $myosbilltbl (branch_code, group_code, client_code, client_name, level_ind, doc_no, doc_date, matter_code, matter_desc, tot_amount, adj_amount, bal_amount) 
                                    select a.branch_code, b.client_group_code, upper(a.client_code) client_code, b.client_name, '1', concat(a.fin_year,'/',a.bill_no), a.bill_date, a.matter_code, if(substring(a.matter_code,1,1)='0','$old_matter_desc',if(c.matter_desc1 != '',concat(c.matter_desc1,'/',c.matter_desc2),c.matter_desc2)),
                                    (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)),
                                    (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)),
                                    ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)))
                                    from sinhaco.client_master b, sinhaco.bill_detail a left outer join sinhaco.fileinfo_header c on c.matter_code = a.matter_code
                                    where a.branch_code like '$branch_code'
                                    and a.bill_date <= '$ason_date_ymd'
                                    and a.collectable_ind = 'C'
                                    and a.client_code like '$client_code'
                                    and a.client_code = b.client_code
                                    and b.client_group_code like '$group_code'
                                    and (ifnull(a.bill_amount_inpocket,0)    + ifnull(a.bill_amount_outpocket,0)    + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
                                    ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                                    (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                                    (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                                    and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                    union all
                                    select a.branch_code, b.client_group_code, upper(a.payee_payer_code) payee_payer_code, a.payee_payer_name, '2', concat(c.doc_type,'/',c.fin_year,'/',c.daybook_code,'/',c.doc_no), c.doc_date, '', 'Unadjusted Advance', ifnull(a.gross_amount,0), ifnull(a.adjusted_amount,0), (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0))
                                    from sinhaco.advance_details a, sinhaco.client_master b, sinhaco.ledger_trans_hdr c
                                    where a.branch_code like '$branch_code'
                                    and a.client_code like '$client_code'
                                    and a.client_code =  b.client_code
                                    and b.client_group_code like '$group_code' 
                                    and a.ref_ledger_serial_no = c.serial_no
                                    and c.doc_date <= '$ason_date_ymd' 
                                    and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0" ;
                        $this->temp_db->query($osbill_sql); 
                
                        $osbill_sql = "select a.* from $myosbilltbl a order by a.client_name, a.level_ind, a.doc_date, a.doc_no"  ;
                    break;
                    case 'G' :
                        $report_desc     = 'LIST OF CLIENT GROUP-WISE O/S BILL(S)' ;
                        $old_matter_desc = 'ALL MATTERS UPTO 30/09/2003' ;

                        $reference_type  = $_REQUEST['reference_type'] ;  if($reference_type == '') { $reference_type = '%' ; }
                        
                        $myosbilltbl = $temp_id.'_mis_osbill_table';
                        $this->temp_db->query("drop table IF EXISTS $myosbilltbl");
                        $this->temp_db->query("create table $myosbilltbl(branch_code varchar(4), group_code varchar(3), reference_type varchar(3), client_code varchar(6), client_name varchar(50), level_ind varchar(1), doc_no varchar(25), doc_date date, matter_code varchar(6), matter_desc varchar(200), tot_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2))") ;

                        $osbill_sql = "insert into $myosbilltbl (branch_code, group_code, reference_type, client_code, client_name, level_ind, doc_no, doc_date, matter_code, matter_desc, tot_amount, adj_amount, bal_amount) 
                                select a.branch_code, b.client_group_code, c.reference_type_code, a.client_code, b.client_name, '1', concat(a.fin_year,'/',a.bill_no), a.bill_date, a.matter_code, if(substring(a.matter_code,1,1)='0','$old_matter_desc',if(c.matter_desc1 != '',concat(c.matter_desc1,'/',c.matter_desc2),c.matter_desc2)),
                                (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)),
                                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)),
                                ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)))
                                from sinhaco.client_master b, sinhaco.bill_detail a left outer join sinhaco.fileinfo_header c on c.matter_code = a.matter_code
                                where a.branch_code like '$branch_code'
                                and a.bill_date <= '$ason_date_ymd'
                                and a.collectable_ind = 'C'
                                and a.client_code like '$client_code'
                                and a.client_code = b.client_code
                                and b.client_group_code like '$group_code'
                                and ifnull(c.reference_type_code,'') like '$reference_type'
                                and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0)) -
                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                union all
                                select a.branch_code, b.client_group_code, '', a.payee_payer_code, a.payee_payer_name, '2', concat(c.doc_type,'/',c.fin_year,'/',c.daybook_code,'/',c.doc_no), c.doc_date, '', 'Unadjusted Advance', ifnull(a.gross_amount,0), ifnull(a.adjusted_amount,0), (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0))
                                from sinhaco.advance_details a, sinhaco.client_master b, sinhaco.ledger_trans_hdr c
                                where a.branch_code like '$branch_code'
                                and a.client_code like '$client_code'
                                and a.client_code =  b.client_code
                                and b.client_group_code like '$group_code' 
                                and a.ref_ledger_serial_no = c.serial_no
                                and c.doc_date <= '$ason_date_ymd' 
                                and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0  " ; 
                    $this->temp_db->query($osbill_sql);
                    
                    $osbill_sql = "select a.*, b.code_desc group_name, ifnull(c.code_desc,'') reference_name from ".'_temp.'.$myosbilltbl." a, sinhaco.code_master b, sinhaco.code_master c where a.group_code = b.code_code and b.type_code = '022' and ifnull(a.reference_type,'') = c.code_code and c.type_code = '007' order by b.code_desc, a.client_name, a.level_ind, a.doc_date"  ;
                    break;
                }
                $osbill_qry = $this->temp_db->query($osbill_sql)->getResultArray();
                $osbill_cnt = count($osbill_qry);

                if(empty($osbill_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "report_desc" => $report_desc,
                    "branch_name" => $branch_name,
                    "osbill_cnt" => $osbill_cnt,
                    "client_name" => $client_name,
                    "client_code" => $client_code,
                    "ason_date" => $ason_date,
                    "requested_url" => $requested_url,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/MIS/Billing/os_bill_details", compact("osbill_qry", "params", 'report_type', 'report_seq'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render(); 
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else if($output_type == 'Excel') { 
                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    if($report_seq == 'C') {
                        $fileName = 'MIS-Bill-Details-Clientwise-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Bill No./Doc No', 'Doc Date', 'Description', '', 'Total', 'Settled', 'Balance'];
                        $column = 'A'; $rows++;

                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value

                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->mergeCells('C' . $rows . ':D' . $rows);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;

                        $tbbal_amount = 0 ; 
                        $tabal_amount = 0 ; 
                        $tnbal_amount = 0 ;
                        $rowcnt       = 1 ;
                        $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ; 
                        $report_cnt   = $params['osbill_cnt'] ;
                        while ($rowcnt <= $report_cnt) {
                            $cbbal_amount = 0 ; 
                            $cabal_amount = 0 ; 
                            $cnbal_amount = 0 ;
                            $pclientind   = 'Y' ;
                            $pclientcd    = $report_row['client_code'] ;
                            $pclientnm    = $report_row['client_name'] ;
                            while ($pclientcd == $report_row['client_code'] && $rowcnt <= $report_cnt) {
                                $plevel = $report_row['level_ind'];
                                while ($pclientcd == $report_row['client_code'] && $plevel == $report_row['level_ind'] && $rowcnt <= $report_cnt) {
                                    if ($pclientind == 'Y') {
                                        $sheet->setCellValue('A' . $rows, strtoupper($pclientnm));

                                        $pclientind = 'N' ;
                                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                        $style->getFont()->setBold(true);
                                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                        $sheet->mergeCells('A' . $rows . ':G' . $rows);
                                        $rows++;
                                    }
                                    $sheet->setCellValue('A' . $rows, $report_row['doc_no']);
                                    $sheet->setCellValue('B' . $rows, date_conv($report_row['doc_date']));
                                    $sheet->setCellValue('C' . $rows, $report_row['matter_desc']);
                                    $sheet->setCellValue('E' . $rows, ($report_row['tot_amount']>0) ? $report_row['tot_amount'] : '');
                                    $sheet->setCellValue('F' . $rows, ($report_row['adj_amount']>0) ? $report_row['adj_amount'] : '');
                                    $sheet->setCellValue('G' . $rows, ($report_row['bal_amount']>0) ? $report_row['bal_amount'] : '');

                                    if ($plevel=='1') { $cbbal_amount = $cbbal_amount + $report_row['bal_amount'] ; } else { $cabal_amount = $cabal_amount + $report_row['bal_amount'] ; }
                                    $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;   
                                    $rowcnt = $rowcnt + 1 ;

                                    $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                    $rows++;
                                }  
                            }
                            $cnbal_amount = $cbbal_amount - $cabal_amount ;

                            $sheet->setCellValue('A' . $rows, 'CLIENT TOTAL'); 
                            $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                            $sheet->setCellValue('C' . $rows, number_format($cbbal_amount,2,'.','')); 
                            $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                            $sheet->setCellValue('E' . $rows, number_format($cabal_amount,2,'.','')); 
                            $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                            $sheet->setCellValue('G' . $rows, ($cnbal_amount >= 0) ? number_format($cnbal_amount,2,'.','') : '('.number_format(abs($cnbal_amount),2,'.','').')'); 

                            $tbbal_amount = $tbbal_amount + $cbbal_amount ;
                            $tabal_amount = $tabal_amount + $cabal_amount ;

                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                            $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8efe3');
                            $rows++;
                        }
                        $tnbal_amount = $tbbal_amount - $tabal_amount ;

                        $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                        $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                        $sheet->setCellValue('C' . $rows, number_format($tbbal_amount,2,'.','')); 
                        $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                        $sheet->setCellValue('E' . $rows, number_format($tabal_amount,2,'.','')); 
                        $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                        $sheet->setCellValue('G' . $rows, ($tnbal_amount >= 0) ? number_format($tnbal_amount,2,'.','') : '('.number_format(abs($tnbal_amount),2,'.','').')'); 

                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                        $rows++;
                    } else if($report_seq == 'G') {
                        $fileName = 'MIS-Bill-Details-Client-Groupwise-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Doc No', 'Doc Date', 'Description', '', 'Total', 'Settled', 'Balance'];
                        $column = 'A'; $rows++;

                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value

                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->mergeCells('C' . $rows . ':D' . $rows);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;

                        $tbbal_amount = 0 ; 
                        $tabal_amount = 0 ; 
                        $tnbal_amount = 0 ;
                        $rowcnt       = 1 ;
                        $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;  
                        $report_cnt   = $params['osbill_cnt'] ;
                        while ($rowcnt <= $report_cnt) {
                            $gbbal_amount = 0 ; 
                            $gabal_amount = 0 ; 
                            $gnbal_amount = 0 ;
                            $pgroupcd     = $report_row['group_code'] ;
                            $pgroupnm     = $report_row['group_name'] ;
                            while ($pgroupcd == $report_row['group_code']  && $rowcnt <= $report_cnt) {
                                $cbbal_amount = 0 ; 
                                $cabal_amount = 0 ; 
                                $cnbal_amount = 0 ;
                                $pclientind   = 'Y' ;
                                $pclientcd    = $report_row['client_code'] ;
                                $pclientnm    = $report_row['client_name'] ;
                                $preference   = $report_row['reference_name'] ;
                                $prefcode     = $report_row['reference_type'] ;
                                while ($pgroupcd == $report_row['group_code'] && $pclientcd == $report_row['client_code'] && $prefcode == $report_row['reference_type'] && $rowcnt <= $report_cnt) {
                                    $plevel = $report_row['level_ind'];
                                    while ($pgroupcd == $report_row['group_code'] && $pclientcd == $report_row['client_code'] && $prefcode == $report_row['reference_type'] && $plevel == $report_row['level_ind'] && $rowcnt <= $report_cnt) {
                                        if ($pclientind == 'Y') {
                                            $sheet->setCellValue('A' . $rows, strtoupper($pclientnm));

                                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                            $style->getFont()->setBold(true);
                                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                            $sheet->mergeCells('A' . $rows . ':G' . $rows);
                                            $rows++;

                                            $sheet->setCellValue('A' . $rows, strtoupper($preference));
                                            $pclientind = 'N' ;

                                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                            $style->getFont()->setBold(true);
                                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                            $sheet->mergeCells('A' . $rows . ':G' . $rows);
                                            $rows++;
                                        }
                                        $sheet->setCellValue('A' . $rows, $report_row['doc_no']);
                                        $sheet->setCellValue('B' . $rows, date_conv($report_row['doc_date']));
                                        $sheet->setCellValue('C' . $rows, $report_row['matter_desc']);
                                        $sheet->setCellValue('E' . $rows, ($report_row['tot_amount']>0) ? $report_row['tot_amount'] : '');
                                        $sheet->setCellValue('F' . $rows, ($report_row['adj_amount']>0) ? $report_row['adj_amount'] : '');
                                        $sheet->setCellValue('G' . $rows, ($report_row['bal_amount']>0) ? $report_row['bal_amount'] : '');

                                        if ($report_row['level_ind']=='1') { $cbbal_amount = $cbbal_amount + $report_row['bal_amount'] ; } else { $cabal_amount = $cabal_amount + $report_row['bal_amount'] ; }
                                        $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;   
                                        $rowcnt = $rowcnt + 1 ;

                                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                        $rows++;
                                    } 
                                }
                                $cnbal_amount = $cbbal_amount - $cabal_amount ; 
                                     
                                $sheet->setCellValue('A' . $rows, 'CLIENT TOTAL'); 
                                $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                                $sheet->setCellValue('C' . $rows, number_format($cbbal_amount,2,'.','')); 
                                $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                                $sheet->setCellValue('E' . $rows, number_format($cabal_amount,2,'.','')); 
                                $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                                $sheet->setCellValue('G' . $rows, ($cnbal_amount >= 0) ? number_format($cnbal_amount,2,'.','') : '('.number_format(abs($cnbal_amount),2,'.','').')'); 

                                $gbbal_amount = $gbbal_amount + $cbbal_amount ;
                                $gabal_amount = $gabal_amount + $cabal_amount ;

                                $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                $style->getFont()->setBold(true);
                                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                                $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                                $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                                $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                                $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF99');
                                $rows++;
                            }
                            $gnbal_amount = $gbbal_amount - $gabal_amount ;

                            $sheet->setCellValue('A' . $rows, 'GROUP TOTAL'); 
                            $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                            $sheet->setCellValue('C' . $rows, number_format($gbbal_amount,2,'.','')); 
                            $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                            $sheet->setCellValue('E' . $rows, number_format($gabal_amount,2,'.','')); 
                            $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                            $sheet->setCellValue('G' . $rows, ($gnbal_amount >= 0) ? number_format($gnbal_amount,2,'.','') : '('.number_format(abs($gnbal_amount),2,'.','').')'); 

                            $tbbal_amount = $tbbal_amount + $gbbal_amount ;
                            $tabal_amount = $tabal_amount + $gabal_amount ;

                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                            $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8efe3');
                            $rows++;
                        }
                        $tnbal_amount = $tbbal_amount - $tabal_amount ;

                        $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                        $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                        $sheet->setCellValue('C' . $rows, number_format($tbbal_amount,2,'.','')); 
                        $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                        $sheet->setCellValue('E' . $rows, number_format($tabal_amount,2,'.','')); 
                        $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                        $sheet->setCellValue('G' . $rows, ($tnbal_amount >= 0) ? number_format($tnbal_amount,2,'.','') : '('.number_format(abs($tnbal_amount),2,'.','').')'); 

                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                        $rows++;
                    }

                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                } else return view("pages/MIS/Billing/os_bill_details", compact("osbill_qry", "params", 'report_seq'));
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4533', 'group_help_id' => '4071'] ;
            $data['collectable_ind']   = 'C';
            $data['reftyp_qry'] = $this->db->query("select * from code_master where type_code = '007'")->getResultArray();
            $data['ason_date'] = date('d-m-Y');
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Billing/os_bill_details", compact("data", "displayId"));
        }
    }

    public function os_bill_age_analysis() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $ason_date       = $_REQUEST['ason_date'] ;    $ason_date_ymd = date_conv($ason_date);
                $branch_code     = $_REQUEST['branch_code'] ;
                $report_seq      = $_REQUEST['report_seq'] ;
                $group_code      = $_REQUEST['client_group_code'] ;   if($group_code  == '') { $group_code  = '%' ; }
                $group_name      = $_REQUEST['client_group_name'] ;   
                $client_code     = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name     = $_REQUEST['client_name'] ;   
                $collectable_ind = $_REQUEST['collectable_ind'] ;   
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];
                $user_id        = session()->userId ;
                $login_date     = $logdt_qry['current_date'];
                $curr_time      = $logdt_qry['current_time'];
                $curr_date      = $logdt_qry['current_dmydate'];
                $curr_day       = substr($curr_date,0,2) ;
                $curr_month     = substr($curr_date,3,2) ; 
                $curr_year      = substr($curr_date,6,4) ;
                $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);

                $osbill_qry = [];
                $osbill_cnt = $report_desc = '';

                if($report_seq == 'C') {

                    $report_desc     = 'AGE ANALYSIS OF O/S BILLS (CLIENT-WISE)' ;

                    $myosbilltbl = $temp_id.'_mis_ageanalysis_table';
                    $this->temp_db->query("drop table IF EXISTS $myosbilltbl");
    
                    if($output_type == 'Report' || $output_type == 'Pdf') {
    
                        $this->temp_db->query("create table $myosbilltbl(branch_code varchar(4), client_code varchar(6), client_name varchar(50), level_ind varchar(1), no_of_days int(8), tot_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2), uaj_amount double(13,2), net_amount double(13,2))") ;
                      
                        $osbill_sql = "insert into $myosbilltbl (branch_code, client_code, client_name, level_ind, no_of_days, tot_amount, adj_amount, bal_amount, uaj_amount, net_amount) 
                                    select a.branch_code, a.client_code, b.client_name, '1', datediff('$login_date',a.bill_date)+1, sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)),
                                    sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)),
                                    sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))),0,
                                    sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) 
                                    from sinhaco.bill_detail a, sinhaco.client_master b  
                                    where a.branch_code like '$branch_code'
                                    and a.bill_date <= '$ason_date_ymd'
                                    and a.collectable_ind like '$collectable_ind'
                                    and a.client_code like '$client_code'
                                    and a.client_code = b.client_code
                                    and b.client_group_code like '$group_code'
                                    and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
                                    ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                                    (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                                    (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                                    and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                    group by a.branch_code, a.client_code, b.client_name, '1', datediff('$login_date',a.bill_date)+1
                                    union all
                                    select a.branch_code, a.payee_payer_code, a.payee_payer_name, '2', 0, 0, 0, 0, sum(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)), sum(0-(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)))
                                    from sinhaco.advance_details a, sinhaco.client_master b, sinhaco.ledger_trans_hdr c
                                    where a.branch_code like '$branch_code'
                                    and a.client_code like '$client_code'
                                    and a.client_code =  b.client_code
                                    and b.client_group_code like '$group_code' 
                                    and a.ref_ledger_serial_no = c.serial_no
                                    and c.doc_date <= '$ason_date_ymd' 
                                    and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0
                                    group by a.branch_code, a.payee_payer_code, a.payee_payer_name" ;
                        $this->temp_db->query($osbill_sql);
                        
                        $osbill_sql = "select a.branch_code,a.client_code,a.client_name,a.level_ind,a.no_of_days,a.bal_amount,a.uaj_amount,a.net_amount from $myosbilltbl a order by a.client_name,a.level_ind,a.no_of_days"  ;
                        $osbill_qry = $this->temp_db->query($osbill_sql)->getResultArray();
                        // echo'<pre>';print_r($osbill_qry);die;
                        $osbill_cnt = count($osbill_qry);
        
                        
                    } else if($output_type == 'Excel') {
    
                        $this->temp_db->query("create table $myosbilltbl(branch_code varchar(4), client_code varchar(6), client_name varchar(180),client_group_code varchar(3), client_group_name varchar(200),level_ind varchar(1), no_of_days int(8), tot_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2), uaj_amount double(13,2), net_amount double(13,2))") ;
    
                        $osbill_sql = "insert into $myosbilltbl (branch_code, client_code, client_name, client_group_code, client_group_name, level_ind, no_of_days, tot_amount, adj_amount, bal_amount, uaj_amount, net_amount) 
                                    select a.branch_code, a.client_code, b.client_name, b.client_group_code, c.code_desc, '1', datediff('$login_date',a.bill_date)+1, 
                                    sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)),
                                    sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)),
                                    sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))),0,
                                    sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) 
                                    from sinhaco.bill_detail a, sinhaco.client_master b, sinhaco.code_master c
                                    where a.branch_code like '$branch_code'
                                    and a.bill_date <= '$ason_date_ymd'
                                    and a.collectable_ind like '$collectable_ind'
                                    and a.client_code like '$client_code'
                                    and a.client_code = b.client_code
                                    and b.client_group_code like '$group_code'
                                    and b.client_group_code = c.code_code 
                                    and c.type_code LIKE '022'
                                    and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
                                    ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                                    (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                                    (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                                    and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                    group by a.branch_code, a.client_code, b.client_name, '1', datediff('$login_date',a.bill_date)+1
                                    union all
                                    select a.branch_code, a.payee_payer_code, a.payee_payer_name,b.client_group_code,d.code_desc, '2', 0, 0, 0, 0, sum(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)), sum(0-(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)))
                                    from sinhaco.advance_details a, sinhaco.client_master b, sinhaco.ledger_trans_hdr c, sinhaco.code_master d
                                    where a.branch_code like '$branch_code'
                                    and a.client_code like '$client_code'
                                    and a.client_code =  b.client_code
                                    and b.client_group_code like '$group_code' 
                                    and b.client_group_code = d.code_code 
                                    and d.type_code LIKE '022'
                                    and a.ref_ledger_serial_no = c.serial_no
                                    and c.doc_date <= '$ason_date_ymd' 
                                    and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0
                                    group by a.branch_code, a.payee_payer_code, a.payee_payer_name " ; 
                        $this->temp_db->query($osbill_sql);
                        //
                        $osbill_sql = "select a.branch_code,a.client_code,a.client_name,client_group_name,a.level_ind,a.no_of_days,a.bal_amount,a.uaj_amount,a.net_amount from $myosbilltbl a order by a.client_name,a.level_ind,a.no_of_days"  ;
                        $osbill_qry = $this->temp_db->query($osbill_sql)->getResultArray();
                        $report_cnt = count($osbill_qry);
    
                        $showActionBtns = true;
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $rows = 1;
    
                        $fileName = $headings = '';
                        $fileName = 'MIS-Bill-Age-Analysis-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Client Name', 'Client Group', 'Days (0 - 30)', 'Days (31 - 60)', 'Days (61 - 120)', 'Days (121 - 180)', 'Days (> 180)', 'Total', 'Unadj Adv', 'Net O/s'];
                        $column = 'A'; $rows++;
    
                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value
    
                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;
    
                        $rowcnt       = 1 ;
                        $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;  
                        while ($rowcnt <= $report_cnt)
                        {
                            $ctot_amt1     = 0 ;
                            $ctot_amt2     = 0 ;
                            $ctot_amt3     = 0 ;
                            $ctot_amt4     = 0 ;
                            $ctot_amt5     = 0 ;
                            $ctot_tamt     = 0 ;
                            $ctot_uamt     = 0 ;
                            $ctot_namt     = 0 ;
                            $pclientcd     = $report_row['client_code'] ;
                            $pclientnm     = $report_row['client_name'] ;
                            $group_name    = $report_row['client_group_name'] ;
                    
                            while ($pclientcd == $report_row['client_code'] && $rowcnt <= $report_cnt)
                            {
                                if     ($report_row['no_of_days'] >  180) { $ctot_amt5 = $ctot_amt5 + $report_row['bal_amount'] ; } 
                                else if($report_row['no_of_days'] >  120) { $ctot_amt4 = $ctot_amt4 + $report_row['bal_amount'] ; } 
                                else if($report_row['no_of_days'] >   60) { $ctot_amt3 = $ctot_amt3 + $report_row['bal_amount'] ; } 
                                else if($report_row['no_of_days'] >   30) { $ctot_amt2 = $ctot_amt2 + $report_row['bal_amount'] ; } 
                                else if($report_row['no_of_days'] <=  30) { $ctot_amt1 = $ctot_amt1 + $report_row['bal_amount'] ; } 
                                
                                $ctot_tamt = $ctot_tamt + $report_row['bal_amount'] ;
                                $ctot_uamt = $ctot_uamt + $report_row['uaj_amount'] ;
                                $ctot_namt = $ctot_namt + $report_row['net_amount'] ;
                                
                                $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row; 
                                $rowcnt = $rowcnt + 1 ;
                            }
                            $sheet->setCellValue('A' . $rows, strtoupper($pclientnm) );
                            $sheet->setCellValue('B' . $rows, strtoupper($group_name) );
                            $sheet->setCellValue('C' . $rows, ($ctot_amt1 >0 ) ? number_format($ctot_amt1,2,'.','') : '');
                            $sheet->setCellValue('D' . $rows, ($ctot_amt2 >0 ) ? number_format($ctot_amt2,2,'.','') : '');
                            $sheet->setCellValue('E' . $rows, ($ctot_amt3 >0 ) ? number_format($ctot_amt3,2,'.','') : '');
                            $sheet->setCellValue('F' . $rows, ($ctot_amt4 >0 ) ? number_format($ctot_amt4,2,'.','') : '');
                            $sheet->setCellValue('G' . $rows, ($ctot_amt5 >0 ) ? number_format($ctot_amt5,2,'.','') : '');
                            $sheet->setCellValue('H' . $rows, ($ctot_tamt >0 ) ? number_format($ctot_tamt,2,'.','') : '');
                            $sheet->setCellValue('I' . $rows, ($ctot_uamt >0 ) ? number_format($ctot_uamt,2,'.','') : '');
                            $sheet->setCellValue('J' . $rows, ($ctot_namt >= 0 ) ? number_format($ctot_namt,2,'.','') : '('.number_format(abs($ctot_namt),2,'.','').')');
                            
                            $style = $sheet->getStyle('A' . $rows . ':J' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        }
                        $writer = new Xlsx($spreadsheet);
                        $writer->save($fileName);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                        header('Expires: 0');
                        header('Cache-control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length:'.filesize($fileName));
                        flush();
                        readfile($fileName); 
                    }
                } else if($report_seq == 'G'){

                    $report_desc   = 'AGE ANALYSIS OF O/S BILLS (CLIENT GROUP-WISE)' ;
                    $myosbilltbl = $temp_id.'_mis_ageanalysis_table';
                    $this->temp_db->query("drop table IF EXISTS $myosbilltbl");
                    $this->temp_db->query("create table $myosbilltbl(branch_code varchar(4), group_code varchar(3), client_code varchar(6), client_name varchar(50), level_ind varchar(1), no_of_days int(8), tot_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2), uaj_amount double(13,2), net_amount double(13,2))") ;

                    $osbill_sql = "insert into $myosbilltbl (branch_code, group_code, client_code, client_name, level_ind, no_of_days, tot_amount, adj_amount, bal_amount, uaj_amount, net_amount) 
                                select a.branch_code, b.client_group_code, a.client_code, b.client_name, '1', datediff('$login_date',a.bill_date)+1, 
                                sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)),
                                sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)),
                                sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))),0,
                                sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) 
                                from sinhaco.bill_detail a, sinhaco.client_master b  
                                where a.branch_code like '$branch_code'
                                and a.bill_date <= '$ason_date_ymd'
                                and a.collectable_ind like '$collectable_ind'
                                and a.client_code like '$client_code'
                                and a.client_code = b.client_code
                                and b.client_group_code like '$group_code'
                                and (ifnull(a.bill_amount_inpocket,0)     + ifnull(a.bill_amount_outpocket,0)    + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                group by a.branch_code, b.client_group_code, a.client_code, b.client_name, '1', datediff('$login_date',a.bill_date)+1
                                union all
                                select a.branch_code, b.client_group_code, a.payee_payer_code, a.payee_payer_name, '2', 0, 0, 0, 0, sum(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)), sum(0-(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)))
                                from sinhaco.advance_details a, sinhaco.client_master b, sinhaco.ledger_trans_hdr c
                                where a.branch_code like '$branch_code'
                                and a.client_code like '$client_code'
                                and a.client_code =  b.client_code
                                and b.client_group_code like '$group_code' 
                                and a.ref_ledger_serial_no = c.serial_no
                                and c.doc_date <= '$ason_date_ymd' 
                                and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0
                                group by a.branch_code, b.client_group_code, a.payee_payer_code, a.payee_payer_name" ;
                    $this->temp_db->query($osbill_sql);
                    //
                    $osbill_sql = "select a.branch_code,a.group_code,a.client_code,a.client_name,a.level_ind,a.no_of_days,a.bal_amount,a.uaj_amount,a.net_amount,b.code_desc group_name from sinhaco.code_master b, ".'_temp.'.$myosbilltbl." a where a.group_code = b.code_code and b.type_code = '022' order by b.code_desc,a.client_name,a.level_ind,a.no_of_days"  ;
                    $osbill_qry = $this->temp_db->query($osbill_sql)->getResultArray();
                    $osbill_cnt = count($osbill_qry);

                }

                if(empty($osbill_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "report_desc" => $report_desc,
                    "branch_name" => $branch_name,
                    "osbill_cnt" => $osbill_cnt,
                    "client_name" => $client_name,
                    "client_code" => $client_code,
                    "ason_date" => $ason_date,
                    "requested_url" => $requested_url,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/MIS/Billing/os_bill_age_analysis", compact("osbill_qry", "params", 'report_type', 'report_seq'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render(); 
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/MIS/Billing/os_bill_age_analysis", compact("osbill_qry", "params", "report_seq"));
                
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4533', 'group_help_id' => '4071'] ;
            
            return view("pages/MIS/Billing/os_bill_age_analysis", compact("data", "displayId"));
        }
    }

    public function billing_count_summary() {
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $report_desc   = 'BILLING STATUS SUMMARY' ;  
                $branch_code   = $_REQUEST['branch_code'] ;
                $start_date    = $_REQUEST['start_date'] ;   if($start_date != '') {$start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01' ; }
                $end_date      = $_REQUEST['end_date'] ;     $end_date_ymd   = date_conv($end_date) ;  
                $client_code   = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name   = $_REQUEST['client_name'] ; 
                $client_name   = str_replace('_|_', '&', $client_name) ;
                $client_name   = str_replace('-|-', "'", $client_name) ;  
                $court_code    = $_REQUEST['court_code'] ;   if($court_code  == '') { $court_code  = '%' ; }
                $court_name    = $_REQUEST['court_name'] ;   
                $report_for    = $_REQUEST['report_for'] ;
                $initial_code  = $_REQUEST['initial_code'] ; if(empty($initial_code)) { $initial_code = '%' ; }
                $initial_name  = $_REQUEST['initial_name'] ;
              
                if ($report_for == 'A') { $report_for_desc = '(All)' ; } else if ($report_for == 'B') { $report_for_desc = '(Billed)' ; } else { $report_for_desc = '(Not Billed)' ; }
                if ($start_date == '' ) { $period_desc = 'UPTO '.$end_date ; } else { $period_desc = $start_date.' - '.$end_date ; } 
               
                if($initial_code  == '%') { $initial_heading = 'INITIAL : ALL'  ; } else { $initial_heading = 'INITIAL : SELECTIVE'  ; }
              
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                if ($report_for == 'A') { $having_clause = 'billnos >= 0' ; } else if ($report_for == 'B') { $having_clause = 'billnos > 0' ; } else { $having_clause = 'billnos = 0' ; } 
                
                if($output_type == 'Report' || $output_type == 'Pdf') {

                    $bill_sql = "SELECT a.court_code, a.initial_code, a.matter_code, 
                                IF(a.matter_desc1 != ' ', CONCAT(a.matter_desc1, ' : ', a.matter_desc2), a.matter_desc2) AS matter_desc, c.code_desc AS court_name, 
                                COUNT(b.bill_no) AS billnos, 
                                MAX(b.bill_date) AS billdate,
                                SUM(COALESCE(b.bill_amount_inpocket, 0) + COALESCE(b.bill_amount_outpocket, 0) + COALESCE(b.bill_amount_counsel, 0)) AS billamt, 
                                SUM(COALESCE(b.realise_amount_inpocket, 0) + COALESCE(b.realise_amount_outpocket, 0) + COALESCE(b.realise_amount_counsel, 0)) AS realamt 
                                FROM code_master c
                                JOIN fileinfo_header a ON a.court_code = c.code_code
                                LEFT JOIN bill_detail b ON b.matter_code = a.matter_code AND b.bill_date BETWEEN '$start_date_ymd' AND '$end_date_ymd' 
                                AND b.branch_code LIKE '$branch_code' 
                                AND (COALESCE(b.cancel_ind, 'N') = 'N' OR b.cancel_ind = '')
                                WHERE a.client_code LIKE '$client_code'
                                AND a.court_code LIKE '$court_code' 
                                AND a.initial_code LIKE '$initial_code'
                                AND a.client_code != a.matter_code 
                                AND c.type_code = '001'
                                GROUP BY a.court_code, a.matter_code, CONCAT(a.matter_desc1, ' : ', a.matter_desc2), c.code_desc 
                                HAVING ".$having_clause."
                                ORDER BY a.court_code, a.matter_code" ;
                    $bill_qry  = $this->db->query($bill_sql)->getResultArray();
                    $bill_cnt  = count($bill_qry);
    
                    if(empty($bill_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }
    
                    $params = [
                        "report_desc" => $report_desc,
                        "branch_name" => $branch_name,
                        "bill_cnt" => $bill_cnt,
                        "client_name" => $client_name,
                        "client_code" => $client_code,
                        "report_for_desc" => $report_for_desc,
                        "period_desc" => $period_desc,
                        "requested_url" => $requested_url,
                    ];
    
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/MIS/Billing/billing_count_summary", compact("bill_qry", "params", 'report_type'));
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render(); 
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/MIS/Billing/billing_count_summary", compact("bill_qry", "params"));
                } else if($output_type == 'Excel') {

                    $bill_sql = "select a.court_code,a.initial_code,a.matter_code, if(a.matter_desc1 != ' ', concat(a.matter_desc1,' : ',a.matter_desc2), a.matter_desc2) matter_desc, c.code_desc court_name, 
                            count(b.bill_no) billnos, 
                            max(b.bill_date) billdate,
                            sum(ifnull(b.bill_amount_inpocket,0)+ifnull(b.bill_amount_outpocket,0)+ifnull(b.bill_amount_counsel,0)+ifnull(b.service_tax_amount,0)) billamt, 
                            sum(ifnull(b.realise_amount_inpocket,0)+ifnull(b.realise_amount_outpocket,0)+ifnull(b.realise_amount_counsel,0)+ifnull(b.realise_amount_service_tax,0)) realamt,
                            sum(ifnull(b.deficit_amount_inpocket,0)+ifnull(b.deficit_amount_outpocket,0)+ifnull(b.deficit_amount_counsel,0)+ifnull(b.deficit_amount_service_tax,0)) defamt 
                            from code_master c, fileinfo_header a left outer join bill_detail b on b.matter_code = a.matter_code and b.bill_date between '$start_date_ymd' and '$end_date_ymd' and b.branch_code like '$branch_code' and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')
                            where a.client_code like '$client_code'
                            and a.court_code like '$court_code'
                            and a.initial_code like '$initial_code'
                            and a.client_code != a.matter_code 
                            and a.court_code = c.code_code and c.type_code = '001' 
                            group by a.court_code, a.matter_code, concat(a.matter_desc1,' : ',a.matter_desc2), c.code_desc 
                            having ".$having_clause."
                            order by a.court_code, a.matter_code" ; 

                    $bill_qry  = $this->db->query($bill_sql)->getResultArray();
                    $bill_cnt  = count($bill_qry);

                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;

                    $fileName = $headings = '';
                    $fileName = 'MIS-Billing-Count-Summary-'.date('d-m-Y').'.xlsx';  
                    $headings = ['Matter', 'Matter Description', 'Initial', 'No(s)', 'Billed', 'Realised', 'Deficit', 'Last Bill'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;

                    $rowcnt     = 1 ;
                    $report_row = isset($bill_qry[$rowcnt-1]) ? $bill_qry[$rowcnt-1] : '' ;  
                    $report_cnt = $bill_cnt ;
                    while ($rowcnt <= $report_cnt) {
                        $sheet->setCellValue('A' . $rows, $report_row['matter_code']);
                        $sheet->setCellValue('B' . $rows, $report_row['matter_desc']);
                        $sheet->setCellValue('C' . $rows, $report_row['initial_code']);
                        $sheet->setCellValue('D' . $rows, ($report_row['billnos'] > 0) ? $report_row['billnos'] : '');
                        $sheet->setCellValue('E' . $rows, ($report_row['billamt'] > 0) ? $report_row['billamt'] : '');
                        $sheet->setCellValue('F' . $rows, ($report_row['realamt'] > 0) ? $report_row['realamt'] : '');
                        $sheet->setCellValue('G' . $rows, ($report_row['defamt'] > 0) ? $report_row['defamt'] : '');
                        $sheet->setCellValue('H' . $rows, date_conv($report_row['billdate']));

                        $report_row = ($rowcnt < $report_cnt) ? $bill_qry[$rowcnt] : $report_row;  
                        $rowcnt = $rowcnt + 1 ;

                        $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $rows++;
                    }  
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                }
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4072', 'court_help_id' => '4221', 'initial_help_id' => '4191'] ;
            
            return view("pages/MIS/Billing/billing_count_summary", compact("data", "displayId"));
        }
    }

    public function bill_realisation_count_summary() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';
        $user_id =session()->userId ;

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $report_desc   = 'BILL REALISATION SUMMARY' ; 
                $branch_code   = $_REQUEST['branch_code'] ;
                $start_date    = $_REQUEST['start_date'] ;   if($start_date != '') {$start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01' ; }
                $end_date      = $_REQUEST['end_date'] ;     $end_date_ymd   = date_conv($end_date) ;  
                $client_code   = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name   = $_REQUEST['client_name'] ;   
                $court_code    = $_REQUEST['court_code'] ;   if($court_code  == '') { $court_code  = '%' ; }
                $court_name    = $_REQUEST['court_name'] ; 
                
                if ($start_date == '' ) { $period_desc = 'UPTO '.$end_date ; } else { $period_desc = $start_date.' - '.$end_date ; } 
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $result = $this->db->query("select date_format(now(),'%Y%m%d%h%i%s') timestamp")->getRowArray() ;

                $temp_id = "zz_".$user_id."_".$result['timestamp'] ;
              
                $table0 = $temp_id.'_summary';

                $create_stmt = "create table $table0 (client_code varchar(6),court_code varchar(3),matter_code varchar(6),matter_desc1 varchar(30),matter_desc2 varchar(200),
                            court_name varchar(200),billamt double(15,2),dr_amt double(15,2),tot_count varchar(8))";
                $this->temp_db->query($create_stmt);

                $bill_sql = "insert into $table0(client_code,court_code,matter_code,matter_desc1,matter_desc2,court_name,billamt,dr_amt,tot_count)
                        select a.client_code,a.court_code, a.matter_code, a.matter_desc1,a.matter_desc2, c.code_desc court_name, 
                        sum(ifnull(b.bill_amount_inpocket,0)+ifnull(b.bill_amount_outpocket,0)+ifnull(b.bill_amount_counsel,0)) billamt,0,0 
                        from sinhaco.code_master c, sinhaco.fileinfo_header a, sinhaco.bill_detail b 
                        where a.client_code like '$client_code'
                        and a.court_code like '$court_code' 
                        and a.client_code != a.matter_code 
                        and a.court_code = c.code_code 
                        and c.type_code = '001' 
                        and b.matter_code = a.matter_code 
                        and b.bill_date between '$start_date_ymd' and '$end_date_ymd' 
                        and b.branch_code like '$branch_code' 
                        and (ifnull(b.cancel_ind,'N') = 'N' or b.cancel_ind = '')
                        group by a.court_code, a.matter_code, a.matter_desc1,a.matter_desc2, c.code_desc 
                        order by a.court_code, a.matter_code" ;
                $bill_qry  = $this->temp_db->query($bill_sql);
              
                $dtl_sql = "insert into $table0 (client_code,court_code,matter_code,matter_desc1,matter_desc2,court_name,billamt,dr_amt,tot_count)
                        select b.client_code,c.court_code,b.matter_code,
                        c.matter_desc1,c.matter_desc2, 
                        d.code_desc court_name, 0.00 billamt,
                        sum(b.net_amount) dr_amt, count(*) t_count
                        from sinhaco.ledger_trans_hdr a, sinhaco.ledger_trans_dtl b, sinhaco.fileinfo_header c,sinhaco.code_master d
                        where a.doc_date between '$start_date_ymd' and '$end_date_ymd' 
                        and b.dr_cr_ind = 'D'
                        and ifnull(b.ref_doc_type,'') != 'PJ'
                        and b.client_code like '$client_code'
                        and c.court_code like '$court_code' 
                        and a.serial_no = b.ref_ledger_serial_no
                        and b.matter_code = c.matter_code
                        and c.court_code = d.code_code 
                        and d.type_code = '001' 
                        group by client_code,court_code,b.matter_code,c.matter_desc1,c.matter_desc2,d.code_desc
                        order by b.client_code,c.court_code";
                $dtl_qry  = $this->temp_db->query($dtl_sql);
              
                $sele_stmt = "select client_code,court_code,matter_code,matter_desc1,matter_desc2,court_name,sum(billamt) billamt, sum(dr_amt) dr_amt,sum(tot_count) tot_count
                        from $table0 group by client_code,court_code,matter_code,matter_desc1,matter_desc2,court_name";

                $sele_qry  = $this->temp_db->query($sele_stmt)->getResultArray();
                $sele_cnt  = count($sele_qry);

                if(empty($sele_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $showActionBtns = true;
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $rows = 1;

                $fileName = $headings = '';
                $fileName = 'MIS-Bill-Realisation-Count-Summary-'.date('d-m-Y').'.xlsx';  
                $headings = ['Matter', 'Matter Description', 'Billed', 'Dr. Amount', 'Voucher Count'];
                $column = 'A'; $rows++;

                foreach ($headings as $heading) {
                    $cell = $column . $rows;
                    $sheet->setCellValue($cell, $heading); // Set the cell value

                    // Apply formatting
                    $style = $sheet->getStyle($cell);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                    ++$column; // Move to the next column
                } $rows++;

                foreach($sele_qry as $report_row) {

                    $sheet->setCellValue('A' . $rows, $report_row['matter_code']);
                    $sheet->setCellValue('B' . $rows, $report_row['matter_desc1'].':'.$report_row['matter_desc2']);
                    $sheet->setCellValue('C' . $rows, ($report_row['billamt'] > 0) ? $report_row['billamt'] : '');
                    $sheet->setCellValue('D' . $rows, ($report_row['dr_amt'] > 0) ? $report_row['dr_amt'] : '');
                    $sheet->setCellValue('E' . $rows, ($report_row['tot_count'] > 0) ? $report_row['tot_count'] : '');

                    $style = $sheet->getStyle('A' . $rows . ':E' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;
                }
                $this->temp_db->query("DROP TABLE $table0");
                $writer = new Xlsx($spreadsheet);
                $writer->save($fileName);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                header('Expires: 0');
                header('Cache-control: must-revalidate');
                header('Pragma: public');
                header('Content-Length:'.filesize($fileName));
                flush();
                readfile($fileName); 
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4072', 'court_help_id' => '4221'] ;
            
            return view("pages/MIS/Billing/bill_realisation_count_summary", compact("data", "displayId"));
        }
    }

    public function realisation_detail_for_client() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';
        $displayId = ['client_help_id' => '4547', 'other_help_id' => '4404'] ;

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {
                $report_desc   = 'RECEIPT REGISTER';

                $branch_code   = $_REQUEST['branch_code'] ;
                $start_date    = $_REQUEST['start_date'] ;    $start_date_ymd = date_conv($start_date);
                $end_date      = $_REQUEST['end_date'] ;      $end_date_ymd   = date_conv($end_date);
                $payee_type    = $_REQUEST['payee_payer_type'] ;
                $payee_code    = $_REQUEST['payee_payer_code'] ;    if(empty($payee_code)) { $payee_code = '%' ; }
                $payee_name    = $_REQUEST['payee_payer_name'] ;    if(empty($payee_name)) { $payee_name = '%' ; }
              
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code'")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $payreg_sql = "select a.serial_no, a.doc_date, a.doc_no, a.doc_type, a.daybook_code, a.payee_payer_code, a.payee_payer_name, a.gross_amount,a.tax_amount,a.net_amount, b.narration, b.gross_amount paid_amount 
                        from ledger_trans_hdr a, ledger_trans_dtl b
                        where a.branch_code = '$branch_code' 
                        and a.doc_date between '$start_date_ymd' and '$end_date_ymd'
                        and a.status_code = 'C'
                        and a.doc_type in ('RV','RO')
                        and a.ref_doc_type != 'CB'
                        and a.payee_payer_type like '$payee_type' 
                        and ifnull(a.payee_payer_code,'%') like '$payee_code'
                        and a.payee_payer_name like '$payee_name' 
                        and a.serial_no = b.ref_ledger_serial_no 
                        and b.dr_cr_ind = 'C'
                        order by a.payee_payer_name";
                $payreg_qry  = $this->db->query($payreg_sql)->getResultArray() ;
                $payreg_cnt  = count($payreg_qry) ;

                if(empty($payreg_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "report_desc" => $report_desc,
                    "branch_name" => $branch_name,
                    "payreg_cnt" => $payreg_cnt,
                    "payee_code" => $payee_code,
                    "payee_name" => $payee_name,
                    "start_date" => $start_date,
                    "end_date" => $end_date,
                    "requested_url" => $requested_url,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/MIS/Billing/realisation_detail_for_client", compact("payreg_qry", "params", 'report_type', 'displayId'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render(); 
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else if($output_type == 'Excel') { 
                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;

                    $fileName = $headings = '';
                    $fileName = 'MIS-Billing-Count-Summary-'.date('d-m-Y').'.xlsx';  
                    $headings = ['Payee', 'Gross', 'TDS', 'Net'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;

                    $tgramt  = 0; 
                    $ttxamt  = 0; 
                    $tntamt  = 0; 
                    $rowcnt     = 1 ;
                    $report_row = isset($payreg_qry[$rowcnt-1]) ? $payreg_qry[$rowcnt-1] : '' ;  
                    $report_cnt = $params['payreg_cnt'] ;
                    while ($rowcnt <= $report_cnt) {
                        $psrlind = 'Y';
                        $pgramt  = $report_row['gross_amount'] ;
                        $ptxamt  = $report_row['tax_amount'] ;
                        $pntamt  = $report_row['net_amount'] ;
                        $pserial = $report_row['serial_no'] ;
                        while($pserial == $report_row['serial_no'] && $rowcnt <= $report_cnt) {
                            $sheet->setCellValue('A' . $rows, strtoupper($report_row['payee_payer_name']));

                            $psrlind = 'N' ;
                            $report_row = ($rowcnt < $report_cnt) ? $payreg_qry[$rowcnt] : $report_row;  
                            $rowcnt = $rowcnt + 1 ;

                            $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        }
                        $sheet->setCellValue('A' . $rows, 'TOTAL');
                        $sheet->setCellValue('B' . $rows, ($pgramt>0) ? number_format($pgramt,2,'.','') : '');
                        $sheet->setCellValue('C' . $rows, ($ptxamt>0) ? number_format($ptxamt,2,'.','') : '');
                        $sheet->setCellValue('D' . $rows, ($pntamt>0) ? number_format($pntamt,2,'.','') : '');

                        $tgramt  = $tgramt + $pgramt ;
                        $ttxamt  = $ttxamt + $ptxamt ;
                        $tntamt  = $tntamt + $pntamt ;

                        $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8efe3');
                        $rows++;
                    }  
                    $sheet->setCellValue('A' . $rows, 'PERIOD TOTAL');
                    $sheet->setCellValue('B' . $rows, ($tgramt>0) ? number_format($tgramt,2,'.','') : '');
                    $sheet->setCellValue('C' . $rows, ($ttxamt>0) ? number_format($ttxamt,2,'.','') : '');
                    $sheet->setCellValue('D' . $rows, ($tntamt>0) ? number_format($tntamt,2,'.','') : '');

                    $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                    $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                    $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;

                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 

                } else return view("pages/MIS/Billing/realisation_detail_for_client", compact("payreg_qry", "params", "displayId"));
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Billing/realisation_detail_for_client", compact("data", "displayId"));
        }
    }

    public function os_bill_printing_period() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $old_matter_desc = 'ALL MATTERS UPTO 30/09/2003' ;
              
                $start_date    = $_REQUEST['start_date'] ;    $start_date_ymd = date_conv($start_date);
                $end_date      = $_REQUEST['end_date'] ;      $end_date_ymd   = date_conv($end_date);
                $branch_code     = $_REQUEST['branch_code'] ;
                $report_seq      = $_REQUEST['report_seq'] ;
                $group_code      = $_REQUEST['client_group_code'] ;   if($group_code  == '') { $group_code  = '%' ; }
                $group_name      = $_REQUEST['client_group_name'] ;   
                $client_code     = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name     = $_REQUEST['client_name'] ; 
                $client_name   = str_replace('_|_','&', $client_name) ;
                $client_name   = str_replace('-|-',"'", $client_name) ;  
                $collectable_ind = $_REQUEST['collectable_ind'] ;   
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];
                $user_id        = session()->userId ;
                $curr_time      = $logdt_qry['current_time'];
                $curr_date      = $logdt_qry['current_dmydate'];
                $curr_day       = substr($curr_date,0,2) ;
                $curr_month     = substr($curr_date,3,2) ; 
                $curr_year      = substr($curr_date,6,4) ;
                $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);

                if($start_date == '' ) {$period_desc = "UPTO ".$end_date ;} else {$period_desc = $start_date.' - '.$end_date ;}

                $osbill_sql = $report_desc = '';
                switch($report_seq) {
                    case 'C' : 
                        $report_desc     = 'LIST OF CLIENT-WISE O/S BILL(S)' ;
                        $myosbilltbl = $temp_id.'_mis_osbill_table';
                        $this->temp_db->query("drop table IF EXISTS $myosbilltbl");
                        $this->temp_db->query("create table $myosbilltbl(branch_code varchar(4), group_code varchar(3), client_code varchar(6), client_name varchar(50), level_ind varchar(1), doc_no varchar(25), doc_date date, matter_code varchar(6), matter_desc varchar(200), tot_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2))") ;
                       
                      
                        $osbill_sql = "insert into $myosbilltbl (branch_code, group_code, client_code, client_name, level_ind, doc_no, doc_date, matter_code, matter_desc, tot_amount, adj_amount, bal_amount) 
                                select a.branch_code, b.client_group_code, upper(a.client_code) client_code, b.client_name, '1', concat(a.fin_year,'/',a.bill_no), a.bill_date, a.matter_code, if(substring(a.matter_code,1,1)='0','$old_matter_desc',if(c.matter_desc1 != '',concat(c.matter_desc1,'/',c.matter_desc2),c.matter_desc2)),
                                (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)),
                                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)),
                                ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)))
                                from sinhaco.client_master b, sinhaco.bill_detail a left outer join sinhaco.fileinfo_header c on c.matter_code = a.matter_code
                                where a.branch_code like '$branch_code'
                                and a.bill_date between '$start_date_ymd' and '$end_date_ymd'
                                and a.collectable_ind = 'C'
                                and a.client_code like '$client_code'
                                and a.client_code = b.client_code
                                and b.client_group_code like '$group_code'
                                and (ifnull(a.bill_amount_inpocket,0)    + ifnull(a.bill_amount_outpocket,0)    + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                union all
                                select a.branch_code, b.client_group_code, upper(a.payee_payer_code) payee_payer_code, a.payee_payer_name, '2', concat(c.doc_type,'/',c.fin_year,'/',c.daybook_code,'/',c.doc_no), c.doc_date, '', 'Unadjusted Advance', ifnull(a.gross_amount,0), ifnull(a.adjusted_amount,0), (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0))
                                from sinhaco.advance_details a, sinhaco.client_master b, sinhaco.ledger_trans_hdr c
                                where a.branch_code like '$branch_code'
                                and a.client_code like '$client_code'
                                and a.client_code =  b.client_code
                                and b.client_group_code like '$group_code' 
                                and a.ref_ledger_serial_no = c.serial_no
                                and c.doc_date <= '$end_date_ymd' 
                                and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0" ;
                        $this->temp_db->query($osbill_sql);

                        $osbill_sql = "select a.* from $myosbilltbl a order by a.client_name, a.level_ind, a.doc_date, a.doc_no"  ;
                        break;
                    case 'G' :
                        $report_desc = 'LIST OF CLIENT GROUP-WISE O/S BILL(S)' ;
                        $myosbilltbl = $temp_id.'_mis_osbill_table';
                        $this->temp_db->query("drop table IF EXISTS $myosbilltbl");
                        $this->temp_db->query("create table $myosbilltbl(branch_code varchar(4), group_code varchar(3), client_code varchar(6), client_name varchar(50), level_ind varchar(1), doc_no varchar(25), doc_date date, matter_code varchar(6), matter_desc varchar(200), tot_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2))") ;
                        
                        
                        $osbill_sql = "insert into $myosbilltbl
                                            (branch_code, group_code, client_code, client_name, level_ind, doc_no, doc_date, matter_code, matter_desc, tot_amount, adj_amount, bal_amount) 
                                        select 
                                            a.branch_code, b.client_group_code, a.client_code, b.client_name, '1', concat(a.fin_year,'/',a.bill_no), a.bill_date, a.matter_code, if(substring(a.matter_code,1,1)='0','$old_matter_desc',if(c.matter_desc1 != '',concat(c.matter_desc1,'/',c.matter_desc2),c.matter_desc2)),
                                            (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)),
                                            (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)),
                                            ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)))
                                            from sinhaco.client_master b, sinhaco.bill_detail a left outer join sinhaco.fileinfo_header c on c.matter_code = a.matter_code
                                        where a.branch_code         like '$branch_code'
                                            and a.bill_date             between '$start_date_ymd' and '$end_date_ymd'
                                            and a.collectable_ind        = 'C'
                                            and a.client_code         like '$client_code'
                                            and a.client_code            = b.client_code
                                            and b.client_group_code   like '$group_code'
                                            and (ifnull(a.bill_amount_inpocket,0)    + ifnull(a.bill_amount_outpocket,0)    + ifnull(a.bill_amount_counsel,0)) -
                                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                        union all
                                        select a.branch_code, b.client_group_code, a.payee_payer_code, a.payee_payer_name, '2', concat(c.doc_type,'/',c.fin_year,'/',c.daybook_code,'/',c.doc_no), c.doc_date, '', 'Unadjusted Advance', ifnull(a.gross_amount,0), ifnull(a.adjusted_amount,0), (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0))
                                            from sinhaco.advance_details a, sinhaco.client_master b, sinhaco.ledger_trans_hdr c
                                        where a.branch_code               like '$branch_code'
                                            and a.client_code               like '$client_code'
                                            and a.client_code                  =  b.client_code
                                            and b.client_group_code         like '$group_code' 
                                            and a.ref_ledger_serial_no         = c.serial_no
                                            and c.doc_date                    <= '$end_date_ymd' 
                                            and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0" ;
                                        
                        $this->temp_db->query($osbill_sql);
                        
                        $osbill_sql = "select a.*, b.code_desc group_name from _temp.$myosbilltbl a, sinhaco.code_master b where a.group_code = b.code_code and b.type_code = '022' order by b.code_desc, a.client_name, a.level_ind, a.doc_date"  ;
                        break;
                }

                
                $osbill_qry = $this->temp_db->query($osbill_sql)->getResultArray();
                $osbill_cnt = count($osbill_qry);

                if(empty($osbill_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
  
                $params = [
                    "report_desc" => $report_desc,
                    "branch_name" => $branch_name,
                    "osbill_cnt" => $osbill_cnt,
                    "client_name" => $client_name,
                    "client_code" => $client_code,
                    "period_desc" => $period_desc,
                    "requested_url" => $requested_url,
                ];
  
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/MIS/Billing/os_bill_printing_period", compact("osbill_qry", "params", 'report_type', 'report_seq'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render(); 
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else if($output_type == 'Excel') { 
                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    if($report_seq == 'C') {
                        $fileName = 'MIS-OS-Bill-Printing-Period-Clientwise-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Doc No', 'Doc Date', 'Description', '', 'Total', 'Settled', 'Balance'];
                        $column = 'A'; $rows++;

                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value

                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->mergeCells('C' . $rows . ':D' . $rows);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;
                        $tbbal_amount = 0 ; 
                        $tabal_amount = 0 ; 
                        $tnbal_amount = 0 ;
                        $rowcnt       = 1 ;
                        $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;  
                        $report_cnt   = $params['osbill_cnt'] ;
                        while ($rowcnt <= $report_cnt) {
                            $cbbal_amount = 0 ; 
                            $cabal_amount = 0 ; 
                            $cnbal_amount = 0 ;
                            $pclientind   = 'Y' ;
                            $pclientcd    = $report_row['client_code'] ;
                            $pclientnm    = $report_row['client_name'] ;
                            while ($pclientcd == $report_row['client_code'] && $rowcnt <= $report_cnt) {
                                $plevel = $report_row['level_ind'];
                                while ($pclientcd == $report_row['client_code'] && $plevel == $report_row['level_ind'] && $rowcnt <= $report_cnt) {
                                    if ($pclientind == 'Y') {
                                        $sheet->setCellValue('A' . $rows, strtoupper($pclientnm));

                                        $pclientind = 'N' ;
                                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                        $style->getFont()->setBold(true);
                                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                        $sheet->mergeCells('A' . $rows . ':G' . $rows);
                                        $rows++;
                                    }
                                    $sheet->setCellValue('A' . $rows, $report_row['doc_no']);
                                    $sheet->setCellValue('B' . $rows, date_conv($report_row['doc_date']));
                                    $sheet->setCellValue('C' . $rows, $report_row['matter_desc']);
                                    $sheet->setCellValue('E' . $rows, ($report_row['tot_amount']>0) ? $report_row['tot_amount'] : '');
                                    $sheet->setCellValue('F' . $rows, ($report_row['adj_amount']>0) ? $report_row['adj_amount'] : '');
                                    $sheet->setCellValue('G' . $rows, ($report_row['bal_amount']>0) ? $report_row['bal_amount'] : '');

                                    if ($plevel=='1') { $cbbal_amount = $cbbal_amount + $report_row['bal_amount'] ; } else { $cabal_amount = $cabal_amount + $report_row['bal_amount'] ; }
                                    $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;   
                                    $rowcnt = $rowcnt + 1 ;

                                    $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                    $rows++;
                                }  
                            }
                            $cnbal_amount = $cbbal_amount - $cabal_amount ;

                            $sheet->setCellValue('A' . $rows, 'CLIENT TOTAL'); 
                            $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                            $sheet->setCellValue('C' . $rows, number_format($cbbal_amount,2,'.','')); 
                            $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                            $sheet->setCellValue('E' . $rows, number_format($cabal_amount,2,'.','')); 
                            $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                            $sheet->setCellValue('G' . $rows, ($cnbal_amount >= 0) ? number_format($cnbal_amount,2,'.','') : '('.number_format(abs($cnbal_amount),2,'.','').')'); 

                            $tbbal_amount = $tbbal_amount + $cbbal_amount ;
                            $tabal_amount = $tabal_amount + $cabal_amount ;

                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                            $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8efe3');
                            $rows++;
                        }
                        $tnbal_amount = $tbbal_amount - $tabal_amount ;

                        $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                        $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                        $sheet->setCellValue('C' . $rows, number_format($tbbal_amount,2,'.','')); 
                        $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                        $sheet->setCellValue('E' . $rows, number_format($tabal_amount,2,'.','')); 
                        $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                        $sheet->setCellValue('G' . $rows, ($tnbal_amount >= 0) ? number_format($tnbal_amount,2,'.','') : '('.number_format(abs($tnbal_amount),2,'.','').')'); 

                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF99');
                        $rows++;
                    } else if($report_seq == 'G') {
                        $fileName = 'MIS-OS-Bill-Printing-Period-Groupwise-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Doc No', 'Doc Date', 'Description', '', 'Total', 'Settled', 'Balance'];
                        $column = 'A'; $rows++;

                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value

                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $sheet->mergeCells('C' . $rows . ':D' . $rows);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;

                        $tbbal_amount = 0 ; 
                        $tabal_amount = 0 ; 
                        $tnbal_amount = 0 ;
                        $rowcnt       = 1 ;
                        $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;  
                        $report_cnt   = $params['osbill_cnt'] ;
                        while ($rowcnt <= $report_cnt) {
                            $gbbal_amount = 0 ; 
                            $gabal_amount = 0 ; 
                            $gnbal_amount = 0 ;
                            $pgroupcd     = $report_row['group_code'] ;
                            $pgroupnm     = $report_row['group_name'] ;
                            while ($pgroupcd == $report_row['group_code'] && $rowcnt <= $report_cnt) {
                                $cbbal_amount = 0 ; 
                                $cabal_amount = 0 ; 
                                $cnbal_amount = 0 ;
                                $pclientind   = 'Y' ;
                                $pclientcd    = $report_row['client_code'] ;
                                $pclientnm    = $report_row['client_name'] ;
                                while ($pgroupcd == $report_row['group_code'] && $pclientcd == $report_row['client_code'] && $rowcnt <= $report_cnt) {
                                    $plevel = $report_row['level_ind'];
                                    while ($pgroupcd == $report_row['group_code'] && $pclientcd == $report_row['client_code'] && $plevel == $report_row['level_ind'] && $rowcnt <= $report_cnt) {
                                        if ($pclientind == 'Y') {
                                            $sheet->setCellValue('A' . $rows, strtoupper($pclientnm));

                                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                            $style->getFont()->setBold(true);
                                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                            $sheet->mergeCells('A' . $rows . ':G' . $rows);
                                            $rows++;
                                        }
                                        $sheet->setCellValue('A' . $rows, $report_row['doc_no']);
                                        $sheet->setCellValue('B' . $rows, date_conv($report_row['doc_date']));
                                        $sheet->setCellValue('C' . $rows, $report_row['matter_desc']);
                                        $sheet->setCellValue('E' . $rows, ($report_row['tot_amount']>0) ? $report_row['tot_amount'] : '');
                                        $sheet->setCellValue('F' . $rows, ($report_row['adj_amount']>0) ? $report_row['adj_amount'] : '');
                                        $sheet->setCellValue('G' . $rows, ($report_row['bal_amount']>0) ? $report_row['bal_amount'] : '');

                                        if ($report_row['level_ind']=='1') { $cbbal_amount = $cbbal_amount + $report_row['bal_amount'] ; } else { $cabal_amount = $cabal_amount + $report_row['bal_amount'] ; }
                                        $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;   
                                        $rowcnt = $rowcnt + 1 ;

                                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                        $rows++;
                                    }  
                                }
                                $cnbal_amount = $cbbal_amount - $cabal_amount ;

                                $sheet->setCellValue('A' . $rows, 'CLIENT TOTAL'); 
                                $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                                $sheet->setCellValue('C' . $rows, number_format($cbbal_amount,2,'.','')); 
                                $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                                $sheet->setCellValue('E' . $rows, number_format($cabal_amount,2,'.','')); 
                                $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                                $sheet->setCellValue('G' . $rows, ($cnbal_amount >= 0) ? number_format($cnbal_amount,2,'.','') : '('.number_format(abs($cnbal_amount),2,'.','').')'); 

                                $gbbal_amount = $gbbal_amount + $cbbal_amount ;
                                $gabal_amount = $gabal_amount + $cabal_amount ;

                                $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                                $style->getFont()->setBold(true);
                                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                                $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                                $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                                $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                                $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF99');
                                $rows++;
                            }
                            $gnbal_amount = $gbbal_amount - $gabal_amount ;

                            $sheet->setCellValue('A' . $rows, 'GROUP TOTAL'); 
                            $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                            $sheet->setCellValue('C' . $rows, number_format($gbbal_amount,2,'.','')); 
                            $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                            $sheet->setCellValue('E' . $rows, number_format($gabal_amount,2,'.','')); 
                            $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                            $sheet->setCellValue('G' . $rows, ($gnbal_amount >= 0) ? number_format($gnbal_amount,2,'.','') : '('.number_format(abs($gnbal_amount),2,'.','').')'); 

                            $tbbal_amount = $tbbal_amount + $gbbal_amount ;
                            $tabal_amount = $tabal_amount + $gabal_amount ;

                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                            $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8efe3');
                            $rows++;
                        }
                        $tnbal_amount = $tbbal_amount - $tabal_amount ;

                        $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                        $sheet->setCellValue('B' . $rows, 'O/s Bill'); 
                        $sheet->setCellValue('C' . $rows, number_format($tbbal_amount,2,'.','')); 
                        $sheet->setCellValue('D' . $rows, 'Unadjusted Adv'); 
                        $sheet->setCellValue('E' . $rows, number_format($tabal_amount,2,'.','')); 
                        $sheet->setCellValue('F' . $rows, 'Net O/s'); 
                        $sheet->setCellValue('G' . $rows, ($tnbal_amount >= 0) ? number_format($tnbal_amount,2,'.','') : '('.number_format(abs($tnbal_amount),2,'.','').')'); 

                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                        $rows++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                } else return view("pages/MIS/Billing/os_bill_printing_period", compact("osbill_qry", "params", 'report_seq'));
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4533', 'group_help_id' => '4071'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Billing/os_bill_printing_period", compact("data", "displayId"));
        }
    }

    public function os_bill_summary_old() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $ason_date       = $_REQUEST['ason_date'] ;    $ason_date_ymd = date_conv($ason_date);
                $branch_code     = $_REQUEST['branch_code'] ;
                $report_seq      = $_REQUEST['report_seq'] ;
                $group_code      = $_REQUEST['client_group_code'] ;   if($group_code  == '') { $group_code  = '%' ; }
                $group_name      = $_REQUEST['client_group_name'] ;   
                $client_code     = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name     = $_REQUEST['client_name'] ;   
                $collectable_ind = $_REQUEST['collectable_ind'] ;   
                $os_order        = $_REQUEST['os_order'] ;   
              
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getResultArray()[0];
                $user_id        = session()->userId ;
                $curr_time      = $logdt_qry['current_time'];
                $curr_date      = $logdt_qry['current_dmydate'];
                $curr_day       = substr($curr_date,0,2) ;
                $curr_month     = substr($curr_date,3,2) ; 
                $curr_year      = substr($curr_date,6,4) ;
                $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);

                $osbill_sql = $report_desc = '';
                switch($report_seq) {
                    case 'C' : 

                        if($os_order=='D') { $report_desc = 'LIST OF CLIENT-WISE O/S BILL(S) [DESCENDING]'  ; $order_by_clause = 'a.net_amount desc' ; } 
                        if($os_order=='A') { $report_desc = 'LIST OF CLIENT-WISE O/S BILL(S) [ASSCENDING]'  ; $order_by_clause = 'a.net_amount'      ; }
                        if($os_order=='C') { $report_desc = 'LIST OF CLIENT-WISE O/S BILL(S) [CLIENT-WISE]' ; $order_by_clause = 'a.client_name'     ; }
                    
                        
                        $myosbilltbl = $temp_id.'_mis_osbill_table';
                        $this->temp_db->query("drop table IF EXISTS $myosbilltbl");
                        $this->temp_db->query("create table $myosbilltbl(branch_code varchar(4), client_code varchar(6), client_name varchar(50), tot_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2), uaj_amount double(13,2), net_amount double(13,2))") ;
                    
                        $osbill_sql = "insert into $myosbilltbl(branch_code, client_code, client_name, tot_amount, adj_amount, bal_amount, uaj_amount, net_amount) 
                                select x.branch_code, x.client_code, x.client_name, sum(x.tot_amount), sum(x.adj_amount), sum(x.bal_amount), sum(x.uaj_amount), sum(x.net_amount) 
                                from (select a.branch_code, a.client_code, b.client_name, 
                                sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) tot_amount,
                                sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) adj_amount,
                                sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) bal_amount,
                                0 uaj_amount,
                                sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) net_amount
                                from sinhaco.bill_detail a, sinhaco.client_master b  
                                where a.branch_code like '$branch_code'
                                and a.bill_date <= '$ason_date_ymd'
                                and a.collectable_ind = 'C'
                                and a.client_code like '$client_code'
                                and a.client_code = b.client_code
                                and b.client_group_code like '$group_code'
                                and (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                group by a.branch_code, a.client_code, b.client_name
                                union all
                                select a.branch_code, a.payee_payer_code, a.payee_payer_name, 0 tot_amount, 0 adj_amount, 0 bal_amount, sum(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) uaj_amount, sum(0-(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0))) net_amount
                                from sinhaco.advance_details a, sinhaco.client_master b, sinhaco.ledger_trans_hdr c
                                where a.branch_code like '$branch_code'
                                and a.client_code like '$client_code'
                                and a.client_code =  b.client_code
                                and b.client_group_code like '$group_code' 
                                and a.ref_ledger_serial_no = c.serial_no
                                and c.doc_date <= '$ason_date_ymd' 
                                and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0
                                group by a.branch_code, a.payee_payer_code, a.payee_payer_name) x 
                                group by x.branch_code,x.client_code,x.client_name " ;

                    $this->temp_db->query($osbill_sql);

                    $osbill_sql = "select a.branch_code,a.client_code,a.client_name,a.tot_amount,a.adj_amount,a.bal_amount,a.uaj_amount,a.net_amount from $myosbilltbl a order by ".$order_by_clause  ;
                    break;
                    case 'G' : 
                        if($os_order=='D') { $report_desc = 'LIST OF CLIENT GROUP-WISE O/S BILL(S) [DESCENDING]'  ; $order_by_clause = 'a.group_name, a.net_amount desc' ; } 
                        if($os_order=='A') { $report_desc = 'LIST OF CLIENT GROUP-WISE O/S BILL(S) [ASSCENDING]'  ; $order_by_clause = 'a.group_name, a.net_amount'      ; }
                        if($os_order=='C') { $report_desc = 'LIST OF CLIENT GROUP-WISE O/S BILL(S) [CLIENT-WISE]' ; $order_by_clause = 'a.group_name, a.client_name'     ; }
                    
                        $myosbilltbl = $temp_id.'_mis_osbill_table';
                        $this->temp_db->query("drop table IF EXISTS $myosbilltbl");
                        $this->temp_db->query("create table $myosbilltbl(group_code varchar(3), group_name varchar(50), client_code varchar(6), client_name varchar(50), tot_amount double(13,2), adj_amount double(13,2), bal_amount double(13,2), uaj_amount double(13,2), net_amount double(13,2))") ;

                        $osbill_sql = "insert into $myosbilltbl(group_code, group_name, client_code, client_name, tot_amount, adj_amount, bal_amount, uaj_amount, net_amount) 
                                select x.client_group_code, y.code_desc, x.client_code, x.client_name, sum(x.tot_amount), sum(x.adj_amount), sum(x.bal_amount), sum(x.uaj_amount), sum(x.net_amount) 
                                from ( select b.client_group_code, a.client_code, b.client_name, sum(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) tot_amount,
                                sum(ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) adj_amount,
                                sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) bal_amount,
                                0 uaj_amount,
                                sum((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0))) net_amount
                                from sinhaco.bill_detail a, sinhaco.client_master b  
                                where a.bill_date             <= '$ason_date_ymd'
                                and a.collectable_ind        = 'C'
                                and a.client_code         like '$client_code'
                                and a.client_code            = b.client_code
                                and b.client_group_code   like '$group_code'
                                and (ifnull(a.bill_amount_inpocket,0)     + ifnull(a.bill_amount_outpocket,0)    + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) -
                                ((ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0)) +
                                (ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) +
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) > 0
                                and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                                group by b.client_group_code, a.client_code, b.client_name
                                union all
                                select b.client_group_code, a.payee_payer_code, a.payee_payer_name, 0 tot_amount, 0 adj_amount, 0 bal_amount, sum(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) uaj_amount, sum(0-(ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0))) net_amount
                                from sinhaco.advance_details a, sinhaco.client_master b, sinhaco.ledger_trans_hdr c
                                where a.client_code               like '$client_code'
                                and a.client_code                  =  b.client_code
                                and b.client_group_code         like '$group_code' 
                                and a.ref_ledger_serial_no         = c.serial_no
                                and c.doc_date                    <= '$ason_date_ymd' 
                                and (ifnull(a.gross_amount,0) - ifnull(a.adjusted_amount,0)) > 0
                                group by b.client_group_code, a.payee_payer_code, a.payee_payer_name
                                ) x, sinhaco.code_master y
                                where x.client_group_code = y.code_code 
                                and y.type_code         = '022'	 
                                group by x.client_group_code,y.code_desc,x.client_code,x.client_name " ; 
                                            
                    $this->temp_db->query($osbill_sql);

                    $osbill_sql = "select a.group_code,a.group_name,a.client_code,a.client_name, a.tot_amount, a.adj_amount, a.bal_amount, a.uaj_amount, a.net_amount from $myosbilltbl a order by ".$order_by_clause  ;
                    break;
                }
                
                $osbill_qry = $this->temp_db->query($osbill_sql)->getResultArray();
                $osbill_cnt = count($osbill_qry);

                if(empty($osbill_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "report_desc" => $report_desc,
                    "branch_name" => $branch_name,
                    "osbill_cnt" => $osbill_cnt,
                    "client_name" => $client_name,
                    "client_code" => $client_code,
                    "ason_date" => $ason_date,
                    "requested_url" => $requested_url,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/MIS/Billing/os_bill_summary_old", compact("osbill_qry", "params", 'report_type', 'report_seq'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render(); 
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else if($output_type == 'Excel') { 
                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    if($report_seq == 'C') {
                        $fileName = 'MIS-OS-Bill-Summary-Old-Clientwise-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Client Name', 'Total', 'Settled', 'Balance', 'Unadjusted', 'Net'];
                        $column = 'A'; $rows++;

                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value

                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;
                        $tbtot_amount = 0 ; 
                        $tbadj_amount = 0 ; 
                        $ttbal_amount = 0 ;
                        $ttuaj_amount = 0 ;
                        $ttnet_amount = 0 ;
                        $tbbal_amount = 0;
                        $tbuaj_amount  = 0;
                        $tbnet_amount  = 0;
                        $rowcnt       = 1 ;
                        $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;
                        $report_cnt   = $params['osbill_cnt'] ;
                        while ($rowcnt <= $report_cnt) {
                            $sheet->setCellValue('A' . $rows, strtoupper($report_row['client_name']));
                            $sheet->setCellValue('B' . $rows, ($report_row['tot_amount'] > 0) ? number_format($report_row['tot_amount'],2,'.','') : '');
                            $sheet->setCellValue('C' . $rows, ($report_row['adj_amount'] > 0) ? number_format($report_row['adj_amount'],2,'.','') : '');
                            $sheet->setCellValue('D' . $rows, ($report_row['bal_amount'] > 0) ? number_format($report_row['bal_amount'],2,'.','') : '');
                            $sheet->setCellValue('E' . $rows, ($report_row['uaj_amount'] > 0) ? number_format($report_row['uaj_amount'],2,'.','') : '');
                            $sheet->setCellValue('F' . $rows, ($report_row['net_amount'] >= 0) ? number_format($report_row['net_amount'],2,'.','') : '('.number_format(abs($report_row['net_amount']),2,'.','').')');
                            
                            $tbtot_amount = $tbtot_amount + $report_row['tot_amount'] ;
                            $tbadj_amount = $tbadj_amount + $report_row['adj_amount'] ;
                            $tbbal_amount = $tbbal_amount + $report_row['bal_amount'] ;
                            $tbuaj_amount = $tbuaj_amount + $report_row['uaj_amount'] ;
                            $tbnet_amount = $tbnet_amount + $report_row['net_amount'] ;
                            $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;   
                            $rowcnt = $rowcnt + 1 ;

                            $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        }
                        $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                        $sheet->setCellValue('B' . $rows, ($tbtot_amount>0) ? number_format($tbtot_amount,2,'.','') : ''); 
                        $sheet->setCellValue('C' . $rows, ($tbadj_amount>0) ? number_format($tbadj_amount,2,'.','') : ''); 
                        $sheet->setCellValue('D' . $rows, ($tbbal_amount>0) ? number_format($tbbal_amount,2,'.','') : ''); 
                        $sheet->setCellValue('E' . $rows, ($tbuaj_amount>0) ? number_format($tbuaj_amount,2,'.','') : ''); 
                        $sheet->setCellValue('F' . $rows, ($tbnet_amount>=0) ? number_format($tbnet_amount,2,'.','') : '('.number_format(abs($tbnet_amount),2,'.','').')'); 

                        $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                        $rows++;
                    } else if($report_seq == 'G') {
                        $fileName = 'MIS-OS-Bill-Summary-Old-Groupwise-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Client Name', 'Total', 'Settled', 'Balance', 'Unadjusted', 'Net'];
                        $column = 'A'; $rows++;

                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value

                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;

                        $tbtot_amount = 0 ; 
                        $tbadj_amount = 0 ; 
                        $tbbal_amount = 0 ;
                        $ttuaj_amount = 0 ;
                        $ttnet_amount = 0 ;
                        $rowcnt       = 1 ;
                        $report_row   = isset($osbill_qry[$rowcnt-1]) ? $osbill_qry[$rowcnt-1] : '' ;
                        $report_cnt   = $params['osbill_cnt'] ;
                        while ($rowcnt <= $report_cnt) {
                            $gbtot_amount = 0 ; 
                            $gbadj_amount = 0 ; 
                            $gbbal_amount = 0 ;
                            $gtuaj_amount = 0 ;
                            $gtnet_amount = 0 ;
                            $pgroupind    = 'Y';
                            $pgroupcd     = $report_row['group_code'] ;
                            $pgroupnm     = $report_row['group_name'] ;
                            while ($pgroupcd == $report_row['group_code'] && $rowcnt <= $report_cnt) {
                                if ($pgroupind == 'Y') {
                                    $sheet->setCellValue('A' . $rows, strtoupper($pgroupnm));

                                    $pgroupind = 'N' ;
                        
                                    $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                                    $style->getFont()->setBold(true);
                                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                    $sheet->mergeCells('A' . $rows . ':F' . $rows);
                                    $rows++;
                                }
                                $sheet->setCellValue('A' . $rows, strtoupper($report_row['client_name']));
                                $sheet->setCellValue('B' . $rows, ($report_row['tot_amount'] > 0) ? number_format($report_row['tot_amount'],2,'.','') : '');
                                $sheet->setCellValue('C' . $rows, ($report_row['adj_amount'] > 0) ? number_format($report_row['adj_amount'],2,'.','') : '');
                                $sheet->setCellValue('D' . $rows, ($report_row['bal_amount'] > 0) ? number_format($report_row['bal_amount'],2,'.','') : '');
                                $sheet->setCellValue('E' . $rows, ($report_row['uaj_amount'] > 0) ? number_format($report_row['uaj_amount'],2,'.','') : '');
                                $sheet->setCellValue('F' . $rows, ($report_row['net_amount'] >= 0) ? number_format($report_row['net_amount'],2,'.','') : '('.number_format(abs($report_row['net_amount']),2,'.','').')' );
                              
                                $gbtot_amount = $gbtot_amount + $report_row['tot_amount'] ;
                                $gbadj_amount = $gbadj_amount + $report_row['adj_amount'] ;
                                $gbbal_amount = $gbbal_amount + $report_row['bal_amount'] ;
                                $gtuaj_amount = $gtuaj_amount + $report_row['uaj_amount'] ;
                                $gtnet_amount = $gtnet_amount + $report_row['net_amount'] ;
                                $report_row = ($rowcnt < $report_cnt) ? $osbill_qry[$rowcnt] : $report_row;  
                                $rowcnt = $rowcnt + 1 ;

                                $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                                $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                                $rows++;
                            }  
                            $sheet->setCellValue('A' . $rows, 'GROUP TOTAL'); 
                            $sheet->setCellValue('B' . $rows, ($gbtot_amount > 0) ? number_format($gbtot_amount,2,'.','') : ''); 
                            $sheet->setCellValue('C' . $rows, ($gbadj_amount > 0) ? number_format($gbadj_amount,2,'.','') : ''); 
                            $sheet->setCellValue('D' . $rows, ($gbbal_amount > 0) ? number_format($gbbal_amount,2,'.','') : ''); 
                            $sheet->setCellValue('E' . $rows, ($gtuaj_amount > 0) ? number_format($gtuaj_amount,2,'.','') : ''); 
                            $sheet->setCellValue('F' . $rows, ($gtnet_amount >= 0) ? number_format($gtnet_amount,2,'.','') : '('.number_format(abs($gtnet_amount),2,'.','').')'); 
                    
                            $tbtot_amount = $tbtot_amount + $gbtot_amount ;
                            $tbadj_amount = $tbadj_amount + $gbadj_amount ;
                            $tbbal_amount = $tbbal_amount + $gbbal_amount ;
                            $ttuaj_amount = $ttuaj_amount + $gtuaj_amount ;
                            $ttnet_amount = $ttnet_amount + $gtnet_amount ;

                            $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                            $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8efe3');
                            $rows++;
                        }
                        $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                        $sheet->setCellValue('B' . $rows, ($tbtot_amount > 0) ? number_format($tbtot_amount,2,'.','') : ''); 
                        $sheet->setCellValue('C' . $rows, ($tbadj_amount > 0) ? number_format($tbadj_amount,2,'.','') : ''); 
                        $sheet->setCellValue('D' . $rows, ($tbbal_amount > 0) ? number_format($tbbal_amount,2,'.','') : ''); 
                        $sheet->setCellValue('E' . $rows, ($ttuaj_amount > 0) ? number_format($ttuaj_amount,2,'.','') : ''); 
                        $sheet->setCellValue('F' . $rows, ($ttnet_amount >= 0) ? number_format($ttnet_amount,2,'.','') : '('.number_format(abs($ttnet_amount),2,'.','').')'); 
                
                        $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                        $rows++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                } else return view("pages/MIS/Billing/os_bill_summary_old", compact("osbill_qry", "params", 'report_seq'));
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4533', 'group_help_id' => '4071'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Billing/os_bill_summary_old", compact("data", "displayId"));
        }
    }

    /************************************ COUNSEL ******************************************/

    public function counsel_memo_credited() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {
                $report_desc   = 'COUNSEL MEMO CREDITED DURING A PERIOD' ;

                //-------
                $start_date    = $_REQUEST['start_date'] ;     $start_date_ymd  = date_conv($start_date,'-') ;  
                $end_date      = $_REQUEST['end_date'] ;       $end_date_ymd    = date_conv($end_date,'-') ;  
                $branch_code   = $_REQUEST['branch_code'] ;
                $counsel_code  = $_REQUEST['counsel_code'] ;   if($counsel_code == '') { $counsel_code = '%' ; }
                $counsel_name  = $_REQUEST['counsel_name'] ;   
                $period_desc   = $start_date.' - '.$end_date ;
                //
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                //-------------------------------------------------------------
                $memo_sql = "select a.counsel_code, b.associate_name counsel_name, date_format(c.doc_date,'%Y-%m') yyyy_mm, sum(ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) counsel_fee
                            from counsel_memo_header a, associate_master b, ledger_trans_hdr c   
                            where a.branch_code          like '$branch_code' 
                            and a.counsel_code         like '$counsel_code'
                            and (ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) > 0 
                            and a.counsel_code            = b.associate_code
                            and a.ref_ledger_serial_no    = c.serial_no
                            and c.doc_date          between '$start_date_ymd' and '$end_date_ymd' 
                            group by a.counsel_code, b.associate_name, date_format(c.doc_date,'%Y%m')  
                            order by 2,3  " ;
                $memo_qry  = $this->db->query($memo_sql)->getResultArray();
                $memo_cnt  = count($memo_qry);

                if(empty($memo_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "report_desc" => $report_desc,
                    "branch_name" => $branch_name,
                    "memo_cnt" => $memo_cnt,
                    "counsel_name" => $counsel_name,
                    "counsel_name" => $counsel_name,
                    "period_desc" => $period_desc,
                    "requested_url" => $requested_url,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/MIS/Counsel/counsel_memo_credited", compact("memo_qry", "params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render(); 
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else if($output_type == 'Excel') {
                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    $fileName = 'MIS-Counsel-Memo-Credited-'.date('d-m-Y').'.xlsx';  
                    $headings = ['Code', 'Name', 'Year/Month', 'Amount'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;
                    $xgamt   = 0 ;
                    $rowcnt  = 1 ;
                    $report_row = isset($memo_qry[$rowcnt-1]) ? $memo_qry[$rowcnt-1] : '' ; 
                    $report_cnt = $params['memo_cnt'] ;
                    while ($rowcnt <= $report_cnt) {
                        $xsrl   = 0 ;
                        $xcamt  = 0 ;
                        $pccode = $report_row['counsel_code'] ;
                        while ($pccode == $report_row['counsel_code'] && $rowcnt <= $report_cnt) {
                            $sheet->setCellValue('A' . $rows, ($xsrl == 0) ? $report_row['counsel_code'] : '');
                            $sheet->setCellValue('B' . $rows, ($xsrl == 0) ? $report_row['counsel_name'] : '');
                            $sheet->setCellValue('C' . $rows, $report_row['yyyy_mm'] );
                            $sheet->setCellValue('D' . $rows, $report_row['counsel_fee']);

                            $xcamt  = $xcamt + $report_row['counsel_fee'] ;
                            $xsrl   = $xsrl + 1 ;
                            $report_row = ($rowcnt < $report_cnt) ? $memo_qry[$rowcnt] : $report_row;   
                            $rowcnt = $rowcnt + 1 ;

                            $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        }  
                        $sheet->setCellValue('A' . $rows, 'COUNSEL TOTAL'); 
                        $sheet->setCellValue('D' . $rows, number_format($xcamt,2,'.','')); 

                        $xgamt  = $xgamt + $xcamt ;

                        $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $sheet->mergeCells('A' . $rows . ':C' . $rows);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8efe3');
                        $rows++;
                    }  
                    $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                    $sheet->setCellValue('D' . $rows, number_format($xgamt,2,'.','')); 

                    $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                    $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                    $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                    $sheet->mergeCells('A' . $rows . ':C' . $rows);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;

                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 

                } else return view("pages/MIS/Counsel/counsel_memo_credited", compact("memo_qry", "params"));
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['counsel_help_id' => '4011'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Counsel/counsel_memo_credited", compact("data", "displayId"));
        }
    }

    public function counsel_memo_os() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $report_desc   = 'O/S COUNSEL MEMO - MONTH-WISE SUMMARY' ;
                $ason_date     = $_REQUEST['ason_date'] ;      $ason_date_ymd    = date_conv($ason_date,'-') ;  
                $branch_code   = $_REQUEST['branch_code'] ;
                $counsel_code  = $_REQUEST['counsel_code'] ;   if($counsel_code == '') { $counsel_code = '%' ; }
                $counsel_name  = $_REQUEST['counsel_name'] ;   
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $memo_sql = "select a.counsel_code, b.associate_name counsel_name, date_format(a.entry_date,'%Y-%m') yyyy_mm, sum(ifnull(a.counsel_fee,0)+ifnull(a.clerk_fee,0)) counsel_fee
                            from counsel_memo_header a, associate_master b   
                            where a.branch_code like '$branch_code' 
                            and a.counsel_code like '$counsel_code'
                            and a.entry_date <= '$ason_date_ymd' 
                            and (ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) = 0 
                            and a.counsel_code = b.associate_code
                            group by a.counsel_code, b.associate_name, date_format(a.entry_date,'%Y%m')  
                            order by 2,3  " ;
                $memo_qry  = $this->db->query($memo_sql)->getResultArray();
                $memo_cnt  = count($memo_qry);

                if(empty($memo_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "report_desc" => $report_desc,
                    "branch_name" => $branch_name,
                    "memo_cnt" => $memo_cnt,
                    "counsel_name" => $counsel_name,
                    "counsel_code" => $counsel_code,
                    "ason_date" => $ason_date,
                    "requested_url" => $requested_url,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/MIS/Counsel/counsel_memo_os", compact("memo_qry", "params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render(); 
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else if($output_type == 'Excel') {
                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    $fileName = 'MIS-Counsel-Memo-Credited-'.date('d-m-Y').'.xlsx';  
                    $headings = ['Code', 'Name', 'Year/Month', 'Amount'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;
                    $xgamt   = 0 ;
                    $rowcnt  = 1 ;
                    $report_row = isset($memo_qry[$rowcnt-1]) ? $memo_qry[$rowcnt-1] : '' ; 
                    $report_cnt = $params['memo_cnt'] ;
                    while ($rowcnt <= $report_cnt) {
                        $xsrl   = 0 ;
                        $xcamt  = 0 ;
                        $pccode = $report_row['counsel_code'] ;
                        while ($pccode == $report_row['counsel_code'] && $rowcnt <= $report_cnt) {
                            $sheet->setCellValue('A' . $rows, ($xsrl == 0) ? $report_row['counsel_code'] : '');
                            $sheet->setCellValue('B' . $rows, ($xsrl == 0) ? $report_row['counsel_name'] : '');
                            $sheet->setCellValue('C' . $rows, $report_row['yyyy_mm'] );
                            $sheet->setCellValue('D' . $rows, $report_row['counsel_fee']);

                            $xcamt  = $xcamt + $report_row['counsel_fee'] ;
                            $xsrl   = $xsrl + 1 ;
                            $report_row = ($rowcnt < $report_cnt) ? $memo_qry[$rowcnt] : $report_row;   
                            $rowcnt = $rowcnt + 1 ;

                            $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        }  
                        $sheet->setCellValue('A' . $rows, 'COUNSEL TOTAL'); 
                        $sheet->setCellValue('D' . $rows, number_format($xcamt,2,'.','')); 

                        $xgamt  = $xgamt + $xcamt ;

                        $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                        $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                        $sheet->mergeCells('A' . $rows . ':C' . $rows);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8efe3');
                        $rows++;
                    }  
                    $sheet->setCellValue('A' . $rows, 'GRAND TOTAL'); 
                    $sheet->setCellValue('D' . $rows, number_format($xgamt,2,'.','')); 

                    $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM); 
                    $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                    $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
                    $sheet->mergeCells('A' . $rows . ':C' . $rows);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;

                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 

                } else return view("pages/MIS/Counsel/counsel_memo_os", compact("memo_qry", "params"));
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['counsel_help_id' => '4011'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Counsel/counsel_memo_os", compact("data", "displayId"));
        }
    }

    /************************************ MATTER ******************************************/

    public function matter_status_latest() {
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {
                $report_desc   = 'LATEST STATUS OF MATTERS [CLIENT-WISE]' ;
                $start_date    = $_REQUEST['start_date'] ;     if($start_date != '') { $start_date_ymd  = date_conv($start_date,'-') ; } else { $start_date_ymd = '0000-00-00' ; } 
                $end_date      = $_REQUEST['end_date'] ;       $end_date_ymd    = date_conv($end_date,'-') ;  
                $branch_code   = $_REQUEST['branch_code'] ;
                $client_code   = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name   = $_REQUEST['client_name'] ;
                $client_name   = str_replace('_|_','&', $client_name) ;
                $client_name   = str_replace('-|-',"'", $client_name) ;   
                $matter_code   = $_REQUEST['matter_code'] ;  if($matter_code == '') { $matter_code = '%' ; }
                $matter_desc   = $_REQUEST['matter_desc'] ;   
                $court_code    = $_REQUEST['court_code'] ;   if($court_code  == '') { $court_code  = '%' ; }
                $court_name    = $_REQUEST['court_name'] ;
                $report_on     = $_REQUEST['report_on'] ;
                $actv_on       = $_REQUEST['actv_on'] ;
                $r_limit       = $_REQUEST['r_limit']*1 ;   
                if($start_date != '') { $period_desc   = $start_date.' TO '.$end_date ; } else { $period_desc   = 'UPTO '.$end_date ; }   
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
                
                if($output_type == 'Report' || $output_type == 'Pdf') {
                    $case_sql = "select a.*, b.client_name, c.code_desc court_name, concat(a.matter_desc1,' ',a.matter_desc2) matter_desc, 
                            d.activity_date, d.next_date, d.next_fixed_for, d.prev_fixed_for    
                            from client_master b, code_master c, fileinfo_header a
                            left outer join case_header d 
                            on d.matter_code = a.matter_code and a.date_of_filing between '$start_date_ymd' and '$end_date_ymd'
                            and d.activity_date between '$start_date_ymd' and '$end_date_ymd' 
                            and d.serial_no = (select max(serial_no) from case_header where matter_code = d.matter_code)
                            where a.client_code like '$client_code'
                            and a.matter_code like '$matter_code' 
                            and a.court_code like '$court_code' 
                            and a.client_code = b.client_code
                            and a.court_code = c.code_code  and c.type_code = '001'     
                            order by a.client_code,a.matter_code";
                            // echo "<pre>"; print_r($case_sql); die;
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    $case_cnt  = count($case_qry);
    
                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }
    
                    $params = [
                        "report_desc" => $report_desc,
                        "branch_name" => $branch_name,
                        "case_cnt" => $case_cnt,
                        "client_name" => $client_name,
                        "client_code" => $client_code,
                        "matter_code" => $matter_code,
                        "matter_desc" => $matter_desc,
                        "court_code" => $court_code,
                        "court_name" => $court_name,
                        "period_desc" => $period_desc,
                        "requested_url" => $requested_url,
                    ];
    
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/MIS/Matter/matter_status_latest", compact("case_qry", "params", 'report_type'));
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render(); 
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/MIS/Matter/matter_status_latest", compact("case_qry", "params"));

                } else if($output_type == 'Excel') {
                    if($report_on == 'F') {
                        $case_sql = '';
                        if($actv_on == 'Y') {
                            $case_sql = "select a.*, b.client_name, c.code_desc court_name,e.code_desc product_desc, f.matter_type_desc,
                                concat(a.matter_desc1,' ',a.matter_desc2) matter_desc
                                from client_master b, code_master c, fileinfo_header a
                                left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                left outer join matter_type f on f.matter_type_code = a.matter_type_code
                                where ifnull(a.date_of_filing,'') between '$start_date_ymd' and '$end_date_ymd'
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code' 
                                and a.court_code  like '$court_code' 
                                and a.client_code    =  b.client_code
                                and a.court_code     =  c.code_code  
                                and c.type_code      = '001'
                                and a.status_code    =  'A'
                                order by a.client_code,a.matter_code"; 
                                    
                        } if($actv_on == 'I') {
                            $case_sql = "select a.*, b.client_name, c.code_desc court_name,e.code_desc product_desc, f.matter_type_desc,
                                concat(a.matter_desc1,' ',a.matter_desc2) matter_desc
                                from client_master b, code_master c, fileinfo_header a
                                left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                left outer join matter_type f on f.matter_type_code = a.matter_type_code
                                where ifnull(a.date_of_filing,'') between '$start_date_ymd' and '$end_date_ymd'
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code' 
                                and a.court_code  like '$court_code' 
                                and a.client_code    =  b.client_code
                                and a.court_code     =  c.code_code  
                                and c.type_code      = '001'
                                and a.status_code    <>  'A'
                                order by a.client_code,a.matter_code"; 
                                
                        } if($actv_on == 'N') {		
                            $case_sql = "select a.*, b.client_name, c.code_desc court_name,e.code_desc product_desc, f.matter_type_desc, 
                                concat(a.matter_desc1,' ',a.matter_desc2) matter_desc
                                from client_master b, code_master c, fileinfo_header a
                                left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                left outer join matter_type f on f.matter_type_code = a.matter_type_code 
                                where ifnull(a.date_of_filing,'') between '$start_date_ymd' and '$end_date_ymd'
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code' 
                                and a.court_code  like '$court_code' 
                                and a.client_code    =  b.client_code
                                and a.court_code     =  c.code_code  
                                and c.type_code      = '001'
                                order by a.client_code,a.matter_code"; 
                        }
    
                        $case_qry  = $this->db->query($case_sql)->getResultArray();
                        $case_cnt  = count($case_qry);
    
                        if(empty($case_qry)) {
                            session()->setFlashdata('message', 'No Records Found !!');
                            return redirect()->to($this->requested_url());
                        }
        
                        $showActionBtns = true;
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $rows = 1;
                        $fileName = $headings = '';
    
                        $fileName = 'MIS-Matter-Status-Latest-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Client Name', 'Initial', 'Filing', 'Matter', 'Case No', 'Matter Desc', 'Judge', 'Court', 'Reference', 'Product',
                         'Mattr Type', 'Requisition No', 'Amount', 'First Acty Dt', 'Last Date', 'Fix For (Prev)', 'Next Date', 'Fix For (Next)'];
                        $column = 'A'; $rows++;
    
                        for($i=1;$i<$r_limit;$i++) { 
                            array_push($headings, 'Acty Date'.$i, 'Fix For'.$i);
                        }
    
                        array_push($headings, 'Remarks');
    
                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value
    
                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;
    
                        $rowcnt     = 1; 
                        $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; 
                        $report_cnt = $case_cnt ;
    
                        while ($rowcnt <= $report_cnt) {
    
                            $client_name    = strtoupper($report_row['client_name']);
                            $initial_code   = strtoupper($report_row['initial_code']);
                            $matter_code    = strtoupper($report_row['matter_code']);
                            $matter_desc1   = strtoupper($report_row['matter_desc1']);
                            $matter_desc2   = strtoupper($report_row['matter_desc2']);
                            $judge_name     = strtoupper($report_row['judge_name']);
                            $court_name     = strtoupper($report_row['court_name']);
                            $reference_desc = strtoupper($report_row['reference_desc']);
                            $product_desc   = strtoupper($report_row['product_desc']);
                            $matter_type_desc   = strtoupper($report_row['matter_type_desc']);
                            $requisition_no = strtoupper($report_row['requisition_no']);
    
                            if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { 
                                $date_of_filing = date_conv($report_row['date_of_filing'],'-') ; 
                            } else { $date_of_filing = ''; }
    
                            $sele_activity_date_stmt = "select date_format(min(activity_date),'%d-%m-%Y') activity_date from case_header where matter_code = '$matter_code'";
                            $sele_activity_date_qry  = $this->db->query($sele_activity_date_stmt)->getRowArray();
                            $first_activity_date     = $sele_activity_date_qry['activity_date']; 
    
                            $last_activity_date_stmt = "select date_format(max(activity_date),'%d-%m-%Y') activity_date from case_header where matter_code = '$matter_code'";
                            $last_activity_date_qry  = $this->db->query($last_activity_date_stmt)->getRowArray();
                            $last_activity_date     = $last_activity_date_qry['activity_date']; 
    
                            $stake_amount   =  $report_row['stake_amount'];
                            $oth_stmt = "select d.activity_date, d.judge_name, d.next_date, d.next_fixed_for, d.prev_fixed_for, d.remarks from case_header d where d.matter_code like '$matter_code'
                                        order by d.activity_date desc limit $r_limit "; 
                            $oth_qry = $this->db->query($oth_stmt)->getResultArray();
                            $oth_row = count($oth_qry);                        
                            
                            $fix_next_stmt = "select next_fixed_for,prev_fixed_for from case_header  where matter_code like '$matter_code' and client_code like '$client_code'
                                            order by activity_date desc  ";
                            $fix_next_qry   = $this->db->query($fix_next_stmt)->getRowArray();
                            $next_fixed_for = isset($fix_next_qry['next_fixed_for']) ? $fix_next_qry['next_fixed_for'] : ''; 
                            $prev_fixed_for = isset($fix_next_qry['prev_fixed_for']) ? $fix_next_qry['prev_fixed_for'] : ''; 
    
                            $sheet->setCellValue('A' . $rows, strtoupper($report_row['client_name']));
                            $sheet->setCellValue('B' . $rows, strtoupper($report_row['initial_code']));
                            $sheet->setCellValue('C' . $rows, $date_of_filing);
                            $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_code']));
                            $sheet->setCellValue('E' . $rows, strtoupper($report_row['matter_desc1']));
                            $sheet->setCellValue('F' . $rows, strtoupper($report_row['matter_desc2']));
                            $sheet->setCellValue('G' . $rows, strtoupper($report_row['judge_name']));
                            $sheet->setCellValue('H' . $rows, strtoupper($report_row['court_name']));
                            $sheet->setCellValue('I' . $rows, "'".strtoupper($report_row['reference_desc']));
                            $sheet->setCellValue('J' . $rows, strtoupper($report_row['product_desc']));
                            $sheet->setCellValue('K' . $rows, strtoupper($report_row['matter_type_desc']));
                            $sheet->setCellValue('L' . $rows, strtoupper($report_row['requisition_no']));
                            $sheet->setCellValue('M' . $rows, number_format($report_row['stake_amount'], 2, '.', ''));
                            $sheet->setCellValue('N' . $rows, $first_activity_date);
                            
                            $f_row_ind = 'Y';
                            $remarks   = '';
                            $col_no = 14;
    
                            foreach($oth_qry as $reportdtl_row) {
                                $activity_date = date_conv($reportdtl_row['activity_date']);
                                $next_date = date_conv($reportdtl_row['next_date']);
                    
                                if(empty($remarks)) $remarks = strtoupper($reportdtl_row['remarks']);
                                if($f_row_ind == 'Y') {
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $last_activity_date);
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($prev_fixed_for));
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $next_date);
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($next_fixed_for));
                                } else {
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $activity_date);
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $prev_fixed_for);
                                }
                                $f_row_ind = 'N' ;
                             }
                            $sheet->setCellValue(columnFromIndex($col_no) . $rows, $remarks);
    
                            $style = $sheet->getStyle('A' . $rows . ':' . columnFromIndex($col_no) . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
    
                            $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : [];
                            $rowcnt++;
                        }
                        $writer = new Xlsx($spreadsheet);
                        $writer->save($fileName);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                        header('Expires: 0');
                        header('Cache-control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length:'.filesize($fileName));
                        flush();
                        readfile($fileName);
                        
                    } else {
                        $case_sql = "select a.*, f.activity_date, b.client_name, c.code_desc court_name,e.code_desc product_desc, concat(a.matter_desc1,' ',a.matter_desc2) matter_desc
                                    from client_master b, code_master c, fileinfo_header a left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                    left outer join case_header f on f.matter_code = a.matter_code
                                    where ifnull(f.activity_date,'') between '$start_date_ymd' and '$end_date_ymd'
                                        and a.client_code like '$client_code'
                                        and a.matter_code like '$matter_code' 
                                        and a.court_code  like '$court_code' 
                                        and a.client_code    =  b.client_code
                                        and a.court_code     =  c.code_code  
                                        and c.type_code      = '001'
                                    order by a.client_code,a.matter_code";
                        $case_qry = $this->db->query($case_sql)->getresultArray();
                        $case_cnt = count($case_qry);

                        if(empty($case_qry)) {
                            session()->setFlashdata('message', 'No Records Found !!');
                            return redirect()->to($this->requested_url());
                        }

                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $rows = 1; $fileName = $headings = '';
    
                        $fileName = 'MIS-Matter-Status-Latest-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Client Name', 'Initial', 'Filing', 'Matter', 'Case No', 'Matter Desc', 'Judge', 'Court', 'Reference', 'Product',
                            'Requisition No', 'Amount', 'First Acty Dt', 'Last Date', 'Fix For (Prev)', 'Next Date', 'Fix For (Next)'];
                        $column = 'A'; $rows++;
    
                        for($i=1;$i<$r_limit;$i++) { 
                            array_push($headings, 'Acty Date'.$i, 'Fix For'.$i);
                        }
    
                        array_push($headings, 'Remarks');
    
                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value
    
                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;
    
                        $report_row = $case_qry[0]; 
                        $report_cnt = $case_cnt ;
                        $rowcnt     = 1; 
                        while ($rowcnt <= $report_cnt) {
                           $client_name    = strtoupper($report_row['client_name']);
                           $initial_code   = strtoupper($report_row['initial_code']);
                           $matter_code    = strtoupper($report_row['matter_code']);
                           $matter_desc1   = strtoupper($report_row['matter_desc1']);
                           $matter_desc2   = strtoupper($report_row['matter_desc2']);
                           $judge_name     = strtoupper($report_row['judge_name']);
                           $court_name     = strtoupper($report_row['court_name']);
                           $reference_desc = strtoupper($report_row['reference_desc']);
                           $product_desc   = strtoupper($report_row['product_desc']);
                           $requisition_no = strtoupper($report_row['requisition_no']);
                    
                            if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { 
                                $date_of_filing = date_conv($report_row['date_of_filing'],'-') ; 
                            } else { $date_of_filing = '' ; }
                    
                           $sele_activity_date_stmt = "select date_format(min(activity_date),'%d-%m-%Y') activity_date from case_header where matter_code = '$matter_code'";
                           $sele_activity_date_qry  = $this->db->query($sele_activity_date_stmt)->getRowArray();
                           $first_activity_date     = $sele_activity_date_qry['activity_date']; 
                    
                    
                           $last_activity_date_stmt = "select date_format(max(activity_date),'%d-%m-%Y') activity_date from case_header where matter_code = '$matter_code'";
                           $last_activity_date_qry  = $this->db->query($last_activity_date_stmt)->getRowArray();
                           $last_activity_date     = $last_activity_date_qry['activity_date']; 
                    
                    
                           $stake_amount   =  $report_row['stake_amount'];
                           $oth_stmt = "select d.activity_date, d.judge_name, d.next_date, d.next_fixed_for, d.prev_fixed_for, d.remarks from case_header d where d.matter_code like '$matter_code'
                                        order by d.activity_date desc limit $r_limit "; 
                           $oth_qry = $this->db->query($oth_stmt)->getResultArray();
                           $oth_row = count($oth_qry);
                           
                           $fix_next_stmt = "select next_fixed_for,prev_fixed_for from case_header where matter_code like '$matter_code' and client_code like '$client_code'
                                            order by activity_date desc  ";
                           $fix_next_qry   = $this->db->query($fix_next_stmt)->getRowArray();
                           $next_fixed_for = $fix_next_qry['next_fixed_for']; 
                           $prev_fixed_for = $fix_next_qry['prev_fixed_for']; 

                           $sheet->setCellValue('A' . $rows, strtoupper($report_row['client_name']));
                           $sheet->setCellValue('B' . $rows, strtoupper($report_row['initial_code']));
                           $sheet->setCellValue('C' . $rows, $date_of_filing);
                           $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_code']));
                           $sheet->setCellValue('E' . $rows, strtoupper($report_row['matter_desc1']));
                           $sheet->setCellValue('F' . $rows, strtoupper($report_row['matter_desc2']));
                           $sheet->setCellValue('G' . $rows, strtoupper($report_row['judge_name']));
                           $sheet->setCellValue('H' . $rows, strtoupper($report_row['court_name']));
                           $sheet->setCellValue('I' . $rows, "'".strtoupper($report_row['reference_desc']));
                           $sheet->setCellValue('J' . $rows, strtoupper($report_row['product_desc']));
                           $sheet->setCellValue('K' . $rows, strtoupper($report_row['requisition_no']));
                           $sheet->setCellValue('L' . $rows, number_format($report_row['stake_amount'], 2, '.', ''));
                           $sheet->setCellValue('M' . $rows, $first_activity_date);
                           
                           $f_row_ind = 'Y';
                           $remarks   = '';
                           $col_no = 13;

                           foreach($oth_qry as $reportdtl_row) {
                               $activity_date = date_conv($reportdtl_row['activity_date']);
                               $next_date = date_conv($reportdtl_row['next_date']);
                   
                               if(empty($remarks)) $remarks = strtoupper($reportdtl_row['remarks']);
                               if($f_row_ind == 'Y') {
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $last_activity_date);
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($prev_fixed_for));
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $next_date);
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($next_fixed_for));
                               } else {
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $activity_date);
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $prev_fixed_for);
                               }
                               $f_row_ind = 'N' ;
                            }
                           $sheet->setCellValue(columnFromIndex($col_no) . $rows, $remarks);
   
                           $style = $sheet->getStyle('A' . $rows . ':' . columnFromIndex($col_no) . $rows);
                           $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                           $rows++;
   
                           $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : [];
                           $rowcnt++;
                       }

                       $writer = new Xlsx($spreadsheet);
                       $writer->save($fileName);
                       header('Content-Type: application/vnd.ms-excel');
                       header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                       header('Expires: 0');
                       header('Cache-control: must-revalidate');
                       header('Pragma: public');
                       header('Content-Length:'.filesize($fileName));
                       flush();
                       readfile($fileName);
                    }
                }
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4072', 'matter_help_id' => '4220', 'court_help_id' => '4221'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Matter/matter_status_latest", compact("data", "displayId"));
        }
    }
    
    public function matter_history() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $report_desc   = 'CASE HISTORY' ;
                $branch_code   = $_REQUEST['branch_code'] ;
                $ason_date     = $_REQUEST['ason_date'] ;    $ason_date_ymd  = date_conv($ason_date,'-') ;  
                $matter_code   = $_REQUEST['matter_code'] ;  if($matter_code == '') { $matter_code = '%' ; }
                $matter_desc   = $_REQUEST['matter_desc'] ;
                $matter_desc   = str_replace('_|_','&', $matter_desc) ;
                $matter_desc   = str_replace('-|-',"'", $matter_desc) ;
                $client_code   = $_REQUEST['client_code'] ; 
                $desc_ind      = $_REQUEST['desc_ind'] ;
                //
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $client_qry    = $this->db->query("select client_name from client_master where client_code = '$client_code' ")->getRowArray() ;
                $client_name   = $client_qry['client_name'] ;
              
                if($output_type == 'Report' || $output_type == 'Pdf') {

                    $case_sql = "select a.*,b.reference_desc,b.court_code,b.date_of_filing,b.stake_amount,c.client_name,d.code_desc court_name,b.matter_desc1,b.matter_desc2    ,ifnull(a.other_case_desc,'') other_case_desc
                                ,e.ref_bill_serial_no,e.status_code bill_status,if(f.serial_no is not null and f.serial_no != 0,concat(f.fin_year,'/',f.bill_no),'') bill_no
                                from fileinfo_header b, client_master c, code_master d,case_header a
                                left outer join billinfo_header e on e.serial_no = a.ref_billinfo_serial_no 
                                left outer join bill_detail f on f.serial_no = e.ref_bill_serial_no   
                                where a.matter_code like '$matter_code'
                                and a.activity_date <= '$ason_date_ymd' 
                                and a.client_code    = c.client_code
                                and a.matter_code    = b.matter_code
                                and b.court_code     = d.code_code  
                                and d.type_code      = '001' 
                                and a.status_code   != 'X'    
                                order by a.matter_code,a.activity_date " ;
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    $case_cnt  = count($case_qry);
    
                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }
    
                    $params = [
                        "report_desc" => $report_desc,
                        "branch_name" => $branch_name,
                        "case_cnt" => $case_cnt,
                        "client_name" => $client_name,
                        "client_code" => $client_code,
                        "matter_code" => $matter_code,
                        "matter_desc" => $matter_desc,
                        "ason_date" => $ason_date,
                        "desc_ind" => $desc_ind,
                        "requested_url" => $requested_url,
                    ];
    
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/MIS/Matter/matter_history", compact("case_qry", "params", 'report_type'));
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render(); 
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/MIS/Matter/matter_history", compact("case_qry", "params"));

                } else if($output_type == 'Excel') {
                    $case_sql = "select a.*, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, c.client_name, d.code_desc court_name, b.matter_desc1, b.matter_desc2,e.ref_bill_serial_no
                        ,e.status_code bill_status,if(f.serial_no is not null and f.serial_no != 0,concat(f.fin_year,'/',f.bill_no),'') bill_no
                        from fileinfo_header b, client_master c, code_master d
                        ,case_header a
                        left outer join billinfo_header e on e.serial_no = a.ref_billinfo_serial_no 
                        left outer join bill_detail f on f.serial_no = e.ref_bill_serial_no   
                        where a.matter_code like '$matter_code'
                        and a.activity_date <= '$ason_date_ymd' 
                        and a.client_code = c.client_code
                        and a.matter_code = b.matter_code
                        and a.status_code != 'X'
                        and b.court_code = d.code_code  and d.type_code = '001'     
                        order by a.matter_code,a.activity_date " ;
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    $case_cnt  = count($case_qry);

                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }

                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    $fileName = 'MIS-Matter-History-'.date('d-m-Y').'.xlsx';  
                    $headings = ['Serial#', 'Date', 'Client Name', 'Matter', 'Filing', 'Case No', 'Matter Desc', 'Judge', 'Court', 
                    'Reference', 'Previous Date', 'Next Date', 'Fix For (Next)', 'Amount', 'Remarks', 'Status', 'BillNo'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;

                    $rowcnt     = 1; 
                    $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ;  
                    $report_cnt = $case_cnt ;
                    $bill_status = $bill_no = '';
                    while ($rowcnt <= $report_cnt) {

                        if($report_row['bill_status'] == 'B') { 
                            $bill_status = 'BILLED';
                        } else if($report_row['bill_status'] == 'A') { 
                            $bill_status = 'DRAFT';
                        } else { 
                            $bill_status = '.';
                        }

                        if($report_row['bill_status'] == 'B') { 
                            $bill_no = $report_row['bill_no'];
                        } else if($report_row['bill_status'] == 'A') { 
                            $bill_no = $report_row['ref_billinfo_serial_no'];
                        } else { 
                            $bill_no = '';
                        }
                        $sheet->setCellValue('A' . $rows, $report_row['serial_no']);
                        $sheet->setCellValue('B' . $rows, date_conv($report_row['activity_date']));
                        $sheet->setCellValue('C' . $rows, strtoupper($report_row['client_name']));
                        $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_code']));
                        $sheet->setCellValue('E' . $rows, ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') ? date_conv($report_row['date_of_filing']) : '');
                        $sheet->setCellValue('F' . $rows, strtoupper($report_row['matter_desc1']));
                        $sheet->setCellValue('G' . $rows, strtoupper($report_row['matter_desc2']));
                        $sheet->setCellValue('H' . $rows, strtoupper($report_row['judge_name']));
                        $sheet->setCellValue('I' . $rows, strtoupper($report_row['court_name']));
                        $sheet->setCellValue('J' . $rows, strtoupper($report_row['reference_desc']));
                        $sheet->setCellValue('K' . $rows, date_conv($report_row['prev_date']));
                        $sheet->setCellValue('L' . $rows, date_conv($report_row['next_date']));
                        $sheet->setCellValue('M' . $rows, strtoupper($report_row['next_fixed_for']));
                        $sheet->setCellValue('N' . $rows, $report_row['stake_amount']);
                        $sheet->setCellValue('O' . $rows, strtoupper($report_row['remarks']));
                        $sheet->setCellValue('P' . $rows, $bill_status);
                        $sheet->setCellValue('Q' . $rows, $bill_no);

                        $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;

                        $style = $sheet->getStyle('A' . $rows . ':Q' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $rows++;
                    }  
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                }
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['matter_help_id' => '4220'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Matter/matter_history", compact("data", "displayId"));
        }
    }

    public function matters_opened_during_a_period() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $report_desc   = 'MATTER OPENED DURING A PERIOD [ENTRY-DATE WISE]' ;
                $branch_code    = $_REQUEST['branch_code'] ;
                $start_date     = $_REQUEST['start_date'] ;     $start_date_ymd  = date_conv($start_date,'-') ;  
                $end_date       = $_REQUEST['end_date'] ;       $end_date_ymd    = date_conv($end_date,'-') ;  
                $client_code    = $_REQUEST['client_code'] ;    if($client_code == '') { $client_code = '%' ; }
                $client_name    = $_REQUEST['client_name'] ;
                $client_name    = str_replace('_|_','&', $client_name) ; 
                $client_name    = str_replace('-|-',"'", $client_name) ;  
                $court_code     = $_REQUEST['court_code'] ;     if($court_code  == '') { $court_code  = '%' ; }
                $court_name     = $_REQUEST['court_name'] ;   
                $opened_by      = $_REQUEST['opened_by'] ;
                $report_type_form  = $_REQUEST['report_type'] ;
                $report_seqn  = $_REQUEST['report_seqn'] ;
                $case_type      = $_REQUEST['case_type'] ;      
                $case_type_desc = get_code_desc('006',$case_type) ; 
                $period_desc    = $start_date.' TO '.$end_date ;
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
                $case_sql = $report_desc = $count_case_cnt = '';
                if($output_type == 'Report' || $output_type == 'Pdf') {
                    if($report_type_form == 'D' && $opened_by == 'E') {

                        $report_desc   = 'MATTER OPENED DURING A PERIOD [ENTRY-DATE WISE]' ;

                        $case_sql = "select a.*,b.client_name,c.code_desc court_name,a.matter_desc1,a.matter_desc2,d.code_desc appearing_for_desc    
                            from fileinfo_header a, client_master b, code_master c, code_master d   
                            where a.prepared_on between '$start_date_ymd' and '$end_date_ymd' 
                            and a.client_code like '$client_code'
                            and a.court_code like '$court_code'
                            and a.case_type_code like '$case_type'
                            and a.client_code = b.client_code
                            and a.court_code = c.code_code  
                            and c.type_code = '001'     
                            and a.appearing_for_code = d.code_code  
                            and d.type_code = '004'     
                            order by b.client_name,a.prepared_on " ;
                    } else if($report_type_form == 'D' && $opened_by == 'F') {
                        $report_desc   = 'MATTER OPENED DURING A PERIOD [FILING-DATE WISE]' ;

                        $case_sql = "select a.*,b.client_name,c.code_desc court_name,a.matter_desc1,a.matter_desc2,d.code_desc appearing_for_desc    
                            from fileinfo_header a, client_master b, code_master c, code_master d   
                            where a.date_of_filing between '$start_date_ymd' and '$end_date_ymd' 
                            and a.client_code like '$client_code'
                            and a.court_code like '$court_code'
                            and a.case_type_code like '$case_type'
                            and a.client_code = b.client_code
                            and a.court_code = c.code_code  
                            and c.type_code = '001'     
                            and a.appearing_for_code = d.code_code  
                            and d.type_code = '004'     
                            order by a.date_of_filing, a.matter_code " ;
                    } else if($report_type_form == 'S') {

                        $report_desc   = 'MATTER OPENED DURING A PERIOD' ;
                        $initial_code  = '%' ;

                        if($report_seqn == 'C') { $group_by_clause = "a.client_code"  ;  }
                        else if($report_seqn == 'I') { $group_by_clause = "a.initial_code" ; }
                        else if($report_seqn == 'T') { $group_by_clause = "a.court_code" ; }
                      
                        $count_case_sql = "select a.*,b.client_name,c.code_desc court_name,concat(a.matter_desc1,' : ',a.matter_desc2) matter_desc,d.code_desc appearing_for_desc    
                                    from fileinfo_header a, client_master b, code_master c, code_master d   
                                    where a.prepared_on between '$start_date_ymd' and '$end_date_ymd' 
                                    and a.client_code like '$client_code'
                                    and a.court_code like '$court_code'
                                    and a.case_type_code like '$case_type'
                                    and a.client_code = b.client_code
                                    and a.court_code = c.code_code  
                                    and c.type_code = '001'     
                                    and a.appearing_for_code = d.code_code  
                                    and d.type_code = '004' 
                                    order by a.prepared_on, a.matter_code " ;  
                        $count_case_qry  = $this->db->query($count_case_sql)->getResultArray();
                        $count_case_cnt  = count($count_case_qry);

                        $case_sql = "select count(*) tot_count, a.*,b.client_name,e.initial_name,c.code_desc court_name,concat(a.matter_desc1,' : ',a.matter_desc2) matter_desc,d.code_desc appearing_for_desc    
                                from fileinfo_header a, client_master b, code_master c, code_master d, initial_master e   
                                where a.prepared_on between '$start_date_ymd' and '$end_date_ymd' 
                                and a.client_code like '$client_code'
                                and a.court_code like '$court_code'
                                and a.initial_code like '$initial_code'
                                and a.case_type_code like '$case_type'
                                and a.client_code = b.client_code
                                and a.court_code = c.code_code 
                                and a.initial_code = e.initial_code 
                                and c.type_code = '001'     
                                and a.appearing_for_code = d.code_code  
                                and d.type_code = '004' 
                                group by ".$group_by_clause." order by tot_count desc " ; 

                    }
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    // echo '<pre>';print_r($case_qry);die;
                    $case_cnt  = count($case_qry);

                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }
    
                    $params = [
                        "report_desc" => $report_desc,
                        "branch_name" => $branch_name,
                        "case_cnt" => $case_cnt,
                        "client_name" => $client_name,
                        "client_code" => $client_code,
                        "court_code" => $court_code,
                        "court_name" => $court_name,
                        "case_type" => $case_type,
                        "case_type_desc" => $case_type_desc,
                        "report_seqn" => $report_seqn,
                        "count_case_cnt" => $count_case_cnt,
                        "period_desc" => $period_desc,
                        "requested_url" => $requested_url,
                    ];
    
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/MIS/Matter/matters_opened_during_a_period", compact("case_qry", "params", 'report_type_form', 'opened_by', 'report_type'));
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render(); 
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/MIS/Matter/matters_opened_during_a_period", compact("case_qry", "params", 'report_type_form', 'opened_by'));
                } else if($output_type == 'Excel') {
                    $report_desc   = 'MATTER OPENED DURING A PERIOD [EXCEL-SHEET]' ;

                    if ($opened_by == 'E') {
                        $case_sql = "select a.*, b.client_name, c.code_desc court_name, a.matter_desc1, a.matter_desc2, d.attention_name,
                                    concat(e.address_line_1,' ',e.address_line_2,' ',e.address_line_3,' ',e.address_line_4,' ',e.city,e.pin_code) address_desc,f.code_desc appearing_for_desc
                                    from client_master b, code_master c, code_master f, client_address e, fileinfo_header a left outer join client_attention d on d.attention_code = a.corrp_attn_code
                                    where a.prepared_on between '$start_date_ymd' and '$end_date_ymd' 
                                    and a.client_code like '$client_code'
                                    and a.court_code like '$court_code'
                                    and a.case_type_code like '$case_type'
                                    and a.client_code = b.client_code
                                    and a.court_code = c.code_code  
                                    and c.type_code = '001' 
                                    and a.appearing_for_code = f.code_code  
                                    and f.type_code = '004'     
                                    and a.corrp_addr_code = e.address_code
                                    order by a.prepared_on, a.matter_code " ;
                    } else {
                        $case_sql = "select a.*, b.client_name, c.code_desc court_name,  a.matter_desc1, a.matter_desc2, d.attention_name,
                                    concat(e.address_line_1,' ',e.address_line_2,' ',e.address_line_3,' ',e.address_line_4,' ',e.city,e.pin_code) address_desc,f.code_desc appearing_for_desc
                                    from client_master b, code_master c, code_master f, client_address e, fileinfo_header a left outer join client_attention d on d.attention_code = a.corrp_attn_code
                                    where a.date_of_filing between '$start_date_ymd' and '$end_date_ymd' 
                                    and a.client_code like '$client_code'
                                    and a.court_code like '$court_code'
                                    and a.case_type_code like '$case_type'
                                    and a.client_code = b.client_code
                                    and a.court_code = c.code_code  
                                    and c.type_code = '001' 
                                    and a.appearing_for_code = f.code_code  
                                    and f.type_code = '004'     
                                    and a.corrp_addr_code = e.address_code
                                    order by a.date_of_filing, a.matter_code " ;
                    }				  
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    $case_cnt  = count($case_qry);

                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }
    
                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    $fileName = 'MIS-Matter-Opened-'.date('d-m-Y').'.xlsx';  
                    if($opened_by == 'E') { 
                        $opened_by = 'Entry Dt';
                        $opened = 'Filing Dt'; 
                    } else if($opened_by == 'F') { 
                        $opened_by = 'Filing Dt'; 
                        $opened = 'Entry Dt'; 
                    }
                    
                    $headings = [$opened_by, 'Client', 'Matter', 'Case No', 'Matter Desc', 'Initial', 'Subject', 'Reference',
                     'Court', 'Judge', 'Appearing For', $opened, 'Next Date', 'Fix For', 'Attention', 'Address'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;

                    $rowcnt     = 1; 
                    $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ;   
                    $report_cnt = $case_cnt ;
                    while ($rowcnt <= $report_cnt) {
                        $sheet->setCellValue('A' . $rows, ($opened_by=='E') ? date_conv($report_row['prepared_on'],'-') : date_conv($report_row['date_of_filing'],'-'));
                        $sheet->setCellValue('B' . $rows, strtoupper(stripslashes($report_row['client_name'])));
                        $sheet->setCellValue('C' . $rows, strtoupper($report_row['matter_code']));
                        $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_desc1']));
                        $sheet->setCellValue('E' . $rows, strtoupper(stripslashes($report_row['matter_desc2'])));
                        $sheet->setCellValue('F' . $rows, strtoupper($report_row['initial_code']));
                        $sheet->setCellValue('G' . $rows, strtoupper(stripslashes($report_row['subject_desc'])));
                        $sheet->setCellValue('H' . $rows, strtoupper($report_row['reference_desc']));
                        $sheet->setCellValue('I' . $rows, strtoupper($report_row['court_name']));
                        $sheet->setCellValue('J' . $rows, strtoupper($report_row['judge_name']));
                        $sheet->setCellValue('K' . $rows, strtoupper($report_row['appearing_for_desc']));
                        $sheet->setCellValue('L' . $rows, ($opened_by=='E') ? date_conv($report_row['date_of_filing'],'-') : date_conv($report_row['prepared_on'],'-'));
                        $sheet->setCellValue('M' . $rows, ($report_row['first_activity_date'] != '' && $report_row['first_activity_date'] != '0000-00-00') ? date_conv($report_row['first_activity_date']) : '');
                        $sheet->setCellValue('N' . $rows, $report_row['first_fixed_for']);
                        $sheet->setCellValue('O' . $rows, $report_row['attention_name']);
                        $sheet->setCellValue('P' . $rows, $report_row['address_desc']);

                        $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row; 
                        $rowcnt = $rowcnt + 1 ;

                        $style = $sheet->getStyle('A' . $rows . ':P' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $rows++;

                    }   
                    
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 

                }
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4072', 'court_help_id' => '4221'] ;
            $casetype_sql = "select * from code_master where type_code = '006' order by code_desc ";
            $data['casetype_qry'] = $this->db->query($casetype_sql)->getResultArray();
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Matter/matters_opened_during_a_period", compact("data", "displayId"));
        }
    }

    public function matter_history_2() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $report_desc   = 'CASE HISTORY' ;
                $branch_code   = $_REQUEST['branch_code'] ;
                $ason_date     = $_REQUEST['ason_date'] ;    $ason_date_ymd  = date_conv($ason_date,'-') ;  
                $start_date    = $_REQUEST['start_date'] ;     $start_date_ymd  = date_conv($start_date,'-') ;  
                $end_date      = $_REQUEST['end_date'] ;       $end_date_ymd    = date_conv($end_date,'-') ;  
                $matter_code   = $_REQUEST['matter_code'] ;  if($matter_code == '') { $matter_code = '%' ; }
                $matter_desc   = $_REQUEST['matter_desc'] ;
                $matter_desc   = str_replace('_|_','&', $matter_desc) ;
                $matter_desc   = str_replace('-|-',"'", $matter_desc) ;
                $client_code   = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name   = $_REQUEST['client_name'] ;
                $desc_ind      = $_REQUEST['desc_ind'] ;
                $initial_code = $_REQUEST['initial_code'] ; //if(empty($initial_code)){ $initial_code = '%' ; }
                $report_type_form = $_REQUEST['report_type'] ;
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $case_sql = '';
                if($output_type == 'Report' || $output_type == 'Pdf') {

                    $client_qry    = $this->db->query("select client_name from client_master where client_code = '$client_code' ")->getRowArray() ;
                    $client_name   = $client_qry['client_name'] ;

                    if ($report_type_form == 'Yes') {

                        $case_sql = "select a.*,b.reference_desc,b.court_code,b.date_of_filing,b.stake_amount,c.client_name,d.code_desc court_name,b.matter_desc1,b.matter_desc2,ifnull(a.other_case_desc,'') other_case_desc
                                from case_header a, fileinfo_header b, client_master c, code_master d   
                                where a.matter_code like '$matter_code'
                                and a.activity_date <= '$ason_date_ymd' 
                                and a.client_code = c.client_code
                                and a.matter_code = b.matter_code
                                and b.court_code = d.code_code  
                                and d.type_code = '001'
                                and a.status_code != 'X'
                                order by a.matter_code,a.activity_date " ;
                    } else if($report_type_form == 'No'){
                        $case_sql = "select a.*,b.reference_desc,b.court_code,b.date_of_filing,b.stake_amount,c.client_name,d.code_desc court_name,b.matter_desc1,b.matter_desc2    
                            ,ifnull(a.other_case_desc,'') other_case_desc,e.ref_bill_serial_no,e.status_code bill_status,if(f.serial_no is not null and f.serial_no != 0,concat(f.fin_year,'/',f.bill_no),'') bill_no
                            from fileinfo_header b, client_master c, code_master d,case_header a
                            left outer join billinfo_header e on e.serial_no = a.ref_billinfo_serial_no 
                            left outer join bill_detail f on f.serial_no = e.ref_bill_serial_no   
                            where a.matter_code like '$matter_code'
                            and a.activity_date <= '$ason_date_ymd' 
                            and a.client_code    = c.client_code
                            and a.matter_code    = b.matter_code
                            and b.court_code     = d.code_code  
                            and d.type_code      = '001' 
                            and a.status_code   != 'X'    
                            order by a.matter_code,a.activity_date " ;
                    } // else if($initial_code != '') {
                    //     $case_sql = "select a.*,b.reference_desc,b.court_code,b.date_of_filing,b.stake_amount,c.client_name,d.code_desc court_name,b.matter_code matt_code,concat(b.matter_desc1,' ',b.matter_desc2) matt_desc,ifnull(a.other_case_desc,'') other_case_desc
                    //         from case_header a, fileinfo_header b, client_master c, code_master d   
                    //         where a.matter_code like '$matter_code'
                    //         and b.initial_code like '$initial_code'
                    //         and a.activity_date between '$start_date_ymd' and '$end_date_ymd' 
                    //         and a.client_code    = c.client_code
                    //         and a.matter_code    = b.matter_code
                    //         and b.court_code     = d.code_code  
                    //         and d.type_code      = '001'
                    //         and a.status_code   != 'X'
                    //         order by a.activity_date,a.matter_code " ;
                    // }
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    $case_cnt  = count($case_qry);
    
                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }
    
                    $params = [
                        "report_desc" => $report_desc,
                        "branch_name" => $branch_name,
                        "case_cnt" => $case_cnt,
                        "client_name" => $client_name,
                        "client_code" => $client_code,
                        "matter_code" => $matter_code,
                        "matter_desc" => $matter_desc,
                        "ason_date" => $ason_date,
                        "desc_ind" => $desc_ind,
                        "report_type_form" => $report_type_form,
                        "requested_url" => $requested_url,
                    ];
    
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/MIS/Matter/matter_history_2", compact("case_qry", "params", 'report_type_form', 'report_type'));
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render(); 
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/MIS/Matter/matter_history_2", compact("case_qry", "params", 'report_type_form'));
                } else if( $output_type == 'Excel' && $initial_code != '') {
                    $case_sql = "select a.*, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, c.client_name, d.code_desc court_name, b.initial_code, b.matter_desc1, b.matter_desc2    
                                    from case_header a, fileinfo_header b, client_master c, code_master d   
                                where a.matter_code          like '$matter_code'
                                    and b.initial_code like '$initial_code'
                                    and a.activity_date between '$start_date_ymd' and '$end_date_ymd' 
                                    and a.client_code             = c.client_code
                                    and a.matter_code             = b.matter_code
                                    and a.status_code            != 'X'
                                    and b.court_code              = d.code_code  and d.type_code = '001'     
                                order by a.activity_date " ; 
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    $case_cnt  = count($case_qry);

                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }

                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    $fileName = 'MIS-Matter-History2-'.date('d-m-Y').'.xlsx';  
                    $headings = ['Recd SL#', 'Date', 'Client Name', 'Matter', 'Filing', 'Case No', 'Sub Case No', 'Matter Desc', 'Judge', 'Court', 
                    'Activity', 'Reference', 'Previous Date', 'Next Date', 'Fix For (Next)', 'Amount', 'Remarks', 'Prepared On', 'Prepared By'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;

                    $rowcnt     = 1; 
                    $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; 
                    $report_cnt = $case_cnt ;
                    while ($rowcnt <= $report_cnt) {

                        $sheet->setCellValue('A' . $rows, $report_row['serial_no']);
                        $sheet->setCellValue('B' . $rows, date_conv($report_row['activity_date']));
                        $sheet->setCellValue('C' . $rows, strtoupper($report_row['client_name']));
                        $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_code']));
                        $sheet->setCellValue('E' . $rows, ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') ? date_conv($report_row['date_of_filing']) : '');
                        $sheet->setCellValue('F' . $rows, strtoupper($report_row['matter_desc1']));
                        $sheet->setCellValue('G' . $rows, strtoupper($report_row['other_case_desc']));
                        $sheet->setCellValue('H' . $rows, strtoupper($report_row['matter_desc2']));
                        $sheet->setCellValue('I' . $rows, strtoupper($report_row['judge_name']));
                        $sheet->setCellValue('J' . $rows, strtoupper($report_row['court_name']));
                        $sheet->setCellValue('K' . $rows, $report_row['header_desc']);
                        $sheet->setCellValue('L' . $rows, strtoupper($report_row['reference_desc']));
                        $sheet->setCellValue('M' . $rows, date_conv($report_row['prev_date']));
                        $sheet->setCellValue('N' . $rows, date_conv($report_row['next_date']));
                        $sheet->setCellValue('O' . $rows, strtoupper($report_row['next_fixed_for']));
                        $sheet->setCellValue('P' . $rows, $report_row['stake_amount']);
                        $sheet->setCellValue('Q' . $rows, $report_row['remarks']);
                        $sheet->setCellValue('R' . $rows, date_conv($report_row['prepared_on']));
                        $sheet->setCellValue('S' . $rows, $report_row['prepared_by']);

                        $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;

                        $style = $sheet->getStyle('A' . $rows . ':S' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $rows++;
                    }  
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                }
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['matter_help_id' => '4220', 'initial_help_id' => '4191'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Matter/matter_history_2", compact("data", "displayId"));
        }
    }

    public function matter_status() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Excel') {
                $report_desc   = 'LATEST STATUS OF MATTERS [CLIENT-WISE]' ;
                $start_date    = $_REQUEST['start_date'] ;     if($start_date != '') { $start_date_ymd  = date_conv($start_date,'-') ; } else { $start_date_ymd = '0000-00-00' ; } 
                $end_date      = $_REQUEST['end_date'] ;       $end_date_ymd    = date_conv($end_date,'-') ;  
                $branch_code   = $_REQUEST['branch_code'] ;
                $client_code   = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name   = $_REQUEST['client_name'] ;
                $client_name   = str_replace('_|_','&', $client_name) ;
                $client_name   = str_replace('-|-',"'", $client_name) ;   
                $matter_code   = $_REQUEST['matter_code'] ;  if($matter_code == '') { $matter_code = '%' ; }
                $matter_desc   = $_REQUEST['matter_desc'] ;   
                $court_code    = $_REQUEST['court_code'] ;   if($court_code  == '') { $court_code  = '%' ; }
                $court_name    = $_REQUEST['court_name'] ;
                $r_limit       = $_REQUEST['r_limit']*1 ;   
                if($start_date != '') { $period_desc   = $start_date.' TO '.$end_date ; } else { $period_desc   = 'UPTO '.$end_date ; }   
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
            
                $case_sql = "select a.*, b.client_name, c.code_desc court_name,e.code_desc product_desc, concat(a.matter_desc1,' ',a.matter_desc2) matter_desc,f.name other_side,g.name represent_c
                            from client_master b, code_master c, fileinfo_header a left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007' 
                                left outer join fileinfo_details f on f.matter_code = a.matter_code and f.record_code = '1'
                                left outer join fileinfo_details g on g.matter_code = a.matter_code and g.record_code = '10'
                            where ifnull(a.date_of_filing,'') between '' and '2013-01-05' 
                            and a.client_code like '$client_code' 
                            and a.matter_code like '$matter_code' 
                            and a.court_code like '$court_code' 
                            and a.client_code = b.client_code and a.court_code = c.code_code and c.type_code = '001' 
                            order by a.client_code,a.matter_code";
                $case_qry = $this->db->query($case_sql)->getresultArray();
                $case_cnt = count($case_qry);

                if(empty($case_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $rows = 1; $fileName = $headings = '';

                $fileName = 'MIS-Matter-Status-'.date('d-m-Y').'.xlsx';  
                $headings = ['Client Name', 'Initial', 'Filing', 'Matter', 'Case No', 'Matter Desc', 'Judge', 'Court', 'Reference', 'Product',
                    'Other Side Name', 'Address', 'Represented By(Client) Name', 'Address', 'Amount', 'First Acty Dt', 'Last Date', 'Fix For (Prev)', 'Next Date', 'Fix For (Next)'];
                $column = 'A'; $rows++;

                for($i=1;$i<$r_limit;$i++) { 
                    array_push($headings, 'Acty Date'.$i, 'Fix For'.$i);
                }

                array_push($headings, 'Remarks');

                foreach ($headings as $heading) {
                    $cell = $column . $rows;
                    $sheet->setCellValue($cell, $heading); // Set the cell value

                    // Apply formatting
                    $style = $sheet->getStyle($cell);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                    ++$column; // Move to the next column
                } $rows++;

                $report_row = $case_qry[0]; 
                $report_cnt = $case_cnt ;
                $rowcnt     = 1; 
                while ($rowcnt <= $report_cnt) {
                   $client_name       = strtoupper($report_row['client_name']);
                   $initial_code      = strtoupper($report_row['initial_code']);
                   $matter_code       = strtoupper($report_row['matter_code']);
                   $matter_desc1      = strtoupper($report_row['matter_desc1']);
                   $matter_desc2      = strtoupper($report_row['matter_desc2']);
                   $judge_name        = strtoupper($report_row['judge_name']);
                   $court_name        = strtoupper($report_row['court_name']);
                   $reference_desc    = strtoupper($report_row['reference_desc']);
                   $product_desc      = strtoupper($report_row['product_desc']);
                   $other_side        = strtoupper($report_row['other_side']);
                   $other_addr        = strtoupper(isset($report_row['other_addr']) ? $report_row['other_addr'] : '');
                   $represent_c       = strtoupper(isset($report_row['represent_c']) ? $report_row['represent_c'] : '');
                   $represent_c_addr  = strtoupper(isset($report_row['represent_c_addr']) ? $report_row['represent_c_addr'] : '');
                   
                    if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { 
                        $date_of_filing = date_conv($report_row['date_of_filing'],'-') ; 
                    } else { $date_of_filing = '' ; }
            
                   $sele_activity_date_stmt = "select date_format(min(activity_date),'%d-%m-%Y') activity_date from case_header where matter_code = '$matter_code'";
                   $sele_activity_date_qry  = $this->db->query($sele_activity_date_stmt)->getRowArray();
                   $first_activity_date     = $sele_activity_date_qry['activity_date']; 
            
                   $stake_amount = $report_row['stake_amount'];
                   $oth_stmt = "select d.activity_date, d.judge_name, d.next_date, d.next_fixed_for, d.prev_fixed_for, d.remarks from case_header d where d.matter_code like '$matter_code'
                                order by d.serial_no desc limit $r_limit ";
                   $oth_qry = $this->db->query($oth_stmt)->getResultArray();
                   $oth_row = count($oth_qry); 

                    $sheet->setCellValue('A' . $rows, strtoupper($report_row['client_name']));
                    $sheet->setCellValue('B' . $rows, strtoupper($report_row['initial_code']));
                    $sheet->setCellValue('C' . $rows, $date_of_filing);
                    $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_code']));
                    $sheet->setCellValue('E' . $rows, strtoupper($report_row['matter_desc1']));
                    $sheet->setCellValue('F' . $rows, strtoupper($report_row['matter_desc2']));
                    $sheet->setCellValue('G' . $rows, strtoupper($report_row['judge_name']));
                    $sheet->setCellValue('H' . $rows, strtoupper($report_row['court_name']));
                    $sheet->setCellValue('I' . $rows, "'".strtoupper($report_row['reference_desc']));
                    $sheet->setCellValue('J' . $rows, strtoupper($report_row['product_desc']));
                    $sheet->setCellValue('k' . $rows, strtoupper($report_row['other_side']));
                    $sheet->setCellValue('L' . $rows, strtoupper($other_addr));
                    $sheet->setCellValue('M' . $rows, "'".strtoupper($report_row['represent_c']));
                    $sheet->setCellValue('N' . $rows, strtoupper($represent_c_addr));
                    $sheet->setCellValue('O' . $rows, number_format($report_row['stake_amount'], 2, '.', ''));
                    $sheet->setCellValue('P' . $rows, $first_activity_date);
                    
                    $f_row_ind = 'Y';
                    $remarks   = '';
                    $col_no = 16;

                    foreach($oth_qry as $reportdtl_row) {
                        $activity_date  = date_conv($reportdtl_row['activity_date']);
                        $prev_fixed_for = strtoupper($reportdtl_row['prev_fixed_for']);
          
                        $next_date      = date_conv($reportdtl_row['next_date']);
                        $next_fixed_for = strtoupper($reportdtl_row['next_fixed_for']);

                        if(empty($remarks)) $remarks = strtoupper($reportdtl_row['remarks']);
                        if($f_row_ind == 'Y') {
                            $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $activity_date);
                            $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($prev_fixed_for));
                            $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $next_date);
                            $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($next_fixed_for));
                        } else {
                            $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $activity_date);
                            $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $prev_fixed_for);
                        }
                        $f_row_ind = 'N' ;
                    }
                    $sheet->setCellValue(columnFromIndex($col_no) . $rows, $remarks);

                    $style = $sheet->getStyle('A' . $rows . ':' . columnFromIndex($col_no) . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;

                    $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : [];
                    $rowcnt++;
                }

                $writer = new Xlsx($spreadsheet);
                $writer->save($fileName);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                header('Expires: 0');
                header('Cache-control: must-revalidate');
                header('Pragma: public');
                header('Content-Length:'.filesize($fileName));
                flush();
                readfile($fileName);
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4072', 'matter_help_id' => '4220', 'court_help_id' => '4221'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Matter/matter_status", compact("data", "displayId"));
        }
    }

    public function matter_information() {
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $report_desc   = 'MATTER INFORMATION' ;
                $branch_code   = $_REQUEST['branch_code'] ;
                $start_date    = $_REQUEST['start_date'] ;     if($start_date != '') { $start_date_ymd  = date_conv($start_date,'-') ; } else { $start_date_ymd = '0000-00-00' ; } 
                $end_date      = $_REQUEST['end_date'] ;       $end_date_ymd    = date_conv($end_date,'-') ;  
                $client_code   = $_REQUEST['client_code'] ;    if($client_code == '') { $client_code = '%' ; }
                $client_name   = $_REQUEST['client_name'] ;
                $client_name   = str_replace('_|_','&', $client_name) ;
                $client_name   = str_replace('-|-',"'", $client_name) ; 
                $matter_code   = $_REQUEST['matter_code'] ;    if($matter_code == '') { $matter_code = '%' ; }
                $matter_desc   = $_REQUEST['matter_desc'] ;   
                $court_code    = $_REQUEST['court_code'] ;     if($court_code  == '') { $court_code  = '%' ; }
                $court_name    = $_REQUEST['court_name'] ;
                $initial_code  = $_REQUEST['initial_code'] ;     if($initial_code  == '') { $initial_code  = '%' ; }
                $initial_name  = $_REQUEST['initial_name'] ;   
                $opened_by     = $_REQUEST['opened_by'] ;
                if($start_date != '') { $period_desc   = $start_date.' TO '.$end_date ; } else { $period_desc   = 'UPTO '.$end_date ; }

                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                $matrinfo_sql = '';
                if($output_type == 'Report' || $output_type == 'Pdf') {

                    if($opened_by == 'E') {
    
                        $matrinfo_sql = "select a.*, b.client_name, c.code_desc court_name, g.initial_name, d.code_desc appearing_for_name, e.code_desc reference_type_name
                                from client_master b, code_master c, code_master d, fileinfo_header a left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'   
                                left outer join initial_master g on g.initial_code = a.initial_code
                                where a.prepared_on between '$start_date_ymd' and '$end_date_ymd' 
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code'
                                and a.court_code like '$court_code'
                                and a.initial_code like '$initial_code'
                                and a.client_code = b.client_code
                                and a.court_code = c.code_code  and c.type_code = '001'     
                                and a.appearing_for_code = d.code_code  and d.type_code = '004' 
                                and a.initial_code = g.initial_code
                                order by a.prepared_on, a.matter_code " ;
                    } else if($opened_by == 'F') {
                        $matrinfo_sql = "select a.*, b.client_name, c.code_desc court_name, g.initial_name, d.code_desc appearing_for_name, e.code_desc reference_type_name
                                from client_master b, code_master c, code_master d, fileinfo_header a left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'   
                                left outer join initial_master g on g.initial_code = a.initial_code
                                where a.date_of_filing between '$start_date_ymd' and '$end_date_ymd' 
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code'
                                and a.court_code like '$court_code'
                                and a.initial_code like '$initial_code'
                                and a.client_code = b.client_code
                                and a.court_code = c.code_code  and c.type_code = '001'     
                                and a.appearing_for_code = d.code_code  and d.type_code = '004' 
                                and a.initial_code = g.initial_code
                                order by a.date_of_filing, a.matter_code " ;
                    }
                    $matrinfo_qry  = $this->db->query($matrinfo_sql)->getResultArray();
                    $matrinfo_cnt  = count($matrinfo_qry);
    
                    if(empty($matrinfo_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }
    
                    $params = [
                        "report_desc" => $report_desc,
                        "branch_name" => $branch_name,
                        "matrinfo_cnt" => $matrinfo_cnt,
                        "period_desc" => $period_desc,
                        "client_name" => $client_name,
                        "client_code" => $client_code,
                        "matter_code" => $matter_code,
                        "matter_desc" => $matter_desc,
                        "court_code" => $court_code,
                        "court_name" => $court_name,
                        "initial_code" => $initial_code,
                        "initial_name" => $initial_name,
                        "requested_url" => $requested_url,
                    ];
    
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/MIS/Matter/matter_information", compact("matrinfo_qry", "params", 'opened_by', 'report_type'));
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render(); 
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/MIS/Matter/matter_information", compact("matrinfo_qry", "params", 'opened_by'));
                } else if($output_type == 'Excel') {

                    if ($opened_by == 'E') {
                        $matrinfo_sql = "select a.*, b.client_name, j.matter_type_desc, c.code_desc court_name, g.initial_name, d.code_desc appearing_for_name, e.code_desc reference_type_name, f.name
                                from client_master b, code_master c, code_master d, fileinfo_header a 
                                left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                left outer join matter_type j on j.matter_type_code = a.matter_type_code
                                left outer join fileinfo_details f on f.matter_code = a.matter_code and f.record_code in ('10','8','4')
                                left outer join initial_master g on g.initial_code = a.initial_code
                                where a.prepared_on between '$start_date_ymd' and '$end_date_ymd' 
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code'
                                and a.court_code like '$court_code'  
                                and a.initial_code like '$initial_code'
                                and a.client_code = b.client_code
                                and a.court_code = c.code_code  and c.type_code = '001'     
                                and a.appearing_for_code = d.code_code  and d.type_code = '004'  
                                and a.initial_code = g.initial_code
                                order by a.prepared_on, a.matter_code " ;
                    } else {
                        $matrinfo_sql = "select a.*, b.client_name, j.matter_type_desc, c.code_desc court_name, g.initial_name, d.code_desc appearing_for_name, e.code_desc reference_type_name, f.name
                                from client_master b, code_master c, code_master d, fileinfo_header a 
                                left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                left outer join matter_type j on j.matter_type_code = a.matter_type_code						     
                                left outer join fileinfo_details f on f.matter_code = a.matter_code and f.record_code in ('10','8','4')
                                left outer join initial_master g on g.initial_code = a.initial_code
                                where a.date_of_filing between '$start_date_ymd' and '$end_date_ymd' 
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code'
                                and a.court_code like '$court_code'
                                and a.initial_code like '$initial_code'
                                and a.client_code = b.client_code
                                and a.court_code = c.code_code  and c.type_code = '001'     
                                and a.appearing_for_code = d.code_code  and d.type_code = '004'  
                                and a.initial_code = g.initial_code
                                order by a.date_of_filing, a.matter_code " ;
                    }				  
                    $matrinfo_qry  = $this->db->query($matrinfo_sql)->getResultArray();
                    $matrinfo_cnt  = count($matrinfo_qry);
    
                    if(empty($matrinfo_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }

                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    if($opened_by == 'E') { 
                        $opened_by = 'Entry Dt';
                        $opened = 'Filing Dt'; 
                    } else if($opened_by == 'F') { 
                        $opened_by = 'Filing Dt'; 
                        $opened = 'Entry Dt'; 
                    }

                    $fileName = 'MIS-Matter-Information-'.date('d-m-Y').'.xlsx';  
                    $headings = [$opened_by, 'Client', 'Court', 'Judge', 'Initial Name', 'Matter', 'Case No', 'Matter Desc', 'Matter Type', 'Notice No', 'Notice Date',
                    'Appearing For', 'Ref Type', 'Ref No', 'Opp/Appp', $opened, 'Amount', 'Represented By'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;

                    $rowcnt     = 1; 
                    $report_row = isset($matrinfo_qry[$rowcnt-1]) ? $matrinfo_qry[$rowcnt-1] : '' ;  
                    $report_cnt = $matrinfo_cnt ;
                    while ($rowcnt <= $report_cnt) {
                        $pmatter_code         = $report_row['matter_code'] ;
                        $pprepared_on         = date_conv($report_row['prepared_on'],'-') ;
                        $pclient_name         = strtoupper($report_row['client_name']) ;
                        $pinitial_code        = strtoupper($report_row['initial_code']) ;
                        $pinitial_name        = strtoupper($report_row['initial_name']) ;
                        $pmatter_desc1        = strtoupper($report_row['matter_desc1']) ;
                        $pmatter_desc2        = strtoupper($report_row['matter_desc2']) ;
                        $matter_type_desc     = strtoupper($report_row['matter_type_desc']) ;
                        $judge_name           = strtoupper($report_row['judge_name']) ;
                        $court_name           = strtoupper($report_row['court_name']) ;
                        $pnotice_no           = $report_row['notice_no'] ;
                        $pnotice_date         = date_conv($report_row['notice_date']) ; 
                        $pappearing_for_name  = strtoupper($report_row['appearing_for_name']) ;
                        $preference_type_name = strtoupper($report_row['reference_type_name']) ;
                        $preference_desc      = strtoupper($report_row['reference_desc']) ;
                        $papply_oppose_ind    = strtoupper($report_row['apply_oppose_ind']) ;
                        $pdate_of_filing      = date_conv($report_row['date_of_filing'],'-') ;
                        $pstake_amount        = $report_row['stake_amount'] ;
                        $pname                = '';
                        $pcname               = '';
                        $poname               = '';

                        while ($pmatter_code == $report_row['matter_code'] && $rowcnt <= $report_cnt)
                        {
                           if($pname == '') { $pname = $report_row['name'] ; } else { $pname .= ', '.$report_row['name'] ; }
                            $report_row = ($rowcnt < $report_cnt) ? $matrinfo_qry[$rowcnt] : $report_row; 
                           $rowcnt = $rowcnt + 1 ;
                        }	
                        $sheet->setCellValue('A' . $rows, ($opened_by=='E') ? $pprepared_on : $pdate_of_filing);
                        $sheet->setCellValue('B' . $rows, $pclient_name);
                        $sheet->setCellValue('C' . $rows, $court_name);
                        $sheet->setCellValue('D' . $rows, $judge_name);
                        $sheet->setCellValue('E' . $rows, $pinitial_name);
                        $sheet->setCellValue('F' . $rows, $pmatter_code);
                        $sheet->setCellValue('G' . $rows, $pmatter_desc1);
                        $sheet->setCellValue('H' . $rows, $pmatter_desc2);
                        $sheet->setCellValue('I' . $rows, $matter_type_desc);
                        $sheet->setCellValue('J' . $rows, $pnotice_no);
                        $sheet->setCellValue('K' . $rows, $pnotice_date);
                        $sheet->setCellValue('L' . $rows, $pappearing_for_name);
                        $sheet->setCellValue('M' . $rows, $preference_type_name);
                        $sheet->setCellValue('N' . $rows, $preference_desc);
                        $sheet->setCellValue('O' . $rows, $papply_oppose_ind);
                        $sheet->setCellValue('P' . $rows, ($opened_by=='E') ? $pdate_of_filing : $pprepared_on);
                        $sheet->setCellValue('Q' . $rows, $pstake_amount);
                        $sheet->setCellValue('R' . $rows, $pname);

                        $style = $sheet->getStyle('A' . $rows . ':R' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $rows++;

                    }   
                    
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                }
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4072', 'matter_help_id' => '4219', 'court_help_id' => '4221', 'initial_help_id' => '4191'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Matter/matter_information", compact("data", "displayId"));
        }
    }

    public function case_detail_quiry() {
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {
                
                $branch_code   = $_REQUEST['branch_code'] ;
                $start_date    = $_REQUEST['start_date'] ;   if($start_date != '') {$start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01' ; }
                $end_date      = $_REQUEST['end_date'] ;     $end_date_ymd   = date_conv($end_date) ;  
                $client_code   = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $matter_code   = $_REQUEST['matter_code'] ;  if($matter_code == '') { $matter_code = '%' ; }
                $matter_desc   = $_REQUEST['matter_desc'] ;   
                $court_code    = $_REQUEST['court_code'] ;   if($court_code  == '') { $court_code  = '%' ; }
                $court_name    = $_REQUEST['court_name'] ;   
                $initial_code   = isset($_REQUEST['initial_code']) ? $_REQUEST['initial_code'] : '' ;   if($initial_code  == '') { $initial_code  = '%' ; }
                $desc_ind      = $_REQUEST['desc_ind'] ;
                $report_seq    = $_REQUEST['report_seq'] ;
                $forward_inp   = $_REQUEST['forwarding_inp'] ;
              
                if ($start_date == '') { $period_desc = 'UPTO '.$end_date ; } else { $period_desc = $start_date.' - '.$end_date ; } 
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
              
                if($client_code == '')
                {
                  $client_name = '';
                }
                else if($client_code == '%')
                {
                  $client_name = 'All';
                }
                else
                {
                  $client_qry    = $this->db->query("select client_name from client_master where client_code = '$client_code' ")->getRowArray() ;
                  $client_name   = $client_qry['client_name'] ;
                }
              
                if($forward_inp == 'Y')
                {
                  $forwarding_ind = 'Yes';
                }
                else if($forward_inp == 'N')
                {
                  $forwarding_ind = 'No';
                }
                else
                {
                  $forward_inp = '%';
                  $forwarding_ind = 'All';
                }
                $report_desc = $case_sql = '';
                if($output_type == 'Report' || $output_type == 'Pdf') {
                    if($report_seq == '1') {
                        $report_desc   = 'CASES APPEARED DURING A PERIOD [ACTIVITY DATE-WISE]' ;
    
                        $case_sql = "select a.activity_date,a.client_code,a.matter_code,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for,a.prev_fixed_for,b.reference_desc
                                ,b.court_code,b.date_of_filing,b.stake_amount,concat(b.matter_desc1,' ',b.matter_desc2) matter_desc,c.client_name,d.code_desc court_name,b.matter_desc1,b.matter_desc2,a.serial_no   
                                from fileinfo_header b,client_master c,code_master d,case_header a 
                                where a.activity_date between '$start_date_ymd'  and '$end_date_ymd'  
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code' 
                                and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                                and a.client_code = c.client_code
                                and a.matter_code = b.matter_code
                                and b.court_code like '$court_code' 
                                and b.court_code = d.code_code 
                                and d.type_code = '001'     
                                order by a.activity_date,d.code_desc,a.client_code,a.matter_code limit 100" ;
                    } else if($report_seq == '2') {
                        $report_desc   = 'CASES APPEARED DURING A PERIOD [MATTER/ACTIVITY DATE-WISE]' ;
    
                        $case_sql = "select a.activity_date,a.client_code,a.matter_code,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for,
                                a.prev_fixed_for, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, d.code_desc, b.matter_desc1, b.matter_desc2    
                                from fileinfo_header b, client_master c, code_master d,case_header a 
                                where a.activity_date between '$start_date_ymd'  and '$end_date_ymd'  
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code' 
                                and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                                and a.client_code = c.client_code
                                and a.matter_code = b.matter_code
                                and b.court_code like '$court_code' 
                                and b.court_code = d.code_code 
                                and d.type_code = '001'     
                                order by a.matter_code,a.activity_date limit 40" ;
                    } 
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    $case_cnt  = count($case_qry);
    
                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }
                
                    $params = [
                        "report_desc" => $report_desc,
                        "branch_name" => $branch_name,
                        "case_cnt" => $case_cnt,
                        "period_desc" => $period_desc,
                        "client_name" => $client_name,
                        "client_code" => $client_code,
                        "matter_code" => $matter_code,
                        "matter_desc" => $matter_desc,
                        "court_code" => $court_code,
                        "court_name" => $court_name,
                        "forwarding_ind" => $forwarding_ind,
                        "desc_ind" => $desc_ind,
                        "requested_url" => $requested_url,
                    ];
                
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/MIS/Matter/case_detail_quiry", compact("case_qry", "params", 'report_seq', 'report_type'));
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render(); 
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/MIS/Matter/case_detail_quiry", compact("case_qry", "params", 'report_seq'));
                } else if($output_type == 'Excel') {
                    if ($report_seq == '1') {
                        $case_sql = "select a.activity_date,a.serial_no,a.client_code,a.matter_code,a.remarks,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for,
                            a.prev_fixed_for, b.reference_desc, b.initial_code, b.court_code, b.date_of_filing, b.stake_amount, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, 
                            c.client_name, d.code_desc court_name, b.matter_desc1, b.matter_desc2   
                            from fileinfo_header b, client_master c, code_master d,case_header a 
                            where a.activity_date between '$start_date_ymd' and '$end_date_ymd'  
                            and a.client_code like '$client_code'
                            and a.matter_code like '$matter_code' 
                            and a.client_code = c.client_code
                            and a.matter_code = b.matter_code
                            and b.initial_code like '$initial_code'
                            and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                            and b.court_code like '$court_code' 
                            and b.court_code = d.code_code 
                            and d.type_code = '001' 
                            group by a.serial_no
                            order by a.activity_date,d.code_desc,a.client_code,a.matter_code " ;
                    } else {
                        $case_sql = "select a.activity_date,a.serial_no,a.client_code,a.matter_code,a.remarks,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for,
                            a.prev_fixed_for, b.initial_code, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, 
                            c.client_name, d.code_desc, b.matter_desc1, b.matter_desc2   
                            from fileinfo_header b, client_master c, code_master d,case_header a 
                            where a.activity_date between '$start_date_ymd' and '$end_date_ymd'  
                            and a.client_code like '$client_code'
                            and a.matter_code like '$matter_code'
                            and b.initial_code like '$initial_code'
                            and a.client_code = c.client_code
                            and a.matter_code = b.matter_code
                            and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                            and b.status_code = 'A' 
                            and b.court_code like '$court_code' 
                            and b.court_code = d.code_code 
                            and d.type_code = '001'  
                            group by a.serial_no   
                            order by a.matter_code,a.activity_date" ;
                    }				  
                  
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    $case_cnt  = count($case_qry);

                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }

                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    $fileName = 'MIS-Case-Detail-query-'.date('d-m-Y').'.xlsx';  
                    $headings = ['Activity Date', 'Record No', 'Client', 'Matter', 'Initial', 'Case No', 'Matter Description', 'Court', 'Judge', 'Reference',
                    'Fix For (Day)', 'Next Date', 'Fix For (Next)', 'Previous Date', 'Fix For (Prev)', 'Filing Date', 'Amount', 'Forwarding', 'Remarks'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;

                    $rowcnt     = 1; 
                    $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ;  
                    $report_cnt = $case_cnt ;
                    while ($rowcnt <= $report_cnt) {
                        $day_fixed_for = get_fixed_for($report_row['matter_code'],$report_row['activity_date'])	;

                        $sheet->setCellValue('A' . $rows, date_conv($report_row['activity_date']));
                        $sheet->setCellValue('B' . $rows, strtoupper($report_row['serial_no']));
                        $sheet->setCellValue('C' . $rows, strtoupper($report_row['client_name']));
                        $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_code']));
                        $sheet->setCellValue('E' . $rows, strtoupper($report_row['initial_code']));
                        $sheet->setCellValue('F' . $rows, strtoupper($report_row['matter_desc1']));
                        $sheet->setCellValue('G' . $rows, strtoupper($report_row['matter_desc2']));
                        $sheet->setCellValue('H' . $rows, strtoupper($report_row['court_name']));
                        $sheet->setCellValue('I' . $rows, strtoupper($report_row['judge_name']));
                        $sheet->setCellValue('J' . $rows, strtoupper($report_row['reference_desc']));
                        $sheet->setCellValue('K' . $rows, strtoupper($day_fixed_for));
                        $sheet->setCellValue('L' . $rows, date_conv($report_row['next_date']));
                        $sheet->setCellValue('M' . $rows, strtoupper($report_row['next_fixed_for']));
                        $sheet->setCellValue('N' . $rows, date_conv($report_row['prev_date']));
                        $sheet->setCellValue('O' . $rows, strtoupper($report_row['prev_fixed_for']));
                        $sheet->setCellValue('P' . $rows, date_conv($report_row['date_of_filing']));
                        $sheet->setCellValue('Q' . $rows, ($report_row['stake_amount'] > 0) ? $report_row['stake_amount'] : '');
                        $sheet->setCellValue('R' . $rows, $forwarding_ind);
                        $sheet->setCellValue('S' . $rows, strtoupper($report_row['remarks']));

                        $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : $report_row; 
                        $rowcnt = $rowcnt + 1 ;

                        $style = $sheet->getStyle('A' . $rows . ':S' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $rows++;
                    }
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                }
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4072', 'matter_help_id' => '4220', 'court_help_id' => '4221'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Matter/case_detail_quiry", compact("data", "displayId"));
        }
    }

    public function client_matter_change_history() { }

    public function download_matter_files() { 
        $upload_ind='Y';
        $ref_emp_serial_no  = isset($_REQUEST['emp_serial'])?$_REQUEST['emp_serial']:NULL;
        $uploaded_by        = isset($_REQUEST['global_userid'])?$_REQUEST['global_userid']:NULL;
        $user_option        = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

        $qry_txt   = "select a.serial_no,a.file_type,a.file_name_system,a.file_name_original,a.description,a.emp_serial_no,a.uploaded_by,b.user_name,a.uploaded_on 
                        from rup_upload_files a, system_user b
                        where a.status_code = 'A' and a.uploaded_by = b.user_id
                        order by a.file_name_original";
        $qry       = $this->db->query($qry_txt)->getResultArray();
        $no_of_row = count($qry);

        if($no_of_row <= 0 ) {
            session()->setFlashdata('message', 'No Records Found !!');
            return redirect()->to($this->requested_url());
        }
        return view("pages/MIS/Matter/download_matter_files", compact("upload_ind", "ref_emp_serial_no", 'uploaded_by', 'user_option', 'qry', 'no_of_row'));
    }

    public function matter_ps_and_other() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {
                $report_desc   = 'LATEST STATUS OF MATTERS [CLIENT-WISE]' ;
                $start_date    = $_REQUEST['start_date'] ;     if($start_date != '') { $start_date_ymd  = date_conv($start_date,'-') ; } else { $start_date_ymd = '0000-00-00' ; } 
                $end_date      = $_REQUEST['end_date'] ;       $end_date_ymd    = date_conv($end_date,'-') ;  
                $branch_code   = $_REQUEST['branch_code'] ;
                $client_code   = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
                $client_name   = $_REQUEST['client_name'] ;
                $client_name   = str_replace('_|_','&', $client_name) ;
                $client_name   = str_replace('-|-',"'", $client_name) ;   
                $matter_code   = $_REQUEST['matter_code'] ;  if($matter_code == '') { $matter_code = '%' ; }
                $matter_desc   = $_REQUEST['matter_desc'] ;   
                $court_code    = $_REQUEST['court_code'] ;   if($court_code  == '') { $court_code  = '%' ; }
                $court_name    = $_REQUEST['court_name'] ;
                $report_on     = $_REQUEST['report_on'] ;
                $actv_on       = $_REQUEST['actv_on'] ;
                $r_limit       = $_REQUEST['r_limit']*1 ;   
                if($start_date != '') { $period_desc   = $start_date.' TO '.$end_date ; } else { $period_desc   = 'UPTO '.$end_date ; }   
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray() ;
                $branch_name   = $branch_qry['branch_name'] ;
                
                if($output_type == 'Report' || $output_type == 'Pdf') {
                    $case_sql = "select a.*, b.client_name, c.code_desc court_name, concat(a.matter_desc1,' ',a.matter_desc2) matter_desc, 
                            d.activity_date, d.next_date, d.next_fixed_for, d.prev_fixed_for    
                            from client_master b, code_master c, fileinfo_header a
                            left outer join case_header d 
                            on d.matter_code = a.matter_code and a.date_of_filing between '$start_date_ymd' and '$end_date_ymd'
                            and d.activity_date between '$start_date_ymd' and '$end_date_ymd' 
                            and d.serial_no = (select max(serial_no) 
                            from case_header 
                            where matter_code = d.matter_code)
                            where a.client_code like '$client_code'
                            and a.matter_code like '$matter_code' 
                            and a.court_code like '$court_code' 
                            and a.client_code = b.client_code
                            and a.court_code = c.code_code  and c.type_code = '001'     
                            order by a.client_code,a.matter_code";
                    $case_qry  = $this->db->query($case_sql)->getResultArray();
                    $case_cnt  = count($case_qry);
    
                    if(empty($case_qry)) {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to($this->requested_url());
                    }
    
                    $params = [
                        "report_desc" => $report_desc,
                        "branch_name" => $branch_name,
                        "case_cnt" => $case_cnt,
                        "client_name" => $client_name,
                        "client_code" => $client_code,
                        "matter_code" => $matter_code,
                        "matter_desc" => $matter_desc,
                        "court_code" => $court_code,
                        "court_name" => $court_name,
                        "period_desc" => $period_desc,
                        "requested_url" => $requested_url,
                    ];
    
                    if ($output_type == 'Pdf') {
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/MIS/Matter/matter_ps_and_other", compact("case_qry", "params", 'report_type'));
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render(); 
                        $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                    } else return view("pages/MIS/Matter/matter_ps_and_other", compact("case_qry", "params"));

                } else if($output_type == 'Excel') {
                    if($report_on == 'F') {
                        $case_sql = '';
                        if($actv_on == 'Y') {
                            $case_sql = "select a.*, b.client_name, c.code_desc court_name,e.code_desc product_desc, f.matter_type_desc,
                                concat(a.matter_desc1,' ',a.matter_desc2) matter_desc
                                from client_master b, code_master c, fileinfo_header a
                                left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                left outer join matter_type f on f.matter_type_code = a.matter_type_code
                                where ifnull(a.date_of_filing,'') between '$start_date_ymd' and '$end_date_ymd'
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code' 
                                and a.court_code  like '$court_code' 
                                and a.client_code    =  b.client_code
                                and a.court_code     =  c.code_code  
                                and c.type_code      = '001'
                                and a.status_code    =  'A'
                                order by a.client_code,a.matter_code"; 
                                    
                        } if($actv_on == 'I') {
                            $case_sql = "select a.*, b.client_name, c.code_desc court_name,e.code_desc product_desc, f.matter_type_desc,
                                concat(a.matter_desc1,' ',a.matter_desc2) matter_desc
                                from client_master b, code_master c, fileinfo_header a
                                left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                left outer join matter_type f on f.matter_type_code = a.matter_type_code
                                where ifnull(a.date_of_filing,'') between '$start_date_ymd' and '$end_date_ymd'
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code' 
                                and a.court_code  like '$court_code' 
                                and a.client_code    =  b.client_code
                                and a.court_code     =  c.code_code  
                                and c.type_code      = '001'
                                and a.status_code    <>  'A'
                                order by a.client_code,a.matter_code"; 
                                
                        } if($actv_on == 'N') {		
                            $case_sql = "select a.*, b.client_name, c.code_desc court_name,e.code_desc product_desc, f.matter_type_desc, 
                                concat(a.matter_desc1,' ',a.matter_desc2) matter_desc
                                from client_master b, code_master c, fileinfo_header a
                                left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                left outer join matter_type f on f.matter_type_code = a.matter_type_code 
                                where ifnull(a.date_of_filing,'') between '$start_date_ymd' and '$end_date_ymd'
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code' 
                                and a.court_code  like '$court_code' 
                                and a.client_code    =  b.client_code
                                and a.court_code     =  c.code_code  
                                and c.type_code      = '001'
                                order by a.client_code,a.matter_code"; 
                        }
    
                        $case_qry  = $this->db->query($case_sql)->getResultArray();
                        $case_cnt  = count($case_qry);
    
                        if(empty($case_qry)) {
                            session()->setFlashdata('message', 'No Records Found !!');
                            return redirect()->to($this->requested_url());
                        }
        
                        $showActionBtns = true;
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $rows = 1;
                        $fileName = $headings = '';
    
                        $fileName = 'MIS-Matter-Status-Latest-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Client Name', 'Initial', 'Filing', 'Matter', 'Case No', 'Matter Desc', 'Judge', 'Court', 'Reference', 'Product',
                         'Mattr Type', 'Requisition No', 'Amount', 'First Acty Dt', 'Last Date', 'Fix For (Prev)', 'Next Date', 'Fix For (Next)'];
                        $column = 'A'; $rows++;
    
                        for($i=1;$i<$r_limit;$i++) { 
                            array_push($headings, 'Acty Date'.$i, 'Fix For'.$i);
                        }
    
                        array_push($headings, 'Remarks');
    
                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value
    
                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;
    
                        $rowcnt     = 1; 
                        $report_row = isset($case_qry[$rowcnt-1]) ? $case_qry[$rowcnt-1] : '' ; 
                        $report_cnt = $case_cnt ;
    
                        while ($rowcnt <= $report_cnt) {
    
                            $client_name    = strtoupper($report_row['client_name']);
                            $initial_code   = strtoupper($report_row['initial_code']);
                            $matter_code    = strtoupper($report_row['matter_code']);
                            $matter_desc1   = strtoupper($report_row['matter_desc1']);
                            $matter_desc2   = strtoupper($report_row['matter_desc2']);
                            $judge_name     = strtoupper($report_row['judge_name']);
                            $court_name     = strtoupper($report_row['court_name']);
                            $reference_desc = strtoupper($report_row['reference_desc']);
                            $product_desc   = strtoupper($report_row['product_desc']);
                            $matter_type_desc   = strtoupper($report_row['matter_type_desc']);
                            $requisition_no = strtoupper($report_row['requisition_no']);
    
                            if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { 
                                $date_of_filing = date_conv($report_row['date_of_filing'],'-') ; 
                            } else { $date_of_filing = ''; }
    
                            $sele_activity_date_stmt = "select date_format(min(activity_date),'%d-%m-%Y') activity_date from case_header where matter_code = '$matter_code'";
                            $sele_activity_date_qry  = $this->db->query($sele_activity_date_stmt)->getRowArray();
                            $first_activity_date     = $sele_activity_date_qry['activity_date']; 
    
                            $last_activity_date_stmt = "select date_format(max(activity_date),'%d-%m-%Y') activity_date from case_header where matter_code = '$matter_code'";
                            $last_activity_date_qry  = $this->db->query($last_activity_date_stmt)->getRowArray();
                            $last_activity_date     = $last_activity_date_qry['activity_date']; 
    
                            $stake_amount   =  $report_row['stake_amount'];
                            $oth_stmt = "select d.activity_date, d.judge_name, d.next_date, d.next_fixed_for, d.prev_fixed_for, d.remarks from case_header d where d.matter_code like '$matter_code'
                                        order by d.activity_date desc limit $r_limit "; 
                            $oth_qry = $this->db->query($oth_stmt)->getResultArray();
                            $oth_row = count($oth_qry);                        
                            
                            $fix_next_stmt = "select next_fixed_for,prev_fixed_for from case_header  where matter_code like '$matter_code' and client_code like '$client_code'
                                            order by activity_date desc  ";
                            $fix_next_qry   = $this->db->query($fix_next_stmt)->getRowArray();
                            $next_fixed_for = isset($fix_next_qry['next_fixed_for']) ? $fix_next_qry['next_fixed_for'] : ''; 
                            $prev_fixed_for = isset($fix_next_qry['prev_fixed_for']) ? $fix_next_qry['prev_fixed_for'] : ''; 
    
                            $sheet->setCellValue('A' . $rows, strtoupper($report_row['client_name']));
                            $sheet->setCellValue('B' . $rows, strtoupper($report_row['initial_code']));
                            $sheet->setCellValue('C' . $rows, $date_of_filing);
                            $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_code']));
                            $sheet->setCellValue('E' . $rows, strtoupper($report_row['matter_desc1']));
                            $sheet->setCellValue('F' . $rows, strtoupper($report_row['matter_desc2']));
                            $sheet->setCellValue('G' . $rows, strtoupper($report_row['judge_name']));
                            $sheet->setCellValue('H' . $rows, strtoupper($report_row['court_name']));
                            $sheet->setCellValue('I' . $rows, "'".strtoupper($report_row['reference_desc']));
                            $sheet->setCellValue('J' . $rows, strtoupper($report_row['product_desc']));
                            $sheet->setCellValue('K' . $rows, strtoupper($report_row['matter_type_desc']));
                            $sheet->setCellValue('L' . $rows, strtoupper($report_row['requisition_no']));
                            $sheet->setCellValue('M' . $rows, number_format($report_row['stake_amount'], 2, '.', ''));
                            $sheet->setCellValue('N' . $rows, $first_activity_date);
                            
                            $f_row_ind = 'Y';
                            $remarks   = '';
                            $col_no = 14;
    
                            foreach($oth_qry as $reportdtl_row) {
                                $activity_date = date_conv($reportdtl_row['activity_date']);
                                $next_date = date_conv($reportdtl_row['next_date']);
                    
                                if(empty($remarks)) $remarks = strtoupper($reportdtl_row['remarks']);
                                if($f_row_ind == 'Y') {
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $last_activity_date);
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($prev_fixed_for));
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $next_date);
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($next_fixed_for));
                                } else {
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $activity_date);
                                    $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $prev_fixed_for);
                                }
                                $f_row_ind = 'N' ;
                             }
                            $sheet->setCellValue(columnFromIndex($col_no) . $rows, $remarks);
    
                            $style = $sheet->getStyle('A' . $rows . ':' . columnFromIndex($col_no) . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
    
                            $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : [];
                            $rowcnt++;
                        }
                        $writer = new Xlsx($spreadsheet);
                        $writer->save($fileName);
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                        header('Expires: 0');
                        header('Cache-control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length:'.filesize($fileName));
                        flush();
                        readfile($fileName);
                        
                    } else {
                        $case_sql = "select a.*, f.activity_date, b.client_name, c.code_desc court_name,e.code_desc product_desc, concat(a.matter_desc1,' ',a.matter_desc2) matter_desc
                                    from client_master b, code_master c, fileinfo_header a left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'
                                    left outer join case_header f on f.matter_code = a.matter_code
                                    where ifnull(f.activity_date,'') between '$start_date_ymd' and '$end_date_ymd'
                                        and a.client_code like '$client_code'
                                        and a.matter_code like '$matter_code' 
                                        and a.court_code  like '$court_code' 
                                        and a.client_code    =  b.client_code
                                        and a.court_code     =  c.code_code  
                                        and c.type_code      = '001'
                                    order by a.client_code,a.matter_code";
                        $case_qry = $this->db->query($case_sql)->getresultArray();
                        $case_cnt = count($case_qry);

                        if(empty($case_qry)) {
                            session()->setFlashdata('message', 'No Records Found !!');
                            return redirect()->to($this->requested_url());
                        }

                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $rows = 1; $fileName = $headings = '';
    
                        $fileName = 'MIS-Matter-Status-Latest-'.date('d-m-Y').'.xlsx';  
                        $headings = ['Client Name', 'Initial', 'Filing', 'Matter', 'Case No', 'Matter Desc', 'Judge', 'Court', 'Reference', 'Product',
                            'Requisition No', 'Amount', 'First Acty Dt', 'Last Date', 'Fix For (Prev)', 'Next Date', 'Fix For (Next)'];
                        $column = 'A'; $rows++;
    
                        for($i=1;$i<$r_limit;$i++) { 
                            array_push($headings, 'Acty Date'.$i, 'Fix For'.$i);
                        }
    
                        array_push($headings, 'Remarks');
    
                        foreach ($headings as $heading) {
                            $cell = $column . $rows;
                            $sheet->setCellValue($cell, $heading); // Set the cell value
    
                            // Apply formatting
                            $style = $sheet->getStyle($cell);
                            $style->getFont()->setBold(true);
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                            ++$column; // Move to the next column
                        } $rows++;
    
                        $report_row = $case_qry[0]; 
                        $report_cnt = $case_cnt ;
                        $rowcnt     = 1; 
                        while ($rowcnt <= $report_cnt) {
                           $client_name    = strtoupper($report_row['client_name']);
                           $initial_code   = strtoupper($report_row['initial_code']);
                           $matter_code    = strtoupper($report_row['matter_code']);
                           $matter_desc1   = strtoupper($report_row['matter_desc1']);
                           $matter_desc2   = strtoupper($report_row['matter_desc2']);
                           $judge_name     = strtoupper($report_row['judge_name']);
                           $court_name     = strtoupper($report_row['court_name']);
                           $reference_desc = strtoupper($report_row['reference_desc']);
                           $product_desc   = strtoupper($report_row['product_desc']);
                           $requisition_no = strtoupper($report_row['requisition_no']);
                    
                            if ($report_row['date_of_filing'] != '' && $report_row['date_of_filing'] != '0000-00-00') { 
                                $date_of_filing = date_conv($report_row['date_of_filing'],'-') ; 
                            } else { $date_of_filing = '' ; }
                    
                           $sele_activity_date_stmt = "select date_format(min(activity_date),'%d-%m-%Y') activity_date from case_header where matter_code = '$matter_code'";
                           $sele_activity_date_qry  = $this->db->query($sele_activity_date_stmt)->getRowArray();
                           $first_activity_date     = $sele_activity_date_qry['activity_date']; 
                    
                    
                           $last_activity_date_stmt = "select date_format(max(activity_date),'%d-%m-%Y') activity_date from case_header where matter_code = '$matter_code'";
                           $last_activity_date_qry  = $this->db->query($last_activity_date_stmt)->getRowArray();
                           $last_activity_date     = $last_activity_date_qry['activity_date']; 
                    
                    
                           $stake_amount   =  $report_row['stake_amount'];
                           $oth_stmt = "select d.activity_date, d.judge_name, d.next_date, d.next_fixed_for, d.prev_fixed_for, d.remarks from case_header d where d.matter_code like '$matter_code'
                                        order by d.activity_date desc limit $r_limit "; 
                           $oth_qry = $this->db->query($oth_stmt)->getResultArray();
                           $oth_row = count($oth_qry);
                           
                           $fix_next_stmt = "select next_fixed_for,prev_fixed_for from case_header where matter_code like '$matter_code' and client_code like '$client_code'
                                            order by activity_date desc  ";
                           $fix_next_qry   = $this->db->query($fix_next_stmt)->getRowArray();
                           $next_fixed_for = $fix_next_qry['next_fixed_for']; 
                           $prev_fixed_for = $fix_next_qry['prev_fixed_for']; 

                           $sheet->setCellValue('A' . $rows, strtoupper($report_row['client_name']));
                           $sheet->setCellValue('B' . $rows, strtoupper($report_row['initial_code']));
                           $sheet->setCellValue('C' . $rows, $date_of_filing);
                           $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_code']));
                           $sheet->setCellValue('E' . $rows, strtoupper($report_row['matter_desc1']));
                           $sheet->setCellValue('F' . $rows, strtoupper($report_row['matter_desc2']));
                           $sheet->setCellValue('G' . $rows, strtoupper($report_row['judge_name']));
                           $sheet->setCellValue('H' . $rows, strtoupper($report_row['court_name']));
                           $sheet->setCellValue('I' . $rows, "'".strtoupper($report_row['reference_desc']));
                           $sheet->setCellValue('J' . $rows, strtoupper($report_row['product_desc']));
                           $sheet->setCellValue('K' . $rows, strtoupper($report_row['requisition_no']));
                           $sheet->setCellValue('L' . $rows, number_format($report_row['stake_amount'], 2, '.', ''));
                           $sheet->setCellValue('M' . $rows, $first_activity_date);
                           
                           $f_row_ind = 'Y';
                           $remarks   = '';
                           $col_no = 13;

                           foreach($oth_qry as $reportdtl_row) {
                               $activity_date = date_conv($reportdtl_row['activity_date']);
                               $next_date = date_conv($reportdtl_row['next_date']);
                   
                               if(empty($remarks)) $remarks = strtoupper($reportdtl_row['remarks']);
                               if($f_row_ind == 'Y') {
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $last_activity_date);
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($prev_fixed_for));
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $next_date);
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, strtoupper($next_fixed_for));
                               } else {
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $activity_date);
                                   $sheet->setCellValue(columnFromIndex($col_no++) . $rows, $prev_fixed_for);
                               }
                               $f_row_ind = 'N' ;
                            }
                           $sheet->setCellValue(columnFromIndex($col_no) . $rows, $remarks);
   
                           $style = $sheet->getStyle('A' . $rows . ':' . columnFromIndex($col_no) . $rows);
                           $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                           $rows++;
   
                           $report_row = ($rowcnt < $report_cnt) ? $case_qry[$rowcnt] : [];
                           $rowcnt++;
                       }

                       $writer = new Xlsx($spreadsheet);
                       $writer->save($fileName);
                       header('Content-Type: application/vnd.ms-excel');
                       header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                       header('Expires: 0');
                       header('Cache-control: must-revalidate');
                       header('Pragma: public');
                       header('Content-Length:'.filesize($fileName));
                       flush();
                       readfile($fileName);
                    }
                }
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4072', 'matter_help_id' => '4220', 'court_help_id' => '4221'] ;
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Matter/matter_ps_and_other", compact("data", "displayId"));
        }
    }

    public function excel_cause_list() { 
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Excel') {
                $report_desc   = 'EXCEL CAUSE LIST' ;
                // $ason_date       = $_REQUEST['ason_date'] ;
                $branch_code     = $_REQUEST['branch_code'] ;
                $start_date      = $_REQUEST['start_date'] ;   if($start_date != '')   { $start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01'; }
                $end_date        = $_REQUEST['end_date'] ;     if($end_date   != '')   { $end_date_ymd   = date_conv($end_date);   } else { $end_date_ymd   = $global_sysdate ; }
                $court_code      = $_REQUEST['court_code'] ;   if(empty($court_code))  { $court_code   = '%' ; }
                $court_name      = $_REQUEST['court_name'] ;
                $client_code     = $_REQUEST['client_code'] ;  if(empty($client_code)) { $client_code  = '%' ; }
                $client_name     = $_REQUEST['client_name'] ;
                $matter_code     = $_REQUEST['matter_code'] ;  if(empty($matter_code)) { $matter_code  = '%' ; }
                $matter_desc     = $_REQUEST['matter_desc'] ;
                $initial_code    = $_REQUEST['initial_code'] ; if(empty($initial_code)){ $initial_code = '%' ; }
                $initial_name    = $_REQUEST['initial_name'] ;
                // $billfor_ind     = $_REQUEST['billfor_ind'] ;
                // $billfor_ind     = 'N' ;
              
                // $report_seqn     = $_REQUEST['report_seqn'] ;
                $output_type     = $_REQUEST['output_type'] ;
                $branch_name     = getBranchName($branch_code) ;
                
                if ($start_date == '') { $period_desc = 'UPTO '.$end_date ; } else { $period_desc = $start_date.' - '.$end_date ; } 
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                $branch_name   = $branch_qry['branch_name'] ;
              
                $case_sql = "select a.activity_date, a.judge_name, a.prev_fixed_for, a.prev_date, a.next_fixed_for, a.next_date, a.instrument_ind, a.instrument_code, a.instrument_no, a.remarks, 
                            c.matter_desc1, c.matter_desc2, c.reference_desc, c.requisition_no, c.date_of_filing, c.court_code, c.initial_code, d.code_desc instrument, f.code_desc case_type, c.case_no, c.case_year, g.name complainant_name
                            from client_master b, fileinfo_header c, code_master d, case_header a, code_master f, fileinfo_details g					
                            where a.branch_code like '$branch_code' and a.client_code like '$client_code' and a.matter_code like '$matter_code' and a.client_code = b.client_code and ifnull(c.initial_code,'N') like '$initial_code'  
                            and a.activity_date between '$start_date_ymd' and '$end_date_ymd' and a.client_code = b.client_code and a.matter_code = c.matter_code and c.court_code like '$court_code'
                            and ifnull(g.matter_code,'N') = a.matter_code and g.record_code = '10' and ifnull(a.instrument_code,'N') = d.code_code and d.type_code = '040' and c.case_type_code = f.code_code and f.type_code = '006'
                            order by a.activity_date desc" ; 

                $case_qry = $this->db->query($case_sql)->getResultArray();
                $case_cnt = count($case_qry);

                if($case_cnt == 0) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $rows = 1; $fileName = $headings = '';

                $fileName = $report_desc .'-'.date('d-m-Y').'.xlsx';  
                $headings = ['SL. No', 'CRT', 'PROPOSAL NO', 'PRE DT', 'PRE STAGE', 'CASE NO', 'FORMATED CASE NO', 'PARTY NAME', 'ACTY DT', 'TODAY\'S FIXED FOR', 'NEXT DT', 'STAGE', 'COMP', 'ADV', 'COMMENTS OF ADV FOR TODAY\'S PROCEEDINGS', 'PARTY APP YES/NO', 'INST ISSUED', 'REQUISITE FILED FOR LEGAL', 'ENTRY DATE', 'RECEIVED'];
                $column = 'A'; $rows++;

                foreach ($headings as $heading) {
                    $cell = $column . $rows;
                    $sheet->setCellValue($cell, $heading); // Set the cell value

                    // Apply formatting
                    $style = $sheet->getStyle($cell);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                    ++$column; // Move to the next column
                } $rows++;

                foreach($case_qry as $key => $report_row) {            
                    // $serial_no         = strtoupper($report_row['serial_no']);
                    $next_date         = date_conv($report_row['next_date']);
                    // $client_name       = strtoupper($report_row['client_name']);
                    // $matter_code       = strtoupper($report_row['matter_code']);
                    $case_no           = strtoupper($report_row['matter_desc1']);
                    $matter_desc_case  = strtoupper($report_row['case_type'].'/'.$report_row['case_no'].'/'.substr($report_row['case_year'],2,4));
                    $matter_desc2      = strtoupper($report_row['matter_desc2']);
                    // $court_name        = strtoupper($report_row['court_name']);
                    $judge_name        = strtoupper($report_row['judge_name']);
                    $inst_issued       = strtoupper($report_row['instrument'].' '.$report_row['instrument_no']);
                    $reference_desc    = strtoupper($report_row['reference_desc']);
                    $requisition_no    = strtoupper($report_row['requisition_no']);
                    $complainant_name  = strtoupper($report_row['complainant_name']);
                    $advocate_name     = strtoupper('SINHA & COMPANY');
                    // $accused           = strtoupper($report_row['accused']);
                    // $service_month     = strtoupper($report_row['service_month']);
                    // $product_desc      = strtoupper($report_row['product_desc']);
                    $next_fixed_for    = strtoupper($report_row['next_fixed_for']);
                    $activity_date     = date_conv($report_row['activity_date']);
                    $prev_fixed_for    = strtoupper($report_row['prev_fixed_for']);
                    $prev_date         = date_conv($report_row['prev_date']);
                    $date_of_filing    = date_conv($report_row['date_of_filing']);
                    $remarks           = strtoupper($report_row['remarks']);

                    $sheet->setCellValue('A' . $rows, $key);
                    $sheet->setCellValue('B' . $rows, $judge_name);
                    $sheet->setCellValue('C' . $rows, "'".$reference_desc);
                    $sheet->setCellValue('D' . $rows, $prev_date);
                    $sheet->setCellValue('E' . $rows, $prev_fixed_for);
                    $sheet->setCellValue('F' . $rows, $case_no);
                    $sheet->setCellValue('G' . $rows, $matter_desc_case);
                    $sheet->setCellValue('H' . $rows, $matter_desc2);
                    $sheet->setCellValue('I' . $rows, $activity_date);
                    $sheet->setCellValue('J' . $rows, $next_fixed_for);
                    $sheet->setCellValue('k' . $rows, ($next_date == '') ? '-' : $next_date);
                    $sheet->setCellValue('L' . $rows, $next_fixed_for);
                    $sheet->setCellValue('M' . $rows, $complainant_name);
                    $sheet->setCellValue('N' . $rows, $advocate_name);
                    $sheet->setCellValue('O' . $rows, '-');
                    $sheet->setCellValue('P' . $rows, '-');
                    $sheet->setCellValue('Q' . $rows, $inst_issued);
                    $sheet->setCellValue('R' . $rows, '-');
                    $sheet->setCellValue('S' . $rows, '-');
                    $sheet->setCellValue('T' . $rows, '-');
                    
                    $style = $sheet->getStyle('A' . $rows . ':T' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;
                }

                $writer = new Xlsx($spreadsheet);
                $writer->save($fileName);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                header('Expires: 0');
                header('Cache-control: must-revalidate');
                header('Pragma: public');
                header('Content-Length:'.filesize($fileName));
                flush();
                readfile($fileName);
            }
        } else {
            $data = []; $global_dmydate = date('d-m-Y');
            $data = branches(session()->userId);
            $displayId = ['client_help_id' => '4072', 'matter_help_id' => '4220', 'court_help_id' => '4221', 'initial_help_id' => '4191'] ;
            $start_date     = $global_dmydate ;
            $end_date       = $global_dmydate ;
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/MIS/Matter/excel_cause_list", compact("data", "displayId", 'start_date', 'end_date'));
        }
    }

    public function instrument_receive() { }

    public function client_list_new() {
        $requested_url = session()->requested_end_menu_url;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';

        if($this->request->getMethod() == "post") {
            if($output_type == 'Report' || $output_type == 'Pdf' || $output_type == 'Excel') {

                $ason_date     = isset($_REQUEST['ason_date'])   ?$_REQUEST['ason_date']   : NULL ;  
                $start_date    = isset($_REQUEST['start_date'])  ?$_REQUEST['start_date']  : NULL ;  $start_date_ymd = date_conv($start_date);
                $end_date      = isset($_REQUEST['end_date'])    ?$_REQUEST['end_date']    : NULL ;  $end_date_ymd   = date_conv($end_date);
                
                $report_desc   = "LIST OF CLIENT(s) DURING ".$start_date." TO ".$end_date ;
              
                $trandtl_sql = "select a.prepared_on,a.client_code ,a.client_name,b.matter_code,b.matter_desc1,b.matter_desc2,b.initial_code,b.court_code						
                        from client_master a,fileinfo_header b
                        where a.prepared_on between '$start_date_ymd' and '$end_date_ymd'
                        and a.client_code = b.client_code 
                        order by a.prepared_on,a.client_name,b.matter_code "  ; 
                $trandtl_qry = $this->db->query($trandtl_sql)->getResultArray();
                // echo '<pre>';print_r($trandtl_qry);die;
                $trandtl_cnt = count($trandtl_qry);

                if(empty($trandtl_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
            
                $params = [
                    "report_desc" => $report_desc,
                    "trandtl_cnt" => $trandtl_cnt,
                    "requested_url" => $requested_url,
                ];
            
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/MIS/Matter/client_list_new", compact("trandtl_qry", "params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render(); 
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else if($output_type == 'Excel') {
                    $showActionBtns = true;
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $rows = 1;
                    $fileName = $headings = '';

                    $fileName = 'MIS-Clinet-List-New-'.date('d-m-Y').'.xlsx';  
                    $headings = ['Date', 'Client', 'Case No', 'Matter', 'Initial', 'Court'];
                    $column = 'A'; $rows++;

                    foreach ($headings as $heading) {
                        $cell = $column . $rows;
                        $sheet->setCellValue($cell, $heading); // Set the cell value

                        // Apply formatting
                        $style = $sheet->getStyle($cell);
                        $style->getFont()->setBold(true);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                        ++$column; // Move to the next column
                    } $rows++;

                    $rowcnt       = 1 ;
                    $report_row = isset($trandtl_qry[$rowcnt-1]) ? $trandtl_qry[$rowcnt-1] : '' ;
                    $report_cnt   = $params['trandtl_cnt'] ;
                    while ($rowcnt <= $report_cnt) {
                        $pclientind = 'Y';
                        $pclientcd  = $report_row['client_code'] ;
                        $pclientnm  = $report_row['client_name'] ;
                        while ($pclientcd == $report_row['client_code'] && $rowcnt <= $report_cnt) {
                            
                            $sheet->setCellValue('A' . $rows, date_conv($report_row['prepared_on']));
                            $sheet->setCellValue('B' . $rows, $report_row['client_name']);
                            $sheet->setCellValue('C' . $rows, strtoupper($report_row['matter_desc1']));
                            $sheet->setCellValue('D' . $rows, strtoupper($report_row['matter_desc2']));
                            $sheet->setCellValue('E' . $rows, strtoupper($report_row['initial_code']));
                            $sheet->setCellValue('F' . $rows, get_code_desc('001',$report_row['court_code']));

                            $report_row = ($rowcnt < $report_cnt) ? $trandtl_qry[$rowcnt] : $report_row; 
                            $rowcnt    += 1 ;

                            $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        } 
                    } 
                    $writer = new Xlsx($spreadsheet);
                    $writer->save($fileName);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                    header('Expires: 0');
                    header('Cache-control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length:'.filesize($fileName));
                    flush();
                    readfile($fileName); 
                } else return view("pages/MIS/Matter/client_list_new", compact("trandtl_qry", "params"));
            }
        } else {
            $data = [];
            $data = branches(session()->userId);
    
            return view("pages/MIS/Matter/client_list_new", compact("data"));
        }
    }

    /************************************ SERVICE TAX REPORT ******************************************/

    public function bill_register_service_tax() { 
        $user_option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $requested_url = session()->requested_end_menu_url;
        $global_dmydate = date('d-m-Y');

        if ($this->request->getMethod() == 'post' ) {

            $report_desc        = "BILL REGISTER SERVICE TAX (BILL DATE WISE)" ;
            $branch_code        = $_REQUEST['branch_code'] ;
            $billing_start_date = $_REQUEST['billing_start_date'] ; if($billing_start_date != '') { $billing_start_date_ymd = date_conv($billing_start_date); } else { $billing_start_date_ymd = '1901-01-01'; }
            $billing_end_date   = $_REQUEST['billing_end_date'] ;   if($billing_end_date   != '') { $billing_end_date_ymd   = date_conv($billing_end_date);   } else { $billing_end_date_ymd   = $global_sysdate ; }
            $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';
            $branch_name        = getBranchName($branch_code);
            $reports = true;
            if($billing_start_date == '') {$period_desc = "UPTO ".$billing_end_date ;} else {$period_desc = $billing_start_date.' - '.$billing_end_date ;}

            $bill_sql    = "select a.fin_year bill_year,a.bill_no,a.bill_date, a.service_tax_amount, concat(a.fin_year,'/',a.bill_no) billno,
                                (ifnull(a.bill_amount_inpocket_stax,0) + ifnull(a.bill_amount_outpocket_stax,0) + ifnull(a.bill_amount_counsel_stax,0)) taxable_total,
                                (ifnull(a.bill_amount_inpocket_ntax,0) + ifnull(a.bill_amount_outpocket_ntax,0) + ifnull(a.bill_amount_counsel_ntax,0)) non_taxable_total,
                                (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0)) bill_total,
                                (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                                (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0)) realised_amount,
                                (ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0)) deficit_amount,
                                ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(a.realise_amount_inpocket,0) + ifnull(a.realise_amount_outpocket,0) + ifnull(a.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(a.deficit_amount_inpocket,0) + ifnull(a.deficit_amount_outpocket,0) + ifnull(a.deficit_amount_counsel,0) + ifnull(a.deficit_amount_service_tax,0))) balance_amount,
                                b.client_name
                            from bill_detail a, client_master b 
                            where a.client_code = b.client_code
                            and (ifnull(a.cancel_ind,'N') = 'N' or a.cancel_ind = '')
                            and a.service_tax_amount > 0
                            and a.bill_date between '$billing_start_date_ymd'  and '$billing_end_date_ymd' 
                            order by a.bill_date";

            $bill_qry  = $this->db->query($bill_sql)->getResultArray();
            $bill_cnt  = count($bill_qry);

            if ($bill_cnt == 0) {
                session()->setFlashdata('message', 'No records Found !!');
                return redirect()->to($requested_url);
            }

            if ($output_type == 'Pdf') {
                $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                $reportHTML = view('pages/MIS/Service_tax_report/bill_register_service_tax', compact('report_type', 'bill_qry', 'bill_cnt', 'branch_code', 'billing_start_date', 'billing_end_date', 'period_desc', 'report_desc', 'reports', 'branch_name', 'global_dmydate', 'requested_url'));
                $dompdf->loadHtml($reportHTML);
                $dompdf->setPaper('A4', 'landscape'); // portrait
                $dompdf->render(); 
                $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;

            } else if ($output_type == 'Excel') {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $rows = 1; $fileName = $headings = '';

                $fileName = $report_desc . '-' .date('d-m-Y').'.xlsx';  
                $headings = ['Bill No', 'Bill Date', 'Client Name', 'Non Taxable Service', 'Taxable Service', 'S Tax %', 'Service Tax', 'Edu. Cess', 'H.S. Edu. Cess', 'Total S. Tax', 'Re-Imbursement', 'Bill total'];
                $column = 'A'; $rows++;

                foreach ($headings as $heading) {
                    $cell = $column . $rows;
                    $sheet->setCellValue($cell, $heading); // Set the cell value

                    // Apply formatting
                    $style = $sheet->getStyle($cell);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                    ++$column; // Move to the next column
                } $rows++;

                $report_row = $bill_qry[0]; 
                $report_cnt = $bill_cnt ;
                $rowcnt     = 1 ;
                $s_tax_percent = $s_tax = $cess_tax = $hecess_tax = $totsrv_tax_amount = 0;
                
                while ($rowcnt <= $report_cnt) {
                    $realamt = isset($report_row['realamt']) ? $report_row['realamt'] : 0;  
                    $defcamt = isset($report_row['defcamt']) ? $report_row['defcamt'] : 0;
            
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

                    $sheet->setCellValue('A' . $rows, $report_row['billno']);
                    $sheet->setCellValue('B' . $rows, date_conv($report_row['bill_date']));
                    $sheet->setCellValue('C' . $rows, strtoupper($report_row['client_name']));
                    $sheet->setCellValue('D' . $rows, number_format($report_row['non_taxable_total'], 2, '.', ''));
                    $sheet->setCellValue('E' . $rows, number_format($report_row['taxable_total'], 2, '.', ''));
                    $sheet->setCellValue('F' . $rows, $s_tax_percent);
                    $sheet->setCellValue('G' . $rows, number_format($s_tax, 2, '.', ''));
                    $sheet->setCellValue('H' . $rows, number_format($cess_tax, 2, '.', ''));
                    $sheet->setCellValue('I' . $rows, number_format($hecess_tax, 2, '.', ''));
                    $sheet->setCellValue('J' . $rows, number_format($report_row['service_tax_amount'], 2, '.', ''));
                    $sheet->setCellValue('K' . $rows, number_format($report_row['non_taxable_total'], 2, '.', ''));
                    $sheet->setCellValue('L' . $rows, number_format($report_row['billed_amount'], 2, '.', ''));

                    $report_row = ($rowcnt < $report_cnt) ? $bill_qry[$rowcnt] : $report_row;   
                    $rowcnt = $rowcnt + 1 ;

                    $style = $sheet->getStyle('A' . $rows . ':L' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;
                }  
                    
                $writer = new Xlsx($spreadsheet);
                $writer->save($fileName);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                header('Expires: 0');
                header('Cache-control: must-revalidate');
                header('Pragma: public');
                header('Content-Length:'.filesize($fileName));
                flush();
                readfile($fileName); 
            } else {
                return view('pages/MIS/Service_tax_report/bill_register_service_tax', compact('bill_qry', 'bill_cnt', 'branch_code', 'billing_start_date', 'billing_end_date', 'period_desc', 'report_desc', 'reports', 'branch_name', 'global_dmydate', 'requested_url'));
            }
        } else {
            $branch_code = session()->user_qry['branch_code'];
            $billing_start_date = '' ;
            $billing_end_date = $global_dmydate ;
            $current_date = $global_dmydate ; 
        
            $branch_qry = $this->db->query(session()->branch_selection_stmt)->getResultArray();
            return view('pages/MIS/Service_tax_report/bill_register_service_tax', compact('branch_qry', 'branch_code', 'billing_start_date', 'billing_end_date', 'current_date', 'user_option'));
        }
    }

    public function bill_realisation_service_tax() { 
        $user_option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $requested_url = session()->requested_end_menu_url;
        $global_curr_finyear = session()->financialYear;
        $global_curr_finyr_fdmydate = '01-04-'.substr($global_curr_finyear,0,4);
        $finyr_qry = $this->db->query("select fin_year from params order by fin_year desc")->getResultArray(); 
        $global_dmydate = date('d-m-Y');
        
        if ($this->request->getMethod() == 'post' ) {
            $reports = true;
            $report_desc             = "BILL REALISED SERVICE TAX";
            $branch_code             = $_REQUEST['branch_code'];
            $fin_year                = $_REQUEST['fin_year'];
            $realisation_start_date  = $_REQUEST['realisation_start_date']; if($realisation_start_date != '') { $realisation_start_date_ymd = date_conv($realisation_start_date); } else { $realisation_start_date_ymd = '1901-01-01'; }
            $realisation_end_date    = $_REQUEST['realisation_end_date'];   if($realisation_end_date   != '') { $realisation_end_date_ymd   = date_conv($realisation_end_date);   } else { $realisation_end_date_ymd   = $global_sysdate; }
            $billing_start_date      = $_REQUEST['billing_start_date'];     if($billing_start_date     != '') { $billing_start_date_ymd     = date_conv($billing_start_date);     } else { $billing_start_date_ymd     = '1901-01-01'; }
            $billing_end_date        = $_REQUEST['billing_end_date'];       if($billing_end_date       != '') { $billing_end_date_ymd       = date_conv($billing_end_date);       } else { $billing_end_date_ymd       = $global_sysdate; }
            $output_type             = $_REQUEST['output_type'];
            $branch_name             = getBranchName($branch_code);

            $financial_year = $fin_year;
            if($realisation_start_date == '') { $period_desc = "UPTO ".$realisation_end_date; } else { $period_desc = $realisation_start_date.' - '.$realisation_end_date; }

            if ($financial_year == '2011-2012') {
                $bill_sql = "select a.fin_year bill_year,a.bill_no,a.bill_date,d.doc_no,d.doc_date,e.serial_no,
                                a.service_tax_amount,
                                concat(a.fin_year,'/',a.bill_no) billno,
                                (ifnull(a.bill_amount_inpocket_stax,0) + ifnull(a.bill_amount_outpocket_stax,0) + ifnull(a.bill_amount_counsel_stax,0)) taxable_total,
                                (ifnull(a.bill_amount_inpocket_ntax,0) + ifnull(a.bill_amount_outpocket_ntax,0) + ifnull(a.bill_amount_counsel_ntax,0)) non_taxable_total,
                                (ifnull(a.bill_amount_outpocket_ntax,0)) reim,
                                (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0)) bill_total,
                                (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                                (ifnull(c.realise_amount_inpocket, 0)+ifnull(c.realise_amount_outpocket,0)+ifnull(c.realise_amount_counsel,0)+ifnull(c.realise_amount_service_tax,0)) realised_amount,
                                (ifnull(c.deficit_amount_inpocket,0) + ifnull(c.deficit_amount_outpocket,0) + ifnull(c.deficit_amount_counsel,0) + ifnull(c.deficit_amount_service_tax,0)) deficit_amount,
                                ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(c.realise_amount_inpocket,0) + ifnull(c.realise_amount_outpocket,0) + ifnull(c.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(c.deficit_amount_inpocket,0) + ifnull(c.deficit_amount_outpocket,0) + ifnull(c.deficit_amount_counsel,0) + ifnull(c.deficit_amount_service_tax,0))) balance_amount,
                                b.client_name
                            from bill_detail a, client_master b, bill_realisation_detail c,ledger_trans_hdr d,bill_realisation_header e 
                            where a.client_code        = b.client_code
                            and d.fin_year             = '$financial_year'
                            and a.serial_no            = c.ref_bill_serial_no
                            and e.serial_no            = c.ref_realisation_serial_no
                            and e.ref_ledger_serial_no = d.serial_no
                            and a.service_tax_amount > 0
                            and d.doc_date between '$realisation_start_date_ymd' and '$realisation_end_date_ymd' 
                            order by d.doc_date";
            } else {
                $bill_sql = "select a.fin_year bill_year,a.bill_no,a.bill_date,d.doc_no,d.doc_date,e.serial_no, a.service_tax_amount, concat(a.fin_year,'/',a.bill_no) billno,
                                    (ifnull(c.realise_amount_inpocket_stax,0) + ifnull(c.realise_amount_outpocket_stax,0) + ifnull(c.realise_amount_counsel_stax,0)) taxable_total,
                                    (ifnull(c.realise_amount_inpocket_ntax,0) + ifnull(c.realise_amount_outpocket_ntax,0) + ifnull(c.realise_amount_counsel_ntax,0)) non_taxable_total,
                                    (ifnull(c.realise_amount_outpocket_ntax,0)) reim,
                                    (ifnull(c.realise_amount_service_tax,0)) real_stax,
                                    (ifnull(c.deficit_amount_service_tax,0)) def_stax,
                                    (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0)) bill_total,
                                    (ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) billed_amount,
                                    (ifnull(c.realise_amount_inpocket, 0)+ifnull(c.realise_amount_outpocket,0)+ifnull(c.realise_amount_counsel,0)+ifnull(c.realise_amount_service_tax,0)) realised_amount,
                                    (ifnull(c.deficit_amount_inpocket,0) + ifnull(c.deficit_amount_outpocket,0) + ifnull(c.deficit_amount_counsel,0) + ifnull(c.deficit_amount_service_tax,0)) deficit_amount,
                                    ((ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0) + ifnull(a.service_tax_amount,0)) - (ifnull(c.realise_amount_inpocket,0) + ifnull(c.realise_amount_outpocket,0) + ifnull(c.realise_amount_counsel,0) + ifnull(a.realise_amount_service_tax,0) + ifnull(a.advance_amount_inpocket,0) + ifnull(a.advance_amount_outpocket,0) + ifnull(a.advance_amount_counsel,0) + ifnull(a.advance_amount_service_tax,0) + ifnull(c.deficit_amount_inpocket,0) + ifnull(c.deficit_amount_outpocket,0) + ifnull(c.deficit_amount_counsel,0) + ifnull(c.deficit_amount_service_tax,0))) balance_amount,
                                    b.client_name
                                from bill_detail a, client_master b, bill_realisation_detail c,ledger_trans_hdr d,bill_realisation_header e 
                                where a.client_code        = b.client_code
                                and d.fin_year             = '$financial_year'
                                and a.serial_no            = c.ref_bill_serial_no
                                and e.serial_no            = c.ref_realisation_serial_no
                                and e.ref_ledger_serial_no = d.serial_no
                                and a.realise_amount_service_tax > 0
                                and (ifnull(c.realise_amount_inpocket_stax,0) + ifnull(c.realise_amount_outpocket_stax,0) + ifnull(c.realise_amount_counsel_stax,0)) > 0
                                and d.doc_date between '$realisation_start_date_ymd'  and '$realisation_end_date_ymd' 
                                order by d.doc_date " ;
            } 

            $bill_qry  = $this->db->query($bill_sql)->getResultArray();
            $bill_cnt  = count($bill_qry);

            if ($bill_cnt == 0) {
                session()->setFlashdata('message', 'No records Found !!');
                return redirect()->to($requested_url);
            }
            
            if($output_type == 'Pdf') {
                $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                $reportHTML = view('pages/MIS/Service_tax_report/bill_realisation_service_tax', compact('bill_qry', 'bill_cnt', 'branch_code', 'billing_start_date', 'billing_end_date', 'period_desc', 'report_desc', 'reports', 'branch_name', 'global_dmydate', 'requested_url', 'report_type'));
                $dompdf->loadHtml($reportHTML);
                $dompdf->setPaper('A4', 'landscape'); // portrait
                $dompdf->render(); 
                $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;

            } else if ($output_type == 'Excel') {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $rows = 1; $fileName = $headings = '';

                $fileName = $report_desc . '-' .date('d-m-Y').'.xlsx';  
                $headings = ['Bill No', 'Bill Date', 'Doc No', 'Doc Date', 'Client Name', 'Non Taxable Service', 'Taxable Service', 'S Tax %', 'Service Tax', 'Edu. Cess', 'H.S. Edu. Cess', 'Total S. Tax', 'Re-Imbursement', 'Bill total', 'Realised', 'Deficit'];
                $column = 'A'; $rows++;

                foreach ($headings as $heading) {
                    $cell = $column . $rows;
                    $sheet->setCellValue($cell, $heading); // Set the cell value

                    // Apply formatting
                    $style = $sheet->getStyle($cell);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                    ++$column; // Move to the next column
                } $rows++;

                $report_row = $bill_qry[0]; 
                $report_cnt = $bill_cnt ;
                $rowcnt     = 1 ;
                while ($rowcnt <= $report_cnt) {
                    $realamt = isset($report_row['realamt']) ? $report_row['realamt'] : 0;  
                    $defcamt = isset($report_row['defcamt']) ? $report_row['defcamt'] : 0;
            
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

                    if ($s_tax > 0) { 
                        $sheet->setCellValue('A' . $rows, $report_row['billno']);
                        $sheet->setCellValue('B' . $rows, date_conv($report_row['bill_date']));
                        $sheet->setCellValue('C' . $rows, $report_row['doc_no']);
                        $sheet->setCellValue('D' . $rows, date_conv($report_row['doc_date']));
                        $sheet->setCellValue('E' . $rows, strtoupper($report_row['client_name']));
                        $sheet->setCellValue('F' . $rows, number_format($report_row['non_taxable_total'], 2, '.', ''));
                        $sheet->setCellValue('G' . $rows, number_format($report_row['taxable_total'], 2, '.', ''));
                        $sheet->setCellValue('H' . $rows, $s_tax_percent);
                        $sheet->setCellValue('I' . $rows, number_format($s_tax, 2, '.', ''));
                        $sheet->setCellValue('J' . $rows, number_format($cess_tax, 2, '.', ''));
                        $sheet->setCellValue('K' . $rows, number_format($hecess_tax, 2, '.', ''));
                        $sheet->setCellValue('L' . $rows, number_format($totsrv_tax_amount, 2, '.', ''));
                        $sheet->setCellValue('M' . $rows, number_format($report_row['reim'], 2, '.', ''));
                        $sheet->setCellValue('N' . $rows, number_format($report_row['billed_amount'], 2, '.', ''));
                        $sheet->setCellValue('O' . $rows, number_format($report_row['realised_amount'], 2, '.', ''));
                        $sheet->setCellValue('P' . $rows, number_format($report_row['deficit_amount'], 2, '.', ''));
                    }
                    $report_row = ($rowcnt < $report_cnt) ? $bill_qry[$rowcnt] : $report_row;   
                    $rowcnt = $rowcnt + 1 ;

                    $style = $sheet->getStyle('A' . $rows . ':P' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;
                }  
                    
                $writer = new Xlsx($spreadsheet);
                $writer->save($fileName);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                header('Expires: 0');
                header('Cache-control: must-revalidate');
                header('Pragma: public');
                header('Content-Length:'.filesize($fileName));
                flush();
                readfile($fileName); 
                
            } else {
                return view('pages/MIS/Service_tax_report/bill_realisation_service_tax', compact('bill_qry', 'bill_cnt', 'branch_code', 'billing_start_date', 'billing_end_date', 'period_desc', 'report_desc', 'reports', 'branch_name', 'global_dmydate', 'requested_url'));
            }
        } else {
            $branch_code = session()->user_qry['branch_code'];
            $billing_start_date = '' ;
            $billing_end_date = $global_dmydate ;
            $current_date = $global_dmydate ; 
            $realisation_start_date = $global_curr_finyr_fdmydate ;
            $realisation_end_date   = $global_dmydate ;
        
            $branch_qry = $this->db->query(session()->branch_selection_stmt)->getResultArray();
            return view('pages/MIS/Service_tax_report/bill_realisation_service_tax', compact('branch_qry', 'branch_code', 'billing_start_date', 'billing_end_date', 'realisation_start_date', 'realisation_end_date', 'current_date', 'user_option', 'finyr_qry'));
        }
    }

    public function payments_made_to_party_service_tax() { 
        $user_option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $requested_url = session()->requested_end_menu_url;
        $global_dmydate = date('d-m-Y');

        if ($this->request->getMethod() == 'post' ) {        
            $reports = true;
            $report_desc = "PAYMENTS MADE TO PARTY SERVICE TAX (BILL DATE WISE)" ;
            $branch_code = $_REQUEST['branch_code'] ;
            $billing_start_date = $_REQUEST['billing_start_date'] ;       if($billing_start_date     != '')    { $billing_start_date_ymd     = date_conv($billing_start_date);     } else { $billing_start_date_ymd     = '1901-01-01'; }
            $billing_end_date   = $_REQUEST['billing_end_date'] ;         if($billing_end_date       != '')    { $billing_end_date_ymd       = date_conv($billing_end_date);       } else { $billing_end_date_ymd       = $global_sysdate ; }
            $output_type        = $_REQUEST['output_type'] ;
            $branch_qry  = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
            $branch_name = $branch_qry['branch_name'] ;

            if($billing_start_date    == '' ) {$period_desc = "UPTO ".$billing_end_date ;} else {$period_desc = $billing_start_date.' - '.$billing_end_date ;}

            $bill_sql = "select a.fin_year,b.bill_no,b.bill_date,a.doc_no,a.doc_date, (ifnull(a.basic_amount,0)) basic_amount, (ifnull(a.new_tax_amount,0)) new_tax_amount,
                            (ifnull(a.new_tax_cess_amount,0)) new_tax_cess_amount, (ifnull(a.new_tax_hecess_amount,0)) new_tax_hecess_amount, (ifnull(b.gross_amount,0)) gross_amount, a.service_tax_amount, a.payee_payer_code, a.payee_payer_name 
                            from ledger_trans_hdr a, ledger_trans_dtl b 
                            where a.serial_no = b.ref_ledger_serial_no
                            and a.ref_doc_type in ('PV','MJ') and a.service_tax_amount > 0
                            and a.doc_date between '$billing_start_date_ymd'  and '$billing_end_date_ymd'
                            group by a.serial_no 
                            union all
                                select d.fin_year, a.memo_no bill_no, a.memo_date bill_date,d.doc_no doc_no,d.doc_date doc_date
                                ,(ifnull(b.counsel_fee,0)) basic_amount, (ifnull(b.new_tax_amount,0)) new_tax_amount, (ifnull(b.new_tax_cess_amount,0)) new_tax_cess_amount, (ifnull(b.new_tax_hecess_amount,0)) new_tax_hecess_amount
                                ,(ifnull(b.counsel_fee,0) + ifnull(b.new_tax_amount,0) + ifnull(b.new_tax_cess_amount,0) + ifnull(b.new_tax_hecess_amount,0)) gross_amount
                                ,b.service_tax_amount service_tax_amount, a.counsel_code payee_payer_code, c.associate_name payee_payer_name
                            FROM counsel_memo_header a, counsel_memo_detail b, associate_master c, ledger_trans_hdr d
                            WHERE a.serial_no = b.ref_counsel_memo_serial_no
                            and a.counsel_code = c.associate_code
                            and c.associate_type = '001'
                            and a.ref_ledger_serial_no > 0
                            and a.ref_ledger_serial_no = d.serial_no
                            and d.doc_date between '$billing_start_date_ymd'  and '$billing_end_date_ymd'
                            and b.service_tax_amount >0 
                            order by doc_date";
 
            $bill_qry = $this->db->query($bill_sql)->getResultArray();
            $bill_cnt = count($bill_qry);

            if ($bill_cnt == 0) {
                session()->setFlashdata('message', 'No records Found !!');
                return redirect()->to($requested_url);
            }

            if ($output_type == 'Pdf') {
                $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                $reportHTML = view('pages/MIS/Service_tax_report/payments_made_to_party_service_tax', compact('report_type', 'bill_qry', 'bill_cnt', 'branch_code', 'billing_start_date', 'billing_end_date', 'period_desc', 'report_desc', 'reports', 'branch_name', 'global_dmydate', 'requested_url'));
                $dompdf->loadHtml($reportHTML);
                $dompdf->setPaper('A4', 'landscape'); // portrait
                $dompdf->render(); 
                $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;

            } else if ($output_type == 'Excel') {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $rows = 1; $fileName = $headings = '';

                $fileName = $report_desc . '-' .date('d-m-Y').'.xlsx';  
                $headings = ['Bill No', 'Bill Date', 'Doc No', 'Doc Date', 'Party Name', 'Basic Amount', 'S Tax %', 'Service Tax', 'Edu. Cess', 'H.S. Edu. Cess', 'Total S. Tax', 'Bill total'];
                $column = 'A'; $rows++;

                foreach ($headings as $heading) {
                    $cell = $column . $rows;
                    $sheet->setCellValue($cell, $heading); // Set the cell value

                    // Apply formatting
                    $style = $sheet->getStyle($cell);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); // Add borders
                    ++$column; // Move to the next column
                } $rows++;

                $report_row = $bill_qry[0]; 
                $report_cnt = $bill_cnt ;
                $rowcnt     = 1 ;
                while ($rowcnt <= $report_cnt) {
            
                    if ($report_row['fin_year'] == '2010-2011') {
                            $s_tax_percent = '10.30%';
                    }
                    
                    if ($report_row['fin_year'] == '2011-2012') {
                            $s_tax_percent = '10.30%';
                    }
                    
                    if ($report_row['fin_year'] == '2012-2013') {
                            $s_tax_percent = '12.36%';
                    }

                    $sheet->setCellValue('A' . $rows, $report_row['bill_no']);
                    $sheet->setCellValue('B' . $rows, date_conv($report_row['bill_date']));
                    $sheet->setCellValue('C' . $rows, $report_row['doc_no']);
                    $sheet->setCellValue('D' . $rows, date_conv($report_row['doc_date']));
                    $sheet->setCellValue('E' . $rows, strtoupper($report_row['payee_payer_name']));
                    $sheet->setCellValue('F' . $rows, number_format($report_row['basic_amount'], 2, '.', ''));
                    $sheet->setCellValue('G' . $rows, $s_tax_percent);
                    $sheet->setCellValue('H' . $rows, number_format($report_row['new_tax_amount'], 2, '.', ''));
                    $sheet->setCellValue('I' . $rows, number_format($report_row['new_tax_cess_amount'], 2, '.', ''));
                    $sheet->setCellValue('J' . $rows, number_format($report_row['new_tax_hecess_amount'], 2, '.', ''));
                    $sheet->setCellValue('K' . $rows, number_format($report_row['service_tax_amount'], 2, '.', ''));
                    $sheet->setCellValue('L' . $rows, number_format($report_row['gross_amount'], 2, '.', ''));

                    $report_row = ($rowcnt < $report_cnt) ? $bill_qry[$rowcnt] : $report_row;   
                    $rowcnt = $rowcnt + 1 ;

                    $style = $sheet->getStyle('A' . $rows . ':L' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;
                }  
                    
                $writer = new Xlsx($spreadsheet);
                $writer->save($fileName);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                header('Expires: 0');
                header('Cache-control: must-revalidate');
                header('Pragma: public');
                header('Content-Length:'.filesize($fileName));
                flush();
                readfile($fileName); 
            } else {
                return view('pages/MIS/Service_tax_report/payments_made_to_party_service_tax', compact('bill_qry', 'bill_cnt', 'branch_code', 'billing_start_date', 'billing_end_date', 'period_desc', 'report_desc', 'reports', 'branch_name', 'global_dmydate', 'requested_url'));
            }
        } else {
            $branch_code = session()->user_qry['branch_code'];
            $billing_start_date = '' ;
            $billing_end_date = $global_dmydate ;
            $current_date = $global_dmydate ; 
        
            $branch_qry = $this->db->query(session()->branch_selection_stmt)->getResultArray();
            return view('pages/MIS/Service_tax_report/payments_made_to_party_service_tax', compact('branch_qry', 'branch_code', 'billing_start_date', 'billing_end_date', 'current_date', 'user_option'));
        }
    }

}

?>