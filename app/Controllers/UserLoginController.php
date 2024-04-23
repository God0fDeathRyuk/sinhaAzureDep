<?php

namespace App\Controllers;

class UserLoginController extends BaseController
{
    public function __construct() {
        $this->db = \config\database::connect();
    }
    
    public function login(){
        
        if($this->request->getMethod() == 'post') {
            
            $session = session();
            $userId = $this->request->getPost('userid');
            $userName = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $postPassword = $this->request->getPost('password');
            $roleId = $this->request->getPost('roleid');
            $Accpermission = $this->request->getPost('permission');
            $financialYear = $this->request->getPost('finyr');
            // password('jdh');
            $pkey='Sinha&co';
            // Store a string into the variable which
            // need to be Encrypted
            $simple_string = $password;

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
            $resultUser = $this->db->query("select count(*) count from system_user where user_id = '$userId' and user_password = '$encryption'  and status_code = 'Active'")->getResultArray()[0];

            if($resultUser['count'] > 0) {
            	$session->set('userId', $userId);
            	$session->set('userName', $userName);
            	$session->set('password', $password);
                $session->set('postPassword', $postPassword);
                $session->set('roleId', $roleId);
                $session->set('Accpermission', $Accpermission);
            	$session->set('financialYear', $financialYear); 
            	
            	                if ($userId != 'abm' && $userId != 'admin') { 
                    $user_sql = "select a.branch_code,b.branch_name,b.branch_abbr_name,c.company_code,c.company_name,c.company_abbr_name 
                                    from system_user_branch_permission a, branch_master b, company_master c
                                    where a.user_id = '$userId' and a.default_access_ind = 'Y' and a.branch_code = b.branch_code  and b.company_code = c.company_code ";
                    $user_qry = $this->db->query($user_sql)->getRowArray();
            	    $session->set('user_qry', $user_qry);    
                } else {
                    $user_sql = "select a.branch_code,a.branch_name,a.branch_abbr_name,b.company_code,b.company_name,b.company_abbr_name 
                                    from branch_master a, company_master b where a.default_access_ind = 'Y' and a.company_code = b.company_code ";
                    $user_qry = $this->db->query($user_sql)->getRowArray();
                    
                    if($user_qry['company_name'] == '') { $user_qry['company_name'] = strtoupper('Sinha & Co (Advocates)') ; }
                    if($user_qry['branch_name']  == '') { $user_qry['branch_name']  = 'KOLKATA' ; }

            	    $session->set('user_qry', $user_qry);    
                }

                if ($userId == 'abhijit' || $userId == 'admin') {
                    $session->set('branch_selection_ind', 'Y');
                    $session->set('branch_selection_stmt', "select b.branch_code,b.branch_name from branch_master b where b.branch_code like '%' order by b.branch_name");
        
                } else {
                    $branch_qry = $this->db->query("select * from control_keycodes where key_code = '025'")->getRowArray();
                    $session->set('branch_selection_ind', $branch_qry['key_value']);
                    $session->set('branch_selection_stmt', "select a.branch_code,b.branch_name from system_user_branch_permission a, branch_master b where a.user_id = '$userId' and a.branch_code = b.branch_code order by b.branch_name");
                }
            	
            	return redirect()->to('/dashboard');
            } else {
            	$session->setFlashdata('message', 'Invalid Login Credentials');
            	return redirect()->to('/login');
            }

        } else {
            if(isset(session()->userName)) return redirect()->to(site_url('/'));
            $fin_years = $this->db->query("select * from params where year_close_ind = 'N' order by fin_year desc")->getResultArray();
            $current_fin_year = get_current_fin_year();
            return view("pages/login",  compact("fin_years", "current_fin_year"));
        }
    }

    public function logout(){
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
?>