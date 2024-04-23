<?php

namespace App\Controllers;
use App\Models\User;

class ApiController extends BaseController
{
    public function __construct() {
        $this->db = \config\database::connect();
    }

    public function username($user_id = null)
    { 
   
        $data = $this->db->query("select system_user.user_name as username, system_user.role,role_permission_details.permission from system_user INNER JOIN role_permission_details ON system_user.role=role_permission_details.role where system_user.user_id = '$user_id'")->getResultArray()[0];
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($data));
    }
    public function checkUserName($user_id = null)
    {
        $data = $this->db->query("select UPPER(user_id) as user_id from system_user where user_id = '$user_id'")->getResultArray();
        
        try {
            $data=$data[0];
        } catch (\Exception $e) {
            $data = ['user_id' => ''];
        }
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($data));
    }

    public function lookup_byID ($selectCase = null, $params = null) {
        $temp = $data = [];
        $params = str_replace( "@All", "%", $params);
        foreach(explode('&', $params) as $param) {
            $arr = explode('=', $param);  $temp += [$arr[0] => $arr[1]];
        } $params = (object) $temp;
        
        switch($selectCase) {
            case 'serial_no': 
                $sql = "select a.branch_code, a.matter_code, a.alert_disp_ind, concat(b.matter_desc1, ', ', b.matter_desc2) as matter_desc, a.status_code from case_header a INNER JOIN fileinfo_header b ON a.matter_code = b.matter_code where a.serial_no = $params->code AND a.alert_disp_ind='Yes'";
                break;

            case 'client_code': 
                $sql = "select client_name from client_master where client_code = '$params->code' ";
                break;
            
            case 'matter_code':
                $sql = "SELECT CONCAT(a.matter_desc1, ' : ', a.matter_desc2) AS matter_desc, a.client_code, b.client_name, a.initial_code, a.judge_name, a.status_code FROM fileinfo_header a JOIN client_master b ON a.client_code = b.client_code WHERE a.matter_code = '$params->code'";
                break;

            case 'court_code':
                $sql = "select code_desc from code_master where type_code = '001' and code_code = '$params->code' " ;
                break;

            case 'initial_code':
                $sql = "select initial_name,initial_code from initial_master where initial_code = '$params->code' " ;
                break;

            case 'status_code':
                $sql  = "select code_desc from code_master where type_code = '008' and code_code = '$params->code' " ;
                break;
    
            case 'payee_code':
                $sql = "select distinct payee_payer_name from ledger_trans_hdr where payee_payer_type = '$params->payee_payer_type' and payee_payer_code = '$params->code' ";
                break;
        
            case 'counsel_code':
                $sql = "select associate_name from associate_master where associate_code = '$params->code' and associate_type = '001' ";
                break;
        
            case 'attention_code':
                $sql = "select attention_name from client_attention where attention_code = '$params->code'";
            
            case 'attention_code_with_join':
                $sql = "select a.attention_name, a.designation, b.address_line_1,b.address_line_2,b.address_line_3,b.address_line_4,concat(b.city,' - ',b.pin_code),a.attention_code,a.address_code from client_attention a left outer join client_address b on b.address_code = a.address_code where a.attention_code='$params->code' order by a.attention_name limit 0,40";
                break;

            case 'bill_serial_code':
                $sql = "select a.branch_code, a.matter_code, concat(b.matter_desc1,if(b.matter_desc1 != '',' : ',''),b.matter_desc2) matter_desc,a.client_code,c.client_name,a.status_code from billinfo_header a, fileinfo_header b, client_master c where a.serial_no = '$params->code' and a.matter_code = b.matter_code and a.client_code = c.client_code  " ;
                break;
                
            case 'final_bill_serial_code':
                    $sql = "select a.serial_no ref_bill_serial_no, a.branch_code, a.matter_code, concat(b.matter_desc1,if(b.matter_desc1 != '',' : ',''),b.matter_desc2) matter_desc,a.client_code,c.client_name,d.serial_no,d.status_code,(ifnull(a.realise_amount_inpocket,0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0)) realised_amount from bill_detail a, fileinfo_header b, client_master c, billinfo_header d  where a.fin_year = '$bill_year' and a.bill_no = '$bill_no' and a.matter_code = b.matter_code and a.client_code = c.client_code and a.serial_no = d.ref_bill_serial_no " ;
                    break;
    
            case 'get_counsel_result':
                $sql = "SELECT a.associate_name,a.link_associate_code,b.associate_name link_associate_name FROM associate_master a left outer join associate_master b on b.associate_code = a.link_associate_code WHERE a.associate_code = '$params->code' AND a.associate_type ='001'" ;
                break;

            case 'counsel_type':
                $sql = "select a.code_desc,a.code_code from code_master a where a.code_code='$params->code'  AND a.type_code='009' " ;
                break;
            case 'client_group':
                $sql = "select a.code_desc,a.code_code from code_master a where a.code_code='$params->code'" ;
                break;

            case 'get_clerk_result':
                $sql = "SELECT associate_name,associate_code FROM associate_master WHERE associate_code = '$params->code' AND associate_type ='002'" ;
                break;

            case 'get_peon_result':
                $sql = "SELECT associate_name,associate_code FROM associate_master WHERE associate_code = '$params->code' AND associate_type ='005'" ;
                break;
            case 'matter_code_des':
                    if($params->code!="null" && $params->code2!="null" && $params->code3!="null"){
                    $sql = "select a.matter_type_code,a.matter_type_desc,b.matter_sub_type_desc,b.matter_sub_type_code,c.matter_sub_sub_type_desc,c.matter_sub_sub_type_code from matter_type a, matter_sub_type b,matter_sub_sub_type c where  a.`matter_type_code`='$params->code' AND c.`matter_sub_type_code`='$code2' AND c.`matter_sub_sub_type_code`='$code3'";
                    }
                    if($params->code!="null" && $params->code2!="null" && $params->code3=="null")
                    {
                        $sql = "select a.matter_type_code,a.matter_type_desc,b.matter_sub_type_desc,b.matter_sub_type_code from matter_type a, matter_sub_type b where  a.`matter_type_code`='$params->code' AND b.`matter_sub_type_code`='$code2'";
                    }
                    if($params->code!="null" && $params->code2=="null" && $params->code3=="null")
                    {
                        $sql = "select a.matter_type_code,a.matter_type_desc from matter_type a, matter_sub_type b,matter_sub_sub_type c where  a.`matter_type_code`='$params->code' ";
                    }
                break;
            case 'activity_code':
                $sql = "SELECT activity_desc,activity_code FROM activity_master WHERE activity_code = '$params->code'" ;
                $data = $this->db->query($sql)->getResultArray();
                break;

            case 'corrp_addr_code':
                $sql="select a.client_name,b.address_line_1,b.address_line_2, b.address_line_3,b.city,b.pin_code,c.state_name,b.country,b.isd_code,b.std_code,b.phone_no,b.fax_no,b.mobile_no,b.email_id,b.client_gst, b.address_code as corrp_addr_code from client_master a, client_address b, state_master c where a.client_code = b.client_code and b.state_code = c.state_code AND b.address_code='$params->code'";
                break;
            case 'corrp_attn_code':
                $sql="select a.attention_name, a.designation,a.sex,a.phone_no,a.fax_no,a.mobile_no,a.email_id,a.attention_code as corrp_attn_code from client_attention a WHERE a.`attention_code`='$params->code'";
                break;
            case 'act_group_code':
                $sql="select a.code_desc,a.code_code from code_master a where a.type_code='023' AND a.code_code='$params->code'";   
                break;
            case 'bspl_desc':
                $sql="select a.client_name,a.client_code from client_master a where a.client_code='$params->code'";  
                break;
            case 'relatedmatter_code':
                $sql="select concat(a.matter_desc1,a.matter_desc2) mat_des,a.matter_code from fileinfo_header a where a.matter_code='$params->code'";
                break;
            case 'bill_attn_code':
                $sql="select a.attention_name, a.designation, a.sex,a. phone_no,a.fax_no,a.mobile_no,a.email_id,a.attention_code as bill_attn_code,a.address_code as bill_addr_code from client_attention a where a.attention_code ='".$params->code."'";
                break;
            case 'bill_addr_code':
                $sql="select a.client_name,b.address_line_1,b.address_line_2, b.address_line_3,b.city,b.pin_code,c.state_name,b.country,b.isd_code,b.std_code,b.phone_no,b.fax_no,b.mobile_no,b.email_id, b.address_code as bill_addr_code from client_master a, client_address b, state_master c where a.client_code = b.client_code and b.state_code = c.state_code and b.address_code ='".$params->code."'";
                break;
            case 'main_ac_code':
                $sql="select a.main_ac_desc,a.main_ac_code,a.sub_ac_ind from account_master a where a.status_code = 'Active' AND a.main_ac_code='$params->code'";
                break;
            case 'designation':
                $sql="select designation_name,designation_code from designation_master where designation_code='$params->code'";
                break;
            case 'department':
                $sql="select department_name,department_code from department_master where department_code='$params->code'";
                break;
            case 'branch':
                $sql="select branch_code,branch_name from branch_master where branch_code='$params->code'";
                break;
            case 'supplier_code':
                $sql="select supplier_code,supplier_name,pan_no from supplier_master where supplier_code='$params->code'";
                break;
            case 'activity_code2':
                    $sql="select a.code_desc,a.code_code from code_master a where a.type_code='033' and code_code='$params->code'";
                break;
            case 'narration_type':
                    $sql="select a.code_desc,a.code_code from code_master a where a.type_code = '005' and code_code='$params->code'";
                break;
            case 'tax_account_code':
                    $sql="select a.main_ac_desc,a.main_ac_code from account_master a where  main_ac_code='$params->code'";
                break;
            case 'sub_ac_code':
                    $sql="select a.sub_ac_desc,a.sub_ac_code from sub_account_master a where a.sub_ac_code='$params->code'";
                break;
            case 'stenographer_code':
                $sql = "SELECT associate_name FROM associate_master WHERE associate_code = '$params->code' and associate_type = '004'";
                break;
            case 'payee_code_finance':
                $sql = "SELECT distinct payee_payer_name FROM tds_certificate WHERE payee_payer_type = '$params->payee_type' and payee_payer_code = '$params->code' " ;
                break;
            case 'sub_ledger_main_account':
                $sql = "SELECT main_ac_desc FROM account_master WHERE main_ac_code = '$params->code' and sub_ac_ind = 'Y'  " ;
                break;
            case 'advance_payee':
                $sql = "SELECT distinct payee_payer_name from advance_details where payee_payer_type = '$params->payee_type' and payee_payer_code = '$params->code' and advance_type = '$params->advance_type' " ;
                break;
            case 'ceo_code':
                $sql = "SELECT ceo_code, name_desc FROM mis_name_master where ceo_code = '$params->code'" ;
                break;
            case 'exps_code':
                $sql = "SELECT exps_code, exps_desc FROM mis_exps_master where exps_code = '$params->code'" ;
                break;
            case 'voucher_details':
                $sql = "SELECT * from ledger_trans_hdr where branch_code = '$params->branch_code' and fin_year = '$params->fin_year' and daybook_code = '$params->daybook_code' and doc_no = '$params->code' and doc_type = 'RV'" ;
                break;
            case 'mis_client_group':
                $sql = "select code_desc from code_master where code_code = '$params->code' and type_code = '022'" ;
                break;
            case 'mis_client':
                $sql = "select a.client_name,a.client_group_code,b.code_desc from client_master a, code_master b where a.client_code = '$params->code' and a.client_group_code = b.code_code and b.type_code = '022' " ;
                break;
        }
        $data = $this->db->query($sql)->getResultArray();
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($data));
    }

    public function lookup() {
        $data = display_list();
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($data));
    }

    function duplicate_code_check($main_ac_code,$user_option,$mode,$error_msg) {
        $option['option']=$user_option;
       if($main_ac_code != '')
       {
        if($mode == "getDuplMainAcc")
        {
                $data = $this->db->query("select count(main_ac_code) as totalRow from account_master where main_ac_code='$main_ac_code'")->getResultArray()[0];
                $data2 = $this->db->query("select max(main_ac_code) as `maxValue` from account_master")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$data2,$option)));
        }
        if($mode == "getTotNameCount")
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = substr($client_name,0,1);
                $first_str    = $first_letter.'%';
                $where = "WHERE client_code LIKE '$first_str'";
                    $data = $this->db->query("select substring(max(client_code),2)+1 as `max_id` from client_master $where")->getResultArray()[0];
                //   / echo "select substring(max(client_code),2) as `max_id` from client_master $where";die;
                    // echo '<pre>'; print_r($data); echo '</pre>';die;
                   // $data2 = $this->db->query("select max(main_ac_code) as `maxValue` from account_master")->getResultArray()[0];
                    return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option)));
            }
            if($mode == "getAcode")
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = substr($client_name,0,1);
                $first_str    = $first_letter.'%';
                $where = "WHERE associate_code LIKE '$first_str'";
                    $data = $this->db->query("select substring(max(associate_code),2) as `max_id` from associate_master $where")->getResultArray()[0];
                   // echo '<pre>'; print_r($data); echo '</pre>';die;
                   // $data2 = $this->db->query("select max(main_ac_code) as `maxValue` from account_master")->getResultArray()[0];
                    return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option)));
            }
            if($mode=='getTotBankCount')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = substr($client_name,0,1);
                $first_str    = $first_letter.'%';
                $where = "WHERE bank_code LIKE '$first_str'";
                    $data = $this->db->query("select ifnull(max(substring(bank_code,2,3)),0) as `max_id` from bank_master $where")->getResultArray()[0];
                    //echo '<pre>'; print_r($data); echo '</pre>';die;
                   // $data2 = $this->db->query("select max(main_ac_code) as `maxValue` from account_master")->getResultArray()[0];
                    return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option)));
            }
            if($mode=='getTotCompanyCount')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = 'C';
                $first_str    = $first_letter.'%';
                $where = "WHERE company_code LIKE '$first_str'";
                    $data = $this->db->query("select substring(max(company_code),2) as `max_id` from company_master $where")->getResultArray()[0];
                    //echo '<pre>'; print_r($data); echo '</pre>';die;
                   // $data2 = $this->db->query("select max(main_ac_code) as `maxValue` from account_master")->getResultArray()[0];
                    return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option)));
            }
            if($mode=='getTotBranchCount')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = 'B';
                $first_str    = $first_letter.'%';
                $where = "WHERE branch_code LIKE '$first_str'";
                    $data = $this->db->query("select substring(max(branch_code),2) as `max_id` from branch_master $where")->getResultArray()[0];
                    //echo '<pre>'; print_r($data); echo '</pre>';die;
                   // $data2 = $this->db->query("select max(main_ac_code) as `maxValue` from account_master")->getResultArray()[0];
                    return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option)));
            }
            if($mode=='getTotEmpCount')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = 'E';
                $first_str    = $first_letter.'%';
                $where = "WHERE employee_id LIKE '$first_str'";
                    $data = $this->db->query("select substring(max(employee_id),2) as `max_id` from empmas $where")->getResultArray()[0];
                    return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option)));
            }
            if($mode=='type_code')
            {
                $data = $this->db->query("select type_desc from type_master where type_code = '$main_ac_code'")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='getCompanyDetails')
            {
                $data = $this->db->query("select pan_no, tan_no from company_master where company_code = '$main_ac_code' ")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='masSubAcc')
            {
               
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = substr($client_name,0,1);
                $first_str    = $first_letter.'%';
                $where = "WHERE sub_ac_code LIKE '$first_str'";
                $data = $this->db->query("select substring(max(sub_ac_code),2) as `max_id` from sub_account_master $where")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='getsupNo')
            {
               
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = 'S';
                $first_str    =$first_letter.'%';
                $where = "WHERE supplier_code LIKE '$first_str'";
                $data = $this->db->query("select substring(max(supplier_code),2) as `max_id` from supplier_master $where")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='getTaxNo')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = 'T';
                $first_str    =$first_letter.'%';
                $where = "WHERE tax_code LIKE '$first_str'";
                $data = $this->db->query("select substring(max(tax_code),2) as `max_id` from tax_master $where")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='getactiNo')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = substr($client_name,0,1);
                $first_str    =$first_letter.'%';
                $where = "WHERE activity_code LIKE '$first_str'";   
                $data = $this->db->query("select substring(max(activity_code),2) as `max_id` from activity_master $where")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='getExpNo')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = substr($client_name,0,1);
                $first_str    =$first_letter.'%';
                $where = "WHERE expense_code LIKE '$first_str'";   
                $data = $this->db->query("select substring(max(expense_code),2) as `max_id` from expense_master $where")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='getotherMasterNo')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = 'O';
                $first_str    =$first_letter.'%'; 
                $where = "WHERE other_payee_code LIKE '$first_str'";   
                $data = $this->db->query("select substring(max(other_payee_code),2) as `max_id` from other_payee_master $where")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='getnameNo')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = 'P';
                $first_str    =$first_letter.'%'; 
                $where = "WHERE ceo_code LIKE '$first_str'";   
                $data = $this->db->query("select substring(max(ceo_code),2)+1 as `max_id` from mis_name_master $where")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='getexpsNo')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = 'E';
                $first_str    =$first_letter.'%'; 
                $where = "WHERE exps_code LIKE '$first_str'";   
                $data = $this->db->query("select substring(max(exps_code),2) as `max_id` from mis_exps_master $where")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
            if($mode=='getConsuNo')
            {
                $client_name  = strtoupper(stripslashes($main_ac_code));
                $first_letter = 'U';
                $first_str    =$first_letter.'%'; 
                $where = "WHERE consultant_code LIKE '$first_str'";   
                $data = $this->db->query("select substring(max(consultant_code),2) as `max_id` from consultant_master $where")->getResultArray()[0];
                return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$option))); 
            }
           
       }
    }

    public function getMatterValue($matter_code,$error_msg) {
        if($matter_code != '') {
            $matter_code = $matter_code;
            $row_details = $this->db->query("select * from fileinfo_header where matter_code='$matter_code'")->getResultArray();
            $row_num = count($row_details);
            
            if($row_num == 1)
            {
                $row_details = $row_details[0];
                $matter_code        = $row_details['matter_code'];
                $matter_desc1       = $row_details['matter_desc1'];
                $matter_desc2       = $row_details['matter_desc2'];
                
                $mat_description    = trim($matter_desc1.' '.$matter_desc2);                                                                          
                $client_code        = $row_details['client_code'];
                $court_code         = $row_details['court_code'];
                $appearing_for_code = $row_details['appearing_for_code'];
                $initial_code       = $row_details['initial_code'];
                $subject_desc       = $row_details['subject_desc'];
                $reference_desc     = $row_details['reference_desc'];
                
                
                $row = $this->db->query("SELECT client_name FROM client_master where client_code = '$client_code'")->getResultArray()[0];
                // return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($row));
                //$row = $res->fetchRow();
                $client_name = $row['client_name']; 

                $row = $this->db->query("select code_desc court_name from code_master where type_code = '001' and code_code = '$court_code' ")->getRowArray();
                $court_name = $row['court_name'];

                $row = $this->db->query("SELECT a.associate_name counsel_name, b.initial_code counsel_code FROM associate_master a, fileinfo_counsels b where b.record_code = '7' and a.associate_code = b.initial_code and b.matter_code = '$matter_code'")->getRowArray();
                $counsel_name = isset($row['counsel_name']) ? $row['counsel_name'] : '';
                $counsel_code = isset($row['counsel_code']) ? $row['counsel_code'] : '';

                $row = $this->db->query("SELECT ifnull(count(matter_code),0) as no_of_case FROM  fileinfo_other_cases where matter_code = '$matter_code'")->getRowArray();
                $no_of_case = isset($row['no_of_case']) ? $row['no_of_case'] : '';
                
                $row = $this->db->query("SELECT max(serial_no) srl,max(activity_date) acty_date ,next_fixed_for FROM case_header WHERE matter_code = '$matter_code'
                               GROUP BY next_fixed_for ORDER BY srl desc, acty_date DESC LIMIT 0,1 ")->getRowArray();
                $prev_fixed_for = isset($row['next_fixed_for']) ? $row['next_fixed_for'] : '';
                $prev_date      = isset($row['acty_date']) ? date_conv($row['acty_date']) : '';
                
                $row = $this->db->query("select remarks last_remark from case_header where matter_code = '$matter_code' order by activity_date desc")->getRowArray();
                $last_remark = isset($row['last_remark']) ? $row['last_remark'] : '';

                $row = $this->db->query("SELECT code_desc FROM code_master where type_code = '004' and code_code = '$appearing_for_code'")->getRowArray();
                $appear_for = isset($row['code_desc']) ? $row['code_desc'] : '';
                
                $row = $this->db->query("SELECT a.address_code, a.state_code, b.billing_addr_code, c.state_code, c.state_name, c.gst_zone_code 
                					FROM client_address a JOIN fileinfo_header b ON a.address_code = b.billing_addr_code JOIN state_master c ON a.state_code = c.state_code
									WHERE b.matter_code = '$matter_code'")->getRowArray();
                $state_name = isset($row['state_name']) ? $row['state_name'] : '';

                $data = [
                    'no_of_case' => $no_of_case,
                    'last_remark' => $last_remark,
                    'state_name' => $state_name,
                    'subject_desc' => $subject_desc,
                    'reference_desc' => $reference_desc,
                    'court_name' => $court_name,
                    'court_code' => $court_code,
                    'counsel_name' => $counsel_name,
                    'counsel_code' => $counsel_code,
                    'prev_fixed_for' => $prev_fixed_for,
                    'prev_date' => $prev_date,
                    'mat_description' => $mat_description,
                    'appear_for' => $appear_for,
                ];
                
                
            }
        }
        //return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode([$data]));
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data)));

    }

    public function getBillInfo($serial_no,$error_msg) {
        if($serial_no != '') {
            $serial_no = $serial_no;
            $qry = $this->db->query("SELECT a.matter_code,a.branch_code,trim(concat(b.matter_desc1,' ',b.matter_desc2)) mat_desc,a.client_code,c.client_name,a.reference_desc,a.subject_desc,a.other_case_desc,concat(d.fin_year,'/',d.bill_no) bill_no,a.bill_amount_inpocket,a.bill_amount_outpocket,a.bill_amount_counsel,a.court_fee_bill_ind
                FROM fileinfo_header b,client_master c,billinfo_header a left outer join bill_detail d on d.serial_no = a.ref_bill_serial_no
                WHERE a.serial_no = '$serial_no'
                AND a.matter_code = b.matter_code
                AND a.client_code = c.client_code ")->getResultArray() ;
            $num_row = count($qry);
            if($num_row > 0)
            {
                foreach($qry as $row){

                    $matter_code           = $row['matter_code'];
                    $branch_code           = $row['branch_code'];
                    $mat_desc              = $row['mat_desc'];
                    $client_code           = $row['client_code'];
                    $client_name           = $row['client_name'];
                    $reference_desc        = $row['reference_desc'];
                    $subject_desc          = $row['subject_desc'];
                    $other_case_desc       = isset($row['other_case_desc']) ? $row['other_case_desc'] : '';
                    $bill_no               = $row['bill_no'];
                    $bill_amount_inpocket  = $row['bill_amount_inpocket'];
                    $bill_amount_outpocket = $row['bill_amount_outpocket'];
                    $bill_amount_counsel   = $row['bill_amount_counsel'];
                    $bill_amount           = number_format(($bill_amount_inpocket + $bill_amount_outpocket + $bill_amount_counsel),2,'.','');
                    $court_fee_bill_ind    = isset($row['court_fee_bill_ind']) ? $row['court_fee_bill_ind'] : '';

                    $data = [
                        'matter_code' => $matter_code,
                        'branch_code' => $branch_code,
                        'mat_desc' => $mat_desc,
                        'client_code' => $client_code,
                        'client_name' => $client_name,
                        'reference_desc' => $reference_desc,
                        'subject_desc' => $subject_desc,
                        'other_case_desc' => $other_case_desc,
                        'bill_no' => $bill_no,
                        'bill_amount' => $bill_amount,
                        'court_fee_bill_ind' => $court_fee_bill_ind,
                    ];
                }
            }
        }
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data)));
    }

    public function getMatterInfo($matter_code,$error_msg) {
        if($matter_code != '') {
            $matter_code = $matter_code;
            $qry = $this->db->query("SELECT a.matter_code ,trim(concat(a.matter_desc1,' ',a.matter_desc2)) mat_desc ,a.client_code ,b.client_name
                                    FROM fileinfo_header a ,client_master b
                                    WHERE a.matter_code = '$matter_code'
                                    AND a.client_code = b.client_code")->getResultArray() ;
            $num_row = count($qry);
            if($num_row > 0)
            {
                foreach($qry as $row){

                    $matter_code = $row['matter_code'];
                    $mat_desc    = $row['mat_desc'];
                    $client_code = $row['client_code']; 
                    $client_name = $row['client_name']; 
                    $data = [];

                    $oth_qry = $this->db->query("SELECT b.matter_code, b.row_no, ifnull(b.case_no,'') case_no, ifnull(b.subject_desc,'') subject_desc, concat(ifnull(b.case_no,''),' :: ',ifnull(b.subject_desc,'')) case_no_subject_desc
                            FROM fileinfo_other_cases b
                            WHERE b.matter_code = '$matter_code'")->getResultArray();
                    $oth_case_cnt = count($oth_qry);
                    if($oth_case_cnt > 0)
                    {
                        foreach($oth_qry as $row)
                        {
                            $case_no_subject_desc = $row['case_no_subject_desc'];
                            $row_no = $row['row_no'];
                            $case_no = $row['case_no'];
                            $subject_desc = $row['subject_desc'];
                        }
                        $data = [
                            'matter_code' => $matter_code,
                            'mat_desc' => $mat_desc,
                            'client_code' => $client_code,
                            'client_name' => $client_name,
                            'case_no_subject_desc' => $case_no_subject_desc,
                            'row_no' => $row_no,
                            'case_no' => $case_no,
                            'subject_desc' => $subject_desc,
                            'oth_case_cnt' => $oth_case_cnt,
                            'oth_qry' => $oth_qry,
                        ];
                    } else {
                        $data = [
                            'status' => 'failed',
                            'msg' => 'No Records Found !!'
                        ];
                    }

                }
            }
        }
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data)));
    }

    public function myFinalBillSerial($bill_no, $bill_year) {
        if($bill_no != '' && $bill_year != '') {
            $bill_no = $bill_no;
            $bill_year = $bill_year;

            $qry1 = $this->db->query("select a.serial_no ref_bill_serial_no, a.branch_code, a.matter_code, concat(b.matter_desc1,if(b.matter_desc1 != '',' : ',''),b.matter_desc2) matter_desc,a.client_code,c.client_name,d.serial_no,d.status_code,(ifnull(a.realise_amount_inpocket,0)+ifnull(a.realise_amount_outpocket,0)+ifnull(a.realise_amount_counsel,0)) realised_amount 
                                    from bill_detail a, fileinfo_header b, client_master c, billinfo_header d 
                                    where a.fin_year = '$bill_year' and a.bill_no = '$bill_no' 
                                    and a.matter_code = b.matter_code and a.client_code = c.client_code and a.serial_no = d.ref_bill_serial_no ")->getResultArray() ;
            $num_row = count($qry1);
            $qry = $qry1[0];
                $data = [
                    'ref_bill_serial_no' => $qry['ref_bill_serial_no'],
                    'serial_no'          => $qry['serial_no'],
                    'matter_code'        => $qry['matter_code'],
                    'matter_desc'        => $qry['matter_desc'],
                    'client_code'        => $qry['client_code'],
                    'client_name'        => $qry['client_name'],
                    'status_code'        => $qry['status_code'],
                    'realised_amount'    => $qry['realised_amount'],
                    'num_row'            => $num_row
                ];
                
        }
        
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data)));
    }

    public function myReturnNo($qtr_no, $brch_code, $finc_year) {
        if($brch_code != '' && $finc_year != '' && $qtr_no != '') {
            $qtr_no = $qtr_no;
            $brch_code = $brch_code;
            $finc_year = $finc_year;

            $qry1 = $this->db->query("select tds_return_no from tds_return_detail where branch_code = '$brch_code' and fin_year = '$finc_year' and quarter_no = '$qtr_no' ")->getResultArray() ;
            $num_row = count($qry1);
            $qry = $qry1[0];
                $data = [
                    'num_row' => $num_row,
                    'tds_return_no' => $qry['tds_return_no'],
                ];
        }
        
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data)));
    }

    function check_case_number($main_ac_code,$after_slash,$code,$msg) {
        if($after_slash!='undefined')
        {
            $comb_letter=$main_ac_code.'/'.$after_slash;
        }
        else{
            $comb_letter=$main_ac_code;
        }
        $where = "WHERE court_code = '$code' AND case_no ='$comb_letter'"; 
        $data = $this->db->query("select count(case_no) as count from fileinfo_header $where")->getResultArray()[0];
      
        
         return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data))); 
    }
    
    function mymattercheck($matter_code,$after_slash,$code){
     $my_sql1  = $this->db->query("select * from fileinfo_header where matter_code = '$matter_code' ")->getResultArray();
     if (!empty($my_sql1)) {$my_sql1 = $my_sql1[0];} else {$my_sql1= [];}
     $client_qry  = $this->db->query("select client_name,client_code  from client_master  where client_code  = '$my_sql1[client_code]'")->getResultArray();
     if (!empty($client_qry)) {$client_qry = $client_qry[0];} else {$client_qry= [];}
     $initial_qry = $this->db->query("select initial_name,initial_code from initial_master where initial_code = '$my_sql1[initial_code]'")->getResultArray();
     if (!empty($initial_qry)) {$initial_qry = $initial_qry[0];} else {$initial_qry= [];}
     $court_qry   = $this->db->query("select code_desc  as court_name,code_code as court_code  from code_master    where code_code    = '$my_sql1[court_code]' and type_code = '001' ")->getResultArray();
     if (!empty($court_qry)) {$court_qry = $court_qry[0];} else {$court_qry= [];}
     $status_qry  = $this->db->query("select code_desc  as pesent_desc  from code_master    where code_code    = '$my_sql1[status_code]' and type_code = '008' ")->getResultArray();
     if (!empty($status_qry)) { $status_qry = $status_qry[0];} else {$status_qry= [];}
     //echo '<pre>'; print_r($status_qry);die;
     //  $data = $this->db->query("select count(case_no) as count from fileinfo_header $where")->getResultArray()[0];
      
        
         return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($my_sql1,$client_qry,$initial_qry,$court_qry,$status_qry))); 
    }
    
    /* Added by Surajit Naskar on 22-01-2024 */
    public function getCourierExpensesById($serial_no = null) {
    $my_sql1   = "select * from courier_expense where serial_no = '$serial_no' " ;
    $my_qry1  = $this->db->query($my_sql1)->getResultArray();
    $my_cnt1  = count($my_qry1) ; 
    $my_arr1  = $my_qry1[0];
 
    if ($my_cnt1 == 0) {
        $data = [
            "status" => "failed",
            "massage" => "Details Not Found !!"
        ];
    } else {
        $my_arr1['consignment_note_date'] = date_conv($my_arr1['consignment_note_date']); 
        $matrcd = $my_arr1['matter_code'];
        $clntcd = $my_arr1['client_code'];
        $empid = $my_arr1['employee_id'];

        $client_qry = $this->db->query("select client_name from client_master where client_code = '$clntcd' ")->getRowArray();
        $clntnm     = empty($client_qry) ? '' : $client_qry['client_name']  ; 
        $matter_qry = $this->db->query("select concat(matter_desc1,' : ',matter_desc2) matter_desc from fileinfo_header where matter_code = '$matrcd'")->getRowArray();
        $matrnm     = empty($matter_qry) ? '' : $matter_qry['matter_desc']  ; 
        $empmas_qry = $this->db->query("select employee_name from empmas where employee_id = '$empid' ")->getRowArray();
        $empname    = empty($empmas_qry) ? '' : $empmas_qry['employee_name'];

        $data = [
            "courier_expense" => $my_arr1,
            "other_info" => [
                "client_name" => $clntnm,
                "matter_desc" => $matrnm,
                "employee_name" => $empname,
            ],
            "status" => 'sucess',
        ];
    }
    return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data))); 
}

    /* Added by Surajit Naskar on 22-01-2024 */
    public function get_finance_details($advance_serial_no = null, $mode = null) {
        if ($mode == 'AdvanceSerial') {      
           $advance_sql = "select * from advance_details where serial_no = '$advance_serial_no' " ;
           $advance_arr = $this->db->query($advance_sql)->getRowArray();
           
            if (!empty($advance_arr)) {
                $client_code  = $advance_arr['client_code'] ;
                $matter_code  = $advance_arr['matter_code'] ;
                $advance_amt  = $advance_arr['gross_amount'] ;
                $adjusted_amt = number_format(($advance_arr['adjusted_amount'] + $advance_arr['booked_amount']),2,'.','') ;
                $balance_amt  = number_format(($advance_arr['gross_amount'] - $advance_arr['adjusted_amount'] - $advance_arr['booked_amount']),2,'.','') ;
                
                $client_qry   = $this->db->query("select client_name from client_master where client_code = '$client_code' ")->getRowArray();
                $client_name  = $client_qry['client_name'] ; 
                $matter_qry   = $this->db->query("select concat(matter_desc1,':',matter_desc2) matter_desc, initial_code from fileinfo_header where matter_code = '$matter_code' ")->getRowArray();
                $matter_desc  = isset($matter_qry['matter_desc']) ? $matter_qry['matter_desc'] : '' ; 
                $initial_code = isset($matter_qry['initial_code']) ? $matter_qry['initial_code'] : '' ; 
              
                if($matter_code == $client_code) { $matter_desc = 'ADVANCE' ; }
                $data = [
                    'status' => true,
                    'client_code' => $client_code,
                    'client_name' => $client_name,
                    'matter_code' => $matter_code,
                    'matter_desc' => $matter_desc,
                    'advance_amt' => $advance_amt,
                    'adjusted_amt' => $adjusted_amt,
                    'balance_amt' => $balance_amt,
                    'initial_code' => $initial_code,
                ];
            } else {
                $data = [ 'status' => false, 'message' => 'Advance Serial Not Found!!' ];
            }
            return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data))); 
        } else if ($mode == 'CheckAdvanceSerial') {
            $my_sql1  = "select * from advance_details where serial_no = '$advance_serial_no' " ;
            $my_arr1  = $this->db->query($my_sql1)->getRowArray();

            if (empty($my_arr1)) {
                $data = [ 'status' => false, 'message' => 'Advance Details Not Found!!' ];
            } else {
                $data = [
                    'status' => true,
                    'client_code' => $my_arr1['client_code'],
                    'client_name' => getClientName($my_arr1['client_code']),
                    'matter_code' => $my_arr1['matter_code'],
                    'matter_desc' => getMatterDesc($my_arr1['client_code'], $my_arr1['matter_code']),
                    'gross_amount' => $my_arr1['gross_amount'],
                    'adjusted_amount' => $my_arr1['adjusted_amount'],
                    'booked_amount' => $my_arr1['booked_amount'],
                    'advance_amount' => ($my_arr1['adjusted_amount'] + $my_arr1['booked_amount']),
                    'balance_amount' => $my_arr1['gross_amount'] - ($my_arr1['adjusted_amount'] + $my_arr1['booked_amount']),
                ];
            } 
            return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data))); 

        } else if ($mode == 'CheckBillDetails') {
            $clntcd = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null ;
            $matrcd = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null ;
            $billyr = isset($_REQUEST['bill_year'])?$_REQUEST['bill_year']:null ;
            $billno = isset($_REQUEST['bill_no'])?$_REQUEST['bill_no']:null ;

            $my_sql1  = "select * from bill_detail where fin_year = '$billyr' and bill_no = '$billno' and cancel_ind IS NULL " ;
            $my_arr1  = $this->db->query($my_sql1)->getRowArray();

            if(!empty($my_arr1)) {
                $clnt_code      =  $my_arr1['client_code'] ;   
                $matr_code      =  $my_arr1['matter_code'] ;   
                $bill_amt       =  $my_arr1['bill_amount_inpocket']    + $my_arr1['bill_amount_outpocket']    + $my_arr1['bill_amount_counsel']     + $my_arr1['service_tax_amount'];   
                $adv_amt        =  $my_arr1['advance_amount_inpocket'] + $my_arr1['advance_amount_outpocket'] + $my_arr1['advance_amount_counsel']  + $my_arr1['advance_amount_service_tax'];   
                $real_amt       =  $my_arr1['realise_amount_inpocket'] + $my_arr1['realise_amount_outpocket'] + $my_arr1['realise_amount_counsel']  + $my_arr1['realise_amount_service_tax'];   
                $defc_amt       =  $my_arr1['deficit_amount_inpocket'] + $my_arr1['deficit_amount_outpocket'] + $my_arr1['deficit_amount_counsel']  + $my_arr1['deficit_amount_service_tax'];   
                $book_amt       =  $my_arr1['booked_amount_inpocket']  + $my_arr1['booked_amount_outpocket']  + $my_arr1['booked_amount_counsel']   + $my_arr1['booked_amount_service_tax'];   
                $os_amt         =  $bill_amt - ($adv_amt + $real_amt + $defc_amt + $book_amt) ;
                
                $iposamt_stax   =  $my_arr1['bill_amount_inpocket_stax'] - ($my_arr1['realise_amount_inpocket_stax'] + $my_arr1['deficit_amount_inpocket_stax'] + $my_arr1['booked_amount_inpocket_stax']);
                $oposamt_stax   =  $my_arr1['bill_amount_outpocket_stax'] - ($my_arr1['realise_amount_outpocket_stax'] + $my_arr1['deficit_amount_outpocket_stax'] + $my_arr1['booked_amount_outpocket_stax']);
                $cnosamt_stax   =  $my_arr1['bill_amount_counsel_stax'] - ($my_arr1['realise_amount_counsel_stax'] + $my_arr1['deficit_amount_counsel_stax'] + $my_arr1['booked_amount_counsel_stax']);
                $iposamt_ntax   =  $my_arr1['bill_amount_inpocket_ntax'] - ($my_arr1['realise_amount_inpocket_ntax'] + $my_arr1['deficit_amount_inpocket_ntax'] + $my_arr1['booked_amount_inpocket_ntax']);
                $oposamt_ntax   =  $my_arr1['bill_amount_outpocket_ntax'] - ($my_arr1['realise_amount_outpocket_ntax'] + $my_arr1['deficit_amount_outpocket_ntax'] + $my_arr1['booked_amount_outpocket_ntax']);
                $cnosamt_ntax   =  $my_arr1['bill_amount_counsel_ntax'] - ($my_arr1['realise_amount_counsel_ntax'] + $my_arr1['deficit_amount_counsel_ntax'] + $my_arr1['booked_amount_counsel_ntax']);
                
                $iposamt        = $iposamt_stax + $iposamt_ntax ;
                $oposamt        = $oposamt_stax + $oposamt_ntax ;
                $cnosamt        = $cnosamt_stax + $cnosamt_ntax ;
                    
                $stosamt       =  $my_arr1['service_tax_amount'] - ($my_arr1['advance_amount_service_tax'] + $my_arr1['realise_amount_service_tax'] + $my_arr1['deficit_amount_service_tax'] + $my_arr1['booked_amount_service_tax']);
            }

            if (empty($my_arr1)) {
                $data = [ 'status' => false, 'message' => 'Bill Details Not Found!!' ];
            } else if ($clnt_code != $clntcd) {
                $data = [ 'status' => false, 'message' => 'Bill does not belongs to the selected Client!!' ];
            } else if ($matrcd != $clntcd && $matr_code != $matrcd) {
                $data = [ 'status' => false, 'message' => 'Bill does not belongs to the selected Matter!!' ];
            } else if ($os_amt <= 0) {
                $data = [ 'status' => false, 'message' => 'Bill has already been SETTLED!!' ];
            } else {
                $data = [
                    'status' => true,
                    'matr_code' => $matr_code,
                    'iposamt_stax' => $iposamt_stax,
                    'oposamt_stax' => $oposamt_stax,
                    'cnosamt_stax' => $cnosamt_stax,
                    'iposamt_ntax' => $iposamt_ntax,
                    'oposamt_ntax' => $oposamt_ntax,
                    'cnosamt_ntax' => $cnosamt_ntax,
                    'stosamt' => $stosamt,
                ];
            }
            return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data))); 

        } else if ($mode == 'GetBillOs') {
            $billyr = isset($_REQUEST['bill_year'])?$_REQUEST['bill_year']:null ;
            $billno = isset($_REQUEST['bill_no'])?$_REQUEST['bill_no']:null ;
            
            $my_sql1   = "select * from bill_detail where fin_year = '$billyr' and bill_no = '$billno' " ;
            $my_qry1   = $this->db->query($my_sql1)->getResultArray();
            $my_cnt1   = count($my_qry1) ; 
            $my_arr1   = $my_qry1[0];
            
            $matr_code =  $my_arr1['matter_code'] ;  

            $iposamt_stax   =  $my_arr1['bill_amount_inpocket_stax'] - ($my_arr1['realise_amount_inpocket_stax'] + $my_arr1['deficit_amount_inpocket_stax'] + $my_arr1['booked_amount_inpocket_stax']);
            $oposamt_stax   =  $my_arr1['bill_amount_outpocket_stax'] - ($my_arr1['realise_amount_outpocket_stax'] + $my_arr1['deficit_amount_outpocket_stax'] + $my_arr1['booked_amount_outpocket_stax']);
            $cnosamt_stax   =  $my_arr1['bill_amount_counsel_stax'] - ($my_arr1['realise_amount_counsel_stax'] + $my_arr1['deficit_amount_counsel_stax'] + $my_arr1['booked_amount_counsel_stax']);
            $iposamt_ntax   =  $my_arr1['bill_amount_inpocket_ntax'] - ($my_arr1['realise_amount_inpocket_ntax'] + $my_arr1['deficit_amount_inpocket_ntax'] + $my_arr1['booked_amount_inpocket_ntax']);
            $oposamt_ntax   =  $my_arr1['bill_amount_outpocket_ntax'] - ($my_arr1['realise_amount_outpocket_ntax'] + $my_arr1['deficit_amount_outpocket_ntax'] + $my_arr1['booked_amount_outpocket_ntax']);
            $cnosamt_ntax   =  $my_arr1['bill_amount_counsel_ntax'] - ($my_arr1['realise_amount_counsel_ntax'] + $my_arr1['deficit_amount_counsel_ntax'] + $my_arr1['booked_amount_counsel_ntax']);

            $iposamt        = $iposamt_stax + $iposamt_ntax ;
            $oposamt        = $oposamt_stax + $oposamt_ntax ;
            $cnosamt        = $cnosamt_stax + $cnosamt_ntax ;

            $stosamt   =  $my_arr1['service_tax_amount'] - ($my_arr1['advance_amount_service_tax'] + $my_arr1['realise_amount_service_tax'] + $my_arr1['deficit_amount_service_tax'] + $my_arr1['booked_amount_service_tax']);

            if ($my_cnt1 == 0) {
                $data = [ 'status' => false, 'message' => 'Bill Details Not Found!!' ];
            } else {
                $data = [
                    'status' => true,
                    'matr_code' => $matr_code,
                    'iposamt_stax' => $iposamt_stax,
                    'oposamt_stax' => $oposamt_stax,
                    'cnosamt_stax' => $cnosamt_stax,
                    'iposamt_ntax' => $iposamt_ntax,
                    'oposamt_ntax' => $oposamt_ntax,
                    'cnosamt_ntax' => $cnosamt_ntax,
                    'stosamt' => $stosamt,
                ];
            }
            return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data))); 

        } else if ($mode == 'CheckMainAc') {
            $acode = isset($_REQUEST['main_ac_code'])?$_REQUEST['main_ac_code']:null ;

            $my_sql1  = "select main_ac_desc, sub_ac_ind from account_master where main_ac_code = '$acode' " ;
            $my_qry1  = $this->db->query($my_sql1)->getResultArray();
            $my_cnt1  = count($my_qry1) ; 
            
            if ($my_cnt1 == 0) {
                $data = [ 'status' => false, 'message' => 'Main A/c Not Found!!', ];
            } else {
                $my_arr1  = $my_qry1[0];
                $data = [ 'status' => true, 'main_ac_desc' => $my_arr1['main_ac_desc'], 'sub_ac_ind' => $my_arr1['sub_ac_ind'], ];
            }    
            return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data))); 

        } else if ($mode == 'CheckSubAc') {
            $acode = isset($_REQUEST['main_ac_code'])?$_REQUEST['main_ac_code']:null ;
            $scode = isset($_REQUEST['sub_ac_code'])?$_REQUEST['sub_ac_code']:null ;

            $my_sql1  = "select sub_ac_desc from sub_account_master where main_ac_code = '$acode' and sub_ac_code = '$scode' " ;
            $my_qry1  = $this->db->query($my_sql1)->getResultArray();
            $my_cnt1  = count($my_qry1) ; 
            
            if ($my_cnt1 == 0) {
                $data = [ 'status' => false, 'message' => 'Sub A/c Not Found!!', ];
            } else {
                $my_arr1  = $my_qry1[0];
                $data = [ 'status' => true, 'sub_ac_desc' => $my_arr1['sub_ac_desc'], ];
            }    
            return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data))); 

        } else if ($mode == 'CheckMatter') {
            $matter_code = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null ;

            $my_sql1  = "select concat(a.matter_desc1,' : ',a.matter_desc2) matter_desc,a.client_code,b.client_name from fileinfo_header a, client_master b where a.matter_code = '$matter_code' and a.client_code = b.client_code " ;
            $my_qry1  = $this->db->query($my_sql1)->getResultArray();
            $my_cnt1  = count($my_qry1) ; 
            
            if ($my_cnt1 == 0) {
                $data = [ 'status' => false, 'message' => 'Matter Not Found!!', ];
            } else {
                $my_arr1  = $my_qry1[0];
                $data = [ 'status' => true, 'matter_desc' => $my_arr1['matter_desc'], 'client_code' => $my_arr1['client_code'], 'client_name' => $my_arr1['client_name'],];
            }    
            return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data)));
        }
    }
    
    /* Added by Surajit Naskar on 29-01-2024 */
    public function myVoucherDetails($voucher_no, $branch_code, $fin_year, $daybook_code) {
        if($voucher_no != '' && $branch_code != '' && $fin_year != '' && $daybook_code != '') {
            $voucher_no   = $voucher_no;
            $branch_code  = $branch_code;
            $fin_year     = $fin_year; 
            $daybook_code = $daybook_code;

            $qry1 = $this->db->query("select * from ledger_trans_hdr where branch_code = '$branch_code' 
                            and fin_year = '$fin_year' and daybook_code = '$daybook_code' 
                            and doc_no = '$voucher_no' and doc_type = 'RV' ")->getResultArray() ;
            // echo '<pre>';print_r($qry1);die;

            $num_row = count($qry1);
            $qry = $qry1[0];
                $data = [
                    'received_date'      => date_conv($qry['doc_date']),
                    'received_from_name' => strtoupper(stripslashes($qry['received_from'])) ,
                    'payee_payer_name'   => strtoupper(stripslashes($qry['payee_payer_name'])),
                    'instrument_type'    => $qry['instrument_type'],
                    'instrument_no'      => $qry['instrument_no'],
                    'instrument_dt'      => date_conv($qry['instrument_dt']),
                    'instrument_bk'      => $qry['bank_name'],
                    'received_amt'       => $qry['net_amount'],
                    'ledger_serial_no'   => $qry['serial_no'],
                    'money_receipt_no'   => $qry['money_receipt_no'] ,
                    'money_receipt_date' => date_conv($qry['money_receipt_date']),
                    'num_row'            => $num_row
                ];
                
        }
        
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data)));
    }
    //Added By Sylvester On 02-02-2024
        function getAddress($code,$client_code){ 
        $data  = $this->db->query("select * from client_address where client_code = '$client_code' ")->getResultArray();
        $data2  = $this->db->query("select * from client_attention where client_code = '$client_code' ")->getResultArray();
        $datacount=count($data);
        $datacount2=count($data2);
        $count =['count' => $datacount];
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$count))); 
        
    }
    function getAttention($code,$client_code){ 
        $data  = $this->db->query("select * from client_attention where client_code = '$client_code' ")->getResultArray();
        $datacount2=count($data);
        $count =['count2' => $datacount2];
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$count))); 
        
    }
    function getBillDetails($fin_year,$bill_no){ 
        $data  = $this->db->query("select * from bill_detail where fin_year = '$fin_year' and bill_no = '$bill_no' ")->getResultArray()[0];
        $client_qry            = $this->db->query("select  client_name from client_master where client_code = '$data[client_code]' ")->getResultArray()[0] ;
 
        $matter_qry            = $this->db->query("select if(matter_desc1 != '', concat(matter_desc1,':',matter_desc2), matter_desc2) matter_desc from fileinfo_header where matter_code = '$data[matter_code]' ") ->getResultArray()[0] ;
        $initial_qry           = $this->db->query("select initial_name from initial_master where initial_code = '$data[initial_code]' ")->getResultArray()[0] ;

        $address_qry           = $this->db->query("select * from client_address where address_code = '$data[address_code]' ")->getResultArray()[0] ;
 
        $attention_qry         = $this->db->query("select * from client_attention where attention_code = '$data[attention_code]' ")->getResultArray()[0] ;
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($data,$client_qry,$matter_qry,$initial_qry,$address_qry,$attention_qry))); 
        
    }
   function myRecSelectall($serial_no,$hhh){ 
       // echo "select a.*,b.client_name,concat(c.matter_desc1,' : ',c.matter_desc2) matter_desc from case_header a, client_master b, fileinfo_header c where a.serial_no = '$serial_no' and a.client_code = b.client_code and a.matter_code = c.matter_code";die;
        $my_sql1  = $this->db->query("select a.*,b.client_name,concat(c.matter_desc1,' : ',c.matter_desc2) matter_desc from case_header a, client_master b, fileinfo_header c where a.serial_no = '$serial_no' and a.client_code = b.client_code and a.matter_code = c.matter_code")->getResultArray()[0];
        $my_qry1  = $my_sql1;
        $my_cnt1  =count($my_qry1) ; 
        $my_arr1  = $my_qry1;
        //
        // echo"select ref_bill_serial_no from billinfo_header where serial_no = '$my_sql1[ref_billinfo_serial_no]'";die;
//        print_r($my_sql1);die;
        $my_arr2  =  $this->db->query("select ref_bill_serial_no from billinfo_header where serial_no = '$my_sql1[ref_billinfo_serial_no]'") ->getResultArray();
        
        if (!empty($my_arr2)) 
        { 
            $my_arr2 = $my_arr2[0];
            $billsrl  = $my_arr2['ref_bill_serial_no'] ;
        } 
        else 
        { 
            // Handle the case where no results were found
            $my_arr2 = []; // or any default value or action you want to take
            $billsrl  = '' ;
        }
        $my_arr3         =  $this->db->query("select concat(fin_year,'/',bill_no) final_bill_no, bill_date from bill_detail where serial_no = '$billsrl'")->getResultArray(); 
        if (!empty($my_arr3)) 
                    {
                        $my_arr3 = $my_arr3[0];
                        $final_bill_no   = $my_arr3['final_bill_no'] ;
                        $final_bill_date = $my_arr3['bill_date'] ;
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $my_arr3 = []; // or any default value or action you want to take
                        $final_bill_no   = '' ;
                        $final_bill_date = '' ;
                    }
     
     if($my_arr1['status_code'] == "A") { $status_desc = "ENTERED"; } else if ($my_arr1['status_code'] == "B") { $status_desc = "BILLED"; } else { $status_desc = ""; }
     //
	 if($my_arr1['billable_option'] == "Y") { $billable_desc = "BILLABLE"; } else if ($my_arr1['billable_option'] == "N") { $billable_desc = "NON-BILLABLE"; }  else if ($my_arr1['billable_option'] == "P") { $billable_desc = "PRE-BILLABLE"; }
     
     $retvalue=[
        'status_desc'=>$status_desc,
        'billable_desc'=>$billable_desc
     ];
    //  if ($my_cnt1 == 0)
	//  {
    //     $retvalue[] = 'N'.'|'.$my_arr1['serial_no'].  '|'.$my_arr1['activity_date']. '|'.$my_arr1['matter_code'].'|'.$my_arr1['matter_desc'].   '|'.$my_arr1['client_code'].           '|'.$my_arr1['client_name'].'|'.$my_arr1['judge_name'].'|'.$my_arr1['appear_for'].'|' 
	// 	                   .$my_arr1['prev_date'].  '|'.$my_arr1['prev_fixed_for'].'|'.$my_arr1['next_date'].  '|'.$my_arr1['next_fixed_for'].'|'.$my_arr1['ref_billinfo_serial_no'].'|'.$final_bill_no.       '|'.$final_bill_date.    '|'.$my_arr1['letter_no']. '|'
    //                        .$my_arr1['letter_date'].'|'.$my_arr1['header_desc'].   '|'.$status_desc.   '|'.$my_arr1['remarks'].   '|'.$billable_desc  ;						   
    //  }
    //  else
    //  {
    //     $retvalue = 'Y'.'|'.$my_arr1['serial_no'].  '|'.$my_arr1['activity_date']. '|'.$my_arr1['matter_code'].'|'.$my_arr1['matter_desc'].   '|'.$my_arr1['client_code'].           '|'.$my_arr1['client_name'].'|'.$my_arr1['judge_name'].'|'.$my_arr1['appear_for'].'|' 
	// 	                   .$my_arr1['prev_date'].  '|'.$my_arr1['prev_fixed_for'].'|'.$my_arr1['next_date'].  '|'.$my_arr1['next_fixed_for'].'|'.$my_arr1['ref_billinfo_serial_no'].'|'.$final_bill_no.       '|'.$final_bill_date.    '|'.$my_arr1['letter_no']. '|'
    //                        .$my_arr1['letter_date'].'|'.$my_arr1['header_desc'].   '|'.$status_desc.   '|'.$my_arr1['remarks'].   '|'.$billable_desc ;						   
    //  } 
     return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($my_sql1,$my_arr3,$retvalue)));    
	     
    }
    function chkCaseDet($serial_no){ 
        $my_sql1  = $this->db->query("select * FROM case_header WHERE serial_no = '$serial_no'")->getResultArray()[0];
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode(array_merge($my_sql1)));  
    }
    // End
    function checkCaseNo($caseNo){ 
        $my_sql1  = $this->db->query("select * FROM fileinfo_header WHERE matter_desc1 = '$caseNo'")->getResultArray()[0];
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($my_sql1)); 
    } 
}