<?php

namespace App\Controllers;

//use App\Models\User;

class MasterController extends BaseController
{
    public function __construct()
    {
        $this->db = \config\database::connect();
        $this->session = session();
    }

    public function index()
    {
        // $data = $this->db- >query("select count(*) ,sum(link_width) from display_link_detail where display_id = 2004 and link_type = 'D'")->getResultArray();
        // $data = $this->db->query("select * from display_header where display_id = 2004")->getResultArray();

        // $user = new User();

        // $data = $user->findAll();
        // echo "<pre>"; print_r($data); die;

        return view("bar");
    }
    public function master_list($action = null)
    { //echo $action;die;
        //edited By Sylvester
        $perm = "select * from permission WHERE permission_on='0'";
        $permdata = $this->db->query($perm)->getResultArray();
        //edit end
        $display_id = $this->request->getVar("display_id");
        $menu_id = $this->request->getVar("menu_id");
        $option = "list";
        $params = ["displayId" => $display_id, "menuId" => $menu_id, "option" => $option];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        // /echo $action;die;
        switch ($action) {
            case null:
                $report_type = "";
                
                if($this->request->getMethod() == 'post' && isset($_POST['output_type'])) {
                    if($_POST['output_type'] == 'Pdf') {
                        $data = display_list('Pdf');
                        $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                        $reportHTML = view("pages/master_list", compact("url", "data", "params", "permdata", "report_type"));
                        $dompdf->loadHtml($reportHTML);
                        $dompdf->setPaper('A4', 'landscape'); // portrait
                        $dompdf->render();
                        $dompdf->stream($data['heading'].date(' - d-m-Y').'.pdf', array('Attachment'=>0));
                        exit; //return redirect('', 'refresh');
                    }
                } else {
                    $data = display_list();
                    return view("pages/master_list", compact("url", "data", "params", "permdata")); // added permdata by sylvester
                } 

            case 'mas-account-master-pl':
                $session = session();
                $sessionName = $session->userId;
                $data = branches($sessionName);
                $data['requested_url'] = $this->session->requested_end_menu_url;
                $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
                $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
                $uid = isset($_REQUEST['main_ac_code']) ? $_REQUEST['main_ac_code'] : null;
                $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
                $pg = isset($_REQUEST['pg']) ? $_REQUEST['pg'] : null;
                $perm = "select max(main_ac_code) as `maxValue` from account_master";
                $mainAcCode = $this->db->query($perm)->getResultArray()[0];
                $main_ac_code = $mainAcCode['maxValue'] + 1;
                if ($option == 'Edit') {
                    $mainAcCode = isset($_REQUEST['main_ac_code']) ? $_REQUEST['main_ac_code'] : null;
                }
                $main_ac_desc = isset($_REQUEST['main_ac_desc']) ? $_REQUEST['main_ac_desc'] : null;
                $account_type_code = isset($_REQUEST['account_type_code']) ? $_REQUEST['account_type_code'] : null;
                $act_group_desc = isset($_REQUEST['act_group_desc']) ? $_REQUEST['act_group_desc'] : null;
                $act_group_code = isset($_REQUEST['act_group_code']) ? $_REQUEST['act_group_code'] : null;
                $bspl_desc = isset($_REQUEST['bspl_desc']) ? $_REQUEST['bspl_desc'] : null;
                $bspl_code = isset($_REQUEST['bspl_code']) ? $_REQUEST['bspl_code'] : null;
                $bspl_desc_adv = isset($_REQUEST['bspl_desc_adv']) ? $_REQUEST['bspl_desc_adv'] : null;
                $bspl_code_adv = isset($_REQUEST['bspl_code_adv']) ? $_REQUEST['bspl_code_adv'] : null;
                $sub_ac_ind = isset($_REQUEST['sub_ac_ind']) ? $_REQUEST['sub_ac_ind'] : null;
                $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
                $status_date = isset($_REQUEST['status_date']) ? $_REQUEST['status_date'] : null;
                $segregated_ind = isset($_REQUEST['segregated_ind']) ? $_REQUEST['segregated_ind'] : null;
                $opening_date = isset($_REQUEST['opening_date']) ? $_REQUEST['opening_date'] : null;
                $update_id = isset($_REQUEST['update_id']) ? $_REQUEST['update_id'] : null;
                $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
                $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
                $sql = '';
                if($this->request->getMethod() == 'post') {
                    
                    if($display_id!="" & $finsub!="fsub")
                    {
                        switch ($option) {
                            case 'Edit':
                                $sql = "select *,account_master.status_code as statusCode from account_master INNER JOIN code_master ON code_master.code_code=account_master.account_group_code WHERE account_master.main_ac_code='$uid' AND code_master.type_code='023'";
                                break;
                            case 'View':
                                $sql = "select *,account_master.status_code as statusCode from account_master INNER JOIN code_master ON code_master.code_code=account_master.account_group_code WHERE account_master.main_ac_code='$uid' AND code_master.type_code='023'";
                                break;
    
                            default:
                            case 'Add':
                                $sql = "select max(client_code) as `maxValue` from account_master";
                                break;
                            case 'Delete':
                                $sql = "select *,account_master.status_code as statusCode from account_master INNER JOIN code_master ON code_master.code_code=account_master.account_group_code WHERE account_master.main_ac_code='$uid' AND code_master.type_code='023'";
                                break;       
                        }
                        if ($option != 'Add') {
                            $data = $this->db->query($sql)->getResultArray()[0];
                            return view("pages/Master/mas-account-master-pl", compact("url", "data", "params", "option","redokadd","disview"));
                        }
                        if ($option == 'Add') {
                            $data = [];
                            return view("pages/Master/mas-account-master-pl", compact("url", "data", "params", "option","redokadd","disview"));
                        }
                    }
                    if($finsub!="" || $finsub=="fsub"){
                    switch ($option) {
                        case 'Add':
                            $this->db->query("insert into `account_master`(`main_ac_code`, `main_ac_desc`, `account_group_code`, `account_type_code`, `sub_ac_ind`, `segregated_ind`, `status_code`, `status_date`,`opening_date`) VALUES ('$main_ac_code','$main_ac_desc','$act_group_code','$account_type_code','$sub_ac_ind','$segregated_ind','$status_code','$status_date','$opening_date')");
                            return redirect()->to($url);
                            break;

                        case 'Edit':
                            $this->db->query("update account_master set  `main_ac_desc`='$main_ac_desc', `account_group_code`='$act_group_code', `account_type_code`='$account_type_code', `sub_ac_ind`='$sub_ac_ind', `segregated_ind`='$segregated_ind', `status_code`='$status_code',`update_id`='$update_id' where main_ac_code='$mainAcCode'");
                            session()->setFlashdata('message', 'Records Updated Successfully !!');
                            return redirect()->to($url);
                            break;
                        case 'Delete':
                            $this->db->query("delete from account_master where main_ac_code='".$_REQUEST['main_ac_code']."'");
                            session()->setFlashdata('message', 'Records Deleted Successfully !!');
                            return redirect()->to($url);
                        break;
                        }
                    }
                }
            case 'client-master':
                $sql = '';
                $sql1 = '';
                $session = session();
                $sessionName = $session->userId;
                $data = branches($sessionName);
                $data['requested_url'] = $this->session->requested_end_menu_url;
                $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
                $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
                $client_name = isset($_REQUEST['client_name']) ? $_REQUEST['client_name'] : null;
                $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
                $uid = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
                $client_nm = strtoupper(stripslashes($client_name));
                $first_letter = substr($client_nm, 0, 1);
                $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
                $client_group_name = isset($_REQUEST['client_group_id']) ? $_REQUEST['client_group_id'] : null;
                $credit_limit_amount = isset($_REQUEST['credit_limit_amount']) ? $_REQUEST['credit_limit_amount'] : null;
                $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
                $referred_by = isset($_REQUEST['referred_by']) ? $_REQUEST['referred_by'] : null;
                $new_client = isset($_REQUEST['new_client']) ? $_REQUEST['new_client'] : null;
                $prepared_on = isset($_REQUEST['prepared_on']) ? $_REQUEST['prepared_on'] : null;
                $prepared_by = isset($_REQUEST['prepared_by']) ? $_REQUEST['prepared_by'] : null;
                $updated_on = isset($_REQUEST['updated_on']) ? $_REQUEST['updated_on'] : null;
                $updated_by = isset($_REQUEST['updated_by']) ? $_REQUEST['updated_by'] : null;
                $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
                $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
                if ($this->request->getMethod() == 'post') {
                    if($finsub=="fsub")
                    {
                    switch ($option) {
                        case 'list':
                            $sql = "select substring(max(client_code),2) as `maxValue` from client_master";
                            break;
                        case 'Add':
                            $sql1 = "select * from client_master where client_code='$uid'";
                            $this->db->query("insert into client_master (client_code, client_group_code, client_name, credit_limit_amount,mobile_no,referred_by,new_client,prepared_on,prepared_by) values ('$first_letter$client_code', '$client_group_name', '$client_name', '$credit_limit_amount','$mobile_no','$referred_by','$new_client','$prepared_on','$prepared_by')");
                            session()->setFlashdata('message', 'Records Added Successfully !!');
                            session()->setFlashdata('message', 'Records Added Successfully !! <br> Client Code Is : ' . $first_letter.$uid);
                            return redirect()->to($url);
                            break;
                        case 'Edit':
                            $this->db->query("update client_master set client_group_code='$client_group_name', client_name='$client_name', credit_limit_amount='$credit_limit_amount',mobile_no='$mobile_no',referred_by='$referred_by',new_client='$new_client',updated_on='$updated_on',updated_by='$updated_by' where client_code='$uid'");
                            session()->setFlashdata('message', 'Records Updated Successfully !!');
                            return redirect()->to($url);
                            break;
                        case 'Delete':
                            $this->db->query("delete from client_master where client_code='$uid'");
                            session()->setFlashdata('message', 'Records Delete Successfully !!');
                            return redirect()->to($url);
                            break;                            
                        }
                    }
                    if($finsub=="" || $finsub!="fsub")
                    {
                        switch ($option) {
                            case 'Edit':
                                $sql1 = "select substring(max(client_code),2) as `maxValue` from client_master";
                                $sql = "select * from client_master  INNER JOIN code_master ON client_master.client_group_code= code_master.code_code where client_master.client_code='$client_code' AND code_master.type_code='022' AND code_master.status_code = 'Active'";
                                break;
                            case 'Add':
                                $sql = "select substring(max(client_code),2) as `maxValue` from client_master";
                                $sql1 = "select * from client_master";
                                break;
                            case 'View':
                                $sql1 = "select substring(max(client_code),2) as `maxValue` from client_master";
                                $sql = "select * from client_master  INNER JOIN code_master ON client_master.client_group_code= code_master.code_code where client_master.client_code='$client_code' AND code_master.type_code='022' AND code_master.status_code = 'Active'";
                                break;
                            case 'Delete':
                                $sql1 = "select substring(max(client_code),2) as `maxValue` from client_master";
                                $sql = "select * from client_master  INNER JOIN code_master ON client_master.client_group_code= code_master.code_code where client_master.client_code='$client_code' AND code_master.type_code='022' AND code_master.status_code = 'Active'";
                                break;
                        }
                        $data = $this->db->query($sql)->getResultArray()[0];
                        $data1 = $this->db->query($sql1)->getResultArray();
                        return view("pages/Master/client_master", compact("url", "display_id", "menu_id", "option", "data", "data1", "redk", "disv", "redv","redokadd","disview"));
                    }
                }
                break;

            case 'client-address-addedit':
                $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
                $sql = '';
                $sql1 = '';
                $session = session();
                $sessionName = $session->userId;
                $data = branches($sessionName);
                $data['requested_url'] = $this->session->requested_end_menu_url;
                $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
                $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
                $uid = isset($_REQUEST['address_code']) ? $_REQUEST['address_code'] : null;
                $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
                $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
                $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
                $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
                $state_code = isset($_REQUEST['state_code']) ? $_REQUEST['state_code'] : null;
                $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : null;
                $pan_no = isset($_REQUEST['pan_no']) ? $_REQUEST['pan_no'] : null;
                $isd_code = isset($_REQUEST['isd_code']) ? $_REQUEST['isd_code'] : null;
                $std_code = isset($_REQUEST['std_code']) ? $_REQUEST['std_code'] : null;
                $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
                $fax_no = isset($_REQUEST['fax_no']) ? $_REQUEST['fax_no'] : null;
                $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
                $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
                $web_id = isset($_REQUEST['web_id']) ? $_REQUEST['web_id'] : null;
                $client_gst = isset($_REQUEST['client_gst']) ? $_REQUEST['client_gst'] : null;
                $prepared_on = isset($_REQUEST['prepared_on']) ? $_REQUEST['prepared_on'] : null;
                $prepared_by = isset($_REQUEST['prepared_by']) ? $_REQUEST['prepared_by'] : null;
                $updated_on = isset($_REQUEST['updated_on']) ? $_REQUEST['updated_on'] : null;
                $updated_by = isset($_REQUEST['updated_by']) ? $_REQUEST['updated_by'] : null;
                $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
                $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
                if ($option == 'Delete') {$redk = '';
                    $redv = 'readonly';
                    $disv = 'disabled';
                    $disb = '';
                    $redve = '';
                    $redokadd = '';
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
                    $disview = '';
                    $redLetter = 'disabled';}
                if ($option == 'letter') {$redk = 'readonly';
                    $redv = 'readonly';
                    $disv = 'disabled';
                    $disb = 'disabled';
                    $redve = 'disabled';
                    $redokadd = 'readonly';
                    $disview = 'disabled';
                    $redLetter = '';}
                if ($this->request->getMethod() == 'post') {
                    if($finsub=="fsub")
                    {
                    switch ($option) {
                        // case 'list':
                        //     $sql="select substring(max(client_code),2) as `maxValue` from client_master";
                        // break;
                        case 'Add':
                            $this->db->query("insert into  client_address (client_code , address_line_1, city, pin_code,state_code,country,pan_no,isd_code,std_code,phone_no,fax_no,mobile_no,email_id,web_id,client_gst,prepared_on,prepared_by) values ('$client_code ', '$address_line_1', '$city', '$pin_code','$state_code','$country','$pan_no','$isd_code','$std_code','$phone_no','$fax_no','$mobile_no','$email_id','$web_id','$client_gst','$prepared_on','$prepared_by')");
                            session()->setFlashdata('message', 'Records Added Successfully !!');
                             return redirect()->to($url);
                            break;
                        case 'Copy':
                            $this->db->query("insert into  client_address (client_code , address_line_1, city, pin_code,state_code,country,pan_no,isd_code,std_code,phone_no,fax_no,mobile_no,email_id,web_id,client_gst,prepared_on,prepared_by) values ('$client_code ', '$address_line_1', '$city', '$pin_code','$state_code','$country','$pan_no','$isd_code','$std_code','$phone_no','$fax_no','$mobile_no','$email_id','$web_id','$client_gst','$prepared_on','$prepared_by')");
                            session()->setFlashdata('message', 'Records Copied Successfully !!');
                           return redirect()->to($url);
                            break;
                        case 'Edit':
                            $this->db->query("update client_address set  address_line_1='$address_line_1', city='$city', pin_code='$pin_code',state_code='$state_code',country='$country',pan_no='$pan_no',isd_code='$isd_code',std_code='$std_code',phone_no='$phone_no',fax_no='$fax_no',mobile_no='$mobile_no',email_id='$email_id',web_id='$web_id',client_gst='$client_gst',updated_by='$updated_by',updated_on='$updated_on' where address_code='$uid'");
                            session()->setFlashdata('message', 'Records Updated Successfully !!');
                            return redirect()->to($url);
                            break;
                        case 'Delete':
                            $this->db->query("delete from client_address  where address_code='$uid'");
                            session()->setFlashdata('message', 'Records Deleted Successfully !!');
                            return redirect()->to($url);
                            break;
                        }
                    }
                    if($finsub=="" || $finsub!="fsub")
                    {
                        switch ($option) {
                            case 'Add':
                                $sql = "select* from state_master";
                                $sql1 = "select *,client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4,client_address.city,client_address.pan_no,client_address.isd_code,client_address.std_code,client_address.phone_no,client_address.fax_no,client_address.mobile_no,client_address.email_id,client_address.web_id,client_address.client_gst,client_address.prepared_by,client_address.prepared_on from client_address INNER JOIN client_master ON client_master.client_code = client_address.client_code";
                                break;
                            case 'Edit':
                                $sql = "select * from state_master";
                                $sql1 = "select *,client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4,client_address.city,client_address.pan_no,client_address.isd_code,client_address.std_code,client_address.phone_no,client_address.fax_no,client_address.mobile_no,client_address.email_id,client_address.web_id,client_address.client_gst,client_address.prepared_by,client_address.prepared_on from client_address INNER JOIN client_master ON client_master.client_code = client_address.client_code where client_address.address_code='$uid'";
                                break;
                            case 'View':
                                $sql = "select * from state_master";
                                $sql1 = "select *,client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4,client_address.city,client_address.pan_no,client_address.isd_code,client_address.std_code,client_address.phone_no,client_address.fax_no,client_address.mobile_no,client_address.email_id,client_address.web_id,client_address.client_gst,client_address.prepared_by,client_address.prepared_on from client_address INNER JOIN client_master ON client_master.client_code = client_address.client_code where client_address.address_code='$uid'";
                                break;
                            case 'Copy':
                                $sql = "select * from state_master";
                                $sql1 = "select *,client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4,client_address.city,client_address.pan_no,client_address.isd_code,client_address.std_code,client_address.phone_no,client_address.fax_no,client_address.mobile_no,client_address.email_id,client_address.web_id,client_address.client_gst,client_address.prepared_by,client_address.prepared_on from client_address INNER JOIN client_master ON client_master.client_code = client_address.client_code where client_address.address_code='$uid'";
                                break;
                            case 'Delete':
                                $sql = "select * from state_master";
                                $sql1 = "select *,client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4,client_address.city,client_address.pan_no,client_address.isd_code,client_address.std_code,client_address.phone_no,client_address.fax_no,client_address.mobile_no,client_address.email_id,client_address.web_id,client_address.client_gst,client_address.prepared_by,client_address.prepared_on from client_address INNER JOIN client_master ON client_master.client_code = client_address.client_code where client_address.address_code='$uid'";
                                break;
                        }
                    }
                    
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray();
                    $data1 = $this->db->query($sql1)->getResultArray()[0];

                    return view("pages/Master/client_address_addedit", compact("url", "display_id", "menu_id", "option", "data", "data1", "redokadd","disview"));
                } else {
                    $data = $this->db->query($sql)->getResultArray();
                    return view("pages/Master/client_address_addedit", compact("url", "display_id", "menu_id", "option", "data", "redokadd","disview"));
                }
                } 
                break;

            case 'associate-list-add':
                $session = session();
                $sessionName = $session->userId;
                $data = branches($sessionName);
                $sql = '';
                $sql1 = '';
                $data['requested_url'] = $this->session->requested_end_menu_url;
                $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
                $link_associate_code = isset($_REQUEST['link_associate_code']) ? $_REQUEST['link_associate_code'] : null;
                $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
                $associate_code = isset($_REQUEST['associate_code']) ? $_REQUEST['associate_code'] : null;
                $maxCode = isset($_REQUEST['maxCode']) ? $_REQUEST['maxCode'] : null;
                $associate_name = isset($_REQUEST['associate_name']) ? $_REQUEST['associate_name'] : null;
                $client_nm = strtoupper(stripslashes($associate_name));
                $first_letter = substr($client_nm, 0, 1);
                $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
                $address_line_2 = isset($_REQUEST['address_line_2']) ? $_REQUEST['address_line_2'] : null;
                $address_line_3 = isset($_REQUEST['address_line_3']) ? $_REQUEST['address_line_3'] : null;
                $address_line_4 = isset($_REQUEST['address_line_4']) ? $_REQUEST['address_line_4'] : null;
                $counsel_type = isset($_REQUEST['counsel_type']) ? $_REQUEST['counsel_type'] : null;
                $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
                $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
                $associate_type = isset($_REQUEST['associate_type']) ? $_REQUEST['associate_type'] : null;
                $bearer_fee = isset($_REQUEST['bearer_fee']) ? $_REQUEST['bearer_fee'] : null;
                $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
                $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
                $pan_no = isset($_REQUEST['pan_no']) ? $_REQUEST['pan_no'] : null;
                $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
                $last_update_id = isset($_REQUEST['last_update_id']) ? $_REQUEST['last_update_id'] : null;
                $last_update_dt = isset($_REQUEST['last_update_dt']) ? $_REQUEST['last_update_dt'] : null;
                $prepared_by = isset($_REQUEST['prepared_by']) ? $_REQUEST['prepared_by'] : null;
                $prepared_on = isset($_REQUEST['prepared_on']) ? $_REQUEST['prepared_on'] : null;
                $where_stmt = "AND (ifnull(link_associate_code,'')='' OR link_associate_code = '$link_associate_code') ";
                $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
                $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
                if ($this->request->getMethod() == 'post') {
                    if($display_id!="" & $finsub=="fsub")
                    {
                    switch ($option) {
                        case 'Add':
                            $this->db->query("insert into  associate_master (associate_code,associate_name,associate_type, counsel_type, address_line_1, address_line_2,address_line_3,address_line_4,city,pin_code,mobile_no,phone_no,pan_no,bearer_fee,status_code,prepared_by,prepared_on) values ('$first_letter$maxCode','$associate_name ', '$associate_type','$counsel_type','$address_line_1','$address_line_2','$address_line_3','$address_line_4','$city','$pin_code','$mobile_no','$phone_no','$pan_no','$bearer_fee','$status_code','$prepared_by','$prepared_on')");
                            session()->setFlashdata('message', 'Records Added Successfully !!');
                            //return redirect()->to(previous_url());
                            return redirect()->to($url);
                            break;

                        case 'Edit':
                            $this->db->query("update associate_master set associate_type='$associate_type',associate_name='$associate_name', counsel_type='$counsel_type', address_line_1='$address_line_1',address_line_2='$address_line_2',address_line_3='$address_line_3',address_line_4='$address_line_4',city='$city',pin_code='$pin_code',mobile_no='$mobile_no',phone_no='$phone_no',pan_no='$pan_no',bearer_fee='$bearer_fee',status_code='$status_code',last_update_id='$last_update_id',last_update_dt='$last_update_dt' where associate_code='$associate_code'");
                            session()->setFlashdata('message', 'Records Updated Successfully !!');
                            return redirect()->to($url);
                            break;
                        case 'Delete':
                            $this->db->query("delete from associate_master where associate_code='$associate_code'");
                            session()->setFlashdata('message', 'Records Deleted Successfully !!');
                            return redirect()->to($url);
                            break;

                        }
                    }
                    if($finsub=="" || $finsub!="fsub"){
                        switch ($option) {
                            case 'Add':
                                $sql = "select code_code,code_desc from code_master where type_code = '027' AND status_code!='Old'";
                                $sql1 = "select associate_name,associate_code,link_associate_code FROM associate_master  WHERE associate_type ='002' " . $where_stmt . " ORDER BY associate_name,associate_code";
                                break;
    
                            case 'Edit':
                                $sql = "select code_code,code_desc from code_master where type_code = '027' AND status_code!='Old'";
                                $sql1 = "select *,associate_master.status_code AS statusCode from associate_master INNER JOIN code_master ON code_master.code_code = associate_master.counsel_type where associate_code='$associate_code'";
                                break;
                            case 'View':
                                $sql = "select code_code,code_desc from code_master where type_code = '027' AND status_code!='Old'";
                                $sql1 = "select *,associate_master.status_code AS statusCode from associate_master INNER JOIN code_master ON code_master.code_code = associate_master.counsel_type where associate_code='$associate_code'";
                                break;
                            case 'Delete':
                                $sql = "select code_code,code_desc from code_master where type_code = '027' AND status_code!='Old'";
                                $sql1 = "select *,associate_master.status_code AS statusCode from associate_master INNER JOIN code_master ON code_master.code_code = associate_master.counsel_type where associate_code='$associate_code'";
                                break;
                        }
                        $data = $this->db->query($sql)->getResultArray();
                        $data1 = $this->db->query($sql1)->getResultArray()[0];
                        return view("pages/Master/associate_list_add", compact("url", "option", "data", "data1","disview","redokadd"));
    
                    }
                }
                break;
            case 'mas-branch-master':
                $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
                $sql = '';
                $sql1 = '';
                $session = session();
                $sessionName = $session->userId;
                $data = branches($sessionName);
                $data['requested_url'] = $this->session->requested_end_menu_url;
                $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
                $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
                $branch_code = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : null;
                $branch_name = isset($_REQUEST['branch_name']) ? $_REQUEST['branch_name'] : null;
                $branch_abbr_name = isset($_REQUEST['branch_abbr_name']) ? $_REQUEST['branch_abbr_name'] : null;
                $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
                $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
                $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
                $state_code = isset($_REQUEST['state_code']) ? $_REQUEST['state_code'] : null;
                $website = isset($_REQUEST['website']) ? $_REQUEST['website'] : null;
                $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
                $fax_no = isset($_REQUEST['fax_no']) ? $_REQUEST['fax_no'] : null;
                $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
                $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
                $contact_person = isset($_REQUEST['contact_person']) ? $_REQUEST['contact_person'] : null;
                $company_code = isset($_REQUEST['company_code']) ? $_REQUEST['company_code'] : null;
                $pan_no = isset($_REQUEST['pan_no']) ? $_REQUEST['pan_no'] : null;
                $tan_no = isset($_REQUEST['tan_no']) ? $_REQUEST['tan_no'] : null;
                $cst_no = isset($_REQUEST['cst_no']) ? $_REQUEST['cst_no'] : null;
                $lst_no = isset($_REQUEST['lst_no']) ? $_REQUEST['lst_no'] : null;
                $vat_no = isset($_REQUEST['vat_no']) ? $_REQUEST['vat_no'] : null;
                $tin_no = isset($_REQUEST['tin_no']) ? $_REQUEST['tin_no'] : null;
                $tds_circle_no = isset($_REQUEST['tds_circle_no']) ? $_REQUEST['tds_circle_no'] : null;
                $excise_registration_no = isset($_REQUEST['excise_registration_no']) ? $_REQUEST['excise_registration_no'] : null;
                $excise_registration_date = isset($_REQUEST['excise_registration_date']) ? date($_REQUEST['excise_registration_date']) : '';
                $excise_registration_type = isset($_REQUEST['excise_registration_type']) ? $_REQUEST['excise_registration_type'] : null;
                $service_tax_regn_no = isset($_REQUEST['service_tax_regn_no']) ? $_REQUEST['service_tax_regn_no'] : null;
                $service_tax_regn_date = isset($_REQUEST['service_tax_regn_date']) ? date('Y-m-d',strtotime($_REQUEST['service_tax_regn_date'])) : null;
                $default_access_ind = isset($_REQUEST['default_access_ind']) ? $_REQUEST['default_access_ind'] : null;
                $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
                $sigRowCount = isset($_REQUEST['sigRowCount']) ? $_REQUEST['sigRowCount'] : null;
                $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
                $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
                $first_letter = 'B';
                $branch_codein = $first_letter . str_pad($branch_code, 3, '0', STR_PAD_LEFT);
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
                if ($this->request->getMethod() == 'post') {
                    if($finsub=="fsub")
                    {
                        switch ($option)
                        {
                            case 'Add':
                                $this->db->query("insert into  branch_master (branch_code,branch_name,branch_abbr_name,address_line_1,city,pin_code,state_code,website,phone_no,fax_no,mobile_no,email_id,contact_person,company_code,pan_no,tan_no,cst_no,lst_no,vat_no,tin_no,tds_circle_no,excise_registration_no,excise_registration_date,excise_registration_type,service_tax_regn_no,service_tax_regn_date,default_access_ind,status_code) values ('$branch_codein','$branch_name','$branch_abbr_name','$address_line_1','$city','$pin_code','$state_code','$website','$phone_no','$fax_no','$mobile_no','$email_id','$contact_person','$company_code','$pan_no','$tan_no','$cst_no','$lst_no','$vat_no','$tin_no','$tds_circle_no','$excise_registration_no','$excise_registration_date','$excise_registration_type','$service_tax_regn_no','$service_tax_regn_date','$default_access_ind','$status_code')");
                                for ($i = 1; $i <= $sigRowCount; $i++) {
                                    $this->db->query("insert into  branch_signatory_master (branch_code,row_no,signatory_name,signatory_desg) values ('$branch_codein','$i','" . $_REQUEST['signatory_name' . $i] . "','" . $_REQUEST['signatory_desg' . $i] . "')");
                                }
                                session()->setFlashdata('message', 'Records Inserted Successfully !!');
                                return redirect()->to($url);
                                break;
                            case 'Edit':
                                if ($_REQUEST['branch_code'] != '') {
                                    $this->db->query("delete from branch_signatory_master where branch_code='$branch_code'");
                                    for ($i = 1; $i <= $sigRowCount; $i++) {
                                        if($_REQUEST['signatory_name' . $i]!=''){
                                        $this->db->query("insert into  branch_signatory_master (branch_code,row_no,signatory_name,signatory_desg) values ('$branch_code',$i,'" . $_REQUEST['signatory_name' . $i] . "','" . $_REQUEST['signatory_desg' . $i] . "')");
                                        }
                                    }
                                }
                                $this->db->query("update  branch_master set branch_name='$branch_name',branch_abbr_name='$branch_abbr_name',address_line_1='$address_line_1',city='$city',pin_code='$pin_code',state_code='$state_code',website='$website',phone_no='$phone_no',fax_no='$fax_no',mobile_no='$mobile_no',email_id='$email_id',contact_person='$contact_person',company_code='$company_code',pan_no='$pan_no',tan_no='$tan_no',cst_no='$cst_no',lst_no='$lst_no',vat_no='$vat_no',tin_no='$tin_no',tds_circle_no='$tds_circle_no',excise_registration_no='$excise_registration_no',excise_registration_date='$excise_registration_date',excise_registration_type='$excise_registration_type',service_tax_regn_no='$service_tax_regn_no',service_tax_regn_date='$service_tax_regn_date',default_access_ind='$default_access_ind',status_code='$status_code' WHERE branch_code='$branch_code' ");
                                session()->setFlashdata('message', 'Records Updated Successfully !!');
                                return redirect()->to($url);
                                break;
                                case 'Delete':
                                    $this->db->query("delete from  branch_master  WHERE branch_code='$branch_code' ");
                                    $this->db->query("delete from branch_signatory_master where branch_code='$branch_code'");
                                    session()->setFlashdata('message', 'Records Delete Successfully !!');
                                    return redirect()->to($url);
                                    break;
                        }
                    }
                    if($finsub=="" || $finsub!="fsub")
                    {
                        switch ($option) {
                            case 'Add':
                                $sql1 = "select state_code,  state_name   from state_master   order by state_name";
                                $sql2 = "select company_code,company_name from company_master where status_code!='O' order by company_name";
                                $sql3 = "select * from branch_signatory_master where branch_code = '$branch_code'";
                                break;
                            case 'Edit':
                                $sql = "select *,CONCAT(address_line_1,' ', address_line_2,' ', address_line_3,' ',address_line_4) as address from branch_master where branch_code= '$branch_code'";
                                $sql1 = "select state_code,  state_name   from state_master   order by state_name";
                                $sql2 = "select company_code,company_name from company_master where status_code!='O' order by company_name";
                                $sql3 = "select * from branch_signatory_master where branch_code = '$branch_code'";
                                break;
                            case 'View':
                                $sql = "select *,CONCAT(address_line_1,' ', address_line_2,' ', address_line_3,' ',address_line_4) as address from branch_master where branch_code= '$branch_code'";
                                $sql1 = "select state_code,  state_name   from state_master   order by state_name";
                                $sql2 = "select company_code,company_name from company_master where status_code!='O' order by company_name";
                                $sql3 = "select * from branch_signatory_master where branch_code = '$branch_code'";
                                break;
                            case 'Delete':
                               $sql = "select *,CONCAT(address_line_1,' ', address_line_2,' ', address_line_3,' ',address_line_4) as address from branch_master where branch_code= '$branch_code'";
                                $sql1 = "select state_code,  state_name   from state_master   order by state_name";
                                $sql2 = "select company_code,company_name from company_master where status_code!='O' order by company_name";
                                $sql3 = "select * from branch_signatory_master where branch_code = '$branch_code'";
                                break;
                        }
                        if ($option != 'Add') {
                            $data = $this->db->query($sql)->getResultArray()[0];
                            $data1 = $this->db->query($sql1)->getResultArray();
                            $data2 = $this->db->query($sql2)->getResultArray();
                            $data3 = $this->db->query($sql3)->getResultArray();
                            return view("pages/Master/mas_branch_master", compact("url", "option", "data", "data1", "data2", "data3","redokadd","disview"));
                        }
                        if ($option == 'Add') {
                            $data1 = $this->db->query($sql1)->getResultArray();
                            $data2 = $this->db->query($sql2)->getResultArray();
                            $data3 = $this->db->query($sql3)->getResultArray();
                            return view("pages/Master/mas_branch_master", compact("url", "option", "data1", "data2", "data3","redokadd","disview"));
                        }
                    } 
                } 
               
            case 'mas-bank-master':
                $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
                $sql = '';
                $sql1 = '';
                $session = session();
                $sessionName = $session->userId;
                $data = branches($sessionName);
                $data['requested_url'] = $this->session->requested_end_menu_url;
                $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
                $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
                $bank_code = isset($_REQUEST['bank_code']) ? $_REQUEST['bank_code'] : null;
                $bank_name = isset($_REQUEST['bank_name']) ? $_REQUEST['bank_name'] : null;
                $first_letter = substr($bank_name, 0, 1);
                $bank_codein = $first_letter . $bank_code;
                $bsr_code = isset($_REQUEST['bsr_code']) ? $_REQUEST['bsr_code'] : null;
                $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
                $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
                
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
                if ($this->request->getMethod() == 'post') {
                    if($display_id!="" & $finsub=="fsub"){
                        switch ($option) {
                            case 'Edit':
                            $this->db->query("update bank_master set bank_name='$bank_name',bsr_code='$bsr_code' WHERE bank_code='$bank_code'");
                            session()->setFlashdata('message', 'Records Updated Successfully !!');
                           return redirect()->to($url);
                            break;
                        case 'Add':
                            $this->db->query("insert into  bank_master (bank_code,bank_name,bsr_code) values ('$bank_codein','$bank_name','$bsr_code')");
                            session()->setFlashdata('message', 'Records Inserted Successfully !!');
                            return redirect()->to($url);
                            break;
                        case 'Delete':
                            $this->db->query("delete from bank_master WHERE bank_code='$bank_code'");
                            session()->setFlashdata('message', 'Records Delete Successfully !!');
                            return redirect()->to($url);
                            break;
                        }
                    }
                    if($finsub=="" || $finsub!="fsub"){ 
                        switch ($option) {
                            case 'Edit':
                                $sql = "select * from bank_master where bank_code='$bank_code'";
                                break;
                            case 'View':
                                $sql = "select * from bank_master where bank_code='$bank_code'";
                                break;
                            case 'Delete':
                                $sql = "select * from bank_master where bank_code='$bank_code'";
                                break;
                        }
                        if ($option != 'Add') {
                            $data = $this->db->query($sql)->getResultArray()[0];
        
                            return view("pages/Master/mas_bank_master", compact("url", "option", "data","redokadd","disview"));
                        } else {
                            return view("pages/Master/mas_bank_master", compact("url", "option","redokadd","disview"));
                        }
                    }
                } 
               
                break;

        }

    }

    public function view()
    {
        $data = view_list();
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($data));
    }
    public function client_attention_addedit($option = 'list')
    {
        $sql = '';
                $sql1 = '';
                $session = session();
                $sessionName = $session->userId;
                $data = branches($sessionName);
                $data['requested_url'] = $this->session->requested_end_menu_url;
                $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
                $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
                $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
                $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
                $uid = isset($_REQUEST['attention_code']) ? $_REQUEST['attention_code'] : null;
                $client_name = isset($_REQUEST['client_name']) ? $_REQUEST['client_name'] : null;
                $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
                $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : null;
                $attention_code = isset($_REQUEST['attention_code']) ? $_REQUEST['attention_code'] : null;
                $address_code = isset($_REQUEST['address_code']) ? $_REQUEST['address_code'] : null;
                $attention_name = isset($_REQUEST['attention_name']) ? $_REQUEST['attention_name'] : null;
                $designation = isset($_REQUEST['designation']) ? $_REQUEST['designation'] : null;
                $short_name = isset($_REQUEST['short_name']) ? $_REQUEST['short_name'] : null;
                $sex = isset($_REQUEST['sex']) ? $_REQUEST['sex'] : null;
                $title = isset($_REQUEST['title']) ? $_REQUEST['title'] : null;
                $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
                $fax_no = isset($_REQUEST['fax_no']) ? $_REQUEST['fax_no'] : null;
                $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
                $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
                $email_id_other = isset($_REQUEST['email_id_other']) ? $_REQUEST['email_id_other'] : null;
                $prepared_on = isset($_REQUEST['prepared_on']) ? $_REQUEST['prepared_on'] : null;
                $prepared_by = isset($_REQUEST['prepared_by']) ? $_REQUEST['prepared_by'] : null;
                $updated_on = isset($_REQUEST['updated_on']) ? $_REQUEST['updated_on'] : null;
                $updated_by = isset($_REQUEST['updated_by']) ? $_REQUEST['updated_by'] : null;
                $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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

                if ($this->request->getMethod() == 'post') {
                    if($finsub=="fsub")
                    {
                    switch ($option) {
                        case 'Add':
                            $this->db->query("insert into  client_attention (client_code, address_code, attention_name, designation,short_name,sex,title,phone_no,fax_no,mobile_no,email_id,email_id_other,prepared_on,prepared_by) values ('$client_code ', '$address_code','$attention_name','$designation','$short_name','$sex','$title','$phone_no','$fax_no','$mobile_no','$email_id','$email_id_other','$prepared_on','$prepared_by')");
                            session()->setFlashdata('message', 'Records Added Successfully !!');
                            return redirect()->to($url);
                            break;
                        case 'Copy':
                            $this->db->query("insert into  client_attention (client_code, address_code, attention_name, designation,short_name,sex,title,phone_no,fax_no,mobile_no,email_id,email_id_other,prepared_on,prepared_by) values ('$client_code ', '$address_code','$attention_name','$designation','$short_name','$sex','$title','$phone_no','$fax_no','$mobile_no','$email_id','$email_id_other','$prepared_on','$prepared_by')");
                            session()->setFlashdata('message', 'Records Coping Successfully !!');
                            return redirect()->to($url);
                            break;
                        case 'Edit':
                            $this->db->query("update client_attention set client_code='$client_code', address_code='$address_code', attention_name='$attention_name',designation='$designation',short_name='$short_name',sex='$sex',title='$title',phone_no='$phone_no',fax_no='$fax_no',email_id='$email_id',email_id_other='$email_id_other',mobile_no='$mobile_no',updated_on='$updated_on',updated_by='$updated_by' where attention_code='$uid'");
                            session()->setFlashdata('message', 'Records Updated Successfully !!');
                            return redirect()->to($url);
                            break;
                        case 'Delete':
                            $this->db->query("delete from client_attention where attention_code='$uid'");
                            session()->setFlashdata('message', 'Records Deleted Successfully !!');
                            return redirect()->to($url);
                            break;
                    }
                }
                if($finsub=="" || $finsub!="fsub")
                {
                    switch ($option) {
                        case 'Add':
                            $sql = "select *,client_attention.phone_no,client_attention.fax_no,client_attention.mobile_no,client_attention.email_id,client_attention.email_id_other,CONCAT(client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4) AS addr from client_attention INNER JOIN client_master ON client_master.client_code = client_attention.client_code INNER JOIN client_address ON client_address.address_code = client_attention.address_code";
                            break;
                        case 'Edit':
                            $sql = "select *,client_attention.phone_no,client_attention.fax_no,client_attention.mobile_no,client_attention.email_id,client_attention.email_id_other,CONCAT(client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4) AS addr,client_master.client_name  from client_attention INNER JOIN client_master ON client_master.client_code = client_attention.client_code INNER JOIN client_address ON client_address.address_code = client_attention.address_code where client_attention.attention_code='$uid'";
                            break;
                        case 'Copy':
                            $sql = "select *,client_attention.phone_no,client_attention.fax_no,client_attention.mobile_no,client_attention.email_id,client_attention.email_id_other,CONCAT(client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4) AS addr,client_master.client_name  from client_attention INNER JOIN client_master ON client_master.client_code = client_attention.client_code INNER JOIN client_address ON client_address.address_code = client_attention.address_code where client_attention.attention_code='$uid'";
                            break;
                        case 'View':
                            $sql = "select *,client_attention.phone_no,client_attention.fax_no,client_attention.mobile_no,client_attention.email_id,client_attention.email_id_other,CONCAT(client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4) AS addr,client_master.client_name  from client_attention INNER JOIN client_master ON client_master.client_code = client_attention.client_code INNER JOIN client_address ON client_address.address_code = client_attention.address_code where client_attention.attention_code='$uid'";
                            break;
                        case 'Delete':
                            $sql = "select *,client_attention.phone_no,client_attention.fax_no,client_attention.mobile_no,client_attention.email_id,client_attention.email_id_other,CONCAT(client_address.address_line_1,client_address.address_line_2,client_address.address_line_3,client_address.address_line_4) AS addr,client_master.client_name  from client_attention INNER JOIN client_master ON client_master.client_code = client_attention.client_code INNER JOIN client_address ON client_address.address_code = client_attention.address_code where client_attention.attention_code='$uid'";
                            break;
                    }
                    if ($option != 'Add') {
                        $data = $this->db->query($sql)->getResultArray()[0];
                        return view("pages/Master/client_attention_addedit", compact("url", "display_id", "menu_id", "option", "data", "redk", "disv", "disview"));
                    } else {
                        return view("pages/Master/client_attention_addedit", compact("url", "display_id", "menu_id", "option", "redk", "disv", "disview"));
                    }
                }
                } 
                

    }
    //end
    //Done by Sylvester
    public function matter_master($option = 'list')
    {
        //edited By sylvester
        $perm = "select * from permission WHERE permission_on='0'";
        $permdata = $this->db->query($perm)->getResultArray();
        //edit end
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
            $redokadd = 'readonly';
            $disview = '';
            $redLetter = 'disabled';}
        if ($option == 'delete') {$redk = '';
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
        if ($option == 'list') {$redk = 'readonly';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = '';}
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
       // $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        return view("pages/Master/matter_master", compact( "display_id", "menu_id", "option", "redk", "permdata"));
    }
    //end
    //Done by Sylvester
    public function matter_masteraddedit($option = 'list')
    {
        // echo '<pre>'; print_r($uploadedFiles);die;
        //edited By sylvester
        $uoption = $option;
        $perm = "select * from permission WHERE permission_on='0'";
        $permdata = $this->db->query($perm)->getResultArray();
        //edit end
        $pageMode = isset($_REQUEST['pageMode']) ? $_REQUEST['pageMode'] : null;
        if ($option == 'Add' && $pageMode == 'Edit' || $pageMode == 'View' ) {
            if ($_REQUEST['pageMode'] == 'Edit' || $_REQUEST['pageMode'] == 'View' ) {$option   = $_REQUEST['pageMode'];} else { $option = $uoption;}
        }
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
        if ($option == 'delete') {$redk = '';
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
        if ($option == 'copy') {$redk = 'readonly';
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
        if ($option == 'list') {$redk = 'readonly';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = '';}
        $sql = '';
        $sql1 = '';
        $sql2 = '';
        $sql3 = '';
        $sql4 = '';
        $sql5 = '';
        $my_sql1 = '';
        $matter_type_desc = '';
        $matter_sub_type_desc = '';
        $matter_sub_sub_type_desc = '';
        $corrp_addr_code = '';
        $city = '';
        $attention_name = '';
        $designation = '';
        $sex = '';
        $attn_phone_no = '';
        $attn_fax_no = '';
        $attn_mobile_no = '';
        $attn_email_id = '';
        $sele_stmt = '';
        $other_side_stmt = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $branchCode = $data['branch_code']['branch_code'];
        $ip = $_SERVER['SERVER_NAME'];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master/matter-master?display_id={$display_id}&menu_id={$menu_id}";
        $code_code = isset($_REQUEST['matter_code']) ? $_REQUEST['matter_code'] : null;
        $lastId = isset($_REQUEST['lastId']) ? $_REQUEST['lastId'] : null;
        $prepared_name = isset($_REQUEST['prepared_name']) ? $_REQUEST['prepared_name'] : null;
        $prepared_on = isset($_REQUEST['prepared_on']) ? date('Y-m-d', strtotime($_REQUEST['prepared_on'])) : null;
        $prepared_by = isset($_REQUEST['prepared_by']) ? $_REQUEST['prepared_by'] : null;
        $last_update_name = isset($_REQUEST['last_update_name']) ? $_REQUEST['last_update_name'] : null;
        $last_update_dt = isset($_REQUEST['last_update_dt']) ? date('Y-m-d', strtotime($_REQUEST['last_update_dt'])) : null;
        $last_update_id = isset($_REQUEST['last_update_id']) ? $_REQUEST['last_update_id'] : null;
        $matter_code = isset($_REQUEST['matter_code']) ? $_REQUEST['matter_code'] : null;

        $initial_code = isset($_REQUEST['initial_code']) ? $_REQUEST['initial_code'] : null;
        $file_locn_code = isset($_REQUEST['file_locn_code']) ? $_REQUEST['file_locn_code'] : null;
        $matter_type_code = isset($_REQUEST['matter_type_code']) ? $_REQUEST['matter_type_code'] : null;
        $matter_sub_type_code = isset($_REQUEST['matter_sub_type_code']) ? $_REQUEST['matter_sub_type_code'] : null;
        $matter_sub_sub_type_code = isset($_REQUEST['matter_sub_sub_type_code']) ? $_REQUEST['matter_sub_sub_type_code'] : null;
        $court_code = isset($_REQUEST['court_code']) ? $_REQUEST['court_code'] : null;
        $judge_name = isset($_REQUEST['judge_name']) ? $_REQUEST['judge_name'] : null;
        $case_type_code = isset($_REQUEST['case_type_code']) ? $_REQUEST['case_type_code'] : null;
        $case_no = isset($_REQUEST['case_no']) ? $_REQUEST['case_no'] : null;
        $case_year = isset($_REQUEST['case_year']) ? $_REQUEST['case_year'] : null;
        $matter_desc1 = isset($_REQUEST['matter_desc1']) ? $_REQUEST['matter_desc1'] : null;
        $matter_desc2 = isset($_REQUEST['matter_desc2']) ? $_REQUEST['matter_desc2'] : null;
        $trust_name = isset($_REQUEST['trust_name']) ? $_REQUEST['trust_name'] : null;
        $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
        // echo $client_code;die;

        $client_group_name = isset($_REQUEST['client_group_name']) ? $_REQUEST['client_group_name'] : null;
        $appearing_for_code = isset($_REQUEST['appearing_for_code']) ? $_REQUEST['appearing_for_code'] : null;
        $date_of_filing = isset($_REQUEST['date_of_filing']) ? $_REQUEST['date_of_filing'] :  '';
        if($date_of_filing!=''){$date_of_filing=date('Y-m-d',strtotime($date_of_filing));}else{ $date_of_filing=''; }
        $appearing_for_no = isset($_REQUEST['appearing_for_no']) ? $_REQUEST['appearing_for_no'] : null;
        $requisition_no = isset($_REQUEST['requisition_no']) ? $_REQUEST['requisition_no'] : null;
        $stake_amount = isset($_REQUEST['stake_amount']) ? $_REQUEST['stake_amount'] : null;
        $notice_no = isset($_REQUEST['notice_no']) ? $_REQUEST['notice_no'] : null;
        $notice_date = isset($_REQUEST['notice_date']) ? $_REQUEST['notice_date'] : '';
        if($notice_date!=''){$notice_date=date('Y-m-d',strtotime($notice_date));}else{ $notice_date=''; }
        $apply_opposeInd1 = isset($_REQUEST['apply_oppose_ind1']) ? $_REQUEST['apply_oppose_ind1'] : null;
        $billable_option1 = isset($_REQUEST['billable_option1']) ? $_REQUEST['billable_option1'] : null;
        $reference_desc = isset($_REQUEST['reference_desc']) ? $_REQUEST['reference_desc'] : null;
        $reference_type_code = isset($_REQUEST['reference_type_code']) ? $_REQUEST['reference_type_code'] : null;
        $subject_desc = isset($_REQUEST['subject_desc']) ? $_REQUEST['subject_desc'] : null;
        $corrp_addr_code = isset($_REQUEST['corrp_addr_code']) ? $_REQUEST['corrp_addr_code'] : null;
        $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
        $address_line_2 = isset($_REQUEST['address_line_2']) ? $_REQUEST['address_line_2'] : null;
        $address_line_3 = isset($_REQUEST['address_line_3']) ? $_REQUEST['address_line_3'] : null;
        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
        $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
        $state_name = isset($_REQUEST['state_name']) ? $_REQUEST['state_name'] : null;
        $std_code = isset($_REQUEST['std_code']) ? $_REQUEST['std_code'] : null;
        $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : null;
        $isd_code = isset($_REQUEST['isd_code']) ? $_REQUEST['isd_code'] : null;
        $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
        $fax_no = isset($_REQUEST['fax_no']) ? $_REQUEST['fax_no'] : null;
        $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
        $client_gst = isset($_REQUEST['client_gst']) ? $_REQUEST['client_gst'] : null;
        $corrp_attn_code = isset($_REQUEST['corrp_attn_code']) ? $_REQUEST['corrp_attn_code'] : null;
        $attention_name = isset($_REQUEST['attention_name']) ? $_REQUEST['attention_name'] : null;
        $designation = isset($_REQUEST['designation']) ? $_REQUEST['designation'] : null;
        $sex = isset($_REQUEST['sex']) ? $_REQUEST['sex'] : null;
        $first_activity_date = isset($_REQUEST['first_activity_date']) ? $_REQUEST['first_activity_date'] : '';
        if($first_activity_date!=''){$first_activity_date=date('Y-m-d',strtotime($first_activity_date));}else{ $first_activity_date=''; }
        $first_fixed_for = isset($_REQUEST['first_fixed_for']) ? $_REQUEST['first_fixed_for'] : null;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
        $other_side_count = isset($_REQUEST['other_side_count']) ? $_REQUEST['other_side_count'] : null;
        $counsel_other_side_count = isset($_REQUEST['counsel_other_side_count']) ? $_REQUEST['counsel_other_side_count'] : null;
        $advidor_other_side_count = isset($_REQUEST['advidor_other_side_count']) ? $_REQUEST['advidor_other_side_count'] : null;
        $adv_on_rec_other_side_count = isset($_REQUEST['adv_on_rec_other_side_count']) ? $_REQUEST['adv_on_rec_other_side_count'] : null;
        $int_party_othher_side_count = isset($_REQUEST['int_party_othher_side_count']) ? $_REQUEST['int_party_othher_side_count'] : null;
        $advisor_client_count = isset($_REQUEST['advisor_client_count']) ? $_REQUEST['advisor_client_count'] : null;
        $matter_counsel_count = isset($_REQUEST['matter_counsel_count']) ? $_REQUEST['matter_counsel_count'] : null;
        $adv_rec_client_count = isset($_REQUEST['adv_rec_client_count']) ? $_REQUEST['adv_rec_client_count'] : null;
        $int_party_client_count = isset($_REQUEST['int_party_client_count']) ? $_REQUEST['int_party_client_count'] : null;
        $repr_by_client_count = isset($_REQUEST['repr_by_client_count']) ? $_REQUEST['repr_by_client_count'] : null;
        $ref_by_client_count = isset($_REQUEST['ref_by_client_count']) ? $_REQUEST['ref_by_client_count'] : null;
        $matter_initial_count = isset($_REQUEST['matter_initial_count']) ? $_REQUEST['matter_initial_count'] : null;
        $related_matter_count = isset($_REQUEST['related_matter_count']) ? $_REQUEST['related_matter_count'] : null;
        $matter_org_count = isset($_REQUEST['matter_org_count']) ? $_REQUEST['matter_org_count'] : null;
        $case_no_count = isset($_REQUEST['case_no_count']) ? $_REQUEST['case_no_count'] : null;
        $cheques_count = isset($_REQUEST['cheques_count']) ? $_REQUEST['cheques_count'] : null;
        $other_side_record_code = isset($_REQUEST['other_side_record_code']) ? $_REQUEST['other_side_record_code'] : null;
        $other_side_record_code = isset($_REQUEST['other_side_record_code']) ? $_REQUEST['other_side_record_code'] : null;
        $other_side_record_code = isset($_REQUEST['other_side_record_code']) ? $_REQUEST['other_side_record_code'] : null;
        $status_master = isset($_REQUEST['status_master']) ? $_REQUEST['status_master'] : null;
        $matter_counsel_codeAr = isset($_REQUEST['matter_counsel_code']) ? $_REQUEST['matter_counsel_code'] : null;
        $case_details= isset($_REQUEST['case_details'])? $_REQUEST['case_details']: null;
        $closePage= isset($_REQUEST['closePage'])? $_REQUEST['closePage']: null;
        $matter_counsel_codeCount = '';
        if ($matter_counsel_codeAr != '') {
            $matter_counsel_codeCount = count($matter_counsel_codeAr);
        }
        $lastId = isset($_REQUEST['lastId']) ? $_REQUEST['lastId'] : null;

        if ($this->request->getMethod() == 'post') {

            switch ($option) {

                case 'Add':
                    $this->db->query("insert into  fileinfo_header (branch_code,matter_code,initial_code, file_locn_code, matter_type_code,matter_sub_type_code,matter_sub_sub_type_code,court_code,
                 judge_name ,case_type_code,case_no,case_year,matter_desc1,matter_desc2,
                 trust_name,client_code,appearing_for_code, date_of_filing,
                 appearing_for_no, requisition_no, stake_amount, notice_no, notice_date,  billable_option, reference_desc, reference_type_code,
                 subject_desc, billing_addr_code,billing_attn_code,corrp_addr_code,corrp_attn_code, first_activity_date,
                  first_fixed_for, status_code,new_matter,start_date,last_bill_date,prepared_by,prepared_ip) values ('$branchCode','$lastId','$initial_code ', '$file_locn_code','$matter_type_code','$matter_sub_type_code','$matter_sub_sub_type_code','$court_code',
                  '$judge_name','$case_type_code','$case_no','$case_year','$matter_desc1','$matter_desc2','$trust_name','$client_code','$appearing_for_code',
                   '" . date('Y-m-d', strtotime($date_of_filing)) . "','$appearing_for_no','$requisition_no','$stake_amount','$notice_no','" . date('Y-m-d', strtotime($notice_date)) . "','$billable_option1','$reference_desc',
                 '$reference_type_code','$subject_desc', '$corrp_addr_code','$corrp_attn_code', '$corrp_addr_code','$corrp_attn_code', '" . date('Y-m-d', strtotime($first_activity_date)) . "',
                   '$first_fixed_for','$status_code','NEW','" . date('Y-m-d') . "','" . date('Y-m-d') . "','$prepared_by','$ip')");
                    for ($i = 1; $i <= $other_side_count; $i++) {
                        $other_side_ok_ind = isset($_REQUEST['other_side_ok_ind' . $i]) ? $_REQUEST['other_side_ok_ind' . $i] : null;
                        $other_side_name = isset($_REQUEST['other_side_name' . $i]) ? $_REQUEST['other_side_name' . $i] : null;
                        if ($other_side_ok_ind == "Y" && $other_side_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['other_side_name' . $i] . "', '" . $_REQUEST['other_side_address_line_1' . $i] . "', '" . $_REQUEST['other_side_address_line_2' . $i] . "', '" . $_REQUEST['other_side_address_line_3' . $i] . "','" . $_REQUEST['other_side_address_line_4' . $i] . "','" . $_REQUEST['other_side_city' . $i] . "','" . $_REQUEST['other_side_pin_code' . $i] . "','" . $_REQUEST['other_side_state_name' . $i] . "','" . $_REQUEST['other_side_country' . $i] . "','" . $_REQUEST['other_side_isd_code' . $i] . "','" . $_REQUEST['other_side_std_code' . $i] . "','" . $_REQUEST['other_side_phone_no' . $i] . "','" . $_REQUEST['other_side_mobile_no' . $i] . "','" . $_REQUEST['other_side_email_id' . $i] . "','" . $_REQUEST['other_side_fax_no' . $i] . "','" . $_REQUEST['other_side_company_name' . $i] . "','" . $_REQUEST['other_side_designation' . $i] . "','" . $_REQUEST['other_side_office_tel' . $i] . "','1','" . $i . "')");
                        }
                    }
                    for ($i = 1; $i <= $counsel_other_side_count; $i++) {
                        $counsel_other_side_ok_ind = isset($_REQUEST['counsel_other_side_ok_ind' . $i]) ? $_REQUEST['counsel_other_side_ok_ind' . $i] : null;
                        $counsel_other_side_name = isset($_REQUEST['counsel_other_side_name' . $i]) ? $_REQUEST['counsel_other_side_name' . $i] : null;
                        if ($counsel_other_side_ok_ind == "Y" && $counsel_other_side_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
               phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['counsel_other_side_name' . $i] . "', '" . $_REQUEST['counsel_other_side_address_line_1' . $i] . "', '" . $_REQUEST['counsel_other_side_address_line_2' . $i] . "', '" . $_REQUEST['counsel_other_side_address_line_3' . $i] . "','" . $_REQUEST['counsel_other_side_address_line_4' . $i] . "','" . $_REQUEST['counsel_other_side_city' . $i] . "','" . $_REQUEST['counsel_other_side_pin_code' . $i] . "','" . $_REQUEST['counsel_other_side_state_name' . $i] . "','" . $_REQUEST['counsel_other_side_country' . $i] . "','" . $_REQUEST['counsel_other_side_isd_code' . $i] . "','" . $_REQUEST['counsel_other_side_std_code' . $i] . "','" . $_REQUEST['counsel_other_side_phone_no' . $i] . "','" . $_REQUEST['counsel_other_side_mobile_no' . $i] . "','" . $_REQUEST['counsel_other_side_email_id' . $i] . "','" . $_REQUEST['counsel_other_side_fax_no' . $i] . "','" . $_REQUEST['counsel_other_side_company_name' . $i] . "','" . $_REQUEST['counsel_other_side_designation' . $i] . "','" . $_REQUEST['counsel_other_side_office_tel' . $i] . "','2','" . $i . "')");
                        }
                    }
                    for ($i = 1; $i <= $advidor_other_side_count; $i++) {
                        $advisor_other_side_ok_ind = isset($_REQUEST['advisor_other_side_ok_ind' . $i]) ? $_REQUEST['advisor_other_side_ok_ind' . $i] : null;
                        $advisor_other_side_name = isset($_REQUEST['advisor_other_side_name' . $i]) ? $_REQUEST['advisor_other_side_name' . $i] : null;
                        if ($advisor_other_side_ok_ind == "Y" && $advisor_other_side_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
               phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['advisor_other_side_name' . $i] . "', '" . $_REQUEST['advisor_other_side_address_line_1' . $i] . "', '" . $_REQUEST['advisor_other_side_address_line_2' . $i] . "', '" . $_REQUEST['advisor_other_side_address_line_3' . $i] . "','" . $_REQUEST['advisor_other_side_address_line_4' . $i] . "','" . $_REQUEST['advisor_other_side_city' . $i] . "','" . $_REQUEST['advisor_other_side_pin_code' . $i] . "','" . $_REQUEST['advisor_other_side_state_name' . $i] . "','" . $_REQUEST['advisor_other_side_country' . $i] . "','" . $_REQUEST['advisor_other_side_isd_code' . $i] . "','" . $_REQUEST['advisor_other_side_std_code' . $i] . "','" . $_REQUEST['advisor_other_side_phone_no' . $i] . "','" . $_REQUEST['advisor_other_side_mobile_no' . $i] . "','" . $_REQUEST['advisor_other_side_email_id' . $i] . "','" . $_REQUEST['advisor_other_side_fax_no' . $i] . "','" . $_REQUEST['advisor_other_side_company_name' . $i] . "','" . $_REQUEST['advisor_other_side_designation' . $i] . "','" . $_REQUEST['advisor_other_side_office_tel' . $i] . "','3','" . $i . "')");
                        }
                    }
                    for ($i = 1; $i <= $adv_on_rec_other_side_count; $i++) {
                        $adv_on_rec_other_side_ok_ind = isset($_REQUEST['adv_on_rec_other_side_ok_ind' . $i]) ? $_REQUEST['adv_on_rec_other_side_ok_ind' . $i] : null;
                        $adv_on_rec_other_side_name = isset($_REQUEST['adv_on_rec_other_side_name' . $i]) ? $_REQUEST['adv_on_rec_other_side_name' . $i] : null;
                        if ($adv_on_rec_other_side_ok_ind == "Y" && $adv_on_rec_other_side_name != '') {

                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
              phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['adv_on_rec_other_side_name' . $i] . "', '" . $_REQUEST['adv_on_rec_other_side_address_line_1' . $i] . "', '" . $_REQUEST['adv_on_rec_other_side_address_line_2' . $i] . "', '" . $_REQUEST['adv_on_rec_other_side_address_line_3' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_address_line_4' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_city' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_pin_code' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_state_name' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_country' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_isd_code' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_std_code' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_phone_no' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_mobile_no' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_email_id' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_fax_no' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_company_name' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_designation' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_office_tel' . $i] . "','4','" . $i . "')");
                        }
                    }
                    for ($i = 1; $i <= $int_party_othher_side_count; $i++) {
                        $int_party_other_side_ok_ind = isset($_REQUEST['int_party_other_side_ok_ind' . $i]) ? $_REQUEST['int_party_other_side_ok_ind' . $i] : null;
                        $int_party_other_side_name = isset($_REQUEST['int_party_other_side_name' . $i]) ? $_REQUEST['int_party_other_side_name' . $i] : null;
                        if ($int_party_other_side_ok_ind == "Y" && $int_party_other_side_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
               phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['int_party_other_side_name' . $i] . "','" . $_REQUEST['int_party_other_side_address_line_1' . $i] . "','" . $_REQUEST['int_party_other_side_address_line_2' . $i] . "', '" . $_REQUEST['int_party_other_side_address_line_3' . $i] . "','" . $_REQUEST['int_party_other_side_address_line_4' . $i] . "','" . $_REQUEST['int_party_other_side_city' . $i] . "','" . $_REQUEST['int_party_other_side_pin_code' . $i] . "','" . $_REQUEST['int_party_other_side_state_name' . $i] . "','" . $_REQUEST['int_party_other_side_country' . $i] . "','" . $_REQUEST['int_party_other_side_isd_code' . $i] . "','" . $_REQUEST['int_party_other_side_std_code' . $i] . "','" . $_REQUEST['int_party_other_side_phone_no' . $i] . "','" . $_REQUEST['int_party_other_side_mobile_no' . $i] . "','" . $_REQUEST['int_party_other_side_email_id' . $i] . "','" . $_REQUEST['int_party_other_side_fax_no' . $i] . "','" . $_REQUEST['int_party_other_side_company_name' . $i] . "','" . $_REQUEST['int_party_other_side_designation' . $i] . "','" . $_REQUEST['int_party_other_side_office_tel' . $i] . "','5','" . $i . "')");
                        }
                    }
                    for ($i = 1; $i <= $advisor_client_count; $i++) {
                        $advisor_client_ok_ind = isset($_REQUEST['advisor_client_ok_ind' . $i]) ? $_REQUEST['advisor_client_ok_ind' . $i] : null;
                        $advisor_client_name = isset($_REQUEST['advisor_client_name' . $i]) ? $_REQUEST['advisor_client_name' . $i] : null;
                        if ($advisor_client_ok_ind == "Y" && $advisor_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
             phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['advisor_client_name' . $i] . "', '" . $_REQUEST['advisor_client_address_line_1' . $i] . "', '" . $_REQUEST['advisor_client_address_line_2' . $i] . "', '" . $_REQUEST['advisor_client_address_line_3' . $i] . "','" . $_REQUEST['advisor_client_address_line_4' . $i] . "','" . $_REQUEST['advisor_client_city' . $i] . "','" . $_REQUEST['advisor_client_pin_code' . $i] . "','" . $_REQUEST['advisor_client_state_name' . $i] . "','" . $_REQUEST['advisor_client_country' . $i] . "','" . $_REQUEST['advisor_client_isd_code' . $i] . "','" . $_REQUEST['advisor_client_std_code' . $i] . "','" . $_REQUEST['advisor_client_phone_no' . $i] . "','" . $_REQUEST['advisor_client_mobile_no' . $i] . "','" . $_REQUEST['advisor_client_email_id' . $i] . "','" . $_REQUEST['advisor_client_fax_no' . $i] . "','" . $_REQUEST['advisor_client_company_name' . $i] . "','" . $_REQUEST['advisor_client_designation' . $i] . "','" . $_REQUEST['advisor_client_office_tel' . $i] . "','6','" . $i . "')");
                        }
                    }
                    $cnt = isset($_REQUEST['matter_counsel_code']) ? count($_REQUEST['matter_counsel_code']) : 0;
                    $j = $i + 1;
                    for ($i = 0; $i < $cnt; $i++) {
                        $matter_counsel_chk = isset($_REQUEST['matter_counsel_chk' . $i]) ? $_REQUEST['matter_counsel_chk' . $i] : null;
                        if ($matter_counsel_chk != "0") {
                            $this->db->query("insert into  fileinfo_counsels (matter_code , record_code, row_no, initial_code) values('$lastId','7','$j','" . $_REQUEST['matter_counsel_code'][$i] . "')");
                        }
                    }
                    for ($i = 1; $i <= $adv_rec_client_count; $i++) {
                        $adv_on_rec_client_ok_ind = isset($_REQUEST['adv_on_rec_client_ok_ind' . $i]) ? $_REQUEST['adv_on_rec_client_ok_ind' . $i] : null;
                        $adv_on_rec_client_name = isset($_REQUEST['adv_on_rec_client_name' . $i]) ? $_REQUEST['adv_on_rec_client_name' . $i] : null;
                        if ($adv_on_rec_client_ok_ind == "Y" && $adv_on_rec_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                   phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['adv_on_rec_client_name' . $i] . "', '" . $_REQUEST['adv_on_rec_client_address_line_1' . $i] . "', '" . $_REQUEST['adv_on_rec_client_address_line_2' . $i] . "', '" . $_REQUEST['adv_on_rec_client_address_line_3' . $i] . "','" . $_REQUEST['adv_on_rec_client_address_line_4' . $i] . "','" . $_REQUEST['adv_on_rec_client_city' . $i] . "','" . $_REQUEST['adv_on_rec_client_pin_code' . $i] . "','" . $_REQUEST['adv_on_rec_client_state_name' . $i] . "','" . $_REQUEST['adv_on_rec_client_country' . $i] . "','" . $_REQUEST['adv_on_rec_client_isd_code' . $i] . "','" . $_REQUEST['adv_on_rec_client_std_code' . $i] . "','" . $_REQUEST['adv_on_rec_client_phone_no' . $i] . "','" . $_REQUEST['adv_on_rec_client_mobile_no' . $i] . "','" . $_REQUEST['adv_on_rec_client_email_id' . $i] . "','" . $_REQUEST['adv_on_rec_client_fax_no' . $i] . "','" . $_REQUEST['adv_on_rec_client_company_name' . $i] . "','" . $_REQUEST['adv_on_rec_client_designation' . $i] . "','" . $_REQUEST['adv_on_rec_client_office_tel' . $i] . "','8','" . $i . "')");
                        }
                    }
                    for ($i = 1; $i <= $int_party_client_count; $i++) {
                        $int_party_client_ok_ind = isset($_REQUEST['int_party_client_ok_ind' . $i]) ? $_REQUEST['int_party_client_ok_ind' . $i] : null;
                        $adv_on_rec_client_name = isset($_REQUEST['adv_on_rec_client_name' . $i]) ? $_REQUEST['adv_on_rec_client_name' . $i] : null;
                        if ($int_party_client_ok_ind == "Y" && $adv_on_rec_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                   phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['int_party_client_name' . $i] . "', '" . $_REQUEST['int_party_client_address_line_1' . $i] . "', '" . $_REQUEST['int_party_client_address_line_2' . $i] . "', '" . $_REQUEST['int_party_client_address_line_3' . $i] . "','" . $_REQUEST['int_party_client_address_line_4' . $i] . "','" . $_REQUEST['int_party_client_city' . $i] . "','" . $_REQUEST['int_party_client_pin_code' . $i] . "','" . $_REQUEST['int_party_client_state_name' . $i] . "','" . $_REQUEST['int_party_client_country' . $i] . "','" . $_REQUEST['int_party_client_isd_code' . $i] . "','" . $_REQUEST['int_party_client_std_code' . $i] . "','" . $_REQUEST['int_party_client_phone_no' . $i] . "','" . $_REQUEST['int_party_client_mobile_no' . $i] . "','" . $_REQUEST['int_party_client_email_id' . $i] . "','" . $_REQUEST['int_party_client_fax_no' . $i] . "','" . $_REQUEST['int_party_client_company_name' . $i] . "','" . $_REQUEST['int_party_client_designation' . $i] . "','" . $_REQUEST['int_party_client_office_tel' . $i] . "','9','" . $i . "')");
                        }
                    }
                    for ($i = 1; $i <= $repr_by_client_count; $i++) {
                        $repr_by_client_ok_ind = isset($_REQUEST['repr_by_client_ok_ind' . $i]) ? $_REQUEST['repr_by_client_ok_ind' . $i] : null;
                        $repr_by_client_name = isset($_REQUEST['repr_by_client_name' . $i]) ? $_REQUEST['repr_by_client_name' . $i] : null;
                        if ($repr_by_client_ok_ind == "Y" && $repr_by_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                  phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['repr_by_client_name' . $i] . "', '" . $_REQUEST['repr_by_client_address_line_1' . $i] . "', '" . $_REQUEST['repr_by_client_address_line_2' . $i] . "', '" . $_REQUEST['repr_by_client_address_line_3' . $i] . "','" . $_REQUEST['repr_by_client_address_line_4' . $i] . "','" . $_REQUEST['repr_by_client_city' . $i] . "','" . $_REQUEST['repr_by_client_pin_code' . $i] . "','" . $_REQUEST['repr_by_client_state_name' . $i] . "','" . $_REQUEST['repr_by_client_country' . $i] . "','" . $_REQUEST['repr_by_client_isd_code' . $i] . "','" . $_REQUEST['repr_by_client_std_code' . $i] . "','" . $_REQUEST['repr_by_client_phone_no' . $i] . "','" . $_REQUEST['repr_by_client_mobile_no' . $i] . "','" . $_REQUEST['repr_by_client_email_id' . $i] . "','" . $_REQUEST['repr_by_client_fax_no' . $i] . "','" . $_REQUEST['repr_by_client_company_name' . $i] . "','" . $_REQUEST['repr_by_client_designation' . $i] . "','" . $_REQUEST['repr_by_client_office_tel' . $i] . "','10','" . $i . "')");
                        }
                    }
                    for ($i = 1; $i <= $ref_by_client_count; $i++) {
                        $ref_by_client_ok_ind = isset($_REQUEST['ref_by_client_ok_ind' . $i]) ? $_REQUEST['ref_by_client_ok_ind' . $i] : null;
                        $ref_by_client_name = isset($_REQUEST['ref_by_client_name' . $i]) ? $_REQUEST['ref_by_client_name' . $i] : null;
                        if ($ref_by_client_ok_ind == "Y" && $ref_by_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                  phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$lastId','" . $_REQUEST['ref_by_client_name' . $i] . "', '" . $_REQUEST['ref_by_client_address_line_1' . $i] . "', '" . $_REQUEST['ref_by_client_address_line_2' . $i] . "', '" . $_REQUEST['ref_by_client_address_line_3' . $i] . "','" . $_REQUEST['ref_by_client_address_line_4' . $i] . "','" . $_REQUEST['ref_by_client_city' . $i] . "','" . $_REQUEST['ref_by_client_pin_code' . $i] . "','" . $_REQUEST['ref_by_client_state_name' . $i] . "','" . $_REQUEST['ref_by_client_country' . $i] . "','" . $_REQUEST['ref_by_client_isd_code' . $i] . "','" . $_REQUEST['ref_by_client_std_code' . $i] . "','" . $_REQUEST['ref_by_client_phone_no' . $i] . "','" . $_REQUEST['ref_by_client_mobile_no' . $i] . "','" . $_REQUEST['ref_by_client_email_id' . $i] . "','" . $_REQUEST['ref_by_client_fax_no' . $i] . "','" . $_REQUEST['ref_by_client_company_name' . $i] . "','" . $_REQUEST['ref_by_client_designation' . $i] . "','" . $_REQUEST['ref_by_client_office_tel' . $i] . "','11','" . $i . "')");
                        }
                    }
                    for ($i = 1; $i <= $matter_initial_count; $i++) {
                        $matter_initial_chk = isset($_REQUEST['matter_initial_chk' . $i]) ? $_REQUEST['matter_initial_chk' . $i] : null;
                        if ($matter_initial_chk == "on") {

                            $this->db->query("insert into  fileinfo_counsels (matter_code, initial_code , record_code, row_no) values('$lastId','" . $_REQUEST['matter_initial_code' . $i] . "','12', '$i')");
                        }
                    }
                    for ($i = 1; $i <= $related_matter_count; $i++) {
                        $related_matter_ok_ind = isset($_REQUEST['related_matter_ok_ind' . $i]) ? $_REQUEST['related_matter_ok_ind' . $i] : null;
                        $related_matter_code = isset($_REQUEST['related_matter_code' . $i]) ? $_REQUEST['related_matter_code' . $i] : null;
                        if ($related_matter_ok_ind == "Y" && $related_matter_code) {
                            $this->db->query("insert into  fileinfo_related_matters (matter_code, related_matter_code , row_no) values('$lastId','" . $_REQUEST['related_matter_code' . $i] . "', '$i')");
                        }
                    }
                    for ($i = 1; $i <= $matter_org_count; $i++) {
                        $matter_org_ok_ind = isset($_REQUEST['matter_org_ok_ind' . $i]) ? $_REQUEST['matter_org_ok_ind' . $i] : null;
                        $matter_org_record_desc = isset($_REQUEST['other_case_no' . $i]) ? $_REQUEST['matter_org_record_desc' . $i] : null;
                        $matter_org_receivedon = isset($_REQUEST['matter_org_receivedon' . $i]) ? $_REQUEST['matter_org_receivedon' . $i] : '';
                        if($matter_org_receivedon!=''){$matter_org_receivedon=date('Y-m-d',strtotime($matter_org_receivedon));}else{ $matter_org_receivedon=''; }
                        $matter_org_returnon = isset($_REQUEST['matter_org_returnon' . $i]) ? $_REQUEST['matter_org_returnon' . $i] : '';
                        if($matter_org_returnon!=''){$matter_org_returnon=date('Y-m-d',strtotime($matter_org_returnon));}else{ $matter_org_returnon=''; }
                        if ($matter_org_ok_ind == "Y" && $matter_org_record_desc) {
                            $this->db->query("insert into  fileinfo_original_record (matter_code, record_desc, remarks, file_location, receive_date, return_date , row_no) values('$lastId','" . $_REQUEST['matter_org_record_desc' . $i] . "', '" . $_REQUEST['matter_org_remarks' . $i] . "', '" . $_REQUEST['matter_org_filelocation' . $i] . "', '$matter_org_receivedon', '$matter_org_returnon,'$i')");
                        }
                    }
                    for ($i = 1; $i <= $case_no_count; $i++) {
                        $other_case_no = isset($_REQUEST['other_case_no' . $i]) ? $_REQUEST['other_case_no' . $i] : null;
                        if ($other_case_no != "") {
                            $this->db->query("insert into  fileinfo_other_cases (matter_code, case_no, subject_desc, row_no) values('$lastId','" . $_REQUEST['other_case_no' . $i] . "', '" . $_REQUEST['other_subject' . $i] . "','$i')");
                        }
                    }
                    $this->db->query("insert into  fileinfo_case_details (matter_code, case_details) values('$lastId','" . $_REQUEST['case_details'] . "')");

                    for ($i = 1; $i <= $cheques_count; $i++) {
                        $matter_instrument_no = isset($_REQUEST['matter_instrument_no' . $i]) ? $_REQUEST['matter_instrument_no' . $i] : null;
                        if ($matter_instrument_no != "") {

                            $this->db->query("insert into  fileinfo_cheques (matter_code, instrument_no, instrument_dt, bank_name, instr_amt) values('$lastId','" . $_REQUEST['matter_instrument_no' . $i] . "', '" . date('Y-m-d', strtotime($_REQUEST['matter_instrument_dt' . $i])) . "','" . $_REQUEST['matter_bank_name' . $i] . "','" . $_REQUEST['matter_instr_amt' . $i] . "')");
                        }
                    }
                    session()->setFlashdata('message', 'Please note generated Matter No = ' . $lastId);
                    return redirect()->to($url);
                //     $message = 'Please note generated Matter No = ' . $lastId;

                //    echo '<script type="text/javascript">
                //    return redirect()->to("/matter-master?display_id={$display_id}&menu_id={$menu_id}&message={$message}");
                //   </script>';  
                    break;

                default:
                case 'Edit':
                    $cnt = 0;
                    $this->db->query("update fileinfo_header set branch_code='$branchCode', initial_code ='$initial_code', file_locn_code='$file_locn_code', matter_type_code='$matter_type_code',matter_sub_type_code='$matter_sub_type_code',matter_sub_sub_type_code='$matter_sub_sub_type_code',court_code='$court_code',
                judge_name='$judge_name',case_type_code='$case_type_code',case_no='$case_no',case_year='$case_year',matter_desc1='$matter_desc1',matter_desc2='$matter_desc2',
                trust_name='$trust_name',client_code='$client_code',appearing_for_code='$appearing_for_code', date_of_filing='$date_of_filing',
                appearing_for_no='$appearing_for_no', requisition_no='$requisition_no', stake_amount='$stake_amount', notice_no='$notice_no', notice_date='$notice_date',
                apply_oppose_ind='$apply_opposeInd1', billable_option='$billable_option1', reference_desc='$reference_desc', reference_type_code='$reference_type_code',
                subject_desc='$subject_desc', corrp_addr_code='$corrp_addr_code',corrp_attn_code='$corrp_attn_code',first_activity_date='$first_activity_date',first_fixed_for='$first_fixed_for', status_code='$status_code',last_bill_date='" . date('Y-m-d') . "',last_update_ip='$ip' where matter_code='$matter_code'");
                    if ($matter_code != "") {
                        $this->db->query("delete from  fileinfo_details  where  matter_code='$matter_code'");
                    }
                    for ($i = 1; $i <= $other_side_count; $i++) {
                        $other_side_ok_ind = isset($_REQUEST['other_side_ok_ind' . $i]) ? $_REQUEST['other_side_ok_ind' . $i] : null;
                        $other_side_name = isset($_REQUEST['other_side_name' . $i]) ? $_REQUEST['other_side_name' . $i] : null;
                        if ($other_side_ok_ind == "Y" && $other_side_name != '') {
                            
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['other_side_name' . $i] . "', '" . $_REQUEST['other_side_address_line_1' . $i] . "', '" . $_REQUEST['other_side_address_line_2' . $i] . "', '" . $_REQUEST['other_side_address_line_3' . $i] . "','" . $_REQUEST['other_side_address_line_4' . $i] . "','" . $_REQUEST['other_side_city' . $i] . "','" . $_REQUEST['other_side_pin_code' . $i] . "','" . $_REQUEST['other_side_state_name' . $i] . "','" . $_REQUEST['other_side_country' . $i] . "','" . $_REQUEST['other_side_isd_code' . $i] . "','" . $_REQUEST['other_side_std_code' . $i] . "','" . $_REQUEST['other_side_phone_no' . $i] . "','" . $_REQUEST['other_side_mobile_no' . $i] . "','" . $_REQUEST['other_side_email_id' . $i] . "','" . $_REQUEST['other_side_fax_no' . $i] . "','" . $_REQUEST['other_side_company_name' . $i] . "','" . $_REQUEST['other_side_designation' . $i] . "','" . $_REQUEST['other_side_office_tel' . $i] . "','1','$i')");
                        }
                    }
                    for ($i = 1; $i <= $counsel_other_side_count; $i++) {
                        $counsel_other_side_ok_ind = isset($_REQUEST['counsel_other_side_ok_ind' . $i]) ? $_REQUEST['counsel_other_side_ok_ind' . $i] : null;
                        $counsel_other_side_name = isset($_REQUEST['counsel_other_side_name' . $i]) ? $_REQUEST['counsel_other_side_name' . $i] : null;
                        if ($counsel_other_side_ok_ind == "Y" && $counsel_other_side_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['counsel_other_side_name' . $i] . "', '" . $_REQUEST['counsel_other_side_address_line_1' . $i] . "', '" . $_REQUEST['counsel_other_side_address_line_2' . $i] . "', '" . $_REQUEST['counsel_other_side_address_line_3' . $i] . "','" . $_REQUEST['counsel_other_side_address_line_4' . $i] . "','" . $_REQUEST['counsel_other_side_city' . $i] . "','" . $_REQUEST['counsel_other_side_pin_code' . $i] . "','" . $_REQUEST['counsel_other_side_state_name' . $i] . "','" . $_REQUEST['counsel_other_side_country' . $i] . "','" . $_REQUEST['counsel_other_side_isd_code' . $i] . "','" . $_REQUEST['counsel_other_side_std_code' . $i] . "','" . $_REQUEST['counsel_other_side_phone_no' . $i] . "','" . $_REQUEST['counsel_other_side_mobile_no' . $i] . "','" . $_REQUEST['counsel_other_side_email_id' . $i] . "','" . $_REQUEST['counsel_other_side_fax_no' . $i] . "','" . $_REQUEST['counsel_other_side_company_name' . $i] . "','" . $_REQUEST['counsel_other_side_designation' . $i] . "','" . $_REQUEST['counsel_other_side_office_tel' . $i] . "','2','$i')");
                        }
                    }
                    for ($i = 1; $i <= $advidor_other_side_count; $i++) {
                        $advisor_other_side_ok_ind = isset($_REQUEST['advisor_other_side_ok_ind' . $i]) ? $_REQUEST['advisor_other_side_ok_ind' . $i] : null;
                        $advisor_other_side_name = isset($_REQUEST['advisor_other_side_name' . $i]) ? $_REQUEST['advisor_other_side_name' . $i] : null;
                        if ($advisor_other_side_ok_ind == "Y" && $advisor_other_side_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['advisor_other_side_name' . $i] . "', '" . $_REQUEST['advisor_other_side_address_line_1' . $i] . "', '" . $_REQUEST['advisor_other_side_address_line_2' . $i] . "', '" . $_REQUEST['advisor_other_side_address_line_3' . $i] . "','" . $_REQUEST['advisor_other_side_address_line_4' . $i] . "','" . $_REQUEST['advisor_other_side_city' . $i] . "','" . $_REQUEST['advisor_other_side_pin_code' . $i] . "','" . $_REQUEST['advisor_other_side_state_name' . $i] . "','" . $_REQUEST['advisor_other_side_country' . $i] . "','" . $_REQUEST['advisor_other_side_isd_code' . $i] . "','" . $_REQUEST['advisor_other_side_std_code' . $i] . "','" . $_REQUEST['advisor_other_side_phone_no' . $i] . "','" . $_REQUEST['advisor_other_side_mobile_no' . $i] . "','" . $_REQUEST['advisor_other_side_email_id' . $i] . "','" . $_REQUEST['advisor_other_side_fax_no' . $i] . "','" . $_REQUEST['advisor_other_side_company_name' . $i] . "','" . $_REQUEST['advisor_other_side_designation' . $i] . "','" . $_REQUEST['advisor_other_side_office_tel' . $i] . "','3','$i')");
                        }
                    }
                    for ($i = 1; $i <= $adv_on_rec_other_side_count; $i++) {
                        $adv_on_rec_other_side_ok_ind = isset($_REQUEST['adv_on_rec_other_side_ok_ind' . $i]) ? $_REQUEST['adv_on_rec_other_side_ok_ind' . $i] : null;
                        $adv_on_rec_other_side_name = isset($_REQUEST['adv_on_rec_other_side_name' . $i]) ? $_REQUEST['adv_on_rec_other_side_name' . $i] : null;
                        if ($adv_on_rec_other_side_ok_ind == "Y" && $adv_on_rec_other_side_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['adv_on_rec_other_side_name' . $i] . "', '" . $_REQUEST['adv_on_rec_other_side_address_line_1' . $i] . "', '" . $_REQUEST['adv_on_rec_other_side_address_line_2' . $i] . "', '" . $_REQUEST['adv_on_rec_other_side_address_line_3' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_address_line_4' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_city' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_pin_code' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_state_name' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_country' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_isd_code' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_std_code' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_phone_no' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_mobile_no' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_email_id' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_fax_no' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_company_name' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_designation' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_office_tel' . $i] . "','4','$i')");
                        }
                    }
                    for ($i = 1; $i <= $int_party_othher_side_count; $i++) {
                        $int_party_other_side_ok_ind = isset($_REQUEST['int_party_other_side_ok_ind' . $i]) ? $_REQUEST['int_party_other_side_ok_ind' . $i] : null;
                        $int_party_other_side_name = isset($_REQUEST['int_party_other_side_name' . $i]) ? $_REQUEST['int_party_other_side_name' . $i] : null;
                        if ($int_party_other_side_ok_ind == "Y" && $int_party_other_side_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['int_party_other_side_name' . $i] . "', '" . $_REQUEST['int_party_other_side_address_line_1' . $i] . "', '" . $_REQUEST['int_party_other_side_address_line_2' . $i] . "', '" . $_REQUEST['int_party_other_side_address_line_3' . $i] . "','" . $_REQUEST['int_party_other_side_address_line_4' . $i] . "','" . $_REQUEST['int_party_other_side_city' . $i] . "','" . $_REQUEST['int_party_other_side_pin_code' . $i] . "','" . $_REQUEST['int_party_other_side_state_name' . $i] . "','" . $_REQUEST['int_party_other_side_country' . $i] . "','" . $_REQUEST['int_party_other_side_isd_code' . $i] . "','" . $_REQUEST['int_party_other_side_std_code' . $i] . "','" . $_REQUEST['int_party_other_side_phone_no' . $i] . "','" . $_REQUEST['int_party_other_side_mobile_no' . $i] . "','" . $_REQUEST['int_party_other_side_email_id' . $i] . "','" . $_REQUEST['int_party_other_side_fax_no' . $i] . "','" . $_REQUEST['int_party_other_side_company_name' . $i] . "','" . $_REQUEST['int_party_other_side_designation' . $i] . "','" . $_REQUEST['int_party_other_side_office_tel' . $i] . "','5','$i')");
                        }
                    }
                    for ($i = 1; $i <= $advisor_client_count; $i++) {
                        $advisor_client_ok_ind = isset($_REQUEST['advisor_client_ok_ind' . $i]) ? $_REQUEST['advisor_client_ok_ind' . $i] : null;
                        $advisor_client_name = isset($_REQUEST['advisor_client_name' . $i]) ? $_REQUEST['advisor_client_name' . $i] : null;
                        if ($advisor_client_ok_ind == "Y" && $advisor_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['advisor_client_name' . $i] . "', '" . $_REQUEST['advisor_client_address_line_1' . $i] . "', '" . $_REQUEST['advisor_client_address_line_2' . $i] . "', '" . $_REQUEST['advisor_client_address_line_3' . $i] . "','" . $_REQUEST['advisor_client_address_line_4' . $i] . "','" . $_REQUEST['advisor_client_city' . $i] . "','" . $_REQUEST['advisor_client_pin_code' . $i] . "','" . $_REQUEST['advisor_client_state_name' . $i] . "','" . $_REQUEST['advisor_client_country' . $i] . "','" . $_REQUEST['advisor_client_isd_code' . $i] . "','" . $_REQUEST['advisor_client_std_code' . $i] . "','" . $_REQUEST['advisor_client_phone_no' . $i] . "','" . $_REQUEST['advisor_client_mobile_no' . $i] . "','" . $_REQUEST['advisor_client_email_id' . $i] . "','" . $_REQUEST['advisor_client_fax_no' . $i] . "','" . $_REQUEST['advisor_client_company_name' . $i] . "','" . $_REQUEST['advisor_client_designation' . $i] . "','" . $_REQUEST['advisor_client_office_tel' . $i] . "','6','$i')");
                        }
                    }
                    $cnt = 0;
                    $this->db->query("delete from  fileinfo_counsels  where  matter_code='$matter_code' and record_code='7'");
                    for ($i = 0; $i < $matter_counsel_codeCount; $i++) {
                        if ($matter_counsel_codeAr != "") {
                            $cnt += 1;
                            $this->db->query("insert into  fileinfo_counsels (matter_code , record_code, row_no, initial_code) values('$matter_code','7','$cnt','" . $matter_counsel_codeAr[$i] . "')");
                        }
                    }
                    for ($i = 1; $i <= $adv_rec_client_count; $i++) {
                        $adv_on_rec_client_ok_ind = isset($_REQUEST['adv_on_rec_client_ok_ind' . $i]) ? $_REQUEST['adv_on_rec_client_ok_ind' . $i] : null;
                        $adv_on_rec_client_name = isset($_REQUEST['adv_on_rec_client_name' . $i]) ? $_REQUEST['adv_on_rec_client_name' . $i] : null;
                        if ($adv_on_rec_client_ok_ind == "Y" && $adv_on_rec_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','".$_REQUEST['adv_on_rec_client_name'. $i]."','" . $_REQUEST['adv_on_rec_client_address_line_1' . $i] . "', '" . $_REQUEST['adv_on_rec_client_address_line_2' . $i] . "', '" . $_REQUEST['adv_on_rec_client_address_line_3' . $i] . "', '" . $_REQUEST['adv_on_rec_client_address_line_4' . $i] . "','" . $_REQUEST['adv_on_rec_client_city' . $i] . "','" . $_REQUEST['adv_on_rec_client_pin_code' . $i] . "','" . $_REQUEST['adv_on_rec_client_state_name' . $i] . "','" . $_REQUEST['adv_on_rec_client_country' . $i] . "','" . $_REQUEST['adv_on_rec_client_isd_code' . $i] . "','" . $_REQUEST['adv_on_rec_client_std_code' . $i] . "','" . $_REQUEST['adv_on_rec_client_phone_no' . $i] . "','" . $_REQUEST['adv_on_rec_client_mobile_no' . $i] . "','" . $_REQUEST['adv_on_rec_client_email_id' . $i] . "','" . $_REQUEST['adv_on_rec_client_fax_no' . $i] . "','" . $_REQUEST['adv_on_rec_client_company_name' . $i] . "','" . $_REQUEST['adv_on_rec_client_designation' . $i] . "','" . $_REQUEST['adv_on_rec_client_office_tel' . $i] . "','8','$i')");
                        }
                    }
                    for ($i = 1; $i <= $int_party_client_count; $i++) {
                        $int_party_client_ok_ind = isset($_REQUEST['int_party_client_ok_ind' . $i]) ? $_REQUEST['int_party_client_ok_ind' . $i] : null;
                        $adv_on_rec_client_name = isset($_REQUEST['adv_on_rec_client_name' . $i]) ? $_REQUEST['adv_on_rec_client_name' . $i] : null;
                        if ($int_party_client_ok_ind == "Y" && $adv_on_rec_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['int_party_client_name' . $i] . "', '" . $_REQUEST['int_party_client_address_line_1' . $i] . "', '" . $_REQUEST['int_party_client_address_line_2' . $i] . "', '" . $_REQUEST['int_party_client_address_line_3' . $i] . "','" . $_REQUEST['int_party_client_address_line_4' . $i] . "','" . $_REQUEST['int_party_client_city' . $i] . "','" . $_REQUEST['int_party_client_pin_code' . $i] . "','" . $_REQUEST['int_party_client_state_name' . $i] . "','" . $_REQUEST['int_party_client_country' . $i] . "','" . $_REQUEST['int_party_client_isd_code' . $i] . "','" . $_REQUEST['int_party_client_std_code' . $i] . "','" . $_REQUEST['int_party_client_phone_no' . $i] . "','" . $_REQUEST['int_party_client_mobile_no' . $i] . "','" . $_REQUEST['int_party_client_email_id' . $i] . "','" . $_REQUEST['int_party_client_fax_no' . $i] . "','" . $_REQUEST['int_party_client_company_name' . $i] . "','" . $_REQUEST['int_party_client_designation' . $i] . "','" . $_REQUEST['int_party_client_office_tel' . $i] . "','9','$i')");
                        }
                    }
                    for ($i = 1; $i <= $repr_by_client_count; $i++) {
                        $repr_by_client_ok_ind = isset($_REQUEST['repr_by_client_ok_ind' . $i]) ? $_REQUEST['repr_by_client_ok_ind' . $i] : null;
                        $repr_by_client_name = isset($_REQUEST['repr_by_client_name' . $i]) ? $_REQUEST['repr_by_client_name' . $i] : null;
                        if ($repr_by_client_ok_ind == "Y" && $repr_by_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['repr_by_client_name' . $i] . "', '" . $_REQUEST['repr_by_client_address_line_1' . $i] . "', '" . $_REQUEST['repr_by_client_address_line_2' . $i] . "', '" . $_REQUEST['repr_by_client_address_line_3' . $i] . "','" . $_REQUEST['repr_by_client_address_line_4' . $i] . "','" . $_REQUEST['repr_by_client_city' . $i] . "','" . $_REQUEST['repr_by_client_pin_code' . $i] . "','" . $_REQUEST['repr_by_client_state_name' . $i] . "','" . $_REQUEST['repr_by_client_country' . $i] . "','" . $_REQUEST['repr_by_client_isd_code' . $i] . "','" . $_REQUEST['repr_by_client_std_code' . $i] . "','" . $_REQUEST['repr_by_client_phone_no' . $i] . "','" . $_REQUEST['repr_by_client_mobile_no' . $i] . "','" . $_REQUEST['repr_by_client_email_id' . $i] . "','" . $_REQUEST['repr_by_client_fax_no' . $i] . "','" . $_REQUEST['repr_by_client_company_name' . $i] . "','" . $_REQUEST['repr_by_client_designation' . $i] . "','" . $_REQUEST['repr_by_client_office_tel' . $i] . "','10','$i')");
                        }
                    }
                    for ($i = 1; $i <= $ref_by_client_count; $i++) {
                        $ref_by_client_ok_ind = isset($_REQUEST['ref_by_client_ok_ind' . $i]) ? $_REQUEST['ref_by_client_ok_ind' . $i] : null;
                        $ref_by_client_name = isset($_REQUEST['ref_by_client_name' . $i]) ? $_REQUEST['ref_by_client_name' . $i] : null;
                        if ($ref_by_client_ok_ind == "Y" && $ref_by_client_name != '') {
                            $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                      phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['ref_by_client_name' . $i] . "', '" . $_REQUEST['ref_by_client_address_line_1' . $i] . "', '" . $_REQUEST['ref_by_client_address_line_2' . $i] . "', '" . $_REQUEST['ref_by_client_address_line_3' . $i] . "','" . $_REQUEST['ref_by_client_address_line_4' . $i] . "','" . $_REQUEST['ref_by_client_city' . $i] . "','" . $_REQUEST['ref_by_client_pin_code' . $i] . "','" . $_REQUEST['ref_by_client_state_name' . $i] . "','" . $_REQUEST['ref_by_client_country' . $i] . "','" . $_REQUEST['ref_by_client_isd_code' . $i] . "','" . $_REQUEST['ref_by_client_std_code' . $i] . "','" . $_REQUEST['ref_by_client_phone_no' . $i] . "','" . $_REQUEST['ref_by_client_mobile_no' . $i] . "','" . $_REQUEST['ref_by_client_email_id' . $i] . "','" . $_REQUEST['ref_by_client_fax_no' . $i] . "','" . $_REQUEST['ref_by_client_company_name' . $i] . "','" . $_REQUEST['ref_by_client_designation' . $i] . "','" . $_REQUEST['ref_by_client_office_tel' . $i] . "','11','$i')");
                        }
                    }
                    if ($matter_code != "") {
                        $this->db->query("delete from  fileinfo_counsels  where  matter_code='$matter_code' and record_code='12'");
                    }
                    for ($i = 1; $i <= $matter_initial_count; $i++) {
                        $matter_initial_chk = isset($_REQUEST['matter_initial_chk' . $i]) ? $_REQUEST['matter_initial_chk' . $i] : null;
                        if ($matter_initial_chk == "on") {

                            $this->db->query("insert into  fileinfo_counsels (matter_code, initial_code , record_code, row_no) values('$matter_code','" . $_REQUEST['matter_initial_code' . $i] . "','12', '$i')");
                        }
                    }
                    $this->db->query("delete from  fileinfo_related_matters  where  matter_code='$matter_code'");
                    for ($i = 1; $i <= $related_matter_count; $i++) {
                        $related_matter_ok_ind = isset($_REQUEST['related_matter_ok_ind' . $i]) ? $_REQUEST['related_matter_ok_ind' . $i] : null;
                        $related_matter_code = isset($_REQUEST['related_matter_code' . $i]) ? $_REQUEST['related_matter_code' . $i] : null;
                        if ($related_matter_ok_ind == "Y" && $related_matter_code) {
                            $this->db->query("insert into  fileinfo_related_matters (matter_code, related_matter_code , row_no) values('$matter_code','" . $_REQUEST['related_matter_code' . $i] . "', '$i')");
                        }
                    }
                    $this->db->query("delete from  fileinfo_original_record  where  matter_code='$matter_code'");
                    for ($i = 1; $i <= $matter_org_count; $i++) {
                        $matter_org_ok_ind = isset($_REQUEST['matter_org_ok_ind' . $i]) ? $_REQUEST['matter_org_ok_ind' . $i] : null;
                        $matter_org_record_desc = isset($_REQUEST['matter_org_record_desc' . $i]) ? $_REQUEST['matter_org_record_desc' . $i] : null;
                        $matter_org_receivedon = isset($_REQUEST['matter_org_receivedon' . $i]) ? $_REQUEST['matter_org_receivedon' . $i] : '';
                        if($matter_org_receivedon!=''){$matter_org_receivedon=date('Y-m-d',strtotime($matter_org_receivedon));}else{ $matter_org_receivedon=''; }
                        $matter_org_returnon = isset($_REQUEST['matter_org_returnon' . $i]) ? $_REQUEST['matter_org_returnon' . $i] : '';
                        if($matter_org_returnon!=''){$matter_org_returnon=date('Y-m-d',strtotime($matter_org_returnon));}else{ $matter_org_returnon=''; }
                        if ($matter_org_ok_ind == "Y" && $matter_org_record_desc) {
                            $this->db->query("insert into  fileinfo_original_record (matter_code, record_desc, remarks, file_location, receive_date, return_date , row_no) values('$matter_code','" . $_REQUEST['matter_org_record_desc' . $i] . "', '" . $_REQUEST['matter_org_remarks'. $i] . "', '" . $_REQUEST['matter_org_filelocation' .$i] . "', '$matter_org_receivedon', '$matter_org_returnon','$i')");
                        }
                    }
                    $this->db->query("delete from  fileinfo_other_cases  where  matter_code='$matter_code'");
                    for ($i = 1; $i <= $case_no_count; $i++) {
                        $other_case_no = isset($_REQUEST['other_case_no' . $i]) ? $_REQUEST['other_case_no' . $i] : null;
                        if ($other_case_no != "") {
                            $this->db->query("insert into  fileinfo_other_cases (matter_code, case_no, subject_desc, row_no) values('$matter_code','" . $_REQUEST['other_case_no' . $i] . "', '" . $_REQUEST['other_subject' . $i] . "','$i')");
                        }
                    }
                    $this->db->query("delete from  fileinfo_case_details  where  matter_code='$matter_code'");
                    $this->db->query("insert into  fileinfo_case_details (matter_code, case_details) values('$matter_code','$case_details')");
                    $this->db->query("delete from  fileinfo_cheques  where  matter_code='$matter_code'");
                    $cheques_count = isset($_REQUEST['cheques_count']) ? $_REQUEST['cheques_count'] : null;
                    for ($i = 1; $i <= $cheques_count; $i++) {
                        $matter_instr_amt = isset($_REQUEST['matter_instr_amt' . $i]) ? $_REQUEST['matter_instr_amt' . $i] : null;
                        $matter_instrument_dt = isset($_REQUEST['matter_instrument_dt' . $i]) ? $_REQUEST['matter_instrument_dt' . $i] : '';
                         if($matter_instrument_dt!=''){$matter_instrument_dt=date('Y-m-d',strtotime($matter_instrument_dt));}else{ $matter_instrument_dt=''; }
                        if ($matter_instr_amt != "") {
                            $this->db->query("insert into  fileinfo_cheques (matter_code, instrument_no, instrument_dt, bank_name, instr_amt) values('$matter_code','" . $_REQUEST['matter_instrument_no' . $i] . "', '$matter_instrument_dt','" . $_REQUEST['matter_bank_name'. $i] . "','" . $_REQUEST['matter_instr_amt'. $i] . "')");
                        }
                    }
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($data['requested_url']);
                    break;
                    case 'View':
                        $cnt = 0;
                        $this->db->query("update fileinfo_header set branch_code='$branchCode', initial_code ='$initial_code', file_locn_code='$file_locn_code', matter_type_code='$matter_type_code',matter_sub_type_code='$matter_sub_type_code',matter_sub_sub_type_code='$matter_sub_sub_type_code',court_code='$court_code',
                    judge_name='$judge_name',case_type_code='$case_type_code',case_no='$case_no',case_year='$case_year',matter_desc1='$matter_desc1',matter_desc2='$matter_desc2',
                    trust_name='$trust_name',client_code='$client_code',appearing_for_code='$appearing_for_code', date_of_filing='$date_of_filing',
                    appearing_for_no='$appearing_for_no', requisition_no='$requisition_no', stake_amount='$stake_amount', notice_no='$notice_no', notice_date='$notice_date',
                    apply_oppose_ind='$apply_opposeInd1', billable_option='$billable_option1', reference_desc='$reference_desc', reference_type_code='$reference_type_code',
                    subject_desc='$subject_desc', corrp_addr_code='$corrp_addr_code',corrp_attn_code='$corrp_attn_code',first_activity_date='$first_activity_date',first_fixed_for='$first_fixed_for', status_code='$status_code',last_bill_date='" . date('Y-m-d') . "',last_update_ip='$ip' where matter_code='$matter_code'");
                        if ($matter_code != "") {
                            $this->db->query("delete from  fileinfo_details  where  matter_code='$matter_code'");
                        }
                        for ($i = 1; $i <= $other_side_count; $i++) {
                            $other_side_ok_ind = isset($_REQUEST['other_side_ok_ind' . $i]) ? $_REQUEST['other_side_ok_ind' . $i] : null;
                            $other_side_name = isset($_REQUEST['other_side_name' . $i]) ? $_REQUEST['other_side_name' . $i] : null;
                            if ($other_side_ok_ind == "Y" && $other_side_name != '') {
                                
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['other_side_name' . $i] . "', '" . $_REQUEST['other_side_address_line_1' . $i] . "', '" . $_REQUEST['other_side_address_line_2' . $i] . "', '" . $_REQUEST['other_side_address_line_3' . $i] . "','" . $_REQUEST['other_side_address_line_4' . $i] . "','" . $_REQUEST['other_side_city' . $i] . "','" . $_REQUEST['other_side_pin_code' . $i] . "','" . $_REQUEST['other_side_state_name' . $i] . "','" . $_REQUEST['other_side_country' . $i] . "','" . $_REQUEST['other_side_isd_code' . $i] . "','" . $_REQUEST['other_side_std_code' . $i] . "','" . $_REQUEST['other_side_phone_no' . $i] . "','" . $_REQUEST['other_side_mobile_no' . $i] . "','" . $_REQUEST['other_side_email_id' . $i] . "','" . $_REQUEST['other_side_fax_no' . $i] . "','" . $_REQUEST['other_side_company_name' . $i] . "','" . $_REQUEST['other_side_designation' . $i] . "','" . $_REQUEST['other_side_office_tel' . $i] . "','1','$i')");
                            }
                        }
                        for ($i = 1; $i <= $counsel_other_side_count; $i++) {
                            $counsel_other_side_ok_ind = isset($_REQUEST['counsel_other_side_ok_ind' . $i]) ? $_REQUEST['counsel_other_side_ok_ind' . $i] : null;
                            $counsel_other_side_name = isset($_REQUEST['counsel_other_side_name' . $i]) ? $_REQUEST['counsel_other_side_name' . $i] : null;
                            if ($counsel_other_side_ok_ind == "Y" && $counsel_other_side_name != '') {
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['counsel_other_side_name' . $i] . "', '" . $_REQUEST['counsel_other_side_address_line_1' . $i] . "', '" . $_REQUEST['counsel_other_side_address_line_2' . $i] . "', '" . $_REQUEST['counsel_other_side_address_line_3' . $i] . "','" . $_REQUEST['counsel_other_side_address_line_4' . $i] . "','" . $_REQUEST['counsel_other_side_city' . $i] . "','" . $_REQUEST['counsel_other_side_pin_code' . $i] . "','" . $_REQUEST['counsel_other_side_state_name' . $i] . "','" . $_REQUEST['counsel_other_side_country' . $i] . "','" . $_REQUEST['counsel_other_side_isd_code' . $i] . "','" . $_REQUEST['counsel_other_side_std_code' . $i] . "','" . $_REQUEST['counsel_other_side_phone_no' . $i] . "','" . $_REQUEST['counsel_other_side_mobile_no' . $i] . "','" . $_REQUEST['counsel_other_side_email_id' . $i] . "','" . $_REQUEST['counsel_other_side_fax_no' . $i] . "','" . $_REQUEST['counsel_other_side_company_name' . $i] . "','" . $_REQUEST['counsel_other_side_designation' . $i] . "','" . $_REQUEST['counsel_other_side_office_tel' . $i] . "','2','$i')");
                            }
                        }
                        for ($i = 1; $i <= $advidor_other_side_count; $i++) {
                            $advisor_other_side_ok_ind = isset($_REQUEST['advisor_other_side_ok_ind' . $i]) ? $_REQUEST['advisor_other_side_ok_ind' . $i] : null;
                            $advisor_other_side_name = isset($_REQUEST['advisor_other_side_name' . $i]) ? $_REQUEST['advisor_other_side_name' . $i] : null;
                            if ($advisor_other_side_ok_ind == "Y" && $advisor_other_side_name != '') {
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['advisor_other_side_name' . $i] . "', '" . $_REQUEST['advisor_other_side_address_line_1' . $i] . "', '" . $_REQUEST['advisor_other_side_address_line_2' . $i] . "', '" . $_REQUEST['advisor_other_side_address_line_3' . $i] . "','" . $_REQUEST['advisor_other_side_address_line_4' . $i] . "','" . $_REQUEST['advisor_other_side_city' . $i] . "','" . $_REQUEST['advisor_other_side_pin_code' . $i] . "','" . $_REQUEST['advisor_other_side_state_name' . $i] . "','" . $_REQUEST['advisor_other_side_country' . $i] . "','" . $_REQUEST['advisor_other_side_isd_code' . $i] . "','" . $_REQUEST['advisor_other_side_std_code' . $i] . "','" . $_REQUEST['advisor_other_side_phone_no' . $i] . "','" . $_REQUEST['advisor_other_side_mobile_no' . $i] . "','" . $_REQUEST['advisor_other_side_email_id' . $i] . "','" . $_REQUEST['advisor_other_side_fax_no' . $i] . "','" . $_REQUEST['advisor_other_side_company_name' . $i] . "','" . $_REQUEST['advisor_other_side_designation' . $i] . "','" . $_REQUEST['advisor_other_side_office_tel' . $i] . "','3','$i')");
                            }
                        }
                        for ($i = 1; $i <= $adv_on_rec_other_side_count; $i++) {
                            $adv_on_rec_other_side_ok_ind = isset($_REQUEST['adv_on_rec_other_side_ok_ind' . $i]) ? $_REQUEST['adv_on_rec_other_side_ok_ind' . $i] : null;
                            $adv_on_rec_other_side_name = isset($_REQUEST['adv_on_rec_other_side_name' . $i]) ? $_REQUEST['adv_on_rec_other_side_name' . $i] : null;
                            if ($adv_on_rec_other_side_ok_ind == "Y" && $adv_on_rec_other_side_name != '') {
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['adv_on_rec_other_side_name' . $i] . "', '" . $_REQUEST['adv_on_rec_other_side_address_line_1' . $i] . "', '" . $_REQUEST['adv_on_rec_other_side_address_line_2' . $i] . "', '" . $_REQUEST['adv_on_rec_other_side_address_line_3' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_address_line_4' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_city' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_pin_code' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_state_name' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_country' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_isd_code' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_std_code' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_phone_no' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_mobile_no' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_email_id' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_fax_no' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_company_name' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_designation' . $i] . "','" . $_REQUEST['adv_on_rec_other_side_office_tel' . $i] . "','4','$i')");
                            }
                        }
                        for ($i = 1; $i <= $int_party_othher_side_count; $i++) {
                            $int_party_other_side_ok_ind = isset($_REQUEST['int_party_other_side_ok_ind' . $i]) ? $_REQUEST['int_party_other_side_ok_ind' . $i] : null;
                            $int_party_other_side_name = isset($_REQUEST['int_party_other_side_name' . $i]) ? $_REQUEST['int_party_other_side_name' . $i] : null;
                            if ($int_party_other_side_ok_ind == "Y" && $int_party_other_side_name != '') {
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['int_party_other_side_name' . $i] . "', '" . $_REQUEST['int_party_other_side_address_line_1' . $i] . "', '" . $_REQUEST['int_party_other_side_address_line_2' . $i] . "', '" . $_REQUEST['int_party_other_side_address_line_3' . $i] . "','" . $_REQUEST['int_party_other_side_address_line_4' . $i] . "','" . $_REQUEST['int_party_other_side_city' . $i] . "','" . $_REQUEST['int_party_other_side_pin_code' . $i] . "','" . $_REQUEST['int_party_other_side_state_name' . $i] . "','" . $_REQUEST['int_party_other_side_country' . $i] . "','" . $_REQUEST['int_party_other_side_isd_code' . $i] . "','" . $_REQUEST['int_party_other_side_std_code' . $i] . "','" . $_REQUEST['int_party_other_side_phone_no' . $i] . "','" . $_REQUEST['int_party_other_side_mobile_no' . $i] . "','" . $_REQUEST['int_party_other_side_email_id' . $i] . "','" . $_REQUEST['int_party_other_side_fax_no' . $i] . "','" . $_REQUEST['int_party_other_side_company_name' . $i] . "','" . $_REQUEST['int_party_other_side_designation' . $i] . "','" . $_REQUEST['int_party_other_side_office_tel' . $i] . "','5','$i')");
                            }
                        }
                        for ($i = 1; $i <= $advisor_client_count; $i++) {
                            $advisor_client_ok_ind = isset($_REQUEST['advisor_client_ok_ind' . $i]) ? $_REQUEST['advisor_client_ok_ind' . $i] : null;
                            $advisor_client_name = isset($_REQUEST['advisor_client_name' . $i]) ? $_REQUEST['advisor_client_name' . $i] : null;
                            if ($advisor_client_ok_ind == "Y" && $advisor_client_name != '') {
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['advisor_client_name' . $i] . "', '" . $_REQUEST['advisor_client_address_line_1' . $i] . "', '" . $_REQUEST['advisor_client_address_line_2' . $i] . "', '" . $_REQUEST['advisor_client_address_line_3' . $i] . "','" . $_REQUEST['advisor_client_address_line_4' . $i] . "','" . $_REQUEST['advisor_client_city' . $i] . "','" . $_REQUEST['advisor_client_pin_code' . $i] . "','" . $_REQUEST['advisor_client_state_name' . $i] . "','" . $_REQUEST['advisor_client_country' . $i] . "','" . $_REQUEST['advisor_client_isd_code' . $i] . "','" . $_REQUEST['advisor_client_std_code' . $i] . "','" . $_REQUEST['advisor_client_phone_no' . $i] . "','" . $_REQUEST['advisor_client_mobile_no' . $i] . "','" . $_REQUEST['advisor_client_email_id' . $i] . "','" . $_REQUEST['advisor_client_fax_no' . $i] . "','" . $_REQUEST['advisor_client_company_name' . $i] . "','" . $_REQUEST['advisor_client_designation' . $i] . "','" . $_REQUEST['advisor_client_office_tel' . $i] . "','6','$i')");
                            }
                        }
                        $cnt = 0;
                        $this->db->query("delete from  fileinfo_counsels  where  matter_code='$matter_code' and record_code='7'");
                        for ($i = 0; $i < $matter_counsel_codeCount; $i++) {
                            if ($matter_counsel_codeAr != "") {
                                $cnt += 1;
                                $this->db->query("insert into  fileinfo_counsels (matter_code , record_code, row_no, initial_code) values('$matter_code','7','$cnt','" . $matter_counsel_codeAr[$i] . "')");
                            }
                        }
                        for ($i = 1; $i <= $adv_rec_client_count; $i++) {
                            $adv_on_rec_client_ok_ind = isset($_REQUEST['adv_on_rec_client_ok_ind' . $i]) ? $_REQUEST['adv_on_rec_client_ok_ind' . $i] : null;
                            $adv_on_rec_client_name = isset($_REQUEST['adv_on_rec_client_name' . $i]) ? $_REQUEST['adv_on_rec_client_name' . $i] : null;
                            if ($adv_on_rec_client_ok_ind == "Y" && $adv_on_rec_client_name != '') {
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','".$_REQUEST['adv_on_rec_client_name'. $i]."','" . $_REQUEST['adv_on_rec_client_address_line_1' . $i] . "', '" . $_REQUEST['adv_on_rec_client_address_line_2' . $i] . "', '" . $_REQUEST['adv_on_rec_client_address_line_3' . $i] . "', '" . $_REQUEST['adv_on_rec_client_address_line_4' . $i] . "','" . $_REQUEST['adv_on_rec_client_city' . $i] . "','" . $_REQUEST['adv_on_rec_client_pin_code' . $i] . "','" . $_REQUEST['adv_on_rec_client_state_name' . $i] . "','" . $_REQUEST['adv_on_rec_client_country' . $i] . "','" . $_REQUEST['adv_on_rec_client_isd_code' . $i] . "','" . $_REQUEST['adv_on_rec_client_std_code' . $i] . "','" . $_REQUEST['adv_on_rec_client_phone_no' . $i] . "','" . $_REQUEST['adv_on_rec_client_mobile_no' . $i] . "','" . $_REQUEST['adv_on_rec_client_email_id' . $i] . "','" . $_REQUEST['adv_on_rec_client_fax_no' . $i] . "','" . $_REQUEST['adv_on_rec_client_company_name' . $i] . "','" . $_REQUEST['adv_on_rec_client_designation' . $i] . "','" . $_REQUEST['adv_on_rec_client_office_tel' . $i] . "','8','$i')");
                            }
                        }
                        for ($i = 1; $i <= $int_party_client_count; $i++) {
                            $int_party_client_ok_ind = isset($_REQUEST['int_party_client_ok_ind' . $i]) ? $_REQUEST['int_party_client_ok_ind' . $i] : null;
                            $adv_on_rec_client_name = isset($_REQUEST['adv_on_rec_client_name' . $i]) ? $_REQUEST['adv_on_rec_client_name' . $i] : null;
                            if ($int_party_client_ok_ind == "Y" && $adv_on_rec_client_name != '') {
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['int_party_client_name' . $i] . "', '" . $_REQUEST['int_party_client_address_line_1' . $i] . "', '" . $_REQUEST['int_party_client_address_line_2' . $i] . "', '" . $_REQUEST['int_party_client_address_line_3' . $i] . "','" . $_REQUEST['int_party_client_address_line_4' . $i] . "','" . $_REQUEST['int_party_client_city' . $i] . "','" . $_REQUEST['int_party_client_pin_code' . $i] . "','" . $_REQUEST['int_party_client_state_name' . $i] . "','" . $_REQUEST['int_party_client_country' . $i] . "','" . $_REQUEST['int_party_client_isd_code' . $i] . "','" . $_REQUEST['int_party_client_std_code' . $i] . "','" . $_REQUEST['int_party_client_phone_no' . $i] . "','" . $_REQUEST['int_party_client_mobile_no' . $i] . "','" . $_REQUEST['int_party_client_email_id' . $i] . "','" . $_REQUEST['int_party_client_fax_no' . $i] . "','" . $_REQUEST['int_party_client_company_name' . $i] . "','" . $_REQUEST['int_party_client_designation' . $i] . "','" . $_REQUEST['int_party_client_office_tel' . $i] . "','9','$i')");
                            }
                        }
                        for ($i = 1; $i <= $repr_by_client_count; $i++) {
                            $repr_by_client_ok_ind = isset($_REQUEST['repr_by_client_ok_ind' . $i]) ? $_REQUEST['repr_by_client_ok_ind' . $i] : null;
                            $repr_by_client_name = isset($_REQUEST['repr_by_client_name' . $i]) ? $_REQUEST['repr_by_client_name' . $i] : null;
                            if ($repr_by_client_ok_ind == "Y" && $repr_by_client_name != '') {
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['repr_by_client_name' . $i] . "', '" . $_REQUEST['repr_by_client_address_line_1' . $i] . "', '" . $_REQUEST['repr_by_client_address_line_2' . $i] . "', '" . $_REQUEST['repr_by_client_address_line_3' . $i] . "','" . $_REQUEST['repr_by_client_address_line_4' . $i] . "','" . $_REQUEST['repr_by_client_city' . $i] . "','" . $_REQUEST['repr_by_client_pin_code' . $i] . "','" . $_REQUEST['repr_by_client_state_name' . $i] . "','" . $_REQUEST['repr_by_client_country' . $i] . "','" . $_REQUEST['repr_by_client_isd_code' . $i] . "','" . $_REQUEST['repr_by_client_std_code' . $i] . "','" . $_REQUEST['repr_by_client_phone_no' . $i] . "','" . $_REQUEST['repr_by_client_mobile_no' . $i] . "','" . $_REQUEST['repr_by_client_email_id' . $i] . "','" . $_REQUEST['repr_by_client_fax_no' . $i] . "','" . $_REQUEST['repr_by_client_company_name' . $i] . "','" . $_REQUEST['repr_by_client_designation' . $i] . "','" . $_REQUEST['repr_by_client_office_tel' . $i] . "','10','$i')");
                            }
                        }
                        for ($i = 1; $i <= $ref_by_client_count; $i++) {
                            $ref_by_client_ok_ind = isset($_REQUEST['ref_by_client_ok_ind' . $i]) ? $_REQUEST['ref_by_client_ok_ind' . $i] : null;
                            $ref_by_client_name = isset($_REQUEST['ref_by_client_name' . $i]) ? $_REQUEST['ref_by_client_name' . $i] : null;
                            if ($ref_by_client_ok_ind == "Y" && $ref_by_client_name != '') {
                                $this->db->query("insert into  fileinfo_details (matter_code, name , address_line_1, address_line_2, address_line_3,address_line_4,city,pin_code,state_name,country,isd_code,std_code,
                          phone_no,mobile_no,email_id,fax_no,company_name,designation,office_tel,record_code,row_no) values('$matter_code','" . $_REQUEST['ref_by_client_name' . $i] . "', '" . $_REQUEST['ref_by_client_address_line_1' . $i] . "', '" . $_REQUEST['ref_by_client_address_line_2' . $i] . "', '" . $_REQUEST['ref_by_client_address_line_3' . $i] . "','" . $_REQUEST['ref_by_client_address_line_4' . $i] . "','" . $_REQUEST['ref_by_client_city' . $i] . "','" . $_REQUEST['ref_by_client_pin_code' . $i] . "','" . $_REQUEST['ref_by_client_state_name' . $i] . "','" . $_REQUEST['ref_by_client_country' . $i] . "','" . $_REQUEST['ref_by_client_isd_code' . $i] . "','" . $_REQUEST['ref_by_client_std_code' . $i] . "','" . $_REQUEST['ref_by_client_phone_no' . $i] . "','" . $_REQUEST['ref_by_client_mobile_no' . $i] . "','" . $_REQUEST['ref_by_client_email_id' . $i] . "','" . $_REQUEST['ref_by_client_fax_no' . $i] . "','" . $_REQUEST['ref_by_client_company_name' . $i] . "','" . $_REQUEST['ref_by_client_designation' . $i] . "','" . $_REQUEST['ref_by_client_office_tel' . $i] . "','11','$i')");
                            }
                        }
                        if ($matter_code != "") {
                            $this->db->query("delete from  fileinfo_counsels  where  matter_code='$matter_code' and record_code='12'");
                        }
                        for ($i = 1; $i <= $matter_initial_count; $i++) {
                            $matter_initial_chk = isset($_REQUEST['matter_initial_chk' . $i]) ? $_REQUEST['matter_initial_chk' . $i] : null;
                            if ($matter_initial_chk == "on") {
    
                                $this->db->query("insert into  fileinfo_counsels (matter_code, initial_code , record_code, row_no) values('$matter_code','" . $_REQUEST['matter_initial_code' . $i] . "','12', '$i')");
                            }
                        }
                        $this->db->query("delete from  fileinfo_related_matters  where  matter_code='$matter_code'");
                        for ($i = 1; $i <= $related_matter_count; $i++) {
                            $related_matter_ok_ind = isset($_REQUEST['related_matter_ok_ind' . $i]) ? $_REQUEST['related_matter_ok_ind' . $i] : null;
                            $related_matter_code = isset($_REQUEST['related_matter_code' . $i]) ? $_REQUEST['related_matter_code' . $i] : null;
                            if ($related_matter_ok_ind == "Y" && $related_matter_code) {
                                $this->db->query("insert into  fileinfo_related_matters (matter_code, related_matter_code , row_no) values('$matter_code','" . $_REQUEST['related_matter_code' . $i] . "', '$i')");
                            }
                        }
                        $this->db->query("delete from  fileinfo_original_record  where  matter_code='$matter_code'");
                        for ($i = 1; $i <= $matter_org_count; $i++) {
                            $matter_org_ok_ind = isset($_REQUEST['matter_org_ok_ind' . $i]) ? $_REQUEST['matter_org_ok_ind' . $i] : null;
                            $matter_org_record_desc = isset($_REQUEST['matter_org_record_desc' . $i]) ? $_REQUEST['matter_org_record_desc' . $i] : null;
                            $matter_org_receivedon = isset($_REQUEST['matter_org_receivedon' . $i]) ? $_REQUEST['matter_org_receivedon' . $i] : '';
                            if($matter_org_receivedon!=''){$matter_org_receivedon=date('Y-m-d',strtotime($matter_org_receivedon));}else{ $matter_org_receivedon=''; }
                            $matter_org_returnon = isset($_REQUEST['matter_org_returnon' . $i]) ? $_REQUEST['matter_org_returnon' . $i] : '';
                            if($matter_org_returnon!=''){$matter_org_returnon=date('Y-m-d',strtotime($matter_org_returnon));}else{ $matter_org_returnon=''; }
                            if ($matter_org_ok_ind == "Y" && $matter_org_record_desc) {
                                $this->db->query("insert into  fileinfo_original_record (matter_code, record_desc, remarks, file_location, receive_date, return_date , row_no) values('$matter_code','" . $_REQUEST['matter_org_record_desc' . $i] . "', '" . $_REQUEST['matter_org_remarks'. $i] . "', '" . $_REQUEST['matter_org_filelocation' .$i] . "', '$matter_org_receivedon', '$matter_org_returnon','$i')");
                            }
                        }
                        $this->db->query("delete from  fileinfo_other_cases  where  matter_code='$matter_code'");
                        for ($i = 1; $i <= $case_no_count; $i++) {
                            $other_case_no = isset($_REQUEST['other_case_no' . $i]) ? $_REQUEST['other_case_no' . $i] : null;
                            if ($other_case_no != "") {
                                $this->db->query("insert into  fileinfo_other_cases (matter_code, case_no, subject_desc, row_no) values('$matter_code','" . $_REQUEST['other_case_no' . $i] . "', '" . $_REQUEST['other_subject' . $i] . "','$i')");
                            }
                        }
                        $this->db->query("delete from  fileinfo_case_details  where  matter_code='$matter_code'");
                        $this->db->query("insert into  fileinfo_case_details (matter_code, case_details) values('$matter_code','$case_details')");
                        $this->db->query("delete from  fileinfo_cheques  where  matter_code='$matter_code'");
                        $cheques_count = isset($_REQUEST['cheques_count']) ? $_REQUEST['cheques_count'] : null;
                        for ($i = 1; $i <= $cheques_count; $i++) {
                            $matter_instr_amt = isset($_REQUEST['matter_instr_amt' . $i]) ? $_REQUEST['matter_instr_amt' . $i] : null;
                            $matter_instrument_dt = isset($_REQUEST['matter_instrument_dt' . $i]) ? $_REQUEST['matter_instrument_dt' . $i] : '';
                             if($matter_instrument_dt!=''){$matter_instrument_dt=date('Y-m-d',strtotime($matter_instrument_dt));}else{ $matter_instrument_dt=''; }
                            if ($matter_instr_amt != "") {
                                $this->db->query("insert into  fileinfo_cheques (matter_code, instrument_no, instrument_dt, bank_name, instr_amt) values('$matter_code','" . $_REQUEST['matter_instrument_no' . $i] . "', '$matter_instrument_dt','" . $_REQUEST['matter_bank_name'. $i] . "','" . $_REQUEST['matter_instr_amt'. $i] . "')");
                            }
                        }
                        session()->setFlashdata('message', 'Records Updated Successfully !!');
                        return redirect()->to($data['requested_url']);
                        break;
            }
        } else {
            switch ($option) {
                case 'Add':
                    $sql = "select code_desc,code_code from code_master where type_code = '024' order by code_desc";
                    $sql1 = "select code_code,code_desc from code_master where type_code = '001' order by code_desc";
                    $sql2 = "select code_code,code_desc from code_master where type_code = '006' order by code_desc";
                    $sql3 = "select code_code,code_desc from code_master where type_code = '004' order by code_desc";
                    $sql4 = "select code_code,code_desc from code_master where type_code = '007' order by code_desc";
                    $sql55 = "select status_desc, status_code FROM status_master where table_name = 'fileinfo_header' and status_code = 'A'";
                    $associate_stmtal = "select * from associate_master where status_code = 'Active'";
                    $lastId = "select max(matter_code) as lastIdMax from fileinfo_header";
                    $initial_stmt = "select * FROM `initial_master` WHERE `status_code`='Active'";
                    break;

                case 'Edit':
                    $sql = "select code_desc,code_code from code_master where type_code = '024' order by code_desc";
                    $sql1 = "select code_code,code_desc from code_master where type_code = '001' order by code_desc";
                    $sql2 = "select code_code,code_desc from code_master where type_code = '006' order by code_desc";
                    $sql3 = "select code_code,code_desc from code_master where type_code = '004' order by code_desc";
                    $sql4 = "select code_code,code_desc from code_master where type_code = '007' order by code_desc";
                    $sele_stmt = "select * from fileinfo_header where matter_code ='$code_code'";
                    $sql_data = $this->db->query($sele_stmt)->getResultArray()[0];
                    $matter_type_code = $sql_data['matter_type_code'];
                    $matter_sub_type_code = $sql_data['matter_sub_type_code'];
                    $matter_sub_sub_type_code = $sql_data['matter_sub_sub_type_code'];
                    $corrp_addr_code = $sql_data['corrp_addr_code'];
                    $corrp_attn_code = $sql_data['corrp_attn_code'];
                    $new_matter = $sql_data['new_matter'];
                    if ($new_matter != '') {
                        $newMatter = "select status_desc new_matter FROM status_master where table_name = 'fileinfo_header' and status_code = '" . $new_matter . "'";
                        $sql_data2 = $this->db->query($newMatter)->getResultArray()[0];
                        $new_matter = $sql_data2['new_matter'];
                    }
                    $status = "select status_desc FROM status_master where table_name = 'fileinfo_header' and status_code = '" . $sql_data['status_code'] . "'";
                    $initial = "select initial_name from initial_master where initial_code = '" . $sql_data['initial_code'] . "'";
                    $clientCode = "select client_name,client_group_code from client_master where client_code = '" . $sql_data['client_code'] . "'";
                    $sql_data2 = $this->db->query($clientCode)->getResultArray()[0];
                    $client_group_code = $sql_data2['client_group_code'];
                    $clientGroup = "select code_desc from code_master where code_code = '" . $client_group_code . "' and type_code = '022'";
                    if ($corrp_addr_code != '' && $corrp_addr_code != 0) {
                        $tot_addr = "select *,concat(address_line_1,' ', address_line_2,' ', address_line_3,' ', address_line_4,' ', city,' ', pin_code ) AS `add` FROM client_address  where address_code = '" . $corrp_addr_code . "'";
                        $sql_data2 = $this->db->query($tot_addr)->getResultArray()[0];
                        $address_line_1 = $sql_data2['address_line_1'];
                        $address_line_2 = $sql_data2['address_line_2'];
                        $address_line_3 = $sql_data2['address_line_3'];
                        $address_line_4 = $sql_data2['address_line_4'];
                    } else {
                        $address_line_1 = '';
                        $address_line_2 = '';
                        $address_line_3 = '';
                        $address_line_4 = '';
                    }
                    if ($corrp_addr_code != '' && $corrp_addr_code != 0) {
                        $cty = "select a.*, b.state_code, b.state_name,b.gst_zone_code from client_address a, state_master b where a.state_code = b.state_code and a.address_code = '" . $sql_data['corrp_addr_code'] . "'";
                        $sql_data2 = $this->db->query($cty)->getResultArray();
                        if (!empty($sql_data2)) 
                        {
                            $sql_data2 = $sql_data2[0];
                        } 
                        else 
                        {
                            // Handle the case where no results were found
                            $sql_data2 = []; // or any default value or action you want to take
                        }
                       // echo'<pre>'; print_r($sql_data3);die;
                       if($sql_data2!=null)
                       {
                        $city = $sql_data2['city'];
                        $pin_code = $sql_data2['pin_code'];
                        $state_name = $sql_data2['state_name'];
                        $std_code = $sql_data2['std_code'];
                        $country = $sql_data2['country'];
                        $isd_code = $sql_data2['isd_code'];
                        $phone_no = $sql_data2['phone_no'];
                        $fax_no = $sql_data2['fax_no'];
                        $mobile_no = $sql_data2['mobile_no'];
                        $email_id = stripslashes(strtolower($sql_data2['email_id']));
                        $client_gst = stripslashes(strtoupper($sql_data2['client_gst']));
                       }
                       else
                       {
                        $city = '';
                        $pin_code = '';
                        $state_name ='';
                        $std_code = '';
                        $country = '';
                        $isd_code = '';
                        $phone_no ='';
                        $fax_no ='';
                        $mobile_no ='';
                        $email_id = '';
                        $client_gst = '';
                       }
                    }
                    if ($corrp_attn_code != '' && $corrp_attn_code != 0) {
                        $attn = "select * from client_attention where attention_code = '" . $sql_data['corrp_attn_code'] . "'";
                        $sql_data2 = $this->db->query($attn)->getResultArray()[0];
                        $attention_name = $sql_data2['attention_name'];
                        $designation = $sql_data2['designation'];
                        $sex = $sql_data2['sex'];
                        $attn_phone_no = $sql_data2['phone_no'];
                        $attn_fax_no = $sql_data2['fax_no'];
                        $attn_mobile_no = $sql_data2['mobile_no'];
                        $attn_email_id = $sql_data2['email_id'];
                    }
                    if ($matter_sub_sub_type_code != '' and $matter_sub_sub_type_code != 0) {
                        $my_sql1 = "select a.matter_sub_sub_type_desc, b.matter_sub_type_desc, c.matter_type_desc
		              from matter_sub_sub_type a, matter_sub_type b, matter_type c
					 where a.matter_sub_sub_type_code = '$matter_sub_sub_type_code'
					   and a.matter_sub_type_code     = b.matter_sub_type_code
					   and a.matter_type_code         = c.matter_type_code ";
                        $sql_data2 = $this->db->query($my_sql1)->getResultArray()[0];
                        $matter_type_desc = $sql_data2['matter_type_desc'];
                        $matter_sub_type_desc = $sql_data2['matter_sub_type_desc'];
                        $matter_sub_sub_type_desc = $sql_data2['matter_sub_sub_type_desc'];
                    } else if ($matter_sub_type_code != '' and $matter_sub_type_code != 0) {
                        $my_sql1 = "select a.matter_sub_type_desc, b.matter_type_desc
		              from matter_sub_type a, matter_type b
					 where a.matter_sub_type_code = '$matter_sub_type_code'
					   and a.matter_type_code     = b.matter_type_code ";
                        $sql_data2 = $this->db->query($my_sql1)->getResultArray()[0];
                        $matter_type_desc = $sql_data2['matter_type_desc'];
                        $matter_sub_type_desc = $sql_data2['matter_sub_type_desc'];
                        $matter_sub_sub_type_desc = '';
                    } else if ($matter_type_code != '' and $matter_type_code != 0) {
                        $my_sql1 = "select a.matter_type_desc
		              from matter_type a
					 where a.matter_type_code = '$matter_type_code' ";
                        $sql_data2 = $this->db->query($my_sql1)->getResultArray()[0];
                        $matter_type_desc = $sql_data2['matter_type_desc'];
                        $matter_sub_type_desc = '';
                        $matter_sub_sub_type_desc = '';
                    }
                    $other_side_stmt = "select * from fileinfo_details where record_code = '1' and matter_code ='$code_code'";
                    $counsel_other_side_stmt = "select * from fileinfo_details where record_code = '2' and matter_code = '$code_code'";
                    $advisor_other_side_stmt = "select * from fileinfo_details where record_code = '3' and matter_code = '$code_code'";
                    $adv_on_rec_other_side_stmt = "select * from fileinfo_details where record_code = '4' and matter_code = '$code_code'";
                    $int_party_other_side_stmt = "select * from fileinfo_details where record_code = '5' and matter_code = '$code_code'";
                    $advisor_client_stmt = "select * from fileinfo_details where record_code = '6' and matter_code = '$code_code'";
                    //Counsel(c)
                    $associate_stmt = "select * from  fileinfo_counsels where matter_code = '$code_code' and record_code=7";
                    $associate_stmtal = "select * from associate_master where status_code = 'Active'  and associate_type='001'";
                    $adv_on_rec_client_stmt = "select * from fileinfo_details where record_code = '8' and matter_code = '$code_code'";
                    $int_party_client_stmt = "select * from fileinfo_details where record_code = '9' and matter_code = '$code_code'";
                    $repr_by_client_stmt = "select * from fileinfo_details where record_code = '10' and matter_code = '$code_code'";
                    $ref_by_stmt = "select * from fileinfo_details where record_code = '11' and matter_code = '$code_code'";
                    $initial_stmt = "select a.*, b.initial_code fileinfo_initial_code from initial_master a left join fileinfo_counsels b ON a.initial_code = b.initial_code and b.record_code  = '12' and b.matter_code  = '$code_code' where a.status_code  = 'Active'";
                    $related_matters_stmt = "select a.related_matter_code,CONCAT(b.matter_desc1,' ',b.matter_desc2) as matterdesc from fileinfo_related_matters a, fileinfo_header b where a.matter_code = '$code_code' and a.related_matter_code = b.matter_code";
                    $original_record_stmt = "select * from fileinfo_original_record where matter_code = '$code_code'";
                    $other_cases_stmt = "select * from fileinfo_other_cases where matter_code = '$code_code'";
                    $case_details_stmt = "select case_details from fileinfo_case_details where matter_code = '$code_code'";
                    $matter_cheques_stmt = "select * from fileinfo_cheques where matter_code = '$code_code'";
                    break;
                    case 'View':
                        $sql = "select code_desc,code_code from code_master where type_code = '024' order by code_desc";
                        $sql1 = "select code_code,code_desc from code_master where type_code = '001' order by code_desc";
                        $sql2 = "select code_code,code_desc from code_master where type_code = '006' order by code_desc";
                        $sql3 = "select code_code,code_desc from code_master where type_code = '004' order by code_desc";
                        $sql4 = "select code_code,code_desc from code_master where type_code = '007' order by code_desc";
                        $sele_stmt = "select * from fileinfo_header where matter_code ='$code_code'";
                        $sql_data = $this->db->query($sele_stmt)->getResultArray()[0];
                        $matter_type_code = $sql_data['matter_type_code'];
                        $matter_sub_type_code = $sql_data['matter_sub_type_code'];
                        $matter_sub_sub_type_code = $sql_data['matter_sub_sub_type_code'];
                        $corrp_addr_code = $sql_data['corrp_addr_code'];
                        $corrp_attn_code = $sql_data['corrp_attn_code'];
                        $new_matter = $sql_data['new_matter'];
                        if ($new_matter != '') {
                            $newMatter = "select status_desc new_matter FROM status_master where table_name = 'fileinfo_header' and status_code = '" . $new_matter . "'";
                            $sql_data2 = $this->db->query($newMatter)->getResultArray()[0];
                            $new_matter = $sql_data2['new_matter'];
                        }
                        $status = "select status_desc FROM status_master where table_name = 'fileinfo_header' and status_code = '" . $sql_data['status_code'] . "'";
                        $initial = "select initial_name from initial_master where initial_code = '" . $sql_data['initial_code'] . "'";
                        $clientCode = "select client_name,client_group_code from client_master where client_code = '" . $sql_data['client_code'] . "'";
                        $sql_data2 = $this->db->query($clientCode)->getResultArray()[0];
                        $client_group_code = $sql_data2['client_group_code'];
                        $clientGroup = "select code_desc from code_master where code_code = '" . $client_group_code . "' and type_code = '022'";
                        if ($corrp_addr_code != '' && $corrp_addr_code != 0) {
                            $tot_addr = "select *,concat(address_line_1,' ', address_line_2,' ', address_line_3,' ', address_line_4,' ', city,' ', pin_code ) AS `add` FROM client_address  where address_code = '" . $corrp_addr_code . "'";
                            $sql_data2 = $this->db->query($tot_addr)->getResultArray()[0];
                            $address_line_1 = $sql_data2['address_line_1'];
                            $address_line_2 = $sql_data2['address_line_2'];
                            $address_line_3 = $sql_data2['address_line_3'];
                            $address_line_4 = $sql_data2['address_line_4'];
                        } else {
                            $address_line_1 = '';
                            $address_line_2 = '';
                            $address_line_3 = '';
                            $address_line_4 = '';
                        }
                        if ($corrp_addr_code != '' && $corrp_addr_code != 0) {
                            $cty = "select a.*, b.state_code, b.state_name,b.gst_zone_code from client_address a, state_master b where a.state_code = b.state_code and a.address_code = '" . $sql_data['corrp_addr_code'] . "'";
                          
                            $sql_data3 = $this->db->query($cty)->getResultArray();
                            if (!empty($sql_data3)) 
                            {
                                $sql_data3 = $sql_data3[0];
                            } 
                            else 
                            {
                                // Handle the case where no results were found
                                $sql_data3 = []; // or any default value or action you want to take
                            }
                           // echo'<pre>'; print_r($sql_data3);die;
                           if($sql_data3!=null)
                           {
                            $city = $sql_data3['city'];
                            $pin_code = $sql_data3['pin_code'];
                            $state_name = $sql_data3['state_name'];
                            $std_code = $sql_data3['std_code'];
                            $country = $sql_data3['country'];
                            $isd_code = $sql_data3['isd_code'];
                            $phone_no = $sql_data3['phone_no'];
                            $fax_no = $sql_data3['fax_no'];
                            $mobile_no = $sql_data3['mobile_no'];
                            $email_id = stripslashes(strtolower($sql_data3['email_id']));
                            $client_gst = stripslashes(strtoupper($sql_data3['client_gst']));
                           }
                           else
                           {
                            $city = '';
                            $pin_code = '';
                            $state_name ='';
                            $std_code = '';
                            $country = '';
                            $isd_code = '';
                            $phone_no ='';
                            $fax_no ='';
                            $mobile_no ='';
                            $email_id = '';
                            $client_gst = '';
                           }
                        }
                        if ($corrp_attn_code != '' && $corrp_attn_code != 0) {
                            $attn = "select * from client_attention where attention_code = '" . $sql_data['corrp_attn_code'] . "'";
                            $sql_data2 = $this->db->query($attn)->getResultArray()[0];
                            $attention_name = $sql_data2['attention_name'];
                            $designation = $sql_data2['designation'];
                            $sex = $sql_data2['sex'];
                            $attn_phone_no = $sql_data2['phone_no'];
                            $attn_fax_no = $sql_data2['fax_no'];
                            $attn_mobile_no = $sql_data2['mobile_no'];
                            $attn_email_id = $sql_data2['email_id'];
                        }
                        if ($matter_sub_sub_type_code != '' and $matter_sub_sub_type_code != 0) {
                            $my_sql1 = "select a.matter_sub_sub_type_desc, b.matter_sub_type_desc, c.matter_type_desc
                          from matter_sub_sub_type a, matter_sub_type b, matter_type c
                         where a.matter_sub_sub_type_code = '$matter_sub_sub_type_code'
                           and a.matter_sub_type_code     = b.matter_sub_type_code
                           and a.matter_type_code         = c.matter_type_code ";
                            $sql_data2 = $this->db->query($my_sql1)->getResultArray()[0];
                            $matter_type_desc = $sql_data2['matter_type_desc'];
                            $matter_sub_type_desc = $sql_data2['matter_sub_type_desc'];
                            $matter_sub_sub_type_desc = $sql_data2['matter_sub_sub_type_desc'];
                        } else if ($matter_sub_type_code != '' and $matter_sub_type_code != 0) {
                            $my_sql1 = "select a.matter_sub_type_desc, b.matter_type_desc
                          from matter_sub_type a, matter_type b
                         where a.matter_sub_type_code = '$matter_sub_type_code'
                           and a.matter_type_code     = b.matter_type_code ";
                            $sql_data2 = $this->db->query($my_sql1)->getResultArray()[0];
                            $matter_type_desc = $sql_data2['matter_type_desc'];
                            $matter_sub_type_desc = $sql_data2['matter_sub_type_desc'];
                            $matter_sub_sub_type_desc = '';
                        } else if ($matter_type_code != '' and $matter_type_code != 0) {
                            $my_sql1 = "select a.matter_type_desc
                          from matter_type a
                         where a.matter_type_code = '$matter_type_code' ";
                            $sql_data2 = $this->db->query($my_sql1)->getResultArray()[0];
                            $matter_type_desc = $sql_data2['matter_type_desc'];
                            $matter_sub_type_desc = '';
                            $matter_sub_sub_type_desc = '';
                        }
                        $other_side_stmt = "select * from fileinfo_details where record_code = '1' and matter_code ='$code_code'";
                        $counsel_other_side_stmt = "select * from fileinfo_details where record_code = '2' and matter_code = '$code_code'";
                        $advisor_other_side_stmt = "select * from fileinfo_details where record_code = '3' and matter_code = '$code_code'";
                        $adv_on_rec_other_side_stmt = "select * from fileinfo_details where record_code = '4' and matter_code = '$code_code'";
                        $int_party_other_side_stmt = "select * from fileinfo_details where record_code = '5' and matter_code = '$code_code'";
                        $advisor_client_stmt = "select * from fileinfo_details where record_code = '6' and matter_code = '$code_code'";
                        //Counsel(c)
                        $associate_stmt = "select * from  fileinfo_counsels where matter_code = '$code_code' and record_code=7";
                        $associate_stmtal = "select * from associate_master where status_code = 'Active'  and associate_type='001'";
                        $adv_on_rec_client_stmt = "select * from fileinfo_details where record_code = '8' and matter_code = '$code_code'";
                        $int_party_client_stmt = "select * from fileinfo_details where record_code = '9' and matter_code = '$code_code'";
                        $repr_by_client_stmt = "select * from fileinfo_details where record_code = '10' and matter_code = '$code_code'";
                        $ref_by_stmt = "select * from fileinfo_details where record_code = '11' and matter_code = '$code_code'";
                        $initial_stmt = "select a.*, b.initial_code fileinfo_initial_code from initial_master a left join fileinfo_counsels b ON a.initial_code = b.initial_code and b.record_code  = '12' and b.matter_code  = '$code_code' where a.status_code  = 'Active'";
                        $related_matters_stmt = "select a.related_matter_code,CONCAT(b.matter_desc1,' ',b.matter_desc2) as matterdesc from fileinfo_related_matters a, fileinfo_header b where a.matter_code = '$code_code' and a.related_matter_code = b.matter_code";
                        $original_record_stmt = "select * from fileinfo_original_record where matter_code = '$code_code'";
                        $other_cases_stmt = "select * from fileinfo_other_cases where matter_code = '$code_code'";
                        $case_details_stmt = "select case_details from fileinfo_case_details where matter_code = '$code_code'";
                        $matter_cheques_stmt = "select * from fileinfo_cheques where matter_code = '$code_code'";
                        break;
            }
        }
       // echo '<pre>'; print_r($option);die;
        if ($option == 'Edit' || $option == 'View') {
            $data = $this->db->query($sql)->getResultArray();
            $data1 = $this->db->query($sql1)->getResultArray();
            $data2 = $this->db->query($sql2)->getResultArray();
            $data3 = $this->db->query($sql3)->getResultArray();
            $data4 = $this->db->query($sql4)->getResultArray();
            $data5 = $this->db->query($sele_stmt)->getResultArray();
            if (!empty($data5)) {$data5 = $data5[0];} else {$data5= [];}
            $data6 = $this->db->query($other_side_stmt)->getResultArray();
            $data7 = $this->db->query($counsel_other_side_stmt)->getResultArray();
            $data8 = $this->db->query($advisor_other_side_stmt)->getResultArray();
            $data9 = $this->db->query($adv_on_rec_other_side_stmt)->getResultArray();
            $data10 = $this->db->query($int_party_other_side_stmt)->getResultArray();
            $data11 = $this->db->query($advisor_client_stmt)->getResultArray();
            $data12 = $this->db->query($associate_stmt)->getResultArray();
            $data13 = $this->db->query($adv_on_rec_client_stmt)->getResultArray();
            $data14 = $this->db->query($int_party_client_stmt)->getResultArray();
            $data15 = $this->db->query($repr_by_client_stmt)->getResultArray();
            $data16 = $this->db->query($ref_by_stmt)->getResultArray();
            $data17 = $this->db->query($initial_stmt)->getResultArray();
            $data18 = $this->db->query($related_matters_stmt)->getResultArray();
            $data19 = $this->db->query($original_record_stmt)->getResultArray();
            $data20 = $this->db->query($other_cases_stmt)->getResultArray();

            $data21 = $this->db->query($case_details_stmt)->getResultArray();
            if (!empty($data21)) {$data21 = $data21[0];} else {$data22= [];}
            $data22 = $this->db->query($matter_cheques_stmt)->getResultArray();
            $data23 = $this->db->query($status)->getResultArray();
            if (!empty($data23)) {$data23 = $data23[0];} else {$data23= [];}
            $data24 = $this->db->query($clientCode)->getResultArray();
            if (!empty($data24)) {$data24 = $data24[0];} else {$data24= [];}
            $data25 = $this->db->query($initial)->getResultArray();
            if (!empty($data25)) {$data25 = $data25[0];} else {$data25= [];}
            $data26 = $this->db->query($clientGroup)->getResultArray();
            if (!empty($data26)) {$data26 = $data26[0];} else {$data26= [];}
            $data27 = $this->db->query($associate_stmtal)->getResultArray();

            $count = count($data6);
            $count2 = count($data7);
            $count3 = count($data8);
            $count4 = count($data9);
            $count5 = count($data10);
            $count6 = count($data11);
            $count7 = count($data12);
            $count8 = count($data13);
            $count9 = count($data14);
            $count10 = count($data15);
            $count11 = count($data16);
            $count12 = count($data17);
            $count13 = count($data18);
            $count14 = count($data19);
            $count15 = count($data20);
            $count16 = count($data21);
            $count17 = count($data22);
            if ($data5['status_code'] == 'A') {
                $colour_s = "#0000FF";
            } else {
                $colour_s = "#FF0000";
            }
            // echo "<pre>"; print_r($data5);die;
        } else {
            $data = $this->db->query($sql)->getResultArray();
            $data1 = $this->db->query($sql1)->getResultArray();
            $data2 = $this->db->query($sql2)->getResultArray();
            
            
            $data3 = $this->db->query($sql3)->getResultArray();
            $data4 = $this->db->query($sql4)->getResultArray();
            $data55 = $this->db->query($sql55)->getResultArray()[0];
            $data5 = [];
            $data6 = [];
            $data7 = [];
            $data8 = [];
            $data9 = [];
            $data10 = [];
            $data11 = [];
            $data12 = [];
            $data13 = [];
            $data14 = [];
            $data15 = [];
            $data16 = [];
            $data17 = $this->db->query($initial_stmt)->getResultArray();
            $data18 = [];
            $data19 = [];
            $data20 = [];
            $data21 = [];
            $data22 = [];
            $data27 = $this->db->query($associate_stmtal)->getResultArray();
            $count = 0;
            $count2 = 0;
            $count3 = 0;
            $count4 = 0;
            $count5 = 0;
            $count6 = 0;
            $count7 = 0;
            $count8 = 0;
            $count9 = 0;
            $count10 = 0;
            $count11 = 0;
            $count12 = 0;
            $count13 = 0;
            $count14 = 0;
            $count15 = 0;
            $count16 = 0;
            $count17 = 0;
            $lastIdtot = $this->db->query($lastId)->getResultArray()[0];
            if ($data55['status_code'] == 'A') {
                $colour_s = "#0000FF";
            } else {
                $colour_s = "#FF0000";
            }
        }
        if ($option == 'Edit' || $option == 'View') { 
            return view("pages/Master/matter_masteraddedit", compact("url", "display_id", "menu_id", "option", "colour_s", "data", "data1", "data2", "data3", "data4", "data5", "data6", "data7", "data8", "data9", "data10", "data11", "data12", "data13", "data14", "data15", "data16", "data17", "data18", "data19", "data20", "data21", "data22", "data23", "data24", "data25", "data26", "data27",
                "matter_type_code", "matter_sub_type_code", "matter_sub_sub_type_code", "matter_type_desc", "matter_sub_type_desc", "matter_sub_sub_type_desc", "address_line_1", "address_line_2", "address_line_3", "address_line_4", "city", "pin_code", "state_name", "std_code", "country", "isd_code", "phone_no", "fax_no", "mobile_no", "email_id", "client_gst",
                "attention_name", "designation", "sex", "attn_phone_no", "attn_fax_no", "attn_mobile_no", "attn_email_id", "new_matter",
                "redk", "permdata", "count", "count2", "count3", "count4", "count5", "count6", "count7", "count8", "count9", "count10", "count11", "count12", "count13", "count14", "count15", "count16", "count17","redokadd","disview","closePage","redv"));
        } else {
            return view("pages/Master/matter_masteraddedit", compact("url", "display_id", "menu_id", "option", "colour_s", "data", "data1", "data2", "data3", "data4", "data5", "data6", "data7", "data8", "data9", "data10", "data11", "data12", "data13", "data14", "data15", "data16", "data17", "data18", "data19", "data20", "data21", "data22", "data55", "data27", "redk", "permdata", "count", "count2", "count3", "count4", "count5", "count6", "count7", "count8", "count9", "count10", "count11", "count12", "count13", "count14", "count15", "count16", "count17", "lastIdtot","redokadd","disview","closePage"));

        }

    }
    //end
//Done by Sylvester
    public function client_details_combine($option = 'list')
    {
        //edited By sylvester
        $perm = "select * from permission WHERE permission_on='0'";
        $permdata = $this->db->query($perm)->getResultArray();
        //edit end

        $sql = '';
        $sql1 = '';
        $sql2 = '';
        $sql3 = '';
        $sql4 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $client_name = isset($_REQUEST['client_name']) ? $_REQUEST['client_name'] : null;
        $uid = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
        $usrcode = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
        $client_nm = strtoupper(stripslashes($client_name));
        $first_letter = substr($client_nm, 0, 1);
        $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
        $client_group_name = isset($_REQUEST['client_group_id']) ? $_REQUEST['client_group_id'] : null;
        $credit_limit_amount = isset($_REQUEST['credit_limit_amount']) ? $_REQUEST['credit_limit_amount'] : null;
        $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
        $referred_by = isset($_REQUEST['referred_by']) ? $_REQUEST['referred_by'] : null;
        $new_client = isset($_REQUEST['new_client']) ? $_REQUEST['new_client'] : null;
        $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
        $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
        $state_code = isset($_REQUEST['state_code']) ? $_REQUEST['state_code'] : null;
        $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : null;
        $pan_no = isset($_REQUEST['pan_no']) ? $_REQUEST['pan_no'] : null;
        $isd_code = isset($_REQUEST['isd_code']) ? $_REQUEST['isd_code'] : null;
        $std_code = isset($_REQUEST['std_code']) ? $_REQUEST['std_code'] : null;
        $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
        $fax_no = isset($_REQUEST['fax_no']) ? $_REQUEST['fax_no'] : null;
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
        $web_id = isset($_REQUEST['web_id']) ? $_REQUEST['web_id'] : null;
        $client_gst = isset($_REQUEST['client_gst']) ? $_REQUEST['client_gst'] : null;
        $prepared_on = isset($_REQUEST['prepared_on']) ? $_REQUEST['prepared_on'] : null;
        $prepared_by = isset($_REQUEST['prepared_by']) ? $_REQUEST['prepared_by'] : null;
        $updated_on = isset($_REQUEST['updated_on']) ? $_REQUEST['updated_on'] : null;
        $updated_by = isset($_REQUEST['updated_by']) ? $_REQUEST['updated_by'] : null;
        $address_count = isset($_REQUEST['address_count']) ? $_REQUEST['address_count'] : null;
        $attentionCount = isset($_REQUEST['attentionCount']) ? $_REQUEST['attentionCount'] : null;
        $prevattentionCount = isset($_REQUEST['prevattentionCount']) ? $_REQUEST['prevattentionCount'] : null;
        $preaddress_count = isset($_REQUEST['preaddress_count']) ? $_REQUEST['preaddress_count'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        if ($option == 'Add') {$redk = '';
            $redv = '';
            $disv = '';
            $disb = '';
            $redve = '';
            $redokadd = '';
            $disview = '';
            $redLetter = 'disabled';}
        if ($option == 'Edit') {$redk = 'readonly';
            $redv = '';
            $disv = 'disabled';
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into client_master (client_code, client_group_code, client_name, credit_limit_amount,mobile_no,referred_by,new_client,prepared_on,prepared_by) values ('$first_letter$client_code', '$client_group_name', '$client_name', '$credit_limit_amount','$mobile_no','$referred_by','$new_client','$prepared_on','$prepared_by')");
                   for($i=1;$i<=$address_count;$i++){
                    $this->db->query("insert into  client_address (client_code , address_line_1, city, pin_code,state_code,country,pan_no,isd_code,std_code,phone_no,fax_no,email_id,web_id,client_gst,prepared_on,prepared_by) values ('$first_letter$client_code ', '".$_REQUEST['address_line_1'. $i]."', '".$_REQUEST['city'. $i]."', '".$_REQUEST['pin_code'. $i]."','".$_REQUEST['state_code'. $i]."','".$_REQUEST['country'. $i]."','".$_REQUEST['pan_no'. $i]."','".$_REQUEST['isd_code'. $i]."','".$_REQUEST['std_code'. $i]."','".$_REQUEST['phone_no'. $i]."','".$_REQUEST['fax_no'. $i]."','".$_REQUEST['email_id'. $i]."','".$_REQUEST['web_id'. $i]."','".$_REQUEST['client_gst'. $i]."','$prepared_on','$prepared_by')");
                   }
                   for($i=1;$i<=$attentionCount;$i++){
                    $address_qry = $this->db->query("select last_insert_id() as lastid from client_address ")->getRowArray();
                    $lastId=$address_qry['lastid'];
                    $this->db->query("insert into  client_attention (client_code, address_code, attention_name, designation,short_name,sex,title,phone_no,fax_no,mobile_no,email_id,email_id_other,prepared_on,prepared_by) values ('$first_letter$client_code ', '".$lastId."','".$_REQUEST['attention_name'.$i]."','".$_REQUEST['designation'.$i]."','".$_REQUEST['short_name'.$i]."','".$_REQUEST['sex'.$i]."','".$_REQUEST['title'.$i]."','".$_REQUEST['phone_no'.$i]."','".$_REQUEST['fax_no'.$i]."','".$_REQUEST['mobile_no'.$i]."','".$_REQUEST['email_id'.$i]."','".$_REQUEST['email_id_other'.$i]."','$prepared_on','$prepared_by')");
                   }
                    session()->setFlashdata('message', 'Records Added Successfully !!');
                    return redirect()->to($url);
                    break;

                case 'Edit':
                    $this->db->query("update client_master set client_group_code='$client_group_name', client_name='$client_name', credit_limit_amount='$credit_limit_amount',mobile_no='$mobile_no',referred_by='$referred_by',new_client='$new_client',updated_on='$updated_on',updated_by='$updated_by' where client_code='$uid'");
                    if($preaddress_count==$address_count){
                    for($i=1;$i<=$address_count;$i++){
                    $this->db->query("update client_address set address_line_1='".$_REQUEST['address_line_1'. $i]."', city='".$_REQUEST['city'. $i]."', pin_code='".$_REQUEST['pin_code'. $i]."',state_code='".$_REQUEST['state_code'. $i]."',country='".$_REQUEST['country'. $i]."',pan_no='".$_REQUEST['pan_no'. $i]."',isd_code='".$_REQUEST['isd_code'. $i]."',std_code='".$_REQUEST['std_code'. $i]."',phone_no='".$_REQUEST['phone_no'. $i]."',fax_no='".$_REQUEST['fax_no'. $i]."',email_id='".$_REQUEST['email_id'. $i]."',web_id='".$_REQUEST['web_id'. $i]."',client_gst='".$_REQUEST['client_gst'. $i]."',updated_on='$updated_on',updated_by='$updated_by' where address_code='".$_REQUEST['address_code'. $i]."'");
                    }
                    }
                    else if($preaddress_count<$address_count)
                    {
                        for($i=$preaddress_count+1;$i<=$address_count;$i++){
                            $this->db->query("insert into  client_address (client_code , address_line_1, city, pin_code,state_code,country,pan_no,isd_code,std_code,phone_no,fax_no,email_id,web_id,client_gst,prepared_on,prepared_by) values ('$uid ', '".$_REQUEST['address_line_1'. $i]."', '".$_REQUEST['city'. $i]."', '".$_REQUEST['pin_code'. $i]."','".$_REQUEST['state_code'. $i]."','".$_REQUEST['country'. $i]."','".$_REQUEST['pan_no'. $i]."','".$_REQUEST['isd_code'. $i]."','".$_REQUEST['std_code'. $i]."','".$_REQUEST['phone_no'. $i]."','".$_REQUEST['fax_no'. $i]."','".$_REQUEST['email_id'. $i]."','".$_REQUEST['web_id'. $i]."','".$_REQUEST['client_gst'. $i]."','$prepared_on','$prepared_by')");
                           }
                    }
                    if($prevattentionCount==$attentionCount){
                    for($i=1;$i<=$attentionCount;$i++){
                        $this->db->query("update  client_attention set address_code='".$_REQUEST['address_code1']."', attention_name='".$_REQUEST['attention_name'.$i]."', designation='".$_REQUEST['designation'.$i]."',short_name='".$_REQUEST['short_name'.$i]."',sex='".$_REQUEST['sex'.$i]."',title='".$_REQUEST['title'.$i]."',phone_no='".$_REQUEST['phone_no'.$i]."',fax_no='".$_REQUEST['fax_no'.$i]."',mobile_no='".$_REQUEST['mobile_no'.$i]."',email_id='".$_REQUEST['email_id'.$i]."',email_id_other='".$_REQUEST['email_id_other'.$i]."',updated_on='$updated_on',updated_by='$updated_by' where attention_code='".$_REQUEST['attention_code'. $i]."'");
                       }
                    }
                    else if($prevattentionCount<$attentionCount)
                    {
                        for($i=$prevattentionCount+1;$i<=$attentionCount;$i++){
                        $address_qry = $this->db->query("select last_insert_id() as lastid from client_address ")->getRowArray();
                        $lastId=$address_qry['lastid'];
                        $this->db->query("insert into  client_attention (client_code, address_code, attention_name, designation,short_name,sex,title,phone_no,fax_no,mobile_no,email_id,email_id_other,prepared_on,prepared_by) values ('$uid', '".$lastId."','".$_REQUEST['attention_name'.$i]."','".$_REQUEST['designation'.$i]."','".$_REQUEST['short_name'.$i]."','".$_REQUEST['sex'.$i]."','".$_REQUEST['title'.$i]."','".$_REQUEST['phone_no'.$i]."','".$_REQUEST['fax_no'.$i]."','".$_REQUEST['mobile_no'.$i]."','".$_REQUEST['email_id'.$i]."','".$_REQUEST['email_id_other'.$i]."','$prepared_on','$prepared_by')");
                        }
                    }
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from client_master where client_code='$uid'");
                    for($i=1;$i<=$address_count;$i++){
                    $this->db->query("delete from client_address where client_code='$uid'");
                    }
                    for($i=1;$i<=$attentionCount;$i++){
                        $this->db->query("delete from client_attention where client_code='$uid'");
                        }
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Add':
                        $sql = "select substring(max(client_code),2) as `maxValue` from client_master";
                        $sql1 = "select * from client_master WHERE client_code=''";
                        $sql2 = "select* from state_master";
                        break;
                    case 'Edit':
                       // $sql = "select substring(max(client_code),2) as `maxValue` from client_master";
                        $sql1 = "select * from client_master INNER JOIN code_master ON client_master.client_group_code=code_master.code_code WHERE client_code='$usrcode' AND code_master.type_code='022' AND code_master.status_code = 'Active'";
                        $sql2 = "select* from state_master";
                        $sql3 = "select * from client_address WHERE client_code='$usrcode'";
                        $sql4 = "select * from client_attention WHERE client_code='$usrcode'";
                        break;
                    case 'Delete':
                        // $sql = "select substring(max(client_code),2) as `maxValue` from client_master";
                         $sql1 = "select * from client_master INNER JOIN code_master ON client_master.client_group_code=code_master.code_code WHERE client_code='$usrcode' AND code_master.type_code='022' AND code_master.status_code = 'Active'";
                         $sql2 = "select* from state_master";
                         $sql3 = "select * from client_address WHERE client_code='$usrcode'";
                         $sql4 = "select * from client_attention WHERE client_code='$usrcode'";
                         break;
                }
                if ($option == 'Add') {
                    $data = $this->db->query($sql)->getResultArray();
                    $data1 = $this->db->query($sql1)->getResultArray();
                    $data2 = $this->db->query($sql2)->getResultArray();
                    return view("pages/Master/client_details_combine", compact("url", "display_id", "menu_id", "option", "data", "data1", "data2", "redokadd","disview", "permdata","redk"));
                }
                if ($option != 'Add') {
                    //$data = $this->db->query($sql)->getResultArray();
                    $data1 = $this->db->query($sql1)->getResultArray()[0];
                    $data2 = $this->db->query($sql2)->getResultArray();
                    $result = $this->db->query($sql3)->getResultArray();
                    $result2 = $this->db->query($sql4)->getResultArray();
                    if (empty($result)) {
                        $data3 = ['client_code' => '',
                        'address_code' => '',
                        'address_line_1' => '',
                        'address_line_2' => '',
                        'address_line_3' => '',
                        'address_line_4' => '',
                        'city' => '',
                        'pin_code' => '',
                        'pan_no' => '',
                        'state_code' => '',
                        'country' => '',
                        'isd_code' => '',
                        'std_code' => '',
                        'phone_no' => '',
                        'fax_no' => '',
                        'mobile_no' => '',
                        'email_id' => '',
                        'web_id' => '',
                        'client_gst' => '',
                        'prepared_by' => '',
                        'prepared_on' => '',
                        'updated_by' => '',
                        'updated_on' => '',];
                    } else {
                        $data3 = $this->db->query($sql3)->getResultArray();
                    }
                    return view("pages/Master/client_details_combine", compact("url", "display_id", "menu_id", "option", "data1", "data2", "data3","result2", "redokadd","disview", "permdata","redk"));
                }
            }
        }  
    }
//end
//Done By sylvester
    public function mas_matter_type($option = 'list')
    {
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $query_id = isset($_REQUEST['matter_type_code']) ? $_REQUEST['matter_type_code'] : null;
        $matter_type_count = isset($_REQUEST['matter_type_count']) ? $_REQUEST['matter_type_count'] : null;
        $org_matter_type_count = isset($_REQUEST['org_matter_type_count']) ? $_REQUEST['org_matter_type_count'] : null;
        $c = 0;
        $sql = '';
        // echo $option;die;
        if ($this->request->getMethod() == 'post') {
            switch ($option) {
                case 'Edit':
                    for ($i = 1; $i <= $org_matter_type_count; $i++) {
                        $this->db->query("update matter_type set  matter_type_desc='" . $_REQUEST['matter_type_desc' . $i] . "' where matter_type_code='" . $_REQUEST['matter_type_code' . $i] . "'");
                        if ($_REQUEST['matter_type_desc' . $i] == "") {
                            $this->db->query("delete from  matter_type where matter_type_code='" . $_REQUEST['matter_type_code' . $i] . "'");
                        }
                    }
                    for ($i = $org_matter_type_count + 1; $i <= $matter_type_count; $i++) {
                        if ($_REQUEST['matter_type_desc' . $i] != "") {
                            $this->db->query("insert into  matter_type (matter_type_desc) values ('" . $_REQUEST['matter_type_desc' . $i] . "')");
                        }
                    }
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($data['requested_url']);
                    break;
            }
        } else {

            switch ($option) {
                case 'list':
                    $sql = "select * from matter_type where matter_type_desc!='' order by matter_type_code ASC";
                    break;
            }

        }
        $data = $this->db->query($sql)->getResultArray();
        return view("pages/Master/mas_matter_type", compact("url", "option", "data"));
    }
//edit end
//Done By sylvester

    public function mas_matter_sub_type($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $matter_type_code = isset($_REQUEST['matter_type_code']) ? $_REQUEST['matter_type_code'] : null;
        $org_matter_sub_type_count = isset($_REQUEST['org_matter_sub_type_count']) ? $_REQUEST['org_matter_sub_type_count'] : null;
        $matter_sub_type_count = isset($_REQUEST['matter_sub_type_count']) ? $_REQUEST['matter_sub_type_count'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $sql = '';
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Edit':

                    for ($i = 1; $i <= $org_matter_sub_type_count; $i++) {

                        $this->db->query("update matter_sub_type set  matter_sub_type_desc='" . $_REQUEST['matter_sub_type_desc' . $i] . "' where matter_type_code='" . $matter_type_code . "' and matter_sub_type_code='" . $_REQUEST['matter_sub_type_code' . $i] . "'");
                    }
                    for ($i = $org_matter_sub_type_count + 1; $i <= $matter_sub_type_count; $i++) {

                        if ($_REQUEST['matter_sub_type_desc' . $i] != "") {
                            $this->db->query("insert into  matter_sub_type (matter_type_code,matter_sub_type_desc) values ('$matter_type_code','" . $_REQUEST['matter_sub_type_desc' . $i] . "')");
                        }
                    }
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                    
            }
        }
        if($finsub=="" || $finsub!="fsub")
        {
            switch ($option)
            {
                case 'Select':
                    $sql = "select a.matter_type_code,a.matter_type_desc from matter_type a where a.matter_type_code='$matter_type_code'";
                    $sql1 = "select b.matter_sub_type_desc,b.matter_sub_type_code from matter_type a INNER JOIN  matter_sub_type b ON a.matter_type_code=b.matter_type_code where b.matter_type_code='$matter_type_code' AND b.matter_sub_type_desc!=''";
                    break;
                case 'View':
                    $sql = "select a.matter_type_code,a.matter_type_desc from matter_type a where a.matter_type_code='$matter_type_code'";
                    $sql1 = "select b.matter_sub_type_desc,b.matter_sub_type_code from matter_type a INNER JOIN  matter_sub_type b ON a.matter_type_code=b.matter_type_code where b.matter_type_code='$matter_type_code' AND b.matter_sub_type_desc!=''";
                    break;
            }
            $data = $this->db->query($sql)->getResultArray()[0];
            $data1 = $this->db->query($sql1)->getResultArray();
            return view("pages/Master/mas_matter_sub_type", compact("url", "data", "data1", "option","redokadd","disview"));
            }
        } 
    }
// end
//Done By Sylvester
    public function mas_matter_sub_sub_type($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $matter_type_code = isset($_REQUEST['matter_type_code']) ? $_REQUEST['matter_type_code'] : null;
        $matter_sub_type_code = isset($_REQUEST['matter_sub_type_code']) ? $_REQUEST['matter_sub_type_code'] : null;
        $matter_sub_sub_type_code = isset($_REQUEST['matter_sub_sub_type_code']) ? $_REQUEST['matter_sub_sub_type_code'] : null;
        $matter_sub_sub_type_desc = isset($_REQUEST['matter_sub_sub_type_desc']) ? $_REQUEST['matter_sub_sub_type_desc'] : null;
        $org_matter_sub_sub_type_count = isset($_REQUEST['org_matter_sub_sub_type_count']) ? $_REQUEST['org_matter_sub_sub_type_count'] : null;
        $matter_sub_sub_type_count = isset($_REQUEST['matter_sub_sub_type_count']) ? $_REQUEST['matter_sub_sub_type_count'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
        if ($option == 'delete') {$redk = '';
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Edit':
                    for ($i = 1; $i <= $org_matter_sub_sub_type_count; $i++) {

                        $this->db->query("update matter_sub_sub_type set  matter_sub_sub_type_desc='" . $_REQUEST['matter_sub_sub_type_desc' . $i] . "' where matter_type_code='" . $matter_type_code . "' and matter_sub_type_code='" . $matter_sub_type_code . "' and matter_sub_sub_type_code='" . $_REQUEST['matter_sub_sub_type_code' . $i] . "'");
                    }
                    for ($i = $org_matter_sub_sub_type_count + 1; $i <= $matter_sub_sub_type_count; $i++) {

                        if ($_REQUEST['matter_sub_sub_type_desc' . $i] != "") {
                            $this->db->query("insert into  matter_sub_sub_type (matter_type_code,matter_sub_type_code,matter_sub_sub_type_desc) values ('$matter_type_code','$matter_sub_type_code','" . $_REQUEST['matter_sub_sub_type_desc' . $i] . "')");
                        }
                    }
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($data['requested_url']);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Select':
                        $sql = "select a.matter_type_code,a.matter_type_desc,b.matter_sub_type_code,b.matter_sub_type_desc from matter_type a INNER JOIN matter_sub_type b ON a.matter_type_code=b.matter_type_code where b.matter_type_code='$matter_type_code' AND b.matter_sub_type_code='$matter_sub_type_code'";
                        $sql1 = "select matter_sub_sub_type_code,matter_sub_sub_type_desc from matter_sub_sub_type where matter_type_code='$matter_type_code' AND matter_sub_type_code='$matter_sub_type_code' AND matter_sub_sub_type_desc!=''";
                        break;
                    case 'View':
                        $sql = "select a.matter_type_code,a.matter_type_desc,b.matter_sub_type_code,b.matter_sub_type_desc from matter_type a INNER JOIN matter_sub_type b ON a.matter_type_code=b.matter_type_code where b.matter_type_code='$matter_type_code' AND b.matter_sub_type_code='$matter_sub_type_code'";
                        $sql1 = "select matter_sub_sub_type_code,matter_sub_sub_type_desc from matter_sub_sub_type where matter_type_code='$matter_type_code' AND matter_sub_type_code='$matter_sub_type_code' AND matter_sub_sub_type_desc!=''";
                        break;
                }
                $data = $this->db->query($sql)->getResultArray()[0];
        $data1 = $this->db->query($sql1)->getResultArray();
        return view("pages/Master/mas_matter_sub_sub_type", compact("url", "option", "data", "data1","redokadd","disview"));
            }
        }
    }
//End
//Done By Sylvester
    public function mas_company_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $company_code = isset($_REQUEST['company_code']) ? $_REQUEST['company_code'] : null;
        $company_name = isset($_REQUEST['company_name']) ? $_REQUEST['company_name'] : null;
        $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
        $company_abbr_name = isset($_REQUEST['company_abbr_name']) ? $_REQUEST['company_abbr_name'] : null;
        $contact_person = isset($_REQUEST['contact_person']) ? $_REQUEST['contact_person'] : null;
        $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
        $fax_no = isset($_REQUEST['fax_no']) ? $_REQUEST['fax_no'] : null;
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
        $website = isset($_REQUEST['website']) ? $_REQUEST['website'] : null;
        $pan_no = isset($_REQUEST['pan_no']) ? $_REQUEST['pan_no'] : null;
        $tan_no = isset($_REQUEST['tan_no']) ? $_REQUEST['tan_no'] : null;
        $tin_no = isset($_REQUEST['tin_no']) ? $_REQUEST['tin_no'] : null;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
        $first_letter = 'C';
        $company_codein = $first_letter . $company_code;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
           // echo $option;die;
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch($option) 
                {
                case 'Add':
                    $this->db->query("insert into  company_master (company_code,company_name,address_line_1,company_abbr_name,contact_person,phone_no,fax_no,email_id,website,pan_no,tan_no,tin_no,status_code) values ('$company_codein','$company_name','$address_line_1','$company_abbr_name','$contact_person','$phone_no','$fax_no','$email_id','$website','$pan_no','$tan_no','$tin_no','$status_code')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update  company_master set company_name='$company_name',address_line_1='$address_line_1',company_abbr_name='$company_abbr_name',contact_person='$contact_person',phone_no='$phone_no',fax_no='$fax_no',email_id='$email_id',website='$website',pan_no='$pan_no',tan_no='$tan_no',tin_no='$tin_no',status_code='$status_code' WHERE company_code='$company_code' ");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from  company_master where company_code='$company_code'");
                    session()->setFlashdata('message', 'Records Delete Successfully !!');
                return redirect()->to($url);
                break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) 
                {
                    case 'Edit':
                    $sql = "select * from company_master where company_code= '$company_code'";
                    break;
                    case 'View':
                    $sql = "select * from company_master where company_code= '$company_code'";
                    break;
                    case 'Delete':
                    $sql = "select * from company_master where company_code= '$company_code'"; 
                    break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
        
                    return view("pages/Master/mas_company_master", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_company_master", compact("url", "option","redokadd","disview"));
                }
            }
        }
    }
//end
//Done By Sylvester

    public function mas_building_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $building_code = isset($_REQUEST['building_code']) ? $_REQUEST['building_code'] : null;
        $floor = isset($_REQUEST['floor']) ? $_REQUEST['floor'] : null;
        $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
        $address_line_2 = isset($_REQUEST['address_line_2']) ? $_REQUEST['address_line_2'] : null;
        $address_line_3 = isset($_REQUEST['address_line_3']) ? $_REQUEST['address_line_3'] : null;
        $address_line_4 = isset($_REQUEST['address_line_4']) ? $_REQUEST['address_line_4'] : null;
        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) 
            {
                case 'Add':
                    $this->db->query("insert into  building_master (building_code,floor,address_line_1,address_line_2,address_line_3,address_line_4,city,pin_code) values ('$building_code','$floor','$address_line_1','$address_line_2','$address_line_3','$address_line_4','$city','$pin_code')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update  building_master set floor='$floor',address_line_1='$address_line_1',address_line_2='$address_line_2',address_line_3='$address_line_3',address_line_4='$address_line_4',city='$city',pin_code='$pin_code' WHERE building_code='$building_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from  building_master WHERE building_code='$building_code'");
                    session()->setFlashdata('message', 'Records Delete Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Edit':
                        $sql = "select * from building_master where building_code='$building_code'";
                        break;
                    case 'View':
                        $sql = "select * from building_master where building_code='$building_code'";
                        break;
                    case 'Delete':
                        $sql = "select * from building_master where building_code='$building_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_building_master", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_building_master", compact("url", "option","redokadd","disview"));
                }
            }
        } 
    }
//end
//Done By Sylvester
    public function mas_daybook_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $daybook_code = isset($_REQUEST['daybook_code']) ? $_REQUEST['daybook_code'] : null;
        $daybook_desc = isset($_REQUEST['daybook_desc']) ? $_REQUEST['daybook_desc'] : null;
        $daybook_type = isset($_REQUEST['daybook_type']) ? $_REQUEST['daybook_type'] : null;
        $branch_code = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : null;
        $bank_account_no = isset($_REQUEST['bank_account_no']) ? $_REQUEST['bank_account_no'] : null;
        $main_ac_code = isset($_REQUEST['main_ac_code']) ? $_REQUEST['main_ac_code'] : null;
        $sub_ac_code = isset($_REQUEST['sub_ac_code']) ? $_REQUEST['sub_ac_code'] : null;
        $overdraft_amount = isset($_REQUEST['overdraft_amount']) ? $_REQUEST['overdraft_amount'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  daybook_master (daybook_code,daybook_desc,daybook_type,branch_code,bank_account_no,main_ac_code,sub_ac_code,overdraft_amount) values ('$daybook_code','$daybook_desc','$daybook_type','$branch_code','$bank_account_no','$main_ac_code','$sub_ac_code','$overdraft_amount')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update  daybook_master set daybook_code='$daybook_code',daybook_desc='$daybook_desc',daybook_type='$daybook_type',branch_code='$branch_code',bank_account_no='$bank_account_no',main_ac_code='$main_ac_code',sub_ac_code='$sub_ac_code',overdraft_amount='$overdraft_amount' WHERE daybook_code='$daybook_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from  daybook_master  WHERE daybook_code='$daybook_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Add':
                        $sql1 = "select * from branch_master order by branch_name";
                        $sql2 = "select max(daybook_code) as `maxValue` from daybook_master";
                        break;
                    case 'Edit':
                        $sql = "select *,b.main_ac_desc,b.main_ac_code,b.sub_ac_ind from daybook_master a,account_master b  where a.main_ac_code=b.main_ac_code AND a.daybook_code='$daybook_code' AND b.status_code='Active' ";
                        $sql1 = "select * from branch_master order by branch_name";
                        break;
                    case 'View':
                        $sql = "select *,b.main_ac_desc,b.main_ac_code,b.sub_ac_ind from daybook_master a,account_master b  where a.main_ac_code=b.main_ac_code AND a.daybook_code='$daybook_code' AND b.status_code='Active' ";
                        $sql1 = "select * from branch_master order by branch_name";
                        break;
                    case 'Delete':
                        $sql = "select *,b.main_ac_desc,b.main_ac_code,b.sub_ac_ind from daybook_master a,account_master b  where a.main_ac_code=b.main_ac_code AND a.daybook_code='$daybook_code' AND b.status_code='Active' ";
                        $sql1 = "select * from branch_master order by branch_name";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    $data1 = $this->db->query($sql1)->getResultArray();
                    return view("pages/Master/mas_daybook_master", compact("url", "option", "data", "data1","redokadd","disview"));
                } else {
                    $data1 = $this->db->query($sql1)->getResultArray();
                    $data2 = $this->db->query($sql2)->getResultArray()[0];
                    return view("pages/Master/mas_daybook_master", compact("url", "option", "data1", "data2","redokadd","disview"));
                }
            }
            
        }

    }
//end
//Done By Sylvester
    public function mas_department_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $department_code = isset($_REQUEST['department_code']) ? $_REQUEST['department_code'] : null;
        $department_name = isset($_REQUEST['department_name']) ? $_REQUEST['department_name'] : null;
        $department_codein = str_pad($department_code, 4, '0', STR_PAD_LEFT);
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            { 
            switch ($option) 
                {
                case 'Add':
                    $this->db->query("insert into  department_master (department_code,department_name) values ('$department_codein','$department_name')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update  department_master set department_name='$department_name' WHERE department_code='$department_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from department_master WHERE department_code='$department_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
                {
                    switch ($option) {
                        case 'Add':
                            $sql = "select max(department_code) as `maxValue` from department_master";
                            break;
                        case 'Edit':
                            $sql = "select * from department_master where department_code='$department_code' ";
                            break;
                        case 'View':
                            $sql = "select * from department_master where department_code='$department_code' ";
                            break;
                        case 'Delete':
                            $sql = "select * from department_master where department_code='$department_code' ";
                            break;
                    }
                    if ($option != 'Add') {
                        $data = $this->db->query($sql)->getResultArray()[0];
                        return view("pages/Master/mas_department_master", compact("url", "option", "data","redokadd","disview"));
                    } else {
                        $data1 = $this->db->query($sql)->getResultArray()[0];
                        return view("pages/Master/mas_department_master", compact("url", "option", "data1","redokadd","disview"));
                    }
                }
        } 
    }
//end
//Done By Sylvester
    public function mas_designation_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $designation_code = isset($_REQUEST['designation_code']) ? $_REQUEST['designation_code'] : null;
        $designation_name = isset($_REQUEST['designation_name']) ? $_REQUEST['designation_name'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $designation_codein = str_pad($designation_code, 4, '0', STR_PAD_LEFT);
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  designation_master (designation_code,designation_name) values ('$designation_codein','$designation_name')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update  designation_master set designation_name='$designation_name' WHERE designation_code='$designation_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from designation_master  WHERE designation_code='$designation_code'");
                    session()->setFlashdata('message', 'Records Delete Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
            switch ($option)
                {
                    case 'Add':
                        $sql = "select max(designation_code) as `maxValue` from designation_master";
                        break;
                    case 'Edit':
                        $sql = "select * from designation_master where designation_code='$designation_code' ";
                        break;
                    case 'View':
                        $sql = "select * from designation_master where designation_code='$designation_code' ";
                        break;
                    case 'Delete':
                        $sql = "select * from designation_master where designation_code='$designation_code' ";
                        break;
                }
                if ($option != 'Add') 
                {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_designation_master", compact("url", "option", "data","redokadd","disview"));
                } else {
                    $data1 = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_designation_master", compact("url", "option", "data1","redokadd","disview"));
                }
                }
        }        
    }
//end
//Done By Sylvester
    public function mas_employee_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $employee_id = isset($_REQUEST['employee_id']) ? $_REQUEST['employee_id'] : null;
        $employee_name = isset($_REQUEST['employee_name']) ? $_REQUEST['employee_name'] : null;
        $employee_address1 = isset($_REQUEST['employee_address1']) ? $_REQUEST['employee_address1'] : null;
        $employee_pin = isset($_REQUEST['employee_pin']) ? $_REQUEST['employee_pin'] : null;
        $employee_phone = isset($_REQUEST['employee_phone']) ? $_REQUEST['employee_phone'] : null;
        $department_code = isset($_REQUEST['department_code']) ? $_REQUEST['department_code'] : null;
        $designation_code = isset($_REQUEST['designation_code']) ? $_REQUEST['designation_code'] : null;
        $login_id = isset($_REQUEST['login_id']) ? $_REQUEST['login_id'] : null;
        $employee_ind = isset($_REQUEST['employee_ind']) ? $_REQUEST['employee_ind'] : null;
        $salesman_ind = isset($_REQUEST['salesman_ind']) ? $_REQUEST['salesman_ind'] : null;
        $user_type = isset($_REQUEST['user_type']) ? $_REQUEST['user_type'] : null;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
        $atten_indicator = isset($_REQUEST['atten_indicator']) ? $_REQUEST['atten_indicator'] : null;
        $attn_id = isset($_REQUEST['attn_id']) ? $_REQUEST['attn_id'] : null;
        $employee_initial = isset($_REQUEST['employee_initial']) ? $_REQUEST['employee_initial'] : null;
        $employee_city = isset($_REQUEST['employee_city']) ? $_REQUEST['employee_city'] : null;
        $employee_pan_no = isset($_REQUEST['employee_pan_no']) ? $_REQUEST['employee_pan_no'] : null;
        $employee_mobile = isset($_REQUEST['employee_mobile']) ? $_REQUEST['employee_mobile'] : null;
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
        $user_ind = isset($_REQUEST['user_ind']) ? $_REQUEST['user_ind'] : null;
        $branch_code = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : null;
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
        $gross_salary = isset($_REQUEST['gross_salary']) ? $_REQUEST['gross_salary'] : null;
        $last_update_id = isset($_REQUEST['last_update_id']) ? $_REQUEST['last_update_id'] : null;
        $last_update_dt = isset($_REQUEST['last_update_dt']) ? $_REQUEST['last_update_dt'] : null;
        $prepared_by = isset($_REQUEST['prepared_by']) ? $_REQUEST['prepared_by'] : null;
        $prepared_on = isset($_REQUEST['prepared_on']) ? $_REQUEST['prepared_on'] : null;
        $first_letter = 'E';
        $employe_codein = str_pad($first_letter . $employee_id, 4, '0', STR_PAD_LEFT);
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
            $redLetter = '';};
            if ($this->request->getMethod() == 'post') {
                if($finsub=="fsub")
            {
            switch ($option) 
                {
                case 'Add':
                    
                    $this->db->query("insert into  empmas (employee_id,employee_name,employee_address1,employee_pin,employee_phone,department_code,designation_code,login_id,employee_ind,salesman_ind,user_type,status_code,atten_indicator,attn_id,employee_initial,employee_city,employee_pan_no,employee_mobile,password,user_ind,branch_code,email_id,gross_salary,prepared_by,prepared_on) values ('$employe_codein','$employee_name','$employee_address1','$employee_pin','$employee_phone','$department_code','$designation_code','$login_id','$employee_ind','$salesman_ind','$user_type','$status_code','$atten_indicator','$attn_id','$employee_initial','$employee_city','$employee_pan_no','$employee_mobile','$password','$user_ind','$branch_code','$email_id','$gross_salary','$prepared_by','$prepared_on')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update empmas set employee_name='$employee_name',employee_address1='$employee_address1',employee_pin='$employee_pin',employee_phone='$employee_phone',department_code='$department_code',designation_code='$designation_code',login_id='$login_id',employee_ind='$employee_ind',salesman_ind='$salesman_ind',user_type='$user_type',status_code='$status_code',atten_indicator='$atten_indicator',attn_id='$attn_id',employee_initial='$employee_initial',employee_city='$employee_city',employee_pan_no='$employee_pan_no',employee_mobile='$employee_mobile',password='$password',user_ind='$user_ind',branch_code='$branch_code',email_id='$email_id',gross_salary='$gross_salary',last_update_id='$last_update_id',last_update_dt='$last_update_id' WHERE employee_id='$employee_id'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from empmas WHERE employee_id='$employee_id'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Add':
                        $sql = "select max(employee_id) as `maxValue` from empmas";
                        break;
                    case 'Edit':
                        $sql = "select *,a.email_id,a.status_code from empmas a,designation_master b, department_master c, branch_master d where a.designation_code=b.designation_code AND a.department_code=c.department_code AND a.branch_code=d.branch_code AND a.employee_id='$employee_id' ";
                        break;
                    case 'View':
                        $sql = "select *,a.email_id,a.status_code from empmas a,designation_master b, department_master c, branch_master d where a.designation_code=b.designation_code AND a.department_code=c.department_code AND a.branch_code=d.branch_code AND a.employee_id='$employee_id' ";
                        break;
                    case 'Delete':
                        $sql = "select *,a.email_id,a.status_code from empmas a,designation_master b, department_master c, branch_master d where a.designation_code=b.designation_code AND a.department_code=c.department_code AND a.branch_code=d.branch_code AND a.employee_id='$employee_id' ";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_employee_master", compact("url", "option", "data", "sessionName","redokadd","disview"));
                } else {
                    $data1 = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_employee_master", compact("url", "option", "data1", "sessionName","redokadd","disview"));
                }
            }
        } 
    }
//end
//Done By Sylvester
    public function mas_initial_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $initial_code = isset($_REQUEST['initial_code']) ? $_REQUEST['initial_code'] : null;
        $initial_name = isset($_REQUEST['initial_name']) ? $_REQUEST['initial_name'] : null;
        $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
        $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
        $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
        $dt_of_join = isset($_REQUEST['dt_of_join']) ? date('Y-m-d',strtotime($_REQUEST['dt_of_join'])) : null;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) 
                {
                case 'Add':
                    $this->db->query("insert into  initial_master (initial_code,initial_name,address_line_1,city,pin_code,mobile_no,dt_of_join,status_code) values ('$initial_code','$initial_name','$address_line_1','$city','$pin_code','$mobile_no','$dt_of_join','$status_code')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update initial_master set initial_name='$initial_name',address_line_1='$address_line_1',city='$city',pin_code='$pin_code',mobile_no='$mobile_no',dt_of_join='$dt_of_join',status_code='$status_code' WHERE initial_code='$initial_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from  initial_master WHERE initial_code='$initial_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Add':
                        $sql = "select * from status_master where table_name='initial_master'";
                        $sql2 = "select max(initial_code) as `maxValue` from initial_master";
                        break;
                    case 'Edit':
                        $sql = "select * from status_master where table_name='initial_master'";
                        $sql2 = "select * from initial_master where initial_code='$initial_code'";
                        break;
                    case 'View':
                        $sql = "select * from status_master where table_name='initial_master'";
                        $sql2 = "select * from initial_master where initial_code='$initial_code'";
                        break;
                    case 'Delete':
                        $sql = "select * from status_master where table_name='initial_master'";
                        $sql2 = "select * from initial_master where initial_code='$initial_code'";
                        break;
                }
                $data = $this->db->query($sql)->getResultArray();
        $data1 = $this->db->query($sql2)->getResultArray()[0];
        return view("pages/Master/mas_initial_master", compact("url", "option", "data", "data1","redokadd","disview"));
            }
        }
    }
//end
//Done By Sylvester
    public function mas_code_master($option = 'list')
    {

        $sql = "select * from type_master where user_access = 'Y' order by type_desc";
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";

        $data = $this->db->query($sql)->getResultArray();
        // $data1 = $this->db->query($sql2)->getResultArray()[0];
        return view("pages/Master/mas_code_master", compact("url", "option", "data"));

    }
//end
//Done By Sylvester
    public function mas_code_master_list($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $type_code = isset($_REQUEST['type_code']) ? $_REQUEST['type_code'] : null;
        $url = "mas-code-master?display_id={$display_id}&menu_id={$menu_id}";
        if ($this->request->getMethod() == 'post') {
            $sql = "select type_code,type_desc from type_master where type_code = '$type_code'";
            $sql2 = "select code_code,code_desc from code_master where type_code = '$type_code'";
        }
        $data = $this->db->query($sql)->getResultArray()[0];
        $data2 = $this->db->query($sql2)->getResultArray();
        return view("pages/Master/mas_code_master_list", compact("url", "option", "data", "data2"));

    }
//end
//Done By Sylvester
    public function mas_code_master_insert($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $option = isset($_REQUEST['option']) ? $_REQUEST['option'] : null;
        $type_code = isset($_REQUEST['type_code']) ? $_REQUEST['type_code'] : null;
        $code_code = isset($_REQUEST['code_code']) ? $_REQUEST['code_code'] : null;
        $code_desc = isset($_REQUEST['code_desc']) ? $_REQUEST['code_desc'] : null;
        $code_code_hi = isset($_REQUEST['code_code_hi']) ? $_REQUEST['code_code_hi'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $url = "/sinhaco/master/mas-code-master?display_id={$display_id}&menu_id={$menu_id}";
        $url2 = "master/mas-code-master?display_id={$display_id}&menu_id={$menu_id}";
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  code_master (type_code,code_code,code_desc) values ('$type_code','$code_code','$code_desc')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url2);
                    break;
                case 'Edit':
                    $this->db->query("update code_master set code_code='$code_code',code_desc='$code_desc' WHERE type_code='$type_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url2);
                    break;
                case 'Delete':
                    $this->db->query("delete from code_master WHERE type_code='$type_code' AND code_code='$code_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url2);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Add':
                        $sql = "select type_code,type_desc from type_master where type_code = '$type_code'";
                        break;
                    case 'Edit':
                        $sql = "select type_code,type_desc from type_master where type_code = '$type_code'";
                        $sql2 = "select * from code_master where type_code = '$type_code' AND code_code='$code_code'";
                        break;
                    case 'View':
                        $sql = "select type_code,type_desc from type_master where type_code = '$type_code'";
                        $sql2 = "select * from code_master where type_code = '$type_code' AND code_code='$code_code'";
                        break;
                    case 'Delete':
                        $sql = "select type_code,type_desc from type_master where type_code = '$type_code'";
                        $sql2 = "select * from code_master where type_code = '$type_code' AND code_code='$code_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    $data2 = $this->db->query($sql2)->getResultArray()[0];
                    return view("pages/Master/mas_code_master_insert", compact("url", "option", "data", "data2","redokadd","disview"));
                } else {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_code_master_insert", compact("url", "option", "data","redokadd","disview"));
                }
            }

        }
        

    }
//end
//Done By Sylvester
    public function mas_courier($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $rate_code = isset($_REQUEST['rate_code']) ? $_REQUEST['rate_code'] : null;
        $rt_code = isset($_REQUEST['rt_code']) ? $_REQUEST['rt_code'] : null;
        $supplier_code = isset($_REQUEST['supplier_code']) ? $_REQUEST['supplier_code'] : null;
        $rate_desc = isset($_REQUEST['rate_desc']) ? $_REQUEST['rate_desc'] : null;
        $rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  courier_rate (supplier_code,rate_desc,rate,status_code) values ('$supplier_code','$rate_desc','$rate','$status_code')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;

                case 'Edit':
                    $this->db->query("update courier_rate set supplier_code='$supplier_code',rate_desc='$rate_desc',rate='$rate',status_code='$status_code' where rate_code='$rt_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from courier_rate  where rate_code='$rt_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
            }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Edit':
                        $sql = "select *,a.status_code from courier_rate a, supplier_master b where a.supplier_code=b.supplier_code and a.rate_code='$rate_code'";
                        break;
                    case 'View':
                        $sql = "select *,a.status_code from courier_rate a, supplier_master b where a.supplier_code=b.supplier_code and a.rate_code='$rate_code'";
                        break;
                    case 'Delete':
                        $sql = "select *,a.status_code from courier_rate a, supplier_master b where a.supplier_code=b.supplier_code and a.rate_code='$rate_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_courier", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_courier", compact("url", "option","redokadd","disview"));
                }
            }
    }  
    }
//end
//Done By Sylvester
    public function mas_photocopy($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $rate_code = isset($_REQUEST['rate_code']) ? $_REQUEST['rate_code'] : null;
        $rt_code = isset($_REQUEST['rt_code']) ? $_REQUEST['rt_code'] : null;
        $supplier_code = isset($_REQUEST['supplier_code']) ? $_REQUEST['supplier_code'] : null;
        $page_size = isset($_REQUEST['page_size']) ? $_REQUEST['page_size'] : null;
        $page_side = isset($_REQUEST['page_side']) ? $_REQUEST['page_side'] : null;
        $rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  photocopy_rate (supplier_code,page_size,page_side,rate,status_code) values ('$supplier_code','$page_size','$page_side','$rate','$status_code')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;

                case 'Edit':
                    $this->db->query("update photocopy_rate set supplier_code='$supplier_code',page_size='$page_size',page_side='$page_side',rate='$rate',status_code='$status_code' where rate_code='$rt_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from photocopy_rate where rate_code='$rt_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
            switch ($option) {
                case 'Edit':
                    $sql = "select *,a.status_code from photocopy_rate a, supplier_master b where a.supplier_code=b.supplier_code and a.rate_code='$rate_code'";
                    break;
                case 'View':
                    $sql = "select *,a.status_code from photocopy_rate a, supplier_master b where a.supplier_code=b.supplier_code and a.rate_code='$rate_code'";
                    break;
                case 'Delete':
                    $sql = "select *,a.status_code from photocopy_rate a, supplier_master b where a.supplier_code=b.supplier_code and a.rate_code='$rate_code'";
                    break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_photocopy", compact("url", "option", "data","redokadd","disview"));
                } else {
        
                    return view("pages/Master/mas_photocopy", compact("url", "option","redokadd","disview"));
                }
            }
        }
    }
//end
//Done By Sylvester
    public function mas_billing_rate($option = 'list')
    {  
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $serial_no = isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : null;
        $sl_no = isset($_REQUEST['sl_no']) ? $_REQUEST['sl_no'] : null;
        $counsel_code = isset($_REQUEST['counsel_code']) ? $_REQUEST['counsel_code'] : null;
        $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
        $matter_code = isset($_REQUEST['matter_code']) ? $_REQUEST['matter_code'] : null;
        $activity_code = isset($_REQUEST['activity_code']) ? $_REQUEST['activity_code'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $rate = isset($_REQUEST['rate']) ? $_REQUEST['rate'] : null;        
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  billing_rate (counsel_code,client_code,matter_code,activity_code,rate) values ('$counsel_code','$client_code','$matter_code','$activity_code','$rate')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;

                case 'Edit':
                    $this->db->query("update billing_rate set counsel_code='$counsel_code',client_code='$client_code',matter_code='$matter_code',activity_code='$activity_code',rate='$rate' where serial_no='$sl_no'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from billing_rate where serial_no='$sl_no'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Edit':
                        $sql = "select billing_rate.*,b.associate_name,c.client_name,d.matter_desc1,d.matter_desc2,e.code_desc FROM billing_rate INNER JOIN associate_master b ON billing_rate.counsel_code = b.associate_code and b.counsel_type in ('C00','C01','C02','X01')
                                 INNER JOIN client_master c ON billing_rate.client_code = c.client_code
                                 INNER JOIN fileinfo_header d ON billing_rate.matter_code = d.matter_code
                                 INNER JOIN code_master e ON billing_rate.activity_code = e.code_code and e.type_code = '033' WHERE serial_no='$serial_no'";
                        break;
                    case 'View':
                        $sql = "select billing_rate.*,b.associate_name,c.client_name,d.matter_desc1,d.matter_desc2,e.code_desc FROM billing_rate INNER JOIN associate_master b ON billing_rate.counsel_code = b.associate_code and b.counsel_type in ('C00','C01','C02','X01')
                                 INNER JOIN client_master c ON billing_rate.client_code = c.client_code
                                 INNER JOIN fileinfo_header d ON billing_rate.matter_code = d.matter_code
                                 INNER JOIN code_master e ON billing_rate.activity_code = e.code_code and e.type_code = '033' WHERE serial_no='$serial_no'";
                        break;
                    case 'Delete':
                        $sql = "select billing_rate.*,b.associate_name,c.client_name,d.matter_desc1,d.matter_desc2,e.code_desc FROM billing_rate INNER JOIN associate_master b ON billing_rate.counsel_code = b.associate_code and b.counsel_type in ('C00','C01','C02','X01')
                        INNER JOIN client_master c ON billing_rate.client_code = c.client_code
                        INNER JOIN fileinfo_header d ON billing_rate.matter_code = d.matter_code
                        INNER JOIN code_master e ON billing_rate.activity_code = e.code_code and e.type_code = '033' WHERE serial_no='$serial_no'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_billing_rate", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_billing_rate", compact("url", "option","redokadd","disview"));
                }
            }
        }
    }
//end
//Done By Sylvester
    public function mas_sub_account_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $main_ac_code = isset($_REQUEST['main_ac_code']) ? $_REQUEST['main_ac_code'] : null;
        $ac_desc = isset($_REQUEST['ac_desc']) ? $_REQUEST['ac_desc'] : null;
        $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : null;
        $orcount = isset($_REQUEST['orcount']) ? $_REQUEST['orcount'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Edit':
                    if ($count > $orcount && $orcount != 0) {
                        for ($i = $orcount + 1; $i <= $count; $i++) {
                            $first_letter = substr($_REQUEST['sub_ac_desc' . $i], 0, 1);
                            $sub_ac_code = $first_letter . $_REQUEST['subCod' . $i];
                            $this->db->query("insert into  sub_account_master (main_ac_code,sub_ac_code,sub_ac_desc,status_code) values ('$main_ac_code','$sub_ac_code','" . $_REQUEST['sub_ac_desc' . $i] . "','" . $_REQUEST['status_code' . $i] . "')");
                        }
                        session()->setFlashdata('message', 'Records Inserted Successfully !!');
                         return redirect()->to($url);
                    }
                    if ($orcount == 0) {
                        for ($i = $orcount + 1; $i <= $count; $i++) {
                            $first_letter = substr($_REQUEST['sub_ac_desc' . $i], 0, 1);
                            $sub_ac_code = $first_letter . $_REQUEST['subCod' . $i];

                            $this->db->query("insert into  sub_account_master (main_ac_code,sub_ac_code,sub_ac_desc,status_code) values ('$main_ac_code','$sub_ac_code','" . $_REQUEST['sub_ac_desc' . $i] . "','" . $_REQUEST['status_code' . $i] . "')");
                        }
                        session()->setFlashdata('message', 'Records Inserted Successfully !!');
                        return redirect()->to($url);
                    }
                    if ($orcount == $count) {
                        for ($i = 1; $i <= $count; $i++) {
                            $this->db->query("update sub_account_master set main_ac_code='$main_ac_code',sub_ac_code='" . $_REQUEST['sub_ac_code' . $i] . "',sub_ac_desc='" . $_REQUEST['sub_ac_desc' . $i] . "',status_code='" . $_REQUEST['status_code' . $i] . "' WHERE main_ac_code='$main_ac_code' AND sub_ac_code='" . $_REQUEST['sub_ac_code' . $i] . "'");
                        }
                        session()->setFlashdata('message', 'Records Updated Successfully !!');
                        return redirect()->to($url);
                    }

                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Select':
                        $sql = "select * from sub_account_master WHERE main_ac_code='$main_ac_code'";
                        $sql1 = "select a.main_ac_desc,a.main_ac_code,b.code_desc,a.sub_ac_ind from account_master a, code_master b WHERE b.type_code='011' and a.account_type_code=b.code_code and a.sub_ac_ind='Y' and a.main_ac_code='$main_ac_code'";
                        break;
                    case 'View':
                        $sql = "select * from sub_account_master WHERE main_ac_code='$main_ac_code'";
                        $sql1 = "select a.main_ac_desc,a.main_ac_code,b.code_desc,a.sub_ac_ind from account_master a, code_master b WHERE b.type_code='011' and a.account_type_code=b.code_code and a.sub_ac_ind='Y' and a.main_ac_code not in ('100037','200025')";
                        break;
                }
                if ($option == 'Select') {
                    if (count($data) > 0) {
                        $data = $this->db->query($sql)->getResultArray();
                        $data1 = $this->db->query($sql1)->getResultArray()[0];
                        return view("pages/Master/mas_sub_account_master", compact("url", "option", "data", "data1","redokadd","disview"));
                    } else {
                        $data = $this->db->query($sql)->getResultArray();
                        $data1 = $this->db->query($sql1)->getResultArray()[0];
                        return view("pages/Master/mas_sub_account_master", compact("url", "option", "data", "data1","redokadd","disview"));
                    }
                }
                else
                    {
                        $data = $this->db->query($sql)->getResultArray();
                        $data1 = $this->db->query($sql1)->getResultArray()[0];
                        return view("pages/Master/mas_sub_account_master", compact("url", "option", "data", "data1","redokadd","disview"));
                    }
            }
        }
        // $data = $this->db->query($sql)->getResultArray();
        
    }
//end
//Done By Sylvester
    public function mas_supplier_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $supplier_code = isset($_REQUEST['supplier_code']) ? $_REQUEST['supplier_code'] : null;
        $supCode = isset($_REQUEST['supplier_code']) ? $_REQUEST['supplier_code'] : null;
        $supplier_name = isset($_REQUEST['supplier_name']) ? $_REQUEST['supplier_name'] : null;
        $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
        $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
        $state_code = isset($_REQUEST['state_code']) ? $_REQUEST['state_code'] : null;
        $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : null;
        $nature_of_service = isset($_REQUEST['nature_of_service']) ? $_REQUEST['nature_of_service'] : null;
        $credit_days = isset($_REQUEST['credit_days']) ? $_REQUEST['credit_days'] : null;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
        $contact_person = isset($_REQUEST['contact_person']) ? $_REQUEST['contact_person'] : null;
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
        $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
        $fax_no = isset($_REQUEST['fax_no']) ? $_REQUEST['fax_no'] : null;
        $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
        $default_comm_mode = isset($_REQUEST['default_comm_mode']) ? $_REQUEST['default_comm_mode'] : null;
        $pan_no = isset($_REQUEST['pan_no']) ? $_REQUEST['pan_no'] : null;
        $tan_no = isset($_REQUEST['tan_no']) ? $_REQUEST['tan_no'] : null;
        $tin_no = isset($_REQUEST['tin_no']) ? $_REQUEST['tin_no'] : null;
        $vat_no = isset($_REQUEST['vat_no']) ? $_REQUEST['vat_no'] : null;
        $service_tax_regn_no = isset($_REQUEST['service_tax_regn_no']) ? $_REQUEST['service_tax_regn_no'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $first_letter = 'S';
        $code = $first_letter . str_pad($supplier_code, 4, '0', STR_PAD_LEFT);
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";   
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  supplier_master (supplier_code,supplier_name,address_line_1,city,pin_code,state_code,country,nature_of_service,credit_days,status_code,contact_person,email_id,phone_no,fax_no,mobile_no,default_comm_mode,pan_no,tan_no,tin_no,vat_no,service_tax_regn_no) values ('$code','$supplier_name','$address_line_1','$city','$pin_code','$state_code','$country','$nature_of_service','$credit_days','$status_code','$contact_person','$email_id','$phone_no','$fax_no','$mobile_no','$default_comm_mode','$pan_no','$tan_no','$tin_no','$vat_no','$service_tax_regn_no')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update supplier_master set supplier_name='$supplier_name',address_line_1='$address_line_1',city='$city',pin_code='$pin_code',state_code='$state_code',country='$country',nature_of_service='$nature_of_service',credit_days='$credit_days',status_code='$status_code',contact_person='$contact_person',email_id='$email_id',phone_no='$phone_no',fax_no='$fax_no',mobile_no='$mobile_no',default_comm_mode='$default_comm_mode',pan_no='$pan_no',tan_no='$tan_no',tin_no='$tin_no',vat_no='$vat_no',service_tax_regn_no='$service_tax_regn_no' WHERE supplier_code='$supCode'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from supplier_master WHERE supplier_code='$supCode'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Add':
                        $sql1 = "select * from state_master";
                        break;
                    case 'Edit':
                        $sql = "select * from supplier_master WHERE supplier_code='$supplier_code'";
                        $sql1 = "select * from state_master";
                        break;
                    case 'View':
                        $sql = "select * from supplier_master WHERE supplier_code='$supplier_code'";
                        $sql1 = "select * from state_master";
                        break;
                    case 'Delete':
                        $sql = "select * from supplier_master WHERE supplier_code='$supplier_code'";
                        $sql1 = "select * from state_master";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    $data2 = $this->db->query($sql1)->getResultArray();
                    return view("pages/Master/mas_supplier_master", compact("url", "option", "data", "data2","redokadd","disview"));
                } else {
                    $data2 = $this->db->query($sql1)->getResultArray();
                    return view("pages/Master/mas_supplier_master", compact("url", "option", "data2","redokadd","disview"));
                }
            }
        }   
    }
//end
//Done By Sylvester
    public function mas_st_narration($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $serial_no = isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : null;
        $sl_no = isset($_REQUEST['sl_no']) ? $_REQUEST['sl_no'] : null;
        $narration_type = isset($_REQUEST['narration_type']) ? $_REQUEST['narration_type'] : null;
        $std_narration = isset($_REQUEST['std_narration']) ? $_REQUEST['std_narration'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
        //$first_letter = 'S';
        //$code =substr($first_letter.$supplier_code,0,5);
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  standard_narration (narration_type,std_narration) values ('$narration_type','$std_narration')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update standard_narration set narration_type='$narration_type',std_narration='$std_narration' WHERE serial_no='$sl_no'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from  standard_narration WHERE serial_no='$sl_no'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Edit':
                        $sql = "select * from standard_narration inner join code_master ON standard_narration.narration_type= code_master.code_code WHERE standard_narration.serial_no='$serial_no'";
                        break;
                    case 'View':
                        $sql = "select * from standard_narration inner join code_master ON standard_narration.narration_type= code_master.code_code WHERE standard_narration.serial_no='$serial_no'";
                        break;
                    case 'Delete':
                        $sql = "select * from standard_narration inner join code_master ON standard_narration.narration_type= code_master.code_code WHERE standard_narration.serial_no='$serial_no'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_st_narration", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_st_narration", compact("url", "option","redokadd","disview"));
                }
            }
        } 
    }
//end
//Done By Sylvester
    public function mas_state_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $state_code = isset($_REQUEST['state_code']) ? $_REQUEST['state_code'] : null;
        $state_name = isset($_REQUEST['state_name']) ? $_REQUEST['state_name'] : null;
        $gst_zone_code = isset($_REQUEST['gst_zone_code']) ? $_REQUEST['gst_zone_code'] : null;
        $zone_code = isset($_REQUEST['zone_code']) ? $_REQUEST['zone_code'] : null;
        $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
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
        if ($option == 'letter') {
            $redk = 'readonly';
            $redv = 'readonly';
            $disv = 'disabled';
            $disb = 'disabled';
            $redve = 'disabled';
            $redokadd = 'readonly';
            $disview = 'disabled';
            $redLetter = '';}
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  state_master (state_code,state_name,gst_zone_code,zone_code,country) values ('$state_code','$state_name','$gst_zone_code','$zone_code','$country')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update state_master set state_name='$state_name',gst_zone_code='$gst_zone_code',zone_code='$zone_code',country='$country' WHERE state_code='$state_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from state_master WHERE state_code='$state_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Add':
                        $sql1 = "select * from code_master where type_code=029";
                        break;
                    case 'Edit':
                        $sql = "select * from state_master WHERE  state_code='$state_code'";
                        $sql1 = "select * from code_master where type_code=029";
                        break;
                    case 'View':
                        $sql = "select * from state_master  WHERE state_code='$state_code'";
                        $sql1 = "select * from code_master where type_code=029";
                        break;
                    case 'Delete':
                        $sql = "select * from state_master  WHERE state_code='$state_code'";
                        $sql1 = "select * from code_master where type_code=029";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    $data1 = $this->db->query($sql1)->getResultArray();
                    return view("pages/Master/mas_state_master", compact("url", "option", "data", "data1","redokadd","disview"));
                } else {
                    $data1 = $this->db->query($sql1)->getResultArray();
                    return view("pages/Master/mas_state_master", compact("url", "option", "data1","redokadd","disview"));
                }
            }
        } 
    }
//end
//Done By Sylvester
    public function mas_tax_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $tax_code = isset($_REQUEST['tax_code']) ? $_REQUEST['tax_code'] : null;
        $tax_name = isset($_REQUEST['tax_name']) ? $_REQUEST['tax_name'] : null;
        $tax_type_code = isset($_REQUEST['tax_type_code']) ? $_REQUEST['tax_type_code'] : null;
        $tax_account_code = isset($_REQUEST['tax_account_code']) ? $_REQUEST['tax_account_code'] : null;
        $sub_ac_code = isset($_REQUEST['sub_ac_code']) ? $_REQUEST['sub_ac_code'] : null;
        $main_ac_code = isset($_REQUEST['main_ac_code']) ? $_REQUEST['main_ac_code'] : null;
        $first_letter = 'T';
        $code = $first_letter . str_pad($tax_code, 3, '0', STR_PAD_LEFT);
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  tax_master (tax_code,tax_name,tax_type_code,tax_account_code,tax_sub_account_code) values ('$code','$tax_name','$tax_type_code','$tax_account_code','$sub_ac_code')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update tax_master set tax_name='$tax_name',tax_type_code='$tax_type_code',tax_account_code='$tax_account_code',tax_sub_account_code='$sub_ac_code' WHERE tax_code='$tax_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from tax_master WHERE tax_code='$tax_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
                
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Edit':
                        $sql = "select * from tax_master INNER JOIN account_master ON tax_master.tax_account_code=account_master.main_ac_code INNER JOIN sub_account_master ON tax_master.tax_sub_account_code=sub_account_master.sub_ac_code WHERE  tax_master.tax_code='$tax_code'";
                        break;
                    case 'View':
                        $sql = "select * from tax_master INNER JOIN account_master ON tax_master.tax_account_code=account_master.main_ac_code INNER JOIN sub_account_master ON tax_master.tax_sub_account_code=sub_account_master.sub_ac_code WHERE  tax_master.tax_code='$tax_code'";
                        break;
                    case 'Delete':
                        $sql = "select * from tax_master INNER JOIN account_master ON tax_master.tax_account_code=account_master.main_ac_code INNER JOIN sub_account_master ON tax_master.tax_sub_account_code=sub_account_master.sub_ac_code WHERE  tax_master.tax_code='$tax_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_tax_master", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_tax_master", compact("url", "option","redokadd","disview"));
                }
            }
        } 
        
    }
//end
//Done By Sylvester
    public function mas_activity_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $activity_code = isset($_REQUEST['activity_code']) ? $_REQUEST['activity_code'] : null;
        $activityCodeNew = isset($_REQUEST['activity_code']) ? $_REQUEST['activity_code'] : null;
        $activity_desc = isset($_REQUEST['activity_desc']) ? $_REQUEST['activity_desc'] : null;
        $activity_type = isset($_REQUEST['activity_type']) ? $_REQUEST['activity_type'] : null;
        $first_letter = substr($activity_desc, 0, 1);
        $code = $first_letter . str_pad($activityCodeNew, 2, '0', STR_PAD_LEFT);
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  activity_master (activity_code,activity_desc,activity_type) values ('$code','$activity_desc','$activity_type')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update activity_master set activity_desc='$activity_desc',activity_type='$activity_type' WHERE activity_code='$activity_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from activity_master WHERE activity_code='$activity_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Edit':
                        $sql = "select * from activity_master WHERE activity_code='$activity_code'";
                        break;
                    case 'View':
                        $sql = "select * from activity_master WHERE activity_code='$activity_code'";
                        break;
                    case 'Delete':
                        $sql = "select * from activity_master WHERE activity_code='$activity_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_activity_master", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_activity_master", compact("url", "option","redokadd","disview"));
                }
            } 
        }
        
    }
//end
//Done By Sylvester
    public function mas_expense_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $expense_code = isset($_REQUEST['expense_code']) ? $_REQUEST['expense_code'] : null;
        $expenseCodeNew = isset($_REQUEST['expense_code']) ? $_REQUEST['expense_code'] : null;
        $expense_description = isset($_REQUEST['expense_description']) ? $_REQUEST['expense_description'] : null;
        $expense_type = isset($_REQUEST['expense_type']) ? $_REQUEST['expense_type'] : null;
        $first_letter = substr($expense_description, 0, 1);
        $code = $first_letter . str_pad($expenseCodeNew, 2, '0', STR_PAD_LEFT);
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  expense_master (expense_code,expense_desc,expense_type) values ('$code','$expense_description','$expense_type')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update expense_master set expense_desc='$expense_description',expense_type='$expense_type' WHERE expense_code='$expense_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from expense_master WHERE expense_code='$expense_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Edit':
                        $sql = "select * from expense_master WHERE expense_code='$expense_code'";
                        break;
                    case 'View':
                        $sql = "select * from expense_master WHERE expense_code='$expense_code'";
                        break;
                    case 'Delete':
                        $sql = "select * from expense_master WHERE expense_code='$expense_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_expense_master", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_expense_master", compact("url", "option","redokadd","disview"));
                }
            }
        } 
    }
//end
//Done By Sylvester
    public function mas_other_payee($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $other_payee_code = isset($_REQUEST['other_payee_code']) ? $_REQUEST['other_payee_code'] : null;
        $otherCodeNew = isset($_REQUEST['other_payee_code']) ? $_REQUEST['other_payee_code'] : null;
        $other_payee_name = isset($_REQUEST['other_payee_name']) ? $_REQUEST['other_payee_name'] : null;
        $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
        $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
        $state_code1 = isset($_REQUEST['state_code1']) ? $_REQUEST['state_code1'] : null;
        $state_code = isset($_REQUEST['state_code']) ? $_REQUEST['state_code'] : null;
        $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : null;
        $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
        $fax_no = isset($_REQUEST['fax_no']) ? $_REQUEST['fax_no'] : null;
        $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
        $pan_no = isset($_REQUEST['pan_no']) ? $_REQUEST['pan_no'] : null;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
        $first_letter = 'O';
        $code = $first_letter . str_pad($otherCodeNew, 5, '0', STR_PAD_LEFT);
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  other_payee_master (other_payee_code,other_payee_name,address_line_1,city,pin_code,state_code,country,phone_no,fax_no,mobile_no,email_id,pan_no,status_code) values ('$code','$other_payee_name','$address_line_1','$city','$pin_code','$state_code','$country','$phone_no','$fax_no','$mobile_no','$email_id','$pan_no','$status_code')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update other_payee_master set other_payee_name='$other_payee_name',address_line_1='$address_line_1',city='$city',pin_code='$pin_code',state_code='$state_code',country='$country',phone_no='$phone_no',fax_no='$fax_no',mobile_no='$mobile_no',email_id='$email_id',pan_no='$pan_no',status_code='$status_code' WHERE other_payee_code='$other_payee_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from other_payee_master WHERE other_payee_code='$other_payee_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Add':
                        $sql1 = "select * from state_master";
                    case 'Edit':
                        $sql1 = "select * from state_master";
                        $sql = "select * from other_payee_master WHERE other_payee_code='$other_payee_code'";
                        break;
                    case 'View':
                        $sql1 = "select * from state_master";
                        $sql = "select * from other_payee_master WHERE other_payee_code='$other_payee_code'";
                        break;
                    case 'Delete':
                        $sql1 = "select * from state_master";
                        $sql = "select * from other_payee_master WHERE other_payee_code='$other_payee_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    $data1 = $this->db->query($sql1)->getResultArray();
                    return view("pages/Master/mas_other_payee", compact("url", "option", "data", "data1","redokadd","disview"));
                } else {
                    $data1 = $this->db->query($sql1)->getResultArray();
                    return view("pages/Master/mas_other_payee", compact("url", "option", "data1","redokadd","disview"));
                }
            }
        }
        
    }
//end
//Done By Sylvester
    public function mas_mis_name_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;

        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $ceo_code = isset($_REQUEST['ceo_code']) ? $_REQUEST['ceo_code'] : null;
        $ceoCodeNew = isset($_REQUEST['ceo_code']) ? $_REQUEST['ceo_code'] : null;
        $name_desc = isset($_REQUEST['name_desc']) ? $_REQUEST['name_desc'] : null;
        $first_letter = 'P';
        $code = $first_letter . str_pad($ceoCodeNew, 3, '0', STR_PAD_LEFT);
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  mis_name_master (ceo_code,name_desc,status_code) values ('$code','$name_desc','A')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update mis_name_master set name_desc='$name_desc' WHERE ceo_code='$ceo_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from mis_name_master WHERE ceo_code='$ceo_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Edit':
                        $sql = "select * from mis_name_master WHERE ceo_code='$ceo_code'";
                        break;
                    case 'View':
                        $sql = "select * from mis_name_master WHERE ceo_code='$ceo_code'";
                        break;
                    case 'Delete':
                        $sql = "select * from mis_name_master WHERE ceo_code='$ceo_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_mis_name_master", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_mis_name_master", compact("url", "option","redokadd","disview"));
                }
            }
        } 
        
    }
//end
//Done By Sylvester
    public function mas_mis_exps_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $exps_code = isset($_REQUEST['exps_code']) ? $_REQUEST['exps_code'] : null;
        $expsCodeNew = isset($_REQUEST['exps_code']) ? $_REQUEST['exps_code'] : null;
        $exps_desc = isset($_REQUEST['exps_desc']) ? $_REQUEST['exps_desc'] : null;
        $first_letter = 'E';
        $code = $first_letter . str_pad($expsCodeNew, 3, '0', STR_PAD_LEFT);
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  mis_exps_master (exps_code,exps_desc) values ('$code','$exps_desc')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update mis_exps_master set exps_desc='$exps_desc' WHERE exps_code='$exps_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from mis_exps_master WHERE exps_code='$exps_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Edit':
                        $sql = "select * from mis_exps_master WHERE exps_code='$exps_code'";
                        break;
                    case 'View':
                        $sql = "select * from mis_exps_master WHERE exps_code='$exps_code'";
                        break;
                    case 'Delete':
                        $sql = "select * from mis_exps_master WHERE exps_code='$exps_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    return view("pages/Master/mas_mis_exps_master", compact("url", "option", "data","redokadd","disview"));
                } else {
                    return view("pages/Master/mas_mis_exps_master", compact("url", "option","redokadd","disview"));
                }
            }
        } 
        
    }
//end
//Done By Sylvester
    public function mas_consultant_master($option = 'list')
    {
        $option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $consultant_code = isset($_REQUEST['consultant_code']) ? $_REQUEST['consultant_code'] : null;
        $consuCodeNew = isset($_REQUEST['consultant_code']) ? $_REQUEST['consultant_code'] : null;
        $consultant_name = isset($_REQUEST['consultant_name']) ? $_REQUEST['consultant_name'] : null;
        $address_line_1 = isset($_REQUEST['address_line_1']) ? $_REQUEST['address_line_1'] : null;
        $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : null;
        $pin_code = isset($_REQUEST['pin_code']) ? $_REQUEST['pin_code'] : null;
        $state_code = isset($_REQUEST['state_code']) ? $_REQUEST['state_code'] : null;
        $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : null;
        $nature_of_service = isset($_REQUEST['nature_of_service']) ? $_REQUEST['nature_of_service'] : null;
        $credit_days = isset($_REQUEST['credit_days']) ? $_REQUEST['credit_days'] : null;
        $contact_person = isset($_REQUEST['contact_person']) ? $_REQUEST['contact_person'] : null;
        $default_comm_mode = isset($_REQUEST['default_comm_mode']) ? $_REQUEST['default_comm_mode'] : null;
        $phone_no = isset($_REQUEST['phone_no']) ? $_REQUEST['phone_no'] : null;
        $fax_no = isset($_REQUEST['fax_no']) ? $_REQUEST['fax_no'] : null;
        $mobile_no = isset($_REQUEST['mobile_no']) ? $_REQUEST['mobile_no'] : null;
        $email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : null;
        $pan_no = isset($_REQUEST['pan_no']) ? $_REQUEST['pan_no'] : null;
        $tan_no = isset($_REQUEST['tan_no']) ? $_REQUEST['tan_no'] : null;
        $tin_no = isset($_REQUEST['tin_no']) ? $_REQUEST['tin_no'] : null;
        $vat_no = isset($_REQUEST['vat_no']) ? $_REQUEST['vat_no'] : null;
        $service_tax_regn_no = isset($_REQUEST['service_tax_regn_no']) ? $_REQUEST['service_tax_regn_no'] : null;
        $status_code = isset($_REQUEST['status_code']) ? $_REQUEST['status_code'] : null;
        $first_letter = 'E';
        $code = $first_letter . str_pad($consuCodeNew, 4, '0', STR_PAD_LEFT);
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
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
        if ($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {            switch ($option) {
                case 'Add':
                    $this->db->query("insert into  consultant_master (consultant_code,consultant_name,address_line_1,city,pin_code,state_code,country,nature_of_service,credit_days,contact_person,default_comm_mode,phone_no,fax_no,mobile_no,email_id,pan_no,tan_no,tin_no,vat_no,service_tax_regn_no,status_code) values ('$code','$consultant_name','$address_line_1','$city','$pin_code','$state_code','$country','$nature_of_service','$credit_days','$contact_person','$default_comm_mode','$phone_no','$fax_no','$mobile_no','$email_id','$pan_no','$tan_no','$tin_no','$vat_no','$service_tax_regn_no','$status_code')");
                    session()->setFlashdata('message', 'Records Inserted Successfully !!');
                     return redirect()->to($url);
                    break;
                case 'Edit':
                    $this->db->query("update consultant_master set consultant_name='$consultant_name',address_line_1='$address_line_1',address_line_2='',address_line_3='',address_line_4='',city='$city',pin_code='$pin_code',state_code='$state_code',country='$country',nature_of_service='$nature_of_service',credit_days='$credit_days',contact_person='$contact_person',default_comm_mode='$default_comm_mode',phone_no='$phone_no',fax_no='$fax_no',mobile_no='$mobile_no',email_id='$email_id',pan_no='$pan_no',tan_no='$tan_no',tin_no='$tin_no',vat_no='$vat_no',service_tax_regn_no='$service_tax_regn_no',status_code='$status_code' WHERE consultant_code='$consultant_code'");
                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                    return redirect()->to($url);
                    break;
                case 'Delete':
                    $this->db->query("delete from consultant_master WHERE consultant_code='$consultant_code'");
                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($url);
                    break;
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {
                switch ($option) {
                    case 'Add':
                        $sql1 = "select * from state_master";
                    case 'Edit':
                        $sql1 = "select * from state_master";
                        $sql = "select * from consultant_master WHERE consultant_code='$consultant_code'";
                        break;
                    case 'View':
                        $sql1 = "select * from state_master";
                        $sql = "select * from consultant_master WHERE consultant_code='$consultant_code'";
                        break;
                    case 'Delete':
                        $sql1 = "select * from state_master";
                        $sql = "select * from consultant_master WHERE consultant_code='$consultant_code'";
                        break;
                }
                if ($option != 'Add') {
                    $data = $this->db->query($sql)->getResultArray()[0];
                    $data1 = $this->db->query($sql1)->getResultArray();
                    return view("pages/Master/mas_consultant_master", compact("url", "option", "data", "data1","redokadd","disview"));
                } else {
                    $data1 = $this->db->query($sql1)->getResultArray();
                    return view("pages/Master/mas_consultant_master", compact("url", "option", "data1","redokadd","disview"));
                }
            }
        }
        
    }
//Done By Sylvester
    public function file_uploads($option = 'list')
    {
        $sql = '';
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : null;
        $uploadCount = isset($_REQUEST['uploadCount']) ? $_REQUEST['uploadCount'] : null;
        $emp_serial_no = isset($_REQUEST['emp_serial_no']) ? $_REQUEST['emp_serial_no'] : null;
        $uploaded_by = isset($_REQUEST['uploaded_by']) ? $_REQUEST['uploaded_by'] : null;
        $uploaded_on = isset($_REQUEST['uploaded_on']) ? $_REQUEST['uploaded_on'] : null;
        $uploaded_time = isset($_REQUEST['uploaded_time']) ? $_REQUEST['uploaded_time'] : null;

        if ($this->request->getMethod() == 'post') {
            switch ($option) {
                case 'Edit':
                    $this->db->query("delete from rup_upload_files where emp_serial_no='$emp_serial_no'");
                    for ($i = 1; $i <= $uploadCount; $i++) {
                        // $files = $this->request->getFiles('userfiles'.$i);
                        $desc = isset($_REQUEST['desc' . $i]) ? $_REQUEST['desc' . $i] : null;
                        if ($_REQUEST['userfilesname' . $i] != '') {
                            $file = $_REQUEST['userfilesname' . $i];
                        } else {
                            $file = $this->request->getFile('userfiles' . $i);
                        }

                        echo '<pre>';
                        print_r($file);die;
                        $originalName = $file->getClientName();
                        $fileExtension = $file->getExtension();
                        $fileSize = $file->getSize();
                        $code = $emp_serial_no;
                        // Generate a random file name
                        $randomName = uniqid() . '.' . $fileExtension;
                        // Move the uploaded file to a directory (optional)
                        $file->move(ROOTPATH . 'public/uploads/', $randomName);

                        // Insert file info into the database

                        $db = db_connect();
                        $data = [

                            'emp_serial_no' => $emp_serial_no,
                            'file_type' => $fileExtension,
                            'file_name_system' => $randomName,
                            'file_name_original' => $originalName,
                            'file_size' => $fileSize,
                            'status_code' => 'A',
                            'uploaded_by' => $uploaded_by,
                            'uploaded_on' => $uploaded_on,
                            'uploaded_time' => $uploaded_time,
                            'description' => $desc,

                        ];
                        $db->table('rup_upload_files')->insert($data);

                    }
                    echo '<script>window.close();</script>';

                    break;
            }
        } else {
            switch ($option) {
                case 'Edit':
                    $sql = "select * from rup_upload_files where emp_serial_no='$code'";
                    break;
            }

        }
        $data = $this->db->query($sql)->getResultArray();
        return view("pages/Master/file_uploads", compact("url", "option", "code", "data"));
    }
//end
}
