<?php



namespace App\Controllers;

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;



class AdminController extends BaseController

{

    public function __construct() {

        $this->db = \config\database::connect();

        $this->session = session();

    }

 

    public function user_details($option = 'list') {

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

        $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;

        $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

        $user_id       = isset($_REQUEST['user_id'])?$_REQUEST['user_id']: null;	

        $user_name     = isset($_REQUEST['user_name'])?$_REQUEST['user_name']: null;

        $user_type     = isset($_REQUEST['user_type'])?$_REQUEST['user_type']: null;

        $user_gender   = isset($_REQUEST['user_gender'])?$_REQUEST['user_gender']: null;

        $user_password   = isset($_REQUEST['user_password'])?$_REQUEST['user_password']: null;

        $status_code   = isset($_REQUEST['status_code'])?$_REQUEST['status_code']: null;

        $hiddenuser_id   = isset($_REQUEST['hiddenuser_id'])?$_REQUEST['hiddenuser_id']: null;

        $sql = '';

        $sql1='';

        $permission = ($option != 'add' && $option != 'edit') ? 'readonly disabled' : '';

        $params = ['requested_url' => $this->requested_url()];

        $system_user_table = $this->db->table("system_user");

        $pkey='Sinha&co';

            // Store a string into the variable which

            // need to be Encrypted

            $simple_string = $user_password;



            // Display the original string

           // echo "Original String: " . $simple_string;



            // Store the cipher method

            $ciphering = "AES-128-CTR";



            // Use OpenSSl Encryption method

            $iv_length = openssl_cipher_iv_length($ciphering);

            $options = 0;



            // Non-NULL Initialization Vector for encryption

            $encryption_iv = '1234567891011121';



            // Store the encryption key

            $encryption_key = $pkey;

           

            $privetkey=$pkey;

            // Use openssl_encrypt() function to encrypt the data

            $encryption = openssl_encrypt($simple_string, $ciphering,

                        $encryption_key, $options, $encryption_iv);



        if($this->request->getMethod() == 'post') {

            switch($option) {

               

                case "add":

                    $sql = "select * from system_user where user_id = '$user_id' ";

                    if(!empty($user_id) && !empty($user_name) && !empty($user_type) && !empty($user_password) && !empty($status_code) && !empty($user_gender)){

                        $this->db->query("insert into system_user (user_id, user_name, role, user_password, status_code, user_gender) values ('$user_id','$user_name','$user_type','$encryption','$status_code','$user_gender') ");

                        session()->setFlashdata('message', 'Records Added Successfully !!');

                        return redirect()->to($data['requested_url']);

                    }

                    break;

                case "edit":

                   $sql = "select * from system_user where user_id = '$user_id' ";

                   $this->db->query("update system_user set user_id='".$user_id."', user_name='".$user_name."', role='".$user_type."', user_password='".$encryption."', status_code='".$status_code."', user_gender='".$user_gender."' WHERE user_id='".$hiddenuser_id."'");

                

                

                    session()->setFlashdata('message', 'Records Updated Successfully !!');

                    return redirect()->to($data['requested_url']);

               

                    break;

                case "delete":

                    break;

            }

        } else {

            switch($option) {

                case 'list':

                    $sql = "select * from system_user order by user_name";

                    $sql1 = "select * from role order by id";

                    break;

    

                case "add":

                    $sql = "select * from system_user where user_id = '$user_id' ";

                    $sql1 = "select * from role order by id";

                    break;

    

                case "view":

                    $sql1 = "select * from role order by id";

                    

                    $sql = "select * from system_user where user_id = '$user_id' "; 

                    break;



                case "edit":

                    $sql1 = "select * from role order by id";

                    

                    $sql = "select * from system_user where user_id = '$user_id' ";

                    break;

                

                case "delete":

                    $this->db->query("delete from system_user WHERE user_id='".$user_id."'");

                    

                        session()->setFlashdata('message', 'Records Deleted Successfully !!');

                        return redirect()->to($data['requested_url']);

                    break;

            }

        }

        

        $data = $this->db->query($sql)->getResultArray();

        $data1 = $this->db->query($sql1)->getResultArray();

        

        $data = (count($data) == 1) ? $data[0] : $data;



        // echo '<pre>'.$user_id;print_r ($data);die;

        return view("pages/Admin/user_details",  compact("data", "option", "permission", "params","data1","privetkey"));

    }
    public function  system_menu($option = 'list'){

        //echo $option;die;

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

        $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

        $menu_head           = isset($_REQUEST['menu_head'])?$_REQUEST['menu_head']:null;	

        $t_menu_id           = isset($_REQUEST['t_menu_id'])?$_REQUEST['t_menu_id']:null;	

        $menu_desc           = isset($_REQUEST['menu_desc'])?$_REQUEST['menu_desc']:null;

        $menu_type           = isset($_REQUEST['menu_type'])?$_REQUEST['menu_type']:null;

        $program_help_id     = isset($_REQUEST['program_help_id'])?$_REQUEST['program_help_id']:null;

        $menu_prog           = isset($_REQUEST['menu_prog'])?$_REQUEST['menu_prog']:null;

        $program_screen_header_name = isset($_REQUEST['program_screen_header_name'])?$_REQUEST['program_screen_header_name']:null;

        $params              = isset($_REQUEST['params'])?$_REQUEST['params']:null;

        $default_access_ind  = isset($_REQUEST['default_access_ind'])?$_REQUEST['default_access_ind']:null;

        $status_code         = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:null;

        $program_screen_ref_no = isset($_REQUEST['program_screen_ref_no'])?$_REQUEST['program_screen_ref_no']:null;	

        $index_order = isset($_REQUEST['index_order'])?$serial_no = $_REQUEST['index_order']:'';

        $serial_no = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;	

        $sql = '';

        $sql1= '';

        $permission = ($option != 'add' && $option != 'edit') ? 'readonly disabled' : '';

       // echo $option;die;

        if($this->request->getMethod() == 'POST') 

        {

            switch($option) {

                case 'edit':

            }

           

        }

        else{

            switch($option) { 

                case 'list':

                    $order_by = " order by menu_head,menu_id " ;

                        if($index_order == 'menu_desc')

                        {

                            $order_by = " order by menu_desc " ;

                        }

                        if($index_order == 'menu_id')

                        {

                            $order_by = " order by menu_id " ;

                        }

                        if($index_order == 'menu_prog')

                        {

                            $order_by = " order by menu_prog " ;

                        }

                        if($index_order == 'menu_type')

                        {

                            $order_by = " order by menu_type " ;

                        }

                        if($index_order == 'params')

                        {

                            $order_by = " order by params " ;

                        }

                      $sql = "select serial_no,menu_head,menu_desc,menu_id as t_menu_id,menu_prog,if(menu_type='M','Menu',if(menu_type='P','Program','')) as menu_type,params,status_code from system_menu $order_by ";

                  

                     break;

                case 'edit':

                    $order_by = " order by menu_head,menu_id " ;

                        if($index_order == 'menu_desc')

                        {

                            $order_by = " order by menu_desc " ;

                        }

                        if($index_order == 'menu_id')

                        {

                            $order_by = " order by menu_id " ;

                        }

                        if($index_order == 'menu_prog')

                        {

                            $order_by = " order by menu_prog " ;

                        }

                        if($index_order == 'menu_type')

                        {

                            $order_by = " order by menu_type " ;

                        }

                        if($index_order == 'params')

                        {

                            $order_by = " order by params " ;

                        }

                     if($t_menu_id!='' && $menu_head!=''){

                       

                        $this->db->query("update system_menu set menu_head='".$menu_head."', menu_desc='".$menu_desc."', menu_id='".$t_menu_id."', menu_prog='".$menu_prog."', menu_type='".$menu_type."', params='".$params."',program_screen_header_name='".$program_screen_header_name."',program_help_id='".$program_help_id."',default_access_ind='".$default_access_ind."',program_screen_ref_no='".$program_screen_ref_no."',status_code='".$status_code."' WHERE serial_no='".$serial_no."'");

                     session()->setFlashdata('message', 'Records Updated Successfully !!');

                     return redirect()->to($data['requested_url']);

                  }

                   else{

                    $sql1 = "select serial_no,menu_head,menu_desc,menu_id as t_menu_id,program_help_id as prog_help_id,menu_prog,if(menu_type='M','Menu',if(menu_type='P','Program','')) as menu_type,params,status_code from system_menu where serial_no != '$serial_no' $order_by ";

                 

                    $sql = "select serial_no,menu_head,menu_desc,menu_id as t_menu_id,program_help_id as prog_help_id,program_screen_header_name,default_access_ind,program_screen_ref_no,menu_prog,if(menu_type='M','Menu',if(menu_type='P','Program','')) as menu_type,params,status_code from system_menu  where serial_no = '$serial_no'";

                  

                   }

                   break;

                   case "add":
                    if($t_menu_id!='' && $menu_head!=''){

                        $this->db->query("insert into system_menu (menu_head, menu_desc, menu_id, menu_prog, menu_type, params,program_screen_header_name,program_help_id,default_access_ind,program_screen_ref_no,status_code) values ('$menu_head','$menu_desc','$t_menu_id','$menu_prog','$menu_type','$params','$program_screen_header_name','$program_help_id','$default_access_ind','$program_screen_ref_no','$status_code') ");

                        session()->setFlashdata('message', 'Records Added Successfully !!');
                        return redirect()->to($data['requested_url']);

                    }

                    break;

            }

            

        }

        if($serial_no!=''){

        $data1 = $this->db->query($sql)->getResultArray();

        $data = $this->db->query($sql1)->getResultArray();

        $data1 = (count($data1) == 1) ? $data1[0] : $data1;

        return view("pages/Admin/system_menu",  compact("option","data1","data","serial_no"));

        }

        else{

            $data = $this->db->query($sql)->getResultArray();

        

        $data = (count($data) == 1) ? $data[0] : $data;

        return view("pages/Admin/system_menu",  compact("option","data"));

        }

    }
        public function  query_details($option = 'list'){

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

        $sql= '';

        $permission = ($option != 'add' && $option != 'edit') ? 'readonly disabled' : '';

        if($this->request->getMethod() == 'POST') 

        {

            switch($option) {

                case 'list':

                

                break;

           

            }

        }

        else

        {

            switch($option) {

                case 'list':

                  $sql="select * from system_query order by query_id";

                break;

                case 'add':

                    return view("pages/Admin/query_details",  compact("option","data"));

                    break;



                    

           

            }

        }

        $data = $this->db->query($sql)->getResultArray();

        $data = (count($data) == 1) ? $data[0] : $data;

        return view("pages/Admin/query_details",  compact("option","data"));

    }
        public function  query_details_add($option = 'list'){

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

        $query_id           = isset($_REQUEST['query_id'])?$_REQUEST['query_id']:null;	

        $query_name         = isset($_REQUEST['query_name'])?$_REQUEST['query_name']:null;	

        $query_module_code  = isset($_REQUEST['query_module_code'])?$_REQUEST['query_module_code']:null;	

        $query_program_name = isset($_REQUEST['query_program_name'])?$_REQUEST['query_program_name']:null;	

        $status_code        = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:null;	

        $sql= '';

        $permission = ($option != 'add' && $option != 'edit') ? 'readonly disabled' : '';

        if($this->request->getMethod() == 'POST') 

        {

            switch($option) {

                case 'list':

                

                break;

           

            }

        }

        else

        {

            switch($option) {

                case 'add':

                    if($query_id==''){

                    $sql1 = "select code_code, code_desc from code_master where type_code = '030' order by code_desc";

                    }

                    else

                    {

                        $this->db->query("insert into system_query (query_id,query_module_code,query_name,query_program_name,status_code) values ('$query_id','$query_module_code','$query_name','$query_program_name','$status_code' )");

                        session()->setFlashdata('message', 'Records Added Successfully !!');

                        return redirect()->to($data['requested_url']);

                    }

                    break;

                    case 'edit':

                        

                        $sql1 = "select code_code, code_desc from code_master where type_code = '030' order by code_desc";

                        if(!empty($query_id) && !empty($query_name) && !empty($query_module_code) && !empty($query_program_name) && !empty($status_code))

                        {

                        

                        $this->db->query("update system_query set query_id='".$query_id."', query_name='".$query_name."', query_module_code='".$query_module_code."', query_program_name='".$query_program_name."', status_code='".$status_code."' WHERE query_id='".$query_id."'");

                        session()->setFlashdata('message', 'Records Updated Successfully !!');

                        return redirect()->to($data['requested_url']);

                        }

                        else{

                        $sql = "select query_id ,query_module_code, query_name,query_module_code as code_desc,query_program_name,status_code from system_query where query_id = '".$query_id."'";

                        }

                    break;
                    case 'view':

                        

                        $sql1 = "select code_code, code_desc from code_master where type_code = '030' order by code_desc";


                        $sql = "select query_id ,query_module_code, query_name,query_module_code as code_desc,query_program_name,status_code from system_query where query_id = '".$query_id."'";

                    break;



                    

           

            }

        }

       if($option!='add'){

        $data = $this->db->query($sql)->getResultArray();

          }

        $data1 = $this->db->query($sql1)->getResultArray();

        $data = (count($data) == 1) ? $data[0] : $data;

        //print_r($data);die;

        return view("pages/Admin/query_details_add",  compact("option","data","data1"));

    }
    public function  system_menu_activity($option = 'list'){

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $data3 ='';

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

        $my_menuid           = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;	

        $activity_id           = isset($_REQUEST['activity_id'])?$_REQUEST['activity_id']:null;	

        $activity_desc         = isset($_REQUEST['activity_desc'])?$_REQUEST['activity_desc']:null;	

        $sql= '';

        $permission = ($option != 'add' && $option != 'edit') ? 'readonly disabled' : '';

        if($this->request->getMethod() == 'POST') 

        {

            switch($option)

            {



            }

        }

        else

        {

            switch($option)

            {

            case 'list':

                

                isset($_REQUEST['index_order'])?$index_order = $_REQUEST['index_order']:$index_order='';

                $order_by = " order by menu_desc " ;

                    if($index_order == 'menu_id')

                    {

                        $order_by = " order by a.menu_id " ;

                    }

                    if($index_order == 'menu_desc')

                    {

                        $order_by = " order by b.menu_desc " ;

                    }

                    if($index_order == 'activity_id')

                    {

                        $order_by = " order by a.activity_id " ;

                    }

                    if($index_order == 'activity_desc')

                    {

                        $order_by = " order by a.activity_desc " ;

                    }



                        $sql = "select 

                            a.menu_id as t_menu_id,

                            b.menu_desc,

                            a.activity_id,

                            a.activity_desc

                            from system_menu_activity a, system_menu b

                            where a.menu_id = b.menu_id and b.menu_type='P' " . $order_by ;

                            $sql1 = "select menu_desc,menu_id from system_menu where menu_type='P' order by menu_desc";

                break;

                case 'add':

                    $this->db->query("insert into system_menu_activity (menu_id,activity_id,activity_desc) values ('$menu_id',null,'$activity_desc')");

                    session()->setFlashdata('message', 'Records Added Successfully !!');

                    return redirect()->to($data['requested_url']);

                break;

                case 'edit':

                    isset($_REQUEST['index_order'])?$index_order = $_REQUEST['index_order']:$index_order='';

                $order_by = " order by menu_desc " ;

                    if($index_order == 'menu_id')

                    {

                        $order_by = " order by a.menu_id " ;

                    }

                    if($index_order == 'menu_desc')

                    {

                        $order_by = " order by b.menu_desc " ;

                    }

                    if($index_order == 'activity_id')

                    {

                        $order_by = " order by a.activity_id " ;

                    }

                    if($index_order == 'activity_desc')

                    {

                        $order_by = " order by a.activity_desc " ;

                    }

                    $sql = "select 

                    a.menu_id as t_menu_id,

                    b.menu_desc,

                    a.activity_id,

                    a.activity_desc

                    from system_menu_activity a, system_menu b

                    where a.menu_id = b.menu_id and b.menu_type='P' " . $order_by ;

                    $sql1 = "select menu_desc,menu_id from system_menu where menu_type='P' order by menu_desc";

                    $sql3 = "select 

                       menu_id as t_menu_id,

                       activity_id,

                       activity_desc

                       from system_menu_activity 

                where activity_id = '$activity_id'" ;

                if(!empty($menu_id) && !empty($activity_id) && !empty($activity_desc))

                        {

                            

                            $this->db->query("update system_menu_activity set menu_id='".$menu_id."', activity_desc='".$activity_desc."' WHERE activity_id='".$activity_id."'");

                           session()->setFlashdata('message', 'Records Updated Successfully !!');

                           return redirect()->to($data['requested_url']);

                           

                        }

                    break;

            }

        }

        $data = $this->db->query($sql)->getResultArray();

        $data1 = $this->db->query($sql1)->getResultArray();

        if($option=='edit')

        {

            $data1 = $this->db->query($sql1)->getResultArray();

            $data3 = $this->db->query($sql3)->getResultArray();   

            $data3 = (count($data3) == 1) ? $data3[0] : $data3;

        }

        

        

        $data = (count($data) == 1) ? $data[0] : $data;

        

        return view("pages/Admin/system_menu_activity",  compact("option","data","data1","data3","menu_id"));

    }
    public function system_menu_perm($option='list'){

        $session = session(); 

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

        $my_menuid           = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;	

        $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

        $user_id           = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:null;	

        $select_button     = isset($_REQUEST['select_button'])?$_REQUEST['select_button']:null;	

        $max_chk_cnt     = isset($_REQUEST['max_chk_cnt'])?$_REQUEST['max_chk_cnt']:null;	

        $sql= '';

        $permission = ($option != 'add' && $option != 'edit') ? 'readonly disabled' : '';

        $roleId=$session->roleId;

        if($this->request->getMethod() == 'post') 

        {

            switch($option)

            {

                case 'edit':

                    $this->db->query("delete from system_user_menu_permission where role = '$user_id'");

                     

                     for($cnt=1; $cnt<=$max_chk_cnt;$cnt++)

                     {

                         

                         isset($_REQUEST['chk'.$cnt])?$this_menuid = $_REQUEST['chk'.$cnt]:$this_menuid='';

                         $quick_link = isset($_POST['quick_link'.$cnt]) ? $_POST['quick_link'.$cnt] : 'N';

                         if ($this_menuid != '')

                         {

                                 $this->db->query("insert into system_user_menu_permission (role,menu_id,quick_link) 

                                 values ('$user_id','$this_menuid','$quick_link')");

                         }

                     }

                     $sql1="select a.menu_head,a.menu_desc,a.menu_id,a.menu_prog,if(a.menu_type='M','Menu',if(a.menu_type='P','Program','')) as menu_type,if(b.user_id is not null,'YES','NO') as sele_ind,

                     b.quick_link from system_menu a left join system_user_menu_permission b on a.menu_id = b.menu_id and b.user_id='$user_id' and a.status_code = 'Active'

                     order by a.menu_id ";

                    

                     session()->setFlashdata('message', 'Records Updated Successfully !!');

                     return redirect()->to($data['requested_url']);

 

                     break;

            }

            

        }

        else

        {

            switch($option)

            {

               

                case 'list':

                    $sql="select id,role_name from role order by id";

                    $sql1="select a.menu_head,a.menu_desc,a.menu_id,a.menu_prog,if(a.menu_type='M','Menu',if(a.menu_type='P','Program','')) as menu_type,if(b.user_id is not null,'YES','NO') as sele_ind,

                    b.quick_link from system_menu a left join system_user_menu_permission b on a.menu_id = b.menu_id and b.role='$roleId' and a.status_code = 'Active'

                    order by a.menu_id ";

                break;

                

                }

        }

        $data = $this->db->query($sql)->getResultArray();

        $data1 = $this->db->query($sql1)->getResultArray();

        return view("pages/Admin/system_menu_perm",  compact("option","data","data1","select_button","menu_id","user_id"));



    }
    public function sys_user_initial_perm($option='list')  {   

        $sql="";

        $sql1="";

        $sql3="";

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	

        //echo $menu_id;die;

        $default_access_ind = isset($_REQUEST['default_access_ind'])?$_REQUEST['default_access_ind']:null;	

        $user_id           = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:null;	

        $userId           = isset($_REQUEST['userId'])?$_REQUEST['userId']:null;

        

        $total_row           = isset($_REQUEST['total_row'])?$_REQUEST['total_row']:null;

        

       // echo $default_access_ind;die;

        $my_menuid           = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;	

        $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;

        $innitialname='';

        if($this->request->getMethod() == 'post') 

        {

           

            switch($option)

            {

                case 'edit':

                      $this->db->query("delete from system_user_initial_permission where user_id = '$userId'");

                                 for($cnt=1; $cnt<=$total_row;$cnt++)

                                 {

                                    $initial_code           = isset($_REQUEST['initial_code'.$cnt])?$_REQUEST['initial_code'.$cnt]:null;

                                    $initial_name           = isset($_REQUEST['initial_name'.$cnt])?$_REQUEST['initial_name'.$cnt]:null;

                                    $initial_perm           = isset($_REQUEST['initial_perm'.$cnt])?$_REQUEST['initial_perm'.$cnt]:null;

                                    if ($initial_perm == 'Y')

                                        {

                                           

                                    $this->db->query("insert into system_user_initial_permission (user_id,initial_code) values ('$userId','$initial_code')");

                                        }

                                }

                   

                    session()->setFlashdata('message', 'Records Updated Successfully !!');

                    return redirect()->to($data['requested_url']);

                    break;



            }

        }

        else

        {

            switch ($option) {

                case 'list':

                        $sql1="select a.initial_code,a.initial_name,'Y' initial_perm from initial_master a, system_user_initial_permission b where a.initial_code = b.initial_code and b.user_id = '$default_access_ind' order by a.initial_name";

                        $sql="select user_id,user_name from system_user where status_code = 'Active' order by user_name";

                        $sql2="select user_id,user_name from system_user where user_id = '$default_access_ind' and status_code = 'Active'";

                        $sql3="select count(*) cnt from initial_master where status_code = 'Active' ";

                break;

                case 'edit':

                         $sql="select user_id,user_name from system_user where status_code = 'Active' order by user_name";

                         $sql2="select user_id,user_name from system_user where user_id = '$user_id' and status_code = 'Active'";

                         $sql1="select a.initial_code,a.initial_name,if(b.initial_code is not null,'Y','N') initial_perm 

                         from initial_master a left outer join system_user_initial_permission b on b.initial_code = a.initial_code and b.user_id = '$user_id' where a.status_code = 'Active' order by a.initial_name ";

                         $sql3="select count(*) cnt from initial_master where status_code = 'Active' ";

                         break;

            }



        }

       

        $data = $this->db->query($sql)->getResultArray();   

        $data1 = $this->db->query($sql1)->getResultArray(); 

        $data2 = $this->db->query($sql2)->getResultArray(); 

        $data3 = $this->db->query($sql3)->getResultArray(); 

        $data = (count($data) == 1) ? $data[0] : $data;

        $data3 = (count($data3) == 1) ? $data3[0] : $data3;

        return view("pages/Admin/sys_user_initial_perm",  compact("option","data","data1","data2","data3","default_access_ind","menu_id","user_id"));



    }
    public function user_role($option='list'){ 

        $sql="";

        $sql2="";

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;

        $id           = isset($_REQUEST['id'])?$_REQUEST['id']:null;

        //echo $id;die;

        $role_name           = isset($_REQUEST['role_name'])?$_REQUEST['role_name']:null;

        if($this->request->getMethod() == 'post') 

        {

            switch($option)

            {

                case 'add':

                    $this->db->query("insert into role (role_name) 

                    values ('$role_name')");

                    session()->setFlashdata('message', 'Records Added Successfully !!');

                    return redirect()->to($data['requested_url']);

                    break;

                case 'edit':

                    

                    $this->db->query("update role set role_name='$role_name' where id='$id'"); 

                    session()->setFlashdata('message', 'Records Updated Successfully !!');

                    return redirect()->to($data['requested_url']);

                    break;

            }

        }

        else{

            switch($option)

            {

                case 'list':

                    $sql2="select * FROM role";

                    $sql="select * from role where id ='$id'";

                break;

                case 'edit':

                $sql2="select * FROM role";

                $sql="select * from role where id ='$id'";

                

            }

        }

        $data = $this->db->query($sql)->getResultArray(); 

        $data2 = $this->db->query($sql2)->getResultArray();  

        $data = (count($data) == 1) ? $data[0] : $data;

        $data2 = (count($data2) == 1) ? $data2[0] : $data2;

        return view("pages/Admin/role",  compact("option","data","data2"));



    }
    public function permission_add($option='list'){

        $sql="";

        $sql2="";

        $sql3="";

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;

        $permission_name   = isset($_REQUEST['permission_name'])?$_REQUEST['permission_name']:null;

        $permission_on   = isset($_REQUEST['permission_on'])?$_REQUEST['permission_on']:null;

        $id           = isset($_REQUEST['id'])?$_REQUEST['id']:null;

        $page_namelist   = isset($_REQUEST['page_name'])?$_REQUEST['page_name']:null;

        $icon   = isset($_REQUEST['icon'])?$_REQUEST['icon']:null;

       

        if($option=='list')

        {

            $page_name[]   = isset($_REQUEST['page_name'])?$_REQUEST['page_name']:null;

        }

        if($option=='edit')

        { 

            if($page_namelist!=''){

                $page_name   = $page_namelist;

                }

                else{

                    $page_name[]   = $page_namelist;

                }

        }

        if($option=='add')

        { 

            if($page_namelist!=''){

                $page_name   = $page_namelist;

                }

                else{

                    $page_name[]   = $page_namelist;

                }

        }

        $page_nameList =  implode(",", $page_name);

        if($this->request->getMethod() == 'post') 

        {

            switch($option)

            {

                case 'add':

                    $this->db->query("insert into permission (permission_name,permission_on,menu_id,icon) 

                    values ('$permission_name','$permission_on','$page_nameList','$icon')");

                    session()->setFlashdata('message', 'Records Added Successfully !!');

                    return redirect()->to($data['requested_url']);

                break;

                case 'edit':

                    $this->db->query("update permission set permission_name='$permission_name',permission_on='$permission_on',menu_id='$page_nameList',icon='$icon' where id='$id'"); 

                    session()->setFlashdata('message', 'Records Updated Successfully !! <br> Log Out To Perform The Change');

                    return redirect()->to($data['requested_url']);

                break;



            }

        }

        else

        {

            switch($option)

            {

                case 'list':

                    $sql="select * from permission";

                    $sql2="select * from permission";

                    $sql3="select * from system_menu";

                    $sql4="select * from system_menu";

                break;

                case 'edit':

                $sql="select * from permission";

                $sql2="select * from permission  where id='$id'";

                $sql3="select * from system_menu";

                $sql4="select * from system_menu";

            }

        }

        $data = $this->db->query($sql)->getResultArray();  

        $data2 = $this->db->query($sql2)->getResultArray()[0]; 

        $data3 = $this->db->query($sql3)->getResultArray(); 

        $data4 = $this->db->query($sql4)->getResultArray(); 

        $data = (count($data) == 1) ? $data[0] : $data;

        $data2 = (count($data2) == 1) ? $data2[0] : $data2;

        $data3 = (count($data3) == 1) ? $data3[0] : $data3;

        return view("pages/Admin/permission_add",  compact("option","data","data2","data3","data4"));





    }
    public function role_permission_add($option='list') {

        $sql="";

        $sql1="";

        $sql2="";

        $session = session();

       $sessionName=$session->userId;

       $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;

        $id           = isset($_REQUEST['id'])?$_REQUEST['id']:null;

        $permission_nameinitial   = isset($_REQUEST['permission_name'])?$_REQUEST['permission_name']:null;

        if($option=='edit')

        {

            

            if($permission_nameinitial!=''){

            $permission_name   = $permission_nameinitial;

            }

            else{

                $permission_name[]   = $permission_nameinitial;

            }

        }

        if($option=='list')

        {

            $permission_name[]   = isset($_REQUEST['permission_name'])?$_REQUEST['permission_name']:null;

        }

        if($option=='add')

        {

            $permission_name   = isset($_REQUEST['permission_name'])?$_REQUEST['permission_name']:null;

        }

        $permissionList =  implode(",", $permission_name);

        $role_id           = isset($_REQUEST['role_id'])?$_REQUEST['role_id']:null;

        if($this->request->getMethod() == 'post') 

        {

            switch ($option) {

                case 'add':

                    $this->db->query("insert into role_permission_details (role,permission) 

                    values ('$role_id','$permissionList')");

                    session()->setFlashdata('message', 'Records Added Successfully !!');

                    return redirect()->to($data['requested_url']);

                    break;

                case 'edit':

                    $this->db->query("update role_permission_details set permission='$permissionList',role='$role_id' where id='$id'"); 

                    session()->setFlashdata('message', 'Records Updated Successfully !!');

                    return redirect()->to($data['requested_url']);

                    break;

            }

        }

        else

        {

            switch ($option) {

                case 'list':

                    $sql="select * from role";

                    $sql1="select * from permission";

                    $sql2="select permission.permission_name,role_permission_details.id,role_permission_details.role,role_permission_details.permission,role.role_name from role_permission_details INNER JOIN permission ON role_permission_details.permission=permission.id INNER JOIN role ON role_permission_details.role=role.id ";

                    break;

                    case 'edit':

                        $sql="select * from role";

                        $sql1="select * from permission";

                         $sql2="select permission.permission_name,role_permission_details.id,role_permission_details.role,role_permission_details.permission,role.role_name from role_permission_details INNER JOIN permission ON role_permission_details.permission=permission.id INNER JOIN role ON role_permission_details.role=role.id WHERE role_permission_details.id='$id'";

                    break;

            }

        }

        $data = $this->db->query($sql)->getResultArray();  

        $data1 = $this->db->query($sql1)->getResultArray(); 

        $data1 = (count($data1) == 1) ? $data1[0] : $data1;

        $data2 = $this->db->query($sql2)->getResultArray();  

        return view("pages/Admin/role_permission_add",  compact("option","data","data1","data2"));

    }
    public function excel_files_upload($option='list'){
        $session = session();

        $sessionName=$session->userId;

        $data = branches($sessionName);

        $data['requested_url'] = $this->session->requested_end_menu_url;

        $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
        $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
        $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;
        $file_name = isset($_REQUEST['file_name']) ? $_REQUEST['file_name'] : null;
        $url = "admin/excel-files-upload?display_id={$display_id}&menu_id={$menu_id}";
        if ($this->request->getMethod() == 'post') {
            {
                $file = $this->request->getFile('userfiles');
                //$file = $_REQUEST['filess'];
                if ($file!='')
                {
                    $randomNumber = mt_rand(10000, 99999);
                    $originalName = $file->getClientName();
                    $fileExtension = $file->getExtension();
                    $file->move(WRITEPATH . 'uploads/excel_files', $randomNumber.$originalName);
                    $db = db_connect();
                    $data = [

                        'file' => $originalName,
                        'file_name' => $file_name,
                        'stored_file_name' => $randomNumber.$originalName

                    ];
                    $db->table('excel_file')->insert($data);
                    session()->setFlashdata('message', 'File Uploaded Successfully !!');
                    return redirect()->to($url);
                    
                }
                else
                {
                    echo $file->getErrorString();
                }
            
            }
                
                  
        } 
        else
        {
            return view("pages/Admin/excel_files_upload",  compact("option"));
        }
    }
    public function client_report($option='list') {
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null; 
        $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null; 
        $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null; 
        $frmDt           = isset($_REQUEST['frmDt'])? date('Y-m-d',strtotime($_REQUEST['frmDt'])):null;
        $toDate           = isset($_REQUEST['toDate'])?date('Y-m-d',strtotime($_REQUEST['toDate'])):null;
        $menu_type           = isset($_REQUEST['menu_type'])?$_REQUEST['menu_type']:null;
        $report_type           = isset($_REQUEST['report'])?$_REQUEST['report']:null;
        if ($this->request->getMethod() == 'post') 
        {
        
            if($menu_type=='W')
            {
                $sql="select *,a.client_code FROM client_master a INNER JOIN client_address b ON a.client_code=b.client_code INNER JOIN client_attention c ON a.client_code=c.client_code WHERE  a.prepared_on between '$frmDt' and '$toDate'";
                $option="with";
            }
            else if($menu_type=='WO')
            {
                $sql="select * FROM client_master WHERE  prepared_on between '$frmDt' and '$toDate'";
                $option="without";
            }
            else
            {
                $message="No data Found";
            }
            $data = $this->db->query($sql)->getResultArray();
            if($report_type=="Excel")
            {
                if($menu_type=='W')
                {
                    $fileName = 'Cient Report With Address & Attention -'.date('d-m-Y').'.xlsx'; 
                }
                else if($menu_type=='WO')
                {
                    $fileName = 'Cient Report Without Address & Attention -'.date('d-m-Y').'.xlsx'; 
                } 
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Define the headings
                if($menu_type=='W')
                {
                    $headings = ['Client Name', 'Client Code', 'Address', 'Attention'];
                }
                else if($menu_type=='WO')
                {
                    $headings = ['Client Name', 'Client Code', 'Created On', 'Created By'];
                }

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
                foreach ($data as $excel){
                    if($menu_type=='W')
                    {
                    $sheet->setCellValue('A' . $rows, $excel['client_name']);
                    $sheet->setCellValue('B' . $rows, $excel['client_code']);
                    $sheet->setCellValue('C' . $rows, $excel['address_line_1'].$excel['address_line_2'].$excel['address_line_2'].$excel['address_line_4']);
                    $sheet->setCellValue('D' . $rows, $excel['attention_name']);
                    }
                    else if($menu_type=='WO')
                    {
                        $sheet->setCellValue('A' . $rows, $excel['client_name']);
                        $sheet->setCellValue('B' . $rows, $excel['client_code']);
                        $sheet->setCellValue('C' . $rows, $excel['prepared_on']);
                        $sheet->setCellValue('D' . $rows, $excel['prepared_by']);
                    }
                    // Apply border to the current row
                    $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
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
            if ($report_type == 'Pdf') {
                $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                $reportHTML = view("pages/Admin/client_report",  compact("option","data","frmDt","toDate","menu_type","report_type"));
                $dompdf->loadHtml($reportHTML);
                $dompdf->setPaper('A4', 'landscape'); // portrait
                $dompdf->render();
                $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
            }
           
           // echo '<pre>'; print_r($data);die;
            return view("pages/Admin/client_report",  compact("option","data","frmDt","toDate","menu_type","report_type"));
        }
        else
        {
            return view("pages/Admin/client_report",  compact("option","menu_type"));
        }
    }
}

?>