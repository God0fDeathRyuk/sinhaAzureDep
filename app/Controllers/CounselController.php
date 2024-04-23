<?php

namespace App\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CounselController extends BaseController
{
    public function __construct() {
        $this->db = \config\database::connect();
    }
    /*********************************************************************************************/
    /***************************** Counsel [Transactions] ***********************************/
    /*********************************************************************************************/
    public function memo_maint_direct(){
        // echo "sucess!!"; die;
    	$data = branches('demo');
        $displayId   = ['counsel_help_id' => '4013', 'clerk_help_id' => '4017', 'peon_help_id' => '4020', 'matter_help_id' => '4203'] ;
        $data['requested_url'] = session()->requested_end_menu_url;
        $user_id = session()->userId;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $user_option       = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;

        $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index             = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord               = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg                = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val        = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
        $finsub = isset($_REQUEST['finsub']) ? $_REQUEST['finsub'] : null;
        if($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            $row_counter = isset($_REQUEST['row_counter'])?$_POST['row_counter']:null; 
            $serial_no   = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;

            //---------------------------------------- OBJECT CREATION ----------------------------------------------------------//

            $counsel_memo_direct_header_table = $this->db->table("counsel_memo_direct_header");
            $counsel_memo_direct_detail_table = $this->db->table("counsel_memo_direct_detail");
            if($user_option == 'Add') {
                $array = array('serial_no'            => '',
                               'entry_date'           => date_conv($_REQUEST['entry_date']),
                               'branch_code'          => $_REQUEST['branch_code'],
                               //'memo_no'              => $_REQUEST['memo_no'],
                               //'memo_date'            => date_conv($_REQUEST['memo_date']),
                               'counsel_code'         => $_REQUEST['associate_code'],
                               'clerk_code'           => $_REQUEST['clerk_code'],
                               'counsel_fee'          => $_REQUEST['counsel_fee'],
                               'clerk_fee'            => $_REQUEST['clerk_fee'],
                               'peon_fee'             => $_REQUEST['peon_fee'],
                               'counsel_fee_recd'     => $_REQUEST['counsel_fee_recd'],
                               'clerk_fee_recd'       => $_REQUEST['clerk_fee_recd'],
                               'peon_fee_recd'        => $_REQUEST['peon_fee_recd'],
                               'status_code'          => 'A',
                               'prepared_by'          => $user_id,
                               'prepared_on'          => date('d-m-Y'),
                             );
          
                        $counselMemoHdr = $counsel_memo_direct_header_table->insert($array); 
           				// echo '<pre>';print_r($this->db->insertID());die;
                 //----------------------------------------------------------- LAST INSERT ID -----------------------------------------
                $last_memo_serial = $this->db->insertID(); //$this->db->query("select max(serial_no) as serial_no from counsel_memo_direct_header")->getResultArray()[0];//
            	//$last_memo_serial = $last_memo_serial_qry['serial_no'];
            // echo '<pre>';print_r($last_memo_serial);die;
                $row_count = $k = 1;
                for($i=1; $row_count <= $row_counter; $i++)
                {
                    if(isset($_REQUEST['voucher_ok_ind'.$i], $_REQUEST['brief_date'.$i], $_REQUEST['matter_code'.$i], $_REQUEST['counsel_fee'.$i])) {
                   
                        if($_REQUEST['voucher_ok_ind'.$i]=='Y' && !empty($_REQUEST['brief_date'.$i]) && !empty($_REQUEST['matter_code'.$i]) && !empty($_REQUEST['counsel_fee'.$i])) {  
                            
                            $array = array( 'ref_counsel_memo_serial_no' => $last_memo_serial,
                                            'row_no'                     => $k,
                                            'matter_code'                => $_REQUEST['matter_code'.$i],
                                            'client_code'                => $_REQUEST['client_code'.$i],
                                            'initial_code'               => $_REQUEST['initial_code'.$i],
                                            'brief_date'                 => date_conv($_REQUEST['brief_date'.$i]),
                                            'memo_no'                    => $_REQUEST['memo_no'.$i],
                                            'memo_date'                  => date_conv($_REQUEST['memo_date'.$i]),
                                            //'narration'                  => $_REQUEST['narration'.$i],
                                            'counsel_fee'                => $_REQUEST['counsel_fee'.$i],
                                            'clerk_fee'                  => $_REQUEST['clerk_fee'.$i],
                                            'peon_fee'                   => $_REQUEST['peon_fee'.$i],
                                            'counsel_fee_recd'           => $_REQUEST['counsel_fee_recd'.$i],
                                            'clerk_fee_recd'             => $_REQUEST['clerk_fee_recd'.$i],
                                            'peon_fee_recd'              => $_REQUEST['peon_fee_recd'.$i],
                                        );
         
                            $memo_detl = $counsel_memo_direct_detail_table->insert($array);
                            $k++;
                        }
                        $row_count++;
                    }
                }
                session()->setFlashdata('message', 'Record Added Successfully !! [Serial No: '.$last_memo_serial.']');
                return redirect()->to(session()->last_selected_end_menu);
            } else if($user_option == 'Select') {
                $array = array('branch_code'          => $_REQUEST['branch_code'],
                               //'memo_no'              => $_REQUEST['memo_no'],
                               //'memo_date'            => date_conv($_REQUEST['memo_date']),
                               'counsel_code'         => $_REQUEST['associate_code'],
                               'clerk_code'           => $_REQUEST['clerk_code'],
                               'counsel_fee'          => $_REQUEST['counsel_fee'],
                               'clerk_fee'            => $_REQUEST['clerk_fee'],
                               'peon_fee'             => $_REQUEST['peon_fee'],
                               'counsel_fee_recd'     => $_REQUEST['counsel_fee_recd'],
                               'clerk_fee_recd'       => $_REQUEST['clerk_fee_recd'],
                               'peon_fee_recd'        => $_REQUEST['peon_fee_recd'],
                              );
          
                        $where = "serial_no = '".$_REQUEST['serial_no']."'";
                        $counselMemoHdr = $counsel_memo_direct_header_table->update($array,$where);

                $where = "counsel_memo_direct_detail.ref_counsel_memo_serial_no = '".$_REQUEST['serial_no']."'";
                $counselMemoDtl_del = $counsel_memo_direct_detail_table->delete($where);
        
                $row_count = $k = 1;
                for($i=1; $row_count<=$row_counter; $i++)
                {
                    if(isset($_REQUEST['voucher_ok_ind'.$i], $_REQUEST['brief_date'.$i], $_REQUEST['matter_code'.$i], $_REQUEST['counsel_fee'.$i])) {
                        if($_REQUEST['voucher_ok_ind'.$i]=='Y' && !empty($_REQUEST['brief_date'.$i]) && !empty($_REQUEST['matter_code'.$i]) && !empty($_REQUEST['counsel_fee'.$i])) {   
                          
                            $array = array( 'ref_counsel_memo_serial_no' => $_REQUEST['serial_no'],
                                            'row_no'                     => $k,
                                            'matter_code'                => $_REQUEST['matter_code'.$i],
                                            'client_code'                => $_REQUEST['client_code'.$i],
                                            'initial_code'               => $_REQUEST['initial_code'.$i],
                                            'brief_date'                 => date_conv($_REQUEST['brief_date'.$i]),
                                            'memo_no'                    => $_REQUEST['memo_no'.$i],
                                            'memo_date'                  => date_conv($_REQUEST['memo_date'.$i]),
                                            //'narration'                  => $_REQUEST['narration'.$i],
                                            'counsel_fee'                => $_REQUEST['counsel_fee'.$i],
                                            'clerk_fee'                  => $_REQUEST['clerk_fee'.$i],
                                            'peon_fee'                   => $_REQUEST['peon_fee'.$i],
                                            'counsel_fee_recd'           => $_REQUEST['counsel_fee_recd'.$i],
                                            'clerk_fee_recd'             => $_REQUEST['clerk_fee_recd'.$i],
                                            'peon_fee_recd'              => $_REQUEST['peon_fee_recd'.$i],
                                            'instrument_no'              => $_REQUEST['instrument_no'.$i],
                                            'instrument_date'            => date_conv($_REQUEST['instrument_date'.$i]),
                                            );
            
                            $memo_detl = $counsel_memo_direct_detail_table->insert($array);
                            $k++;
                        }
                        $row_count++;
                    }
                }
                session()->setFlashdata('message', 'Record Updated Successfully !!');
            } else if($user_option == 'Receive') {
                $array = array('branch_code'          => $_REQUEST['branch_code'],
                               //'memo_no'              => $_REQUEST['memo_no'],
                               //'memo_date'            => date_conv($_REQUEST['memo_date']),
                               'counsel_code'         => $_REQUEST['associate_code'],
                               'clerk_code'           => $_REQUEST['clerk_code'],
                               'counsel_fee'          => $_REQUEST['counsel_fee'],
                               'clerk_fee'            => $_REQUEST['clerk_fee'],
                               'peon_fee'             => $_REQUEST['peon_fee'],
                               'counsel_fee_recd'     => $_REQUEST['counsel_fee_recd'],
                               'clerk_fee_recd'       => $_REQUEST['clerk_fee_recd'],
                               'peon_fee_recd'        => $_REQUEST['peon_fee_recd'],
                              );
          
                        $where = "serial_no = '".$_REQUEST['serial_no']."'";
                        $counselMemoHdr = $counsel_memo_direct_header_table->update($array,$where);

                $where = "counsel_memo_direct_detail.ref_counsel_memo_serial_no = '".$_REQUEST['serial_no']."'";
                $counselMemoDtl_del = $counsel_memo_direct_detail_table->delete($where);
        
                $row_count = $k = 1;
                for($i=1; $row_count <= $row_counter; $i++)
                {
                    if(isset($_REQUEST['voucher_ok_ind'.$i], $_REQUEST['brief_date'.$i], $_REQUEST['matter_code'.$i], $_REQUEST['counsel_fee'.$i])) {
                        if($_REQUEST['voucher_ok_ind'.$i]=='Y' && !empty($_REQUEST['brief_date'.$i]) && !empty($_REQUEST['matter_code'.$i]) && !empty($_REQUEST['counsel_fee'.$i])){   
                            
                            $array = array( 'ref_counsel_memo_serial_no' => $_REQUEST['serial_no'],
                                            'row_no'                     => $k,
                                            'matter_code'                => $_REQUEST['matter_code'.$i],
                                            'client_code'                => $_REQUEST['client_code'.$i],
                                            'initial_code'               => $_REQUEST['initial_code'.$i],
                                            'brief_date'                 => date_conv($_REQUEST['brief_date'.$i]),
                                            'memo_no'                    => $_REQUEST['memo_no'.$i],
                                            'memo_date'                  => date_conv($_REQUEST['memo_date'.$i]),
                                            //'narration'                  => $_REQUEST['narration'.$i],
                                            'counsel_fee'                => $_REQUEST['counsel_fee'.$i],
                                            'clerk_fee'                  => $_REQUEST['clerk_fee'.$i],
                                            'peon_fee'                   => $_REQUEST['peon_fee'.$i],
                                            'counsel_fee_recd'           => $_REQUEST['counsel_fee_recd'.$i],
                                            'clerk_fee_recd'             => $_REQUEST['clerk_fee_recd'.$i],
                                            'peon_fee_recd'              => $_REQUEST['peon_fee_recd'.$i],
                                            'instrument_no'              => $_REQUEST['instrument_no'.$i],
                                            'instrument_date'            => date_conv($_REQUEST['instrument_date'.$i]),
            
                                            );
            
                            $memo_detl = $counsel_memo_direct_detail_table->insert($array);
                            $k++;
                        }
                        $row_count++;
                    }
                } 
                session()->setFlashdata('message', 'Record Received Successfully !!');
            } else if($user_option == 'Delete') {
                 $serial_no  = $_REQUEST['serial_no'];
          
                 $where = "serial_no = '$serial_no'";
                 $counsel_hdr_del = $counsel_memo_direct_header_table->delete($where);
          
                 $where = "counsel_memo_direct_detail.ref_counsel_memo_serial_no = '".$_REQUEST['serial_no']."'";
                 $counselMemoDtl_del = $counsel_memo_direct_detail_table->delete($where);
                
                session()->setFlashdata('message', 'Record Deleted Successfully !!');
                return redirect()->to(base_url(session()->last_selected_end_menu));
            }
            return redirect()->to($url);
        }
        if($finsub=="" || $finsub!="fsub")
        {
            $serial_no = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;
            $hdr_row = [];
            $redkc = ''; $redk = ''; $redv = ''; $disv = ''; $redvc = '';
            if($user_option != 'Add')
            {
                $header_query =    "SELECT  a.*, b.associate_name, c.associate_name clerk_name
                                        FROM  associate_master b, counsel_memo_direct_header a 
                                LEFT JOIN  associate_master c
                                        ON  a.clerk_code = c.associate_code
                                    WHERE  a.serial_no = '$serial_no'
                                        AND  a.counsel_code = b.associate_code";
                $hdr_row =$this->db->query($header_query)->getRowArray();
                
                $serial_no            = $hdr_row['serial_no'];
            
            }
    
            if ($user_option == 'Add' )     { $redkc = 'readonly' ; $redk = 'readonly' ;  $redv = '';          $disv = ''         ; }
            if ($user_option == 'Select')   { $redkc = 'readonly' ; $redk = 'readonly' ;  $redv = '';          $disv = ''         ; }
            if ($user_option == 'Delete')   { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }
            if ($user_option == 'Receive')  { $redvc = 'readonly' ; $redk = 'readonly' ;  $redv = '';          $disv = ''         ; }
            if ($user_option == 'View' )    { $redkc = 'readonly' ; $redvc = 'readonly' ;  $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }
    
            $params["status_desc"] = 'Active';
    
            $dtl_query = "SELECT a.*, b.matter_desc1, b.matter_desc2, trim(concat(b.matter_desc1,' ',b.matter_desc2)) matter_desc 
                        FROM counsel_memo_direct_detail a, fileinfo_header b
                        WHERE a.ref_counsel_memo_serial_no = '$serial_no'
                        AND a.matter_code = b.matter_code";
            $reports  = $this->db->query($dtl_query)->getResultArray() ;
            // echo '<pre>'; print_r($user_option);die;
            $count  = count($reports);
            $params["requested_url"] = $data['requested_url']; 
    
            return view("pages/Counsel/counsel_direct_payment_entry", compact("hdr_row", "data", "reports", "params", "count", "user_option", "displayId", "redkc", "redk", "redv", "disv", "redvc"));
        }
        }

    }
    public function memo_entry() {
        $data['requested_url'] = session()->requested_end_menu_url;
        $user_id = session()->userId;
        $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $param_id          = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
        $my_menuid         = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
        $user_option       = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
        
        $display_id        = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
        $menu_id           = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
        $screen_ref        = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
        $index             = isset($_REQUEST['index'])?$_REQUEST['index']:null;
        $ord               = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
        $pg                = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
        $search_val        = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;

        $row_counter      = isset($_POST['row_counter'])?$_POST['row_counter']:null; 
        $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;
        $finsub         = isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
        $url = "master-list?display_id={$display_id}&menu_id={$menu_id}";
        if($this->request->getMethod() == 'post') {
            if($finsub=="fsub")
            {
            //======================================= Declaring Database table variables ==============================================
            $counsel_memo_header_table = $this->db->table('counsel_memo_header');
            $counsel_memo_detail_table = $this->db->table('counsel_memo_detail');

            //================================================== WHEN : ADD ===========================================================
            if($user_option == 'Add') {
                //------------------------------------------- ADDING RECORD :  COUNSEL MEMO HEADER  ---------------------------------
                $array = array('serial_no'            => '',
                                'entry_date'           => date_conv($_POST['entry_date']),
                                'branch_code'          => $_POST['branch_code'],
                                'memo_no'              => $_POST['memo_no'],
                                'memo_date'            => date_conv($_POST['memo_date']),
                                'counsel_code'         => $_POST['associate_code'],
                                'clerk_code'           => $_POST['clerk_code'],
                                'peon_code'            => $_POST['peon_code'],
                                'counsel_fee'          => $_POST['counsel_fee'],
                                'clerk_fee'            => $_POST['clerk_fee'],
                                'peon_fee'             => $_POST['peon_fee'],
                                'service_tax_fee'      => $_POST['service_tax_fee'],
                                'status_code'          => 'A',
                                'prepared_by'          => $user_id,
                                'prepared_on'          => date('d-m-Y'),
                            );
                $counsel_memo_header_table->insert($array);
                $last_memo_serial = $this->db->insertID();  // LAST INSERT ID
                            
                //------------------------------------------- ADDING RECORD :  COUNSEL MEMO DETAIL  -----------------------------------
                $row_count = $k = 1;
                for($i=1; $row_count <= $row_counter; $i++) {
                    if(isset($_REQUEST['voucher_ok_ind'.$i], $_REQUEST['brief_date'.$i], $_REQUEST['matter_code'.$i], $_REQUEST['narration'.$i], $_REQUEST['counsel_fee'.$i])) {

                        if($_POST['voucher_ok_ind'.$i]=='Y' && !empty($_POST['brief_date'.$i]) && !empty($_POST['matter_code'.$i]) && !empty($_POST['narration'.$i]) && !empty($_POST['counsel_fee'.$i])) {   
      
                              $array = array( 'ref_counsel_memo_serial_no' => $last_memo_serial,
                                              'row_no'                     => $k,
                                              'matter_code'                => $_POST['matter_code'.$i],
                                              'client_code'                => $_POST['client_code'.$i],
                                              'initial_code'               => $_POST['initial_code'.$i],
                                              'brief_date'                 => date_conv($_POST['brief_date'.$i]),
                                              'narration'                  => $_POST['narration'.$i],
                                              'counsel_fee'                => $_POST['counsel_fee'.$i],
                                              'clerk_fee'                  => $_POST['clerk_fee'.$i],
                                              'peon_fee'                  => $_POST['peon_fee'.$i],
                                              'new_tax_code'               => NULL,
                                              'new_tax_percent'            => $_POST['new_tax_percent'.$i],
                                              'new_tax_amount'             => $_POST['new_tax_amount'.$i],
                                              'new_tax_cess_percent'       => $_POST['new_tax_cess_percent'.$i],
                                              'new_tax_cess_amount'        => $_POST['new_tax_cess_amount'.$i],
                                              'new_tax_hecess_percent'     => $_POST['new_tax_hecess_percent'.$i],
                                              'new_tax_hecess_amount'      => $_POST['new_tax_hecess_amount'.$i],
                                              'new_tax_total_percent'      => $_POST['new_tax_total_percent'.$i],
                                              'new_tax_total_amount'       => $_POST['new_tax_total_amount'.$i],
                                              'service_tax_amount'         => $_POST['new_tax_total_amount'.$i],
                                              );
                              $counsel_memo_detail_table->insert($array);
                              $k++;
                          }
                          $row_count++;
                    }
                }
                session()->setFlashdata('message', 'Record Added Successfully !! [Serial No: '.$last_memo_serial.']');
                return redirect()->to(session()->last_selected_end_menu);
            } //================================================== WHEN : EDIT ===========================================================
            else if($user_option == 'Edit') {
                $array = array('branch_code'          => $_POST['branch_code'],
                                'memo_no'              => $_POST['memo_no'],
                                'memo_date'            => date_conv($_POST['memo_date']),
                                'counsel_code'         => $_POST['associate_code'],
                                'clerk_code'           => $_POST['clerk_code'],
                                'peon_code'            => $_POST['peon_code'],
                                'counsel_fee'          => $_POST['counsel_fee'],
                                'clerk_fee'            => $_POST['clerk_fee'],
                                'peon_fee'             => $_POST['peon_fee'],
                                'service_tax_fee'      => $_POST['service_tax_fee'],
                                );
                $where = "serial_no = '".$_POST['serial_no']."'";
                $counsel_memo_header_table->update($array, $where);

                //------------------------------------------- MODIFY RECORD :  COUNCEL MEMO DETAIL  ---------------------------------------
                $where = "counsel_memo_detail.ref_counsel_memo_serial_no = '".$_POST['serial_no']."'";
                $counsel_memo_detail_table->delete($where);

                $row_count = $k = 1;
                for($i=1; $row_count <= $row_counter; $i++) {
                    if(isset($_REQUEST['voucher_ok_ind'.$i], $_REQUEST['brief_date'.$i], $_REQUEST['matter_code'.$i], $_REQUEST['narration'.$i], $_REQUEST['counsel_fee'.$i])) {

                        if($_POST['voucher_ok_ind'.$i]=='Y' && !empty($_POST['brief_date'.$i]) && !empty($_POST['matter_code'.$i]) && !empty($_POST['narration'.$i]) && !empty($_POST['counsel_fee'.$i])) {  
                            $array = array( 'ref_counsel_memo_serial_no' => $_POST['serial_no'],
                                            'row_no'                     => $k,
                                            'matter_code'                => $_POST['matter_code'.$i],
                                            'client_code'                => $_POST['client_code'.$i],
                                            'initial_code'               => $_POST['initial_code'.$i],
                                            'brief_date'                 => date_conv($_POST['brief_date'.$i]),
                                            'narration'                  => $_POST['narration'.$i],
                                            'counsel_fee'                => $_POST['counsel_fee'.$i],
                                            'clerk_fee'                  => $_POST['clerk_fee'.$i],
                                            'peon_fee'                   => $_POST['peon_fee'.$i],
                                            'new_tax_code'               => NULL,
                                            'new_tax_percent'            => $_POST['new_tax_percent'.$i],
                                            'new_tax_amount'             => $_POST['new_tax_amount'.$i],
                                            'new_tax_cess_percent'       => $_POST['new_tax_cess_percent'.$i],
                                            'new_tax_cess_amount'        => $_POST['new_tax_cess_amount'.$i],
                                            'new_tax_hecess_percent'     => $_POST['new_tax_hecess_percent'.$i],
                                            'new_tax_hecess_amount'      => $_POST['new_tax_hecess_amount'.$i],
                                            'new_tax_total_percent'      => $_POST['new_tax_total_percent'.$i],
                                            'new_tax_total_amount'       => $_POST['new_tax_total_amount'.$i],
                                            'service_tax_amount'         => $_POST['new_tax_total_amount'.$i],
                                            );
                            $counsel_memo_detail_table->insert($array);
                            $k++;
                        }
                        $row_count++;
                    }
                }
                session()->setFlashdata('message', 'Record Updated Successfully !!');
            } //================================================== WHEN : DELETE =====================================================
            else if($user_option == 'Delete') {
                $serial_no  = $_POST['serial_no'];

                $where = "serial_no = '$serial_no'";
                $counsel_memo_header_table->delete($where);   // DELETE : NOTICE_HEADER

                $where = "counsel_memo_detail.ref_counsel_memo_serial_no = '".$_POST['serial_no']."'";
                $counsel_memo_detail_table->delete($where);   // DELETE : NOTICE_DETAIL
                                
                session()->setFlashdata('message', 'Record Deleted Successfully !!');
                return redirect()->to(base_url(session()->last_selected_end_menu));
            }
            return redirect()->to($url);
        }
        if($finsub=="" || $finsub!="fsub")
        {

            $displayId   = ['counsel_help_id' => '4013', 'clerk_help_id' => '4017', 'peon_help_id' => '4020', 'matter_help_id' => '4203'] ; //4220
            $user_id = session()->userId;
            $data = branches($user_id); $hdr_row = [];
            if($user_option != 'Add') {
                $header_query =    "SELECT  a.*, b.associate_name, b.pan_no counsel_pan, c.pan_no clerk_pan, d.pan_no peon_pan,  c.associate_name clerk_name, d.associate_name peon_name
                                    FROM  associate_master b, counsel_memo_header a  LEFT JOIN  associate_master c ON  a.clerk_code = c.associate_code LEFT JOIN  associate_master d ON  a.peon_code = d.associate_code
                                    WHERE  a.serial_no = '$serial_no' AND  a.counsel_code = b.associate_code";
                $hdr_row = $this->db->query($header_query)->getRowArray();
                $serial_no = $hdr_row['serial_no'];
            }

            $dtl_query =    "SELECT a.*, b.matter_desc1, b.matter_desc2, trim(concat(b.matter_desc1,' ',b.matter_desc2)) matter_desc 
                            FROM counsel_memo_detail a, fileinfo_header b
                            WHERE a.ref_counsel_memo_serial_no = '$serial_no' AND a.matter_code = b.matter_code";

            $response = $this->db->query($dtl_query)->getResultArray();
            // echo "<pre>"; print_r($response); die;
            $count = count($response);

            $redk = $redv = $disv = '';
            if ($user_option == 'Add' )     { $redk = 'readonly' ;  $redv = '';          $disv = ''         ; }
            if ($user_option == 'Edit')     { $redk = 'readonly' ;  $redv = '';          $disv = ''         ; }
            if ($user_option == 'Delete')   { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }
            if ($user_option == 'View')     { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; }
    
            $status_desc = 'Active';
            return view("pages/Counsel/counsel_memo_entry", compact("hdr_row", "data", "user_option", "response", "status_desc", "count", "displayId", "redk", "redv", "disv"));
        }
        } 
    }
    /*********************************************************************************************/
    /***************************** Counsel [Reports] ***********************************/
    /*********************************************************************************************/

    public function counsel_memo_credited(){
        if($this->request->getMethod() == 'post') {
            $display_id = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
            $param_id = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
            $my_menuid = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
            $menu_id = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
            $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
            $screen_ref = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
            $index = isset($_REQUEST['index'])?$_REQUEST['index']:null;
            $ord = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
            $pg = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
            $search_val = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
            $output_type = $_REQUEST['output_type']; 

            $start_date = $_REQUEST['start_date'] ;       
            if($start_date != '') {$start_date_ymd = date_conv($start_date) ;} else {$start_date_ymd = '1901-01-01' ; }

            $end_date = $_REQUEST['end_date'] ;         
            if($end_date != '') {$end_date_ymd   = date_conv($end_date)   ;} else {$end_date_ymd   = date('Y-m-d') ; }

            $branch_code = $_REQUEST['branch_code'] ;
            $counsel_code = $_REQUEST['counsel_code'] ;     
            if($counsel_code == '') { $counsel_code = '%' ; }

            $report_desc = 'List of Counsel Memo(s) Credited';
            
            if($output_type == 'Report' || $output_type == 'Pdf') {
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                $branch_name = $branch_qry['branch_name'] ;

                $memo_sql =    "select a.serial_no,a.memo_no,a.memo_date,a.counsel_code,b.client_code,b.matter_code,b.narration, b.counsel_fee+b.clerk_fee AS counsel_amount, b.counsel_fee+b.clerk_fee+b.service_tax_amount AS totcr_amount,  b.service_tax_amount stax_amount, c.associate_name, d.client_name, concat(e.matter_desc1,' : ',e.matter_desc2)AS matter_desc, f.doc_no, f.doc_date
                from counsel_memo_header a left outer join ledger_trans_hdr f on a.ref_ledger_serial_no  =  f.serial_no
                left outer join associate_master c on a.counsel_code =  c.associate_code left outer join counsel_memo_detail b on a.serial_no =  b.ref_counsel_memo_serial_no
                left outer join client_master d on b.client_code =  d.client_code left outer join fileinfo_header e on b.matter_code =  e.matter_code
                where a.branch_code like '$branch_code' and a.counsel_code like '$counsel_code' and a.counsel_fee_jv + a.clerk_fee_jv > 0 and f.doc_date between  '$start_date_ymd' and '$end_date_ymd' order by c.associate_name,f.doc_date,f.doc_no";
                
                $reports  = $this->db->query($memo_sql)->getResultArray() ;
                $memo_cnt  = count($reports);
                $date = date('d-m-Y');
                
                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                    "counsel_code" => $counsel_code,
                    "memo_cnt" =>$memo_cnt,
                    "start_date" =>$start_date,
                    "end_date" =>$end_date,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/Counsel/counsel_memo_credited",  compact("reports", "params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/Counsel/counsel_memo_credited",  compact("reports","params"));
                
            } else if($output_type == 'Excel') { 
                $branch_qry = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                $branch_name = $branch_qry['branch_name'] ;

                $memo_sql =    "select a.serial_no,a.memo_no,a.memo_date,a.counsel_code,b.client_code,b.matter_code,b.narration, b.counsel_fee+b.clerk_fee AS counsel_amount, b.counsel_fee+b.clerk_fee+b.service_tax_amount AS totcr_amount,  b.service_tax_amount stax_amount, c.associate_name, d.client_name, concat(e.matter_desc1,' : ',e.matter_desc2)AS matter_desc, f.doc_no, f.doc_date
                from counsel_memo_header a left outer join ledger_trans_hdr f on a.ref_ledger_serial_no  =  f.serial_no
                left outer join associate_master c on a.counsel_code =  c.associate_code left outer join counsel_memo_detail b on a.serial_no =  b.ref_counsel_memo_serial_no
                left outer join client_master d on b.client_code =  d.client_code left outer join fileinfo_header e on b.matter_code =  e.matter_code
                where a.branch_code like '$branch_code' and a.counsel_code like '$counsel_code' and a.counsel_fee_jv + a.clerk_fee_jv > 0 and f.doc_date between  '$start_date_ymd' and '$end_date_ymd' order by c.associate_name,f.doc_date,f.doc_no";
                
                $reports  = $this->db->query($memo_sql)->getResultArray() ;
                $memo_cnt  = count($reports);
                $date = date('d-m-Y');
                
                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $fileName = 'Counsel-Memo-Credited-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Define the headings
                $headings = ['Srl#', 'Memo No', 'Memo Dt', 'Client/Matter', 'Amount', 'S Tax', 'Total Amount', 'JV No/Dt'];

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
                $tcramt  = 0; 
        		$tstamt  = 0; 
        		$ttcramt = 0;
        		$tcfamt  = 0; 
        		$tfjamt  = 0;
        		$rowcnt  = 1 ;
        		$report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
        		$report_cnt = $memo_cnt;
        		
        		while ($rowcnt <= $report_cnt) {
            		$mcramt   = 0;
            		$mstamt   = 0;
            		$mtcramt  = 0; 
            		$pcnslind = 'Y';
            		$pcnslcd  = $report_row['counsel_code'] ;
            		$pcnslnm  = $report_row['associate_name'] ;
            		
            		while ($pcnslcd == $report_row['counsel_code'] && $rowcnt <= $report_cnt) {
            			if($pcnslind == 'Y') {
            			    $sheet->setCellValue('A' . $rows, strtoupper($pcnslnm)); 
        			        $pcnslind = 'N';
        			        
                            $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
        			        // Appling cell merging
            			    $style->getActiveSheet()->mergeCells('A'.$rows.':H'.$rows);
        			        // Apply Background Color to the current row
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('5c5ccf');
            			    $rows++;
        			    }
                        $sheet->setCellValue('A' . $rows, $report_row['serial_no']);
                        $sheet->setCellValue('B' . $rows, $report_row['memo_no']);
                        $sheet->setCellValue('C' . $rows, $report_row['memo_date']);
                        $sheet->setCellValue('D' . $rows, $report_row['client_name']);
                        $sheet->setCellValue('E' . $rows, $report_row['counsel_amount']);
                        $sheet->setCellValue('F' . $rows, $report_row['stax_amount']);
                        $sheet->setCellValue('G' . $rows, $report_row['totcr_amount']);
                        $sheet->setCellValue('H' . $rows, $report_row['doc_no']);
                        
                        // Apply border to the current row
                        $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        
                        $rows++;
                        $sheet->setCellValue('D' . $rows, $report_row['matter_desc']);
                        $sheet->setCellValue('H' . $rows, $report_row['doc_date']);
                		$mcramt = $mcramt + $report_row['counsel_amount'] ; 
        				$mstamt = $mstamt + $report_row['stax_amount'] ;                   
        				$mtcramt = $mtcramt + $report_row['totcr_amount'] ;                   
        
        				$report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
        				$rowcnt = $rowcnt + 1;
                        $rows++;
                    }  
            		$tcramt  = $tcramt + $mcramt ; 
            		$tstamt  = $tstamt + $mstamt ;                   
            		$ttcramt = $ttcramt + $mtcramt ;  
                   
                    $sheet->setCellValue('D' . $rows, 'TOTAL');
                    $sheet->setCellValue('E' . $rows, number_format($mcramt,2,'.',''));
                    $sheet->setCellValue('F' . $rows, number_format($mstamt,2,'.',''));
                    $sheet->setCellValue('G' . $rows, number_format($mtcramt,2,'.',''));
                    
                    // Apply Background Color to the current row
                    $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;
                }
                
                $sheet->setCellValue('D' . $rows, 'GRAND TOTAL');
                $sheet->setCellValue('E' . $rows, number_format($tcramt,2,'.',''));
                $sheet->setCellValue('F' . $rows, number_format($tstamt,2,'.',''));
                $sheet->setCellValue('G' . $rows, number_format($ttcramt,2,'.',''));
                
                // Apply Background Color to the current row
                $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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
                
            } else {
                session()->setFlashdata('message', 'Please Select The Output Type !!');
                return redirect()->to($this->requested_url());
            }
        } else {
            $user_id = session()->userId;
            $fin_year = session()->financialYear;
            $data = branches($user_id);
            
            $curr_fyrsdt = '01-04-'.substr($fin_year,0,4);
            $displayId   = ['counsel_help_id' => '4011'];

            return view("pages/Counsel/counsel_memo_credited",  compact("data", "displayId", "curr_fyrsdt"));
        }
    }
    public function counsel_memo_os() {
        if($this->request->getMethod() == 'post') {
            $clients = [];
            $display_id = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
            $param_id = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
            $my_menuid = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
            $menu_id = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
            $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
            $screen_ref = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
            $index = isset($_REQUEST['index'])?$_REQUEST['index']:null;
            $ord = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
            $pg = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
            $search_val = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
            $output_type = $_REQUEST['output_type']; 
            $report_desc = "LIST OF OUTSTANDING COUNSEL MEMO DETAILS" ;
            $ason_date = isset($_REQUEST['ason_date']) ? $_REQUEST['ason_date'] : NULL ; $ason_date_ymd = date_conv($ason_date, '-');  
            $branch_code = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : NULL;
            $counsel_code = isset($_REQUEST['counsel_code']) ? $_REQUEST['counsel_code'] : NULL;   
            $counsel_name = isset($_REQUEST['counsel_name']) ? $_REQUEST['counsel_name'] : NULL;   
            $initial_code = isset($_REQUEST['initial_code']) ? $_REQUEST['initial_code']: NULL;  
            
            if ($counsel_code == '') { $counsel_desc = 'ALL' ; } else { $counsel_desc = $counsel_name ; } 
            if ($initial_code == '%') { $initial_desc = 'ALL' ; } else { $initial_desc = get_initial_name($initial_code) ; } 
            
            if($output_type == 'Report' || $output_type == 'Pdf') {
                $branch_qry = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                $branch_name = (!empty($branch_qry)) ? $branch_qry['branch_name'] : '';
                
                // making where condition 
                $branch_code_con = ($branch_code != '') ? "a.branch_code = '$branch_code' AND" : '';
                $counsel_code_con = ($counsel_code != '') ? "a.counsel_code = '$counsel_code' AND" : '';
                $ason_date_ymd_con = ($ason_date_ymd != '') ? "a.entry_date <= '$ason_date_ymd' AND" : '';
                $initial_code_con = ($initial_code != '%') ? "AND b.initial_code = '$initial_code'" : "";

                $where_condition = "$branch_code_con $counsel_code_con $ason_date_ymd_con (COALESCE(a.counsel_fee_jv, 0) + COALESCE(a.clerk_fee_jv, 0)) = 0 $initial_code_con ORDER BY c.associate_name, a.memo_date DESC";
    
                $memo_sql = "SELECT a.serial_no, a.memo_no, a.memo_date, a.counsel_code, b.client_code, b.matter_code, b.initial_code, b.narration,
                COALESCE(b.counsel_fee, 0) + COALESCE(b.clerk_fee, 0) AS os_amount,
                COALESCE(b.counsel_fee, 0) + COALESCE(b.clerk_fee, 0) + COALESCE(b.service_tax_amount, 0) AS totos_amount,
                COALESCE(b.service_tax_amount, 0) AS stax_amount, c.associate_name AS counsel_name
                FROM counsel_memo_header a JOIN counsel_memo_detail b ON a.serial_no = b.ref_counsel_memo_serial_no
                JOIN associate_master c ON a.counsel_code = c.associate_code WHERE $where_condition";

                $reports  = $this->db->query($memo_sql)->getResultArray(); $memo_cnt  = count($reports);

                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                foreach ($reports as $key => $report) {
                    $client_qry   = $this->db->query("select client_name from client_master where client_code = '$report[client_code]'")->getRowArray();
                    $clients[$key]['client_name']  = isset($client_qry['client_name']) ? $client_qry['client_name'] : ''; 
                    
                    $matter_qry   = $this->db->query("select matter_desc1, matter_desc2 from fileinfo_header where matter_code = '$report[matter_code]'")->getRowArray() ;
                    $clients[$key]['matter_desc1'] = isset($matter_qry['matter_desc1']) ? $matter_qry['matter_desc1'] : '' ; 
                    $clients[$key]['matter_desc2'] = isset($matter_qry['matter_desc2']) ? $matter_qry['matter_desc2'] : ''; 
                }
                $date = date('d-m-Y');
            
                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "date" => $date,
                    "ason_date" => $ason_date,
                    "counsel_code" => $counsel_code,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                    "counsel_code" => $counsel_code,
                    "memo_cnt" =>$memo_cnt,
                    "counsel_desc" =>$counsel_desc,
                    "initial_desc" =>$initial_desc,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/Counsel/counsel_memo_os",  compact("reports","params", "clients", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/Counsel/counsel_memo_os",  compact("reports","params", "clients"));
            } else if($output_type == 'Excel') { 
                $branch_qry = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                $branch_name = (!empty($branch_qry)) ? $branch_qry['branch_name'] : '';
                
                // making where condition 
                $branch_code_con = ($branch_code != '') ? "a.branch_code = '$branch_code' AND" : '';
                $counsel_code_con = ($counsel_code != '') ? "a.counsel_code = '$counsel_code' AND" : '';
                $ason_date_ymd_con = ($ason_date_ymd != '') ? "a.entry_date <= '$ason_date_ymd' AND" : '';
                $initial_code_con = ($initial_code != '%') ? "AND b.initial_code = '$initial_code'" : "";

                $where_condition = "$branch_code_con $counsel_code_con $ason_date_ymd_con (COALESCE(a.counsel_fee_jv, 0) + COALESCE(a.clerk_fee_jv, 0)) = 0 $initial_code_con ORDER BY c.associate_name, a.memo_date DESC";
    
                $memo_sql = "SELECT a.serial_no, a.memo_no, a.memo_date, a.counsel_code, b.client_code, b.matter_code, b.initial_code, b.narration,
                COALESCE(b.counsel_fee, 0) + COALESCE(b.clerk_fee, 0) AS os_amount,
                COALESCE(b.counsel_fee, 0) + COALESCE(b.clerk_fee, 0) + COALESCE(b.service_tax_amount, 0) AS totos_amount,
                COALESCE(b.service_tax_amount, 0) AS stax_amount, c.associate_name AS counsel_name
                FROM counsel_memo_header a JOIN counsel_memo_detail b ON a.serial_no = b.ref_counsel_memo_serial_no
                JOIN associate_master c ON a.counsel_code = c.associate_code WHERE $where_condition";

                $reports  = $this->db->query($memo_sql)->getResultArray(); $memo_cnt  = count($reports);
                
                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                foreach ($reports as $key => $report) {
                    $client_qry   = $this->db->query("select client_name from client_master where client_code = '$report[client_code]'")->getRowArray();
                    $clients[$key]['client_name']  = isset($client_qry['client_name']) ? $client_qry['client_name'] : ''; 
                    
                    $matter_qry   = $this->db->query("select matter_desc1, matter_desc2 from fileinfo_header where matter_code = '$report[matter_code]'")->getRowArray() ;
                    $clients[$key]['matter_desc1'] = isset($matter_qry['matter_desc1']) ? $matter_qry['matter_desc1'] : '' ; 
                    $clients[$key]['matter_desc2'] = isset($matter_qry['matter_desc2']) ? $matter_qry['matter_desc2'] : ''; 
                }
                $date = date('d-m-Y');
                
                $fileName = 'Counsel-Memo-OS-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Define the headings
                $headings = ['Memo Sl', 'Memo No/Dt', 'Initial', 'Client/Matter/Narration', 'Amount', 'S/Tax', 'Total'];

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
        		$tosamt     = 0 ;
        		$tstamt     = 0 ;
        		$ttotosamt  = 0 ;
        		$rowcnt     = 1 ;
        		$report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
        		$report_cnt = $memo_cnt; 
        		
        		while ($rowcnt <= $report_cnt) {
            		$mosamt      = 0; 
            		$mstamt      = 0 ;
            		$mtotosamt   = 0 ;
            		$pcounselcd  = $report_row['counsel_code'] ;
            		$pcounselnm  = $report_row['counsel_name'] ;
            		$pcounselind = 'Y';
            		while ($pcounselcd == $report_row['counsel_code'] && $rowcnt <= $report_cnt) {
            			if($pcounselind == 'Y') {
            			    $sheet->setCellValue('A' . $rows, strtoupper($pcounselnm)); 
        			        $pcounselind = 'N';
        			        
                            $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
        			        // Appling cell merging
            			    $style->getActiveSheet()->mergeCells('A'.$rows.':G'.$rows);
        			        // Apply Background Color to the current row
                            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('5c5ccf');
            			    $rows++;
        			    }
        			    
            			$client_qry   = $clients[$rowcnt-1]; 
            			$client_name  = $client_qry['client_name'] ; 
            			$matter_qry   = $clients[$rowcnt-1];
            			$matter_desc1 = $matter_qry['matter_desc1'] ; 
            			$matter_desc2 = $matter_qry['matter_desc2'] ; 
            			$matter_desc = ($matter_desc1 != '') ? $matter_desc1 . ' : ' . $matter_desc2 : $matter_desc1 ; 
			
                        $sheet->setCellValue('A' . $rows, $report_row['serial_no']);
                        $sheet->setCellValue('B' . $rows, $report_row['memo_no']);
                        $sheet->setCellValue('C' . $rows, $report_row['initial_code']);
                        $sheet->setCellValue('D' . $rows, $client_name);
                        $sheet->setCellValue('E' . $rows, ($report_row['os_amount'] != 0) ? number_format($report_row['os_amount'], 2,'.',',') : '');
                        $sheet->setCellValue('F' . $rows, ($report_row['stax_amount'] != 0) ? number_format($report_row['stax_amount'], 2,'.',',') : '');
                        $sheet->setCellValue('G' . $rows, ($report_row['totos_amount'] != 0) ? number_format($report_row['totos_amount'], 2,'.',',') : '');
                        
                        // Apply border to the current row
                        $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                        $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                        $rows++;
                        $sheet->setCellValue('B' . $rows, $report_row['memo_date']);
                        $sheet->setCellValue('D' . $rows, 'Matter: ['.$report_row['matter_code'].'] '.'-'.'Re.: '. $matter_desc.']');
                        $rows++;
                        $sheet->setCellValue('D' . $rows, $report_row['narration']);
                        
        				$mosamt    = $mosamt    + $report_row['os_amount'] ;
        				$mstamt    = $mstamt    + $report_row['stax_amount'] ;                   
        				$mtotosamt = $mtotosamt + $report_row['totos_amount'] ;     
        
        				$report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
        				$rowcnt = $rowcnt + 1 ;
                        $rows++;
                    }  
                    $tosamt = $tosamt + $mosamt ;  
        			$tstamt = $tstamt + $mstamt ;                   
        			$ttotosamt = $ttotosamt + $mtotosamt ; 
                   
                    $sheet->setCellValue('D' . $rows, 'TOTAL');
                    $sheet->setCellValue('E' . $rows, number_format($mosamt,2,'.',''));
                    $sheet->setCellValue('F' . $rows, number_format($mstamt,2,'.',''));
                    $sheet->setCellValue('G' . $rows, number_format($mtotosamt,2,'.',''));
                    
                    // Apply Background Color to the current row
                    $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                    $rows++;
                }
                
                $sheet->setCellValue('D' . $rows, 'GRAND TOTAL');
                $sheet->setCellValue('E' . $rows, number_format($tosamt,2,'.',''));
                $sheet->setCellValue('F' . $rows, number_format($tstamt,2,'.',''));
                $sheet->setCellValue('G' . $rows, number_format($ttotosamt,2,'.',''));
                
                // Apply Background Color to the current row
                $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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
                
            } else {
                session()->setFlashdata('message', 'Please Select The Output Type !!');
                return redirect()->to($this->requested_url());
            }
        } else {
            $user_id = session()->userId;
            $data = branches($user_id);
            $displayId   = ['counsel_help_id' => '4011'] ;

            $initial_sql = "select * from initial_master order by initial_name ";
            $initial_qry = $this->db->query($initial_sql)->getResultArray();

            return view("pages/Counsel/counsel_memo_os",  compact("data", "displayId", "initial_qry"));
        }   
    }
    public function counsel_memo_direct_payment_os(){      
        if($this->request->getMethod() == 'post') {
            $clients = [];
            $display_id = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
            $param_id = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
            $my_menuid = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
            $menu_id = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
            $user_option = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
            $screen_ref = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
            $index = isset($_REQUEST['index'])?$_REQUEST['index']:null;
            $ord = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
            $pg = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
            $search_val = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
            $output_type = $_REQUEST['output_type']; 

            $param_id = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
            $my_menuid = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
            $query_id = isset($_REQUEST['query_id'])?$_REQUEST['query_id']:null; 
            $query_module_code = isset($_REQUEST['query_module_code'])?$_REQUEST['query_module_code']:null; 
            $query_name = isset($_REQUEST['query_name'])?$_REQUEST['query_name']:'O/s Counsel Memo' ;
            $query_program_name = isset($_REQUEST['query_program_name'])?$_REQUEST['query_program_name']:null;  

            $start_date = $_REQUEST['start_date'] ;   
            $start_date_ymd = date_conv($start_date,'-');
            $end_date = $_REQUEST['end_date'] ;    
            $end_date_ymd = date_conv($end_date,'-') ;  
            $branch_code = $_REQUEST['branch_code'] ;  
            $counsel_code = $_REQUEST['counsel_code'] ; if($counsel_code == '') { $counsel_code = '%' ; }
            $counsel_name = $_REQUEST['counsel_name'] ;
            $report_type = $_REQUEST['report_type'] ; 
            $initial_code = $_REQUEST['initial_code'] ; if($initial_code == '')  { $initial_code   = '%' ; }
            $initial_name = $_REQUEST['initial_name'] ;
            $client_code = $_REQUEST['client_code'] ; if($client_code == '') { $client_code  = '%' ; }
            $client_name = $_REQUEST['client_name'] ;
            $client_name = str_replace('_|_','&',$client_name) ;
            $client_name = str_replace('-|-',"'",$client_name) ;
            $report_seqn = $_REQUEST['report_seqn'] ;

            if ($counsel_code == '%') { $counsel_desc = 'ALL' ; } else { $counsel_desc = $counsel_name ; } 
            if ($initial_code == '%') { $initial_desc = 'ALL' ; } else { $initial_desc = $initial_name ; } 
        
            if ($start_date == '') { $period_desc = 'UPTO '.$end_date ; } else { $period_desc = $start_date.' - '.$end_date ; } 
            
            if($output_type == 'Report' || $output_type == 'Pdf') {
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                $branch_name   = (!empty($branch_qry)) ? $branch_qry['branch_name'] : '';

                $memo_sql = '';
                switch($report_type) {
                    case 'D':  
                        if($report_seqn == 'C')   { $order_by_clause = "c.client_name" ; }
                        if($report_seqn == 'R')   { $order_by_clause = "b.associate_name" ; }

                        $memo_sql = "select a.serial_no, d.memo_no, d.memo_date, a.counsel_code,d.client_code, d.matter_code, d.initial_code, d.narration,(ifnull(d.counsel_fee,0)+ifnull(d.clerk_fee,0)) amount,((ifnull(d.counsel_fee,0)+ifnull(d.clerk_fee,0))-(ifnull(d.counsel_fee_recd,0)+ifnull(d.clerk_fee_recd,0))) os_amount,b.associate_name counsel_name
                                from counsel_memo_direct_header a, associate_master b, client_master c,counsel_memo_direct_detail d
                                where d.memo_date between '$start_date_ymd'  and '$end_date_ymd'  
                                and a.branch_code = '$branch_code' 
                                and a.counsel_code like '$counsel_code'
                                and d.client_code like '$client_code'
                                and d.initial_code like '$initial_code'
                                and c.client_code = d.client_code 
                                and a.counsel_code = b.associate_code 
                                and b.associate_type = '001' 
                                and a.serial_no = d.ref_counsel_memo_serial_no 
                                and ifnull(d.counsel_fee_recd,0)=0 order by ".$order_by_clause;

                        $reports  = $this->db->query($memo_sql)->getResultArray();
                        $report_desc = "LIST OF O/S COUNSEL MEMO DETAIL (DIRECT PAYMENT)" ;

                        foreach ($reports as $key => $report){
                            $client_qry   = $this->db->query("select client_name from client_master where client_code = '$report[client_code]' ")->getRowArray();
                            $clients[$key]['client_name']  = $client_qry['client_name'] ; 
                            
                            $matter_qry   = $this->db->query("select ifnull(matter_desc1,'') matter_desc1, matter_desc2 from fileinfo_header where matter_code = '$report[matter_code]' ")->getRowArray() ;
                            $clients[$key]['matter_desc1'] = isset($matter_qry['matter_desc1']) ? $matter_qry['matter_desc1'] : '' ; 
                            $clients[$key]['matter_desc2'] = isset($matter_qry['matter_desc2']) ? $matter_qry['matter_desc2'] : '';     
                        }
                        break;
                    case'S':
                        if($report_seqn == 'C')   { $group_by_clause = "ifnull(c.client_code,''),ifnull(c.client_name,'OTHERS')"   ; $order_by_clause = "c.client_name" ; }
                        if($report_seqn == 'R')   { $group_by_clause = "a.counsel_code"   ; $order_by_clause = "b.associate_name" ; }

                        $memo_sql  ="select a.counsel_code, b.associate_name counsel_name,c.client_name,sum(ifnull(d.counsel_fee,0)-(ifnull(d.counsel_fee_recd,0))) os_counsel,sum(ifnull(d.clerk_fee,0)-(ifnull(d.clerk_fee_recd,0))) os_clerk,sum((ifnull(d.counsel_fee,0)+ifnull(d.clerk_fee,0))-(ifnull(d.counsel_fee_recd,0)+ifnull(d.clerk_fee_recd,0))) os_amount 
                                from counsel_memo_direct_header a, associate_master b, client_master c,counsel_memo_direct_detail d 
                                where d.memo_date between '$start_date_ymd'  and '$end_date_ymd'  
                                and a.branch_code = '$branch_code'  
                                and a.counsel_code like '$counsel_code'
                                and d.client_code like '$client_code'
                                and c.client_code = d.client_code 
                                and a.counsel_code = b.associate_code 
                                and b.associate_type = '001' 
                                and a.serial_no = d.ref_counsel_memo_serial_no
                                and (ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) = 0  
                                group by ".$group_by_clause." order by ".$order_by_clause ;
                        $reports  = $this->db->query($memo_sql)->getResultArray();
                        $report_desc = 'O/s Counsel Memo (Direct Payment) - Summary';
                        break;   
                }
                                                
                $memo_cnt  = count($reports);
                $date = date('d-m-Y');

                if(empty($memo_cnt)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "period_desc" => $period_desc,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    "initial_code" => $initial_code,
                    "initial_name" => $initial_name,
                    "counsel_code" => $counsel_code,
                    "counsel_name" => $counsel_name,
                    "report_seqn" => $report_seqn,
                    // "court_name" => $court_name,
                    "counsel_desc" => $counsel_desc,
                    "initial_desc" => $initial_desc,
                    "date" => $date,
                    //"matter_desc1" => $matter_desc1,
                    //"matter_desc2" => $matter_desc2,
                    "requested_url" => $this->requested_url(),
                    "memo_cnt" => $memo_cnt,
                    "report_type" => $report_type
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/Counsel/counsel_memo_direct_payment_os",  compact("reports","params", "clients", "report_type"));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/Counsel/counsel_memo_direct_payment_os",  compact("reports","params", "clients"));
                
            } else if($output_type == 'Excel') { 
                $branch_qry = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
                $branch_name = (!empty($branch_qry)) ? $branch_qry['branch_name'] : '';

                $memo_sql = '';
                switch($report_type) {
                    case 'D':  
                        if($report_seqn == 'C')   { $order_by_clause = "c.client_name" ; }
                        if($report_seqn == 'R')   { $order_by_clause = "b.associate_name" ; }

                        $memo_sql = "select a.serial_no, d.memo_no, d.memo_date, a.counsel_code,d.client_code, d.matter_code, d.initial_code, d.narration,(ifnull(d.counsel_fee,0)+ifnull(d.clerk_fee,0)) amount,((ifnull(d.counsel_fee,0)+ifnull(d.clerk_fee,0))-(ifnull(d.counsel_fee_recd,0)+ifnull(d.clerk_fee_recd,0))) os_amount,b.associate_name counsel_name
                                from counsel_memo_direct_header a, associate_master b, client_master c,counsel_memo_direct_detail d
                                where d.memo_date between '$start_date_ymd'  and '$end_date_ymd'  
                                and a.branch_code = '$branch_code' 
                                and a.counsel_code like '$counsel_code'
                                and d.client_code like '$client_code'
                                and d.initial_code like '$initial_code'
                                and c.client_code = d.client_code 
                                and a.counsel_code = b.associate_code 
                                and b.associate_type = '001' 
                                and a.serial_no = d.ref_counsel_memo_serial_no 
                                and ifnull(d.counsel_fee_recd,0)=0 order by ".$order_by_clause;

                        $reports  = $this->db->query($memo_sql)->getResultArray();
                        $report_desc = "LIST OF O/S COUNSEL MEMO DETAIL (DIRECT PAYMENT)" ;

                        foreach ($reports as $key => $report){
                            $client_qry   = $this->db->query("select client_name from client_master where client_code = '$report[client_code]' ")->getRowArray();
                            $clients[$key]['client_name']  = $client_qry['client_name'] ; 
                            
                            $matter_qry   = $this->db->query("select ifnull(matter_desc1,'') matter_desc1, matter_desc2 from fileinfo_header where matter_code = '$report[matter_code]' ")->getRowArray() ;
                            $clients[$key]['matter_desc1'] = isset($matter_qry['matter_desc1']) ? $matter_qry['matter_desc1'] : '' ; 
                            $clients[$key]['matter_desc2'] = isset($matter_qry['matter_desc2']) ? $matter_qry['matter_desc2'] : '';     
                        }
                        break;
                    case'S':
                        if($report_seqn == 'C')   { $group_by_clause = "ifnull(c.client_code,''),ifnull(c.client_name,'OTHERS')"   ; $order_by_clause = "c.client_name" ; }
                        if($report_seqn == 'R')   { $group_by_clause = "a.counsel_code"   ; $order_by_clause = "b.associate_name" ; }

                        $memo_sql  ="select a.counsel_code, b.associate_name counsel_name,c.client_name,sum(ifnull(d.counsel_fee,0)-(ifnull(d.counsel_fee_recd,0))) os_counsel,sum(ifnull(d.clerk_fee,0)-(ifnull(d.clerk_fee_recd,0))) os_clerk,sum((ifnull(d.counsel_fee,0)+ifnull(d.clerk_fee,0))-(ifnull(d.counsel_fee_recd,0)+ifnull(d.clerk_fee_recd,0))) os_amount 
                                from counsel_memo_direct_header a, associate_master b, client_master c,counsel_memo_direct_detail d 
                                where d.memo_date between '$start_date_ymd'  and '$end_date_ymd'  
                                and a.branch_code = '$branch_code'  
                                and a.counsel_code like '$counsel_code'
                                and d.client_code like '$client_code'
                                and c.client_code = d.client_code 
                                and a.counsel_code = b.associate_code 
                                and b.associate_type = '001' 
                                and a.serial_no = d.ref_counsel_memo_serial_no
                                and (ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) = 0  
                                group by ".$group_by_clause." order by ".$order_by_clause ;
                        $reports  = $this->db->query($memo_sql)->getResultArray();
                        $report_desc = 'O/s Counsel Memo (Direct Payment) - Summary';
                        break;   
                }
                                                
                $memo_cnt  = count($reports);
                $date = date('d-m-Y');

                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                
                $fileName = 'Counsel-Memo-Direct-Payment-OS-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Define the headings
                if ($report_type == 'D') {
                    $headings = ['Memo Sl', 'Memo No/Dt', 'Client/Matter', 'Initial', 'Amount', 'OS Amount'];
                } else if ($report_type == 'S') {
                    $headings = ['Counsel Name', 'O/S Counsel', 'O/S Clerk', 'O/S Total'];
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
                if ($report_type == 'D') { 
                    $tosamt     = 0; $gtamount   = 0;
                    $tstamt     = 0; $ttotosamt  = 0; $rowcnt     = 1;
                    $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '' ; 
                    $report_cnt = $memo_cnt;
                    
                    while ($rowcnt <= $report_cnt) {
                       $mosamt = 0; $mstamt = 0; $tamount = 0; $mtotosamt   = 0;
                       $pcounselcd  = $report_row['counsel_code'] ;
                       $pcounselnm  = $report_row['counsel_name'] ;
                       $pcounselind = 'Y';
                       
                       while ($pcounselcd == $report_row['counsel_code'] && $rowcnt <= $report_cnt) {
                			if($pcounselind == 'Y') {
                			    $sheet->setCellValue('A' . $rows, strtoupper($pcounselnm)); 
            			        $pcounselind = 'N';
            			        
                                $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
            			        // Appling cell merging
                			    $style->getActiveSheet()->mergeCells('A'.$rows.':F'.$rows);
            			        // Apply Background Color to the current row
                                $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                                $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('5c5ccf');
                			    $rows++;
            			    }
            			    
                			$client_qry   = $clients[$rowcnt-1]; 
                			$client_name  = $client_qry['client_name'] ; 
                			$matter_qry   = $clients[$rowcnt-1];
                			$matter_desc1 = $matter_qry['matter_desc1'] ; 
                			$matter_desc2 = $matter_qry['matter_desc2'] ; 
                			$matter_desc = ($matter_desc1 != '') ? $matter_desc1 . ' : ' . $matter_desc2 : $matter_desc1 ; 
    			
                            $sheet->setCellValue('A' . $rows, $report_row['serial_no']);
                            $sheet->setCellValue('B' . $rows, $report_row['memo_no']);
                            $sheet->setCellValue('C' . $rows, $client_name);
                            $sheet->setCellValue('D' . $rows, $report_row['initial_code']);
                            $sheet->setCellValue('E' . $rows, ($report_row['amount'] != 0) ? number_format($report_row['amount'], 2,'.',',') : '');
                            $sheet->setCellValue('F' . $rows, ($report_row['os_amount'] != 0) ? number_format($report_row['os_amount'], 2,'.',',') : '');
                            
                            // Apply border to the current row
                            $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                            $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                            $rows++;
                            $sheet->setCellValue('B' . $rows, date_conv($report_row['memo_date']));
                            $sheet->setCellValue('D' . $rows, 'Matter: ['.$report_row['matter_code'].'] '.'-'.'Re.: '. $matter_desc.']');
                            
                            $mosamt    = $mosamt + $report_row['os_amount'] ;
                            $tamount   = $tamount + $report_row['amount'] ;
            
                            $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                            $rowcnt = $rowcnt + 1 ;
                            $rows++;
                        }  
                        
                        $tosamt = $tosamt + $mosamt ;  
                        $tstamt = $tstamt + $mstamt ;
                        $gtamount = $gtamount + $tamount ;
                        $ttotosamt = $ttotosamt + $mtotosamt ; 
                       
                        $sheet->setCellValue('D' . $rows, 'TOTAL');
                        $sheet->setCellValue('E' . $rows, number_format($tamount,2,'.',''));
                        $sheet->setCellValue('F' . $rows, number_format($mosamt,2,'.',''));
                        
                        // Apply Background Color to the current row
                        $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffe93a');
                        $rows++;
                    }
                    
                    $sheet->setCellValue('D' . $rows, 'GRAND TOTAL');
                    $sheet->setCellValue('E' . $rows, number_format($gtamount,2,'.',''));
                    $sheet->setCellValue('F' . $rows, number_format($tosamt,2,'.',''));
                    
                    // Apply Background Color to the current row
                    $style = $sheet->getStyle('A' . $rows . ':F' . $rows);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('58ff86');
                    
                } else if($report_type == 'S') {
                    
                    $tos_counsel = 0; $tos_clerk = 0;
                    $rowcnt  = 1 ; $tosamt  = 0;
                    $report_row = isset($reports[$rowcnt-1]) ? $reports[$rowcnt-1] : '';
                    $report_cnt = $memo_cnt;
                    
                    while ($rowcnt <= $report_cnt) {
                       $rowdesc =  $report_row['counsel_name'] ;
                       $os_counsel =  $report_row['os_counsel'] ;
                       $os_clerk =  $report_row['os_clerk'] ;
                       $rosamt =  $report_row['os_amount'] ;
                       $client_name =  $report_row['client_name'] ;
                       $tos_counsel =  $tos_counsel + $report_row['os_counsel'] ;
                       $tos_clerk =  $tos_clerk + $report_row['os_clerk'] ;
                       $tosamt =  $tosamt + $rosamt; 
                       
                       if($report_seqn == 'C' && $os_counsel > 0)   {
                           $sheet->setCellValue('A' . $rows, $client_name);
                           $sheet->setCellValue('B' . $rows, ($os_counsel != 0.00) ? number_format($os_counsel,2,'.','') : '');
                           $sheet->setCellValue('C' . $rows, ($os_clerk != 0.00) ? number_format($os_clerk,2,'.','') : '');
                           $sheet->setCellValue('D' . $rows, ($rosamt != 0.00) ? number_format($rosamt,2,'.','') : '');
                           // Apply border to the current row
                           $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                           $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                           $rows++;
                       } else if($report_seqn == 'R' && $os_counsel > 0)   {
                           $sheet->setCellValue('A' . $rows, $rowdesc);
                           $sheet->setCellValue('B' . $rows, ($os_counsel != 0.00) ? number_format($os_counsel,2,'.','') : '');
                           $sheet->setCellValue('C' . $rows, ($os_clerk != 0.00) ? number_format($os_clerk,2,'.','') : '');
                           $sheet->setCellValue('D' . $rows, ($rosamt != 0.00) ? number_format($rosamt,2,'.','') : '');
                           // Apply border to the current row
                           $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                           $style->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN); 
                           $rows++;
                       }
                       
                       $report_row = ($rowcnt < $report_cnt) ? $reports[$rowcnt] : $report_row;
                       $rowcnt = $rowcnt + 1 ;
                    }
                    
                    $sheet->setCellValue('A' . $rows, 'TOTAL');
                    $sheet->setCellValue('B' . $rows, number_format($tos_counsel,2,'.',''));
                    $sheet->setCellValue('C' . $rows, number_format($tos_clerk,2,'.',''));
                    $sheet->setCellValue('D' . $rows, number_format($tosamt,2,'.',''));
                    
                    // Apply Background Color to the current row
                    $style = $sheet->getStyle('A' . $rows . ':D' . $rows);
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('58ff86');
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
                
            } else {
                session()->setFlashdata('message', 'Please Select The Output Type !!');
                return redirect()->to($this->requested_url());
            }
        } else {
            $user_id = session()->userId;
            $data = branches($user_id);
            $displayId = ['counsel_help_id' => '4011', 'client_help_id'=>'4072', 'matter_help_id'=>'4220', 'court_help_id'=>'4221', 'initial_help_id'=>'4191'];
    
            $cr_yr = date("Y");
            $fst_dt_yr = '01-01-'.$cr_yr;

            return view("pages/Counsel/counsel_memo_direct_payment_os",  compact("data", "displayId","fst_dt_yr"));
        }
    }
    public function direct_memo_followup(){
        if($this->request->getMethod() == 'post') {
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
            $output_type   = $_REQUEST['output_type']; 

            $ason_date       = $_REQUEST['ason_date'] ;
            $branch_code     = $_REQUEST['branch_code'] ;
            $client_code     = $_REQUEST['client_code'] ;  if($client_code == '') {$client_code = '%';}
            $client_name     = $_REQUEST['client_name'] ;  
            $initial_code    = $_REQUEST['initial_code'] ;     if($initial_code == '') {$initial_code = '%';}
            $initial_name    = $_REQUEST['initial_name'] ;  
            $counsel_code    = $_REQUEST['counsel_code'] ;     if($counsel_code == '') {$counsel_code = '%';}
            $start_date      = $_REQUEST['start_date'] ;       if($start_date   != '') {$start_date_ymd = date_conv($start_date);} else {$start_date_ymd = '1901-01-01';}
            $end_date        = $_REQUEST['end_date'] ;         if($end_date     != '') {$end_date_ymd   = date_conv($end_date)  ;} else {$end_date_ymd   = date('Y-m-d') ;}
            //$unadjadv_ind    = $_REQUEST['unadjadv_ind'] ;
            $attention_name  = $_REQUEST['attention_name'] ;
            $attention_code  = $_REQUEST['attention_code'] ;
            $attention_name  = $_REQUEST['attention_name'] ;
            $address_line_1  = $_REQUEST['address_line_1'] ;
            $address_line_2  = $_REQUEST['address_line_2'] ;
            $address_line_3  = $_REQUEST['address_line_3'] ;
            $address_line_4  = $_REQUEST['address_line_4'] ;
            $address_line_5  = $_REQUEST['address_line_5'] ;

            if($output_type == 'Report' || $output_type == 'Pdf') {
                $branch_qry       = $this->db->query("select * from branch_master where branch_code = '$branch_code' ")->getResultArray()[0] ;
                $branch_name      = $branch_qry['branch_name'] ;
                $branch_addr1     = strtoupper($branch_qry['address_line_1'].', '.$branch_qry['city'].' - '.$branch_qry['pin_code']) ;
                $branch_addr2     = 'TEL : '.$branch_qry['phone_no'].'     FAX : '.$branch_qry['fax_no'] ;
                $branch_addr3     = 'E-Mail : '.$branch_qry['email_id'] ;
                $branch_panno     = $branch_qry['pan_no'] ;
                $designation      = '';

                if($attention_code != '') {
                    $attn_qry         = $this->db->query("select * from client_attention where attention_code = '$attention_code' ")->getRowArray() ;
                    $attention_name   = $attn_qry['attention_name'] ;
                    $attention_sex    = $attn_qry['sex'] ;
                    $designation      = $attn_qry['designation'] ;
                
                
                    if($attention_sex == 'F') { $attn_name = $attention_name ;}
                    if($attention_sex == 'M') { $attn_name = 'Mr. '.$attention_name ; }
                }
                    
                if($attention_code == '') { $attn_name = $attention_name ;}

                $session = session();
                $letter_ref_no  = 'S/'.strtoupper($client_code).'/'.$session->financialYear.'/' ;
                $letter_ref_dt  = date('d-m-Y');

                $pendbill_stmt = "select a.memo_no,a.memo_date,a.matter_code, d.counsel_code, c.associate_name counsel_name,
                                if(b.matter_desc1 != '', concat(b.matter_desc1,' : ',b.matter_desc2), b.matter_desc2) matter_desc,
                                a.counsel_fee, a.clerk_fee, a.counsel_fee_recd, a.clerk_fee_recd,
                                (ifnull(a.counsel_fee,0) + ifnull(a.clerk_fee,0)) billamt,
                                (ifnull(a.counsel_fee_recd,0) + ifnull(a.clerk_fee_recd,0)) realamt
                                from counsel_memo_direct_detail a, associate_master c, counsel_memo_direct_header d, fileinfo_header b 
                                where a.client_code    like '$client_code'
                                and a.initial_code   like '$initial_code'
                                and d.counsel_code   like '$counsel_code'
                                and d.serial_no = a.ref_counsel_memo_serial_no 
                                and c.associate_code = d.counsel_code
                                and b.matter_code = a.matter_code						  
                                and a.memo_date between '$start_date_ymd' and '$end_date_ymd'
                                and (ifnull(a.counsel_fee,0) + ifnull(a.clerk_fee,0)) -
                                (ifnull(a.counsel_fee_recd,0) + ifnull(a.clerk_fee_recd,0)) > 0
                                order by a.memo_date"; 
            
                $reports  = $this->db->query($pendbill_stmt)->getResultArray();
                $pendbill_cnt  = count($reports);
                $bill_count    = $pendbill_cnt ;
                $date = date('d-m-Y');

                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                
                $params = [
                    "branch_name" => $branch_name,
                    "branch_addr1" => $branch_addr1,
                    "branch_addr2" => $branch_addr2,
                    "branch_addr3" => $branch_addr3,
                    "branch_panno" => $branch_panno,
                    // "report_desc" => $report_desc,
                    // "period_desc" => $period_desc,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    "counsel_code" => $counsel_code,
                    // "counsel_name" => $counsel_name,
                    "initial_code" => $initial_code,
                    "initial_name" => $initial_name,
                    // "report_seqn" => $report_seqn,
                    // "counsel_desc" => $counsel_desc,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                    // "memo_cnt" => $memo_cnt,
                    "designation" => $designation,
                    "attention_code" => $attention_code,
                    "attention_name" => $attention_name,
                    "letter_ref_no" => $letter_ref_no,
                    "letter_ref_dt" => $letter_ref_dt,
                    "address_line_1" => $address_line_1,
                    "address_line_2" => $address_line_2,
                    "address_line_3" => $address_line_3,
                    "address_line_4" => $address_line_4,
                    "address_line_5" => $address_line_5,
                    "attn_name" => $attn_name,
                    "pendbill_cnt" => $pendbill_cnt,
                    "bill_count" => $bill_count
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/Counsel/direct_memo_followup",  compact("reports","params", "report_type"));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/Counsel/direct_memo_followup",  compact("reports","params"));
            } else {
                session()->setFlashdata('message', 'Please Select The Output Type !!');
                return redirect()->to($this->requested_url());
            }
        } else {
            $user_id = session()->userId;
            $data = branches($user_id);
            $displayId   = ['counsel_help_id' => '4011', 'client_help_id'=>'4072', 'adratn_help_id'=>'4550', 'initial_help_id'=>'4191'];
    
            return view("pages/Counsel/direct_memo_followup",  compact("data", "displayId"));
        }
    }
}
?>