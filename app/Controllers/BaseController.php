<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['url','dispMenu','menu_data', 'dispList'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

	public function requested_url() {
        $url =  base_url(substr($_SERVER['REQUEST_URI'], 8));
        $urlSlice = explode(base_url(), $url);
        return $urlSlice[0] . '/' . $urlSlice[1];  
    }
    
        public function common_print_expenses($ref_voucher_serial_no = null, $user_id = null, $status_code = null) { 

        // if($status_code == '<font color=red>C</font>' || $status_code == 'C') {   
        //     $result = $this->db->query("select status_code from voucher_header where serial_no = '$ref_voucher_serial_no'")->getRowArray();
        //     $voucher_status = $result['status_code'];

            // if($voucher_status == 'A') {
                $params = []; $i = 0; $payee = '';
                $serial_no          = isset($_REQUEST['serial_no'])         ?$_REQUEST['serial_no']        :null;
                $voucher_ind        = isset($_REQUEST['voucher_ind'])       ?$_REQUEST['voucher_ind']      :null;
                $voucher_serial_no  = isset($_REQUEST['voucher_serial_no']) ?$_REQUEST['voucher_serial_no']:null;
                $dupl_ind           = isset($_REQUEST['dupl_ind'])          ?$_REQUEST['dupl_ind']         :null;
                $serial_no  = $ref_voucher_serial_no;

                $instrument_dt = date('d-m-Y'); 

                $inst_dd1   =  substr($instrument_dt,0,1) ;
                $inst_dd2   =  substr($instrument_dt,1,1) ;
           
                $inst_mm1   =  substr($instrument_dt,3,1) ;
                $inst_mm2   =  substr($instrument_dt,4,1) ;
           
                $inst_yyyy1 =  substr($instrument_dt,6,1) ;
                $inst_yyyy2 =  substr($instrument_dt,7,1) ;
                $inst_yyyy3 =  substr($instrument_dt,8,1) ;
                $inst_yyyy4 =  substr($instrument_dt,9,1) ;
           
                $inst_dd    = $inst_dd1.' '.' '.$inst_dd2 ;
                $inst_mm    = $inst_mm1.' '.$inst_mm2;
                $inst_yyyy  = $inst_yyyy1.' '.' '.$inst_yyyy2.' '.$inst_yyyy3.' '.$inst_yyyy4;

                if ($voucher_ind == 'Memo') {
                    $hdr_stmt = "select a.* from voucher_header a where a.link_jv_serial_no = '$voucher_serial_no' ";
                } else {
                    $hdr_stmt = "select a.* from voucher_header a where a.serial_no = '$serial_no' ";
                }
                
                $res1 = $this->db->query($hdr_stmt)->getResultarray();
                $header_cnt = count($res1);
                
                // $user_sql   = "select * from system_user where user_id = '$user_id' " ; 
                // $user_row   = $this->db->query($user_sql)->getRowArray();
                // if($user_row['user_gender'] == 'F') { $sys_user_name = 'Ms. '.$user_row['user_name'] ;} else { $sys_user_name = 'Mr. '.$user_row['user_name'] ; }
                
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
                    
                    $user_sql = "select * from system_user where user_id = '$hdr_user' " ; 
                    $user_row = $this->db->query($user_sql)->getRowArray();

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

                    if ($payee_payer_name != '%(P)%') {
                        $payee_payer_name = preg_replace('~\(.*?\)~', '', $payee_payer_name); }
                   
                    $hdr_net_rs_arr   = explode(".",$hdr_row['net_amount']); //Changed by ABM with help of PC (on 19/11/11)  for actual rupees and paise figure.
                    $hdr_net_rs       = $hdr_net_rs_arr[0]*1;
                    $hdr_net_paise    = $hdr_net_rs_arr[1]*1;    
                    $net_riw          = int_to_words($hdr_net_rs) ;
                    $paise_riw        = int_to_words($hdr_net_paise) ;
                
                    $rs_ps  = explode('.',$hdr_net_amount);
                    $rs     = $rs_ps[0];
                    $ps     = $rs_ps[1];
                    
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
                        $main_ac_desc = empty($main_ac_row) ? '' : $main_ac_row['main_ac_desc'];
                        $sub_ac_ind   = empty($main_ac_row) ? '' : $main_ac_row['sub_ac_ind'];
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
        //     } else {
        //         session()->setFlashdata('message', 'Voucher Has Already Been APPROVED !!');
        //         return redirect()->to(session()->last_selected_end_menu);
        //     }
        // } else {
        //     session()->setFlashdata('message', 'Voucher Not Yet Been GENERATED !!');
        //     return redirect()->to(session()->last_selected_end_menu);
        // }
    } 
}