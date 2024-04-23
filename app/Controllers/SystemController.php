<?php



namespace App\Controllers;
use DateTime;
use DateInterval;


class SystemController extends BaseController

{

    public function __construct() {

        $this->db = \config\database::connect();

        $this->session = session();

    }

    public function change_password($option='Edit')
    {
         $session = session();
         $sessionName=$session->userId;
         $data['requested_url'] = $this->session->requested_end_menu_url; 
         $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
         $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
         $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
         $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
         $employee_name           = isset($_REQUEST['employee_name'])?$_REQUEST['employee_name']:null;
         $user_id           = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:null;
         $employee_password           = isset($_REQUEST['employee_password'])?$_REQUEST['employee_password']:null;
         $employee_new_password_1           = isset($_REQUEST['employee_new_password_1'])?$_REQUEST['employee_new_password_1']:null;
         $employee_new_password_2           = isset($_REQUEST['employee_new_password_2'])?$_REQUEST['employee_new_password_2']:null;
         $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
         $ip_address        = $_SERVER['REMOTE_ADDR'];
         $url = "system/change-password?display_id={$display_id}&menu_id={$menu_id}";
            $pkey='Sinha&co';
            $simple_string = $employee_new_password_1;
            $ciphering = "AES-128-CTR";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            $encryption_iv = '1234567891011121';
            $encryption_key = $pkey;
            $privetkey=$pkey;
            $encryption = openssl_encrypt($simple_string, $ciphering,

                        $encryption_key, $options, $encryption_iv);


         if ($option == 'Add') {$redk = '';
                    $redv = '';
                    $disv = '';
                    $disb = '';
                    $redve = '';
                    $redokadd = '';
                    $disview = '';
                    $redLetter = 'disabled';}
                if ($option == 'Edit') {$redk = '';
                    $redv = '';
                    $disv = '';
                    $disb = '';
                    $redve = 'disabled';
                    $redokadd = '';
                    $disview = '';
                    $redLetter = 'disabled';}
                    if ($option == 'Select') {$redk = '';
                        $redv = '';
                        $disv = '';
                        $disb = '';
                        $redve = 'disabled';
                        $redokadd = '';
                        $disview = '';
                        $redLetter = 'disabled';}
                if ($option == 'Delete') {$redk = '';
                    $redv = 'readonly';
                    $disv = 'disabled';
                    $disb = '';
                    $redve = '';
                    $redokadd = 'readonly';
                    $disview = 'disabled';
                    $redLetter = 'disabled';}
                if ($option == 'View') {$redk = 'readonly';
                    $redv = 'none';
                    $disv = 'disabled';
                    $disb = 'disabled';
                    $redve = 'disabled';
                    $redokadd = 'readonly';
                    $disview = 'disabled';
                    $redLetter = 'disabled';}
                if ($option == 'Copy') {$redk = 'readonly';
                    $redv = 'readonly';
                    $disv = 'disabled';
                    $disb = 'disabled';
                    $redve = 'disabled';
                    $redokadd = 'readonly';
                    $disview = 'disabled';
                    $redLetter = 'disabled';}
                if ($option == 'letter') {$redk = 'readonly';
                    $redv = 'readonly';
                    $disv = 'disabled';
                    $disb = 'disabled';
                    $redve = 'disabled';
                    $redokadd = 'readonly';
                    $disview = 'disabled';
                    $redLetter = '';}
         if($this->request->getMethod() == 'post') {
             switch ($option) {
                 case 'Edit':
            $this->db->query("update system_user set updated_by = '$user_id', ip_address = '$ip_address', updated_on = '".date('d-m-Y')."', user_password = '$encryption' where user_id = '$user_id'");
                session()->setFlashdata('message', 'Records Updated Successfully !!');
                return redirect()->to($url);
                break;
         }	
         return view("pages/System/change_password",  compact("option"));
        }
        else
        {
            $sql = "select * from system_user where user_id = '$sessionName' ";
            $data = $this->db->query($sql)->getResultArray()[0];
           // echo '<pre>'; print_r($data);die;
            return view("pages/System/change_password",  compact("option","data"));
        }
    }
    public function matter_merge($option='list')
    {
        $session = session();
         $sessionName=$session->userId;
        $data['requested_url'] = $this->session->requested_end_menu_url; 
         $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
         $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
         $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
         $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
         $new_matter_code           = isset($_REQUEST['new_matter_code'])?$_REQUEST['new_matter_code']:null;
         $new_client_code           = isset($_REQUEST['new_client_code'])?$_REQUEST['new_client_code']:null;
         $old_matter_code           = isset($_REQUEST['old_matter_code'])?$_REQUEST['old_matter_code']:null;
         $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
         $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
         $ip_address        = $_SERVER['REMOTE_ADDR'];
         $url = "system/matter-merge?display_id={$display_id}&menu_id={$menu_id}";
         if($this->request->getMethod() == 'post') {
            switch ($option) {
                case 'merge':
                    $this->db->query("update advance_details set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update arbitrator_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update billinfo_header set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update bill_detail set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update billinfo_header set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update bill_realisation_detail a, bill_realisation_header b set a.matter_code = '$new_matter_code', a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_realisation_serial_no = b.serial_no");
                    $this->db->query("update counsel_memo_detail a, counsel_memo_header b set a.matter_code = '$new_matter_code', a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_counsel_memo_serial_no = b.serial_no ");
                    $this->db->query("update courier_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update court_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update expense_detail set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update ledger_trans_dtl a, ledger_trans_hdr b set a.matter_code = '$new_matter_code', a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_ledger_serial_no = b.serial_no");
                    $this->db->query("update misc_letter_header set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update notice_header set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update photocopy_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update steno_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update voucher_detail a, voucher_header b set a.matter_code = '$new_matter_code', a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_voucher_serial_no = b.serial_no ");
                    $this->db->query("update matter_balance set matter_code = '$new_matter_code' where matter_code = '$old_matter_code'");
                    $this->db->query("insert into fileinfo_header_history select `matter_no`, `matter_code`,`client_code`, `initial_code`,
                    `matter_desc1`, `matter_desc2`,`case_type_code`, `matter_type_code`, 
                    `matter_sub_type_code`, `matter_sub_sub_type_code`, `case_no`, `case_year`, 
                    `date_of_filing`, `court_code`, `judge_name`, `colour`,
                     `billing_addr_code`, `billing_attn_code`, `corrp_addr_code`, `corrp_attn_code`, 
                     `billable_option`, `file_type_code`, `file_locn_code`, `appearing_for_code`,
                     `reference_desc`, `reference_type_code`, `subject_desc`, `listed_ind`, 
                     `psi_ind`, `start_date`, `end_date`, `destroy_date`, 
                     `last_bill_date`, `reject_date`, '',`stake_amount`,
                     `notice_no`, `notice_date`, `apply_oppose_ind`, `remarks`, 
                     `first_activity_date`, `first_fixed_for`, `prepared_by`, `prepared_on`,
                      `last_update_id`, `last_update_dt`, `status_code`  from fileinfo_header where matter_code = '$old_matter_code'");
                    $this->db->query("update fileinfo_header_history set remarks = concat('Merged with Matter Code ','$new_matter_code',' on ','".date('d-m-Y')."',' by ','$sessionName') where matter_code = '$old_matter_code'");
                    $this->db->query("delete from fileinfo_header where matter_code = '$old_matter_code'");
                    session()->setFlashdata('message', 'Matter Merge Successfully !!');
                    return redirect()->to($url);
                    break;
            }
         }
         else{
            $fin_years = $this->db->query("select * from params where year_close_ind = 'N' order by fin_year desc")->getResultArray();
            $current_fin_year = get_current_fin_year();
            return view("pages/System/matter_merge",  compact("option","fin_years", "current_fin_year"));
         }
        
    }
    public function client_merge($option='list')
    {
         $session = session();
         $sessionName=$session->userId;
         $data['requested_url'] = $this->session->requested_end_menu_url; 
         $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
         $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
         $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
         $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
         $new_matter_code           = isset($_REQUEST['new_matter_code'])?$_REQUEST['new_matter_code']:null;
         $new_client_code           = isset($_REQUEST['new_client_code'])?$_REQUEST['new_client_code']:null;
         $old_client_code           = isset($_REQUEST['old_client_code'])?$_REQUEST['old_client_code']:null;
         $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
         $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
         $url = "system/client-merge?display_id={$display_id}&menu_id={$menu_id}";
        if($this->request->getMethod() == 'post') 
            {
            switch ($option) 
            {
                case 'merge':
                    $this->db->query("update advance_details set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update arbitrator_expense set client_code = '$new_client_code' where client_code = '$old_client_code' ");
                    $this->db->query("update billinfo_header set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update bill_detail set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update billing_rate set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update bill_realisation_detail a, bill_realisation_header b set a.client_code = '$new_client_code' where a.client_code = '$old_client_code' and a.ref_realisation_serial_no = b.serial_no ");
                    $this->db->query("update case_header set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update courier_expense set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update court_expense set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update expense_detail set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update ledger_trans_dtl a, ledger_trans_hdr b set a.client_code = '$new_client_code' where a.client_code = '$old_client_code' and a.ref_ledger_serial_no = b.serial_no");
                    $this->db->query("update misc_letter_header set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update notice_header set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update photocopy_expense set client_code = '$new_client_code' where client_code = '$old_client_code'");
                    $this->db->query("update steno_expense set client_code = '$new_client_code' where client_code = '$old_client_code' ");
                    $this->db->query("update voucher_detail a, voucher_header b set a.client_code = '$new_client_code' where a.client_code = '$old_client_code' and a.ref_voucher_serial_no = b.serial_no");
                    $this->db->query("insert into fileinfo_header_history select '', '', `client_code`, 
                    `initial_code`, `matter_desc1`, `matter_desc2`, 
                    `case_type_code`, `matter_type_code`, `matter_sub_type_code`, 
                    `matter_sub_sub_type_code`, `case_no`, `case_year`, 
                    `date_of_filing`, `court_code`, `judge_name`, 
                    `colour`, `billing_addr_code`, `billing_attn_code`,
                    `corrp_addr_code`, `corrp_attn_code`, `billable_option`,
                   `file_type_code`, `file_locn_code`, `appearing_for_code`, 
                   `reference_desc`, `reference_type_code`, `subject_desc`, 
                   `listed_ind`, `psi_ind`, `start_date`,
                    `end_date`, `destroy_date`, `last_bill_date`,
                    `reject_date`,'', `stake_amount`, `notice_no`,`notice_date`,
                    `apply_oppose_ind`, `remarks`, `first_activity_date`, 
                    `first_fixed_for`, `prepared_by`, `prepared_on`,
                    `last_update_id`, `last_update_dt`,`status_code` from fileinfo_header where client_code = '$old_client_code'");
                    $this->db->query("update fileinfo_header_history set remarks = concat('Merged with Client Code ','$new_client_code',' on ','".date('d-m-Y')."',' by ','$sessionName') where client_code = '$old_client_code'");
                    $this->db->query("update fileinfo_header set client_code = '$new_client_code' where client_code = '$old_client_code' ");
                    session()->setFlashdata('message', 'Client Merge Successfully !!');
                    return redirect()->to($url);
                    break;
            }
            }
            else
            {
                $fin_years = $this->db->query("select * from params where year_close_ind = 'N' order by fin_year desc")->getResultArray();
                $current_fin_year = get_current_fin_year();
                return view("pages/System/client_merge",  compact("option","fin_years", "current_fin_year"));
            }
    }
    public function matter_client_updation($option='list')
    {
         $session = session();
         $sessionName=$session->userId;
         $data['requested_url'] = $this->session->requested_end_menu_url; 
         $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
         $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
         $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
         $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
         $fin_year           = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null;
         $old_matter_code           = isset($_REQUEST['old_matter_code'])?$_REQUEST['old_matter_code']:null;
         $old_matter_desc           = isset($_REQUEST['old_matter_desc'])?$_REQUEST['old_matter_desc']:null;
         $old_client_code           = isset($_REQUEST['old_client_code'])?$_REQUEST['old_client_code']:null;
         $old_client_name           = isset($_REQUEST['old_client_name'])?$_REQUEST['old_client_name']:null;
         $new_client_code           = isset($_REQUEST['new_client_code'])?$_REQUEST['new_client_code']:null;
         $new_client_name           = isset($_REQUEST['new_client_name'])?$_REQUEST['new_client_name']:null;
         $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
         $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
         $url = "system/matter-client-updation?display_id={$display_id}&menu_id={$menu_id}";
        if($this->request->getMethod() == 'post') 
            {
                switch ($option) {
                    case 'update':
                    $this->db->query("update advance_details set client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                    $this->db->query("update arbitrator_expense set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update billinfo_header set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update bill_detail set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update billinfo_header set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update bill_realisation_detail a, bill_realisation_header b set a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_realisation_serial_no = b.serial_no ");
                    $this->db->query("update case_header set client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                    $this->db->query("update counsel_memo_detail a, counsel_memo_header b set a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code'  and a.ref_counsel_memo_serial_no = b.serial_no");
                    $this->db->query("update courier_expense set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update court_expense set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update expense_detail set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update ledger_trans_dtl a, ledger_trans_hdr b set a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_ledger_serial_no = b.serial_no");
                    $this->db->query("update misc_letter_header set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update notice_header set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update photocopy_expense set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    

                    $this->db->query("update steno_expense set client_code = '$new_client_code' where matter_code = '$old_matter_code'");
                    $this->db->query("update voucher_detail a, voucher_header b set a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_voucher_serial_no = b.serial_no");
                    $this->db->query("insert into fileinfo_header_history select '', '', `client_code`, 
                    `initial_code`, `matter_desc1`, `matter_desc2`, 
                    `case_type_code`, `matter_type_code`, `matter_sub_type_code`, 
                    `matter_sub_sub_type_code`, `case_no`, `case_year`, 
                    `date_of_filing`, `court_code`, `judge_name`, 
                    `colour`, `billing_addr_code`, `billing_attn_code`,
                    `corrp_addr_code`, `corrp_attn_code`, `billable_option`,
                   `file_type_code`, `file_locn_code`, `appearing_for_code`, 
                   `reference_desc`, `reference_type_code`, `subject_desc`, 
                   `listed_ind`, `psi_ind`, `start_date`,
                    `end_date`, `destroy_date`, `last_bill_date`,
                    `reject_date`,'', `stake_amount`, `notice_no`,`notice_date`,
                    `apply_oppose_ind`, `remarks`, `first_activity_date`, 
                    `first_fixed_for`, `prepared_by`, `prepared_on`,
                    `last_update_id`, `last_update_dt`,`status_code` from fileinfo_header where matter_code = '$old_matter_code'");
                    $this->db->query("update fileinfo_header_history set remarks = concat('Client Code has been changed from',' ','$old_client_code',' ','to',' ','$new_client_code',' on ','".date('d-m-Y')."',' by ','$sessionName') where matter_code = '$old_matter_code' ");
                    $this->db->query("update fileinfo_header set client_code = '$new_client_code',billing_addr_code = '',billing_attn_code = '',corrp_addr_code = '',corrp_attn_code = '' where matter_code = '$old_matter_code'");
                    $this->db->query("update fileinfo_header set remarks = concat('Client Code has been changed from',' ','$old_client_code',' ','to',' ','$new_client_code',' on ','".date('d-m-Y')."',' by ','$sessionName') where matter_code = '$old_matter_code'");
                    session()->setFlashdata('message', 'Client Updated Successfully !!');
                    return redirect()->to($url);  
                    break;
                }
            }
            else
            {
                $fin_years = $this->db->query("select * from params where year_close_ind = 'N' order by fin_year desc")->getResultArray();
                $current_fin_year = get_current_fin_year();
                return view("pages/System/matter_client_updation",  compact("option","fin_years", "current_fin_year"));
       
            }
    }
    public function matter_status_change($option='list')
    {
         $session = session();
         $sessionName=$session->userId;
         $data['requested_url'] = $this->session->requested_end_menu_url; 
         $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
         $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
         $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
         $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
         $fin_year           = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null;
         $new_status_code           = isset($_REQUEST['new_status_code'])?$_REQUEST['new_status_code']:null;
         $matter_code           = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;
         $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
         $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
         $url = "system/matter-status-change?display_id={$display_id}&menu_id={$menu_id}";
        if($this->request->getMethod() == 'post') 
            {
                switch ($option) {
                    case 'Edit':
                        $this->db->query("update fileinfo_header set status_code = '$new_status_code', start_date = '".date('d-m-Y')."', last_update_id = '$sessionName' where matter_code = '$matter_code'");
                        session()->setFlashdata('message', 'Client Updated Successfully !!');
                        return redirect()->to($url);  
                        break;
                } 
            }
            else
            {
                
                $data = $this->db->query("select * from code_master where type_code = '008' order by code_desc ")->getResultArray();
                return view("pages/System/matter_status_change",  compact("option","data")); 
            }
        } 
        public function matter_copy($option='list')
        {
            $session = session();
            $sessionName=$session->userId;
            $data = branches($sessionName);
            $bcode =$data['branch_code'];
            $global_branch_code=$bcode['branch_code'];
            $ip_address = $_SERVER['SERVER_ADDR'];
            $data['requested_url'] = $this->session->requested_end_menu_url; 
            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
            $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
            $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
            $matter_code           = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;
            $matter_desc1           = isset($_REQUEST['matter_desc1'])?$_REQUEST['matter_desc1']:null;
            $matter_desc2           = isset($_REQUEST['matter_desc2'])?$_REQUEST['matter_desc2']:null;
            $client_name           = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;
            $client_code           = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null;
            $initial_name           = isset($_REQUEST['initial_name'])?$_REQUEST['initial_name']:null;
            $initial_code           = isset($_REQUEST['initial_code'])?$_REQUEST['initial_code']:null;
            $court_name           = isset($_REQUEST['court_name'])?$_REQUEST['court_name']:null;
            $court_code           = isset($_REQUEST['court_code'])?$_REQUEST['court_code']:null;
            $judge_name           = isset($_REQUEST['judge_name'])?$_REQUEST['judge_name']:null; 
            $reference_desc           = isset($_REQUEST['reference_desc'])?$_REQUEST['reference_desc']:null; 
            $status_desc           = isset($_REQUEST['status_desc'])?$_REQUEST['status_desc']:null; 
            $status_code           = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:null; 
            $subject_desc           = isset($_REQUEST['subject_desc'])?$_REQUEST['subject_desc']:null;             
            $url = "system/matter-copy?display_id={$display_id}&menu_id={$menu_id}";
            if($this->request->getMethod() == 'post') 
            {
                switch ($option) {
                    case 'Edit':
                        $this->db->query("insert into fileinfo_header 
                      ( matter_code,trust_name,client_code,initial_code,matter_desc1,matter_desc2,case_type_code,matter_type_code,matter_sub_type_code,matter_sub_sub_type_code,case_no,case_year,date_of_filing,judge_name,court_code,colour,
                        billing_addr_code,billing_attn_code,corrp_addr_code,corrp_attn_code,billable_option,file_type_code,file_locn_code,appearing_for_code,reference_desc,reference_type_code,subject_desc,listed_ind,psi_ind,
                        start_date,end_date,destroy_date,last_bill_date,reject_date,requisition_no,stake_amount,notice_no,notice_date,apply_oppose_ind,prepared_by,prepared_on,last_update_id,last_update_dt,status_code
                      )
                  select 
                        matter_code,trust_name,client_code,initial_code,matter_desc1,matter_desc2,case_type_code,matter_type_code,matter_sub_type_code,matter_sub_sub_type_code,case_no,case_year,date_of_filing,judge_name,court_code,colour,
                        billing_addr_code,billing_attn_code,corrp_addr_code,corrp_attn_code,billable_option,file_type_code,file_locn_code,appearing_for_code,reference_desc,reference_type_code,subject_desc,listed_ind,psi_ind,
                        start_date,end_date,destroy_date,last_bill_date,reject_date,requisition_no,stake_amount,notice_no,notice_date,apply_oppose_ind,prepared_by,prepared_on,last_update_id,last_update_dt,status_code
                   from fileinfo_header where matter_code = '$matter_code' ") ;
                   $lastno_qry= $this->db->query("select MAX(matter_no) AS lastid from fileinfo_header")->getResultArray()[0];
                   $lastno_datNo = $lastno_qry ; 
                   $lastno_dat = $lastno_datNo['lastid'] ; 
                   $new_matter = str_pad($lastno_datNo['lastid']+1,6,'0',STR_PAD_LEFT) ;
                   $this->db->query("update fileinfo_header set matter_code = '$new_matter', start_date = '".date('d-m-Y')."', status_code = 'A', branch_code = '$global_branch_code', prepared_by = '$sessionName', prepared_ip = '$ip_address', prepared_on = '".date('d-m-Y')."', last_update_id='', last_update_dt='', last_update_time='', new_matter = 'NEW' where matter_no = '$lastno_dat'");
                   $this->db->query("insert into fileinfo_header_history 
                     (
					   matter_code,record_code,row_no,name,address_line_1,address_line_2,address_line_3,address_line_4,city,pin_code,state_name,country,
					   isd_code,std_code,phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel
					 ) 
			         select 
					   'matter_code',record_code,row_no,name,address_line_1,address_line_2,address_line_3,address_line_4,city,pin_code,state_name,country,
					   isd_code,std_code,phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel
					 from fileinfo_details where matter_code = '$matter_code' ");
                     $this->db->query("insert into fileinfo_case_details (matter_code,case_details) select '$new_matter',case_details from fileinfo_case_details where matter_code = '$matter_code'");
                     $this->db->query("insert into fileinfo_counsels (matter_code,record_code,row_no,initial_code) select '$new_matter',record_code,row_no,initial_code from fileinfo_counsels where matter_code = '$matter_code'");
                     $this->db->query("insert into fileinfo_original_record (matter_code,row_no,record_desc,remarks,file_location,receive_date,return_date) select '$new_matter',row_no,record_desc,remarks,file_location,receive_date,return_date from fileinfo_original_record where matter_code = '$matter_code' ");
                     $this->db->query("insert into fileinfo_other_cases (matter_code,row_no,case_no,subject_desc) select '$new_matter',row_no,case_no,subject_desc from fileinfo_other_cases where matter_code = '$matter_code'");
                     $this->db->query("insert into fileinfo_related_matters (matter_code,row_no,related_matter_code) select '$new_matter',row_no,related_matter_code from fileinfo_related_matters where matter_code = '$matter_code' ");
                     session()->setFlashdata('message', 'Matter Coping Successfull !!');
                     return redirect()->to($url);  
                     break;
                } 
            }
            else
            {
                return view("pages/System/matter_copy",  compact("option")); 
            }
        }   
        public function holiday_master($option='list')
        {
         $session = session();
         $sessionName=$session->userId;
         $data['requested_url'] = $this->session->requested_end_menu_url; 
         $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
         $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
         $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
         $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
         $fin_year           = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null;
         $calendar_year           = isset($_REQUEST['calendar_year'])?$_REQUEST['calendar_year']:null;
         $calendar_year2           = isset($_REQUEST['calendar_year2'])?$_REQUEST['calendar_year2']:null;
         $tRowCount           = isset($_REQUEST['tRowCount'])?$_REQUEST['tRowCount']:null;
         //echo $tRowCount;die;
         $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
         $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
         $url = "system/holiday-master?display_id={$display_id}&menu_id={$menu_id}";
        // echo $calendar_year;die;
        if($this->request->getMethod() == 'post') 
            {
                if($finsub=='nsub')
                {
                        $fin_years = $this->db->query("select * from params where year_close_ind = 'N' order by fin_year desc")->getResultArray();
                        $current_fin_year = get_current_fin_year();
                        $years = $this->db->query("select * from holiday_master where calendar_year = '$calendar_year' order by holiday_date")->getResultArray();
                        return view("pages/System/holiday_master",  compact("option", "years","fin_years", "current_fin_year"));
                }
                if($finsub=='fsub')
                {
                        $this->db->query("delete from holiday_master WHERE calendar_year = '$calendar_year2'");
                        for($i=1;$i<=$tRowCount;$i++)
                        {
                            if(!empty($_REQUEST['holiday_date'.$i]))
                            {
                            $this->db->query("insert into holiday_master (`calendar_year`,`holiday_date`,`holiday_day`,`holiday_desc`) values ('".$calendar_year2."','".date('Y-m-d',strtotime($_REQUEST['holiday_date'.$i]))."','".$_REQUEST['holiday_day'.$i]."','".$_REQUEST['holiday_desc'.$i]."')");
                            }
                        }
                        session()->setFlashdata('message', 'Holiday Added Successfully !!');
                        return redirect()->to($url);  
                } 
            }
            else
            {
                
                $fin_years = $this->db->query("select * from params where year_close_ind = 'N' order by fin_year desc")->getResultArray();
                $current_fin_year = get_current_fin_year();
                return view("pages/System/holiday_master",  compact("option","fin_years", "current_fin_year"));
            }
        }  
   public function schedule_task($option='list')
       {
            $session = session();
            $sessionName=$session->userId;
            $data['requested_url'] = $this->session->requested_end_menu_url; 
            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
            $fin_year           = isset($_REQUEST['fin_year'])?$_REQUEST['fin_year']:null;
            $user_id           = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:null;
            $start_date           = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null;
            $task_desc           = isset($_REQUEST['task_desc'])?$_REQUEST['task_desc']:null;
            $task_freq           = isset($_REQUEST['task_freq'])?$_REQUEST['task_freq']:null;
            $task_day           = isset($_REQUEST['task_day'])?$_REQUEST['task_day']:null;
            $adv_notice           = isset($_REQUEST['adv_notice'])?$_REQUEST['adv_notice']:null;
            $status           = isset($_REQUEST['status'])?$_REQUEST['status']:null;
            $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
            $option           = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
            $task_id           = isset($_REQUEST['task_id'])?$_REQUEST['task_id']:null;
            if ($option == 'Add') {$redk = '';
                $redv = '';
                $disv = '';
                $disb = '';
                $redve = '';
                $redokadd = '';
                $disview = '';
                $redLetter = 'disabled';}
            if ($option == 'Edit') {$redk = '';
                $redv = '';
                $disv = 'disabled';
                $disb = '';
                $redve = 'disabled';
                $redokadd = 'readonly';
                $disview = '';
                $redLetter = 'disabled';}
            if ($option == 'Delete') {$redk = '';
                $redv = 'readonly';
                $disv = 'disabled';
                $disb = '';
                $redve = '';
                $redokadd = 'readonly';
                $disview = 'disabled';
                $redLetter = 'disabled';}
            if ($option == 'View') {$redk = 'readonly';
                $redv = 'none';
                $disv = 'disabled';
                $disb = 'disabled';
                $redve = 'disabled';
                $redokadd = 'readonly';
                $disview = 'disabled';
                $redLetter = 'disabled';}
            if ($option == 'Copy') {$redk = 'readonly';
                $redv = 'readonly';
                $disv = 'disabled';
                $disb = 'disabled';
                $redve = 'disabled';
                $redokadd = 'readonly';
                $disview = 'disabled';
                $redLetter = 'disabled';}
            if ($option == 'letter') {$redk = 'readonly';
                $redv = 'readonly';
                $disv = 'disabled';
                $disb = 'disabled';
                $redve = 'disabled';
                $redokadd = 'readonly';
                $disview = 'disabled';
                $redLetter = '';}


            $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
         //   echo $finsub ;die;
            if($this->request->getMethod() == 'post') 
                {
                    if($finsub=="fsub")
                    {
                        switch ($option) 
                        {
                            case 'Add':
                               // echo  $next_day=$task_freq." ".$task_day;	die;


                                $givenDate = $start_date;
                    
                                // Convert the given date to a DateTime object
                                $dateTime = DateTime::createFromFormat('d-m-Y', $givenDate);
                                
                                // Given number of days to add
                                $daysToAdd = 5; // Change this to the number of days you want to add
                                
                                // Add the specified number of days to the date
                                $dateTime->add(new DateInterval('P' . $daysToAdd . 'D'));
                                
                                // Get the next date
                                $nextDate = $dateTime->format('d-m-Y');
                               // echo "Next Date after $daysToAdd days: '".)."'";
                               $next_day=$daysToAdd. " days";
                               $next_date=date('Y-m-d',strtotime($nextDate));
                               $this->db->query("insert into scheduled_task_hdr(user_id,start_date,task_desc,task_freq,next_date,advance_notice_period,status) values ('$user_id','".date('Y-m-d',strtotime($start_date))."','$task_desc','$next_day','$next_date','$adv_notice','$status')");
                               session()->setFlashdata('message', 'Schedule Added Successfully !!');
                               return redirect()->to($url);  
                                break;
                            
                            case 'Delete':
                                $sql =  $this->db->query("delete  from scheduled_task_hdr where task_id = '".$task_id."'");
                                session()->setFlashdata('message', 'Record Deleted Successfully !!');
                                return redirect()->to($url);  
                                break;
                        }
                    }
                    if($finsub=="" || $finsub!="fsub")
                    {
                        switch ($option) 
                        {
                            case 'Edit':
                                $sql = "select user_id,user_name from system_user where status_code = 'Active' order by user_name";
                                $sql2 = "select *  from scheduled_task_hdr where task_id = '".$task_id."'";
                                break;
                            case 'View':
                                $sql = "select user_id,user_name from system_user where status_code = 'Active' order by user_name";
                                $sql2 = "select *  from scheduled_task_hdr where task_id = '".$task_id."'";
                                break;
                            case 'Add':
                                $sql = "select user_id,user_name from system_user where status_code = 'Active' order by user_name";
                                break;
                            case 'Delete':
                                $sql = "select user_id,user_name from system_user where status_code = 'Active' order by user_name";
                                $sql2 = "select *  from scheduled_task_hdr where task_id = '".$task_id."'";
                                break;
                        }
                        if ($option != 'Add') {
                            $data = $this->db->query($sql)->getResultArray();
                            $data2 = $this->db->query($sql2)->getResultArray()[0];
                            return view("pages/System/schedule_task",  compact("option","data","data2","redokadd","disview"));
                        } else {
                            $data = $this->db->query($sql)->getResultArray();
                            return view("pages/System/schedule_task",  compact("option","data","redokadd","disview"));
                        }
                    } 
                }
       }   
     public function disp_master_report($option='list')
     { 
        $session = session();
            $sessionName=$session->userId;
            $data['requested_url'] = $this->session->requested_end_menu_url; 
            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
            $year           = isset($_REQUEST['year'])?$_REQUEST['year']:null;
            $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
            $maxYear = "select MAX(calendar_year) as year from holiday_master";
            $permdate = $this->db->query($maxYear)->getResultArray();
            $yr= implode(' ',$permdate[0]);
            if($year!=''){
             $years = $this->db->query("select * from holiday_master where calendar_year='".$year."' order by holiday_date")->getResultArray();
            }
            else
            {
                $years = $this->db->query("select * from holiday_master where calendar_year='".$yr."' order by holiday_date")->getResultArray();  
            }
        return view("pages/dispMasterReport",  compact("years","year","yr"));
     }
     public function matter_data_transfer($option='list')
     {
            $session = session();
            $sessionName=$session->userId;
            $data['requested_url'] = $this->session->requested_end_menu_url; 
            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
            $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
            $new_matter_code           = isset($_REQUEST['new_matter_code'])?$_REQUEST['new_matter_code']:null;
            $new_client_code           = isset($_REQUEST['new_client_code'])?$_REQUEST['new_client_code']:null;
            $old_matter_code           = isset($_REQUEST['old_matter_code'])?$_REQUEST['old_matter_code']:null;
            $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
            $url = "system/matter-data-transfer?display_id={$display_id}&menu_id={$menu_id}";
            if($this->request->getMethod() == 'post') 
                {
                    if($finsub=='fsub')
                    {
                       // echo "update advance_details set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ";die;
                        $this->db->query("update advance_details set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update arbitrator_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'  ");
                        $this->db->query("update billinfo_header set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'  ");
                        $this->db->query("update bill_detail set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update billinfo_header set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update bill_realisation_detail a, bill_realisation_header b set a.matter_code = '$new_matter_code', a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_realisation_serial_no = b.serial_no ");

                        $this->db->query("update case_header set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update counsel_memo_detail a, counsel_memo_header b set a.matter_code = '$new_matter_code', a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_counsel_memo_serial_no = b.serial_no ");
                        $this->db->query("update courier_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code'" );
                        $this->db->query("update court_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update expense_detail set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update ledger_trans_dtl a, ledger_trans_hdr b set a.matter_code = '$new_matter_code', a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_ledger_serial_no = b.serial_no ");
                        $this->db->query("update misc_letter_header set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update notice_header set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update photocopy_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update steno_expense set matter_code = '$new_matter_code', client_code = '$new_client_code' where matter_code = '$old_matter_code' ");
                        $this->db->query("update voucher_detail a, voucher_header b set a.matter_code = '$new_matter_code', a.client_code = '$new_client_code' where a.matter_code = '$old_matter_code' and a.ref_voucher_serial_no = b.serial_no ");
                        $this->db->query("update matter_balance set matter_code = '$new_matter_code' where matter_code = '$old_matter_code' ");


                        $this->db->query("insert into fileinfo_header_history 
                        ( matter_code,client_code,initial_code,matter_desc1,matter_desc2,case_type_code,matter_type_code,matter_sub_type_code,matter_sub_sub_type_code,case_no,case_year,date_of_filing,judge_name,court_code,colour,
                          billing_addr_code,billing_attn_code,corrp_addr_code,corrp_attn_code,billable_option,file_type_code,file_locn_code,appearing_for_code,reference_desc,reference_type_code,subject_desc,listed_ind,psi_ind,
                          start_date,end_date,destroy_date,last_bill_date,reject_date,stake_amount,notice_no,notice_date,apply_oppose_ind,prepared_by,prepared_on,last_update_id,last_update_dt,status_code
                        )
                    select 
                          matter_code,client_code,initial_code,matter_desc1,matter_desc2,case_type_code,matter_type_code,matter_sub_type_code,matter_sub_sub_type_code,case_no,case_year,date_of_filing,judge_name,court_code,colour,
                          billing_addr_code,billing_attn_code,corrp_addr_code,corrp_attn_code,billable_option,file_type_code,file_locn_code,appearing_for_code,reference_desc,reference_type_code,subject_desc,listed_ind,psi_ind,
                          start_date,end_date,destroy_date,last_bill_date,reject_date,stake_amount,notice_no,notice_date,apply_oppose_ind,prepared_by,prepared_on,last_update_id,last_update_dt,status_code
                     from fileinfo_header where matter_code = '$old_matter_code' ");


                        $this->db->query("update fileinfo_header_history set remarks = concat('All data of ','$old_matter_code',' Transfered to ','$new_matter_code',' on ','".date('d-m-Y')."',' by ','$sessionName') where matter_code = '$old_matter_code' ");
                        session()->setFlashdata('message', 'Matter Data Transfer Successfully !!');
                        return redirect()->to($url);  
                    }
                    if($finsub=="" || $finsub!="fsub")
                    {

                    }

                }
                else
                {
                    $fin_years = $this->db->query("select * from params where year_close_ind = 'N' order by fin_year desc")->getResultArray();
                    $current_fin_year = get_current_fin_year();
                    return view("pages/System/matter_data_transfer",  compact("option","fin_years","current_fin_year"));
                }
            
     }
     public function excel_files($option='list')
       {
            $session = session();
            $sessionName=$session->userId;
            $data['requested_url'] = $this->session->requested_end_menu_url; 
            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
            $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
            $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
            $url = "system/matter-data-transfer?display_id={$display_id}&menu_id={$menu_id}";
            if($this->request->getMethod() == 'post') 
                {
                    // $sql = "select file_name,file,stored_file_name from excel_file"; 
                    // $data = $this->db->query($sql)->getResultArray();
                    // return view("pages/System/excel_files",  compact("option","data")); 
                }
                else
                {
                    $sql = "select id,file_name,file,stored_file_name from excel_file"; 
                    $data = $this->db->query($sql)->getResultArray();
                    return view("pages/System/excel_files",  compact("option","data")); 
                }
       }
public function download_excel()
{
    $id           = isset($_REQUEST['id'])?$_REQUEST['id']:null;
    $sql = "select file_name,file,stored_file_name from excel_file where id='$id'";
    $data = $this->db->query($sql)->getResultArray()[0];
    $excelFilePath = WRITEPATH . 'uploads/excel_files/'.$data['stored_file_name']; // Replace with the actual path to your Excel file

    if (file_exists($excelFilePath)) {
        // Set appropriate headers
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.$data['file_name'].'.xlsx'); // Replace with the desired filename

        // Read the file and output it to the browser
        readfile($excelFilePath);
       
    } else {
        // Handle file not found error
        echo 'Excel file not found.';
    }
    $sql = "select id,file_name,file,stored_file_name from excel_file"; 
    $data = $this->db->query($sql)->getResultArray();
    return view("pages/System/excel_files",  compact("data")); 
}
public function notice_download()
{
    $session = session();
            $sessionName=$session->userId;
            $data['requested_url'] = $this->session->requested_end_menu_url; 
            $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
            $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
            $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
            $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
            $finsub           = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
            $option           = isset($_REQUEST['option'])?$_REQUEST['option']:null;
            $url = "system/notice-download?display_id={$display_id}&menu_id={$menu_id}";
            if($this->request->getMethod() == 'post') 
                {

                }
                else
                { 
                    $sql = "select a.serial_no,a.file_type,a.file_name_system,a.file_name_original,a.description,a.hr_serial_no,a.uploaded_by,b.user_name,a.uploaded_on 
                    from hr_upload_files a, system_user b
                   where a.status_code = 'A'
                         and a.uploaded_by = b.user_id
                order by a.file_name_original"; 
                    $data = $this->db->query($sql)->getResultArray();

                    return view("pages/System/notice_download",  compact("option","data")); 
                }
}
public function download_notice()
        {
            $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
            $sql = "select * FROM hr_upload_files WHERE serial_no='$id'";
            $data = $this->db->query($sql)->getResultArray()[0];

            //$pdfFilePath = WRITEPATH . 'uploads/hr_file_upload/' . $data['file_name_system'];
            $pdfFilePath = WRITEPATH .'uploads/hr_file_upload/' . $data['file_name_system'];
            // Check if the file exists
            if (file_exists($pdfFilePath)) {
                // Load the download helper
                helper('download');
    
                // Set the file mime type
                $mime = mime_content_type($pdfFilePath);
    
                // Generate the response
                $response = $this->response->download($pdfFilePath, null, true)->setFileName($data['file_name_original'])->setContentType($mime);
    
                // Return the response to initiate the download
                return $response;
            } else {
                // File not found
                return 'PDF file not found!';
            }

            $sql = "SELECT a.serial_no, a.file_type, a.file_name_system, a.file_name_original, a.description, a.hr_serial_no, a.uploaded_by, b.user_name, a.uploaded_on 
                    FROM hr_upload_files a, system_user b
                    WHERE a.status_code = 'A'
                    AND a.uploaded_by = b.user_id
                    ORDER BY a.file_name_original";

            $data = $this->db->query($sql)->getResultArray();

            return view("pages/System/notice_download", compact("data"));
        }

     
}


?>