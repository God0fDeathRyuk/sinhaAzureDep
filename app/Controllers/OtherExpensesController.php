<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OtherExpensesController extends BaseController
{
    public function __construct() {
        $db = $this->db = db_connect();
        $temp_db = $this->temp_db = db_connect('temp'); 
    }

    /*********************************************************************************************/
    /***************************** TDS [Transactions] ***********************************/
    /*********************************************************************************************/

    public function common_print_expenses($ref_voucher_serial_no = null, $user_id = null, $status_code = null) { 

        if($status_code == '<font color=red>C</font>' || $status_code == 'C') {   
            $result = $this->db->query("select status_code from voucher_header where serial_no = '$ref_voucher_serial_no'")->getRowArray();
            $voucher_status = $result['status_code'];
            
            if($voucher_status == 'A') {
                $params = []; $i = 0; $payee = '';
                $serial_no          = isset($_REQUEST['serial_no'])         ?$_REQUEST['serial_no']        :null;
                $serial_no          = isset($_REQUEST['serial_no'])         ?$_REQUEST['serial_no']        :null;
                $voucher_ind        = isset($_REQUEST['voucher_ind'])       ?$_REQUEST['voucher_ind']      :null;
                $voucher_serial_no  = isset($_REQUEST['voucher_serial_no']) ?$_REQUEST['voucher_serial_no']:null;

                $serial_no  = $ref_voucher_serial_no;
                $user_sql   = "select * from system_user where user_id = '$user_id' " ; 
                $user_row   = $this->db->query($user_sql)->getRowArray();

                if($user_row['user_gender'] == 'F') { $sys_user_name = 'Ms. '.$user_row['user_name'] ;} else { $sys_user_name = 'Mr. '.$user_row['user_name'] ; }

                if ($voucher_ind == 'Memo') {
                    $hdr_stmt = "select a.* from voucher_header a where a.link_jv_serial_no = '$voucher_serial_no' ";
                } else {
                    $hdr_stmt = "select a.* from voucher_header a where a.serial_no = '$serial_no' ";
                }

                $res1 = $this->db->query($hdr_stmt)->getResultarray();
                $header_cnt = count($res1);
                
                $hcnt = 1;
                foreach($res1 as $hdr_row) {
                    $i++;
                    $branch_code       = $hdr_row['branch_code'];
                    $serial_no         = $hdr_row['serial_no'];
                    $entry_date        = date_conv($hdr_row['entry_date']); 
                    $payee_payer_name  = $hdr_row['payee_payer_name']; 
                    $remarks           = $hdr_row['remarks']; 
                    $ref_advance_serial_no  = $hdr_row['ref_advance_serial_no']; 
                    $trans_type        = $hdr_row['trans_type'] ;
                    $payee_payer_type  = $hdr_row['payee_payer_type'] ;
                    $daybook_code      = $hdr_row['daybook_code'] ;
                    $inst_type         = $hdr_row['instrument_type']; 
                    $inst_no           = $hdr_row['instrument_no']; 
                    $inst_dt           = date_conv($hdr_row['instrument_dt'],'-'); 
                    $inst_bank         = $hdr_row['bank_name']; 
                    $hdr_gross_amount  = $hdr_row['gross_amount']; 
                    $hdr_tax_amount    = $hdr_row['tax_amount']; 
                    $hdr_net_amount    = $hdr_row['net_amount'];
                    $hdr_user          = strtoupper($hdr_row['prepared_by']); 
                    $trans_type        = $hdr_row['trans_type']; 
                    $payment_type      = $hdr_row['payment_type'];
                    $ref_advance_serial_no       = $hdr_row['ref_advance_serial_no'];
                    
                
                    $user_sql       = "select * from system_user where user_id = '$hdr_user' " ; 
                    $user_row      = $this->db->query($user_sql)->getRowArray();

                    if($user_row['user_gender'] == 'F') { $sys_user_name = 'Ms. '.$user_row['user_name'] ;} else { $sys_user_name = 'Mr. '.$user_row['user_name'] ; }

                    if($payee_payer_type == 'S') {  $payee = 'SUPPL' ;}
                    if($payee_payer_type == 'E') {  $payee = 'EMPL' ;}
                    if($payee_payer_type == 'C') {  $payee = 'CUNL' ;}
                    if($payee_payer_type == 'K') {  $payee = 'CLRK' ;}
                    if($payee_payer_type == 'O') {  $payee = 'ORS' ;}
                    if($payee_payer_type == 'U') {  $payee = 'CONST' ;}
                    
                    if($payment_type == 'A') {  $payment_type = '(ADV)' ;}
                    if($payment_type == 'N') {  $payment_type = '(NOR)' ;}
                    
                    $branch_sql   = $this->db->query("select * from branch_master where branch_code = '$branch_code' ")->getRowArray();
                    $branch_addr1 = $branch_sql['address_line_1'].', '.$branch_sql['city'].' - '.$branch_sql['pin_code'] ;
                    $branch_addr2 = 'TEL : '.$branch_sql['phone_no'] ;
                    $branch_addr3 = 'FAX : '.$branch_sql['fax_no'] ;
                    $branch_addr4 = 'E-Mail : '.$branch_sql['email_id'] ;

                    $hdr_net_rs_arr   = explode(".",$hdr_row['net_amount']); //Changed by ABM with help of PC (on 19/11/11)  for actual rupees and paise figure.
                    $hdr_net_rs       = $hdr_net_rs_arr[0]*1;
                    $hdr_net_paise    = $hdr_net_rs_arr[1]*1;    
                    $net_riw          = int_to_words($hdr_net_rs) ;
                    $paise_riw        = int_to_words($hdr_net_paise) ;
                
                    if($paise_riw > 0) {$hdr_net_riw = '(Rupees '.$net_riw.' and paise '.$paise_riw.' only)';} else {$hdr_net_riw = '(Rupees '.$net_riw.' only)';} 

                    $dtl_stmt = "select b.* from voucher_detail b where b.ref_voucher_serial_no = '$serial_no' order by b.row_no asc,b.dr_cr_ind desc";
                    $res2 = $this->db->query($dtl_stmt)->getResultArray();
                    $detail_cnt = count($res2);

                    $cnt = 0 ;
                    foreach($res2 as $dtl_row) {
                        $cnt++;
                        $narration    = $dtl_row['narration'];
                        $main_ac_code = $dtl_row['main_ac_code'];
                        $sub_ac_code  = $dtl_row['sub_ac_code'];
                        $matter_code  = $dtl_row['matter_code'];
                        $client_code  = $dtl_row['client_code'];
                        $expense_code = $dtl_row['expense_code'];
                        $gross_amount = $dtl_row['gross_amount'];
                        $dr_cr_ind    = $dtl_row['dr_cr_ind'];
                    
                        $main_ac_stmt = "select main_ac_desc,sub_ac_ind from account_master where main_ac_code = '$main_ac_code'";
                        $main_ac_row = $this->db->query($main_ac_stmt)->getRowArray();
                        $main_ac_desc = $main_ac_row['main_ac_desc'];
                        $sub_ac_ind   = $main_ac_row['sub_ac_ind'];
                        $sub_ac_desc  = '' ;

                        if($sub_ac_ind == 'Y') {
                            $sub_ac_stmt = "select sub_ac_desc from sub_account_master where main_ac_code = '$main_ac_code' and sub_ac_code  = '$sub_ac_code'";
                            $sub_ac_row = $this->db->query($sub_ac_stmt)->getRowArray();
                            $slash_params = isset($sub_ac_row['sub_ac_desc']) ? $sub_ac_row['sub_ac_desc'] : '';
                            $sub_ac_desc = ' / '. $slash_params;
                        }
                    
                        $matter_stmt = "select concat(matter_desc1,' ',matter_desc2) matter_desc from fileinfo_header where matter_code = '$matter_code'";
                        $matter_row = $this->db->query($matter_stmt)->getRowArray();
                        $matter_desc = isset($matter_row['matter_desc']) ? $matter_row['matter_desc'] : '';

                        $params[$i-1] = [
                            "serial_no" => $serial_no,
                            "entry_date" => $entry_date,
                            "branch_addr1" => $branch_addr1,
                            "branch_addr2" => $branch_addr2,
                            "branch_addr3" => $branch_addr3,
                            "branch_addr4" => $branch_addr4,
                            "trans_type" => $trans_type,
                            "daybook_code" => $daybook_code,
                            "inst_no" => $inst_no,
                            "inst_dt" => $inst_dt,
                            "payee" => $payee,
                            "payment_type" => $payment_type,
                            "cnt" => $cnt,
                            "narration" => $narration,
                            "main_ac_code" => $main_ac_code,
                            "sub_ac_code" => $sub_ac_code,
                            "matter_code" => $matter_code,
                            "client_code" => $client_code,
                            "expense_code" => $expense_code,
                            "dr_cr_ind" => $dr_cr_ind,
                            "gross_amount" => $gross_amount,
                            "main_ac_desc" => $main_ac_desc,
                            "sub_ac_desc" => $sub_ac_desc,
                            "hdr_net_riw" => $hdr_net_riw,
                            "hdr_net_amount" => $hdr_net_amount,
                            "payee_payer_name" => $payee_payer_name,
                            "remarks" => $remarks,
                            "ref_advance_serial_no" => $ref_advance_serial_no,
                            "hdr_user" => $hdr_user,
                        ];
                    }
                }
                return ["params" => $params];
            } else {
                session()->setFlashdata('message', 'Voucher Has Already Been APPROVED !!');
                return redirect()->to(session()->last_selected_end_menu);
            }
        } else {
            session()->setFlashdata('message', 'Voucher Not Yet Been GENERATED !!');
            return redirect()->to(session()->last_selected_end_menu);
        }
    }  

    public function court_expenses() {
        $global_userid = $user_id = session()->userId;
        $data = branches($user_id); $global_curr_finyear = session()->financialYear;
        $global_dmydate = date('d-m-Y'); $global_sysdate = date_conv($global_dmydate); 
        $data['requested_url'] = session()->requested_end_menu_url;
        $disp_heading = 'Court Misc Expenses'; 

        $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $selemode = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:null;
        $branch_code = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : session()->user_qry['branch_code'];
        $employee_id = isset($_REQUEST['employee_id']) ? $_REQUEST['employee_id'] : null ;
        $employee_name = isset($_REQUEST['employee_name']) ? $_REQUEST['employee_name'] : null ;
		$pan_no = isset($_REQUEST['pan_no']) ? $_REQUEST['pan_no'] : null ;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : 'B';
        $status_desc = isset($_REQUEST['status_desc']) ? $_REQUEST['status_desc'] : null;
        $exp_date = isset($_REQUEST['exp_date']) ? $_REQUEST['exp_date'] : $global_dmydate;
        $total_amount = isset($_REQUEST['total_amount']) ? $_REQUEST['total_amount'] : 0;
        $memo_date = isset($_REQUEST['exp_date'])?$_REQUEST['exp_date']:null;
        $ref_voucher_serial_no  = isset($_REQUEST['ref_voucher_serial_no'])?$_REQUEST['ref_voucher_serial_no']:null;
        $arbitrator_rownum = isset($_REQUEST['arb_row_num'])?$_REQUEST['arb_row_num']:null; 
        $exp_date_ymd = date_conv($exp_date);

        $branch = $this->db->query(session()->branch_selection_stmt)->getResultArray();
        $displayId = ['agency_help_id' => 4142, 'matter_help_id' => 4540, 'ratecd_help_id' => 4544]; $params_opt = ['expdate' => ($user_option == 'Add') ? $exp_date : '']; $xerox_qry = [];

        $sele_stmt = "select code_code, code_desc from code_master where type_code = '041' order by code_desc";
        $sele_qry  = $this->db->query($sele_stmt)->getResultArray(); $res = ''; $row_num = 0;

        if ($selemode != 'Y') {
            $srlno = '' ; $rowoptn = '' ; $expdate = '' ; $expfor = '' ; $matr_code = '' ;  $clnt_code = '' ; $narr = '' ;  $narr2 = '' ; $expamt = '' ; 

            if ($user_option == 'Add') {
                $redv = '' ; $disv = ''; $expdate = $exp_date; $expfor = 'C';

            } else if ($user_option == 'Edit') {
                $redv = 'readonly'; $disv = 'disabled'; $selemode = 'Y';

            } else if ($user_option == 'Generate') {
                $redv = 'readonly' ; $disv = 'disabled' ;

                if($status_code == '<font color=green>B</font>' || $status_code == 'B') {
                   $memo_date     = date_conv($memo_date);
                   $where         = "court_expense.employee_id = '$employee_id' and court_expense.exp_date = '$memo_date' and court_expense.status_code = 'B' and (court_expense.ref_jv_serial_no is NULL or court_expense.ref_jv_serial_no = '0')";

                   $xerox_qry = $this->db->query("select court_expense.*,b.client_code,b.matter_desc1,b.matter_desc2 from court_expense inner join fileinfo_header b on court_expense.matter_code = b.matter_code where $where order by court_expense.serial_no ASC")->getResultArray();
                   $row_num = $this->db->query("select count(serial_no) as totalRow from court_expense where $where")->getRowArray();
                   $branch_code = isset($xerox_qry[0]['branch_code']) ? $xerox_qry[0]['branch_code'] : '';

                   $res = $this->db->query("select a.tax_percent,a.tax_code, b.tax_name from tax_rate a, tax_master b where a.fin_year = '$global_curr_finyear' and a.tax_code = b.tax_code and b.tax_type_code = 'T'")->getResultArray();
                } else {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to(session()->last_selected_end_menu);
                }
                $selemode = 'Y';

            } else if ($user_option == 'Approve') {
                $redv = 'readonly' ; $disv = 'disabled';

                if($status_code == '<font color=red>A</font>' || $status_code == 'A') {
                    $memo_date     = date_conv($memo_date);
                    $where         = "court_expense.employee_id = '$employee_id' and court_expense.exp_date = '$memo_date' and court_expense.status_code = 'A' and (court_expense.ref_jv_serial_no is NULL or court_expense.ref_jv_serial_no = '0')";

                    $xerox_qry    = $this->db->query("select court_expense.*,b.client_code,b.matter_desc1,b.matter_desc2 from court_expense inner join fileinfo_header b on court_expense.matter_code = b.matter_code where $where order by court_expense.serial_no ASC")->getResultArray();
                    $row_num = $this->db->query("select count(serial_no) as totalRow from court_expense where $where")->getRowArray();
                    $branch_code = isset($xerox_qry[0]['branch_code']) ? $xerox_qry[0]['branch_code'] : '';

                } else {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to(session()->last_selected_end_menu);
                }
                $selemode = 'Y';

            } else if($user_option == 'Delete') {
                $redv = 'readonly' ; $disv = 'disabled';
                
                if($status_code == '<font color=red>C</font>' || $status_code == 'C') {
                    $memo_date = date_conv($memo_date);
                    $where = "court_expense.employee_id = '$employee_id' and court_expense.exp_date = '$memo_date' and court_expense.status_code = 'C' and ref_voucher_serial_no = '$ref_voucher_serial_no'";

                    $xerox_qry = $this->db->query("select court_expense.*,b.client_code,b.matter_desc1,b.matter_desc2 from court_expense inner join fileinfo_header b on court_expense.matter_code = b.matter_code where $where order by court_expense.serial_no ASC")->getResultArray();
                    $row_num = $this->db->query("select count(serial_no) as totalRow from court_expense where $where")->getRowArray();

                    $branch_code = isset($xerox_qry[0]['branch_code']) ? $xerox_qry[0]['branch_code'] : '';
                } else {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to(session()->last_selected_end_menu);
                }
                $selemode = 'Y';
            } else if ($user_option == 'Print') {
                $print = true; $response = $this->common_print_expenses($ref_voucher_serial_no, $user_id, $status_code);
                
                if(!is_object($response)) {
                    return view("pages/OtherExpenses/court_expenses", ['print' => $print, "data" => $data, "displayId" => $displayId, 'params' => $response['params'], 'user_option' => $user_option]);
                } else return $response;
            } 
            
            $params_opt = [
                'srlno' => $srlno, 'rowoptn' => $rowoptn, 'expdate' => $expdate, 'expfor' => $expfor, 'matr_code' => $matr_code,  'clnt_code' => $clnt_code, 'narr' => $narr,  'narr2' => $narr2, 'expamt' => $expamt, 
            ];
            
        } else {
            $redv = 'readonly' ; $disv = 'disabled' ;
            $exp_date_ymd = date_conv($exp_date);
            $expdate = $exp_date ;
            $exp_for = isset($_REQUEST['expdate'])?$_REQUEST['expdate']:null ; 
            $matter_code = isset($_REQUEST['matr_code'])?$_REQUEST['matr_code']:null ;
            $client_code = isset($_REQUEST['clnt_code'])?$_REQUEST['clnt_code']:null ;
            $narr_code = isset($_REQUEST['narr_code'])?$_REQUEST['narr_code']:null ;
            $description = isset($_REQUEST['narr'])?$_REQUEST['narr']:null ;
            $description2 = isset($_REQUEST['narr2'])?$_REQUEST['narr2']:null ;
            $amount = isset($_REQUEST['expamt'])?$_REQUEST['expamt']:null ;
            $serial_no = isset($_REQUEST['srlno'])?$_REQUEST['srlno']:null ;
            $rowoptn = isset($_REQUEST['rowoptn'])?$_REQUEST['rowoptn']:null ;
            $status_code = 'B';
            $redv = 'readonly' ; $disv = 'disabled' ;

            //---- Database Connection 
            $photoCopyExpenseObj = $this->db->table("court_expense");

            if ($serial_no == '' && $user_option == 'Add') {
                $array = array('serial_no'              => '',
                                'branch_code'            => $branch_code,
                                'exp_date'               => date_conv($exp_date),
                                'employee_id'            => $employee_id,
                                'description'            => $description,
                                'description2'           => $description2,
                                'matter_code'            => $matter_code,
                                'client_code'            => $client_code,
                                'amount'                 => $amount,
                                'passed_amount'          => $amount,
                                'ref_billinfo_serial_no' => NULL,
                                'ref_voucher_serial_no'  => NULL,
                                'ref_jv_serial_no'       => NULL,
                                'status_code'            => 'B',
                                'prepared_by'            => $global_userid,
                                'prepared_on'            => $global_sysdate,
                                'passed_by'              => $global_userid,
                                'passed_dt'              => $global_sysdate
                            );
                $photoCopyExpenseObj->insert($array);
            } else if ($serial_no != '' && $rowoptn == 'Edit') {
                $array = array('branch_code'            => $branch_code,
                                'exp_date'               => date_conv($exp_date),
                                'employee_id'            => $employee_id,
                                'description'            => $description,
                                'description2'           => $description2,
                                'matter_code'            => $matter_code,
                                'client_code'            => $client_code,
                                'amount'                 => $amount,
                                'passed_amount'          => $amount,
                                'ref_billinfo_serial_no' => NULL,
                                'ref_voucher_serial_no'  => NULL,
                                'ref_jv_serial_no'       => NULL,
                                'status_code'            => 'B',
                                'prepared_by'            => $global_userid,
                                'prepared_on'            => $global_sysdate,
                                'passed_by'              => $global_userid,
                                'passed_dt'              => $global_sysdate
                            );
                $where = "serial_no = '".$serial_no."'";
                $photoCopyExpenseObj->update($array, $where);

            } else if ($serial_no != '' && $rowoptn == 'Delete') {
                $where = "serial_no = '".$serial_no."'";
                $photoCopyExpenseObj->delete($where);
            } else if($user_option == 'Approve') {
                for($i=1; $i <= $arbitrator_rownum; $i++) {
                    if(!empty($_REQUEST['passed_amount'.$i])) {
                        $array = array('serial_no'              => $_REQUEST['serial_no'.$i],
                                        'branch_code'            => $branch_code,
                                        'exp_date'               => date_conv($_REQUEST['memo_date'.$i]),
                                        'employee_id'            => strtoupper($_REQUEST['employee_id']),
                                        'matter_code'            => $_REQUEST['matter_code'.$i],
                                        'description'            => $_REQUEST['description'.$i],
                                        'amount'                 => $_REQUEST['amount'.$i],
                                        'passed_amount'          => isset($_REQUEST['passed_amount'.$i])?$_REQUEST['passed_amount'.$i]:null,
                                        'ref_billinfo_serial_no' => NULL,
                                        'ref_voucher_serial_no'  => NULL,
                                        'ref_jv_serial_no'       => NULL,
                                        'status_code'            => 'B',
                                        'prepared_by'            => $_REQUEST['prepared_name'.$i],
                                        'prepared_on'            => $_REQUEST['prepared_dt'.$i],
                                        'passed_by'              => $_REQUEST['prepared_by'],
                                        'passed_dt'              => $_REQUEST['prepared_on']
                                    );
            
                        $where = "serial_no = '".$_REQUEST['serial_no'.$i]."'";
                        $court_expense = $courtExpenseObj->update($array, $where);
                    }
                }
            } else if ($user_option == 'Generate') {
                
                $branch_code = $_REQUEST['branch_code_copy'];

                $codemasObj = $this->db->table("code_master");
                $controlKeyObj = $this->db->table("control_keycodes");
                $voucherHdrObj = $this->db->table("voucher_header");
                $voucherDtlObj = $this->db->table("voucher_detail");
                $ledgerTranHdrObj = $this->db->table("ledger_trans_hdr");
                $ledgerTranDtlObj = $this->db->table("ledger_trans_dtl");
                $courtExpenseObj = $this->db->table("court_expense");

                $branch_code = $_REQUEST['branch_code_copy'];
                // echo "<pre>"; print_r($arbitrator_rownum); die;
                //--- Update Record : COURT_EXPENSE <t></t>able
                for($i=1; $i <= $arbitrator_rownum; $i++) {
                    $array = array ('status_code' => 'C',
                                    'passed_by' => $_REQUEST['approve_by'.$i],
                                    'passed_dt' => $_REQUEST['approve_on'.$i]
                                );
                    $where = "serial_no = '".$_REQUEST['serial_no'.$i]."'";
                    $court_expense = $courtExpenseObj->update($array, $where);
                }

                //--- Insert Record (JV) : VOUCHER_HEADER table
                $employee_id = strtoupper($_REQUEST['employee_id']);
                $where = "court_expense.employee_id = '$employee_id' and court_expense.status_code = 'C' and (court_expense.ref_voucher_serial_no is NULL or court_expense.ref_voucher_serial_no = '0')";
                $dateRangeArray = $this->db->query("SELECT min(exp_date) as start_date, max(exp_date) as end_date from court_expense where $where")->getRowArray();

                $start_date = $dateRangeArray['start_date'];
                $end_date   = $dateRangeArray['end_date'];

                $where         = "control_keycodes.key_code = '017'";
                $controlArray = $this->db->query("SELECT control_keycodes.* from control_keycodes where $where")->getRowArray();

                $day_book_code = $controlArray['key_value'];
                $doc_type      = $controlArray['key_desc'];
                $doc_type      = 'JV';

                $narration = "COURT MISC EXPENSE FROM ".date_conv($start_date) ." TO ".date_conv($end_date);

                $array = array( 'serial_no'             => '',
                                'branch_code'           => $branch_code,
                                'entry_date'            => date_conv($_REQUEST['memo_date']),
                                'trans_type'            => 'CM',
                                'voucher_type'          => $doc_type,
                                'payee_payer_type'      => NULL,
                                'payee_payer_code'      => NULL,
                                'payee_payer_name'      => NULL,
                                'payment_type'          => NULL,
                                'daybook_code'          => $day_book_code,
                                'instrument_type'       => NULL,
                                'instrument_no'         => NULL,
                                'instrument_dt'         => NULL,
                                'bank_name'             => NULL,
                                'gross_amount'          => $_REQUEST['gross_amount'],
                                'tax_code'              => NULL,
                                'tax_amount'            => NULL,
                                'net_amount'            => $_REQUEST['gross_amount'],
                                'remarks'               => $narration,
                                'status_code'           => 'C',
                                'ref_ledger_serial_no'  => NULL,
                                'ref_jv_serial_no'      => NULL,
                                'ref_advance_serial_no' => NULL,
                                'link_jv_serial_no'     => NULL,
                                'prepared_by'           => $global_userid,
                                'prepared_on'           => $global_sysdate,
                                'passed_by'             => NULL,
                                'passed_on'             => NULL,
                                'paid_by'               => NULL,
                                'paid_on'               => NULL,
                            );

                $ins_voucherHdr = $voucherHdrObj->insert($array);
                $voucherSerial = $this->db->insertID();

                //--- Insert Record (JV) : VOUCHER_DETAIL table
                $where         = "control_keycodes.key_code = '011'";
                $controlArray = $this->db->query("SELECT control_keycodes.* from control_keycodes where $where")->getRowArray();

                $main_ac_code = $controlArray['key_value'];
                $ref_doc_type = 'COURT';

                for($i=1; $i <= $arbitrator_rownum; $i++) {
                    $array = array( 'ref_voucher_serial_no'    => $voucherSerial,
                                    'row_no'                   => $i,
                                    'main_ac_code'             => $main_ac_code,
                                    'sub_ac_code'              => NULL,
                                    'ref_bill_year'            => NULL,
                                    'ref_bill_no'              => NULL,
                                    'client_code'              => $_REQUEST['client_code'.$i],
                                    'matter_code'              => $_REQUEST['matter_code'.$i],
                                    'initial_code'             => $_REQUEST['initial_code'.$i],
                                    'expense_type'             => NULL,
                                    'expense_code'             => NULL,
                                    'narration'                => $_REQUEST['description'.$i].' '.$_REQUEST['description2'.$i],
                                    'realise_amount_inpocket'  => NULL,
                                    'realise_amount_outpocket' => NULL,
                                    'realise_amount_counsel'   => NULL,
                                    'gross_amount'             => $_REQUEST['passed_amount'.$i],
                                    'tax_amount'               => NULL,
                                    'net_amount'               => $_REQUEST['passed_amount'.$i],
                                    'dr_cr_ind'                => 'D',
                                    'deficit_amount_inpocket'  => NULL,
                                    'deficit_amount_outpocket' => NULL,
                                    'deficit_amount_counsel'   => NULL,
                                    'part_full_ind'            => NULL,
                                );
                    $ins_voucherDtl = $voucherDtlObj->insert($array);
                }

                $where = "control_keycodes.key_code = '003'";
                $controlArray = $this->db->query("SELECT control_keycodes.* from control_keycodes where $where")->getRowArray();

                $main_ac_code = $controlArray['key_value'];
                $array = array( 'ref_voucher_serial_no'    => $voucherSerial,
                                'row_no'                   => $i,
                                'main_ac_code'             => $main_ac_code,
                                'sub_ac_code'              => strtoupper($_REQUEST['employee_id']),
                                'ref_bill_year'            => NULL,
                                'ref_bill_no'              => NULL,
                                'client_code'              => NULL,
                                'matter_code'              => NULL,
                                'initial_code'             => NULL,
                                'expense_type'             => NULL,
                                'expense_code'             => NULL,
                                'narration'                => $narration,
                                'realise_amount_inpocket'  => NULL,
                                'realise_amount_outpocket' => NULL,
                                'realise_amount_counsel'   => NULL,
                                'gross_amount'             => $_REQUEST['gross_amount'],
                                'tax_amount'               => NULL,
                                'net_amount'               => $_REQUEST['gross_amount'],
                                'dr_cr_ind'                => 'C',
                                'deficit_amount_inpocket'  => NULL,
                                'deficit_amount_outpocket' => NULL,
                                'deficit_amount_counsel'   => NULL,
                                'part_full_ind'            => NULL,
                            );

                $ins_voucherDtl = $voucherDtlObj->insert($array);

                //--- Insert Record (PV) : VOUCHER_HEADER table
                $doc_type = 'PV';
                $array = array( 'serial_no'             => '',
                            'branch_code'           => $branch_code,
                            'entry_date'            => date_conv($_REQUEST['memo_date']),
                            'trans_type'            => 'CM',
                            'voucher_type'          => $doc_type,
                            'payee_payer_type'      => 'E',
                            'payee_payer_code'      => strtoupper($_REQUEST['employee_id']),
                            'payee_payer_name'      => $_REQUEST['employee_name'],
                            'payment_type'          => 'N',
                            'daybook_code'          => NULL,
                            'instrument_type'       => NULL,
                            'instrument_no'         => NULL,
                            'instrument_dt'         => NULL,
                            'bank_name'             => NULL,
                            'gross_amount'          => $_REQUEST['gross_amount'],
                            'tax_code'              => $_REQUEST['tax_code'],
                            'tax_amount'            => $_REQUEST['tax_amount'],
                            'net_amount'            => $_REQUEST['net_amount'],
                            'remarks'               => $narration,
                            'status_code'           => 'A',
                            'ref_ledger_serial_no'  => NULL,
                            'ref_jv_serial_no'      => NULL,
                            'ref_advance_serial_no' => NULL,
                            'link_jv_serial_no'     => $voucherSerial,
                            'prepared_by'           => $global_userid,
                            'prepared_on'           => $global_sysdate,
                            'passed_by'             => NULL,
                            'passed_on'             => NULL,
                            'paid_by'               => NULL,
                            'paid_on'               => NULL,
                            );

                $ins_voucherHdr = $voucherHdrObj->insert($array);
                $voucherPVSerial = $this->db->insertID();

                //--- Insert Record (PV) : VOUCHER_DETAIL table
                $where         = "control_keycodes.key_code = '003'";
                $controlArray = $this->db->query("SELECT control_keycodes.* from control_keycodes where $where")->getRowArray();

                $main_ac_code = $controlArray['key_value'];
                $i = 1;
                $array = array( 'ref_voucher_serial_no'    => $voucherPVSerial,
                            'row_no'                   => $i,
                            'main_ac_code'             => $main_ac_code,
                            'sub_ac_code'              => strtoupper($_REQUEST['employee_id']),
                            'ref_bill_year'            => NULL,
                            'ref_bill_no'              => NULL,
                            'client_code'              => NULL,
                            'matter_code'              => NULL,
                            'initial_code'             => NULL,
                            'expense_type'             => NULL,
                            'expense_code'             => NULL,
                            'narration'                => $narration,
                            'realise_amount_inpocket'  => NULL,
                            'realise_amount_outpocket' => NULL,
                            'realise_amount_counsel'   => NULL,
                            'gross_amount'             => $_REQUEST['gross_amount'],
                            'tax_amount'               => NULL,
                            'net_amount'               => $_REQUEST['gross_amount'],
                            'dr_cr_ind'                => 'D',
                            'deficit_amount_inpocket'  => NULL,
                            'deficit_amount_outpocket' => NULL,
                            'deficit_amount_counsel'   => NULL,
                            'part_full_ind'            => NULL,
                        );

                $ins_voucherDtl = $voucherDtlObj->insert($array);
                
                $i++;
                if ($_REQUEST['tax_amount'] > 0 ) {
                    
                    $tax_code = $_REQUEST['tax_code'];
                    $row = $this->db->query("SELECT tax_account_code,tax_sub_account_code FROM tax_master WHERE tax_code = '$tax_code'")->getRowArray();

                    $tax_account_code = $row['tax_account_code'];
                    $tax_sub_account_code = $row['tax_sub_account_code'];

                    $array = array( 'ref_voucher_serial_no'    => $voucherPVSerial,
                                'row_no'                   => $i,
                                'main_ac_code'             => $tax_account_code,
                                'sub_ac_code'              => $tax_sub_account_code,
                                'ref_bill_year'            => NULL,
                                'ref_bill_no'              => NULL,
                                'client_code'              => NULL,
                                'matter_code'              => NULL,
                                'initial_code'             => NULL,
                                'expense_type'             => NULL,
                                'expense_code'             => NULL,
                                'narration'                => $narration,
                                'realise_amount_inpocket'  => NULL,
                                'realise_amount_outpocket' => NULL,
                                'realise_amount_counsel'   => NULL,
                                'gross_amount'             => $_REQUEST['tax_amount'],
                                'tax_amount'               => NULL,
                                'net_amount'               => $_REQUEST['tax_amount'],
                                'dr_cr_ind'                => 'C',
                                'deficit_amount_inpocket'  => NULL,
                                'deficit_amount_outpocket' => NULL,
                                'deficit_amount_counsel'   => NULL,
                                'part_full_ind'            => NULL,
                            );
                    $ins_voucherDtl = $voucherDtlObj->insert($array);
                }

                //--- Update Record : COURT_EXPENSE table
                $array = array( 'ref_billinfo_serial_no' => NULL,
                                'ref_voucher_serial_no'  => $voucherPVSerial,
                                'ref_jv_serial_no'       => $voucherSerial,
                                );
                $associate_code = strtoupper($_REQUEST['employee_id']);
                $where = "court_expense.employee_id = '$employee_id' and court_expense.status_code = 'C' and (court_expense.ref_voucher_serial_no is NULL or court_expense.ref_voucher_serial_no = '0')";
                $court_expense = $courtExpenseObj->update($array, $where);
                
                if ($voucherPVSerial > 0) {
                    session()->setFlashdata('message', 'Please Note Generated Serial No. ['.$voucherPVSerial.']');
                } else {
                    session()->setFlashdata('message', 'Records Generation Failed !!');
                }
                return redirect()->to(session()->last_selected_end_menu);
                
            } else if($user_option == 'Delete') {
                $courtExpenseObj = $this->db->table("court_expense");
                $voucherHdrObj = $this->db->table("voucher_header");
                
                for($i=1; $i <= $arbitrator_rownum; $i++) {
                    if(!empty($_REQUEST['passed_amount'.$i])) {
                        $array = array('status_code'            => 'X',
                                       'deleted_by'             => $global_userid,
                                       'deleted_dt'             => $global_sysdate
                                      );
                        $where = "serial_no = '".$_REQUEST['serial_no'.$i]."'";
                        $court_expense = $courtExpenseObj->update($array, $where);
                    }
                }
                // voucher header
                $h_array = array('status_code' => 'X');
                $v_header = $voucherHdrObj->update($h_array, "serial_no = '".$ref_voucher_serial_no."'");

                if($v_header) {
                    session()->setFlashdata('message', 'Record Deleted Sucessfully !!');
                    return redirect()->to(session()->last_selected_end_menu);
                }
            }
           
            $xrxsum_qry = $this->db->query("select sum(amount) totamt from court_expense where branch_code = '$branch_code' and employee_id = '$employee_id' and exp_date = '$exp_date_ymd' and status_code = '$status_code' ")->getRowArray(); 
            $totamt = number_format($xrxsum_qry['totamt'], 2, '.', '') ; 

            // $retvalue = 'Y'.'|'.'Data Updated ....'.'|'.$totamt.'|'.$rowoptn.'|'.$description.'|'.$description2.'|'  ; 
            // echo $retvalue ;    
        }
        
        // if($status_code == '<font color=green>B</font>') $status_code = 'B';
        // else if($status_code == '<font color=red>C</font>') $status_code = 'C';

        $xerox_sql  = "select * from court_expense where branch_code = '$branch_code' and employee_id = '$employee_id' and exp_date = '$exp_date_ymd' and status_code = '$status_code' order by serial_no";
        $xerox_qry  = $this->db->query($xerox_sql)->getResultArray();
        $xerox_cnt  = count($xerox_qry);
        
        $xeroxtot_qry = $this->db->query("select sum(amount) amt from court_expense where branch_code = '$branch_code' and employee_id = '$employee_id' and exp_date = '$exp_date_ymd' and status_code = '$status_code' ")->getRowArray();
        $total_amount = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['amt'];  
        
        if($user_option != 'Add') {
            if ($xerox_cnt == 0) {
                session()->setFlashdata('message', 'No Records Found !!');
                return redirect()->to(session()->last_selected_end_menu);
            }
        }
        
        $params = [
            'branch' => $branch,
            'branch_code' => $branch_code,
            'employee_name' => $employee_name,
            'employee_id' => $employee_id,
            'ref_voucher_serial_no' => $ref_voucher_serial_no
        ];
        return view("pages/OtherExpenses/court_expenses", compact('user_option', 'params', 'data', 'displayId', 'params_opt', 'exp_date', 'redv', 'total_amount', 'status_code', 'selemode', 'pan_no', 'sele_qry', 'xerox_qry', 'res', 'row_num'));
    }
    
    public function photocopy_expenses() {
        $global_userid = $user_id = session()->userId;
        $data = branches($user_id);
        $data['requested_url'] = session()->requested_end_menu_url;

        $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $selemode = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:null;

        $displayId = ['agency_help_id' => '4311', 'matter_help_id' => '4540', 'ratecd_help_id' => '4544']; 
        $branch = $this->db->query(session()->branch_selection_stmt)->getResultArray();
        $supplier_code = $supplier_name = '';

        $branch_qry  = $this->db->query(session()->branch_selection_stmt)->getResultArray();
        $global_curr_finyear = session()->financialYear;

        $tax_qry     = $this->db->query("select a.tax_code,a.tax_name,b.tax_percent from tax_master a, tax_rate b where a.tax_type_code = 'T' and a.tax_code = b.tax_code and b.fin_year = '$global_curr_finyear' ")->getResultArray();

        $branch_code         = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:session()->user_qry['branch_code'] ;
        $supplier_code       = isset($_REQUEST['supplier_code'])?$_REQUEST['supplier_code']:null ;
        $supplier_name       = isset($_REQUEST['supplier_name'])?$_REQUEST['supplier_name']:null ;
        $pan_no              = isset($_REQUEST['pan_no'])?$_REQUEST['pan_no']:null ;
        $status_code         = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:null;
        $status_desc         = isset($_REQUEST['status_desc'])?$_REQUEST['status_desc']:null ;
        $exp_date            = isset($_REQUEST['exp_date'])?$_REQUEST['exp_date']:date('d-m-Y') ;
        $voucher_serial_no   = isset($_REQUEST['ref_voucher_serial_no'])?$_REQUEST['ref_voucher_serial_no']:0 ;
        $total_amount        = isset($_REQUEST['total_amount'])?$_REQUEST['total_amount']:0 ;
        $exp_date_ymd        = date_conv($exp_date); $expdate = $exp_date;
        $params_opt = []; $xerox_qry = []; $xerox_cnt = ''; $passed_amount = '';

        if ($selemode != 'Y') {
            // if($status_code != 'B') {
            //     session()->setFlashdata('message', 'Sorry!! Not Allowed since you have chosen record(s) having status other than EDITABLE !!');
            //     return redirect()->to(session()->last_selected_end_menu);
            // }
            $srlno = '' ; $rowoptn = '' ; $expdate = '' ; $expfor  = '' ; $matr_code = '' ;  $clnt_code = '' ; $narr = '' ;  $pages = '' ; $copies = '' ;  $ratecd = '' ;  $pgsize = '' ;  $pgside = '' ;  $exprate = '' ;  $expamt = '' ; 

            if ($user_option == 'Add') { 
                $status_code = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:'B';
                $redv = ''; $disv = ''; 

            } else if ($user_option == 'Edit') { 
                $status_code = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:'B';
                $redv = 'readonly'; $disv = 'disabled'; $selemode = 'Y'; 

            } else if ($user_option != 'Print') {
                $redv = 'readonly'; $disv = 'disabled';
                $status_code         = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:'A' ;

                $xerox_sql  = "select * from photocopy_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and exp_date = '$exp_date_ymd' and status_code = '$status_code' order by serial_no " ;
                $xerox_qry  = $this->db->query($xerox_sql)->getResultArray();
                $xerox_cnt  = count($xerox_qry);
    
                $xeroxtot_qry = $this->db->query("select sum(amount) tamt, sum(passed_amount) pamt from photocopy_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and exp_date = '$exp_date_ymd' and status_code = '$status_code' ")->getRowArray() ;
                $total_amount  = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['tamt'] ;  
                $passed_amount = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['pamt'] ;  
                $gross_amount  = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['pamt'] ;  
                $tax_amount    = number_format(0,2,'.','') ;  
                $net_amount    = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['pamt'] ;  
                $selemode = 'Y'; 

            } else {
                $redv = 'readonly'; $disv = 'disabled';
                $status_code         = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:'A' ;

                $xerox_sql  = "select * from photocopy_expense where ref_voucher_serial_no = '$voucher_serial_no' ";
                $xerox_qry  = $this->db->query($xerox_sql)->getResultArray();
                $xerox_cnt  = count($xerox_qry);
    
                $xeroxtot_qry = $this->db->query("select gross_amount,tax_code,tax_amount,net_amount from voucher_header where serial_no = '$voucher_serial_no'  ")->getRowArray();
                $passed_amount = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['gross_amount'] ;  
                $gross_amount  = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['gross_amount'] ;  
                $tax_code      = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['tax_code'] ;  
                $tax_amount    = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['tax_amount'] ;  
                $net_amount    = empty($xeroxtot_qry) ? '' : $xeroxtot_qry['net_amount'] ; 
                $selemode = 'Y'; 
            }

            $params_opt = [
                'srlno' => $srlno, 'rowoptn' => $rowoptn, 'expdate' => $expdate, 'expfor'  => $expfor, 'matr_code' => $matr_code, 'clnt_code' => $clnt_code, 'narr' => $narr, 
                'pages' => $pages,  'copies' => $copies,  'ratecd' => $ratecd,  'pgsize' => $pgsize,  'pgside' => $pgside, 'exprate' => $exprate, 'expamt' => $expamt,
            ];

        } else {
   
            if($user_option == 'Approve' && $status_code != 'A') {
                session()->setFlashdata('message', 'Sorry!! Not Allowed since you have chosen record(s) having status other than APPROVABLE !!');
                return redirect()->to(session()->last_selected_end_menu);
                
            } else if($user_option == 'Generate' && $status_code != 'B') { 
                session()->setFlashdata('message', 'Sorry!! Not Allowed since you have chosen record(s) having status other than GENERATABLE !!');
                return redirect()->to(session()->last_selected_end_menu);

            } else if($user_option == 'Print' && $status_code != 'C') { 
                session()->setFlashdata('message', 'Sorry!! Not Allowed since you have chosen record(s) having status other than PRINTATABLE !!');
                return redirect()->to(session()->last_selected_end_menu);
            } 
            
            $redv = 'readonly' ; $disv = 'disabled' ;
            $status_code = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:'B';
            $exp_date = isset($_REQUEST['expdate'])?$_REQUEST['expdate']:date('d-m-Y');

            $exp_for = isset($_REQUEST['expfor'])?$_REQUEST['expfor']:null ; 
            $matter_code = isset($_REQUEST['matr_code'])?$_REQUEST['matr_code']:null ;
            $client_code = isset($_REQUEST['clnt_code'])?$_REQUEST['clnt_code']:null ;
            $description = isset($_REQUEST['narr'])?$_REQUEST['narr']:null ;
            $page_no = isset($_REQUEST['pages'])?$_REQUEST['pages']:null ;
            $copy_no = isset($_REQUEST['copies'])?$_REQUEST['copies']:null ;
            $rate_code = isset($_REQUEST['ratecd'])?$_REQUEST['ratecd']:null ;
            $page_size = isset($_REQUEST['pgsize'])?$_REQUEST['pgsize']:null ;
            $page_side = isset($_REQUEST['pgside'])?$_REQUEST['pgside']:null ;
            $rate = isset($_REQUEST['exprate'])?$_REQUEST['exprate']:null ;
            $amount = isset($_REQUEST['expamt'])?$_REQUEST['expamt']:null ;
            $serial_no = isset($_REQUEST['srlno'])?$_REQUEST['srlno']:null ;
            $rowoptn = isset($_REQUEST['rowoptn'])?$_REQUEST['rowoptn']:null ;

            $photoCopyExpenseObj = $this->db->table("photocopy_expense");
            
            if ($serial_no == '' && $user_option == 'Add') {
                $status_code = 'B';
                $array = array('serial_no'              => '',
                                'branch_code'            => $branch_code,
                                'exp_date'               => date_conv($exp_date),
                                'supplier_code'          => $supplier_code,
                                'pan_no'                 => $pan_no,
                                'description'            => $description,
                                'exp_for'                => $exp_for,
                                'matter_code'            => $matter_code,
                                'client_code'            => $client_code,
                                'page_no'                => $page_no,
                                'copy_no'                => $copy_no,
                                'page_size'              => $page_size,
                                'page_side'              => $page_side,
                                'rate_code'              => $rate_code,
                                'rate'                   => $rate,
                                'amount'                 => $amount,
                                'passed_amount'          => $amount,
                                'ref_billinfo_serial_no' => NULL,
                                'ref_voucher_serial_no'  => NULL,
                                'ref_jv_serial_no'       => NULL,
                                'status_code'            => 'B',
                                'prepared_by'            => $global_userid,
                                'prepared_on'            => date_conv(date('d-m-Y')),
                                'passed_by'              => $global_userid,
                                'passed_dt'              => date_conv(date('d-m-Y'))
                                );
                $photoCopyExpenseObj->insert($array);

            } else if ($serial_no != '' && $rowoptn == 'Edit') {
                $status_code = 'B';
                $array = array('branch_code'            => $branch_code,
                                'exp_date'               => date_conv($exp_date),
                                'supplier_code'          => $supplier_code,
                                'pan_no'                 => $pan_no,
                                'description'            => $description,
                                'exp_for'                => $exp_for,
                                'matter_code'            => $matter_code,
                                'client_code'            => $client_code,
                                'page_no'                => $page_no,
                                'copy_no'                => $copy_no,
                                'page_size'              => $page_size,
                                'page_side'              => $page_side,
                                'rate_code'              => $rate_code,
                                'rate'                   => $rate,
                                'amount'                 => $amount,
                                'passed_amount'          => $amount,
                                'ref_billinfo_serial_no' => NULL,
                                'ref_voucher_serial_no'  => NULL,
                                'ref_jv_serial_no'       => NULL,
                                'status_code'            => 'B',
                                'prepared_by'            => $global_userid,
                                'prepared_on'            => date_conv(date('d-m-Y')),
                                'passed_by'              => $global_userid,
                                'passed_dt'              => date_conv(date('d-m-Y'))
                                );
                $where = "serial_no = '".$serial_no."'";
                $photoCopyExpenseObj->update($array, $where);

            } else if ($serial_no != '' && $rowoptn == 'Delete') {
                $status_code = 'B';
                $where = "serial_no = '".$serial_no."'";
                $photoCopyExpenseObj->delete($where);
            } else if ($user_option == 'Generate') {
                $gross_amount = isset($_REQUEST['gross_amount'])?$_REQUEST['gross_amount']:0 ;
                $exp_date = isset($_REQUEST['exp_date'])?$_REQUEST['exp_date']: date('d-m-Y');
                $tax_amount = isset($_REQUEST['tax_amount'])?$_REQUEST['tax_amount']:0 ;
                $net_amount = isset($_REQUEST['net_amount'])?$_REQUEST['net_amount']:0 ;
                $tax_code = isset($_REQUEST['tax_code'])?$_REQUEST['tax_code']:null ;
                $status_code = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:NULL ;
                $voucher_serial_no = isset($_REQUEST['voucher_serial_no'])?$_REQUEST['voucher_serial_no']:0 ;
                $exp_date_ymd = date_conv($exp_date);

               $trans_type   = 'PE' ;
               $narration    = 'PHOTOCOPY EXPENSES FOR '.$exp_date ;

               //---- Database Connection 
                $voucherHeaderObj = $this->db->table("voucher_header");
                $voucherDetailObj = $this->db->table("voucher_detail");

               
               //---- Controlling Values
               $res = $this->db->query("select key_value from control_keycodes where key_code = '017'")->getRowArray();
               $daybook_code = empty($res) ? '' : $res['key_value'];
               
               $res = $this->db->query("select key_desc,key_value from control_keycodes where key_code = '019'")->getRowArray();
               $agency_ac_code     = empty($res) ? '' : $res['key_value'];
               $agency_sub_ac_code = $supplier_code ;
               
               $res = $this->db->query("select key_desc,key_value from control_keycodes where key_code = '023'")->getRowArray();
               $xerox_ac_code     = empty($res) ? '' : $res['key_value'];
               $xerox_sub_ac_code = '';
                 
               $res = $this->db->query("select tax_account_code,tax_sub_account_code from tax_master where tax_code = '$tax_code'")->getRowArray();
               $tax_ac_code     = empty($res) ? '' : $res['tax_account_code'];
               $tax_sub_ac_code = empty($res) ? '' : $res['tax_sub_account_code'];

               //---- Selection of Approved Data for the selcted Branch/Supplier/Date
              $xerox_sql  = "select a.*, b.initial_code from photocopy_expense a left outer join fileinfo_header b on b.matter_code = a.matter_code  where a.branch_code = '$branch_code' and a.supplier_code = '$supplier_code' and a.exp_date = '$exp_date_ymd' and a.status_code = 'B' order by a.serial_no " ; 
               $xerox_qry  = $this->db->query($xerox_sql)->getResultArray(); 
               $xerox_cnt  = count($xerox_qry);
               $pan_row    = $xerox_qry[0];	 
               $pan_no     = $pan_row['pan_no'] ; 
               
               $supplier_pan_sql  = "select pan_no from supplier_master where supplier_code = '$supplier_code' " ; 
               $supplier_pan_qry  = $this->db->query($supplier_pan_sql)->getResultArray();
               $supplier_pan_cnt  = count($supplier_pan_qry);
               $supplier_pan = $supplier_pan_qry[0]['pan_no'] ; 
 
               //-- JV Record (Header)	 
               $h_array = array('serial_no'             => '',
                                'branch_code'           => $branch_code,
                                'entry_date'            => date_conv($exp_date),
                                'trans_type'            => $trans_type,
                                'voucher_type'          => 'JV',
                                'payee_payer_type'      => NULL,
                                'payee_payer_code'      => NULL,
                                'payee_payer_name'      => NULL,
                                'received_from'         => NULL,
                                   'payment_type'          => NULL,
                                'daybook_code'          => $daybook_code,
                                'instrument_type'       => NULL,
                                'instrument_no'         => NULL,
                                'instrument_dt'         => NULL,
                                'bank_name'             => NULL,
                                'gross_amount'          => $gross_amount,
                                'tax_code'              => NULL,
                                'tax_amount'            => 0,
                                'net_amount'            => $gross_amount,
                                'remarks'               => NULL,
                                'status_code'           => 'C',
                                'ref_ledger_serial_no'  => NULL,
                                'ref_jv_serial_no'      => NULL,
                                'ref_advance_serial_no' => NULL,
                                'link_jv_serial_no'     => NULL,
                                'prepared_by'           => $global_userid,
                                'prepared_on'           => date_conv(date('d-m-Y'))
                            );
               $voucherHeaderObj->insert($h_array);
               $jv_serial_no = $this->db->insertID();
          
               //-- JV Record (Detail)	
                $row_no = 0;
                foreach($xerox_qry as $xerox_row) {
                    $row_no++;
                    $d_array = array('ref_voucher_serial_no'     => $jv_serial_no,
                                   'row_no'                    => $row_no,
                                   'main_ac_code'              => $xerox_ac_code,
                                   'sub_ac_code'               => $xerox_sub_ac_code,
                                   'ref_bill_year'             => NULL,
                                   'ref_bill_no'               => NULL,
                                   'client_code'               => $xerox_row['client_code'],
                                   'matter_code'               => $xerox_row['matter_code'],
                                   'initial_code'              => $xerox_row['initial_code'],
                                   'expense_type'              => NULL,
                                   'expense_code'              => NULL,
                                   'narration'                 => $xerox_row['description'],
                                   'realise_amount_inpocket'   => NULL,
                                   'realise_amount_outpocket'  => NULL,
                                   'realise_amount_counsel'    => NULL,
                                   'gross_amount'              => $xerox_row['passed_amount'],
                                   'tax_amount'                => 0,
                                   'net_amount'                => $xerox_row['amount'],
                                   'dr_cr_ind'                 => 'D',
                                   'deficit_amount_inpocket'   => NULL,
                                   'deficit_amount_outpocket'  => NULL,
                                   'deficit_amount_counsel'    => NULL,
                                   'part_full_ind'             => NULL
                                );
                    $voucherDetailObj->insert($d_array);
               }

               $row_no++ ;
               $d_array = array('ref_voucher_serial_no'     => $jv_serial_no,
                                'row_no'                    => $row_no,
                                'main_ac_code'              => $agency_ac_code,
                                'sub_ac_code'               => $agency_sub_ac_code,
                                'ref_bill_year'             => NULL,
                                'ref_bill_no'               => NULL,
                                'client_code'               => NULL,
                                'matter_code'               => NULL,
                                'initial_code'              => NULL,
                                'expense_type'              => NULL,
                                'expense_code'              => NULL,
                                'narration'                 => $narration,
                                'realise_amount_inpocket'   => NULL,
                                'realise_amount_outpocket'  => NULL,
                                'realise_amount_counsel'    => NULL,
                                'gross_amount'              => $gross_amount,
                                'tax_amount'                => 0,
                                'net_amount'                => $gross_amount,
                                'dr_cr_ind'                 => 'C',
                                // 'deficit_amount_inpcoket'   => NULL,
                                // 'deficit_amount_outpcoket'  => NULL,
                                'deficit_amount_counsel'    => NULL,
                                'part_full_ind'             => NULL
                            );
               $voucherDetailObj->insert($d_array);
               
               //-- PV Record (Header)	 
               $h_array = array('serial_no'             => '',
                                'branch_code'           => $branch_code,
                                'entry_date'            => date_conv($exp_date),
                                'trans_type'            => $trans_type,
                                'voucher_type'          => 'PV',
                                'payee_payer_type'      => 'S',
                                'payee_payer_code'      => $supplier_code,
                                'payee_payer_name'      => $supplier_name,
                                'pan_no'                => $pan_no,
                                'received_from'         => NULL,
                                   'payment_type'          => 'N',
                                'daybook_code'          => NULL,
                                'instrument_type'       => NULL,
                                'instrument_no'         => NULL,
                                'instrument_dt'         => NULL,
                                'bank_name'             => NULL,
                                'gross_amount'          => $gross_amount,
                                'tax_code'              => $tax_code,
                                'tax_amount'            => $tax_amount,
                                'net_amount'            => $net_amount,
                                'remarks'               => NULL,
                                'status_code'           => 'A',
                                'ref_ledger_serial_no'  => NULL,
                                'ref_jv_serial_no'      => NULL,
                                'ref_advance_serial_no' => NULL,
                                'link_jv_serial_no'     => $jv_serial_no,
                                'prepared_by'           => $global_userid,
                                'prepared_on'           => date_conv(date('d-m-Y'))
                            );
               $voucherHeaderObj->insert($h_array);
               $pv_serial_no = $this->db->insertID();
               
               //-- PV Record (Detail)	 
               $row_no = 1 ;
               $d_array = array('ref_voucher_serial_no'     => $pv_serial_no,
                                'row_no'                    => $row_no,
                                'main_ac_code'              => $agency_ac_code,
                                'sub_ac_code'               => $agency_sub_ac_code,
                                'ref_bill_year'             => NULL,
                                'ref_bill_no'               => NULL,
                                'client_code'               => NULL,
                                'matter_code'               => NULL,
                                'initial_code'              => NULL,
                                'narration'                 => $narration,
                                'realise_amount_inpocket'   => NULL,
                                'realise_amount_outpocket'  => NULL,
                                'realise_amount_counsel'    => NULL,
                                'gross_amount'              => $gross_amount,
                                'tax_amount'                => 0,
                                'net_amount'                => $gross_amount,
                                'dr_cr_ind'                 => 'D',
                                'deficit_amount_inpocket'   => NULL,
                                'deficit_amount_outpocket'  => NULL,
                                'deficit_amount_counsel'    => NULL,
                                'part_full_ind'             => NULL
                            );
               $voucherDetailObj->insert($d_array);

               if($tax_amount > 0) { 	 
                 $row_no++ ;
                 $d_array = array('ref_voucher_serial_no'     => $pv_serial_no,
                                  'row_no'                    => $row_no,
                                  'main_ac_code'              => $tax_ac_code,
                                  'sub_ac_code'               => $tax_sub_ac_code,
                                  'ref_bill_year'             => NULL,
                                  'ref_bill_no'               => NULL,
                                  'client_code'               => NULL,
                                  'matter_code'               => NULL,
                                  'initial_code'              => NULL,
                                  'narration'                 => $narration,
                                  'realise_amount_inpocket'   => NULL,
                                  'realise_amount_outpocket'  => NULL,
                                  'realise_amount_counsel'    => NULL,
                                  'gross_amount'              => $tax_amount,
                                  'tax_amount'                => 0,
                                  'net_amount'                => $tax_amount,
                                  'dr_cr_ind'                 => 'C',
                                //   'deficit_amount_inpocket'   => NULL,
                                //   'deficit_amount_outpocket'  => NULL,
                                  'deficit_amount_counsel'    => NULL,
                                  'part_full_ind'             => NULL
                                 );
                    $voucherDetailObj->insert($d_array);
               }
          
               //-- Photocopy Record 	 
               $xerox_sql  = "select a.* from photocopy_expense a where a.branch_code = '$branch_code' and a.supplier_code = '$supplier_code' and a.exp_date = '$exp_date_ymd' and a.status_code = 'B' order by a.serial_no " ;
               $xerox_qry  = $this->db->query($xerox_sql)->getResultArray();

                foreach($xerox_qry as $xerox_row) {
                  $array = array('ref_voucher_serial_no' => $pv_serial_no,
                                 'ref_jv_serial_no'      => $jv_serial_no,
                                 'status_code'           => 'C' 
                                );
                  $where = "serial_no = '".$xerox_row['serial_no']."'";
                  $photoCopyExpenseObj->update($array, $where);
               }  
               
               if($pv_serial_no != 0) {
                   session()->setFlashdata('message', 'Please Note Voucher Serial No. [' . $pv_serial_no . ']');
                   return redirect()->to(session()->last_selected_end_menu);
               }
            } else if ($user_option == 'Delete') {
               $xerox_sql  = "delete from photocopy_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and exp_date = '$exp_date_ymd' and status_code = '$status_code' " ;
               $this->db->query($xerox_sql);

               session()->setFlashdata('message', 'Record Deleted Sucessfully !!');
               return redirect()->to(session()->last_selected_end_menu);

            } else if ($user_option == 'Print') {
                $print = true; $response = $this->common_print_expenses($voucher_serial_no, $user_id, $status_code);
                
                if(!is_object($response)) {
                    return view("pages/OtherExpenses/photocopy_expenses", ['print' => $print, "data" => $data, "displayId" => $displayId, 'params' => $response['params'], 'user_option' => $user_option]);
                } else return $response;
            }

            $xrxsum_qry = $this->db->query("select sum(amount) totamt from photocopy_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and exp_date = '$exp_date_ymd' and status_code = '$status_code' ")->getRowArray() ; 
            $totamt = number_format($xrxsum_qry['totamt'], 2, '.', '') ; 

            // $retvalue = 'Y'.'|'.'Data Updated ....'.'|'.$totamt.'|'.$rowoptn.'|'.$description.'|' ; 
            // echo $retvalue ;  
        }

        if($user_option != 'Add') {
            $xerox_sql  = "select * from photocopy_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and exp_date = '$exp_date_ymd' and status_code = '$status_code' order by serial_no " ;
            $xerox_qry  = $this->db->query($xerox_sql)->getResultArray();
            $xerox_cnt  = count($xerox_qry);
    
            $xeroxtot_qry = $this->db->query("select sum(amount) amt from photocopy_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and exp_date = '$exp_date_ymd' and status_code = '$status_code' ")->getRowArray();
            $total_amount = $xeroxtot_qry['amt'] ;  
    
            if ($xerox_cnt == 0) {
                session()->setFlashdata('message', 'No Records Found !!');
                return redirect()->to(session()->last_selected_end_menu);
            }
        }

        $params = [
            'supplier_name' => $supplier_name,
            'supplier_code' => $supplier_code,
            'voucher_serial_no' => $voucher_serial_no,
        ];
        return view("pages/OtherExpenses/photocopy_expenses", compact('data', 'displayId', 'params', 'params_opt', 'user_option', 'exp_date', 'redv', 'total_amount', 'passed_amount', 'status_code', 'selemode', 'xerox_qry', 'pan_no', 'xerox_cnt'));
    } 

    public function courier_expenses() {
        $global_userid = $user_id = session()->userId;
        $data = branches($user_id); $global_sysdate = date_conv(date('d-m-Y'));
        $data['requested_url'] = session()->requested_end_menu_url;

        $branch_code = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:session()->user_qry['branch_code'];
        $supplier_code = isset($_REQUEST['supplier_code'])?$_REQUEST['supplier_code']:null ;
        $supplier_name = isset($_REQUEST['supplier_name'])?$_REQUEST['supplier_name']:null ;
        $status_code = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:'A' ;
        $status_desc = isset($_REQUEST['status_desc'])?$_REQUEST['status_desc']:null ;
        $exp_date = isset($_REQUEST['exp_date'])?$_REQUEST['exp_date']:date('d-m-Y') ;
        $pan_no = isset($_REQUEST['pan_no'])?$_REQUEST['pan_no']:null ;
        $voucher_serial_no = isset($_REQUEST['ref_voucher_serial_no'])?$_REQUEST['ref_voucher_serial_no']:0 ;
        $total_amount = isset($_REQUEST['total_amount'])?$_REQUEST['total_amount']:0 ;
        $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $selemode = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:null;
        
        $exp_date_ymd = date_conv($exp_date); $courier_cnt = '';
        $global_curr_finyear = session()->financialYear;
        

        $displayId = ['agency_help_id' => '4102', 'matter_help_id' => '4540', 'ratecd_help_id' => '4545', 'emplid_help_id' => '4546']; 

        $branch_qry  = $this->db->query(session()->branch_selection_stmt)->getResultArray();

        $posttype_qry = $this->db->query("select * from code_master where type_code = '003'")->getResultArray();
        $courier_qry = []; $passed_amount = 0; $tax_qry = []; $tax_amount = $net_amount = $gross_amount = $tax_code = 0;

        $edit_confirm = isset($_REQUEST['edit_confirm']) ? $_REQUEST['edit_confirm'] : null;

        if ($selemode != 'Y') {
            // if($status_code != 'A') {
            //     session()->setFlashdata('message', 'Sorry!! Not Allowed since you have chosen record(s) having status other than EDITABLE !!');
            //     return redirect()->to(session()->last_selected_end_menu);
            // } 
            
            $srlno = ''; $rowoptn = ''; $notedate = ''; $notetime = ''; $noteno = '';  $expfor = ''; $matr_code = '';  $matr_desc = '';  $clnt_code = ''; 
            $clnt_name = ''; $consgname = ''; $consgadr1 = ''; $consgadr2 = ''; $consgadr3 = ''; $city = ''; $pincode = '';  $state = '';  $country = ''; 
            $posttype = ''; $ratecd = ''; $exprate = ''; $expamt = ''; $refletrno = ''; $empid = ''; $empname = ''; $remks = '';

            if ($user_option == 'Add') {
                $redv = ''; $disv = ''; $notedate = $exp_date;
                
            } else if ($user_option == 'Edit') {
                $redv = 'readonly'; $disv = 'disabled'; $notedate = $exp_date;
                $selemode = 'Y'; 

            } else if ($user_option != 'Print') {
                $redv = 'readonly'; $disv = 'disabled';
                $courier_sql  = "select * from courier_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and consignment_note_date = '$exp_date_ymd' and status_code = '$status_code' order by serial_no " ;
                $courier_qry  = $this->db->query($courier_sql)->getResultArray();
                $courier_cnt  = count($courier_qry);

                $courrtot_qry  = $this->db->query("select sum(amount) tamt, sum(passed_amount) pamt from courier_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and consignment_note_date = '$exp_date_ymd' and status_code = '$status_code' ")->getRowArray();
                $total_amount  = empty($courrtot_qry) ? '' : $courrtot_qry['tamt'] ;  
                $passed_amount = empty($courrtot_qry) ? '' : $courrtot_qry['pamt'] ;  
                $gross_amount  = empty($courrtot_qry) ? '' : $courrtot_qry['pamt'] ;  
                $tax_amount    = number_format(0,2,'.','') ;  
                $net_amount    = empty($courrtot_qry) ? '' : $courrtot_qry['pamt'] ;   
                $tax_qry = $this->db->query("select a.tax_code,a.tax_name,b.tax_percent from tax_master a, tax_rate b where a.tax_type_code = 'T' and a.tax_code = b.tax_code and b.fin_year = '$global_curr_finyear' ")->getResultArray();
                $selemode = 'Y';

            } else {
                $redv = 'readonly'; $disv = 'disabled';
                $courier_sql  = "select * from courier_expense where ref_voucher_serial_no = '$voucher_serial_no' ";
                $courier_qry  = $this->db->query($courier_sql)->getResultArray();
                $courier_cnt  = count($courier_qry);
                
                $courrtot_qry  = $this->db->query("select gross_amount,tax_code,tax_amount,net_amount from voucher_header where serial_no = '$voucher_serial_no'  ")->getRowArray();
                $passed_amount = empty($courrtot_qry) ? '' : $courrtot_qry['gross_amount'] ;  
                $gross_amount  = empty($courrtot_qry) ? '' : $courrtot_qry['gross_amount'] ;  
                $tax_code      = empty($courrtot_qry) ? '' : $courrtot_qry['tax_code'] ;  
                $tax_amount    = empty($courrtot_qry) ? '' : $courrtot_qry['tax_amount'] ;  
                $net_amount    = empty($courrtot_qry) ? '' : $courrtot_qry['net_amount'] ; 
                $tax_qry = $this->db->query("select a.tax_code,a.tax_name,b.tax_percent from tax_master a, tax_rate b where a.tax_type_code = 'T' and a.tax_code = b.tax_code and b.fin_year = '$global_curr_finyear' ")->getResultArray();
                $selemode = 'Y';
            
            }
            
        } else {
            
            // if($user_option == 'Approve' && $status_code != 'A') { 
            //     session()->setFlashdata('message', 'Sorry!! Not Allowed since you have chosen record(s) having status other than APPROVABLE !!');
            //     return redirect()->to(session()->last_selected_end_menu);
                
            // } else if($user_option == 'Generate' && $status_code != 'B') { 
            //     session()->setFlashdata('message', 'Sorry!! Not Allowed since you have chosen record(s) having status other than GENERATABLE !!');
            //     return redirect()->to(session()->last_selected_end_menu);

            // } else if($user_option == 'Print' && $status_code != 'C') {
            //     session()->setFlashdata('message', 'Sorry!! Not Allowed since you have chosen record(s) having status other than PRINTATABLE !!');
            //     return redirect()->to(session()->last_selected_end_menu);
            // }
   
            $redv = 'readonly' ; $disv = 'disabled' ;
            $gross_amount = isset($_REQUEST['gross_amount'])?$_REQUEST['gross_amount']:0 ;
            $tax_amount = isset($_REQUEST['tax_amount'])?$_REQUEST['tax_amount']:0 ;
            $net_amount = isset($_REQUEST['net_amount'])?$_REQUEST['net_amount']:0 ;
            $tax_code = isset($_REQUEST['tax_code'])?$_REQUEST['tax_code']:null;
            $exp_date_ymd = date_conv($exp_date);
            $notedate = $exp_date ;

            $serial_no             = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null ;
            $rowoptn               = isset($_REQUEST['rowoptn'])?$_REQUEST['rowoptn']:null ;
            $consignment_note_date = isset($_REQUEST['notedate'])?$_REQUEST['notedate']:null ;
            $consignment_note_time = isset($_REQUEST['notetime'])?$_REQUEST['notetime']:null ;
            $consignment_note_no   = isset($_REQUEST['noteno'])?$_REQUEST['noteno']:null ;
            $exp_for               = isset($_REQUEST['expfor'])?$_REQUEST['expfor']:null ; 
            $matter_code           = isset($_REQUEST['matr_code'])?$_REQUEST['matr_code']:null ;
            $client_code           = isset($_REQUEST['clnt_code'])?$_REQUEST['clnt_code']:null ;
            $consignee_name        = isset($_REQUEST['consgname'])?$_REQUEST['consgname']:null ;
            $address_line_1        = isset($_REQUEST['consgadr1'])?$_REQUEST['consgadr1']:null ;
            $address_line_2        = isset($_REQUEST['consgadr2'])?$_REQUEST['consgadr2']:null ;
            $address_line_3        = isset($_REQUEST['consgadr3'])?$_REQUEST['consgadr3']:null ;
            $city                  = isset($_REQUEST['city'])?$_REQUEST['city']:null ;
            $pin_code              = isset($_REQUEST['pincode'])?$_REQUEST['pincode']:null ;
            $state_name            = isset($_REQUEST['state'])?$_REQUEST['state']:null ;
            $country               = isset($_REQUEST['country'])?$_REQUEST['country']:null ;
            $letter_post_type      = isset($_REQUEST['posttype'])?$_REQUEST['posttype']:null ;
            $rate_code             = isset($_REQUEST['ratecd'])?$_REQUEST['ratecd']:null ;
            $rate                  = isset($_REQUEST['exprate'])?$_REQUEST['exprate']:null ;
            $amount                = isset($_REQUEST['expamt'])?$_REQUEST['expamt']:null ;
            $ref_letter_no         = isset($_REQUEST['refletrno'])?$_REQUEST['refletrno']:null ;
            $employee_id           = isset($_REQUEST['empid'])?$_REQUEST['empid']:null ;
            $remarks               = isset($_REQUEST['remks'])?$_REQUEST['remks']:null ;
            $prepared_by           = session()->userId; 
            $prepared_on           = date_conv(date('d-m-Y'));
            $exp_date_ymd          = date_conv($exp_date);
            
            $courierExpenseObj = $this->db->table("courier_expense");

            if ($serial_no == '' && $user_option == 'Add') {
                $status_code = 'A';

                $array = array('serial_no' => '',
                                'branch_code'            => $branch_code,
                                'supplier_code'          => $supplier_code,
                                'consignment_note_date'  => date_conv($consignment_note_date),
                                'consignment_note_time'  => $consignment_note_time,
                                'consignment_note_no'    => $consignment_note_no,
                                'exp_for'                => $exp_for,
                                'matter_code'            => $matter_code,
                                'client_code'            => $client_code,
                                'consignee_name'         => $consignee_name,
                                'address_line_1'         => $address_line_1,
                                'address_line_2'         => $address_line_2,
                                'address_line_3'         => $address_line_3,
                                'city'                   => $city,
                                'pin_code'               => $pin_code,
                                'state_name'             => $state_name,
                                'country'                => $country,
                                'letter_post_type'       => $letter_post_type,
                                'rate_code'              => $rate_code,
                                'rate'                   => $rate,
                                'amount'                 => $amount,
                                'ref_letter_no'          => $ref_letter_no,
                                'employee_id'            => $employee_id,
                                'remarks'                => $remarks,
                                'passed_amount'          => NULL,
                                'pod_date'               => NULL,
                                'pod_time'               => NULL,
                                'pod_remarks'            => NULL,
                                'delivery_status'        => NULL,
                                'ref_billinfo_serial_no' => NULL,
                                'ref_voucher_serial_no'  => NULL,
                                'ref_jv_serial_no'       => NULL,
                                'status_code'            => $status_code,
                                'prepared_by'            => $prepared_by,
                                'prepared_on'            => $prepared_on,
                                'passed_by'              => NULL,
                                'passed_dt'              => NULL 
                                );
                
                $obj = $courierExpenseObj->insert($array); // echo $this->db->insertID(); die;
                    
            } else if ($serial_no != '' && $rowoptn == 'Edit') {
                $status_code = 'A';

                $array = array('branch_code'            => $branch_code,
                                'supplier_code'          => $supplier_code,
                                'consignment_note_date'  => date_conv($consignment_note_date),
                                'consignment_note_time'  => $consignment_note_time,
                                'consignment_note_no'    => $consignment_note_no,
                                'exp_for'                => $exp_for,
                                'matter_code'            => $matter_code,
                                'client_code'            => $client_code,
                                'consignee_name'         => $consignee_name,
                                'address_line_1'         => $address_line_1,
                                'address_line_2'         => $address_line_2,
                                'address_line_3'         => $address_line_3,
                                'city'                   => $city,
                                'pin_code'               => $pin_code,
                                'state_name'             => $state_name,
                                'country'                => $country,
                                'letter_post_type'       => $letter_post_type,
                                'rate_code'              => $rate_code,
                                'rate'                   => $rate,
                                'amount'                 => $amount,
                                'ref_letter_no'          => $ref_letter_no,
                                'employee_id'            => $employee_id,
                                'remarks'                => $remarks,
                                'passed_amount'          => NULL,
                                'pod_date'               => NULL,
                                'pod_time'               => NULL,
                                'pod_remarks'            => NULL,
                                'delivery_status'        => NULL,
                                'ref_billinfo_serial_no' => NULL,
                                'ref_voucher_serial_no'  => NULL,
                                'ref_jv_serial_no'       => NULL,
                                'status_code'            => $status_code,
                                'prepared_by'            => $prepared_by,
                                'prepared_on'            => $prepared_on,
                                'passed_by'              => NULL,
                                'passed_dt'              => NULL 
                                );
                $where = "serial_no = '".$serial_no."'";
                $courierExpenseObj->update($array, $where);

            } else if ($serial_no != '' && $rowoptn == 'Delete') {
                $status_code = 'A';
                $where = "serial_no = '".$serial_no."'";
                $courierExpenseObj->delete($where);

            } else if ($user_option == 'Generate') {
                
                $trans_type   = 'CC' ;
                $narration    = 'COURIER CHARGES FOR '.$exp_date ;
                     
                //---- Database Connection 
                $courierExpenseObj = $this->db->table("courier_expense");
                $voucherHeaderObj = $this->db->table("voucher_header");
                $voucherDetailObj = $this->db->table("voucher_detail");

                //---- Controlling Values
                $row = $this->db->query("select key_value from control_keycodes where key_code = '017'")->getRowArray();
                $daybook_code = empty($row) ? '' : $row['key_value'];

                $row = $this->db->query("select key_desc,key_value from control_keycodes where key_code = '019'")->getRowArray();
                $agency_ac_code     = empty($row) ? '' : $row['key_value'];
                $agency_sub_ac_code = $supplier_code ;

                $row = $this->db->query("select key_desc,key_value from control_keycodes where key_code = '009'")->getRowArray();
                $courier_ac_code = $row['key_value'];
                $courier_sub_ac_code = '';
    
                $row = $this->db->query("select tax_account_code,tax_sub_account_code from tax_master where tax_code = '$tax_code'")->getRowArray();
                $tax_ac_code     = empty($row) ? '' : $row['tax_account_code'];
                $tax_sub_ac_code = empty($row) ? '' : $row['tax_sub_account_code'];

                //---- Selection of Approved Data for the selcted Branch/Supplier/Date
                $courier_sql  = "select a.*, b.initial_code from courier_expense a left outer join fileinfo_header b on b.matter_code = a.matter_code where a.branch_code = '$branch_code' and a.supplier_code = '$supplier_code' and a.consignment_note_date = '$exp_date_ymd' and a.status_code = 'B' order by a.serial_no " ;
                $courier_qry  = $this->db->query($courier_sql)->getResultArray();
                $courier_cnt  = count($courier_qry);
               
                //-- JV Record (Header)	 
                $h_array = array('serial_no'                => '',
                                    'branch_code'           => $branch_code,
                                    'entry_date'            => date_conv($exp_date),
                                    'trans_type'            => $trans_type,
                                    'voucher_type'          => 'JV',
                                    'payee_payer_type'      => NULL,
                                    'payee_payer_code'      => NULL,
                                    'payee_payer_name'      => NULL,
                                    'received_from'         => NULL,
                                    'payment_type'          => NULL,
                                    'daybook_code'          => $daybook_code,
                                    'instrument_type'       => NULL,
                                    'instrument_no'         => NULL,
                                    'instrument_dt'         => NULL,
                                    'bank_name'             => NULL,
                                    'gross_amount'          => $gross_amount,
                                    'tax_code'              => NULL,
                                    'tax_amount'            => 0,
                                    'net_amount'            => $gross_amount,
                                    'remarks'               => NULL,
                                    'status_code'           => 'C',
                                    'ref_ledger_serial_no'  => NULL,
                                    'ref_jv_serial_no'      => NULL,
                                    'ref_advance_serial_no' => NULL,
                                    'link_jv_serial_no'     => NULL,
                                    'prepared_by'           => $global_userid,
                                    'prepared_on'           => $global_sysdate
                                );
                $voucherHeaderObj->insert($h_array);
                $jv_serial_no = $this->db->insertID();
               
                //-- JV Record (Detail)	
                $row_no = 0; 
                foreach($courier_qry as $courier_row) {
                    $row_no++ ;
                    $d_array = array('ref_voucher_serial_no'     => $jv_serial_no,
                                    'row_no'                    => $row_no,
                                    'main_ac_code'              => $courier_ac_code,
                                    'sub_ac_code'               => $courier_sub_ac_code,
                                    'ref_bill_year'             => NULL,
                                    'ref_bill_no'               => NULL,
                                    'client_code'               => $courier_row['client_code'],
                                    'matter_code'               => $courier_row['matter_code'],
                                    'initial_code'              => $courier_row['initial_code'],
                                    'expense_type'              => NULL,
                                    'expense_code'              => NULL,
                                    'narration'                 => 'COURIER CHARGES',
                                    'realise_amount_inpocket'   => NULL,
                                    'realise_amount_outpocket'  => NULL,
                                    'realise_amount_counsel'    => NULL,
                                    'gross_amount'              => $courier_row['passed_amount'],
                                    'tax_amount'                => 0,
                                    'net_amount'                => $courier_row['passed_amount'],
                                    'dr_cr_ind'                 => 'D',
                                    'deficit_amount_inpocket'   => NULL,
                                    'deficit_amount_outpocket'  => NULL,
                                    'deficit_amount_counsel'    => NULL,
                                    'part_full_ind'             => NULL
                                    );
                    $v_detail = $voucherDetailObj->insert($d_array);
                }
                    
                $row_no++ ;
                $d_array = array('ref_voucher_serial_no'     => $jv_serial_no,
                                    'row_no'                    => $row_no,
                                    'main_ac_code'              => $agency_ac_code,
                                    'sub_ac_code'               => $agency_sub_ac_code,
                                    'ref_bill_year'             => NULL,
                                    'ref_bill_no'               => NULL,
                                    'client_code'               => NULL,
                                    'matter_code'               => NULL,
                                    'initial_code'              => NULL,
                                    'expense_type'              => NULL,
                                    'expense_code'              => NULL,
                                    'narration'                 => $narration,
                                    'realise_amount_inpocket'   => NULL,
                                    'realise_amount_outpocket'  => NULL,
                                    'realise_amount_counsel'    => NULL,
                                    'gross_amount'              => $gross_amount,
                                    'tax_amount'                => 0,
                                    'net_amount'                => $gross_amount,
                                    'dr_cr_ind'                 => 'C',
                                //  'deficit_amount_inpcoket'   => NULL,
                                //  'deficit_amount_outpcoket'  => NULL,
                                    'deficit_amount_counsel'    => NULL,
                                    'part_full_ind'             => NULL
                                );
                $v_detail = $voucherDetailObj->insert($d_array);
            
                //-- PV Record (Header)	 
                $h_array = array('serial_no'             => '',
                                    'branch_code'           => $branch_code,
                                    'entry_date'            => date_conv($exp_date),
                                    'trans_type'            => $trans_type,
                                    'voucher_type'          => 'PV',
                                    'payee_payer_type'      => 'S',
                                    'payee_payer_code'      => $supplier_code,
                                    'payee_payer_name'      => $supplier_name,
                                    'received_from'         => NULL,
                                    'payment_type'          => 'N',
                                    'daybook_code'          => NULL,
                                    'instrument_type'       => NULL,
                                    'instrument_no'         => NULL,
                                    'instrument_dt'         => NULL,
                                    'bank_name'             => NULL,
                                    'gross_amount'          => $gross_amount,
                                    'tax_code'              => $tax_code,
                                    'tax_amount'            => $tax_amount,
                                    'net_amount'            => $net_amount,
                                    'remarks'               => NULL,
                                    'status_code'           => 'A',
                                    'ref_ledger_serial_no'  => NULL,
                                    'ref_jv_serial_no'      => NULL,
                                    'ref_advance_serial_no' => NULL,
                                    'link_jv_serial_no'     => $jv_serial_no,
                                    'prepared_by'           => $global_userid,
                                    'prepared_on'           => $global_sysdate
                                );
                $v_header = $voucherHeaderObj->insert($h_array);
            
                $pv_serial_no = $this->db->insertID();
                
                //-- PV Record (Detail)	 
                $row_no = 1 ;
                $d_array = array('ref_voucher_serial_no'     => $pv_serial_no,
                                    'row_no'                    => $row_no,
                                    'main_ac_code'              => $agency_ac_code,
                                    'sub_ac_code'               => $agency_sub_ac_code,
                                    'ref_bill_year'             => NULL,
                                    'ref_bill_no'               => NULL,
                                    'client_code'               => NULL,
                                    'matter_code'               => NULL,
                                    'initial_code'              => NULL,
                                    'narration'                 => $narration,
                                    'realise_amount_inpocket'   => NULL,
                                    'realise_amount_outpocket'  => NULL,
                                    'realise_amount_counsel'    => NULL,
                                    'gross_amount'              => $gross_amount,
                                    'tax_amount'                => 0,
                                    'net_amount'                => $gross_amount,
                                    'dr_cr_ind'                 => 'D',
                                    'deficit_amount_inpocket'   => NULL,
                                    'deficit_amount_outpocket'  => NULL,
                                    'deficit_amount_counsel'    => NULL,
                                    'part_full_ind'             => NULL
                                );
                $v_detail = $voucherDetailObj->insert($d_array);
                    
                if($tax_amount > 0) { 	 
                    $row_no++ ;
                    $d_array = array('ref_voucher_serial_no'     => $pv_serial_no,
                                    'row_no'                    => $row_no,
                                    'main_ac_code'              => $tax_ac_code,
                                    'sub_ac_code'               => $tax_sub_ac_code,
                                    'ref_bill_year'             => NULL,
                                    'ref_bill_no'               => NULL,
                                    'client_code'               => NULL,
                                    'matter_code'               => NULL,
                                    'initial_code'              => NULL,
                                    'narration'                 => $narration,
                                    'realise_amount_inpocket'   => NULL,
                                    'realise_amount_outpocket'  => NULL,
                                    'realise_amount_counsel'    => NULL,
                                    'gross_amount'              => $tax_amount,
                                    'tax_amount'                => 0,
                                    'net_amount'                => $tax_amount,
                                    'dr_cr_ind'                 => 'C',
                                    'deficit_amount_inpocket'   => NULL,
                                    'deficit_amount_outpocket'  => NULL,
                                    'deficit_amount_counsel'    => NULL,
                                    'part_full_ind'             => NULL
                                    );
                    $v_detail = $voucherDetailObj->insert($d_array);
                }
               
                //-- Courier Record 	 
                $courier_sql  = "select a.* from courier_expense a where a.branch_code = '$branch_code' and a.supplier_code = '$supplier_code' and a.consignment_note_date = '$exp_date_ymd' and a.status_code = 'B' order by a.serial_no " ;
                $courier_qry  = $this->db->query($courier_sql)->getResultArray();

                foreach($courier_qry as $courier_row) {
                    $array = array('ref_voucher_serial_no' => $pv_serial_no,
                                    'ref_jv_serial_no'      => $jv_serial_no,
                                    'status_code'           => 'C' ,
                                    'passed_by'             => $global_userid,
                                    'passed_dt'             => $global_sysdate					   
                                    );
                    $where = "serial_no = '".$courier_row['serial_no']."'";
                    $courier_expense = $courierExpenseObj->update($array,$where);
                }  
                
                if ($pv_serial_no != 0) {
                    session()->setFlashdata('message', 'Please Note Voucher Serial No. ['.$pv_serial_no.']');
                    return redirect()->to(session()->last_selected_end_menu);
                }

            } else if ($user_option == 'Print') {
                $print = true; $response = $this->common_print_expenses($voucher_serial_no, $user_id, $status_code);
                
                if(!is_object($response)) {
                    return view("pages/OtherExpenses/courier_expenses", ['print' => $print, "data" => $data, "displayId" => $displayId, 'params' => $response['params'], 'user_option' => $user_option]);
                } else return $response;
            } else if ($user_option == 'Approve') {
                $serial_no = isset($_REQUEST['serial_no0'])?$_REQUEST['serial_no0']:null ;
                $passed_amount = isset($_REQUEST['passed_amount0'])?$_REQUEST['passed_amount0']:null ;
                $updt_ind      = isset($_REQUEST['updt_ind'])?$_REQUEST['updt_ind']:null ;
                $rec_no        = isset($_REQUEST['rec_no'])?$_REQUEST['rec_no']:null ;
                
                $retvalue      = 'N'.'|'.'xxxx'.'|'.$rec_no.'|';
                // echo $serial_no; die;
                if($updt_ind == 'Y') {
                    $array = array('passed_amount' => $passed_amount,
                                    'status_code'  => 'B',
                                    'passed_by'    => $global_userid,
                                    // 'passed_on'    => $global_sysdate
                                );
                    $where = "serial_no = '".$serial_no."'";
                    $courier_expense = $courierExpenseObj->update($array, $where);
                    $retvalue = 'Y'.'|'.'Data Updated ....'.'|'.$rec_no.'|' ; 
                }

                if($courier_expense) {
                    session()->setFlashdata('message', $retvalue);
                    return redirect()->to(session()->last_selected_end_menu);
                }
            }

            $sql = "select SUM(amount) as totamt from courier_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and consignment_note_date = '$exp_date_ymd' and status_code = '$status_code' ";
            $crrsum_qry = $this->db->query($sql)->getRowArray(); 
            $totamt = number_format($crrsum_qry['totamt'], 2, '.', ''); 

            $retvalue = 'Y'.'|'.'Data Updated ....'.'|'.$totamt.'|'.$rowoptn.'|';

            $courier_sql  = "select * from courier_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and consignment_note_date = '$exp_date_ymd' and status_code = '$status_code' order by serial_no " ;
            $courier_qry  = $this->db->query($courier_sql)->getResultArray();
            $courier_cnt  = count($courier_qry);
            
            $xeroxtot_qry = $this->db->query("select sum(amount) amt from courier_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and consignment_note_date = '$exp_date_ymd' and status_code = '$status_code' ")->getRowArray();
            $total_amount = $xeroxtot_qry['amt'] ;  

            if ($courier_cnt == 0) {
                echo "hello"; die;
                session()->setFlashdata('message', 'No Records Found !!');
                return redirect()->to($this->requested_url());
            }
        }

        if($user_option != 'Add') {
            $courier_sql = "select * from courier_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and consignment_note_date = '$exp_date_ymd' and status_code = '$status_code' order by serial_no"  ;
            $courier_qry = $this->db->query($courier_sql)->getResultArray();
            $courier_cnt = count($courier_qry);

            $courrtot_qry = $this->db->query("select sum(amount) amt from courier_expense where branch_code = '$branch_code' and supplier_code = '$supplier_code' and consignment_note_date = '$exp_date_ymd' and status_code = '$status_code' ")->getRowArray();
            $total_amount = $courrtot_qry['amt'] ; 

            if ($courier_cnt == 0) {
                session()->setFlashdata('message', 'No Records Found !!');
                return redirect()->to(session()->requested_end_menu_url);
            }
        }

        $params = [
            "supplier_name" => $supplier_name,
            "supplier_code" => $supplier_code,
            "exp_date" => $exp_date,
            'voucher_serial_no' => $voucher_serial_no
        ];

        return view("pages/OtherExpenses/courier_expenses", compact('data', 'displayId', 'params', 'redv', 'courier_qry', 'courier_cnt', 'total_amount', 'passed_amount', 'tax_amount', 'net_amount', 'gross_amount', 'status_code', 'user_option', 'selemode', 'tax_qry', 'tax_code'));
    } 

    public function arbitrator_expenes() {
        session()->setFlashdata('message', 'Sorry!! This Operation is not allowed [Deprecated Page] !!');
        return redirect()->to(session()->last_selected_end_menu);
    } 

    public function stenographer_expenses() {

        $user_id = session()->userId;
        $current_fin_year = session()->financialYear;
        $date = date('d-m-Y');
        $data = branches($user_id);
        $data['requested_url'] = session()->requested_end_menu_url;
        $displayId = ['stenographer_help_id' => '4015', 'matter_help_id' => '4203'];
        $disp_heading = 'Stenographer Expenses';

        $ref_voucher_serial_no = isset($_REQUEST['ref_voucher_serial_no'])?$_REQUEST['ref_voucher_serial_no']:null;
        $user_option       = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        $status_code       = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:null;
        $associate_code    = isset($_REQUEST['associate_code'])?$_REQUEST['associate_code']:null;
        $associate_name    = isset($_REQUEST['associate_name'])?$_REQUEST['associate_name']:null;
        $selemode          = isset($_REQUEST['selemode'])?$_REQUEST['selemode']:null;
        
        $row_num = 0;
        $steno_expense_table = $this->db->table("steno_expense");

        if ($user_option == 'Add' )     { $redk = '' ;          $redv = '';          $disv = ''         ; $disb = ''         ;  $redve = '';         $redokadd = ''; }
        if ($user_option == 'Edit')     { $redk = '' ;          $redv = '';          $disv = ''         ; $disb = ''         ;  $redve = 'disabled'; $redokadd = 'readonly'; }
        if ($user_option == 'Approve')  { $redk = '' ;          $redv = 'readonly';  $disv = 'disabled' ; $disb = ''         ;  $redve = '';         $redokadd = 'readonly'; }
        if ($user_option == 'Generate') { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; $disb = 'disabled' ;  $redve = 'disabled'; $redokadd = 'readonly'; }
        if ($user_option == 'Voucher')  { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; $disb = 'disabled' ;  $redve = 'disabled'; $redokadd = 'readonly'; }

        // if($this->request->getMethod() == 'post') {
        if($selemode == 'Y') {
               
            $arbitrator_rownum = isset($_POST['arb_row_num'])?$_POST['arb_row_num']:null; 
            $row_counter = isset($_POST['row_counter'])?$_POST['row_counter']:null; 
            $branch_code = $_POST['branch_code_copy'];

            $counsel_memo_header_table = $this->db->table('counsel_memo_header');
            $code_master_table = $this->db->table("code_master"); 

            $control_keycodes_table = $this->db->table("control_keycodes");

            $ledger_trans_hdr_table = $this->db->table("ledger_trans_hdr");

            $ledger_trans_dtl_table = $this->db->table("ledger_trans_dtl");

            $voucher_header_table = $this->db->table("voucher_header");

            $voucher_detail_table = $this->db->table("voucher_detail");

            $steno_expense_table = $this->db->table("steno_expense");

            $serial_str = session()->financialYear.'%';
            $cnt = 0 ;
            $serial = 0;

            if($user_option == 'Add')
            {
                $branch_code = $_POST['branch_code'];
                $row_count = $k = 1;
                for($i=1; $row_count <= $row_counter; $i++)
                {
                    if(isset($_REQUEST['voucher_ok_ind'.$i], $_POST['associate_code'], $_REQUEST['memo_date'.$i], $_REQUEST['memo_no'.$i], $_REQUEST['matter_code'.$i], $_REQUEST['description'.$i], $_REQUEST['amount'.$i]))
                    {
                        if($_POST['voucher_ok_ind'.$i]=='Y' && !empty($_POST['associate_code']) && !empty($_POST['memo_date'.$i]) && !empty($_POST['memo_no'.$i]) && !empty($_POST['matter_code'.$i]) && !empty($_POST['description'.$i]) && !empty($_POST['amount'.$i]))
                        {
                            $array = array('serial_no'           => '',
                                        'branch_code'            => $branch_code,
                                        'associate_code'         => $_POST['associate_code'],
                                        'matter_code'            => $_POST['matter_code'.$i],
                                        'client_code'            => isset($_POST['client_code'.$i])?$_POST['client_code'.$i]:null,
                                        'memo_no'                => $_POST['memo_no'.$i],
                                        'memo_date'              => date_conv($_POST['memo_date'.$i]),
                                        'description'            => $_POST['description'.$i],
                                        'amount'                 => $_POST['amount'.$i],
                                        'passed_amount'          => isset($_POST['passed_amount'.$i])?$_POST['passed_amount'.$i]:null,
                                        'voucher_amount'         => NULL,
                                        'adjusted_amount'        => NULL,
                                        'ref_billinfo_serial_no' => NULL,
                                        'ref_voucher_serial_no'  => NULL,
                                        'ref_jv_serial_no'       => NULL,
                                        'status_code'            => 'A',
                                        'prepared_by'            => $_POST['prepared_by'],
                                        'prepared_on'            => $_POST['prepared_on'],
                                        'passed_by'              => NULL,
                                        'passed_dt'              => NULL
                                        );
                            $steno_expense = $steno_expense_table->insert($array);
                            $k++;
                        }
                        $row_count++;
                    }
                }
                session()->setFlashdata('message', 'Record Added Successfully !!');
                return redirect()->to(session()->last_selected_end_menu);
            }
            else if($user_option == 'Edit') {
                $saved_serial_no = isset($_REQUEST['saved_serial_no']) ? explode(',', $_REQUEST['saved_serial_no']) : NULL;
                $row_count = $k = 1;
                
                for($i=1; $row_count <= $row_counter; $i++) {
                    if(isset($_REQUEST['voucher_ok_ind'.$i], $_REQUEST['associate_code'], $_REQUEST['memo_date'.$i], $_REQUEST['memo_no'.$i], $_REQUEST['matter_code'.$i], $_REQUEST['description'.$i], $_REQUEST['amount'.$i])) {
                        if($_REQUEST['voucher_ok_ind'.$i] == 'Y' && !empty($_POST['associate_code']) && !empty($_POST['memo_date'.$i]) && !empty($_POST['memo_no'.$i]) && !empty($_POST['matter_code'.$i]) && !empty($_POST['description'.$i]) && !empty($_POST['amount'.$i])) {
                            $array = array( 'branch_code'            => $branch_code,
                                            'associate_code'         => $_POST['associate_code'],
                                            'matter_code'            => $_POST['matter_code'.$i],
                                            'client_code'            => isset($_POST['client_code'.$i])?$_POST['client_code'.$i]:null,
                                            'memo_no'                => $_POST['memo_no'.$i],
                                            'memo_date'              => date_conv($_POST['memo_date'.$i]),
                                            'description'            => $_POST['description'.$i],
                                            'amount'                 => $_POST['amount'.$i],
                                            'passed_amount'          => isset($_POST['passed_amount'.$i])?$_POST['passed_amount'.$i]:null,
                                            'voucher_amount'         => NULL,
                                            'adjusted_amount'        => NULL,
                                            'ref_billinfo_serial_no' => NULL,
                                            'ref_voucher_serial_no'  => NULL,
                                            'ref_jv_serial_no'       => NULL,
                                            'status_code'            => 'A',
                                            'prepared_by'            => $_POST['prepared_by'],
                                            'prepared_on'            => $_POST['prepared_on'],
                                            'passed_by'              => NULL,
                                            'passed_dt'              => NULL
                                            );
                            
                            if($_POST['serial_no'.$i] != '') {
                                unset($saved_serial_no[array_search($_POST['serial_no'.$i], $saved_serial_no)]);
                                
                                $where = "serial_no = '".$_POST['serial_no'.$i]."'";
                                $steno_expense = $steno_expense_table->update($array, $where);
                            } else $steno_expense_table->insert($array);
                            
                            $k++;
                        }
                        $row_count++;
                    } 
                }
                
                foreach($saved_serial_no as $sl_no) $steno_expense_table->delete("serial_no = '".$sl_no."'");
                
                session()->setFlashdata('message', 'Record Updated Successfully !!');
                return redirect()->to(session()->last_selected_end_menu);
            }

            else if($user_option == 'Approve')
            {
                for($i=1; $i<=$arbitrator_rownum; $i++)
                {
                    if(!empty($_POST['passed_amount'.$i]))
                    {
                        $array = array( 'serial_no'              => $_POST['serial_no'.$i],
                                        'branch_code'            => $branch_code,
                                        'associate_code'         => $_POST['associate_code'],
                                        'matter_code'            => $_POST['matter_code'.$i],
                                        'memo_no'                => $_POST['memo_no'.$i],
                                        'memo_date'              => date_conv($_POST['memo_date'.$i]),
                                        'description'            => $_POST['description'.$i],
                                        'amount'                 => $_POST['amount'.$i],
                                        'passed_amount'          => isset($_POST['passed_amount'.$i])?$_POST['passed_amount'.$i]:null,
                                        'voucher_amount'         => NULL,
                                        'adjusted_amount'        => NULL,
                                        'ref_billinfo_serial_no' => NULL,
                                        'ref_voucher_serial_no'  => NULL,
                                        'ref_jv_serial_no'       => NULL,
                                        'status_code'            => 'B',
                                        'prepared_by'            => $_POST['prepared_name'.$i],
                                        'prepared_on'            => $_POST['prepared_dt'.$i],
                                        'passed_by'              => $_POST['prepared_by'],
                                        'passed_dt'              => $_POST['prepared_on']
                                        );
                        $where = "serial_no = '".$_POST['serial_no'.$i]."'";
                        $steno_expense = $steno_expense_table->update($array,$where);
                    }
                }
                session()->setFlashdata('message', 'Record Approved Successfully !!');
                return redirect()->to(session()->last_selected_end_menu);
            }
            else if($user_option == 'Generate')
            {
                //----- Update Record : STENO_EXPENSE Table 
                $branch_code = $_POST['branch_code_copy'];
                for($i=1; $i <= $arbitrator_rownum; $i++)
                {
                    $array = array('status_code' => 'C');
                    $where = "serial_no = '".$_POST['serial_no'.$i]."'";
                    $steno_expense_update = $steno_expense_table->update($array,$where);
                }

                //----- Insert Record (JV) : VOUCHER_HEADER Table 
                $associate_code = $_POST['associate_code'];
                $where         = "steno_expense.associate_code = '$associate_code' and steno_expense.status_code = 'C' and (steno_expense.ref_jv_serial_no is NULL or steno_expense.ref_jv_serial_no = '0')";
                $dateRangeArray = $this->db->query("SELECT  min(memo_date) as start_date, max(memo_date) as end_date FROM  steno_expense WHERE $where")->getRowArray();
                
                $start_date = $dateRangeArray['start_date'];
                $end_date   = $dateRangeArray['end_date'];

                $where         = "control_keycodes.key_code = '017'";
                $controlArray  = $this->db->query("SELECT control_keycodes.* FROM control_keycodes WHERE $where")->getRowArray();
               
                $day_book_code = $controlArray['key_value'];
                $doc_type      = $controlArray['key_desc'];
                $doc_type      = 'JV';
                $narration = "STENOGRAPHER EXPENSE DURING ".date_conv($start_date) ." TO ".date_conv($end_date);

                $array = array( 'serial_no'             => '',
                                'branch_code'           => $branch_code,
                                'entry_date'            => date_conv($date),
                                'trans_type'            => 'ST',
                                'voucher_type'          => $doc_type,
                                'payee_payer_type'      => NULL,
                                'payee_payer_code'      => NULL,
                                'payee_payer_name'      => NULL,
                                'payment_type'          => NULL,
                                'daybook_code'          => $day_book_code,
                                'instrument_type'       => NULL,
                                'instrument_no'         => NULL,
                                'instrument_dt'         => NULL,
                                'bank_name'             => NULL,
                                'gross_amount'          => $_POST['gross_amount'],
                                'tax_code'              => NULL,
                                'tax_amount'            => NULL,
                                'net_amount'            => $_POST['gross_amount'],
                                'remarks'               => $narration,
                                'status_code'           => 'C',
                                'ref_ledger_serial_no'  => NULL,
                                'ref_jv_serial_no'      => NULL,
                                'ref_advance_serial_no' => NULL,
                                'link_jv_serial_no'     => NULL,
                                'prepared_by'           => date_conv($date),
                                'prepared_on'           => date_conv($date),
                                'passed_by'             => NULL,
                                'passed_on'             => NULL,
                                'paid_by'               => NULL,
                                'paid_on'               => NULL,
                            );
                $ins_voucherHdr = $voucher_header_table->insert($array);

                $voucherSerial = $this->db->insertID(); 

                //----- Insert Record (JV) : VOUCHER_DETAIL Table 
                $where        = "control_keycodes.key_code = '013'";
                $controlArray = $this->db->query("SELECT control_keycodes.* FROM control_keycodes WHERE $where")->getRowArray();
               
                $main_ac_code = $controlArray['key_value'];
                $ref_doc_type = 'STENO';

                for($i=1; $i<=$arbitrator_rownum; $i++)
                {
                    $array = array( 'ref_voucher_serial_no'    => $voucherSerial,
                                    'row_no'                   => $i,
                                    'main_ac_code'             => $main_ac_code,
                                    'sub_ac_code'              => NULL,
                                    'ref_bill_year'            => NULL,
                                    'ref_bill_no'              => NULL,
                                    'client_code'              => $_POST['client_code'.$i],
                                    'matter_code'              => $_POST['matter_code'.$i],
                                    'initial_code'             => $_POST['initial_code'.$i],
                                    'expense_type'             => NULL,
                                    'expense_code'             => NULL,
                                    'narration'                => $narration,
                                    'realise_amount_inpocket'  => NULL,
                                    'realise_amount_outpocket' => NULL,
                                    'realise_amount_counsel'   => NULL,
                                    'gross_amount'             => $_POST['passed_amount'.$i],
                                    'tax_amount'               => NULL,
                                    'net_amount'               => $_POST['passed_amount'.$i],
                                    'dr_cr_ind'                => 'D',
                                    'deficit_amount_inpocket'  => NULL,
                                    'deficit_amount_outpocket' => NULL,
                                    'deficit_amount_counsel'   => NULL,
                                    'part_full_ind'            => NULL,
                                    );
                        $ins_voucherDtl = $voucher_detail_table->insert($array);
                }
                $where        = "control_keycodes.key_code = '007'";
                $controlArray = $this->db->query("SELECT control_keycodes.* FROM control_keycodes WHERE $where")->getRowArray();

                $main_ac_code = $controlArray['key_value'];
                $array = array( 'ref_voucher_serial_no'    => $voucherSerial,
                                'row_no'                   => $i,
                                'main_ac_code'             => $main_ac_code,
                                'sub_ac_code'              => $_POST['associate_code'],
                                'ref_bill_year'            => NULL,
                                'ref_bill_no'              => NULL,
                                'client_code'              => NULL,
                                'matter_code'              => NULL,
                                'initial_code'             => NULL,
                                'expense_type'             => NULL,
                                'expense_code'             => NULL,
                                'narration'                => $narration,
                                'realise_amount_inpocket'  => NULL,
                                'realise_amount_outpocket' => NULL,
                                'realise_amount_counsel'   => NULL,
                                'gross_amount'             => $_POST['gross_amount'],
                                'tax_amount'               => NULL,
                                'net_amount'               => $_POST['gross_amount'],
                                'dr_cr_ind'                => 'C',
                                'deficit_amount_inpocket'  => NULL,
                                'deficit_amount_outpocket' => NULL,
                                'deficit_amount_counsel'   => NULL,
                                'part_full_ind'            => NULL,
                                );
                $ins_voucherDtl = $voucher_detail_table->insert($array);
              
                $doc_type = 'PV';
                $array = array( 'serial_no'             => '',
                                'branch_code'           => $branch_code,
                                'entry_date'            => date_conv($date),
                                'trans_type'            => 'ST',
                                'voucher_type'          => $doc_type,
                                'payee_payer_type'      => 'T',
                                'payee_payer_code'      => $_POST['associate_code'],
                                'payee_payer_name'      => $_POST['associate_name'],
                                'payment_type'          => 'N',
                                'daybook_code'          => NULL,
                                'instrument_type'       => NULL,
                                'instrument_no'         => NULL,
                                'instrument_dt'         => NULL,
                                'bank_name'             => NULL,
                                'gross_amount'          => $_POST['gross_amount'],
                                'tax_code'              => $_POST['tax_code'],
                                'tax_amount'            => $_POST['tax_amount'],
                                'net_amount'            => $_POST['net_amount'],
                                'remarks'               => $narration,
                                'status_code'           => 'A',
                                'ref_ledger_serial_no'  => NULL,
                                'ref_jv_serial_no'      => NULL,
                                'ref_advance_serial_no' => NULL,
                                'link_jv_serial_no'     => $voucherSerial,
                                'prepared_by'           => $user_id,
                                'prepared_on'           => date_conv($date),
                                'passed_by'             => NULL,
                                'passed_on'             => NULL,
                                'paid_by'               => NULL,
                                'paid_on'               => NULL,
                                );
                    $ins_voucherHdr = $voucher_header_table->insert($array);

                    $voucherPVSerial = $this->db->insertID(); 

                    //----- Insert Record (PV) : VOUCHER_DETAIL Table 
                    $where         = "control_keycodes.key_code = '007'";
                    $controlArray = $this->db->query("SELECT control_keycodes.* FROM control_keycodes WHERE $where")->getRowArray();

                    $main_ac_code = $controlArray['key_value'];
                    $i = 1;
                    $array = array( 'ref_voucher_serial_no'    => $voucherPVSerial,
                                    'row_no'                   => $i,
                                    'main_ac_code'             => $main_ac_code,
                                    'sub_ac_code'              => $_POST['associate_code'],
                                    'ref_bill_year'            => NULL,
                                    'ref_bill_no'              => NULL,
                                    'client_code'              => NULL,
                                    'matter_code'              => NULL,
                                    'initial_code'             => NULL,
                                    'expense_type'             => NULL,
                                    'expense_code'             => NULL,
                                    'narration'                => $narration,
                                    'realise_amount_inpocket'  => NULL,
                                    'realise_amount_outpocket' => NULL,
                                    'realise_amount_counsel'   => NULL,
                                    'gross_amount'             => $_POST['gross_amount'],
                                    'tax_amount'               => NULL,
                                    'net_amount'               => $_POST['gross_amount'],
                                    'dr_cr_ind'                => 'D',
                                    'deficit_amount_inpocket'  => NULL,
                                    'deficit_amount_outpocket' => NULL,
                                    'deficit_amount_counsel'   => NULL,
                                    'part_full_ind'            => NULL,
                                );
                    $ins_voucherDtl = $voucher_detail_table->insert($array);
                    
                    $i++;
                    if($_POST['tax_amount'] > 0 )
                    {
                        $tax_code = $_POST['tax_code'];
                        $row = $this->db->query("SELECT tax_account_code,tax_sub_account_code FROM tax_master WHERE tax_code = '$tax_code'")->getRowArray();
                        // echo '<pre>';print_r($row);die;
                        $tax_account_code        = $row['tax_account_code'];
                        $tax_sub_account_code    = $row['tax_sub_account_code'];

                        $array = array( 'ref_voucher_serial_no'    => $voucherPVSerial,
                                        'row_no'                   => $i,
                                        'main_ac_code'             => $tax_account_code,
                                        'sub_ac_code'              => $tax_sub_account_code,
                                        'ref_bill_year'            => NULL,
                                        'ref_bill_no'              => NULL,
                                        'client_code'              => NULL,
                                        'matter_code'              => NULL,
                                        'initial_code'             => NULL,
                                        'expense_type'             => NULL,
                                        'expense_code'             => NULL,
                                        'narration'                => $narration,
                                        'realise_amount_inpocket'  => NULL,
                                        'realise_amount_outpocket' => NULL,
                                        'realise_amount_counsel'   => NULL,
                                        'gross_amount'             => $_POST['tax_amount'],
                                        'tax_amount'               => NULL,
                                        'net_amount'               => $_POST['tax_amount'],
                                        'dr_cr_ind'                => 'C',
                                        'deficit_amount_inpocket'  => NULL,
                                        'deficit_amount_outpocket' => NULL,
                                        'deficit_amount_counsel'   => NULL,
                                        'part_full_ind'            => NULL,
                                        );
                        $ins_voucherDtl = $voucher_detail_table->insert($array);
                    }

                    //----- Update Record : STENO_EXPENSE Table 
                    $array = array( 'ref_billinfo_serial_no' => NULL,
                                    'ref_voucher_serial_no'  => $voucherPVSerial,
                                    'ref_jv_serial_no'       => $voucherSerial,
                                );
                    $associate_code = $_POST['associate_code'];
                    $where          = "steno_expense.associate_code = '$associate_code' and steno_expense.status_code = 'C' and (steno_expense.ref_voucher_serial_no is NULL or steno_expense.ref_voucher_serial_no = '0')";
                    $steno_expense  = $steno_expense_table->update($array,$where);
                   
                    // if ($voucherPVSerial < 1 || $voucherSerial < 1)
                    // {
                    // if ($global_mdb2->inTransaction()) { $global_mdb2->rollback($savepoint); }
                    // }
                    // else 
                    // {
                    // if ($global_mdb2->inTransaction()) { $global_mdb2->commit(); }
                    // }
                    session()->setFlashdata('message', 'Record Generated Successfully !!');
                    return redirect()->to(session()->last_selected_end_menu);
            }

            else if($user_option == 'Print') {

            }
        } else {

            $row_num = 0; $stenoArray = $tax_data = []; $branch_code = '';
            if($user_option != 'Add')
            {   
                if($user_option == 'Edit') {
                    if($status_code == 'A') {
                        $where         = "steno_expense.associate_code = '$associate_code' and steno_expense.status_code = 'A' and (steno_expense.ref_jv_serial_no is NULL or steno_expense.ref_jv_serial_no = '0')";
                        $stenoArray    = $this->db->query("SELECT steno_expense.*,b.client_code,b.matter_desc1,b.matter_desc2,b.initial_code FROM `steno_expense` INNER JOIN fileinfo_header b on steno_expense.matter_code = b.matter_code WHERE $where ORDER BY steno_expense.serial_no ASC")->getResultArray();
                        
                        $row_num =  $this->db->query("SELECT count(serial_no) as totalRow FROM `steno_expense` WHERE $where")->getRowArray();
                        $branch_code = $stenoArray[0]['branch_code'];
                    } else {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to(session()->last_selected_end_menu);
                    }
                }
                else if($user_option == 'Approve') {
                    if($status_code == 'A') {
                        $where         = "steno_expense.associate_code = '$associate_code' and steno_expense.status_code = 'A' and (steno_expense.ref_jv_serial_no is NULL or steno_expense.ref_jv_serial_no = '0')";
                        $stenoArray    = $this->db->query("SELECT steno_expense.*,b.client_code,b.matter_desc1,b.matter_desc2,b.initial_code FROM `steno_expense` INNER JOIN fileinfo_header b on steno_expense.matter_code = b.matter_code WHERE $where ORDER BY steno_expense.serial_no ASC")->getResultArray();
                        
                        $row_num =  $this->db->query("SELECT count(serial_no) as totalRow FROM `steno_expense` WHERE $where")->getRowArray();
                        $branch_code         = $stenoArray[0]['branch_code'];
                    } else {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to(session()->last_selected_end_menu);
                    }
                }
                else if($user_option == 'Generate') {
                    if($status_code == '<font color=green>B</font>' || $status_code == 'B') {
                        $where         = "steno_expense.associate_code = '$associate_code' and steno_expense.status_code = 'B' and (steno_expense.ref_jv_serial_no is NULL or steno_expense.ref_jv_serial_no = '0')";
                        $stenoArray    = $this->db->query("SELECT steno_expense.*,b.client_code,b.matter_desc1,b.matter_desc2,b.initial_code FROM `steno_expense` INNER JOIN fileinfo_header b on steno_expense.matter_code = b.matter_code WHERE $where ORDER BY steno_expense.serial_no ASC")->getResultArray();
                        
                        $row_num =  $this->db->query("SELECT count(serial_no) as totalRow FROM `steno_expense` WHERE $where")->getRowArray();
                        $branch_code = $stenoArray[0]['branch_code'];

                        $tax_data = $this->db->query("select a.tax_percent,a.tax_code, b.tax_name from tax_rate a, tax_master b
                                     where a.fin_year      =  '$current_fin_year'
                                       and a.tax_code      =   b.tax_code
                                       and b.tax_type_code =  'T'")->getResultArray();
                    } else {
                        session()->setFlashdata('message', 'No Records Found !!');
                        return redirect()->to(session()->last_selected_end_menu);
                    }
                } else if($user_option == 'Print') { 
                    if($status_code == '<font color=red>C</font>' || $status_code == 'C') {   
                        $result = $this->db->query("select status_code from voucher_header where serial_no = '$ref_voucher_serial_no'")->getRowArray();
                        $voucher_status = $result['status_code'];
                        
                        if($voucher_status == 'A') {

                            $params = []; $i = 0; $payee = '';
                            $serial_no          = isset($_REQUEST['serial_no'])         ?$_REQUEST['serial_no']        :null;
                            $serial_no          = isset($_REQUEST['serial_no'])         ?$_REQUEST['serial_no']        :null;
                            $voucher_ind        = isset($_REQUEST['voucher_ind'])       ?$_REQUEST['voucher_ind']      :null;
                            $voucher_serial_no  = isset($_REQUEST['voucher_serial_no']) ?$_REQUEST['voucher_serial_no']:null;

                            $serial_no  = $ref_voucher_serial_no;
                            $user_sql   = "select * from system_user where user_id = '$user_id' " ; 
                            $user_row   = $this->db->query($user_sql)->getRowArray();

                            if($user_row['user_gender'] == 'F') { $sys_user_name = 'Ms. '.$user_row['user_name'] ;} else { $sys_user_name = 'Mr. '.$user_row['user_name'] ; }

                            if ($voucher_ind == 'Memo') {
                                $hdr_stmt = "select a.* from voucher_header a where a.link_jv_serial_no = '$voucher_serial_no' ";
                            } else {
                                $hdr_stmt = "select a.* from voucher_header a where a.serial_no = '$serial_no' ";
                            }

                            $res1 = $this->db->query($hdr_stmt)->getResultarray();
                            $header_cnt = count($res1);
                            
                            $hcnt = 1;
                            foreach($res1 as $hdr_row) {
                                $i++;
                                //$hdr_row           = $res1;
                                $branch_code       = $hdr_row['branch_code'];
                                $serial_no         = $hdr_row['serial_no'];
                                $entry_date        = date_conv($hdr_row['entry_date']); 
                                $payee_payer_name  = $hdr_row['payee_payer_name']; 
                                $remarks           = $hdr_row['remarks']; 
                                $ref_advance_serial_no  = $hdr_row['ref_advance_serial_no']; 
                                $trans_type        = $hdr_row['trans_type'] ;
                                $payee_payer_type  = $hdr_row['payee_payer_type'] ;
                                $daybook_code      = $hdr_row['daybook_code'] ;
                                $inst_type         = $hdr_row['instrument_type']; 
                                $inst_no           = $hdr_row['instrument_no']; 
                                $inst_dt           = date_conv($hdr_row['instrument_dt'],'-'); 
                                $inst_bank         = $hdr_row['bank_name']; 
                                $hdr_gross_amount  = $hdr_row['gross_amount']; 
                                $hdr_tax_amount    = $hdr_row['tax_amount']; 
                                $hdr_net_amount    = $hdr_row['net_amount'];
                                $hdr_user          = strtoupper($hdr_row['prepared_by']); 
                                $trans_type        = $hdr_row['trans_type']; 
                                $payment_type      = $hdr_row['payment_type'];
                                $ref_advance_serial_no       = $hdr_row['ref_advance_serial_no'];
                                
                            
                                $user_sql       = "select * from system_user where user_id = '$hdr_user' " ; 
                                $user_row      = $this->db->query($user_sql)->getRowArray();

                                if($user_row['user_gender'] == 'F') { $sys_user_name = 'Ms. '.$user_row['user_name'] ;} else { $sys_user_name = 'Mr. '.$user_row['user_name'] ; }
                                //
                                if($payee_payer_type == 'S') {  $payee = 'SUPPL' ;}
                                if($payee_payer_type == 'E') {  $payee = 'EMPL' ;}
                                if($payee_payer_type == 'C') {  $payee = 'CUNL' ;}
                                if($payee_payer_type == 'K') {  $payee = 'CLRK' ;}
                                if($payee_payer_type == 'O') {  $payee = 'ORS' ;}
                                if($payee_payer_type == 'U') {  $payee = 'CONST' ;}
                                
                                if($payment_type == 'A') {  $payment_type = '(ADV)' ;}
                                if($payment_type == 'N') {  $payment_type = '(NOR)' ;}
                                
                                $branch_sql   = $this->db->query("select * from branch_master where branch_code = '$branch_code' ")->getRowArray();
                                $branch_addr1 = $branch_sql['address_line_1'].', '.$branch_sql['city'].' - '.$branch_sql['pin_code'] ;
                                $branch_addr2 = 'TEL : '.$branch_sql['phone_no'] ;
                                $branch_addr3 = 'FAX : '.$branch_sql['fax_no'] ;
                                $branch_addr4 = 'E-Mail : '.$branch_sql['email_id'] ;

                                $hdr_net_rs_arr   = explode(".",$hdr_row['net_amount']); //Changed by ABM with help of PC (on 19/11/11)  for actual rupees and paise figure.
                                $hdr_net_rs       = $hdr_net_rs_arr[0]*1;
                                $hdr_net_paise    = $hdr_net_rs_arr[1]*1;    
                                $net_riw          = int_to_words($hdr_net_rs) ;
                                $paise_riw        = int_to_words($hdr_net_paise) ;
                            
                                if($paise_riw > 0) {$hdr_net_riw = '(Rupees '.$net_riw.' and paise '.$paise_riw.' only)';} else {$hdr_net_riw = '(Rupees '.$net_riw.' only)';} 
                                //-------------------------
                                $dtl_stmt = "select b.* from voucher_detail b where b.ref_voucher_serial_no = '$serial_no' order by b.row_no asc,b.dr_cr_ind desc";
                                $res2 = $this->db->query($dtl_stmt)->getResultArray();
                                $detail_cnt = count($res2);

                                $cnt = 0 ;
                                foreach($res2 as $dtl_row) {
                                    $cnt++;
                                    //$dtl_row      = $res2->fetchRow();
                                    $narration    = $dtl_row['narration'];
                                    $main_ac_code = $dtl_row['main_ac_code'];
                                    $sub_ac_code  = $dtl_row['sub_ac_code'];
                                    $matter_code  = $dtl_row['matter_code'];
                                    $client_code  = $dtl_row['client_code'];
                                    $expense_code = $dtl_row['expense_code'];
                                    $gross_amount = $dtl_row['gross_amount'];
                                    $dr_cr_ind    = $dtl_row['dr_cr_ind'];
                                
                                    $main_ac_stmt = "select main_ac_desc,sub_ac_ind from account_master where main_ac_code = '$main_ac_code'";
                                    $main_ac_row = $this->db->query($main_ac_stmt)->getRowArray();
                                    $main_ac_desc = $main_ac_row['main_ac_desc'];
                                    $sub_ac_ind   = $main_ac_row['sub_ac_ind'];
                                    $sub_ac_desc  = '' ;
                                    if($sub_ac_ind == 'Y')
                                    {
                                    $sub_ac_stmt = "select sub_ac_desc from sub_account_master where main_ac_code = '$main_ac_code' and sub_ac_code  = '$sub_ac_code'";
                                    $sub_ac_row = $this->db->query($sub_ac_stmt)->getRowArray();
                                    $sub_ac_desc = ' / '.$sub_ac_row['sub_ac_desc'];
                                    }
                                
                                    $matter_stmt = "select concat(matter_desc1,' ',matter_desc2) matter_desc from fileinfo_header where matter_code = '$matter_code'";
                                    $matter_row = $this->db->query($matter_stmt)->getRowArray();
                                    $matter_desc = empty($matter_row['matter_desc']) ? '' : $matter_row['matter_desc'];

                                    $params[$i-1] = [
                                        "serial_no" => $serial_no,
                                        "entry_date" => $entry_date,
                                        "branch_addr1" => $branch_addr1,
                                        "branch_addr2" => $branch_addr2,
                                        "branch_addr3" => $branch_addr3,
                                        "branch_addr4" => $branch_addr4,
                                        "trans_type" => $trans_type,
                                        "daybook_code" => $daybook_code,
                                        "inst_no" => $inst_no,
                                        "inst_dt" => $inst_dt,
                                        "payee" => $payee,
                                        "payment_type" => $payment_type,
                                        "cnt" => $cnt,
                                        "narration" => $narration,
                                        "main_ac_code" => $main_ac_code,
                                        "sub_ac_code" => $sub_ac_code,
                                        "matter_code" => $matter_code,
                                        "client_code" => $client_code,
                                        "expense_code" => $expense_code,
                                        "dr_cr_ind" => $dr_cr_ind,
                                        "gross_amount" => $gross_amount,
                                        "main_ac_desc" => $main_ac_desc,
                                        "sub_ac_desc" => $sub_ac_desc,
                                        "hdr_net_riw" => $hdr_net_riw,
                                        "hdr_net_amount" => $hdr_net_amount,
                                        "payee_payer_name" => $payee_payer_name,
                                        "remarks" => $remarks,
                                        "ref_advance_serial_no" => $ref_advance_serial_no,
                                        "hdr_user" => $hdr_user,

                                    ];
                                }
                            }
                            return view("pages/OtherExpenses/stenographer_expenses", compact("params", "data", "displayId"));

                        } else {
                            session()->setFlashdata('message', 'Voucher has already been APPROVED . . .');
                            return redirect()->to(session()->last_selected_end_menu);
                        }
                    } else {
                        session()->setFlashdata('message', 'Voucher not yet been GENERATED . . .');
                        return redirect()->to(session()->last_selected_end_menu);
                    }
                }      
            } 
            $params = [
                'redk' => $redk,
                'redv' => $redv,
                'disv' => $disv,
                'disb' => $disb,
                'redve' => $redve,
                'redokadd' => $redokadd,
                'associate_code' => $associate_code,
                'associate_name' => $associate_name,
            ];
            return view("pages/OtherExpenses/stenographer_expenses", compact('data', 'displayId', 'params', 'user_option', 'row_num', 'stenoArray', 'branch_code', 'tax_data'));
        }

    } 

    /*********************************************************************************************/
    /***************************** TDS [Reports] ***********************************/
    /*********************************************************************************************/

    public function list_of_other_expenses() {
        if($this->request->getMethod() == "post") {
            $params['requested_url'] = session()->requested_end_menu_url;
            $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : '';
            $showActionBtns = false;

            if($output_type == 'Report' || $output_type == 'Pdf') {
                $date_from     = $_REQUEST['date_from'] ; $date_from = date_conv($date_from,'-');
                $date_to       = $_REQUEST['date_to'] ;   $date_to   = date_conv($date_to,'-');
                $branch_code   = $_REQUEST['branch_code'] ;
                $showActionBtns = true;
    
                  //------  
                $finyr_qry  = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getRowArray() ; 
                //
                //   $branch_qry = mysql_query($global_branch_selection_stmt,$link) ;
                // echo "<pre>"; print_r($_REQUEST); die;
                $fin_year      = $finyr_qry['fin_year'] ;
                $expn_type     = $_REQUEST['expn_type'] ;
                $payee_type    = $_REQUEST['payee_type'] ;
                $payee_code    = $_REQUEST['payee_code'] ;  if(empty($payee_code)) { $payee_code = '%' ; }
                $payee_name    = $_REQUEST['payee_name'] ;  if(empty($payee_name)) { $payee_name = '%' ; }
                $serial_no     = isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : '';
                $report_desc = '';

                if($expn_type == 'CM'){ $report_desc   = 'Court Miscellaneous Expenses' ; }
                if($expn_type == 'CC'){ $report_desc   = 'Courier Expenses' ; }
                if($expn_type == 'PE'){ $report_desc   = 'Photocopy Expenses' ; }
                if($expn_type == 'AR'){ $report_desc   = 'Arbitrator Expenses' ; }
                if($expn_type == 'SE'){ $report_desc   = 'Stenographer Expenses' ; }
              
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                $branch_name   = $branch_qry['branch_name'] ;
              
                $ldg_stmt = "select a.* from ledger_trans_hdr a where a.doc_date >= '$date_from'
                                and a.doc_date <= '$date_to' and a.payee_payer_type like '$payee_type' and ifnull(a.payee_payer_code,'%') like ifnull('$payee_code','%') 
                                and a.payee_payer_name like '$payee_name' and a.doc_type = 'PV' and a.ref_doc_type like '$expn_type' order by a.doc_date,a.payee_payer_code";
                $ldg_qry  = $this->db->query($ldg_stmt)->getResultArray();
                $ldg_cnt  = count($ldg_qry);

                if(empty($ldg_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/OtherExpenses/list_of_other_expenses", compact("ldg_qry", 'ldg_cnt', 'params', 'report_desc', 'branch_name', 'fin_year', 'date_from', 'date_to', 'payee_name', 'branch_code', 'expn_type', 'payee_type', 'showActionBtns', 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/OtherExpenses/list_of_other_expenses", compact("ldg_qry", 'ldg_cnt', 'params', 'report_desc', 'branch_name', 'fin_year', 'date_from', 'date_to', 'payee_name', 'branch_code', 'expn_type', 'payee_type', 'showActionBtns'));

            } else if($output_type == 'innerReport' || $output_type == 'innerPdf') {

                $date_from     = $_REQUEST['date_from'] ; //$date_from = date_conv($date_from,'-');
                $date_to       = $_REQUEST['date_to'] ;  // $date_to   = date_conv($date_to,'-');
                $branch_code   = $_REQUEST['branch_code'] ;
                $fin_year      = $_REQUEST['fin_year'] ;
                $expn_type     = $_REQUEST['expn_type'] ;
                $payee_type    = $_REQUEST['payee_type'] ;
                $payee_code    = $_REQUEST['payee_code'] ;  if(empty($payee_code)) { $payee_code = '%' ; }
                $payee_name    = $_REQUEST['payee_name'] ;  if(empty($payee_name)) { $payee_name = '%' ; }
                $serial_no     = $_REQUEST['serial_no'] ;

                if($expn_type == 'CM'){ $report_desc   = 'Court Miscellaneous Expenses' ; }
                if($expn_type == 'CC'){ $report_desc   = 'Courier Expenses' ; }
                if($expn_type == 'PE'){ $report_desc   = 'Photocopy Expenses' ; }
                if($expn_type == 'AR'){ $report_desc   = 'Arbitrator Expenses' ; }
                if($expn_type == 'SE'){ $report_desc   = 'Stenographer Expenses' ; }
              
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                $branch_name   = $branch_qry['branch_name'] ;
              
                //-------------------------------------------------------------
                $ldg_stmt = "select a.payee_payer_code, a.payee_payer_name, date_format(a.doc_date,'%d-%m-%Y') doc_date,a.serial_no,a.ref_doc_serial_no,b.serial_no,c.ref_ledger_serial_no,c.main_ac_code,c.client_code,c.matter_code,c.narration,c.net_amount,c.dr_cr_ind  
                               from ledger_trans_hdr a, voucher_header b, ledger_trans_dtl c
                              where a.doc_date between '$date_from' and '$date_to'
                                and a.payee_payer_type             like '$payee_type' 
                                and a.payee_payer_code             like '$payee_code' 
                                and a.payee_payer_name             like '$payee_name' 
                                and a.doc_type                        = 'PV' 
                                and a.ref_doc_type                 like '$expn_type'
                                and a.ref_doc_serial_no = b.serial_no 
                                and b.ref_jv_serial_no  = c.ref_ledger_serial_no
                                and c.main_ac_code = '7050' 
                                and c.dr_cr_ind = 'D'
                                and a.serial_no = '$serial_no'
                           order by a.doc_date,a.payee_payer_code"; 
                $ldg_qry  = $this->db->query($ldg_stmt)->getResultArray();
                $ldg_cnt  = count($ldg_qry);

                if ($output_type == 'innerPdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/OtherExpenses/list_of_other_expenses", compact("ldg_qry", 'ldg_cnt', 'params', 'report_desc', 'branch_name', 'fin_year', 'date_from', 'date_to', 'payee_name', 'branch_code', 'expn_type', 'payee_type', 'showActionBtns', 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/OtherExpenses/list_of_other_expenses", compact("ldg_qry", 'ldg_cnt', 'params', 'report_desc', 'branch_name', 'fin_year', 'date_from', 'date_to', 'payee_name', 'branch_code', 'expn_type', 'payee_type', 'showActionBtns'));

            } else if($output_type == 'Excel' || $output_type == 'innerExcel') {

                if($output_type == 'Excel') {
                    $date_from     = $_REQUEST['date_from'] ; $date_from = date_conv($date_from,'-');
                    $date_to       = $_REQUEST['date_to'] ;   $date_to   = date_conv($date_to,'-');
                    $branch_code   = $_REQUEST['branch_code'] ;
                    $showActionBtns = true;
        
                    $finyr_qry  = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getRowArray(); 
    
                    $fin_year      = $finyr_qry['fin_year'] ;
                    $expn_type     = $_REQUEST['expn_type'] ;
                    $payee_type    = $_REQUEST['payee_type'] ;
                    $payee_code    = $_REQUEST['payee_code'] ;  if(empty($payee_code)) { $payee_code = '%' ; }
                    $payee_name    = $_REQUEST['payee_name'] ;  if(empty($payee_name)) { $payee_name = '%' ; }
                    $serial_no     = isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : '';
                    $report_desc = '';
    
                    if($expn_type == 'CM'){ $report_desc   = 'Court Miscellaneous Expenses' ; }
                    if($expn_type == 'CC'){ $report_desc   = 'Courier Expenses' ; }
                    if($expn_type == 'PE'){ $report_desc   = 'Photocopy Expenses' ; }
                    if($expn_type == 'AR'){ $report_desc   = 'Arbitrator Expenses' ; }
                    if($expn_type == 'SE'){ $report_desc   = 'Stenographer Expenses' ; }
                  
                    $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                    $branch_name   = $branch_qry['branch_name'] ;
                  
                    $ldg_stmt = "select a.* from ledger_trans_hdr a where a.doc_date >= '$date_from'
                                    and a.doc_date <= '$date_to' and a.payee_payer_type like '$payee_type' and ifnull(a.payee_payer_code,'%') like ifnull('$payee_code','%') 
                                    and a.payee_payer_name like '$payee_name' and a.doc_type = 'PV' and a.ref_doc_type like '$expn_type' order by a.doc_date,a.payee_payer_code";
                } else {
                    $date_from     = $_REQUEST['date_from'] ; //$date_from = date_conv($date_from,'-');
                    $date_to       = $_REQUEST['date_to'] ;  // $date_to   = date_conv($date_to,'-');
                    $branch_code   = $_REQUEST['branch_code'] ;
                    $fin_year      = $_REQUEST['fin_year'] ;
                    $expn_type     = $_REQUEST['expn_type'] ;
                    $payee_type    = $_REQUEST['payee_type'] ;
                    $payee_code    = $_REQUEST['payee_code'] ;  if(empty($payee_code)) { $payee_code = '%' ; }
                    $payee_name    = $_REQUEST['payee_name'] ;  if(empty($payee_name)) { $payee_name = '%' ; }
                    $serial_no     = $_REQUEST['serial_no'] ;
    
                    if($expn_type == 'CM'){ $report_desc   = 'Court Miscellaneous Expenses' ; }
                    if($expn_type == 'CC'){ $report_desc   = 'Courier Expenses' ; }
                    if($expn_type == 'PE'){ $report_desc   = 'Photocopy Expenses' ; }
                    if($expn_type == 'AR'){ $report_desc   = 'Arbitrator Expenses' ; }
                    if($expn_type == 'SE'){ $report_desc   = 'Stenographer Expenses' ; }
                  
                    $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                    $branch_name   = $branch_qry['branch_name'] ;
                  
                    //-------------------------------------------------------------
                    $ldg_stmt = "select a.payee_payer_code, a.payee_payer_name, date_format(a.doc_date,'%d-%m-%Y') doc_date,a.serial_no,a.ref_doc_serial_no,b.serial_no,c.ref_ledger_serial_no,c.main_ac_code,c.client_code,c.matter_code,c.narration,c.net_amount,c.dr_cr_ind  
                                   from ledger_trans_hdr a, voucher_header b, ledger_trans_dtl c
                                  where a.doc_date between '$date_from' and '$date_to'
                                    and a.payee_payer_type             like '$payee_type' 
                                    and a.payee_payer_code             like '$payee_code' 
                                    and a.payee_payer_name             like '$payee_name' 
                                    and a.doc_type                        = 'PV' 
                                    and a.ref_doc_type                 like '$expn_type'
                                    and a.ref_doc_serial_no = b.serial_no 
                                    and b.ref_jv_serial_no  = c.ref_ledger_serial_no
                                    and c.main_ac_code = '7050' 
                                    and c.dr_cr_ind = 'D'
                                    and a.serial_no = '$serial_no'
                               order by a.doc_date,a.payee_payer_code"; 
                }
                $ldg_qry  = $this->db->query($ldg_stmt)->getResultArray();
                $ldg_cnt  = count($ldg_qry);

                if(empty($ldg_qry)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $fileName = 'List-of-Other-Expenses-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Define the headings
                $headings = ['Date', 'Doc#', 'Type', 'DB', 'Payee', 'Gross', 'Tds', 'Net'];

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
                $tgramt  = 0; 
                $ttxamt  = 0; 
                $tntamt  = 0; 
                $rowcnt     = 1 ;
                $report_row = isset($ldg_qry[$rowcnt-1]) ? $ldg_qry[$rowcnt-1] : '' ;  
                $report_cnt = $ldg_cnt ;
                while ($rowcnt <= $report_cnt) {
                    $mgramt = 0; $mtxamt = 0; $mntamt = 0; 
                    $pdocym = substr($report_row['doc_date'],0,4).substr($report_row['doc_date'],5,2);

                    while ($pdocym == (substr($report_row['doc_date'],0,4).substr($report_row['doc_date'],5,2)) && $rowcnt <= $report_cnt) {
                        $sheet->setCellValue('A' . $rows, date_conv($report_row['doc_date'],'-'));
                        $sheet->setCellValue('B' . $rows, isset($report_row['doc_no']) ? $report_row['doc_no'] : '');
                        $sheet->setCellValue('C' . $rows, isset($report_row['doc_type']) ? $report_row['doc_type'] : '');
                        $sheet->setCellValue('D' . $rows, isset($report_row['daybook_code']) ? $report_row['daybook_code'] : '');
                        $sheet->setCellValue('E' . $rows, $report_row['payee_payer_name']);
                        $sheet->setCellValue('F' . $rows, isset($report_row['gross_amount']) ? $report_row['gross_amount'] : '');
                        $sheet->setCellValue('G' . $rows, isset($report_row['tax_amount']) ? $report_row['tax_amount'] : '');
                        $sheet->setCellValue('H' . $rows, isset($report_row['net_amount']) ? $report_row['net_amount'] : '');
                        
                        // Apply border to the current row
                        $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                        $mgramt = $mgramt + isset($report_row['gross_amount']) ? $report_row['gross_amount'] : 0 ;                   
                        $mtxamt = $mtxamt + isset($report_row['tax_amount']) ? $report_row['tax_amount'] : 0;                   
                        $mntamt = $mntamt + $report_row['net_amount'] ;                   

                        $report_row = ($rowcnt < $report_cnt) ? $ldg_qry[$rowcnt] : $report_row;
                        $rowcnt = $rowcnt + 1 ;
                        $rows++;
                    }
                    $tgramt = $tgramt + $mgramt;                   
                    $ttxamt = $ttxamt + $mtxamt;                   
                    $tntamt = $tntamt + $mntamt;    

                    $sheet->setCellValue('E' . $rows, 'TOTAL');
                    $sheet->setCellValue('F' . $rows, number_format($mgramt,2,'.',''));
                    $sheet->setCellValue('G' . $rows, number_format($mtxamt,2,'.',''));
                    $sheet->setCellValue('H' . $rows, number_format($mntamt,2,'.',''));
                    
                    // Apply Background Color to the current row
                    $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;
                }

                $sheet->setCellValue('E' . $rows, 'GRAND TOTAL');
                $sheet->setCellValue('F' . $rows, number_format($tgramt,2,'.',''));
                $sheet->setCellValue('G' . $rows, number_format($ttxamt,2,'.',''));
                $sheet->setCellValue('H' . $rows, number_format($tntamt,2,'.',''));
                
                // Apply Background Color to the current row
                $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('58ff86');

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
            $data = branches("demo");
            $data['requested_url'] = session()->requested_end_menu_url;
            $displayId   = ['payee_help_id' => '4402'] ;
            $data['finyr_qry']  = $this->db->query("select fin_year from params where year_close_ind != 'Y' order by fin_year desc")->getResultArray(); 
            $data['ason_date'] = date('d-m-Y');
    
            $curr_finyr = session()->financialYear;
            $data['curr_fyrsdt'] = '01-04-'.substr($curr_finyr,0,4);
            return view("pages/OtherExpenses/list_of_other_expenses", compact("data", 'displayId'));
        }
    }

}