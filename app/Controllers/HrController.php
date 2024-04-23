<?php



namespace App\Controllers;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class HrController extends BaseController

{

    public function __construct() {

        $this->db = \config\database::connect();
        $temp_db = $this->temp_db = db_connect('temp');

        $this->session = session();

    }
    public function employe_details_general($option = 'list')
    { 
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $global_curr_finyear=$session->financialYear;
        $global_curr_date2=date('Y-m-d');
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $user_option = isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] : null;
        $user_options = isset($_REQUEST['user_options']) ? $_REQUEST['user_options'] : null;
        $employee_id = isset($_REQUEST['employee_id']) ? $_REQUEST['employee_id'] : null;
        $employee_name                 = isset($_REQUEST['employee_name'])?trim($_REQUEST['employee_name']):'';
        $employee_initial              = isset($_REQUEST['employee_initial'])?trim($_REQUEST['employee_initial']):'';
        $present_address_line_1        = isset($_REQUEST['present_address_line_1'])?trim($_REQUEST['present_address_line_1']):'';
        $present_pincode_no            = isset($_REQUEST['present_pincode_no'])?trim($_REQUEST['present_pincode_no']):'';
        $present_phone_no              = isset($_REQUEST['present_phone_no'])?trim($_REQUEST['present_phone_no']):'';
        $permanent_address_line_1      = isset($_REQUEST['permanent_address_line_1'])?trim($_REQUEST['permanent_address_line_1']):'';
        $permanent_pincode_no          = isset($_REQUEST['permanent_pincode_no'])?trim($_REQUEST['permanent_pincode_no']):'';
        $permanent_phone_no            = isset($_REQUEST['permanent_phone_no'])?trim($_REQUEST['permanent_phone_no']):'';
        $gender_code                   = isset($_REQUEST['gender'])?trim($_REQUEST['gender']):'';
        $caste_code                    = isset($_REQUEST['caste_code'])?trim($_REQUEST['caste_code']):'';
        $marital_status_code           = isset($_REQUEST['martial_status'])?trim($_REQUEST['martial_status']):'';
        $blood_group_code              = isset($_REQUEST['blood_group'])?trim($_REQUEST['blood_group']):'';
        $religion_code                 = isset($_REQUEST['religion'])?trim($_REQUEST['religion']):'';
        $nationality_code              = isset($_REQUEST['nationality'])?trim($_REQUEST['nationality']):'';
        $relationship_code             = isset($_REQUEST['relation'])?trim($_REQUEST['relation']):'';
        $father_spouse_name            = isset($_REQUEST['father_spouse_name'])?trim($_REQUEST['father_spouse_name']):'';
        $mobile_phone_no               = isset($_REQUEST['mobile_no'])? trim($_REQUEST['mobile_no']):'';
        $email_id                      = isset($_REQUEST['email_id'])? trim($_REQUEST['email_id']):'';
        $dob                           = isset($_REQUEST['dob'])? trim($_REQUEST['dob']):'';       
        $company_code                  = isset($_REQUEST['company_code'])?trim($_REQUEST['company_code']):'';
        $branch_code                   = isset($_REQUEST['branch_code'])?trim($_REQUEST['branch_code']):'';
        $department_code               = isset($_REQUEST['department_code'])?trim($_REQUEST['department_code']):'';
        $designation_code              = isset($_REQUEST['designation_code'])?trim($_REQUEST['designation_code']):'';
        $join_date                     = isset($_REQUEST['join_date'])?trim(date('Y-m-d',strtotime($_REQUEST['join_date']))):'';
        $conf_date                     = isset($_REQUEST['conf_date'])?trim(date('Y-m-d',strtotime($_REQUEST['conf_date']))):'';
        $retirement_date               = isset($_REQUEST['retirement_date'])?trim(date('Y-m-d',strtotime($_REQUEST['retirement_date']))):'';
        $last_incr_date                = isset($_REQUEST['last_incr_date'])?trim(date('Y-m-d',strtotime($_REQUEST['last_incr_date']))):'';
        $enrollment_date               = isset($_REQUEST['enrollment_date'])?trim(date('Y-m-d',strtotime($_REQUEST['enrollment_date']))):'';
        $enrollment_no                 = isset($_REQUEST['enrollment_no'])?trim($_REQUEST['enrollment_no']):'';
        $employee_type_code            = isset($_REQUEST['employee_type_code'])?trim($_REQUEST['employee_type_code']):'';
        $employee_group_code           = isset($_REQUEST['employee_group_code'])?trim($_REQUEST['employee_group_code']):'';
        $employee_sub_group_code       = isset($_REQUEST['employee_sub_group_code'])?trim($_REQUEST['employee_sub_group_code']):'';
        $salary_subgroup_code          = isset($_REQUEST['salary_subgroup_code'])?trim($_REQUEST['salary_subgroup_code']):'';
        $employee_skill_code           = isset($_REQUEST['employee_skill_code'])?trim($_REQUEST['employee_skill_code']):'';
        $pf_acc_no                     = isset($_REQUEST['pf_acc_no'])?trim($_REQUEST['pf_acc_no']):'';
        $esi_acc_no                    = isset($_REQUEST['esi_acc_no'])?trim($_REQUEST['esi_acc_no']):'';
        $pf_elibility                  = isset($_REQUEST['pf_elibility'])?trim($_REQUEST['pf_elibility']):'';
        $esi_elibility                 = isset($_REQUEST['esi_elibility'])?trim($_REQUEST['esi_elibility']):'';
        $ptax_elibility                = isset($_REQUEST['ptax_elibility'])?trim($_REQUEST['ptax_elibility']):'';
        $basic_rate                    = isset($_REQUEST['basic_rate'])?trim($_REQUEST['basic_rate']):'';
        $vda_rate                      = isset($_REQUEST['vda_rate'])?trim($_REQUEST['vda_rate']):'';
        $hra_rate                      = isset($_REQUEST['hra_rate'])?trim($_REQUEST['hra_rate']):'';
        $city_comp_allowance_rate      = isset($_REQUEST['city_comp_allowance_rate'])?trim($_REQUEST['city_comp_allowance_rate']):'';
        $conveyance_allowance_rate     = isset($_REQUEST['conveyance_allowance_rate'])?trim($_REQUEST['conveyance_allowance_rate']):'';
      
        $special_allowance_rate        = isset($_REQUEST['special_allowance_rate'])?trim($_REQUEST['special_allowance_rate']):'';
        $other_amount                  = isset($_REQUEST['other_amount'])?trim($_REQUEST['other_amount']):'';
        $salary_type_indicator         = isset($_REQUEST['salary_type_indicator'])?trim($_REQUEST['salary_type_indicator']):'';
        $salary_location_city_code     = isset($_REQUEST['salary_location_city_code'])?trim($_REQUEST['salary_location_city_code']):'';
        $salary_pay_indicator          = isset($_REQUEST['salary_pay_indicator'])?trim($_REQUEST['salary_pay_indicator']):'';
        $bank_code                     = isset($_REQUEST['bank_code'])?trim($_REQUEST['bank_code']):'';
        $bank_account_no               = isset($_REQUEST['bank_account_no'])?trim($_REQUEST['bank_account_no']):'';
        $pan_no                        = isset($_REQUEST['pan_no'])?trim($_REQUEST['pan_no']):'';
        $adhar_no                      = isset($_REQUEST['adhar_no'])?trim($_REQUEST['adhar_no']):'';
        $status_code                   = isset($_REQUEST['status_code'])?trim($_REQUEST['status_code']):'';
        $leave_cnt                     = isset($_REQUEST['leave_cnt'])?trim($_REQUEST['leave_cnt']):'';
        $hod_name_code                 = isset($_REQUEST['hod_name_code'])?trim($_REQUEST['hod_name_code']):'';
        $empId                 = isset($_REQUEST['employee_id'])?trim($_REQUEST['employee_id']):'';
        $em_name                 = isset($_REQUEST['em_name'])?trim($_REQUEST['em_name']):'';
        $em_relation                 = isset($_REQUEST['em_relation'])?trim($_REQUEST['em_relation']):'';
        $em_number                 = isset($_REQUEST['em_number'])?trim($_REQUEST['em_number']):'';
        $reason                 = isset($_REQUEST['reason'])?trim($_REQUEST['reason']):'';
        $others                 = isset($_REQUEST['others'])?trim($_REQUEST['others']):'';
          
        $employee_name                 = strtoupper($employee_name);
        $employee_initial              = strtoupper($employee_initial);;
        $present_address_line_1        = strtoupper($present_address_line_1);
        $pan_no                        = strtoupper($pan_no);
        $adhar_no                      = strtoupper($adhar_no);
        $permanent_address_line_1      = strtoupper($permanent_address_line_1);
        $enrollment_no                 = strtoupper($enrollment_no);
       
        $father_spouse_name            = strtoupper($father_spouse_name);
       
        $present_address  = $present_address_line_1;
        $present_address  = trim($present_address);
        $present_address  = strtoupper($present_address);
        $birth_date_ymd                    = date_conv($dob) ;   
    $joining_date_ymd                  = date_conv($join_date) ;
    $confirmation_date_ymd             = date_conv($conf_date) ;
    $retirement_date_ymd               = date_conv($retirement_date ) ;
	$enrollment_date_ymd               = isset($_REQUEST['enroll_date'])?(date_conv($_REQUEST['enroll_date'])):'';
	$attn_id               = isset($_REQUEST['attn_id'])?($_REQUEST['attn_id']):'';
    $present_address_line_4               = isset($_REQUEST['present_address_line_4'])?($_REQUEST['present_address_line_4']):'';
    $gross_salary               = isset($_REQUEST['gross_salary'])?($_REQUEST['gross_salary']):'';
    $last_increment_date_ymd           = date_conv($last_incr_date) ;
        $confirm                 = isset($_REQUEST['confirm'])?trim($_REQUEST['confirm']):'';
        $count                 = isset($_REQUEST['count'])?trim($_REQUEST['count']):'';
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $emp_sql = "select substring(max(employee_id),1) as max_id  from empmas where employee_id like '%E%' order by employee_id desc limit 0,1";
        $emp_sql = $this->db->query($emp_sql)->getResultArray()[0];
        $str = $emp_sql['max_id'];
        $url = "../master-list?display_id={$display_id}&menu_id={$menu_id}";
if($option=='list'){
        if($str)
        {
            for($i=0;$i<4;$i++)
            {
            if(is_numeric($str[$i]) && $str[$i]>0)
            {
                $len = $i;
                break;
            }
            }
            if($len)
            {
            $last_no  = substr($str,$len); 
            $last_no = $last_no+1;
            }
        } 
        $emp_id       = str_pad($last_no,3,"0",STR_PAD_LEFT);
        $employee_id  = 'E'.$emp_id;
    }
    if ($user_options == 'Add') {$redk = '';
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
    if ($user_options == 'Delete') {$redk = '';
        $redv = 'readonly';
        $disv = 'disabled';
        $disb = '';
        $redve = '';
        $redokadd = 'readonly';
        $disview = 'disabled';
        $redLetter = 'disabled';}
    if ($user_options == 'View') {$redk = 'readonly';
        $redv = 'none';
        $disv = 'disabled';
        $disb = 'disabled';
        $redve = 'disabled';
        $redokadd = 'readonly';
        $disview = 'disabled';
        $redLetter = 'disabled';}
    if ($user_options == 'Copy') {$redk = 'readonly';
        $redv = 'readonly';
        $disv = 'disabled';
        $disb = 'disabled';
        $redve = 'disabled';
        $redokadd = 'readonly';
        $disview = 'disabled';
        $redLetter = 'disabled';}
    if ($user_options == 'letter') {$redk = 'readonly';
        $redv = 'readonly';
        $disv = 'disabled';
        $disb = 'disabled';
        $redve = 'disabled';
        $redokadd = 'readonly';
        $disview = 'disabled';
        $redLetter = '';}
        if ($this->request->getMethod() == 'post') 
        {
   //  echo $confirm;die;
            if($confirm=='')
            {
                    $bank_code_sql    = "select bank_code,bank_name  from bank_master "; 
                    $leave_sql = "select a.leave_type_code, a.leave_type_name, ifnull(b.leave_opening_days_no,0) leave_opening_days_no, ifnull(b.leave_earned_days_no,0) leave_earned_days_no, ifnull(b.leave_availed_days_no,0) leave_availed_days_no, ifnull(b.leave_closing_days_no,0) leave_closing_days_no 
                    from leave_master a left outer join employee_leave_summary b on b.leave_type_code = a.leave_type_code and b.financial_year = '$global_curr_finyear' and b.employee_id = '$empId' ORDER BY a.leave_type_code ASC " ;
                    $company_sql      = "select company_code,company_name from company_master order by company_name";
                    $branch_sql       = "select branch_code,branch_name from branch_master order by branch_name ";
                    $department_sql   = "select department_code,department_name from department_master order by department_name ";
                    $designation_sql  = "select designation_code,designation_name from designation_master order by designation_name ";
                    $employee_type    = "select code_desc,code_code from code_master where type_code=038 order by code_code ";
                    $hod_name         = "select initial_code,initial_name from initial_master where status_code = 'Active' ";
                    $employee_grp     = "select code_desc,code_code from code_master where type_code=210 order by code_code ";
                    $employee_subgrp  = "select code_desc,code_code from code_master where type_code=211 order by code_code ";
                    $salary_subgroup  = "select code_desc,code_code from code_master where type_code=216 order by code_code ";
                    $employee_skill   = "select code_desc,code_code from code_master where type_code=212 order by code_code ";
                    $salary_code      = "select salary_location_city_code,salary_location_city_name from salary_location_master ";
                    $caste_sql        = "select code_desc,code_code from code_master where type_code=202 order by code_code ";
                    $religion_sql     = "select code_desc,code_code from code_master where type_code=019 order by code_code ";
                    $nationality_sql  = "select code_desc,code_code from code_master where type_code=035 order by code_code ";
                    $marstat_sql      = "select code_desc,code_code from code_master where type_code=020 order by code_code ";
                    $bloodgrp_sql     = "select code_desc,code_code from code_master where type_code=017 order by code_code ";
                    $relation_sql     = "select code_desc,code_code from code_master where type_code=018 and (code_code ='001' or code_code ='003') order by code_code ";
                    $sql ="select *,employee_personal_detail.employee_initial from empmas INNER JOIN employee_personal_detail ON  empmas.employee_id=employee_personal_detail.employee_id where employee_personal_detail.employee_id='".$empId."'";
                    $data = $this->db->query($sql)->getResultArray();
                    if (!empty($data)) 
                    {
                        $data = $data[0];
                        $sele_stmt = "SELECT status_desc FROM status_master where table_name = 'empmas' and status_code = '".$data['status_code']."'";
                    $sql_data = $this->db->query($sele_stmt)->getRowArray();
                    if($data['status_code'] == 'A') { $colour_s = "#0000FF"; } else { $colour_s = "#FF0000";}
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $data = []; // or any default value or action you want to take
                        $sql_data=[];
                        $colour_s="";
                    }
                    
                    //echo '<pre>';print_r($data);die;
                    $relation_sql = $this->db->query($relation_sql)->getResultArray();
                    $nationality_sql = $this->db->query($nationality_sql)->getResultArray();
                    $branch_sql = $this->db->query($branch_sql)->getResultArray();
                    $department_sql = $this->db->query($department_sql)->getResultArray();
                    $designation_sql = $this->db->query($designation_sql)->getResultArray();
                    $employee_type = $this->db->query($employee_type)->getResultArray();
                    $hod_name = $this->db->query($hod_name)->getResultArray();
                    $marstat_sql = $this->db->query($marstat_sql)->getResultArray();
                    $bloodgrp_sql = $this->db->query($bloodgrp_sql)->getResultArray();
                    $religion_sql = $this->db->query($religion_sql)->getResultArray();
                
                    $bank_code_sql = $this->db->query($bank_code_sql)->getResultArray();
                    $leave_sql = $this->db->query($leave_sql)->getResultArray();
                    if($user_option!='Add'){
                    return view("pages/hr/employe_details_general", compact("option","data","bank_code_sql","leave_sql","relation_sql",
                    "nationality_sql","branch_sql","department_sql","designation_sql","employee_type","hod_name","marstat_sql",
                "bloodgrp_sql","religion_sql","str","user_option","redokadd","disview","user_options","sql_data","colour_s","url"));
                    }
                    else
                    {
                        
                        return view("pages/hr/employe_details_general", compact("option","bank_code_sql","leave_sql","relation_sql",
                        "nationality_sql","branch_sql","department_sql","designation_sql","employee_type","hod_name","marstat_sql",
                    "bloodgrp_sql","religion_sql","str","user_option","user_options","sql_data","colour_s","url"));
                    }
            }
            else
                {   if($confirm!='Submit'){
                           switch ($user_options) {
                            case 'Add':
                                //------ Personal Details
                                $this->db->query("insert into employee_personal_detail 
                                (employee_id
                                ,employee_name
                                ,employee_initial
                                ,present_address_line_1
                                ,present_pincode_no
                                ,present_phone_no
                                ,permanent_address_line_1
                                ,permanent_pincode_no
                                ,permanent_phone_no
                                ,mobile_phone_no
                                ,email_id
                                ,gender_code
                                ,caste_code
                                ,marital_status_code
                                ,blood_group_code
                                ,religion_code
                                ,nationality_code
                                ,relationship_code
                                ,father_spouse_name
                                ,birth_date
                                ,em_name
                                ,em_relation
                                ,em_number
                                ,employee_type_code
                                ,reason
                                ,others
                                ,join_date
                                ,conf_date
                                ,retirement_date
                                ,last_incr_date
                                ,enroll_date
                                ,enrollment_no
                                ,pf_acc_no
                                ,esi_acc_no
                                ,pf_elibility
                                ,esi_elibility
                                ,ptax_elibility
                                ,basic_rate
                                ,hra_rate
                                ,conveyance_allowance_rate
                                ,special_allowance_rate
                                ,other_amount
                                ,salary_type_indicator
                                ,salary_pay_indicator
                                ,bank_account_no
                                ,pan_no
                                ,adhar_no
                                ,gross_salary
                                ,hod_name_code
                                ,bank_code)
                            values ('$employee_id'
                                ,'$employee_name'
                                ,'$employee_initial'
                                ,'$present_address_line_1'
                                ,'$present_pincode_no'
                                ,'$present_phone_no'
                                ,'$permanent_address_line_1'
                                ,'$permanent_pincode_no'
                                ,'$permanent_phone_no'
                                ,'$mobile_phone_no'
                                ,'$email_id'
                                ,'$gender_code'
                                ,'$caste_code'
                                ,'$marital_status_code'
                                ,'$blood_group_code'
                                ,'$religion_code'
                                ,'$nationality_code'
                                ,'$relationship_code'
                                ,'$father_spouse_name'
                                ,'$birth_date_ymd'
                                ,'$em_name'
                                ,'$em_relation'
                                ,'$em_number'
                                ,'$employee_type_code'
                                ,'$reason'
                                ,'$others'
                                ,'$join_date'
                                ,'$conf_date'
                                ,'$retirement_date'
                                ,'$last_incr_date'
                                ,'$enroll_date'
                                ,'$enrollment_no'
                                ,'$pf_acc_no'
                                ,'$esi_acc_no'
                                ,'$pf_elibility'
                                ,'$esi_elibility'
                                ,'$ptax_elibility'
                                ,'$basic_rate'
                                ,'$hra_rate'
                                ,'$conveyance_allowance_rate'
                                ,'$special_allowance_rate'
                                ,'$other_amount'
                                ,'$salary_type_indicator'
                                ,'$salary_pay_indicator'
                                ,'$bank_account_no'
                                ,'$pan_no'
                                ,'$adhar_no'
                                ,'$gross_salary'
                                ,'$hod_name_code'
                                ,'$bank_code') ") ;
                                $this->db->query("insert INTO empmas 
                                                            (employee_id
                                                            ,attn_id
                                                            ,employee_name
                                                            ,employee_initial
                                                            ,employee_address1
                                                            ,employee_city
                                                            ,employee_pin
                                                            ,employee_phone
                                                            ,employee_mobile
                                                            ,employee_pan_no
                                                            ,branch_code
                                                            ,department_code
                                                            ,designation_code
                                                            ,login_id
                                                            ,email_id
                                                            ,status_code
                                                            ,gross_salary
                                                            ,birth_date								  
                                                            ,prepared_by             
                                                            ,prepared_on)

                            
                                                    VALUES ('$employee_id'
                                                            ,'$attn_id'
                                                            ,'$employee_name'
                                                            ,'$employee_initial'
                                                            ,'$present_address_line_1'
                                                            ,'$present_address_line_4'
                                                            ,'$present_pincode_no'
                                                            ,'$permanent_phone_no'
                                                            ,'$mobile_phone_no'
                                                            ,'$pan_no'
                                                            ,'B001'
                                                            ,'$department_code'
                                                            ,'$designation_code'
                                                            ,'$sessionName'
                                                            ,'$email_id'
                                                            ,'$status_code'
                                                            ,'$gross_salary'
                                                            ,'$birth_date_ymd'
                                                            ,'$sessionName'
                                                            ,'$global_curr_date2')");
                                                            //------ Leave Balances (as on Date)
                                for ($i=1; $i<=$count; $i++) 
                                        {
                                            if ($_REQUEST['leave_opening_days_no'.$i] + $_REQUEST['leave_earned_days_no'.$i] + $_REQUEST['leave_availed_days_no'.$i] + $_REQUEST['leave_closing_days_no'.$i] != 0)
                                            { 
                                                $this->db->query("insert into employee_leave_summary (financial_year,employee_id,leave_type_code,leave_opening_days_no,leave_earned_days_no,leave_availed_days_no,leave_closing_days_no) 
                                                                values ('$global_curr_finyear','$employee_id','".$_REQUEST['leave_type_code'.$i]."','".$_REQUEST['leave_opening_days_no'.$i]."','".$_REQUEST['leave_earned_days_no'.$i]."','".$_REQUEST['leave_availed_days_no'.$i]."','".$_REQUEST['leave_closing_days_no'.$i]."')"); 
                                            }
                                        }
                                       
                                        session()->setFlashdata('message', 'Records Added Successfully !!');
                                        return redirect()->to($url);
                                break;
                                case 'View':
                                $this->db->query("select * employee_personal_detail where employee_id = '$empId'") ;
                              $this->db->query("delete from employee_leave_summary where employee_id = '$empId'"  );
                                for ($i=1; $i<=$count; $i++) 
                                        {
                                            if ($_REQUEST['leave_opening_days_no'.$i] + $_REQUEST['leave_earned_days_no'.$i] + $_REQUEST['leave_availed_days_no'.$i] + $_REQUEST['leave_closing_days_no'.$i] != 0)
                                            { 
                                                $this->db->query("insert into employee_leave_summary (financial_year,employee_id,leave_type_code,leave_opening_days_no,leave_earned_days_no,leave_availed_days_no,leave_closing_days_no) 
                                                                values ('$global_curr_finyear','$empId','".$_REQUEST['leave_type_code'.$i]."','".$_REQUEST['leave_opening_days_no'.$i]."','".$_REQUEST['leave_earned_days_no'.$i]."','".$_REQUEST['leave_availed_days_no'.$i]."','".$_REQUEST['leave_closing_days_no'.$i]."')"); 
                                            }
                                        }
                                        session()->setFlashdata('message', 'Records Updated Successfully !!');
                                        return redirect()->to($url);
                                break;
                                    }
                                    
                           } 
                           else{
                            $this->db->query("update employee_personal_detail
                            set employee_name                  = '$employee_name',
                            employee_initial               = '$employee_initial',
                            present_address_line_1         = '$present_address_line_1',
                            present_pincode_no             = '$present_pincode_no',
                            present_phone_no               = '$present_phone_no',
                            permanent_address_line_1       = '$permanent_address_line_1',
                            permanent_pincode_no           = '$permanent_pincode_no',
                            permanent_phone_no             = '$permanent_phone_no',   
                            mobile_phone_no                = '$mobile_phone_no',
                            email_id                       = '$email_id',
                            gender_code                    = '$gender_code',
                            caste_code                     = '$caste_code',
                            marital_status_code            = '$marital_status_code',
                            blood_group_code               = '$blood_group_code',
                            religion_code                  = '$religion_code',
                            nationality_code               = '$nationality_code',
                            relationship_code              = '$relationship_code',
                            father_spouse_name             = '$father_spouse_name',
                            birth_date                     = '$birth_date_ymd',    
                            em_name                        = '$em_name',    
                            em_relation                    = '$em_relation',    
                            em_number                      = '$em_number',
                            employee_type_code             = '$employee_type_code',
                            reason                         = '$reason',
                            others                         = '$others', 
                            join_date                      ='$join_date',
                            conf_date                      ='$conf_date',
                            retirement_date                ='$retirement_date',
                            last_incr_date                 ='$last_incr_date',
                            enroll_date                    ='$enrollment_date_ymd',
                            enrollment_no                  ='$enrollment_no',
                            pf_acc_no                      ='$pf_acc_no',
                            esi_acc_no                     ='$esi_acc_no',
                            pf_elibility                   ='$pf_elibility',
                            esi_elibility                  ='$esi_elibility',
                            ptax_elibility                 ='$ptax_elibility',
                            basic_rate                     ='$basic_rate',
                            hra_rate                       ='$hra_rate',
                            conveyance_allowance_rate      ='$conveyance_allowance_rate',
                            special_allowance_rate         ='$special_allowance_rate',
                            other_amount                   ='$other_amount',
                            salary_type_indicator          ='$salary_type_indicator',
                            salary_pay_indicator           ='$salary_pay_indicator',
                            bank_account_no                ='$bank_account_no',
                            pan_no                         ='$pan_no',
                            adhar_no                       ='$adhar_no',
                            gross_salary                   ='$gross_salary',
                            hod_name_code                  ='$hod_name_code',
                            bank_code                      ='$bank_code'      
                          where employee_id = '$empId'") ;
                          
                          $this->db->query("update empmas
                          set attn_id                   = '$attn_id',
                          employee_name                 = '$employee_name',
                          employee_initial              = '$employee_initial',
                          employee_address1             = '$present_address_line_1',
                          employee_city                 = '$present_address_line_4',
                          employee_pin                  = '$present_pincode_no',
                          employee_phone                = '$permanent_phone_no',
                          employee_mobile               = '$mobile_phone_no',
                          employee_pan_no               = '$pan_no',   
                          branch_code                   = 'B001',
                          department_code               = '$department_code',
                          designation_code              = '$designation_code',
                          login_id                      = '$sessionName',
                          email_id                      = '$email_id',
                          status_code                   = '$status_code',
                          gross_salary                  = '$gross_salary',
                          birth_date                    = '$birth_date_ymd',
                          prepared_by                   = '$sessionName',
                          prepared_on                   = '$global_curr_date2'
                          where employee_id = '$empId'") ;
                          $this->db->query("delete from employee_leave_summary where employee_id = '$empId'"  );
                            for ($i=1; $i<=$count; $i++) 
                                    {
                                        if ($_REQUEST['leave_opening_days_no'.$i] + $_REQUEST['leave_earned_days_no'.$i] + $_REQUEST['leave_availed_days_no'.$i] + $_REQUEST['leave_closing_days_no'.$i] != 0)
                                        { 
                                            $this->db->query("insert into employee_leave_summary (financial_year,employee_id,leave_type_code,leave_opening_days_no,leave_earned_days_no,leave_availed_days_no,leave_closing_days_no) 
                                                            values ('$global_curr_finyear','$empId','".$_REQUEST['leave_type_code'.$i]."','".$_REQUEST['leave_opening_days_no'.$i]."','".$_REQUEST['leave_earned_days_no'.$i]."','".$_REQUEST['leave_availed_days_no'.$i]."','".$_REQUEST['leave_closing_days_no'.$i]."')"); 
                                        }
                                    }
                                    session()->setFlashdata('message', 'Records Updated Successfully !!');
                                    return redirect()->to($url);
                        }  
                               
                            
                }
        }
           
    }
    

    public function notice_upload()
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
            $submit            = isset($_REQUEST['submit'])?$_REQUEST['submit']:null;
            $ref_hr_serial_no  = '110022003';
            $url = "system/notice-upload?display_id={$display_id}&menu_id={$menu_id}";
            if($this->request->getMethod() == 'post') 
                {

                    return view("pages/Hr/notice_upload",  compact("option","data")); 
                }
                else
                { 
                    $sql = "select a.serial_no,a.file_type,a.file_name_system,a.file_name_original,a.description,a.uploaded_by,b.user_name,a.uploaded_on 
                    from hr_upload_files a, system_user b
                   where a.hr_serial_no = '$ref_hr_serial_no' and a.status_code = 'A'
                         and a.uploaded_by = b.user_id
                order by a.file_name_original"; 
                    $data = $this->db->query($sql)->getResultArray();

                    return view("pages/Hr/notice_upload",  compact("option","data")); 
                }
}

public function upload_file($option = 'list')
        {
        $sql = '';
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
        $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
        $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
        $url = "system/notice-upload?display_id={$display_id}&menu_id={$menu_id}";
        $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : null;
        $uploadCount = isset($_REQUEST['uploadCount']) ? $_REQUEST['uploadCount'] : null;
        $emp_serial_no = isset($_REQUEST['emp_serial_no']) ? $_REQUEST['emp_serial_no'] : null;
        $uploaded_by = isset($_REQUEST['uploaded_by']) ? $_REQUEST['uploaded_by'] : null;
        $uploaded_on = isset($_REQUEST['uploaded_on']) ? $_REQUEST['uploaded_on'] : null;
        $uploaded_time = isset($_REQUEST['uploaded_time']) ? $_REQUEST['uploaded_time'] : null;
        $option     = isset($_REQUEST['option'])?$_REQUEST['option'] : 'list';
        $desc = isset($_REQUEST['desc']) ? $_REQUEST['desc'] : null;
        $ref_hr_serial_no  = '110022003';
       // echo $option;die;
        if ($this->request->getMethod() == 'post') {
            switch ($option) {
                case 'Add':
                //    $this->db->query("delete from rup_upload_files where emp_serial_no='$emp_serial_no'");
                            $file = $this->request->getFile('userfiles');
                       
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

                            'file_type' => $fileExtension,
                            'file_name_system' => $randomName,
                            'file_name_original' => $originalName,
                            'file_size' => $fileSize,
                            'hr_serial_no' => $ref_hr_serial_no,
                            'status_code' => 'A',
                            'uploaded_by' => $uploaded_by,
                            'uploaded_on' => $uploaded_on,
                            'uploaded_time' => $uploaded_time,
                            'description' => $desc,

                        ];
                        $db->table('hr_upload_files')->insert($data);

                    
                    echo '<script>window.close();</script>';
                    break;
                    case 'list':
                        $sql = "select * from hr_upload_files where serial_no='$code'";
                        $data = $this->db->query($sql)->getResultArray();
                        return view("pages/Hr/upload_file", compact("url", "option","data","code"));
                    break;

            }
        }
        
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
            $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
            $frmDt = isset($_REQUEST['frmDt']) ? date('Y-m-d',strtotime($_REQUEST['frmDt'])) : null;
            $toDt = isset($_REQUEST['toDt']) ? date('Y-m-d',strtotime($_REQUEST['toDt'])) : null;
            $url = "system/notice-download?display_id={$display_id}&menu_id={$menu_id}";
            $ref_hr_serial_no  = '110022003';
            if($this->request->getMethod() == 'post') 
                {
                    switch ($option) {
                        case 'search':
                            if($_REQUEST['frmDt']!=''){
                            $sql = "select a.serial_no,a.file_type,a.file_name_system,a.file_name_original,a.description,a.hr_serial_no,a.uploaded_by,b.user_name,a.uploaded_on 
                    from hr_upload_files a, system_user b
                   where a.status_code = 'A'
                         and a.uploaded_by = b.user_id and a.hr_serial_no='".$ref_hr_serial_no."' and  uploaded_on  between '$frmDt' and '$toDt' and  description LIKE '%$query%'"; 
                            }
                            else
                            { $sql = "select a.serial_no,a.file_type,a.file_name_system,a.file_name_original,a.description,a.hr_serial_no,a.uploaded_by,b.user_name,a.uploaded_on 
                                from hr_upload_files a, system_user b
                               where a.status_code = 'A'
                                     and a.uploaded_by = b.user_id and a.hr_serial_no='".$ref_hr_serial_no."'
                            order by a.file_name_original"; 

                            }
                    $data = $this->db->query($sql)->getResultArray();
                    return view("pages/hr/notice_download",  compact("option","data")); 
                            break;
                    }
                }
                else
                { 
                    $sql = "select a.serial_no,a.file_type,a.file_name_system,a.file_name_original,a.description,a.hr_serial_no,a.uploaded_by,b.user_name,a.uploaded_on 
                    from hr_upload_files a, system_user b
                   where a.status_code = 'A'
                         and a.uploaded_by = b.user_id and a.hr_serial_no='".$ref_hr_serial_no."'
                order by a.file_name_original"; 
                    $data = $this->db->query($sql)->getResultArray();

                    return view("pages/hr/notice_download",  compact("option","data")); 
                }
}
public function time_sheet_upload()
{ 
    $sql = '';
    $data['requested_url'] = $this->session->requested_end_menu_url;
    $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
    $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
    $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
    $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
    $url = "system/notice-upload?display_id={$display_id}&menu_id={$menu_id}";
    $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : null;
    $uploadCount = isset($_REQUEST['uploadCount']) ? $_REQUEST['uploadCount'] : null;
    $emp_serial_no = isset($_REQUEST['emp_serial_no']) ? $_REQUEST['emp_serial_no'] : null;
    $uploaded_by = isset($_REQUEST['uploaded_by']) ? $_REQUEST['uploaded_by'] : null;
    $uploaded_on = isset($_REQUEST['uploaded_on']) ? $_REQUEST['uploaded_on'] : null;
    $uploaded_time = isset($_REQUEST['uploaded_time']) ? $_REQUEST['uploaded_time'] : null;
    $option     = isset($_REQUEST['option'])?$_REQUEST['option'] : 'list';
    $desc = isset($_REQUEST['desc']) ? $_REQUEST['desc'] : null;
    $ref_hr_serial_no  = '110022111';
   // echo $option;die;
    if ($this->request->getMethod() == 'post') {
        switch ($option) {
            case 'Add':
            //    $this->db->query("delete from rup_upload_files where emp_serial_no='$emp_serial_no'");
                    $file = $this->request->getFile('userfiles');
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

                        'file_type' => $fileExtension,
                        'file_name_system' => $randomName,
                        'file_name_original' => $originalName,
                        'file_size' => $fileSize,
                        'hr_serial_no' => $ref_hr_serial_no,
                        'status_code' => 'A',
                        'uploaded_by' => $uploaded_by,
                        'uploaded_on' => $uploaded_on,
                        'uploaded_time' => $uploaded_time,
                        'description' => $desc,

                    ];
                    $db->table('hr_upload_files')->insert($data);

                
                    return view("pages/hr/time_sheet_upload", compact("url", "option","data","code"));
                break;
                case 'list':
                    $sql = "select * from hr_upload_files where emp_serial_no='$code'";
                    $data = $this->db->query($sql)->getResultArray();
                    return view("pages/hr/time_sheet_upload", compact("url", "option","data","code"));
                break;

        }
    
    }
    else{
        $sql = "select * from hr_upload_files where serial_no='$code'";
                    $data = $this->db->query($sql)->getResultArray();
                    return view("pages/Hr/time_sheet_upload", compact("url", "option","data","code"));
    }
}
    
public function time_sheet_download($option = 'list')
{ 
    $sql = '';
    $data['requested_url'] = $this->session->requested_end_menu_url;
    $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
    $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
    $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
    $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
    $url = "system/notice-upload?display_id={$display_id}&menu_id={$menu_id}";
    $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : null;
    $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
    $frmDt = isset($_REQUEST['frmDt']) ? date('Y-m-d',strtotime($_REQUEST['frmDt'])) : null;
    $toDt = isset($_REQUEST['toDt']) ? date('Y-m-d',strtotime($_REQUEST['toDt'])) : null;
    $option     = isset($_REQUEST['option'])?$_REQUEST['option'] : '';
    $opt     = isset($_REQUEST['opt'])?$_REQUEST['opt'] : '';
    
    $ref_hr_serial_no  = '110022111';
    if ($this->request->getMethod() == 'post') 
    {
        switch ($option) {
            case 'search':
                if($_REQUEST['frmDt']!='')
                {
                $sql = "select * from hr_upload_files where hr_serial_no='$ref_hr_serial_no' and  uploaded_on  between '$frmDt' and '$toDt' and  description LIKE '%$query%'";
                }
                else
                {
                    $sql = "select * from hr_upload_files where hr_serial_no='$ref_hr_serial_no'";
                }
                $data = $this->db->query($sql)->getResultArray();
                return view("pages/Hr/time_sheet_download", compact("url", "option","data","code","display_id","menu_id"));
                break;
        }
        
    }
    else{
                $sql = "select * from hr_upload_files where hr_serial_no='$ref_hr_serial_no'";
                $data = $this->db->query($sql)->getResultArray();
                return view("pages/Hr/time_sheet_download", compact("url", "option","data","code","display_id","menu_id"));
        
    }
    
}
public function download_timesheet()
{ 
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
    $ref_hr_serial_no  = '110022111';
            $sql = "select * FROM hr_upload_files WHERE serial_no='$id'";
            $data = $this->db->query($sql)->getResultArray()[0];

            //$pdfFilePath = WRITEPATH . 'uploads/hr_file_upload/' . $data['file_name_system'];
            $pdfFilePath = ROOTPATH .'public/uploads/' . $data['file_name_system'];
            
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

            $sql = "select * from hr_upload_files where hr_serial_no='$ref_hr_serial_no'";

            $data = $this->db->query($sql)->getResultArray();

            return view("pages/System/time_sheet_download", compact("option","data","code","display_id","menu_id"));
}
}