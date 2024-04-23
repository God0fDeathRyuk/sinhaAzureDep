<?php 

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CaseDetailsController extends BaseController
{
    public function __construct() {
        $this->db = \config\database::connect();
        $this->session = session();
    }

    /*********************************************************************************************/
    /***************************** Case Details [Transactions] ***********************************/
    /*********************************************************************************************/

    public function case_history($url = null) {   
        $response = [];
        switch($url) {
            case 'matter':
                $report_desc   = 'CASE HISTORY' ;
                $branch_code   = $_REQUEST['branch_code'] ;
                $ason_date     = $_REQUEST['ason_date'] ;    $ason_date_ymd  = date_conv($ason_date,'-') ;  
                $matter_code   = $_REQUEST['matter_code'] ;  if($matter_code == '') { $matter_code = '%' ; }
                $matter_desc   = $_REQUEST['matter_desc'] ;
                $matter_desc   = str_replace('_|_', '&', $matter_desc) ;
                $matter_desc   = str_replace('-|-', "'", $matter_desc) ;
                $client_code   = $_REQUEST['client_code'] ; 
                $desc_ind      = $_REQUEST['desc_ind'] ;
                
                $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code'")->getRowArray();
                $branch_name   = $branch_qry['branch_name'] ;
        
                $client_qry    = $this->db->query("select client_name from client_master where client_code = '$client_code'")->getRowArray();
                $client_name   = $client_qry['client_name'] ;
        
                $case_sql = "select a.*, b.reference_desc , b.court_code , b.date_of_filing , b.stake_amount , c.client_name , d.code_desc court_name , b.matter_desc1 , b.matter_desc2     , ifnull(a.other_case_desc,'') other_case_desc , e.ref_bill_serial_no , e.status_code                bill_status , if(f.serial_no is not null and f.serial_no != 0,concat(f.fin_year,'/',f.bill_no),'') bill_no
                            from fileinfo_header b, client_master c, code_master d, case_header a left outer join billinfo_header e on e.serial_no = a.ref_billinfo_serial_no left outer join bill_detail f on f.serial_no = e.ref_bill_serial_no   
                            where a.matter_code like '$matter_code' and a.activity_date <= '$ason_date_ymd' and a.client_code = c.client_code and a.matter_code = b.matter_code and b.court_code = d.code_code and d.type_code = '001' and a.status_code != 'X'    
                            order by a.matter_code,a.activity_date " ;
                $case_qry  = $this->db->query($case_sql)->getResultArray();
                $case_cnt  = count($case_qry);

                if (empty($case_qry)) $response['status'] = 'failed'; else {
                    $response['status'] = 'success';
                    $response ["page"] = view('pages/CaseDetails/matter_history', compact("case_qry", "case_cnt", "report_desc", "branch_name", "client_name", "ason_date", 'client_code', "matter_code", "matter_desc", "desc_ind")); 
                } break;    
        }
        return $this->response->setStatusCode(200)->setContentType('text/json')->setBody(json_encode($response));
    }

    public function case_status($option = null) {
        $user_id = session()->userId;
        $data = branches($user_id);
   		$perm = "select * from permission WHERE permission_on='0'";
        $permdata = $this->db->query($perm)->getResultArray();
      	$data['requested_url'] = $this->session->requested_end_menu_url;
      	$last_url = $this->requested_url();
    
        // $user_option = 'View';
        $user_option = $option; $permission = ($option != 'add' && $option != 'edit') ? 'readonly disabled' : '';
        $displayId   = ['casesrl_help_id' => '4532', 'matter_help_id' => '4206', 'activity_list_help_id' => '4086', 'counsel_help_id' => '4011'] ; 

        
        $case_detail_counsel_table = $this->db->table("case_detail_counsel");
        $case_detail_other_case_table = $this->db->table("case_detail_other_case"); 
        $finsub= isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
        $redv = ''; $disv = ''; $disb = ''; $disview = ''; $redLetter = '';
        if ($user_option == 'add' )     { $redk = '' ;          $redv = '';          $disv = ''         ; $disb = ''         ;  $redve = '';         $redokadd = '';         $disview = '';         $redLetter = 'disabled'; }
        if ($user_option == 'edit')     { $redk = '' ;          $redv = '';          $disv = ''         ; $disb = ''         ;  $redve = 'disabled'; $redokadd = 'readonly'; $disview = '';         $redLetter = 'disabled'; }
        if ($user_option == 'delete')   { $redk = '' ;          $redv = 'readonly';  $disv = 'disabled' ; $disb = ''         ;  $redve = '';         $redokadd = 'readonly'; $disview = 'disabled'; $redLetter = 'disabled'; }
        if ($user_option == 'view')     { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; $disb = 'disabled' ;  $redve = 'disabled'; $redokadd = 'readonly'; $disview = 'disabled'; $redLetter = 'disabled'; }
        if ($user_option == 'copy')     { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; $disb = 'disabled' ;  $redve = 'disabled'; $redokadd = 'readonly'; $disview = 'disabled'; $redLetter = 'disabled'; }
        if ($user_option == 'letter')   { $redk = 'readonly' ;  $redv = 'readonly';  $disv = 'disabled' ; $disb = 'disabled' ;  $redve = 'disabled'; $redokadd = 'readonly'; $disview = 'disabled'; $redLetter = ''; }
        
        if ($this->request->getMethod() == 'post' ) {
            if($finsub=="fsub")
            {
                // post data submit into database 
                $case_header_table = $this->db->table("case_header"); 
                $case_detail_int_dates_table = $this->db->table("case_detail_int_dates"); 
                if($user_option == 'add') {
                    //echo $billable_option = $_REQUEST['billable_option'];  [isset($_REQUEST['other_case_desc'])?stripslashes($_REQUEST['other_case_desc']):null ,]
                    //------------------------------------------- ADDING RECORD :  CASE HEADER  ----------------------------------------
                    $array = array(
                        'serial_no'              => '' ,
                        'branch_code'            => $_REQUEST['branch_code'],
                        'activity_date'          => date_conv($_REQUEST['activity_date']) ,
                        'matter_code'            => $_REQUEST['matter_code'] ,
                        'client_code'            => $_REQUEST['client_code'] ,
                        'other_case_desc'        => stripslashes($_REQUEST['other_case_desc']) ,
                        'judge_name'             => stripslashes($_REQUEST['judge_name']) ,
                        'appear_for'             => stripslashes($_REQUEST['appear_for']) ,
                        'prev_fixed_for'         => isset($_REQUEST['prev_fixed_for'])?stripslashes($_REQUEST['prev_fixed_for']):null ,
                        'prev_date'              => date_conv($_REQUEST['prev_date']),
                        'next_fixed_for'         => isset($_REQUEST['next_fixed_for'])?stripslashes($_REQUEST['next_fixed_for']):null ,
                        'next_date'              => date_conv($_REQUEST['next_date']) ,
                        'billable_option'        => stripslashes($_REQUEST['billable_option']) ,
                        'header_desc'            => stripslashes($_REQUEST['letter_body_desc']) ,
                        'footer_desc'            => stripslashes($_REQUEST['footer_desc']) ,
                        'cc_desc'                => stripslashes($_REQUEST['cc_desc']) ,
                        'signatory'              => stripslashes($_REQUEST['signatory']) ,
                        'ref_billinfo_serial_no' => NULL ,
                        'letter_no'              => NULL ,
                        'letter_date'            => NULL ,
                        'case_status_report_no'  => NULL ,
                        'status_code'            => 'A' ,
                        'forwarding_ind'         => isset($_REQUEST['forwarding_ind'])?$_REQUEST['forwarding_ind']:null ,
                        'stage_ind'              => isset($_REQUEST['stage_ind'])?$_REQUEST['stage_ind']:null ,
                        'instrument_ind'         => isset($_REQUEST['instrument_ind'])?$_REQUEST['instrument_ind']:null ,
                        'instrument_code'        => isset($_REQUEST['instrument_code'])?$_REQUEST['instrument_code']:null ,
                        'instrument_no'          => isset($_REQUEST['instrument_no'])?$_REQUEST['instrument_no']:null ,
                        'instrument_date'       => date_conv($_REQUEST['instrument_date']) ,
                        'remarks'                => isset($_REQUEST['remarks'])?stripslashes($_REQUEST['remarks']):null ,
                        'matter_status'          => isset($_REQUEST['matter_status'])?stripslashes($_REQUEST['matter_status']):null ,
                        'status_date'            => date_conv($_REQUEST['status_date']) ,
                        'alert_narration'        => isset($_REQUEST['alert_narration'])?stripslashes($_REQUEST['alert_narration']):null ,
                        'alert_date'             => date_conv($_REQUEST['alert_date']),
                        'prepared_by'            => $user_id ,
                        'prepared_on'            => date_conv(date('d-m-Y')) ,
                        'prepared_ip'            => '', //$_REQUEST['prepared_ip'] ,
                        'other_body_desc'        => stripslashes($_REQUEST['other_body_desc']) ,
                    );

                    $case_header = $case_header_table->insert($array);
                    $last_case_serial = $this->db->insertID();
                                
                    //------------------------------------------- ADDING RECORD :  OTHER CASES  ------------------------------------------
                    if($_REQUEST['other_ind'] == "Y") {
                    $count    = $_REQUEST['all_other_case_counter'];
                    $case_no  = explode("|$|",$_REQUEST['all_case']); 
                    $sub_desc = explode("|$|",$_REQUEST['all_sub']);
                    for($i=1; $i<=$count; $i++) { 
                            $array = array( 'ref_case_header_serial' => $last_case_serial,
                                            'case_no'                => $case_no[$i],
                                            'subject_desc'           => $sub_desc[$i], );
                            $case_det_other = $case_detail_other_case_table->insert($array);
                        }
                    }   
                    
                    //------------------------------------------- ADDING RECORD :  INTERMEDIATE DATES  -------------------------------------
                    if($_REQUEST['IntermediateFlag'] == "Y")
                    {
                        $rowno    = $_REQUEST['IntermediateRowNo'];
                        for($i=1; $i<=$rowno; $i++)
                        {   
                            if($_REQUEST['del_intermediate'.$i] != "Y")
                            {
                                if(!empty($_REQUEST['date_activity'.$i]) && !empty($_REQUEST['desc_activity'.$i]))
                                {
                                    $array = array( 'ref_case_header_serial' => $last_case_serial,
                                                    'activity_date'          => date_conv($_REQUEST['date_activity'.$i]),
                                                    'activity_desc'          => $_REQUEST['desc_activity'.$i],
                                                    );
                            
                                        $case_det_int_date = $case_detail_int_dates_table->insert($array);
                                }
                            }
                        }
                    }  
                    
                    //------------------------------------------- ADDING RECORD :  INPOCKET  -----------------------------------------------
                    $rowno = $_REQUEST['InpocketRowNo']; $row_count = $k = 1;
                    for($i = 1; $row_count <= $rowno; $i++) {
                        if(isset($_REQUEST['asso_code'.$i], $_REQUEST['asso_name'.$i], $_REQUEST['asso_acty'.$i], $_REQUEST['asso_desc'.$i])) {
                            if(!empty($_REQUEST['asso_code'.$i]) && !empty($_REQUEST['asso_name'.$i]) && !empty($_REQUEST['asso_acty'.$i]) && !empty($_REQUEST['asso_desc'.$i])) {   //echo 'Case No : '.$case_no[$i].' --- Subject : '.$sub_desc[$i].' --- i value : '.$i."<br>";
                                $array = array( 'ref_case_header_serial'  => $last_case_serial,
                                    'row_no' => $k,
                                    'billing_type' => '1',
                                    'activity_of' => 'O',
                                    'counsel_code' => $_REQUEST['asso_code'.$i],
                                    'activity_code' => $_REQUEST['asso_acty'.$i],
                                    'ref_counsel_memo_serial' => NULL,
                                    'payable_ind' => "Y",
                                );
                                $case_detail_counsel_table->insert($array);
                                $k++;
                            }
                            $row_count++;
                        }
                    }       
                    //----------------------------------------------- ADDING RECORD :  COUNSEL  -------------------------------------------
            
                    $rowno = $_REQUEST['CounselRowNo']; $row_count = $k = 1;
                    $payable_ind = '';

                    for($i=1; $row_count <= $rowno; $i++) {
                        if(isset($_REQUEST['counsel_code'.$i], $_REQUEST['counsel_name'.$i], $_REQUEST['coun_acty'.$i], $_REQUEST['coun_desc'.$i])) {
                            if(!empty($_REQUEST['counsel_code'.$i]) && !empty($_REQUEST['counsel_name'.$i]) && !empty($_REQUEST['coun_acty'.$i]) && !empty($_REQUEST['coun_desc'.$i])) {   
                                $payable_ind = isset($_REQUEST['pay'.$i]) ? "Y" : "N";
                                $array = array( 'ref_case_header_serial'  => $last_case_serial,
                                                'row_no' => $k,
                                                'billing_type' => '2',
                                                'activity_of' => 'C',
                                                'counsel_code' => $_REQUEST['counsel_code'.$i],
                                                'activity_code' => $_REQUEST['coun_acty'.$i],
                                                'ref_counsel_memo_serial' => NULL,
                                                'payable_ind' => $payable_ind,
                                            );
                                $case_det_counsel = $case_detail_counsel_table->insert($array);         
                                $k++;
                            }
                            $row_count++;
                        }
                    }          
                    
                    $matter_code = $_REQUEST['matter_code'];
                    $status_code = $_REQUEST['matter_status'];
                    $qry_txt     = $this->db->query("UPDATE fileinfo_header SET status_code = '$status_code' WHERE fileinfo_header.matter_code = '$matter_code'");
                
                    $msg      = "Please note the generated Bill Serial Number ..... : ".$last_case_serial ;
                    session()->setFlashdata('noted_message', $msg);
                    return redirect()->to($data['requested_url']);
                        
                } else if($user_option == 'edit') {                   
                    $array = array (
                        'activity_date'         => date_conv($_REQUEST['activity_date']) ,
                        'matter_code'           => $_REQUEST['matter_code'] ,
                        'client_code'           => $_REQUEST['client_code'] ,
                        'other_case_desc'       => stripslashes($_REQUEST['other_case_desc']) ,
                        'prev_fixed_for'        => isset($_REQUEST['prev_fixed_for'])?stripslashes($_REQUEST['prev_fixed_for']):null ,
                        'prev_date'             => date_conv($_REQUEST['prev_date']) ,
                        'next_fixed_for'        => isset($_REQUEST['next_fixed_for'])?stripslashes($_REQUEST['next_fixed_for']):null ,
                        'next_date'             => date_conv($_REQUEST['next_date']) ,
                        'billable_option'       => $_REQUEST['billable_option'] ,
                        'judge_name'            => stripslashes($_REQUEST['judge_name']) ,
                        'header_desc'           => stripslashes($_REQUEST['letter_body_desc']) ,
                        'footer_desc'           => stripslashes($_REQUEST['footer_desc']) ,
                        'cc_desc'               => stripslashes($_REQUEST['cc_desc']) ,
                        'signatory'             => stripslashes($_REQUEST['signatory']) ,
                        'status_code'           => 'A' ,
                        'forwarding_ind'        => isset($_REQUEST['forwarding_ind'])?$_REQUEST['forwarding_ind']:null ,
                        'stage_ind'             => isset($_REQUEST['stage_ind'])?$_REQUEST['stage_ind']:null ,
                        'instrument_ind'        => isset($_REQUEST['instrument_ind'])?$_REQUEST['instrument_ind']:null ,
                        'instrument_code'       => isset($_REQUEST['instrument_code'])?$_REQUEST['instrument_code']:null ,
                        'instrument_no'         => isset($_REQUEST['instrument_no'])?$_REQUEST['instrument_no']:null ,
                        'instrument_date'       => date_conv($_REQUEST['instrument_date']) ,
                        'remarks'               => isset($_REQUEST['remarks'])?stripslashes($_REQUEST['remarks']):null ,
                        'matter_status'         => isset($_REQUEST['matter_status'])?stripslashes($_REQUEST['matter_status']):null ,
                        'status_date'           => date_conv($_REQUEST['status_date']) ,
                        'alert_narration'       => isset($_REQUEST['alert_narration'])?stripslashes($_REQUEST['alert_narration']):null ,
                        'alert_date'            => date_conv($_REQUEST['alert_date']),
                        'updated_by'            => $user_id,
                        'updated_on'            => date_conv(date('d-m-Y')),
                        // 'last_update_ip'        => $_REQUEST['last_update_ip'],
                        'other_body_desc'       => stripslashes($_REQUEST['other_body_desc']) ,
                    );
                    $where = "serial_no = '".$_REQUEST['serial_no']."'";
                    $case_header = $case_header_table->update($array, $where);

                    //------------------------------------------- MODIFY RECORD :  OTHER CASES  ------------------------------------------
                    if($_REQUEST['other_ind'] == "Y") {
                        $count    = intval($_REQUEST['all_other_case_counter']);
                        $case_no  = explode("|$|",$_REQUEST['all_case']);
                        $sub_desc = explode("|$|",$_REQUEST['all_sub']);
            
                        $where = "case_detail_other_case.ref_case_header_serial = '".$_REQUEST['serial_no']."'";
                        $case_det_other_del = $case_detail_other_case_table->delete($where);
            
                        for($i=1; $i<=$count; $i++) {   
                            // echo '<br>Case No : '.$case_no[$i].' --- Subject : '.$sub_desc[$i].' --- i value : '.$i."<br>";
                            $array = [ 
                                'ref_case_header_serial' => $_REQUEST['serial_no'],
                                'case_no' => $case_no[$i],
                                'subject_desc' => $sub_desc[$i]
                            ];
                            $case_det_other = $case_detail_other_case_table->insert($array);
                        }
                    }          
                    
                    //------------------------------------------- MODIFY RECORD :  INTERMEDIATE DATES  -------------------------------------
                    if($_REQUEST['IntermediateFlag'] == "Y") {
                        
                        $case_det_int_date_del = $case_detail_int_dates_table->delete(["case_detail_int_dates.ref_case_header_serial" => $_REQUEST['serial_no']]);
                        $rowno = $_REQUEST['IntermediateRowNo'];
                        
                        for($i=1; $i<=$rowno; $i++) { 

                            if($_REQUEST['del_intermediate'.$i] != "Y") {
                                
                                if(!empty($_REQUEST['date_activity'.$i]) && !empty($_REQUEST['desc_activity'.$i])) {
                                    $array = [
                                        'ref_case_header_serial' => $_REQUEST['serial_no'],
                                        'activity_date' => date_conv($_REQUEST['date_activity'.$i]),
                                        'activity_desc' => $_REQUEST['desc_activity'.$i],
                                            ];
                                    $case_det_int_date = $case_detail_int_dates_table->insert($array);
                                }
                            }
                        }
                    }          
                    
                    //------------------------------------------- MODIFY RECORD :  INPOCKET  -----------------------------------------------
                    $where = "case_detail_counsel.ref_case_header_serial = '".$_POST['serial_no']."' and case_detail_counsel.activity_of = 'O'";
                    $case_det_counsel_del = $case_detail_counsel_table->delete($where);

                    $rowno = $_REQUEST['InpocketRowNo']; $row_count = $k = 1;
                    for($i = 1; $row_count <= $rowno; $i++) {
                        if(isset($_REQUEST['asso_code'.$i], $_REQUEST['asso_name'.$i], $_REQUEST['asso_acty'.$i], $_REQUEST['asso_desc'.$i])) {
                            if(!empty($_REQUEST['asso_code'.$i]) && !empty($_REQUEST['asso_name'.$i]) && !empty($_REQUEST['asso_acty'.$i]) && !empty($_REQUEST['asso_desc'.$i])) {   //echo 'Case No : '.$case_no[$i].' --- Subject : '.$sub_desc[$i].' --- i value : '.$i."<br>";
                                $array = array( 'ref_case_header_serial'  => $_REQUEST['serial_no'],
                                    'row_no' => $k,
                                    'billing_type' => '1',
                                    'activity_of' => 'O',
                                    'counsel_code' => $_REQUEST['asso_code'.$i],
                                    'activity_code' => $_REQUEST['asso_acty'.$i],
                                    'ref_counsel_memo_serial' => NULL,
                                    'payable_ind' => "Y",
                                );
                                $case_detail_counsel_table->insert($array);
                                $k++;
                            }
                            $row_count++;
                        }
                    }       
                    
                    //----------------------------------------------- MODIFY RECORD :  COUNSEL  -------------------------------------------
                    $where = "case_detail_counsel.ref_case_header_serial = '".$_POST['serial_no']."' and case_detail_counsel.activity_of = 'C'";
                    $case_det_counsel_del = $case_detail_counsel_table->delete($where);
                    // $case_det_counsel_del = $case_detail_counsel_table->delete(["case_detail_counsel.ref_case_header_serial" => $_REQUEST['serial_no'] ]);
            
                    $rowno = $_REQUEST['CounselRowNo']; $row_count = $k = 1;
                    $payable_ind = '';

                    for($i=1; $row_count <= $rowno; $i++) {
                        if(isset($_REQUEST['counsel_code'.$i], $_REQUEST['counsel_name'.$i], $_REQUEST['coun_acty'.$i], $_REQUEST['coun_desc'.$i])) {
                            if(!empty($_REQUEST['counsel_code'.$i]) && !empty($_REQUEST['counsel_name'.$i]) && !empty($_REQUEST['coun_acty'.$i]) && !empty($_REQUEST['coun_desc'.$i])) {   
                                $payable_ind = isset($_REQUEST['pay'.$i]) ? "Y" : "N";
                                $array = array( 'ref_case_header_serial'  => $_REQUEST['serial_no'],
                                                'row_no' => $k,
                                                'billing_type' => '2',
                                                'activity_of' => 'C',
                                                'counsel_code' => $_REQUEST['counsel_code'.$i],
                                                'activity_code' => $_REQUEST['coun_acty'.$i],
                                                'ref_counsel_memo_serial' => NULL,
                                                'payable_ind' => $payable_ind,
                                            );
                                $case_det_counsel = $case_detail_counsel_table->insert($array);         
                                $k++;
                            }
                            $row_count++;
                        }
                    }          
            
                    //--------------------------------------------------------------------------------------------------------------
                    $matter_code = $_REQUEST['matter_code'];
                    $status_code = $_REQUEST['matter_status'];
                    $qry_txt = $this->db->query("UPDATE fileinfo_header SET status_code = '$status_code' WHERE fileinfo_header.matter_code = '$matter_code'");
            
                } else if($user_option == 'copy' && $user_id == 'rghosh') {
                    
                    $serial_no    = $_REQUEST['serial_no'];
                    $prepared_by  = $user_id ;
                    $prepared_on  = date_conv(date('d-m-Y')) ;
                
                    $row = $this->db->query("SELECT * FROM case_header where serial_no = '$serial_no'")->getResultArray()[0];
                    $row['serial_no']   = NULL;
                    $row['letter_no']   = NULL;
                    $row['letter_date'] = NULL;
                    $row['case_status_report_no'] = NULL;
                    $row['ref_billinfo_serial_no'] = NULL;
                    $row['billable_option'] = 'P' ;
                    $row['prepared_by'] = $prepared_by;
                    $row['prepared_on'] = $prepared_on;
                    $row['status_code'] = 'A' ;
                                    
                    $case_header = $case_header_table->insert($row);
                    $last_case_serial = $this->db->insertID();
        
                    //------------------------------------------- ADDING RECORD :  INTERMEDIATE DATES  -------------------------------------
                    $res = $this->db->query("SELECT * FROM case_detail_int_dates where ref_case_header_serial = '$serial_no'")->getResultArray();
                    $cnt = count($res);
                    
                    if($cnt > 0 ) {
                        foreach($res as $row) {
                            //$row = $res;
                            $row['ref_case_header_serial'] = $last_case_serial;
                            $case_det_int_date = $case_detail_int_dates_table->insert($row);
                        }
                    }
                    //------------------------------------------- ADDING RECORD :  INPOCKET/Counsel  -----------------------------------------------
                    $res = $this->db->query("SELECT * FROM case_detail_counsel where ref_case_header_serial = '$serial_no'")->getResultArray();
                    $cnt = count($res);
                    
                    if($cnt > 0 ) {
                        foreach($res as $row) {
                            //$row = $res;
                            $row['ref_case_header_serial'] = $last_case_serial;
                            $case_det_counsel = $case_detail_counsel_table->insert($row);
                        }
                    }

                    $msg = "Please note the generated Bill Serial Number ..... : ".$last_case_serial ;
                    session()->setFlashdata('noted_message', $msg);
                    return redirect()->to($data['requested_url']);

                }
                    
                else if($user_option == 'copy' && $user_id != 'rghosh' ) {
                    // echo '<pre>';print_r($_REQUEST);die;
                    $serial_no    = $_REQUEST['serial_no'];
                    $prepared_by  = $user_id ;
                    $prepared_on  = date_conv(date('d-m-Y')) ;
                
                    $res = $this->db->query("SELECT * FROM case_header where serial_no = '$serial_no'")->getResultArray()[0];
                    $row = $res;
                    $row['serial_no']   = NULL;
                    $row['letter_no']   = NULL;
                    $row['letter_date'] = NULL;
                    $row['case_status_report_no'] = NULL;
                    $row['ref_billinfo_serial_no'] = NULL;
                    $row['prepared_by'] = $prepared_by;
                    $row['prepared_on'] = $prepared_on;
                    $row['status_code'] = 'A' ;
                                    
                    $case_header = $case_header_table->insert($row);

                    //----------------------------------------------------------- LAST INSERT ID -----------------------------------------
                    $last_case_serial = $this->db->insertID();

                    $res = $this->db->query("SELECT * FROM case_detail_int_dates where ref_case_header_serial = '$serial_no'")->getResultArray();
                    $cnt = count($res);
                    if($cnt > 0 )
                    {
                        foreach($res as $row) {
                            //$row = $res;
                            $row['ref_case_header_serial'] = $last_case_serial;
            
                            $case_det_int_date = $case_detail_int_dates_table->insert($row);
                        }
                    }
                    //------------------------------------------- ADDING RECORD :  INPOCKET/Counsel  -----------------------------------------------
            
                    $res = $this->db->query("SELECT * FROM case_detail_counsel where ref_case_header_serial = '$serial_no'")->getResultArray();
                    $cnt = count($res);
                    if($cnt > 0 )
                    {
                        foreach($res as $row) {
                            //$row = $res->fetchRow();
                            $row['ref_case_header_serial'] = $last_case_serial;
            
                                $case_det_counsel = $case_detail_counsel_table->insert($row);
                        }
                    }

                    $msg = "Please note the generated Bill Serial Number ..... : ".$last_case_serial ;
                    session()->setFlashdata('noted_message', $msg);
                    return redirect()->to($data['requested_url']);
                }
                    
                else if($user_option == 'delete') {
                    $serial_no  = $_REQUEST['serial_no'];
                    
                    //----------------------------------------- DELETE : CASE_HEADER --------------------------------------------
            
                    $where = "serial_no = '$serial_no'";
                    $case_hdr_del = $case_header_table->delete($where);
            
                    //----------------------------------------- DELETE : CASE_DETAIL_OTHER_CASE ---------------------------------
            
                    $where = "case_detail_other_case.ref_case_header_serial = '$serial_no'";
                    $case_det_other_del = $case_detail_other_case_table->delete($where);
            
                    //----------------------------------------- DELETE : CASE_DETAIL_INT_DATES ----------------------------------
            
                    $where = "case_detail_int_dates.ref_case_header_serial = '$serial_no'";
                    $case_det_int_date_del = $case_detail_int_dates_table->delete($where);
            
                    //----------------------------------------- DELETE : CASE_DETAIL_COUNSEL ------------------------------------
                    
                    $where = "case_detail_counsel.ref_case_header_serial = '$serial_no'";
                    $case_det_counsel_del = $case_detail_counsel_table->delete($where);

                    session()->setFlashdata('message', 'Records Deleted Successfully !!');
                    return redirect()->to($data['requested_url']);
                    
                } else if($user_option == 'open') {
                    $updt_stmt = "update case_header set status_code = 'E' where serial_no = '$serial_no'"; 
                    $update_case = $this->db->query($updt_stmt);

                } else if($user_option == 'letter') {
                    $data['requested_url'] = $this->requested_url();
                    $serial_no      = isset($_REQUEST['serial_no'])      ?$_REQUEST['serial_no']      :null;
                    $logo_print_ind = isset($_REQUEST['logo_print_ind']) ?$_REQUEST['logo_print_ind'] :null;
                    $letter_date    = isset($_REQUEST['letter_date'])    ?$_REQUEST['letter_date']    :null;
                    $option = $user_option;
                    //
                    $xQry = $this->db->query("select a.matter_code,b.matter_desc1,b.matter_desc2 from case_header a INNER JOIN fileinfo_header b ON a.matter_code = b.matter_code where a.serial_no = '$serial_no'")->getRowArray() ;
                   
                    $matter_code = $xQry['matter_code'];
                    $matter_desc = $xQry['matter_desc2'];
                    
                    $yQry = $this->db->query("select row_no,name from fileinfo_details where matter_code = '$matter_code' and record_code = '1' order by row_no ")->getResultArray() ; 
                    $yCnt = count($yQry) ; 

                    $params = [
                        'serial_no' => $serial_no,
                        'logo_print_ind' => $logo_print_ind,
                        'letter_date' => $letter_date,
                    ];
                    return view("pages/CaseDetails/case_status",  compact("xQry", "yQry","yCnt", "params", 'option', 'displayId', 'data'));
                }
                
                session()->setFlashdata('message', 'Records Updated Successfully !!');
                return redirect()->to($data['requested_url']);
            }
            if($finsub=="" || $finsub!="fsub")
            {
                if($user_option == 'add' && $user_option != 'print') {
                    $count = ['row_num' => 0, 'row_inpocket' => 1, 'row_counsel' => 1];
                    // $row_inpocket = 1 ; $row_counsel  = 1 ; 
                    //$displayId   = ['matter_help_id' => '4206'] ; 
                    if (isset($_REQUEST['serial_no'])) {
                        if(!empty($_REQUEST['serial_no'])) {
                            $serial_no = $this->request->getVar("serial_no");
                            $caseHdrArray = $this->db->query("SELECT * FROM `case_header` WHERE case_header.serial_no = '$serial_no'")->getRowArray();
                            $caseHdrArray['prepared_on']  = date_conv($caseHdrArray['prepared_on']);
                            $caseHdrArray['activity_date'] = date_conv($caseHdrArray['activity_date']);
                        }
                    }
    
                    $instrument_status = $this->db->query("select code_code,code_desc from code_master where type_code = '040' order by code_desc")->getResultArray();
                    $caseHdrArray['instrument_status'] = $instrument_status;
                    $matter_status =  $this->db->query("SELECT status_code, status_desc FROM status_master WHERE table_name = 'fileinfo_header' and status_code != 'NEW' Order by status_code")->getResultArray();
                    $caseHdrArray['matter_status'] = $matter_status;
                    // echo "<pre>"; print_r($caseHdrArray); die;
                    return view("pages/CaseDetails/case_status",  compact( "caseHdrArray", "option", "permission", "data", "displayId", "count", 'redv', 'disv', 'disb', 'disview', 'redLetter'));
    
                }
                else if ($user_option == 'print'){
                
                    $tot_char        = 90 ;
                    $other_qry  = [];
                    //---
                    $serial_no       = isset($_REQUEST['serial_no'])      ?$_REQUEST['serial_no']      :null; 
                    $logo_print_ind  = isset($_REQUEST['logo_print_ind']) ?$_REQUEST['logo_print_ind'] :null;
                    $letter_date     = isset($_REQUEST['letter_date'])    ?$_REQUEST['letter_date']    :null;
                    $other_cnt       = isset($_REQUEST['other_cnt'])      ?$_REQUEST['other_cnt']      :null;
                    $other_str       = isset($_REQUEST['other_str'])      ?$_REQUEST['other_str']      :null; 
                    // echo '<pre>'; print_r($other_str);die;
                    
                    $case_serial_no  = $serial_no; 
                    $letter_date_ymd = date_conv($letter_date);
                    $option = $user_option ; //isset($_REQUEST['user_option']) ? $_REQUEST['user_option'] :null; 
                    //
                    $xQry        = $this->db->query("select letter_no from case_header where serial_no = '$serial_no' ")->getRowArray();
                    $letter_no   = $xQry['letter_no'];
                    //
                    if($letter_no == '') {
                        $cal_year    = date("Y"); 
                        $letter_type = 'IL';
                        //
                        $letter_qry  = $this->db->query("select ifnull(last_serial_no,0)+1 as newsrl from system_letter_serial where calendar_year = '$cal_year' and letter_type = '$letter_type' ")->getResultArray();
                        //$letter_qry  = mysql_query($letter_sql,$link) ;
                        $letter_cnt  = count($letter_qry) ;
                        //$letter_row  = mysql_fetch_array($letter_qry) ;
                        //   
                        if($letter_cnt == 0) {
                            $new_serial = 1 ;
                            $letter_no  = 'S/'.$cal_year.'/'.str_pad($new_serial,4,'0',STR_PAD_LEFT);
                            $this->db->query("insert into system_letter_serial (calendar_year,letter_type,last_serial_no) values ('$cal_year','$letter_type', $new_serial)");
                        } else {
                            foreach($letter_qry as $letter_row){
                                $new_serial = $letter_row['newsrl'];
                                $letter_no  = 'S/'.$cal_year.'/'.str_pad($new_serial,4,'0',STR_PAD_LEFT);
                                $this->db->query("update system_letter_serial set last_serial_no = '$new_serial' where calendar_year = '$cal_year' AND letter_type = '$letter_type'");
                            }
                        }
                        //
                        $this->db->query("update case_header set letter_no = '$letter_no' where serial_no = '$serial_no' ");
                    }
                    $this->db->query("update case_header set letter_date = '$letter_date_ymd' where serial_no = '$serial_no' ");
                    
                    //---
                    // $page_no = 1;
                    // $line_no = 0; 
                    // $page_flag = 'F';
                    // $company_address = strtoupper(trim(trim(trim($global_company_address1.' '.$global_company_address2).' '.$global_company_address3).' '.$global_company_address4));
                    // $x25thLogoYear   = getParameterValue('20',$link) ;
                    // $x25thLogoInd    = (substr($letter_date,6,4) == $x25thLogoYear) ? 'Y' : 'N' ; 
                    //
                    $sql = "SELECT  a.letter_no, a.serial_no case_no, a.header_desc, a.footer_desc, a.cc_desc, a.signatory, a.letter_date, b.matter_desc1, b.matter_desc2, b.reference_desc, b.subject_desc, c.client_code, c.client_name
                        , d.address_line_1 client_addr1, d.address_line_2 client_addr2, d.address_line_3 client_addr3, d.address_line_4 client_addr4, d.city client_city, d.pin_code client_pin, e.attention_code, e.attention_name
                        , ifnull(e.designation,'') designation, e.sex, e.title, a.branch_code , a.other_case_desc, a.letter_date, a.matter_code, a.other_body_desc
                        FROM   case_header a, fileinfo_header b, client_master c, client_address d, client_attention e
                        WHERE a.serial_no        = '$case_serial_no'
                        AND a.matter_code        = b.matter_code
                        AND a.client_code        = c.client_code
                        AND b.corrp_addr_code    = d.address_code
                        AND b.corrp_attn_code    = e.attention_code";
                    $qry = $this->db->query($sql)->getResultArray();
                    $cnt = count($qry) ;
                    //$row = mysql_fetch_array($qry) ;
                    foreach($qry as $row){
                        $letter_no       = stripslashes($row['letter_no']);
                        $letter_date     = stripslashes($row['letter_date']);
                        $case_no         = stripslashes($row['case_no']);
                        $header_desc     = stripslashes($row['header_desc']); //
                        $footer_desc     = stripslashes($row['footer_desc']);
                        $cc_desc         = stripslashes($row['cc_desc']);
                        $matter_code     = stripslashes($row['matter_code']);
                        $matter_desc1    = stripslashes($row['matter_desc1']);
                        $matter_desc2    = stripslashes($row['matter_desc2']);
                        $reference_desc  = stripslashes($row['reference_desc']);
                        $subject_desc    = stripslashes($row['subject_desc']);
                        $client_code     = stripslashes($row['client_code']);
                        $client_name     = stripslashes($row['client_name']);
                        $client_addr1    = stripslashes($row['client_addr1']); //
                        $client_addr2    = stripslashes($row['client_addr2']); //
                        $client_addr3    = stripslashes($row['client_addr3']); //
                        $client_addr4    = stripslashes($row['client_addr4']); //
                        $client_city     = stripslashes($row['client_city']); //
                        $client_pin      = stripslashes($row['client_pin']); //
                        $attention_name  = stripslashes($row['attention_name']); //
                        $designation     = stripslashes($row['designation']);
                        $sex             = stripslashes($row['sex']); //
                        $title           = stripslashes($row['title']); //
                        $branch_code     = stripslashes($row['branch_code']);
                        $other_cases     = stripslashes($row['other_case_desc']);
                        $signatory       = stripslashes($row['signatory']);
                        $matter_desc     = trim($matter_desc1.' '.$matter_desc2);
                        $other_body_desc = stripslashes($row['other_body_desc']);
                    
                        //
                        $attn_desc       = ($sex == 'F') ? ('Madam,') : (($sex == 'M') ? ('Dear Sir(s),') : '') ;  
                        $attn_name       = (($title != 'ORS') ? ($title.' ') : '').stripslashes($attention_name) ;  
                        $title           = $attn_desc ; 
                        //
                        $header_desc     = $header_desc.chr(13);
                        $hdr_desc        = wordwrap($header_desc, $tot_char, "\n");
                        $hdr_array       = explode("\n",$hdr_desc);
                        $row_cnt         = count($hdr_array);
                        //
                        $client_addr5 = '';
                        if($client_city != '') 
                        { 
                            $client_addr5 = ($client_pin != '') ? ($client_city.',  PIN - '.$client_pin) : $client_city  ; 
                        } 	
                        else
                        { 
                            $client_addr5 = ($client_pin != '') ? ('PIN - '.$client_pin) : ''  ; 
                        }
                        $client_address  = $client_addr1 ;
                        $client_address .= ($client_addr2 != '') ? ('<br>'.$client_addr2) : '' ;
                        $client_address .= ($client_addr3 != '') ? ('<br>'.$client_addr3) : '' ;
                        $client_address .= ($client_addr4 != '') ? ('<br>'.$client_addr4) : '' ;
                        $client_address .= ($client_addr5 != '') ? ('<br>'.$client_addr5) : '' ;
                        //$client_address  = trim($client_addr1.'<br>'.$client_addr2.'<br>'.$client_addr3.'<br>'.$client_addr4.'<br>'.$client_addr5);
                        
                        //---
                        $ind = 0;
                        $mr_ind = '';
                        //
                        $branch_sql   = $this->db->query("select * from branch_master where branch_code = '$branch_code' ")->getRowArray();
                        $branch_addr1 = $branch_sql['address_line_1'].', '.$branch_sql['city'].' - '.$branch_sql['pin_code'] ;
                        $branch_addr2 = 'TEL : '.$branch_sql['phone_no'].'       FAX : '.$branch_sql['fax_no'] ;
                        $branch_addr3 = 'E-Mail : '.$branch_sql['email_id'] ;
                        $my_company_name = 'Sinha and Company' ;
    
                        //---
                        //echo '<pre>';print_r($other_str);die;
                        if($other_str != '')
                        {
                            $other_sql   = "select * from fileinfo_details where matter_code = '$matter_code' and record_code = '1' and row_no in (".$other_str.")" ;
                            $other_qry   = $this->db->query($other_sql)->getResultArray();
                            $other_cnt   = count($other_qry);
                            //
                            $header_desc = $other_body_desc.chr(13);
                            $hdr_desc    = wordwrap($header_desc, $tot_char, "\n");
                            $hdr_array   = explode("\n",$hdr_desc);
                            // echo '<pre>'; print_r($other_qry);die;
                            $row_cnt     = count($hdr_array);
                        } 	
                    }
                    $params = [
                        'client_code' => $client_code,
                        'letter_no'   => $letter_no,
                        'letter_date' => $letter_date,
                        'other_cases'   => $other_cases,
                        'designation'     => $designation,
                        'client_name' => $client_name,
                        'client_address' => $client_address,
                        'attn_name'   => $attn_name,
                        'matter_desc' => $matter_desc,
                        'title'       => $title,
                        "branch_addr1" => $branch_addr1,
                        "branch_addr2" => $branch_addr2,
                        "branch_addr3" => $branch_addr3,
                        "reference_desc" => $reference_desc,
                        "subject_desc" => $subject_desc,
                        "signatory" => $signatory,
                        "footer_desc" => $footer_desc,
                        "cc_desc" => $cc_desc,
                        "logo_print_ind" => $logo_print_ind,
                        "requested_url" => $this->session->requested_end_menu_url,
                    ];
                    return view("pages/CaseDetails/case_status",  compact("row_cnt", "other_cnt", "other_qry", "qry", "hdr_array", "params", "option", "displayId", "data"));
    
                } else {
                    $serial_no = $this->request->getVar("serial_no");
                    $caseHdrArray = $this->db->query("SELECT * FROM `case_header` WHERE case_header.serial_no = '$serial_no'")->getResultArray()[0];
        
                    $serial_no = $caseHdrArray['serial_no'];
                    $caseHdrArray['activity_date'] = date_conv($caseHdrArray['activity_date']);
                    $client_code = $caseHdrArray['client_code'];
                    $matter_code = $caseHdrArray['matter_code'];
                    $ref_billinfo_serial_no = $caseHdrArray['ref_billinfo_serial_no'];
                    $other_case_desc = $caseHdrArray['other_case_desc'];
                    $caseHdrArray['prev_date']  = date_conv($caseHdrArray['prev_date']);
                    $caseHdrArray['next_date']  = date_conv($caseHdrArray['next_date']);
                    $status_code = $caseHdrArray['status_code'];
                    $caseHdrArray['instrument_date']  = date_conv($caseHdrArray['instrument_date']);
                    $caseHdrArray['letter_date']  = date_conv($caseHdrArray['letter_date']);
                    $caseHdrArray['prepared_on']  = date_conv($caseHdrArray['prepared_on']);
                    $caseHdrArray['status_date']  = date_conv($caseHdrArray['status_date']);
                    $caseHdrArray['alert_date']  = date_conv($caseHdrArray['alert_date']);
                    // echo '<pre>';print_r($caseHdrArray);die;
        
                    if(($user_option == 'edit' || $user_option == 'delete') && ($status_code != 'A' || $ref_billinfo_serial_no > 0)) { 
                        session()->setFlashdata('message_not_editable', 'Sorry !! This is not EDITABLE');
                        return redirect()->to($data['requested_url']);
                    }
                    if(empty($other_case_desc)) $caseHdrArray['other_ind'] = 'N'; else $caseHdrArray['other_ind'] = 'Y'; 
        
                    $stat_qry = $this->db->query("select status_desc from status_master where table_name = 'case_header' and status_code = '$status_code' ")->getResultArray()[0];
                    $caseHdrArray['status_desc'] = $stat_qry['status_desc'] ;
                    
                    $where = "fileinfo_header.matter_code = '$matter_code'";
                    $matterArray = $this->db->query("SELECT * FROM `fileinfo_header` WHERE $where")->getResultArray()[0];
                    
        
                    $caseHdrArray['matter_desc1'] = $matterArray['matter_desc1'];
                    $caseHdrArray['matter_desc2'] = $matterArray['matter_desc2'];
                    $caseHdrArray['reference_desc'] = $matterArray['reference_desc'];
                    $caseHdrArray['matter_status_code'] = $matterArray['status_code'];
                    $caseHdrArray['court_code'] = $matterArray['court_code'];
                    $caseHdrArray['full_matter_desc'] = trim($caseHdrArray['matter_desc1']." ".$caseHdrArray['matter_desc2']);
                    
                    $row = $this->db->query("SELECT client_name FROM client_master where client_code = '$client_code'")->getResultArray()[0];
                    $caseHdrArray['client_name'] = $row['client_name'];
                    $court_code = $caseHdrArray['court_code'];
                    
                    $court_qry = $this->db->query("select code_desc from code_master where type_code = '001' and code_code = '$court_code'")->getResultArray()[0];
                    $caseHdrArray['code_desc'] = $court_qry['code_desc'] ;
                    
                    //----
                    $where = "case_detail_int_dates.ref_case_header_serial = '$serial_no'";
                    $caseIntArray = $this->db->query("SELECT * FROM `case_detail_int_dates` WHERE $where")->getResultArray();
                    if( isset($caseIntArray[0]) ) $caseHdrArray['caseIntArray'] = $caseIntArray[0];
                    $row_num = $this->db->query("SELECT count(*) as count FROM `case_detail_int_dates` WHERE $where")->getResultArray()[0]['count'];
    
                    //---- 
                    $where = "case_detail_other_case.ref_case_header_serial = '$serial_no'";
                    $row_num = $this->db->query("SELECT count(*) as count FROM `case_detail_other_case` WHERE $where")->getResultArray()[0]['count'];
                    $caseHdrArray['all_other_case_counter'] = $row_num ;
    
                    //----
                    $where = "case_detail_counsel.ref_case_header_serial = '$serial_no' and case_detail_counsel.activity_of = 'O'";
                    // echo '<pre>';print_r("SELECT case_detail_counsel.*, a.activity_code,a.activity_desc,a.activity_type FROM `case_detail_counsel` INNER JOIN activity_master a ON case_detail_counsel.activity_code = a.activity_code WHERE $where OR case_detail_counsel.ref_case_header_serial");die;
                    $caseInpocketArray = $this->db->query("SELECT case_detail_counsel.*, a.activity_code,a.activity_desc,a.activity_type FROM `case_detail_counsel` INNER JOIN activity_master a ON case_detail_counsel.activity_code = a.activity_code WHERE $where")->getResultArray();
                    // if( isset($caseInpocketArray[0]) ) $caseHdrArray['caseInpocketArray'] = $caseInpocketArray[0];
                    if(!empty($caseInpocketArray)) $caseHdrArray['caseInpocketArray'] = $caseInpocketArray;
                    $row_inpocket = $this->db->query("SELECT count(*) as count FROM `case_detail_counsel` WHERE $where")->getResultArray()[0]['count'];
                    if ($row_inpocket == 0) $row_inpocket = 1;
                    //----
                    $where = "case_detail_counsel.ref_case_header_serial = '$serial_no' and case_detail_counsel.activity_of = 'C'";
                    // echo '<pre>';print_r("SELECT case_detail_counsel.*,a.activity_code,a.activity_desc,a.activity_type, b.associate_name, b.associate_code FROM `case_detail_counsel` INNER JOIN activity_master a ON case_detail_counsel.activity_code = a.activity_code
                    // INNER JOIN associate_master b ON case_detail_counsel.counsel_code = b.associate_code AND b.associate_type ='001' WHERE $where and case_detail_counsel.ref_case_header_serial");die;
                    $caseCounselArray = $this->db->query("SELECT case_detail_counsel.*,a.activity_code,a.activity_desc,a.activity_type, b.associate_name, b.associate_code FROM `case_detail_counsel` INNER JOIN activity_master a ON case_detail_counsel.activity_code = a.activity_code
                    INNER JOIN associate_master b ON case_detail_counsel.counsel_code = b.associate_code AND b.associate_type ='001' WHERE $where and case_detail_counsel.ref_case_header_serial")->getResultArray();
                    // if( isset($caseCounselArray[0]) ) $caseHdrArray['caseCounselArray'] = $caseCounselArray[0];
                    if(!empty($caseCounselArray)) $caseHdrArray['caseCounselArray'] = $caseCounselArray;
                    $row_counsel = $this->db->query("SELECT count(*) as count FROM `case_detail_counsel` WHERE $where")->getResultArray()[0]['count'];
                    if ($row_counsel == 0) $row_counsel = 1;
                    
                    $matter_status =  $this->db->query("SELECT status_code, status_desc FROM status_master WHERE table_name = 'fileinfo_header' and status_code != 'NEW' Order by status_code")->getResultArray();
                    $caseHdrArray['matter_status'] = $matter_status;
    
                    $instrument_status = $this->db->query("select code_code,code_desc from code_master where type_code = '040' order by code_desc")->getResultArray();
                    $caseHdrArray['instrument_status'] = $instrument_status;
                    
                    $count = ['row_num' => $row_num, 'row_inpocket' => $row_inpocket, 'row_counsel' => $row_counsel];
                    // echo "<pre>"; print_r($caseHdrArray['caseCounselArray']); die;
                    
                    if ($user_option == 'Add' )     { $redk = '' ;          $redv = '';          $disv = ''         ; $disb = ''         ;  $redve = '';         $redokadd = '';         $disview = '';         $redLetter = 'disabled'; }
                    if ($user_option == 'Add' )     { $redk = '' ;          $redv = '';          $disv = ''         ; $disb = ''         ;  $redve = '';         $redokadd = '';         $disview = '';         $redLetter = 'disabled'; }
                    return view("pages/CaseDetails/case_status", compact( 'displayId', 'option', 'caseHdrArray', 'permission', "data", 'count', 'redv', 'disv', 'disb', 'disview', 'redLetter'));
                }
            }
        } else {   
             if ($user_option == null) { 

                return view("pages/CaseDetails/case_status",  compact("data", "displayId", "permdata", "option"));

            }
            
           
        }               
    }

    public function case_alert_close() {
        $user_id = session()->userId;
        $data = branches($user_id);
     	$displayId   = ['casesrl_help_id' => '4532'] ; 

        if ($this->request->getMethod() == 'post') { 
            $serialNo = $this->request->getVar("serial_no");
            $msg = ["id" => $serialNo, "msg" => "Alert Closed"]; 

            $global_curr_date2 =  $this->db->query("select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate")->getRowArray()['current_dmydate'];

            if($_REQUEST['alertType'] == 'No') {
                $updt_stmt = "update case_header set alert_disp_ind = 'No', alert_off_by   = '$user_id', alert_off_on   = '$global_curr_date2' where serial_no   = '$serialNo' ";
                
                // print_r($this->db->query($updt_stmt)->getResultArray()); die;
                $this->db->query($updt_stmt);
                // if ($this->db->query($updt_stmt)->getResultArray()) $msg = ["id" => $serialNo, "msg" => "Alert Closed"];
            }
            return view('pages/CaseDetails/case_alert_close', compact( "msg", "data", "displayId"));
        } 
    
        return view('pages/CaseDetails/case_alert_close',  compact("data", "displayId"));
    }

    public function change_billing_option() {
        $arr['leftMenus'] = menu_data(); 
        $arr['menuHead'] = [0];
        $data = branches('demo');
        
        return view("pages/CaseDetails/change_billing_option",  compact("data"));
    }

    public function case_status_spell_check() {
        $arr['leftMenus'] = menu_data(); 
        $arr['menuHead'] = [0];
        $data = branches('demo');
        
        return view("pages/CaseDetails/case_status_spell_check",  compact("data"));
    }

    public function case_status_open() {
        $arr['leftMenus'] = menu_data(); 
        $arr['menuHead'] = [0];
        $data = branches('demo');
        // $displayId = '4579';
        $displayId   = ['display_id' => '4579'] ; 

        
        return view("pages/CaseDetails/case_status_open",  compact("data" , "displayId"));
    }

    public function billed_case_status_edit($option = null) {
        $user_id = session()->userId;
        $data = branches($user_id);
        $displayId   = ['casesrl_help_id' => '4578', 'activity_list_help_id' => '4086', 'counsel_help_id' => '4011'];
        $user_option = $option;
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $finsub =isset($_REQUEST['finsub'])?$_REQUEST['finsub']:null;
        if ($this->request->getMethod() == 'post') {  
            if($finsub=="fsub")
            {
            $case_header_table = $this->db->table("case_header"); 
            $case_detail_int_dates_table = $this->db->table("case_detail_int_dates"); 
            $case_detail_counsel_table = $this->db->table("case_detail_counsel");

            if($user_option == 'post edit' && $user_id == 'abhijit') {   
                        
                $array = array( 'activity_date'         => date_conv($_REQUEST['activity_date']) ,
                                'matter_code'           => $_REQUEST['matter_code'] ,
                                'client_code'           => $_REQUEST['client_code'] ,
                                'other_case_desc'       => stripslashes($_REQUEST['other_case_desc']) ,
                                'prev_fixed_for'        => isset($_REQUEST['prev_fixed_for'])?stripslashes($_REQUEST['prev_fixed_for']):null ,
                                'prev_date'             => date_conv($_REQUEST['prev_date']) ,
                                'next_fixed_for'        => isset($_REQUEST['next_fixed_for'])?stripslashes($_REQUEST['next_fixed_for']):null ,
                                'next_date'             => date_conv($_REQUEST['next_date']) ,
                                'judge_name'            => stripslashes($_REQUEST['judge_name']) ,
                                'header_desc'           => stripslashes($_REQUEST['letter_body_desc']) ,
                                'footer_desc'           => stripslashes($_REQUEST['footer_desc']) ,
                                'cc_desc'               => stripslashes($_REQUEST['cc_desc']) ,
                                'signatory'             => stripslashes($_REQUEST['signatory']) ,
                                'status_code'           => 'B' ,
                                'forwarding_ind'        => isset($_REQUEST['forwarding_ind'])?$_REQUEST['forwarding_ind']:null ,
                                'stage_ind'             => isset($_REQUEST['stage_ind'])?$_REQUEST['stage_ind']:null ,
                                'remarks'               => isset($_REQUEST['remarks'])?stripslashes($_REQUEST['remarks']):null ,
                                'matter_status'         => isset($_REQUEST['matter_status'])?stripslashes($_REQUEST['matter_status']):null ,
                                'status_date'           => date_conv($_REQUEST['status_date']) ,
                                'alert_narration'       => isset($_REQUEST['alert_narration'])?stripslashes($_REQUEST['alert_narration']):null ,
                                'alert_date'            => date_conv($_REQUEST['alert_date']),
                                //'other_body_desc'       => stripslashes($_REQUEST['other_body_desc']) ,
                                );
                    $case_header = $case_header_table->update($array, [ "serial_no" => $_REQUEST['serial_no']]);

                    //------------------------------------------- MODIFY RECORD :  OTHER CASES  ------------------------------------------
                    //echo $_REQUEST['other_ind'] ;
                    if($_REQUEST['other_ind'] == "Y") {

                        $count    = intval($_REQUEST['all_other_case_counter']);
                        $case_no  = explode("|$|",$_REQUEST['all_case']);
                        $sub_desc = explode("|$|",$_REQUEST['all_sub']);

                        $case_det_counsel_del = $case_detail_other_case_table->delete(["case_detail_other_case.ref_case_header_serial" => $_REQUEST['serial_no'] ]);

                        for($i=1; $i<=$count; $i++) {   //echo '<br>Case No : '.$case_no[$i].' --- Subject : '.$sub_desc[$i].' --- i value : '.$i."<br>";
                            $array = array( 'ref_case_header_serial' => $_REQUEST['serial_no'],
                                            'case_no'                => $case_no[$i],
                                            'subject_desc'           => $sub_desc[$i]
                                        );
                            $case_det_other = $case_detail_other_case_table->insert($array);
                        }
                    }          
                
                    //------------------------------------------- MODIFY RECORD :  INTERMEDIATE DATES  -------------------------------------
                    if($_REQUEST['IntermediateFlag'] == "Y") {
                    
                        $case_det_int_date_del = $case_detail_int_dates_table->delete(["case_detail_int_dates.ref_case_header_serial" => $_REQUEST['serial_no']]);
                        $rowno = $_REQUEST['IntermediateRowNo'];
                        
                        for($i=1; $i<=$rowno; $i++) {   
                            
                            if($_REQUEST['del_intermediate'.$i] != "Y") {
                                
                                if(!empty($_REQUEST['date_activity'.$i]) && !empty($_REQUEST['desc_activity'.$i])) {
                                    $array = array( 'ref_case_header_serial' => $_REQUEST['serial_no'],
                                                    'activity_date'          => date_conv($_REQUEST['date_activity'.$i]),
                                                    'activity_desc'          => $_REQUEST['desc_activity'.$i],
                                                );
                                    $case_det_int_date = $case_detail_int_dates_table->insert($array);
                                    }
                                }
                            }
                    }          
                    
                //------------------------------------------- MODIFY RECORD :  INPOCKET  -----------------------------------------------
                $where = "case_detail_counsel.ref_case_header_serial = '".$_POST['serial_no']."' and case_detail_counsel.activity_of = 'O'";
                $case_det_counsel_del = $case_detail_counsel_table->delete($where);

                $rowno = $_REQUEST['InpocketRowNo']; $row_count = $k = 1;
                for($i = 1; $row_count <= $rowno; $i++) {
                    if(isset($_REQUEST['asso_code'.$i], $_REQUEST['asso_name'.$i], $_REQUEST['asso_acty'.$i], $_REQUEST['asso_desc'.$i])) {
                        if(!empty($_REQUEST['asso_code'.$i]) && !empty($_REQUEST['asso_name'.$i]) && !empty($_REQUEST['asso_acty'.$i]) && !empty($_REQUEST['asso_desc'.$i])) {   //echo 'Case No : '.$case_no[$i].' --- Subject : '.$sub_desc[$i].' --- i value : '.$i."<br>";
                            $array = array( 'ref_case_header_serial'  => $_REQUEST['serial_no'],
                                'row_no' => $k,
                                'billing_type' => '1',
                                'activity_of' => 'O',
                                'counsel_code' => $_REQUEST['asso_code'.$i],
                                'activity_code' => $_REQUEST['asso_acty'.$i],
                                'ref_counsel_memo_serial' => NULL,
                                'payable_ind' => "Y",
                            );
                            $case_detail_counsel_table->insert($array);
                            $k++;
                        }
                        $row_count++;
                    }
                }       
                 
                //----------------------------------------------- MODIFY RECORD :  COUNSEL  -------------------------------------------
                $where = "case_detail_counsel.ref_case_header_serial = '".$_POST['serial_no']."' and case_detail_counsel.activity_of = 'C'";
                $case_det_counsel_del = $case_detail_counsel_table->delete($where);
                // $case_det_counsel_del = $case_detail_counsel_table->delete(["case_detail_counsel.ref_case_header_serial" => $_REQUEST['serial_no'] ]);
          
                $rowno = $_REQUEST['CounselRowNo']; $row_count = $k = 1;
                $payable_ind = '';

                for($i=1; $row_count <= $rowno; $i++) {
                    if(isset($_REQUEST['counsel_code'.$i], $_REQUEST['counsel_name'.$i], $_REQUEST['coun_acty'.$i], $_REQUEST['coun_desc'.$i])) {
                        if(!empty($_REQUEST['counsel_code'.$i]) && !empty($_REQUEST['counsel_name'.$i]) && !empty($_REQUEST['coun_acty'.$i]) && !empty($_REQUEST['coun_desc'.$i])) {   
                            $payable_ind = isset($_REQUEST['pay'.$i]) ? "Y" : "N";
                            $array = array( 'ref_case_header_serial'  => $_REQUEST['serial_no'],
                                            'row_no' => $k,
                                            'billing_type' => '2',
                                            'activity_of' => 'C',
                                            'counsel_code' => $_REQUEST['counsel_code'.$i],
                                            'activity_code' => $_REQUEST['coun_acty'.$i],
                                            'ref_counsel_memo_serial' => NULL,
                                            'payable_ind' => $payable_ind,
                                        );
                            $case_det_counsel = $case_detail_counsel_table->insert($array);         
                            $k++;
                        }
                        $row_count++;
                    }
                }        

                $matter_code = $_REQUEST['matter_code'];
                $status_code = $_REQUEST['matter_status'];
                $qry_txt = $this->db->query("UPDATE fileinfo_header SET status_code = '$status_code' WHERE fileinfo_header.matter_code = '$matter_code'");

                session()->setFlashdata('message', 'Records Updated Successfully !!');
                return redirect()->to($data['requested_url']);

            } else if($user_option == 'post edit' && $user_id != 'abhijit') {   
                
                $array = array('activity_date'         => date_conv($_REQUEST['activity_date']) ,
                                'matter_code'           => $_REQUEST['matter_code'] ,
                                'client_code'           => $_REQUEST['client_code'] ,
                                'other_case_desc'       => stripslashes($_REQUEST['other_case_desc']) ,
                                'prev_fixed_for'        => isset($_REQUEST['prev_fixed_for'])?stripslashes($_REQUEST['prev_fixed_for']):null ,
                                'prev_date'             => date_conv($_REQUEST['prev_date']) ,
                                'next_fixed_for'        => isset($_REQUEST['next_fixed_for'])?stripslashes($_REQUEST['next_fixed_for']):null ,
                                'next_date'             => date_conv($_REQUEST['next_date']) ,
                                'judge_name'            => stripslashes($_REQUEST['judge_name']) ,
                                'header_desc'           => stripslashes($_REQUEST['letter_body_desc']) ,
                                'footer_desc'           => stripslashes($_REQUEST['footer_desc']) ,
                                'cc_desc'               => stripslashes($_REQUEST['cc_desc']) ,
                                'signatory'             => stripslashes($_REQUEST['signatory']) ,
                                'status_code'           => 'B' ,
                                'forwarding_ind'        => isset($_REQUEST['forwarding_ind'])?$_REQUEST['forwarding_ind']:null ,
                                'stage_ind'             => isset($_REQUEST['stage_ind'])?$_REQUEST['stage_ind']:null ,
                                'remarks'               => isset($_REQUEST['remarks'])?stripslashes($_REQUEST['remarks']):null ,
                                'matter_status'         => isset($_REQUEST['matter_status'])?stripslashes($_REQUEST['matter_status']):null ,
                                'status_date'           => date_conv($_REQUEST['status_date']) ,
                                'alert_narration'       => isset($_REQUEST['alert_narration'])?stripslashes($_REQUEST['alert_narration']):null ,
                                'alert_date'            => date_conv($_REQUEST['alert_date']),
                                'updated_by'            => $user_id ,
                                'updated_on'            => date_conv(date('d-m-Y')) ,
                                //'other_body_desc'       => stripslashes($_REQUEST['other_body_desc']) ,
                            );
                $case_header = $case_header_table->update($array, [ "serial_no" => $_REQUEST['serial_no']]);

                //------------------------------------------- MODIFY RECORD :  OTHER CASES  ------------------------------------------
                echo $_REQUEST['other_ind'] ;
                if($_REQUEST['other_ind'] == "Y") {
                    $count    = intval($_REQUEST['all_other_case_counter']);
                    $case_no  = explode("|$|",$_REQUEST['all_case']);
                    $sub_desc = explode("|$|",$_REQUEST['all_sub']);
                    
                    $case_det_other_del = $case_detail_other_case_table->delete(["case_detail_other_case.ref_case_header_serial" => $_REQUEST['serial_no'] ]);

                    for($i=1; $i<=$count; $i++) {   
                        echo '<br>Case No : '.$case_no[$i].' --- Subject : '.$sub_desc[$i].' --- i value : '.$i."<br>";
                        $array = array( 'ref_case_header_serial' => $_REQUEST['serial_no'],
                                        'case_no'                => $case_no[$i],
                                        'subject_desc'           => $sub_desc[$i]
                                    );
                    
                        $case_det_other = $case_detail_other_case_table->insert($array);
                    }
                }          
                
                //------------------------------------------- MODIFY RECORD :  INTERMEDIATE DATES  -------------------------------------
                if($_REQUEST['IntermediateFlag'] == "Y") {
                
                    $case_det_int_date_del = $case_detail_int_dates_table->delete(["case_detail_int_dates.ref_case_header_serial" => $_REQUEST['serial_no'] ]);

                    $rowno    = $_REQUEST['IntermediateRowNo'];
                    for($i=1; $i<=$rowno; $i++) {   

                        if($_REQUEST['del_intermediate'.$i] != "Y") {
                            if(!empty($_REQUEST['date_activity'.$i]) && !empty($_REQUEST['desc_activity'.$i])) {
                                $array = array( 'ref_case_header_serial' => $_REQUEST['serial_no'],
                                                'activity_date'          => date_conv($_REQUEST['date_activity'.$i]),
                                                'activity_desc'          => $_REQUEST['desc_activity'.$i],
                                            );
                    
                            $case_det_int_date = $case_detail_int_dates_table->insert($array);
                            }
                        }
                    }
                }          
                
                //------------------------------------------- MODIFY RECORD :  INPOCKET  -----------------------------------------------
                $where = "case_detail_counsel.ref_case_header_serial = '".$_POST['serial_no']."' and case_detail_counsel.activity_of = 'O'";
                $case_det_counsel_del = $case_detail_counsel_table->delete($where);

                $rowno = $_REQUEST['InpocketRowNo']; $row_count = $k = 1;
                for($i = 1; $row_count <= $rowno; $i++) {
                    if(isset($_REQUEST['asso_code'.$i], $_REQUEST['asso_name'.$i], $_REQUEST['asso_acty'.$i], $_REQUEST['asso_desc'.$i])) {
                        if(!empty($_REQUEST['asso_code'.$i]) && !empty($_REQUEST['asso_name'.$i]) && !empty($_REQUEST['asso_acty'.$i]) && !empty($_REQUEST['asso_desc'.$i])) {   //echo 'Case No : '.$case_no[$i].' --- Subject : '.$sub_desc[$i].' --- i value : '.$i."<br>";
                            $array = array( 'ref_case_header_serial'  => $_REQUEST['serial_no'],
                                'row_no' => $k,
                                'billing_type' => '1',
                                'activity_of' => 'O',
                                'counsel_code' => $_REQUEST['asso_code'.$i],
                                'activity_code' => $_REQUEST['asso_acty'.$i],
                                'ref_counsel_memo_serial' => NULL,
                                'payable_ind' => "Y",
                            );
                            $case_detail_counsel_table->insert($array);
                            $k++;
                        }
                        $row_count++;
                    }
                }       
                 
                //----------------------------------------------- MODIFY RECORD :  COUNSEL  -------------------------------------------
                $where = "case_detail_counsel.ref_case_header_serial = '".$_POST['serial_no']."' and case_detail_counsel.activity_of = 'C'";
                $case_det_counsel_del = $case_detail_counsel_table->delete($where);
                // $case_det_counsel_del = $case_detail_counsel_table->delete(["case_detail_counsel.ref_case_header_serial" => $_REQUEST['serial_no'] ]);
          
                $rowno = $_REQUEST['CounselRowNo']; $row_count = $k = 1;
                $payable_ind = '';

                for($i=1; $row_count <= $rowno; $i++) {
                    if(isset($_REQUEST['counsel_code'.$i], $_REQUEST['counsel_name'.$i], $_REQUEST['coun_acty'.$i], $_REQUEST['coun_desc'.$i])) {
                        if(!empty($_REQUEST['counsel_code'.$i]) && !empty($_REQUEST['counsel_name'.$i]) && !empty($_REQUEST['coun_acty'.$i]) && !empty($_REQUEST['coun_desc'.$i])) {   
                            $payable_ind = isset($_REQUEST['pay'.$i]) ? "Y" : "N";
                            $array = array( 'ref_case_header_serial'  => $_REQUEST['serial_no'],
                                            'row_no' => $k,
                                            'billing_type' => '2',
                                            'activity_of' => 'C',
                                            'counsel_code' => $_REQUEST['counsel_code'.$i],
                                            'activity_code' => $_REQUEST['coun_acty'.$i],
                                            'ref_counsel_memo_serial' => NULL,
                                            'payable_ind' => $payable_ind,
                                        );
                            $case_det_counsel = $case_detail_counsel_table->insert($array);         
                            $k++;
                        }
                        $row_count++;
                    }
                }        

                //--------------------------------------------------------------------------------------------------------------
                $matter_code = $_REQUEST['matter_code'];
                $status_code = $_REQUEST['matter_status'];
                $qry_txt = $this->db->query("UPDATE fileinfo_header SET status_code = '$status_code' WHERE fileinfo_header.matter_code = '$matter_code'");

                session()->setFlashdata('message', 'Records Updated Successfully !!');
                return redirect()->to($data['requested_url']);
            
            }
            session()->setFlashdata('message', 'Records Updated Successfully !!');
            return redirect()->to($data['requested_url']);
        }
        if($finsub=="" || $finsub!="fsub")
        {

            $branch_code       = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;
            $serial_no         = isset($_REQUEST['serial_no'])?$_REQUEST['serial_no']:null;
            $matter_code       = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;
            $matter_desc       = isset($_REQUEST['matter_desc'])?$_REQUEST['matter_desc']:null;
            $status_code       = isset($_REQUEST['status_code'])?$_REQUEST['status_code']:null;
            $billable_option   = 'Y' ;
            $row_num = 0;

            $user_qry          = $this->db->query("SELECT * FROM system_user WHERE user_id = '$user_id' ")->getRowArray() ;
            $user_name         = $user_qry['user_name'];

            $fileinfo_header_table = $this->db->table("fileinfo_header");
            
            $case_header_table   = $this->db->table("case_header");

            $case_detail_counsel_table   = $this->db->table("case_detail_counsel");

            $case_detail_int_dates_table   = $this->db->table("case_detail_int_dates");
      
            $case_detail_other_case_table   = $this->db->table("case_detail_other_case");
         
            if ($user_option == 'Post Edit') { $predk = 'readonly' ;  $predv = 'readonly';  $pdisv = 'disabled' ; $pdisb = 'disabled' ;  $predve = 'disabled'; $predokadd = 'readonly'; $pdisview = 'disabled'; $predLetter = ''; }

            $serial_no = $this->request->getVar("serial_no");
            $caseHdrArray  = $this->db->query("SELECT * FROM `case_header` WHERE case_header.serial_no = '$serial_no'")->getResultArray()[0];

            //$serial_no             = $caseHdrArray['serial_no'];
            $activity_date         = date_conv($caseHdrArray['activity_date']);
            $branch_code           = $caseHdrArray['branch_code'];
            $matter_code           = $caseHdrArray['matter_code'];
            $client_code           = $caseHdrArray['client_code'];
            $other_case_desc       = $caseHdrArray['other_case_desc'];
            $judge_name            = $caseHdrArray['judge_name'];
            $appear_for            = $caseHdrArray['appear_for'];
            $prev_fixed_for        = $caseHdrArray['prev_fixed_for'];
           // $last_remark           = $caseHdrArray['last_remark'];
            $prev_date             = date_conv($caseHdrArray['prev_date']);
            $next_fixed_for        = $caseHdrArray['next_fixed_for'];
            $next_date             = date_conv($caseHdrArray['next_date']);
            $billable_option       = $caseHdrArray['billable_option'];
            $header_desc           = $caseHdrArray['header_desc'];
            $footer_desc           = $caseHdrArray['footer_desc'];
            $cc_desc               = $caseHdrArray['cc_desc'];
            $signatory               = $caseHdrArray['signatory'];
            $ref_billinfo_serial_no = $caseHdrArray['ref_billinfo_serial_no'];
            $letter_no             = $caseHdrArray['letter_no'];
            $letter_date           = $caseHdrArray['letter_date'];
            $case_status_report_no = $caseHdrArray['case_status_report_no'];
            //$ref_court_exp_serial  = $caseHdrArray['ref_court_exp_serial'];
            //$court_exp_by          = $caseHdrArray['court_exp_by'];
            //$court_exp_details     = $caseHdrArray['court_exp_details'];
            //$court_exp_amount      = $caseHdrArray['court_exp_amount'];
            $status_code           = $caseHdrArray['status_code'];
            $forwarding_ind        = $caseHdrArray['forwarding_ind'];
            $remarks               = $caseHdrArray['remarks'];
            $status_date           = date_conv($caseHdrArray['status_date']);
            $alert_narration       = $caseHdrArray['alert_narration'];
            $alert_date            = date_conv($caseHdrArray['alert_date']);
            $prepared_by           = $caseHdrArray['prepared_by'];
            $prepared_on           = $caseHdrArray['prepared_on'];

            if($user_option == 'post edit'  &&  $status_code != 'B' ) {
                session()->setFlashdata('message_not_editable', 'Sorry !! This is not EDITABLE');
                return redirect()->to($data['requested_url']);
            }

            if(empty($other_case_desc)) { $other_ind = 'N' ; } else { $other_ind = 'Y' ; } 

            //----
            $stat_qry    = $this->db->query("select status_desc from status_master where table_name = 'case_header' and status_code = '$status_code' ")->getRowArray() ;
            $caseHdrArray['status_desc'] = $stat_qry['status_desc'] ;

            //----
            $where         = "fileinfo_header.matter_code = '$matter_code'";
            $matterArray   = $this->db->query("SELECT * FROM `fileinfo_header` WHERE $where")->getRowArray();
            $caseHdrArray['matter_desc1']       = $matterArray['matter_desc1'];
            $caseHdrArray['matter_desc2']       = $matterArray['matter_desc2'];
            $caseHdrArray['reference_desc']     = $matterArray['reference_desc'];
            $caseHdrArray['matter_status_code'] = $matterArray['status_code'];
            $caseHdrArray['court_code']         = $matterArray['court_code'];
            $caseHdrArray['full_matter_desc']   = trim($caseHdrArray['matter_desc1']." ".$caseHdrArray['matter_desc2']);

            $row = $this->db->query("SELECT client_name FROM client_master where client_code = '$client_code'")->getRowArray();
            $caseHdrArray['client_name'] = $row['client_name'];

            $court_code = $caseHdrArray['court_code'];
            $court_qry = $this->db->query("select code_desc from code_master where type_code = '001' and code_code = '$court_code'")->getRowArray();
            $caseHdrArray['code_desc'] = $court_qry['code_desc'] ;
            

            //---- 
            $where         = "case_detail_int_dates.ref_case_header_serial = '$serial_no'";
            $caseIntArray = $this->db->query("SELECT * FROM `case_detail_int_dates` WHERE $where")->getResultArray();
            if( isset($caseIntArray[0]) ) $caseHdrArray['caseIntArray'] = $caseIntArray[0];
            $row_num = $this->db->query("SELECT count(*) as count FROM `case_detail_int_dates` WHERE $where")->getResultArray()[0]['count'];
            
            //---- 
            $where = "case_detail_other_case.ref_case_header_serial = '$serial_no'";
            $row_num = $this->db->query("SELECT count(*) as count FROM `case_detail_other_case` WHERE $where")->getResultArray()[0]['count'];
            $caseHdrArray['all_other_case_counter'] = $row_num ;

            //----
            $where = "case_detail_counsel.ref_case_header_serial = '$serial_no' and case_detail_counsel.activity_of = 'O'";
            // echo '<pre>';print_r("SELECT case_detail_counsel.*,a.activity_code,a.activity_desc,a.activity_type FROM `case_detail_counsel` INNER JOIN activity_master a ON case_detail_counsel.activity_code = a.activity_code WHERE $where order by case_detail_counsel.ref_case_header_serial");die;
            $caseInpocketArray = $this->db->query("SELECT case_detail_counsel.*,a.activity_code,a.activity_desc,a.activity_type FROM `case_detail_counsel` INNER JOIN activity_master a ON case_detail_counsel.activity_code = a.activity_code WHERE $where order by case_detail_counsel.ref_case_header_serial")->getResultArray();
            // if( isset($caseInpocketArray[0]) ) $caseHdrArray['caseInpocketArray'] = $caseInpocketArray[0];
            if(!empty($caseInpocketArray)) $caseHdrArray['caseInpocketArray'] = $caseInpocketArray;
            $row_inpocket = $this->db->query("SELECT count(*) as count FROM `case_detail_counsel` WHERE $where")->getResultArray()[0]['count'];
            if ($row_inpocket == 0) $row_inpocket = 1;
            //----
            $where = "case_detail_counsel.ref_case_header_serial = '$serial_no' and case_detail_counsel.activity_of = 'C'";
            // echo '<pre>';print_r("SELECT case_detail_counsel.*,a.activity_code,a.activity_desc,a.activity_type, b.associate_name, b.associate_code FROM `case_detail_counsel` INNER JOIN activity_master a ON case_detail_counsel.activity_code = a.activity_code
            // INNER JOIN associate_master b ON case_detail_counsel.counsel_code = b.associate_code AND b.associate_type ='001' WHERE $where and case_detail_counsel.ref_case_header_serial");die;
            $caseCounselArray = $this->db->query("SELECT case_detail_counsel.*,a.activity_code,a.activity_desc,a.activity_type, b.associate_name, b.associate_code FROM `case_detail_counsel` INNER JOIN activity_master a ON case_detail_counsel.activity_code = a.activity_code
            INNER JOIN associate_master b ON case_detail_counsel.counsel_code = b.associate_code AND b.associate_type ='001' WHERE $where and case_detail_counsel.ref_case_header_serial")->getResultArray();
            // if( isset($caseCounselArray[0]) ) $caseHdrArray['caseCounselArray'] = $caseCounselArray[0];
            if(!empty($caseCounselArray)) $caseHdrArray['caseCounselArray'] = $caseCounselArray;
            $row_counsel = $this->db->query("SELECT count(*) as count FROM `case_detail_counsel` WHERE $where")->getResultArray()[0]['count'];
            if ($row_counsel == 0) $row_counsel = 1;

            $matter_status =  $this->db->query("SELECT status_code, status_desc FROM status_master WHERE table_name = 'fileinfo_header' and status_code != 'NEW' Order by status_code")->getResultArray();
            $caseHdrArray['matter_status'] = $matter_status;

            $instrument_status = $this->db->query("select code_code,code_desc from code_master where type_code = '040' order by code_desc")->getResultArray();
            $caseHdrArray['instrument_status'] = $instrument_status;
            
            $count = ['row_num' => $row_num, 'row_inpocket' => $row_inpocket, 'row_counsel' => $row_counsel];
// echo "<pre>"; print_r($caseInpocketArray); die;
            return view("pages/CaseDetails/billed_case_status_edit", compact( 'displayId', 'option', 'caseHdrArray', "data", 'count'));
        }
        }
        else { 
            if ($user_option == null) {
                return view("pages/CaseDetails/billed_case_status_edit",  compact("data", "displayId", "option"));
            }
        }
    }


    /*********************************************************************************************/
    /***************************** Case Details [Reports] ***********************************/
    /*********************************************************************************************/

    public function cases_appeared() {
        if($this->request->getMethod() == 'post') {
            $requested_url = session()->requested_end_menu_url;
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
            
            $branch_code   = $_REQUEST['branch_code'] ;
            $start_date    = $_REQUEST['start_date'] ;   if($start_date != '') {$start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01' ; }
            $end_date      = $_REQUEST['end_date'] ;     $end_date_ymd   = date_conv($end_date) ;  
            $client_code   = $_REQUEST['client_code'] ;  if($client_code == '') { $client_code = '%' ; }
            $client_name   = $_REQUEST['client_name'] ; 
            $matter_code   = $_REQUEST['matter_code'] ;  if($matter_code == '') { $matter_code = '%' ; }
            $matter_desc   = $_REQUEST['matter_desc'] ;   
            $court_code    = $_REQUEST['court_code'] ;   if($court_code  == '') { $court_code  = '%' ; }
            $court_name    = $_REQUEST['court_name'] ;  
            $initial_code  = $_REQUEST['initial_code'] ;   if($initial_code  == '') { $initial_code  = '%' ; }
            $initial_name  = $_REQUEST['initial_name'] ;    
            $desc_ind      = $_REQUEST['desc_ind'] ;
            $report_seq    = $_REQUEST['report_seq'] ;
            $output_type   = $_REQUEST['output_type'] ;
            $forward_inp   = $_REQUEST['forward_inp'] ;

            if ($start_date == '') { $period_desc = 'UPTO '.$end_date ; } else { $period_desc = $start_date.' - '.$end_date ; } 
    
            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getRowArray();
            $branch_name = (!empty($branch_qry)) ? $branch_qry['branch_name'] : '';

            if($client_code == '') $client_name = '';
            else if($client_code == '%') $client_name = 'All';
            else {
                $client_qry    = $this->db->query("select client_name from client_master where client_code = '$client_code' ")->getRowArray();
                $client_name   = (!empty($client_qry)) ? $client_qry['client_name'] : '';
            }
            
            if($forward_inp == 'Y') $forwarding_ind = 'Yes';
            else if($forward_inp == 'N') $forwarding_ind = 'No';
            else {
                $forward_inp = '%';
                $forwarding_ind = 'All';
            }
            
            $case_sql = ''; $report_desc = '';
            
            if($output_type == 'Report' || $output_type == 'Pdf'){
                switch($report_seq) {
                    case '1':
                        $case_sql = "SELECT a.activity_date, a.client_code, a.matter_code, a.judge_name, a.next_date, a.prev_date, a.header_desc, a.next_fixed_for, a.prev_fixed_for, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount,
                            CONCAT(b.matter_desc1, ' ', b.matter_desc2) AS matter_desc, c.client_name, d.code_desc AS court_name, b.matter_desc1, b.matter_desc2, a.serial_no
                            FROM case_header a JOIN fileinfo_header b ON a.matter_code = b.matter_code JOIN client_master c ON a.client_code = c.client_code JOIN code_master d ON b.court_code = d.code_code
                            WHERE a.activity_date BETWEEN '$start_date_ymd' AND '$end_date_ymd'
                                AND a.client_code LIKE '$client_code' AND a.matter_code LIKE '$matter_code'
                                AND IFNULL(a.forwarding_ind, 'N') LIKE '$forward_inp'
                                AND b.court_code LIKE '$court_code' AND d.type_code = '001'
                            ORDER BY a.activity_date, d.code_desc, a.client_code, a.matter_code limit 40";
                        $report_desc   = 'CASES APPEARED DURING A PERIOD [ACTIVITY DATE-WISE]' ;
                        break;
                    case '2': 
                        $case_sql = "SELECT a.activity_date, a.client_code, a.matter_code, a.judge_name, a.next_date, a.prev_date, a.header_desc, a.next_fixed_for, a.prev_fixed_for, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount,
                            CONCAT(b.matter_desc1, ' ', b.matter_desc2) AS matter_desc, c.client_name, d.code_desc, b.matter_desc1, b.matter_desc2
                            FROM case_header a JOIN fileinfo_header b ON a.matter_code = b.matter_code JOIN client_master c ON a.client_code = c.client_code JOIN code_master d ON b.court_code = d.code_code
                            WHERE a.activity_date BETWEEN '$start_date_ymd' AND '$end_date_ymd'
                                AND a.client_code LIKE '$client_code' AND a.matter_code LIKE '$matter_code'
                                AND IFNULL(a.forwarding_ind, 'N') LIKE '$forward_inp'
                                AND b.court_code LIKE '$court_code' AND d.type_code = '001'
                            ORDER BY a.matter_code, a.activity_date limit 40";
                        $report_desc   = 'CASES APPEARED DURING A PERIOD [MATTER/ACTIVITY DATE-WISE]' ;
                        break;
                }
                $reports  = $this->db->query($case_sql)->getResultArray();
                $case_cnt  = count($reports); $date = date('d-m-Y');

                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "period_desc" => $period_desc,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    "matter_code" => $matter_code,
                    "matter_desc" => $matter_desc,
                    "court_code" => $court_code,
                    "court_name" => $court_name,
                    "date" => $date,
                    "requested_url" => $requested_url,
                    "report_seq" => $report_seq,
                    "forwarding_ind" => $forwarding_ind,
                    "desc_ind" => $desc_ind,
                ];

                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/CaseDetails/cases_appeared", compact("reports", "params", "report_type"));
                    // echo htmlspecialchars($reportHTML); die;
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/CaseDetails/cases_appeared",  compact("reports","params"));

            } else if($output_type == 'Excel') {   
                            
                $case_sql = ''; $report_desc = '';
                switch($report_seq) {
                    case '1':
                        $case_sql = "select a.activity_date,a.serial_no,a.client_code,a.matter_code,a.remarks,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for,
                        a.prev_fixed_for, b.reference_desc, b.initial_code, b.court_code, b.date_of_filing, b.stake_amount, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, 
                        c.client_name, d.code_desc court_name, b.matter_desc1, b.matter_desc2   
                        from fileinfo_header b, client_master c, code_master d, case_header a 
                        where a.activity_date between '$start_date_ymd'  and '$end_date_ymd'  
                        and a.client_code like '$client_code'
                        and a.matter_code like '$matter_code' 
                        and a.client_code = c.client_code
                        and a.matter_code = b.matter_code
                        and b.initial_code like '$initial_code'
                        and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                        and b.court_code like '$court_code' 
                        and b.court_code = d.code_code 
                        and d.type_code = '001' 
                        group by a.serial_no
                        order by a.activity_date,d.code_desc,a.client_code,a.matter_code " ;
                        break;
                    case '2': 
                        $case_sql = "select a.activity_date,a.serial_no,a.client_code,a.matter_code,a.remarks,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for,
                         a.prev_fixed_for, b.initial_code, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, 
                         c.client_name, d.code_desc, b.matter_desc1, b.matter_desc2   
                        from fileinfo_header b, client_master c, code_master d, case_header a 
                        where a.activity_date between '$start_date_ymd'  and '$end_date_ymd'  
                        and a.client_code like '$client_code'
                        and a.matter_code like '$matter_code'
                        and b.initial_code like '$initial_code'
                        and a.client_code = c.client_code
                        and a.matter_code = b.matter_code
                        and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                        and b.status_code = 'A' 
                        and b.court_code like '$court_code' 
                        and b.court_code = d.code_code 
                        and d.type_code = '001'  
                        group by a.serial_no   
                        order by a.matter_code,a.activity_date" ;
                        break;
                }
                $excels  = $this->db->query($case_sql)->getResultArray();
                $case_cnt  = count($excels);
        
                if(empty($excels)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $fileName = 'CASES_APPEARED-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
        
                $headings = ['Acty Dt','Recd No','Client','Matter','Initial','Case No','Matter Desc','Court','Judge','Reference','Fix For (Day)','Next Dt','Fix For (Next)','Prev Dt','Fix For (Prev)','Filing Dt','Amount','Forwarding','Remarks'];

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

                foreach ($excels as $excel){

                    $day_fixed_for = get_fixed_for($excel['matter_code'], $excel['activity_date']);
                    if($excel['stake_amount'] > 0) { 
                        $stake_amount = $excel['stake_amount'];
                    } 

                    $sheet->setCellValue('A' . $rows, date_conv($excel['activity_date']));
                    $sheet->setCellValue('B' . $rows, strtoupper($excel['serial_no']));
                    $sheet->setCellValue('C' . $rows, strtoupper($excel['client_name']));
                    $sheet->setCellValue('D' . $rows, strtoupper($excel['matter_code']));
                    $sheet->setCellValue('E' . $rows, strtoupper($excel['initial_code']));
                    $sheet->setCellValue('F' . $rows, strtoupper($excel['matter_desc1']));
                    $sheet->setCellValue('G' . $rows, strtoupper($excel['matter_desc2']));
                    $sheet->setCellValue('H' . $rows, isset($excel['court_name']) ? strtoupper($excel['court_name']) : '');
                    $sheet->setCellValue('I' . $rows, strtoupper($excel['judge_name']));
                    $sheet->setCellValue('J' . $rows, strtoupper($excel['reference_desc']));
                    $sheet->setCellValue('K' . $rows, strtoupper($day_fixed_for));
                    $sheet->setCellValue('L' . $rows, date_conv($excel['next_date']));
                    $sheet->setCellValue('M' . $rows, strtoupper($excel['next_fixed_for']));
                    $sheet->setCellValue('N' . $rows, date_conv($excel['prev_date']));
                    $sheet->setCellValue('O' . $rows, strtoupper($excel['prev_fixed_for']));
                    $sheet->setCellValue('P' . $rows, date_conv($excel['date_of_filing']));
                    $sheet->setCellValue('Q' . $rows, isset($stake_amount) ? $stake_amount : '' );
                    $sheet->setCellValue('R' . $rows, $forwarding_ind);
                    $sheet->setCellValue('S' . $rows, strtoupper($excel['remarks']));

                    $style = $sheet->getStyle('A' . $rows . ':S' . $rows);
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
        }else {
            $user_id = session()->userId;
            $data = branches($user_id);
            $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220' , 'court_help_id' => '4221', 'initial_help_id'=>'4191'] ;
    
            return view("pages/CaseDetails/cases_appeared",  compact("data", "displayId"));
        }
    }
    public function cases_tobe_appeared() {
        if($this->request->getMethod() == 'post') {
        
            $requested_url = base_url($_SERVER['REQUEST_URI']);

            $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
            $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
            $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
            // $my_menuid     = "350202";
            $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
            $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
            $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
            $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
            $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
            $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
            $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
            $report_desc   = 'CASES TO BE APPEARED DURING A PERIOD [NEXT DATE-WISE]' ;
    
            //-------
            $branch_code   = $_REQUEST['branch_code'] ;
            // $branch_code   = "B001" ;
            $start_date    = $_REQUEST['start_date'] ;   

            //$start_date    = "21-11-2003";
            $start_date_ymd = ($start_date != '') ? date_conv($start_date,'-') : '';
            
            
            $end_date      = $_REQUEST['end_date'] ; //    "05-02-2004";
            //$end_date    = "05-02-2004";

            $end_date_ymd   = ($start_date != '') ? date_conv($end_date,'-') : '';  
           
            // echo $start_date_ymd; die;


            $client_code   = $_REQUEST['client_code'] ;  // '';
            if($client_code == '') { $client_code = '%' ; }
            $client_name   = $_REQUEST['client_name'] ; //'';    
            $client_name   = str_replace('_|_','&', $client_name) ;
            $client_name   = str_replace('-|-',"'", $client_name) ;
            $matter_code   = $_REQUEST['matter_code'] ; //'';   
            if($matter_code == '') { $matter_code = '%' ; }
            $matter_desc   = $_REQUEST['matter_desc'] ; //'';    
            $court_code    = $_REQUEST['court_code'] ; //'';    
            if($court_code  == '') { $court_code  = '%' ; }
            $court_name    = $_REQUEST['court_name'] ; //'';    
            $output_type   = $_REQUEST['output_type'] ; //'';  
            $initial_code    = $_REQUEST['initial_code'] ; //'';    
             if(empty($initial_code)){ $initial_code = '%' ; }
            $initial_name    = $_REQUEST['initial_name'] ; //''; 
            
             
            if ($start_date == '') { $period_desc = 'UPTO '.$end_date ; } else { $period_desc = $start_date.' - '.$end_date ; } 
            //
            //$branch_qry    = mysql_fetch_array(mysql_query("select branch_name from branch_master where branch_code = '$branch_code' ",$link)) ;
            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0];
    
            $branch_name   = $branch_qry["branch_name"] ;
            
            
            if($court_code  == '%') { $court_heading   = 'COURT : ALL'  ; } else { $court_heading   = 'COURT : SELECTIVE'  ; }
            if($client_code == '%') { $client_heading  = 'CLIENT : ALL' ; } else { $client_heading  = 'CLIENT : SELECTIVE' ; }
            if($matter_code == '%') { $matter_heading  = 'MATTER : ALL' ; } else { $matter_heading  = 'MATTER : SELECTIVE' ; }
            if($client_code == '%') { $client_name     = 'ALL' ; } else { $client_name     = $client_name ; }
            if($initial_code  == '%') { $initial_heading   = 'INITIAL : ALL'  ; } else { $initial_heading   = 'INITIAL : SELECTIVE'  ; }
            
            
            $report_sub_desc = '[ '.$court_heading.' / '.$client_heading.' / '.$matter_heading.' / '.$initial_heading.' ]' ;
            if($output_type == 'Report' || $output_type == 'Pdf'){

                $case_sql = "select a.*, b.initial_code,b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, d.code_desc court_name, b.matter_desc1, b.matter_desc2    
                from fileinfo_header b, client_master c, code_master d, case_header a 
                where a.next_date        between '$start_date_ymd'  and '$end_date_ymd'  
                and a.client_code          like '$client_code'
                and a.matter_code          like '$matter_code' 
                and ifnull(b.initial_code,'N') like '$initial_code' 
                and b.status_code            = 'A' 
                and a.client_code             = c.client_code
                and a.matter_code             = b.matter_code
                and b.court_code           like '$court_code' 
                and b.court_code              = d.code_code 
                and d.type_code               = '001'     
                order by a.next_date,d.code_desc,a.client_code,a.matter_code limit 40" ;
            
                
                // $case_qry  = mysql_query($case_sql,$link);
                // $case_qry  = $this->db->query($case_sql)->getResultArray();
                $reports  = $this->db->query($case_sql)->getResultArray();
                $case_cnt  = count($reports);
                $date = date('d-m-Y');
                
               if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                
                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "report_sub_desc" => $report_sub_desc,
                    "period_desc" => $period_desc,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    "matter_code" => $matter_code,
                    "matter_desc" => $matter_desc,
                    "court_code" => $court_code,
                    "court_name" => $court_name,
                    "initial_code" => $initial_code,
                    "initial_name" => $initial_name,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),

                ];
                
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/CaseDetails/cases_tobe_appeared",  compact("reports","params", "report_type"));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/CaseDetails/cases_tobe_appeared",  compact("reports","params"));

            } else if($output_type == 'Excel') { 

                $case_sql = "select a.*, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, c.client_name, d.code_desc court_name, b.requisition_no, b.matter_desc1, b.matter_desc2, b.corrp_attn_code, b.reference_type_code    
                        from client_master c, code_master d, case_header a, fileinfo_header b 
                        where a.next_date between '$start_date_ymd'  and '$end_date_ymd'  
                        and a.client_code like '$client_code'
                        and a.matter_code like '$matter_code'
                        and b.status_code = 'A' 
                        and a.client_code = c.client_code
                        and a.matter_code = b.matter_code
                        and b.court_code like '$court_code' 
                        and b.court_code = d.code_code 
                        and d.type_code = '001'     
                        order by a.next_date,d.code_desc,a.client_code,a.matter_code " ;
                $excels  = $this->db->query($case_sql)->getResultArray();
                $case_cnt  = count($excels);
        
                try {
                    $excels[0];
                    if($case_cnt == 0)  throw new \Exception('No Records Found !!');

                } catch (\Exception $e) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($_SERVER['REQUEST_URI']);
                }

                $fileName = 'CASES_TOBE_APPEARED-'.date('d-m-Y').'.xlsx';   
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $headings = ['Record Sl', 'Next Date', 'Client', 'Matter', 'Case No', 'Matter Description', 'Court', 'Judge', 'Requisition No', 'Reference', 'Product', 'Attention', 'Represent', 'Fix For', 'Last Date', 'Fix For (Last)', 'Prev Date', 'Filing Date', 'Remarks'];       
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
                foreach ($excels as $excel){

                    $product_desc        = get_code_desc('007',$excel['reference_type_code']);
                    $attention_name      = get_attention_name($excel['corrp_attn_code']) ;
                    //
                    $aQry =  $this->db->query("select group_concat(name separator '; ') repr_name from fileinfo_details where matter_code = '$matter_code' and record_code = '10' order by row_no ")->getResultArray()[0];
                    $repr_name = $aQry['repr_name'];

                    $sheet->setCellValue('A' . $rows, strtoupper($excel['serial_no']));
                    $sheet->setCellValue('B' . $rows, date_conv($excel['next_date']));
                    $sheet->setCellValue('C' . $rows, strtoupper($excel['client_name']));
                    $sheet->setCellValue('D' . $rows, strtoupper($excel['matter_code']));
                    $sheet->setCellValue('E' . $rows, strtoupper($excel['matter_desc1']));
                    $sheet->setCellValue('F' . $rows, strtoupper($excel['matter_desc2']));
                    $sheet->setCellValue('G' . $rows, strtoupper($excel['court_name']));
                    $sheet->setCellValue('H' . $rows, strtoupper($excel['judge_name']));
                    $sheet->setCellValue('I' . $rows, strtoupper($excel['requisition_no']));
                    $sheet->setCellValue('J' . $rows, strtoupper($excel['reference_desc']));
                    $sheet->setCellValue('K' . $rows, strtoupper($product_desc));
                    $sheet->setCellValue('L' . $rows, strtoupper($attention_name));
                    $sheet->setCellValue('M' . $rows, $repr_name);
                    $sheet->setCellValue('N' . $rows, strtoupper($excel['next_fixed_for']));
                    $sheet->setCellValue('O' . $rows, date_conv($excel['activity_date']));
                    $sheet->setCellValue('P' . $rows, strtoupper($excel['prev_fixed_for']));
                    $sheet->setCellValue('Q' . $rows, date_conv($excel['prev_date']));
                    $sheet->setCellValue('R' . $rows, date_conv($excel['date_of_filing']));
                    $sheet->setCellValue('S' . $rows, strtoupper($excel['remarks']));

                    $style = $sheet->getStyle('A' . $rows . ':S' . $rows);
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
        } else {
            $user_id = session()->userId;
            $data = branches($user_id);
            $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220' , 'court_help_id' => '4221' , 'initial_help_id' => '4191'] ; 
      
            return view("pages/CaseDetails/cases_tobe_appeared",  compact("data", "displayId"));
        }
    }
    public function list_of_cases() {
        if($this->request->getMethod() == 'post') {
            $requested_url = base_url($_SERVER['REQUEST_URI']);
            $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
            $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
            //$my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
            $my_menuid     = "350203";
            //$menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
            $menu_id       = "350203";	
            $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
            $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
            $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
            $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
            $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
            $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
            $report_desc   = 'LIST OF CASES AS ON DATE [CLIENT/COUR-WISE]' ;

            //-------
            $ason_date     = $_REQUEST['ason_date'] ;    $ason_date_ymd  = date_conv($ason_date,'-') ; 
            $branch_code   = $_REQUEST['branch_code'] ;
            $client_code   = $_REQUEST['client_code'] ;  
            if($client_code == '') { $client_code = '%' ; }
            $client_name   = $_REQUEST['client_name'] ;   
            $client_name   = str_replace('_|_','&', $client_name) ;
            $client_name   = str_replace('-|-',"'", $client_name) ;
            $court_code    = $_REQUEST['court_code'] ;   
            if($court_code  == '') { $court_code  = '%' ; }
            $court_name    = $_REQUEST['court_name'] ;   
            $status_code   = $_REQUEST['status_code'] ;  
            if($status_code == '') { $status_code = '%' ; }
            $status_name   = $_REQUEST['status_name'] ;   
            $output_type    = $_REQUEST['output_type'] ;
            
            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0];
            $branch_name   = $branch_qry["branch_name"] ;
            
            if($output_type == 'Report' || $output_type == 'Pdf') {

                $case_sql = "select a.*, b.client_name, c.code_desc court_name, d.code_desc status_name
                    from fileinfo_header a, client_master b, code_master c, code_master d  
                    where a.client_code like '$client_code'
                    and a.court_code like '$court_code'
                    and a.status_code like '$status_code' 
                    and a.client_code = b.client_code
                    and a.court_code = c.code_code  and c.type_code = '001'     
                    and a.status_code = d.code_code  and d.type_code = '008'     
                    order by b.client_name,d.code_desc,a.matter_code limit 40" ;
                $reports  = $this->db->query($case_sql)->getResultArray();
                $case_cnt  = count($reports);
                $date = date('d-m-Y');

               if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "ason_date" => $ason_date,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    // "matter_code" => $matter_code,
                    // "matter_desc" => $matter_desc,
                    "court_code" => $court_code,
                    "court_name" => $court_name,
                    "status_code" => $status_code,
                    "status_desc" => $status_name,
                    // "initial_name" => $initial_name,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                ];
                
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/CaseDetails/list_of_cases",  compact("reports","params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/CaseDetails/list_of_cases",  compact("reports","params"));
            } else if($output_type == 'Excel') { 
                $case_sql = "select a.*, b.client_name, c.code_desc court_name, d.code_desc status_name from fileinfo_header a, client_master b, code_master c, code_master d where a.client_code like '$client_code'
                    and a.court_code like '$court_code' and a.status_code like '$status_code' and a.client_code = b.client_code and a.court_code = c.code_code  and c.type_code = '001'     
                    and a.status_code = d.code_code  and d.type_code = '008' order by b.client_name,d.code_desc,a.matter_code limit 40" ;
                
                $reports  = $this->db->query($case_sql)->getResultArray();
                $case_cnt  = count($reports);
                $date = date('d-m-Y');

               if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                
                $fileName = 'List-of-Cases-Client-Court-wise-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Define the headings
                $headings = ['Court', 'Judge', 'Master', 'Case No/Matter Description', 'Filling Dt', 'Amount', 'Status'];

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
                foreach ($reports as $key => $report) { 
                    $sheet->setCellValue('A' . $rows, $report['client_name']);
        			// Appling cell merging
                    $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
            	    $style->getActiveSheet()->mergeCells('A'.$rows.':G'.$rows);
            	    // Apply Background Color to the current row
                    $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ffea95');
                    $rows++;
                    
                    $sheet->setCellValue('A' . $rows, $report['court_name']);
                    $sheet->setCellValue('B' . $rows, $report['judge_name']);
                    $sheet->setCellValue('C' . $rows, $report['matter_code']);
                    $sheet->setCellValue('D' . $rows, $report['matter_desc1']);
                    $sheet->setCellValue('E' . $rows, date_conv($report['date_of_filing'],'-'));
                    $sheet->setCellValue('F' . $rows, $report['stake_amount']);
                    $sheet->setCellValue('G' . $rows, $report['status_name']);
                    $rows++;
                    
                    $sheet->setCellValue('D' . $rows, $report['matter_desc2']);
                    // Appling cell merging
                    $style = $sheet->getStyle('A' . $rows . ':G' . $rows);
            	    $style->getActiveSheet()->mergeCells('D'.$rows.':G'.$rows);
            	    // Apply Background Color to the current row
                    $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('c7f1f0');
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
            
        } else {
            $user_id = session()->userId;
            $data = branches($user_id);
            $displayId   = ['client_help_id' => '4072', 'status_help_id' => '4223' , 'court_help_id' => '4221'] ; 

            return view("pages/CaseDetails/list_of_cases",  compact("data", "displayId"));
        }
    }
    public function list_of_unbilled_case_status() {
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
            $report_desc   = '' ;

            //-------
            //$ason_date     = $_REQUEST['ason_date'] ;    $ason_date_ymd = date_conv($ason_date,'-');
            $branch_code   = $_REQUEST['branch_code'] ;
            $client_code   = $_REQUEST['client_code'] ;  
            if($client_code == '') { $client_code = '%' ; }
            $client_name   = $_REQUEST['client_name'] ;   
            $client_name   = str_replace('_|_','&', $client_name) ;
            $client_name   = str_replace('-|-',"'", $client_name) ;
            $matter_code   =  $_REQUEST['matter_code'] ;  
            if($matter_code == '') { $matter_code = '%' ; }
            $matter_desc   = $_REQUEST['matter_desc'] ;   
            $court_code    = $_REQUEST['court_code'] ;   
            if($court_code  == '') { $court_code  = '%' ; }
            $court_name    = $_REQUEST['court_name'] ;   
            $report_seq    = $_REQUEST['report_seq'] ; 
            $initial_code  = $_REQUEST['initial_code'] ;     
            if($initial_code  == '') { $initial_code  = '%' ; }
            $initial_name  = $_REQUEST['initial_name'] ; 
            //
            $start_date    = $_REQUEST['start_date'] ;  
            $start_date_ymd = date_conv($start_date,'-');
            $end_date      = $_REQUEST['end_date'] ;     
            $end_date_ymd   = date_conv($end_date,'-') ; 
            $output_type    = $_REQUEST['output_type'] ; 

            if ($start_date == '') { $period_desc = 'UPTO '.$end_date ; } else { $period_desc = $start_date.' - '.$end_date ; } 
            //
            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0];
            
            $branch_name   = $branch_qry["branch_name"] ;
            
            if($output_type == 'Report' || $output_type == 'Pdf') {
                $case_sql = "";
                switch($report_seq) {
                    case '1' : 
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, d.code_desc court_name,
                        e.case_no, e.subject_desc    
                        from fileinfo_header b, client_master c, code_master d, case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                        where a.branch_code          like '$branch_code' 
                            and a.activity_date          between '$start_date_ymd'  and '$end_date_ymd'  
                            and a.status_code             = 'A'
                            and a.billable_option         = 'Y'
                            and a.client_code          like '$client_code'
                            and a.matter_code          like '$matter_code' 
                            and a.client_code             = c.client_code
                            and a.matter_code             = b.matter_code
                            and b.court_code           like '$court_code' 
                            and b.court_code              = d.code_code  and d.type_code = '001' 
                            and b.initial_code         like '$initial_code'
                            and (a.ref_billinfo_serial_no is null or a.ref_billinfo_serial_no = 0)
                            and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                        order by a.activity_date desc,a.matter_code desc,a.serial_no desc " ;

                        $report_desc   = 'LIST OF UNBILLED CASES DURING A PERIOD [ACTIVITY DATE-WISE]' ;
                        break;

                    case '2' : 
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, d.code_desc court_name,
                        e.case_no, e.subject_desc, e.ref_billinfo_serial_no    
                        from fileinfo_header b, client_master c, code_master d, case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                        where a.branch_code          like '$branch_code' 
                            and a.activity_date          between '$start_date_ymd'  and '$end_date_ymd'   
                            and a.billable_option         = 'Y'
                            and a.status_code             = 'A'
                            and a.client_code          like '$client_code'
                            and a.matter_code          like '$matter_code' 
                            and a.client_code             = c.client_code
                            and a.matter_code             = b.matter_code
                            and b.court_code           like '$court_code' 
                            and b.court_code              = d.code_code  and d.type_code = '001' 
                            and b.initial_code         like '$initial_code'
                            and (a.ref_billinfo_serial_no is null)     
                            and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                        order by a.client_code,a.matter_code,a.activity_date,a.serial_no " ;

                        $report_desc   = 'LIST OF UNBILLED CASES DURING A PERIOD [MATTER/ACTIVITY DATE-WISE]' ;
                        break;

                    case '3' : 
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, b.initial_code, d.code_desc court_name,
                        e.case_no, e.subject_desc, e.ref_billinfo_serial_no    
                        from fileinfo_header b, client_master c, code_master d, case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                        where a.branch_code          like '$branch_code' 
                            and a.activity_date         between '$start_date_ymd'  and '$end_date_ymd'
                            and a.billable_option         = 'Y'
                            and a.status_code             = 'A'              
                            and a.client_code          like '$client_code'
                            and a.matter_code          like '$matter_code' 
                            and a.client_code             = c.client_code
                            and a.matter_code             = b.matter_code
                            and b.court_code           like '$court_code' 
                            and b.court_code              = d.code_code  and d.type_code = '001'
                            and b.initial_code         like '$initial_code'
                            and (a.ref_billinfo_serial_no is null)
                            and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                        order by a.serial_no desc,a.matter_code,a.client_code,a.activity_date" ;

                        $report_desc   = 'LIST OF UNBILLED CASES DURING A PERIOD [STATUS SERIAL NO./MATTER-WISE/INITIAL-WISE]' ;
                        break;

                    case '4' : 
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, b.initial_code, d.code_desc court_name,
                        e.case_no, e.subject_desc, e.ref_billinfo_serial_no    
                        from fileinfo_header b, client_master c, code_master d, case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                        where a.branch_code          like '$branch_code' 
                            and a.activity_date         between '$start_date_ymd'  and '$end_date_ymd'
                            and a.billable_option         = 'Y'
                            and a.status_code             = 'A'              
                            and a.client_code          like '$client_code'
                            and a.matter_code          like '$matter_code' 
                            and a.client_code             = c.client_code
                            and a.matter_code             = b.matter_code
                            and b.court_code           like '$court_code' 
                            and b.court_code              = d.code_code  and d.type_code = '001'
                            and b.initial_code         like '$initial_code'
                            and (a.ref_billinfo_serial_no is null)
                            and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                        order by court_name, b.initial_code, a.activity_date DESC " ;

                        $report_desc   = 'LIST OF UNBILLED CASES DURING A PERIOD [COURT/INITIAL-WISE]' ;
                        break;
                }
                
                $reports  = $this->db->query($case_sql)->getResultArray();
                // echo "<pre>"; print_r($reports); die;
                $case_cnt  = count($reports);
                $date = date('d-m-Y');

                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                
                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "period_desc" => $period_desc,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    "matter_code" => $matter_code,
                    "matter_desc" => $matter_desc,
                    "court_code" => $court_code,
                    "court_name" => $court_name,
                    "initial_code" => $initial_code,
                    "initial_name" => $initial_name,
                    "report_seq" => $report_seq,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                ];
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/CaseDetails/list_of_unbilled_case_status",  compact("reports","params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/CaseDetails/list_of_unbilled_case_status",  compact("reports","params"));
                
            } else if($output_type == 'Excel') {
                $case_sql = "";
                switch($report_seq) {
                    case '1' : 
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, d.code_desc court_name,
                        e.case_no, e.subject_desc    
                        from fileinfo_header b, client_master c, code_master d, case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                        where a.branch_code          like '$branch_code' 
                            and a.activity_date          between '$start_date_ymd'  and '$end_date_ymd'  
                            and a.status_code             = 'A'
                            and a.billable_option         = 'Y'
                            and a.client_code          like '$client_code'
                            and a.matter_code          like '$matter_code' 
                            and a.client_code             = c.client_code
                            and a.matter_code             = b.matter_code
                            and b.court_code           like '$court_code' 
                            and b.court_code              = d.code_code  and d.type_code = '001' 
                            and b.initial_code         like '$initial_code'
                            and (a.ref_billinfo_serial_no is null or a.ref_billinfo_serial_no = 0)
                            and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                        order by a.activity_date desc,a.matter_code desc,a.serial_no desc " ;

                        $report_desc   = 'LIST OF UNBILLED CASES DURING A PERIOD [ACTIVITY DATE-WISE]' ;
                        break;

                    case '2' : 
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, d.code_desc court_name,
                        e.case_no, e.subject_desc, e.ref_billinfo_serial_no    
                        from fileinfo_header b, client_master c, code_master d, case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                        where a.branch_code          like '$branch_code' 
                            and a.activity_date          between '$start_date_ymd'  and '$end_date_ymd'   
                            and a.billable_option         = 'Y'
                            and a.status_code             = 'A'
                            and a.client_code          like '$client_code'
                            and a.matter_code          like '$matter_code' 
                            and a.client_code             = c.client_code
                            and a.matter_code             = b.matter_code
                            and b.court_code           like '$court_code' 
                            and b.court_code              = d.code_code  and d.type_code = '001' 
                            and b.initial_code         like '$initial_code'
                            and (a.ref_billinfo_serial_no is null)     
                            and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                        order by a.client_code,a.matter_code,a.activity_date,a.serial_no " ;

                        $report_desc   = 'LIST OF UNBILLED CASES DURING A PERIOD [MATTER/ACTIVITY DATE-WISE]' ;
                        break;

                    case '3' : 
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, b.initial_code, d.code_desc court_name,
                        e.case_no, e.subject_desc, e.ref_billinfo_serial_no    
                        from fileinfo_header b, client_master c, code_master d, case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                        where a.branch_code          like '$branch_code' 
                            and a.activity_date         between '$start_date_ymd'  and '$end_date_ymd'
                            and a.billable_option         = 'Y'
                            and a.status_code             = 'A'              
                            and a.client_code          like '$client_code'
                            and a.matter_code          like '$matter_code' 
                            and a.client_code             = c.client_code
                            and a.matter_code             = b.matter_code
                            and b.court_code           like '$court_code' 
                            and b.court_code              = d.code_code  and d.type_code = '001'
                            and b.initial_code         like '$initial_code'
                            and (a.ref_billinfo_serial_no is null)
                            and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                        order by a.serial_no desc,a.matter_code,a.client_code,a.activity_date" ;

                        $report_desc   = 'LIST OF UNBILLED CASES DURING A PERIOD [STATUS SERIAL NO./MATTER-WISE/INITIAL-WISE]' ;
                        break;

                    case '4' : 
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, b.initial_code, d.code_desc court_name,
                        e.case_no, e.subject_desc, e.ref_billinfo_serial_no    
                        from fileinfo_header b, client_master c, code_master d, case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                        where a.branch_code          like '$branch_code' 
                            and a.activity_date         between '$start_date_ymd'  and '$end_date_ymd'
                            and a.billable_option         = 'Y'
                            and a.status_code             = 'A'              
                            and a.client_code          like '$client_code'
                            and a.matter_code          like '$matter_code' 
                            and a.client_code             = c.client_code
                            and a.matter_code             = b.matter_code
                            and b.court_code           like '$court_code' 
                            and b.court_code              = d.code_code  and d.type_code = '001'
                            and b.initial_code         like '$initial_code'
                            and (a.ref_billinfo_serial_no is null)
                            and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                        order by court_name, b.initial_code, a.activity_date DESC " ;

                        $report_desc   = 'LIST OF UNBILLED CASES DURING A PERIOD [COURT/INITIAL-WISE]' ;
                        break;
                }
                
                $reports = $this->db->query($case_sql)->getResultArray();
                $case_cnt = count($reports);
                $date = date('d-m-Y');

                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                
                $fileName = 'List-of-Unbilled-Case-Status-'.date('d-m-Y').'.xlsx';  
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Define the headings
                if ($report_seq == '1') {
                    $headings = ['SL', 'RecSrl', 'Date', 'Matter', 'Client/Matter Description', 'Court', 'Letter Ref'];
                } else if($report_seq == '2') { 
                    $headings = ['SL', 'RecSrl', 'Date', 'Matter', 'Matter Description', 'Court', 'Letter Ref'];
                } else if($report_seq == '3') {
                    $headings = ['SL', 'RecSrl', 'Date', 'Matter', 'Initial', 'Matter Description', 'Court', 'Letter Ref'];
                } else {
                    $headings = ['SL', 'RecSrl', 'Date', 'Matter', 'Initial', 'Matter Description', 'Court', 'Letter Ref'];
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
                foreach ($reports as $key => $report) {
                    $last_col = '';
                    if ($report_seq == '1') {
                        $sheet->setCellValue('A' . $rows, $key+1);
                        $sheet->setCellValue('B' . $rows, $report['serial_no']);
                        $sheet->setCellValue('C' . $rows, date_conv($report['activity_date'],'-'));
                        $sheet->setCellValue('D' . $rows, $report['matter_code']);
                        $sheet->setCellValue('E' . $rows, $report['client_name']);
                        $sheet->setCellValue('F' . $rows, $report['court_name']);
                        $sheet->setCellValue('G' . $rows, $report['letter_no']);
                        $last_col = 'G';
                        
                    } else if($report_seq == '2') { 
                        $sheet->setCellValue('A' . $rows, $key+1);
                        $sheet->setCellValue('B' . $rows, $report['serial_no']);
                        $sheet->setCellValue('C' . $rows, date_conv($report['activity_date'],'-'));
                        $sheet->setCellValue('D' . $rows, $report['matter_code']);
                        $sheet->setCellValue('E' . $rows, $report['matter_desc']);
                        $sheet->setCellValue('F' . $rows, $report['court_name']);
                        $sheet->setCellValue('G' . $rows, $report['letter_no']);
                        $last_col = 'G';
                        
                    } else if($report_seq == '3') {
                        $sheet->setCellValue('A' . $rows, $key+1);
                        $sheet->setCellValue('B' . $rows, $report['serial_no']);
                        $sheet->setCellValue('C' . $rows, date_conv($report['activity_date'],'-'));
                        $sheet->setCellValue('D' . $rows, $report['matter_code']);
                        $sheet->setCellValue('E' . $rows, $report['initial_code']);
                        $sheet->setCellValue('F' . $rows, $report['matter_desc']);
                        $sheet->setCellValue('G' . $rows, $report['court_name']);
                        $sheet->setCellValue('H' . $rows, $report['letter_no']);
                        $last_col = 'H';
                        
                    } else {
                        $sheet->setCellValue('A' . $rows, $key+1);
                        $sheet->setCellValue('B' . $rows, $report['serial_no']);
                        $sheet->setCellValue('C' . $rows, date_conv($report['activity_date'],'-'));
                        $sheet->setCellValue('D' . $rows, $report['matter_code']);
                        $sheet->setCellValue('E' . $rows, $report['initial_code']);
                        $sheet->setCellValue('F' . $rows, $report['matter_desc']);
                        $sheet->setCellValue('G' . $rows, $report['court_name']);
                        $sheet->setCellValue('H' . $rows, $report['letter_no']);
                        $last_col = 'H';
                    }
                    
                    // Apply border to the current row
                    $style = $sheet->getStyle('A' . $rows . ':'. $last_col . $rows);
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
        } else {
            $user_id = session()->userId;
            $data = branches($user_id);
            $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220' , 'court_help_id' => '4221' , 'initial_help_id' => '4191'] ; 
        
            return view("pages/CaseDetails/list_of_unbilled_case_status",  compact("data", "displayId"));
        }
    }
    public function list_of_case_status() {
        if($this->request->getMethod() == 'post') {
            $requested_url = base_url($_SERVER['REQUEST_URI']);
            $display_id        = isset($_REQUEST['display_id'])  ?$_REQUEST['display_id']  :null;
            $param_id          = isset($_REQUEST['param_id'])    ?$_REQUEST['param_id']    :null;
            $my_menuid         = isset($_REQUEST['my_menuid'])   ?$_REQUEST['my_menuid']   :null;
            // $my_menuid         = "350205";
            $menu_id           = isset($_REQUEST['menu_id'])     ?$_REQUEST['menu_id']     :null;	
            // $menu_id           = "350205";	
            $user_option       = isset($_REQUEST['user_option']) ?$_REQUEST['user_option'] :null;
            $screen_ref        = isset($_REQUEST['screen_ref'])  ?$_REQUEST['screen_ref']  :null;
            $index             = isset($_REQUEST['index'])       ?$_REQUEST['index']       :null;
            $ord               = isset($_REQUEST['ord'])         ?$_REQUEST['ord']         :null;
            $pg                = isset($_REQUEST['pg'])          ?$_REQUEST['pg']          :null;
            $search_val        = isset($_REQUEST['search_val'])  ?$_REQUEST['search_val']  :null;

            //-------
            $start_date    =  $_REQUEST['start_date'] ;     //'21-11-2003' ;
            if($start_date != '') { $start_date_ymd  = date_conv($start_date,'-') ; } else { $start_date_ymd = '0000-00-00' ; } 
            $end_date      = $_REQUEST['end_date'] ;   //'05-02-2004' ;      
            $end_date_ymd    = date_conv($end_date,'-') ;
            $branch_code   = $_REQUEST['branch_code'] ;
            //$branch_code   = "B001" ;
            $client_code   = $_REQUEST['client_code'] ;    
            if($client_code == '') { $client_code = '%' ; }
            $client_name   = $_REQUEST['client_name'] ;   
            $matter_code   = $_REQUEST['matter_code'] ;    
            if($matter_code == '') { $matter_code = '%' ; }
            $matter_desc   = $_REQUEST['matter_desc'] ;   
            $court_code    = $_REQUEST['court_code'] ;     
            if($court_code  == '') { $court_code  = '%' ; }
            $initial_code  = $_REQUEST['initial_code'] ;   
            if($initial_code  == '') { $initial_code  = '%' ; }
            $initial_name  = $_REQUEST['initial_name'] ;  
            $court_name    = $_REQUEST['court_name'] ;   
            $bill_optn     =  $_REQUEST['billing_option'] ;
            $report_seq    = $_REQUEST['report_seq'] ;
            $client_name   = str_replace('_|_','&', $client_name) ;
            $client_name   = str_replace('-|-',"'", $client_name) ;
            $output_type    = $_REQUEST['output_type'] ;

            if($start_date != '') { $period_desc   = $start_date.' TO '.$end_date ; } else { $period_desc   = 'UPTO '.$end_date ; }

            if($bill_optn == 'P') { $billoptn_desc = 'PRE-BILLABLE' ; } else { $billoptn_desc = 'NON-BILLABLE' ; }

            $report_desc   = 'LIST OF CASE STATUS : '.strtoupper($billoptn_desc).' : AS ON DATE [ACTIVITY DATE-WISE]' ;

            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0] ;
            
            $branch_name   = $branch_qry['branch_name'] ;
            
            if($output_type == 'Report' || $output_type == 'Pdf'){

                $case_sql = '';
                switch($report_seq) {
                    case '1':  
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, b.initial_code, c.client_name, d.code_desc court_name, e.case_no, e.subject_desc    
                                from fileinfo_header b, client_master c, code_master d, case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                                where a.branch_code like '$branch_code' 
                                and a.activity_date between '$start_date_ymd' and '$end_date_ymd'  
                                and a.status_code = 'A'
                                and a.billable_option like '$bill_optn'
                                and a.client_code like '$client_code'
                                and a.matter_code like '$matter_code' 
                                and b.initial_code like '$initial_code'
                                and a.client_code = c.client_code
                                and a.matter_code = b.matter_code
                                and b.court_code like '$court_code' 
                                and b.court_code = d.code_code  and d.type_code = '001'     
                                and (a.ref_billinfo_serial_no is null or a.ref_billinfo_serial_no = 0)
                                and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                                order by a.activity_date desc,a.matter_code desc,a.serial_no desc " ; 
                        break;

                    case '2':  
                        $case_sql = "select a.*, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, b.initial_code, c.client_name, d.code_desc court_name, e.case_no, e.subject_desc, e.ref_billinfo_serial_no from fileinfo_header b, client_master c, code_master d, 
                            case_header a left outer join case_detail_other_case e on e.ref_case_header_serial = a.serial_no
                            where a.branch_code like '$branch_code' 
                            and a.activity_date between '$start_date_ymd' and '$end_date_ymd'  
                            and a.billable_option like '$bill_optn'
                            and a.status_code = 'A'
                            and a.client_code like '$client_code'
                            and a.matter_code like '$matter_code' 
                            and b.initial_code like '$initial_code'
                            and a.client_code = c.client_code
                            and a.matter_code = b.matter_code
                            and b.court_code like '$court_code' 
                            and b.court_code = d.code_code  and d.type_code = '001'     
                            and (e.ref_billinfo_serial_no is null or e.ref_billinfo_serial_no = 0)
                            order by a.client_code,a.matter_code,a.activity_date,a.serial_no " ;
                        break;
                }

                $reports  = $this->db->query($case_sql)->getResultArray();
                // echo '<pre>';print_r($reports);die;
                $case_cnt  = count($reports);
                $date = date('d-m-Y');

               if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "period_desc" => $period_desc,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    "matter_code" => $matter_code,
                    "matter_desc" => $matter_desc,
                    "court_code" => $court_code,
                    "court_name" => $court_name,
                    "initial_code" => $initial_code,
                    "initial_name" => $initial_name,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                    "report_seq" => $report_seq
                ];
                
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/CaseDetails/list_of_case_status",  compact("reports","params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/CaseDetails/list_of_case_status",  compact("reports","params"));
            }
        } else {
            $user_id = session()->userId;
            $data = branches($user_id);
            $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220' , 'court_help_id' => '4221' , 'initial_help_id' => '4191'] ; 
        
            return view("pages/CaseDetails/list_of_case_status",  compact("data", "displayId"));
        }
    }
    public function cases_appeared_prepare_date() {
        if($this->request->getMethod() == 'post') {
            $requested_url = base_url($_SERVER['REQUEST_URI']);
            $display_id        = isset($_REQUEST['display_id'])  ?$_REQUEST['display_id']  :null;
            $param_id          = isset($_REQUEST['param_id'])    ?$_REQUEST['param_id']    :null;
            $my_menuid         = isset($_REQUEST['my_menuid'])   ?$_REQUEST['my_menuid']   :null;
            // $my_menuid         = "350206";
            $menu_id           = isset($_REQUEST['menu_id'])     ?$_REQUEST['menu_id']     :null;	
            // $menu_id           = "350206";	
            $user_option       = isset($_REQUEST['user_option']) ?$_REQUEST['user_option'] :null;
            $screen_ref        = isset($_REQUEST['screen_ref'])  ?$_REQUEST['screen_ref']  :null;
            $index             = isset($_REQUEST['index'])       ?$_REQUEST['index']       :null;
            $ord               = isset($_REQUEST['ord'])         ?$_REQUEST['ord']         :null;
            $pg                = isset($_REQUEST['pg'])          ?$_REQUEST['pg']          :null;
            $search_val        = isset($_REQUEST['search_val'])  ?$_REQUEST['search_val']  :null;
            
            //-------
            $branch_code   = $_REQUEST['branch_code'] ;
            $start_date    = $_REQUEST['start_date'] ;   
            if($start_date != '') {$start_date_ymd = date_conv($start_date); } else { $start_date_ymd = '1901-01-01' ; }
            $end_date      = $_REQUEST['end_date'] ;     
            $end_date_ymd   = date_conv($end_date) ;  
            $client_code   = $_REQUEST['client_code'] ;  
            if($client_code == '') { $client_code = '%' ; }
            $matter_code   = $_REQUEST['matter_code'] ;  
            if($matter_code == '') { $matter_code = '%' ; }
            $matter_desc   = $_REQUEST['matter_desc'] ;   
            $court_code    = $_REQUEST['court_code'] ;   
            if($court_code  == '') { $court_code  = '%' ; }
            $court_name    = $_REQUEST['court_name'] ;   
            $desc_ind      = $_REQUEST['desc_ind'] ;
            $report_seq    = $_REQUEST['report_seq'] ;
            $forward_inp   = $_REQUEST['forward_inp'] ;
            $output_type   = $_REQUEST['output_type'] ;


            if($start_date != '') { $period_desc   = $start_date.' TO '.$end_date ; } else { $period_desc   = 'UPTO '.$end_date ; }

            if($client_code == '')
            {
            $client_name = '';
            }
            else if($client_code == '%')
            {
            $client_name = 'All';
            }
            else
            {
            $client_qry    = $this->db->query("select client_name from client_master where client_code = '$client_code' ")->getRowArray() ;
            $client_name   = $client_qry['client_name'] ;
            }

            if($forward_inp == 'Y')
            {
                $forwarding_ind = 'Yes';
            }
            else if($forward_inp == 'N')
            {
                $forwarding_ind = 'No';
            }
            else
            {
                $forward_inp = '%';
                $forwarding_ind = 'All';
            }
        
            $branch_qry = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0] ;
            
            $branch_name = $branch_qry['branch_name'] ;

            $case_sql = ''; $report_desc = '';
            if($output_type == 'Report' || $output_type == 'Pdf'){
                switch($report_seq) {
                    case '1':  
                        $case_sql = "select a.activity_date,a.client_code,a.matter_code,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for,a.prev_fixed_for,b.reference_desc,b.court_code,b.date_of_filing,b.stake_amount,concat(b.matter_desc1,' ',b.matter_desc2) matter_desc,c.client_name,d.code_desc court_name,b.matter_desc1,b.matter_desc2,a.serial_no 
                            from fileinfo_header b,client_master c,code_master d,case_header a 
                            where a.activity_date between '$start_date_ymd' 
                            and '$end_date_ymd' 
                            and a.client_code like '$client_code'
                            and a.matter_code like '$matter_code' 
                            and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                            and a.client_code  = c.client_code 
                            and a.matter_code = b.matter_code 
                            and b.court_code like '$court_code' 
                            and b.court_code = d.code_code 
                            and d.type_code  = '001'  
                            order by a.activity_date,d.code_desc,a.client_code,a.matter_code limit 40" ;
                        $report_desc   = 'CASES APPEARED DURING A PERIOD [ACTIVITY DATE-WISE]' ;
                        break;
                    case '2':    
                        $case_sql = "select a.activity_date,a.client_code,a.matter_code,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for, a.prev_fixed_for, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, c.client_name, d.code_desc, b.matter_desc1, b.matter_desc2    
                            from fileinfo_header b, client_master c, code_master d, 
                            case_header a 
                            where a.activity_date between '$start_date_ymd'  and '$end_date_ymd'  
                            and a.client_code like '$client_code'
                            and a.matter_code  like '$matter_code' 
                            and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                            and a.client_code = c.client_code
                            and a.matter_code = b.matter_code
                            and b.court_code like '$court_code' 
                            and b.court_code = d.code_code 
                            and d.type_code = '001'     
                            order by a.matter_code,a.activity_date limit 40" ;
                        $report_desc   = 'CASES APPEARED DURING A PERIOD [MATTER/ACTIVITY DATE-WISE]' ;
                        break;
                }

                $reports  = $this->db->query($case_sql)->getResultArray();
                $case_cnt  = count($reports);
                $date = date('d-m-Y');
                
               if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }

                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "period_desc" => $period_desc,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    "matter_code" => $matter_code,
                    "matter_desc" => $matter_desc,
                    "court_code" => $court_code,
                    "court_name" => $court_name,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                    "report_seq" => $report_seq,
                    "forwarding_ind" => $forwarding_ind,
                    "desc_ind" => $desc_ind,
                ];
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/CaseDetails/cases_appeared_prepare_date",  compact("reports","params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/CaseDetails/cases_appeared_prepare_date",  compact("reports","params"));

            }else if($output_type == 'Excel'){

                switch($report_seq) {
                    case '1':  
                        $case_sql = "select a.activity_date,a.serial_no,a.client_code,a.prepared_on,a.matter_code,a.remarks,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for,
                            a.prev_fixed_for, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, 
                            c.client_name, d.code_desc court_name, b.matter_desc1, b.matter_desc2   
                            from fileinfo_header b, client_master c, code_master d, case_header a 
                            where a.prepared_on between '$start_date_ymd'  and '$end_date_ymd'  
                            and a.client_code like '$client_code'
                            and a.matter_code like '$matter_code' 
                            and a.client_code = c.client_code
                            and a.matter_code = b.matter_code
                            and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                            and b.court_code like '$court_code' 
                            and b.court_code = d.code_code 
                            and d.type_code = '001'     
                            order by a.activity_date,d.code_desc,a.client_code,a.matter_code " ;
                        break;
                    case '2':    
                        $case_sql = "select distinct a.activity_date,a.serial_no,a.client_code,a.prepared_on,a.matter_code,a.judge_name,a.next_date,a.prev_date,a.header_desc,a.next_fixed_for,
                            a.prev_fixed_for, b.reference_desc, b.court_code, b.date_of_filing, b.stake_amount, concat(b.matter_desc1,' ',b.matter_desc2) matter_desc, 
                            c.client_name, d.code_desc, b.matter_desc1, b.matter_desc2    
                            from fileinfo_header b, client_master c, code_master d, case_header a 
                            where a.prepared_on between '$start_date_ymd'  and '$end_date_ymd'  
                            and a.client_code like '$client_code'
                            and a.matter_code like '$matter_code' 
                            and a.client_code = c.client_code
                            and a.matter_code = b.matter_code
                            and ifnull(a.forwarding_ind,'N') like '$forward_inp'
                            and b.status_code = 'A' 
                            and b.court_code like '$court_code' 
                            and b.court_code = d.code_code 
                            and d.type_code = '001'     
                            order by a.matter_code,a.activity_date" ;
                        break;
                }
    
                $excels  = $this->db->query($case_sql)->getResultArray();
                $case_cnt  = count($excels);
    
                try {
                    $excels[0];
                    if($case_cnt == 0)  throw new \Exception('No Records Found !!');
    
                } catch (\Exception $e) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($_SERVER['REQUEST_URI']);
                }
    
                $fileName = 'CASES_APPEARED_PREPARE_DATE-'.date('d-m-Y').'.xlsx';    
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $headings = ['Activity Date', 'Record No', 'Client', 'Matter', 'Case No.', 'Matter Description', 'Court', 'Judge', 'Reference', 'Fix For (Day)', 'Next Date', 'Fix For (Next)', 'Previous Date', 'Fix For (Prev)', 'Filing Date', 'Amount', 'Forwarding', 'Prepare Date'];

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
                
                foreach ($excels as $excel){
    
                    $day_fixed_for = get_fixed_for($excel['matter_code'], $excel['activity_date']);
                    if($excel['stake_amount'] > 0) { 
                        $stake_amount = $excel['stake_amount'];
                    }
    
                    $sheet->setCellValue('A' . $rows, date_conv($excel['activity_date']));
                    $sheet->setCellValue('B' . $rows, strtoupper($excel['serial_no']));
                    $sheet->setCellValue('C' . $rows, strtoupper($excel['client_name']));
                    $sheet->setCellValue('D' . $rows, strtoupper($excel['matter_code']));
                    $sheet->setCellValue('E' . $rows, strtoupper($excel['matter_desc1']));
                    $sheet->setCellValue('F' . $rows, strtoupper($excel['matter_desc2']));
                    $sheet->setCellValue('G' . $rows, strtoupper(isset($excel['court_name']) ? $excel['court_name'] : ''));
                    $sheet->setCellValue('H' . $rows, strtoupper($excel['judge_name']));
                    $sheet->setCellValue('I' . $rows, strtoupper($excel['reference_desc']));
                    $sheet->setCellValue('J' . $rows, strtoupper($day_fixed_for));
                    $sheet->setCellValue('K' . $rows, date_conv($excel['next_date']));
                    $sheet->setCellValue('L' . $rows, strtoupper($excel['next_fixed_for']));
                    $sheet->setCellValue('M' . $rows, date_conv($excel['prev_date']));
                    $sheet->setCellValue('N' . $rows, strtoupper($excel['prev_fixed_for']));
                    $sheet->setCellValue('O' . $rows, date_conv($excel['date_of_filing']));
                    $sheet->setCellValue('P' . $rows, isset($stake_amount) ? $stake_amount : '');
                    $sheet->setCellValue('Q' . $rows, $forwarding_ind);
                    $sheet->setCellValue('R' . $rows, date_conv($excel['prepared_on']));
    
                    $style = $sheet->getStyle('A' . $rows . ':R' . $rows);
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
        } else {
            $user_id = session()->userId;
            $data = branches($user_id);
            $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4220' , 'court_help_id' => '4221'] ; 

            return view("pages/CaseDetails/cases_appeared_prepare_date",  compact("data", "displayId"));
        }
    }
    public function status_of_matters() {
        $user_id = session()->userId;
     	$fin_year = session()->financialYear;
        $data = branches($user_id);
        $curr_fyrsdt = '01-04-'.substr($fin_year,0,4);
        $displayId   = ['client_help_id' => '4072', 'matter_help_id' => '4219' , 'court_help_id' => '4221', 'status_help_id'=> '4192', 'initial_help_id'=>'4191'] ; 

        if($this->request->getMethod() == 'post') {
            $requested_url = base_url($_SERVER['REQUEST_URI']);
            $display_id    = isset($_REQUEST['display_id'])?$_REQUEST['display_id']:null;
            $param_id      = isset($_REQUEST['param_id'])?$_REQUEST['param_id']:null;
            $my_menuid     = isset($_REQUEST['my_menuid'])?$_REQUEST['my_menuid']:null;
            // $my_menuid     = '350207';
            $menu_id       = isset($_REQUEST['menu_id'])?$_REQUEST['menu_id']:null;	
            // $menu_id       = '350207';
            $user_option   = isset($_REQUEST['user_option'])?$_REQUEST['user_option']:null;
            $screen_ref    = isset($_REQUEST['screen_ref'])?$_REQUEST['screen_ref']:null;
            $index         = isset($_REQUEST['index'])?$_REQUEST['index']:null;
            $ord           = isset($_REQUEST['ord'])?$_REQUEST['ord']:null;
            $pg            = isset($_REQUEST['pg'])?$_REQUEST['pg']:null;
            $search_val    = isset($_REQUEST['search_val'])?$_REQUEST['search_val']:null;
            $report_desc   = 'MATTER STATUS INFORMATION' ;

            //-------
            $branch_code   = $_REQUEST['branch_code'] ;
            // $branch_code   = "B001" ;
            $start_date    = $_REQUEST['start_date'] ;     
            if($start_date != '') { $start_date_ymd  = date_conv($start_date,'-') ; } else { $start_date_ymd = '0000-00-00' ; } 
            $end_date      = $_REQUEST['end_date'] ;      
            $end_date_ymd    = date_conv($end_date,'-') ;  
            $client_code   = $_REQUEST['client_code'] ;    
            if($client_code == '') { $client_code = '%' ; }
            $client_name   = $_REQUEST['client_name'] ;
            //$client_name   = myStringReplace($client_name,'_|_','&') ;
            //$client_name   = myStringReplace($client_name,'-|-',"'") ; 
            $matter_code   = $_REQUEST['matter_code'] ;    
            if($matter_code == '') { $matter_code = '%' ; }
            $matter_desc   = $_REQUEST['matter_desc'] ;   
            $court_code    = $_REQUEST['court_code'] ;     
            if($court_code  == '') { $court_code  = '%' ; }
            $court_name    = $_REQUEST['court_name'] ;
            $initial_code  = $_REQUEST['initial_code'] ;     
            if($initial_code  == '') { $initial_code  = '%' ; }
            $initial_name  = $_REQUEST['initial_name'] ;   
            $status_code   = $_REQUEST['status_code'] ;     
            if($status_code  == '') { $status_code  = '%' ; }
            $status_desc   = $_REQUEST['status_desc'] ; 
            $output_type     = $_REQUEST['output_type'] ;  

            //$opened_by     = $_REQUEST['opened_by'] ;
            if($start_date != '') { $period_desc   = $start_date.' TO '.$end_date ; } else { $period_desc   = 'UPTO '.$end_date ; }
            //
            $branch_qry    = $this->db->query("select branch_name from branch_master where branch_code = '$branch_code' ")->getResultArray()[0]; ;
            
            $branch_name   = $branch_qry["branch_name"] ;
            
            if($output_type == 'Report' || $output_type == 'Pdf') {
                $matrinfo_sql = "select a.*, b.client_name, c.code_desc court_name, g.initial_name, j.status_desc, d.code_desc appearing_for_name, e.code_desc reference_type_name
                from client_master b, code_master c, code_master d, fileinfo_header a left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'   
                left outer join initial_master g on g.initial_code = a.initial_code 
                left outer join status_master j on j.status_code = a.status_code and j.table_name = 'fileinfo_header' 
                WHERE a.prepared_on between '$start_date_ymd' and '$end_date_ymd' 
                and a.client_code like '$client_code'
                and a.matter_code like '$matter_code'
                and a.court_code like '$court_code'
                and a.initial_code like '$initial_code'
                and a.status_code like '$status_code'
                and a.client_code = b.client_code
                and a.court_code = c.code_code  and c.type_code = '001'     
                and a.appearing_for_code = d.code_code  and d.type_code = '004' 
                and a.initial_code = g.initial_code
                order by a.matter_code desc " ;
                $reports  = $this->db->query($matrinfo_sql)->getResultArray();
                $matrinfo_cnt  = count($reports);
                $date = date('d-m-Y');

                if(empty($reports)) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($this->requested_url());
                }
                
                $params = [
                    "branch_name" => $branch_name,
                    "report_desc" => $report_desc,
                    "period_desc" => $period_desc,
                    "client_code" => $client_code,
                    "client_name" => $client_name,
                    "matter_code" => $matter_code,
                    "matter_desc" => $matter_desc,
                    "court_code" => $court_code,
                    "court_name" => $court_name,
                    "initial_code" => $initial_code,
                    "initial_name" => $initial_name,
                    "date" => $date,
                    "requested_url" => $this->requested_url(),
                    "status_code" => $status_code,
                    "status_desc" => $status_desc,
                    //"opened_by" => $opened_by,
                ];
                if ($output_type == 'Pdf') {
                    $report_type = 'Pdf'; $dompdf = new \Dompdf\Dompdf(); 
                    $reportHTML = view("pages/CaseDetails/status_of_matters",  compact("reports","params", 'report_type'));
                    $dompdf->loadHtml($reportHTML);
                    $dompdf->setPaper('A4', 'landscape'); // portrait
                    $dompdf->render();
                    $dompdf->stream('abc.pdf', array('Attachment'=>0)); exit;
                } else return view("pages/CaseDetails/status_of_matters",  compact("reports","params"));
            }else if($output_type == 'Excel'){
            
                $matrinfo_sql = "select a.*, b.client_name, c.code_desc court_name, g.initial_name, j.status_desc, d.code_desc appearing_for_name, e.code_desc reference_type_name
                        from client_master b, code_master c, code_master d, fileinfo_header a left outer join code_master e on e.code_code = a.reference_type_code and e.type_code = '007'   
                        left outer join initial_master g on g.initial_code = a.initial_code 
                        left outer join status_master j on j.status_code = a.status_code and j.table_name = 'fileinfo_header' 
                        WHERE a.prepared_on between '$start_date_ymd' and '$end_date_ymd' 
                        and a.client_code like '$client_code'
                        and a.matter_code like '$matter_code'
                        and a.court_code like '$court_code'
                        and a.initial_code like '$initial_code'
                        and a.status_code like '$status_code'
                        and a.client_code = b.client_code
                        and a.court_code = c.code_code  and c.type_code = '001'     
                        and a.appearing_for_code = d.code_code  and d.type_code = '004' 
                        and a.initial_code = g.initial_code
                        order by a.matter_code desc " ;
        
                $excels  = $this->db->query($matrinfo_sql)->getResultArray();
                // echo "<pre>"; print_r($excels); die;
                $matrinfo_cnt  = count($excels);
    
                try {
                    $excels[0];
                    if($matrinfo_cnt == 0)  throw new \Exception('No Records Found !!');
    
                } catch (\Exception $e) {
                    session()->setFlashdata('message', 'No Records Found !!');
                    return redirect()->to($_SERVER['REQUEST_URI']);
                }
    
                $fileName = 'STATUS_OF_MATTERS-'.date('d-m-Y').'.xlsx';    
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $headings = ['Client', 'Initial Name', 'Court', 'Matter', 'Case No', 'Matter Description', 'Notice No', 'Notice Date', 'Appear For', 'Filing Date', 'Reference Type', 'Reference No', 'Status', 'Amount'];

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
                
                foreach ($excels as $excel){
    
                    $sheet->setCellValue('A' . $rows, strtoupper($excel['client_name']));
                    $sheet->setCellValue('B' . $rows, strtoupper($excel['initial_name']));
                    $sheet->setCellValue('C' . $rows, strtoupper($excel['court_name']));
                    $sheet->setCellValue('D' . $rows, $excel['matter_code']);
                    $sheet->setCellValue('E' . $rows, strtoupper($excel['matter_desc1']));
                    $sheet->setCellValue('F' . $rows, strtoupper($excel['matter_desc2']));
                    $sheet->setCellValue('G' . $rows, $excel['notice_no']);
                    $sheet->setCellValue('H' . $rows, date_conv($excel['notice_date']));
                    $sheet->setCellValue('I' . $rows, strtoupper($excel['appearing_for_name']));
                    $sheet->setCellValue('J' . $rows, date_conv($excel['date_of_filing']));
                    $sheet->setCellValue('K' . $rows, strtoupper($excel['reference_type_name']));
                    $sheet->setCellValue('L' . $rows, strtoupper($excel['reference_desc']));
                    $sheet->setCellValue('M' . $rows, strtoupper($excel['status_desc']));
                    $sheet->setCellValue('N' . $rows, strtoupper($excel['stake_amount']));
    
                    $style = $sheet->getStyle('A' . $rows . ':N' . $rows);
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
        } else {
            return view("pages/CaseDetails/status_of_matters",  compact("data", "displayId", "curr_fyrsdt"));
        }
        
    }
}