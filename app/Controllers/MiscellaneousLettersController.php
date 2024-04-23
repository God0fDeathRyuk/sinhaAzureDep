<?php

namespace App\Controllers;

class MiscellaneousLettersController extends BaseController
{
    public function __construct() {
        $this->db = \config\database::connect();
        $this->session = session();
    }
    
    /*********************************************************************************************/
    /***************************** Case Details [Transactions] ***********************************/
    /*********************************************************************************************/

    public function actions() {
        $display_id       = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:NULL;
        $param_id         = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:NULL;
        $my_menuid        = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:NULL;
        $screen_ref       = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:NULL;
        $user_option      = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:NULL;
        $index            = isset($_REQUEST['index'])?$_REQUEST['index']:NULL;
        $ord              = isset($_REQUEST['ord'])?$_REQUEST['ord']:NULL;
        $pg               = isset($_REQUEST['pg'])?$_REQUEST['pg']:NULL;
        $search_val       = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:NULL;
        $menu_id          = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:NULL;
        $serial_no        = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:NULL;
        $letter = [];

        $send_mode         = isset($_REQUEST['send_mode']) ? $_REQUEST['send_mode'] : NULL; 
        $remarks           = isset($_POST['remarks']) ? $_POST['remarks'] : NULL; 
        $letter_address    = addslashes(isset($_POST['letter_address'])?$_POST['letter_address']:NULL);
        $letter_desc_ref   = addslashes(isset($_REQUEST['letter_desc_ref'])?$_REQUEST['letter_desc_ref']:NULL);
        $letter_desc_sub   = addslashes(isset($_REQUEST['letter_desc_sub'])?$_REQUEST['letter_desc_sub']:NULL);
        $letter_desc       = addslashes(isset($_REQUEST['letter_desc'])?$_REQUEST['letter_desc']:NULL);
        $letter_body       = addslashes(isset($_REQUEST['letter_body'])?$_REQUEST['letter_body']:NULL);
        $letter_date       = isset($_REQUEST['letter_date'])?$_REQUEST['letter_date']:NULL;
        $letter_client     = addslashes(isset($_REQUEST['letter_client'])?$_REQUEST['letter_client']:NULL);
        $your_client       = addslashes(isset($_REQUEST['your_client'])?$_REQUEST['your_client']:NULL);
        $ip_address        = $_SERVER["REMOTE_ADDR"];
        $global_userid = session()->userId;
        date_default_timezone_set('Asia/Calcutta');
        $letter_year    = date("Y");
        $uploaded_on    = date("Y-m-d");
        $uploaded_by    = $global_userid;
        $uploaded_time  = date("H:i:s");
        $entry_by       = $global_userid;
        $entry_on       = $uploaded_on;
        $entry_time     = $uploaded_time;
        $letter_date    = date_conv($letter_date);
        $finsub        = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:NULL;
        
        if($this->request->getMethod() == 'post') {
            //echo $user_option;die;
            if($finsub=="fsub")
            {
               // $misc_letter = $this->db->table('misc_letter');
                if($user_option == 'Add') {
                    $sql = "SELECT lpad(ifnull(max(letter_serial),0) + 1,9,'0') letter_serial_pad, ifnull(max(letter_serial),0) + 1 letter_serial FROM misc_letter WHERE letter_year = '$letter_year'";
                    $row = $this->db->query($sql)->getRowArray();
                    $letter_serial     = $row['letter_serial'];
                    $letter_serial_pad = $row['letter_serial_pad'];
                    $letter_no = 'S/'.$letter_year.'/'.$letter_serial_pad;
                    
                    $insert_stmt = "insert into misc_letter(letter_year, letter_serial, letter_no, letter_date, send_mode, remarks, letter_address, letter_desc_ref, letter_desc_sub, letter_desc, letter_client, your_client, letter_body, entry_by, entry_on, entry_time,status_code,ip_address)
                                            values('$letter_year', '$letter_serial', '$letter_no', '$letter_date', '$send_mode', '$remarks', '$letter_address', '$letter_desc_ref', '$letter_desc_sub', '$letter_desc', '$letter_client', '$your_client', '$letter_body', '$entry_by', '$entry_on', '$entry_time','A', '$ip_address')";
                    $inst_qry = $this->db->query($insert_stmt);
                    $sl_no = $this->db->insertID();
    
                    session()->setFlashdata('message', 'Record Added Successfully !! [Serial No: '.$sl_no.']');
                    return redirect()->to(session()->last_selected_end_menu);
                }
    
                if($user_option == 'Edit' && $global_userid == 'abhijit')
                {
                    $updt_stmt = "update misc_letter set
                                    send_mode        = '$send_mode',
                                    remarks          = '$remarks',
                                    letter_address   = '$letter_address' ,
                                    letter_desc_ref  = '$letter_desc_ref' ,
                                    letter_desc_sub  = '$letter_desc_sub' ,
                                    letter_desc      = '$letter_desc' ,
                                    letter_client    = '$letter_client' ,
                                    your_client      = '$your_client' ,
                                    letter_body      = '$letter_body' ,
                                    letter_date      = '$letter_date'
                                where serial_no    = '$serial_no'"; 
                    $updt_qry = $this->db->query($updt_stmt) ;	
    
                    session()->setFlashdata('message', 'Record Updated Successfully !!'); 
                }
    
                if($user_option == 'Edit' && $global_userid != 'abhijit')
                {                    
                    $updt_stmt = "update misc_letter set
                                    send_mode        = '$send_mode',
                                    remarks          = '$remarks',
                                    letter_address   = '$letter_address' ,
                                    letter_desc_ref  = '$letter_desc_ref' ,
                                    letter_desc_sub  = '$letter_desc_sub' ,
                                    letter_desc      = '$letter_desc' ,
                                    letter_client    = '$letter_client' ,
                                    your_client      = '$your_client' ,
                                    letter_body      = '$letter_body' ,
                                    letter_date      = '$letter_date',
                                    update_by        = '$global_userid' ,
                                    update_on        = '$uploaded_on' ,
                                    update_time      = '$entry_time' ,
                                    ip_address       = '$ip_address'
                                where serial_no    = '$serial_no'"; 
                    $updt_qry = $this->db->query($updt_stmt) ;
                    
                    session()->setFlashdata('message', 'Record Updated Successfully !!');
                    return redirect()->to(session()->last_selected_end_menu);
                }
    
                if($user_option == 'Copy')
                {
                    $sql = "SELECT lpad(ifnull(max(letter_serial),0) + 1,9,'0') letter_serial_pad, ifnull(max(letter_serial),0) + 1 letter_serial 
                            FROM misc_letter WHERE letter_year = '$letter_year'";
                    // echo'<pre>';print_r($sql);die;
                    
                    $row = $this->db->query($sql)->getRowArray();
                    $letter_serial     = $row['letter_serial'];
                    $letter_serial_pad = $row['letter_serial_pad'];
                    $letter_no = 'S/'.$letter_year.'/'.$letter_serial_pad;
                    
                    $insert_stmt = "insert into misc_letter(letter_year, letter_serial, letter_no, letter_date, send_mode, remarks, letter_address, letter_desc_ref, letter_desc_sub, letter_desc, letter_client, your_client, letter_body, entry_by, entry_on, entry_time,status_code)
                                        select '$letter_year' letter_year, '$letter_serial' letter_serial, '$letter_no' letter_no, letter_date, send_mode, remarks, letter_address, letter_desc_ref, letter_desc_sub, letter_desc, letter_client, your_client, letter_body, '$entry_by' entry_by, '$entry_on' entry_on, '$entry_time' entry_time,status_code
                                        from misc_letter
                                        where serial_no = '$serial_no'";
                    $inst_qry = $this->db->query($insert_stmt);
                    $sl_no = $this->db->insertID();
    
                    session()->setFlashdata('message', 'Record Copied Successfully !! [Serial No: '.$sl_no.']');
                    return redirect()->to(session()->last_selected_end_menu);
                }
            }
            if($finsub=="" || $finsub!="fsub")
            {

                $redv = '';
                if ($user_option == 'Add')      {  $redv = ''; }
                if ($user_option == 'Edit')     {  $redv = ''; }
                if ($user_option == 'Copy')     {  $redv = ''; }
                if ($user_option == 'View')     {  $redv = 'readonly'; }
    
                if($user_option != 'Add') {   
                    $sql = "select * from misc_letter where serial_no = '$serial_no' and status_code = 'A'"; 
                    $row = $this->db->query($sql)->getResultArray()[0];
                    
                    $letter['letter_no']       = $row['letter_no'];
                    $letter['send_mode']       = $row['send_mode'];
                    $letter['remarks']         = $row['remarks'];
                    $letter['letter_address']  = stripslashes($row['letter_address']);
                    $letter['letter_desc']     = stripslashes($row['letter_desc']);
                    $letter['letter_desc_ref'] = stripslashes($row['letter_desc_ref']);
                    $letter['letter_desc_sub'] = stripslashes($row['letter_desc_sub']);
                    $letter['letter_client']   = stripslashes($row['letter_client']);
                    $letter['your_client']     = stripslashes($row['your_client']);
                    $letter['letter_body']     = stripslashes($row['letter_body']);
                    $letter['letter_date']     = date_conv($row['letter_date']);
        
                } else {
                    $letter['letter_no']        = NULL;
                    $letter['send_mode']        = NULL; 
                    $letter['remarks']          = NULL;
                    $letter['letter_address']   = NULL;
                    $letter['letter_desc_ref']  = NULL;
                    $letter['letter_desc_sub']  = NULL;
                    $letter['letter_desc']      = NULL;
                    $letter['letter_client']    = NULL;
                    $letter['your_client']      = NULL;
                    $letter['letter_body']      = NULL;
                }
                // echo "<pre>"; print_r($session); die;
                $params = [
                    "requested_url" => $this->session->requested_end_menu_url,
                ];
                return view('pages/MiscellaneousLetters/crud', compact('letter', 'params', 'user_option', 'redv'));
            }
            
        } 
    }

    public function letter() {
        $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:NULL;
        $serial_no   = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:NULL; $letter = [];

        $row = $this->db->query("select * from misc_letter where serial_no = '$serial_no' and status_code = 'A'")->getRowArray(); 
        $letter['letter_no']      = $row['letter_no'];
        $letter['letter_dt']      = date_conv($row['letter_date']);
        $letter['send_mode']      = $row['send_mode'];
        $letter['remarks']        = $row['remarks'];
        $letter['letter_address'] = stripslashes($row['letter_address']);
        $letter['letter_desc_ref'] = stripslashes($row['letter_desc_ref']);
        $letter['letter_desc_sub'] = stripslashes($row['letter_desc_sub']);
        $letter['letter_desc']    = stripslashes($row['letter_desc']);
        $letter['letter_client']  = stripslashes($row['letter_client']);
        $letter['letter_body']    = stripslashes($row['letter_body']);
        $letter['your_client']    = stripslashes($row['your_client']);
        
            $branch_sql   = $this->db->query("select * from branch_master where branch_code = 'B001' ")->getRowArray();
            $branch_addr1 = $branch_sql['address_line_1'].', '.$branch_sql['city'].' - '.$branch_sql['pin_code'] ;
            $branch_addr2 = 'TEL : '.$branch_sql['phone_no'].'     FAX : '.$branch_sql['fax_no'] ;
            $branch_addr3 = 'E-Mail : '.$branch_sql['email_id'] ;

            $fancy_branch_addr1 = '4, FANCY LANE'.','. ' KOLKATA'.' - '.'700 001' ;
            $fancy_branch_addr2 = 'TEL : '.'91-33-22624821/ 2210-1617/ 1625 '.'     FAX : '.'033-22436176 ' ;
            $fancy_branch_addr3 = 'E-Mail : '.'sinhacofancylane@gmail.com/sinhacofancylane@sinhaco.com' ;
        
            $my_company_name = 'Sinha and Company' ;
        
            $x25thLogoYear   = get_parameter_value('20') ;
            $x25thLogoInd    = (substr($letter['letter_dt'],6,4) == $x25thLogoYear) ? 'Y' : 'N' ;
            
            $params = [
                 "branch_addr1" => $branch_addr1,
                 "branch_addr2" => $branch_addr2,
                 "branch_addr3" => $branch_addr3,
                 "fancy_branch_addr1" => $fancy_branch_addr1,
                 "fancy_branch_addr2" => $fancy_branch_addr2,
                 "fancy_branch_addr3" => $fancy_branch_addr3,
                 "my_company_name" => $my_company_name,
                 "requested_url" => $this->session->requested_end_menu_url,
            ];


            return view('pages/MiscellaneousLetters/letter', compact('letter', 'params','user_option'));

       
    }

    /*********************************************************************************************/
    /***************************** Case Details [Reports] ***********************************/
    /*********************************************************************************************/

    public function list_of_unbilled_notice(){
        $data = branches('demo');
        $global_curr_date2 =  $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate")->getResultArray()[0]['current_dmydate'];
    	$displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220'] ;
        
        if ($this->request->getMethod() == 'post') {
            $requested_url = base_url($_SERVER['REQUEST_URI']);

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
            $report_desc   = 'LIST OF UNBILLED NOTICE(S) AS ON DATE [CLIENT/MATTER-WISE]' ;
          
            //-------
            $ason_date     = $_REQUEST['ason_date'] ;    $ason_date_ymd  = date_conv($ason_date,'-') ;  
            $branch_code   = $_REQUEST['branch_code'] ;
            $client_code   = $_REQUEST['client_code'] ;  if($client_code  == '') { $client_code = '%' ; }
            $client_name   = $_REQUEST['client_name'] ;   
            $matter_code   = $_REQUEST['matter_code'] ;  if($matter_code  == '') { $matter_code = '%' ; }
            $matter_desc   = $_REQUEST['matter_desc'] ;   
            //
            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code'")->getRowArray();
            $branch_name   = $branch_qry['branch_name'] ;

            
            //-------------------------------------------------------------
            $notice_sql = "select a.*, b.client_name, concat(c.matter_desc1,' : ',c.matter_desc2) matter_desc
            from notice_header a, client_master b, fileinfo_header c
            where a.letter_date              <= '$ason_date_ymd'
            and a.ref_billinfo_serial_no   is NULL 
            and a.client_code            like '$client_code'
            and a.matter_code            like '$matter_code'
            and a.client_code               = b.client_code
            and a.matter_code               = c.matter_code
            order by a.letter_date, a.serial_no " ;
            
            $reports  = $this->db->query($notice_sql)->getResultArray();
            $notice_cnt  = count($reports);

            try {
                $reports[0];
                if($notice_cnt == 0)  throw new \Exception('No Records Found !!');

            } catch (\Exception $e) {
                session()->setFlashdata('message', 'No Records Found !!');
                return redirect()->to($this->requested_url());
            }

            $params = [
                "branch_name" => $branch_name,
                "report_desc" => $report_desc,
                "notice_cnt" => $notice_cnt,
                "ason_date" => $ason_date,
                "client_code" => $client_code,
                "client_name" => $client_name,
                "matter_code" => $matter_code,
                "matter_desc" => $matter_desc,
                // "court_code" => $court_code,
                // "court_name" => $court_name,
                // "initial_code" => $initial_code,
                // "initial_name" => $initial_name,
                "date" => date('d-m-Y'), 
                "requested_url" => $this->requested_url(),
            ];
            
            // echo "<pre>"; print_r($notice_sql); die;
            return view('pages/MiscellaneousLetters/list_of_unbilled_notice', compact('data', 'reports', 'params'));
        } else {

            return view('pages/MiscellaneousLetters/list_of_unbilled_notice', compact('data', 'global_curr_date2', 'displayId'));
        }
    }
}
?>