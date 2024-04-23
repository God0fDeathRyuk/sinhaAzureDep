<?php



namespace App\Controllers;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class QueryController extends BaseController

{

    public function __construct() {

        $this->db = \config\database::connect();
        $temp_db = $this->temp_db = db_connect('temp');

        $this->session = session();

    }

 
    public function query_details($option = 'list')
    { 
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        $query_id = isset($_REQUEST['query_id']) ? $_REQUEST['query_id'] : null;
        $search_val = isset($_REQUEST['search_val']) ? $_REQUEST['search_val'] : null;
        //echo $this->request->getMethod();die;
           if($query_id!='qry_0002')
           {
            if ($this->request->getMethod() == 'post') 
            {
                switch ($options) {
                    case 'Proceed':
                        $sql="select client_code,client_group_code,client_name,credit_limit_amount,mobile_no,referred_by,new_client from client_master ORDER BY client_code ASC";
                        $sql1="select * from system_query where query_id = '$query_id'";
                    break;
                    case 'Search':
                        $sql="select client_code,client_group_code,client_name,credit_limit_amount,mobile_no,referred_by,new_client from client_master where client_name LIKE '%$search_val'";
                        $sql1="select * from system_query where query_id = '$query_id'";
                    break;
                }
                $data = $this->db->query($sql1)->getResultArray();
                $data2 = $this->db->query($sql)->getResultArray();
                return view("pages/Query/query_details", compact("options","option","data", "data2"));
            }
            else
            { 
            switch ($option) {
                case 'list':
                    $sql="select query_id,query_name from system_query where query_module_code = '$query_module_code' and status_code = 'A' order by query_name ";
                break;
            }
            $data = $this->db->query($sql)->getResultArray();
            $data2=[];
            return view("pages/Query/query_details", compact("options","option", "data","data2"));
            }
           }
            else
                {
                    return $this->matter_information();
                }
    }
    public function matter_information($option = 'list')
    { 
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['search_by']) ? $_REQUEST['search_by'] : null;
        $query_id = isset($_REQUEST['query_id']) ? $_REQUEST['query_id'] : null;
        $search_by = isset($_REQUEST['search_by']) ? $_REQUEST['search_by'] : null;
        $search_text = isset($_REQUEST['search_text']) ? $_REQUEST['search_text'] : null;
        $url = "/query/matter-information?search_by={$search_by}&search_text={$search_text}";
        $option2 = isset($_REQUEST['option']) ? $_REQUEST['option'] : null;
        if($search_by == 'Client')
     {
       $where_clause = "b.client_name like '%".$search_text."%'" ; $order_clause = "6,8" ; 
     }
     else if($search_by == 'Case')
     {
       $where_clause = "a.matter_desc1 like '%".$search_text."%'" ; $order_clause = "2,8" ; 
     }
     else if($search_by == 'Matter')
     {
       $where_clause = "a.matter_desc2 like '%".$search_text."%'" ; $order_clause = "3,8" ; 
     }
     else if($search_by == 'Ref')
     {
       $where_clause = "a.reference_desc like '%".$search_text."%'" ; $order_clause = "4,8" ; 
     }
     else if($search_by == 'Court')
     {
       $where_clause = "c.code_desc like '%".$search_text."%'" ; $order_clause = "7,8" ; 
     }
     else if($search_by == 'Judge')
     {
       $where_clause = "a.judge_name like '%".$search_text."%'" ; $order_clause = "5,8" ; 
     }
     else{
        $where_clause='';
     }
     
        if ($this->request->getMethod() == 'post') 
        {
            if($search_by != '')
                { 
                    switch ($option2) 
                    {
                            case 'list':
                                $matter_sql = "select a.matter_code,a.matter_desc1,a.matter_desc2,a.reference_desc,a.judge_name,b.client_name,c.code_desc court_name,'1' ind
                        from fileinfo_header a, client_master b, code_master c
                        where a.client_code     = b.client_code 
                        and a.court_code      = c.code_code  and c.type_code = '001' 
                        and ".$where_clause." 
                        UNION ALL
                        select d.matter_code,d.case_no matter_desc1,a.matter_desc2,a.reference_desc,a.judge_name,b.client_name,c.code_desc court_name, '2' ind
                        from fileinfo_header a, client_master b, code_master c, fileinfo_other_cases d
                        where a.client_code     = b.client_code 
                        and a.court_code      = c.code_code  and c.type_code = '001' 
                        and a.matter_code     = d.matter_code
                        and ".$where_clause ;
                        $data2 = $this->db->query($matter_sql)->getResultArray();
                        $option='search';
                        return view("pages/Query/matter_information", compact("options","option","data2","search_by","search_text","query_id"));
                        break;
                            
                            case 'Print':
                                $matter_sql = "select a.matter_code,a.matter_desc1,a.matter_desc2,a.reference_desc,a.judge_name,b.client_name,c.code_desc court_name,'1' ind
                        from fileinfo_header a, client_master b, code_master c
                        where a.client_code     = b.client_code 
                        and a.court_code      = c.code_code  and c.type_code = '001' 
                        and ".$where_clause." 
                        UNION ALL
                        select d.matter_code,d.case_no matter_desc1,a.matter_desc2,a.reference_desc,a.judge_name,b.client_name,c.code_desc court_name, '2' ind
                        from fileinfo_header a, client_master b, code_master c, fileinfo_other_cases d
                        where a.client_code     = b.client_code 
                        and a.court_code      = c.code_code  and c.type_code = '001' 
                        and a.matter_code     = d.matter_code
                        and ".$where_clause ;
                        $option="Print";
                        $data = $this->db->query($matter_sql)->getResultArray();
                        break;
                    }
                }
                if($query_id=='qry_0002')
                { 
                    $data2[]='';
                    return view("pages/Query/matter_information", compact("options","option","data2","search_by","search_text","query_id"));
                }
                // else
                // {
                //             return redirect()->to($url);
                // }
                
           
        }

    }
    public function print()
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['search_by']) ? $_REQUEST['search_by'] : null;
        $query_id = isset($_REQUEST['query_id']) ? $_REQUEST['query_id'] : null;
        $search_by = isset($_REQUEST['search_by']) ? $_REQUEST['search_by'] : null;
        $search_text = isset($_REQUEST['search_text']) ? $_REQUEST['search_text'] : null;
        $url = "/query/matter-information?search_by={$search_by}&search_text={$search_text}";
        $option2 = isset($_REQUEST['option']) ? $_REQUEST['option'] : null;
        if($search_by == 'Client')
            {
            $where_clause = "b.client_name like '%".$search_text."%'" ; $order_clause = "6,8" ; 
            }
            else if($search_by == 'Case')
            {
            $where_clause = "a.matter_desc1 like '%".$search_text."%'" ; $order_clause = "2,8" ; 
            }
            else if($search_by == 'Matter')
            {
            $where_clause = "a.matter_desc2 like '%".$search_text."%'" ; $order_clause = "3,8" ; 
            }
            else if($search_by == 'Ref')
            {
            $where_clause = "a.reference_desc like '%".$search_text."%'" ; $order_clause = "4,8" ; 
            }
            else if($search_by == 'Court')
            {
            $where_clause = "c.code_desc like '%".$search_text."%'" ; $order_clause = "7,8" ; 
            }
            else if($search_by == 'Judge')
            {
            $where_clause = "a.judge_name like '%".$search_text."%'" ; $order_clause = "5,8" ; 
            }
            else{
                $where_clause='';
            }
        $matter_sql = "select a.matter_code,a.matter_desc1,a.matter_desc2,a.reference_desc,a.judge_name,b.client_name,c.code_desc court_name,'1' ind
        from fileinfo_header a, client_master b, code_master c
        where a.client_code     = b.client_code 
        and a.court_code      = c.code_code  and c.type_code = '001' 
        and ".$where_clause." 
        UNION ALL
        select d.matter_code,d.case_no matter_desc1,a.matter_desc2,a.reference_desc,a.judge_name,b.client_name,c.code_desc court_name, '2' ind
        from fileinfo_header a, client_master b, code_master c, fileinfo_other_cases d
        where a.client_code     = b.client_code 
        and a.court_code      = c.code_code  and c.type_code = '001' 
        and a.matter_code     = d.matter_code
        and ".$where_clause ;
        $option="Print";
        $data = $this->db->query($matter_sql)->getResultArray();
        return view("pages/Query/print", compact("data")); 
    }
    public function payment_made_to_emp($option='list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $branch_code = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : null;
        $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : null;
        $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : null;
        $payee_payer_type = isset($_REQUEST['payee_payer_type']) ? $_REQUEST['payee_payer_type'] : null;
        $payee_payer_code = isset($_REQUEST['payee_payer_code']) ? $_REQUEST['payee_payer_code'] : $payee_payer_code = '%' ; ;
        $payee_payer_name = isset($_REQUEST['payee_payer_name']) ? $_REQUEST['payee_payer_name'] :  $payee_payer_name = '%' ;;
        $payee_payer_name = str_replace('|and|','&',$payee_payer_name);
        $start_date_ymd   = date_conv($start_date) ;  
        $end_date_ymd     = date_conv($end_date) ;  
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        if ($this->request->getMethod() == 'post') 
        {
            $ldgtrans_sql  = "select a.*,b.daybook_code,b.daybook_desc daybook_name from ledger_trans_hdr a, daybook_master b 
  	                    where a.branch_code        like '$branch_code' 
					      and a.doc_date        between '$start_date_ymd' and '$end_date_ymd' 
						  and a.doc_type              = 'PV'
						  and a.ref_doc_type         != 'CB'
					  	  and a.payee_payer_type   like '$payee_payer_type' 
						  and ifnull(a.payee_payer_code,'')   like '$payee_payer_code'
						  and a.payee_payer_name   like '$payee_payer_name' 
						  and a.status_code           = 'C'
						  and a.daybook_code          = b.daybook_code
                        order by fin_year desc, doc_date desc " ;
                        $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                        $data2 = $this->db->query($ldgtrans_sql)->getResultArray();
                        $data = $this->db->query($sql)->getResultArray();
                        //echo '<pre>'; print_r($data2);die;
            return view("pages/Query/payment_made_to_emp", compact("option","options","data","data2")); 
        }
        else
        {
            switch ($option) {
                case 'list':
                    $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                    break;
            }
            $data = $this->db->query($sql)->getResultArray();
            $data2[]='';
            return view("pages/Query/payment_made_to_emp", compact("option","options","data","data2")); 
        }
    }
    public function voucher_view($option='list')
    { 
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $serial_no = isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : null;
        if ($this->request->getMethod() == 'post') 
        {
            $tranhdr_qry="select a.*, b.branch_name from ledger_trans_hdr a, branch_master b where a.serial_no = '$serial_no' and a.branch_code = b.branch_code  ";
            $trandtl_qry ="select a.*, b.branch_name from ledger_trans_dtl a, branch_master b where a.ref_ledger_serial_no = '$serial_no' and a.branch_code = b.branch_code  ";
            $vchrhdr_qry = "select a.prepared_by, a.prepared_on, b.serial_no from voucher_header a, ledger_trans_hdr b where a.ref_ledger_serial_no = '$serial_no' and a.ref_ledger_serial_no = b.serial_no  ";
            $data = $this->db->query($tranhdr_qry)->getResultArray()[0];
            $data1 = $this->db->query($trandtl_qry)->getResultArray();
            $data2 = $this->db->query($vchrhdr_qry)->getResultArray()[0];
            return view("pages/Query/voucher_view", compact("option","data","data1","data2")); 
        }
    }

    public function payment_made_to_consltn($option='list')
    { 
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $branch_code = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : null;
        $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : null;
        $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : null;
        $payee_payer_type = isset($_REQUEST['payee_payer_type']) ? $_REQUEST['payee_payer_type'] : null;
        $payee_payer_code = isset($_REQUEST['payee_payer_code']) ? $_REQUEST['payee_payer_code'] : $payee_payer_code = '%' ; ;
        $payee_payer_name = isset($_REQUEST['payee_payer_name']) ? $_REQUEST['payee_payer_name'] :  $payee_payer_name = '%' ;;
        $payee_payer_name = str_replace('|and|','&',$payee_payer_name);
        $start_date_ymd   = date_conv($start_date) ;  
        $end_date_ymd     = date_conv($end_date) ;  
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        if ($this->request->getMethod() == 'post') 
        { 
            $ldgtrans_sql  = "select a.*,b.daybook_code,b.daybook_desc daybook_name from ledger_trans_hdr a, daybook_master b 
            where a.branch_code        like '$branch_code' 
            and a.doc_date        between '$start_date_ymd' and '$end_date_ymd' 
            and a.doc_type              = 'PV'
            and a.ref_doc_type         != 'CB'
              and a.payee_payer_type   like '$payee_payer_type' 
            and ifnull(a.payee_payer_code,'')   like '$payee_payer_code'
            and a.payee_payer_name   like '$payee_payer_name' 
            and a.status_code           = 'C'
            and a.daybook_code          = b.daybook_code
          order by fin_year desc, doc_date desc ";
                        $data2 = $this->db->query($ldgtrans_sql)->getResultArray();
                        $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                        $data = $this->db->query($sql)->getResultArray();
                        //echo '<pre>'; print_r($data2);die;
            return view("pages/Query/payment_made_to_consltn", compact("option","options","data","data2")); 
        }
        else
        { 
            switch ($option) {
                case 'list':
                    $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                    break;
            }
            $data = $this->db->query($sql)->getResultArray();
            $data2[]='';
            return view("pages/Query/payment_made_to_consltn", compact("option","options","data","data2")); 
        }
    }
    public function consltn_voucher_view($option='list')
    { 
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $serial_no = isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : null;
        if ($this->request->getMethod() == 'post') 
        {
            $tranhdr_qry="select a.*, b.branch_name from ledger_trans_hdr a, branch_master b where a.serial_no = '$serial_no' and a.branch_code = b.branch_code  ";
            $trandtl_qry ="select a.*, b.branch_name from ledger_trans_dtl a, branch_master b where a.ref_ledger_serial_no = '$serial_no' and a.branch_code = b.branch_code  ";
            $vchrhdr_qry = "select a.prepared_by, a.prepared_on, b.serial_no from voucher_header a, ledger_trans_hdr b where a.ref_ledger_serial_no = '$serial_no' and a.ref_ledger_serial_no = b.serial_no  ";
            $data = $this->db->query($tranhdr_qry)->getResultArray()[0];
            $data1 = $this->db->query($trandtl_qry)->getResultArray();
            $data2 = $this->db->query($vchrhdr_qry)->getResultArray()[0];
            return view("pages/Query/consltn_voucher_view", compact("option","data","data1","data2")); 
        }
    }
    public function finance_query_details($option = 'list')
    { 
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        $query_id = isset($_REQUEST['query_id']) ? $_REQUEST['query_id'] : null;
        $branch_code = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : null;
        $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : null;
        $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : null;
        $payee_payer_type = isset($_REQUEST['payee_payer_type']) ? $_REQUEST['payee_payer_type'] : null;
        $payee_payer_code = isset($_REQUEST['payee_payer_code']) ? $_REQUEST['payee_payer_code'] : $payee_payer_code = '%' ; ;
        $payee_payer_name = isset($_REQUEST['payee_payer_name']) ? $_REQUEST['payee_payer_name'] :  $payee_payer_name = '%' ;;
        $payee_payer_name = str_replace('|and|','&',$payee_payer_name);
        $start_date_ymd   = date_conv($start_date) ;  
        $end_date_ymd     = date_conv($end_date) ; 
        if($options!='pro')
        {
           if($query_id!='qry_0004')
            {
                if($this->request->getMethod() == 'post') 
                { 
                    switch ($options) 
                    {
                        case 'send':
                            $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                            break;
                    }
                    $data = $this->db->query($sql)->getResultArray();
                    $data2=[];
                    return view("pages/Query/voucher_view", compact("options","option","data","data2"));
                }
                else
                { 
                    switch ($option) {
                        case 'list':
                           // $sql="select query_id,query_name from system_query where query_module_code = '$query_module_code' and status_code = 'A' order by query_name ";
                           $sql="select branch_code,branch_name from branch_master  order by branch_code";
                           break;
                    }
                    $data = $this->db->query($sql)->getResultArray();
                    $data2=[];
                    return view("pages/Query/payment_made", compact("options","option", "data","data2"));
                }
            }
            else
            { //echo $options;die;
                $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                $sql2="select * from params order by fin_year desc";
                $sql3="select * from daybook_master order by daybook_desc";
                $data = $this->db->query($sql)->getResultArray();
                $finyr_qry = $this->db->query($sql2)->getResultArray();
                $daybook_qry = $this->db->query($sql3)->getResultArray();
                $vchdtl_sql[]=null;
                $vchhdr_sql[]=null;
                return view("pages/Query/payment_to_party_voucher", compact("options","option", "data","finyr_qry","daybook_qry","vchdtl_sql","vchhdr_sql"));
              //  return redirect()->to(base_url("/query/payment-to-party-voucher"));
               // return redirect()->to(base_url("/query/payment-to-party-voucher"));
            }   
        }
        else
        {
            $ldgtrans_sql  = "select a.*,b.daybook_code,b.daybook_desc daybook_name from ledger_trans_hdr a, daybook_master b 
            where a.branch_code        like '$branch_code' 
            and a.doc_date        between '$start_date_ymd' and '$end_date_ymd' 
            and a.doc_type              = 'PV'
            and a.ref_doc_type         != 'CB'
              and a.payee_payer_type   like '$payee_payer_type' 
            and ifnull(a.payee_payer_code,'')   like '$payee_payer_code'
            and a.payee_payer_name   like '$payee_payer_name' 
            and a.status_code           = 'C'
            and a.daybook_code          = b.daybook_code
          order by fin_year desc, doc_date desc ";
                        $data2 = $this->db->query($ldgtrans_sql)->getResultArray();
                        $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                        $data = $this->db->query($sql)->getResultArray();
            return view("pages/Query/payment_made", compact("options","option", "data","data2"));
        }
    }
    public function payment_to_party_voucher($option = 'list')
    {       
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        $branch_code      = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : null;
        $voucher_type     = isset($_REQUEST['voucher_type']) ? $_REQUEST['voucher_type'] : null;
        $fin_year         = isset($_REQUEST['fin_year']) ? $_REQUEST['fin_year'] : null;
        $daybook_code     = isset($_REQUEST['daybook_code']) ? $_REQUEST['daybook_code'] : null;
        $voucher_no       = isset($_REQUEST['voucher_no']) ? $_REQUEST['voucher_no'] : null;
        $serial_no       = isset($_REQUEST['serial_no']) ? $_REQUEST['serial_no'] : null;
        if($this->request->getMethod() == 'post') 
        {
            $vchdtl_sql  = "select a.main_ac_code,a.sub_ac_code,a.client_code,a.matter_code,a.narration,a.dr_cr_ind,a.gross_amount
            from ledger_trans_dtl a
             where a.ref_ledger_serial_no = '$serial_no' 
           order by a.dr_cr_ind desc, a.main_ac_code, a.sub_ac_code " ;
           $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                $sql2="select * from params order by fin_year desc";
                $sql3="select * from daybook_master order by daybook_desc";
                $vchhdr_sql  = "select a.serial_no,a.payee_payer_name,a.instrument_type,a.instrument_no,a.instrument_dt,a.bank_name,a.gross_amount,a.tax_amount,a.net_amount
                       from ledger_trans_hdr a
  	                  where a.branch_code        like '$branch_code' 
				        and a.doc_type              = '$voucher_type'
						and a.fin_year              = '$fin_year'
						and a.daybook_code          = '$daybook_code'
						and a.doc_no                = '$voucher_no'
						and a.status_code           = 'C' " ; 
                $data = $this->db->query($sql)->getResultArray();
                $vchdtl_sql = $this->db->query($vchdtl_sql)->getResultArray();
                $finyr_qry = $this->db->query($sql2)->getResultArray();
                $daybook_qry = $this->db->query($sql3)->getResultArray();
                $vchhdr_sql = $this->db->query($vchhdr_sql)->getResultArray();
                //echo '<pre>';print_r($vchhdr_sql);die;
            //return redirect()->to(base_url("/query/voucher", compact("options","option", "data","data2")));
           return view("pages/Query/payment_to_party_voucher", compact("options","option", "data","vchdtl_sql","finyr_qry","daybook_qry","vchhdr_sql"));
        }
    }
    public function councel_memo_query_details($option = 'list')
    {  
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $defaultdate="01-04-".$splitdate[0];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        $branch_code      = $data['branch_code']['branch_code'];
        $branch_name      = $data['branch_code']['branch_name'];
        $counsel_code       = '%'; 
        $counsel_name       = 'ALL' ;
        $query_id = isset($_REQUEST['query_id']) ? $_REQUEST['query_id'] : null;
        $start_date         = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null ;   if($start_date == '') {$start_date = $defaultdate ;}
        $end_date           = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null ;       if($end_date   == '') {$end_date   = date('d-m-Y') ;}
        //
        $period_start_date  = isset($_REQUEST['period_start_date'])?$_REQUEST['period_start_date']:null ;  if($period_start_date == '') {$period_start_date = $start_date;}
        $period_end_date    = isset($_REQUEST['period_end_date'])?$_REQUEST['period_end_date']:null ;      if($period_end_date   == '') {$period_end_date   = $end_date  ;}
        $start_date_ymd     = date_conv($period_start_date,'-') ;
        $end_date_ymd       = date_conv($period_end_date,  '-') ;
        $curr_yyyymmdd      = date_conv(date('d-m-Y'),   '-') ;
        if ($end_date_ymd > $curr_yyyymmdd) { $end_date_ymd = $curr_yyyymmdd ; $period_end_date = date('d-m-Y') ; }
        $fin_years = $this->db->query("select * from params where year_close_ind = 'N' order by fin_year desc")->getResultArray();
   //
   $period_desc  = $period_start_date.' - ' .$period_end_date ; 

        if($this->request->getMethod() == 'post') 
        {
            if($query_id=='qry_0022'){
             $trans_qry = "select a.counsel_code, b.associate_name counsel_name, sum(ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) credited_amount from counsel_memo_header a, associate_master b, ledger_trans_hdr c where a.branch_code = '$branch_code' and a.counsel_code like '$counsel_code' and a.counsel_code = b.associate_code and b.associate_type = '001' and (ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) > 0 and a.ref_ledger_serial_no = c.serial_no and c.doc_date between '$start_date_ymd' and '$end_date_ymd' group by a.counsel_code, b.associate_name order by b.associate_name";
             $data = $this->db->query($trans_qry)->getResultArray();
            return view("pages/Query/councel_memo_credited", compact("options","option","data","branch_name","counsel_name","start_date","end_date","branch_code"));
            }
            else
            {
                $trans_qry = "select a.counsel_code, b.associate_name counsel_name, sum((ifnull(counsel_fee,0)+ifnull(clerk_fee,0))-(ifnull(counsel_fee_jv,0)+ifnull(clerk_fee_jv,0))) os_amount from counsel_memo_header a, associate_master b where a.branch_code = '$branch_code' and a.counsel_code like '$counsel_code' and a.counsel_code = b.associate_code and b.associate_type = '001' and (ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) = 0 group by a.counsel_code, b.associate_name order by b.associate_name";
                $data = $this->db->query($trans_qry)->getResultArray();
               return view("pages/Query/councel_memo_os", compact("options","option","data","branch_name","counsel_name","start_date","end_date","branch_code"));   
            }
        }
        else
        {
            switch ($option) {
                case 'list':
                    $sql="select query_id,query_name from system_query where query_module_code = '$query_module_code' and status_code = 'A' order by query_name ";
                    break;
            }
            $data = $this->db->query($sql)->getResultArray();
            return view("pages/Query/councel_memo_query_details", compact("options","option", "data"));
        }
    }
    public function councel_memo_view($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $counsel_code = isset($_REQUEST['counsel_code']) ? $_REQUEST['counsel_code'] : null;
        $end_date_ymd   = date('Y-m-d') ;
        $branch_name      = $data['branch_code']['branch_name'];
        $counsel_name       = isset($_REQUEST['counsel_name']) ? $_REQUEST['counsel_name'] : null;
        if ($this->request->getMethod() == 'post') 
        {
            $sql="select a.serial_no, a.memo_no, a.memo_date, b.brief_date, b.client_code, b.matter_code, b.initial_code, b.narration, (ifnull(b.counsel_fee,0)+ifnull(b.clerk_fee,0)) os_amount from counsel_memo_header a, counsel_memo_detail b where a.counsel_code = '$counsel_code' and (ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) = 0 and a.serial_no = b.ref_counsel_memo_serial_no order by a.memo_date ";
            $data = $this->db->query($sql)->getResultArray();
            return view("pages/Query/councel_memo_view", compact("option","data","end_date_ymd","branch_name","counsel_name")); 
        }
    }
    public function councel_memo_credited_view($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $defaultdate="01-04-".$splitdate[0];
        $start_date_ymd = date('Y-m-d',strtotime($defaultdate)) ;
        $branch_name      = $data['branch_code']['branch_name'];
        $end_date_ymd   = date('Y-m-d') ;
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $counsel_code = isset($_REQUEST['counsel_code']) ? $_REQUEST['counsel_code'] : null;
        $counsel_name = isset($_REQUEST['counsel_name']) ? $_REQUEST['counsel_name'] : null;
        if ($this->request->getMethod() == 'post') 
        {
            $sql="select a.serial_no, a.memo_no, a.memo_date, b.brief_date, b.client_code, b.matter_code, b.initial_code, b.narration, (ifnull(b.counsel_fee,0)+ifnull(b.clerk_fee,0)) credited_amount from counsel_memo_header a, counsel_memo_detail b, ledger_trans_hdr c where a.counsel_code = '$counsel_code' and (ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) > 0 and a.ref_ledger_serial_no = c.serial_no and c.doc_date between '$start_date_ymd' and '$end_date_ymd' and a.serial_no = b.ref_counsel_memo_serial_no order by a.memo_date ";
            $data = $this->db->query($sql)->getResultArray();
            return view("pages/Query/councel_memo_credited_view", compact("option","data","defaultdate","end_date_ymd","branch_name","counsel_name")); 
        }
    }
    public function billing_query_details($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $defaultdate="01-04-".$splitdate[0];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        $branch_code      = $data['branch_code']['branch_code'];
        $branch_name      = $data['branch_code']['branch_name'];
        // $counsel_code       = '%'; 
        // $counsel_name       = 'ALL' ;
        $query_id = isset($_REQUEST['query_id']) ? $_REQUEST['query_id'] : null;
        // $start_date         = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null ;   if($start_date == '') {$start_date = $defaultdate ;}
        // $end_date           = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null ;       if($end_date   == '') {$end_date   = date('d-m-Y') ;}
        // //
        // $period_start_date  = isset($_REQUEST['period_start_date'])?$_REQUEST['period_start_date']:null ;  if($period_start_date == '') {$period_start_date = $start_date;}
        // $period_end_date    = isset($_REQUEST['period_end_date'])?$_REQUEST['period_end_date']:null ;      if($period_end_date   == '') {$period_end_date   = $end_date  ;}
        // $start_date_ymd     = date_conv($period_start_date,'-') ;
        // $end_date_ymd       = date_conv($period_end_date,  '-') ;
        // $curr_yyyymmdd      = date_conv(date('d-m-Y'),   '-') ;
        // if ($end_date_ymd > $curr_yyyymmdd) { $end_date_ymd = $curr_yyyymmdd ; $period_end_date = date('d-m-Y') ; }
        // $fin_years = $this->db->query("select * from params where year_close_ind = 'N' order by fin_year desc")->getResultArray();
   //
  // $period_desc  = $period_start_date.' - ' .$period_end_date ; 

        if($this->request->getMethod() == 'post') 
        {
            if($query_id=='qry_0020'){
            //  $trans_qry = "select a.counsel_code, b.associate_name counsel_name, sum(ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) credited_amount from counsel_memo_header a, associate_master b, ledger_trans_hdr c where a.branch_code = '$branch_code' and a.counsel_code like '$counsel_code' and a.counsel_code = b.associate_code and b.associate_type = '001' and (ifnull(a.counsel_fee_jv,0)+ifnull(a.clerk_fee_jv,0)) > 0 and a.ref_ledger_serial_no = c.serial_no and c.doc_date between '$start_date_ymd' and '$end_date_ymd' group by a.counsel_code, b.associate_name order by b.associate_name";
            //  $data = $this->db->query($trans_qry)->getResultArray();
            $sql="select branch_code,branch_name from branch_master  order by branch_code ";
            $fin_years = $this->db->query("select * from params order by fin_year desc")->getResultArray();
            $data = $this->db->query($sql)->getResultArray();
            return view("pages/Query/qry_bill_details_billno", compact("options","option","data","fin_years","query_id"));
            }
            if($query_id=='qry_0008')
            {
               
                $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                 $branch = $this->db->query($sql)->getResultArray();
               return view("pages/Query/qry_bill_details_matter", compact("options","option","branch"));   
            }
            if($query_id=="qry_0021")
            {
                $sql="select branch_code,branch_name from branch_master  order by branch_code ";
                $branch = $this->db->query($sql)->getResultArray();
                return view("pages/Query/qry_bill_not_approved", compact("options","option","branch"));  
            }
        }
        else
        {
            switch ($option) {
                case 'list':
                    $sql="select query_id,query_name from system_query where query_module_code = '$query_module_code' and status_code = 'A' order by query_name ";
                    break;
            }
            $data = $this->db->query($sql)->getResultArray();
            return view("pages/Query/billing_query_details", compact("options","option", "data"));
        } 
    }
    public function qry_bill_details_bill_no_realisation($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $defaultdate="01-04-".$splitdate[0];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['displayid']) ? $_REQUEST['displayid'] : null;
        $menu_id = isset($_REQUEST['menuid']) ? $_REQUEST['menuid'] : null;
        $queryId = isset($_REQUEST['queryId']) ? $_REQUEST['queryId'] : null;
        $branch_code      = isset($_REQUEST['branchcode']) ? $_REQUEST['branchcode'] : null;
        $finYear      = isset($_REQUEST['finYear']) ? $_REQUEST['finYear'] : null;
        $billNo      = isset($_REQUEST['billNo']) ? $_REQUEST['billNo'] : null;
        
        if($this->request->getMethod() == 'post') 
        {
              $sql = "select a.ref_bill_year,a.ref_bill_no,a.realised_amount gross_amount,
            c.fin_year,c.doc_date,c.doc_no,c.doc_type,c.daybook_code,c.received_from,c.instrument_no,c.instrument_dt,c.bank_name,c.service_tax_amount
       from bill_realisation_detail a, bill_realisation_header b, ledger_trans_hdr c
      where a.branch_code            like '$branch_code' 
        and a.ref_bill_year          like '$finYear'
        and a.ref_bill_no            like '$billNo'
        and a.ref_realisation_serial_no = b.serial_no
        and b.ref_ledger_serial_no      = c.serial_no
        and c.status_code               = 'C'
      order by c.fin_year,c.doc_date,c.doc_type,c.doc_no ";
      $sql1="select branch_code,branch_name from branch_master  order by branch_code ";
      $sql2 = "select * from params order by fin_year desc";
      $resultArray = $this->db->query($sql)->getResultArray();
      if (!empty($resultArray)) {
        $data = $resultArray[0];
        } else {
            // Handle the case where no results were found
            $data = []; // or any default value or action you want to take
        }
            $branch = $this->db->query($sql1)->getResultArray();
            $fin_years = $this->db->query($sql2)->getResultArray();
            $branchCode[]=$branch_code;
            return view("pages/Query/qry_bill_details_bill_no_realisation", compact("data","branch","fin_years","branchCode"));
           
            

           
        
        
        
        }
       
    }
    public function qry_bill_details_matter($option = 'list')
    { 
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $branch_code    = isset($_REQUEST['branch_code'])?$_REQUEST['branch_code']:null;
        $start_date     = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:null;
        $end_date       = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:null;
        $matter_code    = isset($_REQUEST['matter_code'])?$_REQUEST['matter_code']:null;
        $matter_desc    = isset($_REQUEST['matter_desc'])?$_REQUEST['matter_desc']:null;
        $matter_descst =$matter_desc;
        $matter_desc    = str_replace($matter_desc,'_|_','&') ;
        $matter_desc    = str_replace($matter_desc,'-|-',"'") ;
        $client_code    = isset($_REQUEST['client_code'])?$_REQUEST['client_code']:null;
        $client_name    = isset($_REQUEST['client_name'])?$_REQUEST['client_name']:null;
        $client_namest =$client_name;
        $client_name    = str_replace($client_name,'_|_','&') ;
        $client_name    = str_replace($client_name,'-|-',"'") ;
        $bill_status    = isset($_REQUEST['bill_status'])?$_REQUEST['bill_status']:null;
        $query_mode     = isset($_REQUEST['query_mode'])?$_REQUEST['query_mode']:null;
        $options     = isset($_REQUEST['option'])?$_REQUEST['option']:null;
      
        if($bill_status == 'A') { $stat_cond = "like 'A' " ; } else if($bill_status == 'B') { $stat_cond = "in ('B','C')" ; } else if($bill_status == 'X') { $stat_cond = "like 'X' " ; }  else { $stat_cond = "like '%' " ; }   
      
        //
        if ($start_date != '') { $start_date_ymd = date_conv($start_date) ; } else { $start_date_ymd = '1901-01-01' ; } 
        if ($end_date   != '') { $end_date_ymd   = date_conv($end_date)   ; } else { $end_date_ymd   = date('Y-m-d') ; } 
        if ($start_date != '') { $period_desc = $start_date.' - '.$end_date ; } else { $period_desc  = 'UPTO '.$end_date ; }
        if($this->request->getMethod() == 'post') 
        {
            $bill_sql       = "select a.serial_no, a.ref_bill_serial_no, a.bill_date draft_bill_date, b.bill_date, a.start_date, a.end_date, a.bill_amount_inpocket, a.bill_amount_outpocket, a.bill_amount_counsel, a.service_tax_amount, a.status_code,a.prepared_by,a.updated_by,a.approved_by,
                            if(b.bill_no != '', concat(b.fin_year,'/',b.bill_no), '') bill_number, 
							(ifnull(b.realise_amount_inpocket,0)+ifnull(b.realise_amount_outpocket,0)+ifnull(b.realise_amount_counsel,0)+ifnull(b.realise_amount_service_tax,0)) realise_amount,
							(ifnull(b.deficit_amount_inpocket,0)+ifnull(b.deficit_amount_outpocket,0)+ifnull(b.deficit_amount_counsel,0)+ifnull(b.deficit_amount_service_tax,0)) deficit_amount,
							((ifnull(b.bill_amount_inpocket,0)+ifnull(b.bill_amount_outpocket,0)+ifnull(b.bill_amount_counsel,0)+ifnull(b.service_tax_amount,0)) - (ifnull(b.realise_amount_inpocket,0)+ifnull(b.realise_amount_outpocket,0)+ifnull(b.realise_amount_counsel,0)+ifnull(b.realise_amount_service_tax,0)+ifnull(b.deficit_amount_inpocket,0)+ifnull(b.deficit_amount_outpocket,0)+ifnull(b.deficit_amount_counsel,0)+ifnull(b.deficit_amount_service_tax,0))) balance_amount
                       from billinfo_header a left outer join bill_detail b on b.serial_no = a.ref_bill_serial_no
                      where a.branch_code     like    '$branch_code'
	 			        and a.bill_date       between '$start_date_ymd' and '$end_date_ymd' 
						and a.matter_code     like    '$matter_code' 
						and a.status_code     " . $stat_cond  . "
                        and a.status_code != 'D'
                      order by a.bill_date desc, a.serial_no ";
                      $sql1="select branch_code,branch_name from branch_master  order by branch_code ";
                      $data = $this->db->query($bill_sql)->getResultArray();
                      $branch = $this->db->query($sql1)->getResultArray();
                      return view("pages/Query/bill_details_matter", compact("option","data","branch","branch_code","start_date","end_date","matter_code","matter_descst","client_namest","client_code","bill_status"));
                    }

    }
    public function rep_final_bill_tax($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $logdt_qry      = "select current_date, current_time, date_format(current_date,'%d-%m-%Y') current_dmydate ";
        $DATE = $this->db->query($logdt_qry)->getResultArray()[0];
        $login_date     = $DATE['current_date'];
        $user_id        = session()->userId ;

        $curr_time      = $DATE['current_time'];

        $curr_date      = $DATE['current_dmydate'];

        $curr_day       = substr($curr_date,0,2) ;

        $curr_month     = substr($curr_date,3,2) ; 

        $curr_year      = substr($curr_date,6,4) ;

        $temp_id        = $user_id."_".$curr_year.$curr_month.$curr_day.str_replace(':','',$curr_time);
        $temp_table     = $temp_id. "_fb" ;
        $x25thLogoYear   = ('20');
        $tbl_qry = $this->temp_db->query("drop table if exists $temp_table");
        $this->temp_db->query("create table if not exists $temp_table 
                        (srl_no        int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                         row_no        int(4),
                         activity_date date,
                         activity_desc varchar(200),
                         io_ind        varchar(1),
                         source_code   varchar(1),
                         billed_amount       double(12,2),
                         service_tax_ind     varchar(1),
                         service_tax_percent double(7,3),
                         service_tax_desc    varchar(50),
                         service_tax_amount  double(12,2))");
        $row_count       = 1;
        $bill_str        = isset($_REQUEST['ref_bill_serial_no'])?$_REQUEST['ref_bill_serial_no']:$_REQUEST['serial_no'];
        $final_bill_date = isset($_REQUEST['final_bill_date'])?$_REQUEST['final_bill_date']:null;
        $dupl_ind        = isset($_REQUEST['dupl_ind'])?$_REQUEST['dupl_ind']:null;
        $revd_ind        = isset($_REQUEST['revd_ind'])?$_REQUEST['revd_ind']:null;
        $prop_ind        = isset($_REQUEST['prop_ind'])?$_REQUEST['prop_ind']:null;
        $copy_ind        = isset($_REQUEST['copyx_ind'])?$_REQUEST['copyx_ind']:null;
        $tot_char        = 58 ;
        $tot_no_of_lines = 60 ;
        $b_serial_no     = $bill_str;
       // print_r($b_serial_no);die;
            for($i=1;$i<=$row_count;$i++)
            {
                $bill_serial_no = $b_serial_no;
                $hdr_stmt  = "select b.serial_no   ref_bill_serial_no,
                                    a.serial_no,
                                    b.branch_code,
                                    date_format(b.bill_date,'%d-%m-%Y') bill_date,
                                    b.client_code,
                                    b.matter_code,
                                    a.subject_desc,
                                    a.other_case_desc,
                                    a.reference_desc,
                                    a.no_fee_bill_ind,
                                    ifnull(a.direct_counsel_ind,'N') direct_counsel_ind,
                                    a.status_code,
                                    ifnull(b.bill_amount_inpocket, '') bill_amount_inpocket,
                                    ifnull(b.bill_amount_outpocket,'') bill_amount_outpocket,
                                    ifnull(b.bill_amount_counsel,  '') bill_amount_counsel,
                                    ifnull(b.service_tax_amount,   '') service_tax_amount,
                                    a.source_code
                                from bill_detail b
                                        left outer join billinfo_header a on a.ref_bill_serial_no = b.serial_no 
                                where b.serial_no = '$bill_serial_no' ";
                            
                $hdr_row = $this->db->query($hdr_stmt)->getResultArray();
            if (!empty($hdr_row)) {
                $hdr_row = $hdr_row[0];
                $ref_bill_serial_no    = $hdr_row['ref_bill_serial_no'];
                $serial_no             = $hdr_row['serial_no'];
                $branch_code           = $hdr_row['branch_code'];
                $matter_code           = $hdr_row['matter_code'];
                $subject_desc          = stripslashes($hdr_row['subject_desc']);
                $other_case_desc       = stripslashes($hdr_row['other_case_desc']);
                $other_case_desc       = str_replace(',','<br>',$other_case_desc);
                $reference_desc        = stripslashes($hdr_row['reference_desc']);
                $no_fee_bill_ind       = $hdr_row['no_fee_bill_ind'];
                $direct_counsel_ind    = stripslashes($hdr_row['direct_counsel_ind']);
                $bill_amount_inpocket  = $hdr_row['bill_amount_inpocket'];
                $bill_amount_outpocket = $hdr_row['bill_amount_outpocket'];
                $bill_amount_counsel   = $hdr_row['bill_amount_counsel'];
                $service_tax_amount    = $hdr_row['service_tax_amount'];
                $source_code           = $hdr_row['source_code'];
                } else {
                    // Handle the case where no results were found
                    $hdr_row = []; // or any default value or action you want to take
                    $ref_bill_serial_no    = '';
                $serial_no             = '';
                $branch_code           = '';
                $matter_code           = '';
                $subject_desc          = '';
                $other_case_desc       = '';
                $other_case_desc       = '';
                $reference_desc        = '';
                $no_fee_bill_ind       = '';
                $direct_counsel_ind    = '';
                $bill_amount_inpocket  = '';
                $bill_amount_outpocket = '';
                $bill_amount_counsel   = '';
                $service_tax_amount    = '';
                $source_code           = '';
                }
             
                
                //
                $myqry1 = "select subject_desc, reference_desc from fileinfo_header where matter_code = '$matter_code' ";
                $myqry1 = $this->db->query($myqry1)->getResultArray();
                if (!empty($myqry1)) 
                    {
                        $myqry1 = $myqry1[0];
                        $subj_desc = stripslashes($myqry1['subject_desc']);
                        $refr_desc = stripslashes($myqry1['reference_desc']) ;
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $myqry1 = []; // or any default value or action you want to take
                        $subj_desc = '';
                        $refr_desc = '';
                    }
                
                if ($subject_desc   == '') { $subject_desc   = $subj_desc ; }
                if ($reference_desc == '') { $reference_desc = $refr_desc ; }
                //	  	

                $branch_sql         = "select * from branch_master where branch_code = '$branch_code' ";
                $branch_sql = $this->db->query($branch_sql)->getResultArray();
                if (!empty($branch_sql)) 
                    {
                        $branch_sql = $branch_sql[0];
                        $branch_addr1       = $branch_sql['address_line_1'].', '.$branch_sql['city'].' - '.$branch_sql['pin_code'] ;
                        $branch_addr2       = 'TEL : '.$branch_sql['phone_no'].'     FAX : '.$branch_sql['fax_no'] ;
                        $branch_addr3       = 'E-Mail : '.$branch_sql['email_id'] ;
                        $branch_pan_no      = 'PAN : '.$branch_sql['pan_no'] ;
                        $branch_service_tax = 'SERVICE TAX REGN. NO. : '.$branch_sql['pan_no'] ;
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $branch_sql = []; // or any default value or action you want to take
                        $branch_addr1       = '';
                $branch_addr2       = '' ;
                $branch_addr3       = '' ;
                $branch_pan_no      = '';
                $branch_service_tax = '' ;
                    }
                $service_nature     = 'NATURE OF SERVICE : LEGAL CONSULTANT`S SERVICE';
                $service_dec        = 'GST on the above services are payable on reverse charge basis by you, the service recipient.';

                
                $my_company_name = 'Sinha & Company' ;

                
                $direct_memo  = 'Counsel fee not included, payable directly by you as per memo(s).';
                $direct_memo = strtoupper($direct_memo) ;
                $bill_stmt = "select fin_year,bill_no,client_code,address_code,attention_code,date_format(bill_date,'%d-%m-%Y') bill_date, bill_date b_d
                                                        ,sum(ifnull(bill_amount_inpocket_stax,0)) bill_amount_inpocket_stax
                                                        ,sum(ifnull(bill_amount_outpocket_stax,0)) bill_amount_outpocket_stax
                                                        ,sum(ifnull(bill_amount_counsel_stax,0)) bill_amount_counsel_stax
                                                        ,sum(ifnull(bill_amount_inpocket_ntax,0)) bill_amount_inpocket_ntax
                                                        ,sum(ifnull(bill_amount_outpocket_ntax,0)) bill_amount_outpocket_ntax
                                                        ,sum(ifnull(bill_amount_counsel_ntax,0)) bill_amount_counsel_ntax
                                                        ,service_tax_amount from bill_detail where serial_no = '".$ref_bill_serial_no."' group by fin_year,bill_no,bill_date";
               
                $bill_row = $this->db->query($bill_stmt)->getResultArray();
                if (!empty($bill_row)) 
                    {
                        $bill_row = $bill_row[0];
                        $bill_fin_year                = $bill_row['fin_year'];
                $bill_no                      = $bill_row['bill_no'];
                $client_code                  = $bill_row['client_code'];
                $bill_addr_code               = $bill_row['address_code'];
                $bill_attn_code               = $bill_row['attention_code'];
                $bill_date                    = $bill_row['bill_date'];
                $bill_amount_inpocket_stax    = $bill_row['bill_amount_inpocket_stax'];
                $bill_amount_outpocket_stax   = $bill_row['bill_amount_outpocket_stax'];
                $bill_amount_counsel_stax     = $bill_row['bill_amount_counsel_stax'];
                $bill_amount_inpocket_ntax    = $bill_row['bill_amount_inpocket_ntax'];
                $bill_amount_outpocket_ntax   = $bill_row['bill_amount_outpocket_ntax'];
                $bill_amount_counsel_ntax     = $bill_row['bill_amount_counsel_ntax'];
                $service_tax_amount           = $bill_row['service_tax_amount'];
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $bill_row = []; // or any default value or action you want to take
                        $bill_fin_year                = '';
                        $bill_no                      = '';
                        $client_code                  = '';
                        $bill_addr_code               = '';
                        $bill_attn_code               = '';
                        $bill_date                    = '';
                        $bill_amount_inpocket_stax    = '';
                        $bill_amount_outpocket_stax   = '';
                        $bill_amount_counsel_stax     = '';
                        $bill_amount_inpocket_ntax    = '';
                        $bill_amount_outpocket_ntax   = '';
                        $bill_amount_counsel_ntax     = '';
                        $service_tax_amount           = '';
                    }
                

                //--- SB (05/01/2019) 
                if(empty($final_bill_date)) 
                {
                    $x25thLogoInd = (substr($bill_date,6,4) == $x25thLogoYear) ? 'Y' : 'N' ; 
                }	
                else 
                {
                    $x25thLogoInd = (substr($final_bill_date,6,4) == $x25thLogoYear) ? 'Y' : 'N' ; 
                }
                //
                // fileinfo header
                $finh_stmt = "select trust_name,matter_desc1,matter_desc2,billing_addr_code,billing_attn_code from fileinfo_header where matter_code = '".$matter_code."'";
                $finh_row = $this->db->query($finh_stmt)->getResultArray();
                if (!empty($finh_row)) 
                    {
                        $finh_row = $finh_row[0];
                        $matter_name    = stripslashes($finh_row['matter_desc1']).'&nbsp;'.stripslashes($finh_row['matter_desc2']);
                        $trust_name     = stripslashes($finh_row['trust_name']);
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $finh_row = []; // or any default value or action you want to take
                        $matter_name    = '';
                        $trust_name     = '';
                    }
               
                $clnt_stmt = "select * from client_master where client_code = '".$client_code."'";
                $clnt_row = $this->db->query($clnt_stmt)->getResultArray();
                if (!empty($clnt_row)) 
                    {
                        $clnt_row = $clnt_row[0];
                        $client_name = $clnt_row['client_name'];
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $clnt_row = []; // or any default value or action you want to take
                        $client_name = '';
                    }
                
                //   

                // client address
                $cadr_stmt = "select * from client_address where client_code = '".$client_code."' and address_code = '".$bill_addr_code."'";
                //echo $cadr_stmt;die;
                $cadr_row = $this->db->query($cadr_stmt)->getResultArray();
                if (!empty($cadr_row)) 
                {
                    $cadr_row = $cadr_row[0];
                    $address_line_1 = $cadr_row['address_line_1'];
                    $address_line_2 = $cadr_row['address_line_2'];
                    $address_line_3 = $cadr_row['address_line_3'];
                    $address_line_4 = $cadr_row['address_line_4'];
                    $city           = $cadr_row['city'];
                    $pin_code       = ' - '.$cadr_row['pin_code'];
                } 
                else 
                {
                    // Handle the case where no results were found
                    $cadr_row = []; // or any default value or action you want to take
                    $address_line_1 = '';
                    $address_line_2 = '';
                    $address_line_3 = '';
                    $address_line_4 = '';
                    $city           = '';
                    $pin_code       = '';
                }
                
                // client address for gst
                $gst_sql         = "select client_gst, pan_no from client_address where client_code = '".$client_code."' and address_code = '".$bill_addr_code."'";
                $gst_sql = $this->db->query($gst_sql)->getResultArray();
                if (!empty($gst_sql)) 
                    {
                        $gst_sql = $gst_sql[0];
                        $client_gst       = $gst_sql['client_gst'] ;
                        $pan_no           = $gst_sql['pan_no'] ;
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $gst_sql = []; // or any default value or action you want to take
                        $client_gst       = '' ;
                        $pan_no           = '' ;
                    }
               
                
                // client address with state for gst
                $state_sql         = "select a.state_code, a.state_name, a.zone_code, a.gst_zone_code, a.country FROM state_master a, client_address b where a.state_code = b.state_code and a.state_code <> '33' and b.address_code = '".$bill_addr_code."'"; 
                $state_sql = $this->db->query($state_sql)->getResultArray();
                if (!empty($state_sql)) 
                    {
                        $state_sql = $state_sql[0];
                        $state_name       = $state_sql['state_name'] ;
                        $gst_zone_code    = $state_sql['gst_zone_code'] ;
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $state_sql = []; // or any default value or action you want to take
                        $state_name       = '';
                        $gst_zone_code    = '';
                    }
                

                // client attention
                $catn_stmt = "select * from client_attention where client_code = '".$client_code."' and attention_code = '".$bill_attn_code."'";
                $catn_row = $this->db->query($catn_stmt)->getResultArray();
                if (!empty($catn_row)) 
                    {
                        $catn_row = $catn_row[0];
                        $attention_name = $catn_row['attention_name'];
                        $designation    = $catn_row['designation'];
                        $sex            = $catn_row['sex'];
                        $title          = $catn_row['title'];
                    } 
                    else 
                    {
                        // Handle the case where no results were found
                        $catn_row = []; // or any default value or action you want to take
                        $attention_name = '';
                        $designation    = '';
                        $sex            = '';
                        $title          = '';
                    }
               
                if($title != 'ORS') { $attention_name = $title.' '.$attention_name; }
                if($title == 'ORS') { $attention_name = $attention_name; }
                // billinfo detail    
                $dtl_stmt = "select b.row_no,
                                    b.activity_date,
                                    b.activity_desc,
                                    b.io_ind,
                                    b.source_code,
                                    ifnull(b.billed_amount,0) billed_amount,
                                    ifnull(b.service_tax_ind,'N') service_tax_ind,
                                    ifnull(b.service_tax_percent,'N') service_tax_percent,
                                    if(ifnull(b.service_tax_ind,'N')='Y','(A) TAXABLE SERVICE','(B) NON TAXABLE SERVICE') service_tax_desc,
                                    ifnull(b.service_tax_amount,0) service_tax_amount
                                from billinfo_detail b
                                where b.ref_billinfo_serial_no  = '$ref_bill_serial_no'
                                and ifnull(b.printer_ind,'N') = 'Y'
                            order by ifnull(b.service_tax_ind,'N') desc, b.prn_seq_no ";
                $dtl_qry = $this->db->query($dtl_stmt)->getResultArray();
                // delete old record from temporary table
                $dele_stmt = "delete from $temp_table";
                $dele_qry = $this->temp_db->query($dele_stmt);
                // insert into temporary table
                $tot_inp_amount  = 0;
                $tot_out_amount  = 0;
                $tot_tax_amount  = 0;
                $tot_tot_amount  = 0;
                $tot_net_amount  = 0;
                $tot_srv_amount  = 0;
                $sub_inp_amount  = 0;
                    $sub_out_amount  = 0;
                    $sub_srv_amount  = 0;
                    $sub_tot_amount  = 0;
                    $sub_net_amount  = 0;
                    $sactivity_desc='';
                foreach($dtl_qry as  $dtl_row)
                {
                    $row_no            = $dtl_row['row_no'];
                    $activity_date     = $dtl_row['activity_date'];
                    $activity_desc     = stripslashes($dtl_row['activity_desc']) . chr(13);
                    $io_ind            = $dtl_row['io_ind'];
                    $source_code_dtl   = $dtl_row['source_code'];
                    $billed_amount     = $dtl_row['billed_amount'];
                    $serv_tax_ind      = $dtl_row['service_tax_ind'];
                    $serv_tax_per      = $dtl_row['service_tax_percent'];
                    $serv_tax_desc     = $dtl_row['service_tax_desc'];
                    $serv_tax_amount   = $dtl_row['service_tax_amount'];
                    $activity_date     = $dtl_row['activity_date'];
                    $activity_desc     = $dtl_row['activity_desc'];
                    $io_ind            = $dtl_row['io_ind'];
                    $source_code_dtl2  = $dtl_row['source_code'];
                    $billed_amount     = $dtl_row['billed_amount'];
                    $serv_tax_amount   = $dtl_row['service_tax_amount'];
                    $pserv_bill_date   = $bill_row['bill_date'];
                    $actvt_desc    = wordwrap($activity_desc, $tot_char, "\n");
                    $actvt_array   = explode("\n",$actvt_desc);
                    $row_cnt       = count($actvt_array);
                    
                    $ptaxind = 'Y';
                    $pserv_tax_ind  = $dtl_row['service_tax_ind'];
                    $pserv_tax_per  = $dtl_row['service_tax_percent'];
                    if ($service_tax_amount >0)
                      $pserv_tax_desc = $dtl_row['service_tax_desc'];
                    else
                      $pserv_tax_desc = '';
                      if($io_ind == 'O' && $source_code_dtl2 == 'M')
                      {
                        $sub_out_amount += $dtl_row['billed_amount'];
                      }
                      else
                      {
                        $sub_inp_amount += $dtl_row['billed_amount'];
                      }
             
                      $sub_tot_amount += $dtl_row['billed_amount'];
                      $sub_srv_amount += $dtl_row['service_tax_amount'];
                    // insertion of first line into temp table
                    $inst_stmt = "insert into $temp_table
                                    (row_no,activity_date,activity_desc,io_ind,billed_amount,service_tax_ind,service_tax_percent,service_tax_desc,service_tax_amount,source_code)
                                values($row_no,'$activity_date','".addslashes($actvt_array[0])."','$io_ind',$billed_amount,'$serv_tax_ind','$serv_tax_per','$serv_tax_desc','$serv_tax_amount','$source_code_dtl')";
                    $inst_qry = $this->temp_db->query($inst_stmt);
                    // insertion of rest lines into temp table
                    for($j=1;$j<$row_cnt;$j++)
                    {
                    $inst_stmt = "insert into $temp_table
                                            (row_no,activity_desc,service_tax_ind,service_tax_percent)
                                    values($row_no,'".addslashes($actvt_array[$j])."','$serv_tax_ind','$serv_tax_per')";
                    $inst_qry = $this->temp_db->query($inst_stmt);
                    }
                }
                // end of temporary table insertion

                // selection record from temp table
                $sele_stmt = "select date_format(activity_date,'%d-%m-%Y') activity_date,
                                    activity_desc,
                                    io_ind, source_code,
                                    ifnull(billed_amount,0) billed_amount,
                                    service_tax_ind,
                                    service_tax_percent,
                                    service_tax_desc,
                                    ifnull(service_tax_amount,0) service_tax_amount 
                                from $temp_table
                            order by service_tax_ind desc, srl_no, row_no ";
                            $sele_qry = $this->temp_db->query($sele_stmt)->getResultArray();

                $selecnt_sql = "select count(*) cnt from $temp_table " ; 
                $selecnt_qry = $this->temp_db->query($selecnt_sql)->getResultArray()[0];
                $selecnt_nos = $selecnt_qry['cnt'] ; 
                
                // $l_no = $l_no + 5 ;
         $tot_inp_amount  += $sub_inp_amount;
         $tot_out_amount  += $sub_out_amount;
         $tot_tot_amount  += $sub_tot_amount;
         $tot_srv_amount  += $sub_srv_amount;
         $tot_net_amount  += $sub_net_amount;
                $page_no = 1 ;
                return view("pages/Query/rep_final_bill_tax", compact("option","prop_ind","bill_fin_year","bill_no","bill_date",
                "dupl_ind","revd_ind","copy_ind","page_no","client_code","client_name","trust_name","address_line_1","city","pin_code","pan_no",
                "state_name","bill_row","client_gst","bill_attn_code","attention_name","designation","matter_name","other_case_desc",
                "source_code","reference_desc","subject_desc","activity_desc","tot_char","pserv_tax_desc","activity_date",
                "billed_amount","io_ind","source_code_dtl2","dtl_row","pserv_tax_per","sele_qry","tot_inp_amount","tot_net_amount",
                "direct_counsel_ind","branch_pan_no","service_tax_amount","service_nature","service_dec","tot_tot_amount","tot_srv_amount",
                "row_count","selecnt_nos","tot_no_of_lines","branch_service_tax","final_bill_date","no_fee_bill_ind"));
            }
  
    }
    public function bill_not_approved_rp($option = 'list')
    { 
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $defaultdate="01-04-".$splitdate[0];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        $ason_date = isset($_REQUEST['ason_date']) ? $_REQUEST['ason_date'] : null;
        $branch_code = isset($_REQUEST['branch_code']) ? $_REQUEST['branch_code'] : null;
        $court_code = isset($_REQUEST['court_code']) ? $_REQUEST['court_code'] : null;
        $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
        $court_name = isset($_REQUEST['court_name']) ? $_REQUEST['court_name'] : null;
        $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
        $client_name = isset($_REQUEST['client_name']) ? $_REQUEST['client_name'] : null;
        $matter_code = isset($_REQUEST['matter_code']) ? $_REQUEST['matter_code'] : null;
        $matter_desc = isset($_REQUEST['matter_desc']) ? $_REQUEST['matter_desc'] : null;
        $output_type = isset($_REQUEST['output_type']) ? $_REQUEST['output_type'] : null;
        if($branch_code == ''){  $branch_code ='%';  }
        if($court_code  == ''){  $court_code ='%';   }
        if($client_code == ''){  $client_code ='%';  }
        if($matter_code == ''){  $matter_code ='%';  }

        if($client_name == ''){  $client_name ='ALL';  }
        if($court_name == '') {  $court_name ='ALL';  }
        if($matter_desc == ''){  $matter_desc ='ALL';  }
        $report_desc      = "LIST OF BILL(S) GENERATED BUT NOT YET APPROVED AS ON " ;
        if($this->request->getMethod() == 'post') 
        {
            if($output_type=='Report')
            { 
                 $report_sql = "select     d.client_name
                ,a.serial_no
                ,a.bill_date
                ,(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0)) bill_amount
                ,b.matter_code
                ,if(b.matter_desc1 != '',concat(b.matter_desc1,' : ',b.matter_desc2),b.matter_desc2) matter_desc
                ,c.code_desc  court_desc
                ,a.client_code
          from   billinfo_header a
                ,fileinfo_header b
                ,code_master c
                ,client_master d
         where   a.branch_code like '$branch_code'
           and   a.client_code like '$client_code' 
           and   a.matter_code like '$matter_code' 
           and   a.status_code = 'A'  
           and   a.matter_code = b.matter_code
           and   a.client_code = d.client_code
           and   b.court_code  = c.code_code
           and   c.code_code   like '$court_code'
           and   c.type_code   = '001'
      order by   a.bill_date desc,a.client_code";
      $bill_qry = $this->db->query($report_sql)->getResultArray();
      return view("pages/Query/bill_not_approved_rp", compact("bill_qry","report_desc"));
            }
            if($output_type=='Excel')
            {
                $report_sql = "select     d.client_name  client 
                ,a.serial_no       bill_no
                ,date_format(a.bill_date,'%d-%m-%Y')    bill_date
                
                ,(ifnull(a.bill_amount_inpocket,0) + ifnull(a.bill_amount_outpocket,0) + ifnull(a.bill_amount_counsel,0)) amount
                ,b.matter_code
                ,if(b.matter_desc1 != '',concat(b.matter_desc1,' : ',b.matter_desc2),b.matter_desc2) matter_desc
                ,c.code_desc  court_desc
          from   billinfo_header a
                ,fileinfo_header b
                ,code_master c
                ,client_master d
         where   a.branch_code like '$branch_code'
           and   a.client_code like '$client_code' 
           and   a.matter_code like '$matter_code' 
           and   a.status_code = 'A'  
           and   a.matter_code = b.matter_code
           and   a.client_code = d.client_code
           and   b.court_code  = c.code_code
           and   c.code_code   like '$court_code'
           and   c.type_code   = '001'
      order by   a.bill_date,a.serial_no";
      $bill_qry = $this->db->query($report_sql)->getResultArray();
      $bill_cnt  = count($bill_qry);
      try {
        $bill_qry[0];
        if($bill_cnt == 0)  throw new \Exception('No Records Found !!');

          } catch (\Exception $e) {
              session()->setFlashdata('message', 'No Records Found !!');
              return redirect()->to($this->requested_url());
          }
          $fileName = 'BILL NOT APPROVED-'.date('d-m-Y').'.xlsx';  
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
          // Define the headings
          $headings = ['Bill Srl', 'Bill Date', 'Matter', 'Desc', 'Court', 'Amount'];
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
                foreach ($bill_qry as $excel){
                    $sheet->setCellValue('A' . $rows, $excel['bill_no']);
                    $sheet->setCellValue('B' . $rows, $excel['bill_date']);
                    $sheet->setCellValue('C' . $rows, strtoupper($excel['matter_code']));
                    $sheet->setCellValue('D' . $rows, strtoupper($excel['matter_desc']));
                    $sheet->setCellValue('E' . $rows, strtoupper($excel['court_desc']));
                    $sheet->setCellValue('F' . $rows, number_format($excel['amount'],2,'.',''));
                    
                    // Apply border to the current row
                    $style = $sheet->getStyle('A' . $rows . ':H' . $rows);
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
        }
    }
    public function case_status_query_details($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $defaultdate="01-04-".$splitdate[0];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        $branch_code      = $data['branch_code']['branch_code'];
        $branch_name      = $data['branch_code']['branch_name'];
        $query_id = isset($_REQUEST['query_id']) ? $_REQUEST['query_id'] : null;
        $client_searchind = isset($_REQUEST['client_searchind']) ? $_REQUEST['client_searchind'] : null;
        $client_searchval = isset($_REQUEST['client_searchval']) ? $_REQUEST['client_searchval'] : null;
        if($this->request->getMethod() == 'post') 
        if($query_id=='qry_0010'){ 
           
            $client_qry = "select * from client_master where client_code like concat('client_searchval','%') or client_name like concat('$client_searchval','%') order by client_name";
            $client_qry = $this->db->query($client_qry)->getResultArray();
            $sql="select query_id,query_name from system_query where query_module_code = '$query_module_code' and status_code = 'A' order by query_name ";
            $data = $this->db->query($sql)->getResultArray();
             return view("pages/Query/case_details_client_matter_wise", compact("options","option","data","client_qry"));
        }
        if($query_id=='qry_0011')
        {
            $qry = "select max( activity_date ) acty, serial_no, last_day( max( activity_date ) ) lst_dt FROM case_header GROUP BY serial_no ORDER BY acty DESC , serial_no DESC LIMIT 0 , 1"; 
            $qry1 = $this->db->query($qry)->getResultArray()[0];
            $date_to    = $qry1['lst_dt'];
            $srl_no     = $qry1['serial_no'];
            $acy_date   = $qry1['acty'];
            $date_from1 = '01-'.substr($acy_date,5,2).'-'.substr($acy_date,0,4);
            $date_from  = date_conv($date_from1);
            $res ="select * FROM case_header WHERE activity_date between '$date_from' and '$date_to' ORDER BY activity_date desc, serial_no desc";
            $res = $this->db->query($res)->getResultArray();
            return view("pages/Query/case_status_client_date_wise", compact("options","option","res"));     
        }
        else
        {
            switch ($option) {
                case 'list':
                    $sql="select query_id,query_name from system_query where query_module_code = '$query_module_code' and status_code = 'A' order by query_name ";
                    break;
            }
            $data = $this->db->query($sql)->getResultArray();
            return view("pages/Query/case_status_query_details", compact("options","option", "data"));
        } 
    }
    public function case_details_client_matter_view($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $defaultdate="01-04-".$splitdate[0];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        $branch_code      = $data['branch_code']['branch_code'];
        $branch_name      = $data['branch_code']['branch_name'];
        $query_id = isset($_REQUEST['query_id']) ? $_REQUEST['query_id'] : null;
        $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
        $client_name = isset($_REQUEST['client_name']) ? $_REQUEST['client_name'] : null;
        $matter_searchval = isset($_REQUEST['matter_searchval']) ? $_REQUEST['matter_searchval'] : null;
        if($this->request->getMethod() == 'post') 
       {
            $matter_sql = "select matter_code, concat(matter_desc1,' : ',matter_desc2) matter_desc from fileinfo_header where client_code = '$client_code' and (matter_code like concat('$matter_searchval','%') or concat(matter_desc1,matter_desc2) like concat('$matter_searchval','%')) order by matter_code"  ;
            $matter_sql = $this->db->query($matter_sql)->getResultArray();
            return view("pages/Query/case_details_client_matter_view", compact("options","option","matter_sql","client_code","client_name"));   
               
       }
    }
    public function case_details_matter_view($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $defaultdate="01-04-".$splitdate[0];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        $display_id = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $menu_id = isset($_REQUEST['menu_id']) ? $_REQUEST['menu_id'] : null;
        $query_module_code = isset($_REQUEST['display_id']) ? $_REQUEST['display_id'] : null;
        $options = isset($_REQUEST['options']) ? $_REQUEST['options'] : null;
        $branch_code      = $data['branch_code']['branch_code'];
        $branch_name      = $data['branch_code']['branch_name'];
        $query_id = isset($_REQUEST['query_id']) ? $_REQUEST['query_id'] : null;
        $client_code = isset($_REQUEST['client_code']) ? $_REQUEST['client_code'] : null;
        $client_name = isset($_REQUEST['client_name']) ? $_REQUEST['client_name'] : null;
        $matter_code = isset($_REQUEST['matter_code']) ? $_REQUEST['matter_code'] : null;
        $matter_desc = isset($_REQUEST['matter_desc']) ? $_REQUEST['matter_desc'] : null;  
        $start_date_ymd = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : null;  
        $end_date_ymd = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : null;  
        $op = isset($_REQUEST['op']) ? $_REQUEST['op'] : null;  
        if($this->request->getMethod() == 'post') 
        {
            if($op!='search')
            {
                $case_qry = "select * from case_header where client_code like concat('$client_code','%') and matter_code like concat('$matter_code','%')  order by activity_date,serial_no " ;
                $case_qry = $this->db->query($case_qry)->getResultArray();
             return view("pages/Query/case_details_matter_view", compact("options","option","case_qry","client_code","client_name","matter_desc"));   
            } 
            else
            {
                $case_qry = "select * from case_header where client_code like concat('$client_code','%') and matter_code like concat('$matter_code','%') and activity_date between '$start_date_ymd' and '$end_date_ymd' order by activity_date,serial_no " ;
                $case_qry = $this->db->query($case_qry)->getResultArray();
                return view("pages/Query/case_details_matter_view", compact("options","option","case_qry","client_code","client_name","matter_desc"));   
            }
        }
        
        
        
    }
    public function case_status_client_date_wise($option = 'list')
    {
        $sql = '';
        $sql1 = '';
        $session = session();
        $sessionName = $session->userId;
        $data = branches($sessionName);
        $splitdate=explode('-',$session->financialYear);
        $defaultdate="01-04-".$splitdate[0];
        $data['requested_url'] = $this->session->requested_end_menu_url;
        if($this->request->getMethod() == 'post') 
        {
            $qry = $this->db->query("select max( activity_date ) acty, serial_no, last_day( max( activity_date ) ) lst_dt FROM case_header GROUP BY serial_no ORDER BY acty DESC , serial_no DESC LIMIT 0 , 1"); 
            $date_to    = $qry['lst_dt'];
            $srl_no     = $qry['serial_no'];
            $acy_date   = $qry['acty'];
            $date_from1 = '01-'.substr($acy_date,5,2).'-'.substr($acy_date,0,4);
            $date_from  = date_conv($date_from1);
       echo     $res ="select * FROM case_header WHERE activity_date between '$date_from' and '$date_to' ORDER BY activity_date desc, serial_no desc";die;
            return view("pages/Query/case_status_client_date_wise", compact("options","option"));   
        }
    }
    
    
}

?>