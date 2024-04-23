<?php

namespace App\Controllers; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BankReconciliationController extends BaseController
{
    public function __construct() {
        $this->db = \config\database::connect();
        $this->session = session();
    }
    
    /*********************************************************************************************/
    /***************************** Bank-Reconciliation [Transactions] ***********************************/
    /*********************************************************************************************/

    public function bank_reconciliation_entry($option = null) {

        $user_id = session()->userId ;
        $data = branches($user_id);
        $user_option = $option; 
        $data['requested_url'] = $this->session->requested_end_menu_url;

        $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        //$user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
        $selemode      = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:null;
        
        $daybook_qry   = $this->db->query("select daybook_desc,daybook_code from daybook_master where daybook_type = 'BB' order by daybook_desc ")->getResultArray() ; 
        $month_qry     = $this->db->query("select month_descl, month_no from months order by month_serial ")->getResultArray() ;
        $finyr_qry     = $this->db->query("select fin_year from params order by fin_year desc ")->getResultArray() ;
        $date_dmy      = date('d-m-Y');
        $redv = 'readonly' ;  $disv = 'disabled' ; $disb = 'disabled' ;
        $fin_year    = session()->financialYear ;
        $month_no    = substr($date_dmy,03,02) ;
        $rec_type    = 'U' ;
        $trans_type  = 'U' ;
        $recon_sql = '';
        if ($this->request->getMethod() == 'post') {
            if($selemode == 'Y' && $user_option != 'confirm') {
                
                $requested_url   = base_url($data['requested_url']);
                $db_code         = isset($_REQUEST['db_code'])?$_REQUEST['db_code']:null; 
                $daybook_code    = isset($_REQUEST['daybook_code'])?$_REQUEST['daybook_code']:null; 
                $rec_type        = isset($_REQUEST['rec_type'])?$_REQUEST['rec_type']:null; 
                $trans_type      = isset($_REQUEST['trans_type'])?$_REQUEST['trans_type']:null; 
                $last_recon_date = isset($_REQUEST['last_recon_date'])?$_REQUEST['last_recon_date']:null; 
                $reco_yymm       = isset($_REQUEST['reco_yymm'])?$_REQUEST['reco_yymm']:null; 
                $reco_sdt        = substr($reco_yymm,0,4).'-'.substr($reco_yymm,4,2).'-'.'01' ;
                //
                $xqry            = $this->db->query("select last_day('$reco_sdt') ldate ")->getRowArray();
                $reco_edt        = $xqry['ldate'] ;
                
                $last_recon_qry  = $this->db->query("select max(clear_date) last_cleared_date from bank_recon where daybook_code = '$daybook_code' ")->getRowArray();
                $last_date       = $last_recon_qry['last_cleared_date'] ;  
                if ($last_date != '') { $last_recon_date = date_conv($last_date,'/') ; } else { $last_recon_date = '' ; }
                
                $daybook_qry1   = $this->db->query("select daybook_desc from daybook_master where daybook_code = '$daybook_code' ")->getRowArray() ;
                // echo '<pre>';print_r($daybook_qry1);die;
                $daybook_name  = $daybook_qry1['daybook_desc'] ;

                if ($trans_type == 'A')  {
                    $recon_sql = "select a.serial_no,a.doc_no,a.instrument_no,a.instrument_dt,a.payee_payer_name, if(a.dr_cr_ind = 'D',a.amount,'') debit_amt, if(a.dr_cr_ind = 'C',a.amount,'') credit_amt, a.clear_date
                            from bank_recon a 
                            where a.daybook_code like '$daybook_code' and a.doc_date <= '$reco_edt' and a.clear_date between '$reco_sdt' and '$reco_edt'
                            union all  	 
                            select a.serial_no,a.doc_no,a.instrument_no,a.instrument_dt,a.payee_payer_name, if(a.dr_cr_ind = 'D',a.amount,'') debit_amt, if(a.dr_cr_ind = 'C',a.amount,'') credit_amt, a.clear_date
                            from bank_recon a 
                            where a.daybook_code like '$daybook_code' and a.doc_date <= '$reco_edt' and (a.clear_date is null or a.clear_date = '0000-00-00')
                            order by 2 " ; 
                } else if ($trans_type == 'R') {
                    $recon_sql = "select a.serial_no,a.doc_no,a.instrument_no,a.instrument_dt,a.payee_payer_name, if(a.dr_cr_ind = 'D',a.amount,'') debit_amt, if(a.dr_cr_ind = 'C',a.amount,'') credit_amt, a.clear_date
                                from bank_recon a 
                                where a.daybook_code like '$daybook_code' and a.doc_date <= '$reco_edt'and a.clear_date between '$reco_sdt' and '$reco_edt'
                                order by a.instrument_no " ;
                } else if ($trans_type == 'U') {
                    $recon_sql = "select a.serial_no,a.doc_no,a.instrument_no,a.instrument_dt,a.payee_payer_name, if(a.dr_cr_ind = 'D',a.amount,'') debit_amt, if(a.dr_cr_ind = 'C',a.amount,'') credit_amt, ifnull(a.clear_date,'0000-00-00') clear_date
                                from bank_recon  a 
                                where a.daybook_code like '$daybook_code' and a.doc_date <= '$reco_edt' and (a.clear_date is null or a.clear_date = '0000-00-00')
                                order by a.instrument_no " ;
                }
          
                $recon_qry = $this->db->query($recon_sql)->getResultArray();
                $recon_cnt = count($recon_qry);
                // echo '<pre>';print_r($recon_qry);die;


                if(empty($recon_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($requested_url);
                }

                $params = [
                    "db_code"           => $db_code,
                    "daybook_code"      => $daybook_code,
                    "last_recon_date"   => $last_recon_date,
                    "recon_cnt"         => $recon_cnt, 
                    "daybook_name"      => $daybook_name, 
                    "requested_url"     => $requested_url, 
                    "reco_yymm"         => $reco_yymm, 
                    "selemode"          => $selemode, 
                    "db_code"           => $db_code,        
                    "daybook_code"      => $daybook_code, 
                    "trans_type"        => $trans_type, 
                ];
    
                return view("pages/BankReconciliation/bank_reconciliation_entry", compact("user_option", "data", "fin_year", "month_no", "params", "recon_qry", "rec_type", "trans_type", "daybook_qry", "finyr_qry", "month_qry", "selemode"));
            } else if($user_option == 'confirm') {
                // echo $user_option;die;
                $db_code         = $_POST['db_code'];
                $daybook_code    = $_POST['daybook_code'];
                //$trans_upto_date = $_POST['trans_upto_date'];
                $recon_cnt        = $_POST['recon_cnt'];
                $trans_type      = $_POST['trans_type'];

                for ($i = 1; $i <= $recon_cnt; $i++)
                {

                    $serial_no  = $_POST['serial_no'.$i] ;
                    $clear_ind  = isset($_POST['cleared_ind'.$i]) ?  $_POST['cleared_ind'.$i] : '';
                    $clear_date = $_POST['cleared_date'.$i] ; 
                    if ($clear_ind == 'Y') { $status_code = 'B' ; $clear_date = date_conv($clear_date,'-') ; } else { $status_code = 'A' ; $clear_date = null ; }
                    //
                    $update_sql = "update bank_recon set clear_date ='$clear_date', updated_by ='$user_id', status_code = '$status_code'  where serial_no = '$serial_no' ";
                    $this->db->query($update_sql);
                }
                session()->setFlashdata('success_message', 'Records Updated Successfully');
                return redirect()->to($data['requested_url']);
            }
        }  else {
            
            return view("pages/BankReconciliation/bank_reconciliation_entry", compact("user_option", "data", "fin_year", "month_no", "rec_type", "trans_type", "daybook_qry", "finyr_qry", "month_qry", "selemode"));
        }	
    }

    public function bank_transections_debited_credited($option = null) {

        $user_id = session()->userId ;
        $data = branches($user_id);
        $user_option = $option; 
        $data['requested_url'] = $this->session->requested_end_menu_url;

        $curr_date      = date('d-m-Y');
        $curr_day       = substr($curr_date,0,2) ;
        $curr_month     = substr($curr_date,3,2) ; 
        $curr_year      = substr($curr_date,6,4) ;
        
        $selemode = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:null;

        $trans_rowno   = 50 ;
        $curr_yyyymm   = $curr_year.$curr_month ;
        
        $finyr_qry     = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray() ; 
        $month_qry     = $this->db->query("select month_descl,month_no from months order by month_serial")->getResultArray() ; 
        $daybook_qry   = $this->db->query("select daybook_desc,daybook_code from daybook_master where daybook_type = 'BB' order by daybook_desc ")->getResultArray() ;
        
        if ($this->request->getMethod() == 'post') {
            if ($selemode == 'Y') {
                $redv = 'readonly' ;  $disv = 'disabled' ; $disb = 'disabled' ; 
                $requested_url   = base_url($data['requested_url']);
                $db_code         = isset($_REQUEST['db_code'])?$_REQUEST['db_code']:null; 
                $daybook_code    = isset($_REQUEST['daybook_code'])?$_REQUEST['daybook_code']:null; 
                $branch_code     = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null; 
                $branch_name     = isset($_REQUEST['branch_name'])?$_REQUEST['branch_name']:null; 
                $fin_year        = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null; 
                $fin_month       = isset($_REQUEST['fin_month'])?$_REQUEST['fin_month']:null; 
                $curr_yyyymm     = isset($_REQUEST['curr_yyyymm'])?$_REQUEST['curr_yyyymm']:null; 
                $trans_yyyymm    = isset($_REQUEST['trans_yyyymm'])?$_REQUEST['trans_yyyymm']:null; 

                $trans_sql = "select a.trans_date,a.narration,if(a.dr_cr_ind = 'D',a.amount,'') debit_amt, if(a.dr_cr_ind = 'C',a.amount,'') credit_amt 
                                from bank_drcr a 
                                where a.bank_code   like '$daybook_code' 
                                and a.branch_code like '$branch_code'
                                and a.fin_year       = '$fin_year'
                                and date_format(a.trans_date,'%Y%m') = '$trans_yyyymm'
                                order by a.trans_date " ;
                $trans_qry = $this->db->query($trans_sql)->getResultArray();
                $trans_cnt = count($trans_qry);

                if(empty($trans_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($requested_url);
                }

                $params = [
                    "trans_rowno" => $trans_rowno,
                    "trans_rowno" => $trans_rowno,
                    "trans_rowno" => $trans_rowno,

                ];
            return view("pages/BankReconciliation/bank_transections_debited_credited", compact("finyr_qry", "daybook_qry", "month_qry", "trans_qry", "params", "curr_yyyymm", "selemode"));

            }
        } else {
            return view("pages/BankReconciliation/bank_transections_debited_credited", compact("finyr_qry", "daybook_qry", "month_qry", "curr_yyyymm", "selemode"));
        }	
    }

    /*********************************************************************************************/
    /***************************** Bank-Reconciliation [Reports] ***********************************/
    /*********************************************************************************************/
    public function bank_reconciliation_statement() {

        $data['requested_url'] = $this->session->requested_end_menu_url;
        $curr_date      = date('d-m-Y');
        $curr_day       = substr($curr_date,0,2) ;
        $curr_month     = substr($curr_date,3,2) ; 
        $curr_year      = substr($curr_date,6,4) ;

        $curr_yyyymm   = $curr_year.$curr_month ;
        $finyr_qry     = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray() ; 
        $month_qry     = $this->db->query("select month_descl,month_no from months order by month_serial")->getResultArray() ; 
        $daybook_qry   = $this->db->query("select daybook_desc,daybook_code from daybook_master where daybook_type = 'BB' order by daybook_desc ")->getResultArray() ;
        
        if($this->request->getMethod() == 'post') { 
            $report_desc   = 'BANK RECONCILIATION STATEMENT' ;
            $requested_url = base_url($data['requested_url']);
            $fin_year      = $_REQUEST['fin_year'] ;
            $fin_month     = $_REQUEST['fin_month'] ;
            $daybook_code  = $_REQUEST['daybook_code'] ;
            $statement_no  = $_REQUEST['statement_no'] ;
            $curr_yyyymm   = $_REQUEST['curr_yyyymm'] ;
            $curr_date     = $_REQUEST['curr_date'] ;
            $output_type   = $_REQUEST['output_type'] ;
            $opsyymm       = substr($fin_year,0,4).'04' ;
            $opsdate       = substr($fin_year,0,4).'-04-01' ;
            
            if ($fin_month < '04') { 
                $tyymm = substr($fin_year,5,4).$fin_month ; $trsdate = substr($fin_year,5,4).'-'.$fin_month.'-'.'01' ; 
            } else { 
                $tyymm = substr($fin_year,0,4).$fin_month ; $trsdate = substr($fin_year,0,4).'-'.$fin_month.'-'.'01' ; 
            }

            $opedate_qry   = $this->db->query("select date_sub('$trsdate', interval 1 day) edate ")->getRowArray() ;
            $opedate       = $opedate_qry['edate'] ;
            $opeyymm       = substr($opedate,0,4).substr($opedate,5,2) ;
            
            $period_desc   = substr($tyymm,0,4).'/'.$fin_month ;
            
            $lastdt_qry    = $this->db->query("select date_sub(date_add('$trsdate', interval 1 month), interval 1 day) ldate ")->getRowArray() ;
            $lstdate       = substr($lastdt_qry['ldate'],8,2).'-'.substr($lastdt_qry['ldate'],5,2).'-'.substr($lastdt_qry['ldate'],0,4);
            
            $daybook_qry   = $this->db->query("select daybook_desc from daybook_master where daybook_code = '$daybook_code' ")->getRowArray() ;
            $daybook_name  = $daybook_qry['daybook_desc'] ;
            
            $year_opbal_amt = $debit_opbal_amt = $credit_opbal_amt = $book_opbal_amt = $curr_debit_amt = $curr_credit_amt = 
            $book_clbal_amt = $unclr_debit_amt  = $unclr_credit_amt = $bank_debit_amt = $bank_credit_amt  = $bank_clbal_amt = 0;
            if ($statement_no == '1' || $statement_no == '2') {
                //----- Opening Balance (as per Book)
                $year_opbal_qry   = $this->db->query("select (sum(ifnull(a.amount_cr,0.00) - ifnull(a.amount_dr,0.00))) opbal from daybook_balance a where a.daybook_code = '$daybook_code' and ((a.yyyy_mm = '$opsyymm' and a.record_code = '01') or (a.yyyy_mm >= '$opsyymm' AND a.yyyy_mm <= '$opeyymm'))")->getRowArray() ;
                $year_opbal_amt   = $year_opbal_qry['opbal'] ;
            
                //----- Opening Debited/Credited Transactions by Bank (not in Book)
                $debit_opbal_qry  = $this->db->query("select sum(ifnull(a.amount,0.00)) dramt from bank_drcr a where a.bank_code = '$daybook_code' and a.trans_date between '$opsdate' and '$opedate' and dr_cr_ind = 'D' ")->getRowArray() ;
                $debit_opbal_amt  = $debit_opbal_qry['dramt'] ;
            
                $credit_opbal_qry = $this->db->query("select sum(ifnull(a.amount,0.00)) cramt from bank_drcr a where a.bank_code = '$daybook_code' and a.trans_date between '$opsdate' and '$opedate' and dr_cr_ind = 'C' ")->getRowArray() ;
                $credit_opbal_amt = $credit_opbal_qry['cramt'] ;
            
                //----- Net Opening Balance (as per Book)
                $book_opbal_amt = $year_opbal_amt + $credit_opbal_amt - $debit_opbal_amt ; 
            
                //----- Payments/Receipts (Current Month)
                $curr_debit_qry   = $this->db->query("select sum(ifnull(a.amount_dr,0.00)) dramt from daybook_balance a where a.daybook_code = '$daybook_code' and a.record_code = '02' and a.yyyy_mm = '$tyymm' ")->getRowArray() ;
                $curr_debit_amt   = $curr_debit_qry['dramt'] ;
            
                $curr_credit_qry  = $this->db->query("select sum(ifnull(a.amount_cr,0.00)) cramt from daybook_balance a where a.daybook_code = '$daybook_code' and a.record_code = '02' and a.yyyy_mm = '$tyymm' ")->getRowArray() ;
                $curr_credit_amt  = $curr_credit_qry['cramt'] ;
            
                //----- Net Closing Balance (as per Book)
                $book_clbal_amt = $book_opbal_amt + ($curr_credit_amt - $curr_debit_amt)  ; 
            
                //----- Uncleared Payments/Receipts (as per Bank)
                $unclr_debit_qry  = $this->db->query("select ifnull(sum(a.amount),0.00) dramt from bank_recon a where a.daybook_code = '$daybook_code' and date_format(a.doc_date,'%Y%m') <= '$tyymm' and a.dr_cr_ind = 'D' and (a.status_code = 'A' or  (a.status_code = 'B' and date_format(a.clear_date,'%Y%m') >'$tyymm'))")->getRowArray() ;
                $unclr_debit_amt  = $unclr_debit_qry['dramt'] ;
            
                $unclr_credit_qry = $this->db->query("select ifnull(sum(a.amount),0.00) cramt from bank_recon a where a.daybook_code = '$daybook_code' and date_format(a.doc_date,'%Y%m') <= '$tyymm' and a.dr_cr_ind = 'C' and (a.status_code = 'A' or  (a.status_code = 'B' and date_format(a.clear_date,'%Y%m') >'$tyymm'))")->getRowArray() ;
                $unclr_credit_amt = $unclr_credit_qry['cramt'] ;
            
                //----- Debited/Credited by Bank  (Current Month)
                $bank_debit_qry   = $this->db->query("select sum(ifnull(a.amount,0.00)) dramt from bank_drcr a where a.bank_code = '$daybook_code' and date_format(a.trans_date,'%Y%m') = '$tyymm' and dr_cr_ind = 'D' ")->getRowArray() ;
                $bank_debit_amt   = $bank_debit_qry['dramt'] ;
            
                $bank_credit_qry  = $this->db->query("select sum(ifnull(a.amount,0.00)) cramt from bank_drcr a where a.bank_code = '$daybook_code' and date_format(a.trans_date,'%Y%m') = '$tyymm' and dr_cr_ind = 'C' ")->getRowArray() ;
                $bank_credit_amt  = $bank_credit_qry['cramt'] ;
            
                //----- Net Closing Balance (as per Bank)
                $bank_clbal_amt = $book_clbal_amt + $unclr_debit_amt - $unclr_credit_amt + $bank_credit_amt - $bank_debit_amt ; 
            }  
            $unclr_credit_cnt = $unclr_debit_cnt = $bank_drcr_cnt = '';
            $unclr_credit_sql = $unclr_debit_sql = $bank_drcr_sql = [];
            if ($statement_no == '1' || $statement_no == '3') {
                $unclr_credit_sql = $this->db->query("select * from bank_recon a where a.daybook_code = '$daybook_code' and date_format(a.doc_date,'%Y%m') <= '$tyymm' and a.dr_cr_ind = 'C' and (a.status_code = 'A' or  (a.status_code = 'B' and date_format(a.clear_date,'%Y%m') > '$tyymm')) order by a.doc_date")->getResultArray() ;
                $unclr_credit_cnt = count($unclr_credit_sql);
            }     
            
            if ($statement_no == '1' || $statement_no == '4') {
                $unclr_debit_sql  = $this->db->query("select * from bank_recon a where a.daybook_code = '$daybook_code' and date_format(a.doc_date,'%Y%m') <= '$tyymm' and a.dr_cr_ind = 'D' and (a.status_code = 'A' or  (a.status_code = 'B' and date_format(a.clear_date,'%Y%m') > '$tyymm')) order by a.doc_date")->getResultArray() ;
                $unclr_debit_cnt  = count($unclr_debit_sql);
            }     
            
            if ($statement_no == '1' || $statement_no == '5') {
                $bank_drcr_sql    = $this->db->query("select * from bank_drcr a where a.bank_code = '$daybook_code' and date_format(a.trans_date,'%Y%m') = '$tyymm' order by a.trans_date, a.dr_cr_ind")->getResultArray() ;
                // echo '<pre>';print_r($bank_drcr_sql);die;
                $bank_drcr_cnt    = count($bank_drcr_sql);
            }  

            $params = [
                "statement_no" => $statement_no,
                "report_desc" => $report_desc,
                "daybook_name" => $daybook_name,
                "daybook_code" => $daybook_code,
                "lstdate" => $lstdate,
                "period_desc" => $period_desc,
                "book_opbal_amt" => $book_opbal_amt,
                "curr_credit_amt" => $curr_credit_amt,
                "curr_debit_amt" => $curr_debit_amt,
                "book_clbal_amt" => $book_clbal_amt,
                "unclr_debit_amt" => $unclr_debit_amt,
                "unclr_credit_amt" => $unclr_credit_amt,
                "bank_credit_amt" => $bank_credit_amt,
                "bank_debit_amt" => $bank_debit_amt,
                "bank_clbal_amt" => $bank_clbal_amt,
                "unclr_credit_cnt" => $unclr_credit_cnt,
                "unclr_debit_cnt" => $unclr_debit_cnt,
                "bank_drcr_cnt" => $bank_drcr_cnt,
                "requested_url" => $requested_url,
            ]; 

            if ($output_type == 'Pdf') {
                $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                $reportHTML = view("pages/BankReconciliation/bank_reconciliation_statement", compact("unclr_debit_sql", "bank_drcr_sql", "params", "unclr_credit_sql",  "report_type"));
                $dompdf->loadHtml($reportHTML);
                $dompdf->setPaper('A4', 'landscape'); // portrait
                $dompdf->render();
                $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;

            } else if($output_type == 'Excel') {
                $fileName = 'Bank-Reconciliation-Statement-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $rows = 1;
                if($params['statement_no'] == '1' || $params['statement_no'] == '2') {
                    $headings = ['Particulars', 'Credit', 'Debit'];
                    $column = 'A';
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
                    
                    $sheet->setCellValue('A' . $rows, 'Opening Balance as per Book');
                    $style = $sheet->getStyle('A'. $rows); $style->getFont()->setBold(true);
                    $sheet->setCellValue('B' . $rows, ($params['book_opbal_amt'] >= 0.00) ? number_format(abs($params['book_opbal_amt']), 2, '.', '') : '');
                    $sheet->setCellValue('C' . $rows, ($params['book_opbal_amt'] <  0.00) ? number_format(abs($params['book_opbal_amt']), 2, '.', '') : '');
                    $style = $sheet->getStyle('A' . $rows . ':C' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;

                    $sheet->setCellValue('A' . $rows, 'Add  : Receipts during the period');
                    $sheet->setCellValue('B' . $rows, ($params['curr_credit_amt'] > 0.00) ? number_format($params['curr_credit_amt'], 2, '.', '') : '');
                    $sheet->setCellValue('C' . $rows, '');
                    $style = $sheet->getStyle('A' . $rows . ':C' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;

                    $sheet->setCellValue('A' . $rows, 'Less : Payments during the period');
                    $sheet->setCellValue('B' . $rows, '');
                    $sheet->setCellValue('C' . $rows, ($params['curr_debit_amt'] > 0.00) ? number_format($params['curr_debit_amt'], 2, '.', '') : '');
                    $style = $sheet->getStyle('A' . $rows . ':C' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);  // Add borders
                    $rows++;

                    $sheet->setCellValue('A' . $rows, 'Closing Balance as per Book');
                    $style = $sheet->getStyle('A'. $rows); $style->getFont()->setBold(true);
                    $sheet->setCellValue('B' . $rows, ($params['book_clbal_amt'] > 0.00) ? number_format(abs($params['book_clbal_amt']), 2, '.', '') : '');
                    $sheet->setCellValue('C' . $rows, ($params['book_clbal_amt'] < 0.00) ? number_format(abs($params['book_clbal_amt']), 2, '.', '') : '');
                    $style = $sheet->getStyle('A' . $rows . ':C' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;

                    $sheet->setCellValue('A' . $rows, 'Add  : Cheques Issued not debited by Bank');
                    $sheet->setCellValue('B' . $rows, ($params['unclr_debit_amt'] > 0.00) ? number_format($params['unclr_debit_amt'], 2, '.', '') : '');
                    $sheet->setCellValue('C' . $rows, '');
                    $style = $sheet->getStyle('A' . $rows . ':C' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;

                    $sheet->setCellValue('A' . $rows, 'Less  : Cheques Deposited not credited by Bank');
                    $sheet->setCellValue('B' . $rows, '');
                    $sheet->setCellValue('C' . $rows, ($params['unclr_credit_amt'] > 0.00) ? number_format($params['unclr_credit_amt'], 2, '.', '') : '');
                    $style = $sheet->getStyle('A' . $rows . ':C' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;

                    $sheet->setCellValue('A' . $rows, 'Add  : Amount Credited by Bank not shown in Book');
                    $sheet->setCellValue('B' . $rows, ($params['bank_credit_amt'] > 0.00) ? number_format($params['bank_credit_amt'], 2, '.', '') : '');
                    $sheet->setCellValue('C' . $rows, '');
                    $style = $sheet->getStyle('A' . $rows . ':C' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $rows++;

                    $sheet->setCellValue('A' . $rows, 'Add : Amount Debited by Bank not shown in Book');
                    $sheet->setCellValue('B' . $rows, '');
                    $sheet->setCellValue('C' . $rows, ($params['bank_debit_amt'] > 0.00) ? number_format(($params['bank_debit_amt']), 2, '.', '') : '');
                    $style = $sheet->getStyle('A' . $rows . ':C' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);  // Add borders
                    $rows++;

                    $sheet->setCellValue('A' . $rows, 'Closing Balance as per Bank');
                    $style = $sheet->getStyle('A'. $rows); $style->getFont()->setBold(true);
                    $sheet->setCellValue('B' . $rows, ($params['bank_clbal_amt'] > 0.00) ? number_format(abs($params['bank_clbal_amt']), 2, '.', '') : '');
                    $sheet->setCellValue('C' . $rows, ($params['bank_clbal_amt'] < 0.00) ? number_format(abs($params['bank_clbal_amt']), 2, '.', '') : '');
                    $style = $sheet->getStyle('A' . $rows . ':C' . $rows);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);  // Add borders
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;
                }
                
                if($params['statement_no'] == '1' || $params['statement_no'] == '3') {
                    $headings = ['Date', 'Chq#', 'Chq Dt', 'Received From', 'Amount'];
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

                    $totamt = 0; 
                    $rowcnt = 1 ;
                    $report_row = isset($unclr_credit_sql[$rowcnt-1]) ? $unclr_credit_sql[$rowcnt-1] : '' ;   
                    $report_cnt = $params['unclr_credit_cnt'] ;
                    
                    while ($rowcnt <= $report_cnt) {
                        $pdocdt = $report_row['doc_date'] ;
                        while ($pdocdt == $report_row['doc_date'] && $rowcnt <= $report_cnt) {
                            $sheet->setCellValue('A' . $rows, date_conv($report_row['doc_date'],'-'));
                            $sheet->setCellValue('B' . $rows, $report_row['instrument_no']);
                            $sheet->setCellValue('C' . $rows, date_conv($report_row['instrument_dt'],'-'));
                            $sheet->setCellValue('D' . $rows, $report_row['payee_payer_name']);
                            $sheet->setCellValue('E' . $rows, $report_row['amount']);

                            $totamt = $totamt + $report_row['amount'] ;                   
                            $report_row = ($rowcnt < $report_cnt) ? $unclr_credit_sql[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ; 
                            
                             // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':E' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        }
                    }

                    $sheet->setCellValue('D' . $rows, 'TOTAL');
                    $sheet->setCellValue('E' . $rows, number_format($totamt,2,'.',''));
                    
                    // Apply Background Color to the current row
                    $style = $sheet->getStyle('A' . $rows . ':E' . $rows);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;
                }
                
                if($params['statement_no'] == '1' || $params['statement_no'] == '4') {
                    $headings = ['Date', 'Chq#', 'Chq Dt', 'Pay To', 'Amount'];
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

                    $totamt = 0; 
                    $rowcnt = 1 ;
                    $report_row = isset($unclr_debit_sql[$rowcnt-1]) ? $unclr_debit_sql[$rowcnt-1] : '' ;   
                    $report_cnt = $params['unclr_debit_cnt'] ;
                    
                    while ($rowcnt <= $report_cnt) {
                        $pdocdt = $report_row['doc_date'] ;
                        while ($pdocdt == $report_row['doc_date'] && $rowcnt <= $report_cnt) {
                            $sheet->setCellValue('A' . $rows, date_conv($report_row['doc_date'],'-'));
                            $sheet->setCellValue('B' . $rows, $report_row['instrument_no']);
                            $sheet->setCellValue('C' . $rows, date_conv($report_row['instrument_dt'],'-'));
                            $sheet->setCellValue('D' . $rows, $report_row['payee_payer_name']);
                            $sheet->setCellValue('E' . $rows, $report_row['amount']);

                            $totamt = $totamt + $report_row['amount'] ;                   
                            $report_row = ($rowcnt < $report_cnt) ? $unclr_debit_sql[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ; 
                            
                             // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':E' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        }
                    }

                    $sheet->setCellValue('D' . $rows, 'TOTAL');
                    $sheet->setCellValue('E' . $rows, number_format($totamt,2,'.',''));
                    
                    // Apply Background Color to the current row
                    $style = $sheet->getStyle('A' . $rows . ':E' . $rows);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;
                } 
                
                if($params['bank_drcr_cnt'] > 0 && ($params['statement_no'] == '1' || $params['statement_no'] == '5')) {
                    $headings = ['Date', 'Narration', 'Credit', 'Debit'];
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

                    $tdramt  = 0; 
                    $tcramt  = 0;  
                    $rowcnt = 1 ;
                    $report_row = isset($bank_drcr_sql[$rowcnt-1]) ? $bank_drcr_sql[$rowcnt-1] : '' ;  
                    $report_cnt = $params['bank_drcr_cnt'] ;
                    
                    while ($rowcnt <= $report_cnt) {
                        $pdocdt = $report_row['trans_date'] ;
                        while ($pdocdt == $report_row['trans_date'] && $rowcnt <= $report_cnt) {
                            $sheet->setCellValue('A' . $rows, date_conv($report_row['trans_date'],'-'));
                            $sheet->setCellValue('B' . $rows, $report_row['narration']);
                            $sheet->setCellValue('C' . $rows, ($report_row['dr_cr_ind'] == 'C') ? $report_row['amount'] : '' );
                            $sheet->setCellValue('D' . $rows, ($report_row['dr_cr_ind'] == 'D') ? $report_row['amount'] : '');

                            if($report_row['dr_cr_ind'] == 'D') { $tdramt = $tdramt + $report_row['amount'] ; } else { $tcramt = $tcramt + $report_row['amount'] ; }                   
                            $report_row = ($rowcnt < $report_cnt) ? $bank_drcr_sql[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ; 
                            
                             // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                        }
                    }

                    $sheet->setCellValue('B' . $rows, 'TOTAL');
                    $sheet->setCellValue('C' . $rows, ($tcramt > 0.00) ? number_format($tcramt,2,'.','') : '');
                    $sheet->setCellValue('D' . $rows, ($tdramt > 0.00) ? number_format($tdramt,2,'.','') : '');
                    
                    // Apply Background Color to the current row
                    $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;
                } 
                
                // $writer = new Xlsx($spreadsheet);
                // $writer->save($fileName);
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                //$writer = IOFactory::createWriter($spreadsheet, 'Xls');
                ob_end_clean();
                // header('Content-Type: application/vnd.ms-excel');
                // header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                // header('Expires: 0');
                // header('Cache-control: must-revalidate');
                // header('Pragma: public');
                // header('Content-Length:'.filesize($fileName));
                // flush();
                // readfile($fileName); 
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
                header('Expires: 0');
                header('Cache-control: must-revalidate');
                header('Pragma: public');
                
                // Directly output the file to the browser
                $writer->save('php://output');
exit;
                
            } else return view("pages/BankReconciliation/bank_reconciliation_statement", compact("unclr_debit_sql", "bank_drcr_sql", "params", "unclr_credit_sql"));

        } else {
            return view("pages/BankReconciliation/bank_reconciliation_statement",  compact("daybook_qry", "month_qry", "finyr_qry", "curr_month", "curr_yyyymm"));
        }  
    }
}
?>