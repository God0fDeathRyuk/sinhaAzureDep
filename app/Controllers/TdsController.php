<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TdsController extends BaseController
{
    public function __construct() {
        $db = $this->db = db_connect();
        $temp_db = $this->temp_db = db_connect('temp'); 
    }

    /*********************************************************************************************/
    /***************************** TDS [Transactions] ***********************************/
    /*********************************************************************************************/

    public function deposited_by_company() {
        $requested_url = session()->requested_end_menu_url;
        $user_id = session()->userId ;
    	$data = branches($user_id);
        
        $disp_heading = 'TDS Deposited By Company'; 
        $displayId = ['bank_help_id' => '4021'] ;
        $display_id = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $menu_id = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $screen_ref = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
        $selemode = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:null;
        $finsub = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        if ($user_option == 'Add') {$redk = '';
            $redv = '';
            $disv = '';
            $disb = '';
            $redve = '';
            $redokadd = '';
            $disview = '';
            $redLetter = 'disabled';}
        if ($user_option == 'Edit') {$redk = '';
            $redv = '';
            $disv = '';
            $disb = '';
            $redve = 'disabled';
            $redokadd = '';
            $disview = '';
            $redLetter = 'disabled';}
            if ($user_option == 'Select') {$redk = '';
                $redv = '';
                $disv = '';
                $disb = '';
                $redve = 'disabled';
                $redokadd = '';
                $disview = '';
                $redLetter = 'disabled';}
        if ($user_option == 'Delete') {$redk = '';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = '';
            $redve = '';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = 'disabled';}
        if ($user_option == 'View') {$redk = 'readonly';
            $redv = 'none';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = 'disabled';}
        if ($user_option == 'Copy') {$redk = 'readonly';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = 'disabled';}
        if ($user_option == 'letter') {$redk = 'readonly';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = '';}
        if ($this->request->getMethod() == "post") {
            //echo $requested_url;die;
            //edit update code//
            if($finsub!="fsub")
            {
               // echo 'www';die;
                $branch_code = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;
                $tds_deposit_date  = isset($_REQUEST['tds_deposit_date'])?$_REQUEST['tds_deposit_date']:null;
                $bank_code = isset($_REQUEST['bank_code'])?$_REQUEST['bank_code']:null;
                $bank_name = isset($_REQUEST['bank_name'])?$_REQUEST['bank_name']:null;
                $tds_challan_no = isset($_REQUEST['tds_challan_no'])?$_REQUEST['tds_challan_no']:null;
                $tds_cheque_no = isset($_REQUEST['tds_cheque_no'])?$_REQUEST['tds_cheque_no']:null;
                $tds_cheque_date = isset($_REQUEST['tds_cheque_date'])?$_REQUEST['tds_cheque_date']:null;
                $tds_cheque_bank = isset($_REQUEST['tds_cheque_bank'])?$_REQUEST['tds_cheque_bank']:null;
        
                $params = [
                    "disp_heading" => $disp_heading,
                    "bank_name" => $bank_name,
                    "tds_deposit_date" => $tds_deposit_date,
                    "bank_code" => $bank_code,
                    "tds_challan_no" => $tds_challan_no,
                    "tds_cheque_no" => $tds_cheque_no,
                    "tds_cheque_date" => $tds_cheque_date,
                    "tds_cheque_bank" => $tds_cheque_bank,
                ];
    
                if ($selemode != 'Y') {
                    $redv = ''; $disv = '';  $disc = '';  $disb_proc = '';  $disb_save = 'disabled'; $dis_exit = ''; $tdscert_cnt = 0; $tot_deposit_amount = 0;
        
                    if ($user_option != 'Add') {  
                        $params['tds_deposit_date'] = date_conv($tds_deposit_date); 
                        $params['tds_cheque_date']  = date_conv($tds_cheque_date); 
                    }
                    return view("pages/TDS/deposited_by_company", compact("params", "displayId", "data", "user_option","disview"));
        
                } else {
                    $tdscert_sql = null;
                    if ($user_option == 'Add') {
                        $redv = 'readonly'; $disv = 'disabled';  $disc = '';  $disb_proc = 'disabled';  $disb_save = ''; $dis_exit = ''; 
                        $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,b.narration,a.gross_amount,a.tax_amount,a.tds_deposit_ind
                                        FROM tds_certificate a, ledger_trans_hdr b
                                        WHERE a.branch_code = '$branch_code' and a.pay_rcpt_ind = 'P' and a.dr_cr_ind in ('D','C') and a.tds_deposit_ind = 'N' and a.ref_ledger_serial_no = b.serial_no
                                        ORDER by a.doc_date, a.serial_no";
        
                    } else if ($user_option == 'Edit') {
                        $redv = 'readonly'; $disv = 'disabled';  $disc = '';  $disb_proc = 'disabled';  $disb_save = ''; $dis_exit = '';
                        $tds_deposit_date_ymd = date_conv($tds_deposit_date) ; 
                        $tds_cheque_date_ymd  = date_conv($tds_cheque_date) ; 
                        $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,b.narration,a.gross_amount,a.tax_amount,a.tds_deposit_ind
                                        FROM tds_certificate a, ledger_trans_hdr b
                                        WHERE a.branch_code = '$branch_code' and a.pay_rcpt_ind = 'P' and a.dr_cr_ind = 'D' and a.ref_ledger_serial_no =  b.serial_no and ((a.tds_deposit_ind = 'N')
                                        OR (a.tds_deposit_ind = 'Y' and a.tds_deposit_date = '$tds_deposit_date_ymd' and a.bank_code = '$bank_code' and a.tds_challan_no = '$tds_challan_no' and a.tds_cheque_no = '$tds_cheque_no' and a.tds_cheque_date = '$tds_cheque_date_ymd'))
                                        ORDER by a.doc_date, a.serial_no";
                    
                    } else if ($user_option == 'View') {
                        $redv = 'readonly'; $disv = 'disabled';  $disc = 'disabled';  $disb_proc = 'disabled';  $disb_save = 'disabled'; $dis_exit = '';
                        $tds_deposit_date_ymd = date_conv($tds_deposit_date) ; 
                        $tds_cheque_date_ymd  = date_conv($tds_cheque_date) ; 
                        $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,b.narration,a.gross_amount,a.tax_amount,a.tds_deposit_ind
                                        FROM tds_certificate a, ledger_trans_hdr b
                                        WHERE a.branch_code = '$branch_code' and a.pay_rcpt_ind = 'P' and a.dr_cr_ind = 'D' and a.ref_ledger_serial_no =  b.serial_no and a.tds_deposit_ind = 'Y'
                                        and a.tds_deposit_date = '$tds_deposit_date_ymd' and a.bank_code = '$bank_code' and a.tds_challan_no = '$tds_challan_no' and a.tds_cheque_no = '$tds_cheque_no' and a.tds_cheque_date = '$tds_cheque_date_ymd'
                                        ORDER by a.doc_date, a.serial_no";
                    }
                    else if ($user_option == 'Delete') {
                        $redv = 'readonly'; $disv = 'disabled';  $disc = 'disabled';  $disb_proc = 'disabled';  $disb_save = 'disabled'; $dis_exit = '';
                        $tds_deposit_date_ymd = date_conv($tds_deposit_date) ; 
                        $tds_cheque_date_ymd  = date_conv($tds_cheque_date) ; 
                        $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,b.narration,a.gross_amount,a.tax_amount,a.tds_deposit_ind
                                        FROM tds_certificate a, ledger_trans_hdr b
                                        WHERE a.branch_code = '$branch_code' and a.pay_rcpt_ind = 'P' and a.dr_cr_ind = 'D' and a.ref_ledger_serial_no =  b.serial_no and a.tds_deposit_ind = 'Y'
                                        and a.tds_deposit_date = '$tds_deposit_date_ymd' and a.bank_code = '$bank_code' and a.tds_challan_no = '$tds_challan_no' and a.tds_cheque_no = '$tds_cheque_no' and a.tds_cheque_date = '$tds_cheque_date_ymd'
                                        ORDER by a.doc_date, a.serial_no";
                    }
        
                    $tdscert_qry = $this->db->query($tdscert_sql)->getResultArray(); 
                    $tdscert_cnt = count($tdscert_qry) ;
    
                    $tdscert = [
                        "tdscert_qry" => $tdscert_qry,
                        "tdscert_cnt" => $tdscert_cnt,
                    ];
                    return view("pages/TDS/deposited_by_company", compact("tdscert", "params", "displayId", "data", "user_option","disview"));
                }
            }
            if($finsub!="" || $finsub=="fsub")
            {
            $tdscert_cnt = isset($_REQUEST['tdscert_cnt'])?$_REQUEST['tdscert_cnt']:null;

            $tds_certificate_table = $this->db->table("tds_certificate");

            for($i = 1 ; $i <= $tdscert_cnt; $i++) {
                $depind = isset($_POST['depind'.$i]) ? $_POST['depind'.$i] : '';
                if($depind == 'Y') {
                    $array = array( 'tds_deposit_ind'      => 'Y',
                                    'tds_deposit_date'     => date_conv($_POST['tds_deposit_date']),
                                    'bank_code'            => $_POST['bank_code'],
                                    'tds_cheque_no'        => $_POST['tds_cheque_no'],
                                    'tds_cheque_date'      => date_conv($_POST['tds_cheque_date']),
                                    'tds_cheque_bank'      => $_POST['tds_cheque_bank'],
                                    'tds_challan_no'       => $_POST['tds_challan_no'],
                                );
                    $where = "serial_no = '".$_POST['serial_no'.$i]."'";
                    $tds_certificate_table->update($array, $where);
                } else {
                    $array = array( 'tds_deposit_ind'      => 'N',
                                    'tds_deposit_date'     => NULL,
                                    'bank_code'            => NULL,
                                    'tds_cheque_no'        => NULL,
                                    'tds_cheque_date'      => NULL,
                                    'tds_cheque_bank'      => NULL,
                                    'tds_challan_no'       => NULL,
                                );
                    $where = "serial_no = '".$_POST['serial_no'.$i]."'";
                    $tds_certificate_table->update($array, $where);
                }  
            }
            if ($user_option == 'Add') {
                    session()->setFlashdata('message', 'Record Added Successfully !!');
                    return redirect()->to($url);
            } else {
                session()->setFlashdata('message', 'Record Updated Successfully !!');
                return redirect()->to($url);
            }
        }
            //edit end

        } 
        // else {
        //     $branch_code = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;
        //     $tds_deposit_date  = isset($_REQUEST['tds_deposit_date'])?$_REQUEST['tds_deposit_date']:null;
        //     $bank_code = isset($_REQUEST['bank_code'])?$_REQUEST['bank_code']:null;
        //     $bank_name = isset($_REQUEST['bank_name'])?$_REQUEST['bank_name']:null;
        //     $tds_challan_no = isset($_REQUEST['tds_challan_no'])?$_REQUEST['tds_challan_no']:null;
        //     $tds_cheque_no = isset($_REQUEST['tds_cheque_no'])?$_REQUEST['tds_cheque_no']:null;
        //     $tds_cheque_date = isset($_REQUEST['tds_cheque_date'])?$_REQUEST['tds_cheque_date']:null;
        //     $tds_cheque_bank = isset($_REQUEST['tds_cheque_bank'])?$_REQUEST['tds_cheque_bank']:null;
    
        //     $params = [
        //         "disp_heading" => $disp_heading,
        //         "bank_name" => $bank_name,
        //         "tds_deposit_date" => $tds_deposit_date,
        //         "bank_code" => $bank_code,
        //         "tds_challan_no" => $tds_challan_no,
        //         "tds_cheque_no" => $tds_cheque_no,
        //         "tds_cheque_date" => $tds_cheque_date,
        //         "tds_cheque_bank" => $tds_cheque_bank,
        //     ];

        //     if ($selemode != 'Y') {
        //         $redv = ''; $disv = '';  $disc = '';  $disb_proc = '';  $disb_save = 'disabled'; $dis_exit = ''; $tdscert_cnt = 0; $tot_deposit_amount = 0;
    
        //         if ($user_option != 'Add') {  
        //             $params['tds_deposit_date'] = date_conv($tds_deposit_date); 
        //             $params['tds_cheque_date']  = date_conv($tds_cheque_date); 
        //         }
        //         return view("pages/TDS/deposited_by_company", compact("params", "displayId", "data", "user_option"));
    
        //     } else {
        //         $tdscert_sql = null;
        //         if ($user_option == 'Add') {
        //             $redv = 'readonly'; $disv = 'disabled';  $disc = '';  $disb_proc = 'disabled';  $disb_save = ''; $dis_exit = ''; 
        //             $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,b.narration,a.gross_amount,a.tax_amount,a.tds_deposit_ind
        //                             FROM tds_certificate a, ledger_trans_hdr b
        //                             WHERE a.branch_code = '$branch_code' and a.pay_rcpt_ind = 'P' and a.dr_cr_ind in ('D','C') and a.tds_deposit_ind = 'N' and a.ref_ledger_serial_no = b.serial_no
        //                             ORDER by a.doc_date, a.serial_no";
    
        //         } else if ($user_option == 'Edit') {
        //             $redv = 'readonly'; $disv = 'disabled';  $disc = '';  $disb_proc = 'disabled';  $disb_save = ''; $dis_exit = '';
        //             $tds_deposit_date_ymd = date_conv($tds_deposit_date) ; 
        //             $tds_cheque_date_ymd  = date_conv($tds_cheque_date) ; 
        //             $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,b.narration,a.gross_amount,a.tax_amount,a.tds_deposit_ind
        //                             FROM tds_certificate a, ledger_trans_hdr b
        //                             WHERE a.branch_code = '$branch_code' and a.pay_rcpt_ind = 'P' and a.dr_cr_ind = 'D' and a.ref_ledger_serial_no =  b.serial_no and ((a.tds_deposit_ind = 'N')
        //                             OR (a.tds_deposit_ind = 'Y' and a.tds_deposit_date = '$tds_deposit_date_ymd' and a.bank_code = '$bank_code' and a.tds_challan_no = '$tds_challan_no' and a.tds_cheque_no = '$tds_cheque_no' and a.tds_cheque_date = '$tds_cheque_date_ymd'))
        //                             ORDER by a.doc_date, a.serial_no";
                
        //         } else if ($user_option == 'View') {
        //             $redv = 'readonly'; $disv = 'disabled';  $disc = 'disabled';  $disb_proc = 'disabled';  $disb_save = 'disabled'; $dis_exit = '';
        //             $tds_deposit_date_ymd = date_conv($tds_deposit_date) ; 
        //             $tds_cheque_date_ymd  = date_conv($tds_cheque_date) ; 
        //             $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,b.narration,a.gross_amount,a.tax_amount,a.tds_deposit_ind
        //                             FROM tds_certificate a, ledger_trans_hdr b
        //                             WHERE a.branch_code = '$branch_code' and a.pay_rcpt_ind = 'P' and a.dr_cr_ind = 'D' and a.ref_ledger_serial_no =  b.serial_no and a.tds_deposit_ind = 'Y'
        //                             and a.tds_deposit_date = '$tds_deposit_date_ymd' and a.bank_code = '$bank_code' and a.tds_challan_no = '$tds_challan_no' and a.tds_cheque_no = '$tds_cheque_no' and a.tds_cheque_date = '$tds_cheque_date_ymd'
        //                             ORDER by a.doc_date, a.serial_no";
        //         }
    
        //         $tdscert_qry = $this->db->query($tdscert_sql)->getResultArray(); 
        //         $tdscert_cnt = count($tdscert_qry) ;

        //         $tdscert = [
        //             "tdscert_qry" => $tdscert_qry,
        //             "tdscert_cnt" => $tdscert_cnt,
        //         ];
        //         return view("pages/TDS/deposited_by_company", compact("tdscert", "params", "displayId", "data", "user_option"));
        //     }
        // }
    }

    public function received_from_client() {
        $requested_url = session()->requested_end_menu_url;
        $user_id = session()->userId ;
    	$data = branches($user_id);
        $display_id = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $menu_id = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $screen_ref = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
        $selemode = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:null;
        $disp_heading = 'TDS Received From Client'; 
        $displayId = ['client_help_id' => '4072'] ;
        $finsub = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        if ($user_option == 'Add') {$redk = '';
            $redv = '';
            $disv = '';
            $disb = '';
            $redve = '';
            $redokadd = '';
            $disview = '';
            $redLetter = 'disabled';}
        if ($user_option == 'Edit') {$redk = '';
            $redv = '';
            $disv = '';
            $disb = '';
            $redve = 'disabled';
            $redokadd = '';
            $disview = '';
            $redLetter = 'disabled';}
        if ($user_option == 'Select') {$redk = '';
                $redv = '';
                $disv = '';
                $disb = '';
                $redve = 'disabled';
                $redokadd = '';
                $disview = '';
                $redLetter = 'disabled';}
        if ($user_option == 'Delete') {$redk = '';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = '';
            $redve = '';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = 'disabled';}
        if ($user_option == 'View') {$redk = 'readonly';
            $redv = 'none';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = 'disabled';}
        if ($user_option == 'Copy') {$redk = 'readonly';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = 'disabled';}
        if ($user_option == 'letter') {$redk = 'readonly';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = '';}
            //echo $finsub;die;
        if ($this->request->getMethod() == "post") {
            if($finsub=="fsub")
            {
            $tds_certificate_table = $this->db->table("tds_certificate");

            $total_row = $tdscert_cnt = isset($_REQUEST['tdscert_cnt'])?$_REQUEST['tdscert_cnt']:null;
            $array = array (
                'branch_code' => $_POST['branch_code'], 
                'fin_year' => $_POST['fin_year'], 
                'tds_cert_no' => $_POST['tds_cert_no'],
                'tds_cert_date' => date_conv($_POST['tds_cert_date']),
            );

            for($i = 1; $i <= $total_row; $i++) {
                $array['doc_date'] = date_conv($_POST['doc_date'.$i]);
                $array['daybook_code'] = $_POST['daybook_code'.$i];
                $array['doc_no'] = $_POST['doc_no'.$i];
                $array['instrument_no'] = $_POST['instrument_no'.$i];
                $array['instrument_dt'] = $_POST['instrument_dt'.$i];
                $array['bank_name'] = $_POST['bank_name'.$i];
                $array['gross_amount'] = $_POST['gross_amount'.$i];
                $array['tax_amount'] = $_POST['tax_amount'.$i];
                $array['net_amount'] = $_POST['net_amount'.$i];
                $tds_certificate_table->update($array, "serial_no ='".$_POST['serial_no'.$i]."'");
            }

            for($i = 1 ; $i <= $tdscert_cnt; $i++) {
                $depind = isset($_POST['depind'.$i]) ? $_POST['depind'.$i] : '';
                if($depind == 'Y') {
                    $array = array( 'tds_cert_no' => $_POST['tds_cert_no'], 'tds_cert_date' => date_conv($_POST['tds_cert_date']), );
                    $where = "serial_no = '".$_POST['serial_no'.$i]."'";
                    $tds_certificate_table->update($array, $where);
                } else {
                    $array = array( 'tds_cert_no'   => NULL, 'tds_cert_date' => NULL, );
                    $where = "serial_no = '".$_POST['serial_no'.$i]."'";
                    $tds_certificate_table->update($array, $where);
                }  
            }
            if ($user_option == 'Add') {
                session()->setFlashdata('message', 'Record Added Successfully !!');
                return redirect()->to($url);
            } else {
                session()->setFlashdata('message', 'Record Updated Successfully !!');
                return redirect()->to($url);
            }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                
                $branch_code = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;
                $fin_year = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null;
                $client_code = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null;
                $client_name = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;
                $tds_cert_no = isset($_REQUEST['tds_cert_no'])?$_REQUEST['tds_cert_no']:null;
                $tds_cert_date = isset($_REQUEST['tds_cert_date'])?$_REQUEST['tds_cert_date']:null;
        
                $finyr_qry  = $this->db->query("select * from params where year_close_ind != 'Y' ")->getResultArray();
        
                $params = [
                    "disp_heading" => $disp_heading,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    "tds_cert_no" => $tds_cert_no,
                    "tds_cert_date" =>$tds_cert_date,
                    "finyr_qry" => $finyr_qry,
                ];
                
                if ($selemode != 'Y') {
                    $redv = ''; $disv = '';  $disc = '';  $disb_proc = '';  $disb_save = 'disabled'; $dis_exit = ''; $tdscert_cnt = 0 ;
                    if ($user_option != 'Add') $params['tds_cert_date'] = date_conv($tds_cert_date);
                    return view("pages/TDS/received_from_client", compact("params", "displayId", "data", "user_option"));
    
                } else {
                    if ($user_option == 'Add') {
                        $redv = 'readonly'; $disv = 'disabled';  $disc = '';  $disb_proc = 'disabled';  $disb_save = ''; $dis_exit = '';
                        $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,a.instrument_no,a.instrument_dt,a.bank_name,a.gross_amount,a.tax_amount,a.net_amount,a.tds_cert_no,a.tds_cert_date
                                        FROM tds_certificate a
                                        WHERE a.branch_code like '$branch_code' and a.fin_year like '$fin_year' and a.payee_payer_code like '$client_code' and a.pay_rcpt_ind = 'R' and a.dr_cr_ind = 'C' and a.payee_payer_type = 'C' and (a.tds_cert_no is null or a.tds_cert_no = '') 
                                        ORDER by a.doc_date, a.serial_no";
    
                    } else if ($user_option == 'Edit') {
                        $redv = 'readonly'; $disv = 'disabled';  $disc = '';  $disb_proc = 'disabled';  $disb_save = ''; $dis_exit = '';
                        $tds_cert_date_ymd = date_conv($tds_cert_date) ; 
                        $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,a.instrument_no,a.instrument_dt,a.bank_name,a.gross_amount,a.tax_amount,a.net_amount,a.tds_cert_no,a.tds_cert_date
                                        FROM tds_certificate a
                                        WHERE a.branch_code like '$branch_code' and a.fin_year like '$fin_year' and a.payee_payer_code  like '$client_code' and a.pay_rcpt_ind = 'R' and a.dr_cr_ind = 'C' and a.payee_payer_type = 'C' and ((a.tds_cert_no is null or a.tds_cert_no = '')
                                        OR (a.tds_cert_no = '$tds_cert_no' and a.tds_cert_date = '$tds_cert_date_ymd'))
                                        ORDER by a.doc_date, a.serial_no";
    
                    } else if ($user_option == 'View') {
                        $redv = 'readonly'; $disv = 'disabled';  $disc = 'disabled';  $disb_proc = 'disabled';  $disb_save = 'disabled'; $dis_exit = '';
                        $tds_cert_date_ymd = date_conv($tds_cert_date) ; 
                        $tdscert_sql = "SELECT a.serial_no,a.daybook_code,a.doc_date,a.doc_no,a.instrument_no,a.instrument_dt,a.bank_name,a.gross_amount,a.tax_amount,a.net_amount,a.tds_cert_no,a.tds_cert_date
                                        FROM tds_certificate a
                                        WHERE a.branch_code like '$branch_code' and a.fin_year like '$fin_year' and a.payee_payer_code  like '$client_code' and a.pay_rcpt_ind = 'R' and a.dr_cr_ind = 'C' and a.payee_payer_type = 'C' and a.tds_cert_no = '$tds_cert_no' and a.tds_cert_date = '$tds_cert_date_ymd'
                                        ORDER by a.doc_date, a.serial_no" ;
                    }
    
                    $tdscert_qry = $this->db->query($tdscert_sql)->getResultArray(); 
                    $tdscert_cnt = count($tdscert_qry) ;
                
                    $tdscert = [
                        "tdscert_qry" => $tdscert_qry,
                        "tdscert_cnt" => $tdscert_cnt,
                    ];
                    return view("pages/TDS/received_from_client", compact("tdscert", "params", "displayId", "data", "user_option"));
            }
        } 
        }
    }

    public function acknowledgement_no() {
        $requested_url = session()->requested_end_menu_url;
        $user_id = session()->userId ;
		$data = branches($user_id);
        $display_id = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $menu_id = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $screen_ref = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
        $serial_no = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;
        $finsub = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        if ($user_option == 'Add') {$redk = '';
            $redv = '';
            $disv = '';
            $disb = '';
            $redve = '';
            $redokadd = '';
            $disview = '';
            $redLetter = 'disabled';}
        if ($user_option == 'Edit') {$redk = '';
            $redv = '';
            $disv = '';
            $disb = '';
            $redve = 'disabled';
            $redokadd = '';
            $disview = '';
            $redLetter = 'disabled';}
        if ($user_option == 'Select') {$redk = '';
                $redv = '';
                $disv = '';
                $disb = '';
                $redve = 'disabled';
                $redokadd = '';
                $disview = '';
                $redLetter = 'disabled';}
        if ($user_option == 'Delete') {$redk = '';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = '';
            $redve = '';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = 'disabled';}
        if ($user_option == 'View') {$redk = 'readonly';
            $redv = 'none';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = 'disabled';}
        if ($user_option == 'Copy') {$redk = 'readonly';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = 'disabled';}
        if ($user_option == 'letter') {$redk = 'readonly';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = '';}
        if ($this->request->getMethod() == "post") {
            if($finsub=="fsub")
            {
            $branch_code = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;
            $fin_year = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null;
            $quarter_no = isset($_REQUEST['quarter_no'])?$_REQUEST['quarter_no']:null;
            $start_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null;
            $end_date = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null;
            $tds_return_no = isset($_REQUEST['tds_return_no'])?$_REQUEST['tds_return_no']:null;

            $start_date_ymd = date_conv($start_date,'-');
            $end_date_ymd   = date_conv($end_date,  '-');

            if ($user_option == 'Add') {
                $tdsretn_sql = "insert into tds_return_detail (branch_code,fin_year,quarter_no,start_date,end_date,tds_return_no) values ('$branch_code','$fin_year','$quarter_no','$start_date_ymd','$end_date_ymd','$tds_return_no') "  ;
                $this->db->query($tdsretn_sql);
                session()->setFlashdata('message', 'Record Added Successfully !!');
                return redirect()->to(base_url(session()->last_selected_end_menu));
            } else  if ($user_option != 'Add' && $user_option != 'Delete') {
                $tdsretn_sql = "update tds_return_detail set branch_code = '$branch_code', fin_year = '$fin_year', quarter_no = '$quarter_no', start_date = '$start_date_ymd', end_date = '$end_date_ymd', tds_return_no = '$tds_return_no' where serial_no = '$serial_no' " ;
                $this->db->query($tdsretn_sql) ;
                session()->setFlashdata('message', 'Record Updated Successfully !!');
                return redirect()->to($url);
            }
            else
            {
                $tdsretn_sql = "delete from tds_return_detail where serial_no = '$serial_no' " ;
                $this->db->query($tdsretn_sql) ;
                session()->setFlashdata('message', 'Record Deleted Successfully !!');
                return redirect()->to($url);
            }
        }
        if($finsub=="" || $finsub!="fsub")
        {
            $acknowledgement = [];
            $acknowledgement['finyr_qry'] = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray(); 
            $tdsretn_qry = $this->db->query("select * from tds_return_detail where serial_no = '$serial_no'")->getRowArray();

            if($tdsretn_qry != null) $acknowledgement['tdsretn_qry'] = $tdsretn_qry;
            if ($user_option == 'Add')  {$redk = '' ;         $redv = '' ;         $disv = '' ;         $disb = '' ; }
            if ($user_option == 'Edit') {$redk = 'readonly' ; $redv = '' ;         $disv = '' ;         $disb = '' ; }
            if ($user_option == 'View') {$redk = 'readonly' ; $redv = 'readonly' ; $disv = 'disabled' ; $disb = 'disabled' ; }
            
            return view("pages/TDS/acknowledgement_no", compact("acknowledgement", "user_option", "data", "disview"));
        }
        } 
    }

    /*********************************************************************************************/
    /***************************** TDS [Reports] ***********************************/
    /*********************************************************************************************/


    public function not_deposited() {

        $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

        $data = [];
        $data = branches("demo");
        $data['requested_url'] = session()->requested_end_menu_url;

        if($this->request->getMethod() == "post") {
            $report_desc   = 'TDS NOT YET DEPOSITED' ;
            //-------
            $ason_date     = $_REQUEST['ason_date'] ;
            $branch_code   = $_REQUEST['branch_code'] ;
            $fin_year      = $_REQUEST['fin_year'] ;
            $payee_type    = $_REQUEST['payee_type'] ;
            $output_type    = $_REQUEST['output_type'] ;
            $payee_code    = $_REQUEST['payee_code'] ;  if($payee_code == '') { $payee_code = '%' ; }
            $payee_name    = $_REQUEST['payee_name'] ;  if($payee_name == '') { $payee_name = '%' ; }

            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code'")->getRowArray();
            $branch_name   = $branch_qry['branch_name'] ;
            
            if($output_type == 'Report' || $output_type == 'Pdf') {
                $tds_stmt = "select a.*, b.doc_type 
                                from tds_certificate a, ledger_trans_hdr b 
                                where a.branch_code = '$branch_code' 
                                and a.fin_year = '$fin_year' 
                                and a.payee_payer_type like '$payee_type' 
                                and ifnull(a.payee_payer_code,'%') like ifnull('$payee_code','%') 
                                and a.payee_payer_name like '$payee_name' 
                                and a.pay_rcpt_ind = 'P' 
                                and ifnull(a.tds_deposit_ind,'N') = 'N' 
                                and a.ref_ledger_serial_no = b.serial_no 
                                and a.doc_date <= '".date_conv($ason_date.'-')."'
                            order by a.doc_date ";
                $tds_qry  = $this->db->query($tds_stmt)->getResultArray() ;
                $tds_cnt  = count($tds_qry);

                if(empty($tds_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "tds_cnt" => $tds_cnt,
                    "ason_date" => $ason_date,
                    "fin_year" => $fin_year,
                    "payee_type" => $payee_type,
                    "payee_name" => $payee_name,
                    "requested_url" => $data['requested_url'],
                ];
                
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/TDS/not_deposited", compact("tds_qry", "params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/TDS/not_deposited", compact("tds_qry", "params"));
                
            } else if($output_type == 'Excel'){ 
                $tds_stmt = "select a.*, b.doc_type from tds_certificate a, ledger_trans_hdr b 
                    where a.branch_code = '$branch_code' and a.fin_year = '$fin_year' 
                    and a.payee_payer_type like '$payee_type' 
                    and ifnull(a.payee_payer_code,'%') like ifnull('$payee_code','%') 
                    and a.payee_payer_name like '$payee_name' 
                    and a.pay_rcpt_ind = 'P' and ifnull(a.tds_deposit_ind,'N') = 'N' 
                    and a.ref_ledger_serial_no = b.serial_no 
                    and a.doc_date <= '".date_conv($ason_date.'-')."'order by a.doc_date ";

                $excels  = $this->db->query($tds_stmt)->getResultArray() ;
                $tds_cnt  = count($excels);

                try {
                    $excels[0];
                    if($tds_cnt == 0)  throw new \Exception('No Records Found !!');

                } catch (\Exception $e) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $fileName = 'TDS-NOT-DEPOSITED-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Define the headings
                $headings = ['Date', 'Doc No.', 'Type', 'DB', 'Payee', 'PAN', 'Gross', 'TDS'];

                // Loop through the headings and set the formatting
                $column = 'A';
                foreach ($headings as $heading) {
                    $cell = $column . '1';

                    // Set the cell value
                    $sheet->setCellValue($cell, $heading);

                    // Apply formatting
                    $style = $sheet->getStyle($cell);
                    $style->getFont()->setBold(true);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c8d5ef');
                    // Add borders
                    $style->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $style->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $style->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $style->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    // Move to the next column
                    ++$column;
                }
                
                $rows = 2;
                foreach ($excels as $excel){

                    $sheet->setCellValue('A' . $rows, date_conv($excel['doc_date'],'-'));
                    $sheet->setCellValue('B' . $rows, $excel['doc_no']);
                    $sheet->setCellValue('C' . $rows, $excel['doc_type']);
                    $sheet->setCellValue('D' . $rows, $excel['daybook_code']);
                    $sheet->setCellValue('E' . $rows, $excel['payee_payer_name']);
                    $sheet->setCellValue('F' . $rows, ($excel['pan_no'] != '') ? $excel['pan_no'] : '-');
                    $sheet->setCellValue('G' . $rows, $excel['gross_amount']);
                    $sheet->setCellValue('H' . $rows, $excel['tax_amount']);
                    
                    // Apply border to the current row
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
        } else {
            $displayId   = ['payee_help_id' => '4214'] ;
            
            $data['finyr_qry']  = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray(); 
            $data['ason_date'] = date('d-m-Y');
            
            return view("pages/TDS/not_deposited", compact("data", 'displayId'));
        }
    }

    public function payable_certificate_status () {

        $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

        $data = [];
        $data = branches("demo");

        if($this->request->getMethod() == "post") {
         	$requested_url = base_url($_SERVER['REQUEST_URI']);
            $report_desc   = 'TDS CERTIFICATE STATUS (PAYABLE)' ;
            $output_type    = $_REQUEST['output_type'] ;

            $ason_date     = $_REQUEST['ason_date'] ;
            $branch_code   = $_REQUEST['branch_code'] ;
            $fin_year      = $_REQUEST['fin_year'] ;
            $payee_type    = $_REQUEST['payee_type'] ;
            $payee_code    = $_REQUEST['payee_code'] ;  if($payee_code == '') { $payee_code = '%' ; }
            $payee_name    = $_REQUEST['payee_name'] ;  if($payee_name == '') { $payee_name = '%' ; }
            $cert_status   = $_REQUEST['cert_status'] ;
            $date = date('d-m-Y');
            
            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0];
            $branch_name   = $branch_qry['branch_name'] ;
            
            if($output_type == 'Report' || $output_type == 'Pdf') {
                //-------------------------------------------------------------
                if ($cert_status == 'A') 
                {
                    $cert_type    = 'ALL' ;
                    $tdscert_qry  = $this->db->query("select a.*, b.doc_type from tds_certificate a left outer join ledger_trans_hdr b on b.serial_no = a.ref_ledger_serial_no where a.branch_code = '$branch_code' and a.fin_year = '$fin_year' and a.payee_payer_type like '$payee_type' and ifnull(a.payee_payer_code,'%') like ifnull('$payee_code','%') and a.payee_payer_name like '$payee_name' and a.pay_rcpt_ind = 'P' and a.tds_deposit_ind = 'Y' order by a.doc_date ")->getResultArray();
                }  
                else if ($cert_status == 'Y') 
                {
                    $cert_type    = 'ISSUED' ;
                    $tdscert_qry  = $this->db->query("select a.*, b.doc_type from tds_certificate a left outer join ledger_trans_hdr b on b.serial_no = a.ref_ledger_serial_no where a.branch_code = '$branch_code' and a.fin_year = '$fin_year' and a.payee_payer_type like '$payee_type' and ifnull(a.payee_payer_code,'%') like ifnull('$payee_code','%') and a.payee_payer_name like '$payee_name' and a.pay_rcpt_ind = 'P' and a.tds_deposit_ind = 'Y' and a.tds_cert_no != '' order by a.doc_date ")->getResultArray();
                }  
                else if ($cert_status == 'N') 
                {
                    $cert_type    = 'NOT ISSUED' ;
                    $tdscert_qry  = $this->db->query("select a.*, b.doc_type from tds_certificate a left outer join ledger_trans_hdr b on b.serial_no = a.ref_ledger_serial_no where a.branch_code = '$branch_code' and a.fin_year = '$fin_year' and a.payee_payer_type like '$payee_type' and ifnull(a.payee_payer_code,'%') like ifnull('$payee_code','%') and a.payee_payer_name like '$payee_name' and a.pay_rcpt_ind = 'P' and a.tds_deposit_ind = 'Y' and ((ifnull(a.tds_cert_no,'X') = 'X') or (a.tds_cert_no = '')) order by a.doc_date ")->getResultArray();
                }  
                
                $tdscert_cnt  = count($tdscert_qry);
                try {
                    $tdscert_qry[0];
                    if($tdscert_cnt == 0)  throw new \Exception('No Records Found !!');
    
                } catch (\Exception $e) {
                    session()->setFlashdata('message', 'No Records Found !!');
                   // return redirect()->to($this->requested_url());
                    return redirect()->to($this->requested_url());
                }
                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                    "fin_year" => $fin_year,
                    "tdscert_cnt" => $tdscert_cnt,
                    "ason_date" => $ason_date,
                    "payee_code" => $payee_code,
                    "payee_name" => $payee_name,
                    "cert_type" => $cert_type,
                ];
                
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/TDS/payable_certificate_status", compact("tdscert_qry", "params", "report_type"));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/TDS/payable_certificate_status", compact("tdscert_qry", "params"));
            }

        } else {
            $displayId   = ['payee_help_id' => '4214'];
            $data['finyr_qry']  = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray(); 
            $data['ason_date'] = date('d-m-Y');
            
            // echo "<pre>"; print_r($data); die;
            return view("pages/TDS/payable_certificate_status", compact("data", "displayId"));
        }
    }

    public function certificate_fresh () {
        $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
        
        if($this->request->getMethod() == "post") {

            $branch_code    = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null ;
            $fin_year       = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null ;
            $quarter_no     = isset($_REQUEST['quarter_no'])?$_REQUEST['quarter_no']:null ;
            $start_date     = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null ;
            $start_date_ymd = date_conv($start_date,'-');
            $end_date       = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null ;
            $end_date_ymd   = date_conv($end_date,'-');
            $tds_return_no  = isset($_REQUEST['tds_return_no'])?$_REQUEST['tds_return_no']:null ;
            $payee_type     = isset($_REQUEST['payee_type'])?$_REQUEST['payee_type']:null ;
            $payee_code     = isset($_REQUEST['payee_code'])?$_REQUEST['payee_code']:null ;
            if($payee_code == '') { $payee_code = '%' ; } 
            $payee_name     = isset($_REQUEST['payee_name'])?$_REQUEST['payee_name']:null ;
            if($payee_name == '') { $payee_name = '%' ; } 
            $tds_cert_date  = isset($_REQUEST['tds_cert_date'])?$_REQUEST['tds_cert_date']:null ;
            $tds_cert_date_ymd = date_conv($tds_cert_date,'-');
            $sign_by_name   = isset($_REQUEST['sign_by_name'])?$_REQUEST['sign_by_name']:null ;
            $sign_by_desg   = isset($_REQUEST['sign_by_desg'])?$_REQUEST['sign_by_desg']:null ;
            $sign_by_row    = isset($_REQUEST['sign_by_row'])?$_REQUEST['sign_by_row']:null ;
            $view_print_ind = isset($_REQUEST['view_print_ind'])?$_REQUEST['view_print_ind']:null ;
            $tds_cert_no    = isset($_REQUEST['tds_cert_no'])?$_REQUEST['tds_cert_no']:null ;
            $duplicate_ind  = isset($_REQUEST['duplicate_ind'])?$_REQUEST['duplicate_ind']:null ;
            $short_fin_year = substr($fin_year,2,2).substr($fin_year,7,2) ;

            //-------
            $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getRowArray();
            $user_id        = session()->userId ;
            $curr_time      = $logdt_qry['current_time'];
            $curr_date      = $logdt_qry['current_dmydate'];
            $curr_day       = substr($curr_date,0,2) ;
            $curr_month     = substr($curr_date,3,2) ; 
            $curr_year      = substr($curr_date,6,4) ;
            $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);
            $mytable        = $temp_id."_tdsprint" ;
            $this->temp_db->query("drop table $mytable ") ;
            $my_sql1        = "create table $mytable 
                        ( payee_type varchar(01), payee_code varchar(06), payee_name varchar(50), tds_challan_no varchar(15), tds_deposit_date date, tds_bank_code varchar(04), tds_bank_name varchar(50), tds_cheque_no varchar(10), tds_return_no varchar(15), nature_of_service varchar(30), tds_cert_no varchar(10), tds_cert_date date,
                        fin_year varchar(09), daybook_code varchar(02), doc_no varchar(10), doc_date date, dr_cr_ind varchar(01), tax_code varchar(04), tax_percent double(7,3), gross_amount double(12,2), tax_amount double(12,2),
                        payee_adr1 varchar(50), payee_adr2 varchar(50), payee_adr3 varchar(50), payee_adr4 varchar(50), payee_city varchar(30),  payee_pin varchar(10), payee_panno varchar(50), tds_bank_bsr_code varchar(20), serial_no int(8), backlog_counsel_fee double(12,2)  
                        ) " ;
            $this->temp_db->query($my_sql1)->getResultArray() ;

            $branch_qry    = $this->db->query("select a.*,b.company_name from branch_master a, company_master b where a.branch_code = '$branch_code' and a.company_code = b.company_code ")->getResultArray()[0];
            $company_code  = $branch_qry['company_code'] ;
            $company_name  = $branch_qry['company_name'] ;
            $branch_name   = $branch_qry['branch_name'] ;
            $branch_addr1  = $branch_qry['address_line_1'] ; 
            $branch_addr2  = $branch_qry['address_line_2'] ;   if($branch_addr2 == '') { $branch_addr2 = '&nbsp;' ; }
            $branch_addr3  = $branch_qry['address_line_3'] ;   if($branch_addr3 == '') { $branch_addr3 = '&nbsp;' ; }
            $branch_addr4  = $branch_qry['address_line_4'] ;   if($branch_addr4 == '') { $branch_addr4 = '&nbsp;' ; }
            $branch_city   = $branch_qry['city'] ; 
            $branch_pin    = $branch_qry['pin_code'] ; 
            $branch_panno  = $branch_qry['pan_no'] ; 
            $branch_tanno  = $branch_qry['tan_no'] ; 
            $comp_name     = $company_name ;
            
            //--- City & Pin
            if ($branch_city != '') 
            { 
                if($branch_pin  != '') { $branch_citypin = $branch_city.'-'.$branch_pin ; } else { $branch_citypin = $branch_city ; } 
            } 	 
            else
            { 
                if($branch_pin  != '') { $branch_citypin = 'PIN - '.$branch_pin ; } else { $branch_citypin = '' ; } 
            } 	 

            //-------
            if($duplicate_ind != 'Y')
            {
                if ($view_print_ind == 'P')
                {
                $tdscert_sql = "select payee_payer_type,payee_payer_code,serial_no 
                                    from tds_certificate 
                                where branch_code             = '$branch_code'
                                    and fin_year                = '$fin_year'
                                    and doc_date          between '$start_date_ymd' and '$end_date_ymd'
                                    and payee_payer_type        = '$payee_type' 
                                    and payee_payer_code     like '$payee_code' 
                                    and payee_payer_name     like '$payee_name' 
                                    and tds_deposit_ind         = 'Y'
                                    and (ifnull(tds_cert_no,'X') = 'X' or tds_cert_no = '')
                                    order by payee_payer_type,payee_payer_code,serial_no " ;
                $tdscert_row = $this->db->query($tdscert_sql)->getResultArray() ;
                $tdscert_cnt = count($tdscert_row) ;
                                    //
                $i = 1 ;
                $tdscert_from = 'X' ; 
                $tdscert_to   = 'X' ;
                foreach ($tdscert_row as $key => $tdscert_rows) 
                {
                    $cert_no   = $short_fin_year.'/'.str_pad(get_last_doc_no($fin_year,$branch_code,'TD','00','X')->getResultArray(),5,'0',STR_PAD_LEFT) ;
                    $cert_date = $tds_cert_date_ymd ;
                    //
                    if ($i==1) { $tdscert_from = $cert_no ; $tdscert_to = $cert_no ; } else { $tdscert_to = $cert_no ; } 
                    //
                    $ppayee_type = $tdscert_rows['payee_payer_type']  ;
                    $ppayee_code = $tdscert_rows['payee_payer_code']  ;
                    while ($ppayee_type == $tdscert_rows['payee_payer_type'] && $ppayee_code == $tdscert_rows['payee_payer_code'] && $i <= $tdscert_cnt)		
                    { 
                    $myupdt_sql = "update tds_certificate set tds_cert_no = '$cert_no', tds_cert_date = '$cert_date', tds_return_no = '$tds_return_no' where serial_no = '$tdscert_rows[serial_no]' " ;
                    $this->db->query($myupdt_sql)->getResultArray() ;
                    //
                    //$tdscert_rows = mysql_fetch_array($tdscert_qry)  ;
                    //$i = $i + 1 ;
                    }
                }
                //
                
                $myinsert_sql = "insert into $mytable 
                                    (payee_type,payee_code,payee_name,tds_challan_no,tds_deposit_date,tds_bank_code,tds_bank_name,tds_cheque_no,tds_return_no,nature_of_service,tds_cert_no,tds_cert_date,fin_year,daybook_code,doc_no,doc_date,dr_cr_ind,tax_code,tax_percent,gross_amount,tax_amount,payee_adr1,payee_adr2,payee_adr3,payee_adr4,payee_city,payee_pin,payee_panno,tds_bank_bsr_code,serial_no,backlog_counsel_fee)
                                select a.payee_payer_type,a.payee_payer_code,a.payee_payer_name,a.tds_challan_no,a.tds_deposit_date,a.bank_code,'',a.tds_cheque_no,'','',a.tds_cert_no,a.tds_cert_date,a.fin_year,a.daybook_code,a.doc_no,a.doc_date,a.dr_cr_ind,a.tax_code,'',a.gross_amount,a.tax_amount,'','','','','','','','',a.serial_no,b.backlog_counsel_fee
                                    from tds_certificate a left outer join voucher_header b on b.ref_ledger_serial_no = a.ref_ledger_serial_no 
                                    where tds_cert_no between '$tdscert_from' and '$tdscert_to' " ;
                $this->temp_db->query($myinsert_sql);  
                }   
                else
                {
                $myinsert_sql = "insert into $mytable 
                (payee_type,payee_code,payee_name,tds_challan_no,tds_deposit_date,tds_bank_code,tds_bank_name,tds_cheque_no,tds_return_no,nature_of_service,tds_cert_no,tds_cert_date,fin_year,daybook_code,doc_no,doc_date,dr_cr_ind,tax_code,tax_percent,gross_amount,tax_amount,payee_adr1,payee_adr2,payee_adr3,payee_adr4,payee_city,payee_pin,payee_panno,tds_bank_bsr_code,serial_no,backlog_counsel_fee)
                select a.payee_payer_type,a.payee_payer_code,a.payee_payer_name,a.tds_challan_no,a.tds_deposit_date,a.bank_code,'',a.tds_cheque_no,'','',a.tds_cert_no,a.tds_cert_date,a.fin_year,a.daybook_code,a.doc_no,a.doc_date,a.dr_cr_ind,a.tax_code,'',a.gross_amount,a.tax_amount,'','','','','','','','',a.serial_no, b.backlog_counsel_fee
                                    from tds_certificate a 
                                    left outer join voucher_header b on b.ref_ledger_serial_no = a.ref_ledger_serial_no
                                    where a.branch_code             = '$branch_code'
                                    and a.fin_year                = '$fin_year'
                                    and a.doc_date          between '$start_date_ymd' and '$end_date_ymd'
                                    and a.payee_payer_type        = '$payee_type' 
                                    and a.payee_payer_code     like '$payee_code' 
                                    and a.payee_payer_name     like '$payee_name' 
                                    and a.tds_deposit_ind         = 'Y'
                                    and (ifnull(a.tds_cert_no,'X') = 'X' or a.tds_cert_no = '')
                                    order by a.payee_payer_type,a.payee_payer_code,a.serial_no " ;
                $this->temp_db->query($myinsert_sql);
                }
            }
            
            else
            {
                $myinsert_sql = "insert into $mytable 
                                    (payee_type,payee_code,payee_name,tds_challan_no,tds_deposit_date,tds_bank_code,tds_bank_name,tds_cheque_no,tds_return_no,nature_of_service,tds_cert_no,tds_cert_date,fin_year,daybook_code,doc_no,doc_date,dr_cr_ind,tax_code,tax_percent,gross_amount,tax_amount,payee_adr1,payee_adr2,payee_adr3,payee_adr4,payee_city,payee_pin,payee_panno,tds_bank_bsr_code,serial_no,backlog_counsel_fee)
                                select a.payee_payer_type,a.payee_payer_code,a.payee_payer_name,a.tds_challan_no,a.tds_deposit_date,a.bank_code,'',a.tds_cheque_no,'','',a.tds_cert_no,a.tds_cert_date,a.fin_year,a.daybook_code,a.doc_no,a.doc_date,a.dr_cr_ind,a.tax_code,'',a.gross_amount,a.tax_amount,'','','','','','','','',a.serial_no,b.backlog_counsel_fee
                                    from tds_certificate a left outer join voucher_header b on b.ref_ledger_serial_no = a.ref_ledger_serial_no 
                                    where tds_cert_no = '$tds_cert_no' " ;
                $this->temp_db->query($myinsert_sql);  
            }
            //-------

            // echo "<pre>"; print_r($tdscert_qry); die;

            $tdscert_sql = "select * from $mytable order by payee_type,payee_code,doc_date " ;
            $tdscert_row = $this->db->query($tdscert_sql)->getResultArray() ;
            $tdscert_cnt = count($tdscert_row) ;
            $i = 1 ;
            foreach ($tdscert_row as $key => $tdscert_rows) 
            {
                $bank_qry          = $this->db->query("select bank_name, bsr_code from bank_master where bank_code = '$tdscert_rows[tds_bank_code]' ")->getRowArray() ;
                $tds_bank_name     = $bank_qry['bank_name'] ;	 
                $tds_bank_bsr_code = $bank_qry['bsr_code'] ;	 
                //
                $tax_qry           = $this->db->query("select tax_percent from tax_rate where fin_year = '$tdscert_rows[fin_year]' and tax_code = '$tdscert_rows[tax_code]' ")->getRowArray() ;
                $tax_percent       = $tax_qry['tax_percent'] ;	 
                //
                if ($tdscert_rows['payee_type'] == 'A' || $tdscert_rows['payee_type'] == 'T' || $tdscert_rows['payee_type'] == 'K')
                {
                $nature_qry  = $this->db->query("select code_desc from code_master where type_code = '010' and code_code = 'C' ")->getRowArray() ;
                $nature_of_service = $nature_qry['code_desc'] ; 
                }
                else 
                {
                $nature_qry  = $this->db->query("select code_desc from code_master where type_code = '010' and code_code = '$tdscert_rows[payee_type]' ")->getRowArray() ;
                $nature_of_service = $nature_qry['code_desc'] ; 
                }
                //
                if ($tdscert_rows['payee_type'] == 'C' || $tdscert_rows['payee_type'] == 'A' || $tdscert_rows['payee_type'] == 'T' || $tdscert_rows['payee_type'] == 'K')
                {
                $party_qry   = $this->db->query("select * from associate_master where associate_code = '$tdscert_rows[payee_code]' ")->getRowArray() ;
                $party_addr1 = $party_qry['address_line_1'] ;
                $party_addr2 = $party_qry['address_line_2'] ;
                $party_addr3 = $party_qry['address_line_3'] ;
                $party_addr4 = $party_qry['address_line_4'] ;
                $party_city  = $party_qry['city'] ;
                $party_pin   = $party_qry['pin_code'] ;
                $party_panno = $party_qry['pan_no'] ;
                }
                else if ($tdscert_rows['payee_type'] == 'E')
                {
                $party_qry   = $this->db->query("select * from empmas where employee_id = '$tdscert_rows[payee_code]' ")->getRowArray() ;
                $party_addr1 = $party_qry['employee_address1'] ;
                $party_addr2 = $party_qry['employee_address2'] ;
                $party_addr3 = $party_qry['employee_address3'] ;
                $party_addr4 = '' ;
                $party_city  = $party_qry['employee_city'] ;
                $party_pin   = $party_qry['employee_pin'] ;
                $party_panno = $party_qry['employee_pan_no'] ;
                }
                else if ($tdscert_rows['payee_type'] == 'S')
                {
                $party_qry   = $this->db->query("select * from supplier_master where supplier_code = '$tdscert_rows[payee_code]' ")->getRowArray() ;
                $party_addr1 = $party_qry['address_line_1'] ;
                $party_addr2 = $party_qry['address_line_2'] ;
                $party_addr3 = $party_qry['address_line_3'] ;
                $party_addr4 = $party_qry['address_line_4'] ;
                $party_city  = $party_qry['city'] ;
                $party_pin   = $party_qry['pin_code'] ;
                $party_panno = $party_qry['pan_no'] ;
                }
                else
                {
                $party_addr1 = '' ;
                $party_addr2 = '' ;
                $party_addr3 = '' ;
                $party_addr4 = '' ;
                $party_city  = '' ;
                $party_pin   = '' ;
                $party_panno = '' ;
                }
                //
                $myupdate_sql = "update $mytable set tax_percent = '$tax_percent', tds_bank_name = '$tds_bank_name', tds_bank_bsr_code = '$tds_bank_bsr_code', tds_return_no = '$tds_return_no', nature_of_service = '$nature_of_service', payee_adr1 = '$party_addr1', payee_adr2 = '$party_addr2', payee_adr3 = '$party_addr3', payee_adr4 = '$party_addr4', payee_city = '$party_city', payee_pin = '$party_pin', payee_panno = '$party_panno' where serial_no = '$tdscert_rows[serial_no]' " ;
                $this->temp_db->query($myupdate_sql) ;
                //	 
                // $tdscert_row = mysql_fetch_array($tdscert_qry)  ;
                // $i = $i + 1 ;
            }
            //-------
            $tdscert_qry = $this->db->query("select * from $mytable order by tds_cert_no, payee_type,payee_code,doc_date")->getResultArray() ;
            //$tdscert_qry = $this->db->query($tdscert_sql)->getResultArray() ;
            $tdscert_cnt = count($tdscert_qry) ;

            try {
                $tdscert_qry[0];
                if($tdscert_cnt == 0)  throw new \Exception('No Records Found !!');

            } catch (\Exception $e) {
                session()->setFlashdata('message', 'No Records Found !!');
                return redirect()->to($this->requested_url());
            }

            $params = [
                "tdscert_cnt" => $tdscert_cnt,
                "company_name" => $company_name,
                "quarter_no" => $quarter_no,
                "branch_tanno" => $branch_tanno,
                "branch_panno" => $branch_panno,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "sign_by_name" => $sign_by_name,
                "tds_cert_date" => $tds_cert_date,
                "sign_by_desg" => $sign_by_desg,

            ];
           return view("pages/TDS/certificate_fresh", compact("data", "tdscert_qry", "params"));

        } else {
            $displayId   = ['payee_help_id' => '4214', 'signed_help_id' => '4218'];
            $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
            $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
            $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
            $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
            $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
            $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
            $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
            $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
            $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
            $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
            $data = [];

            $data = branches("demo");
            $data['finyr_qry']  = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray(); 
            $data['ason_date'] = date('d-m-Y');
            
            // echo "<pre>"; print_r($data); die;
            return view("pages/TDS/certificate_fresh", compact("data", "displayId"));
        }
    }

    public function receivable_certificate_status () {
        $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
        
        if($this->request->getMethod() == "post") {
            $report_desc   = 'TDS CERTIFICATE STATUS (RECEIVABLE)' ;
            $output_type    = $_REQUEST['output_type'] ;
            $ason_date     = $_REQUEST['ason_date'] ;
            $branch_code   = $_REQUEST['branch_code'] ;
            $fin_year      = $_REQUEST['fin_year'] ;
            $payee_type    = $_REQUEST['payee_type'] ; 
            $payee_code    = $_REQUEST['payee_code'] ;  if($payee_code == '') { $payee_code = '%' ; }
            $payee_name    = $_REQUEST['payee_name'] ;  $payee_name = str_replace("``","&",$payee_name); $payee_name = str_replace("`","'",$payee_name);  if($payee_name == '') { $payee_name = '%' ; }
            $cert_status   = $_REQUEST['cert_status'] ;
            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0];
            $branch_name   = $branch_qry['branch_name'] ;
            
            if($output_type == 'Report' || $output_type == 'Pdf') {
                if ($cert_status == 'A') {
                    $cert_type    = 'ALL' ;
                    $tdscert_stmt =  "select a.*, b.doc_type 
                                        from tds_certificate a 
                                                left outer join ledger_trans_hdr b on b.serial_no = a.ref_ledger_serial_no 
                                        where a.branch_code         = '$branch_code' 
                                        and a.fin_year             = '$fin_year' 
                                        and a.payee_payer_type  like '$payee_type' 
                                        and ifnull(a.payee_payer_code,'%')  like '$payee_code' 
                                        and ifnull(a.payee_payer_name,'%')  like '$payee_name' 
                                        and a.pay_rcpt_ind         = 'R' 
                                    order by a.payee_payer_name,a.doc_date "; 
                    $tdscert_qry  = $this->db->query($tdscert_stmt)->getResultArray() ;
                } else if ($cert_status == 'Y') {
                    $cert_type    = 'RECEIVED' ;
                    $tdscert_stmt = "select a.*, b.doc_type 
                                        from tds_certificate a left outer join ledger_trans_hdr b on b.serial_no = a.ref_ledger_serial_no 
                                    where a.branch_code         = '$branch_code' 
                                        and a.fin_year            = '$fin_year' 
                                        and a.payee_payer_type  like '$payee_type' 
                                        and ifnull(a.payee_payer_code,'%')  like '$payee_code' 
                                        and ifnull(a.payee_payer_name,'%')  like '$payee_name' 
                                        and a.pay_rcpt_ind         = 'R' 
                                        and (a.tds_cert_no != '' and a.tds_cert_no is not null)
                                    order by a.payee_payer_name,a.doc_date ";
                    $tdscert_qry  = $this->db->query($tdscert_stmt)->getResultArray() ;
                } else if ($cert_status == 'N') {
                    $cert_type    = 'NOT RECEIVED' ;
                    $tdscert_stmt = "select a.*, b.doc_type 
                                        from tds_certificate a left outer join ledger_trans_hdr b on b.serial_no = a.ref_ledger_serial_no 
                                    where a.branch_code         = '$branch_code' 
                                        and a.fin_year            = '$fin_year' 
                                        and a.payee_payer_type  like '$payee_type' 
                                        and ifnull(a.payee_payer_code,'%')  like '$payee_code' 
                                        and ifnull(a.payee_payer_name,'%')  like '$payee_name' 
                                        and a.pay_rcpt_ind         = 'R' 
                                        and (a.tds_cert_no = '' or a.tds_cert_no is null)
                                    order by a.payee_payer_name,a.doc_date " ;
                    $tdscert_qry  = $this->db->query($tdscert_stmt)->getResultArray() ;
                }  
                $tdscert_cnt  = count($tdscert_qry);
                $date = date('d-m-Y');
                try {
                    $tdscert_qry[0];
                    if($tdscert_cnt == 0)  throw new \Exception('No Records Found !!');

                } catch (\Exception $e) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                    "fin_year" => $fin_year,
                    "tdscert_cnt" => $tdscert_cnt,
                    "ason_date" => $ason_date,
                    "payee_code" => $payee_code,
                    "payee_name" => $payee_name,
                    "cert_type" => $cert_type,
                ];

                if ($output_type == 'Pdf') { // $output_type    = $_REQUEST['output_type'] ; || if($output_type == 'Report' || $output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/TDS/receivable_certificate_status", compact("tdscert_qry", "params", "report_type"));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/TDS/receivable_certificate_status", compact("tdscert_qry", "params")); 
            }       
        } else {
            $data = [];
            $displayId   = ['payee_help_id' => '4214'];

            $data = branches("demo");
            $data['finyr_qry']  = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray(); 
            $data['ason_date'] = date('d-m-Y');
            
            // echo "<pre>"; print_r($data); die;
            return view("pages/TDS/receivable_certificate_status", compact("data", "displayId"));
        }
    }

    public function receivable_followup_letter() {
        $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
		$data['requested_url'] = session()->requested_end_menu_url;
    
        if($this->request->getMethod() == "post") {
                $report_desc   = 'TDS CERTIFICATE STATUS (RECEIVABLE)';
                $output_type    = $_REQUEST['output_type'] ;
                $branch_code   = $_REQUEST['branch_code'] ;
                $fin_year      = $_REQUEST['fin_year'] ;
                $payee_type    = $_REQUEST['payee_type'] ;
                $payee_code    = $_REQUEST['payee_code'] ;  
                $payee_name    = str_replace('x_and_x','&',$_REQUEST['payee_name']) ; 
                $payee_name    = str_replace("x_app_x","'",$payee_name) ; 
                $addr_code     = $_REQUEST['addr_code'] ;
                $attn_code     = $_REQUEST['attn_code'] ;
                $attn_name     = str_replace('x_and_x','&',$_REQUEST['attn_name']) ;
                $attn_name     = str_replace("x_app_x","'",$attn_name) ;
            
                //---- Branch 
                $branch_qry    = $this->db->query("select a.*,b.company_name from branch_master a, company_master b where a.branch_code = '$branch_code' and a.company_code = b.company_code ")->getResultArray()[0];
                $company_code  = $branch_qry['company_code'] ;
                $company_name  = $branch_qry['company_name'] ;
                $branch_name   = $branch_qry['branch_name'] ;
                $branch_addr1  = $branch_qry['address_line_1'] ; 
                $branch_addr2  = $branch_qry['address_line_2'] ; 
                $branch_addr3  = $branch_qry['address_line_3'] ; 
                $branch_addr4  = $branch_qry['address_line_4'] ; 
                $branch_city   = $branch_qry['city'] ; 
                $branch_pin    = $branch_qry['pin_code'] ; 
                $branch_phone  = $branch_qry['phone_no'] ; 
                $branch_fax    = $branch_qry['fax_no'] ; 
                $branch_email  = $branch_qry['email_id'] ; 
                $branch_panno  = $branch_qry['pan_no'] ; 
                $comp_name     = $company_name ;            //  .' ['.$branch_name.']' ;
            if($output_type == 'Report' || $output_type == 'Pdf') {
                if ($branch_addr2 != '') 
                { 
                    if($branch_addr3 != '') { 
                        if($branch_addr4 != '') { 
                            $branch_addr2 = $branch_addr2.', '.$branch_addr3.', '.$branch_addr4 ; 
                        } else { 
                            $branch_addr2 = $branch_addr2.' '.$branch_addr3 ; 
                        }	 
                    } else { 
                        if($branch_addr4 != '') { 
                            $branch_addr2 = $branch_addr2.', '.$branch_addr4  ; 
                        } else { 
                            $branch_addr2 = $branch_addr2 ; 
                        }	 
                    } 
                } else { 
                    if($branch_addr3 != '') { 
                        if($branch_addr4 != '') { 
                            $branch_addr2 = $branch_addr3.', '.$branch_addr4 ; 
                        } else { 
                            $branch_addr2 = $branch_addr3 ; 
                        }	 
                    } else { 
                        if($branch_addr4 != '') { 
                            $branch_addr2 = $branch_addr4  ; 
                        } else { 
                            $branch_addr2 = '' ; 
                        }	 
                    } 
                } 
                
                if ($branch_city != '') { 
                    if($branch_pin  != '') { $branch_citypin = $branch_city.'-'.$branch_pin ; } else { $branch_citypin = $branch_city ; } 
                } else { 
                    if($branch_pin  != '') { $branch_citypin = 'PIN - '.$branch_pin ; } else { $branch_citypin = '' ; } 
                } 	 
                
                if ($branch_phone != '') { 
                    if($branch_fax != '') { $branch_telfax = 'Tel : '.$branch_phone.', Fax : '.$branch_fax ; } else { $branch_telfax = 'Tel : '.$branch_phone ; } 
                } else { 
                    if($branch_fax != '') { $branch_telfax = 'Fax : '.$branch_fax ; } else { $branch_telfax = '' ; } 
                } 	 
                
                if ($branch_email != '') { $branch_email = 'E-mail : '.$branch_email ; } else { $branch_email = '' ; }
            
                //---- Address
                $addr_qry = $this->db->query("select * from client_address where client_code = '$payee_code' and address_code = '$addr_code'  ")->getRowArray() ;
            
                $client_addr1  = $addr_qry['address_line_1'] ; 
                $client_addr2  = $addr_qry['address_line_2'] ; 
                $client_addr3  = $addr_qry['address_line_3'] ; 
                $client_addr4  = $addr_qry['address_line_4'] ; 
                $client_city   = $addr_qry['city'] ; 
                $client_pin    = $addr_qry['pin_code'] ; 
            
                if ($client_city != '') { 
                    if($client_pin  != '') { $client_addr5 = $client_city.'-'.$client_pin ; } else { $client_addr5 = $client_city ; } 
                } else { 
                    if($client_pin  != '') { $client_addr5 = 'PIN - '.$client_pin ; } else { $client_addr5 = '' ; } 
                } 	 
               
                $tdscert_qry  = $this->db->query("select a.* from tds_certificate a where a.branch_code = '$branch_code' and a.fin_year = '$fin_year' and a.payee_payer_type like '$payee_type' and ifnull(a.payee_payer_code,'%') like ifnull('$payee_code','%') and a.pay_rcpt_ind = 'R' and (a.tds_cert_no is NULL or a.tds_cert_no = '') order by a.doc_date ")->getResultArray() ;
                $tdscert_cnt  = count($tdscert_qry);

            	if(empty($tdscert_qry)) {
                   	session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($data['requested_url']);
                }

                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "date" => date('d-m-Y'),
                    "requested_url" => $this->requested_url(),
                    "fin_year" => $fin_year,
                    "tdscert_cnt" => $tdscert_cnt,
                    "comp_name" => $comp_name,
                    "branch_addr1" => $branch_addr1,
                    "branch_addr2" => $branch_addr2,
                    "branch_addr3" => $branch_addr3,
                    "branch_addr4" => $branch_addr4,
                    "branch_citypin" => $branch_citypin,
                    "branch_telfax" => $branch_telfax,
                    "branch_code" => $branch_code,
                    "branch_email" => $branch_email,
                    "payee_name" => $payee_name,
                    "client_addr1" => $client_addr1,
                    "client_addr2" => $client_addr2,
                    "client_addr3" => $client_addr3,
                    "client_addr4" => $client_addr4,
                    "client_addr5" => $client_addr5,
                    "attn_name" => $attn_name,
                    "branch_panno" => $branch_panno,
                    "company_name" => $company_name,
                ];

                if ($output_type == 'Pdf') { // $output_type    = $_REQUEST['output_type'] ; || if($output_type == 'Report' || $output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/TDS/receivable_followup_letter", compact("tdscert_qry", "params", "report_type"));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/TDS/receivable_followup_letter", compact("tdscert_qry", "params"));
            }            
        } else {
            $data = []; $data = branches("demo");
        	$displayId = ['client_help_id' => '4216', 'attention_help_id' => '4217'] ;
            $data['finyr_qry']  = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray(); 
            $data['ason_date'] = date('d-m-Y');

            $data['payee_payer_type'] = 'C' ;
            $data['payee_payer_code'] = ''  ;
            $data['payee_payer_name'] = ''  ;
            $data['attention_code']   = ''  ;
            $data['attention_name']   = ''  ;
            $data['address_code']     = ''  ;
            $data['address_name']     = ''  ;
            return view("pages/TDS/receivable_followup_letter", compact("data", "displayId"));
        }
    }

    public function certificate_duplicate() {
        $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
        $selemode      = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:null;

        if($this->request->getMethod() == "post") {
            $branch_code    = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null ;
            $fin_year       = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null ;
            $quarter_no     = isset($_REQUEST['quarter_no'])?$_REQUEST['quarter_no']:null ;
            $start_date     = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null ;
            $start_date_ymd = date_conv($start_date,'-');
            $end_date       = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null ;
            $end_date_ymd   = date_conv($end_date,'-');
            $tds_return_no  = isset($_REQUEST['tds_return_no'])?$_REQUEST['tds_return_no']:null ;
            $payee_type     = isset($_REQUEST['payee_type'])?$_REQUEST['payee_type']:null ;
            $payee_code     = isset($_REQUEST['payee_code'])?$_REQUEST['payee_code']:null ;
            if($payee_code == '') { $payee_code = '%' ; } 
            $payee_name     = isset($_REQUEST['payee_name'])?$_REQUEST['payee_name']:null ;
            if($payee_name == '') { $payee_name = '%' ; } 
            $tds_cert_date  = isset($_REQUEST['tds_cert_date'])?$_REQUEST['tds_cert_date']:null ;
            $tds_cert_date_ymd = date_conv($tds_cert_date,'-');
            $sign_by_name   = isset($_REQUEST['sign_by_name'])?$_REQUEST['sign_by_name']:null ;
            $sign_by_desg   = isset($_REQUEST['sign_by_desg'])?$_REQUEST['sign_by_desg']:null ;
            $sign_by_row    = isset($_REQUEST['sign_by_row'])?$_REQUEST['sign_by_row']:null ;
            $view_print_ind = isset($_REQUEST['view_print_ind'])?$_REQUEST['view_print_ind']:null ;
            $tds_cert_no    = isset($_REQUEST['tds_cert_no'])?$_REQUEST['tds_cert_no']:null ;
            $duplicate_ind  = isset($_REQUEST['duplicate_ind'])?$_REQUEST['duplicate_ind']:null ;
            $short_fin_year = substr($fin_year,2,2).substr($fin_year,7,2) ;

            //-------
            $logdt_qry      = $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ")->getRowArray();
            $user_id        = session()->userId ;
            $curr_time      = $logdt_qry['current_time'];
            $curr_date      = $logdt_qry['current_dmydate'];
            $curr_day       = substr($curr_date,0,2) ;
            $curr_month     = substr($curr_date,3,2) ; 
            $curr_year      = substr($curr_date,6,4) ;
            $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);
            $mytable        = $temp_id."_tdsprint" ;
            $this->temp_db->query("drop table $mytable ") ;
            $my_sql1        = "create table $mytable 
                        ( payee_type varchar(01), payee_code varchar(06), payee_name varchar(50), tds_challan_no varchar(15), tds_deposit_date date, tds_bank_code varchar(04), tds_bank_name varchar(50), tds_cheque_no varchar(10), tds_return_no varchar(15), nature_of_service varchar(30), tds_cert_no varchar(10), tds_cert_date date,
                        fin_year varchar(09), daybook_code varchar(02), doc_no varchar(10), doc_date date, dr_cr_ind varchar(01), tax_code varchar(04), tax_percent double(7,3), gross_amount double(12,2), tax_amount double(12,2),
                        payee_adr1 varchar(50), payee_adr2 varchar(50), payee_adr3 varchar(50), payee_adr4 varchar(50), payee_city varchar(30),  payee_pin varchar(10), payee_panno varchar(50), tds_bank_bsr_code varchar(20), serial_no int(8), backlog_counsel_fee double(12,2)  
                        ) " ;
            $this->temp_db->query($my_sql1)->getResultArray() ;
            
            //-------
            $branch_qry    = $this->db->query("select a.*,b.company_name from branch_master a, company_master b where a.branch_code = '$branch_code' and a.company_code = b.company_code ")->getResultArray()[0];
            $company_code  = $branch_qry['company_code'] ;
            $company_name  = $branch_qry['company_name'] ;
            $branch_name   = $branch_qry['branch_name'] ;
            $branch_addr1  = $branch_qry['address_line_1'] ; 
            $branch_addr2  = $branch_qry['address_line_2'] ;   if($branch_addr2 == '') { $branch_addr2 = '&nbsp;' ; }
            $branch_addr3  = $branch_qry['address_line_3'] ;   if($branch_addr3 == '') { $branch_addr3 = '&nbsp;' ; }
            $branch_addr4  = $branch_qry['address_line_4'] ;   if($branch_addr4 == '') { $branch_addr4 = '&nbsp;' ; }
            $branch_city   = $branch_qry['city'] ; 
            $branch_pin    = $branch_qry['pin_code'] ; 
            $branch_panno  = $branch_qry['pan_no'] ; 
            $branch_tanno  = $branch_qry['tan_no'] ; 
            $comp_name     = $company_name ;
            
            //--- City & Pin
            if ($branch_city != '') 
            { 
                if($branch_pin  != '') { $branch_citypin = $branch_city.'-'.$branch_pin ; } else { $branch_citypin = $branch_city ; } 
            } 	 
            else
            { 
                if($branch_pin  != '') { $branch_citypin = 'PIN - '.$branch_pin ; } else { $branch_citypin = '' ; } 
            } 	 

            //-------
            if($duplicate_ind != 'Y')
            {
                if ($view_print_ind == 'P')
                {
                $tdscert_sql = "select payee_payer_type,payee_payer_code,serial_no 
                                    from tds_certificate 
                                where branch_code             = '$branch_code'
                                    and fin_year                = '$fin_year'
                                    and doc_date          between '$start_date_ymd' and '$end_date_ymd'
                                    and payee_payer_type        = '$payee_type' 
                                    and payee_payer_code     like '$payee_code' 
                                    and payee_payer_name     like '$payee_name' 
                                    and tds_deposit_ind         = 'Y'
                                    and (ifnull(tds_cert_no,'X') = 'X' or tds_cert_no = '')
                                    order by payee_payer_type,payee_payer_code,serial_no " ;
                $tdscert_row = $this->db->query($tdscert_sql)->getResultArray() ;
                $tdscert_cnt = count($tdscert_row) ;
                                    //
                $i = 1 ;
                $tdscert_from = 'X' ; 
                $tdscert_to   = 'X' ;
                foreach ($tdscert_row as $key => $tdscert_rows) 
                {
                    $cert_no   = $short_fin_year.'/'.str_pad(get_last_doc_no($fin_year,$branch_code,'TD','00','X')->getResultArray(),5,'0',STR_PAD_LEFT) ;
                    $cert_date = $tds_cert_date_ymd ;
                    //
                    if ($i==1) { $tdscert_from = $cert_no ; $tdscert_to = $cert_no ; } else { $tdscert_to = $cert_no ; } 
                    //
                    $ppayee_type = $tdscert_rows['payee_payer_type']  ;
                    $ppayee_code = $tdscert_rows['payee_payer_code']  ;
                    while ($ppayee_type == $tdscert_rows['payee_payer_type'] && $ppayee_code == $tdscert_rows['payee_payer_code'] && $i <= $tdscert_cnt)		
                    { 
                    $myupdt_sql = "update tds_certificate set tds_cert_no = '$cert_no', tds_cert_date = '$cert_date', tds_return_no = '$tds_return_no' where serial_no = '$tdscert_rows[serial_no]' " ;
                    $this->db->query($myupdt_sql)->getResultArray() ;
                    //
                    //$tdscert_rows = mysql_fetch_array($tdscert_qry)  ;
                    //$i = $i + 1 ;
                    }
                }
                //
                
                $myinsert_sql = "insert into $mytable 
                                    (payee_type,payee_code,payee_name,tds_challan_no,tds_deposit_date,tds_bank_code,tds_bank_name,tds_cheque_no,tds_return_no,nature_of_service,tds_cert_no,tds_cert_date,fin_year,daybook_code,doc_no,doc_date,dr_cr_ind,tax_code,tax_percent,gross_amount,tax_amount,payee_adr1,payee_adr2,payee_adr3,payee_adr4,payee_city,payee_pin,payee_panno,tds_bank_bsr_code,serial_no,backlog_counsel_fee)
                                select a.payee_payer_type,a.payee_payer_code,a.payee_payer_name,a.tds_challan_no,a.tds_deposit_date,a.bank_code,'',a.tds_cheque_no,'','',a.tds_cert_no,a.tds_cert_date,a.fin_year,a.daybook_code,a.doc_no,a.doc_date,a.dr_cr_ind,a.tax_code,'',a.gross_amount,a.tax_amount,'','','','','','','','',a.serial_no,b.backlog_counsel_fee
                                    from tds_certificate a left outer join voucher_header b on b.ref_ledger_serial_no = a.ref_ledger_serial_no 
                                    where tds_cert_no between '$tdscert_from' and '$tdscert_to' " ;
                $this->temp_db->query($myinsert_sql);  
                }   
                else
                {
                $myinsert_sql = "insert into $mytable 
                (payee_type,payee_code,payee_name,tds_challan_no,tds_deposit_date,tds_bank_code,tds_bank_name,tds_cheque_no,tds_return_no,nature_of_service,tds_cert_no,tds_cert_date,fin_year,daybook_code,doc_no,doc_date,dr_cr_ind,tax_code,tax_percent,gross_amount,tax_amount,payee_adr1,payee_adr2,payee_adr3,payee_adr4,payee_city,payee_pin,payee_panno,tds_bank_bsr_code,serial_no,backlog_counsel_fee)
                select a.payee_payer_type,a.payee_payer_code,a.payee_payer_name,a.tds_challan_no,a.tds_deposit_date,a.bank_code,'',a.tds_cheque_no,'','',a.tds_cert_no,a.tds_cert_date,a.fin_year,a.daybook_code,a.doc_no,a.doc_date,a.dr_cr_ind,a.tax_code,'',a.gross_amount,a.tax_amount,'','','','','','','','',a.serial_no, b.backlog_counsel_fee
                                    from tds_certificate a 
                                    left outer join voucher_header b on b.ref_ledger_serial_no = a.ref_ledger_serial_no
                                    where a.branch_code             = '$branch_code'
                                    and a.fin_year                = '$fin_year'
                                    and a.doc_date          between '$start_date_ymd' and '$end_date_ymd'
                                    and a.payee_payer_type        = '$payee_type' 
                                    and a.payee_payer_code     like '$payee_code' 
                                    and a.payee_payer_name     like '$payee_name' 
                                    and a.tds_deposit_ind         = 'Y'
                                    and (ifnull(a.tds_cert_no,'X') = 'X' or a.tds_cert_no = '')
                                    order by a.payee_payer_type,a.payee_payer_code,a.serial_no " ;
                $this->temp_db->query($myinsert_sql);
                }
            }
            
            else
            {
                $myinsert_sql = "insert into $mytable 
                                    (payee_type,payee_code,payee_name,tds_challan_no,tds_deposit_date,tds_bank_code,tds_bank_name,tds_cheque_no,tds_return_no,nature_of_service,tds_cert_no,tds_cert_date,fin_year,daybook_code,doc_no,doc_date,dr_cr_ind,tax_code,tax_percent,gross_amount,tax_amount,payee_adr1,payee_adr2,payee_adr3,payee_adr4,payee_city,payee_pin,payee_panno,tds_bank_bsr_code,serial_no,backlog_counsel_fee)
                                select a.payee_payer_type,a.payee_payer_code,a.payee_payer_name,a.tds_challan_no,a.tds_deposit_date,a.bank_code,'',a.tds_cheque_no,'','',a.tds_cert_no,a.tds_cert_date,a.fin_year,a.daybook_code,a.doc_no,a.doc_date,a.dr_cr_ind,a.tax_code,'',a.gross_amount,a.tax_amount,'','','','','','','','',a.serial_no,b.backlog_counsel_fee
                                    from tds_certificate a left outer join voucher_header b on b.ref_ledger_serial_no = a.ref_ledger_serial_no 
                                    where tds_cert_no = '$tds_cert_no' " ;
                $this->temp_db->query($myinsert_sql);  
            }
            //-------

            // echo "<pre>"; print_r($tdscert_qry); die;

            $tdscert_sql = "select * from $mytable order by payee_type,payee_code,doc_date " ;
            $tdscert_row = $this->db->query($tdscert_sql)->getResultArray() ;
            $tdscert_cnt = count($tdscert_row) ;
            $i = 1 ;
            foreach ($tdscert_row as $key => $tdscert_rows) 
            {
                $bank_qry          = $this->db->query("select bank_name, bsr_code from bank_master where bank_code = '$tdscert_rows[tds_bank_code]' ")->getRowArray() ;
                $tds_bank_name     = $bank_qry['bank_name'] ;	 
                $tds_bank_bsr_code = $bank_qry['bsr_code'] ;	 
                //
                $tax_qry           = $this->db->query("select tax_percent from tax_rate where fin_year = '$tdscert_rows[fin_year]' and tax_code = '$tdscert_rows[tax_code]' ")->getRowArray() ;
                $tax_percent       = $tax_qry['tax_percent'] ;	 
                //
                if ($tdscert_rows['payee_type'] == 'A' || $tdscert_rows['payee_type'] == 'T' || $tdscert_rows['payee_type'] == 'K')
                {
                $nature_qry  = $this->db->query("select code_desc from code_master where type_code = '010' and code_code = 'C' ")->getRowArray() ;
                $nature_of_service = $nature_qry['code_desc'] ; 
                }
                else 
                {
                $nature_qry  = $this->db->query("select code_desc from code_master where type_code = '010' and code_code = '$tdscert_rows[payee_type]' ")->getRowArray() ;
                $nature_of_service = $nature_qry['code_desc'] ; 
                }
                //
                if ($tdscert_rows['payee_type'] == 'C' || $tdscert_rows['payee_type'] == 'A' || $tdscert_rows['payee_type'] == 'T' || $tdscert_rows['payee_type'] == 'K')
                {
                $party_qry   = $this->db->query("select * from associate_master where associate_code = '$tdscert_rows[payee_code]' ")->getRowArray() ;
                $party_addr1 = $party_qry['address_line_1'] ;
                $party_addr2 = $party_qry['address_line_2'] ;
                $party_addr3 = $party_qry['address_line_3'] ;
                $party_addr4 = $party_qry['address_line_4'] ;
                $party_city  = $party_qry['city'] ;
                $party_pin   = $party_qry['pin_code'] ;
                $party_panno = $party_qry['pan_no'] ;
                }
                else if ($tdscert_rows['payee_type'] == 'E')
                {
                $party_qry   = $this->db->query("select * from empmas where employee_id = '$tdscert_rows[payee_code]' ")->getRowArray() ;
                $party_addr1 = $party_qry['employee_address1'] ;
                $party_addr2 = $party_qry['employee_address2'] ;
                $party_addr3 = $party_qry['employee_address3'] ;
                $party_addr4 = '' ;
                $party_city  = $party_qry['employee_city'] ;
                $party_pin   = $party_qry['employee_pin'] ;
                $party_panno = $party_qry['employee_pan_no'] ;
                }
                else if ($tdscert_rows['payee_type'] == 'S')
                {
                $party_qry   = $this->db->query("select * from supplier_master where supplier_code = '$tdscert_rows[payee_code]' ")->getRowArray() ;
                $party_addr1 = $party_qry['address_line_1'] ;
                $party_addr2 = $party_qry['address_line_2'] ;
                $party_addr3 = $party_qry['address_line_3'] ;
                $party_addr4 = $party_qry['address_line_4'] ;
                $party_city  = $party_qry['city'] ;
                $party_pin   = $party_qry['pin_code'] ;
                $party_panno = $party_qry['pan_no'] ;
                }
                else
                {
                $party_addr1 = '' ;
                $party_addr2 = '' ;
                $party_addr3 = '' ;
                $party_addr4 = '' ;
                $party_city  = '' ;
                $party_pin   = '' ;
                $party_panno = '' ;
                }
                //
                $myupdate_sql = "update $mytable set tax_percent = '$tax_percent', tds_bank_name = '$tds_bank_name', tds_bank_bsr_code = '$tds_bank_bsr_code', tds_return_no = '$tds_return_no', nature_of_service = '$nature_of_service', payee_adr1 = '$party_addr1', payee_adr2 = '$party_addr2', payee_adr3 = '$party_addr3', payee_adr4 = '$party_addr4', payee_city = '$party_city', payee_pin = '$party_pin', payee_panno = '$party_panno' where serial_no = '$tdscert_rows[serial_no]' " ;
                $this->temp_db->query($myupdate_sql) ;
                //	 
                // $tdscert_row = mysql_fetch_array($tdscert_qry)  ;
                // $i = $i + 1 ;
            }
            //-------
            $tdscert_qry = $this->db->query("select * from $mytable order by tds_cert_no, payee_type,payee_code,doc_date")->getResultArray() ;
            //$tdscert_qry = $this->db->query($tdscert_sql)->getResultArray() ;
            $tdscert_cnt = count($tdscert_qry) ;

            try {
                $tdscert_qry[0];
                if($tdscert_cnt == 0)  throw new \Exception('No Records Found !!');

            } catch (\Exception $e) {
                session()->setFlashdata('message', 'No Records Found !!');
                return redirect()->to($this->requested_url());
            }

            $params = [
                "tdscert_cnt" => $tdscert_cnt,
                "company_name" => $company_name,
                "quarter_no" => $quarter_no,
                "branch_tanno" => $branch_tanno,
                "branch_panno" => $branch_panno,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "sign_by_name" => $sign_by_name,
                "tds_cert_date" => $tds_cert_date,
                "sign_by_desg" => $sign_by_desg,

            ];
           return view("pages/TDS/certificate_duplicate", compact("data", "tdscert_qry", "params"));
        } else {
            $data = [];

            $data = branches("demo");
            $data['finyr_qry']  = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray(); 
            $data['ason_date'] = date('d-m-Y');
            $displayId   = ['payee_help_id' => '4214', 'signed_help_id' => '4218'];

            if ($selemode == 'Y')
            {
                $tds_cert_no = isset($_REQUEST['tds_cert_no'])?$_REQUEST['tds_cert_no']:null;
                //
                $tdscert_qry = $this->db->query("select a.*, b.quarter_no, b.start_date, b.end_date from tds_certificate a left outer join tds_return_detail b on b.tds_return_no = a.tds_return_no where a.tds_cert_no = '$tds_cert_no' ")->getResultArray() ; 
                $tdscert_cnt = count($tdscert_qry);
            //echo "<pre>"; print_r($tdscert_qry); die;

                //
                $branch_code      = $tdscert_qry['branch_code'] ;
                $fin_year         = $tdscert_qry['fin_year'] ;
                $quarter_no       = $tdscert_qry['quarter_no'] ;
                $start_date       = date_conv($tdscert_qry['start_date']) ;
                $end_date         = date_conv($tdscert_qry['end_date']) ;
                $tds_return_no    = $tdscert_qry['tds_return_no'] ;
                $payee_payer_type = $tdscert_qry['payee_payer_type'] ;
                $payee_payer_code = $tdscert_qry['payee_payer_code'] ;
                $payee_payer_name = $tdscert_qry['payee_payer_name'] ;
                $tds_cert_date    = date_conv($tdscert_qry['tds_cert_date']) ;
            }
            
            // echo "<pre>"; print_r($data); die;
            return view("pages/TDS/certificate_duplicate", compact("data", "displayId"));
        }
    }
}
?>