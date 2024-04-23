<?php

namespace App\Controllers;
use App\Models\User;

class HomeController extends BaseController
{
    public function __construct() {
        $this->db = \config\database::connect();
    }

    public function index() {
        
        // $sql      = "select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ";
        // $login_date = $this->db->query($sql)->getResultArray()[0]['current_date'];
        
        // $sql = "select a.serial_no, a.alert_narration, a.alert_date, a.matter_code, b.matter_desc1, b.matter_desc2
        //           from case_header a, fileinfo_header b
        //          where datediff(a.alert_date, '$login_date') <= 7
        //            and ifnull(a.alert_disp_ind,'Yes') = 'Yes'
        //            and a.matter_code = b.matter_code
        //       order by alert_date";

        // $data = $this->db->query($sql)->getResultArray();
        
        // echo "<pre>"; print_r($sql); die;
        
        $session = session();
        if(isset($session->userName)) {
            $arr['leftMenus'] = menu_data(); 
            $arr['menuHead'] = [0];
            return view("pages/home",  compact("arr"));
        } else {
            return redirect()->to('/login');
        }
    }

    public function home() {
        return view("login");
    }

    public function dashboard() {
        $arr['leftMenus'] = menu_data(); 
        $arr['menuHead'] = [0];
        return view("pages/Dashboard/dashboard",  compact("arr"));
    }
}

